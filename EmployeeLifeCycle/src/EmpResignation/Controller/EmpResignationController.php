<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace EmpResignation\Controller;

use EmpResignation\Service\EmpResignationServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use EmpResignation\Form\EmpResignationForm;
use EmpResignation\Form\DuesForm;
use EmpResignation\Form\SeparationForm;
use EmpResignation\Form\SeparationRecordForm;
use EmpResignation\Form\EmployeeSearchForm;
use EmpResignation\Model\EmpResignation;
use EmpResignation\Model\Dues;
use EmpResignation\Model\Separation;
use EmpResignation\Model\SeparationRecord;
use Zend\Session\Container;
use Zend\Http\Response\Stream;
use Zend\Http\Headers;
use Zend\View\Model\JsonModel;

/**
 * Description of IndexController
 *
 */
 
class EmpResignationController extends AbstractActionController
{
	protected $resignationService;
	protected $notificationService;
	protected $auditTrailService;
	protected $serviceLocator;
	protected $emailService;
	protected $username;
	protected $userrole;
	protected $usertype;
	protected $userregion;
	protected $userDetails;
	protected $userImage;
	protected $employee_details_id;
	protected $organisation_id;
	protected $departments_id;
	protected $departments_units_id;
	protected $keyphrase = "RUB_IMS";
	
	public function __construct(EmpResignationServiceInterface $resignationService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->resignationService = $resignationService;
		$this->notificationService = $notificationService;
        $this->auditTrailService = $auditTrailService;
        $this->emailService = $serviceLocator->get('Application\Service\EmailService');
		
		/*
		 * To retrieve the user name from the session
		*/
		$authPlugin = $serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
        $this->username = $authPlugin['username'];
        $this->userrole = $authPlugin['role'];
        $this->userregion = $authPlugin['region'];
        $this->usertype = $authPlugin['user_type_id'];
				
		/*
		* Getting the employee_details_id related to username
		*/
		
		$empData = $this->resignationService->getEmployeeDetailsId($this->username);
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			$this->departments_units_id = $emp['departments_units_id'];
			$this->departments_id = $emp['departments_id'];
		}
		
		//get the organisation id
		$organisationID = $this->resignationService->getOrganisationId($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}
		
		//get the user details such as name
		$this->userDetails = $this->resignationService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->resignationService->getUserImage($this->username, $this->usertype);
	}
	
	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }
    
	public function applyResignationAction()
    {
		$this->loginDetails();
		
        $form = new EmpResignationForm();
		$resignationModel = new EmpResignation();
		$form->bind($resignationModel);
		
		$resignation_details = $this->resignationService->getEmployeeResigningDetails($this->employee_details_id);
		/*
		* Get the notification details, i.e. submission to and submission to department
		*/
		$submission_to = $this->resignationService->getNotificationDetails($this->organisation_id);

		$resignationType = $this->resignationService->listSelectData($tableName = 'resignation_type', $columnName = 'resignation_type');

		$message = NULL;
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             $check_resignation_application = $this->resignationService->crossCheckResignationApplication($this->employee_details_id, NULL);
             $check_resignation_approval = $this->resignationService->crossCheckResignationApplication($this->employee_details_id, 'Approved');
             
             if($check_resignation_application||$check_resignation_approval){
             	$message = 'Failure';
             	$this->flashMessenger()->addMessage("You can't apply for resignation since you have already applied for resignation and it is still pending or it has been approved!");
             }else{
             	if ($form->isValid()) {
					$data = $form->getData();
					$resignationType = $resignationModel->getResignation_Type(); 
	                 try {
						 $this->resignationService->save($resignationModel);
						 $this->sendAppliedResignationEmail($this->employee_details_id, $this->departments_units_id, $this->userrole, $this->organisation_id, $resignationType);
						 $this->flashMessenger()->addMessage('Resignation Application was successfully added');
						 $this->notificationService->saveNotification('Resignation Application', $submission_to, NULL, 'Resignation by Employee');
						 $this->auditTrailService->saveAuditTrail("INSERT", "Resignation Application", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('empresignation');
					 }
					 catch(\Exception $e) {
					 	$message = 'Failure';
					 	$this->flashMessenger()->addMessage($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
	             }
             }
         }
		 
        return array(
			'form' => $form,
			'employee_details_id' => $this->employee_details_id,
			 'resignation_details' => $resignation_details,
			 'resignationType' => $resignationType,
			 'message' => $message,
			 'keyphrase' => $this->keyphrase
			);
	}
	
	//Function to send email to resignation approving authority
	public function sendAppliedResignationEmail($employee_details_id, $departments_units_id, $userrole, $organisation_id, $resignationType)
	{
		$this->loginDetails();

    	$supervisor_email = $this->resignationService->getSupervisorEmailId($userrole, $departments_units_id);

	 	$applicant_name = NULL;
	 	$applicant = $this->resignationService->getRecordedResignedEmpDetails($employee_details_id, $organisation_id);
	 	foreach($applicant as $temp){
	 		$applicant_name = $temp['first_name'].' '.$temp['middle_name'].' '.$temp['last_name'];
		 }
		 
		$type = $this->resignationService->getResignationType($resignationType);

	 	foreach($supervisor_email as $email){
	 		$toEmail = $email;
	        $messageTitle = "New Resignation Application";
			$messageBody = "Dear Sir/Madam,<br><h3>".$applicant_name." has applied for resignation type ".$type." on ".date('Y-m-d').".</h3><br><b>Please click the link below for necessary action.</b><br><u>http://ims.rub.edu.bt</u><p>This is an auto-generated email and Please do not reply because this mail id is not monitored</p>";

	        $this->emailService->sendMailer($toEmail, $messageTitle, $messageBody);
	 	}
	}
	
	public function resignationApprovalListAction()
    {
		$this->loginDetails();
		
        $form = new EmpResignationForm();
		$resignationModel = new EmpResignation();
		$form->bind($resignationModel);
		
		$resignationList = $this->resignationService->listAll($this->userrole, $tableName='emp_resignation', $this->organisation_id, NULL);
		
		//the details of employees that have applied for resignation
		$resigningEmployee = $this->resignationService->getResigningEmployee($this->organisation_id);

		$message = NULL;
		
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->resignationService->save($resignationModel);
					 $this->flashMessenger()->addMessage('Resignation Approval was approved');
					 $this->notificationService->saveNotification('Resignation Application', 'ALL', 'ALL', 'Resignation Application Approval');
					 $this->auditTrailService->saveAuditTrail("INSERT", "Resignation Application Approval", "ALL", "SUCCESS");
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'keyphrase' => $this->keyphrase,
			'resignationList' => $resignationList,
			'resigningEmployee' => $resigningEmployee,
			'message' => $message,
		);
    }
	
	public function viewResignationDetailsAction()
    {
		$this->loginDetails();
		
        //get the resignation id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new EmpResignationForm();
		
			$resignationDetails = $this->resignationService->getResignationDetails($id);
					 
			return array(
				'form' => $form,
				'resignationDetails' => $resignationDetails);
		}
		else {
			return $this->redirect()->toRoute('resignationapprovallist');
		}
    }

	
	public function editResignationDetailsAction()
    {
		$this->loginDetails();

		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new EmpResignationForm();
			$resignationModel = new EmpResignation();
			$form->bind($resignationModel);

			$resignationType = $this->resignationService->listSelectData($tableName = 'resignation_type', $columnName = 'resignation_type');

			$resignationDetails = $this->resignationService->getResignationApplicationDetails($id);
			
	        $request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) {
	                 try {
						 $this->resignationService->save($resignationModel);
						 $this->flashMessenger()->addMessage('Resignation Application was successfully edited');
						 $this->auditTrailService->saveAuditTrail("EDIT", "Resignation Application", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('empresignation');
					 }
					 catch(\Exception $e) {
					 	$message = 'Failure';
					 	$this->flashMessenger()->addMessage($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
	             }
	         }
			 
	        return array(
	        	'id' => $id,
				'form' => $form,
				'resignationDetails' => $resignationDetails,
				'resignationType' => $resignationType,
				'employee_details_id' => $this->employee_details_id,
				// 'resignationList' => $resignationList,
				//'resigningEmployee' => $resigningEmployee,
			);
		}else{
			return $this->redirect()->toRoute('empresignation');
		}
    }
	
	public function deleteResignationDetailsAction()
    {
		$this->loginDetails();

        //get the student id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
        	try{
                $this->resignationService->deleteEmployeeResignation($id);
                $this->auditTrailService->saveAuditTrail("DELETE", "Employee Resignation", "ALL", "SUCCESS");

                $this->flashMessenger()->addMessage('You have delected your resignation application successfully');
                return $this->redirect()->toRoute('empresignation');
        	}
        	catch(\Exception $e) {
        		$message = 'Failure';
        		$this->flashMessenger()->addMessage($e->getMessage());
            }

        return array(
        	'id' => $id,
        	'message' => $message,
        );

        }else{
            return $this->redirect()->toRoute('empresignation');
        }
    }
	
	public function approveResignationAction()
	{
		$this->loginDetails();
		
		//get the id of the hrd proposal
		$id_from_route = $this->params()->fromRoute('id');
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);

		$emp_det_id_from_route = $this->params()->fromRoute('employee_details_id');
		$employee_details_id = $this->my_decrypt($emp_det_id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			try {
				 $this->resignationService->updateResignationStatus($id, 'Approved');
				 $this->sendEmpRecordResignationMail($employee_details_id);
				 $this->flashMessenger()->addMessage('Staff Resignation was successfully approved');
				 return $this->redirect()->toRoute('resignationapprovallist');
			 }
			 catch(\Exception $e) {
					 die($e->getMessage());
					 // Some DB Error happened, log it and let the user know
			 }
			return array();
		}
		else {
			return $this->redirect()->toRoute('resignationapprovallist');
		}
	}
	
	public function rejectResignationAction()
	{
		$this->loginDetails();
		
		//get the id of the hrd proposal
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			try {
				 $this->resignationService->updateResignationStatus($id, 'Rejected');
				 $this->flashMessenger()->addMessage('Staff Resignation was successfully rejected');
				 return $this->redirect()->toRoute('resignationapprovallist');
			 }
			 catch(\Exception $e) {
					 die($e->getMessage());
					 // Some DB Error happened, log it and let the user know
			 }
			return array();
		}
		else {
			return $this->redirect()->toRoute('resignationapprovallist');
		}
	}
	
	public function resignationRecordAction()
    {
		$this->loginDetails();
		
        $form = new EmpResignationForm();
		$resignationModel = new EmpResignation();
		$form->bind($resignationModel);
		
		$resignationList = $this->resignationService->listAll(NULL, $tableName='emp_resignation', $this->organisation_id, 'Approved');
		
		//the details of employees that have applied for resignation
		$resigningEmployee = $this->resignationService->getResigningEmployee($this->organisation_id);
		$employeeDueClearance = $this->resignationService->getDueClearance($this->organisation_id);
		
		$store_clearance_authority = $this->resignationService->getAuthorisingRole('Store', $this->organisation_id);
		$estate_clearance_authority = $this->resignationService->getAuthorisingRole('Estate', $this->organisation_id);
		$accounts_clearance_authority = $this->resignationService->getAuthorisingRole('Accounts', $this->organisation_id);
		$library_clearance_authority = $this->resignationService->getAuthorisingRole('Library', $this->organisation_id);
		$it_clearance_authority = $this->resignationService->getAuthorisingRole('IT Store', $this->organisation_id);
		
		$message = NULL;
				 
        return array(
			'form' => $form,
			'keyphrase' => $this->keyphrase,
			'userrole' => $this->userrole,
			'organisation_id' => $this->organisation_id,
			'resignationList' => $resignationList,
			'resigningEmployee' => $resigningEmployee,
			'employeeDueClearance' => $employeeDueClearance,
			'store_clearance_authority' => $store_clearance_authority,
			'estate_clearance_authority' => $estate_clearance_authority,
			'accounts_clearance_authority' => $accounts_clearance_authority,
			'library_clearance_authority' => $library_clearance_authority,
			'it_clearance_authority' => $it_clearance_authority,
			'message' => $message
			);
    }


    public function recordResignedEmployeeAction()
    {
       $this->loginDetails();

       $form = new EmployeeSearchForm();

       $employeeList = $this->resignationService->listAllEmployees($this->organisation_id);
	   	   
	   $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$empName = $this->getRequest()->getPost('employee_name');
				$empId = $this->getRequest()->getPost('emp_id');
				$department = $this->getRequest()->getPost('department');
				$employeeList = $this->resignationService->getEmployeeList($empName, $empId, $department, $this->organisation_id);
             }
         }
		
		return new ViewModel(array(
            'form' => $form,
			'keyphrase' => $this->keyphrase,
			'employeeList' => $employeeList
            ));
    }


    public function recordResignedEmpDetailsAction()
    {
    	$this->loginDetails();

    	$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new EmpResignationForm();
			$resignationModel = new EmpResignation();
			$form->bind($resignationModel);
			
			$employeeDetails = $this->resignationService->getResignedEmpDetails($id);

			$submission_to = $this->resignationService->getNotificationDetails($this->organisation_id);

			$resignationType = $this->resignationService->listSelectData($tableName = 'resignation_type', $columnName = 'resignation_type');

			$message = NULL;
	        
	        $request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());

	             $check_resignation_application = $this->resignationService->crossCheckResignationApplication($id, 'Approved');
	             
	             if($check_resignation_application){
	             	$message = 'Failure';
	             	$this->flashMessenger()->addMessage("You cannot record resignation details for this particular staff since you have already recorded.");
	             }else{
	             	if ($form->isValid()) {
		                 try {
							 $this->resignationService->save($resignationModel);
							 $this->sendEmpRecordResignationMail($id);
							 $this->flashMessenger()->addMessage('You have recorded resignation successfully');
							 $this->notificationService->saveNotification('Resignation Record', $submission_to, NULL, 'Resignation by Employee');
							 $this->auditTrailService->saveAuditTrail("INSERT", "Resignation Record", "ALL", "SUCCESS");
							 return $this->redirect()->toRoute('recordresignedemployee');
						 }
						 catch(\Exception $e) {
						 	$message = 'Failure';
						 	$this->flashMessenger()->addMessage($e->getMessage());
								 // Some DB Error happened, log it and let the user know
						 }
		             }
	             }
	         }
			 
	        return array(
				'form' => $form,
				'employee_details_id' => $id,
				'employeeDetails' => $employeeDetails,
				'resignationType' => $resignationType,
				'message' => $message,
				'keyphrase' => $this->keyphrase
				);
		}else{
			return $this->redirect()->toRoute('recordresignedemployee');
		}
    }


    public function sendEmpRecordResignationMail($employee_details_id)
    {
    	$this->loginDetails();

    	$staff_name = NULL;
    	$departments_units = NULL;
    	$departments = NULL;
    	$staff_role = NULL;
    	$staff_details = $this->resignationService->getRecordedResignedEmpDetails($employee_details_id, $this->organisation_id);
    	foreach($staff_details as $details){
    		$staff_name = $details['first_name'].' '.$details['middle_name'].' '.$details['last_name'];
    		$departments_units = $details['departments_units_id'];
    		$departments = $details['departments_id'];
    		$staff_role = $details['role'];
    	}  

    	$authorizee_emails = $this->resignationService->getIssuingAuthorityEmails($staff_role, $this->organisation_id, $departments_units);

    	foreach($authorizee_emails as $email){
    		$toEmail = $email;
	        $messageTitle = "New Resignation Record";
			$messageBody = "Dear Sir/Madam,<br><h3>".$staff_name." resignation was recorded on ".date('Y-m-d')." and approved.</h3><br><b>Please click the link below for necessary action.</b><br><u>http://ims.rub.edu.bt</u><p>This is an auto-generated email and Please do not reply because this mail id is not monitored</p>";

	        $this->emailService->sendMailer($toEmail, $messageTitle, $messageBody);
    	}
    }

	
	public function separationRecordAction()
    {
		$this->loginDetails();
		
        $form = new EmpResignationForm();
		$resignationModel = new EmpResignation();
		$form->bind($resignationModel);
		
		$resignationList = $this->resignationService->listAll(NULL, $tableName='emp_resignation', $this->organisation_id, 'Approved');
		
		//the details of employees that have applied for resignation
		$resigningEmployee = $this->resignationService->getResigningEmployee($this->organisation_id);
		$employeeDueClearance = $this->resignationService->getDueClearance($this->organisation_id);
		$store_clearance_authority = $this->resignationService->getAuthorisingRole('Store', $this->organisation_id);
		$estate_clearance_authority = $this->resignationService->getAuthorisingRole('Estate', $this->organisation_id);
		$accounts_clearance_authority = $this->resignationService->getAuthorisingRole('Accounts', $this->organisation_id);
		$library_clearance_authority = $this->resignationService->getAuthorisingRole('Library', $this->organisation_id);
		$it_clearance_authority = $this->resignationService->getAuthorisingRole('IT Store', $this->organisation_id);
		
		$message = NULL;
		 
        return array(
			'form' => $form,
			'keyphrase' => $this->keyphrase,
			'userrole' => $this->userrole,
			'organisation_id' => $this->organisation_id,
			'resignationList' => $resignationList,
			'employeeDueClearance' => $employeeDueClearance,
			'resigningEmployee' => $resigningEmployee,
			'store_clearance_authority' => $store_clearance_authority,
			'estate_clearance_authority' => $estate_clearance_authority,
			'accounts_clearance_authority' => $accounts_clearance_authority,
			'library_clearance_authority' => $library_clearance_authority,
			'it_clearance_authority' => $it_clearance_authority,
			'message' => $message);
    }
	
	public function issueSeparationRecordAction()
    {
		$this->loginDetails();
		
        //get the resignation id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new SeparationRecordForm();
			$resignationModel = new SeparationRecord();
			$form->bind($resignationModel);
			
			$resignationDetails = $this->resignationService->getResignationDetails($id);
			$employeeDueClearance = $this->resignationService->getDueClearance($this->organisation_id);
			
			//For notifying relevant authorities when a separation record is issued
			$store_clearance_authority = $this->resignationService->getAuthorisingRole('Store', $this->organisation_id);
			$accounts_clearance_authority = $this->resignationService->getAuthorisingRole('Accounts', $this->organisation_id);
			$library_clearance_authority = $this->resignationService->getAuthorisingRole('Library', $this->organisation_id);
			$estate_clearance_authority = $this->resignationService->getAuthorisingRole('Estate', $this->organisation_id);
			$workshop_clearance_authority = $this->resignationService->getAuthorisingRole('Workshop', $this->organisation_id);
			
			$request = $this->getRequest();
			if($request->isPost()){
				$form->setData($request->getPost());
				 $data = array_merge_recursive(
					$request->getPost()->toArray(),
					$request->getFiles()->toArray()
				 ); 
				 $form->setData($data); 
				 if ($form->isValid()) {
					 //$data = $form->getData();
					 try {
						 $this->resignationService->saveSeparationRecord($resignationModel);
						 $this->flashMessenger()->addMessage('Separation Order was successfully issued');
						 $this->notificationService->saveNotification('Separation Record Application', $store_clearance_authority, NULL, 'Separation Record');
						 $this->notificationService->saveNotification('Separation Record Application', $accounts_clearance_authority, NULL, 'Separation Record');
						 $this->notificationService->saveNotification('Separation Record Application', $library_clearance_authority, NULL, 'Separation Record');
						 $this->notificationService->saveNotification('Separation Record Application', $estate_clearance_authority, NULL, 'Separation Record');
						 $this->notificationService->saveNotification('Separation Record Application', $workshop_clearance_authority, NULL, 'Separation Record');
						 $this->auditTrailService->saveAuditTrail("INSERT", "Issuance of Separation Record", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('separationrecordlist');
					 }
					 catch(\Exception $e) {
					 	$message = 'Failure';
					 	$this->flashMessenger()->addMessage($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			}
			 
			return array(
				'form' => $form,
				'userrole' => $this->userrole,
				'organisation_id' => $this->organisation_id,
				'employeeDueClearance' => $employeeDueClearance,
				'resignationDetails' => $resignationDetails,
			);
		}
		else {
			return $this->redirect()->toRoute('separationrecord');
		}
    }


    public function viewSeparationRecordListAction()
    {
    	$this->loginDetails();

    	$message = NULL;

    	return new ViewModel(array(
    		'separationRecordList' => $this->resignationService->getSeparationRecordList($tableName = 'emp_separation_record', $this->organisation_id),
    		'message' => $message,
    		'keyphrase' => $this->keyphrase,

    	));
    }
	
	//Dues Clearance
	public function issueStoreDuesAction()
	{
		$this->loginDetails();
		
		//get the resignation id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new DuesForm();
			$resignationModel = new Dues();
			$form->bind($resignationModel); 
			
			$empGoods = $this->resignationService->getEmpGoods($id, NULL); 
			$store_clearance_authority = $this->resignationService->getAuthorisingRole('Store', $this->organisation_id);

			$message = NULL;
			
			//For notification to the resigning employee
			$employee_id = NULL;
			$resignationDetails = $this->resignationService->getResignationDetails($id);
			foreach($resignationDetails as $temp){
				$employee_id = $temp['employee_details_id'];
			}
	
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
						 $this->resignationService->saveDueClearance($resignationModel);
						 $this->flashMessenger()->addMessage('Store Due Clearance successfully issued');
						 $this->notificationService->saveNotification('Dues Record', $employee_id, NULL, 'Issuance of Store Dues');
						 $this->auditTrailService->saveAuditTrail("INSERT", "Store Dues Issue", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('resignationrecord');
						 
					 }
					 catch(\Exception $e) {
					 	$message = 'Failure';
					 	$this->flashMessenger()->addMessage($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			 
			return array(
				'id' => $id,
				'form' => $form,
				'empGoods' => $empGoods,
				'store_clearance_authority' => $store_clearance_authority,
				'emp_resignation_id' => $id,
				'message' => $message,
			);
		}
		else {
			return $this->redirect()->toRoute('resignationrecord');
		}
	}
	
	public function issueAccountsDuesAction()
	{
		$this->loginDetails();
		
		//get the resignation id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new DuesForm();
			$resignationModel = new Dues();
			$form->bind($resignationModel);
			
			$empGoods = $this->resignationService->getEmpGoods($id, NULL);
			$accounts_clearance_authority = $this->resignationService->getAuthorisingRole('Accounts', $this->organisation_id);
			
			//For notification to the resigning employee
			$employee_id = NULL;
			$resignationDetails = $this->resignationService->getResignationDetails($id);
			foreach($resignationDetails as $temp){
				$employee_id = $temp['employee_details_id'];
			}

			$message = NULL;
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
						 $this->resignationService->saveDueClearance($resignationModel);
						 $this->flashMessenger()->addMessage('Accounts Due Clearance successfully issued');
						 $this->notificationService->saveNotification('Dues Record', $employee_id, NULL, 'Issuance of Accounts Dues');
						 $this->auditTrailService->saveAuditTrail("INSERT", "Accounts Dues Issue", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('resignationrecord');
					 }
					 catch(\Exception $e) {
							$message = 'Failure';
					 		$this->flashMessenger()->addMessage($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			 
			return array(
				'form' => $form,
				'empGoods' => $empGoods,
				'accounts_clearance_authority' => $accounts_clearance_authority,
				'emp_resignation_id' => $id,
				'message' => $message,
			);
		}
		else {
			return $this->redirect()->toRoute('resignationrecord');
		}		
	}


	public function issueItDuesAction()
	{
		$this->loginDetails();
		
		//get the resignation id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new DuesForm();
			$resignationModel = new Dues();
			$form->bind($resignationModel);
			
			$empGoods = $this->resignationService->getEmpGoods($id, '5');
			$it_clearance_authority = $this->resignationService->getAuthorisingRole('IT Store', $this->organisation_id);
			
			//For notification to the resigning employee
			$employee_id = NULL;
			$resignationDetails = $this->resignationService->getResignationDetails($id);
			foreach($resignationDetails as $temp){
				$employee_id = $temp['employee_details_id'];
			}

			$message = NULL;
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
						 $this->resignationService->saveDueClearance($resignationModel);
						 $this->flashMessenger()->addMessage('IT Due Clearance successfully issued');
						 $this->notificationService->saveNotification('Dues Record', $employee_id, NULL, 'Issuance of IT Dues');
						 $this->auditTrailService->saveAuditTrail("INSERT", "IT Dues Issue", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('resignationrecord');
					 }
					 catch(\Exception $e) {
							$message = 'Failure';
					 		$this->flashMessenger()->addMessage($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			 
			return array(
				'form' => $form,
				'empGoods' => $empGoods,
				'it_clearance_authority' => $it_clearance_authority,
				'emp_resignation_id' => $id,
				'message' => $message,
			);
		}
		else {
			return $this->redirect()->toRoute('resignationrecord');
		}
	}

	
	public function issueLibraryDuesAction()
	{
		$this->loginDetails();
		
		//get the resignation id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new DuesForm();
			$resignationModel = new Dues();
			$form->bind($resignationModel);
			
			//$empGoods = $this->resignationService->getEmpGoods($id);
			$library_clearance_authority = $this->resignationService->getAuthorisingRole('Library', $this->organisation_id);
			
			//For notification to the resigning employee
			$employee_id = NULL;
			$resignationDetails = $this->resignationService->getResignationDetails($id);
			foreach($resignationDetails as $temp){
				$employee_id = $temp['employee_details_id'];
			}
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
						 $this->resignationService->saveDueClearance($resignationModel);
						 $this->flashMessenger()->addMessage('Library Due Clearance successfully issued');
						 $this->notificationService->saveNotification('Dues Record', $employee_id, NULL, 'Issuance of Library Dues');
						 $this->auditTrailService->saveAuditTrail("INSERT", "Library Dues Issue", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('resignationrecord');
					 }
					 catch(\Exception $e) {
					 	$message = 'Failure';
					 	$this->flashMessenger()->addMessage($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			 
			return array(
				'form' => $form,
				//'empGoods' => $empGoods,
				'library_clearance_authority' => $library_clearance_authority,
				'emp_resignation_id' => $id);
		}
		else {
			return $this->redirect()->toRoute('resignationrecord');
		}
	}
	
	public function issueEstateDuesAction()
	{
		$this->loginDetails();
		
		//get the resignation id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new DuesForm();
			$resignationModel = new Dues();
			$form->bind($resignationModel);
			
			$resignationList = 'List of Resignation';
			$estate_clearance_authority = $this->resignationService->getAuthorisingRole('Estate', $this->organisation_id);
			
			//For notification to the resigning employee
			$employee_id = NULL;
			$resignationDetails = $this->resignationService->getResignationDetails($id);
			foreach($resignationDetails as $temp){
				$employee_id = $temp['employee_details_id'];
			}
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
						 $this->resignationService->saveDueClearance($resignationModel);
						 $this->flashMessenger()->addMessage('Estate Due Clearance successfully issued');
						 $this->notificationService->saveNotification('Dues Record', $employee_id, NULL, 'Issuance of Estate Dues');
						 $this->auditTrailService->saveAuditTrail("INSERT", "Estate Dues Issue", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('resignationrecord');
					 }
					 catch(\Exception $e) {
							$message = 'Failure';
					 		$this->flashMessenger()->addMessage($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			 
			return array(
				'form' => $form,
				'resignationList' => $resignationList,
				'estate_clearance_authority' => $estate_clearance_authority,
				'emp_resignation_id' => $id);
		}
		else {
			return $this->redirect()->toRoute('resignationrecord');
		}
	}
	
	public function issueWorkshopDuesAction()
	{
		$this->loginDetails();
		
		//get the resignation id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new DuesForm();
			$resignationModel = new Dues();
			$form->bind($resignationModel);
			
			$resignationList = 'List of Resignation';
			$workshop_clearance_authority = $this->resignationService->getAuthorisingRole('Workshop', $this->organisation_id);
			
			//For notification to the resigning employee
			$employee_id = NULL;
			$resignationDetails = $this->resignationService->getResignationDetails($id);
			foreach($resignationDetails as $temp){
				$employee_id = $temp['employee_details_id'];
			}
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
						 $this->resignationService->saveDueClearance($resignationModel);
						 $this->flashMessenger()->addMessage('Workshop Due Clearance successfully issued');
						 $this->notificationService->saveNotification('Dues Record', $employee_id, NULL, 'Issuance of Workshop Dues');
						 $this->auditTrailService->saveAuditTrail("INSERT", "Workshop Dues Issue", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('resignationrecord');
					 }
					 catch(\Exception $e) {
					 	$message = 'Failure';
					 	$this->flashMessenger()->addMessage($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			 
			return array(
				'form' => $form,
				'resignationList' => $resignationList,
				'workshop_clearance_authority' => $workshop_clearance_authority,
				'emp_resignation_id' => $id);
		}
		else {
			return $this->redirect()->toRoute('resignationrecord');
		}
	}

	public function empSeparationRecordDetailsAction()
	{
		$this->loginDetails();

		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new SeparationRecordForm();
			$resignationModel = new SeparationRecord();
			$form->bind($resignationModel);

			$separationDetails = $this->resignationService->getSeparationRecordDetails($tableName = 'emp_separation_record', $id);

			$message = NULL;

			$request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
              	if ($form->isValid()) {
                 try {
					 
				 }
				 catch(\Exception $e) {
					 $message = 'Failure';
				 	 $this->flashMessenger()->addMessage($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
	         
	         }
			
			return array(
				'form' => $form,
				'separationDetails' => $separationDetails,
				'message' => $message,
			);
        }else{
        	$this->redirect()->toRoute('separationrecordlist');
        }
	}


	public function downloadSeparationRecordAction()
	{
		$this->loginDetails();
        //get the student id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $file = $this->resignationService->getSeparationRecordFile($id);
        
            $mimetype = mime_content_type($file);
            $response = new Stream();
            $response->setStream(fopen($file, 'r'));
            $response->setStatusCode(200);
            $response->setStreamName(basename($file));
            $headers = new Headers();
            $headers->addHeaderLine('Content-Type', $mimetype)
					->addHeaderLine('Content-Disposition: inline', 'attachment; filename="' . basename($file) .'"')
					->addHeaderLine('Content-Length', filesize($file))
					->addHeaderLine('Expires', '@0')
					->addHeaderLine('Cache-Control', 'must-revalidate')
					->addHeaderLine('Pragma', 'public')
					->addHeaderLine('Content-Transfer-Encoding: binary')
	  				->addHeaderLine('Accept-Ranges: bytes');

            $response->setHeaders($headers);
            return $response;
        }
        else
        {
            $this->redirect()->toRoute('separationrecordlist');
        }
	}
	
	private function my_decrypt($data, $key) 
	{
		// Remove the base64 encoding from our key
		$encryption_key = base64_decode($key);
		
		$len = strlen($data);
        if ($len % 2) {
			return "ERROR";
        } else {
			// To decrypt, split the encrypted data from our IV - our unique separator used was "::"
			list($encrypted_data, $iv) = explode('::', base64_decode(hex2bin($data)), 2);
			return openssl_decrypt($encrypted_data, 'BF-CFB', $encryption_key, 0, $iv);
		}
	}
    
}

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace StudentLeave\Controller;

use StudentLeave\Service\StudentLeaveServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use StudentLeave\Form\StudentLeaveForm;
use StudentLeave\Form\StudentOutingForm;
use StudentLeave\Form\StudentLeaveCategoryForm;
use StudentLeave\Model\StudentLeave;
use StudentLeave\Model\StudentOuting;
use StudentLeave\Model\StudentLeaveCategory;
use Zend\Http\Response\Stream;
use Zend\Http\Headers;
use Zend\Paginator\Adapter\DbSelect;
use Zend\View\Model\JsonModel;
use Zend\Form\Element\Select;

/**
 * Description of IndexController
 *
 */
 
class StudentLeaveController extends AbstractActionController
{
	protected $leaveService;
	protected $notificationService;
    protected $auditTrailService;
	protected $serviceLocator;
	protected $username;
	protected $userrole;
	protected $userregion;
	protected $usertype;
	protected $userDetails;
	protected $userImage;
    protected $employee_details_id;
    protected $student_id;
    protected $organisation_id;

    protected $keyphrase = "RUB_IMS";
	
	public function __construct(StudentLeaveServiceInterface $leaveService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->leaveService = $leaveService;
		$this->notificationService = $notificationService;
        $this->auditTrailService = $auditTrailService;
		$this->serviceLocator = $serviceLocator;

		/*
         * To retrieve the user name from the session
        */
        $authPlugin = $this->serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
        // Use service locator to get the authPlugin
        $this->username = $authPlugin['username'];
		$this->userrole = $authPlugin['role'];
		$this->userregion = $authPlugin['region'];
		$this->usertype = $authPlugin['user_type_id'];

		/*
		* Getting the student_id/employee_details_id related to username
		*/
		if($this->usertype == 1){
			$empData = $this->leaveService->getUserDetailsId($this->username, $tableName = 'employee_details');
			foreach($empData as $emp){
				$this->employee_details_id = $emp['id'];
			}
		}
		else if($this->usertype == 2){
			$stdData = $this->leaveService->getUserDetailsId($this->username, $tableName = 'student');
			foreach($stdData as $std){
				$this->student_id = $std['id'];
			}
		}

		//get the organisation id
		$organisationID = $this->leaveService->getOrganisationId($this->username, $this->usertype);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}	
		

		//get the user details such as name
        $this->userDetails = $this->leaveService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->leaveService->getUserImage($this->username, $this->usertype);
	}

	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }
	
	public function addStudentLeaveCategoryAction()
	{
		$this->loginDetails();
		$form = new StudentLeaveCategoryForm();
		$leaveModel = new StudentLeaveCategory();
		$form->bind($leaveModel);
		
		$leaveCategories = $this->leaveService->listAll($tableName='student_leave_category', $this->organisation_id);
		$approvingAuthority = $this->leaveService->listSelectData($tableName = 'user_role', $columnName = 'rolename', $this->organisation_id);

		$message = NULL;
		
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
             	$data = $this->getRequest()->getPost('studentleavecategory');
             	$leave_category = $data['leave_category'];
             	$check_leave_category = $this->leaveService->crossCheckStdLeaveCategory($leave_category, $this->organisation_id, NULL);
             	if($check_leave_category){
             		$message = 'Failure';
             		$this->flashMessenger()->addMessage('You have already added similar category of leave. Please try for another!');
             	}else{
             		try {
					 	$this->leaveService->saveLeaveCategory($leaveModel);
					 	$this->auditTrailService->saveAuditTrail("INSERT", "Student Leave Category", "ALL", "SUCCESS");
					 	$this->flashMessenger()->addMessage('Leave Category was successfully added');
					 	return $this->redirect()->toRoute('stdleavecategory');
				 	}
				 	catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 	}
             	}
             }
         }
		 
        return array(
			'form' => $form,
			'leaveCategories' => $leaveCategories,
			'approvingAuthority' => $approvingAuthority,
			'organisation_id' => $this->organisation_id,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
		);
	}
	
	public function viewLeaveCategoryAction()
	{
		$this->loginDetails();
		//get the leave category id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new StudentLeaveCategoryForm();
				
			$viewCategory = $this->leaveService->findLeaveCategory($id);
			$leaveCategories = $this->leaveService->listAll($tableName='student_leave_category', $this->organisation_id);
			 
	        return array(
				'form' => $form,
				'viewCategory' => $viewCategory,
				'leaveCategories' => $leaveCategories,
			);
        }else{
        	$this->redirect()->toRoute('stdleavecategory');
        }
	}
	
	public function editLeaveCategoryAction()
	{
		$this->loginDetails();
		//get the leave category id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new StudentLeaveCategoryForm();
			$leaveModel = new StudentLeaveCategory();
			$form->bind($leaveModel);
			
			$editCategory = $this->leaveService->findLeaveCategory($id);
			$leaveCategories = $this->leaveService->listAll($tableName='student_leave_category', $this->organisation_id);

			$approvingAuthority = $this->leaveService->listSelectData($tableName = 'user_role', $columnName = 'rolename', $this->organisation_id);

			$message = NULL;
			
	        $request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) {
	             	$data = $this->getRequest()->getPost('studentleavecategory');
             		$leave_category = $data['leave_category'];
             		$check_leave_category = $this->leaveService->crossCheckStdLeaveCategory($leave_category, $this->organisation_id, $id);
             		if($check_leave_category){
             			$message = 'Failure';
             			$this->flashMessenger()->addMessage("You can't edit this particular leave category since you have already added the similar leave category");
             		}else{
             			try {
						 	$this->leaveService->saveLeaveCategory($leaveModel);
						 	$this->auditTrailService->saveAuditTrail("EDIT", "Student Leave Category", "ALL", "SUCCESS");
						 	$this->flashMessenger()->addMessage('Student Leave Category was successfully edited.');
						 	return $this->redirect()->toRoute('stdleavecategory');
					 	}
					 	catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 	}
             		}
	             }
	         }
			 
	        return array(
				'form' => $form,
				'editCategory' => $editCategory,
				'leaveCategories' => $leaveCategories,
				'message' => $message,
				'approvingAuthority' => $approvingAuthority,
			);
        }else{
        	$this->redirect()->toRoute('stdleavecategory');
        }
	}
    
	public function applyStudentLeaveAction()
    {
    	$this->loginDetails();

        $form = new StudentLeaveForm();
		$leaveModel = new StudentLeave();
		$form->bind($leaveModel);

		$leaveCategory = $this->leaveService->listSelectData($tableName = 'student_leave_category', $columnName = 'leave_category', $this->organisation_id);

		$appliedLeaveList = $this->leaveService->getAppliedLeaveList($this->student_id);

		$message = NULL;
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             $data = array_merge_recursive(
					$request->getPost()->toArray(),
					$request->getFiles()->toArray()
				 ); 
			 $form->setData($data);

             if ($form->isValid()) {

             	$data = $this->getRequest()->getPost('leaveapplication');
             	$from_date = $data['from_date'];
             	$to_date = $data['to_date'];
             	$appliedLeaveCat = $this->leaveService->getAppliedLeaveCategory($data['student_leave_category_id'], $this->organisation_id); 
             	$check_allocated_hostel = $this->leaveService->checkStudentHostelAllocation($this->student_id);

             	if($from_date > $to_date){
             		$message = 'Failure';
             		$this->flashMessenger()->addMessage("You can't apply for leave since your To Date less than From Date. To Date should be equal or greater than From Date. Please try again.");
             	}else if(!$check_allocated_hostel && $appliedLeaveCat == 'Weekend Leave'){
             		$message = 'Failure';
             		$this->flashMessenger()->addMessage("You are not allowed to apply for Weekend Leave. Please try for other.");
             	}
             	else{
             		try {
					 	$this->leaveService->save($leaveModel);
						 $this->notificationService->saveNotification('Student Leave Application', 'ALL', 'ALL', 'Student Leave');
	                	 $this->auditTrailService->saveAuditTrail("INSERT", "Student Leave", "ALL", "SUCCESS");
	                	 $this->flashMessenger()->addMessage("You have successfully applied for leave");
	                	 return $this->redirect()->toRoute('applystdleave');
				 	}
				 	catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 	}
             	}	
             }
         }
		 
        return array(
        	'form' => $form,
        	'student_id' => $this->student_id,
        	'organisation_id' => $this->organisation_id,
        	'appliedLeaveList' => $appliedLeaveList,
        	'semester_duration' => $this->leaveService->getSemesterDuration($this->organisation_id),
        	'leaveCategory' => $leaveCategory,
        	'keyphrase' => $this->keyphrase,
        	'message' => $message,
    	);
    } 

    public function applyStudentOutingAction()
    {
    	$this->loginDetails();
        $form = new StudentLeaveForm();
		$outingModel = new StudentLeave();
		$form->bind($outingModel);
		$leaveCategory = $this->leaveService->listSelectData($tableName = 'student_leave_category', $columnName = 'Day Outing', $this->organisation_id);
		$appliedLeaveList = $this->leaveService->getAppliedLeaveList($this->student_id);

		$appliedLastDate = $this->leaveService->getAppliedLastDate($this->student_id);

		//var_dump($appliedLastDate); die();

		$message = NULL;
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             $data = array_merge_recursive(
					$request->getPost()->toArray(),
					$request->getFiles()->toArray()
				 ); 
			 $form->setData($data);

             if ($form->isValid()) {
             	$data = $this->getRequest()->getPost('leaveapplication');
             	$from_date = $data['from_date'];

             	$appliedLeaveCat = $this->leaveService->getAppliedLeaveCategory($data['student_leave_category_id'], $this->organisation_id); 


             	$check_allocated_hostel = $this->leaveService->checkStudentHostelAllocation($this->student_id);
				
				if(!$check_allocated_hostel && $appliedLeaveCat == 'Weekend Leave'){
             		$message = 'Failure';
             		$this->flashMessenger()->addMessage("You are not allowed to apply for Weekend Leave. Please try for other.");
             	}
             	else if($appliedLastDate){
             		//var_dump(expression); die();
             		$lastapplieddate = $appliedLastDate['from_date'];
             		$pickeddate = date('Y-m-d', strtotime($from_date));
             		$lastapplieddate = date('Y-m-d', strtotime($lastapplieddate. ' + 7 days'));
             		
             		if ($pickeddate>=$lastapplieddate) {
             			try {
						 	$this->leaveService->save($outingModel);
							 $this->notificationService->saveNotification('Student Outing Application', 'ALL', 'ALL', 'Student Outing');
		                	 $this->auditTrailService->saveAuditTrail("INSERT", "Student Outing", "ALL", "SUCCESS");
		                	 $this->flashMessenger()->addMessage("You have successfully applied for Outing");
		                	 return $this->redirect()->toRoute('applystdouting');
					 	}
					 	catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 	}
             		} else {
             			$message = 'Failure';
             			$this->flashMessenger()->addMessage("You are not allowed to apply for following resons
             				<br>1 - you can only apply for dates after 7 days of you last approved date ($pickeddate). 
             				<br>2 - OR you still have pending application. 
             				<br>3 - OR you have selected date which is already gone. ");
             		}
             		
             	} else {
             		try {
					 	$this->leaveService->save($outingModel);
						 $this->notificationService->saveNotification('Student Outing Application', 'ALL', 'ALL', 'Student Outing');
	                	 $this->auditTrailService->saveAuditTrail("INSERT", "Student Outing", "ALL", "SUCCESS");
	                	 $this->flashMessenger()->addMessage("You have successfully applied for Outing");
	                	 return $this->redirect()->toRoute('applystdouting');
				 	}
				 	catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 	}
             	}	
             }
         }
	
        return array(
        	'form' => $form,
        	'student_id' => $this->student_id,
        	'organisation_id' => $this->organisation_id,
        	'appliedLeaveList' => $appliedLeaveList,
        	'semester_duration' => $this->leaveService->getSemesterDuration($this->organisation_id),
        	'leaveCategory' => $leaveCategory,
        	'keyphrase' => $this->keyphrase,
        	'message' => $message,
    	);
    } 


    public function stdLeaveApprovalAction()
    {
    	$this->loginDetails();
		
		$message = NULL;

		$pendingList = $this->leaveService->listAllLeave($status='Pending', $this->employee_details_id, $userrole=$this->userrole, $this->organisation_id);
		$approvedList= $this->leaveService->listAllLeave($status='Approved', $this->employee_details_id, $userrole = $this->userrole, $this->organisation_id);
		$rejectList = $this->leaveService->listAllLeave($status='Rejected', $this->employee_details_id, $userrole=$this->userrole, $this->organisation_id);
		
		return new ViewModel(array(
			'keyphrase' => $this->keyphrase,
			'pendingList' => $pendingList,
			'approvedList' => $approvedList,
			'rejectList' => $rejectList,
			'message' => $message
		));
    }


    public function approveStudentLeaveAction()
    {
    	$this->loginDetails();
		
		//get the id of the leave
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$leave = $this->leaveService->findLeave($id);
		
			//Need to get the employee details id/username of leave application
			$leave_applicant = NULL;
			$leaveDetails = $this->leaveService->findLeave($id);
			foreach($leaveDetails as $temp){
				$leave_applicant = $temp['student_id'];
			}
			
			$form = new StudentLeaveForm();
			$leaveModel = new StudentLeave();
			$form->bind($leaveModel);
			
			 $request = $this->getRequest();
			 if ($request->isPost()) {
				 //the following set of code is to get the value from the submit buttons
				 $postData = $this->getRequest()->getPost();
				 $form->setData($request->getPost());
				 $remarks = $postData['leaveapplication']['remarks'];
				 foreach ($postData as $key => $value)
				 { 
					 if($key == 'leaveapplication')
					 {
						 $leaveData = $value;
						 if(array_key_exists('approve', $leaveData))
							 $leaveStatus = 'Approved';
						 else if(array_key_exists('reject', $leaveData))
							$leaveStatus = 'Rejected';
					 }
				 }
				 //We do not check for valid form as we are not getting any values
				 // just updating the leave status
				 if ($leaveStatus) {
					 try {
						 $this->leaveService->updateLeave($id, $leaveStatus, $remarks, $this->employee_details_id);
						 $this->flashMessenger()->addMessage('Leave was '.$leaveStatus);
						 $this->notificationService->saveNotification('Student Leave Application', $leave_applicant, NULL, "Leave Status $leaveStatus");
						 $this->auditTrailService->saveAuditTrail("UPDATE", "Updating Leave Status", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('stdleaveapproval');
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
				'leave' => $leave,
				'employee_details_id' => $this->employee_details_id,
			 );
		}
		else {
			return $this->redirect()->toRoute('stdleaveapproval');
		}
    }


    public function downloadStdLeaveApplicationAction()
    {
    	//get the param from the view file
		$file_location = $this->params()->fromRoute('filename',0);
		
		//extract the training id and column name
		$column_name = preg_replace('!\d+!', '', $file_location);
		$column_name = rtrim($column_name,"_");
		preg_match_all('!\d+!',$file_location, $id);
		$leave_id = implode(' ', $id[0]);
		
		//get the location of the file from the database		
		$fileArray = $this->leaveService->getFileName($leave_id);
		$file;
		foreach($fileArray as $set){
			$file = $set['evidence_file'];
		}
		
		$response = new Stream();
		$response->setStream(fopen($file, 'r'));
		$response->setStatusCode(200);
		$response->setStreamName(basename($file));
		$headers = new Headers();
		$headers->addHeaders(array(
			'Content-Disposition' => 'attachment; filename="' . basename($file) .'"',
			'Content-Type' => 'application/octet-stream',
			'Content-Length' => filesize($file),
			'Expires' => '@0', // @0, because zf2 parses date as string to \DateTime() object
			'Cache-Control' => 'must-revalidate',
			'Pragma' => 'public'
		));
		$response->setHeaders($headers);
		return $response;
    }

     public function downloadStdLeaveFileAction()
    {
    	//get the param from the view file
		$file_location = $this->params()->fromRoute('filename',0);
		
		//extract the training id and column name
		$column_name = preg_replace('!\d+!', '', $file_location);
		$column_name = rtrim($column_name,"_");
		preg_match_all('!\d+!',$file_location, $id);
		$leave_id = implode(' ', $id[0]);
		
		//get the location of the file from the database		
		$fileArray = $this->leaveService->getFileName($leave_id);
		$file;
		foreach($fileArray as $set){
			$file = $set['evidence_file'];
		}
		
		$response = new Stream();
		$response->setStream(fopen($file, 'r'));
		$response->setStatusCode(200);
		$response->setStreamName(basename($file));
		$headers = new Headers();
		$headers->addHeaders(array(
			'Content-Disposition' => 'attachment; filename="' . basename($file) .'"',
			'Content-Type' => 'application/octet-stream',
			'Content-Length' => filesize($file),
			'Expires' => '@0', // @0, because zf2 parses date as string to \DateTime() object
			'Cache-Control' => 'must-revalidate',
			'Pragma' => 'public'
		));
		$response->setHeaders($headers);
		return $response;
    }


    
	public function viewStudentLeaveAction()
    {
    	$this->loginDetails();
       //get the id of the leave
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$leave = $this->leaveService->findLeave($id);

			return array(
				'id' => $id,
				//'form' => $form,
				'leave' => $leave,
			);
		}else{
			return $this->redirect()->toRoute('applystdleave');
		}
    }
    

    
	public function editStudentLeaveAction()
    {
    	$this->loginDetails();
        $form = new StudentLeaveForm();
		$leaveModel = new StudentLeave();
		$form->bind($leaveModel);
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 var_dump($form);
				 die();
                 try {
					 $this->leaveService->save($leaveModel);
					 $this->auditTrailService->saveAuditTrail("EDIT", "Student Leave", "ALL", "SUCCESS");
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array('form' => $form);
    }
    
	public function searchStudentLeaveAction()
    {
    	$this->loginDetails();
        $form = new StudentLeaveForm();
		$leaveModel = new StudentLeave();
		$form->bind($leaveModel);
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 var_dump($form);
				 die();
                 try {
					 $this->leaveService->save($leaveModel);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array('form' => $form);
    }

    public function my_decrypt($data, $key) 
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

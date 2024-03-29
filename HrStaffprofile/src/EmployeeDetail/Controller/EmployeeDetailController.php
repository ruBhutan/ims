<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace EmployeeDetail\Controller;

use EmployeeDetail\Service\EmployeeDetailServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use EmployeeDetail\Form\AddEmployeeForm;
use EmployeeDetail\Form\NewEmployeeDetailForm;
use EmployeeDetail\Form\NewEmployeeForm;
use EmployeeDetail\Form\UpdateNewEmployeeForm;
use EmployeeDetail\Form\NewEmployeeEducationForm;
use EmployeeDetail\Form\NewEmployeeRelationForm;
use EmployeeDetail\Form\NewEmployeeTrainingForm;
use EmployeeDetail\Form\NewEmployeePublicationForm;
use EmployeeDetail\Form\NewEmployeeWorkExperienceForm;
use EmployeeDetail\Form\NewEmployeeDocumentsForm;
use EmployeeDetail\Form\EmpDetailForm;
use EmployeeDetail\Form\EmployeePublicationsForm;
use EmployeeDetail\Form\EmployeeEducationForm;
use EmployeeDetail\Form\EmployeeRelationDetailForm;
use EmployeeDetail\Form\EmployeeTrainingsForm;
use EmployeeDetail\Form\EmployeeWorkExperienceForm;
use EmployeeDetail\Form\EmployeeRubWorkExperienceForm;
use EmployeeDetail\Form\EmployeeAwardForm;
use EmployeeDetail\Form\EmployeeContributionForm;
use EmployeeDetail\Form\EmployeeResponsibilityForm;
use EmployeeDetail\Form\EmployeeCommunityServiceForm;
use EmployeeDetail\Form\EmployeeTitleForm;
use EmployeeDetail\Form\EmployeeLevelForm;
use EmployeeDetail\Form\EmployeePersonalDetailsForm;
use EmployeeDetail\Form\EmployeePermanentAddressForm;
use EmployeeDetail\Form\EmployeeEmploymentDetailsForm;
use EmployeeDetail\Form\EmployeeProfilePictureForm;
use EmployeeDetail\Form\EmployeeDisciplineForm;
use EmployeeDetail\Form\SearchForm;
use EmployeeDetail\Form\EmployeeDetailForm;
use EmployeeDetail\Form\EmployeeOnProbationForm;
use EmployeeDetail\Form\HrReportForm;
use EmployeeDetail\Form\EmployeePayDetailsForm;
use EmployeeDetail\Form\EmployeeDepartmentForm;
use EmployeeDetail\Form\UpdateEmpDepartmentForm;
use EmployeeDetail\Form\UpdateEmpPositionTitleLevelForm;
use EmployeeDetail\Form\UpdateNewEmpDocForm;
use EmployeeDetail\Form\EmployeeJobProfileForm;
use EmployeeDetail\Model\EmployeeDetail;
use EmployeeDetail\Model\NewEmployeeDetail;
use EmployeeDetail\Model\NewEmployee;
use EmployeeDetail\Model\UpdateNewEmpDoc;
use EmployeeDetail\Model\NewEmployeeAdditionalDetail;
use EmployeeDetail\Model\NewEmployeeDocuments;
use EmployeeDetail\Model\EmployeeAward;
use EmployeeDetail\Model\EmployeeContribution;
use EmployeeDetail\Model\EmployeeResponsibilities;
use EmployeeDetail\Model\EmployeeCommunityService;
use EmployeeDetail\Model\EmployeeRelationDetail;
use EmployeeDetail\Model\EmployeeEducation;
use EmployeeDetail\Model\EmployeePublications;
use EmployeeDetail\Model\EmployeeTrainings;
use EmployeeDetail\Model\EmployeeWorkExperience;
use EmployeeDetail\Model\EmployeeLevel;
use EmployeeDetail\Model\EmployeeTitle;
use EmployeeDetail\Model\EmployeePersonalDetails;
use EmployeeDetail\Model\EmployeePermanentAddress;
use EmployeeDetail\Model\EmployeeProfilePicture;
use EmployeeDetail\Model\EmployeeDisciplineRecord;
use EmployeeDetail\Model\EmployeeOnProbation;
use EmployeeDetail\Model\EmployeePayDetails;
use EmployeeDetail\Model\EmployeeJobProfile;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;
use Zend\Http\Response\Stream;
use Zend\Http\Headers;
use DOMPDFModule\View\Model\PdfModel;

class EmployeeDetailController extends AbstractActionController
{
	protected $employeeService;
	protected $notificationService;
	protected $auditTrailService;
	protected $emailService;
	protected $serviceLocator;
	protected $username;
	protected $userrole;
	protected $usertype;
	protected $userregion;
	protected $userDetails;
	protected $userImage;
	protected $employee_details_id;
	protected $organisation_id;
	protected $keyphrase = "RUB_IMS";
	
	public function __construct(EmployeeDetailServiceInterface $employeeService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->employeeService = $employeeService;
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
		* Getting the student_id/employee_details_id related to username
		*/
		
		$empData = $this->employeeService->getUserDetailsId($this->username, $tableName = 'employee_details');
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			$this->departments_id = $emp['departments_id'];
			$this->departments_units_id = $emp['departments_units_id'];
			}
		
		//get the organisation id
		$organisationID = $this->employeeService->getOrganisationId($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}
		
		//get the user details such as name
		$this->userDetails = $this->employeeService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->employeeService->getUserImage($this->username, $this->usertype);
		
	}
	
	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }
   
    public function employeeListAction()
    {
		$this->loginDetails();

		$role = $this->userrole; 
		$self_id = $this->employee_details_id;
		
       $form = new SearchForm();
	   $employeeList = $this->employeeService->listAllEmployees($this->organisation_id, $self_id);
	   	   
	   $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$empName = $this->getRequest()->getPost('employee_name');
				$empId = $this->getRequest()->getPost('emp_id');
				$department = $this->getRequest()->getPost('department');
				$employeeList = $this->employeeService->getEmployeeList($empName, $empId, $department, $this->organisation_id, $self_id, $role);
             }
         }
		
		return new ViewModel(array(
            'form' => $form,
			'keyphrase' => $this->keyphrase,
			'employeeList' => $employeeList
            ));
    }


    public function empJobProfileAction()
    {
    	$this->loginDetails();

    	$id_from_route = $this->params()->fromRoute('id', 0);
    	$id = $this->my_decrypt($id_from_route, $this->keyphrase);

    	if(is_numeric($id)){
    		$employeeDetails = $this->employeeService->getEmployeeDetails($id);
    		$empPermanentAddr = $this->employeeService->getEmpPermanentAddress($id);
    		$empPositionTitleDetails = $this->employeeService->getEmpPositionTitleDetail($id);
    		$empPositionLevelDetails = $this->employeeService->getEmpPositionLevelDetail($id);
    		$empJobProfile = $this->employeeService->getEmpJobProfile($id);

    		$message = NULL;

    		return new ViewModel(array(
    			'id' => $id,
    			'keyphrase' => $this->keyphrase,
    			'employeeDetails' => $employeeDetails,
    			'empPermanentAddr' => $empPermanentAddr,
    			'empPositionTitleDetails' => $empPositionTitleDetails,
    			'empPositionLevelDetails' => $empPositionLevelDetails,
    			'empJobProfile' => $empJobProfile,
    			'organisation_id' => $this->organisation_id,
    			'message' => $message
    		));
    	}else{
    		return $this->redirect()->toRoute('employeelist');
    	}
    }


    //FUntion to add employee job profile
    public function addEmpJobProfileAction()
    {
    	$this->loginDetails();

    	$id_from_route = $this->params()->fromRoute('id', 0);
    	$id = $this->my_decrypt($id_from_route, $this->keyphrase);

    	if(is_numeric($id)){
    		$form = new EmployeeJobProfileForm($this->serviceLocator);

    		$employeeDetails = $this->employeeService->getEmployeeDetails($id);
    		$empPermanentAddr = $this->employeeService->getEmpPermanentAddress($id);
    		$empPositionTitleDetails = $this->employeeService->getEmpPositionTitleDetail($id);
    		$empPositionLevelDetails = $this->employeeService->getEmpPositionLevelDetail($id);

    		$employeeType = $this->employeeService->listSelectData($tableName='employee_type', $columnName = 'employee_type');
    		$payIncrementType = $this->employeeService->listSelectData($tableName = 'increment_type', $columnName = 'increment_type');
    		$empStatus = $this->employeeService->listSelectData($tableName = 'resignation_type', $columnName = 'resignation_type');

    		$message = null;

    		$request = $this->getRequest();
    		if($request->isPost()){
    			$form->setData($request->getPost());
    			if($form->isValid()){
    				$data = $form->getData();
    				try{
    					$employeeData = $this->employeeService->saveEmpJobProfile($data);
    					$this->flashMessenger()->addMessage('Employee Job Profile was successfully added');
    					$this->auditTrailService->saveAuditTrail("INSERT", "Job Profile", "ALL", "SUCCESS");
    					return $this->redirect()->toRoute('empjobprofile', array('id' => $this->my_encrypt($id, $this->keyphrase)));
    				}
    				catch(\Exception $e){
    					$message = 'Failure';
    					$this->flashMessenger()->addMessage($e->getMessage());
    				}
    			}
    		}

    		return new ViewModel(array(
    			'id' => $id,
    			'form' => $form,
    			'employeeDetails' => $employeeDetails,
    			'empPermanentAddr' => $empPermanentAddr,
    			'empPositionTitleDetails' => $empPositionTitleDetails,
    			'empPositionLevelDetails' => $empPositionLevelDetails,
    			'employeeType' => $employeeType,
    			'payIncrementType' => $payIncrementType,
    			'empStatus' => $empStatus,
    			'employee_details_id' => $this->employee_details_id,
    			'message' => $message
    		));
    	}else{
    		return $this->redirect()->toRoute('empjobprofile', array('id' => $this->my_encrypt($id, $this->keyphrase)));
    	}
    }


    public function editEmpJobProfileAction()
	{
		$this->loginDetails();

		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);

		if(is_numeric($id)){ 
			$form = new EmployeeJobProfileForm($this->serviceLocator);

			$empJobProfileDetails = $this->employeeService->getEmpJobProfileDetails($id);

			$jobProfileDetails = $this->employeeService->getEmpJobProfileDetails($id);
			$emp_job_details_array = array();
			foreach($jobProfileDetails as $details){
				$emp_job_details_array = $details;
			}

			$employeeDetails = $this->employeeService->getEmployeeDetails($emp_job_details_array['employee_details']);

			$employeeType = $this->employeeService->listSelectData($tableName='employee_type', $columnName='employee_type');
			$payIncrementType = $this->employeeService->listSelectData($tableName='increment_type', $columnName='increment_type');
			$empStatus = $this->employeeService->listSelectData($tableName='resignation_type', $columnName='resignation_type');


			$request = $this->getRequest();
			if($request->isPost()){
				$form->setData($request->getPost());
				if($form->isValid()){
					$data = $form->getData(); 
					try {
						$this->employeeService->saveEmpJobProfile($data);
						$this->flashMessenger()->addMessage('Employee Job Profile was successfully edited');
						$this->auditTrailService->saveAuditTrail("UPDATE", "Job Profile", "ALL", "SUCCESS");
						return $this->redirect()->toRoute('');
					}
					catch(\Exception $e){
						die($e->getMessage());
					}
				}
			}
			return new ViewModel(array(
				'id' => $id,
				'form' => $form,
				'empJobProfileDetails' => $empJobProfileDetails,
				'employeeDetails' => $employeeDetails,
				'employeeType' => $employeeType,
				'payIncrementType' => $payIncrementType,
				'empStatus' => $empStatus,

			));

		}else{
			return $this->redirect()->toRoute('employeelist');
		}
	}
	

     
	public function addEmployeeAction()
    {
		$this->loginDetails();
		
        //$dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
		
		$form = new NewEmployeeDetailForm($this->serviceLocator);
		$employeeModel = new NewEmployeeDetail();
		$form->bind($employeeModel);
		
		$employeeType = $this->employeeService->listSelectData($tableName='employee_type', $columnName='employee_type');
		$country = $this->employeeService->listSelectData($tableName='country', $columnName='country');
		$nationality = $this->employeeService->listSelectData($tableName='nationality', $columnName='nationality');
		$bloodGroup = $this->employeeService->listSelectData($tableName='blood_group', $columnName='blood_group');
		$religion = $this->employeeService->listSelectData($tableName='religion', $columnName='religion');
		$maritialStatus = $this->employeeService->listSelectData($tableName = 'maritial_status', $columnName = 'maritial_status');
		$gender = $this->employeeService->listSelectData($tableName = 'gender', $columnName = 'gender');
		$empId = $this->employeeService->generateEmployeeId();
		$message = NULL;
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $employeeData = $this->employeeService->saveNewEmployee($employeeModel, $this->employee_details_id);
					 $this->flashMessenger()->addMessage('Employee was successfully added and the password is Citzenship ID Card No.');
					 $this->auditTrailService->saveAuditTrail("INSERT", "Add New Employee", "ALL", "SUCCESS");
					 return $this->redirect()->toRoute('employeelist');
				 }
				 catch(\Exception $e) {
				 	$message = 'Failure';
				 	$this->flashMessenger()->addMessage($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'empId' => $empId,
			'employeeType' => $employeeType,
			'country' => $country,
			'nationality' => $nationality,
			'bloodGroup' => $bloodGroup,
			'religion' => $religion,
			'maritialStatus' => $maritialStatus,
			'gender' => $gender,
			'message' => $message
			));
    }


    /*
    *Function to add new selected staff from organisation
    **/
    public function addNewEmployeeAction()
    {
    	$this->loginDetails();
		
        //$dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
		
		$form = new NewEmployeeForm($this->serviceLocator);
		$employeeModel = new NewEmployee();
		$form->bind($employeeModel);
		
		$employeeType = $this->employeeService->listSelectData($tableName='employee_type', $columnName='employee_type');
		$country = $this->employeeService->listSelectData($tableName='country', $columnName='country');
		$nationality = $this->employeeService->listSelectData($tableName='nationality', $columnName='nationality');
		$bloodGroup = $this->employeeService->listSelectData($tableName='blood_group', $columnName='blood_group');
		$religion = $this->employeeService->listSelectData($tableName='religion', $columnName='religion');
		$maritialStatus = $this->employeeService->listSelectData($tableName = 'maritial_status', $columnName = 'maritial_status');
		$gender = $this->employeeService->listSelectData($tableName = 'gender', $columnName = 'gender');
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
				$organisation_id = $this->getRequest()->getPost('organisation_id'); 
                 try {
					 $employeeData = $this->employeeService->saveNewEmployeeDetails($employeeModel);
					 $this->sendNewStaffAddEmail($organisation_id);
					 $this->flashMessenger()->addMessage('Employee was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "Add New Employee Details", "ALL", "SUCCESS");
					 return $this->redirect()->toRoute('newemployeelist');
				 }
				 catch(\Exception $e) {
				 	$message = 'Failure';
				 	$this->flashMessenger()->addMessage($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'employeeType' => $employeeType,
			'country' => $country,
			'nationality' => $nationality,
			'bloodGroup' => $bloodGroup,
			'religion' => $religion,
			'maritialStatus' => $maritialStatus,
			'gender' => $gender,
			'organisation_id' => $this->organisation_id,
			'message' => $message
			));
    }


	//Function to send email to OVC_HRO for the generation of Staff ID of newly added staff
	public function sendNewStaffAddEmail($organisation_id)
	{
		$this->loginDetails();


    	$ovc_hro_email = $this->employeeService->getOVCHroEmailId($role = 'OVC_HRO');

	 	$organisation_name = NULL;
	 	$organisation_details = $this->employeeService->getOrganisationDetails($organisation_id);
	 	foreach($organisation_details as $org){
	 		$organisation_name = $org['organisation_name'];
	 	}

	 	foreach($ovc_hro_email as $email){
	 		$toEmail = $email;
	        $messageTitle = "New Staff Recruitment";
	        //$messageBody = "<h2>".$applicant_name."</h2><b>has applied for leave on ".date('Y-m-d')."</b><br>.<b>For Details: Please click below link</b> <br><u>http://ims.rub.edu.bt/public/empleaveapproval/</u>";
			$messageBody = "Dear Sir/Madam,<br><h3>".$organisation_name." has recruited one new staff and added to system on ".date('Y-m-d').".</h3><br><b>Please click the link below for necessary action (like view details and generate Staff ID).</b><br><u>http://ims.rub.edu.bt/public/newemployeelist</u><p>This is an auto-generated email and Please do not reply because this mail id is not monitored</p>";

	        $this->emailService->sendMailer($toEmail, $messageTitle, $messageBody);
	 	}   
	}

    public function downloadNewEmpDocumentAction()
    {
    	//get the param from the view file
		$file_location = $this->params()->fromRoute('filename',0);
		
		//extract the training id and column name
		$column_name = preg_replace('!\d+!', '', $file_location);
		$document_type = rtrim($column_name,"_");
		preg_match_all('!\d+!',$file_location, $id);
		$new_employee_doc_id = implode(' ', $id[0]); 
		
		//get the location of the file from the database		
		$file = $this->employeeService->getNewEmpFileName($new_employee_doc_id, $document_type);
		
		
		$mimetype = mime_content_type($file);
		$response = new Stream();
		$response->setStream(fopen($file, 'r'));
		$response->setStatusCode(200);
		$response->setStreamName(basename($file));
		$headers = new Headers();
		$headers->addHeaderLine('Content-Disposition: inline','attachment; filename="' . basename($file) .'"')
				->addHeaderLine('Content-Type',$mimetype)
				->addHeaderLine('Content-Length',filesize($file))
				->addHeaderLine('Expires','@0') // @0, because zf2 parses date as string to \DateTime() object
				->addHeaderLine('Cache-Control','must-revalidate')
				->addHeaderLine('Pragma','public')
				->addHeaderLine('Content-Transfer-Encoding:binary')
				->addHeaderLine('Accept-Ranges: bytes');

		$response->setHeaders($headers);
		return $response;
    }


    public function newEmployeeListAction()
    {
    	$this->loginDetails();
		
	   $employeeList = $this->employeeService->listAllNewEmployees($this->organisation_id);

		return new ViewModel(array(
			'keyphrase' => $this->keyphrase,
			'organisation_id' => $this->organisation_id,
			'employeeList' => $employeeList
            ));
    }


/* This function will delete staff if the information is not correct before generating Staff ID */

   public function rollBackNewEmployeeIdAction()
    {

	       $this->loginDetails();
	    
                if ($this->getRequest()->isGet())
		{
                        try {
                                 $id_from_route = $this->params()->fromRoute('id', 0);

				 $id = $this->my_decrypt($id_from_route, $this->keyphrase);
				//echo "ID:".$id;
				 $email_add = $this ->employeeService-> getEmailtoSend($id);

				 $cid = $this->employeeService->rollBackNewEmpId($id);
			//	 echo "CID:".$cid;
                                 if($cid != null)
                                 {


                                        $this->auditTrailService->saveAuditTrail("DELETE", "New Employee Details", "One Row", "SUCCESS", "DELETED STAFF WITH CID:".$cid." by this user:".$this->username);

					if($this -> sendEmail($email_add,$cid))
					{

						$this->flashMessenger()->addMessage('Employee was successfully DELETED and forwarded the email to respective HR division/section');
					        $this->sendEmail($this ->employeeService-> getEmailAddressForSender($this->username), $cid);
					}
										else
										            $this->flashMessenger()->addMessage('Employee was successfully DELETED and but could not forwarded the email to respective HR division/section');
										
												
                                        return $this->redirect()->toRoute('employeelist');
                                 }
                        }catch(\Exception $e) {

                                 $message = 'Failure';

                                 $this->flashMessenger()->addMessage($e->getMessage());
                        }
		
			return $this->redirect()->toRoute('newemployeelist');    
		    
		} 
    }

     /*
	 * Forward email to respective HR if there are mistake in entries to the new employee
	 */
	/**
     *  To forward email
     */

    private function sendEmail($emailAddress, $cid)
    {
    

         // echo $emailAddress; 
		 $messageTitle = "Deleted the records of Staff:".$cid;
         
		 $messageBody = "Dear Sir/Madam,<br><h3> Records of the staff with CID:".$cid." is deleted on ".date('Y-m-d')."
		                .</h3><br><b> Imcomplete documents <p>This is an auto-generated email and Please do not reply because this mail id is not monitored</p>";
        $val = false;

        if (is_array($emailAddress))
	{
	//	echo "2";
		
			foreach ($emailAddress as $email)
					
		              $val = $this->emailService->sendMailer($email['email'], $messageTitle, $messageBody);
		}
	else
	{
		//echo "1";
		          $val = $this->emailService->sendMailer($emailAddress, $messageTitle, $messageBody);
			  
	}
       return $val; 

    } 
   
    public function generateNewEmployeeIdAction()
    {
    	$this->loginDetails();

		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){ 
			$form = new UpdateNewEmployeeForm();
			$employeeModel = new NewEmployee();
			$form->bind($employeeModel); 

			$newEmployeeDetails = $this->employeeService->getNewEmployeeDetails($id);

			$empId = $this->employeeService->generateEmployeeId();
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				$data = array_merge_recursive(
                 $request->getPost()->toArray(),
                 $request->getFiles()->toArray()
                 ); 
                 $form->setData($data);
				 if ($form->isValid()) { 
					 try {
						 $this->employeeService->updateNewEmployee($employeeModel, $this->employee_details_id);
						 $this->flashMessenger()->addMessage('Staff ID was successfully generated and the password is the Citizenship ID Card No.');
					 	$this->auditTrailService->saveAuditTrail("UPDATE", "New Employee Details", "ALL", "SUCCESS");
					 	$this->auditTrailService->saveAuditTrail("INSERT", "Employee Details", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('newemployeelist');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'id' => $this->my_encrypt($id, $this->keyphrase),
				'form' => $form,
				'empId' => $empId,
				'newEmployeeDetails' => $newEmployeeDetails,
				'keyphrase' => $this->keyphrase,
			)); 
		}
		else {
			return $this->redirect()->toRoute('newemployeelist');
		}   	
    }

    public function uploadNewEmployeeOrderAction()
    {
    	$this->loginDetails();

		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){ 
			$form = new UpdateNewEmployeeForm();
			$employeeModel = new NewEmployee();
			$form->bind($employeeModel); 

			$newEmployeeDetails = $this->employeeService->getNewEmployeeDetails($id);

			//$empId = $this->employeeService->generateEmployeeId();
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				$data = array_merge_recursive(
                 $request->getPost()->toArray(),
                 $request->getFiles()->toArray()
                 ); 
                 $form->setData($data);
				 if ($form->isValid()) { 
					 try {
						 $this->employeeService->uploadNewEmployeeOrder($employeeModel, $this->employee_details_id);
						 $this->flashMessenger()->addMessage('Office Order is uploaded successfully');
					 	$this->auditTrailService->saveAuditTrail("UPDATE", "New Employee Details", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('newemployeelist');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'id' => $id,
				'form' => $form,
				//'empId' => $empId,
				'newEmployeeDetails' => $newEmployeeDetails,
				'keyphrase' => $this->keyphrase,
				));
		}
		else {
			return $this->redirect()->toRoute('newemployeelist');
		}   	
    }


    public function viewNewAddedEmployeeDetailsAction()
    {
    	$this->loginDetails();

		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			
			$form = new UpdateNewEmpDocForm();
			$employeeModel = new UpdateNewEmpDoc();
			$form->bind($employeeModel);

			$newEmployeeDetails = $this->employeeService->getNewEmployeeDetails($id);

			$new_employee_id = $this->employeeService->getNewEmployeeGeneratedId($id);

			$uploaded_file = $this->employeeService->getNewEmpFileUploaded($id);

			$request = $this->getRequest();
			if($request->isPost()){
				$form->setData($request->getPost());
				$data = array_merge_recursive(
					$request->getPost()->toArray(),
					$request->getFiles()->toArray()
				 ); 
				 $form->setData($data); 
				if($form->isValid()){
					try{
						$this->employeeService->updateNewEmpDoc($employeeModel);
						$this->flashMessenger()->addMessage('New Staff Document uploaded successfully');
						$this->auditTrailService->saveAuditTrail("UPDATE", "New Employee Document", "ALL", "SUCCESS");
						return $this->redirect()->toRoute('newemployeelist');
					}
					catch(\Exception $e) {
							die($e->getMessage());
							// Some DB Error happened, log it and let the user know
					}
				}
			}
			
			return new ViewModel(array(
				'id' => $id,
				'form' => $form,
				'newEmployeeDetails' => $newEmployeeDetails,
				'new_employee_id' => $new_employee_id,
				'new_employee_details_id' => $id,
				'uploaded_file' => $uploaded_file,
				'keyphrase' => $this->keyphrase,
				));
		}
		else {
			return $this->redirect()->toRoute('newemployeelist');
		} 
    }

    public function downloadRecruitedEmployeeDocAction()
    {
    	//get the param from the view file
		$file_location = $this->params()->fromRoute('filename',0);
		
		//extract the training id and column name
		$column_name = preg_replace('!\d+!', '', $file_location);
		$column_name = rtrim($column_name,"_");
		preg_match_all('!\d+!',$file_location, $id);
		$new_employee_id = implode(' ', $id[0]); 
		
		//get the location of the file from the database		
		$fileArray = $this->employeeService->getFileName($new_employee_id);
		$file;
		foreach($fileArray as $set){
			$file = $set['evidence_file'];
		}
		
		$mimetype = mime_content_type($file);
		$response = new Stream();
		$response->setStream(fopen($file, 'r'));
		$response->setStatusCode(200);
		$response->setStreamName(basename($file));
		$headers = new Headers();
		$headers->addHeaderLine('Content-Disposition:inline','attachment; filename="' . basename($file) .'"')
				->addHeaderLine('Content-Type', $mimetype)
				->addHeaderLine('Content-Length', filesize($file))
				->addHeaderLine('Expires','@0') // @0, because zf2 parses date as string to \DateTime() object
				->addHeaderLine('Cache-Control','must-revalidate')
				->addHeaderLine('Pragma','public')
				->addHeaderLine('Content-Transfer-Encoding:binary')
				->addHeaderLine('Accept-Ranges:bytes');
	
		$response->setHeaders($headers);
		return $response;
    }


    public function updateEmpInitialDetailsAction()
    {
    	$this->loginDetails();

    	$role = $this->userrole; 
    	$self_id = $this->employee_details_id;

		
		
       $form = new SearchForm();
	   $employeeList = $this->employeeService->listAllEmployees($this->organisation_id, $self_id);
	   	   
	   $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$empName = $this->getRequest()->getPost('employee_name');
				$empId = $this->getRequest()->getPost('emp_id');
				$department = $this->getRequest()->getPost('department');
				$employeeList = $this->employeeService->getEmployeeList($empName, $empId, $department, $this->organisation_id, $self_id, $role);
             }
         }
		
		return new ViewModel(array(
            'form' => $form,
			'keyphrase' => $this->keyphrase,
			'employeeList' => $employeeList
            ));
    }
	
	//to add relation details of employee for the first time
	public function addNewEmployeeRelationDetailsAction()
    {
		$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new NewEmployeeRelationForm();
				
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 //extract the data from the form
					 $data = $this->getRequest()->getPost('employeefields');
					 $employee_details_id = $data['employee_details_id'];
					 $relation = $data['newrelationdetails'];
					 try {
						 $this->employeeService->saveNewEmployeeRelation($employee_details_id, $relation);
						 $this->auditTrailService->saveAuditTrail("INSERT", "Add Employee Relation Details", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('addnewemployeeeducationdetails', array('id'=> $id));
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'form' => $form,
				'keyphrase' => $this->keyphrase,
				'employee_details_id' => $id
				));
		}
		else {
			return $this->redirect()->toRoute('emprelationdetail');
		}
    }
	
	//to add education details of employee for the first time
	public function addNewEmployeeEducationDetailsAction()
    {
		$this->loginDetails();
		
        //get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new NewEmployeeEducationForm();
				
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 //extract the data from the form
					 $data = $this->getRequest()->getPost('employeefields');
					 $employee_details_id = $data['employee_details_id'];
					 $education = $data['neweducationdetails'];
					 try {
						 $this->employeeService->saveNewEmployeeEducation($employee_details_id, $education);
						 $this->auditTrailService->saveAuditTrail("INSERT", "Add Employee Education Details", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('addnewemployeetrainingdetails', array('id'=> $id));
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'form' => $form,
				'keyphrase' => $this->keyphrase,
				'employee_details_id' => $id
				));
		}
		else {
			return $this->redirect()->toRoute('empeducation');
		}
    }

	
	//to add training details of employee for the first time
	public function addNewEmployeeTrainingDetailsAction()
    {
		$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new NewEmployeeTrainingForm();
				
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 $training = $this->getRequest()->getPost('employeefields');
					 var_dump($training);
					 die();
					 try {
						 $this->employeeService->saveNewEmployeeTraining($employeeModel);
						 $this->auditTrailService->saveAuditTrail("INSERT", "Add Employee Training Details", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('addnewemployeeemploymentdetails', array('id'=> $id));
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'form' => $form,
				'keyphrase' => $this->keyphrase,
				'employee_details_id' => $id
				));
		}
		else {
			return $this->redirect()->toRoute('emptrainingdetail');
		}
    }
	
	//to add employment details of employee for the first time
	public function addNewEmployeeEmploymentDetailsAction()
    {
		$this->loginDetails();
		
        //get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new NewEmployeeWorkExperienceForm();
				
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 $employment = $this->getRequest()->getPost('employeefields');
					 var_dump($employment);
					 die();
					 try {
						 $this->employeeService->saveNewEmployeeEmployment($employeeModel);
						 $this->auditTrailService->saveAuditTrail("INSERT", "Add Employee Employment Details", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('addnewemployeeresearchdetails', array('id'=> $id));
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'form' => $form,
				'keyphrase' => $this->keyphrase,
				'employee_details_id' => $id
				));
		}
		else {
			return $this->redirect()->toRoute('emptrainingdetail');
		}
    }
	
	//to add research details of employee for the first time
	public function addNewEmployeeResearchDetailsAction()
    {
		$this->loginDetails();
		
        //get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new NewEmployeePublicationForm();
				
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 $research = $this->getRequest()->getPost('employeefields');
					 var_dump($research);
					 die();
					 try {
						 $this->employeeService->saveNewEmployeeResearch($employeeModel);
						 $this->auditTrailService->saveAuditTrail("INSERT", "Add Employee Research", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('addnewemployeeresearchdetails', array('id'=> $id));
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'form' => $form,
				'keyphrase' => $this->keyphrase,
				'employee_details_id' => $id
				));
		}
		else {
			return $this->redirect()->toRoute('emppublication');
		}
    }
	
	//to add documents of employee for the first time
	public function addNewEmployeeDocumentsDetailsAction()
    {
		$this->loginDetails();
		
        //get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new NewEmployeeDocumentsForm();
			$employeeModel = new NewEmployeeDocuments();
			$form->bind($employeeModel);
					
			$request = $this->getRequest();
			if ($request->isPost()) {
				 $form->setData($request->getPost());
				 $data = array_merge_recursive(
					$request->getPost()->toArray(),
					$request->getFiles()->toArray()
				 ); 
				 $form->setData($data); 
				 if ($form->isValid()) {
					 $data = $form->getData();
					 try {
						 $this->employeeService->saveNewEmployeeDocuments($employeeModel);
						 $this->auditTrailService->saveAuditTrail("INSERT", "Add Employee Documents", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('viewnewemployeedetails', array('id' => $id));
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
				'employee_details_id' => $id
			);
		}
		else {
			return $this->redirect()->toRoute('employeelist');
		}
    }
	
	public function viewNewEmployeeDetailsAction()
	{
		$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new AddEmployeeForm();
			$employee_details = $this->employeeService->findEmployee($id, NULL);
			
			return array(
				'form' => $form,
				'employee_details' => $employee_details
				);
		}
		else {
			return $this->redirect()->toRoute('employeelist');
		}
	}
	
	public function addNewEmployeeDetailsAction()
    {
		$this->loginDetails();
		
        $form = new NewEmployeeEducationForm();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 var_dump($form);
				 die();
                 try {
					 $this->employeeService->saveEmployee($employeeModel);
					 return $this->redirect()->toRoute('addinitialworkexperience');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			));
    }
	
	public function empEducationAction()
    {
		$this->loginDetails();

		$role = $this->userrole; 
		$self_id = $this->employee_details_id;
		
       $form = new SearchForm();

       $employeeList = $this->employeeService->listAllEmployees($this->organisation_id, $self_id);
	   
	   $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$empName = $this->getRequest()->getPost('employee_name');
				$empId = $this->getRequest()->getPost('emp_id');
				$department = $this->getRequest()->getPost('department');
				$employeeList = $this->employeeService->getEmployeeList($empName, $empId, $department, $this->organisation_id, $self_id, $role);
             }
         }
		 
		
		return new ViewModel(array(
            'form' => $form,
			'keyphrase' => $this->keyphrase,
			'employeeList' => $employeeList
            ));
    }
	
	public function addEmployeeEducationAction()
    {
		$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new EmployeeEducationForm();
			$employeeModel = new EmployeeEducation();
			$form->bind($employeeModel);
			
			$country = $this->employeeService->listSelectData('country','country');
			$funding_category = $this->employeeService->listSelectData('funding_category','funding_type');

			$study_level = $this->employeeService->listSelectData('study_level','study_level');
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if(!$id)
					$id = $this->getRequest->getPost('employee_details_id');

				$data = array_merge_recursive(
                 $request->getPost()->toArray(),
                 $request->getFiles()->toArray()
                 ); 
                 $form->setData($data);
				 if ($form->isValid()) {
					 try {
						 $this->employeeService->saveEmployeeEducation($employeeModel);
						 return $this->redirect()->toRoute('addempeducation', array('id'=>$this->my_encrypt($id, $this->keyphrase)));
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'employees' => $this->employeeService->findEmployee($id, NULL),
				'id' => $id,
				'form' => $form,
				'employeeDetails' => $this->employeeService->findEmployeeExtraDetails($tableName='emp_education_details', $id),
				'country' => $country,
				'study_level' => $study_level,
				'funding_category' => $funding_category,
				'keyphrase' => $this->keyphrase,
				));
		}
		else {
			return $this->redirect()->toRoute('empeducation');
		}
    }
	
	public function viewEmployeeEducationAction()
    {
		$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);

		$message = NULL;
		
		if(is_numeric($id)){
			$form = new EmployeeEducationForm();
			
			return new ViewModel(array(
				'employees' => $this->employeeService->findEmployee($id, NULL),
				'id' => $id,
				'form' => $form,
				'keyphrase' => $this->keyphrase,
				'message' => $message,
				'employeeDetails' => $this->employeeService->findEmployeeExtraDetails($tableName='emp_education_details', $id)
				));
		}
		else {
			return $this->redirect()->toRoute('empeducation');
		}
    }


    public function editEmployeeEducationAction()
    {
    	$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$employeeEducationDetail = $this->employeeService->getEmployeeExtraDetail($id, $tableName = 'emp_education_details', NULL);
			$employee_details_id = $this->employeeService->getEmployeeDetailsId('emp_education_details', $id);
			$form = new EmployeeEducationForm();
			$employeeModel = new EmployeeEducation();
			$form->bind($employeeModel);
			
			$country = $this->employeeService->listSelectData('country','country');
			$funding_category = $this->employeeService->listSelectData('funding_category','funding_type');

			$study_level = $this->employeeService->listSelectData('study_level','study_level');
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				$data = array_merge_recursive(
                 $request->getPost()->toArray(),
                 $request->getFiles()->toArray()
                 ); 
                 $form->setData($data);
				 if ($form->isValid()) {
					 try {
						 $this->employeeService->saveEmployeeEducation($employeeModel);
						 return $this->redirect()->toRoute('editemployeeeducation', array('id'=>$this->my_encrypt($id, $this->keyphrase)));
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'id' => $id,
				'form' => $form,
				'country' => $country,
				'study_level' => $study_level,
				'funding_category' => $funding_category,
				'employeeEducationDetail' => $employeeEducationDetail,
				'keyphrase' => $this->keyphrase,
				));
		}
		else {
			return $this->redirect()->toRoute('empeducation');
		}
    }


    public function deleteEmployeeEducationAction()
    {
    	$this->loginDetails();
		 
		 //get the id of the travel authorization proposal
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			//Get the id of the employee details for redirection
			$employee_details_id = $this->employeeService->getEmployeeDetailsId('emp_education_details', $id);
			 try {
				 $result = $this->employeeService->deleteEmployeeEducation($id);
				 $this->auditTrailService->saveAuditTrail("DELETE", "Employee Education Details", "ALL", "SUCCESS");
				 $this->flashMessenger()->addMessage("You have successfully deleted education details");
				 $encrypted_id = $this->my_encrypt($employee_details_id, $this->keyphrase);
				 return $this->redirect()->toRoute('viewempeducation', array('id' => $encrypted_id));
				 //return $this->redirect()->toRoute('emptraveldetails');
			 }
			 catch(\Exception $e) {
					 die($e->getMessage());
					 // Some DB Error happened, log it and let the user know
			 }
	
		} else {
			return $this->redirect()->toRoute('viewempeducation');
		}
    }

    
    //Function to download the emp education details file
    public function downloadEmpEducationEvidenceFileAction()
    {
    	$this->loginDetails();
        //get the student id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $file = $this->employeeService->getEvidenceFileName($id, 'emp_education_details');
        
            
            $mimetype = mime_content_type($file);
            $response = new Stream();
            $response->setStream(fopen($file, 'r'));
            $response->setStatusCode(200);
            $response->setStreamName(basename($file));
            $headers = new Headers();
            $headers->addHeaderLine('Content-Disposition:inline','attachment; filename="' . basename($file) .'"')
                	->addHeaderLine('Content-Type',$mimetype)
                	->addHeaderLine('Content-Length', filesize($file))
                	->addHeaderLine('Expires','@0') // @0, because zf2 parses date as string to \DateTime() object
                	->addHeaderLine('Cache-Control','must-revalidate')
               		->addHeaderLine('Pragma','public')
               		->addHeaderLine('Content-Transfer-Encoding:binary')
               		->addHeaderLine('Accept-Ranges:bytes');
        
            $response->setHeaders($headers);
            return $response;
        }
        else
        {
            $this->redirect()->toRoute('empeducation');
        }
    }

	
	public function empPositionLevelAction()
    {
		$this->loginDetails();
		
       $form = new SearchForm();

       $employeeList = $this->employeeService->listAllEmployees($this->organisation_id, $self_id);
	   
	   $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$empName = $this->getRequest()->getPost('employee_name');
				$empId = $this->getRequest()->getPost('emp_id');
				$department = $this->getRequest()->getPost('department');
				$employeeList = $this->employeeService->getEmployeeListByLevel($empName, $empId, $department, $this->organisation_id);
             }
         }
		 //else {
			 //$employeeList = array();
		 //}
		
		return new ViewModel(array(
            'form' => $form,
			'keyphrase' => $this->keyphrase,
			'employeeList' => $employeeList
            ));
    }
	
	public function addEmployeePositionLevelAction()
    {
		$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new EmployeeLevelForm();
			$employeeModel = new EmployeeLevel();
			$form->bind($employeeModel);
			
			//cannot use selectData as we need unique
			$positionLevel = $this->employeeService->listSelectData('position_level', NULL);
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
						 $this->employeeService->saveEmployeeLevel($employeeModel);
						 return $this->redirect()->toRoute('emppositionlevel');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'employees' => $this->employeeService->findEmployee($id, NULL),
				'positionLevel' => $positionLevel,
				'id' => $id,
				'form' => $form,
				));
		}
		else {
			return $this->redirect()->toRoute('emppositionlevel');
		}
    }
		
	public function viewEmployeePositionLevelAction()
    {
		$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new EmployeeLevelForm();
			
			return new ViewModel(array(
				'employees' => $this->employeeService->findEmployee($id, NULL),
				'id' => $id,
				'form' => $form,
				'employeeDetails' => $this->employeeService->findEmployeeLevelDetails($id)
				));
		}
		else {
			return $this->redirect()->toRoute('emppositionlevel');
		}
    }

	public function empPositionTitleAction()
    {
		$this->loginDetails();

		$role = $this->userrole; 
		$self_id = $this->employee_details_id;
		
       $form = new SearchForm();

       $employeeList = $this->employeeService->listAllEmployees($this->organisation_id, $self_id);
	   
	   $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$empName = $this->getRequest()->getPost('employee_name');
				$empId = $this->getRequest()->getPost('emp_id');
				$department = $this->getRequest()->getPost('department');
				$employeeList = $this->employeeService->getEmployeeList($empName, $empId, $department, $this->organisation_id, $self_id, $role);
             }
         }
		 //else {
			 //$employeeList = array();
		 //}
		
		return new ViewModel(array(
            'form' => $form,
			'keyphrase' => $this->keyphrase,
			'employeeList' => $employeeList
            ));
    }
	
	public function addEmployeePositionTitleAction()
    {
		$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new EmployeeTitleForm();
			$employeeModel = new EmployeeTitle();
			$form->bind($employeeModel);
			$message = NULL;
			
			$positionTitle = $this->employeeService->listSelectData($tableName='position_title', $columnName='description');
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
						 $this->employeeService->saveEmployeeTitle($employeeModel);
						 $this->flashMessenger()->addMessage('Position Title was successfully added');
						 $this->auditTrailService->saveAuditTrail("INSERT", "Add Employee Position Title", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('emppositiontitle');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'employees' => $this->employeeService->findEmployee($id, NULL),
				'id' => $id,
				'form' => $form,
				'positionTitle' => $positionTitle,
				'message' => $message
				));
		}
		else {
			return $this->redirect()->toRoute('emppositiontitle');
		}
    }
	
	public function viewEmployeePositionTitleAction()
    {
		$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new EmployeeTitleForm();
			
			return new ViewModel(array(
				'employees' => $this->employeeService->findEmployee($id, NULL),
				'id' => $id,
				'form' => $form,
				'employeeDetails' => $this->employeeService->findEmployeeTitleDetails($id)
				));
		}
		else {
			return $this->redirect()->toRoute('emppositiontitle');
		}
    }
	
	//to add the profile picture
	public function empProfilePictureAction()
    {
		$this->loginDetails();

		$role = $this->userrole; 
		$self_id = $this->employee_details_id;
		
       $form = new SearchForm();

       $employeeList = $this->employeeService->listAllEmployees($this->organisation_id, $self_id);

       $message = NULL;
	   
	   $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$empName = $this->getRequest()->getPost('employee_name');
				$empId = $this->getRequest()->getPost('emp_id');
				$department = $this->getRequest()->getPost('department');
				$employeeList = $this->employeeService->getEmployeeList($empName, $empId, $department, $this->organisation_id, $self_id, $role);
             }
         }
		
		return new ViewModel(array(
            'form' => $form,
			'keyphrase' => $this->keyphrase,
			'employeeList' => $employeeList,
			'message' => $message,
            ));
    }
	
	public function addEmployeeProfilePictureAction()
    {
		$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new EmployeeProfilePictureForm();
			$employeeModel = new EmployeeProfilePicture();
			$form->bind($employeeModel);

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
					 try {
						 $this->employeeService->saveEmployeeProfilePicture($employeeModel);
						 $this->flashMessenger()->addMessage('Employee Profile was successfully uploaded');
						 $this->auditTrailService->saveAuditTrail("INSERT/UPDATE", "Employee Details", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('empprofilepicture');
					 }
					 catch(\Exception $e) {
					 	$message = 'Failure';
					 	$this->flashMessenger()->addMessage($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'id' => $id,
				'form' => $form,
				'employees' => $this->employeeService->findEmployee($id, NULL),
				'profilePicture' => $this->employeeService->getEmployeeProfilePicture($id),
				'message' => $message,
				));
		}
		else {
			return $this->redirect()->toRoute('empprofilepicture');
		}
    }
    
    //to edit Personal Details
    public function empPersonalDetailsAction()
    {
		$this->loginDetails();

		$role = $this->userrole; 
		$self_id = $this->employee_details_id;
		
       $form = new SearchForm();

       $employeeList = $this->employeeService->listAllEmployees($this->organisation_id, $self_id);
	   
	   $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$empName = $this->getRequest()->getPost('employee_name');
				$empId = $this->getRequest()->getPost('emp_id');
				$department = $this->getRequest()->getPost('department');
				$employeeList = $this->employeeService->getEmployeeList($empName, $empId, $department, $this->organisation_id, $self_id, $role);
             }
         }
		 
		return new ViewModel(array(
            'form' => $form,
			'keyphrase' => $this->keyphrase,
			'employeeList' => $employeeList
            ));
    }

    
    //to edit Permanent Address
    public function empPermanentAddressAction()
    {
		$this->loginDetails();

		$role = $this->userrole; 
		$self_id = $this->employee_details_id;
		
       $form = new SearchForm();

       $employeeList = $this->employeeService->listAllEmployees($this->organisation_id, $self_id);
	   
	   $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$empName = $this->getRequest()->getPost('employee_name');
				$empId = $this->getRequest()->getPost('emp_id');
				$department = $this->getRequest()->getPost('department');
				$employeeList = $this->employeeService->getEmployeeList($empName, $empId, $department, $this->organisation_id, $self_id, $role);
             }
         }
		
		return new ViewModel(array(
            'form' => $form,
			'keyphrase' => $this->keyphrase,
			'employeeList' => $employeeList
            ));
    }


    //Function to update the employee details
    public function updateEmployeeDetailsAction()
    {
    	$this->loginDetails();

    	$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new EmployeeDetailForm($this->serviceLocator);
			$employeeModel = new EmployeeDetail();
			$form->bind($employeeModel);

			$detailForm = new EmployeePersonalDetailsForm();
			
			$country = $this->employeeService->listSelectData($tableName='country', $columnName='country');
			$nationality = $this->employeeService->listSelectData($tableName='nationality', $columnName='nationality');
			$bloodGroup = $this->employeeService->listSelectData($tableName='blood_group', $columnName='blood_group');
			$religion = $this->employeeService->listSelectData($tableName='religion', $columnName='religion');
			$maritialStatus = $this->employeeService->listSelectData($tableName = 'maritial_status', $columnName = 'maritial_status');
			$gender = $this->employeeService->listSelectData($tableName = 'gender', $columnName = 'gender');

			$employeeDetails = $this->employeeService->findEmployee($id, 'Personal Details');

			$permanentAddress = $this->employeeService->findEmployee($id, 'Permanent Address');

			$message = NULL;
			
			$request = $this->getRequest();
         		if ($request->isPost()) {
             		$form->setData($request->getPost());
             		if ($form->isValid()) {
	                 try {
	                 	$dzongkhag = $this->getRequest()->getPost('emp_dzongkhag');
	                 	$gewog = $this->getRequest()->getPost('emp_gewog');
	                 	$village = $this->getRequest()->getPost('emp_village');

						 $this->employeeService->updateEmployeeDetails($employeeModel, $dzongkhag, $gewog, $village);
						 $this->auditTrailService->saveAuditTrail("UPDATE", "Employee Details", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage('Employee Address was successfully updated.');
						 return $this->redirect()->toRoute('updateemployeedetails', array('id'=>$this->my_encrypt($id, $this->keyphrase)));
					 }
					 catch(\Exception $e) {
					 	$message = 'Failure';
					 	$this->flashMessenger()->addMessage($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
	             }
	         }
			
			return new ViewModel(array(
				'id' => $id,
				'form' => $form,
				'detailForm' => $detailForm,
				'country' => $country,
				'nationality' => $nationality,
				'bloodGroup' => $bloodGroup,
				'religion' => $religion,
				'maritialStatus' => $maritialStatus,
				'gender' => $gender,
				'employeeDetails' => $employeeDetails,
				'empPermanentAddr' => $this->employeeService->getEmpPermanentAddress($id),
				'permanentAddress' => $permanentAddress,
				'message' => $message,
				'keyphrase' => $this->keyphrase,
			));
		}else {
			return $this->redirect()->toRoute('employeelist');
		}
    }


    public function editEmployeePersonalDetailsAction()
    {
		$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$detailForm = new EmployeePersonalDetailsForm();
			$employeeModel = new EmployeeDetail();
			$detailForm->bind($employeeModel);

			$form = new EmployeeDetailForm($this->serviceLocator);

			$employeeDetails = $this->employeeService->findEmployee($id, 'Personal Details');

			$permanentAddress = $this->employeeService->findEmployee($id, 'Permanent Address');

			$country = $this->employeeService->listSelectData($tableName='country', $columnName='country');
			$nationality = $this->employeeService->listSelectData($tableName='nationality', $columnName='nationality');
			$bloodGroup = $this->employeeService->listSelectData($tableName='blood_group', $columnName='blood_group');
			$religion = $this->employeeService->listSelectData($tableName='religion', $columnName='religion');
			$maritialStatus = $this->employeeService->listSelectData($tableName = 'maritial_status', $columnName = 'maritial_status');
			$gender = $this->employeeService->listSelectData($tableName = 'gender', $columnName = 'gender');

			$message = NULL;
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $detailForm->setData($request->getPost());
				 if ($detailForm->isValid()) { 
				 	$data = $this->params()->fromPost();
				 	$previous_emp_id = $data['employeedetails']['previous_emp_id'];
					 try {
						 $this->employeeService->updateEmployeePersonalDetails($employeeModel, $previous_emp_id);
						 $this->auditTrailService->saveAuditTrail("EDIT", "Employee Details", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage('Employee Personal Details was successfully updated.');
						 return $this->redirect()->toRoute('updateemployeedetails', array('id'=>$this->my_encrypt($id, $this->keyphrase)));
					 }
					 catch(\Exception $e) {
					 	$message = 'Failure';
					 	$this->flashMessenger()->addMessage($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'id' => $id,
				'form' => $form,
				'country' => $country,
				'nationality' => $nationality,
				'bloodGroup' => $bloodGroup,
				'religion' => $religion,
				'maritialStatus' => $maritialStatus,
				'gender' => $gender,
				'detailForm' => $detailForm,
				'employeeDetails' => $employeeDetails,
				'permanentAddress' => $permanentAddress,
				'keyphrase' => $this->keyphrase,
				'message' => $message,
				));
		}
		else {
			return $this->redirect()->toRoute('employeelist');
		}
    }

	
	public function editEmployeePermanentAddressAction()
    {
		$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$addressForm = new EmployeePermanentAddressForm();
			$employeeModel = new EmployeePersonalDetails();
			$addressForm->bind($employeeModel);

			$form = new EmployeePersonalDetailsForm();

			$permanentAddress = $this->employeeService->findEmployee($id, 'Permanent Address');

			$dzongkhag = $this->employeeService->listSelectData($tableName = 'dzongkhag', $columnName = 'dzongkhag_name');
			$gewog = $this->employeeService->listSelectData($tableName = 'gewog', $columnName = 'gewog_name');
			$village = $this->employeeService->listSelectData($tableName = 'village', $columnName = 'village_name');

			$message = NULL;
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $addressForm->setData($request->getPost());
				 if ($addressForm->isValid()) {
					 try {
						 $this->employeeService->updateEmployeeDetails($employeeModel);
						 $this->auditTrailService->saveAuditTrail("EDIT", "Edit Employee Permanent Address", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage('Employee Permanent Address was successfully updated.');
						 return $this->redirect()->toRoute('updateemployeedetails', array('id' => $this->my_encrypt($id, $this->keyphrase)));
					 }
					 catch(\Exception $e) {
					 	$message = 'Failure';
					 	$this->flashMessenger()->addMessage($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'id' => $id,
				'form' => $form,
				'dzongkhag' => $dzongkhag,
				'gewog' => $gewog,
				'village' => $village,
				'addressForm' => $addressForm,
				'permanentAddress' => $permanentAddress,
				'keyphrase' => $this->keyphrase,
				'message' => $message,
				));
		}
		else {
			return $this->redirect()->toRoute('employeelist');
		}
    }


    public function editEmployeeEmploymentDetailsAction()
    {
		$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$employmentForm = new EmployeeEmploymentDetailsForm();
			$employeeModel = new EmployeePersonalDetails();
			$addressForm->bind($employeeModel);

			$form = new EmployeePersonalDetailsForm();

			$employmentDetails = $this->employeeService->findEmployee($id, 'Employment Details');
			$positionTitleDetails = $this->employeeService->findEmployee($id, 'Position Title Details');
			$positionLevelDetails = $this->employeeService->findEmployee($id, 'Position Level Details');

			$organisation = $this->employeeService->listSelectData($tableName = 'organisation', $columnName = 'organisation_name');
			$department = $this->employeeService->listSelectData($tableName = 'departments', $columnName = 'department_name');
			$departmentUnit = $this->employeeService->listSelectData($tableName = 'department_units', $columnName = 'unit_name');
			$positionLevel = $this->employeeService->listSelectData($tableName = 'position_level', $columnName = 'position_level');
			$positionTitle = $this->employeeService->listSelectData($tableName = 'position_title', $columnName = 'position_title');
			$employeeType = $this->employeeService->listSelectData($tableName = 'employee_type', $columnName = 'employee_type');

			$message = NULL;
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $employmentForm->setData($request->getPost());
				 if ($employmentForm->isValid()) {
				 	var_dump($employmentForm); die();
				 	die();
					 try {
						 $this->employeeService->saveEmployeePermanentAddress($employeeModel);
						 $this->auditTrailService->saveAuditTrail("EDIT", "Edit Employee Permanent Address", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage('Employee Employment Details was successfully updated.');
						 return $this->redirect()->toRoute('updateemployeedetails', array('id' => $this->my_encrypt($id, $this->keyphrase)));
					 }
					 catch(\Exception $e) {
					 	$message = 'Failure';
					 	$this->flashMessenger()->addMessage($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'id' => $id,
				'form' => $form,
				'employmentDetails' => $employmentDetails,
				'positionTitleDetails' => $positionTitleDetails,
				'positionLevelDetails' => $positionLevelDetails,
				'organistion' => $organisation,
				'department' => $department,
				'departmentUnit' => $departmentUnit,
				'positionLevel' => $positionLevel,
				'positionTitle' => $positionTitle,
				'employeeType' => $employeeType,
				'employmentForm' => $employmentForm,
				'permanentAddress' => $permanentAddress,
				'keyphrase' => $this->keyphrase,
				'message' => $message,
				));
		}
		else {
			return $this->redirect()->toRoute('employeelist');
		}
    }


	public function empWorkExperienceAction()
    {
		$this->loginDetails();

		$role = $this->userrole; 
		$self_id = $this->employee_details_id;
		//var_dump($self_id); die();
		
       $form = new SearchForm();
	   $empAwards = $this->employeeService->getExtraCurricularDetails($tableName = 'emp_awards', $this->organisation_id, $self_id);
	   $employeeList = $this->employeeService->listAllEmployees($this->organisation_id, $self_id);
	   
	   $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$empName = $this->getRequest()->getPost('employee_name');
				$empId = $this->getRequest()->getPost('emp_id');
				$department = $this->getRequest()->getPost('department');
				$employeeList = $this->employeeService->getEmployeeList($empName, $empId, $department, $this->organisation_id, $self_id, $role);
             }
         }
		 //else {
			 //$employeeList = array();
		 //}
		
		return new ViewModel(array(
            'form' => $form,
			'keyphrase' => $this->keyphrase,
			'empAwards' => $empAwards,
			'employeeList' => $employeeList
            ));
    }
	
	public function addEmployeeWorkExperienceAction()
    {
		$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new EmployeeWorkExperienceForm();
			$employeeModel = new EmployeeWorkExperience();
			$form->bind($employeeModel);
			
			//RUB Work Experience Form
			$rubForm = new EmployeeRubWorkExperienceForm($this->serviceLocator);
			
			$organisationList = $this->employeeService->listSelectData('organisation','organisation_name');
			$positionLevel = $this->employeeService->listSelectData('position_level' , 'position_level');
			$positionTitle = $this->employeeService->listSelectData('position_title' , 'position_title');
			$occupationalGroup = $this->employeeService->listSelectData('major_occupational_group' , 'major_occupational_group');
			$positionCategory = $this->employeeService->listSelectData('position_category' , 'category');
			
			$message = NULL;
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if(!$id)
					$id = $this->getRequest->getPost('employee_details_id');

				$data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
                ); 
                $form->setData($data);
				 if ($form->isValid()) { 
					 try {
						 $this->employeeService->saveEmployeeWorkExperience($employeeModel);
						 $this->flashMessenger()->addMessage('Work Experience was successfully added');
						 $this->auditTrailService->saveAuditTrail("INSERT", "Add Employee Work Experience", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('addempworkexperience', array('id'=>$this->my_encrypt($id, $this->keyphrase)));
					 }
					 catch(\Exception $e) {
							 $message = 'Failure';
							 $this->flashMessenger()->addMessage($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'employees' => $this->employeeService->findEmployee($id, NULL),
				'id' => $id,
				'form' => $form,
				'rubForm' => $rubForm,
				'employeeDetails' => $this->employeeService->findEmployeeRUBExtraDetails($tableName='emp_employment_record', $id),
				'nonRUBDetails' => $this->employeeService->findEmployeeNonRUBExtraDetails($tableName='emp_employment_record', $id),
				'organisationList' => $organisationList,
				'positionLevel' => $positionLevel,
				'positionTitle' => $positionTitle,
				'occupationalGroup' => $occupationalGroup,
				'positionCategory' => $positionCategory,
				'keyphrase' => $this->keyphrase,
				'message' => $message,
				));
		}
		else {
			return $this->redirect()->toRoute('empworkexperience');
		}
    }
	
	public function addEmployeeRubWorkExperienceAction()
    {
		$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);		
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);		
		
		if(is_numeric($id)){
			$rubForm = new EmployeeRubWorkExperienceForm($this->serviceLocator);
			$employeeModel = new EmployeeWorkExperience();
			$rubForm->bind($employeeModel);
			
			//work experience form
			$form = new EmployeeWorkExperienceForm();
			
			$organisationList = $this->employeeService->listSelectData('organisation','organisation_name');
			$positionLevel = $this->employeeService->listSelectData('position_level' , 'position_level');
			$positionTitle = $this->employeeService->listSelectData('position_title' , 'position_title');
			$occupationalGroup = $this->employeeService->listSelectData('major_occupational_group' , 'major_occupational_group');
			$positionCategory = $this->employeeService->listSelectData('position_category' , 'category');
			
			
			$message = NULL;
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $rubForm->setData($request->getPost());
				 if(!$id)
					$id = $this->getRequest->getPost('employee_details_id');

				$data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
                ); 
                $rubForm->setData($data);
				 if ($rubForm->isValid()) { 
					 try {
					 	$occupationalGroup = $this->getRequest()->getPost('occupational_group');
					 	$positionLevel = $this->getRequest()->getPost('position_level');
					 	$positionTitle = $this->getRequest()->getPost('position_title');
					 	$positionCategory = $this->getRequest()->getPost('position_category');

						 $this->employeeService->saveRubEmployeeWorkExperience($employeeModel, $occupationalGroup, $positionLevel, $positionTitle, $positionCategory);
						 $this->flashMessenger()->addMessage('Work Experience was successfully added');
						 $this->auditTrailService->saveAuditTrail("INSERT", "Add Employee Work Experience", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('addempworkexperience', array('id'=>$this->my_encrypt($id, $this->keyphrase)));
					 }
					 catch(\Exception $e) {
					 	$message = 'Failure';
					 	$this->flashMessenger()->addMessage($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'employees' => $this->employeeService->findEmployee($id, NULL),
				'id' => $id,
				'form' => $form,
				'rubForm' => $rubForm,
				'employeeDetails' => $this->employeeService->findEmployeeRUBExtraDetails($tableName='emp_employment_record', $id),
				'nonRUBDetails' => $this->employeeService->findEmployeeNonRUBExtraDetails($tableName='emp_employment_record', $id),
				'organisationList' => $organisationList,
				'positionLevel' => $positionLevel,
				'positionTitle' => $positionTitle,
				'occupationalGroup' => $occupationalGroup,
				'positionCategory' => $positionCategory,
				'keyphrase' => $this->keyphrase,
				'message' => $message
				));
		}
		else {
			return $this->redirect()->toRoute('empworkexperience');
		}
    }
	
	public function viewEmployeeWorkExperienceAction()
    {
		$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new EmployeeWorkExperienceForm();

			$message = NULL;
			
			return new ViewModel(array(
				'employees' => $this->employeeService->findEmployee($id, NULL),
				'id' => $id,
				'form' => $form,
				'keyphrase' => $this->keyphrase,
				'message' => $message,
				'employeeDetails' => $this->employeeService->findEmployeeRUBExtraDetails($tableName='emp_employment_record', $id),
				'nonRUBDetails' => $this->employeeService->findEmployeeNonRUBExtraDetails($tableName='emp_employment_record', $id),
				));
		}
		else {
			return $this->redirect()->toRoute('empworkexperience');
		}
    }

    public function editEmployeeNonRubWorkExperienceAction()
    {
    	$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){ 
			$employeeWorkExperience = $this->employeeService->getEmployeeExtraDetail($id, $tableName = 'emp_employment_record', 'NON-RUB');
			$employee_details_id = $this->employeeService->getEmployeeDetailsId('emp_employment_record', $id);

			$form = new EmployeeWorkExperienceForm();
			$employeeModel = new EmployeeWorkExperience();
			$form->bind($employeeModel); 
			
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
					 try {
						 $this->employeeService->updateEmployeeWorkExperience($employeeModel);
						 $this->flashMessenger()->addMessage('Employee Non RUB Experience was successfully edited');
						 $this->auditTrailService->saveAuditTrail("EDIT", "Employee Employment Record", "ALL", "SUCCESS");
						 $encrypted_id = $this->my_encrypt($employee_details_id, $this->keyphrase);
						 return $this->redirect()->toRoute('viewempworkexperience', array('id' => $encrypted_id));
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'id' => $id,
				'form' => $form,
				'employeeWorkExperience' => $employeeWorkExperience,
				'keyphrase' => $this->keyphrase,
				'message' => $message
				));
		}
		else {
			return $this->redirect()->toRoute('empworkexperience');
		}
    }


    public function editEmployeeRubWorkExperienceAction()
    {
    	$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){ 
			$employeeWorkExperience = $this->employeeService->getEmployeeExtraDetail($id, $tableName = 'emp_employment_record', 'RUB');
			$employee_details_id = $this->employeeService->getEmployeeDetailsId('emp_employment_record', $id);

			$form = new EmployeeRubWorkExperienceForm($this->serviceLocator);
			$employeeModel = new EmployeeWorkExperience();
			$form->bind($employeeModel); 

			$organisationList = $this->employeeService->listSelectData('organisation','organisation_name');
			$positionLevel = $this->employeeService->listSelectData('position_level' , 'position_level');
			$positionTitle = $this->employeeService->listSelectData('position_title' , 'position_title');
			$occupationalGroup = $this->employeeService->listSelectData('major_occupational_group' , 'major_occupational_group');
			$positionCategory = $this->employeeService->listSelectData('position_category' , 'category');
			
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
					 try {
					 	$occupationalGroup = $this->getRequest()->getPost('occupational_group');
					 	$positionLevel = $this->getRequest()->getPost('position_level');
					 	$positionTitle = $this->getRequest()->getPost('position_title');
					 	$positionCategory = $this->getRequest()->getPost('position_category');
						 $this->employeeService->saveRubEmployeeWorkExperience($employeeModel, $occupationalGroup, $positionLevel, $positionTitle, $positionCategory);
						 $this->flashMessenger()->addMessage('Employee Non RUB Experience was successfully edited');
						 $this->auditTrailService->saveAuditTrail("EDIT", "Employee Employment Record", "ALL", "SUCCESS");
						 $encrypted_id = $this->my_encrypt($employee_details_id, $this->keyphrase);
						 return $this->redirect()->toRoute('viewempworkexperience', array('id' => $encrypted_id));
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'id' => $id,
				'form' => $form,
				'organisationList' => $organisationList,
				'positionLevel' => $positionLevel,
				'positionTitle' => $positionTitle,
				'occupationalGroup' => $occupationalGroup,
				'positionCategory' => $positionCategory,
				'employeeWorkExperience' => $employeeWorkExperience,
				'keyphrase' => $this->keyphrase,
				'message' => $message
				));
		}
		else {
			return $this->redirect()->toRoute('empworkexperience');
		}
    }


    public function deleteEmployeeWorkExperienceAction()
    {
    	$this->loginDetails();
		 
		 //get the id of the travel authorization proposal
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			//Get the id of the employee details for redirection
			$employee_details_id = $this->employeeService->getEmployeeDetailsId('emp_employment_record', $id);
			 try {
				 $result = $this->employeeService->deleteEmployeeWorkExperience($id);
				 $this->auditTrailService->saveAuditTrail("DELETE", "Employee Employment Record", "ALL", "SUCCESS");
				 $this->flashMessenger()->addMessage("You have successfully deleted employment record details");
				 $encrypted_id = $this->my_encrypt($employee_details_id, $this->keyphrase);
				 return $this->redirect()->toRoute('viewempworkexperience', array('id' => $encrypted_id));
				 //return $this->redirect()->toRoute('emptraveldetails');
			 }
			 catch(\Exception $e) {
					 die($e->getMessage());
					 // Some DB Error happened, log it and let the user know
			 }
	
		} else {
			return $this->redirect()->toRoute('empworkexperience');
		}
    }


    //Function to download the emp education details file
    public function downloadEmpWorkExperienceFileAction()
    {
    	$this->loginDetails();
        //get the student id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $file = $this->employeeService->getEvidenceFileName($id, 'emp_employment_record');
        
            $mimetype = mime_content_type($file);
            $response = new Stream();
            $response->setStream(fopen($file, 'r'));
            $response->setStatusCode(200);
            $response->setStreamName(basename($file));
            $headers = new Headers();
            $headers->addHeaderLine('Content-Disposition:inline','attachment; filename="' . basename($file) .'"')
                	->addHeaderLine('Content-Type', $mimetype)
                	->addHeaderLine('Content-Length',filesize($file))
                	->addHeaderLine('Expires','@0') // @0, because zf2 parses date as string to \DateTime() object
                	->addHeaderLine('Cache-Control','must-revalidate')
                	->addHeaderLine('Pragma','public')
                	->addHeaderLine('Content-Transfer-Encoding:binary')
                	->addHeaderLine('Accept-Ranges:bytes');
           
            $response->setHeaders($headers);
            return $response;
        }
        else
        {
            $this->redirect()->toRoute('empworkexperience');
        }
    }


	
	public function empRelationDetailAction()
    {
		$this->loginDetails();

		$role = $this->userrole; 
		$self_id = $this->employee_details_id;
		
       $form = new SearchForm();

       $employeeList = $this->employeeService->listAllEmployees($this->organisation_id, $self_id);
	   
	   $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$empName = $this->getRequest()->getPost('employee_name');
				$empId = $this->getRequest()->getPost('emp_id');
				$department = $this->getRequest()->getPost('department');
				$employeeList = $this->employeeService->getEmployeeList($empName, $empId, $department, $this->organisation_id, $self_id, $role);
             }
         }
		 
		
		return new ViewModel(array(
            'form' => $form,
			'keyphrase' => $this->keyphrase,
			'employeeList' => $employeeList
            ));
    }
	
	public function addEmployeeRelationDetailAction()
    {
		$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new EmployeeRelationDetailForm();
			$employeeModel = new EmployeeRelationDetail();
			$form->bind($employeeModel);
			
			$nationality = $this->employeeService->listSelectData('nationality','nationality');
			$gender = $this->employeeService->listSelectData('gender','gender');

			$relationType = $this->employeeService->listSelectData('relation_type', 'relation');
			
			$message = NULL;
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if(!$id)
					$id = $this->getRequest->getPost('employee_details_id');
				 if ($form->isValid()) {
					 try {
						 $this->employeeService->saveEmployeeRelation($employeeModel);
						 $this->flashMessenger()->addMessage('Relation was successfully added');
						 $this->auditTrailService->saveAuditTrail("INSERT", "Add Employee Relation Detail", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('addemprelationdetail', array('id'=>$this->my_encrypt($id, $this->keyphrase)));
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'employees' => $this->employeeService->findEmployee($id, NULL),
				'id' => $id,
				'form' => $form,
				'employeeDetails' => $this->employeeService->findEmployeeExtraDetails($tableName='emp_relation_details', $id),
				'nationality' => $nationality,
				'gender' => $gender,
				'relationType' => $relationType,
				'message' => $message
				));
		}
		else {
			return $this->redirect()->toRoute('emprelationdetail');
		}
    }
	
	public function viewEmployeeRelationDetailAction()
    {
		$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new EmployeeRelationDetailForm();

			$message = NULL;
			
			return new ViewModel(array(
				'employees' => $this->employeeService->findEmployee($id, NULL),
				'id' => $id,
				'form' => $form,
				'keyphrase' => $this->keyphrase,
				'message' => $message,
				'employeeDetails' => $this->employeeService->findEmployeeExtraDetails($tableName='emp_relation_details', $id)
				));
		}
		else {
			return $this->redirect()->toRoute('emprelationdetail');
		}
    }


    public function editEmployeeRelationDetailAction()
    {
    	$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$employeeRelationDetail = $this->employeeService->getEmployeeExtraDetail($id, $tableName = 'emp_relation_details', NULL);
			$employee_details_id = $this->employeeService->getEmployeeDetailsId('emp_relation_details', $id);
			$form = new EmployeeRelationDetailForm();
			$employeeModel = new EmployeeRelationDetail();
			$form->bind($employeeModel);
			
			$nationality = $this->employeeService->listSelectData('nationality','nationality');
			$gender = $this->employeeService->listSelectData('gender','gender');

			$relationType = $this->employeeService->listSelectData('relation_type', 'relation');
			
			$message = NULL;
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
						 $this->employeeService->saveEmployeeRelation($employeeModel);
						 $this->flashMessenger()->addMessage('Relation was successfully edited');
						 $this->auditTrailService->saveAuditTrail("EDIT", "Add Employee Relation Detail", "ALL", "SUCCESS");
						 $encrypted_id = $this->my_encrypt($employee_details_id, $this->keyphrase);
						 return $this->redirect()->toRoute('viewemprelationdetail', array('id' => $encrypted_id));
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				//'employees' => $this->employeeService->findEmployee($id, NULL),
				'id' => $id,
				'form' => $form,
				//'employeeDetails' => $this->employeeService->findEmployeeExtraDetails($tableName='emp_relation_details', $id),
				'nationality' => $nationality,
				'gender' => $gender,
				'relationType' => $relationType,
				'employeeRelationDetail' => $employeeRelationDetail,
				'message' => $message
				));
		}
		else {
			return $this->redirect()->toRoute('emprelationdetail');
		}
    }


    public function deleteEmployeeRelationDetailAction()
    {
    	$this->loginDetails();
		 
		 //get the id of the travel authorization proposal
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			//Get the id of the employee details for redirection
			$employee_details_id = $this->employeeService->getEmployeeDetailsId('emp_relation_details', $id);
			 try {
				 $result = $this->employeeService->deleteEmployeeRelationDetail($id);
				 $this->auditTrailService->saveAuditTrail("DELETE", "Employee Relation Details", "ALL", "SUCCESS");
				 $this->flashMessenger()->addMessage("You have successfully deleted employee relation details");
				 $encrypted_id = $this->my_encrypt($employee_details_id, $this->keyphrase);
				 return $this->redirect()->toRoute('viewemprelationdetail', array('id' => $encrypted_id));
				 //return $this->redirect()->toRoute('emptraveldetails');
			 }
			 catch(\Exception $e) {
					 die($e->getMessage());
					 // Some DB Error happened, log it and let the user know
			 }
	
		} else {
			return $this->redirect()->toRoute('emprelationdetail');
		}
    }
	
	public function empTrainingDetailAction()
    {
		$this->loginDetails();

		$role = $this->userrole; 
		$self_id = $this->employee_details_id;
		
       $form = new SearchForm();
	   $empAwards = $this->employeeService->getExtraCurricularDetails($tableName = 'emp_awards', $this->organisation_id, $self_id);
	   $employeeList = $this->employeeService->listAllEmployees($this->organisation_id, $self_id);
	   
	   $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$empName = $this->getRequest()->getPost('employee_name');
				$empId = $this->getRequest()->getPost('emp_id');
				$department = $this->getRequest()->getPost('department');
				$employeeList = $this->employeeService->getEmployeeList($empName, $empId, $department, $this->organisation_id, $self_id, $role);
             }
         }
		 //else {
			 //$employeeList = array();
		//}
		
		return new ViewModel(array(
            'form' => $form,
			'keyphrase' => $this->keyphrase,
			'empAwards' => $empAwards,
			'employeeList' => $employeeList
            ));
    }
	
	public function addEmployeeTrainingDetailAction()
    {
		$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new EmployeeTrainingsForm();
			$employeeModel = new EmployeeTrainings();
			$form->bind($employeeModel);
			
			$message = NULL;
			$fundingSource = $this->employeeService->listSelectData('funding_category','funding_type');
			$countryList = $this->employeeService->listSelectData('country','country');
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if(!$id)
					$id = $this->getRequest->getPost('employee_details_id');
				$data = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                 ); 
                $form->setData($data); 
				 if ($form->isValid()) {
					 try {
						 $this->employeeService->saveEmployeeTraining($employeeModel);
						 $this->flashMessenger()->addMessage('Training was successfully added');
						 $this->auditTrailService->saveAuditTrail("INSERT", "Add Employee Training", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('addemptrainingdetail', array('id'=>$this->my_encrypt($id, $this->keyphrase)));
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'employees' => $this->employeeService->findEmployee($id, NULL),
				'id' => $id,
				'form' => $form,
				'fundingSource' => $fundingSource,
				'countryList' => $countryList,
				'keyphrase' => $this->keyphrase,
				'employeeDetails' => $this->employeeService->findEmployeeExtraDetails($tableName='emp_previous_trainings', $id),
				'message' => $message
				));
		}
		else {
			return $this->redirect()->toRoute('emptrainingdetail');
		}
    }
	
	public function viewEmployeeTrainingDetailAction()
    {
		$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);

		$message = NULL;
		
		if(is_numeric($id)){
			$form = new EmployeeTrainingsForm();
			
			return new ViewModel(array(
				'employees' => $this->employeeService->findEmployee($id, NULL),
				'id' => $id,
				'form' => $form,
				'keyphrase' => $this->keyphrase,
				'message' => $message,
				'employeeDetails' => $this->employeeService->findEmployeeExtraDetails($tableName='emp_previous_trainings', $id)
				));
		}
		else {
			return $this->redirect()->toRoute('emptrainingdetail');
		}
    }


    public function editEmployeeTrainingDetailAction()
    {
    	$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$employeeTrainingDetail = $this->employeeService->getEmployeeExtraDetail($id, $tableName = 'emp_previous_trainings', NULL);
			$employee_details_id = $this->employeeService->getEmployeeDetailsId('emp_previous_trainings', $id);
			$form = new EmployeeTrainingsForm();
			$employeeModel = new EmployeeTrainings();
			$form->bind($employeeModel);
			
			$message = NULL;
			$fundingSource = $this->employeeService->listSelectData('funding_category','funding_type');
			$countryList = $this->employeeService->listSelectData('country','country');
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				$data = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                 ); 
                $form->setData($data); 
				 if ($form->isValid()) {
					 try {
						 $this->employeeService->saveEmployeeTraining($employeeModel);
						 $this->flashMessenger()->addMessage('Training was successfully edited');
						 $this->auditTrailService->saveAuditTrail("INSERT", "Employee Previous Training", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('editemployeetrainingdetail', array('id'=>$this->my_encrypt($id, $this->keyphrase)));
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'id' => $id,
				'form' => $form,
				'employeeTrainingDetail' => $employeeTrainingDetail,
				'fundingSource' => $fundingSource,
				'countryList' => $countryList,
				'keyphrase' => $this->keyphrase,
				'message' => $message
				));
		}
		else {
			return $this->redirect()->toRoute('emptrainingdetail');
		}
    }


    public function deleteEmployeeTrainingDetailAction()
    {
    	$this->loginDetails();
		 
		 //get the id of the travel authorization proposal
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			//Get the id of the employee details for redirection
			$employee_details_id = $this->employeeService->getEmployeeDetailsId('emp_previous_trainings', $id);
			 try {
				 $result = $this->employeeService->deleteEmployeeTrainingDetail($id);
				 $this->auditTrailService->saveAuditTrail("DELETE", "Employee Training Details", "ALL", "SUCCESS");
				 $this->flashMessenger()->addMessage("You have successfully deleted training details");
				 $encrypted_id = $this->my_encrypt($employee_details_id, $this->keyphrase);
				 return $this->redirect()->toRoute('viewemptrainingdetail', array('id' => $encrypted_id));
				 //return $this->redirect()->toRoute('emptraveldetails');
			 }
			 catch(\Exception $e) {
					 die($e->getMessage());
					 // Some DB Error happened, log it and let the user know
			 }
	
		} else {
			return $this->redirect()->toRoute('emptrainingdetail');
		}
    }


    public function downloadEmpTrainingEvidenceFileAction()
    {
    	$this->loginDetails();
        //get the student id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $file = $this->employeeService->getEvidenceFileName($id, 'emp_previous_trainings');
        
            $mimetype = mime_content_type($file);
            $response = new Stream();
            $response->setStream(fopen($file, 'r'));
            $response->setStatusCode(200);
            $response->setStreamName(basename($file));
            $headers = new Headers();
            $headers->addHeaderLine('Content-Disposition:inline','attachment; filename="' . basename($file) .'"')
                	->addHeaderLine('Content-Type', $mimetype)
                	->addHeaderLine('Content-Length',filesize($file))
                	->addHeaderLine('Expires','@0') // @0, because zf2 parses date as string to \DateTime() object
                	->addHeaderLine('Cache-Control','must-revalidate')
                	->addHeaderLine('Pragma','public')
                	->addHeaderLine('Content-Transfer-Encoding:binary')
                	->addHeaderLine('Accept-Ranges:bytes');
          
            $response->setHeaders($headers);
            return $response;
        }
        else
        {
            $this->redirect()->toRoute('emptrainingdetail');
        }
    }

	
	public function empPublicationAction()
    {
		$this->loginDetails();

		$role = $this->userrole; 
		$self_id = $this->employee_details_id;
		
       $form = new SearchForm();
	   $empAwards = $this->employeeService->getExtraCurricularDetails($tableName = 'emp_awards', $this->organisation_id, $self_id);
	   $employeeList = $this->employeeService->listAllEmployees($this->organisation_id, $self_id);
	   
	   $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$empName = $this->getRequest()->getPost('employee_name');
				$empId = $this->getRequest()->getPost('emp_id');
				$department = $this->getRequest()->getPost('department');
				$employeeList = $this->employeeService->getEmployeeList($empName, $empId, $department, $this->organisation_id, $self_id, $role);
             }
         }
		 
		
		return new ViewModel(array(
            'form' => $form,
			'keyphrase' => $this->keyphrase,
			'empAwards' => $empAwards,
			'employeeList' => $employeeList
            ));
    }
	
	public function addEmployeePublicationAction()
    {
		$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new EmployeePublicationsForm();
			$employeeModel = new EmployeePublications();
			$form->bind($employeeModel);
			
			$researchType = $this->employeeService->listSelectData($tableName='research_category', $columnName='research_category');
			
			$message = NULL;
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if(!$id)
					$id = $this->getRequest->getPost('employee_details_id');
				$data = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                 ); 
                $form->setData($data); 
				 if ($form->isValid()) {
					 try {
						 $this->employeeService->saveEmployeePublication($employeeModel);
						 $this->flashMessenger()->addMessage('Research Publication was successfully added');
						 $this->auditTrailService->saveAuditTrail("INSERT", "Add Employee Research Publication", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('addemppublication', array('id'=>$this->my_encrypt($id, $this->keyphrase)));
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'employees' => $this->employeeService->findEmployee($id, NULL),
				'id' => $id,
				'researchType' => $researchType,
				'form' => $form,
				'keyphrase' => $this->keyphrase,
				'employeeDetails' => $this->employeeService->findEmployeeExtraDetails($tableName='emp_previous_research', $id),
				'message' => $message
				));
		}
		else {
			return $this->redirect()->toRoute('emppublication');
		}
    }
	
	public function viewEmployeePublicationAction()
    {
		$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new EmployeePublicationsForm();

			$message = NULL;
			
			return new ViewModel(array(
				'employees' => $this->employeeService->findEmployee($id, NULL),
				'id' => $id,
				'form' => $form,
				'keyphrase' => $this->keyphrase,
				'message' => $message,
				'employeeDetails' => $this->employeeService->findEmployeeExtraDetails($tableName='emp_previous_research', $id)
				));
		}
		else {
			return $this->redirect()->toRoute('emppublication');
		}
    }


    public function editEmployeePublicationAction()
    {
    	$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$employeeResearchDetail = $this->employeeService->getEmployeeExtraDetail($id, $tableName = 'emp_previous_research', NULL);
			$employee_details_id = $this->employeeService->getEmployeeDetailsId('emp_previous_research', $id);
			$form = new EmployeePublicationsForm();
			$employeeModel = new EmployeePublications();
			$form->bind($employeeModel);
			
			$researchType = $this->employeeService->listSelectData($tableName='research_category', $columnName='research_category');
			
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
					 try {
						 $this->employeeService->saveEmployeePublication($employeeModel);
						 $this->flashMessenger()->addMessage('Research Publication was successfully edited');
						 $this->auditTrailService->saveAuditTrail("UPDATE", "Employee Previous Research", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('editemployeepublication', array('id'=>$this->my_encrypt($id, $this->keyphrase)));
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'id' => $id,
				'employeeResearchDetail' => $employeeResearchDetail,
				'researchType' => $researchType,
				'form' => $form,
				'keyphrase' => $this->keyphrase,
				'message' => $message
				));
		}
		else {
			return $this->redirect()->toRoute('emppublication');
		}
    }


    public function deleteEmployeePublicationAction()
    {
    	$this->loginDetails();
		 
		 //get the id of the travel authorization proposal
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			//Get the id of the employee details for redirection
			$employee_details_id = $this->employeeService->getEmployeeDetailsId('emp_previous_research', $id);
			 try {
				 $result = $this->employeeService->deleteEmployeePublication($id);
				 $this->auditTrailService->saveAuditTrail("DELETE", "Employee Publication Details", "ALL", "SUCCESS");
				 $this->flashMessenger()->addMessage("You have successfully deleted publication details");
				 $encrypted_id = $this->my_encrypt($employee_details_id, $this->keyphrase);
				 return $this->redirect()->toRoute('viewemppublication', array('id' => $encrypted_id));
				 //return $this->redirect()->toRoute('emptraveldetails');
			 }
			 catch(\Exception $e) {
					 die($e->getMessage());
					 // Some DB Error happened, log it and let the user know
			 }
	
		} else {
			return $this->redirect()->toRoute('emppublication');
		}
    }


    public function downloadEmpPublicationEvidenceFileAction()
    {
    	$this->loginDetails();
        //get the student id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $file = $this->employeeService->getEvidenceFileName($id, 'emp_previous_research');
        
            $mimetype = mime_content_type($file);
            $response = new Stream();
            $response->setStream(fopen($file, 'r'));
            $response->setStatusCode(200);
            $response->setStreamName(basename($file));
            $headers = new Headers();
            $headers->addHeaderLine('Content-Disposition:inline','attachment; filename="' . basename($file) .'"')
                	->addHeaderLine('Content-Type', $mimetype)
                	->addHeaderLine('Content-Length',filesize($file))
                	->addHeaderLine('Expires','@0') // @0, because zf2 parses date as string to \DateTime() object
                	->addHeaderLine('Cache-Control','must-revalidate')
                	->addHeaderLine('Pragma','public')
                	->addHeaderLine('Content-Transfer-Encoding:binary')
                	->addHeaderLine('Accept-Ranges:bytes');
           
            $response->setHeaders($headers);
            return $response;
        }
        else
        {
            $this->redirect()->toRoute('emppublication');
        }
    }
	
    public function empAwardAction()
    {
		$this->loginDetails();
		$empAwards = NULL;

		$role = $this->userrole; 
		$self_id = $this->employee_details_id;
		
       $form = new SearchForm();
	   $empAwards = $this->employeeService->getExtraCurricularDetails($tableName = 'emp_awards', $this->organisation_id, $self_id);
	   $employeeList = $this->employeeService->listAllEmployees($this->organisation_id, $self_id);
	   
	   $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$empName = $this->getRequest()->getPost('employee_name');
				$empId = $this->getRequest()->getPost('emp_id');
				$department = $this->getRequest()->getPost('department');
				$employeeList = $this->employeeService->getEmployeeList($empName, $empId, $department, $this->organisation_id, $self_id, $role);
             }
         }
		 //else {
			 //$employeeList = array();
		 //}
		
		return new ViewModel(array(
            'form' => $form,
			'keyphrase' => $this->keyphrase,
			'empAwards' => $empAwards,
			'employeeList' => $employeeList
            ));
    }
	
	public function addEmployeeAwardAction()
    {
		$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new EmployeeAwardForm();
			$employeeModel = new EmployeeAward();
			$form->bind($employeeModel);

			$award_category = $this->employeeService->listSelectCategoryData($tableName = 'emp_award_category', $columnName = 'award_category', $this->organisation_id);
			
			$message = NULL;
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if(!$id)
					$id = $this->getRequest->getPost('employee_details_id');
					$data = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                 ); 
                $form->setData($data); 
				 if ($form->isValid()) {
					 try {
						 $this->employeeService->saveEmployeeAward($employeeModel);
						 $this->flashMessenger()->addMessage('Award was successfully added');
						 $this->auditTrailService->saveAuditTrail("INSERT", "Employee Awards", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('addempaward', array('id'=>$this->my_encrypt($id, $this->keyphrase)));
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'employees' => $this->employeeService->findEmployee($id, NULL),
				'id' => $id,
				'form' => $form,
				'keyphrase' => $this->keyphrase,
				'employeeDetails' => $this->employeeService->findEmployeeExtraDetails($tableName='emp_awards', $id),
				'award_category' => $award_category,
				'message' => $message
				));
		}
		else {
			return $this->redirect()->toRoute('empaward');
		}
    }
	
	public function viewEmployeeAwardAction()
    {
		$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new EmployeeAwardForm();

			$message = NULL;
			
			return new ViewModel(array(
				'employees' => $this->employeeService->findEmployee($id, NULL),
				'id' => $id,
				'form' => $form,
				'keyphrase' => $this->keyphrase,
				'message' => $message,
				'employeeDetails' => $this->employeeService->findEmployeeExtraDetails($tableName='emp_awards', $id)
				));
		}
		else {
			return $this->redirect()->toRoute('empaward');
		}
    }


    public function editEmployeeAwardAction()
    {
    	$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$employeeAwardDetail = $this->employeeService->getEmployeeExtraDetail($id, $tableName = 'emp_awards', NULL);
			$employee_details_id = $this->employeeService->getEmployeeDetailsId('emp_awards', $id);
			$form = new EmployeeAwardForm();
			$employeeModel = new EmployeeAward();
			$form->bind($employeeModel);

			$award_category = $this->employeeService->listSelectCategoryData($tableName = 'emp_award_category', $columnName = 'award_category', $this->organisation_id);
			
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
					 try {
						 $this->employeeService->saveEmployeeAward($employeeModel);
						 $this->flashMessenger()->addMessage('Award was successfully edited');
						 $this->auditTrailService->saveAuditTrail("UPDATE", "Employee Award", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('editempaward', array('id'=>$this->my_encrypt($id, $this->keyphrase)));
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'id' => $id,
				'form' => $form,
				'employeeAwardDetail' => $employeeAwardDetail,
				'keyphrase' => $this->keyphrase,
				'award_category' => $award_category,
				'message' => $message
				));
		}
		else {
			return $this->redirect()->toRoute('empaward');
		}
    }


    public function deleteEmployeeAwardAction()
    {
    	$this->loginDetails();
		 
		 //get the id of the travel authorization proposal
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			//Get the id of the employee details for redirection
			$employee_details_id = $this->employeeService->getEmployeeDetailsId('emp_awards', $id);
			 try {
				 $result = $this->employeeService->deleteEmployeeAward($id);
				 $this->auditTrailService->saveAuditTrail("DELETE", "Employee Awards Details", "ALL", "SUCCESS");
				 $this->flashMessenger()->addMessage("You have successfully deleted award details");
				 $encrypted_id = $this->my_encrypt($employee_details_id, $this->keyphrase);
				 return $this->redirect()->toRoute('viewempaward', array('id' => $encrypted_id));
				 //return $this->redirect()->toRoute('emptraveldetails');
			 }
			 catch(\Exception $e) {
					 die($e->getMessage());
					 // Some DB Error happened, log it and let the user know
			 }
	
		} else {
			return $this->redirect()->toRoute('empaward');
		}
    }


    public function downloadEmpAwardEvidenceFileAction()
    {
    	$this->loginDetails();
        //get the student id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $file = $this->employeeService->getEvidenceFileName($id, 'emp_awards');
        
            $mimetype = mime_content_type($file);
            $response = new Stream();
            $response->setStream(fopen($file, 'r'));
            $response->setStatusCode(200);
            $response->setStreamName(basename($file));
            $headers = new Headers();
            $headers->addHeaderLine('Content-Disposition:inline','attachment; filename="' . basename($file) .'"')
                	->addHeaderLine('Content-Type', $mimetype)
                	->addHeaderLine('Content-Length',filesize($file))
                	->addHeaderLine('Expires','@0') // @0, because zf2 parses date as string to \DateTime() object
                	->addHeaderLine('Cache-Control','must-revalidate')
                	->addHeaderLine('Pragma','public')
                	->addHeaderLine('Content-Transfer-Encoding:binary')
                	->addHeaderLine('Accept-Ranges:bytes');

            $response->setHeaders($headers);
            return $response;
        }
        else
        {
            $this->redirect()->toRoute('empaward');
        }
    }
    
    public function empContributionAction()
    {
		$this->loginDetails();

		$role = $this->userrole; 
		$self_id = $this->employee_details_id;
		
       $form = new SearchForm();
	   $empContribution = $this->employeeService->getExtraCurricularDetails($tableName = 'emp_contributions', $this->organisation_id, $self_id);
	   $employeeList = $this->employeeService->listAllEmployees($this->organisation_id, $self_id);
	   
	   $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$empName = $this->getRequest()->getPost('employee_name');
				$empId = $this->getRequest()->getPost('emp_id');
				$department = $this->getRequest()->getPost('department');
				$employeeList = $this->employeeService->getEmployeeList($empName, $empId, $department, $this->organisation_id, $self_id, $role);
             }
         }
		 //else {
			 //$employeeList = array();
		 //}
		
		return new ViewModel(array(
            'form' => $form,
			'keyphrase' => $this->keyphrase,
			'empContribution' => $empContribution,
			'employeeList' => $employeeList
            ));
    }
	
	public function addEmployeeContributionAction()
    {
		$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new EmployeeContributionForm();
			$employeeModel = new EmployeeContribution();
			$form->bind($employeeModel);
			
			$message = NULL;

			$contribution_category = $this->employeeService->listSelectCategoryData($tableName = 'emp_contribution_category', $columnName = 'contribution_category', $this->organisation_id);
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if(!$id)
					$id = $this->getRequest->getPost('employee_details_id');
				$data = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                 ); 
                $form->setData($data); 
				 if ($form->isValid()) {
					 try {
						 $this->employeeService->saveEmployeeContribution($employeeModel);
						 $this->flashMessenger()->addMessage('Contribution was successfully added');
						 $this->auditTrailService->saveAuditTrail("INSERT", "Add Employee Contribution", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('addempcontribution', array('id'=>$this->my_encrypt($id, $this->keyphrase)));
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'employees' => $this->employeeService->findEmployee($id, NULL),
				'id' => $id,
				'form' => $form,
				'keyphrase' => $this->keyphrase,
				'employeeDetails' => $this->employeeService->findEmployeeExtraDetails($tableName='emp_contributions', $id),
				'contribution_category' => $contribution_category,
				'message' => $message
				));
		}
		else {
			return $this->redirect()->toRoute('empcontribution');
		}
    }
	
	public function viewEmployeeContributionAction()
    {
		$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new EmployeeContributionForm();

			$message = NULL;
			
			return new ViewModel(array(
				'employees' => $this->employeeService->findEmployee($id, NULL),
				'id' => $id,
				'form' => $form,
				'keyphrase' => $this->keyphrase,
				'message' => $message,
				'employeeDetails' => $this->employeeService->findEmployeeExtraDetails($tableName='emp_contributions', $id)
				));
		}
		else {
			return $this->redirect()->toRoute('empcontribution');
		}
    }


    public function editEmployeeContributionAction()
    {
    	$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$employeeContributionDetail = $this->employeeService->getEmployeeExtraDetail($id, $tableName = 'emp_contributions', NULL);
			$employee_details_id = $this->employeeService->getEmployeeDetailsId('emp_contributions', $id);
			$form = new EmployeeContributionForm();
			$employeeModel = new EmployeeContribution();
			$form->bind($employeeModel);
			
			$message = NULL;

			$contribution_category = $this->employeeService->listSelectCategoryData($tableName = 'emp_contribution_category', $columnName = 'contribution_category', $this->organisation_id);
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				$data = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                 ); 
                $form->setData($data); 
				 if ($form->isValid()) {
					 try {
						 $this->employeeService->saveEmployeeContribution($employeeModel);
						 $this->flashMessenger()->addMessage('Contribution was successfully edited');
						 $this->auditTrailService->saveAuditTrail("UPDATE", "Employee Contribution", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('editemployeecontribution', array('id'=>$this->my_encrypt($id, $this->keyphrase)));
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'id' => $id,
				'form' => $form,
				'employeeContributionDetail' => $employeeContributionDetail,
				'keyphrase' => $this->keyphrase,
				'contribution_category' => $contribution_category,
				'message' => $message
				));
		}
		else {
			return $this->redirect()->toRoute('empcontribution');
		}
    }


    public function deleteEmployeeContributionAction()
    {
    	$this->loginDetails();
		 
		 //get the id of the travel authorization proposal
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			//Get the id of the employee details for redirection
			$employee_details_id = $this->employeeService->getEmployeeDetailsId('emp_contributions', $id);
			 try {
				 $result = $this->employeeService->deleteEmployeeContribution($id);
				 $this->auditTrailService->saveAuditTrail("DELETE", "Employee Contribution Details", "ALL", "SUCCESS");
				 $this->flashMessenger()->addMessage("You have successfully deleted contribution details");
				 $encrypted_id = $this->my_encrypt($employee_details_id, $this->keyphrase);
				 return $this->redirect()->toRoute('viewempcontribution', array('id' => $encrypted_id));
				 //return $this->redirect()->toRoute('emptraveldetails');
			 }
			 catch(\Exception $e) {
					 die($e->getMessage());
					 // Some DB Error happened, log it and let the user know
			 }
	
		} else {
			return $this->redirect()->toRoute('empcontribution');
		}
    }


    public function downloadEmpContributionFileAction()
    {
    	$this->loginDetails();
        //get the student id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $file = $this->employeeService->getEvidenceFileName($id, 'emp_contributions');
        
            $mimetype = mime_content_type($file);
            $response = new Stream();
            $response->setStream(fopen($file, 'r'));
            $response->setStatusCode(200);
            $response->setStreamName(basename($file));
            $headers = new Headers();
            $headers->addHeaderLine('Content-Disposition:inline','attachment; filename="' . basename($file) .'"')
                	->addHeaderLine('Content-Type', $mimetype)
                	->addHeaderLine('Content-Length',filesize($file))
                	->addHeaderLine('Expires','@0') // @0, because zf2 parses date as string to \DateTime() object
                	->addHeaderLine('Cache-Control','must-revalidate')
                	->addHeaderLine('Pragma','public')
                	->addHeaderLine('Content-Transfer-Encoding:binary')
                	->addHeaderLine('Accept-Ranges:bytes');
            
            $response->setHeaders($headers);
            return $response;
        }
        else
        {
            $this->redirect()->toRoute('empcontribution');
        }
    }
    
    public function empResponsibilityAction()
    {
		$this->loginDetails();

		$role = $this->userrole; 
		$self_id = $this->employee_details_id;
		
       $form = new SearchForm();
	   $empResponsibilities = $this->employeeService->getExtraCurricularDetails($tableName = 'emp_responsibilities', $this->organisation_id, $self_id);
	   $employeeList = $this->employeeService->listAllEmployees($this->organisation_id, $self_id);
	   
	   $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$empName = $this->getRequest()->getPost('employee_name');
				$empId = $this->getRequest()->getPost('emp_id');
				$department = $this->getRequest()->getPost('department');
				$employeeList = $this->employeeService->getEmployeeList($empName, $empId, $department, $this->organisation_id, $self_id, $role);
             }
         }
		 //else {
			 //$employeeList = array();
		 //}
		
		return new ViewModel(array(
            'form' => $form,
			'keyphrase' => $this->keyphrase,
			'empResponsibilities' => $empResponsibilities,
			'employeeList' => $employeeList
            ));
    }
	
	public function addEmployeeResponsibilityAction()
    {
		$this->loginDetails();
		
        //get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new EmployeeResponsibilityForm();
			$employeeModel = new EmployeeResponsibilities();
			$form->bind($employeeModel);
			
			$message = NULL;

			$responsibility_category = $this->employeeService->listSelectCategoryData($tableName = 'emp_responsibility_category', $columnName = 'responsibility_category', $this->organisation_id);
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if(!$id)
					$id = $this->getRequest->getPost('employee_details_id');
				$data = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                 ); 
                $form->setData($data); 
				 if ($form->isValid()) {
					 try {
						 $this->employeeService->saveEmployeeResponsibility($employeeModel);
						 $this->flashMessenger()->addMessage('Responsibility was successfully added');
						 $this->auditTrailService->saveAuditTrail("INSERT", "Add Employee Responsibility", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('addempresponsibility', array('id'=>$this->my_encrypt($id, $this->keyphrase)));
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'employees' => $this->employeeService->findEmployee($id, NULL),
				'id' => $id,
				'form' => $form,
				'keyphrase' => $this->keyphrase,
				'employeeDetails' => $this->employeeService->findEmployeeExtraDetails($tableName='emp_responsibilities', $id),
				'responsibility_category' => $responsibility_category,
				'message' => $message
				));
		}
		else {
			return $this->redirect()->toRoute('empresponsibility');
		}
    }
	
	public function viewEmployeeResponsibilityAction()
    {
		$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);

		$message = NULL;
		
		if(is_numeric($id)){
			$form = new EmployeeResponsibilityForm();
			
			return new ViewModel(array(
				'employees' => $this->employeeService->findEmployee($id, NULL),
				'id' => $id,
				'form' => $form,
				'keyphrase' => $this->keyphrase,
				'message' => $message,
				'employeeDetails' => $this->employeeService->findEmployeeExtraDetails($tableName='emp_responsibilities', $id)
				));
		}
		else {
			return $this->redirect()->toRoute('empresponsibility');
		}
    }


    public function editEmployeeResponsibilityAction()
    {
    	$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$employeeResponsibilityDetail = $this->employeeService->getEmployeeExtraDetail($id, $tableName = 'emp_responsibilities', NULL);
			$employee_details_id = $this->employeeService->getEmployeeDetailsId('emp_responsibilities', $id);
			$form = new EmployeeResponsibilityForm();
			$employeeModel = new EmployeeResponsibilities();
			$form->bind($employeeModel);
			
			$message = NULL;

			$responsibility_category = $this->employeeService->listSelectCategoryData($tableName = 'emp_responsibility_category', $columnName = 'responsibility_category', $this->organisation_id);
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				$data = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                 ); 
                $form->setData($data); 
				 if ($form->isValid()) {
					 try {
						 $this->employeeService->saveEmployeeResponsibility($employeeModel);
						 $this->flashMessenger()->addMessage('Responsibility was successfully edited');
						 $this->auditTrailService->saveAuditTrail("UPDATE", "Edit Employee Responsibility", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('editemployeeresponsibility', array('id'=>$this->my_encrypt($id, $this->keyphrase)));
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'id' => $id,
				'form' => $form,
				'keyphrase' => $this->keyphrase,
				'responsibility_category' => $responsibility_category,
				'employeeResponsibilityDetail' => $employeeResponsibilityDetail,
				'message' => $message
				));
		}
		else {
			return $this->redirect()->toRoute('empresponsibility');
		}
    }


    public function deleteEmployeeResponsibilityAction()
    {
    	$this->loginDetails();
		 
		 //get the id of the travel authorization proposal
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			//Get the id of the employee details for redirection
			$employee_details_id = $this->employeeService->getEmployeeDetailsId('emp_responsibilities', $id);
			 try {
				 $result = $this->employeeService->deleteEmployeeResponsibility($id);
				 $this->auditTrailService->saveAuditTrail("DELETE", "Employee Responsibility Details", "ALL", "SUCCESS");
				 $this->flashMessenger()->addMessage("You have successfully deleted responsibility details");
				 $encrypted_id = $this->my_encrypt($employee_details_id, $this->keyphrase);
				 return $this->redirect()->toRoute('viewempresponsibility', array('id' => $encrypted_id));
				 //return $this->redirect()->toRoute('emptraveldetails');
			 }
			 catch(\Exception $e) {
					 die($e->getMessage());
					 // Some DB Error happened, log it and let the user know
			 }
	
		} else {
			return $this->redirect()->toRoute('empresponsibility');
		}
    }


    public function downloadEmpResponsibilityFileAction()
    {
    	$this->loginDetails();
        //get the student id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $file = $this->employeeService->getEvidenceFileName($id, 'emp_responsibilities');
        
            $mimetype = mime_content_type($file);
            $response = new Stream();
            $response->setStream(fopen($file, 'r'));
            $response->setStatusCode(200);
            $response->setStreamName(basename($file));
            $headers = new Headers();
            $headers->addHeaderLine('Content-Disposition:inline','attachment; filename="' . basename($file) .'"')
                	->addHeaderLine('Content-Type', $mimetype)
                	->addHeaderLine('Content-Length',filesize($file))
                	->addHeaderLine('Expires','@0') // @0, because zf2 parses date as string to \DateTime() object
                	->addHeaderLine('Cache-Control','must-revalidate')
                	->addHeaderLine('Pragma','public')
                	->addHeaderLine('Content-Transfer-Encoding:binary')
                	->addHeaderLine('Accept-Ranges:bytes');
    
            $response->setHeaders($headers);
            return $response;
        }
        else
        {
            $this->redirect()->toRoute('empresponsibility');
        }
    }
    
    public function empCommunityServiceAction()
    {
		$this->loginDetails();

		$role = $this->userrole; 
		$self_id = $this->employee_details_id;

		
       $form = new SearchForm();
	   $empCommunityService = $this->employeeService->getExtraCurricularDetails($tableName = 'emp_community_services', $this->organisation_id, $self_id);
	   $employeeList = $this->employeeService->listAllEmployees($this->organisation_id, $self_id);

	   $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$empName = $this->getRequest()->getPost('employee_name');
				$empId = $this->getRequest()->getPost('emp_id');
				$department = $this->getRequest()->getPost('department');
				$employeeList = $this->employeeService->getEmployeeList($empName, $empId, $department, $this->organisation_id, $self_id, $role);
             }
         }
		 //else {
			 //$employeeList = array();
		 //}
		
		return new ViewModel(array(
            'form' => $form,
			'keyphrase' => $this->keyphrase,
			'empCommunityService' => $empCommunityService,
			'employeeList' => $employeeList
            ));
    }
	
	public function addEmployeeCommunityServiceAction()
    {
		$this->loginDetails();
		
       //get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new EmployeeCommunityServiceForm();
			$employeeModel = new EmployeeCommunityService();
			$form->bind($employeeModel);
			
			$message = NULL;

			$community_service_category = $this->employeeService->listSelectCategoryData($tableName = 'emp_community_service_category', $columnName = 'community_service_category', $this->organisation_id);
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if(!$id)
					$id = $this->getRequest->getPost('employee_details_id');
				$data = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                 ); 
                $form->setData($data); 
				 if ($form->isValid()) {
					 try {
						 $this->employeeService->saveEmployeeCommunityService($employeeModel);
						 $this->flashMessenger()->addMessage('Community Service was successfully added');
						 $this->auditTrailService->saveAuditTrail("INSERT", "Add Employee Community Service", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('addempcommunityservice', array('id'=>$this->my_encrypt($id, $this->keyphrase)));
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'employees' => $this->employeeService->findEmployee($id, NULL),
				'id' => $id,
				'form' => $form,
				'keyphrase' => $this->keyphrase,
				'employeeDetails' => $this->employeeService->findEmployeeExtraDetails($tableName='emp_community_services', $id),
				'community_service_category' => $community_service_category,
				'message' => $message
				));
		}
		else {
			return $this->redirect()->toRoute('empcommunityservice');
		}
    }
	
	public function viewEmployeeCommunityServiceAction()
    {
		$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new EmployeeCommunityServiceForm();

			$message = NULL;
			
			return new ViewModel(array(
				'employees' => $this->employeeService->findEmployee($id, NULL),
				'id' => $id,
				'form' => $form,
				'keyphrase' => $this->keyphrase,
				'message' => $message,
				'employeeDetails' => $this->employeeService->findEmployeeExtraDetails($tableName='emp_community_services', $id)
				));
		}
		else {
			return $this->redirect()->toRoute('empcommunityservice');
		}
    }


    public function editEmployeeCommunityServiceAction()
    {
    	$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$employeeCommunityService = $this->employeeService->getEmployeeExtraDetail($id, $tableName = 'emp_community_services', NULL);
			$employee_details_id = $this->employeeService->getEmployeeDetailsId('emp_community_services', $id);
			$form = new EmployeeCommunityServiceForm();
			$employeeModel = new EmployeeCommunityService();
			$form->bind($employeeModel);
			
			$message = NULL;

			$community_service_category = $this->employeeService->listSelectCategoryData($tableName = 'emp_community_service_category', $columnName = 'community_service_category', $this->organisation_id);
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				$data = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                 ); 
                $form->setData($data); 
				 if ($form->isValid()) {
					 try {
						 $this->employeeService->saveEmployeeCommunityService($employeeModel);
						 $this->flashMessenger()->addMessage('Community Service was successfully edited');
						 $this->auditTrailService->saveAuditTrail("UPDATE", "Employee Community Service", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('editempcommunityservice', array('id'=>$this->my_encrypt($id, $this->keyphrase)));
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'id' => $id,
				'form' => $form,
				'employeeCommunityService' => $employeeCommunityService,
				'keyphrase' => $this->keyphrase,
				'community_service_category' => $community_service_category,
				'message' => $message
				));
		}
		else {
			return $this->redirect()->toRoute('empcommunityservice');
		}
    }


    public function deleteEmployeeCommunityServiceAction()
    {
    	$this->loginDetails();
		 
		 //get the id of the travel authorization proposal
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			//Get the id of the employee details for redirection
			$employee_details_id = $this->employeeService->getEmployeeDetailsId('emp_community_services', $id);
			 try {
				 $result = $this->employeeService->deleteEmployeeCommunityService($id);
				 $this->auditTrailService->saveAuditTrail("DELETE", "Employee Community Service", "ALL", "SUCCESS");
				 $this->flashMessenger()->addMessage("You have successfully deleted employee community service");
				 $encrypted_id = $this->my_encrypt($employee_details_id, $this->keyphrase);
				 return $this->redirect()->toRoute('viewempcommunityservice', array('id' => $encrypted_id));
				 //return $this->redirect()->toRoute('emptraveldetails');
			 }
			 catch(\Exception $e) {
					 die($e->getMessage());
					 // Some DB Error happened, log it and let the user know
			 }
	
		} else {
			return $this->redirect()->toRoute('empcommunityservice');
		}
    }


    // Function to search for staff to change the department
    public function empDepartmentSearchAction()
	{
		$this->loginDetails();
		
		$form = new SearchForm();

		$message = NULL;
		
		$request = $this->getRequest();
        if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$empName = $this->getRequest()->getPost('employee_name');
				$empId = $this->getRequest()->getPost('emp_id');
				$department = $this->getRequest()->getPost('department');
				$employeeList = $this->employeeService->getDepartmentEmployeeList($empName, $empId, $department, $this->organisation_id);
             }
        }
		else {
			$employeeList = array();
		}
		
		return new ViewModel(array(
            'form' => $form,
			'keyphrase' => $this->keyphrase,
			'employeeList' => $employeeList,
			'message' => $message,
          ));
	}


	public function updateEmployeeDepartmentAction()
	{
		$this->loginDetails();
		
		//get the promotion id. It will be used to get the employee_details_id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		$employee_id = $id;
		
		if(is_numeric($id)){ 
			//$dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
			$form = new EmployeeDepartmentForm($this->serviceLocator);
			
			$personalDetails = $this->employeeService->getPersonalDetails($employee_id, 'Department');
			$employmentDetails = $this->employeeService->getEmploymentDetails($employee_id);
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 $data = array_merge_recursive(
						$request->getPost()->toArray(),
						$request->getFiles()->toArray()
				 ); 
				 $form->setData($data); 
				 if ($form->isValid()) { 
					$data = $form->getData();
					 try {
							 $this->employeeService->updateEmployeeDepartment($data);
							 $this->flashMessenger()->addMessage('Staff Department was successfully updated');
							 $this->auditTrailService->saveAuditTrail("UPDATE", "Employee Details", "ALL", "SUCCESS");
							 return $this->redirect()->toRoute('empdepartmentsearch');
					 }
					 catch(\Exception $e) {
									 die($e->getMessage());
									 // Some DB Error happened, log it and let the user know
					 }
				 }
	
			 }
					 
			return array(
				'form' => $form,
				'employee_details_id' => $employee_id,
				'personalDetails' => $personalDetails,
				'employmentDetails' => $employmentDetails,
				'organisation_id' => $this->organisation_id,
				);
		}
		else {
			return $this->redirect()->toRoute('empdepartmentsearch');
		}
	}


	// Function to edit the department and section of staff
	public function editStaffDepartmentAction()
	{
		$this->loginDetails();
		
		$role = $this->userrole; 
		$self_id = $this->employee_details_id;
		
		$form = new SearchForm();

		$message = NULL;

		$employeeList = $this->employeeService->listAllEmployees($this->organisation_id, $self_id);
		
		$request = $this->getRequest();
        if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$empName = $this->getRequest()->getPost('employee_name');
				$empId = $this->getRequest()->getPost('emp_id');
				$department = $this->getRequest()->getPost('department');
				$employeeList = $this->employeeService->getDepartmentEmployeeList($empName, $empId, $department, $this->organisation_id);
             }
        }
		//else {
			//$employeeList = array();
		//}
		
		return new ViewModel(array(
            'form' => $form,
			'keyphrase' => $this->keyphrase,
			'employeeList' => $employeeList,
			'message' => $message,
          ));
	}


	public function updateEditedStaffDepartmentAction()
	{
		$this->loginDetails();
		
		//get the promotion id. It will be used to get the employee_details_id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		$employee_id = $id;
		
		if(is_numeric($id)){ 
			//$dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
			$form = new UpdateEmpDepartmentForm($this->serviceLocator);
			
			$personalDetails = $this->employeeService->getPersonalDetails($employee_id, 'Department');
			$employmentDetails = $this->employeeService->getEmploymentDetails($employee_id);
			$departmentDetails = $this->employeeService->getDepartmentDetails($employee_id);

			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 $data = array_merge_recursive(
						$request->getPost()->toArray(),
						$request->getFiles()->toArray()
				 ); 
				 $form->setData($data); 
				 if ($form->isValid()) { 
					$data = $form->getData(); 
					 try {
							 $this->employeeService->updateEditEmpDepartment($data);
							 $this->flashMessenger()->addMessage('Staff Department was successfully edited');
							 $this->auditTrailService->saveAuditTrail("UPDATE", "Employee Details", "ALL", "SUCCESS");
							 return $this->redirect()->toRoute('editstaffdepartment');
					 }
					 catch(\Exception $e) {
									 die($e->getMessage());
									 // Some DB Error happened, log it and let the user know
					 }
				 }
	
			 }
					 
			return array(
				'form' => $form,
				'employee_details_id' => $employee_id,
				'personalDetails' => $personalDetails,
				'employmentDetails' => $employmentDetails,
				'departmentDetails' => $departmentDetails,
				'organisation_id' => $this->organisation_id,
				'departments_id' => $this->departments_id,
				'departments_units_id' => $this->departments_units_id,
				);
		}
		else {
			return $this->redirect()->toRoute('editstaffdepartment');
		}
	}



	// Function to edit the job title and position staff
	public function editStaffPositionTitleLevelAction()
	{
		$this->loginDetails();

		$role = $this->userrole; 
		$self_id = $this->employee_details_id;
		
		$form = new SearchForm();

		$message = NULL;

		$employeeList = $this->employeeService->listAllEmployees($this->organisation_id, $self_id);
		
		$request = $this->getRequest();
        if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$empName = $this->getRequest()->getPost('employee_name');
				$empId = $this->getRequest()->getPost('emp_id');
				$department = $this->getRequest()->getPost('department');
				$employeeList = $this->employeeService->getDepartmentEmployeeList($empName, $empId, $department, $this->organisation_id);
             }
        }
		//else {
			//$employeeList = array();
		//}
		
		return new ViewModel(array(
            'form' => $form,
			'keyphrase' => $this->keyphrase,
			'employeeList' => $employeeList,
			'message' => $message,
          ));
	}



	public function updateEditedPositionTitleLevelAction()
	{
		$this->loginDetails();
		
		//get the promotion id. It will be used to get the employee_details_id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		$employee_id = $id;
		
		if(is_numeric($id)){ 
			//$dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
			$form = new UpdateEmpPositionTitleLevelForm($this->serviceLocator);
			
			$personalDetails = $this->employeeService->getPersonalDetails($employee_id, 'Position');
			$employmentDetails = $this->employeeService->getEmploymentDetails($employee_id);
			$departmentDetails = $this->employeeService->getDepartmentDetails($employee_id);
			$positionDetails = $this->employeeService->getStaffPositionDetails($employee_id);

			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 $data = array_merge_recursive(
						$request->getPost()->toArray(),
						$request->getFiles()->toArray()
				 ); 
				 $form->setData($data); 
				 if ($form->isValid()) { 
					$data = $form->getData(); 
					 try {
							 $this->employeeService->updateEditedPositionTitleLevel($data);
							 $this->flashMessenger()->addMessage('Staff Position title and level was successfully edited');
							 $this->auditTrailService->saveAuditTrail("UPDATE", "Emp Position Title", "ALL", "SUCCESS");
							 $this->auditTrailService->saveAuditTrail("UPDATE", "Emp Position Level", "ALL", "SUCCESS");
							 return $this->redirect()->toRoute('editstaffpositiontitlelevel');
					 }
					 catch(\Exception $e) {
									 die($e->getMessage());
									 // Some DB Error happened, log it and let the user know
					 }
				 }
	
			 }
					 
			return array(
				'form' => $form,
				'employee_details_id' => $employee_id,
				'personalDetails' => $personalDetails,
				'employmentDetails' => $employmentDetails,
				'departmentDetails' => $departmentDetails,
				'positionDetails' => $positionDetails,
				);
		}
		else {
			return $this->redirect()->toRoute('editstaffpositiontitlelevel');
		}
	}



    public function downloadEmpCommunityServiceFileAction()
    {
    	$this->loginDetails();
        //get the student id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $file = $this->employeeService->getEvidenceFileName($id, 'emp_community_services');
        
            $mimetype = mime_content_type($file);
            $response = new Stream();
            $response->setStream(fopen($file, 'r'));
            $response->setStatusCode(200);
            $response->setStreamName(basename($file));
            $headers = new Headers();
            $headers->addHeaderLine('Content-Disposition:inline','attachment; filename="' . basename($file) .'"')
                	->addHeaderLine('Content-Type', $mimetype)
                	->addHeaderLine('Content-Length',filesize($file))
                	->addHeaderLine('Expires','@0') // @0, because zf2 parses date as string to \DateTime() object
                	->addHeaderLine('Cache-Control','must-revalidate')
                	->addHeaderLine('Pragma','public')
                	->addHeaderLine('Content-Transfer-Encoding:binary')
                	->addHeaderLine('Accept-Ranges:bytes');
    
            $response->setHeaders($headers);
            return $response;
        }
        else
        {
            $this->redirect()->toRoute('empcommunityservice');
        }
    }
    
    public function empDisciplineAction()
    {
		$this->loginDetails();

		$role = $this->userrole; 
		$self_id = $this->employee_details_id;
		
       $form = new SearchForm();
	   $empRecords = $this->employeeService->getExtraCurricularDetails($tableName = 'emp_disciplinary_record', $this->organisation_id, $self_id);
	   $employeeList = $this->employeeService->listAllEmployees($this->organisation_id, $self_id);

	   $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$empName = $this->getRequest()->getPost('employee_name');
				$empId = $this->getRequest()->getPost('emp_id');
				$department = $this->getRequest()->getPost('department');
				$employeeList = $this->employeeService->getEmployeeList($empName, $empId, $department, $this->organisation_id, $self_id, $role);
             }
         }
		 //else {
			 //$employeeList = array();
		 //}
		
		return new ViewModel(array(
            'form' => $form,
			'keyphrase' => $this->keyphrase,
			'empRecords' => $empRecords,
			'employeeList' => $employeeList
            ));
    }
	
	public function addEmployeeDisciplinaryRecordAction()
    {
		$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new EmployeeDisciplineForm();
			$employeeModel = new EmployeeDisciplineRecord();
			$form->bind($employeeModel);
			
			$message = NULL;
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 $data = array_merge_recursive(
					$request->getPost()->toArray(),
					$request->getFiles()->toArray()
				 ); 
				 $form->setData($data); 
				 if(!$id)
					$id = $this->getRequest->getPost('employee_details_id');
				 if ($form->isValid()) {
					 try {
						 $this->employeeService->saveEmployeeDiscipline($employeeModel);
						 $this->flashMessenger()->addMessage('Record was successfully added');
						 $this->auditTrailService->saveAuditTrail("INSERT", "Add Employee Disciplinary Record", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('addempdisciplinaryrecord', array('id'=>$this->my_encrypt($id, $this->keyphrase)));
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'employees' => $this->employeeService->findEmployee($id, NULL),
				'id' => $id,
				'form' => $form,
				'employeeDetails' => $this->employeeService->findEmployeeExtraDetails($tableName='emp_disciplinary_record', $id),
				'message' => $message
				));
		}
		else {
			return $this->redirect()->toRoute('empdiscipline');
		}
    }
    
    public function viewEmployeeDisciplinaryRecordAction()
    {
		$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new EmployeeCommunityServiceForm();
			
			return new ViewModel(array(
				'employees' => $this->employeeService->findEmployee($id, NULL),
				'id' => $id,
				'form' => $form,
				'employeeDetails' => $this->employeeService->findEmployeeExtraDetails($tableName='emp_community_services', $id)
				));
		}
		else {
			return $this->redirect()->toRoute('empdiscipline');
		}
    }


    public function employeeOnProbationAction()
    {

		$this->loginDetails();
		
       $form = new SearchForm();
	   $employeeList = $this->employeeService->listAllEmployeesOnProbation($this->organisation_id, NULL);
	   	   
	   $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$empName = $this->getRequest()->getPost('employee_name');
				$empId = $this->getRequest()->getPost('emp_id');
				$department = $this->getRequest()->getPost('department');
				$employeeList = $this->employeeService->getEmployeeOnProbationList($empName, $empId, $department, $this->organisation_id);
             }
         }
		
		return new ViewModel(array(
            'form' => $form,
			'keyphrase' => $this->keyphrase,
			'employeeList' => $employeeList
            ));
    }


    public function updateEmployeeOnProbationAction()
    {
    	$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new EmployeeOnProbationForm();
			$employeeModel = new EmployeeOnProbation();
			$form->bind($employeeModel);

			$empType = $this->employeeService->listSelectData($tableName = 'employee_type', $columnName = 'employee_type');
			
			$message = NULL;
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if(!$id)
					$id = $this->getRequest->getPost('employee_details_id');
					$data = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                 ); 
                $form->setData($data); 
				 if ($form->isValid()) {
					 try {
						 $this->employeeService->updateEmployeeOnProbation($employeeModel);
						 $this->flashMessenger()->addMessage('Employee on Probation was successfully added');
						 $this->auditTrailService->saveAuditTrail("UPDATE", "Employee Details", "Emp Type", "SUCCESS");
						 $this->auditTrailService->saveAuditTrail("INSERT", "Employment Record", "ALL", "SUCCESS");
						 $this->auditTrailService->saveAuditTrail("INSERT", "Employee Position Title", "ALL", "SUCCESS");
						 $this->auditTrailService->saveAuditTrail("INSERT", "Employee Position Level", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('employeeonprobation');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'employees' => $this->employeeService->findEmployee($id, NULL),
				'id' => $id,
				'form' => $form,
				'keyphrase' => $this->keyphrase,
				'employeeDetails' => $this->employeeService->findEmployeeExtraDetails($tableName='employee_details', $id),
				'empType' => $empType,
				'message' => $message
				));
		}
		else {
			return $this->redirect()->toRoute('employeeonprobation');
		}
    }


    public function employeePayDetailsAction()
    {
		$this->loginDetails();

		$role = $this->userrole; 
		$self_id = $this->employee_details_id;
		
       $form = new SearchForm();
	   $employeeList = $this->employeeService->listAllEmployees($this->organisation_id, NULL);
	   	   
	   $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$empName = $this->getRequest()->getPost('employee_name');
				$empId = $this->getRequest()->getPost('emp_id');
				$department = $this->getRequest()->getPost('department');
				$employeeList = $this->employeeService->getEmployeeList($empName, $empId, $department, $this->organisation_id, $self_id, $role);
             }
         }
		
		return new ViewModel(array(
            'form' => $form,
			'keyphrase' => $this->keyphrase,
			'employeeList' => $employeeList
            ));
    }


    public function editEmployeePayDetailsAction()
    {
    	$this->loginDetails();

		//get the officiating id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);

		if(is_numeric($id)){ 
			$form = new EmployeePayDetailsForm();
			$employeeModel = new EmployeePayDetails();
			$form->bind($employeeModel);

			$employee_pay_details = $this->employeeService->getEmployeePayDetails($id);
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
						 $this->employeeService->updateEmpPayDetails($employeeModel);
						 $this->flashMessenger()->addMessage('Staff Pay Details was successfully edited');
						 $this->auditTrailService->saveAuditTrail("EDIT", "Emp Pay Details", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('employeepaydetails');
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
				'employee_pay_details' => $employee_pay_details,
				);
		}else{
			return $this->redirect()->toRoute('employeepaydetails');
		}
    }

	
	//HR Reports
	public function hrmReportsAction()
	{
		$this->loginDetails();
		
		$form = new HrReportForm();
		$hr_report = NULL;
		$organisationList = $this->employeeService->listSelectData('organisation','organisation_name');
		$studyLevel = $this->employeeService->listSelectData('study_level','study_level');
		$report_type = array();
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 $report_type['report_type'] = $this->getRequest()->getPost('report_type');
				 $report_type['report_format'] = $this->getRequest()->getPost('report_format');
				 $report_type['organisation_id'] = $this->organisation_id;
				 try {
					 $hr_report = $this->employeeService->getHrReport($report_type);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
		return new ViewModel(array(
			'form' => $form,
			'hr_report' => $hr_report,
			'organisationList' => $organisationList,
			'studyLevel' => $studyLevel,
			'report_type' => $report_type
			));
	}
	
	//generate pdf of the reports
	public function generateHrReportsAction()
	{
		//get the param from the type of report
		$reporttype = $this->params()->fromRoute('reporttype',0);
		//exceeding memory, so report needs to be broken up
		$reports = $this->employeeService->getHrReport($reporttype);
		$contract = array();
		$regular = array();
		foreach($reports as $key=>$value){
			foreach($value as $k => $v){
				foreach($v as $k2=>$v2){
					if($k == 'Contract'){
						$contract[$key][$k2] = count($v2);
					}
					if($k == 'Regular'){
						$regular[$key][$k2] = count($v2);
					}
				}
			}
		}
        $pdf = new PdfModel();
        $pdf->setOption('fileName', 'RUB HR Reports'); // Triggers PDF download, automatically appends ".pdf"
        //$pdf->setOption('display', PdfModel::DISPLAY_ATTACHMENT);
        $pdf->setOption('paperSize', 'a4'); // Defaults to "8x11"
        $pdf->setOption('paperOrientation', 'landscape'); // Defaults to "portrait"

        //To set view variables
        $pdf->setVariables(array(
           'hr_report' => $this->employeeService->getHrReport($reporttype),
		   'contract' => $contract,
		   'regular' => $regular,
		   'studyLevel' => $this->employeeService->listSelectData('study_level','study_level'),
		   'organisationList' => $this->employeeService->listSelectData('organisation','organisation_name'),
       ));

        return $pdf;
	}
	
	 function my_encrypt($data, $key) 
	 {
		// Remove the base64 encoding from our key
		$encryption_key = base64_decode($key);
		// Generate an initialization vector
		$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('BF-CFB'));
		// Encrypt the data using AES 256 encryption in CBC mode using our encryption key and initialization vector.
		$encrypted = openssl_encrypt($data, 'BF-CFB', $encryption_key, 0, $iv);
		// The $iv is just as important as the key for decrypting, so save it with our encrypted data using a unique separator (::)
		return bin2hex(base64_encode($encrypted . '::' . $iv));
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

<?php


namespace EmpTravelAuthorization\Controller;

use EmpTravelAuthorization\Service\EmpTravelAuthorizationServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use EmpTravelAuthorization\Model\EmpTravelAuthorization;
use EmpTravelAuthorization\Model\EmpTravelDetails;
use EmpTravelAuthorization\Model\OnBehalfEmpTravelAuthorization;
use EmpTravelAuthorization\Form\EmpTravelAuthorizationForm;
use EmpTravelAuthorization\Form\EmpTravelDetailsForm;
use EmpTravelAuthorization\Form\UpdateTravelAuthorizationForm;
use EmpTravelAuthorization\Form\OnBehalfEmpTravelAuthorizationForm;
use EmpTravelAuthorization\Form\UpdateOnBehalfTravelAuthorizationForm;
use EmpTravelAuthorization\Form\SubmitForm;
use EmpTravelAuthorization\Form\SubmitApprovalForm;
use EmpTravelAuthorization\Form\SubmitTravelOrderForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Zend\Http\Response\Stream;
use Zend\Http\Headers;
 
  
class EmpTravelAuthorizationController extends AbstractActionController
{
    protected $empTravelAuthorizationService;
	protected $notificationService;
    protected $auditTrailService;
    protected $emailService;
    protected $username;
    protected $userrole;
    protected $userregion;
    protected $usertype;
    protected $userDetails;
    protected $userImage;
	protected $employee_details_id;
	protected $organisation_id;
	protected $departments_id;
	protected $departments_units_id;
	protected $keyphrase = "RUB_IMS";
	
	public function __construct(EmpTravelAuthorizationServiceInterface $empTravelAuthorizationService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->empTravelAuthorizationService = $empTravelAuthorizationService;
		$this->notificationService = $notificationService;
        $this->auditTrailService = $auditTrailService;
        $this->serviceLocator = $serviceLocator;
        $this->emailService = $serviceLocator->get('Application\Service\EmailService');
		
		/*
		 * To retrieve the user name from the session
		*/        

        $authPlugin = $serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
        $this->username = $authPlugin['username'];
        $this->userrole = $authPlugin['role'];
        $this->userregion = $authPlugin['region'];
        $this->usertype = $authPlugin['user_type_id'];


        $emp_id = $this->empTravelAuthorizationService->getUserDetailsId($this->username, $tableName = 'employee_details');
        foreach ($emp_id as $emp) {
            $this->employee_details_id = $emp['id'];
            $this->departments_units_id = $emp['departments_units_id'];
			$this->departments_id = $emp['departments_id'];
        }

        //get the organisation id
        $organisationID = $this->empTravelAuthorizationService->getOrganisationId($this->username);
        foreach ($organisationID as $organisation) {
            $this->organisation_id = $organisation['organisation_id'];
        }


        //get the user details such as name
        $this->userDetails = $this->empTravelAuthorizationService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->empTravelAuthorizationService->getUserImage($this->username, $this->usertype);

	}
	
	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }
	
	public function empTravelAuthorizationAction()
    {
		$this->loginDetails();
				
		$form = new EmpTravelAuthorizationForm();
		$empTravelAuthorizationModel = new EmpTravelAuthorization();
		$form->bind($empTravelAuthorizationModel);
		
		$travelForm = new EmpTravelDetailsForm();

		$employeeList = $this->empTravelAuthorizationService->getEmployeeList($this->organisation_id);
		unset($employeeList[$this->employee_details_id]);

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
             	$data = $this->params()->fromPost();
			    $auth_date = date("Y-m-d", strtotime(substr($data['emptravelauthorization']['travel_auth_date'],0,10)));
			    $start_date = date("Y-m-d", strtotime(substr($data['emptravelauthorization']['start_date'],0,10)));
			    $end_date = date("Y-m-d", strtotime(substr($data['emptravelauthorization']['end_date'],0,10)));
			    $travel_auth_date = $auth_date;
             	$check_applied_travel = $this->empTravelAuthorizationService->crossCheckAppliedTravelAuthorization($this->employee_details_id);
             
             	if($travel_auth_date == $check_applied_travel){
             		$message = 'Failure';
             		$this->flashMessenger()->addMessage("You have already applied for travel authorization on " .$check_applied_travel. " and it is still pending. Try again later for other date");
             	}
             	else if($auth_date > $start_date || $auth_date > $end_date){
             		$message = 'Failure';
             		$this->flashMessenger()->addMessage("Authorization Date should not be greater than start date or end date");
             	}
             	else if($start_date > $end_date){
             		$message = 'Failure';
             		$this->flashMessenger()->addMessage("Start Date should not be greater than end. It should be less than or equal to end date.");
             	}
             	else{
             		try {  
						 $result = $this->empTravelAuthorizationService->save($empTravelAuthorizationModel);
						 $id = $result->getId();
						 $encrypted_id = $this->my_encrypt($id, $this->keyphrase);
						 $this->auditTrailService->saveAuditTrail("INSERT", "Travel Authorization", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage('You have applied for travel authorization. Please enter travel details.');
						 return $this->redirect()->toRoute('emptraveldetails', array('id' => $encrypted_id));
						 //return $this->redirect()->toRoute('emptraveldetails');
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
			 'travelForm' => $travelForm,
			 'employeeList' => $employeeList,
			 'employee_details_id' => $this->employee_details_id,
			 'message' => $message,
			 'organisation_id' => $this->organisation_id
         );
	 }
	 
	 
	 public function empTravelDetailsAction()
     {
		 $this->loginDetails();
		 
		 //get the id of the travel authorization proposal
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new EmpTravelDetailsForm();
			$empTravelDetailsModel = new EmpTravelDetails();
			$form->bind($empTravelDetailsModel);
			
			$submitForm = new SubmitForm();

			$travelAuthorizationForm = new UpdateTravelAuthorizationForm();
			
			$travelAuthorization = $this->empTravelAuthorizationService->findTravel($id);
			$travelDetails = $this->empTravelAuthorizationService->findTravelDetails($id);
			$travelOfficiating = $this->empTravelAuthorizationService->findTravelOfficiating($id);

			$employeeList = $this->empTravelAuthorizationService->getEmployeeList($this->organisation_id);
			unset($employeeList[$this->employee_details_id]);

			$message = NULL;
	
			 $request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());

				 $data = $this->params()->fromPost();
				 $fromDate = $data['emptraveldetails']['from_date'];
				 $toDate = $data['emptraveldetails']['to_date'];

				 /*if($fromDate > $toDate){
				 	$message = 'Failure';
				 	$this->flashMessenger()->addMessage("Your from date should not be greater than to date. Please select the accurate from date and to date!");
				 }else{*/
				 	if ($form->isValid()) {
						 try {
							 $result = $this->empTravelAuthorizationService->saveTravelDetails($empTravelDetailsModel);
							 $encrypted_id = $this->my_encrypt($id, $this->keyphrase);
							 $this->auditTrailService->saveAuditTrail("INSERT", "Travel Details", "ALL", "SUCCESS");
						 	$this->flashMessenger()->addMessage('You have successfully entered travel details.');
						 	 return $this->redirect()->toRoute('emptraveldetails', array('id' => $encrypted_id));
							 //return $this->redirect()->toRoute('emptraveldetails');
						 }
						 catch(\Exception $e) {
								$message = 'Failure';
	                        	$this->flashMessenger()->addMessage($e->getMessage());
								 // Some DB Error happened, log it and let the user know
						 }
					 }
				 //}
			 }
	
			 return array(
			 	 'id' => $id,
				 'form' => $form,
				 'emp_travel_authorization_id' => $id,
				 'submitForm' => $submitForm,
				 'travelAuthorizationForm' => $travelAuthorizationForm,
				 'travelAuthorization' => $travelAuthorization,
				 'travelDetails' => $travelDetails,
				 'travelOfficiating' => $travelOfficiating,
				 'employeeList' => $employeeList,
				 'employee_details_id' => $this->employee_details_id,
				 'organisation_id' => $this->organisation_id,
				 'message' => $message,
				 'keyphrase'=> $this->keyphrase
			 );
		} else {
			return $this->redirect()->toRoute('emptravelstatus');
		}
	 }



	 public function onBehalfEmpTravelAuthorizationAction()
	 {
		$this->loginDetails();
				
		$form = new OnBehalfEmpTravelAuthorizationForm();
		$empTravelAuthorizationModel = new EmpTravelAuthorization();
		$form->bind($empTravelAuthorizationModel);
		
		$travelForm = new EmpTravelDetailsForm();

		$employeeList = $this->empTravelAuthorizationService->getEmployeeList($this->organisation_id);
		unset($employeeList[$this->employee_details_id]);

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
             	$data = $this->params()->fromPost(); 
			    $auth_date = date("Y-m-d", strtotime(substr($data['onbehalfemptravelauthorization']['travel_auth_date'],0,10)));
			    $start_date = date("Y-m-d", strtotime(substr($data['onbehalfemptravelauthorization']['start_date'],0,10)));
			    $end_date = date("Y-m-d", strtotime(substr($data['onbehalfemptravelauthorization']['end_date'],0,10)));
			    $travel_auth_date = $auth_date;
             	$check_applied_travel = $this->empTravelAuthorizationService->crossCheckAppliedTravelAuthorization($data['onbehalfemptravelauthorization']['employee_details_id']);
             
             	if($travel_auth_date == $check_applied_travel){
             		$message = 'Failure';
             		$this->flashMessenger()->addMessage("You have already applied for travel authorization on " .$check_applied_travel. " and it is still pending. Try again later for other date");
             	}
             	else if($auth_date > $start_date || $auth_date > $end_date){
             		$message = 'Failure';
             		$this->flashMessenger()->addMessage("Authorization Date should not be greater than start date or end date");
             	}
             	else if($start_date > $end_date){
             		$message = 'Failure';
             		$this->flashMessenger()->addMessage("Start Date should not be greater than end. It should be less than or equal to end date.");
             	}
             	else{
             		try {  
						 $result = $this->empTravelAuthorizationService->save($empTravelAuthorizationModel);
						 $id = $result->getId();
						 $encrypted_id = $this->my_encrypt($id, $this->keyphrase);
						 $this->auditTrailService->saveAuditTrail("INSERT", "Travel Authorization", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage('You have applied for on behalf travel authorization. Please enter travel details.');
						 return $this->redirect()->toRoute('onbehalfemptraveldetails', array('id' => $encrypted_id));
						 //return $this->redirect()->toRoute('emptraveldetails');
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
			 'travelForm' => $travelForm,
			 'employeeList' => $employeeList,
			 'employee_details_id' => $this->employee_details_id,
			 'message' => $message,
			 'organisation_id' => $this->organisation_id
         );
	 }
	 

	 public function onBehalfEmpTravelDetailsAction()
     {
		 $this->loginDetails();
		 
		 //get the id of the travel authorization proposal
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new EmpTravelDetailsForm();
			$empTravelDetailsModel = new EmpTravelDetails();
			$form->bind($empTravelDetailsModel);
			
			$submitForm = new SubmitForm();

			$onBehalfTravelAuthorizationForm = new UpdateOnBehalfTravelAuthorizationForm();
			
			$travelAuthorization = $this->empTravelAuthorizationService->findTravel($id);
			$travelDetails = $this->empTravelAuthorizationService->findTravelDetails($id);
			$travelOfficiating = $this->empTravelAuthorizationService->findTravelOfficiating($id);

			$employeeList = $this->empTravelAuthorizationService->getEmployeeList($this->organisation_id);
			unset($employeeList[$this->employee_details_id]);

			$message = NULL;
	
			 $request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());

				 $data = $this->params()->fromPost();
				 $fromDate = $data['emptraveldetails']['from_date'];
				 $toDate = $data['emptraveldetails']['to_date'];

				 if($fromDate > $toDate){
				 	$message = 'Failure';
				 	$this->flashMessenger()->addMessage("Your from date should not be greater than to date. Please select the accurate from date and to date!");
				 }else{
				 	if ($form->isValid()) {
						 try {
							 $result = $this->empTravelAuthorizationService->saveTravelDetails($empTravelDetailsModel);
							 $encrypted_id = $this->my_encrypt($id, $this->keyphrase);
							 $this->auditTrailService->saveAuditTrail("INSERT", "Travel Details", "ALL", "SUCCESS");
						 	$this->flashMessenger()->addMessage('You have successfully entered travel details.');
						 	 return $this->redirect()->toRoute('onbehalfemptraveldetails', array('id' => $encrypted_id));
							 //return $this->redirect()->toRoute('emptraveldetails');
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
			 	 'id' => $id,
				 'form' => $form,
				 'emp_travel_authorization_id' => $id,
				 'submitForm' => $submitForm,
				 'onBehalfTravelAuthorizationForm' => $onBehalfTravelAuthorizationForm,
				 'travelAuthorization' => $travelAuthorization,
				 'travelDetails' => $travelDetails,
				 'travelOfficiating' => $travelOfficiating,
				 'employeeList' => $employeeList,
				 'employee_details_id' => $this->employee_details_id,
				 'organisation_id' => $this->organisation_id,
				 'message' => $message,
				 'keyphrase'=> $this->keyphrase
			 );
		} else {
			return $this->redirect()->toRoute('emptravelstatus');
		}
     }



     // Function to update the travel authorization from the travel details if there is any update
     public function updateTravelAuthorizationAction()
     {
     	$this->loginDetails(); 

     	//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$travelAuthorizationForm = new UpdateTravelAuthorizationForm();
			$empTravelAuthorizationModel = new EmpTravelAuthorization();
			$travelAuthorizationForm->bind($empTravelAuthorizationModel);

			$form = new EmpTravelDetailsForm();
	
	        $message = NULL;

	         //$organisation_id = 1;
	        $request = $this->getRequest();
	         if ($request->isPost()) {
				 $travelAuthorizationForm->setData($request->getPost());
				 $data = array_merge_recursive(
					$request->getPost()->toArray(),
					$request->getFiles()->toArray()
			 ); 
			 $travelAuthorizationForm->setData($data);
	             if ($travelAuthorizationForm->isValid()) { 
			         try {
			             $this->empTravelAuthorizationService->updateTravelAuthorization($empTravelAuthorizationModel);
			             $this->auditTrailService->saveAuditTrail("UPDATE", "Travel Authorization", "ALL", "SUCCESS");

			             $this->flashMessenger()->addMessage('You have successfully updated the travel authorization.');
			             return $this->redirect()->toRoute('emptraveldetails', array('id'=>$this->my_encrypt($id, $this->keyphrase)));
			         }
			         catch(\Exception $e) {
			                 die($e->getMessage());
			                 // Some DB Error happened, log it and let the user know
			                }
			            }
			        } 
	         
	        return array(
	        	'id' => $id,
	            'form' => $form,
	            'travelAuthorizationForm' => $travelAuthorizationForm,
	            'message' => $message,
	            'keyphrase' => $this->keyphrase,
	        );
		}else{
			return $this->redirect()->toRoute('emptraveldetails');
		}
	 }
	 

	 // Function to update the travel authorization from the travel details if there is any update
     public function updateOnBehalfTravelAuthorizationAction()
     {
     	$this->loginDetails(); 

     	//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$onBehalfTravelAuthorizationForm = new UpdateOnBehalfTravelAuthorizationForm();
			$empTravelAuthorizationModel = new EmpTravelAuthorization();
			$onBehalfTravelAuthorizationForm->bind($empTravelAuthorizationModel);

			$form = new EmpTravelDetailsForm();
	
	        $message = NULL;

	         //$organisation_id = 1;
	        $request = $this->getRequest();
	         if ($request->isPost()) {
				 $onBehalfTravelAuthorizationForm->setData($request->getPost());
				 $data = array_merge_recursive(
					$request->getPost()->toArray(),
					$request->getFiles()->toArray()
			 ); 
			 $onBehalfTravelAuthorizationForm->setData($data);
	             if ($onBehalfTravelAuthorizationForm->isValid()) { 
			         try {
			             $this->empTravelAuthorizationService->updateTravelAuthorization($empTravelAuthorizationModel);
			             $this->auditTrailService->saveAuditTrail("UPDATE", "Travel Authorization", "ALL", "SUCCESS");

			             $this->flashMessenger()->addMessage('You have successfully updated the on behalf travel authorization.');
			             return $this->redirect()->toRoute('onbehalfemptraveldetails', array('id'=>$this->my_encrypt($id, $this->keyphrase)));
			         }
			         catch(\Exception $e) {
			                 die($e->getMessage());
			                 // Some DB Error happened, log it and let the user know
			                }
			            }
			        } 
	         
	        return array(
	        	'id' => $id,
	            'form' => $form,
	            'onBehalfTravelAuthorizationForm' => $onBehalfTravelAuthorizationForm,
	            'message' => $message,
	            'keyphrase' => $this->keyphrase,
	        );
		}else{
			return $this->redirect()->toRoute('onbehalfemptraveldetails');
		}
     }


     //Function to send travel authorization email to the particular applicant supervisor
    public function sendTravelAuthorizationEmail($employee_details_id, $departments_id, $departments_units_id, $userrole)
    {
    	$this->loginDetails();

    	$supervisor_email = $this->empTravelAuthorizationService->getSupervisorEmailId($userrole, $departments_units_id);

	 	$applicant_name = NULL;
	 	$applicant = $this->empTravelAuthorizationService->getTourApplicant($employee_details_id);
	 	foreach($applicant as $temp){
	 		$applicant_name = $temp['first_name'].' '.$temp['middle_name'].' '.$temp['last_name'];
	 	}

	 	foreach($supervisor_email as $email){
	 		$toEmail = $email;
	        $messageTitle = "New Travel Authorization";
	        //$messageBody = "<h2>".$applicant_name."</h2><b>has applied for leave on ".date('Y-m-d')."</b><br>.<b>For Details: Please click below link</b> <br><u>http://ims.rub.edu.bt/public/empleaveapproval/</u>";
			$messageBody = "Dear Sir/Madam,<br><h3>".$applicant_name." has applied for travel authorization on ".date('Y-m-d').".</h3><br><b>Please click the link below for necessary action.</b><br><u>http://ims.rub.edu.bt/public/emptravellist</u><p>This is an auto-generated email and Please do not reply because this mail id is not monitored</p>";

	        $this->emailService->sendMailer($toEmail, $messageTitle, $messageBody);
	 	}   
    }
    //Function to send email to the applicant and substitution when approved
    public function sendApprovedTravelEmail($id, $employee_details_id, $organisation_id)
    {
    	$this->loginDetails();

		$applicant_name = NULL;
		$applicant_email = NULL;
	 	$applicantDetails = $this->empTravelAuthorizationService->getApprovedTravelApplicantDetails($id);

	 	foreach($applicantDetails as $temp){
	 		$applicant_name = $temp['first_name'].' '.$temp['middle_name'].' '.$temp['last_name'];
	 		$applicant_email = $temp['email'];
	 	}

 		$toEmail = $applicant_email;

 		$messageTitle = "Approved Travel Application";
 		$messageBody = "Dear Sir/Madam (".$applicant_name."),<br> Your travel application has been approved on ".date('Y-m-d')." and a copy have been send to Administrative Officer for necessary action.<p>This is an auto-generated email and Please do not reply because this mail id is not monitored</p>";

 		$this->emailService->sendMailer($toEmail, $messageTitle, $messageBody);  

 		//$this->sendSubstitutionLeaveEmail($id, $applicant_name);
    	
	}
	public function sendRejectedTravelEmail($id, $employee_details_id, $organisation_id)
    {
    	$this->loginDetails();

		$applicant_name = NULL;
		$applicant_email = NULL;
	 	$applicantDetails = $this->empTravelAuthorizationService->getApprovedTravelApplicantDetails($id);

	 	foreach($applicantDetails as $temp){
	 		$applicant_name = $temp['first_name'].' '.$temp['middle_name'].' '.$temp['last_name'];
	 		$applicant_email = $temp['email'];
	 	}

 		$toEmail = $applicant_email;

 		$messageTitle = "Rejected Travel Application";
 		$messageBody = "Dear Sir/Madam (".$applicant_name."),<br> Your travel application has been rejected on ".date('Y-m-d').".<p>This is an auto-generated email and Please do not reply because this mail id is not monitored</p>";

 		$this->emailService->sendMailer($toEmail, $messageTitle, $messageBody);  
	
		return;
    	
	}

    public function sendTravelApprovedEmailtoADM($id, $employee_details_id, $organisation_id)
    {
    	$this->loginDetails();

    	$travelAuthNo = $this->empTravelAuthorizationService->getTravelAuthNo($id);

    	$adm_email = $this->empTravelAuthorizationService->getAdmEmailId($employee_details_id);

	 	$applicant_name = NULL;
	 	$applicant = $this->empTravelAuthorizationService->getTravelApplicant($id);
	 	foreach($applicant as $temp){
	 		$applicant_name = $temp['first_name'].' '.$temp['middle_name'].' '.$temp['last_name'];
	 	}

	 	foreach($adm_email as $email){
	 		$toEmail = $email['email'];
	        $messageTitle = "Approved Travel Authorization";
	        //$messageBody = "<h2>".$applicant_name."</h2><b>Have applied for leave on ".date('Y-m-d')."</b><br>.<b>For Details: Please click below link</b> <br><u>http://ims.rub.edu.bt/public/empleaveapproval/</u>";
			$messageBody = "Dear Sir/Madam,<br><h3>".$applicant_name." Travel Application has been approved on ".date('Y-m-d')." and Authorization Number is <b>".$travelAuthNo."</b>.</h3><br><b>Please click the link below for necessary action.</b><br><u>http://ims.rub.edu.bt/public/emptravelorder</u><p>This is an auto-generated email and Please do not reply because this mail id is not monitored</p>";

	        $this->emailService->sendMailer($toEmail, $messageTitle, $messageBody);
	 	}
    }

    public function sendTravelApprovedEmailtoRegistrar($id, $employee_details_id, $organisation_id)
    {
    	$this->loginDetails();

    	$travelAuthNo = $this->empTravelAuthorizationService->getTravelAuthNo($id);

    	$reg_email = $this->empTravelAuthorizationService->getRegistrarEmailId($employee_details_id);

	 	$applicant_name = NULL;
	 	$applicant = $this->empTravelAuthorizationService->getTravelApplicant($id);
	 	foreach($applicant as $temp){
	 		$applicant_name = $temp['first_name'].' '.$temp['middle_name'].' '.$temp['last_name'];
	 	}

	 	foreach($reg_email as $email){
	 		$toEmail = $email['email'];
	        $messageTitle = "Approved Travel Authorization";
	        //$messageBody = "<h2>".$applicant_name."</h2><b>Have applied for leave on ".date('Y-m-d')."</b><br>.<b>For Details: Please click below link</b> <br><u>http://ims.rub.edu.bt/public/empleaveapproval/</u>";
			$messageBody = "Dear Sir/Madam,<br><h3>".$applicant_name." Travel Application has been approved on ".date('Y-m-d')." and Authorization Number is <b>".$travelAuthNo."</b>.</h3><br><b>Please click the link below for necessary action.</b><br><u>http://ims.rub.edu.bt/public/emptravelorder</u><p>This is an auto-generated email and Please do not reply because this mail id is not monitored</p>";

	        $this->emailService->sendMailer($toEmail, $messageTitle, $messageBody);
	 	}
    }


     public function downloadRelatedTourDocumentAction()
     {
     	//get the param from the view file
		$file_location = $this->params()->fromRoute('filename',0);
		
		//extract the training id and column name
		$column_name = preg_replace('!\d+!', '', $file_location);
		$column_name = rtrim($column_name,"_");
		preg_match_all('!\d+!',$file_location, $id);
		$travel_authorization_id = implode(' ', $id[0]);
		
		//get the location of the file from the database		
		$fileArray = $this->empTravelAuthorizationService->getFileName($travel_authorization_id, $column_name);
		$file;
		foreach($fileArray as $set){
			$file = $set[$column_name];
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

	 
	 public function deleteTravelDetailsAction()
	 {
		 $this->loginDetails();
		 
		 //get the id of the travel authorization proposal
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			//Get the id of the travel authorization for redirection
			$authorization_id = $this->empTravelAuthorizationService->getTravelAuthorizationId($id);
			 try {
				 $result = $this->empTravelAuthorizationService->deleteTravelDetails($id);
				 $encrypted_id = $this->my_encrypt($authorization_id, $this->keyphrase);
				 return $this->redirect()->toRoute('emptraveldetails', array('id' => $encrypted_id));
				 //return $this->redirect()->toRoute('emptraveldetails');
			 }
			 catch(\Exception $e) {
					 die($e->getMessage());
					 // Some DB Error happened, log it and let the user know
			 }
	
		} else {
			return $this->redirect()->toRoute('emptravelstatus');
		}
	 }


	  /*
    * The action is for update the Dept to Individual Item Issue
    */
    
    public function updateEmpTravelDetailStatusAction()
    {
        $this->loginDetails();

        //Value 1 is change of status from "Not Issue" to "Issue to Individual"
        //need to take care of organisation as well

        $message = NULL;
        
        $id = (int) $this->params()->fromRoute('id', 0);

       // if($value == 1){
            $status = 'Submitted';
            $previousStatus = 'Pending';
        //}

         try {
             $this->empTravelAuthorizationService->updateEmpTravelDetailStatus($status, $previousStatus, $id);
             $this->auditTrailService->saveAuditTrail("UPDATE", "Travel Authorization", "ALL", "SUCCESS");
             $this->sendTravelAuthorizationEmail($this->employee_details_id, $this->departments_id, $this->departments_units_id, $this->userrole);
             $this->flashMessenger()->addMessage('You have successfully submitted you travel details to your supervisor.');
             return $this->redirect()->toRoute('emptravelauthorization');
         }
         catch(\Exception $e) {
            $message = 'Failure';
            $this->flashMessenger()->addMessage($e->getMessage());
            return $this->redirect()->toRoute('emptravelauthorization');
                 // Some DB Error happened, log it and let the user know
         }

        return array(
        	'message' => $message,
        );
    }

	 
	 public function empTravelListAction()
	 {
		 $this->loginDetails(); 

		 $message = NULL;
		 
		$empTravels = $this->empTravelAuthorizationService->listAllTravels('Submitted', $this->organisation_id, $userrole = $this->userrole, $this->employee_details_id, $this->departments_id);
		$approvedEmpTravels = $this->empTravelAuthorizationService->listAllTravels('Approved', $this->organisation_id, $userrole = $this->userrole, $this->employee_details_id, $this->departments_id);
		$rejectedEmpTravels = $this->empTravelAuthorizationService->listAllTravels('Rejected', $this->organisation_id, $userrole = $this->userrole, $this->employee_details_id, $this->departments_id);

         return array(
			 'travel' => $empTravels,
			 'approvedTravels' => $approvedEmpTravels,
			 'rejectedTravels' => $rejectedEmpTravels,
			// 'employees' => $employees,
			 'message' => $message,
			 'keyphrase' => $this->keyphrase
         );
	 }
	 
	 public function viewTravelDetailsAction()
     {
		 $this->loginDetails();
		 
        //get the id of the travel authorization proposal
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);

		$message = NULL;
		
		if(is_numeric($id)){
			$empDetails = $this->empTravelAuthorizationService->getEmployeeDetails($id);
			$travelDetails = $this->empTravelAuthorizationService->getTravelDetails($id);
			$travelAuthorization = $this->empTravelAuthorizationService->findTravel($id);

			$travelOfficiating = $this->empTravelAuthorizationService->findTravelOfficiating($id);

			$fromDate = $this->empTravelAuthorizationService->findFromTravelDate($id);
			$toDate = $this->empTravelAuthorizationService->findToTravelDate($id);
			
			$form = new SubmitApprovalForm();

			$request = $this->getRequest();
			if($request->isPost()){
				$form->setData($request->getPost());
				if($form->isValid()){ 
					$remarks = $this->getRequest()->getPost('remarks');
               		$status = $this->getRequest()->getPost('tour_status');
               		$employee_details_id = $this->getRequest()->getPost('employee_details_id');
					
               		if($status == 'Approved'){
               			try{
							$this->empTravelAuthorizationService->updateEmpTravelDetail($remarks, $status, $id, $this->organisation_id, $this->employee_details_id);
							$this->notificationService->saveNotification('Travel Authorization Approval', $employee_details_id, 'NULL', 'Travel Authorization');
							$this->auditTrailService->saveAuditTrail("UPDATE", "Travel Authorizations", "ALL", "SUCCESS");
							//$this->sendTravelApprovedEmailtoADM($id, $employee_details_id, $this->organisation_id);
							$this->sendApprovedTravelEmail($id, $employee_details_id, $this->organisation_id);
							$this->sendTravelApprovedEmailtoADM($id, $employee_details_id, $this->organisation_id);
							//$this->sendTravelApprovedEmailtoRegistrar($id, $employee_details_id, $this->organisation_id);
							$this->flashMessenger()->addMessage('You have successfully updated the staff travel details');
	                     	return $this->redirect()->toRoute('emptravellist');
						}
						catch(\Exception $e) {
	                	$message = 'Failure';
	                	$this->flashMessenger()->addMessage($e->getMessage());
	                 	// Some DB Error happened, log it and let the user know
	                	}
               		}
               		else
               		{
               			try{
							$this->empTravelAuthorizationService->updateEmpTravelDetail($remarks, $status, $id, $this->organisation_id, $this->employee_details_id);
							$this->sendRejectedTravelEmail($id, $employee_details_id, $this->organisation_id);
							$this->notificationService->saveNotification('Travel Authorization Approval', $employee_details_id, 'NULL', 'Travel Authorization');
							$this->auditTrailService->saveAuditTrail("UPDATE", "Travel Authorizations", "ALL", "SUCCESS");
							$this->flashMessenger()->addMessage('You have successfully updated the staff travel details');
	                     	return $this->redirect()->toRoute('emptravellist');
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
			 	'id' => $id,
				 'form' => $form,
				 'empDetails' => $empDetails,
				 'emp_travel_authorization_id' => $id,
				 'travelDetails' => $travelDetails,
				 'travelOfficiating' => $travelOfficiating,
				 'fromDate' => $fromDate,
				 'toDate' => $toDate,
				 'travelAuthorization' => $travelAuthorization,
				 'message' => $message,
			 );
		}
		else {
			return $this->redirect()->toRoute('emptravellist');
		}
     }


     public function updateEmpTravelDetailAction()
     {
     	$this->loginDetails();

     	$id = $this->params()->fromPost('id', 0);

     	$form = new SubmitApprovalForm();

        $message = NULL;

     	//$organisation_id = 1;
        $request = $this->getRequest();
        if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
               $remarks = $this->getRequest()->getPost('remarks');
               $status = $this->getRequest()->getPost('tour_status');

                try {
                     $this->empTravelAuthorizationService->updateEmpTravelDetail($remarks, $status, $id);
                    /* $this->auditTrailService->saveAuditTrail("INSERT", "Student Programme Change Details", "ALL", "SUCCESS");
                     $this->auditTrailService->saveAuditTrail("UPDATE", "Student", "programmes_id", "SUCCESS");*/
                     $this->flashMessenger()->addMessage('Travel Details is successfully updated');
                     return $this->redirect()->toRoute('emptravellist');
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
        );  
     }


     public function viewEmpTravelDetailsAction()
     {
     	$this->loginDetails();
     	//get the id of the travel authorization proposal
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$empDetails = $this->empTravelAuthorizationService->getEmployeeDetails($id);
			$travelDetails = $this->empTravelAuthorizationService->getTravelDetails($id);
			$travelAuthorization = $this->empTravelAuthorizationService->findTravel($id);
			$fromDate = $this->empTravelAuthorizationService->findFromTravelDate($id);
			$toDate = $this->empTravelAuthorizationService->findToTravelDate($id);
			
			$form = new EmpTravelAuthorizationForm();
	
			 return array(
			 	'id' => $id,
				 'form' => $form,
				 'empDetails' => $empDetails,
				 'emp_travel_authorization_id' => $id,
				 'travelDetails' => $travelDetails,
				 'fromDate' => $fromDate,
				 'toDate' => $toDate,
				 'travelAuthorization' => $travelAuthorization
			 );
		}
		else {
			return $this->redirect()->toRoute('emptravellist');
		}
     }


     public function empTravelOrderAction()
     {
     	$this->loginDetails(); 

		 $message = NULL;
		 
		$approvedEmpTravels = $this->empTravelAuthorizationService->listEmpApprovedTravels($order_no = "NULL", $this->organisation_id);

		$approvedOrderEmpTravels = $this->empTravelAuthorizationService->listEmpApprovedTravels($order_no = "NOT NULL", $this->organisation_id);
         return array(
			 'approvedEmpTravels' => $approvedEmpTravels,
			 'approvedOrderEmpTravels' => $approvedOrderEmpTravels,			 
			 'message' => $message,
			 'keyphrase' => $this->keyphrase
         );
     }


     public function updateEmpTravelOrderAction()
     {
     	$this->loginDetails();
		 
        //get the id of the travel authorization proposal
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);

		$message = NULL;
		
		if(is_numeric($id)){
			$empDetails = $this->empTravelAuthorizationService->getEmployeeDetails($id);
			$travelDetails = $this->empTravelAuthorizationService->getTravelDetails($id);
			$travelAuthorization = $this->empTravelAuthorizationService->findTravel($id);
			$approvingAuthority = $this->empTravelAuthorizationService->getTourApprovingAuthority($id);
			$fromDate = $this->empTravelAuthorizationService->findFromTravelDate($id);
			$toDate = $this->empTravelAuthorizationService->findToTravelDate($id);
			
			$form = new SubmitTravelOrderForm();

			$request = $this->getRequest();
			if($request->isPost()){
				$form->setData($request->getPost());
             	$data = array_merge_recursive(
					   $request->getPost()->toArray(),
					   $request->getFiles()->toArray()
				); 
				$form->setData($data);
				if($form->isValid()){
					$data = $form->getData(); 
					try{
						$this->empTravelAuthorizationService->updateEmpTravelOrder($data, $id);
						$this->notificationService->saveNotification('Travel Order', $employee_details_id, 'NULL', 'Travel Order');
						$this->auditTrailService->saveAuditTrail("UPDATE", "Travel Authorizations", "ALL", "SUCCESS");
						$this->flashMessenger()->addMessage('You have successfully updated the staff travel order');
                     	return $this->redirect()->toRoute('emptravelorder');
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
				 'empDetails' => $empDetails,
				 'emp_travel_authorization_id' => $id,
				 'travelDetails' => $travelDetails,
				 'fromDate' => $fromDate,
				 'toDate' => $toDate,
				 'travelAuthorization' => $travelAuthorization,
				 'approvingAuthority' => $approvingAuthority,
				 'message' => $message,
				 'keyphrase' => $this->keyphrase,
			 );
		}
		else {
			return $this->redirect()->toRoute('emptravelorder');
		}
     }



     public function viewTravelOrderDetailsAction()
     {
     	$this->loginDetails();
     	//get the id of the travel authorization proposal
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$empDetails = $this->empTravelAuthorizationService->getEmployeeDetails($id);
			$travelDetails = $this->empTravelAuthorizationService->getTravelDetails($id);
			$travelAuthorization = $this->empTravelAuthorizationService->findTravel($id);
			$approvingAuthority = $this->empTravelAuthorizationService->getTourApprovingAuthority($id);
			$fromDate = $this->empTravelAuthorizationService->findFromTravelDate($id);
			$toDate = $this->empTravelAuthorizationService->findToTravelDate($id);
			
			$form = new SubmitTravelOrderForm();
	
			 return array(
			 	'id' => $id,
				 'form' => $form,
				 'empDetails' => $empDetails,
				 'emp_travel_authorization_id' => $id,
				 'travelDetails' => $travelDetails,
				 'fromDate' => $fromDate,
				 'toDate' => $toDate,
				 'travelAuthorization' => $travelAuthorization,
				'approvingAuthority' => $approvingAuthority,
			 );
		}
		else {
			return $this->redirect()->toRoute('emptravellist');
		}
     }


     public function downloadTravelOrderFileAction()
     {
     	//get the param from the view file
		$file_location = $this->params()->fromRoute('filename',0);
		
		//extract the training id and column name
		$column_name = preg_replace('!\d+!', '', $file_location);
		$column_name = rtrim($column_name,"_");
		preg_match_all('!\d+!',$file_location, $id);
		$travel_authorization_id = implode(' ', $id[0]);
		//get the location of the file from the database		
		$fileArray = $this->empTravelAuthorizationService->getFileName($travel_authorization_id, $column_name);
		$file;
		foreach($fileArray as $set){
			$file = $set[$column_name];
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


	 
	 private function my_encrypt($data, $key) 
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
             

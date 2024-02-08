<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace LeaveEncashment\Controller;

use LeaveEncashment\Service\LeaveEncashmentServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use LeaveEncashment\Form\LeaveEncashmentForm;
use LeaveEncashment\Form\SubmitLeaveEncashmentOrderForm;
use LeaveEncashment\Model\LeaveEncashment;

use Zend\Session\Container;
use Zend\Http\Response\Stream;
use Zend\Http\Headers;

/**
 * Description of IndexController
 *
 */
 
class LeaveEncashmentController extends AbstractActionController
{
	protected $leaveService;
	protected $notificationService;
    protected $auditTrailService;
    protected $emailService;
    protected $serviceLocator;
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
	
	public function __construct(LeaveEncashmentServiceInterface $leaveService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->leaveService = $leaveService;
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
		
		/*
		* Getting the student_id/employee_details_id related to username
		*/
		
		$empData = $this->leaveService->getUserDetailsId($this->username, $tableName = 'employee_details');
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			$this->departments_units_id = $emp['departments_units_id'];
			$this->departments_id = $emp['departments_id'];
			}
		
		//get the organisation id
		$organisationID = $this->leaveService->getOrganisationId($this->username);
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
    
	public function applyLeaveEncashmentAction()
    {
        $this->loginDetails();
		
		$form = new LeaveEncashmentForm();
		$leaveModel = new LeaveEncashment();
		$form->bind($leaveModel);
		
		$message = NULL;
		
		$leaveBalance = $this->leaveService->getLeaveBalance($this->employee_details_id);
		$leaveEncashed = $this->leaveService->getLeaveEncashed($this->employee_details_id);

		$applicationList = $this->leaveService->listAll($tableName='emp_leave_encashment', $this->organisation_id, $this->employee_details_id);


		//echo $leaveEncashed; die();
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
             	$cross_check_leave_encashment = $this->leaveService->crossCheckLeaveEncashment($this->employee_details_id);
             	$last_approved_encashment_date = $this->leaveService->crossCheckApprovedLeaveEncashment($this->employee_details_id);

             	$currentYear = date('Y');
				$currentMonth = date('m');

				if($currentMonth <=6){
					$startYear = ($currentYear-1).'-'.'07'.'-'.'01';
					$endYear = $currentYear.'-'.'06'.'-'.'30';
				}else{
					$startYear = $currentYear.'-'.'07'.'-'.'01';
					$endYear = ($currentYear+1).'-'.'06'.'-'.'30';
				}

             	if($cross_check_leave_encashment){
             		$message = 'Failure';
             		$this->flashMessenger()->addMessage("You have already applied for leave encashment and it is still pending");
             	}else if($last_approved_encashment_date > $startYear && $last_approved_encashment_date < $endYear){
             		$message = 'Failure';
             		$this->flashMessenger()->addMessage("You have already applied for leave encashment and it has been approved. You cannot apply for leave encashment more than once in financial year");
             	}
             	else{
             		 try {
					 $this->leaveService->save($leaveModel);
					 $this->flashMessenger()->addMessage('Leave Encashment Application was successful');
					 $this->notificationService->saveNotification('Leave Encashment', 'ALL', 'ALL', 'Leave Encashment Application');
					 $this->auditTrailService->saveAuditTrail("INSERT", "Application for leave encashment", "ALL", "SUCCESS");
					 $this->sendLeaveEncashmentApplicationEmail($this->employee_details_id, $this->departments_id, $this->departments_units_id, $this->userrole);
					 return $this->redirect()->toRoute('empleaveencashmentapplication');
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
			'leaveBalance' => $leaveBalance,
			'leaveEncashed' => $leaveEncashed,
			'message' => $message,
			'applicationList' => $applicationList,
		);
    }

	
	public function viewLeaveEncashmentAction()
    {
        $this->loginDetails();
		
		$message = NULL;
		$pendingList = $this->leaveService->getLeaveEncashment('pending', $this->employee_details_id ,$this->organisation_id, $this->userrole, $this->departments_id);
		//approved list also includes those leave encashment that have been rejected
		$approvedList = $this->leaveService->getLeaveEncashment('Approved', $this->employee_details_id ,$this->organisation_id, $this->userrole, $this->departments_id);
		$rejectedList = $this->leaveService->getLeaveEncashment('Reject', $this->employee_details_id ,$this->organisation_id, $this->userrole, $this->departments_id);
		
        return array(
			'keyphrase' => $this->keyphrase,
			'pendingList' => $pendingList,
			'approvedList' => $approvedList,
			'rejectedList' => $rejectedList,
			'message' => $message);
    }
	
	public function viewLeaveEncashmentStatusAction()
    {
        $this->loginDetails();
		
		$encashmentStatus = $this->leaveService->getLeaveEncashmentStatus($this->employee_details_id, 'staff');
		
        return array(
			'keyphrase' => $this->keyphrase,
			'encashmentStatus' => $encashmentStatus);
    }
	
	public function editLeaveEncashmentAction()
    {
        $this->loginDetails();
		
		$form = new LeaveEncashmentForm();
		$leaveModel = new LeaveEncashment();
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
		 
        return array(
			'form' => $form,
			'keyphrase' => $this->keyphrase);
    }
	
	public function approveLeaveEncashmentAction()
    {
        $this->loginDetails();
		//get the leave encashment id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){

			$leave_encashment_status = $this->leaveService->getLeaveEncashmentStatus($id, 'approval');
			$le_status = array();
			foreach($leave_encashment_status as $status){
				$le_status = $status;
			}
			if($le_status['leave_encashment_status'] != 'pending'){
				$message = 'Failure';
				$this->flashMessenger()->addMessage("You can't approve since it has been already ".$le_status['leave_encashment_status']);
				return $this->redirect()->toRoute('empleaveencashmentlist');
			}else{
				$this->leaveService->updateLeaveEncashment($id,'Approved', $this->employee_details_id);
				$this->flashMessenger()->addMessage('Leave Encashment Application was approved');
				$this->notificationService->saveNotification('Leave Encashment', 'ALL', 'ALL', 'Leave Encashment Approval');
				$this->auditTrailService->saveAuditTrail("Update", "Approval of Leave Encashment", "ALL", "SUCCESS");
				return $this->redirect()->toRoute('empleaveencashmentlist');
			}
		}
		else {
			return $this->redirect()->toRoute('empleaveencashmentlist');
		}
        
		
		
    }
	
	public function rejectLeaveEncashmentAction()
    {
        $this->loginDetails();
		
		//get the leave encashment id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$leave_encashment_status = $this->leaveService->getLeaveEncashmentStatus($id, 'approval');
			$le_status = array();
			foreach($leave_encashment_status as $status){
				$le_status = $status;
			}
			if($le_status['leave_encashment_status'] != 'pending'){
				$message = 'Failure';
				$this->flashMessenger()->addMessage("You can't reject since it has been already ".$le_status['leave_encashment_status']);
				return $this->redirect()->toRoute('empleaveencashmentlist');
			}else{
				$this->leaveService->updateLeaveEncashment($id,'Reject', $this->employee_details_id);
				$this->flashMessenger()->addMessage('Leave Encashment Application was rejected');
				$this->notificationService->saveNotification('Leave Encashment', 'ALL', 'ALL', 'Leave Encashment Rejected');
				$this->auditTrailService->saveAuditTrail("Update", "Rejection of Leave Encashment", "ALL", "SUCCESS");
				return $this->redirect()->toRoute('empleaveencashmentlist');
			}
		}
		else {
			return $this->redirect()->toRoute('empleaveencashmentlist');
		}
		
    }


    public function sendLeaveEncashmentApplicationEmail($employee_details_id, $departments_id, $departments_units_id, $userrole)
    {
    	$this->loginDetails();

    	$supervisor_email = $this->leaveService->getSupervisorEmailId($userrole, $departments_units_id);

	 	$applicant_name = NULL;
	 	$applicant = $this->leaveService->getLeaveEncashmentApplicant($employee_details_id);
	 	foreach($applicant as $temp){
	 		$applicant_name = $temp['first_name'].' '.$temp['middle_name'].' '.$temp['last_name'];
	 	}

	 	foreach($supervisor_email as $email){
	 		$toEmail = $email;
	        $messageTitle = "New Leave Encashment";
	        //$messageBody = "<h2>".$applicant_name."</h2><b>Have applied for leave on ".date('Y-m-d')."</b><br>.<b>For Details: Please click below link</b> <br><u>http://rub-ims.rub.edu.bt/public/empleaveapproval/</u>";
			$messageBody = "Dear Sir/Madam,<br><h3>".$applicant_name." have applied for Leave Encashment on ".date('Y-m-d').".</h3><br><b>Please click the link below for necessary action.</b><br><u>http://rub-ims.rub.edu.bt</u><p>This is an auto-generated email and Please do not reply because this mail id is not monitored</p>";

	        $this->emailService->sendMailer($toEmail, $messageTitle, $messageBody);
	 	}   
	}
	

	public function empLeaveEncashmentOrderAction()
	{
		$this->loginDetails(); 

		 $message = NULL;
		 
		$approvedEmpLeaveEncashment = $this->leaveService->listEmpApprovedLeaveEncashment($order_no = "NULL", $this->organisation_id);

		$approvedOrderLeaveEncashment = $this->leaveService->listEmpApprovedLeaveEncashment($order_no = "NOT NULL", $this->organisation_id);
         return array(
			 'approvedEmpLeaveEncashment' => $approvedEmpLeaveEncashment,
			 'approvedOrderLeaveEncashment' => $approvedOrderLeaveEncashment,			 
			 'message' => $message,
			 'keyphrase' => $this->keyphrase
         );
	}


	public function updateEmpLeaveEncashmentOrderAction()
	{
		$this->loginDetails();
		 
        //get the id of the travel authorization proposal
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);

		$message = NULL;
		
		if(is_numeric($id)){ 
			$empDetails = $this->leaveService->getEmployeeDetails($id);
			$leaveEncashmentDetails = $this->leaveService->getLeaveEncashmentDetails($id);
			
			$form = new SubmitLeaveEncashmentOrderForm();

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
						$this->leaveService->updateEmpLeaveEncashmentOrder($data, $id);
						$this->notificationService->saveNotification('Leave Encashment Order', $employee_details_id, 'NULL', 'Leave Encashment Order');
						$this->auditTrailService->saveAuditTrail("UPDATE", "Emp Leave Encashment", "ALL", "SUCCESS");
						$this->flashMessenger()->addMessage('You have successfully updated the staff leave encashment order');
                     	return $this->redirect()->toRoute('empleaveencashmentorder');
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
				 //'emp_travel_authorization_id' => $id,
				 'leaveEncashmentDetails' => $leaveEncashmentDetails,
				 'message' => $message,
				 'keyphrase' => $this->keyphrase,
			 );
		}
		else {
			return $this->redirect()->toRoute('empleaveencashmentorder');
		}
	}


	public function viewLeaveEncashmentOrderDetailsAction()
	{
		$this->loginDetails();
		 
        //get the id of the travel authorization proposal
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);

		$message = NULL;
		
		if(is_numeric($id)){ 
			$empDetails = $this->leaveService->getEmployeeDetails($id);
			$leaveEncashmentDetails = $this->leaveService->getLeaveEncashmentDetails($id);
			
			$form = new SubmitLeaveEncashmentOrderForm();
	
			 return array(
			 	'id' => $id,
				 'form' => $form,
				 'empDetails' => $empDetails,
				 //'emp_travel_authorization_id' => $id,
				 'leaveEncashmentDetails' => $leaveEncashmentDetails,
				 'message' => $message,
				 'keyphrase' => $this->keyphrase,
			 );
		}
		else {
			return $this->redirect()->toRoute('empleaveencashmentorder');
		}
	}


	public function downloadLeaveEncashmentOrderFileAction()
	{
		//get the param from the view file
		$file_location = $this->params()->fromRoute('filename',0);
		
		//extract the training id and column name
		$column_name = preg_replace('!\d+!', '', $file_location);
		$column_name = rtrim($column_name,"_");
		preg_match_all('!\d+!',$file_location, $id);
		$id = implode(' ', $id[0]); //echo $file_location; echo '<br>'; echo $id; die();
		//get the location of the file from the database		
		$fileArray = $this->leaveService->getFileName($id, $column_name);
		$file;
		foreach($fileArray as $set){
			$file = $set[$column_name];
		}
		
		$mimetype = mime_content_type($file);
		$response = new Stream();
		$response->setStream(fopen($file, 'r'));
		$response->setStatusCode(200);
		$response->setStreamName(basename($file));
		$headers = new Headers();
		$headers->addHeaderLine('Content-Disposition: inline', 'attachment; filename="' . basename($file) .'"')
				->addHeaderLine('Content-Type',$mimetype)
				->addHeaderLine('Content-Length', filesize($file))
				->addHeaderLine('Expires', '@0') // @0, because zf2 parses date as string to \DateTime() object
				->addHeaderLine('Cache-Control','must-revalidate')
				->addHeaderLine('Pragma','public')
				->addHeaderLine('Content-Transfer-Encoding: binary')
				->addHeaderLine('Accept-Ranges: bytes');

		$response->setHeaders($headers);
		return $response;
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

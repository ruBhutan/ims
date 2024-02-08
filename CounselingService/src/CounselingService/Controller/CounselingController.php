<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CounselingService\Controller;

use CounselingService\Service\CounselingServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use CounselingService\Model\Counselor;
use CounselingService\Model\CounselingAppointment;
use CounselingService\Model\CounselingNotes;
use CounselingService\Model\CounselingSuggest;
use CounselingService\Model\ScheduledAppointment;
use CounselingService\Form\CounselorForm;
use CounselingService\Form\CounselingAppointmentForm;
use CounselingService\Form\CounselingNotesForm;
use CounselingService\Form\CounselingSuggestForm;
use CounselingService\Form\ScheduledAppointmentForm;
use CounselingService\Form\SearchForm;
use CounselingService\Model\Counseling;
use Zend\Session\Container;
use Zend\Http\Response\Stream;
use Zend\Http\Headers;

/**
 * Description of IndexController
 *
 */
 
class CounselingController extends AbstractActionController
{
	protected $counselingService;
	protected $notificationService;
    protected $auditTrailService;
	protected $serviceLocator;
	protected $emailService;
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

	protected $cryptKey = "RUB-IMS@Counseling#$!";
	
	public function __construct(CounselingServiceInterface $counselingService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->counselingService = $counselingService;
		$this->notificationService = $notificationService;
        $this->auditTrailService = $auditTrailService;
		$this->serviceLocator = $serviceLocator;
		$this->emailService = $serviceLocator->get('Application\Service\EmailService');
				
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
		
		$empData = $this->counselingService->getUserDetailsId($this->username, $tableName = 'employee_details');
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			}

		if($this->employee_details_id == NULL)
		{
			$studentData = $this->counselingService->getUserDetailsId($this->username, $tableName = 'student');
			foreach($studentData as $std){
			$this->student_id = $std['id'];
			$this->organisation_id = $std['organisation_id'];
			}
		}

		//get the organisation id
		if($this->employee_details_id == NULL){

			$organisationID = $this->counselingService->getOrganisationId($this->username, $tableName = 'student');
		}
		else {
			$organisationID = $this->counselingService->getOrganisationId($this->username, $tableName = 'employee_details');
		}
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}
		

		//get the user details such as name
		$this->userDetails = $this->counselingService->getUserDetails($this->username, $this->usertype);
		$this->userImage = $this->counselingService->getUserImage($this->username, $this->usertype);		
	}

	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
		$this->layout()->setVariable('userDetails', $this->userDetails);
		$this->layout()->setVariable('userImage', $this->userImage);
    }

    public function appointCounselorAction()
    {
    	$this->loginDetails();	

    	$counselorID = NULL;

		$form = new CounselorForm();
		$counselingModel = new Counselor();
		$form->bind($counselingModel);

		$message = NULL;

		$selectStaff = $this->counselingService->listSelectData($tableName = 'employee_details', $this->organisation_id);
		        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             $data = $this->params()->fromPost();
             $counselorId = $data['counselor']['employee_details_id'];

             $check_active_counselor = $this->counselingService->crossCheckCounselor($counselorId, $this->organisation_id, 'Active');
             $check_inactive_counselor = $this->counselingService->crossCheckCounselor($counselorId, $this->organisation_id, 'Inactive');

             if($check_active_counselor){
             	$message = 'Failure';
             	$this->flashMessenger()->addMessage('You have already appointed this particular staff as counselor and still active.');
             }else if($check_inactive_counselor){
             	$message = 'Failure';
             	$this->flashMessenger()->addMessage('You have already appointed this particular staff as counselor and he is currently in-active. Please activate it to change status to active');
             }else{
             	if ($form->isValid()) {
	                 try {
						 $this->counselingService->saveCounselor($counselingModel);
						 $this->notificationService->saveNotification('Counselor Appointment', $counselorId, 'NULL', 'Student Counseling');
	                     $this->auditTrailService->saveAuditTrail("INSERT", "Counselor", "ALL", "SUCCESS");

	                     $this->flashMessenger()->addMessage('You have successfully added counselor.');
	                     return $this->redirect()->toRoute('appointcounselor');
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
			'organisation_id' => $this->organisation_id,
			'selectStaff' => $selectStaff,
			'counselorList' => $this->counselingService->getCounselorList($this->organisation_id),
			'message' => $message,
			'keyphrase' => $this->keyphrase,
			);

    }


    //Function to update the counselor status
    public function updateCounselorStatusAction()
    {
    	$this->loginDetails();

    	$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$current_status = $this->counselingService->getCurrentCounselorStatus($id);
        	if($current_status == 'Active'){
        		try{
        			$this->counselingService->updateCounselorStatus($status = 'Inactive', $previousStatus=NULL, $id);
        			$this->auditTrailService->saveAuditTrail("UPDATE", "Counselor", "status", "SUCCESS");
        			$this->flashMessenger()->addMessage("You have successfully Deactivated counselor status");
        			return $this->redirect()->toRoute('appointcounselor');
        		}
	        	catch(\Exception $e){
	        		$message = 'Failure';
				 	$this->flashMessenger()->addMessage($e->getMessage());
	        	}
        	}
        	else if($current_status == 'Inactive'){
        		try{
        			$this->counselingService->updateCounselorStatus($status = 'Active', $previousStatus=NULL, $id);
        			$this->auditTrailService->saveAuditTrail("UPDATE", "Counselor", "status", "SUCCESS");
        			$this->flashMessenger()->addMessage("You have successfully Activated counselor status");
        			return $this->redirect()->toRoute('appointcounselor');
        		}
	        	catch(\Exception $e){
	        		$message = 'Failure';
				 	$this->flashMessenger()->addMessage($e->getMessage());
	        	}
        	}
        }else{
        	return $this->redirect()->toRoute('appointcounselor');
        }

    }
	
	//function to search and then display before recommending/suggesting student for counseling
	public function recommendCounselingAction()
    {
    	$this->loginDetails();
       $form = new SearchForm();

       $message = NULl;

       $suggestionType = NULL;
	   
	   $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$suggestionType = $this->getRequest()->getPost('suggestion_type');
				$name = $this->getRequest()->getPost('name');
				$suggestionId = $this->getRequest()->getPost('suggestion_id');
				$suggestionList = $this->counselingService->getSuggestionList($suggestionType, $name, $suggestionId, $this->organisation_id);
             }
         }
		 else {
			 $suggestionList = array();
		 }
		
		return new ViewModel(array(
            'form' => $form,
            'suggestionType' => $suggestionType,
			'suggestionList' => $suggestionList,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
            ));
    }


    //add the staff details that needs/is suggested for counseling
	public function recommendStaffCounselingAction()
    {
    	$this->loginDetails();
        //get the student id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new CounselingSuggestForm();
			$counselingModel = new CounselingSuggest();
			$form->bind($counselingModel);
			
			$staff = $this->counselingService->getStaffDetails($id);
			
			$employeeDetails = $this->counselingService->getEmployeeDetails($this->employee_details_id);

			$selectCounselor = $this->counselingService->listSelectData($tableName = 'counselor', $this->organisation_id);

			$message = NULL;
	        
	        $request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             $data = $this->params()->fromPost();
	              $counselorId = $data['suggestcounseling']['counselor_id'];
	              $subject = $data['suggestcounseling']['subject'];
	              $suggestedId = $data['suggestcounseling']['suggested_id'];
	              $suggestedType = $data['suggestcounseling']['suggested_type'];
	              //$suggestedBy = $data['suggestcounseling']['suggested_by'];
	              $counselor = $this->counselingService->getCounselorId($counselorId);
	              $check_suggested_counseling = $this->counselingService->crossCheckSuggestedCounseling($subject, $suggestedId, $suggestedType, $counselorId);
	              if($check_suggested_counseling){
	              	$message = 'Failure';
	              	$this->flashMessenger()->addMessage('This particular staff is already suggested for couseling for similar subject to particular counselor and it is still Pending. If you want to suggest then please look for different counselor or different subject if and only if this particular staff require counseling.');
	              }
	              else{
	              	if ($form->isValid()) {
		                 try {
							 $this->counselingService->saveCounselingRecommendation($counselingModel);
							 $this->sendRecommendedCounselingEmail($counselor, $id, $suggestedType);
							 $this->notificationService->saveNotification('Counseling Recommendation', $id, 'NULL', 'Staff Counseling');
							 $this->notificationService->saveNotification('Counseling Recommendation', $counselor, 'NULL', 'Staff Counseling');
		                     $this->auditTrailService->saveAuditTrail("INSERT", "Counseling Suggest", "ALL", "SUCCESS");

		                     $this->flashMessenger()->addMessage('You have successfully recommended Staff for counseling');
							 return $this->redirect()->toRoute('recommendcounselinglist');
						 }
						 catch(\Exception $e) {
						 	$message = 'Failure';
						 	$this->flashMessenger()->addMessage($e->getMessage());
						 	return $this->redirect()->toRoute('recommendcounselinglist');
								 // Some DB Error happened, log it and let the user know
						 }
		             }
	              }
	         }
			 
	        return array(
				'form' => $form,
				'staff' => $staff,
				'suggested_id' => $id,
				'employee_details_id' => $this->employee_details_id,
				'selectCounselor' => $selectCounselor,
				'message' => $message,
				'employeeDetails' => $employeeDetails
			);
        }else{
        	$this->redirect()->toRoute('recommendcounseling');
        }
    }

	
	//add the student details that needs/is suggested for counseling
	public function recommendStudentCounselingAction()
    {
    	$this->loginDetails();
        //get the student id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new CounselingSuggestForm();
			$counselingModel = new CounselingSuggest();
			$form->bind($counselingModel);
			
			$student = $this->counselingService->getStudentDetails($id);
			
			$employeeDetails = $this->counselingService->getEmployeeDetails($this->employee_details_id);

			$selectCounselor = $this->counselingService->listSelectData($tableName = 'counselor', $this->organisation_id);

			$message = NULL;
	        
	        $request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             $data = $this->params()->fromPost();
	              $counselorId = $data['suggestcounseling']['counselor_id'];
	              $subject = $data['suggestcounseling']['subject'];
	              $suggestedId = $data['suggestcounseling']['suggested_id'];
	              $suggestedType = $data['suggestcounseling']['suggested_type'];
	              //$suggestedBy = $data['suggestcounseling']['suggested_by'];
	              $counselor = $this->counselingService->getCounselorId($counselorId);
	              $check_suggested_counseling = $this->counselingService->crossCheckSuggestedCounseling($subject, $suggestedId, $suggestedType, $counselorId);
	              if($check_suggested_counseling){
	              	$message = 'Failure';

              		$this->flashMessenger()->addMessage('This particular student is already suggested for couseling for similar subject to particular counselor and it is still Pending. If you want to suggest then please look for different counselor or different subject if and only if this particular student require counseling.');
	              }
	              else{
	              	if ($form->isValid()) {
		                 try {
							 $this->counselingService->saveCounselingRecommendation($counselingModel);
							 $this->notificationService->saveNotification('Counseling Recommendation', $id, 'NULL', 'Student Counseling');
							 $this->notificationService->saveNotification('Counseling Recommendation', $counselor, 'NULL', 'Student Counseling');
		                     $this->auditTrailService->saveAuditTrail("INSERT", "Counseling Suggest", "ALL", "SUCCESS");

		                     $this->flashMessenger()->addMessage('You have successfully recommended Staff for counseling');
							 return $this->redirect()->toRoute('recommendcounselinglist');
						 }
						 catch(\Exception $e) {
							$message = 'Failure';
						 	$this->flashMessenger()->addMessage($e->getMessage());
						 	return $this->redirect()->toRoute('recommendcounselinglist');
								 // Some DB Error happened, log it and let the user know
						 }
		             }
	              }
	         }
			 
	        return array(
				'form' => $form,
				'student' => $student,
				'suggested_id' => $id,
				'employee_details_id' => $this->employee_details_id,
				'selectCounselor' => $selectCounselor,
				'message' => $message,
				'employeeDetails' => $employeeDetails
			);
        }else{
        	$this->redirect()->toRoute('recommendcounseling');
        }
    }
	
	public function recommendCounselingListAction()
	{
		$this->loginDetails();

		$message = NULL;

		$staffRecommendCounselingList = $this->counselingService->getStaffRecommendCounselingList($tableName = 'counseling_suggest', $this->employee_details_id);
		$stdRecommendCounselingList = $this->counselingService->getStdRecommendCounselingList($tableName = 'counseling_suggest', $this->employee_details_id);
		return new ViewModel(array(
			'staffRecommendCounselingList' => $staffRecommendCounselingList,
			'stdRecommendCounselingList' => $stdRecommendCounselingList,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
            ));
	}


	public function editRecommendCounselingAction()
	{
		$this->loginDetails();

		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new CounselingSuggestForm();
			$counselingModel = new CounselingSuggest();
			$form->bind($counselingModel);

			$suggestType = $this->counselingService->getSuggestedType($id);
			
			$suggestedDetails = $this->counselingService->getSuggestedDetails($id, $suggestType);

			$selectCounselor = $this->counselingService->listSelectData($tableName = 'counselor', $this->organisation_id);

			$recommendCounseling = $this->counselingService->getRecommendCounselingDetails($id);

			$message = NULL;
	        
	        $request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	              $data = $this->params()->fromPost();
	              $counselorId = $data['suggestcounseling']['counselor_id'];
	              $subject = $data['suggestcounseling']['subject'];
	              $suggestedId = $data['suggestcounseling']['suggested_id'];
	              $suggestedType = $data['suggestcounseling']['suggested_type'];
	              $suggestedBy = $data['suggestcounseling']['suggested_by'];
	              $counselor = $this->counselingService->getCounselorId($counselorId);
	              $check_suggested_counseling = $this->counselingService->crossCheckSuggestedCounselingDetails($subject, $suggestedId, $suggestedType, $counselorId, $suggestedBy, $id);
	              if($check_suggested_counseling){
	              	$message = 'Failure';
	              	if($suggestedType == 1){
	              		$this->flashMessenger()->addMessage("You can't edit this counseling subject to ".$subject.". Since you have already have similar subject suggested to this particular staff and it is still pending.");
	              	}
	              	else if($suggestedType == 2){
	              		$this->flashMessenger()->addMessage("You can't edit this counseling subject to ".$subject.". Since you have already have similar subject suggested to this particular student and it is still pending.");
	              	}
	              }
	              else{
	              	if ($form->isValid()) {
	              		try {
							 $this->counselingService->saveCounselingRecommendation($counselingModel);
		                     $this->auditTrailService->saveAuditTrail("UPDATE", "Counseling Suggest", "ALL", "SUCCESS");
		                     $this->flashMessenger()->addMessage('You have successfully edited the recommended for counseling');
							 return $this->redirect()->toRoute('recommendcounselinglist');
						 }
						 catch(\Exception $e) {
							$message = 'Failure';
						 	$this->flashMessenger()->addMessage($e->getMessage());
						 	return $this->redirect()->toRoute('recommendcounselinglist');
								 // Some DB Error happened, log it and let the user know
						 }
		             }
		         }
	         }
			 
	        return array(
	        	'id' => $id,
				'form' => $form,
				'suggestType' => $suggestType,
				'suggestedDetails' => $suggestedDetails,
				'selectCounselor' => $selectCounselor,
				'recommendCounseling' => $recommendCounseling,
				'message' => $message,
			);
        }else{
        	$this->redirect()->toRoute('recommendcounselinglist');
        }
	}


	public function viewRecommendCounselingDetailsAction()
	{
		$this->loginDetails();

		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new CounselingSuggestForm();
			$counselingModel = new CounselingSuggest();
			$form->bind($counselingModel);

			$suggestType = $this->counselingService->getSuggestedType($id);
			
			$suggestedDetails = $this->counselingService->getSuggestedDetails($id, $suggestType);

			$recommendCounselingDetails = $this->counselingService->findRecommendCounselingDetails($id);

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
						 	return $this->redirect()->toRoute('recommendcounselinglist');
								 // Some DB Error happened, log it and let the user know
						 }
		             }
	            //  }
	         }
			 
	        return array(
	        	'id' => $id,
				'form' => $form,
				'suggestType' => $suggestType,
				'suggestedDetails' => $suggestedDetails,
				'employee_details_id' => $this->employee_details_id,
				'recommendCounselingDetails' => $recommendCounselingDetails,
				'message' => $message,
			);
        }else{
        	$this->redirect()->toRoute('recommendcounselinglist');
        }
	}


	public function viewRecommendedCounselingListAction()
	{
		$this->loginDetails();

		$message = NULL;

		$staffRecommendedLists = $this->counselingService->getStaffRecommendedList($status = 'Pending', $this->employee_details_id);
		$stdRecommendedLists = $this->counselingService->getStdRecommendedList($status = 'Pending', $this->employee_details_id);
		return new ViewModel(array(
			'staffRecommendedLists' => $staffRecommendedLists,
			'stdRecommendedLists' => $stdRecommendedLists,
			'keyphrase' => $this->keyphrase,
			'employee_details_id' => $this->employee_details_id,
			'message' => $message,
            ));
	}


	public function grantRecommendedCounselingAppointmentAction()
	{
		$this->loginDetails();
        //get the student id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new ScheduledAppointmentForm();
			$counselingModel = new ScheduledAppointment();
			$form->bind($counselingModel);

			$recommendedType = $this->counselingService->getRecommendedType($tableName = 'counseling_suggest', $id);

			$recommendedDetails = $this->counselingService->findRecommendedDetails($tableName = 'counseling_suggest', $id, $recommendedType);
			
			$recommendCounselingDetail = $this->counselingService->findCounseling($tableName = 'counseling_suggest', $id);

			$message = NULL;
			        
	        $request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             $data = $this->params()->fromPost();
	             $applicantId = $data['scheduledappointment']['applicant_id'];
	             $applicantType = $data['scheduledappointment']['applicant_type'];
	             $appointmentId = $data['scheduledappointment']['counseling_appointment_id'];
	             $scheduledTime = $data['scheduledappointment']['scheduled_time'];
	             $scheduledDate = $data['scheduledappointment']['scheduled_date'];
	             $counselingType = $data['scheduledappointment']['counseling_type'];
				 $scheduled_time = date('h:i A', strtotime($scheduledTime));
				 $scheduled_date = date("Y-m-d", strtotime(substr($scheduledDate,0,10)));
	             $check_counselor_scheduled = $this->counselingService->crossCheckCounselorScheduled($scheduledTime, $scheduled_date, $this->employee_details_id);
	             if($check_counselor_scheduled){
	             	$message = 'Failure';
	             	$this->flashMessenger()->addMessage('You have already scheduled at '.'"'.$scheduled_time.'" '.' on' .' "'.$scheduled_date.'". '.' Please Scheduled different.');
	             }
	             else if($scheduled_date < date('Y-m-d')){
              		$message = 'Failure';
              		$this->flashMessenger()->addMessage("You can't grant an appointment since your scheduled date is less than current date. It should be current date or next date.");
				  }
              	  else if(($scheduled_date == date('Y-m-d'))&&($scheduled_time <= $this->ftime(time(),12))){
              		$message = 'Failure';
              		$this->flashMessenger()->addMessage("You can't grant an appointment since your scheduled time for today is less than or equal to current time. Schedule time should be greater than the current time.");
              	  }
	             else{
	             	if($applicantType == 1){
		             	if ($form->isValid()) {
			                 try {
								 $this->counselingService->grantAppointment($counselingModel, $appointmentId, $counselingType);
								 $this->notificationService->saveNotification('Grand Counseling Recommendation Appointment', $applicantId, 'NULL', 'Staff Counseling');
			                     $this->auditTrailService->saveAuditTrail("INSERT", "Scheduled Counseling Appointments", "ALL", "SUCCESS");

			                     $this->flashMessenger()->addMessage('You have successfully granted the recommended/ suggested counseling appointment');
								 return $this->redirect()->toRoute('recommendedcounselinglist');
							 }
							 catch(\Exception $e) {
							 	$message = 'Failure';
							 	$this->flashMessenger()->addMessage($e->getMessage());
									 // Some DB Error happened, log it and let the user know
							 }
			             }
		             }
		             else if($applicantType == 2){
		             	if ($form->isValid()) {
			                 try {
								 $this->counselingService->grantAppointment($counselingModel, $appointmentId, $counselingType);
								 $this->notificationService->saveNotification('Grand Counseling Recommendation Appointment', $applicantId, 'NULL', 'Student Counseling');
			                     $this->auditTrailService->saveAuditTrail("INSERT", "Scheduled Counseling Appointments", "ALL", "SUCCESS");

			                     $this->flashMessenger()->addMessage('You have successfully granted the recommended/ suggested counseling appointment');
								 return $this->redirect()->toRoute('recommendedcounselinglist');
							 }
							 catch(\Exception $e) {
									 $message = 'Failure';
							 		$this->flashMessenger()->addMessage($e->getMessage());
									 // Some DB Error happened, log it and let the user know
							 }
			             }
		             }
	             }
	         }
			 
	        return array(
				'counseling_appointment_id' => $id,
				'form' => $form,
				'recommendedType' => $recommendedType,
				'recommendedDetails' => $recommendedDetails,
				'recommendCounselingDetail' => $recommendCounselingDetail,
				'message' => $message,
				'employee_details_id' => $this->employee_details_id,
			);
        }else{
        	$this->redirect()->toRoute('recommendedcounselinglist');
        }
	}
	
	public function seekCounselingAppointmentAction()
    {	
    	$this->loginDetails();	
		$form = new CounselingAppointmentForm();
		$counselingModel = new CounselingAppointment();
		$form->bind($counselingModel);

		$selectCounselor = $this->counselingService->listSelectData($tableName = 'counselor', $this->organisation_id);

		$message = NULL;

        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
              $data = $this->params()->fromPost();
              $counselorId = $data['counselingappointment']['counselor_id'];
              $subject = $data['counselingappointment']['subject'];
              $applicant = $data['counselingappointment']['applicant_id'];
              $applicantType = $data['counselingappointment']['applicant_type'];
              $appointmentTime = $data['counselingappointment']['appointment_time'];
              $appointmentDate = $data['counselingappointment']['appointment_date'];
			  $counselor = $this->counselingService->getCounselorId($counselorId);
			  $appointment_time = date('h:i A', strtotime($appointmentTime));
			  $appointment_date = date("Y-m-d", strtotime(substr($appointmentDate,0,10)));
			  
              //$check_counseling_time = $this->counselingService->crossCheckCounselingAppointmentTime($appointmentTime);
              //$check_sounseling_time = $this->counselingService->crossCheckCounselingAppointmentDate($appointmentDate);
              $check_counseling_appointment = $this->counselingService->crossCheckCounselingAppointment($subject, $applicant, $applicantType, $counselorId, $appointmentTime, $appointment_date);
			  
              if($check_counseling_appointment){
              	$message = 'Failure';
              	$this->flashMessenger()->addMessage('You have already seek appointment for the subject '.'"'.$subject.'" '. ' at '.$appointment_time.' on '.$appointment_date.' from this particular counselor and it is still pending. Please try for different subject or different time or different date if you want to seek an appointment.');
              }
              else if($appointment_date < date('Y-m-d')){
              	$message = 'Failure';
              	$this->flashMessenger()->addMessage("You can't apply for counseling appointment since your appointment date is less than current date. It should be current date or next date.");

			  } 
              else if(($appointment_date == date('Y-m-d'))&&($appointment_time <= $this->ftime(time(),12))){
              	$message = 'Failure';
              	$this->flashMessenger()->addMessage("You can't apply for counseling appointment since your appointment time for today is less than or equal to current time. Appointment time should be greater than the current time.");
              }
              else{
	              	if ($form->isValid()) { //var_dump($form); die();
	                 try {
						 $this->counselingService->saveAppointment($counselingModel);
						 $this->sendSeekCounselingEmail($counselor, $applicantType, $appointment_date, $appointment_time, $applicant);
						 $this->notificationService->saveNotification('Counseling Appointment', $counselor, 'NULL', 'Student Counseling');
	                     $this->auditTrailService->saveAuditTrail("INSERT", "Counseling Appointment", "ALL", "SUCCESS");

	                     $this->flashMessenger()->addMessage("You have successfully applied for counseling appointment.");
	                     return $this->redirect()->toRoute('counselingappointment');
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
			'student_id' => $this->student_id,
			'employee_details_id' => $this->employee_details_id,
			'usertype' => $this->usertype,
			'organisation_id' => $this->organisation_id,
			'selectCounselor' => $selectCounselor,
			'indcounselingapplication' => $this->counselingService->getIndCounselingApplicationList($this->username, $this->usertype),
			'message' => $message,
			'keyphrase' => $this->keyphrase,
			);
    }

    public function editIndCounselingAppointmentAction()
    {
    	$this->loginDetails();

    	$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){ 
        	$form = new CounselingAppointmentForm();
			$counselingModel = new CounselingAppointment();
			$form->bind($counselingModel);

			$selectCounselor = $this->counselingService->listSelectData($tableName = 'counselor', $this->organisation_id);

			$CounselingAppointmentDetails = $this->counselingService->getIndCounselingAppointmentDetails($id);

			$message = NULL;

	        $request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	              $data = $this->params()->fromPost();
	              $counselorId = $data['counselingappointment']['counselor_id'];
	              $subject = $data['counselingappointment']['subject'];
	              $applicant = $data['counselingappointment']['applicant_id'];
	              $applicantType = $data['counselingappointment']['applicant_type'];
	              $appointmentTime = $data['counselingappointment']['appointment_time'];
					$appointmentDate = $data['counselingappointment']['appointment_date'];
				  $appointment_time = date('h:i A', strtotime($appointmentTime)); 
	              $check_counseling_appointment = $this->counselingService->crossCheckCounselingAppointmentDetails($subject, $applicant, $applicantType, $id, $counselorId);
				  $appointment_date = date("Y-m-d", strtotime(substr($appointmentDate,0,10)));
	              if($check_counseling_appointment){
	              	$message = 'Failure';
	              	$this->flashMessenger()->addMessage('Unable to edit seek appointment subject to '.'"'.$subject.'" '. 'since already you have same subject and it is still pending.');
	              }
	              else if($appointment_date < date('Y-m-d')){
              		$message = 'Failure';
              		$this->flashMessenger()->addMessage("You can't apply for counseling appointment since your appointment date is less than current date. It should be current date or next date.");
				  }
              	  else if(($appointment_date == date('Y-m-d'))&&($appointment_time <= $this->ftime(time(),12))){
              		$message = 'Failure';
              		$this->flashMessenger()->addMessage("You can't apply for counseling appointment since your appointment time for today is less than or equal to current time. Appointment time should be greater than the current time.");
              	  }
	              else{
		              	if ($form->isValid()) { //var_dump($form); die();
		                 try {
							 $this->counselingService->saveAppointment($counselingModel);
		                     $this->auditTrailService->saveAuditTrail("UPDATE", "Counseling Appointment", "ALL", "SUCCESS");

		                     $this->flashMessenger()->addMessage("You have successfully edited the applied for counseling appointment.");
		                     return $this->redirect()->toRoute('counselingappointment');
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
				'organisation_id' => $this->organisation_id,
				'selectCounselor' => $selectCounselor,
				'CounselingAppointmentDetails' => $CounselingAppointmentDetails,
				'indcounselingapplication' => $this->counselingService->getIndCounselingApplicationList($this->username, $this->usertype),
				'message' => $message,
				);
    	}else{
    		return $this->redirect()->toRoute('counselingappointment');
    	}

	}
	

	public function sendSeekCounselingEmail($counselor, $applicantType, $appointment_date, $appointmentTime, $applicant)
	{
		$this->loginDetails();

    	$counselor_email = $this->counselingService->getCounselorEmail($counselor);

		 $applicant_name = NULL;
		 
		 if($applicantType == 1){
			 $type = "Staff";
			$applicantDetails = $this->counselingService->getCounselingApplicant($tableName = 'employee_details', $applicant);
		 }
		 else if($applicantType == 2){
			 $type = "Student";
			 $applicantDetails = $this->counselingService->getCounselingApplicant($tableName = 'student', $applicant);
		 }
	 	foreach($applicantDetails as $temp){
	 		$applicant_name = $temp['first_name'].' '.$temp['middle_name'].' '.$temp['last_name'];
	 	}

		$toEmail = $counselor_email;
		$messageTitle = "New Counseling Appointment";
		$messageBody = "Dear Sir/Madam,<br>".$type." <b>".$applicant_name."</b> has seek counseling appointment on ".$appointment_date." at ".$appointmentTime.".<br><b>Please click the link below for necessary action.</b><br><u>http://ims.rub.edu.bt</u><p>This is an auto-generated email and Please do not reply because this mail id is not monitored</p>";

		$this->emailService->sendMailer($toEmail, $messageTitle, $messageBody);
	}


	public function sendRecommendedCounselingEmail($counselor, $id, $suggestedType)
	{
		$this->loginDetails();

    	$counselor_email = $this->counselingService->getCounselorEmail($counselor);

		 $applicant_name = NULL;
		 
		 if($suggestedType == 1){
			 $type = "Staff";
			$applicantDetails = $this->counselingService->getCounselingApplicant($tableName = 'employee_details', $id);
		 }
		 else if($suggestedType == 2){
			 $type = "Student";
			 $applicantDetails = $this->counselingService->getCounselingApplicant($tableName = 'student', $id);
		 }
	 	foreach($applicantDetails as $temp){
	 		$applicant_name = $temp['first_name'].' '.$temp['middle_name'].' '.$temp['last_name'];
	 	}

		$toEmail = $counselor_email;
		$messageTitle = "New Counseling Appointment Suggestion";
		$messageBody = "Dear Sir/Madam,<br>".$type." <b>".$applicant_name."</b> has been suggested for counseling on ".date('Y-m-d').".<br><b>Please click the link below for necessary action.</b><br><u>http://ims.rub.edu.bt</u><p>This is an auto-generated email and Please do not reply because this mail id is not monitored</p>";

		$this->emailService->sendMailer($toEmail, $messageTitle, $messageBody);
	}


    public function viewIndCounselingAppointmentAction()
    {
    	$this->loginDetails();

    	$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new CounselingAppointmentForm();
			$counselingModel = new CounselingAppointment();
			$form->bind($counselingModel);

			$counselingAppointmentDetails = $this->counselingService->findIndCounselingAppointmentDetails($id);

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
	        	'id' => $id,
	        	'form' => $form,
				'student_id' => $this->student_id,
				'employee_details_id' => $this->employee_details_id,
				'usertype' => $this->usertype,
				'organisation_id' => $this->organisation_id,
				'counselingAppointmentDetails' => $counselingAppointmentDetails,

				);
    	}else{
    		return $this->redirect()->toRoute('counselingappointment');
    	}
    }
	
	public function viewSeekingAppointmentListsAction()
	{
		$this->loginDetails();

		$message = NULL;

		$staffAppointmentLists = $this->counselingService->getStaffAppointmentList($status = 'Pending', $this->organisation_id, $this->employee_details_id);
		$stdAppointmentLists = $this->counselingService->getStdAppointmentList($status = 'Pending', $this->organisation_id, $this->employee_details_id);
		return new ViewModel(array(
			'staffAppointmentLists' => $staffAppointmentLists,
			'stdAppointmentLists' => $stdAppointmentLists,
			'keyphrase' => $this->keyphrase,
			'employee_details_id' => $this->employee_details_id,
			'message' => $message,
            ));
	}
	
	public function viewCounselingAppointmentDetailAction()
	{
		$this->loginDetails();
		//get the scheduled counseling appointments id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){		
			$form = new ScheduledAppointmentForm();
			$counselingModel = new ScheduledAppointment();
			$form->bind($counselingModel);

			$counselingType = $this->counselingService->getCounselingType($tableName = 'counseling_scheduled_appointments', $id);

			if($counselingType == 'Appointment'){
				$counselingDetail = $this->counselingService->findScheduledCounseling($tableName = 'counseling_appointment', $id);
				$applicantType = $this->counselingService->findCounselingApplicantType($tableName = 'counseling_appointment', $id);			
				$applicantDetails = $this->counselingService->findApplicantDetails($id, $applicantType);
			}

			else if($counselingType == 'Recommended'){
				$counselingDetail = $this->counselingService->findScheduledCounseling($tableName = 'counseling_suggest', $id);
				$applicantType = $this->counselingService->findCounselingApplicantType($tableName = 'counseling_suggest', $id);			
				$applicantDetails = $this->counselingService->findApplicantDetails($id, $applicantType);
			}
			
	        return array(
				'form' => $form,
				'counselingDetail' => $counselingDetail,
				'applicantDetails' => $applicantDetails,
				'counselingType' => $counselingType,
				'applicantType' => $applicantType,
			);
        }else{
        	$this->redirect()->toRoute('viewappointments');
        }
	}
    
	public function grantCounselingAppointmentAction()
    {
    	$this->loginDetails();
        //get the student id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new ScheduledAppointmentForm();
			$counselingModel = new ScheduledAppointment();
			$form->bind($counselingModel);

			$applicantType = $this->counselingService->getAppointmentApplicantType($tableName = 'counseling_appointment', $id);

			$applicantDetails = $this->counselingService->getAppointmentApplicantDetails($applicantType, $id);

			$counselingDetail = $this->counselingService->findCounseling($tableName = 'counseling_appointment', $id);

			$message = NULL;
			        
	        $request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             $data = $this->params()->fromPost();
	             $applicantId = $data['scheduledappointment']['applicant_id'];
	             $applicantType = $data['scheduledappointment']['applicant_type'];
	             $appointmentId = $data['scheduledappointment']['counseling_appointment_id'];
	             $scheduledTime = $data['scheduledappointment']['scheduled_time'];
	             $scheduledDate = $data['scheduledappointment']['scheduled_date'];
				 $counselingType = $data['scheduledappointment']['counseling_type'];
				 
				 $scheduled_date = date("Y-m-d", strtotime(substr($scheduledDate,0,10)));
	             $check_counselor_scheduled = $this->counselingService->crossCheckCounselorScheduled($scheduledTime, $scheduled_date, $this->employee_details_id);
	             if($check_counselor_scheduled){
	             	$message = 'Failure';
	             	$this->flashMessenger()->addMessage('You have already scheduled at '.'"'.$scheduledTime.'" '.' on' .' "'.$scheduled_date.'". '.' Please Scheduled different.');
	             }
	             else if($scheduled_date < date('Y-m-d')){
              		$message = 'Failure';
              		$this->flashMessenger()->addMessage("You can't grant an appointment since your scheduled date is less than current date. It should be current date or next date.");
				  }
              	  else if(($scheduled_date == date('Y-m-d'))&&($scheduledTime <= $this->ftime(time(),12))){
              		$message = 'Failure';
              		$this->flashMessenger()->addMessage("You can't grant an appointment since your scheduled time for today is less than or equal to current time. Schedule time should be greater than the current time.");
              	  }
	             else{
	             	if($applicantType == 1){
		             	if ($form->isValid()) {
			                 try {
								 $this->counselingService->grantAppointment($counselingModel, $appointmentId, $counselingType);
								 $this->notificationService->saveNotification('Grand Counseling Appointment', $applicantId, 'NULL', 'Staff Counseling');
			                     $this->auditTrailService->saveAuditTrail("INSERT", "Scheduled Counseling Appointments", "ALL", "SUCCESS");

			                     $this->flashMessenger()->addMessage('You have successfully grant counseling appointment');
								 return $this->redirect()->toRoute('viewappointments');
							 }
							 catch(\Exception $e) {
							 	$message = 'Failure';
							 	$this->flashMessenger()->addMessage($e->getMessage());
									 // Some DB Error happened, log it and let the user know
							 }
			             }
		             }
		             else if($applicantType == 2){
		             	if ($form->isValid()) {
			                 try {
								 $this->counselingService->grantAppointment($counselingModel, $appointmentId, $counselingType);
								 $this->notificationService->saveNotification('Grand Counseling Appointment', $applicantId, 'NULL', 'Student Counseling');
			                     $this->auditTrailService->saveAuditTrail("INSERT", "Scheduled Counseling Appointments", "ALL", "SUCCESS");

			                     $this->flashMessenger()->addMessage('You have successfully grant counseling appointment');
								 return $this->redirect()->toRoute('viewappointments');
							 }
							 catch(\Exception $e) {
									 $message = 'Failure';
							 		$this->flashMessenger()->addMessage($e->getMessage());
									 // Some DB Error happened, log it and let the user know
							 }
			             }
		             }
	             }
	         }
			 
	        return array(
				'counseling_appointment_id' => $id,
				'form' => $form,
				'applicantType' => $applicantType,
				'applicantDetails' => $applicantDetails,
				'counselingDetail' => $counselingDetail,
				'message' => $message,
				'employee_details_id' => $this->employee_details_id,
			);
        }else{
        	$this->redirect()->toRoute('viewappointments');
        }
    }
	
	public function viewCounselingAppointmentsAction()
    {
    	$this->loginDetails();

    	$message = NULL;

        $staffAppointmentLists = $this->counselingService->getStaffScheduledAppointmentList($tableName = 'counseling_scheduled_appointments', $this->employee_details_id);
        $stdAppointmentLists = $this->counselingService->getStdScheduledAppointmentList($tableName = 'counseling_scheduled_appointments', $this->employee_details_id);
		return new ViewModel(array(
			'staffAppointmentLists' => $staffAppointmentLists,
			'stdAppointmentLists' => $stdAppointmentLists,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
            ));
    }
	
	public function counselingRecordAction()
    {
    	$this->loginDetails();

    	$message = NULL;

    	 $stdAppointmentLists = $this->counselingService->findStdScheduledAppointmentList($tableName = 'counseling_scheduled_appointments', 'Pending', $this->employee_details_id);

        $staffAppointmentLists = $this->counselingService->findStaffScheduledAppointmentList($tableName = 'counseling_scheduled_appointments', 'Pending', $this->employee_details_id);

		return new ViewModel(array(
			'stdAppointmentLists' => $stdAppointmentLists,
			'staffAppointmentLists' => $staffAppointmentLists,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
			'cryptKey' => $this->cryptKey,
            ));
    }
	
	public function counselingNotesAction()
	{
		$this->loginDetails();
		//get the scheduled counseling appointment id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new CounselingNotesForm();
			$counselingModel = new CounselingNotes();
			$form->bind($counselingModel);

			$counselingType = $this->counselingService->getCounselingType($tableName = 'counseling_scheduled_appointments', $id);

			if($counselingType == 'Appointment'){
				$counselingDetail = $this->counselingService->findScheduledCounseling($tableName = 'counseling_appointment', $id);
				$applicantType = $this->counselingService->findCounselingApplicantType($tableName = 'counseling_appointment', $id);
				$applicantDetails = $this->counselingService->findApplicantDetails($id, $applicantType);
			}
			else if($counselingType == 'Recommended'){
				$counselingDetail = $this->counselingService->findScheduledCounseling($tableName = 'counseling_suggest', $id);
				$applicantType = $this->counselingService->findRecommendedCounselingType($tableName = 'counseling_suggest', $id);
				$applicantDetails = $this->counselingService->findRecommendedDetails($tableName = 'counseling_scheduled_appointments', $id, $applicantType);
			}
			
			//$counselingDetail = $this->counselingService->findScheduledCounseling($id);
			$message = NULL;

			$key = $this->cryptKey; 

			$request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost()); 
	             $data1 = $this->params()->fromPost(); 
	             $scheduledId = $data1['counselingnotes']['scheduled_counseling_id'];
	             $note = $data1['counselingnotes']['notes'];
				 
	             $notes = $this->encryptIt($note, $key);
				// echo $notes; die();
             	 $data = array_merge_recursive(
                 $request->getPost()->toArray(),
                 $request->getFiles()->toArray()
                 ); 
                $form->setData($data);
	             if ($form->isValid()) { //var_dump($form); die();
	                 try {
						 $this->counselingService->saveCounselingRecord($counselingModel, $notes, $scheduledId);
						 $this->auditTrailService->saveAuditTrail("INSERT", "Counseling Record", "ALL", "SUCCESS");

						 $this->flashMessenger()->addMessage('Counseling Record was successfully added');
						 return $this->redirect()->toRoute('listcounselingrecord');
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
				'scheduled_counseling_id' => $id,
				'counselingDetail' => $counselingDetail,
				'applicantType' => $applicantType,
				'applicantDetails' => $applicantDetails,
				'employee_details_id' => $this->employee_details_id,
				'counselingType' => $counselingType,
				'cryptKey' => $this->cryptKey,
				'message' => $message
			);
        }else{
        	$this->redirect()->toRoute('counselingrecord');
        }
	}
	
	public function counselingRecordListAction()
    {
    	$this->loginDetails();

    	$message = NULL;

        $stdCounselingRecordList = $this->counselingService->findStdCounselingRecordList($tableName = 'counseling_record', $this->employee_details_id);
        $staffCounselingRecordList = $this->counselingService->findStaffCounselingRecordList($tableName = 'counseling_record', $this->employee_details_id);
		return new ViewModel(array(
			'stdCounselingRecordList' => $stdCounselingRecordList,
			'staffCounselingRecordList' => $staffCounselingRecordList,
			'keyphrase' => $this->keyphrase,
			'cryptKey' => $this->cryptKey,
			'message' => $message,
            ));
    }
	
	public function counselingRecordDetailsAction()
    {
    	$this->loginDetails();
    	$message = NULL;
        //get the scheduled counseling appointment id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new CounselingNotesForm();
			$counselingModel = new CounselingNotes();
			$form->bind($counselingModel);

			$counselingType = $this->counselingService->getCounselingType($tableName = 'counseling_record', $id);
			
        	$counselingDetails = $this->counselingService->findCounselingRecordDetails($counselingType, $id);

			$applicantType = $this->counselingService->findCounselingApplicantType($tableName = 'counseling_record', $id);
			
			$applicantDetails = $this->counselingService->getApplicantDetails($id, $applicantType);

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
				'counselingDetails' => $counselingDetails,
				'applicantType' => $applicantType,
				'applicantDetails' => $applicantDetails,
				'counselingType' => $counselingType,
				'message' => $message,
				'cryptKey' => $this->cryptKey,
			);
        }else{
        	$this->redirect()->toRoute('listcounselingrecord');
        }
    }


    public function editCounselingRecordDetailsAction()
    {
    	$this->loginDetails();
    	$message = NULL;
        //get the scheduled counseling appointment id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new CounselingNotesForm();
			$counselingModel = new CounselingNotes();
			$form->bind($counselingModel);

			$counselingType = $this->counselingService->getCounselingType($tableName = 'counseling_record', $id);

			$counselingDetails = $this->counselingService->findCounselingRecordDetails($counselingType, $id);
        	$counselingRecordDetails = $this->counselingService->getCounselingRecordDetails($id);

			$applicantType = $this->counselingService->findCounselingApplicantType($tableName = 'counseling_record', $id);
			
			$applicantDetails = $this->counselingService->getApplicantDetails($id, $applicantType);

			$request = $this->getRequest();
	         if ($request->isPost()) {
				 $form->setData($request->getPost());
				 $data1 = $this->params()->fromPost();
	             $scheduledId = $data1['counselingnotes']['scheduled_counseling_id'];
				 $note = $data1['counselingnotes']['notes'];
				 
				 $notes = $this->encryptIt($note, $this->cryptKey);
	             $data = array_merge_recursive(
                 $request->getPost()->toArray(),
                 $request->getFiles()->toArray()
                 ); 
                $form->setData($data);
              	if ($form->isValid()) { 
	                 try {
	                 	$this->counselingService->saveCounselingRecord($counselingModel, $notes, $scheduledId);
	                 	$this->auditTrailService->saveAuditTrail("UPDATE", "Counseling Record", "ALL", "SUCCESS");
	                 	$this->flashMessenger()->addMessage("You have successfully updated the counseling record");
	                 	return $this->redirect()->toRoute('listcounselingrecord');
						 
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
				'counselingRecordDetails' => $counselingRecordDetails,
				'applicantType' => $applicantType,
				'counselingType' => $counselingType,
				'applicantDetails' => $applicantDetails,
				'counselingDetails' => $counselingDetails,
				'cryptKey' => $this->cryptKey,
				'message' => $message,
			);
        }else{
        	$this->redirect()->toRoute('listcounselingrecord');
        }
    }


    public function downloadCounselingRecordedFileAction()
    {
    	$this->loginDetails();
        //get the student id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $file = $this->counselingService->getCounselingRecordFileName($id);
        
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
            $this->redirect()->toRoute('listcounselingrecord');
        }
    }
    
	public function editCounselingAppointmentsAction()
    {
    	$this->loginDetails();
        $form = new CounselingForm();
		$counselingModel = new Counseling();
		$form->bind($counselingModel);
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 var_dump($form);
				 die();
                 try {
					 $this->counselingService->save($counselingModel);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array('form' => $form);
    }
    
	
	public function searchCounselingAction()
    {
    	$this->loginDetails();
        $form = new CounselingSuggestForm();
		$counselingModel = new CounselingSuggest();
		$form->bind($counselingModel);
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 var_dump($form);
				 die();
                 try {
					 $this->counselingService->save($counselingModel);
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
	

	public function mc_encrypt($encrypt, $key){
		$encrypt = serialize($encrypt);
		$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);
		$key = pack('H*', $key);
		$mac = hash_hmac('sha256', $encrypt, substr(bin2hex($key), -32));
		$passcrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $encrypt.$mac, MCRYPT_MODE_CBC, $iv);
		$encoded = base64_encode($passcrypt).'|'.base64_encode($iv);
		return $encoded;
	}


	// Decrypt Function
	public function mc_decrypt($decrypt, $key){
		$decrypt = explode('|', $decrypt.'|');
		$decoded = base64_decode($decrypt[0]);
		$iv = base64_decode($decrypt[1]);
		if(strlen($iv)!==mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC)){ return false; }
		$key = pack('H*', $key);
		$decrypted = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $decoded, MCRYPT_MODE_CBC, $iv));
		$mac = substr($decrypted, -64);
		$decrypted = substr($decrypted, 0, -64);
		$calcmac = hash_hmac('sha256', $decrypted, substr(bin2hex($key), -32));
		if($calcmac!==$mac){ return false; }
		$decrypted = unserialize($decrypted);
		return $decrypted;
	}


    public function decryptIt($q, $key) 
    {
		list($encrypted_data, $iv) = explode('::', base64_decode($q), 2);
    	return openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, 0, $iv);
    	//$qDecoded = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $key ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $key ) ) ), "\0");
    	//return( $qDecoded );
	}

	public function encryptIt($q, $key) 
	{ 
		$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
		$encrypted = openssl_encrypt($q, 'aes-256-cbc', $key, 0, $iv);
		return base64_encode($encrypted . '::' . $iv);
		//$cryptKey  = 'RUB-IMS@counseling';
		//$qEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $key ), $q, MCRYPT_MODE_CBC, md5( md5( $key ) ) ) );
		//return( $qEncoded );
	}  


  public function ftime($time,$f) {
  	date_default_timezone_set("Asia/Thimphu");
    if (gettype($time)=='string')	
	  $time = strtotime($time);	 
  
    return ($f==24) ? date("h:i", $time) : date("h:i A", $time);	
  } 
}

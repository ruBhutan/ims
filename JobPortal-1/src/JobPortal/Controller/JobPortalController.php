<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace JobPortal\Controller;

use JobPortal\Service\JobPortalServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use JobPortal\Model\Awards;
use JobPortal\Model\PersonalDetails;
use JobPortal\Model\CommunityService;
use JobPortal\Model\Documents;
use JobPortal\Model\EducationDetails;
use JobPortal\Model\EmploymentDetails;
use JobPortal\Model\JobPortal;
use JobPortal\Model\LanguageSkills;
use JobPortal\Model\MembershipDetails;
use JobPortal\Model\PublicationDetails;
use JobPortal\Model\References;
use JobPortal\Model\TrainingDetails;
use JobPortal\Model\ApplicantMarks;
use JobPortal\Form\AwardForm;
use JobPortal\Form\MembershipForm;
use JobPortal\Form\CommunityServiceForm;
use JobPortal\Form\EducationForm;
use JobPortal\Form\PersonalDetailsForm;
use JobPortal\Form\PublicationsForm;
use JobPortal\Form\LanguageForm;
use JobPortal\Form\TrainingsForm;
use JobPortal\Form\ReferencesForm;
use JobPortal\Form\WorkExperienceForm;
use JobPortal\Form\DocumentsForm;
use JobPortal\Form\ApplicantMarksForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;
use Zend\Http\Response\Stream;
use Zend\Http\Headers;

/**
 * Description of IndexController
 *
 * @author Mendrel
 */
class JobPortalController extends AbstractActionController
{
    
	protected $jobService;
	protected $notificationService;
    protected $auditTrailService;
    protected $serviceLocator;
	protected $username;
	protected $userrole;
	protected $usertype;
	protected $userDetails;
	protected $userImage;
	protected $user_status_id;
	protected $job_applicant_id;
	protected $keyphrase = "RUB_IMS";
	
	public function __construct(JobPortalServiceInterface $jobService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->jobService = $jobService;
		$this->notificationService = $notificationService;
        $this->auditTrailService = $auditTrailService;
        $this->serviceLocator = $serviceLocator;
		 /*
         * To retrieve the user name from the session
        */
        //$user_session = new Container('user');
        //$this->username = $user_session->username;
		$authPlugin = $serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
		// Use service locator to get the authPlugin
		$this->username = $authPlugin['username'];
		$this->userrole = $authPlugin['role'];
		$this->userregion = $authPlugin['region'];
		$this->usertype = $authPlugin['user_type_id'];
		$this->user_status_id = $authPlugin['user_status_id'];

		$jobApplicantData = $this->jobService->getUserDetailsId($this->username, $this->usertype);
		foreach($jobApplicantData as $data){
			$this->job_applicant_id = $data['id'];
			$this->userDetails = $data['first_name'].' '.$data['middle_name'].' '.$data['last_name'];
		} 

		$this->userImage = $this->jobService->getUserImage($this->username, $this->usertype);	
	} 

	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }
	
	public function registrantPersonalDetailsAction()
	{	
		$this->loginDetails();
		
		$form = new PersonalDetailsForm($this->serviceLocator);
		$registrantModel = new PersonalDetails();
		$form->bind($registrantModel);
		
		$personalDetails = $this->jobService->listAll('job_applicant', $this->job_applicant_id);
		$applicantAddress = $this->jobService->getApplicantAddressDetails($this->job_applicant_id);

		$message = NULL;
				
		return new ViewModel(array(
			'form' => $form,
			'personalDetails' => $personalDetails,
			'applicantAddress' => $applicantAddress,
			'job_applicant_id' => $this->job_applicant_id,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
			//'user' => $user,
			));
	}
	
	public function editRegistrantPersonalDetailsAction()
	{
		$this->loginDetails();

		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new PersonalDetailsForm($this->serviceLocator);
			$registrantModel = new PersonalDetails();
			$form->bind($registrantModel);
			
			$personalDetails = $this->jobService->listAll('job_applicant', $id);
			$applicantAddress = $this->jobService->getApplicantAddressDetails($id);
			$maritial_status = $this->jobService->listSelectData('maritial_status', 'maritial_status', NULL);
			$gender = $this->jobService->listSelectData('gender', 'gender', NULL);
			$nationality = $this->jobService->listSelectData('nationality', 'nationality', NULL);
			//$country = $this->jobService->listSelectData('country', 'country', NULL);
					
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
	                 	$country = $this->getRequest()->getPost('country');
	                 	$dzongkhag = $this->getRequest()->getPost('dzongkhag');
	                 	$gewog = $this->getRequest()->getPost('gewog');
	                 	$village = $this->getRequest()->getPost('village');
						 $this->jobService->savePersonalDetails($registrantModel, $country, $dzongkhag, $gewog, $village);
						 $this->auditTrailService->saveAuditTrail("UPDATE", "Job Applicant", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage('Successfully edited your personal details');
						 return $this->redirect()->toRoute('registrantpersonaldetails');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
	             }
	         }
			
			return new ViewModel(array(
				'form' => $form,
				'personalDetails' => $personalDetails,
				'maritial_status' => $maritial_status,
				'gender' => $gender,
				'nationality' => $nationality,
				'applicantAddress' => $applicantAddress,
				'job_applicant_id' => $this->job_applicant_id,
				'username' => $this->username,
				));
        }else{
        	return $this->redirect()->toRoute('registrantpersonaldetails');
        }
	}


	public function downloadJobApplicantCIDAction()
	{
		//get the param from the view file
		$file_location = $this->params()->fromRoute('filename',0);
		
		//extract the training id and column name
		$column_name = preg_replace('!\d+!', '', $file_location);
		$column_name = rtrim($column_name,"_");
		preg_match_all('!\d+!',$file_location, $id);
		$file_id = implode(' ', $id[0]);
		//get the location of the file from the database		
		$fileArray = $this->jobService->getFileName($file_id, $column_name, $type='cid');
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

	
	public function registrantEducationDetailsAction()
	{
		$this->loginDetails();

		$form = new EducationForm();
		$registrantModel = new EducationDetails();
		$form->bind($registrantModel);
		
		$educationDetails = $this->jobService->listAll('job_applicant_education', $this->job_applicant_id);
		$country = $this->jobService->listSelectData('country', 'country', NULL);
		$studyLevel = $this->jobService->listSelectData('study_level', 'study_level', NULL);
		$fundingCategory = $this->jobService->listSelectData('funding_category', 'funding_type', NULL);

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
					 $this->jobService->saveEducationDetails($registrantModel);
					 $this->auditTrailService->saveAuditTrail('INSERT', 'Job Applicant Education', 'ALL', 'SUCCESS');
					 $this->flashMessenger()->addMessage('Successfully added your education details');
					 return $this->redirect()->toRoute('registranteducationdetails');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'country' => $country,
			'studyLevel' => $studyLevel,
			'fundingCategory' => $fundingCategory,
			'educationDetails' => $educationDetails,
			'job_applicant_id' => $this->job_applicant_id,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
			));
	}

	public function editRegistrantEducationDetailsAction()
	{
		$this->loginDetails();

		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){ 
        	$form = new EducationForm();
			$registrantModel = new EducationDetails();
			$form->bind($registrantModel);
			
			$educationDetails = $this->jobService->getRegistrantOtherDetails('job_applicant_education', $id);
			$country = $this->jobService->listSelectData('country', 'country', NULL);
			$studyLevel = $this->jobService->listSelectData('study_level', 'study_level', NULL);
			$fundingCategory = $this->jobService->listSelectData('funding_category', 'funding_type', NULL);

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
						 $this->jobService->updateEducationDetails($registrantModel);
						 $this->auditTrailService->saveAuditTrail('UPDATE', 'Job Applicant Education', 'ALL', 'SUCCESS');
						 $this->flashMessenger()->addMessage('Successfully edited your education details');
						 return $this->redirect()->toRoute('registranteducationdetails');
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
				'studyLevel' => $studyLevel,
				'fundingCategory' => $fundingCategory,
				'educationDetails' => $educationDetails,
				'job_applicant_id' => $this->job_applicant_id,
				'message' => $message,
				));
        }else{
        	return $this->redirect()->toRoute('registranteducationdetails');
        }
	}
	public function deleteRegistrantEducationDetailsAction()
	{
		$this->loginDetails();

		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

       // var_dump($id); die();

        try {
			 $this->jobService->deleteEducationDetails($id);
			 $this->auditTrailService->saveAuditTrail('UPDATE', 'Job Applicant Education', 'ALL', 'SUCCESS');
			 $this->flashMessenger()->addMessage('Successfully deleted your education details');
			 return $this->redirect()->toRoute('registranteducationdetails');
		 }
		 catch(\Exception $e) {
				 die($e->getMessage());
				 // Some DB Error happened, log it and let the user know
		 }
        return $this->redirect()->toRoute('registranteducationdetails'); 
	}


	public function downloadApplicantEducationFileAction()
	{
		//get the param from the view file
		$file_location = $this->params()->fromRoute('filename',0);
		
		//extract the training id and column name
		$column_name = preg_replace('!\d+!', '', $file_location);
		$column_name = rtrim($column_name,"_");
		preg_match_all('!\d+!',$file_location, $id);
		$file_id = implode(' ', $id[0]);
		//get the location of the file from the database		
		$fileArray = $this->jobService->getFileName($file_id, $column_name, $type='education');
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


	
	public function registrantTrainingDetailsAction()
	{
		$this->loginDetails();

		$form = new TrainingsForm();
		$registrantModel = new TrainingDetails();
		$form->bind($registrantModel);
		$trainingDetails = $this->jobService->listAll('job_applicant_training_details', $this->job_applicant_id);

		$country = $this->jobService->listSelectData('country', 'country', NULL);
		$fundingCategory = $this->jobService->listSelectData('funding_category', 'funding_type', NULL);

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
					 $this->jobService->saveTrainingDetails($registrantModel);
					 $this->auditTrailService->saveAuditTrail('INSERT', 'Job Applicant Training Details', 'ALL', 'SUCCESS');
					 $this->flashMessenger()->addMessage('Successfully added your training details');
					 return $this->redirect()->toRoute('registranttrainingdetails');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'country' => $country,
			'fundingCategory' => $fundingCategory,
			'trainingDetails' => $trainingDetails,
			'job_applicant_id' => $this->job_applicant_id,
			'message' => $message,
			'keyphrase' => $this->keyphrase,
			));
	}

	public function editRegistrantTrainingDetailsAction()
	{
		$this->loginDetails();

		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new TrainingsForm();
			$registrantModel = new TrainingDetails();
			$form->bind($registrantModel);

			$trainingDetails = $this->jobService->getRegistrantOtherDetails('job_applicant_training_details', $id);

			$country = $this->jobService->listSelectData('country', 'country', NULL);
			$fundingCategory = $this->jobService->listSelectData('funding_category', 'funding_type', NULL);

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
						 $this->jobService->updateTrainingDetails($registrantModel);
						 $this->auditTrailService->saveAuditTrail('UPDATE', 'Job Applicant Training Details', 'ALL', 'SUCCESS');
						 $this->flashMessenger()->addMessage('Successfully edited your training details');
						 return $this->redirect()->toRoute('registranttrainingdetails');
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
				'fundingCategory' => $fundingCategory,
				'trainingDetails' => $trainingDetails,
				'job_applicant_id' => $this->job_applicant_id,
				'message' => $message,
				'keyphrase' => $this->keyphrase,
				));
        }else{
        	return $this->redirect()->toRoute('registranttrainingdetails');
        }
	}

	public function downloadApplicantTrainingFileAction()
	{
		//get the param from the view file
		$file_location = $this->params()->fromRoute('filename',0);
		
		//extract the training id and column name
		$column_name = preg_replace('!\d+!', '', $file_location);
		$column_name = rtrim($column_name,"_");
		preg_match_all('!\d+!',$file_location, $id);
		$file_id = implode(' ', $id[0]);
		//get the location of the file from the database		
		$fileArray = $this->jobService->getFileName($file_id, $column_name, $type='training');
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
	
	public function registrantEmploymentRecordAction()
	{
		$this->loginDetails();

		$form = new WorkExperienceForm();
		$registrantModel = new EmploymentDetails();
		$form->bind($registrantModel);
		$employmentDetails = $this->jobService->listAll('job_applicant_employment_record', $this->job_applicant_id);

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
					 $this->jobService->saveEmploymentRecord($registrantModel);
					 $this->auditTrailService->saveAuditTrail('INSERT', 'Job Applicant Employment Record', 'ALL', 'SUCCESS');
						 $this->flashMessenger()->addMessage('Successfully added your employment record details');
					 return $this->redirect()->toRoute('registrantemploymentrecord');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'employmentDetails' => $employmentDetails,
			'job_applicant_id' => $this->job_applicant_id,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
			));
	}


	public function editRegistrantEmploymentRecordAction()
	{
		$this->loginDetails();
		
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new WorkExperienceForm();
			$registrantModel = new EmploymentDetails();
			$form->bind($registrantModel);
			$employmentDetails = $this->jobService->getRegistrantOtherDetails('job_applicant_employment_record', $id);

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
						 $this->jobService->updateEmploymentRecord($registrantModel);
						 $this->auditTrailService->saveAuditTrail('INSERT', 'Job Applicant Employment Record', 'ALL', 'SUCCESS');
							 $this->flashMessenger()->addMessage('Successfully added your employment record details');
						 return $this->redirect()->toRoute('registrantemploymentrecord');
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
				'employmentDetails' => $employmentDetails,
				'job_applicant_id' => $this->job_applicant_id,
				'keyphrase' => $this->keyphrase,
				'message' => $message,
				));
        }else{
        	return $this->redirect()->toRoute('registrantemploymentrecord');
        }		
	}


	public function downloadApplicantEmploymentFileAction()
	{
		//get the param from the view file
		$file_location = $this->params()->fromRoute('filename',0);
		
		//extract the training id and column name
		$column_name = preg_replace('!\d+!', '', $file_location);
		$column_name = rtrim($column_name,"_");
		preg_match_all('!\d+!',$file_location, $id);
		$file_id = implode(' ', $id[0]);
		//get the location of the file from the database		
		$fileArray = $this->jobService->getFileName($file_id, $column_name, $type='employment');
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

	
	public function registrantMembershipDetailsAction()
	{
		$this->loginDetails();

		$form = new MembershipForm();
		$registrantModel = new MembershipDetails();
		$form->bind($registrantModel);
		
		$membershipDetails = $this->jobService->listAll('job_applicant_memberships', $this->job_applicant_id);

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
					 $this->jobService->saveMembership($registrantModel);
					  $this->auditTrailService->saveAuditTrail('INSERT', 'Job Applicant Memberships Record', 'ALL', 'SUCCESS');
							 $this->flashMessenger()->addMessage('Successfully added your membership record details');
					 return $this->redirect()->toRoute('registrantmembershipdetails');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'membershipDetails' => $membershipDetails,
			'job_applicant_id' => $this->job_applicant_id,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
			));
	}


	public function editRegistrantMembershipDetailsAction()
	{
		$this->loginDetails();
		
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new MembershipForm();
			$registrantModel = new MembershipDetails();
			$form->bind($registrantModel);
			
			$membershipDetails = $this->jobService->getRegistrantOtherDetails('job_applicant_memberships', $id);

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
						 $this->jobService->updateMembership($registrantModel);
						  $this->auditTrailService->saveAuditTrail('UPDATE', 'Job Applicant Memberships Record', 'ALL', 'SUCCESS');
								 $this->flashMessenger()->addMessage('Successfully edited your membership record details');
						 return $this->redirect()->toRoute('registrantmembershipdetails');
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
				'membershipDetails' => $membershipDetails,
				'job_applicant_id' => $this->job_applicant_id,
				'keyphrase' => $this->keyphrase,
				'message' => $message,
				));
        }else{
        	 return $this->redirect()->toRoute('registrantmembershipdetails');
        }
	}


	public function downloadApplicantMembershipFileAction()
	{
		//get the param from the view file
		$file_location = $this->params()->fromRoute('filename',0);
		
		//extract the training id and column name
		$column_name = preg_replace('!\d+!', '', $file_location);
		$column_name = rtrim($column_name,"_");
		preg_match_all('!\d+!',$file_location, $id);
		$file_id = implode(' ', $id[0]);
		//get the location of the file from the database		
		$fileArray = $this->jobService->getFileName($file_id, $column_name, $type='membership');
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

	
	public function registrantCommunityServiceAction()
	{
		$this->loginDetails();

		$form = new CommunityServiceForm();
		$registrantModel = new CommunityService();
		$form->bind($registrantModel);
		
		$serviceDetails = $this->jobService->listAll('job_applicant_community_service', $this->job_applicant_id);

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
					 $this->jobService->saveCommunityService($registrantModel);
					 $this->auditTrailService->saveAuditTrail('INSERT', 'Job Applicant Community Service', 'ALL', 'SUCCESS');
					 $this->flashMessenger()->addMessage('Successfully added your commnunity service record details');
					 return $this->redirect()->toRoute('registrantcommunityservice');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'serviceDetails' => $serviceDetails,
			'job_applicant_id' => $this->job_applicant_id,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
			));
	}


	public function editRegistrantCommunityServiceAction()
	{
		$this->loginDetails();
		
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new CommunityServiceForm();
			$registrantModel = new CommunityService();
			$form->bind($registrantModel);
			
			$serviceDetails = $this->jobService->getRegistrantOtherDetails('job_applicant_community_service', $id);

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
						 $this->jobService->updateCommunityService($registrantModel);
						 $this->auditTrailService->saveAuditTrail('UPDATE', 'Job Applicant Community Service', 'ALL', 'SUCCESS');
						 $this->flashMessenger()->addMessage('Successfully edited your commnunity service record details');
						 return $this->redirect()->toRoute('registrantcommunityservice');
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
				'serviceDetails' => $serviceDetails,
				'job_applicant_id' => $this->job_applicant_id,
				'keyphrase' => $this->keyphrase,
				'message' => $message,
				));
        }else{
        	return $this->redirect()->toRoute('registrantcommunityservice');
        }
	}


	public function downloadApplicantCommunityServiceFileAction()
	{
		//get the param from the view file
		$file_location = $this->params()->fromRoute('filename',0);
		
		//extract the training id and column name
		$column_name = preg_replace('!\d+!', '', $file_location);
		$column_name = rtrim($column_name,"_");
		preg_match_all('!\d+!',$file_location, $id);
		$file_id = implode(' ', $id[0]);
		//get the location of the file from the database		
		$fileArray = $this->jobService->getFileName($file_id, $column_name, $type='community_service');
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

	
	public function registrantLanguageSkillsAction()
	{
		$this->loginDetails();

		$form = new LanguageForm();
		$registrantModel = new LanguageSkills();
		$form->bind($registrantModel);
		
		$languageDetails = $this->jobService->listAll('job_applicant_languages', $this->job_applicant_id);

		$message = NULL;
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->jobService->saveLanguageSkills($registrantModel);
					 $this->auditTrailService->saveAuditTrail('INSERT', 'Job Applicant Languages Service', 'ALL', 'SUCCESS');
						 $this->flashMessenger()->addMessage('Successfully added your languages record details');
					 return $this->redirect()->toRoute('registrantlanguageskills');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'languageDetails' => $languageDetails,
			'job_applicant_id' => $this->job_applicant_id,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
			));
	}


	public function editRegistrantLanguageSkillsAction()
	{
		$this->loginDetails();
		
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new LanguageForm();
			$registrantModel = new LanguageSkills();
			$form->bind($registrantModel);
			
			$languageDetails = $this->jobService->getRegistrantOtherDetails('job_applicant_languages', $id);

			$message = NULL;
					
			$request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) {
	                 try {
						 $this->jobService->saveLanguageSkills($registrantModel);
						 $this->auditTrailService->saveAuditTrail('UPDATE', 'Job Applicant Languages Service', 'ALL', 'SUCCESS');
							 $this->flashMessenger()->addMessage('Successfully edited your languages record details');
						 return $this->redirect()->toRoute('registrantlanguageskills');
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
				'languageDetails' => $languageDetails,
				'job_applicant_id' => $this->job_applicant_id,
				'keyphrase' => $this->keyphrase,
				'message' => $message,
				));
		}else{
		 	return $this->redirect()->toRoute('registrantlanguageskills');
		}
	}
	
	public function registrantPublicationDetailsAction()
	{
		$this->loginDetails();

		$form = new PublicationsForm();
		$registrantModel = new PublicationDetails();
		$form->bind($registrantModel);
		
		$publicationDetails = $this->jobService->listAll('job_applicant_research_details', $this->job_applicant_id);
		$researchTypes = $this->jobService->listSelectData('research_category' ,'research_category',NULL);

		$message = NULL;
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->jobService->savePublications($registrantModel);
					 $this->auditTrailService->saveAuditTrail('INSERT', 'Job Applicant Research Details', 'ALL', 'SUCCESS');
							 $this->flashMessenger()->addMessage('Successfully added your publication record details');
					 return $this->redirect()->toRoute('registrantpublicationdetails');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'publicationDetails' => $publicationDetails,
			'researchTypes' => $researchTypes,
			'job_applicant_id' => $this->job_applicant_id,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
			));
	}


	public function editRegistrantPublicationDetailsAction()
	{
		$this->loginDetails();
		
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new PublicationsForm();
			$registrantModel = new PublicationDetails();
			$form->bind($registrantModel);
			
			$publicationDetails = $this->jobService->getRegistrantOtherDetails('job_applicant_research_details', $id);
			$researchTypes = $this->jobService->listSelectData('research_category' ,'research_category',NULL);

			$message = NULL;
					
			$request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) {
	                 try {
						 $this->jobService->savePublications($registrantModel);
						 $this->auditTrailService->saveAuditTrail('UPDATE', 'Job Applicant Research Details', 'ALL', 'SUCCESS');
								 $this->flashMessenger()->addMessage('Successfully edited your publication record details');
						 return $this->redirect()->toRoute('registrantpublicationdetails');
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
				'publicationDetails' => $publicationDetails,
				'researchTypes' => $researchTypes,
				'job_applicant_id' => $this->job_applicant_id,
				'keyphrase' => $this->keyphrase,
				'message' => $message,
				));
        }else{
        	return $this->redirect()->toRoute('registrantpublicationdetails');
        }
	}
	
	public function registrantAwardsAction()
	{
		$this->loginDetails();

		$form = new AwardForm();
		$registrantModel = new Awards();
		$form->bind($registrantModel);
		
		$awardDetails = $this->jobService->listAll('job_applicant_awards', $this->job_applicant_id);

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
					 $this->jobService->saveAwards($registrantModel);
					 $this->auditTrailService->saveAuditTrail('INSERT', 'Job Applicant Awards', 'ALL', 'SUCCESS');
						 $this->flashMessenger()->addMessage('Successfully added your award record details');
					 return $this->redirect()->toRoute('registrantawards');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'awardDetails' => $awardDetails,
			'job_applicant_id' => $this->job_applicant_id,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
			));
	}


	public function editRegistrantAwardsAction()
	{
		$this->loginDetails();
		
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new AwardForm();
			$registrantModel = new Awards();
			$form->bind($registrantModel);
			
			$awardDetails = $this->jobService->getRegistrantOtherDetails('job_applicant_awards', $id);

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
						 $this->jobService->updateAwards($registrantModel);
						 $this->auditTrailService->saveAuditTrail('UPDATE', 'Job Applicant Awards', 'ALL', 'SUCCESS');
							 $this->flashMessenger()->addMessage('Successfully edited your award record details');
						 return $this->redirect()->toRoute('registrantawards');
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
				'awardDetails' => $awardDetails,
				'job_applicant_id' => $this->job_applicant_id,
				'keyphrase' => $this->keyphrase,
				'message' => $message,
				));
        }else{
        	return $this->redirect()->toRoute('registrantawards');
        }
	}

	public function downloadApplicantAwardFileAction()
	{
		//get the param from the view file
		$file_location = $this->params()->fromRoute('filename',0);
		
		//extract the training id and column name
		$column_name = preg_replace('!\d+!', '', $file_location);
		$column_name = rtrim($column_name,"_");
		preg_match_all('!\d+!',$file_location, $id);
		$file_id = implode(' ', $id[0]);
		//get the location of the file from the database		
		$fileArray = $this->jobService->getFileName($file_id, $column_name, $type='award');
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

	
	public function registrantReferencesAction()
	{
		$this->loginDetails();

		$form = new ReferencesForm();
		$registrantModel = new References();
		$form->bind($registrantModel);
		
		$referenceDetails = $this->jobService->listAll('job_applicant_references', $this->job_applicant_id);

		$message = NULL;
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->jobService->saveReferences($registrantModel);
					 $this->auditTrailService->saveAuditTrail('INSERT', 'Job Applicant References', 'ALL', 'SUCCESS');
							 $this->flashMessenger()->addMessage('Successfully added your references record details');
					 return $this->redirect()->toRoute('registrantreferences');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'referenceDetails' => $referenceDetails,
			'job_applicant_id' => $this->job_applicant_id,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
			));
	}

	public function editRegistrantReferencesAction()
	{
		$this->loginDetails();
		
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new ReferencesForm();
			$registrantModel = new References();
			$form->bind($registrantModel);
			
			$referenceDetails = $this->jobService->getRegistrantOtherDetails('job_applicant_references', $id);

			$message = NULL;
					
			$request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) {
	                 try {
						 $this->jobService->saveReferences($registrantModel);
						 $this->auditTrailService->saveAuditTrail('UPDATE', 'Job Applicant References', 'ALL', 'SUCCESS');
								 $this->flashMessenger()->addMessage('Successfully edited your references record details');
						 return $this->redirect()->toRoute('registrantreferences');
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
				'referenceDetails' => $referenceDetails,
				'job_applicant_id' => $this->job_applicant_id,
				'keyphrase' => $this->keyphrase,
				'message' => $message,
				));
        }else{
        	return $this->redirect()->toRoute('registrantreferences');
        }
	}

	public function addApplicantMarksAction()
	{
		$this->loginDetails();

    	$form = new ApplicantMarksForm();
		$registrantModel = new ApplicantMarks();
		$form->bind($registrantModel);
		
		$markDetails = $this->jobService->listAll('job_applicant_marks', $this->job_applicant_id);
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
             	$x_english = $data['applicantmarks']['x_english'];
             	$xll_english = $data['applicantmarks']['xll_english'];
             	$applicantEducation = $this->jobService->listApplicantStudyLevel('job_applicant_education', $this->job_applicant_id);
             	if($x_english != NULL && $xll_english != NULL){
             		if(!array_key_exists('4', $applicantEducation) && !array_key_exists('5', $applicantEducation)){
             			$message = 'Failure';
             			$this->flashMessenger()->addMessage("You haven't entered your class 10 and 12 education details. Please update to insert your class 10 and 12 marks");
             		}
             		else if(!array_key_exists('4', $applicantEducation) && array_key_exists('5', $applicantEducation)){
             			$message = 'Failure';
             			$this->flashMessenger()->addMessage("You haven't entered your class 10 education details. Please update to insert your class 10 marks");
             		}
             		else if(array_key_exists('4', $applicantEducation) && !array_key_exists('5', $applicantEducation)){
             			$message = 'Failure';
             			$this->flashMessenger()->addMessage("You haven't entered your class 12 education details. Please update to insert your class 12 marks");
             		}
             		else{
	             		try {
							$this->jobService->saveJobApplicantMarks($registrantModel);
							$this->auditTrailService->saveAuditTrail("UPDATE", "Job Applicant Marks", "ALL", "SUCCESS");
							$this->flashMessenger()->addMessage('Successfully updated your class 10 and 12 marks');
							return $this->redirect()->toRoute('addapplicantmarks');
							}
							catch(\Exception $e) {
							        die($e->getMessage());
									 // Some DB Error happened, log it and let the user know
							}
             		}
             	}
             	else if($x_english != NULL && $xll_english == NULL){
             		if(!array_key_exists('4', $applicantEducation)){
             			$message = 'Failure';
             			$this->flashMessenger()->addMessage("You haven't entered your class 10 education details. Please update to insert your class 10 marks");
             		}
             		else{
	             		try {
							$this->jobService->saveJobApplicantMarks($registrantModel);
							$this->auditTrailService->saveAuditTrail("UPDATE", "Job Applicant Marks", "ALL", "SUCCESS");
							$this->flashMessenger()->addMessage('Successfully updated your class 10 and 12 marks');
							return $this->redirect()->toRoute('addapplicantmarks');
							}
							catch(\Exception $e) {
							        die($e->getMessage());
									 // Some DB Error happened, log it and let the user know
							}
             		}
             	}
             	else if($x_english == NULL && $xll_english != NULL){
             		if(!array_key_exists('5', $applicantEducation)){
             			$message = 'Failure';
             			$this->flashMessenger()->addMessage("You haven't entered your class 12 education details. Please update to insert your class 12 marks");
             		}
             		else{
	             		try {
							$this->jobService->saveJobApplicantMarks($registrantModel);
							$this->auditTrailService->saveAuditTrail("UPDATE", "Job Applicant Marks", "ALL", "SUCCESS");
							$this->flashMessenger()->addMessage('Successfully updated your class 10 and 12 marks');
							return $this->redirect()->toRoute('addapplicantmarks');
							}
							catch(\Exception $e) {
							        die($e->getMessage());
									 // Some DB Error happened, log it and let the user know
							}
             		}
             	}
             	else if($x_english == NULL && $xll_english == NULL){
             		$message = 'Failure';
     				$this->flashMessenger()->addMessage("Please enter 10 or 12 marks and update marks");
             	}
             	else{
             		try {
						$this->jobService->saveJobApplicantMarks($registrantModel);
						$this->auditTrailService->saveAuditTrail("UPDATE", "Job Applicant Marks", "ALL", "SUCCESS");
						$this->flashMessenger()->addMessage('Successfully updated your class 10 and 12 marks');
						return $this->redirect()->toRoute('addapplicantmarks');
						}
						catch(\Exception $e) {
						        die($e->getMessage());
								 // Some DB Error happened, log it and let the user know
						}
             	}
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'markDetails' => $markDetails,
			'job_applicant_id' => $this->job_applicant_id,
			'username' => $this->username,
			'message' => $message,
			));
	}
	
	public function registrantDocumentsAction()
	{
		$form = new DocumentsForm();
		$registrantModel = new Documents();
		$form->bind($registrantModel);
		
		$documentDetails = $this->jobService->listAll('job_applicant_documents', $this->job_applicant_id);
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 var_dump($form);
				 die();
                 try {
					 $this->jobService->saveDocuments($registrantModel);
					 $this->redirect()->toRoute('registrantdocuments');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'documentDetails' => $documentDetails,
			'job_applicant_id' => $this->job_applicant_id
			));
	}


	//ajax for selecting country, dzongkhag, gewog and village
    
    public function ajaxJobApplicantDzongkhagAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        
        $sql       = "SELECT * FROM `dzongkhag` where `country_id`= '$parentValue'";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Select a Dzongkhag";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['dzongkhag_name'];
        }
        
        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }
    
    public function ajaxJobApplicantGewogAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        
        $sql       = "SELECT * FROM `gewog` where `dzongkhag_id`= '$parentValue'";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Select a Gewog";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['gewog_name'];
        }
        
        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }
    
    public function ajaxJobApplicantVillageAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        
        $sql       = "SELECT `t1`.*, `t2`.`gewog_name` AS `gewog_name` FROM `village` AS `t1` INNER JOIN `gewog` AS `t2` ON `t1`.`gewog_id` = `t2`.`id` WHERE `t2`.`id`='$parentValue'";

        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Select a Village";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['village_name'];
        }
        
        return new JsonModel([
            'data' => $selectTwoData
        ]);
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

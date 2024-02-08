<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace JobApplicant\Controller;

use JobApplicant\Service\JobApplicantServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Helper\AbstractHelper;
use JobApplicant\Form\JobApplicantForm;
use JobApplicant\Form\SearchForm;
use JobApplicant\Form\SelectedApplicantForm;
use JobApplicant\Form\JobApplicationForm;
use JobApplicant\Form\JobRegistrantForm;
use JobApplicant\Model\JobApplicant;
use JobApplicant\Model\JobRegistrant;
use JobApplicant\Model\JobApplication;
use JobApplicant\Model\SelectedApplicant;
use Zend\Session\Container;

/**
 * Description of IndexController
 *
 */
 
class JobApplicantController extends AbstractActionController
{
	protected $jobApplicantService;
	protected $notificationService;
    protected $auditTrailService;
	protected $serviceLocator;
	protected $username;
	protected $userrole;
	protected $usertype;
	protected $userregion;
	protected $userDetails;
	protected $userImage;
	protected $employee_details_id;
	protected $job_applicant_id;
	protected $organisation_id;
	protected $keyphrase = "RUB_IMS";
	
	public function __construct(JobApplicantServiceInterface $jobApplicantService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->jobApplicantService = $jobApplicantService;
		$this->notificationService = $notificationService;
        $this->auditTrailService = $auditTrailService;
		$this->serviceLocator = $serviceLocator;
		
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
		
		$empData = $this->jobApplicantService->getUserDetailsId($this->username, $tableName = 'employee_details');
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			$this->userDetails = $emp['first_name'].' '.$emp['middle_name'].' '.$emp['last_name'];
			}
		
		if($this->employee_details_id == NULL){
			$applicantData = $this->jobApplicantService->getUserDetailsId($this->username, $tableName = 'job_applicant');
			foreach($applicantData as $applicant){
				$this->job_applicant_id = $applicant['id'];
				$this->userDetails = $applicant['first_name'].' '.$applicant['middle_name'].' '.$applicant['last_name'];
			}
		} 
		
		//get the organisation id
		$organisationID = $this->jobApplicantService->getOrganisationId($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}
		
		//$this->userDetails = $this->jobApplicantService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->jobApplicantService->getUserImage($this->username, $this->usertype);
		
	}
	
	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }
    
	
	public function applicantApplyJobAction()
	{
		$this->loginDetails();
		
		//get the vacancy id
		$id_from_route = $this->params()->fromRoute('id');
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);

		$study_level_id = $this->params()->fromRoute('study_level');
		$minimum_study_level_id = $this->my_decrypt($study_level_id, $this->keyphrase);

		$applicantEducationLevel = $this->jobApplicantService->getApplicantEducationLevel($this->job_applicant_id);

		if(is_numeric($id)){ 
			$form = new JobApplicationForm();
			$vacancyModel = new JobApplication();
			$form->bind($vacancyModel);

			$vacancyDetail = $this->jobApplicantService->getVacancyDetail($id);
			$personalDetails = $this->jobApplicantService->getPersonalDetails($tableName = 'job_applicant', $this->job_applicant_id);
			$applicantAddress = $this->jobApplicantService->getApplicantAddressDetails($this->job_applicant_id);
			$referenceDetails = $this->jobApplicantService->getApplicantReferenceDetails($this->job_applicant_id);
			$positionTitle = $this->jobApplicantService->listSelectData($tableName='position_title', $columnName='position_title');

			$job_applicant_details = $this->jobApplicantService->getUserDetailsId($this->username, $tableName = 'job_applicant');

			$organisations = $this->jobApplicantService->listSelectData($tableName='organisation', $columnName='organisation_name');
			$gender = $this->jobApplicantService->listSelectData($tableName='gender', $columnName='gender');
			$maritalStatus = $this->jobApplicantService->listSelectData($tableName='maritial_status', $columnName='maritial_status');
			$presentJobDescription = $this->jobApplicantService->getPresentJobDescription($this->job_applicant_id);
			$employmentDetails = $this->jobApplicantService->getEmploymentDetails($this->job_applicant_id);
			$educationDetails = $this->jobApplicantService->getEducationDetails($this->job_applicant_id);
			$marksDetail = $this->jobApplicantService->getApplicantMarksDetail($this->job_applicant_id);
			$languageDetails = $this->jobApplicantService->getLanguageDetails($this->job_applicant_id);
			$trainingDetails = $this->jobApplicantService->getTrainingDetails($this->job_applicant_id);
			$researchDetails = $this->jobApplicantService->getResearchDetails($this->job_applicant_id);
			$communityServices = $this->jobApplicantService->getApplicantCommunityServices($this->job_applicant_id);
			$awardDetails = $this->jobApplicantService->getApplicantAwardDetail($this->job_applicant_id);
			$membershipDetails = $this->jobApplicantService->getApplicantMembershipDetail($this->job_applicant_id);
			
			//check if the applicant has applied or not
			$message = NULL;
			$application = $this->jobApplicantService->getJobApplication($this->employee_details_id, $this->job_applicant_id, $id);
			if($application){
				$message = "Failure";
				$this->flashMessenger()->addMessage('Cannot apply for a particular job more than once!');
			}else{
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
							 $this->jobApplicantService->saveJobApplication($vacancyModel);
							 $this->auditTrailService->saveAuditTrail('INSERT', 'Emp Job Application', 'ALL', 'SUCCESS');
							 $this->auditTrailService->saveAuditTrail('INSERT', 'Job Application Reference', 'ALL', 'SUCCESS');
							 $this->flashMessenger()->addMessage('Successfully applied for the job');
							 return $this->redirect()->toRoute('listvacancy');
						 }
						 catch(\Exception $e) {
								 die($e->getMessage());
								 // Some DB Error happened, log it and let the user know
						 }
					 }
				 }					
			}
			 
			return array(
				'id' => $id,
				'keyphrase' => $this->keyphrase,
				'form' => $form,
				'vacancy_announcements_id' => $id,
				'employee_details_id' => $this->employee_details_id,
				'job_applicant_id' => $this->job_applicant_id,
				'referenceDetails' => $referenceDetails,
				'job_applicant_details' => $job_applicant_details,
				'minimum_study_level_id' => $minimum_study_level_id,
				'applicantEducationLevel' => $applicantEducationLevel,
				'positionTitle' => $positionTitle,
				'organisations' => $organisations,
				'vacancyDetail' => $vacancyDetail,
				'personalDetails' => $personalDetails,
				'applicantAddress' => $applicantAddress,
				'gender' => $gender,
				'maritalStatus' => $maritalStatus,
				'presentJobDescription' => $presentJobDescription,
				'employmentDetails' => $employmentDetails,
				'educationDetails' => $educationDetails,
				'marksDetail' => $marksDetail,
				'languageDetails' => $languageDetails,
				'trainingDetails' => $trainingDetails,
				'researchDetails' => $researchDetails,
				'communityServices' => $communityServices,
				'awardDetails' => $awardDetails,
				'membershipDetails' => $membershipDetails,
				'message' => $message);
		} 
		else {
			return $this->redirect()->toRoute('listvacancy');
		}		
	}

	public function jobRegistrantDetailsAction()
	{
		$this->loginDetails();

		if($this->employee_details_id != NULL){
			$tableName = 'job_applicant';

			$registrantList = $this->jobApplicantService->getPersonalDetails($tableName, NULL);
		}

		$message = NULL;

		return new ViewModel(array(
			'keyphrase' => $this->keyphrase,
			'registrantList' => $registrantList,
			'message' => $message,
			));
	}

	public function editJobRegistrantDetailsAction()
    {
    	$this->loginDetails(); 

    	$editer_id = $this->employee_details_id;

		
        //get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);


        if(is_numeric($id)){
        	$form = new JobRegistrantForm();
			$JobRegistrantModel = new JobRegistrant();
			$form->bind($JobRegistrantModel);

			//Need to send value of the table name and columns
			$tableName = 'job_applicant';
			$registrantList = $this->jobApplicantService->getPersonalDetails($tableName, $id);

			$message = NULL;

	        $request = $this->getRequest();
	        if ($request->isPost()) { 
	        	$form->setData($request->getPost());
	             $data = array_merge_recursive(
	        		$request->getPost()->toArray()
	        	); 
				$form->setData($data);				
				if ($form->isValid()) {
					//var_dump($data); die();
	                 try {
						$this->jobApplicantService->saveJobRegistrantDetails($JobRegistrantModel, $registrantList);

	             		$this->auditTrailService->saveAuditTrail("EDIT", "Employee Task Category", "ALL", "SUCCESS");
	             		$this->flashMessenger()->addMessage('Job Registrant Email has been updated');
	             		return $this->redirect()->toRoute('jobregistrantdetails');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
	             }
	             else {
	             	var_dump($form); die();
	             }
	         }
			 
	        return array(
				'id' => $id,
				'form' => $form,
				'editer_id' => $editer_id,
				'registrantList' => $registrantList,
				'message' => $message);
        }
    }


		public function appliedJobApplicationStatusAction()
	{
		$this->loginDetails();

		if($this->employee_details_id != NULL){
			$job_applicant_id = $this->employee_details_id;
			$tableName = 'employee_details';
		}else{
			$job_applicant_id = $this->job_applicant_id;
			$tableName = 'job_applicant';
		}

		$applicantList = $this->jobApplicantService->getJobApplicationList($tableName, $job_applicant_id);

		$positionTitle = $this->jobApplicantService->listSelectData($tableName='position_title', $columnName='position_title');
		$empType = $this->jobApplicantService->listSelectData($tableName='employee_type', $columnName='employee_type');
		$organisations = $this->jobApplicantService->listSelectData($tableName='organisation', $columnName='organisation_name');

		$message = NULL;

		return new ViewModel(array(
			'keyphrase' => $this->keyphrase,
			'applicantList' => $applicantList,
			'positionTitle' => $positionTitle,
			'empType' => $empType,
			'organisations' => $organisations,
			'message' => $message,
			));
	}

	
	//the decrypt function
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

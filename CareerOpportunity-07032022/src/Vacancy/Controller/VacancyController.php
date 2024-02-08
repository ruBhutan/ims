<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Vacancy\Controller;

use Vacancy\Service\VacancyServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Helper\AbstractHelper;
use Vacancy\Form\VacancyForm;
use Vacancy\Form\SearchForm;
use Vacancy\Form\SelectedApplicantForm;
use Vacancy\Form\JobApplicationForm;
use Vacancy\Form\JobApplicantMarksForm;
use Vacancy\Model\Vacancy;
use Vacancy\Model\JobApplication;
use Vacancy\Model\SelectedApplicant;
use Vacancy\Model\JobApplicantMarks;
use Zend\Session\Container;
use DOMPDFModule\View\Model\PdfModel;

//AJAX
use Zend\Http\Response\Stream;
use Zend\Http\Headers;
use Zend\Paginator\Adapter\DbSelect;
use Zend\View\Model\JsonModel;
use Zend\Form\Element\Select;

/**
 * Description of IndexController
 *
 */
 
class VacancyController extends AbstractActionController
{
	protected $vacancyService;
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
	
	public function __construct(VacancyServiceInterface $vacancyService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->vacancyService = $vacancyService;
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
		
		$empData = $this->vacancyService->getUserDetailsId($this->username, $tableName = 'employee_details');
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			$this->userDetails = $emp['first_name'].' '.$emp['middle_name'].' '.$emp['last_name'];
			}
		
		if($this->employee_details_id == NULL){
			$applicantData = $this->vacancyService->getUserDetailsId($this->username, $tableName = 'job_applicant');
			foreach($applicantData as $applicant){
				$this->job_applicant_id = $applicant['id'];
				$this->userDetails = $applicant['first_name'].' '.$applicant['middle_name'].' '.$applicant['last_name'];
			}
		}
		
		//get the organisation id
		$organisationID = $this->vacancyService->getOrganisationId($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}
		
		//$this->userDetails = $this->vacancyService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->vacancyService->getUserImage($this->username, $this->usertype);
		
	}
	
	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);

        date_default_timezone_set('Asia/Dhaka');
    }
    
	//old function
	public function addJobVacancyAction()
    {
        $form = new VacancyForm();
		$vacancyModel = new Vacancy();
		$form->bind($vacancyModel);
		
		$vacancy = $this->vacancyService->listAll($tableName='vacancy_announcements', NULL, $this->organisation_id);
        
		$request = $this->getRequest();
        if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					// $this->vacancyService->saveBudgetProposal($vacancyModel);
					 return $this->redirect()->toRoute('listvacancy');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'vacancys' => $vacancys);
    } 
    
	//old function 
	public function updateJobVacancyAction()
    {
        $form = new VacancyForm();
		$vacancyModel = new Vacancy();
		$form->bind($vacancyModel);
		
		$empDetails = $this->vacancyService->findEmpDetails($this->username);
		$empDetails = $empDetails->toArray();
        
		$request = $this->getRequest();
        if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->vacancyService->saveBudgetProposal($vacancyModel);
					 return $this->redirect()->toRoute('listvacancy');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form);
    }
	
	public function listJobVacancyAction()
	{
		$this->loginDetails();

		
		
		
		$form = new SearchForm();
	    $vacancyList = $this->vacancyService->listAll($tableName = 'vacancy_announcements', 'Vacancy List', $this->organisation_id);
		$positionTitle = $this->vacancyService->listSelectData($tableName='position_title', $columnName='position_title', NULL);
		$positionLevel = $this->vacancyService->listSelectData($tableName='position_level', $columnName='position_level', NULL);
		$empType = $this->vacancyService->listSelectData($tableName='employee_type', $columnName='employee_type', NULL);
		$organisations = $this->vacancyService->listSelectData($tableName='organisation', $columnName='organisation_name', NULL);
		$workingAgency = $this->vacancyService->listSelectData($tableName='organisation', $columnName='organisation_name', $this->organisation_id);
		$studyLevel = $this->vacancyService->listSelectData($tableName='study_level', $columnName='study_level', NULL);

		$message = NULL;
	   
	    $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$empName = $this->getRequest()->getPost('employee_name');
				$empId = $this->getRequest()->getPost('emp_id');
				$department = $this->getRequest()->getPost('department');
				$employeeList = $this->vacancyService->getEmployeeList($empName, $empId, $department);
             }
         }
		 else {
			 $employeeList = array();
		 }
		
		return new ViewModel(array(
            'keyphrase' => $this->keyphrase,
			'form' => $form,
			'vacancyList' => $vacancyList,
			'organisations' => $organisations,
			'positionTitle' => $positionTitle,
			'positionLevel' => $positionLevel,
			'empType' => $empType,
			'message' => $message,
			'workingAgency' => $workingAgency,
			'studyLevel' => $studyLevel,
			'employee_details_id' => $this->employee_details_id,
			'job_applicant_id' => $this->job_applicant_id,
            ));
	}
	
	public function announcePlannedVacancyListAction()
	{
		$this->loginDetails();
		
		$message = NULL;
		
		return new ViewModel(array(
			'keyphrase' => $this->keyphrase,
			'approvedProposals' => $this->vacancyService->listAllProposals($this->organisation_id),
			'organisations' => $this->vacancyService->listSelectData($tableName='organisation', $columnName='organisation_name', $this->organisation_id),
			'positionTitle' => $this->vacancyService->listSelectData($tableName='position_title', $columnName='position_title', NULL),
			'positionCategory' => $this->vacancyService->listSelectData($tableName='position_category', $columnName='category', NULL),
			'empType' => $this->vacancyService->listSelectData($tableName='employee_type', $columnName='employee_type', NULL),
			'message' => $message
			));
	}
	
	public function applyJobAction()
	{
		$this->loginDetails();
		
		//get the vacancy id
		$id_from_route = $this->params()->fromRoute('id');
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);

		$study_level_id = $this->params()->fromRoute('study_level');
		$minimum_study_level_id = $this->my_decrypt($study_level_id, $this->keyphrase);

		$applicantEducationLevel = $this->vacancyService->getApplicantEducationLevel($this->employee_details_id);
		
		if(is_numeric($id)){
			$form = new JobApplicationForm();
			$vacancyModel = new JobApplication();
			$form->bind($vacancyModel);

			$vacancyDetail = $this->vacancyService->getVacancyDetail($id);
			$organisations = $this->vacancyService->listSelectData($tableName='organisation', $columnName='organisation_name', NULL);
			$personalDetails = $this->vacancyService->getPersonalDetails($tableName = 'employee_details', $this->employee_details_id);
			$gender = $this->vacancyService->listSelectData($tableName='gender', $columnName='gender', NULL);
			$maritalStatus = $this->vacancyService->listSelectData($tableName='maritial_status', $columnName='maritial_status', NULL);
			$country = $this->vacancyService->listSelectData($tableName='country', $columnName='country', NULL);
			$applicantAddress = $this->vacancyService->getApplicantAddressDetails($this->employee_details_id, $type = 'staff');

			$presentJobDescription = $this->vacancyService->getPresentJobDescription($table_name = 'employee_details', $this->employee_details_id);
			$employmentDetails = $this->vacancyService->getEmploymentDetails($tableName = 'employee_details', $this->employee_details_id);
			$educationDetails = $this->vacancyService->getEducationDetails($tableName='employee_details', $this->employee_details_id, $id);	
			//$marksDetail = $this->vacancyService->getApplicantMarksDetail(NULL, $this->job_applicant_id);
			$languageDetails = $this->vacancyService->getLanguageDetails($this->employee_details_id, $type = 'staff');
			$trainingDetails = $this->vacancyService->getTrainingDetails($tableName='employee_details', $this->employee_details_id);
			$researchDetails = $this->vacancyService->getResearchDetails($tableName='employee_details', $this->employee_details_id);		
			//check if the applicant has applied or not
			$message = NULL;
			$application = $this->vacancyService->getJobApplication($this->employee_details_id, $this->job_applicant_id, $id);
			if($application){
				$message = "Failure";
				$this->flashMessenger()->addMessage('Cannot apply for a particular job more than once!');
			}
					
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
					 	
						 $this->vacancyService->saveJobApplication($vacancyModel);
						 $this->auditTrailService->saveAuditTrail('INSERT', '', '', '');
						 $this->flashMessenger()->addMessage('Successfully applied for the job');
						 return $this->redirect()->toRoute('listvacancy');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
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
				'minimum_study_level_id' => $minimum_study_level_id,
				'applicantEducationLevel' => $applicantEducationLevel,
				'vacancyDetail' => $vacancyDetail,
				'personalDetails' => $personalDetails,
				'organisations' => $organisations,
				'gender' => $gender,
				'maritalStatus' => $maritalStatus,
				'country' => $country,
				'applicantAddress' => $applicantAddress,
				'presentJobDescription' => $presentJobDescription,
				'employmentDetails' => $employmentDetails,
				'educationDetails' => $educationDetails,
				//'marksDetail' => $marksDetail,
				'languageDetails' => $languageDetails,
				'trainingDetails' => $trainingDetails,
				'researchDetails' => $researchDetails,
				'message' => $message,
			);
		} 
		else {
			return $this->redirect()->toRoute('listvacancy');
		}
				
	}
	
	public function announcePlannedVacancyAction()
	{
		$this->loginDetails();
		
		//get the proposal id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new VacancyForm();
			$vacancyModel = new Vacancy();
			$form->bind($vacancyModel);
			
			$proposalDetail = $this->vacancyService->getProposalDetail($id);
			$positionCategory = $this->vacancyService->listSelectData($tableName='position_category', $columnName='category', NULL);
			$positionLevel = $this->vacancyService->listSelectData($tableName='position_level', $columnName='position_level', NULL);
			$positionTitle = $this->vacancyService->listSelectData($tableName='position_title', $columnName='position_title', NULL);
			$empType = $this->vacancyService->listSelectData($tableName='employee_type', $columnName='employee_type', NULL);
			$workingAgency = $this->vacancyService->listSelectData($tableName='organisation', $columnName='organisation_name', $this->organisation_id);

			$studyLevel = $this->vacancyService->listSelectData($tableName='study_level', $columnName='study_level', NULL);
			
			$message = NULL;
			
			$request = $this->getRequest();
			if ($request->isPost()) {
				 $form->setData($request->getPost());
				 $data = $this->params()->fromPost();
				 $advertisementDate = $data['vacancy']['date_of_advertisement'];
				 $submissionDate = $data['vacancy']['last_date_submission'];

				 if($advertisementDate >= $submissionDate){
				 	$message = 'Failure';
				 	$this->flashMessenger()->addMessage('You cannot announce the vacancy since your advertisement date is greater than or equal to last date of submission');
				 }else{
				 	if ($form->isValid()) {
						 try {
							 $this->vacancyService->saveVacancy($vacancyModel);
							 $this->flashMessenger()->addMessage('Job Vacancy has successfully been announced');
							 $this->notificationService->saveNotification('New Vacancy', 'ALL', 'ALL', 'New Vacancy Announcement');
							 $this->auditTrailService->saveAuditTrail("UPDATE", "Announce Planned Vacancy", "ALL", "SUCCESS");
							 return $this->redirect()->toRoute('listvacancy');
						 }
						 catch(\Exception $e) {
						 	$message = 'Failure';
						 	$this->flashMessenger()->addMessage($e->getMessage());
						 }
					 }
				 }
			 }
			 
			return array(
				'form' => $form,
				'organisation_id' => $this->organisation_id,
				'proposalDetail' => $proposalDetail,
				'positionCategory' => $positionCategory,
				'positionLevel' => $positionLevel,
				'positionTitle' => $positionTitle,
				'studyLevel' => $studyLevel,
				'empType' => $empType,
				'workingAgency' => $workingAgency,
				'message' => $message,
				'vacancyList' => $this->vacancyService->listAll($tableName = 'vacancy_announcements', 'Planned', $this->organisation_id),
				);
		} else {
			return $this->redirect()->toRoute('listvacancy');
		}

		
	}
	
	public function announceAdhocVacancyAction()
	{
		$this->loginDetails();
		
		$form = new VacancyForm();
		$vacancyModel = new Vacancy();
		$form->bind($vacancyModel);
		
		$positionCategory = $this->vacancyService->listSelectData($tableName='position_category', $columnName='category', NULL);
		$positionLevel = $this->vacancyService->listSelectData($tableName='position_level', $columnName='position_level', NULL);
		$positionTitle = $this->vacancyService->listSelectData($tableName='position_title', $columnName='position_title', NULL);
		$empType = $this->vacancyService->listSelectData($tableName='employee_type', $columnName='employee_type', NULL);
		$workingAgency = $this->vacancyService->listSelectData($tableName='organisation', $columnName='organisation_name', $this->organisation_id);
		$studyLevel = $this->vacancyService->listSelectData($tableName='study_level', $columnName='study_level', NULL);
		
		$message = NULL;
		
		$request = $this->getRequest();
        if ($request->isPost()) {
             $form->setData($request->getPost());
             $data = $this->params()->fromPost();
				 $advertisementDate = $data['vacancy']['date_of_advertisement'];
				 $submissionTime = $data['vacancy']['last_time_submission'];
				 $submissionDate = $data['vacancy']['last_date_submission'];

				 //if($advertisementDate >= $submissionDate){
				 	//$message = 'Failure';
				 	//$this->flashMessenger()->addMessage('You cannot announce the vacancy since your advertisement date is greater than or equal to last date of submission');
				// }else{
				 	if ($form->isValid()) {
		                 try {
							 $this->vacancyService->saveAdhocVacancy($vacancyModel);
							 $this->flashMessenger()->addMessage('Job Vacancy successfully announced');
							 $this->notificationService->saveNotification('New Vacancy', 'ALL', 'ALL', 'New Vacancy Announcement');
							 $this->auditTrailService->saveAuditTrail("INSERT", "Announce Adhoc Vacancy", "ALL", "SUCCESS");
							 return $this->redirect()->toRoute('announceadhocvacancy');
						 }
						 catch(\Exception $e) {
								 die($e->getMessage());
								 // Some DB Error happened, log it and let the user know
						 }
		             //}
				 }
         }
		 
        return array(
			'form' => $form,
			'organisation_id' => $this->organisation_id,
			'positionCategory' => $positionCategory,
			'positionLevel' => $positionLevel,
			'positionTitle' => $positionTitle,
			'empType' => $empType,
			'workingAgency' => $workingAgency,
			'studyLevel' => $studyLevel,
			'message' => $message,
			'keyphrase' => $this->keyphrase,
			'vacancyList' => $this->vacancyService->listAll($tableName = 'vacancy_announcements', 'Adhoc', $this->organisation_id),
			);
	}

	public function viewAnnouncedVacancyAction()
	{
		$this->loginDetails();
		$positionCategory = $this->vacancyService->listSelectData($tableName='position_category', $columnName='category', NULL);
		$positionLevel = $this->vacancyService->listSelectData($tableName='position_level', $columnName='position_level', NULL);
		$positionTitle = $this->vacancyService->listSelectData($tableName='position_title', $columnName='position_title', NULL);
		$empType = $this->vacancyService->listSelectData($tableName='employee_type', $columnName='employee_type', NULL);
		$workingAgency = $this->vacancyService->listSelectData($tableName='organisation', $columnName='organisation_name', $this->organisation_id);
		$studyLevel = $this->vacancyService->listSelectData($tableName='study_level', $columnName='study_level', NULL);
		
        return array(
        	'positionCategory' => $positionCategory,
			'positionLevel' => $positionLevel,
			'positionTitle' => $positionTitle,
			'empType' => $empType,
			'workingAgency' => $workingAgency,
			'studyLevel' => $studyLevel,
			'keyphrase' => $this->keyphrase,

			'vacancyList' => $this->vacancyService->listAll($tableName = 'vacancy_announcements', 'Lists', $this->organisation_id),
		);
	}

	public function closeAnnouncedAdhocVacancyAction()
	{
		$this->loginDetails();
		
		//get the vacancy id

		$id_from_route = $this->params()->fromRoute('id', 0);
		
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		//var_dump($id); die();
		$this->vacancyService->closeAdhocVacancy($id);
		$this->auditTrailService->saveAuditTrail("UPDATE", "Close Adhoc Vacancy", "ALL", "SUCCESS");
		return $this->redirect()->toRoute('viewannouncedvacancy');
	}


	public function editAnnouncedAdhocVacancyAction()
	{
		$this->loginDetails();
		
		//get the vacancy id

		$id_from_route = $this->params()->fromRoute('id', 0);
		
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		//var_dump($id); die();
		if(is_numeric($id)){ 
			$form = new VacancyForm();
			$vacancyModel = new Vacancy();
			$form->bind($vacancyModel);
			
			$vacancyDetail = $this->vacancyService->getVacancyDetail($id);
			$positionCategory = $this->vacancyService->listSelectData($tableName='position_category', $columnName='category', NULL);
			$positionLevel = $this->vacancyService->listSelectData($tableName='position_level', $columnName='position_level', NULL);
			$positionTitle = $this->vacancyService->listSelectData($tableName='position_title', $columnName='position_title', NULL);
			$empType = $this->vacancyService->listSelectData($tableName='employee_type', $columnName='employee_type', NULL);
			$workingAgency = $this->vacancyService->listSelectData($tableName='organisation', $columnName='organisation_name', $this->organisation_id);

			$studyLevel = $this->vacancyService->listSelectData($tableName='study_level', $columnName='study_level', NULL);
			
			$message = NULL;
			
			$request = $this->getRequest();
	        if ($request->isPost()) {
	             $form->setData($request->getPost());
	             $data = $this->params()->fromPost();
					 $advertisementDate = $data['vacancy']['date_of_advertisement'];
					 $submissionDate = $data['vacancy']['last_date_submission'];

					 if($advertisementDate >= $submissionDate){
					 	$message = 'Failure';
					 	$this->flashMessenger()->addMessage('You cannot announce the vacancy since your advertisement date is greater than or equal to last date of submission');
					 }else{
					 	if ($form->isValid()) {
			                 try {
								 $this->vacancyService->saveAdhocVacancy($vacancyModel);
								 $this->flashMessenger()->addMessage('Job Vacancy successfully edited');
								 $this->notificationService->saveNotification('New Vacancy', 'ALL', 'ALL', 'New Vacancy Announcement');
								 $this->auditTrailService->saveAuditTrail("UPDATE", "Announce Adhoc Vacancy", "ALL", "SUCCESS");
								 return $this->redirect()->toRoute('announceadhocvacancy');
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
				'form' => $form,
				'organisation_id' => $this->organisation_id,
				'positionCategory' => $positionCategory,
				'positionLevel' => $positionLevel,
				'positionTitle' => $positionTitle,
				'empType' => $empType,
				'workingAgency' => $workingAgency,
				'studyLevel' => $studyLevel,
				'message' => $message,
				'keyphrase' => $this->keyphrase,
				'vacancyDetail' => $vacancyDetail,
				);
		}
		else {
			return $this->redirect()->toRoute('announceadhocvacancy');
		}
	}


	    
	public function jobDetailsAction()
    {
        $this->loginDetails();
		
		//get the vacancy id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);

		//var_dump($id); die();
		
		if(is_numeric($id)){
			$form = new VacancyForm();
			$vacancyDetail = $this->vacancyService->getVacancyDetail($id);
			$positionCategory = $this->vacancyService->listSelectData($tableName='position_category', $columnName='category', NULL);
			$positionLevel = $this->vacancyService->listSelectData($tableName='position_level', $columnName='position_level', NULL);
			$positionTitle = $this->vacancyService->listSelectData($tableName='position_title', $columnName='position_title', NULL);
			$empType = $this->vacancyService->listSelectData($tableName='employee_type', $columnName='employee_type', NULL);
			$workingAgency = $this->vacancyService->listSelectData($tableName='organisation', $columnName='organisation_name', $this->organisation_id);
			$studyLevel = $this->vacancyService->listSelectData($tableName='study_level', $columnName='study_level', NULL);
			
			return array(
				'id' => $id,
				'form' => $form,
				'vacancyDetail' => $vacancyDetail,
				'positionCategory' => $positionCategory,
				'positionLevel' => $positionLevel,
				'positionTitle' => $positionTitle,
				'empType' => $empType,
				'workingAgency' => $workingAgency,
				'studyLevel' => $studyLevel,
			);
		}
		else {
			return $this->redirect()->toRoute('listvacancy');
		}
		
    }
	
	public function jobApplicantStatusAction()
	{
		$this->loginDetails();
		
		$form = new VacancyForm();
		
		$message = NULL;
		//For current Job application
		$jobApplicants = $this->vacancyService->listJobApplicants($type='in_service', $status='Submitted', $this->organisation_id);
		$shortlistedApplicants = $this->vacancyService->listJobApplicants($type='in_service', $status='Short Listed', $this->organisation_id);
		$selectedApplicants = $this->vacancyService->listJobApplicants($type='in_service', $status='Selected', $this->organisation_id);
		//for Non RUB job applicants
		$appliedCandidates = $this->vacancyService->listJobApplicants(NULL, $status='Submitted', $this->organisation_id);
		$shortlistedCandidates = $this->vacancyService->listJobApplicants(NULL, $status='Short Listed', $this->organisation_id);
		$selectedCandidates = $this->vacancyService->listJobApplicants(NULL, $status='Selected', $this->organisation_id);
		
        return array(
			'keyphrase' => $this->keyphrase,
			'form' => $form,
			//Current
			'jobApplicants' => $jobApplicants,
			'shortlistedApplicants' => $shortlistedApplicants,
			'selectedApplicants' => $selectedApplicants,
			'appliedCandidates' => $appliedCandidates,
			'shortlistedCandidates' => $shortlistedCandidates,
			'selectedCandidates' => $selectedCandidates,
			
			'message' => $message
			);
	}
	public function pastJobApplicantStatusAction()
	{
		$this->loginDetails();
		
		$form = new VacancyForm();
		
		$message = NULL;
		

		//For Past Job Application
		$pastJobApplicants = $this->vacancyService->pastListJobApplicants($type='in_service', $status='Submitted', $this->organisation_id);
		$pastShortlistedApplicants = $this->vacancyService->pastListJobApplicants($type='in_service', $status='Short Listed', $this->organisation_id);
		$pastSelectedApplicants = $this->vacancyService->pastListJobApplicants($type='in_service', $status='Selected', $this->organisation_id);
		//for Non RUB job applicants
		$pastAppliedCandidates = $this->vacancyService->pastListJobApplicants(NULL, $status='Submitted', $this->organisation_id);
		$pastShortlistedCandidates = $this->vacancyService->pastListJobApplicants(NULL, $status='Short Listed', $this->organisation_id);
		$pastSelectedCandidates = $this->vacancyService->pastListJobApplicants(NULL, $status='Selected', $this->organisation_id);



		
        return array(
			'keyphrase' => $this->keyphrase,
			'form' => $form,
			//Past
			'pastJobApplicants' => $pastJobApplicants,
			'pastShortlistedApplicants' => $pastShortlistedApplicants,
			'pastSelectedApplicants' => $pastSelectedApplicants,
			'pastAppliedCandidates' => $pastAppliedCandidates,
			'pastShortlistedCandidates' => $pastShortlistedCandidates,
			'pastSelectedCandidates' => $pastSelectedCandidates,
			
			'message' => $message
			);
	}
	
	public function viewJobApplicantDetailsAction()
	{
		$this->loginDetails();
		
		//get the application id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		//echo $id; die();
		
		if(is_numeric($id)){
			//get the employee details id OR job applicant id
			$job_applicant_details = $this->vacancyService->getJobApplicantDetail($id);
			if($job_applicant_details['job_applicant_id'] != NULL){
				$table_name = 'job_applicant';
				$job_applicant_id = $job_applicant_details['job_applicant_id'];
				//$applicant_type = "outsider";
			} else {
				$table_name = 'employee_details';
				$job_applicant_id = $job_applicant_details['employee_details_id'];
				$applicant_type = "in_service";
			}
			
			$form = new VacancyForm();
			
			$vacancyDetail = $this->vacancyService->getAppliedVacancyDetail($table_name, $id);
			$personalDetails = $this->vacancyService->getPersonalDetails($table_name, $job_applicant_id);
			$applicantAddress = $this->vacancyService->getApplicantAddressDetails($job_applicant_id, $type = 'job_applicant');
			$educationDetails = $this->vacancyService->getEducationDetails($table_name, $job_applicant_id,$id);
			$marksDetail = $this->vacancyService->getApplicantMarksDetail($table_name, $job_applicant_id,$id);
			$languageDetails = $this->vacancyService->getLanguageDetails($job_applicant_id, $type = 'job_applicant');
			$employmentDetails = $this->vacancyService->getEmploymentDetails($table_name, $job_applicant_id, $id);
			$trainingDetails = $this->vacancyService->getTrainingDetails($table_name, $job_applicant_id);
			$researchDetails = $this->vacancyService->getResearchDetails($table_name, $job_applicant_id);
			$referenceDetails = $this->vacancyService->getApplicantReferenceDetails($table_name, $job_applicant_id, $id);
			$presentJobDescription = $this->vacancyService->getPresentJobDescription($table_name, $job_applicant_id);
			$applicantPromotionDetails = $this->vacancyService->getApplicantPromotionDetails($table_name, $job_applicant_id);
			$payScale = $this->vacancyService->listAll('pay_scale', NULL, $this->organisation_id);

			//Calling function to download the uploaded documents
			$applicantDocuments = $this->vacancyService->getApplicantDocuments($id);
			$applicantAwardList = $this->vacancyService->getApplicantDocumentList($table_name, $job_applicant_id, $type = 'awards');
			$applicantServiceList = $this->vacancyService->getApplicantDocumentList($table_name, $job_applicant_id, $type = 'community_services');
			$applicantMemberList = $this->vacancyService->getApplicantDocumentList($table_name, $job_applicant_id, $type = 'membership');
			$applicantContributionList = $this->vacancyService->getApplicantDocumentList($table_name, $job_applicant_id, $type = 'contributions');
			$applicantDisciplinaryList = $this->vacancyService->getApplicantDocumentList($table_name, $job_applicant_id, $type = 'disciplinary');
			$applicantResponsibilityList = $this->vacancyService->getApplicantDocumentList($table_name, $job_applicant_id, $type = 'responsibility');
			
			$organisations = $this->vacancyService->listSelectData($tableName='organisation', $columnName='organisation_name', NULL);
			$gender = $this->vacancyService->listSelectData($tableName='gender', $columnName='gender', NULL);
			$maritalStatus = $this->vacancyService->listSelectData($tableName='maritial_status', $columnName='maritial_status', NULL);
			
			$communityServices = $this->vacancyService->getApplicantCommunityServices($job_applicant_id);
			$awardDetails = $this->vacancyService->getApplicantAwardDetail($job_applicant_id);
			$membershipDetails = $this->vacancyService->getApplicantMembershipDetail($job_applicant_id);
			
			
			return array(
				'id' => $id,
				'form' => $form,
				'organisations' => $organisations,
				'gender' => $gender,
				'maritalStatus' => $maritalStatus,
				'personalDetails' => $personalDetails,
				'applicantAddress' => $applicantAddress,
				'employmentDetails' => $employmentDetails,
				'educationDetails' => $educationDetails,
				'marksDetail' => $marksDetail,
				'languageDetails' => $languageDetails,
				'trainingDetails' => $trainingDetails,
				'researchDetails' => $researchDetails,
				'referenceDetails' => $referenceDetails,
				'presentJobDescription' => $presentJobDescription,
				'applicantPromotionDetails' => $applicantPromotionDetails,
				'payScale' => $payScale,
				'applicantDocuments' => $applicantDocuments,
				'applicantAwardList' => $applicantAwardList,
				'applicantServiceList' => $applicantServiceList,
				'applicantMemberList' => $applicantMemberList,
				'applicantContributionList' => $applicantContributionList,
				'applicantDisciplinaryList' => $applicantDisciplinaryList,
				'applicantResponsibilityList' => $applicantResponsibilityList,
				'employee_details_id' => $this->employee_details_id,
				'job_applicant_id' => $job_applicant_id,
				'vacancyDetail' => $vacancyDetail,
				//'applicant_type' => $applicant_type,
				'communityServices' => $communityServices,
				'awardDetails' => $awardDetails,
				'membershipDetails' => $membershipDetails,
				'keyphrase' => $this->keyphrase,
			);
		}
		else {
			return $this->redirect()->toRoute('jobapplicantstatus');	
		}
	}
	
	
	public function downloadUploadedDocumentsAction()
	{
		//get the param from the view file
		$this->loginDetails();
		
		//get the application id
		$file_id = $this->params()->fromRoute('id');
		//$file_id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		$column_name = $this->params()->fromRoute('column');
		//$column_name = $this->my_decrypt($column_id, $this->keyphrase);
		/*echo $file_id; echo '<br>';
		echo $column_name; die();*/
		/*$file_location = $this->params()->fromRoute('filename',0);
		
		//extract the training id and column name
		$column_name = preg_replace('!\d+!', '', $file_location);
		$column_name = rtrim($column_name,"_");
		preg_match_all('!\d+!',$file_location, $id);*/
		//$file_id = $id;
		//get the location of the file from the database	

		//get the employee details id OR job applicant id
		
		$job_applicant_details = $this->vacancyService->getJobApplicantDetail($file_id);
		//var_dump($file_id); die();
			if($job_applicant_details['job_applicant_id'] != NULL){
				$table_name = 'job_applicant';
				$job_applicant_id = $job_applicant_details['job_applicant_id'];
				//$applicant_type = "outsider";
			} else {
				$table_name = 'employee_details';
				$job_applicant_id = $job_applicant_details['employee_details_id'];
				$applicant_type = "in_service";
			}

		$fileArray = $this->vacancyService->getFileName($file_id, $column_name);
		$file;
		//var_dump($fileArray); die();
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
	
	//Function to download the whole job application form in pdf
	public function generateJobApplicationPdfAction()
	{
		$this->loginDetails();
         //get the id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
	echo $id; die();
        if(is_numeric($id)){
			//get the employee details id OR job applicant id
			$job_applicant_details = $this->vacancyService->getJobApplicantDetail($id);
			if($job_applicant_details['job_applicant_id'] != NULL){
				$table_name = 'job_applicant';
				$job_applicant_id = $job_applicant_details['job_applicant_id'];
				$applicant_type = "outsider";
			} else {
				$table_name = 'employee_details';
				$job_applicant_id = $job_applicant_details['employee_details_id'];
				$applicant_type = "in_service";
			}
			
			
            $pdf = new PdfModel();
            $pdf->setOption('fileName', 'jobApplication'); // Triggers PDF download, automatically appends ".pdf"
            $pdf->setOption('display', PdfModel::DISPLAY_ATTACHMENT);
            $pdf->setOption('paperSize', 'a4'); // Defaults to "8x11"
            $pdf->setOption('paperOrientation', 'landscape'); // Defaults to "portrait"


           
            //To set view variables
            $pdf->setVariables(array(
                'id' => $id,
		        'vacancyDetail' => $this->vacancyService->getAppliedVacancyDetail($table_name, $id),
				'personalDetails' => $this->vacancyService->getPersonalDetails($table_name, $job_applicant_id),
				'educationDetails' => $this->vacancyService->getEducationDetails($table_name, $job_applicant_id),
				'employmentDetails' => $this->vacancyService->getEmploymentDetails($table_name, $job_applicant_id),
				'trainingDetails' => $this->vacancyService->getTrainingDetails($table_name, $job_applicant_id),
				'researchDetails' => $this->vacancyService->getResearchDetails($table_name, $job_applicant_id),
				'referenceDetails' => $this->vacancyService->getApplicantReferenceDetails($table_name, $job_applicant_id, $id),
				'presentJobDescription' => $this->vacancyService->getPresentJobDescription($table_name, $job_applicant_id),
				'applicantPromotionDetails' => $this->vacancyService->getApplicantPromotionDetails($table_name, $job_applicant_id),
				'payScale' => $this->vacancyService->listAll('pay_scale', NULL, $this->organisation_id),
           ));

            return $pdf;
        }
        else{
            $this->redirect()->toRoute('jobapplicantstatus');
        }
	}
    
	public function selectJobApplicantAction()
	{
		$this->loginDetails();
		
		//get the application id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			try {
				 $this->vacancyService->updateJobApplication($id, $status='Selected');
				 $this->flashMessenger()->addMessage('Job Applicant was selected');
				 return $this->redirect()->toRoute('jobapplicantstatus');
			 }
			 catch(\Exception $e) {
					 die($e->getMessage());
					 // Some DB Error happened, log it and let the user know
			 }
		} else {
			return $this->redirect()->toRoute('jobapplicantstatus');
		}
	}
	
	public function shortlistJobApplicantAction()
	{
		$this->loginDetails();
		
		//get the application id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
					
		if(is_numeric($id)){
			try {
				 $this->vacancyService->updateJobApplication($id, $status='Short Listed');
				 $this->flashMessenger()->addMessage('Job Applicant was short listed');
				 return $this->redirect()->toRoute('jobapplicantstatus');
			 }
			 catch(\Exception $e) {
					 die($e->getMessage());
					 // Some DB Error happened, log it and let the user know
			 }
		} else {
			return $this->redirect()->toRoute('jobapplicantstatus');
		}
	}
	
	public function rejectJobApplicantAction()
	{
		$this->loginDetails();
		
		//get the application id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			try {
				 $this->vacancyService->updateJobApplication($id, $status='Rejected');
				 $this->flashMessenger()->addMessage('Job Applicant was rejected');
				 return $this->redirect()->toRoute('jobapplicantstatus');
			 }
			 catch(\Exception $e) {
					 die($e->getMessage());
					 // Some DB Error happened, log it and let the user know
			 }
		} else {
			return $this->redirect()->toRoute('jobapplicantstatus');
		}
	}
	
	public function updateSelectedApplicantDetailsAction()
	{
		$this->loginDetails();
		
		//get the application id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			//get the employee details id OR job applicant id
			$job_applicant_details = $this->vacancyService->getJobApplicantDetail($id);
			if($job_applicant_details['job_applicant_id'] != NULL){
				$table_name = 'job_applicant';
				$job_applicant_id = $job_applicant_details['job_applicant_id'];
			} else {
				$table_name = 'employee_details';
				$job_applicant_id = $job_applicant_details['employee_details_id'];
			}
			
			//$dbAdapter = $this->serviceLocator()->get('Zend\Db\Adapter\Adapter');
			$form = new SelectedApplicantForm($this->serviceLocator);

			$jobModel = new SelectedApplicant();
			$form->bind($jobModel);


			
			$personalDetails = $this->vacancyService->getPersonalDetails($table_name, $job_applicant_id);
			$vacancyDetail = $this->vacancyService->getAppliedVacancyDetail($table_name, $id);
			$positionLevels = $this->vacancyService->listSelectData('position_level', 'position_level', NULL);
			$positionCategory = $this->vacancyService->listSelectData('position_category', 'category', NULL);
			$religion = $this->vacancyService->listSelectData('religion', 'religion', NULL);
			$bloodGroup = $this->vacancyService->listSelectData('blood_group', 'blood_group', NULL);
			$employeeType = $this->vacancyService->listSelectData('employee_type', 'employee_type', NULL);
			$payScale = $this->vacancyService->listAll('pay_scale', NULL, $this->organisation_id); 
			
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
					$formData = $this->params()->fromPost();  
					try {
						 $this->vacancyService->updateJobApplicantDetails($table_name, $job_applicant_id, $formData, $jobModel);
						 $this->flashMessenger()->addMessage('Job Applicant was updated');
						 $this->notificationService->saveNotification('Update Selected Applicant', 'ALL', $job_applicant_details, 'Selected Candidate Update');
						 $this->auditTrailService->saveAuditTrail("UPDATE", "Update Selected Applicant", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('jobapplicantstatus');
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
				//'emp_job_applications_id' => $id,
				'personalDetails' => $personalDetails,
				'vacancyDetail' => $vacancyDetail,
				'positionLevels' => $positionLevels,
				'positionCategory' => $positionCategory,
				'religion' => $religion,
				'bloodGroup' => $bloodGroup,
				'employeeType' => $employeeType,
				'payScale' => $payScale
			);
		}
		else {
			return $this->redirect()->toRoute('jobapplicantstatus');
		}
		
	}
        
	//get the list of selected candidate for OVC to view and update
	
	public function listSelectedCandidatesAction()
	{
        $this->loginDetails();
		      
		$form = new VacancyForm();
		
		$message = NULL;
		
		$jobApplicants = $this->vacancyService->listRecruitedCandidates();
		
                return array(
			'keyphrase' => $this->keyphrase,
			'form' => $form,
			'jobApplicants' => $jobApplicants,
			'message' => $message
			);
        }
        
	//update and generate EMP ID by OVC for selected candidates
	
	public function updateSelectedCandidateAction()
	{
		$this->loginDetails();
		
		//get the application id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			//get the employee details id OR job applicant id
			$job_applicant_details = $this->vacancyService->getSelectedApplicantDetail($id);
			if($job_applicant_details['job_applicant_id'] != NULL){
				$table_name = 'job_applicant';
				$job_applicant_id = $job_applicant_details['job_applicant_id'];
			} else {
				$table_name = 'employee_details';
				$job_applicant_id = $job_applicant_details['employee_details_id'];
			}
					
			$recruitmentDetails = $this->vacancyService->getRecruitmentDetails($id);
			
			//$dbAdapter = $this->serviceLocator()->get('Zend\Db\Adapter\Adapter');
			$form = new SelectedApplicantForm($dbAdapter);
					$jobModel = new SelectedApplicant();
			$form->bind($jobModel);
			
			$personalDetails = $this->vacancyService->getPersonalDetails($table_name, $job_applicant_id);
			$vacancyDetail = $this->vacancyService->getAppliedVacancyDetail($table_name, $id);
			$positionLevels = $this->vacancyService->listSelectData('position_level', 'position_level', NULL);
			$positionCategory = $this->vacancyService->listSelectData('position_category', 'category', NULL);
					$payScale = $this->vacancyService->listAll('pay_scale', NULL, $this->organisation_id);
			
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
					 $formData = $this->params()->fromPost();
					 try {
						 $this->vacancyService->updateSelectedCandidateDetails($table_name, $job_applicant_id, $jobModel, $formData);
						 $this->flashMessenger()->addMessage('Job Applicant was updated');
						 return $this->redirect()->toRoute('jobapplicantstatus');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			 
			 return array(
				'form' => $form,
							'emp_job_applications_id' => $id,
				'personalDetails' => $personalDetails,
							'recruitmentDetails' => $recruitmentDetails,
				'vacancyDetail' => $vacancyDetail,
				'positionLevels' => $positionLevels,
				'positionCategory' => $positionCategory,
							'payScale' => $payScale
			);
		}
		else {
			return $this->redirect()->toRoute('jobapplicantstatus');
		}		
	}


	//Function to view applied job applicant marks
	public function viewAppliedJobApplicantMarksAction()
	{
		$this->loginDetails();

		$internalApplicant = array();
		$outsiderApplicant = array();
	
		$form = new SearchForm();

		$announcedVacancy = $this->vacancyService->listAnnouncedVacancy($this->organisation_id);

		$internalApplicant = $this->vacancyService->listAllAppliedApplicant($type = 'in_service', $this->organisation_id);
		$outsiderApplicant = $this->vacancyService->listAllAppliedApplicant($type = 'outsider', $this->organisation_id);
		$degreeMarks = $this->vacancyService->listAllApplicantDegreeMarks($type = 'degree');
		$masterDetail = $this->vacancyService->listAllApplicantDegreeMarks($type = 'master');
		$employmentDetails = $this->vacancyService->listAllApplicantDegreeMarks($type = 'employment');
		 //var_dump($employmentDetails); die();
		$message = NULL;

		$request = $this->getRequest();
		if($request->isPost()){
			$form->setData($request->getPost());
			if($form->isValid()){
				$position_title = $this->getRequest()->getPost('position_title'); 
				//echo $position_title; die();
				$internalApplicant = $this->vacancyService->listAppliedApplicants($type='in_service', $position_title, $this->organisation_id);
				//for Non RUB job applicants
				$outsiderApplicant = $this->vacancyService->listAppliedApplicants($type='outsider', $position_title, $this->organisation_id);

			}
		}		

        return array(
			'form' => $form,
			'announcedVacancy' => $announcedVacancy,
			'internalApplicant' => $internalApplicant,
			'outsiderApplicant' => $outsiderApplicant,
			'degreeMarks' => $degreeMarks,
			'masterDetail' => $masterDetail,
			'employmentDetails' => $employmentDetails,
			'keyphrase' => $this->keyphrase,
			'message' => $message
			);

	}


	public function editJobApplicantMarkDetailsAction()
	{
		$this->loginDetails();

		$applicant_id_from_route = $this->params()->fromRoute('applicant_id');
		$applicant_id = $this->my_decrypt($applicant_id_from_route, $this->keyphrase);

		$category_from_route = $this->params()->fromRoute('category');
		$category = $this->my_decrypt($category_from_route, $this->keyphrase);

    	$form = new JobApplicantMarksForm();
		$jobModel = new JobApplicantMarks();
		$form->bind($jobModel);
		
		$applicantDetails = $this->vacancyService->getApplicantDetail($applicant_id, $category);
		$markDetails = $this->vacancyService->getJobApplicantMarks($applicant_id, $category);
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
             	$x_english = $data['jobapplicantmarks']['x_english'];
				$xll_english = $data['jobapplicantmarks']['xll_english'];
				$job_applicant_id = $data['jobapplicantmarks']['job_applicant_id'];

				 $applicantEducation = $this->vacancyService->listApplicantStudyLevel('job_applicant_education', $job_applicant_id);
				 
             	if($x_english != NULL && $xll_english != NULL){
             		if(!array_key_exists('4', $applicantEducation) && !array_key_exists('5', $applicantEducation)){
             			$message = 'Failure';
             			$this->flashMessenger()->addMessage("Applicant haven't entered class 10 and 12 education details. Please update to insert your class 10 and 12 marks");
             		}
             		else if(!array_key_exists('4', $applicantEducation) && array_key_exists('5', $applicantEducation)){
             			$message = 'Failure';
             			$this->flashMessenger()->addMessage("Applicant haven't entered class 10 education details. Please update to insert your class 10 marks");
             		}
             		else if(array_key_exists('4', $applicantEducation) && !array_key_exists('5', $applicantEducation)){
             			$message = 'Failure';
             			$this->flashMessenger()->addMessage("Applicant haven't entered class 12 education details. Please update to insert your class 12 marks");
             		}
             		else{
	             		try {
							$this->vacancyService->saveJobApplicantMarks($jobModel);
							$this->auditTrailService->saveAuditTrail("UPDATE", "Job Applicant Marks", "ALL", "SUCCESS");
							$this->flashMessenger()->addMessage('Successfully updated Applicant class 10 and 12 marks');
							return $this->redirect()->toRoute('viewappliedapplicantmarks');
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
             			$this->flashMessenger()->addMessage("Applicant haven't entered class 10 education details. Please update to insert your class 10 marks");
             		}
             		else{
	             		try {
							$this->vacancyService->saveJobApplicantMarks($jobModel);
							$this->auditTrailService->saveAuditTrail("UPDATE", "Job Applicant Marks", "ALL", "SUCCESS");
							$this->flashMessenger()->addMessage('Successfully updated Applicant class 10 and 12 marks');
							return $this->redirect()->toRoute('viewappliedapplicantmarks');
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
             			$this->flashMessenger()->addMessage("Applicant haven't entered class 12 education details. Please update to insert your class 12 marks");
             		}
             		else{
	             		try {
							$this->vacancyService->saveJobApplicantMarks($jobModel);
							$this->auditTrailService->saveAuditTrail("UPDATE", "Job Applicant Marks", "ALL", "SUCCESS");
							$this->flashMessenger()->addMessage('Successfully updated Applicant class 10 and 12 marks');
							return $this->redirect()->toRoute('viewappliedapplicantmarks');
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
						$this->vacancyService->saveJobApplicantMarks($jobModel);
						$this->auditTrailService->saveAuditTrail("UPDATE", "Job Applicant Marks", "ALL", "SUCCESS");
						$this->flashMessenger()->addMessage('Successfully updated Applicant class 10 and 12 marks');
						return $this->redirect()->toRoute('viewappliedapplicantmarks');
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
			'applicantDetails' => $applicantDetails,
			//'job_applicant_id' => $this->job_applicant_id,
			'username' => $this->username,
			'message' => $message,
			));

	}


	public function exportApplicantToExcelAction()
	{
		$this->loginDetails();

		$jobApplicants = $this->vacancyService->listJobApplicants($type='in_service', $status='Pending', $this->organisation_id);
		$appliedCandidates = $this->vacancyService->listJobApplicants(NULL, $status='Pending', $this->organisation_id);

		$staffLatestEducation = $this->vacancyService->listJobApplicantsLatestEducation($type='in_service');

	    var_dump($staffLatestEducation); die();

		 

		/*$array1 = array();
		foreach($jobApplicants as $applicant){
			$array1[] = $applicant;
		}

		$array2 = array();
		foreach($appliedCandidates as $applicant){
			$array2[] = $applicant;
		}var_dump($array1); echo '<br>'; var_dump($array2); die();*/
		
		// I recommend constructor injection for all needed dependencies ;-)
        $this->phpExcelService = $this->serviceLocator->get('mvlabs.phpexcel.service');
        
        $objPHPExcel = $this->phpExcelService->createPHPExcelObject();
       /* $objPHPExcel->getProperties()->setCreator("Diego Drigani")
            ->setLastModifiedBy("Diego Drigani")
            ->setTitle("MvlabsPHPExcel Test Document")
            ->setSubject("MvlabsPHPExcel Test Document")
            ->setDescription("Test document for MvlabsPHPExcel, generated using Zend Framework 2 and PHPExcel.")
            ->setKeywords("office PHPExcel php zf2 mvlabs")
            ->setCategory("Test result file");*/
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Sl No.')
            ->setCellValue('B1', 'App. No.')
            ->setCellValue('C1', 'Name')
            ->setCellValue('D1', 'CID No.')
            ->setCellValue('E1', 'Contact No.')
            ->setCellValue('F1', 'Postition Applied for')
            ->setCellValue('G1', 'College applied for')
            ->setCellValue('H1', 'Qualification')
            ->setCellValue('I1', 'University')
            ->setCellValue('J1', 'ClassX (Eng + best 4)')
            ->setCellValue('K1', '15%')
            ->setCellValue('L1', 'Class XII (English + best 3)')
            ->setCellValue('M1', '25%')
            ->setCellValue('N1', 'Bachelors')
            ->setCellValue('O1', '60%')
            ->setCellValue('P1', 'Masters')
            ->setCellValue('Q1', 'Details of work experience (if any)')
            ->setCellValue('R1', 'Bonus for Masters (if applicable)')
            ->setCellValue('S1', 'Bonus for Experience')
            ->setCellValue('T1', 'Total')
            ->setCellValue('U1', 'Remarks')
            ->setCellValue('V1', 'Entered by');

         if(count($jobApplicants) > 0){
         	$i = 2;
         	foreach($jobApplicants as $applicant){
         		$objPHPExcel->setActiveSheetIndex()
			        ->setCellValue('A'.$i, $i-1)
			        ->setCellValue('B'.$i, $i-1)
			        ->setCellValue('C'.$i, $applicant['first_name'].' '.$applicant['middle_name'].' '.$applicant['last_name'])
			        ->setCellValue('D'.$i, $applicant['cid'])
			        ->setCellValue('E'.$i, $applicant['phone_no'])
			        ->setCellValue('F'.$i, $applicant['position_title'])
			        ->setCellValue('G'.$i, $applicant['organisation_name'])
			        ->setCellValue('V'.$i, 'IMS');
			        $i++;
         	}
         	foreach($appliedCandidates as $applicant){
         		$objPHPExcel->setActiveSheetIndex()
			        ->setCellValue('A'.$i, $i-1)
			        ->setCellValue('B'.$i, $i-1)
			        ->setCellValue('C'.$i, $applicant['first_name'].' '.$applicant['middle_name'].' '.$applicant['last_name'])
			        ->setCellValue('D'.$i, $applicant['cid'])
			        ->setCellValue('E'.$i, $applicant['contact_no'])
			        ->setCellValue('F'.$i, $applicant['position_title'])
			        ->setCellValue('G'.$i, $applicant['organisation_name'])
			        ->setCellValue('V'.$i, 'IMS');
			        $i++;
         	}
         }

       // $objPHPExcel->getActiveSheet()->setCellValue('A8',"Hello\nWorld");
        $objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(-1);
        $objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->setTitle('Mvlabs');
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = $this->phpExcelService->createWriter($objPHPExcel, 'Excel2007' );

        $response = $this->phpExcelService->createHttpResponse($objWriter, 200, [
            'Pragma' => 'public',
            'Cache-control' => 'must-revalidate, post-check=0, pre-check=0',
            'Cache-control' => 'private',
            'Expires' => '0000-00-00',
            'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
            'Content-Disposition' => 'attachment; filename=' . 'myTest.xlsx',
            ]);
    
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

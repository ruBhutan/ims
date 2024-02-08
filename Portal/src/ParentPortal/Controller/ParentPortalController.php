<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Portal\Controller;

use Zend\ServiceManager\ServiceLocatorInterface;
use ParentPortal\Service\ParentPortalServiceInterface;
use ParentPortal\Model\Awards;
use ParentPortal\Model\PersonalDetails;
use ParentPortal\Model\CommunityService;
use ParentPortal\Model\Documents;
use ParentPortal\Model\EducationDetails;
use ParentPortal\Model\EmploymentDetails;
use ParentPortal\Model\ParentPortal;
use ParentPortal\Model\LanguageSkills;
use ParentPortal\Model\MembershipDetails;
use ParentPortal\Model\PublicationDetails;
use ParentPortal\Model\References;
use ParentPortal\Model\TrainingDetails;
use ParentPortal\Form\AwardForm;
use ParentPortal\Form\MembershipForm;
use ParentPortal\Form\CommunityServiceForm;
use ParentPortal\Form\EducationForm;
use ParentPortal\Form\PersonalDetailsForm;
use ParentPortal\Form\PublicationsForm;
use ParentPortal\Form\LanguageForm;
use ParentPortal\Form\TrainingsForm;
use ParentPortal\Form\ReferencesForm;
use ParentPortal\Form\WorkExperienceForm;
use ParentPortal\Form\DocumentsForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;

/**
 * Description of IndexController
 *
 * @author Mendrel
 */
class ParentPortalController extends AbstractActionController
{
    
	protected $parentService;
	protected $username;
	protected $job_applicant_id;
	
	public function __construct(ParentPortalServiceInterface $parentService)
	{
		$this->jobService = $jobService;
		
		/*
		 * To retrieve the user name from the session
		*/
		$user_session = new Container('user');
        $this->username = $user_session->username;
		
		/*
		 * To retrieve the user name from the session
		*/
		$user_session = new Container('user');
        $this->username = $user_session->username;
		
		/*
		* Getting the employee_details_id related to username
		*/
		
		$applicant = $this->jobService->getUserDetailsId($this->username);
		foreach($applicant as $app){
			$this->job_applicant_id = $app['id'];
			}		
	}
	
	public function registrantPersonalDetailsAction()
	{
		$form = new PersonalDetailsForm();
		$registrantModel = new PersonalDetails();
		$form->bind($registrantModel);
		
		$personalDetails = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 var_dump($form);
				 die();
                 try {
					 $this->jobService->savePersonalDetails($registrantModel);
					 $this->redirect()->toRoute('addnewemployeedetails');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'personalDetails' => $personalDetails
			));
	}
	
	public function registrantEducationDetailsAction()
	{
		$form = new EducationForm();
		$registrantModel = new EducationDetails();
		$form->bind($registrantModel);
		$educationDetails = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 var_dump($form);
				 die();
                 try {
					 $this->jobService->saveEducationDetails($registrantModel);
					 $this->redirect()->toRoute('registranteducationdetails');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'educationDetails' => $educationDetails
			));
	}
	
	public function registrantTrainingDetailsAction()
	{
		$form = new TrainingsForm();
		$registrantModel = new TrainingDetails();
		$form->bind($registrantModel);
		$trainingDetails = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 var_dump($form);
				 die();
                 try {
					 $this->jobService->saveTrainingDetails($registrantModel);
					 $this->redirect()->toRoute('registranttrainingdetails');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'trainingDetails' => $trainingDetails
			));
	}
	
	public function registrantEmploymentRecordAction()
	{
		$form = new WorkExperienceForm();
		$registrantModel = new EmploymentDetails();
		$form->bind($registrantModel);
		$employmentDetails = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 var_dump($form);
				 die();
                 try {
					 $this->jobService->saveEmploymentRecord($registrantModel);
					 $this->redirect()->toRoute('registrantemploymentrecord');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'employmentDetails' => $employmentDetails
			));
	}
	
	public function registrantMembershipDetailsAction()
	{
		$form = new MembershipForm();
		$registrantModel = new MembershipDetails();
		$form->bind($registrantModel);
		
		$membershipDetails = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 var_dump($form);
				 die();
                 try {
					 $this->jobService->saveMembership($registrantModel);
					 $this->redirect()->toRoute('registrantmembershipdetails');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'membershipDetails' => $membershipDetails
			));
	}
	
	public function registrantCommunityServiceAction()
	{
		$form = new CommunityServiceForm();
		$registrantModel = new CommunityService();
		$form->bind($registrantModel);
		
		$serviceDetails = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 var_dump($form);
				 die();
                 try {
					 $this->jobService->saveCommunityService($registrantModel);
					 $this->redirect()->toRoute('registrantcommunityservice');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'serviceDetails' => $serviceDetails
			));
	}
	
	public function registrantLanguageSkillsAction()
	{
		$form = new LanguageForm();
		$registrantModel = new LanguageSkills();
		$form->bind($registrantModel);
		
		$languageDetails = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 var_dump($form);
				 die();
                 try {
					 $this->jobService->saveLanguageSkills($registrantModel);
					 $this->redirect()->toRoute('registrantlanguageskills');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'languageDetails' => $languageDetails
			));
	}
	
	public function registrantPublicationDetailsAction()
	{
		$form = new PublicationsForm();
		$registrantModel = new PublicationDetails();
		$form->bind($registrantModel);
		
		$publicationDetails = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 var_dump($form);
				 die();
                 try {
					 $this->jobService->savePublications($registrantModel);
					 $this->redirect()->toRoute('registrantpublicationdetails');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'publicationDetails' => $publicationDetails
			));
	}
	
	public function registrantAwardsAction()
	{
		$form = new AwardForm();
		$registrantModel = new Awards();
		$form->bind($registrantModel);
		
		$awardDetails = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 var_dump($form);
				 die();
                 try {
					 $this->jobService->saveAwards($registrantModel);
					 $this->redirect()->toRoute('registrantawards');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'awardDetails' => $awardDetails
			));
	}
	
	public function registrantReferencesAction()
	{
		$form = new ReferencesForm();
		$registrantModel = new References();
		$form->bind($registrantModel);
		
		$referenceDetails = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 var_dump($form);
				 die();
                 try {
					 $this->jobService->saveReferences($registrantModel);
					 $this->redirect()->toRoute('registrantreferences');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'referenceDetails' => $referenceDetails
			));
	}
	
	public function registrantDocumentsAction()
	{
		$form = new DocumentsForm();
		$registrantModel = new Documents();
		$form->bind($registrantModel);
		
		$documentDetails = array();
				
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
			'documentDetails' => $documentDetails
			));
	}
    
}

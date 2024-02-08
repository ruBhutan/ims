<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace HrSettings\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use HrSettings\Service\HrSettingsServiceInterface;
use HrSettings\Model\EmploymentStatus;
use HrSettings\Model\MajorOccupationalGroup;
use HrSettings\Model\PayScale;
use HrSettings\Model\PositionCategory;
use HrSettings\Model\PositionLevel;
use HrSettings\Model\PositionTitle;
use HrSettings\Model\RentAllowance;
use HrSettings\Model\UniversityAllowance;
use HrSettings\Model\TeachingAllowance;
use HrSettings\Model\FundingCategory;
use HrSettings\Model\StudyLevelCategory;
use HrSettings\Model\ResearchCategory;
use HrSettings\Model\EmpAwardCategory;
use HrSettings\Model\EmpCommunityServiceCategory;
use HrSettings\Model\EmpContributionCategory;
use HrSettings\Model\EmpResponsibilityCategory;
use HrSettings\Form\EmploymentStatusForm;
use HrSettings\Form\MajorOccupationalGroupForm;
use HrSettings\Form\PayScaleForm;
use HrSettings\Form\StudyLevelCategoryForm;
use HrSettings\Form\ResearchCategoryForm;
use HrSettings\Form\FundingCategoryForm;
use HrSettings\Form\PositionCategoryForm;
use HrSettings\Form\PositionLevelForm;
use HrSettings\Form\PositionTitleForm;
use HrSettings\Form\RentAllowanceForm;
use HrSettings\Form\UniversityAllowanceForm;
use HrSettings\Form\TeachingAllowanceForm;
use HrSettings\Form\EmpAwardCategoryForm;
use HrSettings\Form\EmpCommunityServiceCategoryForm;
use HrSettings\Form\EmpContributionCategoryForm;
use HrSettings\Form\EmpResponsibilityCategoryForm;
use Zend\View\Model\ViewModel;

class HrSettingsController extends AbstractActionController
{
    protected $settingService;
	protected $notificationService;
	protected $auditTrailService;
	protected $serviceLocator;
	protected $username;
	protected $userrole;
	protected $usertype;
	protected $userregion;
	protected $userDetails;
	protected $userImage;
	protected $organisation_id;
	protected $keyphrase = "RUB_IMS";

	
	public function __construct(HrSettingsServiceInterface $settingService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->settingService = $settingService;
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

        //get the organisation id
        $organisationID = $this->settingService->getOrganisationId($this->username);
        foreach($organisationID as $organisation){
            $this->organisation_id = $organisation['organisation_id'];
        }
		
		//get the user details such as name
		//get the user details such as name
		$this->userDetails = $this->settingService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->settingService->getUserImage($this->username, $this->usertype);
	}
	
	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }
	
    public function jobAction()
    {
		$this->loginDetails();
		
        $employmentForm = new EmploymentStatusForm();
		$occupationForm = new MajorOccupationalGroupForm();
		$payForm = new PayScaleForm();
		$pCategoryForm = new PositionCategoryForm();
		$pLevelForm = new PositionLevelForm();
		$pTitleForm = new PositionTitleForm();
		$rentForm = new RentAllowanceForm();
		$universityForm = new UniversityAllowanceForm();
		$teachingForm = new TeachingAllowanceForm();
		
		$occupationalGroup = $this->settingService->listSelectData($tableName='major_occupational_group', $columnName='major_occupational_group');
		$positionCategory = $this->settingService->listSelectData($tableName='position_category', $columnName='category');
		$positionLevel = $this->settingService->listSelectData($tableName='position_level', $columnName='position_level');
		
		$message = NULL;
		
        return new ViewModel(array(
			'employmentForm' => $employmentForm,
			'occupationForm' => $occupationForm,
			'payForm' => $payForm,
			'pCategoryForm' => $pCategoryForm,
			'pLevelForm' => $pLevelForm,
			'pTitleForm' => $pTitleForm,
			'rentForm' => $rentForm,
			'universityForm' => $universityForm,
			'teachingForm' => $teachingForm,
			'position_title' => $this->settingService->listPositionTitle(),
			'position_category' => $this->settingService->listPositionCategory(),
			'position_level' => $this->settingService->listPositionLevel(),
			'teaching_allowance' => $this->settingService->listTeachingAllowance(),
			'housing_allowance' => $this->settingService->listRentAllowance(),
			'university_allowance' => $this->settingService->listUniversityAllowance(),
			'pay_scale' => $this->settingService->listPayScale(),
			'occupationalGroup' => $occupationalGroup,
			'positionCategory' => $positionCategory,
			'positionLevel' => $positionLevel,
			'keyphrase' => $this->keyphrase,
			'message' => $message
			));
    }
	
	public function otherConfigurationAction()
    {
		$this->loginDetails();
		
		$researchForm = new ResearchCategoryForm();
		$fundingForm = new fundingCategoryForm();
		$studyLevelForm = new StudyLevelCategoryForm();
		
		$message = NULL;
		
        return new ViewModel(array(
			'researchForm' => $researchForm,
			'fundingForm' => $fundingForm,
			'studyLevelForm' => $studyLevelForm,
			'study_level' => $this->settingService->listStudyLevel(),
			'funding' => $this->settingService->listFundingCategory(),
			'research_type' => $this->settingService->listResearchType(),
			'keyphrase' => $this->keyphrase,
			'message' => $message
			));
    }
	
	public function addResearchCategoryAction()
	{
		$this->loginDetails();
		
		$form = new ResearchCategoryForm();
		$employeeModel = new ResearchCategory();
		$form->bind($employeeModel);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->settingService->saveResearchCategory($employeeModel);
					 $this->flashMessenger()->addMessage('Research Category was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "New Research Category", "ALL", "SUCCESS");
					 return $this->redirect()->toRoute('otherconfig');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
	}
	
	public function addStudyLevelAction()
	{
		$this->loginDetails();
		
		$form = new StudyLevelCategoryForm();
		$employeeModel = new StudyLevelCategory();
		$form->bind($employeeModel);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->settingService->saveStudyLevel($employeeModel);
					 $this->flashMessenger()->addMessage('Study Level was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "New Research Category", "ALL", "SUCCESS");
					 return $this->redirect()->toRoute('otherconfig');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
	}
	
	public function addFundingAction()
	{
		$this->loginDetails();
		
		$form = new FundingCategoryForm();
		$employeeModel = new FundingCategory();
		$form->bind($employeeModel);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->settingService->saveFunding($employeeModel);
					 $this->flashMessenger()->addMessage('Funding Type was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "New Funding Type", "ALL", "SUCCESS");
					 return $this->redirect()->toRoute('otherconfig');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
	}
	
	public function addEmploymentStatusAction()
	{
		$this->loginDetails();
		
		$form = new EmploymentStatusForm();
		$employeeModel = new EmploymentStatus();
		$form->bind($employeeModel);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->settingService->saveEmploymentStatus($employeeModel);
					 $this->flashMessenger()->addMessage('Employee Status was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "New Employee Status", "ALL", "SUCCESS");
					 return $this->redirect()->toRoute('job');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
	}
	
	public function addOccupationalGroupAction()
	{
		$this->loginDetails();
		
		$form = new MajorOccupationalGroupForm();
		$groupModel = new MajorOccupationalGroup();
		$form->bind($groupModel);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->settingService->saveOccupationGroup($groupModel);
					 $this->flashMessenger()->addMessage('Occupational Group was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "New Occupational Group", "ALL", "SUCCESS");
					 return $this->redirect()->toRoute('job');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
	}
	
	public function addPayScaleAction()
	{
		$this->loginDetails();
		
		$form = new PayScaleForm();
		$payModel = new PayScale();
		$form->bind($payModel);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->settingService->savePayScale($payModel);
					 $this->flashMessenger()->addMessage('Pay Scale was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "New Pay Scale", "ALL", "SUCCESS");
					 return $this->redirect()->toRoute('job');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
	}
	
	public function addPositionCategoryAction()
	{
		$this->loginDetails();
		
		$form = new PositionCategoryForm();
		$categoryModel = new PositionCategory();
		$form->bind($categoryModel);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->settingService->savePositionCategory($categoryModel);
					 $this->flashMessenger()->addMessage('Position Category was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "New Position Category", "ALL", "SUCCESS");
					 return $this->redirect()->toRoute('job');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
	}
	
	public function addPositionLevelAction()
	{
		$this->loginDetails();
		
		$form = new PositionLevelForm();
		$positionModel = new PositionLevel();
		$form->bind($positionModel);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->settingService->savePositionLevel($positionModel);
					 $this->flashMessenger()->addMessage('Position Level was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "New Position Level", "ALL", "SUCCESS");
					 return $this->redirect()->toRoute('job');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
	}
	
	public function addPositionTitleAction()
	{
		$this->loginDetails();
		
		$form = new PositionTitleForm();
		$positionModel = new PositionTitle();
		$form->bind($positionModel);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->settingService->savePositionTitle($positionModel);
					 $this->flashMessenger()->addMessage('Position Title was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "New Position Title", "ALL", "SUCCESS");
					 return $this->redirect()->toRoute('job');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
	}
	
	public function addRentAllowanceAction()
	{
		$this->loginDetails();
		
		$form = new RentAllowanceForm();
		$allowanceModel = new RentAllowance();
		$form->bind($allowanceModel);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->settingService->saveRentAllowance($allowanceModel);
					 $this->flashMessenger()->addMessage('Rent Allowance was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "New Rent Allowance", "ALL", "SUCCESS");
					 return $this->redirect()->toRoute('job');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
	}


	public function addUniversityAllowanceAction()
	{
		$this->loginDetails();
		
		$form = new UniversityAllowanceForm;
		$allowanceModel = new UniversityAllowance();
		$form->bind($allowanceModel);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->settingService->saveUniversityAllowance($allowanceModel);
					 $this->flashMessenger()->addMessage('Professional Allowance was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "New University Allowance", "ALL", "SUCCESS");
					 return $this->redirect()->toRoute('job');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
	}

	
	public function addTeachingAllowanceAction()
	{
		$this->loginDetails();
		
		$form = new TeachingAllowanceForm();
		$allowanceModel = new TeachingAllowance();
		$form->bind($allowanceModel);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->settingService->saveTeachingAllowance($allowanceModel);
					 $this->flashMessenger()->addMessage('Teaching Allowance was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "New Teaching Allowance", "ALL", "SUCCESS");
					 return $this->redirect()->toRoute('job');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
	}
	
	public function editEmploymentStatusAction()
	{
		$this->loginDetails();
		
		//get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new EmploymentStatusForm();
			$employeeModel = new EmploymentStatus();
			$form->bind($employeeModel);
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
						 $this->settingService->saveEmploymentStatus($employeeModel);
						 $this->flashMessenger()->addMessage('Employee Status was successfully edited');
						 $this->auditTrailService->saveAuditTrail("EDIT", "Employee Status Edited", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('job');
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
		else {
			return $this->redirect()->toRoute('job');
		}
		
		
	}
	
	public function editOccupationalGroupAction()
	{
		$this->loginDetails();
		
		//get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new MajorOccupationalGroupForm();
			$groupModel = new MajorOccupationalGroup();
			$form->bind($groupModel);
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
						 $this->settingService->saveOccupationGroup($groupModel);
						 $this->flashMessenger()->addMessage('Occupational Group was successfully edited');
						 $this->auditTrailService->saveAuditTrail("EDIT", "Occupational Group Edited", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('job');
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
		else {
			return $this->redirect()->toRoute('job');
		}
		
	}
	
	public function editPayScaleAction()
	{
		$this->loginDetails();
		
		//get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new PayScaleForm();
			$payModel = new PayScale();
			$form->bind($payModel);
			
			$payDetails = $this->settingService->findHrSettings($id, $tableName = 'pay_scale');
			$pay_scale = $this->settingService->listPayScale();
			$positionLevel = $this->settingService->listSelectData($tableName='position_level', $columnName='position_level');
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
						 $this->settingService->savePayScale($payModel);
						 $this->flashMessenger()->addMessage('Pay Scale was successfully edited');
						 $this->auditTrailService->saveAuditTrail("EDIT", "Pay Scale Edited", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('job');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'form' => $form,
				'payDetails' => $payDetails,
				'pay_scale' => $pay_scale,
				'positionLevel' => $positionLevel
				));
		}
		else {
			return $this->redirect()->toRoute('job');
		}
		
	}
	
	public function editPositionCategoryAction()
	{
		$this->loginDetails();
		
		//get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new PositionCategoryForm();
			$categoryModel = new PositionCategory();
			$form->bind($categoryModel);
			
			$categoryDetails = $this->settingService->findHrSettings($id, $tableName = 'position_category');
			$position_category = $this->settingService->listPositionCategory();
			$occupationalGroup = $this->settingService->listSelectData($tableName='major_occupational_group', $columnName='major_occupational_group');
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
						 $this->settingService->savePositionCategory($categoryModel);
						 $this->flashMessenger()->addMessage('Position Category was successfully edited');
						 $this->auditTrailService->saveAuditTrail("EDIT", "Position Category Edited", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('job');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'form' => $form,
				'categoryDetails' => $categoryDetails,
				'occupationalGroup' => $occupationalGroup,
				'position_category' => $position_category
				));
		}
		else {
			return $this->redirect()->toRoute('job');
		}
		
	}
	
	public function editPositionLevelAction()
	{
		$this->loginDetails();
		
		//get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new PositionLevelForm();
			$positionModel = new PositionLevel();
			$form->bind($positionModel);
			
			$position_level = $this->settingService->listPositionLevel();
			$positionDetails = $this->settingService->findHrSettings($id, $tableName = 'position_level');
			$occupationalGroup = $this->settingService->listSelectData($tableName='major_occupational_group', $columnName='major_occupational_group');
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
						 $this->settingService->savePositionLevel($positionModel);
						 $this->flashMessenger()->addMessage('Position Level was successfully edited');
						 $this->auditTrailService->saveAuditTrail("EDIT", "Position Level Edited", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('job');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'form' => $form,
				'position_level' => $position_level,
				'positionDetails' => $positionDetails,
				'occupationalGroup' => $occupationalGroup
				));
		}
		else {
			return $this->redirect()->toRoute('job');
		}
		
	}
	
	public function editPositionTitleAction()
	{
		$this->loginDetails();
		
		//get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$position_title = $this->settingService->listPositionTitle();
			$positionDetails = $this->settingService->findHrSettings($id, $tableName = 'position_title');
			$positionCategory = $this->settingService->listSelectData($tableName='position_category', $columnName='category');
			
			$form = new PositionTitleForm();
			$positionModel = new PositionTitle();
			$form->bind($positionModel);
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
						 $this->settingService->savePositionTitle($positionModel);
						 $this->flashMessenger()->addMessage('Position Title was successfully edited');
						 $this->auditTrailService->saveAuditTrail("EDIT", "Position Title Edited", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('job');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'form' => $form,
				'positionDetails' => $positionDetails,
				'position_title' => $position_title,
				'positionCategory' => $positionCategory
				));
		}
		else {
			return $this->redirect()->toRoute('job');
		}
		
		
	}
	
	public function editRentAllowanceAction()
	{
		$this->loginDetails();
		
		//get the id
		//get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new RentAllowanceForm();
			$allowanceModel = new RentAllowance();
			$form->bind($allowanceModel);
			
			$allowanceDetails = $this->settingService->findHrSettings($id, $tableName = 'housing_allowance');
			$positionLevel = $this->settingService->listSelectData($tableName='position_level', $columnName='position_level');
			$housing_allowance = $this->settingService->listRentAllowance();
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
						 $this->settingService->saveRentAllowance($allowanceModel);
						 $this->flashMessenger()->addMessage('Rent Allowance was successfully edited');
						 $this->auditTrailService->saveAuditTrail("EDIT", "Rent Allowance Edited", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('job');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'form' => $form,
				'allowanceDetails' => $allowanceDetails,
				'positionLevel' => $positionLevel,
				'housing_allowance' => $housing_allowance
				));
		}
		else {
			return $this->redirect()->toRoute('job');
		}
		
	}


	public function editUniversityAllowanceAction()
	{
		$this->loginDetails();
		
		//get the id
		//get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new UniversityAllowanceForm();
			$allowanceModel = new UniversityAllowance();
			$form->bind($allowanceModel);
			
			$allowanceDetails = $this->settingService->findHrSettings($id, $tableName = 'professional_allowance');
			$positionLevel = $this->settingService->listSelectData($tableName='position_level', $columnName='position_level');
			$university_allowance = $this->settingService->listUniversityAllowance();
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
						 $this->settingService->saveUniversityAllowance($allowanceModel);
						 $this->flashMessenger()->addMessage('Professional Allowance was successfully edited');
						 $this->auditTrailService->saveAuditTrail("EDIT", "University Allowance Edited", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('job');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'form' => $form,
				'allowanceDetails' => $allowanceDetails,
				'positionLevel' => $positionLevel,
				'university_allowance' => $university_allowance
				));
		}
		else {
			return $this->redirect()->toRoute('job');
		}
		
	}

	
	public function editTeachingAllowanceAction()
	{
		$this->loginDetails();
		
		//get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new TeachingAllowanceForm();
			$allowanceModel = new TeachingAllowance();
			$form->bind($allowanceModel);
			
			$allowanceDetails = $this->settingService->findHrSettings($id, $tableName = 'teaching_allowance');
			$positionLevel = $this->settingService->listSelectData($tableName='position_level', $columnName='position_level');
			$teaching_allowance = $this->settingService->listTeachingAllowance();
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
						 $this->settingService->saveTeachingAllowance($allowanceModel);
						 $this->flashMessenger()->addMessage('Teaching Allowance was successfully edited');
						 $this->auditTrailService->saveAuditTrail("EDIT", "Teaching Allowance Edited", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('job');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'form' => $form,
				'allowanceDetails' => $allowanceDetails,
				'positionLevel' => $positionLevel,
				'teaching_allowance' => $teaching_allowance
				));
		}
		else {
			return $this->redirect()->toRoute('job');
		}
		
	}

	public function hrOtherSettingAction()
    {
    	$this->loginDetails();
		
		$awardCategoryForm = new EmpAwardCategoryForm();
		$csCategoryForm = new EmpCommunityServiceCategoryForm();
		$contributionCategoryForm = new EmpContributionCategoryForm();
		$responsibilityCategoryForm = new EmpResponsibilityCategoryForm();
		
		$message = NULL;
		
        return new ViewModel(array(
			'awardCategoryForm' => $awardCategoryForm,
			'csCategoryForm' => $csCategoryForm,
			'contributionCategoryForm' => $contributionCategoryForm,
			'responsibilityCategoryForm' => $responsibilityCategoryForm,
			'award_category' => $this->settingService->listAwardCategory($this->organisation_id),
			'cs_category' => $this->settingService->listCommunityServiceCategory($this->organisation_id),
			'contribution_category' => $this->settingService->listContributionCategory($this->organisation_id),
			'responsibility_category' => $this->settingService->listResponsibilityCategory($this->organisation_id),
			'keyphrase' => $this->keyphrase,
			'organisation_id' => $this->organisation_id,
			'message' => $message
			));	
    }


    public function addEmpAwardCategoryAction()
	{
		$this->loginDetails();
		
		$form = new EmpAwardCategoryForm();
		$otherCategoryModel = new EmpAwardCategory();
		$form->bind($otherCategoryModel);

		$message = NULL;
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->settingService->saveAwardCategory($otherCategoryModel);
					 $this->flashMessenger()->addMessage('Award Category was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "Emp Award Category", "ALL", "SUCCESS");
					 return $this->redirect()->toRoute('hrothersetting');
				 }
				 catch(\Exception $e) {
					die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
	}


	public function editEmpAwardCategoryAction()
	{
		$this->loginDetails();
		
		//get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new EmpAwardCategoryForm();
			$otherCategoryModel = new EmpAwardCategory();
			$form->bind($otherCategoryModel);
			
			$awardCategoryDetails = $this->settingService->findHrOtherSetting($id, $tableName = 'emp_award_category');
			$award_category = $this->settingService->listAwardCategory($this->organisation_id);

			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
						 $this->settingService->saveAwardCategory($otherCategoryModel);
						 $this->flashMessenger()->addMessage('Award Category was successfully edited');
						 $this->auditTrailService->saveAuditTrail("EDIT", "Emp Award Category", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('hrothersetting');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'form' => $form,
				'awardCategoryDetails' => $awardCategoryDetails,
				'award_category' => $award_category,
				'organisation_id' => $this->organisation_id,
				));
		}
		else {
			return $this->redirect()->toRoute('hrothersetting');
		}
	}


	public function addEmpCommunityServiceCategoryAction()
	{
		$this->loginDetails();
		
		$form = new EmpCommunityServiceCategoryForm();
		$otherCategoryModel = new EmpCommunityServiceCategory();
		$form->bind($otherCategoryModel);

		$message = NULL;
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->settingService->saveCommunityServiceCategory($otherCategoryModel);
					 $this->flashMessenger()->addMessage('Community Service Category was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "Emp Community Service Category", "ALL", "SUCCESS");
					 return $this->redirect()->toRoute('hrothersetting');
				 }
				 catch(\Exception $e) {
					die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
	}



	public function editEmpCommunityServiceCategoryAction()
	{
		$this->loginDetails();
		
		//get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new EmpCommunityServiceCategoryForm();
			$otherCategoryModel = new EmpCommunityServiceCategory();
			$form->bind($otherCategoryModel);
			
			$csCategoryDetails = $this->settingService->findHrOtherSetting($id, $tableName = 'emp_community_service_category');
			$cs_category = $this->settingService->listCommunityServiceCategory($this->organisation_id);

			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
						 $this->settingService->saveCommunityServiceCategory($otherCategoryModel);
						 $this->flashMessenger()->addMessage('Community Service Category was successfully edited');
						 $this->auditTrailService->saveAuditTrail("EDIT", "Emp Community Service Category", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('hrothersetting');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'form' => $form,
				'csCategoryDetails' => $csCategoryDetails,
				'cs_category' => $cs_category,
				'organisation_id' => $this->organisation_id,
				));
		}
		else {
			return $this->redirect()->toRoute('hrothersetting');
		}
	}



	public function addEmpContributionCategoryAction()
	{
		$this->loginDetails();
		
		$form = new EmpContributionCategoryForm();
		$otherCategoryModel = new EmpContributionCategory();
		$form->bind($otherCategoryModel);

		$message = NULL;
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->settingService->saveContributionCategory($otherCategoryModel);
					 $this->flashMessenger()->addMessage('Contribution Category was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "Emp Contribution Category", "ALL", "SUCCESS");
					 return $this->redirect()->toRoute('hrothersetting');
				 }
				 catch(\Exception $e) {
						die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
	}


	public function editEmpContributionCategoryAction()
	{
		$this->loginDetails();
		
		//get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new EmpContributionCategoryForm();
			$otherCategoryModel = new EmpContributionCategory();
			$form->bind($otherCategoryModel); 
			
			$contributionCategoryDetails = $this->settingService->findHrOtherSetting($id, $tableName = 'emp_contribution_category');
			$contribution_category = $this->settingService->listContributionCategory($this->organisation_id);

			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
						 $this->settingService->saveContributionCategory($otherCategoryModel);
						 $this->flashMessenger()->addMessage('Contribution Category was successfully edited');
						 $this->auditTrailService->saveAuditTrail("EDIT", "Emp Contribution Category", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('hrothersetting');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'form' => $form,
				'contributionCategoryDetails' => $contributionCategoryDetails,
				'contribution_category' => $contribution_category,
				'organisation_id' => $this->organisation_id,
				));
		}
		else {
			return $this->redirect()->toRoute('hrothersetting');
		}
	}



	public function addEmpResponsibilityCategoryAction()
	{
		$this->loginDetails();
		
		$form = new EmpResponsibilityCategoryForm();
		$otherCategoryModel = new EmpResponsibilityCategory();
		$form->bind($otherCategoryModel);

		$message = NULL;
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->settingService->saveResponsibilityCategory($otherCategoryModel);
					 $this->flashMessenger()->addMessage('Responsibility Category was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "Emp Responsibility Category", "ALL", "SUCCESS");
					 return $this->redirect()->toRoute('hrothersetting');
				 }
				 catch(\Exception $e) {
				 	die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
	}


	public function editEmpResponsibilityCategoryAction()
	{
		$this->loginDetails();
		
		//get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new EmpResponsibilityCategoryForm();
			$otherCategoryModel = new EmpResponsibilityCategory();
			$form->bind($otherCategoryModel); 
			
			$responsibilityCategoryDetails = $this->settingService->findHrOtherSetting($id, $tableName = 'emp_responsibility_category');
			$responsibility_category = $this->settingService->listResponsibilityCategory($this->organisation_id);

			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
						 $this->settingService->saveResponsibilityCategory($otherCategoryModel);
						 $this->flashMessenger()->addMessage('Responsibility Category was successfully edited');
						 $this->auditTrailService->saveAuditTrail("EDIT", "Emp Responsibility Category", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('hrothersetting');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'form' => $form,
				'responsibilityCategoryDetails' => $responsibilityCategoryDetails,
				'responsibility_category' => $responsibility_category,
				'organisation_id' => $this->organisation_id,
				));
		}
		else {
			return $this->redirect()->toRoute('hrothersetting');
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
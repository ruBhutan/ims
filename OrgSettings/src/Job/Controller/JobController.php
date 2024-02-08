<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Job\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Job\Service\JobServiceInterface;
use Job\Model\EmploymentStatus;
use Job\Model\MajorOccupationalGroup;
use Job\Model\PayScale;
use Job\Model\PositionCategory;
use Job\Model\PositionLevel;
use Job\Model\PositionTitle;
use Job\Model\RentAllowance;
use Job\Model\TeachingAllowance;
use Job\Model\FundingCategory;
use Job\Model\StudyLevelCategory;
use Job\Model\ResearchCategory;
use Job\Form\EmploymentStatusForm;
use Job\Form\MajorOccupationalGroupForm;
use Job\Form\PayScaleForm;
use Job\Form\StudyLevelCategoryForm;
use Job\Form\ResearchCategoryForm;
use Job\Form\FundingCategoryForm;
use Job\Form\PositionCategoryForm;
use Job\Form\PositionLevelForm;
use Job\Form\PositionTitleForm;
use Job\Form\RentAllowanceForm;
use Job\Form\TeachingAllowanceForm;
use Zend\View\Model\ViewModel;

class JobController extends AbstractActionController
{
    protected $jobService;
	protected $notificationService;
	protected $auditTrailService;
	protected $serviceLocator;
	protected $username;
	protected $userrole;
	protected $usertype;
	protected $userregion;
	protected $userDetails;
	
	public function __construct(JobServiceInterface $jobService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->jobService = $jobService;
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
	}
	
	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
    }
	
    public function jobAction()
    {
        $employmentForm = new EmploymentStatusForm();
		$occupationForm = new MajorOccupationalGroupForm();
		$payForm = new PayScaleForm();
		$pCategoryForm = new PositionCategoryForm();
		$pLevelForm = new PositionLevelForm();
		$pTitleForm = new PositionTitleForm();
		$rentForm = new RentAllowanceForm();
		$teachingForm = new TeachingAllowanceForm();
		
		$occupationalGroup = $this->jobService->listSelectData($tableName='major_occupational_group', $columnName='major_occupational_group');
		$positionCategory = $this->jobService->listSelectData($tableName='position_category', $columnName='category');
		$positionLevel = $this->jobService->listSelectData($tableName='position_level', $columnName='position_level');
		
		$message = NULL;
		
        return new ViewModel(array(
			'employmentForm' => $employmentForm,
			'occupationForm' => $occupationForm,
			'payForm' => $payForm,
			'pCategoryForm' => $pCategoryForm,
			'pLevelForm' => $pLevelForm,
			'pTitleForm' => $pTitleForm,
			'rentForm' => $rentForm,
			'teachingForm' => $teachingForm,
			'position_title' => $this->jobService->listPositionTitle(),
			'position_category' => $this->jobService->listPositionCategory(),
			'position_level' => $this->jobService->listPositionLevel(),
			'teaching_allowance' => $this->jobService->listTeachingAllowance(),
			'housing_allowance' => $this->jobService->listRentAllowance(),
			'pay_scale' => $this->jobService->listPayScale(),
			'occupationalGroup' => $occupationalGroup,
			'positionCategory' => $positionCategory,
			'positionLevel' => $positionLevel,
			'message' => $message
			));
    }
	
	public function otherConfigurationAction()
    {
		$researchForm = new ResearchCategoryForm();
		$fundingForm = new fundingCategoryForm();
		$studyLevelForm = new StudyLevelCategoryForm();
		
		$message = NULL;
		
        return new ViewModel(array(
			'researchForm' => $researchForm,
			'fundingForm' => $fundingForm,
			'studyLevelForm' => $studyLevelForm,
			'study_level' => $this->jobService->listStudyLevel(),
			'funding' => $this->jobService->listFundingCategory(),
			'research_type' => $this->jobService->listResearchType(),
			'message' => $message
			));
    }
	
	public function addResearchCategoryAction()
	{
		$form = new ResearchCategoryForm();
		$employeeModel = new ResearchCategory();
		$form->bind($employeeModel);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->jobService->saveResearchCategory($employeeModel);
					 $this->flashMessenger()->addMessage('Research Category was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "New Research Category", "ALL", "SUCCESS");
					 $this->redirect()->toRoute('otherconfig');
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
		$form = new StudyLevelCategoryForm();
		$employeeModel = new StudyLevelCategory();
		$form->bind($employeeModel);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->jobService->saveStudyLevel($employeeModel);
					 $this->flashMessenger()->addMessage('Study Level was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "New Research Category", "ALL", "SUCCESS");
					 $this->redirect()->toRoute('otherconfig');
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
		$form = new FundingCategoryForm();
		$employeeModel = new FundingCategory();
		$form->bind($employeeModel);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->jobService->saveFunding($employeeModel);
					 $this->flashMessenger()->addMessage('Funding Type was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "New Funding Type", "ALL", "SUCCESS");
					 $this->redirect()->toRoute('otherconfig');
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
		$form = new EmploymentStatusForm();
		$employeeModel = new EmploymentStatus();
		$form->bind($employeeModel);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->jobService->saveEmploymentStatus($employeeModel);
					 $this->flashMessenger()->addMessage('Employee Status was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "New Employee Status", "ALL", "SUCCESS");
					 $this->redirect()->toRoute('job');
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
		$form = new MajorOccupationalGroupForm();
		$groupModel = new MajorOccupationalGroup();
		$form->bind($groupModel);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->jobService->saveOccupationGroup($groupModel);
					 $this->flashMessenger()->addMessage('Occupational Group was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "New Occupational Group", "ALL", "SUCCESS");
					 $this->redirect()->toRoute('job');
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
		$form = new PayScaleForm();
		$payModel = new PayScale();
		$form->bind($payModel);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->jobService->savePayScale($payModel);
					 $this->flashMessenger()->addMessage('Pay Scale was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "New Pay Scale", "ALL", "SUCCESS");
					 $this->redirect()->toRoute('job');
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
		$form = new PositionCategoryForm();
		$categoryModel = new PositionCategory();
		$form->bind($categoryModel);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->jobService->savePositionCategory($categoryModel);
					 $this->flashMessenger()->addMessage('Position Category was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "New Position Category", "ALL", "SUCCESS");
					 $this->redirect()->toRoute('job');
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
		$form = new PositionLevelForm();
		$positionModel = new PositionLevel();
		$form->bind($positionModel);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->jobService->savePositionLevel($positionModel);
					 $this->flashMessenger()->addMessage('Position Level was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "New Position Level", "ALL", "SUCCESS");
					 $this->redirect()->toRoute('job');
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
		$form = new PositionTitleForm();
		$positionModel = new PositionTitle();
		$form->bind($positionModel);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->jobService->savePositionTitle($positionModel);
					 $this->flashMessenger()->addMessage('Position Title was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "New Position Title", "ALL", "SUCCESS");
					 $this->redirect()->toRoute('job');
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
		$form = new RentAllowanceForm();
		$allowanceModel = new RentAllowance();
		$form->bind($allowanceModel);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->jobService->saveRentAllowance($allowanceModel);
					 $this->flashMessenger()->addMessage('Rent Allowance was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "New Rent Allowance", "ALL", "SUCCESS");
					 $this->redirect()->toRoute('job');
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
		$form = new TeachingAllowanceForm();
		$allowanceModel = new TeachingAllowance();
		$form->bind($allowanceModel);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->jobService->saveTeachingAllowance($allowanceModel);
					 $this->flashMessenger()->addMessage('Teaching Allowance was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "New Teaching Allowance", "ALL", "SUCCESS");
					 $this->redirect()->toRoute('job');
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
		//get the id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$form = new EmploymentStatusForm();
		$employeeModel = new EmploymentStatus();
		$form->bind($employeeModel);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->jobService->saveEmploymentStatus($employeeModel);
					 $this->flashMessenger()->addMessage('Employee Status was successfully edited');
					 $this->auditTrailService->saveAuditTrail("EDIT", "Employee Status Edited", "ALL", "SUCCESS");
					 $this->redirect()->toRoute('job');
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
	
	public function editOccupationalGroupAction()
	{
		//get the id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$form = new MajorOccupationalGroupForm();
		$groupModel = new MajorOccupationalGroup();
		$form->bind($groupModel);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->jobService->saveOccupationGroup($groupModel);
					 $this->flashMessenger()->addMessage('Occupational Group was successfully edited');
					 $this->auditTrailService->saveAuditTrail("EDIT", "Occupational Group Edited", "ALL", "SUCCESS");
					 $this->redirect()->toRoute('job');
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
	
	public function editPayScaleAction()
	{
		//get the id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$form = new PayScaleForm();
		$payModel = new PayScale();
		$form->bind($payModel);
		
		$payDetails = $this->jobService->findJob($id, $tableName = 'pay_scale');
		$pay_scale = $this->jobService->listPayScale();
		$positionLevel = $this->jobService->listSelectData($tableName='position_level', $columnName='position_level');
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->jobService->savePayScale($payModel);
					 $this->flashMessenger()->addMessage('Pay Scale was successfully edited');
					 $this->auditTrailService->saveAuditTrail("EDIT", "Pay Scale Edited", "ALL", "SUCCESS");
					 $this->redirect()->toRoute('job');
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
	
	public function editPositionCategoryAction()
	{
		//get the id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$form = new PositionCategoryForm();
		$categoryModel = new PositionCategory();
		$form->bind($categoryModel);
		
		$categoryDetails = $this->jobService->findJob($id, $tableName = 'position_category');
		$position_category = $this->jobService->listPositionCategory();
		$occupationalGroup = $this->jobService->listSelectData($tableName='major_occupational_group', $columnName='major_occupational_group');
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->jobService->savePositionCategory($categoryModel);
					 $this->flashMessenger()->addMessage('Position Category was successfully edited');
					 $this->auditTrailService->saveAuditTrail("EDIT", "Position Category Edited", "ALL", "SUCCESS");
					 $this->redirect()->toRoute('job');
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
	
	public function editPositionLevelAction()
	{
		//get the id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$form = new PositionLevelForm();
		$positionModel = new PositionLevel();
		$form->bind($positionModel);
		
		$position_level = $this->jobService->listPositionLevel();
		$positionDetails = $this->jobService->findJob($id, $tableName = 'position_level');
		$occupationalGroup = $this->jobService->listSelectData($tableName='major_occupational_group', $columnName='major_occupational_group');
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->jobService->savePositionLevel($positionModel);
					 $this->flashMessenger()->addMessage('Position Level was successfully edited');
					 $this->auditTrailService->saveAuditTrail("EDIT", "Position Level Edited", "ALL", "SUCCESS");
					 $this->redirect()->toRoute('job');
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
	
	public function editPositionTitleAction()
	{
		//get the id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$position_title = $this->jobService->listPositionTitle();
		$positionDetails = $this->jobService->findJob($id, $tableName = 'position_title');
		$positionCategory = $this->jobService->listSelectData($tableName='position_category', $columnName='category');
		
		$form = new PositionTitleForm();
		$positionModel = new PositionTitle();
		$form->bind($positionModel);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->jobService->savePositionTitle($positionModel);
					 $this->flashMessenger()->addMessage('Position Title was successfully edited');
					 $this->auditTrailService->saveAuditTrail("EDIT", "Position Title Edited", "ALL", "SUCCESS");
					 $this->redirect()->toRoute('job');
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
	
	public function editRentAllowanceAction()
	{
		//get the id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$form = new RentAllowanceForm();
		$allowanceModel = new RentAllowance();
		$form->bind($allowanceModel);
		
		$allowanceDetails = $this->jobService->findJob($id, $tableName = 'housing_allowance');
		$positionLevel = $this->jobService->listSelectData($tableName='position_level', $columnName='position_level');
		$housing_allowance = $this->jobService->listRentAllowance();
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->jobService->saveRentAllowance($allowanceModel);
					 $this->flashMessenger()->addMessage('Rent Allowance was successfully edited');
					 $this->auditTrailService->saveAuditTrail("EDIT", "Rent Allowance Edited", "ALL", "SUCCESS");
					 $this->redirect()->toRoute('job');
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
	
	public function editTeachingAllowanceAction()
	{
		//get the id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$form = new TeachingAllowanceForm();
		$allowanceModel = new TeachingAllowance();
		$form->bind($allowanceModel);
		
		$allowanceDetails = $this->jobService->findJob($id, $tableName = 'teaching_allowance');
		$positionLevel = $this->jobService->listSelectData($tableName='position_level', $columnName='position_level');
		$teaching_allowance = $this->jobService->listTeachingAllowance();
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->jobService->saveTeachingAllowance($allowanceModel);
					 $this->flashMessenger()->addMessage('Teaching Allowance was successfully edited');
					 $this->auditTrailService->saveAuditTrail("EDIT", "Teaching Allowance Edited", "ALL", "SUCCESS");
					 $this->redirect()->toRoute('job');
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
    
}
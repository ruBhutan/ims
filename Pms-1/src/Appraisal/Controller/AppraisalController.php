<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Appraisal\Controller;

use Appraisal\Service\AppraisalServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Appraisal\Form\AppraisalForm;
use Appraisal\Form\AppraisalReviewForm;
use Appraisal\Form\AppraisalNominationForm;
use Appraisal\Form\NatureActivityForm;
use Appraisal\Form\AcademicWeightForm;
use Appraisal\Form\AcademicAppraisalForm;
use Appraisal\Form\AdministrativeAppraisalForm;
use Appraisal\Form\SubmitForm;
use Appraisal\Model\Appraisal;
use Appraisal\Model\NatureActivity;
use Appraisal\Model\AcademicWeight;
use Appraisal\Model\IwpObjectives;
use Appraisal\Model\AcademicAppraisal;
use Zend\Session\Container;

/**
 * Description of IndexController
 *
 */
 
class AppraisalController extends AbstractActionController
{
	protected $appraisalService;
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
	protected $organisation_id;
	protected $role;
	protected $occupational_group;
	protected $keyphrase = "RUB_IMS";
	
	public function __construct(AppraisalServiceInterface $appraisalService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->appraisalService = $appraisalService;
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
		
		$empData = $this->appraisalService->getUserDetailsId($this->username, $tableName = 'employee_details');
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			}

		//get the organisation id
		$organisationID = $this->appraisalService->getOrganisationId($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}
		
		//get the occupational group of the employee
		$occupationalGroup = $this->appraisalService->getOccupationalGroup($this->username);
		foreach($occupationalGroup as $group){
			$this->occupational_group = $group['major_occupational_group'];
		}
		
		//get the user details such as name
		$this->userDetails = $this->appraisalService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->appraisalService->getUserImage($this->username, $this->usertype);
	}
	
	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }
	
	public function administrativeAppraisalAction()
    {
		$this->loginDetails();
				
		$form = new AdministrativeAppraisalForm();
		$administrativeModel = new IwpObjectives();
		$form->bind($administrativeModel);
                
        $submitForm = new SubmitForm();
		
		//Need to send value of the table name and columns
		//Emp Ids is an array of Ids of Directors
		$objectivesSelect = $this->appraisalService->getSupervisorSuccessIndicators($this->employee_details_id);

		//Data to fill up the table
		$kpi = $this->appraisalService->listAdministrativeAppraisal($table = 'iwp_subactivities', $this->employee_details_id, $status='Not Submitted');
		
		//get the last date for IWP
		$iwp_deadline = $this->appraisalService->getIwpDeadline('IWP Submission');
		$appraisal_period = $this->appraisalService->getAppraisalPeriodYear('IWP Submission', $tableName = 'pms_activation_dates');

		$message = NULL;
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->appraisalService->saveAdministrativeAppraisal($administrativeModel);
					 $this->notificationService->saveNotification('Administrative Appraisal', 'ALL', 'ALL', 'Submission of Administrative Appraisal');
					 $this->auditTrailService->saveAuditTrail("INSERT", "Administrative Appraisal Added", "ALL", "SUCCESS");
					 $this->flashMessenger()->addMessage('You have successfully added appraisal for submission to your supervisor');
					 return $this->redirect()->toRoute('administrativeappraisal');
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
                'submitForm' => $submitForm,
				'employee_details_id' => $this->employee_details_id,
				'role' => $this->userrole,
				'occupational_group' => $this->occupational_group,
				'selectData' => $objectivesSelect,
				'iwp_deadline' => $iwp_deadline,
				'keyphrase' => $this->keyphrase,
				'organisation_id' => $this->organisation_id,
				'appraisal_period' => $appraisal_period,
				'message' => $message,
				'kpi' => $kpi);
    }
	
	public function editAdministrativeAppraisalAction()
    {
		$this->loginDetails();
				
		//get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new AdministrativeAppraisalForm();
			$administrativeModel = new IwpObjectives();
			$form->bind($administrativeModel);
			
			//Need to send value of the table name and columns
			$objectivesSelect = $this->appraisalService->listSelectData($tableName='awpa_objectives_activity', $columnName='activity_name', $this->organisation_id);
			
			$kpi = $this->appraisalService->listAdministrativeAppraisal($table = 'iwp_subactivities', $this->employee_details_id, $status=NULL);
			$appraisalDetail = $this->appraisalService->getDetail($tableName = 'iwp_subactivities', $id);

			$message = NULL;
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
						 $this->appraisalService->saveAdministrativeAppraisal($administrativeModel);
						 $this->auditTrailService->saveAuditTrail("EDIT", "Administrative Appraisal Edited", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage('You have successfully edited the added appraisal for submission to your supervisor');
						 return $this->redirect()->toRoute('administrativeappraisal');
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
					'employee_details_id' => $this->employee_details_id,
					'selectData' => $objectivesSelect,
					'appraisalDetail' => $appraisalDetail,
					'message' => $message,
					'kpi' => $kpi);
		}
		else {
			return $this->redirect()->toRoute('administrativeappraisal');
		}
    }
	
	public function viewAdministrativeAppraisalAction()
    {
		$this->loginDetails();
				
		//get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new AdministrativeAppraisalForm();
			$administrativeModel = new IwpObjectives();
			$form->bind($administrativeModel);
			
			//Need to send value of the table name and columns
			$objectivesSelect = $this->appraisalService->listSelectData($tableName='awpa_objectives_activity', $columnName='activity_name', $this->organisation_id);
			$appraisalDetail = $this->appraisalService->getDetail($tableName = 'iwp_subactivities', $id);
			
			return array(
					'form' => $form,
					'selectData' => $objectivesSelect,
					'appraisalDetail' => $appraisalDetail);
		}
		else {
			return $this->redirect()->toRoute('administrativeappraisal');
		}
    }
	
	public function deleteAdministrativeAppraisalAction()
    {
		$this->loginDetails();
		//get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			try {
				 $this->appraisalService->deleteAppraisal($id);
				 $this->auditTrailService->saveAuditTrail("DELETE", "Administrative Appraisal Edited", "ALL", "SUCCESS");
				 $this->flashMessenger()->addMessage('IWP Appraisal was successfully deleted');
				 return $this->redirect()->toRoute('administrativeappraisal');
			 }
			 catch(\Exception $e) {
				 $message = 'Failure';
				 $this->flashMessenger()->addMessage($e->getMessage());
				 // Some DB Error happened, log it and let the user know
			 }
			return array(
				'message' => $message,
			);
		}
		else {
			return $this->redirect()->toRoute('administrativeappraisal');
		}
    }
	
    public function academicAppraisalAction()
    {
		$this->loginDetails();
				
		$form = new AcademicAppraisalForm();
		$academicModel = new AcademicAppraisal();
		$form->bind($academicModel);
                
        $submitForm = new SubmitForm();
		
		//Need to send value of the table name and columns
		$natureActivitySelect = $this->appraisalService->listSelectData($tableName='pms_nature_activity', $columnName='nature_of_activity', $empIds = NULL);
		
		$objectivesSelect = $this->appraisalService->listSelectData($tableName='awpa_objectives_activity', $columnName='activity_name', $this->organisation_id);
		
		//Data to fill up the table
		//activity id is hard coded. Will need to retrieve these values
		$kpi = $this->appraisalService->listEmployeeAppraisal($table = 'pms_academic_api', $this->employee_details_id, $status='Not Submitted');
		$teachingTheme = $this->appraisalService->listActivityDetail($table = 'pms_nature_activity', $columnName = 'pms_academic_weight_id', $activity_id=1);
		$researchTheme = $this->appraisalService->listActivityDetail($table = 'pms_nature_activity', $columnName = 'pms_academic_weight_id', $activity_id=2);
		$servicesTheme = $this->appraisalService->listActivityDetail($table = 'pms_nature_activity', $columnName = 'pms_academic_weight_id', $activity_id=3);
		$academicWeight = $this->appraisalService->listSelectData($table='pms_academic_weight', $columnName='category', $organisation_id = NULL);
		
		//get the last date for IWP
		$iwp_deadline = $this->appraisalService->getIwpDeadline('IWP Submission');

		$appraisal_period = $this->appraisalService->getAppraisalPeriodYear('IWP Submission', $tableName = 'pms_activation_dates');

		$message = NULL;
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->appraisalService->saveAcademicAppraisal($academicModel);
					 $this->notificationService->saveNotification('Academic Appraisal', 'ALL', 'ALL', 'Submission of Academic Appraisal');
					 $this->auditTrailService->saveAuditTrail("INSERT", "Academic Appraisal Added", "ALL", "SUCCESS");
					 $this->flashMessenger()->addMessage('You have successfully added appraisal for submission to your supervisor');
					 return $this->redirect()->toRoute('academicappraisal');
				 }
				 catch(\Exception $e) {
				 	$message = 'Failure';
				 	$this->flashMessenger()->addMessage($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
        return array(
				'employee_id' => $this->employee_details_id,
				'role' => $this->userrole,
				'occupational_group' => $this->occupational_group,
				'form' => $form,
                'submitForm' => $submitForm,
				'natureActivity' => $natureActivitySelect,
				'objectivesSelect' => $objectivesSelect,
				'kpi' => $kpi,
				'teachingTheme' => $teachingTheme,
				'researchTheme' => $researchTheme,
				'servicesTheme' => $servicesTheme,
				'academicWeight' => $academicWeight,
				'iwp_deadline' => $iwp_deadline,
				'appraisal_period' => $appraisal_period,
				'keyphrase' => $this->keyphrase,
				'message' => $message,
			);
    }
	
	public function editAcademicAppraisalAction()
    {
		$this->loginDetails();
				
		//get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new AcademicAppraisalForm();
			$academicModel = new AcademicAppraisal();
			$form->bind($academicModel);
			
			$appraisalDetail = $this->appraisalService->getDetail($tableName = 'pms_academic_api', $id);
			
			//Need to send value of the table name and columns
			$natureActivitySelect = $this->appraisalService->listSelectData($tableName='pms_nature_activity', $columnName='nature_of_activity', $empIds = NULL);
			
			$objectivesSelect = $this->appraisalService->listSelectData($tableName='awpa_objectives_activity', $columnName='activity_name', $this->organisation_id);
			
			//Data to fill up the table
			//activity id is hard coded. Will need to retrieve these values
			$kpi = $this->appraisalService->listEmployeeAppraisal($table = 'pms_academic_api', $this->employee_details_id, $status=NULL);
			$teachingTheme = $this->appraisalService->listActivityDetail($table = 'pms_nature_activity', $columnName = 'pms_academic_weight_id', $activity_id=1);
			$researchTheme = $this->appraisalService->listActivityDetail($table = 'pms_nature_activity', $columnName = 'pms_academic_weight_id', $activity_id=2);
			$servicesTheme = $this->appraisalService->listActivityDetail($table = 'pms_nature_activity', $columnName = 'pms_academic_weight_id', $activity_id=3);
			$academicWeight = $this->appraisalService->listSelectData($table='pms_academic_weight', $columnName='category', $organisation_id = NULL);
			
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
						 $this->appraisalService->saveAcademicAppraisal($academicModel);
						 $this->auditTrailService->saveAuditTrail("EDIT", "Academic Appraisal Edited", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('academicappraisal');
						 
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			return array(
					'employee_id' => $this->employee_details_id,
					'form' => $form,
					'appraisalDetail' => $appraisalDetail,
					'natureActivity' => $natureActivitySelect,
					'objectivesSelect' => $objectivesSelect,
					'kpi' => $kpi,
					'teachingTheme' => $teachingTheme,
					'researchTheme' => $researchTheme,
					'servicesTheme' => $servicesTheme,
					'keyphrase' => $this->keyphrase,
					'academicWeight' => $academicWeight);
		}
		else {
			return $this->redirect()->toRoute('academicappraisal');
		}
    }
	
	public function viewAcademicAppraisalAction()
    {
		$this->loginDetails();
				
		//get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new AcademicAppraisalForm();
			$academicModel = new AcademicAppraisal();
			$form->bind($academicModel);
			
			$appraisalDetail = $this->appraisalService->getDetail($tableName = 'pms_academic_api', $id);
			
			//Need to send value of the table name and columns
			$natureActivitySelect = $this->appraisalService->listSelectData($tableName='pms_nature_activity', $columnName='nature_of_activity', $empIds = NULL);
			
			//Need to send value of the table name and columns
			//Emp Ids is an array of Ids of Directors
			$objectivesSelect = $this->appraisalService->listSelectData($tableName='awpa_objectives_activity', $columnName='activity_name', $this->organisation_id);
			
			//Data to fill up the table
			//activity id is hard coded. Will need to retrieve these values
			$kpi = $this->appraisalService->listEmployeeAppraisal($table = 'pms_academic_api', $this->employee_details_id, $status=NULL);
			$teachingTheme = $this->appraisalService->listActivityDetail($table = 'pms_nature_activity', $columnName = 'pms_academic_weight_id', $activity_id=1);
			$researchTheme = $this->appraisalService->listActivityDetail($table = 'pms_nature_activity', $columnName = 'pms_academic_weight_id', $activity_id=2);
			$servicesTheme = $this->appraisalService->listActivityDetail($table = 'pms_nature_activity', $columnName = 'pms_academic_weight_id', $activity_id=3);
			$academicWeight = $this->appraisalService->listSelectData($table='pms_academic_weight', $columnName='category', $organisation_id = NULL);
			
			return array(
					'employee_id' => $this->employee_details_id,
					'form' => $form,
					'appraisalDetail' => $appraisalDetail,
					'natureActivity' => $natureActivitySelect,
					'objectivesSelect' => $objectivesSelect,
					'kpi' => $kpi,
					'teachingTheme' => $teachingTheme,
					'researchTheme' => $researchTheme,
					'servicesTheme' => $servicesTheme,
					'academicWeight' => $academicWeight
				);
		}
		else {
			return $this->redirect()->toRoute('academicappraisal');
		}	
    }
    
	public function viewPeerAction()
    {
		$this->loginDetails();
		
        $form = new AppraisalForm();
		$appraisalModel = new Appraisal();
		$form->bind($appraisalModel);
		
		$students = $this->appraisalService->listAll($tableName='student');
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->appraisalService->save($appraisalModel);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'students' => $students);
    }
	
	public function addNatureActivityAction()
    {
		$this->loginDetails();
		
        $activityForm = new NatureActivityForm();
		$activityModel = new NatureActivity();
		$activityForm->bind($activityModel);
		
		$academicForm = new AcademicWeightForm();
		
		//$students = $this->appraisalService->listAll($tableName='student');
		$academicWeight = $this->appraisalService->listAll($tableName='pms_academic_weight');
		$natureActivity = $this->appraisalService->listAll($tableName='pms_nature_activity');
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $academicForm->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->appraisalService->save($activityModel);
					 $this->auditTrailService->saveAuditTrail("INSERT", "Nature of Activity Added", "ALL", "SUCCESS");
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'activityForm' => $activityForm,
			'academicForm' => $academicForm,
			'academicWeight' => $academicWeight,
			'natureActivity' => $natureActivity);
    }
	
	public function editNatureActivityAction()
    {
		$this->loginDetails();
		
        //get the id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$activityForm = new NatureActivityForm();
		$activityModel = new NatureActivity();
		$activityForm->bind($activityModel);
				
		$natureActivity = $this->appraisalService->listAll($tableName='pms_nature_activity');
		$activityDetail = $this->appraisalService->getDetail('pms_nature_activity', $id);
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $academicForm->setData($request->getPost());
			 var_dump($activityForm);
			 die();
             if ($form->isValid()) {
                 try {
					 $this->appraisalService->save($activityModel);
					 $this->auditTrailService->saveAuditTrail("EDIT", "Academic Appraisal Edited", "ALL", "SUCCESS");
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'activityForm' => $activityForm,
			'natureActivity' => $natureActivity,
			'activityDetail' => $activityDetail);
    }
	
	public function addAcademicWeightAction()
    {
		$this->loginDetails();
			
		$academicForm = new AcademicWeightForm();
		$academicModel = new AcademicWeight();
		$academicForm->bind($academicModel);
		
		//$students = $this->appraisalService->listAll($tableName='student');
		$academicWeight = array();
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $academicForm->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->appraisalService->save($academicModel);
					 $this->auditTrailService->saveAuditTrail("INSERT", "Academic Weight Added", "ALL", "SUCCESS");
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'academicForm' => $academicForm,
			'academicWeight' => $academicWeight);
    }
	
	public function editAcademicWeightAction()
    {
		$this->loginDetails();
			
		//get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
				
		$academicForm = new AcademicWeightForm();
		$academicModel = new AcademicWeight();
		$academicForm->bind($academicModel);
		
		$academicWeight = $this->appraisalService->listAll($tableName='pms_academic_weight');
        $academicWeightDetails = $this->appraisalService->getDetail('pms_academic_weight', $id);
		
        $request = $this->getRequest();
         if ($request->isPost()) {
             $academicForm->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->appraisalService->save($academicModel);
					 $this->auditTrailService->saveAuditTrail("EDIT", "Academic Weight Edited", "ALL", "SUCCESS");
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'academicForm' => $academicForm,
			'academicWeight' => $academicWeight,
			'academicWeightDetails' => $academicWeightDetails);
    }
	
	public function addAcademicApiAction()
    {
		$this->loginDetails();
		
        $form = new AppraisalForm();
		$appraisalModel = new Appraisal();
		$form->bind($appraisalModel);
		
		$students = $this->appraisalService->listAll($tableName='student');
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->appraisalService->save($appraisalModel);
					 $this->auditTrailService->saveAuditTrail("INSERT", "Academic API Added", "ALL", "SUCCESS");
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'students' => $students);
    }
	
	public function appraisalApprovalListAction()
	{
		$this->loginDetails();
		
		$form = new AppraisalForm();
		$appraisalModel = new Appraisal();
		$form->bind($appraisalModel);
		
		$academicAppraisal = $this->appraisalService->getAppraisalList($type='academic', $this->employee_details_id, $this->userrole, $this->organisation_id);
		$administrativeAppraisal = $this->appraisalService->getAppraisalList($type='administrative', $this->employee_details_id, $this->userrole, $this->organisation_id);
		
		return array(
			'form' => $form,
			'academicAppraisal' => $academicAppraisal,
			'administrativeAppraisal' => $administrativeAppraisal,
			'keyphrase' => $this->keyphrase );
	}
	
	public function viewAdministrativeAppraisalDetailAction()
	{
		$this->loginDetails();
		
		//here the $id is the employee_details id we get from the form/route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			//Status "Approved" is used as a dummy. We will be listing all the non approved for evaluation
			$administrativeAppraisal = $this->appraisalService->listAdministrativeAppraisal($table = 'iwp_subactivities', $id, $status="Approved");
			
			//Get the list of the Supervisor Activities
			$supervisor_activities = $this->appraisalService->getSupervisorSuccessIndicators($id);
			
			//rewind not supported so same function used to get the array data
			$tmp_data = $this->appraisalService->listAdministrativeAppraisal($table = 'iwp_subactivities', $id, $status='Approved');
			$array_data = array();
			foreach($tmp_data as $appraisal){
				$array_data[$appraisal['id']] = $appraisal['id'];
			}
			$appraisalCount = count($administrativeAppraisal);
			
			$form = new AppraisalReviewForm($array_data);
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 $data = $this->extractFormData($appraisalCount, $administrativeAppraisal);
					 try {
						 $this->appraisalService->saveReview($data, $type='Administrative');
						 $this->notificationService->saveNotification('Administrative Appraisal Review', 'ALL', 'ALL', 'Submission of Administrative Appraisal Review');
						 $this->auditTrailService->saveAuditTrail("INSERT", "Administrative Appraisal Review Added", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage('You have successfully updated the appraisal status of staff.');
						 return $this->redirect()->toRoute('appraisalapprovallist');
					 }
					 catch(\Exception $e) {
							$message = 'Failure';
							$this->flashMessenger()->addMessage($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			$employee_details = $this->appraisalService->getEmployeeDetails($id);		
			return array(
				'form' => $form,
				'employee_details' => $employee_details,
				'supervisor_activities' => $supervisor_activities,
				'administrativeAppraisal' => $administrativeAppraisal);
		}
		else {
			return $this->redirect()->toRoute('appraisalapprovallist');
		}
	}
	
	public function viewAcademicAppraisalDetailAction()
	{
		$this->loginDetails();
		
		//here the $id is the employee_details id we get from the form
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$academicAppraisal = $this->appraisalService->listEmployeeAppraisal($table = 'pms_academic_api', $id, $status='Approved');
			//rewind not supported so same function used to get the array data
			$tmp_data = $this->appraisalService->listEmployeeAppraisal($table = 'pms_academic_api', $id, $status='Approved');
			$array_data = array();
			foreach($tmp_data as $appraisal){
				$array_data[$appraisal['id']] = $appraisal['id'];
			}
			
			$form = new AppraisalReviewForm($array_data);
			
			//Need to send value of the table name and columns
			$natureActivitySelect = $this->appraisalService->listSelectData($tableName='pms_nature_activity', $columnName='nature_of_activity', $empIds = NULL);
			
			//Need to send value of the table name and columns
			//Emp Ids is an array of Ids of Directors
			$objectivesSelect = $this->appraisalService->listSelectData($tableName='awpa_objectives_activity', $columnName='activity_name', $this->organisation_id);
			
			//Data to fill up the table
			//activity id is hard coded. Will need to retrieve these values
			$kpi = $this->appraisalService->listEmployeeAppraisal($table = 'pms_academic_api', $id, $status='Approved');
			$teachingTheme = $this->appraisalService->listActivityDetail($table = 'pms_nature_activity', $columnName = 'pms_academic_weight_id', $activity_id=1);
			$researchTheme = $this->appraisalService->listActivityDetail($table = 'pms_nature_activity', $columnName = 'pms_academic_weight_id', $activity_id=2);
			$servicesTheme = $this->appraisalService->listActivityDetail($table = 'pms_nature_activity', $columnName = 'pms_academic_weight_id', $activity_id=3);
			$academicWeight = $this->appraisalService->listSelectData($table='pms_academic_weight', $columnName='category', $organisation_id = NULL);
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 $data = $this->extractFormData($array_data, $kpi);
					 try {
						 $this->appraisalService->saveReview($data, $type='Academic');
						 $this->notificationService->saveNotification('Administrative Appraisal Review', 'ALL', 'ALL', 'Submission of Administrative Appraisal Review');
						 $this->auditTrailService->saveAuditTrail("INSERT", "Administrative Appraisal Review Added", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('appraisalapprovallist');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
					
			$employee_details = $this->appraisalService->getEmployeeDetails($id);	
			
			return array(
				'form' => $form,
				'employee_id' => $this->employee_details_id,
				'natureActivity' => $natureActivitySelect,
				'objectivesSelect' => $objectivesSelect,
				'kpi' => $kpi,
				'teachingTheme' => $teachingTheme,
				'researchTheme' => $researchTheme,
				'servicesTheme' => $servicesTheme,
				'academicWeight' => $academicWeight,
				'employee_details' => $employee_details,
				'academicAppraisal' => $academicAppraisal);
		}
		else {
			return $this->redirect()->toRoute('appraisalapprovallist');
		}
		

	}
        
	public function employeeIWPActivitiesAction()
	{
		$this->loginDetails();
		
		if($this->occupational_group == "Academics"){
			//Academic Appraisal
			$kpi = $this->appraisalService->listEmployeeAppraisal($table = 'pms_academic_api', $this->employee_details_id, $status=NULL);
			$teachingTheme = $this->appraisalService->listActivityDetail($table = 'pms_nature_activity', $columnName = 'pms_academic_weight_id', $activity_id=1);
			$researchTheme = $this->appraisalService->listActivityDetail($table = 'pms_nature_activity', $columnName = 'pms_academic_weight_id', $activity_id=2);
			$servicesTheme = $this->appraisalService->listActivityDetail($table = 'pms_nature_activity', $columnName = 'pms_academic_weight_id', $activity_id=3);
			$academicWeight = $this->appraisalService->listSelectData($table='pms_academic_weight', $columnName='category', $organisation_id = NULL);
		} else {
			//Administrative Appraisal
			$kpi = $this->appraisalService->listAdministrativeAppraisal($table = 'iwp_subactivities', $this->employee_details_id, $status=NULL);
			$teachingTheme = NULL;
			$researchTheme = NULL;
			$servicesTheme = NULL;
			$academicWeight = NULL;
		}
		
		return array(
			'employee_id' => $this->employee_details_id,
            'occupational_group' => $this->occupational_group,
			'kpi' => $kpi,
			'teachingTheme' => $teachingTheme,
			'researchTheme' => $researchTheme,
			'servicesTheme' => $servicesTheme,
			'academicWeight' => $academicWeight);
        }
        
	public function submitIWPActivitiesAction()
	{
		$this->loginDetails();
		
		//get the employee id
		$employee_id = (int) $this->params()->fromRoute('id', 0);
                
		if($this->occupational_group == "Academics"){
			$table_name = 'pms_academic_api';
			$redirect_route = 'academicappraisal';
		}
		else {
			$table_name ='iwp_subactivities';
			$redirect_route = 'administrativeappraisal';
		}
		$this->appraisalService->submitIWPActivities($employee_id, $table_name);
		$this->flashMessenger()->addMessage('You have successfully submitted your appraisal to your supervisor.');
		return $this->redirect()->toRoute($redirect_route);
        }
	
	//TO APPROVE AND REJECT NOMINATIONS (BENEFICIARY, PEER AND SUBORDINATE)
	
	public function viewNominationAppraisalAction()
	{
		$this->loginDetails();
		
		//get the employee nomination id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$peerList = $this->appraisalService->getNominationList($tableName = 'peer_nomination', $id);
			$subordinateList = $this->appraisalService->getNominationList($tableName = 'subordinate_nomination', $id);
			$beneficiaryList = $this->appraisalService->getNominationList($tableName = 'beneficiary_nomination', $id);
			$employee_details = $this->appraisalService->getEmployeeDetails($id);
			
			$nomination_list['peer'] = count($peerList);
			$nomination_list['subordinate'] = count($subordinateList);
			$nomination_list['beneficiary'] = count($beneficiaryList);
			
			$form = new AppraisalNominationForm($nomination_list);
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 $data = $this->extractFormNominationData($nomination_list);
					 try {
						 $this->appraisalService->updateNominationStatus($data, $id);
						 $this->notificationService->saveNotification('Administrative Appraisal Review', 'ALL', 'ALL', 'Submission of Administrative Appraisal Review');
						 $this->auditTrailService->saveAuditTrail("INSERT", "Administrative Appraisal Review Added", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('appraisalapprovallist');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return array(
				'form' => $form,
				'peerList' => $peerList,
				'subordinateList' => $subordinateList,
				'beneficiaryList' => $beneficiaryList,
				'employee_details' => $employee_details
			);
		}
		else {
			return $this->redirect()->toRoute('appraisalapprovallist');
		}
	}
	
	//check whether used or not
	public function listNatureActivityAction()
    {
		$this->loginDetails();
		
        $form = new AppraisalForm();
		$appraisalModel = new Appraisal();
		$form->bind($appraisalModel);
		
		$approvedList = array();
		$rejectedList = array();
		$pendingList = array();
		return array(
			'form' => $form,
			'approvedList' => $approvedList,
			'rejectedList' => $rejectedList,
			'pendingList' => $pendingList);
    }
	
	//check whether used or not
	public function listAcademicApiAction()
    {
		$this->loginDetails();
		
        $form = new AppraisalForm();
		$appraisalModel = new Appraisal();
		$form->bind($appraisalModel);
		
		$approvedList = array();
		$rejectedList = array();
		$pendingList = array();
		return array(
			'form' => $form,
			'approvedList' => $approvedList,
			'rejectedList' => $rejectedList,
			'pendingList' => $pendingList);
    }
    
	//check whether used or not
	public function listAppraisalAction()
    {
		$this->loginDetails();
		
        $form = new AppraisalForm();
		$appraisalModel = new Appraisal();
		$form->bind($appraisalModel);
		
		$approvedList = array();
		$rejectedList = array();
		$pendingList = array();
		return array(
			'form' => $form,
			'approvedList' => $approvedList,
			'rejectedList' => $rejectedList,
			'pendingList' => $pendingList);
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
	
	//the following function is to extract the data from the form 
	// and return clean data to be inserted into database
	public function extractFormData($appraisal_data, $kpi)
	{
		$evaluationData = array();
		$academic_activities = array();
		$i=1;
		foreach($kpi as $kpi){
			 $academic_activities[$kpi['id']]['remarks']= $this->getRequest()->getPost('remarks'.$kpi['id']);
			 $academic_activities[$kpi['id']]['status']= $this->getRequest()->getPost('status'.$kpi['id']);
		 }
		return $academic_activities;
	}
	
	//new extract form as the above could not be reused due to the complexity in the data
	public function extractFormNominationData($nomination_list)
	{
		$evaluationData = array();
		for($i=1; $i<=$nomination_list['subordinate']; $i++){
			$evaluationData['subordinate'][] = $this->getRequest()->getPost('subordinate'.$i);
		}
		for($i=1; $i<=$nomination_list['peer']; $i++){
			$evaluationData['peer'][] = $this->getRequest()->getPost('peer'.$i);
		}
		for($i=1; $i<=$nomination_list['beneficiary']; $i++){
			$evaluationData['beneficiary'][] = $this->getRequest()->getPost('beneficiary'.$i);
		}
		return $evaluationData;
	}
    
}

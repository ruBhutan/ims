<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Planning\Controller;

use Planning\Service\PlanningServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Planning\Model\Vision;
use Planning\Model\Mission;
use Planning\Model\Objectives;
use Planning\Model\ObjectivesWeightage;
use Planning\Model\Activities;
use Planning\Model\AwpaObjectives;
use Planning\Model\AwpaActivities;
use Planning\Model\KeyAspiration;
use Planning\Model\Evaluation;
use Planning\Model\FiveYearPlan;
use Planning\Model\ApaActivation;
use Planning\Model\SuccessIndicatorDefinition;
use Planning\Model\SuccessIndicatorTrend;
use Planning\Model\SuccessIndicatorRequirements;
use Planning\Model\BudgetOverlay;
use Planning\Model\OrganisationBudgetOverlay;
use Planning\Form\PlanningForm;
use Planning\Form\VisionForm;
use Planning\Form\MissionForm;
use Planning\Form\ObjectivesForm;
use Planning\Form\ObjectivesWeightageForm;
use Planning\Form\ActivitiesForm;
use Planning\Form\AwpaObjectivesActivityForm; 
use Planning\Form\AwpaActivitiesForm;
use Planning\Form\KeyAspirationForm;
use Planning\Form\EvaluationForm;
use Planning\Form\FiveYearPlanForm;
use Planning\Form\ApaActivationForm;
use Planning\Form\SuccessIndicatorDefinitionForm;
use Planning\Form\SuccessIndicatorRequirementsForm;
use Planning\Form\SuccessIndicatorTrendForm;
use Planning\Form\BudgetOverlayForm;
use Planning\Form\OrganisationBudgetOverlayForm;
use Planning\Form\SearchForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
//AJAX
use Zend\Http\Response\Stream;
use Zend\Http\Headers;
use Zend\Paginator\Adapter\DbSelect;
use Zend\View\Model\JsonModel;
use Zend\Form\Element\Select;

/**
 * Description of IndexController
 *
 * @author Mendrel
 */
class PlanningController extends AbstractActionController
{
	protected $planningService;
	protected $notificationService;
    protected $auditTrailService;
    protected $serviceLocator;
	protected $username;
	protected $userrole;
	protected $usertype;
	protected $userregion;
	protected $userDetails;
	protected $userImage;
	protected $role;
	protected $employee_details_id;
	protected $position_title;
	protected $organisation_id;
	protected $departments_id;
	protected $keyphrase = "RUB_IMS";
	
	public function __construct(PlanningServiceInterface $planningService,  NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator) 
	{
		$this->planningService = $planningService;
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
		* Getting the employee_details_id related to username
		*/
		
		$empData = $this->planningService->getUserDetailsId($this->username, $tableName = 'employee_details');
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			$this->departments_id = $emp['departments_id'];
		}
		
		/*
		* Get the position title of the user
		*/
		
		$title = $this->planningService->getPositionTitle($this->employee_details_id);
		foreach($title as $emptitle){
			$this->position_title = $emptitle['position_title'];
		}
		
		//get the organisation id
		$organisationID = $this->planningService->getOrganisationId($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}
		
		//get the user details such as name
        $this->userDetails = $this->planningService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->planningService->getUserImage($this->username, $this->usertype);
	}
	
	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
         $this->layout()->setVariable('userImage', $this->userImage);
    }
	
	/*
	* The action is for setting the Five Year Plan
	*/
	
	public function addFiveYearPlanAction()
    {
		$this->loginDetails();
		
		$form = new FiveYearPlanForm();
		$planningModel = new FiveYearPlan();
		$form->bind($planningModel);
		
		$FiveYearPlan = $this->planningService->listAll($tableName = 'five_year_plan', $employee_details_id = NULL);

		$message = NULL;
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid()) {
				$data = $this->getRequest()->getPost('fiveyearplan');
				$five_year_plan = $data['five_year_plan'];
				
				$check_five_year_plan = $this->planningService->crossCheckFiveYearPlan($five_year_plan);

				if($check_five_year_plan){
					$message = 'Failure';
					$this->flashMessenger()->addMessage("You have already added ".$five_year_plan." and if you want to edit please click on edit from the list below.");
				}else{
					try {
					   $this->planningService->saveFiveYearPlan($planningModel);
					   $this->flashMessenger()->addMessage('Five Year Dates was successfully added');
					   $this->auditTrailService->saveAuditTrail("INSERT", "Five Year Dates were added", "ALL", "SUCCESS");
					   return $this->redirect()->toRoute('addfiveyearplan');
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
			'keyphrase' => $this->keyphrase,
			'FiveYearPlan' => $FiveYearPlan,
			'message' => $message,
		);
	}
    
	public function editFiveYearPlanAction()
    {
		$this->loginDetails();
		
		//get the FiveYearPlan id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new FiveYearPlanForm();
			$planningModel = new FiveYearPlan();
			$form->bind($planningModel);
			
			$FiveYearPlanDetails = $this->planningService->findFiveYearPlan($id);
			$FiveYearPlan = $this->planningService->listAll($tableName = 'five_year_plan', $employee_details_id = NULL);
			
			$request = $this->getRequest();
			if ($request->isPost()) {
				$form->setData($request->getPost());
				if ($form->isValid()) {
					try {
						   $this->planningService->saveFiveYearPlan($planningModel);
						   $this->flashMessenger()->addMessage('Dates were successfully edited');
						   $this->auditTrailService->saveAuditTrail("EDIT", "Five Year Dates were edited", "ALL", "SUCCESS");
						   return $this->redirect()->toRoute('addfiveyearplan');
				   }
				   catch(\Exception $e) {
								   die($e->getMessage());
								   // Some DB Error happened, log it and let the user know
				   }
				}
			}
	
		   return array(
				   'form' => $form,
				   'FiveYearPlan' => $FiveYearPlan,
				   'keyphrase' => $this->keyphrase,
				   'FiveYearPlanDetails' => $FiveYearPlanDetails);
		} 
		else {
			return $this->redirect()->toRoute('addfiveyearplan');
		}
		
	}
	
	/*
	* The action is for setting the Vision
	*/
	
	public function addVisionAction()
    {
		$this->loginDetails();
		
		$form = new VisionForm();
		$planningModel = new Vision();
		$form->bind($planningModel);
		
		$five_year = NULL;
		$five_year_plan = $this->planningService->getFiveYearPlan();
		foreach($five_year_plan as $key => $value){
			$five_year = $key; 
		 }
		 
		$visionMission = $this->planningService->getVisionMission($table='rub_vision', $five_year);

		$message = NULL;
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid()) {
				$data = $this->getRequest()->getPost('rubvisionmission');
				$vision = $data['vision'];
				$check_vision = $this->planningService->crossCheckFiveYearVision($five_year, $vision);

				if($check_vision){
					$message = 'Failure';
					$this->flashMessenger()->addMessage("You have already added similar vision for the same five year. Please try for different.");
				}
				else{
					try {
					   $this->planningService->saveVision($planningModel);
					   $this->flashMessenger()->addMessage('Vision was successfully added');
					   $this->auditTrailService->saveAuditTrail("INSERT", "Five Year Vision were added", "ALL", "SUCCESS");
					   return $this->redirect()->toRoute('addvision');
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
			'five_year_plan' => $five_year_plan,
			'keyphrase' => $this->keyphrase,
			'visionMission' => $visionMission,
			'message' => $message,
		);
	}
    
	public function editVisionAction()
    {
		$this->loginDetails();
		
		//get the mission and vision id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new VisionForm();
			$planningModel = new Vision();
			$form->bind($planningModel);
			
			$five_year = NULL;
			$five_year_plan = $this->planningService->getFiveYearPlan();
			foreach($five_year_plan as $key => $value){
				$five_year = $key; 
			 }
			 
			$visionMission = $this->planningService->getVisionMission($table='rub_vision', $five_year);
			$visionDetails = $this->planningService->findVisionMission($table_name = 'rub_vision', $id);
			
			$request = $this->getRequest();
			if ($request->isPost()) {
				$form->setData($request->getPost());
				if ($form->isValid()) {
					try {
						   $this->planningService->saveVision($planningModel);
						   $this->flashMessenger()->addMessage('Vision was successfully edited');
						   $this->auditTrailService->saveAuditTrail("EDIT", "Five Year Vision was edited", "ALL", "SUCCESS");
						   return $this->redirect()->toRoute('addvision');
				   }
				   catch(\Exception $e) {
								   die($e->getMessage());
								   // Some DB Error happened, log it and let the user know
				   }
				}
			}
	
		   return array(
				   'form' => $form,
				   'five_year_plan' => $five_year_plan,
				   'visionDetails' => $visionDetails,
				   'keyphrase' => $this->keyphrase,
				   'visionMission' => $visionMission);
		} 
		else {
			return $this->redirect()->toRoute('addvision');
		}
		
   }
	
	/*
	* The action is for setting the Mission
	*/
	
	public function addMissionAction()
	{
		$this->loginDetails();
		
		$form = new MissionForm();
		$planningModel = new Mission();
		$form->bind($planningModel);
		
		$five_year = NULL;
		$five_year_plan = $this->planningService->getFiveYearPlan();
		foreach($five_year_plan as $key => $value){
			$five_year = $key; 
		 }
		 
		$visionMission = $this->planningService->getVisionMission($table='rub_mission', $five_year);

		$message = NULL;
		
		$request = $this->getRequest();
		if ($request->isPost()) {
		 $form->setData($request->getPost());
		 if ($form->isValid()) {
		 	$data = $this->getRequest()->getPost('rubvisionmission');
			$mission = $data['mission'];

			$check_mission = $this->planningService->crossCheckFiveYearMission($five_year, $mission);
			if($check_mission){
				$message = 'Failure';
				$this->flashMessenger()->addMessage("You have already added similar mission for the same five year. Please try for different.");
			}else{
				try {
					$this->planningService->saveMission($planningModel);
					$this->flashMessenger()->addMessage('Mission was successfully added');
					$this->auditTrailService->saveAuditTrail("INSERT", "Five Year Mission were added", "ALL", "SUCCESS");
					return $this->redirect()->toRoute('addmission');
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
				'five_year_plan' => $five_year_plan,
				'keyphrase' => $this->keyphrase,
				'visionMission' => $visionMission,
				'message' => $message,
			);
	}
    
	public function editMissionAction()
	{
		$this->loginDetails();
		
		//get the mission and vision id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new MissionForm();
			$planningModel = new Mission();
			$form->bind($planningModel);
			
			$five_year = NULL;
			$five_year_plan = $this->planningService->getFiveYearPlan();
			foreach($five_year_plan as $key => $value){
				$five_year = $key; 
			 }
			 
			$visionMission = $this->planningService->getVisionMission($table='rub_mission', $five_year);
			$missionDetails = $this->planningService->findVisionMission($table_name='rub_mission', $id);
			
			$request = $this->getRequest();
			if ($request->isPost()) {
				$form->setData($request->getPost());
				if ($form->isValid()) {
					try {
						   $this->planningService->saveMission($planningModel);
						   $this->flashMessenger()->addMessage('Mission was successfully edited');
						   $this->auditTrailService->saveAuditTrail("EDIT", "Five Year Mission was edited", "ALL", "SUCCESS");
						   return $this->redirect()->toRoute('addmission');
				   }
				   catch(\Exception $e) {
								   die($e->getMessage());
								   // Some DB Error happened, log it and let the user know
				   }
				}
			}
	
			   return array(
					   'form' => $form,
					   'five_year_plan' => $five_year_plan,
					   'missionDetails' => $missionDetails,
					   'visionMission' => $visionMission,
					   'keyphrase' => $this->keyphrase);
		} 
		else {
			return $this->redirect()->toRoute('addmission');
		}	
		
	}
	
	/*
	* The action is for setting the Objectives
	*/
	
	public function objectivesAction()
	{
		$this->loginDetails();
		
        $form = new ObjectivesForm();
		$planningModel = new Objectives();
		$form->bind($planningModel);
		
		$five_year = NULL;
		$five_year_plan = $this->planningService->getFiveYearPlan();
		foreach($five_year_plan as $key => $value){
			$five_year = $key; 
		 } 
		 
		$objectives = $this->planningService->getRubObjectives($table='rub_objectives_weightage', $five_year);
                $message = NULL;
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
			//extract the data from the form
			$data = $this->getRequest()->getPost('rubobjectives');
			$weightage = $data['weightage'];
			$organisation = $data['remarks'];
			//var_dump($organisation); die();
			$five_year_plan = $data['five_year_plan'];
			$objectives = $data['objectives']; 
			$total_weightage = $this->planningService->getObjectiveWeightage(NULL, $five_year_plan, NULL, NULL);

			$check_objective = $this->planningService->crossCheckFiveYearObjective($five_year_plan, $objectives);

			if($check_objective){
				$message = 'Failure';
				$this->flashMessenger()->addMessage("You have already added similar objective for the same five year. Please try for different!");
			}else{
				if($total_weightage+$weightage > 100){
				//ensure that the objective weight does not exceed 100
				$message = 'Error';
				$this->flashMessenger()->addMessage("The Total weightage exceeds 100! Please re-enter the values.");
				
				} else{
					if ($form->isValid()) {
						try {
							   $this->planningService->saveObjectives($planningModel);
							   $this->flashMessenger()->addMessage('Objectives was successfully added');
							   $this->auditTrailService->saveAuditTrail("INSERT", "Objectives were added", "ALL", "SUCCESS");
							   return $this->redirect()->toRoute('objectives');
					   }
					   catch(\Exception $e) {
									   die($e->getMessage());
									   // Some DB Error happened, log it and let the user know
					   }
					}
				}
			}
		}
	   return array(
			   'form' => $form,
			   'message' => $message,
			   'five_year_plan' => $this->planningService->getFiveYearPlan(),
			   'objectives' => $objectives,
			   'keyphrase' => $this->keyphrase,
			   'message' => $message,
			);
   }
	
	public function editObjectivesAction()
	{
		$this->loginDetails();
		
		//get the objectives id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new ObjectivesForm();
			$planningModel = new Objectives();
			$form->bind($planningModel);
			
			$five_year = NULL;
			$five_year_plan = $this->planningService->getFiveYearPlan();
			foreach($five_year_plan as $key => $value){
				$five_year = $key; 
			 }
			 
			$objectives = $this->planningService->getRubObjectives($table='rub_objectives_weightage', $five_year);
			$objectivesDetail = $this->planningService->findObjectives($id);
	
			$request = $this->getRequest();
			if ($request->isPost()) {
				$form->setData($request->getPost());
				//extract the data from the form
				$data = $this->getRequest()->getPost('rubobjectives'); 
				$id = $data['id'];
				$weightage = $data['weightage'];
				$five_year_plan = $data['five_year_plan']; 
				$total_weightage = $this->planningService->getObjectiveWeightage($id, $five_year_plan, NULL, NULL);
							 
				if($total_weightage+$weightage > 100){
					//ensure that the objective weight does not exceed 100
					$this->flashMessenger()->addMessage('Error');
					return $this->redirect()->toRoute('objectives');
					
				} else{
					if ($form->isValid()) {
						try {
							   $this->planningService->saveObjectivesWeightage($planningModel);
							   $this->flashMessenger()->addMessage('Objectives was successfully edited');
							   $this->auditTrailService->saveAuditTrail("EDIT", "Objectives was edited", "ALL", "SUCCESS");
							   return $this->redirect()->toRoute('objectives');
					   }
					   catch(\Exception $e) {
									   die($e->getMessage());
									   // Some DB Error happened, log it and let the user know
					   }
					}
				}
			}
		   return array(
				   'form' => $form,
				   'objectivesDetail' => $objectivesDetail,
				   'objectives' => $objectives,
				   'keyphrase' => $this->keyphrase,
				   'five_year_plan' => $five_year_plan);
		} 
		else {
			return $this->redirect()->toRoute('objectives');
		}	
   }


   public function addOvcObjectiveWeightageAction()
   {
   		$this->loginDetails(); 

   		$form = new ObjectivesWeightageForm();
   		$planningModel = new ObjectivesWeightage();
   		$form->bind($planningModel);

   		$five_year = NULL;
		$five_year_plan = $this->planningService->getFiveYearPlan();
		foreach($five_year_plan as $key => $value){
			$five_year = $key; 
		}

		$rub_objectives = $this->planningService->listRubObjectives($five_year);
		$departments = $this->planningService->listSelectData('departments', 'department_name', NULL);

		$objectives = $this->planningService->getOVCObjectives($table='rub_objectives_weightage', $five_year, $this->organisation_id);

		$message = NULL;

   		$request = $this->getRequest();
   		if($request->isPost()){
   			$form->setData($request->getPost());	
	   			$data = $this->getRequest()->getPost('ovcobjectiveweightage'); 
	   			$rub_objectives_id = $data['rub_objectives_id'];
	   			$five_year_plan_id = $data['five_year_plan_id'];
	   			$departments_id = $data['departments_id'];
	   			$weightage = $data['weightage'];
	   			$check_objective = $this->planningService->crossCheckOVCObjective(NULL, $rub_objectives_id, $five_year_plan_id, $departments_id, NULL);
	   			$total_weightage = $this->planningService->getOVCObjectiveWeightage(NULL, $five_year_plan_id, $departments_id);

	   			if($check_objective){
	   				$message = 'Failure';
	   				$this->flashMessenger()->addMessage("You have already added similar objective for the same five year of this particular department. Please try for different!");
	   			}else{ 
   					if($form->isValid()){ 
	   					try{
		   					$this->planningService->saveOVCObjectivesWeightage($planningModel);
		   					$this->flashMessenger()->addMessage('Objective weightage was successfully added');
						    $this->auditTrailService->saveAuditTrail("INSERT", "RUB Objectives Weightage", "ALL", "SUCCESS");
		   					return $this->redirect()->toRoute('addovcobjectiveweightage');
	   					}catch(\Exception $e){
		   					die($e->getMessage());
		   			}
   				}
   			}
   		}

   		return array(
   			'form' => $form,
   			'rub_objectives' => $rub_objectives,
   			'departments' => $departments,
   			'objectives' => $objectives,
   			'organisation_id' => $this->organisation_id,
   			'keyphrase' => $this->keyphrase,
   			'message' => $message,
   			'five_year_plan' => $five_year_plan,
   		);
   }


   public function editOvcObjectiveWeightageAction()
   {
   		$this->loginDetails();
		
		//get the objectives id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new ObjectivesWeightageForm();
			$planningModel = new ObjectivesWeightage();
			$form->bind($planningModel);
			
			$five_year = NULL;
			$five_year_plan = $this->planningService->getFiveYearPlan();
			foreach($five_year_plan as $key => $value){
				$five_year = $key; 
			 }
			 
			$rub_objectives = $this->planningService->listRubObjectives($five_year);
			$departments = $this->planningService->listSelectData('departments', 'department_name', NULL);

			$objectives = $this->planningService->getOVCObjectives($table='rub_objectives_weightage', $five_year, $this->organisation_id);
			$objectivesDetail = $this->planningService->findObjectives($id);

			$message = NULL;
	
			$request = $this->getRequest();
   			if($request->isPost()){
   				$form->setData($request->getPost());	
	   			$data = $this->getRequest()->getPost('ovcobjectiveweightage'); 
	   			$rub_objectives_id = $data['rub_objectives_id'];
	   			$five_year_plan_id = $data['five_year_plan_id'];
	   			$organisation_id = $data['organisation_id'];
	   			$departments_id = $data['departments_id'];
	   			$weightage = $data['weightage']; 
	   			$financial_year = $data['financial_year']; 
	   			$check_objective = $this->planningService->crossCheckOVCObjective($id, $rub_objectives_id, $five_year_plan_id, $departments_id, $financial_year);
	   			$total_weightage = $this->planningService->getOVCObjectiveWeightage($id, $five_year_plan_id, $departments_id);

	   			if($check_objective){
	   				$message = 'Failure';
	   				$this->flashMessenger()->addMessage("You have already added similar objective for the same five year of this particular department. Please try to edit objectives for this particular objective!");
	   			}else{ 
   					if($form->isValid()){
	   					try{
		   					$this->planningService->updateOVCObjectivesWeightage($planningModel);
		   					$this->flashMessenger()->addMessage('Objective weightage was successfully edited');
						    $this->auditTrailService->saveAuditTrail("EDIT", "RUB Objectives Weightage", "ALL", "SUCCESS");
		   					return $this->redirect()->toRoute('addovcobjectiveweightage');
	   					}catch(\Exception $e){
		   					die($e->getMessage());
		   			}
   				}
   			}
   		}

   		return array(
   			'form' => $form,
   			'objectivesDetail' => $objectivesDetail,
   			'rub_objectives' => $rub_objectives,
   			'departments' => $departments,
   			'objectives' => $objectives,
   			'organisation_id' => $this->organisation_id,
   			'keyphrase' => $this->keyphrase,
   			'message' => $message,
   			'five_year_plan' => $five_year_plan,
   		);
   		} 
		else {
			return $this->redirect()->toRoute('addovcobjectiveweightage');
		}	
   }


	 
	public function addRubActivitiesAction()
	{
		$this->loginDetails();
		
		$form = new ActivitiesForm();
		$planningModel = new Activities();
		$form->bind($planningModel);

		$rubObjectives = $this->planningService->listSelectData('rub_objectives', 'objectives', NULL);
		$rub_activities = $this->planningService->listAll('rub_activities', NULL);
		$message = NULL;

		 $request = $this->getRequest();
		 if ($request->isPost()) {
			 $form->setData($request->getPost());

			 $data = $this->getRequest()->getPost('rubactivities');
			$rub_objective = $data['rub_objectives_id'];
			$activity_name = $data['activity_name'];

			$check_activity = $this->planningService->crossCheckFiveYearOActivity($rub_objective, $activity_name);
			if($check_activity){
				$message = 'Failure';
				$this->flashMessenger()->addMessage("You have added similar activity name for the same objective. Please try different.");
			}else{
				if ($form->isValid()) {
				 try {
						$this->planningService->saveRubActivities($planningModel);
						$this->flashMessenger()->addMessage('RUB Activity was added');
						$this->auditTrailService->saveAuditTrail("INSERT", "RUB Activity was added", "ALL", "SUCCESS");
						return $this->redirect()->toRoute('rubactivities');
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
				'rub_activities' => $rub_activities,
				'rubObjectives' => $rubObjectives,
				'keyphrase' => $this->keyphrase,
				'message' => $message
		 );
	}

	public function editRubActivitiesAction()
	{
		$this->loginDetails();
		
		//get the RUB activities id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new ActivitiesForm();
			$planningModel = new Activities();
			$form->bind($planningModel);
	
			$rubObjectives = $this->planningService->listSelectData('rub_objectives', 'objectives', NULL);
			$rub_activities = $this->planningService->listAll('rub_activities', NULL);
			$rubActivitiesDetail = $this->planningService->getDetailsById('rub_activities', $id);
			$message = NULL;
	
			 $request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
							$this->planningService->saveRubActivities($planningModel);
							$this->flashMessenger()->addMessage('RUB Activity was successfully edited');
							$this->auditTrailService->saveAuditTrail("EDIT", "RUB Activities was edited", "ALL", "SUCCESS");
							return $this->redirect()->toRoute('rubactivities');
					}
					catch(\Exception $e) {
							$message = 'Failure';
							$this->flashMessenger()->addMessage($e->getMessage());
							die();
							// Some DB Error happened, log it and let the user know
					}
				 }
			 }
	
			 return array(
					'form' => $form,
					'rub_activities' => $rub_activities,
					'rubObjectives' => $rubObjectives,
					'rubActivitiesDetail' => $rubActivitiesDetail,
					'keyphrase' => $this->keyphrase,
					'message' => $message
			 );
		} 
		else {
			return $this->redirect()->toRoute('rubactivities');
		}

	}

	public function deleteRubActivitiesAction()
	{

	}
    
	public function kpiAction()
	{
		$this->loginDetails();

		$form = new PlanningForm();
		return array('form' => $form);
	}
	
	public function addApaDatesAction()
	{
		$this->loginDetails();
		
		$form = new ApaActivationForm();
		$ApaActivationModel = new ApaActivation();
		$form->bind($ApaActivationModel);
						
		$activationDates = $this->planningService->getActivationDates($id=NULL);
		$five_year_plan = $this->planningService->getFiveYearPlan();
		foreach($five_year_plan as $key => $value){
			$five_year = $value; 
		 }
		$message = NULL;

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid()) {
				try {
					   $this->planningService->saveApaDates($ApaActivationModel);
					   $this->flashMessenger()->addMessage('APA Dates was added');
					   $this->auditTrailService->saveAuditTrail("INSERT", "APA Dates were added", "ALL", "SUCCESS");
					   return $this->redirect()->toRoute('apadates');
			   }
			   catch(\Exception $e) {
					   $message = 'Failure';
					   $this->flashMessenger()->addMessage($e->getMessage());
					   die();
					   // Some DB Error happened, log it and let the user know
			   }
			}
		}

		return array(
		   'form' => $form,
		   'activationDates' => $activationDates,
		   'five_year_plan' => $five_year,
		   'keyphrase' => $this->keyphrase,
		   'message' => $message
		);
	}
	
	public function editApaDatesAction()
	{
		$this->loginDetails();
		
		//get the id of the dates
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new ApaActivationForm();
			$ApaActivationModel = new ApaActivation();
			$form->bind($ApaActivationModel);
							
			$activationDates = $this->planningService->getActivationDates($id);
			$five_year_plan = $this->planningService->getFiveYearPlan();
			foreach($five_year_plan as $key => $value){
				$five_year = $value; 
			 }
			$message = NULL;
	
			$request = $this->getRequest();
			if ($request->isPost()) {
				$form->setData($request->getPost());
				if ($form->isValid()) {
					try {
						   $this->planningService->saveApaDates($ApaActivationModel);
						   $this->flashMessenger()->addMessage('APA Dates was Edited');
						   $this->auditTrailService->saveAuditTrail("EDIT", "APA Dates was edited", "ALL", "SUCCESS");
						   return $this->redirect()->toRoute('apadates');
				   }
				   catch(\Exception $e) {
						   $message = 'Failure';
						   $this->flashMessenger()->addMessage($e->getMessage());
						   die();
						   // Some DB Error happened, log it and let the user know
				   }
				}
			}
	
			return array(
				   'form' => $form,
				   'activationDates' => $activationDates,
				   'five_year_plan' => $five_year,
				   'keyphrase' => $this->keyphrase,
				   'message' => $message
			);
		} 
		else {
			return $this->redirect()->toRoute('apadates');
		}		
		
	}
	
	/*
	* The action is for setting the Activities for each Objective
	*/
	
	public function activitiesAction()
	{
		$this->loginDetails();
		
		$form = new AwpaObjectivesActivityForm();
		$planningModel = new AwpaObjectives();
		$form->bind($planningModel);
		
		$supervisor_ids = $this->planningService->getSupervisorIds($this->employee_details_id, 'Executive', $this->organisation_id);
		$activities = $this->planningService->listAll($tableName='awpa_objectives_activity', $supervisor_ids);
		$last_submission_date = $this->planningService->getLastDateApa();
		//$rub_objectives = $this->planningService->listSelectData($tableName='rub_objectives', $columnName = 'objectives', $role= NULL);
		//use supervisorRoles for selecting the supervisor when adding activities
		$supervisorRoles = $this->planningService->listSupervisorRoles($supervisor_ids);

		$apa_deadline = $this->planningService->getApaDeadline('APA'); 
		
		/*
		* RUB Objectives are objectives for VC and Directors/Presidents etc.
		* Activities of Directors/Presidents are objectives for staff and so on.
		*/
		//$objectives = $this->planningService->listSupervisorObjectives($supervisor_ids, $type = NULL);
		//this is for the drop down list

		$five_year = NULL;
		$five_year_plan = $this->planningService->getFiveYearPlan();
		foreach($five_year_plan as $key => $value){
			$five_year = $key; 
		 }

		 $supervisor_dept_id = $this->planningService->getSupervisorDeptIds($supervisor_ids[0]); 
		 
		$rub_objectives = $this->planningService->getRubObjectivesWeightage($table='rub_objectives_weightage', $five_year, $this->organisation_id, $supervisor_dept_id);

		$objectivesSelect = $this->planningService->listSupervisorObjectives($supervisor_ids, $this->organisation_id);		
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid()) {
				try {
						$this->planningService->saveActivities($planningModel);
						$this->flashMessenger()->addMessage('Activities was successfully added');
						$this->auditTrailService->saveAuditTrail("INSERT", "Activities was added", "ALL", "SUCCESS");
						return $this->redirect()->toRoute('activities');
				}
				catch(\Exception $e) {
								die($e->getMessage());
								// Some DB Error happened, log it and let the user know
				}
			}
		}
		   return array(
					'form' => $form,
					'rub_objectives' => $rub_objectives,
					'selectData' => $objectivesSelect,
					//'objectives' => $objectives,
					'apa_deadline' => $apa_deadline,
					'employee_details_id' => $this->employee_details_id,
					'activities' => $activities,
					'supervisorRoles' => $supervisorRoles,
					'last_submission_date' => $last_submission_date,
					'keyphrase' => $this->keyphrase,
					//'username' => $this->username,
				);
	}
	
	
    
	//Activities for VC
	public function addVcActivitiesAction()
	{
		$this->loginDetails();
		
		$form = new AwpaObjectivesActivityForm();
		$planningModel = new AwpaObjectives();
		$form->bind($planningModel);

		$supervisor_ids = $this->planningService->getSupervisorIds($this->employee_details_id, 'VICE_CHANCELLOR', $this->organisation_id);
		$activities = $this->planningService->listAll($tableName='awpa_objectives_activity', $supervisor_ids);
		$last_submission_date = $this->planningService->getLastDateApa();
		
		//$rub_objectives = $this->planningService->listSelectData($tableName='rub_objectives', $columnName = 'objectives', $role= NULL);
		//use supervisorRoles for selecting the supervisor when adding activities
		$supervisorRoles = $this->planningService->listSupervisorRoles($supervisor_ids);
		/*
		* RUB Objectives are objectives for VC and Directors/Presidents etc.
		* Activities of Directors/Presidents are objectives for staff and so on.
		*/
		$five_year = NULL;
		$five_year_plan = $this->planningService->getFiveYearPlan();
		foreach($five_year_plan as $key => $value){
			$five_year = $key; 
		 }

		$supervisor_dept_id = $this->planningService->getSupervisorDeptIds($supervisor_ids[0]);
		 
		$rub_objectives = $this->planningService->getRubObjectivesWeightage($table='rub_objectives_weightage', $five_year, $this->organisation_id, $supervisor_dept_id);
        
        $message = NULL;

		$apa_deadline = $this->planningService->getApaDeadline('APA');
		//$objectives = $this->planningService->listSupervisorObjectives($supervisor_ids, $type = NULL);
		//this is for the drop down list
		$objectivesSelect = $this->planningService->listSupervisorObjectives($supervisor_ids, $this->organisation_id);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid()) {
				try {
						$this->planningService->saveActivities($planningModel);
						$this->flashMessenger()->addMessage('Activities was successfully added');
						$this->auditTrailService->saveAuditTrail("INSERT", "VC Activities was added", "ALL", "SUCCESS");
						return $this->redirect()->toRoute('addvcactivities');
				}
				catch(\Exception $e) {
								die($e->getMessage());
								// Some DB Error happened, log it and let the user know
				}
			}
		}
	   return array(
				'form' => $form,
				'rub_objectives' => $rub_objectives,
				'selectData' => $objectivesSelect,
				'apa_deadline' => $apa_deadline,
				//'objectives' => $objectives,
				'employee_details_id' => $this->employee_details_id,
				'activities' => $activities,
				'supervisorRoles' => $supervisorRoles,
				'last_submission_date' => $last_submission_date,
				'keyphrase' => $this->keyphrase,
				'username' => $this->username,
				'message' => $message,
			);
	}


	public function editActivitiesAction()
	{
		$this->loginDetails();
		
		//get the Activities id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new AwpaObjectivesActivityForm();
			$planningModel = new AwpaObjectives();
			$form->bind($planningModel);
					
			$activitiesDetail = $this->planningService->getDetailsById('awpa_objectives_activity', $id);
		
			//we extract the supervisor id from the database based on the $id
			$supervisor_ids = NULL;
			foreach($activitiesDetail as $value){
				$supervisor_ids = $value['employee_details_id'];
			}
					
			$activities = $this->planningService->listAll($tableName='awpa_objectives_activity', $supervisor_ids);
			$last_submission_date = $this->planningService->getLastDateApa();
			//$rub_objectives = $this->planningService->listSelectData($tableName='rub_objectives', $columnName = 'objectives', $role= NULL);
			//use supervisorRoles for selecting the supervisor when adding activities
			$supervisorRoles = $this->planningService->listSupervisorRoles($supervisor_ids);
			/*
			* RUB Objectives are objectives for VC and Directors/Presidents etc.
			* Activities of Directors/Presidents are objectives for staff and so on.
			*/
			//$objectives = $this->planningService->listSupervisorObjectives($supervisor_ids, $type = NULL);
			//this is for the drop down list
			$objectivesSelect = $this->planningService->listSupervisorObjectives($supervisor_ids, $this->organisation_id);

			$five_year = NULL;
			$five_year_plan = $this->planningService->getFiveYearPlan();
			foreach($five_year_plan as $key => $value){
				$five_year = $key; 
			 }
		 
		$rub_objectives = $this->planningService->getVisionMission($table='rub_objectives', $five_year);
        
        $message = NULL;
			
			
			$request = $this->getRequest();
			if ($request->isPost()) {
				$form->setData($request->getPost());
				if ($form->isValid()) {
					try {
							$this->planningService->saveActivities($planningModel);
							$this->flashMessenger()->addMessage('Activities was successfully edited');
							$this->auditTrailService->saveAuditTrail("EDIT", "Activities was edited", "ALL", "SUCCESS");
							return $this->redirect()->toRoute('editactivities', array('id' => $this->my_encrypt($id, $this->keyphrase)));
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
					'activitiesDetail' => $activitiesDetail,
					'rub_objectives' => $rub_objectives,
					'selectData' => $objectivesSelect,
					//'objectives' => $objectives,
					'employee_details_id' => $this->employee_details_id,
					'activities' => $activities,
					'supervisorRoles' => $supervisorRoles,
					'last_submission_date' => $last_submission_date,
					'keyphrase' => $this->keyphrase,
					'username' => $this->username,
					'message' => $message,
				);
		} 
		else {
			return $this->redirect()->toRoute('activities');
		}
		
	}

    
	public function evaluateApaAction()
	{
		$this->loginDetails();
		
		$supervisor_ids = $this->planningService->getSupervisorIds($this->employee_details_id, 'Executive', $this->organisation_id);
		//use supervisorRoles for selecting the supervisor when adding activities
		$supervisorRoles = $this->planningService->listSupervisorRoles($supervisor_ids);

		$apa_deadline = $this->planningService->getApaDeadline('Annual Review (APA)');

		//List of the KPI activities 		
		$kpi = $this->planningService->listAllEvaluation($table = 'awpa_activities', $supervisor_ids[0]);

		$kpiList = $this->planningService->listAllEvaluation($table = 'awpa_activities', $supervisor_ids[0]);
		$kpi_form = array(); //to store the id of the kpis
		foreach($kpiList as $tmp){
				$kpi_form[$tmp['id']] = $tmp['id'];
		}

		$form = new EvaluationForm($kpi_form);

		$request = $this->getRequest();
		 if ($request->isPost()) {
			 $form->setData($request->getPost());
			 if ($form->isValid()) {
					$data = $this->extractFormData($kpi_form);
					try {
							$this->planningService->saveApaEvaluation($data);
							$this->flashMessenger()->addMessage('APA Evaluation was successfully');
							$this->auditTrailService->saveAuditTrail("INSERT", "APA Evaluation was added", "ALL", "SUCCESS");
							return $this->redirect()->toRoute('apaselfevaluated');
					}
					catch(\Exception $e) {
									die($e->getMessage());
									// Some DB Error happened, log it and let the user know
					}
			 }
		 }
		return array(
					'form' => $form,
					'kpi' => $kpi, 
					'apa_deadline' => $apa_deadline,
					'keyphrase' => $this->keyphrase,
					'employee_details_id' => $this->employee_details_id);
	}

	public function evaluateVcApaAction()
	{
		$this->loginDetails();

		$supervisor_ids = $this->planningService->getSupervisorIds($this->employee_details_id, 'VICE_CHANCELLOR', $this->organisation_id);
		//use supervisorRoles for selecting the supervisor when adding activities
		$supervisorRoles = $this->planningService->listSupervisorRoles($supervisor_ids);

		$apa_deadline = $this->planningService->getApaDeadline('Annual Review (APA)');

		//List of the KPI activities 		
		$kpi = $this->planningService->listAll($table = 'awpa_activities', $supervisor_ids[0]);

		$kpiList = $this->planningService->listAll($table = 'awpa_activities', $supervisor_ids[0]);
		$kpi_form = array(); //to store the id of the kpis
		foreach($kpiList as $tmp){
				$kpi_form[$tmp['id']] = $tmp['id'];
		}

		$form = new EvaluationForm($kpi_form);

		$request = $this->getRequest();
		 if ($request->isPost()) {
			 $form->setData($request->getPost());
			 if ($form->isValid()) {
					$data = $this->extractFormData($kpi_form);
					try {
							$this->planningService->saveApaEvaluation($data);
							$this->flashMessenger()->addMessage('APA Evaluation was successfully');
							$this->auditTrailService->saveAuditTrail("INSERT", "VC APA Evaluation was added", "ALL", "SUCCESS");
							return $this->redirect()->toRoute('apaselfevaluated');
					}
					catch(\Exception $e) {
									die($e->getMessage());
									// Some DB Error happened, log it and let the user know
					}
			 }
		 }
		return array(
				'form' => $form,
				'kpi' => $kpi, 
				'apa_deadline' => $apa_deadline,
				'keyphrase' => $this->keyphrase,
				'employee_details_id' => $this->employee_details_id);
	}
	
	public function apaSelfEvaluatedAction()
	{
		$this->loginDetails();

		$supervisor_ids = $this->planningService->getSupervisorIds($this->employee_details_id, 'Executive', $this->organisation_id);
		
		$selfEvaluation = $this->planningService->getSelfEvaluation($supervisor_ids);
		$selfEvaluatedIndicators = $this->planningService->getSelfEvaluation($supervisor_ids); 

		//$selfEvaluation = $this->planningService->getSelfEvaluation($this->employee_details_id);
		//$selfEvaluatedIndicators = $this->planningService->getSelfEvaluation($this->employee_details_id); 
		return array(
				'selfEvaluation' => $selfEvaluation,
				'selfEvaluatedIndicators' => $selfEvaluatedIndicators);
	}
	
	public function viewFiveYearPlanAction()
	{
		$this->loginDetails();

		$form = new SearchForm();

		$five_year = NULL;
		$five_year_plan = $this->planningService->getFiveYearPlan();
		foreach($five_year_plan as $key => $value){
				$five_year = $key; 
		 }

		$visionDetail = $this->planningService->getVisionMission($table='rub_vision', $five_year);
		$missionDetail = $this->planningService->getVisionMission($table='rub_mission', $five_year);
		$objectivesDetail = $this->planningService->getVisionMission($table='rub_objectives', $five_year);
		$kpi = $this->planningService->listAll($table = 'awpa_activities', $employee_details_id = NULL);
		$fiveYearPlanList = $this->planningService->listSelectData('five_year_plan', 'five_year_plan', NULL);

		$request = $this->getRequest();
		 if ($request->isPost()) {
			 $form->setData($request->getPost());
			 if ($form->isValid()) {
				$five_year = $this->getRequest()->getPost('five_year_plan');
				$visionDetail = $this->planningService->getVisionMission($table='rub_vision', $five_year);
				$missionDetail = $this->planningService->getVisionMission($table='rub_mission', $five_year);
				$objectivesDetail = $this->planningService->getVisionMission($table='rub_objectives', $five_year);
			 }
		 }

		return array(
			'form' => $form,
			'vision' => $visionDetail,
			'mission' => $missionDetail,
			'objectives' => $objectivesDetail,
			'five_year_plan' => $five_year_plan,
			'kpi' => $kpi,
			'fiveYearPlanList' => $fiveYearPlanList);
	}
    
	/*
	* The action is for setting the KPI
	* KPI is also known as Success Indicator
	* Each activity has a KPI
	*/
	
	public function successIndicatorAction()
	{
		$this->loginDetails();
				
		$form = new AwpaActivitiesForm();
		$planningModel = new AwpaActivities();
		$form->bind($planningModel);

		$supervisor_ids = $this->planningService->getSupervisorIds($this->employee_details_id, 'Executive', $this->organisation_id);
		//use supervisorRoles for selecting the supervisor when adding activities
		$supervisorRoles = $this->planningService->listSupervisorRoles($supervisor_ids);

		$supervisor_dept_id = $this->planningService->getSupervisorDeptIds($supervisor_ids[0]);

		//Need to send value of the table name and columns
		$objectivesSelect = $this->planningService->listSelectData($tableName='awpa_objectives_activity', $columnName='activity_name', $supervisor_ids[0]);
		$last_submission_date = $this->planningService->getLastDateApa();

		//Data to fill up the table
		//only Temporary as it will be redirected to View Vision Mission Page
		// Just to check whether data is being inserted or not
		$kpi = $this->planningService->listAll($table = 'awpa_activities', $supervisor_ids[0]);

		$apa_deadline = $this->planningService->getApaDeadline('APA');

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());

			/*
			 * This function is no longer needed. Values may exceed
			 */
			//extract the data from the form
			$data = $this->getRequest()->getPost('awpaactivities');
			$awpa_objectives_activity_id = $data['awpa_objectives_activity_id'];
			$new_indicator_weight = $data['weight'];
			$total_objective_weightage = $this->planningService->getObjectiveWeightage($awpa_objectives_activity_id, NULL, $this->organisation_id, $supervisor_dept_id);
			$total_indicator_weightage = $this->planningService->getIndicatorWeightage($awpa_objectives_activity_id, NULL);
						 
			if($new_indicator_weight+$total_indicator_weightage > $total_objective_weightage){
				//ensure that the objective weight does not exceed Total Objective Weightage
				$this->flashMessenger()->addMessage('Error');
				return $this->redirect()->toRoute('successindicator');
				
			} else{
			
				if ($form->isValid()) {
					try {
						   $this->planningService->saveKpi($planningModel);
						   $this->flashMessenger()->addMessage('Success Indicator was successfully added');
						   $this->auditTrailService->saveAuditTrail("INSERT", "Success Indicator was added", "ALL", "SUCCESS");
						   return $this->redirect()->toRoute('successindicator');
				   }
				   catch(\Exception $e) {
								   die($e->getMessage());
								   // Some DB Error happened, log it and let the user know
				   }
				}
			}
			
		}
		return array(
					'form' => $form,
					'selectData' => $objectivesSelect,
					'supervisorRoles' => $supervisorRoles,
					'apa_deadline' => $apa_deadline,
					'kpi' => $kpi,
					'last_submission_date' => $last_submission_date,
					'keyphrase' => $this->keyphrase,
					'employee_details_id'=> $this->employee_details_id);
	}
	
	public function editSuccessIndicatorAction()
	{
		$this->loginDetails();
				
		//get the Success Indicator id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new AwpaActivitiesForm();
			$planningModel = new AwpaActivities();
			$form->bind($planningModel);
	
			$successIndicatorDetail = $this->planningService->getDetailsById('awpa_activities', $id);
	
			//we extract the supervisor id from the database based on the $id
			$supervisor_ids = NULL;
			foreach($successIndicatorDetail as $value){
				$supervisor_ids = $value['employee_details_id'];
			}

			$supervisor_dept_id = $this->planningService->getSupervisorDeptIds($supervisor_ids);
			
			//use supervisorRoles for selecting the supervisor when adding activities
			$supervisorRoles = $this->planningService->listSupervisorRoles($supervisor_ids);
	
			//Need to send value of the table name and columns
			$objectivesSelect = $this->planningService->listSelectData($tableName='awpa_objectives_activity', $columnName='activity_name', $supervisor_ids);
			$last_submission_date = $this->planningService->getLastDateApa();
	
			//Data to fill up the table
			//only Temporary as it will be redirected to View Vision Mission Page
			// Just to check whether data is being inserted or not
			$kpi = $this->planningService->listAll($table = 'awpa_activities', $supervisor_ids);

			$message = NULL;
	
			$request = $this->getRequest();
			if ($request->isPost()) {
				$form->setData($request->getPost());
				//extract the data from the form
				$data = $this->getRequest()->getPost('awpaactivities');
				$awpa_objectives_activity_id = $data['awpa_objectives_activity_id'];
				$new_indicator_weight = $data['weight'];
				$total_objective_weightage = $this->planningService->getObjectiveWeightage($awpa_objectives_activity_id, NULL, $this->organisation_id, $supervisor_dept_id);
				$total_indicator_weightage = $this->planningService->getIndicatorWeightage($awpa_objectives_activity_id, $id);
							 
				if($new_indicator_weight+$total_indicator_weightage > $total_objective_weightage){
					//ensure that the objective weight does not exceed Total Objective Weightage
					$this->flashMessenger()->addMessage('Weight is more then allocated!');
					return $this->redirect()->toRoute('editsuccessindicator', array('id' => $this->my_encrypt($id, $this->keyphrase)));
					
				} else{
					if ($form->isValid()) {
						try {
							   $this->planningService->saveKpi($planningModel);
							   $this->flashMessenger()->addMessage('Success Indicator was successfully edited');
							   $this->auditTrailService->saveAuditTrail("EDIT", "Success Indicator was edited", "ALL", "SUCCESS");
							   return $this->redirect()->toRoute('editsuccessindicator', array('id' => $this->my_encrypt($id, $this->keyphrase)));
					   }
					   catch(\Exception $e) {
									   die($e->getMessage());
									   // Some DB Error happened, log it and let the user know
					   }
					}
				}
			}
			return array(
					'form' => $form,
					'successIndicatorDetail' => $successIndicatorDetail,
					'selectData' => $objectivesSelect,
					'supervisorRoles' => $supervisorRoles,
					'kpi' => $kpi,
					'last_submission_date' => $last_submission_date,
					'keyphrase' => $this->keyphrase,
					'employee_details_id'=> $this->employee_details_id,
					'message' => $message,
				);
		} 
		else {
			return $this->redirect()->toRoute('successindicator');
		}

	}


	public function addVcSuccessIndicatorAction()
	{
		$this->loginDetails();
				
		$form = new AwpaActivitiesForm();
		$planningModel = new AwpaActivities();
		$form->bind($planningModel);

		$supervisor_ids = $this->planningService->getSupervisorIds($this->employee_details_id, 'VICE_CHANCELLOR', $this->organisation_id); 
		//use supervisorRoles for selecting the supervisor when adding activities
		$supervisorRoles = $this->planningService->listSupervisorRoles($supervisor_ids);

		//Need to send value of the table name and columns
		$objectivesSelect = $this->planningService->listSelectData($tableName='awpa_objectives_activity', $columnName='activity_name', $supervisor_ids[0]);
		$last_submission_date = $this->planningService->getLastDateApa();

		$supervisor_dept_id = $this->planningService->getSupervisorDeptIds($supervisor_ids[0]);
		//Data to fill up the table
		//only Temporary as it will be redirected to View Vision Mission Page
		// Just to check whether data is being inserted or not
		$kpi = $this->planningService->listAll($table = 'awpa_activities', $supervisor_ids[0]);

		$apa_deadline = $this->planningService->getApaDeadline('APA');

		$request = $this->getRequest();
		 if ($request->isPost()) {
				$form->setData($request->getPost());
				//extract the data from the form
				$data = $this->getRequest()->getPost('awpaactivities');
				$awpa_objectives_activity_id = $data['awpa_objectives_activity_id'];
				$new_indicator_weight = $data['weight'];
				$total_objective_weightage = $this->planningService->getObjectiveWeightage($awpa_objectives_activity_id, NULL, $this->organisation_id, $supervisor_dept_id);
				$total_indicator_weightage = $this->planningService->getIndicatorWeightage($awpa_objectives_activity_id, NULL);

				if($new_indicator_weight+$total_indicator_weightage > $total_objective_weightage){
					//ensure that the objective weight does not exceed Total Objective Weightage
					$this->flashMessenger()->addMessage('Error');
					return $this->redirect()->toRoute('addvcsuccessindicator');
					
				} else{
					if ($form->isValid()) {
						try {
							   $this->planningService->saveKpi($planningModel);
							   $this->flashMessenger()->addMessage('Success Indicator was successfully added');
							   $this->auditTrailService->saveAuditTrail("INSERT", "VC Success Indicator was added", "ALL", "SUCCESS");
							   return $this->redirect()->toRoute('addvcsuccessindicator');
					   }
					   catch(\Exception $e) {
									   die($e->getMessage());
									   // Some DB Error happened, log it and let the user know
					   }
					}
				}
			}
		return array(
					'form' => $form,
					'selectData' => $objectivesSelect,
					'supervisorRoles' => $supervisorRoles,
					'apa_deadline' => $apa_deadline,
					'kpi' => $kpi,
					'last_submission_date' => $last_submission_date,
					'keyphrase' => $this->keyphrase,
					'employee_details_id'=> $this->employee_details_id);
	}

	//Function to add key aspiration
	public function addVcKeyAspirationAction()
	{
		$this->loginDetails();
				
		$form = new KeyAspirationForm();
		$planningModel = new KeyAspiration();
		$form->bind($planningModel);

		$supervisor_ids = $this->planningService->getSupervisorIds($this->employee_details_id, 'VICE_CHANCELLOR', $this->organisation_id); 
		//use supervisorRoles for selecting the supervisor when adding activities
		$supervisorRoles = $this->planningService->listSupervisorRoles($supervisor_ids);
		//var_dump($supervisorRoles); die();
		//Need to send value of the table name and columns
		//$objectivesSelect = $this->planningService->listSelectData($tableName='awpa_objectives_activity', $columnName='activity_name', $supervisor_ids[0]);
		$last_submission_date = $this->planningService->getLastDateApa();

		$supervisor_dept_id = $this->planningService->getSupervisorDeptIds($supervisor_ids[0]);
		//Data to fill up the table
		//only Temporary as it will be redirected to View Vision Mission Page
		// Just to check whether data is being inserted or not
		$key_aspiration = $this->planningService->listAll($table = 'awpa_key_aspiration', $supervisor_ids[0]);

		$apa_deadline = $this->planningService->getApaDeadline('APA');

		$request = $this->getRequest();
		 if ($request->isPost()) {
				$form->setData($request->getPost());
					if ($form->isValid()) { 
						try {
							$this->planningService->saveKeyAspiration($planningModel);
							$this->flashMessenger()->addMessage('Key Aspiration was successfully added');
							$this->auditTrailService->saveAuditTrail("INSERT", "VC  Key Aspiration was added", "ALL", "SUCCESS");
							return $this->redirect()->toRoute('addvckeyaspiration');
					   }
					   catch(\Exception $e) {
									   die($e->getMessage());
									   // Some DB Error happened, log it and let the user know
					   }
					}
			}
		return array(
					'form' => $form,
					//'selectData' => $objectivesSelect,
					'supervisorRoles' => $supervisorRoles,
					'apa_deadline' => $apa_deadline,
					'key_aspiration' => $key_aspiration,
					'last_submission_date' => $last_submission_date,
					'keyphrase' => $this->keyphrase,
					'employee_details_id'=> $this->employee_details_id);
	}


	public function addExecutiveKeyAspirationAction()
	{
		$this->loginDetails();
				
		$form = new KeyAspirationForm();
		$planningModel = new KeyAspiration();
		$form->bind($planningModel);

		$supervisor_ids = $this->planningService->getSupervisorIds($this->employee_details_id, 'Executive', $this->organisation_id);
		//use supervisorRoles for selecting the supervisor when adding activities
		$supervisorRoles = $this->planningService->listSupervisorRoles($supervisor_ids);

		$supervisor_dept_id = $this->planningService->getSupervisorDeptIds($supervisor_ids[0]);

		$last_submission_date = $this->planningService->getLastDateApa();

		//Data to fill up the table
		//only Temporary as it will be redirected to View Vision Mission Page
		// Just to check whether data is being inserted or not
		$key_aspiration = $this->planningService->listAll($table = 'awpa_key_aspiration', $supervisor_ids[0]);

		$apa_deadline = $this->planningService->getApaDeadline('APA');

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
				if ($form->isValid()) {
					try {
						   $this->planningService->saveKeyAspiration($planningModel);
						   $this->flashMessenger()->addMessage('Key Aspiration was successfully added');
						   $this->auditTrailService->saveAuditTrail("INSERT", "Executive  Key Aspiration was added", "ALL", "SUCCESS");
						   return $this->redirect()->toRoute('addexecutivekeyaspiration');
				   }
				   catch(\Exception $e) {
								   die($e->getMessage());
								   // Some DB Error happened, log it and let the user know
				   }
				}
			
		}
		return array(
					'form' => $form,
					'supervisorRoles' => $supervisorRoles,
					'apa_deadline' => $apa_deadline,
					'key_aspiration' => $key_aspiration,
					'last_submission_date' => $last_submission_date,
					'keyphrase' => $this->keyphrase,
					'employee_details_id'=> $this->employee_details_id);
	}



	public function editKeyAspirationAction()
	{
		$this->loginDetails();
				
		//get the Success Indicator id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){ 
			$form = new KeyAspirationForm();
			$planningModel = new KeyAspiration();
			$form->bind($planningModel);
	
			$keyAspirationDetail = $this->planningService->getDetailsById('awpa_key_aspiration', $id);
			
			//we extract the supervisor id from the database based on the $id
			$supervisor_ids = NULL;
			foreach($keyAspirationDetail as $value){
				$supervisor_ids = $value['employee_details_id'];
			}
			
			$supervisor_dept_id = $this->planningService->getSupervisorDeptIds($supervisor_ids);
			
			//use supervisorRoles for selecting the supervisor when adding activities
			$supervisorRoles = $this->planningService->listSupervisorRoles($supervisor_ids);

			$last_submission_date = $this->planningService->getLastDateApa();
	
			//Data to fill up the table
			//only Temporary as it will be redirected to View Vision Mission Page
			// Just to check whether data is being inserted or not
			$key_aspiration = $this->planningService->listAll($table = 'awpa_key_aspiration', $supervisor_ids);

			$message = NULL;
	
			$request = $this->getRequest();
			if ($request->isPost()) {
				$form->setData($request->getPost());
					if ($form->isValid()) { 
						try {
							$this->planningService->saveKeyAspiration($planningModel);
							$this->flashMessenger()->addMessage('Key Aspiration was successfully edited');
							$this->auditTrailService->saveAuditTrail("EDIT", "Key Aspiration was edited", "ALL", "SUCCESS");
							return $this->redirect()->toRoute('editkeyaspiration', array('id' => $this->my_encrypt($id, $this->keyphrase)));
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
				'keyAspirationDetail' => $keyAspirationDetail,
				'supervisorRoles' => $supervisorRoles,
				'key_aspiration' => $key_aspiration,
				'last_submission_date' => $last_submission_date,
				'keyphrase' => $this->keyphrase,
				'employee_details_id'=> $this->employee_details_id,
				'message' => $message,
			);
		} 
		else {
			return $this->redirect()->toRoute('editkeyaspiration');
		}
	}


	
	public function addSuccessIndicatorTrendAction()
	{
		$this->loginDetails();
		
		$form = new SuccessIndicatorTrendForm();
		$planningModel = new SuccessIndicatorTrend();
		$form->bind($planningModel);

		$supervisor_ids = $this->planningService->getSupervisorIds($this->employee_details_id, 'Executive', $this->organisation_id);
		//use supervisorRoles for selecting the supervisor when adding activities
		$supervisorRoles = $this->planningService->listSupervisorRoles($supervisor_ids);

		//Need to send value of the table name and columns
		$objectivesSelect = $this->planningService->listSelectData($tableName='awpa_objectives_activity', $columnName='activity_name', $supervisor_ids[0]);
		$last_submission_date = $this->planningService->getLastDateApa();
		$five_year_plan = $this->planningService->getFiveYearPlan();
		$five_year = array();
		foreach($five_year_plan as $key=> $value){
				$five_year_id = $key;
				$five_year = $this->planningService->findFiveYearPlan($five_year_id);
		}
		$successIndicatorTrend = $this->planningService->getSuccessIndicatorVariables('success_indicator_trend_values', $supervisor_ids[0]);

		$apa_deadline = $this->planningService->getApaDeadline('APA');

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid()) {
				try {
					   $this->planningService->saveSuccessIndicatorTrend($planningModel);
					   $this->flashMessenger()->addMessage('Success Indicator Trend was successfully added');
					   $this->auditTrailService->saveAuditTrail("INSERT", "Success Indicator Trend was added", "ALL", "SUCCESS");
					   return $this->redirect()->toRoute('addsuccessindicatortrend');
			   }
			   catch(\Exception $e) {
							   die($e->getMessage());
							   // Some DB Error happened, log it and let the user know
			   }
			}
		}
	   return array(
						'form' => $form,
						'selectData' => $objectivesSelect,
						'successIndicatorTrend' => $successIndicatorTrend,
						'apa_deadline' => $apa_deadline,
						'supervisorRoles' => $supervisorRoles,
						'last_submission_date' => $last_submission_date,
						'five_year' => $five_year,
						'keyphrase' => $this->keyphrase,
						'employee_details_id'=> $this->employee_details_id);
	}

	public function editSuccessIndicatorTrendAction()
	{
		$this->loginDetails();
		
		//get the Success Indicator id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new SuccessIndicatorTrendForm();
			$planningModel = new SuccessIndicatorTrend();
			$form->bind($planningModel); 
			
			$successTrendDetail = $this->planningService->getDetailsById('success_indicator_trend_values', $id);
			//we extract the awpa id to get the supervisor id
			$awpa_activities_id = NULL;
			foreach($successTrendDetail as $value){
				$awpa_activities_id = $value['awpa_activities_id'];
			}
			
			$successIndicatorDetail = $this->planningService->getDetailsById('awpa_objectives_activity', $awpa_activities_id);
	
			//we extract the supervisor id from the database based on the $id
			$supervisor_ids = NULL;
			foreach($successIndicatorDetail as $value){
				$supervisor_ids = $value['employee_details_id'];
			}
	
			
			//use supervisorRoles for selecting the supervisor when adding activities
			$supervisorRoles = $this->planningService->listSupervisorRoles($supervisor_ids);		
			//Need to send value of the table name and columns
			$objectivesSelect = $this->planningService->listSelectData($tableName='awpa_objectives_activity', $columnName='activity_name', $supervisor_ids);
			$last_submission_date = $this->planningService->getLastDateApa();
			$five_year_plan = $this->planningService->getFiveYearPlan();
			$five_year = array();
			foreach($five_year_plan as $key=> $value){
					$five_year_id = $key;
					$five_year = $this->planningService->findFiveYearPlan($five_year_id);
			} 
			$successIndicatorTrend = $this->planningService->getSuccessIndicatorVariables('success_indicator_trend_values', $supervisor_ids); 
	
			$request = $this->getRequest();
			if ($request->isPost()) {
				$form->setData($request->getPost());
				if ($form->isValid()) {
					try {
						   $this->planningService->saveSuccessIndicatorTrend($planningModel);
						   $this->flashMessenger()->addMessage('Success Indicator Trend was successfully edited');
						   $this->auditTrailService->saveAuditTrail("EDIT", "Success Indicator Trend was edited", "ALL", "SUCCESS");
						   return $this->redirect()->toRoute('editsuccessindicatortrend', array('id' => $this->my_encrypt($id, $this->keyphrase)));
				   }
				   catch(\Exception $e) {
								   die($e->getMessage());
								   // Some DB Error happened, log it and let the user know
				   }
				}
			}
		   return array(
					'form' => $form,
					'successTrendDetail' => $successTrendDetail,
					'selectData' => $objectivesSelect,
					'successIndicatorTrend' => $successIndicatorTrend,
					'supervisorRoles' => $supervisorRoles,
					'last_submission_date' => $last_submission_date,
					'five_year' => $five_year,
					'keyphrase' => $this->keyphrase,
					'employee_details_id'=> $this->employee_details_id);
		} 
		else {
			return $this->redirect()->toRoute('addsuccessindicatortrend');
		}
		
	}

	public function addVcSuccessIndicatorTrendAction()
	{
		$this->loginDetails();
		
		$form = new SuccessIndicatorTrendForm();
		$planningModel = new SuccessIndicatorTrend();
		$form->bind($planningModel);

		$supervisor_ids = $this->planningService->getSupervisorIds($this->employee_details_id, 'VICE_CHANCELLOR', $this->organisation_id);
		//use supervisorRoles for selecting the supervisor when adding activities
		$supervisorRoles = $this->planningService->listSupervisorRoles($supervisor_ids);

		//Need to send value of the table name and columns
		$objectivesSelect = $this->planningService->listSelectData($tableName='awpa_objectives_activity', $columnName='activity_name', $supervisor_ids[0]);
		$last_submission_date = $this->planningService->getLastDateApa();
		$five_year_plan = $this->planningService->getFiveYearPlan();
		$five_year = array();
		foreach($five_year_plan as $key=> $value){
				$five_year_id = $key;
				$five_year = $this->planningService->findFiveYearPlan($five_year_id);
		}
		$successIndicatorTrend = $this->planningService->getSuccessIndicatorVariables('success_indicator_trend_values', $supervisor_ids[0]);

		$apa_deadline = $this->planningService->getApaDeadline('APA');

		$request = $this->getRequest();
		 if ($request->isPost()) {
			 $form->setData($request->getPost());
			 if ($form->isValid()) {
				 try {
						$this->planningService->saveSuccessIndicatorTrend($planningModel);
						$this->flashMessenger()->addMessage('Success Indicator Trend was successfully added');
						$this->auditTrailService->saveAuditTrail("INSERT", "VC Success Indicator Trend was added", "ALL", "SUCCESS");
						return $this->redirect()->toRoute('addvcsuccessindicatortrend');
				}
				catch(\Exception $e) {
								die($e->getMessage());
								// Some DB Error happened, log it and let the user know
				}
			 }
		 }
		return array(
				'form' => $form,
				'selectData' => $objectivesSelect,
				'successIndicatorTrend' => $successIndicatorTrend,
				'apa_deadline' => $apa_deadline,
				'supervisorRoles' => $supervisorRoles,
				'last_submission_date' => $last_submission_date,
				'five_year' => $five_year,
				'keyphrase' => $this->keyphrase,
				'employee_details_id'=> $this->employee_details_id);
	}

	public function addSuccessIndicatorDefinitionAction()
	{
		$this->loginDetails();
		
		$form = new SuccessIndicatorDefinitionForm();
		$planningModel = new SuccessIndicatorDefinition();
		$form->bind($planningModel);

		$supervisor_ids = $this->planningService->getSupervisorIds($this->employee_details_id, 'Executive', $this->organisation_id);
		//use supervisorRoles for selecting the supervisor when adding activities
		$supervisorRoles = $this->planningService->listSupervisorRoles($supervisor_ids);

		//Need to send value of the table name and columns
		$objectivesSelect = $this->planningService->listSelectData($tableName='awpa_objectives_activity', $columnName='activity_name', $supervisor_ids[0]);
		$last_submission_date = $this->planningService->getLastDateApa();
		$successIndicatorDefinitions = $this->planningService->getSuccessIndicatorVariables('success_indicator_definition', $supervisor_ids[0]);

		$apa_deadline = $this->planningService->getApaDeadline('APA');

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid()) {
				try {
					   $this->planningService->saveSuccessIndicatorDefinition($planningModel);
					   $this->flashMessenger()->addMessage('Success Indicator Definition was successfully added');
					   $this->auditTrailService->saveAuditTrail("INSERT", "Success Indicator Definition was added", "ALL", "SUCCESS");
					   return $this->redirect()->toRoute('addsuccessindicatordefinition');
			   }
			   catch(\Exception $e) {
							   die($e->getMessage());
							   // Some DB Error happened, log it and let the user know
			   }
			}
		}
	   return array(
			   'form' => $form,
			   'selectData' => $objectivesSelect,
			   'successIndicatorDefinitions' => $successIndicatorDefinitions,
			   'apa_deadline' => $apa_deadline,
			   'supervisorRoles' => $supervisorRoles,
			   'last_submission_date' => $last_submission_date,
			   'keyphrase' => $this->keyphrase,
			   'employee_details_id'=> $this->employee_details_id);
	}

	public function editSuccessIndicatorDefinitionAction()
	{
		$this->loginDetails();
		
		//get the Success Indicator id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new SuccessIndicatorDefinitionForm();
			$planningModel = new SuccessIndicatorDefinition();
			$form->bind($planningModel);
			
			$successDefinitionDetail = $this->planningService->getDetailsById('success_indicator_definition', $id);
			//we extract the awpa id to get the supervisor id
			$awpa_activities_id = NULL;
			foreach($successDefinitionDetail as $value){
				$awpa_activities_id = $value['awpa_activities_id'];
			}
			
			$successIndicatorDetail = $this->planningService->getDetailsById('awpa_objectives_activity', $awpa_activities_id);
	
			//we extract the supervisor id from the database based on the $id
			$supervisor_ids = NULL;
			foreach($successIndicatorDetail as $value){
				$supervisor_ids = $value['employee_details_id'];
			}
	
			
			//use supervisorRoles for selecting the supervisor when adding activities
			$supervisorRoles = $this->planningService->listSupervisorRoles($supervisor_ids);
	
			//Need to send value of the table name and columns
			$objectivesSelect = $this->planningService->listSelectData($tableName='awpa_objectives_activity', $columnName='activity_name', $supervisor_ids);
			$last_submission_date = $this->planningService->getLastDateApa();
			$successIndicatorDefinitions = $this->planningService->getSuccessIndicatorVariables('success_indicator_definition', $supervisor_ids);
	
			$request = $this->getRequest();
			if ($request->isPost()) {
				$form->setData($request->getPost());
				if ($form->isValid()) {
					try {
						   $this->planningService->saveSuccessIndicatorDefinition($planningModel);
						   $this->flashMessenger()->addMessage('Success Indicator Definition was successfully edited');
						   $this->auditTrailService->saveAuditTrail("EDIT", "Success Indicator Definition was edited", "ALL", "SUCCESS");
						   return $this->redirect()->toRoute('editsuccessindicatordefinition', array('id' => $this->my_encrypt($id, $this->keyphrase)));
				   }
				   catch(\Exception $e) {
								   die($e->getMessage());
								   // Some DB Error happened, log it and let the user know
				   }
				}
			}
		   return array(
				   'form' => $form,
				   'successDefinitionDetail' => $successDefinitionDetail,
				   'selectData' => $objectivesSelect,
				   'successIndicatorDefinitions' => $successIndicatorDefinitions,
				   'supervisorRoles' => $supervisorRoles,
				   'last_submission_date' => $last_submission_date,
				   'keyphrase' => $this->keyphrase,
				   'employee_details_id'=> $this->employee_details_id);
		} 
		else {
			return $this->redirect()->toRoute('addsuccessindicatordefinition');
		}

		
	}

	public function addVcSuccessIndicatorDefinitionAction()
	{
		$this->loginDetails();
		
		$form = new SuccessIndicatorDefinitionForm();
		$planningModel = new SuccessIndicatorDefinition();
		$form->bind($planningModel);

		$supervisor_ids = $this->planningService->getSupervisorIds($this->employee_details_id, 'VICE_CHANCELLOR', $this->organisation_id);
		//use supervisorRoles for selecting the supervisor when adding activities
		$supervisorRoles = $this->planningService->listSupervisorRoles($supervisor_ids);

		//Need to send value of the table name and columns
		$objectivesSelect = $this->planningService->listSelectData($tableName='awpa_objectives_activity', $columnName='activity_name', $supervisor_ids[0]);
		$last_submission_date = $this->planningService->getLastDateApa();
		$successIndicatorDefinitions = $this->planningService->getSuccessIndicatorVariables('success_indicator_definition', $supervisor_ids[0]);

		$apa_deadline = $this->planningService->getApaDeadline('APA');

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid()) {
				try {
					   $this->planningService->saveSuccessIndicatorDefinition($planningModel);
					   $this->flashMessenger()->addMessage('Success Indicator Definition was successfully added');
					   $this->auditTrailService->saveAuditTrail("INSERT", "VC Success Indicator Definition was added", "ALL", "SUCCESS");
					   return $this->redirect()->toRoute('addvcsuccessindicatordefinition');
			   }
			   catch(\Exception $e) {
							   die($e->getMessage());
							   // Some DB Error happened, log it and let the user know
			   }
			}
		}
	   return array(
			'form' => $form,
			'selectData' => $objectivesSelect,
			'successIndicatorDefinitions' => $successIndicatorDefinitions,
			'apa_deadline' => $apa_deadline,
			'supervisorRoles' => $supervisorRoles,
			'last_submission_date' => $last_submission_date,
			'keyphrase' => $this->keyphrase,
			'employee_details_id'=> $this->employee_details_id);
	}

	public function addSuccessIndicatorRequirementsAction()
	{
		$this->loginDetails();
		
		$form = new SuccessIndicatorRequirementsForm();
		$planningModel = new SuccessIndicatorRequirements();
		$form->bind($planningModel);

		$supervisor_ids = $this->planningService->getSupervisorIds($this->employee_details_id, 'Executive', $this->organisation_id);
		//use supervisorRoles for selecting the supervisor when adding activities
		$supervisorRoles = $this->planningService->listSupervisorRoles($supervisor_ids);

		//Need to send value of the table name and columns
		$objectivesSelect = $this->planningService->listSelectData($tableName='awpa_objectives_activity', $columnName='activity_name', $supervisor_ids[0]);
		$last_submission_date = $this->planningService->getLastDateApa();
		$successIndicatorRequirements = $this->planningService->getSuccessIndicatorVariables('success_indicator_requirements', $supervisor_ids[0]);

		$apa_deadline = $this->planningService->getApaDeadline('APA');

		$message = NULL;

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid()) {
				try {
					   $this->planningService->saveSuccessIndicatorRequirements($planningModel);
					   $this->flashMessenger()->addMessage('Success Indicator Requirement was successfully added');
					   $this->auditTrailService->saveAuditTrail("INSERT", "Success Indicator Requirement was added", "ALL", "SUCCESS");
					   return $this->redirect()->toRoute('addsuccessindicatorrequirements');
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
			'selectData' => $objectivesSelect,
			'successIndicatorRequirements' => $successIndicatorRequirements,
			'apa_deadline' => $apa_deadline,
			'supervisorRoles' => $supervisorRoles, 
			'last_submission_date' => $last_submission_date,
			'keyphrase' => $this->keyphrase,
			'employee_details_id'=> $this->employee_details_id,
			'message' => $message,
		);
	}

	public function editSuccessIndicatorRequirementsAction()
	{
		$this->loginDetails();
		
		//get the Success Indicator id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new SuccessIndicatorRequirementsForm();
			$planningModel = new SuccessIndicatorRequirements();
			$form->bind($planningModel);
	
			$successRequirementsDetail = $this->planningService->getDetailsById('success_indicator_requirements', $id);
			//we extract the awpa id to get the supervisor id
			$awpa_activities_id = NULL;
			foreach($successRequirementsDetail as $value){
				$awpa_activities_id = $value['awpa_activities_id'];
			}
	
			$successIndicatorDetail = $this->planningService->getDetailsById('awpa_objectives_activity', $awpa_activities_id);
	
			//we extract the supervisor id from the database based on the $id
			$supervisor_ids = NULL;
			foreach($successIndicatorDetail as $value){
				$supervisor_ids = $value['employee_details_id'];
			}
			//use supervisorRoles for selecting the supervisor when adding activities
			$supervisorRoles = $this->planningService->listSupervisorRoles($supervisor_ids);
	
			//Need to send value of the table name and columns
			$objectivesSelect = $this->planningService->listSelectData($tableName='awpa_objectives_activity', $columnName='activity_name', $supervisor_ids);
			$last_submission_date = $this->planningService->getLastDateApa();
			$successIndicatorRequirements = $this->planningService->getSuccessIndicatorVariables('success_indicator_requirements', $supervisor_ids);
	
			$request = $this->getRequest();
			if ($request->isPost()) {
				$form->setData($request->getPost());
				if ($form->isValid()) {
					try {
						   $this->planningService->saveSuccessIndicatorRequirements($planningModel);
						   $this->flashMessenger()->addMessage('Success Indicator Requirement was successfully edited');
						   $this->auditTrailService->saveAuditTrail("EDIT", "Success Indicator Requirement was eidted", "ALL", "SUCCESS");
						   return $this->redirect()->toRoute('editsuccessindicatorrequirements', array('id' => $this->my_encrypt($id, $this->keyphrase)));
				   }
				   catch(\Exception $e) {
								   die($e->getMessage());
								   // Some DB Error happened, log it and let the user know
				   }
				}
			}
		   return array(
				'form' => $form,
				'successRequirementsDetail' => $successRequirementsDetail,
				'selectData' => $objectivesSelect,
				'successIndicatorRequirements' => $successIndicatorRequirements,
				'supervisorRoles' => $supervisorRoles, 
				'last_submission_date' => $last_submission_date,
				'keyphrase' => $this->keyphrase,
				'employee_details_id'=> $this->employee_details_id);
		} 
		else {
			return $this->redirect()->toRoute('addsuccessindicatorrequirements');
		}

		
	}

	public function addVcSuccessIndicatorRequirementsAction()
	{
		$this->loginDetails();
		
		$form = new SuccessIndicatorRequirementsForm();
		$planningModel = new SuccessIndicatorRequirements();
		$form->bind($planningModel);

		$supervisor_ids = $this->planningService->getSupervisorIds($this->employee_details_id, 'VICE_CHANCELLOR', $this->organisation_id);
		//use supervisorRoles for selecting the supervisor when adding activities
		$supervisorRoles = $this->planningService->listSupervisorRoles($supervisor_ids);

		//Need to send value of the table name and columns
		$objectivesSelect = $this->planningService->listSelectData($tableName='awpa_objectives_activity', $columnName='activity_name', $supervisor_ids[0]);
		$last_submission_date = $this->planningService->getLastDateApa();
		$successIndicatorRequirements = $this->planningService->getSuccessIndicatorVariables('success_indicator_requirements', $supervisor_ids[0]);

		$apa_deadline = $this->planningService->getApaDeadline('APA');

		$request = $this->getRequest();
		 if ($request->isPost()) {
			 $form->setData($request->getPost());
			 if ($form->isValid()) {
				 try {
						$this->planningService->saveSuccessIndicatorRequirements($planningModel);
						$this->flashMessenger()->addMessage('Success Indicator Requirement was successfully added');
						$this->auditTrailService->saveAuditTrail("INSERT", "VC Success Indicator Requirement was added", "ALL", "SUCCESS");
						return $this->redirect()->toRoute('addvcsuccessindicatorrequirements');
				}
				catch(\Exception $e) {
								die($e->getMessage());
								// Some DB Error happened, log it and let the user know
				}
			 }
		 }
		return array(
				'form' => $form,
				'selectData' => $objectivesSelect,
				'successIndicatorRequirements' => $successIndicatorRequirements,
				'apa_deadline' => $apa_deadline,
				'supervisorRoles' => $supervisorRoles,
				'last_submission_date' => $last_submission_date,
				'keyphrase' => $this->keyphrase,
				'employee_details_id'=> $this->employee_details_id);
	}

	public function applyMidTermReviewAction()
	{
		$this->loginDetails();
		
		$form = new AwpaActivitiesForm();
		$planningModel = new AwpaActivities();
		$form->bind($planningModel);

		$supervisor_ids = $this->planningService->getSupervisorIds($this->employee_details_id, 'Executive', $this->organisation_id);
		//use supervisorRoles for selecting the supervisor when adding activities
		$supervisorRoles = $this->planningService->listSupervisorRoles($supervisor_ids);

		//Need to send value of the table name and columns
		$objectivesSelect = $this->planningService->listSelectData($tableName='awpa_objectives_activity', $columnName='activity_name', $supervisor_ids[0]);

		//Data to fill up the table
		//only Temporary as it will be redirected to View Vision Mission Page
		// Just to check whether data is being inserted or not
		$kpi = $this->planningService->listAllEvaluation($table = 'awpa_activities', $supervisor_ids[0]);

		$apa_deadline = $this->planningService->getApaDeadline('Mid-Term Review (APA)');

				$request = $this->getRequest();
		 if ($request->isPost()) {
			 $form->setData($request->getPost());
			 if ($form->isValid()) {
				 try {
						$this->planningService->saveKpi($planningModel);
						$this->flashMessenger()->addMessage('Mid Term Review was successful');
						$this->auditTrailService->saveAuditTrail("INSERT", "Mid Term Review was added", "ALL", "SUCCESS");
						return $this->redirect()->toRoute('midtermreview');
				}
				catch(\Exception $e) {
								die($e->getMessage());
								// Some DB Error happened, log it and let the user know
				}
			 }
		 }
		return array(
				'form' => $form,
				'kpi' => $kpi,
				'apa_deadline' => $apa_deadline,
				'keyphrase' => $this->keyphrase,
				'employee_details_id'=> $supervisor_ids[0]);
	}

	public function applyVcMidTermReviewAction()
	{
		$this->loginDetails();
		
		$form = new AwpaActivitiesForm();
		$planningModel = new AwpaActivities();
		$form->bind($planningModel);

		$supervisor_ids = $this->planningService->getSupervisorIds($this->employee_details_id, 'VICE_CHANCELLOR', $this->organisation_id);
		//use supervisorRoles for selecting the supervisor when adding activities
		$supervisorRoles = $this->planningService->listSupervisorRoles($supervisor_ids);

		//Need to send value of the table name and columns
		$objectivesSelect = $this->planningService->listSelectData($tableName='awpa_objectives_activity', $columnName='activity_name', $supervisor_ids[0]);

		//Data to fill up the table
		//only Temporary as it will be redirected to View Vision Mission Page
		// Just to check whether data is being inserted or not
		$kpi = $this->planningService->listAll($table = 'awpa_activities', $supervisor_ids[0]);

		$apa_deadline = $this->planningService->getApaDeadline('Mid-Term Review (APA)');

		$request = $this->getRequest();
		 if ($request->isPost()) {
			 $form->setData($request->getPost());
			 if ($form->isValid()) {
				 try {
						$this->planningService->saveKpi($planningModel);
						$this->flashMessenger()->addMessage('Mid Term Review was successfully added');
						$this->auditTrailService->saveAuditTrail("INSERT", "VC Mid Term Review was added", "ALL", "SUCCESS");
						return $this->redirect()->toRoute('midtermreview');
				}
				catch(\Exception $e) {
								die($e->getMessage());
								// Some DB Error happened, log it and let the user know
				}
			 }
		 }
		return array(
					'form' => $form,
					'kpi' => $kpi,
					'apa_deadline' => $apa_deadline,
					'keyphrase' => $this->keyphrase,
					'employee_details_id'=> $this->employee_details_id);
	}

	public function addMidTermReviewAction()
	{
		$this->loginDetails();

		//mid term review id from route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new AwpaActivitiesForm();
			$planningModel = new AwpaActivities();
			$form->bind($planningModel);
	
			//Need to send value of the table name and columns
			$objectivesSelect = $this->planningService->listSelectData($tableName='awpa_objectives_activity', $columnName='activity_name', $this->employee_details_id);
	
			//Data to fill up the table
			//only Temporary as it will be redirected to View Vision Mission Page
			// Just to check whether data is being inserted or not
			$kpi = $this->planningService->listAll($table = 'awpa_activities', $this->employee_details_id);
	
			//get list of activity and populate the form
			$listActivities = $this->planningService->findActivities($id);
	
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
							$this->planningService->saveMidTermReview($planningModel, $id);
							$this->flashMessenger()->addMessage('Mid Term Review was successfully added');
							$this->auditTrailService->saveAuditTrail("INSERT", "Mid Term Review was added", "ALL", "SUCCESS");
							return $this->redirect()->toRoute('midtermreview');
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
					'selectData' => $objectivesSelect,
					'listActivities' => $listActivities,
					'kpi' => $kpi,
					'keyphrase' => $this->keyphrase,
					'employee_details_id'=> $this->employee_details_id);
		} 
		else {
			return $this->redirect()->toRoute('successindicator');
		}
		
	}


	public function addBudgetOverlayAction()
	{
		$this->loginDetails();
		
		$form = new BudgetOverlayForm();
		$planningModel = new BudgetOverlay();
		$form->bind($planningModel);

		//$objectives = $this->planningService->listSupervisorObjectives($this->role, $type = NULL);
		//this is for the drop down list
		$objectivesSelect = $this->planningService->listSupervisorObjectives($this->role, $type = 'dropdown');
		$budgetOverlay = $this->planningService->getBudgetOverlay('budget_overlay', NULL);


		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid()) {
				try {
						$this->planningService->saveBudgetOverlay($planningModel);
						$this->flashMessenger()->addMessage('Budget Overlay was successfully added');
						$this->auditTrailService->saveAuditTrail("INSERT", "Budget Overlay was added", "ALL", "SUCCESS");
						return $this->redirect()->toRoute('budgetoverlay');
				}
				catch(\Exception $e) {
								die($e->getMessage());
								// Some DB Error happened, log it and let the user know
				}
			}
		}

		return array(
				'form' => $form,
				'selectData' => $objectivesSelect,
				//'$objectives' => $objectives,
				'budgetOverlay' => $budgetOverlay,
				'keyphrase' => $this->keyphrase,
				'employee_details_id'=> $this->employee_details_id);


	}

	public function addOrganisationBudgetOverlayAction()
	{
		$this->loginDetails();
		
		$form = new OrganisationBudgetOverlayForm();
		$planningModel = new OrganisationBudgetOverlay();
		$form->bind($planningModel);

		//$objectives = $this->planningService->listSupervisorObjectives($this->role, $type = NULL);
		//this is for the drop down list
		$objectivesSelect = $this->planningService->listSupervisorObjectives($this->role, $type = 'dropdown');
		$organisationList = $this->planningService->listSelectData('organisation', 'organisation_name', NULL);
		$budgetOverlay = $this->planningService->getBudgetOverlay('organisation_budget_overlay', NULL);


		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid()) {
				try {
						$this->planningService->saveOrganisationBudgetOverlay($planningModel);
						$this->flashMessenger()->addMessage('Budget Overlay was successfully added');
						$this->auditTrailService->saveAuditTrail("INSERT", "Organisational Budget Overlay was added", "ALL", "SUCCESS");
						return $this->redirect()->toRoute('organisationbudgetoverlay');
				}
				catch(\Exception $e) {
								die($e->getMessage());
								// Some DB Error happened, log it and let the user know
				}
			}
		}

		return array(
				'form' => $form,
				'selectData' => $objectivesSelect,
				//'$objectives' => $objectives,
				'organisationList' => $organisationList,
				'budgetOverlay' => $budgetOverlay,
				'keyphrase' => $this->keyphrase,
				'employee_details_id'=> $this->employee_details_id);
	}

	//the following function is to extract the data from the form 
	// and return clean data to be inserted into database
	public function extractFormData($kpi_form)
	{
		$evaluationData = array();

		//evaluation data => 'evaluation_'.$i.$j,
		foreach($kpi_form as $key=>$value)
		{
				$evaluationData[$key]['status']= $this->getRequest()->getPost('status_'.$key);
				$evaluationData[$key]['verification_means']= $this->getRequest()->getPost('verification_means_'.$key);
				$evaluationData[$key]['evaluation']= $this->getRequest()->getPost('evaluation_'.$key);
		}
		return $evaluationData;
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

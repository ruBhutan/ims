<?php

namespace Planning\Service;

//use Planning\Model\Planning;
use Planning\Model\Vision;
use Planning\Model\Mission;
use Planning\Model\Objectives;
use Planning\Model\ObjectivesWeightage;
use Planning\Model\Activities;
use Planning\Model\AwpaObjectives;
use Planning\Model\AwpaActivities;
use Planning\Model\KeyAspiration;
use Planning\Model\FiveYearPlan;
use Planning\Model\ApaActivation;
use Planning\Model\SuccessIndicatorDefinition;
use Planning\Model\SuccessIndicatorTrend;
use Planning\Model\SuccessIndicatorRequirements;
use Planning\Model\BudgetOverlay;
use Planning\Model\OrganisationBudgetOverlay;

//need to add more models

interface PlanningServiceInterface
{
	/*
	* Getting the id for username
	*/
	
	public function getEmployeeDetailsId($emp_id);
	
	/*
	* Get organisation id based on the username
	*/
	
	public function getOrganisationId($username);
	
	/*
	* take username and returns employee details id/student id
	*/
	
	public function getUserDetailsId($username, $tableName);
	
	/*
	* take username and returns Name and any other detail required
	*/
	
	public function getUserDetails($username, $usertype);

	public function getUserImage($username, $usertype);
	
	/*
	* Get the position title of the user
	*/
		
	public function getPositionTitle($employee_details_id);
	
	/*
	* Get Five Year Plan
	*/
	
	public function getFiveYearPlan();

	public function crossCheckFiveYearPlan($five_year_plan);

	public function crossCheckFiveYearVision($five_year, $vision);

	public function crossCheckFiveYearMission($five_year, $mission);

	public function crossCheckFiveYearObjective($five_year_plan, $objectives);

	public function crossCheckFiveYearOActivity($rub_objective, $activity_name);

	public function crossCheckOVCObjective($id, $rub_objectives_id, $five_year_plan_id, $departments_id, $financial_year);

	public function getOVCObjectiveWeightage($id, $five_year_plan_id, $departments_id);
	
	/*
	* Get the Vision and Mission for a given Five Year Plan
	*/
	
	public function getVisionMission($table_name, $five_year_plan);

	public function getRubObjectives($table_name, $five_year_plan);

	public function getRubObjectivesWeightage($table_name, $five_year_plan, $organisation_id, $supervisor_dept_id);


	public function getOVCObjectives($tableName, $five_year_plan, $organisation_id);
		
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|PlanningInterface[]
	*/
	
	public function listAll($tableName, $employee_details_id);

	public function listAllEvaluation($tableName, $employee_details_id);
	
	
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|PlanningInterface[]
	*/
	
	public function listSupervisorObjectives($supervisor_ids, $organisation_id);

	/**
	 * Should return a single proposal
	 *
	 * @param int $id Identifier of the proposal that should be returned
	 * @return PlanningInterface
	 */
	 
	public function findVisionMission($table_name, $id);
        
        
	/**
	 * Should return a single proposal
	 *
	 * @param int $id Identifier of the Proposal that should be returned
	 * @return PlanningInterface
	 */
        
        public function findObjectives($id);
	 
	 /**
	 * @param PlanningInterface $planningObject
	 */
	 
	 public function saveVision(Vision $planningObject);
	 
	 /**
	 * @param PlanningInterface $planningObject
	 *
	 */
	 
	 public function saveMission(Mission $planningObject);
	 
	 /**
	 * @param PlanningInterface $planningObject
	 *
	 * @param PlanningInterface $planningObject
	 * @return PlanningInterface
	 * @throws \Exception
	 */
	 
	 public function saveObjectives(Objectives $planningObject);

	 public function saveObjectivesWeightage(Objectives $planningObject);

	 public function saveOVCObjectivesWeightage(ObjectivesWeightage $planningObject);

	 public function updateOVCObjectivesWeightage(ObjectivesWeightage $planningObject);
         
         /*
          * Save RUB Activities
          * saves RUB Activities while saveActivities saves individual APA activities
          */
         
         public function saveRubActivities(Activities $planningObject);
	 
	 /**
	 * @param PlanningInterface $planningObject
	 *
	 * @param PlanningInterface $planningObject
	 * @return PlanningInterface
	 * @throws \Exception
	 */
	 
	 public function saveActivities(AwpaObjectives $planningObject);
	 
	 /*
	 * Save the Success Indicator Trend
	 */
	 
	 public function saveSuccessIndicatorTrend(SuccessIndicatorTrend $planningObject);
	 
	 /*
	 * Save the Success Indicator Definition
	 */
	 
	 public function saveSuccessIndicatorDefinition(SuccessIndicatorDefinition $planningObject);
	 
	 /*
	 * Save the Success Indicator Requirements
	 */
	 
	 public function saveSuccessIndicatorRequirements(SuccessIndicatorRequirements $planningObject);
	 
	 
	 /**
	 * Save Five Year Plan
	 *
	 */
	 
	 public function saveFiveYearPlan(FiveYearPlan $planningObject);
	 
	 /*
	 * Save APA Dates
	 */
	 
	 public function saveApaDates(ApaActivation $planningObject);
	 
	 /*
	 * Find Five Year Plan Details
	 */
	 
	 public function findFiveYearPlan($id);
	 
	 /*
	 * Find Activity List
	 */
	 public function findActivities($id);
	 
	 /*
	 * Activity Detail when editing the Activity Objectives
	 */
	 
	 public function findObjectivesActivity($id);
         
         /*
          * Generic function to get the details of various tables when editing such as RUB Activities etc.
          */
         
         public function getDetailsById($table_name, $id);
	 
	 /*
	 * Get the APA activation dates
	 */
	 
	 public function getActivationDates($id);
	 
	 /*
	 * Get the last date for APA submission
	 */
	 
	 public function getLastDateApa();
         
         /*
          * Get the weight of the objectives
          */
         
         public function getObjectiveWeightage($id, $five_year_plan, $organisation_id, $supervisor_dept_id);
         
         /*
          * Get the weight of the Success Indicator
          */
         
         public function getIndicatorWeightage($awpa_objectives_activity_id, $id);
	 
	 /**
	 * @param PlanningInterface $planningObject
	 *
	 * @param PlanningInterface $planningObject
	 * @return PlanningInterface
	 * @throws \Exception
	 */
	 
	 public function saveKpi(AwpaActivities $planningObject);

	 public function saveKeyAspiration(KeyAspiration $planningObject);
	 
	 public function saveMidTermReview(AwpaActivities $planningObject, $id);
	 
	 /*
	 * Save the APA self evaluation
	 */
	 
	 public function saveApaEvaluation($data);
         
         /*
          * Save Budget Overlay
          */
         
         public function saveBudgetOverlay(BudgetOverlay $planningObject);
         
         /*
          * Save Organisation Budget Overlay
          */
         
         public function saveOrganisationBudgetOverlay(OrganisationBudgetOverlay $planningObject);
         
         /*
          * This is a generic function to get the table values for
          * Success Indicator Trends, Requirements and Definitions
          *
          * takes table name and employee details id
          */
         
         public function getSuccessIndicatorVariables($table_name, $employee_details_id);
	 
	 /*
	 * Get the Self Evaluation of APA
	 */
	 
	 public function getSelfEvaluation($employee_details_id);
         
         /*
          * Get the budget overlay
          */
         
         public function getBudgetOverlay($table_name, $organisation_id);
         
         /*
          * Get the ids of the supervisors
          * Only for Planning Division, there will be two , i.e VC and Directors
          * Other departments/organisations will have only one.
          */
         
         public function getSupervisorIds($employee_details_id, $supervisor_role, $organisation_id);

         public function getSupervisorDeptIds($employee_details_id);

         public function getApaDeadline($apa_type);
         
         /*
          * Get the supervisor roles
          * in the same format as listSelectData
          */
         
         public function listSupervisorRoles($supervisor_ids);

         public function listRubObjectives($five_year);
	 
	 /**
	 * Should return a set of all objectives that we can iterate over. 
	 * 
	 * The purpose of the function is the objectives for the dropdown select list
	 *
	 * @return array|PlanningInterface[]
	*/
	
	public function listSelectData($tableName, $columnName, $emp_id);
		
		
}
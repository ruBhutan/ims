<?php

namespace Planning\Mapper;

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

interface PlanningMapperInterface
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
	 * @param int/string $id
	 * @return Planning
	 * throws \InvalidArugmentException
	 * 
	*/
	
	public function findVisionMission($table_name, $id);

	/**
	 * 
	 * @return array/ Planning[]
	 */
	 
	public function findAll($tableName, $employee_details_id);

	public function findAllEvaluation($tableName, $employee_details_id);

	
	/**
	 * 
	 * @return array/ Objectives[]
	 */
	 
	public function listSupervisorObjectives($supervisor_ids, $organisation_id);
        
	/**
	 * 
	 * @param type $id
	 * 
	 * to find details related to the Objectives
	 */
	
	public function findObjectives($id);
	
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
	 * Find Five Year Plan Details
	 */
	 
	 public function findFiveYearPlan($id);
	
	 /**
	 * @param PlanningInterface $planningObject
	 */
	 
	 public function saveVision(Vision $planningInterface);
	 
	 /**
	 * @param PlanningInterface $planningObject
	 *
	 */
	 
	 public function saveMission(Mission $planningInterface);
	
	/**
	 * 
	 * @param type $PlanningInterface
	 * 
	 * to save plannings
	 */
	
	public function saveObjectives(Objectives $PlanningInterface);

	public function saveObjectivesWeightage(Objectives $planningObject);

	public function saveOVCObjectivesWeightage(ObjectivesWeightage $planningObject);


	public function updateOVCObjectivesWeightage(ObjectivesWeightage $planningObject);
        
        /*
          * Save RUB Activities
          * saves RUB Activities while saveActivities saves individual APA activities
          */
         
        public function saveRubActivities(Activities $planningObject);
	 
	
	/**
	 * 
	 * @param type $PlanningInterface
	 * 
	 * to save plannings
	 */
	
	public function saveActivities(AwpaObjectives $PlanningInterface);
	
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
	 * 
	 * @param type $PlanningInterface
	 * 
	 * to save plannings
	 */
	
	public function saveKpi(AwpaActivities $PlanningInterface);

	public function saveKeyAspiration(KeyAspiration $PlanningInterface);
	
	public function saveMidTermReview(AwpaActivities $PlanningInterface, $id);
        
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
	 * Save the APA self evaluation
	 */
	 
	public function saveApaEvaluation($data);
	
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
	 * 
	 * @return array/ Planning[]
	 */
	 
	public function listSelectData($tableName, $columnName, $emp_id);
	
}
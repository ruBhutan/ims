<?php

namespace Planning\Service;

use Planning\Mapper\PlanningMapperInterface;
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

class PlanningService implements PlanningServiceInterface
{
	/**
	 * @var \Blog\Mapper\PostMapperInterface
	*/
	
	protected $planningMapper;
	
	public function __construct(PlanningMapperInterface $planningMapper) {
		$this->planningMapper = $planningMapper;
	}
	
	public function getEmployeeDetailsId($emp_id)
	{
		return $this->planningMapper->getEmployeeDetailsId($emp_id);
	}
	
	public function getOrganisationId($username)
	{
		return $this->planningMapper->getOrganisationId($username);
	}
	
	public function getUserDetailsId($username, $tableName)
	{
		return $this->planningMapper->getUserDetailsId($username, $tableName);
	}
	
	public function getUserDetails($username, $usertype)
	{
		return $this->planningMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->planningMapper->getUserImage($username, $usertype);
	}
	
	public function getPositionTitle($employee_details_id)
	{
		return $this->planningMapper->getPositionTitle($employee_details_id);
	}
		
	public function getFiveYearPlan()
	{
		return $this->planningMapper->getFiveYearPlan();
	}

	public function crossCheckFiveYearPlan($five_year_plan)
	{
		return $this->planningMapper->crossCheckFiveYearPlan($five_year_plan);
	}

	public function crossCheckFiveYearVision($five_year, $vision)
	{
		return $this->planningMapper->crossCheckFiveYearVision($five_year, $vision);
	}

	public function crossCheckFiveYearMission($five_year, $mission)
	{
		return $this->planningMapper->crossCheckFiveYearMission($five_year, $mission);
	}

	public function crossCheckFiveYearObjective($five_year_plan, $objectives)
	{
		return $this->planningMapper->crossCheckFiveYearObjective($five_year_plan, $objectives);
	}

	public function crossCheckFiveYearOActivity($rub_objective, $activity_name)
	{
		return $this->planningMapper->crossCheckFiveYearOActivity($rub_objective, $activity_name);
	}


	public function crossCheckOVCObjective($id, $rub_objectives_id, $five_year_plan_id, $departments_id, $financial_year)
	{
		return $this->planningMapper->crossCheckOVCObjective($id, $rub_objectives_id, $five_year_plan_id, $departments_id, $financial_year);
	}

	public function getOVCObjectiveWeightage($id, $five_year_plan_id, $departments_id)
	{
		return $this->planningMapper->getOVCObjectiveWeightage($id, $five_year_plan_id, $departments_id);
	}

		
	public function getVisionMission($table_name, $five_year_plan)
	{
		return $this->planningMapper->getVisionMission($table_name, $five_year_plan);
	}

	public function getRubObjectives($table_name, $five_year_plan)
	{
		return $this->planningMapper->getRubObjectives($table_name, $five_year_plan);
	}


	public function getRubObjectivesWeightage($table_name, $five_year_plan, $organisation_id, $supervisor_dept_id)
	{
		return $this->planningMapper->getRubObjectivesWeightage($table_name, $five_year_plan, $organisation_id, $supervisor_dept_id);
	}

	public function getOVCObjectives($tableName, $five_year_plan, $organisation_id)
	{
		return $this->planningMapper->getOVCObjectives($tableName, $five_year_plan, $organisation_id);
	}
				
	public function listAll($tableName, $employee_details_id)
	{
		return $this->planningMapper->findAll($tableName, $employee_details_id);
	}

	public function listAllEvaluation($tableName, $employee_details_id)
	{
		return $this->planningMapper->findAllEvaluation($tableName, $employee_details_id);
	}
	
	public function listSupervisorObjectives($supervisor_ids, $organisation_id)
	{
		return $this->planningMapper->listSupervisorObjectives($supervisor_ids, $organisation_id);
	}
	 
	public function findVisionMission($table_name, $id)
	{
		return $this->planningMapper->findVisionMission($table_name, $id);
	}
        
	public function findObjectives($id) 
	{
		return $this->planningMapper->findObjectives($id);;
	}
         
    public function saveRubActivities(Activities $planningObject)
    {
        return $this->planningMapper->saveRubActivities($planningObject);
    }
	
	public function findActivities($id) 
	{
		return $this->planningMapper->findActivities($id);;
	}
		 
	public function findObjectivesActivity($id)
	{
		return $this->planningMapper->findObjectivesActivity($id);
	}
         
        public function getDetailsById($table_name, $id)
        {
                return $this->planningMapper->getDetailsById($table_name, $id);
        }
	 
	public function findFiveYearPlan($id)
	{
		return $this->planningMapper->findFiveYearPlan($id);
	}
	
	public function saveVision(Vision $planningObject)
	{
		return $this->planningMapper->saveVision($planningObject);
	}
	 	 
	public function saveMission(Mission $planningObject)
	{
		return $this->planningMapper->saveMission($planningObject);
	}
	
	public function saveObjectives(Objectives $planningObject) 
	{
		return $this->planningMapper->saveObjectives($planningObject);
	}

	public function saveObjectivesWeightage(Objectives $planningObject)
	{
		return $this->planningMapper->saveObjectivesWeightage($planningObject);
	}


	public function saveOVCObjectivesWeightage(ObjectivesWeightage $planningObject)
	{
		return $this->planningMapper->saveOVCObjectivesWeightage($planningObject);
	}

	public function updateOVCObjectivesWeightage(ObjectivesWeightage $planningObject)
	{
		return $this->planningMapper->updateOVCObjectivesWeightage($planningObject);
	}
	
	public function saveActivities(AwpaObjectives $planningObject) 
	{
		return $this->planningMapper->saveActivities($planningObject);
	}
		 
	public function saveSuccessIndicatorTrend(SuccessIndicatorTrend $planningObject)
	{
		return $this->planningMapper->saveSuccessIndicatorTrend($planningObject);
	}
	 	 
	public function saveSuccessIndicatorDefinition(SuccessIndicatorDefinition $planningObject)
	{
		return $this->planningMapper->saveSuccessIndicatorDefinition($planningObject);
	}
	 	 
	public function saveSuccessIndicatorRequirements(SuccessIndicatorRequirements $planningObject)
	{
		return $this->planningMapper->saveSuccessIndicatorRequirements($planningObject);
	}
		 
	public function saveFiveYearPlan(FiveYearPlan $planningObject)
	{
		return $this->planningMapper->saveFiveYearPlan($planningObject);
	}
		 
	public function saveApaDates(ApaActivation $planningObject)
	{
		return $this->planningMapper->saveApaDates($planningObject);
	}
		 
	public function getActivationDates($id)
	{
		return $this->planningMapper->getActivationDates($id);
	}
		 
	public function getLastDateApa()
	{
		return $this->planningMapper->getLastDateApa();
	}
         
        public function getObjectiveWeightage($id, $five_year_plan, $organisation_id, $supervisor_dept_id)
        {
                return $this->planningMapper->getObjectiveWeightage($id, $five_year_plan, $organisation_id, $supervisor_dept_id);
        }
         
        public function getIndicatorWeightage($awpa_objectives_activity_id, $id)
        {
                return $this->planningMapper->getIndicatorWeightage($awpa_objectives_activity_id, $id);
        }
	
	public function saveKpi(AwpaActivities $planningObject) 
	{
		return $this->planningMapper->saveKpi($planningObject);
	}

	public function saveKeyAspiration(KeyAspiration $planningObject)
	{
		return $this->planningMapper->saveKeyAspiration($planningObject);
	}
	
	public function saveMidTermReview(AwpaActivities $planningObject, $id) 
	{
		return $this->planningMapper->saveMidTermReview($planningObject, $id);
	}
		 
	public function saveApaEvaluation($data)
	{
		return $this->planningMapper->saveApaEvaluation($data);
	}
         
        public function saveBudgetOverlay(BudgetOverlay $planningObject)
        {
            return $this->planningMapper->saveBudgetOverlay($planningObject);
        }
         
        public function saveOrganisationBudgetOverlay(OrganisationBudgetOverlay $planningObject)
        {
            return $this->planningMapper->saveOrganisationBudgetOverlay($planningObject);
        }
         
        public function getSuccessIndicatorVariables($table_name, $employee_details_id)
	{
		return $this->planningMapper->getSuccessIndicatorVariables($table_name, $employee_details_id); 
	}
	
	public function getSelfEvaluation($employee_details_id)
	{
		return $this->planningMapper->getSelfEvaluation($employee_details_id);
	}
         
        public function getBudgetOverlay($table_name, $organisation_id)
        {
                return $this->planningMapper->getBudgetOverlay($table_name, $organisation_id);
        }
         
        public function getSupervisorIds($employee_details_id, $supervisor_role, $organisation_id)
        {
                return $this->planningMapper->getSupervisorIds($employee_details_id, $supervisor_role, $organisation_id);
        }

        public function getSupervisorDeptIds($employee_details_id)
        {
        	return $this->planningMapper->getSupervisorDeptIds($employee_details_id);
        }

        public function getApaDeadline($apa_type)
        {
        	return $this->planningMapper->getApaDeadline($apa_type);
        }
         
        public function listSupervisorRoles($supervisor_ids)
        {
                return $this->planningMapper->listSupervisorRoles($supervisor_ids);
        }
        

        public function listRubObjectives($five_year)
        {
        	return $this->planningMapper->listRubObjectives($five_year);
        }
	 
		public function listSelectData($tableName, $columnName, $emp_id)
		{
			return $this->planningMapper->listSelectData($tableName, $columnName, $emp_id);
		}
	
}
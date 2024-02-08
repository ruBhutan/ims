<?php

namespace EmpTraining\Service;

use EmpTraining\Mapper\EmpTrainingMapperInterface;
use EmpTraining\Model\TrainingDetails;
use EmpTraining\Model\WorkshopDetails;
use EmpTraining\Model\TrainingNomination;
use EmpTraining\Model\ShortTermApplication;
use EmpTraining\Model\LongTermApplication;
use EmpTraining\Model\TrainingReport;
use EmpTraining\Model\StudyReport;
use EmpTraining\Model\StudyExtension;
use EmpTraining\Model\HrLongTermApplication;

class EmpTrainingService implements EmpTrainingServiceInterface
{
	/**
	 * @var \Blog\Mapper\EmpTrainingMapperInterface
	*/
	
	protected $trainingMapper;
	
	public function __construct(EmpTrainingMapperInterface $trainingMapper) {
		$this->trainingMapper = $trainingMapper;
	}
	
	public function getOrganisationId($username)
	{
		return $this->trainingMapper->getOrganisationId($username);
	}
	
	public function getUserDetailsId($username)
	{
		return $this->trainingMapper->getUserDetailsId($username);
	}
	
	public function getUserDetails($username, $usertype)
	{
		return $this->trainingMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->trainingMapper->getUserImage($username, $usertype);
	}
	
	public function listAll($tableName)
	{
		return $this->trainingMapper->findAll($tableName);
	}
	
	public function listTrainingDetails($table_name, $organisation_id)
	{
		return $this->trainingMapper->listTrainingDetails($table_name, $organisation_id);
	}
	
	public function listHrdPlan($type, $organisation_id)
	{
		return $this->trainingMapper->listHrdPlan($type, $organisation_id);
	}


	public function listAdhocTrainingList($type, $organisation_id)
	{
		return $this->trainingMapper->listAdhocTrainingList($type, $organisation_id);
	}

	public function getAdhocTrainingDetails($type, $id)
	{
		return $this->trainingMapper->getAdhocTrainingDetails($type, $id);
	}
	
	public function getAdhocTrainingNomination($id, $type)
	{
		return $this->trainingMapper->getAdhocTrainingNomination($id, $type);
	}
	
	public function deleteAdhocTraining($id, $type)
	{
		return $this->trainingMapper->deleteAdhocTraining($id, $type);
	}
	 
	public function findPlanDetail($id)
	{
		return $this->trainingMapper->findPlanDetail($id);
	}
	
	public function findEmpDetails($id)
	{
		return $this->trainingMapper->findEmpDetails($id);
	}
        	
	public function save(TrainingDetails $trainingObject, $category, $type) 
	{
		return $this->trainingMapper->save($trainingObject, $category, $type);
	}
		 
	public function saveShortTermTraining(WorkshopDetails $trainingObject)
	{
		return $this->trainingMapper->saveShortTermTraining($trainingObject);
	}
	
	public function saveTrainingNomination(TrainingNomination $trainingObject)
	{
		return $this->trainingMapper->saveTrainingNomination($trainingObject);
	}

	
	public function saveLongTermApplication(LongTermApplication $trainingObject)
	{
		return $this->trainingMapper->saveLongTermApplication($trainingObject);
	}

	public function updateEditedLongTermApplication(LongTermApplication $trainingObject)
	{
		return $this->trainingMapper->updateEditedLongTermApplication($trainingObject);
	}

	public function updateEditedShortTermApplication(ShortTermApplication $trainingObject)
	{
		return $this->trainingMapper->updateEditedShortTermApplication($trainingObject);
	}

	public function updateLongTermApplication(HrLongTermApplication $trainingObject)
	{
		return $this->trainingMapper->updateLongTermApplication($trainingObject);
	}
	 
	public function saveShortTermApplication(ShortTermApplication $trainingObject)
	{
		return $this->trainingMapper->saveShortTermApplication($trainingObject);
	}
	 
	public function updateShortTermApplication(ShortTermApplication $trainingObject, $data_to_check)
	{
		return $this->trainingMapper->updateShortTermApplication($trainingObject, $data_to_check);
	}
		 
	public function saveTrainingReport(TrainingReport $trainingObject)
	{
		return $this->trainingMapper->saveTrainingReport($trainingObject);
	}
	 
	public function saveStudyReport(StudyReport $trainingObject)
	{
		return $this->trainingMapper->saveStudyReport($trainingObject);
	}
	 
	public function saveStudyExtensionRequest(StudyExtension $trainingObject)
	{
		return $this->trainingMapper->saveStudyExtensionRequest($trainingObject);
	}
		
	public function getEmployeeList($empName, $empId, $department, $organisation_id)
	{
		return $this->trainingMapper->getEmployeeList($empName, $empId, $department, $organisation_id);
	}
	 
	public function getNominatedTrainingList($tableName, $employee_details_id)
	{
		return $this->trainingMapper->getNominatedTrainingList($tableName, $employee_details_id);
	}


	public function getAppliedTrainingDetails($id, $training_type, $employee_details_id)
	{
		return $this->trainingMapper->getAppliedTrainingDetails($id, $training_type, $employee_details_id);
	}


	public function getAppliedTrainingList($type, $employee_details_id)
	{
		return $this->trainingMapper->getAppliedTrainingList($type, $employee_details_id);
	}
		 
	public function getTrainingNominations($id, $training_type, $training_status)
	{
		return $this->trainingMapper->getTrainingNominations($id, $training_type, $training_status);
	}

	public function crossCheckTrainingReport($id, $training_type)
	{
		return $this->trainingMapper->crossCheckTrainingReport($id, $training_type);
	}
		 
	public function getTrainingList($table_name, $organisation_id)
	{
		return $this->trainingMapper->getTrainingList($table_name, $organisation_id);
	}

	public function getTraineeList($id, $tableName, $organisation_id)
	{
		return $this->trainingMapper->getTraineeList($id, $tableName, $organisation_id);
	}

	public function getUpdatedStudyReportList($id, $tableName)
	{
		return $this->trainingMapper->getUpdatedStudyReportList($id, $tableName);
	}
	 
	public function getTrainingDetails($id, $training_type, $status)
	{
		return $this->trainingMapper->getTrainingDetails($id, $training_type, $status);
	}
		 
	public function getTrainingReportDetails($id, $training_type)
	{
		return $this->trainingMapper->getTrainingReportDetails($id, $training_type);
	}


	public function getNomineeDetail($employee_details_id)
	{
		return $this->trainingMapper->getNomineeDetail($employee_details_id);
	}


	public function getAuthorityEmail($organisation_id)
	{
		return $this->trainingMapper->getAuthorityEmail($organisation_id);
	}

	public function getTrainingNominationDetails($training_details_id, $type, $id)
	{
		return $this->trainingMapper->getTrainingNominationDetails($training_details_id, $type, $id);
	}

	 
	public function crossCheckTrainingApplication($employee_id, $training_id, $training_type)
	{
		return $this->trainingMapper->crossCheckTrainingApplication($employee_id, $training_id, $training_type);
	}

	public function getLongTermApplicantDetails($id)
	{
		return $this->trainingMapper->getLongTermApplicantDetails($id);
	}
	
	public function getTrainingNominationId($training_id, $category, $employee_details_id)
	{
		return $this->trainingMapper->getTrainingNominationId($training_id, $category, $employee_details_id);
	}
	 
	public function getFileName($training_id, $column_name, $training_type)
	{
		return $this->trainingMapper->getFileName($training_id, $column_name, $training_type);
	}
		
	public function listSelectData($tableName, $columnName)
	{
		return $this->trainingMapper->listSelectData($tableName, $columnName);
	}
	
}
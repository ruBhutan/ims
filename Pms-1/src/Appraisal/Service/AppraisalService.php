<?php

namespace Appraisal\Service;

use Appraisal\Mapper\AppraisalMapperInterface;
use Appraisal\Model\Appraisal;
use Appraisal\Model\AcademicAppraisal;
use Appraisal\Model\AcademicWeight;
use Appraisal\Model\IwpObjectives;
use Appraisal\Model\NatureActivity;

class AppraisalService implements AppraisalServiceInterface
{
	/**
	 * @var \Blog\Mapper\AppraisalMapperInterface
	*/
	
	protected $appraisalMapper;
	
	public function __construct(AppraisalMapperInterface $appraisalMapper) {
		$this->appraisalMapper = $appraisalMapper;
	}
		 
	public function getOrganisationId($username)
	{
		return $this->appraisalMapper->getOrganisationId($username);
	}
		
	public function getUserDetailsId($username)
	{
		return $this->appraisalMapper->getUserDetailsId($username);
	}
	
	public function getUserDetails($username, $usertype)
	{
		return $this->appraisalMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->appraisalMapper->getUserImage($username, $usertype);
	}
	
	public function getOccupationalGroup($username)
	{
		return $this->appraisalMapper->getOccupationalGroup($username);
	}
	
	public function listAll($tableName)
	{
		return $this->appraisalMapper->findAll($tableName);
	}
	
	public function listEmployeeAppraisal($tableName, $employee_id, $status)
	{
		return $this->appraisalMapper->findEmployeeAppraisal($tableName, $employee_id, $status);
	}
        
        public function getSupervisorSuccessIndicators($employee_id)
        {
                return $this->appraisalMapper->getSupervisorSuccessIndicators($employee_id);
        }
	
	public function listAdministrativeAppraisal($tableName, $employee_id, $status)
	{
		return $this->appraisalMapper->listAdministrativeAppraisal($tableName, $employee_id, $status);
	}
	
	public function listActivityDetail($tableName, $columnName, $activity_id)
	{
		return $this->appraisalMapper->findActivityDetail($tableName, $columnName, $activity_id);
	}
	
	public function saveAcademicAppraisal(AcademicAppraisal $appraisalObject)
	{
		return $this->appraisalMapper->saveAcademicAppraisal($appraisalObject);
	}
	
	public function getIwpDeadline($iwp_type)
	{
		return $this->appraisalMapper->getIwpDeadline($iwp_type);
	}

	public function getAppraisalPeriodYear($iwp_type, $tableName)
	{
		return $this->appraisalMapper->getAppraisalPeriodYear($iwp_type, $tableName);
	}
		
	public function saveReview($data, $type)
	{
		return $this->appraisalMapper->saveReview($data, $type);
	}
		
	public function saveNatureOfActivity(NatureActivity $activityModel)
	{
		return $this->appraisalMapper->saveNatureOfActivity($activityModel);
	}
		
	public function saveAcademicWeight(AcademicWeight $academicModel)
	{
		return $this->appraisalMapper->saveAcademicWeight($academicModel);
	}
	
	public function saveAdministrativeAppraisal(IwpObjectives $appraisalObject)
	{
		return $this->appraisalMapper->saveAdministrativeAppraisal($appraisalObject);
	}
		
	public function getEmployeeDetails($id)
	{
		return $this->appraisalMapper->getEmployeeDetails($id);
	}
		
	public function getDetail($tableName, $id)
	{
		return $this->appraisalMapper->getDetail($tableName, $id);
	}

	public function deleteAppraisal($id)
	{
		return $this->appraisalMapper->deleteAppraisal($id);
	}
	        
	public function getAppraisalList($type, $employee_details_id, $role, $organisation_id)
	{
		return $this->appraisalMapper->getAppraisalList($type, $employee_details_id, $role, $organisation_id);
	}
		
	public function getNominationList($table_name, $employee_id)
	{
		return $this->appraisalMapper->getNominationList($table_name, $employee_id);
	}
		
	public function updateNominationStatus($data, $employee_id)
	{
		return $this->appraisalMapper->updateNominationStatus($data, $employee_id);
	}
        
        public function submitIWPActivities($employee_id, $table_name)
        {
                return $this->appraisalMapper->submitIWPActivities($employee_id, $table_name);
        }
	 	
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		return $this->appraisalMapper->listSelectData($tableName, $columnName, $organisation_id);
	}
	
}
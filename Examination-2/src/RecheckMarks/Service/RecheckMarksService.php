<?php

namespace RecheckMarks\Service;

use RecheckMarks\Mapper\RecheckMarksMapperInterface;
use RecheckMarks\Model\RecheckMarks;

class RecheckMarksService implements RecheckMarksServiceInterface
{
	/**
	 * @var \Blog\Mapper\RecheckMarksMapperInterface
	*/
	
	protected $recheckMapper;
	
	public function __construct(RecheckMarksMapperInterface $recheckMapper) {
		$this->recheckMapper = $recheckMapper;
	}
	
	public function listAll($tableName, $applicant_id)
	{
		return $this->recheckMapper->findAll($tableName, $applicant_id);
	}
	
	public function getUserDetailsId($username)
	{
		return $this->recheckMapper->getUserDetailsId($username);
	}

	public function getUserDetails($username, $usertype)
	{
		return $this->recheckMapper->getUserDetails($username, $usertype);
	}

    public function getUserImage($username, $usertype)
    {
    	return $this->recheckMapper->getUserImage($username, $usertype);
    }
		
	public function getStudentId($username)
	{
		return $this->recheckMapper->getStudentId($username);
	}
	
	public function getOrganisationId($username)
	{
		return $this->recheckMapper->getOrganisationId($username);
	}

	public function getAcademicModules($student_id)
	{
		return $this->recheckMapper->getAcademicModules($student_id);
	}

	public function getStudentDetails($student_id, $type)
	{
		return $this->recheckMapper->getStudentDetails($student_id, $type);
	}

	public function getRecheckList($student_id)
	{
		return $this->recheckMapper->getRecheckList($student_id);
	}

	public function crossCheckModuleRecheckApplication($academic_modules_allocation_id, $student_id, $type)
	{
		return $this->recheckMapper->crossCheckModuleRecheckApplication($academic_modules_allocation_id, $student_id, $type);
	}

	public function listRecheckApplicants($organisation_id)
	{
		return $this->recheckMapper->listRecheckApplicants($organisation_id);
	}

	public function getRecheckApplicationDetails($id)
	{
		return $this->recheckMapper->getRecheckApplicationDetails($id);
	}

	public function saveRecheckApplication(RecheckMarks $recheckObject)
	{
		return $this->recheckMapper->saveRecheckApplication($recheckObject);
	}

	public function updateRecheckMarksStatus($data, $organisation_id, $employee_details_id)
	{
		return $this->recheckMapper->updateRecheckMarksStatus($data, $organisation_id, $employee_details_id);
	}

	public function updateApprovedRecheckMarksStatus($data_to_insert, $organisation_id, $employee_details_id)
	{
		return $this->recheckMapper->updateApprovedRecheckMarksStatus($data_to_insert, $organisation_id, $employee_details_id);
	}
	
	public function listSelectData($tableName, $columnName, $condition)
	{
		return $this->recheckMapper->listSelectData($tableName, $columnName, $condition);
	}
	
}
<?php

namespace Responsibilities\Service;

use Responsibilities\Mapper\ResponsibilitiesMapperInterface;
use Responsibilities\Model\Responsibilities;
use Responsibilities\Model\ResponsibilityCategory;
use Responsibilities\Model\StudentResponsibility;

class ResponsibilitiesService implements ResponsibilitiesServiceInterface
{
	/**
	 * @var \Blog\Mapper\ResponsibilitiesMapperInterface
	*/
	
	protected $responsibilityMapper;
	
	public function __construct(ResponsibilitiesMapperInterface $responsibilityMapper) {
		$this->responsibilityMapper = $responsibilityMapper;
	}
		 
	public function getOrganisationId($username)
	{
		return $this->responsibilityMapper->getOrganisationId($username);
	}
	
	public function getUserDetailsId($username)
	{
		return $this->responsibilityMapper->getUserDetailsId($username);
	}

	public function getUserDetails($username, $usertype)
	{
		return $this->responsibilityMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->responsibilityMapper->getUserImage($username, $usertype);
	}
	
	public function listAll($tableName, $organisation_id)
	{
		return $this->responsibilityMapper->findAll($tableName, $organisation_id);
	}

	public function save(ResponsibilityCategory $responsibilityObject) 
	{
		return $this->responsibilityMapper->saveCategory($responsibilityObject);
	}
	
	public function saveResponsibility(StudentResponsibility $responsibilityObject) 
	{
		return $this->responsibilityMapper->saveResponsibility($responsibilityObject);
	}
	
	public function getStudentList($studentName, $studentId, $programme, $organisation_id)
	{
		return $this->responsibilityMapper->getStudentList($studentName, $studentId, $programme, $organisation_id);
	}
		
	public function getStudentResponsibilitiesList($studentName, $studentId, $programme, $organisation_id)
	{
		return $this->responsibilityMapper->getStudentResponsibilitiesList($studentName, $studentId, $programme, $organisation_id);
	}
		
	public function listStudentResponsibilities($organisation_id)
	{
		return $this->responsibilityMapper->listStudentResponsibilities($organisation_id);
	}
	
	public function getResponsibilityCategoryDetails($id)
	{
		return $this->responsibilityMapper->getResponsibilityCategoryDetails($id);
	}
		
	public function getStudentResponsibilities($student_id)
	{
		return $this->responsibilityMapper->getStudentResponsibilities($student_id);
	}
	
	public function getStudentDetails($id)
	{
		return $this->responsibilityMapper->getStudentDetails($id);
	}
		
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		return $this->responsibilityMapper->listSelectData($tableName, $columnName, $organisation_id);
	}
	
}
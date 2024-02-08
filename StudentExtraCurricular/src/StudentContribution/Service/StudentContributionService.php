<?php

namespace StudentContribution\Service;

use StudentContribution\Mapper\StudentContributionMapperInterface;
use StudentContribution\Model\StudentContribution;
use StudentContribution\Model\StudentContributionCategory;

class StudentContributionService implements StudentContributionServiceInterface
{
	/**
	 * @var \Blog\Mapper\StudentContributionMapperInterface
	*/
	
	protected $contributionMapper;
	
	public function __construct(StudentContributionMapperInterface $contributionMapper) {
		$this->contributionMapper = $contributionMapper;
	}
		 
	public function getOrganisationId($username, $tableName)
	{
		return $this->contributionMapper->getOrganisationId($username, $tableName);
	}
		
	public function getUserDetailsId($username, $tableName)
	{
		return $this->contributionMapper->getUserDetailsId($username, $tableName);
	}


	public function getUserDetails($username, $usertype)
	{
		return $this->contributionMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->contributionMapper->getUserImage($username, $usertype);
	}
	
	public function listAll($tableName, $organisation_id)
	{
		return $this->contributionMapper->findAll($tableName, $organisation_id);
	}
	 	
	public function save(StudentContribution $contributionObject) 
	{
		return $this->contributionMapper->saveDetails($contributionObject);
	}
		 
	public function saveContributionCategory(StudentContributionCategory $contributionObject)
	{
		return $this->contributionMapper->saveContributionCategory($contributionObject);
	}

	public function getStudentContributionCategoryDetails($id)
	{
		return $this->contributionMapper->getStudentContributionCategoryDetails($id);
	}
		
	public function getStudentList($studentName, $studentId, $programme, $organisation_id)
	{
		return $this->contributionMapper->getStudentList($studentName, $studentId, $programme, $organisation_id);
	}
		
	public function getStudentContributionList($studentName, $studentId, $programme, $organisation_id)
	{
		return $this->contributionMapper->getStudentContributionList($studentName, $studentId, $programme, $organisation_id);
	}
	
	public function getStudentDetails($id) 
	{
		return $this->contributionMapper->getStudentDetails($id);
	}
	
	public function getContributionList($organisation_id)
	{
		return $this->contributionMapper->getContributionList($organisation_id);
	}
		
	public function getStudentContributions($student_id)
	{
		return $this->contributionMapper->getStudentContributions($student_id);
	}
		
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		return $this->contributionMapper->listSelectData($tableName, $columnName, $organisation_id);
	}
	
}
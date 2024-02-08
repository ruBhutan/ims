<?php

namespace StudentParticipation\Service;

use StudentParticipation\Mapper\StudentParticipationMapperInterface;
use StudentParticipation\Model\StudentParticipation;
use StudentParticipation\Model\StudentParticipationCategory;

class StudentParticipationService implements StudentParticipationServiceInterface
{
	/**
	 * @var \Blog\Mapper\StudentParticipationMapperInterface
	*/
	
	protected $participationMapper;
	
	public function __construct(StudentParticipationMapperInterface $participationMapper) {
		$this->participationMapper = $participationMapper;
	}
		 
	public function getOrganisationId($username, $tableName)
	{
		return $this->participationMapper->getOrganisationId($username, $tableName);
	}
		
	public function getUserDetailsId($username, $tableName)
	{
		return $this->participationMapper->getUserDetailsId($username, $tableName);
	}

	public function getUserDetails($username, $usertype)
	{
		return $this->participationMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->participationMapper->getUserImage($username, $usertype);
	}
	
	public function listAll($tableName, $organisation_id)
	{
		return $this->participationMapper->findAll($tableName, $organisation_id);
	}


	public function getStudentParticipationCategoryDetails($id)
	{
		return $this->participationMapper->getStudentParticipationCategoryDetails($id);
	}
	 	
	public function save(StudentParticipation $participationObject) 
	{
		return $this->participationMapper->saveDetails($participationObject);
	}
	
	public function saveParticipationCategory(StudentParticipationCategory $participationObject)
	{
		return $this->participationMapper->saveParticipationCategory($participationObject);
	}
	
	public function getStudentList($studentName, $studentId, $programme, $organisation_id)
	{
		return $this->participationMapper->getStudentList($studentName, $studentId, $programme, $organisation_id);
	}
		
	public function getStudentParticipationList($studentName, $studentId, $programme, $organisation_id)
	{
		return $this->participationMapper->getStudentParticipationList($studentName, $studentId, $programme, $organisation_id);
	}
	
	public function getStudentDetails($id)
	{
		return $this->participationMapper->getStudentDetails($id);
	}
	
	public function getParticipationList($organisation_id)
	{
		return $this->participationMapper->getParticipationList($organisation_id);
	}
		
	public function getStudentParticipations($student_id)
	{
		return $this->participationMapper->getStudentParticipations($student_id);
	}
		
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		return $this->participationMapper->listSelectData($tableName, $columnName, $organisation_id);
	}
	
}
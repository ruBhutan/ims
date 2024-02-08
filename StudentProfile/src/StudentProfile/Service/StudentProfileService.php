<?php

namespace StudentProfile\Service;

use StudentProfile\Mapper\StudentProfileMapperInterface;
use StudentProfile\Model\StudentProfile;


class StudentProfileService implements StudentProfileServiceInterface
{
	/**
	 * @var \Blog\Mapper\PostMapperInterface
	*/
	
	protected $studentProfileMapper;
	
	public function __construct(StudentProfileMapperInterface $studentProfileMapper) {
		$this->studentProfileMapper = $studentProfileMapper;
	}
	

	
	public function getUserDetailsId($username, $tableName)
	{
		return $this->studentProfileMapper->getUserDetailsId($username, $tableName);
	}
	
	public function getOrganisationId($username)
	{
		return $this->studentProfileMapper->getOrganisationId($username);
	}

	public function getStudentList($stdName, $stdId, $stdProgramme, $organisation_id)
	{
		return $this->studentProfileMapper->getStudentList($stdName, $stdId, $stdProgramme, $organisation_id);
	}


	public function getStudentDetails($id)
	{
		return $this->studentProfileMapper->getStudentDetails($id);
	}

	public function getStudentPreviousDetails($id)
	{
		return $this->studentProfileMapper->getStudentPreviousDetails($id);
	}
        
		
}
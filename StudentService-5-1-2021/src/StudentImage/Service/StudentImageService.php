<?php

namespace StudentImage\Service;

use StudentImage\Mapper\StudentImageMapperInterface;
use StudentImage\Model\StudentProfilePicture;

class StudentImageService implements StudentImageServiceInterface
{
	/**
	 * @var \Blog\Mapper\DisciplineMapperInterface
	*/
	
	protected $studentImageMapper;
	
	public function __construct(StudentImageMapperInterface $studentImageMapper) {
		$this->studentImageMapper = $studentImageMapper;
	}
	
	public function getOrganisationId($username)
	{
		return $this->studentImageMapper->getOrganisationId($username);
	}
	 	
	public function getUserDetailsId($username)
	{
		return $this->studentImageMapper->getUserDetailsId($username);
	}

	public function getUserDetails($username, $usertype)
	{
		return $this->studentImageMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->studentImageMapper->getUserImage($username, $usertype);
	}

	public function getStudentList($studentName, $studentId, $programme, $organisation_id)
	{
		return $this->studentImageMapper->getStudentList($studentName, $studentId, $programme, $organisation_id);
	}

	public function findStudent($id, $type)
	{
		return $this->studentImageMapper->findStudent($id, $type);
	}

	public function getStudentProfilePicture($id)
	{
		return $this->studentImageMapper->getStudentProfilePicture($id);
	}

	public function saveStudentProfilePicture(StudentProfilePicture $studentModel)
	{
		return $this->studentImageMapper->saveStudentProfilePicture($studentModel);
	}

		
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		return $this->studentImageMapper->listSelectData($tableName, $columnName, $organisation_id);
	}
	
}
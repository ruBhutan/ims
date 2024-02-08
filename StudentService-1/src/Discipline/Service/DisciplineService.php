<?php

namespace Discipline\Service;

use Discipline\Mapper\DisciplineMapperInterface;
use Discipline\Model\Discipline;
use Discipline\Model\DisciplineCategory;

class DisciplineService implements DisciplineServiceInterface
{
	/**
	 * @var \Blog\Mapper\DisciplineMapperInterface
	*/
	
	protected $disciplineMapper;
	
	public function __construct(DisciplineMapperInterface $disciplineMapper) {
		$this->disciplineMapper = $disciplineMapper;
	}
	
	public function getOrganisationId($username)
	{
		return $this->disciplineMapper->getOrganisationId($username);
	}
	 	
	public function getUserDetailsId($username)
	{
		return $this->disciplineMapper->getUserDetailsId($username);
	}

	public function getUserDetails($username, $usertype)
	{
		return $this->disciplineMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->disciplineMapper->getUserImage($username, $usertype);
	}
	
	public function listAll($tableName, $organisation_id)
	{
		return $this->disciplineMapper->findAll($tableName, $organisation_id);
	}
	 
	public function findStudent($id)
	{
		return $this->disciplineMapper->findStudent($id);
	}
        	
	public function saveCategory(DisciplineCategory $disciplineObject) 
	{
		return $this->disciplineMapper->saveDetails($disciplineObject);
	}
		
	public function saveDisciplinaryRecord(Discipline $disciplineObject)
	{
		return $this->disciplineMapper->saveDisciplinaryRecord($disciplineObject);
	}
	
	public function getStudentList($studentName, $studentId, $programme, $organisation_id)
	{
		return $this->disciplineMapper->getStudentList($studentName, $studentId, $programme, $organisation_id);
	}
		
	public function getStudentDisciplinaryList($studentName, $studentId, $programme, $organisation_id)
	{
		return $this->disciplineMapper->getStudentDisciplinaryList($studentName, $studentId, $programme, $organisation_id);
	}
	
	public function getStudentDetails($id)
	{
		return $this->disciplineMapper->getStudentDetails($id);
	}
	
	public function getDisciplineCategoryDetails($id)
	{
		return $this->disciplineMapper->getDisciplineCategoryDetails($id);
	}
		
	public function getStudentDisciplinaryRecords($student_id)
	{
		return $this->disciplineMapper->getStudentDisciplinaryRecords($student_id);
	}
	
	public function getDisciplinaryRecord($organisation_id)
	{
		return $this->disciplineMapper->getDisciplinaryRecord($organisation_id);
	}
		
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		return $this->disciplineMapper->listSelectData($tableName, $columnName, $organisation_id);
	}
	
}
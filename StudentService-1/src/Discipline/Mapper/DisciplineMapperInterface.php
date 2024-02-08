<?php

namespace Discipline\Mapper;

use Discipline\Model\Discipline;
use Discipline\Model\DisciplineCategory;

interface DisciplineMapperInterface
{	
	/*
	 * Get the Organisation Id
	 */
	 
	public function getOrganisationId($username);
	 
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username);

	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);
	
	/**
	 * @param int/string $id
	 * @return Discipline
	 * throws \InvalidArugmentException
	 * 
	*/
	
	public function findStudent($id);

	/**
	 * 
	 * @return array/ Discipline[]
	 */
	 
	public function findAll($tableName, $organisation_id);
        
	/**
	 * 
	 * @param type $DisciplineInterface
	 * 
	 * to save budgetings
	 */
	
	public function saveDetails(DisciplineCategory $DisciplineInterface);
	
	/*
	* Save the disciplinary record of a student
	*/
	
	public function saveDisciplinaryRecord(Discipline $disciplineObject);
	
	/*
	* List Student to add awards etc
	*/
	
	public function getStudentList($studentName, $studentId, $programme, $organisation_id);
	
	/*
	* Get the list of disciplinary action of students after search funcationality
	*/
	
	public function getStudentDisciplinaryList($studentName, $studentId, $programme, $organisation_id);
	
	/**
	 * 
	 * @param type $id
	 * 
	 * to find Category details to edit/display
	 */
	public function getDisciplineCategoryDetails($id);
	
	/*
	* Get the disciplinary record of the students
	*/
	
	public function getDisciplinaryRecord($organisation_id);
	
	/*
	* Get the list of disciplinary records by a student
	*/
	
	public function getStudentDisciplinaryRecords($student_id);
	
	/**
	 * 
	 * @param type $id
	 * 
	 * to find Student details so that their names are displayed
	 */
	public function getStudentDetails($id);
	
	/**
	 * 
	 * @return array/ Discipline[]
	 */
	 
	public function listSelectData($tableName, $columnName, $organisation_id);
	
}
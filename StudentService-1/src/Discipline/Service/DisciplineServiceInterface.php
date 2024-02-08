<?php

namespace Discipline\Service;

use Discipline\Model\Discipline;
use Discipline\Model\DisciplineCategory;

//need to add more models

interface DisciplineServiceInterface
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
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|DisciplineInterface[]
	*/
	
	public function listAll($tableName, $organisation_id);

	/**
	 * Should return a single proposal
	 *
	 * @param int $id Identifier of the proposal that should be returned
	 * @return DisciplineInterface
	 */
	 
	public function findStudent($id);
        
        
	 /**
	 * @param DisciplineInterface $disciplineObject
	 *
	 * @param DisciplineInterface $disciplineObject
	 * @return DisciplineInterface
	 * @throws \Exception
	 */
	 
	 public function saveCategory(DisciplineCategory $disciplineObject);
	 
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
	
	/**
	 * 
	 * @param type $id
	 * 
	 * to find Student details so that their names are displayed
	 */
	public function getStudentDetails($id);
	
	/*
	* Get the disciplinary record of the students
	*/
	
	public function getDisciplinaryRecord($organisation_id);
	
	/*
	* Get the list of disciplinary records by a student
	*/
	
	public function getStudentDisciplinaryRecords($student_id);
	 
	 
	 /**
	 * Should return a set of all objectives that we can iterate over. 
	 * 
	 * The purpose of the function is the objectives for the dropdown select list
	 *
	 * @return array|DisciplineInterface[]
	*/
	
	public function listSelectData($tableName, $columnName, $organisation_id);
		
		
}
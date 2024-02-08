<?php

namespace StudentParticipation\Service;

use StudentParticipation\Model\StudentParticipation;
use StudentParticipation\Model\StudentParticipationCategory;

//need to add more models

interface StudentParticipationServiceInterface
{
	/*
	 * Get the Organisation Id
	 */
	 
	public function getOrganisationId($username, $tableName);
	
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username, $tableName);

	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);
	
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|StudentParticipationInterface[]
	*/
	
	public function listAll($tableName, $organisation_id);

	public function getStudentParticipationCategoryDetails($id);

	 /**
	 * @param StudentParticipationInterface $participationObject
	 *
	 * @param StudentParticipationInterface $participationObject
	 * @return StudentParticipationInterface
	 * @throws \Exception
	 */
	 
	 public function save(StudentParticipation $participationObject);
	 
	 /*
	 * To save student participation category
	 */
	 
	 public function saveParticipationCategory(StudentParticipationCategory $participationObject);
	 
	 /*
	 * List Student to add awards etc
	 */
	
	 public function getStudentList($studentName, $studentId, $programme, $organisation_id);
	 
	 /*
	* Get the list of participations by students after search funcationality
	*/
	
	public function getStudentParticipationList($studentName, $studentId, $programme, $organisation_id);
	
	 /**
	 * 
	 * @param type $id
	 * 
	 * to find Student details so that their names are displayed
	 */
	 public function getStudentDetails($id);
	 
	 /*
	 * Get the participation list of students
	 */
	 
	 public function getParticipationList($organisation_id);
	 
	 /*
	* Get the list of participations by a student
	*/
	
	public function getStudentParticipations($student_id);
	 
	 /**
	 * Should return a set of all objectives that we can iterate over. 
	 * 
	 * The purpose of the function is the objectives for the dropdown select list
	 *
	 * @return array|StudentParticipationInterface[]
	*/
	
	public function listSelectData($tableName, $columnName, $organisation_id);
		
		
}
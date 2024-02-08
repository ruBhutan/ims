<?php

namespace StudentParticipation\Mapper;

use StudentParticipation\Model\StudentParticipation;
use StudentParticipation\Model\StudentParticipationCategory;

interface StudentParticipationMapperInterface
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
	 * 
	 * @return array/ StudentParticipation[]
	 */
	 
	public function findAll($tableName, $organisation_id);


	public function getStudentParticipationCategoryDetails($id);
        
	
	/**
	 * 
	 * @param type $StudentParticipationInterface
	 * 
	 */
	
	public function saveDetails(StudentParticipation $StudentParticipationInterface);
	
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
	 * 
	 * @return array/ StudentParticipation[]
	 */
	 
	public function listSelectData($tableName, $columnName, $organisation_id);
	
}
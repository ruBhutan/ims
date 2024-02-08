<?php

namespace StudentImage\Service;

use StudentImage\Model\StudentProfilePicture;

//need to add more models

interface StudentImageServiceInterface
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

	public function getStudentList($studentName, $studentId, $programme, $organisation_id);

	public function findStudent($id, $type);

	public function getStudentProfilePicture($id);

	public function saveStudentProfilePicture(StudentProfilePicture $studentModel);
	 
	 
	 /**
	 * Should return a set of all objectives that we can iterate over. 
	 * 
	 * The purpose of the function is the objectives for the dropdown select list
	 *
	 * @return array|DisciplineInterface[]
	*/
	
	public function listSelectData($tableName, $columnName, $organisation_id);
		
		
}
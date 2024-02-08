<?php

namespace StudentImage\Mapper;

use StudentImage\Model\StudentProfilePicture;

interface StudentImageMapperInterface
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
	 * 
	 * @return array/ Discipline[]
	 */
	 
	public function listSelectData($tableName, $columnName, $organisation_id);
	
}
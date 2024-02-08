<?php

namespace StudentProfile\Mapper;

use StudentProfile\Model\StudentProfile;


interface StudentProfileMapperInterface
{
	/**
	 * @param int/string $id
	 * @return EmployeeDetail
	 * throws \InvalidArugmentException
	 * 
	*/
	
	/*
	* take username and returns employee details id/student id
	*/
	
	public function getUserDetailsId($username, $tableName);
	
	/*
	 * Get the Organisation Id
	 */
	 
	public function getOrganisationId($username);
        

	public function getStudentList($stdName, $stdId, $stdProgramme, $organisation_id);


	public function getStudentDetails($id);

	public function getStudentPreviousDetails($id);
	
	
}
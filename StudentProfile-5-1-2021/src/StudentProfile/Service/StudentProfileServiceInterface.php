<?php

namespace StudentProfile\Service;

use StudentProfile\Model\StudentProfile;


interface StudentProfileServiceInterface
{
	/**
	 * Should return a set of all employees that we can iterate over. 
     * Single entries of the array are 11 * implementing \EmployeeDetail\Model\EmployeeDetailInterface
	 *
	 * @return array|EmployeeDetailInterface[]
	*/
	
	/*
	* take username and returns employee details id/student id
	*/
	
	public function getUserDetailsId($username, $tableName);
	
	/*
	 * Get the Organisation Id
	 */
	 
	public function getOrganisationId($username);
        
        
    /*
	* List Studnet so that to view the details of student.
	*/

    public function getStudentList($stdName, $stdId, $stdProgramme, $organisation_id);
	

	/*
	*To get the details of particular student to view it
	*/

	public function getStudentDetails($id);

	public function getStudentPreviousDetails($id);
	
}
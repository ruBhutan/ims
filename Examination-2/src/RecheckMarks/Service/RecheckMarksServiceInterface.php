<?php

namespace RecheckMarks\Service;

use RecheckMarks\Model\RecheckMarks;

//need to add more models

interface RecheckMarksServiceInterface
{
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|RecheckMarksInterface[]
	*/
	
	public function listAll($tableName, $applicant_id);
	
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username);

	/*
	* Get the User Details
	*/

	public function getUserDetails($username, $usertype);

    public function getUserImage($username, $usertype);
	
	/*
	* take username and returns student id
	*/
	
	public function getStudentId($username);
	
	/*
	* Get organisation id based on the username
	*/
	
	public function getOrganisationId($username);

	/*
	* Get the details of the student
	*/
	
	public function getStudentDetails($student_id, $type);

	public function getRecheckList($student_id);
	
	/*
	* Get the list of the academic modules for the current semester
	*/
	
	public function getAcademicModules($student_id);

	public function crossCheckModuleRecheckApplication($academic_modules_allocation_id, $student_id, $type);

	public function listRecheckApplicants($organisation_id);

	public function getRecheckApplicationDetails($id);
	
	public function saveRecheckApplication(RecheckMarks $recheckModel);

	public function updateRecheckMarksStatus($data, $organisation_id, $employee_details_id);

	public function updateApprovedRecheckMarksStatus($data_to_insert, $organisation_id, $employee_details_id);
	
	
	/**
	* Should return a set of all objectives that we can iterate over. 
	* 
	* The purpose of the function is the objectives for the dropdown select list
	*
	* @return array|RecheckMarksInterface[]
	*/
	
	public function listSelectData($tableName, $columnName, $condition);
		
		
}
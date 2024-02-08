<?php

namespace RepeatModules\Service;

use RepeatModules\Model\RepeatModules;

//need to add more models

interface RepeatModulesServiceInterface
{
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|RepeatModulesInterface[]
	*/
	
	public function listAll($tableName, $applicant_id);
	
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username);
	
	/*
	* take username and returns student id
	*/
	
	public function getStudentId($username);
	
	/*
	* Get organisation id based on the username
	*/
	
	public function getOrganisationId($username);

	/*
	* Get the User Details
	*/

	public function getUserDetails($username, $usertype);

    public function getUserImage($username, $usertype);
        
	/*
	* Save Repeat Modules Application
	*/
	
	public function save(RepeatModules $repeatModulesModel);


	public function listRegisteredRepeatModules($student_id, $organisation_id, $type);
	
	/*
	* Get the details of the student
	*/
	
	public function getStudentDetails($student_id);

	public function getModuleRepeatRegistrationDuration($organisation_id);

	public function listEligibleRepeatModuleList($student_id);
	
	/**
	* Should return a set of all objectives that we can iterate over. 
	* 
	* The purpose of the function is the objectives for the dropdown select list
	*
	* @return array|RepeatModulesInterface[]
	*/
	
	public function listSelectData($tableName, $columnName, $condition);
		
		
}
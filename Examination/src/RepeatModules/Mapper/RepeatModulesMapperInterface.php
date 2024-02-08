<?php

namespace RepeatModules\Mapper;

use RepeatModules\Model\RepeatModules;

interface RepeatModulesMapperInterface
{

	/**
	 * 
	 * @return array/ RepeatModules[]
	 */
	 
	public function findAll($tableName, $applicant_id);
	
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
	 * 
	 * @return array/ RepeatModules[]
	 */
	 
	public function listSelectData($tableName, $columnName, $condition);
	
}
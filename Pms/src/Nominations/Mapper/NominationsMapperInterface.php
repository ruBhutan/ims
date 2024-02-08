<?php

namespace Nominations\Mapper;

use Nominations\Model\Nominations;

interface NominationsMapperInterface
{
	/*
	* Getting the id for username
	*/
	
	public function getEmployeeDetailsId($emp_id);
	
	/*
	* take username and returns Name and any other detail required
	*/
	
	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);
	
	/**
	 * 
	 * @return array/ Nominations[]
	 */
	 
	public function findAll($tableName);
	
	/*
	* Save Nominations
	*/
	
	public function saveNominations(Nominations $nominationObject);
	
	/*
	* Get the list of employees
	*/
	
	public function getEmployeeList($id, $empName, $position_title, $organisation_id);
	
	/*
	* Get Nominations
	*/
	
	public function getNominationList($tableName, $employee_details_id);
	
	/*
	* Get Employee Details of those Nominated Employees
	*/
	
	public function getNominatedEmployee($employee_details_id);
	
	/*
	* Get the deadline for the IWP
	*/
	
	public function getIwpDeadline();
	
	/*
	* Delete Nomination
	* Gets the table name and $id
	*/
	
	public function deleteNomination($table_name, $id);
	
	/**
	 * 
	 * @return array/ Nominations[]
	 */
	 
	public function listSelectData($tableName, $columnName);
	
}
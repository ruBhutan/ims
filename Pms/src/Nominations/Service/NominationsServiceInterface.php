<?php

namespace Nominations\Service;

use Nominations\Model\Nominations;

//need to add more models

interface NominationsServiceInterface
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
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|NominationsInterface[]
	*/
	
	public function listAll($tableName);
	
	/*
	* Get the list of employees
	*/
	
	public function getEmployeeList($id, $empName, $position_title, $organisation_id);
	
	/*
	* Save Nominations
	*/
	
	public function saveNominations(Nominations $nominationObject);
	
	/*
	* Get List of Employees that Nominations
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
	 * Should return a set of all objectives that we can iterate over. 
	 * 
	 * The purpose of the function is the objectives for the dropdown select list
	 *
	 * @return array|NominationsInterface[]
	*/
	
	public function listSelectData($tableName, $columnName);
		
		
}
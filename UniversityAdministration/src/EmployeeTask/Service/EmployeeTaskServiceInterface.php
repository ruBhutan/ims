<?php

namespace EmployeeTask\Service;

use EmployeeTask\Model\EmployeeTask;
use EmployeeTask\Model\EmployeeTaskCategory;

//need to add more models

interface EmployeeTaskServiceInterface
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
	
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|DisciplineInterface[]
	*/
	
	public function listAll($tableName, $organisation_id, $employee_details_id);
	public function listAll1($employee_details_id);

	/**
	 * Should return a single proposal
	 *
	 * @param int $id Identifier of the proposal that should be returned
	 * @return DisciplineInterface
	 */
	 
	public function findStaff($id);
        
        
	 /**
	 * @param EmployeeTaskInterface $employeetaskObject
	 *
	 * @param EmployeeTaskInterface $employeetaskObject
	 * @return EmployeeTaskInterface
	 * @throws \Exception
	 */
	 
	 public function saveCategory(EmployeeTaskCategory $employeetaskObject);
	 
	/*
	* Save the EmployeeTask record of a student
	*/
	
	public function saveEmployeeTaskRecord(EmployeeTask $employeetaskObject);
	 
	/*
	* List Staff to add awards etc
	*/
	
	public function getStaffList($staffName, $staffId, $organisation_id);
	
	/*
	* Get the list of disciplinary action of students after search funcationality
	*/
	
	public function getStaffEmployeeTaskList($staffName, $staffId, $organisation_id);
	
	/**
	 * 
	 * @param type $id
	 * 
	 * to find Category details to edit/display
	 */
	public function getEmployeeTaskCategoryDetails($id);
	
	public function getEmployeeTaskRecordDetails($id);
	
	/**
	 * 
	 * @param type $id
	 * 
	 * to find Staff details so that their names are displayed
	 */
	public function getStaffDetails($id);
	
	/*
	* Get the disciplinary record of the students
	*/
	
	public function getEmployeeTaskRecord($organisation_id);
	
	/*
	* Get the list of disciplinary records by a student
	*/
	
	public function getStaffEmployeeTaskRecords($staff_id);
	 
	 
	 /**
	 * Should return a set of all objectives that we can iterate over. 
	 * 
	 * The purpose of the function is the objectives for the dropdown select list
	 *
	 * @return array|DisciplineInterface[]
	*/
	
	public function listSelectData($tableName, $columnName, $organisation_id, $id);

	public function listSelectData1($tableName, $id);

	public function getFileName($file_id);

	public function getstafftaskRecord($staff_id,$from_date, $to_date);
		
		
}
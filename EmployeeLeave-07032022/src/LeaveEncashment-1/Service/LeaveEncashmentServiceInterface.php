<?php

namespace LeaveEncashment\Service;

use LeaveEncashment\Model\LeaveEncashment;

//need to add more models

interface LeaveEncashmentServiceInterface
{
	/*
	 * Get the Organisation Id
	 */
	 
	public function getOrganisationId($username);
	 
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username);
	
	/*
	* take username and returns Name and any other detail required
	*/
	
	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);
	
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|LeaveEncashmentInterface[]
	*/
	
	public function listAll($tableName);

	/**
	 * Should return a single proposal
	 *
	 * @param int $id Identifier of the proposal that should be returned
	 * @return LeaveEncashmentInterface
	 */
	 
	public function findEmployeeId($id);

	
	/**
	 * Should return a single proposal
	 *
	 * @param int $id Identifier of the proposal that should be returned
	 * @return LeaveEncashmentInterface
	 */
	 
	public function findEmployeeDetails($tableName, $id);
        
	 /**
	 * @param LeaveEncashmentInterface $leaveObject
	 *
	 * @param LeaveEncashmentInterface $leaveObject
	 * @return LeaveEncashmentInterface
	 * @throws \Exception
	 */
	 
	 public function save(LeaveEncashment $leaveObject);
	 
	 /*
	 * Get the leave balance of an employee
	 */	
	  
	 public function getLeaveBalance($employee_details_id);
	 
	 /*
	 * Get whether the employee has encashed his/her leave or not.
	 */	
	  
	 public function getLeaveEncashed($employee_details_id);

	 public function crossCheckLeaveEncashment($employee_details_id);

	 public function crossCheckApprovedLeaveEncashment($employee_details_id);
	 
	 /*
	 * Get the list of leave encashment
	 */	
	  
	 public function getLeaveEncashment($status, $employee_details_id, $organisation_id, $userrole, $departments_id);
	 
	 /*
	 * Get the leave encashment status
	 */	
	  
	 public function getLeaveEncashmentStatus($id, $authority);

	 public function getSupervisorEmailId($userrole, $departments_units_id);

	 public function getLeaveEncashmentApplicant($employee_details_id);

	 public function listEmpApprovedLeaveEncashment($order_no, $organisation_id);

	 public function getEmployeeDetails($id);

	 public function getLeaveEncashmentDetails($id);

	 public function getFileName($id, $column_name);
	 
	 /*
	 * Update the Leave Encashment (i.e. approve or reject)
	 */
	 
	 public function updateLeaveEncashment($id, $status, $employee_details_id);

	 public function updateEmpLeaveEncashmentOrder($data, $id);
	 
	 /**
	 * Should return a set of all objectives that we can iterate over. 
	 * 
	 * The purpose of the function is the objectives for the dropdown select list
	 *
	 * @return array|LeaveEncashmentInterface[]
	*/
	
	public function listSelectData($tableName, $columnName);
		
		
}
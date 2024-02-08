<?php

namespace LeaveEncashment\Mapper;

use LeaveEncashment\Model\LeaveEncashment;

interface LeaveEncashmentMapperInterface
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
	 * @param int/string $id
	 * @return LeaveEncashment
	 * throws \InvalidArugmentException
	 * 
	*/
	
	public function findEmployeeId($id);


	/**
	 * 
	 * @return array/ LeaveEncashment[]
	 */
	 
	public function findAll($tableName);
	
	/**
	 * 
	 * @param type $LeaveEncashmentInterface
	 * 
	 * to save budgetings
	 */
	
	public function save(LeaveEncashment $LeaveEncashmentInterface);
	
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

	public function getLeaveEncashmentDetails($id);

	public function getEmployeeDetails($id);

	public function getFileName($id, $column_name);
	
	/*
	 * Update the Leave Encashment (i.e. approve or reject)
	 */
	 
	public function updateLeaveEncashment($id, $status, $employee_details_id);


	public function updateEmpLeaveEncashmentOrder($data, $id);
		
	/**
	 * 
	 * @return array/ LeaveEncashment[]
	 */
	 
	public function listSelectData($tableName, $columnName);
	
}
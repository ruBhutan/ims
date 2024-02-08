<?php

namespace EmployeeLeave\Mapper;

use EmployeeLeave\Model\EmployeeLeave;
use EmployeeLeave\Model\OnbehalfEmployeeLeave;
use EmployeeLeave\Model\OfficiatingSupervisor;
use EmployeeLeave\Model\CancelledLeave;

interface EmployeeLeaveMapperInterface
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
	 * @return EmployeeLeave
	 * throws \InvalidArugmentException
	 * 
	*/
	
	public function findEmployeeId($id);


	public function getSupervisorEmailId($userrole, $departments_units_id, $emp_leave_category_id);


	public function getLeaveApplicant($employee_details_id);

	public function getApprovedLeaveApplicantDetails($id);

	public function getPresidentDetails($organisation_id);

	public function getApprovedLeaveSubstitution($id);

	public function getApplicantName($id);

	public function getOnBehalfStaffDetails($employee_details_id);
	
	/**
	 * Finds list of employees name and emp id for those that have
	 * applied for leave
	 * 
	*/
	
	public function findEmployeeDetails($empIds);

	/**
	 * 
	 * @return array/ EmployeeLeave[]
	 */
	 
	public function findAll($tableName);
    
	/**
	 * 
	 * @return array/ EmployeeLeave[]
	 */
	 
	public function findAllLeave($status, $employee_details_id, $userrole, $organisation_id, $departments_id);

	
	/**
	 * 
	 * @return array/ EmployeeLeave[]
	 */
	 
	public function listLeaveEmployee($role, $organisation_id);


	public function getEmpApprovedLeaveList($organisation_id);
	    
	/**
	 * 
	 * @param type $id
	 * 
	 * to find Leave Type related to the $id
	 */
	
	public function findLeaveType($id);
	
	/**
	 * 
	 * @param type $id
	 * 
	 * to find Leave Type related to the $id
	 */
	
	public function findLeave($id);
	
	/**
	 * 
	 * @param type $id
	 * 
	 * to update leave status
	 */
	
	public function updateLeave($id, $leaveStatus, $remarks, $employee_details_id);


	public function updateEmpApprovedLeave(CancelledLeave $EmployeeLeaveInterface);
	
	/**
	 * 
	 * @param type $id
	 * 
	 * to update leave status
	 */
	
	public function listLeaveCategory();


	public function getEmployeeOccupationalGroup($employee_details_id);

	
	/**
	 * 
	 * @param type $EmployeeLeaveInterface
	 * 
	 * to save budgetings
	 */
	
	public function saveDetails(EmployeeLeave $EmployeeLeaveInterface);


	public function saveOnBehalfLeave(OnbehalfEmployeeLeave $EmployeeLeaveInterface);
	
	/*
	* Save Officiating Officer
	*/
	 
	public function saveOfficiatingOfficer(OfficiatingSupervisor $leaveObject, $supervisor_id, $employee_details_id, $userrole);

	 /*
	 *Save edited staff leave balance
	 **/
	 public function updateEmpLeaveBalance($id, $casual_leave, $earned_leave, $employee_details_id);

	 public function getEmpOfficiatedRole($officiating, $from_date, $to_date, $userrole);

	 public function crossCheckCancelledLeave($emp_leave_id);

	 public function crossCheckOwnOfficiating($employee_details_id, $from_date);
	 
	/*
	* Get list of officers to officiate
	*/
	 
	public function getOfficiatingList($employee_details_id);
	
	/*
	 * Get Officiating Details
	 */
	 
	public function getOfficiatingDetails($id);
	
	/*
	 * Get the list of employees to be assigned officiating role
	 */
	 
	public function getEmployeeList($organisation_id);
	
	/*
	 * Get the Leave taken by an employee
	 */
	 
	public function getLeaveTaken($employee_details_id, $type);
	
	/*
	 * Get the Earned Leave balance of an employee
	 */
	 
	public function getLeaveBalance($employee_details_id);

	public function crossCheckAppliedLeave($employee_details_id);
	
	public function getStaffAppliedLeave($employee_details_id, $emp_leave_category_id, $status);


	public function getLeaveCategory($emp_leave_category_id);


	/*
	 *Get the details of staff from the leave balance id
	 **/
	 public function getEmployeeDetails($id);

	 /*
	 *Get the details of leave balance from leave balance id
	 **/
	 public function getEmpLeaveBalanceDetails($id);
	
	/*
	 * Get Notification Details
	 */
	 
	public function getNotificationDetails($id, $role, $departments_id);


	public function getEmployeeLeaveDetails($organisation_id);
	
	/*
	 * Get the name of the file to download
	 */
	 
	public function getFileName($leave_id);

	public function getOfficiatingFileName($id);

	/**
	 * 
	 * @return array/ EmployeeLeave[]
	 */
	 
	public function listSelectData($tableName, $columnName);
	
}
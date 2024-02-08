<?php

namespace EmployeeLeave\Service;

use EmployeeLeave\Model\EmployeeLeave;
use EmployeeLeave\Model\OnbehalfEmployeeLeave;
use EmployeeLeave\Model\OfficiatingSupervisor;
use EmployeeLeave\Model\CancelledLeave;

//need to add more models

interface EmployeeLeaveServiceInterface
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
	 * @return array|EmployeeLeaveInterface[]
	*/
	
	public function listAll($tableName);
	
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|EmployeeLeaveInterface[]
	*/
	
	public function listAllLeave($status, $employee_details_id, $userrole, $organisation_id, $departments_id);
	
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|EmployeeLeaveInterface[]
	*/
	
	public function listLeaveEmployee($role, $organisation_id);

	public function getEmpApprovedLeaveList($organisation_id);
	
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|EmployeeLeaveInterface[]
	*/
	
	public function findEmployeeDetails($empIds);

	/**
	 * Should return a single proposal
	 *
	 * @param int $id Identifier of the proposal that should be returned
	 * @return EmployeeLeaveInterface
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
	 * Should return a single proposal
	 *
	 * @param int $id Identifier of the Proposal that should be returned
	 * @return EmployeeLeaveInterface
	 */
        
     public function findLeaveType($id);
	 
	 /**
	 * Should return a single proposal
	 *
	 * @param int $id Identifier of the Proposal that should be returned
	 * @return EmployeeLeaveInterface
	 */
        
     public function findLeave($id);
	 
	 /**
	 * List Leave Categories
	 *
	 */
	 
	 public function listLeaveCategory();

	 public function getEmployeeOccupationalGroup($employee_details_id);
	 
	/**
	 * Update Leave
	 *
	 */
	 
	 public function updateLeave($id, $leaveStatus, $remarks, $employee_details_id);

	 public function updateEmpApprovedLeave(CancelledLeave $leaveObject);


	 /**
	 * @param EmployeeLeaveInterface $leaveObject
	 *
	 * @param EmployeeLeaveInterface $leaveObject
	 * @return EmployeeLeaveInterface
	 * @throws \Exception
	 */
	 
	 public function save(EmployeeLeave $leaveObject);

	 public function saveOnBehalfLeave(OnbehalfEmployeeLeave $leaveObject);
	 
	 /*
	 * Save Officiating Officer
	 */
	 
	 public function saveOfficiatingOfficer(OfficiatingSupervisor $leaveObject, $supervisor_id, $employee_details_id, $userrole);

	 /*
	 *Save edited staff leave balance
	 **/
	 public function updateEmpLeaveBalance($id, $casual_leave, $earned_leave, $annual_leave, $employee_details_id);

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

	 public function getLeaveCategory($emp_leave_category_id);
	 
	 /*
	 * Get the Earned Leave balance of an employee
	 */
	 
	 public function getLeaveBalance($employee_details_id);


	 public function crossCheckAppliedLeave($employee_details_id);
	 
	 public function getStaffAppliedLeave($employee_details_id, $emp_leave_category_id, $status);


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
	 * Should return a set of all objectives that we can iterate over. 
	 * 
	 * The purpose of the function is the objectives for the dropdown select list
	 *
	 * @return array|EmployeeLeaveInterface[]
	*/
	
	public function listSelectData($tableName, $columnName);

}
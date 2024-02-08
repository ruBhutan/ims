<?php

namespace EmpAttendance\Service;

use EmpAttendance\Model\EmpAttendance;

//need to add more models

interface EmpAttendanceServiceInterface
{
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|EmpAttendanceInterface[]
	*/
	
	public function listAll($tableName);
	
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
	 * Should return a single proposal
	 *
	 * @param int $id Identifier of the proposal that should be returned
	 * @return EmpAttendanceInterface
	 */
	 
	public function findFunction($id);
        
	 /**
	 * @param EmpAttendanceInterface $attendanceObject
	 *
	 * @param EmpAttendanceInterface $attendanceObject
	 * @return EmpAttendanceInterface
	 * @throws \Exception
	 */
	 
	 public function save(EmpAttendance $attendanceObject);
	 
	 /*
	 * Save the Attendance Record
	 */
	 
	 public function saveAttendanceRecord($unit_name, $from_date, $to_date, $data);
	 
	 /*
	 * Get Employee Attendance Data
	 * This will look at the staff tour, leave (EOL, study etc)., absent etc.
	 */
	 
	 public function getEmployeeAttendance($from_date, $to_date, $unit, $organisation_id);
	 
	 /*
	 * check to see whether the attendance has been recorded or not
	 */
	 
	 public function getAttendanceRecordDates($from_date, $to_date, $unitName);
	 
	 /*
	 * get list of absentees
	 */
	 
	 public function getAbsenteeList($from_date, $to_date, $unitName);
	 
	 /*
	 * Get the weekends
	 */
	 
	 public function getWeekends($from_date, $to_date);
	 
	 /*
	 * Get the list of staff for a given unit in an organisation
	 * Will be used for entering the attendance
	 */
	 
	 public function getStaffList($unitName, $organisation_id);
	 
	 /**
	 * Should return a set of all objectives that we can iterate over. 
	 * 
	 * The purpose of the function is the objectives for the dropdown select list
	 *
	 * @return array|EmpAttendanceInterface[]
	*/
	
	public function listSelectData($tableName, $columnName, $organisation_id);
		
		
}
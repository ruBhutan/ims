<?php

namespace EmpAttendance\Mapper;

use EmpAttendance\Model\EmpAttendance;

interface EmpAttendanceMapperInterface
{
	/**
	 * @param int/string $id
	 * @return EmpAttendance
	 * throws \InvalidArugmentException
	 * 
	*/
	
	public function findFunction($id);
	
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
	 * 
	 * @return array/ EmpAttendance[]
	 */
	 
	public function findAll($tableName);
        
	/**
	 * 
	 * @param type $EmpAttendanceInterface
	 * 
	 * to save budgetings
	 */
	
	public function save(EmpAttendance $EmpAttendanceInterface);
	
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
	 * 
	 * @return array/ EmpAttendance[]
	 */
	 
	public function listSelectData($tableName, $columnName, $organisation_id);
	
}
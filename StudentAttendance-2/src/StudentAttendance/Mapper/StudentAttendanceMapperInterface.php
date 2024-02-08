<?php

namespace StudentAttendance\Mapper;

use StudentAttendance\Model\StudentAttendance;

interface StudentAttendanceMapperInterface
{
	/**
	 * @param int/string $id
	 * @return StudentAttendance
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
	
	public function getUserDetailsId($username, $tableName);

	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);

	/**
	 * 
	 * @return array/ StudentAttendance[]
	 */
	 
	public function findAll($tableName);
        	
	/*
	* Save the Attendance Record
	*/
	 
	public function saveAttendanceRecord($studentList, $timetable_dates, $attendance_data, $module, $programe, $section, $employee_details_id);
	
	/*
	 * Save the Edited Attendance Record
	 */
	 
	 public function saveEditedAttendanceRecord($studentList, $timetable_dates, $attendance_data, $module, $programe, $section, $employee_details_id);

	 public function updateDeletedStudentAttendance($from_date, $to_date, $attendance_data, $academic_modules_allocation_id, $programme, $section);
	
	 /*
	 * Save Extra Class Attendance
	 */
	 
	 public function saveExtraClassAttendance($studentList, $from_date, $from_time, $attendance_data, $module, $programe, $section, $employee_details_id);
	
	/*
	 * Save the cancelled lectures details
	 */
	 
	public function saveCancelledLectures($timetable_dates, $lectures_data, $section, $module, $programme, $lectures_reasons);
	
	/*
	 * Get details of cancelled lecture for editing purposes
	 */
	 
	public function getCancelledLectureDetail($id);
	
	/*
	 * Get Student Attendance Data
	 */
	 
	public function getStudentAttendance($from_date, $to_date, $academic_modules_allocation_id, $year);
	
	/*
	 * Get the Attendance for a date and a module for editing
	 */
	 
	public function getStudentAttendanceList($programme, $module, $year, $from_date);
	
	/*
	 * check to see whether the attendance has been recorded or not
	 */
	 
	public function getAttendanceRecordDates($from_date, $to_date, $module, $programme, $section);

	public function getAcademicModulesAllocationId($programme, $module, $organisation_id);
	
	/*
	 * Get the last date of attendance entry
	 */
	 
	public function getLastAttendanceDate($module, $programme, $section);
	
	/*
	* Get the dates in an array according to the timetable
	*/
	 
	public function getAttendanceDates($from_date, $to_date, $section, $module, $programme);
	
	/*
	 * Check whether the attendance has been entered
	 */
	 
	public function checkAttendanceDate($section, $module, $from_date);

	public function checkAttendanceDateRange($section, $module, $from_date);
	
	/*
	* Get the timetable with the dates
	*/
	 
	public function getTimetableWithDates($from_date, $to_date, $section, $module, $programme);
	
	/*
	 * Get Extra Class Dates
	 */
	 
	 public function getExtraClassDates($from_date, $section, $module, $programme);
	
	/*
	* Get timetable for a given module
	* used by attendance to get the days of the week
	*/
	
	public function getTimeTable($section, $academic_modules_allocation_id);
	
	/*
	 * get list of absentees
	 */
	 
	public function getAbsenteeList($from_date, $to_date, $module, $programme);
	
	/*
	 * Get the Student Attendance Record (Contact Hours, % attendance etc)
	 */
	 
	public function getStudentAttendanceRecord($programme, $module, $section, $from_date, $to_date);
	
	/*
	 * Get Individual student attendance record
	 */
	 
	public function getIndividualStudentAttendanceRecord($student_id, $academic_modules_allocation_id);
	
	/*
	* Generate Consolidated Student Attendance
	*/
	
	public function generateConsolidatedAttendance($data);
	
	/*
	 * Get Contact hours
	 */
	 
	 public function getModuleContactHours($academic_modules_allocation_id);
	 
	 /*
	 * Get the Module Tutor
	 */
	 
	 public function getModuleTutor($module, $section);
	 
	 /*
	 * Get total lecture delivered
	 */
	 
	 public function getTotalLecturesDelivered($academic_modules_allocation_id);
	 
	 /*
	 * Get Total Lecture Hours
	 */
	 
	 public function getTotalLectureHours($academic_modules_allocation_id, $organisation_id);
	 
	 /*
	 * Get the lecture length
	 */
	 
	 public function getLectureLength($organisation_id);
	
	/*
	 * Get the list of staff for a given unit in an organisation
	 * Will be used for entering the attendance
	 */
	 
	public function getStudentList($programme, $academic_modules_allocation_id, $section, $year, $status);
	
	/*
	* Get the max. duration of Programmes for Organisation
	*/
	
	public function getMaxProgrammeDuration($organisation_id);
	
	/*
	* Get the list of Months for Present Semester
	*/
	
	public function getMonthList($organisation_id);
	
	/*
	* Get the Name of Programme for Displaying
	*/
	
	public function getProgrammeName($programme_id);
	
	/*
	* Get the programme id given the academic modules allocation id
	*/
	
	public function getProgrammeId($academic_modules_allocation_id);
	
	/*
	* Get the name of module for displaying
	*/
	
	public function getModuleCode($academic_modules_allocation_id);
	
	/*
	* Crosscheck and see whether id from route is student id
	*/
	
	public function crosscheckStudentId($student_id);
	
	/**
	 * 
	 * @return array/ StudentAttendance[]
	 */
	 
	public function listSelectData($tableName, $columnName, $organisation_id);
	
}
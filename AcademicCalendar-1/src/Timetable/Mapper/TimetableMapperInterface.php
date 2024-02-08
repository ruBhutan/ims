<?php

namespace Timetable\Mapper;

use Timetable\Model\Timetable;
use Timetable\Model\UploadTimetable;
use Timetable\Model\TimetableTiming;

interface TimetableMapperInterface
{

	/**
	 * 
	 * @return array/ Timetable[]
	 */
	 
	public function findAll($tableName);
	
	/*
	* Get the timetable
	*/
	
	public function getTimetable($programme, $section, $year, $organisation_id);

	public function checkAllocatedModuleTutor($employee_details_id);
	
	/*
	* Get timetable for module tutor
	*/
	
	public function getTutorTimetable($employee_details_id, $status);
	
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username);
	
	/*
	* Get organisation id based on the username
	*/
	
	public function getOrganisationId($username);

	public function getUserDetails($username, $tableName);
        	
	/**
	 * 
	 * @param type $TimetableInterface
	 * 
	 * to save timetables
	 */
	
	public function saveTimetable(Timetable $TimetableInterface);
	
	/*
	* Save Timetable
	*/
	
	public function saveTimings(TimetableTiming $timetableObject);
        
	/*
	 * Upload timetable file for Academic Year
	 */
	
	public function saveTimetableFile(UploadTimetable $uploadModel, $organisation_id);
	
	/*
	* Get the timetable timings given an organisation
	*/
	
	public function getTimingsList($organisation_id);
	
	/*
	* Get the timetable timings given an id
	*/
	
	public function getTimingDetails($id);
	
	/*
	* Get Timetable Details for Editing
	*/
	
	public function getTimetableDetails($id);
	
	/*
	* Get the timetable timings given an organisation e.g. 09:00-10:00 etc
	* Used when to view timetable
	*/
	
	public function getTimetableTiming($organisation_id);
	
	/*
	* Get the max. duration of Programmes for Organisation
	*/
	
	public function getMaxProgrammeDuration($organisation_id);
	
	/*
	* Crosscheck whether attendance for timetable has been entered
	*/
	
	public function checkTimetableAttendance($id);
	
	/*
	* Crosscheck whether timetable has been entered
	*/
	
	public function crosscheckTimetable($timetableModel);

	public function crosscheckTiming($timetableModel);
	
	/*
	* Delete Timetable
	*/
	
	public function deleteTimetable($id);
		
	/**
	 * 
	 * @return array/ Timetable[]
	 */
	 
	public function listSelectData($tableName, $columnName, $organisation_id);
	
}
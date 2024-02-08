<?php

namespace ExtraCurricularAttendance\Mapper;

use ExtraCurricularAttendance\Model\ExtraCurricularAttendance;
use ExtraCurricularAttendance\Model\SocialEvent;
use ExtraCurricularAttendance\Model\ClubAttendance;

interface ExtraCurricularAttendanceMapperInterface
{
	
	/*
	* take username and returns employee details id/student id
	*/
	
	public function getUserDetailsId($username, $tableName);
	
	/*
	 * Get the Organisation Id
	*/
	 
	public function getOrganisationId($username);

	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);

	/**
	 * @param int/string $id
	 * @return ExtraCurricularAttendance
	 * throws \InvalidArugmentException
	 * 
	*/
	
	public function findAttendance($id);
	
	/*
	* Get Social Event Details
	*/
	
	public function getSocialEvent($id);

	/**
	 * 
	 * @return array/ ExtraCurricularAttendance[]
	 */
	 
	public function findAll($tableName, $organisation_id);
        	
	/**
	 * 
	 * @param type $ExtraCurricularAttendanceInterface
	 * 
	 * to save budgetings
	 */
	
	public function saveExtraCurricularAttendance($data, $programme, $year, $studentName, $studentId, $event_name, $date_event, $organisation_id);

	public function updateExtraCurricularAttendance($data, $programme, $year, $studentName, $studentId, $event_name, $date_event, $organisation_id);
	
	/**
	 * 
	 * @param type $ExtraCurricularAttendanceInterface
	 * 
	 * to save budgetings
	 */
	
	public function saveClubAttendance($data, $clubs_id, $date, $organisation_id);

	public function updateClubAttendance($data, $clubsId, $date, $organisation_id);
	
	/*
	* Save Social Event
	*/
	
	public function saveSocialEvent(SocialEvent $eventModel);


	public function crossCheckExtraCurricularAttendance($programme, $year, $event_name);

	public function crossCheckClubMembers($clubId, $organisation_id);

	public function crossCheckClubAttendance($student_clubs_members, $attendance_date);
		
	/*
	* List Student to add awards etc
	*/
	
	public function getStudentList($studentName, $studentId, $programme, $year, $organisation_id);
	
	/*
	* Count of the students
	*/
	
	public function getStudentCount($studentName, $studentId, $programme, $year, $organisation_id);

	public function getExtraCurricularAttendanceList($programme, $year, $event_name, $organisation_id);

	public function getStudentExtraCurricularAttendance($studentName, $studentId, $programme, $year, $event_name, $organisation_id);
	
	/*
	* List Students in clubs
	*/
	
	public function getStudentClubList($clubId, $organisation_id);


	public function getStudentClubAttendance($clubId, $organisation_id, $attendance_date);
	
	/*
	* Get No. of Students in clubs
	*/
	
	public function getStudentClubCount($clubId, $organisation_id);
	
	/**
	 * 
	 * @param type $id
	 * 
	 * to find Student details so that their names are displayed
	 */
	public function getStudentDetails($id);
	
	/**
	 * 
	 * @return array/ ExtraCurricularAttendance[]
	 */
	 
	public function listSelectData($tableName, $columnName, $organisation_id);
	
}
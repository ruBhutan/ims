<?php

namespace ExtraCurricularAttendance\Service;

use ExtraCurricularAttendance\Mapper\ExtraCurricularAttendanceMapperInterface;
use ExtraCurricularAttendance\Model\ExtraCurricularAttendance;
use ExtraCurricularAttendance\Model\ClubAttendance;
use ExtraCurricularAttendance\Model\SocialEvent;

class ExtraCurricularAttendanceService implements ExtraCurricularAttendanceServiceInterface
{
	/**
	 * @var \Blog\Mapper\ExtraCurricularAttendanceMapperInterface
	*/
	
	protected $attendanceMapper;
	
	public function __construct(ExtraCurricularAttendanceMapperInterface $attendanceMapper) {
		$this->attendanceMapper = $attendanceMapper;
	}
		
	public function getUserDetailsId($username, $tableName)
	{
		return $this->attendanceMapper->getUserDetailsId($username, $tableName);
	}
	
	public function getOrganisationId($username)
	{
		return $this->attendanceMapper->getOrganisationId($username);
	}

	public function getUserDetails($username, $usertype)
	{
		return $this->attendanceMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->attendanceMapper->getUserImage($username, $usertype);
	}
	
	public function listAll($tableName, $organisation_id)
	{
		return $this->attendanceMapper->findAll($tableName, $organisation_id);
	}
	 
	public function findAttendance($id)
	{
		return $this->attendanceMapper->findAttendance($id);
	}
	
	public function getSocialEvent($id)
	{
		return $this->attendanceMapper->getSocialEvent($id);
	}
        	
	public function saveExtraCurricularAttendance($data, $programme, $year, $studentName, $studentId, $event_name, $date_event, $organisation_id) 
	{
		return $this->attendanceMapper->saveExtraCurricularAttendance($data, $programme, $year, $studentName, $studentId, $event_name, $date_event, $organisation_id);
	}


	public function updateExtraCurricularAttendance($data, $programme, $year, $studentName, $studentId, $event_name, $date_event, $organisation_id) 
	{
		return $this->attendanceMapper->updateExtraCurricularAttendance($data, $programme, $year, $studentName, $studentId, $event_name, $date_event, $organisation_id);
	}

	
	public function saveClubAttendance($data, $clubs_id, $date, $organisation_id) 
	{
		return $this->attendanceMapper->saveClubAttendance($data, $clubs_id, $date, $organisation_id);
	}

	public function updateClubAttendance($data, $clubsId, $date, $organisation_id)
	{
		return $this->attendanceMapper->updateClubAttendance($data, $clubsId, $date, $organisation_id);
	}
		
	public function saveSocialEvent(SocialEvent $eventModel)
	{
		return $this->attendanceMapper->saveSocialEvent($eventModel);
	}

	public function crossCheckExtraCurricularAttendance($programme, $year, $event_name)
	{
		return $this->attendanceMapper->crossCheckExtraCurricularAttendance($programme, $year, $event_name);
	}


	public function crossCheckClubMembers($clubId, $organisation_id)
	{
		return $this->attendanceMapper->crossCheckClubMembers($clubId, $organisation_id);
	}


	public function crossCheckClubAttendance($student_clubs_members, $attendance_date)
	{
		return $this->attendanceMapper->crossCheckClubAttendance($student_clubs_members, $attendance_date);
	}
			
	public function getStudentList($studentName, $studentId, $programme, $year, $organisation_id)
	{
		return $this->attendanceMapper->getStudentList($studentName, $studentId, $programme, $year, $organisation_id);
	}
	
	public function getStudentCount($studentName, $studentId, $programme, $year, $organisation_id)
	{
		return $this->attendanceMapper->getStudentCount($studentName, $studentId, $programme, $year, $organisation_id);
	}

	public function getExtraCurricularAttendanceList($programme, $year, $event_name, $organisation_id)
	{
		return $this->attendanceMapper->getExtraCurricularAttendanceList($programme, $year, $event_name, $organisation_id);
	}


	public function getStudentExtraCurricularAttendance($studentName, $studentId, $programme, $year, $event_name, $organisation_id)
	{
		return $this->attendanceMapper->getStudentExtraCurricularAttendance($studentName, $studentId, $programme, $year, $event_name, $organisation_id);
	}
	
	public function getStudentClubList($clubId, $organisation_id)
	{
		return $this->attendanceMapper->getStudentClubList($clubId, $organisation_id);
	}


	public function getStudentClubAttendance($clubId, $organisation_id, $attendance_date)
	{
		return $this->attendanceMapper->getStudentClubAttendance($clubId, $organisation_id, $attendance_date);
	}

		
	public function getStudentClubCount($clubId, $organisation_id)
	{
		return $this->attendanceMapper->getStudentClubCount($clubId, $organisation_id);
	}
	
	public function getStudentDetails($id)
	{
		return $this->attendanceMapper->getStudentDetails($id);
	}
	
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		return $this->attendanceMapper->listSelectData($tableName, $columnName, $organisation_id);
	}
	
}
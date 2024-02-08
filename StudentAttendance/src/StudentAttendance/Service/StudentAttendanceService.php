<?php

namespace StudentAttendance\Service;

use StudentAttendance\Mapper\StudentAttendanceMapperInterface;
use StudentAttendance\Model\StudentAttendance;

class StudentAttendanceService implements StudentAttendanceServiceInterface
{
	/**
	 * @var \Blog\Mapper\StudentAttendanceMapperInterface
	*/
	
	protected $attendanceMapper;
	
	public function __construct(StudentAttendanceMapperInterface $attendanceMapper) {
		$this->attendanceMapper = $attendanceMapper;
	}
	
	public function listAll($tableName)
	{
		return $this->attendanceMapper->findAll($tableName);
	}
	
	public function getOrganisationId($username)
	{
		return $this->attendanceMapper->getOrganisationId($username);
	}
	
	public function getUserDetailsId($username, $tableName)
	{
		return $this->attendanceMapper->getUserDetailsId($username, $tableName);
	}

	public function getUserDetails($username, $usertype)
	{
		return $this->attendanceMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->attendanceMapper->getUserImage($username, $usertype);
	}
	 
	public function findFunction($id)
	{
		return $this->attendanceMapper->findFunction($id);
	}
    	
	public function saveAttendanceRecord($studentList, $timetable_dates, $attendance_data, $module, $programe, $section, $employee_details_id)
	{
		return $this->attendanceMapper->saveAttendanceRecord($studentList, $timetable_dates, $attendance_data, $module, $programe, $section, $employee_details_id);
	}
	 
	public function saveEditedAttendanceRecord($studentList, $timetable_dates, $attendance_data, $module, $programe, $section, $employee_details_id)
	{
		return $this->attendanceMapper->saveEditedAttendanceRecord($studentList, $timetable_dates, $attendance_data, $module, $programe, $section, $employee_details_id);
	}

	public function updateDeletedStudentAttendance($from_date, $to_date, $attendance_data, $academic_modules_allocation_id, $programme, $section)
	{
		return $this->attendanceMapper->updateDeletedStudentAttendance($from_date, $to_date, $attendance_data, $academic_modules_allocation_id, $programme, $section);
	}
	 
	public function saveExtraClassAttendance($studentList, $from_date, $from_time, $attendance_data, $module, $programe, $section, $employee_details_id)
	{
		return $this->attendanceMapper->saveExtraClassAttendance($studentList, $from_date, $from_time, $attendance_data, $module, $programe, $section, $employee_details_id);
	}
		 
	public function saveCancelledLectures($timetable_dates, $lectures_data, $section, $module, $programme, $lectures_reasons)
	{
		return $this->attendanceMapper->saveCancelledLectures($timetable_dates, $lectures_data, $section, $module, $programme, $lectures_reasons);
	}
		 
	public function getCancelledLectureDetail($id)
	{
		return $this->attendanceMapper->getCancelledLectureDetail($id);
	}
		 
	public function getStudentAttendance($from_date, $to_date, $academic_modules_allocation_id, $year)
	{
		return $this->attendanceMapper->getStudentAttendance($from_date, $to_date, $academic_modules_allocation_id, $year);
	}
		 
	public function getStudentAttendanceList($programme, $module, $year, $from_date)
	{
		return $this->attendanceMapper->getStudentAttendanceList($programme, $module, $year, $from_date);
	}
		 
	public function getAttendanceRecordDates($from_date, $to_date, $module, $programme, $section)
	{
		return $this->attendanceMapper->getAttendanceRecordDates($from_date, $to_date, $module, $programme, $section);
	}

	public function getAcademicModulesAllocationId($programme, $module, $organisation_id)
	{
		return $this->attendanceMapper->getAcademicModulesAllocationId($programme, $module, $organisation_id);
	}
	 
	public function getLastAttendanceDate($module, $programme, $section)
	{
		return $this->attendanceMapper->getLastAttendanceDate($module, $programme, $section);
	}
	 
	public function getAttendanceDates($from_date, $to_date, $section, $module, $programme)
	{
		return $this->attendanceMapper->getAttendanceDates($from_date, $to_date, $section, $module, $programme);
	}
	 
	public function checkAttendanceDate($section, $module, $from_date)
	{
		return $this->attendanceMapper->checkAttendanceDate($section, $module, $from_date);
	}

	public function checkAttendanceDateRange($section, $module, $from_date)
	{
		return $this->attendanceMapper->checkAttendanceDateRange($section, $module, $from_date);
	}
		 
	public function getTimetableWithDates($from_date, $to_date, $section, $module, $programme)
	{
		return $this->attendanceMapper->getTimetableWithDates($from_date, $to_date, $section, $module, $programme);
	}
	 
	public function getExtraClassDates($from_date, $section, $module, $programme)
	{
		return $this->attendanceMapper->getExtraClassDates($from_date, $section, $module, $programme);
	}
	
	public function getTimeTable($section, $academic_modules_allocation_id)
	{
		return $this->attendanceMapper->getTimeTable($section, $academic_modules_allocation_id);
	}
	 
	public function getAbsenteeList($from_date, $to_date, $module, $programme)
	{
		return $this->attendanceMapper->getAbsenteeList($from_date, $to_date, $module, $programme);
	}
	 
	public function getStudentAttendanceRecord($programme, $module, $section, $from_date, $to_date)
	{
		return $this->attendanceMapper->getStudentAttendanceRecord($programme, $module, $section, $from_date, $to_date);
	}
	 
	public function getIndividualStudentAttendanceRecord($student_id, $academic_modules_allocation_id)
	{
		return $this->attendanceMapper->getIndividualStudentAttendanceRecord($student_id, $academic_modules_allocation_id);
	}
	
	public function generateConsolidatedAttendance($data)
	{
		return $this->attendanceMapper->generateConsolidatedAttendance($data);
	}
	 
	public function getModuleContactHours($academic_modules_allocation_id)
	{
		return $this->attendanceMapper->getModuleContactHours($academic_modules_allocation_id);
	}
	 
	public function getModuleTutor($module, $section)
	{
		return $this->attendanceMapper->getModuleTutor($module, $section);
	}
	 
	public function getTotalLecturesDelivered($academic_modules_allocation_id)
	{
		return $this->attendanceMapper->getTotalLecturesDelivered($academic_modules_allocation_id);
	}
	 
	public function getTotalLectureHours($academic_modules_allocation_id, $organisation_id)
	{
		return $this->attendanceMapper->getTotalLectureHours($academic_modules_allocation_id, $organisation_id);
	}
	 
	public function getLectureLength($organisation_id)
	{
		return $this->attendanceMapper->getLectureLength($organisation_id);
	}
		 
	public function getStudentList($programme, $academic_modules_allocation_id, $section, $year, $status)
	{
		return $this->attendanceMapper->getStudentList($programme, $academic_modules_allocation_id, $section, $year, $status);
	}
	
	public function getMaxProgrammeDuration($organisation_id)
	{
		return $this->attendanceMapper->getMaxProgrammeDuration($organisation_id);
	}
	
	public function getMonthList($organisation_id)
	{
		return $this->attendanceMapper->getMonthList($organisation_id);
	}
	
	public function getProgrammeName($programme_id)
	{
		return $this->attendanceMapper->getProgrammeName($programme_id);
	}
	
	public function getProgrammeId($academic_modules_allocation_id)
	{
		return $this->attendanceMapper->getProgrammeId($academic_modules_allocation_id);
	}
	
	public function getModuleCode($academic_modules_allocation_id)
	{
		return $this->attendanceMapper->getModuleCode($academic_modules_allocation_id);
	}
	
	public function crosscheckStudentId($student_id)
	{
		return $this->attendanceMapper->crosscheckStudentId($student_id);
	}
	
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		return $this->attendanceMapper->listSelectData($tableName, $columnName, $organisation_id);
	}
	
}
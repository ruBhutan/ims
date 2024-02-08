<?php

namespace EmpAttendance\Service;

use EmpAttendance\Mapper\EmpAttendanceMapperInterface;
use EmpAttendance\Model\EmpAttendance;

class EmpAttendanceService implements EmpAttendanceServiceInterface
{
	/**
	 * @var \Blog\Mapper\EmpAttendanceMapperInterface
	*/
	
	protected $attendanceMapper;
	
	public function __construct(EmpAttendanceMapperInterface $attendanceMapper) {
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
	
	public function getUserDetailsId($username)
	{
		return $this->attendanceMapper->getUserDetailsId($username);
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
        	
	public function save(EmpAttendance $attendanceObject) 
	{
		return $this->attendanceMapper->save($attendanceObject);
	}
	
	public function saveAttendanceRecord($unit_name, $from_date, $to_date, $data)
	{
		return $this->attendanceMapper->saveAttendanceRecord($unit_name, $from_date, $to_date, $data);
	}
		 
	public function getEmployeeAttendance($from_date, $to_date, $unit, $organisation_id)
	{
		return $this->attendanceMapper->getEmployeeAttendance($from_date, $to_date, $unit, $organisation_id);
	}
		 
	public function getAttendanceRecordDates($from_date, $to_date, $unitName)
	{
		return $this->attendanceMapper->getAttendanceRecordDates($from_date, $to_date, $unitName);
	}
	 
	public function getAbsenteeList($from_date, $to_date, $unitName)
	{
		return $this->attendanceMapper->getAbsenteeList($from_date, $to_date, $unitName);
	}
		 
	public function getWeekends($from_date, $to_date)
	{
		return $this->attendanceMapper->getWeekends($from_date, $to_date);
	}
		 
	public function getStaffList($unitName, $organisation_id)
	{
		return $this->attendanceMapper->getStaffList($unitName, $organisation_id);
	}
	
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		return $this->attendanceMapper->listSelectData($tableName, $columnName, $organisation_id);
	}
	
}
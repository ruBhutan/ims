<?php

namespace Timetable\Service;

use Timetable\Mapper\TimetableMapperInterface;
use Timetable\Model\Timetable;
use Timetable\Model\UploadTimetable;
use Timetable\Model\TimetableTiming;

class TimetableService implements TimetableServiceInterface
{
	/**
	 * @var \Blog\Mapper\TimetableMapperInterface
	*/
	
	protected $timetableMapper;
	
	public function __construct(TimetableMapperInterface $timetableMapper) {
		$this->timetableMapper = $timetableMapper;
	}
	
	public function listAll($tableName)
	{
		return $this->timetableMapper->findAll($tableName);
	}
		
	public function getTimetable($programme, $section, $year, $organisation_id)
	{
		return $this->timetableMapper->getTimetable($programme, $section, $year, $organisation_id);
	}

	public function checkAllocatedModuleTutor($employee_details_id)
	{
		return $this->timetableMapper->checkAllocatedModuleTutor($employee_details_id);
	}
		
	public function getTutorTimetable($employee_details_id, $status)
	{
		return $this->timetableMapper->getTutorTimetable($employee_details_id, $status);
	}
		
	public function getUserDetailsId($username)
	{
		return $this->timetableMapper->getUserDetailsId($username);
	}
	
	public function getOrganisationId($username)
	{
		return $this->timetableMapper->getOrganisationId($username);
	}

	public function getUserDetails($username, $tableName)
	{
		return $this->timetableMapper->getUserDetails($username, $tableName);
	}
		
	public function saveTimetable(Timetable $timetableObject)
	{
		return $this->timetableMapper->saveTimetable($timetableObject);
	}
		
	public function saveTimings(TimetableTiming $timetableObject)
	{
		return $this->timetableMapper->saveTimings($timetableObject);
	}
        
	public function saveTimetableFile(UploadTimetable $uploadModel, $organisation_id)
	{
		return $this->timetableMapper->saveTimetableFile($uploadModel, $organisation_id);
	}
		
	public function getTimingsList($organisation_id)
	{
		return $this->timetableMapper->getTimingsList($organisation_id);
	}
		
	public function getTimingDetails($id)
	{
		return $this->timetableMapper->getTimingDetails($id);
	}
		
	public function getTimetableDetails($id)
	{
		return $this->timetableMapper->getTimetableDetails($id);
	}
		
	public function getTimetableTiming($organisation_id)
	{
		return $this->timetableMapper->getTimetableTiming($organisation_id);
	}
	
	public function getMaxProgrammeDuration($organisation_id)
	{
		return $this->timetableMapper->getMaxProgrammeDuration($organisation_id);
	}
	
	public function checkTimetableAttendance($id)
	{
		return $this->timetableMapper->checkTimetableAttendance($id);
	}
	
	public function crosscheckTimetable($timetableModel)
	{
		return $this->timetableMapper->crosscheckTimetable($timetableModel);
	}

	public function crosscheckTiming($timetableModel)
	{
		return $this->timetableMapper->crosscheckTiming($timetableModel);
	}
	
	public function deleteTimetable($id)
	{
		return $this->timetableMapper->deleteTimetable($id);
	}
	
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		return $this->timetableMapper->listSelectData($tableName, $columnName, $organisation_id);
	}
	
}
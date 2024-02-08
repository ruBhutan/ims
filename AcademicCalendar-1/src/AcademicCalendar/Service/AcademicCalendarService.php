<?php

namespace AcademicCalendar\Service;

use AcademicCalendar\Mapper\AcademicCalendarMapperInterface;
use AcademicCalendar\Model\AcademicCalendar;
use AcademicCalendar\Model\AcademicEvent;

class AcademicCalendarService implements AcademicCalendarServiceInterface
{
	/**
	 * @var \Blog\Mapper\AcademicCalendarMapperInterface
	*/
	
	protected $calendarMapper;
	
	public function __construct(AcademicCalendarMapperInterface $calendarMapper) {
		$this->calendarMapper = $calendarMapper;
	}
	
	public function listAll($tableName, $organisation_id)
	{
		return $this->calendarMapper->findAll($tableName, $organisation_id);
	}
	
	public function getUserDetailsId($username)
	{
		return $this->calendarMapper->getUserDetailsId($username);
	}
	
	public function getOrganisationId($username)
	{
		return $this->calendarMapper->getOrganisationId($username);
	}

	public function getUserDetails($username, $tableName)
	{
		return $this->calendarMapper->getUserDetails($username, $tableName);
	}
		
	public function saveAcademicCalendar(AcademicCalendar $calendarObject) 
	{
		return $this->calendarMapper->saveAcademicCalendar($calendarObject);
	}
	
	public function saveAcademicEvent(AcademicEvent $eventObject)
	{
		return $this->calendarMapper->saveAcademicEvent($eventObject);
	}
	 
	public function findCalendarDetail($id)
	{
		return $this->calendarMapper->findCalendarDetail($id);
	}
	
	public function findEventDetail($id)
	{
		return $this->calendarMapper->findEventDetail($id);
	}
	
	public function getMyEvents($employee_id)
	{
		return $this->calendarMapper->getMyEvents($employee_id);
	}
	
	public function listSelectData($tableName, $columnName, $condition)
	{
		return $this->calendarMapper->listSelectData($tableName, $columnName, $condition);
	}

	public function getSemester($organisation_id)
	{
		return $this->calendarMapper->getSemester($organisation_id);
	}
	
}
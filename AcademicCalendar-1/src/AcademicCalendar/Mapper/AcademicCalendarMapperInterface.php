<?php

namespace AcademicCalendar\Mapper;

use AcademicCalendar\Model\AcademicCalendar;
use AcademicCalendar\Model\AcademicEvent;

interface AcademicCalendarMapperInterface
{

	/**
	 * 
	 * @return array/ AcademicCalendar[]
	 */
	 
	public function findAll($tableName, $organisation_id);
	
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
	 * @param type $AcademicCalendarInterface
	 * 
	 * to save academics
	 */
	
	public function saveAcademicCalendar(AcademicCalendar $AcademicCalendarInterface);
	
	/*
	* Save Academic Calendar Event
	*/
	
	public function saveAcademicEvent(AcademicEvent $eventObject);
	 
	 /*
	* Find the Calendar Details
	*/
	
	public function findCalendarDetail($id);
	
	/*
	* Find the Event Details
	*/
	
	public function findEventDetail($id);
	
	/*
	* Get the list of events given employee id
	*/
	
	public function getMyEvents($employee_id);
	
	/**
	 * 
	 * @return array/ AcademicCalendar[]
	 */
	 
	public function listSelectData($tableName, $columnName, $condition);

	public function getSemester($organisation_id);
	
}
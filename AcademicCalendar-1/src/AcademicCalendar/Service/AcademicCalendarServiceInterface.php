<?php

namespace AcademicCalendar\Service;

use AcademicCalendar\Model\AcademicCalendar;
use AcademicCalendar\Model\AcademicEvent;

interface AcademicCalendarServiceInterface
{
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|AcademicCalendarInterface[]
	*/
	
	public function listAll($tableName, $organisation_id);
	
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
	* @param AcademicCalendarInterface $budgetingObject
	*
	* @param AcademicCalendarInterface $budgetingObject
	* @return AcademicCalendarInterface
	* @throws \Exception
	*/
	 
	public function saveAcademicCalendar(AcademicCalendar $calendarObject);
	
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
	 * Should return a set of all objectives that we can iterate over. 
	 * 
	 * The purpose of the function is the objectives for the dropdown select list
	 *
	 * @return array|AcademicCalendarInterface[]
	*/
	
	public function listSelectData($tableName, $columnName, $condition);

	public function getSemester($organisation_id);
		
		
}
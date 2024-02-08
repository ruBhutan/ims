<?php

namespace Responsibilities\Service;

use Responsibilities\Model\Responsibilities;
use Responsibilities\Model\ResponsibilityCategory;
use Responsibilities\Model\StudentResponsibility;

//need to add more models

interface ResponsibilitiesServiceInterface
{
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
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|ResponsibilitiesInterface[]
	*/
	
	public function listAll($tableName, $organisation_id);

	 
	 /**
	 * @param ResponsibilitiesInterface $responsibilityObject
	 *
	 * @param ResponsibilitiesInterface $responsibilityObject
	 * @return ResponsibilitiesInterface
	 * @throws \Exception
	 */
	 
	 public function save(ResponsibilityCategory $responsibilityObject);
	 
	 /**
	 * @param ResponsibilitiesInterface $responsibilityObject
	 *
	 * @param ResponsibilitiesInterface $responsibilityObject
	 * @return ResponsibilitiesInterface
	 * @throws \Exception
	 */
	 
	 public function saveResponsibility(StudentResponsibility $responsibilityObject);


	 public function updateResponsibility(StudentResponsibility $responsibilityObject);
	 

	 /*
	 * List Student to add awards etc
	 */
	
	public function getStudentList($studentName, $studentId, $programme, $organisation_id);
	
	/*
	* Get the list of responsibilities by students after search funcationality
	*/
	
	public function getStudentResponsibilitiesList($studentName, $studentId, $programme, $organisation_id);
	
	/*
	 * List Student with their responsibilities
	 */
	
	 public function listStudentResponsibilities($organisation_id);
	 
	/**
	 * 
	 * @param type $id
	 * 
	 * to find Responsibility Category details to edit/display
	 */
	 
	 public function getResponsibilityCategoryDetails($id) ;
	 
	/**
	 * 
	 * @param type $id
	 * 
	 * to find Student details so that their names are displayed
	 */
	 
	 public function getStudentDetails($id) ;
	 
	 /*
	 * Get the list of responsibilities by a student
	 */
	
	 public function getStudentResponsibilities($student_id);
	
	 
	 /**
	 * Should return a set of all objectives that we can iterate over. 
	 * 
	 * The purpose of the function is the objectives for the dropdown select list
	 *
	 * @return array|ResponsibilitiesInterface[]
	*/
	
	public function listSelectData($tableName, $columnName, $organisation_id);
		
		
}
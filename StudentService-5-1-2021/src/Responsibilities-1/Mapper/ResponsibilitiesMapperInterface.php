<?php

namespace Responsibilities\Mapper;

use Responsibilities\Model\Responsibilities;
use Responsibilities\Model\ResponsibilityCategory;
use Responsibilities\Model\StudentResponsibility;

interface ResponsibilitiesMapperInterface
{

	/*
	 * Get the Organisation Id
	 */
	 
	public function getOrganisationId($username);
	
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username);

	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);
	
	/**
	 * 
	 * @return array/ Responsibilities[]
	 */
	 
	public function findAll($tableName, $organisation_id);
	
	/**
	 * 
	 * @param type $ResponsibilitiesInterface
	 * 
	 * to save budgetings
	 */
	
	public function saveCategory(ResponsibilityCategory $ResponsibilitiesInterface);
	
	/**
	 * 
	 * @param type $ResponsibilitiesInterface
	 * 
	 * to save budgetings
	 */
	
	public function saveResponsibility(StudentResponsibility $ResponsibilitiesInterface);
	
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
	 
	/*
	* Get the list of responsibilities by a student
	*/
	
	public function getStudentResponsibilities($student_id);
	 
	/**
	 * 
	 * @param type $id
	 * 
	 * to find Student details so that their names are displayed
	 */
	 
	public function getStudentDetails($id) ;
		
	/**
	 * 
	 * @return array/ Responsibilities[]
	 */
	 
	public function listSelectData($tableName, $columnName, $organisation_id);
	
}
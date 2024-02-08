<?php

namespace StudentContribution\Mapper;

use StudentContribution\Model\StudentContribution;
use StudentContribution\Model\StudentContributionCategory;

interface StudentContributionMapperInterface
{
	/*
	 * Get the Organisation Id
	 */
	 
	public function getOrganisationId($username, $tableName);
	
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username, $tableName);

	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);
	
	/**
	 * 
	 * @return array/ StudentContribution[]
	 */
	 
	public function findAll($tableName, $organisation_id);
        	
	/**
	 * 
	 * @param type $StudentContributionInterface
	 * 
	 * to save budgetings
	 */
	
	public function saveDetails(StudentContribution $StudentContributionInterface);
	
	/*
	 * TO save the student contribution category
	 */
	 
	public function saveContributionCategory(StudentContributionCategory $contributionObject);

	public function getStudentContributionCategoryDetails($id);
	
	/*
	* List Student to add awards etc
	*/
	
	public function getStudentList($studentName, $studentId, $programme, $organisation_id);
	
	/*
	* Get the list of contributions by students after search funcationality
	*/
	
	public function getStudentContributionList($studentName, $studentId, $programme, $organisation_id);
	
	/**
	 * 
	 * @param type $id
	 * 
	 * to find Student details so that their names are displayed
	 */
	
	public function getStudentDetails($id);
	
	/*
	 * get the list of contribution list of students
	 */
	 
	 public function getContributionList($organisation_id);
	 
	 /*
	* Get the list of contributions made by a student
	*/
	
	public function getStudentContributions($student_id);
	
	/**
	 * 
	 * @return array/ StudentContribution[]
	 */
	 
	public function listSelectData($tableName, $columnName, $organisation_id);
	
}
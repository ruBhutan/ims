<?php

namespace StudentContribution\Service;

use StudentContribution\Model\StudentContribution;
use StudentContribution\Model\StudentContributionCategory;

//need to add more models

interface StudentContributionServiceInterface
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
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|StudentContributionInterface[]
	*/
	
	public function listAll($tableName, $organisation_id);

	 
	 /**
	 * @param StudentContributionInterface $contributionObject
	 *
	 * @param StudentContributionInterface $contributionObject
	 * @return StudentContributionInterface
	 * @throws \Exception
	 */
	 
	 public function save(StudentContribution $contributionObject);
	 
	 /*
	 * TO save the student contribution category
	 */
	 
	 public function saveContributionCategory(StudentContributionCategory $contributionObject);

	 public function getStudentContributionCategoryDetails($id);
	 
	 /**
	 * @param StudentContributionInterface $contributionObject
	 *
	 * @param StudentContributionInterface $contributionObject
	 * @return StudentContributionInterface
	 * @throws \Exception
	 */
	 
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
	 public function getStudentDetails($id) ;
	 
	 /*
	 * get the list of contribution list of students
	 */
	 
	 public function getContributionList($organisation_id);
	 
	 /*
	* Get the list of contributions made by a student
	*/
	
	public function getStudentContributions($student_id);
	 
	 /**
	 * Should return a set of all objectives that we can iterate over. 
	 * 
	 * The purpose of the function is the objectives for the dropdown select list
	 *
	 * @return array|StudentContributionInterface[]
	*/
	
	public function listSelectData($tableName, $columnName, $organisation_id);
		
		
}
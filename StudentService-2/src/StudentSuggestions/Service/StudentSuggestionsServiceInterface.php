<?php

namespace StudentSuggestions\Service;

use StudentSuggestions\Model\StudentSuggestions;
use StudentSuggestions\Model\SuggestionCommittee;
use StudentSuggestions\Model\SuggestionCategory;

//need to add more models

interface StudentSuggestionsServiceInterface
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
	 * @return array|StudentSuggestionsInterface[]
	*/
	
	public function listAll($tableName, $organisation_id);

	/**
	 * Should return a single proposal
	 *
	 * @param int $id Identifier of the proposal that should be returned
	 * @return StudentSuggestionsInterface
	 */

	public function crossCheckSuggestionCategory($suggestionCategory, $organisation_id);

	public function crossCheckSuggestionCategoryDetails($suggestionCategory, $id, $organisation_id);
	 
	public function findStudentSuggestions($id);

	public function getSuggestionCategoryDetails($id);

	public function getAjaxEmployeeDetailsId($tableName, $code);
        
	 /**
	 * @param StudentSuggestionsInterface $studentObject
	 *
	 * @param StudentSuggestionsInterface $studentObject
	 * @return StudentSuggestionsInterface
	 * @throws \Exception
	 */
	 
	 public function saveStudentSuggestions(StudentSuggestions $studentObject);

	 public function listStudentSuggestionList($student_id);

	 public function getSuggestionDetails($id);

	 public function saveSuggestionCommittee(SuggestionCommittee $studentObject, $employeeDetailsId);

	 public function listAllSuggestionCommitteeList($tableName, $organisation_id);

	 public function listStudentSuggestionToCommittee($employee_details_id);

	 public function getPostedCommitteeSuggestionDetails($id);

	 public function crossCheckSuggestionCommitteeMember($suggestionCategory, $employeeDetailsId);

	 public function crossCheckSuggestionCommittee($id, $suggestionCategory, $employeeDetailsId);
	 
	 /**
	 * @param StudentSuggestionsInterface $studentObject
	 *
	 * @param StudentSuggestionsInterface $studentObject
	 * @return StudentSuggestionsInterface
	 * @throws \Exception
	 */
	 
	 public function saveCategory(SuggestionCategory $studentObject);

	 public function updateSuggestionCommitteeStatus($status, $previousStatus, $id);

	 public function getCommitteDetails($id);

	 public function getSuggestionCommitteeDetails($id);
	 
	 
	 /**
	 * Should return a set of all students that we search. 
	 * 
	 * The purpose of the function is get a student and add student
	 *
	 * @return array|StudentSuggestionsInterface[]
	*/
	
	public function getStudentList($studentName, $studentId, $programme);
	
	/**
	 * 
	 * @param type $id
	 * 
	 * to find Student details so that their names are displayed
	 */
	public function getStudentDetails($id);
	
	/*
	* Get list of employee to assign to committee
	*/
	
	public function getEmployeeList($organisation_id);
	
	 /**
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName, $organisation_id);
		
		
}
<?php

namespace StudentSuggestions\Mapper;

use StudentSuggestions\Model\StudentSuggestions;
use StudentSuggestions\Model\SuggestionCommittee;
use StudentSuggestions\Model\SuggestionCategory;

interface StudentSuggestionsMapperInterface
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
	 * @param int/string $id
	 * @return StudentSuggestions
	 * throws \InvalidArugmentException
	 * 
	*/

	public function crossCheckSuggestionCategory($suggestionCategory, $organisation_id);

	public function crossCheckSuggestionCategoryDetails($suggestionCategory, $id, $organisation_id);
	
	public function findStudentSuggestions($id);


	public function getSuggestionCategoryDetails($id);

	public function getAjaxEmployeeDetailsId($tableName, $code);

	/**
	 * 
	 * @return array/ StudentSuggestions[]
	 */
	 
	public function findAll($tableName, $organisation_id);
        
	/**
	 * 
	 * @param type $StudentSuggestionsInterface
	 * 
	 * to save budgetings
	 */
	
	public function savePost(StudentSuggestions $studentsInterface);

	public function listStudentSuggestionList($student_id);

	public function getSuggestionDetails($id);

	public function saveSuggestionCommittee(SuggestionCommittee $studentsInterface, $employeeDetailsId);

	public function listAllSuggestionCommitteeList($tableName, $organisation_id);

	public function listStudentSuggestionToCommittee($employee_details_id);

	public function getPostedCommitteeSuggestionDetails($id);

	public function crossCheckSuggestionCommitteeMember($suggestionCategory, $employeeDetailsId);

	public function crossCheckSuggestionCommittee($id, $suggestionCategory, $employeeDetailsId);
	
	/**
	 * 
	 * @param type $StudentSuggestionsInterface
	 * 
	 * to save budgetings
	 */
	
	public function saveCategory(SuggestionCategory $studentsInterface);

	public function updateSuggestionCommitteeStatus($status, $previousStatus, $id);

	public function getSuggestionCommitteeDetails($id);

	public function getCommitteDetails($id);
	
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
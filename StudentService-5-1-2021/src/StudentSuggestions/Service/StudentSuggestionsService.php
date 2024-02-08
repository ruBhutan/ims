<?php

namespace StudentSuggestions\Service;

use StudentSuggestions\Mapper\StudentSuggestionsMapperInterface;
use StudentSuggestions\Model\StudentSuggestions;
use StudentSuggestions\Model\SuggestionCommittee;
use StudentSuggestions\Model\SuggestionCategory;

class StudentSuggestionsService implements StudentSuggestionsServiceInterface
{
	/**
	 * @var \Blog\Mapper\StudentSuggestionsMapperInterface
	*/
	
	protected $studentMapper;
	
	public function __construct(StudentSuggestionsMapperInterface $studentMapper) {
		$this->studentMapper = $studentMapper;
	}
		 
	public function getOrganisationId($username, $tableName)
	{
		return $this->studentMapper->getOrganisationId($username, $tableName);
	}
	 	
	public function getUserDetailsId($username, $tableName)
	{
		return $this->studentMapper->getUserDetailsId($username, $tableName);
	}

	public function getUserDetails($username, $usertype)
	{
		return $this->studentMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->studentMapper->getUserImage($username, $usertype);
	}

	public function listAll($tableName, $organisation_id)
	{
		return $this->studentMapper->findAll($tableName, $organisation_id);
	}

	public function listSelectedSuggestion($employee_details_id, $tableName, $organisation_id)
	{
		return $this->studentMapper->listSelectedSuggestion($employee_details_id, $tableName, $organisation_id);
	}

	public function crossCheckSuggestionCategory($suggestionCategory, $organisation_id)
	{
		return $this->studentMapper->crossCheckSuggestionCategory($suggestionCategory, $organisation_id);
	}

	public function crossCheckSuggestionCategoryDetails($suggestionCategory, $id, $organisation_id)
	{
		return $this->studentMapper->crossCheckSuggestionCategoryDetails($suggestionCategory, $id, $organisation_id);
	}
	 
	public function findStudentSuggestions($id)
	{
		return $this->studentMapper->findVisionMission($id);
	}

	public function getSuggestionCategoryDetails($id)
	{
		return $this->studentMapper->getSuggestionCategoryDetails($id);
	}

	public function getAjaxEmployeeDetailsId($tableName, $code)
	{
		return $this->studentMapper->getAjaxEmployeeDetailsId($tableName, $code);
	}
	
	public function saveStudentSuggestions(StudentSuggestions $studentObject) 
	{
		return $this->studentMapper->savePost($studentObject);
	}

	public function saveSuggestionCommittee(SuggestionCommittee $studentObject, $employeeDetailsId)
	{
		return $this->studentMapper->saveSuggestionCommittee($studentObject, $employeeDetailsId);
	}

	public function listStudentSuggestionList($student_id)
	{
		return $this->studentMapper->listStudentSuggestionList($student_id);
	}

	public function getSuggestionDetails($id)
	{
		return $this->studentMapper->getSuggestionDetails($id);
	}

	public function listAllSuggestionCommitteeList($tableName, $organisation_id)
	{
		return $this->studentMapper->listAllSuggestionCommitteeList($tableName, $organisation_id);
	}

	public function listStudentSuggestionToCommittee($employee_details_id)
	{
		return $this->studentMapper->listStudentSuggestionToCommittee($employee_details_id);
	}

	public function getPostedCommitteeSuggestionDetails($id)
	{
		return $this->studentMapper->getPostedCommitteeSuggestionDetails($id);
	}

	public function crossCheckSuggestionCommitteeMember($suggestionCategory, $employeeDetailsId)
	{
		return $this->studentMapper->crossCheckSuggestionCommitteeMember($suggestionCategory, $employeeDetailsId);
	}

	public function crossCheckSuggestionCommittee($id, $suggestionCategory, $employeeDetailsId)
	{
		return $this->studentMapper->crossCheckSuggestionCommittee($id, $suggestionCategory, $employeeDetailsId);
	}
	
	public function saveCategory(SuggestionCategory $studentObject) 
	{
		return $this->studentMapper->saveCategory($studentObject);
	}


	public function updateSuggestionCommitteeStatus($status, $previousStatus, $id)
	{
		return $this->studentMapper->updateSuggestionCommitteeStatus($status, $previousStatus, $id);
	}

	public function getCommitteDetails($id)
	{
		return $this->studentMapper->getCommitteDetails($id);
	}

	public function getSuggestionCommitteeDetails($id)
	{
		return $this->studentMapper->getSuggestionCommitteeDetails($id);
	}
	
	public function getStudentList($studentName, $studentId, $programme)
	{
		return $this->studentMapper->getStudentList($studentName, $studentId, $programme);
	}
	
	public function getStudentDetails($id)
	{
		return $this->studentMapper->getStudentDetails($id);
	}
		
	public function getEmployeeList($organisation_id)
	{
		return $this->studentMapper->getEmployeeList($organisation_id);
	}
	
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		return $this->studentMapper->listSelectData($tableName, $columnName, $organisation_id);
	}
	
}
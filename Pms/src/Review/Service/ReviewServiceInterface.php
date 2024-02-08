<?php

namespace Review\Service;

use Review\Model\Review;
use Review\Model\AcademicReview;
use Review\Model\AcademicWeight;
use Review\Model\IwpObjectives;
use Review\Model\NatureActivity;

//need to add more models

interface ReviewServiceInterface
{
	/*
	 * Get the Organisation Id
	*/
	 
	public function getOrganisationId($username);
	
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username);
	
	/*
	* take username and returns Name and any other detail required
	*/
	
	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);
	
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|ReviewInterface[]
	*/
	
	public function listAll($tableName);
	
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|ReviewInterface[]
	*/
	
	public function listEmployeeReview($tableName, $employee_id);
	
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|ReviewInterface[]
	*/
	
	public function listActivityDetail($tableName, $columnName, $activity_id);
	
	/*
	* List the Administrative Appraisal
	*/
	
	public function listAdministrativeAppraisal($tableName, $employee_id, $status, $appraisal_year);
	
	/*
	* List the Academic Appraisal
	*/
	
	public function listEmployeeAppraisal($tableName, $employee_id, $status, $appraisal_year);

	/**
	 * Should return a single proposal
	 *
	 * @param int $id Identifier of the proposal that should be returned
	 * @return ReviewInterface
	 */
	 
	public function findEmployeeId($emp_id);
	
	/**
	 * @param ReviewInterface $reviewObject
	 *
	 * @param ReviewInterface $reviewObject
	 * @return ReviewInterface
	 * @throws \Exception
	 */
	 
	public function saveAcademicReview(AcademicReview $reviewObject);
	
	/*
	* Save Self Evaluation - Gets results in an array
	*/
	
	public function saveSelfEvaluation($data, $review_data, $evaluation_type, $employee_details_id);
	
	/*
	* Save Supervisor Evaluation - Gets results in an array
	*/
	
	public function saveSupervisorEvaluation($rating_data, $evaluation_type, $employee_details_id);
	
	/*
	* Save Feedback evaluations
	*/
	
	public function saveFeedbackEvaluation($feedback_for, $nomination_id, $data, $employee_id, $appraisal_period, $employee_details_id);
        
	/*
	 * Save Student Feedback
	 */
	
	public function saveStudentFeedback($rating_data, $academic_module, $module_tutor, $appraisal_period, $student_id);
	
	/*
	* Get List of Nominations
	*/
	 
	public function getNominationList($tableName, $employee_details_id);
	
	public function getNominatedEmployee($employee_details_id);
	
	/*
	* Get the list of appraisal for a given employee
	*/
	
	public function listAppraisalForEmployee($tableName, $employee_id);
	
	/*
	* Get the employee details
	*/
	
	public function getEmployeeDetails($id);

	public function getIwpDeadline($iwp_type);
	
	/*
	* Get the reviewee details given a nomination $id and nomination for
	*/
	
	public function getRevieweeDetails($id, $tableName);
	
	/*
	 * Get the appraisal list
	 */
        
	public function getAppraisalList($type, $employee_details_id, $role, $organisation_id);
	
	/*
	* Get the Performance Score
	*/
	
	public function getPerformanceScore($evaluation_type, $employee_details_id);
	
	/*
	* Get the Feedback Score
	*/
	
	public function getFeedbackScore($evaluation_type, $employee_details_id);
	
	/*
	* List Employees 
	*/
	
	public function getEmployeeList($empName, $empId, $department, $organisation_id);
        
	 
	 /**
	 * Should return a set of all objectives that we can iterate over. 
	 * 
	 * The purpose of the function is the objectives for the dropdown select list
	 *
	 * @return array|ReviewInterface[]
	*/
	
	public function listSelectData($tableName, $columnName, $empIds);
		
		
}
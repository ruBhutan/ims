<?php

namespace Review\Mapper;

use Review\Model\Review;
use Review\Model\AcademicReview;
use Review\Model\AcademicWeight;
use Review\Model\IwpObjectives;
use Review\Model\NatureActivity;

interface ReviewMapperInterface
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
	 * @param int/string $id
	 * @return Review
	 * throws \InvalidArugmentException
	 * 
	*/
	
	public function findEmployeeId($emp_id);

	/**
	 * 
	 * @return array/ Review[]
	 */
	 
	public function findAll($tableName);
	
	/**
	 * 
	 * @return array/ Review[]
	 */
	 
	public function findEmployeeReview($tableName, $employee_id);
	
	/**
	 * @param ReviewInterface $reviewObject
	 *
	 * @param ReviewInterface $reviewObject
	 * @return ReviewInterface
	 * @throws \Exception
	 */
	 
	 public function saveAcademicReview(AcademicReview $reviewObject);
	
	/**
	 * 
	 * @return array/ Review[]
	 */
	 
	public function findActivityDetail($tableName, $columnName, $activity_id);
	
	/*
	* List the Administrative Appraisal
	*/
	
	public function listAdministrativeAppraisal($tableName, $employee_id, $status, $appraisal_year);
	
	/*
	* List the Academic Appraisal
	*/
	
	public function listEmployeeAppraisal($tableName, $employee_id, $status, $appraisal_year);
	
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
	
	public function saveFeedbackEvaluation($feedback_for,$nomination_id,  $data, $employee_id, $appraisal_period, $employee_details_id);
        
	/*
	 * Save Student Feedback
	 */
	
	public function saveStudentFeedback($rating_data, $academic_module, $module_tutor, $appraisal_period, $student_id);
	
	
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
	 * 
	 * @return array/ Review[]
	 */
	 
	public function listSelectData($tableName, $columnName, $empIds);
	
}
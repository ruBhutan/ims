<?php

namespace EmpPromotion\Mapper;

use EmpPromotion\Model\EmpPromotion;
use EmpPromotion\Model\RejectPromotion;

interface EmpPromotionMapperInterface
{
	/*
	* Getting the id for username
	*/
	
	public function getEmployeeDetailsId($emp_id);
	
	 /*
	 * Get the Organisation Id
	 */
	 
	public function getOrganisationId($username);
	
	/*
	* take username and returns Name and any other detail required
	*/
	
	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);

	/**
	 * 
	 * @return array/ EmpPromotion[]
	 */
	 
	public function findAll($tableName);
        
	/*
	 * Function to list all meritorious promotion details
	 */
	
	public function listMeritoriousPromotion($organisation_id);
        
	/**
	 * 
	 * @param type $id
	 * 
	 * to find the Promotion Details for a given $id
	 */
	 
	public function findPromotionDetails($id) ;
	
	/**
	 * 
	 * @param type $EmpPromotionInterface
	 * 
	 * to save budgetings
	 */
	
	public function save(EmpPromotion $EmpPromotionInterface, $performance_year_data, $performance_rating_data, $performance_category_data, $userrole);

	public function crossCheckAppliedPromotion($promotion_type, $employee_details_id);
	
	/*
	* Save Promotion Approval Details
	*/
	
	public function savePromotionApprovalDetails($data);
	
	/*
	* Save Promotion via Open Competition
	*/
	
	public function saveOpenCompetitionPromotion($data);
	
	/*
	* Reject Promotion
	*/
	
	public function rejectPromotion(RejectPromotion $promotionObject);
	
	/*
	* Get Personal Details
	*/
	
	public function getPersonalDetails($employee_id);
	
	/*
	* Get Education Details of the employee
	*/
	
	public function getEducationDetails($employee_id);
	
	/*
	* Get Employment Details such as Position Title, Position Level etc. of the employee
	*/
	
	public function getEmploymentDetails($employee_id);

	public function getEmployeeLastPromotion($last_promotion, $employee_id);

	public function getPromotionDetails($id);

	public function getEmployeePromotionId($id, $promotion_type);
	
	/*
	* Get Training Details of the employee
	*/
	
	public function getTrainingDetails($employee_id);
	
	/*
	* Get Research Details of the employee
	*/
	
	public function getResearchDetails($employee_id);
	
	/*
	* Get Leave Details of the employee
	*/
	
	public function getStudyLeaveDetails($employee_id);
	
	/*
	* Get EOL Leave Details of the employee
	*/
	
	public function getEolLeaveDetails($employee_id);
	
	/*
	* Get PMS Details of the employee
	*/
	
	public function getPmsDetails($employee_id, $userrole);
	
	/*
	* Get the pay details for the employee
	*/
	
	public function getPayDetails($position_level);
	
	/*
	* Get the details of the position
	*/
	
	public function getPositionDetails($position_title);
	
	/*
	* Get the list of applicants applying for promotion
	*/
	
	public function getPromotionApplicantList($organisation_id, $userrole, $employee_details_id, $departments_id, $status);
	
	/*
	* Get the details of the applicant for promotion
	* Used when viewing the details of the applicant
	* Takes the id of the promotion details
	*/
	
	public function getPromotionApplicantDetail($id);
	
	/*
	* Get the notification details, i.e. submission to and submission to department
	*/
	
	public function getNotificationDetails($organisation_id);
	
	/*
	* List Employees
	*/
	
	public function getEmployeeList($empName, $empId, $department, $organisation_id);
	
	/*
	* For downloading files. Need to get the file location from database
	*/
	
	public function getFileName($promotion_id, $document_type);


	public function getPromotionDetailFileName($emp_promotion_id, $column_name);

	public function getSupervisorEmailId($userrole, $departments_units_id);

	public function getPromotionApplicant($employee_details_id);
	
	/**
	 * 
	 * @return array/ EmpPromotion[]
	 */
	 
	public function listSelectData($tableName, $columnName);
	
}
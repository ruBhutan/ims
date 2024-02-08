<?php

namespace Reassessment\Mapper;

use Reassessment\Model\Reassessment;

interface ReassessmentMapperInterface
{

	/**
	 * 
	 * @return array/ Reassessment[]
	 */
	 
	public function findAll($tableName, $applicant_id);
	
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username);
	
	/*
	* take username and returns employee details id
	*/
	
	public function getStudentDetailsId($username);
	
	/*
	* Get organisation id based on the username
	*/
	
	public function getOrganisationId($username);

	/*
	* Get the User Details
	*/

	public function getUserDetails($username, $usertype);

    public function getUserImage($username, $usertype);
        	
	/*
	* Save Personal Details of Job Applicant
	*/
	
	public function savePersonalDetails(PersonalDetails $reassessmentObject);
	
	/*
	* Get the details of the student
	*/
	
	public function getStudentDetails($student_id, $type);

	public function getReassessmentList($student_id);

	public function crossCheckModuleReassessmentApplication($academic_modules_allocation_id, $student_id);

	public function listReassessmentApplicants($organisation_id);

	public function getReassessmentApplicationDetails($id);
	
	/*
	* Get the list of the academic modules for the current semester
	*/
	
	public function getAcademicModules($student_id);

	public function saveReassessmentApplication(Reassessment $reassessmentObject);

	public function updateReassessmentModuleStatus($data_to_insert, $organisation_id, $employee_details_id);

	public function updateApprovedReassessmentModuleStatus($data_to_insert, $organisation_id, $employee_details_id);
		
	/**
	 * 
	 * @return array/ Reassessment[]
	 */
	 
	public function listSelectData($tableName, $columnName, $condition);
	
}
<?php

namespace Reassessment\Service;

use Reassessment\Model\Reassessment;

//need to add more models

interface ReassessmentServiceInterface
{
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|ReassessmentInterface[]
	*/
	
	public function listAll($tableName, $applicant_id);
	
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
	
	public function savePersonalDetails(PersonalDetails $reassessmentModel);
	
	/*
	* Get the details of the student
	*/
	
	public function getStudentDetails($student_id, $type);

	public function getReassessmentList($student_id);

	public function crossCheckModuleReassessmentApplication($academic_modules_allocation_id, $student_id);

	public function listReassessmentApplicants($organisation_id);

	public function getReassessmentApplicationDetails($id);

	public function getReassessmentAnnouncementPeriod($organisation_id);

	public function getTotalModuleList($student_id);
	
	/*
	* Get the list of the academic modules for the current semester
	*/
	
	public function getAcademicModules($student_id);

	public function saveReassessmentApplication(Reassessment $reassessmentModel);

	public function updateReassessmentModuleStatus($data_to_insert, $organisation_id, $employee_details_id);

	public function updateApprovedReassessmentModuleStatus($data_to_insert, $organisation_id, $employee_details_id);
	
	/**
	* Should return a set of all objectives that we can iterate over. 
	* 
	* The purpose of the function is the objectives for the dropdown select list
	*
	* @return array|ReassessmentInterface[]
	*/
	
	public function listSelectData($tableName, $columnName, $condition);
		
		
}
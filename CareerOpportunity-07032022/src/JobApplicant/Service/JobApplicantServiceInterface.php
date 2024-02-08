<?php

namespace JobApplicant\Service;

use JobApplicant\Model\JobApplicant;
use JobApplicant\Model\JobApplication;
use JobApplicant\Model\JobRegistrant;
use JobApplicant\Model\SelectedApplicant;

//need to add more models

interface JobApplicantServiceInterface
{
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|VacancyInterface[]
	*/
	
	public function listAll($tableName);

	/**
	 * Should return employee details
	 *
	 * @param int $emp_id 
	 * @return EmployeeDetails Array
	 */
	 
	public function findEmpDetails($id);
	
	/*
	 * Get the Organisation Id
	 */
	 
	public function getOrganisationId($username);
	
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username, $tableName);
	
	/*
	* take username and returns Name and any other detail required
	*/
	
	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);

	public function getPersonalDetails($tableName, $job_applicant_id);

	public function getApplicantAddressDetails($job_applicant_id);

	public function getPresentJobDescription($job_applicant_id);

	public function getEmploymentDetails($job_applicant_id);

	public function getEducationDetails($job_applicant_id);
	
	public function getApplicantMarksDetail($job_applicant_id);

	public function getLanguageDetails($job_applicant_id);

	public function getTrainingDetails($job_applicant_id);

	public function getResearchDetails($job_applicant_id);

	public function getApplicantCommunityServices($job_applicant_id);
	
	public function getApplicantAwardDetail($job_applicant_id);
	
	public function getApplicantMembershipDetail($job_applicant_id);
	 
	 /*
	 * Save Job Application
	 */
	 
	 public function saveJobApplication(JobApplication $jobObject);
	 
        
	/*
	* To check whether the user has applied for the job or not
	*/
	
	public function getJobApplication($employee_details_id, $job_applicant_id, $id);

	public function getVacancyDetail($id);

	public function getApplicantReferenceDetails($job_applicant_id);

	public function getApplicantEducationLevel($job_applicant_id);

	public function getJobApplicationList($tableName, $job_applicant_id);
                 
	 /**
	 * Should return a set of all objectives that we can iterate over. 
	 * 
	 * The purpose of the function is the objectives for the dropdown select list
	 *
	 * @return array|VacancyInterface[]
	*/
	
	public function listSelectData($tableName, $columnName);

	public function saveJobRegistrantDetails(JobRegistrant $jobregistrantObject, $registrantList);	
		
}
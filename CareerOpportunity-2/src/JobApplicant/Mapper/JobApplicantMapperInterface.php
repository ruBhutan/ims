<?php

namespace JobApplicant\Mapper;

use JobApplicant\Model\JobApplicant;
use JobApplicant\Model\JobRegistrant;
use JobApplicant\Model\SelectedApplicant;
use JobApplicant\Model\JobApplication;

interface JobApplicantMapperInterface
{
	/**
	 * 
	 * @return array/ Vacancy[]
	 */
	 
	public function findAll($tableName);
        
	/**
	 * 
	 * @param type $id
	 * 
	 * to find details related to Employee Details
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
	 * 
	 * @return array/ Vacancy[]
	 */
	 
	public function listSelectData($tableName, $columnName);

	public function saveJobRegistrantDetails(JobRegistrant $jobregistrantObject, $registrantList);
	
}
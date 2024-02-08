<?php

namespace Vacancy\Mapper;

use Vacancy\Model\Vacancy;
use Vacancy\Model\SelectedApplicant;
use Vacancy\Model\JobApplication;
use Vacancy\Model\JobApplicantMarks;

interface VacancyMapperInterface
{
	/**
	 * 
	 * @return array/ Vacancy[]
	 */
	 
	public function findAll($tableName, $type, $organisation_id);
        
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
	
	/**
	 * @param int/string $id
	 * @return Vacancy
	 * throws \InvalidArugmentException
	 * 
	*/
	
	public function findVacancy($id);
	
	/**
	 * 
	 * @param type $id
	 * 
	 * to find details related to the Obejctives
	 */
	
	public function findModule($id);
	
	/**
	 * 
	 * @param type $VacancyInterface
	 * 
	 * to save budgetings
	 */
	
	public function saveVacancy(Vacancy $VacancyInterface);
	
	/**
	 * 
	 * @param type $VacancyInterface
	 * 
	 * to save budgetings
	 */
	
	public function saveAdhocVacancy(Vacancy $VacancyInterface);

	public function closeAdhocVacancy($id);
	
	/*
	 * Save Job Application
	 */
	 
	 public function saveJobApplication(JobApplication $jobObject);
	
	/*
	 * Get the details of the HRD Proposal for announcing vacancy
	*/
	 
	public function getProposalDetail($id);
	
	/*
	 * Get the details of the Job Vacancy
	 */
	 
	public function getVacancyDetail($id);
	
	public function getAppliedVacancyDetail($table_name, $id);
	
	/*
	* Get the details of the job applicant
	* Used when viewing the details of the applicant
	* Takes the id of the job application 
	*/
	
	public function getJobApplicantDetail($id);
	
        /*
	* Get the details of the selected applicant
	* Used when viewing the details of the applicant
	* Takes the id of the recruited applicant 
	*/
	
	public function getSelectedApplicantDetail($id);
        
        /*
         * Get the Recruitment details
         */
        
	public function getRecruitmentDetails($id);

	public function getApplicantEducationLevel($employee_details_id);

	public function getApplicantAddressDetails($employee_details_id, $type);
        
	/*
	* To check whether the user has applied for the job or not
	*/
	
	public function getJobApplication($employee_details_id, $job_applicant_id, $id);
	
	/*
	* Get Personal Details of the job applicant 
	*/
	 
	public function getPersonalDetails($tableName, $applicant_id);
	 
	/*
	* Get the education details of the job applicant
	*/
	 
	public function getEducationDetails($tableName, $applicant_id, $id);
	
	public function getApplicantMarksDetail($table_name, $job_applicant_id,$id);
	 
	/*
	* get employment details of the job applicant
	*/
	 
	public function getEmploymentDetails($tableName, $applicant_id, $id);
	 
	/*
	* get training details of the job applicant
	*/
	 
	public function getTrainingDetails($tableName, $applicant_id);
	 
	/*
	* get research details of the job applicant
	*/
	 
	public function getResearchDetails($tableName, $applicant_id);
	
	public function getApplicantCommunityServices($job_applicant_id);
	
	public function getApplicantAwardDetail($job_applicant_id);
	
	public function getApplicantMembershipDetail($job_applicant_id);

	public function getApplicantReferenceDetails($table_name, $job_applicant_id, $id);

	public function getPresentJobDescription($table_name, $job_applicant_id);

	public function getLanguageDetails($employee_details_id, $type);

	public function getApplicantPromotionDetails($table_name, $job_applicant_id);
	
	public function getApplicantDocuments($job_applicant_id);
	
	public function getApplicantDocumentList($table_name, $job_applicant_id, $type);
	
	public function getFileName($file_id, $column_name);
	
	/*
	* List all Proposals
	*/
	public function listAllProposals($organisation_id);
	
	/*
	 * Get list of Job Applicants
	 */
	 
	public function listJobApplicants($type,$status, $organisation_id);


	public function listJobApplicantsLatestEducation($type);
	
	/*
	 * Update Job Application i.e. Selected, Shortlisted or Rejected
	 */
	 
	public function updateJobApplication($id, $status);
	
	/*
	 * Update Job Applicant Details i.e. once selected update details into the employee details table
	 */
	 
	public function updateJobApplicantDetails($table_name, $job_applicant_id, $data, SelectedApplicant $jobObject);
        
        /*
          * Get the list of Job Applicants selected by Colleges for OVC to update
          */
         
         public function listRecruitedCandidates();
         
         /*
	 * Update Job Applicant Details i.e. once selected update details into the employee details table by OVC
	 */
	 
	 public function updateSelectedCandidateDetails($table_name, $job_applicant_id, SelectedApplicant $jobObject, $data);

	 public function saveJobApplicantMarks(JobApplicantMarks $jobObject);

	 public function listAnnouncedVacancy($organisation_id);

	 public function listAllAppliedApplicant($type, $organisation_id);

	 public function listAppliedApplicants($type, $position_title, $organisation_id);
	 
	 public function listAllApplicantDegreeMarks($type);

	 public function getApplicantDetail($applicant_id, $category);

	 public function getJobApplicantMarks($applicant_id, $category);

	 public function listApplicantStudyLevel($tableName, $job_applicant_id);
	 
	/**
	 * 
	 * @return array/ Vacancy[]
	 */
	 
	public function listSelectData($tableName, $columnName, $organisation_id);
	
}
<?php

namespace JobPortal\Service;

use JobPortal\Model\Awards;
use JobPortal\Model\PersonalDetails;
use JobPortal\Model\CommunityService;
use JobPortal\Model\Documents;
use JobPortal\Model\EducationDetails;
use JobPortal\Model\EmploymentDetails;
use JobPortal\Model\JobPortal;
use JobPortal\Model\LanguageSkills;
use JobPortal\Model\MembershipDetails;
use JobPortal\Model\PublicationDetails;
use JobPortal\Model\References;
use JobPortal\Model\TrainingDetails;
use JobPortal\Model\ApplicantMarks;

//need to add more models

interface JobPortalServiceInterface
{

	public function getUserDetailsId($username, $usertype);

	public function getUserImage($username, $usertype);
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|JobPortalInterface[]
	*/
	
	public function listAll($tableName, $applicant_id);

	public function listApplicantStudyLevel($tableName, $job_applicant_id);

	public function getApplicantAddressDetails($job_applicant_details_id);

	public function getRegistrantOtherDetails($tableName, $id);
	
	/*
	* Get organisation id based on the username
	*/
	
	public function getOrganisationId($username);
        
	/*
	* Save Personal Details of Job Applicant
	*/
	
	public function savePersonalDetails(PersonalDetails $jobModel, $country, $dzongkhag, $gewog, $village);
	
	/*
	* Save Education Details of Job Applicant
	*/
	
	public function saveEducationDetails(EducationDetails $jobModel);

	public function updateEducationDetails(EducationDetails $jobObject);

	public function deleteEducationDetails($id);
	
	/*
	* Save Training Details of Job Applicant
	*/
	
	public function saveTrainingDetails(TrainingDetails $jobModel);

	public function updateTrainingDetails(TrainingDetails $jobObject);
	
	/*
	* Save employment history of Job Applicant
	*/
	
	public function saveEmploymentRecord(EmploymentDetails $jobModel);

	public function updateEmploymentRecord(EmploymentDetails $jobObject);
	
	/*
	* Save membership such as board membership etc. of Job Applicant
	*/
	
	public function saveMembership(MembershipDetails $jobModel);

	public function updateMembership(MembershipDetails $jobObject);
	
	/*
	* Save Community Service of the Job Applicant
	*/
	
	public function saveCommunityService(CommunityService $jobModel);

	public function updateCommunityService(CommunityService $jobObject);
	
	/*
	* Save Language skills of the Job Applicant
	*/
	
	public function saveLanguageSkills(LanguageSkills $jobModel);
	
	/*
	* Save Publications
	*/
	
	public function savePublications(PublicationDetails $jobModel);
	
	/*
	* Save Awards
	*/
	
	public function saveAwards(Awards $jobModel);

	public function updateAwards(Awards $jobObject);
	
	/*
	* Save References
	*/
	
	public function saveReferences(References $jobModel);

	public function saveJobApplicantMarks(ApplicantMarks $jobObject);
	
	/*
	* Save Documents
	*/
	
	public function saveDocuments(Documents $jobModel);

	public function getFileName($file_id, $column_name, $type);
	 
	/**
	* Should return a set of all objectives that we can iterate over. 
	* 
	* The purpose of the function is the objectives for the dropdown select list
	*
	* @return array|JobPortalInterface[]
	*/
	
	public function listSelectData($tableName, $columnName, $condition);
		
		
}
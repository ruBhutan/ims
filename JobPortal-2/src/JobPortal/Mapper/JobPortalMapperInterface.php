<?php

namespace JobPortal\Mapper;

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

interface JobPortalMapperInterface
{

	public function getUserDetailsId($username, $usertype);

	public function getUserImage($username, $usertype);

	/**
	 * 
	 * @return array/ JobPortal[]
	 */
	 
	public function findAll($tableName, $applicant_id);

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
	
	public function savePersonalDetails(PersonalDetails $jobObject, $country, $dzongkhag, $gewog, $village);
	
	/*
	* Save Education Details of Job Applicant
	*/
	
	public function saveEducationDetails(EducationDetails $jobObject);

	public function updateEducationDetails(EducationDetails $jobObject);

	public function deleteEducationDetails($id);
	
	/*
	* Save Training Details of Job Applicant
	*/
	
	public function saveTrainingDetails(TrainingDetails $jobObject);

	public function updateTrainingDetails(TrainingDetails $jobObject);
	
	/*
	* Save employment history of Job Applicant
	*/
	
	public function saveEmploymentRecord(EmploymentDetails $jobObject);

	public function updateEmploymentRecord(EmploymentDetails $jobObject);
	
	/*
	* Save membership such as board membership etc. of Job Applicant
	*/
	
	public function saveMembership(MembershipDetails $jobObject);

	public function updateMembership(MembershipDetails $jobObject);
	
	/*
	* Save Community Service of the Job Applicant
	*/
	
	public function saveCommunityService(CommunityService $jobObject);

	public function updateCommunityService(CommunityService $jobObject);
	
	/*
	* Save Language skills of the Job Applicant
	*/
	
	public function saveLanguageSkills(LanguageSkills $jobObject);
	
	/*
	* Save Publications
	*/
	
	public function savePublications(PublicationDetails $jobObject);
	
	/*
	* Save Awards
	*/
	
	public function saveAwards(Awards $jobObject);

	public function updateAwards(Awards $jobObject);
	
	/*
	* Save References
	*/
	
	public function saveReferences(References $jobObject);

	public function saveJobApplicantMarks(ApplicantMarks $jobObject);
	
	/*
	* Save Documents
	*/
	
	public function saveDocuments(Documents $jobObject);

	public function getFileName($file_id, $column_name, $type);
		
	/**
	 * 
	 * @return array/ JobPortal[]
	 */
	 
	public function listSelectData($tableName, $columnName, $condition);
	
}
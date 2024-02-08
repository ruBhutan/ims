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

interface JobPortalMapperInterface
{

	/**
	 * 
	 * @return array/ JobPortal[]
	 */
	 
	public function findAll($tableName);
	
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username);
	
	/*
	* Get organisation id based on the username
	*/
	
	public function getOrganisationId($username);
        	
	/*
	* Save Personal Details of Job Applicant
	*/
	
	public function savePersonalDetails(PersonalDetails $jobObject);
	
	/*
	* Save Education Details of Job Applicant
	*/
	
	public function saveEducationDetails(EducationDetails $jobObject);
	
	/*
	* Save Training Details of Job Applicant
	*/
	
	public function saveTrainingDetails(TrainingDetails $jobObject);
	
	/*
	* Save employment history of Job Applicant
	*/
	
	public function saveEmploymentRecord(EmploymentDetails $jobObject);
	
	/*
	* Save membership such as board membership etc. of Job Applicant
	*/
	
	public function saveMembership(MembershipDetails $jobObject);
	
	/*
	* Save Community Service of the Job Applicant
	*/
	
	public function saveCommunityService(CommunityService $jobObject);
	
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
	
	/*
	* Save References
	*/
	
	public function saveReferences(References $jobObject);
	
	/*
	* Save Documents
	*/
	
	public function saveDocuments(Documents $jobObject);
		
	/**
	 * 
	 * @return array/ JobPortal[]
	 */
	 
	public function listSelectData($tableName, $columnName, $condition);
	
}
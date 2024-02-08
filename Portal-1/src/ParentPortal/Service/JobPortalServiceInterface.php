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

//need to add more models

interface JobPortalServiceInterface
{
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|JobPortalInterface[]
	*/
	
	public function listAll($tableName);
	
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
	
	public function savePersonalDetails(PersonalDetails $jobModel);
	
	/*
	* Save Education Details of Job Applicant
	*/
	
	public function saveEducationDetails(EducationDetails $jobModel);
	
	/*
	* Save Training Details of Job Applicant
	*/
	
	public function saveTrainingDetails(TrainingDetails $jobModel);
	
	/*
	* Save employment history of Job Applicant
	*/
	
	public function saveEmploymentRecord(EmploymentDetails $jobModel);
	
	/*
	* Save membership such as board membership etc. of Job Applicant
	*/
	
	public function saveMembership(MembershipDetails $jobModel);
	
	/*
	* Save Community Service of the Job Applicant
	*/
	
	public function saveCommunityService(CommunityService $jobModel);
	
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
	
	/*
	* Save References
	*/
	
	public function saveReferences(References $jobModel);
	
	/*
	* Save Documents
	*/
	
	public function saveDocuments(Documents $jobModel);
	 
	/**
	* Should return a set of all objectives that we can iterate over. 
	* 
	* The purpose of the function is the objectives for the dropdown select list
	*
	* @return array|JobPortalInterface[]
	*/
	
	public function listSelectData($tableName, $columnName, $condition);
		
		
}
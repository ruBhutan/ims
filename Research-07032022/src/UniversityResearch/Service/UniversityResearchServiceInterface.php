<?php

namespace UniversityResearch\Service;

use UniversityResearch\Model\AurgTitle;
use UniversityResearch\Model\AurgProjectDescription;
use UniversityResearch\Model\AurgActionPlan;
use UniversityResearch\Model\ResearchGrantAnnouncement;
use UniversityResearch\Model\ResearchRecommendation;
use UniversityResearch\Model\UpdateAurgGrant;

interface UniversityResearchServiceInterface
{
	/**
	 * Should return a set of all UniversityResearch that we can iterate over. 
	 *
	 * @return array|UniversityResearchInterface[]
	*/
	
	public function listAllResearches();

	/**
	 * Should return a single UniversityResearch
	 *
	 * @param int $id Identifier of the UniversityResearch that should be returned
	 * @return UniversityResearchInterface
	 */
	 
	public function findResearch($id);
	
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username, $tableName);	
	
	/*
	* take username and returns Name and any other detail required
	*/
	
	public function getUserDetails($username, $usertype);

	public function getUserImage($username, $usertype);
	/*
	* Get organisation id based on the username
	*/
	
	public function getOrganisationId($username, $usertype);
	/*
	* Get employee details
	*/
	
	public function getEmployeeDetails($id);
        
        
	/**
	 * Should return a single UniversityResearch
	 *
	 * @param int $id Identifier of the UniversityResearch that should be returned
	 * @return UniversityResearchInterface
	 */
        
     public function findResearchDetails($id, $tableName);
	 
	 /*
	 * To get the employee details for CARG research
	 * common display of all grants
	 */
	
	 public function findCargResearchDetails($id, $type);
	 
	 /**
	 * @param UniversityResearchInterface $universityResearchObject
	 *
	 * @param UniversityResearchInterface $UniversityResearchObject
	 * @return UniversityResearchInterface
	 * @throws \Exception
	 */
	 
	 public function saveAurgTitle(AurgTitle $aurgTitleObject);
	 
	  /**
	 * @param UniversityResearchInterface $universityResearchObject
	 *
	 * @param UniversityResearchInterface $UniversityResearchObject
	 * @return UniversityResearchInterface
	 * @throws \Exception
	 */
	 
	 public function saveAurgProjectDescription(AurgProjectDescription $aurgProjectObject);
	 
	  /**
	 * @param UniversityResearchInterface $universityResearchObject
	 *
	 * @param UniversityResearchInterface $UniversityResearchObject
	 * @return UniversityResearchInterface
	 * @throws \Exception
	 */
	 
	 public function saveAurgActionPlan(AurgActionPlan $aurgPlanObject);

	 public function updateAurgStatus(UpdateAurgGrant $aurgPlanObject);
	 
	 /*
	 * Save Research Recommendation by DRER or DRIL
	 */
	 
	 public function saveResearchRecommendation(ResearchRecommendation $researchObject, $approving_authority);
	 
	 
	 /*
	 * To get the employee details 
	 */
	
	 public function getResearcherDetails($id, $type);

	 public function getResearchGrantDetail($type, $research_grant_type);
	 
	 /*
	* Get All the research types
	*/
	
	public function getAllResearchTypes($organisation_id);

	public function getFileName($application_id, $column_name, $research_type);
	 
	 /*
	 * TO save Research Grant Announcement
	 */
	 
	 public function saveResearchGrantAnnouncement(ResearchGrantAnnouncement $announcementObject);
	 
	 /*
	 * Save the Recommendation from the Reviewers
	 */
	 
	 public function saveRecommendation(ResearchRecommendation $recommendationObject);
	 
	 /*
	 * Get Research Grant Announcement
	 */
	 
	 public function getResearchGrantAnnouncement($id, $organisation_id);
	 
	 /*
	 * Get Previus Research
	 */
	 
	 public function getPreviousResearch($id);
	 
	 /*
	 * Get the list of AURG grant for updating
	 */
	 
	 public function getAurgList($researcher_name, $research_title, $grant_type, $status);
	 
	 /*
	 * Get Research list - for both AURG and CARG
	 */
	 
	 public function getResearchList($employee_id);
	 
	 /*
	* Get Research Grant List
	*/
	
	public function getResearchGrantList();


	public function deleteResearchGrantApplication($id, $type);
	 
	 
	 /*
	 * Get a list of the data for drop down
	 */
	
	public function listSelectData($tableName, $columnName);
}
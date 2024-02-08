<?php

namespace CollegeResearch\Service;

use CollegeResearch\Model\CollegeResearch;
use CollegeResearch\Model\CargGrant;
use CollegeResearch\Model\CargResearch;
use CollegeResearch\Model\CargActionPlan;
use CollegeResearch\Model\CargAction;
use CollegeResearch\Model\ResearchRecommendation;
use CollegeResearch\Model\UpdateCargGrant;

interface CollegeResearchServiceInterface
{
	/**
	 * Should return a set of all CollegeResearch that we can iterate over. 
	 *
	 * @return array|EmployeeDetailInterface[]
	*/
	
	public function listAllResearches();

	/**
	 * Should return a single CollegeResearch
	 *
	 * @param int $id Identifier of the CollegeResearch that should be returned
	 * @return CollegeResearchInterface
	 */
	 
	public function findResearch($id);
        
        
	 
	 /**
	 * @param CollegeResearchInterface $collegeResearchObject
	 *
	 * @param CollegeResearchInterface $CollegeResearchObject
	 * @return CollegeResearchInterface
	 * @throws \Exception
	 */
	 
	 public function save(CollegeResearch $collegeResearchObject);
	 
	 /**
	 * @param CollegeResearchInterface $collegeResearchObject
	 *
	 * @param CollegeResearchInterface $CollegeResearchObject
	 * @return CollegeResearchInterface
	 * @throws \Exception
	 */
	 
	 public function saveCargGrant(CargGrant $cargGrantObject);
	 
	 /**
	 * @param CollegeResearchInterface $collegeResearchObject
	 *
	 * @param CollegeResearchInterface $CollegeResearchObject
	 * @return CollegeResearchInterface
	 * @throws \Exception
	 */
	 
	 public function saveCargProject(CargResearch $cargProjectObject);
	 
	 /**
	 * @param CollegeResearchInterface $collegeResearchObject
	 *
	 * @param CollegeResearchInterface $CollegeResearchObject
	 * @return CollegeResearchInterface
	 * @throws \Exception
	 */
	 
	 public function saveCargActionPlan(CargAction $cargActionPlanObject);

	 public function updateCargGrant(UpdateCargGrant $cargActionPlanObject);
	 
	 /*
	 * Save the Recommendation from the Reviewers
	 */
	 
	public function saveRecommendation(ResearchRecommendation $recommendationObject);

	public function saveResearchApproval(ResearchRecommendation $collegeResearchObject);
	 
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
	
	/*
	* Get Research Grant List
	*/
	
	public function getResearchGrantList();
	
	/*
	* Get Research Grant Announcement
	*/
	 
	public function getResearchGrantAnnouncement($id);
	
	/*
	* Get the list of CARG - from the search form
	*/
	
	public function getCargList($researcher_name, $research_title, $grant_type, $status, $organisation_id);
	
	/*
	 * Get the list of AURG grant for updating
	 */
	 
	public function getAurgList($researcher_name, $research_title, $grant_type, $status, $organisation_id);
	
	/**
	 * 
	 * @param type $id
	 * 
	 * to find the UniversityResearch Proposal for a given $id
	 */
	public function findResearchDetails($id, $tableName);
	
	/*
	 * Get the location of the file name 
	 */
	 
	public function getFileName($training_id, $column_name, $research_type);
		
		
}
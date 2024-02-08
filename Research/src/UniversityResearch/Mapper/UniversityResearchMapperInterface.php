<?php

namespace UniversityResearch\Mapper;

use UniversityResearch\Model\AurgTitle;
use UniversityResearch\Model\AurgProjectDescription;
use UniversityResearch\Model\AurgActionPlan;
use UniversityResearch\Model\ResearchGrantAnnouncement;
use UniversityResearch\Model\ResearchRecommendation;
use UniversityResearch\Model\UpdateAurgGrant;

interface UniversityResearchMapperInterface
{
	/**
	 * @param int/string $id
	 * @return EmpWorkForceProposal
	 * throws \InvalidArugmentException
	 * 
	*/
	
	public function find($id);

	/**
	 * 
	 * @return array/ EmpWorkForceProposal[]
	 */
	 
	public function findAll();
	
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
        
	/**
	 * 
	 * @param type $id
	 * 
	 * to find details related to the work force proposal
	 */
	
	public function findResearchDetails($id, $tableName);
	
	/*
	 * To get the employee details for CARG research
	 * common display of all grants
	 */
	
	public function findCargResearchDetails($id, $type);
	
	/**
	 * 
	 * @param type $EmpWorkForceProposalInterface
	 * 
	 * to save work force proposals
	 */
	
	public function saveAurgTitle(AurgTitle $aurgTitle);
	
	/**
	 * 
	 * @param type $EmpWorkForceProposalInterface
	 * 
	 * to save work force proposals
	 */
	
	public function saveAurgProjectDescription(AurgProjectDescription $aurgProject);
	
	/**
	 * 
	 * @param type $EmpWorkForceProposalInterface
	 * 
	 * to save work force proposals
	 */
	
	public function saveAurgActionPlan(AurgActionPlan $aurgPlan);

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
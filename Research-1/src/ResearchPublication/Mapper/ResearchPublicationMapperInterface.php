<?php

namespace ResearchPublication\Mapper;

use ResearchPublication\Model\ResearchPublication;
use ResearchPublication\Model\PublicationType;
use ResearchPublication\Model\ResearchAnnouncement;
use ResearchPublication\Model\ResearchRecommendation;
use ResearchPublication\Model\ResearchType;
use ResearchPublication\Model\SeminarAnnouncement;


interface ResearchPublicationMapperInterface
{
	/**
	 * 
	 * @return array/ ResearchPublication[]
	 */
	 
	public function findAll($tableName, $organisation_id);
	
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
	 * to find details related to Employee Details
	 */
	
	public function findEmpDetails($id);
	
	
	/**
	 * 
	 * @param type $id
	 * 
	 * to find details related to the Publication Type
	 */
	
	public function findPublicationType($id);

	public function getResearchPublicationDetail($type, $research_publication_type);
	
	/**
	 * 
	 * @param type $id
	 * 
	 * to find details related to the Publication
	 */
	
	public function findPublication($id);

	public function getResearchPublicationList($employee_details_id);

	public function getSemiarAnnouncementList($organisation_id);
	
	/**
	 * 
	 * @param type $ResearchPublicationInterface
	 * 
	 * to save budgetings
	 */
	
	public function saveDetails(ResearchPublication $ResearchPublicationInterface);


	public function updateResearchPublication(ResearchRecommendation $publicationObject);
	
	/**
	 * 
	 * @param type $ResearchPublicationInterface
	 * 
	 * to save budgetings
	 */
	
	public function saveResearchAnnouncement(ResearchAnnouncement $ResearchPublicationInterface);

	public function saveSeminarAnnouncement(SeminarAnnouncement $ResearchPublicationInterface);
	
	/**
	 * 
	 * @param type $ResearchPublicationInterface
	 * 
	 * to save budgetings
	 */
	
	public function savePublicationType(PublicationType $ResearchPublicationInterface);

	public function getSeminarAnnouncementDetails($id);
	
	/*
	 * Save the Recommendation from the Reviewers
	*/
	 
	public function saveRecommendation(ResearchRecommendation $recommendationObject);
	
	/*
	* Save the Research Type for each organisation
	*/
	
	public function saveResearchType(ResearchType $researchObject);
	
	/*
	* Get All the research types
	*/
	
	public function getAllResearchTypes($organisation_id);
	
	/*
	 * Get the list of publications based on type of publication, i.e. College or University
	 */
	 
	public function getPublicationList($type);
	
	/*
	* Get the file name given the $id so that user can download
	*/
	 
	public function getFileName($id);
	
	/*
	* Generic function to get the details given an id and table name
	*/
	
	public function getDetails($id, $table_name);

	public function getResearchPublicationAnnouncement($id, $organisation_id);
		
	/**
	 * 
	 * @return array/ ResearchPublication[]
	 */
	 
	public function listSelectData($tableName, $columnName, $date, $organisation_id);
	
}
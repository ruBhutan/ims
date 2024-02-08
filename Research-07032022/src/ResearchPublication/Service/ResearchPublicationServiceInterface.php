<?php

namespace ResearchPublication\Service;

use ResearchPublication\Model\ResearchPublication;
use ResearchPublication\Model\PublicationType;
use ResearchPublication\Model\ResearchAnnouncement;
use ResearchPublication\Model\ResearchRecommendation;
use ResearchPublication\Model\ResearchType;
use ResearchPublication\Model\SeminarAnnouncement;

//need to add more models

interface ResearchPublicationServiceInterface
{
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|ResearchPublicationInterface[]
	*/
	
	public function listAll($tableName, $organisation_id);
	
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
	 * Should return employee details
	 *
	 * @param int $emp_id 
	 * @return EmployeeDetails Array
	 */
	 
	public function findEmpDetails($id);
	
	/**
	 * Should return a single proposal
	 *
	 * @param int $id Identifier of the proposal that should be returned
	 * @return ResearchPublicationInterface
	 */
	 
	public function findPublicationType($id);

	public function getResearchPublicationDetail($type, $research_publication_type);
	
	/**
	 * Should return a single proposal
	 *
	 * @param int $id Identifier of the proposal that should be returned
	 * @return ResearchPublicationInterface
	 */
	 
	public function findPublication($id);

	public function getResearchPublicationList($employee_details_id);

	public function getSeminarAnnouncementDetails($id);

	public function getSemiarAnnouncementList($organisation_id);
        
        	 
	 /**
	 * @param ResearchPublicationInterface $publicationObject
	 *
	 * @param ResearchPublicationInterface $publicationObject
	 * @return ResearchPublicationInterface
	 * @throws \Exception
	 */
	 
	public function save(ResearchPublication $publicationObject);

	public function updateResearchPublication(ResearchRecommendation $publicationObject);
	 
	 /**
	 * @param ResearchPublicationInterface $publicationObject
	 *
	 * @param ResearchPublicationInterface $publicationObject
	 * @return ResearchPublicationInterface
	 * @throws \Exception
	 */
	 
	public function saveResearchAnnouncement(ResearchAnnouncement $publicationObject);
	 
	 
	public function saveSeminarAnnouncement(SeminarAnnouncement $publicationObject);
	 /**
	 * @param ResearchPublicationInterface $publicationObject
	 *
	 * @param ResearchPublicationInterface $publicationObject
	 * @return ResearchPublicationInterface
	 * @throws \Exception
	 */
	 
	public function savePublicationType(PublicationType $publicationObject);
	 
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
	 * Should return a set of all objectives that we can iterate over. 
	 * 
	 * The purpose of the function is the objectives for the dropdown select list
	 *
	 * @return array|ResearchPublicationInterface[]
	*/
	
	public function listSelectData($tableName, $columnName, $date, $organisation_id);
		
		
}
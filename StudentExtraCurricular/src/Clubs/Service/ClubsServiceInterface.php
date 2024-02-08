<?php

namespace Clubs\Service;

use Clubs\Model\Clubs;
use Clubs\Model\ClubsApplication;

//need to add more models

interface ClubsServiceInterface
{
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|ClubsInterface[]
	*/
	
	public function listAll($tableName, $organisation_id);

	/**
	 * Should return a single proposal
	 *
	 * @param int $id Identifier of the proposal that should be returned
	 * @return ClubsInterface
	 */
	 
	public function findClubs($id);


	public function crossCheckClubApplication($student_id, $id);
        
        
	/**
	 * Should return a single proposal
	 *
	 * @param int $id Identifier of the Proposal that should be returned
	 * @return ClubsInterface
	 */
        
     public function findStudentClubs($id);
	 
	 /**
	 * @param ClubsInterface $clubsObject
	 *
	 * @param ClubsInterface $clubsObject
	 * @return ClubsInterface
	 * @throws \Exception
	 */
	 
	 public function save(Clubs $clubsObject);
	 
	 /**
	 * @param ClubsInterface $clubsObject
	 *
	 * @param ClubsInterface $clubsObject
	 * @return ClubsInterface
	 * @throws \Exception
	 */
	 
	 public function saveClubApplications(ClubsApplication $clubsObject);
	 
	 /*
	* take username and returns employee details id/student id
	*/
	
	public function getUserDetailsId($username, $tableName);
	
	/*
	* Get the Organisation Id
	*/
	 
	public function getOrganisationId($username, $usertype);

	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);
	
	/**
	 * 
	 * @param type $id
	 * 
	 * to find Student details so that their names are displayed
	 */
	public function getStudentDetails($id);
	
	/*
	* List Club Applications and their status
	*/
	 
	public function listClubApplications($organisation_id);
	
	/*
	* List Club Members
	*/
	 
	public function listClubMembers($organisation_id);

	public function getStudentClubMembership($clubs_id, $tableName);
	
	/*
	* Approve/Reject Club Application
	*/
	
	public function submitClubApplication($application_id, $status);
	
	 /**
	 * Should return a set of all objectives that we can iterate over. 
	 * 
	 * The purpose of the function is the objectives for the dropdown select list
	 *
	 * @return array|ClubsInterface[]
	*/
	
	public function listSelectData($tableName, $columnName, $organisation_id);
		
		
}
<?php

namespace Clubs\Mapper;

use Clubs\Model\Clubs;
use Clubs\Model\ClubsApplication;

interface ClubsMapperInterface
{
	/**
	 * @param int/string $id
	 * @return Clubs
	 * throws \InvalidArugmentException
	 * 
	*/
	
	public function findClubs($id);


	public function crossCheckClubApplication($student_id, $id);

	/**
	 * 
	 * @return array/ Clubs[]
	 */
	 
	public function findAll($tableName, $organisation_id);
        
	/**
	 * 
	 * @param type $id
	 * 
	 * to find details related to the Obejctives
	 */
	
	public function findStudentClubs($id);
	
	/**
	 * 
	 * @param type $ClubsInterface
	 * 
	 * to save budgetings
	 */
	
	public function saveDetails(Clubs $ClubsInterface);
	
	/**
	 * 
	 * @param type $ClubsInterface
	 * 
	 * to save budgetings
	 */
	
	public function saveClubApplications(ClubsApplication $ClubsInterface);
	
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
	 * 
	 * @return array/ Clubs[]
	 */
	 
	public function listSelectData($tableName, $columnName, $organisation_id);
	
}
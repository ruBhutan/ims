<?php

namespace PmsDates\Mapper;

use PmsDates\Model\PmsDates;

interface PmsDatesMapperInterface
{
	
	/*
	 * Get the Organisation Id
	*/
	 
	public function getOrganisationId($username);
	
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username);
	
	/*
	* take username and returns Name and any other detail required
	*/
	
	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);
	
	/*
	* Save the Activation Dates for PMS (appraisal and review)
	*/
	
	public function save(PmsDates $dateObject);
	
	/*
	* List all PMS Dates
	*/
	
	public function findAll();
	
	/*
	* Find PMS Dates given an $id
	*/
	
	public function find($id);
        
	/**
	 * 
	 * @return array/ PmsDates[]
	 */
	 
	public function listSelectData($tableName, $columnName, $organisation_id);
	
}
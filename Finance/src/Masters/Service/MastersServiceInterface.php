<?php

namespace Masters\Service;

use Masters\Model\FinancialInstitution;

interface MastersServiceInterface
{
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|MastersInterface[]
	*/
	
	public function listAll($tableName);
	
	/*
	* Find the Proposal Details
	*/
	
	public function findCalendarDetail($id);
	
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username);
	
	/*
	* Get organisation id based on the username
	*/
	
	public function getOrganisationId($username);
        
	 
	 /**
	 * @param MastersInterface $budgetingObject
	 *
	 * @param MastersInterface $budgetingObject
	 * @return MastersInterface
	 * @throws \Exception
	 */
	 
	 public function saveMasters(Masters $mastersObject);
	 
	 
	 /**
	 * Should return a set of all objectives that we can iterate over. 
	 * 
	 * The purpose of the function is the objectives for the dropdown select list
	 *
	 * @return array|MastersInterface[]
	*/
	
	public function listSelectData($tableName, $columnName, $condition);
		
		
}
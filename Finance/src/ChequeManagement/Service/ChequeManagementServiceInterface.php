<?php

namespace ChequeManagement\Service;

use ChequeManagement\Model\FinancialInstitution;

interface ChequeManagementServiceInterface
{
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|ChequeManagementInterface[]
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
	 * @param ChequeManagementInterface $budgetingObject
	 *
	 * @param ChequeManagementInterface $budgetingObject
	 * @return ChequeManagementInterface
	 * @throws \Exception
	 */
	 
	 public function saveChequeManagement(ChequeManagement $chequeObject);
	 
	 
	 /**
	 * Should return a set of all objectives that we can iterate over. 
	 * 
	 * The purpose of the function is the objectives for the dropdown select list
	 *
	 * @return array|ChequeManagementInterface[]
	*/
	
	public function listSelectData($tableName, $columnName, $condition);
		
		
}
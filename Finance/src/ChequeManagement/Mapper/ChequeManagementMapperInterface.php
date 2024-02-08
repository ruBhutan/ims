<?php

namespace ChequeManagement\Mapper;

use ChequeManagement\Model\Cheque;

interface ChequeManagementMapperInterface
{

	/**
	 * 
	 * @return array/ ChequeManagement[]
	 */
	 
	public function findAll($tableName);
	
	/*
	* Find the Academic Calendar Details
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
	 * 
	 * @param type $ChequeManagementInterface
	 * 
	 * to save academics
	 */
	
	public function saveChequeManagement(ChequeManagement $ChequeManagementInterface);
	
	/**
	 * 
	 * @return array/ ChequeManagement[]
	 */
	 
	public function listSelectData($tableName, $columnName, $condition);
	
}
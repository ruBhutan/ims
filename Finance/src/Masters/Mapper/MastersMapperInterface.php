<?php

namespace Masters\Mapper;

use Masters\Model\Masters;

interface MastersMapperInterface
{

	/**
	 * 
	 * @return array/ Masters[]
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
	 * @param type $MastersInterface
	 * 
	 * to save academics
	 */
	
	public function saveMasters(Masters $MastersInterface);
	
	/**
	 * 
	 * @return array/ Masters[]
	 */
	 
	public function listSelectData($tableName, $columnName, $condition);
	
}
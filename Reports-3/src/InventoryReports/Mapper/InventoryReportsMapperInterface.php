<?php

namespace InventoryReports\Mapper;

//use InventoryReports\Model\PlanningReports;
//use InventoryReports\Model\PlanningReportsCategory;

interface InventoryReportsMapperInterface
{	
	/*
	 * Get the Organisation Id
	 */
	 
	public function getOrganisationId($username);
	 
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username);

	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);

	public function getstaffDetail($report_details, $organisation_id);
	 
	public function listSelectData($tableName, $columnName, $organisation_id);
	
}
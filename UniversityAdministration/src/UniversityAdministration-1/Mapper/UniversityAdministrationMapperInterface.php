<?php

namespace UniversityAdministration\Mapper;

use UniversityAdministration\Model\UniversityAdministration;

interface UniversityAdministrationMapperInterface
{
	/*
	* Getting the id for username
	*/
	
	public function getUserDetailsId($tableName, $username);
	
	/*
	* Get organisation id based on the username
	*/
	
	public function getOrganisationId($tableName, $username);

	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);
}
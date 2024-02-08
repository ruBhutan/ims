<?php

namespace OrgSettings\Service;

use OrgSettings\Model\Organisation;

interface OrgSettingsServiceInterface
{
	
	/*
	* take username and returns Name and any other detail required
	*/
	
	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);
	
	/**
	 * Should return a set of all Organisations that we can iterate over. 
	 *
	 * @return array|EmployeeDetailInterface[]
	*/
	
	public function listAll($tableName);
	
	public function listOrganisationEmployee($date);
	
	public function findEmployeeDetails($empIds);

	/**
	 * Should return a single Organisation
	 *
	 * @param int $id Identifier of the Organisation that should be returned
	 * @return EmpWorkForceOrganisationInterface
	 */
	 
	public function findDetails($id, $tableName);
        
        
	/**
	 * Should return a single Organisation
	 *
	 * @param int $id Identifier of the Organisation that should be returned
	 * @return EmpWorkForceOrganisationInterface
	 */
        
     public function findOrganisationDetails($id);
	 
	 /**
	 * @param EmpWorkForceOrganisationInterface $empWorkForceOrganisationObject
	 *
	 * @param EmpWorkForceOrganisationInterface $empWorkForceOrganisationObject
	 * @return EmpWorkForceOrganisationInterface
	 * @throws \Exception
	 */
	 
	 public function save(Organisation $settingsObject);
	 
	 public function listSelectData($tableName, $columnName);
		
		
}
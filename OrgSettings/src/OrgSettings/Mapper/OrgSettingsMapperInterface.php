<?php

namespace OrgSettings\Mapper;

use OrgSettings\Model\Organisation;
use OrgSettings\Model\OrganisationDocuments;

interface OrgSettingsMapperInterface
{
	/*
	* take username and returns Name and any other detail required
	*/
	
	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);
	
	/**
	 * @param int/string $id
	 * @return EmpWorkForceProposal
	 * throws \InvalidArugmentException
	 * 
	*/
	
	public function find($id, $tableName);

	/**
	 * 
	 * @return array/ EmpWorkForceProposal[]
	 */
	 
	public function findAll($tableName);
        
	/**
	 * 
	 * @param type $id
	 * 
	 * to find details related to the work force proposal
	 */
	
	public function findDetails($id);
	
	/**
	 * 
	 * @param type $EmpWorkForceProposalInterface
	 * 
	 * to save work force proposals
	 */
	
	public function saveDetails(Organisation $settingsInterface);

	public function insertOrganisationDocument(OrganisationDocuments $settingsInterface, $organisation_id);
	
	public function listOrganisationEmployee($date);
	
	public function findEmployeeDetails($empIds);

	public function getUploadeOrganisationDocument($tableName, $document_type, $organisation_id);
	
	public function listSelectData($tableName, $columnName);
	
}
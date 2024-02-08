<?php

namespace DocumentFiling\Mapper;

use DocumentFiling\Model\DocumentFiling;
use DocumentFiling\Model\FilingType;
use DocumentFiling\Model\FilingDocument;

interface DocumentFilingMapperInterface
{
	/*
	* Getting the id for username
	*/
	
	public function getUserDetailsId($tableName, $username);
	
	/*
	* Get organisation id based on the username
	*/

	public function saveDetails(FilingType $FilingTypeInterface);
	
	public function getOrganisationId($tableName, $username);

	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);

	public function findAll($tableName, $columnName, $id, $department_id);

	public function saveFilingDocument(FilingDocument $filingdocumentObject);

	public function getFileName($table,$file_id);

	public function getFileName1($file_id);

	public function listSelectData($tableName, $organisation_id, $department_id);

	public function getFilingTypeDetails($id);

	public function listSelectData1($tableName, $id);

	public function getFilingDocumentDetails($id);

}
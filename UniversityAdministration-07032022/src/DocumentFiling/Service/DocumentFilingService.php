<?php

namespace DocumentFiling\Service;

use DocumentFiling\Mapper\DocumentFilingMapperInterface;
use DocumentFiling\Model\DocumentFiling;
use DocumentFiling\Model\FilingType;
use DocumentFiling\Model\FilingDocument;


class DocumentFilingService implements DocumentFilingServiceInterface
{
	/**
	 * @var \Blog\Mapper\PostMapperInterface
	*/
	
	protected $documentFilingMapper;
	
	public function __construct(DocumentFilingMapperInterface $documentFilingMapper) {
		$this->documentFilingMapper = $documentFilingMapper;
	}

	public function getUserDetailsId($tableName, $username)
	{
		return $this->documentFilingMapper->getUserDetailsId($tableName, $username);
	}
	
	public function getOrganisationId($tableName, $username)
	{
		return $this->documentFilingMapper->getOrganisationId($tableName, $username);
	}

	public function getUserDetails($username, $usertype)
	{
		return $this->documentFilingMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->documentFilingMapper->getUserImage($username, $usertype);
	}
	
	public function listAll($tableName, $columnName, $id, $department_id)
	{
		return $this->documentFilingMapper->findAll($tableName, $columnName, $id, $department_id);
	}

	public function saveFilingDocument(FilingDocument $filingdocumentObject)
	{
		return $this->documentFilingMapper->saveFilingDocument($filingdocumentObject);
	}

	public function getFileName($table,$file_id)
	{
		return $this->documentFilingMapper->getFileName($table,$file_id);
	}

	public function getFileName1($file_id)
	{
		return $this->documentFilingMapper->getFileName1($file_id);
	}

	public function saveCategory(FilingType $filingtypeObject) 
	{	
		return $this->documentFilingMapper->saveDetails($filingtypeObject);
	}

	public function listSelectData($tableName, $organisation_id, $department_id)
	{
		return $this->documentFilingMapper->listSelectData($tableName, $organisation_id, $department_id);
	}

	public function getFilingTypeDetails($id)
	{
		return $this->documentFilingMapper->getFilingTypeDetails($id);
	}

	public function listSelectData1($tableName, $id)
	{
		return $this->documentFilingMapper->listSelectData1($tableName, $id);
	}

	public function getFilingDocumentDetails($id)
	{
		return $this->documentFilingMapper->getFilingDocumentDetails($id);
	}
}
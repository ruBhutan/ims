<?php

namespace OrgSettings\Service;

use OrgSettings\Mapper\OrgSettingsMapperInterface;
use OrgSettings\Model\Organisation;
use OrgSettings\Model\OrganisationDocuments;

class OrgSettingsService implements OrgSettingsServiceInterface
{
	/**
	 * @var \Blog\Mapper\PostMapperInterface
	*/
	
	protected $settingsMapper;
	
	public function __construct(OrgSettingsMapperInterface $settingsMapper) {
		$this->settingsMapper = $settingsMapper;
	}
	
	public function getUserDetails($username, $usertype)
	{
		return $this->settingsMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->settingsMapper->getUserImage($username, $usertype);
	}
	
	public function listAll($tableName)
	{
		return $this->settingsMapper->findAll($tableName);
	}
	
	public function listOrganisationEmployee($date)
	{
		return $this->settingsMapper->listOrganisationEmployee($date);
	}
	
	public function findEmployeeDetails($empIds)
	{
		return $this->settingsMapper->findEmployeeDetails($empIds);
	}
	 
	public function findDetails($id, $tableName)
	{
		return $this->settingsMapper->find($id, $tableName);
	}
        
	public function findOrganisationDetails($id) 
	{
		return $this->settingsMapper->findDetails($id);;
	}

	public function getUploadeOrganisationDocument($tableName, $document_type, $organisation_id)
	{
		return $this->settingsMapper->getUploadeOrganisationDocument($tableName, $document_type, $organisation_id);
	}
	
	public function save(Organisation $settings) 
	{
		return $this->settingsMapper->saveDetails($settings);
	}

	public function insertOrganisationDocument(OrganisationDocuments $settings, $organisation_id)
	{
		return $this->settingsMapper->insertOrganisationDocument($settings, $organisation_id);
	}
	
	public function listSelectData($tableName, $columnName)
	{
		return $this->settingsMapper->listSelectData($tableName, $columnName);
	}
	
}
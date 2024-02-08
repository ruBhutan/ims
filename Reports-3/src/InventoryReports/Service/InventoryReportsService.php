<?php

namespace InventoryReports\Service;

use InventoryReports\Mapper\InventoryReportsMapperInterface;
//use PlanningReports\Model\PlanningReports;
//use InventoryReports\Model\PlanningReportsCategory;

class InventoryReportsService implements InventoryReportsServiceInterface
{
	/**
	 * @var \Blog\Mapper\inventoryreportsMapperInterface
	*/
	
	protected $inventoryreportsMapper;
	
	public function __construct(InventoryReportsMapperInterface $inventoryreportsMapper) {
		$this->inventoryreportsMapper = $inventoryreportsMapper;
	}
	
	public function getOrganisationId($username)
	{
		return $this->inventoryreportsMapper->getOrganisationId($username);
	}
	 	
	public function getUserDetailsId($username)
	{
		return $this->inventoryreportsMapper->getUserDetailsId($username);
	}

	public function getUserDetails($username, $usertype)
	{
		return $this->inventoryreportsMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->inventoryreportsMapper->getUserImage($username, $usertype);
	}

	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		return $this->inventoryreportsMapper->listSelectData($tableName, $columnName, $organisation_id);
	}

	public function getstaffDetail($report_details, $organisation_id)
	{
		return $this->inventoryreportsMapper->getstaffDetail($report_details, $organisation_id);
	}
	
}
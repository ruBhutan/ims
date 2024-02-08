<?php

namespace Masters\Service;

use Masters\Mapper\MastersMapperInterface;
use Masters\Model\FinancialInstitution;

class MastersService implements MastersServiceInterface
{
	/**
	 * @var \Blog\Mapper\MastersMapperInterface
	*/
	
	protected $mastersMapper;
	
	public function __construct(MastersMapperInterface $mastersMapper) {
		$this->mastersMapper = $mastersMapper;
	}
	
	public function listAll($tableName)
	{
		return $this->mastersMapper->findAll($tableName);
	}
	
	public function findCalendarDetail($id)
	{
		return $this->mastersMapper->findCalendarDetail($id);
	}
	
	public function getUserDetailsId($username)
	{
		return $this->mastersMapper->getUserDetailsId($username);
	}
	
	public function getOrganisationId($username)
	{
		return $this->mastersMapper->getOrganisationId($username);
	}
		
	public function saveMasters(Masters $mastersObject) 
	{
		return $this->mastersMapper->saveMasters($mastersObject);
	}
	
	public function listSelectData($tableName, $columnName, $condition)
	{
		return $this->mastersMapper->listSelectData($tableName, $columnName, $condition);
	}
	
}
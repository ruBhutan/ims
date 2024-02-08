<?php

namespace ChequeManagement\Service;

use ChequeManagement\Mapper\ChequeManagementMapperInterface;
use ChequeManagement\Model\FinancialInstitution;

class ChequeManagementService implements ChequeManagementServiceInterface
{
	/**
	 * @var \Blog\Mapper\ChequeManagementMapperInterface
	*/
	
	protected $chequeMapper;
	
	public function __construct(ChequeManagementMapperInterface $chequeMapper) {
		$this->chequeMapper = $chequeMapper;
	}
	
	public function listAll($tableName)
	{
		return $this->chequeMapper->findAll($tableName);
	}
	
	public function findCalendarDetail($id)
	{
		return $this->chequeMapper->findCalendarDetail($id);
	}
	
	public function getUserDetailsId($username)
	{
		return $this->chequeMapper->getUserDetailsId($username);
	}
	
	public function getOrganisationId($username)
	{
		return $this->chequeMapper->getOrganisationId($username);
	}
		
	public function saveChequeManagement(ChequeManagement $chequeObject) 
	{
		return $this->chequeMapper->saveChequeManagement($chequeObject);
	}
	
	public function listSelectData($tableName, $columnName, $condition)
	{
		return $this->chequeMapper->listSelectData($tableName, $columnName, $condition);
	}
	
}
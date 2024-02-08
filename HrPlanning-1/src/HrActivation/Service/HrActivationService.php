<?php

namespace HrActivation\Service;

use HrActivation\Mapper\HrActivationMapperInterface;
use HrActivation\Model\HrActivation;
use HrActivation\Model\HrActivationApproval;

class HrActivationService implements HrActivationServiceInterface
{
	/**
	 * @var \Blog\Mapper\PostMapperInterface
	*/
	
	protected $hrActivationDetailMapper;
	
	public function __construct(HrActivationMapperInterface $hrActivationDetailMapper) {
		$this->hrActivationDetailMapper = $hrActivationDetailMapper;
	}
	
	public function listAllActivationDates()
	{
		return $this->hrActivationDetailMapper->findAll();
	}
	 
	public function findActivationDate($id)
	{
		return $this->hrActivationDetailMapper->find($id);
	}
	
	public function save(HrActivation $hrActivationObject) 
	{
		return $this->hrActivationDetailMapper->saveDetails($hrActivationObject);
	}
			
	public function getUserDetailsId($username, $tableName)
	{
		return $this->hrActivationDetailMapper->getUserDetailsId($username, $tableName);
	}
	
	public function getUserDetails($username, $tableName)
	{
		return $this->hrActivationDetailMapper->getUserDetails($username, $tableName);
	}
	
	public function getOrganisationId($username)
	{
		return $this->hrActivationDetailMapper->getOrganisationId($username);
	}
	
	public function getFiveYearPlan()
	{
		return $this->hrActivationDetailMapper->getFiveYearPlan();
	}
	
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		return $this->hrActivationDetailMapper->listSelectData($tableName, $columnName, $organisation_id);
	}
	
}
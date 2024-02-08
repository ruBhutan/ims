<?php

namespace EmpTravelAuthorization\Service;

use EmpTravelAuthorization\Mapper\EmpTravelAuthorizationMapperInterface;
use EmpTravelAuthorization\Model\EmpTravelAuthorization;

class EmpTravelAuthorizationService implements EmpTravelAuthorizationServiceInterface
{
	/**
	 * @var \Blog\Mapper\PostMapperInterface
	*/
	
	protected $empTravelAuthorizationMapper;
	
	public function __construct(EmpTravelAuthorizationMapperInterface $empTravelAuthorizationMapper) {
		$this->empTravelAuthorizationMapper = $empTravelAuthorizationMapper;
	}
	
	public function listAllTravels()
	{
		return $this->empTravelAuthorizationMapper->findAll();
	}
	
	public function listTravelEmployee($date)
	{
		return $this->empTravelAuthorizationMapper->listTravelEmployee($date);
	}
	
	public function findEmployeeDetails($empIds)
	{
		return $this->empTravelAuthorizationMapper->findEmployeeDetails($empIds);
	}
	 
	public function findTravel($id)
	{
		return $this->empTravelAuthorizationMapper->find($id);
	}
        
	public function findTravelDetails($id) 
	{
		return $this->empTravelAuthorizationMapper->findDetails($id);;
	}
	
	public function save(EmpTravelAuthorization $empTravelAuthorization) 
	{
		return $this->empTravelAuthorizationMapper->saveDetails($empTravelAuthorization);
	}
		 
	public function getOrganisationId($username)
	{
		return $this->empTravelAuthorizationMapper->getOrganisationId($username);
	}
		
	public function getUserDetailsId($username, $tableName)
	{
		return $this->empTravelAuthorizationMapper->getUserDetailsId($username, $tableName);
	}

	
}
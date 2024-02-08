<?php

namespace PmsDates\Service;

use PmsDates\Mapper\PmsDatesMapperInterface;
use PmsDates\Model\PmsDates;

class PmsDatesService implements PmsDatesServiceInterface
{
	/**
	 * @var \Blog\Mapper\PmsDatesMapperInterface
	*/
	
	protected $pmsDatesMapper;
	
	public function __construct(PmsDatesMapperInterface $pmsDatesMapper) {
		$this->pmsDatesMapper = $pmsDatesMapper;
	}
		 
	public function getOrganisationId($username)
	{
		return $this->pmsDatesMapper->getOrganisationId($username);
	}
		
	public function getUserDetailsId($username)
	{
		return $this->pmsDatesMapper->getUserDetailsId($username);
	}
	
	public function getUserDetails($username, $usertype)
	{
		return $this->pmsDatesMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->pmsDatesMapper->getUserImage($username, $usertype);
	}
	
	public function save(PmsDates $dateObject)
	{
		return $this->pmsDatesMapper->save($dateObject);
	}
	
	public function listAll()
	{
		return $this->pmsDatesMapper->findAll();
	}
	
	public function find($id)
	{
		return $this->pmsDatesMapper->find($id);
	}
	
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		return $this->pmsDatesMapper->listSelectData($tableName, $columnName, $organisation_id);
	}
	
}
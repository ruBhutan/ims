<?php

namespace UniversityAdministration\Service;

use UniversityAdministration\Mapper\UniversityAdministrationMapperInterface;
use UniversityAdministration\Model\UniversityAdministration;


class UniversityAdministrationService implements UniversityAdministrationServiceInterface
{
	/**
	 * @var \Blog\Mapper\PostMapperInterface
	*/
	
	protected $universityAdministrationMapper;
	
	public function __construct(UniversityAdministrationMapperInterface $universityAdministrationMapper) {
		$this->universityAdministrationMapper = $universityAdministrationMapper;
	}

	public function getUserDetailsId($tableName, $username)
	{
		return $this->universityAdministrationMapper->getUserDetailsId($tableName, $username);
	}
	
	public function getOrganisationId($tableName, $username)
	{
		return $this->universityAdministrationMapper->getOrganisationId($tableName, $username);
	}


	public function getUserDetails($username, $usertype)
	{
		return $this->universityAdministrationMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->universityAdministrationMapper->getUserImage($username, $usertype);
	}
}
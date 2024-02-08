<?php

namespace Clubs\Service;

use Clubs\Mapper\ClubsMapperInterface;
use Clubs\Model\Clubs;
use Clubs\Model\ClubsApplication;

class ClubsService implements ClubsServiceInterface
{
	/**
	 * @var \Blog\Mapper\ClubsMapperInterface
	*/
	
	protected $clubsMapper;
	
	public function __construct(ClubsMapperInterface $clubsMapper) {
		$this->clubsMapper = $clubsMapper;
	}
	
	public function listAll($tableName, $organisation_id)
	{
		return $this->clubsMapper->findAll($tableName, $organisation_id);
	}
	 
	public function findClubs($id)
	{
		return $this->clubsMapper->findClubs($id);
	}

	public function crossCheckClubApplication($student_id, $id)
	{
		return $this->clubsMapper->crossCheckClubApplication($student_id, $id);
	}
        
	public function findStudentClubs($id) 
	{
		return $this->clubsMapper->findStudentClubs($id);;
	}
	
	public function save(Clubs $clubsObject) 
	{
		return $this->clubsMapper->saveDetails($clubsObject);
	}
	
	public function saveClubApplications(ClubsApplication $clubsObject) 
	{
		return $this->clubsMapper->saveClubApplications($clubsObject);
	}
		
	public function getUserDetailsId($username, $tableName)
	{
		return $this->clubsMapper->getUserDetailsId($username, $tableName);
	}
		 
	public function getOrganisationId($username, $usertype)
	{
		return $this->clubsMapper->getOrganisationId($username, $usertype);
	}

	public function getUserDetails($username, $usertype)
	{
		return $this->clubsMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->clubsMapper->getUserImage($username, $usertype);
	}
	
	public function getStudentDetails($id)
	{
		return $this->clubsMapper->getStudentDetails($id);
	}
		 
	public function listClubApplications($organisation_id)
	{
		return $this->clubsMapper->listClubApplications($organisation_id);
	}
	
	public function listClubMembers($organisation_id)
	{
		return $this->clubsMapper->listClubMembers($organisation_id);
	}

	public function getStudentClubMembership($clubs_id, $tableName)
	{
		return $this->clubsMapper->getStudentClubMembership($clubs_id, $tableName);
	}
		
	public function submitClubApplication($application_id, $status)
	{
		return $this->clubsMapper->submitClubApplication($application_id, $status);
	}
	
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		return $this->clubsMapper->listSelectData($tableName, $columnName, $organisation_id);
	}
	
}
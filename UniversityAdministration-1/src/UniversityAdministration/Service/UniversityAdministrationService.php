<?php

namespace UniversityAdministration\Service;

use UniversityAdministration\Mapper\UniversityAdministrationMapperInterface;
use UniversityAdministration\Model\UniversityAdministration;
use UniversityAdministration\Model\NewsPaper;
use UniversityAdministration\Model\MeetingType;
use UniversityAdministration\Model\MeetingMinutes;


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
	
	public function listAll($tableName, $columnName, $id)
	{
		return $this->universityAdministrationMapper->findAll($tableName, $columnName, $id);
	}

	public function saveNewsPaper(NewsPaper $newspaperObject)
	{
		return $this->universityAdministrationMapper->saveNewsPaper($newspaperObject);
	}

	public function saveMeetingMinutes(MeetingMinutes $meetingminutesObject)
	{
		return $this->universityAdministrationMapper->saveMeetingMinutes($meetingminutesObject);
	}

	public function getFileName($table,$file_id)
	{
		return $this->universityAdministrationMapper->getFileName($table,$file_id);
	}

	public function getFileName1($file_id)
	{
		return $this->universityAdministrationMapper->getFileName1($file_id);
	}

	public function saveCategory(MeetingType $meetingtypeObject) 
	{	
		return $this->universityAdministrationMapper->saveDetails($meetingtypeObject);
	}

	public function listSelectData($tableName, $organisation_id)
	{
		return $this->universityAdministrationMapper->listSelectData($tableName, $organisation_id);
	}

	public function getMeetingTypeDetails($id)
	{
		return $this->universityAdministrationMapper->getMeetingTypeDetails($id);
	}

	public function listSelectData1($tableName, $id)
	{
		return $this->universityAdministrationMapper->listSelectData1($tableName, $id);
	}

	public function getMeetingMinutesDetails($id)
	{
		return $this->universityAdministrationMapper->getMeetingMinutesDetails($id);
	}
}
<?php

namespace UniversityAdministration\Mapper;

use UniversityAdministration\Model\UniversityAdministration;
use UniversityAdministration\Model\NewsPaper;
use UniversityAdministration\Model\MeetingType;
use UniversityAdministration\Model\MeetingMinutes;

interface UniversityAdministrationMapperInterface
{
	/*
	* Getting the id for username
	*/
	
	public function getUserDetailsId($tableName, $username);
	
	/*
	* Get organisation id based on the username
	*/

	public function saveDetails(MeetingType $MeetingTypeInterface);
	
	public function getOrganisationId($tableName, $username);

	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);

	public function findAll($tableName, $columnName, $id);

	public function saveNewsPaper(NewsPaper $newspaperObject);

	public function saveMeetingMinutes(MeetingMinutes $meetingminutesObject);

	public function getFileName($table,$file_id);

	public function getFileName1($file_id);

	public function listSelectData($tableName, $organisation_id);

	public function getMeetingTypeDetails($id);

	public function listSelectData1($tableName, $id);

	public function getMeetingMinutesDetails($id);

}
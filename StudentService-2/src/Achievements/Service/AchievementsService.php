<?php

namespace Achievements\Service;

use Achievements\Mapper\AchievementsMapperInterface;
use Achievements\Model\Achievements;
use Achievements\Model\AchievementsCategory;

class AchievementsService implements AchievementsServiceInterface
{
	/**
	 * @var \Blog\Mapper\AchievementsMapperInterface
	*/
	
	protected $achievementMapper;
	
	public function __construct(AchievementsMapperInterface $achievementMapper) {
		$this->achievementMapper = $achievementMapper;
	}
		 
	public function getOrganisationId($username)
	{
		return $this->achievementMapper->getOrganisationId($username);
	}
		
	public function getUserDetailsId($username)
	{
		return $this->achievementMapper->getUserDetailsId($username);
	}

	public function getUserDetails($username, $usertype)
	{
		return $this->achievementMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->achievementMapper->getUserImage($username, $usertype);
	}
	
	public function listAll($tableName, $organisation_id)
	{
		return $this->achievementMapper->findAll($tableName, $organisation_id);
	}
	 
	public function findAchievements($id)
	{
		return $this->achievementMapper->findVisionMission($id);
	}
	
	public function saveAchievements(Achievements $achievementObject) 
	{
		return $this->achievementMapper->saveDetails($achievementObject);
	}

	public function updateAchievements(Achievements $achievementObject)
	{
		return $this->achievementMapper->updateAchievements($achievementObject);
	}
		 
	public function saveAchievementsCategory(AchievementsCategory $achievementObject)
	{
		return $this->achievementMapper->saveAchievementsCategory($achievementObject);
	}
	
	public function getAchievements($organisation_id)
	{
		return $this->achievementMapper->getAchievements($organisation_id);
	}
	
	public function getStudentList($studentName, $studentId, $programme, $organisation_id)
	{
		return $this->achievementMapper->getStudentList($studentName, $studentId, $programme, $organisation_id);
	}
		
	public function getStudentAchievementList($studentName, $studentId, $programme, $organisation_id)
	{
		return $this->achievementMapper->getStudentAchievementList($studentName, $studentId, $programme, $organisation_id);
	}
	
	public function getStudentAchievements($student_id)
	{
		return $this->achievementMapper->getStudentAchievements($student_id);
	}
	
	public function getStudentDetails($tableName, $id)
	{
		return $this->achievementMapper->getStudentDetails($tableName, $id);
	}

	public function getAchievementsCategoryDetails($id)
	{
		return $this->achievementMapper->getAchievementsCategoryDetails($id);
	}

	public function getStudentDetail($id)
	{
		return $this->achievementMapper->getStudentDetail($id);
	}

	public function getStudentAchievementDetails($id)
	{
		return $this->achievementMapper->getStudentAchievementDetails($id);
	}

	public function getFileName($id)
	{
		return $this->achievementMapper->getFileName($id);
	}


	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		return $this->achievementMapper->listSelectData($tableName, $columnName, $organisation_id);
	}
	
}
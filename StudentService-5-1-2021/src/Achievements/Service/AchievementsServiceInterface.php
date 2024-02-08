<?php

namespace Achievements\Service;

use Achievements\Model\Achievements;
use Achievements\Model\AchievementsCategory;

//need to add more models

interface AchievementsServiceInterface
{
	/*
	* Get the Organisation Id
	*/
	 
	public function getOrganisationId($username);
	
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username);

	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);
	
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|AchievementsInterface[]
	*/
	
	public function listAll($tableName, $organisation_id);

	/**
	 * Should return a single proposal
	 *
	 * @param int $id Identifier of the proposal that should be returned
	 * @return AchievementsInterface
	 */
	 
	public function findAchievements($id);
        
	 /**
	 * @param AchievementsInterface $achievementObject
	 *
	 * @param AchievementsInterface $achievementObject
	 * @return AchievementsInterface
	 * @throws \Exception
	 */
	 
	 public function saveAchievements(Achievements $achievementObject);

	 public function updateAchievements(Achievements $achievementObject);
	 
	 /*
	 * To save the various achievements categories
	 */
	 
	 public function saveAchievementsCategory(AchievementsCategory $achievementObject);
	 
	 /*
	 * Get a list of all the achievements 
	 **/
	 
	 public function getAchievements($organisation_id);
	 
	 
	 /**
	 * Should return a set of all students that we search. 
	 * 
	 * The purpose of the function is get a student and add achievement
	 *
	 * @return array|AchievementsInterface[]
	*/
	
	public function getStudentList($studentName, $studentId, $programme, $organisation_id);
	
	/*
	* Get the list of achievements by students after serach funcationality
	*/
	
	public function getStudentAchievementList($studentName, $studentId, $programme, $organisation_id);
	
	/*
	* Get the list of achievements by a student
	*/
	
	public function getStudentAchievements($student_id);
	
	/**
	 * 
	 * @param type $id
	 * 
	 * to find Student details so that their names are displayed
	 */
	public function getStudentDetails($tableName, $id);

	public function getAchievementsCategoryDetails($id);

	public function getStudentDetail($id);

	public function getStudentAchievementDetails($id);

	public function getFileName($id);

	public function listSelectData($tableName, $columnName, $organisation_id);
		
		
}
<?php

namespace Achievements\Mapper;

use Achievements\Model\Achievements;
use Achievements\Model\AchievementsCategory;

interface AchievementsMapperInterface
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
	 * @param int/string $id
	 * @return Achievements
	 * throws \InvalidArugmentException
	 * 
	*/
	
	public function findAchievements($id);

	/**
	 * 
	 * @return array/ Achievements[]
	 */
	 
	public function findAll($tableName, $organisation_id);
        
	/**
	 * 
	 * @param type $AchievementsInterface
	 * 
	 * to save budgetings
	 */
	
	public function saveDetails(Achievements $achievementsInterface);

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
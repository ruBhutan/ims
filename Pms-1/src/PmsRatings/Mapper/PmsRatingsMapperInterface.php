<?php

namespace PmsRatings\Mapper;

use PmsRatings\Model\Subordinate;
use PmsRatings\Model\Peer;
use PmsRatings\Model\Beneficiary;
use PmsRatings\Model\Student;
use PmsRatings\Model\FeedbackQuestions;

interface PmsRatingsMapperInterface
{
	
	/*
	* take username and returns Name and any other detail required
	*/
	
	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);
	
	/**
	 * @param int/string $id
	 * @return PmsRatings
	 * throws \InvalidArugmentException
	 * 
	*/
	
	public function findQuestion($id, $tableName);

	/**
	 * 
	 * @return array/ PmsRatings[]
	 */
	 
	public function findAll($tableName);
        
	/**
	 * 
	 * @param type $PmsRatingsInterface
	 * 
	 * to save budgetings
	 */
	
	public function saveQuestion(FeedbackQuestions $PmsRatingsInterface, $tableName);
	
	/**
	 * 
	 * @return array/ PmsRatings[]
	 */
	 
	public function listSelectData($tableName, $columnName);
	
}
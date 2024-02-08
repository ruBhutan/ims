<?php

namespace PmsRatings\Service;

use PmsRatings\Model\Student;
use PmsRatings\Model\Subordinate;
use PmsRatings\Model\Peer;
use PmsRatings\Model\Beneficiary;
use PmsRatings\Model\FeedbackQuestions;

//need to add more models

interface PmsRatingsServiceInterface
{
	/*
	* take username and returns Name and any other detail required
	*/
	
	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);
	
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|PmsRatingsInterface[]
	*/
	
	public function listAll($tableName);

	/**
	 * Should return a single proposal
	 *
	 * @param int $id Identifier of the proposal that should be returned
	 * @return PmsRatingsInterface
	 */
	 
	public function findQuestion($id, $tableName);
        
	 /**
	 * @param PmsRatingsInterface $pmsObject
	 *
	 * @param PmsRatingsInterface $pmsObject
	 * @return PmsRatingsInterface
	 * @throws \Exception
	 */
	 
	 public function save(FeedbackQuestions $pmsObject, $tableName);
	 
	 
	 /**
	 * Should return a set of all objectives that we can iterate over. 
	 * 
	 * The purpose of the function is the objectives for the dropdown select list
	 *
	 * @return array|PmsRatingsInterface[]
	*/
	
	public function listSelectData($tableName, $columnName);
		
		
}
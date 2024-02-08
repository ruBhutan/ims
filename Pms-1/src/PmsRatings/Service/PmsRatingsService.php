<?php

namespace PmsRatings\Service;

use PmsRatings\Mapper\PmsRatingsMapperInterface;
use PmsRatings\Model\Student;
use PmsRatings\Model\Beneficiary;
use PmsRatings\Model\Peer;
use PmsRatings\Model\Subordinate;
use PmsRatings\Model\FeedbackQuestions;

class PmsRatingsService implements PmsRatingsServiceInterface
{
	/**
	 * @var \Blog\Mapper\PmsRatingsMapperInterface
	*/
	
	protected $pmsMapper;
	
	public function __construct(PmsRatingsMapperInterface $pmsMapper) {
		$this->pmsMapper = $pmsMapper;
	}
	
	public function getUserDetails($username, $usertype)
	{
		return $this->pmsMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->pmsMapper->getUserImage($username, $usertype);
	}
	
	public function listAll($tableName)
	{
		return $this->pmsMapper->findAll($tableName);
	}
	 
	public function findQuestion($id, $tableName)
	{
		return $this->pmsMapper->findQuestion($id, $tableName);
	}
        	
	public function save(FeedbackQuestions $pmsObject, $tableName) 
	{
		return $this->pmsMapper->saveQuestion($pmsObject, $tableName);
	}
			
	public function listSelectData($tableName, $columnName)
	{
		return $this->pmsMapper->listSelectData($tableName, $columnName);
	}
	
}
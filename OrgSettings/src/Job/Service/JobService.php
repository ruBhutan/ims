<?php

namespace Job\Service;

use Job\Mapper\JobMapperInterface;
use Job\Model\Job;
use Job\Model\EmploymentStatus;
use Job\Model\MajorOccupationalGroup;
use Job\Model\PayScale;
use Job\Model\PositionCategory;
use Job\Model\PositionLevel;
use Job\Model\PositionTitle;
use Job\Model\RentAllowance;
use Job\Model\TeachingAllowance;
use Job\Model\FundingCategory;
use Job\Model\StudyLevelCategory;
use Job\Model\ResearchCategory;

class JobService implements JobServiceInterface
{
	/**
	 * @var \Job\Mapper\JobMapperInterface
	*/
	
	protected $jobDetailMapper;
	
	public function __construct(JobMapperInterface $jobDetailMapper) {
		$this->jobDetailMapper = $jobDetailMapper;
	}
	
	public function listAllJobs()
	{
		return $this->jobDetailMapper->findAll();
	}
	 
	public function findJob($id, $tableName)
	{
		return $this->jobDetailMapper->find($id, $tableName);
	}
        
	public function findJobDetails($id) 
	{
		return $this->jobDetailMapper->findDetails($id);;
	}
	
	public function save(Job $job) 
	{
		return $this->jobDetailMapper->saveDetails($job);
	}
	
	/**
	 * Should return a set of position titles
	 */
	 
	 public function listPositionTitle()
	 {
		 return $this->jobDetailMapper->findPositionTitles();
	 }
	 
	 /**
	 * Should return a set of position levels
	 */
	 
	 public function listPositionLevel()
	 {
		 return $this->jobDetailMapper->findPositionLevels();
	 }
	 
	 /**
	 * Should return a set of teachng allowances
	 */
	 
	 public function listTeachingAllowance()
	 {
		 return $this->jobDetailMapper->findTeachingAllowances();
	 }
	 
	 /**
	 * Should return a set of Pay Scale
	 */
	 
	 public function listPayScale()
	 {
		 return $this->jobDetailMapper->findPayScales();
	 }
	 
	 /**
	 * Should return a set of Employment Status
	 */
	 
	 public function listEmploymentStatus()
	 {
		 return $this->jobDetailMapper->findEmploymentStatus();
	 }
	 
	 /*
	 * Should return a set of Position Category
	 */
	 
	 public function listPositionCategory()
	 {
		 return $this->jobDetailMapper->findPositionCategory();
	 }
	 
	 /**
	 * Should return a set of rent allowances
	 */
	 
	 public function listRentAllowance()
	 {
		 return $this->jobDetailMapper->findRentAllowances();
	 }
	 	 
	 public function listStudyLevel()
	 {
		 return $this->jobDetailMapper->findStudyLevel();
	 }
	 	 
	 public function listFundingCategory()
	 {
		 return $this->jobDetailMapper->findFundingCategory();
	 }
	 	 
	 public function listResearchType()
	 {
		 return $this->jobDetailMapper->findResearchType();
	 }
	 
	 public function saveEmploymentStatus(EmploymentStatus $employmentObject)
	 {
		 return $this->jobDetailMapper->saveEmploymentStatus($employmentObject);
	 }
	 
	 public function saveOccupationalGroup(MajorOccupationalGroup $occupationalObject)
	 {
		 return $this->jobDetailMapper->saveOccupationalGroup($occupationalObject);
	 }
	 
	 public function savePayScale(PayScale $payObject)
	 {
		 return $this->jobDetailMapper->savePayScale($payObject);
	 }
	 
	 public function savePositionCategory(PositionCategory $categoryObject)
	 {
		 return $this->jobDetailMapper->savePositionCategory($categoryObject);
	 }
	 
	 public function savePositionLevel(PositionLevel $positionObject)
	 {
		 return $this->jobDetailMapper->savePositionLevel($positionObject);
	 }
	 
	 public function savePositionTitle(PositionTitle $positionObject)
	 {
		 return $this->jobDetailMapper->savePositionTitle($positionObject);
	 }
	 
	 public function saveRentAllowance(RentAllowance $allowanceObject)
	 {
		 return $this->jobDetailMapper->saveRentAllowance($allowanceObject);
	 }
	 
	 public function saveTeachingAllowance(TeachingAllowance $allowanceObject)
	 {
		 return $this->jobDetailMapper->saveTeachingAllowance($allowanceObject);
	 }
	 
	 public function saveFunding(FundingCategory $fundingObject)
	 {
		 return $this->jobDetailMapper->saveFunding($fundingObject);
	 }
	 
	 public function saveResearchCategory(ResearchCategory $categoryObject)
	 {
		 return $this->jobDetailMapper->saveResearchCategory($categoryObject);
	 }
	 
	 public function saveStudyLevel(StudyLevelCategory $studyObject)
	 {
		 return $this->jobDetailMapper->saveStudyLevel($studyObject);
	 }
	 
	 public function listSelectData($tableName, $columnName)
	 {
		 return $this->jobDetailMapper->listSelectData($tableName, $columnName);
	 }
	
}
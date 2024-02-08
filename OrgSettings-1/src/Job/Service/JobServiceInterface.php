<?php

namespace Job\Service;

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

interface JobServiceInterface
{
	/**
	 * Should return a set of all jobs that we can iterate over. 
	 *
	 * @return array|Job[]
	*/
	
	public function listAllJobs();

	/**
	 * Should return a single job
	 *
	 * @param int $id Identifier of the job that should be returned
	 * @return Job
	 */
	 
	public function findJob($id, $tableName);
        
        
	/**
	 * Should return a single job
	 *
	 * @param int $id Identifier of the job that should be returned
	 * @return Job
	 */
        
     public function findJobDetails($id);
	 
	 /**
	 * @param Job $job
	 *
	 * @param Job $job
	 * @return Job
	 * @throws \Exception
	 */
	 
	 public function save(Job $jobObject);
	 
	 /**
	 * Should return a set of position titles
	 */
	 
	 public function listPositionTitle();
	 
	 /**
	 * Should return a set of position levels
	 */
	 
	 public function listPositionLevel();
	 
	 /**
	 * Should return a set of teachng allowances
	 */
	 
	 public function listTeachingAllowance();
	 
	 /**
	 * Should return a set of Pay Scale
	 */
	 
	 public function listPayScale();
	 
	 /**
	 * Should return a set of Employment Status
	 */
	 
	 public function listEmploymentStatus();
	 
	 /*
	 * Should return a set of Position Category
	 */
	 
	 public function listPositionCategory();
	 
	 /**
	 * Should return a set of rent allowances
	 */
	 
	 public function listRentAllowance();
	 
	 /*
	 * Get list of study level
	 */
	 
	 public function listStudyLevel();
	 
	 /*
	 * get list of funding categories
	 */
	 
	 public function listFundingCategory();
	 
	 /*
	 * get list of research types
	 */
	 
	 public function listResearchType();
	 
	 /*
	 * The following functions are to save Position Title, Category etc.
	 */
	 
	 public function saveEmploymentStatus(EmploymentStatus $employmentObject);
	 
	 public function saveOccupationalGroup(MajorOccupationalGroup $occupationalObject);
	 
	 public function savePayScale(PayScale $payObject);
	 
	 public function savePositionCategory(PositionCategory $categoryObject);
	 
	 public function savePositionLevel(PositionLevel $positionObject);
	 
	 public function savePositionTitle(PositionTitle $positionObject);
	 
	 public function saveRentAllowance(RentAllowance $allowanceObject);
	 
	 public function saveTeachingAllowance(TeachingAllowance $allowanceObject);
	 
	 public function saveFunding(FundingCategory $fundingObject);
	 
	 public function saveResearchCategory(ResearchCategory $categoryObject);
	 
	 public function saveStudyLevel(StudyLevelCategory $studyObject);
	 
	 public function listSelectData($tableName, $columnName);
		
}
<?php

namespace HrSettings\Service;

use HrSettings\Model\Job;
use HrSettings\Model\EmploymentStatus;
use HrSettings\Model\MajorOccupationalGroup;
use HrSettings\Model\PayScale;
use HrSettings\Model\PositionCategory;
use HrSettings\Model\PositionLevel;
use HrSettings\Model\PositionTitle;
use HrSettings\Model\RentAllowance;
use HrSettings\Model\UniversityAllowance;
use HrSettings\Model\TeachingAllowance;
use HrSettings\Model\FundingCategory;
use HrSettings\Model\StudyLevelCategory;
use HrSettings\Model\ResearchCategory;
use HrSettings\Model\EmpAwardCategory;
use HrSettings\Model\EmpCommunityServiceCategory;
use HrSettings\Model\EmpContributionCategory;
use HrSettings\Model\EmpResponsibilityCategory;

interface HrSettingsServiceInterface
{
	/*
	* take username and returns organisation id
	*/
	public function getOrganisationId($username);

	/*
	* take username and returns Name and any other detail required
	*/
	
	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);
	
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
	 
	 public function save(HrSettings $jobObject);
	 
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


	 public function listUniversityAllowance();
	 
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

	  public function findHrSettings($id, $tableName);

	 public function listAwardCategory($organisation_id);

	  public function listCommunityServiceCategory($organisation_id);

	  public function listContributionCategory($organisation_id);

	  public function listResponsibilityCategory($organisation_id);

	  public function findHrOtherSetting($id, $tableName);
	 
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

	 public function saveUniversityAllowance(UniversityAllowance $allowanceObject);
	 
	 public function saveTeachingAllowance(TeachingAllowance $allowanceObject);
	 
	 public function saveFunding(FundingCategory $fundingObject);
	 
	 public function saveResearchCategory(ResearchCategory $categoryObject);
	 
	 public function saveStudyLevel(StudyLevelCategory $studyObject);

	 public function saveAwardCategory(EmpAwardCategory $otherCategoryObject);

	 public function saveCommunityServiceCategory(EmpCommunityServiceCategory $otherCategoryObject);

	 public function saveContributionCategory(EmpContributionCategory $otherCategoryObject);

	 public function saveResponsibilityCategory(EmpResponsibilityCategory $otherCategoryObject);
	 
	 public function listSelectData($tableName, $columnName);
		
}
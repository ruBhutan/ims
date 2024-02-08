<?php

namespace HrSettings\Service;

use HrSettings\Mapper\HrSettingsMapperInterface;
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

class HrSettingsService implements HrSettingsServiceInterface
{
	/**
	 * @var \HrSettings\Mapper\JobMapperInterface
	*/
	
	protected $settingsMapper;
	
	public function __construct(HrSettingsMapperInterface $settingsMapper) {
		$this->settingsMapper = $settingsMapper;
	}

	public function getOrganisationId($username)
	{
		return $this->settingsMapper->getOrganisationId($username);
	}
	
	public function getUserDetails($username, $usertype)
	{
		return $this->settingsMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->settingsMapper->getUserImage($username, $usertype);
	}
	
	public function listAllJobs()
	{
		return $this->settingsMapper->findAll();
	}
	 
	public function findJob($id, $tableName)
	{
		return $this->settingsMapper->find($id, $tableName);
	}
        
	public function findJobDetails($id) 
	{
		return $this->settingsMapper->findDetails($id);;
	}
	
	public function save(HrSettings $job) 
	{
		return $this->settingsMapper->saveDetails($job);
	}
	
	/**
	 * Should return a set of position titles
	 */
	 
	 public function listPositionTitle()
	 {
		 return $this->settingsMapper->findPositionTitles();
	 }
	 
	 /**
	 * Should return a set of position levels
	 */
	 
	 public function listPositionLevel()
	 {
		 return $this->settingsMapper->findPositionLevels();
	 }
	 
	 /**
	 * Should return a set of teachng allowances
	 */
	 
	 public function listTeachingAllowance()
	 {
		 return $this->settingsMapper->findTeachingAllowances();
	 }
	 
	 /**
	 * Should return a set of Pay Scale
	 */
	 
	 public function listPayScale()
	 {
		 return $this->settingsMapper->findPayScales();
	 }
	 
	 /**
	 * Should return a set of Employment Status
	 */
	 
	 public function listEmploymentStatus()
	 {
		 return $this->settingsMapper->findEmploymentStatus();
	 }
	 
	 /*
	 * Should return a set of Position Category
	 */
	 
	 public function listPositionCategory()
	 {
		 return $this->settingsMapper->findPositionCategory();
	 }
	 
	 /**
	 * Should return a set of rent allowances
	 */
	 
	 public function listRentAllowance()
	 {
		 return $this->settingsMapper->findRentAllowances();
	 }


	 public function listUniversityAllowance()
	 {
	 	return $this->settingsMapper->findUniversityAllowances();
	 }
	 	 
	 public function listStudyLevel()
	 {
		 return $this->settingsMapper->findStudyLevel();
	 }
	 	 
	 public function listFundingCategory()
	 {
		 return $this->settingsMapper->findFundingCategory();
	 }
	 	 
	 public function listResearchType()
	 {
		 return $this->settingsMapper->findResearchType();
	 }


	 public function findHrSettings($id, $tableName)
	 {
	 	return $this->settingsMapper->findHrSettings($id, $tableName);
	 }


	 public function listAwardCategory($organisation_id)
	 {
	 	return $this->settingsMapper->findAwardCategory($organisation_id);
	 }

	 public function listCommunityServiceCategory($organisation_id)
	 {
	 	return $this->settingsMapper->findCommunityServiceCategory($organisation_id);
	 }

	 public function listContributionCategory($organisation_id)
	 {
	 	return $this->settingsMapper->findContributionCategory($organisation_id);
	 }


	 public function listResponsibilityCategory($organisation_id)
	 {
	 	return $this->settingsMapper->findResponsibilityCategory($organisation_id);
	 }


	 public function findHrOtherSetting($id, $tableName)
	 {
	 	return $this->settingsMapper->findHrOtherSetting($id, $tableName);
	 }

	 
	 public function saveEmploymentStatus(EmploymentStatus $employmentObject)
	 {
		 return $this->settingsMapper->saveEmploymentStatus($employmentObject);
	 }
	 
	 public function saveOccupationalGroup(MajorOccupationalGroup $occupationalObject)
	 {
		 return $this->settingsMapper->saveOccupationalGroup($occupationalObject);
	 }
	 
	 public function savePayScale(PayScale $payObject)
	 {
		 return $this->settingsMapper->savePayScale($payObject);
	 }
	 
	 public function savePositionCategory(PositionCategory $categoryObject)
	 {
		 return $this->settingsMapper->savePositionCategory($categoryObject);
	 }
	 
	 public function savePositionLevel(PositionLevel $positionObject)
	 {
		 return $this->settingsMapper->savePositionLevel($positionObject);
	 }
	 
	 public function savePositionTitle(PositionTitle $positionObject)
	 {
		 return $this->settingsMapper->savePositionTitle($positionObject);
	 }
	 
	 public function saveRentAllowance(RentAllowance $allowanceObject)
	 {
		 return $this->settingsMapper->saveRentAllowance($allowanceObject);
	 }

	 public function saveUniversityAllowance(UniversityAllowance $allowanceObject)
	 {
	 	return $this->settingsMapper->saveUniversityAllowance($allowanceObject);
	 }
	 
	 public function saveTeachingAllowance(TeachingAllowance $allowanceObject)
	 {
		 return $this->settingsMapper->saveTeachingAllowance($allowanceObject);
	 }
	 
	 public function saveFunding(FundingCategory $fundingObject)
	 {
		 return $this->settingsMapper->saveFunding($fundingObject);
	 }
	 
	 public function saveResearchCategory(ResearchCategory $categoryObject)
	 {
		 return $this->settingsMapper->saveResearchCategory($categoryObject);
	 }
	 
	 public function saveStudyLevel(StudyLevelCategory $studyObject)
	 {
		 return $this->settingsMapper->saveStudyLevel($studyObject);
	 }

	 public function saveAwardCategory(EmpAwardCategory $otherCategoryObject)
	 {
	 	return $this->settingsMapper->saveAwardCategory($otherCategoryObject);
	 }

	 public function saveCommunityServiceCategory(EmpCommunityServiceCategory $otherCategoryObject)
	 {
	 	return $this->settingsMapper->saveCommunityServiceCategory($otherCategoryObject);
	 }

	 public function saveContributionCategory(EmpContributionCategory $otherCategoryObject)
	 {
	 	return $this->settingsMapper->saveContributionCategory($otherCategoryObject);
	 }

	 public function saveResponsibilityCategory(EmpResponsibilityCategory $otherCategoryObject)
	 {
	 	return $this->settingsMapper->saveResponsibilityCategory($otherCategoryObject);
	 }
	 
	 public function listSelectData($tableName, $columnName)
	 {
		 return $this->settingsMapper->listSelectData($tableName, $columnName);
	 }
	
}
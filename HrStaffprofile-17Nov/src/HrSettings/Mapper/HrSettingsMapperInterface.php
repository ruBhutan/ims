<?php

namespace HrSettings\Mapper;

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

interface HrSettingsMapperInterface
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
	 * @param int/string $id
	 * @return EmpWorkForceProposal
	 * throws \InvalidArugmentException
	 * 
	*/
	
	public function find($id, $tableName);

	/**
	 * 
	 * @return array/ EmpWorkForceProposal[]
	 */
	 
	public function findAll();
        
	/**
	 * 
	 * @param type $id
	 * 
	 * to find details related to the work force proposal
	 */
	
	public function findDetails($id);
	
	/**
	 * Should return a set of position titles
	 */
	 
	 public function findPositionTitles();
	 
	 /**
	 * Should return a set of position levels
	 */
	 
	 public function findPositionLevels();
	 
	 /**
	 * Should return a set of teachng allowances
	 */
	 
	 public function findTeachingAllowances();
	 
	 /**
	 * Should return a set of Pay Scale
	 */
	 
	 public function findPayScales();
	 
	 /**
	 * Should return a set of Employment Status
	 */
	 
	 public function findEmploymentStatus();
	 
	 /*
	 * Should return a set of Position Category
	 */
	 
	 public function findPositionCategory();
	 
	 /**
	 * Should return a set of rent allowances
	 */
	 
	 public function findRentAllowances();


	 public function findUniversityAllowances();
	 
	 /*
	 * Get list of study level
	 */
	 
	 public function findStudyLevel();
	 
	 /*
	 * get list of funding categories
	 */
	 
	 public function findFundingCategory();
	 
	 /*
	 * get list of research types
	 */
	 
	 public function findResearchType();

	  public function findHrSettings($id, $tableName);

	 public function findAwardCategory($organisation_id);

	 public function findCommunityServiceCategory($organisation_id);

	 public function findContributionCategory($organisation_id);

	 public function findResponsibilityCategory($organisation_id);

	 public function findHrOtherSetting($id, $tableName);
	
	/**
	 * 
	 * @param type $EmpWorkForceProposalInterface
	 * 
	 * to save work force proposals
	 */
	
	public function saveDetails(HrSettings $jobInterface);
	
	/*
	* Various Save functions to save Category etc.
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
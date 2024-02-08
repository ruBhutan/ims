<?php

namespace Job\Mapper;

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

interface JobMapperInterface
{
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
	
	/**
	 * 
	 * @param type $EmpWorkForceProposalInterface
	 * 
	 * to save work force proposals
	 */
	
	public function saveDetails(Job $jobInterface);
	
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
	 
	public function saveTeachingAllowance(TeachingAllowance $allowanceObject);
	
	public function saveFunding(FundingCategory $fundingObject);
	 
	public function saveResearchCategory(ResearchCategory $categoryObject);
	 
	public function saveStudyLevel(StudyLevelCategory $studyObject);
	
	public function listSelectData($tableName, $columnName);
	
}
<?php

namespace EmpTraining\Mapper;

use EmpTraining\Model\TrainingDetails;
use EmpTraining\Model\HrdTrainingPlan;
use EmpTraining\Model\WorkshopDetails;
use EmpTraining\Model\TrainingNomination;
use EmpTraining\Model\ShortTermApplication;
use EmpTraining\Model\LongTermApplication;
use EmpTraining\Model\TrainingReport;
use EmpTraining\Model\StudyReport;
use EmpTraining\Model\StudyExtension;

interface EmpTrainingMapperInterface
{
	/**
	* @param int/String $id
	* @return array Employee Details
	* @throws \InvalidArgumentException
	*/
	
	public function findEmpDetails($id);
	
	/*
	 * Get the Organisation Id
	 */
	 
	public function getOrganisationId($username);
	
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username);
	
	/*
	* take username and returns Name and any other detail required
	*/
	
	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);

	/**
	 * 
	 * @return array/ EmpTraining[]
	 */
	 
	public function findAll($tableName);
	
	/*
	* List all training details
	*/
	
	public function listTrainingDetails($table_name, $organisation_id);
	
	/**
	 * 
	 * @return array/ HrdPlan[]
	 */
	 
	public function listHrdPlan($type, $organisation_id);
	
	/**
	 * 
	 * @return array/ HrdPlan[]
	 */
	 
	public function findPlanDetail($id);
        
	
	/**
	 * 
	 * @param type $EmpTrainingInterface
	 * 
	 * to save trainings
	 */
	
	public function save(TrainingDetails $EmpTrainingInterface, $category, $type);
	
	/**
	 * 
	 * @param type $EmpTrainingInterface
	 * 
	 * to save trainings
	 */
	
	public function saveShortTermTraining(WorkshopDetails $EmpTrainingInterface);
	
	/*
	 * Save Training Nominations
	 */
	
	public function saveTrainingNomination(TrainingNomination $trainingObject);
	
	/*
	 * Save Long Term application form submitted by staff for training
	 */
	 
	public function saveLongTermApplication(LongTermApplication $trainingObject);
	 
	 /*
	 * Save Short Term application form submitted by staff for training
	 */
	 
	public function saveShortTermApplication(ShortTermApplication $trainingObject);
        
        /*
	 * Save Short Term application form submitted by HR Officer
	 */
	 
	public function updateShortTermApplication(ShortTermApplication $trainingObject);
	
	/*
	* Save Training Report
	*/
	 
	public function saveTrainingReport(TrainingReport $trainingObject);
        
         /*
	 * Save Long Term Training/Study Report
	 */
	 
	public function saveStudyReport(StudyReport $trainingObject);
         
         /*
	 * Save Study Extension Request
	 */
	 
	public function saveStudyExtensionRequest(StudyExtension $trainingObject);
		
	/*
	* List Employees to be nominated
	*/
	
	public function getEmployeeList($empName, $empId, $department, $organisation_id);
	
	/*
	 * Get the list of trainings that an employee is nominated for
	 * should only see training list that he/she has been nominated for
	 */
	 
	public function getNominatedTrainingList($tableName, $employee_details_id);
	
	/*
	 * Get the list of nominations for a training or workshop
	 * takes the training id as its argument
	 */
	 
	public function getTrainingNominations($id, $training_type);
	
	/*
	 * Get the list of employees that have gone for training
	 */
	 
	public function getTrainingList($table_name, $organisation_id);
	
	/*
	 * Get the details for the training for a given ID
	 * Used when displaying the documents submitted for training
	 */
	 
	public function getTrainingDetails($id, $training_type);
	
	/*
	* Get the details for the training report for a given ID
	*/
	 
	public function getTrainingReportDetails($id, $training_type);
	
	/*
	 * Cross Check whether Employee has already applied
	 */
	 
	public function crossCheckTrainingApplication($employee_id, $training_id, $training_type);
	
	/*
	 * Get the location of the file name 
	 */
	 
	public function getFileName($training_id, $column_name, $training_type);
	
	/**
	 * 
	 * @return array/ EmpTraining[]
	 */
	 
	public function listSelectData($tableName, $columnName);
	
}
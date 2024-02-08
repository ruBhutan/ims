<?php

namespace Appraisal\Service;

use Appraisal\Model\Appraisal;
use Appraisal\Model\AcademicAppraisal;
use Appraisal\Model\AcademicWeight;
use Appraisal\Model\IwpObjectives;
use Appraisal\Model\NatureActivity;

//need to add more models

interface AppraisalServiceInterface
{
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
	
	/*
	* Take username and return the occupational group of the user
	*/
	
	public function getOccupationalGroup($username);
	
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|AppraisalInterface[]
	*/
	
	public function listAll($tableName);
	
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|AppraisalInterface[]
	*/
	
	public function listEmployeeAppraisal($tableName, $employee_id, $status);
        
	/*
	 * Get the list of Success Indicators of the Supervisor for staff
	 */
	
	public function getSupervisorSuccessIndicators($employee_id);
	
	/*
	 * To get the list of Appraisal for administrative staff
	 */
	
	public function listAdministrativeAppraisal($tableName, $employee_id, $status);
	
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|AppraisalInterface[]
	*/
	
	public function listActivityDetail($tableName, $columnName, $activity_id);
	
	/*
	* Save Academic Appraisal
	*/
		 
	public function saveAcademicAppraisal(AcademicAppraisal $appraisalObject);
	
	/*
	* Save Administrative Appraisal
	*/
	 
	public function saveAdministrativeAppraisal(IwpObjectives $appraisalObject);
	
	/*
	* Get the deadline for the IWP
	*/
	
	public function getIwpDeadline($iwp_type);

	public function getAppraisalPeriodYear($iwp_type, $tableName);
	
	/*
	* Save Administrative/Academic Reviews from Supervisor
	*/
	
	public function saveReview($data, $type);
	
	/*
	* Save Nature of Activity
	*/
	
	public function saveNatureOfActivity(NatureActivity $activityModel);
	
	/*
	* Save Academic Weight
	*/
	
	public function saveAcademicWeight(AcademicWeight $academicModel);
	 
	/*
	* Get the details of the employee details
	*/
	
	public function getEmployeeDetails($id);
	
	/*
	* Generic function to get the details given an id and table name
	*/
	
	public function getDetail($tableName, $id);

	public function deleteAppraisal($id);
	
	 /*
	 * Get the appraisal list
	 */
        
	public function getAppraisalList($type, $employee_details_id, $role, $organisation_id);
	
	/*
	* Get the list of nominations for supervisor approval
	*/
	
	public function getNominationList($table_name, $employee_id);
	
	/*
	* Update the status of the nomination by the supervisor
	*/
	
	public function updateNominationStatus($data, $employee_id);
        
        /*
         * To submit the IWP Activities to Supervisor
         */
        
        public function submitIWPActivities($employee_id, $table_name);
	 
	 /**
	 * Should return a set of all objectives that we can iterate over. 
	 * 
	 * The purpose of the function is the objectives for the dropdown select list
	 *
	 * @return array|AppraisalInterface[]
	*/
	
	public function listSelectData($tableName, $columnName, $organisation_id);
		
		
}
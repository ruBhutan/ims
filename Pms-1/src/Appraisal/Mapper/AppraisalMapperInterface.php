<?php

namespace Appraisal\Mapper;

use Appraisal\Model\Appraisal;
use Appraisal\Model\AcademicAppraisal;
use Appraisal\Model\AcademicWeight;
use Appraisal\Model\IwpObjectives;
use Appraisal\Model\NatureActivity;

interface AppraisalMapperInterface
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
	 * 
	 * @return array/ Appraisal[]
	 */
	 
	public function findAll($tableName);
	
	/**
	 * 
	 * @return array/ Appraisal[]
	 */
	 
	public function findEmployeeAppraisal($tableName, $employee_id, $status);
        
	/*
	 * Get the list of Success Indicators of the Supervisor for staff
	 */
	
	public function getSupervisorSuccessIndicators($employee_id);
	
	public function listAdministrativeAppraisal($tableName, $employee_id, $status);
	
	/**
	 * @param AppraisalInterface $appraisalObject
	 *
	 * @param AppraisalInterface $appraisalObject
	 * @return AppraisalInterface
	 * @throws \Exception
	 */
	 
	public function saveAcademicAppraisal(AcademicAppraisal $appraisalObject);
	 
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
	 * 
	 * @return array/ Appraisal[]
	 */
	 
	public function findActivityDetail($tableName, $columnName, $activity_id);
        
	/**
	 * 
	 * @return array/ Appraisal[]
	 */
	 
	public function listSelectData($tableName, $columnName, $organisation_id);
	
}
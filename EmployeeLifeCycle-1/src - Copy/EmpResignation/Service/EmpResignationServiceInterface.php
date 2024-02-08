<?php

namespace EmpResignation\Service;

use EmpResignation\Model\EmpResignation;
use EmpResignation\Model\Dues;
use EmpResignation\Model\Separation;
use EmpResignation\Model\SeparationRecord;

//need to add more models

interface EmpResignationServiceInterface
{
	/**
	* @param int/String $id
	* @return array Employee Details
	* @throws \InvalidArgumentException
	*/
	
	public function getEmployeeDetailsId($emp_id);
	
	/*
	 * Get the Organisation Id
	 */
	 
	public function getOrganisationId($username);
	
	/*
	* take username and returns Name and any other detail required
	*/
	
	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);
	
	/*
	* Get details of resigning employee
	*/
	
	public function getResigningEmployee($organisation_id);


	public function getEmployeeList($empName, $empId, $department, $organisation_id);


	public function listAllEmployees($organisation_id);

	public function getResignedEmpDetails($id);
	
	/*
	* Get the status of the dues clearance of the resiging employee
	*/
	
	public function getDueClearance($organisation_id);
	
	/*
	* get the list of goods issued to an employee
	* $id is the id of the resignation details
	*/
	
	public function getEmpGoods($id, $categoryType);
	
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|EmpResignationInterface[]
	*/
	
	public function listAll($userrole, $tableName, $organisation_id, $status);

	/**
	 * Should return a single proposal
	 *
	 * @param int $id Identifier of the Proposal that should be returned
	 * @return EmpResignationInterface
	 */
        
     public function getResignationDetails($id);

     public function getResignationApplicationDetails($id);
	 
	 /*
	 * Get the details of the employee resigning to edit, delete etc.
	 */
	 
	 public function getEmployeeResigningDetails($employee_id);

	 public function getSeparationRecordFile($id);

	 public function getSeparationRecordDetails($tableName, $id);

	 public function crossCheckResignationApplication($employee_details_id, $status);
	 
	 /**
	 * @param EmpResignationInterface $resignationObject
	 *
	 * @param EmpResignationInterface $resignationObject
	 * @return EmpResignationInterface
	 * @throws \Exception
	 */
	 
	 public function save(EmpResignation $resignationObject);

	 public function deleteEmployeeResignation($id);
	 
	 /*
	 * Save Separation Record of Resigning Employee
	 */
	 
	 public function saveSeparationRecord(SeparationRecord $resignationObject);
	 
	 /*
	 * Save the Dues Clearance Record
	 */
	 
	 public function saveDueClearance(Dues $resignationModel);
	 
	 /*
	 * Get the Authorizing Role for various "no due certificate"
	 */
	 
	 public function getAuthorisingRole($type, $organisation_id);

	 public function getSeparationRecordList($tableName, $organisation_id);
	 
	 /*
	 * Get the notification details, i.e. submission to and submission to department
	 */
	
	 public function getNotificationDetails($organisation_id);
	 
	 /*
	 * Approve/Reject Resignation
	 */
	 
	 public function updateResignationStatus($id, $status);
	 
	 
	 /**
	 * Should return a set of all objectives that we can iterate over. 
	 * 
	 * The purpose of the function is the objectives for the dropdown select list
	 *
	 * @return array|EmpResignationInterface[]
	*/
	
	public function listSelectData($tableName, $columnName);
		
		
}
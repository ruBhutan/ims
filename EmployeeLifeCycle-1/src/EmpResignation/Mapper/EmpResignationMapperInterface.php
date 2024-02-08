<?php

namespace EmpResignation\Mapper;

use EmpResignation\Model\EmpResignation;
use EmpResignation\Model\Dues;
use EmpResignation\Model\Separation;
use EmpResignation\Model\SeparationRecord;

interface EmpResignationMapperInterface
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
	
	
	public function getResigningEmployee($organisation_id);

	public function getEmployeeList($empName, $empId, $department, $organisation_id);

	public function listAllEmployees($organisation_id);

	public function getResignedEmpDetails($id);

	public function getResignationType($resignationType);

	public function getSupervisorEmailId($userrole, $departments_units_id);

	public function getRecordedResignedEmpDetails($employee_details_id, $organisation_id);

	public function getIssuingAuthorityEmails($staff_role, $organisation_id, $departments_units);
	
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
	 * 
	 * @return array/ EmpResignation[]
	 */
	 
	public function findAll($userrole, $tableName, $organisation_id, $status);
        
	/**
	 * 
	 * @param type $id
	 * 
	 * to find details related to the Resigning Employee
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
	 * 
	 * @param type $EmpResignationInterface
	 * 
	 * to save budgetings
	 */
	
	public function save(EmpResignation $EmpResignationInterface);

	public function deleteEmployeeResignation($id);
	
	/*
	 * Save Separation Record of Resigning Employee
	*/
	 
	public function saveSeparationRecord(SeparationRecord $resignationInterface);
	 
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
	 * 
	 * @return array/ EmpResignation[]
	 */
	 
	public function listSelectData($tableName, $columnName);
	
}
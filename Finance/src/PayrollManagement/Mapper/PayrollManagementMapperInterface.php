<?php

namespace PayrollManagement\Mapper;

use PayrollManagement\Model\PayrollManagement;

interface PayrollManagementMapperInterface
{

	/**
	 * 
	 * @return array/ PayrollManagement[]
	 */
	 
	public function findAll($tableName);
	
	/*
	* Find the Staff Payroll Details
	*/
	
	public function findPayrollDetail($id);
	
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username);
	
	/*
	* Get organisation id based on the username
	*/
	
	public function getOrganisationId($username);
	
	/*
	* Get list of employees given a department/unit
	*/
	
	public function getEmployeeList($department_name, $department_unit);
        	
	/**
	 * 
	 * @param type $PayrollManagementInterface
	 * 
	 * to save academics
	 */
	
	public function savePayrollManagement(PayrollManagement $PayrollManagementInterface);
	
	/**
	 * 
	 * @return array/ PayrollManagement[]
	 */
	 
	public function listSelectData($tableName, $columnName, $condition);
	
}
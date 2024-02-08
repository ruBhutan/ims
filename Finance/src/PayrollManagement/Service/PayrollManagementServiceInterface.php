<?php

namespace PayrollManagement\Service;

use PayrollManagement\Model\FinancialInstitution;

interface PayrollManagementServiceInterface
{
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|PayrollManagementInterface[]
	*/
	
	public function listAll($tableName);
	
	/*
	* Find the Proposal Details
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
	 * @param PayrollManagementInterface $budgetingObject
	 *
	 * @param PayrollManagementInterface $budgetingObject
	 * @return PayrollManagementInterface
	 * @throws \Exception
	 */
	 
	 public function savePayrollManagement(PayrollManagement $payrollObject);
	 
	 
	 /**
	 * Should return a set of all objectives that we can iterate over. 
	 * 
	 * The purpose of the function is the objectives for the dropdown select list
	 *
	 * @return array|PayrollManagementInterface[]
	*/
	
	public function listSelectData($tableName, $columnName, $condition);
		
		
}
<?php

namespace PayrollManagement\Service;

use PayrollManagement\Mapper\PayrollManagementMapperInterface;
use PayrollManagement\Model\FinancialInstitution;

class PayrollManagementService implements PayrollManagementServiceInterface
{
	/**
	 * @var \Blog\Mapper\PayrollManagementMapperInterface
	*/
	
	protected $payrollMapper;
	
	public function __construct(PayrollManagementMapperInterface $payrollMapper) {
		$this->payrollMapper = $payrollMapper;
	}
	
	public function listAll($tableName)
	{
		return $this->payrollMapper->findAll($tableName);
	}
	
	public function findPayrollDetail($id)
	{
		return $this->payrollMapper->findPayrollDetail($id);
	}
	
	public function getUserDetailsId($username)
	{
		return $this->payrollMapper->getUserDetailsId($username);
	}
	
	public function getOrganisationId($username)
	{
		return $this->payrollMapper->getOrganisationId($username);
	}
		
	public function getEmployeeList($department_name, $department_unit)
	{
		return $this->payrollMapper->getEmployeeList($department_name, $department_unit);
	}
		
	public function savePayrollManagement(PayrollManagement $payrollObject) 
	{
		return $this->payrollMapper->savePayrollManagement($payrollObject);
	}
	
	public function listSelectData($tableName, $columnName, $condition)
	{
		return $this->payrollMapper->listSelectData($tableName, $columnName, $condition);
	}
	
}
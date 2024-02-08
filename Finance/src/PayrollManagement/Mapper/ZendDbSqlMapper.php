<?php

namespace PayrollManagement\Mapper;

use PayrollManagement\Model\PayrollManagement;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements PayrollManagementMapperInterface
{
	/**
	* @var \Zend\Db\Adapter\AdapterInterface
	*
	*/
	
	protected $dbAdapter;
	
	/*
	 * @var \Zend\Stdlib\Hydrator\HydratorInterface
	*/
	protected $hydrator;
	
	/*
	 * @var \PayrollManagement\Model\PayrollManagementInterface
	*/
	protected $payrollPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			PayrollManagement $payrollPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->payrollPrototype = $payrollPrototype;
	}
	
	
	/**
	* @return array/PayrollManagement()
	*/
	public function findAll($tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName)); // join expression

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		 return $resultSet->initialize($result);
	}
	
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'));
		$select->where(array('emp_id' =>$username));
		$select->columns(array('id'));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get organisation id based on the username
	*/
	
	public function getOrganisationId($username)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'));
		$select->where(array('emp_id' =>$username));
		$select->columns(array('organisation_id'));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Find the Payroll Details
	* This will take Table Name and id as the argument
	*/
	
	public function findPayrollDetail($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'academic_payroll'));
		$select->where(array('id' =>$id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get list of employees given a department/unit
	*/
	
	public function getEmployeeList($department_name, $department_unit)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details'))
					->columns(array('id','first_name','middle_name','last_name','emp_id'))
					->join(array('t2' => 'emp_position_title'), 
                            't1.id = t2.employee_details_id', array('position_title_id'))
                    ->join(array('t3'=>'position_title'),
                            't2.position_title_id = t3.id', array('position_title'))
					->join(array('t4' => 'emp_position_level'), 
                            't1.id = t4.employee_details_id', array('position_level_id'))
                    ->join(array('t5'=>'position_level'),
                            't4.position_level_id = t5.id', array('position_level'))
					->join(array('t6'=>'pay_scale'),
                            't5.position_level = t6.position_level', array('maximum_pay_scale'))
					//->join(array('t7'=>'teaching_allowance'),
                     //       't5.position_level = t7.position_level', array('teaching_allowance'))
					->join(array('t8'=>'housing_allowance'),
                            't5.position_level = t8.position_level', array('rent_allowance'))
                    ->where(array('t1.departments_id' =>$department_name));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
        
	/**
	 * 
	 * @param type $PayrollManagementInterface
	 * 
	 * to save Calendar Details
	 */
	
	public function savePayrollManagement(PayrollManagement $payrollObject)
	{
		$payrollData = $this->hydrator->extract($payrollObject);
		unset($payrollData['id']);

		if($payrollObject->getId()) {
			//ID present, so it is an update
			$action = new Update('academic_payroll');
			$action->set($payrollData);
			$action->where(array('id = ?' => $payrollObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('academic_payroll');
			$action->values($payrollData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $payrollObject->setId($newId);
			}
			return $payrollObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/**
	* @return array/PayrollManagement()
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName, $condition)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array('id',$columnName)); 
		if($condition != NULL)
		{
			$select->where(array('organisation_id = ?' => $condition));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['id']] = $set[$columnName];
		}
		
		return $selectData;
			
	}
        
}
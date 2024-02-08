<?php

namespace FinanceCodes\Mapper;

use FinanceCodes\Model\ChartAccounts;
use FinanceCodes\Model\AccountsGroupHead;
use FinanceCodes\Model\BroadHeadName;
use FinanceCodes\Model\ObjectCode;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements FinanceCodesMapperInterface
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
	 * @var \FinanceCodes\Model\FinanceCodesInterface
	*/
	protected $codesPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			\stdClass $codesPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->codesPrototype = $codesPrototype;
	}
	
	/*
	* return an array of object code
	*/
	
	public function findFinanceCode($tableName, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select($tableName);
		$select->where(array('id = ? ' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/**
	* @return array/FinanceCodes()
	*/
	public function findAll($tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName)); // join expression
		$select->limit(15);

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

	}
        	
	public function saveBroadHeadName(BroadHeadName $codesObject)
	{
		$codesData = $this->hydrator->extract($codesObject);
		unset($codesData['id']);
		
		if($codesObject->getId()) {
			//ID present, so it is an update
			$action = new Update('broad_head_name');
			$action->set($codesData);
			$action->where(array('id = ?' => $codesObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('broad_head_name');
			$action->values($codesData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $codesObject->setId($newId);
			}
			return $codesObject;
		}
		
		throw new \Exception("Database Error");
	}
	 
	 /*
	 * Save Object Code
	 */
	 
	public function saveObjectCode(ObjectCode $codesObject)
	{
		$codesData = $this->hydrator->extract($codesObject);
		unset($codesData['id']);
		
		if($codesObject->getId()) {
			//ID present, so it is an update
			$action = new Update('object_code');
			$action->set($codesData);
			$action->where(array('id = ?' => $codesObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('object_code');
			$action->values($codesData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $codesObject->setId($newId);
			}
			return $codesObject;
		}
		
		throw new \Exception("Database Error");
	}
	 
	 /*
	 * Save Chart of Accounts
	 */
	 
	public function saveChartAccounts(ChartAccounts $codesObject)
	{
		$codesData = $this->hydrator->extract($codesObject);
		unset($codesData['id']);
		
		if($codesObject->getId()) {
			//ID present, so it is an update
			$action = new Update('chart_of_accounts');
			$action->set($codesData);
			$action->where(array('id = ?' => $codesObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('chart_of_accounts');
			$action->values($codesData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $codesObject->setId($newId);
			}
			return $codesObject;
		}
		
		throw new \Exception("Database Error");
	}
	 
	 /*
	 * Save Accounts Group Head
	 */
	 
	public function saveAccountsGroupHead(AccountsGroupHead $codesObject)
	{
		$codesData = $this->hydrator->extract($codesObject);
		unset($codesData['id']);
		
		if($codesObject->getId()) {
			//ID present, so it is an update
			$action = new Update('accounts_group_head');
			$action->set($codesData);
			$action->where(array('id = ?' => $codesObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('accounts_group_head');
			$action->values($codesData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $codesObject->setId($newId);
			}
			return $codesObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/**
	* @return array/FinanceCodes()
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array('id',$columnName)); 

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
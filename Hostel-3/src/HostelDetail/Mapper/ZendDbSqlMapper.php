<?php

namespace Responsibilities\Mapper;

use Responsibilities\Model\Responsibilities;
use Responsibilities\Model\ResponsibilityCategory;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements ResponsibilitiesMapperInterface
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
	 * @var \Responsibilities\Model\ResponsibilitiesInterface
	*/
	protected $responsibilityPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			Responsibilities $responsibilityPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->responsibilityPrototype = $responsibilityPrototype;
	}
	
	/**
	* @param int/String $id
	* @return Responsibilities
	* @throws \InvalidArgumentException
	*/
	
	public function findStudent($id)
	{
        $sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student')); 
		$select->where(array('id = ?' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

	}
	
	/**
	* @return array/Responsibilities()
	*/
	public function findAll($tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

	}
        
	/**
	 * 
	 * @param type $id
	 * 
	 * to find the Objectives for a given $id
	 */
	 
	public function findObjectives($id) 
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'rub_objectives'));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if ($result instanceof ResultInterface && $result->isQueryResult()) {

				$resultSet = new ResultSet();
				$resultSet->initialize($result);

				$resultSet = new HydratingResultSet($this->hydrator, $this->responsibilityPrototype);
				$resultSet->buffer();
				return $resultSet->initialize($result); 
		}
		return array();
	}
		
	/**
	 * 
	 * @param type $ResponsibilitiesInterface
	 * 
	 * to save Vision and Mission Details
	 */
	
	public function saveResponsibility(ResponsibilityCategory $responsibilityObject)
	{
		$responsibilityData = $this->hydrator->extract($responsibilityObject);
		unset($responsibilityData['id']);
		
		if($responsibilityObject->getId()) {
			//ID present, so it is an update
			$action = new Update('responsibility_category');
			$action->set($responsibilityData);
			$action->where(array('id = ?' => $responsibilityObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('responsibility_category');
			$action->values($responsibilityData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $responsibilityObject->setId($newId);
			}
			return $responsibilityObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	
	/**
	 * 
	 * @param type $ResponsibilitiesInterface
	 * 
	 * to save Objectives Details
	 */
	
	public function saveObjectives(Objectives $responsibilityObject)
	{
		$responsibilityData = $this->hydrator->extract($responsibilityObject);
		unset($responsibilityData['id']);
		
		if($responsibilityObject->getId()) {
			//ID present, so it is an update
			$action = new Update('rub_objectives');
			$action->set($responsibilityData);
			$action->where(array('id = ?' => $responsibilityObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('rub_objectives');
			$action->values($responsibilityData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $responsibilityObject->setId($newId);
			}
			return $responsibilityObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	
	/**
	 * 
	 * @param type $ResponsibilitiesInterface
	 * 
	 * to save Activities Details
	 */
	
	public function saveActivities(AwpaObjectives $responsibilityObject)
	{
		$responsibilityData = $this->hydrator->extract($responsibilityObject);
		unset($responsibilityData['id']);
		
		if($responsibilityObject->getId()) {
			//ID present, so it is an update
			$action = new Update('awpa_objectives_activity');
			$action->set($responsibilityData);
			$action->where(array('id = ?' => $responsibilityObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('awpa_objectives_activity');
			$action->values($responsibilityData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $responsibilityObject->setId($newId);
			}
			return $responsibilityObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/**
	 * 
	 * @param type $ResponsibilitiesInterface
	 * 
	 * to save Activities Details
	 */
	
	public function saveKpi(AwpaActivities $responsibilityObject)
	{
		$responsibilityData = $this->hydrator->extract($responsibilityObject);
		unset($responsibilityData['id']);
		
		if($responsibilityObject->getId()) {
			//ID present, so it is an update
			$action = new Update('awpa_activities');
			$action->set($responsibilityData);
			$action->where(array('id = ?' => $responsibilityObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('awpa_activities');
			$action->values($responsibilityData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $responsibilityObject->setId($newId);
			}
			return $responsibilityObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/**
	* @return array/Responsibilities()
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
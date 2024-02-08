<?php

namespace Nominations\Mapper;

use Nominations\Model\Nominations;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements NominationsMapperInterface
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
	 * @var \Nominations\Model\NominationsInterface
	*/
	protected $nominationPrototype;
	
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			Nominations $nominationPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->nominationPrototype = $nominationPrototype;
	}
	
	/*
	* Getting the id for username
	*/
	
	public function getEmployeeDetailsId($emp_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'));
		
		$select->where(array('emp_id' =>$emp_id));
		$select->columns(array('id'));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* take username and returns Name and any other detail required
	*/
	
	public function getUserDetails($username, $usertype)
	{
		$name = NULL;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($usertype == 1){
			$select->from(array('t1' => 'employee_details'));
			$select->where(array('t1.emp_id' => $username));
			$select->columns(array('first_name', 'middle_name', 'last_name'));
		}

		else if($usertype == 2){
			$select->from(array('t1' => 'student'));
			$select->where(array('student_id' => $username));
			$select->columns(array('first_name', 'middle_name', 'last_name'));
		}		
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$name = $set['first_name']." ".$set['middle_name']." ".$set['last_name'];
		}
		
		return $name;
	}

	public function getUserImage($username, $usertype)
	{
		$img_location = NULL;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($usertype == 1){
			$select->from(array('t1' => 'employee_details'));
			$select->where(array('t1.emp_id' => $username));
			$select->columns(array('profile_picture', 'first_name', 'middle_name', 'last_name'));
		}

		if($usertype == 2){
			$select->from(array('t1' => 'student'));
			$select->where(array('t1.student_id' => $username));
			$select->columns(array('profile_picture', 'first_name', 'middle_name', 'last_name'));
		}		
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$img_location = $set['profile_picture'];
		}
		
		return $img_location;
	}

	/**
	* @return array/Nominations()
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

				$resultSet = new HydratingResultSet($this->hydrator, $this->nominationPrototype);
				$resultSet->buffer();
				return $resultSet->initialize($result); 
		}
		return array();
	}
			
	/**
	 * 
	 * @param type $NominationsInterface
	 * 
	 * to save Activities Details
	 */
	
	public function saveNominations(Nominations $nominationObject)
	{
		$nominationData = $this->hydrator->extract($nominationObject);
		unset($nominationData['id']);
		
		//preset Data for nominations
		$nominationData['appraisal_Period'] = date('Y');
			
		//need to set the table name depending on the nomination type
		// and then unset the nomination type from the data
		$nominationType = $nominationData['nomination_Type'];
		if($nominationType == 'Peer'){
			$tableName = 'peer_nomination';
		} elseif($nominationType == 'Beneficiary'){
			$tableName = 'beneficiary_nomination';
		} else{
			$tableName = 'subordinate_nomination';
		}
		unset($nominationData['nomination_Type']);
		
		if($nominationObject->getId()) {
			//ID present, so it is an update
			$action = new Update($tableName);
			$action->set($nominationData);
			$action->where(array('id = ?' => $nominationObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert($tableName);
			$action->values($nominationData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $nominationObject->setId($newId);
			}
			return $nominationObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Get List of Employees that Nominations
	*/
	
	public function getNominationList($tableName, $employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->where(array('employee_details_id' =>$employee_details_id));
		$select->where(array('appraisal_period' =>date('Y')));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
	}
	
	/*
	* Get Employee Details of those Nominated Employees
	*/
	
	public function getNominatedEmployee($employee_details_id)
	{
		//the employee array stores that array data of the nominees
		$employeeArray = array();
		$tableNames = array(1 => 'peer_nomination', 2 => 'subordinate_nomination', 3 => 'beneficiary_nomination');
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		for($i = 1; $i<=3; $i++){
			$select->from(array('t1' => $tableNames[$i]));
			$select->where(array('employee_details_id' =>$employee_details_id));
			$select->columns(array('nominee'));
	
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			
			foreach($resultSet as $tempData){
				array_push($employeeArray,$tempData['nominee']);
			}
		}
		
		if(empty($employeeArray)){
			return $employeeArray;
		}
		
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details'));
		$select->where(array('id' =>$employeeArray));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
		
	}
	
	/*
	* Get the deadline for the IWP
	*/
	
	public function getIwpDeadline()
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'pms_activation_dates'))
							->columns(array('end_date'));
		$select->where(array('pms_year' =>date('Y')));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
	}
	
	/*
	* Delete Nomination
	* Gets the table name and $id
	*/
	
	public function deleteNomination($table_name, $id)
	{
		$action = new Delete($table_name);
		$action->where(array('id = ?' => $id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		return (bool)$result->getAffectedRows();
	}
	
	/*
	* List Employees to add to nominations
	*/
	
	public function getEmployeeList($id, $empName, $position_title, $organisation_id)
	{
        $employee_list = array();
                
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'))
                            ->join(array('t2' => 'emp_position_title'), 
                                    't1.id = t2.employee_details_id', array('position_title_id'))
                            ->join(array('t3'=>'position_title'),
                                    't2.position_title_id = t3.id', array('position_title'))
                            ->join(array('t4'=>'organisation'),
                                    't1.organisation_id = t4.id', array('organisation_name'));
		
		$select->order('t2.date ASC');
        if($id){
			$select->where(array('t1.id' =>$id));
			$select->columns(array('id','first_name','middle_name','last_name'));
		}
		if($empName){
			$select->where->like('t1.first_name','%'.$empName.'%');
		}
		if($position_title){
			$select->where->like('t3.position_title','%'.$position_title.'%');
		}
		if($organisation_id){
			$select->where(array('t1.organisation_id' =>$organisation_id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
         foreach($resultSet as $set){
			$employee_list[$set['id']] = $set;
		}
		
		return $employee_list;

	}
	
	/**
	* @return array/Nominations()
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
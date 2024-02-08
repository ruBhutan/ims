<?php

namespace InventoryReports\Mapper;

//use InventoryReports\Model\InventoryReports;
//use InventoryReports\Model\InventoryReportsCategory;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\where;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Having;
use Zend\Db\Sql\Group;
use Zend\Db\Sql\Expression;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements InventoryReportsMapperInterface
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
	 * @var \EmployeeTask\Model\EmployeeTaskInterface
	*/
	protected $employeetaskPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			\stdClass $employeetaskPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		//$this->planningreportsPrototype = $planningreportsPrototype;
	}
	
	
	/*
	 * Get the Organisation Id
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
	* @return array/Reports()
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($organisation_id != NULL){
			if($tableName == 'organisation'){
				if($organisation_id == '1'){
					$select->from(array('t1' => $tableName));
					$select->columns(array('id', $columnName));
				}else{
					$select->from(array('t1' => $tableName));
					$select->columns(array('id', $columnName));
					$select->where(array('t1.id' => $organisation_id));
				}
			}else{
				$select->from(array('t1' => $tableName));
				$select->columns(array('id',$columnName,'from_date'));
			}
		}else{
			$select->from(array('t1' => $tableName));
			$select->columns(array('id',$columnName));
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

	public function getInventoryReports($report_details){
		$student_reports = array();
		$report_name = $report_details['report_name'];
		$organisation = $report_details['organisation'];
		switch($report_name){
			case "stock_summary":
			$year = $report_details['year'];
			$inventory_reports = $this->getStockSummary($year, $organisation);
			break;
			//Sample
			case "student_intake_report_college":
			$year = $report_details['year'];
			$student_reports = $this->getStudentByCollege($year, $organisation);
			break;
		}
		return $inventory_reports;

	}

	public function getStockSummary($year, $organisation)
	{ 
		$inventory_data = array();
		$inventory_reports = array();
                
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'goods_received'))
						->join(array('t2'=>'item_name'),
							't1.item_name_id = t2.id',array('item_name'))
						->join(array('t3'=>'item_sub_category'),
							't2.item_sub_category_id = t3.id',array('sub_category_type','sub_category_code'))
						->join(array('t4'=>'item_category'),
							't3.item_category_id = t4.id',array('category_type','category_code'))
						->join(array('t5' => 'item_major_class'),
							't5.id = t4.major_class_id', array('major_class'))
						->join(array('t6' => 'item_quantity_type'),
							't2.item_quantity_type_id = t6.id', array('item_quantity_type'));
		if($organisation != 0){
			$select->where(array('t3.organisation_id' =>$organisation));
		}
		$select->where(array('t5.id' => 2));
		$select->where(array('t1.item_in_stock > 0'));
		$select->order('t2.id ASC');
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$inventory_data[$set['category_type']][$set['sub_category_type']][$set['item_received_date']][$set['item_name']][$set['item_in_stock']][$set['item_purchasing_rate']][$set['id']] = $set['id'];
		} 
		foreach($inventory_data as $key => $value){ foreach($value as $key2 => $value2){
			foreach($value2 as $key3 => $value3){ foreach($value3 as $key4 => $value4){
				foreach($value4 as $key5 => $value5){ foreach($value5 as $key6 => $value6){ 
						$inventory_reports[$key][$key2][$key3][$key4][$key5][$key6] = count($inventory_data[$key][$key2][$key3][$key4][$key5][$key6]);
					}
					}}
				}			
			}
		} 
		return $inventory_reports;
	}
	    
}

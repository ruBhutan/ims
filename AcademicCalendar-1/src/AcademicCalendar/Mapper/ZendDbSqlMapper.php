<?php

namespace AcademicCalendar\Mapper;

use AcademicCalendar\Model\AcademicCalendar;
use AcademicCalendar\Model\AcademicEvent;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements AcademicCalendarMapperInterface
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
	 * @var \AcademicCalendar\Model\AcademicCalendarInterface
	*/
	protected $academicPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			AcademicCalendar $academicPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->academicPrototype = $academicPrototype;
	}
	
	
	/**
	* @return array/AcademicCalendar()
	*/
	public function findAll($tableName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		if($tableName == 'academic_calendar'){ 
			$select->from(array('t1' => $tableName))
					->columns(array('id', 'from_date', 'to_date', 'academic_year', 'event_for','remarks'))
						->join(array('t2' => 'academic_calendar_events'), 
							't1.academic_event = t2.id', array('academic_event'));
			$select->where(array('t2.organisation_id' =>$organisation_id));
			$select->where(array('t2.type' =>'NonEditable'));
			$select->order('academic_year DESC');
			$select->order('academic_event ASC');
			//$select->order('from_date ASC');
		}
		else {
			$select->from(array('t1' => $tableName));
			$select->where(array('t1.organisation_id' =>$organisation_id));
		}
		

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


	public function getUserDetails($username, $tableName)
	{
		$name = NULL;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName = 'employee_details'){
			$select->from(array('t1' => $tableName));
			$select->where(array('emp_id' =>$username));
			$select->columns(array('first_name', 'middle_name', 'last_name'));
		}

		else if($tableName = 'student'){
			$select->from(array('t1' => $tableName));
			$select->where(array('student_id' =>$username));
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
        
	/**
	 * 
	 * @param type $AcademicCalendarInterface
	 * 
	 * to save Calendar Details
	 */
	
	public function saveAcademicCalendar(AcademicCalendar $academicObject)
	{
		$academicData = $this->hydrator->extract($academicObject);
		unset($academicData['id']);
		//unset($academicData['id']);
		
		$academicData['from_Date'] = date("Y-m-d", strtotime(substr($academicData['date_Range'] ,0,10)));
		$academicData['to_Date']  = date("Y-m-d", strtotime(substr($academicData['date_Range'] ,13,10)));
		
		unset($academicData['date_Range']);

		if($academicObject->getId()) {
			//ID present, so it is an update
			$action = new Update('academic_calendar');
			$action->set($academicData);
			$action->where(array('id = ?' => $academicObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('academic_calendar');
			$action->values($academicData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $academicObject->setId($newId);
			}
			return $academicObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Save Academic Calendar Event
	*/
	
	public function saveAcademicEvent(AcademicEvent $eventObject)
	{
		$eventData = $this->hydrator->extract($eventObject);
		unset($eventData['id']);

		if($eventObject->getId()) {
			//ID present, so it is an update
			$action = new Update('academic_calendar_events');
			$action->set($eventData);
			$action->where(array('id = ?' => $eventObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('academic_calendar_events');
			$action->values($eventData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $eventObject->setId($newId);
			}
			return $eventObject;
		}
		
		throw new \Exception("Database Error");
	}
	 
	 /*
	* Find the Calendar Details
	*/
	
	public function findCalendarDetail($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'academic_calendar'))
			   ->join(array('t2' => 'academic_calendar_events'),
					't2.id = t1.academic_event', array('academic_event_name' => 'academic_event'));
		$select->where(array('t1.id' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Find the Event Details
	*/
	
	public function findEventDetail($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'academic_calendar_events'));
		$select->where(array('id' =>$id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get the list of events given employee id
	*/
	
	public function getMyEvents($employee_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'academic_calendar'));
		$select->where(array('employee_details_id' =>$employee_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/**
	* @return array/AcademicCalendar()
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

	public function getSemester($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'academic_calendar'))
					->columns(array('academic_year'))
				->join(array('t2' => 'academic_calendar_events'), 
						't1.academic_event = t2.id', array('academic_event'));
		$select->where(array('from_date <= ? ' => date('Y-m-d')));
		$select->where(array('to_date >= ? ' => date('Y-m-d')));
		$select->where('t2.organisation_id = ' .$organisation_id);
                
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$semester = NULL;
		
		foreach($result as $set){
			if($set['academic_event'] == 'Autumn Semester Duration'){
				$semester['academic_event'] = 'Autumn';
                $semester['academic_year'] = $set['academic_year'];
			}
			else if($set['academic_event'] == 'Spring Semester Duration'){
				$semester['academic_event'] = 'Spring';
                $semester['academic_year'] = $set['academic_year'];
			}
		}
		//var_dump($semester); die();
		return $semester;
	}
        
}

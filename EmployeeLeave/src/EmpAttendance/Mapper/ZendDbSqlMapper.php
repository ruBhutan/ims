<?php

namespace EmpAttendance\Mapper;

use EmpAttendance\Model\EmpAttendance;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements EmpAttendanceMapperInterface
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
	 * @var \EmpAttendance\Model\EmpAttendanceInterface
	*/
	protected $attendancePrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			EmpAttendance $attendancePrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->attendancePrototype = $attendancePrototype;
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
	* @param int/String $id
	* @return EmpAttendance
	* @throws \InvalidArgumentException
	*/
	
	public function findFunction($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select('hr_development');
		$select->where(array('id = ? ' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();


		if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
				return $this->hydrator->hydrate($result->current(), $this->attendancePrototype);
		}

		throw new \InvalidArgumentException("EmpAttendance Proposal with given ID: ($id) not found");
	}
	
	/**
	* @return array/EmpAttendance()
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
	 * @param type $EmpAttendanceInterface
	 * 
	 * to save Vision and Mission Details
	 */
	
	public function save(EmpAttendance $attendanceObject)
	{
		$attendanceData = $this->hydrator->extract($attendanceObject);
		unset($attendanceData['id']);
		
		if($attendanceObject->getId()) {
			//ID present, so it is an update
			$action = new Update('emp_attendance_category');
			$action->set($attendanceData);
			$action->where(array('id = ?' => $attendanceObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('emp_attendance_category');
			$action->values($attendanceData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $attendanceObject->setId($newId);
			}
			return $attendanceObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	 * Save the Attendance Record
	 */
	 
	public function saveAttendanceRecord($unit_name, $from_date, $to_date, $data)
	{
		$attendanceDates['from_date'] = $from_date;
		$attendanceDates['to_date'] = $to_date;
		$attendanceDates['departments_units_id'] = $unit_name;

		$action = new Insert('emp_attendance_record_dates');
		$action->values($attendanceDates);
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($data != NULL){
			foreach($data as $attendance){
				$attendanceData = $attendance;
				$action2 = new Insert('emp_attendance');
				$action2->values($attendanceData);
				
				$sql2 = new Sql($this->dbAdapter);
				$stmt2 = $sql2->prepareStatementForSqlObject($action2);
				$result = $stmt2->execute();
			}
		}
		return; 
	}
	
	/*
	 * Get Employee Attendance Data
	 * This will look at the staff tour, leave (EOL, study etc)., absent etc.
	 */
	 
	public function getEmployeeAttendance($from_date, $to_date, $unit, $organisation_id)
	{
		//get the list of staff in the unit
		$staff_id = array();
		$staffName = $this->getStaffList($unit, $organisation_id);
		foreach($staffName as $staff){
			$staff_id[$staff['id']]= $staff['id'];
		}

		$attendanceData = array();
		$leaveData = array();
		$tourData = array();
		$index = 1;
		if(!empty($staff_id)){
			$leaveData = $this->getLeaveData($from_date, $to_date, $staff_id);
			$tourData = $this->getTourData($from_date, $to_date, $staff_id);
		}
				
		foreach($leaveData as $key=>$value){
			foreach($value as $key1 => $value1){
				$attendanceData[$index][$key1]= $value1;
			}
			$index++;
		}
		
		foreach($tourData as $key=>$value){
			foreach($value as $key1 => $value1){
				$attendanceData[$index][$key1]= $value1;
			}
			$index++;
		}
		return $attendanceData;
	}
	
	/*
	 * Get the list of staff for a given unit in an organisation
	 * Will be used for entering the attendance
	 */
	 
	public function getStaffList($unitName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'));
		$select->columns(array('id','first_name','middle_name','last_name'));
		$select->where(array('departments_units_id' =>$unitName));
		$select->where(array('organisation_id' =>$organisation_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();		
		$resultSet = new ResultSet();
		$resultSet->buffer();
		return $resultSet->initialize($result); 
	}
	
	/*
	* Get the leave data of the employees
	*/
	
	public function getLeaveData($from_date, $to_date, $staff_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_leave'));
		$select->columns(array('id','from_date','to_date','employee_details_id','emp_leave_category_id'));
		$select->where(array('employee_details_id' => $staff_id));
		$select->where(array('from_date >= ? ' => $from_date));
		$select->where(array('to_date <= ? ' => $to_date));

		$stmt = $sql->prepareStatementForSqlObject($select);		
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->buffer();
		$resultSet->initialize($result); 
		$leaveData = array();
		foreach($resultSet as $data){
			$leaveData[$data['employee_details_id']] = $data;
		}
		return $leaveData;
	}
	
	/*
	* Get tour data
	*/
	
	public function getTourData($from_date, $to_date, $staff_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'travel_authorization'))
				->columns(array('id','employee_details_id'))
				->join(array('t2' => 'travel_details'), 
                            't1.id = t2.travel_authorization_id', array('from_date','to_date'));
		$select->where(array('t1.employee_details_id' => $staff_id));
		$select->where(array('t2.from_date >= ? ' => $from_date));
		$select->where(array('t2.to_date <= ? ' => $to_date));

		$stmt = $sql->prepareStatementForSqlObject($select);		
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->buffer();
		$resultSet->initialize($result);
		$tourData = array();
		foreach($resultSet as $data){
			if(!array_key_exists($data['employee_details_id'], $tourData)){
				$tourData[$data['employee_details_id']] = $data;
			} else{
				foreach($data as $key=>$value){
					if($key != 'from_date'){
						$tourData[$data['employee_details_id']][$key] = $value;
					}
				}
			}
		}
		return $tourData;
	}
	
	/*
	 * check to see whether the attendance has been recorded or not
	 */
	 
	public function getAttendanceRecordDates($from_date, $to_date, $unitName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		//get the "from" month
		//The $to_date argument is no longer required. Was used in old function that did not work
		 $from_month = substr($from_date,5,2);
		 $from_year = substr($from_date, 0,4);
		//get number of dats in the selected month
		$days_in_month = cal_days_in_month(CAL_GREGORIAN,substr($from_date,5,2),date('Y'));
		$start_date = $from_year.'-'.substr($from_date,5,2).'-'.'01';
		$end_date = $from_year.'-'.substr($from_date,5,2).'-'.$days_in_month;

		$select->from(array('t1' => 'emp_attendance_record_dates'));
		$select->columns(array('from_date','to_date'));
		$select->where(array('departments_units_id' => $unitName));
		$select->where(array('from_date >= ? ' => $start_date));
		$select->where(array('to_date <= ? ' => $end_date));

		$stmt = $sql->prepareStatementForSqlObject($select);		
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
	}
	
	/*
	 * get list of absentees
	 */
	 
	public function getAbsenteeList($from_date, $to_date, $unitName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_attendance'))
				->join(array('t2' => 'employee_details'), 
									't2.id = t1.employee_details_id', array('departments_units_id'));
		$select->where(array('t2.departments_units_id' => $unitName));
		$select->where->between('t1.absent_date', $from_date, $to_date);

		$stmt = $sql->prepareStatementForSqlObject($select);		
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
	}
	
	/*
	 * Get the weekends
	*/
	 
	public function getWeekends($from_date, $to_date)
	{
		$weekends = array();
		$type = CAL_GREGORIAN;
		$month = substr($from_date,5,2); // Month ID, 1 through to 12.
		$year = substr($from_date,0,4); // Year in 4 digit 2009 format.
		$day_count = cal_days_in_month($type, $month, $year); // Get the amount of days
		
		//loop through all days
		for ($i = 1; $i <= $day_count; $i++) {
		
				$date = $year.'/'.$month.'/'.$i; //format date
				$get_name = date('l', strtotime($date)); //get week day
				$day_name = substr($get_name, 0, 3); // Trim day name to 3 chars
		
				//if not a weekend add day to array
				if($day_name == 'Sun' || $day_name == 'Sat'){
					$weekends[] = $i;
					
				}
		}
		return $weekends;
	}
		
	/**
	* @return array/EmpAttendance()
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'department_units'){
			$select->from(array('t1' => $tableName)) 
                    ->join(array('t2' => 'departments'), 
                            't1.departments_id = t2.id', array('organisation_id'))
                    ->join(array('t3'=>'organisation'),
                            't2.organisation_id = t3.id', array('organisation_name'))
                    ->where('t3.id = ' .$organisation_id);
		}
		else{
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
        
}
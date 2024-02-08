<?php

namespace Hostel\Mapper;

use Hostel\Model\Hostel;
use Hostel\Model\HostelAllocation;
use Hostel\Model\HostelApplication;
use Hostel\Model\HostelRoom;
use Hostel\Model\HostelInventory;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Predicate;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements HostelMapperInterface
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
	 * @var \Hostel\Model\HostelInterface
	*/
	protected $hostelPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			Hostel $hostelPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->hostelPrototype = $hostelPrototype;
	}
	
	/**
	* @param int/String $id
	* @return Hostel
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
	
	/*
	 * Get the Organisation Id
	 */
	 
	public function getOrganisationId($username, $tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		if($tableName == 'employee_details'){
			$select->where(array('emp_id' =>$username));
		} else {
			$select->where(array('student_id' =>$username));
		}
		$select->columns(array('organisation_id'));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	 
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username, $tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		if($tableName == 'employee_details'){
			$select->where(array('emp_id' =>$username));
		} else {
			$select->where(array('student_id' =>$username));
		}
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
	* @return array/Hostel()
	*/
	public function findAll($tableName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'hostels_list'){
			$select->from(array('t1' => $tableName))
				   ->join(array('t2' => 'employee_details'),
						't2.id = t1.provost_name', array('first_name', 'middle_name', 'last_name', 'emp_id'));
			$select->where(array('t1.organisation_id' =>$organisation_id));
		}
		else{
			$select->from(array('t1' => $tableName));
			$select->where(array('organisation_id' =>$organisation_id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

	}
        
	/**
	 * 
	 * @param type $HostelInterface
	 * 
	 * to save Vision and Mission Details
	 */
	
	public function saveHostel(Hostel $hostelObject)
	{
		$hostelData = $this->hydrator->extract($hostelObject);
		unset($hostelData['id']);
		
		if($hostelObject->getId()) {
			//in case the capacity of the hostel changes, then rooms allocated has to be changed accordingly.
			//Delete all initial allocations and allocate new
			$room_details = $this->findHostel($hostelObject->getId());
			foreach($room_details as $detail){
				$total_room_no = $detail['hostel_room_no'];
			}
			
			//ID present, so it is an update
			$action = new Update('hostels_list');
			$action->set($hostelData);
			$action->where(array('id = ?' => $hostelObject->getId()));
			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();
			
			
			if($total_room_no != $hostelData['hostel_Room_No']){
				$delete_action = new Delete('hostel_rooms');
				$delete_action->where(array('hostels_list_id = ?' => $hostelObject->getId()));
				$delete = new Sql($this->dbAdapter);
				$statement = $delete->prepareStatementForSqlObject($delete_action);
				$delete_result = $statement->execute();
								
				for($i=1; $i<=$hostelData['hostel_Room_No']; $i++){
					$room_action = new Insert('hostel_rooms');
					$room_action->values(array(
									'room_no' => $i,
									'room_capacity' => $hostelData['room_Capacity'],
									'room_available' => $hostelData['room_Capacity'],
									'hostels_list_id' => $hostelObject->getId(),
								));
					$sql = new Sql($this->dbAdapter);
					$stmt = $sql->prepareStatementForSqlObject($room_action);
					$result = $stmt->execute();
				}
			}
				
		} else {
			//ID is not present, so its an insert
			$action = new Insert('hostels_list');
			$action->values($hostelData);
			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();
			
			//get last generated id
			$hostels_list_id = $result->getGeneratedValue();
			
			//when new hostel is allocated, we need to fill in the default values for the rooms
			for($i=1; $i<=$hostelData['hostel_Room_No']; $i++){
				$room_action = new Insert('hostel_rooms');
				$room_action->values(array(
								'room_no' => $i,
								'room_capacity' => $hostelData['room_Capacity'],
								'room_available' => $hostelData['room_Capacity'],
								'hostels_list_id' => $hostels_list_id,
							));
				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($room_action);
				$result = $stmt->execute();
			}
			
		}
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $hostelObject->setId($newId);
			}
			return $hostelObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	public function saveHostelRoom(HostelRoom $hostelObject)
	{
		$hostelData = $this->hydrator->extract($hostelObject);
		unset($hostelData['id']);
		
		//Data to change room availability no depending on changes
		$room_details = $this->getHostelRoomAvailability($hostelObject->getId());
		
		if($hostelObject->getId()) {
			//ID present, so it is an update
			$action = new Update('hostel_rooms');
			$action->set($hostelData);
			$action->where(array('id = ?' => $hostelObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('hostel_room');
			$action->values($hostelData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		//if update, we need to change the room availability
		if($hostelObject->getId()) {
			$roomData['room_Available'] = $hostelData['room_Capacity'] - $room_details['room_capacity'] + $room_details['room_available'];
			$action2 = new Update('hostel_rooms');
			$action2->set($roomData);
			$action2->where(array('id = ?' => $hostelObject->getId()));
			$sql2 = new Sql($this->dbAdapter);
			$stmt2 = $sql2->prepareStatementForSqlObject($action2);
			$result2 = $stmt2->execute();
		}
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $hostelObject->setId($newId);
			}
			return $hostelObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	 * Save Hostel Application for change/new 
	 */
	 
	public function saveHostelApplication(HostelApplication $hostelObject)
	{
		$hostelData = $this->hydrator->extract($hostelObject);
		unset($hostelData['id']);
		
		if($hostelObject->getId()) {
			//ID present, so it is an update
			$action = new Update('student_hostel_application');
			$action->set($hostelData);
			$action->where(array('id = ?' => $hostelObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('student_hostel_application');
			$action->values($hostelData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $hostelObject->setId($newId);
			}
			return $hostelObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	 * Save Hostel Inventory
	 */
	 
	public function saveHostelInventory(HostelInventory $hostelObject)
	{
		$hostelData = $this->hydrator->extract($hostelObject);
		unset($hostelData['id']);
		
		if($hostelObject->getId()) {
			//ID present, so it is an update
			$action = new Update('student_hostel_inventory');
			$action->set($hostelData);
			$action->where(array('id = ?' => $hostelObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('student_hostel_inventory');
			$action->values($hostelData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $hostelObject->setId($newId);
			}
			return $hostelObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Allocate Hostel to students
	*/
	
	public function allocateHostel(HostelAllocation $hostelObject, $organisation_id)
	{
		$hostelData = $this->hydrator->extract($hostelObject);
		
		//extract the variables passed
		$hostelList = $hostelData['hostel_Name'];
		$yearParameters = $hostelData['yearwise'];
		
		//get the no of hostels chosen
		$hostelCount = count($hostelList);
		
		//get the hostel type, i.e. whether girls or boys
		$hostel_type = $this->getHostelType($hostelList[0]);	
		if($hostel_type=='Boys')
			$gender = '1';
		else
			$gender = '2';
		
		//get the list of programmes in the organisation
		$programmes = $this->getProgrammeNo($organisation_id);
		
		//need to get which part of the year so that we do not mix the enrollment years
		$semester = $this->getSemester($organisation_id);
		$academic_year = $this->getAcademicYear($semester);
		
		if($semester == 'Spring')
			$minus_year = 1;
		else 
			$minus_year = 0;
				
		//get student ID and store them in a 3-dimensional array (studentList[programme][enrollment year/batch][students id])
		$studentList = array();
		foreach($yearParameters as $year){
			$i=1;
			$enrollment_year = date('Y') - (int) $year +1-$minus_year;
			foreach($programmes as $prog){
				$studentList[$i++][] = $this->getStudentList($prog, $enrollment_year, $gender, $organisation_id, $columnName='id');
			}
		}
		/* 
		* put the programmes into an array 
		* when student list for a programme is empty, need to pop it from the array
		* This array will be repopulated for a new hostel
		*/
			
		$programme_list = array();
		foreach($programmes as $key=>$value){
			$programme_list[$key] = $key;
		}
		
		//the following is the programme index
		// use to increment the studentList [batch]
		$batch_index =0;
		$batch_counter = 0;
		$tmp_programme_list = $programme_list;
		
		for($i=0; $i<$hostelCount; $i++)
		{
			//get the hostel parameters such as hostel name, no. of rooms
			$hostelDetails = $this->getHostelRoomCapacity($hostelList[$i]);
			
			foreach($hostelDetails as $hostel_value)
			{
				for($i=1; $i<= $hostel_value['room_available']; $i++)
				{
					//if there is only a single value in the tmp list
					if(count($tmp_programme_list)== 1)
							$index = array_pop($tmp_programme_list);
					else if(count($tmp_programme_list)> 1)
						$index = array_rand($tmp_programme_list,1);
							
					while((empty($studentList[$index][$batch_index])) && ($batch_index <= count($yearParameters))){
						unset($tmp_programme_list[$index]);
						++$batch_counter;
						//if there is only a single value in the tmp list
						if(count($tmp_programme_list)== 1)
							$index = array_pop($tmp_programme_list);
						else if(count($tmp_programme_list)> 1)
							$index = array_rand($tmp_programme_list,1);
						//reset values if batch counter == count(programme list)
						if($batch_counter == count($programme_list)){
							++$batch_index;
							$batch_counter=0;
							if(empty($tmp_programme_list))
								$tmp_programme_list = $programme_list;
						}
					}
					//insert values into database
					if(!empty($studentList[$index][$batch_index])){
						$action = new Insert('student_hostels');
						$action->values(array(
							'year' => date('Y'),
							'hostel_rooms_id' => $hostel_value['id'],
							'student_id' => array_pop($studentList[$index][$batch_index]),
						));
						
						$sql = new Sql($this->dbAdapter);
						$stmt = $sql->prepareStatementForSqlObject($action);
						$result = $stmt->execute();
						
						//need to reduce the room availability after the room has been occupied
						$this->reduceRoomAvailability($hostel_value['id']);
					}
				}
			}
		}
		return;
	}
	
	/**
	* @param int/String $id
	* @return Hostel
	* @throws \InvalidArgumentException
	*/
	
	public function findHostel($id)
	{
        $sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'hostels_list')); 
		$select->where(array('id = ?' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

	}
	
	public function findHostelRoom($id)
	{
        $sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'hostel_rooms')); 
		$select->where(array('id = ?' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

	}
	
	public function getHostelAllocationDetails($id)
	{
        $sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_hostels'))
						->join(array('t2' => 'hostel_rooms'), 
                            't1.hostel_rooms_id = t2.id', array('room_no'))
						->join(array('t3' => 'hostels_list'), 
                            't2.hostels_list_id = t3.id', array('hostel_name'))
						->join(array('t4' => 'student'), 
                            't1.student_id = t4.id', array('first_name','middle_name','last_name','student_id'))
						->where(array('t3.id = ?' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	
	/*
	 * Get the details of the hostel room inventory
	 */
	 public function getHostelInventoryDetails($id)
	 {
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_hostel_inventory'))
						->join(array('t2' => 'hostels_list'), 
                            't1.hostels_list_id = t2.id', array('hostel_name'))
						->where(array('t1.id = ?' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	 }
	
	 /*
	 * Get the list of the rooms for all hostels in a college 
	 */
	 
	public function getHostelRoomList($hostelName, $roomNo, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'hostel_rooms'))
					->join(array('t2' => 'hostels_list'), 
                            't1.hostels_list_id = t2.id', array('hostel_name','organisation_id'));
		if($hostelName){
			$select->where->like('t2.id','%'.$hostelName.'%');
		}
		if($roomNo){
			$select->where(array('t1.room_no' =>$roomNo));
		}
		if($organisation_id){
			$select->where(array('t2.organisation_id' =>$organisation_id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get List of Students according to branch
	* (used for hostel allocation)
	*/
	
	public function getStudentList($programme, $enrollment_year, $gender, $organisation_id, $columnName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		//first get the list of students that are allocated hostels
		$select->from(array('t1' => 'student_hostels'))
				->join(array('t2' => 'student'), 
                            't1.student_id = t2.id', array('id','enrollment_year'));
		$select->where(array('t2.organisation_id' =>$organisation_id));
		$select->where(array('t2.enrollment_year' =>$enrollment_year));
		$select->where(array('t2.student_status_type_id' => 1));
		$select->where->like('t2.gender','%'.$gender.'%');
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$student_ids = array();
		foreach($resultSet as $set){
			$student_ids[$set['student_id']] = $set['student_id'];
		}
		
		//Get list of students that are not allocated
		$action = $sql->select();
		$action->from(array('t1' => 'student'))
				->columns(array($columnName));
		$action->where(array('t1.programmes_id' =>$programme));
		$action->where(array('t1.organisation_id' =>$organisation_id));
		$action->where(array('t1.enrollment_year' =>$enrollment_year));
		$action->where(array('t1.student_status_type_id' => 1));
		$action->where->like('t1.gender', $gender.'%');
		if($student_ids != NULL){
			$action->where->addPredicate(new Predicate\NotIn('t1.id', $student_ids));
		}
		
		$stmt2 = $sql->prepareStatementForSqlObject($action);
		$result2 = $stmt2->execute();
		$resultSet2 = new ResultSet();
		$resultSet2->initialize($result2);
		
		//Need to make the resultSet as an array
		// id=> name. For Example 2 => Name etc.
			
		$studentData = array();
		$i=1;
		foreach($resultSet2 as $set2){
			$studentData[$i++] = $set2[$columnName];
		}
		return $studentData;
	}
	
	/*
	* Get Hostel Tupe
	* This function is used by Allocate Hostel to find whether the hostel type is girls or boys
	*/
	
	public function getHostelType($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'hostels_list'));
		$select->columns(array('hostel_category'));
		$select->where(array('id = ?' => $id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
				return $set['hostel_category'];
			}
	}
	
	/*
	* Get No of Progammes in a given organisation
	* used for hostel allocation
	*/
	
	public function getProgrammeNo($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'programmes'));
		$select->columns(array('id'));
		$select->where(array('organisation_id = ?' => $organisation_id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$programmes = array();
		$i=1;
		foreach($resultSet as $set){
			$programmes[$i++] = $set['id'];
		}
		
		return $programmes;
	}
	
	/*
	* Reduce the Room Available everytime a room is allocated to a student
	* Used by Hostel Allocation Function
	*/
	
	public function reduceRoomAvailability($room_id)
	{
		$sql = new Sql($this->dbAdapter);
		
		//get the room capacity
		$room_capacity = 0;
		
		$select = $sql->select();
		$select->from(array('t1' => 'hostel_rooms')) 
                    ->columns(array('room_available'))
                    ->where('t1.id = ' .$room_id);
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$room_capacity = $set['room_available'];
		}
		
		$hostelData['room_Available'] = $room_capacity-1;
		$action = new Update('hostel_rooms');
		$action->set($hostelData);
		$action->where(array('id = ?' => $room_id));
		$stmt2 = $sql->prepareStatementForSqlObject($action);
		$result2 = $stmt2->execute();
	}
	
	/*
	* Get the room capacity of each hostel
	* (used for hostel allocation)
	*/
	
	public function getHostelRoomCapacity($hostel_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'hostels_list')) 
                    ->columns(array('hostel_name'))
					->join(array('t2' => 'hostel_rooms'), 
                            't1.id = t2.hostels_list_id', array('id','room_no','room_capacity','room_available'))
                    ->where('t1.id = ' .$hostel_id);
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get Hostel Room Availability
	* Similar to above. However, since the getHostelRoomCapacity is used by hostel allocation
	* did not want to change it
	*/
	
	public function getHostelRoomAvailability($room_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'hostel_rooms')) 
                    ->where('t1.id = ' .$room_id);
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$roomDetails = array();
		foreach($resultSet as $set){
			$roomDetails['room_capacity'] = $set['room_capacity'];
			$roomDetails['room_available'] = $set['room_available'];
		}		
		return $roomDetails;
	}
	
	/*
	 * Get Hostel Application
	 */
	 
	public function getHostelApplication($student_id, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		if($student_id != NULL){
			$select->from(array('t1' => 'student_hostel_application'))
					->join(array('t2' => 'hostels_list'), 
                            't1.hostel_to_name = t2.id', array('hostel_to_name'=>'hostel_name'))
                    ->where('t1.student_id = ' .$student_id);
		}
		else {
			$status = 'Pending';
			$select->from(array('t1' => 'student_hostel_application'))
					->join(array('t3' => 'hostels_list'), 
                            't1.hostel_to_name = t3.id', array('hostel_to_name'=>'hostel_name'))
					->join(array('t2' => 'student'), 
                            't1.student_id = t2.id', array('first_name','middle_name','last_name','student_id'))
                    ->where('t1.organisation_id = ' .$organisation_id)
					->where(array('t1.status = ?' => $status));
		}
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	 * Get Hostel Inventory
	 */
	 
	 public function getHostelInventory($organisation_id)
	 {
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student_hostel_inventory'))
				->join(array('t3' => 'hostels_list'), 
						't1.hostels_list_id = t3.id', array('hostel_name'))
				->where('t3.organisation_id = ' .$organisation_id);
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	 }
	
	/*
	 * Get student numbers by year
	 */
	 
	public function getStudentNoByYear($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		//get the maximum programme duration
		$select->from(array('t1' => 'programmes'))
				->columns(array('id', 'programme_duration'))
				->where(array('organisation_id' =>$organisation_id))
				->order('programme_duration DESC')
				->limit(1);
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$duration = 0;
		foreach($resultSet as $set){
			$duration = $set['programme_duration'];
		}
		preg_match_all('!\d+!', $duration, $years);
		$years = implode(' ', $years[0]);
		
		//need to get which part of the year so that we do not mix the enrollment years
		$semester = $this->getSemester($organisation_id);
		$academic_year = $this->getAcademicYear($semester);
								
		$programme_years = array();
		for($i =1; $i<=$years; $i++){
			$batch_year = $i;
			$student_male = $this->getNumberStudents($batch_year, $academic_year, $organisation_id, $gender='1');
			$student_female = $this->getNumberStudents($batch_year, $academic_year, $organisation_id, $gender='2');
			$programme_years[$i] = $i.' Year (Male = ' . $student_male .' / Female = '. $student_female.' )';
		}
		return $programme_years ;
	}
	
	//gets the number of students that have not been allocated hostel by year for a college
	public function getNumberStudents($batch_year, $academic_year, $organisation_id, $gender)
	{
		$sql = new Sql($this->dbAdapter);
		
		$select = $sql->select();
		$select->from(array('t1' => 'student_hostels'))
				->join(array('t2' => 'student'), 
                            't1.student_id = t2.id', array('organisation_id'))
				->join(array('t3' => 'student_semester_registration'), 
                            't3.student_id = t2.id', array('academic_year'));
		$select->where(array('t2.organisation_id' =>$organisation_id));
		$select->where(array('t3.year_id' =>$batch_year));
		$select->where(array('t3.academic_year' =>$academic_year));
		$select->where(array('t2.student_status_type_id' => 1));
		$select->where->like('t2.gender','%'.$gender.'%');
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$student_ids = array();
		foreach($resultSet as $set){
			$student_ids[$set['student_id']] = $set['student_id'];
		}
		
		$action = $sql->select();
		$action->from(array('t1' => 'student'))
					->columns(array('id'))
				->join(array('t2' => 'student_semester_registration'), 
                            't2.student_id = t1.id', array('academic_year'));
		$action->where(array('t1.organisation_id' =>$organisation_id));
		$action->where(array('t2.year_id' =>$batch_year));
		$action->where(array('t1.student_status_type_id' => 1));
		$action->where->like('t1.gender', $gender.'%');
		if(!empty($student_ids)){
			$action->where->addPredicate(new Predicate\NotIn('t1.id', $student_ids));
		}
		
		$stmt2 = $sql->prepareStatementForSqlObject($action);
		$result2 = $stmt2->execute();
		$resultSet2 = new ResultSet();
		$resultSet2->initialize($result2);

		$ids = array();
		foreach($resultSet2 as $set2){
			$ids[$set2['id']] = $set2['id'];
		}

		return count($ids);
	}
	
	//get the total number of rooms available for each hostel
	public function getTotalRoomAvailability($hostel_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'hostel_rooms'));
		$select->columns(array('room_available' => new \Zend\Db\Sql\Expression('SUM(room_available)')));
		$select->where(array('hostels_list_id' =>$hostel_id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$sum = $set['room_available'];
		}
		return $sum;
	}
	
	/*
	 * Get the semester from the database
	 */
	
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
		
		foreach($resultSet as $set){
			if($set['academic_event'] == 'Start of Autumn Semester'){
				$semester = 'Autumn';
			}
			else if($set['academic_event'] == 'Start of Spring Semester'){
				$semester = 'Spring';
			}
		}
		return $semester;
	}
        
	/*
	 * Get the academic year based on the semester from the database
	 */
	
	public function getAcademicYear($semester_type)
	{
		$academic_year = NULL;
		
		if($semester_type == 'Autumn'){
			$academic_year = date('Y').'-'.(date('Y')+1);
		} else {
			$academic_year = (date('Y')-1).'-'.date('Y');
		}
		
		return $academic_year;
	}
	
	/**
	* @return array/Hostel()
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		if($tableName == 'hostels_list'){
			$select->from(array('t1' => $tableName));
			$select->columns(array('id',$columnName, 'hostel_category'));
			$select->where(array('organisation_id' =>$organisation_id));
		}
		else if($tableName == 'employee_details'){
			$select->from(array('t1' => $tableName));
			$select->columns(array('id','first_name', 'middle_name', 'last_name', 'emp_id'));
			$select->where(array('t1.organisation_id' =>$organisation_id));
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
		if($tableName == 'hostels_list'){
			foreach($resultSet as $set)
			{
				$availability = $this->getTotalRoomAvailability($set['id']);
				$selectData[$set['id']] = $set[$columnName].' ('.$set['hostel_category'].') - Total Available= '.$availability;
			}
		}
		else if($tableName == 'employee_details'){
			foreach ($resultSet as $set) {
				$selectData[$set['id']] = $set['first_name'].' '.$set['middle_name'].' '.$set['last_name'].' ('.$set['emp_id'].')';
			}
		}
		else{
			foreach($resultSet as $set)
			{
				$selectData[$set['id']] = $set[$columnName];
			}
		}
		
		return $selectData;
	}
        
}
<?php

namespace RepeatModules\Mapper;

use RepeatModules\Model\RepeatModules;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements RepeatModulesMapperInterface
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
	 * @var \RepeatModules\Model\RepeatModulesInterface
	*/
	protected $repeatModulesPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			RepeatModules $repeatModulesPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->repeatModulesPrototype = $repeatModulesPrototype;
	}
	
	
	/**
	* @return array/RepeatModules()
	*/
	public function findAll($tableName, $applicant_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName)); 
		$select->where(array('id' =>$applicant_id));

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
		//$select->columns(array('id'));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* take username and returns student id
	*/
	
	public function getStudentId($username)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student'));
		$select->where(array('student_id' =>$username));
		//$select->columns(array('id'));
			
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
	*take username and return the employee first name, middle name and last name
	*/
	public function getUserDetails($username, $usertype)
	{
		$name = NULL;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($usertype == 1){
			$select->from(array('t1' => 'employee_details'));
			$select->where(array('emp_id' =>$username));
			$select->columns(array('first_name', 'middle_name', 'last_name'));
		}

		else if($usertype == 2){
			$select->from(array('t1' => 'student'));
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


	public function getModuleRepeatRegistrationDuration($organisation_id)
	{
		$academic_event = 'Module Repeat Registration';

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_calendar'))
			   ->join(array('t2' => 'academic_calendar_events'),
			   		't2.id = t1.academic_event', array('academic_event'));
		$select->where(array('t1.from_date <= ? ' => date('Y-m-d')));
		$select->where(array('t1.to_date >= ? ' => date('Y-m-d')));
		$select->where(array('t2.academic_event' => $academic_event));
		$select->where(array('t2.organisation_id'=> $organisation_id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//here programme stores both programme id and organisaiton id
		$announcementperiod = array();
		foreach($resultSet as $set)
		{
			$announcementperiod['from_date'] = $set['from_date'];
			$announcementperiod['to_date'] = $set['to_date'];

		}
		return $announcementperiod;
	}
	
	/*
	* Save Repeat Modules Application
	*/
	
	public function save(RepeatModules $repeatModulesObject)
	{
		$repeatModulesData = $this->hydrator->extract($repeatModulesObject);
		unset($repeatModulesData['id']);

		$repeat_module_details = $this->getRepeatModuleDetails($repeatModulesData['academic_Modules_Allocation_Id']);
//var_dump($repeat_module_details); die();
		$present_academic_year = $this->getAcademicYear($repeatModulesData['student_Id']);
		
		if($repeatModulesObject->getId()) {
			//ID present, so it is an update
			$action = new Update('student_backpaper_registration');
			$action->set($repeatModulesData);
			$action->where(array('id = ?' => $repeatModulesObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('student_backpaper_registration');
			$action->values(array(
				'registration_date' => $repeatModulesData['registration_Date'],
				'module_code' => $repeat_module_details['module_code'],
				'academic_year' => $present_academic_year['academic_year'],
				'backpaper_semester' => $repeat_module_details['semester'],
				'programmes_id' => $repeat_module_details['programmes_id'],
				'backpaper_academic_year' => $repeat_module_details['academic_year'],
				'backpaper_in' => $repeat_module_details['year'],
				'registration_status' => 'Pending',
				'academic_modules_allocation_id' => $repeatModulesData['academic_Modules_Allocation_Id'],
				'student_id' => $repeatModulesData['student_Id']
			));
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $repeatModulesObject->setId($newId);
			}
			return $repeatModulesObject;
		}
		
		throw new \Exception("Database Error");
	}


	public function getAcademicYear($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student'));
		$select->where(array('t1.id' =>$student_id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$organisation_id = NULL;
		foreach($resultSet as $set){
			$organisation_id = $set['organisation_id'];
		}

		$select1 = $sql->select();

		$select1->from(array('t1' => 'academic_calendar'))
				->columns(array('academic_year'))
				->join(array('t2' => 'academic_calendar_events'), 
						't1.academic_event = t2.id', array('academic_event'));
		$select1->where(array('t1.from_date <= ? ' => date('Y-m-d')));
		$select1->where(array('t1.to_date >= ? ' => date('Y-m-d')));
		$select1->where(array('t2.organisation_id' => $organisation_id));
			
		$stmt1 = $sql->prepareStatementForSqlObject($select1);
		$result1 = $stmt1->execute();
		
		$resultSet1 = new ResultSet();
		$resultSet1->initialize($result1);

		$academic_year = NULL;
		
		foreach($resultSet1 as $set1){
			if($set1['academic_event'] == 'Autumn Semester Duration'){
				$academic_year['academic_event'] = 'Autumn';
				$academic_year['academic_year'] = $set1['academic_year'];
			}
			else if($set1['academic_event'] == 'Spring Semester Duration'){
				$academic_year['academic_event'] = 'Spring';
				$academic_year['academic_year'] = $set1['academic_year'];
			}
		} 
		return $academic_year;
		
	}


	public function getRepeatModuleDetails($academic_modules_allocation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_modules_allocation'));
		$select->where(array('t1.id' =>$academic_modules_allocation_id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$module_details = array();
		foreach($resultSet as $set){
			$module_details = $set;
		}
		return $module_details;
	}


	public function listRegisteredRepeatModules($student_id, $organisation_id, $type)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($type == 'student'){
			$select->from(array('t1' => 'student_backpaper_registration'))
			   ->join(array('t2' => 'academic_modules_allocation'),
			   		't2.id = t1.academic_modules_allocation_id', array('module_title', 'module_type'));
			$select->where(array('t1.student_id' =>$student_id));
		}else{
			$select->from(array('t1' => 'student_backpaper_registration'))
			   ->join(array('t2' => 'academic_modules_allocation'),
			   		't2.id = t1.academic_modules_allocation_id', array('module_title', 'module_type'))
			   ->join(array('t3' => 'programmes'),
			   		't3.id = t2.programmes_id', array('programme_name'))
				->join(array('t4' => 'student'),
					't4.id = t1.student_id', array('first_name', 'middle_name', 'last_name', 'student_id'));
			$select->where(array('t3.organisation_id' =>$organisation_id));
		}
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	
	/*
	* Get the details of the student
	*/
	
	public function getStudentDetails($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student'))
				->columns(array('first_name','middle_name','last_name','student_id'))
				->join(array('t2' => 'programmes'), 
									't1.programmes_id = t2.id', array('programme_name'));
		$select->where(array('t1.id' =>$student_id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function listEligibleRepeatModuleList($student_id)
	{
		$programme = $this->getProgrammeId($student_id);
		$student_No = $this->getStudentNo($student_id);
		$programme_id = $programme['programmes_id'];
		$organisation_id = $programme['organisation_id'];
		$semester_details = $this->getSemester($organisation_id);
		
		$semester = $semester_details['academic_event'];
		$academic_year = $semester_details['academic_year'];
		$student_semester_details = $this->getStudentYear($student_id); 
		$student_semester = $student_semester_details['semester_id']; 
		$student_year = $student_semester_details['year_id'];
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_modules_allocation'))
				->columns(array('id','module_title','module_code'))
				->join(array('t2' => 'student_consolidated_marks'),
					't2.academic_modules_allocation_id = t1.id', array('level', 'result_status', 'status'));
		$select->where(array('t1.programmes_id' =>$programme_id));
        //$select->where(array('t1.academic_year' =>$academic_year));
		//$select->where(array('t2.semester' =>$student_semester));
		//$select->where(array('t1.year' =>$student_year));
		//$select->where(array('t2.level' => 'Regular'));
		$select->where(array('t2.result_status' => 'Declared'));
		$select->where(array('t2.status != ?' => 'Pass'));
		//$select->where(array('t2.status' => 'Repeat'));
		$select->where(array('t2.student_id' => $student_No));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//here programme stores both programme id and organisaiton id
		$modules = array();
		foreach($resultSet as $set)
		{
			$modules[$set['id']] = $set['module_title']." (".$set['module_code'].")";
		}
		return $modules;
	}


	/*
	* Get the Year of the Student
	*/
	
	private function getStudentYear($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_semester_registration'))
				->columns(array('semester_id', 'year_id'))
				->join(array('t2' => 'student'), 
						't1.student_id = t2.id', array('student_id'));
		$select->where(array('t2.id' =>$student_id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		//here programme stores both programme id and organisaiton id
		$student_year = array();
		foreach($resultSet as $set)
		{
			$student_year['semester_id'] = $set['semester_id'];
			$student_year['year_id'] = $set['year_id'];
		}
		return $student_year;
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
			if($set['academic_event'] == 'Autumn Semester Duration'){
				$semester['academic_event'] = 'Autumn';
				$semester['academic_year'] = $set['academic_year'];
			}
			else if($set['academic_event'] == 'Spring Semester Duration'){
				$semester['academic_event'] = 'Spring';
				$semester['academic_year'] = $set['academic_year'];
			}
		}
		return $semester;
	}


	public function getStudentNo($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student'))
				->columns(array('student_id'));
		$select->where(array('t1.id' =>$id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//here programme stores both programme id and organisaiton id
		$student_no = NULL;
		foreach($resultSet as $set)
		{
			$student_no = $set['student_id'];
		}
		return $student_no;
	}


	/*
	* Get the Programme Id of the student
	*/
	
	public function getProgrammeId($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student'))
				->columns(array('programmes_id', 'organisation_id'));
		$select->where(array('t1.id' =>$student_id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		//here programme stores both programme id and organisaiton id
		$programme = array();
		foreach($resultSet as $set)
		{
			$programme['programmes_id'] = $set['programmes_id'];
			$programme['organisation_id'] = $set['organisation_id'];
		}
		return $programme;
	}
	
	/**
	* @return array/RepeatModules()
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
		//this if for Activities for AWPA Activities
		if($tableName == 'awpa_objectives_activity')
		{
			$selectData[0] = 'Others';
		}
		return $selectData;
			
	}
        
}
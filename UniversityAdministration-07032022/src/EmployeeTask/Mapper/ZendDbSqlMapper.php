<?php

namespace EmployeeTask\Mapper;

use EmployeeTask\Model\EmployeeTask;
use EmployeeTask\Model\EmployeeTaskCategory;
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

class ZendDbSqlMapper implements EmployeeTaskMapperInterface
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
		$this->employeetaskPrototype = $employeetaskPrototype;
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
	* @param int/String $id
	* @return EmployeeTask
	* @throws \InvalidArgumentException
	*/
	
	public function findStaff($id)
	{
        $sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details')); 
		$select->where(array('id = ?' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		
		return $resultSet->initialize($result);

	}

	
	/**
	* @return array/EmployeeTask()
	*/
	public function findAll($tableName, $organisation_id, $employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->where(array('t1.organisation_id' =>$organisation_id));
		$select->where(array('t1.employee_details_id' =>$employee_details_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		 return $resultSet->initialize($result);
	}
	/**
	* @return array/EmployeeTask()
	*/
	public function findAll1($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'employee_task_record')) 
					->columns(array('id', 'employeetask_details','from_date','to_date','recorded_by', 'evidence_file','from_time','to_time','employeetask_type','status'))
					->join(array('t2' => 'employee_task_category'), 
                            't1.employeetask_category_id = t2.id', array('employee_task_category'))
                    ->join(array('t3'=>'employee_details'),
                            't1.staff_id = t3.id', array('employee_details_id'=>'id','first_name','middle_name','last_name', 'emp_id'))
					->join(array('t5'=>'employee_details'),
							't1.recorded_by= t5.id' , array('staff_first_name'=>'first_name','staff_last_name'=>'last_name', 'emp_id'));
		$select->where(array('t1.staff_id' =>$employee_details_id));
		$select->order(array('t1.from_date DESC'));
		$select->order(array('t1.employeetask_type DESC'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
	}
        
	/**
	 * 
	 * @param type $EmployeeTaskInterface
	 * 
	 * to save  Details
	 */
	
	public function saveDetails(EmployeeTaskCategory $employeetaskObject)
	{

		$employeetaskData = $this->hydrator->extract($employeetaskObject);

		unset($employeetaskData['id']);

		if($employeetaskObject->getId()) {
			//ID present, so it is an update
			$action = new Update('employee_task_category');
			$action->set($employeetaskData);
			$action->where(array('id = ?' => $employeetaskObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('employee_task_category');
			$action->values($employeetaskData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $employeetaskObject->setId($newId);
			}
			return $employeetaskObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Save the disciplinary record of a student
	*/
	
	public function saveEmployeeTaskRecord(EmployeeTask $employeetaskObject)
	{
		$employeetaskData = $this->hydrator->extract($employeetaskObject);

		$evidence_file_id = $employeetaskData['id'];


		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'employee_task_record')) 
					->columns(array('id','evidence_file'));
		$select->where(array('t1.id' => $evidence_file_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$id = $resultSet->initialize($result);

		foreach($resultSet as $set){
			$evidence_file = $set['evidence_file'];
		}
		if($evidence_file != NULL){
			$evidence_file_name = $employeetaskData['evidence_File'];			
		} else {
			
			$evidence_file_name = $employeetaskData['evidence_File'];
			$employeetaskData['evidence_File'] = $evidence_file_name['tmp_name'];
			
		}

		$employeetaskData['from_Date'] = date("Y-m-d", strtotime(substr($employeetaskData['from_Date'],0,10)));
		$employeetaskData['to_Date'] = date("Y-m-d", strtotime(substr($employeetaskData['to_Date'],0,10)));
		
		if($employeetaskObject->getId()) {
			//ID present, so it is an update	
			//var_dump($employeetaskModel); die();								
			$action = new Update('employee_task_record');
			$action->set($employeetaskData);
			$action->where(array('id = ?' => $employeetaskObject->getId()));
		} else {
			//ID is not present, so its an insert
			unset($employeetaskData['id']);

			$action = new Insert('employee_task_record');
			$action->values($employeetaskData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $employeetaskObject->setId($newId);
			}
			return $employeetaskObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* List Staff to add awards etc
	*/
	
	public function getStaffList($staffName, $staffId, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'employee_details'))
				->where(array('t1.emp_resignation_id' => '0'));
		
		if($staffName){
			$select->where->like('first_name','%'.$staffName.'%');
		}
		if($staffId){
			$select->where(array('emp_id' =>$staffId));
		}
		if($organisation_id){
			$select->where(array('t1.organisation_id' =>$organisation_id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

	}
	
	/*
	* Get the list of disciplinary action of students after search funcationality
	*/
	
	public function getStaffEmployeeTaskList($staffName, $staffId, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'employee_task_record')) 
                    ->columns(array('employeetask_details','from_date','to_date','recorded_by'))
					->join(array('t4' => 'employee_task_category'), 
                            't1.employeetask_category_id = t4.id', array('employee_task_category'))
					->join(array('t2' => 'employee_details'), 
                            't1.staff_Id = t2.id', array('id','first_name','middle_name','last_name','emp_id'));
		//$select->group(array('t1.staff_id'));
		
		if($stafftName){
			$select->where->like('t2.first_name','%'.$staffName.'%');
		}
		if($staffId){
			$select->where(array('t2.emp_id' =>$staffId));
		}
		if($organisation_id){
			$select->where(array('t2.organisation_id' =>$organisation_id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
	}
	
	/**
	 * 
	 * @param type $id
	 * 
	 * to find Category details to edit/display
	 */
	public function getEmployeeTaskCategoryDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_task_category')); 
		$select->where(array('id = ?' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	

	public function getEmployeeTaskRecordDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_task_record')); 
		$select->where(array('id = ?' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	/**
	 * 
	 * @param type $id
	 * 
	 * to find Student details so that their names are displayed
	 */
	public function getStaffDetails($id) 
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details')) 
				->where('t1.id = ' .$id); 
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
		
	}
	
	/*
	* Get the disciplinary record of the students
	*/
	
	public function getEmployeeTaskRecord($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_task_record')) 
					->columns(array('staff_id','employeetask_details','employeetask_type','from_date','to_date','recorded_by'))
					->join(array('t2' => 'employee_task_category'), 
                            't1.employeetask_category_id = t2.id', array('employee_task_category'))
                    ->join(array('t3'=>'employee_details'),
                            't1.staff_id = t3.id', array('id','first_name','middle_name','last_name','emp_id'))
                    ->where(array('t3.organisation_id = ' .$organisation_id));
                    //->group(array('t3.emp_id'));
				
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get the list of disciplinary records by a student
	*/
	
	public function getStaffEmployeeTaskRecords($staff_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'employee_task_record')) 
					->columns(array('emp_task_record_id'=>'id','employeetask_details','from_date','to_date','recorded_by', 'evidence_file', 'from_time','to_time','employeetask_type','status'))
					->join(array('t2' => 'employee_task_category'), 
                            't1.employeetask_category_id = t2.id', array('employee_task_category'))
                    ->join(array('t3'=>'employee_details'),
                            't1.staff_id = t3.id', array('id','first_name','middle_name','last_name', 'emp_id'))
					->join(array('t5'=>'employee_details'),
							't1.recorded_by= t5.id' , array('staff_first_name'=>'first_name','staff_last_name'=>'last_name', 'emp_id'));
		$select->where(array('t1.staff_id' =>$staff_id));
		$select->order(array('t1.from_date DESC'));	

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
	}
	
	/**
	* @return array/Discipline()
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName, $organisation_id, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array('id',$columnName)); 
		$select->where(array('t1.organisation_id' =>$organisation_id));
		$select->where(array('t1.status' => 'Active'));

		if($id != NULL){
			$select->where(array('t1.employee_details_id' =>$id));
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

	public function listSelectData1($tableName, $id)
	{

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_task_record')) 
			->columns(array('task_record_id'=>'id','employeetask_details','from_date','to_date','recorded_by', 'evidence_file','employeetask_type'))
			->join(array('t2' => 'employee_task_category'), 
                    't1.employeetask_category_id = t2.id', array('id','employee_task_category'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['id']] = $set['employee_task_category'];
		}
		return $selectData;
	}

	public function getstafftaskRecord($staff_id,$from_date, $to_date)
	{

		$from_date = date("Y-m-d", strtotime(substr($from_date,0,10)));
		$to_date = date("Y-m-d", strtotime(substr($to_date,0,10)));		
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_task_record')) 
					->columns(array('employeetask_details','from_date','to_date','recorded_by', 'evidence_file', 'from_time','to_time','employeetask_type','status'))
					->join(array('t2' => 'employee_task_category'), 
                            't1.employeetask_category_id = t2.id', array('employee_task_category'))
                    ->join(array('t3'=>'employee_details'),
                            't1.staff_id = t3.id', array('id','first_name','middle_name','last_name', 'emp_id'))
					->join(array('t5'=>'employee_details'),
							't1.recorded_by= t5.id' , array('staff_first_name'=>'first_name','staff_last_name'=>'last_name', 'emp_id'));
		$select->where(array('t1.staff_id' =>$staff_id));
		$select->where(array('t1.from_date >= ? ' => $from_date, 't1.to_date <= ? ' => $to_date));
		$select->order(array('t1.from_date DESC'));	

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
	}


	public function getFileName($file_id)
	{ 
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_task_record')) 
				->where(array('t1.id' => $file_id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$fileLocation;
		foreach($resultSet as $set)
		{
			$fileLocation = $set['evidence_file'];
		}

		return $fileLocation;
	}
        
}
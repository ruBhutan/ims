<?php

namespace StudentParticipation\Mapper;

use StudentParticipation\Model\StudentParticipation;
use StudentParticipation\Model\StudentParticipationCategory;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements StudentParticipationMapperInterface
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
	 * @var \StudentParticipation\Model\StudentParticipationInterface
	*/
	protected $participationPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			StudentParticipation $participationPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->participationPrototype = $participationPrototype;
	}
	
	/*
	 * Get the Organisation Id
	 */
	 
	public function getOrganisationId($username, $tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName = 'employee_details'){
			$select->from(array('t1' => $tableName));
			$select->where(array('emp_id' =>$username));
			$select->columns(array('organisation_id'));
		}
		else if($tableName = 'student'){
			$select->from(array('t1' => $tableName));
			$select->where(array('student_id' =>$username));
			$select->columns(array('organisation_id'));
		}
			
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
		$select->columns(array('id','organisation_id'));
			
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
	* @return array/StudentParticipation()
	*/
	public function findAll($tableName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		if($organisation_id != NULL){
			$select->where(array('t1.organisation_id' =>$organisation_id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		 return $resultSet->initialize($result);

	}


	public function getStudentParticipationCategoryDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'student_participation_category'));
		$select->where(array('t1.id' => $id)); 

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
	}
        
		
	/**
	 * 
	 * @param type $StudentParticipationInterface
	 * 
	 * to save  Details
	 */
	
	public function saveDetails(StudentParticipation $participationObject)
	{
		$participationData = $this->hydrator->extract($participationObject);
		unset($participationData['id']);
                
                //need to get the file locations and store them in database
		$evidence_file_name = $participationData['evidence_File'];
		$participationData['evidence_File'] = $evidence_file_name['tmp_name'];

		$participationData['participation_Date'] = date('Y-m-d', strtotime(substr($participationData['participation_Date'], 0,10)));
		
		if($participationObject->getId()) {
			//ID present, so it is an update
			$action = new Update('student_participation');
			$action->set($participationData);
			$action->where(array('id = ?' => $participationObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('student_participation');
			$action->values($participationData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $participationObject->setId($newId);
			}
			return $participationObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	 * To save student participation category
	 */
	 
	public function saveParticipationCategory(StudentParticipationCategory $participationObject)
	{
		$participationData = $this->hydrator->extract($participationObject);
		unset($participationData['id']);
		
		if($participationObject->getId()) {
			//ID present, so it is an update
			$action = new Update('student_participation_category');
			$action->set($participationData);
			$action->where(array('id = ?' => $participationObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('student_participation_category');
			$action->values($participationData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $participationObject->setId($newId);
			}
			return $participationObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	
	/*
	* List Student to add awards etc
	*/
	
	public function getStudentList($studentName, $studentId, $programme, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'student'))
				->join(array('t2' => 'programmes'), 
                            't1.programmes_id = t2.id', array('programme_name'));
		
		if($studentName){
			$select->where->like('first_name','%'.$studentName.'%');
		}
		if($studentId){
			$select->where(array('student_id' =>$studentId));
		}
		if($programme){
			$select->where(array('programme' =>$programme));
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
	* Get the list of participations by students after search funcationality
	*/
	
	public function getStudentParticipationList($studentName, $studentId, $programme, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'student_participation')) 
                    ->columns(array('participation_date','remarks'))
					->join(array('t2' => 'student'), 
                            't1.student_id = t2.id', array('id','first_name','middle_name','last_name','student_id','enrollment_year'))
					->join(array('t3' => 'programmes'), 
                            't2.programmes_id = t3.id', array('programme_name'))
					->join(array('t4' => 'student_participation_category'),
							't4.id = t1.participation_type', array('participation_type'));
		
		if($studentName){
			$select->where->like('t2.first_name','%'.$studentName.'%');
		}
		if($studentId){
			$select->where(array('t2.student_id' =>$studentId));
		}
		if($programme){
			$select->where(array('t2.programme' =>$programme));
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
	 * to find Student details so that their names are displayed
	 */
	public function getStudentDetails($id) 
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student')) // base table
				->where('t1.id = ' .$id); 
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
		
	}
	
	/*
	 * Get the participation list of students
	 */
	 
	public function getParticipationList($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_participation')) 
                    ->columns(array('participation_date','remarks'))
					->join(array('t2' => 'student'), 
                            't1.student_id = t2.id', array('id','first_name','middle_name','last_name','student_id','enrollment_year'))
					->join(array('t3' => 'programmes'), 
                            't2.programmes_id = t3.id', array('programme_name'))
					->join(array('t4' => 'student_participation_category'),
							't4.id = t1.participation_type', array('participation_type'))
					->where(array('t2.organisation_id' =>$organisation_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
	}
	
	/*
	* Get the list of participations by a student
	*/
	
	public function getStudentParticipations($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'student_participation'))
					->columns(array('participation_date','remarks'))
                    ->join(array('t2' => 'student'), 
                            't1.student_id = t2.id', array('id','first_name','middle_name','last_name','student_id','enrollment_year'))
					->join(array('t3' => 'programmes'), 
                            't2.programmes_id = t3.id', array('programme_name'))
					->join(array('t4' => 'student_participation_category'),
							't4.id = t1.participation_type', array('participation_type'));
		$select->where(array('t1.student_id' =>$student_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
	}
	
	/**
	* @return array/StudentParticipation()
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'student_participation_category'){
			$select->from(array('t1' => $tableName));
			$select->columns(array('id',$columnName));
			$select->where(array('t1.organisation_id' => $organisation_id)); 
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
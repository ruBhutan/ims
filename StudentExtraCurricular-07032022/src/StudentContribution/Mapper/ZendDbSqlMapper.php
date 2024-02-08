<?php

namespace StudentContribution\Mapper;

use StudentContribution\Model\StudentContribution;
use StudentContribution\Model\StudentContributionCategory;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements StudentContributionMapperInterface
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
	 * @var \StudentContribution\Model\StudentContributionInterface
	*/
	protected $contributionPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			StudentContribution $contributionPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->contributionPrototype = $contributionPrototype;
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

		if($tableName = 'employee_details'){
			$select->from(array('t1' => $tableName));
			$select->where(array('emp_id' =>$username));
			$select->columns(array('id'));
		}

		else if($tableName = 'student'){
			$select->from(array('t1' => $tableName));
			$select->where(array('student_id' =>$username));
			$select->columns(array('id'));
		}
			
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
	* @return array/StudentContribution()
	*/
	public function findAll($tableName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName)); 
		$select->where(array('t1.organisation_id' =>$organisation_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

	}
        

	/**
	 * 
	 * @param type $StudentContributionInterface
	 * 
	 * to save Student Contribution Details
	 */
	
	public function saveDetails(StudentContribution $contributionObject)
	{
		$contributionData = $this->hydrator->extract($contributionObject);
		unset($contributionData['id']);
                
                //need to get the file locations and store them in database
		$evidence_file_name = $contributionData['evidence_File'];
		$contributionData['evidence_File'] = $evidence_file_name['tmp_name'];

		$contributionData['contribution_Date'] = date('Y-m-d', strtotime(substr($contributionData['contribution_Date'], 0,10)));
		
		if($contributionObject->getId()) {
			//ID present, so it is an update
			$action = new Update('student_contributions');
			$action->set($contributionData);
			$action->where(array('id = ?' => $contributionObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('student_contributions');
			$action->values($contributionData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $contributionObject->setId($newId);
			}
			return $contributionObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	 * TO save the student contribution category
	 */
	 
	public function saveContributionCategory(StudentContributionCategory $contributionObject)
	{
		$contributionData = $this->hydrator->extract($contributionObject);
		unset($contributionData['id']);
		
		if($contributionObject->getId()) {
			//ID present, so it is an update
			$action = new Update('student_contributions_category');
			$action->set($contributionData);
			$action->where(array('id = ?' => $contributionObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('student_contributions_category');
			$action->values($contributionData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $contributionObject->setId($newId);
			}
			return $contributionObject;
		}
		
		throw new \Exception("Database Error");
	}


	public function getStudentContributionCategoryDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'student_contributions_category'));
		$select->where(array('t1.id' => $id)); 

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
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
		$select->where(array('t1.organisation_id' => $organisation_id, 't1.student_status_type_id' => '1'));
		
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
	* Get the list of contributions by students after search funcationality
	*/
	
	public function getStudentContributionList($studentName, $studentId, $programme, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'student_contributions')) 
                    ->columns(array('contribution_date','contribution_type','remarks'))
					->join(array('t2' => 'student'), 
                            't1.student_id = t2.id', array('id','first_name','middle_name','last_name','student_id','enrollment_year'))
					->join(array('t3' => 'programmes'), 
                            't2.programmes_id = t3.id', array('programme_name'))
					->group(array('t1.student_id'));
		
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
	 * get the list of contribution list of students
	 */
	 
	public function getContributionList($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_contributions')) 
                    ->columns(array('contribution_date','contribution_type','remarks'))
					->join(array('t2' => 'student'), 
                            't1.student_id = t2.id', array('id','first_name','middle_name','last_name','student_id','enrollment_year'))
					->join(array('t3' => 'programmes'), 
                            't2.programmes_id = t3.id', array('programme_name'))
					->where(array('t2.organisation_id' =>$organisation_id))
					->group(array('t1.student_id'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
	}
	
	/*
	* Get the list of contributions made by a student
	*/
	
	public function getStudentContributions($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'student_contributions'))
					->columns(array('contribution_date','remarks'))
                    ->join(array('t2' => 'student'), 
                            't1.student_id = t2.id', array('id','first_name','middle_name','last_name','student_id','enrollment_year'))
					->join(array('t3' => 'programmes'), 
                            't2.programmes_id = t3.id', array('programme_name'))
					->join(array('t4' => 'student_contributions_category'),
							't4.id = t1.contribution_type', array('contribution_type'));
		$select->where(array('t1.student_id' =>$student_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
	}
	
	/**
	* @return array/StudentContribution()
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'student_contributions_category'){
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
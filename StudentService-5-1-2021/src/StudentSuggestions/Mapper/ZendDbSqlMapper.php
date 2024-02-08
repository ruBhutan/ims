<?php

namespace StudentSuggestions\Mapper;

use StudentSuggestions\Model\StudentSuggestions;
use StudentSuggestions\Model\SuggestionCommittee;
use StudentSuggestions\Model\SuggestionCategory;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements StudentSuggestionsMapperInterface
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
	 * @var \StudentSuggestions\Model\StudentSuggestionsInterface
	*/
	protected $studentPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			StudentSuggestions $studentPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->studentPrototype = $studentPrototype;
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


	public function crossCheckSuggestionCategory($suggestionCategory, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

    	$select->from(array('t1' => 'student_suggestion_category'))
		       ->columns(array('suggestion_category'))
	   	       ->where(array('t1.suggestion_category' => $suggestionCategory, 't1.organisation_id' => $organisation_id)); 
       
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
			
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
			
		$suggestionCategory = NULL;
		foreach($resultSet as $set)
		{
			$suggestionCategory = $set['suggestion_category'];
		}
		return $suggestionCategory;
	}


	public function crossCheckSuggestionCategoryDetails($suggestionCategory, $id, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

    	$select->from(array('t1' => 'student_suggestion_category'))
		       ->columns(array('suggestion_category'))
	   	       ->where(array('t1.suggestion_category' => $suggestionCategory, 't1.organisation_id' => $organisation_id)); 
	   	       //->where(array('t1.suggestion_category' => $suggestionCategory, 't1.organisation_id' => $organisation_id, 't1.id != ?' => $id));
       			
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
			
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
			
		$suggestionCategory = NULL;
		foreach($resultSet as $set)
		{
			$suggestionCategory = $set['suggestion_category'];
		}
		return $suggestionCategory;
	}
	
	/**
	* @param int/String $id
	* @return StudentSuggestions
	* @throws \InvalidArgumentException
	*/
	
	public function findStudentSuggestions($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select('hr_development');
		$select->where(array('id = ? ' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
				return $this->hydrator->hydrate($result->current(), $this->studentPrototype);
		}

		throw new \InvalidArgumentException("StudentSuggestions Proposal with given ID: ($id) not found");
	}


	public function getSuggestionCategoryDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select('student_suggestion_category');
		$select->where(array('id = ? ' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
				return $this->hydrator->hydrate($result->current(), $this->studentPrototype);
		}

		throw new \InvalidArgumentException("Suggestion suggestion_Category with given ID: ($id) not found");
	}


	public function getAjaxEmployeeDetailsId($tableName, $code)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->where(array('t1.emp_id = ?' => $code));		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$id = NULL;
		
		foreach($resultSet as $set)
		{
			$id = $set['id'];
		}
		return $id;
	}
	
	/**
	* @return array/StudentSuggestions()
	*/
	public function findAll($tableName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName))
		   ->where(array('t1.organisation_id' => $organisation_id));	
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		 return $resultSet->initialize($result);

		if ($result instanceof ResultInterface && $result->isQueryResult()) {

				$resultSet = new ResultSet();
				$resultSet->initialize($result);

				$resultSet = new HydratingResultSet($this->hydrator, $this->studentPrototype);
				return $resultSet->initialize($result); 
		}

		return array();
	}

	public function listSelectedSuggestion($employee_details_id, $tableName, $organisation_id) {
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if ($tableName == 'student_suggestion') {
			$select->from(array('t1' => $tableName))
			   ->join(array('t2' => 'student_suggestion_category'),
					't2.id = t1.student_suggestion_category_id', array('suggestion_category'))
			   ->join(array('t3' => 'organisation'),
					't3.id = t2.organisation_id', array('organisation_name','abbr'))
			   ->join(array('t4' => 'student_suggestion_committee'),
					't2.id = t4.student_suggestion_category_id', array('from_date','to_date'))
			   //->where(array('t3.id' => $organisation_id))
			   ->where(array('t4.from_date <= ? ' => date('Y-m-d')))
			   ->where(array('t4.to_date >= ? ' => date('Y-m-d')))
			   ->where(array('t4.employee_details_id' => $employee_details_id))
			   ->where(array('t1.action_taken' => NULL))
			   ->where(array('t4.status' => 'Active'))
			   ->order('id DESC');

		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		 return $resultSet->initialize($result);

		if ($result instanceof ResultInterface && $result->isQueryResult()) {

				$resultSet = new ResultSet();
				$resultSet->initialize($result);

				$resultSet = new HydratingResultSet($this->hydrator, $this->studentPrototype);
				return $resultSet->initialize($result); 
		}

		return array();

	}


	public function listStudentSuggestionList($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_suggestion'))
			   ->join(array('t2' => 'student_suggestion_category'),
					't2.id = t1.student_suggestion_category_id', array('suggestion_category'))
			   ->where(array('t1.student_id' => $student_id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getSuggestionDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_suggestion'))
			   ->join(array('t2' => 'student_suggestion_category'),
					't2.id = t1.student_suggestion_category_id', array('suggestion_category'))
			   ->where(array('t1.id' => $id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
        

	/**
	 * 
	 * @param type $StudentSuggestionsInterface
	 * 
	 * to save StudentSuggestions Details
	 */
	
	public function savePost(StudentSuggestions $studentObject)
	{
		$studentData = $this->hydrator->extract($studentObject);
		unset($studentData['id']);
		unset($studentData['organisation_Id']);
		unset($studentData['employee_Details_Id']);
		unset($studentData['from_Date']);
		unset($studentData['to_Date']);
		unset($studentData['status']);
		
		//the following are unset as they are part of the Suggestion Category Model
		unset($studentData['suggestion_Category']);
		
		if($studentObject->getId()) {
			//ID present, so it is an update
			$action = new Update('student_suggestion');
			$action->set($studentData);
			$action->where(array('id = ?' => $studentObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('student_suggestion');
			$action->values($studentData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $studentObject->setId($newId);
			}
			return $studentObject;
		}
		
		throw new \Exception("Database Error");
	}


	public function saveSuggestionCommittee(SuggestionCommittee $studentObject, $employeeDetailsId)
	{
		$studentData = $this->hydrator->extract($studentObject);
		unset($studentData['id']);

		$studentData['employee_Details_Id'] = $employeeDetailsId;
		$studentData['status'] = 'Active';

		$studentData['from_Date'] = date("Y-m-d", strtotime(substr($studentData['from_Date'],0,10)));
		$studentData['to_Date'] = date("Y-m-d", strtotime(substr($studentData['to_Date'],0,10)));
		
		if($studentObject->getId()) {
			//ID present, so it is an update
			$action = new Update('student_suggestion_committee');
			$action->set($studentData);
			$action->where(array('id = ?' => $studentObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('student_suggestion_committee');
			$action->values($studentData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $studentObject->setId($newId);
			}
			return $studentObject;
		}
		
		throw new \Exception("Database Error");
	}


	public function listAllSuggestionCommitteeList($tableName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => $tableName))
			   ->join(array('t2' => 'employee_details'),
					't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
			   ->join(array('t3' => 'student_suggestion_category'),
					't3.id = t1.student_suggestion_category_id', array('suggestion_category'))
			   ->join(array('t4' => 'organisation'),
					't4.id = t2.organisation_id', array('organisation_name'))
			   ->where(array('t3.organisation_id' => $organisation_id))
			   ->order('id DESC');

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
	}


	public function listStudentSuggestionToCommittee($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'student_suggestion'))
			   ->join(array('t2' => 'student_suggestion_category'),
					't2.id = t1.student_suggestion_category_id', array('suggestion_category'))
			   ->join(array('t3' => 'student_suggestion_committee'),
					't2.id = t3.student_suggestion_category_id', array('employee_details_id'))
			   ->join(array('t4' => 'organisation'),
					't4.id = t2.organisation_id', array('organisation_name'))
			   ->where(array('t3.employee_details_id' => $employee_details_id, 't3.status' => 'Active'))
			   ->order('id DESC');

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
	}


	public function getPostedCommitteeSuggestionDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'student_suggestion'))
			   ->join(array('t2' => 'student_suggestion_category'),
					't2.id = t1.student_suggestion_category_id', array('suggestion_category'))
			   ->join(array('t3' => 'student_suggestion_committee'),
					't2.id = t3.student_suggestion_category_id', array('employee_details_id'))
			   ->join(array('t4' => 'organisation'),
					't4.id = t2.organisation_id', array('organisation_name'))
			   ->where(array('t1.id' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function crossCheckSuggestionCommitteeMember($suggestionCategory, $employeeDetailsId)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

    	$select->from(array('t1' => 'student_suggestion_committee'))
		       ->columns(array('student_suggestion_category_id'))
	   	       ->where(array('t1.student_suggestion_category_id' => $suggestionCategory, 't1.employee_details_id' => $employeeDetailsId, 't1.status' => 'Active')); 
       
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
			
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
			
		$suggestionCommittee = NULL;
		foreach($resultSet as $set)
		{
			$suggestionCommittee = $set['student_suggestion_category_id'];
		}
		return $suggestionCommittee;
	}

	public function crossCheckSuggestionCommittee($id, $suggestionCategory, $employeeDetailsId)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

    	$select->from(array('t1' => 'student_suggestion_committee'))
		       ->columns(array('student_suggestion_category_id'))
	   	       ->where(array('t1.student_suggestion_category_id' => $suggestionCategory, 't1.employee_details_id' => $employeeDetailsId, 't1.status' => 'Active', 't1.id != ?' => $id)); 
       
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
			
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
			
		$suggestionCommittee = NULL;
		foreach($resultSet as $set)
		{
			$suggestionCommittee = $set['student_suggestion_category_id'];
		}
		return $suggestionCommittee;
	}

	
	/**
	 * 
	 * @param type $StudentSuggestionsInterface
	 * 
	 * to save StudentSuggestions Details
	 */
	
	public function saveCategory(SuggestionCategory $studentObject)
	{

		$studentData = $this->hydrator->extract($studentObject);
		unset($studentData['id']);
		
		//the following are unset as they are part of the Suggestion Model
		unset($studentData['subject']);
		unset($studentData['suggestion']);
		unset($studentData['suggestion_Category_Id']);
		
		
		if($studentObject->getId()) {
			//ID present, so it is an update
			$action = new Update('student_suggestion_category');
			$action->set($studentData);
			$action->where(array('id = ?' => $studentObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('student_suggestion_category');
			$action->values($studentData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $studentObject->setId($newId);
			}
			return $studentObject;
		}
		
		throw new \Exception("Database Error");
	}

	public function updateSuggestionCommitteeStatus($status, $previousStatus, $id)
	{
		$studentData['status'] = $status;

		$action = new Update('student_suggestion_committee');
		$action->set($studentData);
		if($previousStatus  != NULL){
			$action->where(array('status = ?' => $previousStatus));
		} elseif($id != NULL){
			$action->where(array('id = ?' => $id));
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		return;
	}


	public function getSuggestionCommitteeDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select('student_suggestion_committee');
        $select->where(array('id = ? ' => $id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();


        if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->studentPrototype);
            }

            throw new \InvalidArgumentException("Suggestion Committee with given ID: ($id) not found");
	}


	public function getCommitteDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		//using tiral students. Change it to proper table after testing
		$select->from(array('t1' => 'student_suggestion_committee'))
			   ->join(array('t2' => 'employee_details'),
					't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id', 'organisation_id'))
			   ->join(array('t3' => 'organisation'),
					't3.id = t2.organisation_id', array('organisation_name'))
			   ->where(array('t1.id' => $id));		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
	}
	
	/*
	* List Student to add awards etc
	*/
	
	public function getStudentList($studentName, $studentId, $programme)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		//using tiral students. Change it to proper table after testing
		$select->from(array('t1' => 'trial_student'));
		
		if($studentName){
			$select->where->like('student_name','%'.$studentName.'%');
		}
		if($studentId){
			$select->where(array('student_id' =>$studentId));
		}
		if($programme){
			$select->where(array('programme' =>$programme));
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
		$select->from(array('t1' => 'trial_student')) // base table
				->where('t1.id = ' .$id); // join expression
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
		
	}
	
	/*
	* Get list of employee to assign to committee
	*/
	
	public function getEmployeeList($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details')) ;
		$select->columns(array('id','first_name', 'middle_name','last_name'))
				->where('t1.organisation_id = ' .$organisation_id);
				
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$selectData = array();
		foreach($resultSet as $set){
			$selectData[$set['id']] = $set['first_name']. ' ' .$set['middle_name']. ' ' .$set['last_name'];
		}
		return $selectData;
	}
	
	
	 /**
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array('id',$columnName))
			   ->where(array('t1.organisation_id' => $organisation_id)); 

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
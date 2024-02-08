<?php

namespace Achievements\Mapper;

use Achievements\Model\Achievements;
use Achievements\Model\AchievementsCategory;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements AchievementsMapperInterface
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
	 * @var \Achievements\Model\AchievementsInterface
	*/
	protected $achievementPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			\stdClass $achievementPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->achievementPrototype = $achievementPrototype;
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
		$select->columns(array('organisation_id', 'departments_id'));
			
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
	* @return Achievements
	* @throws \InvalidArgumentException
	*/
	
	public function findAchievements($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select('student_achievements');
		$select->where(array('id = ? ' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
				return $this->hydrator->hydrate($result->current(), $this->achievementPrototype);
		}

		throw new \InvalidArgumentException("Achievements Proposal with given ID: ($id) not found");
	}
	
	/**
	* @return array/Achievements()
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
        

	/**
	 * 
	 * @param type $AchievementsInterface
	 * 
	 * to save Achievements Details
	 */
	
	public function saveDetails(Achievements $achievementObject)
	{
		$achievementData = $this->hydrator->extract($achievementObject);
		unset($achievementData['id']);
                
        //need to get the file locations and store them in database
		$evidence_file_name = $achievementData['evidence_File'];
		$achievementData['evidence_File'] = $evidence_file_name['tmp_name'];
		
		if($achievementObject->getId()) {
			//ID present, so it is an update
			$action = new Update('student_achievements');
			$action->set($achievementData);
			$action->where(array('id = ?' => $achievementObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('student_achievements');
			$action->values($achievementData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $achievementObject->setId($newId);
			}
			return $achievementObject;
		}
		
		throw new \Exception("Database Error");
	}


	public function updateAchievements(Achievements $achievementObject)
	{
		$achievementData = $this->hydrator->extract($achievementObject);
                
                //need to get the file locations and store them in database
		$evidence_file_name = $achievementData['evidence_File'];
		$achievementData['evidence_File'] = $evidence_file_name['tmp_name'];
		
		//ID present, so it is an update
		$action = new Update('student_achievements');
		$action->set($achievementData);
		$action->where(array('id = ?' => $achievementData['id']));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
	}
	
	/*
	* To save the various achievements categories
	*/
	 
	public function saveAchievementsCategory(AchievementsCategory $achievementObject)
	{
		$achievementData = $this->hydrator->extract($achievementObject);
		unset($achievementData['id']);

		
		if($achievementObject->getId()) {
			//ID present, so it is an update
			$action = new Update('student_achievements_category');
			$action->set($achievementData);
			$action->where(array('id = ?' => $achievementObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('student_achievements_category');
			$action->values($achievementData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $achievementObject->setId($newId);
			}
			return $achievementObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	 * Get a list of all the achievements 
	 **/
	 
	public function getAchievements($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_achievements')) 
					->join(array('t2' => 'student'), 
                            't1.student_id = t2.id', array('first_name','middle_name','last_name','student_id','enrollment_year'))
					->join(array('t3' => 'programmes'), 
                            't2.programmes_id = t3.id', array('programme_name'))
					->join(array('t4' => 'student_achievements_category'),
							't4.id = t1.achievement_name', array('achievement_name'))
					->where(array('t1.organisation_id' =>$organisation_id));
		
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
		$select->where(array('t1.student_status_type_id' => '1'));
		
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
	* Get the list of achievements by students after search funcationality
	*/
	
	public function getStudentAchievementList($studentName, $studentId, $programme, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'student_achievements'))
					->columns(array('achievement_name','remarks'))
                    ->join(array('t2' => 'student'), 
                            't1.student_id = t2.id', array('id','first_name','middle_name','last_name','student_id','enrollment_year'))
					->join(array('t3' => 'programmes'), 
                            't2.programmes_id = t3.id', array('programme_name'))
					->join(array('t4' => 'student_achievements_category'),
							't4.id = t1.achievement_name', array('achievement_name'));
		
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
			$select->where(array('t1.organisation_id' =>$organisation_id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
	}
	
	/*
	* Get the list of achievements by a student
	*/
	
	public function getStudentAchievements($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'student_achievements'))
					->columns(array('id','remarks', 'student_id', 'organisation_id', 'evidence_file'))
                    ->join(array('t2' => 'student'), 
                            't1.student_id = t2.id', array('id','first_name','middle_name','last_name','student_id','enrollment_year'))
					->join(array('t3' => 'programmes'), 
                            't2.programmes_id = t3.id', array('programme_name'))
					->join(array('t4' => 'student_achievements_category'),
							't4.id = t1.achievement_name', array('achievement_name'));
		$select->where(array('t1.student_id' =>$student_id));

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
	public function getStudentDetails($tableName, $id) 
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'student'){
			$select->from(array('t1' => $tableName)) // base table
			   ->join(array('t2' => 'programmes'),
					't2.id = t1.programmes_id', array('programme_name'))
			   ->where(array('t1.id = ' .$id)); // join expression
		}

		if($tableName == 'student_achievements'){
			$select->from(array('t1' => $tableName)) // base table
				->join(array('t2' => 'student'),
					't2.id = t1.student_id', array('id', 'first_name', 'middle_name', 'last_name', 'student_id'))
			   ->join(array('t3' => 'programmes'),
					't3.id = t2.programmes_id', array('programme_name'))
			   ->where(array('t1.id = ' .$id));
		}
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);	
	}


	public function getAchievementsCategoryDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student_achievements_category')) // base table
				->where(array('t1.id = ' .$id)); // join expression
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}

	public function getStudentDetail($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student_achievements'))
			   ->join(array('t2' => 'student'),
			   		't2.id = t1.student_id', array('first_name', 'middle_name', 'last_name', 'student_id', 'programmes_id')) // base table
			   ->join(array('t3' => 'programmes'),
					't3.id = t2.programmes_id', array('programme_name'))
				->where(array('t1.id = ' .$id)); // join expression
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getStudentAchievementDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student_achievements')); // base table
		$select->where(array('t1.id = ? ' => $id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getFileName($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student_achievements')) 
				->where(array('t1.id' => $id));
		
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



	
	 /**
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'student_achievements_category'){
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
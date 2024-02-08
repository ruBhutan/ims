<?php

namespace Clubs\Mapper;

use Clubs\Model\Clubs;
use Clubs\Model\ClubsApplication;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements ClubsMapperInterface
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
	 * @var \Clubs\Model\ClubsInterface
	*/
	protected $clubsPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			Clubs $clubsPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->clubsPrototype = $clubsPrototype;
	}
	
	/**
	* @param int/String $id
	* @return Clubs
	* @throws \InvalidArgumentException
	*/
	
	public function findClubs($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select('clubs');
		$select->where(array('id = ? ' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function crossCheckClubApplication($student_id, $id)
	{
		$status = 'Rejected';

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student_club_applications'));
		$select->where(array('t1.student_id = ? ' => $student_id, 't1.clubs_id' => $id));
		$select->where(array('t1.status != ? ' => $status));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$club_application = NULL;
		foreach ($resultSet as $set) {
			$club_application = $set['id'];
		}

		return $club_application;
	}
	
	/**
	* @return array/Clubs()
	*/
	public function findAll($tableName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->where(array('organisation_id = ?' => $organisation_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
        
	/**
	 * 
	 * @param type $id
	 * 
	 * to find the Objectives for a given $id
	 */
	 
	public function findStudentClubs($id) 
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student_club_applications'));
		$select->where(array('id = ?' => $id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if ($result instanceof ResultInterface && $result->isQueryResult()) {

			$resultSet = new ResultSet();
			$resultSet->initialize($result);

			$resultSet = new HydratingResultSet($this->hydrator, $this->clubsPrototype);
			$resultSet->buffer();
			return $resultSet->initialize($result); 
		}
		
		return array();
	}
		
	/**
	 * 
	 * @param type $ClubsInterface
	 * 
	 * to save Vision and Mission Details
	 */
	
	public function saveDetails(Clubs $clubsObject)
	{
		$clubsData = $this->hydrator->extract($clubsObject);
		unset($clubsData['id']);
		
		//need to unset as this is for another table
		unset($clubsData['date']);
		unset($clubsData['student_Id']);
		unset($clubsData['clubs_Id']);
		
		if($clubsObject->getId()) {
			//ID present, so it is an update
			$action = new Update('clubs');
			$action->set($clubsData);
			$action->where(array('id = ?' => $clubsObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('clubs');
			$action->values($clubsData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $clubsObject->setId($newId);
			}
			return $clubsObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	
	/**
	 * 
	 * @param type $ClubsInterface
	 * 
	 * to save Objectives Details
	 */
	
	public function saveClubApplications(ClubsApplication $clubsObject)
	{
		$clubsData = $this->hydrator->extract($clubsObject);
		unset($clubsData['id']);
		
		if($clubsObject->getId()) {
			//ID present, so it is an update
			$action = new Update('student_club_applications');
			$action->set($clubsData);
			$action->where(array('id = ?' => $clubsObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('student_club_applications');
			$action->values($clubsData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $clubsObject->setId($newId);
			}
			return $clubsObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* take username and returns employee details id/student id
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
	
	/*
	 * Get the Organisation Id
	 */
	 
	public function getOrganisationId($username, $usertype)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($usertype == 1){
			$select->from(array('t1' => 'employee_details'));
			$select->where(array('emp_id' =>$username));
			$select->columns(array('organisation_id'));
		}

		else if($usertype == '2'){
			$select->from(array('t1' => 'student'));
			$select->where(array('student_id' =>$username));
			$select->columns(array('organisation_id'));
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
				->where('t1.id = ' .$id); // join expression
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
		
	}
	
	/*
	* List Club Applications and their status
	*/
	 
	public function listClubApplications($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student_club_applications')) 
                    ->join(array('t2' => 'student'), 
                            't1.student_id = t2.id', array('first_name','middle_name','last_name'))
                    ->join(array('t3'=>'clubs'),
                            't1.clubs_id = t3.id', array('club_name'))
					->where(array('t1.status' =>'Pending', 't2.organisation_id' => $organisation_id));                
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* List Club Members
	*/
	 
	public function listClubMembers($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student_clubs')) 
                    ->join(array('t2' => 'student'), 
                            't1.student_id = t2.id', array('first_name','middle_name','last_name'))
                    ->join(array('t3'=>'clubs'),
                            't1.clubs_id = t3.id', array('club_name'));
        $select->where(array('t2.organisation_id' => $organisation_id));
       // a where statement needs to be added when to get list for club coordinators
	                
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getStudentClubMembership($clubs_id, $tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'student_club_applications'){
			$select->from(array('t1' => $tableName)) 
                    ->join(array('t2' => 'student'), 
                            't1.student_id = t2.id', array('first_name','middle_name','last_name'))
                    ->join(array('t3'=>'clubs'),
                            't1.clubs_id = t3.id', array('club_name'))
					->where(array('t1.status' =>'Pending', 't1.clubs_id' => $clubs_id));  
		}

		if($tableName == 'student_clubs'){
			$select->from(array('t1' => $tableName)) 
                    ->join(array('t2' => 'student'), 
                            't1.student_id = t2.id', array('first_name','middle_name','last_name'))
                    ->join(array('t3'=>'clubs'),
                            't1.clubs_id = t3.id', array('club_name'));
        	$select->where(array('t1.clubs_id' => $clubs_id)); 
		}	                
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Approve/Reject Club Application
	*/
	
	public function submitClubApplication($application_id, $status)
	{
		//need to update the status of the club applications
		$clubsData['status'] = $status;
		$action = new Update('student_club_applications');
		$action->set($clubsData);
		$action->where(array('id = ?' => $application_id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		//once the clu status is updated, need to add the student to the club member tables
		
		$studentClubDetails = $this->findStudentClubs($application_id);
		
		$clubDetails = array();
		foreach($studentClubDetails as $details)
		{
			$clubDetails['student_id'] = $details->getStudent_Id();
			$clubDetails['clubs_id'] = $details->getClubs_Id();
		}
		
		if($clubsData['status'] == 'Approved'){
			$insert = new Insert('student_clubs');
			$insert->values(array(
					'date'=> date('Y-m-d'),
					'student_id' => $clubDetails['student_id'],
					'clubs_id' => $clubDetails['clubs_id'],
				));
					
			$sql2 = new Sql($this->dbAdapter);
			$stmt2 = $sql2->prepareStatementForSqlObject($insert);
			$result2 = $stmt2->execute();
		}	

		return;
	}
	
	/**
	* @return array/Clubs()
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'clubs'){
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


	// delete club
	
	public function deleteClub($id)
	{
		

			if ($id != null) 
			{
				$action = new Delete('clubs');

                $action->where(array('id = ?' => $id));

                $sqlDelete = new Sql($this->dbAdapter);
                $stmtDelete = $sqlDelete->prepareStatementForSqlObject($action);
				$resultDelete = $stmtDelete->execute();

				if($resultDelete)

					return true;

			}

		return false;
	}

        
}
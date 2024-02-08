<?php

namespace ExtraCurricularAttendance\Mapper;

use ExtraCurricularAttendance\Model\ExtraCurricularAttendance;
use ExtraCurricularAttendance\Model\SocialEvent;
use ExtraCurricularAttendance\Model\ClubAttendance;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements ExtraCurricularAttendanceMapperInterface
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
	 * @var \ExtraCurricularAttendance\Model\ExtraCurricularAttendanceInterface
	*/
	protected $attendancePrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			ExtraCurricularAttendance $attendancePrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->attendancePrototype = $attendancePrototype;
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
		$select->columns(array('id','organisation_id'));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
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
	* @return ExtraCurricularAttendance
	* @throws \InvalidArgumentException
	*/
	
	public function findAttendance($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select('hr_development');
		$select->where(array('id = ? ' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
				return $this->hydrator->hydrate($result->current(), $this->attendancePrototype);
		}

		throw new \InvalidArgumentException("ExtraCurricularAttendance Proposal with given ID: ($id) not found");
	}
	
	/*
	* Get Social Event Details
	*/
	
	public function getSocialEvent($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'social_events')) // base table
				->where('t1.id = ' .$id); 
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/**
	* @return array/ExtraCurricularAttendance()
	*/
	public function findAll($tableName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->where(array('t1.organisation_id' => $organisation_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

	}
        		
	/**
	 * 
	 * @param type $ExtraCurricularAttendanceInterface
	 * 
	 * to save Vision and Mission Details
	 */
	
	public function saveExtraCurricularAttendance($data, $programme, $year, $studentName, $studentId, $event_name, $date_event, $organisation_id)
	{ 
		//get the student list
		$i=1;
		$studentIds = array();
		$studentList = $this->getStudentList($studentName, $studentId, $programme, $year, $organisation_id);
		foreach($studentList as $value){
			$studentIds[$i++] = $value['id'];
		}
		
		//the following loop is to insert attendance
		if($data != NULL)
		{
			$i = 1;
			foreach($data as $value)
			{
				$action = new Insert('student_extracurricular_attendance');
				$action->values(array(
					'social_events_id' => $event_name,
					'attendance' => $value,
					'student_id' => $studentIds[$i]
				));
			
				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
				$i++;
			}
			return;
		}
		
		throw new \Exception("Database Error");
	}


	public function updateExtraCurricularAttendance($data, $programme, $year, $studentName, $studentId, $event_name, $date_event, $organisation_id)
	{
		//get the student list
		$i=1;
		$studentIds = array();
		$studentList = $this->getStudentExtraCurricularAttendance($studentName, $studentId, $programme, $year, $event_name, $organisation_id);
		foreach($studentList as $value){
			$studentIds[$i++] = $value['id'];
		}
		
		//the following loop is to insert attendance
		if($data != NULL)
		{
			$i = 1;
			foreach($data as $value)
			{
				$studentAttendanceData['attendance'] = $value;

				$action = new Update('student_extracurricular_attendance');
				$action->set($studentAttendanceData);
                $action->where(array('id = ?' => $studentIds[$i]));
			
				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
				$i++;
			}
			return;
		}
		
		throw new \Exception("Database Error");
	}
	
	
	/**
	 * 
	 * @param type $ExtraCurricularAttendanceInterface
	 * 
	 * to save Objectives Details
	 */
	
	public function saveClubAttendance($data, $clubs_id, $date, $organisation_id)
	{
		//get the student list
		$i=1;
		$studentIds = array();
		$studentData = $this->getStudentClubList($clubs_id, $organisation_id);
		foreach($studentData as $value){
			$studentIds[$i++] = $value['id'];
		} 
		
		//the following loop is to insert attendance
		if($data != NULL)
		{
			$i = 1;
			foreach($data as $value)
			{
				$action = new Insert('student_club_attendance');
				$action->values(array(
					'date' => $date,
					'attendance' => $value,
					'student_clubs_id' => $studentIds[$i]
				));
			
				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
				$i++;
			}
			return;
		}
		
		throw new \Exception("Database Error"); 
	}


	public function updateClubAttendance($data, $clubsId, $date, $organisation_id)
	{
		//get the student list
		$i=1;
		$studentIds = array();
		$studentList = $this->getStudentClubAttendance($clubsId, $organisation_id, $date);
		foreach($studentList as $value){
			$studentIds[$i++] = $value['id'];
		} 

		//the following loop is to insert attendance
		if($data != NULL)
		{
			$i = 1;
			foreach($data as $value)
			{
				$clubAttendanceData['attendance'] = $value;

				$action = new Update('student_club_attendance');
				$action->set($clubAttendanceData);
                $action->where(array('id = ?' => $studentIds[$i]));
			
				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
				$i++;
			}
			return;
		}
		
		throw new \Exception("Database Error"); 
	}
	
	/*
	* Save Social Event
	*/
	
	public function saveSocialEvent(SocialEvent $eventObject)
	{
		$eventData = $this->hydrator->extract($eventObject);
		unset($eventData['id']);

		$eventData['date'] = date("Y-m-d", strtotime(substr($eventData['date'], 0,10)));
		
		if($eventObject->getId()) {
			//ID present, so it is an update
			$action = new Update('social_events');
			$action->set($eventData);
			$action->where(array('id = ?' => $eventObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('social_events');
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


	public function crossCheckExtraCurricularAttendance($programme, $year, $event_name)
	{
		$academic_year = NULL;
		$month = date('m'); 
		if($month >= 1 && $month <= 6){
			$start_year = date('Y')-1;
			$end_year = date('Y');
			$academic_year = $start_year.'-'.$end_year;
		} else{
			$start_year = date('Y');
			$end_year = date('Y')+1;
			$academic_year = $start_year.'-'.$end_year;
		}

		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'student_extracurricular_attendance'))
        	   ->join(array('t2' => 'student'),
        			't2.id = t1.student_id', array('programmes_id', 'id'))
        	   ->join(array('t3' => 'student_semester_registration'),
        			't2.id = t3.student_id', array('year_id'));
        $select->where(array('t1.social_events_id' => $event_name, 't2.programmes_id' => $programme, 't3.year_id' => $year, 't3.academic_year' => $academic_year));
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $studentID = array();
        foreach($resultSet as $set){
            $studentID[] = $set['id'];
        }
       return $studentID; 
	}


	public function crossCheckClubMembers($clubId, $organisation_id)
	{
		$current_academic_session = $this->getAcademicSession($organisation_id);

		$academic_year = NULL;
		$month = date('m'); 
		if($month >= 1 && $month <= 6){
			$start_year = date('Y')-1;
			$end_year = date('Y');
			$academic_year = $start_year.'-'.$end_year;
		} else{
			$start_year = date('Y');
			$end_year = date('Y')+1;
			$academic_year = $start_year.'-'.$end_year;
		}

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
				
		$select->from(array('t1' => 'student_clubs')) 
                    ->join(array('t2' => 'student'), 
                            't1.student_id = t2.id', array('first_name','middle_name','last_name', 'studentId' => 'student_id'))
                    ->join(array('t3' => 'student_semester_registration'),
                			't3.student_id = t2.id', array('year_id', 'academic_year', 'academic_session_id'))
                    ->join(array('t4' => 'programmes'),
                			't4.id = t2.programmes_id', array('programme_name'))
                    ->join(array('t5' => 'programme_year'),
                			't5.id = t3.year_id', array('year'))
                    ->where(array('t1.clubs_id = ' .$clubId))
					->where(array('t2.organisation_id = ' .$organisation_id, 't3.academic_session_id' => $current_academic_session, 't3.academic_year' => $academic_year, 't2.student_status_type_id != ?' => '7'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result); 

		$club_student = array();
        foreach($resultSet as $set){
            $club_student[] = $set['id'];
        }

       return $club_student; 
	}


	public function crossCheckClubAttendance($student_clubs_members, $attendance_date)
	{  
		$club_attendance = array();

		foreach($student_clubs_members as $student_clubs_id){
			$sql = new Sql($this->dbAdapter);
			$select = $sql->select();

			$select->from(array('t1' => 'student_club_attendance')) 
						->where(array('t1.student_clubs_id = ' .$student_clubs_id, 't1.date' => $attendance_date));

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result); 

	        foreach($resultSet as $set){
	            $club_attendance[] = $set['id'];
	        }
		}
       return $club_attendance; 
	}
	
	/*
	* List Student to add awards etc
	*/
	
	public function getStudentList($studentName, $studentId, $programme, $year, $organisation_id)
	{
		$current_academic_session = $this->getAcademicSession($organisation_id);

		$academic_year = NULL;
		$month = date('m'); 
		if($month >= 1 && $month <= 6){
			$start_year = date('Y')-1;
			$end_year = date('Y');
			$academic_year = $start_year.'-'.$end_year;
		} else{
			$start_year = date('Y');
			$end_year = date('Y')+1;
			$academic_year = $start_year.'-'.$end_year;
		}

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'student'))
			   ->join(array('t2' => 'student_semester_registration'),
					't1.id = t2.student_id', array('year_id'))
			    ->join(array('t3' => 'programmes'),
					't3.id = t1.programmes_id', array('programme_name'))
			   ->where(array('t2.academic_session_id' => $current_academic_session, 't1.student_status_type_id != ?' => '7', 't2.academic_year' => $academic_year));
		
		if($studentName){
			$select->where->like('t1.first_name','%'.$studentName.'%');
		}
		if($studentId){
			$select->where(array('t1.student_id' =>$studentId));
		}
		if($programme){
			$select->where(array('t1.programmes_id = ? ' => $programme));
		}
		if($year){
			$select->where(array('t2.year_id' => $year));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 

	}
	
	/*
	* Count of the students
	*/
	
	public function getStudentCount($studentName, $studentId, $programme, $year, $organisation_id)
	{
		$current_academic_session = $this->getAcademicSession($organisation_id);

		$academic_year = NULL;
		$month = date('m'); 
		if($month >= 1 && $month <= 6){
			$start_year = date('Y')-1;
			$end_year = date('Y');
			$academic_year = $start_year.'-'.$end_year;
		} else{
			$start_year = date('Y');
			$end_year = date('Y')+1;
			$academic_year = $start_year.'-'.$end_year;
		}

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'student'))
			   ->join(array('t2' => 'student_semester_registration'),
					't1.id = t2.student_id', array('year_id'))
			   ->join(array('t3' => 'programmes'),
					't3.id = t1.programmes_id', array('programme_name'))
			   ->where(array('t2.academic_session_id' => $current_academic_session, 't1.student_status_type_id != ?' => '7', 't2.academic_year' => $academic_year));
		
		if($studentName){
			$select->where->like('t1.first_name','%'.$studentName.'%');
		}
		if($studentId){
			$select->where(array('t1.student_id' =>$studentId));
		}
		if($programme){
			$select->where(array('t1.programmes_id = ? ' => $programme));
		}
		if($year){
			$select->where(array('t2.year_id' =>$year));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		return $result->count();
	}


	public function getExtraCurricularAttendanceList($programme, $year, $event_name, $organisation_id)
	{
		$current_academic_session = $this->getAcademicSession($organisation_id);

		$academic_year = NULL;
		$month = date('m'); 
		if($month >= 1 && $month <= 6){
			$start_year = date('Y')-1;
			$end_year = date('Y');
			$academic_year = $start_year.'-'.$end_year;
		} else{
			$start_year = date('Y');
			$end_year = date('Y')+1;
			$academic_year = $start_year.'-'.$end_year;
		}

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_extracurricular_attendance'))
			   ->columns(array('id', 'student_id', 'attendance', 'social_events_id'))
				->join(array('t2' => 'student'), 
						't1.student_id = t2.id', array('first_name', 'middle_name', 'last_name', 'studentId' => 'student_id'))
				->join(array('t3' => 'student_semester_registration'), 
						't3.student_id = t2.id', array('academic_year', 'academic_session_id', 'year_id'));
		$select->where(array('t1.social_events_id' => $event_name, 't2.programmes_id' => $programme, 't3.academic_year' => $academic_year, 't3.academic_session_id' => $current_academic_session, 't3.year_id' => $year));
		
		$stmt = $sql->prepareStatementForSqlObject($select);		
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getStudentExtraCurricularAttendance($studentName, $studentId, $programme, $year, $event_name, $organisation_id)
	{
		$current_academic_session = $this->getAcademicSession($organisation_id); 

		$academic_year = NULL;
		$month = date('m'); 
		if($month >= 1 && $month <= 6){
			$start_year = date('Y')-1;
			$end_year = date('Y');
			$academic_year = $start_year.'-'.$end_year;
		} else{
			$start_year = date('Y');
			$end_year = date('Y')+1;
			$academic_year = $start_year.'-'.$end_year;
		}

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'student_extracurricular_attendance'))
			   ->join(array('t2' => 'student'),
					't2.id = t1.student_id', array('first_name', 'middle_name', 'last_name', 'studentId' => 'student_id', 'enrollment_year'))
			    ->join(array('t3' => 'programmes'),
					't3.id = t2.programmes_id', array('programme_name'))
			    ->join(array('t4' => 'student_semester_registration'),
						't2.id = t4.student_id', array('year_id', 'academic_session_id'))
			   ->where(array('t4.academic_session_id' => $current_academic_session, 't2.student_status_type_id != ?' => '7', 't1.social_events_id' => $event_name, 't4.academic_year' => $academic_year));

		if($studentName){
			$select->where->like('t2.first_name','%'.$studentName.'%');
		}
		if($studentId){
			$select->where(array('t2.student_id' =>$studentId));
		}
		if($programme){
			$select->where(array('t2.programmes_id = ? ' => $programme));
		}
		if($year){
			$select->where(array('t4.year_id' =>$year));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
	}
	
	/*
	* List Students in clubs
	*/
	
	public function getStudentClubList($clubId, $organisation_id)
	{
		$current_academic_session = $this->getAcademicSession($organisation_id);

		$academic_year = NULL;
		$month = date('m'); 
		if($month >= 1 && $month <= 6){
			$start_year = date('Y')-1;
			$end_year = date('Y');
			$academic_year = $start_year.'-'.$end_year;
		} else{
			$start_year = date('Y');
			$end_year = date('Y')+1;
			$academic_year = $start_year.'-'.$end_year;
		}

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
				
		$select->from(array('t1' => 'student_clubs')) 
                    ->join(array('t2' => 'student'), 
                            't1.student_id = t2.id', array('first_name','middle_name','last_name', 'studentId' => 'student_id'))
                    ->join(array('t3' => 'student_semester_registration'),
                			't3.student_id = t2.id', array('year_id', 'academic_year', 'academic_session_id'))
                    ->join(array('t4' => 'programmes'),
                			't4.id = t2.programmes_id', array('programme_name'))
                    ->join(array('t5' => 'programme_year'),
                			't5.id = t3.year_id', array('year'))
                    ->where(array('t1.clubs_id = ' .$clubId))
					->where(array('t2.organisation_id = ' .$organisation_id, 't3.academic_session_id' => $current_academic_session, 't3.academic_year' => $academic_year, 't2.student_status_type_id != ?' => '7'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
	}


	public function getStudentClubAttendance($clubId, $organisation_id, $attendance_date)
	{
		$current_academic_session = $this->getAcademicSession($organisation_id); 

		$academic_year = NULL;
		$month = date('m'); 
		if($month >= 1 && $month <= 6){
			$start_year = date('Y')-1;
			$end_year = date('Y');
			$academic_year = $start_year.'-'.$end_year;
		} else{
			$start_year = date('Y');
			$end_year = date('Y')+1;
			$academic_year = $start_year.'-'.$end_year;
		}

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'student_club_attendance'))
			   ->join(array('t2' => 'student_clubs'),
					't2.id = t1.student_clubs_id', array('student_id', 'clubs_id'))
			   ->join(array('t3' => 'student'),
					't3.id = t2.student_id', array('first_name', 'middle_name', 'last_name', 'studentId' => 'student_id'))
			    ->join(array('t4' => 'programmes'),
					't4.id = t3.programmes_id', array('programme_name'))
			    ->join(array('t5' => 'student_semester_registration'),
						't3.id = t5.student_id', array('year_id', 'academic_session_id'))
			    ->join(array('t6' => 'clubs'),
						't6.id = t2.clubs_id', array('club_name'))
			    ->join(array('t7' => 'programme_year'),
                			't7.id = t5.year_id', array('year'))
			   ->where(array('t5.academic_session_id' => $current_academic_session, 't3.student_status_type_id != ?' => '7', 't2.clubs_id' => $clubId, 't5.academic_year' => $academic_year, 't1.date' => $attendance_date));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
	}
	
	/*
	* Get No. of Students in clubs
	*/
	
	public function getStudentClubCount($clubId, $organisation_id)
	{
		$current_academic_session = $this->getAcademicSession($organisation_id);

		$academic_year = NULL;
		$month = date('m'); 
		if($month >= 1 && $month <= 6){
			$start_year = date('Y')-1;
			$end_year = date('Y');
			$academic_year = $start_year.'-'.$end_year;
		} else{
			$start_year = date('Y');
			$end_year = date('Y')+1;
			$academic_year = $start_year.'-'.$end_year;
		}

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
				
		$select->from(array('t1' => 'student_clubs')) 
                    ->join(array('t2' => 'student'), 
                            't1.student_id = t2.id', array('first_name','middle_name','last_name', 'studentId' => 'student_id'))
                    ->join(array('t3' => 'student_semester_registration'),
                			't3.student_id = t2.id', array('year_id', 'academic_year', 'academic_session_id'))
                    ->join(array('t4' => 'programmes'),
                			't4.id = t2.programmes_id', array('programme_name'))
                    ->join(array('t5' => 'programme_year'),
                			't5.id = t3.year_id', array('year'))
                    ->where(array('t1.clubs_id = ' .$clubId))
					->where(array('t2.organisation_id = ' .$organisation_id, 't3.academic_session_id' => $current_academic_session, 't3.academic_year' => $academic_year, 't2.student_status_type_id != ?' => '7'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		return $result->count();
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
	
	/**
	* @return array/ExtraCurricularAttendance()
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		$academic_year = NULL;
		$month = date('m'); 
		if($month >= 1 && $month <= 6){
			$start_year = date('Y')-1;
			$end_year = date('Y');
			$academic_year = $start_year.'-'.$end_year;
		} else{
			$start_year = date('Y');
			$end_year = date('Y')+1;
			$academic_year = $start_year.'-'.$end_year;
		}

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'social_events'){
			$select->from(array('t1' => $tableName));
			$select->columns(array('id',$columnName));
			$select->where(array('t1.organisation_id = ' .$organisation_id, 't1.academic_year' => $academic_year));
		}
		else if($organisation_id == NULL){
			$select->from(array('t1' => $tableName));
			$select->columns(array('id',$columnName));
		}
		else{
			$select->from(array('t1' => $tableName));
			$select->columns(array('id',$columnName));
			$select->where(array('organisation_id = ' .$organisation_id));
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


	/*
     * Get the semester from the database
     */
    
    public function getAcademicSession($organisation_id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'academic_calendar'))
                    ->columns(array('academic_year'))
                ->join(array('t2' => 'academic_calendar_events'), 
                        't1.academic_event = t2.id', array('academic_event', 'academic_session_id'));
        $select->where(array('from_date <= ? ' => date('Y-m-d')));
        $select->where(array('to_date >= ? ' => date('Y-m-d')));
        $select->where('t2.organisation_id = ' .$organisation_id);
                
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
        $academic_session = NULL;
        
        foreach($resultSet as $set){
            if($set['academic_event'] == 'Start of Autumn Semester'){
                $academic_session = $set['academic_session_id'];
            }
            else if($set['academic_event'] == 'Start of Spring Semester'){
                $academic_session = $set['academic_session_id'];
            }
        }
        return $academic_session;
    }
        
}
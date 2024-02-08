<?php

namespace StudentAttendance\Mapper;

use StudentAttendance\Model\StudentAttendance;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements StudentAttendanceMapperInterface
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
	 * @var \StudentAttendance\Model\StudentAttendanceInterface
	*/
	protected $attendancePrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			StudentAttendance $attendancePrototype
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
	
	public function getUserDetailsId($username, $tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		if($tableName == 'employee_details'){
			$select->from(array('t1' => $tableName));
			$select->where(array('emp_id' =>$username));
			$select->columns(array('id'));
		} else{
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
	
	/*
	* Crosscheck and see whether id from route is student id
	*/
	
	public function crosscheckStudentId($student_id)
	{
		$id = NULL;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student'));
		$select->where(array('student_id' => $student_id));
		$select->columns(array('id'));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$id = $set['id'];
		}
		
		return $id;
	}
	
	/**
	* @param int/String $id
	* @return StudentAttendance
	* @throws \InvalidArgumentException
	*/
	
	public function findFunction($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select('table_name');
		$select->where(array('id = ? ' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
				return $this->hydrator->hydrate($result->current(), $this->attendancePrototype);
		}

		throw new \InvalidArgumentException("StudentAttendance Proposal with given ID: ($id) not found");
	}
	
	/**
	* @return array/StudentAttendance()
	*/
	public function findAll($tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName)); // join expression

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

	}
        
	/*
	 * Save the Attendance Record
	 */
	 
	public function saveAttendanceRecord($studentList, $timetable_dates, $attendance_data, $module, $programme, $section, $employee_details_id)
	{
		//$academic_modules_allocation_id = $this->getAcademicModulesAllocationId($programme, $module);
		$academic_modules_allocation_id = $module;

		if(!empty($attendance_data)){
			foreach($timetable_dates as $key=>$value){
				$start_time = substr($key, 11, 5).':00';
				$end_time = substr($key, 17, 5).':00';
				$day = date("l", strtotime(substr($key,0,10)));
				$academic_timetable_id = $this->getAcademicTimetableId($day, $start_time, $end_time, $academic_modules_allocation_id, $section);
				$attendanceData['attendance_Date'] = substr($key,0,10);
				$attendanceData['period'] = substr($key, 11, 22);
				$attendanceData['academic_Timetable_Id'] = $academic_timetable_id;
				$attendanceData['section_Id'] = $section;
				$attendanceData['academic_Modules_Allocation_Id'] = $academic_modules_allocation_id;
				$attendanceData['entered_By'] = $employee_details_id;
				$action2 = new Insert('student_attendance_dates');
				$action2->values($attendanceData);
				
				$sql2 = new Sql($this->dbAdapter);
				$stmt2 = $sql2->prepareStatementForSqlObject($action2);
				$result2 = $stmt2->execute();
				$newId = $result2->getGeneratedValue();
				
				foreach($attendance_data as $student_id=>$times){
					foreach($times as $key2 => $value2){
						if(substr($key,0,10) == substr($key2,0,10) && substr($key, 11, 22) == substr($key2, 11, 22) ){
							$recordData['student_Id'] = $student_id;
							$recordData['student_Attendance_Dates_Id'] = $newId;
							$action = new Insert('student_absentee_record');
							$action->values($recordData);
							
							$sql = new Sql($this->dbAdapter);
							$stmt = $sql->prepareStatementForSqlObject($action);
							$result = $stmt->execute();
						}
					}
				}
			}
		} else if(!empty($timetable_dates)){
			foreach($timetable_dates as $key=>$value){
				$start_time = substr($key, 11, 5).':00';
				$end_time = substr($key, 17, 5).':00';
				$day = date("l", strtotime(substr($key,0,10)));
				$academic_timetable_id = $this->getAcademicTimetableId($day, $start_time, $end_time, $academic_modules_allocation_id, $section);
				$attendanceData['attendance_Date'] = substr($key,0,10);
				$attendanceData['period'] = substr($key, 11, 22);
				$attendanceData['academic_Timetable_Id'] = $academic_timetable_id;
				$attendanceData['section_Id'] = $section;
				$attendanceData['academic_Modules_Allocation_Id'] = $academic_modules_allocation_id;
				$attendanceData['entered_By'] = $employee_details_id;
				$action2 = new Insert('student_attendance_dates');
				$action2->values($attendanceData);
				
				$sql2 = new Sql($this->dbAdapter);
				$stmt2 = $sql2->prepareStatementForSqlObject($action2);
				$result2 = $stmt2->execute();
			}
		}
			
		return; 
	}
	
	/*
	 * Save the Edited Attendance Record
	 */
	 
	public function saveEditedAttendanceRecord($studentList, $timetable_dates, $attendance_data, $module, $programe, $section, $employee_details_id)
	{
		$academic_modules_allocation_id = $module;
		$student_attendance_dates_id = NULL; 
		
		//if(!empty($attendance_data)){
			foreach($timetable_dates as $key=>$value){
				$start_time = substr($key, 11, 5).':00';
				$end_time = substr($key, 17, 5).':00';
				$day = date("l", strtotime(substr($key,0,10)));
				$attendance_date = substr($key,0,10);
				$period = substr($key, 11, 22);
				
				$sql = new Sql($this->dbAdapter);
				$select = $sql->select();
		
				$select->from(array('t1' => 'student_attendance_dates'))
							->columns(array('id'));
				$select->where(array('attendance_date' => $attendance_date));
				$select->where(array('period' => $period));
				$select->where(array('academic_modules_allocation_id' => $academic_modules_allocation_id));
				$select->where(array('section_id' => $section));
				$stmt = $sql->prepareStatementForSqlObject($select);		
				$result = $stmt->execute();
				
				$resultSet = new ResultSet();
				$resultSet->initialize($result);
				foreach($resultSet as $set){
					$student_attendance_dates_id = $set['id'];
				}
				//Delete Student Absentee Record
				$action = new Delete('student_absentee_record');
				$action->where(array('student_attendance_dates_id = ?' => $student_attendance_dates_id));
				
				$sql2 = new Sql($this->dbAdapter);
				$stmt2 = $sql2->prepareStatementForSqlObject($action);
				$result2 = $stmt2->execute();
				
				//Delete from Attendance Record Dates
				$action3 = new Delete('student_attendance_dates');
				$action3->where(array('id = ?' => $student_attendance_dates_id));
				
				$sql3 = new Sql($this->dbAdapter);
				$stmt3 = $sql2->prepareStatementForSqlObject($action3);
				$result3 = $stmt3->execute();
			}
			$this->saveAttendanceRecord($studentList, $timetable_dates, $attendance_data, $module, $programe, $section, $employee_details_id);
		//}
			
		return; 
	}


	public function updateDeletedStudentAttendance($from_date, $to_date, $attendance_data, $academic_modules_allocation_id, $programme, $section)
	{
		$student_attendance_dates_id = NULL; 

		$i = 1;
		$student_attendance_dates_id = array();
		$timetable_dates = $this->getTimetableWithDates($from_date, $to_date, $section, $academic_modules_allocation_id, $programme);
	
		foreach($timetable_dates as $key=>$value){
			$start_time = substr($key, 11, 5).':00';
			$end_time = substr($key, 17, 5).':00';
			$day = date("l", strtotime(substr($key,0,10)));
			$attendance_date = substr($key,0,10);
			$period = substr($key, 11, 22);
			
			$sql = new Sql($this->dbAdapter);
			$select = $sql->select();
	
			$select->from(array('t1' => 'student_attendance_dates'))
						->columns(array('id'));
			$select->where(array('attendance_date' => $attendance_date));
			$select->where(array('period' => $period));
			$select->where(array('academic_modules_allocation_id' => $academic_modules_allocation_id, 't1.section_id' => $section));
			$stmt = $sql->prepareStatementForSqlObject($select);		
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			foreach($resultSet as $set){
				$student_attendance_dates_id[$i++] = $set['id'];
			}
		}

		if($attendance_data != NULL)
		{
			$i = 1;
			foreach($attendance_data as $data){
				$sql1 = new Sql($this->dbAdapter);
				$select1 = $sql1->select();
		
				$select1->from(array('t1' => 'student_attendance_dates'));
				$select1->where(array('t1.id' => $student_attendance_dates_id[$i]));
				$stmt1 = $sql1->prepareStatementForSqlObject($select1);		
				$result1 = $stmt1->execute();
				
				$resultSet1 = new ResultSet();
				$resultSet1->initialize($result1);
				$attendance_data_array = array();
				foreach($resultSet1 as $set1){
					$attendance_data_array[] = $set1;
				}
				
				if($data == 1){
					foreach($attendance_data_array as $value){
						$start_time = substr($value['period'], 0, 5).':00'; 
						$end_time = substr($value['period'], 6, 5).':00';

						//Delete Student Absentee Record
						$action = new Delete('student_absentee_record');
						$action->where(array('student_attendance_dates_id = ?' => $student_attendance_dates_id[$i]));
						
						$sql2 = new Sql($this->dbAdapter);
						$stmt2 = $sql2->prepareStatementForSqlObject($action);
						$result2 = $stmt2->execute();
						
						//Delete from Attendance Record Dates
						$action3 = new Delete('student_attendance_dates');
						$action3->where(array('id = ?' => $student_attendance_dates_id[$i]));
						
						$sql3 = new Sql($this->dbAdapter);
						$stmt3 = $sql2->prepareStatementForSqlObject($action3);
						$result3 = $stmt3->execute();

						$academic_timetable_id = $this->getAcademicTimetableId($day, $start_time, $end_time, $academic_modules_allocation_id, $section);

						//$this->addCancelledLectures($value['attendance_date'], $value['period'], $section, $academic_modules_allocation_id, $value['academic_timetable_id']);
						}
					}
				$i++;
			}
			return;
		} 
	}
	 
	/*
	* Save Extra Class Attendance
	*/
	 
	public function saveExtraClassAttendance($studentList, $from_date, $from_time, $attendance_data, $module, $programme, $section, $employee_details_id)
	{ 
		//$academic_modules_allocation_id = $this->getAcademicModulesAllocationId($programme, $module);
			$attendanceData['attendance_Date'] = $from_date;
			$timestamp = strtotime($from_time) + 60*60;
			$to_time = date('H:i', $timestamp);
			$attendanceData['period'] = $from_time.'-'.$to_time; 
			$attendanceData['academic_Modules_Allocation_Id'] = $module;
			$attendanceData['section_Id'] = $section;
			$attendanceData['entered_By'] = $employee_details_id;
			$attendanceData['attendance_Type'] = 'Extra-Class';
			$action2 = new Insert('student_attendance_dates');
			$action2->values($attendanceData);
			
			$sql2 = new Sql($this->dbAdapter);
			$stmt2 = $sql2->prepareStatementForSqlObject($action2);
			$result2 = $stmt2->execute();
			$newId = $result2->getGeneratedValue(); 

			if(!empty($attendance_data)){
				foreach($attendance_data as $student_id=>$record){
					$recordData['student_Id'] = $student_id;
					$recordData['student_Attendance_Dates_Id'] = $newId; 
					$action = new Insert('student_absentee_record');
					$action->values($recordData);
					
					$sql = new Sql($this->dbAdapter);
					$stmt = $sql->prepareStatementForSqlObject($action);
					$result = $stmt->execute();
				}
		}
			
		return; 
	}
	 
	 /*
	 * Save the cancelled lectures details
	 */
	 
	public function saveCancelledLectures($timetable_dates, $lectures_data, $section, $module, $programme, $lectures_reasons)
	{ 
		$academic_modules_allocation_id = $module;

		if(!empty($lectures_data)){
			foreach($lectures_data as $key=>$value){ 
				$start_time = substr($key, 11, 5).':00';
				$end_time = substr($key, 17, 5).':00';
				$day = date("l", strtotime(substr($key,0,10)));
				$academic_timetable_id = $this->getAcademicTimetableId($day, $start_time, $end_time, $academic_modules_allocation_id, $section);
				$lectureData['lecture_Date'] = substr($key,0,10);
				$lectureData['period'] = substr($key, 11, 22);
				$lectureData['section'] = $section;
				$lectureData['academic_Timetable_Id'] = $academic_timetable_id;
				$lectureData['academic_Modules_Allocation_Id'] = $academic_modules_allocation_id;
				$action = new Insert('cancelled_lectures');
				$action->values($lectureData);
				
				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();

				$this->addCancelledLecturesReason($timetable_dates, $section, $module, $programme, $lectures_reasons);
			}
		} 
			
		return; 
	}


	public function addCancelledLecturesReason($timetable_dates, $section, $module, $programme, $lectures_reasons)
	{
		$academic_modules_allocation_id = $module;

		if(!empty($lectures_reasons)){
			foreach($lectures_reasons as $key=>$value){ 
				$start_time = substr($key, 11, 5).':00';
				$end_time = substr($key, 17, 5).':00';
				$day = date("l", strtotime(substr($key,0,10)));
				$academic_timetable_id = $this->getAcademicTimetableId($day, $start_time, $end_time, $academic_modules_allocation_id, $section);
				$lectureData['lecture_Date'] = substr($key,0,10);
				$lectureData['period'] = substr($key, 11, 22);
				$lectureData['section'] = $section;
				$lectureData['academic_Timetable_Id'] = $academic_timetable_id;
				$lectureData['academic_Modules_Allocation_Id'] = $academic_modules_allocation_id;
				$action = new Update('cancelled_lectures');
				$action->set(array('reasons' => $value));
				$action->where(array('lecture_date' => $lectureData['lecture_Date'], 'period' => $lectureData['period'], 'section' => $lectureData['section'], 'academic_timetable_id' => $lectureData['academic_Timetable_Id'] , 'academic_modules_allocation_id' => $lectureData['academic_Modules_Allocation_Id']));
				
				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
			}
		} 
			
		return; 
	}

	public function addCancelledLectures($attendance_date, $period, $section, $academic_modules_allocation_id, $academic_timetable_id)
	{
		$lectureData['lecture_Date'] = $attendance_date;
		$lectureData['period'] = $period;
		$lectureData['section'] = $section;
		$lectureData['academic_Timetable_Id'] = $academic_timetable_id;
		$lectureData['academic_Modules_Allocation_Id'] = $academic_modules_allocation_id;
		$action = new Insert('cancelled_lectures');
		$action->values($lectureData);
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
	}
	
	/*
	* Get details of cancelled lecture for editing purposes
	*/
	 
	public function getCancelledLectureDetail($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'cancelled_lectures'));
		$select->where(array('id' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);		
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
	}
	
	/*
	* Get the days of the cancelled lectures and remove them when taking attendance
	*/
	
	public function getCancelledLectureDates($from_date, $to_date, $section, $module, $programme)
	{
		$cancelled_lectures = array();
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'cancelled_lectures'));
		$select->where(array('section' => $section));
		$select->where(array('academic_modules_allocation_id' => $module));
		$select->where->between('t1.lecture_date', $from_date, $to_date);

		$stmt = $sql->prepareStatementForSqlObject($select);		
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$cancelled_lectures[] = $set['lecture_date']."_".$set['period'];
		}
		
		return $cancelled_lectures;
	}
	
	/*
	* Get the Local and National Holidays for which Lectures are cancelled
	*/
	
	public function getHolidayDates($from_date, $to_date, $organisation_id)
	{
		$holidays_dates = array();
		$holidays = array();
		$index =0;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_calendar'))
					->join(array('t2' => 'academic_calendar_events'), 
									't1.academic_event = t2.id', array('organisation_id'));
		$select->where(array('t1.from_date >= ? ' => $from_date));
		$select->where(array('t1.to_date <= ? ' => $to_date));
		$select->where(array('t2.organisation_id' => $organisation_id));
		//$select->where(array('t2.academic_event' => 'Local Holidays', 't2.academic_event' => 'National Holiday'));
		$select->where->like('t2.academic_event', '%'.'Holiday'.'%');

		$stmt = $sql->prepareStatementForSqlObject($select);		
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$holidays_dates[$set['id']]['from_date'] = $set['from_date'];
			$holidays_dates[$set['id']]['to_date'] = $set['to_date'];
		}
		
		foreach($holidays_dates as $key => $value){
			$start_date = $value['from_date'];
			$end_date = $value['to_date'];
			while(strtotime($start_date) <= strtotime($end_date)){
				$holidays[$index++] = $start_date;
				$start_date = date('Y-m-d', strtotime("+1 day", strtotime($start_date)));
			}
		}
		return $holidays;
	}
	
	/*
	 * Get Student Attendance Data
	 * This will look at the leave, absent etc
	 */
	 
	public function getStudentAttendance($from_date, $to_date, $unit, $organisation_id)
	{
		
	}
	
	/*
	 * Get the Attendance for a date and a module for editing
	 */
	 
	public function getStudentAttendanceList($programme, $module, $year, $from_date)
	{
		$academic_modules_allocation_id = $this->getAcademicModulesId($module, $programme);
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_attendance_dates'))
				->columns(array('attendance_date','period'))
				->join(array('t2' => 'academic_modules_allocation'), 
									't1.academic_modules_allocation_id = t2.id', array('academic_modules_id'));
		$select->where(array('t2.academic_modules_id' => $academic_modules_allocation_id));
		$select->where(array('t1.attendance_date' => $from_date));

		$stmt = $sql->prepareStatementForSqlObject($select);		
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
	}
	
	/*
	 * Get the list of staff for a given unit in an organisation
	 * Will be used for entering the attendance
	 */
	 
	public function getStudentList($programme, $academic_modules_allocation_id, $section, $year, $status)
	{
		
		$organisation_id = $this->getOrganisationIdByProgramme($programme);
		//$semester_type = $this->getSemester($organisation_id);
		//$academic_year = $this->getAcademicYear($semester_type);

		$academic_event_details = $this->getSemester($organisation_id);

        $semester_type = $academic_event_details['academic_event'];
        $academic_year = $this->getAcademicYear($academic_event_details);

		if($academic_modules_allocation_id){
			$semester = $this->getSemesterForModule($academic_modules_allocation_id);
		}
		
		$student_list = array();
				
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$modules_type = $this->getModuleAllocationDetails($academic_modules_allocation_id);
			
		if ($modules_type =='Compulsory' || $modules_type =='compulsory'){
 			$select->from(array('t1' => 'student'))
	            ->columns(array('id','first_name','middle_name','last_name','student_id', 'enrollment_year'))
	        	->join(array('t2' => 'student_semester_registration'), 
	            	't1.id = t2.student_id', array('student_section_id'))
	        	->join(array('t3' => 'student_section'), 
	            	't2.student_section_id = t3.id', array('section'))
	        	->join(array('t4' => 'programmes'), 
	            	't1.programmes_id = t4.id', array('programme_name'));
	        if($academic_modules_allocation_id){
				$select->where(array('t2.semester_id' => $semester, 't1.student_status_type_id' => '1'));
			}
			$select->where(array('t2.academic_year' => $academic_year));
			if($programme){
				$select->where(array('programmes_id' =>$programme));
			}
	        if($section){
				$select->where(array('t3.id' =>$section));
			}
			$select->order('t1.enrollment_year DESC, t1.student_id ASC'); 

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			
			foreach($resultSet as $set){
				$student_list[$set['student_id']] = $set['first_name']. ' ' .$set['middle_name'].' '.$set['last_name'];
			}
			
			//get the backyear students and remove students who have cleared from student list
			$backyear_students_in_module = $this->getBackyearStudentForModule($academic_modules_allocation_id, $semester, $academic_year, $programme);
			$backyear_students_list = $this->getBackyearStudentList($semester, $academic_year);
			
			//get backpaper students
			$backpaper_students_in_module = $this->getBackpaperStudentsForModule($academic_modules_allocation_id, $section, $semester, $academic_year, $programme);
			
			//remove this from student list
			$backyear_students_module_cleared = array_diff_key($backyear_students_list, $backyear_students_in_module);
			
			foreach($backyear_students_module_cleared as $key => $value){
				unset($student_list[$key]);
			}
			
			//add this to student list
			$backpaper_students = array_diff_key($backpaper_students_in_module, $backyear_students_list);
			foreach($backpaper_students as $key => $value){
				$student_list[$key] = $value;
			}
		} else {
			//echo "$modules_type"; die();
 			$select->from(array('t1' => 'student'))
	            ->columns(array('id','first_name','middle_name','last_name','student_id', 'enrollment_year'))
	        	->join(array('t2' => 'student_semester_registration'), 
	            	't1.id = t2.student_id', array('student_section_id'))
	        	->join(array('t3' => 'student_section'), 
	            	't2.student_section_id = t3.id', array('section'))
	        	->join(array('t4' => 'programmes'), 
	            	't1.programmes_id = t4.id', array('programme_name'))
	        	->join(array('t5' => 'student_elective_modules'),
						't5.student_id = t1.id',  array('academic_modules_allocation_id'));
	        if($academic_modules_allocation_id){
				$select->where(array('t2.semester_id' => $semester, 't1.student_status_type_id' => '1'));
			}
			$select->where(array('t2.academic_year' => $academic_year));
			$select->where(array('t5.academic_modules_allocation_id' => $academic_modules_allocation_id));
			$select->order('t1.enrollment_year DESC, t1.student_id ASC'); 
			if($programme){
				$select->where(array('programmes_id' =>$programme));
			}
	        if($section){
				$select->where(array('t3.id' =>$section));
			}

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			
			foreach($resultSet as $set){
				$student_list[$set['student_id']] = $set['first_name']. ' ' .$set['middle_name'].' '.$set['last_name'];
			}
			
			//get the backyear students and remove students who have cleared from student list
			$backyear_students_in_module = $this->getBackyearStudentForModule($academic_modules_allocation_id, $semester, $academic_year, $programme);
			$backyear_students_list = $this->getBackyearStudentList($semester, $academic_year);
			
			//get backpaper students
			$backpaper_students_in_module = $this->getBackpaperStudentsForModule($academic_modules_allocation_id, $section, $semester, $academic_year, $programme);
			
			//remove this from student list
			$backyear_students_module_cleared = array_diff_key($backyear_students_list, $backyear_students_in_module);
			
			foreach($backyear_students_module_cleared as $key => $value){
				unset($student_list[$key]);
			}
			
			//add this to student list
			$backpaper_students = array_diff_key($backpaper_students_in_module, $backyear_students_list);
			foreach($backpaper_students as $key => $value){
				$student_list[$key] = $value;
			}

		}
		
		return $student_list;
	}
	
	/*
	* Get the leave data of the students
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
	 * check to see whether the attendance has been recorded or not
	 */
	 
	public function getAttendanceRecordDates($from_date, $to_date, $module, $programme, $section)
	{
		//$academic_modules_allocation_id = $this->getAcademicModulesId($module, $programme);
		$academic_modules_allocation_id = $module;

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_attendance_dates'))
					->columns(array('attendance_date','period', 'attendance_type'));
		$select->where(array('t1.section_id' => $section));
		$select->where(array('t1.academic_modules_allocation_id' => $academic_modules_allocation_id));
		$select->where->between('t1.attendance_date', $from_date, $to_date);
		$select->order('attendance_date ASC');

		$stmt = $sql->prepareStatementForSqlObject($select);		
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
	}


	public function getAcademicModulesAllocationId($programme, $module, $organisation_id)
	{

		//$semester_type = $this->getSemester($organisation_id);
		//$academic_year = $this->getAcademicYear($semester_type);

		$academic_event_details = $this->getSemester($organisation_id);

        $semester_type = $academic_event_details['academic_event'];
        $academic_year = $this->getAcademicYear($academic_event_details);
		
		//need to get the academic module allocation id first
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		//need to take care of the year as well
		$select->from(array('t1' => 'academic_modules_allocation'));
		$select->columns(array('id'));
		$select->where(array('t1.academic_modules_id' => $module, 't1.programmes_id'=>$programme, 't1.academic_year' => $academic_year));
		$stmt = $sql->prepareStatementForSqlObject($select);		
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$academic_modules_allocation_id = NULL;
		foreach($resultSet as $set){
			$academic_modules_allocation_id = $set['id'];
		}
		//var_dump($academic_modules_allocation_id); die();
		return $academic_modules_allocation_id;

	}
	
	/*
	* Get the dates in an array according to the timetable
	*/
	 
	public function getAttendanceDates($from_date, $to_date, $section, $module, $programme)
	{
		//$academic_modules_allocation_id = $this->getAcademicModulesId($module, $programme);
		$academic_modules_allocation_id = $module;
		$timetable = $this->getTimeTable($section, $module);
		
		//get the days of the week of the timetable
		$days_of_week = array('Monday'=> '1', 'Tuesday' => '2', 'Wednesday' => '3', 'Thursday' => '4', 'Friday' => '5', 'Saturday' => 6);
		$timetable_days = array();
		$index=1;
		foreach($timetable as $key=>$value){
			if(array_key_exists($value['day'],$days_of_week)){
				$timetable_days[$value['day']] = $days_of_week[$value['day']];
			}
		}
		
		$dates_of_timetable = array();
		
		foreach($timetable_days as $key=>$value){
			$dates_of_timetable_temp[$index++] = $this->getDayForDates($from_date, $to_date, $value);
		}
		
		$index=1;
		foreach($dates_of_timetable_temp as $temp){
			foreach($temp as $key=>$value){
				$dates_of_timetable[$index++] = $value;
			}
		}
		sort($dates_of_timetable);
		return $dates_of_timetable;
	}
	
	/*
	 * Get the last date of attendance entry
	 */
	 
	public function getLastAttendanceDate($module, $programme, $section)
	{
		//$academic_modules_allocation_id = $this->getAcademicModulesId($module, $programme);
		//last date needs to be start of session
		$last_date = '1970-01-01';
		$academic_modules_allocation_id = $module;

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_attendance_dates'))
				->columns(array('attendance_date','period'))
				->join(array('t2' => 'academic_timetable'), 
                            't1.academic_timetable_id = t2.id', array('group'));
		$select->where(array('t1.academic_modules_allocation_id' => $academic_modules_allocation_id));
		$select->where(array('t2.group' => $section));
		$select->order('attendance_date DESC');

		$stmt = $sql->prepareStatementForSqlObject($select);		
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result); 
		
		foreach($resultSet as $set){
			return $last_date = $set['attendance_date'];
		}
		
		return $last_date;
	}
	 
	 
	
	/*
	* Get the timetable with the dates
	*/
	 
	public function getTimetableWithDates($from_date, $to_date, $section, $module, $programme)
	{
		//$academic_modules_allocation_id = $this->getAcademicModulesId($module, $programme);
		$timetable = $this->getTimeTable($section, $module);
		
		//get the holiday dates
		$organisation_id = $this->getOrganisationIdByProgramme($programme);
		$holiday_dates = $this->getHolidayDates($from_date, $to_date, $organisation_id);
		
		//get the days of the week of the timetable
		$days_of_week = array('Monday'=> '1', 'Tuesday' => '2', 'Wednesday' => '3', 'Thursday' => '4', 'Friday' => '5', 'Saturday' => 6);
		$timetable_days = array();
		$index=1;
		foreach($timetable as $key=>$value){
				if(array_key_exists($value['day'],$days_of_week)){
					$dates_of_timetable = $this->getDayForDates($from_date, $to_date, $days_of_week[$value['day']]);
					foreach($dates_of_timetable as $key2 => $value2){
						if(!in_array($value2, $holiday_dates)){
							$timetable_days[$value2.'_'.$value['times']] = $value['day'];
						}
					}
				}
		}
		ksort($timetable_days);
		
		$cancelled_lectures = $this->getCancelledLectureDates($from_date, $to_date, $section, $module, $programme);
		
		foreach($cancelled_lectures as $key=>$value){
			unset($timetable_days[$value]);
		}
		return $timetable_days;
	}
	
	/*
	 * Get Extra Class Dates
	 */
	 
	 public function getExtraClassDates($from_date, $section, $module, $programme)
	 {
		$timetable_days = array();
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_attendance_dates'));
		$select->where(array('attendance_date' => $from_date));
		$select->where(array('t1.academic_modules_allocation_id' => $module));
		$select->where(array('t1.section_id' => $section));
		$select->where(array('t1.attendance_type' => 'Extra-Class'));
		
		$stmt = $sql->prepareStatementForSqlObject($select);		
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$timetable_days[$set['attendance_date']."_".$set['period']] = date("l", strtotime($set['attendance_date']));
		}
		return $timetable_days;
	 }
	
	/*
	 * get list of absentees
	 */
	 
	public function getAbsenteeList($from_date, $to_date, $module, $programme)
	{
		//$academic_modules_allocation_id = $this->getAcademicModulesId($module, $programme);
		$academic_modules_allocation_id = $module;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_attendance_dates'))
				->join(array('t2' => 'student_absentee_record'), 
									't1.id = t2.student_attendance_dates_id', array('student_id'))
				->join(array('t3' => 'student'), 
									't3.student_id = t2.student_id', array('id'=>'student_id'));
		$select->where->between('t1.attendance_date', $from_date, $to_date);
		$select->where(array('t1.academic_modules_allocation_id' => $academic_modules_allocation_id));
		$select->order('attendance_date ASC');
		
		$stmt = $sql->prepareStatementForSqlObject($select);		
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
	}
	
	
	/*
	 * Get the Student Attendance Record (Contact Hours, % attendance etc)
	 *
	 * Here, $module is the $academic_modules_allocation_id
	 */
	 
	public function getStudentAttendanceRecord($programme, $module, $section, $from_date, $to_date)
	{
		$academic_modules_allocation_id = $module;

		$studentList = $this->getStudentList($programme, $academic_modules_allocation_id, $section, $year=NULL, $status=NULL);

		$student_attendance_data = array();
		
		//Get the months between dates
		$start_month = date("m", strtotime($from_date));

		$end_month = date("m", strtotime($to_date));

		$months = array();
		for($m=(int)$start_month; $m<=(int)$end_month; $m++){
			$months[]= date('m', mktime(0, 0, 0, $m, 1));
		}
		
		for($i=0; $i<sizeof($months); $i++){
			//$year = date('Y');
			$year = date("Y", strtotime($from_date));

			if($i==0){
				$start_date = $from_date;
			} else {
				$start_date = $year."-".$months[$i]."-01";
			}
			$start_date = $year."-".$months[$i]."-01";
			$end_date = $year."-".$months[$i]."-31";
			
			//studentList student_identity => name
			 foreach($studentList as $key => $value){
				 $student_attendance_data[$key][$months[$i]]['name'] = $value;
				 $student_attendance_data[$key][$months[$i]]['classes_taken'] = $this->getClassesTaken($academic_modules_allocation_id, $section, $start_date, $end_date);
				 $student_attendance_data[$key][$months[$i]]['classes_missed'] = $this->getClassesMissed($key, $academic_modules_allocation_id, $start_date, $end_date);
				 $student_attendance_data[$key][$months[$i]]['total_class_missed'] = $this->getClassesMissed($key, $academic_modules_allocation_id, NULL, NULL);
			 }
		}
		//var_dump($student_attendance_data); die();
		return $student_attendance_data;
	}
	
	/*
	* Get the classes missed by student
	* NULL "month" indicates "TOTAL"
	*/
	
	private function getClassesMissed($student_id, $academic_modules_allocation_id, $from_date, $to_date)
	{		
		$missed_class = array();
			
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'student_attendance_dates'))
					->columns(array('attendance_date'))
				->join(array('t2' => 'student_absentee_record'), 
					't1.id = t2.student_attendance_dates_id', array('student_id'));
		$select->where(array('t1.academic_modules_allocation_id' => $academic_modules_allocation_id));
		$select->where(array('t2.student_id' => $student_id));
		if($from_date != NULL && $to_date != NULL){
			$select->where->between('t1.attendance_date', $from_date, $to_date);
		}
		
		$stmt = $sql->prepareStatementForSqlObject($select);		
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$missed_class[] = $set['attendance_date'];
		}
		return count($missed_class);
	}
	
	/*
	* Get the total classes taken
	*/
	
	public function getClassesTaken($academic_modules_allocation_id, $section, $from_date, $to_date)
	{
		$classes_taken = array();		
			
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'student_attendance_dates'));
		$select->where(array('t1.academic_modules_allocation_id' => $academic_modules_allocation_id));
		$select->where(array('t1.section_id' => $section));
		$select->where->between('t1.attendance_date', $from_date, $to_date);
		
		$stmt = $sql->prepareStatementForSqlObject($select);		
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$classes_taken[$set['id']] = $set['period'];
		}

		return count($classes_taken);
	}
	
	/*
	 * Get Contact hours
	 */
	 
	public function getModuleContactHours($academic_modules_allocation_id)
	{		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_modules'))
				->columns(array('contact_hours'))
				->join(array('t2' => 'academic_modules_allocation'), 
									't1.id = t2.academic_modules_id', array('academic_modules_id'));
		$select->where(array('t2.id' => $academic_modules_allocation_id));

		$stmt = $sql->prepareStatementForSqlObject($select);		
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			return $set['contact_hours'];
		}
	}
	
	/*
	 * Get the Module Tutor
	 */
	 
	public function getModuleTutor($module, $section)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_module_tutors'))
				->columns(array('module_tutor'))
				->join(array('t2' => 'employee_details'), 
									't1.module_tutor = t2.emp_id', array('first_name', 'middle_name', 'last_name'));
		$select->where(array('t1.academic_modules_allocation_id' => $module));

		$stmt = $sql->prepareStatementForSqlObject($select);		
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			return $set['first_name']." ".$set['middle_name']." ".$set['last_name']." (".$set['module_tutor'].")";
		}
	}
	 
	 /*
	 * Get total lecture delivered
	 */
	 
	public function getTotalLecturesDelivered($academic_modules_allocation_id)
	{
		$total_lectures = array();
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_attendance_dates'))
				->columns(array('attendance_date'));
		$select->where(array('t1.academic_modules_allocation_id' => $academic_modules_allocation_id));

		$stmt = $sql->prepareStatementForSqlObject($select);		
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$total_lectures[$set['attendance_date']] = $set['attendance_date'];
		}
		
		return count($total_lectures);
	}
	 
	 /*
	 * Get Total Lecture Hours
	 */
	 
	public function getTotalLectureHours($academic_modules_allocation_id, $organisation_id)
	{
		$from_time = NULL;
		$to_time = NULL;
		$total_lectures = $this->getTotalLecturesDelivered($academic_modules_allocation_id);
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_timetable_timing'))
				->columns(array('from_time', 'to_time'));
		$select->where(array('t1.organisation_id' => $organisation_id));
		$select->limit(1);

		$stmt = $sql->prepareStatementForSqlObject($select);		
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$from_time = strtotime($set['from_time']);
			$to_time = strtotime($set['to_time']);
		}
		$lecture_length = round(abs($to_time - $from_time)/3600, 2);
		
		//return the total lecture hours
		return $lecture_length * $total_lectures;
		
	}
	
	/*
	 * Get the lecture length
	 */
	 
	public function getLectureLength($organisation_id)
	{
		$lecture_length = NULL;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_timetable_timing'))
				->columns(array('from_time', 'to_time'));
		$select->where(array('t1.organisation_id' => $organisation_id));
		$select->limit(1);

		$stmt = $sql->prepareStatementForSqlObject($select);		
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$from_time = strtotime($set['from_time']);
			$to_time = strtotime($set['to_time']);
		}
		$lecture_length = round(abs($to_time - $from_time)/3600, 2);
		
		return $lecture_length;
	}
	
	/*
	* Get the academic modules id 
	*/
	
	public function getAcademicModulesId($module, $programme)
	{
		//need to get the academic module allocation id first
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		//need to take care of the year as well
		$select->from(array('t1' => 'academic_modules_allocation'));
		$select->columns(array('academic_modules_id'));
		$select->where(array('module_title' => $module, 'programmes_id'=>$programme));
		$stmt = $sql->prepareStatementForSqlObject($select);		
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$academic_modules_allocation_id = NULL;
		foreach($resultSet as $set){
			$academic_modules_allocation_id = $set['academic_modules_id'];
		}
		return $academic_modules_allocation_id;
	}
	
	/*
	* Get the academic modules allocation id for cancelled lectures
	*/
	
	/*public function getAcademicModulesAllocationId($programme, $module)
	{
		//need to get the academic module allocation id first
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		//need to take care of the year as well
		$select->from(array('t1' => 'academic_modules_allocation'));
		$select->columns(array('id'));
		$select->where(array('module_title' => $module, 'programmes_id'=>$programme));
		$stmt = $sql->prepareStatementForSqlObject($select);		
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$academic_modules_allocation_id = NULL;
		foreach($resultSet as $set){
			$academic_modules_allocation_id = $set['id'];
		}
		return $academic_modules_allocation_id;
	}*/
	
	/*
	 * Check whether the attendance has been entered
	 */
	 
	public function checkAttendanceDate($section, $module, $from_date)
	{
		$date = NULL;
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select = $sql->select();
		$select->from(array('t1' => 'student_attendance_dates'));
		$select->columns(array('id'));
		$select->where(array('academic_modules_allocation_id' => $module));
        $select->where(array('section_id' => $section));
		$select->where(array('attendance_date' => $from_date));
		$stmt = $sql->prepareStatementForSqlObject($select);		
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$date = $set['id'];
		}
		return $date;
	}

	/*
	 * Check whether the attendance has been entered in range
	 */
	 
	public function checkAttendanceDateRange($section, $module, $from_date)
	{
		$date = NULL;
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select = $sql->select();
		$select->from(array('t1' => 'student_attendance_dates'));
		$select->columns(array('id'));
		$select->where(array('academic_modules_allocation_id' => $module));
        $select->where(array('section_id' => $section));
		$select->where(array('attendance_date' => $from_date));
		$stmt = $sql->prepareStatementForSqlObject($select);		
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$date = $set['id'];
		}
		return $date;
	}
	
	/*
	* Get timetable for a given module
	* used by attendance to get the days of the week
	*/
	
	public function getTimeTable($section, $academic_modules_allocation_id)
	{
		$sectionList = $this->listSelectData('student_section', 'section', NULL);
        $sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		//need to take care of year
		$select = $sql->select();
		$select->from(array('t1' => 'academic_timetable'));
		$select->columns(array('day','from_time','to_time'));
		$select->where(array('academic_modules_allocation_id' => $academic_modules_allocation_id));
        $select->where(array('group' => $section));
		$stmt = $sql->prepareStatementForSqlObject($select);		
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$timetable = array();
		$index = 0;
		foreach($resultSet as $set){
			$timetable[$index]['day'] = $set['day'];
			$timetable[$index]['times'] = substr($set['from_time'],0,5).'-'.substr($set['to_time'],0,5);
			$index++;
		}
		return $timetable;
	}
	
	/*
	* Get the dates of the days of the timetable
	*/
	
	public function getDayForDates($from_date, $to_date, $day_number)
	{
		//testing getting days
		$startDate = $from_date;
		$endDate = $to_date;
		$end_date = strtotime($endDate);
		$days = array('1'=> 'Monday', '2' => 'Tuesday', '3' => 'Wednesday', '4' => 'Thursday', '5' => 'Friday', '6' => 'Saturday');
		$date_array = array();
		for($i= strtotime($days[$day_number], strtotime($startDate)); $i<= strtotime($endDate); $i = strtotime('+1 week',$i))
			//echo date('Y-m-d', $i);
			$date_array[] = date('Y-m-d', $i);
		return $date_array;
	}
	
	/*
	* Get organisation id based on the programme_id
	*/
	
	private function getOrganisationIdByProgramme($programmes_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'programmes'));
		$select->where(array('id' =>$programmes_id));
		$select->columns(array('organisation_id'));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			return $set['organisation_id'];
		}
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
		
		/*foreach($resultSet as $set){
			if($set['academic_event'] == 'Start of Autumn Semester'){
				$semester = 'Autumn';
			}
			else if($set['academic_event'] == 'Start of Spring Semester'){
				$semester = 'Spring';
			}
		}*/
		foreach($result as $set){
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
	
	/*
	 * Get the academic year based on the semester from the database
	 */
	
	public function getAcademicYear($academic_event_details)
	{
		//$academic_year = NULL;
        $semester = $academic_event_details['academic_event'];
        $academic_year = $academic_event_details['academic_year'];

		if($semester == 'Autumn'){
			$academic_year;
		} else {
			$academic_year;
			//$semester = (date('Y')-1).'-'.date('Y');
		}
		
		return $academic_year;
	}
	
	/*
	* Get the semester for module allocated
	*/
	
	private function getSemesterForModule($academic_modules_allocation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'academic_modules_allocation'))
				->columns(array('semester'));
		$select->where(array('id' => $academic_modules_allocation_id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$semester = NULL;
		
		foreach($resultSet as $set){
			$semester= $set['semester'];
		}
		return $semester;
	}
	
	/*
	* Get the list of backyear students for a particular module
	*/
	
	private function getBackyearStudentForModule($academic_modules_allocation_id, $semester, $academic_year, $programmesId)
	{
		$module_code = $this->getAllocatedAcademicModuleCode($academic_modules_allocation_id);
		
		$backyear_academic_year = $this->getPreviousAcademicYear($academic_year);
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'academic_modules_allocation'))
					->columns(array('module_title'))
					->join(array('t2' => 'academic_modules'), 
                            't2.id = t1.academic_modules_id', array('module_code'))
					->join(array('t3' => 'student_repeat_modules'),
							't3.module_code = t2.module_code', array('backlog_semester'))
					->join(array('t4' => 'student_backyears'), 
                            't3.student_id = t4.student_id', array('backyear_semester'))
					->join(array('t5' => 'student'), 
                            't4.student_id = t5.id', array('first_name', 'middle_name', 'last_name','student_id'));
		$select->where->like('t2.module_code', $module_code);
		$select->where(array('t2.programmes_id' => $programmesId));
		$select->where(array('t3.backlog_semester' => $semester));
		$select->where(array('t1.academic_year' => $academic_year));
		$select->where(array('t4.backyear_academic_year' => $backyear_academic_year));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$student_list = array();
		
		foreach($resultSet as $set){
			$student_list[$set['student_id']] = $set['first_name']. ' ' .$set['middle_name'].' '.$set['last_name'];
		}
		return $student_list;
	}
	
	private function getBackyearStudentList($semester, $academic_year)
	{
		$backyear_academic_year = $this->getPreviousAcademicYear($academic_year);
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'student_repeat_modules'))
					->join(array('t4' => 'student_backyears'), 
                            't1.student_id = t4.student_id', array('backyear_semester'))
					->join(array('t5' => 'student'), 
                            't4.student_id = t5.id', array('first_name', 'middle_name', 'last_name','student_id'));
		$select->where(array('t4.backyear_semester' => $semester));
		$select->where(array('t4.backyear_academic_year' => $backyear_academic_year));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$student_list = array();
		
		foreach($resultSet as $set){
			$student_list[$set['student_id']] = $set['first_name']. ' ' .$set['middle_name'].' '.$set['last_name'];
		}
		
		return $student_list;
	}
	
	/*
	* Get the list of students with backpapers
	*/
	
	private function getBackpaperStudentsForModule($academic_modules_allocation_id, $section, $semester, $academic_year, $programmesId)
	{
		$module_code = $this->getAllocatedAcademicModuleCode($academic_modules_allocation_id);
		$backpaper_in = 'CA';
		
		$backpaper_academic_year = $this->getPreviousAcademicYear($academic_year);
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'academic_modules_allocation'))
					->columns(array('module_title'))
					->join(array('t2' => 'academic_modules'), 
                            't2.id = t1.academic_modules_id', array('module_code'))
					->join(array('t3' => 'student_backpaper_registration'),
							't3.module_code = t2.module_code', array('backpaper_semester'))
					->join(array('t4' => 'student_section'), 
                            't3.section_id = t4.id', array('section'))
					->join(array('t5' => 'student'), 
                            't3.student_id = t5.id', array('first_name', 'middle_name', 'last_name','student_id'));
		$select->where->like('t2.module_code', $module_code);
		$select->where(array('t3.programmes_id' => $programmesId));
		$select->where(array('t1.academic_year' => $academic_year));
		$select->where(array('t3.backpaper_academic_year' => $backpaper_academic_year));
		$select->where(array('t4.id' => $section));
		$select->where(array('t3.backpaper_in' => $backpaper_in));
		$select->where->like('t3.registration_status', "Registered");
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$student_list = array();
		
		foreach($resultSet as $set){
			$student_list[$set['student_id']] = $set['first_name']. ' ' .$set['middle_name'].' '.$set['last_name'];
		}
		
		return $student_list;
	}
	
	/*
	* Get Module Code given Module Allocation ID
	*/
	
	private function getAllocatedAcademicModuleCode($academic_modules_allocation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t3' => 'academic_modules_allocation'))
						->columns(array('id'))
				->join(array('t4' => 'academic_modules'), 
						't3.academic_modules_id = t4.id', array('module_code'));
		$select->where('t3.id = ' .$academic_modules_allocation_id);
                
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$module_code = NULL;
		
		foreach($resultSet as $set){
			$module_code = $set['module_code'];
		}
		return $module_code;
	}
	
	/*
	 * Get Individual student attendance record
	 */
	 
	public function getIndividualStudentAttendanceRecord($student_id, $academic_modules_allocation_id)
	{
		//$attendance_data = array();
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'student_attendance_dates'))
				->join(array('t2' => 'student_absentee_record'), 
					't1.id = t2.student_attendance_dates_id', array('student_id'));
		$select->where(array('t1.academic_modules_allocation_id' => $academic_modules_allocation_id));
		$select->where(array('t2.student_id' => $student_id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);		
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
		
	}
	
	/*
	* Generate Consolidated Student Attendance
	*/
	
	public function generateConsolidatedAttendance($data)
	{
		$student_attendance_data = array();
		
		
		
		//Get the start and end months of semester
		$organisation_id = $this->getOrganisationIdByProgramme($data['programmes_id']);
		//$semester = $this->getSemester($organisation_id);
		//$academic_year = $this->getAcademicYear($semester);

		$academic_event_details = $this->getSemester($organisation_id);

        $semester = $academic_event_details['academic_event'];
        $academic_year = $this->getAcademicYear($academic_event_details);

		$semester_dates = $this->getSemesterDates($organisation_id);
		$start_date = $semester_dates['start_date'];
		$end_date = $semester_dates['end_date'];
		
		//get the academic modules by programme (academic modules allocation)
		$modules_allocated = $this->getAcademicModulesAllocationByProgramme($data['programmes_id'], $data['year'], $academic_year);
		$sectionList = $this->listSelectData('student_section', 'section', NULL);
		
		foreach($sectionList as $key=>$value){
			$section = $key;
			foreach($modules_allocated as $academic_modules_allocation_id => $academic_modules_id){
				$studentList = $this->getStudentList($data['programmes_id'], $academic_modules_allocation_id, $section, $data['year']);
				foreach($studentList as $key2 => $value2){
					$studentAttendanceData = array();
					$studentAttendanceData['student_Id'] = $key2;
					$studentAttendanceData['total_Lectures_Delivered'] = $this->getClassesTaken($academic_modules_allocation_id, $section, $start_date, $end_date);
					$studentAttendanceData['total_Lectures_Missed'] = $this->getClassesMissed($key, $academic_modules_allocation_id, $start_date, $end_date);
					$studentAttendanceData['contact_Hours'] = $this->getModuleContactHours($academic_modules_allocation_id);
					$studentAttendanceData['academic_Year'] = $academic_year;
					$studentAttendanceData['semester'] = $semester;
					if($studentAttendanceData['total_Lectures_Delivered'] != 0)
						$studentAttendanceData['percentage'] = ($studentAttendanceData['total_Lectures_Delivered'] - $studentAttendanceData['total_Lectures_Missed']) / $studentAttendanceData['total_Lectures_Delivered'];
					else 
						$studentAttendanceData['percentage'] = 100;
					
					if($studentAttendanceData['percentage'] >= 90)
						$studentAttendanceData['eligibility_Status'] = 'Eligible';
					else
						$studentAttendanceData['eligibility_Status'] = 'Not Eligible';
					$studentAttendanceData['academic_Modules_Id'] = $academic_modules_id;
					
					$action = new Insert('student_consolidated_attendance_record');
					$action->values($studentAttendanceData);
					
					$sql = new Sql($this->dbAdapter);
					$stmt = $sql->prepareStatementForSqlObject($action);
					$result = $stmt->execute();
				}
			}
		}
		$this->recordStudentAttendanceGeneration($data['programmes_id'], $data['year']);
		
		return;
	}
	
	/*
	* Get the previous academic year
	*/
	
	private function getPreviousAcademicYear($academic_year)
	{
		$years = explode("-", $academic_year);
		return (($years[0]-1)."-".($years[0]));
	}
	
	/*
	* Get the max. duration of Programmes for Organisation
	*/
	
	public function getMaxProgrammeDuration($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'programmes'));
		$select->columns(array(new Expression ('MAX(programme_duration) as max_duration')));
		$select->where('t1.organisation_id = ' .$organisation_id);
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$years = array();
		$index = 1;
		foreach ($resultSet as $res) {
			$tmp_number = $res['max_duration'];
			preg_match_all('!\d+!', $tmp_number, $matches);
			$max_years = implode(' ', $matches[0]);
		}
		
		for($i=1; $i<=($max_years); $i++){
				$years[$i] = $i ." Year";
		}
		
		return $years;
	}
	
	/*
	* Get the list of Months for Present Semester
	*/
	
	public function getMonthList($organisation_id)
	{
		$start_month = NULL;
		$months = array();
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'academic_calendar'))
					->columns(array('academic_year', 'from_date'))
				->join(array('t2' => 'academic_calendar_events'), 
						't1.academic_event = t2.id', array('academic_event'));
		$select->where(array('from_date <= ? ' => date('Y-m-d')));
		$select->where(array('t2.organisation_id = ' .$organisation_id));
                
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$start_month = date("m", strtotime($set['from_date']));
		}
		
		for($m = (int)$start_month; $m<= date('m'); $m++){
			$months[$m] = date('F', mktime(0,0,0, $m,1, date('Y')));
		}
		return $months;
	}
	
	/*
	* Get the Name of Programme for Displaying
	*/
	
	public function getProgrammeName($programme_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'programmes'));
		$select->columns(array('programme_name'));
		$select->where('t1.id = ' .$programme_id);
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach ($resultSet as $res) {
			return $res['programme_name'];
		}
	}
	
	/*
	* Get the programme id given the academic modules allocation id
	*/
	
	public function getProgrammeId($academic_modules_allocation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'academic_modules_allocation'))
					->join(array('t2' => 'academic_modules'), 
                            't1.academic_modules_id = t2.id', array('programmes_id'))
                    ->where('t1.id = ' .$academic_modules_allocation_id);
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach ($resultSet as $res) {
			return $res['programmes_id'];
		}
	}
	
	/*
	* Get the name of module for displaying
	*/
	
	public function getModuleCode($academic_modules_allocation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t3' => 'academic_modules_allocation'))
						->columns(array('id'))
				->join(array('t4' => 'academic_modules'), 
						't3.academic_modules_id = t4.id', array('module_code'));
		$select->where('t3.id = ' .$academic_modules_allocation_id);
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach ($resultSet as $res) {
			return $res['module_code'];
		}
	}
	
	/*
	* Get the timetable id
	*/
	
	private function getAcademicTimetableId($day, $start_time, $end_time, $academic_modules_allocation_id, $section)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'academic_timetable'))
						->columns(array('id'));
		$select->where->like('t1.day', $day);
		$select->where(array('t1.academic_modules_allocation_id' => $academic_modules_allocation_id));
		$select->where(array('t1.from_time' => $start_time));
		$select->where(array('t1.to_time' => $end_time));
		$select->where(array('t1.group' => $section));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach ($resultSet as $res) {
			return $res['id'];
		}
	}
	
	/*
	* Get the Year of Enrollment
	*/
	
	private function getAcademicModulesAllocationByProgramme($programmes_id, $year, $academic_year)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'academic_modules_allocation'))
						->columns(array('id', 'academic_modules_id'));
		$select->where('t1.programmes_id = ' .$programmes_id);
		$select->where(array('t1.academic_year' => $academic_year));
		$select->where(array('t1.year' => $year));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$academic_modules_allocation_id = array();
		foreach ($resultSet as $res) {
			$academic_modules_allocation_id[$res['id']] = $res['academic_modules_id'];
		}
		return $academic_modules_allocation_id;
	}
	
	/*
	* Get the Dates for Semesters
	*/
	
	private function getSemesterDates($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'academic_calendar'))
					->columns(array('academic_year', 'from_date', 'to_date'))
				->join(array('t2' => 'academic_calendar_events'), 
						't1.academic_event = t2.id', array('academic_event'));
		$select->where(array('from_date <= ? ' => date('Y-m-d')));
		$select->where(array('to_date >= ? ' => date('Y-m-d')));
		$select->where('t2.organisation_id = ' .$organisation_id);
                
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$semester_dates = array();
		
		foreach($resultSet as $set){
			$semester_dates['start_date'] = $set['from_date'];
			$semester_dates['end_date'] = $set['to_date'];
		}
		return $semester_dates;
	}
	
	/*
	* Insert into table to record which programme and year has been generated
	*/ 
	
	private function recordStudentAttendanceGeneration($programmes_id, $year)
	{
		$organisation_id = $this->getOrganisationIdByProgramme($programmes_id);
		//$semester = $this->getSemester($organisation_id);
		//$academic_year = $this->getAcademicYear($semester);

		$academic_event_details = $this->getSemester($organisation_id);

        $semester = $academic_event_details['academic_event'];
        $academic_year = $this->getAcademicYear($academic_event_details);
		
		$attendanceData['programmes_id'] = $programmes_id;
		$attendanceData['year'] = $year;
		$attendanceData['semester'] = $semester;
		$attendanceData['academic_year'] = $academic_year;
		
		$action = new Insert('student_consolidated_attendance_generation');
		$action->values($attendanceData);
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		return;
	}
		
	/**
	* @return array/StudentAttendance()
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

	/*
	* Get module_allocation_module type based on the academic_module_allocation
	*/
	
	public function getModuleAllocationDetails($academic_modules_allocation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_modules_allocation'));
		$select->where(array('id' =>$academic_modules_allocation_id));
		$select->columns(array('module_type'));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			return $set['module_type'];
		}
	}
        
}

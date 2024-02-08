<?php

namespace StudentLeave\Mapper;

use StudentLeave\Model\StudentLeave;
use StudentLeave\Model\StudentLeaveCategory;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements StudentLeaveMapperInterface
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
	 * @var \StudentLeave\Model\StudentLeaveInterface
	*/
	protected $leavePrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			StudentLeave $leavePrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->leavePrototype = $leavePrototype;
	}
	
	/**
	* @param int/String $id
	* @return StudentLeave
	* @throws \InvalidArgumentException
	*/

	public function getUserDetailsId($username, $tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'employee_details'){
			$select->from(array('t1' => $tableName));
			$select->where(array('emp_id' =>$username));
			$select->columns(array('id'));
		}
		if($tableName == 'student'){
			$select->from(array('t1' => $tableName));
			$select->where(array('student_id' =>$username));
			$select->columns(array('id'));
		}
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
		
	}

	public function getOrganisationId($username, $usertype)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($usertype == '1'){
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


	public function findLeave($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student_leave')) 
                    ->join(array('t2' => 'student_leave_category'), 
                            't2.id = t1.student_leave_category_id', array('leave_category'))
                    ->join(array('t3' => 'student'), 
                            't1.student_id = t3.id', array('first_name','middle_name', 'last_name', 'studentId' => 'student_id', 'programmes_id'))
                    ->join(array('t4' => 'programmes'),
                			't4.id = t3.programmes_id', array('programme_name'))
                    ->join(array('t5' => 'student_semester_registration'),
                			't5.student_id = t3.id', array('semester_id'))
                    ->join(array('t6' => 'student_semester'),
                			't6.id = t5.semester_id', array('semester', 'programme_year_id'))
                    ->join(array('t7' => 'programme_year'),
                			't7.id = t6.programme_year_id', array('year'))
					->where(array('t1.id = ? ' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
                
        $resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}

	public function getFileName($leave_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student_leave')) 
				->columns(array('evidence_file'))
				->where('t1.id = ' .$leave_id);
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}

	
	public function findStudentLeave($id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select('hr_development');
            $select->where(array('id = ? ' => $id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();


            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->leavePrototype);
            }

            throw new \InvalidArgumentException("StudentLeave Proposal with given ID: ($id) not found");
	}
	
	/**
	* @return array/StudentLeave()
	*/
	public function findAll($tableName, $organisation_id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => $tableName))
            	   ->join(array('t2' => 'user_role'),// join expression
            	   		't2.id = t1.approval_by', array('rolename')); 
            $select->where(array('t1.organisation_id' => $organisation_id));

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
	 
	public function findLeaveCategory($id) 
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student_leave_category'))
			   ->join(array('t2' => 'user_role'),
					't2.id = t1.approval_by', array('rolename'));
		$select->where(array('t1.id = ?' => $id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}

	public function crossCheckStdLeaveCategory($leave_category, $organisation_id, $id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select(); 

        if($id != NULL){
        	$select->from(array('t1' => 'student_leave_category'));
        	$select->where(array('t1.id != ?' => $id, 't1.leave_category' => $leave_category, 't1.organisation_id' => $organisation_id));
        }else{
        	$select->from(array('t1' => 'student_leave_category'));
        	$select->where(array('t1.leave_category' => $leave_category, 't1.organisation_id' => $organisation_id));
        }
        
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $leaveCategory = NULL;
        foreach($resultSet as $set){
            $leaveCategory = $set['leave_category'];
        }
        return $leaveCategory;
	}


	public function getAppliedLeaveCategory($leave_category, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select(); 

    	$select->from(array('t1' => 'student_leave_category'));
    	$select->where(array('t1.id' => $leave_category, 't1.organisation_id' => $organisation_id));        
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $leaveCategory = NULL;
        foreach($resultSet as $set){
            $leaveCategory = $set['leave_category'];
        }
        return $leaveCategory;
	}

	public function checkStudentHostelAllocation($student_id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select(); 

        //var_dump($student_id); die();
    	$select->from(array('t1' => 'student_hostels'));
    	$select->where(array('t1.student_id' => $student_id, 't1.year' => date('Y')));        
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $hostelAllocation = NULL;
        foreach($resultSet as $set){
            $hostelAllocation = $set['id'];
        }
        //The if function need to be removed once hostel allocation is used by the colleges.
        if ($hostelAllocation == NULL) {
        	$hostelAllocation = 1;
        } else {
        	$hostelAllocation;
        }
        return $hostelAllocation;
	}

	public function getAppliedLeaveList($student_id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select(); 

    	$select->from(array('t1' => 'student_leave'))
    		   ->join(array('t2' => 'student_leave_category'),
    					't2.id = t1.student_leave_category_id', array('leave_category', 'approval_by'));
    	$select->where(array('t1.student_id' => $student_id));        
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}

	public function getAppliedLastDate($student_id)
	{	
		$currentDate = date('Y-m-d');
		//var_dump($currentDate); die();

		$action1 = new Update('student_leave');
		$action1->set(array('leave_status' => 'Rejected'));
		$action1->where(array('from_date < ?' => $currentDate));
		$action1->where(array('leave_status = ?' => 'Pending'));
		$action1->where(array('student_id = ?' => $student_id));
		
		$sql1 = new Sql($this->dbAdapter);
		$stmt1 = $sql1->prepareStatementForSqlObject($action1);
		$result1 = $stmt1->execute();

		$sql = new Sql($this->dbAdapter);
        $select = $sql->select(); 

    	$select->from(array('t1' => 'student_leave'))    			
    		   ->join(array('t2' => 'student_leave_category'),
    					't2.id = t1.student_leave_category_id', array('leave_category', 'approval_by'));
    	$select->where(array('t1.student_id' => $student_id)); 
    	$select->where(array('t1.leave_status != ?' => 'Rejected'));        
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        $lastAppliedDate = NULL;
        foreach($resultSet as $set){
        	$lastAppliedDate['from_date'] = $set['from_date'];
				
        }

        //var_dump($lastAppliedDate); die();
        return $lastAppliedDate;

       	
	}


	public function getSemesterDuration($organisation_id)
	{
		$academic_session = $this->getAcademicSession($organisation_id);
		$current_academic_session = $academic_session['academic_session_id'];
		$semester = $academic_session['academic_event'];
		$academic_year = $academic_session['academic_year'];

		$sql = new Sql($this->dbAdapter);
        $select = $sql->select(); 

    	$select->from(array('t1' => 'academic_calendar'))
    		   ->join(array('t2' => 'academic_calendar_events'), 
                        't1.academic_event = t2.id', array('academic_event', 'academic_session_id'));
    	$select->where(array('t2.academic_session_id' => $current_academic_session, 't1.academic_year' => $academic_year));        
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function listAllLeave($status, $employee_details_id, $userrole, $organisation_id)
	{
		$check_assigned_officiating = $this->checkOwnAssignedOfficiating($userrole);	

		if($check_assigned_officiating){
			return;
		}
		else{ 
			$user_role_id = $this->getUserRoleId($userrole, $organisation_id);

			$sql = new Sql($this->dbAdapter);
			$select = $sql->select();
					
			//first get the department, organisation and authtype for the user role
			$select->from(array('t1' => 'student_leave_category'));
			$select->where(array('t1.approval_by' => $user_role_id, 't1.organisation_id' => $organisation_id));
			
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);

			foreach($resultSet as $tmp_data){
				$leave_auth[$tmp_data['id']][$tmp_data['leave_category']] = $tmp_data['leave_category'];
			}

			
			$officiating_role = $this->getOfficiatingRole($employee_details_id);
			if($officiating_role){  
				$user_role_id = $this->getUserRoleId($officiating_role, $organisation_id);
						
				//first get the department, organisation and authtype for the user role
				$select2 = $sql->select();
				$select2->from(array('t1' => 'student_leave_category'));
				$select2->where(array('t1.approval_by' => $user_role_id, 't1.organisation_id' => $organisation_id));
				
				$stmt2 = $sql->prepareStatementForSqlObject($select2);
				$result2 = $stmt2->execute();
				
				$resultSet2 = new ResultSet();
				$resultSet2->initialize($result2);

				//officiating role	
				foreach($resultSet2 as $tmp_data2){
					$leave_auth[$tmp_data2['id']][$tmp_data2['leave_category']] = $tmp_data2['leave_category'];
				}
			}

			$student_list = array();
			if(!empty($leave_auth)){
				foreach($leave_auth as $key1=>$value1){
					foreach ($value1 as $key2) {
						$select4 = $sql->select();

						$select4->from(array('t1' => 'student_leave'))
							   ->join(array('t2' => 'student_leave_category'),
									't2.id = t1.student_leave_category_id', array('leave_category'))
							   ->join(array('t3' => 'student'),
									't3.id = t1.student_id', array('first_name', 'middle_name', 'last_name', 'student_id'));
						$select4->where(array('t1.from_date >= ? ' => date('Y'.'-01-01'), 't2.approval_by = ? ' => $user_role_id, 't1.leave_status' => $status));
						
						$stmt4 = $sql->prepareStatementForSqlObject($select4);
						$result4 = $stmt4->execute();
						
						$resultSet4 = new ResultSet();
						$resultSet4->initialize($result4);
						$student_list = array();
						foreach ($resultSet4 as $set4) {
							$student_list[] = $set4;
						}
						/*if($key2 == 'Weekday Leave'){
							$select4 = $sql->select();

							$select4->from(array('t1' => 'student_leave'))
								   ->join(array('t2' => 'student_leave_category'),
										't2.id = t1.student_leave_category_id', array('leave_category'))
								   ->join(array('t3' => 'student'),
										't3.id = t1.student_id', array('first_name', 'middle_name', 'last_name', 'student_id'));
							$select4->where(array('t1.from_date >= ? ' => date('Y'.'-01-01'), 't1.student_leave_category_id = ? ' => $key1, 't1.leave_status' => $status));
							
							$stmt4 = $sql->prepareStatementForSqlObject($select4);
							$result4 = $stmt4->execute();
							
							$resultSet4 = new ResultSet();
							$resultSet4->initialize($result4);
							$student_list = array();
							foreach ($resultSet4 as $set4) {
								$student_list[] = $set4;
							}
						}
				
						else if($key2 == 'Weekend Leave'){
							$student_list = array();
							$hostel_student_list = $this->getHostelStudentList($employee_details_id, $organisation_id);
							
							foreach($hostel_student_list as $key=>$value){						
								$select1 = $sql->select();

								$select1->from(array('t1' => 'student_leave'))
									   ->join(array('t2' => 'student_leave_category'),
											't2.id = t1.student_leave_category_id', array('leave_category'))
									   ->join(array('t3' => 'student'),
											't3.id = t1.student_id', array('first_name', 'middle_name', 'last_name', 'student_id'));
								$select1->where(array('t1.from_date >= ? ' => date('Y'.'-01-01'), 't1.student_leave_category_id = ? ' => $key1, 't1.leave_status' => $status, 't1.student_id' => $key));
								
								$stmt1 = $sql->prepareStatementForSqlObject($select1);
								$result1 = $stmt1->execute();
								
								$resultSet1 = new ResultSet();
								$resultSet1->initialize($result1);
								$student_list = array();
								foreach($resultSet1 as $set1){
									$student_list[] = $set1;
								}
							}
						}
						else if($key2 == 'Day Outing'){
							$select5 = $sql->select();

							$select5->from(array('t1' => 'student_leave'))
								   ->join(array('t2' => 'student_leave_category'),
										't2.id = t1.student_leave_category_id', array('leave_category'))
								   ->join(array('t3' => 'student'),
										't3.id = t1.student_id', array('first_name', 'middle_name', 'last_name', 'student_id'));
							$select5->where(array('t1.from_date >= ? ' => date('Y'.'-01-01'), 't1.student_leave_category_id = ? ' => $key1, 't1.leave_status' => $status));
							
							$stmt5 = $sql->prepareStatementForSqlObject($select5);
							$result5 = $stmt5->execute();
							
							$resultSet5 = new ResultSet();
							$resultSet5->initialize($result5);
							$student_list = array();
							foreach ($resultSet5 as $set5) {
								$student_list[] = $set5;
							}
						}*/
					} 
				}
			}	
			return $student_list;
		}
	}

		
	/**
	 * 
	 * @param type $StudentLeaveInterface
	 * 
	 * to save Vision and Mission Details
	 */
	
	public function saveDetails(StudentLeave $leaveObject)
	{
		$leaveData = $this->hydrator->extract($leaveObject);
		unset($leaveData['id']);

		//var_dump($leaveData); die();

		$file_name = $leaveData['evidence_File'];
        $leaveData['evidence_File'] = $file_name['tmp_name'];
        $leaveData['student_Leave_Category_Id'] = $leaveData['student_Leave_Category_Id'];	

        $sql1 = new Sql($this->dbAdapter);
		$select1 = $sql1->select();

        $select1->from(array('t1' => 'student_leave_category'));
		$select1->where(array('t1.id = ? ' => $leaveData['student_Leave_Category_Id']));
			
		$stmt1 = $sql1->prepareStatementForSqlObject($select1);
		$result1 = $stmt1->execute();
			
		$resultSet1 = new ResultSet();
		$resultSet1->initialize($result1);
		$student_list = array();
		foreach ($resultSet1 as $set1) {
			$student_list['leave_category'] = $set1['leave_category'];
		}

		if($student_list['leave_category'] == 'Day Outing'){
			$leaveData['from_Date'] = date("Y-m-d", strtotime(substr($leaveData['from_Date'], 0,10)));
    		$leaveData['to_Date'] = date("Y-m-d", strtotime(substr($leaveData['from_Date'], 0,10)));
		} else {
			$leaveData['from_Date'] = date("Y-m-d", strtotime(substr($leaveData['from_Date'], 0,10)));
    		$leaveData['to_Date'] = date("Y-m-d", strtotime(substr($leaveData['to_Date'], 0,10)));
		}
		
		if($leaveObject->getId()) {
			//ID present, so it is an update
			$action = new Update('student_leave');
			$action->set($leaveData);
			$action->where(array('id = ?' => $leaveObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('student_leave');
			$action->values($leaveData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $leaveObject->setId($newId);
			}
			return $leaveObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/**
	 * 
	 * @param type $StudentLeaveInterface
	 * 
	 * to save Vision and Mission Details
	 */
	
	public function saveLeaveCategory(StudentLeaveCategory $leaveObject)
	{
		$leaveData = $this->hydrator->extract($leaveObject);
		unset($leaveData['id']);
		
		if($leaveObject->getId()) {
			//ID present, so it is an update
			$action = new Update('student_leave_category');
			$action->set($leaveData);
			$action->where(array('id = ?' => $leaveObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('student_leave_category');
			$action->values($leaveData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $leaveObject->setId($newId);
			}
			return $leaveObject;
		}
		
		throw new \Exception("Database Error");
	}


	public function updateLeave($id, $leaveStatus, $remarks, $employee_details_id)
	{
		$action = new Update('student_leave');
		$action->set(array('leave_status' => $leaveStatus, 'remarks' => $remarks, 'approved_by' => $employee_details_id));
		$action->where(array('id = ?' => $id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
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
            if($set['academic_event'] == 'Autumn Semester Duration'){
				$academic_session['academic_event'] = 'Autumn';
				$academic_session['academic_session_id'] = $set['academic_session_id'];
				$academic_session['academic_year'] = $set['academic_year'];
            }
            else if($set['academic_event'] == 'Spring Semester Duration'){
                $academic_session['academic_event'] = 'Spring';
				$academic_session['academic_session_id'] = $set['academic_session_id'];
				$academic_session['academic_year'] = $set['academic_year'];
            }
        }
        return $academic_session;
    }


    // Function to check whether the user have assigned his/ her own officiating
	public function checkOwnAssignedOfficiating($userrole)
	{
		$date = date('Y-m-d');
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'user_workflow_officiating'))
					->columns(array('id', 'officiating_supervisor','from_date','to_date','supervisor', 'supervisor_id', 'department'))
                    ->where(array('t1.supervisor' => $userrole, 't1.from_date <= ?' => $date, 't1.to_date >= ?' => $date));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$officiated = NULL;
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		foreach($resultSet as $set){
			$officiated = $set['officiating_supervisor'];
		}

		return $officiated;
	}


	public function getUserRoleId($userrole, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'user_role'))
					->columns(array('id', 'rolename', 'organisation_id'))
                    ->where(array('t1.rolename' => $userrole, 't1.organisation_id' => $organisation_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$user_role_id = NULL;
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		foreach($resultSet as $set){
			$user_role_id = $set['id'];
		}

		return $user_role_id;
	}


		 /*
	 * If Officiating, get new officiating role
	 */
	 
	 private function getOfficiatingRole($employee_details_id)
	 {
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
				
		//first get the department, organisation and authtype for the user role
		$select->from(array('t1' => 'user_workflow_officiating'))
					->columns(array('supervisor'));
		$select->where(array('t1.officiating_supervisor = ? ' => $employee_details_id));
		$select->where(array('from_date <= ? ' => date('Y-m-d')));
		$select->where(array('to_date >= ? ' => date('Y-m-d')));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		//officiating role
		$officiating_role = NULL;
		foreach($resultSet as $tmp_data){
			$officiating_role = $tmp_data['supervisor'];
		}
		
		return $officiating_role;
	 }


	 public function getLeaveAuthCategory($user_role_id, $organisation_id)
	 {
	 	$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
				
		//first get the department, organisation and authtype for the user role
		$select->from(array('t1' => 'student_leave_category'));
		$select->where(array('t1.approval_by' => $user_role_id, 't1.organisation_id' => $organisation_id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		//officiating role
		$leave_category = array();		
		foreach($resultSet as $tmp_data){
			$leave_category[$tmp_data['id']][$tmp_data['leave_category']] = $tmp_data['id'];
		}
		return $leave_category;
	 }


	 public function getHostelStudentList($employee_details_id, $organisation_id)
	 {
	 	$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
				
		//first get the department, organisation and authtype for the user role
		$select->from(array('t1' => 'student_hostels'))
			   ->join(array('t2' => 'hostel_rooms'),
					't2.id = t1.hostel_rooms_id', array('hostels_list_id'))
			   ->join(array('t3' => 'hostels_list'),
					't3.id = t2.hostels_list_id', array('provost_name', 'organisation_id'));
		$select->where(array('t3.provost_name' => $employee_details_id, 't3.organisation_id' => $organisation_id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		//officiating role
		$student_list = array();		
		foreach($resultSet as $set){
			$student_list[$set['student_id']][$set['hostels_list_id']] = $set['student_id'];
		}
		return $student_list;
	 }

	
	/**
	* @return array/StudentLeave()
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		$leave_category = 0;
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            if($tableName == 'user_role'){
            	$select->from(array('t1' => $tableName));
				$select->columns(array('id','rolename'));
				$select->where(array('t1.organisation_id' => $organisation_id)); 

				$stmt = $sql->prepareStatementForSqlObject($select);
	            $result = $stmt->execute();
				
				$resultSet = new ResultSet();
				$resultSet->initialize($result);
				
				//Need to make the resultSet as an array
				// e.g. 1=> Objective 1, 2 => Objective etc.
				
				$selectData = array();
				foreach($resultSet as $set)
				{
					$selectData[$set['id']] = $set['rolename'];
				}
				return $selectData;

            }

            if($tableName == 'student_leave_category'){
            	if($columnName == 'Day Outing'){
            		$select->from(array('t1' => $tableName));
					$select->columns(array('id','leave_category'));
					$select->where(array('t1.organisation_id' => $organisation_id)); 
					$select->where(array('t1.leave_category' => $columnName)); 
            	} else {
            		$select->from(array('t1' => $tableName));
					$select->columns(array('id','leave_category'));
					$select->where(array('t1.organisation_id' => $organisation_id)); 
					$select->where(array('t1.leave_category != ?' => 'Day Outing')); 
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
					$selectData[$set['id']] = $set['leave_category'];
				}

				return $selectData;
            }
			
			
	}
        
}
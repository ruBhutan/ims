<?php

namespace LeaveEncashment\Mapper;

use LeaveEncashment\Model\LeaveEncashment;
use LeaveEncashment\Model\Employee;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements LeaveEncashmentMapperInterface
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
	 * @var \LeaveEncashment\Model\LeaveEncashmentInterface
	*/
	protected $leavePrototype;
	
	/*
	 * @var \LeaveEncashment\Model\Employee
	*/
	protected $empPrototype;

	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			LeaveEncashment $leavePrototype,
			Employee $empPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->leavePrototype = $leavePrototype;
		$this->empPrototype = $empPrototype;
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
		$select->columns(array('id', 'departments_units_id', 'departments_id'));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* take username and returns Name and any other detail required
	*/
	
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
	* @return LeaveEncashment
	* @throws \InvalidArgumentException
	*/
	
	public function findEmployeeId($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select('employee_details');
		$select->where(array('emp_id = ? ' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();


		if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
				return $this->hydrator->hydrate($result->current(), $this->leavePrototype);
		}

		throw new \InvalidArgumentException("Employee Detail with given ID: ($id) not found");
	}

	
	/**
	* @param int/String $id
	* @return LeaveEncashment
	* @throws \InvalidArgumentException
	*/
	
	public function findEmployeeDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'emp_leave_encashment')) // base table
				->join(array('t2' => 'employee_details'), // join table with alias
						't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id')); // join expression

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

	}

	
	/**
	* @return array/LeaveEncashment()
	*/
	public function findAll($tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
            
	}
        
		
	/**
	 * 
	 * @param type $LeaveEncashmentInterface
	 * 
	 * to save Vision and Mission Details
	 */
	
	public function save(LeaveEncashment $leaveObject)
	{
		$leaveData = $this->hydrator->extract($leaveObject);
		unset($leaveData['id']);
		$leaveData['application_Date'] = date("Y-m-d", strtotime(substr($leaveData['application_Date'],0,10)));		
		
		if($leaveObject->getId()) {
			//ID present, so it is an update
			$action = new Update('emp_leave_encashment');
			$action->set($leaveData);
			$action->where(array('id = ?' => $leaveObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('emp_leave_encashment');
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
	
	/*
	* Get the leave balance of an employee
	*/	
	  
	public function getLeaveBalance($employee_details_id)
	{
		$leave_balance = NULL;
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'emp_leave_balance')) 
                    ->where('t1.employee_details_id = ' .$employee_details_id);
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$leave_balance = $set['earned_leave'];
		}
		
		return $leave_balance;
	}
	
	/*
	* Get whether the employee has encashed his/her leave or not.
	*/	
	  
	public function getLeaveEncashed($employee_details_id)
	{
		$leave_encashed_status = NULL;
		$id = 0;
		$leave_encashment_year = date('Y');
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'emp_leave_encashment')) 
                    ->columns(array('id'))
					->where(array('approval_date >= ? ' => $leave_encashment_year.'-06-30', 'approval_date <= ? ' => $leave_encashment_year.'-12-31'))
					//->where('t1.employee_details_id = ' .$employee_details_id);
					->where(array('t1.employee_details_id = ' .$employee_details_id, 't1.leave_encashment_status' => 'Approved'));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$id = $set['id'];
		}
		
		if($id)
			$leave_encashed_status = 'Encashed';
		else
			$leave_encashed_status = 'Not Encashed';
			
		return $leave_encashed_status;
	}


	public function crossCheckLeaveEncashment($employee_details_id)
	{
		$leave_encashment = NULL;
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'emp_leave_encashment')) 
                    ->where(array('t1.employee_details_id = ' .$employee_details_id, 't1.leave_encashment_status' => 'pending'));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$leave_encashment = $set['id'];
		}
		
		return $leave_encashment;
	}



	public function crossCheckApprovedLeaveEncashment($employee_details_id)
	{
		$leave_encashment_date = NULL;
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'emp_leave_encashment')) 
                    ->where(array('t1.employee_details_id = ' .$employee_details_id, 't1.leave_encashment_status' => 'Approved'))
                    ->order('t1.application_date DESC')
                    ->limit(1);
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$leave_encashment_date = $set['application_date'];
		}
		
		return $leave_encashment_date;
	}
	
	/*
	 * Get the list of leave encashment
	 */	
	  
	public function getLeaveEncashment($status, $employee_details_id, $organisation_id, $userrole, $departments_id)
	{
		$auth_type = 'Leave Encashment';
		$employee_leave_encashment = array();

		//Get whether the particular user have assigned his/ her officiating
		$check_assigned_officiating = $this->checkOwnAssignedOfficiating($userrole);

		if($check_assigned_officiating){
			return;
		}
		else{
			$check_authorized_role = $this->checkAuthorizedRole($userrole, $organisation_id);

			if(!empty($check_authorized_role)){
				$sql = new Sql($this->dbAdapter);
				$select = $sql->select();
						
				//first get the department, organisation and authtype for the user role
				$select->from(array('t1' => 'user_workflow'))
							->columns(array('role_department','department','type', 'role'));
				$select->where(array('t1.auth = ? ' => $userrole));
				//$select->where(array('t1.department = ? ' => $departments_id));
				$select->where(array('t1.organisation = ? ' => $organisation_id));
				$select->where(array('t1.type' => $auth_type));
				
				$stmt = $sql->prepareStatementForSqlObject($select);
				$result = $stmt->execute();
				
				$resultSet = new ResultSet();
				$resultSet->initialize($result); 
				
				foreach($resultSet as $tmp_data){
					$type_authorisation[$tmp_data['type']][$tmp_data['role_department']][$tmp_data['role']] = $tmp_data['role'];
					} 
				} 

				//get details of departments if username is officiating
				$officiating_role = $this->getOfficiatingRole($employee_details_id);

				//Need to redo previous sql statements for officiating role
				if(!empty($officiating_role)){
					$sql = new Sql($this->dbAdapter);
					$select3 = $sql->select();
					$select3->from(array('t1' => 'user_workflow'))
							->columns(array('role_department','department','type', 'role'));
					$select3->where(array('t1.auth = ? ' => $officiating_role));
					$select3->where(array('t1.organisation = ? ' => $organisation_id));
					$select3->where(array('t1.type' => $auth_type));
					
					$stmt3 = $sql->prepareStatementForSqlObject($select3);
					$result3 = $stmt3->execute();
					
					$resultSet3 = new ResultSet();
					$resultSet3->initialize($result3);
					
					foreach($resultSet3 as $tmp_data3){
						$type_authorisation[$tmp_data3['type']][$tmp_data3['role_department']][$tmp_data3['role']] = $tmp_data3['role'];
					} 
				} 

				if(!empty($type_authorisation)){
					foreach ($type_authorisation as $type => $value) {
						$applied_type = $type;
						foreach ($value as $role_department => $value2) { 
							foreach($value2 as $role){
								$authorizee_emp_ids = $this->getEmployeeIdByRoles($role, $role_department);
								
								foreach($authorizee_emp_ids as $value3){
									//get the list of employees
								$select2 = $sql->select();
								$select2->from(array('t1' => 'emp_leave_encashment'))
											->join(array('t2' => 'employee_details'), 
													't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'))
											->join(array('t3' => 'emp_leave_balance'),
		                							't3.employee_details_id = t1.employee_details_id', array('earned_leave'));
								$select2->where(array('t1.leave_encashment_status = ? ' => $status));
								$select2->where(array('t1.application_date >= ? ' => date('Y'.'-01-01', strtotime("-1 year"))));
								$select2->where(array('t2.departments_units_id ' => $role_department));
								//$select2->where(array('t1.employee_details_id != ?' => $employee_details_id));
								if($authorizee_emp_ids){
									$select2->where(array('t1.employee_details_id' => $value3));
								}
								
								$stmt2 = $sql->prepareStatementForSqlObject($select2);
								$result2 = $stmt2->execute();
								
								$resultSet2 = new ResultSet();
								$resultSet2->initialize($result2);
								foreach($resultSet2 as $set){
									$employee_leave_encashment[] = $set;
									} 
								}
							}
						}
					}

				}

				
				/*foreach ($type_authorisation as $type1 => $value1) {
					//$applied_type1 = $type1;
					foreach ($value1 as $role_department1 => $value4) { 
						//Check whether the authorizee role have assigned the officiating or not within the give time
						$officiating_authorizee = $this->getOfficiatingAuthorizee($value4); // var_dump($value4); die();

					    if(!empty($officiating_authorizee)){
					    	foreach($officiating_authorizee as $officiating){
								$officiating_authorizee_role = $this->getOfficiatingAuthorizeeRole($officiating);

								//get the list of employees
								$select4 = $sql->select();
								$select4->from(array('t1' => 'emp_leave_encashment'))
											->join(array('t2' => 'employee_details'), 
													't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'))
											->join(array('t3' => 'emp_leave_balance'),
		            								't3.employee_details_id = t1.employee_details_id', array('earned_leave'));
								$select4->where(array('t1.leave_encashment_status = ? ' => $status));
								$select4->where(array('t1.application_date >= ? ' => date('Y'.'-01-01', strtotime("-1 year"))));

								//if($officiating_authorizee_role != $value4){
									$select4->where(array('t1.employee_details_id' => $officiating_authorizee));
								//}
								
								$stmt4 = $sql->prepareStatementForSqlObject($select4);
								$result4 = $stmt4->execute();
								
								$resultSet4 = new ResultSet();
								$resultSet4->initialize($result4);
								foreach($resultSet4 as $set1){
									$employee_leave_encashment[] = $set1;
								}
									
							} 
					    } 
					}
								
				}*/
				return $employee_leave_encashment;
		}
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


	/*
	* To check whether the particular logged in role is in user_workflow table or not 
	*/

	private function checkAuthorizedRole($userrole, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
				
		//first get the department, organisation and authtype for the user role
		$select->from(array('t1' => 'user_workflow'));
		$select->where(array('t1.auth' => $userrole));
		$select->where(array('t1.organisation' => $organisation_id));
		$select->where->like('type','Leave Encashment');
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$role = array();
		foreach($resultSet as $set){
            $role[$set['id']] = $set['id'];
        }
        return $role;
	}


	public function getOfficiatingRole($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
				
		//first get the department, organisation and authtype for the user role
		$select->from(array('t1' => 'user_workflow_officiating'))
					->columns(array('supervisor'));
		$select->where(array('t1.officiating_supervisor = ? ' => $employee_details_id));
		$select->where(array('t1.from_date <= ? ' => date('Y-m-d'), 't1.to_date >= ? ' => date('Y-m-d')));
		//$select->where(array('t1.to_date >= ? ' => date('Y-m-d')));
		
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



	/*
	* Get employee ids given roles
	*/

	private function getEmployeeIdByRoles($role, $departments_units_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
				
		//first get the department, organisation and authtype for the user role
		$select->from(array('t1' => 'users'))
						->join(array('t2' => 'employee_details'), 
								't1.username = t2.emp_id', array('id'));
		$select->where(array('t1.role ' => $role));
		$select->where(array('t2.departments_units_id ' => $departments_units_id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$employee_ids = array();
		//$employee_details_id = array();
		foreach($resultSet as $set){
            $employee_ids[$set['id']] = $set['id'];

        } 
        
        //$employee_ids = array_splice($employee_ids, $employee_details_id);   	
        return $employee_ids;

	}



	public function getSupervisorDepartmentId($department_units_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'department_units'))
					->columns(array('departments_id'))
                    ->where(array('t1.id' =>$department_units_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$departments_id = NULL;
		foreach($resultSet as $set)
		{
			$departments_id = $set['departments_id'];
		}
		return $departments_id;
	}



	public function getOfficiatingAuthorizee($authorizee_role)
	{
		$date = date('Y-m-d');

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
				
		//first get the department, organisation and authtype for the user role
		$select->from(array('t1' => 'user_workflow_officiating'));
		$select->where(array('t1.from_date <= ?' => $date, 't1.to_date >= ?' => $date));
		$select->where(array('t1.supervisor' => $authorizee_role));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$officiating_authorizees = array();
		//$employee_details_id = array();
		foreach($resultSet as $set){
            $officiating_authorizees[$set['officiating_supervisor']] = $set['officiating_supervisor'];

        } 
        
        //$employee_ids = array_splice($employee_ids, $employee_details_id);   	
        return $officiating_authorizees;
	}


	public function getOfficiatingAuthorizeeRole($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
				
		//first get the department, organisation and authtype for the user role
		$select->from(array('t1' => 'employee_details'))
			   ->join(array('t2' => 'users'),
					't2.username = t1.emp_id', array('role'));
		$select->where(array('t1.id' => $employee_details_id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$officiated_role = NULL;
		//$employee_details_id = array();
		foreach($resultSet as $set){
            $officiated_role = $set['role'];

        } 
        
        //$employee_ids = array_splice($employee_ids, $employee_details_id);   	
        return $officiated_role;
	}



	public function getEmployeeIdBySupervisorRole($role, $departments_id)
	{
		$date = date('Y-m-d');

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
				
		//first get the department, organisation and authtype for the user role
		$select->from(array('t1' => 'user_workflow_officiating'));
		$select->where(array('t1.from_date <= ?' => $date, 't1.to_date >= ?' => $date));
		$select->where(array('t1.supervisor' => $role));
		$select->where(array('t1.department' => $departments_id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$employee_ids = array();
		//$employee_details_id = array();
		foreach($resultSet as $set){
            $employee_ids[$set['officiating_supervisor']] = $set['officiating_supervisor'];

        } 
        
        //$employee_ids = array_splice($employee_ids, $employee_details_id);   	
        return $employee_ids;
	}


	
	/*
	 * Get the leave encashment status
	 */	
	  
	public function getLeaveEncashmentStatus($id, $authority)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($authority == 'staff'){
			$select->from(array('t1' => 'emp_leave_encashment')) 
                    ->join(array('t2' => 'employee_details'), 
                            't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'))
                    ->where('t1.employee_details_id = ' .$id);
		}
		else if($authority == 'approval'){
			$select->from(array('t1' => 'emp_leave_encashment')) 
                    ->join(array('t2' => 'employee_details'), 
                            't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'))
                    ->where('t1.id = ' .$id);
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
		
	}



	public function getSupervisorEmailId($userrole, $departments_units_id)
	{
		$supervisor_email = array();
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'user_workflow'))
			   ->columns(array('auth', 'department', 'organisation'));
		$select->where(array('t1.role' =>$userrole, 't1.role_department' => $departments_units_id, 't1.type' => 'Leave Encashment'));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		foreach($resultSet as $set){
			$leave_encashment_auth[$set['auth']][$set['department']][$set['organisation']] = $set['auth'];
        } 
        
       if(!empty($leave_encashment_auth)){
       	foreach($leave_encashment_auth as $key=>$value){
       		foreach($value as $key1=>$value1){
       			foreach($value1 as $key2=>$value2){
       				$select = $sql->select();

					$select->from(array('t1' => 'users'))
						   ->join(array('t2' => 'employee_details'),
								't2.emp_id = t1.username', array('email'));
					$select->where(array('t1.role' =>$key, 't2.departments_id' => $key1, 't2.organisation_id' => $key2));
						
					$stmt = $sql->prepareStatementForSqlObject($select);
					$result = $stmt->execute();
					
					$resultSet = new ResultSet();
					$resultSet->initialize($result);

					//$email = array();
					foreach($resultSet as $set){
						$supervisor_email[] = $set['email'];
					} 
       			}
       		}
       	}
       } 
        return $supervisor_email;
	}


	public function getLeaveEncashmentApplicant($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'));
		$select->where(array('t1.id' => $employee_details_id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function listEmpApprovedLeaveEncashment($order_no, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		if($order_no == "NULL"){
			$select->from(array('t1' => 'emp_leave_encashment'))
                    		->join(array('t2' => 'employee_details'),
                			't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
                    		->where(array('t2.organisation_id' => $organisation_id, 't1.order_no is NULL', 't1.leave_encashment_status' => 'Approved'));
		}else if($order_no == "NOT NULL"){
			$select->from(array('t1' => 'emp_leave_encashment'))
                    		->join(array('t2' => 'employee_details'),
                			't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
                    		->where(array('t2.organisation_id' => $organisation_id, 't1.order_no is NOT NULL', 't1.leave_encashment_status' => 'Approved'));
		}
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
        	return $resultSet->initialize($result);
	}

	public function getEmployeeDetails($id)
	{  
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'emp_leave_encashment'))
                    ->join(array('t2' => 'employee_details'),
							't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
					->join(array('t3' => 'departments'),
							't3.id = t2.departments_id', array('department_name'))
					->join(array('t4' => 'department_units'),
							't4.id = t2.departments_units_id', array('unit_name'))
                    ->where(array('t1.id' => $id)); 
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getLeaveEncashmentDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'emp_leave_encashment'))
               ->where(array('t1.id' => $id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}

	public function getFileName($id, $column_name)
	{ 
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
			
		$select->from(array('t1' => 'emp_leave_encashment')) 
                ->columns(array($column_name))
				->where('t1.id = ' .$id);
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	 * Update the Leave Encashment (i.e. approve or reject)
	 */
	 
	public function updateLeaveEncashment($id, $status, $employee_details_id)
	{ //echo $employee_details_id; die();
		$leaveData['leave_Encashment_Status'] = $status;
		$leaveData['approval_Date'] = date('Y-m-d');
		$leaveData['update_By_Employee_Id'] = $employee_details_id;
		$leaveData['updated_Date'] = date('Y-m-d');
		
		$action = new Update('emp_leave_encashment');
		$action->set($leaveData);
		$action->where(array('id = ?' => $id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($leaveData['leave_Encashment_Status'] == 'Approved')
		{
			$this->updateLeaveBalance($id);
		}

		if($result instanceof ResultInterface) {
			return $status;
		}
		
		throw new \Exception("Database Error");
	}


	public function updateEmpLeaveEncashmentOrder($data, $id)
	{
		$leave_encashment_order_no = $data['order_no'];

		$leave_encashment_order_date = date("Y-m-d", strtotime(substr($data['order_date'],0,10)));

		$order_file = $data['order_file'];
		$leave_encashment_order_file = $order_file['tmp_name'];

        $action = new Update('emp_leave_encashment');
        $action->set(array('order_no' => $leave_encashment_order_no, 'order_date' => $leave_encashment_order_date, 'order_file' => $leave_encashment_order_file));
        $action->where(array('id = ?' => $id));

        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
	}
	
	/*
	* Update Leave Balance when Leave Encashment is Approved
	*/
	
	public function updateLeaveBalance($id)
	{
		//first get the leave balance of the employee
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_leave_encashment'))
					->columns(array('employee_details_id'))
					->join(array('t2' => 'emp_leave_balance'), 
                            't1.employee_details_id = t2.employee_details_id', array('earned_leave'))
                    ->where('t1.id = ' .$id);
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
				
		$earned_leave = NULL;
		$employee_id = NULL;
		foreach($resultSet as $set){
			$earned_leave = $set['earned_leave'];
			$employee_id = $set['employee_details_id'];
		}		
		
		$leaveData['earned_Leave'] = $earned_leave - 30;
		
		$action = new Update('emp_leave_balance');
		$action->set($leaveData);
		$action->where(array('employee_details_id = ?' => $employee_id));
		
		$sql2 = new Sql($this->dbAdapter);
		$stmt2 = $sql2->prepareStatementForSqlObject($action);
		$result2 = $stmt2->execute();

		if($result instanceof ResultInterface) {
			return $id;
		}
		
		throw new \Exception("Database Error");
	}
	
	/**
	* @return array/LeaveEncashment()
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array('id',$columnName)); 

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
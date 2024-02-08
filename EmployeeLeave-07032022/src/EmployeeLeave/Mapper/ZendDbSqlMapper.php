<?php

namespace EmployeeLeave\Mapper;

use EmployeeLeave\Model\EmployeeLeave;
use EmployeeLeave\Model\OfficiatingSupervisor;
use EmployeeLeave\Model\OnbehalfEmployeeLeave;
use EmployeeLeave\Model\CancelledLeave;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Having;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\where;
use Zend\Db\Sql\Group;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements EmployeeLeaveMapperInterface
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
	 * @var \EmployeeLeave\Model\EmployeeLeaveInterface
	*/
	protected $leavePrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			EmployeeLeave $leavePrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->leavePrototype = $leavePrototype;
	}
	
	/**
	* @param int/String $id
	* @return EmployeeLeave
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


	public function getSupervisorEmailId($userrole, $departments_units_id, $emp_leave_category_id)
	{
		$leave_data = $this->listLeaveCategoryList('emp_leave_category', 'id');

		$applied_leave_category = array_search($emp_leave_category_id, $leave_data); 

		$email = array();
		$supervisor_details = array();
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'user_workflow'))
			   ->columns(array('auth', 'department', 'organisation'));
		$select->where(array('t1.role' =>$userrole, 't1.role_department' => $departments_units_id, 't1.type' => $applied_leave_category));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		foreach($resultSet as $set){
			$supervisor_details[$set['auth']][$set['department']][$set['organisation']] = $set['organisation'];
        }

        
        if(!empty($supervisor_details)){
        	foreach($supervisor_details as $auth => $value){ 
	        	foreach($value as $department => $value1){
	        		foreach($value1 as $organisation){
						$supervisor_ids = $this->getSupervisorIdByRole($auth, $department);
						foreach($supervisor_ids as $supervisorId){
							$officiating = $this->getSupervisorOfficiating($supervisorId, $auth, $department);
							
							if(!empty($officiating)){
								foreach($officiating as $officiating_supervisor_id){
									$sql2 = new Sql($this->dbAdapter);
									$select2 = $sql2->select();

									$select2->from(array('t1' => 'employee_details'));
									$select2->where(array('t1.id' =>$officiating_supervisor_id));
										
									$stmt2 = $sql2->prepareStatementForSqlObject($select2);
									$result2 = $stmt2->execute();
									
									$resultSet2 = new ResultSet();
									$resultSet2->initialize($result2);

									//$email = array();
									foreach($resultSet2 as $set2){
										$email[] = $set2['email'];
									}
								}
							}
							else{
								$sql1 = new Sql($this->dbAdapter);
								$select1 = $sql1->select();

								$select1->from(array('t1' => 'employee_details'));
								$select1->where(array('t1.id' =>$supervisorId));
									
								$stmt1 = $sql1->prepareStatementForSqlObject($select1);
								$result1 = $stmt1->execute();
								
								$resultSet1 = new ResultSet();
								$resultSet1->initialize($result1);

								//$email = array();
								foreach($resultSet1 as $set1){
									$email[] = $set1['email'];
								} 
							} 
						} 					
	        		}
	        	}
	        }
        }
        return $email;
	}


	public function getSupervisorIdByRole($supervisor_role, $supervisor_dept)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'))
			   ->join(array('t2' => 'users'),
					't1.emp_id = t2.username', array('username', 'role', 'user_status_id'));
		$select->where(array('t2.role' =>$supervisor_role, 't1.departments_id' => $supervisor_dept, 't2.user_status_id' => '1'));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$supervisor_ids = array();
		foreach($resultSet as $set){
			$supervisor_ids[] = $set['id'];
		} 
		return $supervisor_ids;
	}

	public function getSupervisorOfficiating($supervisorId, $supervisor_role, $supervisor_dept)
	{
		$date = date('Y-m-d');

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'user_workflow_officiating'));
		$select->where(array('t1.supervisor_id' => $supervisorId, 't1.supervisor' =>$supervisor_role, 't1.department' => $supervisor_dept, 't1.from_date <= ?' => $date, 't1.to_date >= ?' => $date));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$officiating_supervisor_id = array();
		foreach($resultSet as $set){
			$officiating_supervisor_id[] = $set['officiating_supervisor'];
		} 
		return $officiating_supervisor_id;
	}


	public function getSupervisorEmail($supervisor_role, $supervisor_dept, $supervisor_org)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'users'))
			   ->join(array('t2' => 'employee_details'),
					't2.emp_id = t1.username', array('email'));
		$select->where(array('t1.role' =>$supervisor_role, 't2.departments_id' => $supervisor_dept, 't2.organisation_id' => $supervisor_org));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$email = array();
		foreach($resultSet as $set){
			$email[] = $set['email'];
		} 
		return $email;
	}


	public function getLeaveApplicant($employee_details_id)
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


	public function getApprovedLeaveApplicantDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_leave'))
		       ->join(array('t2' => 'employee_details'),
					   't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'email'))
			   ->join(array('t3' => 'employee_details'),
						't3.id = t1.approved_by', array('afirst_name' => 'first_name', 'amiddle_name' => 'middle_name', 'alast_name' => 'last_name'));
		$select->where(array('t1.id' => $id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getPresidentDetails($organisation_id)
	{
		$abbr = $this->getOrganisationAbbr($organisation_id);
		$role = $abbr."_PRESIDENT";

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'users'))
		       ->join(array('t2' => 'employee_details'),
		   			't2.emp_id = t1.username', array('first_name', 'middle_name', 'last_name', 'email'));
		$select->where(array('t1.role' => $role, 't2.organisation_id' => $organisation_id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getOrganisationAbbr($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'organisation'))
		       ->columns(array('abbr'));
		$select->where(array('t1.id' => $organisation_id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$abbr = NULL;
		foreach($resultSet as $set){
			$abbr = $set['abbr'];
		}
		return $abbr;
	}


	public function getApprovedLeaveSubstitution($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_leave'))
		       ->join(array('t2' => 'employee_details'),
		   			't2.id = t1.substitution', array('sub_first_name' => 'first_name', 'sub_middle_name' => 'middle_name', 'sub_last_name' => 'last_name', 'sub_email' => 'email'));
		$select->where(array('t1.id' => $id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getApplicantName($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_leave'))
		       ->join(array('t2' => 'employee_details'),
		   			't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name'));
		$select->where(array('t1.id' => $id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$name = NULL;
		foreach($resultSet as $set){
			$name = $set['first_name'].' '.$set['middle_name'].' '.$set['last_name'];
		}
		return $name;
	}


	public function getOnBehalfStaffDetails($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'))
			   ->columns(array('departments_id', 'departments_units_id'))
			   ->join(array('t2' => 'users'),
					't2.username = t1.emp_id', array('role'));
		$select->where(array('t1.id' => $employee_details_id));
			
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
	* @return EmployeeLeave
	* @throws \InvalidArgumentException
	*/
	
	public function findLeave($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'emp_leave')) 
                    ->join(array('t2' => 'employee_details'), 
                            't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'))
                    ->join(array('t3' => 'employee_details'), 
                            't1.substitution = t3.id', array('sub_first_name'=>'first_name','sub_middle_name'=>'middle_name','sub_last_name'=>'last_name','sub_emp_id'=>'emp_id'))
					->where(array('t1.id = ? ' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
                
                $resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	
	/**
	* Find details of employees that have applied for leave
	*
	*/
	
	public function findEmployeeDetails($empIds)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select('employee_details');
		$select->where(array('id ' => $empIds));
		$select->columns(array('id','first_name','middle_name','last_name','emp_id'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
			
		$employeeData = array();
		foreach($resultSet as $set)
		{
			$employeeData[$set['id']] = $set['first_name'] . ' '. $set['middle_name'] .' '. $set['last_name'];
			$employeeData['emp_id' . $set['id']] = $set['emp_id'];
		}
		return $employeeData;

	}
	
	/**
	* Find details of employees that have applied for leave
	*/
	
	public function listLeaveCategory()
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select('emp_leave_category');
		$select->columns(array('id','leave_category'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
			
		$leaveCategory = array();
		foreach($resultSet as $set)
		{
			$leaveCategory[$set['id']] = $set['leave_category'];
		}
		return $leaveCategory;

	}



	public function getEmployeeOccupationalGroup($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'emp_position_title')) // base table
				->join(array('t2' => 'position_title'), // join table with alias
						't1.position_title_id = t2.id', array('position_category_id')) // join expression
				->join(array('t3' => 'position_category'),
						't3.id = t2.position_category_id', array('major_occupational_group_id'))
				->join(array('t4' => 'major_occupational_group'),
						't4.id = t3.major_occupational_group_id', array('id', 'major_occupational_group'))
				->where(array('t1.employee_details_id' => $employee_details_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$occupationalGroup = array();
		foreach($resultSet as $set){
			$occupationalGroup['id'] = $set['id'];
			$occupationalGroup['major_occupational_group'] = $set['major_occupational_group'];
		} 
		return $occupationalGroup;
	}

	
	/**
	* @return array/EmployeeLeave()
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
	* @return array/EmployeeLeave()
	*/
	public function findAllLeave($status, $employee_details_id, $userrole, $organisation_id, $departments_id)
	{  
		$check_assigned_officiating = $this->checkOwnAssignedOfficiating($userrole, $employee_details_id); 
		
		if($check_assigned_officiating){
			return;
		}else{
			
			$check_authorized_role = $this->checkAuthorizedRole($userrole, $organisation_id);
			$employee_leaves = array();		

			if(!empty($check_authorized_role)){ 
		
				$sql = new Sql($this->dbAdapter);
				$select = $sql->select();
						
				//first get the department, organisation and authtype for the user role
				$select->from(array('t1' => 'user_workflow'))
							->columns(array('role_department','department','type', 'role'));
				$select->where(array('t1.auth = ? ' => $userrole));
				//$select->where(array('t1.department = ? ' => $departments_id));
				$select->where(array('t1.organisation = ? ' => $organisation_id));
				$select->where->like('t1.type', '%Leave%');
				
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
	//	var_dump($officiating_role); die();	
			//Need to redo previous sql statements for officiating role
				if($officiating_role){
					$sql = new Sql($this->dbAdapter);
					$select3 = $sql->select();
					$select3->from(array('t1' => 'user_workflow'))
							->columns(array('role_department','department','type', 'role'));
					$select3->where(array('t1.auth = ? ' => $officiating_role));
					$select3->where(array('t1.organisation = ? ' => $organisation_id));
					$select3->where->like('t1.type', '%Leave%');
					
					$stmt3 = $sql->prepareStatementForSqlObject($select3);
					$result3 = $stmt3->execute();
					
					$resultSet3 = new ResultSet();
					$resultSet3->initialize($result3);
					
					foreach($resultSet3 as $tmp_data3){
						$type_authorisation[$tmp_data3['type']][$tmp_data3['role_department']][$tmp_data3['role']] = $tmp_data3['role'];
					} 
				}
			       		
				//$authorizee_emp_ids = $this->getEmployeeIdByRoles($authorizee_role);

				$leave_data = $this->listSelectData('emp_leave_category', 'leave_category'); 
				//var_dump($leave_data); die();
			                
	         //	$employee_leaves = array();      
			//if the role departments are empty, then user has no authority
				if(!empty($type_authorisation)){
					foreach($type_authorisation as $type => $value){	
						$applied_leave_categories = array_search($type, $leave_data); 
						foreach($value as $role_department => $value2){ 
												
							foreach($value2 as $role){ //var_dump($role); var_dump($role_department);

								$authorizee_emp_ids = $this->getEmployeeIdByRoles($role, $role_department);

								foreach($authorizee_emp_ids as $value3){
										
									//get the list of employees
									$select2 = $sql->select();
									$select2->from(array('t1' => 'emp_leave'))
												->join(array('t2' => 'employee_details'), 
														't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'));
									$select2->where(array('t1.leave_status = ? ' => $status));
									$select2->where(array('t1.from_date >= ? ' => date('Y'.'-01-01', strtotime("-1 year"))));
									$select2->where(array('t2.departments_units_id ' => $role_department));
									$select2->where(array('t1.emp_leave_category_id ' => $applied_leave_categories));
									$select2->where(array('t1.employee_details_id != ?' => $employee_details_id));
									if($authorizee_emp_ids){
										$select2->where(array('t1.employee_details_id' => $value3));
									}
									
									$stmt2 = $sql->prepareStatementForSqlObject($select2);
									$result2 = $stmt2->execute();
									
									$resultSet2 = new ResultSet();
									$resultSet2->initialize($result2);
									foreach($resultSet2 as $set){
										$employee_leaves[] = $set;
									} 
								}
							}
						} 
					}
				       //return $employee_leaves;	
				}

				/*$sql = new Sql($this->dbAdapter);
				$select1 = $sql->select();
				//first get the department, organisation and authtype for the user role
				$select1->from(array('t1' => 'user_workflow'))
							->columns(array('role_department','department','type', 'role'));
				$select1->where(array('t1.auth = ? ' => $userrole));
				//$select->where(array('t1.department = ? ' => $departments_id));
				$select1->where(array('t1.organisation = ? ' => $organisation_id));
				$select1->where->like('t1.type', '%Leave%');
				
				$stmt1 = $sql->prepareStatementForSqlObject($select1);
				$result1 = $stmt1->execute();
				
				$resultSet1 = new ResultSet();
				$resultSet1->initialize($result1); 
				
				foreach($resultSet1 as $tmp_data1){
					$type_authorisation1[$tmp_data1['type']][$tmp_data1['role_department']][$tmp_data1['role']] = $tmp_data1['role'];
				}

				if(!empty($type_authorisation1)){
					foreach ($type_authorisation1 as $type1 => $value1) {
						$applied_leave_categories1 = array_search($type1, $leave_data);
						foreach ($value1 as $role_department1 => $value4) {
							foreach ($value4 as $role1) { 
								$departments_id = $this->getSupervisorDepartmentId($role_department1);
								$officiating_supervisor_ids = $this->getEmployeeIdBySupervisorRole($role1, $departments_id);

								foreach ($officiating_supervisor_ids as $value5) { 
									//get the list of employees
									$select4 = $sql->select();
									$select4->from(array('t1' => 'emp_leave'))
												->join(array('t2' => 'employee_details'), 
														't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'));
									$select4->where(array('t1.leave_status = ? ' => $status));
									$select4->where(array('t1.from_date >= ? ' => date('Y'.'-01-01', strtotime("-1 year"))));
									//$select4->where(array('t2.departments_units_id ' => $role_department1));
									$select4->where(array('t1.emp_leave_category_id ' => $applied_leave_categories1));
									//$select2->where->notLike('t1.employee_details_id', $employee_details_id);
									if($officiating_supervisor_ids){
										$select4->where(array('t1.employee_details_id' => $value5));
									}
									
									$stmt4 = $sql->prepareStatementForSqlObject($select4);
									$result4 = $stmt4->execute();
									
									$resultSet4 = new ResultSet();
									$resultSet4->initialize($result4);
									foreach($resultSet4 as $set1){
										$employee_leaves[] = $set1;
									} 
								}
							}
						}
					}
				}*/

			 	return $employee_leaves;
			
		}				
	}


	public function getAuthType($userrole)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'user_workflow'));
        $select->where(array('t1.auth' => $userrole));
        $select->where->like('t1.type','%Leave%');

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        $auth = array();
        foreach($resultSet as $set){
        	$auth[$set['type']] = $set['type'];
        }
        return $auth;
	}


	// Function to check whether the user have assigned his/ her own officiating
	public function checkOwnAssignedOfficiating($userrole, $employee_details_id)
	{
		$date = date('Y-m-d');
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'user_workflow_officiating'))
					->columns(array('id', 'officiating_supervisor','from_date','to_date','supervisor', 'supervisor_id', 'department'))
                    ->where(array('t1.supervisor_id' => $employee_details_id, 't1.supervisor' => $userrole, 't1.from_date <= ?' => $date, 't1.to_date >= ?' => $date));

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
		$select->where->like('type','%Leave%');
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$role = array();
		foreach($resultSet as $set){
            $role[$set['type']][$set['role']][$set['role_department']] = $set['role_department'];
        }
        return $role;

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
         * Get the Department Units for HRO
         */
        
        public function getDepartmentUnits($organisation_id)
        {
            $units = array();
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'departments'))
                    ->columns(array('department_name'))
                    ->join(array('t2' => 'department_units'), 
                        't1.id = t2.departments_id', array('id'));
            $select->where(array('t1.organisation_id ' => $organisation_id));
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            $resultSet = new ResultSet();
            $resultSet->initialize($result);
            foreach($resultSet as $set){
                $units[] = $set['id'];
            }
            return $units;
        }
	
	/**
	* @return array/EmployeeLeave()
	*/
	public function listLeaveEmployee($role, $organisation_id)
	{
		//need to get the date as we do not need old leaves
		$date=('Y-m-d');
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_leave'));
		$select->where(array('from_date >= ? ' => $date));
		$select->columns(array('employee_details_id'));
		$select->group('employee_details_id');
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

	}


	public function getEmpApprovedLeaveList($organisation_id)
	{		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_leave'))
			   ->join(array('t2' => 'emp_leave_category'),
					't1.emp_leave_category_id = t2.id', array('leave_category'))
			   ->join(array('t3' => 'employee_details'),
					't1.employee_details_id = t3.id', array('first_name', 'middle_name', 'last_name', 'emp_id'));
        $select->where(array('t1.from_date >= ? ' => date('Y'.'-01-01'), 't3.organisation_id' => $organisation_id, 't1.leave_status' => 'Approved'));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
        
	/**
	 * 
	 * @param type $id
	 * 
	 * to find the Leave Type for a given $id
	 */
	 
	public function findLeaveType($id) 
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'emp_leave_category'));
		$select->where(array('id = ? ' => $id));
		$select->columns(array('leave_category'));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		return $resultSet;
	}
	
	/**
	 * 
	 * @param type $id
	 * 
	 * update leave status
	 */
	 
	public function updateLeave($id, $leaveStatus, $remarks, $employee_details_id) 
	{
		$leaveCategoryId = $this->getLeaveCategoryId($id);
		$employeeDetailsId = $this->getEmployeeDetailsId($id);  

		$action = new Update('emp_leave');
		$action->set(array('leave_status' => $leaveStatus, 'remarks' => $remarks, 'approved_by' => $employee_details_id));
		$action->where(array('id = ?' => $id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		if($leaveStatus == 'Approved'){
			$this->updateLeaveBalance($id);
			$this->updateStaffOfficiating($id);
			if($leaveCategoryId == '2' || $leaveCategoryId == '7' || $leaveCategoryId == '10' || $leaveCategoryId == '11')
			{
				$this->updateEmpLeaveBalanceStatus($employeeDetailsId);
			}
			else
			{
				return;
			}
		}
		
		return $resultSet;
	}


	/*
	* Function to get leave category id based on the id of emp leave
	*/
	public function getLeaveCategoryId($id)
	{		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'emp_leave'))
					->columns(array('emp_leave_category_id'))
					->where(array('id = ? ' => $id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$leaveCategoryId = NULL;
		foreach($resultSet as $set){
			$leaveCategoryId = $set['emp_leave_category_id'];
		}
		
		return $leaveCategoryId;
	}

	/*
	* Function to get employee details id based on the id of emp leave
	*/
	public function getEmployeeDetailsId($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'emp_leave'))
					->columns(array('employee_details_id'))
					->where(array('id = ? ' => $id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$employeeId = NULL;
		foreach($resultSet as $set){
			$employeeId = $set['employee_details_id'];
		}
		
		return $employeeId;
	}
	

	/*
	* When we update the leave status and if the leave is approved, we need to update the leave balance table
	*/
	
	public function updateLeaveBalance($id)
	{
		$leave_type = NULL;
		$days_of_leave = NULL;
		$employee_details_id = NULL;
		
		//get the type of leave and no of days from the leave application
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'emp_leave'))
					->columns(array('days_of_leave','employee_details_id'))
					->join(array('t2' => 'emp_leave_category'), 
							't1.emp_leave_category_id = t2.id', array('leave_category'))
					->where(array('t1.id = ? ' => $id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$days_of_leave = $set['days_of_leave'];
			$leave_type = $set['leave_category'];
			$employee_details_id = $set['employee_details_id'];
		}
		
		//get the leave balance for the employee 
		$employee_leave_balance = $this->getEmployeeLeaveBalance($employee_details_id);
		$casual_leave_balance = $employee_leave_balance['casual_leave'];
		$earned_leave_balance = $employee_leave_balance['earned_leave'];
		
		
		//Update the leave balance for the employee
		if($leave_type == 'Casual Leave'){
			$leave_balance = $casual_leave_balance-$days_of_leave;
			$action = new Update('emp_leave_balance');
			$action->set(array('casual_leave' => $leave_balance));
			$action->where(array('employee_details_id = ?' => $employee_details_id));
		} else if($leave_type == 'Earned Leave'){
			$leave_balance = $earned_leave_balance-$days_of_leave;
			$action = new Update('emp_leave_balance');
			$action->set(array('earned_leave' => $leave_balance));
			$action->where(array('employee_details_id = ?' => $employee_details_id));
		} else {
			return;
		}
		
		
		$sql2 = new Sql($this->dbAdapter);
		$stmt2 = $sql2->prepareStatementForSqlObject($action);
		$result2 = $stmt2->execute();

		$resultSet2 = new ResultSet();
		$resultSet2->initialize($result2);
		
		return;
		
	}


	public function updateStaffOfficiating($id)
	{
		$from_date = NULL;
		$to_date = NULL;
		$substitution = NULL;
		$employee_details_id = NULL;
		
		//get the type of leave and no of days from the leave application
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'emp_leave'))
					->columns(array('from_date', 'to_date', 'substitution', 'employee_details_id'))
					->where(array('t1.id = ? ' => $id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$from_date = $set['from_date'];
			$to_date = $set['to_date'];
			$substitution = $set['substitution'];
			$employee_details_id = $set['employee_details_id'];
		}

		//Call function to get emp_id and department
		$emp_id = NULL;
		$department = NULL;
		$applicant_details = $this->getLeaveApplicant($employee_details_id);
		foreach($applicant_details as $details){
			$emp_id = $details['emp_id'];
			$department = $details['departments_id'];
		}
		
		// Call function to get the staff role
		$staff_role = $this->getAppliedLeaveStaffRole($emp_id);

		$officiating_role = $this->getEmpOfficiatedRole($substitution, $from_date, $to_date, $staff_role);
		$check_own_officiating = $this->crossCheckOwnOfficiating($employee_details_id, $from_date);

		if(empty($officiating_role) || empty($check_own_officiating)){
			$leaveData = array();
			$leaveData['supervisor'] = $staff_role;
			$leaveData['department'] = $department;
			$leaveData['from_Date'] = $from_date;
			$leaveData['to_Date'] = $to_date;
			$leaveData['officiating_Supervisor'] = $substitution;
			$leaveData['supervisor_Id'] = $employee_details_id;
			$leaveData['remarks'] = "Officiating Staff";

			$action = new Insert('user_workflow_officiating');
			$action->values($leaveData);
		
			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();
		}else{
			return;
		}
	}



	//Function to get the applied leave staff role
	public function getAppliedLeaveStaffRole($emp_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'users'))
					->columns(array('role'))
					->where(array('t1.username = ? ' => $emp_id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$user_role = NULL;
		foreach($resultSet as $set){
			$user_role = $set['role'];
		}

		return $user_role;
	}


	public function updateEmpLeaveBalanceStatus($employee_details_id)
	{
		$action = new Update('emp_leave_balance');
		$action->set(array('emp_leave_status' => '1'));
		$action->where(array('employee_details_id = ?' => $employee_details_id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
	}



	public function updateEmpApprovedLeave(CancelledLeave $leaveObject)
	{
		$leaveData = $this->hydrator->extract($leaveObject);
		unset($leaveData['id']);
                
		$leaveData['from_Date'] = date("Y-m-d", strtotime(substr($leaveData['from_Date'],0,10)));
		$leaveData['to_Date'] = date("Y-m-d",strtotime(substr($leaveData['to_Date'],0,10)));
                
		//ID is not present, so its an insert
		$action = new Insert('emp_cancelled_leave');
		$action->values($leaveData);
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		$leave_category = $this->getAppliedLeaveCategory($leaveData['emp_Leave_Id']);
		//get the leave balance for the employee 
		$employee_leave_balance = $this->getEmployeeLeaveBalance($leaveData['employee_Details_Id']);
		$casual_leave_balance = $employee_leave_balance['casual_leave'];
		$earned_leave_balance = $employee_leave_balance['earned_leave'];
		
		//Update the leave balance for the employee
		if($leave_category == 'Casual Leave'){
			// To check whether the cancelled leave is in new financial year or not (10 makes sure that it is in new financial year).
			if($casual_leave_balance =='10'){
				$leave_balance = $earned_leave_balance+$leaveData['no_Of_Days'];
				$leave_action = new Update('emp_leave_balance');
				$leave_action->set(array('earned_leave' => $leave_balance));
				$leave_action->where(array('employee_details_id = ?' => $leaveData['employee_Details_Id']));

			} else {				
				$leave_balance = $casual_leave_balance+$leaveData['no_Of_Days'];
				$leave_action = new Update('emp_leave_balance');
				$leave_action->set(array('casual_leave' => $leave_balance));
				$leave_action->where(array('employee_details_id = ?' => $leaveData['employee_Details_Id']));
			}
		} else if($leave_category == 'Earned Leave'){
			$leave_balance = $earned_leave_balance+$leaveData['no_Of_Days'];
			$leave_action = new Update('emp_leave_balance');
			$leave_action->set(array('earned_leave' => $leave_balance));
			$leave_action->where(array('employee_details_id = ?' => $leaveData['employee_Details_Id']));
		} else {
			return;
		}

		$sql2 = new Sql($this->dbAdapter);
		$stmt2 = $sql2->prepareStatementForSqlObject($leave_action);
		$result2 = $stmt2->execute();

		$resultSet2 = new ResultSet();
		$resultSet2->initialize($result2);
		
		return;
	}



	public function getAppliedLeaveCategory($emp_leave_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'emp_leave'))
		       ->join(array('t2' => 'emp_leave_category'),
		   			't2.id = t1.emp_leave_category_id', array('leave_category'))
					->where(array('t1.id = ? ' => $emp_leave_id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$leave_category = NULL;
		foreach($resultSet as $set){
			$leave_category = $set['leave_category'];
		}
		return $leave_category;
	}
	
	/*
	* Get the leave balance for a given employee so we can update the leave balance if leave is approved
	*/
	
	public function getEmployeeLeaveBalance($employee_details_id)
	{
		$leave_details = array();
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'emp_leave_balance'))
					->columns(array('casual_leave','earned_leave'))
					->where(array('employee_details_id = ? ' => $employee_details_id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$leave_details['casual_leave'] = $set['casual_leave'];
			$leave_details['earned_leave'] = $set['earned_leave'];
		}
		
		return $leave_details;
	}
		
	/**
	 * 
	 * @param type $EmployeeLeaveInterface
	 * 
	 * to save Vision and Mission Details
	 */
	
	public function saveDetails(EmployeeLeave $leaveObject)
	{
		$leaveData = $this->hydrator->extract($leaveObject);
		unset($leaveData['id']);
		unset($leaveData['first_Name']);
		unset($leaveData['middle_Name']);
		unset($leaveData['last_Name']);
                
		$file_name = $leaveData['evidence_File'];
		$leaveData['evidence_File'] = $file_name['tmp_name'];
		$leaveData['from_Date'] = date("Y-m-d", strtotime(substr($leaveData['from_Date'],0,10)));
		$leaveData['to_Date'] = date("Y-m-d",strtotime(substr($leaveData['to_Date'],0,10))); 
                
		if($leaveObject->getId()) {
			//ID present, so it is an update
			$action = new Update('emp_leave');
			$action->set($leaveData);
			$action->where(array('id = ?' => $leaveObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('emp_leave');
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


	public function saveOnBehalfLeave(OnbehalfEmployeeLeave $leaveObject)
	{
		$leaveData = $this->hydrator->extract($leaveObject);
		unset($leaveData['id']); 
                
		$file_name = $leaveData['evidence_File'];
		$leaveData['evidence_File'] = $file_name['tmp_name'];
		$leaveData['from_Date'] = date("Y-m-d", strtotime(substr($leaveData['from_Date'],0,10)));
		$leaveData['to_Date'] = date("Y-m-d",strtotime(substr($leaveData['to_Date'],0,10))); 
		//var_dump($leaveData); die();
                
		if($leaveObject->getId()) {
			//ID present, so it is an update
			$action = new Update('emp_leave');
			$action->set($leaveData);
			$action->where(array('id = ?' => $leaveObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('emp_leave');
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
	* Save Officiating Officer
	*/
	 
	public function saveOfficiatingOfficer(OfficiatingSupervisor $leaveObject, $supervisor_id, $employee_details_id, $userrole)
	{
		$leaveData = $this->hydrator->extract($leaveObject);
		unset($leaveData['id']);

		//var_dump($employee_details_id); die();
		
		$department = $this->getSupervisorDepartment($supervisor_id);
		$role = $this->getSupervisorRole($supervisor_id);
		//set the data
		$leaveData['supervisor'] = $role;
		$leaveData['department'] = $department;
		$leaveData['from_Date'] = date("Y-m-d", strtotime(substr($leaveData['date_Range'],0,10)));
		$leaveData['to_Date'] = date("Y-m-d",strtotime(substr($leaveData['date_Range'],13,10)));
		$leaveData['supervisor_Id'] = $supervisor_id;
		$leaveData['added_employee_id'] = $employee_details_id;
		unset($leaveData['date_Range']);

		$evidence_file = $leaveData['evidence_File'];
		$leaveData['evidence_File'] = $evidence_file['tmp_name'];
				
		if($leaveObject->getId()) {
			//ID present, so it is an update
			$action = new Update('user_workflow_officiating');
			$action->set($leaveData);
			$action->where(array('id = ?' => $leaveObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('user_workflow_officiating');
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
	 *Save edited staff leave balance
	 **/
	 public function updateEmpLeaveBalance($id, $casual_leave, $earned_leave, $employee_details_id)
	 {
	 	$leaveData['casual_Leave'] = $casual_leave;
		$leaveData['earned_Leave'] = $earned_leave;
		$leaveData['update_By_Employee_Id'] = $employee_details_id;
		$leaveData['updated_Date'] = date('Y-m-d');
		
        $action = new Update('emp_leave_balance');
        $action->set($leaveData);
        $action->where(array('id = ?' => $id));

        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
	 }

	 /*
	 *Get particular selected staff has been already assigned officiating or not.
	 */
	 public function getEmpOfficiatedRole($officiating, $from_date, $to_date, $userrole)
	 {
	 	$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'user_workflow_officiating'));
        $select->where(array('t1.officiating_supervisor' =>$officiating, 't1.from_date <= ?' => $from_date, 't1.to_date >= ?' => $from_date, 't1.supervisor != ?' => $userrole));
		//$select->where(array('t1.officiating_supervisor' =>$officiating, 't1.from_date <= ?' => $to_date, 't1.to_date >= ?' => $to_date, 't1.supervisor != ?' => $userrole));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	 }


	 public function crossCheckCancelledLeave($emp_leave_id)
	 {
	 	$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_cancelled_leave'))
                    ->where(array('t1.emp_leave_id' =>$emp_leave_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$leave_id = NULL;
		foreach($resultSet as $set){
			$leave_id = $set['id'];
		}

		return $leave_id;
	 }


	 /*
	 *Check whether particular logged in user have already assigned his/her officiating within that date
	 **/
	 public function crossCheckOwnOfficiating($employee_details_id, $from_date)
	 {
	 	$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'user_workflow_officiating'))
                    ->where(array('t1.supervisor_id' =>$employee_details_id, 't1.from_date <= ?' => $from_date, 't1.to_date >= ?' => $from_date));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$officiating = NULL;
		foreach($resultSet as $set){
			$officiating = $set['id'];
		}

		return $officiating;
	 }
	 
	/*
	* Get list of officers to officiate
	*/
	 
	public function getOfficiatingList($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'))
					->columns(array('first_name','middle_name','last_name','emp_id'))
					->join(array('t2' => 'user_workflow_officiating'), 
                            't1.id = t2.officiating_supervisor')
                    ->where(array('t2.added_employee_id' =>$employee_details_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	 * Get Officiating Details
	 */
	 
	 public function getOfficiatingDetails($id)
	 {
		 $sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'user_workflow_officiating'))
                    ->where(array('t1.id = ? ' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	 }
	
	/*
	 * Get the list of employees to be assigned officiating role
	 */
	 
	 public function getEmployeeList($organisation_id)
	 {
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'))
					->columns(array('id','first_name','middle_name','last_name','emp_id'))
					->join(array('t2' => 'emp_position_level'), 
                            't1.id = t2.employee_details_id', array('position_level_id'))
					->join(array('t3' => 'position_level'), 
                            't2.position_level_id = t3.id', array('position_level'))
                    ->where(array('t1.organisation_id' =>$organisation_id))
			->where(array('t1.emp_resignation_id' => 0 ));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$staffList = array();
		foreach($resultSet as $set)
		{
			$staffList[$set['id']] = $set['first_name'].' '.$set['middle_name'].' '.$set['last_name'].' ('.$set['emp_id'].')';
		}
		return $staffList;
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
	
	/*
	* Get the Leave taken by an employee
	*/
	 
	public function getLeaveTaken($employee_details_id, $type)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		if($type== "Extra-Ordinary Leave (EOL)"){
			$leave_id = $this->getLeaveId($type);
			$select->from(array('t1' => 'emp_leave'))
					->columns(array('employee_details_id','days_of_leave','emp_leave_category_id'))
                                    ->where(array('employee_details_id' =>$employee_details_id))
                                    ->where(array('leave_status' => 'Approved'))
                                    ->where(array('emp_leave_category_id' =>$leave_id));
		} else if ($type == "Casual Leave"){
			$leave_id = $this->getLeaveId($type);
			$select->from(array('t1' => 'emp_leave_balance'))
					->columns(array('employee_details_id','casual_leave'))
                                    ->where(array('employee_details_id' =>$employee_details_id));
		}  else if ($type == "Earned Leave"){
			$leave_id = $this->getLeaveId($type);
			$select->from(array('t1' => 'emp_leave_balance'))
					->columns(array('employee_details_id','earned_leave'))
                                    ->where(array('employee_details_id' =>$employee_details_id));
		} else if($type == "Study Leave (Administrative & Technical)") { 
			$leave_id = $this->getLeaveId($type); 
			$select->from(array('t1' => 'emp_leave'))
					->columns(array('employee_details_id','days_of_leave','emp_leave_category_id'))
                                    ->where(array('employee_details_id' =>$employee_details_id))
                                    ->where(array('leave_status' => 'Approved'))
                                    ->where(array('emp_leave_category_id' =>$leave_id));
		} else if($type == "Study Leave (Academics)") { 
			$leave_id = $this->getLeaveId($type); 
			$select->from(array('t1' => 'emp_leave'))
					->columns(array('employee_details_id','days_of_leave','emp_leave_category_id'))
                                    ->where(array('employee_details_id' =>$employee_details_id))
                                    ->where(array('leave_status' => 'Approved'))
                                    ->where(array('emp_leave_category_id' =>$leave_id));
		} 
		/*else if($type == "Maternity Leave") {
			$leave_id = $this->getLeaveId($type);
			$select->from(array('t1' => 'emp_leave'))
					->columns(array('employee_details_id','days_of_leave','emp_leave_category_id'))
                                    ->where(array('employee_details_id' =>$employee_details_id))
                                    ->where(array('leave_status' => 'Approved'))
                                    ->where(array('emp_leave_category_id' =>$leave_id));
		} */
		else {
			$leave_id = $this->getLeaveId($type);
			$select->from(array('t1' => 'emp_leave'))
					->columns(array('employee_details_id','days_of_leave','emp_leave_category_id'))
                                    ->where(array('employee_details_id' =>$employee_details_id))
                                    ->where(array('emp_leave_category_id' =>$leave_id))
                                    ->where(array('leave_status' => 'Approved'))
                                    ->where(array('from_date >= ? ' => date('Y'.'-01-01')));
		}
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	 * Get the Earned Leave balance of an employee
	 */
	 
	public function getLeaveBalance($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_leave_balance'))
					->columns(array('earned_leave','casual_leave'))
                    ->where(array('employee_details_id' =>$employee_details_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
                
		$leave_balance=array();
		foreach($resultSet as $set){
			$leave_balance['earned_leave'] = $set['earned_leave'];
                        $leave_balance['casual_leave'] = $set['casual_leave'];
		}
		return $leave_balance;
	}


	public function getLeaveCategory($emp_leave_category_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_leave_category'))
					->columns(array('leave_category'))
                    ->where(array('t1.id' =>$emp_leave_category_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
                
		$leave_category = NULL;
		foreach($resultSet as $set){
			$leave_category = $set['leave_category'];
		}
		return $leave_category;
	}


	public function crossCheckAppliedLeave($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_leave'))
					->columns(array('from_date','to_date', 'leave_status'))
                    ->where(array('t1.employee_details_id' =>$employee_details_id));
        $select->where->notEqualTo('t1.leave_status','Rejected');
        $select->order(array('t1.id DESC'))
        	   ->limit(1);

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
                
		$applied_leave=array();
		foreach($resultSet as $set){
			$applied_leave['from_date'] = $set['from_date'];
            $applied_leave['to_date'] = $set['to_date'];
            $applied_leave['leave_status'] = $set['leave_status'];
		}
		return $applied_leave;
	}
	
	
	public function getStaffAppliedLeave($employee_details_id, $emp_leave_category_id, $status)
	{
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($status == 'Pending'){
			$select->from(array('t1' => 'emp_leave'))
					->columns(array('days_of_leave' => new \Zend\Db\Sql\Expression('SUM(days_of_leave)'), 'employee_details_id'))
                    ->where(array('t1.employee_details_id' => $employee_details_id, 't1.emp_leave_category_id' => $emp_leave_category_id, 't1.leave_status' => $status));

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
					
			$days_of_leave=NULL;
			foreach($resultSet as $set){
				$days_of_leave = $set['days_of_leave'];
			}//echo $days_of_leave; die();
			return $days_of_leave;
		}
	}



	/*
	 *Get the details of staff from the leave balance id
	 **/
	 public function getEmployeeDetails($id)
	 {
	 	$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

    	$select->from(array('t1' => 'emp_leave_balance'))
    	   ->join(array('t2' => 'employee_details'),
    			't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id'));
    	$select->where(array('t1.id' => $id));           

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->buffer();
        return $resultSet->initialize($result);
	 }

	 /*
	 *Get the details of leave balance from leave balance id
	 **/
	 public function getEmpLeaveBalanceDetails($id)
	 {
	 	$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

    	$select->from(array('t1' => 'emp_leave_balance'))
    		   ->join(array('t2' => 'employee_details'),
    				't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id'));
    	$select->where(array('t1.id' => $id));           

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->buffer();
        return $resultSet->initialize($result);
	 }
	
	/*
	* Get the leave id
	*/
	
	public function getLeaveId($type)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_leave_category'))
					->columns(array('id'))
                    ->where->like('leave_category','%'.$type.'%');

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set)
		{
			$leave_id = $set['id'];
		}
		return $leave_id;
	}
	
	/*
	* Get the Department and role of the Supervisor when assigning Officiating Supervisor
	*/
	
	public function getSupervisorDepartment($supervisor_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'))
					->columns(array('departments_id'))
                    ->where(array('id' =>$supervisor_id));

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

	public function getSupervisorRole($supervisor_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'))
        	   ->join(array('t2' => 'users'),
        			't2.username = t1.emp_id', array('username','role'))             
                    ->where(array('t1.id' =>$supervisor_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$departments_id = NULL;
		foreach($resultSet as $set)
		{
			$role = $set['role'];
		}
		return $role;
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
	
	/*
	 * Get Notification Details
	 */
	 
	public function getNotificationDetails($id, $role, $departments_id)
	{
		$notification_details = array();

		$auth_type = $this->getAppliedLeaveType($id);

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'user_workflow'))
					->columns(array('auth', 'department'))
                    ->where(array('t1.role' =>$role, 't1.role_department' => $departments_id, 't1.type' => $auth_type));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result); 
		
		foreach($resultSet as $set)
		{
			$notification_details['submission_to'] = $set['auth'];
			$notification_details['submission_to_dept'] = $set['department'];
		}
		
		return $notification_details;
	}


	public function getAppliedLeaveType($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_leave_category'))
					->columns(array('id', 'leave_category'))
                    ->where(array('t1.id' =>$id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$auth_type = NULL;
		foreach($resultSet as $set)
		{
			$auth_type = $set['leave_category'];
		}
		return $auth_type;
	}


	public function getEmployeeLeaveDetails($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        if($organisation_id != 1){
        	$select->from(array('t1' => 'emp_leave_balance'))
        	   ->join(array('t2' => 'employee_details'),
        			't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id', 'organisation_id'))             
        	   ->join(array('t3' => 'emp_position_title'),
        			't2.id = t3.employee_details_id', array('employee_details_id'))
        	   ->join(array('t4' => 'position_title'),
        			't4.id = t3.position_title_id', array('position_title'))
        	   ->join(array('t5'=>'employee_details'),
				't1.update_by_employee_id = t5.id',array('sub_first_name'=>'first_name', 'sub_middle_name'=>'middle_name', 'sub_last_name'=>'last_name'));
        	$select->where(array('t2.organisation_id' => $organisation_id, 't2.emp_resignation_id' =>'0', 't4.id != ?' =>'3'));
        	//t4.id = 3 or President
        }else{
        	$select->from(array('t1' => 'emp_leave_balance'))
        	   ->join(array('t2' => 'employee_details'),
        			't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id', 'organisation_id'))
        	   ->join(array('t3' => 'organisation'),
        			't3.id = t2.organisation_id', array('organisation_name'))
        	   ->join(array('t4' => 'emp_position_title'),
        			't2.id = t4.employee_details_id', array('employee_details_id'))
        	   ->join(array('t5' => 'position_title'),
        			't5.id = t4.position_title_id', array('position_title'))
        	   ->join(array('t6'=>'employee_details'),
				't1.update_by_employee_id = t6.id',array('sub_first_name'=>'first_name', 'sub_middle_name'=>'middle_name', 'sub_last_name'=>'last_name'));
        	$select->where(array('t2.emp_resignation_id' =>'0'));
        	$select->order(array('t2.organisation_id ASC'));
	}

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->buffer();
        return $resultSet->initialize($result);
	}
	
	/*
	 * Get the name of the file to download
	 */
	 
	public function getFileName($leave_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'emp_leave')) 
				->columns(array('evidence_file'))
				->where('t1.id = ' .$leave_id);
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}

	public function getOfficiatingFileName($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'user_workflow_officiating')) 
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


	public function listLeaveCategoryList($tableName, $columnName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array('leave_category',$columnName)); 

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['leave_category']] = $set[$columnName];
		}
		return $selectData;
	}
	
	/**
	* @return array/EmployeeLeave()
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

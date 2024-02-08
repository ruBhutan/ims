<?php

namespace Application\Mapper;

use Application\Model\Application;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements ApplicationMapperInterface
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
	 * @var \Application\Model\ApplicationInterface
	*/
	protected $applicationPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			Application $applicationPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->applicationPrototype = $applicationPrototype;
	}
	
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username, $tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'employee_details'){
			$select->from(array('t1' => $tableName))
				   ->columns(array('id', 'departments_id'))
				   ->join(array('t2' => 'emp_position_level'),
						't1.id = t2.employee_details_id', array('position_level_id'))
				   ->join(array('t3' => 'position_level'),
						't3.id = t2.position_level_id', array('position_level', 'major_occupational_group_id'));
			$select->where(array('t1.emp_id' => $username));
		}

		else if($tableName == 'student'){
			$select->from(array('t1' => $tableName));
			$select->where(array('t1.student_id' => $username));
			$select->columns(array('id'));
		}

		else if($tableName == 'parent_portal_access'){
			$select->from(array('t1' => $tableName));
			$select->where(array('t1.parent_cid' => $username));
			$select->columns(array('id'));
		}

		else if($tableName == 'job_applicant'){
			$select->from(array('t1' => $tableName));
			$select->where(array('t1.email' => $username));
			$select->columns(array('id'));
		}

		else if($tableName == 'alumni'){
			$select->from(array('t1' => $tableName));
			$select->where(array('t1.cid' => $username));
			$select->columns(array('id'));
		}
		
			
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

		else if($usertype == 3){
			$select->from(array('t1' => 'parent_portal_access'));
			$select->where(array('parent_cid' => $username))
				   ->join(array('t2' => 'student_relation_details'),
						't1.parent_cid = t2.parent_cid');
			$select->columns(array('first_name' => 'parent_name', 'middle_name' => NULL, 'last_name' => NULL));
		}	

		else if($usertype == 4){
			$select->from(array('t1' => 'job_applicant'));
			$select->where(array('email' => $username));
			$select->columns(array('first_name', 'middle_name', 'last_name'));
		}	

		else if($usertype == 5){
			$select->from(array('t1' => 'alumni'));
			$select->where(array('cid' => $username));
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

		if($usertype == 3){
			$select->from(array('t1' => 'parent_portal_access'));
			$select->where(array('t1.parent_cid' => $username));
			$select->columns(array('profile_picture'=>NULL));
		}	

		if($usertype == 4){
			$select->from(array('t1' => 'job_applicant'));
			$select->where(array('t1.email' => $username));
			$select->columns(array('profile_picture'=>NULL));
		}

		if($usertype == 5){
			$select->from(array('t1' => 'alumni'));
			$select->where(array('t1.cid' => $username));
			$select->columns(array('profile_picture'=>NULL)); 

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
	* Get organisation id based on the username
	*/
	
	public function getOrganisationId($username, $usertype)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($usertype == 1){
			$select->from(array('t1' => 'employee_details'));
			$select->where(array('t1.emp_id' => $username));
			$select->columns(array('organisation_id'));
		}

		if($usertype == 2){
			$select->from(array('t1' => 'student'));
			$select->where(array('t1.student_id' => $username));
			$select->columns(array('organisation_id'));
		}

		if($usertype == 4){
			$select->from(array('t1' => 'users'));
			$select->where(array('t1.username' => $username));
			$select->columns(array('region'));
		}

		if($usertype == 5){
			$select->from(array('t1' => 'alumni'));
			$select->where(array('t1.cid' => $username));
			$select->columns(array('organisation_id'));
		}
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get the leave notifications
	*/
	
	public function getNotifications($notification_type, $userrole, $employee_details_id, $user_department, $organisation_id)
	{ 
		$check_assigned_officiating = $this->checkOwnAssignedOfficiating($userrole); 

		if($check_assigned_officiating){ 
			return;
		}else{
			
			$check_authorized_role = $this->checkAuthorizedRole($userrole, $organisation_id, $notification_type); 		
			$sql = new Sql($this->dbAdapter);
			if(!empty($check_authorized_role)){ 
				$select = $sql->select();
						
				//first get the department, organisation and authtype for the user role
				$select->from(array('t1' => 'user_workflow'))
							->columns(array('role_department','department','type', 'role'));
				$select->where(array('t1.auth = ? ' => $userrole));
				//$select->where(array('t1.department = ? ' => $departments_id));
				$select->where(array('t1.organisation = ? ' => $organisation_id));
				$select->where->like('t1.type', '%'.$notification_type.'%');
				
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
				if($officiating_role){
					$select3 = $sql->select();
					$select3->from(array('t1' => 'user_workflow'))
							->columns(array('role_department','department','type', 'role'));
					$select3->where(array('t1.auth = ? ' => $officiating_role));
					$select3->where(array('t1.organisation = ? ' => $organisation_id));
					$select3->where->like('t1.type', '%'.$notification_type.'%');
					
					$stmt3 = $sql->prepareStatementForSqlObject($select3);
					$result3 = $stmt3->execute();
					
					$resultSet3 = new ResultSet();
					$resultSet3->initialize($result3);
					
					foreach($resultSet3 as $tmp_data3){
						$type_authorisation[$tmp_data3['type']][$tmp_data3['role_department']][$tmp_data3['role']] = $tmp_data3['role'];
					} 
				} 
			
				//$authorizee_emp_ids = $this->getEmployeeIdByRoles($authorizee_role);

				$leave_data = $this->listSelectData1('emp_leave_category', 'leave_category'); 
				//var_dump($leave_data); die();
			                
	         	$employee_leaves = array();      
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
									$select2->where(array('t1.leave_status = ? ' => 'Pending'));
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


	public function getTourNotifications($notification_type, $userrole, $employee_details_id, $user_department, $organisation_id)
	{
		//$auth_type = 'Goods Requisition';

			//Get whether the particular user have assigned his/ her officiating
			$check_assigned_officiating = $this->checkOwnAssignedOfficiating($userrole);

			if($check_assigned_officiating){
				return;
			}
			else{
				$check_authorized_role = $this->checkAuthorizedRole($userrole, $organisation_id, $notification_type);
				$sql = new Sql($this->dbAdapter);
				if(!empty($check_authorized_role)){
					$select = $sql->select();

					//echo "user role:".$userrole;
							
					//first get the department, organisation and authtype for the user role
					$select->from(array('t1' => 'user_workflow'))
								->columns(array('role_department','department','type', 'role'));
					$select->where(array('t1.auth = ? ' => $userrole));
					//$select->where(array('t1.department = ? ' => $departments_id));
					$select->where(array('t1.organisation = ? ' => $organisation_id));
					$select->where->like('t1.type', '%'.$notification_type.'%');
					
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
					if($officiating_role){
						$select3 = $sql->select();
						$select3->from(array('t1' => 'user_workflow'))
								->columns(array('role_department','department','type', 'role'));
						$select3->where(array('t1.auth = ? ' => $officiating_role));
						$select3->where(array('t1.organisation = ? ' => $organisation_id));
						$select3->where->like('t1.type', '%'.$notification_type.'%');
						
						$stmt3 = $sql->prepareStatementForSqlObject($select3);
						$result3 = $stmt3->execute();
						
						$resultSet3 = new ResultSet();
						$resultSet3->initialize($result3);
						
						foreach($resultSet3 as $tmp_data3){
							$type_authorisation[$tmp_data3['type']][$tmp_data3['role_department']][$tmp_data3['role']] = $tmp_data3['role'];
						} 
					}
					
					$employee_tour = array();

					if(!empty($type_authorisation)){
						foreach ($type_authorisation as $type => $value) {
							$applied_type = $type;
							foreach ($value as $role_department => $value2) {
								foreach($value2 as $role){
									$authorizee_emp_ids = $this->getEmployeeIdByRoles($role, $role_department);
									
									foreach($authorizee_emp_ids as $value3){
										//get the list of employees
									$select2 = $sql->select();
									$select2->from(array('t1' => 'travel_authorization'))
												->join(array('t2' => 'employee_details'), 
														't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'));
									$select2->where(array('t1.tour_status  = ? ' => 'Submitted'));
									$select2->where(array('t1.travel_auth_date >= ? ' => date('Y'.'-01-01')));
									$select2->where(array('t2.departments_units_id ' => $role_department));
									$select2->where(array('t1.employee_details_id != ?' => $employee_details_id));
									if($authorizee_emp_ids){
										$select2->where(array('t1.employee_details_id' => $value3));
									}
									
									$stmt2 = $sql->prepareStatementForSqlObject($select2);
									$result2 = $stmt2->execute();
									
									$resultSet2 = new ResultSet();
									$resultSet2->initialize($result2);
									foreach($resultSet2 as $set){
										$employee_tour[] = $set;
										} 
									}
								}
							}
						}

					}

					// This will display the tour approval of officiating supervisor to the supervisor
				/*	$sql = new Sql($this->dbAdapter);
					$select1 = $sql->select();
							
					//first get the department, organisation and authtype for the user role
					$select1->from(array('t1' => 'user_workflow'))
								->columns(array('role_department','department','type', 'role'));
					$select1->where(array('t1.auth = ? ' => $userrole));
					//$select->where(array('t1.department = ? ' => $departments_id));
					$select1->where(array('t1.organisation = ? ' => $organisation_id));
					$select1->where->like('t1.type', '%'.$notification_type.'%');
					
					$stmt1 = $sql->prepareStatementForSqlObject($select1);
					$result1 = $stmt1->execute();
					
					$resultSet1 = new ResultSet();
					$resultSet1->initialize($result1); 
					
					foreach($resultSet1 as $tmp_data1){
						$type_authorisation1[$tmp_data1['type']][$tmp_data1['role_department']][$tmp_data1['role']] = $tmp_data1['role'];
					} 

					if(!empty($type_authorisation1)){
						foreach ($type_authorisation1 as $type1 => $value1) {
							$applied_type1 = $type1;
							foreach ($value1 as $role_department1 => $value4) {
								foreach ($value4 as $role1) { 
									$departments_id = $this->getSupervisorDepartmentId($role_department1);
									$officiating_supervisor_ids = $this->getEmployeeIdBySupervisorRole($role1, $departments_id);

									foreach ($officiating_supervisor_ids as $value5) { 
										//get the list of employees
										$select4 = $sql->select();
										$select4->from(array('t1' => 'travel_authorization'))
													->join(array('t2' => 'employee_details'), 
															't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'));
										$select4->where(array('t1.tour_status  = ? ' => 'Submitted'));
										$select4->where(array('t1.travel_auth_date >= ? ' => date('Y'.'-01-01')));
										//$select4->where(array('t2.departments_units_id ' => $role_department));

										if($officiating_supervisor_ids){
											$select4->where(array('t1.employee_details_id' => $value5));
										}
										
										$stmt4 = $sql->prepareStatementForSqlObject($select4);
										$result4 = $stmt4->execute();
										
										$resultSet4 = new ResultSet();
										$resultSet4->initialize($result4);
										foreach($resultSet4 as $set1){
											$employee_tour[] = $set1;
										} 
									}
								}
							}
						}
					}
					// var_dump($employee_tour);
*/
					return $employee_tour;
				}
			//}
	}



	// Function to get goods requisition notification
	public function getGoodsRequisitiontNotifications($notification_type, $userrole, $employee_details_id, $user_department, $organisation_id)
	{ 
		//$auth_type = 'Goods Requisition';

			//Get whether the particular user have assigned his/ her officiating
			$check_assigned_officiating = $this->checkOwnAssignedOfficiating($userrole);

			if($check_assigned_officiating){
				return;
			}
			else{
				$check_authorized_role = $this->checkAuthorizedRole($userrole, $organisation_id, $notification_type);
				$sql = new Sql($this->dbAdapter);
				if(!empty($check_authorized_role)){
					$select = $sql->select();
							
					//first get the department, organisation and authtype for the user role
					$select->from(array('t1' => 'user_workflow'))
								->columns(array('role_department','department','type', 'role'));
					$select->where(array('t1.auth = ? ' => $userrole));
					//$select->where(array('t1.department = ? ' => $departments_id));
					$select->where(array('t1.organisation = ? ' => $organisation_id));
					$select->where->like('t1.type', '%'.$notification_type.'%');
					
					$stmt = $sql->prepareStatementForSqlObject($select);
					$result = $stmt->execute();
					
					$resultSet = new ResultSet();
					$resultSet->initialize($result); 
					
					foreach($resultSet as $tmp_data){
						$type_authorisation[$tmp_data['type']][$tmp_data['role_department']][$tmp_data['role']] = $tmp_data['role'];
					} 

					//get details of departments if username is officiating
					$officiating_role = $this->getOfficiatingRole($employee_details_id);

					//Need to redo previous sql statements for officiating role
					if($officiating_role){
						$select3 = $sql->select();
						$select3->from(array('t1' => 'user_workflow'))
								->columns(array('role_department','department','type', 'role'));
						$select3->where(array('t1.auth = ? ' => $officiating_role));
						$select3->where(array('t1.organisation = ? ' => $organisation_id));
						$select->where->like('t1.type', '%'.$notification_type.'%');
						
						$stmt3 = $sql->prepareStatementForSqlObject($select3);
						$result3 = $stmt3->execute();
						
						$resultSet3 = new ResultSet();
						$resultSet3->initialize($result3);
						
						foreach($resultSet3 as $tmp_data3){
							$type_authorisation[$tmp_data3['type']][$tmp_data3['role_department']][$tmp_data3['role']] = $tmp_data3['role'];
						} 
					}
					
					$employee_requisition = array();

					if(!empty($type_authorisation)){
						foreach ($type_authorisation as $type => $value) {
							$applied_type = $type;
							foreach ($value as $role_department => $value2) {
								foreach($value2 as $role){
									$authorizee_emp_ids = $this->getEmployeeIdByRoles($role, $role_department);
									
									foreach($authorizee_emp_ids as $value3){
										//get the list of employees
									$select2 = $sql->select();
									$select2->from(array('t1' => 'goods_requisition_details'))
												->join(array('t2' => 'employee_details'), 
														't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'));
									$select2->where(array('t1.requisition_status  = ? ' => 'Pending'));
									$select2->where(array('t1.requisition_date >= ? ' => date('Y'.'-01-01')));
									$select2->where(array('t2.departments_units_id ' => $role_department));
									$select2->where(array('t1.employee_details_id != ?' => $employee_details_id));
									if($authorizee_emp_ids){
										$select2->where(array('t1.employee_details_id' => $value3));
									}
									
									$stmt2 = $sql->prepareStatementForSqlObject($select2);
									$result2 = $stmt2->execute();
									
									$resultSet2 = new ResultSet();
									$resultSet2->initialize($result2);
									foreach($resultSet2 as $set){
										$employee_requisition[] = $set;
										} 
									}
								}
							}
						}

					}

					// This will display the tour approval of officiating supervisor to the supervisor
					$sql = new Sql($this->dbAdapter);
					$select1 = $sql->select();
							
					//first get the department, organisation and authtype for the user role
					$select1->from(array('t1' => 'user_workflow'))
								->columns(array('role_department','department','type', 'role'));
					$select1->where(array('t1.auth = ? ' => $userrole));
					//$select->where(array('t1.department = ? ' => $departments_id));
					$select1->where(array('t1.organisation = ? ' => $organisation_id));
					$select1->where->like('t1.type', '%'.$notification_type.'%');
					
					$stmt1 = $sql->prepareStatementForSqlObject($select1);
					$result1 = $stmt1->execute();
					
					$resultSet1 = new ResultSet();
					$resultSet1->initialize($result1); 
					
					foreach($resultSet1 as $tmp_data1){
						$type_authorisation1[$tmp_data1['type']][$tmp_data1['role_department']][$tmp_data1['role']] = $tmp_data1['role'];
					} 

					if(!empty($type_authorisation1)){
						foreach ($type_authorisation1 as $type1 => $value1) {
							$applied_type1 = $type1;
							foreach ($value1 as $role_department1 => $value4) {
								foreach ($value4 as $role1) { 
									$departments_id = $this->getSupervisorDepartmentId($role_department1);
									$officiating_supervisor_ids = $this->getEmployeeIdBySupervisorRole($role1, $departments_id);

									foreach ($officiating_supervisor_ids as $value5) { 
										//get the list of employees
										$select4 = $sql->select();
										$select4->from(array('t1' => 'goods_requisition_details'))
													->join(array('t2' => 'employee_details'), 
															't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'));
										$select4->where(array('t1.requisition_status  = ? ' => 'Pending'));
										$select4->where(array('t1.requisition_date >= ? ' => date('Y'.'-01-01')));
										//$select4->where(array('t2.departments_units_id ' => $role_department));

										if($officiating_supervisor_ids){
											$select4->where(array('t1.employee_details_id' => $value5));
										}
										
										$stmt4 = $sql->prepareStatementForSqlObject($select4);
										$result4 = $stmt4->execute();
										
										$resultSet4 = new ResultSet();
										$resultSet4->initialize($result4);
										foreach($resultSet4 as $set1){
											$employee_requisition[] = $set1;
										} 
									}
								}
							}
						}
					}

					return $employee_requisition;
				}
			}
	}



	// Function to get staff transfer application notification
	public function getStaffTransferNotification($notification_type, $userrole, $employee_details_id, $user_department, $organisation_id)
	{
		//Get whether the particular user have assigned his/ her officiating
			$check_assigned_officiating = $this->checkOwnAssignedOfficiating($userrole);

			if($check_assigned_officiating){
				return;
			}
			else{
				$check_authorized_role = $this->checkAuthorizedRole($userrole, $organisation_id, $notification_type);
				$sql = new Sql($this->dbAdapter);
				if(!empty($check_authorized_role)){
					$select = $sql->select();
							
					//first get the department, organisation and authtype for the user role
					$select->from(array('t1' => 'user_workflow'))
								->columns(array('role_department','department','type', 'role'));
					$select->where(array('t1.auth = ? ' => $userrole));
					//$select->where(array('t1.department = ? ' => $departments_id));
					$select->where(array('t1.organisation = ? ' => $organisation_id));
					$select->where->like('t1.type', '%'.$notification_type.'%');
					
					$stmt = $sql->prepareStatementForSqlObject($select);
					$result = $stmt->execute();
					
					$resultSet = new ResultSet();
					$resultSet->initialize($result); 
					
					foreach($resultSet as $tmp_data){
						$type_authorisation[$tmp_data['type']][$tmp_data['role_department']][$tmp_data['role']] = $tmp_data['role'];
					} 

					//get details of departments if username is officiating
					$officiating_role = $this->getOfficiatingRole($employee_details_id);

					//Need to redo previous sql statements for officiating role
					if($officiating_role){
						$select3 = $sql->select();
						$select3->from(array('t1' => 'user_workflow'))
								->columns(array('role_department','department','type', 'role'));
						$select3->where(array('t1.auth = ? ' => $officiating_role));
						$select3->where(array('t1.organisation = ? ' => $organisation_id));
						$select->where->like('t1.type', '%'.$notification_type.'%');
						
						$stmt3 = $sql->prepareStatementForSqlObject($select3);
						$result3 = $stmt3->execute();
						
						$resultSet3 = new ResultSet();
						$resultSet3->initialize($result3);
						
						foreach($resultSet3 as $tmp_data3){
							$type_authorisation[$tmp_data3['type']][$tmp_data3['role_department']][$tmp_data3['role']] = $tmp_data3['role'];
						} 
					}
					
					$employee_transfer = array();

					if(!empty($type_authorisation)){
						foreach ($type_authorisation as $type => $value) {
							$applied_type = $type;
							foreach ($value as $role_department => $value2) {
								foreach($value2 as $role){
									$authorizee_emp_ids = $this->getEmployeeIdByRoles($role, $role_department);
									
									foreach($authorizee_emp_ids as $value3){
										//get the list of employees
									$select2 = $sql->select();
									$select2->from(array('t1' => 'emp_transfer_application'))
												->join(array('t2' => 'employee_details'), 
														't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'));
									$select2->where(array('t1.from_org_transfer_status  = ? ' => 'Pending'));
									$select2->where(array('t1.date_of_request >= ? ' => date('Y'.'-01-01')));
									$select2->where(array('t2.departments_units_id ' => $role_department));
									$select2->where(array('t1.employee_details_id != ?' => $employee_details_id));
									if($authorizee_emp_ids){
										$select2->where(array('t1.employee_details_id' => $value3));
									}
									
									$stmt2 = $sql->prepareStatementForSqlObject($select2);
									$result2 = $stmt2->execute();
									
									$resultSet2 = new ResultSet();
									$resultSet2->initialize($result2);
									foreach($resultSet2 as $set){
										$employee_transfer[] = $set;
										} 
									}
								}
							}
						}

					}

					// This will display the tour approval of officiating supervisor to the supervisor
					$sql = new Sql($this->dbAdapter);
					$select1 = $sql->select();
							
					//first get the department, organisation and authtype for the user role
					$select1->from(array('t1' => 'user_workflow'))
								->columns(array('role_department','department','type', 'role'));
					$select1->where(array('t1.auth = ? ' => $userrole));
					//$select->where(array('t1.department = ? ' => $departments_id));
					$select1->where(array('t1.organisation = ? ' => $organisation_id));
					$select1->where->like('t1.type', '%'.$notification_type.'%');
					
					$stmt1 = $sql->prepareStatementForSqlObject($select1);
					$result1 = $stmt1->execute();
					
					$resultSet1 = new ResultSet();
					$resultSet1->initialize($result1); 
					
					foreach($resultSet1 as $tmp_data1){
						$type_authorisation1[$tmp_data1['type']][$tmp_data1['role_department']][$tmp_data1['role']] = $tmp_data1['role'];
					} 

					if(!empty($type_authorisation1)){
						foreach ($type_authorisation1 as $type1 => $value1) {
							$applied_type1 = $type1;
							foreach ($value1 as $role_department1 => $value4) {
								foreach ($value4 as $role1) { 
									$departments_id = $this->getSupervisorDepartmentId($role_department1);
									$officiating_supervisor_ids = $this->getEmployeeIdBySupervisorRole($role1, $departments_id);

									foreach ($officiating_supervisor_ids as $value5) { 
										//get the list of employees
										$select4 = $sql->select();
										$select4->from(array('t1' => 'emp_transfer_application'))
													->join(array('t2' => 'employee_details'), 
															't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'));
										$select4->where(array('t1.from_org_transfer_status  = ? ' => 'Pending'));
										$select4->where(array('t1.date_of_request >= ? ' => date('Y'.'-01-01')));
										//$select4->where(array('t2.departments_units_id ' => $role_department));

										if($officiating_supervisor_ids){
											$select4->where(array('t1.employee_details_id' => $value5));
										}
										
										$stmt4 = $sql->prepareStatementForSqlObject($select4);
										$result4 = $stmt4->execute();
										
										$resultSet4 = new ResultSet();
										$resultSet4->initialize($result4);
										foreach($resultSet4 as $set1){
											$employee_transfer[] = $set1;
										} 
									}
								}
							}
						}
					}

					return $employee_transfer;
				}
			}
	}




	public function getStaffPromotionNotification($notification_type, $userrole, $employee_details_id, $user_department, $organisation_id)
	{
		//Get whether the particular user have assigned his/ her officiating
			$check_assigned_officiating = $this->checkOwnAssignedOfficiating($userrole);

			if($check_assigned_officiating){
				return;
			}
			else{
				$check_authorized_role = $this->checkAuthorizedRole($userrole, $organisation_id, $notification_type);
				$sql = new Sql($this->dbAdapter);
				if(!empty($check_authorized_role)){
					$select = $sql->select();
							
					//first get the department, organisation and authtype for the user role
					$select->from(array('t1' => 'user_workflow'))
								->columns(array('role_department','department','type', 'role'));
					$select->where(array('t1.auth = ? ' => $userrole));
					//$select->where(array('t1.department = ? ' => $departments_id));
					$select->where(array('t1.organisation = ? ' => $organisation_id));
					$select->where->like('t1.type', '%'.$notification_type.'%');
					
					$stmt = $sql->prepareStatementForSqlObject($select);
					$result = $stmt->execute();
					
					$resultSet = new ResultSet();
					$resultSet->initialize($result); 
					
					foreach($resultSet as $tmp_data){
						$type_authorisation[$tmp_data['type']][$tmp_data['role_department']][$tmp_data['role']] = $tmp_data['role'];
					} 

					//get details of departments if username is officiating
					$officiating_role = $this->getOfficiatingRole($employee_details_id);

					//Need to redo previous sql statements for officiating role
					if($officiating_role){
						$select3 = $sql->select();
						$select3->from(array('t1' => 'user_workflow'))
								->columns(array('role_department','department','type', 'role'));
						$select3->where(array('t1.auth = ? ' => $officiating_role));
						$select3->where(array('t1.organisation = ? ' => $organisation_id));
						$select->where->like('t1.type', '%'.$notification_type.'%');
						
						$stmt3 = $sql->prepareStatementForSqlObject($select3);
						$result3 = $stmt3->execute();
						
						$resultSet3 = new ResultSet();
						$resultSet3->initialize($result3);
						
						foreach($resultSet3 as $tmp_data3){
							$type_authorisation[$tmp_data3['type']][$tmp_data3['role_department']][$tmp_data3['role']] = $tmp_data3['role'];
						} 
					}
					
					$employee_promotion = array();

					if(!empty($type_authorisation)){
						foreach ($type_authorisation as $type => $value) {
							$applied_type = $type;
							foreach ($value as $role_department => $value2) {
								foreach($value2 as $role){
									$authorizee_emp_ids = $this->getEmployeeIdByRoles($role, $role_department);
									
									foreach($authorizee_emp_ids as $value3){
										//get the list of employees
									$select2 = $sql->select();
									$select2->from(array('t1' => 'emp_promotion'))
												->join(array('t2' => 'employee_details'), 
														't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'));
									$select2->where(array('t1.promotion_status  = ? ' => 'Pending'));
									//$select2->where(array('t1.date_of_request >= ? ' => date('Y'.'-01-01')));
									$select2->where(array('t2.departments_units_id ' => $role_department));
									$select2->where(array('t1.employee_details_id != ?' => $employee_details_id));
									if($authorizee_emp_ids){
										$select2->where(array('t1.employee_details_id' => $value3));
									}
									
									$stmt2 = $sql->prepareStatementForSqlObject($select2);
									$result2 = $stmt2->execute();
									
									$resultSet2 = new ResultSet();
									$resultSet2->initialize($result2);
									foreach($resultSet2 as $set){
										$employee_promotion[] = $set;
										} 
									}
								}
							}
						}

					}

					// This will display the tour approval of officiating supervisor to the supervisor
					$sql = new Sql($this->dbAdapter);
					$select1 = $sql->select();
							
					//first get the department, organisation and authtype for the user role
					$select1->from(array('t1' => 'user_workflow'))
								->columns(array('role_department','department','type', 'role'));
					$select1->where(array('t1.auth = ? ' => $userrole));
					//$select->where(array('t1.department = ? ' => $departments_id));
					$select1->where(array('t1.organisation = ? ' => $organisation_id));
					$select1->where->like('t1.type', '%'.$notification_type.'%');
					
					$stmt1 = $sql->prepareStatementForSqlObject($select1);
					$result1 = $stmt1->execute();
					
					$resultSet1 = new ResultSet();
					$resultSet1->initialize($result1); 
					
					foreach($resultSet1 as $tmp_data1){
						$type_authorisation1[$tmp_data1['type']][$tmp_data1['role_department']][$tmp_data1['role']] = $tmp_data1['role'];
					} 

					if(!empty($type_authorisation1)){
						foreach ($type_authorisation1 as $type1 => $value1) {
							$applied_type1 = $type1;
							foreach ($value1 as $role_department1 => $value4) {
								foreach ($value4 as $role1) { 
									$departments_id = $this->getSupervisorDepartmentId($role_department1);
									$officiating_supervisor_ids = $this->getEmployeeIdBySupervisorRole($role1, $departments_id);

									foreach ($officiating_supervisor_ids as $value5) { 
										//get the list of employees
										$select4 = $sql->select();
										$select4->from(array('t1' => 'emp_promotion'))
													->join(array('t2' => 'employee_details'), 
															't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'));
										$select4->where(array('t1.promotion_status  = ? ' => 'Pending'));
										//$select4->where(array('t1.date_of_request >= ? ' => date('Y'.'-01-01')));
										//$select4->where(array('t2.departments_units_id ' => $role_department));

										if($officiating_supervisor_ids){
											$select4->where(array('t1.employee_details_id' => $value5));
										}
										
										$stmt4 = $sql->prepareStatementForSqlObject($select4);
										$result4 = $stmt4->execute();
										
										$resultSet4 = new ResultSet();
										$resultSet4->initialize($result4);
										foreach($resultSet4 as $set1){
											$employee_promotion[] = $set1;
										} 
									}
								}
							}
						}
					}

					return $employee_promotion;
				}
			}
	}



	public function getStaffResignationNotification($notification_type, $userrole, $employee_details_id, $user_department, $organisation_id)
	{
		//Get whether the particular user have assigned his/ her officiating
			$check_assigned_officiating = $this->checkOwnAssignedOfficiating($userrole);

			if($check_assigned_officiating){
				return;
			}
			else{
				$check_authorized_role = $this->checkAuthorizedRole($userrole, $organisation_id, $notification_type);
				$sql = new Sql($this->dbAdapter);
				if(!empty($check_authorized_role)){
					$select = $sql->select();
							
					//first get the department, organisation and authtype for the user role
					$select->from(array('t1' => 'user_workflow'))
								->columns(array('role_department','department','type', 'role'));
					$select->where(array('t1.auth = ? ' => $userrole));
					//$select->where(array('t1.department = ? ' => $departments_id));
					$select->where(array('t1.organisation = ? ' => $organisation_id));
					$select->where->like('t1.type', '%'.$notification_type.'%');
					
					$stmt = $sql->prepareStatementForSqlObject($select);
					$result = $stmt->execute();
					
					$resultSet = new ResultSet();
					$resultSet->initialize($result); 
					
					foreach($resultSet as $tmp_data){
						$type_authorisation[$tmp_data['type']][$tmp_data['role_department']][$tmp_data['role']] = $tmp_data['role'];
					} 

					//get details of departments if username is officiating
					$officiating_role = $this->getOfficiatingRole($employee_details_id);

					//Need to redo previous sql statements for officiating role
					if($officiating_role){
						$select3 = $sql->select();
						$select3->from(array('t1' => 'user_workflow'))
								->columns(array('role_department','department','type', 'role'));
						$select3->where(array('t1.auth = ? ' => $officiating_role));
						$select3->where(array('t1.organisation = ? ' => $organisation_id));
						$select->where->like('t1.type', '%'.$notification_type.'%');
						
						$stmt3 = $sql->prepareStatementForSqlObject($select3);
						$result3 = $stmt3->execute();
						
						$resultSet3 = new ResultSet();
						$resultSet3->initialize($result3);
						
						foreach($resultSet3 as $tmp_data3){
							$type_authorisation[$tmp_data3['type']][$tmp_data3['role_department']][$tmp_data3['role']] = $tmp_data3['role'];
						} 
					}
					
					$employee_resignation = array();

					if(!empty($type_authorisation)){
						foreach ($type_authorisation as $type => $value) {
							$applied_type = $type;
							foreach ($value as $role_department => $value2) {
								foreach($value2 as $role){
									$authorizee_emp_ids = $this->getEmployeeIdByRoles($role, $role_department);
									
									foreach($authorizee_emp_ids as $value3){
										//get the list of employees
									$select2 = $sql->select();
									$select2->from(array('t1' => 'emp_resignation'))
												->join(array('t2' => 'employee_details'), 
														't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'));
									$select2->where(array('t1.resignation_status is NULL'));
									$select2->where(array('t1.date_of_application >= ? ' => date('Y'.'-01-01')));
									$select2->where(array('t2.departments_units_id ' => $role_department));
									$select2->where(array('t1.employee_details_id != ?' => $employee_details_id));
									if($authorizee_emp_ids){
										$select2->where(array('t1.employee_details_id' => $value3));
									}
									
									$stmt2 = $sql->prepareStatementForSqlObject($select2);
									$result2 = $stmt2->execute();
									
									$resultSet2 = new ResultSet();
									$resultSet2->initialize($result2);
									foreach($resultSet2 as $set){
										$employee_resignation[] = $set;
										} 
									}
								}
							}
						}

					}

					// This will display the tour approval of officiating supervisor to the supervisor
					$sql = new Sql($this->dbAdapter);
					$select1 = $sql->select();
							
					//first get the department, organisation and authtype for the user role
					$select1->from(array('t1' => 'user_workflow'))
								->columns(array('role_department','department','type', 'role'));
					$select1->where(array('t1.auth = ? ' => $userrole));
					//$select->where(array('t1.department = ? ' => $departments_id));
					$select1->where(array('t1.organisation = ? ' => $organisation_id));
					$select1->where->like('t1.type', '%'.$notification_type.'%');
					
					$stmt1 = $sql->prepareStatementForSqlObject($select1);
					$result1 = $stmt1->execute();
					
					$resultSet1 = new ResultSet();
					$resultSet1->initialize($result1); 
					
					foreach($resultSet1 as $tmp_data1){
						$type_authorisation1[$tmp_data1['type']][$tmp_data1['role_department']][$tmp_data1['role']] = $tmp_data1['role'];
					} 

					if(!empty($type_authorisation1)){
						foreach ($type_authorisation1 as $type1 => $value1) {
							$applied_type1 = $type1;
							foreach ($value1 as $role_department1 => $value4) {
								foreach ($value4 as $role1) { 
									$departments_id = $this->getSupervisorDepartmentId($role_department1);
									$officiating_supervisor_ids = $this->getEmployeeIdBySupervisorRole($role1, $departments_id);

									foreach ($officiating_supervisor_ids as $value5) { 
										//get the list of employees
										$select4 = $sql->select();
										$select4->from(array('t1' => 'emp_resignation'))
													->join(array('t2' => 'employee_details'), 
															't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'));
										$select4->where(array('t1.resignation_status is NULL'));
										$select4->where(array('t1.date_of_application >= ? ' => date('Y'.'-01-01')));
										//$select4->where(array('t2.departments_units_id ' => $role_department));

										if($officiating_supervisor_ids){
											$select4->where(array('t1.employee_details_id' => $value5));
										}
										
										$stmt4 = $sql->prepareStatementForSqlObject($select4);
										$result4 = $stmt4->execute();
										
										$resultSet4 = new ResultSet();
										$resultSet4->initialize($result4);
										foreach($resultSet4 as $set1){
											$employee_resignation[] = $set1;
										} 
									}
								}
							}
						}
					}

					return $employee_resignation;
				}
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

	private function checkAuthorizedRole($userrole, $organisation_id, $type)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
				
		//first get the department, organisation and authtype for the user role
		$select->from(array('t1' => 'user_workflow'));
		$select->where(array('t1.auth' => $userrole));
		$select->where(array('t1.organisation' => $organisation_id));
		$select->where->like('type','%'.$type.'%');
		
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
	* Get the important upcoming dates
	*
	* The events will also depending on the userrole
	*/
	
	public function getUpcomingDates($userrole)
	{
		$upcoming_date = array();
		
		$hrd_dates = $this->getHRProposalDate('HRD Proposal');
		$hrm_dates = $this->getHRProposalDate('HRM Proposal');
		$research_dates = $this->getResearchDate();
		
		$upcoming_date[] = $hrd_dates;
		$upcoming_date[] = $hrm_dates;
		//$upcoming_date[] = $research_dates;
		
		return $upcoming_date;
	}


	/*
	*Get staff details based on employee details id
	**/
	public function getStaffDetails($tableName, $personal_details_id, $type)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'employee_details' && $type == NULL){
			$select->from(array('t1' => $tableName))
			   ->join(array('t2' => 'departments'),
					't2.id = t1.departments_id', array('department_name'))
			   ->join(array('t3' => 'department_units'),
					't3.id = t1.departments_units_id', array('unit_name'))
		       ->where(array('t1.id = ?' => $personal_details_id));
		}
		elseif ($tableName == 'job_applicant' && $type == 'permanent_address') {
			$select->from(array('t1' => $tableName))
			   ->join(array('t2' => 'dzongkhag'),
					't2.id = t1.dzongkhag', array('dzongkhag_name'))
			   ->join(array('t3' => 'gewog'),
					't3.id = t1.gewog', array('gewog_name'))
			   ->join(array('t4' => 'village'),
					't4.id = t1.village', array('village_name'))
			   ->join(array('t5' => 'nationality'),
					't5.id = t1.nationality', array('nationality'))
			   ->join(array('t6' => 'country'),
					't6.id = t1.country', array('country'))
		       ->where(array('t1.id = ?' => $personal_details_id));
		}
		elseif ($tableName == 'job_applicant' && $type == NULL) {
			$select->from(array('t1' => $tableName))
			       ->join(array('t2' => 'users'),
			   			't2.username = t1.email', array('role'))
		       		->where(array('t1.id = ?' => $personal_details_id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}

	/*
	*Get present position title
	**/
	public function getPresentPositionTitle($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_position_title'))
			   ->join(array('t2' => 'position_title'),
					't2.id = t1.position_title_id', array('position_title'))
		       ->where(array('t1.employee_details_id = ?' => $employee_details_id))
		       ->order(array('t1.date DESC'))
		       ->limit(1);

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        $position_title = NULL;
        foreach($resultSet as $detail){
			 $position_title = $detail['position_title'];
		}
		return $position_title;
	}

	/*
	*Get present position level
	**/
	public function getPresentPositionLevel($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_position_level'))
			   ->join(array('t2' => 'position_level'),
					't2.id = t1.position_level_id', array('position_level'))
		       ->where(array('t1.employee_details_id = ?' => $employee_details_id))
		       ->order(array('t1.date DESC'))
		       ->limit(1);

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        $position_level = NULL;
        foreach($resultSet as $detail){
			 $position_level = $detail['position_level'];
		}
		return $position_level;
	}


	public function getNumberOfStudents()
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student'))
			   ->columns(array('id', 'organisation_id', 'gender'))
			   ->join(array('t2' => 'organisation'),
					't2.id = t1.organisation_id', array('abbr'));
		//$select->where->notEqualTo('t1.student_status_type_id','4');
        	//$select->where->notEqualTo('t1.student_status_type_id','5');
        	//$select->where->notEqualTo('t1.student_status_type_id','6');
        	//$select->where->notEqualTo('t1.student_status_type_id','7');
		$select->where(array('t1.student_status_type_id NOT IN (4,5,6,7,8)'));

	    	$select->order('t2.id ASC');

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$temp_student_no = array();
		$student_no = array();

		//replace both the foreach with "count"
		foreach($resultSet as $set){
			$temp_student_no[$set['abbr']][$set['gender']][$set['id']] = $set['id'];
		}

		foreach($temp_student_no as $key => $value){ 
			foreach($value as $key2 => $value2){
				$student_no[$key][$key2] = count($temp_student_no[$key][$key2]);
			}
		}//var_dump($student_no); die();
		return $student_no;
	}


	public function getNumberOfStaff()
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'))
			   ->columns(array('id', 'organisation_id', 'gender'))
			   ->join(array('t2' => 'organisation'),
					't2.id = t1.organisation_id', array('abbr'))
			   ->where(array('t1.emp_resignation_id' => '0'));
		 $select->order('t2.id ASC');

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$temp_staff_no = array();
		$staff_no = array();
		foreach($resultSet as $set){
			$temp_staff_no[$set['abbr']][$set['gender']][$set['id']] = $set['id'];
		}

		foreach($temp_staff_no as $key => $value){
			foreach($value as $key2 => $value2){
				$staff_no[$key][$key2] = count($temp_staff_no[$key][$key2]);
			}
		}//var_dump($staff_no); die();

		return $staff_no;
	}

	public function getStaffOnLeave($organisation_id)
	{
		$date = date('Y-m-d');

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_leave'))
			   ->join(array('t2' => 'employee_details'),
					't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id', 'organisation_id', 'departments_id', 'departments_units_id'))
			   ->join(array('t3' => 'departments'),
					't3.id = t2.departments_id', array('department_name'))
			   ->join(array('t4' => 'department_units'),
					't4.id = t2.departments_units_id', array('unit_name'))
			   ->join(array('t5' => 'employee_details'),
					't1.approved_by = t5.id', array('fname' => 'first_name', 'mname' => 'middle_name', 'lname' => 'last_name', 'aemp_id' => 'emp_id'))
			   ->join(array('t6' => 'emp_leave_category'),
					't6.id = t1.emp_leave_category_id', array('leave_category'))
			   ->join(array('t7' => 'employee_details'),
					't7.id = t1.substitution', array('sf_name' => 'first_name', 'sm_name' => 'middle_name', 'sl_name' => 'last_name', 'semp_id' => 'emp_id'))
			   ->where(array('t2.organisation_id' => $organisation_id, 't1.from_date <= ?' => $date, 't1.to_date >= ?' => $date, 't1.leave_status' => 'Approved'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	
	public function getStaffOnTour($organisation_id)
	{
		$date = date('Y-m-d');

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'travel_authorization'))
			   ->join(array('t2' => 'employee_details'),
					't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id', 'organisation_id'))
			   ->join(array('t3' => 'departments'),
					't3.id = t2.departments_id', array('department_name'))
			   ->join(array('t4' => 'department_units'),
					't4.id = t2.departments_units_id', array('unit_name'))
			   ->join(array('t5' => 'employee_details'),
					't1.authorizing_officer = t5.id', array('fname' => 'first_name', 'mname' => 'middle_name', 'lname' => 'last_name', 'aemp_id' => 'emp_id'))
			   ->where(array('t1.organisation_id' => $organisation_id, 't1.tour_status' => 'Approved', 't1.start_date <= ?' => date('Y-m-d'), 't1.end_date >= ?' => date('Y-m-d')));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}

	public function getModuleAllocation($employee_detaild_id, $organisation_id) {
		//var_dump($organisation_id); die();
		$date = date('Y-m-d');

		$academic_event_details = $this->getSemester($organisation_id);

		if($academic_event_details){
			$semester_session = $academic_event_details['academic_event'];
			$academic_year = $this->getAcademicYear($academic_event_details);

			$sql = new Sql($this->dbAdapter);
			$select = $sql->select();

			$select->from(array('t1' => 'academic_modules_allocation'))
				->join(array('t2' => 'academic_module_tutors'),
						't1.id = t2.academic_modules_allocation_id')
				->join(array('t3' => 'employee_details'),
						't3.emp_id = t2.module_tutor')
				->join(array('t4' => 'programmes'),
						't1.programmes_id = t4.id')
				->join(array('t5' => 'assessment_component'),
						't5.academic_modules_allocation_id = t1.id')
				->join(array('t6' => 'student_section'),
						't2.section = t6.id', array('section'))
				->where(array('t3.organisation_id' => $organisation_id))
				->where(array('t1.academic_year' => $academic_year))
				->where(array('t1.academic_session' => $academic_event_details))
				->where(array('t5.weightage != ?' => '0'))
				->where(array('t3.id' => $employee_detaild_id))
				->order('t4.id')
	            ->order('t5.id')
	            ->group('t5.id');

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			return $resultSet->initialize($result);
		}
	}
	
	/*
	* Get the last Date for HRD / HRM Proposal
	*/
	
	public function getHRProposalDate($hr_type)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'hr_activation_date'))
				->join(array('t2' => 'five_year_plan'), 
                            't1.five_year_plan = t2.five_year_plan', array('five_year_plan'));
		//$select->where(array('t2.from_date <= ? ' => date('Y-m-d'), 't2.to_date >= ? ' => date('Y-m-d')));
		$select->where(array('t1.hr_proposal_type' => $hr_type));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$proposal_date = array();
		foreach($resultSet as $detail){
			 $proposal_date['date'] = $detail['end_date'];
			 $proposal_date['remarks'] = "The Last Date for the Submission of ".$hr_type;
		}
		
		return $proposal_date;
	}
	
	/*
	* Get the last Date for CARG/AURG
	*/
	
	public function getResearchDate()
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'research_grant_announcement'))
					->join(array('t2' => 'research_type'), 
                            't1.research_grant_type = t2.id', array('grant_type'));
                   // ->where('t1.employee_details_id = ' .$employee_id);
		$select->where(array('t1.start_date <= ? ' => date('Y-m-d'), 't1.end_date >= ? ' => date('Y-m-d')));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get the last Date for Publication
	*/
	
	public function getPublicationDate()
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'research_publication_announcement'));
		$select->where(array('t2.from_date <= ? ' => date('Y-m-d'), 't2.to_date >= ? ' => date('Y-m-d')));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get the last Date for APA / IWP Submission
	*/
	
	public function getPMSDate()
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'apa_activation_date'));
		$select->where(array('t2.from_date <= ? ' => date('Y-m-d'), 't2.to_date >= ? ' => date('Y-m-d')));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}



	public function getStudentDetails($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student'))
			   ->join(array('t2' => 'student_semester_registration'),
					't1.id = t2.student_id', array('semester_id'))
			   ->join(array('t3' => 'student_semester'),
					't3.id = t2.semester_id', array('semester', 'programme_year_id'))
			   ->join(array('t4' => 'programmes'),
					't4.id = t1.programmes_id', array('programme_name'))
		       ->where(array('t1.id = ?' => $student_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getStdCurrentSemesterDetails($student_id, $organisation_id)
	{
        //$academic_year = $this->getAcademicYear($organisation_id);
        $academic_event_details = $this->getSemester($organisation_id);

		if($academic_event_details)
		{
			$semester_session = $academic_event_details['academic_event'];
			$academic_year = $this->getAcademicYear($academic_event_details);

			$academic_session = $this->getCurrentAcademicSession($organisation_id);
			$assessment_component = array();
			$year = NULl;
			$semester = NULL;
			$programme = NULL;
			$academicYear = NULL;

			$sql = new Sql($this->dbAdapter);
			$select = $sql->select();

			$select->from(array('t1' => 'student_semester_registration'))
				->columns(array('student_id', 'semester_id', 'year_id', 'academic_session_id', 'academic_year'))
				->join(array('t2' => 'student'),
						't2.id = t1.student_id', array('programmes_id'))
					->where(array('t1.student_id = ?' => $student_id, 't1.academic_year' => $academic_year, 't1.academic_session_id' => $academic_session));

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);

			foreach($resultSet as $set){
				$year = $set['year_id'];
				$semester = $set['semester_id'];
				$programme = $set['programmes_id'];
				$academicYear = $set['academic_year'];
			} 

			$assessment_component = $this->getAssessmentComponentWeightage($year, $semester, $programme, $academicYear);

			return $assessment_component; 
		}
	}


	public function getAssessmentComponentWeightage($year, $semester, $programme, $academicYear)
	{ 
		$assessment_component_weightage = array();

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'assessment_component'))
			   ->join(array('t2' => 'academic_modules_allocation'),
					't2.id = t1.academic_modules_allocation_id', array('year', 'semester', 'academic_year', 'programmes_id'));
		$select->where(array('t2.year' => $year, 't2.semester' => $semester, 't2.academic_year' => $academicYear, 't2.programmes_id' => $programme));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $assessment_component = array();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        foreach($resultSet as $set){
        	$assessment_component[$set['id']] = $set['assessment'];
        }

        foreach($assessment_component as $key => $value){
        	$select2 = $sql->select();
			$select2->from(array('t1' => 'assessment_component'))
				    ->join(array('t2' => 'academic_modules_allocation'),
							't2.id = t1.academic_modules_allocation_id', array('academic_modules_id'));
            $select2->where(array('t1.id = ' .$key));

			$stmt2 = $sql->prepareStatementForSqlObject($select2);
			$result2 = $stmt2->execute();
			
			$resultSet2 = new ResultSet();
			$resultSet2->initialize($result2);
			foreach($resultSet2 as $set2){
				$assessment_component_weightage[$value][$set2['academic_modules_id']] = $set2['weightage'];
			}	
        }
       // var_dump($value);
        //var_dump($assessment_component_weightage); die();
        return $assessment_component_weightage;

	}


	public function getAcademicModuleLists($student_id, $organisation_id)
	{
		//$academic_year = $this->getAcademicYear($organisation_id);
		$academic_event_details = $this->getSemester($organisation_id);

		if($academic_event_details){
			$semester_session = $academic_event_details['academic_event'];
			$academic_year = $this->getAcademicYear($academic_event_details);

			$academic_session = $this->getCurrentAcademicSession($organisation_id);
			$module_list = array();
			$year = NULl;
			$semester = NULL;
			$programme = NULL;
			$academicYear = NULL;

			$sql = new Sql($this->dbAdapter);
			$select = $sql->select();

			$select->from(array('t1' => 'student_semester_registration'))
				->columns(array('student_id', 'semester_id', 'year_id', 'academic_session_id', 'academic_year'))
				->join(array('t2' => 'student'),
						't2.id = t1.student_id', array('programmes_id'))
					->where(array('t1.student_id = ?' => $student_id, 't1.academic_year' => $academic_year, 't1.academic_session_id' => $academic_session));

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);

			foreach($resultSet as $set){
				$year = $set['year_id'];
				$semester = $set['semester_id'];
				$programme = $set['programmes_id'];
				$academicYear = $set['academic_year'];
			}

			$module_list = $this->getModuleLists($year, $semester, $programme, $academicYear);
		// /var_dump($module_list); die();
			return $module_list;
		}
	}


	public function getModuleLists($year, $semester, $programme, $academic_year)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_modules_allocation'))
			   ->join(array('t2' => 'academic_modules'),
					't2.id = t1.academic_modules_id', array('module_title', 'module_code'));
		$select->where(array('t1.year' => $year, 't1.semester' => $semester, 't1.academic_year' => $academic_year, 't1.programmes_id' => $programme));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $modules = array();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        foreach($resultSet as $set){
        	$modules[$set['academic_modules_id']][$set['module_code']] = $set['module_title'];
        }

        return $modules;
	}


	public function getStdCurrentCADetails($student_id, $organisation_id)
	{
		//$academic_year = $this->getAcademicYear($organisation_id);
		$academic_event_details = $this->getSemester($organisation_id);

		if($academic_event_details){
			$semester_session = $academic_event_details['academic_event'];
			$academic_year = $this->getAcademicYear($academic_event_details);

			$academic_session = $this->getCurrentAcademicSession($organisation_id);
			$assessment_component = array();
			$year = NULl;
			$semester = NULL;
			$programme = NULL;
			$academicYear = NULL;

			$sql = new Sql($this->dbAdapter);
			$select = $sql->select();

			$select->from(array('t1' => 'student_semester_registration'))
				->columns(array('student_id', 'semester_id', 'year_id', 'academic_session_id', 'academic_year'))
				->join(array('t2' => 'student'),
						't2.id = t1.student_id', array('programmes_id'))
					->where(array('t1.student_id = ?' => $student_id, 't1.academic_year' => $academic_year, 't1.academic_session_id' => $academic_session));

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);

			foreach($resultSet as $set){
				$year = $set['year_id'];
				$semester = $set['semester_id'];
				$programme = $set['programmes_id'];
				$academicYear = $set['academic_year'];
			}

			$ca_component = $this->getCAComponentWeightage($year, $semester, $programme, $academicYear);

			return $ca_component;
		}
	}


	public function getCAComponentWeightage($year, $semester, $programme, $academicYear)
	{
		$assessment_component_weightage = array();

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'assessment_component'))
			   ->join(array('t2' => 'academic_modules_allocation'),
					't2.id = t1.academic_modules_allocation_id', array('year', 'semester', 'academic_year', 'programmes_id'));
		$select->where(array('t2.year' => $year, 't2.semester' => $semester, 't2.academic_year' => $academicYear, 't2.programmes_id' => $programme));
		$select->where->notLike('t1.assessment','%Semester Exams%');

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $assessment_component = array();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        foreach($resultSet as $set){
        	$assessment_component[$set['id']] = $set['assessment'];
        }

        foreach($assessment_component as $key => $value){
        	$select2 = $sql->select();
			$select2->from(array('t1' => 'assessment_component'))
				    ->join(array('t2' => 'academic_modules_allocation'),
							't2.id = t1.academic_modules_allocation_id', array('academic_modules_id'));
            $select2->where(array('t1.id = ' .$key));

			$stmt2 = $sql->prepareStatementForSqlObject($select2);
			$result2 = $stmt2->execute();
			
			$resultSet2 = new ResultSet();
			$resultSet2->initialize($result2);
			foreach($resultSet2 as $set2){
				$assessment_component_weightage[$value][$set2['academic_modules_id']] = $set2['weightage'];
			}	
        }
       // var_dump($value);
        //var_dump($assessment_component_weightage); die();
        return $assessment_component_weightage;
	}


	public function getStudentAcademicTimetable($student_id, $organisation_id)
	{
		//$academic_year = $this->getAcademicYear($organisation_id);
		$academic_event_details = $this->getSemester($organisation_id);

		if($academic_event_details){
			$semester_session = $academic_event_details['academic_event'];
			$academic_year = $this->getAcademicYear($academic_event_details);

			$academic_session = $this->getCurrentAcademicSession($organisation_id);
			$academic_timetable = array();
			$year = NULl;
			$semester = NULL;
			$section = NULL;
			$programme = NULL;
			$academicYear = NULL;

			$sql = new Sql($this->dbAdapter);
			$select = $sql->select();

			$select->from(array('t1' => 'student_semester_registration'))
				->columns(array('student_id', 'semester_id', 'student_section_id', 'year_id', 'academic_session_id', 'academic_year'))
				->join(array('t2' => 'student'),
						't2.id = t1.student_id', array('programmes_id'))
					->where(array('t1.student_id = ?' => $student_id, 't1.academic_year' => $academic_year, 't1.academic_session_id' => $academic_session));

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);

			foreach($resultSet as $set){
				$year = $set['year_id'];
				$semester = $set['semester_id'];
				$section = $set['student_section_id'];
				$programme = $set['programmes_id'];
				$academicYear = $set['academic_year'];
			} 

			$academic_timetable = $this->getAcademicTimetable($year, $semester, $section, $programme, $academicYear);
			
			return $academic_timetable;
		}
	}


	public function getAcademicTimetable($year, $semester, $section, $programme, $academicYear)
	{
		$sql = new Sql($this->dbAdapter); 
		$select = $sql->select();

		$select->from(array('t1' => 'academic_timetable')) 
                            ->join(array('t2' => 'academic_modules_allocation'), 
                                't1.academic_modules_allocation_id = t2.id', array('academic_modules_id'))
                            ->join(array('t3' => 'academic_modules'), 
                                't2.academic_modules_id = t3.id', array('module_title', 'module_code', 'programmes_id'))
                            ->join(array('t4' => 'programmes'), 
                                't3.programmes_id = t4.id', array('programme_name'))
                            ->join(array('t5' => 'academic_modules_allocation'),
                        		't5.id = t1.academic_modules_allocation_id', array('semester'))
                            ->join(array('t6' => 'academic_module_tutors'),
                        		't6.academic_modules_allocation_id = t5.id', array('year', 'module_tutor'))
                            ->join(array('t7' => 'student_section'),
                        		't7.id = t6.section', array('section'))
                            ->join(array('t8' => 'employee_details'),
                        		't8.emp_id = t6.module_tutor', array('first_name', 'middle_name', 'last_name'))
                            ->join(array('t9' => 'academic_module_coordinators'),
                        		't9.academic_modules_id = t2.academic_modules_id', array('module_coordinator'))
                            ->join(array('t10' => 'employee_details'),
                        		't10.emp_id = t9.module_coordinator', array('mcfirst_name' => 'first_name', 'mcmiddle_name' => 'middle_name', 'mclast_name' => 'last_name'));

		$select->where(array('t5.semester' => $semester, 't1.group' => $section, 't6.section' => $section, 't1.academic_year' => $academicYear, 't1.programmes_id' => $programme, 't3.module_year' => $year));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->buffer();
		return $resultSet->initialize($result);
	}


	public function getTimetableTiming($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_timetable_timing'));
		$select->where(array('organisation_id' =>$organisation_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$timetable_timing = array();
		foreach($resultSet as $set){
			$timetable_timing[] = $set['from_time'].'-'.$set['to_time'];
		}

		return $timetable_timing;
	}


	public function getAbsenteeModuleRecord($student_id, $organisation_id)
	{
		//$academic_year = $this->getAcademicYear($organisation_id);
		$academic_event_details = $this->getSemester($organisation_id);

		if($academic_event_details){
			$semester_session = $academic_event_details['academic_event'];
			$academic_year = $this->getAcademicYear($academic_event_details);

			$academic_session = $this->getCurrentAcademicSession($organisation_id);
			$student_attendance_data = array();
			$year = NULl;
			$semester = NULL;
			$section = NULL;
			$programme = NULL;
			$academicYear = NULL;

			$sql = new Sql($this->dbAdapter);
			$select = $sql->select();

			$select->from(array('t1' => 'student_semester_registration'))
				->columns(array('student_id', 'semester_id', 'student_section_id', 'year_id', 'academic_session_id', 'academic_year'))
				->join(array('t2' => 'student'),
						't2.id = t1.student_id', array('programmes_id'))
					->where(array('t1.student_id = ?' => $student_id, 't1.academic_year' => $academic_year, 't1.academic_session_id' => $academic_session));

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);

			foreach($resultSet as $set){
				$year = $set['year_id'];
				$semester = $set['semester_id'];
				$section = $set['student_section_id'];
				$programme = $set['programmes_id'];
				$academicYear = $set['academic_year'];
			} 

			$module_list = $this->getModuleLists($year, $semester, $programme, $academicYear); 
			foreach($module_list as $key=>$value){ 
				foreach($value as $key2=>$value2){ 
					//$academic_modules_allocation_id = $this->getAcademicModuleAllocationId($key, $year, $semester, $programme, $academicYear); 
					$student_attendance_data[$key]['module_title'] = $value2;
					$student_attendance_data[$key]['module_code'] = $key2;
					$academic_modules_allocation_id = $this->getAcademicModuleAllocationId($key, $year, $semester, $programme, $academicYear); 
					foreach($academic_modules_allocation_id as $value3)
					{
						$student_attendance_data[$key]['total_lectures_delivered'] = $this->getTotalLecturesDelivered($value3, $year, $semester, $section, $programme, $academicYear);
					$student_attendance_data[$key]['total_lectures_missed'] = $this->getTotalLecturesMissed($key, $year, $semester, $section, $programme, $academicYear, $student_id);
					}
					
				}
			} 
			
			//var_dump($student_attendance_data[$key]['total_lectures_delivered']); die();
			return $student_attendance_data;
		}
	}


	public function getTotalLecturesDelivered($academic_modules_allocation_id, $year, $semester, $section, $programme, $academicYear)
	{
        $total_lectures = array();
        
       // $academic_modules_allocation_id = $this->getAcademicModuleAllocationId($academic_modules_id, $year, $semester, $programme, $academicYear);

    	//$academic_timetable_id = $this->getAcademicTimetableId($section, $academicYear, $programme, $academic_modules_allocation_id);


        $sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_timetable'));
		$select->where(array('t1.group' => $section, 't1.academic_year' => $academicYear, 't1.programmes_id' => $programme, 't1.academic_modules_allocation_id' => $academic_modules_allocation_id));

		$stmt = $sql->prepareStatementForSqlObject($select);		
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$academic_timetable_id = array();
		foreach($resultSet as $set){  
			$academic_timetable_id[$set['id']] = $set['id'];
		} 

		foreach($academic_timetable_id as $key=>$value){ 
			$sql = new Sql($this->dbAdapter);
			$select1 = $sql->select();

			$select1->from(array('t1' => 'student_attendance_dates'));
				   //->join(array('t2' => 'academic_timetable'),
					//	't2.id = t1.academic_timetable_id', array('academic_modules_allocation_id', 'group'))
				   //->join(array('t3' => 'academic_modules_allocation'),
					//	't3.id = t2.academic_modules_allocation_id', array('academic_year', 'semester', 'year', 'programmes_id'));
			$select1->where(array('t1.academic_timetable_id' => $key, 't1.academic_modules_allocation_id' => $academic_modules_allocation_id));

			$stmt1 = $sql->prepareStatementForSqlObject($select1);		
			$result1 = $stmt1->execute();
			
			$resultSet1 = new ResultSet();
			$resultSet1->initialize($result1);
			
			foreach($resultSet1 as $set1){
				$total_lectures[$set1['id']] = $set1['id'];
			}
		}
		return count($total_lectures);
	}

	public function getAcademicTimetableId($section, $academicYear, $programme, $academic_modules_allocation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_timetable'));
		$select->where(array('t1.group' => $section, 't1.academic_year' => $academicYear, 't1.programmes_id' => $programme, 't1.academic_modules_allocation_id' => $academic_modules_allocation_id));

		$stmt = $sql->prepareStatementForSqlObject($select);		
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$academic_timetable_id = array();
		foreach($resultSet as $set){
			$academic_timetable_id[] = $set['id'];
		}

		return $academic_timetable_id;
	}


	public function getAcademicModuleAllocationId($academic_modules_id, $year, $semester, $programme, $academicYear)
	{		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_modules_allocation'));
		$select->where(array('t1.academic_year' => $academicYear, 't1.semester' => $semester, 't1.year' => $year, 't1.programmes_id' => $programme, 't1.academic_modules_id' => $academic_modules_id));

		$stmt = $sql->prepareStatementForSqlObject($select);		
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$academic_modules_allocation_id = array();

		foreach($resultSet as $set){
			$academic_modules_allocation_id[]= $set['id'];
		} 
		
		return $academic_modules_allocation_id;
	}


	public function getTotalLecturesMissed($academic_modules_id, $year, $semester, $section, $programme, $academicYear, $student_id)
	{
		$missed_class = array();
		$studentId = $this->getStudentId($student_id);
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_absentee_record'))
				->join(array('t2' => 'student_attendance_dates'), 
					't2.id = t1.student_attendance_dates_id', array('academic_timetable_id'))
				->join(array('t3' => 'academic_timetable'),
					't3.id = t2.academic_timetable_id', array('group', 'academic_modules_allocation_id'))
				->join(array('t4' => 'academic_modules_allocation'),
					't4.id = t3.academic_modules_allocation_id', array('academic_year', 'semester', 'year', 'programmes_id'));
		$select->where(array('t1.student_id' => $studentId, 't4.academic_year' => $academicYear, 't4.semester' => $semester, 't4.year' => $year, 't4.programmes_id' => $programme, 't3.group' => $section, 't4.academic_modules_id' => $academic_modules_id));


		$stmt = $sql->prepareStatementForSqlObject($select);		
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$missed_class[$set['id']] = $set['id'];
		} 
		
		return count($missed_class);
	}


	public function getStudentId($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student'))
					->columns(array('student_id'));
		$select->where(array('t1.id' => $student_id));

		$stmt = $sql->prepareStatementForSqlObject($select);		
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$student_id = NULL;
		foreach($resultSet as $set){
			$student_id = $set['student_id'];
		}

		return $student_id;
	}


	public function getAcademicModuleTutor($student_id, $organisation_id)
	{
		//$academic_year = $this->getAcademicYear($organisation_id);
		$academic_event_details = $this->getSemester($organisation_id);

		if($academic_event_details){
			$semester_session = $academic_event_details['academic_event'];
			$academic_year = $this->getAcademicYear($academic_event_details);

			$sql = new Sql($this->dbAdapter);
			$select = $sql->select();

			$select->from(array('t1' => 'academic_timetable')) 
								->join(array('t2' => 'academic_modules_allocation'), 
									't1.academic_modules_allocation_id = t2.id', array('academic_year', 'semester', 'year', 'programmes_id', 'academic_modules_id'))
								->join(array('t3' => 'student'), 
									't2.programmes_id = t3.programmes_id', array('id'))
								->join(array('t4' => 'student_semester_registration'), 
									't3.id = t4.student_id', array('student_section_id','semester_id'))
								->join(array('t5' => 'programmes'), 
									't1.programmes_id = t5.id', array('programme_name'))
								->join(array('t6' => 'academic_module_tutors'),
									't2.id = t6.academic_modules_allocation_id', array('module_tutor'))
								->join(array('t7' => 'academic_modules'),
									't7.id = t2.academic_modules_id', array('module_code'));
			$select->where(array('t4.student_id' => $student_id, 't1.semester = t4.semester_id'));

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->buffer();
			return $resultSet->initialize($result);
		}
	}
	
	/*
	* Get Promotion Dates
	*/
	
	public function getPromotionDates($employee_id)
	{
		
	}
	
	/*
	* Get Leave Balance and Notify if balance exceeds 80 days
	*/
	
	public function getLeaveBalance($employee_id)
	{
		
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
			$select->where(array('t2.organisation_id' => $organisation_id));
					
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			
			$semester = NULL;
			
			/*foreach($resultSet as $set){
				if($set['academic_event'] == 'Autumn Semester Duration'){
					$semester = 'Autumn';
				}
				else if($set['academic_event'] == 'Spring Semester Duration'){
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
		//$academic_event_details = $this->getSemester();

        $semester = $academic_event_details['academic_event'];
        $academic_year = $academic_event_details['academic_year'];
		//$academic_year = NULL;
		
		if($semester == 'Autumn'){
			$academic_year; // = (date('Y')).'-'.(date('Y')+1);
		} else {
			$academic_year; // = (date('Y')-1).'-'.date('Y');
		}
		
		return $academic_year;
	}

	/*
	public function getAcademicYear($organisation_id)
    {
    	$date = date('Y-m-d');

        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'academic_calendar')) 
               ->columns(array('academic_year'))
               ->join(array('t2' => 'academic_calendar_events'),
                    't2.id = t1.academic_event', array('academic_event'));
        $select->where(array('t1.from_date <= ?' => $date, 't1.to_date >= ?' => $date, 't2.organisation_id' => $organisation_id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
            
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
            
        //Need to make the resultSet as an array
        // e.g. 1=> Category 1, 2 => Category etc.
            
        $academicYear = NULL;
        foreach($resultSet as $set)
        {
            $academicYear = $set['academic_year'];
        }
        return $academicYear;
    }*/


    /*
     * Get the semester from the database
     */
    
    public function getCurrentAcademicSession($organisation_id)
    { 
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        if(empty($organisation_id)){
        	return;
        }else{
        	$select->from(array('t1' => 'academic_calendar'))
                    ->columns(array('academic_year'))
                ->join(array('t2' => 'academic_calendar_events'), 
                        't1.academic_event = t2.id', array('academic_event', 'academic_session_id'));
	        $select->where(array('from_date <= ? ' => date('Y-m-d')));
	        $select->where(array('to_date >= ? ' => date('Y-m-d')));
	        $select->where('t2.organisation_id = ' .$organisation_id);
        }
                
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
        $academic_session = NULL;
        
        foreach($resultSet as $set){
            if($set['academic_event'] == 'Autumn Semester Duration'){
                $academic_session = $set['academic_session_id'];
            }
            else if($set['academic_event'] == 'Spring Semester Duration'){
                $academic_session = $set['academic_session_id'];
            }
        }
        return $academic_session;
    }


    public function getStdAcademicModuleLists($student_id, $organisation_id)
    {
    	//$academic_year = $this->getAcademicYear($organisation_id);

    	$academic_event_details = $this->getSemester($organisation_id);

		if($academic_event_details){
			$semester_session = $academic_event_details['academic_event'];
			$academic_year = $this->getAcademicYear($academic_event_details);

			$sql = new Sql($this->dbAdapter);
			$select = $sql->select();

			$select->from(array('t1' => 'academic_timetable')) 
								->join(array('t2' => 'academic_modules_allocation'),
									't2.id = t1.academic_modules_allocation_id', array('academic_year', 'semester', 'year'))
								->join(array('t3' => 'student'), 
									't2.programmes_id = t3.programmes_id', array('id'))
								->join(array('t4' => 'student_semester_registration'), 
									't3.id = t4.student_id', array('student_section_id','semester_id'))
								->join(array('t5' => 'programmes'), 
									't1.programmes_id = t5.id', array('programme_name'))
								->join(array('t6' => 'academic_modules'), 
									't2.academic_modules_id = t6.id', array('module_code'))
								->join(array('t7' => 'academic_module_tutors'),
									't6.id = t7.academic_modules_allocation_id', array('module_tutor'));
			$select->where(array('t4.student_id' => $student_id, 't1.semester = t4.semester_id'));

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->buffer();
			return $resultSet->initialize($result);
		}
    }


	public function listSelectData($tableName, $columnName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array('id','abbr', $columnName));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);		
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['id']] = $set['abbr'];
		}
		return $selectData;
	}


	/**
	* @return array/EmployeeLeave()
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData1($tableName, $columnName)
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

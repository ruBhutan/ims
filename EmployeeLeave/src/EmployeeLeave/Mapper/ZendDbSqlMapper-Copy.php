<?php

namespace EmployeeLeave\Mapper;

use EmployeeLeave\Model\EmployeeLeave;
use EmployeeLeave\Model\OfficiatingSupervisor;
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
		$select->columns(array('id', 'departments_units_id'));
			
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
	public function findAllLeave($status, $employee_details_id, $userrole, $organisation_id)
	{
		/*$i = 1;
		$auth = array();
		$auth_type =  $this->getAuthType($userrole);
		foreach($auth_type as $key=>$value){
			$auth[$i++] = $key;
		} */



		$check_assigned_officiating = $this->checkOwnAssignedOfficiating($userrole);

		if($check_assigned_officiating){ 
			return;
		}else{

		$check_authorized_role = $this->checkAuthorizedRole($userrole, $organisation_id);

		if($check_authorized_role){
		//stores values of the departments that user can authorise
		$role_departments = array();
	
		//store values of the user authoriser's department
		$authoriser_department = array(); 
		
		//store values of the user authorisation type
		$type_authorisation = array();

		//authorizee roles
		$authorizee_role = array();
		
		//authorizee emp ids
		$authorizee_emp_ids = array();
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
				
		//first get the department, organisation and authtype for the user role
		$select->from(array('t1' => 'user_workflow'))
					->columns(array('role_department','department','type', 'role'));
		$select->where(array('t1.auth = ? ' => $userrole));
		$select->where(array('t1.organisation = ? ' => $organisation_id));
		//$select->where(array('t1.type'=>$auth));
		$select->where->like('t1.type', '%Leave%');
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result); 
		
		foreach($resultSet as $tmp_data){
			$role_departments[$tmp_data['role_department']] = $tmp_data['role_department']; 
			$authoriser_department[$tmp_data['department']] = $tmp_data['department'];
			$authorizee_role[$tmp_data['role']]  = $tmp_data['role'];
			if(!in_array($tmp_data['type'],$type_authorisation))
				$type_authorisation[] = $tmp_data['type'];
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
			//$select->where(array('t1.type'=>$auth));
			$select->where->like('t1.type', '%Leave%');
			
			$stmt3 = $sql->prepareStatementForSqlObject($select3);
			$result3 = $stmt3->execute();
			
			$resultSet3 = new ResultSet();
			$resultSet3->initialize($result3);
			
			foreach($resultSet3 as $tmp_data){
				$role_departments[$tmp_data['role_department']] = $tmp_data['role_department']; 
				$authoriser_department[$tmp_data['department']] = $tmp_data['department'];
				$authorizee_role[$tmp_data['role']]  = $tmp_data['role'];
				if(!in_array($tmp_data['type'],$type_authorisation))
					$type_authorisation[] = $tmp_data['type'];
			} //var_dump($type_authorisation);
		}
		
		$authorizee_emp_ids = $this->getEmployeeIdByRoles($authorizee_role);

		$leave_data = $this->listSelectData('emp_leave_category', 'leave_category');
		$applied_leave_categories = array();
		foreach($leave_data as $key=>$value){
			if(in_array($value, $type_authorisation))
				$applied_leave_categories[]=$key;
		}                 
                
		//if the role departments are empty, then user has no authority
		if(!empty($role_departments)){
			//get the list of employees
			$select2 = $sql->select();
			$select2->from(array('t1' => 'emp_leave'))
						->join(array('t2' => 'employee_details'), 
								't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'));
			$select2->where(array('t1.leave_status = ? ' => $status));
			$select2->where(array('t1.from_date >= ? ' => date('Y'.'-01-01')));
			$select2->where(array('t2.departments_units_id ' => $role_departments));
			$select2->where(array('t1.emp_leave_category_id ' => $applied_leave_categories));
			if($authorizee_emp_ids){
				$select2->where(array('t1.employee_details_id ' => $authorizee_emp_ids));
			}
			
			$stmt2 = $sql->prepareStatementForSqlObject($select2);
			$result2 = $stmt2->execute();
			
			$resultSet2 = new ResultSet();
			return $resultSet->initialize($result2);
		}
		//empty, so return empty array
		$empty = array();
		return $empty;
		}
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
		$select->where->like('type','%Leave');
		
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

	/*
	* Get employee ids given roles
	*/

	private function getEmployeeIdByRoles($userroles)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
				
		//first get the department, organisation and authtype for the user role
		$select->from(array('t1' => 'users'))
						->join(array('t2' => 'employee_details'), 
								't1.username = t2.emp_id', array('id'));
		$select->where(array('t1.role ' => $userroles));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$employee_ids = array();
		foreach($resultSet as $set){
            $employee_ids[$set['id']] = $set['id'];
        }
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
                
                $leave_dates = $leaveData['from_Date'];
                
		$file_name = $leaveData['evidence_File'];
		$leaveData['evidence_File'] = $file_name['tmp_name'];
		$leaveData['from_Date'] = date("Y-m-d", strtotime(substr($leave_dates,0,10)));
		$leaveData['to_Date'] = date("Y-m-d",strtotime(substr($leave_dates,13,10)));
                
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
	 
	public function saveOfficiatingOfficer(OfficiatingSupervisor $leaveObject, $employee_details_id, $userrole)
	{
		$leaveData = $this->hydrator->extract($leaveObject);
		unset($leaveData['id']);
		
		$department = $this->getSupervisorDepartment($employee_details_id);
		//set the data
		$leaveData['supervisor'] = $userrole;
		$leaveData['department'] = $department;
		$leaveData['from_Date'] = date("Y-m-d", strtotime(substr($leaveData['date_Range'],0,10)));
		$leaveData['to_Date'] = date("Y-m-d",strtotime(substr($leaveData['date_Range'],13,10)));
		$leaveData['supervisor_Id'] = $employee_details_id;
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
	 public function updateEmpLeaveBalance($id, $casual_leave, $earned_leave)
	 {
	 	$leaveData['casual_Leave'] = $casual_leave;
		$leaveData['earned_Leave'] = $earned_leave;
		
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
	 public function getEmpOfficiatedRole($officiating)
	 {
	 	$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'user_workflow_officiating'))
                    ->where(array('t1.officiating_supervisor' =>$officiating));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	 }


	 /*
	 *Check whether particular logge in user have already assigned his/her officiating within that date
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
                    ->where(array('t2.supervisor_id' =>$employee_details_id));

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
                    ->where(array('t1.organisation_id' =>$organisation_id));
					//->where(array('t3.position' =>$employee_details_id));

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
		} else if($type == "Study Leave") {
			$leave_id = $this->getLeaveId($type);
			$select->from(array('t1' => 'emp_leave'))
					->columns(array('employee_details_id','days_of_leave','emp_leave_category_id'))
                                    ->where(array('employee_details_id' =>$employee_details_id))
                                    ->where(array('leave_status' => 'Approved'))
                                    ->where(array('emp_leave_category_id' =>$leave_id));
		} /*else if($type == "Maternity Leave") {
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
	* Get the Department of the Supervisor when assigning Officiating Supervisor
	*/
	
	public function getSupervisorDepartment($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'))
					->columns(array('departments_id'))
                    ->where(array('id' =>$employee_details_id));

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
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'user_workflow'))
					->columns(array('auth', 'department'))
					->join(array('t2' => 'emp_leave_category'), 
                            't1.type = t2.leave_category', array('leave_category'))
                    ->where(array('t1.role' =>$role))
					->where(array('t1.role_department' =>$departments_id))
                    ->where(array('t2.id' =>$id));

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


	public function getEmployeeLeaveDetails($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        if($organisation_id != 1){
        	$select->from(array('t1' => 'emp_leave_balance'))
        	   ->join(array('t2' => 'employee_details'),
        			't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id', 'organisation_id'));             
        	$select->where(array('t2.organisation_id' => $organisation_id));
        }else{
        	$select->from(array('t1' => 'emp_leave_balance'))
        	   ->join(array('t2' => 'employee_details'),
        			't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id', 'organisation_id'));
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

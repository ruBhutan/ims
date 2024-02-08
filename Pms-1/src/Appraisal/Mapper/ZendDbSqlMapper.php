<?php

namespace Appraisal\Mapper;

use Appraisal\Model\Appraisal;
use Appraisal\Model\AcademicAppraisal;
use Appraisal\Model\AcademicWeight;
use Appraisal\Model\IwpObjectives;
use Appraisal\Model\NatureActivity;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements AppraisalMapperInterface
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
	 * @var \Appraisal\Model\AppraisalInterface
	*/
	protected $appraisalPrototype;
	
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			Appraisal $appraisalPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->appraisalPrototype = $appraisalPrototype;
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
		$select->columns(array('id'));
			
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
	
	/*
	* Take username and return the occupational group of the user
	*/
	
	public function getOccupationalGroup($username)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'))
				->columns(array('id'))
				->join(array('t2' => 'emp_position_title'), 
									't1.id = t2.employee_details_id', array('employee_details_id'))
							->join(array('t3'=>'position_title'),
									't2.position_title_id = t3.id', array('position_title'))
							->join(array('t4'=>'position_category'),
									't3.position_category_id = t4.id', array('major_occupational_group_id'))
							->join(array('t5'=>'major_occupational_group'),
									't4.major_occupational_group_id = t5.id', array('major_occupational_group'));
		$select->where(array('t1.emp_id' =>$username));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/**
	* @return array/Appraisal()
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
	* @return array/Appraisal()
	*/
	public function findEmployeeAppraisal($tableName, $employee_id, $status)
	{
		$appraisal_period = $this->getAppraisalPeriod();

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
        if($status == NULL){
			$select->from(array('t1' => $tableName)) 
                    ->join(array('t2' => 'pms_nature_activity'), 
                            't1.pms_nature_activity_id = t2.id', array('nature_of_activity'))
                    ->join(array('t3'=>'pms_academic_weight'),
                            't2.pms_academic_weight_id = t3.id', array('category'))
					->join(array('t4'=>'awpa_objectives_activity'),
                            't1.awpa_objectives_activity_id = t4.id', array('activity_name'))
					->where(array('t1.employee_details_id = ' .$employee_id, 't1.appraisal_period' => $appraisal_period, 't1.status' => $status))
					->order('t3.id ASC')
					->order('t4.activity_name ASC');
		}
		if($status == 'Not Submitted'){
			$select->from(array('t1' => $tableName)) 
                    ->join(array('t2' => 'pms_nature_activity'), 
                            't1.pms_nature_activity_id = t2.id', array('nature_of_activity'))
                    ->join(array('t3'=>'pms_academic_weight'),
                            't2.pms_academic_weight_id = t3.id', array('category'))
					->join(array('t4'=>'awpa_objectives_activity'),
                            't1.awpa_objectives_activity_id = t4.id', array('activity_name'))
					->where(array('t1.employee_details_id = ' .$employee_id, 't1.appraisal_period' => $appraisal_period, 't1.status' => $status))
					->order('t3.id ASC')
					->order('t4.activity_name ASC');
		} 
		else {
			$select->from(array('t1' => $tableName)) 
                    ->join(array('t2' => 'pms_nature_activity'), 
                            't1.pms_nature_activity_id = t2.id', array('nature_of_activity'))
                    ->join(array('t3'=>'pms_academic_weight'),
                            't2.pms_academic_weight_id = t3.id', array('category'))
					->join(array('t4'=>'awpa_objectives_activity'),
                            't1.awpa_objectives_activity_id = t4.id', array('activity_name'))
					->where(array('t1.employee_details_id = ' .$employee_id, 't1.appraisal_period' => $appraisal_period))
					->order('t3.id ASC')
					->order('t4.activity_name ASC');
			$select->where->notEqualTo('t1.status','Approved');
		}
		
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

	}
        
	/*
	 * Get the list of Success Indicators of the Supervisor for staff
	 */
	
	public function getSupervisorSuccessIndicators($employee_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
	
		$select->from(array('t1' => 'awpa_objectives_activity'));
		$select->columns(array('id','activity_name'));
				$supervisor_id = $this->getSupervisorForEmployee($employee_id);
				if($supervisor_id == NULL){
						$selectData = array();
						return $selectData;
				}
		$select->where(array('employee_details_id' => $supervisor_id));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['id']] = $set['activity_name'];
		}
		return $selectData;
        }
	
	/**
	* @return array/Appraisal()
	*/
	public function listAdministrativeAppraisal($tableName, $employee_id, $status)
	{
		$appraisal_period = $this->getAppraisalPeriod();
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
							
		if($status == NULL){
			$select->from(array('t1' => $tableName))
					->join(array('t2' => 'awpa_objectives_activity'), 
                            't1.awpa_objectives_activity_id = t2.id', array('activity_name'))
					->where(array('t1.employee_details_id = ' .$employee_id));
			$select->where(array('appraisal_period' =>$appraisal_period));
					//->order('awpa_objectives_activity_id ASC');
		}
		else if($status == 'Not Submitted'){
			$select->from(array('t1' => $tableName))
					->join(array('t2' => 'awpa_objectives_activity'), 
                            't1.awpa_objectives_activity_id = t2.id', array('activity_name'))
					->where(array('t1.employee_details_id = ' .$employee_id));
			$select->where(array('appraisal_period' =>$appraisal_period, 't1.status' => $status));
					//->order('awpa_objectives_activity_id ASC');
		}
		 else {
			$select->from(array('t1' => $tableName))
					->join(array('t2' => 'awpa_objectives_activity'), 
                            't1.awpa_objectives_activity_id = t2.id', array('activity_name'))
			//$select->where(array('status' =>'Rejected', 'status' =>'Approve Conditional to Changes'));
					->where(array('t1.employee_details_id = ' .$employee_id));
			$select->where(array('appraisal_period' =>$appraisal_period));
					//->order('awpa_objectives_activity_id ASC');
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

	}
	
	/**
	* @return array/Appraisal()
	*/
	public function findActivityDetail($tableName, $columnName, $activity_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->where(array($columnName.' = ?' => $activity_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

	}
	
	/**
	 * 
	 * @param type $AppraisalInterface
	 * 
	 * to save Academic Appraisal Details
	 */
	
	public function saveAcademicAppraisal(AcademicAppraisal $appraisalObject)
	{
		$appraisalData = $this->hydrator->extract($appraisalObject);
		unset($appraisalData['id']);
		
		if($appraisalObject->getId()) {
			//ID present, so it is an update
			$action = new Update('pms_academic_api');
			$action->set($appraisalData);
			$action->where(array('id = ?' => $appraisalObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('pms_academic_api');
			$action->values($appraisalData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$appraisalObject->setId($newId);
			}
			return $appraisalObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	public function saveAdministrativeAppraisal(IwpObjectives $appraisalObject)
	{
		$appraisalData = $this->hydrator->extract($appraisalObject);
		unset($appraisalData['id']);

		if($appraisalObject->getId()) {
			//ID present, so it is an update
			$action = new Update('iwp_subactivities');
			$action->set($appraisalData);
			$action->where(array('id = ?' => $appraisalObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('iwp_subactivities');
			$action->values($appraisalData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$appraisalObject->setId($newId);
			}
			return $appraisalObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Get the deadline for the IWP
	*/
	
	public function getIwpDeadline($iwp_type)
	{
		$deadline = NULL;
		$appraisal_period = $this->getAppraisalPeriod();
		 
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'pms_activation_dates'))
							->columns(array('start_date', 'end_date'));
		$select->where(array('pms_year' =>$appraisal_period));
		$select->where(array('date_for' =>$iwp_type));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}

	public function getAppraisalPeriodYear($iwp_type, $tableName)
	{
		$appraisal_period = NULL;
		 
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName))
							->columns(array('pms_year'));
		$select->where(array('t1.start_date <= ?' => date('Y-m-d'), 't1.end_date >= ?' => date('Y-m-d')));
		$select->where(array('t1.date_for' =>$iwp_type));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		foreach($resultSet as $set){
			$appraisal_period = $set['pms_year'];
		}
		return $appraisal_period;
	}
	
	/*
	* Save Administrative/Academic Reviews from Supervisor
	*/
	
	public function saveReview($data, $type)
	{
		if($type=='Administrative')
			$tableName = 'iwp_subactivities';
		else
			$tableName = 'pms_academic_api';
		
		foreach($data as $key=>$value){
			$appraisalData['remarks'] = $value['remarks'];
			$appraisalData['status'] = $value['status'];
			$action = new Update($tableName);
			$action->set($appraisalData);
			$action->where(array('id = ?' => $key));
			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();

		}
		
		return;
	}
	
	/*
	* Save Nature of Activity
	*/
	
	public function saveNatureOfActivity(NatureActivity $activityObject)
	{
		$activityData = $this->hydrator->extract($activityObject);
		unset($activityData['id']);

		if($activityObject->getId()) {
			//ID present, so it is an update
			$action = new Update('pms_nature_activity');
			$action->set($activityData);
			$action->where(array('id = ?' => $activityObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('pms_nature_activity');
			$action->values($activityData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$activityObject->setId($newId);
			}
			return $activityObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Save Academic Weight
	*/
	
	public function saveAcademicWeight(AcademicWeight $academicObject)
	{
		$academicData = $this->hydrator->extract($academicObject);
		unset($academicData['id']);

		if($academicObject->getId()) {
			//ID present, so it is an update
			$action = new Update('pms_academic_weight');
			$action->set($academicData);
			$action->where(array('id = ?' => $academicObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('pms_academic_weight');
			$action->values($academicData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$academicObject->setId($newId);
			}
			return $academicObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Get the details of the employee details
	* This function can be replace by the generic function
	*/
	
	public function getEmployeeDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'));
		$select->where(array('id = ?' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Generic function to get the details given an id and table name
	*/
	
	public function getDetail($tableName, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->where(array('id = ?' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function deleteAppraisal($id)
	{
		$action = new Delete('iwp_subactivities');
		$action->where(array('id = ?' => $id));

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		return (bool)$result->getAffectedRows();
	}
    
	/*
	* Get the appraisal list
	*/
        
	public function getAppraisalList($type, $employee_details_id, $role, $organisation_id)
	{
		$auth_type = "PMS";
		$departments_staff = array();
		$empty = array();
		$sql = new Sql($this->dbAdapter);
		
		//need to get the supervisor UNIT
		$supervisor_unit = $this->getSupervisorUnit($employee_details_id, $role);
		
		$action = $sql->select();
		$action->from(array('t1' => 'user_workflow')) 
						->columns(array('role','role_department','type'))
						->join(array('t2' => 'users'), 
							't1.role = t2.role', array('username'))
						->join(array('t3' => 'employee_details'), 
							't2.username = t3.emp_id', array('id', 'departments_units_id'));
		//$action->where('t1.role_department = ' .$supervisor_department);
		$action->where->like('t1.type','%'.$auth_type.'%');
		$action->where->like('t1.auth','%'.$role.'%');
		$action->where->notEqualTo('t2.role',$role);
		$action->where(array('t3.departments_units_id ' => $supervisor_unit));

		$stmt2 = $sql->prepareStatementForSqlObject($action);
		$result2 = $stmt2->execute();
		$resultSet2 = new ResultSet();
		$resultSet2->initialize($result2);
		
		$departments_staff = array();
		foreach($resultSet2 as $set2){
				$departments_staff[$set2['id']] = $set2['id'];
		}
			
		//old function to get the list of employee for a supervisor
		if(!empty($departments_staff)){
			$select = $sql->select();
			if($type == 'academic'){
				$select->from(array('t1' => 'pms_academic_api')) 
								->columns(array(new Expression('DISTINCT (employee_details_id) as employee_details_id')))
							->join(array('t2' => 'employee_details'), 
											't1.employee_details_id = t2.id', array('id','first_name','middle_name','last_name','emp_id'))
							->join(array('t3' => 'departments'), 
											't2.departments_id = t3.id', array('department_name'))
							->where(array('t1.employee_details_id ' => $departments_staff));
			} else{
				$select->from(array('t1' => 'iwp_subactivities')) 
								->columns(array(new Expression('DISTINCT (employee_details_id) as employee_details_id')))
							->join(array('t2' => 'employee_details'), 
											't1.employee_details_id = t2.id', array('id','first_name','middle_name','last_name','emp_id'))
							->join(array('t3' => 'departments'), 
											't2.departments_id = t3.id', array('department_name'))
							->where(array('t1.employee_details_id ' => $departments_staff));
			}
			
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			return $resultSet->initialize($result);
		}
		return $empty;
	}
	
	/*
	* Get the list of nominations for supervisor approval
	*/
	
	public function getNominationList($table_name, $employee_id)
	{
		$nomination_list = array();
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $table_name))
				->join(array('t2' => 'employee_details'), 
                            't1.nominee = t2.id', array('first_name', 'middle_name' ,'last_name', 'emp_id'));
							//need to join with position title
                   // ->join(array('t3'=>'pms_academic_weight'),
                     //       't2.pms_academic_weight_id = t3.id', array('category'));
		$select->where(array('employee_details_id' =>$employee_id));
		$select->where(array('appraisal_period' =>date('Y')));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$index = 1;
		foreach($resultSet as $set){
			$nomination_list[$index++] = $set;
		}
		return $nomination_list;
	}
	
	/*
	* Update the status of the nomination by the supervisor
	*/
	
	public function updateNominationStatus($data, $employee_id)
	{
		foreach($data as $key=>$value){
			foreach($value as $k=>$v){
				$table_name = $key.'_nomination';
				//need to get the id of the nomination tables
				//ensure that they data retrieved is in the same order as the form displayed in view
				$nomination_list = $this->getNominationList($table_name, $employee_id);
				$index = 0;
				foreach($nomination_list as $nomination){
					$nomination_ids[$index++] = $nomination['id'];
				}
				$appraisalData['id'] = $nomination_ids[$k];
				$appraisalData['status'] = $v;
				$action = new Update($table_name);
				$action->set($appraisalData);
				$action->where(array('id = ?' => $appraisalData['id']));
				
				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();

			}
			
		}		
	}
        
	/*
	 * To submit the IWP Activities to Supervisor
	 */
	
	public function submitIWPActivities($employee_id, $table_name)
	{
		$sql = new Sql($this->dbAdapter);

		$action = new Update($table_name);
		$action->set(array(
							'status' => 'Pending for Approval'
					));
		$action->where(array('employee_details_id' => $employee_id));
		$action->where(array('status' => 'Not Submitted'));
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		return;
	}
	
	/**
	* @return array/Appraisal()
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array('id',$columnName));
		if($organisation_id != NULL)
		{
			$empIds = $this->getSupervisorId($organisation_id);
			if($empIds == NULL){
				$selectData = array();
				return $selectData;
			}
			$select->where(array('employee_details_id' => $empIds));
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
	* Get the ids of Registrars, Presidents and Directors
	*/
	
	public function getSupervisorId($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		if($organisation_id == 1){
			$position_title = array('REGISTRAR', 'PLANNING_DIRECTOR');
			$select->from(array('t1' => 'users')) 
					->columns(array('username'))
                    ->join(array('t2' => 'employee_details'), 
                            't1.username = t2.emp_id', array('id'))
					->where(array('t1.role' => $position_title))
					->order(array('t1.id ASC'));
		} else{
			$position_title = 'PRESIDENT';
			$select->from(array('t1' => 'users')) 
					->columns(array('username'))
                    ->join(array('t2' => 'employee_details'), 
                            't1.username = t2.emp_id', array('id', 'organisation_id'));
					//->where(array('t1.role' => $position_title))
			$select->where->like('t1.role','%'.$position_title);
			$select->where(array('t2.organisation_id' => $organisation_id));
			$select->order(array('t1.id ASC'));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$id_array= array();
		foreach($resultSet as $set){
				$id_array[$set['id']] =$set['id'];
		}
		return $id_array;
	}
	
	/*
	* To get the unit of the various roles such as HOD, PROGRAMME LEADER etc for PMS
	*/
	
	public function getSupervisorUnit($employee_details_id, $role)
	{
		/*
		//old function
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details'))
				->columns(array('departments_units_id'))
				->where(array('t1.id' => $employee_details_id));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$supervisor_unit = NULL;
		foreach($resultSet as $set){
				$supervisor_unit = $set['departments_units_id'];
		}
		*/
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'user_workflow'))
				->columns(array('role_department'));
		$select->where->like('t1.type','%PMS%');
		$select->where->like('t1.auth','%'.$role.'%');
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$supervisor_unit = NULL;
		foreach($resultSet as $set){
				$supervisor_unit[] = $set['role_department'];
		}
		return $supervisor_unit;
	}
        
	/*
	  * Get the ids of the supervisors
	  */
	
	public function getSupervisorForEmployee($employee_details_id)
	{
		//store the list of supervisors in array
		$id = array();
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		//get supervisor role based on employee department
		$supervisor_role = $this->getSupervisorRole($employee_details_id);
		$select->from(array('t1' => 'employee_details'))
				->columns(array('id'))
				->join(array('t2' => 'users'), 
						't1.emp_id = t2.username', array('role'))
				->where(array('role ' => $supervisor_role));
		
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$id[] = $set['id'];
		}
		return $id;
		
	}
	
	/*
	 * For OVC, get the supervisor roles based on the employee details id
	 */
	
	public function getSupervisorRole($employee_details_id)
	{
		//store the list of supervisors role
		$roles = NULL;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'employee_details'))
				->columns(array('id'))
				->join(array('t2' => 'departments'), 
						't1.departments_id = t2.id', array('department_name'))
				->where('t1.id = ' .$employee_details_id);
		
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			if($set['department_name'] == 'Department of Planning and Resources'){
				$roles = 'PLANNING_DIRECTOR';
			} else if($set['department_name'] == 'Department of Planning and Resources'){
				$roles = 'VICE_CHANCELLOR';
			} else if($set['department_name'] == 'Office of the Registrar'){
				$roles = 'REGISTRAR';
			} else if($set['department_name'] == 'Department of Academic Affairs'){
				$roles = 'ACADEMIC_DIRECTOR';
			} if($set['department_name'] == 'Department of Research and External Affairs'){
				$roles = 'RESEARCH_DIRECTOR';
			}
		}
		
		//if role is null, then it is a college. Get the President Role
		if($roles == NULL){
			$sql2 = new Sql($this->dbAdapter);
			$select2 = $sql2->select();

			$select2->from(array('t1' => 'employee_details'))
					->columns(array('id', 'organisation_id'))
					->join(array('t2' => 'user_role'), 
							't1.organisation_id = t2.organisation_id', array('rolename'))
					->where('t1.id = ' .$employee_details_id);
			$select2->where->like('t2.rolename','%PRESIDENT%');


			$stmt2 = $sql2->prepareStatementForSqlObject($select2);
			$result2 = $stmt2->execute();

			$resultSet2 = new ResultSet();
			$resultSet2->initialize($result2);
			foreach($resultSet2 as $set2){
				$roles = $set2['rolename'];
			}
		}
		
		return $roles;
	}
	
	private function getAppraisalPeriod()
	{
		$appraisal_period = NULL;
		if(date('m') < 6){
			$appraisal_period = (date('Y')-1)."-".(date('Y'));
		 } else {
			 $appraisal_period = (date('Y'))."-".(date('Y')+1);
		 }
		 return $appraisal_period;
	}
        
}
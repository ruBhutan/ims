<?php

namespace HrmPlan\Mapper;

use HrmPlan\Model\HrmPlan;
use HrmPlan\Model\HrmPlanApproval;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements HrmPlanMapperInterface
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
	 * @var \EmpWorkForceProposal\Model\EmpWorkForceProposalInterface
	*/
	protected $hrmPlanPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			HrmPlan $hrmPlanPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->hrmPlanPrototype = $hrmPlanPrototype;
	}
	
	/**
	* @param int/String $id
	* @return EmpWorkForceProposal
	* @throws \InvalidArgumentException
	*/
	
	public function find($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'hr_management'))
				->join(array('t2' => 'position_category'), 
                            't1.position_category_id = t2.id', array('category'))
				->join(array('t3'=>'position_title'),
						't1.position_title_id = t3.id', array('position_title'))
				->join(array('t4'=>'organisation'),
						't1.working_agency = t4.id', array('organisation_name'))
				->join(array('t5'=>'departments'),
						't1.department_name = t5.id', array('department_name'))
				->join(array('t6'=>'position_level'),
						't1.position_level_id = t6.id', array('position_level'));
		$select->where(array('t1.id = ? ' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
				return $this->hydrator->hydrate($result->current(), $this->hrmPlanPrototype);
		}

		throw new \InvalidArgumentException("HRM with given ID: ($id) not found");
	}
	
	/**
	* @return array/EmpWorkForceProposal()
	*/
	public function findAll($status, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'hr_management'))
				->join(array('t2' => 'position_category'), 
                            't1.position_category_id = t2.id', array('category'))
				->join(array('t3'=>'position_title'),
						't1.position_title_id = t3.id', array('position_title'))
				->join(array('t4'=>'organisation'),
						't1.working_agency = t4.id', array('organisation_name'))
				->join(array('t5'=>'departments'),
						't1.department_name = t5.id', array('department_name'))
				->join(array('t6'=>'position_level'),
						't1.position_level_id = t6.id', array('position_level'));
		$select->where(array('proposal_status = ? ' => $status));
		if($organisation_id != NULL && $organisation_id != 1){
			$select->where(array('working_agency' => $organisation_id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if ($result instanceof ResultInterface && $result->isQueryResult()) {

				$resultSet = new ResultSet();
				$resultSet->initialize($result);

				$resultSet = new HydratingResultSet($this->hydrator, $this->hrmPlanPrototype);
				return $resultSet->initialize($result); 
		}

		return array();
	}
        
	/**
	 * 
	 * @param type $id
	 * 
	 * to find the HRD Proposal for a given $id
	 */
	public function findDetails($id) 
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'hr_management'));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if ($result instanceof ResultInterface && $result->isQueryResult()) {
			$resultSet = new ResultSet();
			$resultSet->initialize($result);

			$resultSet = new HydratingResultSet($this->hydrator, $this->hrmPlanPrototype);
			$resultSet->buffer();
			return $resultSet->initialize($result); 
		}
		
		return array();
	}
		
	/**
	 * 
	 * @param type $EmpWorkForceProposalInterface
	 * 
	 * to save work force proposals
	 */
	
	public function saveDetails(HrmPlan $hrmPlanObject, $data)
	{
		$hrmPlanData = $this->hydrator->extract($hrmPlanObject);
		unset($hrmPlanData['id']);
		//getting the data from ajax form
		$hrmPlanData['position_Title_Id'] = $data['position_title_id'];
		$hrmPlanData['position_Level_Id'] = $data['position_level_id'];
		$hrmPlanData['position_Category_Id'] = $data['position_category_id'];
		/*$hrmPlanData['position_Category_Id']= $emp_position_category;
		$hrmPlanData['position_Title_Id'] = $emp_position_title;
		$hrmPlanData['position_Level_Id'] = $emp_position_level;*/
		
		//unsetting as these values are used for displaying
		unset($hrmPlanData['organisation_Name']);
		unset($hrmPlanData['position_Title']);
		unset($hrmPlanData['position_Level']);
		unset($hrmPlanData['category']);
		
		if($hrmPlanObject->getId()) {
			//ID present, so it is an update
			$action = new Update('hr_management');
			$action->set($hrmPlanData);
			$action->where(array('id = ?' => $hrmPlanObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('hr_management');
			$action->values($hrmPlanData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $hrmPlanObject->setId($newId);
			}
			return $hrmPlanObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/**
	 * 
	 * @param type $EmpWorkForceProposalInterface
	 * 
	 * to save work force proposals
	 */
	
	public function updateProposal(HrmPlanApproval $hrmPlanObject, $submitValue)
	{
		$hrmPlanData = $this->hydrator->extract($hrmPlanObject);
		unset($hrmPlanData['id']);
				
		$hrmPlanData['proposal_Status'] = $submitValue;
		
		//ID present, so it is an update
		$action = new Update('hr_management');
		$action->set($hrmPlanData);
		$action->where(array('id = ?' => $hrmPlanObject->getId()));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $hrmPlanObject->setId($newId);
			}
			return $hrmPlanObject;
		}
		
		throw new \Exception("Database Error");
	}

    /*
	* The following function to change the status from "not submitted to pending"
	*/
	public function updateHrmProposal($status, $previousStatus, $id, $organisation_id)
	{
		$hrdData['proposal_Status'] = $status;
		$action = new Update('hr_management');
		$action->set($hrdData);
		if($previousStatus != NULL){
			$action->where(array('proposal_status = ?' => $previousStatus, 'working_agency' => $organisation_id));
		} elseif($id != NULL){
			$action->where(array('id = ?' => $id));
		}
			
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		return;
	}
	
	/*
	* Delete HRM Proposal by College HRO before submission to OVC
	*/
	
	public function deleteHrmProposal($id)
	{
		$action = new Delete('hr_management');
		$action->where(array('id = ?' => $id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		return (bool)$result->getAffectedRows();
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
	* Get the employee details
	*/
	
	public function getEmployeeDetails($empId)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details'))
				->where('t1.id = ' .$empId);
		$select->columns(array('id','first_name','middle_name','last_name'));
		
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
	* Get Five Year Plan
	*/
	
	public function getFiveYearPlan()
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'five_year_plan'));
		$select->columns(array('five_year_plan'));
		$select->where(array('from_date <= ? ' => date('Y-m-d'), 'to_date >= ? ' => date('Y-m-d')));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$five_year_plan = NULL;
		foreach($resultSet as $set){
			$five_year_plan = $set['five_year_plan'];
		}
		return $five_year_plan;
	}
	
	/*
	* Get the dates for the proposal, i.e. whether the proposal is active or not
	*/
	
	public function getProposalDates($proposal_type)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'hr_activation_date'))
				->join(array('t2' => 'five_year_plan'), 
                            't1.five_year_plan = t2.five_year_plan', array('five_year_plan'));
		$select->where(array('t2.from_date <= ? ' => date('Y-m-d'), 't2.to_date >= ? ' => date('Y-m-d')));
		$select->where(array('t1.hr_proposal_type' => $proposal_type));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Return an id for the departments and units given the name
	* this is done as the ajax returns a value and not the id
	*/
	
	public function getAjaxDataId($tableName, $column_name)
	{
		//note: $department_name takes various parameters. Check calling function 
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => $tableName))
				->columns(array('id'));
		if($tableName=='position_title')
			$select->where->like('t1.position_title','%'.$column_name.'%');
		else if($tableName == 'position_level')
			$select->where->like('t1.position_level','%'.$column_name.'%');
		else
			$select->where->like('t1.category','%'.$column_name.'%');
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$id = NULL;
		
		foreach($resultSet as $set){
			$id = $set['id'];
		}
		return $id;
	}
    
	/**
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => $tableName));
		if($organisation_id != NULL){
			$select->columns(array('id',$columnName))
				->where('t1.organisation_id = ' .$organisation_id);
		}
		else {
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
}
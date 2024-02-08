<?php

namespace HrdPlan\Mapper;

use HrdPlan\Model\HrdPlan;
use HrdPlan\Model\HrdPlanApproval;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements HrdPlanMapperInterface
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
	protected $hrdPlanPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			HrdPlan $hrdPlanPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->hrdPlanPrototype = $hrdPlanPrototype;
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

		$select->from(array('t1' => 'hr_development'))
			   ->join(array('t2' => 'funding_category'),
					't2.id = t1.source_of_funding', array('funding_type'))
			   ->join(array('t3' => 'training_types'),
					't3.id = t1.training_type', array('training_type'));
		$select->where(array('t1.id = ? ' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();


		if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
				return $this->hydrator->hydrate($result->current(), $this->hrdPlanPrototype);
		}

		throw new \InvalidArgumentException("HRD Proposal with given ID: ($id) not found");
	}
	
	/**
	* @return array/EmpWorkForceProposal()
	*/
	public function findAll($status, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'hr_development'))
			   ->join(array('t2' => 'funding_category'),
					't2.id = t1.source_of_funding', array('funding_type'))
			   ->join(array('t3' => 'training_types'),
					't3.id = t1.training_type', array('training_type'));
		$select->where(array('approval_status = ? ' => $status));
		if($organisation_id != NULL){
			$select->where(array('working_agency' => $organisation_id));
		}
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if ($result instanceof ResultInterface && $result->isQueryResult()) {
			$resultSet = new ResultSet();
			$resultSet->initialize($result);

			$resultSet = new HydratingResultSet($this->hydrator, $this->hrdPlanPrototype);
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
		$select->from(array('t1' => 'hr_development'));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if ($result instanceof ResultInterface && $result->isQueryResult()) {

				$resultSet = new ResultSet();
				$resultSet->initialize($result);

				$resultSet = new HydratingResultSet($this->hydrator, $this->hrdPlanPrototype);
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
	
	public function saveDetails(HrdPlan $hrdPlanObject)
	{
		$hrdPlanData = $this->hydrator->extract($hrdPlanObject);
		unset($hrdPlanData['id']);
		unset($hrdPlanData['funding_Type']);
                $hrdPlanData['total_No_Slots'] = $hrdPlanData['amount_Year_1']+$hrdPlanData['amount_Year_2']+$hrdPlanData['amount_Year_3']+$hrdPlanData['amount_Year_4']+$hrdPlanData['amount_Year_5'];

		if($hrdPlanObject->getId()) {
			//ID present, so it is an update
			$action = new Update('hr_development');
			$action->set($hrdPlanData);
			$action->where(array('id = ?' => $hrdPlanObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('hr_development');
			$action->values($hrdPlanData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $hrdPlanObject->setId($newId);
			}
			return $hrdPlanObject;
		}
		
		throw new \Exception("Database Error");
	}

	
	/**
	 * 
	 * @param type $EmpWorkForceProposalInterface
	 * 
	 * to save work force proposals
	 */
	
	public function updateProposal(HrdPlanApproval $hrdPlanObject, $submitValue)
	{
		$hrdPlanData = $this->hydrator->extract($hrdPlanObject);
		unset($hrdPlanData['id']);
		
		$hrdPlanData['approval_Status'] = $submitValue;
		
		//ID present, so it is an update
		$action = new Update('hr_development');
		$action->set($hrdPlanData);
		$action->where(array('id = ?' => $hrdPlanObject->getId()));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $hrdPlanObject->setId($newId);
			}
			return $hrdPlanObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* The following function to change the status from "not submitted to pending"
	* Collegs submit HR Proposal to OVC
	*/
	public function updateHrdProposal($status, $previousStatus, $id, $organisation_id)
	{
		$hrdData['approval_status'] = $status;
		$action = new Update('hr_development');
		$action->set($hrdData);
		if($previousStatus != NULL){
			$action->where(array('approval_status = ?' => $previousStatus, 'working_agency' => $organisation_id));
		} elseif($id != NULL){
			$action->where(array('id = ?' => $id));
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		return;
	}
	
	/*
	* Delete HR Proposal by College HRO before submission to OVC
	*/
	
	public function deleteProposal($id)
	{
		$action = new Delete('hr_development');
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
		foreach($resultSet as $set)
		{
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
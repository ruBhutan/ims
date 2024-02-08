<?php

namespace Budgeting\Mapper;

use Budgeting\Model\BudgetProposal;
use Budgeting\Model\CapitalBudgetProposal;
use Budgeting\Model\BudgetLedger;
use Budgeting\Model\BudgetReappropriationSelect;
use Budgeting\Model\BudgetReappropriation;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements BudgetingMapperInterface
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
	 * @var \Budgeting\Model\BudgetingInterface
	*/
	protected $budgetingPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			\stdClass $budgetingPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->budgetingPrototype = $budgetingPrototype;
	}
	
	
	/**
	* @return array/Budgeting()
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
	* Get organisation id based on the username
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
	
	/**
	* @return array/Budgeting()
	*/
	public function listBudgetLedger($tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => $tableName)) 
				->join(array('t2' => 'departments'), 
						't1.departments_id = t2.id', array('department_name'))
				->join(array('t3'=>'organisation'),
						't2.organisation_id = t3.id', array('organisation_name'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

	}
	
	/*
	* Find the budget ledger given an id
	*/
	
	public function findBudgetLedger($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'budget_ledger_head'));
		$select->where(array('id' =>$id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

	}
	
	/*
	* Find the Proposal Details
	* This will take Table Name and id as the argument
	* should work for all types of Proposals, eg Current etc
	*/
	
	public function findProposalDetail($tableName, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => $tableName));
		$select->where(array('id' =>$id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	
	/**
	* @return array/Budgeting()
	*/
	public function listBudgetProposal($tableName, $status)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		if($tableName == 'budget_proposal'){
			$select->from(array('t1' => $tableName)) 
					->join(array('t2' => 'budget_ledger_head'), 
							't1.budget_ledger_head_id = t2.id', array('ledger_head'))
					->join(array('t3'=>'chart_of_accounts'),
							't1.chart_of_accounts_id = t3.id', array('account_code'))
					->join(array('t4'=>'accounts_group_head'),
							't1.accounts_group_head_id = t4.id', array('group_head'))
					->where(array('t1.budget_proposal_status = ? ' => $status));
		}
		else {
			$select->from(array('t1' => $tableName)) 
					->join(array('t2'=>'object_code'),
							't1.object_code_id = t2.id', array('object_name'))
					->join(array('t3'=>'broad_head_name'),
							't1.broad_head_name_id = t3.id', array('broad_head_name'))
					->where(array('t1.budget_proposal_status = ? ' => $status));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

	}
	
	public function updateBudgetProposal($status, $previousStatus)
	{
		//need to get the organisaiton id
		$organisation_id = 1;
		$budgetingData['budget_proposal_status'] = $status;
		$action = new Update('budget_proposal');
		$action->set($budgetingData);
		$action->where(array('budget_proposal_status = ?' => $previousStatus));
			
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
	}
        
	/**
	 * 
	 * @param type $BudgetingInterface
	 * 
	 * to save Objectives Details
	 */
	
	public function saveBudgetLedger(BudgetLedger $budgetingObject)
	{
		$budgetingData = $this->hydrator->extract($budgetingObject);
		unset($budgetingData['id']);

		
		if($budgetingObject->getId()) {
			//ID present, so it is an update
			$action = new Update('budget_ledger_head');
			$action->set($budgetingData);
			$action->where(array('id = ?' => $budgetingObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('budget_ledger_head');
			$action->values($budgetingData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $budgetingObject->setId($newId);
			}
			return $budgetingObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	
	/**
	 * 
	 * @param type $BudgetingInterface
	 * 
	 * to save Activities Details
	 */
	
	public function saveBudgetProposal(BudgetProposal $budgetingObject, $chart_of_accounts_id, $accounts_group_head_id)
	{
		$budgetingData = $this->hydrator->extract($budgetingObject);
		unset($budgetingData['id']);
		
		$budgetingData['accounts_Group_Head_Id'] = $accounts_group_head_id;
		
		//get the id of the chart of accounts
		$budgetingData['chart_Of_Accounts_Id'] = $this->getAjaxDataId($tableName='chart_of_accounts', $chart_of_accounts_id);
		
		//need to change this only for approval
		$budgetingData['balance'] = $budgetingData['proposed_Budget_Amount'];
		
		if($budgetingObject->getId()) {
			//ID present, so it is an update
			$action = new Update('budget_proposal');
			$action->set($budgetingData);
			$action->where(array('id = ?' => $budgetingObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('budget_proposal');
			$action->values($budgetingData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $budgetingObject->setId($newId);
			}
			return $budgetingObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/**
	 * 
	 * @param type $BudgetingInterface
	 * 
	 * to save Activities Details
	 */
	
	public function saveCapitalBudgetProposal(CapitalBudgetProposal $budgetingObject, $broad_head_name_id, $object_code_id)
	{
		$budgetingData = $this->hydrator->extract($budgetingObject);
		
		//saving the activity name and not the activity id
		if($budgetingData['activity_Id'] != 0)
		{
			 $activityName = $this->findActivity($budgetingData['activity_Id']);
			 foreach($activityName as $activity){
				 $budgetingData['activity_Name'] = $activity['activity_name'];
			 }
		}
		
		unset($budgetingData['id']);
		unset($budgetingData['activity_Id']);
		//need to change this only for approval
		$budgetingData['balance'] = $budgetingData['proposed_Budget_Amount'];
		
		$budgetingData['broad_Head_Name_Id'] = $broad_head_name_id;
		
		//get the id of the chart of accounts
		$budgetingData['object_Code_Id'] = $this->getAjaxDataId($tableName='object_code', $object_code_id);
		
		
		if($budgetingObject->getId()) {
			//ID present, so it is an update
			$action = new Update('budget_proposal_capital');
			$action->set($budgetingData);
			$action->where(array('id = ?' => $budgetingObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('budget_proposal_capital');
			$action->values($budgetingData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $budgetingObject->setId($newId);
			}
			return $budgetingObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Get To Reappropriation Details
	*/
	
	public function reappropriationDetails(BudgetReappropriationSelect $budgetingObject, $type, $data)
	{
		var_dump($budgetingObject);
		die();
		if($type == 'to')
		{
			$budgetingData = $this->hydrator->extract($budgetingObject);
			$account_group_head = $budgetingData['to_Accounts_Group_Head_Id'];
			$budget_ledger_head = $budgetingData['to_Budget_Ledger_Head_Id'];
		}
		else
		{
			$budgetingData = $this->hydrator->extract($budgetingObject);
			$account_group_head = $budgetingData['from_Accounts_Group_Head_Id'];
			$budget_ledger_head = $budgetingData['from_Budget_Ledger_Head_Id'];
		}
		
		
		$organisation = $budgetingData['organisation_Id'];
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		//if $id is not null, we only want to return the ids
		if($data == 'id') {
			$select->from(array('t1' => 'budget_proposal'))
				->columns(array('id'))
				->where(array('t1.budget_ledger_head_id = ? ' => $budget_ledger_head, 't1.accounts_group_head_id = ?' => $account_group_head, 't1.organisation_id = ?' => $organisation )); 
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet = $resultSet->initialize($result);
			$idToReturn = array();
			foreach($resultSet as $key => $value)
			 {
				 foreach($value as $key1 => $value1)
				 {
					 array_push($idToReturn, $value1);
				 }
			 }
			 return $idToReturn;
		}
		else {
			$select->from(array('t1' => 'budget_proposal')) 
				->where(array('t1.budget_ledger_head_id = ? ' => $budget_ledger_head, 't1.accounts_group_head_id = ?' => $account_group_head, 't1.organisation_id = ?' => $organisation,
							't1.budget_proposal_status' => 'Approved' ));
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			return $resultSet->initialize($result);
		}

	}
	
	/*
	* Get From Reappropriation Details
 	*/
	
	public function fromReappropriationDetails(BudgetReappropriationSelect $budgetingObject)
	{
		$budgetingData = $this->hydrator->extract($budgetingObject);
		$account_group_head = $budgetingData['from_Accounts_Group_Head_Id'];
		$budget_ledger_head = $budgetingData['from_Budget_Ledger_Head_Id'];
		$organisation = $budgetingData['organisation_Id'];
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'budget_proposal')) 
				->where(array('t1.budget_ledger_head_id = ? ' => $budget_ledger_head, 't1.accounts_group_head_id = ?' => $account_group_head, 't1.organisation_id = ?' => $organisation )); 

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

	}
	/*
	* Adding budget reappropriation
	*/
	
	public function addBudgetReappropriation(BudgetReappropriation $budgetingObject, $toData, $fromData, $toId, $fromId)
	{
		$budgetingData = $this->hydrator->extract($budgetingObject);
		$budgetingData['to_Amount'] = $toData;
		$budgetingData['from_Amount'] =$fromData;
		$budgetingData['to_Proposal_Id'] = $toId;
		$budgetingData['from_Proposal_Id'] = $fromId;

		$action = new Insert('budget_reappropriation');
		$action->values($budgetingData);
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $budgetingObject->setId($newId);
			}
			return $budgetingObject;
		}
		
		throw new \Exception("Database Error");
		
	}
	
	public function listBudgetReappropriation($columnName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'budget_reappropriation')) 
				->join(array('t2' => 'budget_proposal'), 
						't2.id = '.$columnName);

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

	}
	
	/*
	* Get the Activity Name. We are storing the Activity Name and not the id
	*
	*/
	
	public function findActivity($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'awpa_objectives_activity'));
		$select->columns(array('activity_name'));
		$select->where(array('id = ?' => $id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Return an id for the chart of accounts given the account code
	* this is done as the ajax returns a value and not the id
	*/
	
	public function getAjaxDataId($tableName, $code)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array('id'));
		if($tableName == 'chart_of_accounts'){
			$select->where(array('account_code = ?' => $code));
		}
		else if($tableName == 'object_code'){
			$select->where(array('object_name = ?' => $code));
		}
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$id = NULL;
		
		foreach($resultSet as $set)
		{
			$id = $set['id'];
		}
		return $id;
	}
	
	/**
	* @return array/Budgeting()
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName, $condition)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array('id',$columnName)); 
		if($condition != NULL)
		{
			$select->where(array('organisation_id = ?' => $condition));
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
		//this if for Activities for AWPA Activities
		if($tableName == 'awpa_objectives_activity')
		{
			$selectData[0] = 'Others';
		}
		return $selectData;
			
	}
        
}
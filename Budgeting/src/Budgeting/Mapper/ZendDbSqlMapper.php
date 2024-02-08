<?php

namespace Budgeting\Mapper;

use Budgeting\Model\BudgetProposal;
use Budgeting\Model\CapitalBudgetProposal;
use Budgeting\Model\BudgetLedger;
use Budgeting\Model\BudgetReappropriationSelect;
use Budgeting\Model\BudgetReappropriation;
use Budgeting\Model\CapitalBudgetReappropriationSelect;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;
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

		if($tableName == 'five_year_plan'){
			$select->from(array('t1' => $tableName));
			$select->where(array('from_date <= ? ' => date('Y-m-d'), 'to_date >= ? ' => date('Y-m-d')));
		}else{
		$select->from(array('t1' => $tableName)); // join expression
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		 return $resultSet->initialize($result);
	}
	
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username, $usertype)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($usertype == 1){
			$select->from(array('t1' => 'employee_details'));
			$select->where(array('emp_id' =>$username));
			$select->columns(array('id', 'first_name', 'middle_name', 'last_name', 'organisation_id', 'departments_id', 'departments_units_id', 'profile_picture'));
		}
			
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
		
		if($tableName == 'budget_proposal'){
			$select->from(array('t1' => $tableName)) 
					->join(array('t2' => 'budget_ledger_head'), 
							't1.budget_ledger_head_id = t2.id', array('ledger_head'))
					->join(array('t3'=>'chart_of_accounts'),
							't1.chart_of_accounts_id = t3.id', array('head_of_accounts','account_code'))
					->join(array('t4'=>'accounts_group_head'),
							't1.accounts_group_head_id = t4.id', array('group_head'))
					->where(array('t1.id = ? ' => $id));
		}
		else {
			$select->from(array('t1' => $tableName)) 
					->join(array('t2'=>'object_code'),
							't1.object_code_id = t2.id', array('object_name'))
					->join(array('t3'=>'broad_head_name'),
							't1.broad_head_name_id = t3.id', array('broad_head_name'))
					->where(array('t1.id = ? ' => $id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	
	/**
	* @return array/Budgeting()
	*/
	public function listBudgetProposal($tableName, $status, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		if($tableName == 'budget_proposal'){
			if($status == 'Approved' || $status == 'Submitted to OVC'){
				$select->from(array('t1' => $tableName)) 
					->join(array('t2' => 'budget_ledger_head'), 
							't1.budget_ledger_head_id = t2.id', array('ledger_head'))
					->join(array('t3'=>'chart_of_accounts'),
							't1.chart_of_accounts_id = t3.id', array('head_of_accounts','account_code'))
					->join(array('t4'=>'accounts_group_head'),
							't1.accounts_group_head_id = t4.id', array('group_head'))
					->join(array('t5' => 'organisation'),
							't5.id = t1.organisation_id', array('organisation_name'))
					->where(array('t1.budget_proposal_status = ? ' => $status));
			}else{
				$select->from(array('t1' => $tableName)) 
					->join(array('t2' => 'budget_ledger_head'), 
							't1.budget_ledger_head_id = t2.id', array('ledger_head'))
					->join(array('t3'=>'chart_of_accounts'),
							't1.chart_of_accounts_id = t3.id', array('head_of_accounts','account_code'))
					->join(array('t4'=>'accounts_group_head'),
							't1.accounts_group_head_id = t4.id', array('group_head'))
					->where(array('t1.budget_proposal_status = ? ' => $status, 't1.organisation_id' => $organisation_id));
			}
		}
		else {
			if($status == 'Approved' || $status == 'Submitted to OVC'){
				$select->from(array('t1' => $tableName)) 
					->join(array('t2'=>'object_code'),
							't1.object_code_id = t2.id', array('object_name'))
					->join(array('t3'=>'broad_head_name'),
							't1.broad_head_name_id = t3.id', array('broad_head_name'))
					->where(array('t1.budget_proposal_status = ? ' => $status));
			}else{
				$select->from(array('t1' => $tableName)) 
					->join(array('t2'=>'object_code'),
							't1.object_code_id = t2.id', array('object_name'))
					->join(array('t3'=>'broad_head_name'),
							't1.broad_head_name_id = t3.id', array('broad_head_name'))
					->where(array('t1.budget_proposal_status = ? ' => $status, 't1.organisation_id' => $organisation_id));
			}
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

	}
	
	public function updateBudgetProposal($tableName, $status, $previousStatus, $organisation_id)
	{ 
		if($tableName == 'budget_proposal'){
			$budgetingData['budget_proposal_status'] = $status;
			if($budgetingData['budget_proposal_status']== 'Submitted to OVC'){
				$action = new Update('budget_proposal');
				$action->set($budgetingData);
				$action->where(array('budget_proposal_status' => $previousStatus, 'organisation_id' => $organisation_id));

				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
			}else{
				$budgeting_data_array = $this->listBudgetProposal($tableName = 'budget_proposal', $status = 'Submitted to OVC', NULL);

				foreach($budgeting_data_array as $key => $value){
					if($value['budget_amount_approved'] == NULL){
						$budgetingData['budget_Amount_Approved'] = $value['proposed_budget_amount'];
					}else{
						$budgetingData['budget_Amount_Approved'] = $value['budget_amount_approved'];
					}

					$action = new Update('budget_proposal');
					$action->set($budgetingData);
					$action->where(array('id' => $value['id']));

					$sql = new Sql($this->dbAdapter);
					$stmt = $sql->prepareStatementForSqlObject($action);
					$result = $stmt->execute();
				}
			}
		}else{ 
			$budgetingData['budget_proposal_status'] = $status; 
			if($budgetingData['budget_proposal_status']== 'Submitted to OVC'){
				$action = new Update('budget_proposal_capital');
				$action->set($budgetingData);
				$action->where(array('budget_proposal_status' => $previousStatus, 'organisation_id' => $organisation_id));

				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
			}else{
				$budgeting_data_array = $this->listBudgetProposal($tableName = 'budget_proposal_capital', $status = 'Submitted to OVC', NULL);

				foreach($budgeting_data_array as $key => $value){

					if($value['budget_amount_approved'] == NULL){
						$budgetingData['budget_Amount_Approved'] = $value['proposed_budget_amount'];
					}else{
						$budgetingData['budget_Amount_Approved'] = $value['budget_amount_approved'];
					}

					$action = new Update('budget_proposal_capital');
					$action->set($budgetingData);
					$action->where(array('id' => $value['id']));

					$sql = new Sql($this->dbAdapter);
					$stmt = $sql->prepareStatementForSqlObject($action);
					$result = $stmt->execute();
				}
			}
		}
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
	
	public function saveBudgetProposal(BudgetProposal $budgetingObject, $chart_of_accounts_id, $accounts_group_head_id, $role_type)
	{
		$budgetingData = $this->hydrator->extract($budgetingObject);
		unset($budgetingData['id']);
		
		$budgetingData['accounts_Group_Head_Id'] = $accounts_group_head_id;
		
		//get the id of the chart of accounts
		$budgetingData['chart_Of_Accounts_Id'] = $chart_of_accounts_id;
		
		//need to change this only for approval
		$budgetingData['balance'] = $budgetingData['proposed_Budget_Amount']; 
		
		if($budgetingObject->getId()) {
			if($role_type == 'RUB'){
				$budgetingData['budget_Amount_Approved'] = $budgetingData['proposed_Budget_Amount'];
				$budgetingData['proposed_Budget_Amount'] = $this->getPreviousProposedAmount($budgetingObject->getId(), 'budget_proposal');
				$budgetingData['budget_proposal_status'] = 'Submitted to OVC';
			}else{
				$budgetingData['proposed_Budget_Amount'] = $this->getPreviousProposedAmount($budgetingObject->getId(), 'budget_proposal');
			} 

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


	//Function to delete current budget proposal
	public function deleteCurrentBudgetProposal($id)
	{
        //Delete Budget Proposal
        $action = new Delete('budget_proposal');
        $action->where(array('id = ?' => $id));
        
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
        return (bool)$result->getAffectedRows();
	}
	
	/**
	 * 
	 * @param type $BudgetingInterface
	 * 
	 * to save Activities Details
	 */
	
	public function saveCapitalBudgetProposal(CapitalBudgetProposal $budgetingObject, $broad_head_name_id, $object_code_id, $role_type)
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
		$budgetingData['object_Code_Id'] = $object_code_id;
		 
		//var_dump($budgetingData); die();
		if($budgetingObject->getId()) { 
			if($role_type == 'RUB'){
				$budgetingData['budget_Amount_Approved'] = $budgetingData['proposed_Budget_Amount'];
				$budgetingData['proposed_Budget_Amount'] = $this->getPreviousProposedAmount($budgetingObject->getId(), 'budget_proposal_capital');
				$budgetingData['budget_proposal_status'] = 'Submitted to OVC';
			}else{
				$budgetingData['proposed_Budget_Amount'] = $this->getPreviousProposedAmount($budgetingObject->getId(), 'budget_proposal_capital');
			} 
			
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


	public function getPreviousProposedAmount($id, $tableName)
	{ 
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'budget_proposal_capital'){
			$select->from(array('t1' => $tableName)) 
				   ->columns(array('proposed_budget_amount'))
				   ->where(array('t1.id' => $id)); 
		}else{
			$select->from(array('t1' => $tableName)) 
				   ->columns(array('proposed_budget_amount'))
				   ->where(array('t1.id' => $id)); 
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$proposed_budget_amount = NULL;
		foreach($resultSet as $set){
			$proposed_budget_amount = $set['proposed_budget_amount'];
		}
		return $proposed_budget_amount;
	}


	public function deleteCapitalBudgetProposal($id)
	{
		//Delete Budget Proposal
        $action = new Delete('budget_proposal_capital');
        $action->where(array('id = ?' => $id));
        
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
        return (bool)$result->getAffectedRows();
	}


	/*
	*Get from and to proposal id from budget reappropriation table
	*/
	public function getBudgetReappropriationDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'budget_reappropriation')) 
				->where(array('t1.id' => $id)); 

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get To Reappropriation Details
	*/
	
	public function reappropriationDetails($tableName, $data)
	{ 
		//first extract the $key=> $value from $data as the $key are the names of the columns
		$columnNames = array();
		$dataValues = array();
		foreach($data as $key => $value)
		{
			array_push($columnNames, $key);
			array_push($dataValues, $value);
		} 
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		if($tableName == 'budget_proposal_capital'){ 
			//$dataValues[1] = $this->getAjaxDataId($table = 'broad_head_name', $column = 'broad_head_name', $code = $dataValues['1']);
			//$dataValues[2] = $this->getAjaxDataId($table = 'object_code', $column = 'object_name', $code = $dataValues['2']);
			$select->from(array('t1' => $tableName)) 
                    ->join(array('t2' => 'object_code'), 
                            't1.object_code_id = t2.id', array('object_name'))
                    ->join(array('t3'=>'broad_head_name'),
                            't2.broad_head_name_id = t3.id', array('broad_head_name'))
                    ->where(array('t1.id' => $dataValues[0]));
		}
		else {
			$select->from(array('t1' => $tableName)) 
                    ->join(array('t2' => 'chart_of_accounts'), 
                            't1.chart_of_accounts_id = t2.id', array('account_code','head_of_accounts'))
                    ->join(array('t3'=>'accounts_group_head'),
							't2.accounts_group_head_id = t3.id', array('group_head'))
					->join(array('t4' => 'budget_ledger_head'),
							't4.id = t1.budget_ledger_head_id', array('ledger_head'))
                    ->where(array('t1.id' => $dataValues[0]));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);	
	}


	public function getBudgetReappropriationDetailsList($tableName, $type, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'budget_proposal_capital' && $type == 'to'){
			$select->from(array('t1' => 'budget_reappropriation')) 
					->join(array('t2' => $tableName), 
								't2.id = t1.to_proposal_id', array('activity_name', 'budget_amount_approved', 'balance'))
					->join(array('t3' => 'object_code'), 
								't2.object_code_id = t3.id', array('object_name'))
					->join(array('t4'=>'broad_head_name'),
								't2.broad_head_name_id = t4.id', array('broad_head_name'));
			$select->where(array('t1.id' => $id));
		}
		else if($tableName == 'budget_proposal_capital' && $type == 'from'){
			$select->from(array('t1' => 'budget_reappropriation')) 
					->join(array('t2' => $tableName), 
								't2.id = t1.from_proposal_id', array('activity_name', 'budget_amount_approved', 'balance'))
					->join(array('t3' => 'object_code'), 
								't2.object_code_id = t3.id', array('object_name'))
					->join(array('t4'=>'broad_head_name'),
								't2.broad_head_name_id = t4.id', array('broad_head_name'));
			$select->where(array('t1.id' => $id));
		}
		else if($tableName == 'budget_proposal' && $type == 'to')
		{
			$select->from(array('t1' => 'budget_reappropriation'))
					->join(array('t2' => $tableName),
							't2.id = t1.to_proposal_id', array('budget_amount_approved', 'balance')) 
					->join(array('t3' => 'budget_ledger_head'), 
							't2.budget_ledger_head_id = t3.id', array('ledger_head'))
					->join(array('t4'=>'chart_of_accounts'),
							't2.chart_of_accounts_id = t4.id', array('head_of_accounts','account_code'))
					->join(array('t5'=>'accounts_group_head'),
							't2.accounts_group_head_id = t5.id', array('group_head'))
					->join(array('t6' => 'organisation'),
							't6.id = t1.organisation_id', array('organisation_name'));
			$select->where(array('t1.id' => $id));
		}
		else if($tableName == 'budget_proposal' && $type == 'from')
		{
			$select->from(array('t1' => 'budget_reappropriation'))
					->join(array('t2' => $tableName),
							't2.id = t1.from_proposal_id', array('budget_amount_approved', 'balance')) 
					->join(array('t3' => 'budget_ledger_head'), 
							't2.budget_ledger_head_id = t3.id', array('ledger_head'))
					->join(array('t4'=>'chart_of_accounts'),
							't2.chart_of_accounts_id = t4.id', array('head_of_accounts','account_code'))
					->join(array('t5'=>'accounts_group_head'),
							't2.accounts_group_head_id = t5.id', array('group_head'))
					->join(array('t6' => 'organisation'),
							't6.id = t1.organisation_id', array('organisation_name'));
			$select->where(array('t1.id' => $id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);	
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
	
	public function addBudgetReappropriation(BudgetReappropriationSelect $budgetingObject, $toData, $fromData)
	{ 
		$budgetingData = $this->hydrator->extract($budgetingObject);
		unset($budgetingData['id']);
		unset($budgetingData['from_Budget_Ledger_Head_Id']);
		unset($budgetingData['from_Accounts_Group_Head_Id']);
		unset($budgetingData['from_Chart_of_Accounts_Id']);
		unset($budgetingData['to_Budget_Ledger_Head_Id']);
		unset($budgetingData['to_Accounts_Group_Head_Id']);
		unset($budgetingData['to_Chart_of_Accounts_Id']);

		$budgetingData['to_Proposal_Id'] = $toData;
		$budgetingData['from_Proposal_Id'] = $fromData; 

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


	/*
	* Adding Capital budget reappropriation
	*/
	
	public function addCapitalBudgetReappropriation(CapitalBudgetReappropriationSelect $budgetingObject, $toData, $fromData)
	{ 
		$budgetingData = $this->hydrator->extract($budgetingObject);
		unset($budgetingData['id']);
		unset($budgetingData['from_Activity_Name_Id']);
		unset($budgetingData['from_Broad_Head_Name_Id']);
		unset($budgetingData['from_Object_Code_Id']);
		unset($budgetingData['to_Activity_Name_Id']);
		unset($budgetingData['to_Broad_Head_Name_Id']);
		unset($budgetingData['to_Object_Code_Id']);

		$budgetingData['to_Proposal_Id'] = $toData;
		$budgetingData['from_Proposal_Id'] = $fromData; 

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

	public function updateBudgetReappropriation(BudgetReappropriation $budgetingObject, $from_amount, $to_amount)
	{
		$budgetingData = $this->hydrator->extract($budgetingObject);
		unset($budgetingData['id']);
		unset($budgetingData['from_Proposal_Id']);
		unset($budgetingData['to_Proposal_Id']);
		$budgetingData['from_Amount'] = $from_amount;
		$budgetingData['to_Amount'] = $to_amount;
		$budgetingData['reference_Date'] = date("Y-m-d", strtotime(substr($budgetingData['reference_Date'],0,10)));

		$action = new Update('budget_reappropriation');
		$action->set($budgetingData);
		$action->where(array('id = ?' => $budgetingObject->getId()));

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
	}


	public function updateBudgetReappropriationProposal($status,$id, $tableName)
	{
		if($tableName == 'budget_reappropriation'){
			
			if($status == 'Approved'){
				$reappropriation_details = $this->getBudgetReappropriationDetails($id);

				$from_proposal_id = NULL;
				$to_proposal_id = NULL;
				$amount = NULL;
				$budget_type = NULL;
				foreach($reappropriation_details as $key => $value){
					$from_proposal_id = $value['from_proposal_id'];
					$to_proposal_id = $value['to_proposal_id'];
					$amount = $value['from_amount'];
					$budget_type = $value['budget_type'];
				}

				$this->updateBudgetBalance($from_proposal_id, $amount, $budget_type, $type = 'from');
				$this->updateBudgetBalance($to_proposal_id, $amount, $budget_type, $type = 'to');
			}

			$action = new Update($tableName);
			$action->set(array('status' => $status));
			$action->where(array('id = ?' => $id));
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
	}


	/*This update the budget balance in both the tables */
	public function updateBudgetBalance($id, $amount, $budget_type, $type)
	{
		$sql = new Sql($this->dbAdapter);
		
		if($budget_type == 'capital'){
			$select = $sql->select();
			$select->from(array('t1' => 'budget_proposal_capital'))
					->columns(array('balance'))
					->where(array('t1.id = ?' => $id));
			
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			foreach($resultSet as $set){
				$balance = $set['balance'];
			}
			
			if($type == 'from'){
				$budgetingData['balance'] = (int)$balance - (int)$amount;
			}else{
				$budgetingData['balance'] = (int)$balance + (int)$amount;
			}
			$action = new Update('budget_proposal_capital');
			$action->set($budgetingData);
			$action->where(array('id = ?' => $id));
			$stmt2 = $sql->prepareStatementForSqlObject($action);
			$result2 = $stmt2->execute();
		}
		else{
			$select = $sql->select();
			$select->from(array('t1' => 'budget_proposal'))
					->columns(array('balance'))
					->where(array('t1.id = ?' => $id));
			
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			foreach($resultSet as $set){
				$balance = $set['balance'];
			}
			
			if($type == 'from'){
				$budgetingData['balance'] = (int)$balance - (int)$amount;
			}else{
				$budgetingData['balance'] = (int)$balance + (int)$amount;
			}
			$action = new Update('budget_proposal');
			$action->set($budgetingData);
			$action->where(array('id = ?' => $id));
			$stmt2 = $sql->prepareStatementForSqlObject($action);
			$result2 = $stmt2->execute();
		}
	}

	public function updateEditedBudgetReappropriation(BudgetReappropriation $budgetingObject)
	{
		$budgetingData = $this->hydrator->extract($budgetingObject);
		unset($budgetingData['id']);
		unset($budgetingData['from_Proposal_Id']);
		unset($budgetingData['to_Proposal_Id']);
		$from_amount = $budgetingData['from_Amount'];
		$to_amount = $budgetingData['from_Amount'];
		$purpose = $budgetingData['purpose'];
		$reference_no = $budgetingData['reference_No'];
		$reference_date = date("Y-m-d", strtotime(substr($budgetingData['reference_Date'],0,10)));

		$action = new Update('budget_reappropriation');
		$action->set(array('reference_no' => $reference_no, 'reference_date' => $reference_date, 'from_amount' => $from_amount, 'to_amount' => $to_amount, 'purpose' => $purpose));
		$action->where(array('id = ?' => $budgetingObject->getId()));

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
	}
	
	public function listBudgetReappropriation($columnName, $type, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($type == 'capital'){
			if($organisation_id != NULL){
				$select->from(array('t1' => 'budget_reappropriation')) 
					->join(array('t2' => 'budget_proposal_capital'), 
								't2.id = '.$columnName, array('from_activity_name' => 'activity_name'))
					->join(array('t3' => 'budget_proposal_capital'),
								't3.id = t1.to_proposal_id', array('to_activity_name' => 'activity_name'))
					->join(array('t4' => 'organisation'),
							't4.id = t1.organisation_id', array('organisation_name'))
					->join(array('t5' => 'departments'),
							't5.id = t2.departments_id', array('department_name'));
				$select->where(array('t1.budget_type' => 'capital', 't1.organisation_id' => $organisation_id));
				$select->where->notEqualTo('t1.status','Not Submitted');
			}else{
				$select->from(array('t1' => 'budget_reappropriation')) 
					->join(array('t2' => 'budget_proposal_capital'), 
								't2.id = '.$columnName, array('from_activity_name' => 'activity_name'))
					->join(array('t3' => 'budget_proposal_capital'),
								't3.id = t1.to_proposal_id', array('to_activity_name' => 'activity_name'))
					->join(array('t4' => 'organisation'),
							't4.id = t1.organisation_id', array('organisation_name'))
					->join(array('t5' => 'departments'),
							't5.id = t2.departments_id', array('department_name'));
				$select->where(array('t1.budget_type' => 'capital'));
				$select->where->notEqualTo('t1.status','Not Submitted');
			}
		}else{
			if($organisation_id != NULL){
				$select->from(array('t1' => 'budget_reappropriation')) 
					->join(array('t2' => 'budget_proposal'), 
								't2.id = '.$columnName, array('budget_ledger_head_id'))
						->join(array('t3' => 'budget_ledger_head'),
								't3.id = t2.budget_ledger_head_id', array('from_ledger_head' => 'ledger_head'))
					->join(array('t4' => 'budget_proposal'),
								't4.id = t1.to_proposal_id', array('budget_ledger_head_id'))
					->join(array('t5' => 'budget_ledger_head'),
								't5.id = t4.budget_ledger_head_id', array('to_ledger_head' => 'ledger_head'))
						->join(array('t6' => 'organisation'),
								't6.id = t1.organisation_id', array('organisation_name'))
						->join(array('t7' => 'departments'),
								't7.id = t2.departments_id', array('department_name'));
					$select->where(array('t1.budget_type' => 'current', 't1.organisation_id' => $organisation_id));
					$select->where->notEqualTo('t1.status','Not Submitted');
			}else{
				$select->from(array('t1' => 'budget_reappropriation')) 
					->join(array('t2' => 'budget_proposal'), 
								't2.id = '.$columnName, array('budget_ledger_head_id'))
						->join(array('t3' => 'budget_ledger_head'),
								't3.id = t2.budget_ledger_head_id', array('from_ledger_head' => 'ledger_head'))
					->join(array('t4' => 'budget_proposal'),
								't4.id = t1.to_proposal_id', array('budget_ledger_head_id'))
					->join(array('t5' => 'budget_ledger_head'),
								't5.id = t4.budget_ledger_head_id', array('to_ledger_head' => 'ledger_head'))
						->join(array('t6' => 'organisation'),
								't6.id = t1.organisation_id', array('organisation_name'))
						->join(array('t7' => 'departments'),
								't7.id = t2.departments_id', array('department_name'));
					$select->where(array('t1.budget_type' => 'current'));
					$select->where->notEqualTo('t1.status','Not Submitted');
			}
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}

	public function findReappropriationBudgetTransactions($budgetType, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($budgetType == 'capital'){
			$select->from(array('t1' => 'budget_reappropriation')) 
				->join(array('t2' => 'budget_proposal_capital'), 
							't2.id = t1.from_proposal_id', array('from_activity_name' => 'activity_name', 'from_budget_amount_approved' => 'budget_amount_approved', 'from_balance' => 'balance'))
				->join(array('t3' => 'budget_proposal_capital'),
							't3.id = t1.to_proposal_id', array('to_activity_name' => 'activity_name', 'to_budget_amount_approved' => 'budget_amount_approved', 'to_balance' => 'balance'))
				->join(array('t4' => 'organisation'),
						't4.id = t1.organisation_id', array('organisation_name'))
				->join(array('t5' => 'departments'),
						't5.id = t2.departments_id', array('department_name'));
			$select->where(array('t1.id' => $id));
			
		}else{
			$select->from(array('t1' => 'budget_reappropriation')) 
			   ->join(array('t2' => 'budget_proposal'), 
						't2.id = t1.from_proposal_id', array('budget_ledger_head_id', 'from_budget_amount_approved' => 'budget_amount_approved', 'to_balance' => 'balance'))
				->join(array('t3' => 'budget_ledger_head'),
						't3.id = t2.budget_ledger_head_id', array('from_ledger_head' => 'ledger_head'))
			   ->join(array('t4' => 'budget_proposal'),
						't4.id = t1.to_proposal_id', array('budget_ledger_head_id', 'to_budget_amount_approved' => 'budget_amount_approved', 'to_balance' => 'balance'))
			   ->join(array('t5' => 'budget_ledger_head'),
			   			't5.id = t4.budget_ledger_head_id', array('to_ledger_head' => 'ledger_head'))
				->join(array('t6' => 'organisation'),
						't6.id = t1.organisation_id', array('organisation_name'))
				->join(array('t7' => 'departments'),
						't7.id = t2.departments_id', array('department_name'));
			$select->where(array('t1.id' => $id));
		}

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
	
	public function getAjaxDataId($tableName, $column, $code)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array('id'));
		$select->where(array($column.' = ?' => $code));
		
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
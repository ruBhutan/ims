<?php

namespace BudgetTransactions\Mapper;

use BudgetTransactions\Model\BudgetTransactions;
use BudgetTransactions\Model\BudgetTransactionsSelect;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements BudgetTransactionsMapperInterface
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
	 * @var \BudgetTransactions\Model\BudgetTransactionsInterface
	*/
	protected $transactionPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			\stdClass $transactionPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->transactionPrototype = $transactionPrototype;
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
	
	/**
	* @param int/String $id
	* @return BudgetTransactions
	* @throws \InvalidArgumentException
	*/
	
	public function findSupplementaryBudgetTransactions($budgetType, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		if($budgetType == 'capital'){
			$select->from(array('t1' => 'budget_supplementary')) 
                    ->join(array('t4' => 'budget_proposal_capital'), 
                            't1.from_proposal_id = t4.id', array('activity_name','budget_amount_approved','balance'))
					->join(array('t2' => 'object_code'), 
                            't4.object_code_id = t2.id', array('object_name'))
                    ->join(array('t3'=>'broad_head_name'),
                            't2.broad_head_name_id = t3.id', array('broad_head_name'))
					->where(array('t1.id' =>$id, 't1.budget_type' => $budgetType));
		}
		else {
			$select->from(array('t1' => 'budget_supplementary')) 
					->join(array('t5' => 'budget_proposal'), 
                            't1.from_proposal_id = t5.id', array('budget_amount_approved','balance'))
					->join(array('t4' => 'budget_ledger_head'), 
                            't5.budget_ledger_head_id = t4.id', array('ledger_head'))
                    ->join(array('t2' => 'chart_of_accounts'), 
                            't5.chart_of_accounts_id = t2.id', array('account_code', 'head_of_accounts'))
                    ->join(array('t3'=>'accounts_group_head'),
                            't2.accounts_group_head_id = t3.id', array('group_head'))
					->where(array('t1.id' =>$id, 't1.budget_type' => $budgetType));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function findWithdrawalBudgetTransactions($budgetType, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		if($budgetType == 'capital'){
			$select->from(array('t1' => 'budget_withdrawal')) 
                    ->join(array('t4' => 'budget_proposal_capital'), 
                            't1.from_proposal_id = t4.id', array('activity_name','budget_amount_approved','balance'))
					->join(array('t2' => 'object_code'), 
                            't4.object_code_id = t2.id', array('object_name'))
                    ->join(array('t3'=>'broad_head_name'),
                            't2.broad_head_name_id = t3.id', array('broad_head_name'))
					->where(array('t1.id' =>$id, 't1.budget_type' => $budgetType));
		}
		else {
			$select->from(array('t1' => 'budget_withdrawal')) 
					->join(array('t5' => 'budget_proposal'), 
                            't1.from_proposal_id = t5.id', array('budget_amount_approved','balance'))
					->join(array('t4' => 'budget_ledger_head'), 
                            't5.budget_ledger_head_id = t4.id', array('ledger_head'))
                    ->join(array('t2' => 'chart_of_accounts'), 
                            't5.chart_of_accounts_id = t2.id', array('account_code', 'head_of_accounts'))
                    ->join(array('t3'=>'accounts_group_head'),
                            't2.accounts_group_head_id = t3.id', array('group_head'))
					->where(array('t1.id' =>$id, 't1.budget_type' => $budgetType));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/**
	 * Should return a single proposal
	 *
	 * @param int $id Identifier of the proposal that should be returned
	 * @return BudgetTransactionsInterface
	 */
	 
	public function findBudgetWithdrawalTransactions($budgetType, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		if($budgetType == 'Capital Budget'){
			$select->from(array('t1' => 'budget_withdrawal')) 
                    ->join(array('t4' => 'budget_proposal_capital'), 
                            't1.from_proposal_id = t4.id', array('activity_name','budget_amount_approved','balance'))
					->join(array('t2' => 'object_code'), 
                            't4.object_code_id = t2.id', array('object_name'))
                    ->join(array('t3'=>'broad_head_name'),
                            't2.broad_head_name_id = t3.id', array('broad_head_name'))
					->where(array('t1.id' => $id, 't1.budget_type' => $budgetType));
		}
		else {
			$select->from(array('t1' => 'budget_withdrawal')) 
					->join(array('t5' => 'budget_proposal'), 
                            't1.from_proposal_id = t5.id',array('budget_amount_approved','balance'))
					->join(array('t4' => 'budget_ledger_head'), 
                            't5.budget_ledger_head_id = t4.id', array('ledger_head'))
                    ->join(array('t2' => 'chart_of_accounts'), 
                            't5.chart_of_accounts_id = t2.id', array('account_code', 'head_of_accounts'))
                    ->join(array('t3'=>'accounts_group_head'),
                            't2.accounts_group_head_id = t3.id', array('group_head'))
					->where(array('t1.id' => $id, 't1.budget_type' => $budgetType));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* List the Budget Transactions, i.e. Current/Capital Budget Withdrawal/Supplementary Budget
	*/
	
	public function listSupplementaryBudgetTransactions($budgetType, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		if($budgetType == 'capital'){
			if($organisation_id != NULL){
				$select->from(array('t1' => 'budget_supplementary')) 
                    ->join(array('t4' => 'budget_proposal_capital'), 
                            't1.from_proposal_id = t4.id', array('activity_name','budget_amount_approved','balance'))
					->join(array('t2' => 'object_code'), 
                            't4.object_code_id = t2.id', array('object_name'))
                    ->join(array('t3'=>'broad_head_name'),
                            't2.broad_head_name_id = t3.id', array('broad_head_name'))
					->where(array('t1.budget_type' => $budgetType, 't1.organisation_id' => $organisation_id));
				$select->where->notEqualTo('t1.status','Not Submitted');
			}else{
				$select->from(array('t1' => 'budget_supplementary')) 
                    ->join(array('t4' => 'budget_proposal_capital'), 
                            't1.from_proposal_id = t4.id', array('activity_name','budget_amount_approved','balance'))
					->join(array('t2' => 'object_code'), 
                            't4.object_code_id = t2.id', array('object_name'))
                    ->join(array('t3'=>'broad_head_name'),
							't2.broad_head_name_id = t3.id', array('broad_head_name'))
					->join(array('t5' => 'organisation'),
							't1.organisation_id = t5.id', array('organisation_name'))
					->where(array('t1.budget_type' => $budgetType));
				$select->where->notEqualTo('t1.status','Not Submitted');
			}
		}
		else {
			if($organisation_id != NULL){
				$select->from(array('t1' => 'budget_supplementary')) 
					->join(array('t5' => 'budget_proposal'), 
                            't1.from_proposal_id = t5.id', array('budget_amount_approved','balance'))
					->join(array('t4' => 'budget_ledger_head'), 
                            't5.budget_ledger_head_id = t4.id', array('ledger_head'))
                    ->join(array('t2' => 'chart_of_accounts'), 
                            't5.chart_of_accounts_id = t2.id', array('account_code', 'head_of_accounts'))
                    ->join(array('t3'=>'accounts_group_head'),
                            't2.accounts_group_head_id = t3.id', array('group_head'))
					->where(array('t1.budget_type' => $budgetType, 't1.organisation_id' => $organisation_id));
				$select->where->notEqualTo('t1.status','Not Submitted');
			}else{
				$select->from(array('t1' => 'budget_supplementary')) 
					->join(array('t5' => 'budget_proposal'), 
                            't1.from_proposal_id = t5.id', array('budget_amount_approved','balance'))
					->join(array('t4' => 'budget_ledger_head'), 
                            't5.budget_ledger_head_id = t4.id', array('ledger_head'))
                    ->join(array('t2' => 'chart_of_accounts'), 
                            't5.chart_of_accounts_id = t2.id', array('account_code', 'head_of_accounts'))
                    ->join(array('t3'=>'accounts_group_head'),
							't2.accounts_group_head_id = t3.id', array('group_head'))
					->join(array('t6' => 'organisation'),
							't1.organisation_id = t6.id', array('organisation_name'))
					->where(array('t1.budget_type' => $budgetType));
				$select->where->notEqualTo('t1.status','Not Submitted');
			}
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* List the Budget Transactions, i.e. Current/Capital Budget Withdrawal
	*/
	
	public function listBudgetWithdrawalTransactions($budgetType, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		if($budgetType == 'capital'){
			if($organisation_id != NULL){
				$select->from(array('t1' => 'budget_withdrawal')) 
                    ->join(array('t4' => 'budget_proposal_capital'), 
                            't1.from_proposal_id = t4.id', array('activity_name','budget_amount_approved','balance'))
					->join(array('t2' => 'object_code'), 
                            't4.object_code_id = t2.id', array('object_name'))
                    ->join(array('t3'=>'broad_head_name'),
                            't2.broad_head_name_id = t3.id', array('broad_head_name'))
					->where(array('t1.budget_type' => $budgetType, 't1.organisation_id' => $organisation_id));
				$select->where->notEqualTo('t1.status','Not Submitted');
			}else{
				$select->from(array('t1' => 'budget_withdrawal')) 
                    ->join(array('t4' => 'budget_proposal_capital'), 
                            't1.from_proposal_id = t4.id', array('activity_name','budget_amount_approved','balance'))
					->join(array('t2' => 'object_code'), 
                            't4.object_code_id = t2.id', array('object_name'))
                    ->join(array('t3'=>'broad_head_name'),
							't2.broad_head_name_id = t3.id', array('broad_head_name'))
					->join(array('t5' => 'organisation'),
							't1.organisation_id = t5.id', array('organisation_name'))
					->where(array('t1.budget_type' => $budgetType));
				$select->where->notEqualTo('t1.status','Not Submitted');
			}
		}
		else {
			if($organisation_id != NULL){
				$select->from(array('t1' => 'budget_withdrawal')) 
					->join(array('t5' => 'budget_proposal'), 
                            't1.from_proposal_id = t5.id', array('budget_amount_approved','balance'))
					->join(array('t4' => 'budget_ledger_head'), 
                            't5.budget_ledger_head_id = t4.id', array('ledger_head'))
                    ->join(array('t2' => 'chart_of_accounts'), 
                            't5.chart_of_accounts_id = t2.id', array('account_code', 'head_of_accounts'))
                    ->join(array('t3'=>'accounts_group_head'),
                            't2.accounts_group_head_id = t3.id', array('group_head'))
					->where(array('t1.budget_type' => $budgetType, 't1.organisation_id' => $organisation_id));
				$select->where->notEqualTo('t1.status','Not Submitted');
			}else{
				$select->from(array('t1' => 'budget_withdrawal')) 
					->join(array('t5' => 'budget_proposal'), 
                            't1.from_proposal_id = t5.id', array('budget_amount_approved','balance'))
					->join(array('t4' => 'budget_ledger_head'), 
                            't5.budget_ledger_head_id = t4.id', array('ledger_head'))
                    ->join(array('t2' => 'chart_of_accounts'), 
                            't5.chart_of_accounts_id = t2.id', array('account_code', 'head_of_accounts'))
                    ->join(array('t3'=>'accounts_group_head'),
							't2.accounts_group_head_id = t3.id', array('group_head'))
					->join(array('t6' => 'organisation'),
							't1.organisation_id = t6.id', array('organisation_name'))
					->where(array('t1.budget_type' => $budgetType));
				$select->where->notEqualTo('t1.status','Not Submitted');
			}
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/**
	* @return array/BudgetTransactions()
	*/
	public function findAll($tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName)); // join expression
		$select->limit(15);

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getBudgetSupplementaryDetails($tableName, $status, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		if($tableName == 'budget_proposal_capital'){
			$select->from(array('t1' => 'budget_supplementary'))
					->join(array('t2' => $tableName),
						't2.id = t1.from_proposal_id', array('activity_name', 'proposed_budget_amount', 'budget_amount_approved', 'balance')) 
                    ->join(array('t3' => 'object_code'), 
                            't2.object_code_id = t3.id', array('object_name'))
                    ->join(array('t4'=>'broad_head_name'),
                            't2.broad_head_name_id = t4.id', array('broad_head_name'))
                    ->where(array('t1.status' => $status, 't1.budget_type' => 'capital', 't1.organisation_id' => $organisation_id));
		}else{
			$select->from(array('t1' => 'budget_supplementary'))
					->join(array('t2' => $tableName),
						't2.id = t1.from_proposal_id', array('proposed_budget_amount', 'budget_amount_approved', 'balance')) 
					->join(array('t3' => 'budget_ledger_head'), 
						't2.budget_ledger_head_id = t3.id', array('ledger_head'))
					->join(array('t4' => 'chart_of_accounts'), 
						't2.chart_of_accounts_id = t4.id', array('account_code', 'head_of_accounts'))
					->join(array('t5'=>'accounts_group_head'),
						't2.accounts_group_head_id = t5.id', array('group_head'))
                    ->where(array('t1.status' => $status, 't1.budget_type' => 'current', 't1.organisation_id' => $organisation_id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);	
	}

	public function getBudgetWithdrawalDetails($tableName, $status, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		if($tableName == 'budget_proposal_capital'){
			$select->from(array('t1' => 'budget_withdrawal'))
					->join(array('t2' => $tableName),
						't2.id = t1.from_proposal_id', array('activity_name', 'proposed_budget_amount', 'budget_amount_approved', 'balance')) 
                    ->join(array('t3' => 'object_code'), 
                            't2.object_code_id = t3.id', array('object_name'))
                    ->join(array('t4'=>'broad_head_name'),
                            't2.broad_head_name_id = t4.id', array('broad_head_name'))
                    ->where(array('t1.status' => $status, 't1.budget_type' => 'capital', 't1.organisation_id' => $organisation_id));
		}else{
			$select->from(array('t1' => 'budget_withdrawal'))
					->join(array('t2' => $tableName),
						't2.id = t1.from_proposal_id', array('proposed_budget_amount', 'budget_amount_approved', 'balance')) 
					->join(array('t3' => 'budget_ledger_head'), 
						't2.budget_ledger_head_id = t3.id', array('ledger_head'))
					->join(array('t4' => 'chart_of_accounts'), 
						't2.chart_of_accounts_id = t4.id', array('account_code', 'head_of_accounts'))
					->join(array('t5'=>'accounts_group_head'),
						't2.accounts_group_head_id = t5.id', array('group_head'))
                    ->where(array('t1.status' => $status, 't1.budget_type' => 'current', 't1.organisation_id' => $organisation_id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get the budget transaction details
	* such as from and to details
	* This function will work for all transactions such as Supplementary
	* Budget and Budget Withdrawal
	*/
	
	public function getBudgetDetails($tableName, $data)
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
			$dataValues[1] = $this->getAjaxDataId($table = 'broad_head_name', $column = 'broad_head_name', $code = $dataValues['1']);
			$dataValues[2] = $this->getAjaxDataId($table = 'object_code', $column = 'object_name', $code = $dataValues['2']);
			$select->from(array('t1' => $tableName)) 
                    ->join(array('t2' => 'object_code'), 
                            't1.object_code_id = t2.id', array('object_name'))
                    ->join(array('t3'=>'broad_head_name'),
                            't2.broad_head_name_id = t3.id', array('broad_head_name'))
                    ->where(array('t1.'.$columnNames[0].'= ? ' => $dataValues[0], 't1.broad_head_name_id = ?' => $dataValues[1], 't1.object_code_id = ?' => $dataValues[2] ));
		}
		else {
			$dataValues[1] = $this->getAjaxDataId($table = 'accounts_group_head', $column = 'group_head', $code = $dataValues['1']);
			$dataValues[2] = $this->getAjaxDataId($table = 'chart_of_accounts', $column = 'account_code', $code = $dataValues['2']);
			$select->from(array('t1' => $tableName)) 
					->join(array('t4' => 'budget_ledger_head'), 
                            't1.budget_ledger_head_id = t4.id', array('ledger_head'))
                    ->join(array('t2' => 'chart_of_accounts'), 
                            't1.chart_of_accounts_id = t2.id', array('account_code'))
                    ->join(array('t3'=>'accounts_group_head'),
                            't2.accounts_group_head_id = t3.id', array('group_head'))
                    ->where(array('t1.'.$columnNames[0].'= ? ' => $dataValues[0], 't1.accounts_group_head_id = ?' => $dataValues[1], 't1.chart_of_accounts_id = ?' => $dataValues[2] ));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);	
	}

	public function saveSupplementaryBudget($data, $type)
	{ 
		$transactionData['budget_Type'] = $data['budget_type'];
		if($type == 'capital'){
			$transactionData['from_Proposal_Id'] = $data['object_code_id'];
		}else{
			$transactionData['from_Proposal_Id'] = $data['chart_of_accounts_id'];
		}
		$transactionData['status'] = $data['status'];
		$transactionData['organisation_Id'] = $data['organisation_id'];

		$action = new Insert('budget_supplementary');
		$action->values($transactionData);
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
	}


	public function saveWithdrawalBudget($data, $type)
	{
		$transactionData['budget_Type'] = $data['budget_type'];
		if($type == 'capital'){
			$transactionData['from_Proposal_Id'] = $data['object_code_id'];
		}else{
			$transactionData['from_Proposal_Id'] = $data['chart_of_accounts_id'];
		}
		$transactionData['status'] = $data['status'];
		$transactionData['organisation_Id'] = $data['organisation_id'];

		$action = new Insert('budget_withdrawal');
		$action->values($transactionData);
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
	}
        	
	/**
	 * 
	 * @param type $BudgetTransactionsInterface
	 * 
	 * to save Objectives Details
	 */
	
	public function saveBudgetTransactions(BudgetTransactions $transactionObject, $data_to_insert, $tableName)
	{
		$transactionData = $this->hydrator->extract($transactionObject);
		unset($transactionData['id']);

		//ID present, so it is an update
		foreach ($data_to_insert as $key => $value){
			$transactionData['reference_No'] = $transactionData['reference_No'];
			$transactionData['reference_Date'] = date("Y-m-d", strtotime(substr($transactionData['reference_Date'],0,10)));
			$transactionData['reasons'] = $transactionData['reasons'];
			$transactionData['amount'] = $value;
			$transactionData['status'] = 'Submitted';

			if($tableName == 'budget_supplementary'){
				$action = new Update($tableName);
				$action->set(array('reference_no' => $transactionData['reference_No'], 'reference_date' => $transactionData['reference_Date'], 'reasons' => $transactionData['reasons'], 'amount' => $transactionData['amount'], 'status' => $transactionData['status']));
				$action->where(array('id = ?' => $key));
			}
			else if($tableName == 'budget_withdrawal'){
				$action = new Update($tableName);
				$action->set(array('reference_no' => $transactionData['reference_No'], 'reference_date' => $transactionData['reference_Date'], 'reasons' => $transactionData['reasons'], 'amount' => $transactionData['amount'], 'status' => $transactionData['status']));
				$action->where(array('id = ?' => $key));
			}
			
			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();
		}
	}


	public function updateBudgetTransactions(BudgetTransactions $transactionObject, $tableName)
	{
		$transactionData = $this->hydrator->extract($transactionObject);
		unset($transactionData['id']);

		$transactionData['reference_Date'] = date("Y-m-d", strtotime(substr($transactionData['reference_Date'],0,10)));

		if($tableName == 'budget_supplementary'){
			$action = new Update($tableName);
			$action->set($transactionData);
			$action->where(array('id = ?' => $transactionObject->getId()));
		}

		if($tableName == 'budget_withdrawal'){
			$action = new Update($tableName);
			$action->set($transactionData);
			$action->where(array('id = ?' => $transactionObject->getId()));
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
	}


	public function updateBudgetProposalTransaction($status,$id, $tableName)
	{
		if($tableName == 'budget_supplementary'){
			if($status == 'Approved'){
				$suplementary_details = $this->getBudgetBudgetTransactionDetails($id, $tableName = 'budget_supplementary');
				$from_proposal_id = NULL;
				$amount = NULL;
				$budget_type = NULL;

				foreach($suplementary_details as $key => $value){
					$from_proposal_id = $value['from_proposal_id'];
					$amount = $value['amount'];
					$budget_type = $value['budget_type'];
				}
				$this->updateBudgetBalance($from_proposal_id, $amount, $budget_type, $type = 'supplementary');
			}
			$action = new Update($tableName);
			$action->set(array('status' => $status));
			$action->where(array('id = ?' => $id));
		}

		else if($tableName == 'budget_withdrawal'){
			if($status == 'Approved'){
				$withdrawal_details = $this->getBudgetBudgetTransactionDetails($id, $tableName = 'budget_withdrawal');
				$from_proposal_id = NULL;
				$amount = NULL;
				$budget_type = NULL;

				foreach($withdrawal_details as $key => $value){
					$from_proposal_id = $value['from_proposal_id'];
					$amount = $value['amount'];
					$budget_type = $value['budget_type'];
				}
				$this->updateBudgetBalance($from_proposal_id, $amount, $budget_type, $type = 'withdrawal');
			}
			$action = new Update($tableName);
			$action->set(array('status' => $status));
			$action->where(array('id = ?' => $id));
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
	}


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
			
			if($type == 'supplementary'){
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
			
			if($type == 'supplementary'){
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


	public function getBudgetBudgetTransactionDetails($id, $tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'budget_supplementary'){
			$select->from(array('t1' => $tableName)) 
				->where(array('t1.id' => $id)); 
			}else{
				$select->from(array('t1' => $tableName)) 
				->where(array('t1.id' => $id)); 
			}

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
	* @return array/BudgetTransactions()
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
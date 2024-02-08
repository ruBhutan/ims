<?php

namespace BudgetTransactions\Service;

use BudgetTransactions\Model\BudgetTransactions;
use BudgetTransactions\Model\BudgetTransactionsSelect;

//need to add more models

interface BudgetTransactionsServiceInterface
{
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username, $usertype);
	
	
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|BudgetTransactionsInterface[]
	*/
	
	public function listAll($tableName);

	public function getBudgetSupplementaryDetails($tableName, $status, $organisation_id);

	public function getBudgetWithdrawalDetails($tableName, $status, $organisation_id);
	
	/*
	* Get the budget transaction details
	* such as from and to details
	* This function will work for all transactions such as Supplementary
	* Budget and Budget Withdrawal
	*/
	
	public function getBudgetDetails($tableName, $data);
	
	/**
	 * Should return a single proposal
	 *
	 * @param int $id Identifier of the proposal that should be returned
	 * @return BudgetTransactionsInterface
	 */
	 
	public function findSupplementaryBudgetTransactions($budgetType, $id);

	public function findWithdrawalBudgetTransactions($budgetType, $id);
	
	/**
	 * Should return a single proposal
	 *
	 * @param int $id Identifier of the proposal that should be returned
	 * @return BudgetTransactionsInterface
	 */
	 
	public function findBudgetWithdrawalTransactions($budgetType, $id);
	
	/*
	* List the Budget Transactions, i.e. Current/Capital Supplementary Budget
	*/
	
	public function listSupplementaryBudgetTransactions($budgetType, $organisation_id);
	
	/*
	* List the Budget Transactions, i.e. Current/Capital Budget Withdrawal
	*/
	
	public function listBudgetWithdrawalTransactions($budgetType, $organisation_id);

	public function saveSupplementaryBudget($data, $type);

	public function saveWithdrawalBudget($data, $type);
        
	 /**
	 * @param BudgetTransactionsInterface $transactionObject
	 *
	 * @param BudgetTransactionsInterface $transactionObject
	 * @return BudgetTransactionsInterface
	 * @throws \Exception
	 */
	 
	 public function saveBudgetTransactions(BudgetTransactions $transactionObject, $data_to_insert, $tableName);

	 public function updateBudgetTransactions(BudgetTransactions $transactionObject, $tableName);

	 public function updateBudgetProposalTransaction($status,$id, $tableName);
	 
	 /**
	 * Should return a set of all objectives that we can iterate over. 
	 * 
	 * The purpose of the function is the objectives for the dropdown select list
	 *
	 * @return array|BudgetTransactionsInterface[]
	*/
	
	public function listSelectData($tableName, $columnName);
		
		
}
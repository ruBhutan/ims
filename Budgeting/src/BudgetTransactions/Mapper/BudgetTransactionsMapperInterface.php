<?php

namespace BudgetTransactions\Mapper;

use BudgetTransactions\Model\BudgetTransactions;
use BudgetTransactions\Model\BudgetTransactionsSelect;

interface BudgetTransactionsMapperInterface
{
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username, $usertype);

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
	 * @param int/string $id
	 * @return BudgetTransactions
	 * throws \InvalidArugmentException
	 * 
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

	/**
	 * 
	 * @return array/ BudgetTransactions[]
	 */
	 
	public function findAll($tableName);

	public function saveSupplementaryBudget($data, $type);

	public function saveWithdrawalBudget($data, $type);
        
	/**
	 * 
	 * @param type $BudgetTransactionsInterface
	 * 
	 * to save transactions
	 */
	
	public function saveBudgetTransactions(BudgetTransactions $BudgetTransactionsInterface, $data_to_insert, $tableName);

	public function updateBudgetTransactions(BudgetTransactions $transactionObject, $tableName);

	public function updateBudgetProposalTransaction($status,$id, $tableName);
		
	/**
	 * 
	 * @return array/ BudgetTransactions[]
	 */
	 
	public function listSelectData($tableName, $columnName);
	
}
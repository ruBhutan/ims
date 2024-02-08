<?php

namespace BudgetTransactions\Mapper;

use BudgetTransactions\Model\BudgetTransactions;
use BudgetTransactions\Model\BudgetTransactionsSelect;

interface BudgetTransactionsMapperInterface
{
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username);
	
	/*
	* Get organisation id based on the username
	*/
	
	public function getOrganisationId($username);
	
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
	
	public function listSupplementaryBudgetTransactions($budgetType);
	
	/*
	* List the Budget Transactions, i.e. Current/Capital Budget Withdrawal
	*/
	
	public function listBudgetWithdrawalTransactions($budgetType);

	/**
	 * 
	 * @return array/ BudgetTransactions[]
	 */
	 
	public function findAll($tableName);
        
	/**
	 * 
	 * @param type $BudgetTransactionsInterface
	 * 
	 * to save transactions
	 */
	
	public function saveBudgetTransactions(BudgetTransactions $BudgetTransactionsInterface, $tableName);
		
	/**
	 * 
	 * @return array/ BudgetTransactions[]
	 */
	 
	public function listSelectData($tableName, $columnName);
	
}
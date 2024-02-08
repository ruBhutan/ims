<?php

namespace BudgetTransactions\Service;

use BudgetTransactions\Mapper\BudgetTransactionsMapperInterface;
use BudgetTransactions\Model\BudgetTransactions;
use BudgetTransactions\Model\BudgetTransactionsSelect;

class BudgetTransactionsService implements BudgetTransactionsServiceInterface
{
	/**
	 * @var \Blog\Mapper\BudgetTransactionsMapperInterface
	*/
	
	protected $transactionMapper;
	
	public function __construct(BudgetTransactionsMapperInterface $transactionMapper) {
		$this->transactionMapper = $transactionMapper;
	}
	
	public function listAll($tableName)
	{
		return $this->transactionMapper->findAll($tableName);
	}
		
	public function getUserDetailsId($username)
	{
		return $this->transactionMapper->getUserDetailsId($username);
	}
		
	public function getOrganisationId($username)
	{
		return $this->transactionMapper->getOrganisationId($username);
	}
	
	public function getBudgetDetails($tableName, $data)
	{
		return $this->transactionMapper->getBudgetDetails($tableName, $data);
	}
	 
	public function findSupplementaryBudgetTransactions($budgetType, $id)
	{
		return $this->transactionMapper->findSupplementaryBudgetTransactions($budgetType, $id);
	}
		 
	public function findBudgetWithdrawalTransactions($budgetType, $id)
	{
		return $this->transactionMapper->findBudgetWithdrawalTransactions($budgetType, $id);
	}
	
	public function listSupplementaryBudgetTransactions($budgetType)
	{
		return $this->transactionMapper->listSupplementaryBudgetTransactions($budgetType);
	}
		
	public function listBudgetWithdrawalTransactions($budgetType)
	{
		return $this->transactionMapper->listBudgetWithdrawalTransactions($budgetType);
	}
        	
	public function saveBudgetTransactions(BudgetTransactions $transactionObject, $tableName) 
	{
		return $this->transactionMapper->saveBudgetTransactions($transactionObject, $tableName);
	}
		
	public function listSelectData($tableName, $columnName)
	{
		return $this->transactionMapper->listSelectData($tableName, $columnName);
	}
	
}
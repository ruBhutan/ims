<?php

namespace BudgetTransactions\Service;

use BudgetTransactions\Mapper\BudgetTransactionsMapperInterface;
use BudgetTransactions\Model\BudgetTransactions;
use BudgetTransactions\Model\BudgetTransactionsSelect;

class BudgetTransactionsService implements BudgetTransactionsServiceInterface
{
	/**
	 * @var \BudgetTransaction\Mapper\BudgetTransactionsMapperInterface
	*/
	
	protected $transactionMapper;
	
	public function __construct(BudgetTransactionsMapperInterface $transactionMapper) {
		$this->transactionMapper = $transactionMapper;
	}
	
	public function listAll($tableName)
	{
		return $this->transactionMapper->findAll($tableName);
	}
		
	public function getUserDetailsId($username, $usertype)
	{
		return $this->transactionMapper->getUserDetailsId($username, $usertype);
	}

	public function getBudgetSupplementaryDetails($tableName, $status, $organisation_id)
	{
		return $this->transactionMapper->getBudgetSupplementaryDetails($tableName, $status, $organisation_id);
	}

	public function getBudgetWithdrawalDetails($tableName, $status, $organisation_id)
	{
		return $this->transactionMapper->getBudgetWithdrawalDetails($tableName, $status, $organisation_id);
	}
	
	public function getBudgetDetails($tableName, $data)
	{
		return $this->transactionMapper->getBudgetDetails($tableName, $data);
	}
	 
	public function findSupplementaryBudgetTransactions($budgetType, $id)
	{
		return $this->transactionMapper->findSupplementaryBudgetTransactions($budgetType, $id);
	}

	public function findWithdrawalBudgetTransactions($budgetType, $id)
	{
		return $this->transactionMapper->findWithdrawalBudgetTransactions($budgetType, $id);
	}
		 
	public function findBudgetWithdrawalTransactions($budgetType, $id)
	{
		return $this->transactionMapper->findBudgetWithdrawalTransactions($budgetType, $id);
	}
	
	public function listSupplementaryBudgetTransactions($budgetType, $organisation_id)
	{
		return $this->transactionMapper->listSupplementaryBudgetTransactions($budgetType, $organisation_id);
	}
		
	public function listBudgetWithdrawalTransactions($budgetType, $organisation_id)
	{
		return $this->transactionMapper->listBudgetWithdrawalTransactions($budgetType, $organisation_id);
	}

	public function saveSupplementaryBudget($data, $type)
	{
		return $this->transactionMapper->saveSupplementaryBudget($data, $type);
	}

	public function saveWithdrawalBudget($data, $type)
	{
		return $this->transactionMapper->saveWithdrawalBudget($data, $type);
	}
        	
	public function saveBudgetTransactions(BudgetTransactions $transactionObject, $data_to_insert, $tableName) 
	{
		return $this->transactionMapper->saveBudgetTransactions($transactionObject, $data_to_insert, $tableName);
	}

	public function updateBudgetTransactions(BudgetTransactions $transactionObject, $tableName)
	{
		return $this->transactionMapper->updateBudgetTransactions($transactionObject, $tableName);
	}

	public function updateBudgetProposalTransaction($status,$id, $tableName)
	{
		return $this->transactionMapper->updateBudgetProposalTransaction($status, $id, $tableName);
	}
		
	public function listSelectData($tableName, $columnName)
	{
		return $this->transactionMapper->listSelectData($tableName, $columnName);
	}
	
}
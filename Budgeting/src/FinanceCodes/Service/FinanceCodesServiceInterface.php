<?php

namespace FinanceCodes\Service;

use FinanceCodes\Model\ChartAccounts;
use FinanceCodes\Model\AccountsGroupHead;
use FinanceCodes\Model\BroadHeadName;
use FinanceCodes\Model\ObjectCode;

//need to add more models

interface FinanceCodesServiceInterface
{

	public function getUserDetailsId($username, $usertype);

	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|FinanceCodesInterface[]
	*/
	
	public function listAll($tableName);

	/**
	 * Take table name and id
	 * returns an array 
	 *
	 * @param int $id Identifier of the proposal that should be returned
	 * @return FinanceCodesInterface
	 */
	 
	public function findFinanceCode($tableName, $id);
	 
	 /*
	 * Save the Broad Head Name
	 */
	 
	 public function saveBroadHeadName(BroadHeadName $codesObject);
	 
	 /*
	 * Save Object Code
	 */
	 
	 public function saveObjectCode(ObjectCode $codesObject);
	 
	 /*
	 * Save Chart of Accounts
	 */
	 
	 public function saveChartAccounts(ChartAccounts $codesObject);
	 
	 /*
	 * Save Accounts Group Head
	 */
	 
	 public function saveAccountsGroupHead(AccountsGroupHead $codesObject);
	 	 
	 /**
	 * Should return a set of all objectives that we can iterate over. 
	 * 
	 * The purpose of the function is the objectives for the dropdown select list
	 *
	 * @return array|FinanceCodesInterface[]
	*/
	
	public function listSelectData($tableName, $columnName);
		
		
}
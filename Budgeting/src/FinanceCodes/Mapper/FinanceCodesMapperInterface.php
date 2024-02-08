<?php

namespace FinanceCodes\Mapper;

use FinanceCodes\Model\ChartAccounts;
use FinanceCodes\Model\AccountsGroupHead;
use FinanceCodes\Model\BroadHeadName;
use FinanceCodes\Model\ObjectCode;

interface FinanceCodesMapperInterface
{

	public function getUserDetailsId($username, $usertype);
	
	/*
	* return array of object Code for a given id
	*/
	public function findFinanceCode($tableName, $id);

	/**
	 * 
	 * @return array/ FinanceCodes[]
	 */
	 
	public function findAll($tableName);
        		
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
	 * 
	 * @return array/ FinanceCodes[]
	 */
	 
	public function listSelectData($tableName, $columnName);
	
}
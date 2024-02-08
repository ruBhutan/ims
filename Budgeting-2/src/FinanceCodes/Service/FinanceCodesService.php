<?php

namespace FinanceCodes\Service;

use FinanceCodes\Mapper\FinanceCodesMapperInterface;
use FinanceCodes\Model\ChartAccounts;
use FinanceCodes\Model\AccountsGroupHead;
use FinanceCodes\Model\BroadHeadName;
use FinanceCodes\Model\ObjectCode;

class FinanceCodesService implements FinanceCodesServiceInterface
{
	/**
	 * @var \Blog\Mapper\FinanceCodesMapperInterface
	*/
	
	protected $codesMapper;
	
	public function __construct(FinanceCodesMapperInterface $codesMapper) {
		$this->codesMapper = $codesMapper;
	}
	
	public function listAll($tableName)
	{
		return $this->codesMapper->findAll($tableName);
	}
	 
	public function findFinanceCode($tableName, $id)
	{
		return $this->codesMapper->findFinanceCode($tableName, $id);
	}
    	
	public function saveBroadHeadName(BroadHeadName $codesObject)
	{
		return $this->codesMapper->saveBroadHeadName($codesObject);
	}
	 
	public function saveObjectCode(ObjectCode $codesObject)
	{
		return $this->codesMapper->saveObjectCode($codesObject);
	}
	
	public function saveChartAccounts(ChartAccounts $codesObject)
	{
		return $this->codesMapper->saveChartAccounts($codesObject);
	}
	
	public function saveAccountsGroupHead(AccountsGroupHead $codesObject)
	{
		return $this->codesMapper->saveAccountsGroupHead($codesObject);
	}
	
	public function listSelectData($tableName, $columnName)
	{
		return $this->codesMapper->listSelectData($tableName, $columnName);
	}
	
}
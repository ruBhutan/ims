<?php

namespace Budgeting\Service;

use Budgeting\Mapper\BudgetingMapperInterface;
use Budgeting\Model\BudgetProposal;
use Budgeting\Model\CapitalBudgetProposal;
use Budgeting\Model\BudgetLedger;
use Budgeting\Model\BudgetReappropriationSelect;
use Budgeting\Model\BudgetReappropriation;

class BudgetingService implements BudgetingServiceInterface
{
	/**
	 * @var \Blog\Mapper\BudgetingMapperInterface
	*/
	
	protected $budgetingMapper;
	
	public function __construct(BudgetingMapperInterface $budgetingMapper) {
		$this->budgetingMapper = $budgetingMapper;
	}
	
	public function listAll($tableName)
	{
		return $this->budgetingMapper->findAll($tableName);
	}
	
	public function listBudgetLedger($tableName)
	{
		return $this->budgetingMapper->listBudgetLedger($tableName);
	}
	
	public function findBudgetLedger($id)
	{
		return $this->budgetingMapper->findBudgetLedger($id);
	}
	
	public function findProposalDetail($tableName,$id)
	{
		return $this->budgetingMapper->findProposalDetail($tableName, $id);
	}
	
	public function listBudgetProposal($tableName, $status)
	{
		return $this->budgetingMapper->listBudgetProposal($tableName, $status);
	}
	
	public function updateBudgetProposal($status, $previousStatus)
	{
		return $this->budgetingMapper->updateBudgetProposal($status, $previousStatus);
	}
	
	public function getUserDetailsId($username)
	{
		return $this->budgetingMapper->getUserDetailsId($username);
	}
	
	public function getOrganisationId($username)
	{
		return $this->budgetingMapper->getOrganisationId($username);
	}
		
	public function saveBudgetLedger(BudgetLedger $budgetingObject) 
	{
		return $this->budgetingMapper->saveBudgetLedger($budgetingObject);
	}
	
	public function saveBudgetProposal(BudgetProposal $budgetingObject, $chart_of_accounts_id, $accounts_group_head_id) 
	{
		return $this->budgetingMapper->saveBudgetProposal($budgetingObject, $chart_of_accounts_id, $accounts_group_head_id);
	}
	
	public function saveCapitalBudgetProposal(CapitalBudgetProposal $budgetingObject, $broad_head_name_id, $object_code_id) 
	{
		return $this->budgetingMapper->saveCapitalBudgetProposal($budgetingObject, $broad_head_name_id, $object_code_id);
	}
		 
	public function getReappropriationDetails($tableName, $data)
	{
		 return $this->budgetingMapper->reappropriationDetails($tableName, $data);
	}
	
	public function addBudgetReappropriation(BudgetReappropriation $budgetingObject, $toData, $fromData, $toId, $fromId)
	{
		return $this->budgetingMapper->addBudgetReappropriation($budgetingObject, $toData, $fromData, $toId, $fromId);
	}
	
	public function listBudgetReappropriation($columnName)
	{
		return $this->budgetingMapper->listBudgetReappropriation($columnName);
	}
	
	public function listSelectData($tableName, $columnName, $condition)
	{
		return $this->budgetingMapper->listSelectData($tableName, $columnName, $condition);
	}
	
}
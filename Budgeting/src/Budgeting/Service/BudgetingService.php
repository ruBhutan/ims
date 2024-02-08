<?php

namespace Budgeting\Service;

use Budgeting\Mapper\BudgetingMapperInterface;
use Budgeting\Model\BudgetProposal;
use Budgeting\Model\CapitalBudgetProposal;
use Budgeting\Model\BudgetLedger;
use Budgeting\Model\BudgetReappropriationSelect;
use Budgeting\Model\BudgetReappropriation;
use Budgeting\Model\CapitalBudgetReappropriationSelect;

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
	
	public function listBudgetProposal($tableName, $status, $organisation_id)
	{
		return $this->budgetingMapper->listBudgetProposal($tableName, $status, $organisation_id);
	}
	
	public function updateBudgetProposal($tableName, $status, $previousStatus, $organisation_id)
	{
		return $this->budgetingMapper->updateBudgetProposal($tableName, $status, $previousStatus, $organisation_id);
	}
	
	public function getUserDetailsId($username, $usertype)
	{
		return $this->budgetingMapper->getUserDetailsId($username, $usertype);
	}
	
	public function getOrganisationId($username)
	{
		return $this->budgetingMapper->getOrganisationId($username);
	}
		
	public function saveBudgetLedger(BudgetLedger $budgetingObject) 
	{
		return $this->budgetingMapper->saveBudgetLedger($budgetingObject);
	}
	
	public function saveBudgetProposal(BudgetProposal $budgetingObject, $chart_of_accounts_id, $accounts_group_head_id, $role_type) 
	{
		return $this->budgetingMapper->saveBudgetProposal($budgetingObject, $chart_of_accounts_id, $accounts_group_head_id, $role_type);
	}

	public function deleteCurrentBudgetProposal($id)
	{
		return $this->budgetingMapper->deleteCurrentBudgetProposal($id);
	}

	public function deleteCapitalBudgetProposal($id)
	{
		return $this->budgetingMapper->deleteCapitalBudgetProposal($id);
	}
	
	public function saveCapitalBudgetProposal(CapitalBudgetProposal $budgetingObject, $broad_head_name_id, $object_code_id, $role_type) 
	{
		return $this->budgetingMapper->saveCapitalBudgetProposal($budgetingObject, $broad_head_name_id, $object_code_id, $role_type);
	}

	public function getBudgetReappropriationDetails($id)
	{
		return $this->budgetingMapper->getBudgetReappropriationDetails($id);
	}
		 
	public function getReappropriationDetails($tableName, $data)
	{
		 return $this->budgetingMapper->reappropriationDetails($tableName, $data);
	}

	public function getBudgetReappropriationDetailsList($tableName, $type, $id)
	{
		return $this->budgetingMapper->getBudgetReappropriationDetailsList($tableName, $type, $id);
	}
	
	public function addBudgetReappropriation(BudgetReappropriationSelect $budgetingObject, $toData, $fromData)
	{
		return $this->budgetingMapper->addBudgetReappropriation($budgetingObject, $toData, $fromData);
	}

	public function addCapitalBudgetReappropriation(CapitalBudgetReappropriationSelect $budgetingObject, $toData, $fromData)
	{
		return $this->budgetingMapper->addCapitalBudgetReappropriation($budgetingObject, $toData, $fromData);
	}

	public function updateBudgetReappropriation(BudgetReappropriation $budgetingObject, $from_amount, $to_amount)
	{
		return $this->budgetingMapper->updateBudgetReappropriation($budgetingObject, $from_amount, $to_amount);
	}

	public function updateBudgetReappropriationProposal($status,$id, $tableName)
	{
		return $this->budgetingMapper->updateBudgetReappropriationProposal($status,$id, $tableName);
	}

	public function updateEditedBudgetReappropriation(BudgetReappropriation $budgetingObject)
	{
		return $this->budgetingMapper->updateEditedBudgetReappropriation($budgetingObject);
	}
	
	public function listBudgetReappropriation($columnName, $type, $organisation_id)
	{
		return $this->budgetingMapper->listBudgetReappropriation($columnName, $type, $organisation_id);
	}

	public function findReappropriationBudgetTransactions($budgetType, $id)
	{
		return $this->budgetingMapper->findReappropriationBudgetTransactions($budgetType, $id);
	}
	
	public function listSelectData($tableName, $columnName, $condition)
	{
		return $this->budgetingMapper->listSelectData($tableName, $columnName, $condition);
	}
	
}
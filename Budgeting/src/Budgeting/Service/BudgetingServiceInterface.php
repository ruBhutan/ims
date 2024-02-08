<?php

namespace Budgeting\Service;

use Budgeting\Model\BudgetProposal;
use Budgeting\Model\CapitalBudgetProposal;
use Budgeting\Model\BudgetLedger;
use Budgeting\Model\BudgetReappropriationSelect;
use Budgeting\Model\BudgetReappropriation;
use Budgeting\Model\CapitalBudgetReappropriationSelect;

//need to add more models

interface BudgetingServiceInterface
{
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|BudgetingInterface[]
	*/
	
	public function listAll($tableName);
	
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|BudgetingInterface[]
	*/
	
	public function listBudgetLedger($tableName);
	
	/*
	* Find the budget ledger given an id
	*/
	
	public function findBudgetLedger($id);
	
	/*
	* Find the Proposal Details
	*/
	
	public function findProposalDetail($tableName, $id);
	
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|BudgetingInterface[]
	*/
	
	public function listBudgetProposal($tableName, $status, $organisation_id);
	
	/*
	* Update the budget proposal status
	*/
	
	public function updateBudgetProposal($tableName, $status, $previousStatus, $organisation_id);
	
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username, $usertype);
	
	/*
	* Get organisation id based on the username
	*/
	
	public function getOrganisationId($username);
        
	 
	 /**
	 * @param BudgetingInterface $budgetingObject
	 *
	 * @param BudgetingInterface $budgetingObject
	 * @return BudgetingInterface
	 * @throws \Exception
	 */
	 
	 public function saveBudgetLedger(BudgetLedger $budgetingObject);
	 
	 /**
	 * @param BudgetingInterface $budgetingObject
	 *
	 * @param BudgetingInterface $budgetingObject
	 * @return BudgetingInterface
	 * @throws \Exception
	 */
	 
	 public function saveBudgetProposal(BudgetProposal $budgetingObject, $chart_of_accounts_id, $accounts_group_head_id, $role_type);

	 public function deleteCurrentBudgetProposal($id);

	 public function deleteCapitalBudgetProposal($id);
	 
	 /*
	 * Save Capital Budget Proposal
	 */
	 
	 public function saveCapitalBudgetProposal(CapitalBudgetProposal $budgetingObject, $broad_head_name_id, $object_code_id, $role_type);	
	 
	 
	 public function getBudgetReappropriationDetails($id);
	 
	 /*
	 * Get Reappropriation Details
	 */
	 
	 public function getReappropriationDetails($tableName, $data);

	 public function getBudgetReappropriationDetailsList($tableName, $type, $id);
	 
	 /*
	 * To add to the reappropriation tables
	 */
	 
	 public function addBudgetReappropriation(BudgetReappropriationSelect $budgetingObject, $toData, $fromData);

	 public function addCapitalBudgetReappropriation(CapitalBudgetReappropriationSelect $budgetingObject, $toData, $fromData);

	 public function updateBudgetReappropriation(BudgetReappropriation $budgetingObject, $from_amount, $to_amount);

	 public function updateBudgetReappropriationProposal($status,$id, $tableName);

	 public function updateEditedBudgetReappropriation(BudgetReappropriation $budgetingObject);
	 
	 /*
	 * List the budget reappropriation
	 */
	 
	 public function listBudgetReappropriation($columnName, $type, $organisation_id);

	 public function findReappropriationBudgetTransactions($budgetType, $id);
	 
	 /**
	 * Should return a set of all objectives that we can iterate over. 
	 * 
	 * The purpose of the function is the objectives for the dropdown select list
	 *
	 * @return array|BudgetingInterface[]
	*/
	
	public function listSelectData($tableName, $columnName, $condition);
		
		
}
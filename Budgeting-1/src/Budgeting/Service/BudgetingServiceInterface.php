<?php

namespace Budgeting\Service;

use Budgeting\Model\BudgetProposal;
use Budgeting\Model\CapitalBudgetProposal;
use Budgeting\Model\BudgetLedger;
use Budgeting\Model\BudgetReappropriationSelect;
use Budgeting\Model\BudgetReappropriation;

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
	
	public function listBudgetProposal($tableName, $status);
	
	/*
	* Update the budget proposal status
	*/
	
	public function updateBudgetProposal($status, $previousStatus);
	
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username);
	
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
	 
	 public function saveBudgetProposal(BudgetProposal $budgetingObject, $chart_of_accounts_id, $accounts_group_head_id);
	 
	 /*
	 * Save Capital Budget Proposal
	 */
	 
	 public function saveCapitalBudgetProposal(CapitalBudgetProposal $budgetingObject, $broad_head_name_id, $object_code_id);	 
	 
	 /*
	 * Get Reappropriation Details
	 */
	 
	 public function getReappropriationDetails($tableName, $data);
	 
	 /*
	 * To add to the reappropriation tables
	 */
	 
	 public function addBudgetReappropriation(BudgetReappropriation $budgetingObject, $toData, $fromData, $toId, $fromId);
	 
	 /*
	 * List the budget reappropriation
	 */
	 
	 public function listBudgetReappropriation($columnName);
	 
	 /**
	 * Should return a set of all objectives that we can iterate over. 
	 * 
	 * The purpose of the function is the objectives for the dropdown select list
	 *
	 * @return array|BudgetingInterface[]
	*/
	
	public function listSelectData($tableName, $columnName, $condition);
		
		
}
<?php

namespace Budgeting\Mapper;

use Budgeting\Model\BudgetProposal;
use Budgeting\Model\CapitalBudgetProposal;
use Budgeting\Model\BudgetLedger;
use Budgeting\Model\BudgetReappropriationSelect;
use Budgeting\Model\BudgetReappropriation;

interface BudgetingMapperInterface
{

	/**
	 * 
	 * @return array/ Budgeting[]
	 */
	 
	public function findAll($tableName);
	
	/**
	 * 
	 * @return array/ Budgeting[]
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
	 * 
	 * @return array/ Budgeting[]
	 */
	 
	public function listBudgetProposal($tableName, $status);
	
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
	 * 
	 * @param type $BudgetingInterface
	 * 
	 * to save budgetings
	 */
	
	public function saveBudgetLedger(BudgetLedger $BudgetingInterface);
	
	/**
	 * 
	 * @param type $BudgetingInterface
	 * 
	 * to save budgetings
	 */
	
	public function saveBudgetProposal(BudgetProposal $BudgetingInterface, $chart_of_accounts_id, $accounts_group_head_id);
	
	public function saveCapitalBudgetProposal(CapitalBudgetProposal $BudgetingInterface, $broad_head_name_id, $object_code_id);
	
	/*
	* Get To/From Reappropraition Details
	*/
	
	public function reappropriationDetails($tableName, $data);
	
	/*
	* Add Budget Reappropriation
	*/
	
	public function addBudgetReappropriation(BudgetReappropriation $budgetingObject, $toData, $fromData, $toId, $fromId);
	
	public function listBudgetReappropriation($columnName);
	

	
	/**
	 * 
	 * @return array/ Budgeting[]
	 */
	 
	public function listSelectData($tableName, $columnName, $condition);
	
}
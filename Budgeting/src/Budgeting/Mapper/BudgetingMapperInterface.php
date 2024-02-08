<?php

namespace Budgeting\Mapper;

use Budgeting\Model\BudgetProposal;
use Budgeting\Model\CapitalBudgetProposal;
use Budgeting\Model\BudgetLedger;
use Budgeting\Model\BudgetReappropriationSelect;
use Budgeting\Model\BudgetReappropriation;
use Budgeting\Model\CapitalBudgetReappropriationSelect;

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
	 
	public function listBudgetProposal($tableName, $status, $organisation_id);
	
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
	
	public function saveBudgetProposal(BudgetProposal $BudgetingInterface, $chart_of_accounts_id, $accounts_group_head_id, $role_type);

	public function deleteCurrentBudgetProposal($id);

	public function deleteCapitalBudgetProposal($id);
	
	public function saveCapitalBudgetProposal(CapitalBudgetProposal $BudgetingInterface, $broad_head_name_id, $object_code_id, $role_type);

	public function getBudgetReappropriationDetails($id);
	
	/*
	* Get To/From Reappropraition Details
	*/
	
	public function reappropriationDetails($tableName, $data);

	public function getBudgetReappropriationDetailsList($tableName, $type, $id);
	
	/*
	* Add Budget Reappropriation
	*/
	
	public function addBudgetReappropriation(BudgetReappropriationSelect $budgetingObject, $toData, $fromData);

	public function addCapitalBudgetReappropriation(CapitalBudgetReappropriationSelect $budgetingObject, $toData, $fromData);

	public function updateBudgetReappropriation(BudgetReappropriation $budgetingObject, $from_amount, $to_amount);

	public function updateBudgetReappropriationProposal($status,$id, $tableName);

	public function updateEditedBudgetReappropriation(BudgetReappropriation $budgetingObject);
	
	public function listBudgetReappropriation($columnName, $type, $organisation_id);
	
	public function findReappropriationBudgetTransactions($budgetType, $id);

	/**
	 * 
	 * @return array/ Budgeting[]
	 */
	 
	public function listSelectData($tableName, $columnName, $condition);
	
}
<?php

namespace HrdPlan\Service;

use HrdPlan\Model\HrdPlanProposal;
use HrdPlan\Model\HrdPlan;
use HrdPlan\Model\HrdPlanApproval;

interface HrdPlanServiceInterface
{
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|EmployeeDetailInterface[]
	*/
	
	public function listAllProposals($status, $organisation_id);

	/**
	 * Should return a single proposal
	 *
	 * @param int $id Identifier of the proposal that should be returned
	 * @return EmpWorkForceProposalInterface
	 */
	 
	public function findProposal($id);
        
        
	/**
	 * Should return a single proposal
	 *
	 * @param int $id Identifier of the Proposal that should be returned
	 * @return EmpWorkForceProposalInterface
	 */
        
     public function findProposalDetails($id);
	 
	 /**
	 * @param EmpWorkForceProposalInterface $empWorkForceProposalObject
	 *
	 * @param EmpWorkForceProposalInterface $empWorkForceProposalObject
	 * @return EmpWorkForceProposalInterface
	 * @throws \Exception
	 */
	 
	 public function save(HrdPlan $hrdPlan);
	 
	 /**
	 * @param EmpWorkForceProposalInterface $empWorkForceProposalObject
	 *
	 * @param EmpWorkForceProposalInterface $empWorkForceProposalObject
	 * @return EmpWorkForceProposalInterface
	 * @throws \Exception
	 */
	 
	 public function updateProposal(HrdPlanApproval $hrdPlan, $submitValue);
	 
	 public function updateHrdProposal($status, $previousStatus, $id, $organisation_id);
	
	/*
	* Delete HR Proposal by College HRO before submission to OVC
	*/
	
	public function deleteProposal($id);
	 
	/*
	* take username and returns employee details id/student id
	*/
	
	public function getUserDetailsId($username, $tableName);
	
	/*
	* take username and returns Name and any other detail required
	*/
	
	public function getUserDetails($username, $tableName);
	
	/*
	* Get the Organisation Id
	*/
	 
	public function getOrganisationId($username);
	
	/*
	* Get the employee details
	*/
	
	public function getEmployeeDetails($empId);
	
	/*
	* Get Five Year Plan
	*/
	
	public function getFiveYearPlan();
	
	/*
	* Get the dates for the proposal, i.e. whether the proposal is active or not
	*/
	
	public function getProposalDates($proposal_type);
	
	/**
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName, $organisation_id);
		
		
}
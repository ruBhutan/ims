<?php

namespace HrmPlan\Mapper;

use HrmPlan\Model\HrmPlan;
use HrmPlan\Model\HrmPlanApproval;

interface HrmPlanMapperInterface
{
	/**
	 * @param int/string $id
	 * @return EmpWorkForceProposal
	 * throws \InvalidArugmentException
	 * 
	*/
	
	public function find($id);

	/**
	 * 
	 * @return array/ EmpWorkForceProposal[]
	 */
	 
	public function findAll($status, $organisation_id);
        
	/**
	 * 
	 * @param type $id
	 * 
	 * to find details related to the work force proposal
	 */
	
	public function findDetails($id);
	
	/**
	 * 
	 * @param type $EmpWorkForceProposalInterface
	 * 
	 * to save work force proposals
	 */
	
	public function saveDetails(HrmPlan $hrmPlanInterface, $data);
	
	/**
	 * Update HRM Proposal
	 * 
	 */
	 
	public function updateProposal(HrmPlanApproval $hrmPlanModel, $submitValue);
	 
	public function updateHrmProposal($status, $previousStatus, $id, $organisation_id);
	 
	 /*
	* Delete HRM Proposal by College HRO before submission to OVC
	*/
	
	public function deleteHrmProposal($id);
	 
	 /*
	* take username and returns employee details id/student id
	*/
	
	public function getUserDetailsId($username, $tableName);
	
	/*
	* take username and returns Name and any other detail required
	*/
	
	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);
	
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
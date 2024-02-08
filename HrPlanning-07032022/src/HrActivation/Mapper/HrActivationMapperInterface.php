<?php

namespace HrActivation\Mapper;

use HrActivation\Model\HrActivation;
use HrActivation\Model\HrActivationApproval;

interface HrActivationMapperInterface
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
	 
	public function findAll();
	
	/**
	 * 
	 * @param type $EmpWorkForceProposalInterface
	 * 
	 * to save work force proposals
	 */
	
	public function saveDetails(HrActivation $HrActivationInterface);
		
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
	* Get Five Year Plan
	*/
	
	public function getFiveYearPlan();
	
	/**
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName, $organisation_id);
	
}
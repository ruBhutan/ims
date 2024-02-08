<?php

namespace HrActivation\Service;

use HrActivation\Model\HrActivation;

interface HrActivationServiceInterface
{
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|EmployeeDetailInterface[]
	*/
	
	public function listAllActivationDates();
        
	/**
	* Should return a single proposal
	*
	* @param int $id Identifier of the Proposal that should be returned
	* @return EmpWorkForceProposalInterface
	*/
        
    public function findActivationDate($id);
	 
	/**
	* @param EmpWorkForceProposalInterface $empWorkForceProposalObject
	*
	* @param EmpWorkForceProposalInterface $empWorkForceProposalObject
	* @return EmpWorkForceProposalInterface
	* @throws \Exception
	*/
	 
	public function save(HrActivation $hrActivation);
	 
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
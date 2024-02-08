<?php

namespace ExternalExaminer\Service;

use ExternalExaminer\Model\ExternalExaminer;

interface ExternalExaminerServiceInterface
{
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|EmployeeDetailInterface[]
	*/
	
	public function listExternalExaminers();
        
	/**
	* Should return a single proposal
	*
	* @param int $id Identifier of the Proposal that should be returned
	* @return EmpWorkForceProposalInterface
	*/
        
    public function findExternalExaminer($id);
	 
	/**
	* @param EmpWorkForceProposalInterface $empWorkForceProposalObject
	*
	* @param EmpWorkForceProposalInterface $empWorkForceProposalObject
	* @return EmpWorkForceProposalInterface
	* @throws \Exception
	*/
	 
	public function save(ExternalExaminer $externalExaminer, $form_data);

	public function updateExternalExaminer(ExternalExaminer $externalExaminerObject, $form_data);
	 
	/*
	* take username and returns employee details id/student id
	*/
	
	public function getUserDetailsId($username, $tableName);
	
	/*
	* Get the Organisation Id
	*/
	 
	public function getOrganisationId($username);

	public function getUserDetails($username, $usertype);

	public function getUserImage($username, $usertype);
	
	/*
	* Get the list of External Examiner after search
	*/
	
	public function getExternalExaminersList($data);

	public function getFileName($id);
	
	
	/**
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName);
		
		
}
<?php

namespace ExternalExaminer\Mapper;

use ExternalExaminer\Model\ExternalExaminer;
use ExternalExaminer\Model\ExternalExaminerApproval;

interface ExternalExaminerMapperInterface
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
	
	public function saveDetails(ExternalExaminer $ExternalExaminerInterface, $form_data);

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
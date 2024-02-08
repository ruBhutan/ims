<?php

namespace EmpTravelAuthorization\Mapper;

use EmpTravelAuthorization\Model\EmpTravelAuthorization;

interface EmpTravelAuthorizationMapperInterface
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
	
	public function saveDetails(EmpTravelAuthorization $empTravelAuthorizationInterface);
	
	public function listTravelEmployee($date);
	
	/*
	* Returns the Employee Details
	*/
	
	public function findEmployeeDetails($empIds);
	
	/*
	 * Get the Organisation Id
	 */
	 
	public function getOrganisationId($username);
	
	/*
	* take username and returns employee details id/student id
	*/
	
	public function getUserDetailsId($username, $tableName);
	
}
<?php

namespace EmpTravelAuthorization\Service;

use EmpTravelAuthorization\Model\EmpTravelAuthorization;

interface EmpTravelAuthorizationServiceInterface
{
	/**
	 * Should return a set of all Travels that we can iterate over. 
	 *
	 * @return array|EmployeeDetailInterface[]
	*/
	
	public function listAllTravels();
	
	public function listTravelEmployee($date);
	
	public function findEmployeeDetails($empIds);

	/**
	 * Should return a single Travel
	 *
	 * @param int $id Identifier of the Travel that should be returned
	 * @return EmpWorkForceTravelInterface
	 */
	 
	public function findTravel($id);
        
        
	/**
	 * Should return a single Travel
	 *
	 * @param int $id Identifier of the Travel that should be returned
	 * @return EmpWorkForceTravelInterface
	 */
        
     public function findTravelDetails($id);
	 
	 /**
	 * @param EmpWorkForceTravelInterface $empWorkForceTravelObject
	 *
	 * @param EmpWorkForceTravelInterface $empWorkForceTravelObject
	 * @return EmpWorkForceTravelInterface
	 * @throws \Exception
	 */
	 
	 public function save(EmpTravelAuthorization $empTravelAuthorizationObject);
	 
	 /*
	 * Get the Organisation Id
	 */
	 
	public function getOrganisationId($username);
	
	/*
	* take username and returns employee details id/student id
	*/
	
	public function getUserDetailsId($username, $tableName);

}
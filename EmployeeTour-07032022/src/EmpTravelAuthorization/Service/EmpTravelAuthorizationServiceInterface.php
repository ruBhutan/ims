<?php

namespace EmpTravelAuthorization\Service;

use EmpTravelAuthorization\Model\EmpTravelAuthorization;
use EmpTravelAuthorization\Model\EmpTravelDetails;
use EmpTravelAuthorization\Model\TravelPaymentDetails;

interface EmpTravelAuthorizationServiceInterface
{

	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);
	/**
	 * Should return a set of all Travels that we can iterate over. 
	 *
	 * @return array|EmployeeDetailInterface[]
	*/
	
	public function listAllTravels($status, $organisation_id, $userrole, $employee_details_id, $departments_id);
	
	public function listTravelEmployee($date, $organisation_id, $userrole, $employee_details_id);
	
	public function findEmployeeDetails($empIds);


	public function getEmployeeList($organisation_id);

	/**
	 * Should return a single Travel
	 *
	 * @param int $id Identifier of the Travel that should be returned
	 * @return EmpWorkForceTravelInterface
	 */
	 
	public function findTravel($id);

	public function getTourApprovingAuthority($id);

	public function findFromTravelDate($id);

	public function findToTravelDate($id);

	public function getEmployeeDetails($id);
        
        
	/**
	 * Should return a single Travel
	 *
	 * @param int $id Identifier of the Travel that should be returned
	 * @return EmpWorkForceTravelInterface
	 */
        
     public function findTravelDetails($id);

     public function findTravelOfficiating($id);

     public function listEmpApprovedTravels($order_no, $organisation_id);

     public function crossCheckAppliedTravelAuthorization($employee_details_id);
	 
	 /**
	 * @param EmpWorkForceTravelInterface $empWorkForceTravelObject
	 *
	 * @param EmpWorkForceTravelInterface $empWorkForceTravelObject
	 * @return EmpWorkForceTravelInterface
	 * @throws \Exception
	 */
	 
	 public function save(EmpTravelAuthorization $empTravelAuthorizationObject);


	 public function updateTravelAuthorization(EmpTravelAuthorization $empTravelAuthorizationObject);
	 
	 /*
	 * Function to save the Travel Details
	 */
	 
	 public function saveTravelDetails(EmpTravelDetails $empTravelDetails);
	 
	 /*
	 * Get Travel Details, given Travel Authorization $id
	 */
	 
	 public function getTravelDetails($id);


	 public function getSupervisorEmailId($userrole, $departments_units_id);

	 public function getTravelAuthNo($id);

	 public function getAdmEmailId($employee_details_id);

	 public function getRegistrarEmailId($employee_details_id);

	 public function getTourApplicant($employee_details_id);

	 public function getTravelApplicant($id);
	 
	 /*
	 * Delete Travel Details (given an id)
	 */
	 
	 public function deleteTravelDetails($id);

	 public function updateEmpTravelDetailStatus($status, $previousStatus, $id);

	 public function updateEmpTravelDetail($remarks, $status, $id, $organisation_id, $employee_details_id);

	 public function updateEmpTravelOrder($data, $id);

	 public function getFileName($travel_authorization_id, $column_name);
	 
	 /*
	 * Get Travel Authorization id
	 */
	 
	 public function getTravelAuthorizationId($id);
	 
	 /*
	 * Get the Organisation Id
	 */
	 
	public function getOrganisationId($username);
	
	/*
	* take username and returns employee details id/student id
	*/
	
	public function getUserDetailsId($username, $tableName);

	public function getApprovedTravelApplicantDetails($id);

	public function fetchPaymentTypes();
  
	public function saveTravelPaymentDetails(TravelPaymentDetails $travelPaymentDetails);
  
  	public function fetchTravelPaymentList($field, $id);
  
  	public function updateTravelAuthorizationStatus($id);
  
	public function deletePaymentDetails($id);

}
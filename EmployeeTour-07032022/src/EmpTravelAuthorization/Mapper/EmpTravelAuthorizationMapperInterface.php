<?php

namespace EmpTravelAuthorization\Mapper;

use EmpTravelAuthorization\Model\EmpTravelAuthorization;
use EmpTravelAuthorization\Model\EmpTravelDetails;
use EmpTravelAuthorization\Model\TravelPaymentDetails;

interface EmpTravelAuthorizationMapperInterface
{

	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);
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
	 
	public function findAll($status, $organisation_id, $userrole, $employee_details_id, $departments_id);

	public function getEmployeeDetails($id);
        
	/**
	 * 
	 * @param type $id
	 * 
	 * to find details related to the work force proposal
	 */
	
	public function findDetails($id);

	public function findTravelOfficiating($id);

	public function crossCheckAppliedTravelAuthorization($employee_details_id);

	public function getTourApprovingAuthority($id);

	public function findFromTravelDate($id);

	public function findToTravelDate($id);
	
	/**
	 * 
	 * @param type $EmpWorkForceProposalInterface
	 * 
	 * to save work force proposals
	 */
	
	public function saveDetails(EmpTravelAuthorization $empTravelAuthorizationInterface);

	public function updateTravelAuthorization(EmpTravelAuthorization $empTravelAuthorizationInterface);
	
	/*
	 * Function to save the Travel Details
	 */
	 
	public function saveTravelDetails(EmpTravelDetails $empTravelDetails);
	
	/*
	 * Get Travel Details, given Travel Authorization $id
	 */
	 
	public function getTravelDetails($id);

	public function listEmpApprovedTravels($order_no, $organisation_id);

	public function getSupervisorEmailId($userrole, $departments_units_id);

	public function getTravelAuthNo($id);

	public function getAdmEmailId($employee_details_id);

	public function getRegistrarEmailId($employee_details_id);

	public function getTourApplicant($employee_details_id);

	public function getTravelApplicant($id);
	
	public function listTravelEmployee($date, $organisation_id, $userrole, $employee_details_id);
	
	/*
	* Returns the Employee Details
	*/
	
	public function findEmployeeDetails($empIds);


	public function getEmployeeList($organisation_id);
	
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
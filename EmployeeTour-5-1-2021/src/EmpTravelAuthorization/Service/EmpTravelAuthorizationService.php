<?php

namespace EmpTravelAuthorization\Service;

use EmpTravelAuthorization\Mapper\EmpTravelAuthorizationMapperInterface;
use EmpTravelAuthorization\Model\EmpTravelAuthorization;
use EmpTravelAuthorization\Model\EmpTravelDetails;

class EmpTravelAuthorizationService implements EmpTravelAuthorizationServiceInterface
{
	/**
	 * @var \Blog\Mapper\PostMapperInterface
	*/
	
	protected $empTravelAuthorizationMapper;
	
	public function __construct(EmpTravelAuthorizationMapperInterface $empTravelAuthorizationMapper) {
		$this->empTravelAuthorizationMapper = $empTravelAuthorizationMapper;
	}

	public function getUserDetails($username, $usertype)
	{
		return $this->empTravelAuthorizationMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->empTravelAuthorizationMapper->getUserImage($username, $usertype);
	}
	
	public function listAllTravels($status, $organisation_id, $userrole, $employee_details_id, $departments_id)
	{
		return $this->empTravelAuthorizationMapper->findAll($status, $organisation_id, $userrole, $employee_details_id, $departments_id);
	}
	
	public function listTravelEmployee($date, $organisation_id, $userrole, $employee_details_id)
	{
		return $this->empTravelAuthorizationMapper->listTravelEmployee($date, $organisation_id, $userrole, $employee_details_id);
	}
	
	public function findEmployeeDetails($empIds)
	{
		return $this->empTravelAuthorizationMapper->findEmployeeDetails($empIds);
	}


	public function getEmployeeList($organisation_id)
	{
		return $this->empTravelAuthorizationMapper->getEmployeeList($organisation_id);
	}
	 
	public function findTravel($id)
	{
		return $this->empTravelAuthorizationMapper->find($id);
	}

	public function getTourApprovingAuthority($id)
	{
		return $this->empTravelAuthorizationMapper->getTourApprovingAuthority($id);
	}

	public function findFromTravelDate($id)
	{
		return $this->empTravelAuthorizationMapper->findFromTravelDate($id);
	}


	public function findToTravelDate($id)
	{
		return $this->empTravelAuthorizationMapper->findToTravelDate($id);
	}

	public function getEmployeeDetails($id)
	{
		return $this->empTravelAuthorizationMapper->getEmployeeDetails($id);
	}
        
	public function findTravelDetails($id) 
	{
		return $this->empTravelAuthorizationMapper->findDetails($id);;
	}

	public function findTravelOfficiating($id)
	{
		return $this->empTravelAuthorizationMapper->findTravelOfficiating($id);
	}

	public function crossCheckAppliedTravelAuthorization($employee_details_id)
	{
		return $this->empTravelAuthorizationMapper->crossCheckAppliedTravelAuthorization($employee_details_id);
	}
	
	public function save(EmpTravelAuthorization $empTravelAuthorization) 
	{
		return $this->empTravelAuthorizationMapper->saveDetails($empTravelAuthorization);
	}

	public function updateTravelAuthorization(EmpTravelAuthorization $empTravelAuthorization)
	{
		return $this->empTravelAuthorizationMapper->updateTravelAuthorization($empTravelAuthorization);
	}
	 
	public function saveTravelDetails(EmpTravelDetails $empTravelDetails)
	{
		return $this->empTravelAuthorizationMapper->saveTravelDetails($empTravelDetails);
	}
	 
	public function getTravelDetails($id)
	{
		return $this->empTravelAuthorizationMapper->getTravelDetails($id);
	}


	public function listEmpApprovedTravels($order_no, $organisation_id)
	{
		return $this->empTravelAuthorizationMapper->listEmpApprovedTravels($order_no, $organisation_id);
	}


	public function getSupervisorEmailId($userrole, $departments_units_id)
	{
		return $this->empTravelAuthorizationMapper->getSupervisorEmailId($userrole, $departments_units_id);
	}

	public function getTravelAuthNo($id)
	{
		return $this->empTravelAuthorizationMapper->getTravelAuthNo($id);
	}


	public function getAdmEmailId($employee_details_id)
	{
		return $this->empTravelAuthorizationMapper->getAdmEmailId($employee_details_id);
	}

	public function getRegistrarEmailId($employee_details_id)
	{
		return $this->empTravelAuthorizationMapper->getRegistrarEmailId($employee_details_id);
	}
	


	public function getTourApplicant($employee_details_id)
	{
		return $this->empTravelAuthorizationMapper->getTourApplicant($employee_details_id);
	}


	public function getTravelApplicant($id)
	{
		return $this->empTravelAuthorizationMapper->getTravelApplicant($id);
	}
	
	 
	public function deleteTravelDetails($id)
	{
		return $this->empTravelAuthorizationMapper->deleteTravelDetails($id);
	}

	public function updateEmpTravelDetailStatus($status, $previousStatus, $id)
	{
		return $this->empTravelAuthorizationMapper->updateEmpTravelDetailStatus($status, $previousStatus, $id);
	}


	public function updateEmpTravelDetail($remarks, $status, $id, $organisation_id, $employee_details_id)
	{
		return $this->empTravelAuthorizationMapper->updateEmpTravelDetail($remarks, $status, $id, $organisation_id, $employee_details_id);
	}


	public function updateEmpTravelOrder($data, $id)
	{
		return $this->empTravelAuthorizationMapper->updateEmpTravelOrder($data, $id);
	}

	public function getFileName($travel_authorization_id, $column_name)
	{
		return $this->empTravelAuthorizationMapper->getFileName($travel_authorization_id, $column_name);
	}
	 
	public function getTravelAuthorizationId($id)
	{
		return $this->empTravelAuthorizationMapper->getTravelAuthorizationId($id);
	}
		 
	public function getOrganisationId($username)
	{
		return $this->empTravelAuthorizationMapper->getOrganisationId($username);
	}
		
	public function getUserDetailsId($username, $tableName)
	{
		return $this->empTravelAuthorizationMapper->getUserDetailsId($username, $tableName);
	}

	public function getApprovedTravelApplicantDetails($id)
	{
		return $this->empTravelAuthorizationMapper->getApprovedTravelApplicantDetails($id);
	}

	
}
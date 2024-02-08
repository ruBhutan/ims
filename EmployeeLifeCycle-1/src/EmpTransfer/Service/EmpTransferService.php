<?php

namespace EmpTransfer\Service;

use EmpTransfer\Mapper\EmpTransferMapperInterface;
use EmpTransfer\Model\EmpTransfer;
use EmpTransfer\Model\OvcTransferApproval;
use EmpTransfer\Model\JoiningReport;

class EmpTransferService implements EmpTransferServiceInterface
{
	/**
	 * @var \Blog\Mapper\EmpTransferMapperInterface
	*/
	
	protected $transferMapper;
	
	public function __construct(EmpTransferMapperInterface $transferMapper) {
		$this->transferMapper = $transferMapper;
	}
	
	public function listAll($tableName)
	{
		return $this->transferMapper->findAll($tableName);
	}
	 
	public function getEmployeeDetailsId($id)
	{
		return $this->transferMapper->getEmployeeDetailsId($id);
	}
		 
	public function getOrganisationId($username)
	{
		return $this->transferMapper->getOrganisationId($username);
	}
	
	public function getUserDetails($username, $usertype)
	{
		return $this->transferMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->transferMapper->getUserImage($username, $usertype);
	}

	public function crossCheckEmpTransfer($employee_details_id, $fromStatus, $toStatus)
	{
		return $this->transferMapper->crossCheckEmpTransfer($employee_details_id, $fromStatus, $toStatus);
	}

	public function getEmployeeSpouseDetails($relationType, $employee_details_id)
	{
		return $this->transferMapper->getEmployeeSpouseDetails($relationType, $employee_details_id);
	}

	public function getTransferedEmpSpouseDetails($relationType, $id)
	{
		return $this->transferMapper->getTransferedEmpSpouseDetails($relationType, $id);
	}

	public function getEmpTransferFileName($application_id, $column_name)
	{
		return $this->transferMapper->getEmpTransferFileName($application_id, $column_name);
	}
	
	public function getTransferEmployee()
	{
		return $this->transferMapper->getTransferEmployee();
	}
        
	public function findTransferDetails($id) 
	{
		return $this->transferMapper->findTransferDetails($id);;
	}
	
	public function saveTransferApplicantDetails($data)
	{
		return $this->transferMapper->saveTransferApplicantDetails($data);
	}

	public function saveTransferedStaffDetails($data)
	{
		return $this->transferMapper->saveTransferedStaffDetails($data);
	}
	
	public function save(EmpTransfer $transferObject) 
	{
		return $this->transferMapper->save($transferObject);
	}
		 
	public function getTransferList($type, $organisation_id)
	{
		return $this->transferMapper->getTransferList($type, $organisation_id);
	}

	public function getTransferApprovalList($type, $organisation_id, $userrole)
	{
		return $this->transferMapper->getTransferApprovalList($type, $organisation_id, $userrole);
	}
		
	public function getPersonalDetails($employee_id)
	{
		return $this->transferMapper->getPersonalDetails($employee_id);
	}

	public function getSupervisorEmailId($userrole, $departments_units_id)
	{
		return $this->transferMapper->getSupervisorEmailId($userrole, $departments_units_id);
	}

	public function getAuthorizeeEmailId($to_organisation)
	{
		return $this->transferMapper->getAuthorizeeEmailId($to_organisation);
	}

	public function getOrganisation($organisation_id)
	{
		return $this->transferMapper->getOrganisation($organisation_id);
	}
		
	public function getEmploymentDetails($employee_id)
	{
		return $this->transferMapper->getEmploymentDetails($employee_id);
	}
		
	public function getTransferApplicantDetail($id)
	{
		return $this->transferMapper->getTransferApplicantDetail($id);
	}

	public function listAllEmployees($organisation_id)
	{
		return $this->transferMapper->listAllEmployees($organisation_id);
	}

	public function getEmployeeList($empName, $empId, $department, $organisation_id)
	{
		return $this->transferMapper->getEmployeeList($empName, $empId, $department, $organisation_id);
	}
		
	public function updateTransferStatus($id, $status, $type)
	{
		return $this->transferMapper->updateTransferStatus($id, $status, $type);
	}

	public function getTransferRequestAgency($id)
	{
		return $this->transferMapper->getTransferRequestAgency($id);
	}
	
	public function saveOvcTransferApproval(OvcTransferApproval $transferObject)
	{
		return $this->transferMapper->saveOvcTransferApproval($transferObject);
	}
	
	public function saveJoiningReport(JoiningReport $reportObject)
	{
		return $this->transferMapper->saveJoiningReport($reportObject);
	}
		
	public function getNotificationDetails($organisation_id)
	{
		return $this->transferMapper->getNotificationDetails($organisation_id);
	}
	
	public function listSelectData($tableName, $columnName)
	{
		return $this->transferMapper->listSelectData($tableName, $columnName);
	}
	
}
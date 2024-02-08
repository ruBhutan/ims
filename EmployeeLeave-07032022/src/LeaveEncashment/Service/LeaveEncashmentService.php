<?php

namespace LeaveEncashment\Service;

use LeaveEncashment\Mapper\LeaveEncashmentMapperInterface;
use LeaveEncashment\Model\LeaveEncashment;

class LeaveEncashmentService implements LeaveEncashmentServiceInterface
{
	/**
	 * @var \Blog\Mapper\LeaveEncashmentMapperInterface
	*/
	
	protected $leaveMapper;
	
	public function __construct(LeaveEncashmentMapperInterface $leaveMapper) {
		$this->leaveMapper = $leaveMapper;
	}
		 
	public function getOrganisationId($username)
	{
		return $this->leaveMapper->getOrganisationId($username);
	}
		
	public function getUserDetailsId($username)
	{
		return $this->leaveMapper->getUserDetailsId($username);
	}
	
	public function getUserDetails($username, $usertype)
	{
		return $this->leaveMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->leaveMapper->getUserImage($username, $usertype);
	}
	
	public function listAll($tableName, $organisation_id, $employee_details_id)
	{
		return $this->leaveMapper->findAll($tableName, $organisation_id, $employee_details_id);
	}
	 
	public function findEmployeeId($id)
	{
		return $this->leaveMapper->findEmployeeId($id);
	}
	
	public function findEmployeeDetails($tableName, $id)
	{
		return $this->leaveMapper->findEmployeeDetails($tableName, $id);
	}
    	
	public function save(LeaveEncashment $leaveObject) 
	{
		return $this->leaveMapper->save($leaveObject);
	}
		  
	public function getLeaveBalance($employee_details_id)
	{
		return $this->leaveMapper->getLeaveBalance($employee_details_id);
	}
	
	public function getLeaveEncashed($employee_details_id)
	{
		return $this->leaveMapper->getLeaveEncashed($employee_details_id);
	}

	public function crossCheckLeaveEncashment($employee_details_id)
	{
		return $this->leaveMapper->crossCheckLeaveEncashment($employee_details_id);
	}

	public function crossCheckApprovedLeaveEncashment($employee_details_id)
	{
		return $this->leaveMapper->crossCheckApprovedLeaveEncashment($employee_details_id);
	}
		  
	public function getLeaveEncashment($status, $employee_details_id ,$organisation_id, $userrole, $departments_id)
	{
		return $this->leaveMapper->getLeaveEncashment($status, $employee_details_id ,$organisation_id, $userrole, $departments_id);
	}
	
	public function getLeaveEncashmentStatus($id, $authority)
	{
		return $this->leaveMapper->getLeaveEncashmentStatus($id, $authority);
	}


	public function getSupervisorEmailId($userrole, $departments_units_id)
	{
		return $this->leaveMapper->getSupervisorEmailId($userrole, $departments_units_id);
	}


	public function getLeaveEncashmentApplicant($employee_details_id)
	{
		return $this->leaveMapper->getLeaveEncashmentApplicant($employee_details_id);
	}

	public function listEmpApprovedLeaveEncashment($order_no, $organisation_id)
	{
		return $this->leaveMapper->listEmpApprovedLeaveEncashment($order_no, $organisation_id);
	}

	public function getEmployeeDetails($id)
	{
		return $this->leaveMapper->getEmployeeDetails($id);
	}

	public function getLeaveEncashmentDetails($id)
	{
		return $this->leaveMapper->getLeaveEncashmentDetails($id);
	}
	
	public function getFileName($id, $column_name)
	{
		return $this->leaveMapper->getFileName($id, $column_name);
	}
	
	public function updateLeaveEncashment($id, $status, $employee_details_id)
	{
		return $this->leaveMapper->updateLeaveEncashment($id, $status, $employee_details_id);
	}

	public function updateEmpLeaveEncashmentOrder($data, $id)
	{
		return $this->leaveMapper->updateEmpLeaveEncashmentOrder($data, $id);
	}
	 	
	public function listSelectData($tableName, $columnName)
	{
		return $this->leaveMapper->listSelectData($tableName, $columnName);
	}
	
}
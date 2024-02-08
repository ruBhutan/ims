<?php

namespace EmployeeLeave\Service;

use EmployeeLeave\Mapper\EmployeeLeaveMapperInterface;
use EmployeeLeave\Model\EmployeeLeave;
use EmployeeLeave\Model\OnbehalfEmployeeLeave;
use EmployeeLeave\Model\OfficiatingSupervisor;
use EmployeeLeave\Model\CancelledLeave;

class EmployeeLeaveService implements EmployeeLeaveServiceInterface
{
	/**
	 * @var \Blog\Mapper\EmployeeLeaveMapperInterface
	*/
	
	protected $leaveMapper;
	
	public function __construct(EmployeeLeaveMapperInterface $leaveMapper) {
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
	
	public function listAll($tableName)
	{
		return $this->leaveMapper->findAll($tableName);
	}
	
	public function listAllLeave($status, $employee_details_id, $userrole, $organisation_id, $departments_id)
	{
		return $this->leaveMapper->findAllLeave($status, $employee_details_id, $userrole, $organisation_id, $departments_id);
	}
	
	public function listLeaveEmployee($role, $organisation_id)
	{
		return $this->leaveMapper->listLeaveEmployee($role, $organisation_id);
	}

	public function getEmpApprovedLeaveList($organisation_id)
	{
		return $this->leaveMapper->getEmpApprovedLeaveList($organisation_id);
	}

	public function getSupervisorEmailId($userrole, $departments_units_id, $emp_leave_category_id)
	{
		return $this->leaveMapper->getSupervisorEmailId($userrole, $departments_units_id, $emp_leave_category_id);
	}

	public function getLeaveApplicant($employee_details_id)
	{
		return $this->leaveMapper->getLeaveApplicant($employee_details_id);
	}


	public function getApprovedLeaveApplicantDetails($id)
	{
		return $this->leaveMapper->getApprovedLeaveApplicantDetails($id);
	}


	public function getPresidentDetails($organisation_id)
	{
		return $this->leaveMapper->getPresidentDetails($organisation_id);
	}


	public function getApprovedLeaveSubstitution($id)
	{
		return $this->leaveMapper->getApprovedLeaveSubstitution($id);
	}


	public function getApplicantName($id)
	{
		return $this->leaveMapper->getApplicantName($id);
	}

	public function getOnBehalfStaffDetails($employee_details_id)
	{
		return $this->leaveMapper->getOnBehalfStaffDetails($employee_details_id);
	}
	
	public function findEmployeeId($id)
	{
		return $this->leaveMapper->findEmployeeId($id);
	}
	
	public function findEmployeeDetails($empIds)
	{
		return $this->leaveMapper->findEmployeeDetails($empIds);
	}
        
	public function findLeaveType($id) 
	{
		return $this->leaveMapper->findLeaveType($id);;
	}
	
	public function findLeave($id) 
	{
		return $this->leaveMapper->findLeave($id);;
	}
	
	public function listLeaveCategory()
	{
		return $this->leaveMapper->listLeaveCategory();
	}

	public function getEmployeeOccupationalGroup($employee_details_id)
	{
		return $this->leaveMapper->getEmployeeOccupationalGroup($employee_details_id);
	}
	
	public function updateLeave($id, $leaveStatus, $remarks, $employee_details_id)
	{
		return $this->leaveMapper->updateLeave($id, $leaveStatus, $remarks, $employee_details_id);
	}


	public function updateEmpApprovedLeave(CancelledLeave $leaveObject)
	{
		return $this->leaveMapper->updateEmpApprovedLeave($leaveObject);
	}
	
	public function save(EmployeeLeave $leaveObject) 
	{
		return $this->leaveMapper->saveDetails($leaveObject);
	}

	public function saveOnBehalfLeave(OnbehalfEmployeeLeave $leaveObject)
	{
		return $this->leaveMapper->saveOnBehalfLeave($leaveObject);
	}
	
	public function saveOfficiatingOfficer(OfficiatingSupervisor $leaveObject, $supervisor_id, $employee_details_id, $userrole)
	{
		return $this->leaveMapper->saveOfficiatingOfficer($leaveObject, $supervisor_id, $employee_details_id, $userrole);
	}

	public function updateEmpLeaveBalance($id, $casual_leave, $earned_leave, $annual_leave, $employee_details_id)
	{
		return $this->leaveMapper->updateEmpLeaveBalance($id, $casual_leave, $earned_leave, $annual_leave, $employee_details_id);
	}

	public function getEmpOfficiatedRole($officiating, $from_date, $to_date, $userrole)
	{
		return $this->leaveMapper->getEmpOfficiatedRole($officiating, $from_date, $to_date, $userrole);
	}


	public function crossCheckCancelledLeave($emp_leave_id)
	{
		return $this->leaveMapper->crossCheckCancelledLeave($emp_leave_id);
	}

	public function crossCheckOwnOfficiating($employee_details_id, $from_date)
	{
		return $this->leaveMapper->crossCheckOwnOfficiating($employee_details_id, $from_date);
	}
	 
	public function getOfficiatingList($employee_details_id)
	{
		return $this->leaveMapper->getOfficiatingList($employee_details_id);
	}
	 
	public function getOfficiatingDetails($id)
	{
		return $this->leaveMapper->getOfficiatingDetails($id);
	}
		 
	public function getEmployeeList($organisation_id)
	{
		return $this->leaveMapper->getEmployeeList($organisation_id);
	}
		 
	public function getLeaveTaken($employee_details_id, $type)
	{
		return $this->leaveMapper->getLeaveTaken($employee_details_id, $type);
	}
		 
	public function getLeaveBalance($employee_details_id)
	{
		return $this->leaveMapper->getLeaveBalance($employee_details_id);
	}

	public function getLeaveCategory($emp_leave_category_id)
	{
		return $this->leaveMapper->getLeaveCategory($emp_leave_category_id);
	}


	public function crossCheckAppliedLeave($employee_details_id)
	{
		return $this->leaveMapper->crossCheckAppliedLeave($employee_details_id);
	}
	
	public function getStaffAppliedLeave($employee_details_id, $emp_leave_category_id, $status)
	{
		return $this->leaveMapper->getStaffAppliedLeave($employee_details_id, $emp_leave_category_id, $status);
	}


	public function getEmployeeDetails($id)
	{
		return $this->leaveMapper->getEmployeeDetails($id);
	}

	public function getEmpLeaveBalanceDetails($id)
	{
		return $this->leaveMapper->getEmpLeaveBalanceDetails($id);
	}
	 
	public function getNotificationDetails($id, $role, $departments_id)
	{
		return $this->leaveMapper->getNotificationDetails($id, $role, $departments_id);
	}

	public function getEmployeeLeaveDetails($organisation_id)
	{
		return $this->leaveMapper->getEmployeeLeaveDetails($organisation_id);
	}
	 
	public function getFileName($leave_id)
	{
		return $this->leaveMapper->getFileName($leave_id);
	}

	public function getOfficiatingFileName($id)
	{
		return $this->leaveMapper->getOfficiatingFileName($id);
	}
	 
	public function listSelectData($tableName, $columnName)
	{
		return $this->leaveMapper->listSelectData($tableName, $columnName);
	}
	
}
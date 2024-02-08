<?php

namespace EmployeeTask\Service;

use EmployeeTask\Mapper\EmployeeTaskMapperInterface;
use EmployeeTask\Model\EmployeeTask;
use EmployeeTask\Model\EmployeeTaskCategory;

class EmployeeTaskService implements EmployeeTaskServiceInterface
{
	/**
	 * @var \Blog\Mapper\EmployeeTaskMapperInterface
	*/
	
	protected $employeetaskMapper;
	
	public function __construct(EmployeeTaskMapperInterface $employeetaskMapper) {
		$this->employeetaskMapper = $employeetaskMapper;
	}
	
	public function getOrganisationId($username)
	{
		return $this->employeetaskMapper->getOrganisationId($username);
	}
	 	
	public function getUserDetailsId($username)
	{
		return $this->employeetaskMapper->getUserDetailsId($username);
	}

	public function getUserDetails($username, $usertype)
	{
		return $this->employeetaskMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->employeetaskMapper->getUserImage($username, $usertype);
	}
	
	public function listAll($tableName, $organisation_id, $employee_details_id)
	{
		return $this->employeetaskMapper->findAll($tableName, $organisation_id, $employee_details_id);
	}

	public function listAll1($employee_details_id)
	{
		return $this->employeetaskMapper->findAll1($employee_details_id);
	}
	 
	public function findStaff($id)
	{
		return $this->employeetaskMapper->findStaff($id);
	}
        	
	public function saveCategory(EmployeeTaskCategory $employeetaskObject) 
	{
		return $this->employeetaskMapper->saveDetails($employeetaskObject);
	}
		
	public function saveEmployeeTaskRecord(EmployeeTask $employeetaskObject)
	{
		return $this->employeetaskMapper->saveEmployeeTaskRecord($employeetaskObject);
	}
	
	public function getStaffList($staffName, $staffId, $organisation_id)
	{
		return $this->employeetaskMapper->getStaffList($staffName, $staffId, $organisation_id);
	}
		
	public function getStaffEmployeeTaskList($staffName, $staffId, $organisation_id)
	{
		return $this->employeetaskMapper->getStaffEmployeeTaskList($staffName, $staffId, $organisation_id);
	}
	
	public function getStaffDetails($id)
	{
		return $this->employeetaskMapper->getStaffDetails($id);
	}
	
	public function getEmployeeTaskCategoryDetails($id)
	{
		return $this->employeetaskMapper->getEmployeeTaskCategoryDetails($id);
	}
	public function getEmployeeTaskRecordDetails($id)
	{
		return $this->employeetaskMapper->getEmployeeTaskRecordDetails($id);
	}
		
	public function getStaffEmployeeTaskRecords($staff_id)
	{
		return $this->employeetaskMapper->getStaffEmployeeTaskRecords($staff_id);
	}
	
	public function getEmployeeTaskRecord($organisation_id)
	{
		return $this->employeetaskMapper->getEmployeeTaskRecord($organisation_id);
	}
		
	public function listSelectData($tableName, $columnName, $organisation_id, $id)
	{
		return $this->employeetaskMapper->listSelectData($tableName, $columnName, $organisation_id, $id);
	}

	public function listSelectData1($tableName, $id)
	{
		return $this->employeetaskMapper->listSelectData1($tableName, $id);
	}

	public function getFileName($file_id)
	{
		return $this->employeetaskMapper->getFileName($file_id);
	}

	public function getstafftaskRecord($staff_id,$from_date, $to_date)
	{
		return $this->employeetaskMapper->getstafftaskRecord($staff_id,$from_date, $to_date);
	}
	
}
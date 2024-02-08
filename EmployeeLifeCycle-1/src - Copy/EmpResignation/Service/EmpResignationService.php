<?php

namespace EmpResignation\Service;

use EmpResignation\Mapper\EmpResignationMapperInterface;
use EmpResignation\Model\EmpResignation;
use EmpResignation\Model\Dues;
use EmpResignation\Model\Separation;
use EmpResignation\Model\SeparationRecord;

class EmpResignationService implements EmpResignationServiceInterface
{
	/**
	 * @var \Blog\Mapper\EmpResignationMapperInterface
	*/
	
	protected $resignationMapper;
	
	public function __construct(EmpResignationMapperInterface $resignationMapper) {
		$this->resignationMapper = $resignationMapper;
	}
	
	public function listAll($userrole, $tableName, $organisation_id, $status)
	{
		return $this->resignationMapper->findAll($userrole, $tableName, $organisation_id, $status);
	}
	 
	public function getEmployeeDetailsId($emp_id)
	{
		return $this->resignationMapper->getEmployeeDetailsId($emp_id);
	}
	
	public function getOrganisationId($username)
	{
		return $this->resignationMapper->getOrganisationId($username);
	}
	
	public function getUserDetails($username, $usertype)
	{
		return $this->resignationMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->resignationMapper->getUserImage($username, $usertype);
	}
	
	public function getResigningEmployee($organisation_id)
	{
		return $this->resignationMapper->getResigningEmployee($organisation_id);
	}

	public function getEmployeeList($empName, $empId, $department, $organisation_id)
	{
		return $this->resignationMapper->getEmployeeList($empName, $empId, $department, $organisation_id);
	}


	public function listAllEmployees($organisation_id)
	{
		return $this->resignationMapper->listAllEmployees($organisation_id);
	}


	public function getResignedEmpDetails($id)
	{
		return $this->resignationMapper->getResignedEmpDetails($id);
	}
		
	public function getDueClearance($organisation_id)
	{
		return $this->resignationMapper->getDueClearance($organisation_id);
	}
		
	public function getEmpGoods($id, $categoryType)
	{
		return $this->resignationMapper->getEmpGoods($id, $categoryType);
	}
        
	public function getResignationDetails($id) 
	{
		return $this->resignationMapper->getResignationDetails($id);;
	}

	public function getResignationApplicationDetails($id)
	{
		return $this->resignationMapper->getResignationApplicationDetails($id);
	}
	 
	public function getEmployeeResigningDetails($employee_id)
	{
		return $this->resignationMapper->getEmployeeResigningDetails($employee_id);
	}

	public function getSeparationRecordFile($id)
	{
		return $this->resignationMapper->getSeparationRecordFile($id);
	}

	public function getSeparationRecordDetails($tableName, $id)
	{
		return $this->resignationMapper->getSeparationRecordDetails($tableName, $id);
	}

	public function crossCheckResignationApplication($employee_details_id, $status)
	{
		return $this->resignationMapper->crossCheckResignationApplication($employee_details_id, $status);
	}
	
	public function save(EmpResignation $resignationObject) 
	{
		return $this->resignationMapper->save($resignationObject);
	}

	public function deleteEmployeeResignation($id)
	{
		return $this->resignationMapper->deleteEmployeeResignation($id);
	}
		 
	public function saveSeparationRecord(SeparationRecord $resignationObject)
	{
		return $this->resignationMapper->saveSeparationRecord($resignationObject);
	}
		 
	public function saveDueClearance(Dues $resignationModel)
	{
		return $this->resignationMapper->saveDueClearance($resignationModel);
	}
		 
	public function getAuthorisingRole($type, $organisation_id)
	{
		return $this->resignationMapper->getAuthorisingRole($type, $organisation_id);
	}

	public function getSeparationRecordList($tableName, $organisation_id)
	{
		return $this->resignationMapper->getSeparationRecordList($tableName, $organisation_id);
	}
	
	public function getNotificationDetails($organisation_id)
	{
		return $this->resignationMapper->getNotificationDetails($organisation_id);
	}
	 
	public function updateResignationStatus($id, $status)
	{
		return $this->resignationMapper->updateResignationStatus($id, $status);
	}
	
	public function listSelectData($tableName, $columnName)
	{
		return $this->resignationMapper->listSelectData($tableName, $columnName);
	}
	
}
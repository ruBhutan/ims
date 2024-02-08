<?php

namespace StudentLeave\Service;

use StudentLeave\Mapper\StudentLeaveMapperInterface;
use StudentLeave\Model\StudentLeave;
use StudentLeave\Model\StudentLeaveCategory;

class StudentLeaveService implements StudentLeaveServiceInterface
{
	/**
	 * @var \Blog\Mapper\StudentLeaveMapperInterface
	*/
	
	protected $leaveMapper;
	
	public function __construct(StudentLeaveMapperInterface $leaveMapper) {
		$this->leaveMapper = $leaveMapper;
	}

	public function getUserDetailsId($username, $tableName)
	{
		return $this->leaveMapper->getUserDetailsId($username, $tableName);
	}

	public function getOrganisationId($username, $usertype)
	{
		return $this->leaveMapper->getOrganisationId($username, $usertype);
	}

	public function getUserDetails($username, $usertype)
	{
		return $this->leaveMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->leaveMapper->getUserImage($username, $usertype);
	}
	
	public function listAll($tableName, $organisation_id)
	{
		return $this->leaveMapper->findAll($tableName, $organisation_id);
	}
	 
	public function findStudentLeave($id)
	{
		return $this->leaveMapper->findStudentLeave($id);
	}

	public function findLeave($id)
	{
		return $this->leaveMapper->findLeave($id);
	}


	public function getLeaveCategory($leave_category_id)
	{
		return $this->leaveMapper->getLeaveCategory($leave_category_id);
	}

	public function getIndividualAppliedLeaveList($student_id)
	{
		return $this->leaveMapper->getIndividualAppliedLeaveList($student_id);
	}


	public function getFileName($leave_id)
	{
		return $this->leaveMapper->getFileName($leave_id);
	}
        
	public function findLeaveCategory($id) 
	{
		return $this->leaveMapper->findLeaveCategory($id);;
	}

	public function crossCheckStdLeaveCategory($leave_category, $organisation_id, $id)
	{
		return $this->leaveMapper->crossCheckStdLeaveCategory($leave_category, $organisation_id, $id);
	}

	public function getAppliedLeaveCategory($leave_category, $organisation_id)
	{
		return $this->leaveMapper->getAppliedLeaveCategory($leave_category, $organisation_id);
	}

	public function checkStudentHostelAllocation($student_id)
	{
		return $this->leaveMapper->checkStudentHostelAllocation($student_id);
	}

	public function getAppliedLeaveList($student_id)
	{
		return $this->leaveMapper->getAppliedLeaveList($student_id);
	}

	public function getAppliedLastDate($student_id)
	{
		return $this->leaveMapper->getAppliedLastDate($student_id);
	}

	public function getSemesterDuration($organisation_id)
	{
		return $this->leaveMapper->getSemesterDuration($organisation_id);
	}

	public function listAllLeave($status, $employee_details_id, $userrole, $organisation_id)
	{
		return $this->leaveMapper->listAllLeave($status, $employee_details_id, $userrole, $organisation_id);
	}
	
	public function save(StudentLeave $leaveObject) 
	{
		return $this->leaveMapper->saveDetails($leaveObject);
	}
	
	public function saveLeaveCategory(StudentLeaveCategory $leaveObject)
	{
		return $this->leaveMapper->saveLeaveCategory($leaveObject);
	}

	public function updateLeave($id, $leaveStatus, $remarks, $employee_details_id)
	{
		return $this->leaveMapper->updateLeave($id, $leaveStatus, $remarks, $employee_details_id);
	}
	
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		return $this->leaveMapper->listSelectData($tableName, $columnName, $organisation_id);
	}
	
}
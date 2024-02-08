<?php
namespace StudentLeave\Mapper;

use StudentLeave\Model\StudentLeave;
use StudentLeave\Model\StudentLeaveCategory;

interface StudentLeaveMapperInterface
{
	/**
	 * @param int/string $id
	 * @return StudentLeave
	 * throws \InvalidArugmentException
	 * 
	*/

	public function getUserDetailsId($username, $tableName);

	public function getOrganisationId($username, $usertype);

	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);
	
	public function findLeaveCategory($id);

	public function crossCheckStdLeaveCategory($leave_category, $organisation_id, $id);

	public function getAppliedLeaveCategory($leave_category, $organisation_id);

	public function checkStudentHostelAllocation($student_id);

	public function getAppliedLeaveList($student_id);

	public function getAppliedLastDate($student_id);

	public function getSemesterDuration($organisation_id);

	public function listAllLeave($status, $employee_details_id, $userrole, $organisation_id);

	/**
	 * 
	 * @return array/ StudentLeave[]
	 */
	 
	public function findAll($tableName, $organisation_id);
        
	/**
	 * 
	 * @param type $id
	 * 
	 * to find details related to the Student Leave
	 */
	
	public function findStudentLeave($id);

	public function findLeave($id);

	public function getFileName($leave_id);
	
	/**
	 * 
	 * @param type $StudentLeaveInterface
	 * 
	 * to save budgetings
	 */
	
	public function saveDetails(StudentLeave $StudentLeaveInterface);
	
	/**
	 * @param StudentLeaveInterface $leaveObject
	 *
	 * @param StudentLeaveInterface $leaveObject
	 * @return StudentLeaveInterface
	 * @throws \Exception
	 */
	 
	 public function saveLeaveCategory(StudentLeaveCategory $leaveObject);

	 public function updateLeave($id, $leaveStatus, $remarks, $employee_details_id);
		
	/**
	 * 
	 * @return array/ StudentLeave[]
	 */
	 
	public function listSelectData($tableName, $columnName, $organisation_id);
	
}
<?php

namespace StudentLeave\Service;

use StudentLeave\Model\StudentLeave;
use StudentLeave\Model\StudentLeaveCategory;

//need to add more models

interface StudentLeaveServiceInterface
{
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|StudentLeaveInterface[]
	*/

	public function getUserDetailsId($username, $tableName);

	public function getOrganisationId($username, $usertype);

	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);
	
	public function listAll($tableName, $organisation_id);

	/**
	 * Should return a single proposal
	 *
	 * @param int $id Identifier of the proposal that should be returned
	 * @return StudentLeaveInterface
	 */
	 
	public function findStudentLeave($id);

	public function findLeave($id);

	public function getLeaveCategory($leave_category_id);

	public function getIndividualAppliedLeaveList($student_id);

	public function getFileName($leave_id);
        
        
	/**
	 * Should return a single proposal
	 *
	 * @param int $id Identifier of the Proposal that should be returned
	 * @return StudentLeaveInterface
	 */
        
     public function findLeaveCategory($id);

     public function crossCheckStdLeaveCategory($leave_category, $organisation_id, $id);

     public function getAppliedLeaveCategory($leave_category, $organisation_id);

     public function checkStudentHostelAllocation($student_id);

     public function getAppliedLeaveList($student_id);

     public function getAppliedLastDate($student_id);

     public function getSemesterDuration($organisation_id);

     public function listAllLeave($status, $employee_details_id, $userrole, $organisation_id);
	 
	 /**
	 * @param StudentLeaveInterface $leaveObject
	 *
	 * @param StudentLeaveInterface $leaveObject
	 * @return StudentLeaveInterface
	 * @throws \Exception
	 */
	 
	 public function save(StudentLeave $leaveObject);
	 
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
	 * Should return a set of all objectives that we can iterate over. 
	 * 
	 * The purpose of the function is the objectives for the dropdown select list
	 *
	 * @return array|StudentLeaveInterface[]
	*/
	
	public function listSelectData($tableName, $columnName, $organisation_id);
		
		
}
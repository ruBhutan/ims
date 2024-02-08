<?php

namespace StaffPortal\Service;

use StaffPortal\Model\StaffPortal;
use StaffPortal\Model\StaffDetail;

//need to add more models

interface StaffPortalServiceInterface
{
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|StaffPortalInterface[]
	*/
	
	public function listAll($tableName);
	
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username);
	
	/*
	* Get organisation id based on the username
	*/
	
	public function getOrganisationId($username);

	public function getDeptUnitId($username);

	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);

	public function getStaffPersonalDetails($employee_details_id);

	public function getStaffPermanentAddress($employee_details_id);

	public function getStaffDetails($employee_details_id);

	public function getEmpLastLeaveDetails($employee_details_id);

	public function getEmpRelationDetails($employee_details_id);

	public function getEmpType($employee_details_id);

	public function getEmpCurrentPosition($employee_details_id);

	public function getEmpPositionLevel($employee_details_id);

	public function getEmpDeptUnit($employee_details_id);

	public function getEmpPublication($employee_details_id);

	public function getEmpWorkExperience($employee_details_id, $working_agency_type);

	public function getEmpEducationDetails($employee_details_id);

	public function getEmpAwardDetails($employee_details_id);

	public function getEmpCommunityServiceDetails($employee_details_id);

	public function getEmpContributionDetails($employee_details_id);

	public function getEmpResponsibilityDetails($employee_details_id);

	public function getEmpTrainingDetails($employee_details_id);

	public function getPendingLeaveList($employee_details_id);

	public function getApprovedLeaveList($employee_details_id);

	public function getRejectedLeaveList($employee_details_id);

	public function getStaffRejectedLeaveStatus($id);

	public function getStaffTourList($employee_details_id);

	public function getApprovedTourList($employee_details_id);

	public function getRejectedTourList($employee_details_id);

	public function deleteStaffPendingTour($id);

	public function getStaffTourAuthDetails($id);

	public function getTourApprovingAuthority($id);
	
	public function findFromTravelDate($id);
	
	public function findToTravelDate($id);

	public function getStaffTourDetails($id);

	public function getStaffJobApplicataionList($employee_details_id);

	public function getStaffPromotionDetails($employee_details_id);


	public function getStaffResignationDetails($employee_details_id);


	public function getStaffTransferDetails($employee_details_id);

	public function getStaffAttendance($from_date, $to_date, $employee_details_id);


	public function getAttendanceRecordDates($from_date, $to_date, $departments_units_id);

	public function getAbsenteeList($from_date, $to_date, $employee_details_id);

	public function getWeekends($from_date, $to_date);

	public function getStaffLeaveEncashmentDetails($employee_details_id);

	//public function getStudentList($stdName, $stdId, $stdCid, $stdProgramme, $organisation_id);   
        
		
}
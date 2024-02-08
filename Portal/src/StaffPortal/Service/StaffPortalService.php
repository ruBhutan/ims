<?php

namespace StaffPortal\Service;

use StaffPortal\Mapper\StaffPortalMapperInterface;
use StaffPortal\Model\StaffPortal;
use StaffPortal\Model\StaffDetail;

class StaffPortalService implements StaffPortalServiceInterface
{
	/**
	 * @var \Blog\Mapper\JobPortalMapperInterface
	*/
	
	protected $staffMapper;
	
	public function __construct(StaffPortalMapperInterface $staffMapper) {
		$this->staffMapper = $staffMapper;
	}
	
	public function listAll($tableName)
	{
		return $this->staffMapper->findAll($tableName);
	}
	
	public function getUserDetailsId($username)
	{
		return $this->staffMapper->getUserDetailsId($username);
	}
	
	public function getOrganisationId($username)
	{
		return $this->staffMapper->getOrganisationId($username);
	}

	public function getDeptUnitId($username)
	{
		return $this->staffMapper->getDeptUnitId($username);
	}

	public function getUserDetails($username, $usertype)
	{
		return $this->staffMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->staffMapper->getUserImage($username, $usertype);
	}


	public function getStaffPersonalDetails($employee_details_id)
	{
		return $this->staffMapper->getStaffPersonalDetails($employee_details_id);
	}

	public function getStaffPermanentAddress($employee_details_id)
	{
		return $this->staffMapper->getStaffPermanentAddress($employee_details_id);
	}

	public function getStaffDetails($employee_details_id)
	{
		return $this->staffMapper->getStaffDetails($employee_details_id);
	}

	public function getEmpLastLeaveDetails($employee_details_id)
	{
		return $this->staffMapper->getEmpLastLeaveDetails($employee_details_id);
	}

	public function getEmpRelationDetails($employee_details_id)
	{
		return $this->staffMapper->getEmpRelationDetails($employee_details_id);
	}

	public function getEmpType($employee_details_id)
	{
		return $this->staffMapper->getEmpType($employee_details_id);
	}

	public function getEmpCurrentPosition($employee_details_id)
	{
		return $this->staffMapper->getEmpCurrentPosition($employee_details_id);
	}

	public function getEmpPositionLevel($employee_details_id)
	{
		return $this->staffMapper->getEmpPositionLevel($employee_details_id);
	}


	public function getEmpDeptUnit($employee_details_id)
	{
		return $this->staffMapper->getEmpDeptUnit($employee_details_id);
	}

	public function getEmpPublication($employee_details_id)
	{
		return $this->staffMapper->getEmpPublication($employee_details_id);
	}


	public function getEmpWorkExperience($employee_details_id, $working_agency_type)
	{
		return $this->staffMapper->getEmpWorkExperience($employee_details_id, $working_agency_type);
	}

	public function getEmpEducationDetails($employee_details_id)
	{
		return $this->staffMapper->getEmpEducationDetails($employee_details_id);
	}

	public function getEmpAwardDetails($employee_details_id)
	{
		return $this->staffMapper->getEmpAwardDetails($employee_details_id);
	}

	public function getEmpCommunityServiceDetails($employee_details_id)
	{
		return $this->staffMapper->getEmpCommunityServiceDetails($employee_details_id);
	}

	public function getEmpContributionDetails($employee_details_id)
	{
		return $this->staffMapper->getEmpContributionDetails($employee_details_id);
	}

	public function getEmpResponsibilityDetails($employee_details_id)
	{
		return $this->staffMapper->getEmpResponsibilityDetails($employee_details_id);
	}


	public function getEmpTrainingDetails($employee_details_id)
	{
		return $this->staffMapper->getEmpTrainingDetails($employee_details_id);
	}

	public function getPendingLeaveList($employee_details_id)
	{
		return $this->staffMapper->getPendingLeaveList($employee_details_id);
	}

	public function getApprovedLeaveList($employee_details_id)
	{
		return $this->staffMapper->getApprovedLeaveList($employee_details_id);
	}

	public function getRejectedLeaveList($employee_details_id)
	{
		return $this->staffMapper->getRejectedLeaveList($employee_details_id);
	}

	public function getStaffRejectedLeaveStatus($id)
	{
		return $this->staffMapper->getStaffRejectedLeaveStatus($id);
	}

	public function getStaffTourList($employee_details_id)
	{
		return $this->staffMapper->getStaffTourList($employee_details_id);
	}

	public function getApprovedTourList($employee_details_id)
	{
		return $this->staffMapper->getApprovedTourList($employee_details_id);
	}

	public function getRejectedTourList($employee_details_id)
	{
		return $this->staffMapper->getRejectedTourList($employee_details_id);
	}

	public function deleteStaffPendingTour($id)
	{
		return $this->staffMapper->deleteStaffPendingTour($id);
	}

	public function getStaffTourAuthDetails($id)
	{
		return $this->staffMapper->getStaffTourAuthDetails($id);
	}

	public function getTourApprovingAuthority($id)
	{
		return $this->staffMapper->getTourApprovingAuthority($id);
	}
	
	public function findFromTravelDate($id)
	{
		return $this->staffMapper->findFromTravelDate($id);
	}
	
	public function findToTravelDate($id)
	{
		return $this->staffMapper->findToTravelDate($id);
	}

	public function getStaffTourDetails($id)
	{
		return $this->staffMapper->getStaffTourDetails($id);
	}


	public function getStaffJobApplicataionList($employee_details_id)
	{
		return $this->staffMapper->getStaffJobApplicataionList($employee_details_id);
	}

	public function getStaffPromotionDetails($employee_details_id)
	{
		return $this->staffMapper->getStaffPromotionDetails($employee_details_id);
	}

	public function getStaffResignationDetails($employee_details_id)
	{
		return $this->staffMapper->getStaffResignationDetails($employee_details_id);
	}

	public function getStaffTransferDetails($employee_details_id)
	{
		return $this->staffMapper->getStaffTransferDetails($employee_details_id);
	}

	public function getStaffAttendance($from_date, $to_date, $employee_details_id)
	{
		return $this->staffMapper->getStaffAttendance($from_date, $to_date, $employee_details_id);
	}

	public function getAttendanceRecordDates($from_date, $to_date, $departments_units_id)
	{
		return $this->staffMapper->getAttendanceRecordDates($from_date, $to_date, $departments_units_id);
	}

	public function getAbsenteeList($from_date, $to_date, $employee_details_id)
	{
		return $this->staffMapper->getAbsenteeList($from_date, $to_date, $employee_details_id);
	}


	public function getWeekends($from_date, $to_date)
	{
		return $this->staffMapper->getWeekends($from_date, $to_date);
	}

	public function getStaffLeaveEncashmentDetails($employee_details_id)
	{
		return $this->staffMapper->getStaffLeaveEncashmentDetails($employee_details_id);
	}

	public function getAcademicAssessmentDetails($assessment_component_id, $employee_details_id, $organisation_id)
	{
		return $this->staffMapper->getAcademicAssessmentDetails($assessment_component_id, $employee_details_id, $organisation_id);
	}


	//Value to get un-assgined sub menu list for ajax function
	public function getNotAssignedSubMenuList($parent_id)
	{
		return $this->staffMapper->getNotAssignedSubMenuList($parent_id);
	}

	//Value to get un-assigned route list for ajax function
	public function getUnassignedRouteList($category_id)
	{
		return $this->staffMapper->getUnassignedRouteList($category_id);
	}
	
}
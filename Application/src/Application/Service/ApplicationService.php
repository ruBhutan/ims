<?php

namespace Application\Service;

use Application\Mapper\ApplicationMapperInterface;
use Application\Model\Application;

class ApplicationService implements ApplicationServiceInterface
{
	/**
	 * @var \Blog\Mapper\ApplicationMapperInterface
	*/
	
	protected $applicationMapper;
	
	public function __construct(ApplicationMapperInterface $applicationMapper) {
		$this->applicationMapper = $applicationMapper;
	}
		
	public function getUserDetailsId($username, $tableName)
	{
		return $this->applicationMapper->getUserDetailsId($username, $tableName);
	}
	
	public function getUserDetails($username, $usertype)
	{
		return $this->applicationMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->applicationMapper->getUserImage($username, $usertype);
	}
	
	public function getOrganisationId($username, $usertype)
	{
		return $this->applicationMapper->getOrganisationId($username, $usertype);
	}
		
	public function getNotifications($notification_type, $userrole, $employee_details_id, $user_department, $organisation_id)
	{
		return $this->applicationMapper->getNotifications($notification_type, $userrole, $employee_details_id, $user_department, $organisation_id);
	}

	public function getTourNotifications($notification_type, $userrole, $employee_details_id, $user_department, $organisation_id)
	{
		return $this->applicationMapper->getTourNotifications($notification_type, $userrole, $employee_details_id, $user_department, $organisation_id);
	}


	public function getGoodsRequisitiontNotifications($notification_type, $userrole, $employee_details_id, $user_department, $organisation_id)
	{
		return $this->applicationMapper->getGoodsRequisitiontNotifications($notification_type, $userrole, $employee_details_id, $user_department, $organisation_id);
	}


	public function getStaffTransferNotification($notification_type, $userrole, $employee_details_id, $user_department, $organisation_id)
	{
		return $this->applicationMapper->getStaffTransferNotification($notification_type, $userrole, $employee_details_id, $user_department, $organisation_id);
	}


	public function getStaffPromotionNotification($notification_type, $userrole, $employee_details_id, $user_department, $organisation_id)
	{
		return $this->applicationMapper->getStaffPromotionNotification($notification_type, $userrole, $employee_details_id, $user_department, $organisation_id);
	}


	public function getStaffResignationNotification($notification_type, $userrole, $employee_details_id, $user_department, $organisation_id)
	{
		return $this->applicationMapper->getStaffResignationNotification($notification_type, $userrole, $employee_details_id, $user_department, $organisation_id);
	}

	
	public function getUpcomingDates($userrole)
	{
		return $this->applicationMapper->getUpcomingDates($userrole);
	}


	public function getStaffDetails($tableName, $personal_details_id, $type)
	{
		return $this->applicationMapper->getStaffDetails($tableName, $personal_details_id, $type);
	}

	public function getPresentPositionTitle($employee_details_id)
	{
		return $this->applicationMapper->getPresentPositionTitle($employee_details_id);
	}

	public function getPresentPositionLevel($employee_details_id)
	{
		return $this->applicationMapper->getPresentPositionLevel($employee_details_id);
	}

	public function getNumberOfStudents()
	{
		return $this->applicationMapper->getNumberOfStudents();
	}

	public function getNumberOfStaff()
	{
		return $this->applicationMapper->getNumberOfStaff();
	}

	public function getStaffOnLeave($organisation_id)
	{
		return $this->applicationMapper->getStaffOnLeave($organisation_id);
	}
	
	public function getStaffOnTour($organisation_id)
	{
		return $this->applicationMapper->getStaffOnTour($organisation_id);
	}

	public function getModuleAllocation($employee_detaild_id, $organisation_id)
	{
		return $this->applicationMapper->getModuleAllocation($employee_detaild_id, $organisation_id);
	}

	public function getStudentDetails($student_id)
	{
		return $this->applicationMapper->getStudentDetails($student_id);
	}

	public function getStdCurrentSemesterDetails($student_id, $organisation_id)
	{
		return $this->applicationMapper->getStdCurrentSemesterDetails($student_id, $organisation_id);
	}

	public function getAcademicModuleLists($student_id, $organisation_id)
	{
		return $this->applicationMapper->getAcademicModuleLists($student_id, $organisation_id);
	}

	public function getStdCurrentCADetails($student_id, $organisation_id)
	{
		return $this->applicationMapper->getStdCurrentCADetails($student_id, $organisation_id);
	}

	public function getStudentAcademicTimetable($student_id, $organisation_id)
	{
		return $this->applicationMapper->getStudentAcademicTimetable($student_id, $organisation_id);
	}

	public function getTimetableTiming($organisation_id)
	{
		return $this->applicationMapper->getTimetableTiming($organisation_id);
	}

	public function getAcademicModuleTutor($student_id, $organisation_id)
	{
		return $this->applicationMapper->getAcademicModuleTutor($student_id, $organisation_id);
	}

	public function getStdAcademicModuleLists($student_id, $organisation_id)
	{
		return $this->applicationMapper->getStdAcademicModuleLists($student_id, $organisation_id);
	}

	/*public function getTotalLecturesDelivered($student_id, $organisation_id)
	{
		return $this->applicationMapper->getTotalLecturesDelivered($student_id, $organisation_id);
	}*/

	public function getAbsenteeModuleRecord($student_id, $organisation_id)
	{
		return $this->applicationMapper->getAbsenteeModuleRecord($student_id, $organisation_id);
	}

	public function listSelectData($tableName, $columnName)
	{
		return $this->applicationMapper->listSelectData($tableName, $columnName);
	}
	
}
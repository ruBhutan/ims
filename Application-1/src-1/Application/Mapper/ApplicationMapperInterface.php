<?php

namespace Application\Mapper;

use Application\Model\Application;

interface ApplicationMapperInterface
{

	
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username, $tableName);
	
	/*
	* take username and returns Name and any other detail required
	*/
	
	public function getUserDetails($username, $usertype);

	public function getUserImage($username, $usertype);
	
	/*
	* Get organisation id based on the username
	*/
	
	public function getOrganisationId($username, $usertype);
	
	/*
	* Get the leave notifications
	*/
	
	public function getNotifications($notification_type, $userrole, $employee_details_id, $user_department, $organisation_id);


	public function getGoodsRequisitiontNotifications($notification_type, $userrole, $employee_details_id, $user_department, $organisation_id);


	public function getStaffTransferNotification($notification_type, $userrole, $employee_details_id, $user_department, $organisation_id);


	public function getStaffPromotionNotification($notification_type, $userrole, $employee_details_id, $user_department, $organisation_id);

	public function getStaffResignationNotification($notification_type, $userrole, $employee_details_id, $user_department, $organisation_id);
		
	/*
	* Get the important upcoming dates
	*/
	
	public function getUpcomingDates($userrole);

	/*
	*Get staff details based on employee details id
	**/
	public function getStaffDetails($employee_details_id);


	/*
	*Get present position title
	**/
	public function getPresentPositionTitle($employee_details_id);

	/*
	*Get present position level
	**/
	public function getPresentPositionLevel($employee_details_id);

	public function getNumberOfStudents();

	public function getNumberOfStaff();

	public function getStaffOnLeave($organisation_id);
	
	public function getStaffOnTour($organisation_id);

	public function getStudentDetails($student_id);

	public function getStdCurrentSemesterDetails($student_id, $organisation_id);

	public function getAcademicModuleLists($student_id, $organisation_id);

	public function getStdCurrentCADetails($student_id, $organisation_id);

	public function getStudentAcademicTimetable($student_id, $organisation_id);

	public function getTimetableTiming($organisation_id);

	public function getAcademicModuleTutor($student_id, $organisation_id);

	public function getStdAcademicModuleLists($student_id, $organisation_id);

	//public function getTotalLecturesDelivered($student_id, $organisation_id);

	public function getAbsenteeModuleRecord($student_id, $organisation_id);

	public function listSelectData($tableName, $columnName);
       
}
<?php

namespace CounselingService\Mapper;

use CounselingService\Model\Counselor;
use CounselingService\Model\CounselingAppointment;
use CounselingService\Model\CounselingNotes;
use CounselingService\Model\CounselingSuggest;
use CounselingService\Model\ScheduledAppointment;

interface CounselingMapperInterface
{
	/**
	 * @param int/string $id
	 * @return Counseling
	 * throws \InvalidArugmentException
	 * 
	*/

	/*
	* take username and returns employee details id/student id
	*/
	
	public function getUserDetailsId($username, $tableName);
	
	/*
	 * Get the Organisation Id
	 */
	 
	public function getOrganisationId($username, $tableName);

	public function getUserDetails($username, $usertype);

	public function getUserImage($username, $usertype);

	public function getAppointmentApplicantType($tableName, $id);

	public function getAppointmentApplicantDetails($applicantType, $id);

	public function getRecommendedType($tableName, $id);
	
	public function findCounseling($tableName, $id);

	public function crossCheckCounselor($counselorId, $organisation_id, $status);

	public function getCounselorId($counselorId);

	public function crossCheckCounselingAppointment($subject, $applicant, $applicantType, $counselorId, $appointmentTime, $appointmentDate);

	public function crossCheckCounselingAppointmentDetails($subject, $applicant, $applicantType, $id, $counselorId);

	public function getCounselorEmail($counselor);

	public function getCounselingApplicant($tableName, $applicant);

	public function getIndCounselingApplicationList($username, $usertype);

	public function getIndCounselingAppointmentDetails($id);

	public function findIndCounselingAppointmentDetails($id);

	/**
	 * 
	 * @return array/ Counseling[]
	 */
	 
	public function getStaffRecommendCounselingList($tableName, $employee_details_id);
	public function getStdRecommendCounselingList($tableName, $employee_details_id);

	public function getSuggestedType($id);

	public function getSuggestedDetails($id, $suggestType);

	public function getRecommendCounselingDetails($id);

	public function findRecommendCounselingDetails($id);

	public function getStaffRecommendedList($status, $employee_details_id);

	public function getStdRecommendedList($status, $employee_details_id);

	public function listSelectData($tableName, $organisation_id);

	public function getCounselorList($organisation_id);

	public function saveCounselor(Counselor $counselingsInterface);

	public function getCurrentCounselorStatus($id);

	public function updateCounselorStatus($status, $previousStatus, $id);
        
	/**
	 * 
	 * @param type $CounselingInterface
	 * 
	 * to save budgetings
	 */
	
	public function saveDetails(Counseling $counselingsInterface);

	public function crossCheckSuggestedCounseling($subject, $suggestedId, $suggestedType, $counselorId);

	public function crossCheckSuggestedCounselingDetails($subject, $suggestedId, $suggestedType, $counselorId, $suggestedBy, $id);
	
	/*
	 * Save details of student recommended for counseling
	 */
	 
	 public function saveCounselingRecommendation(CounselingSuggest $counselingObject);
	 
	 /*
	 * Save details of student counseling record
	 */
	 
	 public function saveCounselingRecord(CounselingNotes $counselingObject, $notes, $scheduledId);
	
	/**
	 * @param CounselingInterface $counselingObject
	 *
	 * @param CounselingInterface $counselingObject
	 * @return CounselingInterface
	 * @throws \Exception
	 */
	 
	 public function saveAppointment(CounselingAppointment $counselingObject);
	 
	 /**
	 * @param CounselingInterface $counselingObject
	 *
	 * @param CounselingInterface $counselingObject
	 * @return CounselingInterface
	 * @throws \Exception
	 */
	 
	 public function grantAppointment(ScheduledAppointment $counselingObject, $appointmentId, $counselingType);
	
	/**
	 * Should return a set of all students that we search. 
	 * 
	 * The purpose of the function is get a student and add counseling
	 *
	 * @return array|CounselingInterface[]
	*/
	
	public function getSuggestionList($suggestionType, $name, $suggestionId, $organisation_id);
	
	/*
	* Get Appointment List depending on the status
	*/
	
	public function getStaffAppointmentList($status, $organisation_id, $employee_details_id);

	public function getStdAppointmentList($status, $organisation_id, $employee_details_id);

	public function getStaffScheduledAppointmentList($tableName, $employee_details_id);

	public function getStdScheduledAppointmentList($tableName, $employee_details_id);


	public function findStdScheduledAppointmentList($tableName, $status, $employee_details_id);

	public function findStaffScheduledAppointmentList($tableName, $status, $employee_details_id);

	public function findStdCounselingRecordList($tableName, $employee_details_id);

	public function findStaffCounselingRecordList($tableName, $employee_details_id);

	public function getStaffDetails($id);
	
	/**
	 * 
	 * @param type $id
	 * 
	 * to find Student details so that their names are displayed
	 */
	public function getStudentDetails($id);
	

	/*
	* Get the details for the scheduled counseling appointments
	*/
	
	public function findScheduledCounseling($tableName, $id);

	public function getCounselingType($tableName, $id);

	public function findCounselingRecordDetails($counselingType, $id);

	public function getCounselingRecordDetails($id);

	public function crossCheckCounselorScheduled($scheduledTime, $scheduledDate, $employee_details_id);

	public function findCounselingApplicantType($tableName, $id);

	public function findRecommendedCounselingType($tableName, $id);

	public function findApplicantDetails($id, $applicantType);

	public function findRecommendedDetails($tableName, $id, $applicantType);

	public function getApplicantDetails($id, $applicantType);

	public function getCounselingRecordFileName($id);
	
	/*
	* Get the details for the student id from the scheduled counseling appointments
	*/
	
	public function findStudentId($id);
	
	/*
	* Get the employee details
	*/
	
	public function getEmployeeDetails($empId);
	
	/*
	* Get the counseling notes for a particular student
	*/
	
	public function getCounselingNotes($studentId);
	
}
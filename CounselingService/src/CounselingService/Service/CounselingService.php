<?php

namespace CounselingService\Service;

use CounselingService\Mapper\CounselingMapperInterface;
use CounselingService\Model\Counselor;
use CounselingService\Model\CounselingAppointment;
use CounselingService\Model\CounselingNotes;
use CounselingService\Model\CounselingSuggest;
use CounselingService\Model\ScheduledAppointment;

class CounselingService implements CounselingServiceInterface
{
	/**
	 * @var \Blog\Mapper\CounselingMapperInterface
	*/
	
	protected $counselingMapper;
	
	public function __construct(CounselingMapperInterface $counselingMapper) {
		$this->counselingMapper = $counselingMapper;
	}

	public function getUserDetailsId($username, $tableName)
	{
		return $this->counselingMapper->getUserDetailsId($username, $tableName);
	}
		 
	public function getOrganisationId($username, $tableName)
	{
		return $this->counselingMapper->getOrganisationId($username, $tableName);
	}

	public function getUserDetails($username, $usertype)
	{
		return $this->counselingMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->counselingMapper->getUserImage($username, $usertype);
	}
	
	public function getStaffRecommendCounselingList($tableName, $employee_details_id)
	{
		return $this->counselingMapper->getStaffRecommendCounselingList($tableName, $employee_details_id);
	}

	public function getStdRecommendCounselingList($tableName, $employee_details_id)
	{
		return $this->counselingMapper->getStdRecommendCounselingList($tableName, $employee_details_id);
	}

	public function getSuggestedType($id)
	{
		return $this->counselingMapper->getSuggestedType($id);
	}

	public function getSuggestedDetails($id, $suggestType)
	{
		return $this->counselingMapper->getSuggestedDetails($id, $suggestType);
	}

	public function getRecommendCounselingDetails($id)
	{
		return $this->counselingMapper->getRecommendCounselingDetails($id);
	}

	public function findRecommendCounselingDetails($id)
	{
		return $this->counselingMapper->findRecommendCounselingDetails($id);
	}

	public function getStaffRecommendedList($status, $employee_details_id)
	{
		return $this->counselingMapper->getStaffRecommendedList($status, $employee_details_id);
	}

	public function getStdRecommendedList($status, $employee_details_id)
	{
		return $this->counselingMapper->getStdRecommendedList($status, $employee_details_id);
	}

	public function listSelectData($tableName, $organisation_id)
	{
		return $this->counselingMapper->listSelectData($tableName, $organisation_id);
	}

	public function getCounselorList($organisation_id)
	{
		return $this->counselingMapper->getCounselorList($organisation_id);
	}

	public function getAppointmentApplicantType($tableName, $id)
	{
		return $this->counselingMapper->getAppointmentApplicantType($tableName, $id);
	}

	public function getAppointmentApplicantDetails($applicantType, $id)
	{
		return $this->counselingMapper->getAppointmentApplicantDetails($applicantType, $id);
	}

	public function getRecommendedType($tableName, $id)
	{
		return $this->counselingMapper->getRecommendedType($tableName, $id);
	}
	 
	public function findCounseling($tableName, $id)
	{
		return $this->counselingMapper->findCounseling($tableName, $id);
	}

	public function crossCheckCounselor($counselorId, $organisation_id, $status)
	{
		return $this->counselingMapper->crossCheckCounselor($counselorId, $organisation_id, $status);
	}

	public function getCounselorId($counselorId)
	{
		return $this->counselingMapper->getCounselorId($counselorId);
	}

	public function crossCheckCounselingAppointment($subject, $applicant, $applicantType, $counselorId, $appointmentTime, $appointmentDate)
	{
		return $this->counselingMapper->crossCheckCounselingAppointment($subject, $applicant, $applicantType, $counselorId, $appointmentTime, $appointmentDate);
	}

	public function crossCheckCounselingAppointmentDetails($subject, $applicant, $applicantType, $id, $counselorId)
	{
		return $this->counselingMapper->crossCheckCounselingAppointmentDetails($subject, $applicant, $applicantType, $id, $counselorId);
	}

	public function getCounselorEmail($counselor)
	{
		return $this->counselingMapper->getCounselorEmail($counselor);
	}

	public function getCounselingApplicant($tableName, $applicant)
	{
		return $this->counselingMapper->getCounselingApplicant($tableName, $applicant);
	}

	public function getIndCounselingApplicationList($username, $usertype)
	{
		return $this->counselingMapper->getIndCounselingApplicationList($username, $usertype);
	}


	public function getIndCounselingAppointmentDetails($id)
	{
		return $this->counselingMapper->getIndCounselingAppointmentDetails($id);
	}

	public function findIndCounselingAppointmentDetails($id)
	{
		return $this->counselingMapper->findIndCounselingAppointmentDetails($id);
	}

	public function saveCounselor(Counselor $counselingObject)
	{
		return $this->counselingMapper->saveCounselor($counselingObject);
	}

	public function getCurrentCounselorStatus($id)
	{
		return $this->counselingMapper->getCurrentCounselorStatus($id);
	}

	public function updateCounselorStatus($status, $previousStatus, $id)
	{
		return $this->counselingMapper->updateCounselorStatus($status, $previousStatus, $id);
	}
	
	public function saveCounseling(Counseling $counselingObject) 
	{
		return $this->counselingMapper->saveDetails($counselingObject);
	}

	public function crossCheckSuggestedCounseling($subject, $suggestedId, $suggestedType, $counselorId)
	{
		return $this->counselingMapper->crossCheckSuggestedCounseling($subject, $suggestedId, $suggestedType, $counselorId);
	}

	public function crossCheckSuggestedCounselingDetails($subject, $suggestedId, $suggestedType, $counselorId, $suggestedBy, $id)
	{
		return $this->counselingMapper->crossCheckSuggestedCounselingDetails($subject, $suggestedId, $suggestedType, $counselorId, $suggestedBy, $id);
	}
	
	public function saveCounselingRecommendation(CounselingSuggest $counselingObject)
	{
		return $this->counselingMapper->saveCounselingRecommendation($counselingObject);
	}
		 
	public function saveCounselingRecord(CounselingNotes $counselingObject, $notes, $scheduledId)
	{
		return $this->counselingMapper->saveCounselingRecord($counselingObject, $notes, $scheduledId);
	}
	
	public function saveAppointment(CounselingAppointment $counselingObject)
	{
		return $this->counselingMapper->saveAppointment($counselingObject);
	}
		 
	public function grantAppointment(ScheduledAppointment $counselingObject, $appointmentId, $counselingType)
	{
		return $this->counselingMapper->grantAppointment($counselingObject, $appointmentId, $counselingType);
	}
	
	public function getSuggestionList($suggestionType, $name, $suggestionId, $organisation_id)
	{
		return $this->counselingMapper->getSuggestionList($suggestionType, $name, $suggestionId, $organisation_id);
	}
		
	public function getStaffAppointmentList($status, $organisation_id, $employee_details_id)
	{
		return $this->counselingMapper->getStaffAppointmentList($status, $organisation_id, $employee_details_id);
	}

	public function getStdAppointmentList($status, $organisation_id, $employee_details_id)
	{
		return $this->counselingMapper->getStdAppointmentList($status, $organisation_id, $employee_details_id);
	}

	public function getStaffScheduledAppointmentList($tableName, $employee_details_id)
	{
		return $this->counselingMapper->getStaffScheduledAppointmentList($tableName, $employee_details_id);
	}

	public function getStdScheduledAppointmentList($tableName, $employee_details_id)
	{
		return $this->counselingMapper->getStdScheduledAppointmentList($tableName, $employee_details_id);
	}


	public function findStdScheduledAppointmentList($tableName, $status, $employee_details_id)
	{
		return $this->counselingMapper->findStdScheduledAppointmentList($tableName, $status, $employee_details_id);
	}

	public function findStaffScheduledAppointmentList($tableName, $status, $employee_details_id)
	{
		return $this->counselingMapper->findStaffScheduledAppointmentList($tableName, $status, $employee_details_id);
	}

	public function findStdCounselingRecordList($tableName, $employee_details_id)
	{
		return $this->counselingMapper->findStdCounselingRecordList($tableName, $employee_details_id);
	}

	public function findStaffCounselingRecordList($tableName, $employee_details_id)
	{
		return $this->counselingMapper->findStaffCounselingRecordList($tableName, $employee_details_id);
	}

	public function getStaffDetails($id)
	{
		return $this->counselingMapper->getStaffDetails($id);
	}

	
	public function getStudentDetails($id)
	{
		return $this->counselingMapper->getStudentDetails($id);
	}
		
	public function findScheduledCounseling($tableName, $id)
	{
		return $this->counselingMapper->findScheduledCounseling($tableName, $id);
	}

	public function getCounselingType($tableName, $id)
	{
		return $this->counselingMapper->getCounselingType($tableName, $id);
	}

	public function findCounselingRecordDetails($counselingType, $id)
	{
		return $this->counselingMapper->findCounselingRecordDetails($counselingType, $id);
	}

	public function getCounselingRecordDetails($id)
	{
		return $this->counselingMapper->getCounselingRecordDetails($id);
	}

	public function crossCheckCounselorScheduled($scheduledTime, $scheduledDate, $employee_details_id)
	{
		return $this->counselingMapper->crossCheckCounselorScheduled($scheduledTime, $scheduledDate, $employee_details_id);
	}

	public function findCounselingApplicantType($tableName, $id)
	{
		return $this->counselingMapper->findCounselingApplicantType($tableName, $id);
	}

	public function findRecommendedCounselingType($tableName, $id)
	{
		return $this->counselingMapper->findRecommendedCounselingType($tableName, $id);
	}

	public function findApplicantDetails($id, $applicantType)
	{
		return $this->counselingMapper->findApplicantDetails($id, $applicantType);
	}

	public function findRecommendedDetails($tableName, $id, $applicantType)
	{
		return $this->counselingMapper->findRecommendedDetails($tableName, $id, $applicantType);
	}

	public function getApplicantDetails($id, $applicantType)
	{
		return $this->counselingMapper->getApplicantDetails($id, $applicantType);
	}

	public function getCounselingRecordFileName($id)
	{
		return $this->counselingMapper->getCounselingRecordFileName($id);
	}
		
	public function findStudentId($id)
	{
		return $this->counselingMapper->findStudentId($id);
	}
	
	public function getEmployeeDetails($empId)
	{
		return $this->counselingMapper->getEmployeeDetails($empId);
	}
		
	public function getCounselingNotes($studentId)
	{
		return $this->counselingMapper->getCounselingNotes($studentId);
	}
	
}
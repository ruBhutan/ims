<?php

namespace StudentPortal\Mapper;

use StudentPortal\Model\StudentDetail;
use StudentPortal\Model\StudentPortal;

interface StudentPortalMapperInterface
{

	/**
	 * 
	 * @return array/ JobPortal[]
	 */
	 
	public function findAll($tableName);
	
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username, $tableName);

	public function getStudentDetails($student_id);	

	public function getStudentPersonalDetails($student_id);

	public function getStudentCategoryDetails($student_id);

	public function getStudentNationality($student_id);

	public function getStudentPermanentAddress($student_id);

	public function getStudentCountry($student_id);

	public function getStudentContactDetails($student_id);

	public function getStudentRelationDetails($student_id);

	public function getStudentGuardianDetails($student_id);

	public function getStudentFatherDetails($student_id);

	public function getStudentMotherDetails($student_id);

	public function getParentContactDetails($student_id);

	public function getStudentResponsibility($student_id, $organisation_id);

	public function getStudentAchievement($student_id);

	public function getStudentParticipation($student_id);

	public function getStudentContribution($student_id);

	public function getStudentPreviousSchoolDetails($student_id);

	public function getStudentDisciplineRecords($student_id);

	public function getStudentSemesterAcademicYear($student_id);

	public function getStudentAcademicModules($student_id, $programmes_id, $organisation_id);

	public function getStudentAcademicTimetable($student_id, $organisation_id);

	public function getTimetableTiming($organisation_id);

	public function getStdCurrentSemesterDetails($student_id, $organisation_id);

	public function getAcademicModuleLists($student_id, $organisation_id);

	public function crossCheckCompiledCaDetails($academic_modules_id, $student_details_id, $organisation_id);

	public function getStudentModuleCaDetails($academic_modules_id, $student_details_id, $organisation_id);

	public function getStudentModuleAttendanceDetails($academic_modules_id, $std_id, $organisation_id);

	public function getStdCurrentCADetails($student_id, $organisation_id);

	public function getDeclaredResult($student_details_id, $student_id, $programmes_id, $organisation_id);

	public function getAcademicModuleTutor($student_id);

	public function getStudentRecheckMarkStatus($student_id);

	public function getStudentReassessmentStatus($student_id);

	public function getStudentRepeatModuleStatus($student_id);

	public function getStudentHostelRoomDetails($student_id);

	public function getStudentHostelRoomInventory($student_id);

	public function getHostelChangeApplicationStatus($student_id);

	public function getStudentClubApplicationStatus($student_id);

	public function getStudentClubApplicationDetails($id);

	public function getStudentClubList($status, $student_id);

	public function getMemberClubDetails($id);

	public function getClubMemberNos($id);

	public function getStudentClubMemberList($id);

	public function getStudentClubAttendanceList($attendanceYear, $id, $student_id);

	public function getStdExtraCurricularAttendanceRecord($attendanceYear, $student_id);

	public function getCounselingAppointmentStatus($student_id);

	public function getScheduledAppointment($student_id);

	public function getRecommendedCounseling($student_id);

	public function getCounselingAppointmentDetails($id);

	public function getCounselingScheduledDetails($id);

	public function getDisciplinaryRecords($student_id);

	public function getStdDisciplinaryRecordDetails($id);

	public function getStdMedicalRecordList($student_id);

	public function getStdMedicalRecordDetails($id);

	public function getStudentLeaveStatus($student_id);

	public function getStudentLeaveDetails($id);

	public function getExamTimetable($programmes_id);

	public function getExamDates($organisation_id);

	public function getNoEligibleModules($student_id);

	public function getStuddentProfilePicture($id);

}
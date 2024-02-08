<?php

namespace StudentPortal\Service;

use StudentPortal\Mapper\StudentPortalMapperInterface;
use StudentPortal\Model\StudentPortal;
use StudentPortal\Model\StudentDetail;

class StudentPortalService implements StudentPortalServiceInterface
{
	/**
	 * @var \Blog\Mapper\JobPortalMapperInterface
	*/
	
	protected $studentMapper;
	
	public function __construct(StudentPortalMapperInterface $studentMapper) {
		$this->studentMapper = $studentMapper;
	}
	
	public function listAll($tableName)
	{
		return $this->studentMapper->findAll($tableName);
	}
	
	public function getUserDetailsId($username, $tableName)
	{
		return $this->studentMapper->getUserDetailsId($username, $tableName);
	}

	public function getStudentDetails($student_id)
	{
		return $this->studentMapper->getStudentDetails($student_id);
	}

	public function getStudentPersonalDetails($student_id)
	{
		return $this->studentMapper->getStudentPersonalDetails($student_id);
	}

	public function getStudentCategoryDetails($student_id)
	{
		return $this->studentMapper->getStudentCategoryDetails($student_id);
	}

	public function getStudentNationality($student_id)
	{
		return $this->studentMapper->getStudentNationality($student_id);
	}

	public function getStudentPermanentAddress($student_id)
	{
		return $this->studentMapper->getStudentPermanentAddress($student_id);
	}

	public function getStudentCountry($student_id)
	{
		return $this->studentMapper->getStudentCountry($student_id);
	}

	public function getStudentContactDetails($student_id)
	{
		return $this->studentMapper->getStudentContactDetails($student_id);
	}

	public function getStudentRelationDetails($student_id)
	{
		return $this->studentMapper->getStudentRelationDetails($student_id);
	}

	public function getStudentGuardianDetails($student_id)
	{
		return $this->studentMapper->getStudentGuardianDetails($student_id);
	}


	public function getStudentFatherDetails($student_id)
	{
		return $this->studentMapper->getStudentFatherDetails($student_id);
	}

	public function getStudentMotherDetails($student_id)
	{
		return $this->studentMapper->getStudentMotherDetails($student_id);
	}


	public function getParentContactDetails($student_id)
	{
		return $this->studentMapper->getParentContactDetails($student_id);
	}


	public function getStudentResponsibility($student_id, $organisation_id)
	{
		return $this->studentMapper->getStudentResponsibility($student_id, $organisation_id);
	}

	public function getStudentAchievement($student_id)
	{
		return $this->studentMapper->getStudentAchievement($student_id);
	}


	public function getStudentParticipation($student_id)
	{
		return $this->studentMapper->getStudentParticipation($student_id);
	}


	public function getStudentContribution($student_id)
	{
		return $this->studentMapper->getStudentContribution($student_id);
	}


	public function getStudentPreviousSchoolDetails($student_id)
	{
		return $this->studentMapper->getStudentPreviousSchoolDetails($student_id);
	}


	public function getStudentDisciplineRecords($student_id)
	{
		return $this->studentMapper->getStudentDisciplineRecords($student_id);
	}


	public function getStudentAcademicModules($student_id, $programmes_id, $organisation_id)
	{
		return $this->studentMapper->getStudentAcademicModules($student_id, $programmes_id, $organisation_id);
	}


	public function getStudentSemesterAcademicYear($student_id)
	{
		return $this->studentMapper->getStudentSemesterAcademicYear($student_id);
	}

	public function getStudentAcademicTimetable($student_id, $organisation_id)
	{
		return $this->studentMapper->getStudentAcademicTimetable($student_id, $organisation_id);
	}


	public function getTimetableTiming($organisation_id)
	{
		return $this->studentMapper->getTimetableTiming($organisation_id);
	}

	public function getStdCurrentSemesterDetails($student_id, $organisation_id)
	{
		return $this->studentMapper->getStdCurrentSemesterDetails($student_id, $organisation_id);
	}

	public function getAcademicModuleLists($student_id, $organisation_id)
	{
		return $this->studentMapper->getAcademicModuleLists($student_id, $organisation_id);
	}

	public function getStudentModuleAttendanceDetails($academic_modules_id, $std_id, $organisation_id)
	{
		return $this->studentMapper->getStudentModuleAttendanceDetails($academic_modules_id, $std_id, $organisation_id);
	}

	public function crossCheckCompiledCaDetails($academic_modules_id, $student_details_id, $organisation_id)
	{
		return $this->studentMapper->crossCheckCompiledCaDetails($academic_modules_id, $student_details_id, $organisation_id);
	}

	public function getStudentModuleCaDetails($academic_modules_id, $student_details_id, $organisation_id)
	{
		return $this->studentMapper->getStudentModuleCaDetails($academic_modules_id, $student_details_id, $organisation_id);
	}

	public function getDeclaredResult($student_details_id, $student_id, $programmes_id, $organisation_id)
	{
		return $this->studentMapper->getDeclaredResult($student_details_id, $student_id, $programmes_id, $organisation_id);
	}

	public function getStdCurrentCADetails($student_id, $organisation_id)
	{
		return $this->studentMapper->getStdCurrentCADetails($student_id, $organisation_id);
	}

	public function getAcademicModuleTutor($student_id)
	{
		return $this->studentMapper->getAcademicModuleTutor($student_id);
	}


	public function getStudentRecheckMarkStatus($student_id)
	{
		return $this->studentMapper->getStudentRecheckMarkStatus($student_id);
	}

	public function getStudentReassessmentStatus($student_id)
	{
		return $this->studentMapper->getStudentReassessmentStatus($student_id);
	}

	public function getStudentRepeatModuleStatus($student_id)
	{
		return $this->studentMapper->getStudentRepeatModuleStatus($student_id);
	}

	public function getStudentHostelRoomDetails($student_id)
	{
		return $this->studentMapper->getStudentHostelRoomDetails($student_id);
	}

	public function getStudentHostelRoomInventory($student_id)
	{
		return $this->studentMapper->getStudentHostelRoomInventory($student_id);
	}

	public function getHostelChangeApplicationStatus($student_id)
	{
		return $this->studentMapper->getHostelChangeApplicationStatus($student_id);
	}

	public function getStudentClubApplicationStatus($student_id)
	{
		return $this->studentMapper->getStudentClubApplicationStatus($student_id);
	}

	public function getStudentClubApplicationDetails($id)
	{
		return $this->studentMapper->getStudentClubApplicationDetails($id);
	}

	public function getStudentClubList($status, $student_id)
	{
		return $this->studentMapper->getStudentClubList($status, $student_id);
	}

	public function getMemberClubDetails($id)
	{
		return $this->studentMapper->getMemberClubDetails($id);
	}

	public function getClubMemberNos($id)
	{
		return $this->studentMapper->getClubMemberNos($id);
	}

	public function getStudentClubMemberList($id)
	{
		return $this->studentMapper->getStudentClubMemberList($id);
	}

	public function getStudentClubAttendanceList($attendanceYear, $id, $student_id)
	{
		return $this->studentMapper->getStudentClubAttendanceList($attendanceYear, $id, $student_id);
	}

	public function getStdExtraCurricularAttendanceRecord($attendanceYear, $student_id)
	{
		return $this->studentMapper->getStdExtraCurricularAttendanceRecord($attendanceYear, $student_id);
	}

	public function getCounselingAppointmentStatus($student_id)
	{
		return $this->studentMapper->getCounselingAppointmentStatus($student_id);
	}

	public function getScheduledAppointment($student_id)
	{
		return $this->studentMapper->getScheduledAppointment($student_id);
	}

	public function getRecommendedCounseling($student_id)
	{
		return $this->studentMapper->getRecommendedCounseling($student_id);
	}

	public function getCounselingAppointmentDetails($id)
	{
		return $this->studentMapper->getCounselingAppointmentDetails($id);
	}

	public function getCounselingScheduledDetails($id)
	{
		return $this->studentMapper->getCounselingScheduledDetails($id);
	}

	public function getDisciplinaryRecords($student_id)
	{
		return $this->studentMapper->getDisciplinaryRecords($student_id);
	}

	public function getStdDisciplinaryRecordDetails($id)
	{
		return $this->studentMapper->getStdDisciplinaryRecordDetails($id);
	}

	public function getStdMedicalRecordList($student_id)
	{
		return $this->studentMapper->getStdMedicalRecordList($student_id);
	}

	public function getStdMedicalRecordDetails($id)
	{
		return $this->studentMapper->getStdMedicalRecordDetails($id);
	}

	public function getStudentLeaveStatus($student_id)
	{
		return $this->studentMapper->getStudentLeaveStatus($student_id);
	}


	public function getStudentLeaveDetails($id)
	{
		return $this->studentMapper->getStudentLeaveDetails($id);
	}

	public function getExamTimetable($programmes_id)
	{
		return $this->studentMapper->getExamTimetable($programmes_id);
	}

	public function getExamDates($organisation_id)
	{
		return $this->studentMapper->getExamDates($organisation_id);
	}

	public function getNoEligibleModules($student_id)
	{
		return $this->studentMapper->getNoEligibleModules($student_id);
	}

	public function getStuddentProfilePicture($id)
	{
		return $this->studentMapper->getStuddentProfilePicture($id);
	}
}
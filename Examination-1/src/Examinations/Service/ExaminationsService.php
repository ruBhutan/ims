<?php

namespace Examinations\Service;

use Examinations\Mapper\ExaminationsMapperInterface;
use Examinations\Model\Examinations;
use Examinations\Model\ExamHall;
use Examinations\Model\ExaminationCode;
use Examinations\Model\ExamInvigilator;

class ExaminationsService implements ExaminationsServiceInterface
{
	/**
	 * @var \Blog\Mapper\ExaminationsMapperInterface
	*/
	
	protected $examinationMapper;
	
	public function __construct(ExaminationsMapperInterface $examinationMapper) {
		$this->examinationMapper = $examinationMapper;
	}
	
	public function listAll($tableName, $organisation_id)
	{
		return $this->examinationMapper->findAll($tableName, $organisation_id);
	}
	
	public function getUserDetailsId($username)
	{
		return $this->examinationMapper->getUserDetailsId($username);
	}
	
	public function getOrganisationId($username)
	{
		return $this->examinationMapper->getOrganisationId($username);
	}

	public function getUserDetails($username, $usertype)
	{
		return $this->examinationMapper->getUserDetails($username, $usertype);
	}

    public function getUserImage($username, $usertype)
    {
    	return $this->examinationMapper->getUserImage($username, $usertype);
    }
			
	public function saveExaminationHall(ExamHall $examinationModel)
	{
		return $this->examinationMapper->saveExaminationHall($examinationModel);
	}

	public function saveBlockStudent($id)
	{
		return $this->examinationMapper->saveBlockStudent($id);
	}

	public function deleteBlockStudent($id)
	{
		return $this->examinationMapper->deleteBlockStudent($id);
	}
	
	public function saveExaminationTimetable($data)
	{
		return $this->examinationMapper->saveExaminationTimetable($data);
	}
		
	public function saveHallArrangement()
	{
		return $this->examinationMapper->saveHallArrangement();
	}
		
	public function saveExamInvigilator(ExamInvigilator $invigilatorModel)
	{
		return $this->examinationMapper->saveExamInvigilator($invigilatorModel);
	}
	
	public function addStudentBackPaper($backpaper_data, $programme, $academic_modules_id, $backlog_academic_year, $backlog_semester)
	{
		return $this->examinationMapper->addStudentBackPaper($backpaper_data, $programme, $academic_modules_id, $backlog_academic_year, $backlog_semester);
	}
		
	public function deleteExamInvigilator($id)
	{
		return $this->examinationMapper->deleteExamInvigilator($id);
	}

	public function getStudentDetail($id)
	{
		return $this->examinationMapper->getStudentDetail($id);
	}
	
	public function getStudentToAddList($studentName, $studentId, $programme, $organisation_id)
	{
		return $this->examinationMapper->getStudentToAddList($studentName, $studentId, $programme, $organisation_id);
	}
	
	public function getStudentBackPaperList($programme, $batch)
	{
		return $this->examinationMapper->getStudentBackPaperList($programme, $batch);
	}
	
	public function getAcademicYearList($organisation_id)
	{
		return $this->examinationMapper->getAcademicYearList($organisation_id);
	}
	
	public function getSemesterList($organisation_id)
	{
		return $this->examinationMapper->getSemesterList($organisation_id);
	}

	public function getSemester($organisation_id)
	{
		return $this->examinationMapper->getSemester($organisation_id);
	}
		
	public function getEligibleStudentList($data, $organisation_id, $type)
	{
		return $this->examinationMapper->getEligibleStudentList($data, $organisation_id, $type);
	}
		
	public function getNonEligibilityReasons($id)
	{
		return $this->examinationMapper->getNonEligibilityReasons($id);
	}
		
	public function getExaminationNonEligibilityDetails($id)
	{
		return $this->examinationMapper->getExaminationNonEligibilityDetails($id);
	}
		
	public function changeStudentEligibility($data)
	{
		return $this->examinationMapper->changeStudentEligibility($data);
	}
		
	public function generateExamCodes(ExaminationCode $examinationModel, $data)
	{
		return $this->examinationMapper->generateExamCodes($examinationModel, $data);
	}
		
	public function getExaminationCode($data)
	{
		return $this->examinationMapper->getExaminationCode($data);
	}
		
	public function getExaminationDates($organisation_id)
	{
		return $this->examinationMapper->getExaminationDates($organisation_id);
	}
		
	public function getExaminationTimetable($data, $employee_id, $organisation_id)
	{
		return $this->examinationMapper->getExaminationTimetable($data, $employee_id, $organisation_id);
	}
		
	public function generateSecretCodes($data)
	{
		return $this->examinationMapper->generateSecretCodes($data);
	}
		
	public function getTableDetails($tableName, $id)
	{
		return $this->examinationMapper->getTableDetails($tableName, $id);
	}
		
	public function createYearList($organisation_id)
	{
		return $this->examinationMapper->createYearList($organisation_id);
	}
	
	public function consolidateMarks($data)
	{
		return $this->examinationMapper->consolidateMarks($data);
	}
	
	public function declareSemesterResults($organisation_id)
	{
		return $this->examinationMapper->declareSemesterResults($organisation_id);
	}
	
	public function generateBackpaperStudentList($data)
	{
		return $this->examinationMapper->generateBackpaperStudentList($data);
	}

	public function updateRepeatSemesterModule($studentId, $programmesId, $semesterId, $module_data, $organisation_id)
	{
		return $this->examinationMapper->updateRepeatSemesterModule($studentId, $programmesId, $semesterId, $module_data, $organisation_id);
	}

	public function crossCheckExamTimetable($tableName, $academic_modules_allocation_id, $exam_date)
	{
		return $this->examinationMapper->crossCheckExamTimetable($tableName, $academic_modules_allocation_id, $exam_date);
	}

	public function checkRepeatModuleList($student_id, $programmes_id, $semester_id)
	{
		return $this->examinationMapper->checkRepeatModuleList($student_id, $programmes_id, $semester_id);
	}

	public function getStudentAcademicModuleList($data)
	{
		return $this->examinationMapper->getStudentAcademicModuleList($data);
	}

	public function getAssignedRepeatSemesterModules($data)
	{
		return $this->examinationMapper->getAssignedRepeatSemesterModules($data);
	}
	
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		return $this->examinationMapper->listSelectData($tableName, $columnName, $organisation_id);
	}
	
}
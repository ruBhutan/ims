<?php

namespace AcademicAssessment\Service;

use AcademicAssessment\Mapper\AcademicAssessmentMapperInterface;
use AcademicAssessment\Model\AcademicAssessment;


class AcademicAssessmentService implements AcademicAssessmentServiceInterface
{
	/**
	 * @var \Blog\Mapper\PostMapperInterface
	*/
	
	protected $academicAssessmentMapper;
	
	public function __construct(AcademicAssessmentMapperInterface $academicAssessmentMapper) {
		$this->academicAssessmentMapper = $academicAssessmentMapper;
	}

	public function getUserDetailsId($tableName, $username)
	{
		return $this->academicAssessmentMapper->getUserDetailsId($tableName, $username);
	}
	
	public function getOrganisationId($tableName, $username)
	{
		return $this->academicAssessmentMapper->getOrganisationId($tableName, $username);
	}


	public function getUserDetails($username, $usertype)
	{
		return $this->academicAssessmentMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->academicAssessmentMapper->getUserImage($username, $usertype);
	}

	public function deleteCompileAssessment($programmes_id, $academic_modules_allocation_id, $assessment_component_id, $section, $organisation_id)
	{
		return $this->academicAssessmentMapper->deleteCompileAssessment($programmes_id, $academic_modules_allocation_id, $assessment_component_id, $section, $organisation_id);
	}

	public function getStudentCompiledMarksList($programmes_id, $academic_modules_allocation_id, $assessment_component_id, $section, $organisation_id)
	{
		return $this->academicAssessmentMapper->getStudentCompiledMarksList($programmes_id, $academic_modules_allocation_id, $assessment_component_id, $section, $organisation_id);
	}

	public function listSelectData($tableName, $columnName)
	{
		return $this->academicAssessmentMapper->listSelectData($tableName, $columnName);
	}
	public function listSelectData1($tableName, $columnName, $organisation_id, $username)
	{
		return $this->academicAssessmentMapper->listSelectData1($tableName, $columnName, $organisation_id, $username);
	}
	public function getSemesterList($organisation_id)
	{
		return $this->academicAssessmentMapper->getSemesterList($organisation_id);
	}
	public function getSemester($organisation_id)
	{
		return $this->academicAssessmentMapper->getSemester($organisation_id);
	}
	public function getStudentConsolidatedMarks($programme, $academic_year, $semester, $username)
	{
		return $this->academicAssessmentMapper->getStudentConsolidatedMarks($programme, $academic_year, $semester, $username);
	}

	public function getModuleCreditList($programme, $academic_year, $semester, $username)
	{
		return $this->academicAssessmentMapper->getModuleCreditList($programme, $academic_year, $semester, $username);
	}
	public function getBasicStudentNameList($programme, $academic_year, $semester)
	{
		return $this->academicAssessmentMapper->getBasicStudentNameList($programme, $academic_year, $semester);
	}
	public function listMarksDatail($stdId, $stdSemester, $organisation_id)
	{
		return $this->academicAssessmentMapper->listMarksDatail($stdId, $stdSemester, $organisation_id);
	}

	public function inserRepeatConsolidatedMark($data, $organisation_id, $id)
	{
		return $this->academicAssessmentMapper->inserRepeatConsolidatedMark($data, $organisation_id, $id);
	}

	public function insertReAssessmentMark($data, $moduleData, $organisation_id, $username, $userrole)
	{
		return $this->academicAssessmentMapper->insertReAssessmentMark($data, $moduleData, $organisation_id, $username, $userrole);
	}

	public function getStudentLists($caution, $stdId, $stdSemester, $organisation_id, $username, $userrole)
	{
		return $this->academicAssessmentMapper->getStudentLists($caution, $stdId, $stdSemester, $organisation_id, $username, $userrole);
	}

	public function listAll($caution, $stdId, $stdSemester, $organisation_id, $username)
	{
		return $this->academicAssessmentMapper->findAll($caution, $stdId, $stdSemester, $organisation_id, $username);
	}

}
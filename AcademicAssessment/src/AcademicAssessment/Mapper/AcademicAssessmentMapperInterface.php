<?php

namespace AcademicAssessment\Mapper;

use AcademicAssessment\Model\AcademicAssessment;

interface AcademicAssessmentMapperInterface
{
	/*
	* Getting the id for username
	*/
	
	public function getUserDetailsId($tableName, $username);
	
	/*
	* Get organisation id based on the username
	*/
	
	public function getOrganisationId($tableName, $username);

	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);

	public function deleteCompileAssessment($programmes_id, $academic_modules_allocation_id, $assessment_component_id, $section, $organisation_id);

	public function getStudentCompiledMarksList($programmes_id, $academic_modules_allocation_id, $assessment_component_id, $section, $organisation_id);

	public function listSelectData($tableName, $columnName);

	public function listSelectData1($tableName, $columnName, $organisation_id, $username);

	public function getStudentConsolidatedMarks($programme, $academic_year, $semester, $username);

	public function getModuleCreditList($programme, $academic_year, $semester, $username);

	public function getBasicStudentNameList($programme, $academic_year, $semester);	

	public function listMarksDatail($stdId, $stdSemester, $organisation_id);

	public function inserRepeatConsolidatedMark($data, $organisation_id, $id);

	public function updateReConsolidatedMark($data, $organisation_id, $id);

	public function insertReAssessmentMark($data, $moduleData, $organisation_id, $username, $userrole);

	public function getStudentLists($caution, $stdId, $stdSemester, $organisation_id, $username, $userrole);

	public function findAll($caution, $stdId, $stdSemester, $organisation_id, $username);
}
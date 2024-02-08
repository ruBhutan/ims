<?php

namespace CharacterCertificate\Mapper;

use CharacterCertificate\Model\CharacterCertificate;
use CharacterCertificate\Model\CharacterEvaluationCriteria;

interface CharacterCertificateMapperInterface
{

	/**
	 * 
	 * @return array/ CharacterCertificate[]
	 */
	 
	public function findAll($tableName, $organisation_id);
	
	/*
	* Getting the id for username
	*/
	
	public function getEmployeeDetailsId($emp_id);
	
	/*
	* Get organisation id based on the username
	*/
	
	public function getOrganisationId($username);

	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype); 
	
	/**
	 * 
	 * @param type $CharacterCertificateInterface
	 * 
	 * to save budgetings
	 */
	
	public function saveCharacterEvaluation($data, $programmesId, $batch, $studentName, $username, $academic_module_tutors_id);


	public function updateCharacterEvaluation($data, $id, $academic_module_tutors_id, $employee_details_id, $organisation_id);
	
	/**
	 * 
	 * @param type $CharacterCertificateInterface
	 * 
	 * to save budgetings
	 */
	
	public function saveCriteria(CharacterEvaluationCriteria $CharacterCertificateInterface);
	
	/**
	 * 
	 * @param type $CharacterCertificateInterface
	 * 
	 * to save budgetings
	 */
	
	public function saveEvaluator(CharacterCertificate $CharacterCertificateInterface);
	
	/*
	* List Student to add awards etc
	*/
	
	public function getStudentList($studentName, $programmesId, $username, $academic_module_tutors_id);


	public function getEvaluatedCharacterStudentList($studentName, $programmesId, $username, $academic_module_tutors_id);
	
	/*
	* List Student to add awards etc
	*/
	
	public function getStudentCharacterEvaluation($studentName, $programmesId, $batch, $employee_id, $organisation_id);

	public function getEvaluatedStudentList($studentName, $programmesId, $batch, $employee_id, $organisation_id);

	public function getStudentEvaluatedRating($id, $academic_module_tutors_id, $employee_details_id);

	public function getStudentDetails($id);
	
	/*
	* Get user Details
	*/
	
	public function getUserDetailsId($userName, $tableName);
	
	/**
	 * 
	 * @param type $id
	 * 
	 * to get the list of criteria
	 */
	public function getCriteriaList($organisation_id);
	
	/**
	 * 
	 * to get the list of criteria based on id
	 */
	public function findCharacterCriteria($id);
	
	/*
	* Get list of staff for evaluator list
	*/
	
	public function getStaffList($organisation_id);
	
	/*
	* Get list of evaluators
	*/
	
	public function getEvaluatorList($organisation_id);
	
	/*
	* get list of programmes given the organisation_id
	*/
	
	public function getProgrammeList($organisation_id);
	
	/*
	* get list of programmes an evaluator given the employee details id
	*/
	
	public function getEvaluatorProgrammeList($employee_details_id);
	
	/*
	* Get the list of the batch the evaluator has to evaluate
	*/
	
	public function getBatchList($organisation_id);


	public function getAcademicModuleAllocationDetails($academic_module_tutors_id);
	
	/*
	* Get the details of the programme for a module tutor given the academic modules allocation id
	*/
	
	public function getBatchDetails($academic_modules_allocation_id, $type);
	
	/*
	* get details of the evaluators
	*/
	
	public function getEvaluatorDetails($organisation_id);

	public function crossCheckCharacterEvaluation($academic_module_tutors_id, $employee_details_id);	
	
}
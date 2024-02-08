<?php

namespace CharacterCertificate\Service;

use CharacterCertificate\Model\CharacterCertificate;
use CharacterCertificate\Model\CharacterEvaluationCriteria;

//need to add more models

interface CharacterCertificateServiceInterface
{
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|CharacterCertificateInterface[]
	*/
	
	public function listAll($tableName, $organisation_id);
	 
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
	 * @param CharacterCertificateInterface $certificateObject
	 *
	 * @param CharacterCertificateInterface $certificateObject
	 * @return CharacterCertificateInterface
	 * @throws \Exception
	 */
	 
	 public function saveCharacterEvaluation($data, $programmesId, $batch, $studentName, $username, $academic_module_tutors_id);

	 public function updateCharacterEvaluation($data, $id, $academic_module_tutors_id, $employee_details_id, $organisation_id);
	 
	 /**
	 * @param CharacterCertificateInterface $certificateObject
	 *
	 * @param CharacterCertificateInterface $certificateObject
	 * @return CharacterCertificateInterface
	 * @throws \Exception
	 */
	 
	 public function saveCriteria(CharacterEvaluationCriteria $certificateObject);
	 
	  /**
	 * @param CharacterCertificateInterface $certificateObject
	 *
	 * @param CharacterCertificateInterface $certificateObject
	 * @return CharacterCertificateInterface
	 * @throws \Exception
	 */
	 
	 public function saveEvaluator(CharacterCertificate $certificateObject);
	 
	/*
	* List Character Evaluation of the students
	*/
	
	public function getStudentList($studentName, $programmesId, $username, $academic_module_tutors_id);

	public function getEvaluatedCharacterStudentList($studentName, $programmesId, $username, $academic_module_tutors_id);
	
	/*
	* List Student to add awards etc
	*/
	
	public function getStudentCharacterEvaluation($studentName, $programmesId, $batch, $employee_id, $organisation_id);

	public function getCharacterEvaluation($id);

	public function getEvaluatedStudentList($studentName, $programmesId, $batch, $employee_id, $organisation_id);


	public function getStudentEvaluatedRating($id, $academic_module_tutors_id, $employee_details_id);

	public function getStudentDetails($id);

	public function getStdPersonalDetails($id);

	public function getStudentRelationDetails($type, $id);

	public function getOrganisationLogo($type, $organisation_id);
	
	/*
	* Get User Details
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
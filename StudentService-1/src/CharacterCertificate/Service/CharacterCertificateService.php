<?php

namespace CharacterCertificate\Service;

use CharacterCertificate\Mapper\CharacterCertificateMapperInterface;
use CharacterCertificate\Model\CharacterCertificate;
use CharacterCertificate\Model\CharacterEvaluationCriteria;

class CharacterCertificateService implements CharacterCertificateServiceInterface
{
	/**
	 * @var \Blog\Mapper\CharacterCertificateMapperInterface
	*/
	
	protected $certificateMapper;
	
	public function __construct(CharacterCertificateMapperInterface $certificateMapper) {
		$this->certificateMapper = $certificateMapper;
	}
	
	public function listAll($tableName, $organisation_id)
	{
		return $this->certificateMapper->findAll($tableName, $organisation_id);
	}
		
	public function getEmployeeDetailsId($emp_id)
	{
		return $this->certificateMapper->getEmployeeDetailsId($emp_id);
	}
	
	public function getOrganisationId($username)
	{
		return $this->certificateMapper->getOrganisationId($username);
	}

	public function getUserDetails($username, $usertype)
	{
		return $this->certificateMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->certificateMapper->getUserImage($username, $usertype);
	}
	 	
	public function saveCharacterEvaluation($data, $programmesId, $batch, $studentName, $username, $academic_module_tutors_id) 
	{
		return $this->certificateMapper->saveCharacterEvaluation($data, $programmesId, $batch, $studentName, $username, $academic_module_tutors_id);
	}


	public function updateCharacterEvaluation($data, $id, $academic_module_tutors_id, $employee_details_id, $organisation_id)
	{
		return $this->certificateMapper->updateCharacterEvaluation($data, $id, $academic_module_tutors_id, $employee_details_id, $organisation_id);
	}
	
	public function saveCriteria(CharacterEvaluationCriteria $certificateObject) 
	{
		return $this->certificateMapper->saveCriteria($certificateObject);
	}
	
	public function saveEvaluator(CharacterCertificate $certificateObject) 
	{
		return $this->certificateMapper->saveEvaluator($certificateObject);
	}
		
	public function getStudentList($studentName, $programmesId, $username, $academic_module_tutors_id)
	{
		return $this->certificateMapper->getStudentList($studentName, $programmesId, $username, $academic_module_tutors_id);
	}

	public function getEvaluatedCharacterStudentList($studentName, $programmesId, $username, $academic_module_tutors_id)
	{
		return $this->certificateMapper->getEvaluatedCharacterStudentList($studentName, $programmesId, $username, $academic_module_tutors_id);
	}
			
	public function getStudentCharacterEvaluation($studentName, $programmesId, $batch, $employee_id, $organisation_id)
	{
		return $this->certificateMapper->getStudentCharacterEvaluation($studentName, $programmesId, $batch, $employee_id, $organisation_id);
	}

	public function getCharacterEvaluation($id)
	{
		return $this->certificateMapper->getCharacterEvaluation($id);
	}


	public function getEvaluatedStudentList($studentName, $programmesId, $batch, $employee_id, $organisation_id)
	{
		return $this->certificateMapper->getEvaluatedStudentList($studentName, $programmesId, $batch, $employee_id, $organisation_id);
	}


	public function getStudentEvaluatedRating($id, $academic_module_tutors_id, $employee_details_id)
	{
		return $this->certificateMapper->getStudentEvaluatedRating($id, $academic_module_tutors_id, $employee_details_id);
	}


	public function getStudentDetails($id)
	{
		return $this->certificateMapper->getStudentDetails($id);
	}

	public function getStdPersonalDetails($id)
	{
		return $this->certificateMapper->getStdPersonalDetails($id);
	}

	public function getStudentRelationDetails($type, $id)
	{
		return $this->certificateMapper->getStudentRelationDetails($type, $id);
	}
	
	public function getOrganisationLogo($type, $organisation_id)
	{
		return $this->certificateMapper->getOrganisationLogo($type, $organisation_id);
	}


	public function getUserDetailsId($userName, $tableName)
	{
		return $this->certificateMapper->getUserDetailsId($userName, $tableName);
	}
	
	public function getCriteriaList($organisation_id)
	{
		return $this->certificateMapper->getCriteriaList($organisation_id);
	}
	
	public function findCharacterCriteria($id)
	{
		return $this->certificateMapper->findCharacterCriteria($id);
	}
		
	public function getStaffList($organisation_id)
	{
		return $this->certificateMapper->getStaffList($organisation_id);
	}
		
	public function getProgrammeList($organisation_id)
	{
		return $this->certificateMapper->getProgrammeList($organisation_id);
	}
	
	public function getEvaluatorList($organisation_id)
	{
		return $this->certificateMapper->getEvaluatorList($organisation_id);
	}
		
	public function getEvaluatorProgrammeList($employee_details_id)
	{
		return $this->certificateMapper->getEvaluatorProgrammeList($employee_details_id);
	}
	
	public function getBatchList($organisation_id)
	{
		return $this->certificateMapper->getBatchList($organisation_id);
	}

	public function getAcademicModuleAllocationDetails($academic_module_tutors_id)
	{
		return $this->certificateMapper->getAcademicModuleAllocationDetails($academic_module_tutors_id);
	}
		
	public function getBatchDetails($academic_modules_allocation_id, $type)
	{
		return $this->certificateMapper->getBatchDetails($academic_modules_allocation_id, $type);
	}
		
	public function getEvaluatorDetails($organisation_id)
	{
		return $this->certificateMapper->getEvaluatorDetails($organisation_id);
	}


	public function crossCheckCharacterEvaluation($academic_module_tutors_id, $employee_details_id)
	{
		return $this->certificateMapper->crossCheckCharacterEvaluation($academic_module_tutors_id, $employee_details_id);
	}
}
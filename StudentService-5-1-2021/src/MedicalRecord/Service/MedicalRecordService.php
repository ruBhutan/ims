<?php

namespace MedicalRecord\Service;

use MedicalRecord\Mapper\MedicalRecordMapperInterface;
use MedicalRecord\Model\MedicalRecord;

class MedicalRecordService implements MedicalRecordServiceInterface
{
	/**
	 * @var \Blog\Mapper\MedicalRecordMapperInterface
	*/
	
	protected $recordMapper;
	
	public function __construct(MedicalRecordMapperInterface $recordMapper) {
		$this->recordMapper = $recordMapper;
	}
	 
	public function getOrganisationId($username)
	{
		return $this->recordMapper->getOrganisationId($username);
	}
	
	public function getUserDetailsId($username)
	{
		return $this->recordMapper->getUserDetailsId($username);
	}

	public function getUserDetails($username, $usertype)
	{
		return $this->recordMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->recordMapper->getUserImage($username, $usertype);
	}
	
	public function listAll($tableName)
	{
		return $this->recordMapper->findAll($tableName);
	}
	 
	public function findMedicalRecord($id)
	{
		return $this->recordMapper->findMedicalRecord($id);
	}
        	
	public function save(MedicalRecord $recordObject) 
	{
		return $this->recordMapper->saveDetails($recordObject);
	}
		
	public function getStudentList($studentName, $studentId, $programme, $organisation_id)
	{
		return $this->recordMapper->getStudentList($studentName, $studentId, $programme, $organisation_id);
	}
		
	public function getStudentMedicalRecords($studentName, $studentId, $programme, $organisation_id)
	{
		return $this->recordMapper->getStudentMedicalRecords($studentName, $studentId, $programme, $organisation_id);
	}
	
	public function getStudentDetails($id)
	{
		return $this->recordMapper->getStudentDetails($id);
	}
		
	public function listMedicalRecords($organisation_id)
	{
		return $this->recordMapper->listMedicalRecords($organisation_id);
	}
		
	public function getIndividualMedicalRecords($student_id)
	{
		return $this->recordMapper->getIndividualMedicalRecords($student_id);
	}

	public function getMedicalRecordedDetails($id)
	{
		return $this->recordMapper->getMedicalRecordedDetails($id);
	}
	
	public function listSelectData($tableName, $columnName)
	{
		return $this->recordMapper->listSelectData($tableName, $columnName);
	}
	
}
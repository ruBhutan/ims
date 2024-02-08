<?php

namespace MedicalRecord\Mapper;

use MedicalRecord\Model\MedicalRecord;

interface MedicalRecordMapperInterface
{
	/*
	 * Get the Organisation Id
	 */
	 
	public function getOrganisationId($username);
	
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username);

	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);
	
	/**
	 * @param int/string $id
	 * @return MedicalRecord
	 * throws \InvalidArugmentException
	 * 
	*/
	
	public function findMedicalRecord($id);

	/**
	 * 
	 * @return array/ MedicalRecord[]
	 */
	 
	public function findAll($tableName);
    
	/*
	* List Student to add awards etc
	*/
	
	public function getStudentList($studentName, $studentId, $programme, $organisation_id);
	
	/*
	* Get the list of medical records by students after search funcationality
	*/
	
	public function getStudentMedicalRecords($studentName, $studentId, $programme, $organisation_id);
	
	/**
	 * 
	 * @param type $id
	 * 
	 * to find Student details so that their names are displayed
	 */
	public function getStudentDetails($id);
	
	/**
	 * 
	 * @param type $MedicalRecordInterface
	 * 
	 * to save budgetings
	 */
	
	public function saveDetails(MedicalRecord $MedicalRecordInterface);
	
	/*
	* List medical Records for students
	*/
	
	public function listMedicalRecords($organisation_id);
	
	/*
	* Get the list of medical records for a student
	*/
	
	public function getIndividualMedicalRecords($student_id);

	public function getMedicalRecordedDetails($id);
	
	
	/**
	 * 
	 * @return array/ MedicalRecord[]
	 */
	 
	public function listSelectData($tableName, $columnName);
	
}
<?php

namespace MedicalRecord\Service;

use MedicalRecord\Model\MedicalRecord;

//need to add more models

interface MedicalRecordServiceInterface
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
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|MedicalRecordInterface[]
	*/
	
	public function listAll($tableName);

	/**
	 * Should return a single proposal
	 *
	 * @param int $id Identifier of the proposal that should be returned
	 * @return MedicalRecordInterface
	 */
	 
	public function findMedicalRecord($id);
        
	 
	 /**
	 * @param MedicalRecordInterface $recordObject
	 *
	 * @param MedicalRecordInterface $recordObject
	 * @return MedicalRecordInterface
	 * @throws \Exception
	 */
	 
	 public function save(MedicalRecord $recordObject);
	 
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
	 * Should return a set of all objectives that we can iterate over. 
	 * 
	 * The purpose of the function is the objectives for the dropdown select list
	 *
	 * @return array|MedicalRecordInterface[]
	*/
		
	public function listSelectData($tableName, $columnName);
		
		
}
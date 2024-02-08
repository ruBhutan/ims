<?php

namespace StudentAdmission\Mapper;

use StudentAdmission\Model\StudentAdmission;
use StudentAdmission\Model\RegisterStudent;
use StudentAdmission\Model\UpdateStudent;
use StudentAdmission\Model\UpdateReportedStudentDetails;
use StudentAdmission\Model\AddNewStudent;
use StudentAdmission\Model\StudentType;
use StudentAdmission\Model\StudentHouse;
use StudentAdmission\Model\StudentCategory;
use StudentAdmission\Model\UploadStudentLists;
use StudentAdmission\Model\StudentSemesterRegistration;
use StudentAdmission\Model\UpdateStudentPersonalDetails;
use StudentAdmission\Model\UpdateStudentPermanentAddr;
use StudentAdmission\Model\UpdateStudentParentDetails;
use StudentAdmission\Model\UpdateStudentGuardianDetails;
use StudentAdmission\Model\StudentPreviousSchool;
use StudentAdmission\Model\StudentRelationDetails;
use StudentAdmission\Model\UpdateStudentPreviousSchool;
use StudentAdmission\Model\StudentChangeProgramme;
use StudentAdmission\Model\UpdateChangeProgramme;
use StudentAdmission\Model\StudentFeeDetails;
use StudentAdmission\Model\StudentFeePaymentDetails;


interface StudentAdmissionMapperInterface
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
	
	public function findRegisteredStudent($id);

	public function findAllRegisteredStudent();
        
	public function findRegisteredStudentDetails($id);

	public function saveRegisteredStudent(RegisterStudent $studentAdmissionInterface, $organisation_id, $programme_id);

	public function findUpdatedStudent($id);

	public function findNewRegisteredStudentDetails($id);

	public function getNewRegisteredStudentDetails($id);

	//public function getNewStudentList($stdName, $stdCid, $stdProgramme, $organisation_id);

	public function findNewStudentList($stdProgramme);

	public function findReportedStudentList($stdProgramme);

	public function deleteNotReportedStudent($id);

	public function deleteStudentRelation($id);

	public function getGeneratedStudentIdList($organisation_id);

	public function crossCheckStudentNotAssignedId($stdProgramme);

	public function assignStudentId($stdProgramme, $organisation_id);

	public function getReportedStudentList($stdName, $stdCid, $stdProgramme, $admissionYear);

	public function getNewReportedStudentList($programmesId);



	public function getRegisteredStudentList($stdOrganisation, $stdProgramme, $stdYear, $stdGender, $stdReportStatus);

	public function findAllReportedStudent($organisation_id);
        
	public function findUpdatedStudentDetails($id);

	public function getStudentPersonalDetails($id);

	public function getStudentDetails($tableName, $id);

	public function getStdPersonalDetails($id);

	public function getStdPermanentAddrDetails($id);

	public function getStdParentDetails($id);

	public function getStudentCategoryDetails($id);

	public function getStudentContactDetails($id);

	public function getStudentNationalityDetails($id);

	public function getStudentPermanentAddrDetails($id, $type);

	public function getStudentParentDetails($id);

	public function getStudentGuardianDetails($id);

	public function getStdGuardianDetails($id);

	public function getStudentPreviousSchool($tableName, $id);

	public function getStdPreviousSchoolDetails($tableName, $id);

   public function saveNewReportedStudent(UpdateStudent $studentAdmissionInterface);

	//public function saveNewReportedStudent(UpdateStudent $studentAdmissionInterface, $status, $previousStatus, $id);

	public function findReportedStudentDetails($id);

	public function saveStudentPersonalDetails(UpdateStudentPersonalDetails $studentAdmissionInterface);

	public function crossCheckStudentPermanentAddress($id);

	public function crossCheckStdParentDetails($id);

	public function saveStudentPermanentAddr(UpdateStudentPermanentAddr $studentAdmissionInterface, $stdDzongkhag, $stdGewog, $stdVillage);

	public function updateStudentPermanentAddr(UpdateStudentPermanentAddr $studentAdmissionInterface, $stdDzongkhag, $stdGewog, $stdVillage);

	public function updateStudentParentDetails(UpdateStudentParentDetails $studentAdmissionObject, $stdFatherDzongkhag, $stdFatherGewog, $stdFatherVillage, $stdMotherDzongkhag, $stdMotherGewog, $stdMotherVillage);

	public function saveStudentGuardianDetails(UpdateStudentGuardianDetails $studentAdmissionInterface);

	public function saveStudentPreviousSchool(StudentPreviousSchool $studentAdmissionInterface);

	public function updateStudentPreviousSchool(UpdateStudentPreviousSchool $studentAdmissionInterface);

	public function crossCheckStudentRelation($parentCID, $id);

	public function findStudentRelationDetails($tableName, $id);

	public function getStudentRelationDetails($tableName, $id);

	public function getStdInitialRelationDetails($id);

	public function checkStdInitialRelationDetails($id);

	public function saveStudentRelationDetails(StudentRelationDetails $studentAdmissionInterface);

	public function saveStudentParentDetails(UpdateStudentParentDetails $studentAdmissionInterface, $stdFatherDzongkhag, $stdFatherGewog, $stdFatherVillage, $stdMotherDzongkhag, $stdMotherGewog, $stdMotherVillage);

	public function saveReportedStudentDetails(UpdateReportedStudentDetails $studentAdmissionInterface, $stdDzongkhag, $stdGewog, $stdVillage, $stdFatherDzongkhag, $stdFatherGewog, $stdFatherVillage, $stdMotherDzongkhag, $stdMotherGewog, $stdMotherVillage);

	public function findNewStudent($id);

	public function findAllNewStudent();

	public function listAllNewStudentFile($tableName, $organisation_id);
        
	public function findNewStudentDetails($id);

	public function findStudentPermanentAddressDetails($id);

	public function findStudentGuardianDetails($id);

	public function findStudentParentsDetails($id);

	public function findStudentPreviousSchoolDetails($id);

	public function findStudentSemesterDetails($id);

	public function getStudentSemesterDetails($id);

	public function crossCheckRegisterStudent($cid, $tableName);

	public function saveNewStudent(AddNewStudent $studentAdmissionInterface, $programmes_id, $country_id, $dzongkhag, $gewog, $village, $year_id, $organisation_id);

	public function updateNewStudentStatus($new_student_data, $status, $organisation_id, $stdProgramme);

	public function updateNewStudentSection($data, $programmesId);

	public function updateEditedStudentSection($data, $programmesId, $yearId, $organisation_id);

	public function getStudentHouseList($programmesId, $yearId, $organisation_id);

	public function saveNewStudentHouse($data1, $programmesId, $yearId, $organisation_id);

	public function updateEditedStudentHouse($data1, $programmesId, $yearId, $organisation_id);

	public function crossCheckSemesterAcademicYear($registration_type, $academicYear);

	public function updateStudentSemester($registration_type, $semester_data, $programmesId, $yearId, $studentName, $studentId, $organisation_id);

	public function updateNotReportedStudent(StudentSemesterRegistration $studentAdmissionInterface);

	public function getEditSectionStudentList($programmesId, $yearId, $organisation_id);

	public function getEditHouseStudentList($programmesId, $yearId, $organisation_id);

	public function getSemesterRegistrationStudentList($programmesId, $yearId, $studentName, $studentId, $organisation_id);

	public function getSemesterReportedStudentList($programmesId, $yearId, $academicYear, $studentName, $studentId);

	public function getStudentList($programmesId, $yearId, $academicYear, $studentName, $studentId);

	/**
	 * @param int/string $id
	 * @return StudentAdmission
	 * throws \InvalidArugmentException
	 * 
	*/
	
	public function findStudentType($id);

	public function findHouse($id);

	/**
	 * 
	 * @return array/ StudentAdmission[]
	 */
	 
	public function findAllStudentType($tableName);
        
	/**
	 * 
	 * @param type $id
	 * 
	 * to find details related to the StudentType
	 */
	
	public function findStudentTypeDetails($id);

	/**
	 * 
	 * @param type $StudentAdmissionInterface
	 * 
	 * to save StudentType
	 */

	public function crossCheckStudentType($stdType);
	 
	public function saveStudentType(StudentType $studentAdmissionInterface);

	public function crossCheckHouse($house_name);

	public function saveNewHouse(StudentHouse $studentAdmissionInterface);

	public function deleteStudentType(StudentAdmission $studentAdmissionInterface);

	/**
     * 
	 * @param type $id
	 * 
	 * to find details related to the Student Category
	 */
	public function findStudentCategory($id);
	public function findStudentCategoryDetails($id);
	
	/**
	 * 
	 * @return array/ StudentCategory[]
	 */
    public function findAllStudentCategory();

    public function listAllStudentHouse($tableName, $organisation_id);

    public function crossCheckStudentCategory($stdCategory);

    public function crossCheckStudentParent($parent_type, $id);

    public function crossCheckStudentParentCid($parent_type, $id);
	
	/**
	 * 
	 * @param type $StudentAdmissionInterface
	 * 
	 * to save Student Category
	 */

	public function saveStudentCategory(StudentCategory $studentAdmissionInterface);

	public function deleteStudentCategory(StudentAdmission $studentAdmissionInterface);

	public function getFileName($id);

	public function selectStudentProgramme($tableName, $columnName, $organisation_id);

	public function getStudentCurrentProgramme($student_id);

	public function listSelectData($tableName, $columnName, $organisation_id);

	public function listSelectData1($tableName, $columnName, $organisation_id);

	public function listSelectAcademicYear($tableName);

	public function getSemesterRegistrationAnnouncement($registration_type, $organisation_id);

	public function saveStudentListFile(UploadStudentLists $studentAdmissionInterface);

	public function saveBulkStudentFile(UploadStudentLists $studentAdmissionObject, $organisation_id);

	public function getStudentLists($stdName, $stdId, $stdCid, $stdProgramme, $organisation_id);

	public function getProgrammeChangeStudentLists($stdProgramme, $stdYear, $stdName, $stdId, $organisation_id);

	public function updateStudentChangeProgramme($programme_data, $stdProgramme, $stdYear, $stdName, $stdId, $organisation_id, $changeProgramme, $changeSession, $year, $semester, $academicYear, $updateDate, $updateBy);

	public function getChangedProgrammeStudentList($stdProgramme, $stdSemester, $stdYear, $organisation_id);

	public function assignParentPortalAccess($parent_type, $id, $parent_cid);

	public function getAssignedParentPortalAccess($access_details_type, $id);

	public function getSemester($organisation_id);
	
	public function getCurrentAcademicYear($academic_event_details);

	public function getSelfFinancedStudentLists($stdName, $stdId, $organisation_id);
  
	public function getSelfFinancedStudentListsToAdmin($stdName, $stdId,$organisation_id);

	public function fetchFeeCategories($organisation_id);
  
	public function saveStudentFeeDetails(StudentFeeDetails $studentFeeDetails);
	
	public function listStudentFeeList($student_id, $id);
	
	public function fetchStudentSemesterList($semester_id);
	
	public function isStudentFeesPaid($data);
	
	public function fetchStudentFeeDetails($id);
	
	public function fetchStudentFeePaymentDetails($field, $id);
	
	public function fetchStudentFeeStructureDetails($id);
	
	public function fetchPaymentTypes();
	
	public function getStudentFeeStructureDetails($organisation_id);
	
	public function saveStudentFeePaymentDetails(StudentFeePaymentDetails $studentFeePaymentDetails);
	
	public function generateBulkStudentFees($organisation_id, $student_fee_structure_id, $due_date);
	
	public function updateStudentFeeDetailsStatus($id, $status);
	
	public function deleteStudentFeeDetails($id);
	
	public function deleteStudentFeePaymentDetails($id);
	
	public function checkChequeAndDraftNumExists($data,$id);
}
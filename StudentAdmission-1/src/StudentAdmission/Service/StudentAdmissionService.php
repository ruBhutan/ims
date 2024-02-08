<?php

namespace StudentAdmission\Service;

use StudentAdmission\Mapper\StudentAdmissionMapperInterface;
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
use StudentAdmission\Model\StudentRelationDetails;
use StudentAdmission\Model\StudentPreviousSchool;
use StudentAdmission\Model\UpdateStudentPreviousSchool;
use StudentAdmission\Model\StudentChangeProgramme;
use StudentAdmission\Model\UpdateChangeProgramme;


class StudentAdmissionService implements StudentAdmissionServiceInterface
{
	/**
	 * @var \Blog\Mapper\PostMapperInterface
	*/
	
	protected $studentAdmissionMapper;
	
	public function __construct(StudentAdmissionMapperInterface $studentAdmissionMapper) {
		$this->studentAdmissionMapper = $studentAdmissionMapper;
	}

	public function getUserDetailsId($tableName, $username)
	{
		return $this->studentAdmissionMapper->getUserDetailsId($tableName, $username);
	}
	
	public function getOrganisationId($tableName, $username)
	{
		return $this->studentAdmissionMapper->getOrganisationId($tableName, $username);
	}


	public function getUserDetails($username, $usertype)
	{
		return $this->studentAdmissionMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->studentAdmissionMapper->getUserImage($username, $usertype);
	}

	public function listAllRegisteredStudent()
	{
		return $this->studentAdmissionMapper->findAllRegisteredStudent();
	}

	public function listRegisteredStudent()
	{
		return $this->studentAdmissionMapper->findRegisteredStudent();
	}

	public function findRegisteredStudent($id)
	{
		return $this->studentAdmissionMapper->findRegisteredStudent($id);
		
	}
        
	public function findRegisteredStudentDetails($id) 
	{
		return $this->studentAdmissionMapper->findRegisteredStudentDetails($id);;
	}
	
	public function saveRegisteredStudent(RegisterStudent $studentAdmissionObject, $organisation_id, $programme_id) 
	{
		return $this->studentAdmissionMapper->saveRegisteredStudent($studentAdmissionObject, $organisation_id, $programme_id);
	}

	/*public function getNewStudentList($stdName, $stdCid,$stdProgramme, $organisation_id)
	{
		return $this->studentAdmissionMapper->getNewStudentList($stdName, $stdCid, $stdProgramme, $organisation_id);
	}*/


	public function findNewStudentList($stdProgramme)
	{
		return $this->studentAdmissionMapper->findNewStudentList($stdProgramme);
	}


	public function findReportedStudentList($stdProgramme)
	{
		return $this->studentAdmissionMapper->findReportedStudentList($stdProgramme);
	}

	public function deleteNotReportedStudent($id)
	{
		return $this->studentAdmissionMapper->deleteNotReportedStudent($id);
	}

	public function deleteStudentRelation($id)
	{
		return $this->studentAdmissionMapper->deleteStudentRelation($id);
	}

	public function getGeneratedStudentIdList($organisation_id)
	{
		return $this->studentAdmissionMapper->getGeneratedStudentIdList($organisation_id);
	}

	public function crossCheckStudentNotAssignedId($stdProgramme)
	{
		return $this->studentAdmissionMapper->crossCheckStudentNotAssignedId($stdProgramme);
	}

	public function assignStudentId($stdProgramme, $organisation_id)
	{
		return $this->studentAdmissionMapper->assignStudentId($stdProgramme, $organisation_id);
	}

	

	public function getReportedStudentList($stdName, $stdCid,$stdProgramme, $admissionYear)
	{
		return $this->studentAdmissionMapper->getReportedStudentList($stdName, $stdCid, $stdProgramme, $admissionYear);
	}

	public function getNewReportedStudentList($programmesId)
	{
		return $this->studentAdmissionMapper->getNewReportedStudentList($programmesId);
	}

	public function getEditSectionStudentList($programmesId, $yearId, $organisation_id)
	{
		return $this->studentAdmissionMapper->getEditSectionStudentList($programmesId, $yearId, $organisation_id);
	}



	public function getRegisteredStudentList($stdOrganisation, $stdProgramme, $stdYear, $stdGender, $stdReportStatus)
	{
		return $this->studentAdmissionMapper->getRegisteredStudentList($stdOrganisation, $stdProgramme, $stdYear, $stdGender, $stdReportStatus);
	}

	public function listAllReportedStudent($organisation_id)
	{
		return $this->studentAdmissionMapper->findAllReportedStudent($organisation_id);
	}

	public function listUpdatedStudent()
	{
		return $this->studentAdmissionMapper->findUpdatedStudent();
	}

	public function findUpdatedStudent($id)
	{
		return $this->studentAdmissionMapper->findUpdatedStudent($id);		
	}

    // to find the new registered student details and view or update to reported.
	public function findNewRegisteredStudentDetails($id)
	{
		return $this->studentAdmissionMapper->findNewRegisteredStudentDetails($id);
	}

	public function getNewRegisteredStudentDetails($id)
	{
		return $this->studentAdmissionMapper->getNewRegisteredStudentDetails($id);
	}
        
	public function findUpdatedStudentDetails($id) 
	{
		return $this->studentAdmissionMapper->findUpdatedStudentDetails($id);
	}


	public function getStudentPersonalDetails($id)
	{
		return $this->studentAdmissionMapper->getStudentPersonalDetails($id);
	}

	public function getStudentDetails($tableName, $id)
	{
		return $this->studentAdmissionMapper->getStudentDetails($tableName,$id);
	}

	public function getStdPersonalDetails($id)
	{
		return $this->studentAdmissionMapper->getStdPersonalDetails($id);
	}


	public function getStdPermanentAddrDetails($id)
	{
		return $this->studentAdmissionMapper->getStdPermanentAddrDetails($id);
	}


	public function getStdParentDetails($id)
	{
		return $this->studentAdmissionMapper->getStdParentDetails($id);
	}


	public function getStudentCategoryDetails($id)
	{
		return $this->studentAdmissionMapper->getStudentCategoryDetails($id);
	}

	public function getStudentContactDetails($id)
	{
		return $this->studentAdmissionMapper->getStudentContactDetails($id);
	}

	public function getStudentNationalityDetails($id)
	{
		return $this->studentAdmissionMapper->getStudentNationalityDetails($id);
	}

	public function getStudentPermanentAddrDetails($id, $type)
	{
		return $this->studentAdmissionMapper->getStudentPermanentAddrDetails($id, $type);
	}


	public function getStudentParentDetails($id)
	{
		return $this->studentAdmissionMapper->getStudentParentDetails($id);
	}


	public function getStudentGuardianDetails($id)
	{
		return $this->studentAdmissionMapper->getStudentGuardianDetails($id);
	}

	public function getStdGuardianDetails($id)
	{
		return $this->studentAdmissionMapper->getStdGuardianDetails($id);
	}


	public function getStudentPreviousSchool($tableName, $id)
	{
		return $this->studentAdmissionMapper->getStudentPreviousSchool($tableName, $id);
	}


	public function getStdPreviousSchoolDetails($tableName, $id)
	{
		return $this->studentAdmissionMapper->getStdPreviousSchoolDetails($tableName, $id);
	}
	

	public function saveNewReportedStudent(UpdateStudent $studentAdmissionObject) 
	{
		return $this->studentAdmissionMapper->saveNewReportedStudent($studentAdmissionObject);
	}
	
	/*public function saveNewReportedStudent(UpdateStudent $studentAdmissionObject, $status, $previousStatus, $id) 
	{
		return $this->studentAdmissionMapper->saveNewReportedStudent($studentAdmissionObject, $status, $previousStatus, $id);
	}*/


	public function findReportedStudentDetails($id) 
	{
		return $this->studentAdmissionMapper->findReportedStudentDetails($id);;
	}

	public function saveStudentPersonalDetails(UpdateStudentPersonalDetails $studentAdmissionObject)
	{
		return $this->studentAdmissionMapper->saveStudentPersonalDetails($studentAdmissionObject);
	}


	public function crossCheckStudentPermanentAddress($id)
	{
		return $this->studentAdmissionMapper->crossCheckStudentPermanentAddress($id);
	}


	public function crossCheckStdParentDetails($id)
	{
		return $this->studentAdmissionMapper->crossCheckStdParentDetails($id);
	}

	public function saveStudentPermanentAddr(UpdateStudentPermanentAddr $studentAdmissionObject, $stdDzongkhag, $stdGewog, $stdVillage)
	{
		return $this->studentAdmissionMapper->saveStudentPermanentAddr($studentAdmissionObject, $stdDzongkhag, $stdGewog, $stdVillage);
	}


	public function updateStudentPermanentAddr(UpdateStudentPermanentAddr $studentAdmissionObject, $stdDzongkhag, $stdGewog, $stdVillage)
	{
		return $this->studentAdmissionMapper->updateStudentPermanentAddr($studentAdmissionObject, $stdDzongkhag, $stdGewog, $stdVillage);
	}


	public function updateStudentParentDetails(UpdateStudentParentDetails $studentAdmissionObject, $stdFatherDzongkhag, $stdFatherGewog, $stdFatherVillage, $stdMotherDzongkhag, $stdMotherGewog, $stdMotherVillage)
	{
		return $this->studentAdmissionMapper->updateStudentParentDetails($studentAdmissionObject, $stdFatherDzongkhag, $stdFatherGewog, $stdFatherVillage, $stdMotherDzongkhag, $stdMotherGewog, $stdMotherVillage);
	}


	public function saveStudentGuardianDetails(UpdateStudentGuardianDetails $studentAdmissionObject)
	{
		return $this->studentAdmissionMapper->saveStudentGuardianDetails($studentAdmissionObject);
	}


	public function saveStudentPreviousSchool(StudentPreviousSchool $studentAdmissionObject)
	{
		return $this->studentAdmissionMapper->saveStudentPreviousSchool($studentAdmissionObject);
	}


	public function updateStudentPreviousSchool(UpdateStudentPreviousSchool $studentAdmissionObject)
	{
		return $this->studentAdmissionMapper->updateStudentPreviousSchool($studentAdmissionObject);
	}

	public function crossCheckStudentRelation($parentCID, $id)
	{
		return $this->studentAdmissionMapper->crossCheckStudentRelation($parentCID, $id);
	}

	public function findStudentRelationDetails($tableName, $id)
	{
		return $this->studentAdmissionMapper->findStudentRelationDetails($tableName, $id);
	}

	public function getStudentRelationDetails($tableName, $id)
	{
		return $this->studentAdmissionMapper->getStudentRelationDetails($tableName, $id);
	}

	public function getStdInitialRelationDetails($id)
	{
		return $this->studentAdmissionMapper->getStdInitialRelationDetails($id);
	}

	public function checkStdInitialRelationDetails($id)
	{
		return $this->studentAdmissionMapper->checkStdInitialRelationDetails($id);
	}

	public function saveStudentRelationDetails(StudentRelationDetails $studentAdmissionObject)
	{
		return $this->studentAdmissionMapper->saveStudentRelationDetails($studentAdmissionObject);
	}


	public function saveStudentParentDetails(UpdateStudentParentDetails $studentAdmissionObject, $stdFatherDzongkhag, $stdFatherGewog, $stdFatherVillage, $stdMotherDzongkhag, $stdMotherGewog, $stdMotherVillage)
	{
		return $this->studentAdmissionMapper->saveStudentParentDetails($studentAdmissionObject, $stdFatherDzongkhag, $stdFatherGewog, $stdFatherVillage, $stdMotherDzongkhag, $stdMotherGewog, $stdMotherVillage);
	}
	
	public function saveReportedStudentDetails(UpdateReportedStudentDetails $studentAdmissionObject, $stdDzongkhag, $stdGewog, $stdVillage, $stdFatherDzongkhag, $stdFatherGewog, $stdFatherVillage, $stdMotherDzongkhag, $stdMotherGewog, $stdMotherVillage) 
	{
		return $this->studentAdmissionMapper->saveReportedStudentDetails($studentAdmissionObject, $stdDzongkhag, $stdGewog, $stdVillage, $stdFatherDzongkhag, $stdFatherGewog, $stdFatherVillage, $stdMotherDzongkhag, $stdMotherGewog, $stdMotherVillage);
	}

	public function listAllNewStudent()
	{
		return $this->studentAdmissionMapper->findAllNewStudent();
	}

	public function listAllNewStudentFile($tableName, $organisation_id)
	{
		return $this->studentAdmissionMapper->listAllNewStudentFile($tableName, $organisation_id);
	}

	public function listNewStudent()
	{
		return $this->studentAdmissionMapper->findNewStudent();
	}

	public function findNewStudent($id)
	{
		return $this->studentAdmissionMapper->findNewStudent($id);	
	}
        
	public function findNewStudentDetails($id) 
	{
		return $this->studentAdmissionMapper->findNewStudentDetails($id);
	}

	public function findStudentPermanentAddressDetails($id)
	{
		return $this->studentAdmissionMapper->findStudentPermanentAddressDetails($id);
	}

	public function findStudentGuardianDetails($id)
	{
		return $this->studentAdmissionMapper->findStudentGuardianDetails($id);
	}

	public function findStudentParentsDetails($id)
	{
		return $this->studentAdmissionMapper->findStudentParentsDetails($id);
	}

	public function findStudentPreviousSchoolDetails($id)
	{
		return $this->studentAdmissionMapper->findStudentPreviousSchoolDetails($id);
	}

	public function findStudentSemesterDetails($id)
	{
		return $this->studentAdmissionMapper->findStudentSemesterDetails($id);
	}

	public function getStudentSemesterDetails($id)
	{
		return $this->studentAdmissionMapper->getStudentSemesterDetails($id);
	}

	public function crossCheckRegisterStudent($cid, $tableName)
	{
		return $this->studentAdmissionMapper->crossCheckRegisterStudent($cid, $tableName);
	}
	
	public function saveNewStudent(AddNewStudent $studentAdmissionObject, $programmes_id, $country_id, $dzongkhag, $gewog, $village, $year_id, $organisation_id) 
	{
		return $this->studentAdmissionMapper->saveNewStudent($studentAdmissionObject, $programmes_id, $country_id, $dzongkhag, $gewog, $village, $year_id, $organisation_id);
	}


	public function updateNewStudentStatus($new_student_data, $status, $organisation_id, $stdProgramme)
	{
		return $this->studentAdmissionMapper->updateNewStudentStatus($new_student_data, $status, $organisation_id, $stdProgramme);
	}

	public function updateNewStudentSection($data, $programmesId)
	{
		return $this->studentAdmissionMapper->updateNewStudentSection($data, $programmesId);
	}

	public function updateEditedStudentSection($data, $programmesId, $yearId, $organisation_id)
	{
		return $this->studentAdmissionMapper->updateEditedStudentSection($data, $programmesId, $yearId, $organisation_id);
	}

	public function getStudentHouseList($programmesId, $yearId, $organisation_id)
	{
		return $this->studentAdmissionMapper->getStudentHouseList($programmesId, $yearId, $organisation_id);
	}

	public function getEditHouseStudentList($programmesId, $yearId, $organisation_id)
	{
		return $this->studentAdmissionMapper->getEditHouseStudentList($programmesId, $yearId, $organisation_id);
	}

	public function getSemesterRegistrationStudentList($programmesId, $yearId, $studentName, $studentId, $organisation_id)
	{
		return $this->studentAdmissionMapper->getSemesterRegistrationStudentList($programmesId, $yearId, $studentName, $studentId, $organisation_id);
	}


	public function getSemesterReportedStudentList($programmesId, $yearId, $academicYear, $studentName, $studentId)
	{
		return $this->studentAdmissionMapper->getSemesterReportedStudentList($programmesId, $yearId, $academicYear, $studentName, $studentId);
	}


	public function getStudentList($programmesId, $yearId, $academicYear, $studentName, $studentId)
	{
		return $this->studentAdmissionMapper->getStudentList($programmesId, $yearId, $academicYear, $studentName, $studentId);
	}

	public function saveNewStudentHouse($data1, $programmesId, $yearId, $organisation_id)
	{
		return $this->studentAdmissionMapper->saveNewStudentHouse($data1, $programmesId, $yearId, $organisation_id);
	}

	public function updateEditedStudentHouse($data1, $programmesId, $yearId, $organisation_id)
	{
		return $this->studentAdmissionMapper->updateEditedStudentHouse($data1, $programmesId, $yearId, $organisation_id);
	}

	public function crossCheckSemesterAcademicYear($registration_type, $academicYear)
	{
		return $this->studentAdmissionMapper->crossCheckSemesterAcademicYear($registration_type, $academicYear);
	}

	public function updateStudentSemester($registration_type, $semester_data, $programmesId, $yearId, $studentName, $studentId, $organisation_id)
	{
		return $this->studentAdmissionMapper->updateStudentSemester($registration_type, $semester_data, $programmesId, $yearId, $studentName, $studentId, $organisation_id);
	}


	public function updateNotReportedStudent(StudentSemesterRegistration $studentAdmissionObject)
	{
		return $this->studentAdmissionMapper->updateNotReportedStudent($studentAdmissionObject);
	}


	//Student Type
	public function listAllStudentType($tableName)
	{
		return $this->studentAdmissionMapper->findAllStudentType($tableName);
	}

	public function listAllStudentHouse($tableName, $organisation_id)
	{
		return $this->studentAdmissionMapper->listAllStudentHouse($tableName, $organisation_id);
	}
	 
	public function findStudentType($id)
	{
		return $this->studentAdmissionMapper->findStudentType($id);	
	}

	public function findHouse($id)
	{
		return $this->studentAdmissionMapper->findHouse($id);
	}
        
	public function findStudentTypeDetails($id) 
	{
		return $this->studentAdmissionMapper->findStudentTypeDetails($id);
	}

	public function crossCheckStudentType($stdType)
	{
		return $this->studentAdmissionMapper->crossCheckStudentType($stdType);
	}

	public function saveStudentType(StudentType $studentAdmissionObject) 
	{
		return $this->studentAdmissionMapper->saveStudentType($studentAdmissionObject);
	}

	public function crossCheckHouse($house_name)
	{
		return $this->studentAdmissionMapper->crossCheckHouse($house_name);
	}


	public function saveNewHouse(StudentHouse $studentAdmissionObject)
	{
		return $this->studentAdmissionMapper->saveNewHouse($studentAdmissionObject);
	}

	public function deleteStudentType(StudentAdmission $studentAdmissionObject)
	{
		return $this->studentAdmissionMapper->deleteStudentType($studentAdmissionObject);
	}

	//Student Category
	public function listAllStudentCategory()
	{
		return $this->studentAdmissionMapper->findAllStudentCategory();
	}

	public function listStudentCategory()
	{
		return $this->studentAdmissionMapper->findStudentCategory();
	}

	public function findStudentCategory($id)
	{
		return $this->studentAdmissionMapper->findStudentCategory($id);
		
	}
        
	public function findStudentCategoryDetails($id) 
	{
		return $this->studentAdmissionMapper->findStudentCategoryDetails($id);;
	}

	public function crossCheckStudentCategory($stdCategory)
	{
		return $this->studentAdmissionMapper->crossCheckStudentCategory($stdCategory);
	}

	public function crossCheckStudentParent($parent_type, $id)
	{
		return $this->studentAdmissionMapper->crossCheckStudentParent($parent_type, $id);
	}

	public function crossCheckStudentParentCid($parent_type, $id)
	{
		return $this->studentAdmissionMapper->crossCheckStudentParentCid($parent_type, $id);
	}
	
	public function saveStudentCategory(StudentCategory $studentAdmissionObject) 
	{
		return $this->studentAdmissionMapper->saveStudentCategory($studentAdmissionObject);
	}

	public function deleteStudentCategory(StudentAdmission $studentAdmissionObject)
	{
		return $this->studentAdmissionMapper->deleteStudentCategory($studentAdmissionObject);
	}

	public function getFileName($id)
	{
		return $this->studentAdmissionMapper->getFileName($id);
	}

	public function selectStudentProgramme($tableName, $columnName, $organisation_id)
	{
		return $this->studentAdmissionMapper->selectStudentProgramme($tableName, $columnName, $organisation_id);
	}

	public function getStudentCurrentProgramme($student_id)
	{
		return $this->studentAdmissionMapper->getStudentCurrentProgramme($student_id);
	}

	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		return $this->studentAdmissionMapper->listSelectData($tableName, $columnName, $organisation_id);
	}

	public function listSelectData1($tableName, $columnName, $organisation_id)
	{
		return $this->studentAdmissionMapper->listSelectData1($tableName, $columnName, $organisation_id);
	}

	public function listSelectAcademicYear($tableName)
	{
		return $this->studentAdmissionMapper->listSelectAcademicYear($tableName);
	}

	public function getSemesterRegistrationAnnouncement($registration_type, $organisation_id)
	{
		return $this->studentAdmissionMapper->getSemesterRegistrationAnnouncement($registration_type, $organisation_id);
	}

	public function saveStudentListFile(UploadStudentLists $studentAdmissionObject) 
	{
		return $this->studentAdmissionMapper->saveStudentListFile($studentAdmissionObject);
	}


	public function saveBulkStudentFile(UploadStudentLists $studentAdmissionObject, $organisation_id)
	{
		return $this->studentAdmissionMapper->saveBulkStudentFile($studentAdmissionObject, $organisation_id);
	}

	public function getStudentLists($stdName, $stdId, $stdCid, $stdProgramme, $organisation_id)
	{
		return $this->studentAdmissionMapper->getStudentLists($stdName, $stdId, $stdCid, $stdProgramme, $organisation_id);
	}

	public function getProgrammeChangeStudentLists($stdProgramme, $stdYear, $stdName, $stdId, $organisation_id)
	{
		return $this->studentAdmissionMapper->getProgrammeChangeStudentLists($stdProgramme, $stdYear, $stdName, $stdId, $organisation_id);
	}

	public function updateStudentChangeProgramme($programme_data, $stdProgramme, $stdYear, $stdName, $stdId, $organisation_id, $changeProgramme, $changeSession, $year, $semester, $academicYear, $updateDate, $updateBy)
	{
		return $this->studentAdmissionMapper->updateStudentChangeProgramme($programme_data, $stdProgramme, $stdYear, $stdName, $stdId, $organisation_id, $changeProgramme, $changeSession, $year, $semester, $academicYear, $updateDate, $updateBy);
	}

	public function getChangedProgrammeStudentList($stdProgramme, $stdSemester, $stdYear, $organisation_id)
	{
		return $this->studentAdmissionMapper->getChangedProgrammeStudentList($stdProgramme, $stdSemester, $stdYear, $organisation_id);
	}


	public function assignParentPortalAccess($parent_type, $id, $parent_cid)
	{
		return $this->studentAdmissionMapper->assignParentPortalAccess($parent_type, $id, $parent_cid);
	}

	public function getAssignedParentPortalAccess($access_details_type, $id)
	{
		return $this->studentAdmissionMapper->getAssignedParentPortalAccess($access_details_type, $id);
	}

	public function getCurrentAcademicYear($organisation_id)
	{
		return $this->studentAdmissionMapper->getCurrentAcademicYear($organisation_id);
	}
}
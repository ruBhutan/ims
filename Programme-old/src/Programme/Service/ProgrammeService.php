<?php

namespace Programme\Service;

use Programme\Mapper\ProgrammeMapperInterface;
use Programme\Model\Programme;
use Programme\Model\Module;
use Programme\Model\AssessmentComponent;
use Programme\Model\EditAssessmentComponent;
use Programme\Model\AssessmentComponentType;
use Programme\Model\ContinuousAssessment;
use Programme\Model\AssignModule;
use Programme\Model\AcademicYearModule;
use Programme\Model\EditAssessmentMark;
use Programme\Model\UploadModuleTutors;

class ProgrammeService implements ProgrammeServiceInterface
{
	/**
	 * @var \Blog\Mapper\ProgrammeMapperInterface
	*/
	
	protected $programmeMapper;
	
	public function __construct(ProgrammeMapperInterface $programmeMapper) {
		$this->programmeMapper = $programmeMapper;
	}
	
	public function listAll($tableName, $organisation_id)
	{
		return $this->programmeMapper->findAll($tableName, $organisation_id);
	}
	
	public function findEmpDetails($id)
	{
		return $this->programmeMapper->findEmpDetails($id);
	}
	 
	public function findProgramme($id)
	{
		return $this->programmeMapper->findProgramme($id);
	}
        
	public function findModule($id) 
	{
		return $this->programmeMapper->findModule($id);;
	}
		 
	public function findAllocatedModule($id)
	{
		return $this->programmeMapper->findAllocatedModule($id);
	}
		 
	public function getAssessmentComponentDetails($id)
	{
		return $this->programmeMapper->getAssessmentComponentDetails($id);
	}
	 
	public function getAcademicAssessmentComponentDetails($id)
	{
		return $this->programmeMapper->getAcademicAssessmentComponentDetails($id);
	}
		 
	public function getAssessmentMarkDetails($id)
	{
		return $this->programmeMapper->getAssessmentMarkDetails($id);
	}
	
	public function saveProgramme(Programme $programmeObject) 
	{
		return $this->programmeMapper->saveProgramme($programmeObject);
	}
		 
	public function updateProgramme(Programme $programmeObject)
	{
		return $this->programmeMapper->updateProgramme($programmeObject);
	}
	
	public function getProgrammeHistory($id)
	{
		return $this->programmeMapper->getProgrammeHistory($id);
	}
		 
	public function getFileName($id, $category)
	{
		return $this->programmeMapper->getFileName($id, $category);
	}
	
	public function saveModule(Module $moduleObject, $data) 
	{
		return $this->programmeMapper->saveModule($moduleObject, $data);
	}
	
	public function saveModuleAllocation($moduleData)
	{
		return $this->programmeMapper->saveModuleAllocation($moduleData);
	}
		 
	public function saveAllModuleAllocation($organisation_id)
	{
		return $this->programmeMapper->saveAllModuleAllocation($organisation_id);
	}
	
	public function saveMissingModuleAllocation($organisation_id)
	{
		return $this->programmeMapper->saveMissingModuleAllocation($organisation_id);
	}
	
	public function allocateDpdMarks($organisation_id)
	{
		return $this->programmeMapper->allocateDpdMarks($organisation_id);
	}
		 
	public function saveModuleTutors($tutorData)
	{
		return $this->programmeMapper->saveModuleTutors($tutorData);
	}
	
	public function saveAcademicModuleToTutorAssignment($data)
	{
		return $this->programmeMapper->saveAcademicModuleToTutorAssignment($data);
	}
	 
	public function saveModuleTutorsAssignment($tutorData)
	{
		return $this->programmeMapper->saveModuleTutorsAssignment($tutorData);
	}
	 
	public function saveModuleCoordinator($coordinatorData)
	{
		return $this->programmeMapper->saveModuleCoordinator($coordinatorData);
	}
	
	public function checkModuleCoordinatorAssignment($coordinatorData)
	{
		return $this->programmeMapper->checkModuleCoordinatorAssignment($coordinatorData);
	}

	public function crossCheckCompiled($batch, $section, $continuous_assessment_id)
	{
		return $this->programmeMapper->crossCheckCompiled($batch, $section, $continuous_assessment_id);
	}
        
	/*public function saveModuleTutorFile(UploadModuleTutors $uploadModel, $organisation_id)
	{
		return $this->programmeMapper->saveModuleTutorFile($uploadModel, $organisation_id);
	}*/
	 
	public function saveAssessmentComponent($data)
	{
		return $this->programmeMapper->saveAssessmentComponent($data);
	}
		 
	public function saveEditedAssessmentComponent(EditAssessmentComponent $assessmentObject)
	{
		return $this->programmeMapper->saveEditedAssessmentComponent($assessmentObject);
	}
		 
	public function saveComponentType(AssessmentComponentType $moduleObject)
	{
		return $this->programmeMapper->saveComponentType($moduleObject);
	}
		 
	public function crosscheckProgrammeModule($module_code, $programmes_id)
	{
		return $this->programmeMapper->crosscheckProgrammeModule($module_code, $programmes_id);
	}
	
	public function assessmentTimeCheck($assessment, $section)
	{
		return $this->programmeMapper->assessmentTimeCheck($assessment, $section);
	}
		 
	public function checkSemesterMarkEntry($academic_modules_allocation_id, $assessment, $section, $organisation_id, $username)
	{
		return $this->programmeMapper->checkSemesterMarkEntry($academic_modules_allocation_id, $assessment, $section, $organisation_id, $username);
	}

	public function getAssessmentComponentType($organisation_id)
	{
		return $this->programmeMapper->getAssessmentComponentType($organisation_id);
	}
	 
	public function saveMarkAllocation($assessmentData)
	{
		return $this->programmeMapper->saveMarkAllocation($assessmentData);
	}
	 
	public function saveDpdMarkAllocation($assessmentData)
	{
		return $this->programmeMapper->saveDpdMarkAllocation($assessmentData);
	}
		 
	public function saveAssessmentMarks($data, $programmesId, $batch, $section, $continuous_assessment_id, $assessment_type)
	{
		return $this->programmeMapper->saveAssessmentMarks($data, $programmesId, $batch, $section, $continuous_assessment_id, $assessment_type);
	}
	 
	public function updateAssessmentMarks($data, $programmesId, $batch, $section, $continuous_assessment_id, $assessment_type)
	{
		return $this->programmeMapper->updateAssessmentMarks($data, $programmesId, $batch, $section, $continuous_assessment_id, $assessment_type);
	}

	public function deleteAssessmentMarks($programmesId, $batch, $section, $continuous_assessment_id, $assessment_type)
	{
		return $this->programmeMapper->deleteAssessmentMarks($programmesId, $batch, $section, $continuous_assessment_id, $assessment_type);
	}

	public function deleteAssessmentMarkAllocation($id)
	{
		return $this->programmeMapper->deleteAssessmentMarkAllocation($id);
	}
	
	public function saveStudentElectiveModules($data, $academic_modules_allocation_id, $organisation_id)
	{
		return $this->programmeMapper->saveStudentElectiveModules($data, $academic_modules_allocation_id, $organisation_id);
	}
		 
	public function saveEditedAssessmentMark($markData)
	{
		return $this->programmeMapper->saveEditedAssessmentMark($markData);
	}
	
	public function saveEditedCompiledMark($marks_data)
	{
		return $this->programmeMapper->saveEditedCompiledMark($marks_data);
	}

	public function crossCheckAcademicAssessmentMarks($id)
	{
		return $this->programmeMapper->crossCheckAcademicAssessmentMarks($id);
	}
        
	public function crossCheckAsssignment($academic_modules_allocation_id, $assessment, $section, $username)
	{
		return $this->programmeMapper->crossCheckAsssignment($academic_modules_allocation_id, $assessment, $section, $username);
	}
	
	public function checkCompiledMarks($academic_modules_allocation_id, $section, $assessment_type)
	{
		return $this->programmeMapper->checkCompiledMarks($academic_modules_allocation_id, $section, $assessment_type);
	}
	
	public function compileMarks($academic_modules_allocation_id, $section, $assessment_type, $organisation_id)
	{
		return $this->programmeMapper->compileMarks($academic_modules_allocation_id, $section, $assessment_type, $organisation_id);
	}
	
	public function getStudentMarks($assessment, $section)
	{
		return $this->programmeMapper->getStudentMarks($assessment, $section);
	}
				 
	public function getOrganisationId($username, $usertype)
	{
		 return $this->programmeMapper->getOrganisationId($username, $usertype);
	}
		
	public function getUserDetailsId($username, $tableName)
	{
		return $this->programmeMapper->getUserDetailsId($username, $tableName);
	}

	public function getUserDetails($username, $usertype)
	{
		return $this->programmeMapper->getUserDetails($username, $usertype);
	}

    public function getUserImage($username, $usertype)
    {
    	return $this->programmeMapper->getUserImage($username, $usertype);
    }
		
	public function findStudentId($id)
	{
		return $this->programmeMapper->findStudentId($id);
	}
	
	public function getStudentDetails($id)
	{
		return $this->programmeMapper->getStudentDetails($id);
	}
		
	public function getEmployeeDetails($empId)
	{
		return $this->programmeMapper->getEmployeeDetails($empId);
	}
		
	public function getBatch($academic_modules_allocation_id, $assessment, $assessment_for)
	{
		return $this->programmeMapper->getBatch($academic_modules_allocation_id, $assessment, $assessment_for);
	}
		
	public function getModuleTutorList($employee_id)
	{
		return $this->programmeMapper->getModuleTutorList($employee_id);
	}
	
	public function getModuleCoordinatorList($employee_id)
	{
		return $this->programmeMapper->getModuleCoordinatorList($employee_id);
	}
		
	public function getTutorAssessmentList($employee_id)
	{
		return $this->programmeMapper->getTutorAssessmentList($employee_id);
	}
	
	public function getDpdAssessmentList($organisation_id)
	{
		return $this->programmeMapper->getDpdAssessmentList($organisation_id);
	}
		
	public function listProgrammes($organisation_id)
	{
		return $this->programmeMapper->listProgrammes($organisation_id);
	}
	
	public function listModules($organisation_id)
	{
		return $this->programmeMapper->listModules($organisation_id);
	}
	
	public function getModuleListByProgramme($programmes_id)
	{
		return $this->programmeMapper->getModuleListByProgramme($programmes_id);
	}
	
	public function getAllocatedModules($organisation_id)
	{
		return $this->programmeMapper->getAllocatedModules($organisation_id);
	}
	
	public function getUnallocatedModule($organisation_id)
	{
		return $this->programmeMapper->getUnallocatedModule($organisation_id);
	}
	
	public function getAllocatedModuleWithTutors($organisation_id)
	{
		return $this->programmeMapper->getAllocatedModuleWithTutors($organisation_id);
	}
	
	public function getAllocatedModuleToTutors($organisation_id)
	{
		return $this->programmeMapper->getAllocatedModuleToTutors($organisation_id);
	}
	
	public function getAssignedAcademicModules($organisation_id)
	{
		return $this->programmeMapper->getAssignedAcademicModules($organisation_id);
	}
	
	public function getAssignedAcademicModulesBySemester($programmes_id, $academic_modules_id)
	{
		return $this->programmeMapper->getAssignedAcademicModulesBySemester($programmes_id, $academic_modules_id);
	}
	
	public function getAllocatedModuleWithCoordinators($organisation_id)
	{
		return $this->programmeMapper->getAllocatedModuleWithCoordinators($organisation_id);
	}
	
	public function getAcademicModuleAssessment($organisation_id)
	{
		return $this->programmeMapper->getAcademicModuleAssessment($organisation_id);
	}
	 
	public function getModuleAllocationPresent($organisation_id)
	{
		return $this->programmeMapper->getModuleAllocationPresent($organisation_id);
	}
	
	public function getAssessmentComponent($organisation_id)
	{
		return $this->programmeMapper->getAssessmentComponent($organisation_id);
	}
	
	public function getModuleTutors($organisation_id)
	{
		return $this->programmeMapper->getModuleTutors($organisation_id);
	}
		
	public function getTutorDetail($tutorIds)
	{
		return $this->programmeMapper->getTutorDetail($tutorIds);
	}
		
	public function getAcademicYearModule($programmes_id, $semester)
	{
		return $this->programmeMapper->getAcademicYearModule($programmes_id, $semester);
	}
		
	public function getStudentAssessment($programmesId, $batch, $assessment)
	{
		return $this->programmeMapper->getStudentAssessment($programmesId, $batch, $assessment);
	}
	
	public function getStudentAssessmentMarks($assessment_component_id, $section, $type)
	{
		return $this->programmeMapper->getStudentAssessmentMarks($assessment_component_id, $section, $type);
	}
	
	public function getCompiledMarks($academic_modules_allocation_id, $section, $type)
	{
		return $this->programmeMapper->getCompiledMarks($academic_modules_allocation_id, $section, $type);
	}
        
	public function getStudentConsolidatedMarks($programme, $academic_year, $semester)
	{
		return $this->programmeMapper->getStudentConsolidatedMarks($programme, $academic_year, $semester);
	}

	public function getModuleCreditList($programme, $academic_year, $semester)
	{
		return $this->programmeMapper->getModuleCreditList($programme, $academic_year, $semester);
	}
	
	public function getConsolidatedMarkByStudentId($id)
	{
		return $this->programmeMapper->getConsolidatedMarkByStudentId($id);
	}

	public function getStudentBlockByStudentId($id)
	{
		return $this->programmeMapper->getStudentBlockByStudentId($id);
	}
		
	public function getExaminationMarks($academic_modules_allocation_id, $programmesId, $academic_year, $section)
	{
		return $this->programmeMapper->getExaminationMarks($academic_modules_allocation_id, $programmesId, $academic_year, $section);
	}
	
	public function getStudentNameList($assessment_component_id, $section)
	{
		return $this->programmeMapper->getStudentNameList($assessment_component_id, $section);
	}
		
	public function getStudentAssessmentEditing($academic_modules_allocation_id, $assessment, $section)
	{
		return $this->programmeMapper->getStudentAssessmentEditing($academic_modules_allocation_id, $assessment, $section);
	}
		
	public function getStudentList($studentName, $section, $academic_modules_allocation_id, $programmesId, $batch, $marks_for,$status)
	{
		return $this->programmeMapper->getStudentList($studentName, $section, $academic_modules_allocation_id, $programmesId, $batch, $marks_for,$status);
	}

	public function getMissingStudentList($continuous_assessment_id, $studentName, $section, $academic_modules_allocation_id,$programmesId, $batch, $marks_for,$status)
	{
		return $this->programmeMapper->getMissingStudentList($continuous_assessment_id, $studentName, $section, $academic_modules_allocation_id,$programmesId, $batch, $marks_for,$status);
	}
	
	public function getBasicStudentNameList($programme, $academic_year, $semester, $section)
	{
		return $this->programmeMapper->getBasicStudentNameList($programme, $academic_year, $semester, $section);
	}
	
	public function getStudentListByYear($student_name, $student_id, $programme)
	{
		return $this->programmeMapper->getStudentListByYear($student_name, $student_id, $programme);
	}
		
	public function getStudentExaminationList($academic_modules_allocation_id, $section, $programmesId, $batch)
	{
		return $this->programmeMapper->getStudentExaminationList($academic_modules_allocation_id, $section, $programmesId, $batch);
	}
		
	public function checkAssessmentComponent($data)
	{
		return $this->programmeMapper->checkAssessmentComponent($data);
	}
	
	public function editStudentAssessmentMark($id)
	{
		return $this->programmeMapper->editStudentAssessmentMark($id);
	}
	
	public function editStudentCompiledMark($id)
	{
		return $this->programmeMapper->editStudentCompiledMark($id);
	}
		
	public function getSemesterList($organisation_id)
	{
		return $this->programmeMapper->getSemesterList($organisation_id);
	}

	public function getSemester($organisation_id)
	{
		return $this->programmeMapper->getSemester($organisation_id);
	}
	
	public function deleteModuleTutor($id)
	{
		return $this->programmeMapper->deleteModuleTutor($id);
	}
	
	public function deleteModuleCoordinator($id)
	{
		return $this->programmeMapper->deleteModuleCoordinator($id);
	}
	
	public function deleteAcademicYearModuleTutor($id)
	{
		return $this->programmeMapper->deleteAcademicYearModuleTutor($id);
	}
	
	public function getAssessmentComponentNumber($programme_id, $semester)
	{
		return $this->programmeMapper->getAssessmentComponentNumber($programme_id, $semester);
	}


	public function getGraduatingStudentList($programmesId, $yearId, $academicYear, $studentName, $studentId)
	{
		return $this->programmeMapper->getGraduatingStudentList($programmesId, $yearId, $academicYear, $studentName, $studentId);
	}

	public function updateGraduatedStudent($student_data, $programmesId, $yearId, $academicYear, $studentName, $studentId, $organisation_id)
	{
		return $this->programmeMapper->updateGraduatedStudent($student_data, $programmesId, $yearId, $academicYear, $studentName, $studentId, $organisation_id);
	}
	
	public function listSelectData($tableName, $columnName, $organisation_id, $username)
	{
		return $this->programmeMapper->listSelectData($tableName, $columnName, $organisation_id, $username);
	}

	public function listSelectData1($tableName, $columnName)
	{
		return $this->programmeMapper->listSelectData1($tableName, $columnName);
	}

	public function getOrganisationDocument($tableName, $document_type, $organisation_id)
	{
		return $this->programmeMapper->getOrganisationDocument($tableName, $document_type, $organisation_id);
	}
	
}
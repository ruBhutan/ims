<?php

namespace Programme\Mapper;

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

interface ProgrammeMapperInterface
{
	/**
	 * 
	 * @return array/ Programme[]
	 */
	 
	public function findAll($tableName, $organisation_id);
        
	/**
	 * 
	 * @param type $id
	 * 
	 * to find details related to Employee Details
	 */
	
	public function findEmpDetails($id);
	
	/**
	 * @param int/string $id
	 * @return Programme
	 * throws \InvalidArugmentException
	 * 
	*/
	
	public function findProgramme($id);
	
	/**
	 * 
	 * @param type $id
	 * 
	 * to find details related to the Obejctives
	 */
	
	public function findModule($id);
	
	/*
	 * Get the details of the allocated module in an academic year
	 */
	 
	public function findAllocatedModule($id);
	
	/*
	 * Get details of the assessment component
	 */
	 
	public function getAssessmentComponentDetails($id);
        
         /*
	 * Get details of the academic assessment component
	*/
	 
	public function getAcademicAssessmentComponentDetails($id);
	
	/*
	 * Get details of the assessment mark allocated
	 */
	 
	public function getAssessmentMarkDetails($id);
	
	/**
	 * 
	 * @param type $ProgrammeInterface
	 * 
	 * to save budgetings
	 */
	
	public function saveProgramme(Programme $ProgrammeInterface);
	
	/*
	 * To save the update programme
	 */
	 
	public function updateProgramme(Programme $programmeObject);
	
	 /*
	 * Get the history of the changes made to a programme
	 */
	 
	public function getProgrammeHistory($id);
	
	/*
	 * Get File Location from the database
	 */
	 
	public function getFileName($id, $category);
	
	/**
	 * 
	 * @param type $ProgrammeInterface
	 * 
	 * to save budgetings
	 */
	
	public function saveModule(Module $ModuleInterface, $data);
	
	/*
	 * To save Module Allocation for an academic year
	*/
	 
	public function saveModuleAllocation($moduleData);
	
	/*
	 * This function extracts all module definitions from "Module Description"
	 * and assigns it to an academic year.
	 *
	 * Different from saveModuleAllocation where modules are save one at a time
	 */
	 
	public function saveAllModuleAllocation($organisation_id);
	
	/*
	* Save Missing Modules Allocation
	*/
	
	public function saveMissingModuleAllocation($organisation_id);
	
	/*
	* Save Mark Allocation as per DPD
	*/
	
	public function allocateDpdMarks($organisation_id);
	
	/*
	 * To save Programme Leader and Tutors to Modules for an academic year
	 */
	 
	public function saveModuleTutors($tutorData);
	
	/*
	* Allocating Modules to Module Tutors in Bulk
	*/
	
	public function saveAcademicModuleToTutorAssignment($data);
	
	/*
	 * To save the default Module Tutors to Academic Modules
	*/
	 
	public function saveModuleTutorsAssignment($tutorData);
	
	/*
	 * To save Programme Leader/Cooridnator to Modules for an academic year
	*/
	 
	public function saveModuleCoordinator($coordinatorData);
	
	/*
	* Cross check whether a module has been assigned to a module coordinator or not
	*/
	
	public function checkModuleCoordinatorAssignment($coordinatorData);

	public function crossCheckCompiled($batch, $section, $continuous_assessment_id);
        
	/*
	 * Upload file with Module Tutors for Academic Year
	 */
	
	//public function saveModuleTutorFile(UploadModuleTutors $uploadModel, $organisation_id);
	
	/*
	 * To Save the Assessment Components for each module
	 */
	 
	 public function saveAssessmentComponent($data);
	 
	  /*
	 * to save edited assessment component
	 */
	 
	 public function saveEditedAssessmentComponent(EditAssessmentComponent $assessmentObject);
	 
	 /*
	 * Save the assessment component types
	 */
	 
	 public function saveComponentType(AssessmentComponentType $moduleObject);
	 
	 /*
	 * Crosscheck to ensure that there is no duplicate entry for a module in a programme
	 */
	 
	 public function crosscheckProgrammeModule($module_code, $programmes_id);
	 
	 /*
	* Crosscheck to while editing assessment mark
	*/
	
	public function assessmentTimeCheck($assessment, $section);
	 
	 /*
	 * Check whether the semester marks have been entered or not
	 */
	 
	public function checkSemesterMarkEntry($academic_modules_allocation_id, $assessment, $section, $organisation_id, $username);
	 
	 /*
	* Get the assessment component types
	*/
	
	public function getAssessmentComponentType($organisation_id);
	 
	 /*
	 * To save the mark allocation
	 */
	 
	 public function saveMarkAllocation($assessmentData);


	 public function deleteAssessmentMarkAllocation($id);
	 
	 /*
	* To save the mark allocation as per DPD
	*/
	 
	public function saveDpdMarkAllocation($assessmentData);
	 
	 /*
	 * To save the continuous assessment marks
	 */
	 
	 public function saveAssessmentMarks($data, $programmesId, $batch, $section, $continuous_assessment_id, $assessment_type);
	 
	 /*
	* To save the edited continuous assessment marks
	*/
	 
	public function updateAssessmentMarks($data, $programmesId, $batch, $section, $continuous_assessment_id, $assessment_type);

	public function deleteAssessmentMarks($programmesId, $batch, $section, $continuous_assessment_id, $assessment_type);
	 
	 /*
	 * Save Edited Assessment Mark
	 */
	 
	public function saveEditedAssessmentMark($markData);
	
	/*
	* Save Edited Compiled Mark
	*/
	
	public function saveEditedCompiledMark($marks_data);
	
	/*
	* Crosscheck to ensure that there is no duplicate entry for a module in a programme
	*/

	public function crossCheckAcademicAssessmentMarks($id);
	 
	 /*
	* Save Student and Elective Modules
	*/
	
	public function saveStudentElectiveModules($data, $academic_modules_allocation_id, $organisation_id);
         
	 /*
	 * check if tutor is assigned to section and/or whether assignment has already been marked
	 */
	
	public function crossCheckAsssignment($academic_modules_allocation_id, $assessment, $section, $username);
	
	/*
	 * check if a module has been compiled or not
	 */
	
	public function checkCompiledMarks($academic_modules_allocation_id, $section, $assessment_type);
	 
	 /*
	 * Get the Organisation Id
	 */
	 
	 public function getOrganisationId($username, $usertype);
	 
	 /*
	* take username and returns employee details id/student id
	*/
	
	public function getUserDetailsId($username, $tableName);

	/*
	*take username and return the employee first name, middle name and last name
	*/
	public function getUserDetails($username, $usertype);

	public function getUserImage($username, $usertype);
	
	/*
	* Get the details for the student id from the scheduled counseling appointments
	*/
	
	public function findStudentId($id);
	
	/**
	 * 
	 * @param type $id
	 * 
	 * to find Student details so that their names are displayed
	 */
	public function getStudentDetails($id);
	
	/*
	* Get the employee details
	*/
	
	public function getEmployeeDetails($empId);
	
	/*
	* Get the batch which the module is for
	*  Returns "year"
	*/
	
	public function getBatch($academic_modules_allocation_id, $assessment, $assessment_for);
	
	/*
	* get the list of modules for each tutor
	*/
	
	public function getModuleTutorList($employee_id);
	
	/*
	* get the list of modules for each coordinator
	*/
	
	public function getModuleCoordinatorList($employee_id);
	
	/*
	* Get the list of modules assessments for a given tutor
	*/
	
	public function getTutorAssessmentList($employee_id);
	
	/*
	* Get the list of modules assessments for an organisation
	*/
	
	public function getDpdAssessmentList($organisation_id);
	
	/*
	* Get the list of Programmes for an organisation
	*/
	
	public function listProgrammes($organisation_id);
	
	/*
	* Get the list of Modules for Programme based on organisation
	*/
	
	public function listModules($organisation_id);
        
        /*
	* Get the list of Modules based on Programme Id
	*/
	
	public function getModuleListByProgramme($programmes_id);
	
	/*
	* Get the list of Modules allocated for an Academic Year by organisation
	*/
	
	public function getAllocatedModules($organisation_id);
        
    /*
	* Get the list of unallocated modules
	*/
	
	public function getUnallocatedModule($organisation_id);
      
     /*
	* Get the assessment components for academic modules
	*/
	
	public function getAcademicModuleAssessment($organisation_id);
	
	/*
	 * To check whether the module allocation has been done or not.
	 */
	 
	public function getModuleAllocationPresent($organisation_id);
	
	/*
	* Get the assessment components
	*/
	
	public function getAssessmentComponent($organisation_id);
	
	/*
	* Get the list of module tutors
	*/
	
	public function getModuleTutors($organisation_id);
	
	/*
	* Get th details of the tutor details
	*/
	
	public function getTutorDetail($tutorIds);
	
	/*
	* Get the modules that are being taught for each academic year by programme
	*/
	
	public function getAcademicYearModule($programmes_id, $semester);
	
	/*
	* Get the list of modules allocated with the tutors
	*/
	
	public function getAllocatedModuleWithTutors($organisation_id);
	
	/*
	* Get the list of modules allocated with the tutors
	*/
	
	public function getAllocatedModuleToTutors($organisation_id);
	
	/*
	* Get the list of the Default Modules assigned to Module Tutors
	*/
	
	public function getAssignedAcademicModules($organisation_id);
	
	/*
	* Get the list of the Default Modules assigned to Module Tutors By Semester
	*/
	
	public function getAssignedAcademicModulesBySemester($programmes_id, $academic_modules_id);
	
	/*
	* Get the list of Module Coordinators for Organisation
	*/
	
	public function getAllocatedModuleWithCoordinators($organisation_id);
	
	/*
	* Get the marks for the student for a particular assessment
	*/
	
	public function getStudentAssessment($programmesId, $batch, $assessment);
	
	/*
	* Get the marks for the student for a particular assessment
	*/
	
	public function getStudentAssessmentMarks($assessment_component_id, $section, $type);
	
	/*
	* Get the compiled marks for the student for a particular assessment
	*/
	
	public function getCompiledMarks($academic_modules_allocation_id, $section, $type);
        
	/*
	 * Get the Consolidated Marks for All Students By Programme
	 */
	
	public function getStudentConsolidatedMarks($programme, $academic_year, $semester);

	public function getModuleCreditList($programme, $academic_year, $semester);
	
	/*
	 * Get the Consolidated Marks for A Student
	 */
	
	public function getConsolidatedMarkByStudentId($id);

	public function getStudentBlockByStudentId($id);
	
	/*
	* Get the semester marks
	*/
	
	public function getExaminationMarks($academic_modules_allocation_id, $programmesId, $academic_year, $section);
	
	/*
	* Get the names for the students for a particular assessment
	* Used for getStudentAssessmentMarks
	*/
	
	public function getStudentNameList($assessment_component_id, $section);
	
	/*
	* Get All marks for a particular assessment for a specific module for editing
	*/
	
	public function getStudentAssessmentEditing($academic_modules_allocation_id, $assessment, $section);
	
	/*
	* List Student to add the marks
	*/
	
	public function getStudentList($studentName, $section, $academic_modules_allocation_id, $programmesId, $batch, $marks_for,$status);

	public function getMissingStudentList($continuous_assessment_id, $studentName, $section, $academic_modules_allocation_id,$programmesId, $batch, $marks_for,$status);
	
	/*
	* List of students for display
	*/
	
	public function getBasicStudentNameList($programme, $academic_year, $semester, $section);
	
	/*
	* List of students by programme
	*/
	
	public function getStudentListByYear($student_name, $student_id, $programme);
	
	/*
	* List of Students to add Examination Marks
	* Different from getStudentList - need to check if there is an examination code or not.
	*/
	
	public function getStudentExaminationList($academic_modules_allocation_id, $section, $programmesId, $batch);
	
	/*
	* Check to see whether an assessment component has been assigned for an academic year
	*
	* Gets an array
	*/
	
	public function checkAssessmentComponent($data);
	
	/*
	* Updating the marks after the Student Edit Assessment Mark List is provided
	* Done for ONE student only
	*/
	
	public function editStudentAssessmentMark($id);
	
	/*
	* Updating the marks after the marks are compiled
	* Done for ONE student only
	*/
	
	public function editStudentCompiledMark($id);
	
	/*
	* Get the list of semesters given an organisation id
	*/
	
	public function getSemesterList($organisation_id);

	/*
	* Get the semester, whether even or odd
	*/

	public function getSemester($organisation_id);
	
	/*
	* Delete Assigned Module Tutor
	*/
	
	public function deleteModuleTutor($id);
	
	/*
	* Delete Module Coordinator
	*/
	
	public function deleteModuleCoordinator($id);
	
	/*
	* Delete Module Tutor Assigned for Academic Year
	*/
	
	public function deleteAcademicYearModuleTutor($id);
	
	/*
	* Compile the assessment marks
	*/
	
	public function compileMarks($academic_modules_allocation_id, $section, $assessment_type, $organisation_id);
	
	/*
	* Get the marks for the CA/SE for mass editing
	*/
	
	public function getStudentMarks($assessment, $section);
	
	/*
	* Get the number of assessment components
	*/
	
	public function getAssessmentComponentNumber($programme_id, $semester);


	public function getGraduatingStudentList($programmesId, $yearId, $academicYear, $studentName, $studentId);

	public function updateGraduatedStudent($student_data, $programmesId, $yearId, $academicYear, $studentName, $studentId, $organisation_id);
	
	/**
	 * 
	 * @return array/ Programme[]
	 */
	 
	public function listSelectData($tableName, $columnName, $organisation_id, $username);

	public function listSelectData1($tableName, $columnName);

	public function getOrganisationDocument($tableName, $document_type, $organisation_id);
	
}
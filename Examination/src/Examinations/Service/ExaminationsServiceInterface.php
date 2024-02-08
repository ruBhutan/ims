<?php

namespace Examinations\Service;

use Examinations\Model\Examinations;
use Examinations\Model\ExamHall;
use Examinations\Model\ExaminationCode;
use Examinations\Model\ExamInvigilator;

//need to add more models

interface ExaminationsServiceInterface
{
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|ExaminationsInterface[]
	*/
	
	public function listAll($tableName, $organisation_id);
	
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username);
	
	/*
	* Get organisation id based on the username
	*/
	
	public function getOrganisationId($username);
	
	/*
	* Get the User Details
	*/

	public function getUserDetails($username, $usertype);

    public function getUserImage($username, $usertype);
        
	/*
	* Save Examination Hall
	*/
	
	public function saveExaminationHall(ExamHall $examinationModel);

	public function saveBlockStudent($id);

	public function deleteBlockStudent($id);
	
	/*
	* Save Examination Timetable
	* Model is not used as we are extracting data from the form due to AJAX 
	*/
	
	public function saveExaminationTimetable($data);
	
	/*
	* Save the Hall Arrangement
	*/
	
	public function saveHallArrangement();
	
	/*
	* Save Examination Invigilator
	*/
	
	public function saveExamInvigilator(ExamInvigilator $invigilatorModel);
	
	/*
	* Save the List of Students with Back paper
	*/
	
	public function addStudentBackPaper($backpaper_data, $programme, $academic_modules_id, $backlog_academic_year, $backlog_semester);
	
	/*
	* Delete Exam Invigilator
	*/
	
	public function deleteExamInvigilator($id);

	public function getStudentDetail($id);
	
	/*
	* Get the list of students searched for by various parameters
	*/
	
	public function getStudentToAddList($studentName, $studentId, $programme, $organisation_id);
	
	/*
	* Get the list of students searched for by various parameters to add to back paper
	*/
	
	public function getStudentBackPaperList($programme, $batch);
	
	/*
	* Get the Academic Year List for Adding Backpapers
	*/
	
	public function getAcademicYearList($organisation_id);
	
	/*
	* Get the Semester List List for Adding Backpapers
	*/
	
	public function getSemesterList($organisation_id);

	public function getSemester($organisation_id);
	
	/*
	* Get the list of students that are eligible to sit for exams
	* should look into the attendance, finance records etc.
	*
	* This function will also take care of getting the list of non-eligible students
	*/
	
	public function getEligibleStudentList($data, $organisation_id, $type);
	
	/*
	* Get the reasons for non-eligibility, given a student id
	*/
	
	public function getNonEligibilityReasons($id);
	
	/*
	* Get the details from the non-eligibility table
	*/
	
	public function getExaminationNonEligibilityDetails($id);
	
	/*
	* Change the eligibility for the student examination 
	*/
	
	public function changeStudentEligibility($data);
	
	/*
	* Generate Examination Codes for Students
	*/
	
	public function generateExamCodes(ExaminationCode $examinationModel, $data);
	
	/*
	* Get the Examination code, given a programme and module name
	*/
	
	public function getExaminationCode($data);
	
	/*
	* Get Examination Dates - the start and end of the semester exams
	*/
	
	public function getExaminationDates($organisation_id, $data);

	public function getExaminationTiming($organisation_id, $data);
	
	/*
	* Get the Examination Timetable for a given programme, year or employee id
	*/
	
	public function getExaminationTimetable($data, $employee_id, $organisation_id);
	
	/*
	* Generate Secret Examination Codes for Students
	*/
	
	public function generateSecretCodes($data);
	
	/*
	* Generic function to get the details of a table given an id
	* 
	* Takes $table name and $id
	*/
	
	public function getTableDetails($tableName, $id);
	
	/*
	* Create the Year List such as First Year, Second Year etc
	*/
	
	public function createYearList($organisation_id);
	
	/*
	* To upload marks once moderation is done
	*/
	
	public function consolidateMarks($data);
	
	/*
	* Declare the results by organisation
	* Changes status from "moderated" to "declared"
	*/
	
	public function declareSemesterResults($organisation_id);

	public function declareSemesterPreviousResults($organisation_id);
	
	/*
	* Generate the list of students with backpaper and back year students
	*/
	
	public function generateBackpaperStudentList($data);

	public function updateRepeatSemesterModule($studentId, $programmesId, $semesterId, $module_data, $organisation_id);

	public function crossCheckExamTimetable($tableName, $academic_modules_allocation_id, $exam_date);

	public function checkRepeatModuleList($student_id, $programmes_id, $semester_id);

	public function getStudentAcademicModuleList($data);

	public function getAssignedRepeatSemesterModules($data);
	
	/**
	* Should return a set of all objectives that we can iterate over. 
	* 
	* The purpose of the function is the objectives for the dropdown select list
	*
	* @return array|ExaminationsInterface[]
	*/
	
	public function listSelectData($tableName, $columnName, $organisation_id);
		
		
}
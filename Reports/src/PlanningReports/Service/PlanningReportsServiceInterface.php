<?php

namespace PlanningReports\Service;

//use PlanningReports\Model\PlanningReports;
//use PlanningReports\Model\PlanningReportsCategory;

//need to add more models

interface PlanningReportsServiceInterface
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

	public function getFiveYearPlan();

	public function findFiveYearPlan($id);

	public function getstaffDetail($report_details, $organisation_id);

	public function getobjectiveWeight($report_details, $organisation_id);

	public function getkeyAspiration($report_details, $organisation_id);

	public function getsuccessIndicator($report_details, $organisation_id);

	public function gettrendsuccessIndicator($report_details, $organisation_id);

	public function getdefinitionsuccessIndicator($report_details, $organisation_id);

	public function getrequirementssuccessindicator($report_details, $organisation_id);
	
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|DisciplineInterface[]
	*/
	
	//public function listAll($tableName, $organisation_id, $employee_details_id);
	//public function listAll1($employee_details_id);

	/**
	 * Should return a single proposal
	 *
	 * @param int $id Identifier of the proposal that should be returned
	 * @return DisciplineInterface
	 */
	 
	//public function findStaff($id);
        
        
	 /**
	 * @param EmployeeTaskInterface $employeetaskObject
	 *
	 * @param EmployeeTaskInterface $employeetaskObject
	 * @return EmployeeTaskInterface
	 * @throws \Exception
	 */
	 
	 //public function saveCategory(PlanningReportsCategory $employeetaskObject);
	 
	/*
	* Save the EmployeeTask record of a student
	*/
	
	//public function saveEmployeeTaskRecord(PlanningReports $employeetaskObject);
	 
	/*
	* List Staff to add awards etc
	*/
	
	//public function getStaffList($staffName, $staffId, $organisation_id);
	
	/*
	* Get the list of disciplinary action of students after search funcationality
	*/
	
	//public function getStaffEmployeeTaskList($staffName, $staffId, $organisation_id);
	
	/**
	 * 
	 * @param type $id
	 * 
	 * to find Category details to edit/display
	 */
	//public function getEmployeeTaskCategoryDetails($id);
	
	//public function getEmployeeTaskRecordDetails($id);
	
	/**
	 * 
	 * @param type $id
	 * 
	 * to find Staff details so that their names are displayed
	 */
	//public function getStaffDetails($id);
	
	/*
	* Get the disciplinary record of the students
	*/
	
	//public function getEmployeeTaskRecord($organisation_id);
	
	/*
	* Get the list of disciplinary records by a student
	*/
	
	//public function getStaffEmployeeTaskRecords($staff_id);
	 
	 
	 /**
	 * Should return a set of all objectives that we can iterate over. 
	 * 
	 * The purpose of the function is the objectives for the dropdown select list
	 *
	 * @return array|DisciplineInterface[]
	*/
	
	public function listSelectData($tableName, $columnName, $organisation_id);

	//public function listSelectData1($tableName, $id);

	//public function getFileName($file_id);

	//public function getstafftaskRecord($staff_id,$from_date, $to_date);
		
		
}
<?php

namespace PlanningReports\Mapper;

//use PlanningReports\Model\PlanningReports;
//use PlanningReports\Model\PlanningReportsCategory;

interface PlanningReportsMapperInterface
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
	 * @param int/string $id
	 * @return Discipline
	 * throws \InvalidArugmentException
	 * 
	*/
	
	//public function findStaff($id);

	/**
	 * 
	 * @return array/ EmployeeTask[]
	 */
	 
	//public function findAll($tableName, $organisation_id, $employee_details_id);
	//public function findAll1($employee_details_id);
        
	/**
	 * 
	 * @param type $EmployeeTaskInterface
	 * 
	 * to save budgetings
	 */
	
	//public function saveDetails(EmployeeTaskCategory $EmployeeTaskInterface);
	
	/*
	* Save the employeetask record of a student
	*/
	
	//public function saveEmployeeTaskRecord(EmployeeTask $employeetaskObject);
	
	/*
	* List Student to add awards etc
	*/
	
	//public function getStaffList($staffName, $stafftId, $organisation_id);
	
	/*
	* Get the list of EmployeeTask action of students after search funcationality
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
	
	/*
	* Get the EmployeeTask record of the students
	*/
	
	//public function getEmployeeTaskRecord($organisation_id);
	
	/*
	* Get the list of EmployeeTask records by a student
	*/
	
	//public function getStaffEmployeeTaskRecords($staff_id);
	
	/**
	 * 
	 * @param type $id
	 * 
	 * to find Student details so that their names are displayed
	 */
	//public function getStaffDetails($id);
	
	/**
	 * 
	 * @return array/ Discipline[]
	 */
	 
	public function listSelectData($tableName, $columnName, $organisation_id);
	
	//public function listSelectData1($tableName, $id);

	//public function getFileName($file_id);

	//public function getstafftaskRecord($staff_id,$from_date, $to_date);
	
}
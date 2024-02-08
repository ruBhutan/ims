<?php

namespace EmployeeDetail\Service;

use EmployeeDetail\Model\EmployeeDetailInterface;
use EmployeeDetail\Model\EmployeeDetail;
use EmployeeDetail\Model\NewEmployeeDetail;
use EmployeeDetail\Model\NewEmployee;
use EmployeeDetail\Model\NewEmployeeDocuments;
use EmployeeDetail\Model\EmployeeAward;
use EmployeeDetail\Model\EmployeeContribution;
use EmployeeDetail\Model\EmployeeResponsibilities;
use EmployeeDetail\Model\EmployeeCommunityService;
use EmployeeDetail\Model\EmployeeRelationDetail;
use EmployeeDetail\Model\EmployeeEducation;
use EmployeeDetail\Model\EmployeePublications;
use EmployeeDetail\Model\EmployeeTrainings;
use EmployeeDetail\Model\EmployeeWorkExperience;
use EmployeeDetail\Model\EmployeeLevel;
use EmployeeDetail\Model\EmployeeTitle;
use EmployeeDetail\Model\EmployeeProfilePicture;
use EmployeeDetail\Model\EmployeePersonalDetails;
use EmployeeDetail\Model\EmployeePermanentAddress;
use EmployeeDetail\Model\EmployeeDisciplineRecord;
use EmployeeDetail\Model\EmployeeOnProbation;
use EmployeeDetail\Model\EmployeePayDetails;
use EmployeeDetail\Model\UpdateNewEmpDoc;

interface EmployeeDetailServiceInterface
{
	/**
	 * Should return a set of all employees that we can iterate over. 
     * Single entries of the array are 11 * implementing \EmployeeDetail\Model\EmployeeDetailInterface
	 *
	 * @return array|EmployeeDetailInterface[]
	*/
	
	public function listAllEmployees($organisation_id, $staff_id);

	public function listAllNewEmployees($organisation_id);

	public function listAllEmployeesOnProbation($organisation_id);

	public function getNewEmployeeDetails($id);

	public function getNewEmployeeGeneratedId($id);

	public function getFileName($new_employee_id);

	public function getOVCHroEmailId($role);

	public function getOrganisationDetails($organisation_id);

	public function getNewEmpFileName($new_employee_doc_id, $document_type);

	public function getNewEmpFileUploaded($id);

	/**
	 * Should return a single employee
	 *
	 * @param int $id Identifier of the Employee that should be returned
	 * @return EmployeeDetailInterface
	 */
	 
	public function findEmployee($id, $type);

	public function getEmpPermanentAddress($id);

	public function getEmployeeDetailsId($tableName, $id);

	public function getEmployeeProfilePicture($id);
	
	/*
	* take username and returns employee details id/student id
	*/
	
	public function getUserDetailsId($username, $tableName);
	
	/*
	* take username and returns Name and any other detail required
	*/
	
	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);
	
	/*
	 * Get the Organisation Id
	 */
	 
	public function getOrganisationId($username);
        
        
	/**
	 * Should return a single employee
	 *
	 * @param int $id Identifier of the Employee that should be returned
	 * @return EmployeeDetailInterface
	 */
        
        public function listEducationDetails($id);
	
	/**
	 * Should return a single employee
	 *
	 * @param int $id Identifier of the Employee that should be returned
	 * @return EmployeeDetailInterface
	 */
        
        public function listParentDetails($id);
	
	/**
	 * Should return a single employee
	 *
	 * @param int $id Identifier of the Employee that should be returned
	 * @return EmployeeDetailInterface
	 */
        
        public function listSpouseDetails($id);
	
	/**
	 * Should return a single employee
	 * 
	 * This function will return Awards, Community Services and Contributions
	 * 
	 * @param int $id Identifier of the Employee that should be returned
	 * @return EmployeeDetailInterface
	 */
        
        public function listAwardDetails($id);
	
	/*
	* List Employees so that Awards/Contributions/Community Service can be added
	* to respective employee
	*/
	
	public function getEmployeeList($empName, $empId, $department, $organisation_id, $self_id, $role);

	public function getEmployeeDetails($employee_details_id);

	public function getEmpPositionTitleDetail($employee_details_id);

	public function getEmpPositionLevelDetail($employee_details_id);

	public function getEmpJobProfileDetails($id);

	public function getEmpJobProfile($employee_details_id);

	public function getDepartmentEmployeeList($empName, $empId, $department, $organisation_id);

	public function getPersonalDetails($employee_id, $type);

	public function getEmploymentDetails($employee_id);

	public function getDepartmentDetails($employee_id);

	public function getStaffPositionDetails($employee_id);

	public function getEmployeeOnProbationList($empName, $empId, $department, $organisation_id);

	public function getEmployeePayDetails($id);
	
        /*
	* List Employees to add Position Level
	*/
	
	public function getEmployeeListByLevel($empName, $empId, $department, $organisation_id);
        
	/*
	* to get the list of extra curricular details such as awards, contributions etc.
	*/
	
	public function getExtraCurricularDetails($tableName, $organisation_id, $self_id);

	public function generateEmployeeId();

	public function getEvidenceFileName($id, $tableName);

	public function getEmployeeExtraDetail($id, $tableName, $type);
	
	/*
	* The following function are to add new employee details such as relation, education etc.
	*/
	
	public function saveNewEmployee(NewEmployeeDetail $employeeModel, $update_by_employee_id);

	public function updateNewEmpDoc(UpdateNewEmpDoc $employeeModel);

	public function updateNewEmployee(NewEmployee $employeeModel, $update_by_employee_id);

	public function uploadNewEmployeeOrder(NewEmployee $employeeModel, $update_by_employee_id);

	public function saveNewEmployeeDetails(NewEmployee $employeeModel);

	public function updateEmpPayDetails(EmployeePayDetails $employeeModel);

	public function updateEmployeeDetails(EmployeeDetail $employeeModel, $dzongkhag, $gewog, $village);

	public function updateEmployeePersonalDetails(EmployeeDetail $employeeModel, $previous_emp_id);
	
	public function saveNewEmployeeRelation($employee_details_id, $data);
	
	public function saveNewEmployeeEducation($employee_details_id, $data);
	
	public function saveNewEmployeeTraining($employee_details_id, $data);
	
	public function saveNewEmployeeEmployment($employee_details_id, $data);
	
	public function saveNewEmployeeResearch($employee_details_id, $data);
	
	public function saveNewEmployeeDocuments(NewEmployeeDocuments $employeeModel);
	
	
	/*
	* The following are the list of add functions to add responsibilities, contributions etc.
	*/
	
	public function saveEmployeeTitle(EmployeeTitle $employeeModel);
	
	public function saveEmployeeLevel(EmployeeLevel $employeeModel);
	
	public function saveEmployeeProfilePicture(EmployeeProfilePicture $employeeModel);
        
        public function saveEmployeePersonalDetails(EmployeePersonalDetails $employeeModel);
        
        public function saveEmployeePermanentAddress(EmployeePermanentAddress $employeeModel);
	
	public function saveEmployeeRelation(EmployeeRelationDetail $employeeModel);

	public function saveEmpJobProfile($data);

	public function deleteEmployeeRelationDetail($id);

	public function deleteEmployeeWorkExperience($id);

	public function deleteEmployeeEducation($id);

	public function deleteEmployeeTrainingDetail($id);

	public function deleteEmployeePublication($id);

	public function deleteEmployeeResponsibility($id);

	public function deleteEmployeeContribution($id);

	public function deleteEmployeeAward($id);

	public function deleteEmployeeCommunityService($id);
	
	public function saveEmployeeWorkExperience(EmployeeWorkExperience $employeeModel);

	public function updateEmployeeWorkExperience(EmployeeWorkExperience $employeeModel);

	public function saveRubEmployeeWorkExperience(EmployeeWorkExperience $employeeModel, $occupationalGroup, $positionLevel, $positionTitle, $positionCategory);
	
	public function saveEmployeeEducation(EmployeeEducation $employeeModel);
	
	public function saveEmployeeTraining(EmployeeTrainings $employeeModel);
	
	public function saveEmployeePublication(EmployeePublications $employeeModel);
	
	public function saveEmployeeAward(EmployeeAward $employeeModel);
	
	public function saveEmployeeContribution(EmployeeContribution $employeeModel);
	
	public function saveEmployeeResponsibility(EmployeeResponsibilities $employeeModel);
	
	public function saveEmployeeCommunityService(EmployeeCommunityService $employeeModel);

	public function updateEmployeeDepartment($data);

	public function updateEditEmpDepartment($data);

	public function updateEditedPositionTitleLevel($data);
        
        public function saveEmployeeDiscipline(EmployeeDisciplineRecord $employeeModel);

        public function updateEmployeeOnProbation(EmployeeOnProbation $employeeModel);
	
	/*
	* The following functions are for finding details for a given $id
	*/
	
	public function findEmployeeTitleDetails($id);
        
        /*
         * To find the leave details of the employee
         */
	
	public function findEmployeeLevelDetails($id);
        
	/*
	* Common function for other details such as contributions, awards etc
	*/
	
	public function findEmployeeExtraDetails($tableName, $id);

	public function findEmployeeRUBExtraDetails($tableName, $id);

	public function findEmployeeNonRUBExtraDetails($tableName, $id);
	
	/*
	* Get the type of report and the data for the report
	*/
	
	public function getHrReport($report_type);

	public function listSelectCategoryData($tableName, $columnName, $organisation_id);
	
	/**
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	
	public function listSelectData($tableName, $columnName);
	
}
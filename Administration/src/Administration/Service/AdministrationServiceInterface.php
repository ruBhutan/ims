<?php

namespace Administration\Service;

use Administration\Model\Administration;
use Administration\Model\User;
use Administration\Model\UserModule;
use Administration\Model\UserSubModule;
use Administration\Model\UserSubMenu;
use Administration\Model\UserRoles;
use Administration\Model\UserRoutes;
use Administration\Model\UserWorkFlow;
use Administration\Model\RouteConfiguration;
use Administration\Model\UserMainMenu;
use Administration\Model\Password;


interface AdministrationServiceInterface
{
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|AdministrationInterface[]
	*/
	
	public function listAll($tableName);

	public function listRole($tableName, $organisation_id);

	public function listAllRole($tableName);


	public function listAllUser($tableName);

	public function listUsers($tableName, $organisation_id);

	public function listAllSubMenu($tableName);

	public function listUserRoutes($tableName);

	/**
	 * Should return employee details
	 *
	 * @param int $emp_id 
	 * @return EmployeeDetails Array
	 */
	 
	public function findEmpDetails($id);
	
	/*
	 * Get the Organisation Id
	 */
	 
	public function getOrganisationId($username, $usertype);
	
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username, $tableName);

	public function getStudentId($username);

	public function getUserDetails($username, $usertype);

	public function getUserImage($username, $usertype);

	public function crossCheckUserRole($role, $organisationId);

	public function crossCheckUserRoute($menuData);

	public function crossCheckUserWorkflow($userRole, $userDept, $userUnit, $authType, $authRole, $authDept);

	public function saveUserRole(UserRoles $moduleObject);

	public function findUserRoleDetails($id);

	public function findUserDetails($id);

	public function getEmployeeDetails($id, $usertype);

	public function findLevelZeroModuleDetails($id);

	public function findModuleDetails($id);

	public function findSubMenuDetails($id);

	public function findLevelOneModuleDetails($id);

	public function findLevelTwoModuleDetails($id);

	public function findLevelThreeModuleDetails($id);

	public function findUserRouteDetails($id);

	public function findUserWorkFlowDetails($id);

	public function listAllWorkFlow($tableName, $organisation_id);

	public function crosscheckUser($username);

	public function updateUser($id, $region, $username, $userrole);

	public function saveUser(User $moduleObject, $region, $username);
	
	/*
	* Save Level One Menu
	*/
	
	public function saveMenu(UserModule $moduleObject);


	public function saveModule(UserMainMenu $moduleObject);
	
	/*
	* Save Sub Menus
	*/
	
	public function saveSubMenu(UserSubModule $moduleObject, $level);

	/*
	* To save the sub menu
	*/
	public function saveUserSubMenu(UserSubMenu $moduleObject, $userMenu);
	
	/*
	* Save the Routes
	*/
	
	public function saveRoutes(UserRoutes $moduleObject);


	public function saveUserRoutes(RouteConfiguration $moduleObject, $data);

	public function saveUserWorkFlow(UserWorkFlow $moduleObject, $userUnit);
	
	/*
	* Get the list of the Routes for the check boxes when configuring user routes
	*/
	
	public function getRoutes();


	public function getAllBroadCategory($tableName);
	 	 
	 /**
	 * Should return a set of all objectives that we can iterate over. 
	 * 
	 * The purpose of the function is the objectives for the dropdown select list
	 *
	 * @return array|AdministrationInterface[]
	*/
	
	public function listSelectData($tableName, $columnName);

	public function selectWorkFlowType($tableName, $columnName);

	public function listSelectRouteData($tableName, $columnName, $organisation_id);

	public function listOrgUserRoles($tableName, $columnName, $organisation_id);
	
	public function getUserRouteList($user_role_id);

	public function selectAllStaff($tableName);

	public function getUserType($id);

	public function selectOrgStaff($usertype, $organisation_id);

	public function listAdminData($tableName, $columnName);

	public function listHrData($tableName, $columnName);

	public function listPMSData($tableName, $columnName);

	public function listAcademicData($tableName, $columnName);

	public function listStudentData($tableName, $columnName);

	public function listPlanningData($tableName, $columnName);

	public function listBudgetingData($tableName, $columnName);

	public function listInventoryData($tableName, $columnName);

	public function listFinanceData($tableName, $columnName);

	public function listAlumniData($tableName, $columnName);

	public function listUserData($tableName, $columnName);

	public function selectAllUserRole($tableName, $columnName);

	public function selectUserRole($tableName, $columnName, $organisation_id);
		
	/*
	* Get the student id (i.e. this->employee_details_id is NULL)
	*/
	
	public function getStudentDetails($username);
	
	/*
	* Get the List of users, student/employee for whose password to change
	*/
	
	public function getSearchList($data);
	
	/*.
	* This is to get the details of the user
	* It can be either Student or Employee
	*/
	
	public function getPasswordChangerDetails($table_name, $id);

	public function crossCheckOldPassword($id, $usertype);
	
	/*
	* Function to change the password
	*/
	
	public function changePassword(Password $passwordModel, $table_name);

	public function changeUserPassword(Password $passwordModel, $table_name, $sign_in);

}
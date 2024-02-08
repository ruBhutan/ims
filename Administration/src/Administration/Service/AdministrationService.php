<?php

namespace Administration\Service;

use Administration\Mapper\AdministrationMapperInterface;
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


class AdministrationService implements AdministrationServiceInterface
{
	/**
	 * @var \Blog\Mapper\AdministrationMapperInterface
	*/
	
	protected $administrationMapper;
	
	public function __construct(AdministrationMapperInterface $administrationMapper) {
		$this->administrationMapper = $administrationMapper;
	}
	
	public function listAll($tableName)
	{
		return $this->administrationMapper->findAll($tableName);
	}

	public function listRole($tableName, $organisation_id)
	{
		return $this->administrationMapper->listRole($tableName, $organisation_id);
	}

	public function listAllRole($tableName)
	{
		return $this->administrationMapper->listAllRole($tableName);
	}

	public function listAllUser($tableName)
	{
		return $this->administrationMapper->listAllUser($tableName);
	}


	public function listUsers($tableName, $organisation_id)
	{
		return $this->administrationMapper->listUsers($tableName, $organisation_id);
	}

	public function listAllSubMenu($tableName)
	{
		return $this->administrationMapper->listAllSubMenu($tableName);
	}

	public function listUserRoutes($tableName)
	{
		return $this->administrationMapper->listUserRoutes($tableName);
	}
	
	public function findEmpDetails($id)
	{
		return $this->administrationMapper->findEmpDetails($id);
	}
	
	public function getOrganisationId($username, $usertype)
	{
		return $this->administrationMapper->getOrganisationId($username, $usertype);
	}
	
	public function getUserDetailsId($username, $tableName)
	{
		return $this->administrationMapper->getUserDetailsId($username, $tableName);
	}

	public function getStudentId($username)
	{
		return $this->administrationMapper->getStudentId($username);
	}

	public function getUserDetails($username, $usertype)
	{
		return $this->administrationMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->administrationMapper->getUserImage($username, $usertype);
	}

	public function crossCheckUserRole($role, $organisationId)
	{
		return $this->administrationMapper->crossCheckUserRole($role, $organisationId);
	}

	public function crossCheckUserRoute($menuData)
	{
		return $this->administrationMapper->crossCheckUserRoute($menuData);
	}


	public function crossCheckUserWorkflow($userRole, $userDept, $userUnit, $authType, $authRole, $authDept)
	{
		return $this->administrationMapper->crossCheckUserWorkflow($userRole, $userDept, $userUnit, $authType, $authRole, $authDept);
	}

	public function saveUserRole(UserRoles $moduleObject)
	{
		return $this->administrationMapper->saveUserRole($moduleObject);
	}

	public function findUserRoleDetails($id)
	{
		return $this->administrationMapper->findUserRoleDetails($id);
	}

	public function findUserDetails($id)
	{
		return $this->administrationMapper->findUserDetails($id);
	}

	public function getEmployeeDetails($id, $usertype)
	{
		return $this->administrationMapper->getEmployeeDetails($id, $usertype);
	}

	public function findLevelZeroModuleDetails($id)
	{
		return $this->administrationMapper->findLevelZeroModuleDetails($id);
	}


	public function findModuleDetails($id)
	{
		return $this->administrationMapper->findModuleDetails($id);
	}


	public function findSubMenuDetails($id)
	{
		return $this->administrationMapper->findSubMenuDetails($id);
	}

	public function findLevelOneModuleDetails($id)
	{
		return $this->administrationMapper->findLevelOneModuleDetails($id);
	}

	public function findLevelTwoModuleDetails($id)
	{
		return $this->administrationMapper->findLevelTwoModuleDetails($id);
	}

	public function findLevelThreeModuleDetails($id)
	{
		return $this->administrationMapper->findLevelThreeModuleDetails($id);
	}

	public function findUserRouteDetails($id)
	{
		return $this->administrationMapper->findUserRouteDetails($id);
	}

	public function findUserWorkFlowDetails($id)
	{
		return $this->administrationMapper->findUserWorkFlowDetails($id);
	}

	public function listAllWorkFlow($tableName, $organisation_id)
	{
		return $this->administrationMapper->listAllWorkFlow($tableName, $organisation_id);
	}

	public function crosscheckUser($username)
	{
		return $this->administrationMapper->crosscheckUser($username);
	}

	public function updateUser($id, $region, $username, $userrole)
	{
		return $this->administrationMapper->updateUser($id, $region, $username, $userrole);
	}

	public function saveUser(User $moduleObject, $region, $username)
	{
		return $this->administrationMapper->saveUser($moduleObject, $region, $username);
	}
		
	public function saveMenu(UserModule $moduleObject)
	{
		return $this->administrationMapper->saveMenu($moduleObject);
	}

	public function saveModule(UserMainMenu $moduleObject)
	{
		return $this->administrationMapper->saveModule($moduleObject);
	}

	public function saveSubMenu(UserSubModule $moduleObject, $level)
	{
		return $this->administrationMapper->saveSubMenu($moduleObject, $level);
	}

	public function saveUserSubMenu(UserSubMenu $moduleObject, $userMenu)
	{
		return $this->administrationMapper->saveUserSubMenu($moduleObject, $userMenu);
	}
	
	public function saveRoutes(UserRoutes $moduleObject)
	{
		return $this->administrationMapper->saveRoutes($moduleObject);
	}

	public function saveUserRoutes(RouteConfiguration $moduleObject, $data)
	{
		return $this->administrationMapper->saveUserRoutes($moduleObject, $data);
	}

	public function saveUserWorkFlow(UserWorkFlow $moduleObject, $userUnit)
	{
		return $this->administrationMapper->saveUserWorkFlow($moduleObject, $userUnit);
	}
		
	public function getRoutes()
	{
		return $this->administrationMapper->getRoutes();
	}

	public function getAllBroadCategory($tableName)
	{
		return $this->administrationMapper->getAllBroadCategory($tableName);
	}
	
	public function listSelectData($tableName, $columnName)
	{
		return $this->administrationMapper->listSelectData($tableName, $columnName);
	}

	public function selectWorkFlowType($tableName, $columnName)
	{
		return $this->administrationMapper->selectWorkFlowType($tableName, $columnName);
	}

	public function listSelectRouteData($tableName, $columnName, $organisation_id)
	{
		return $this->administrationMapper->listSelectRouteData($tableName, $columnName, $organisation_id);
	}

	public function listOrgUserRoles($tableName, $columnName, $organisation_id)
	{
		return $this->administrationMapper->listOrgUserRoles($tableName, $columnName, $organisation_id);
	}
	
	public function getUserRouteList($user_role_id)
	{
		return $this->administrationMapper->getUserRouteList($user_role_id);
	}

	public function selectAllStaff($tableName)
	{
		return $this->administrationMapper->selectAllStaff($tableName);
	}

	public function getUserType($id)
	{
		return $this->administrationMapper->getUserType($id);
	}

	public function selectOrgStaff($usertype, $organisation_id)
	{
		return $this->administrationMapper->selectOrgStaff($usertype, $organisation_id);
	}

	public function listAdminData($tableName, $columnName)
	{
		return $this->administrationMapper->listAdminData($tableName, $columnName);
	}

	public function listHrData($tableName, $columnName)
	{
		return $this->administrationMapper->listHrData($tableName, $columnName);
	}

	public function listPMSData($tableName, $columnName)
	{
		return $this->administrationMapper->listPMSData($tableName, $columnName);
	}

	public function listAcademicData($tableName, $columnName)
	{
		return $this->administrationMapper->listAcademicData($tableName, $columnName);
	}

	public function listStudentData($tableName, $columnName)
	{
		return $this->administrationMapper->listStudentData($tableName, $columnName);
	}

	public function listPlanningData($tableName, $columnName)
	{
		return $this->administrationMapper->listPlanningData($tableName, $columnName);
	}

	public function listBudgetingData($tableName, $columnName)
	{
		return $this->administrationMapper->listBudgetingData($tableName, $columnName);
	}

	public function listInventoryData($tableName, $columnName)
	{
		return $this->administrationMapper->listInventoryData($tableName, $columnName);
	}

	public function listFinanceData($tableName, $columnName)
	{
		return $this->administrationMapper->listFinanceData($tableName, $columnName);
	}

	public function listAlumniData($tableName, $columnName)
	{
		return $this->administrationMapper->listAlumniData($tableName, $columnName);
	}

	public function listUserData($tableName, $columnName)
	{
		return $this->administrationMapper->listUserData($tableName, $columnName);
	}

	public function selectAllUserRole($tableName, $columnName)
	{
		return $this->administrationMapper->selectAllUserRole($tableName, $columnName);
	}

	public function selectUserRole($tableName, $columnName, $organisation_id)
	{
		return $this->administrationMapper->selectUserRole($tableName, $columnName, $organisation_id);
	}
	
	/*
	* Get the student id (i.e. this->employee_details_id is NULL)
	*/
	
	public function getStudentDetails($username)
	{
		return $this->administrationMapper->getStudentDetails($username);
	}
	
	/*
	* Get the List of users, student/employee for whose password to change
	*/
	
	public function getSearchList($data)
	{
		return $this->administrationMapper->getSearchList($data);
	}
	
	/*.
	* This is to get the details of the user
	* It can be either Student or Employee
	*/
	
	public function getPasswordChangerDetails($table_name, $id)
	{
		return $this->administrationMapper->getPasswordChangerDetails($table_name, $id);
	}

	public function crossCheckOldPassword($id, $usertype)
	{
		return $this->administrationMapper->crossCheckOldPassword($id, $usertype);
	}
	
	/*
	* Function to change the password
	*/
	
	public function changePassword(Password $passwordModel, $table_name)
	{
		return $this->administrationMapper->changePassword($passwordModel, $table_name);
	}

	public function changeUserPassword(Password $passwordModel, $table_name, $sign_in)
	{
		return $this->administrationMapper->changeUserPassword($passwordModel, $table_name, $sign_in);
	}

}
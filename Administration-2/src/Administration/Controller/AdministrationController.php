<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Administration\Controller;

use Administration\Service\AdministrationServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Administration\Form\UserForm;
use Administration\Form\ModuleForm;
use Administration\Form\SubmoduleForm;
use Administration\Form\SubmenuForm;
use Administration\Form\UserRolesForm;
use Administration\Form\UserRoutesForm;
use Administration\Form\RoutesConfigurationForm;
use Administration\Form\UserWorkFlowForm;
use Administration\Form\MainMenuForm;
use Administration\Form\SearchForm;
use Administration\Form\PasswordSearchForm;
use Administration\Form\PasswordForm;
use Administration\Form\UserPasswordForm;
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
use Zend\View\Model\JsonModel;
use Zend\Session\Container;
use Zend\Form\Element\Select;


/**
 * Description of IndexController
 *
 */
 
class AdministrationController extends AbstractActionController
{
	protected $administrationService;
	protected $notificationService;
    protected $auditTrailService;
    protected $serviceLocator;
	protected $username;
	protected $userrole;
	protected $userregion;
	protected $usertype;
	protected $userDetails;
	protected $userImage;
	protected $employee_details_id;
	protected $student_details_id;
	protected $alumni_details_id;
	protected $job_applicant_id;
	protected $organisation_id;

	protected $keyphrase = "RUB_IMS";

	protected $parentValue;
	protected $parentValue1;
	
	public function __construct(AdministrationServiceInterface $administrationService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->administrationService = $administrationService;
		$this->notificationService = $notificationService;
        $this->auditTrailService = $auditTrailService;
        $this->serviceLocator = $serviceLocator;

		/*
		 * To retrieve the user name from the session
		*/
		$authPlugin = $serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
        $this->username = $authPlugin['username'];
		$this->userrole = $authPlugin['role'];
		$this->userregion = $authPlugin['region'];
		$this->usertype = $authPlugin['user_type_id'];
		$this->user_status_id = $authPlugin['user_status_id'];

		/*
		* Getting the student_id/employee_details_id related to username
		*/
		
		if($this->usertype == 1){
			$empData = $this->administrationService->getUserDetailsId($this->username, $tableName = 'employee_details');
			foreach($empData as $emp){
				$this->employee_details_id = $emp['id'];
			}
		}
		if($this->usertype == 2){
			$stdData = $this->administrationService->getUserDetailsId($this->username, $tableName = 'student');
			foreach($stdData as $std){
				$this->student_details_id = $std['id'];
			}
		}
		
		if($this->usertype == 4){
			$applicantData = $this->administrationService->getUserDetailsId($this->username, $tableName = 'job_applicant');
			foreach($applicantData as $data){
				$this->job_applicant_id = $data['id'];
			}
		}

		else if($this->usertype == 5){
			$alumniData = $this->administrationService->getUserDetailsId($this->username, $tableName = 'alumni');
			foreach ($alumniData as $alumni) {
				$this->alumni_details_id = $alumni['id'];
			}
		}
		
		
		//get the organisation id
		$organisationID = $this->administrationService->getOrganisationId($this->username, $this->usertype);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		} 

		//get the user details such as name
		$this->userDetails = $this->administrationService->getUserDetails($this->username, $this->usertype);

		$this->userImage = $this->administrationService->getUserImage($this->username, $this->usertype);
	}

	public function loginDetails()
	{
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
	}
	

	//to add new user
	public function addUserAction()
    {
    	$this->loginDetails();
        $form = new UserForm($this->serviceLocator);
		$userModel = new User();
		$form->bind($userModel);

		
		$allUsers = $this->administrationService->listAllUser($tableName='users');

		$users = $this->administrationService->listUsers($tableName='users', $this->organisation_id);

		//$selectAllStaff = $this->administrationService->selectAllStaff($tableName = 'employee_details');

		//$selectOrgStaff = $this->administrationService->selectOrgStaff($tableName = 'employee_details', $this->organisation_id);

		$allUserRoles = $this->administrationService->selectAllUserRole($tableName='user_role', $columnName = 'rolename');

		$userRoles = $this->administrationService->selectUserRole($tableName='user_role', $columnName = 'rolename', $this->organisation_id);

		//$selectOrganisation = $this->administrationService->listSelectData($tableName = 'organisation', $columnName = 'organisation_name');

		$message = NULL;
		
		$request = $this->getRequest();
        if ($request->isPost()) {
             $form->setData($request->getPost());
             $region = $this->getRequest()->getPost('region');
         	 $username = $this->getRequest()->getPost('username');

         	 $user_check = $this->administrationService->crosscheckUser($username);

         	 if($user_check){
         	 	 $message = 'Failure';
        		 $this->flashMessenger()->addMessage('Username already exists. If you want to edit the user, please check in the list and edit it.');
         	 }else{
         	 	if ($form->isValid()) {
	                 try {
						 $this->administrationService->saveUser($userModel, $region, $username);
						 //$this->notificationService->saveNotification('', 'ALL', 'ALL', 'Research Grant Announcement');
	                     $this->auditTrailService->saveAuditTrail("INSERT", "Users", "ALL", "SUCCESS");

						 $this->flashMessenger()->addMessage('User was successfully added');
						 return $this->redirect()->toRoute('adduser');
					 }
					 catch(\Exception $e) {
					 		$message = 'Failure';
					 		$this->flashMessenger()->addMessage($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
	             }
         	 }
         }
		 
        return array(
			'form' => $form,
			'allUsers' => $allUsers,
			'users' => $users,
			'allUserRoles' => $allUserRoles,
			'userRoles' => $userRoles,
			//'selectAllStaff' => $selectAllStaff,
			//'selectOrgStaff' => $selectOrgStaff,
			//'selectOrganisation' => $selectOrganisation,
			'organisation_id' => $this->organisation_id,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
		);
    } 
	
	public function editUserAction()
    {
    	$this->loginDetails();
    	//get the id of the user
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){ 
        	$userTypeId = $this->administrationService->getUserType($id); 
        	$editUser = $this->administrationService->findUserDetails($id);
        	$empDetails = $this->administrationService->getEmployeeDetails($id, $userTypeId);
	        $form = new UserForm($this->serviceLocator);
			$userModel = new User();
			$form->bind($userModel);
			
			$allUsers = $this->administrationService->listAllUser($tableName='users');
			$users = $this->administrationService->listUsers($tableName='users', $this->organisation_id);
			$allUserRoles = $this->administrationService->selectAllUserRole($tableName='user_role', $columnName = 'rolename');
			$userRoles = $this->administrationService->selectUserRole($tableName='user_role', $columnName = 'rolename', $this->organisation_id);
			//$selectAllStaff = $this->administrationService->selectAllStaff($tableName = 'employee_details');

			$selectOrgStaff = $this->administrationService->selectOrgStaff($userTypeId, $this->organisation_id);
			//$selectOrganisation = $this->administrationService->listSelectData($tableName = 'organisation', $columnName = 'organisation_name');

			$message = NULL;
			
			$request = $this->getRequest();
	        if ($request->isPost()) {
	             $form->setData($request->getPost());
	             $region = $this->getRequest()->getPost('region');
         	 	 $username = $this->getRequest()->getPost('username');
         	 	 $userrole = $this->getRequest()->getPost('role');
	             if ($form->isValid()) { 
	                 try {
						 $this->administrationService->updateUser($id, $region, $username, $userrole);
						 $this->auditTrailService->saveAuditTrail("EDIT", "Users", "ALL", "SUCCESS");

						 $this->flashMessenger()->addMessage('User was successfully edited');
						 return $this->redirect()->toRoute('adduser');
					 }
					 catch(\Exception $e) {
					 		$message = 'Failure';
					 		$this->flashMessenger()->addMessage($e->getMessage());
							return $this->redirect()->toRoute('adduser');
							 // Some DB Error happened, log it and let the user know
					 }
	             }
	         }
			 
	        return array(
				'form' => $form,
				'editUser' => $editUser,
				'empDetails' => $empDetails,
				'allUsers' => $allUsers,
				'users' => $users,
				'allUserRoles' => $allUserRoles,
				'userRoles' => $userRoles,
				//'selectOrganisation' => $selectOrganisation,
				//'selectAllStaff' => $selectAllStaff,
				'selectOrgStaff' => $selectOrgStaff,
				'organisation_id' => $this->organisation_id,
				'message' => $message,
			);
        }else{
        	$this->redirect()->toRoute('adduser');
        }
    }
	
	public function listUserAction()
    {
    	$this->loginDetails();
        $form = new UserForm();
		$userModel = new User();
		$form->bind($userModel);
		
		$users = $this->administrationService->listAll($tableName='users', $this->organisation_id);
		
		$request = $this->getRequest();
        if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->administrationService->saveBudgetProposal($userModel);
					 $this->redirect()->toRoute('listadministration');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'users' => $users);
    } 
    

    //to add new user roles
	public function addRolesAction()
    {
    	$this->loginDetails();
        $form = new UserRolesForm();
		$userModel = new UserRoles();
		$form->bind($userModel);

		
		$userRoles = $this->administrationService->listRole($tableName='user_role', $this->organisation_id);

		$allUserRoles = $this->administrationService->listAllRole($tableName='user_role');

		$selectOrganisation = $this->administrationService->listSelectData($tableName = 'organisation', $columnName = 'organisation_name');

		$message = NULL;
		
		$request = $this->getRequest();
        if ($request->isPost()) {
             $form->setData($request->getPost());
             $data = $this->params()->fromPost();
             $role = $data['userroles']['rolename'];
             $organisationId = $data['userroles']['organisation_id'];

             $check_role = $this->administrationService->crossCheckUserRole($role, $organisationId);
             if($check_role){
             	$message = 'Failure';
             	$this->flashMessenger()->addMessage('You have already added same role for the selected organisation. Please try with different role!');
             }else{
             	if ($form->isValid()) {
	                 try {
						 $this->administrationService->saveUserRole($userModel);
						 $this->auditTrailService->saveAuditTrail("INSERT", "User Role", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage('Role was successfully added');
						 return $this->redirect()->toRoute('addroles');
					 }
					 catch(\Exception $e) {
					 		$message = 'Failure';
					 		$this->flashMessenger()->addMessage($e->getMessage());
	                        return $this->flashMessenger()->toRoute('addroles');
	                         // Some DB Error happened, log it and let the user know
	                 }
	             }
             }
         }
		 
        return array(
			'form' => $form,
			'userRoles' => $userRoles,
			'allUserRoles' => $allUserRoles,
			'selectOrganisation' => $selectOrganisation,
			'organisation_id' => $this->organisation_id,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
		);
    } 

	
	public function editRolesAction()
    {
    	$this->loginDetails();
    	// get the id of the role
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$editRole = $this->administrationService->findUserRoleDetails($id);

	        $form = new UserRolesForm();
			$userModel = new UserRoles();
			$form->bind($userModel);
			
			$userRoles = $this->administrationService->listRole($tableName='user_role', $this->organisation_id);
			$allUserRoles = $this->administrationService->listAllRole($tableName='user_role');
			$selectOrganisation = $this->administrationService->listSelectData($tableName = 'organisation', $columnName = 'organisation_name');

			$message = NULL;
			
			$request = $this->getRequest();
	        if ($request->isPost()) {
	             $form->setData($request->getPost());
	             	if ($form->isValid()) {
		                 try {
							 $this->administrationService->saveUserRole($userModel);
							 $this->auditTrailService->saveAuditTrail("EDIT", "User Role", "ALL", "SUCCESS");
							 $this->flashMessenger()->addMessage('Role was successfully edited');
							 return $this->redirect()->toRoute('addroles');
						 }
						 catch(\Exception $e) {
						 		$message = 'Failure';
						 		$this->flashMessenger()->addMessage($e->getMessage());
								return $this->redirect()->toRoute('addroles');
								 // Some DB Error happened, log it and let the user know
						 }
		             }
	         }
			 
	        return array(
				'form' => $form,
				'editRole' => $editRole,
				'userRoles' => $userRoles,
				'allUserRoles' => $allUserRoles,
				'selectOrganisation' => $selectOrganisation,
				'organisation_id' => $this->organisation_id,
				'message' => $message,
			);
        }else{
        	return $this->redirect()->toRoute('addroles');
        }
    } 
	


    //create top level menu
	public function addModuleAction()
    {
    	$this->loginDetails();
        $form = new MainMenuForm();
		$moduleModel = new UserMainMenu();
		$form->bind($moduleModel);
		
		$menus = $this->administrationService->listAll($tableName='user_menu');
		$message = NULL;
		
		$request = $this->getRequest();
       	if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->administrationService->saveModule($moduleModel);
					 $this->auditTrailService->saveAuditTrail("INSERT", "User Menu", "ALL", "SUCCESS");
					 $this->flashMessenger()->addMessage('Module was successfully added');
					 return $this->redirect()->toRoute('addmodule');
				 }
				 catch(\Exception $e) {
				 	$message = 'Failure';
			 		$this->flashMessenger()->addMessage($e->getMessage());
					die();
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'menus' => $menus,
			'keyphrase' => $this->keyphrase,
			'message' => $message);
    }


    public function editUserModuleAction()
    {
    	$this->loginDetails();
    	//get the id of the leave
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
        	$editModule = $this->administrationService->findModuleDetails($id);

	        $form = new MainMenuForm();
			$moduleModel = new UserMainMenu();
			$form->bind($moduleModel);
			
			$menus = $this->administrationService->listAll($tableName='user_menu');

			$message = NULL;
			
			$request = $this->getRequest();
	       	if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) {
	                 try {
						 $this->administrationService->saveModule($moduleModel);
						 $this->auditTrailService->saveAuditTrail("EDIT", "User Menu", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage('Module was successfully edited');

						 return $this->redirect()->toRoute('addmodule');
					 }
					 catch(\Exception $e) {
					 		$message = 'Failure';
					 		$this->flashMessenger()->addMessage($e->getMessage());
							 die();
							 // Some DB Error happened, log it and let the user know
					 }
	             }
	         }
			 
	        return array(
				'form' => $form,
				'editModule' => $editModule,
				'menus' => $menus,
				'message' => $message,
			);
        }else{
        	return $this->redirect()->toRoute('addmodule');
        }
    }

	


    //create sub-menu
	public function addSubMenuAction()
    {
    	$this->loginDetails();
		$form = new SubmenuForm($this->serviceLocator);
		$moduleModel = new UserSubMenu();
		$form->bind($moduleModel);


		$menus = $this->administrationService->listAllSubMenu($tableName='user_menu');
		$message = NULL;
        
		$request = $this->getRequest();
        if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				// $routesData = $this->params()->fromPost();
				//$menuLevel = $this->getRequest()->getPost('user_menu_level');
				$userMenu = $this->getRequest()->getPost('user_menu_id'); 
                 try {
					 $this->administrationService->saveUserSubMenu($moduleModel, $userMenu);
					 $this->auditTrailService->saveAuditTrail("INSERT", "User Menu", "ALL", "SUCCESS");
					 $this->flashMessenger()->addMessage('Sub Menu was successfully added');
					 return $this->redirect()->toRoute('addsubmenu');
				 }
				 catch(\Exception $e) {
				 	$message = 'Failure';
			 		$this->flashMessenger()->addMessage($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'menus' => $menus,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
			//'menuList' => $menuList,
		);
    }


    // Function to edit the sub-menu leve
    public function editSubMenuAction()
    {
    	$this->loginDetails();
    	//get the id of the leave
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
        	$editSubMenu = $this->administrationService->findSubMenuDetails($id);

	        $form = new SubmenuForm();
			$moduleModel = new UserSubMenu();
			$form->bind($moduleModel);
			
			$menus = $this->administrationService->listAllSubMenu($tableName='user_menu');

			$message = NULL;
			
			$request = $this->getRequest();
	       	if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) {
	             	$userMenu = $this->getRequest()->getPost('user_menu_id'); 
	                 try {
						 $this->administrationService->saveUserSubMenu($moduleModel, $userMenu);
						 $this->auditTrailService->saveAuditTrail("EDIT", "User Menu", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage('Sub Menu was successfully edited');
						 $this->redirect()->toRoute('addsubmenu');
					 }
					 catch(\Exception $e) {
					 		$message = 'Failure';
					 		$this->flashMessenger()->addMessage($e->getMessage());
							 die();
							 // Some DB Error happened, log it and let the user know
					 }
	             }
	         }
			 
	        return array(
				'form' => $form,
				'editSubMenu' => $editSubMenu,
				'menus' => $menus,
				'message' => $message,
			);
        }else{
        	return $this->redirect()->toRoute('addsubmenu');
        }
    }

	

	//configure routes
	public function configureUserRoutesAction()
    {
    	$this->loginDetails();
    	$user_role_id = 0;
		
		$routeCategory = $this->administrationService->getAllBroadCategory($tableName='user_menu');

		//route remarks used instead of route name to make it more user friendly
		//when listing the routes
        $routesList = $this->administrationService->listSelectRouteData($tableName = 'user_routes', $columnName = 'route_remarks', $this->organisation_id);
		$userRouteList = $this->administrationService->getUserRouteList($user_role_id);

		$form = new RoutesConfigurationForm($routesList, $userRouteList);	
		$moduleModel = new RouteConfiguration();
		$form->bind($moduleModel);	
		$searchForm = new SearchForm();
		
		$routes = $this->administrationService->listAll($tableName='user_routes', $this->organisation_id);
		$userRoles = $this->administrationService->listSelectData($tableName = 'user_role', $columnName = 'rolename');
                if($this->organisation_id != 1){
                    $userRoles = $this->administrationService->listOrgUserRoles($table = 'user_role', $columnName = 'rolename', $this->organisation_id);
                }
		

		$data = array();

		$message = NULL;
        
		$request = $this->getRequest();
        if ($request->isPost()) {
             $form->setData($request->getPost());
			 $searchForm->setData($request->getPost());
             if($this->getRequest()->getPost('submit') == 'Search User Routes'){
				 if($searchForm->isValid()){
					$user_role_id = $this->getRequest()->getPost('user_role_id');
					$routesList = $this->administrationService->listSelectRouteData($tableName = 'user_routes', $columnName = 'route_remarks', $this->organisation_id);
					$userRouteList = $this->administrationService->getUserRouteList($user_role_id);
					$form = new RoutesConfigurationForm($routesList, $userRouteList);
				 }
			 }
			 else{
				if($form->isValid()) {
					$data = $this->extractFormData($routesList);
					 try {
						$this->administrationService->saveUserRoutes($moduleModel, $data);
						$this->auditTrailService->saveAuditTrail("INSERT", "User Role Routes", "ALL", "SUCCESS");
						$this->flashMessenger()->addMessage('User routes were successfully Configured');
						return $this->redirect()->toRoute('configureuserroutes');
					 }
					 catch(\Exception $e) {
					 	$message = 'Failure';
				 		$this->flashMessenger()->addMessage($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 } 
			 }
			 
         }
		 
        return array(
			'form' => $form,
			'searchForm' => $searchForm,
			'routes' => $routes,
			'routesList' => $routesList,
			'userRoles' => $userRoles,
			//'orgUserRoles' => $orgUserRoles,
			'routeCategory' => $routeCategory,
			'organisation_id' => $this->organisation_id,
			'user_role_id' => $user_role_id,
			'message' => $message,
		);
    }
	
	public function addUserRoutesAction()
    {
        $this->loginDetails();
		$form = new UserRoutesForm($this->serviceLocator);
		$moduleModel = new UserRoutes();
		$form->bind($moduleModel);

		//$menuList = $this->administrationService->listSelectData($tableName = 'user_module', $columnName = 'module_name');		
		$routes = $this->administrationService->listUserRoutes($tableName='user_routes');
		$message = NULL;
        
		$request = $this->getRequest();
        if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {

				// $routesData = $this->params()->fromPost();
				$broadCategory = $this->getRequest()->getPost('route_category');
				$menuData = $this->getRequest()->getPost('user_sub_menu_id');
				$routesDetails = $this->getRequest()->getPost('route_details'); 
				$routeName = $this->getRequest()->getPost('route_name'); 

				$check_user_route = $this->administrationService->crossCheckUserRoute($menuData);
				if($check_user_route){
					$message = 'Failure';
					$this->flashMessenger()->addMessage("You have already assigned route for this menu. Please try for other!");
				}else{
					try {
						 $this->administrationService->saveRoutes($moduleModel, $broadCategory, $menuData, $routesDetails);
						 $this->auditTrailService->saveAuditTrail("INSERT", "User Routes", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage('User Route was successfully added');
						 return $this->redirect()->toRoute('adduserroutes');
					 }
					 catch(\Exception $e) {
					 	$message = 'Failure';
				 		$this->flashMessenger()->addMessage($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				}
             }
         }
		 
        return array(
			'form' => $form,
			'routes' => $routes,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
			//'menuList' => $menuList,
		);
    }
	
	public function listUserRoutesAction()
	{
		$this->loginDetails();
		$form = new UserRoutesForm();
		$routesModel = new UserRoutes();
		$form->bind($routesModel);
		
		//$administration = $this->administrationService->listAll($tableName='administration_announcements');
		$routes = array();
        
		$request = $this->getRequest();
        if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->administrationService->saveBudgetProposal($routesModel);
					 $this->redirect()->toRoute('listadministration');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'routes' => $routes);
	}
	
	public function editUserRoutesAction()
	{
		$this->loginDetails();
		//get the id of the leave
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
        	$editUserRoute = $this->administrationService->findUserRouteDetails($id);

			$form = new UserRoutesForm($this->serviceLocator);
			$routesModel = new UserRoutes();
			$form->bind($routesModel);
			
			$routes = $this->administrationService->listUserRoutes($tableName='user_routes');
			$message = NULL;
			//$routes = array();
	        
			$request = $this->getRequest();
	        if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) {
	             	// $routesData = $this->params()->fromPost();
					$broadCategory = $this->getRequest()->getPost('route_category');
					$menuData = $this->getRequest()->getPost('user_sub_menu_id');
					$routesDetails = $this->getRequest()->getPost('route_details'); 
	                 try {
						 $this->administrationService->saveRoutes($routesModel, $broadCategory, $menuData, $routesDetails);
						 $this->flashMessenger()->addMessage('User Route was successfully edited');
						 return $this->redirect()->toRoute('adduserroutes');
					 }
					 catch(\Exception $e) {
					 		$message = 'Failure';
					 		$this->flashMessenger()->addMessage($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
	             }
	         }
			 
	        return array(
				'form' => $form,
				'routes' => $routes,
				'editUserRoute' => $editUserRoute,
				'message' => $message,
			);
        }else{
        	return $this->redirect()->toRoute('adduserroutes');
        }
	}
	
	public function addUserWorkFlowAction()
	{
		$this->loginDetails();
		$form = new UserWorkFlowForm($this->serviceLocator);
		$workflowModel = new UserWorkFlow();
		$form->bind($workflowModel);

		$workFlowType = $this->administrationService->selectWorkFlowType($tableName = 'user_workflow_type' , $columnName = 'workflow_type');
		
		$workflow = $this->administrationService->listAllWorkFlow($tableName = 'user_workflow', $this->organisation_id);

		$message = NULL;
        
		$request = $this->getRequest();
        if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
             	$userUnit = $this->getRequest()->getPost('role_department');
             	$userRole = $this->getRequest()->getPost('role');
             	$userDept = $this->getRequest()->getPost('user_department');
             	$authType = $this->getRequest()->getPost('type');
             	$authRole = $this->getRequest()->getPost('auth');
             	$authDept = $this->getRequest()->getPost('department');

             	$check_workflow = $this->administrationService->crossCheckUserWorkflow($userRole, $userDept, $userUnit, $authType, $authRole, $authDept);

             	if($check_workflow){
             		$message = 'Failure';
             		$this->flashMessenger()->addMessage("You can't assigned since you have already assigned ".$authRole." to ".$userRole." for type ".$authType);
             	}else{
             		try {
						 $this->administrationService->saveUserWorkFlow($workflowModel, $userUnit);
						 $this->auditTrailService->saveAuditTrail("INSERT", "User Workflow", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage('User Work Flow was successfully added');
						 return $this->redirect()->toRoute('adduserworkflow');
					 }

					 catch(\Exception $e) {
				 			$message = 'Failure';
					 		$this->flashMessenger()->addMessage($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
             	}
             }
         }
		 
        return array(
			'form' => $form,
			'workflow' => $workflow,
			'workFlowType' => $workFlowType,
			'message' => $message,
			'keyphrase' => $this->keyphrase,
			'organisation_id' => $this->organisation_id,
		);
	}
	
	public function editUserWorkFlowAction()
	{
		$this->loginDetails();
		//get the id of the leave
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
        	$editUserWorkFlow = $this->administrationService->findUserWorkFlowDetails($id);

			//$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');

			$form = new UserWorkFlowForm($this->serviceLocator);
			$workflowModel = new UserWorkFlow();
			$form->bind($workflowModel);

			$workFlowType = $this->administrationService->selectWorkFlowType($tableName = 'user_workflow_type' , $columnName = 'workflow_type');
			
			$workflow = $this->administrationService->listAllWorkFlow($tableName = 'user_workflow', $this->organisation_id);

			$message = NULL;
	        
			$request = $this->getRequest();
	        if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) {
	             	$userUnit = $this->getRequest()->getPost('role_department');
	                 try {
						 $this->administrationService->saveUserWorkFlow($workflowModel, $userUnit);
						 $this->auditTrailService->saveAuditTrail("EDIT", "User Workflow", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage('User Work Flow was successfully edited');
						 return $this->redirect()->toRoute('adduserworkflow');
					 }
					 catch(\Exception $e) {
					 		$message = 'Failure';
					 		$this->flashMessenger()->addMessage($e->getMessage());
							 die();
							 // Some DB Error happened, log it and let the user know
					 }
	             }
	         }
			 
	        return array(
				'form' => $form,
				'workFlowType' => $workFlowType,
				'workflow' => $workflow,
				'editUserWorkFlow' => $editUserWorkFlow,
				'message' => $message,
				'organisation_id' => $this->organisation_id,
			);
        }else{
        	return $this->redirect()->toRoute('adduserworkflow');
        }
	}
	
	public function viewWorkFlow()
	{
		$this->loginDetails();
		$form = new UserWorkFlowForm();
		$workflowModel = new UserWorkFlow();
		$form->bind($workflowModel);
		
		//$administration = $this->administrationService->listAll($tableName='administration_announcements');
		$workflow = array();
        
		$request = $this->getRequest();
        if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->administrationService->saveBudgetProposal($workflowModel);
					 $this->redirect()->toRoute('listadministration');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'workflow' => $workflow);
	}
	
	//to change passwords
	public function changeUserPasswordAction()
	{
		$this->loginDetails();
		$form = new PasswordSearchForm();
		
		$employeeList = array();
        
		$request = $this->getRequest();
        if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 $data['user_type'] = $this->getRequest()->getPost('user_type');
				 $data['name'] = $this->getRequest()->getPost('name');
				 $data['user_id'] = $this->getRequest()->getPost('user_id');
				 $data['organisation_id'] = $this->organisation_id;
                 try {
					 $employeeList = $this->administrationService->getSearchList($data);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'keyphrase' => $this->keyphrase,
			'employeeList' => $employeeList,
		);
	}
	
	
	public function changePasswordAction()
	{
		$this->loginDetails();
		//get the employee id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        $table_name = NULL; 
			
		if($this->employee_details_id != NULL){
			if($id == 0)
				$id = $this->employee_details_id;
			    $table_name = 'employee_details';
		}
		else if($this->student_details_id != NULL){
			if($id == 0)
				$id = $this->student_details_id;
			    $table_name = 'student';
		}
		
		else if($this->job_applicant_id != NULL){
			if($id == 0)
				$id = $this->job_applicant_id;
			    $table_name = 'job_applicant';
		}

		else if($this->alumni_details_id != NULL){
			if($id == 0)
				$id = $this->alumni_details_id;
			    $table_name = 'alumni';
		}
        
        if(is_numeric($id)){
        	//$id = (int) $this->params()->fromRoute('id', 0);				
			
			$form = new PasswordForm();
			$passwordModel = new Password();
			$form->bind($passwordModel);
			$message = NULL;
			
			$userDetail = $this->administrationService->getPasswordChangerDetails($table_name, $id);
	        
			$request = $this->getRequest();
	        if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) { 
	             	$data = $this->params()->fromPost(); 
	                $old_pwd = $data['changepassword']['old_password'];
	                $oldPassword = md5($old_pwd);
	             	$userType = $this->getRequest()->getPost('user_type');
					 $password_1 = $data['changepassword']['password'];
					 $password_2 = $data['changepassword']['repeat_password'];
					 if($password_1 === $password_2){
					 	$check_old_password = $this->administrationService->crossCheckOldPassword($id, $this->usertype);
					 	if($check_old_password === $oldPassword){
						 		try {
								 $this->administrationService->changePassword($passwordModel, $table_name);
								 $this->notificationService->saveNotification('Change Password', $id, 'NULL', 'Users Password');
								 $this->auditTrailService->saveAuditTrail("EDIT", "Users", "password", "SUCCESS");
								 $this->flashMessenger()->addMessage('Password was successfully changed');
								 return $this->redirect()->toRoute('auth', array('action' => 'logout'));
							 }
							 catch(\Exception $e) {
							 	$message = 'Failure';
							 	$this->flashMessenger()->addMessage($e->getMessage());
								// Some DB Error happened, log it and let the user know
							 }
					 	}else{
					 		$message = 'Failure';
					 		$this->flashMessenger()->addMessage('Old password Do Not Match. Please enter correct old password');
					 	}
					 } else {
						 $message = 'Failure';
						 $this->flashMessenger()->addMessage('New Passwords Do Not Match');
					 }
						 
	             }
	         }
			 
	        return array(
				'form' => $form,
				'id' => $id,
				'userDetail' => $userDetail,
				'message' => $message
			);
        }else{
        	return $this->redirect()->toRoute('changeuserpassword');
        }
	}


	public function updateUserPasswordAction()
	{
		$this->loginDetails();
		//get the employee id
        $id_from_route = $this->params()->fromRoute('id');
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        $type_id_from_route = $this->params()->fromRoute('user_type_id');
        $userTypeId = $this->my_decrypt($type_id_from_route, $this->keyphrase);

        $table_name = NULL;
			
		if($userTypeId == 1){
			$id = $id;
		    $table_name = 'employee_details';
		}
		else {
			$id = $id;
		    $table_name = 'student';
		}
        
        if(is_numeric($id)){
        	//$id = (int) $this->params()->fromRoute('id', 0);				
			
			$form = new UserPasswordForm();
			$passwordModel = new Password();
			$form->bind($passwordModel);
			$message = NULL;
			
			$userDetail = $this->administrationService->getPasswordChangerDetails($table_name, $id);
	        
			$request = $this->getRequest();
	        if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) { 
	             	$data = $this->params()->fromPost();
	             	$userType = $this->getRequest()->getPost('user_type');
	             	$sign_in = $data['changeuserpassword']['sign_in'];
					 $password_1 = $data['changeuserpassword']['password'];
					 $password_2 = $data['changeuserpassword']['repeat_password'];
					 if($password_1 === $password_2){
						 		try {
								 $this->administrationService->changeUserPassword($passwordModel, $table_name, $sign_in);
								 $this->notificationService->saveNotification('Change Password', $id, 'NULL', 'Users Password');
								 $this->auditTrailService->saveAuditTrail("EDIT", "Users", "password", "SUCCESS");
								 $this->flashMessenger()->addMessage('Password was successfully changed');
								 return $this->redirect()->toRoute('changeuserpassword');
							 }
							 catch(\Exception $e) {
							 	$message = 'Failure';
							 	$this->flashMessenger()->addMessage($e->getMessage());
								return $this->redirect()->toRoute('changeuserpassword');
								// Some DB Error happened, log it and let the user know
							 }
					 } else {
						 $message = 'Failure';
						 $this->flashMessenger()->addMessage('New Passwords Do Not Match');
					 }
						 
	             }
	         }
			 
	        return array(
				'form' => $form,
				'id' => $id,
				'userTypeId' => $userTypeId,
				'userDetail' => $userDetail,
				'message' => $message
			);
        }else{
        	return $this->redirect()->toRoute('changeuserpassword');
        }
	}

	public function extractFormData($routeList)
	{
        $routeData = array();

      	foreach ($routeList as $key => $value) {
      		foreach($value as $key2 => $value2){
				if($this->getRequest()->getPost('route_'.$key.'_'.$key2) == $key2){
					$routeData[$key][$key2]= $this->getRequest()->getPost('route_'.$key.'_'.$key2);
				}
			}
      	}
        return $routeData;
	}


	public function my_decrypt($data, $key) 
	{
		// Remove the base64 encoding from our key
		$encryption_key = base64_decode($key);
		
		$len = strlen($data);
        if ($len % 2) {
			return "ERROR";
        } else {
			// To decrypt, split the encrypted data from our IV - our unique separator used was "::"
			list($encrypted_data, $iv) = explode('::', base64_decode(hex2bin($data)), 2);
			return openssl_decrypt($encrypted_data, 'BF-CFB', $encryption_key, 0, $iv);
		}
	}	
	
}

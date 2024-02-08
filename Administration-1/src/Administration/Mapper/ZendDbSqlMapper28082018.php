<?php

namespace Administration\Mapper;

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
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Predicate;
use Zend\Db\Sql\Expression;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements AdministrationMapperInterface
{
	/**
	* @var \Zend\Db\Adapter\AdapterInterface
	*
	*/
	
	protected $dbAdapter;
	
	/*
	 * @var \Zend\Stdlib\Hydrator\HydratorInterface
	*/
	protected $hydrator;
	
	/*
	 * @var \Administration\Model\AdministrationInterface
	*/
	protected $administrationPrototype;
		
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			Administration $administrationPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->administrationPrototype = $administrationPrototype;
	}

	/**
	* @param int/String $id
	* @return array Employee Details
	* @throws \InvalidArgumentException
	*/
	
	public function findEmpDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select('employee_details');
		$select->where(array('emp_id = ? ' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	 * Get the Organisation Id
	 */
	 
	public function getOrganisationId($username)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'));
		$select->where(array('emp_id' =>$username));
		$select->columns(array('organisation_id'));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	 
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'));
		$select->where(array('emp_id' =>$username));
		$select->columns(array('id'));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/**
	* @return array/Administration()
	*/
	
	public function findAll($tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName)); 

		if($tableName == 'user_menu'){
			$select->from(array('t1' => $tableName))
				   ->where(array('t1.user_menu_id is NULL'));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

	}


	public function listUserRoutes($tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName))
			   ->join(array('t2' => 'user_menu'),
						't2.id = t1.route_category', array('menu_name'));
		//$select->join(array('t3' => 'user_routes'),
		//				't2.id = t3.route_category', array('route_category'));
				   //->where(array('t1.user_menu_id is NULL'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	/**
	* @return array/Administration()
	*/
	
	public function listRole($tableName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName)); 
		$select->where(array('t1.organisation_id = ?' => $organisation_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

	}


	/**
	* @return array/Administration()
	*/
	public function listAllRole($tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName))
			   ->join(array('t2' => 'organisation'),
					't2.id = t1.organisation_id', array('organisation_name'))
			   ->order('t1.organisation_id ASC'); 

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function listAllUser($tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName))
			   ->join(array('t2' => 'organisation'),
					't2.id = t1.region', array('organisation_name'))
			   ->order('t1.region ASC'); 

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function listUsers($tableName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName))
			   ->where(array('t1.region = ?' => $organisation_id))
			   ->order('t1.username ASC'); 

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


    public function listAllSubMenu($tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
			
		$select->join(array('t2' => 'user_menu'),
			        't2.id = t1.user_menu_id', array('parent_menu'=>'menu_name'))
				    ->where(array('t1.user_menu_id is NOT NULL'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}



	public function findUserRoleDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'user_role')) // base table
        	   ->join(array('t2' => 'organisation'),
        			't2.id = t1.organisation_id', array('organisation_name'))
                ->where(array('t1.id = ?' => $id)); 

        $stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
			return $this->hydrator->hydrate($result->current(), $this->administrationPrototype);
		}

		throw new \InvalidArgumentException("User Role with given ID: ($id) not found");
	}


	public function findUserDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'users')) // base table
        	   ->join(array('t2' => 'organisation'),
        			't2.id = t1.region', array('organisation_name'))
                ->where(array('t1.id = ?' => $id)); 

        $stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
			return $this->hydrator->hydrate($result->current(), $this->administrationPrototype);
		}

		throw new \InvalidArgumentException("User with given ID: ($id) not found");
	}

	public function findLevelZeroModuleDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'user_module')) // base table
                ->where(array('t1.id = ?' => $id)); 

        $stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
			return $this->hydrator->hydrate($result->current(), $this->administrationPrototype);
		}

		throw new \InvalidArgumentException("User Module with given ID: ($id) not found");
	}


	public function findModuleDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'user_menu')) // base table
                ->where(array('t1.id = ?' => $id)); 

        $stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
			return $this->hydrator->hydrate($result->current(), $this->administrationPrototype);
		}

		throw new \InvalidArgumentException("User Module with given ID: ($id) not found");
	}


	public function findSubMenuDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'user_menu')); // base table
        if('t1.user_menu_id' != NULL){
        	$select->join(array('t2' => 'user_menu'),
        				't2.id = t1.user_menu_id', array('parent_menu'=> 'menu_name'));
        }
            $select->where(array('t1.id = ?' => $id)); 

        $stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
			return $this->hydrator->hydrate($result->current(), $this->administrationPrototype);
		}

		throw new \InvalidArgumentException("User Menu with given ID: ($id) not found");
	}


	public function findLevelOneModuleDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'user_level_one_module')) // base table
                ->where(array('t1.id = ?' => $id)); 

        $stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
			return $this->hydrator->hydrate($result->current(), $this->administrationPrototype);
		}

		throw new \InvalidArgumentException("Module level one with given ID: ($id) not found");
	}


	public function findLevelTwoModuleDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'user_level_two_module')) // base table
                ->where(array('t1.id = ?' => $id)); 

        $stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
			return $this->hydrator->hydrate($result->current(), $this->administrationPrototype);
		}

		throw new \InvalidArgumentException("Module level two with given ID: ($id) not found");
	}


	public function findLevelThreeModuleDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'user_level_three_module')) // base table
                ->where(array('t1.id = ?' => $id)); 

        $stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
			return $this->hydrator->hydrate($result->current(), $this->administrationPrototype);
		}

		throw new \InvalidArgumentException("Module level three with given ID: ($id) not found");
	}


	/*public function findUserRouteDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'user_routes')); // base table
        if('t1.user_level_two_module_id' == NULL && 't1.user_level_three_module_id' == NULL){
        	$select->join(array('t2' => 'user_level_one_module'),
        			't2.id = t1.user_level_one_module_id', array('submodule_name'));
        	}
        else if('t1.user_level_one_module_id' == NULL && 't1.user_level_three_module_id' == NULL){
        	$select->join(array('t2' => 'user_level_two_module'),
        			't2.id = t1.user_level_two_module_id', array('submodule_name'));
        	}
        else if('t1.user_level_one_module_id' == NULL && 't1.user_level_two_module_id' == NULL){
        	$select->join(array('t2' => 'user_level_three_module'),
        			't2.id = t1.user_level_three_module_id', array('submodule_name'));
        	}
        $select->where(array('t1.id = ?' => $id)); 

        $stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
			return $this->hydrator->hydrate($result->current(), $this->administrationPrototype);
		}

		throw new \InvalidArgumentException("User Route with given ID: ($id) not found");
	}*/


	public function findUserRouteDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'user_routes')) // base table
        	   ->join(array('t2' => 'user_menu'),
        			't2.id = t1.user_sub_menu_id', array('user_menu_level'));
        $select->where(array('t1.id = ?' => $id)); 

        $stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
			return $this->hydrator->hydrate($result->current(), $this->administrationPrototype);
		}

		throw new \InvalidArgumentException("User Route with given ID: ($id) not found");
	}


	public function findUserWorkFlowDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();
       $select->from(array('t1' => 'user_workflow'));
		       /*->join(array('t2' => 'departments'),
					't1.role_department = t2.id', array('department_name'))
		       ->join(array('t3' => 'departments'),
		   			't1.department = t3.id', array('auth_department_name' => 'department_name'));*/
        $select->where(array('t1.id = ?' => $id)); 

        $stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
			return $this->hydrator->hydrate($result->current(), $this->administrationPrototype);
		}

		throw new \InvalidArgumentException("User Route with given ID: ($id) not found");
	}


	public function listAllWorkFlow($tableName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName))
		       ->join(array('t2' => 'department_units'),
					't1.role_department = t2.id', array('unit_name'))
		       ->join(array('t3' => 'departments'),
		   			't1.department = t3.id', array('department_name'))
			   ->where(array('t1.organisation = ?' => $organisation_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	/*
	* Save User Roles
	*/
	public function saveUserRole(UserRoles $moduleObject)
	{
		$moduleData = $this->hydrator->extract($moduleObject);
		unset($moduleData['id']);
		unset($moduleData['organisation_Name']);

		
		if($moduleObject->getId()) {
			//ID present, so it is an update
			$action = new Update('user_role');
			$action->set($moduleData);
			$action->where(array('id = ?' => $moduleObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('user_role');
			$action->values($moduleData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $moduleObject->setId($newId);
			}
			return $moduleObject;
		}
		
		throw new \Exception("Database Error");
	}


	public function saveUser(User $moduleObject)
	{
		$moduleData = $this->hydrator->extract($moduleObject);
		unset($moduleData['id']);
		unset($moduleData['organisation_Name']);

		$password = $moduleData['password'];
		$moduleData['password'] = md5($password);
		
		if($moduleObject->getId()) {
			//ID present, so it is an update
			$action = new Update('users');
			$action->set($moduleData);
			$action->where(array('id = ?' => $moduleObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('users');
			$action->values($moduleData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $moduleObject->setId($newId);
			}
			return $moduleObject;
		}
		
		throw new \Exception("Database Error");
	}


	/*
	* Save Level One Menu
	*/
	
	public function saveMenu(UserModule $moduleObject)
	{
		$moduleData = $this->hydrator->extract($moduleObject);
		unset($moduleData['id']);
		
		if($moduleObject->getId()) {
			//ID present, so it is an update
			$action = new Update('user_module');
			$action->set($moduleData);
			$action->where(array('id = ?' => $moduleObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('user_module');
			$action->values($moduleData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $moduleObject->setId($newId);
			}
			return $moduleObject;
		}
		
		throw new \Exception("Database Error");
	}


	public function saveModule(UserMainMenu $moduleObject)
	{
		$moduleData = $this->hydrator->extract($moduleObject);
		unset($moduleData['id']);
		
		if($moduleObject->getId()) {
			//ID present, so it is an update
			$action = new Update('user_menu');
			$action->set($moduleData);
			$action->where(array('id = ?' => $moduleObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('user_menu');
			$action->values($moduleData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $moduleObject->setId($newId);
			}
			return $moduleObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Save Sub Menus
	*/
	
	public function saveSubMenu(UserSubModule $moduleObject, $level)
	{
		$moduleData = $this->hydrator->extract($moduleObject);
		unset($moduleData['id']);
		
		if($level == 'One'){
			$tableName = 'user_level_one_module';
			unset($moduleData['user_Level_One_Module_Id']);
			unset($moduleData['user_Level_Two_Module_Id']);
		} else if($level == 'Two'){
			$tableName = 'user_level_two_module';
			unset($moduleData['user_Module_Id']);
			unset($moduleData['user_Level_Two_Module_Id']);
		} else {
			$tableName = 'user_level_three_module';
			unset($moduleData['user_Module_Id']);
			unset($moduleData['user_Level_One_Module_Id']);
		}
		
		if($moduleObject->getId()) {
			//ID present, so it is an update
			$action = new Update($tableName);
			$action->set($moduleData);
			$action->where(array('id = ?' => $moduleObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert($tableName);
			$action->values($moduleData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $moduleObject->setId($newId);
			}
			return $moduleObject;
		}
		
		throw new \Exception("Database Error");
	}


	public function saveUserSubMenu(UserSubMenu $moduleObject, $userMenu)
	{
		$moduleData = $this->hydrator->extract($moduleObject);
		unset($moduleData['id']);
		unset($moduleData['parent_Menu']);

		preg_match("/(\d+)/", $userMenu, $menu_id);
		foreach ($menu_id as $key => $value) {
        	$menu_id_to_insert = $value;
        }

        $moduleData['user_Menu_Id'] = $menu_id_to_insert;
		
		if($moduleObject->getId()) {
			//ID present, so it is an update
			$action = new Update('user_menu');
			$action->set($moduleData);
			$action->where(array('id = ?' => $moduleObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('user_menu');
			$action->values($moduleData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $moduleObject->setId($newId);
			}
			return $moduleObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Save the Routes
	*/
	
	public function saveRoutes(UserRoutes $moduleObject, $broadCategory, $menuData, $routesDetails)
	{
		$moduleData = $this->hydrator->extract($moduleObject);
		unset($moduleData['id']);
		unset($moduleData['user_Menu_Level']);


		preg_match("/(\d+)/", $menuData, $menu_id);
		foreach ($menu_id as $key => $value) {
        	$menu_id_to_insert = $value;
        }

		
		//$moduleData['route_Category'] = $this->getAjaxRouteCategory($tableName = 'user_menu', $broadCategory);


		$moduleData['user_Sub_Menu_Id'] = $menu_id_to_insert;

		//var_dump($moduleData);
		//die();

		if($moduleObject->getId()) {
			//ID present, so it is an update
			$action = new Update('user_routes');
			$action->set($moduleData);
			$action->where(array('id = ?' => $moduleObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('user_routes');
			$action->values($moduleData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $moduleObject->setId($newId);
			}
			return $moduleObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Write the user routes into database
	*/

	public function saveUserRoutes(RouteConfiguration $moduleObject, $data)
	{
		//to store the top level menu
		$user_top_level_menu = array();
		
		$moduleData = $this->hydrator->extract($moduleObject);
		unset($moduleData['id']);
		
		$user_role_id = $moduleData['user_Role_Id'];
		$userRouteList = $this->getUserRouteList($user_role_id);
		$new_routes = array();
		$route_to_be_removed = array();
		
				
		foreach ($data as $key => $value) {
			$user_top_level_menu[] = $key;
			foreach($value as $key2 => $value2){
				//check if there are any new routes and then add to user_role_routes table
				$routes[] = $value2;
				$new_routes = array_diff($routes, $userRouteList);
				$route_to_be_removed = array_diff($userRouteList, $routes);
			}
			
		}
		
		//Add new routes
		if($new_routes != NULL){
			foreach($new_routes as $key => $value){
				$moduleData['user_Routes_Id'] = $value;

				$action = new Insert('user_role_routes');
				$action->values($moduleData);
				
				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
			}
		}
		
		//Remove Routes if priveleges have been revoked
		if($route_to_be_removed != NULL){
			foreach($route_to_be_removed as $key => $value){
				$action = new Delete('user_role_routes');
				$action->where(array('user_role_id = ?' => $user_role_id));
				$action->where(array('user_routes_id = ?' => $value));
				
				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
			}
		}
		
		
		$userRoleId = $moduleData['user_Role_Id'];

		$this->writeUserAcl($userRoleId, $user_top_level_menu); 
		
		return;
	}

	// To write user acl
	public function writeUserAcl($userRoleId, $user_top_level_menu)
	{
		//to store the user routes
		$routeData = array();
		
		//route configuration array to store top level and other menu data for a user
		$route_configuration_0 = array();
		$route_configuration_1 = array();
		$route_configuration_2 = array();
		$route_configuration_3 = array();
		$route_configuration = array();

		$routeList = $this->getUserRoleRoutes($userRoleId);
		$userOrganisation = $this->getUserOrganisation($userRoleId);

		$role = $this->getUserRoleName($userRoleId);

		$i = 0;

		foreach ($routeList as $key => $value) {
			$routeData[$i++] = $value['user_routes_id'];
		}
		
		//configure the top level menu first
		if($user_top_level_menu != NULL){
			$menu_level_0 = $this->configureTopLevelNavigation($user_top_level_menu);
			foreach($menu_level_0 as $details){
				$route_configuration_0[$details['menu_name']] = $details;
			}
		}

		if($routeData != NULL)
		{
			//for loop for the 3 levels of menu
			for($menu_level_id = 1; $menu_level_id <=3; $menu_level_id++){
				$user_menu = $this->configureNavigation($routeData, $menu_level_id);
				foreach ($user_menu as $value) {
					//store in route configuration array
					if($menu_level_id == 1)
						$route_configuration_1[$value['top_level_menu_name']][$value['menu_name']] = $value['route_details'];
					else if($menu_level_id == 2)
						$route_configuration_2[$value['top_level_menu_name']][$value['level_two_menu_name']][$value['menu_name']] = $value['route_details'];
					else if($menu_level_id == 3)
						$route_configuration_3[$value['top_level_menu_name']][$value['level_two_menu_name']][$value['level_three_menu_name']][$value['menu_name']] = $value['route_details'];
				}
			}
		}

		//Send the menus to write Side Navigation
		$side_navigation = $this->writeSideNavigationContents($route_configuration_0, $route_configuration_1, $route_configuration_2, $route_configuration_3);
		//Call function to write ACL file
		$this->writeACLFile($userOrganisation, $role, $side_navigation);
		
		return;
	}
	
	/*
	* TO configure Top Level Navigation such as HOME, ADMIN etc
	*/
	
	public function configureTopLevelNavigation($user_top_level_menu)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();
		        
        $select->from(array('t1' => 'user_menu'))
               ->columns(array('id','menu_name','menu_icon'))
			   ->where(array('t1.id ' => $user_top_level_menu))
			   ->order('menu_weight ASC');
                          
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}
	
	/*
	* To Configure Level 1, 2 and 3 menus
	*/
	
	public function configureNavigation($routeData, $menu_level_id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();
		
		if($menu_level_id == 1){
			$select->from(array('t1' => 'user_routes'))
			   ->columns(array('route_category','route_details'))
               ->join(array('t3' => 'user_menu'),
                    't3.id = t1.user_sub_menu_id', array('menu_weight', 'user_menu_id', 'user_menu_level','menu_name'))
				->join(array('t2' => 'user_menu'),
                    't3.user_menu_id = t2.id', array('top_level_menu_name'=>'menu_name'))
               ->where(array('t1.id ' => $routeData))
			   ->where(array('t3.user_menu_level = ?' => $menu_level_id))
			   ->order('t3.user_menu_id ASC, t3.menu_weight ASC');
		} else if($menu_level_id == 2){
			$select->from(array('t1' => 'user_routes'))
			   ->columns(array('route_category','route_details'))
               ->join(array('t3' => 'user_menu'),
                    't3.id = t1.user_sub_menu_id', array('menu_weight', 'user_menu_id', 'user_menu_level','menu_name'))
				->join(array('t2' => 'user_menu'),
                    't3.user_menu_id = t2.id', array('level_two_menu_name'=>'menu_name'))
				->join(array('t4' => 'user_menu'),
                    't2.user_menu_id = t4.id', array('top_level_menu_name'=>'menu_name'))
               ->where(array('t1.id ' => $routeData))
			   ->where(array('t3.user_menu_level = ?' => $menu_level_id))
			   ->order('t4.user_menu_id ASC, t4.menu_weight ASC, t3.user_menu_id ASC, t3.menu_weight ASC');
		} else if($menu_level_id == 3){
			$select->from(array('t1' => 'user_routes'))
			   ->columns(array('route_category','route_details'))
               ->join(array('t3' => 'user_menu'),
                    't3.id = t1.user_sub_menu_id', array('menu_weight', 'user_menu_id', 'user_menu_level','menu_name'))
				->join(array('t2' => 'user_menu'),
                    't3.user_menu_id = t2.id', array('level_three_menu_name'=>'menu_name'))
				->join(array('t4' => 'user_menu'),
                    't2.user_menu_id = t4.id', array('level_two_menu_name'=>'menu_name'))
				->join(array('t5' => 'user_menu'),
                    't4.user_menu_id = t5.id', array('top_level_menu_name'=>'menu_name'))
               ->where(array('t1.id ' => $routeData))
			   ->where(array('t3.user_menu_level = ?' => $menu_level_id))
			   ->order('t3.user_menu_id ASC, t3.menu_weight ASC');
		}
        
                          
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}
	
	/*
	* Receives array of routes and writes them onto a file
	* Returns the contents of the file
	*/
	
	public function writeSideNavigationContents($menu_level_0, $menu_level_1, $menu_level_2, $menu_level_3)
	{
		$side_navigation = NULL;
		$side_navigation .= '<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">'.PHP_EOL;
		$side_navigation .= '<div class="menu_section">'.PHP_EOL;
		$side_navigation .= '<br><br><br>'.PHP_EOL;
		$side_navigation .= '<ul class="nav side-menu">'.PHP_EOL;
		
		foreach($menu_level_0 as $key => $value){
			$side_navigation .= "\t".'<li><a><i class="'.$value['menu_icon'].'"></i>'.$value['menu_name'].'<span class="fa fa-chevron-down"></span></a>'.PHP_EOL;
			if(array_key_exists($key, $menu_level_1)){
				$side_navigation .= "\t\t".'<ul class="nav child_menu">'.PHP_EOL;
				foreach($menu_level_1[$key] as $key1 => $value1){
					$side_navigation .= "\t\t\t".'<li><a href="<?php echo $this->url(\''.$value1.'\') ?>">'.$key1.'</a></li>'.PHP_EOL;
				}
				$side_navigation .= "\t\t".'</ul>'.PHP_EOL;
			}
			if(array_key_exists($key, $menu_level_2)){
				$side_navigation .= "\t\t".'<ul class="nav child_menu">'.PHP_EOL;
				foreach($menu_level_2[$key] as $key1 => $value1){
					$side_navigation .= "\t\t\t".'<li><a><i class=""></i>'.$key1.'<span class="fa fa-chevron-down"></span></a>'.PHP_EOL;
					$side_navigation .= "\t\t\t".'<ul class="nav child_menu">'.PHP_EOL;
					foreach($value1 as $key2 => $value2){
						$side_navigation .= "\t\t\t\t".'<li><a href="<?php echo $this->url(\''.$value2.'\') ?>">'.$key2.'</a></li>'.PHP_EOL;
					}
					if(array_key_exists($key1, $menu_level_3[$key])){
						foreach($menu_level_3[$key][$key1] as $key2 => $value2){
							$side_navigation .= "\t\t\t\t\t".'<li><a><i class=""></i>'.$key2.'<span class="fa fa-chevron-down"></span></a>'.PHP_EOL;
							$side_navigation .= "\t\t\t\t\t".'<ul class="nav child_menu">'.PHP_EOL;
							foreach($value2 as $key3 => $value3){
								$side_navigation .= "\t\t\t\t\t\t".'<li><a href="<?php echo $this->url(\''.$value3.'\') ?>">'.$key3.'</a></li>'.PHP_EOL;
							}
							$side_navigation .= "\t\t\t\t\t".'</ul>'.PHP_EOL;
							$side_navigation .= "\t\t\t\t\t".'</li>'.PHP_EOL;
						}
					}
					$side_navigation .= "\t\t\t".'</ul>'.PHP_EOL;
					$side_navigation .= "\t\t\t".'</li>'.PHP_EOL;
				}
				$side_navigation .= "\t\t".'</ul>'.PHP_EOL;
			}
			$side_navigation .= "\t".'</li>'.PHP_EOL;
			
		}
		$side_navigation .= '</ul>'.PHP_EOL;
		$side_navigation .= '</div>'.PHP_EOL;
		$side_navigation .= '</div>'.PHP_EOL;
		return $side_navigation;
	}


	/*
	* To create directory if not exist, if exist then open and look for php file, if exis then it will open and write. If not create php file, open and write the inside php file.
	*/
	
	public function writeACLFile($userOrganisation, $role, $side_navigation)
	{
		$dirName = getcwd();

		if(!is_dir($dirName.'/module/Application/view/layout/navigation/'.$userOrganisation))
		{
			echo "File does not exist and we are creating";
			mkdir($dirName.'/module/Application/view/layout/navigation/'.$userOrganisation, 0775, true);

			// To open the create directory
			$file_to_write = $role.'_side_navigation'.'.'.'phtml';
			$content_to_write = $side_navigation;

			$file = fopen($dirName.'/module/Application/view/layout/navigation/'.$userOrganisation. '/' . $file_to_write,"w+");
			fwrite($file, $content_to_write);

			// closes the file
			fclose($file);
		}
		else
		{
			// To open the create directory
			$file_to_write = $role.'_side_navigation'.'.'.'phtml';
			$content_to_write = $side_navigation;

			if(!file_exists($dirName.'/module/Application/view/layout/navigation/'.$userOrganisation. '/' . $file_to_write))
			{
				$file = fopen($dirName.'/module/Application/view/layout/navigation/'.$userOrganisation. '/' . $file_to_write,"w+");
				fwrite($file, $content_to_write);

				// closes the file
				fclose($file);
			}
			else
			{
				$content_to_write = $side_navigation;
				$file = fopen($dirName.'/module/Application/view/layout/navigation/'.$userOrganisation. '/' . $file_to_write,"w+");
				fwrite($file, $content_to_write);

				// closes the file
				fclose($file);
			}
		}

		// Call function to allow access to routes
		//$this->moduleAclRoutes($role);
		
		return;
	}


	public function moduleAclRoutes($role)
	{
		$dirName = getcwd();
		$somecontentmodule = NULL;
		$somecontentmodule .= "<?php return array('";
     	$somecontentmodule .= $role;
     	$somecontentmodule .= "'=>array(
			'auth',
			'application',
			'home',
			'login',
			'success' ),";

			if(file_exists($dirName.'/module/Application/config/module.acl.roles.php')){
				$file = fopen($dirName.'/module/Application/config/module.acl.roles.php', "w");
				fwrite($file, $somecontentmodule);

				//Close the fie
				fclose($file);
			} else {
				echo "Can't open the file. Please do it carefully";
			}
		return;
	}


	public function saveUserWorkFlow(UserWorkFlow $moduleObject, $userUnit)
	{
		$moduleData = $this->hydrator->extract($moduleObject);
		unset($moduleData['id']);

		preg_match("/(\d+)/", $userUnit, $unit_id);
		foreach ($unit_id as $key => $value) {
        	$unit_id_to_insert = $value;
        }

        $moduleData['role_Department'] = $unit_id_to_insert;
		
		if($moduleObject->getId()) {
			//ID present, so it is an update
			$action = new Update('user_workflow');
			$action->set($moduleData);
			$action->where(array('id = ?' => $moduleObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('user_workflow');
			$action->values($moduleData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $moduleObject->setId($newId);
			}
			return $moduleObject;
		}
		
		throw new \Exception("Database Error");
	}


	// To get the routes configured to particular role
	public function getUserRoleRoutes($userRoleId)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'user_role_routes'));
		$select->columns(array('id', 'user_routes_id'));
		$select->where(array('t1.user_role_id = ?' => $userRoleId));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	//To get user role name from role_id
	public function getUserRoleName($userRoleId)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'user_role'));
		$select->columns(array('rolename')); 
		$select->where(array('t1.id = ?' => $userRoleId));
		

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
        $id = NULL;
        
        foreach($resultSet as $set)
        {
           $id = $set['rolename'];
        }
        return $id;
	}


	// Function to get the user region/ organisation
	public function getUserOrganisation($userRoleId)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'user_role'));
		$select->columns(array('organisation_id'));
		$select->where(array('t1.id = ?' => $userRoleId));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
        $id = NULL;
        
        foreach($resultSet as $set)
        {
           $id = $set['organisation_id'];
        }
        return $id;
	}
	
	/*
	* Get the list of the Routes for the check boxes when configuring user routes
	* returns 'route_details' => 'route_name'
	*/
	
	public function getRoutes()
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'user_routes'));
		$select->columns(array('route_name','route_details')); 

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
				
		$routes = array();
		foreach($resultSet as $set){
			$routes[$set['route_details']] = $set['route_name'];
		}
		return $routes;
	}

	
	/*
	* Return an id 
	* this is done as the ajax returns a value and not the id
	*/
	
	public function getAjaxDataId($tableName, $ajaxName)
	{
		//note: $department_name takes various parameters. Check calling function 
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'user_level_one_module'){
		$select->from(array('t1' => $tableName))
				->columns(array('id'));
		$select->where(array('t1.submodule_name = ?' => $ajaxName));
	}
	if($tableName == 'user_level_two_module'){
		$select->from(array('t1' => $tableName))
				->columns(array('id'));
		$select->where(array('t1.submodule_name = ?' => $ajaxName));
	}
	if($tableName == 'user_level_three_module'){
		$select->from(array('t1' => $tableName))
				->columns(array('id'));
		$select->where(array('t1.submodule_name = ?' => $ajaxName));
	}
		//$select->where->like('t1.submodule_name','%'.$ajaxName.'%');
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$id = NULL;
		
		foreach($resultSet as $set)
		{
			$id = $set['id'];
		}
		return $id;
	}


	public function getAjaxRouteCategory($tableName, $ajaxName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array('id', 'menu_name'));
			//$select->where->like('id = ?' => $code);
		$select->where(array('t1.id = ?' => $ajaxName));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$id = NULL;
		
		foreach($resultSet as $set)
		{
			$id = $set['menu_name'];
		}
		return $id;
	}
	
	
	/**
	* @return array/Administration()
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'organisation')
		{
			$select->from(array('t1' => $tableName));
			$select->columns(array('id',$columnName)); 
		}
		if($tableName == 'user_module')
		{
			$select->from(array('t1' => $tableName));
			$select->columns(array('id',$columnName));
		}
		if($tableName == 'user_level_one_module')
		{
			$select->from(array('t1' => $tableName));
			$select->columns(array('id',$columnName));
		}
		if($tableName == 'user_level_two_module')
		{
			$select->from(array('t1' => $tableName));
			$select->columns(array('id',$columnName));
		}
		if($tableName == 'user_routes')
		{
			$select->from(array('t1' => $tableName));
			$select->columns(array('id',$columnName, 'user_sub_menu_id'));
			$select->join(array('t2' => 'user_menu'),
						't2.id = t1.route_category', array('menu_name'));
		}
		if($tableName == 'user_role')
		{
			$select->from(array('t1' => $tableName));
			$select->columns(array('id',$columnName));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['id']] = $set[$columnName];
		}
		return $selectData;
			
	}

	public function selectWorkFlowType($tableName, $columnName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array('id',$columnName)); 
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['workflow_type']] = $set[$columnName];
		}
		return $selectData;
	}


	public function getAllBroadCategory($tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName))
			   ->where(array('t1.user_menu_id is NULL'))
			   ->order('id ASC');

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

		if ($result instanceof ResultInterface && $result->isQueryResult()) {

				$resultSet = new ResultSet();
				$resultSet->initialize($result);

				$resultSet = new HydratingResultSet($this->hydrator, $this->studentPrototype);
				return $resultSet->initialize($result); 
		}

		return array();
	}


	public function listSelectRouteData($tableName, $columnName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($organisation_id == '1')
		{	
			$select->from(array('t1' => $tableName));
			$select->columns(array('id',$columnName, 'user_sub_menu_id', 'route_category'));
			$select->join(array('t2' => 'user_menu'),
						't2.id = t1.route_category', array('menu'=>'menu_name', 'menu_id'=>'id'));
				   //->group('t1.route_category');
		}
		else {
			$select->from(array('t1' => $tableName));
			$select->columns(array('id',$columnName, 'user_sub_menu_id', 'route_category'));
			$select->join(array('t2' => 'user_menu'),
						't2.id = t1.route_category', array('menu'=>'menu_name', 'menu_id'=>'id'))
				   ->where->notLIKE('t1.route_details', "addmodule")
				   ->where->notLIKE('t1.route_details', "adduserroutes")
				   ->where->notLIKE('t1.route_details', "addsubmenu");
		    //$select->group('t1.route_category');
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
				
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['route_category']][$set['id']] = $set[$columnName];
		}
		return $selectData;
	}

	public function listOrgUserRoles($tableName, $columnName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array('id',$columnName))
			   ->where(array('t1.organisation_id = ?' => $organisation_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['id']] = $set[$columnName];
		}
		return $selectData;
	}
	
	public function getUserRouteList($user_role_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'user_role_routes'));
		$select->columns(array('user_routes_id'))
			   ->where(array('t1.user_role_id = ?' => $user_role_id));; 

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$user_routes = array();
		foreach($resultSet as $set)
		{
			$user_routes[] = $set['user_routes_id'];
		}
		return $user_routes;
	}

	public function selectAllStaff($tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array('id', 'emp_id', 'first_name', 'middle_name', 'last_name'))
			   ->order('t1.organisation_id ASC'); 

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['emp_id']] = $set['first_name'].' '.$set['middle_name'].' '.$set['last_name'].' ('.$set['emp_id'].')';
		}
		return $selectData;
	}


	public function selectOrgStaff($tableName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array('id', 'emp_id', 'first_name', 'middle_name', 'last_name'))
			   ->where(array('t1.organisation_id = ?' => $organisation_id)); 

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['emp_id']] = $set['first_name'].' '.$set['middle_name'].' '.$set['last_name'].' ('.$set['emp_id'].')';
		}
		return $selectData;
	}

	public function listAdminData($tableName, $columnName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array('id', $columnName));
		$select->where(array('t1.route_category' => 'Administration'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['id']] = $set[$columnName];
		}
		return $selectData;
			
	}


	public function listHrData($tableName, $columnName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array('id', $columnName));
		$select->where(array('t1.route_category' => 'Human Resources'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['id']] = $set[$columnName];
		}
		return $selectData;
	}

	public function listPMSData($tableName, $columnName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array('id', $columnName));
		$select->where(array('t1.route_category' => 'PMS'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['id']] = $set[$columnName];
		}
		return $selectData;
	}

	public function listAcademicData($tableName, $columnName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array('id', $columnName));
		$select->where(array('t1.route_category' => 'Academic'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['id']] = $set[$columnName];
		}
		return $selectData;
	}


	public function listStudentData($tableName, $columnName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array('id', $columnName));
		$select->where(array('t1.route_category' => 'Student'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['id']] = $set[$columnName];
		}
		return $selectData;
	}


	public function listPlanningData($tableName, $columnName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array('id', $columnName));
		$select->where(array('t1.route_category' => 'Planning'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['id']] = $set[$columnName];
		}
		return $selectData;
	}


	public function listBudgetingData($tableName, $columnName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array('id', $columnName));
		$select->where(array('t1.route_category' => 'Budgeting'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['id']] = $set[$columnName];
		}
		return $selectData;
	}

	public function listInventoryData($tableName, $columnName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array('id', $columnName));
		$select->where(array('t1.route_category' => 'Property and Inventory'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['id']] = $set[$columnName];
		}
		return $selectData;
	}


	public function listFinanceData($tableName, $columnName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array('id', $columnName));
		$select->where(array('t1.route_category' => 'Finance'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['id']] = $set[$columnName];
		}
		return $selectData;
	}


	public function listAlumniData($tableName, $columnName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array('id', $columnName));
		$select->where(array('t1.route_category' => 'Alumni'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['id']] = $set[$columnName];
		}
		return $selectData;
	}


	public function listUserData($tableName, $columnName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array('id', $columnName));
		$select->where(array('t1.route_category' => 'User'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['id']] = $set[$columnName];
		}
		return $selectData;
	}


	public function selectAllUserRole($tableName, $columnName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array($columnName));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set[$columnName]] = $set[$columnName];
		}
		return $selectData;
	}


	public function selectUserRole($tableName, $columnName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array($columnName))
			   ->where(array('t1.organisation_id = ?' => $organisation_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set[$columnName]] = $set[$columnName];
		}
		return $selectData;
	}
	
	/*
	* Get the student id (i.e. this->employee_details_id is NULL)
	*/
	
	public function getStudentDetails($username)
	{
		$student_id;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student'));
		$select->where(array('student_id' =>$username));
		$select->columns(array('id'));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$student_id = $et['id'];
		}
		
		return $student_id;
	}
	
	/*
	* Get the List of users, student/employee for whose password to change
	*/
	
	public function getSearchList($data)
	{
		$name = $data['name'];
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		if($data['user_type'] == 'Student'){
			$select->from(array('t1' => 'student'));
			$select->columns(array('id','first_name','middle_name','last_name','student_id'));
			if($data['user_id'] !=NULL)
				$select->where(array('t1.student_id = ?' => $data['user_id']));
			if($data['name'] =! NULL){
				$split_names = explode(" ", $name);
				$first_name = $split_names[0];
				if(array_key_exists(2, $split_names)){
					$middle_name = $split_names[1];
					$last_name = $split_names[2];
					$select->where(array('t1.first_name = ?' => $first_name));
					$select->where(array('t1.middle_name = ?' => $middle_name));
					$select->where(array('t1.last_name = ?' => $last_name));
				} else {
					$middle_name = NULL;
					if(array_key_exists(1, $split_names)){
						$last_name = $split_names[1];
						$select->where(array('t1.last_name = ?' => $last_name));
					}
				}
			}
			
		} else {
			$select->from(array('t1' => 'employee_details'));
			$select->columns(array('id','first_name','middle_name','last_name','emp_id'));
			if($data['user_id'] !=NULL)
				$select->where(array('t1.emp_id = ?' => $data['user_id']));
			if($data['name'] =! NULL){
				$split_names = explode(" ", $name);
				$first_name = $split_names[0];
				if(array_key_exists(2, $split_names)){
					$middle_name = $split_names[1];
					$last_name = $split_names[2];
					$select->where(array('t1.first_name = ?' => $first_name));
					$select->where(array('t1.middle_name = ?' => $middle_name));
					$select->where(array('t1.last_name = ?' => $last_name));
				} else {
					$middle_name = NULL;
					if(array_key_exists(1, $split_names)){
						$last_name = $split_names[1];
						$select->where(array('t1.last_name = ?' => $last_name));
					}
				}
			}
		}
		$select->where(array('t1.first_name = ?' => $first_name));
		if($data['organisation_id'] != 1)
			$select->where(array('t1.organisation_id = ?' => $data['organisation_id']));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*.
	* This is to get the details of the user
	* It can be either Student or Employee
	*/
	
	public function getPasswordChangerDetails($table_name, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		if($table_name == 'student'){
			$select->from(array('t1' => 'student'));
			$select->columns(array('id','first_name','middle_name','last_name','student_id'));
			
		} else {
			$select->from(array('t1' => 'employee_details'));
			$select->columns(array('id','first_name','middle_name','last_name','emp_id'));
		}
		
		$select->where(array('t1.id = ?' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Function to change the password
	*/
	
	public function changePassword(Password $passwordObject, $table_name)
	{
		$passwordData = $this->hydrator->extract($passwordObject);
		$username = $this->getUserIdentity($passwordData['id'], $table_name);
		unset($passwordData['id']);
		unset($passwordData['repeat_Password']);
		
		$passwordData['password'] = md5($passwordData['password']);
				
		$action = new Update('users');
		$action->set($passwordData);
		$action->where(array('username = ?' => $username));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $passwordObject->setId($newId);
			}
			return $passwordObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Get the username based on id
	*/
	
	public function getUserIdentity($id, $table_name)
	{
		$user_name = NULL;
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		if($table_name == 'student'){
			$select->from(array('t1' => 'student'));
			$select->columns(array('student_id'));
			$select->where(array('t1.id = ?' => $id));

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			
			foreach($resultSet as $set){
				$user_name = $set['emp_id'];
			}
			
		} else {
			$select->from(array('t1' => 'employee_details'));
			$select->columns(array('emp_id'));
			$select->where(array('t1.id = ?' => $id));

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			
			foreach($resultSet as $set){
				$user_name = $set['emp_id'];
			}
		}
		
		return $user_name;
	}
        
}
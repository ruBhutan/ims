<?php

namespace Administration\Model;

class Administration
{
	protected $id;
	protected $username;
	protected $password;
	protected $role;
	protected $region;

	// User Role
	protected $rolename;
	protected $organisation_id;
	protected $organisation_name;

	//Level Zero Module
	protected $module_name;
	protected $module_description;
	protected $menu_icon;
	protected $menu_weight;

	//leve one module
	protected $submodule_name;
	protected $submodule_description;
	protected $user_module_id;

	//Level two module
	protected $user_level_one_module_id;

	//Level three module
	protected $user_level_two_module_id;

	//User route
	protected $route_category;
	protected $route_name;
	protected $route_details;
	protected $route_remarks;
	protected $user_level_three_module_id;

	//Route Configuration
	protected $user_role_id;
	protected $user_routes_id;

	//Sub menu
	protected $submenu_name;
	protected $submenu_description;
	protected $user_menu_id;
	protected $user_menu_level;

	//Menu
	protected $menu_name;
	protected $menu_description;

	protected $parent_menu;

	//User work flow
	protected $role_department;
	protected $type;
	protected $auth;
	protected $department;
	protected $details;

	
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getUsername()
	{
		return $this->username;
	}
	
	public function setUsername($username)
	{
		$this->username = $username;
	}
	
	public function getPassword()
	{
		return $this->password;
	}
	
	public function setPassword($password)
	{
		$this->password = $password;
	}
	
	public function getRole()
	{
		return $this->role;
	}
	
	public function setRole($role)
	{
		$this->role = $role;
	}
	
	public function getRegion()
	{
		return $this->region;
	}
	
	public function setRegion($region)
	{
		$this->region = $region;
	}

	// User Role
	public function getRolename()
	{
		return $this->rolename;
	}
	
	public function setRolename($rolename)
	{
		$this->rolename = $rolename;
	}

	public function getOrganisation_Id()
	{
		return $this->organisation_id;
	}
	
	public function setOrganisation_Id($organisation_id)
	{
		$this->organisation_id = $organisation_id;
	}

	public function getOrganisation_Name()
	{
		return $this->organisation_name;
	}
	
	public function setOrganisation_Name($organisation_name)
	{
		$this->organisation_name = $organisation_name;
	}

	//Level zero module
	public function getModule_Name()
	{
		return $this->module_name;
	}
	
	public function setModule_Name($module_name)
	{
		$this->module_name = $module_name;
	}

	public function getModule_Description()
	{
		return $this->module_description;
	}
	
	public function setModule_Description($module_description)
	{
		$this->module_description = $module_description;
	}

	public function getMenu_Icon()
	{
		return $this->menu_icon;
	}
	
	public function setMenu_Icon($menu_icon)
	{
		$this->menu_icon = $menu_icon;
	}

	public function getMenu_Weight()
	{
		return $this->menu_weight;
	}
	
	public function setMenu_Weight($menu_weight)
	{
		$this->menu_weight = $menu_weight;
	}

	public function getSubmodule_Name()
	{
		return $this->submodule_name;
	}
	
	public function setSubmodule_Name($submodule_name)
	{
		$this->submodule_name = $submodule_name;
	}

	public function getSubmodule_Description()
	{
		return $this->submodule_description;
	}
	
	public function setSubmodule_Description($submodule_description)
	{
		$this->submodule_description = $submodule_description;
	}

	public function getUser_Module_Id()
	{
		return $this->user_module_id;
	}
	
	public function setUser_Module_Id($user_module_id)
	{
		$this->user_module_id = $user_module_id;
	}


	//Level two module
	public function getUser_Level_One_Module_Id()
	{
		return $this->user_level_one_module_id;
	}
	
	public function setUser_Level_One_Module_Id($user_level_one_module_id)
	{
		$this->user_level_one_module_id = $user_level_one_module_id;
	}

	//Level three module
	public function getUser_Level_Two_Module_Id()
	{
		return $this->user_level_two_module_id;
	}
	
	public function setUser_Level_Two_Module_Id($user_level_two_module_id)
	{
		$this->user_level_two_module_id = $user_level_two_module_id;
	}

	//User Routes
	public function getRoute_Category()
	{
		return $this->route_category;
	}
	
	public function setRoute_Category($route_category)
	{
		$this->route_category = $route_category;
	}

	public function getRoute_Name()
	{
		return $this->route_name;
	}
	
	public function setRoute_Name($route_name)
	{
		$this->route_name = $route_name;
	}

	public function getRoute_Details()
	{
		return $this->route_details;
	}
	
	public function setRoute_Details($route_details)
	{
		$this->route_details = $route_details;
	}

	public function getRoute_Remarks()
	{
		return $this->route_remarks;
	}
	
	public function setRoute_Remarks($route_remarks)
	{
		$this->route_remarks = $route_remarks;
	}


	public function getUser_Level_Three_Module_Id()
	{
		return $this->user_level_three_module_id;
	}
	
	public function setUser_Level_Three_Module_Id($user_level_three_module_id)
	{
		$this->user_level_three_module_id = $user_level_three_module_id;
	}

	//Route Configuration
	public function getUser_Role_Id()
	{
		return $this->user_role_id;
	}
	
	public function setUser_Role_Id($user_role_id)
	{
		$this->user_role_id = $user_role_id;
	}
	
	public function getUser_Routes_Id()
	{
		return $this->user_routes_id;
	}
	
	public function setUser_Routes_Id($user_routes_id)
	{
		$this->user_routes_id = $user_routes_id;
	}


	public function getSubmenu_Name()
	{
		return $this->submenu_name;
	}
	
	public function setSubmenu_Name($submenu_name)
	{
		$this->submenu_name = $submenu_name;
	}


	public function getSubmenu_Description()
	{
		return $this->submenu_description;
	}
	
	public function setSubmenu_Description($submenu_description)
	{
		$this->submenu_description = $submenu_description;
	}

	public function getUser_Menu_Id()
	{
		return $this->user_menu_id;
	}
	
	public function setUser_Menu_Id($user_menu_id)
	{
		$this->user_menu_id = $user_menu_id;
	}

	public function getUser_Menu_Level()
	{
		return $this->user_menu_level;
	}
	
	public function setUser_Menu_Level($user_menu_level)
	{
		$this->user_menu_level = $user_menu_level;
	}


	public function getMenu_Name()
	{
		return $this->menu_name;
	}
	
	public function setMenu_Name($menu_name)
	{
		$this->menu_name = $menu_name;
	}
	
	public function getMenu_Description()
	{
		return $this->menu_description;
	}
	
	public function setMenu_Description($menu_description)
	{
		$this->menu_description = $menu_description;
	}


	public function getParent_Menu()
	{
		return $this->parent_menu;
	}
	
	public function setParent_Menu($parent_menu)
	{
		$this->parent_menu = $parent_menu;
	}

// User work flow
	public function getRole_Department()
	{
		return $this->role_department;
	}
	
	public function setRole_Department($role_department)
	{
		$this->role_department = $role_department;
	}

	public function getType()
	{
		return $this->type;
	}
	
	public function setType($type)
	{
		$this->type = $type;
	}

	public function getAuth()
	{
		return $this->auth;
	}
	
	public function setAuth($auth)
	{
		$this->auth = $auth;
	}

	public function getDepartment()
	{
		return $this->department;
	}
	
	public function setDepartment($department)
	{
		$this->department = $department;
	}

	public function getDetails()
	{
		return $this->details;
	}
	
	public function setDetails($details)
	{
		$this->details = $details;
	}
}
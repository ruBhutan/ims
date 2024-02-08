<?php

namespace Administration\Model;

class UserSubModule
{
	protected $id;
	protected $submodule_name;
	protected $submodule_description;
	protected $menu_weight;
	protected $user_module_id;
	protected $user_level_one_module_id;
	protected $user_level_two_module_id;
	protected $user_menu_level_id;
	
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
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
	
	public function getMenu_Weight()
	{
		return $this->menu_weight;
	}
	
	public function setMenu_Weight($menu_weight)
	{
		$this->menu_weight = $menu_weight;
	}
	
	public function getUser_Module_Id()
	{
		return $this->user_module_id;
	}
	
	public function setUser_Module_Id($user_module_id)
	{
		$this->user_module_id = $user_module_id;
	}
	
	public function getUser_Level_One_Module_Id()
	{
		return $this->user_level_one_module_id;
	}
	
	public function setUser_Level_One_Module_Id($user_level_one_module_id)
	{
		$this->user_level_one_module_id = $user_level_one_module_id;
	}
	
	public function getUser_Level_Two_Module_Id()
	{
		return $this->user_level_two_module_id;
	}
	
	public function setUser_Level_Two_Module_Id($user_level_two_module_id)
	{
		$this->user_level_two_module_id = $user_level_two_module_id;
	}

	public function getUser_Menu_Level_Id()
	{
		return $this->user_menu_level_id;
	}
	
	public function setUser_Menu_Level_Id($user_menu_level_id)
	{
		$this->user_menu_level_id = $user_menu_level_id;
	}
}
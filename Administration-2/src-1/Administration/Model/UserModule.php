<?php

namespace Administration\Model;

class UserModule
{
	protected $id;
	protected $module_name;
	protected $module_description;
	protected $menu_weight;
	protected $menu_icon;
	
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
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
	
	public function getMenu_Weight()
	{
		return $this->menu_weight;
	}
	
	public function setMenu_Weight($menu_weight)
	{
		$this->menu_weight = $menu_weight;
	}
	
	public function getMenu_Icon()
	{
		return $this->menu_icon;
	}
	
	public function setMenu_Icon($menu_icon)
	{
		$this->menu_icon = $menu_icon;
	}
}
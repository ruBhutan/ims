<?php

namespace Administration\Model;

class UserSubMenu
{
	protected $id;
	protected $menu_name;
	protected $menu_description;
	protected $menu_weight;
	protected $user_menu_id;
	protected $user_menu_level;
	
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
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
	
	public function getMenu_Weight()
	{
		return $this->menu_weight;
	}
	
	public function setMenu_Weight($menu_weight)
	{
		$this->menu_weight = $menu_weight;
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
}
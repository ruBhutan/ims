<?php

namespace Administration\Model;

class UserRoutes
{
	protected $id;
	protected $route_category;
	protected $route_name;
	protected $route_details;
	protected $route_remarks;
	protected $user_sub_menu_id;
	protected $user_menu_level;
	
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
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

	public function getUser_Sub_Menu_Id()
	{
		return $this->user_sub_menu_id;
	}
	
	public function setUser_Sub_Menu_Id($user_sub_menu_id)
	{
		$this->user_sub_menu_id = $user_sub_menu_id;
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
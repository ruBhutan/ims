<?php

namespace Administration\Model;

class UserACL
{
	protected $id;
	protected $user_routes_id;
	protected $user_level_one_module_id;
	protected $user_level_two_module_id;
	protected $user_level_three_module_id;
	
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getUser_Routes_Id()
	{
		return $this->user_routes_id;
	}
	
	public function setUser_Routes_Id($user_routes_id)
	{
		$this->user_routes_id = $user_routes_id;
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
	
	public function getUser_Level_Three_Module_Id()
	{
		return $this->user_level_three_module_id;
	}
	
	public function setUser_Level_Three_Module_Id($user_level_three_module_id)
	{
		$this->user_level_three_module_id = $user_level_three_module_id;
	}
}
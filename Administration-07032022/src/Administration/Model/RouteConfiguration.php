<?php

namespace Administration\Model;

class RouteConfiguration
{
	protected $id;
	protected $user_role_id;
	protected $user_routes_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
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
}
<?php

namespace Administration\Model;

class UserWorkFlow
{
	protected $id;
	protected $role;
	protected $role_department;
	protected $type;
	protected $auth;
	protected $department;
	protected $organisation;
	protected $details;
	
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getRole()
	{
		return $this->role;
	}
	
	public function setRole($role)
	{
		$this->role = $role;
	}
	
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
	
	public function getOrganisation()
	{
		return $this->organisation;
	}
	
	public function setOrganisation($organisation)
	{
		$this->organisation = $organisation;
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
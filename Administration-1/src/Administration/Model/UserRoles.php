<?php

namespace Administration\Model;

class UserRoles
{
	protected $id;
	protected $rolename;
	protected $organisation_id;
	protected $organisation_name;
	
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
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
}
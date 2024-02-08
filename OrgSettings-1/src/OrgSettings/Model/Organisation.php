<?php

namespace OrgSettings\Model;

class Organisation
{
	protected $id;
	protected $organisation_name;
	protected $address;
	protected $department_name;
	protected $organisation_id;
	protected $unit_name;
	protected $departments_id;
	 	 
	public function getId()
	{
		 return $this->id;
	}
	 
	public function setId($id)
	{
		 $this->id = $id;
	}
	 
	public function getOrganisation_Name()
	{
		return $this->organisation_name;
	}
	
	public function setOrganisation_Name($organisation_name)
	{
		$this->organisation_name = $organisation_name;
	}
	
	public function getAddress()
	{
		return $this->address;
	}
	
	public function setAddress($address)
	{
		$this->address = $address;
	}
	
	public function getDepartment_Name()
	{
		return $this->department_name;
	}
	
	public function setDepartment_Name($department_name)
	{
		$this->department_name = $department_name;
	}
	
	public function getOrganisation_Id()
	{
		return $this->organisation_id;
	}
	
	public function setOrganisation_Id($organisation_id)
	{
		$this->organisation_id = $organisation_id;
	}
	
	public function getUnit_Name()
	{
		return $this->unit_name;
	}
	
	public function setUnit_Name($unit_name)
	{
		$this->unit_name = $unit_name;
	}
	
	public function getDepartments_Id()
	{
		return $this->departments_id;
	}
	
	public function setDepartments_Id($departments_id)
	{
		$this->departments_id = $departments_id;
	}
}
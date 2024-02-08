<?php

namespace Responsibilities\Model;

class ResponsibilityCategory
{
	protected $id;
	protected $responsibility_name;
	protected $responsibility_description;
	protected $organisation_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getResponsibility_Name()
	{
		return $this->responsibility_name;
	}
	
	public function setResponsibility_Name($responsibility_name)
	{
		$this->responsibility_name = $responsibility_name;
	}
	
	public function getResponsibility_Description()
	{
		return $this->responsibility_description;
	}
	
	public function setResponsibility_Description($responsibility_description)
	{
		$this->responsibility_description = $responsibility_description;
	}
	
	public function getOrganisation_Id()
	{
		return $this->organisation_id;
	}
	
	public function setOrganisation_Id($organisation_id)
	{
		$this->organisation_id = $organisation_id;
	}
	
}
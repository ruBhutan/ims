<?php

namespace ResearchPublication\Model;

class ResearchType
{
	protected $id;
	protected $grant_type;
	protected $grant_category;
	protected $remarks;
	protected $organisation_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getGrant_Type()
	{
		return $this->grant_type;
	}
	
	public function setGrant_Type($grant_type)
	{
		$this->grant_type = $grant_type;
	}
	
	public function getGrant_Category()
	{
		return $this->grant_category;
	}
	
	public function setGrant_Category($grant_category)
	{
		$this->grant_category = $grant_category;
	}
		
	public function getRemarks()
	{
		return $this->remarks;
	}
	
	public function setRemarks($remarks)
	{
		$this->remarks = $remarks;
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
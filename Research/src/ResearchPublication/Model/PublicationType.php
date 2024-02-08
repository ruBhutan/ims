<?php

namespace ResearchPublication\Model;

class PublicationType
{
	protected $id;
	protected $publication_name;
	protected $publication_type;
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
	
	public function getPublication_Name()
	{
		return $this->publication_name;
	}
	
	public function setPublication_Name($publication_name)
	{
		$this->publication_name = $publication_name;
	}
	 
	public function getPublication_Type()
	{
		return $this->publication_type;
	}
	
	public function setPublication_Type($publication_type)
	{
		$this->publication_type = $publication_type;
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
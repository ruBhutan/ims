<?php

namespace StudentContribution\Model;

class StudentContributionCategory
{
	protected $id;
	protected $contribution_type;
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
	
	public function getContribution_Type()
	{
		return $this->contribution_type;
	}
	
	public function setContribution_Type($contribution_type)
	{
		$this->contribution_type = $contribution_type;
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
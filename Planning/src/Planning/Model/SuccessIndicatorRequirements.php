<?php

namespace Planning\Model;

class SuccessIndicatorRequirements
{
	protected $id;
	protected $organisation_name;
	protected $requirement;
	protected $justification;
	protected $requirement_detail;
	protected $impact;
	protected $awpa_activities_id;
	
 	 
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
	
	public function getRequirement()
	{
		return $this->requirement;
	}
	
	public function setRequirement($requirement)
	{
		$this->requirement = $requirement;
	}
	
	public function getJustification()
	{
		return $this->justification;
	}
	
	public function setJustification($justification)
	{
		$this->justification = $justification;
	}
	
	public function getRequirement_Detail()
	{
		return $this->requirement_detail;
	}
	
	public function setRequirement_Detail($requirement_detail)
	{
		$this->requirement_detail = $requirement_detail;
	}
	
	public function getImpact()
	{
		return $this->impact;
	}
	
	public function setImpact($impact)
	{
		$this->impact = $impact;
	}
	 
	public function getAwpa_Activities_Id()
	{
		return $this->awpa_activities_id;
	}
	
	public function setAwpa_Activities_Id($awpa_activities_id)
	{
		$this->awpa_activities_id = $awpa_activities_id;
	}

}
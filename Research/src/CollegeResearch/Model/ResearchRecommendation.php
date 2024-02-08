<?php

namespace CollegeResearch\Model;

class ResearchRecommendation
{
	protected $id;
	protected $application_status;
	protected $remarks;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getApplication_Status()
	{
		return $this->application_status;
	}
	
	public function setApplication_Status($application_status)
	{
		$this->application_status = $application_status;
	}
	
	public function getRemarks()
	{
		return $this->remarks;
	}
	
	public function setRemarks($remarks)
	{
		$this->remarks = $remarks;
	}
		
}
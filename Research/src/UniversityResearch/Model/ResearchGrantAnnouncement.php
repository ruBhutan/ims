<?php

namespace UniversityResearch\Model;

class ResearchGrantAnnouncement
{
	protected $id;
	protected $research_grant_type;
	protected $start_date;
	protected $end_date;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getResearch_Grant_Type()
	{
		return $this->research_grant_type;
	}
	
	public function setResearch_Grant_Type($research_grant_type)
	{
		$this->research_grant_type = $research_grant_type;
	}
	
	public function getStart_Date()
	{
		return $this->start_date;
	}
	
	public function setStart_Date($start_date)
	{
		$this->start_date = $start_date;
	}
	
	public function getEnd_Date()
	{
		return $this->end_date;
	}
	
	public function setEnd_Date($end_date)
	{
		$this->end_date = $end_date;
	}
		
}
<?php

namespace ResearchPublication\Model;

class ResearchAnnouncement
{
	protected $id;
	protected $research_publication_type;
	protected $start_date;
	protected $end_date;
	protected $organisation_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getResearch_Publication_Type()
	{
		return $this->research_publication_type;
	}
	
	public function setResearch_Publication_Type($research_publication_type)
	{
		$this->research_publication_type = $research_publication_type;
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

	public function getOrganisation_Id()
	{
		return $this->organisation_id;
	}
	
	public function setOrganisation_Id($organisation_id)
	{
		$this->organisation_id = $organisation_id;
	}
		
}
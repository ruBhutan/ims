<?php

namespace ResearchPublication\Model;

class ResearchRecommendation
{
	protected $id;
	protected $publication_status;
	protected $remarks;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getPublication_Status()
	{
		return $this->publication_status;
	}
	
	public function setPublication_Status($publication_status)
	{
		$this->publication_status = $publication_status;
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
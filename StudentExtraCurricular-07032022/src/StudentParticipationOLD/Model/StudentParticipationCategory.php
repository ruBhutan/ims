<?php

namespace StudentParticipation\Model;

class StudentParticipationCategory
{
	protected $id;
	protected $participation_type;
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
		
	public function getParticipation_Type()
	{
		return $this->participation_type;
	}
	
	public function setParticipation_Type($participation_type)
	{
		$this->participation_type = $participation_type;
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
<?php

namespace AcademicCalendar\Model;

class AcademicEvent
{
	protected $id;
	protected $academic_event;
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
	
	public function getAcademic_Event()
	{
		return $this->academic_event;
	}
	
	public function setAcademic_Event($academic_event)
	{
		$this->academic_event = $academic_event;
	}
	
	public function getOrganisation_Id()
	{
		return $this->organisation_id;
	}
	
	public function setOrganisation_Id($organisation_id)
	{
		$this->organisation_id = $organisation_id;
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
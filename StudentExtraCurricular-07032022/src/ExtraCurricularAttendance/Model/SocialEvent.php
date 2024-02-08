<?php

namespace ExtraCurricularAttendance\Model;

class SocialEvent
{
	protected $id;
	protected $date;
	protected $event;
	protected $event_description;
	protected $academic_year;
	protected $organisation_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getDate()
	{
		return $this->date;
	}
	
	public function setDate($date)
	{
		$this->date = $date;
	}
	
	public function getEvent()
	{
		return $this->event;
	}
	
	public function setEvent($event)
	{
		$this->event = $event;
	}
	
	public function getEvent_Description()
	{
		return $this->event_description;
	}
	
	public function setEvent_Description($event_description)
	{
		$this->event_description = $event_description;
	}

	public function getAcademic_Year()
	{
		return $this->academic_year;
	}
	
	public function setAcademic_Year($academic_year)
	{
		$this->academic_year = $academic_year;
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
<?php

namespace ExtraCurricularAttendance\Model;

class ExtraCurricularAttendance
{
	protected $id;
	protected $date;
	protected $social_events_id;
	protected $event_description;
	protected $attendance;
	protected $student_id;
	
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
	
	public function getSocial_Events_Id()
	{
		return $this->social_events_id;
	}
	
	public function setSocial_Events_Id($social_events_id)
	{
		$this->social_events_id = $social_events_id;
	}
	
	public function getEvent_Description()
	{
		return $this->event_description;
	}
	
	public function setEvent_Description($event_description)
	{
		$this->event_description = $event_description;
	}
	 
	public function getAttendance()
	{
		return $this->attendance;
	}
	
	public function setAttendance($attendance)
	{
		$this->attendance = $attendance;
	}
	
	public function getStudent_Id()
	{
		return $this->student_id;
	}
	
	public function setStudent_Id($student_id)
	{
		$this->student_id = $student_id;
	}
}
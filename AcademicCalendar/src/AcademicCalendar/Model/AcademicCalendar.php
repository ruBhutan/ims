<?php

namespace AcademicCalendar\Model;

class AcademicCalendar
{
	protected $id;
	protected $academic_event;
	protected $academic_year;
	protected $from_date;
	protected $to_date;
	protected $event_for;
	protected $date_range;
	protected $employee_details_id;
	protected $remarks;
	 
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
	
	public function getAcademic_Year()
	{
		return $this->academic_year;
	}
	
	public function setAcademic_Year($academic_year)
	{
		$this->academic_year = $academic_year;
	}
	
	public function getFrom_Date()
	{
		return $this->from_date;
	}
	
	public function setFrom_Date($from_date)
	{
		$this->from_date = $from_date;
	}
	
	public function getTo_Date()
	{
		return $this->to_date;
	}
	
	public function setTo_Date($to_date)
	{
		$this->to_date = $to_date;
	}
	
	public function getEvent_For()
	{
		return $this->event_for;
	}
	
	public function setEvent_For($event_for)
	{
		$this->event_for = $event_for;
	}
	
	public function getDate_Range()
	{
		return $this->date_range;
	}
	
	public function setDate_Range($date_range)
	{
		$this->date_range = $date_range;
	}
	
	public function getEmployee_Details_Id()
	{
		return $this->employee_details_id;
	}
	
	public function setEmployee_Details_Id($employee_details_id)
	{
		$this->employee_details_id = $employee_details_id;
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
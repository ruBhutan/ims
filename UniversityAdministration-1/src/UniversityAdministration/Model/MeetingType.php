<?php

namespace UniversityAdministration\Model;

class MeetingType
{
	protected $id;
	protected $meeting;
	protected $meeting_abbr;
	protected $status;
	protected $employee_details_id;
	protected $organisation_id;
	
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 		
	public function getMeeting()
	{
		return $this->meeting;
	}
	
	public function setMeeting($meeting)
	{
		$this->meeting = $meeting;
	}

	public function getMeeting_Abbr()
	{
		return $this->meeting_abbr;
	}
	
	public function setMeeting_Abbr($meeting_abbr)
	{
		$this->meeting_abbr = $meeting_abbr;
	}

	public function getStatus()
	{
		return $this->status;
	}
	
	public function setStatus($status)
	{
		$this->status = $status;
	}

	public function getEmployee_Details_Id()
	{
		return $this->employee_details_id;
	}
	
	public function setEmployee_Details_Id($employee_details_id)
	{
		$this->employee_details_id = $employee_details_id;
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
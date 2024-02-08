<?php

namespace UniversityAdministration\Model;

class MeetingMinutes
{
	protected $id;
	protected $meeting_type_id;
	protected $meeting_date;
	protected $meeting_details;
	protected $recorded_by;
	protected $evidence_file;
	
	
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}

	public function getMeeting_Details()
	{
		return $this->meeting_details;
	}
	
	public function setMeeting_Details($meeting_details)
	{
		$this->meeting_details = $meeting_details;
	}

	public function getMeeting_Type_Id()
	{
		return $this->meeting_type_id;
	}
	
	public function setMeeting_Type_Id($meeting_type_id)
	{
		$this->meeting_type_id = $meeting_type_id;
	}

	public function getMeeting_date()
	{
		return $this->meeting_date;
	}
	
	public function setMeeting_date($meeting_date)
	{
		$this->meeting_date = $meeting_date;
	}

	public function getRecorded_By()
	{
		return $this->recorded_by;
	}
	
	public function setRecorded_By($recorded_by)
	{
		$this->recorded_by = $recorded_by;
	}

	public function getEvidence_file()
	{
		return $this->evidence_file;
	}
	
	public function setEvidence_file($evidence_file)
	{
		$this->evidence_file = $evidence_file;
	}
}
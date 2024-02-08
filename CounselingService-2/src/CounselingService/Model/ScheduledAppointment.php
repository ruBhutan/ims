<?php

namespace CounselingService\Model;

class ScheduledAppointment
{
	protected $id;
	protected $scheduled_time;
	protected $scheduled_date;
	protected $venue;
	protected $counselor_remarks;
	protected $counseling_type;
	protected $counseling_appointment_id;
	protected $applicant_id;
	protected $applicant_type;
	protected $scheduled_status;
	protected $counselor;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getScheduled_Time()
	{
		return $this->scheduled_time;
	}
	
	public function setScheduled_Time($scheduled_time)
	{
		$this->scheduled_time = $scheduled_time;
	}
	
	public function getScheduled_Date()
	{
		return $this->scheduled_date;
	}
	
	public function setScheduled_Date($scheduled_date)
	{
		$this->scheduled_date = $scheduled_date;
	}
	
	public function getVenue()
	{
		return $this->venue;
	}
	
	public function setVenue($venue)
	{
		$this->venue = $venue;
	}
	
	public function getCounselor_Remarks()
	{
		return $this->counselor_remarks;
	}
	
	public function setCounselor_Remarks($counselor_remarks)
	{
		$this->counselor_remarks = $counselor_remarks;
	}
		
	public function getCounseling_Type()
	{
		return $this->counseling_type;
	}
	
	public function setCounseling_Type($counseling_type)
	{
		$this->counseling_type = $counseling_type;
	}
	
	public function getCounseling_Appointment_Id()
	{
		return $this->counseling_appointment_id;
	}
	
	public function setCounseling_Appointment_Id($counseling_appointment_id)
	{
		$this->counseling_appointment_id = $counseling_appointment_id;
	}


	public function getApplicant_Id()
	{
		return $this->applicant_id;
	}
	
	public function setApplicant_Id($applicant_id)
	{
		$this->applicant_id = $applicant_id;
	}

	public function getApplicant_Type()
	{
		return $this->applicant_type;
	}
	
	public function setApplicant_Type($applicant_type)
	{
		$this->applicant_type = $applicant_type;
	}


	public function getScheduled_Status()
	{
		return $this->scheduled_status;
	}
	
	public function setScheduled_Status($scheduled_status)
	{
		$this->scheduled_status = $scheduled_status;
	}

	public function getCounselor()
	{
		return $this->counselor;
	}
	
	public function setCounselor($counselor)
	{
		$this->counselor = $counselor;
	}
}
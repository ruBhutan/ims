<?php

namespace CounselingService\Model;

class CounselingAppointment
{
	protected $id;
	protected $counselor_id;
	protected $subject;
	protected $description;
	protected $appointment_time;
	protected $appointment_date;
	protected $remarks;
	protected $appointment_status;
	protected $applicant_id;
	protected $applicant_type;
	protected $organisation_id;
	protected $consent_detail;
	protected $applied_date;
	protected $granted_date;

	// Recommended counseling
	protected $reason;
	protected $suggested_id;
	protected $suggested_by;
	protected $suggested_date;
	protected $suggested_status;
	protected $suggested_type;

	//Counseling Record
	protected $date;
	protected $notes;
	protected $documents;
	protected $counselor;
	protected $scheduled_counseling_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getCounselor_Id()
	{
		return $this->counselor_id;
	}
	
	public function setCounselor_Id($counselor_id)
	{
		$this->counselor_id = $counselor_id;
	}
	
	public function getSubject()
	{
		return $this->subject;
	}
	
	public function setSubject($subject)
	{
		$this->subject = $subject;
	}
	
	public function getDescription()
	{
		return $this->description;
	}
	
	public function setDescription($description)
	{
		$this->description = $description;
	}
	
	public function getAppointment_Time()
	{
		return $this->appointment_time;
	}
	
	public function setAppointment_Time($appointment_time)
	{
		$this->appointment_time = $appointment_time;
	}
	
	public function getAppointment_Date()
	{
		return $this->appointment_date;
	}
	
	public function setAppointment_Date($appointment_date)
	{
		$this->appointment_date = $appointment_date;
	}
		
	public function getRemarks()
	{
		return $this->remarks;
	}
	
	public function setRemarks($remarks)
	{
		$this->remarks = $remarks;
	}
	
	public function getAppointment_Status()
	{
		return $this->appointment_status;
	}
	
	public function setAppointment_Status($appointment_status)
	{
		$this->appointment_status = $appointment_status;
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
	
	public function getOrganisation_Id()
	{
		return $this->organisation_id;
	}
	
	public function setOrganisation_Id($organisation_id)
	{
		$this->organisation_id = $organisation_id;
	}

	public function getConsent_Detail()
	{
		return $this->consent_detail;
	}
	
	public function setConsent_Detail($consent_detail)
	{
		$this->consent_detail = $consent_detail;
	}

	public function getApplied_Date()
	{
		return $this->applied_date;
	}
	
	public function setApplied_Date($applied_date)
	{
		$this->applied_date = $applied_date;
	}

	public function getGranted_date()
	{
		return $this->granted_date;
	}
	
	public function setGranted_date($granted_date)
	{
		$this->granted_date = $granted_date;
	}



	public function getReason()
	{
		return $this->reason;
	}
	
	public function setReason($reason)
	{
		$this->reason = $reason;
	}


	public function getSuggested_By()
	{
		return $this->suggested_by;
	}
	
	public function setSuggested_By($suggested_by)
	{
		$this->suggested_by = $suggested_by;
	}
		
	public function getSuggested_Id()
	{
		return $this->suggested_id;
	}
	
	public function setSuggested_Id($suggested_id)
	{
		$this->suggested_id = $suggested_id;
	}

	public function getSuggested_Type()
	{
		return $this->suggested_type;
	}
	
	public function setSuggested_Type($suggested_type)
	{
		$this->suggested_type = $suggested_type;
	}

	public function getSuggested_Date()
	{
		return $this->suggested_date;
	}
	
	public function setSuggested_Date($suggested_date)
	{
		$this->suggested_date = $suggested_date;
	}

	public function getSuggested_Status()
	{
		return $this->suggested_status;
	}
	
	public function setSuggested_Status($suggested_status)
	{
		$this->suggested_status = $suggested_status;
	}

	public function getDate()
	{
		return $this->date;
	}
	
	public function setDate($date)
	{
		$this->date = $date;
	}

	public function getNotes()
	{
		return $this->notes;
	}
	
	public function setNotes($notes)
	{
		$this->notes = $notes;
	}

	public function getDocuments()
	{
		return $this->documents;
	}
	
	public function setDocuments($documents)
	{
		$this->documents = $documents;
	}

	public function getCounselor()
	{
		return $this->counselor;
	}
	
	public function setCounselor($counselor)
	{
		$this->counselor  = $counselor;
	}

	public function getScheduled_Counseling_Id()
	{
		return $this->scheduled_counseling_id;
	}
	
	public function setScheduled_Counseling_Id($scheduled_counseling_id)
	{
		$this->scheduled_counseling_id = $scheduled_counseling_id;
	}
}
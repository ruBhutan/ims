<?php

namespace CounselingService\Model;

class CounselingNotes
{
	protected $id;
	protected $counselor;
	protected $notes;
	protected $date;
	protected $applicant_id;
	protected $applicant_type;
	protected $scheduled_counseling_id;
	protected $documents;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getCounselor()
	{
		return $this->counselor;
	}
	
	public function setCounselor($counselor)
	{
		$this->counselor = $counselor;
	}
	
	public function getNotes()
	{
		return $this->notes;
	}
	
	public function setNotes($notes)
	{
		$this->notes = $notes;
	}
	
	public function getDate()
	{
		return $this->date;
	}
	
	public function setDate($date)
	{
		$this->date = $date;
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

	public function getScheduled_Counseling_Id()
	{
		return $this->scheduled_counseling_id;
	}
	
	public function setScheduled_Counseling_Id($scheduled_counseling_id)
	{
		$this->scheduled_counseling_id = $scheduled_counseling_id;
	}


	public function getDocuments()
	{
		return $this->documents;
	}
	
	public function setDocuments($documents)
	{
		$this->documents = $documents;
	}
}
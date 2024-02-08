<?php

namespace DocumentFiling\Model;

class FilingDocument
{
	protected $id;
	protected $meeting_type_id;
	protected $filing_date;
	protected $filing_details;
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

	public function getFiling_Details()
	{
		return $this->filing_details;
	}
	
	public function setFiling_Details($filing_details)
	{
		$this->filing_details = $filing_details;
	}

	public function getMeeting_Type_Id()
	{
		return $this->meeting_type_id;
	}
	
	public function setMeeting_Type_Id($meeting_type_id)
	{
		$this->meeting_type_id = $meeting_type_id;
	}

	public function getFiling_date()
	{
		return $this->filing_date;
	}
	
	public function setFiling_date($filing_date)
	{
		$this->filing_date = $filing_date;
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
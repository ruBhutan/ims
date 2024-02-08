<?php

namespace CounselingService\Model;

class CounselingSuggest
{
	protected $id;
	protected $subject;
	protected $reason;
	protected $suggested_by;
	protected $suggested_id;
	protected $suggested_type;
	protected $suggested_date;
	protected $counselor_id;
	protected $suggested_status;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getSubject()
	{
		return $this->subject;
	}
	
	public function setSubject($subject)
	{
		$this->subject = $subject;
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

	public function getCounselor_Id()
	{
		return $this->counselor_id;
	}
	
	public function setCounselor_Id($counselor_id)
	{
		$this->counselor_id = $counselor_id;
	}


	public function getSuggested_Status()
	{
		return $this->suggested_status;
	}
	
	public function setSuggested_Status($suggested_status)
	{
		$this->suggested_status = $suggested_status;
	}
}
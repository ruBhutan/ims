<?php

namespace ExternalExaminer\Model;

class ExternalExaminer
{
	protected $id;
	protected $examiner_name;
	protected $address;
	protected $contact_no;
	protected $email;
	protected $ab_approval;
	protected $from_date;
	protected $to_date;
	protected $remarks;
	protected $programmes_id;
	protected $organisation_id;
	protected $evidence_file;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getExaminer_Name()
	{
		return $this->examiner_name;
	}
	
	public function setExaminer_Name($examiner_name)
	{
		$this->examiner_name = $examiner_name;
	}
	
	public function getAddress()
	{
		return $this->address;
	}
	
	public function setAddress($address)
	{
		$this->address = $address;
	}
	
	public function getContact_No()
	{
		return $this->contact_no;
	}
	
	public function setContact_No($contact_no)
	{
		$this->contact_no = $contact_no;
	}
	
	public function getEmail()
	{
		return $this->email;
	}
	
	public function setEmail($email)
	{
		$this->email = $email;
	}
	
	public function getAb_Approval()
	{
		return $this->ab_approval;
	}
	
	public function setAb_Approval($ab_approval)
	{
		$this->ab_approval = $ab_approval;
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
	
	public function getRemarks()
	{
		return $this->remarks;
	}
	
	public function setRemarks($remarks)
	{
		$this->remarks = $remarks;
	}
	
	public function getProgrammes_Id()
	{
		return $this->programmes_id;
	}
	
	public function setProgrammes_Id($programmes_id)
	{
		$this->programmes_id = $programmes_id;
	}
	
	public function getOrganisation_Id()
	{
		return $this->organisation_id;
	}
	
	public function setOrganisation_Id($organisation_id)
	{
		$this->organisation_id = $organisation_id;
	}

	public function getEvidence_File()
	{
		return $this->evidence_file;
	}
	
	public function setEvidence_File($evidence_file)
	{
		$this->evidence_file = $evidence_file;
	}
		
}
<?php

namespace JobPortal\Model;

class Documents
{
	protected $id;
	protected $academic_transcripts;
	protected $security_clearance;
	protected $medical_certificate;
	protected $cid;
	protected $audit_clearance;
	protected $tax_clearance;
	protected $no_objection_certificate;
	protected $job_applicant_id;
	 	 
	public function getId()
	{
		 return $this->id;
	}
	 
	public function setId($id)
	{
		 $this->id = $id;
	}
	
	public function getAcademic_Transcripts()
	{
		return $this->academic_transcripts;
	}
	
	public function setAcademic_Transcripts($academic_transcripts)
	{
		$this->academic_transcripts = $academic_transcripts;
	}
	
	public function getSecurity_Clearance()
	{
		return $this->security_clearance;
	}
	
	public function setSecurity_Clearance($security_clearance)
	{
		$this->security_clearance = $security_clearance;
	}
	
	public function getMedical_Certificate()
	{
		return $this->medical_certificate;
	}
	
	public function setMedical_Certificate($medical_certificate)
	{
		$this->medical_certificate = $medical_certificate;
	}
	
	public function getCid()
	{
		return $this->cid;
	}
	
	public function setCid($cid)
	{
		$this->cid = $cid;
	}
	
	public function getAudit_Clearance()
	{
		return $this->audit_clearance;
	}
	
	public function setAudit_Clearance($audit_clearance)
	{
		$this->audit_clearance = $audit_clearance;
	}
	
	public function getTax_Clearance()
	{
		return $this->tax_clearance;
	}
	
	public function setTax_Clearance($tax_clearance)
	{
		$this->tax_clearance = $tax_clearance;
	}
	
	public function getNo_Objection_Certificate()
	{
		return $this->no_objection_certificate;
	}
	
	public function setNo_Objection_Certificate($no_objection_certificate)
	{
		$this->no_objection_certificate = $no_objection_certificate;
	}
	
	public function getJob_Applicant_Id()
	{
		return $this->job_applicant_id;
	}
	
	public function setJob_Applicant_Id($job_applicant_id)
	{
		$this->job_applicant_id = $job_applicant_id;
	}
}
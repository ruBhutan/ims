<?php

namespace EmpTraining\Model;

class StudyExtension
{
	protected $id;
	protected $study_status;
	protected $extension_date;
	protected $committee_approved_evidence;
	protected $remarks;
	protected $training_details_id;
        
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getStudy_Status()
	{
		return $this->study_status;
	}
	
	public function setStudy_Status($study_status)
	{
		$this->study_status = $study_status;
	}
	
	public function getExtension_Date()
	{
		return $this->extension_date;
	}
	
	public function setExtension_Date($extension_date)
	{
		$this->extension_date = $extension_date;
	}
	
	public function getCommittee_Approved_Evidence()
	{
		return $this->committee_approved_evidence;
	}
	
	public function setCommittee_Approved_Evidence($committee_approved_evidence)
	{
		$this->committee_approved_evidence = $committee_approved_evidence;
	}
	
	public function getRemarks()
	{
		return $this->remarks;
	}
	
	public function setRemarks($remarks)
	{
		$this->remarks = $remarks;
	}
	
	public function getTraining_Details_Id()
	{
		return $this->training_details_id;
	}
	
	public function setTraining_Details_Id($training_details_id)
	{
		$this->training_details_id = $training_details_id;
	}
	
}
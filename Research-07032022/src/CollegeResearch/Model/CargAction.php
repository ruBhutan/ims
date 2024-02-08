<?php

namespace CollegeResearch\Model;

class CargAction
{
	protected $id;
	protected $crc_approval_no;
	protected $crc_amount_granted;
	protected $signed_certification_researchers;
	protected $signed_certification_crc;
	protected $research_proposal;
	protected $application_step_status;
	protected $budgetplan;
	 	 
	 public function getId()
	 {
		return $this->id;
	 }
	 
	 public function setId($id)
	 {
		$this->id = $id;
	 }
	
	public function getCrc_Approval_No()
	{
		return $this->crc_approval_no;
	}
	
	public function setCrc_Approval_No($crc_approval_no)
	{
		$this->crc_approval_no = $crc_approval_no;
	}
	
	public function getSigned_Certification_Researchers()
	{
		return $this->signed_certification_researchers;
	}
	
	public function setSigned_Certification_Researchers($signed_certification_researchers)
	{
		$this->signed_certification_researchers = $signed_certification_researchers;
	}
	
	public function getSigned_Certification_Crc()
	{
		return $this->signed_certification_crc;
	}
	
	public function setSigned_Certification_Crc($signed_certification_crc)
	{
		$this->signed_certification_crc = $signed_certification_crc;
	}
	
	public function getResearch_Proposal()
	{
		return $this->research_proposal;
	}
	
	public function setResearch_Proposal($research_proposal)
	{
		$this->research_proposal = $research_proposal;
	}
	
	public function getApplication_Step_Status()
	{
		return $this->application_step_status; 
	}
	
	public function setApplication_Step_Status($application_step_status)
	{
		$this->application_step_status = $application_step_status;
	}
	
	public function getCrc_Amount_Granted()
	{
		return $this->crc_amount_granted;
	}
	
	public function setCrc_Amount_Granted($crc_amount_granted)
	{
		$this->crc_amount_granted = $crc_amount_granted;
	}
	
	public function getBudgetplan()
	{
		return $this->budgetplan;
	}
	
	public function setBudgetplan($budgetplan)
	{
		$this->budgetplan = $budgetplan;
	}
	 
}
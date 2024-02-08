<?php

namespace CollegeResearch\Model;

class UpdateCargGrant
{
	protected $id;
	protected $carg_grant_id;
	protected $carg_research_status;
	protected $carg_remarks;
	protected $carg_evidence_file;
	protected $carg_update_date;
	 	 
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getCarg_Grant_Id()
	{
		return $this->carg_grant_id;
	}
	
	public function setCarg_Grant_Id($carg_grant_id)
	{
		$this->carg_grant_id = $carg_grant_id;
	}
	
	public function getCarg_Research_Status()
	{
		return $this->carg_research_status;
	}
	
	public function setCarg_Research_Status($carg_research_status)
	{
		$this->carg_research_status = $carg_research_status;
	}
	
	public function getCarg_Remarks()
	{
		return $this->carg_remarks;
	}
	
	public function setCarg_Remarks($carg_remarks)
	{
		$this->carg_remarks = $carg_remarks;
	}
	
	public function getCarg_Evidence_File()
	{
		return $this->carg_evidence_file;
	}
	
	public function setCarg_Evidence_File($carg_evidence_file)
	{
		$this->carg_evidence_file = $carg_evidence_file;
	}


	public function getCarg_Update_Date()
	{
		return $this->carg_update_date;
	}
	
	public function setCarg_Update_Date($carg_update_date)
	{
		$this->carg_update_date = $carg_update_date;
	}
}
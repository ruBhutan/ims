<?php

namespace UniversityResearch\Model;

class UpdateAurgGrant
{
	protected $id;
	protected $aurg_grant_id;
	protected $aurg_research_status;
	protected $aurg_remarks;
	protected $aurg_evidence_file;
	protected $aurg_update_date;
	 	 
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getAurg_Grant_Id()
	{
		return $this->aurg_grant_id;
	}
	
	public function setAurg_Grant_Id($aurg_grant_id)
	{
		$this->aurg_grant_id = $aurg_grant_id;
	}
	
	public function getAurg_Research_Status()
	{
		return $this->aurg_research_status;
	}
	
	public function setAurg_Research_Status($aurg_research_status)
	{
		$this->aurg_research_status = $aurg_research_status;
	}
	
	public function getAurg_Remarks()
	{
		return $this->aurg_remarks;
	}
	
	public function setAurg_Remarks($aurg_remarks)
	{
		$this->aurg_remarks = $aurg_remarks;
	}
	
	public function getAurg_Evidence_File()
	{
		return $this->aurg_evidence_file;
	}
	
	public function setAurg_Evidence_File($aurg_evidence_file)
	{
		$this->aurg_evidence_file = $aurg_evidence_file;
	}

	public function getAurg_Update_Date()
	{
		return $this->aurg_update_date;
	}
	
	public function setAurg_Update_Date($aurg_update_date)
	{
		$this->aurg_update_date = $aurg_update_date;
	}
}
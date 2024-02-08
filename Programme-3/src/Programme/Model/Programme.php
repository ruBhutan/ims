<?php

namespace Programme\Model;

class Programme
{
	protected $id;
	protected $programme_name;
	protected $programme_description;
	protected $programme_leader;
	protected $programme_approval_date;
	protected $programme_duration;
	protected $duration_units;
	protected $mode_of_study;
	protected $academic_session_id;
	protected $programme_apmr_date;
	protected $programme_ccr_date;
	protected $programme_approved_dpd;
	protected $programme_code;
	protected $status;
	protected $organisation_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getProgramme_Name()
	{
		return $this->programme_name;
	}
	
	public function setProgramme_Name($programme_name)
	{
		$this->programme_name = $programme_name;
	}
	
	public function getProgramme_Description()
	{
		return $this->programme_description;
	}
	
	public function setProgramme_Description($programme_description)
	{
		$this->programme_description = $programme_description;
	}
	
	public function getProgramme_Leader()
	{
		return $this->programme_leader;
	}
	
	public function setProgramme_Leader($programme_leader)
	{
		$this->programme_leader = $programme_leader;
	}
	
	public function getProgramme_Approval_Date()
	{
		return $this->programme_approval_date;
	}
	
	public function setProgramme_Approval_Date($programme_approval_date)
	{
		$this->programme_approval_date = $programme_approval_date;
	}
	
	public function getProgramme_Duration()
	{
		return $this->programme_duration;
	}
	
	public function setProgramme_Duration($programme_duration)
	{
		$this->programme_duration = $programme_duration;
	}
	
	public function getDuration_Units()
	{
		return $this->duration_units;
	}
	
	public function setDuration_Units($duration_units)
	{
		$this->duration_units = $duration_units;
	}
	
	public function getMode_Of_Study()
	{
		return $this->mode_of_study;
	}
	
	public function setMode_Of_Study($mode_of_study)
	{
		$this->mode_of_study = $mode_of_study;
	}
	
	public function getAcademic_Session_Id()
	{
		return $this->academic_session_id;
	}
	
	public function setAcademic_Session_Id($academic_session_id)
	{
		$this->academic_session_id = $academic_session_id;
	}
		
	public function getProgramme_Apmr_date()
	{
		return $this->programme_apmr_date;
	}
	
	public function setProgramme_Apmr_Date($programme_apmr_date)
	{
		$this->programme_apmr_date = $programme_apmr_date;
	}
	
	public function getProgramme_Ccr_date()
	{
		return $this->programme_ccr_date;
	}
	
	public function setProgramme_Ccr_Date($programme_ccr_date)
	{
		$this->programme_ccr_date = $programme_ccr_date;
	}
	
	public function getProgramme_Approved_Dpd()
	{
		return $this->programme_approved_dpd;
	}
	
	public function setProgramme_Approved_Dpd($programme_approved_dpd)
	{
		$this->programme_approved_dpd = $programme_approved_dpd;
	}
	
	public function getProgramme_Code()
	{
		return $this->programme_code;
	}
	
	public function setProgramme_Code($programme_code)
	{
		$this->programme_code = $programme_code;
	}
	
	public function getStatus()
	{
		return $this->status;
	}
	
	public function setStatus($status)
	{
		$this->status = $status;
	}
	
	public function getOrganisation_Id()
	{
		return $this->organisation_id;
	}
	
	public function setOrganisation_Id($organisation_id)
	{
		$this->organisation_id = $organisation_id;
	}
}
<?php

namespace EmployeeLeave\Model;

class OfficiatingSupervisor
{
	protected $id;
	protected $from_date;
	protected $to_date;
	protected $officiating_supervisor;
	protected $date_range;
	protected $supervisor;
	protected $supervisor_id;
	protected $department;
	protected $remarks;
	protected $evidence_file;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
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
	
	public function getOfficiating_Supervisor()
	{
		return $this->officiating_supervisor;
	}
	
	public function setOfficiating_Supervisor($officiating_supervisor)
	{
		$this->officiating_supervisor = $officiating_supervisor;
	}
	
	public function getDate_Range()
	{
		return $this->date_range;
	}
	
	public function setDate_Range($date_range)
	{
		$this->date_range = $date_range;
	}
	
	public function getSupervisor()
	{
		return $this->supervisor;
	}
	
	public function setSupervisor($supervisor)
	{
		$this->supervisor = $supervisor;
	}
	
	public function getSupervisor_Id()
	{
		return $this->supervisor_id;
	}
	
	public function setSupervisor_Id($supervisor_id)
	{
		$this->supervisor_id = $supervisor_id;
	}
	
	public function getDepartment()
	{
		return $this->department;
	}
	
	public function setDepartment($department)
	{
		$this->department = $department;
	}
	
	public function getRemarks()
	{
		return $this->remarks;
	}
	
	public function setRemarks($remarks)
	{
		$this->remarks = $remarks;
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
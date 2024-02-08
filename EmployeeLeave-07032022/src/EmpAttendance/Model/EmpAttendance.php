<?php

namespace EmpAttendance\Model;

class EmpAttendance
{
	protected $id;
	protected $attendance_category;
	protected $total_days;
	protected $approval_by;
	protected $remarks;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getLeave_Category()
	{
		return $this->attendance_category;
	}
	
	public function setLeave_Category($attendance_category)
	{
		$this->attendance_category = $attendance_category;
	}
	
	public function getTotal_Days()
	{
		return $this->total_days;
	}
	
	public function setTotal_Days($total_days)
	{
		$this->total_days = $total_days;
	}
	
	public function getApproval_By()
	{
		return $this->approval_by;
	}
	
	public function setApproval_By($approval_by)
	{
		$this->approval_by = $approval_by;
	}
	
	public function getRemarks()
	{
		return $this->remarks;
	}
	
	public function setRemarks($remarks)
	{
		$this->remarks = $remarks;
	}
	
}
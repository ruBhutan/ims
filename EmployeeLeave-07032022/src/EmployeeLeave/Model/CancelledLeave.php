<?php

namespace EmployeeLeave\Model;

class CancelledLeave
{
	protected $id;
	protected $no_of_days;
	protected $from_date;
	protected $to_date;
	protected $cancelled_by;
	protected $employee_details_id;
	protected $emp_leave_id;
	protected $remarks;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getNo_Of_Days()
	{
		return $this->no_of_days;
	}
	
	public function setNo_Of_Days($no_of_days)
	{
		$this->no_of_days = $no_of_days;
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
	
	public function getCancelled_By()
	{
		return $this->cancelled_by;
	}
	
	public function setCancelled_By($cancelled_by)
	{
		$this->cancelled_by = $cancelled_by;
	}
	
	public function getEmployee_Details_Id()
	{
		return $this->employee_details_id;
	}
	
	public function setEmployee_Details_Id($employee_details_id)
	{
		$this->employee_details_id = $employee_details_id;
	}
	
	public function getEmp_Leave_Id()
	{
		return $this->emp_leave_id;
	}
	
	public function setEmp_Leave_Id($emp_leave_id)
	{
		$this->emp_leave_id = $emp_leave_id;
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
<?php

namespace LeaveEncashment\Model;

class LeaveEncashment
{
	protected $id;
	protected $application_date;
	protected $leave_encashment_status;
	protected $leave_balance;
	protected $remarks;
	protected $approval_date;
	protected $employee_details_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getApplication_Date()
	{
		return $this->application_date;
	}
	
	public function setApplication_Date($application_date)
	{
		$this->application_date = $application_date;
	}
	
	public function getLeave_Encashment_Status()
	{
		return $this->leave_encashment_status;
	}
	
	public function setLeave_Encashment_Status($leave_encashment_status)
	{
		$this->leave_encashment_status = $leave_encashment_status;
	}
	
	public function getLeave_Balance()
	{
		return $this->leave_balance;
	}
	
	public function setLeave_Balance($leave_balance)
	{
		$this->leave_balance = $leave_balance;
	}
	
	public function getRemarks()
	{
		return $this->remarks;
	}
	
	public function setRemarks($remarks)
	{
		$this->remarks = $remarks;
	}
	
	public function getApproval_Date()
	{
		return $this->approval_date;
	}
	
	public function setApproval_Date($approval_date)
	{
		$this->approval_date = $approval_date;
	}
	
	public function getEmployee_Details_Id()
	{
		return $this->employee_details_id;
	}
	
	public function setEmployee_Details_Id($employee_details_id)
	{
		$this->employee_details_id = $employee_details_id;
	}
	
}
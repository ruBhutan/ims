<?php

namespace EmployeeLeave\Model;

class EmployeeLeave
{
	protected $id;
	protected $first_name;
	protected $middle_name;
	protected $last_name;
	protected $days_of_leave;
	protected $from_date;
	protected $to_date;
	protected $substitution;
	protected $remarks;
	protected $evidence_file;
	protected $reason;
	protected $leave_status;
	protected $approved_by;
	protected $employee_details_id;
	protected $emp_leave_category_id;
	protected $applied_by_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getFirst_Name()
	{
		return $this->first_name;
	}
	
	public function setFirst_Name($first_name)
	{
		$this->first_name = $first_name;
	}
	
	public function getMiddle_Name()
	{
		return $this->middle_name;
	}
	
	public function setMiddle_Name($middle_name)
	{
		$this->middle_name = $middle_name;
	}
	
	public function getLast_Name()
	{
		return $this->last_name;
	}
	
	public function setLast_Name($last_name)
	{
		$this->last_name = $last_name;
	}
	 
	public function getDays_Of_Leave()
	{
		return $this->days_of_leave;
	}
	
	public function setDays_Of_Leave($days_of_leave)
	{
		$this->days_of_leave = $days_of_leave;
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
	
	public function getSubstitution()
	{
		return $this->substitution;
	}
	
	public function setSubstitution($substitution)
	{
		$this->substitution = $substitution;
	}
	
	public function getReason()
	{
		return $this->reason;
	}
	
	public function setReason($reason)
	{
		$this->reason = $reason;
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
	
	public function getLeave_Status()
	{
		return $this->leave_status;
	}
	
	public function setLeave_Status($leave_status)
	{
		$this->leave_status = $leave_status;
	}
	
	public function getApproved_By()
	{
		return $this->approved_by;
	}
	
	public function setApproved_By($approved_by)
	{
		$this->approved_by = $approved_by;
	}
	
	public function getEmployee_Details_Id()
	{
		return $this->employee_details_id;
	}
	
	public function setEmployee_Details_Id($employee_details_id)
	{
		$this->employee_details_id = $employee_details_id;
	}
	
	public function getEmp_Leave_Category_Id()
	{
		return $this->emp_leave_category_id;
	}
	
	public function setEmp_Leave_Category_Id($emp_leave_category_id)
	{
		$this->emp_leave_category_id = $emp_leave_category_id;
	}


	public function getApplied_By_Id()
	{
		return $this->applied_by_id;
	}
	
	public function setApplied_By_Id($applied_by_id)
	{
		$this->applied_by_id = $applied_by_id;
	}
}
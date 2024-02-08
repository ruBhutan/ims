<?php

namespace LeaveCategory\Model;

class LeaveCategory
{
	protected $id;
	protected $leave_category;
	protected $total_days;
	protected $approval_by;
	protected $category_type;
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
		return $this->leave_category;
	}
	
	public function setLeave_Category($leave_category)
	{
		$this->leave_category = $leave_category;
	}
	
	public function getTotal_Days()
	{
		return $this->total_days;
	}
	
	public function setTotal_Days($total_days)
	{
		$this->total_days = $total_days;
	}
	
	public function getCategory_Type()
	{
		return $this->category_type;
	}
	
	public function setCategory_Type($category_type)
	{
		$this->category_type = $category_type;
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
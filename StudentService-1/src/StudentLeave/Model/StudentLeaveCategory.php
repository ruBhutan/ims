<?php

namespace StudentLeave\Model;

class StudentLeaveCategory
{
	protected $id;
	protected $leave_category;
	protected $approval_by;
	protected $remarks;
	protected $organisation_id;
	
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

	public function getOrganisation_Id()
	{
		return $this->organisation_id;
	}
	
	public function setOrganisation_Id($organisation_id)
	{
		$this->organisation_id = $organisation_id;
	}
	
}
<?php

namespace EmployeeDetail\Model;

class EmployeeAward
{
	protected $id;
	protected $award_category_id;
	protected $award_name;
	protected $award_date;
	protected $award_given_by;
	protected $award_reasons;
	protected $remarks;
	protected $employee_details_id;
	protected $evidence_file;
	 
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}

	public function getAward_Category_Id()
	{
		return $this->award_category_id;
	}
	
	public function setAward_Category_Id($award_category_id)
	{
		$this->award_category_id = $award_category_id;
	}
	
	public function getAward_Name()
	{
		return $this->award_name;
	}
	
	public function setAward_Name($award_name)
	{
		$this->award_name = $award_name;
	}
	
	public function getAward_Date()
	{
		return $this->award_date;
	}
	
	public function setAward_Date($award_date)
	{
		$this->award_date = $award_date;
	}
	
	public function getAward_Reasons()
	{
		return $this->award_reasons;
	}
	
	public function setAward_Reasons($award_reasons)
	{
		$this->award_reasons = $award_reasons;
	}
	
	public function getAward_Given_by()
	{
		return $this->award_given_by;
	}
	
	public function setAward_Given_By($award_given_by)
	{
		$this->award_given_by = $award_given_by;
	}
	
	public function getRemarks()
	{
		return $this->remarks;
	}
	
	public function setRemarks($remarks)
	{
		$this->remarks = $remarks;
	}
	
	public function getEmployee_Details_Id()
	{
		return $this->employee_details_id;
	}
	
	public function setEmployee_Details_Id($employee_details_id)
	{
		$this->employee_details_id = $employee_details_id;
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
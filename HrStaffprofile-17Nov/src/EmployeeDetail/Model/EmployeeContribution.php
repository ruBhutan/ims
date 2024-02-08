<?php

namespace EmployeeDetail\Model;

class EmployeeContribution
{
	protected $id;
	protected $contribution_category_id;
	protected $contribution_date;
	protected $contribution_type;
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

	 public function getContribution_Category_Id()
	 {
		 return $this->contribution_category_id;
	 }
	 
	 public function setContribution_Category_Id($contribution_category_id)
	 {
		 $this->contribution_category_id = $contribution_category_id;
	 }
	 
	 public function getContribution_Date()
	 {
		 return $this->contribution_date;
	 }
	 
	 public function setContribution_Date($contribution_date)
	 {
		 $this->contribution_date = $contribution_date;
	 }
	 
	 public function getContribution_Type()
	 {
		 return $this->contribution_type;
	 }
	 
	 public function setContribution_Type($contribution_type)
	 {
		 $this->contribution_type = $contribution_type;
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
<?php

namespace EmployeeDetail\Model;

class EmployeeResponsibilities
{
	protected $id;
	protected $responsibility_category_id;
	protected $responsibility_name;
	protected $start_date;
	protected $end_date;
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

	 public function getResponsibility_Category_Id()
	 {
		 return $this->responsibility_category_id;
	 }
	 
	 public function setResponsibility_Category_Id($responsibility_category_id)
	 {
		 $this->responsibility_category_id = $responsibility_category_id;
	 }
	 
	 public function getResponsibility_Name()
	 {
		 return $this->responsibility_name;
	 }
	 
	 public function setResponsibility_Name($responsibility_name)
	 {
		 $this->responsibility_name = $responsibility_name;
	 }
	 
	 public function getStart_Date()
	 {
		 return $this->start_date;
	 }
	 
	 public function setStart_Date($start_date)
	 {
		 $this->start_date = $start_date;
	 }
	 
	 public function getEnd_Date()
	 {
		 return $this->end_date;
	 }
	 
	 public function setEnd_Date($end_date)
	 {
		 $this->end_date = $end_date;
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
<?php

namespace CollegeResearch\Model;

class CargGrant
{
	protected $id;
	protected $grant_type;
	protected $grant_applied_for;
	protected $research_title;
	protected $amount_applied_for;
	protected $research_year;
	protected $carg_category_type;
	protected $research_summary;
	protected $crc_approval_no;
	protected $crc_amount_granted;
	protected $application_status;
	protected $remarks;
	protected $application_step_status;
	protected $coresearchers;
	protected $employee_details_id;
	
	 	 
	 public function getId()
	 {
		return $this->id;
	 }
	 
	 public function setId($id)
	 {
		$this->id = $id;
	 }
	 
	 public function getGrant_Type()
	 {
		 return $this->grant_type;
	 }
	 
	 public function setGrant_Type($grant_type)
	 {
		 $this->grant_type = $grant_type;
	 }
	 
	 public function getGrant_Applied_For()
	 {
		return $this->grant_applied_for; 
	 }
	 	 
	 public function setGrant_Applied_For($grant_applied_for)
	 {
		$this->grant_applied_for = $grant_applied_for;
	 }
	 
	 public function getResearch_Title()
	 {
		return $this->research_title;
	 }
	 
	 public function setResearch_Title($research_title)
	 {
		$this->research_title = $research_title;
	 }
	 
	 public function getAmount_Applied_For()
	 {
		return $this->amount_applied_for; 
	 }
	 	 
	 public function setAmount_Applied_For($amount_applied_for)
	 {
		$this->amount_applied_for = $amount_applied_for;
	 }
	 	 
	 public function getResearch_Year()
	 {
		return $this->research_year; 
	 }
	 	 
	 public function setResearch_Year($research_year)
	 {
		$this->research_year = $research_year;
	 }

	 public function getCarg_Category_Type()
	 {
		return $this->carg_category_type; 
	 }
	 	 
	 public function setCarg_Category_Type($carg_category_type)
	 {
		$this->carg_category_type = $carg_category_type;
	 }
	 
	 public function getResearch_Summary()
	 {
		return $this->research_summary; 
	 }
	 	 
	 public function setResearch_Summary($research_summary)
	 {
		$this->research_summary = $research_summary;
	 }
	 
	 public function getCrc_Approval_no()
	 {
		return $this->crc_approval_no; 
	 }
	 	 
	 public function setCrc_Approval_no($crc_approval_no)
	 {
		$this->crc_approval_no = $crc_approval_no;
	 }
	 
	 public function getCrc_Amount_Granted()
	 {
		return $this->crc_amount_granted; 
	 }
	 	 
	 public function setCrc_Amount_Granted($crc_amount_granted)
	 {
		$this->crc_amount_granted = $crc_amount_granted;
	 }
	
	 public function getApplication_Status()
	 {
		return $this->application_status; 
	 }
	 	 
	 public function setApplication_Status($application_status)
	 {
		$this->application_status = $application_status;
	 }
	 
	 public function getRemarks()
	 {
		return $this->remarks; 
	 }
	 	 
	 public function setRemarks($remarks)
	 {
		$this->remarks = $remarks;
	 }
	 
	 public function getApplication_Step_Status()
	 {
		return $this->application_step_status; 
	 }
	 	 
	 public function setApplication_Step_Status($application_step_status)
	 {
		$this->application_step_status = $application_step_status;
	 }
	 
	 public function getCoresearchers()
	 {
		 return $this->coresearchers;
	 }
	 
	 public function setCoresearchers($coresearchers)
	 {
		$this->coresearchers = $coresearchers; 
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
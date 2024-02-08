<?php

namespace CollegeResearch\Model;

class CargResearch
{
	protected $id;
	protected $amount_applied_for;
	protected $research_summary;
	protected $application_step_status;
	protected $actionplan;
	protected $employee_details_id;
	
	 	 
	 public function getId()
	 {
		return $this->id;
	 }
	 
	 public function setId($id)
	 {
		$this->id = $id;
	 }
	 
	 public function getAmount_Applied_For()
	 {
		return $this->amount_applied_for; 
	 }
	 	 
	 public function setAmount_Applied_For($amount_applied_for)
	 {
		$this->amount_applied_for = $amount_applied_for;
	 }
	 
	 public function getResearch_Summary()
	 {
		return $this->research_summary; 
	 }
	 	 
	 public function setResearch_Summary($research_summary)
	 {
		$this->research_summary = $research_summary;
	 }
	 
	 public function getApplication_Step_Status()
	 {
		return $this->application_step_status; 
	 }
	 	 
	 public function setApplication_Step_Status($application_step_status)
	 {
		$this->application_step_status = $application_step_status;
	 }
	 
	 public function getActionplan()
	 {
		 return $this->actionplan;
	 }
	 
	 public function setActionplan($actionplan)
	 {
		 $this->actionplan = $actionplan;
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
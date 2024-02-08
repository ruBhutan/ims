<?php

namespace JobPortal\Model;

class EmploymentDetails
{
	protected $id;
	protected $employer;
	protected $start_period;
	protected $end_period;
	protected $remarks;
	protected $job_applicant_id;
	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getEmployer()
	 {
		 return $this->employer;
	 }
	 
	 public function setEmployer($employer)
	 {
		 $this->employer = $employer;
	 }
	 
	 public function getStart_Period()
	 {
		 return $this->start_period;
	 }
	 
	 public function setStart_Period($start_period)
	 {
		 $this->start_period = $start_period;
	 }
	 
	 public function getEnd_Period()
	 {
		 return $this->end_period;
	 }
	 
	 public function setEnd_Period($end_period)
	 {
		 $this->end_period = $end_period;
	 }
	 
	 public function getRemarks()
	 {
		return $this->remarks;
	 }
	
	 public function setRemarks($remarks)
	 {
		$this->remarks = $remarks;
	 }
	 
	 public function getJob_Applicant_Id()
	 {
		return $this->job_applicant_id;
	 }
	
	 public function setJob_Applicant_Id($job_applicant_id)
	 {
		$this->job_applicant_id = $job_applicant_id;
	 }

}
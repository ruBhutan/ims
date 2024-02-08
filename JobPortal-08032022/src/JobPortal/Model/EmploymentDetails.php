<?php

namespace JobPortal\Model;

class EmploymentDetails
{
	protected $id;
	protected $working_agency;
	protected $occupational_group;
	protected $position_category;
	protected $position_title;
	protected $position_level;
	protected $start_period;
	protected $end_period;
	protected $remarks;
	protected $employment_record_file;
	protected $job_applicant_id;
	protected $last_updated;
	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getWorking_Agency()
	 {
		 return $this->working_agency;
	 }
	 
	 public function setWorking_Agency($working_agency)
	 {
		 $this->working_agency = $working_agency;
	 }
	 
	 public function getOccupational_Group()
	 {
		 return $this->occupational_group;
	 }
	 
	 public function setOccupational_Group($occupational_group)
	 {
		 $this->occupational_group = $occupational_group;
	 }
	 
	 public function getPosition_Level()
	 {
		 return $this->position_level;
	 }
	 
	 public function setPosition_Level($position_level)
	 {
		 $this->position_level = $position_level;
	 }
	 
	 public function getPosition_Title()
	 {
		 return $this->position_title;
	 }
	 
	 public function setPosition_Title($position_title)
	 {
		 $this->position_title = $position_title;
	 }
	 
	 public function getPosition_Category()
	 {
		 return $this->position_category;
	 }
	 
	 public function setPosition_Category($position_category)
	 {
		 $this->position_category = $position_category;
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

	 public function getEmployment_Record_File()
	 {
		return $this->employment_record_file;
	 }
	
	 public function setEmployment_Record_File($employment_record_file)
	 {
		$this->employment_record_file = $employment_record_file;
	 }
	 
	 public function getJob_Applicant_Id()
	 {
		return $this->job_applicant_id;
	 }
	
	 public function setJob_Applicant_Id($job_applicant_id)
	 {
		$this->job_applicant_id = $job_applicant_id;
	 }

	 public function getLast_Updated()
	 {
		return $this->last_updated;
	 }
	
	 public function setLast_Updated($last_updated)
	 {
		$this->last_updated = $last_updated;
	 }

}
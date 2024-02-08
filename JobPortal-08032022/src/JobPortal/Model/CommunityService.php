<?php

namespace JobPortal\Model;

class CommunityService
{
	protected $id;
	protected $service_name;
	protected $service_date;
	protected $remarks;
	protected $supporting_file;
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
	 
	 public function getService_Name()
	 {
		 return $this->service_name;
	 }
	 
	 public function setService_Name($service_name)
	 {
		 $this->service_name = $service_name;
	 }
	 
	 public function getService_Date()
	 {
		 return $this->service_date;
	 }
	 
	 public function setService_Date($service_date)
	 {
		 $this->service_date = $service_date;
	 }
	 
	 public function getRemarks()
	 {
		return $this->remarks;
	 }
	
	 public function setRemarks($remarks)
	 {
		$this->remarks = $remarks;
	 }

	 public function getSupporting_File()
	 {
		return $this->supporting_file;
	 }
	
	 public function setSupporting_File($supporting_file)
	 {
		$this->supporting_file = $supporting_file;
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
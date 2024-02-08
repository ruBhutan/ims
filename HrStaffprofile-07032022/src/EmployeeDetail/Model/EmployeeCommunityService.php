<?php

namespace EmployeeDetail\Model;

class EmployeeCommunityService
{
	protected $id;
	protected $community_service_category_id;
	protected $service_name;
	protected $service_date;
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

	 public function getCommunity_Service_Category_Id()
	 {
		 return $this->community_service_category_id;
	 }
	 
	 public function setCommunity_Service_Category_Id($community_service_category_id)
	 {
		 $this->community_service_category_id = $community_service_category_id;
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
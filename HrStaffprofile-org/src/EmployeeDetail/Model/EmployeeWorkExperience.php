<?php

namespace EmployeeDetail\Model;

class EmployeeWorkExperience
{
	protected $id;
	protected $working_agency;
	protected $occupational_group;
	protected $position_category;
	protected $position_title;
	protected $position_level;
	protected $start_period;
	protected $end_period;
	protected $date_range;
	protected $remarks;
	protected $employee_details_id;
	protected $working_agency_type;

	protected $office_order_no;
	protected $office_order_date;
	protected $evidence_file;
	 
	 	 
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

	 public function getDate_Range()
	 {
		 return $this->date_range;
	 }
	 
	 public function setDate_Range($date_range)
	 {
		 $this->date_range = $date_range;
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


	 public function getWorking_Agency_Type()
	 {
		return $this->working_agency_type;
	 }
	
	 public function setWorking_Agency_Type($working_agency_type)
	 {
		$this->working_agency_type = $working_agency_type;
	 }


	 public function getOffice_Order_No()
	 {
		return $this->office_order_no;
	 }
	
	 public function setOffice_Order_No($office_order_no)
	 {
		$this->office_order_no = $office_order_no;
	 }


	 public function getOffice_Order_Date()
	 {
		return $this->office_order_date;
	 }
	
	 public function setOffice_Order_Date($office_order_date)
	 {
		$this->office_order_date = $office_order_date;
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
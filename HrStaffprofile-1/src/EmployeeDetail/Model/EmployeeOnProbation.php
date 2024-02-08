<?php

namespace EmployeeDetail\Model;

class EmployeeOnProbation
{
	protected $id;
	protected $employee_details_id;
	protected $office_order_no;
	protected $office_order_date;
	protected $evidence_file;
	protected $emp_type;

	protected $working_agency_type;

	protected $date;
	protected $position_title_id;
	protected $position_level_id;
	protected $position_category_id;
	protected $major_occupational_group_id;
	protected $organisation_id;
	protected $remarks;
	 
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }

	  public function getEmployee_Details_Id()
	 {
		return $this->employee_details_id;
	 }
	
	 public function setEmployee_Details_Id($employee_details_id)
	 {
		$this->employee_details_id = $employee_details_id;
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

	 public function getEmp_Type()
	 {
		return $this->emp_type;
	 }
	
	 public function setEmp_Type($emp_type)
	 {
		$this->emp_type = $emp_type;
	 }
	
	 public function getWorking_Agency_Type()
	 {
		return $this->working_agency_type;
	 }
	
	 public function setWorking_Agency_Type($working_agency_type)
	 {
		$this->working_agency_type = $working_agency_type;
	 }

	 public function getDate()
	 {
		 return $this->date;
	 }
	 
	 public function setDate($date)
	 {
		 $this->date = $date;
	 }

	 public function getPosition_Title_Id()
	 {
		 return $this->position_title_id;
	 }
	 
	 public function setPosition_Title_Id($position_title_id)
	 {
		 $this->position_title_id = $position_title_id;
	 }
	 
	 public function getPosition_Level_Id()
	 {
		 return $this->position_level_id;
	 }
	 
	 public function setPosition_Level_Id($position_level_id)
	 {
		 $this->position_level_id = $position_level_id;
	 }

	 public function getPosition_Category_Id()
	 {
		 return $this->position_category_id;
	 }
	 
	 public function setPosition_Category_Id($position_category_id)
	 {
		 $this->position_category_id = $position_category_id;
	 }

	 public function getMajor_Occupational_Group_Id()
	 {
		return $this->major_occupational_group_id;
	 }
	
	 public function setMajor_Occupational_Group_Id($major_occupational_group_id)
	 {
		$this->major_occupational_group_id = $major_occupational_group_id;
	 }

	 public function getOrganisation_Id()
	 {
		return $this->organisation_id;
	 }
	
	 public function setOrganisation_Id($organisation_id)
	 {
		$this->organisation_id = $organisation_id;
	 }

	 public function getRemarks()
	 {
		return $this->remarks;
	 }
	
	 public function setRemarks($remarks)
	 {
		$this->remarks = $remarks;
	 }

}
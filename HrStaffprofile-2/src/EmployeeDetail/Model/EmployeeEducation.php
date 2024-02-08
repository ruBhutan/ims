<?php

namespace EmployeeDetail\Model;

class EmployeeEducation
{
	protected $id;
	protected $college_name;
	protected $college_location;
	protected $college_country;
	protected $field_study;
	protected $study_mode;
	protected $study_level;
	protected $start_date;
	protected $end_date;
	protected $funding;
	protected $marks_obtained;
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
	 
	 public function getCollege_Name()
	 {
		 return $this->college_name;
	 }
	 
	 public function setCollege_Name($college_name)
	 {
		 $this->college_name = $college_name;
	 }
	 
	 public function getCollege_Location()
	 {
		 return $this->college_location;
	 }
	 
	 public function setCollege_Location($college_location)
	 {
		 $this->college_location = $college_location;
	 }
	 
	 public function getCollege_Country()
	 {
		 return $this->college_country;
	 }
	 
	 public function setCollege_Country($college_country)
	 {
		 $this->college_country = $college_country;
	 }
	 
	 public function getField_Study()
	 {
		 return $this->field_study;
	 }
	 
	 public function setField_Study($field_study)
	 {
		 $this->field_study = $field_study;
	 }
	 
	 public function getStudy_Mode()
	 {
		 return $this->study_mode;
	 }
	 
	 public function setStudy_Mode($study_mode)
	 {
		 $this->study_mode = $study_mode;
	 }
	 
	 public function getStudy_Level()
	 {
		 return $this->study_level;
	 }
	 
	 public function setStudy_Level($study_level)
	 {
		 $this->study_level = $study_level;
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
	 
	 public function getFunding()
	 {
		 return $this->funding;
	 }
	 
	 public function setFunding($funding)
	 {
		 $this->funding = $funding;
	 }
	 
	 public function getMarks_Obtained()
	 {
		 return $this->marks_obtained;
	 }
	 
	 public function setMarks_Obtained($marks_obtained)
	 {
		 $this->marks_obtained = $marks_obtained;
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
<?php

namespace StaffPortal\Model;

class StaffDetail
{
	protected $id;
	protected $emp_id;
	protected $first_name;
	protected $middle_name;
	protected $last_name;
	protected $cid;
	protected $nationality;
	protected $date_of_birth;
	protected $emp_house_no;
	protected $emp_thram_no;
	protected $emp_dzongkhag;
	protected $emp_gewog;
	protected $emp_village;
	protected $emp_category;
	protected $gender;
	protected $marital_status;
	protected $college_name;
	protected $college_location;
	protected $college_country;
	protected $field_study;
	protected $subject_studied;
	protected $completion_year;
	protected $result_obtained;
	protected $certificate_obtained;
	protected $departments_units_id;
	protected $departments_id;
	protected $organisation_id;
	protected $inputFilter;


	protected $unit_name;
	protected $department_name;

	protected $leave_category;
	 
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getEmp_Id()
	 {
		return $this->emp_id; 
	 }
	 	 
	 public function setEmp_Id($emp_id)
	 {
		 $this->emp_id = $emp_id;
	 }
	 	 
	 public function getFirst_Name()
	 {
		return $this->first_name; 
	 }
	 	 
	 public function setFirst_Name($first_name)
	 {
		 $this->first_name=$first_name;
	 }
	 
	 public function getMiddle_Name()
	 {
		return $this->middle_name; 
	 }
	 	 
	 public function setMiddle_Name($middle_name)
	 {
		 $this->middle_name=$middle_name;
	 }
	 
	 public function getLast_Name()
	 {
		return $this->last_name; 
	 }
	 	 
	 public function setLast_Name($last_name)
	 {
		 $this->last_name=$last_name;
	 }
	 
	 public function getCid()
	 {
		return $this->cid; 
	 }
	 	 
	 public function setCid($cid)
	 {
		 $this->cid=$cid;
	 }
	 
	 public function getNationality()
	 {
		 return $this->nationality;
	 }
	 
	 public function setNationality($nationality)
	 {
		 $this->nationality = $nationality;
	 }
	 
	 public function getDate_Of_Birth()
	 {
		return $this->date_of_birth; 
	 }
	 	 
	 public function setDate_Of_Birth($date_of_birth)
	 {
		 $this->date_of_birth=$date_of_birth;
	 }
	 
	 public function getEmp_House_No()
	 {
		 return $this->emp_house_no;
	 }
	 
	 public function setEmp_House_No($emp_house_no)
	 {
		 $this->emp_house_no = $emp_house_no;
	 }
	 
	 public function getEmp_Thram_No()
	 {
		 return $this->emp_thram_no;
	 }
	 
	 public function setEmp_Thram_No($emp_thram_no)
	 {
		 $this->emp_thram_no = $emp_thram_no;
	 }
	 
	 public function getEmp_Gewog()
	 {
		 return $this->emp_gewog;
	 }
	 
	 public function setEmp_Gewog($emp_gewog)
	 {
		 $this->emp_gewog = $emp_gewog;
	 }
	 
	 public function getEmp_Village()
	 {
		 return $this->emp_village;
	 }
	 
	 public function setEmp_Village($emp_village)
	 {
		 $this->emp_village = $emp_village;
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
	 
	 public function getSubject_Studied()
	 {
		 return $this->subject_studied;
	 }
	 
	 public function setSubject_Studied($subject_studied)
	 {
		 $this->subject_studied = $subject_studied;
	 }
	 
	 public function getCompletion_Year()
	 {
		 return $this->completion_year;
	 }
	 
	 public function setCompletion_Year($completion_year)
	 {
		 $this->completion_year = $completion_year;
	 }
 
	 public function getResult_Obtained()
	 {
		 return $this->result_obtained;
	 }
	 
	 public function setResult_Obtained($result_obtained)
	 {
		 $this->result_obtained = $result_obtained;
	 }
	 
	 public function getCertificate_Obtained()
	 {
		 return $this->certificate_obtained;
	 }
	 
	 public function setCertificate_Obtained($certificate_obtained)
	 {
		 $this->certificate_obtained = $certificate_obtained;
	 }
	 
	 public function getDepartments_Units_Id()
	 {
		 return $this->departments_units_id;
	 }
	 
	 public function setDepartments_Units_Id($departments_units_id)
	 {
		 $this->departments_units_id = $departments_units_id;
	 }
	 
	 public function getDepartments_Id()
	 {
		 return $this->departments_id;
	 }
	 
	 public function setDepartments_Id($departments_id)
	 {
		 $this->departments_id = $departments_id;
	 }
	 
	 public function getOrganisation_Id()
	 {
		 return $this->organisation_id;
	 }
	 
	 public function setOrganisation_Id($organisation_id)
	 {
		 $this->organisation_id = $organisation_id;
	 }

	 public function getUnit_Name()
	 {
		 return $this->unit_name;
	 }
	 
	 public function setUnit_Name($unit_name)
	 {
		 $this->unit_name = $unit_name;
	 }

	 public function getDepartment_Name()
	 {
		 return $this->department_name;
	 }
	 
	 public function setDepartment_Name($department_name)
	 {
		 $this->department_name = $department_name;
	 }

	 public function getLeave_Category()
	 {
		 return $this->leave_category;
	 }
	 
	 public function setLeave_Category($leave_category)
	 {
		 $this->leave_category = $leave_category;
	 }
}
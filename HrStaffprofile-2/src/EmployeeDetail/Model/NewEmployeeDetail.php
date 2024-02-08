<?php
//This model is to handle new employees. Will not be used for anything else.

namespace EmployeeDetail\Model;

class NewEmployeeDetail
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
	protected $country;
	protected $emp_category;
	protected $gender;
	protected $marital_status;
	protected $phone_no;
	protected $email;
	protected $blood_group;
	protected $religion;
	protected $organisation_id;
	protected $departments_id;
	protected $departments_units_id;
	protected $occupational_group;
	protected $emp_type;
	protected $emp_position_title;
	protected $emp_position_level;
	protected $recruitment_date;
	protected $emp_resignation_id;	 
	 	 
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
	 
	 public function getEmp_Dzongkhag()
	 {
		 return $this->emp_dzongkhag;
	 }
	 
	 public function setEmp_Dzongkhag($emp_dzongkhag)
	 {
		 $this->emp_dzongkhag = $emp_dzongkhag;
	 }
	 
	 public function getCountry()
	 {
		 return $this->country;
	 }
	 
	 public function setCountry($country)
	 {
		 $this->country = $country;
	 }
	 
	 public function getRecruitment_Date()
	 {
		 return $this->recruitment_date;
	 }
	 
	 public function setRecruitment_Date($recruitment_date)
	 {
		 $this->recruitment_date = $recruitment_date;
	 }
	 
	 public function getEmp_Category()
	 {
		 return $this->emp_category;
	 }
	 
	 public function setEmp_Category($emp_category)
	 {
		 $this->emp_category = $emp_category;
	 }
	 
	 public function getGender()
	 {
		 return $this->gender;
	 }
	 
	 public function setGender($gender)
	 {
		 $this->gender = $gender;
	 }
	 
	 public function getMarital_Status()
	 {
		 return $this->marital_status;
	 }
	 
	 public function setMarital_Status($marital_status)
	 {
		 $this->marital_status = $marital_status;
	 }
	 
	 public function getPhone_No()
	 {
		 return $this->phone_no;
	 }
	 
	 public function setPhone_No($phone_no)
	 {
		 $this->phone_no = $phone_no;
	 }
	 
	 public function getEmail()
	 {
		 return $this->email;
	 }
	 
	 public function setEmail($email)
	 {
		 $this->email = $email;
	 }
	 
	 public function getBlood_Group()
	 {
		 return $this->blood_group;
	 }
	 
	 public function setBlood_Group($blood_group)
	 {
		 $this->blood_group = $blood_group;
	 }
	 
	 public function getReligion()
	 {
		 return $this->religion;
	 }
	 
	 public function setReligion($religion)
	 {
		 $this->religion = $religion;
	 }
	 
	 public function getOrganisation_Id()
	 {
		 return $this->organisation_id;
	 }
	 
	 public function setOrganisation_Id($organisation_id)
	 {
		 $this->organisation_id = $organisation_id;
	 }
	 
	 public function getDepartments_Id()
	 {
		 return $this->departments_id;
	 }
	 
	 public function setDepartments_Id($departments_id)
	 {
		 $this->departments_id = $departments_id;
	 }
	 
	 public function getDepartments_Units_Id()
	 {
		 return $this->departments_units_id;
	 }
	 
	 public function setDepartments_Units_Id($departments_units_id)
	 {
		 $this->departments_units_id = $departments_units_id;
	 }
	 
	  public function getOccupational_Group()
	 {
		 return $this->occupational_group;
	 }
	 
	 public function setOccupational_Group($occupational_group)
	 {
		 $this->occupational_group = $occupational_group;
	 }
	 
	 public function getEmp_Type()
	 {
		return $this->emp_type; 
	 }
	 
	 public function setEmp_Type($emp_type)
	 {
		 $this->emp_type = $emp_type;
	 }
	 	 
	 public function getEmp_Position_Title()
	 {
		 return $this->emp_position_title;
	 }
	 
	 public function setEmp_Position_Title($emp_position_title)
	 {
		 $this->emp_position_title = $emp_position_title;
	 }
	 
	 public function getEmp_Position_Level()
	 {
		 return $this->emp_position_level;
	 }
	 
	 public function setEmp_Position_Level($emp_position_level)
	 {
		 $this->emp_position_level = $emp_position_level;
	 }

	 public function getEmp_Resignation_Id()
	 {
		 return $this->emp_resignation_id;
	 }
	 
	 public function setEmp_Resignation_Id($emp_resignation_id)
	 {
		 $this->emp_resignation_id = $emp_resignation_id;
	 }
	 
}
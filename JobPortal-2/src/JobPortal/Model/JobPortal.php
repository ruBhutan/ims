<?php

namespace JobPortal\Model;

class JobPortal
{
	protected $id;
	protected $first_name;
	protected $middle_name;
	protected $last_name;
	protected $cid;
	protected $nationality;
	protected $date_of_birth;
	protected $house_no;
	protected $thram_no;
	protected $dzongkhag;
	protected $gewog;
	protected $village;
	protected $category;
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
	protected $job_applicant_id;
	protected $inputFilter;
	 
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
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
	 
	 public function getGewog()
	 {
		 return $this->gewog;
	 }
	 
	 public function setGewog($gewog)
	 {
		 $this->gewog = $gewog;
	 }
	 
	 public function getVillage()
	 {
		 return $this->village;
	 }
	 
	 public function setVillage($village)
	 {
		 $this->village = $village;
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
	 
	 public function getJob_Applicant_Id()
	 {
		return $this->job_applicant_id;
	 }
	
	 public function setJob_Applicant_Id($job_applicant_id)
	 {
		$this->job_applicant_id = $job_applicant_id;
	 }

}
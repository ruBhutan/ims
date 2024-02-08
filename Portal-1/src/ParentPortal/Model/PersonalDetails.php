<?php

namespace JobPortal\Model;

class PersonalDetails
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
	protected $gender;
	protected $marital_status;
	protected $job_applicant_id;
	  
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
	 
	 public function getHouse_No()
	 {
		 return $this->house_no;
	 }
	 
	 public function setHouse_No($house_no)
	 {
		 $this->house_no = $house_no;
	 }
	 
	 public function getThram_No()
	 {
		 return $this->thram_no;
	 }
	 
	 public function setThram_No($thram_no)
	 {
		 $this->thram_no = $thram_no;
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
	 
	 public function getDzongkhag()
	 {
		 return $this->dzongkhag;
	 }
	 
	 public function setDzongkhag($dzongkhag)
	 {
		 $this->dzongkhag = $dzongkhag;
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
	 
	 public function getJob_Applicant_Id()
	 {
		return $this->job_applicant_id;
	 }
	
	 public function setJob_Applicant_Id($job_applicant_id)
	 {
		$this->job_applicant_id = $job_applicant_id;
	 }
}
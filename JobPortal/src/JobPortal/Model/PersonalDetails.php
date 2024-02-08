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
	protected $country;
	protected $date_of_birth;
	protected $email;
	protected $house_no;
	protected $thram_no;
	protected $dzongkhag;
	protected $gewog;
	protected $village;
	protected $gender;
	protected $maritial_status;
	protected $contact_no;
	protected $cid_copy;
	protected $profile_picture;
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

	 public function getCountry()
	 {
		 return $this->country;
	 }
	 
	 public function setCountry($country)
	 {
		 $this->country = $country;
	 }
	 
	 public function getDate_Of_Birth()
	 {
		return $this->date_of_birth; 
	 }
	 	 
	 public function setDate_Of_Birth($date_of_birth)
	 {
		 $this->date_of_birth=$date_of_birth;
	 }

	 public function getEmail()
	 {
		return $this->email; 
	 }
	 	 
	 public function setEmail($email)
	 {
		 $this->email=$email;
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
	 
	 public function getMaritial_Status()
	 {
		 return $this->maritial_status;
	 }
	 
	 public function setMaritial_Status($maritial_status)
	 {
		 $this->maritial_status = $maritial_status;
	 }

	 public function getContact_No()
	 {
		 return $this->contact_no;
	 }
	 
	 public function setContact_No($contact_no)
	 {
		 $this->contact_no = $contact_no;
	 }

	 public function getCid_Copy()
	 {
		return $this->cid_copy;
	 }
	
	 public function setCid_Copy($cid_copy)
	 {
		$this->cid_copy = $cid_copy;
	 }

	 public function getProfile_Picture()
	 {
		return $this->profile_picture;
	 }
	
	 public function setProfile_Picture($profile_picture)
	 {
		$this->profile_picture = $profile_picture;
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
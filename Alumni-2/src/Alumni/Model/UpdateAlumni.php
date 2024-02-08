<?php

namespace Alumni\Model;

class UpdateALumni
{
	protected $id;
	protected $first_name;
	protected $middle_name;
	protected $last_name;
	protected $cid;
	protected $date_of_birth;
	protected $programme_name;
	protected $graduation_year;
	protected $contact_no;
	protected $email_address;
	protected $present_address;
	protected $current_job;
    protected $qualification;
    protected $alumni_status;
    protected $subscription;

    //protected $student_id;
    protected $organisation_id;
    protected $alumni_programmes_id;

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
		 $this->first_name = $first_name;
	 }
	 
	 public function getMiddle_Name()
	 {
		 return $this->middle_name;
	 }
	 
	 public function setMiddle_Name($middle_name)
	 {
		 $this->middle_name = $middle_name;
	 }
	 	 
	 public function getLast_Name()
	 {
		return $this->last_name; 
	 }
	 	 
	 public function setLast_Name($last_name)
	 {
		 $this->last_name = $last_name;
	 }
	 
	 public function getCid()
	 {
		return $this->cid; 
	 }
	 	 
	 public function setCid($cid)
	 {
		 $this->cid = $cid;
	 }

	  public function getDate_Of_Birth()
	 {
		return $this->date_of_birth; 
	 }
	 	 
	 public function setDate_Of_Birth($date_of_birth)
	 {
		 $this->date_of_birth = $date_of_birth;
	 }

	 
	 
	 public function getGraduation_Year()
	 {
		return $this->graduation_year; 
	 }
	 	 
	 public function setGraduation_Year($graduation_year)
	 {
		 $this->graduation_year = $graduation_year;
	 }
	
	 public function getContact_No()
	 {
		return $this->contact_no; 
	 }
	 	 
	 public function setContact_No($contact_no)
	 {
		 $this->contact_no = $contact_no;
	 }
	 
	 public function getEmail_Address()
	 {
		 return $this->email_address;
	 }
	 
	 public function setEmail_Address($email_address)
	 {
		 $this->email_address = $email_address;
	 }

	 public function getPresent_Address()
	 {
		 return $this->present_address;
	 }
	 
	 public function setPresent_Address($present_address)
	 {
		 $this->present_address = $present_address;
	 }

	  public function getCurrent_Job()
	 {
		 return $this->current_job;
	 }
	 
	 public function setCurrent_Job($current_job)
	 {
		 $this->current_job = $current_job;
	 }

	 public function getQualification()
	 {
		return $this->qualification; 
	 }
	 	 
	 public function setQualification($qualification)
	 {
		 $this->qualification = $qualification;
	 }

	  public function getAlumni_Status()
	 {
		return $this->alumni_status; 
	 }
	 	 
	 public function setAlumni_Status($alumni_status)
	 {
		 $this->alumni_status = $alumni_status;
	 }

	  public function getSubscription()
	 {
		return $this->subscription; 
	 }
	 	 
	 public function setSubscription($subscription)
	 {
		 $this->subscription = $subscription;
	 }

	 
	 public function getOrganisation_Id()
	 {
		return $this->organisation_id; 
	 }
	 	 
	 public function setOrganisation_Id($organisation_id)
	 {
		 $this->organisation_id = $organisation_id;
	 }

	 public function getAlumni_Programmes_Id()
	 {
		return $this->alumni_programmes_id; 
	 }
	 	 
	 public function setAlumni_Programmes_Id($alumni_programmes_id)
	 {
		 $this->alumni_programmes_id = $alumni_programmes_id;
	 }

}
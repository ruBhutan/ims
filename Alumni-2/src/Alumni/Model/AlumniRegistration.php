<?php

namespace Alumni\Model;

class ALumniRegistration
{
	protected $id;
	protected $first_name;
	protected $middle_name;
	protected $last_name;
	protected $cid;
	protected $student_id;
	protected $gender;
	protected $date_of_birth;
	protected $enrollment_year;
	protected $graduation_year;
	protected $contact_no;
	protected $email_address;
	protected $present_address;
	protected $current_job_title;
	protected $current_job_organisation;
    protected $qualification_level_id;
    protected $qualification_field;
    protected $alumni_status;
    protected $registration_date;
    protected $organisation_id;
    protected $alumni_programmes_id;
    protected $alumni_type;

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

	 public function getGender()
	 {
		return $this->gender; 
	 }
	 	 
	 public function setGender($gender)
	 {
		 $this->gender = $gender;
	 }

	 public function getStudent_Id()
	 {
		return $this->student_id; 
	 }
	 	 
	 public function setStudent_Id($student_id)
	 {
		 $this->student_id = $student_id;
	 }

	  public function getDate_Of_Birth()
	 {
		return $this->date_of_birth; 
	 }
	 	 
	 public function setDate_Of_Birth($date_of_birth)
	 {
		 $this->date_of_birth = $date_of_birth;
	 }

	 public function getEnrollment_Year()
	 {
		return $this->enrollment_year; 
	 }
	 	 
	 public function setEnrollment_Year($enrollment_year)
	 {
		 $this->enrollment_year = $enrollment_year;
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

	  public function getCurrent_Job_Title()
	 {
		 return $this->current_job_title;
	 }
	 
	 public function setCurrent_Job_Title($current_job_title)
	 {
		 $this->current_job_title = $current_job_title;
	 }

	 public function getCurrent_Job_Organisation()
	 {
		 return $this->current_job_organisation;
	 }
	 
	 public function setCurrent_Job_Organisation($current_job_organisation)
	 {
		 $this->current_job_organisation = $current_job_organisation;
	 }

	 public function getQualification_Level_Id()
	 {
		return $this->qualification_level_id; 
	 }
	 	 
	 public function setQualification_Level_Id($qualification_level_id)
	 {
		 $this->qualification_level_id = $qualification_level_id;
	 }

	 public function getQualification_Field()
	 {
		return $this->qualification_field; 
	 }
	 	 
	 public function setQualification_Field($qualification_field)
	 {
		 $this->qualification_field = $qualification_field;
	 }

	  public function getAlumni_Status()
	 {
		return $this->alumni_status; 
	 }
	 	 
	 public function setAlumni_Status($alumni_status)
	 {
		 $this->alumni_status = $alumni_status;
	 }

	 public function getAlumni_Programmes_Id()
	 {
		return $this->alumni_programmes_id; 
	 }

	 public function setAlumni_Programmes_Id($alumni_programmes_id)
	 {
		 $this->alumni_programmes_id = $alumni_programmes_id;
	 }

	 public function getRegistration_Date()
	 {
		return $this->registration_date; 
	 }
	 	 
	 public function setRegistration_Date($registration_date)
	 {
		 $this->registration_date = $registration_date;
	 }

	 public function getOrganisation_Id()
	 {
		return $this->organisation_id; 
	 }
	 	 
	 public function setOrganisation_Id($organisation_id)
	 {
		 $this->organisation_id = $organisation_id;
	 }

	 public function getAlumni_Type()
	 {
		return $this->alumni_type; 
	 }
	 	 
	 public function setAlumni_Type($alumni_type)
	 {
		 $this->alumni_type = $alumni_type;
	 }
	

}
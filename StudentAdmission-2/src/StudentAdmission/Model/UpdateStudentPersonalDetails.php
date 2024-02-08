<?php

namespace StudentAdmission\Model;

/*Model for student registration update done from College  */
class UpdateStudentPersonalDetails
{
	protected $id;
	protected $student_id;
	protected $date;
	protected $organisation_id;
	protected $programmes_id;
	protected $programme_name;
	protected $first_name;
	protected $middle_name;
	protected $last_name;
	protected $cid;
	protected $gender;
	protected $date_of_birth;
    protected $scholarship_type;
    protected $student_category_id;
    protected $enrollment_year;
    protected $contact_no;
    protected $email;

    public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getStudent_Id()
	 {
		return $this->student_id; 
	 }
	 	 
	 public function setStudent_Id($student_id)
	 {
		 $this->student_id = $student_id;
	 }
	 
	 public function getDate()
	 {
		 return $this->date;
	 }
	 
	 public function setDate($date)
	 {
		 $this->date = $date;
	 }
	 	 
	 public function getOrganisation_Id()
	 {
		return $this->organisation_id; 
	 }
	 	 
	 public function setOrganisation_Id($organisation_id)
	 {
		 $this->organisation_id = $organisation_id;
	 }
	 
	 public function getProgrammes_Id()
	 {
		return $this->programmes_id; 
	 }
	 	 
	 public function setProgrammes_Id($programmes_id)
	 {
		 $this->programmes_id = $programmes_id;
	 }

	 public function getProgramme_Name()
	 {
		return $this->programme_name; 
	 }
	 	 
	 public function setProgramme_Name($programme_name)
	 {
		 $this->programme_name = $programme_name;
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

	 public function getDate_Of_Birth()
	 {
		 return $this->date_of_birth;
	 }
	 
	 public function setDate_Of_Birth($date_of_birth)
	 {
		 $this->date_of_birth = $date_of_birth;
	 }

	 public function getScholarship_Type()
	 {
		 return $this->scholarship_type;
	 }
	 
	 public function setScholarship_Type($scholarship_type)
	 {
		 $this->scholarship_type = $scholarship_type;
	 }

	 public function getStudent_Category_Id()
	 {
		 return $this->student_category_id;
	 }
	 
	 public function setStudent_Category_Id($student_category_id)
	 {
		 $this->student_category_id = $student_category_id;
	 }

	 public function getEnrollment_Year()
	 {
		 return $this->enrollment_year;
	 }
	 
	 public function setEnrollment_Year($enrollment_year)
	 {
		 $this->enrollment_year = $enrollment_year;
	 }

	 public function getContact_No()
	 {
		 return $this->contact_no;
	 }
	 
	 public function setContact_No($contact_no)
	 {
		 $this->contact_no = $contact_no;
	 }

	 public function getEmail()
	 {
		 return $this->email;
	 }
	 
	 public function setEmail($email)
	 {
		 $this->email = $email;
	 }
}
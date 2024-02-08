<?php

namespace StudentAdmission\Model;

/*Model for student registration from OVC  */
class RegisterStudent
{
	protected $id;
	protected $registration_no;
	protected $organisation_id;
	protected $admission_year;
	protected $programme_id;
	protected $first_name;
	protected $middle_name;
	protected $last_name;
	protected $date_of_birth;
	protected $cid;
	protected $rank;
	protected $gender;
	protected $student_type_id;
	protected $moe_student_code;
	protected $twelve_indexnumber;
	protected $twelve_stream;
	protected $twelve_student_type;
	protected $twelve_school;
	protected $aggregate;
	protected $guardian_contact_no;
	protected $parents_contact_no;
	protected $guardian_name;
	protected $parent_name;
	protected $relationship_id;
	protected $submission_date;
	protected $student_reporting_status;
	

    public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getRegistration_No()
	 {
		return $this->registration_no; 
	 }
	 	 
	 public function setRegistration_No($registration_no)
	 {
		 $this->registration_no = $registration_no;
	 }

	 public function getAdmission_Year()
	 {
	 	return $this->admission_year;
	 }

	 public function setAdmission_Year($admission_year)
	 {
	 	$this->admission_year = $admission_year;
	 }
	 
	 public function getOrganisation_Id()
	 {
		return $this->organisation_id; 
	 }
	 	 
	 public function setOrganisation_Id($organisation_id)
	 {
		 $this->organisation_id = $organisation_id;
	 }
	 
	 public function getProgramme_Id()
	 {
		return $this->programme_id; 
	 }
	 	 
	 public function setProgramme_Id($programme_id)
	 {
		 $this->programme_id = $programme_id;
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

	  public function getRank()
	 {
		return $this->rank; 
	 }
	 	 
	 public function setRank($rank)
	 {
		 $this->rank = $rank;
	 }

	  public function getGender()
	 {
		return $this->gender; 
	 }
	 	 
	 public function setGender($gender)
	 {
		 $this->gender = $gender;
	 }

	 public function getAggregate()
	 {
		return $this->aggregate; 
	 }
	 	 
	 public function setAggregate($aggregate)
	 {
		 $this->aggregate = $aggregate;
	 }

	 public function getStudent_Type_Id()
	 {
		return $this->student_type_id; 
	 }
	 	 
	 public function setStudent_Type_Id($student_type_id)
	 {
		 $this->student_type_id = $student_type_id;
	 }

	 public function getMoe_Student_Code()
	 {
		return $this->moe_student_code; 
	 }
	 	 
	 public function setMoe_Student_Code($moe_student_code)
	 {
		 $this->moe_student_code = $moe_student_code;
	 }

	 public function getTwelve_Indexnumber()
	 {
		return $this->twelve_indexnumber; 
	 }
	 	 
	 public function setTwelve_Indexnumber($twelve_indexnumber)
	 {
		 $this->twelve_indexnumber = $twelve_indexnumber;
	 }

	 public function getTwelve_Stream()
	 {
		return $this->twelve_stream; 
	 }
	 	 
	 public function setTwelve_Stream($twelve_stream)
	 {
		 $this->twelve_stream = $twelve_stream;
	 }

	 public function getTwelve_Student_Type()
	 {
		return $this->twelve_student_type; 
	 }
	 	 
	 public function setTwelve_Student_Type($twelve_student_type)
	 {
		 $this->twelve_student_type = $twelve_student_type;
	 }

	 public function getTwelve_School()
	 {
		return $this->twelve_school; 
	 }
	 	 
	 public function setTwelve_School($twelve_school)
	 {
		 $this->twelve_school = $twelve_school;
	 }

	 public function getParent_Name()
	 {
		return $this->parent_name; 
	 }
	 	 
	 public function setParent_Name($parent_name)
	 {
		 $this->parent_name = $parent_name;
	 }
	 public function getParents_Contact_No()
	 {
		return $this->parents_contact_no; 
	 }
	 	 
	 public function setParents_Contact_No($parents_contact_no)
	 {
		 $this->parents_contact_no = $parents_contact_no;
	 }
	 
	 public function getRelationship_Id()
	 {
		return $this->relationship_id; 
	 }
	 	 
	 public function setRelationship_Id($relationship_id)
	 {
		 $this->relationship_id = $relationship_id;
	 }
	 public function getSubmission_Date()
	 {
		return $this->submission_date; 
	 }
	 	 
	 public function setSubmission_Date($submission_date)
	 {
		 $this->submission_date = $submission_date;
	 }

	 public function getStudent_Reporting_Status()
	 {
		return $this->student_reporting_status; 
	 }
	 	 
	 public function setStudent_Reporting_Status($student_reporting_status)
	 {
		 $this->student_reporting_status = $student_reporting_status;
	 }
}
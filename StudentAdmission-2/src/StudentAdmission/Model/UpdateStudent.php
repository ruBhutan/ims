<?php

namespace StudentAdmission\Model;

/*Model for student registration update done from College  */
class UpdateStudent
{
	protected $id;
	protected $registration_no;
	protected $joining_date;
	protected $admission_year;
	protected $submission_date;
	protected $enrollment_year;
	protected $academic_year;
	protected $organisation_id;
	//protected $organisation_name;
	protected $programme_id;
	protected $programmes_id;
	protected $programme_name;
	protected $first_name;
	protected $middle_name;
	protected $last_name;
	protected $cid;
	protected $gender;	
	protected $date_of_birth;
    protected $student_type_id;
    protected $scholarship_type;
    protected $student_type;
    protected $student_reporting_status;
    protected $student_registration_id;

    protected $student_id;
    protected $semester_id;

    protected $parent_name;
    protected $parents_contact_no;
    protected $relationship_id;
    protected $guardian_name;
    protected $guardian_contact_no;
    protected $guardian_relation;
    protected $relation;


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
	 
	 public function getJoining_Date()
	 {
		 return $this->joining_date;
	 }
	 
	 public function setJoining_Date($joining_date)
	 {
		 $this->joining_date = $joining_date;
	 }

	 public function getAdmission_Year()
	 {
		 return $this->admission_year;
	 }
	 
	 public function setAdmission_Year($admission_year)
	 {
		 $this->admission_year = $admission_year;
	 }

	 public function getSubmission_Date()
	 {
		 return $this->submission_date;
	 }
	 
	 public function setSubmission_Date($submission_date)
	 {
		 $this->submission_date = $submission_date;
	 }

	 public function getEnrollment_Year()
	 {
		 return $this->enrollment_year;
	 }
	 
	 public function setEnrollment_Year($enrollment_year)
	 {
		 $this->enrollment_year = $enrollment_year;
	 }

	 public function getAcademic_Year()
	 {
		 return $this->academic_year;
	 }
	 
	 public function setAcademic_Year($academic_year)
	 {
		 $this->academic_year = $academic_year;
	 }
	 	 
	 public function getOrganisation_Id()
	 {
		return $this->organisation_id; 
	 }
	 	 
	 public function setOrganisation_Id($organisation_id)
	 {
		$this->organisation_id = $organisation_id;
	 }

	 //public function getOrganisation_Name()
	 //{
		//return $this->organisation_name; 
	 //}
	 	 
	 //public function setOrganisation_Name($organisation_name)
	 //{
		// $this->organisation_name = $organisation_name;
	 //}
	 
	 public function getProgramme_Id()
	 {
		return $this->programme_id; 
	 }
	 	 
	 public function setProgramme_Id($programme_id)
	 {
		 $this->programme_id = $programme_id;
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
	 

	 public function getStudent_Type_Id()
	 {
		 return $this->student_type_id;
	 }
	 
	 public function setStudent_Type_Id($student_type_id)
	 {
		 $this->student_type_id = $student_type_id;
	 }

	 public function getStudent_Type()
	 {
		 return $this->student_type;
	 }
	 
	 public function setStudent_Type($student_type)
	 {
		 $this->student_type = $student_type;
	 }

	 public function getScholarship_Type()
	 {
		 return $this->scholarship_type;
	 }
	 
	 public function setScholarship_Type($scholarship_type)
	 {
		 $this->scholarship_type = $scholarship_type;
	 }

	
	 public function getStudent_Reporting_Status()
	 {
		return $this->student_reporting_status; 
	 }
	 public function setStudent_Reporting_Status($student_reporting_status)
	 {
		 $this->student_reporting_status = $student_reporting_status;
	 }

	 public function getStudent_Registration_Id()
	 {
		return $this->student_registration_id; 
	 }
	 	 
	 public function setStudent_Registration_Id($student_registration_id)
	 {
		 $this->student_registration_id = $student_registration_id;
	 }

	  public function getStudent_Id()
	 {
		return $this->student_id; 
	 }
	 	 
	 public function setStudent_Id($student_id)
	 {
		 $this->student_id = $student_id;
	 }

	 public function getSemester_Id()
	 {
		 return $this->semester_id;
	 }
	 
	 public function setSemester_Id($semester_id)
	 {
		 $this->semester_id = $semester_id;
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


	 public function getGuardian_Name()
	 {
		return $this->guardian_name; 
	 }
	 	 
	 public function setGuardian_Name($guardian_name)
	 {
		 $this->guardian_name = $guardian_name;
	 }

	 public function getGuardian_Contact_No()
	 {
		return $this->guardian_contact_no; 
	 }
	 	 
	 public function setGuardian_Contact_No($guardian_contact_no)
	 {
		 $this->guardian_contact_no = $guardian_contact_no;
	 }

	 public function getGuardian_Relation()
	 {
		return $this->guardian_relation; 
	 }
	 	 
	 public function setGuardian_Relation($guardian_relation)
	 {
		 $this->guardian_relation = $guardian_relation;
	 }

	 public function getRelation()
	 {
		return $this->relation; 
	 }
	 	 
	 public function setRelation($relation)
	 {
		 $this->relation = $relation;
	 }

}
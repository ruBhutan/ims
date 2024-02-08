<?php

namespace StudentAdmission\Model;

/* Model for Add Masters/Exchange Programme/New Student  like King or Queen Scholarship*/
class AddNewStudent
{
	protected $id;
	protected $admission_year;
	protected $academic_year;
	protected $organisation_id;
	protected $programme_id;
	protected $first_name;
	protected $middle_name;
	protected $last_name;
	protected $cid;
	protected $gender;
	protected $semester_id;
	protected $date_of_birth;
	protected $contact_no;
	protected $country_id;
	protected $dzongkhag;
	protected $gewog;
	protected $village;
    protected $student_type_id;
	protected $parent_name;
	protected $parents_contact_no;
	protected $relationship_id;
	protected $year_id;
	protected $registration_type;


    public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getAdmission_Year()
	 {
		 return $this->admission_year;
	 }
	 
	 public function setAdmission_Year($admission_year)
	 {
		 $this->admission_year = $admission_year;
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
	 
	 public function getGender()
	 {
		 return $this->gender;
	 }
	 
	 public function setGender($gender)
	 {
		 $this->gender = $gender;
	 }

	 public function getSemester_Id()
	 {
		 return $this->semester_id;
	 }
	 
	 public function setSemester_Id($semester_id)
	 {
		 $this->semester_id = $semester_id;
	 }

	 public function getDate_Of_Birth()
	 {
		 return $this->date_of_birth;
	 }
	 
	 public function setDate_Of_Birth($date_of_birth)
	 {
		 $this->date_of_birth = $date_of_birth;
	 }

	 public function getContact_No()
	 {
		 return $this->contact_no;
	 }
	 
	 public function setContact_No($contact_no)
	 {
		 $this->contact_no = $contact_no;
	 }

	 public function getCountry_Id()
	 {
		 return $this->country_id;
	 }
	 
	 public function setCountry_Id($country_id)
	 {
		 $this->country_id = $country_id;
	 }

	 public function getDzongkhag()
	 {
		 return $this->dzongkhag;
	 }
	 
	 public function setDzongkhag($dzongkhag)
	 {
		 $this->dzongkhag = $dzongkhag;
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
	 

	 public function getStudent_Type_Id()
	 {
		 return $this->student_type_id;
	 }
	 
	 public function setStudent_Type_Id($student_type_id)
	 {
		 $this->student_type_id = $student_type_id;
	 }

	public function getParent_Name()
	 {
		return $this->parent_name; 
	 }
	 	 
	public function setParent_Name($parent_name)
	 {
		 $this->parent_name = $parent_name;
	 }

	 public function getRelationship_Id()
	 {
		return $this->relationship_id; 
	 }
	 	 
	public function setRelationship_Id($relationship_id)
	 {
		 $this->relationship_id = $relationship_id;
	 }

	public function getParents_Contact_No()
	 {
		return $this->parents_contact_no; 
	}
	 	 
	public function setParents_Contact_No($parents_contact_no)
	 {
		 $this->parents_contact_no = $parents_contact_no;
	 }

	 public function getYear_Id()
	 {
		return $this->year_id; 
	}
	 	 
	public function setYear_Id($year_id)
	 {
		 $this->year_id = $year_id;
	 }

	 public function getRegistration_Type()
	 {
		return $this->registration_type; 
	}
	 	 
	public function setRegistration_Type($registration_type)
	 {
		 $this->registration_type = $registration_type;
	 }
}
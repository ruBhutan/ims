<?php

namespace StudentAdmission\Model;

/*Model for student registration update done from College  */
class UpdateStudentParentDetails
{
	protected $id;
	protected $student_id;
	protected $programme_name;
	protected $first_name;
	protected $middle_name;
	protected $last_name;
	protected $father_name;
	protected $father_cid;
	protected $father_nationality;
	protected $father_house_no;
	protected $father_thram_no;
    protected $father_village;
    protected $father_gewog;
    protected $father_dzongkhag;
    protected $father_occupation;
    protected $mother_name;
    protected $mother_cid;
    protected $mother_nationality;
    protected $mother_house_no;
    protected $mother_thram_no;
    protected $mother_village;
    protected $mother_gewog;
    protected $mother_dzongkhag;
    protected $mother_occupation;
    protected $parents_present_address;
    protected $parents_contact_no;

    protected $std_id;

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

	 public function getFather_Name()
	 {
	 	return $this->father_name;
	 }
	 	 
	 public function setFather_Name($father_name)
	 {
		 $this->father_name = $father_name;
	 }

	  public function getFather_Cid()
	 {
		return $this->father_cid; 
	 }
	 	 
	 public function setFather_Cid($father_cid)
	 {
		 $this->father_cid = $father_cid;
	 }

	 public function getFather_Nationality()
	 {
		return $this->father_nationality; 
	 }
	 	 
	 public function setFather_Nationality($father_nationality)
	 {
		 $this->father_nationality = $father_nationality;
	 }
	 
	 public function getFather_House_No()
	 {
		return $this->father_house_no; 
	 }
	 	 
	 public function setFather_House_No($father_house_no)
	 {
		 $this->father_house_no = $father_house_no;
	 }
	
	 public function getFather_Thram_No()
	 {
		return $this->father_thram_no; 
	 }
	 	 
	 public function setFather_Thram_No($father_thram_no)
	 {
		 $this->father_thram_no = $father_thram_no;
	 }
	 
	 public function getFather_Village()
	 {
		 return $this->father_village;
	 }
	 
	 public function setFather_Village($father_village)
	 {
		 $this->father_village = $father_village;
	 }

	 public function getFather_Gewog()
	 {
		 return $this->father_gewog;
	 }
	 
	 public function setFather_Gewog($father_gewog)
	 {
		 $this->father_gewog = $father_gewog;
	 }

	  public function getFather_Dzongkhag()
	 {
		 return $this->father_dzongkhag;
	 }
	 
	 public function setFather_Dzongkhag($father_dzongkhag)
	 {
		 $this->father_dzongkhag = $father_dzongkhag;
	 }

	  public function getFather_Occupation()
	 {
		return $this->father_occupation; 
	 }
	 	 
	 public function setFather_Occupation($father_occupation)
	 {
		 $this->father_occupation = $father_occupation;
	 }

	 public function getMother_Name()
	 {
		return $this->mother_name; 
	 }
	 	 
	 public function setMother_Name($mother_name)
	 {
		 $this->mother_name = $mother_name;
	 }

	  public function getMother_Cid()
	 {
		return $this->mother_cid; 
	 }
	 	 
	 public function setMother_Cid($mother_cid)
	 {
		 $this->mother_cid = $mother_cid;
	 }

	 	  public function getMother_Nationality()
	 {
		 return $this->mother_nationality;
	 }
	 
	 public function setMother_Nationality($mother_nationality)
	 {
		 $this->mother_nationality = $mother_nationality;
	 }

	  public function getMother_House_No()
	 {
		return $this->mother_house_no; 
	 }
	 	 
	 public function setmother_house_no($mother_house_no)
	 {
		 $this->mother_house_no = $mother_house_no;
	 }

	 public function getMother_Thram_No()
	 {
		return $this->mother_thram_no; 
	 }
	 	 
	 public function setMother_Thram_No($mother_thram_no)
	 {
		 $this->mother_thram_no = $mother_thram_no;
	 }

	  public function getMother_Village()
	 {
		return $this->mother_village; 
	 }
	 	 
	 public function setMother_Village($mother_village)
	 {
		 $this->mother_village = $mother_village;
	 }

	  public function getMother_Gewog()
	 {
		 return $this->mother_gewog;
	 }
	 
	 public function setMother_Gewog($mother_gewog)
	 {
		 $this->mother_gewog = $mother_gewog;
	 }

	 public function getMother_Dzongkhag()
	 {
		 return $this->mother_dzongkhag;
	 }
	 
	 public function setMother_Dzongkhag($mother_dzongkhag)
	 {
		 $this->mother_dzongkhag = $mother_dzongkhag;
	 }

	  public function getMother_Occupation()
	 {
		 return $this->mother_occupation;
	 }
	 
	 public function setMother_Occupation($mother_occupation)
	 {
		 $this->mother_occupation = $mother_occupation;
	 }

	  public function getParents_Present_Address()
	 {
		return $this->parents_present_address; 
	 }
	 	 
	 public function setParents_Present_Address($parents_present_address)
	 {
		 $this->parents_present_address = $parents_present_address;
	 }

	 public function getParents_Contact_No()
	 {
		return $this->parents_contact_no; 
	 }
	 	 
	 public function setParents_Contact_No($parents_contact_no)
	 {
		 $this->parents_contact_no = $parents_contact_no;
	 }


	 public function getStd_Id()
	 {
		return $this->std_id; 
	 }
	 	 
	 public function setStd_Id($std_id)
	 {
		 $this->std_id = $std_id;
	 }
}



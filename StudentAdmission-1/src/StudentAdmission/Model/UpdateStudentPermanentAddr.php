<?php

namespace StudentAdmission\Model;

/*Model for student registration update done from College  */
class UpdateStudentPermanentAddr
{
	protected $id;
	protected $student_id;
	protected $programme_name;
	protected $first_name;
	protected $middle_name;
	protected $last_name;
	protected $nationality;
    protected $student_type;
    protected $student_category;
    protected $village;
    protected $gewog;
    protected $dzongkhag;
    protected $thram_no;
    protected $house_no;
    protected $student_country_id;
    protected $student_nationality_id;

    protected $student_gender;

    protected $country;
 

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

	 public function getNationality()
	 {
		return $this->nationality; 
	 }
	 	 
	 public function setNationality($nationality)
	 {
		 $this->nationality = $nationality;
	 }

	public function getStudent_Type()
	 {
		 return $this->student_type;
	 }
	 
	 public function setStudent_Type($student_type)
	 {
		 $this->student_type = $student_type;
	 }

	 public function getStudent_Category()
	 {
		 return $this->student_category;
	 }
	 
	 public function setStudent_Category($student_category)
	 {
		 $this->student_category = $student_category;
	 }

	  public function getVillage()
	 {
		return $this->village; 
	 }
	 	 
	 public function setVillage($village)
	 {
		 $this->village = $village;
	 }

	  public function getGewog()
	 {
		return $this->gewog; 
	 }
	 	 
	 public function setGewog($gewog)
	 {
		 $this->gewog = $gewog;
	 }

	  public function getDzongkhag()
	 {
		return $this->dzongkhag; 
	 }
	 	 
	 public function setDzongkhag($dzongkhag)
	 {
		 $this->dzongkhag = $dzongkhag;
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

	 public function getStudent_Country_Id()
	 {
		return $this->student_country_id; 
	 }
	 	 
	public function setStudent_Country_Id($student_country_id)
	 {
		 $this->student_country_id = $student_country_id;
	 }


	 public function getStudent_Nationality_Id()
	 {
		return $this->student_nationality_id; 
	 }
	 	 
	public function setStudent_Nationality_Id($student_nationality_id)
	 {
		 $this->student_nationality_id = $student_nationality_id;
	 }

	 public function getStudent_Gender()
	 {
		return $this->student_gender; 
	 }
	 	 
	public function setStudent_Gender($student_gender)
	 {
		 $this->student_gender = $student_gender;
	 }
}

<?php

namespace StudentAdmission\Model;

/*Model for student registration update done from College  */
class UpdateReportedStudentDetails
{
	protected $id;
	protected $student_id;
	protected $registration_no;
	protected $date;
	protected $organisation_id;
	protected $programmes_id;
	protected $programme_name;
	protected $first_name;
	protected $middle_name;
	protected $last_name;
	protected $cid;
	protected $gender;
	protected $student_registration_id;
	protected $rank;
	protected $date_of_birth;
	protected $blood_group;
	protected $birth_place;
	protected $nationality;
    protected $mother_tongue;
    protected $student_type_id;
    protected $scholarship_type;
    protected $student_type;
    protected $student_category;
    protected $student_category_id;
    protected $village;
    protected $gewog;
    protected $dzongkhag;
    protected $thram_no;
    protected $house_no;
    protected $contact_no;
    protected $email;
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
	protected $guardian_name;
	protected $guardian_occupation;
	protected $guardian_relation;
	protected $guardian_address;
    protected $guardian_contact_no;
    protected $remarks;

    protected $student_country_id;
    protected $student_nationality_id;
    /*protected $previous_institution;
    protected $aggregate_marks_obtained;
    protected $from_date;
    protected $to_date;
    protected $previous_education; */
    // Guardian Details entered before
   /* protected $parent_name;
    protected $relationship;*/
    protected $stdpreviousschooldetails;
    protected $parents_contact_no;

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
	 public function getRegistration_No()
	 {
		return $this->registration_no; 
	 }
	 	 
	 public function setRegistration_No($registration_no)
	 {
		 $this->registration_no = $registration_no;
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

    public function getStudent_Registration_Id()
	 {
		 return $this->student_registration_id;
	 }
	 
	 public function setStudent_Registration_Id($student_registration_id)
	 {
		 $this->student_registration_id = $student_registration_id;
	 }

	 public function getRank()
	 {
		 return $this->rank;
	 }
	 
	 public function setRank($rank)
	 {
		 $this->rank = $rank;
	 }

	 public function getDate_Of_Birth()
	 {
		 return $this->date_of_birth;
	 }
	 
	 public function setDate_Of_Birth($date_of_birth)
	 {
		 $this->date_of_birth = $date_of_birth;
	 }

	  public function getBlood_Group()
	 {
		 return $this->blood_group;
	 }
	 
	 public function setBlood_Group($blood_group)
	 {
		 $this->blood_group = $blood_group;
	 }

	  public function getBirth_Place()
	 {
		return $this->birth_place; 
	 }
	 	 
	 public function setBirth_Place($birth_place)
	 {
		 $this->birth_place = $birth_place;
	 }

	 public function getNationality()
	 {
		return $this->nationality; 
	 }
	 	 
	 public function setNationality($nationality)
	 {
		 $this->nationality = $nationality;
	 }

	  public function getMother_Tongue()
	 {
		return $this->mother_tongue; 
	 }
	 	 
	 public function setMother_Tongue($mother_tongue)
	 {
		 $this->mother_tongue = $mother_tongue;
	 }

	 public function getStudent_Type_Id()
	 {
		 return $this->student_type_id;
	 }
	 
	 public function setStudent_Type_Id($student_type_id)
	 {
		 $this->student_type_id = $student_type_id;
	 }

	 public function getScholarship_Type()
	 {
		 return $this->scholarship_type;
	 }
	 
	 public function setScholarship_Type($scholarship_type)
	 {
		 $this->scholarship_type = $scholarship_type;
	 }

	public function getStudent_Type()
	 {
		 return $this->student_type;
	 }
	 
	 public function setStudent_Type($student_type)
	 {
		 $this->student_type = $student_type;
	 }

	 public function getStudent_Category_Id()
	 {
		 return $this->student_category_id;
	 }
	 
	 public function setStudent_Category_Id($student_category_id)
	 {
		 $this->student_category_id = $student_category_id;
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

	public function getGuardian_Name()
	 {
		return $this->guardian_name; 
	 }
	 	 
	public function setGuardian_Name($guardian_name)
	 {
		 $this->guardian_name = $guardian_name;
	 }

	public function getGuardian_Occupation()
	 {
		return $this->guardian_occupation; 
	 }
	 	 
	public function setGuardian_Occupation($guardian_occupation)
	 {
		 $this->guardian_occupation = $guardian_occupation;
	 }

	public function getGuardian_Relation()
	 {
		return $this->guardian_relation; 
	 }
	 	 
	public function setGuardian_Relation($guardian_relation)
	 {
		 $this->guardian_relation = $guardian_relation;
	 }

	public function getGuardian_Address()
	 {
		return $this->guardian_address; 
	 }
	 	 
	public function setGuardian_Address($guardian_address)
	 {
		 $this->guardian_address = $guardian_address;
	 }

	public function getGuardian_Contact_No()
	 {
		return $this->guardian_contact_no; 
	 }
	 	 
	public function setGuardian_Contact_No($guardian_contact_no)
	 {
		 $this->guardian_contact_no = $guardian_contact_no;
	 }

	public function getRemarks()
	 {
		return $this->remarks; 
	 }
	 	 
	public function setRemarks($remarks)
	 {
		 $this->remarks = $remarks;
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

	/*public function getPrevious_Institution()
	 {
		return $this->previous_institution; 
	 }
	 	 
	public function setPrevious_Institution($previous_institution)
	 {
		 $this->previous_institution = $previous_institution;
	 }

	public function getAggregate_Marks_Obtained()
	 {
		return $this->aggregate_marks_obtained; 
	 }
	 	 
	public function setAggregate_Marks_Obtained($aggregate_marks_obtained)
	 {
		 $this->aggregate_marks_obtained = $aggregate_marks_obtained;
	 }

	public function getFrom_Date()
	 {
		return $this->from_date; 
	 }
	 	 
	public function setFrom_Date($from_date)
	 {
		 $this->from_date = $from_date;
	 }
	public function getTo_Date()
	 {
		return $this->to_date; 
	 }
	public function setTo_Date($to_date)
	 {
		 $this->to_date = $to_date;
	 }
	 public function getPrevious_Education()
	 {
		return $this->previous_education; 
	 }
	public function setPrevious_Education($previous_education)
	 {
		 $this->previous_education = $previous_education;
	 }
 */

     // Guradian Contact details entered before
	/* public function getParent_Name()
	 {
		return $this->parent_name; 
	 }
	 	 
	 public function setParent_Name($parent_name)
	 {
		 $this->parent_name=$parent_name;
	 }

	 public function getRelationship()
	 {
		return $this->relationship; 
	 }
	 	 
	 public function setRelationship($relationship)
	 {
		 $this->relationship=$relationship;
	 }*/

	 public function getStdpreviousschooldetails()
	 {
	 	return $this->stdpreviousschooldetails;
	 }

	 public function setstdpreviousschooldetails($stdpreviousschooldetails)
	 {
	 	$this->stdpreviousschooldetails = $stdpreviousschooldetails;
	 	return $this;
	 }

	 public function getParents_Contact_No()
	 {
		return $this->parents_contact_no; 
	 }
	 	 
	 public function setParents_Contact_No($parents_contact_no)
	 {
		 $this->parents_contact_no = $parents_contact_no;
	 }

}


class StdPreviousSchoolDetails
{

    protected $previous_institution;
    protected $aggregate_marks_obtained;
    protected $from_date;
    protected $to_date;
    protected $previous_education;
    protected $student_id;

	public function getPrevious_Institution()
	 {
		return $this->previous_institution; 
	 }
	 	 
	public function setPrevious_Institution($previous_institution)
	 {
		 $this->previous_institution = $previous_institution;
	 }

	public function getAggregate_Marks_Obtained()
	 {
		return $this->aggregate_marks_obtained; 
	 }
	 	 
	public function setAggregate_Marks_Obtained($aggregate_marks_obtained)
	 {
		 $this->aggregate_marks_obtained = $aggregate_marks_obtained;
	 }

	public function getFrom_Date()
	 {
		return $this->from_date; 
	 }
	 	 
	public function setFrom_Date($from_date)
	 {
		 $this->from_date = $from_date;
	 }
	public function getTo_Date()
	 {
		return $this->to_date; 
	 }
	public function setTo_Date($to_date)
	 {
		 $this->to_date = $to_date;
	 }
	 public function getPrevious_Education()
	 {
		return $this->previous_education; 
	 }
	public function setPrevious_Education($previous_education)
	 {
		 $this->previous_education = $previous_education;
	 }

	 public function getStudent_Id()
	 {
		return $this->student_id; 
	 }
	public function setStudent_Id($student_id)
	 {
		 $this->student_id = $student_id;
	 }
}
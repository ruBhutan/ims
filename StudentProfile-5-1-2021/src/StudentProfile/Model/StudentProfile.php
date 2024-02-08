<?php

namespace StudentProfile\Model;

class StudentProfile
{
	protected $id;
	protected $student_id;
	protected $registration_no;
	protected $joining_date;
	protected $admission_year;
	protected $enrollment_year;
	protected $submission_date;
	protected $organisation_id;
	protected $programme_id;
	protected $programmes_id;
	protected $programme_name;
	protected $college_address;
	protected $first_name;
	protected $middle_name;
	protected $last_name;
	protected $date_of_birth;
	protected $cid;
	protected $gender;
	protected $house_no;
	protected $thram_no;
	protected $blood_group;
	protected $birth_place;
	protected $nationality;
    protected $mother_tongue;
    protected $student_type_id;
    protected $scholarship_type;
	protected $student_category_id;
    protected $student_type;
    protected $student_category;
    protected $village;
    protected $village_name;
    protected $gewog;
    protected $gewog_name;
    protected $dzongkhag;
    protected $dzongkhag_name;
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
    protected $parents_contact_no;
	protected $guardian_name;
	protected $relation_ship;
	protected $guardian_occupation;
	protected $guardian_relation;
	protected $guardian_present_address;
    protected $guardian_village;
    protected $guardian_gewog;
    protected $guardian_dzongkhag;
    protected $guardian_contact_no;
    protected $guardian_email_address;
    protected $previous_institution;
    protected $aggregate_marks_obtained;
    protected $from_date;
    protected $to_date;
    protected $previous_education;
    protected $student_number;
    protected $rank;
    protected $student_reporting_status;
	protected $aggregate;//class 12 result aggregate marks
//student type
    protected $description;
//Programme
    protected $programme_description;
//College
   // protected $college_code;

    protected $file_name;
    protected $upload_date;
	
	protected $name;
	protected $address;
	//protected $programme_name_id;
	protected $start_date;
	protected $end_date;
	protected $ab_approval_no;
	protected $ab_approval_date;
	protected $contact_no;
	protected $external_examinar_status;
	protected $external_examinar_remarks;

	protected $student_registration_id;

	protected $parent_name;
	protected $relationship;




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

	   public function getRank()
	 {
		return $this->rank; 
	 }
	 	 
	 public function setRank($rank)
	 {
		 $this->rank = $rank;
	 }

	  public function getAggregate()
	 {
		return $this->aggregate; 
	 }
	 	 
	 public function setAggregate($aggregate)
	 {
		 $this->aggregate = $aggregate;
	 }
	 
	 public function getJoining_Date()
	 {
		 return $this->joining_date;
	 }
	 
	 public function setJoining_Date($joining_date)
	 {
		 $this->joining_date = $joining_date;
	 }
	 
	 public function getAdmission_year()
	 {
	 	return $this->admission_year;
	 }

	 public function setAdmission_Year($admission_year)
	 {
	 	$this->admission_year = $admission_year;
	 }

	 public function getEnrollment_Year()
	 {
	 	return $this->enrollment_year;
	 }

	 public function setEnrollment_Year($enrollment_year)
	 {
	 	$this->enrollment_year = $enrollment_year;
	 }

	 public function getSubmission_Date()
	 {
		 return $this->submission_date;
	 }
	 
	 public function setSubmission_Date($submission_date)
	 {
		 $this->submission_date = $submission_date;
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

	 public function getProgrammes_Id()
	 {
		return $this->programmes_id; 
	 }
	 	 
	 public function setProgrammes_Id($programmes_id)
	 {
		 $this->programmes_id = $programmes_id;
	 }


	 public function getOrganisation_Name()
	 {
		return $this->organisation_name; 
	 }
	 	 
	 public function setOrganisation_Name($organisation_name)
	 {
		 $this->organisation_name = $organisation_name;
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
	 public function getDate_Of_Birth()
	 {
		return $this->date_of_birth; 
	 }
	 	 
	 public function setDate_Of_Birth($date_of_birth)
	 {
		 $this->date_of_birth = $date_of_birth;
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

     public function getStudent_Category_Id()
	 {
		 return $this->student_category_id;
	 }
	 
	 public function setStudent_Category_Id($student_category_id)
	 {
		 $this->student_category_id = $student_category_id;
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


	 public function getVillage_Name()
	 {
		return $this->village_name; 
	 }
	 	 
	 public function setVillage_Name($village_name)
	 {
		 $this->village_name = $village_name;
	 }

	 public function getGewog()
	 {
		return $this->gewog; 
	 }
	 	 
	 public function setGewog($gewog)
	 {
		 $this->gewog = $gewog;
	 }

	 public function getGewog_Name()
	 {
		return $this->gewog_name; 
	 }
	 	 
	 public function setGewog_Name($gewog_name)
	 {
		 $this->gewog_name = $gewog_name;
	 }

	  public function getDzongkhag()
	 {
		return $this->dzongkhag; 
	 }
	 	 
	 public function setDzongkhag($dzongkhag)
	 {
		 $this->dzongkhag = $dzongkhag;
	 }

	  public function getDzongkhag_Name()
	 {
		return $this->dzongkhag_name; 
	 }
	 	 
	 public function setDzongkhag_Name($dzongkhag_name)
	 {
		 $this->dzongkhag_name = $dzongkhag_name;
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

	public function getParents_Contact_No()
	 {
		return $this->parents_contact_no; 
	 }
	 	 
	 public function setParents_Contact_No($parents_contact_no)
	 {
		 $this->parents_contact_no = $parents_contact_no;
	 }

	public function getGuardian_Name()
	 {
		return $this->guardian_name; 
	 }
	 	 
	public function setGuardian_Name($guardian_name)
	 {
		 $this->guardian_name = $guardian_name;
	 }

	public function getRelation()
	 {
		 return $this->relation;
	 }
	 
	public function setRelation($relation)
	 {
		 $this->relation = $relation;
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

	public function getGuardian_Present_Address()
	 {
		return $this->guardian_present_address; 
	 }
	 	 
	public function setGuardian_Present_Address($guardian_present_address)
	 {
		 $this->guardian_present_address = $guardian_present_address;
	 }

	public function getGuardian_Village()
	 {
		return $this->guardian_village; 
	 }
	 	 
	 public function setGuardian_Village($guardian_village)
	 {
		 $this->guardian_village = $guardian_village;
	 }

	public function getGuardian_Gewog()
	 {
		return $this->guardian_gewog; 
	 }
	 	 
	public function setGuardian_Gewog($guardian_gewog)
	 {
		 $this->guardian_gewog = $guardian_gewog;
	 }

	public function getGuardian_Dzongkhag()
	 {
		 return $this->guardian_dzongkhag;
	 }
	 
	public function setGuardian_Dzongkhag($guardian_dzongkhag)
	 {
		 $this->guardian_dzongkhag = $guardian_dzongkhag;
	 }

	public function getGuardian_Contact_No()
	 {
		return $this->guardian_contact_no; 
	 }
	 	 
	public function setGuardian_Contact_No($guardian_contact_no)
	 {
		 $this->guardian_contact_no = $guardian_contact_no;
	 }

	public function getGuardian_Email_Address()
	 {
		return $this->guardian_email_address; 
	 }
	 	 
	public function setGuardian_Email_Address($guardian_email_address)
	 {
		 $this->guardian_email_address = $guardian_email_address;
	 }

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

	 public function getStudent_Number()
	 {
		return $this->student_number; 
	 }
	public function setStudent_Number($student_number)
	 {
		 $this->student_number = $student_number;
	 }
//student type
	  public function getDescription()
	 {
		 return $this->description;
	 }
	 
	 public function setDescription($description)
	 {
		 $this->description = $description;
	 }
//programme
	 public function getProgramme_Description()
	 {
		 return $this->programme_description;
	 }
	 
	 public function setProgramme_Description($programme_description)
	 {
		 $this->programme_description = $programme_description;
	 }
//College
	/* public function getCollege_Code()
	 {
		return $this->college_code; 
	 }
	 	 
	 public function setCollege_Code($college_code)
	 {
		 $this->college_code = $college_code;
	 }*/
	 
	 public function getCollege_Address()
	 {
		 return $this->college_address;
	 }
	 
	 public function setCollege_Address($college_address)
	 {
		 $this->college_address = $college_address;
	 }

	 public function getStudent_Reporting_Status()
	 {
		 return $this->student_reporting_status;
	 }
	 
	 public function setStudent_Reporting_Status($student_reporting_status)
	 {
		 $this->student_reporting_status = $student_reporting_status;
	 }

	 public function getFile_Name()
	 {
		return $this->file_name; 
	 }
	 	 
	 public function setFile_Name($file_name)
	 {
		 $this->file_name = $file_name;
	 }

	 public function getUpload_Date()
	 {
		return $this->upload_date; 
	 }
	 	 
	 public function setUpload_Date($upload_date)
	 {
		 $this->upload_date = $upload_date;
	 }
	 
	 public function getName()
	 {
		return $this->name; 
	 }
	 	 
	 public function setName($name)
	 {
		 $this->name = $name;
	 }

	 public function getAddress()
	 {
		return $this->address; 
	 }
	 	 
	 public function setAddress($address)
	 {
		 $this->address = $address;
	 }

	 
	  public function getStart_Date()
	 {
		return $this->start_date; 
	 }
	 	 
	 public function setStart_Date($start_date)
	 {
		 $this->start_date = $start_date;
	 }

	 public function getEnd_Date()
	 {
		return $this->end_date; 
	 }
	 	 
	 public function setEnd_Date($end_date)
	 {
		 $this->end_date = $end_date;
	 }

	 public function getAB_Approval_No()
	 {
		return $this->ab_approval_no; 
	 }
	 	 
	 public function setAB_Approval_No($ab_approval_no)
	 {
		 $this->ab_approval_no = $ab_approval_no;
	 }
	 public function getAB_Approval_Date()
	 {
		return $this->ab_approval_date; 
	 }
	 	 
	 public function setAB_Approval_Date($ab_approval_date)
	 {
		 $this->ab_approval_date = $ab_approval_date;
	 }

	 public function getContact_No()
	 {
		return $this->contact_no; 
	 }
	 	 
	 public function setContact_No($contact_no)
	 {
		 $this->contact_no = $contact_no;
	 }

	 public function getExternal_Examinar_Status()
	 {
		return $this->external_examinar_status; 
	 }
	 	 
	 public function setExternal_Examinar_Status($external_examinar_status)
	 {
		 $this->external_examinar_status = $external_examinar_status;
	 }

	 public function getExternal_Examinar_Remarks()
	 {
		return $this->external_examinar_remarks; 
	 }
	 	 
	 public function setExternal_Examinar_Remarks($external_examinar_remarks)
	 {
		 $this->external_examinar_remarks = $external_examinar_remarks;
	 }

	 public function getParent_Name()
	 {
		return $this->parent_name; 
	 }
	 	 
	 public function setParent_Name($parent_name)
	 {
		 $this->parent_name = $parent_name;
	 }

	 public function getRelationship()
	 {
		return $this->relationship; 
	 }
	 	 
	 public function setRelationship($relationship)
	 {
		 $this->relationship = $relationship;
	 }
}
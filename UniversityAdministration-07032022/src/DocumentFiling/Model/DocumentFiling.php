<?php

namespace DocumentFiling\Model;

/*Main Model for Student Admission Controller  */
class DocumentFiling
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
	protected $student_gender;
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
    protected $gewog;
    protected $dzongkhag;
    protected $email;
	protected $parent_cid;
	protected $parent_nationality;
    protected $parent_dzongkhag;
    protected $parent_occupation;
    protected $parent_address;
    protected $parents_contact_no;
    protected $parent_contact_no;
	protected $relation_type;
    protected $remarks;
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
    protected $year;
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
	protected $relationship_id;
	protected $relation;

	protected $semester;
	protected $academic_year;
	protected $semester_id;
	protected $section;
	protected $student_section_id;
	protected $student_semester_registration_type;
	protected $house_name;
	protected $student_house_id;

	protected $studentId;

	protected $applied_date;
	protected $updated_date;
	protected $previous_programme;
	protected $applied_programme;
	protected $status;
	protected $reason;
	protected $updated_by;

	protected $pprogramme_name;
	protected $aprogramme_name;

	protected $moe_student_code;
	protected $twelve_indexnumber;
	protected $twelve_stream;
	protected $twelve_student_type;
	protected $twelve_school;



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

	 public function getStudent_Gender()
	 {
		 return $this->student_gender;
	 }
	 
	 public function setStudent_Gender($student_gender)
	 {
		 $this->student_gender = $student_gender;
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
	 
	 	 
	 public function getEmail()
	 {
		return $this->email; 
	 }
	 	 
	 public function setEmail($email)
	 {
		 $this->email = $email;
	 }

	  public function getParent_Cid()
	 {
		return $this->parent_cid; 
	 }
	 	 
	 public function setParent_Cid($parent_cid)
	 {
		 $this->parent_cid = $parent_cid;
	 }

	 public function getParent_Nationality()
	 {
		return $this->parent_nationality; 
	 }
	 	 
	 public function setParent_Nationality($parent_nationality)
	 {
		 $this->parent_nationality = $parent_nationality;
	 }

	  public function getParent_Dzongkhag()
	 {
		 return $this->parent_dzongkhag;
	 }
	 
	 public function setParent_Dzongkhag($parent_dzongkhag)
	 {
		 $this->parent_dzongkhag = $parent_dzongkhag;
	 }

	  public function getParent_Occupation()
	 {
		return $this->parent_occupation; 
	 }
	 	 
	 public function setParent_Occupation($parent_occupation)
	 {
		 $this->parent_occupation = $parent_occupation;
	 }

	  public function getParent_Address()
	 {
		return $this->parent_address; 
	 }
	 	 
	 public function setParent_Address($parent_address)
	 {
		 $this->parent_address = $parent_address;
	 }

	public function getParents_Contact_No()
	 {
		return $this->parents_contact_no; 
	 }
	 	 
	 public function setParents_Contact_No($parents_contact_no)
	 {
		 $this->parents_contact_no = $parents_contact_no;
	 }

	 public function getParent_Contact_No()
	 {
		return $this->parent_contact_no; 
	 }
	 	 
	 public function setParent_Contact_No($parent_contact_no)
	 {
		 $this->parent_contact_no = $parent_contact_no;
	 }

	public function getRelation_Type()
	 {
		return $this->relation_type; 
	 }
	 	 
	public function setRelation_Type($relation_type)
	 {
		 $this->relation_type = $relation_type;
	 }

	public function getRemarks()
	 {
		return $this->remarks; 
	 }
	 	 
	public function setRemarks($remarks)
	 {
		 $this->remarks = $remarks;
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

	 public function getYear()
	 {
		return $this->year; 
	 }
	 	 
	 public function setYear($year)
	 {
		 $this->year = $year;
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

	 public function getRelationship_Id()
	 {
		return $this->relationship_id; 
	 }
	 	 
	 public function setRelationship_Id($relationship_id)
	 {
		 $this->relationship_id = $relationship_id;
	 }

	 public function getRelation()
	 {
		return $this->relation; 
	 }
	 	 
	 public function setRelation($relation)
	 {
		 $this->relation = $relation;
	 }


	 public function getSemester()
	 {
		return $this->semester; 
	 }
	 	 
	 public function setSemester($semester)
	 {
		 $this->semester = $semester;
	 }

	 public function getAcademic_Year()
	 {
		return $this->academic_year; 
	 }
	 	 
	 public function setAcademic_Year($academic_year)
	 {
		 $this->academic_year = $academic_year;
	 }

	 public function getSemester_Id()
	 {
		return $this->semester_id; 
	 }
	 	 
	 public function setSemester_Id($semester_id)
	 {
		 $this->semester_id = $semester_id;
	 }

	 public function getSection()
	 {
		return $this->section; 
	 }
	 	 
	 public function setSection($section)
	 {
		 $this->section = $section;
	 }

	 public function getStudent_Section_Id()
	 {
		return $this->student_section_id; 
	 }
	 	 
	 public function setStudent_Section_Id($student_section_id)
	 {
		 $this->student_section_id = $student_section_id;
	 }

	 public function getStudent_Semester_Registration_Type()
	 {
		return $this->student_semester_registration_type; 
	 }
	 	 
	 public function setStudent_Semester_Registration_Type($student_semester_registration_type)
	 {
		 $this->student_semester_registration_type = $student_semester_registration_type;
	 }

	 public function getHouse_Name()
	 {
		return $this->house_name; 
	 }
	 	 
	 public function setHouse_Name($house_name)
	 {
		 $this->house_name = $house_name;
	 }

	 public function getStudent_House_Id()
	 {
		return $this->student_house_id; 
	 }
	 	 
	 public function setStudent_House_Id($student_house_id)
	 {
		 $this->student_house_id = $student_house_id;
	 }

	 public function getStudentId()
	 {
		return $this->studentId; 
	 }
	 	 
	 public function setStudentId($studentId)
	 {
		 $this->studentId = $studentId;
	 }


	 public function getApplied_Date()
	 {
		 return $this->applied_date;
	 }
	 
	 public function setApplied_Date($applied_date)
	 {
		 $this->applied_date = $applied_date;
	 }


	 public function getUpdated_Date()
	 {
		 return $this->updated_date;
	 }
	 
	 public function setUpdated_Date($updated_date)
	 {
		 $this->updated_date = $updated_date;
	 }

	 public function getPrevious_Programme()
	 {
		return $this->previous_programme; 
	 }
	 	 
	 public function setPrevious_Programme($previous_programme)
	 {
		 $this->previous_programme = $previous_programme;
	 }

	 public function getApplied_Programme()
	 {
		return $this->applied_programme; 
	 }
	 	 
	 public function setApplied_Programme($applied_programme)
	 {
		 $this->applied_programme = $applied_programme;
	 }

	  public function getStatus()
	 {
		return $this->status; 
	 }
	 	 
	 public function setStatus($status)
	 {
		 $this->status = $status;
	 }

	 public function getReason()
	 {
		return $this->reason; 
	 }
	 	 
	 public function setReason($reason)
	 {
		 $this->reason = $reason;
	 }

	 public function getUpdated_By()
	 {
		return $this->updated_by; 
	 }
	 	 
	 public function setUpdated_By($updated_by)
	 {
		 $this->updated_by = $updated_by;
	 }


	 public function getPprogramme_Name()
	 {
		return $this->pprogramme_name; 
	 }
	 	 
	 public function setPprogramme_Name($pprogramme_name)
	 {
		 $this->pprogramme_name = $pprogramme_name;
	 }

	 public function getAprogramme_Name()
	 {
		return $this->aprogramme_name; 
	 }
	 	 
	 public function setAprogramme_Name($aprogramme_name)
	 {
		 $this->aprogramme_name = $aprogramme_name;
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
}
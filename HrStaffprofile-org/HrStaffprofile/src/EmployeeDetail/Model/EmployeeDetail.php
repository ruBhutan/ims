<?php

namespace EmployeeDetail\Model;

class EmployeeDetail
{
	protected $id;
	protected $emp_id;
	protected $first_name;
	protected $middle_name;
	protected $last_name;
	protected $cid;
	protected $nationality;
	protected $date_of_birth;
	protected $emp_house_no;
	protected $emp_thram_no;
	protected $emp_dzongkhag;
	protected $emp_gewog;
	protected $emp_village;
	protected $emp_type;
	protected $emp_category;
	protected $gender;
	protected $marital_status;
	protected $phone_no;
	protected $email;
	protected $blood_group;
	protected $religion;
    protected $country;
	protected $college_name;
	protected $college_location;
	protected $college_country;
	protected $field_study;
	protected $subject_studied;
	protected $completion_year;
	protected $result_obtained;
	protected $certificate_obtained;
	protected $departments_units_id;
	protected $departments_id;
	protected $organisation_id;

	protected $position_title_id;
	protected $position_level_id;
	protected $recruitment_date;
	protected $emp_resignation_id;	
	protected $profile_picture; 

	protected $evidence_file;

    // Relation 
	protected $relation_type;
	protected $name;
	protected $remarks;
	protected $occupation;
	protected $employee_details_id;

	//Working Experience
	protected $working_agency;
	protected $occupational_group;
	protected $position_category;
	protected $position_title;
	protected $position_level;
	protected $start_period;
	protected $end_period;
	protected $date_range;
	protected $working_agency_type;

	protected $office_order_no;
	protected $office_order_date;

	//Education
	protected $study_mode;
	protected $study_level;
	protected $start_date;
	protected $end_date;
	protected $funding;
	protected $marks_obtained;

	//Training Details
	protected $course_title;
	protected $institute_name;
	protected $institute_address;
	protected $from_date;
	protected $to_date;

	//Research Publication
	protected $publication_year;
	protected $publication_name;
	protected $research_type;
	protected $publisher;
	protected $publication_url;
	protected $publication_no;
	protected $author_level;

	//Responsibility
	protected $responsibility_category_id;
	protected $responsibility_name;

	//Contribution
	protected $contribution_category_id;
	protected $contribution_date;
	protected $contribution_type;

	//Award
	protected $award_category_id;
	protected $award_name;
	protected $award_date;
	protected $award_given_by;
	protected $award_reasons;

	//Community Service
	protected $community_service_category_id;
	protected $service_name;
	protected $service_date;
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getEmp_Id()
	 {
		return $this->emp_id; 
	 }
	 	 
	 public function setEmp_Id($emp_id)
	 {
		 $this->emp_id = $emp_id;
	 }
	 	 
	 public function getFirst_Name()
	 {
		return $this->first_name; 
	 }
	 	 
	 public function setFirst_Name($first_name)
	 {
		 $this->first_name=$first_name;
	 }
	 
	 public function getMiddle_Name()
	 {
		return $this->middle_name; 
	 }
	 	 
	 public function setMiddle_Name($middle_name)
	 {
		 $this->middle_name=$middle_name;
	 }
	 
	 public function getLast_Name()
	 {
		return $this->last_name; 
	 }
	 	 
	 public function setLast_Name($last_name)
	 {
		 $this->last_name=$last_name;
	 }
	 
	 public function getCid()
	 {
		return $this->cid; 
	 }
	 	 
	 public function setCid($cid)
	 {
		 $this->cid=$cid;
	 }
	 
	 public function getNationality()
	 {
		 return $this->nationality;
	 }
	 
	 public function setNationality($nationality)
	 {
		 $this->nationality = $nationality;
	 }
	 
	 public function getDate_Of_Birth()
	 {
		return $this->date_of_birth; 
	 }
	 	 
	 public function setDate_Of_Birth($date_of_birth)
	 {
		 $this->date_of_birth=$date_of_birth;
	 }
	 
	 public function getEmp_House_No()
	 {
		 return $this->emp_house_no;
	 }
	 
	 public function setEmp_House_No($emp_house_no)
	 {
		 $this->emp_house_no = $emp_house_no;
	 }
	 
	 public function getEmp_Thram_No()
	 {
		 return $this->emp_thram_no;
	 }
	 
	 public function setEmp_Thram_No($emp_thram_no)
	 {
		 $this->emp_thram_no = $emp_thram_no;
	 }

	 public function getEmp_Type()
	 {
		 return $this->emp_type;
	 }
	 
	 public function setEmp_Type($emp_type)
	 {
		 $this->emp_type = $emp_type;
	 }
         
         public function getEmp_Dzongkhag()
	 {
		 return $this->emp_dzongkhag;
	 }
	 
	 public function setEmp_Dzongkhag($emp_dzongkhag)
	 {
		 $this->emp_dzongkhag = $emp_dzongkhag;
	 }

	 public function getEmp_Gewog()
	 {
		 return $this->emp_gewog;
	 }
	 
	 public function setEmp_Gewog($emp_gewog)
	 {
		 $this->emp_gewog = $emp_gewog;
	 }
	 
	 public function getEmp_Village()
	 {
		 return $this->emp_village;
	 }
	 
	 public function setEmp_Village($emp_village)
	 {
		 $this->emp_village = $emp_village;
	 }
	 
	 public function getCountry()
	 {
		 return $this->country;
	 }
	 
	 public function setCountry($country)
	 {
		 $this->country = $country;
	 }
         
         public function getGender()
	 {
		 return $this->gender;
	 }
	 
	 public function setGender($gender)
	 {
		 $this->gender = $gender;
	 }
	 
	 public function getMarital_Status()
	 {
		 return $this->marital_status;
	 }
	 
	 public function setMarital_Status($marital_status)
	 {
		 $this->marital_status = $marital_status;
	 }
	 
	 public function getPhone_No()
	 {
		 return $this->phone_no;
	 }
	 
	 public function setPhone_No($phone_no)
	 {
		 $this->phone_no = $phone_no;
	 }
	 
	 public function getEmail()
	 {
		 return $this->email;
	 }
	 
	 public function setEmail($email)
	 {
		 $this->email = $email;
	 }
	 
	 public function getBlood_Group()
	 {
		 return $this->blood_group;
	 }
	 
	 public function setBlood_Group($blood_group)
	 {
		 $this->blood_group = $blood_group;
	 }
	 
	 public function getReligion()
	 {
		 return $this->religion;
	 }
	 
	 public function setReligion($religion)
	 {
		 $this->religion = $religion;
	 }
         
	 public function getCollege_Name()
	 {
		 return $this->college_name;
	 }
	 
	 public function setCollege_Name($college_name)
	 {
		 $this->college_name = $college_name;
	 }
	 
	 public function getCollege_Location()
	 {
		 return $this->college_location;
	 }
	 
	 public function setCollege_Location($college_location)
	 {
		 $this->college_location = $college_location;
	 }
	 
	 public function getCollege_Country()
	 {
		 return $this->college_country;
	 }
	 
	 public function setCollege_Country($college_country)
	 {
		 $this->college_country = $college_country;
	 }
	 
	 public function getField_Study()
	 {
		 return $this->field_study;
	 }
	 
	 public function setField_Study($field_study)
	 {
		 $this->field_study = $field_study;
	 }
	 
	 public function getSubject_Studied()
	 {
		 return $this->subject_studied;
	 }
	 
	 public function setSubject_Studied($subject_studied)
	 {
		 $this->subject_studied = $subject_studied;
	 }
	 
	 public function getCompletion_Year()
	 {
		 return $this->completion_year;
	 }
	 
	 public function setCompletion_Year($completion_year)
	 {
		 $this->completion_year = $completion_year;
	 }
 
	 public function getResult_Obtained()
	 {
		 return $this->result_obtained;
	 }
	 
	 public function setResult_Obtained($result_obtained)
	 {
		 $this->result_obtained = $result_obtained;
	 }
	 
	 public function getCertificate_Obtained()
	 {
		 return $this->certificate_obtained;
	 }
	 
	 public function setCertificate_Obtained($certificate_obtained)
	 {
		 $this->certificate_obtained = $certificate_obtained;
	 }
	 
	 public function getDepartments_Units_Id()
	 {
		 return $this->departments_units_id;
	 }
	 
	 public function setDepartments_Units_Id($departments_units_id)
	 {
		 $this->departments_units_id = $departments_units_id;
	 }
	 
	 public function getDepartments_Id()
	 {
		 return $this->departments_id;
	 }
	 
	 public function setDepartments_Id($departments_id)
	 {
		 $this->departments_id = $departments_id;
	 }
	 
	 public function getOrganisation_Id()
	 {
		 return $this->organisation_id;
	 }
	 
	 public function setOrganisation_Id($organisation_id)
	 {
		 $this->organisation_id = $organisation_id;
	 }


	 public function getPosition_Title_Id()
	 {
		 return $this->position_title_id;
	 }
	 
	 public function setPosition_Title_Id($position_title_id)
	 {
		 $this->position_title_id = $position_title_id;
	 }
	 
	 public function getPosition_Level_Id()
	 {
		 return $this->position_level_id;
	 }
	 
	 public function setPosition_Level_Id($position_level_id)
	 {
		 $this->position_level_id = $position_level_id;
	 }

	  public function getRecruitment_Date()
	 {
		 return $this->recruitment_date;
	 }
	 
	 public function setRecruitment_Date($recruitment_date)
	 {
		 $this->recruitment_date = $recruitment_date;
	 }

	 public function getEmp_Resignation_Id()
	 {
		 return $this->emp_resignation_id;
	 }
	 
	 public function setEmp_Resignation_Id($emp_resignation_id)
	 {
		 $this->emp_resignation_id = $emp_resignation_id;
	 }

	 public function getProfile_Picture()
	 {
		 return $this->profile_picture;
	 }
	 
	 public function setProfile_Picture($profile_picture)
	 {
		 $this->profile_picture = $profile_picture;
	 }

	 public function getEvidence_File()
	 {
		 return $this->evidence_file;
	 }
	 
	 public function setEvidence_File($evidence_file)
	 {
		 $this->evidence_file = $evidence_file;
	 }


	 public function getRelation_Type()
	 {
		 return $this->relation_type;
	 }
	 
	 public function setRelation_Type($relation_type)
	 {
		 $this->relation_type = $relation_type;
	 }
	 	 
	 public function getName()
	 {
		return $this->name; 
	 }
	 	 
	 public function setName($name)
	 {
		 $this->name=$name;
	 }
	 
	 public function getOccupation()
	 {
		 return $this->occupation;
	 }
	 
	 public function setOccupation($occupation)
	 {
		 $this->occupation = $occupation;
	 }

	 
	 public function getRemarks()
	 {
		 return $this->remarks;
	 }
	 
	 public function setRemarks($remarks)
	 {
		 $this->remarks = $remarks;
	 }
         	 
	 public function getEmployee_Details_Id()
	 {
		return $this->employee_details_id;
	 }
	 	
	 public function setEmployee_Details_Id($employee_details_id)
	 {
		$this->employee_details_id = $employee_details_id;
	 }


	 //Work Experience
	 public function getWorking_Agency()
	 {
		 return $this->working_agency;
	 }
	 
	 public function setWorking_Agency($working_agency)
	 {
		 $this->working_agency = $working_agency;
	 }
	 
	 public function getOccupational_Group()
	 {
		 return $this->occupational_group;
	 }
	 
	 public function setOccupational_Group($occupational_group)
	 {
		 $this->occupational_group = $occupational_group;
	 }
	 
	 public function getPosition_Level()
	 {
		 return $this->position_level;
	 }
	 
	 public function setPosition_Level($position_level)
	 {
		 $this->position_level = $position_level;
	 }
	 
	 public function getPosition_Title()
	 {
		 return $this->position_title;
	 }
	 
	 public function setPosition_Title($position_title)
	 {
		 $this->position_title = $position_title;
	 }
	 
	 public function getPosition_Category()
	 {
		 return $this->position_category;
	 }
	 
	 public function setPosition_Category($position_category)
	 {
		 $this->position_category = $position_category;
	 }
	 
	 public function getStart_Period()
	 {
		 return $this->start_period;
	 }
	 
	 public function setStart_Period($start_period)
	 {
		 $this->start_period = $start_period;
	 }
	 
	 public function getEnd_Period()
	 {
		 return $this->end_period;
	 }
	 
	 public function setEnd_Period($end_period)
	 {
		 $this->end_period = $end_period;
	 }

	 public function getDate_Range()
	{
		return $this->date_range;
	}
	
	public function setDate_Range($date_range)
	{
		$this->date_range = $date_range;
	}


	 public function getWorking_Agency_Type()
	 {
		return $this->working_agency_type;
	 }
	
	 public function setWorking_Agency_Type($working_agency_type)
	 {
		$this->working_agency_type = $working_agency_type;
	 }


	 public function getOffice_Order_No()
	 {
		return $this->office_order_no;
	 }
	
	 public function setOffice_Order_No($office_order_no)
	 {
		$this->office_order_no = $office_order_no;
	 }


	 public function getOffice_Order_Date()
	 {
		return $this->office_order_date;
	 }
	
	 public function setOffice_Order_Date($office_order_date)
	 {
		$this->office_order_date = $office_order_date;
	 }
	 
	 public function getStudy_Mode()
	 {
		 return $this->study_mode;
	 }
	 
	 public function setStudy_Mode($study_mode)
	 {
		 $this->study_mode = $study_mode;
	 }
	 
	 public function getStudy_Level()
	 {
		 return $this->study_level;
	 }
	 
	 public function setStudy_Level($study_level)
	 {
		 $this->study_level = $study_level;
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
	 
	 public function getFunding()
	 {
		 return $this->funding;
	 }
	 
	 public function setFunding($funding)
	 {
		 $this->funding = $funding;
	 }
	 
	 public function getMarks_Obtained()
	 {
		 return $this->marks_obtained;
	 }
	 
	 public function setMarks_Obtained($marks_obtained)
	 {
		 $this->marks_obtained = $marks_obtained;
	 }

	 public function getCourse_Title()
	{
		return $this->course_title;
	}
	
	public function setCourse_Title($course_title)
	{
		$this->course_title = $course_title;
	}
	
	public function getInstitute_Name()
	{
		return $this->institute_name;
	}
	
	public function setInstitute_Name($institute_name)
	{
		$this->institute_name = $institute_name;
	}
	
	public function getInstitute_Address()
	{
		return $this->institute_address;
	}
	
	public function setInstitute_Address($institute_address)
	{
		$this->institute_address = $institute_address;
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

	public function getPublication_Year()
	 {
		 return $this->publication_year;
	 }
	 
	 public function setPublication_Year($publication_year)
	 {
		 $this->publication_year = $publication_year;
	 }
	 
	 public function getPublication_Name()
	 {
		 return $this->publication_name;
	 }
	 
	 public function setPublication_Name($publication_name)
	 {
		 $this->publication_name = $publication_name;
	 }
	 
	 public function getResearch_Type()
	 {
		 return $this->research_type;
	 }
	 
	 public function setResearch_Type($research_type)
	 {
		 $this->research_type = $research_type;
	 }
	 
	 public function getPublisher()
	 {
		 return $this->publisher;
	 }
	 
	 public function setPublisher($publisher)
	 {
		 $this->publisher = $publisher;
	 }
	 
	 public function getPublication_Url()
	 {
		 return $this->publication_url;
	 }
	 
	 public function setPublication_Url($publication_url)
	 {
		 $this->publication_url = $publication_url;
	 }
	 
	 public function getPublication_No()
	 {
		 return $this->publication_no;
	 }
	 
	 public function setPublication_No($publication_no)
	 {
		 $this->publication_no = $publication_no;
	 }
	 
	 public function getAuthor_Level()
	 {
		 return $this->author_level;
	 }
	 
	 public function setAuthor_Level($author_level)
	 {
		 $this->author_level = $author_level;
	 }

	 public function getResponsibility_Category_Id()
	 {
		 return $this->responsibility_category_id;
	 }
	 
	 public function setResponsibility_Category_Id($responsibility_category_id)
	 {
		 $this->responsibility_category_id = $responsibility_category_id;
	 }
	 
	 public function getResponsibility_Name()
	 {
		 return $this->responsibility_name;
	 }
	 
	 public function setResponsibility_Name($responsibility_name)
	 {
		 $this->responsibility_name = $responsibility_name;
	 }

	 public function getContribution_Category_Id()
	 {
		 return $this->contribution_category_id;
	 }
	 
	 public function setContribution_Category_Id($contribution_category_id)
	 {
		 $this->contribution_category_id = $contribution_category_id;
	 }
	 
	 public function getContribution_Date()
	 {
		 return $this->contribution_date;
	 }
	 
	 public function setContribution_Date($contribution_date)
	 {
		 $this->contribution_date = $contribution_date;
	 }
	 
	 public function getContribution_Type()
	 {
		 return $this->contribution_type;
	 }
	 
	 public function setContribution_Type($contribution_type)
	 {
		 $this->contribution_type = $contribution_type;
	 }


	 public function getAward_Category_Id()
	{
		return $this->award_category_id;
	}
	
	public function setAward_Category_Id($award_category_id)
	{
		$this->award_category_id = $award_category_id;
	}
	
	public function getAward_Name()
	{
		return $this->award_name;
	}
	
	public function setAward_Name($award_name)
	{
		$this->award_name = $award_name;
	}
	
	public function getAward_Date()
	{
		return $this->award_date;
	}
	
	public function setAward_Date($award_date)
	{
		$this->award_date = $award_date;
	}
	
	public function getAward_Reasons()
	{
		return $this->award_reasons;
	}
	
	public function setAward_Reasons($award_reasons)
	{
		$this->award_reasons = $award_reasons;
	}
	
	public function getAward_Given_by()
	{
		return $this->award_given_by;
	}
	
	public function setAward_Given_By($award_given_by)
	{
		$this->award_given_by = $award_given_by;
	}


	public function getCommunity_Service_Category_Id()
	 {
		 return $this->community_service_category_id;
	 }
	 
	 public function setCommunity_Service_Category_Id($community_service_category_id)
	 {
		 $this->community_service_category_id = $community_service_category_id;
	 }
	 
	 public function getService_Name()
	 {
		 return $this->service_name;
	 }
	 
	 public function setService_Name($service_name)
	 {
		 $this->service_name = $service_name;
	 }
	 
	 public function getService_Date()
	 {
		 return $this->service_date;
	 }
	 
	 public function setService_Date($service_date)
	 {
		 $this->service_date = $service_date;
	 }

}
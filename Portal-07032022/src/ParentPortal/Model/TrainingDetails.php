<?php

namespace JobPortal\Model;

class TrainingDetails
{
	protected $id;
	protected $course_title;
	protected $institute_name;
	protected $institute_location;
	protected $institute_country;
	protected $field_study;
	protected $start_date;
	protected $end_date;
	protected $course_level;
	protected $job_applicant_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
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
	
	public function getInstitute_Location()
	{
		return $this->institute_location;
	}
	
	public function setInstitute_Location($institute_location)
	{
		$this->institute_location = $institute_location;
	}
	
	public function getInstitute_Country()
	{
		return $this->institute_country;
	}
	
	public function setInstitute_Country($institute_country)
	{
		$this->institute_country = $institute_country;
	}
	
	public function getField_Study()
	{
		return $this->field_study;
	}
	
	public function setField_Study($field_study)
	{
		$this->field_study = $field_study;
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
	
	public function getCourse_Level()
	{
		return $this->course_level;
	}
	
	public function setCourse_Level($course_level)
	{
		$this->course_level = $course_level;
	}
	
	public function getJob_Applicant_Id()
	{
		return $this->job_applicant_id;
	}
	
	public function setJob_Applicant_Id($job_applicant_id)
	{
		$this->job_applicant_id = $job_applicant_id;
	}

}
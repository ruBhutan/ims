<?php

namespace EmpTraining\Model;

class TrainingDetails
{
	protected $id;
	protected $order_no;
	protected $order_date;
	protected $hrd_type;
	protected $training_category;
	protected $training_type;
	protected $course_title;
	protected $institute_name;
	protected $institute_location;
	protected $institute_country;
	protected $field_study;
	protected $training_start_date;
	protected $training_end_date;
	protected $course_level;
	protected $source_of_funding;
	protected $proposing_agency;
	protected $professional_development_no;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getOrder_No()
	{
		return $this->order_no;
	}
	
	public function setOrder_No($order_no)
	{
		$this->order_no = $order_no;
	}
	
	public function getOrder_Date()
	{
		return $this->order_date;
	}
	
	public function setOrder_Date($order_date)
	{
		$this->order_date = $order_date;
	}
	
	public function getHrd_Type()
	{
		return $this->hrd_type;
	}
	
	public function setHrd_Type($hrd_type)
	{
		$this->hrd_type = $hrd_type;
	}
	
	public function getTraining_Category()
	{
		return $this->training_category;
	}
	
	public function setTraining_Category($training_category)
	{
		$this->training_category = $training_category;
	}
	
	public function getTraining_Type()
	{
		return $this->training_type;
	}
	
	public function setTraining_Type($training_type)
	{
		$this->training_type = $training_type;
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
	
	public function getTraining_Start_Date()
	{
		return $this->training_start_date;
	}
	
	public function setTraining_Start_Date($training_start_date)
	{
		$this->training_start_date = $training_start_date;
	}
	
	public function getTraining_End_Date()
	{
		return $this->training_end_date;
	}
	
	public function setTraining_End_Date($training_end_date)
	{
		$this->training_end_date = $training_end_date;
	}
	
	public function getCourse_Level()
	{
		return $this->course_level;
	}
	
	public function setCourse_Level($course_level)
	{
		$this->course_level = $course_level;
	}
	
	public function getSource_Of_Funding()
	{
		return $this->source_of_funding;
	}
	
	public function setSource_Of_Funding($source_of_funding)
	{
		$this->source_of_funding = $source_of_funding;
	}
	
	public function getProposing_Agency()
	{
		return $this->proposing_agency;
	}
	
	public function setProposing_Agency($proposing_agency)
	{
		$this->proposing_agency = $proposing_agency;
	}
	
	public function getProfessional_Development_No()
	{
		return $this->professional_development_no;
	}
	
	public function setProfessional_Development_No($professional_development_no)
	{
		$this->professional_development_no = $professional_development_no;
	}
}
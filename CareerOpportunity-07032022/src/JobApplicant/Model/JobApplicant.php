<?php

namespace JobApplicant\Model;

class JobApplicant
{
	protected $id;
	protected $working_agency;
	protected $employee_type;
	protected $position_title;
	protected $position_category;
	protected $position_level;
	protected $no_of_slots;
	protected $general_responsibilities;
	protected $specific_responsibilities;
	protected $education_qualification_experience;
	protected $knowledge_skills;
	protected $vacancy_type;
	protected $date_of_advertisement;
	protected $last_date_submission;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getWorking_Agency()
	{
		return $this->working_agency;
	}
	
	public function setWorking_Agency($working_agency)
	{
		$this->working_agency = $working_agency;
	}
	
	public function getEmployee_Type()
	{
		return $this->employee_type;
	}
	
	public function setEmployee_Type($employee_type)
	{
		$this->employee_type = $employee_type;
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
	
	public function getPosition_Level()
	{
		return $this->position_level;
	}
	
	public function setPosition_Level($position_level)
	{
		$this->position_level = $position_level;
	}
	
	public function getNo_Of_Slots()
	{
		return $this->no_of_slots;
	}
	
	public function setNo_Of_Slots($no_of_slots)
	{
		$this->no_of_slots = $no_of_slots;
	}
	
	public function getGeneral_Responsibilities()
	{
		return $this->general_responsibilities;
	}
	
	public function setGeneral_Responsibilities($general_responsibilities)
	{
		$this->general_responsibilities = $general_responsibilities;
	}
	
	public function getSpecific_Responsibilities()
	{
		return $this->specific_responsibilities;
	}
	
	public function setSpecific_Responsibilities($specific_responsibilities)
	{
		$this->specific_responsibilities = $specific_responsibilities;
	}
	
	public function getEducation_Qualification_Experience()
	{
		return $this->education_qualification_experience;
	}
	
	public function setEducation_Qualification_Experience($education_qualification_experience)
	{
		$this->education_qualification_experience = $education_qualification_experience;
	}
	
	public function getKnowledge_Skills()
	{
		return $this->knowledge_skills;
	}
	
	public function setKnowledge_Skills($knowledge_skills)
	{
		$this->knowledge_skills = $knowledge_skills;
	}
	
	public function getDate_Of_Advertisement()
	{
		return $this->date_of_advertisement;
	}
	
	public function setDate_Of_Advertisement($date_of_advertisement)
	{
		$this->date_of_advertisement = $date_of_advertisement;
	}
	
	public function getLast_Date_Submission()
	{
		return $this->last_date_submission;
	}
	
	public function setLast_Date_Submission($last_date_submission)
	{
		$this->last_date_submission = $last_date_submission;
	}
	
	public function getVacancy_Type()
	{
		return $this->vacancy_type;
	}
	
	public function setVacancy_Type($vacancy_type)
	{
		$this->vacancy_type = $vacancy_type;
	}
}
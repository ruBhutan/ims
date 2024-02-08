<?php

namespace Vacancy\Model;

class Vacancy
{
	protected $id;
	protected $working_agency;
	protected $employee_type;
	protected $area;
	protected $position_title;
	protected $additional_position_title;
	protected $position_category;
	protected $position_level;
	protected $additional_position_level;
	protected $no_of_slots;
	protected $main_purpose_of_the_position;
	protected $general_responsibilities;
	protected $specific_responsibilities;
	protected $minimum_study_level_id;
	protected $education_qualification;
	protected $experience;
	protected $knowledge_skills;
	protected $vacancy_type;
	protected $date_of_advertisement;
	protected $last_time_submission;
	protected $last_date_submission;
	protected $organisation_id;
	protected $last_updated;
	
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

	public function getArea()
	{
		return $this->area;
	}
	
	public function setArea($area)
	{
		$this->area = $area;
	}
	
	public function getPosition_Title()
	{
		return $this->position_title;
	}
	
	public function setPosition_Title($position_title)
	{
		$this->position_title = $position_title;
	}

	public function getAdditional_Position_Title()
	{
		return $this->additional_position_title;
	}
	
	public function setAdditional_Position_Title($additional_position_title)
	{
		$this->additional_position_title = $additional_position_title;
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

	public function getAdditional_Position_Level()
	{
		return $this->additional_position_level;
	}
	
	public function setAdditional_Position_Level($additional_position_level)
	{
		$this->additional_position_level = $additional_position_level;
	}
	
	public function getNo_Of_Slots()
	{
		return $this->no_of_slots;
	}
	
	public function setNo_Of_Slots($no_of_slots)
	{
		$this->no_of_slots = $no_of_slots;
	}

	public function getMain_Purpose_Of_The_Position()
	{
		return $this->main_purpose_of_the_position;
	}
	
	public function setMain_Purpose_Of_The_Position($main_purpose_of_the_position)
	{
		$this->main_purpose_of_the_position = $main_purpose_of_the_position;
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

	public function getMinimum_Study_Level_Id()
	{
		return $this->minimum_study_level_id;
	}
	
	public function setMinimum_Study_Level_Id($minimum_study_level_id)
	{
		$this->minimum_study_level_id = $minimum_study_level_id;
	}
	
	public function getEducation_Qualification()
	{
		return $this->education_qualification;
	}
	
	public function setEducation_Qualification($education_qualification)
	{
		$this->education_qualification = $education_qualification;
	}

	public function getExperience()
	{
		return $this->experience;
	}
	
	public function setExperience($experience)
	{
		$this->experience = $experience;
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

	public function getLast_Time_Submission()
	{
		return $this->last_time_submission;
	}
	
	public function setLast_Time_Submission($last_time_submission)
	{
		$this->last_time_submission = $last_time_submission;
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

	public function getOrganisation_Id()
	{
		return $this->organisation_id;
	}
	
	public function setOrganisation_Id($organisation_id)
	{
		$this->organisation_id = $organisation_id;
	}

	public function getLast_Updated()
	{
		return $this->last_updated;
	}
	
	public function setLast_Updated($last_updated)
	{
		$this->last_updated = $last_updated;
	}
}
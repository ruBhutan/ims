<?php

namespace StudentSuggestions\Model;

class StudentSuggestions
{
	protected $id;
	protected $suggestion_category;
	protected $subject;
	protected $suggestion;
	protected $organisation_id;
	protected $student_id;
	protected $suggested_date;

	protected $student_suggestion_category_id;
	protected $employee_details_id;
	protected $from_date;
	protected $to_date;
	protected $status;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getSuggestion_Category()
	{
		return $this->suggestion_category;
	}
	
	public function setSuggestion_Category($suggestion_category)
	{
		$this->suggestion_category = $suggestion_category;
	}
	
	public function getSubject()
	{
		return $this->subject;
	}
	
	public function setSubject($subject)
	{
		$this->subject = $subject;
	}
	
	public function getSuggestion()
	{
		return $this->suggestion;
	}
	
	public function setSuggestion($suggestion)
	{
		$this->suggestion = $suggestion;
	}

	public function getOrganisation_Id()
	{
		return $this->organisation_id;
	}
	
	public function setOrganisation_Id($organisation_id)
	{
		$this->organisation_id = $organisation_id;
	}

	public function getStudent_Id()
	{
		return $this->student_id;
	}
	
	public function setStudent_Id($student_id)
	{
		$this->student_id = $student_id;
	}

	public function getSuggested_Date()
	{
		return $this->suggested_date;
	}
	
	public function setSuggested_Date($suggested_date)
	{
		$this->suggested_date = $suggested_date;
	}


	//Suggestion Committe
	public function getStudent_Suggestion_Category_Id()
	{
		return $this->student_suggestion_category_id;
	}
	
	public function setStudent_Suggestion_Category_Id($student_suggestion_category_id)
	{
		$this->student_suggestion_category_id = $student_suggestion_category_id;
	}
	
	public function getEmployee_Details_Id()
	{
		return $this->employee_details_id;
	}
	
	public function setEmployee_Details_Id($employee_details_id)
	{
		$this->employee_details_id = $employee_details_id;
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

	public function getStatus()
	{
		return $this->status;
	}
	
	public function setStatus($status)
	{
		$this->status = $status;
	}
}
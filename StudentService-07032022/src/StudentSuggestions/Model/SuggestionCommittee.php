<?php

namespace StudentSuggestions\Model;

class SuggestionCommittee
{
	protected $id;
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
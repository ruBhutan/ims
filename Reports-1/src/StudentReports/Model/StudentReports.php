<?php

namespace StudentReports\Model;

class StudentReports
{
	protected $id;
	protected $award_name;
	protected $award_date;
	protected $award_given_by;
	protected $award_reasons;
	protected $job_applicant_id;
	 
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
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
	
	public function getJob_Applicant_Id()
	{
		return $this->job_applicant_id;
	}
	
	public function setJob_Applicant_Id($job_applicant_id)
	{
		$this->job_applicant_id = $job_applicant_id;
	}
	 
}
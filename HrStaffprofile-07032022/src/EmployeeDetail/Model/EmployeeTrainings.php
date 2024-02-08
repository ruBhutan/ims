<?php

namespace EmployeeDetail\Model;

class EmployeeTrainings
{
	protected $id;
	protected $course_title;
	protected $institute_name;
	protected $institute_address;
	protected $country;
	protected $from_date;
	protected $to_date;
	protected $funding;
	protected $employee_details_id;
	protected $evidence_file;
	
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
	
	public function getInstitute_Address()
	{
		return $this->institute_address;
	}
	
	public function setInstitute_Address($institute_address)
	{
		$this->institute_address = $institute_address;
	}
	
	public function getCountry()
	{
		return $this->country;
	}
	
	public function setCountry($country)
	{
		$this->country = $country;
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
	
	public function getFunding()
	{
		return $this->funding;
	}
	
	public function setFunding($funding)
	{
		$this->funding = $funding;
	}
	
	public function getEmployee_Details_Id()
	{
		return $this->employee_details_id;
	}
	
	public function setEmployee_Details_Id($employee_details_id)
	{
		$this->employee_details_id = $employee_details_id;
	}

	public function getEvidence_File()
	 {
		return $this->evidence_file;
	 }
	
	 public function setEvidence_File($evidence_file)
	 {
		$this->evidence_file = $evidence_file;
	 }

	 

}
<?php

namespace ResearchPublication\Model;

class ResearchPublication
{
	protected $id;
	protected $publication_title;
	protected $publication_type;
	protected $publication_status;
	protected $remarks;
	protected $publication_file;
	protected $submission_date;
	protected $employee_details_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getPublication_Title()
	{
		return $this->publication_title;
	}
	
	public function setPublication_Title($publication_title)
	{
		$this->publication_title = $publication_title;
	}
	
	public function getPublication_Type()
	{
		return $this->publication_type;
	}
	
	public function setPublication_Type($publication_type)
	{
		$this->publication_type = $publication_type;
	}
	
	public function getPublication_Status()
	{
		return $this->publication_status;
	}
	
	public function setPublication_Status($publication_status)
	{
		$this->publication_status = $publication_status;
	}
	
	public function getRemarks()
	{
		return $this->remarks;
	}
	
	public function setRemarks($remarks)
	{
		$this->remarks = $remarks;
	}
	
	public function getPublication_File()
	{
		return $this->publication_file;
	}
	
	public function setPublication_File($publication_file)
	{
		$this->publication_file = $publication_file;
	}
	
	public function getSubmission_Date()
	{
		return $this->submission_date;
	}
	
	public function setSubmission_Date($submission_date)
	{
		$this->submission_date = $submission_date;
	}
	
	public function getEmployee_Details_Id()
	{
		return $this->employee_details_id;
	}
	
	public function setEmployee_Details_Id($employee_details_id)
	{
		$this->employee_details_id = $employee_details_id;
	}
}
<?php

namespace JobPortal\Model;

class PublicationDetails
{
	protected $id;
	protected $publication_name;
	protected $research_type;
	protected $submission_date;
	protected $publication_file;
	protected $remarks;
	protected $job_applicant_id;
	 
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
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
	 
	 public function getSubmission_Date()
	 {
		 return $this->submission_date;
	 }
	 
	 public function setSubmission_Date($submission_date)
	 {
		 $this->submission_date = $submission_date;
	 }
	 
	 public function getPublication_File()
	 {
		 return $this->publication_file;
	 }
	 
	 public function setPublication_File($publication_file)
	 {
		 $this->publication_file = $publication_file;
	 }
	 
	 public function getRemarks()
	 {
		return $this->remarks;
	 }
	
	 public function setRemarks($remarks)
	 {
		$this->remarks = $remarks;
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
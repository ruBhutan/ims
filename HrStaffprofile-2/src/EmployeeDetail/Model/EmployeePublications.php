<?php

namespace EmployeeDetail\Model;

class EmployeePublications
{
	protected $id;
	protected $publication_year;
	protected $publication_name;
	protected $research_type;
	protected $publisher;
	protected $publication_url;
	protected $publication_no;
	protected $author_level;
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
	 
	 public function getPublication_Year()
	 {
		 return $this->publication_year;
	 }
	 
	 public function setPublication_Year($publication_year)
	 {
		 $this->publication_year = $publication_year;
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
	 
	 public function getPublisher()
	 {
		 return $this->publisher;
	 }
	 
	 public function setPublisher($publisher)
	 {
		 $this->publisher = $publisher;
	 }
	 
	 public function getPublication_Url()
	 {
		 return $this->publication_url;
	 }
	 
	 public function setPublication_Url($publication_url)
	 {
		 $this->publication_url = $publication_url;
	 }
	 
	 public function getPublication_No()
	 {
		 return $this->publication_no;
	 }
	 
	 public function setPublication_No($publication_no)
	 {
		 $this->publication_no = $publication_no;
	 }
	 
	 public function getAuthor_Level()
	 {
		 return $this->author_level;
	 }
	 
	 public function setAuthor_Level($author_level)
	 {
		 $this->author_level = $author_level;
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
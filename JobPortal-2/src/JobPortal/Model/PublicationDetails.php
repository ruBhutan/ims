<?php

namespace JobPortal\Model;

class PublicationDetails
{
	protected $id;
	protected $publication_year;
	protected $publication_name;
	protected $research_type;
	protected $publisher;
	protected $publication_url;
	protected $publication_no;
	protected $author_level;
	protected $job_applicant_id;
	protected $last_updated;
	 
	 	 
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
	 
	 public function getJob_Applicant_Id()
	 {
		return $this->job_applicant_id;
	 }
	
	 public function setJob_Applicant_Id($job_applicant_id)
	 {
		$this->job_applicant_id = $job_applicant_id;
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
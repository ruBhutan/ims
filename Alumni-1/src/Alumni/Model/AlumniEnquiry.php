<?php

namespace Alumni\Model;

class AlumniEnquiry
{
	protected $id;
	protected $topic;
	protected $email_contents;
	protected $alumni_id;
	protected $enquiry_status;
	protected $organisation_id;
	
		 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }

	public function getTopic()
	 {
		return $this->topic; 
	 }
	 	 
	 public function setTopic($topic)
	 {
		 $this->topic = $topic;
	 }

	 public function getAlumni_Id()
	 {
		 return $this->alumni_id;
	 }
	 
	 public function setAlumni_Id($alumni_id)
	 {
		 $this->alumni_id = $alumni_id;
	 }
	 	 
	 public function getEmail_Contents()
	 {
		return $this->email_contents; 
	 }
	 	 
	 public function setEmail_Contents($email_contents)
	 {
		 $this->email_contents = $email_contents;
	 }
	 
	 public function getEnquiry_Status()
	 {
		return $this->enquiry_status; 
	 }
	 	 
	 public function setEnquiry_Status($enquiry_status)
	 {
		 $this->enquiry_status = $enquiry_status;
	 }
	 
	 	
		public function getOrganisation_Id()
	 {
		return $this->organisation_id; 
	 }
	 	 
	 public function setOrganisation_Id($organisation_id)
	 {
		 $this->organisation_id = $organisation_id;
	 }	
}
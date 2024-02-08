<?php

namespace CollegeResearch\Model;

class CoResearchers
{
	protected $id;
	protected $name;
	protected $researcher_type;
	protected $qualification;
	protected $position_level;
	protected $appointment_date;
	protected $email;
	protected $contact_no;
	protected $researcher_category;
	protected $carg_grant_id;
	 	 
	 public function getId()
	 {
		return $this->id;
	 }
	 
	 public function setId($id)
	 {
		$this->id = $id;
	 }
	 
	 public function getName()
	 {
		 return $this->name;
	 }
	 
	 public function setName($name)
	 {
		 $this->name = $name;
	 }
	 
	 public function getResearcher_Type()
	 {
		 return $this->researcher_type;
	 }
	 
	 public function setResearcher_Type($researcher_type)
	 {
		 $this->researcher_type = $researcher_type;
	 }
	 
	 public function getQualification()
	 {
		 return $this->qualification;
	 }
	 
	 public function setQualification($qualification)
	 {
		 $this->qualification = $qualification;
	 }
	 
	 public function getPosition_Level()
	 {
		 return $this->position_level;
	 }
	 
	 public function setPosition_Level($position_level)
	 {
		 $this->position_level = $position_level;
	 }
	 
	 public function getAppointment_Date()
	 {
		 return $this->appointment_date;
	 }
	 
	 public function setAppointment_Date($appointment_date)
	 {
		 $this->appointment_date = $appointment_date;
	 }
	 
	 public function getEmail()
	 {
		 return $this->email;
	 }
	 
	 public function setEmail($email)
	 {
		 $this->email = $email;
	 }
	 
	 public function getContact_No()
	 {
		 return $this->contact_no;
	 }
	 
	 public function setContact_No($contact_no)
	 {
		 $this->contact_no = $contact_no;
	 }
	 
	 public function getResearcher_Category()
	 {
		 return $this->researcher_category;
	 }
	 
	 public function setResearcher_Category($researcher_category)
	 {
		 $this->researcher_category = $researcher_category;
	 }
	 
	 public function getCarg_Grant_Id()
	 {
		 return $this->carg_grant_id;
	 }
	 
	 public function setCarg_Grant_Id($carg_grant_id)
	 {
		 $this->carg_grant_id = $carg_grant_id;
	 }
	 
}
<?php

namespace UniversityResearch\Model;

class AurgResearchers
{
	protected $id;
	protected $coresearcher_name;
	protected $working_agency;
	protected $position_level;
	protected $sex;
	protected $email;
	protected $contact_no;
	protected $aurg_grant_id;
	 	 
	 public function getId()
	 {
		return $this->id;
	 }
	 
	 public function setId($id)
	 {
		$this->id = $id;
	 }
	 
	 public function getCoresearcher_Name()
	 {
		 return $this->coresearcher_name;
	 }
	 
	 public function setCoresearcher_Name($coresearcher_name)
	 {
		 $this->coresearcher_name = $coresearcher_name;
	 }
	 
	 public function getWorking_Agency()
	 {
		 return $this->working_agency;
	 }
	 
	 public function setWorking_Agency($working_agency)
	 {
		 $this->working_agency = $working_agency;
	 }
	 
	 public function getPosition_Level()
	 {
		 return $this->position_level;
	 }
	 
	 public function setPosition_Level($position_level)
	 {
		 $this->position_level = $position_level;
	 }
	 
	 public function getSex()
	 {
		 return $this->sex;
	 }
	 
	 public function setSex($sex)
	 {
		 $this->sex = $sex;
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
	 
	 public function getAurg_Grant_Id()
	 {
		 return $this->aurg_grant_id;
	 }
	 
	 public function setAurg_Grant_Id($aurg_grant_id)
	 {
		 $this->aurg_grant_id = $aurg_grant_id;
	 }
}
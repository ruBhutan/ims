<?php

namespace StudentAdmission\Model;

class StudentHouse
{
	protected $id;
	protected $house_name;
	protected $description;
	protected $organisation_id;
	protected $last_updated;
	
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getHouse_Name()
	 {
		return $this->house_name; 
	 }
	 	 
	 public function setHouse_Name($house_name)
	 {
		 $this->house_name = $house_name;
	 }
	 
	 public function getDescription()
	 {
		 return $this->description;
	 }
	 
	 public function setDescription($description)
	 {
		 $this->description = $description;
	 }
	 
	 public function getOrganisation_Id()
	 {
		 return $this->organisation_id;
	 }
	 
	 public function setOrganisation_Id($organisation_id)
	 {
		 $this->organisation_id = $organisation_id;
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
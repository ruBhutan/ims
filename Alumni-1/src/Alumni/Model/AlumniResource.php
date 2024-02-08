<?php

namespace Alumni\Model;

class AlumniResource
{
	protected $id;
	protected $description;
	protected $link;
	protected $alumni_remarks;
	protected $organisation_id;
	protected $created_date;
	
		 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }

	public function getDescription()
	 {
		return $this->description; 
	 }
	 	 
	 public function setDescription($description)
	 {
		 $this->description = $description;
	 }

	 public function getLink()
	 {
		 return $this->link;
	 }
	 
	 public function setLink($link)
	 {
		 $this->link = $link;
	 }
	 
	 public function getAlumni_Remarks()
	 {
		 return $this->alumni_remarks;
	 }
	 
	 public function setAlumni_Remarks($alumni_remarks)
	 {
		 $this->alumni_remarks = $alumni_remarks;
	 }
	 	 
	
		public function getOrganisation_Id()
	 {
		return $this->organisation_id; 
	 }
	 	 
	 public function setOrganisation_Id($organisation_id)
	 {
		 $this->organisation_id = $organisation_id;
	 }


	 	public function getCreated_Date()
	 {
		return $this->created_date; 
	 }
	 	 
	 public function setCreated_Date($created_date)
	 {
		 $this->created_date = $created_date;
	 }
	
}
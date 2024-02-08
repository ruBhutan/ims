<?php

namespace Timetable\Model;

class UploadTimetable
{
	protected $id;
	protected $file_name;
	protected $organisation_id;
		

        public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getFile_Name()
	 {
		return $this->file_name; 
	 }
	 	 
	 public function setFile_Name($file_name)
	 {
		 $this->file_name = $file_name;
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
<?php

namespace StudentAdmission\Model;

class UploadStudentLists
{
	protected $id;
	protected $file_name;
	protected $year;
	protected $upload_date;
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

	 public function getYear()
	 {
		return $this->year; 
	 }
	 	 
	 public function setYear($year)
	 {
		 $this->year = $year;
	 }
	 
	 public function getUpload_Date()
	 {
		return $this->upload_date; 
	 }
	 	 
	 public function setUpload_Date($upload_date)
	 {
		 $this->upload_date = $upload_date;
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
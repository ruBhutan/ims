<?php

namespace StudentAdmission\Model;

class StudentCategory
{
	protected $id;
	protected $student_category;
	protected $description;
	

    public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getStudent_Category()
	 {
		return $this->student_category; 
	 }
	 	 
	 public function setStudent_Category($student_category)
	 {
		 $this->student_category = $student_category;
	 }
	 
	 public function getDescription()
	 {
		 return $this->description;
	 }
	 
	 public function setDescription($description)
	 {
		 $this->description = $description;
	 }
	 	 
}
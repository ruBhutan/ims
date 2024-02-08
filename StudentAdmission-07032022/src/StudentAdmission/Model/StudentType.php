<?php

namespace StudentAdmission\Model;

class StudentType
{
	protected $id;
	protected $student_type;
	protected $description;
	
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getStudent_Type()
	 {
		return $this->student_type; 
	 }
	 	 
	 public function setStudent_Type($student_type)
	 {
		 $this->student_type = $student_type;
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
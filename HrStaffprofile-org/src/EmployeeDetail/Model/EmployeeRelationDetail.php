<?php

namespace EmployeeDetail\Model;

class EmployeeRelationDetail
{
	protected $id;
	protected $relation_type;
	protected $name;
	protected $nationality;
	protected $occupation;
	protected $gender;
	protected $remarks;
	protected $employee_details_id;
	 
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getRelation_Type()
	 {
		 return $this->relation_type;
	 }
	 
	 public function setRelation_Type($relation_type)
	 {
		 $this->relation_type = $relation_type;
	 }
	 	 
	 public function getName()
	 {
		return $this->name; 
	 }
	 	 
	 public function setName($name)
	 {
		 $this->name=$name;
	 }
	 	 
	 public function getNationality()
	 {
		 return $this->nationality;
	 }
	 
	 public function setNationality($nationality)
	 {
		 $this->nationality = $nationality;
	 }
	 
	 public function getOccupation()
	 {
		 return $this->occupation;
	 }
	 
	 public function setOccupation($occupation)
	 {
		 $this->occupation = $occupation;
	 }
	 
	 public function getGender()
	 {
		 return $this->gender;
	 }
	 
	 public function setGender($gender)
	 {
		 $this->gender = $gender;
	 }
	 
	 public function getRemarks()
	 {
		 return $this->remarks;
	 }
	 
	 public function setRemarks($remarks)
	 {
		 $this->remarks = $remarks;
	 }
         	 
	 public function getEmployee_Details_Id()
	 {
		return $this->employee_details_id;
	 }
	 	
	 public function setEmployee_Details_Id($employee_details_id)
	 {
		$this->employee_details_id = $employee_details_id;
	 }

}
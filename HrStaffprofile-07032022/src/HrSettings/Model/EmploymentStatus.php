<?php

namespace HrSettings\Model;

class EmploymentStatus
{
	protected $id;
	protected $employment_status;
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getEmployment_Status()
	 {
		 return $this->employment_status;
	 }
	 
	 public function setEmployment_Status($employment_status)
	 {
		 $this->employment_status = $employment_status;
	 }

}
<?php

namespace EmployeeDetail\Model;

class EmployeeProfilePicture
{
	protected $id;
	protected $profile_picture;
	protected $employee_details_id;
	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getProfile_Picture()
	 {
		 return $this->profile_picture;
	 }
	 
	 public function setProfile_Picture($profile_picture)
	 {
		 $this->profile_picture = $profile_picture;
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
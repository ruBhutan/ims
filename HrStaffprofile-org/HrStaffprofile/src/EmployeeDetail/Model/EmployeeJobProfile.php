<?php

namespace EmployeeDetail\Model;

class EmployeeJobProfile
{
	protected $id;
	protected $author;
	protected $employee_details;
	protected $emp_type_id;
	protected $organisation_id;
	protected $departments_id;
	protected $departments_units_id;
	protected $major_occupational_group_id;
	protected $emp_category_id;
	protected $position_title_id;
	protected $position_level_id;
	protected $increment_type_id;
	protected $pay_scale;
	protected $status;
	protected $reason;
    protected $created;
    protected $modified;
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }

     public function getAuthor()
	 {
		 return $this->author;
	 }
	 
	 public function setAuthor($author)
	 {
		 $this->author = $author;
	 }

     public function getEmployee_Details()
	 {
		 return $this->employee_details;
	 }
	 
	 public function setEmployee_Details($employee_details)
	 {
		 $this->employee_details = $employee_details;
	 }

     public function getEmp_Type_Id()
	 {
		 return $this->emp_type_id;
	 }
	 
	 public function setEmp_Type_Id($emp_type_id)
	 {
		 $this->emp_type_id = $emp_type_id;
	 }


     public function getOrganisation_Id()
	 {
		 return $this->organisation_id;
	 }
	 
	 public function setOrganisation_Id($organisation_id)
	 {
		 $this->organisation_id = $organisation_id;
	 }

     public function getDepartments_Id()
	 {
		 return $this->departments_id;
	 }
	 
	 public function setDepartments_Id($departments_id)
	 {
		 $this->departments_id = $departments_id;
	 }

     public function getDepartments_Units_Id()
	 {
		 return $this->departments_units_id;
	 }
	 
	 public function setDepartments_Units_Id($departments_units_id)
	 {
		 $this->departments_units_id = $departments_units_id;
	 }

     public function getMajor_Occupational_Group_Id()
	 {
		 return $this->major_occupational_group_id;
	 }
	 
	 public function setMajor_Occupational_Group_Id($major_occupational_group_id)
	 {
		 $this->major_occupational_group_id = $major_occupational_group_id;
	 }

     public function getEmp_Category_Id()
	 {
		 return $this->emp_category_id;
	 }
	 
	 public function setEmp_Category_Id($emp_category_id)
	 {
		 $this->emp_category_id = $emp_category_id;
	 }

     public function getPosition_Title_Id()
	 {
		 return $this->position_title_id;
	 }
	 
	 public function setPosition_Title_Id($position_title_id)
	 {
		 $this->position_title_id = $position_title_id;
	 }

     public function getPosition_Level_Id()
	 {
		 return $this->position_level_id;
	 }
	 
	 public function setPosition_Level_Id($position_level_id)
	 {
		 $this->position_level_id = $position_level_id;
	 }

     public function getIncrement_Type_Id()
	 {
		 return $this->increment_type_id;
	 }
	 
	 public function setIncrement_Type_Id($increment_type_id)
	 {
		 $this->increment_type_id = $increment_type_id;
	 }

     public function getPay_Scale()
	 {
		 return $this->pay_scale;
	 }
	 
	 public function setPay_Scale($pay_scale)
	 {
		 $this->pay_scale = $pay_scale;
	 }

     public function getStatus()
	 {
		 return $this->status;
	 }
	 
	 public function setStatus($status)
	 {
		 $this->status = $status;
	 }

     public function getReason()
	 {
		 return $this->reason;
	 }
	 
	 public function setReason($reason)
	 {
		 $this->reason = $reason;
	 }

     public function getCreated()
	 {
		 return $this->created;
	 }
	 
	 public function setCreated($created)
	 {
		 $this->created = $created;
	 }


     public function getModified()
	 {
		 return $this->modified;
	 }
	 
	 public function setModified($modified)
	 {
		 $this->modified = $modified;
	 }


}
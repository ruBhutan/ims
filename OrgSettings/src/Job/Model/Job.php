<?php

namespace Job\Model;

class Job
{
	protected $id;
	protected $position_title;
	protected $description;
	protected $notes;
	protected $occupation_subgroup;
	protected $occupational_subgroup_id;
	protected $major_occupational_group_id;
	protected $position_category_id;
	protected $category;	
	protected $position_level;
	protected $years_in_service;
	protected $teaching_allowance;
	protected $rent_allowance;
	protected $minimum_pay_scale;
	protected $increment;
	protected $maximum_pay_scale;
        protected $status;
		 
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getPosition_Title()
	 {
		return $this->position_title; 
	 }
	 	 
	 public function setPosition_Title($position_title)
	 {
		 $this->position_title = $position_title;
	 }
	 
	 public function getDescription()
	 {
		 return $this->description;
	 }
	 
	 public function setDescription($description)
	 {
		 $this->description = $description;
	 }
	 	 
	 public function getNotes()
	 {
		return $this->notes; 
	 }
	 	 
	 public function setNotes($notes)
	 {
		 $this->notes = $notes;
	 }
	 
	 public function getOccupational_Subgroup()
	 {
		return $this->occupational_subgroup; 
	 }
	 	 
	 public function setOccupational_Subgroup($occupational_subgroup)
	 {
		 $this->occupational_subgroup = $occupational_subgroup;
	 }
	 
	 public function getOccupational_Subgroup_Id()
	 {
		return $this->occupational_subgroup_id; 
	 }
	 	 
	 public function setOccupational_Subgroup_Id($occupational_subgroup_id)
	 {
		 $this->occupational_subgroup_id = $occupational_subgroup_id;
	 }
	 
	 public function getPosition_Level()
	 {
		 return $this->position_level;
	 }
	 
	 public function setPosition_Level($position_level)
	 {
		 $this->position_level = $position_level;
	 }
	 
	 public function getCategory()
	 {
		 return $this->category;
	 }
	 
	 public function setCategory($category)
	 {
		 $this->category = $category;
	 }
	 
	 public function getMajor_Occupational_Group_Id()
	 {
		 return $this->major_occupational_group_id;
	 }
	 
	 public function setMajor_Occupational_Group_Id($major_occupational_group_id)
	 {
		 $this->major_occupational_group_id = $major_occupational_group_id;
	 }
	 
	 public function getPosition_Category_Id()
	 {
		 return $this->position_category_id;
	 }
	 
	 public function setPosition_Category_Id($position_category_id)
	 {
		 $this->position_category_id = $position_category_id;
	 }
	 
	 public function getYears_In_Service()
	 {
		 return $this->years_in_service;
	 }
	 
	 public function setYears_In_Service($years_in_service)
	 {
		 $this->years_in_service = $years_in_service;
	 }
	 
	 public function getTeaching_Allowance()
	 {
		 return $this->teaching_allowance;
	 }
	 
	 public function setTeaching_Allowance($teaching_allowance)
	 {
		 $this->teaching_allowance = $teaching_allowance;
	 }
	 
	 public function getRent_Allowance()
	 {
		 return $this->rent_allowance;
	 }
	 
	 public function setRent_Allowance($rent_allowance)
	 {
		 $this->rent_allowance = $rent_allowance;
	 }
	 
	 public function getMinimum_Pay_Scale()
	 {
		 return $this->minimum_pay_scale;
	 }
	 
	 public function setMinimum_Pay_Scale($minimum_pay_scale)
	 {
		 $this->minimum_pay_scale = $minimum_pay_scale;
	 }
	 
	 public function getIncrement()
	 {
		 return $this->increment;
	 }
	 
	 public function setIncrement($increment)
	 {
		$this->increment = $increment; 
	 }
	 
	 public function getMaximum_Pay_Scale()
	 {
		 return $this->maximum_pay_scale;
	 }
	 
	 public function setMaximum_Pay_Scale($maximum_pay_scale)
	 {
		 $this->maximum_pay_scale = $maximum_pay_scale;
	 }
         
         public function getStatus()
         {
                return $this->status;
         }
         
         public function setStatus($status)
         {
                $this->status = $status;
         }
	 
}
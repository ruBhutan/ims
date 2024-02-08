<?php

namespace HrSettings\Model;

class TeachingAllowance
{
	protected $id;
	protected $teaching_allowance;
	protected $years_in_service;
	protected $position_level;
	
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getTeaching_Allowance()
	 {
		 return $this->teaching_allowance;
	 }
	 
	 public function setTeaching_Allowance($teaching_allowance)
	 {
		 $this->teaching_allowance = $teaching_allowance;
	 }
	 
	 public function getYears_In_Service()
	 {
		 return $this->years_in_service;
	 }
	 
	 public function setYears_In_Service($years_in_service)
	 {
		 $this->years_in_service = $years_in_service;
	 }
	 
	 public function getPosition_Level()
	 {
		 return $this->position_level;
	 }
	 
	 public function setPosition_Level($position_level)
	 {
		 $this->position_level = $position_level;
	 }

}
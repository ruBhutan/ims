<?php

namespace EmployeeDetail\Model;

class EmployeeLevel
{
	protected $id;
	protected $date;
	protected $position_level_id;
	protected $employee_details_id;
	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getDate()
	 {
		 return $this->date;
	 }
	 
	 public function setDate($date)
	 {
		 $this->date = $date;
	 }
	 
	 public function getPosition_Level_Id()
	 {
		 return $this->position_level_id;
	 }
	 
	 public function setPosition_Level_Id($position_level_id)
	 {
		 $this->position_level_id = $position_level_id;
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
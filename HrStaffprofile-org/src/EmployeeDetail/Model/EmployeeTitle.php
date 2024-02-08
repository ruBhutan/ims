<?php

namespace EmployeeDetail\Model;

class EmployeeTitle
{
	protected $id;
	protected $date;
	protected $position_title_id;
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
	 
	 public function getPosition_Title_Id()
	 {
		 return $this->position_title_id;
	 }
	 
	 public function setPosition_Title_Id($position_title_id)
	 {
		 $this->position_title_id = $position_title_id;
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
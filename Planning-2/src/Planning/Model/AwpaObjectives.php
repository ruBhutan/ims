<?php

namespace Planning\Model;

class AwpaObjectives
{
	protected $id;
	protected $financial_year;
	protected $objectives;
	protected $objectives_remarks;
	protected $activity_name;
	protected $awpa_remarks;
	protected $rub_objectives_id;
	protected $employee_details_id;
	protected $awpa_objectives_id;
	
	 public function getId()
	 {
		return $this->id;
	 }
	 
	 public function setId($id)
	 {
		$this->id = $id;
	 }

	 public function getFinancial_Year()
	 {
		 return $this->financial_year;
	 }
	 
	 public function setFinancial_Year($financial_year)
	 {
		 $this->financial_year = $financial_year;
	 }
	 
	 public function getObjectives()
	 {
		return $this->objectives; 
	 }
	 
	 public function setObjectives($objectives)
	 {
		$this->objectives = $objectives; 
	 }
	 
	 public function getObjectives_Remarks()
	 {
		 return $this->objectives_remarks;
	 }
	 
	 public function setObjectives_Remarks($objectives_remarks)
	 {
		$this->objectives_remarks = $objectives_remarks; 
	 }
	 
	 public function getActivity_Name()
	 {
		return $this->activity_name; 
	 }
	 	 
	 public function setActivity_Name($activity_name)
	 {
		$this->activity_name = $activity_name;
	 }
	 
	 public function getAwpa_Remarks()
	 {
		return $this->awpa_remarks; 
	 }
	 
	 public function setAwpa_Remarks($awpa_remarks)
	 {
		$this->awpa_remarks = $awpa_remarks; 
	 }
	 
	 public function getRub_Objectives_Id()
	 {
		 return $this->rub_objectives_id;
	 }
	 
	 public function setRub_Objectives_Id($rub_objectives_id)
	 {
		 $this->rub_objectives_id = $rub_objectives_id;
	 }
	 	 
	 public function getAwpa_Objectives_Id()
	 {
		return $this->awpa_objectives_id; 
	 }
	 	 
	 public function setAwpa_Objectives_Id($awpa_objectives_id)
	 {
		$this->awpa_objectives_id = $awpa_objectives_id;
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

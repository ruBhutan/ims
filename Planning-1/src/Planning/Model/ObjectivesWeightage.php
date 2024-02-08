<?php

namespace Planning\Model;

class ObjectivesWeightage
{
	protected $id;
	protected $rub_objectives_id;
	protected $five_year_plan_id;
	protected $weightage;
	protected $organisation_id;
	protected $departments_id;
	protected $financial_year;
 	 
	 public function getId()
	 {
		return $this->id;
	 }
	 
	 public function setId($id)
	 {
		$this->id = $id;
	 }

	 public function getRub_Objectives_Id()
	 {
		return $this->rub_objectives_id; 
	 }
	 	 
	 public function setRub_Objectives_Id($rub_objectives_id)
	 {
		$this->rub_objectives_id = $rub_objectives_id;
	 }
	 
	 public function getFive_Year_Plan_Id()
	 {
		return $this->five_year_plan_id; 
	 }
		 
	 public function setFive_Year_Plan_Id($five_year_plan_id)
	 {
		$this->five_year_plan_id = $five_year_plan_id;
	 }
	 
	 public function getWeightage()
	 {
		return $this->weightage;
	 }
	 
	 public function setWeightage($weightage)
	 {
		$this->weightage = $weightage;
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

	 public function getFinancial_Year()
	 {
		return $this->financial_year; 
	 }
	 	 
	 public function setFinancial_Year($financial_year)
	 {
		$this->financial_year = $financial_year;
	 }	
}

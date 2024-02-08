<?php

namespace Planning\Model;

class AwpaActivities
{
	protected $id;
	protected $financial_year;
	//protected $success_indicator_name;
	protected $unit;
	protected $weight;
	//protected $baseline;
	protected $target;
	protected $excellent_rating;
	protected $very_good_rating;
	protected $good_rating;
	protected $fair_rating;
	protected $poor_rating;
	protected $activity_status;
	protected $remarks;
	protected $awpa_objectives_activity_id;
	protected $employee_details_id;
	
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
	 	 
	 public function getUnit()
	 {
		return $this->unit; 
	 }
	 	 
	 public function setUnit($unit)
	 {
		$this->unit = $unit;
	 }
	 
	 public function getWeight()
	 {
		return $this->weight; 
	 }
	 	 
	 public function setWeight($weight)
	 {
		$this->weight = $weight;
	 }
	 
	 public function getExcellent_Rating()
	 {
		return $this->excellent_rating; 
	 }
	 	 
	 public function setExcellent_Rating($excellent_rating)
	 {
		$this->excellent_rating = $excellent_rating;
	 }
	 
	 public function getVery_Good_Rating()
	 {
		return $this->very_good_rating; 
	 }
	 	 
	 public function setVery_Good_Rating($very_good_rating)
	 {
		$this->very_good_rating = $very_good_rating;
	 }
	 
	 public function getGood_Rating()
	 {
		return $this->good_rating; 
	 }
	 	 
	 public function setGood_Rating($good_rating)
	 {
		$this->good_rating = $good_rating;
	 }
	 
	 public function getFair_Rating()
	 {
		return $this->fair_rating; 
	 }
	 	 
	 public function setFair_Rating($fair_rating)
	 {
		$this->fair_rating = $fair_rating;
	 }
	 
	 public function getPoor_Rating()
	 {
		return $this->poor_rating; 
	 }
	 	 
	 public function setPoor_Rating($poor_rating)
	 {
		$this->poor_rating = $poor_rating;
	 }
	 
	 public function getActivity_Status()
	 {
		 return $this->activity_status;
	 }
	 
	 public function setActivity_Status($activity_status)
	 {
		 $this->activity_status = $activity_status;
	 }
	 
	 public function getRemarks()
	 {
		 return $this->remarks;
	 }
	 
	 public function setRemarks($remarks)
	 {
		 $this->remarks = $remarks;
	 }
	 
	 public function getAwpa_Objectives_Activity_Id()
	 {
		return $this->awpa_objectives_activity_id; 
	 }
	 	 
	 public function setAwpa_Objectives_Activity_Id($awpa_objectives_activity_id)
	 {
		$this->awpa_objectives_activity_id = $awpa_objectives_activity_id;
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

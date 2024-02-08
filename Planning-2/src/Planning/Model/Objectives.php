<?php

namespace Planning\Model;

class Objectives
{
	protected $id;
	protected $five_year_plan;
	protected $objectives;
	protected $weightage;
	protected $remarks;
	//protected $rub_vision_mission_id;
 	 
	 public function getId()
	 {
		return $this->id;
	 }
	 
	 public function setId($id)
	 {
		$this->id = $id;
	 }
	 
	 public function getFive_Year_Plan()
	 {
		return $this->five_year_plan; 
	 }
		 
	 public function setFive_Year_Plan($five_year_plan)
	 {
		$this->five_year_plan = $five_year_plan;
	 }
	 
	 public function getObjectives()
	 {
		return $this->objectives; 
	 }
	 	 
	 public function setObjectives($objectives)
	 {
		$this->objectives = $objectives;
	 }
	 
	 public function getWeightage()
	 {
		return $this->weightage;
	 }
	 
	 public function setWeightage($weightage)
	 {
		$this->weightage = $weightage;
	 }
	 	 
	 public function getRemarks()
	 {
		return $this->remarks; 
	 }
	 	 
	 public function setRemarks($remarks)
	 {
		$this->remarks = $remarks;
	 }
	 
	 /*
	 public function getRub_Vision_Mission_Id()
	 {
		return $this->rub_vision_mission_id; 
	 }
	 	 
	 public function setRub_Vision_Mission_Id($rub_vision_mission_id)
	 {
		$this->rub_vision_mission_id = $rub_vision_mission_id;
	 }
	*/
}

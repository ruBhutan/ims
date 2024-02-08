<?php

namespace Planning\Model;

class Mission
{
	protected $id;
	protected $five_year_plan;
	protected $mission;
	
 	 
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
	 
	 public function getMission()
	 {
		return $this->mission;
	 }
	 
	 public function setMission($mission)
	 {
		$this->mission = $mission;
	 }
}


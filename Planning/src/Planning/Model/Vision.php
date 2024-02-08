<?php

namespace Planning\Model;

class Vision
{
	protected $id;
	protected $five_year_plan;
	protected $vision;
	
 	 
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
	 		 
	 public function getVision()
	 {
		return $this->vision; 
	 }
		 
	 public function setVision($vision)
	 {
		$this->vision = $vision;
	 }

}
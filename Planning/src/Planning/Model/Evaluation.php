<?php

namespace Planning\Model;

class Evaluation
{
	protected $id;
	protected $objectives;
	protected $weightage;
	protected $remarks;
	protected $awpa_activities_id;
 	 
	 public function getId()
	 {
		return $this->id;
	 }
	 
	 public function setId($id)
	 {
		$this->id = $id;
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

	 public function getAwpa_Activities_Id()
	 {
		return $this->awpa_activities_id;
	 }
	 	 
	 public function setAwpa_Activities_Id($awpa_activities_id)
	 {
		$this->awpa_activities_id = $awpa_activities_id;
	 }
	
}

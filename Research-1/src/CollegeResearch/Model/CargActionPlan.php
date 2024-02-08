<?php

namespace CollegeResearch\Model;

class CargActionPlan
{
	protected $id;
	protected $activity_name;
	protected $time_frame;
	protected $remarks;
	protected $carg_grant_id;
	 	 
	 public function getId()
	 {
		return $this->id;
	 }
	 
	 public function setId($id)
	 {
		$this->id = $id;
	 }
	 
	 public function getActivity_Name()
	 {
		return $this->activity_name; 
	 }
	 	 
	 public function setActivity_Name($activity_name)
	 {
		$this->activity_name = $activity_name;
	 }
	 
	 public function getTime_Frame()
	 {
		return $this->time_frame;
	 }
	 
	 public function setTime_Frame($time_frame)
	 {
		$this->time_frame = $time_frame;
	 }
	 	 
	 public function getRemarks()
	 {
		return $this->remarks; 
	 }
	 	 
	 public function setRemarks($remarks)
	 {
		$this->remarks = $remarks;
	 }
	 
	  public function getCarg_Grant_Id()
	 {
		return $this->carg_grant_id;
	 }
	 
	 public function setCarg_Grant_Id($carg_grant_id)
	 {
		$this->carg_grant_id = $carg_grant_id;
	 }
	 
}
<?php

namespace Planning\Model;

class AwpaObjectives
{
	protected $id;
	protected $activity_name;
	protected $rub_activities_id;
	
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
	 	 
	 public function getRub_Activities_Id()
	 {
		return $this->rub_activities_id; 
	 }
	 	 
	 public function setRub_Activities_Id($rub_activities_id)
	 {
		$this->rub_activities_id = $rub_activities_id;
	 }
	
}

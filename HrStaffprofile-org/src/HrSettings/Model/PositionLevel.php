<?php

namespace HrSettings\Model;

class PositionLevel
{
	protected $id;
	protected $position_level;
	protected $description;
	protected $major_occupational_group_id;
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getPosition_Level()
	 {
		 return $this->position_level;
	 }
	 
	 public function setPosition_Level($position_level)
	 {
		$this->position_level = $position_level; 
	 }
	 
	 public function getDescription()
	 {
		return $this->description; 
	 }
	 
	 public function setDescription($description)
	 {
		 $this->description = $description;
	 }
	 
	 public function getMajor_Occupational_Group_Id()
	 {
		 return $this->major_occupational_group_id;
	 }
	 
	 public function setMajor_Occupational_Group_Id($major_occupational_group_id)
	 {
		 $this->major_occupational_group_id = $major_occupational_group_id;
	 }
}
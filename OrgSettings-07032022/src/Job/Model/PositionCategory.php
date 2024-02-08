<?php

namespace Job\Model;

class PositionCategory
{
	protected $id;
	protected $category;
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
	 
	 public function getCategory()
	 {
		 return $this->category;
	 }
	 
	 public function setCategory($category)
	 {
		 $this->category = $category;
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
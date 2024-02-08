<?php

namespace Job\Model;

class MajorOccupationalGroup
{
	protected $id;
	protected $major_occupational_group;
		 
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getMajor_Occupational_Group()
	 {
		 return $this->major_occupational_group;
	 }
	 
	 public function setMajor_Occupational_Group($major_occupational_group)
	 {
		 $this->major_occupational_group = $major_occupational_group;
	 }

}
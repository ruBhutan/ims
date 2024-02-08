<?php

namespace HrSettings\Model;

class RentAllowance
{
	protected $id;
	protected $rent_allowance;
	protected $position_level;
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getRent_Allowance()
	 {
		 return $this->rent_allowance;
	 }
	 
	 public function setRent_Allowance($rent_allowance)
	 {
		 $this->rent_allowance = $rent_allowance;
	 }
	 
	 public function getPosition_Level()
	 {
		 return $this->position_level;
	 }
	 
	 public function setPosition_Level($position_level)
	 {
		 $this->position_level = $position_level;
	 }
}
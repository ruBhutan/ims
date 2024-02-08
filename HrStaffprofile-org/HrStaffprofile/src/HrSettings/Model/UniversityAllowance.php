<?php

namespace HrSettings\Model;

class UniversityAllowance
{
	protected $id;
	protected $professional_allowance;
	protected $position_level;
	
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getProfessional_Allowance()
	 {
		 return $this->professional_allowance;
	 }
	 
	 public function setProfessional_Allowance($professional_allowance)
	 {
		 $this->professional_allowance = $professional_allowance;
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
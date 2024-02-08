<?php

namespace HrSettings\Model;

class FundingCategory
{
	protected $id;
	protected $funding_type;
	protected $description;
		 
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getFunding_Type()
	 {
		 return $this->funding_type;
	 }
	 
	 public function setFunding_Type($funding_type)
	 {
		 $this->funding_type = $funding_type;
	 }
	 
	 public function getDescription()
	 {
		 return $this->description;
	 }
	 
	 public function setDescription($description)
	 {
		 $this->description = $description;
	 }
	 
}
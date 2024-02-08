<?php

namespace HrSettings\Model;

class EmpResponsibilityCategory
{
	protected $id;
	protected $responsibility_category;
	protected $remarks;
	protected $organisation_id;

	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getResponsibility_Category()
	 {
		 return $this->responsibility_category;
	 }
	 
	 public function setResponsibility_Category($responsibility_category)
	 {
		 $this->responsibility_category = $responsibility_category;
	 }
	 
	 public function getRemarks()
	 {
		 return $this->remarks;
	 }
	 
	 public function setRemarks($remarks)
	 {
		 $this->remarks = $remarks;
	 }
	 
	 public function getOrganisation_Id()
	 {
		 return $this->organisation_id;
	 }
	 
	 public function setOrganisation_Id($organisation_id)
	 {
		 $this->organisation_id = $organisation_id;
	 }

}
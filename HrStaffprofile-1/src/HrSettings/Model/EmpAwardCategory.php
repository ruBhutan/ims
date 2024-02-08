<?php

namespace HrSettings\Model;

class EmpAwardCategory
{
	protected $id;
	protected $award_category;
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
	 
	 public function getAward_Category()
	 {
		 return $this->award_category;
	 }
	 
	 public function setAward_Category($award_category)
	 {
		 $this->award_category = $award_category;
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
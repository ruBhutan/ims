<?php

namespace Review\Model;

class IwpObjectives
{
	protected $id;
	protected $subactivity_name;
	protected $outstanding_description;
	protected $very_good_description;
	protected $good_description;
	protected $needs_improvement_description;
	protected $emp_iwp_rating;
	protected $rated_by;
	protected $remarks;
	protected $awpa_objectives_id;
	
	 public function getId()
	 {
		return $this->id;
	 }
	 
	 public function setId($id)
	 {
		$this->id = $id;
	 }
	 
	 public function getSubactivity_Name()
	 {
		 return $this->subactivity_name;
	 }
	 
	 public function setSubactivity_Name($subactivity_name)
	 {
		 $this->subactivity_name = $subactivity_name;
	 }
	 
	 public function getOutstanding_Description()
	 {
		 return $this->outstanding_description;
	 }
	 
	 public function setOutstanding_Description($outstanding_description)
	 {
		 $this->outstanding_description = $outstanding_description;
	 }
	 
	 public function getVery_Good_Description()
	 {
		 return $this->very_good_description;
	 }
	 
	 public function setVery_Good_Description($very_good_description)
	 {
		 $this->very_good_description = $very_good_description;
	 }
	 
	 public function getGood_Description()
	 {
		 return $this->good_description;
	 }
	 
	 public function setGood_Description($good_description)
	 {
		 $this->good_description = $good_description;
	 }
	 
	 public function getNeeds_Improvement_Description()
	 {
		 return $this->needs_improvement_description;
	 }
	 
	 public function setNeeds_Improvement_Description($needs_improvement_description)
	 {
		 $this->needs_improvement_description = $needs_improvement_description;
	 }
	 
	 public function getEmp_Iwp_Rating()
	 {
		 return $this->emp_iwp_rating;
	 }
	 
	 public function setEmp_Iwp_Rating($emp_iwp_rating)
	 {
		 return $this->emp_iwp_rating = $emp_iwp_rating;
	 }
	 
	 public function getRated_By()
	 {
		 return $this->rated_by;
	 }
	 
	 public function setRated_By($rated_by)
	 {
		 $this->rated_by = $rated_by;
	 }
	 
	 public function getRemarks()
	 {
		 return $this->remarks;
	 }
	 
	 public function setRemarks($remarks)
	 {
		 $this->remarks = $remarks;
	 }
	 	 
	 public function getAwpa_Objectives_Id()
	 {
		return $this->awpa_objectives_id; 
	 }
	 	 
	 public function setAwpa_Objectives_Id($awpa_objectives_id)
	 {
		$this->awpa_objectives_id = $awpa_objectives_id;
	 }
	
}

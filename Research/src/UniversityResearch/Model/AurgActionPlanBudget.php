<?php

namespace UniversityResearch\Model;

class AurgActionPlanBudget
{
	protected $id;
	protected $particulars;
	protected $start_date;
	protected $end_date;
	protected $budget;
	protected $aurg_grant_id;
	
	 	 
	 public function getId()
	 {
		return $this->id;
	 }
	 
	 public function setId($id)
	 {
		$this->id = $id;
	 }
	 
	 public function getParticulars()
	 {
		return $this->particulars; 
	 }
	 	 
	 public function setParticulars($particulars)
	 {
		$this->particulars = $particulars;
	 }
	 
	 public function getStart_Date()
	 {
		return $this->start_date;
	 }
	 
	 public function setStart_Date($start_date)
	 {
		$this->start_date = $start_date;
	 }
	 	 
	 public function getEnd_Date()
	 {
		return $this->end_date; 
	 }
	 	 
	 public function setEnd_Date($end_date)
	 {
		$this->end_date=$end_date;
	 }
	 
	 public function getBudget()
	 {
		return $this->budget; 
	 }
	 	 
	 public function setBudget($budget)
	 {
		$this->budget=$budget;
	 }
	 
	  public function getAurg_Grant_Id()
	 {
		return $this->aurg_grant_id; 
	 }
	 	 
	 public function setAurg_Grant_Id($aurg_grant_id)
	 {
		$this->aurg_grant_id = $aurg_grant_id;
	 }

}
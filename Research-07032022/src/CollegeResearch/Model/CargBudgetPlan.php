<?php

namespace CollegeResearch\Model;

class CargBudgetPlan
{
	protected $id;
	protected $purpose;
	protected $amount;
	protected $remarks;
	protected $carg_grant_id;
	 	 
	 public function getId()
	 {
		return $this->id;
	 }
	 
	 public function setId($id)
	 {
		$this->id = $id;
	 }
	 
	 public function getPurpose()
	 {
		return $this->purpose; 
	 }
	 	 
	 public function setPurpose($purpose)
	 {
		$this->purpose = $purpose;
	 }
	 
	 public function getAmount()
	 {
		return $this->amount;
	 }
	 
	 public function setAmount($amount)
	 {
		$this->amount = $amount;
	 }
	 	 
	 public function getRemarks()
	 {
		return $this->remarks; 
	 }
	 	 
	 public function setRemarks($remarks)
	 {
		$this->remarks = $remarks;
	 }
	 
	 public function getCarg_Grant_Id()
	 {
		return $this->carg_grant_id;
	 }
	 
	 public function setCarg_Grant_Id($carg_grant_id)
	 {
		$this->carg_grant_id = $carg_grant_id;
	 }
	 
}
<?php

namespace UniversityResearch\Model;

class AurgActionPlan
{
	protected $id;
	protected $crc_approval_no;
	protected $ethical_committee_approval_no;
	protected $application_status;
	protected $amount_approved;
	protected $remarks;
	protected $application_step_status;
	protected $related_documents;
	protected $actionplan;
	
	 	 
	 public function getId()
	 {
		return $this->id;
	 }
	 
	 public function setId($id)
	 {
		$this->id = $id;
	 }
	 
	 public function getCrc_Approval_No()
	 {
		return $this->crc_approval_no; 
	 }
	 	 
	 public function setCrc_Approval_No($crc_approval_no)
	 {
		$this->crc_approval_no=$crc_approval_no;
	 }
	 
	 public function getEthical_Committee_Approval_No()
	 {
		return $this->ethical_committee_approval_no; 
	 }
	 	 
	 public function setEthical_Committee_Approval_No($ethical_committee_approval_no)
	 {
		$this->ethical_committee_approval_no=$ethical_committee_approval_no;
	 }
	 
	 public function getApplication_Status()
	 {
		return $this->application_status; 
	 }
	 	 
	 public function setApplication_Status($application_status)
	 {
		$this->application_status=$application_status;
	 }
	 
	 public function getAmount_Approved()
	 {
		return $this->amount_approved; 
	 }
	 	 
	 public function setAmount_Approved($amount_approved)
	 {
		$this->amount_approved=$amount_approved;
	 }
	 
	 public function getRemarks()
	 {
		return $this->remarks; 
	 }
	 	 
	 public function setRemarks($remarks)
	 {
		$this->remarks=$remarks;
	 }
	
	 public function getApplication_Step_Status()
	 {
		return $this->application_step_status; 
	 }
	 	 
	 public function setApplication_Step_Status($application_step_status)
	 {
		$this->application_step_status=$application_step_status;
	 }

	 public function getRelated_Documents()
	 {
		 return $this->related_documents;
	 }
	 
	 public function setRelated_Documents($related_documents)
	 {
		 $this->related_documents = $related_documents;
	 }
	 
	 public function getActionplan()
	 {
		 return $this->actionplan;
	 }
	 
	 public function setActionplan($actionplan)
	 {
		 $this->actionplan = $actionplan;
	 }
	 
}
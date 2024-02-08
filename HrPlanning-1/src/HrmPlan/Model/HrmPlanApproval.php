<?php

namespace HrmPlan\Model;

class HrmPlanApproval
{
	protected $id;
	protected $proposal_status;
	protected $approval_date;
	protected $remarks;	 
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getProposal_Status()
	 {
		 return $this->proposal_status;
	 }
	 
	 public function setProposal_Status($proposal_status)
	 {
		 $this->proposal_status = $proposal_status;
	 }
	 
	 public function getApproval_Date()
	 {
		 return $this->approval_date;
	 }
	 
	 public function setApproval_Date($approval_date)
	 {
		 $this->approval_date = $approval_date;
	 }
	 
	 public function getRemarks()
	 {
		 return $this->remarks;
	 }
	 
	 public function setRemarks($remarks)
	 {
		 $this->remarks = $remarks;
	 }

}
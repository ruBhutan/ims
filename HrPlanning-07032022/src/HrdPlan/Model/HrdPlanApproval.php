<?php

namespace HrdPlan\Model;

class HrdPlanApproval
{
	protected $id;
	protected $approval_status;
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
	 
	 public function getApproval_Status()
	 {
		 return $this->approval_status;
	 }
	 
	 public function setApproval_Status($approval_status)
	 {
		 $this->approval_status = $approval_status;
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
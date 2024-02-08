<?php

namespace Planning\Model;

class BudgetOverlay
{
	protected $id;
	protected $amount;
        protected $awpa_objectives_activity_id;
	
 	 
	 public function getId()
	 {
		return $this->id;
	 }
	 
	 public function setId($id)
	 {
		$this->id = $id;
	 }
	 
	 public function getAmount()
         {
             return $this->amount;
         }
         
         public function setAmount($amount)
         {
             $this->amount = $amount;
         }
         
         public function getAwpa_Objectives_Activity_Id()
         {
            return $this->awpa_objectives_activity_id;
	 }
	
	 public function setAwpa_Objectives_Activity_Id($awpa_objectives_activity_id)
	 {
            $this->awpa_objectives_activity_id = $awpa_objectives_activity_id;
	 }
}


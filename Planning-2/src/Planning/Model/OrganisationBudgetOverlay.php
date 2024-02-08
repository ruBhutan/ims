<?php

namespace Planning\Model;

class OrganisationBudgetOverlay
{
	protected $id;
	protected $amount;
	protected $organisation_id;
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
         
         public function getOrganisation_Id()
         {
             return $this->organisation_id;
         }
         
         public function setOrganisation_Id($organisation_id)
         {
             $this->organisation_id = $organisation_id;
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


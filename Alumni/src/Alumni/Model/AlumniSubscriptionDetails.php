<?php

namespace Alumni\Model;

class AlumniSubscriptionDetails
{
	protected $id;
	protected $subscription_details;
	protected $remarks;
	protected $organisation_id;
	//protected $fk_tbl_alumni_id;
		 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }

	public function getSubscription_Details()
	 {
		return $this->subscription_details; 
	 }
	 	 
	 public function setSubscription_Details($subscription_details)
	 {
		 $this->subscription_details = $subscription_details;
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
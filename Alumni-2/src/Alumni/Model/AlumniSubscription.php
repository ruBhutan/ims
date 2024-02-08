<?php

namespace Alumni\Model;

class AlumniSubscription
{
	protected $id;
	protected $subscription_details;
	protected $subscription_type;
	protected $subscription_charge;
	protected $bank_name;
	protected $bank_account_no;
	protected $organisation_id;
		 
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

	 public function getSubscription_Type()
	 {
		return $this->subscription_type; 
	 }
	 	 
	 public function setSubscription_Type($subscription_type)
	 {
		 $this->subscription_type = $subscription_type;
	 }

	 public function getSubscription_Charge()
	 {
		 return $this->subscription_charge;
	 }
	 
	 public function setSubscription_Charge($subscription_charge)
	 {
		 $this->subscription_charge = $subscription_charge;
	 }
	 
	 public function getBank_Name()
	 {
		return $this->bank_name; 
	 }
	 	 
	 public function setBank_Name($bank_name)
	 {
		 $this->bank_name = $bank_name;
	 }	

	public function getBank_Account_No()
	 {
		return $this->bank_account_no; 
	 }
	 	 
	 public function setBank_Account_No($bank_account_no)
	 {
		 $this->bank_account_no = $bank_account_no;
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
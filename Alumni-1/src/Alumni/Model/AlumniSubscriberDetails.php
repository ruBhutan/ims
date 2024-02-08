<?php

namespace Alumni\Model;

class AlumniSubscriberDetails
{
	protected $id;
	protected $subscriber_id;
	protected $subscription_charge;
	protected $subscription_type;
	protected $application_date;
	protected $updated_date;
	protected $subscriber_details;
	protected $remarks;
	protected $subscription_status;
	protected $alumni_id;
	protected $organisation_id;
		 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }

	public function getSubscriber_Id()
	 {
		return $this->subscriber_id; 
	 }
	 	 
	 public function setSubscriber_Id($subscriber_id)
	 {
		 $this->subscriber_id = $subscriber_id;
	 }

	 public function getSubscription_Charge()
	 {
		 return $this->subscription_charge;
	 }
	 
	 public function setSubscription_Charge($subscription_charge)
	 {
		 $this->subscription_charge = $subscription_charge;
	 }

	 public function getSubscription_Type()
	 {
		 return $this->subscription_type;
	 }
	 
	 public function setSubscription_Type($subscription_type)
	 {
		 $this->subscription_type = $subscription_type;
	 }

	 public function getApplication_Date()
	 {
		 return $this->application_date;
	 }
	 
	 public function setApplication_Date($application_date)
	 {
		 $this->application_date = $application_date;
	 }

	 public function getUpdated_Date()
	 {
		 return $this->updated_date;
	 }
	 
	 public function setUpdated_Date($updated_date)
	 {
		 $this->updated_date = $updated_date;
	 }

	 public function getSubscriber_Details()
	 {
		 return $this->subscriber_details;
	 }
	 
	 public function setSubscriber_Details($subscriber_details)
	 {
		 $this->subscriber_details = $subscriber_details;
	 }
	 
	 public function getRemarks()
	 {
		return $this->remarks; 
	 }
	 	 
	 public function setRemarks($remarks)
	 {
		 $this->remarks = $remarks;
	 }	

	  public function getSubscription_Status()
	 {
		return $this->subscription_status; 
	 }
	 	 
	 public function setSubscription_Status($subscription_status)
	 {
		 $this->subscription_status = $subscription_status;
	 }	

	 public function getAlumni_Id()
	 {
		return $this->alumni_id; 
	 }
	 	 
	 public function setAlumni_Id($alumni_id)
	 {
		 $this->alumni_id = $alumni_id;
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
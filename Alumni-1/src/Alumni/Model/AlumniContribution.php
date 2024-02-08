<?php

namespace Alumni\Model;

class AlumniContribution
{
	protected $id;
	protected $contribution_details;
	protected $contributed_by;
	protected $contributed_date;
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

	public function getContribution_Details()
	 {
		return $this->contribution_details; 
	 }
	 	 
	 public function setContribution_Details($contribution_details)
	 {
		 $this->contribution_details = $contribution_details;
	 }

	 public function getContributed_By()
	 {
		 return $this->contributed_by;
	 }
	 
	 public function setContributed_By($contributed_by)
	 {
		 $this->contributed_by = $contributed_by;
	 }
	 	 
	 public function getContributed_Date()
	 {
		return $this->contributed_date; 
	 }
	 	 
	 public function setContributed_Date($contributed_date)
	 {
		 $this->contributed_date = $contributed_date;
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
<?php

namespace GoodsTransaction\Model;

class ItemDonor
{
	protected $id;
	protected $donor_name;
	protected $remarks;
	protected $organisation_id;
	
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getDonor_Name()
	 {
		return $this->donor_name; 
	 }
	 	 
	 public function setDonor_Name($donor_name)
	 {
		 $this->donor_name = $donor_name;
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
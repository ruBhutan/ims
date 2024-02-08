<?php

namespace HrSettings\Model;

class EmpCommunityServiceCategory
{
	protected $id;
	protected $community_service_category;
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
	 
	 public function getCommunity_Service_Category()
	 {
		 return $this->community_service_category;
	 }
	 
	 public function setCommunity_Service_Category($community_service_category)
	 {
		 $this->community_service_category = $community_service_category;
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
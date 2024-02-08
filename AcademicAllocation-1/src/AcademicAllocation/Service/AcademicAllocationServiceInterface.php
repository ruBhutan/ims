<?php

namespace AcademicAllocation\Service;

use AcademicAllocation\Model\AcademicAllocation;


interface AcademicAllocationServiceInterface
{

	/*
	* Getting the id for username
	*/
	
	public function getUserDetailsId($tableName, $username);
	
	/*
	* Get organisation id based on the username
	*/
	
	public function getOrganisationId($tableName, $username);

	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);

	public function getAllocatedModuleAssessmentComponent($organisation_id);

	public function getAllocatedAssessmmentComponentDetail($id);

	public function updateAllocatedAssessmentWeightage($id, $weightage);
	
}
<?php

namespace PmsDates\Service;

use PmsDates\Model\PmsDates;
use PmsDates\Model\AcademicPmsDates;
use PmsDates\Model\AcademicWeight;
use PmsDates\Model\IwpObjectives;
use PmsDates\Model\NatureActivity;

//need to add more models

interface PmsDatesServiceInterface
{
	/*
	 * Get the Organisation Id
	*/
	 
	public function getOrganisationId($username);
	
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username);
	
	/*
	* take username and returns Name and any other detail required
	*/
	
	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);
	
	/*
	* Save the Activation Dates for PMS (appraisal and review)
	*/
	
	public function save(PmsDates $dateObject);
	
	/*
	* List all PMS Dates
	*/
	
	public function listAll();
	
	/*
	* Find PMS Dates given an $id
	*/
	
	public function find($id);
	 
	/**
	* Should return a set of all objectives that we can iterate over. 
	* 
	* The purpose of the function is the objectives for the dropdown select list
	*
	* @return array|PmsDatesInterface[]
	*/
	
	public function listSelectData($tableName, $columnName, $organisation_id);
		
		
}
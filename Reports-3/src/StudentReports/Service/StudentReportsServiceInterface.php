<?php

namespace StudentReports\Service;

use StudentReports\Model\StudentReports;

//need to add more models

interface StudentReportsServiceInterface
{
	
	public function listAll($tableName, $applicant_id);
	
	public function getUserDetailsId($username);

	public function getUserDetails($username, $usertype);

	public function getUserImage($username, $usertype);
	
	public function getOrganisationId($username);
        
	public function getStudentReport($report_details);
        
	public function getStudentFeedbackReport($report_type, $organisation_id);
	
	public function getFiveYearPlan($five_year_plan);
	 	
	public function listSelectData($tableName, $columnName, $organisation_id);
		
		
}
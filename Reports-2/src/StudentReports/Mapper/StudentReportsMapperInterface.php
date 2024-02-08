<?php

namespace StudentReports\Mapper;

use StudentReports\Model\StudentReports;

interface StudentReportsMapperInterface
{
 
	public function findAll($tableName, $applicant_id);
	
	public function getUserDetailsId($username);
	
	public function getOrganisationId($username);

	public function getUserDetails($username, $usertype);

	public function getUserImage($username, $usertype);
        	
	public function getStudentReport($report_details);
    
	public function getStudentFeedbackReport($report_type, $organisation_id);
	
	public function getFiveYearPlan($five_year_plan);
	 
	public function listSelectData($tableName, $columnName, $organisation_id);
	
}
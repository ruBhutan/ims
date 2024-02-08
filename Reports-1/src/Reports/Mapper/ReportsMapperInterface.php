<?php

namespace Reports\Mapper;

use Reports\Model\Awards;
use Reports\Model\PersonalDetails;
use Reports\Model\CommunityService;
use Reports\Model\Documents;
use Reports\Model\EducationDetails;
use Reports\Model\EmploymentDetails;
use Reports\Model\Reports;
use Reports\Model\LanguageSkills;
use Reports\Model\MembershipDetails;
use Reports\Model\PublicationDetails;
use Reports\Model\References;
use Reports\Model\TrainingDetails;

interface ReportsMapperInterface
{

	/**
	 * 
	 * @return array/ Reports[]
	 */
	 
	public function findAll($tableName, $applicant_id);
	
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username);
	
	/*
	* Get organisation id based on the username
	*/
	
	public function getOrganisationId($username);

	public function getUserDetails($username, $usertype);

	public function getUserImage($username, $usertype);
        	
	/*
	* Get the type of report and the data for the report
	*/
	
	public function getHrReport($report_details, $organisation_id);
	
	/*
	* Get the type of report and the data for the report
	*/
	
	public function getStudentReport($report_details);
        
        /*
	* Get the type of report and the data for the report
        * 
        * Feedback of the students
	*/
	
	public function getStudentFeedbackReport($report_type, $organisation_id);
	
	/*
	* Get the type of report and the data for the report
	* Here the report is an array containing report type and organisation id
	*/
	
	public function getAcademicReport($report);


	public function getAcademicResultReport($report);

	public function getResearchReport($report_details, $organisation_id);
	
	/*
	* Get Five Year Plan in years
	*/

	
	public function getFiveYearPlan($five_year_plan);
		
	/**
	 * 
	 * @return array/ Reports[]
	 */
	 
	public function listSelectData($tableName, $columnName, $organisation_id);
	
}
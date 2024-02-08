<?php

namespace StudentReports\Service;

use StudentReports\Mapper\StudentReportsMapperInterface;
use StudentReports\Model\StudentReports;

class StudentReportsService implements StudentReportsServiceInterface
{

	/**
	 * @var \Blog\Mapper\ReportsMapperInterface
	*/
	
	protected $studentreportMapper;
	
	public function __construct(StudentReportsMapperInterface $studentreportMapper) {
		$this->studentreportMapper = $studentreportMapper;
	}
	
	public function listAll($tableName, $applicant_id)
	{
		return $this->studentreportMapper->findAll($tableName, $applicant_id);
	}
	
	public function getUserDetailsId($username)
	{
		return $this->studentreportMapper->getUserDetailsId($username);
	}

	public function getUserDetails($username, $usertype)
	{
		return $this->studentreportMapper->getUserDetails($username, $usertype);
	}


	public function getUserImage($username, $usertype)
	{
		return $this->studentreportMapper->getUserImage($username, $usertype);
	}
	
	public function getOrganisationId($username)
	{
		return $this->studentreportMapper->getOrganisationId($username);
	}
	
	
	public function getStudentReport($report_details)
	{
		return $this->studentreportMapper->getStudentReport($report_details);
	}
	
	public function getStudentFeedbackReport($report_type, $organisation_id)
        {
                return $this->studentreportMapper->getStudentFeedbackReport($report_type, $organisation_id);
        }
		
	public function getFiveYearPlan($five_year_plan)
	{
		return $this->studentreportMapper->getFiveYearPlan($five_year_plan);
	}
	
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		return $this->studentreportMapper->listSelectData($tableName, $columnName, $organisation_id);
	}
	
}
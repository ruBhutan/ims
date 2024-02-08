<?php

namespace Reports\Service;

use Reports\Mapper\ReportsMapperInterface;
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

class ReportsService implements ReportsServiceInterface
{
	/**
	 * @var \Blog\Mapper\ReportsMapperInterface
	*/
	
	protected $reportMapper;
	
	public function __construct(ReportsMapperInterface $reportMapper) {
		$this->reportMapper = $reportMapper;
	}
	
	public function listAll($tableName, $applicant_id)
	{
		return $this->reportMapper->findAll($tableName, $applicant_id);
	}
	
	public function getUserDetailsId($username)
	{
		return $this->reportMapper->getUserDetailsId($username);
	}

	public function getUserDetails($username, $usertype)
	{
		return $this->reportMapper->getUserDetails($username, $usertype);
	}


	public function getUserImage($username, $usertype)
	{
		return $this->reportMapper->getUserImage($username, $usertype);
	}
	
	public function getOrganisationId($username)
	{
		return $this->reportMapper->getOrganisationId($username);
	}
	
	public function getHrReport($report_details, $organisation_id)
	{
		return $this->reportMapper->getHrReport($report_details, $organisation_id);
	}
	
	public function getStudentReport($report_details)
	{
		return $this->reportMapper->getStudentReport($report_details);
	}
	
	public function getStudentFeedbackReport($report_type, $organisation_id)
        {
                return $this->reportMapper->getStudentFeedbackReport($report_type, $organisation_id);
        }
	
	public function getAcademicReport($report)
	{
		return $this->reportMapper->getAcademicReport($report);
	}

	public function getAcademicResultReport($report)
	{
		return $this->reportMapper->getAcademicResultReport($report);
	}

	public function getResearchReport($report_details, $organisation_id)
	{
		return $this->reportMapper->getResearchReport($report_details, $organisation_id);
	}
		
	public function getFiveYearPlan($five_year_plan)
	{
		return $this->reportMapper->getFiveYearPlan($five_year_plan);
	}
	
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		return $this->reportMapper->listSelectData($tableName, $columnName, $organisation_id);
	}
	public function getGender()
	{
		return $this->reportMapper->getGender();
	}
	public function getEmptype ()
	{
		return $this->reportMapper->getEmptype();
	}
	
	
}
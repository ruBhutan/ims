<?php

namespace JobApplicant\Service;

use JobApplicant\Mapper\JobApplicantMapperInterface;
use JobApplicant\Model\JobApplicant;
use JobApplicant\Model\JobApplication;
use JobApplicant\Model\JobRegistrant;
use JobApplicant\Model\SelectedApplicant;

class JobApplicantService implements JobApplicantServiceInterface
{
	/**
	 * @var \Blog\Mapper\jobApplicantMapperInterface
	*/
	
	protected $jobApplicantMapper;
	
	public function __construct(JobApplicantMapperInterface $jobApplicantMapper) {
		$this->jobApplicantMapper = $jobApplicantMapper;
	}
	
	public function listAll($tableName)
	{
		return $this->jobApplicantMapper->findAll($tableName);
	}
	
	public function findEmpDetails($id)
	{
		return $this->jobApplicantMapper->findEmpDetails($id);
	}
	
	public function getOrganisationId($username)
	{
		return $this->jobApplicantMapper->getOrganisationId($username);
	}
	
	public function getUserDetailsId($username, $tableName)
	{
		return $this->jobApplicantMapper->getUserDetailsId($username, $tableName);
	}
	
	public function getUserDetails($username, $usertype)
	{
		return $this->jobApplicantMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->jobApplicantMapper->getUserImage($username, $usertype);
	}

	public function getPersonalDetails($tableName, $job_applicant_id)
	{
		return $this->jobApplicantMapper->getPersonalDetails($tableName, $job_applicant_id);
	}

	public function getApplicantAddressDetails($job_applicant_id)
	{
		return $this->jobApplicantMapper->getApplicantAddressDetails($job_applicant_id);
	}

	public function getPresentJobDescription($job_applicant_id)
	{
		return $this->jobApplicantMapper->getPresentJobDescription($job_applicant_id);
	}

	public function getEmploymentDetails($job_applicant_id)
	{
		return $this->jobApplicantMapper->getEmploymentDetails($job_applicant_id);
	}

	public function getEducationDetails($job_applicant_id)
	{
		return $this->jobApplicantMapper->getEducationDetails($job_applicant_id);
	}
	
	public function getApplicantMarksDetail($job_applicant_id)
	{
		return $this->jobApplicantMapper->getApplicantMarksDetail($job_applicant_id);
	}

	public function getLanguageDetails($job_applicant_id)
	{
		return $this->jobApplicantMapper->getLanguageDetails($job_applicant_id);
	}

	public function getTrainingDetails($job_applicant_id)
	{
		return $this->jobApplicantMapper->getTrainingDetails($job_applicant_id);
	}

	public function getResearchDetails($job_applicant_id)
	{
		return $this->jobApplicantMapper->getResearchDetails($job_applicant_id);
	}
	
	public function getApplicantCommunityServices($job_applicant_id)
	{
		return $this->jobApplicantMapper->getApplicantCommunityServices($job_applicant_id);
	}
	
	public function getApplicantAwardDetail($job_applicant_id)
	{
		return $this->jobApplicantMapper->getApplicantAwardDetail($job_applicant_id);
	}
	
	public function getApplicantMembershipDetail($job_applicant_id)
	{
		return $this->jobApplicantMapper->getApplicantMembershipDetail($job_applicant_id);
	}
	
	public function saveJobApplication(JobApplication $jobObject)
	{
		return $this->jobApplicantMapper->saveJobApplication($jobObject);
	}
		
	public function getJobApplication($employee_details_id, $job_applicant_id, $id)
	{
		return $this->jobApplicantMapper->getJobApplication($employee_details_id, $job_applicant_id, $id);
	}


	public function getApplicantEducationLevel($job_applicant_id)
	{
		return $this->jobApplicantMapper->getApplicantEducationLevel($job_applicant_id);
	}

	public function getVacancyDetail($id)
	{
		return $this->jobApplicantMapper->getVacancyDetail($id);
	}

	public function getApplicantReferenceDetails($job_applicant_id)
	{
		return $this->jobApplicantMapper->getApplicantReferenceDetails($job_applicant_id);
	}

	public function getJobApplicationList($tableName, $job_applicant_id)
	{
		return $this->jobApplicantMapper->getJobApplicationList($tableName, $job_applicant_id);
	}
	
	public function listSelectData($tableName, $columnName)
	{
		return $this->jobApplicantMapper->listSelectData($tableName, $columnName);
	}

	public function saveJobRegistrantDetails(JobRegistrant $jobregistrantObject, $registrantList)
	{
		return $this->jobApplicantMapper->saveJobRegistrantDetails($jobregistrantObject, $registrantList);
	}

	
}
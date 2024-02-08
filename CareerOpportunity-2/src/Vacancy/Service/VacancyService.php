<?php

namespace Vacancy\Service;

use Vacancy\Mapper\VacancyMapperInterface;
use Vacancy\Model\Vacancy;
use Vacancy\Model\JobApplication;
use Vacancy\Model\SelectedApplicant;
use Vacancy\Model\JobApplicantMarks;

class VacancyService implements VacancyServiceInterface
{
	/**
	 * @var \Blog\Mapper\VacancyMapperInterface
	*/
	
	protected $vacancyMapper;
	
	public function __construct(VacancyMapperInterface $vacancyMapper) {
		$this->vacancyMapper = $vacancyMapper;
	}
	
	public function listAll($tableName, $type, $organisation_id)
	{
		return $this->vacancyMapper->findAll($tableName, $type, $organisation_id);
	}
	
	public function findEmpDetails($id)
	{
		return $this->vacancyMapper->findEmpDetails($id);
	}
	
	public function getOrganisationId($username)
	{
		return $this->vacancyMapper->getOrganisationId($username);
	}
	
	public function getUserDetailsId($username, $tableName)
	{
		return $this->vacancyMapper->getUserDetailsId($username, $tableName);
	}
	
	public function getUserDetails($username, $usertype)
	{
		return $this->vacancyMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->vacancyMapper->getUserImage($username, $usertype);
	}
	
	public function findVacancy($id)
	{
		return $this->vacancyMapper->findVacancy($id);
	}
        
	public function findModule($id) 
	{
		return $this->vacancyMapper->findModule($id);;
	}
	
	public function saveVacancy(Vacancy $vacancyObject) 
	{
		return $this->vacancyMapper->saveVacancy($vacancyObject);
	}
	
	public function saveAdhocVacancy(Vacancy $vacancyObject) 
	{
		return $this->vacancyMapper->saveAdhocVacancy($vacancyObject);
	}

	public function closeAdhocVacancy($id) 
	{
		return $this->vacancyMapper->closeAdhocVacancy($id);
	}
	
	public function saveJobApplication(JobApplication $jobObject)
	{
		return $this->vacancyMapper->saveJobApplication($jobObject);
	}
	
	public function getProposalDetail($id)
	{
		return $this->vacancyMapper->getProposalDetail($id);
	}
	 
	public function getVacancyDetail($id)
	{
		return $this->vacancyMapper->getVacancyDetail($id);
	}
	
	public function getAppliedVacancyDetail($table_name, $id)
	{
		return $this->vacancyMapper->getAppliedVacancyDetail($table_name, $id);
	}
		
	public function getJobApplicantDetail($id)
	{
		return $this->vacancyMapper->getJobApplicantDetail($id);
	}
	
	public function getSelectedApplicantDetail($id)
    {
            return $this->vacancyMapper->getSelectedApplicantDetail($id);
    }
        
	public function getRecruitmentDetails($id)
    {
            return $this->vacancyMapper->getRecruitmentDetails($id);
    }

    public function getApplicantEducationLevel($employee_details_id)
    {
    	return $this->vacancyMapper->getApplicantEducationLevel($employee_details_id);
    }

    public function getApplicantAddressDetails($employee_details_id, $type)
    {
    	return $this->vacancyMapper->getApplicantAddressDetails($employee_details_id, $type);
    }
		
	public function getJobApplication($employee_details_id, $job_applicant_id, $id)
	{
		return $this->vacancyMapper->getJobApplication($employee_details_id, $job_applicant_id, $id);
	}
		 
	public function getPersonalDetails($tableName, $applicant_id)
	{
		return $this->vacancyMapper->getPersonalDetails($tableName, $applicant_id);
	}
	 	 
	public function getEducationDetails($tableName, $applicant_id, $id)
	{
		return $this->vacancyMapper->getEducationDetails($tableName, $applicant_id, $id);
	}
	
	public function getApplicantMarksDetail($table_name, $job_applicant_id,$id)
	{
		return $this->vacancyMapper->getApplicantMarksDetail($table_name, $job_applicant_id,$id);
	}
	 	 
	public function getEmploymentDetails($tableName, $applicant_id, $id)
	{
		return $this->vacancyMapper->getEmploymentDetails($tableName, $applicant_id, $id);
	}
	 
	public function getTrainingDetails($tableName, $applicant_id)
	{
		return $this->vacancyMapper->getTrainingDetails($tableName, $applicant_id);
	}
	 	 
	public function getResearchDetails($tableName, $applicant_id)
	{
		return $this->vacancyMapper->getResearchDetails($tableName, $applicant_id);
	}
	
	public function getApplicantCommunityServices($job_applicant_id)
	{
		return $this->vacancyMapper->getApplicantCommunityServices($job_applicant_id);
	}
	
	public function getApplicantAwardDetail($job_applicant_id)
	{
		return $this->vacancyMapper->getApplicantAwardDetail($job_applicant_id);
	}
	
	public function getApplicantMembershipDetail($job_applicant_id)
	{
		return $this->vacancyMapper->getApplicantMembershipDetail($job_applicant_id);
	}


	public function getApplicantReferenceDetails($table_name, $job_applicant_id, $id)
	{
		return $this->vacancyMapper->getApplicantReferenceDetails($table_name, $job_applicant_id, $id);
	}

	public function getPresentJobDescription($table_name, $job_applicant_id)
	{
		return $this->vacancyMapper->getPresentJobDescription($table_name, $job_applicant_id);
	}

	public function getLanguageDetails($employee_details_id, $type)
	{
		return $this->vacancyMapper->getLanguageDetails($employee_details_id, $type);
	}

	public function getApplicantPromotionDetails($table_name, $job_applicant_id)
	{
		return $this->vacancyMapper->getApplicantPromotionDetails($table_name, $job_applicant_id);
	}
	
	public function getApplicantDocuments($job_applicant_id)
	{
		return $this->vacancyMapper->getApplicantDocuments($job_applicant_id);
	}
	
	public function getApplicantDocumentList($table_name, $job_applicant_id, $type)
	{
		return $this->vacancyMapper->getApplicantDocumentList($table_name, $job_applicant_id, $type);
	}
	
	public function listAllProposals($organisation_id)
	{
		return $this->vacancyMapper->listAllProposals($organisation_id);
	}
		 
	public function listJobApplicants($type,$status, $organisation_id)
	{
		return $this->vacancyMapper->listJobApplicants($type,$status, $organisation_id);
	}

	public function listJobApplicantsLatestEducation($type)
	{
		return $this->vacancyMapper->listJobApplicantsLatestEducation($type);
	}
	
	public function getFileName($file_id, $column_name)
	{
		return $this->vacancyMapper->getFileName($file_id, $column_name);
	}
	
	public function updateJobApplication($id, $status)
	{
		return $this->vacancyMapper->updateJobApplication($id, $status);
	}
		 
	public function updateJobApplicantDetails($table_name, $job_applicant_id, $data, SelectedApplicant $jobObject)
	{
		return $this->vacancyMapper->updateJobApplicantDetails($table_name, $job_applicant_id, $data, $jobObject);
	}

	public function saveJobApplicantMarks(JobApplicantMarks $jobObject)
	{
		return $this->vacancyMapper->saveJobApplicantMarks($jobObject);
	}
         
	public function listRecruitedCandidates()
	{
			return $this->vacancyMapper->listRecruitedCandidates();
	}
	 
	public function updateSelectedCandidateDetails($table_name, $job_applicant_id, SelectedApplicant $jobObject, $data)
	{
			return $this->vacancyMapper->updateSelectedCandidateDetails($table_name, $job_applicant_id, $jobObject, $data);
	}
		
	public function listAnnouncedVacancy($organisation_id)
	{
		return $this->vacancyMapper->listAnnouncedVacancy($organisation_id);
	}

	public function listAllAppliedApplicant($type, $organisation_id)
	{
		return $this->vacancyMapper->listAllAppliedApplicant($type, $organisation_id);
	}

	public function listAppliedApplicants($type, $position_title, $organisation_id)
	{
		return $this->vacancyMapper->listAppliedApplicants($type, $position_title, $organisation_id);
	}
	
	public function listAllApplicantDegreeMarks($type)
	{
		return $this->vacancyMapper->listAllApplicantDegreeMarks($type);
	}

	public function getApplicantDetail($applicant_id, $category)
	{
		return $this->vacancyMapper->getApplicantDetail($applicant_id, $category);
	}

	public function getJobApplicantMarks($applicant_id, $category)
	{
		return $this->vacancyMapper->getJobApplicantMarks($applicant_id, $category);
	}

	public function listApplicantStudyLevel($tableName, $job_applicant_id)
	{
		return $this->vacancyMapper->listApplicantStudyLevel($tableName, $job_applicant_id);
	}
	
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		return $this->vacancyMapper->listSelectData($tableName, $columnName, $organisation_id);
	}
	
}
<?php

namespace JobPortal\Service;

use JobPortal\Mapper\JobPortalMapperInterface;
use JobPortal\Model\Awards;
use JobPortal\Model\PersonalDetails;
use JobPortal\Model\CommunityService;
use JobPortal\Model\Documents;
use JobPortal\Model\EducationDetails;
use JobPortal\Model\EmploymentDetails;
use JobPortal\Model\JobPortal;
use JobPortal\Model\LanguageSkills;
use JobPortal\Model\MembershipDetails;
use JobPortal\Model\PublicationDetails;
use JobPortal\Model\References;
use JobPortal\Model\TrainingDetails;
use JobPortal\Model\ApplicantMarks;

class JobPortalService implements JobPortalServiceInterface
{
	/**
	 * @var \Blog\Mapper\JobPortalMapperInterface
	*/
	
	protected $jobMapper;
	
	public function __construct(JobPortalMapperInterface $jobMapper) {
		$this->jobMapper = $jobMapper;
	}

	public function getUserDetailsId($username, $usertype)
	{
		return $this->jobMapper->getUserDetailsId($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->jobMapper->getUserImage($username, $usertype);
	}
	
	public function listAll($tableName, $applicant_id)
	{
		return $this->jobMapper->findAll($tableName, $applicant_id);
	}

	public function listApplicantStudyLevel($tableName, $job_applicant_id)
	{
		return $this->jobMapper->listApplicantStudyLevel($tableName, $job_applicant_id);
	}

	public function getApplicantAddressDetails($job_applicant_details_id)
	{
		return $this->jobMapper->getApplicantAddressDetails($job_applicant_details_id);
	}

	public function getRegistrantOtherDetails($tableName, $id)
	{
		return $this->jobMapper->getRegistrantOtherDetails($tableName, $id);
	}
	
	public function getOrganisationId($username)
	{
		return $this->jobMapper->getOrganisationId($username);
	}
		
	public function savePersonalDetails(PersonalDetails $jobObject, $country, $dzongkhag, $gewog, $village)
	{
		return $this->jobMapper->savePersonalDetails($jobObject, $country, $dzongkhag, $gewog, $village);
	}
		
	public function saveEducationDetails(EducationDetails $jobObject)
	{
		return $this->jobMapper->saveEducationDetails($jobObject);
	}

	public function updateEducationDetails(EducationDetails $jobObject)
	{
		return $this->jobMapper->updateEducationDetails($jobObject);
	}
	public function deleteEducationDetails($id)
	{
		return $this->jobMapper->deleteEducationDetails($id);
	}

	public function deleteEmploymentDetails($id)
	{
		return $this->jobMapper->deleteEmploymentDetails($id);
	}
		
	public function saveTrainingDetails(TrainingDetails $jobObject)
	{
		return $this->jobMapper->saveTrainingDetails($jobObject);
	}


	public function updateTrainingDetails(TrainingDetails $jobObject)
	{
		return $this->jobMapper->updateTrainingDetails($jobObject);
	}
		
	public function saveEmploymentRecord(EmploymentDetails $jobObject)
	{
		return $this->jobMapper->saveEmploymentRecord($jobObject);
	}


	public function updateEmploymentRecord(EmploymentDetails $jobObject)
	{
		return $this->jobMapper->updateEmploymentRecord($jobObject);
	}
		
	public function saveMembership(MembershipDetails $jobObject)
	{
		return $this->jobMapper->saveMembership($jobObject);
	}

	public function updateMembership(MembershipDetails $jobObject)
	{
		return $this->jobMapper->updateMembership($jobObject);
	}
		
	public function saveCommunityService(CommunityService $jobObject)
	{
		return $this->jobMapper->saveCommunityService($jobObject);
	}

	public function updateCommunityService(CommunityService $jobObject)
	{
		return $this->jobMapper->updateCommunityService($jobObject);
	}
		
	public function saveLanguageSkills(LanguageSkills $jobObject)
	{
		return $this->jobMapper->saveLanguageSkills($jobObject);
	}
		
	public function savePublications(PublicationDetails $jobObject)
	{
		return $this->jobMapper->savePublications($jobObject);
	}
		
	public function saveAwards(Awards $jobObject)
	{
		return $this->jobMapper->saveAwards($jobObject);
	}

	public function updateAwards(Awards $jobObject)
	{
		return $this->jobMapper->updateAwards($jobObject);
	}
		
	public function saveReferences(References $jobObject)
	{
		return $this->jobMapper->saveReferences($jobObject);
	}

	public function saveJobApplicantMarks(ApplicantMarks $jobObject)
	{
		return $this->jobMapper->saveJobApplicantMarks($jobObject);
	}
		
	public function saveDocuments(Documents $jobObject)
	{
		return $this->jobMapper->saveDocuments($jobObject);
	}

	public function getFileName($file_id, $column_name, $type)
	{
		return $this->jobMapper->getFileName($file_id, $column_name, $type);
	}
	
	public function listSelectData($tableName, $columnName, $condition)
	{
		return $this->jobMapper->listSelectData($tableName, $columnName, $condition);
	}
	
}
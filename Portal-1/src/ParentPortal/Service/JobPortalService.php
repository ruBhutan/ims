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

class JobPortalService implements JobPortalServiceInterface
{
	/**
	 * @var \Blog\Mapper\JobPortalMapperInterface
	*/
	
	protected $jobMapper;
	
	public function __construct(JobPortalMapperInterface $jobMapper) {
		$this->jobMapper = $jobMapper;
	}
	
	public function listAll($tableName)
	{
		return $this->jobMapper->findAll($tableName);
	}
	
	public function getUserDetailsId($username)
	{
		return $this->jobMapper->getUserDetailsId($username);
	}
	
	public function getOrganisationId($username)
	{
		return $this->jobMapper->getOrganisationId($username);
	}
		
	public function savePersonalDetails(PersonalDetails $jobObject)
	{
		return $this->jobMapper->savePersonalDetails($jobObject);
	}
		
	public function saveEducationDetails(EducationDetails $jobObject)
	{
		return $this->jobMapper->saveEducationDetails($jobObject);
	}
		
	public function saveTrainingDetails(TrainingDetails $jobObject)
	{
		return $this->jobMapper->saveTrainingDetails($jobObject);
	}
		
	public function saveEmploymentRecord(EmploymentDetails $jobObject)
	{
		return $this->jobMapper->saveEmploymentRecord($jobObject);
	}
		
	public function saveMembership(MembershipDetails $jobObject)
	{
		return $this->jobMapper->saveMembership($jobObject);
	}
		
	public function saveCommunityService(CommunityService $jobObject)
	{
		return $this->jobMapper->saveCommunityService($jobObject);
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
		
	public function saveReferences(References $jobObject)
	{
		return $this->jobMapper->saveReferences($jobObject);
	}
		
	public function saveDocuments(Documents $jobObject)
	{
		return $this->jobMapper->saveDocuments($jobObject);
	}
	
	public function listSelectData($tableName, $columnName, $condition)
	{
		return $this->jobMapper->listSelectData($tableName, $columnName, $condition);
	}
	
}
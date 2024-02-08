<?php

namespace UniversityResearch\Service;

use UniversityResearch\Mapper\UniversityResearchMapperInterface;
use UniversityResearch\Model\AurgTitle;
use UniversityResearch\Model\AurgProjectDescription;
use UniversityResearch\Model\AurgActionPlan;
use UniversityResearch\Model\ResearchGrantAnnouncement;
use UniversityResearch\Model\ResearchRecommendation;
use UniversityResearch\Model\UpdateAurgGrant;

class UniversityResearchService implements UniversityResearchServiceInterface
{
	/**
	 * @var \Blog\Mapper\UniversityResearchMapperInterface
	*/
	
	protected $universityResearchMapper;
	
	public function __construct(UniversityResearchMapperInterface $universityResearchMapper) {
		$this->universityResearchMapper = $universityResearchMapper;
	}
	
	public function listAllResearches()
	{
		return $this->universityResearchMapper->findAll();
	}
	 
	public function findResearch($id)
	{
		return $this->universityResearchMapper->find($id);
	}
		
	public function getUserDetailsId($username, $tableName)
	{
		return $this->universityResearchMapper->getUserDetailsId($username, $tableName);
	}
	
	public function getUserDetails($username, $usertype)
	{
		return $this->universityResearchMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->universityResearchMapper->getUserImage($username, $usertype);
	}
	
	public function getOrganisationId($username, $usertype)
	{
		return $this->universityResearchMapper->getOrganisationId($username, $usertype);
	}
		
	public function getEmployeeDetails($id)
	{
		return $this->universityResearchMapper->getEmployeeDetails($id);
	}
        
	public function findResearchDetails($id, $tableName) 
	{
		return $this->universityResearchMapper->findResearchDetails($id, $tableName);
	}
	
	public function findCargResearchDetails($id, $type)
	{
		return $this->universityResearchMapper->findCargResearchDetails($id, $type);
	}
	
	public function saveAurgTitle(AurgTitle $aurgTitleObject) 
	{
		return $this->universityResearchMapper->saveAurgTitle($aurgTitleObject);
	}
	
	public function saveAurgProjectDescription(AurgProjectDescription $aurgProjectObject) 
	{
		return $this->universityResearchMapper->saveAurgProjectDescription($aurgProjectObject);
	}
	
	public function saveAurgActionPlan(AurgActionPlan $aurgPlanObject) 
	{
		return $this->universityResearchMapper->saveAurgActionPlan($aurgPlanObject);
	}

	public function updateAurgStatus(UpdateAurgGrant $aurgPlanObject)
	{
		return $this->universityResearchMapper->updateAurgStatus($aurgPlanObject);
	}
		 
	public function saveResearchRecommendation(ResearchRecommendation $researchObject, $approving_authority)
	{
		return $this->universityResearchMapper->saveResearchRecommendation($researchObject, $approving_authority);
	}
	
		
	public function getResearcherDetails($id, $type)
	{
		return $this->universityResearchMapper->getResearcherDetails($id, $type);
	}


	public function getResearchGrantDetail($type, $research_grant_type)
	{
		return $this->universityResearchMapper->getResearchGrantDetail($type, $research_grant_type);
	}
		
	public function getAllResearchTypes($organisation_id)
	{
		return $this->universityResearchMapper->getAllResearchTypes($organisation_id);
	}


	public function getFileName($application_id, $column_name, $research_type)
	{
		return $this->universityResearchMapper->getFileName($application_id, $column_name, $research_type);
	}
	
	public function saveResearchGrantAnnouncement(ResearchGrantAnnouncement $announcementObject)
	{
		return $this->universityResearchMapper->saveResearchGrantAnnouncement($announcementObject);
	}
		 
	public function saveRecommendation(ResearchRecommendation $recommendationObject)
	{
		return $this->universityResearchMapper->saveRecommendation($recommendationObject);
	}

	public function getResearchGrantAnnouncement($id, $organisation_id)
	{
		return $this->universityResearchMapper->getResearchGrantAnnouncement($id, $organisation_id);
	}
		 
	public function getPreviousResearch($id)
	{
		return $this->universityResearchMapper->getPreviousResearch($id);
	}
		 
	public function getAurgList($researcher_name, $research_title, $grant_type, $status)
	{
		return $this->universityResearchMapper->getAurgList($researcher_name, $research_title, $grant_type, $status);
	}
		 
	public function getResearchList($employee_id)
	{
		return $this->universityResearchMapper->getResearchList($employee_id);
	}
		
	public function getResearchGrantList()
	{
		return $this->universityResearchMapper->getResearchGrantList();
	}

	public function deleteResearchGrantApplication($id, $type)
	{
		return $this->universityResearchMapper->deleteResearchGrantApplication($id, $type);
	}
		
	public function listSelectData($tableName, $columnName)
	{
		return $this->universityResearchMapper->listSelectData($tableName, $columnName);
	}
	
}
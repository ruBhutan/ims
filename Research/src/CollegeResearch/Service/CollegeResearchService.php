<?php

namespace CollegeResearch\Service;

use CollegeResearch\Mapper\CollegeResearchMapperInterface;
use CollegeResearch\Model\CollegeResearch;
use CollegeResearch\Model\CargGrant;
use CollegeResearch\Model\CargResearch;
use CollegeResearch\Model\CargActionPlan;
use CollegeResearch\Model\CargAction;
use CollegeResearch\Model\ResearchRecommendation;
use CollegeResearch\Model\UpdateCargGrant;


class CollegeResearchService implements CollegeResearchServiceInterface
{
	/**
	 * @var \Blog\Mapper\PostMapperInterface
	*/
	
	protected $collegeResearchMapper;
	
	public function __construct(CollegeResearchMapperInterface $collegeResearchMapper) {
		$this->collegeResearchMapper = $collegeResearchMapper;
	}
	
	public function listAllResearches()
	{
		return $this->collegeResearchMapper->findAll();
	}
	 
	public function findResearch($id)
	{
		return $this->collegeResearchMapper->find($id);
	}
    	
	public function save(CollegeResearch $collegeResearch) 
	{
		return $this->collegeResearchMapper->saveDetails($collegeResearch);
	}
	
	public function saveCargGrant(CargGrant $cargGrant) 
	{
		return $this->collegeResearchMapper->saveCargGrant($cargGrant);
	}
	
	public function saveCargProject(CargResearch $cargProject) 
	{
		return $this->collegeResearchMapper->saveCargProject($cargProject);
	}
	
	public function saveCargActionPlan(CargAction $cargActionPlan) 
	{
		return $this->collegeResearchMapper->saveCargActionPlan($cargActionPlan);
	}

	public function updateCargGrant(UpdateCargGrant $cargActionPlan)
	{
		return $this->collegeResearchMapper->updateCargGrant($cargActionPlan);
	}
		 
	public function saveRecommendation(ResearchRecommendation $recommendationObject)
	{
		return $this->collegeResearchMapper->saveRecommendation($recommendationObject);
	}
		
	public function saveResearchApproval(ResearchRecommendation $collegeResearchObject)
	{
		return $this->collegeResearchMapper->saveResearchApproval($collegeResearchObject);
	}
	
	public function getUserDetailsId($username, $tableName)
	{
		return $this->collegeResearchMapper->getUserDetailsId($username, $tableName);
	}
	
	public function getUserDetails($username, $usertype)
	{
		return $this->collegeResearchMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->collegeResearchMapper->getUserImage($username, $usertype);
	}
	
	public function getOrganisationId($username, $usertype)
	{
		return $this->collegeResearchMapper->getOrganisationId($username, $usertype);
	}
		
	public function getEmployeeDetails($id)
	{
		return $this->collegeResearchMapper->getEmployeeDetails($id);
	}
	
	public function getResearchGrantList($organisation_id)
	{
		return $this->collegeResearchMapper->getResearchGrantList($organisation_id);
	}
		 
	public function getResearchGrantAnnouncement($id,$organisation_id)
	{
		return $this->collegeResearchMapper->getResearchGrantAnnouncement($id,$organisation_id);
	}
		
	public function getCargList($researcher_name, $research_title, $grant_type, $status, $organisation_id)
	{
		return $this->collegeResearchMapper->getCargList($researcher_name, $research_title, $grant_type, $status, $organisation_id);
	}
		 
	public function getAurgList($researcher_name, $research_title, $grant_type, $status, $organisation_id)
	{
		return $this->collegeResearchMapper->getAurgList($researcher_name, $research_title, $grant_type, $status, $organisation_id);
	}
	
	public function findResearchDetails($id, $tableName)
	{
		return $this->collegeResearchMapper->findResearchDetails($id, $tableName);
	}
	 
	public function getFileName($training_id, $column_name, $research_type)
	{
		return $this->collegeResearchMapper->getFileName($training_id, $column_name, $research_type);
	}
	
}
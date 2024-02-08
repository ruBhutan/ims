<?php

namespace ResearchPublication\Service;

use ResearchPublication\Mapper\ResearchPublicationMapperInterface;
use ResearchPublication\Model\ResearchPublication;
use ResearchPublication\Model\PublicationType;
use ResearchPublication\Model\ResearchAnnouncement;
use ResearchPublication\Model\ResearchRecommendation;
use ResearchPublication\Model\ResearchType;
use ResearchPublication\Model\SeminarAnnouncement;

class ResearchPublicationService implements ResearchPublicationServiceInterface
{
	/**
	 * @var \Blog\Mapper\ResearchPublicationMapperInterface
	*/
	
	protected $publicationMapper;
	
	public function __construct(ResearchPublicationMapperInterface $publicationMapper) {
		$this->publicationMapper = $publicationMapper;
	}
	
	public function listAll($tableName, $organisation_id)
	{
		return $this->publicationMapper->findAll($tableName, $organisation_id);
	}
		
	public function getUserDetailsId($username, $tableName)
	{
		return $this->publicationMapper->getUserDetailsId($username, $tableName);
	}
	
	public function getUserDetails($username, $usertype)
	{
		return $this->publicationMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->publicationMapper->getUserImage($username, $usertype);
	}
	
	public function getOrganisationId($username, $usertype)
	{
		return $this->publicationMapper->getOrganisationId($username, $usertype);
	}
	
	public function findEmpDetails($id)
	{
		return $this->publicationMapper->findEmpDetails($id);
	}
	         
	public function findPublicationType($id) 
	{
		return $this->publicationMapper->findPublicationType($id);;
	}

	public function getResearchPublicationDetail($type, $research_publication_type)
	{
		return $this->publicationMapper->getResearchPublicationDetail($type, $research_publication_type);
	}
	
	public function findPublication($id) 
	{
		return $this->publicationMapper->findPublication($id);;
	}

	public function getResearchPublicationList($employee_details_id)
	{
		return $this->publicationMapper->getResearchPublicationList($employee_details_id);
	}

	public function getSemiarAnnouncementList($organisation_id)
	{
		return $this->publicationMapper->getSemiarAnnouncementList($organisation_id);
	}

	public function getSeminarAnnouncementDetails($id)
	{
		return $this->publicationMapper->getSeminarAnnouncementDetails($id);
	}
	
	public function save(ResearchPublication $publicationObject) 
	{
		return $this->publicationMapper->saveDetails($publicationObject);
	}

	public function updateResearchPublication(ResearchRecommendation $publicationObject)
	{
		return $this->publicationMapper->updateResearchPublication($publicationObject);
	}
	
	public function saveResearchAnnouncement(ResearchAnnouncement $publicationObject) 
	{
		return $this->publicationMapper->saveResearchAnnouncement($publicationObject);
	}

	public function saveSeminarAnnouncement(SeminarAnnouncement $publicationObject)
	{
		return $this->publicationMapper->saveSeminarAnnouncement($publicationObject);
	}
	
	public function savePublicationType(PublicationType $publicationObject) 
	{
		return $this->publicationMapper->savePublicationType($publicationObject);
	}
		 
	public function saveRecommendation(ResearchRecommendation $recommendationObject)
	{
		return $this->publicationMapper->saveRecommendation($recommendationObject);
	}
		
	public function saveResearchType(ResearchType $researchObject)
	{
		return $this->publicationMapper->saveResearchType($researchObject);
	}
		
	public function getAllResearchTypes($organisation_id)
	{
		return $this->publicationMapper->getAllResearchTypes($organisation_id);
	}
	
	public function getPublicationList($type)
	{
		 return $this->publicationMapper->getPublicationList($type);
	}
	
	public function getFileName($id)
	{
		return $this->publicationMapper->getFileName($id);
	}
		
	public function getDetails($id, $table_name)
	{
		return $this->publicationMapper->getDetails($id, $table_name);
	}

	public function getResearchPublicationAnnouncement($id, $organisation_id)
	{
		return $this->publicationMapper->getResearchPublicationAnnouncement($id, $organisation_id);
	}
		
	public function listSelectData($tableName, $columnName, $date, $organisation_id)
	{
		return $this->publicationMapper->listSelectData($tableName, $columnName, $date, $organisation_id);
	}
	
}
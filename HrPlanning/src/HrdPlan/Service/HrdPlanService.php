<?php

namespace HrdPlan\Service;

use HrdPlan\Mapper\HrdPlanMapperInterface;
use HrdPlan\Model\HrdPlan;
use HrdPlan\Model\HrdPlanApproval;

class HrdPlanService implements HrdPlanServiceInterface
{
	/**
	 * @var \Blog\Mapper\PostMapperInterface
	*/
	
	protected $hrdPlanDetailMapper;
	
	public function __construct(HrdPlanMapperInterface $hrdPlanDetailMapper) {
		$this->hrdPlanDetailMapper = $hrdPlanDetailMapper;
	}
	
	public function listAllProposals($status, $organisation_id)
	{
		return $this->hrdPlanDetailMapper->findAll($status, $organisation_id);
	}
	 
	public function findProposal($id)
	{
		return $this->hrdPlanDetailMapper->find($id);
	}
        
	public function findProposalDetails($id) 
	{
		return $this->hrdPlanDetailMapper->findDetails($id);;
	}
	
	public function save(HrdPlan $hrdPlanObject) 
	{
		return $this->hrdPlanDetailMapper->saveDetails($hrdPlanObject);
	}
	
	public function updateProposal(HrdPlanApproval $hrdPlanObject, $submitValue) 
	{
		return $this->hrdPlanDetailMapper->updateProposal($hrdPlanObject, $submitValue);
	}
	
	public function updateHrdProposal($status, $previousStatus, $id, $organisation_id)
	{
		return $this->hrdPlanDetailMapper->updateHrdProposal($status, $previousStatus, $id, $organisation_id);
	}
		
	public function deleteProposal($id)
	{
		return $this->hrdPlanDetailMapper->deleteProposal($id);
	}
		
	public function getUserDetailsId($username, $tableName)
	{
		return $this->hrdPlanDetailMapper->getUserDetailsId($username, $tableName);
	}
	
	public function getUserDetails($username, $usertype)
	{
		return $this->hrdPlanDetailMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->hrdPlanDetailMapper->getUserImage($username, $usertype);
	}
	
	public function getOrganisationId($username)
	{
		return $this->hrdPlanDetailMapper->getOrganisationId($username);
	}
	
	public function getEmployeeDetails($empId)
	{
		return $this->hrdPlanDetailMapper->getEmployeeDetails($empId);
	}
		
	public function getFiveYearPlan()
	{
		return $this->hrdPlanDetailMapper->getFiveYearPlan();
	}
		
	public function getProposalDates($proposal_type)
	{
		return $this->hrdPlanDetailMapper->getProposalDates($proposal_type);
	}
	
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		return $this->hrdPlanDetailMapper->listSelectData($tableName, $columnName, $organisation_id);
	}
	
}
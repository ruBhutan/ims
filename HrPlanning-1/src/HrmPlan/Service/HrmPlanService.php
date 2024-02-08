<?php

namespace HrmPlan\Service;

use HrmPlan\Mapper\HrmPlanMapperInterface;
use HrmPlan\Model\HrmPlan;
use HrmPlan\Model\HrmPlanApproval;

class HrmPlanService implements HrmPlanServiceInterface
{
	/**
	 * @var \Blog\Mapper\PostMapperInterface
	*/
	
	protected $hrmPlanMapper;
	
	public function __construct(HrmPlanMapperInterface $hrmPlanMapper) {
		$this->hrmPlanMapper = $hrmPlanMapper;
	}
	
	public function listAllProposals($status, $organisation_id)
	{
		return $this->hrmPlanMapper->findAll($status, $organisation_id);
	}
	 
	public function findProposal($id)
	{
		return $this->hrmPlanMapper->find($id);
	}
        
	public function findProposalDetails($id) 
	{
		return $this->hrmPlanMapper->findDetails($id);;
	}
	
	public function save(HrmPlan $hrmPlan, $data) 
	{
		return $this->hrmPlanMapper->saveDetails($hrmPlan, $data);
	}
	
	public function updateProposal(HrmPlanApproval $hrmPlanModel, $submitValue)
	{
		return $this->hrmPlanMapper->updateProposal($hrmPlanModel, $submitValue);
	}
	
	public function updateHrmProposal($status, $previousStatus, $id, $organisation_id)
	{
		return $this->hrmPlanMapper->updateHrmProposal($status, $previousStatus, $id, $organisation_id);
	}
		
	public function deleteHrmProposal($id)
	{
		return $this->hrmPlanMapper->deleteHrmProposal($id);
	}
	
	public function getUserDetailsId($username, $tableName)
	{
		return $this->hrmPlanMapper->getUserDetailsId($username, $tableName);
	}
	
	public function getUserDetails($username, $tableName)
	{
		return $this->hrmPlanMapper->getUserDetails($username, $tableName);
	}
	
	public function getOrganisationId($username)
	{
		return $this->hrmPlanMapper->getOrganisationId($username);
	}
	
	public function getEmployeeDetails($empId)
	{
		return $this->hrmPlanMapper->getEmployeeDetails($empId);
	}
		
	public function getFiveYearPlan()
	{
		return $this->hrmPlanMapper->getFiveYearPlan();
	}
		
	public function getProposalDates($proposal_type)
	{
		return $this->hrmPlanMapper->getProposalDates($proposal_type);
	}
	
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		return $this->hrmPlanMapper->listSelectData($tableName, $columnName, $organisation_id);
	}
	
}
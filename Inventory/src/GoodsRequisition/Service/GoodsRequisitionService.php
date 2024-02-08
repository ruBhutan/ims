<?php

namespace GoodsRequisition\Service;

use GoodsRequisition\Mapper\GoodsRequisitionMapperInterface;
use GoodsRequisition\Model\GoodsRequisition;
use GoodsRequisition\Model\GoodsRequisitionApproval;
use GoodsRequisition\Model\GoodsRequisitionForwardApproval;

class GoodsRequisitionService implements GoodsRequisitionServiceInterface
{

	public function getUserDetailsId($username)
	{
		return $this->goodsRequisitionMapper->getUserDetailsId($username);
	}
	
	public function getOrganisationId($username)
	{
		return $this->goodsRequisitionMapper->getOrganisationId($username);
	}

	public function getUserDetails($username, $usertype)
	{
		return $this->goodsRequisitionMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->goodsRequisitionMapper->getUserImage($username, $usertype);
	}

	/**
	 * @var \Blog\Mapper\PostMapperInterface
	*/
	
	protected $goodsRequisitionMapper;
	
	public function __construct(GoodsRequisitionMapperInterface $goodsRequisitionMapper) {
		$this->goodsRequisitionMapper = $goodsRequisitionMapper;
	}
	
	public function listAllRequisitionApproval($userrole, $organisation_id)
	{
		return $this->goodsRequisitionMapper->findAllRequisitionApproval($userrole, $organisation_id);
	}

	public function listAllRequisitionApproved()
	{
		return $this->goodsRequisitionMapper->findAllRequisitionApproved();
	}

	public function listAllRequisitions($status, $userrole, $organisation_id)
	{
		return $this->goodsRequisitionMapper->findAllRequisitions($status, $userrole, $organisation_id);
	}
	public function listAllRequisitionsOnly ($status, $userrole, $organisation_id)
	{
		return $this->goodsRequisitionMapper->findAllRequisitionsForViewOnly($status, $userrole, $organisation_id);
	}
	public function calculateDepreciation($status, $userrole, $organisation_id)
	{
		return $this->goodsRequisitionMapper->calculateDepreciation($status, $userrole, $organisation_id);
	}
	public function listIndividualRequisition($status, $employee_details_id)
	{
		return $this->goodsRequisitionMapper->findIndividualRequisition($status, $employee_details_id);
	}

	public function listAllGoodsRequisition($tableName, $status, $employee_details_id)
	{
		return $this->goodsRequisitionMapper->listAllGoodsRequisition($tableName, $status, $employee_details_id);
	}

	public function updateIndGoodsRequisition($status, $previousStatus, $employee_details_id, $id) 
	{
		return $this->goodsRequisitionMapper->updateIndGoodsRequisition($status, $previousStatus, $employee_details_id, $id);
	}
	 
	public function findRequisitionApproval($id)
	{
		return $this->goodsRequisitionMapper->findRequisitionApproval($id);
		
	}
        
	public function findRequisitionDetails($id) 
	{
		return $this->goodsRequisitionMapper->findRequisitionDetails($id);;
	}

	public function listAllRequisitionForwardApproval($organisation_id)
	{
		return $this->goodsRequisitionMapper->findAllRequisitionForwardApproval($organisation_id);
	}


	public function getSupervisorEmailId($userrole, $departments_units_id)
	{
		return $this->goodsRequisitionMapper->getSupervisorEmailId($userrole, $departments_units_id);
	}


	public function getRequisitionApplicant($employee_details_id)
	{
		return $this->goodsRequisitionMapper->getRequisitionApplicant($employee_details_id);
	}
	
	public function findGoodsRequisition($id)
	{
		return $this->goodsRequisitionMapper->findGoodsRequisition($id);
	}

	public function findRequisitionForwardApproval($id)
	{
		return $this->goodsRequisitionMapper->findRequisitionForwardApproval($id);
		
	}

	public function listIndividualRequisitionForwarded($employee_details_id)
	{
		return $this->goodsRequisitionMapper->findIndividualRequisitionForwarded($employee_details_id);
	}

	public function getStaffRequisitionForwardDetails($id)
	{
		return $this->goodsRequisitionMapper->getStaffRequisitionForwardDetails($id);
	}

	public function listAllRequisitionForwarded($organisation_id)
	{
		return $this->goodsRequisitionMapper->findAllRequisitionForwarded($organisation_id);
	}

	public function getStaffGoodsRequisitionDetails($id)
	{
		return $this->goodsRequisitionMapper->getStaffGoodsRequisitionDetails($id);
	}


	public function getStaffGoodsRequisitionListDetails($id)
	{
		return $this->goodsRequisitionMapper->getStaffGoodsRequisitionListDetails($id);
	}

	public function getGoodsRequisitionDetails($id)
	{
		return $this->goodsRequisitionMapper->getGoodsRequisitionDetails($id);
	}

	public function listAllApprovedRequisitionForwarded($organisation_id)
	{
		return $this->goodsRequisitionMapper->findAllApprovedRequisitionForwarded($organisation_id);
	}

	public function findApprovedRequisitionForwarded($id)
	{
		return $this->goodsRequisitionMapper->findApprovedRequisitionForwarded($id);
	}

	public function getIndvReqPendingDetails($id)
	{
		return $this->goodsRequisitionMapper->getIndvReqPendingDetails($id);
	}

	public function getIndvReqApprovedDetails($id)
	{
		return $this->goodsRequisitionMapper->getIndvReqApprovedDetails($id);
	}

	public function getIndvReqRejectedDetails($id)
	{
		return $this->goodsRequisitionMapper->getIndvReqRejectedDetails($id);
	}

	public function getIndvReqForwardedDetails($id)
	{
		return $this->goodsRequisitionMapper->getIndvReqForwardedDetails($id);
	}

	public function getRequisitionPendingDetails($id)
	{
		return $this->goodsRequisitionMapper->getRequisitionPendingDetails($id);
	}

	public function getRequisitionApprovedDetails($id)
	{
		return $this->goodsRequisitionMapper->getRequisitionApprovedDetails($id);
	}

	public function getRequisitionRejectedDetails($id)
	{
		return $this->goodsRequisitionMapper->getRequisitionRejectedDetails($id);
	}

	public function getRequisitionForwardedDetails($id)
	{
		return $this->goodsRequisitionMapper->getRequisitionForwardedDetails($id);
	}
	
	public function saveRequisitionDetails(GoodsRequisition $goodsRequisitionObject, $item_sub_category_id, $item_name_id) 
	{
		return $this->goodsRequisitionMapper->saveRequisitionDetails($goodsRequisitionObject, $item_sub_category_id, $item_name_id);
	}

	public function saveRequisitionApproval(GoodsRequisitionApproval $goodsRequisitionObject) 
	{
		return $this->goodsRequisitionMapper->saveRequisitionApproval($goodsRequisitionObject);
	}


	public function approveGoodsRequisition($status, $previousStatus, $id, $employee_details_id)
	{
		return $this->goodsRequisitionMapper->approveGoodsRequisition($status, $previousStatus, $id, $employee_details_id);
	}

	public function saveRequisitionForwardApproval(GoodsRequisitionForwardApproval $goodsRequisitionObject) 
	{
		return $this->goodsRequisitionMapper->saveRequisitionForwardApproval($goodsRequisitionObject);
	}

	public function updateApprovedForwardedRequisition(GoodsRequisitionForwardApproval $goodsRequisitionObject)
	{
		return $this->goodsRequisitionMapper->updateApprovedForwardedRequisition($goodsRequisitionObject);
	}
	
	public function deleteGoodsRequisition(GoodsRequisition $goodsRequisitionObject)
	{
		return $this->goodsRequisitionMapper->deleteGoodsRequisition($goodsRequisitionObject);
	}

	public function listSelectData($tableName, $columnName)
	{
		return $this->goodsRequisitionMapper->listSelectData($tableName, $columnName);
	}
	// This will look for the email address of the head
	public function getEmail($userRole, $activity)
	{
	      return $this->goodsRequisitionMapper->getEmailAddress($userRole, $activity);
	}
}

<?php

namespace GoodsRequisition\Service;

use GoodsRequisition\Model\GoodsRequisitionProposal;
use GoodsRequisition\Model\GoodsRequisition;
use GoodsRequisition\Model\GoodsRequisitionApproval;
use GoodsRequisition\Model\GoodsRequisitionForwardApproval;

interface GoodsRequisitionServiceInterface
{

	/*
	* Getting the id for username
	*/
	
	public function getUserDetailsId($username);
	
	/*
	* Get organisation id based on the username
	*/
	
	public function getOrganisationId($username);

	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);

	/**
	 * Should return a set of all Goods Requisition that we can iterate over. 
	 *
	 * @return array|GoodsRequisitionInterface[]
	*/
	public function listAllRequisitions($status, $userrole, $organisation_id);

	/**
	* should be able to update the requistion of individual from Not Submitted to Pending 
	*/
	public function updateIndGoodsRequisition($status, $previousStatus, $employee_details_id, $id);

	/**
	 * Should return a set of all Goods Requisition that we can iterate over and new requisition. 
	 *
	 * @return array|GoodsRequisitionInterface[]
	*/

	public function listAllRequisitionApproval($userrole, $organisation_id);

	public function getSupervisorEmailId($userrole, $departments_units_id);

	public function getRequisitionApplicant($employee_details_id);
	
	public function findGoodsRequisition($id);

	public function listAllRequisitionForwarded($organisation_id);

	public function getStaffGoodsRequisitionDetails($id);

	public function getStaffGoodsRequisitionListDetails($id);

	public function getGoodsRequisitionDetails($id);

	/**
	 * Should return a set of all Goods Requisition that we can iterate over and new requisition approved.
	 *
	 * @return array|GoodsRequisitionInterface[]
	*/

	public function listAllRequisitionApproved();

	/**
	 * Should return a set of all Individual Goods Requisition that we can iterate over. 
	 *
	 * @return array|GoodsRequisitionInterface[]
	*/

	public function listIndividualRequisition($status, $employee_details_id);

	/**
	 * Should return a set of all Individual Goods Requisition that we can iterate over. 
	 *
	 * @return array|GoodsRequisitionInterface[]
	*/

	public function listAllGoodsRequisition($tableName, $status, $employee_details_id);

	/**
	 * Should return a single proposal
	 *
	 * @param int $id Identifier of the proposal that should be returned
	 * @return EmpWorkForceProposalInterface
	 */
	 
	public function findRequisitionApproval($id);
        
        
	/**
	 * Should return a single proposal
	 *
	 * @param int $id Identifier of the Proposal that should be returned
	 * @return EmpWorkForceProposalInterface
	 */
        
     public function findRequisitionDetails($id);

     public function findRequisitionForwardApproval($id);
      

     public function listAllRequisitionForwardApproval($organisation_id);

      public function listIndividualRequisitionForwarded($employee_details_id);

     public function getStaffRequisitionForwardDetails($id);

     public function listAllApprovedRequisitionForwarded($organisation_id);
	 
	 public function findApprovedRequisitionForwarded($id);

	 public function getIndvReqPendingDetails($id);

	 public function getIndvReqApprovedDetails($id);

	 public function getIndvReqRejectedDetails($id);

	 public function getIndvReqForwardedDetails($id);

	 public function getRequisitionPendingDetails($id);

	 public function getRequisitionApprovedDetails($id);

	 public function getRequisitionRejectedDetails($id);

	 public function getRequisitionForwardedDetails($id);
	 
	 public function saveRequisitionDetails(GoodsRequisition $goodsRequisitionObject, $item_sub_category_id, $item_name_id);


     public function saveRequisitionApproval(GoodsRequisitionApproval $goodsRequisitionObject);

     public function approveGoodsRequisition($status, $previousStatus, $id, $employee_details_id);
	 
	 public function deleteGoodsRequisition(GoodsRequisition $goodsRequisitionObject);

     public function saveRequisitionForwardApproval(GoodsRequisitionForwardApproval $goodsRequisitionObject);

     public function updateApprovedForwardedRequisition(GoodsRequisitionForwardApproval $goodsRequisitionObject);
	

	 /**
	 * Should return a set of all category type that we can iterate over. 
	 * 
	 * The purpose of the function is the category type for the dropdown select list
	 *
	 * @return array|GoodsTransactionInterface[]
	*/

	public function listSelectData($tableName, $columnName);
		
		
}
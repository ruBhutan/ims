<?php

namespace GoodsRequisition\Mapper;

use GoodsRequisition\Model\GoodsRequisition;
use GoodsRequisition\Model\GoodsRequisitionApproval;
use GoodsRequisition\Model\GoodsRequisitionForwardApproval;

interface GoodsRequisitionMapperInterface
{

	/*
	* Getting the id for username
	*/
	
	public function getUserDetailsId($username);
	
	/*
	* Get organisation id based on the username
	*/
	
	public function getOrganisationId($username);


	/**
	 * @param int/string $id
	 * @return GoodsRequisition
	 * throws \InvalidArugmentException
	 * 
	*/
	public function findRequisitionApproval($id);

	/**
	 * 
	 * @return array/ GoodsRequisition[]
	 */
	 
	public function findAllRequisitionApproval($organisation_id);

	/**
	*
	*@return array/ findAllRequisitions;
	*/

	public function findAllRequisitions($status, $organisation_id);

	/**
	 * 
	 * @return array/ GoodsRequisition[]
	 */
	 
	public function findAllRequisitionApproved();

    /*
    *@return array/ findIndividualRequisition
    */
	public function findIndividualRequisition($status, $employee_details_id);

    /*
	*@return array/ findIndividualRequisition
    */
	public function listAllGoodsRequisition($tableName, $status, $employee_details_id);

	/**
	*@return array/ GoodsRequisition[]
	* to update the individual requisition from Not Submitted to Pending
	*/
	public function updateIndGoodsRequisition($status, $previousStatus, $employee_details_id, $id);
        
        
	/**
	 * 
	 * @param type $id
	 * 
	 * to find details related to the GoodsRequisition
	 */
	
	public function findRequisitionDetails($id);

	 /*
    *@return array/ findIndividualRequisitionForwarded
    */
	public function findIndividualRequisitionForwarded($employee_details_id);

	/**
	 * 
	 * @return array/ GoodsRequisition[]
	 */
	 
	public function findAllRequisitionForwardApproval($organisation_id);
	
	public function findGoodsRequisition($id);

	public function findRequisitionForwardApproval($id);

	public function findAllRequisitionForwarded($organisation_id);

    public function findApprovedRequisitionForwarded($id);
    
    public function findAllApprovedRequisitionForwarded($organisation_id);

    /*
	*save Requisition Details 
	*/
	public function saveRequisitionDetails(GoodsRequisition $goodsRequisitionInterface, $item_sub_category_id, $item_name_id);


	public function getStaffGoodsRequisitionDetails($id);

	public function getStaffGoodsRequisitionListDetails($id);

	public function getGoodsRequisitionDetails($id);

	public function getStaffRequisitionForwardDetails($id);

	public function getIndvReqPendingDetails($id);

	public function getIndvReqApprovedDetails($id);

	public function getIndvReqRejectedDetails($id);

	public function getIndvReqForwardedDetails($id);

	public function getRequisitionPendingDetails($id);

	public function getRequisitionApprovedDetails($id);

	public function getRequisitionRejectedDetails($id);

	public function getRequisitionForwardedDetails($id);
    
    /*
	*save Requisition Approval Details 
	*/
	public function saveRequisitionApproval(GoodsRequisitionApproval $goodsRequisitionInterface);

	 /*
	*save Requisition Forward Approval Details 
	*/
	public function saveRequisitionForwardApproval(GoodsRequisitionForwardApproval $goodsRequisitionInterface);
	
	/*
	*save the details of approved requisition forwarded
	*/
	public function updateApprovedForwardedRequisition(GoodsRequisitionForwardApproval $goodsRequisitionInterface);

	/**
	 * 
	 * @return array/ GoodsRequisition[]
	 */

	public function listSelectData($tableName, $columnName);
}
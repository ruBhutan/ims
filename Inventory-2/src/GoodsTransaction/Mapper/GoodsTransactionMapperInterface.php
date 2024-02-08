<?php

namespace GoodsTransaction\Mapper;

use GoodsTransaction\Model\GoodsTransaction;
use GoodsTransaction\Model\ItemCategory;
use GoodsTransaction\Model\ItemSubCategory;
use GoodsTransaction\Model\ItemQuantityType;
use GoodsTransaction\Model\ItemName;
use GoodsTransaction\Model\ItemSupplier;
use GoodsTransaction\Model\ItemDonor;
use GoodsTransaction\Model\GoodsReceived;
use GoodsTransaction\Model\Itemreceivedpurchased;
use GoodsTransaction\Model\IssueGoods;
use GoodsTransaction\Model\DeptGoods;
use GoodsTransaction\Model\RequisitionIssueGoods;
use GoodsTransaction\Model\DeptIssueGoods;
use GoodsTransaction\Model\GoodsSurrender;
use GoodsTransaction\Model\GoodsTransfer;
use GoodsTransaction\Model\NominateSubStore;
use GoodsTransaction\Model\DeptGoodsSurrender;
use GoodsTransaction\Model\OrgGoodsTransfer;
use GoodsTransaction\Model\DisposeGoods;


interface GoodsTransactionMapperInterface
{

	/*
	* Getting the id for username
	*/
	
	public function getEmployeeDetailsId($emp_id);
	
	/*
	* Get organisation id based on the username
	*/
	
	public function getOrganisationId($username);

	/*
	* Get department id based on the username
	*/
	
	public function getDepartmentId($username);

	public function getDepartmentUnitId($username);

	public function getUserDetails($username, $usertype);

	public function getUserImage($username, $usertype);

	public function findCategory($id);

	/**
	 * 
	 * @return array/ GoodsTransaction[]
	 */
	 
	public function findAllCategory();
        
        
	/**
	 * 
	 * @param type $id
	 * 
	 * to find details related to the Item Category
	 */
	
	public function findCategoryDetails($id);


	public function findSubCategory($id);

	/**
	 * 
	 * @return array/ GoodsTransaction[]
	 */
	 
	public function findAllSubCategory($organisation_id);
        
        
	/**
	 * 
	 * @param type $id
	 * 
	 * to find details related to the Item Sub Category
	 */
	
	public function findSubCategoryDetails($id);

    /**
     * 
	 * @param type $id
	 * 
	 * to find details related to the Item Quantity Type
	 */
	
	public function findItemQuantityTypeDetails($id);


	public function findItemQuantityType($id);

	/**
	 * 
	 * @return array/ GoodsTransaction[]
	 */
	 
	public function findAllItemQuantityType($organisation_id);


	/**
     * 
	 * @param type $id
	 * 
	 * to find details related to the Item Name
	 */
	
	public function findItemNameDetails($id);


	public function findItemName($id);

	/**
	 * 
	 * @return array/ GoodsTransaction[]
	 */
	 
	public function findAllItemName($organisation_id);

	/**
     * 
	 * @param type $id
	 * 
	 * to find details related to the Item Supplier
	 */
	
	public function findItemSupplierDetails($id);

	public function activateBlackListedSupplier($status, $previousStatus, $id);

	public function findBlackListedSupplierDetails($id);


	public function findItemSupplier($id);

	/**
	 * 
	 * @return array/ GoodsTransaction[]
	 */
	 
	public function findAllItemSupplier($tableName, $organisation_id);

	public function findGoodsSupplied($id);

	public function findAllAddedSuppliedGoods($tableName, $status, $organisation_id, $id);

	public function findAddGoodsSupplied($id);

	public function updateAddGoodsSupplied($status, $previousStatus, $id);


	public function deleteAddGoodsSupplied($id);

	/**
	 * 
	 * @return array/ GoodsTransaction[]
	 */
	 
	public function findAllBlackListedSupplier($tableName, $organisation_id);


	public function getFileName($id);



	/**
     * 
	 * @param type $id
	 * 
	 * to find details related to the Item Donar
	 */
	
	public function findItemDonorDetails($id);


	public function findItemDonor($id);

	/**
	 * 
	 * @return array/ GoodsTransaction[]
	 */
	 
	public function findAllItemDonor($tableName, $organisation_id);

	public function listAllFixedAssetInStock($organisation_id);

	public function listAllConsumableAssetInStock($organisation_id);
    
    /**
	 * 
	 * @return array/ GoodsTransaction[]
	 */
	 
	public function findAllPurchasedGoodsInStock($goodsCategory, $goodsSubCategory, $item_name_id, $organisation_id);


	public function findGoodsInStockDetails($id);

	public function findDonatedGoodsInStockDetails($id);

	public function findTransferedGoodsInStockDetails($id);

	/**
	 * 
	 * @return array/ GoodsTransaction[]
	 */
	 
	public function findAllDonationGoodsInStock($organisation_id);

	public function listAllTransferedGoodsInStock($organisation_id);

	/**
     * 
	 * @param type $id
	 * 
	 * to find details related to the Issue Goods
	 */
	
	public function findIssueGoodsDetails($id);

	public function findEmpGoodsDetails($id);

	public function crossCheckEmpGoodsSurrender($status, $id, $employee_details_id);

	public function crossCheckEmpGoodsSurrenderQty($surrenderQuantity, $id, $employee_details_id);

	public function findGoodsSurrenderDetails($id);

	public function findSubStoreGoodsSurrenderDetails($id);

	public function findSubStoreSurrenderGoodsDetails($id);

	public function findIssueGoods($id);


	/**
	 * 
	 * @return array/ GoodsTransaction[]
	 */
	 
	public function findEmpAllFixedAssetLists($employee_details_id);

	public function listEmpAllConsumableGoodsLists($employee_details_id);

	/**
	 * 
	 * @return array/ GoodsTransaction[]
	 */
	 
	public function findAllSuppliedGoods($organisation_id);

	public function findAllSuppliedGoodsVG($organisation_id);


	/**
	 * 
	 * @return array/ GoodsTransaction[]
	 */
	 
	public function findSupplierAllGoodsDetails($id);


	/**
	 * 
	 * @return array/ GoodsTransaction[]
	 */
	 
	public function generateGoodsReceiptVoucher($id);


	/*
	* List Item to add Adhoc Goods Issue
	*/
	
	public function getItemList($itemName);

	/*
	* List Item to add Adhoc Goods Issue
	*/
	
	public function getStaffList($empName, $empId, $department, $organisation_id);

    public function findAdhocGoodsIssueDetails($id);

    public function findAdhocGoodsIssue($id);

    public function findRequisitionGoodsIssue($id);

    public function findSubStoreGoodsIssue($id);

    public function findSubStoreToIndGoodsIssue($id);
   
	public function getStaffDetails($id);

	public function getEmployeeDetails($id);

	public function getStaffGoodsDetails($id);


	public function getDeptStaffList($id, $organisation_id);

	public function getGoodsReceiverList($organisation_id);
	public function getDepartmentList($organisation_id);
	public function getGoodsReceiverDetails($organisation_id);


	/**
	 * 
	 * @return array/ GoodsTransaction[]
	 */

	public function findAllEmpIssuedGoods($departmentId, $organisation_id);
	 
	public function findAllAdhocIssueGoods($tableName, $status, $employee_details_id);


	public function findAllRequisitionIssueGoods($tableName, $status, $employee_details_id);


	public function listAllSubStoreNominee($tableName, $departments_id);


	public function getDeptList($department, $organisation_id);

	public function getDeptDetails($id);

	public function findAllDeptIssueGoods($tableName, $status, $employee_details_id);
	public function findAllIndIssueGoods($tableName, $status, $employee_details_id);

	public function listDeptGoodsInStock($departmentId, $organisation_id);

	public function listEmpAllSurrenderedGoods($organisation_id);

	public function findAllGoodsSurrenderList($employee_details_id);

	public function listAllEmpSurrenderGoods($organisation_id);


	public function listAllEmpSubStoreSurrenderGoods($organisation_id, $departments_units_id);


	public function getStaffGoodsSurrenderDetails($id);

	public function getGoodsSurrenderDetails($id);

	public function getSubStoreGoodsSurrenderDetails($departments_units_id, $id);

	public function goodsSupplierDetails($id);

	public function findGoodsSurrenderList($id);

	public function getSubStoreDetails($id);

	public function getSubStoreSurrenderGoodsDetails($id);

	public function listAllSubStoreSurrenderGoods($organisation_id);

	public function listAllDeptTransferFrom($departments_units_id);

	public function listAllDeptTransferFromStatus($departments_units_id);

	public function listAllOrgGoodsTransferTo($organisation_id);

	public function listAllOrgGoodsTransferFrom($organisation_id);

	public function listAllDeptTransferTo($departments_units_id);

	public function listAllOrgGoodsTransferApproval($organisation_id);

	public function findOrgGoodsTransferDetails($id);

	public function findOrgGoodsTransferToDetails($id);

	public function findOrgGoodsTransferFromDetails($id);


    /**
	 * 
	 * @return array/ GoodsTransaction[]
	 */
	 
	//public function findAllSurrenderGoods();

	/**
     * 
	 * @param type $id
	 * 
	 * to find details related to the Goods Surrender
	 */
	
	//public function findEmpGoodsDetails($id);

	public function crossCheckItemCategory($itemType, $majorClass);

	 /**
	 * 
	 * @param type $GoodsTransactionInterface
	 * 
	 * to save Item Category
	 */
	public function saveItemCategory(ItemCategory $goodsTransactionInterface);

	public function crossCheckItemSubCategory($subCategoryType, $categoryType, $organisation_id);

    
    /**
	 * 
	 * @param type $GoodsTransactionInterface
	 * 
	 * to save Item Sub Category
	 */
	public function saveItemSubCategory(ItemSubCategory $goodsTransactionInterface, $item_category_id);

	//public function deleteItemSubCategory(ItemSubCategory $goodsTransactionInterface);
	public function deleteItemSubCategory(GoodsTransaction $goodsTransactionInterface);

	public function crossCheckItemQuantityType($quantityType, $organisation_id);


	/**
	 *
	 * @param type $GoodsTransactionInterface
	 * 
	 * to save Item Quantity Type
	 */
	public function saveItemQuantityType(ItemQuantityType $goodsTransactionInterface);

	public function deleteItemQuantityType(GoodsTransaction $goodsTransactionInterface);

	public function crossCheckItemName($itemName, $item_sub_category_id, $organisation_id);

	/**
	 * 
	 * @param type $GoodsTransactionInterface
	 * 
	 * to save Item Name
	 */
	public function saveItemName(ItemName $goodsTransactionInterface, $item_category_id, $item_sub_category_id);

	public function deleteItemName(GoodsTransaction $goodsTransactionInterface);

	public function crossCheckItemSupplier($supplierName, $supplierLicense, $organisation_id);

	/**
	 * 
	 * @param type $GoodsTransactionInterface
	 * 
	 * to save Item Supplier
	 */
	public function saveItemSupplier(ItemSupplier $goodsTransactionInterface);

	/**
	 * 
	 * @param type $GoodsTransactionInterface
	 * 
	 * to save Black Listed Item Supplier
	 */
	public function saveBlackListedSupplier(ItemSupplier $goodsTransactionInterface);

	public function deleteItemSupplier(GoodsTransaction $goodsTransactionInterface);

	public function crossCheckItemDonor($donorName, $organisation_id);


	/**
	 * 
	 * @param type $GoodsTransactionInterface
	 * 
	 * to save Item Donar
	 */
	public function saveItemDonor(ItemDonor $goodsTransactionInterface);

	public function deleteItemDonor(GoodsTransaction $goodsTransactionInterface);


	/**
	 * 
	 * @param type $GoodsTransactionInterface
	 * 
	 * to save Goods Received Purchased
	 */
	public function saveGoodsReceivedPurchased(GoodsReceived $goodsPurchasedObject);


	/**
	 * 
	 * @param type $GoodsTransactionInterface
	 * 
	 * to save Goods Supplied
	 */
	public function saveGoodsSupplied(GoodsReceived $goodsTransactionInterface, $item_category_id, $item_sub_category_id, $item_name_id);


	public function saveGoodsReceiptVoucherNo($id, $organisation_id);

	/**
	 * 
	 * @param type $GoodsTransactionInterface
	 * 
	 * to save Goods Received Donation
	 */
	public function saveGoodsReceivedDonation(GoodsReceived $goodsTransactionInterface, $item_category_id, $item_sub_category_id, $item_name_id);

	public function getGoodsIssueToEmployeeId($tableName, $employee_details_id);

	/**
	 * 
	 * @param type $GoodsTransactionInterface
	 * 
	 * to save Issue Goods
	 */
	public function saveAdhocIssueGoods(IssueGoods $goodsTransactionInterface, $goods_received_id, $employee_details_id);

	public function deleteAdhocGoodsIssue(GoodsTransaction $goodsTransactionInterface);


	public function updateAdhocGoodsIssue($data_to_insert, $data_to_insert1);

	public function saveRequisitionIssueGoods(RequisitionIssueGoods $goodsTransactionInterface, $goods_received_id, $employee_details_id, $goods_requisition_details_id);

	public function deleteRequisitionGoodsIssue(GoodsTransaction $goodsTransactionInterface);

	public function updateRequisitionGoodsIssue($data_to_insert, $data_to_insert1);

	public function getGoodsSupplierDetails($id);


	public function getSuppliedGoodsList($id);

	public function getStoreManagerDetails($employee_details_id);

    /**
	 * 
	 * @param type $GoodsTransactionInterface
	 * 
	 * to save Issue Goods
	 */
	public function saveSubStoreIssueGoods(DeptGoods $goodsTransactionInterface, $goods_received_id, $goods_received_by);

	public function updateSubStoreIssueGoods($data_to_insert);

	public function saveSubStoreNomination(NominateSubStore $goodsTransactionInterface);

	public function deleteSubStoreGoodsIssue(GoodsTransaction $goodsTransactionInterface);

	public function deleteSubStoreToIndIssueGoods(GoodsTransaction $goodsTransactionInterface);

	public function saveSubStoreToIndIssueGoods(DeptIssueGoods $goodsTransactionInterface, $departments_id, $employee_details_id, $itemName);

	public function updateSubStoreToIndIssueGoods($data_to_insert, $data_to_insert1);

	public function updateDeptIssueGoods($status, $previousStatus);


	/**
	 * 
	 * @param type $GoodsTransactionInterface
	 * 
	 * to save Goods Surrender
	 */
	public function saveGoodsSurrender(GoodsSurrender $goodsTransactionInterface);

	public function updateSubStoreGoodsSurrender(DeptGoodsSurrender $goodsTransactionInterface, $id);

	public function crossCheckDeptGoodsSurrender($status, $id, $departments_units_id);

	public function crossCheckDeptGoodsSurrenderQty($id, $surrender_quantity);

	public function crossCheckOrgGoodsTransferedQty($id, $itemTransferedQty);

	public function saveDeptGoodsSurrender(DeptGoodsSurrender $goodsTransactionInterface);


	/**
	 * 
	 * @return array/ GoodsTransaction[] to select ItemCategory
	 */

	public function listSelectEmpData($organisation_id);

	public function listSelectData($tableName, $columnName);

	public function listSelectData2($tableName, $columnName, $organisation_id);

	public function listSelectAddSubStoreData($tableName, $organisation_id);

	public function listSelectSubStoreToIndData($tableName, $departments_units_id, $employee_details_id);

	public function listSelectDataDetails($tableName, $columnName, $organisation_id);

	public function listSelectEmpDetails($tableName, $organisation_id);

	public function updateEmpGoodsSurrender($status, $previousStatus, $id);

	public function updateEmpSubStoreSurrender($status, $previousStatus, $id);

	public function updateEmpConsumableGoods($status, $previousStatus, $id);

	public function listSelectItemVerify($organisation_id);

	public function listSelectData1($tableName, $columnName);

	// For transfer of Goods
	public function findGoodsTransfer($id);

	public function findAllGoodsTransfer();

	public function findGoodsTransferDetail($id);

	public function findDeptGoodsTransferFromDetails($id);

	public function findDeptGoodsTransferToDetails($id);

	public function findTransferGoodsApprovalStatus();

	public function crossCheckDeptGoodsTransfer($status, $id, $departments_units_id);

	public function crossCheckDeptGoodsTransferQty($id, $transfer_quantity);

	public function saveDeptGoodsTransfer(GoodsTransfer $goodsTransactionInterface, $department_to_id, $employee_to_id);

	public function updateDeptGoodsTransfer(GoodsTransfer $goodsTransactionInterface, $id);

	public function crossCheckOrgGoodsTransfer($status, $id);

	public function crossCheckOrgGoodsTransferQty($transfer_quantity, $id);

	public function saveOrgGoodsTransfer(OrgGoodsTransfer $goodsTransactionInterface);

	public function updateOrgGoodsTransfer(OrgGoodsTransfer $goodsTransactionInterface, $id, $item_category_id, $item_sub_category_id, $item_name_id, $itemTransferedId, $itemReceivedType, $organisation_id);

	public function rejectOrgFromGoodsTransfer($status, $previousStatus, $id, $employee_details_id);

	public function updateDisposeGoods(DisposeGoods $goodsTransactionInterface, $id);


	// For Departments
	public function findDeptGoodsDetails($id);

	public function findOrgGoodsDetails($id);

	public function findDeptAllGoods($tableName, $departments_units_id);

	public function getOrganisationDocument($tableName, $document_type, $organisation_id);

	public function getOrganizationDetails($id);

	
}
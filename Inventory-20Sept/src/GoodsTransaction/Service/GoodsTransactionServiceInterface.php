<?php

namespace GoodsTransaction\Service;

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
use GoodsTransaction\Model\RequisitionIssueGoods;
use GoodsTransaction\Model\DeptGoods;
use GoodsTransaction\Model\DeptIssueGoods;
use GoodsTransaction\Model\GoodsSurrender;
use GoodsTransaction\Model\GoodsTransfer;
use GoodsTransaction\Model\NominateSubStore;
use GoodsTransaction\Model\DeptGoodsSurrender;
use GoodsTransaction\Model\OrgGoodsTransfer;
use GoodsTransaction\Model\DisposeGoods;


interface GoodsTransactionServiceInterface
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

	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);


	/**
	 * Should return a set of all Item Category that we can iterate over. 
	 *
	 * @return array|GoodsTransactionInterface[]
	*/

    public function listAllItemCategory();



	/**
	 * Should return a single Item Category
	 *
	 * @param int $id Identifier of the Item Category that should be returned
	 * @return GoodsTransactionInterface
	 */
	 
	public function findCategory($id);
        
        
	/**
	 * Should return a single Item Category
	 *
	 * @param int $id Identifier of the Item Category that should be returned
	 * @return GoodsTransactionInterface
	 */
        
    public function findCategoryDetails($id); 

    /**
	 * Should return a set of all Item Sub Category that we can iterate over. 
	 *
	 * @return array|GoodsTransactionInterface[]
	*/

    public function listAllItemSubCategory($organisation_id);


	/**
	 * Should return a single Item Sub Category
	 *
	 * @param int $id Identifier of the Item Sub Category that should be returned
	 * @return GoodsTransactionInterface
	 */
	 
	public function findSubCategory($id);
        
        
	/**
	 * Should return a single Item Sub Category
	 *
	 * @param int $id Identifier of the Item Sub Category that should be returned
	 * @return GoodsTransactionInterface
	 */
        
    public function findSubCategoryDetails($id); 


    /**
	 * Should return a set of all Item Quantity Type that we can iterate over. 
	 *
	 * @return array|GoodsTransactionInterface[]
	*/

    public function listAllItemQuantityType($organisation_id);


	/**
	 * Should return a single Item Quantity Type
	 *
	 * @param int $id Identifier of the Item Quantity Type that should be returned
	 * @return GoodsTransactionInterface
	 */
	 
	public function findItemQuantityType($id);
        
        
	/**
	 * Should return a single Item Quantity Type
	 *
	 * @param int $id Identifier of the Item Quantity Type that should be returned
	 * @return GoodsTransactionInterface
	 */
        
    public function findItemQuantityTypeDetails($id); 

    /**
	 * Should return a set of all Item Name that we can iterate over. 
	 *
	 * @return array|GoodsTransactionInterface[]
	*/

    public function listAllItemName($organisation_id);


	/**
	 * Should return a single Item Name
	 *
	 * @param int $id Identifier of the Item Name that should be returned
	 * @return GoodsTransactionInterface
	 */
	 
	public function findItemName($id);
        
        
	/**
	 * Should return a single Item Name
	 *
	 * @param int $id Identifier of the Item Name that should be returned
	 * @return GoodsTransactionInterface
	 */
        
    public function findItemNameDetails($id); 
    

    /**
	 * Should return a set of all Item Supplier that we can iterate over. 
	 *
	 * @return array|GoodsTransactionInterface[]
	*/

    public function listAllItemSupplier($tableName, $organisation_id);


    public function getFileName($id);

     /**
	 * Should return a set of all Item Supplier that we can iterate over. 
	 *
	 * @return array|GoodsTransactionInterface[]
	*/

    public function listAllBlackListedSupplier($tableName, $organisation_id);



	/**
	 * Should return a single Item Supplier
	 *
	 * @param int $id Identifier of the Item Supplier that should be returned
	 * @return GoodsTransactionInterface
	 */
	 
	public function findItemSupplier($id);

	public function findBlackListedSupplierDetails($id);
        
        
	/**
	 * Should return a single Item Supplier
	 *
	 * @param int $id Identifier of the Item Supplier that should be returned
	 * @return GoodsTransactionInterface
	 */
        
    public function findItemSupplierDetails($id);


    public function activateBlackListedSupplier($status, $previousStatus, $id);


     /**
	 * Should return a set of all Item Donar that we can iterate over. 
	 *
	 * @return array|GoodsTransactionInterface[]
	*/

    public function listAllItemDonor($tableName, $organisation_id);


	/**
	 * Should return a single Item Donar
	 *
	 * @param int $id Identifier of the Item Donar that should be returned
	 * @return GoodsTransactionInterface
	 */
	 
	public function findItemDonor($id);
        
        
	/**
	 * Should return a single Item Donar
	 *
	 * @param int $id Identifier of the Item Donar that should be returned
	 * @return GoodsTransactionInterface
	 */
        
    public function findItemDonorDetails($id);  
    

    public function listAllSuppliedGoods($organisation_id);

    public function listAllSuppliedGoodsVG($organisation_id);

    public function findGoodsSupplied($id);

    public function findAllAddedSuppliedGoods($tableName, $status, $organisation_id, $id);

    public function findAddGoodsSupplied($id);

    public function deleteAddGoodsSupplied($id);


    public function updateAddGoodsSupplied($status, $previousStatus, $id);

    public function getGoodsSupplierDetails($id);

    public function goodsSupplierDetails($id);

    public function getSuppliedGoodsList($id);

    public function getStoreManagerDetails($employee_details_id);

    public function listSupplierAllGoodsDetails($id);

    public function printGoodsReceiptVoucher($id);

	public function listAllFixedAssetInStock($organisation_id);
	
	public function listAllConsumableAssetInStock($organisation_id);

     /**
	 * Should return a set of all Goods In Stock that we can iterate over. 
	 *
	 * @return array|GoodsTransactionInterface[]
	*/

    public function listAllPuchasedGoodsInStock($goodsCategory, $goodsSubCategory, $item_name_id, $organisation_id);

    public function findGoodsInStockDetails($id);

    public function findDonatedGoodsInStockDetails($id);

    public function findTransferedGoodsInStockDetails($id);

    /**
	 * Should return a set of all Goods In Stock that we can iterate over. 
	 *
	 * @return array|GoodsTransactionInterface[]
	*/

    public function listAllDonationGoodsInStock($organisation_id);

    public function listAllTransferedGoodsInStock($organisation_id);

    public function listDeptGoodsInStock($departmentId, $organisation_id);


     /**
	 * Should return a set of all Staff Nominee that we can iterate over. 
	 *
	 * @return array|GoodsTransactionInterface[]
	*/
    public function listAllSubStoreNominee($tableName, $departments_id);

     /**
	 * Should return a set of all Issue Gooods that we can iterate over. 
	 *
	 * @return array|GoodsTransactionInterface[]
	*/

    public function listAllEmpIssuedGoods($departmentId, $organisation_id);


    /*
	 * List Item to add Issue Goods
	 */
	
	public function getItemList($itemName);

	/*
	 * List Staff to add Issue Goods
	 */
	
	public function getStaffList($empName, $empId, $department, $organisation_id);

    public function findAdhocGoodsIssueDetails($id);

    public function findAdhocGoodsIssue($id);

    public function findRequisitionGoodsIssue($id);

    public function findSubStoreGoodsIssue($id);

    public function findSubStoreToIndGoodsIssue($id);

	/*
	* Staff Details to display name, etc
	*/
	public function getStaffDetails($id);


	public function getEmployeeDetails($id);

	/*
	* Staff Details to display name, etc
	*/
	public function getDeptStaffList($id, $organisation_id);

	public function getGoodsReceiverList($organisation_id);

	public function getDepartmentList($organisation_id);

	public function getGoodsReceiverDetails($organisation_id);


	/*
	* Staff Details with item issued list details to display name, etc
	*/
	public function getStaffGoodsDetails($id);



	/**
	 * Should return a set of all item that we can iterate over. 
	 *
	 * @return array|GoodsTransactionInterface[]
	*/
	
	public function listAllAdhocIssueGoods($tableName, $status, $employee_details_id);


	/**
	 * Should return a set of all item that we can iterate over. 
	 *
	 * @return array|GoodsTransactionInterface[]
	*/
	
	public function listAllRequisitionIssueGoods($tableName, $status, $employee_details_id);

	/**
	 * Should return a single Issue Goods
	 *
	 * @param int $id Identifier of the Issue Goods that should be returned
	 * @return GoodsTransactionInterface
	 */
	 
	public function findIssueGoods($id);
        
        
	/**
	 * Should return a single Issue Goods
	 *
	 * @param int $id Identifier of the Issue Goods that should be returned
	 * @return GoodsTransactionInterface
	 */
        
    public function findIssueGoodsDetails($id);


    /**
	 * Should return a single Issue Goods
	 *
	 * @param int $id Identifier of the Issue Goods that should be returned
	 * @return GoodsTransactionInterface
	 */
        
    public function findEmpGoodsDetails($id); 


    public function crossCheckEmpGoodsSurrender($status, $id, $employee_details_id);

    public function crossCheckEmpGoodsSurrenderQty($surrenderQuantity, $id, $employee_details_id);

    public function findGoodsSurrenderDetails($id);

    public function findSubStoreGoodsSurrenderDetails($id);


    public function findSubStoreSurrenderGoodsDetails($id);
	 
	 /**
	 * @param GoodsTransactionInterface $goodsTransactionObject
	 *
	 * @param GoodsTransactionInterface $goodsTransactionObject
	 * @return GoodsTransactionInterface
	 * @throws \Exception
	 */

	  /**
	 * Should return a set of all Issue Gooods that we can iterate over. 
	 *
	 * @return array|GoodsTransactionInterface[]
	*/

    public function listEmpAllFixedAssetLists($employee_details_id);

    public function listEmpAllConsumableGoodsLists($employee_details_id);

    /*
	 * List Department to add Issue Goods
	 */
	
	public function getDeptList($department, $organisation_id);


	/*
	* Staff Details to display name, etc
	*/
	public function getDeptDetails($id);


	/**
	 * Should return a set of all item that we can iterate over. 
	 *
	 * @return array|GoodsTransactionInterface[]
	*/
	
	public function listAllDeptIssueGoods($tableName, $status, $employee_details_id);

	public function listAllIndIssueGoods($tableName, $status, $employee_details_id);

	public function listEmpAllSurrenderedGoods($organisation_id);


	public function findAllGoodsSurrenderList($employee_details_id);


	public function listAllEmpSurrenderGoods($organisation_id);


	public function listAllEmpSubStoreSurrenderGoods($organisation_id, $departments_units_id);


	/*
	* Staff Details with applied item surrender list details to display name, etc
	*/
	public function getStaffGoodsSurrenderDetails($id);

	public function getGoodsSurrenderDetails($id);


	public function getSubStoreGoodsSurrenderDetails($departments_units_id, $id);


	public function findGoodsSurrenderList($id);

	public function listAllSubStoreSurrenderGoods($organisation_id);

	public function getSubStoreDetails($id);

	public function getSubStoreSurrenderGoodsDetails($id);

	public function listAllDeptTransferFrom($departments_units_id);

	public function listAllDeptTransferFromStatus($departments_units_id);

	public function listAllOrgGoodsTransferTo($organisation_id);

	public function listAllOrgGoodsTransferFrom($organisation_id);

	public function listAllDeptTransferTo($departments_units_id);

	public function listAllOrgGoodsTransferApproval($organisation_id);

	public function findOrgGoodsTransferDetails($id);

	public function findOrgGoodsTransferToDetails($id);

	public function findOrgGoodsTransferFromDetails($id);

	public function crossCheckItemCategory($itemType, $majorClass);

    public function saveItemCategory(ItemCategory $goodsTransactionObject);

    public function crossCheckItemSubCategory($subCategoryType, $categoryType, $organisation_id);

	public function saveItemSubCategory(ItemSubCategory $goodsTransactionObject, $item_category_id);
	
	public function deleteItemSubCategory(GoodsTransaction $goodsTransactionObject);

	public function crossCheckItemQuantityType($quantityType, $organisation_id);

	//public function to saveItemQuantityType(ItemQuantityType $goodsTransactionObject);
	public function saveItemQuantityType(ItemQuantityType $goodsTransactionObject);

	//public function deleteItemSubCategory(ItemCategory $goodsTransactionObject);
	public function deleteItemQuantityType(GoodsTransaction $goodsTransactionObject);

	public function crossCheckItemName($itemName, $item_sub_category_id, $organisation_id);

    //public function to saveItemName(ItemName $goodsTransactionObject);
	public function saveItemName(ItemName $goodsTransactionObject, $item_category_id, $item_sub_category_id);
	
	public function deleteItemName(GoodsTransaction $goodsTransactionObject);

	public function crossCheckItemSupplier($supplierName, $supplierLicense, $organisation_id);

	public function saveItemSupplier(ItemSupplier $goodsTransactionObject); 

	public function saveBlackListedSupplier(ItemSupplier $goodsTransactionObject);  

	public function deleteItemSupplier(GoodsTransaction $goodsTransactionObject);

	public function crossCheckItemDonor($donorName, $organisation_id);

	public function saveItemDonor(ItemDonor $goodsTransactionObject);
	public function deleteItemDonor(GoodsTransaction $goodsTransactionObject);


	public function saveGoodsReceivedPurchased(GoodsReceived $goodsPurchasedObject);

	public function saveGoodsSupplied(GoodsReceived $goodsTransactionObject, $item_category_id, $item_sub_category_id, $item_name_id);

	public function saveGoodsReceiptVoucherNo($id, $organisation_id);


	public function saveGoodsReceivedDonation(GoodsReceived $goodsTransactionObject, $item_category_id, $item_sub_category_id, $item_name_id);

	public function getGoodsIssueToEmployeeId($tableName, $employee_details_id);


	public function saveAdhocIssueGoods(IssueGoods $goodsTransaction, $goods_received_id, $employee_details_id);

	public function deleteAdhocGoodsIssue(GoodsTransaction $goodsTransaction);

	public function updateAdhocGoodsIssue($data_to_insert, $data_to_insert1);

	public function saveRequisitionIssueGoods(RequisitionIssueGoods $goodsTransaction, $goods_received_id, $employee_details_id, $goods_requisition_details_id);

	public function deleteRequisitionGoodsIssue(GoodsTransaction $goodsTransaction);

	public function updateRequisitionGoodsIssue($data_to_insert, $data_to_insert1);

	public function saveSubStoreIssueGoods(DeptGoods $goodsTransactionObject, $goods_received_id, $goods_received_by);

	public function updateSubStoreIssueGoods($data_to_insert);

	public function saveSubStoreNomination(NominateSubStore $goodsTransactionObject);

	public function deleteSubStoreGoodsIssue(GoodsTransaction $goodsTransactionObject);

	public function deleteSubStoreToIndIssueGoods(GoodsTransaction $goodsTransactionObject);

	public function saveSubStoreToIndIssueGoods(DeptIssueGoods $goodsTransactionObject, $departments_id, $employee_details_id, $itemName);

	public function updateSubStoreToIndIssueGoods($data_to_insert, $data_to_insert1);

	public function updateDeptIssueGoods($status, $previousStatus);

	public function saveGoodsSurrender(GoodsSurrender $goodsTransactionObject);

	public function updateSubStoreGoodsSurrender(DeptGoodsSurrender $goodsTransactionObject, $id);

	public function crossCheckDeptGoodsSurrender($status, $id, $departments_units_id);

	public function crossCheckDeptGoodsSurrenderQty($id, $surrender_quantity);

	public function crossCheckOrgGoodsTransferedQty($id, $itemTransferedQty);

	public function saveDeptGoodsSurrender(DeptGoodsSurrender $goodsTransactionObject);
	 /**
	 * Should return a set of all category type that we can iterate over. 
	 * 
	 * The purpose of the function is the category type for the dropdown select list
	 *
	 * @return array|GoodsTransactionInterface[]
	*/

	public function listSelectEmpData($organisation_id);

	public function listSelectData($tableName, $columnName);

	public function listSelectData2($tableName, $columnName,$organisation_id);

	public function listSelectAddSubStoreData($tableName, $organisation_id);

	public function listSelectSubStoreToIndData($tableName, $departments_units_id, $employee_details_id);

	public function listSelectDataDetails($tableName, $columnName, $organisation_id);

	public function listSelectEmpDetails($tableName, $organisation_id);

	public function updateEmpGoodsSurrender($status, $previousStatus, $id);

	public function updateEmpSubStoreSurrender($status, $previousStatus, $id);

	public function updateEmpConsumableGoods($status, $previousStatus, $id);

	public function listSelectItemVerify($organisation_id);

	public function listSelectData1($tableName, $columnName);


	//For Transfer of Goods 
	public function findGoodsTransfer($id);
               
    public function findAllGoodsTransfer();

    public function findGoodsTransferDetail($id);

    public function findDeptGoodsTransferFromDetails($id);

    public function findDeptGoodsTransferToDetails($id);

    public function findTransferGoodsApprovalStatus();

    public function crossCheckDeptGoodsTransfer($status, $id, $departments_units_id);

    public function crossCheckDeptGoodsTransferQty($id, $transfer_quantity);
		 	 
	public function saveDeptGoodsTransfer(GoodsTransfer $goodsTransactionObject, $department_to_id, $employee_to_id);


	public function updateDeptGoodsTransfer(GoodsTransfer $goodsTransactionObject, $id);

	public function crossCheckOrgGoodsTransfer($status, $id);

	public function crossCheckOrgGoodsTransferQty($transfer_quantity, $id);

	public function saveOrgGoodsTransfer(OrgGoodsTransfer $goodsTransactionObject);

	public function updateOrgGoodsTransfer(OrgGoodsTransfer $goodsTransactionObject, $id, $item_category_id, $item_sub_category_id, $item_name_id, $itemTransferedId, $itemReceivedType, $organisation_id);

	public function rejectOrgFromGoodsTransfer($status, $previousStatus, $id, $employee_details_id);

	public function updateDisposeGoods(DisposeGoods $goodsTransactionObject, $id);


	//For Department  
	public function findDeptGoodsDetails($id);

	public function findOrgGoodsDetails($id);
               
    public function findDeptAllGoods($tableName, $departments_units_id);

    public function getOrganisationDocument($tableName, $document_type, $organisation_id);

    public function getOrganizationDetails($id);

}
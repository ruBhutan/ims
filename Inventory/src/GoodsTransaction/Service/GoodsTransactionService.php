<?php

namespace GoodsTransaction\Service;

use GoodsTransaction\Mapper\GoodsTransactionMapperInterface;
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

class GoodsTransactionService implements GoodsTransactionServiceInterface
{
	/**
	 * @var \Blog\Mapper\PostMapperInterface
	*/
	
	protected $goodsTransactionMapper;
	
	public function __construct(GoodsTransactionMapperInterface $goodsTransactionMapper) {
		$this->goodsTransactionMapper = $goodsTransactionMapper;
	}

	public function getEmployeeDetailsId($emp_id)
	{
		return $this->goodsTransactionMapper->getEmployeeDetailsId($emp_id);
	}
	
	public function getOrganisationId($username)
	{
		return $this->goodsTransactionMapper->getOrganisationId($username);
	}

	public function getDepartmentId($username)
	{
		return $this->goodsTransactionMapper->getDepartmentId($username);
	}


	public function getDepartmentUnitId($username)
	{
		return $this->goodsTransactionMapper->getDepartmentUnitId($username);
	}

	public function getUserDetails($username, $usertype)
	{
		return $this->goodsTransactionMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->goodsTransactionMapper->getUserImage($username, $usertype);
	}

	public function listAllItemCategory()
	{
		return $this->goodsTransactionMapper->findAllCategory();
	}


	public function findCategory($id)
	{
		return $this->goodsTransactionMapper->findCategory($id);
		
	}
        
	public function findCategoryDetails($id) 
	{
		return $this->goodsTransactionMapper->findCategory($id);
	}

	public function listAllItemSubCategory($organisation_id)
	{
		return $this->goodsTransactionMapper->findAllSubCategory($organisation_id);
	}

	public function findSubCategory($id)
	{
		return $this->goodsTransactionMapper->findSubCategory($id);
		
	}
        
	public function findSubCategoryDetails($id) 
	{
		return $this->goodsTransactionMapper->findSubCategory($id);
	}


	public function listAllItemQuantityType($organisation_id)
	{
		return $this->goodsTransactionMapper->findAllItemQuantityType($organisation_id);
	}

	public function findItemQuantityType($id)
	{
		return $this->goodsTransactionMapper->findItemQuantityType($id);
		
	}
        
	public function findItemQuantityTypeDetails($id) 
	{
		return $this->goodsTransactionMapper->findItemQuantityType($id);;
	}

	public function listAllItemName($organisation_id)
	{
		return $this->goodsTransactionMapper->findAllItemName($organisation_id);
	}

	public function findItemName($id)
	{
		return $this->goodsTransactionMapper->findItemName($id);
		
	}
        
	public function findItemNameDetails($id) 
	{
		return $this->goodsTransactionMapper->findItemNameDetails($id);
	}

	public function listAllItemSupplier($tableName, $organistion_id)
	{
		return $this->goodsTransactionMapper->findAllItemSupplier($tableName, $organistion_id);
	}

	public function getFileName($id)
	{
		return $this->goodsTransactionMapper->getFileName($id);
	}


	public function listAllBlackListedSupplier($tableName, $organistion_id)
	{
		return $this->goodsTransactionMapper->findAllBlackListedSupplier($tableName, $organistion_id);
	}

	public function findItemSupplier($id)
	{
		return $this->goodsTransactionMapper->findItemSupplier($id);
		
	}

	public function findBlackListedSupplierDetails($id)
	{
		return $this->goodsTransactionMapper->findBlackListedSupplierDetails($id);
	}
        
	public function findItemSupplierDetails($id) 
	{
		return $this->goodsTransactionMapper->findItemSupplier($id);;
	}


	public function activateBlackListedSupplier($status, $previousStatus, $id)
	{
		return $this->goodsTransactionMapper->activateBlackListedSupplier($status, $previousStatus, $id);
	}


	public function listAllItemDonor($tableName, $organistion_id)
	{
		return $this->goodsTransactionMapper->findAllItemDonor($tableName, $organistion_id);
	}

	public function findItemDonor($id)
	{
		return $this->goodsTransactionMapper->findItemDonor($id);
		
	}
        
	public function findItemDonorDetails($id) 
	{
		return $this->goodsTransactionMapper->findItemDonor($id);;
	}

	public function listAllSuppliedGoods($organistion_id)
	{
		return $this->goodsTransactionMapper->findAllSuppliedGoods($organistion_id);
	}

	public function listAllSuppliedGoodsVG($organistion_id)
	{
		return $this->goodsTransactionMapper->findAllSuppliedGoodsVG($organistion_id);
	}

	public function findGoodsSupplied($id)
	{
		return $this->goodsTransactionMapper->findGoodsSupplied($id);
	}

	public function findAllAddedSuppliedGoods($tableName, $status, $organisation_id, $id)
	{
		return $this->goodsTransactionMapper->findAllAddedSuppliedGoods($tableName, $status, $organisation_id, $id);
	}

	public function findAddGoodsSupplied($id)
	{
		return $this->goodsTransactionMapper->findAddGoodsSupplied($id);
	}


	public function updateAddGoodsSupplied($status, $previousStatus, $id)
	{
		return $this->goodsTransactionMapper->updateAddGoodsSupplied($status, $previousStatus, $id);
	}


	public function goodsSupplierDetails($id)
	{
		return $this->goodsTransactionMapper->goodsSupplierDetails($id);
	}

	public function getGoodsSupplierDetails($id)
	{
		return $this->goodsTransactionMapper->getGoodsSupplierDetails($id);
	}

	public function getSuppliedGoodsList($id)
	{
		return $this->goodsTransactionMapper->getSuppliedGoodsList($id);
	}


	public function getStoreManagerDetails($employee_details_id)
	{
		return $this->goodsTransactionMapper->getStoreManagerDetails($employee_details_id);
	}


	public function listSupplierAllGoodsDetails($id)
	{
		return $this->goodsTransactionMapper->findSupplierAllGoodsDetails($id);
	}

	public function printGoodsReceiptVoucher($id)
	{
		return $this->goodsTransactionMapper->generateGoodsReceiptVoucher($id);
	}

	public function listAllFixedAssetInStock($organisation_id)
	{
		return $this->goodsTransactionMapper->listAllFixedAssetInStock($organisation_id);
	}

	public function listAllConsumableAssetInStock($organisation_id)
	{
		return $this->goodsTransactionMapper->listAllConsumableAssetInStock($organisation_id);
	}

	public function listAllPuchasedGoodsInStock($goodsCategory, $goodsSubCategory, $item_name_id, $organisation_id)
	{
		return $this->goodsTransactionMapper->findAllPurchasedGoodsInStock($goodsCategory, $goodsSubCategory, $item_name_id, $organisation_id);
	}

	public function findGoodsInStockDetails($id)
	{
		return $this->goodsTransactionMapper->findGoodsInStockDetails($id);
	}
	public function goodSurrenderedStatus($id)
	{
		return $this->goodsTransactionMapper->goodSurrenderedStatus($id);
	}

	public function findDonatedGoodsInStockDetails($id)
	{
		return $this->goodsTransactionMapper->findDonatedGoodsInStockDetails($id);
	}

	public function findTransferedGoodsInStockDetails($id)
	{
		return $this->goodsTransactionMapper->findTransferedGoodsInStockDetails($id);
	}

	public function listAllDonationGoodsInStock($organisation_id)
	{
		return $this->goodsTransactionMapper->findAllDonationGoodsInStock($organisation_id);
	}

	public function listAllTransferedGoodsInStock($organisation_id)
	{
		return $this->goodsTransactionMapper->listAllTransferedGoodsInStock($organisation_id);
	}

	public function listDeptGoodsInStock($departmentId, $organisation_id)
	{
		return $this->goodsTransactionMapper->listDeptGoodsInStock($departmentId, $organisation_id);
	}

	public function listAllEmpIssuedGoods($departmentId, $organisation_id)
	{
		return $this->goodsTransactionMapper->findAllEmpIssuedGoods($departmentId, $organisation_id);
	}


	public function getItemList($itemName)
	{
		return $this->goodsTransactionMapper->getItemList($itemName);
	}

	public function getStaffList($empName, $empId, $department, $organistion_id)
	{
		return $this->goodsTransactionMapper->getStaffList($empName, $empId, $department, $organistion_id);
	}



	public function findAdhocGoodsIssueDetails($id)
	{
		return $this->goodsTransactionMapper->findAdhocGoodsIssueDetails($id);
	}

	public function findAdhocGoodsIssue($id)
	{
		return $this->goodsTransactionMapper->findAdhocGoodsIssue($id);
		
	}


	public function findSubStoreGoodsIssue($id)
	{
		return $this->goodsTransactionMapper->findSubStoreGoodsIssue($id);
	}


	public function findSubStoreToIndGoodsIssue($id)
	{
		return $this->goodsTransactionMapper->findSubStoreToIndGoodsIssue($id);
	}

	public function getDeptStaffList($id, $organistion_id)
	{
		return $this->goodsTransactionMapper->getDeptStaffList($id, $organistion_id);
	}

	public function getGoodsReceiverList($organistion_id)
	{
		return $this->goodsTransactionMapper->getGoodsReceiverList($organistion_id);
	}

	public function getDepartmentList($organistion_id)
	{
		return $this->goodsTransactionMapper->getDepartmentList($organistion_id);
	}

	public function getGoodsReceiverDetails($organistion_id)
	{
		return $this->goodsTransactionMapper->getGoodsReceiverDetails($organistion_id);
	}

	public function getStaffGoodsDetails($id)
	{
		return $this->goodsTransactionMapper->getStaffGoodsDetails($id);
	}

	public function getStaffDetails($id)
	{
		return $this->goodsTransactionMapper->getStaffDetails($id);
	}

	public function getEmployeeDetails($id)
	{
		return $this->goodsTransactionMapper->getEmployeeDetails($id);
	}

	public function listAllAdhocIssueGoods($tableName, $status, $employee_details_id)
	{
		return $this->goodsTransactionMapper->findAllAdhocIssueGoods($tableName, $status, $employee_details_id);
	}

	public function listAllSubStoreNominee($tableName, $departments_id)
	{
		return $this->goodsTransactionMapper->listAllSubStoreNominee($tableName, $departments_id);
	}


	public function listAllRequisitionIssueGoods($tableName, $status, $employee_details_id)
	{
		return $this->goodsTransactionMapper->findAllRequisitionIssueGoods($tableName, $status, $employee_details_id);
	}

        public function getStaffDepreciationDetails($staffId)
	{

		return $this->goodsTransactionMapper->getStaffDepreciationDetails($staffId);
	}
	public function findRequisitionGoodsIssue($id)
	{
		return $this->goodsTransactionMapper->findRequisitionGoodsIssue($id);
		
	}
    

	public function findIssueGoods($id)
	{
		return $this->goodsTransactionMapper->findIssueGoods($id);
		
	}
        
	public function findIssueGoodsDetails($id) 
	{
		return $this->goodsTransactionMapper->findIssueGoodsDetails($id);;
	}


	public function findEmpGoodsDetails($id) 
	{
		return $this->goodsTransactionMapper->findEmpGoodsDetails($id);;
	}

	public function crossCheckEmpGoodsSurrender($status, $id, $employee_details_id) 
	{
		return $this->goodsTransactionMapper->crossCheckEmpGoodsSurrender($status, $id, $employee_details_id);;
	}

	public function crossCheckEmpGoodsSurrenderQty($surrenderQuantity, $id, $employee_details_id)
	{
		return $this->goodsTransactionMapper->crossCheckEmpGoodsSurrenderQty($surrenderQuantity, $id, $employee_details_id);
	}

	public function findGoodsSurrenderDetails($id)
	{
		return $this->goodsTransactionMapper->findGoodsSurrenderDetails($id);
	}


	public function findSubStoreGoodsSurrenderDetails($id)
	{
		return $this->goodsTransactionMapper->findSubStoreGoodsSurrenderDetails($id);
	}

	public function findSubStoreSurrenderGoodsDetails($id)
	{
		return $this->goodsTransactionMapper->findSubStoreSurrenderGoodsDetails($id);
	}

	public function listEmpAllFixedAssetLists($employee_details_id)
	{
		return $this->goodsTransactionMapper->findEmpAllFixedAssetLists($employee_details_id);
	}

	public function listEmpAllConsumableGoodsLists($employee_details_id)
	{
		return $this->goodsTransactionMapper->listEmpAllConsumableGoodsLists($employee_details_id);
	}

	public function getDeptList($department, $organistion_id)
	{
		return $this->goodsTransactionMapper->getDeptList($department, $organistion_id);
	}

	public function getDeptDetails($id)
	{
		return $this->goodsTransactionMapper->getDeptDetails($id);
	}

	public function listAllDeptIssueGoods($tableName, $status, $employee_details_id)
	{
		return $this->goodsTransactionMapper->findAllDeptIssueGoods($tableName, $status, $employee_details_id);
	}

	public function listAllIndIssueGoods($tableName, $status, $employee_details_id)
	{
		return $this->goodsTransactionMapper->findAllIndIssueGoods($tableName, $status, $employee_details_id);
	}

	public function listEmpAllSurrenderedGoods($organisation_id)
	{
		return $this->goodsTransactionMapper->listEmpAllSurrenderedGoods($organisation_id);
	}

	public function findAllGoodsSurrenderList($employee_details_id)
	{
		return $this->goodsTransactionMapper->findAllGoodsSurrenderList($employee_details_id);
	}

	public function listAllEmpSurrenderGoods($organisation_id)
	{
		return $this->goodsTransactionMapper->listAllEmpSurrenderGoods($organisation_id);
	}

	public function listAllEmpSubStoreSurrenderGoods($organisation_id, $departments_units_id)
	{
		return $this->goodsTransactionMapper->listAllEmpSubStoreSurrenderGoods($organisation_id, $departments_units_id);
	}

	public function getStaffGoodsSurrenderDetails($id)
	{
		return $this->goodsTransactionMapper->getStaffGoodsSurrenderDetails($id);
	}

	public function getGoodsSurrenderDetails($id)
	{
		return $this->goodsTransactionMapper->getGoodsSurrenderDetails($id);
	}

	public function getSubStoreGoodsSurrenderDetails($departments_units_id, $id)
	{
		return $this->goodsTransactionMapper->getSubStoreGoodsSurrenderDetails($departments_units_id, $id);
	}

	public function findGoodsSurrenderList($id)
	{
		return $this->goodsTransactionMapper->findGoodsSurrenderList($id);
	}


	public function listAllSubStoreSurrenderGoods($organisation_id)
	{
		return $this->goodsTransactionMapper->listAllSubStoreSurrenderGoods($organisation_id);
	}


	public function getSubStoreDetails($id)
	{
		return $this->goodsTransactionMapper->getSubStoreDetails($id);
	}

	public function getSubStoreSurrenderGoodsDetails($id)
	{
		return $this->goodsTransactionMapper->getSubStoreSurrenderGoodsDetails($id);
	}


	public function listAllDeptTransferFrom($departments_units_id)
	{
		return $this->goodsTransactionMapper->listAllDeptTransferFrom($departments_units_id);
	}

	public function listAllDeptTransferFromStatus($departments_units_id)
	{
		return $this->goodsTransactionMapper->listAllDeptTransferFromStatus($departments_units_id);
	}

	public function listAllDeptTransferTo($departments_units_id)
	{
		return $this->goodsTransactionMapper->listAllDeptTransferTo($departments_units_id);
	}

	public function listAllOrgGoodsTransferApproval($organisation_id)
	{
		return $this->goodsTransactionMapper->listAllOrgGoodsTransferApproval($organisation_id);
	}

	public function findOrgGoodsTransferDetails($id)
	{
		return $this->goodsTransactionMapper->findOrgGoodsTransferDetails($id);
	}


	public function listAllOrgGoodsTransferTo($organisation_id)
	{
		return $this->goodsTransactionMapper->listAllOrgGoodsTransferTo($organisation_id);
	}

	public function listAllOrgGoodsTransferFrom($organisation_id)
	{
		return $this->goodsTransactionMapper->listAllOrgGoodsTransferFrom($organisation_id);
	}

	public function findOrgGoodsTransferToDetails($id)
	{
		return $this->goodsTransactionMapper->findOrgGoodsTransferToDetails($id);
	}

	public function findOrgGoodsTransferFromDetails($id)
	{
		return $this->goodsTransactionMapper->findOrgGoodsTransferFromDetails($id);
	}

	public function crossCheckItemCategory($itemType, $majorClass)
	{
		return $this->goodsTransactionMapper->crossCheckItemCategory($itemType, $majorClass);
	}

	public function saveItemCategory(ItemCategory $goodsTransactionObject) 
	{
		return $this->goodsTransactionMapper->saveItemCategory($goodsTransactionObject);
	}

	public function crossCheckItemSubCategory($subCategoryType, $categoryType, $organisation_id)
	{
		return $this->goodsTransactionMapper->crossCheckItemSubCategory($subCategoryType, $categoryType, $organisation_id);
	}
    
    public function saveItemSubCategory(ItemSubCategory $goodsTransactionObject, $item_category_id) 
	{
		return $this->goodsTransactionMapper->saveItemSubCategory($goodsTransactionObject, $item_category_id);
	}

	public function deleteItemSubCategory(GoodsTransaction $goodsTransactionObject)
	{
		return $this->goodsTransactionMapper->deleteItemSubCategory($goodsTransaction);
	}

	public function crossCheckItemQuantityType($quantityType, $organisation_id)
	{
		return $this->goodsTransactionMapper->crossCheckItemQuantityType($quantityType, $organisation_id);
	}

	public function saveItemQuantityType(ItemQuantityType $goodsTransactionObject) 
	{
		return $this->goodsTransactionMapper->saveItemQuantityType($goodsTransactionObject);
	}

	public function deleteItemQuantityType(GoodsTransaction $goodsTransactionObject)
	{
		return $this->goodsTransactionMapper->deleteItemQuantityType($goodsTransactionObject);
	}

	public function crossCheckItemName($itemName, $item_sub_category_id, $organisation_id)
	{
		return $this->goodsTransactionMapper->crossCheckItemName($itemName, $item_sub_category_id, $organisation_id);
	}

	public function saveItemName(ItemName $goodsTransactionObject, $item_category_id, $item_sub_category_id) 
	{
		return $this->goodsTransactionMapper->saveItemName($goodsTransactionObject, $item_category_id, $item_sub_category_id);
	}


	public function deleteItemName(GoodsTransaction $goodsTransaction)
	{
		return $this->goodsTransactionMapper->deleteItemName($goodsTransaction);
	}

	public function crossCheckItemSupplier($supplierName, $supplierLicense, $organisation_id)
	{
		return $this->goodsTransactionMapper->crossCheckItemSupplier($supplierName, $supplierLicense, $organisation_id);
	}

	public function saveItemSupplier(ItemSupplier $goodsTransactionObject) 
	{
		return $this->goodsTransactionMapper->saveItemSupplier($goodsTransactionObject);
	}

	public function saveBlackListedSupplier(ItemSupplier $goodsTransactionObject) 
	{
		return $this->goodsTransactionMapper->saveBlackListedSupplier($goodsTransactionObject);
	}

	public function deleteItemSupplier(GoodsTransaction $goodsTransactionObject)
	{
		return $this->goodsTransactionMapper->deleteItemSupplier($goodsTransactionObject);
	}

	public function crossCheckItemDonor($donorName, $organisation_id)
	{
		return $this->goodsTransactionMapper->crossCheckItemDonor($donorName, $organisation_id);
	}

	public function saveItemDonor(ItemDonor $goodsTransactionObject) 
	{
		return $this->goodsTransactionMapper->saveItemDonor($goodsTransactionObject);
	}

	public function deleteItemDonor(GoodsTransaction $goodsTransactionObject)
	{
		return $this->goodsTransactionMapper->deleteItemDonor($goodsTransactionObject);
	}


	public function saveGoodsReceivedPurchased(GoodsReceived $goodsPurchasedObject) 
	{
		return $this->goodsTransactionMapper->saveGoodsReceivedPurchased($goodsPurchasedObject);
	}

	public function saveGoodsSupplied(GoodsReceived $goodsTransactionObject, $item_category_id, $item_sub_category_id, $item_name_id)
	{
		return $this->goodsTransactionMapper->saveGoodsSupplied($goodsTransactionObject, $item_category_id, $item_sub_category_id, $item_name_id);
	}

	public function deleteAddGoodsSupplied($id)
	{
		return $this->goodsTransactionMapper->deleteAddGoodsSupplied($id);
	}


	public function saveGoodsReceiptVoucherNo($id, $organisation_id)
	{
		return $this->goodsTransactionMapper->saveGoodsReceiptVoucherNo($id, $organisation_id);
	}

	public function saveGoodsReceivedDonation(GoodsReceived $goodsTransactionObject,  $item_category_id, $item_sub_category_id, $item_name_id) 
	{
		return $this->goodsTransactionMapper->saveGoodsReceivedDonation($goodsTransactionObject, $item_category_id, $item_sub_category_id, $item_name_id);
	}

	public function getGoodsIssueToEmployeeId($tableName, $employee_details_id)
	{
		return $this->goodsTransactionMapper->getGoodsIssueToEmployeeId($tableName, $employee_details_id);
	}


	public function saveAdhocIssueGoods(IssueGoods $goodsTransactionObject, $goods_received_id, $employee_details_id) 
	{
		return $this->goodsTransactionMapper->saveAdhocIssueGoods($goodsTransactionObject, $goods_received_id, $employee_details_id);
	}

	public function deleteAdhocGoodsIssue(GoodsTransaction $goodsTransactionObject)
	{
		return $this->goodsTransactionMapper->deleteAdhocGoodsIssue($goodsTransactionObject);
	}

	public function updateAdhocGoodsIssue($data_to_insert, $data_to_insert1)
	{
		return $this->goodsTransactionMapper->updateAdhocGoodsIssue($data_to_insert, $data_to_insert1);
	}

	public function saveRequisitionIssueGoods(RequisitionIssueGoods $goodsTransactionObject, $goods_received_id, $employee_details_id, $goods_requisition_details_id) 
	{
		return $this->goodsTransactionMapper->saveRequisitionIssueGoods($goodsTransactionObject, $goods_received_id, $employee_details_id, $goods_requisition_details_id);
	}

	public function deleteRequisitionGoodsIssue(GoodsTransaction $goodsTransactionObject)
	{
		return $this->goodsTransactionMapper->deleteRequisitionGoodsIssue($goodsTransactionObject);
	}

	public function updateRequisitionGoodsIssue($data_to_insert, $data_to_insert1)
	{
		return $this->goodsTransactionMapper->updateRequisitionGoodsIssue($data_to_insert, $data_to_insert1);
	}

	public function saveSubStoreIssueGoods(DeptGoods $goodsTransaction, $goods_received_id, $goods_received_by) 
	{
		return $this->goodsTransactionMapper->saveSubStoreIssueGoods($goodsTransaction, $goods_received_id, $goods_received_by);
	}

	public function updateSubStoreIssueGoods($data_to_insert) 
	{
		return $this->goodsTransactionMapper->updateSubStoreIssueGoods($data_to_insert);
	}

	public function saveSubStoreNomination(NominateSubStore $goodsTransactionObject)
	{
		return $this->goodsTransactionMapper->saveSubStoreNomination($goodsTransactionObject);
	}


	public function deleteSubStoreGoodsIssue(GoodsTransaction $goodsTransactionObject)
	{
		return $this->goodsTransactionMapper->deleteSubStoreGoodsIssue($goodsTransactionObject);
	}

	public function deleteSubStoreToIndIssueGoods(GoodsTransaction $goodsTransactionObject)
	{
		return $this->goodsTransactionMapper->deleteSubStoreToIndIssueGoods($goodsTransactionObject);
	}

	public function saveSubStoreToIndIssueGoods(DeptIssueGoods $goodsTransaction, $departments_id, $employee_details_id, $itemName) 
	{
		return $this->goodsTransactionMapper->saveSubStoreToIndIssueGoods($goodsTransaction, $departments_id, $employee_details_id, $itemName);
	}

	public function updateSubStoreToIndIssueGoods($data_to_insert, $data_to_insert1) 
	{
		return $this->goodsTransactionMapper->updateSubStoreToIndIssueGoods($data_to_insert, $data_to_insert1);
	}


	public function updateDeptIssueGoods($status, $previousStatus)
	{
		return $this->goodsTransactionMapper->updateDeptIssueGoods($status, $previousStatus);
	}

	public function saveGoodsSurrender(GoodsSurrender $goodsTransaction) 
	{
		return $this->goodsTransactionMapper->saveGoodsSurrender($goodsTransaction);
	}

	public function crossCheckDeptGoodsSurrender($status, $id, $departments_units_id)
	{
		return $this->goodsTransactionMapper->crossCheckDeptGoodsSurrender($status, $id, $departments_units_id);
	}

	public function crossCheckDeptGoodsSurrenderQty($id, $surrender_quantity)
	{
		return $this->goodsTransactionMapper->crossCheckDeptGoodsSurrenderQty($id, $surrender_quantity);
	}


	public function crossCheckOrgGoodsTransferedQty($id, $itemTransferedQty)
	{
		return $this->goodsTransactionMapper->crossCheckOrgGoodsTransferedQty($id, $itemTransferedQty);
	}

	public function saveDeptGoodsSurrender(DeptGoodsSurrender $goodsTransaction) 
	{
		return $this->goodsTransactionMapper->saveDeptGoodsSurrender($goodsTransaction);
	}

	public function updateSubStoreGoodsSurrender(DeptGoodsSurrender $goodsTransaction, $id)
	{
		return $this->goodsTransactionMapper->updateSubStoreGoodsSurrender($goodsTransaction, $id);
	}	

    public function listSelectEmpData($organistion_id)
	{
		return $this->goodsTransactionMapper->listSelectEmpData($organistion_id);
	}

	public function listSelectData($tableName, $columnName)
	{
		return $this->goodsTransactionMapper->listSelectData($tableName, $columnName);
	}

	public function listSelectData2($tableName, $columnName, $organisation_id)
	{
		return $this->goodsTransactionMapper->listSelectData2($tableName, $columnName, $organisation_id);
	}

	public function listSelectAddSubStoreData($tableName, $organistion_id)
	{
		return $this->goodsTransactionMapper->listSelectAddSubStoreData($tableName, $organistion_id);
	}

	public function listSelectDataDetails($tableName, $columnName, $organistion_id)
	{
		return $this->goodsTransactionMapper->listSelectDataDetails($tableName, $columnName, $organistion_id);
	}

	public function listSelectEmpDetails($tableName, $organisation_id)
	{
		return $this->goodsTransactionMapper->listSelectEmpDetails($tableName, $organisation_id);
	}

	public function updateEmpGoodsSurrender($status, $previousStatus, $id)
	{
		return $this->goodsTransactionMapper->updateEmpGoodsSurrender($status, $previousStatus, $id);
	}

	public function updateEmpSubStoreSurrender($status, $previousStatus, $id)
	{
		return $this->goodsTransactionMapper->updateEmpSubStoreSurrender($status, $previousStatus, $id);
	}

	public function updateEmpConsumableGoods($status, $previousStatus, $id)
	{
		return $this->goodsTransactionMapper->updateEmpConsumableGoods($status, $previousStatus, $id);
	}

	public function listSelectItemVerify($organistion_id)
	{
		return $this->goodsTransactionMapper->listSelectItemVerify($organistion_id);
	}

	public function listSelectSubStoreToIndData($tableName, $departments_units_id, $employee_details_id)
	{
		return $this->goodsTransactionMapper->listSelectSubStoreToIndData($tableName, $departments_units_id, $employee_details_id);
	}

	public function listSelectData1($tableName, $columnName)
	{
		return $this->goodsTransactionMapper->listSelectData($tableName, $columnName);
	}


	//Applying for Goods Transfer

	public function listAllGoodsTransfered()
	{
		return $this->goodsTransactionMapper->listAllGoodsTransfered();
	}
	public function findGoodsTransfer($id)
	{
		return $this->goodsTransactionMapper->findGoodsTransfer($id);
	}
	 
	public function findAllGoodsTransfer()
	{
		return $this->goodsTransactionMapper->findAllGoodsTransfer();
		
	}
        
	public function findGoodsTransferDetail($id) 
	{
		return $this->goodsTransactionMapper->findGoodsTransferDetail($id);
	}

	public function findDeptGoodsTransferFromDetails($id)
	{
		return $this->goodsTransactionMapper->findDeptGoodsTransferFromDetails($id);
	}


	public function findDeptGoodsTransferToDetails($id)
	{
		return $this->goodsTransactionMapper->findDeptGoodsTransferToDetails($id);
	}

	public function crossCheckDeptGoodsTransfer($status, $id, $departments_units_id)
	{
		return $this->goodsTransactionMapper->crossCheckDeptGoodsTransfer($status, $id, $departments_units_id);
	}

	public function crossCheckDeptGoodsTransferQty($id, $transfer_quantity)
	{
		return $this->goodsTransactionMapper->crossCheckDeptGoodsTransferQty($id, $transfer_quantity);
	}
	
	public function saveDeptGoodsTransfer(GoodsTransfer $goodsTransaction, $department_to_id, $employee_to_id) 
	{
		return $this->goodsTransactionMapper->saveDeptGoodsTransfer($goodsTransaction, $department_to_id, $employee_to_id);
	}


	public function updateDeptGoodsTransfer(GoodsTransfer $goodsTransaction, $id)
	{
		return $this->goodsTransactionMapper->updateDeptGoodsTransfer($goodsTransaction, $id);
	}

	public function crossCheckOrgGoodsTransfer($status, $id)
	{
		return $this->goodsTransactionMapper->crossCheckOrgGoodsTransfer($status, $id);
	}

	public function crossCheckOrgGoodsTransferQty($transfer_quantity, $id)
	{
		return $this->goodsTransactionMapper->crossCheckOrgGoodsTransferQty($transfer_quantity, $id);
	}

	public function saveOrgGoodsTransfer(OrgGoodsTransfer $goodsTransaction)
	{
		return $this->goodsTransactionMapper->saveOrgGoodsTransfer($goodsTransaction);
	}

	public function updateOrgGoodsTransfer(OrgGoodsTransfer $goodsTransaction, $id, $item_category_id, $item_sub_category_id, $item_name_id, $itemTransferedId, $itemReceivedType, $organisation_id)
	{
		return $this->goodsTransactionMapper->updateOrgGoodsTransfer($goodsTransaction, $id, $item_category_id, $item_sub_category_id, $item_name_id, $itemTransferedId, $itemReceivedType, $organisation_id);
	}

	public function rejectOrgFromGoodsTransfer($status, $previousStatus, $id, $employee_details_id)
	{
		return $this->goodsTransactionMapper->rejectOrgFromGoodsTransfer($status, $previousStatus, $id, $employee_details_id);
	}

	public function findTransferGoodsApprovalStatus()
	{
		return $this->goodsTransactionMapper->findTransferGoodsApprovalStatus();
	}


	public function updateDisposeGoods(DisposeGoods $goodsTransaction, $id)
	{
		return $this->goodsTransactionMapper->updateDisposeGoods($goodsTransaction, $id);
	}	


	//Department Item Lists
	public function findDeptGoodsDetails($id)
	{
		return $this->goodsTransactionMapper->findDeptGoodsDetails($id);
	}

	public function findOrgGoodsDetails($id)
	{
		return $this->goodsTransactionMapper->findOrgGoodsDetails($id);
	}
	 
	public function findDeptAllGoods($tableName, $departments_units_id)
	{
		return $this->goodsTransactionMapper->findDeptAllGoods($tableName, $departments_units_id);
		
	}   

	public function getOrganisationDocument($tableName, $document_type, $organisation_id)
	{
		return $this->goodsTransactionMapper->getOrganisationDocument($tableName, $document_type, $organisation_id);
	}

	public function getOrganizationDetails($id)
	{
		return $this->goodsTransactionMapper->getOrganizationDetails($id);
	}     	
}

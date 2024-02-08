<?php


namespace GoodsTransaction\Controller;

use GoodsTransaction\Service\GoodsTransactionServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use GoodsTransaction\Model\GoodsTransaction;
use GoodsTransaction\Model\ItemCategory;
use GoodsTransaction\Model\ItemSubCategory;
use GoodsTransaction\Model\ItemQuantityType;
use GoodsTransaction\Model\ItemName;
use GoodsTransaction\Model\ItemSupplier;
use GoodsTransaction\Model\ItemDonor;
use GoodsTransaction\Model\IssueGoods;
use GoodsTransaction\Model\GoodsReceived;
use GoodsTransaction\Model\Itemreceivedpurchased;
use GoodsTransaction\Model\GoodsSurrender;
use GoodsTransaction\Model\GoodsTransfer;
use GoodsTransaction\Model\DeptGoods;
use GoodsTransaction\Model\DeptIssueGoods;
use GoodsTransaction\Model\NominateSubStore;
use GoodsTransaction\Model\RequisitionIssueGoods;
use GoodsTransaction\Model\DeptGoodsSurrender;
use GoodsTransaction\Model\OrgGoodsTransfer;
use GoodsTransaction\Model\DisposeGoods;
use GoodsTransaction\Form\ItemCategoryForm;
use GoodsTransaction\Form\ItemSubCategoryForm;
use GoodsTransaction\Form\ItemQuantityTypeForm;
use GoodsTransaction\Form\ItemNameForm;
use GoodsTransaction\Form\ItemSupplierForm;
use GoodsTransaction\Form\ItemDonorForm;
use GoodsTransaction\Form\GoodsReceivedForm;
use GoodsTransaction\Form\GoodsReceivedPurchasedForm;
use GoodsTransaction\Form\GoodsReceivedDonationForm;
use GoodsTransaction\Form\ItemSearchForm;
use GoodsTransaction\Form\StaffSearchForm;
use GoodsTransaction\Form\DeptSearchForm;
use GoodsTransaction\Form\NominateSubStoreForm;
use GoodsTransaction\Form\RequisitionIssueGoodsForm;
use GoodsTransaction\Form\IssueGoodsForm;
use GoodsTransaction\Form\EmpIssueGoodsForm;
use GoodsTransaction\Form\SubmitIssueGoodsForm;
use GoodsTransaction\Form\SubmitSuppliedGoodsForm;
use GoodsTransaction\Form\GoodsSurrenderForm;
use GoodsTransaction\Form\DeptGoodsTransferForm;
use GoodsTransaction\Form\UpdateDeptGoodsTransferForm;
use GoodsTransaction\Form\GoodsTransferApprovalForm;
use GoodsTransaction\Form\DeptGoodsForm;
use GoodsTransaction\Form\DeptGoodsIssueForm;
use GoodsTransaction\Form\DeptIssueGoodsForm;
use GoodsTransaction\Form\AdhocGoodsIssueForm;
use GoodsTransaction\Form\GoodsSuppliedForm;
use GoodsTransaction\Form\GoodsSearchForm;
use GoodsTransaction\Form\DeptStaffSearchForm;
use GoodsTransaction\Form\SubStoreToIndIssueForm;
use GoodsTransaction\Form\RequisitionGoodsIssueForm;
use GoodsTransaction\Form\GoodsInStockDetailsForm;
use GoodsTransaction\Form\DeptGoodsTransferDetailsForm;
use GoodsTransaction\Form\DeptGoodsSurrenderForm;
use GoodsTransaction\Form\OrgGoodsTransferForm;
use GoodsTransaction\Form\UpdateOrgGoodsTransferForm;
use GoodsTransaction\Form\DisposeGoodsForm;
use Zend\Mvc\Controller\AbstractActionController;
use DOMPDFModule\View\Model\PdfModel;
use Zend\View\Model\ViewModel;
use Zend\Http\Response\Stream;
use Zend\Http\Headers;

//RBACL
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager; 

//AJAX
use Zend\Paginator\Adapter\DbSelect;
use Zend\View\Model\JsonModel;
use Zend\Form\Element\Select;
 
  
class GoodsTransactionController extends AbstractActionController
{
    protected $goodsTransactionService;
    protected $notificationService;
    protected $auditTrailService;
    protected $serviceLocator;
    protected $username;
    protected $userrole;
    protected $userregion;
    protected $usertype;
    protected $userDetails;
    protected $userImage;
    protected $employee_details_id;
    protected $organisation_id;
    protected $departments_id;

    protected $keyphrase = "RUB_IMS";

    protected $parentValue;
    protected $parentValue1;
	
	public function __construct(GoodsTransactionServiceInterface $goodsTransactionService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->goodsTransactionService = $goodsTransactionService;
        $this->notificationService = $notificationService;
        $this->auditTrailService = $auditTrailService;
        $this->serviceLocator = $serviceLocator;

        /*
         * To retrieve the user name from the session
        */
        $authPlugin = $this->serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
        // Use service locator to get the authPlugin
        $this->username = $authPlugin['username'];
        $this->userrole = $authPlugin['role'];
        $this->userregion = $authPlugin['region'];
        $this->usertype = $authPlugin['user_type_id'];

        /*
        * Getting the employee_details_id related to username
        */
        
        $empData = $this->goodsTransactionService->getEmployeeDetailsId($this->username);
        foreach($empData as $emp){
            $this->employee_details_id = $emp['id'];
        }


        //get the organisation id
        $organisationID = $this->goodsTransactionService->getOrganisationId($this->username);
        foreach($organisationID as $organisation){
            $this->organisation_id = $organisation['organisation_id'];
        }

        //get the department id
        $departmentID = $this->goodsTransactionService->getDepartmentId($this->username);
        foreach($departmentID as $department){
            $this->departments_id = $department['departments_id'];
        }

        //get the department id
        $departmentUnitID = $this->goodsTransactionService->getDepartmentUnitId($this->username);
        foreach($departmentUnitID as $unit){
            $this->departments_units_id = $unit['departments_units_id'];
        }

        $this->userDetails = $this->goodsTransactionService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->goodsTransactionService->getUserImage($this->username, $this->usertype);
	}

    public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }


    public function viewItemDetailsAction()
    {   
        $this->loginDetails();        
        $itemcategoryForm = new ItemCategoryForm();
        $itemsubcategoryForm = new ItemSubCategoryForm($this->serviceLocator);
        $itemquantitytypeForm = new ItemQuantityTypeForm();
        $itemnameForm = new ItemNameForm($this->serviceLocator);

        $itemMajorClass = $this->goodsTransactionService->listSelectData($tableName = 'item_major_class', $columnName = 'major_class');
       
        $itemCategory = $this->goodsTransactionService->listSelectData($tableName='item_category', $columnName='category_type');
        //$itemSubCategory = $this->goodsTransactionService->listSelectData($tableName='item_sub_category', $columnName='sub_category_type');
        $itemQuantityType = $this->goodsTransactionService->listSelectData2($tableName='item_quantity_type', $columnName='item_quantity_type', $this->organisation_id);
        
        //need to get the list of item category, item sub category, item quantity type and item name from the database
        $listItemCategory = $this->goodsTransactionService->listAllItemCategory();
        $listItemSubCategory = $this->goodsTransactionService->listAllItemSubCategory($this->organisation_id);
        $listItemQuantityType = $this->goodsTransactionService->listAllItemQuantityType($this->organisation_id);
        $listItemName = $this->goodsTransactionService->listAllItemName($this->organisation_id);

        $message = NULL;
       
        return array(
            'itemcategoryForm' => $itemcategoryForm,
            'itemsubcategoryForm' => $itemsubcategoryForm,
            'itemquantitytypeForm' => $itemquantitytypeForm,
            'itemnameForm' => $itemnameForm,
            'listItemCategory' => $listItemCategory,
            'listItemSubCategory' => $listItemSubCategory,
            'listItemQuantityType' => $listItemQuantityType,
            'listItemName' => $listItemName,
            'itemMajorClass' => $itemMajorClass,
            'itemCategory' => $itemCategory,
            //'itemSubCategory' => $itemSubCategory,
            'itemQuantityType' => $itemQuantityType,
            'organisation_id' => $this->organisation_id,
            'keyphrase' => $this->keyphrase,
            'message' => $message,
        );
    }


    // Add Item Category Action

    public function addItemCategoryAction()
    {
        $this->loginDetails();
        $form = new ItemCategoryForm();
        $goodsTransactionModel = new ItemCategory();
        $form->bind($goodsTransactionModel);

        $message = NULL;

         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());

             $data = $this->params()->fromPost();
             $categoryType = $data['itemcategory']['category_type'];
             $majorClass = $data['itemcategory']['major_class_id'];
             $checkCategoryType = $this->goodsTransactionService->crossCheckItemCategory($categoryType, $majorClass);
             if($checkCategoryType){
                $message = 'Failure';
                $this->flashMessenger()->addMessage('You have already added this type of category for same item major class. Please enter different.');
                return $this->redirect()->toRoute('view-item-details');
             }else{
                if ($form->isValid()) {
                     try {
                         $this->goodsTransactionService->saveItemCategory($goodsTransactionModel);
                         $this->auditTrailService->saveAuditTrail("INSERT", "Item Category", "ALL", "SUCCESS");

                         $this->flashMessenger()->addMessage('Item Category was successfully added');
                         return $this->redirect()->toRoute('view-item-details');
                     }
                     catch(\Exception $e) {
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                        return $this->redirect()->toRoute('view-item-details');
                             // Some DB Error happened, log it and let the user know
                     }
                 }
             }
         }

         return array(
             'form' => $form,
             'message' => $message,
             'keyphrase' => $this->keyphrase,
         );
    }

    //To edit Item Category Action
    public function editItemCategoryAction()
    {
        $this->loginDetails();
        // get the id of the item sub category
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $categoryDetails = $this->goodsTransactionService->findCategory($id);

            $form = new ItemCategoryForm();
            $goodsTransactionModel = new ItemCategory();
            $form->bind($goodsTransactionModel);

            $itemMajorClass = $this->goodsTransactionService->listSelectData($tableName = 'item_major_class', $columnName = 'major_class');

            $request = $this->getRequest();
            if($request->isPost()){
                $form->setData($request->getPost());
                if($form->isValid()){
                    try{
                        $this->goodsTransactionService->saveItemCategory($goodsTransactionModel);
                        $this->auditTrailService->saveAuditTrail("EDIT", "Item Category", "ALL", "SUCCESS");

                        $this->flashMessenger()->addMessage('Item Sub Category was successfully edited');
                        return $this->redirect()->toRoute('view-item-details');
                    }
                    catch(\Exception $e){
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                        return $this->redirect()->toRoute('view-item-details');
                        //Some DB Error happened, log it and let the user know  
                    }
                
                }
            }

            return array(
                'form' => $form,
                'categoryDetails' =>$categoryDetails,
                'itemMajorClass' => $itemMajorClass,
                );
        }else{
            return $this->redirect()->toRoute('view-item-details');
        }
    }

                                                    
    // Add Item Sub Category Action
    public function addItemSubCategoryAction()
    {
        $this->loginDetails();
    	$form = new ItemSubCategoryForm($this->serviceLocator);
		$goodsTransactionModel = new ItemSubCategory();
		$form->bind($goodsTransactionModel);

         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());

             $data = $this->params()->fromPost();
             $subCategoryType = $data['itemsubcategory']['sub_category_type'];
             $categoryType = $this->getRequest()->getPost('item_category_id');

             $checkSubCategoryType = $this->goodsTransactionService->crossCheckItemSubCategory($subCategoryType, $categoryType, $this->organisation_id);

             $message = NULL;

             if($checkSubCategoryType){
                $message = 'Failure';
                $this->flashMessenger()->addMessage('You have already added this type of sub catgory for same item category. Please try with different');
                return $this->redirect()->toRoute('view-item-details');
             }else{
                if ($form->isValid()) { 

                     try {
                         $this->goodsTransactionService->saveItemSubCategory($goodsTransactionModel, $categoryType);
                          $this->auditTrailService->saveAuditTrail("INSERT", "Item Sub Category", "ALL", "SUCCESS");

                           $this->flashMessenger()->addMessage('Item Sub Category was successfully added');
                         return $this->redirect()->toRoute('view-item-details');
                     }
                     catch(\Exception $e) {
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                        return $this->redirect()->toRoute('view-item-details');
                             // Some DB Error happened, log it and let the user know
                     }
                 }
             }
         }

         return array(
             'form' => $form,
             'keyphrase' => $this->keyphrase,
             'message' => $message,
         );
    }


    //To edit Item Category Action
    public function editItemSubCategoryAction()
    {
        $this->loginDetails();
        // get the id of the item sub category
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){ 
            $subCategoryDetails = $this->goodsTransactionService->findSubCategoryDetails($id);

            $form = new ItemSubCategoryForm($this->serviceLocator);
            $goodsTransactionModel = new ItemSubCategory();
            $form->bind($goodsTransactionModel);

           // $itemCategory = $this->goodsTransactionService->listSelectData($tableName='item_category', $columnName='category_type');

            $listItemSubCategory = $this->goodsTransactionService->listAllItemSubCategory($this->organisation_id);

            $request = $this->getRequest();
            if($request->isPost()){
                $form->setData($request->getPost());
                $data = $this->params()->fromPost();
                $subCategoryType = $data['itemsubcategory']['sub_category_type'];
                $categoryType = $this->getRequest()->getPost('item_category_id');
                if($form->isValid()){ 
                    try{
                        $this->goodsTransactionService->saveItemSubCategory($goodsTransactionModel, $categoryType);
                         $this->auditTrailService->saveAuditTrail("EDIT", "Item Sub Category", "ALL", "SUCCESS");

                         $this->flashMessenger()->addMessage('Item Sub Category was successfully edited');
                        return $this->redirect()->toRoute('view-item-details');
                    }
                    catch(\Exception $e){
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                        return $this->redirect()->toRoute('view-item-details');
                        //Some DB Error happened, log it and let the user know  
                    }
                
                }
            }

            return array(
                'id' => $id,
                'form' => $form,
                'subCategoryDetails' =>$subCategoryDetails,
                //'itemCategory' => $itemCategory,
                'listItemSubCategory' => $listItemSubCategory,
                );
            }else {
                return $this->redirect()->toRoute('view-item-details');
            }
    }



       //To add Item Quantity Type
    public function addItemQuantityTypeAction()
     {
        $this->loginDetails();
        $form = new ItemQuantityTypeForm();
        $goodsTransactionModel = new ItemQuantityType();
        $form->bind($goodsTransactionModel);

         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());

             $data = $this->params()->fromPost();
             $quantityType = $data['itemquantitytype']['item_quantity_type'];

             $checkItemQuantityType = $this->goodsTransactionService->crossCheckItemQuantityType($quantityType, $this->organisation_id);
             if($checkItemQuantityType){
                $message = 'Failure';
                $this->flashMessenger()->addMessage('You have already added this type of item quantity type. Please try for different');
                return $this->redirect()->toRoute('view-item-details');
             }else{
                if ($form->isValid()) {
                     try {
                         $this->goodsTransactionService->saveItemQuantityType($goodsTransactionModel);
                          $this->auditTrailService->saveAuditTrail("INSERT", "Item Quantity Type", "ALL", "SUCCESS");

                          $this->flashMessenger()->addMessage('Item Quantity Type was successfully added');
                         return $this->redirect()->toRoute('view-item-details');
                     }
                     catch(\Exception $e) {
                             $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                        return $this->redirect()->toRoute('view-item-details');
                             // Some DB Error happened, log it and let the user know
                     }
                 }
             }
         }

         return array(
             'form' => $form,
             'keyphrase' => $this->keyphrase,
             'organisation_id' => $this->organisation_id
         );
     }


     //To edit Item Quantity Action
    public function editItemQuantityTypeAction()
    {
        $this->loginDetails();
        // get the id of the item sub category
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $itemQtyDetails = $this->goodsTransactionService->findItemQuantityTypeDetails($id);
        
            $form = new ItemQuantityTypeForm();
            $goodsTransactionModel = new ItemQuantityType();
            $form->bind($goodsTransactionModel);

            $itemQuantity = $this->goodsTransactionService->listAllItemQuantityType($this->organisation_id);

            $request = $this->getRequest();
            if($request->isPost()){
                $form->setData($request->getPost());
                if($form->isValid()){
                    try{
                        $this->goodsTransactionService->saveItemQuantityType($goodsTransactionModel);
                         $this->auditTrailService->saveAuditTrail("EDIT", "Item Quantity Type", "ALL", "SUCCESS");

                         $this->flashMessenger()->addMessage('Item Quantity Type was successfully edited');
                        return $this->redirect()->toRoute('view-item-details');
                    }
                    catch(\Exception $e){
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                        return $this->redirect()->toRoute('view-item-details');
                        //Some DB Error happened, log it and let the user know  
                    }
                
                }
            }

            return array(
                'form' => $form,
                'itemQtyDetails' =>$itemQtyDetails,
                'itemQuantity' => $itemQuantity,
                'organisation_id' => $this->organisation_id,
                );
            }
            else{
                $this->redirect()->toRoute('view-item-details');
            }
    }


     //To add Item Name
    public function addItemNameAction()
     {
        $this->loginDetails();
        $form = new ItemNameForm($this->serviceLocator);
        $goodsTransactionModel = new ItemName();
        $form->bind($goodsTransactionModel);

        $message = NULL;

        $request = $this->getRequest();
        if ($request->isPost()) {
             $form->setData($request->getPost());

             $data = $this->params()->fromPost();
             $itemName = $data['itemname']['item_name'];
             $item_category_id = $this->getRequest()->getPost('item_category_id');
             $item_sub_category_id = $this->getRequest()->getPost('item_sub_category_id');

             $check_item_name = $this->goodsTransactionService->crossCheckItemName($itemName, $item_sub_category_id, $this->organisation_id);
             if($check_item_name){
                $message = 'Failure';
                $this->flashMessenger()->addMessage('You have already added this type of item name for the same item sub category. Please enter different.');
                return $this->redirect()->toRoute('view-item-details');
             }else{
                if ($form->isValid()) {
                     try {
                         $this->goodsTransactionService->saveItemName($goodsTransactionModel, $item_category_id, $item_sub_category_id);
                          $this->auditTrailService->saveAuditTrail("INSERT", "Item Name", "ALL", "SUCCESS");

                          $this->flashMessenger()->addMessage('Item Name was successfully added');
                         return $this->redirect()->toRoute('view-item-details');
                     }
                     catch(\Exception $e) {
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                        return $this->redirect()->toRoute('view-item-details');
                             // Some DB Error happened, log it and let the user know
                     }
                 }
             }
         }

         return array(
             'form' => $form,
             'keyphrase' => $this->keyphrase,
             'organisation_id' => $this->organisation_id,
             'message' => $message,
         );
     }


     //To edit Item Name
    public function editItemNameAction()
    {
        $this->loginDetails();
        //get the student id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            //$dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');

        $itemName = $this->goodsTransactionService->findItemNameDetails($id);
        
        $form = new ItemNameForm($this->serviceLocator);
        $goodsTransactionModel = new ItemName();
        $form->bind($goodsTransactionModel);

        $message = NULL;

        $itemQuantityType = $this->goodsTransactionService->listSelectData2($tableName='item_quantity_type', $columnName='item_quantity_type', $this->organisation_id);

        $itemNameList = $this->goodsTransactionService->listAllItemName($this->organisation_id);

        $request = $this->getRequest();
        if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                $item_category_id = $this->getRequest()->getPost('item_category_id');
                $item_sub_category_id = $this->getRequest()->getPost('item_sub_category_id');
                 try {
                     $this->goodsTransactionService->saveItemName($goodsTransactionModel, $item_category_id, $item_sub_category_id);
                      $this->auditTrailService->saveAuditTrail("EDIT", "Item Name", "ALL", "SUCCESS");

                      $this->flashMessenger()->addMessage('Item Name was successfully edited');
                     return $this->redirect()->toRoute('view-item-details');
                }
                catch(\Exception $e){
                    $message = 'Failure';
                    $this->flashMessenger()->addMessage($e->getMessage());
                    return $this->redirect()->toRoute('view-item-details');
                    //Some DB Error happened, log it and let the user know  
                }
            
            }
        }

        return array(
            'form' => $form,
            'itemName' =>$itemName,
            'itemNameList' => $itemNameList,
            'itemQuantityType' => $itemQuantityType,
            'organisation_id' => $this->organisation_id,
            'message' => $message,
            );
        }
        else{
            $this->redirect()->toRoute('view-item-details');
        }
    }


    //To add Item Supplier
    public function addItemSupplierAction()
     {
        $this->loginDetails();
        $form = new ItemSupplierForm();
        $goodsTransactionModel = new ItemSupplier();
        $form->bind($goodsTransactionModel);

        $itemSupplier = $this->goodsTransactionService->listAllItemSupplier($tableName = 'supplier_details', $this->organisation_id);

        $blackListItemSupplier = $this->goodsTransactionService->listAllBlackListedSupplier($tableName = 'supplier_details', $this->organisation_id);
        $message = NULL;

        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             $data = $this->params()->fromPost();
             $supplierName = $data['itemsupplier']['supplier_name'];
             $supplierLicense = $data['itemsupplier']['supplier_license_no'];

             $check_supplier = $this->goodsTransactionService->crossCheckItemSupplier($supplierName, $supplierLicense, $this->organisation_id);

             if($check_supplier){
                $message = 'Failure';
                $this->flashMessenger()->addMessage('You have already added supplier with similar name having same license number. Please try for different.');
             }else{
                if ($form->isValid()) {
                     try {
                         $this->goodsTransactionService->saveItemSupplier($goodsTransactionModel);
                         $this->auditTrailService->saveAuditTrail("INSERT", "Supplier Details", "ALL", "SUCCESS");

                         $this->flashMessenger()->addMessage('Supplier was successfully added');
                         return $this->redirect()->toRoute('add-item-supplier');
                     }
                     catch(\Exception $e) {
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                             // Some DB Error happened, log it and let the user know
                     }
                 }
             }
         }

         return array(
             'form' => $form,
             'itemSupplier' => $itemSupplier,
             'blackListItemSupplier' => $blackListItemSupplier,
             'organisation_id' => $this->organisation_id,
             'keyphrase' => $this->keyphrase,
             'message' => $message,
         );
     }

     //To edit Item Supplier
    public function editItemSupplierAction()
    {
        $this->loginDetails();
        //get the id of the leave
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $supplierDetails = $this->goodsTransactionService->findItemSupplierDetails($id);
        
            $form = new ItemSupplierForm();
            $goodsTransactionModel = new ItemSupplier();
            $form->bind($goodsTransactionModel);

            $itemSupplier = $this->goodsTransactionService->listAllItemSupplier($tableName = 'supplier_details', $this->organisation_id);

            $request = $this->getRequest();
            if($request->isPost()){
                $form->setData($request->getPost());
                if($form->isValid()){
                    try{
                        $this->goodsTransactionService->saveItemSupplier($goodsTransactionModel);
                        $this->auditTrailService->saveAuditTrail("EDIT", "Supplier Details", "ALL", "SUCCESS");

                        $this->flashMessenger()->addMessage('Supplier was successfully edited');
                        return $this->redirect()->toRoute('add-item-supplier');
                    }
                    catch(\Exception $e){
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                        //Some DB Error happened, log it and let the user know  
                    }
                
                }
            }

            return array(
                'form' => $form,
                'supplierDetails' =>$supplierDetails,
                'itemSupplier' => $itemSupplier,
                'organisation_id' => $this->organisation_id,
                );
            }
            else{
                return $this->redirect()->toRoute('add-item-supplier');
            }
    }

     //To edit Item Supplier
    public function blackListItemSupplierAction()
    {
        $this->loginDetails();
        //get the id of the leave
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $supplierDetails = $this->goodsTransactionService->findItemSupplierDetails($id);
        
            $form = new ItemSupplierForm();
            $goodsTransactionModel = new ItemSupplier();
            $form->bind($goodsTransactionModel);

            $request = $this->getRequest();
            if($request->isPost()){
                $form->setData($request->getPost());
                $data = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                 ); 
                $form->setData($data); 
                if($form->isValid()){
                    try{
                        $this->goodsTransactionService->saveBlackListedSupplier($goodsTransactionModel);
                        $this->auditTrailService->saveAuditTrail("INSERT", "Blacklisted Supplier Details", "ALL", "SUCCESS");

                        $this->flashMessenger()->addMessage('Supplier was successfully black listed from your supplier list');
                        return $this->redirect()->toRoute('add-item-supplier');
                    }
                    catch(\Exception $e){
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                        //Some DB Error happened, log it and let the user know  
                    }
                
                }
            }

            return array(
                'form' => $form,
                'supplierDetails' =>$supplierDetails,
                'organisation_id' => $this->organisation_id,
                );
            }
            else{
                return $this->redirect()->toRoute('add-item-supplier');
            }
    }


    // Function to activate the blacklisted item supplier
    public function activateBlackListedSupplierAction()
    {
        $this->loginDetails();
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            try {
             $this->goodsTransactionService->activateBlackListedSupplier($status='Active', $previousStatus=NULL, $id);
             $this->auditTrailService->saveAuditTrail("EDIT", "Supplier Details", "ALL", "SUCCESS");

             $this->flashMessenger()->addMessage('Supplier was successfully activated');
             return $this->redirect()->toRoute('add-item-supplier');
         }
         catch(\Exception $e) {
                $message = 'Failure';
                $this->flashMessenger()->addMessage($e->getMessage());
                 // Some DB Error happened, log it and let the user know
         }
         
        return array();
        }
        else{
            $this->redirect()->toRoute('add-item-supplier');
        }
    }


    public function blackListedSupplierDetailAction()
    {
        $this->loginDetails();
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $form = new ItemSupplierForm();
            $goodsTransactionModel = new ItemSupplier();
            $form->bind($goodsTransactionModel);
                    
            $supplierDetails = $this->goodsTransactionService->findBlackListedSupplierDetails($id);
            
            $request = $this->getRequest();
             if ($request->isPost()) {
                 $form->setData($request->getPost());
                 if ($form->isValid()) {
                     try {
                         $this->goodsTransactionService->save($goodsTransactionModel);
                     }
                     catch(\Exception $e) {
                            $message = 'Failure';
                            $this->flashMessenger()->addMessage($e->getMessage());
                             // Some DB Error happened, log it and let the user know
                     }
                 }
             }
             
            return array(
                'form' => $form,
                'supplierDetails' => $supplierDetails);
            }
            else{
                $this->redirect()->toRoute('add-item-supplier');
            }
    }


    public function downloadBlackListedSupplierDocumentsAction() 
    {
        $this->loginDetails();
        //get the student id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $file = $this->goodsTransactionService->getFileName($id);
        
            $mimetype = mime_content_type($file);
            $response = new Stream();
            $response->setStream(fopen($file, 'r'));
            $response->setStatusCode(200);
            $response->setStreamName(basename($file));
            $headers = new Headers();
            $headers->addHeaderLine('Content-Disposition:inline','attachment; filename="' . basename($file) .'"')
                    ->addHeaderLine('Content-Type', $mimetype)
                    ->addHeaderLine('Content-Length',filesize($file))
                    ->addHeaderLine('Expires','@0') // @0, because zf2 parses date as string to \DateTime() object
                    ->addHeaderLine('Cache-Control','must-revalidate')
                    ->addHeaderLine('Pragma','public')
                    ->addHeaderLine('Content-Transfer-Encoding:binary')
                    ->addHeaderLine('Accept-Ranges:bytes');
        
            $response->setHeaders($headers);
            return $response;
        }
        else
        {
            $this->redirect()->toRoute('add-item-supplier');
        }
    }

    //To delete Item Supplier Action

    public function deleteItemSupplierAction()
    {
        $this->loginDetails();
        //get the student id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            try{
            $id = $this->goodsTransactionService->findItemSupplier($id);
        }
        catch(\InvalidArgumentException $e){
            return $this->redirect()->toRoute('add-item-supplier');
        }

        $request = $this->getRequest();

        if($request->isPost()){
            $del = $request->getPost('delete_confirmation', 'no');

            if($del == 'yes'){
                $this->goodsTransactionService->deleteItemSupplier($id);
            }

            return $this->redirect()->toRoute('add-item-supplier');
        }

        return array(
            'id' => $id,
            );
        }
        else{
            $this->redirect()->toRoute('add-item-supplier');
        }
    } 

     //To view or display list of Item Supplier Action

    public function viewItemSupplierAction()
    {
        $this->loginDetails();
        return new ViewModel(array(
            'itemSupplier' => $this->goodsTransactionService->listAllItemSupplier($tableName)
            ));
    }


    //To add Item Donor

    public function addItemDonorAction()
     {
        $this->loginDetails();
        $form = new ItemDonorForm();
        $goodsTransactionModel = new ItemDonor();
        $form->bind($goodsTransactionModel);

        $itemDonorList = $this->goodsTransactionService->listAllItemDonor($tableName = 'item_donor_details', $this->organisation_id);

        $message = NULL;

         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             $data = $this->params()->fromPost();
             $donorName = $data['additemdonor']['donor_name'];

             $check_donor = $this->goodsTransactionService->crossCheckItemDonor($donorName, $this->organisation_id);

             if($check_donor){
                $message = 'Failure';
                $this->flashMessenger()->addMessage('You have already added donor with similar name. Please try for different.');
             }else{
                if ($form->isValid()) {
                     try {
                         $this->goodsTransactionService->saveItemDonor($goodsTransactionModel);
                         $this->auditTrailService->saveAuditTrail("INSERT", "Item Donor Details", "ALL", "SUCCESS");

                         $this->flashMessenger()->addMessage('Item Donor was successfully added');
                         return $this->redirect()->toRoute('add-item-donor');
                     }
                     catch(\Exception $e) {
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                             // Some DB Error happened, log it and let the user know
                     }
                 }
             }
         }

         return array(
             'form' => $form,
             'itemDonorList' => $itemDonorList,
             'keyphrase' => $this->keyphrase,
             'organisation_id' => $this->organisation_id,
             'message' => $message,
         );
     }


      //To edit Item Donor

    public function editItemDonorAction()
    {
        $this->loginDetails();
        //get the student id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $donorDetails = $this->goodsTransactionService->findItemDonorDetails($id);
        
            $form = new ItemDonorForm();
            $goodsTransactionModel = new ItemDonor();
            $form->bind($goodsTransactionModel);

            $itemDonorList = $this->goodsTransactionService->listAllItemDonor($tableName = 'item_donor_details', $this->organisation_id);

            $request = $this->getRequest();
            if($request->isPost()){
                $form->setData($request->getPost());
                if($form->isValid()){
                    try{
                        $this->goodsTransactionService->saveItemDonor($goodsTransactionModel);
                        $this->auditTrailService->saveAuditTrail("EDIT", "Item Donor Details", "ALL", "SUCCESS");

                        $this->flashMessenger()->addMessage('Donor was successfully edited');
                        return $this->redirect()->toRoute('add-item-donor');
                    }
                    catch(\Exception $e){
                         $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                        //Some DB Error happened, log it and let the user know  
                    }
                
                }
            }

            return array(
                'form' => $form,
                'donorDetails' =>$donorDetails,
                'itemDonorList' => $itemDonorList,
                'organisation_id' => $this->organisation_id,
                );
        }
        else{
            $this->redirect()->toRoute('add-item-donor');
        }
    }

    //To delete Item Donor Action

    public function deleteItemDonorAction()
    {
        $this->loginDetails();
        //get the student id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            try{
            $id = $this->goodsTransactionService->findItemDonor($this->params('id'));
            }
            catch(\InvalidArgumentException $e){
                return $this->redirect()->toRoute('add-item-donor');
            }

            $request = $this->getRequest();

            if($request->isPost()){
                $del = $request->getPost('delete_confirmation', 'no');

                if($del == 'yes'){
                    $this->goodsTransactionService->deleteItemDonor($id);
                }

                return $this->redirect()->toRoute('add-item-donor');
            }

            return array(
                'id' => $id,
                );
            }
            else{
                $this->redirect()->toRoute('add-item-donor');
            }
    }

     //To view or display list of Item Donar Action

    public function viewItemDonorAction()
    {
        $this->loginDetails();
        return new ViewModel(array(
            'itemDonorList' => $this->goodsTransactionService->listAllItemDonor()
            ));
    }


     //To add Goods Received

    public function addGoodsReceivedAction()
    {
        $this->loginDetails();
        $goodsreceivedpurchasedForm = new GoodsSuppliedForm();        
        $goodsreceiveddonationForm = new GoodsReceivedDonationForm($this->serviceLocator);

       //get the organisation id
        $organisationID = $this->goodsTransactionService->getOrganisationId($this->username);
        foreach($organisationID as $organisation){
            $organisation_id = $organisation['organisation_id'];
        }

        $itemSupplier = $this->goodsTransactionService->listSelectDataDetails($tableName='supplier_details', $columnName='supplier_name', $organisation_id);
        $itemDonor = $this->goodsTransactionService->listSelectDataDetails($tableName='item_donor_details', $columnName='donor_name', $organisation_id);

         $itemVerify = $this->goodsTransactionService->listSelectItemVerify($organisation_id);

         $message = NULL;
       
        return array(
            'goodsreceivedpurchasedForm' => $goodsreceivedpurchasedForm,
            'goodsreceiveddonationForm' => $goodsreceiveddonationForm, 
            'itemSupplier' => $itemSupplier,
            'itemDonor' => $itemDonor,
            'employee_details_id' => $this->employee_details_id,
            'organisation_id' => $organisation_id,
            'itemVerify' => $itemVerify,
            'message' => $message,
            'keyphrase' => $this->keyphrase,
            );
    }



    public function addGoodsReceivedPurchasedAction()
    {
        $this->loginDetails();
        $form = new GoodsSuppliedForm();
        $goodsPurchasedModel = new GoodsReceived();
        $form->bind($goodsPurchasedModel);

        $itemVerify = $this->goodsTransactionService->listSelectItemVerify($this->organisation_id);

        $message = NULL;
        
        $request = $this->getRequest();
        if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
                     $goodsPurchasedData = $this->goodsTransactionService->saveGoodsReceivedPurchased($goodsPurchasedModel);
                      $lastGeneratedId = $goodsPurchasedData->getId();
                      $this->auditTrailService->saveAuditTrail("INSERT", "Item Received Purchased", "ALL", "SUCCESS");
                      $this->auditTrailService->saveAuditTrail("INSERT", "Goods Received", "ALL", "SUCCESS");

                      $this->flashMessenger()->addMessage('Item purchased type successfully added. Please add the item supplied.');
                      return $this->redirect()->toRoute('add-goods-supplied', array('id'=> $this->my_encrypt($lastGeneratedId, $this->keyphrase)));
                }
                 catch(\Exception $e) {
                    $message = 'Failure';
                    $this->flashMessenger()->addMessage($e->getMessage());
                         // Some DB Error happened, log it and let the user know
                }
            }
        }

        return array(
            'form' => $form,
            'organisation_id' => $this->organisation_id,
            'message' => $message,
            'keyphrase' => $this->keyphrase,
            );
    }

    public function addGoodsSuppliedAction()
    {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $form = new GoodsReceivedPurchasedForm($this->serviceLocator);
        $goodsTransactionModel = new GoodsReceived();
        $form->bind($goodsTransactionModel);

       // $submitForm = new SubmitSuppliedGoodsForm();

        $goods_supplier = $this->goodsTransactionService->findGoodsSupplied($id);

         $itemVerify = $this->goodsTransactionService->listSelectItemVerify($this->organisation_id);

         $suppliedGoodsLists = $this->goodsTransactionService->findAllAddedSuppliedGoods($tableName = 'goods_received', $status = 'Supplied', $this->organisation_id, $id);

         $message = NULL;

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
                try{
                    $item_category_id = $this->getRequest()->getPost('item_category_id');
                    $item_sub_category_id = $this->getRequest()->getPost('item_sub_category_id');
                    $item_name_id = $this->getRequest()->getPost('item_name_id');

                    $this->goodsTransactionService->saveGoodsSupplied($goodsTransactionModel, $item_category_id, $item_sub_category_id, $item_name_id);
                    $this->auditTrailService->saveAuditTrail("INSERT", "Goods Received", "ALL", "SUCCESS");

                    $this->flashMessenger()->addMessage('Item Supplied was successfully added');
                    return $this->redirect()->toRoute('add-goods-supplied', array('id' => $this->my_encrypt($id, $this->keyphrase)));
                }
                catch(\Exception $e){
                    $message = 'Failure';
                    $this->flashMessenger()->addMessage($e->getMessage());
                    //Some DB Error happened, log it and let the user know  
                }
            
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
            'goods_supplier' => $goods_supplier,
            'employee_details_id' => $this->employee_details_id,
            //'submitForm' => $submitForm,
            'organisation_id' => $this->organisation_id,
            'suppliedGoodsLists' => $suppliedGoodsLists,
            'itemVerify' => $itemVerify,
            'message' => $message,
            'keyphrase' => $this->keyphrase,
            );
        }else{
            return $this->redirect()->toRoute('add-goods-supplied', array('id'=> $this->my_encrypt($lastGeneratedId, $this->keyphrase)));
        }
    }


    public function generateVoucherAction()
    {
        $this->loginDetails();
        //get the id of item received purchased
        $id = (int) $this->params()->fromRoute('id', 0);

        $form = new GoodsReceivedForm();
        $goodsTransactionModel = new GoodsReceived();
        $form->bind($goodsTransactionModel);

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
                try{
                    $this->goodsTransactionService->saveGoodsReceiptVoucherNo($goodsTransactionModel);

                    return $this->redirect()->toRoute('goods-receipt-voucher', array('id' => $id));
                }
                catch(\Exception $e){
                    $message = 'Failure';
                    $this->flashMessenger()->addMessage($e->getMessage());
                    //Some DB Error happened, log it and let the user know  
                }
            }
        }

        return new ViewModel(array(
           // 'goodsSupplierDetail' => $this->goodsTransactionService->getGoodsSupplierDetails($id),
           // 'suppliedGoodsList' => $this->goodsTransactionService->getSuppliedGoodsList($id),
            'form' => $form,
        ));
    }


    /**
    * Update add goods supplied from Not Supplied to Supplied
    */
    public function updateAddGoodsSuppliedAction()
    {
        $this->loginDetails();
        //get the id of item received purchased
        $id = (int) $this->params()->fromRoute('id', 0);

        //Value 1 is change of status from "Not Supplied" to "Supplied"
        //need to take care of organisation as well
        
        $value = (int) $this->params()->fromRoute('id', 0);
        if($value == $id){
            $status = 'Supplied';
            $previousStatus = 'Not Supplied';
        }
        //$organisation_id = 1;
        
    
         try {
             $this->goodsTransactionService->updateAddGoodsSupplied($status, $previousStatus, $id);
             $this->redirect()->toRoute('add-goods-received');
         }
         catch(\Exception $e) {
                 $message = 'Failure';
                $this->flashMessenger()->addMessage($e->getMessage());
                 // Some DB Error happened, log it and let the user know
         }

        return array();
    }


    //To edit Item Category Action
    public function editAddGoodsSuppliedAction()
    {
        $this->loginDetails();
        $addGoodsSuppliedDetails = $this->goodsTransactionService->findAddGoodsSupplied($this->params('id'));

        //$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');

        $form = new GoodsReceivedPurchasedForm($this->serviceLocator);
        $goodsTransactionModel = new GoodsReceived();
        $form->bind($goodsTransactionModel);

        $itemVerify = $this->goodsTransactionService->listSelectItemVerify($this->organisation_id);

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
                try{
                    $item_category_id = $this->getRequest()->getPost('item_category_id');
                    $item_sub_category_id = $this->getRequest()->getPost('item_sub_category_id');
                    $item_name_id = $this->getRequest()->getPost('item_name_id');
                    $this->goodsTransactionService->saveGoodsSupplied($goodsTransactionModel, $item_category_id, $item_sub_category_id, $item_name_id);
                    $this->auditTrailService->saveAuditTrail("EDITED", "Goods Received", "ALL", "SUCCESS");

                    return $this->redirect()->toRoute('add-goods-supplied', array('id' => $id));
                }
                catch(\Exception $e){
                    $message = 'Failure';
                    $this->flashMessenger()->addMessage($e->getMessage());
                    //Some DB Error happened, log it and let the user know  
                }
            
            }
        }

        return array(
            'form' => $form,
            'addGoodsSuppliedDetails' =>$addGoodsSuppliedDetails,
            'itemVerify' => $itemVerify,
            'organisation_id' => $this->organisation_id
            );
    }

    // To delete add goods supplied
    //To delete Item Supplier Action
    public function deleteAddGoodsSuppliedAction()
    {
         $this->loginDetails();
         
         //get the id of the travel authorization proposal
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            //Get the id of the travel authorization for redirection
            $stock_id = $this->goodsTransactionService->findAddGoodsSupplied($id);
             try {
                 $result = $this->goodsTransactionService->deleteAddGoodsSupplied($id);
                 $encrypted_id = $this->my_encrypt($stock_id, $this->keyphrase);
                 $this->auditTrailService->saveAuditTrail("DELETE", "Goods Received", "ALL", "SUCCESS");
                 $this->flashMessenger()->addMessage("You have successfully deleted the record");
                 return $this->redirect()->toRoute('add-goods-supplied', array('id' => $encrypted_id));
                 //return $this->redirect()->toRoute('emptraveldetails');
             }
             catch(\Exception $e) {
                     die($e->getMessage());
                     // Some DB Error happened, log it and let the user know
             }
        }else {
            return $this->redirect()->toRoute('add-goods-supplied');
        }
    } 


    //To view or display list of Suppliers that they have supplied goods Action
    public function suppliedGoodsListAction()
    {
        $this->loginDetails();

        return new ViewModel(array(
            'allSuppliedGoods' => $this->goodsTransactionService->listAllSuppliedGoods($this->organisation_id),
            'allSuppliedGoodsVG' => $this->goodsTransactionService->listAllSuppliedGoodsVG($this->organisation_id),
            'keyphrase' => $this->keyphrase,
            ));
    }

    
    //To view or display the details list of Goods Supplied by Supplier Action
    public function supplierGoodsListDetailsAction()
    {
        $this->loginDetails();
        //get the student id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        $getSelfLogo = $this->goodsTransactionService->getOrganisationDocument('organisation_document', 'Logo', $this->organisation_id);
        $getOVCLogo = $this->goodsTransactionService->getOrganisationDocument('organisation_document', 'Logo', '1');
        $organizationDetails = $this->goodsTransactionService->getOrganizationDetails($this->organisation_id);
        $organization_details = array();

            foreach($organizationDetails as $detail){
                $organization_details = $detail;
            }
        
        if(is_numeric($id)){
            
            $form = new GoodsReceivedForm();
            $goodsTransactionModel = new GoodsReceived();
            $form->bind($goodsTransactionModel);

            $request = $this->getRequest();
            if($request->isPost()){
                $form->setData($request->getPost());
                if($form->isValid()){
                    try{
                       // $this->goodsTransactionService->saveGoodsReceiptVoucherNo($goodsTransactionModel);

                       // return $this->redirect()->toRoute('goods-receipt-voucher', array('id' => $id));
                    }
                    catch(\Exception $e){
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                        //Some DB Error happened, log it and let the user know  
                    }
                
                }
            }
            
            return new ViewModel(array(
                'goodsSupplierDetail' => $this->goodsTransactionService->getGoodsSupplierDetails($id),
                'suppliedGoodsList' => $this->goodsTransactionService->getSuppliedGoodsList($id),
                'form' => $form,
                'getSelfLogo' => $getSelfLogo,
                'getOVCLogo' => $getOVCLogo,
                'organization_details' => $organization_details,
                ));
        }
        else
        {
            $this->redirect()->toRoute('supplied-goods-list');
        }
    }


    // Function to generate the goods receipt voucher
    public function generateGoodsReceiptVoucherAction()
    {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
             try{
                $this->goodsTransactionService->saveGoodsReceiptVoucherNo($id, $this->organisation_id);
                $this->auditTrailService->saveAuditTrail("INSERT", "Item Received Purchased", "receipt voucher no", "SUCCESS");

                $this->flashMessenger()->addMessage('Goods Receipt Voucher was successfully generated');
                //return $this->redirect()->toRoute('goods-receipt-voucher', array('id' => $this->my_encrypt($id, $this->keyphrase)));
                return $this->redirect()->toRoute('goods-receipt-voucher');
            }

            catch(\Exception $e){
                $message = 'Failure';
                $this->flashMessenger()->addMessage($e->getMessage());
                return $this->redirect()->toRoute('goods-receipt-voucher', array('id' => $this->my_encrypt($id, $this->keyphrase)));
            }

        return array();
        }
        else {
            $this->redirect()->toRoute('supplied-goods-list');
        }
    }

    // To view and print goods receipt voucher from pdf
    public function goodsReceiptVoucherAction()
    {
         //get the id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $pdf = new PdfModel();
            $pdf->setOption('fileName', 'goods-receipt-voucher'); // Triggers PDF download, automatically appends ".pdf"
            //$pdf->setOption('display', PdfModel::DISPLAY_ATTACHMENT);
            $pdf->setOption('paperSize', 'a4'); // Defaults to "8x11"
            $pdf->setOption('paperOrientation', 'landscape'); // Defaults to "portrait"



            //To set view variables
            $pdf->setVariables(array(
               'supplierDetails' => $this->goodsTransactionService->goodsSupplierDetails($id),
               'suppliedGoodsList' => $this->goodsTransactionService->getSuppliedGoodsList($id),
               'userrole' => $this->userrole,
               'storeManagerDetails' => $this->goodsTransactionService->getStoreManagerDetails($this->employee_details_id),
           ));

            return $pdf;
        }
        else{
            $this->redirect()->toRoute('supplied-goods-list');
        }
    }




    //To add Goods Received
    public function addGoodsReceivedDonationAction()
    {
        $this->loginDetails();
        $form = new GoodsReceivedDonationForm($this->serviceLocator);
        $goodsTransactionModel = new GoodsReceived();
        $form->bind($goodsTransactionModel);

        $message = NULL;

        $request = $this->getRequest();
        if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {                
                $item_category_id = $this->getRequest()->getPost('item_category_id');
                $item_sub_category_id = $this->getRequest()->getPost('item_sub_category_id');
                $item_name_id = $this->getRequest()->getPost('item_name_id');
                 try {                    
                     $this->goodsTransactionService->saveGoodsReceivedDonation($goodsTransactionModel, $item_category_id, $item_sub_category_id, $item_name_id);
                     $this->auditTrailService->saveAuditTrail("INSERT", "Goods Received Donation", "ALL", "SUCCESS");

                     $this->flashMessenger()->addMessage('Item Donated was successfully added');
                     return $this->redirect()->toRoute('view-goods-in-stock');
                }
                 catch(\Exception $e) {
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                         // Some DB Error happened, log it and let the user know
                }
            }
        }

         return array(
             'form' => $form,
             'organisation_id' => $this->organisation_id,
             'message' => $message,
        );
    }

     //To view or display list of Item In Stock Action
    public function viewGoodsInStockAction()
    {
        $this->loginDetails();

        $flashMessenger = $this->flashMessenger();

        $purchasedGoodsInStock = array();

        $form = new GoodsSearchForm($this->serviceLocator);

        $message = NULL;

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
                $goodsCategory = $this->getRequest()->getPost('category');
                $goodsSubCategory = $this->getRequest()->getPost('sub_category');
                $goodsName = $this->getRequest()->getPost('item_name');

                //need to get the list of item category, item sub category, item quantity type and item name from the database
                $purchasedGoodsInStock = $this->goodsTransactionService->listAllPuchasedGoodsInStock($goodsCategory, $goodsSubCategory, $goodsName, $this->organisation_id);

            }
        }
       
        return new ViewModel(array(
            'form' => $form,
            'fixedAssetInStock' => $this->goodsTransactionService->listAllFixedAssetInStock($this->organisation_id),
            'consumableGoods' => $this->goodsTransactionService->listAllConsumableAssetInStock($this->organisation_id),
            'donationGoodsInStock' => $this->goodsTransactionService->listAllDonationGoodsInStock($this->organisation_id),
            'transferedGoodsInStock' => $this->goodsTransactionService->listAllTransferedGoodsInStock($this->organisation_id),
            'purchasedGoodsInStock' => $purchasedGoodsInStock,
            'organisation_id' => $this->organisation_id,
            'keyphrase' => $this->keyphrase,
            'message' => $message,
        ));
    }

   // Function to view the details of goods in stock
    public function viewGoodsInStockDetailsAction()
    {
        $this->loginDetails(); 
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $goodsInStockDetails = $this->goodsTransactionService->findGoodsInStockDetails($id);

            $form = new GoodsInStockDetailsForm();

            return array(
               // 'id' = $id,
                'form' => $form,
                'goodsInStockDetails' => $goodsInStockDetails,
                 
            );
        }else{
            return $this->redirect()->toRoute('view-goods-in-stock');
        }
    }

    public function viewDonatedGoodsInStockDetailsAction()
    {
        $this->loginDetails();
         //get the id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $goodsInStockDetails = $this->goodsTransactionService->findDonatedGoodsInStockDetails($id);

            $form = new GoodsInStockDetailsForm();

            return array(
               // 'id' = $id,
                'form' => $form,
                'goodsInStockDetails' => $goodsInStockDetails,
                 
            );
        }else{
            return $this->redirect()->toRoute('view-goods-in-stock');
        }
    }


    public function viewTransferedGoodsInStockDetailsAction()
    {
        $this->loginDetails();
         //get the id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $goodsInStockDetails = $this->goodsTransactionService->findTransferedGoodsInStockDetails($id);

            return array(
                'id' => $id,
                'goodsInStockDetails' => $goodsInStockDetails,
                 
            );
        }else{
            return $this->redirect()->toRoute('view-goods-in-stock');
        }
    }



     //To add Adhoc Issue Goods 
    public function adhocIssueGoodsAction()
    {
        $this->loginDetails();
        $form = new StaffSearchForm();
       
        //get the organisation id
        $organisationID = $this->goodsTransactionService->getOrganisationId($this->username);
        foreach($organisationID as $organisation){
            $organisation_id = $organisation['organisation_id'];
        }

        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                $empName = $this->getRequest()->getPost('employee_name');
                $empId = $this->getRequest()->getPost('emp_id');
                $department = $this->getRequest()->getPost('department');
                $staffList = $this->goodsTransactionService->getStaffList($empName, $empId, $department, $organisation_id);
             }
         }
         else {
             $staffList = array();
         }
        
        return new ViewModel(array(
            'form' => $form,
            'staffList' => $staffList
            ));
    }

    public function addAdhocGoodsIssueAction()
    {
        $this->loginDetails();    

        $form = new IssueGoodsForm($this->serviceLocator);
        $goodsTransactionModel = new IssueGoods();
        $form->bind($goodsTransactionModel);

        $tmp_data = array();

        $indAdhocIssueGoods = $this->goodsTransactionService->listAllAdhocIssueGoods($tableName = 'emp_goods', $status = 'Not Issued', $this->employee_details_id);

        $goods_array = $this->goodsTransactionService->listAllAdhocIssueGoods($tableName = 'emp_goods', $status = 'Not Issued', $this->employee_details_id);
         foreach($goods_array as $tmp){
            $tmp_data[] = $tmp->getId();
        }

        $goodForm = new AdhocGoodsIssueForm($tmp_data);

        $message = NULL;
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 $goods_received_id = $this->getRequest()->getPost('item_received_id');
                 $employee_details_id = $this->getRequest()->getPost('employee_details_id');
                 try {
                    $emp_id = $this->goodsTransactionService->getGoodsIssueToEmployeeId($tableName = 'employee_details', $employee_details_id);

                     $this->goodsTransactionService->saveAdhocIssueGoods($goodsTransactionModel, $goods_received_id, $employee_details_id);
                     $this->notificationService->saveNotification('Individual Adhoc Goods Issue', $emp_id, 'NULL', 'Goods Issue');
                     $this->auditTrailService->saveAuditTrail("INSERT", "emp goods", "ALL", "SUCCESS");

                     $this->flashMessenger()->addMessage('Item was successfully added to issue to individual');
                     return $this->redirect()->toRoute('add-adhoc-goods-issue');
                 }
                 catch(\Exception $e) {
                    $message = 'Failure';
                    $this->flashMessenger()->addMessage($e->getMessage());
                         // Some DB Error happened, log it and let the user know
                 }
             }
         }
         
        return array(
            'form' => $form,
            'goodsForm' => $goodForm,
            'employee_details_id' => $this->employee_details_id,
            'organisation_id' => $this->organisation_id,
            'indAdhocIssueGoods' => $indAdhocIssueGoods,
            'keyphrase' => $this->keyphrase,
            'message' => $message,
            );
    }


    //To delete Item Sub Category Action

    public function deleteAdhocGoodsIssueAction()
    {
        $this->loginDetails();

        //get the student id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            try{
                $id = $this->goodsTransactionService->findAdhocGoodsIssue($id);
            }
            catch(\InvalidArgumentException $e){
                return $this->redirect()->toRoute('add-adhoc-goods-issue');
            }

            $request = $this->getRequest();

            if($request->isPost()){
                $del = $request->getPost('delete_confirmation', 'no');

                if($del == 'yes'){
                    $this->goodsTransactionService->deleteAdhocGoodsIssue($id);
                    $this->auditTrailService->saveAuditTrail("DELETE", "emp goods", "ALL", "SUCCESS");
                }

                return $this->flashMessenger()->addMessage('Item was successfully deleted from issuing to individual');

                return $this->redirect()->toRoute('add-adhoc-goods-issue');
            }

            return array(
                'id' => $id,
                );
        }else{
            return $this->redirect()->toRoute('add-adhoc-goods-issue');
        }
    }


    /*
    * The action is for update the Adhoc Item Issue
    */
    
    public function updateAdhocGoodsIssueAction()
    {
        $this->loginDetails();
        $goods_array = $this->goodsTransactionService->listAllAdhocIssueGoods($tableName = 'emp_goods', $status = 'Not Issued', $this->employee_details_id);
         foreach($goods_array as $tmp){
            $tmp_data[] = $tmp->getId();
        }

       $form = new AdhocGoodsIssueForm($tmp_data);

        //$organisation_id = 1;
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                $data_to_insert = $this->extractFormData1($tmp_data);
                $data_to_insert1 = $this->extractFormData2($tmp_data);
               // var_dump($data_to_insert);
               // die();
    
         try {
             $this->goodsTransactionService->updateAdhocGoodsIssue($data_to_insert, $data_to_insert1);

             $this->flashMessenger()->addMessage('Item was successfully issued to individual');
             return $this->redirect()->toRoute('add-adhoc-goods-issue');
         }
         catch(\Exception $e) {
                 die($e->getMessage());
                 // Some DB Error happened, log it and let the user know
                }
            }
        }   
         
        return array(
            'form' => $form
        );
    }


    /**
    *Action to issue goods based on Requisition
    */

    public function requisitionGoodsIssueAction()
    {
        $this->loginDetails();        
        $form = new RequisitionIssueGoodsForm($this->serviceLocator);
        $goodsTransactionModel = new RequisitionIssueGoods();
        $form->bind($goodsTransactionModel);

        $tmp_data = array();

       $requisitionIssueGoods = $this->goodsTransactionService->listAllRequisitionIssueGoods($tableName = 'emp_goods', $status = 'Not Issued', $this->employee_details_id);


       $goods_array = $this->goodsTransactionService->listAllRequisitionIssueGoods($tableName = 'emp_goods', $status = 'Not Issued', $this->employee_details_id);
        foreach($goods_array as $tmp){
        $tmp_data[] = $tmp->getId();
        } 

        $requisitionGoodsForm = new RequisitionGoodsIssueForm($tmp_data);

        $message = NULL;
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                //var_dump($form);
                //die();
                   $goods_received_id = $this->getRequest()->getPost('goods_received_id');
                   $employee_details_id = $this->getRequest()->getPost('employee_details_id');
                   $goods_requisition_details_id = $this->getRequest()->getPost('goods_requisition_details_id');
                 try {
                    $emp_id = $this->goodsTransactionService->getGoodsIssueToEmployeeId($tableName = 'employee_details', $employee_details_id);

                    $this->goodsTransactionService->saveRequisitionIssueGoods($goodsTransactionModel, $goods_received_id, $employee_details_id, $goods_requisition_details_id);
                    $this->notificationService->saveNotification('Individual Requisition Goods Issue', $emp_id, 'NULL', 'Goods Issue');
                     $this->auditTrailService->saveAuditTrail("INSERT", "emp goods", "ALL", "SUCCESS");

                    $this->flashMessenger()->addMessage('Item was successfully added to issue to individual based on their requisition');
                    return $this->redirect()->toRoute('requisition-goods-issue');
                 }
                 catch(\Exception $e) {
                         die($e->getMessage());
                         // Some DB Error happened, log it and let the user know
                 }
             }
         }
         
        return array(
            'form' => $form,
            'requisitionGoodsForm' => $requisitionGoodsForm,
            'employee_details_id' => $this->employee_details_id,
            'organisation_id' => $this->organisation_id,
            'requisitionIssueGoods' => $requisitionIssueGoods,
            'keyphrase' => $this->keyphrase,
            'message' => $message,
            );
    }


    public function deleteRequisitionGoodsIssueAction()
    {
        $this->loginDetails();

        //get the student id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            try{
                $id = $this->goodsTransactionService->findRequisitionGoodsIssue($id);
            }
            catch(\InvalidArgumentException $e){
                return $this->redirect()->toRoute('requisition-goods-issue');
            }

            $request = $this->getRequest();

            if($request->isPost()){
                $del = $request->getPost('delete_confirmation', 'no');

                if($del == 'yes'){
                    $this->goodsTransactionService->deleteRequisitionGoodsIssue($id);
                    $this->auditTrailService->saveAuditTrail("DELETE", "emp goods", "ALL", "SUCCESS");
                }

                $this->flashMessenger()->addMessage('Item was successfully deleted from issuing');
                return $this->redirect()->toRoute('requisition-goods-issue');
            }

            return array(
                'id' => $id,
                );
        }else{
            return $this->redirect()->toRoute('requisition-goods-issue');
        }
    }


    /*
    * The action is for update the Requisition Goods Issue
    */
    
    public function updateRequisitionGoodsIssueAction()
    {
        $this->loginDetails();
        $goods_array = $this->goodsTransactionService->listAllRequisitionIssueGoods($tableName = 'emp_goods', $status = 'Not Issued', $this->employee_details_id);
         foreach($goods_array as $tmp){
            $tmp_data[] = $tmp->getId();
        }

       $form = new RequisitionGoodsIssueForm($tmp_data);

        //$organisation_id = 1;
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                $data_to_insert = $this->extractFormData1($tmp_data);
                $data_to_insert1 = $this->extractFormData2($tmp_data);
    
         try {
             $this->goodsTransactionService->updateRequisitionGoodsIssue($data_to_insert, $data_to_insert1);

             $this->flashMessenger()->addMessage('Item was successfully issued to individual based on their requisition');
             return $this->redirect()->toRoute('requisition-goods-issue');
         }
         catch(\Exception $e) {
            $message = 'Failure';
            $this->flashMessenger()->addMessage($e->getMessage());
                 // Some DB Error happened, log it and let the user know
                }
            }
        }             
         
        return array(
            'form' => $form
        );
    }

  

     //To add Adhoc Issue Goods 
    public function subStoreIssueGoodsAction()
    {
        $this->loginDetails();
        $form = new DeptGoodsForm($this->serviceLocator);
        $goodsTransactionModel = new DeptGoods();
        $form->bind($goodsTransactionModel);

       // $itemName = $this->goodsTransactionService->listSelectAddSubStoreData($tableName = 'goods_received', $organisation_id);
        $tmp_data = array();

        $deptIssueGoods = $this->goodsTransactionService->listAllDeptIssueGoods($tableName = 'department_goods', $status = 'Not Issued', $this->employee_details_id);

        $goods_array = $this->goodsTransactionService->listAllDeptIssueGoods($tableName = 'department_goods', $status = 'Not Issued', $this->employee_details_id);
        foreach($goods_array as $tmp){
            $tmp_data[] = $tmp->getId();
        }

        $goodForm = new DeptGoodsIssueForm($tmp_data);

        $message = NULL;

        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());

             if ($form->isValid()) {
                //$data = $this->params()->fromPost();
                $goods_received_id = $this->getRequest()->getPost('item_received_id');
                $goods_received_by = $this->getRequest()->getPost('goods_received_by');
                 try {
                    $emp_id = $this->goodsTransactionService->getGoodsIssueToEmployeeId($tableName = 'employee_details', $goods_received_by);

                     $this->goodsTransactionService->saveSubStoreIssueGoods($goodsTransactionModel, $goods_received_id, $goods_received_by);
                     $this->notificationService->saveNotification('Sub Store Goods Issue', $emp_id, 'NULL', 'Goods Issue');
                     $this->auditTrailService->saveAuditTrail("INSERT", "department_goods", "ALL", "SUCCESS");

                     $this->flashMessenger()->addMessage('Item was successfully added to issue to sub-store');
                     return $this->redirect()->toRoute('sub-store-issue-goods');
                 }
                 catch(\Exception $e) {
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                         // Some DB Error happened, log it and let the user know
                 }
             }
         }
         
        return array(
            'form' => $form,
            'goodsForm' => $goodForm,
            'employee_details_id' => $this->employee_details_id,
            'organisation_id' => $this->organisation_id,
            'deptIssueGoods' => $deptIssueGoods,
            'keyphrase' => $this->keyphrase,
            'message' => $message,
            );
    }



     public function addSubStoreIssueGoodsAction()
    {
        $this->loginDetails();
        //get the department id
        $id = (int) $this->params()->fromRoute('id', 0);
        
        $form = new DeptGoodsForm();
        $goodsTransactionModel = new DeptGoods();
        $form->bind($goodsTransactionModel);

        $addSubStoreGoodsIssue = array();

        $submitForm = new SubmitIssueGoodsForm();
        
        $department = $this->goodsTransactionService->getDeptDetails($id);
        $deptStaffList = $this->goodsTransactionService->getDeptStaffList($id, $this->organisation_id);
        $goodsReceiverList = $this->goodsTransactionService->getGoodsReceiverList($this->organisation_id);
        $departmentList = $this->goodsTransactionService->getDepartmentList($this->organisation_id);
        $goodsReceiverDetails = $this->goodsTransactionService->getGoodsReceiverDetails($this->organisation_id);

        $itemName = $this->goodsTransactionService->listSelectAddSubStoreData($tableName = 'goods_received', $this->organisation_id);
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                var_dump($form);
                die();
                 try {
                     $this->goodsTransactionService->saveSubStoreIssueGoods($goodsTransactionModel);
                     $this->redirect()->toRoute('add-sub-store-goods-issue');
                 }
                 catch(\Exception $e) {
                         die($e->getMessage());
                         // Some DB Error happened, log it and let the user know
                 }
             }
         }
         
        return array(
            'id' => $id,
            'form' => $form,
            'department' => $department,
            'deptStaffList' => $deptStaffList,
            'goodsReceiverList' => $goodsReceiverList,
            'departmentList' => $departmentList,
            'goodsReceiverDetails' => $goodsReceiverDetails,
            'itemName' => $itemName,
            'submitForm' => $submitForm,
            'submitDeptIssueGoods' => $this->goodsTransactionService->listAllDeptIssueGoods($status = 'Not Issued')
            );
    }


    /*
    * The action is for update the Main Store to Dept Item Issue
    */
    
    public function updateSubStoreIssueGoodsAction()
    {
        $this->loginDetails();
        $goods_array = $this->goodsTransactionService->listAllDeptIssueGoods($tableName = 'department_goods', $status = 'Not Issued', $this->employee_details_id);
        foreach($goods_array as $tmp){
            $tmp_data[] = $tmp->getId();
        }

        $form = new DeptGoodsIssueForm($tmp_data);

        //$organisation_id = 1;
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                $data_to_insert = $this->extractFormData($tmp_data);
              //  var_dump($data_to_insert);
               // die();
    
         try {
             $this->goodsTransactionService->updateSubStoreIssueGoods($data_to_insert);
             $this->flashMessenger()->addMessage('Item was successfully issued to sub-store');
             return $this->redirect()->toRoute('sub-store-issue-goods');
         }
         catch(\Exception $e) {
                 die($e->getMessage());
                 // Some DB Error happened, log it and let the user know
                }
            }
        }             
         
        return array(
            'form' => $form
        );
    }



    // Function to apply for sub store goods surrender
    //To apply for Goods Surrender

    public function applyDeptGoodsSurrenderAction()
    {
        $this->loginDetails();

        // get the id of the item sub category
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $form = new DeptGoodsSurrenderForm();
            $goodsTransactionModel = new DeptGoodsSurrender();
            $form->bind($goodsTransactionModel);

            $deptGoodsSurrender = $this->goodsTransactionService->findDeptGoodsDetails($id);

            //check if the applicant has applied or not
            $message = NULL;
            $check_dept_goods_surrender = $this->goodsTransactionService->crossCheckDeptGoodsSurrender('Pending', $id, $this->departments_units_id);
            if($check_dept_goods_surrender){
                $message = 'Failure';
                $this->flashMessenger()->addMessage('You have alreday applied for goods surrender for this particular item and its in pending. You can apply after some time!');
            }else{
                $request = $this->getRequest();
                if($request->isPost()){
                    $form->setData($request->getPost());
                    $data = $this->params()->fromPost();
                    $surrender_quantity = $data['deptgoodssurrender']['surrender_quantity'];
                    $check_surrender_quantity = $this->goodsTransactionService->crossCheckDeptGoodsSurrenderQty($id, $surrender_quantity);
                    if($check_surrender_quantity){
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage("Sorry, you can't apply for the goods surrender since the surrender quantity is more than the quantity you have. Try with less quantity!");
                        }else{
                            if($form->isValid()){
                                try{
                                $this->goodsTransactionService->saveDeptGoodsSurrender($goodsTransactionModel);
                                $this->notificationService->saveNotification('Department Goods Surrender', $this->employee_details_id, $this->departments_id, 'Goods Surrender');
                                 $this->auditTrailService->saveAuditTrail("INSERT", "sub_store_goods_surrender", "ALL", "SUCCESS");

                                 $this->flashMessenger()->addMessage('You have successfully applied for Sub-store goods surrender');
                                 return $this->redirect()->toRoute('view-dept-goods-list');
                                }
                                catch(\Exception $e){
                                    $message = 'Failure';
                                    $this->flashMessenger()->addMessage($e->getMessage());
                                    return $this->redirect()->toRoute('view-dept-goods-list');
                                    //Some DB Error happened, log it and let the user know  
                                }
                            } 
                        }                         
                    }
            }
           

            return array(
                'id' => $id,
                'form' => $form,
                'deptGoodsSurrender' =>$deptGoodsSurrender,
                'employee_details_id' => $this->employee_details_id,
                'message' => $message,
                );
        }else{
            $this->redirect()->toRoute('view-dept-goods-list');
        }
    }

    //To delete Item Sub Category Action

    public function deleteSubStoreGoodsIssueAction()
    {
        $this->loginDetails();

        //get the student id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            try{
                $id = $this->goodsTransactionService->findSubStoreGoodsIssue($id);
            }
            catch(\InvalidArgumentException $e){
                return $this->redirect()->toRoute('sub-store-issue-goods');
            }

            $request = $this->getRequest();

            if($request->isPost()){
                $del = $request->getPost('delete_confirmation', 'no');

                if($del == 'yes'){
                    $this->goodsTransactionService->deleteSubStoreGoodsIssue($id);
                    $this->auditTrailService->saveAuditTrail("DELETE", "sub_store_goods_surrender", "ALL", "SUCCESS");
                }
                $this->flashMessenger()->addMessage('Item successfully deleted from issuing');
                return $this->redirect()->toRoute('sub-store-issue-goods');
            }

            return array(
                'id' => $id,
                );
        }else{
            return $this->redirect()->toRoute('sub-store-issue-goods');
        }
    }


     /*
    * The action is for section/HOD to nominate the responsible staff to receive sub store goods
    */

     public function nominateSubStoreResponsibleAction()
     {
        $this->loginDetails();
       // $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'); 

        $form = new NominateSubStoreForm($this->serviceLocator);
        $goodsTransactionModel = new NominateSubStore();
        $form->bind($goodsTransactionModel);

        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());

             if ($form->isValid()) {
                //$data = $this->params()->fromPost();
               // $goods_received_id = $this->getRequest()->getPost('item_received_id');
               // $goods_received_by = $this->getRequest()->getPost('goods_received_by');
                 try {
                     $this->goodsTransactionService->saveSubStoreNomination($goodsTransactionModel);
                     $this->redirect()->toRoute('nominate-sub-store-responsible');
                 }
                 catch(\Exception $e) {
                         die($e->getMessage());
                         // Some DB Error happened, log it and let the user know
                 }
             }
         }
         
        return array(
            'form' => $form,
            'employee_details_id' => $this->employee_details_id,
            'organisation_id' => $this->organisation_id,
            'departments_id' => $this->departments_id,
            'nomineeList' => $this->goodsTransactionService->listAllSubStoreNominee($tableName = 'sub_store_nominee', $this->departments_id),
            );
     }


     //To add Adhoc Issue Goods 
    public function subStoreGoodsAction()
    {
        $this->loginDetails();
        //ajax actions
       //  $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');

        $form = new SubStoreSearchForm($this->serviceLocator);
       
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                $department = $this->getRequest()->getPost('department');
                $goodsIssuedList = $this->goodsTransactionService->getDepartmentList($department, $this->organisation_id);
             }
         }
         else {
             $goodsIssuedList = array();
         }

        return new ViewModel(array(
            'form' => $form,
            'goodsIssuedList' => $goodsIssuedList
            ));
    }



    public function addSubStoreToIndIssueGoodsAction()
    {
        $this->loginDetails();        
        $form = new DeptIssueGoodsForm($this->serviceLocator);
        $goodsTransactionModel = new DeptIssueGoods();
        $form->bind($goodsTransactionModel);

        $tmp_data = array();

        //$submitForm = new SubmitIssueGoodsForm();

        $itemName = $this->goodsTransactionService->listSelectSubStoreToIndData($tableName = 'department_goods', $this->departments_units_id, $this->employee_details_id);

       // $empName = $this->goodsTransactionService->listSelectEmpData($this->organisation_id);

        $individualIssueGoods = $this->goodsTransactionService->listAllIndIssueGoods($tableName = 'emp_goods', $status = 'Not Issued', $this->employee_details_id);

        $goods_array = $this->goodsTransactionService->listAllIndIssueGoods($tableName = 'emp_goods', $status = 'Not Issued', $this->employee_details_id);
         foreach($goods_array as $tmp){
            $tmp_data[] = $tmp->getId();
        }

        $subStoreGoodsForm = new SubStoreToIndIssueForm($tmp_data);

        $message = NULL;
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                $departments_id = $this->getRequest()->getPost('departments_id');
                 $employee_details_id = $this->getRequest()->getPost('employee_details_id');
                 $department_goods_id = $this->getRequest()->getPost('department_goods_id');
                 try {
                    $emp_id = $this->goodsTransactionService->getGoodsIssueToEmployeeId($tableName = 'employee_details', $employee_details_id);

                     $this->goodsTransactionService->saveSubStoreToIndIssueGoods($goodsTransactionModel, $departments_id, $employee_details_id, $department_goods_id);
                     $this->notificationService->saveNotification('Department To Individual Goods Issue', $emp_id, 'NULL', 'Goods Issue');
                     $this->auditTrailService->saveAuditTrail("INSERT", "emp_goods", "ALL", "SUCCESS");

                     $this->flashMessenger()->addMessage('Item was successfully added to issue to individual from sub-store');
                     return $this->redirect()->toRoute('add-sub-store-to-ind-issue-goods');
                 }
                 catch(\Exception $e) {
                    $message = 'Failure';
                    $this->flashMessenger()->addMessage($e->getMessage());
                         // Some DB Error happened, log it and let the user know
                 }
             }
         }

        return array(
            'form' => $form,
            'employee_details_id' => $this->employee_details_id,
            //'empName' => $empName,
            'itemName' => $itemName,
            //'submitForm' => $submitForm,
            'subStoreGoodsForm' => $subStoreGoodsForm,
            'individualIssueGoods' => $individualIssueGoods,
            'keyphrase' => $this->keyphrase,
            'message' => $message,
            );
    }


    /*
    * The action is for update the Dept to Individual Item Issue
    */
    
    public function updateSubStoreToIndIssueGoodsAction()
    {
        $this->loginDetails();
        $goods_array = $this->goodsTransactionService->listAllIndIssueGoods($tableName = 'emp_goods', $status = 'Not Issued', $this->employee_details_id);
        foreach($goods_array as $tmp){
            $tmp_data[] = $tmp->getId();
        }

        $form = new SubStoreToIndIssueForm($tmp_data);

        //$organisation_id = 1;
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                $data_to_insert = $this->extractFormData1($tmp_data);
                $data_to_insert1 = $this->extractFormData2($tmp_data);
              //  var_dump($data_to_insert);
               // die();
    
         try {
             $this->goodsTransactionService->updateSubStoreToIndIssueGoods($data_to_insert, $data_to_insert1);

             $this->flashMessenger()->addMessage('Item was successfully issued to individual');
             return $this->redirect()->toRoute('add-sub-store-to-ind-issue-goods');
         }
         catch(\Exception $e) {
                 die($e->getMessage());
                 // Some DB Error happened, log it and let the user know
                }
            }
        } 
         
        return array(
            'form' => $form
        );
    }


    //To delete Item Sub Category Action

    public function deleteSubStoreToIndIssueGoodsAction()
    {
        $this->loginDetails();

        // get the id of the item sub category
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            try{
                $id = $this->goodsTransactionService->findSubStoreToIndGoodsIssue($id);
            }
            catch(\InvalidArgumentException $e){
                return $this->redirect()->toRoute('add-sub-store-to-ind-issue-goods');
            }

            $request = $this->getRequest();

            if($request->isPost()){
                $del = $request->getPost('delete_confirmation', 'no');

                if($del == 'yes'){
                    $this->goodsTransactionService->deleteSubStoreToIndIssueGoods($id);
                }
                $this->flashMessenger()->addMessage('Item was successfully deleted from issuing');
                return $this->redirect()->toRoute('add-sub-store-to-ind-issue-goods');
            }

            return array(
                'id' => $id,
                );
        }else{
            return $this->redirect()->toRoute('add-sub-store-to-ind-issue-goods');
        }
    }


      //To view or display list of Item In Stock Action

    public function viewEmpIssuedGoodsAction()
    {
        $this->loginDetails();
        $empIssueGoods = array();
      //  $donationGoodsInStock = array();

        $form = new DeptStaffSearchForm($this->serviceLocator);

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
                $departmentId = $this->getRequest()->getPost('department');
                //need to get the list of item category, item sub category, item quantity type and item name from the database
                $empIssueGoods = $this->goodsTransactionService->listAllEmpIssuedGoods($departmentId, $this->organisation_id);
            }
        }
       
        return new ViewModel(array(
            'form' => $form,
            'empIssueGoods' => $empIssueGoods,
            'keyphrase' => $this->keyphrase,
            'organisation_id' => $this->organisation_id,
        ));
    }
        
     /*   return new ViewModel(array(
            'empIssueGoods' => $this->goodsTransactionService->listAllEmpIssuedGoods($this->organisation_id)
            ));
    }*/

    //To view or display list of Item In Stock Action

    public function empGoodsListDetailsAction()
    {  
        $this->loginDetails(); 
        // get the id of the item sub category
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $form = new EmpIssueGoodsForm();
            $goodsTransactionModel = new GoodsTransaction();
            $form->bind($goodsTransactionModel);

            $staffDetails = $this->goodsTransactionService->getEmployeeDetails($id);

            $request = $this->getRequest();
            if($request->isPost()){
                $form->setData($request->getPost());
                if($form->isValid()){

                    try{
                     //   $this->goodsTransactionService->saveGoodsReceiptVoucherNo($goodsTransactionModel);

                       // return $this->redirect()->toRoute('goods-receipt-voucher', array('id' => $id));
                    }
                    catch(\Exception $e){
                        die($e->getMessage());
                        //Some DB Error happened, log it and let the user know  
                    }
                
                }
            }
            
            return new ViewModel(array(
                'form' => $form,
                'staffDetails' => $staffDetails,
                'goodsListDetails' => $this->goodsTransactionService->getStaffGoodsDetails($id),
                ));
        }else{
            $this->redirect()->toRoute('view-emp-issued-goods');
        }       
    }


    //To view or display list of Item of staff (self) Action

    public function empGoodsListAction()
    {
        $this->loginDetails();

        $flashMessenger = $this->flashMessenger();

        $message = NULL;

        return new ViewModel(array(

            'fixedAssetGoodsList' => $this->goodsTransactionService->listEmpAllFixedAssetLists($this->employee_details_id),
            'consumableGoodsList' => $this->goodsTransactionService->listEmpAllConsumableGoodsLists($this->employee_details_id),
            'keyphrase' => $this->keyphrase,
            'message' => $message,
            ));
    }


    //To apply for Goods Surrender

    public function editIssueGoodsAction()
    {
        $this->loginDetails();
        $goodsIssueDetails = $this->goodsTransactionService->findIssueGoodsDetails($this->params('id'));
        
        $form = new IssueGoodsForm();
        $goodsTransactionModel = new IssueGoods();
        $form->bind($goodsTransactionModel);

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
                try{
                    //$this->goodsTransactionService->saveGoodsSurrender($goodsTransactionModel);

                    return $this->redirect()->toRoute('view-issue-goods');
                }
                catch(\Exception $e){
                    die($e->getMessage());
                    //Some DB Error happened, log it and let the user know  
                }
            
            }
        }

        return array(
            'form' => $form,
            'goodsIssueDetails' =>$goodsIssueDetails,
            );
    }


    //To apply for Goods Surrender

    public function applyGoodsSurrenderAction()
    {
        $this->loginDetails();

        // get the id of the item sub category
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $form = new GoodsSurrenderForm();
            $goodsTransactionModel = new GoodsSurrender();
            $form->bind($goodsTransactionModel);

            $goodsSurrenderDetails = $this->goodsTransactionService->findEmpGoodsDetails($id);

            //check if the applicant has applied or not
            $message = NULL;

            $checkEmpGoodssurrender = $this->goodsTransactionService->crossCheckEmpGoodsSurrender('Pending', $id, $this->employee_details_id);
            if($checkEmpGoodssurrender){
                $message = 'Failure';
                $this->flashMessenger()->addMessage('You have already applied for this goods surrender and it is still pending. Please try after some time!');
            }else{
                $request = $this->getRequest();
                if($request->isPost()){
                    $form->setData($request->getPost());
                    $data = $this->params()->fromPost();
                    $surrenderQuantity = $data['goodssurrender']['surrender_quantity'];
                    $check_surrender_quantity = $this->goodsTransactionService->crossCheckEmpGoodsSurrenderQty($surrenderQuantity, $id, $this->employee_details_id);
                    if($check_surrender_quantity){
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage("Sorry, you can't apply for goods surrender since the surrender quantity of this particular item is more than the quantity you have!");
                    }else{
                        if($form->isValid()){
                            try{
                                $this->goodsTransactionService->saveGoodsSurrender($goodsTransactionModel);
                                $this->notificationService->saveNotification('Department To Individual Goods Issue', $this->employee_details_id, $this->departments_id, 'Goods Issue');
                                 $this->auditTrailService->saveAuditTrail("INSERT", "goods_surrender", "ALL", "SUCCESS");

                                $this->flashMessenger()->addMessage('You have successfully applied for goods surrender');
                                return $this->redirect()->toRoute('emp-goods-list');
                                }
                                catch(\Exception $e){
                                    $message = 'Failure';
                                    $this->flashMessenger()->addMessage($e->getMessage());
                                    //Some DB Error happened, log it and let the user know  
                                }
                            }   
                        }
                                               
                    }
            }

            return array(
                'id' => $id,
                'form' => $form,
                'goodsSurrenderDetails' =>$goodsSurrenderDetails,
                'message' => $message,
                'employee_details_id' => $this->employee_details_id,
                );
        }else{
             return $this->redirect()->toRoute('emp-goods-list');
        }
    }


   public function consumeEmpConsumableGoodsAction()
    {
        $this->loginDetails();
        // get the id of the item sub category
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            try {
                 $this->goodsTransactionService->updateEmpConsumableGoods($status='Consumed', $previousStatus=NULL, $id);
                 $this->auditTrailService->saveAuditTrail("UPDATE", "emp_goods", "emp_quantity,issue_goods_status", "SUCCESS");

                 $this->flashMessenger()->addMessage('You have successfully consumed consumable goods');
                 return $this->redirect()->toRoute('emp-goods-list');
             }
             catch(\Exception $e) {
                     die($e->getMessage());
                     // Some DB Error happened, log it and let the user know
             }
             
            return array();
            }else{
                $this->redirect()->toRoute('emp-goods-list');
        }
    } 


    //To view or display list of Goods Surrender applied

    public function appliedGoodsSurrenderListAction()
    {
        $this->loginDetails();
        //get the organisation id
        $organisationID = $this->goodsTransactionService->getOrganisationId($this->username);
        foreach($organisationID as $organisation){
            $organisation_id = $organisation['organisation_id'];
        }

        return new ViewModel(array(
            'surrenderList' => $this->goodsTransactionService->listEmpAllSurrenderedGoods($organisation_id)
            ));
    }

    public function goodsSurrenderListAction()
    {
        $this->loginDetails();

        return new ViewModel(array(
            'surrenderList' => $this->goodsTransactionService->findAllGoodsSurrenderList($this->employee_details_id),

        ));
    }


      //To view or display list of Applied Goods Surrender List Action

    public function empGoodsSurrenderListAction()
    {
        $this->loginDetails();

        $message = NULL;

        return new ViewModel(array(
            'empSurrenderList' => $this->goodsTransactionService->listAllEmpSurrenderGoods($this->organisation_id),
            'keyphrase' => $this->keyphrase,
            'message' => $message,
            ));
    }


      //To view or display list of sub store Applied Goods Surrender List Action

    public function empSubStoreGoodsSurrenderListAction()
    {
        $this->loginDetails();

        $message = NULL;

        return new ViewModel(array(
            'subStoreSurrenderList' => $this->goodsTransactionService->listAllEmpSubStoreSurrenderGoods($this->organisation_id, $this->departments_units_id),
            'keyphrase' => $this->keyphrase,
            'message' => $message,
            ));
    }

    //To view or display list of Item that particular staff applied for surrender Action

    public function empGoodsSurrenderListDetailsAction()
    {
        $this->loginDetails();   

        // get the id of the item sub category
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $form = new GoodsSurrenderForm();
            $goodsTransactionModel = new GoodsSurrender();
            $form->bind($goodsTransactionModel);
            
            $staffSurrenderGoods = $this->goodsTransactionService->getStaffGoodsSurrenderDetails($id);
            
            $request = $this->getRequest();
             if ($request->isPost()) {
                 $form->setData($request->getPost());
                 if ($form->isValid()) {
                     try {
                        // $this->goodsTransactionService->saveResponsibility($goodsTransactionModel);
                       //  $this->redirect()->toRoute('responsibilitycategory');
                     }
                     catch(\Exception $e) {
                             die($e->getMessage());
                             // Some DB Error happened, log it and let the user know
                     }
                 }
             }

            return new ViewModel(array(
                'id' => $id,
                'form' => $form,
                'staffSurrenderGoods' => $staffSurrenderGoods,
                'keyphrase' => $this->keyphrase,
                'staffSurrenderGoodsList' => $this->goodsTransactionService->getGoodsSurrenderDetails($id)

                //'empAllGoods' => $this->goodsTransactionService->listEmpAllGoods()
                ));
        }else{
            $this->redirect()->toRoute('emp-goods-surrender-list');
        }
    }

    //To view or display list of Item that particular staff applied for surrender Action

    public function empSubStoreGoodsSurrenderListDetailsAction()
    {
        $this->loginDetails();

       // get the id of the item sub category
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
             $form = new GoodsSurrenderForm();
            $goodsTransactionModel = new GoodsSurrender();
            $form->bind($goodsTransactionModel);
            
            $staffSurrenderGoods = $this->goodsTransactionService->getStaffGoodsSurrenderDetails($id);
            
            $request = $this->getRequest();
             if ($request->isPost()) {
                 $form->setData($request->getPost());
                 if ($form->isValid()) {
                     try {
                         
                     }
                     catch(\Exception $e) {
                             die($e->getMessage());
                             // Some DB Error happened, log it and let the user know
                     }
                 }
             }

            return new ViewModel(array(
                'id' => $id,
                'form' => $form,
                'staffSurrenderGoods' => $staffSurrenderGoods,
                'keyphrase' => $this->keyphrase,
                'subStoreStaffSurrenderGoodsList' => $this->goodsTransactionService->getSubStoreGoodsSurrenderDetails($this->departments_units_id, $id)

                //'empAllGoods' => $this->goodsTransactionService->listEmpAllGoods()
                ));
        }else{
            $this->redirect()->toRoute('emp-sub-store-goods-surrender-list');
        }
    }


    public function approveEmpGoodsSurrenderAction()
    {
        $this->loginDetails();
        //get the id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            try {
                 $this->goodsTransactionService->updateEmpGoodsSurrender($status='Approved', $previousStatus=NULL, $id);
                 $this->auditTrailService->saveAuditTrail("UPDATE", "goods_surrender", "ALL", "SUCCESS");

                 $this->flashMessenger()->addMessage('You have successfully approved goods surrendered');
                 return $this->redirect()->toRoute('emp-goods-surrender-list');
             }
             catch(\Exception $e) {
                     die($e->getMessage());
                     // Some DB Error happened, log it and let the user know
             }
             
            return array();
        }else{
            return $this->redirect()->toRoute('emp-goods-surrender-list');
        }
    }



    public function approveEmpSubStoreSurrenderAction()
    {
        $this->loginDetails();
        //get the id of the emp to sub store surrender goods
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            try {
                 $this->goodsTransactionService->updateEmpSubStoreSurrender($status='Approved', $previousStatus=NULL, $id);
                         $this->auditTrailService->saveAuditTrail("UPDATE", "goods_surrender", "ALL", "SUCCESS");

                 $this->flashMessenger()->addMessage('You have successfully approved staff surredered goods');
                 return $this->redirect()->toRoute('emp-sub-store-goods-surrender-list');
             }
             catch(\Exception $e) {
                     die($e->getMessage());
                     // Some DB Error happened, log it and let the user know
             }
             
            return array();
        }else{
            return $this->redirect()->toRoute('emp-sub-store-goods-surrender-list');
        }
    }


    /*
    * The action is to update the Emp Goods Surrender
    */
    
    public function updateEmpGoodsSurrenderlAction()
    {
        $this->loginDetails();

        $value = (int) $this->params()->fromRoute('id', 0);
        if($value == 1){
            $status = 'Pending';
            $previousStatus = 'Approved';
        }
    
         try {
             $this->goodsTransactionService->updateEmpGoodsSurrender($status, $previousStatus, $id = NULL);
             $this->redirect()->toRoute('emp-goods-surrender-list');
         }
         catch(\Exception $e) {
                 die($e->getMessage());
                 // Some DB Error happened, log it and let the user know
         }             
         
        return array();
    }     



    public function empSurrenderGoodsDetailAction()
    {
        $this->loginDetails();
        //get the id of the goods surrendered
        $id = (int) $this->params()->fromRoute('id', 0);
        
        $surrenderdGoods = $this->goodsTransactionService->findGoodsSurrenderList($id);
        
        $form = new GoodsSurrenderForm();
        $goodsTransactionModel = new GoodsTransaction();
        $form->bind($goodsTransactionModel);

         $request = $this->getRequest();
         if ($request->isPost()) {
             //the following set of code is to get the value from the submit buttons
             $postData = $this->getRequest()->getPost();
             foreach ($postData as $key => $value)
             {
                 if($key == 'hrdplanapproval')
                 {
                     $hrdData = $value;
                     if(array_key_exists('approve', $hrdData))
                         $submitValue = 'Approved';
                     else 
                        $submitValue = 'Rejected';
                 }
             }
             
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
                     $this->goodsTransactionService->updateProposal($goodsTransactionModel, $submitValue);
                     $this->redirect()->toRoute('hrdapprovedlist');
                 }
                 catch(\Exception $e) {
                         die($e->getMessage());
                         // Some DB Error happened, log it and let the user know
                 }
             }
         }

         return array(
             'form' => $form,
             'surrenderdGoods' => $surrenderdGoods
         );
    }


     //To apply for Goods Surrender

    public function viewGoodsSurrenderDetailsAction()
    {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $surrenderDetails = $this->goodsTransactionService->findGoodsSurrenderDetails($id);
        
            $form = new GoodsSurrenderForm();
            $goodsTransactionModel = new GoodsSurrender();
            $form->bind($goodsTransactionModel);

            $request = $this->getRequest();
            if($request->isPost()){
                $form->setData($request->getPost());
                if($form->isValid()){

                    try{
                      //  $this->goodsTransactionService->saveGoodsSurrender($goodsTransactionModel);

                       // return $this->redirect()->toRoute('emp-goods-list');
                    }
                    catch(\Exception $e){
                        die($e->getMessage());
                        //Some DB Error happened, log it and let the user know  
                    }
                
                }
            }

            return array(
                'form' => $form,
                'surrenderDetails' =>$surrenderDetails,
                );
        }else{
            return $this->redirect()->toRoute('emp-goods-surrender-list');
        }
    }


    //To apply for Goods Surrender

    public function viewSubStoreGoodsSurrenderDetailsAction()
    {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $subStoreSurrender = $this->goodsTransactionService->findSubStoreGoodsSurrenderDetails($id);
        
            $form = new GoodsSurrenderForm();
            $goodsTransactionModel = new GoodsSurrender();
            $form->bind($goodsTransactionModel);

            $request = $this->getRequest();
            if($request->isPost()){
                $form->setData($request->getPost());
                if($form->isValid()){

                    try{
                      //  $this->goodsTransactionService->saveGoodsSurrender($goodsTransactionModel);

                       // return $this->redirect()->toRoute('emp-goods-list');
                    }
                    catch(\Exception $e){
                        die($e->getMessage());
                        //Some DB Error happened, log it and let the user know  
                    }
                
                }
            }

            return array(
                'form' => $form,
                'subStoreSurrender' =>$subStoreSurrender,
                );
        }else{
            return $this->redirect()->toRoute('empsubstoregoodssurrenderlist');
        }
    }


      //To view or display list of Applied Goods Surrender List Action

    public function subStoreSurrenderListAction()
    {
        $this->loginDetails();

        $message = NULL;

        return new ViewModel(array(
            'subStoreSurrenderList' => $this->goodsTransactionService->listAllSubStoreSurrenderGoods($this->organisation_id),
            'keyphrase' => $this->keyphrase,
            'message' => $message,
            ));
    }


        //To view or display list of Item that particular staff applied for surrender Action

    public function subStoreSurrenderGoodsListsAction()
    {   
        $this->loginDetails();

        // get the id of the item sub category
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $form = new DeptGoodsSurrenderForm();
            $goodsTransactionModel = new DeptGoodsSurrender();
            $form->bind($goodsTransactionModel);
            
            $subStoreDetails = $this->goodsTransactionService->getSubStoreDetails($id);
            
            $request = $this->getRequest();
             if ($request->isPost()) {
                 $form->setData($request->getPost());
                 if ($form->isValid()) {
                     try {
                      //   $this->goodsTransactionService->saveResponsibility($goodsTransactionModel);
                       //  $this->redirect()->toRoute('responsibilitycategory');
                     }
                     catch(\Exception $e) {
                             die($e->getMessage());
                             // Some DB Error happened, log it and let the user know
                     }
                 }
             }

            return new ViewModel(array(
                'id' => $id,
                'form' => $form,
                'subStoreDetails' => $subStoreDetails,
                'keyphrase' => $this->keyphrase,
                'subStoreSurrenderGoods' => $this->goodsTransactionService->getSubStoreSurrenderGoodsDetails($id)
                ));
        }else{
            $this->redirect()->toRoute('sub-store-surrender-list');
        }
    }



    //To apply for Goods Surrender

    public function subStoreSurrenderGoodsDetailsAction()
    {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $form = new DeptGoodsSurrenderForm();
            $goodsTransactionModel = new DeptGoodsSurrender();
            $form->bind($goodsTransactionModel);

            $subStoreSurrenderDetails = $this->goodsTransactionService->findSubStoreSurrenderGoodsDetails($id);

            $request = $this->getRequest();
            if($request->isPost()){
                $form->setData($request->getPost());
                if($form->isValid()){

                    try{
                       $this->goodsTransactionService->updateSubStoreGoodsSurrender($goodsTransactionModel, $id);
                       $this->auditTrailService->saveAuditTrail("UPDATE", "sub_store_goods_surrender", "ALL", "SUCCESS");
                       $this->flashMessenger()->addMessage('You have successfully approved sub-store surredered goods');
                        return $this->redirect()->toRoute('sub-store-surrender-list');
                    }
                    catch(\Exception $e){
                        die($e->getMessage());
                        //Some DB Error happened, log it and let the user know  
                    }
                
                }
            }

            return array(
                'id' => $id,
                'form' => $form,
                'subStoreSurrenderDetails' => $subStoreSurrenderDetails,
                'employee_details_id' => $this->employee_details_id,
                );
        }else{
            return $this->redirect()->toRoute('sub-store-surrender-list');
        }
    }


    //For Transfer of Goods

    public function applyDeptGoodsTransferAction()
    {
        $this->loginDetails();

        // get the id of the item sub category
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $goodsTransferDetails = $this->goodsTransactionService->findDeptGoodsDetails($id);

            $form = new DeptGoodsTransferForm($this->serviceLocator);
            $goodsTransactionModel = new GoodsTransfer();
            $form->bind($goodsTransactionModel);  

             // Check if the organisation applied for goods transfer and its still pending or not
            $message = NULL;
            $check_dept_goods_transfer = $this->goodsTransactionService->crossCheckDeptGoodsTransfer('Pending', $id, $this->departments_units_id);
            if($check_dept_goods_transfer){
                $message = "Failure";
                $this->flashMessenger()->addMessage('You have alreday applied for goods transfer for this particular item and its in pending. You can apply after some time!');
                }else{
                    $request = $this->getRequest();
                     if ($request->isPost()) {
                         $form->setData($request->getPost());
                         $data = $this->params()->fromPost();
                         $transfer_quantity = $data['transfer_quantity'];

                         $check_goods_transfer_qty = $this->goodsTransactionService->crossCheckDeptGoodsTransferQty($id, $transfer_quantity);
                         if($check_goods_transfer_qty){
                            $message = 'Failure';
                            $this->flashMessenger()->addMessage("Sorry, you can't apply for goods transfer since transfer quantity of this particular item is greater than the quantity you have. Try it again with less quantity!");
                         }else{
                            if ($form->isValid()) {
                                 try {        
                                    $department_to_id = $this->getRequest()->getPost('department_to_id');
                                    $employee_to_id = $this->getRequest()->getPost('employee_details_to_id');
                                    $this->goodsTransactionService->saveDeptGoodsTransfer($goodsTransactionModel, $department_to_id, $employee_to_id);
                                    $this->notificationService->saveNotification('Apply Department Goods Transfer', $this->employee_details_id, $department_to_id, 'Department Goods Transfer');
                                     $this->auditTrailService->saveAuditTrail("INSERT", "goods_transfer", "ALL", "SUCCESS");

                                    $this->flashMessenger()->addMessage('You have successfully applied for Sub-store goods transfer');
                                     return $this->redirect()->toRoute('dept-goods-transfer-list');
                                 }
                                 catch(\Exception $e) {
                                    $message = 'Failure';
                                    $this->flashMessenger()->addMessage($e->getMessage());
                                    return $this->redirect()->toRoute('dept-goods-transfer-list');
                                         // Some DB Error happened, log it and let the user know
                                 }
                             }
                         }
                     }
                }    

             return array(
                 'form' => $form,
                 //'username' => $this->username,
                 'departments_units_id' => $this->departments_units_id,
                 'employee_details_id' => $this->employee_details_id,
                 'goodsTransferDetails' => $goodsTransferDetails,  
                 'message' => $message,           
             );
        }else{
            return $this->redirect()->toRoute('dept-goods-transfer-list');
        }
    }


    public function deptGoodsTransferFromListAction()
    {
        $this->loginDetails();

        $message = NULL;

        return new ViewModel(array(
            'deptTransferFromList' => $this->goodsTransactionService->listAllDeptTransferFrom($this->departments_units_id),
            'keyphrase' => $this->keyphrase,
            'message' => $message,
            ));
    }

    public function deptGoodsTransferListAction()
    {
        $this->loginDetails();

        $message = NULL;

        return new ViewModel(array(
            'deptTransferToList' => $this->goodsTransactionService->listAllDeptTransferTo($this->departments_units_id),
            'deptTransferFromList' => $this->goodsTransactionService->listAllDeptTransferFromStatus($this->departments_units_id),
            'keyphrase' => $this->keyphrase,
            'message' => $message,
            ));
    }


    //To apply for Goods Surrender

    public function viewDeptGoodsTransferFromDetailsAction()
    {   
        $this->loginDetails();

        // get the id of the item sub category
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $form = new UpdateDeptGoodsTransferForm();
            $goodsTransactionModel = new GoodsTransfer();
            $form->bind($goodsTransactionModel);  

            $transferFromDetails = $this->goodsTransactionService->findDeptGoodsTransferFromDetails($id);

             $request = $this->getRequest();
             if ($request->isPost()) {
                 $form->setData($request->getPost());
                 $data = $this->params()->fromPost();
                 $submission_to = $data['updatedeptgoodstransfer']['employee_details_from_id'];
                 $submission_to_department = $data['updatedeptgoodstransfer']['department_from_id'];
                 if ($form->isValid()) {
                     try {        
                        $this->goodsTransactionService->updateDeptGoodsTransfer($goodsTransactionModel, $id);
                        $this->notificationService->saveNotification('Update Department Goods Transfer', $submission_to, $submission_to_department, 'Department Goods Transfer');
                         $this->auditTrailService->saveAuditTrail("UPDATE", "goods_transfer", "ALL", "SUCCESS");
                        $this->flashMessenger()->addMessage('You have successfully approved transfered goods from other department');
                         return $this->redirect()->toRoute('dept-goods-transfer-from-list');
                     }
                     catch(\Exception $e) {
                             die($e->getMessage());
                             // Some DB Error happened, log it and let the user know
                     }
                 }
             }

             return array(
                 'form' => $form,
                 'username' => $this->username,
                 'departments_units_id' => $this->departments_units_id,
                 'employee_details_id' => $this->employee_details_id,
                 'transferFromDetails' => $transferFromDetails,             
             );
        }else{
            return $this->redirect()->toRoute('dept-goods-transfer-from-list');
        }  
     }


     //To apply for Goods Surrender

    public function deptGoodsTransferFromDetailsAction()
    {   
        $this->loginDetails();

        // get the id of the item sub category
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $form = new DeptGoodsTransferDetailsForm();
            $goodsTransactionModel = new GoodsTransfer();
            $form->bind($goodsTransactionModel);  

            $transferFromDetails = $this->goodsTransactionService->findDeptGoodsTransferFromDetails($id);

             $request = $this->getRequest();
             if ($request->isPost()) {
                 $form->setData($request->getPost());
                 if ($form->isValid()) {
                     try {        
                      //  $this->goodsTransactionService->UpdateDeptGoodsTransfer($goodsTransactionModel, $id);

                        // return $this->redirect()->toRoute('dept-goods-transfer-from-list');
                     }
                     catch(\Exception $e) {
                             die($e->getMessage());
                             // Some DB Error happened, log it and let the user know
                     }
                 }
             }

             return array(
                 'form' => $form,
                 'username' => $this->username,
                 'departments_units_id' => $this->departments_units_id,
                 'employee_details_id' => $this->employee_details_id,
                 'transferFromDetails' => $transferFromDetails,             
             );
        }else{
            $this->redirect()->toRoute('dept-goods-transfer-list');
        }
     }


     //To apply for Goods Surrender

    public function deptGoodsTransferToDetailsAction()
    {   
        $this->loginDetails();

        // get the id of the item sub category
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $form = new DeptGoodsTransferDetailsForm();
            $goodsTransactionModel = new GoodsTransfer();
            $form->bind($goodsTransactionModel);  

            $transferToDetails = $this->goodsTransactionService->findDeptGoodsTransferToDetails($id);

             $request = $this->getRequest();
             if ($request->isPost()) {
                 $form->setData($request->getPost());
                 if ($form->isValid()) {
                     try {        
                      //  $this->goodsTransactionService->UpdateDeptGoodsTransfer($goodsTransactionModel, $id);

                        // return $this->redirect()->toRoute('dept-goods-transfer-from-list');
                     }
                     catch(\Exception $e) {
                             die($e->getMessage());
                             // Some DB Error happened, log it and let the user know
                     }
                 }
             }

             return array(
                 'form' => $form,
                 'username' => $this->username,
                 'departments_units_id' => $this->departments_units_id,
                 'employee_details_id' => $this->employee_details_id,
                 'transferToDetails' => $transferToDetails,             
             );
        }else{
            $this->redirect()->toRoute('dept-goods-transfer-list');
        }
     }
    

    //Approval for Goods transfer

    public function updateGoodsTransferStatusAction()
    {
        $this->loginDetails();
        $updateGoodsTransferDetails = $this->goodsTransactionService->findGoodsTransfer($this->params('id'));

        $form = new GoodsTransferApprovalForm();
        $goodsTransactionModel = new GoodsTransfer();
        $form->bind($goodsTransactionModel);

        $tableName = 'department_units';
        $columnName = 'unit_name';
        $Select = $this->goodsTransactionService->listSelectData($tableName, $columnName);

              

         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
                     $this->goodsTransactionService->saveGoodsTransfer($goodsTransactionModel);

                     return $this->redirect()->toRoute('goods-transfer-approval-list');
                 }
                 catch(\Exception $e) {
                         die($e->getMessage());
                         // Some DB Error happened, log it and let the user know
                 }
             }
         }

         return array(
             'form' => $form,
             'selectData' => $Select,
             'username' => $this->username,
             'updateGoodsTransferDetails' => $updateGoodsTransferDetails,
             //'itemName' => $itemName,
             
         );
    }
    //show Goods Transfered Approval List
    public function goodsTransferApprovalListAction()
    {
        $this->loginDetails();

        return new ViewModel(array(
            'approvallist' => $this->goodsTransactionService->findTransferGoodsApprovalStatus()
            ));
    }
    
    

    //Display list of Approval/Pending/Reject Goods Transfered list
    
    public function viewTransferGoodsAction()
    {
        $this->loginDetails();

        return new ViewModel(array(
            'approvals' => $this->goodsTransactionService->findTransferGoodsApprovalStatus()
            ));
    }

    

    

    //To view or display list of Item with Department Action

    public function viewDeptGoodsListAction()
    {
        $this->loginDetails();

        $message = NULL;

        return new ViewModel(array(
            'deptAllGoods' => $this->goodsTransactionService->findDeptAllGoods($tableName = 'department_goods', $this->departments_units_id),
            'transferedGoodsList' => $this->goodsTransactionService->findDeptAllGoods($tableName = 'goods_transfer', $this->departments_units_id),
            'departments_id' => $this->departments_id,
            'keyphrase' => $this->keyphrase,
            'message' => $message,
            ));
    }


    //To view or display list of Item In Stock Action
    public function deptAllGoodsInStockAction()
    {
        $this->loginDetails();

        $deptGoodsInStock = array();
      //  $donationGoodsInStock = array();

        $form = new DeptSearchForm($this->serviceLocator);

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
                $departmentId = $this->getRequest()->getPost('department');
                //need to get the list of item category, item sub category, item quantity type and item name from the database
                $deptGoodsInStock = $this->goodsTransactionService->listDeptGoodsInStock($departmentId, $this->organisation_id);
            }
        }
       
        return new ViewModel(array(
            'form' => $form,
            'deptGoodsInStock' => $deptGoodsInStock,
            'organisation_id' => $this->organisation_id,
        ));
    }


    //For Transfer of Goods

    public function applyOrgGoodsTransferAction()
    {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $orgGoodsTransfer = $this->goodsTransactionService->findOrgGoodsDetails($id);

            $form = new OrgGoodsTransferForm();
            $goodsTransactionModel = new OrgGoodsTransfer();
            $form->bind($goodsTransactionModel); 

            $organisation = $this->goodsTransactionService->listSelectData($tableName = 'organisation', $columnName = 'organisation_name'); 

            // Check if the organisation applied for goods transfer and its still pending or not
            $message = NULL;
            $check_org_goods_transfer = $this->goodsTransactionService->crossCheckOrgGoodsTransfer('Pending', $id);
            if($check_org_goods_transfer){
                $message = "Failure";
                $this->flashMessenger()->addMessage('You have alreday applied for goods transfer for this particular item and its in pending. You can apply after some time!');
            }else{
                 $request = $this->getRequest();
                 if ($request->isPost()) {
                     $form->setData($request->getPost());
                     $data = $this->params()->fromPost();
                     $transfer_quantity = $data['orggoodstransfer']['transfer_quantity'];
                     $check_transfer_quantity = $this->goodsTransactionService->crossCheckOrgGoodsTransferQty($transfer_quantity, $id);
                     if($check_transfer_quantity){
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage("Sorry, you can't apply for goods transfer since transfer quantity is more than item in stock. Please try with less quantity!");
                     }else{
                         if ($form->isValid()) {
                             try {        
                               $this->goodsTransactionService->saveOrgGoodsTransfer($goodsTransactionModel);
                               $this->notificationService->saveNotification('Apply Organisation Goods Transfer', NULL, NULL, 'Organisation Goods Transfer');
                                 $this->auditTrailService->saveAuditTrail("INSERT", "organisation_goods_transfer", "ALL", "SUCCESS");
                                 
                               $this->flashMessenger()->addMessage('You have successfully applied for goods transfer');
                               return $this->redirect()->toRoute('view-goods-in-stock');
                             }
                             catch(\Exception $e) {
                                $message = 'Failure';
                                $this->flashMessenger()->addMessage($e->getMessage());
                                return $this->redirect()->toRoute('view-goods-in-stock');
                            
                                     // Some DB Error happened, log it and let the user know
                             }
                         }
                     }
                 }
            }            

             return array(
                 'form' => $form,
                 'employee_details_id' => $this->employee_details_id,
                 'organisation_id' => $this->organisation_id,
                 'orgGoodsTransfer' => $orgGoodsTransfer, 
                 'organisation' => $organisation,  
                 'message' => $message,         
             );
        }else{
            return $this->redirect()->toRoute('view-goods-in-stock');
        }
    }


    //For Transfer of Goods

    public function disposeGoodsAction()
    {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $form = new DisposeGoodsForm();
            $goodsTransactionModel = new DisposeGoods();
            $form->bind($goodsTransactionModel); 

             $disposeGoods = $this->goodsTransactionService->findOrgGoodsDetails($id);          

             $request = $this->getRequest();
             if ($request->isPost()) {
                 $form->setData($request->getPost());
                 if ($form->isValid()) {
                     try {        
                        $this->goodsTransactionService->updateDisposeGoods($goodsTransactionModel, $id);
                        $this->auditTrailService->saveAuditTrail("INSERT", "goods_received", "item_quantity_disposed", "SUCCESS");

                        $this->flashMessenger()->addMessage('You have successfully disposed goods');
                        return $this->redirect()->toRoute('view-goods-in-stock');
                     }
                     catch(\Exception $e) {
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                        return $this->redirect()->toRoute('view-goods-in-stock');
                             // Some DB Error happened, log it and let the user know
                     }
                 }
             }

             return array(
                 'form' => $form,
                 'employee_details_id' => $this->employee_details_id,
                 'organisation_id' => $this->organisation_id,
                 'disposeGoods' => $disposeGoods,          
             );
        } else{
            return $this->redirect()->toRoute('view-goods-in-stock');
        }
    }


    // Function to display the list of goods transfer from organisation
    public function orgGoodsTransferApprovalListAction()
    {
        $this->loginDetails();

        $message = NULL;

        return new ViewModel(array(
            'orgTransferApprovalList' => $this->goodsTransactionService->listAllOrgGoodsTransferApproval($this->organisation_id),
            'keyphrase' => $this->keyphrase,
            'message' => $message,
            ));
    }


    //To apply for Goods Surrender

    public function orgGoodsTransferDetailsAction()
    { 
        $this->loginDetails();

        // get the id of the item sub category
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $form = new UpdateOrgGoodsTransferForm($this->serviceLocator);
            $goodsTransactionModel = new OrgGoodsTransfer();
            $form->bind($goodsTransactionModel);  

            $orgTransferDetails = $this->goodsTransactionService->findOrgGoodsTransferDetails($id);

            $message = NULL;

             $request = $this->getRequest();
             if ($request->isPost()) {
                 $form->setData($request->getPost());
                 $data = $this->params()->fromPost();
                 $itemTransferedId = $data['updateorggoodstransfer']['item_received_transfered_id'];
                 $itemReceivedType = $data['updateorggoodstransfer']['item_received_type'];
                 $itemTransferedQty = $data['updateorggoodstransfer']['transfer_quantity'];
                 $check_transfer_quantity = $this->goodsTransactionService->crossCheckOrgGoodsTransferedQty($id, $itemTransferedQty);
                 if($check_transfer_quantity){
                        $message = 'Failure';
                       $this->flashMessenger()->addMessage("Sorry, you can't approved more quantity than the actual item transfered quantity. Please try with same to transfered quantity or less quantity!");
                    }
                    else {
                        if ($form->isValid()) {
                        $item_category_id = $this->getRequest()->getPost('item_category_id');
                        $item_sub_category_id = $this->getRequest()->getPost('item_sub_category_id');
                        $item_name_id = $this->getRequest()->getPost('item_name_id');
                        
                         try {        
                            $this->goodsTransactionService->updateOrgGoodsTransfer($goodsTransactionModel, $id, $item_category_id, $item_sub_category_id, $item_name_id, $itemTransferedId, $itemReceivedType, $this->organisation_id);
                            $this->notificationService->saveNotification('Update Organisation Goods Transfer', NULL, NULL, 'Organisation Goods Transfer');
                             $this->auditTrailService->saveAuditTrail("UPDATE", "organisation_goods_transfer", "ALL", "SUCCESS");
                            $this->flashMessenger()->addMessage('You have successfully approved org. transfered goods');
                             return $this->redirect()->toRoute('org-goods-transfer-approval-list');
                         }
                         catch(\Exception $e) {
                                 die($e->getMessage());
                                 // Some DB Error happened, log it and let the user know
                         }
                     }
                }
             }

             return array(
                'id' => $id,
                 'form' => $form,
                 'item_received_transfered_id' => $id,
                 'organisation_id' => $this->organisation_id,
                 'employee_details_id' => $this->employee_details_id,
                 'orgTransferDetails' => $orgTransferDetails,  
                 'message' => $message,           
             );
        }else{
            return $this->redirect()->toRoute('org-goods-transfer-approval-list');
        } 
     }


     public function orgGoodsTransferListAction()
    {
        $this->loginDetails();

        return new ViewModel(array(
            'orgTransferToList' => $this->goodsTransactionService->listAllOrgGoodsTransferTo($this->organisation_id),
            'orgTransferFromList' => $this->goodsTransactionService->listAllOrgGoodsTransferFrom($this->organisation_id),
            'keyphrase' => $this->keyphrase,
            ));
    }


     //To apply for Goods Surrender

    public function orgGoodsTransferToDetailsAction()
    {   
        $this->loginDetails();

        // get the id of the item sub category
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){

            $transferToDetails = $this->goodsTransactionService->findOrgGoodsTransferToDetails($id);

             return array(
                'id' => $id,
                 'username' => $this->username,
                 'organisation_id' => $this->organisation_id,
                 'employee_details_id' => $this->employee_details_id,
                 'transferToDetails' => $transferToDetails,             
             );
        }else{
            $this->redirect()->toRoute('org-goods-transfer-list');
        } 
     }


     //To apply for Goods Surrender

    public function orgGoodsTransferFromDetailsAction()
    {  
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){ 

            $transferFromDetails = $this->goodsTransactionService->findOrgGoodsTransferFromDetails($id);

             return array(
                 'id' => $id,
                 'username' => $this->username,
                 'organisation_id' => $this->organisation_id,
                 'employee_details_id' => $this->employee_details_id,
                 'transferFromDetails' => $transferFromDetails,             
             );
        }else{
            return $this->redirect()->toRoute('org-goods-transfer-list');
        }
     }


     public function rejectOrgFromGoodsTransferAction()
     {
        $this->loginDetails();
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            try {
             $this->goodsTransactionService->rejectOrgFromGoodsTransfer($status='Rejected', $previousStatus=NULL, $id, $this->employee_details_id);
             $this->auditTrailService->saveAuditTrail("UPDATE", "Organisation Goods Transfer", "ALL", "SUCCESS");

             $this->flashMessenger()->addMessage('You have successfully rejected all quantity of the goods transfer');
             return $this->redirect()->toRoute('org-goods-transfer-approval-list');
         }
         catch(\Exception $e) {
                $message = 'Failure';
                $this->flashMessenger()->addMessage($e->getMessage());
                return $this->redirect()->toRoute('org-goods-transfer-approval-list');
                 // Some DB Error happened, log it and let the user know
         }
         
        return array();
        }
        else{
            $this->redirect()->toRoute('org-goods-transfer-approval-list');
        }
     }


    //the following function is to extract the data from the form 
    // and return clean data to be inserted into database
    public function extractFormData($data)
    {
        $evaluationData = array();
        
        foreach($data as $key=>$value)
        {
            $evaluationData[$value]= $this->getRequest()->getPost('dept_quantity'.$value);
        }
        return $evaluationData;
    }


    //the following function is to extract the data from the form 
    // and return clean data to be inserted into database
    public function extractFormData1($data)
    {
        $evaluationData = array();
        
        foreach($data as $key=>$value)
        {
            $evaluationData[$value]= $this->getRequest()->getPost('emp_quantity'.$value);
        }
        return $evaluationData;
    }


    public function extractFormData2($data)
    {
        $evaluationData = array();
        
        foreach($data as $key=>$value)
        {
            $evaluationData[$value]= $this->getRequest()->getPost('goods_code'.$value);
        }
        return $evaluationData;
    }


     function my_encrypt($data, $key) 
     {
        // Remove the base64 encoding from our key
        $encryption_key = base64_decode($key);
        // Generate an initialization vector
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('BF-CFB'));
        // Encrypt the data using AES 256 encryption in CBC mode using our encryption key and initialization vector.
        $encrypted = openssl_encrypt($data, 'BF-CFB', $encryption_key, 0, $iv);
        // The $iv is just as important as the key for decrypting, so save it with our encrypted data using a unique separator (::)
        return bin2hex(base64_encode($encrypted . '::' . $iv));
    }



    public function my_decrypt($data, $key) 
    {
        // Remove the base64 encoding from our key
        $encryption_key = base64_decode($key);
        
        $len = strlen($data);
        if ($len % 2) {
            return "ERROR";
        } else {
            // To decrypt, split the encrypted data from our IV - our unique separator used was "::"
            list($encrypted_data, $iv) = explode('::', base64_decode(hex2bin($data)), 2);
            return openssl_decrypt($encrypted_data, 'BF-CFB', $encryption_key, 0, $iv);
        }
    }
}
             
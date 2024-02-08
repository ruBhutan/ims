<?php

namespace GoodsRequisition\Controller;

use Zend\ServiceManager\ServiceLocatorInterface;
use GoodsRequisition\Service\GoodsRequisitionServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use GoodsRequisition\Model\GoodsRequisition;
use GoodsRequisition\Model\GoodsRequisitionApproval;
use GoodsRequisition\Model\GoodsRequisitionForwardApproval;
use GoodsRequisition\Form\GoodsRequisitionForm;
use GoodsRequisition\Form\SubmitGoodsRequisitionForm;
use GoodsRequisition\Form\GoodsRequisitionApprovalForm;
use GoodsRequisition\Form\GoodsRequisitionForwardApprovalForm;
use GoodsRequisition\Form\GoodsRequisitionForwardUpdateForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

//RBACL
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManage;

//AJAX
use Zend\View\Model\JsonModel;
use Zend\Form\Element\Select;


class GoodsRequisitionController extends AbstractActionController
{
   
   	protected $goodsRequisitionService;
    protected $notificationService;
    protected $auditTrailService;
    protected $emailService;
	protected $serviceLocator;
    protected $username;
    protected $userrole;
    protected $usertype;
    protected $userDetails;
     protected $userImage;
    protected $employee_details_id;
    protected $organisation_id;
	protected $userregion;

    protected $keyphrase = "RUB_IMS";
	
	public function __construct(GoodsRequisitionServiceInterface $goodsRequisitionService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->goodsRequisitionService = $goodsRequisitionService;
        $this->notificationService = $notificationService;
        $this->auditTrailService = $auditTrailService;
		$this->serviceLocator = $serviceLocator;
        $this->emailService = $serviceLocator->get('Application\Service\EmailService');

        /*
         * To retrieve the user name from the session
        */
        $authPlugin = $this->serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
        // Use service locator to get the authPlugin
        $this->username = $authPlugin['username'];
        $this->usertype = $authPlugin['user_type_id'];
		$this->userrole = $authPlugin['role'];
		$this->userregion = $authPlugin['region'];		

        //get the employee details id
        $empData = $this->goodsRequisitionService->getUserDetailsId($this->username);
        foreach($empData as $emp){
            $this->employee_details_id = $emp['id'];
            $this->departments_units_id = $emp['departments_units_id'];
            $this->departments_id = $emp['departments_id'];
        }

        //get the organisation id
        $organisationID = $this->goodsRequisitionService->getOrganisationId($this->username);
        foreach($organisationID as $organisation){
            $this->organisation_id = $organisation['organisation_id'];
        }

        $this->userDetails = $this->goodsRequisitionService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->goodsRequisitionService->getUserImage($this->username, $this->usertype);
	}

    public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }

	
	//To add Item Category Action
	public function applyGoodsRequisitionAction()
    {
        $this->loginDetails();

        $form = new GoodsRequisitionForm($this->serviceLocator);
        $goodsRequisitionModel = new GoodsRequisition();
        $form->bind($goodsRequisitionModel);

        $addGoodsRequisition = array();
        $submitForm = new SubmitGoodsRequisitionForm();

        $indGoodsRequisition = $this->goodsRequisitionService->listAllGoodsRequisition($tableName = 'goods_requisition_details', $status = 'Not Submitted', $this->employee_details_id);

        $message = NULL;

         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                //getting data from ajax. so need to extract them and pass as variables
                $item_sub_category_id = $this->getRequest()->getPost('item_sub_category_id');
                $item_name_id = $this->getRequest()->getPost('item_name_id');
                 try {
                     $this->goodsRequisitionService->saveRequisitionDetails($goodsRequisitionModel, $item_sub_category_id, $item_name_id);
                     $this->notificationService->saveNotification('Individual Goods Requisition', $this->employee_details_id, 'NULL', 'Goods Requisition');
                     $this->auditTrailService->saveAuditTrail("INSERT", "goods_requisition_details", "ALL", "SUCCESS");

                     $this->flashMessenger()->addMessage('Item was successfully added to apply for requisition');
                     return $this->redirect()->toRoute('apply-goods-requisition');
                 }
                 catch(\Exception $e) {
                    $message = 'Failure';
                    $this->flashMessenger()->addMessage($e->getMessage());
                 }
             }
         }

           return array(
             'form' => $form,
             'employee_details_id' => $this->employee_details_id,
             'submitForm' => $submitForm,
             'indGoodsRequisition' => $indGoodsRequisition,
             'keyphrase' => $this->keyphrase,
             'message' => $message,
             //'userrole' => $this->userrole,
             //'userregion' => $this->userregion,
         );
    }


    /**
    * To edit Goods Requisition
    */
	public function editGoodsRequisitionAction()
	{
        $this->loginDetails();
        // get the id of the item sub category
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $goodsRequisitionDetails = $this->goodsRequisitionService->findGoodsRequisition($id);

            $form = new GoodsRequisitionForm($this->serviceLocator);
            $goodsRequisitionModel = new GoodsRequisition();
            $form->bind($goodsRequisitionModel);
            
            $request = $this->getRequest();
             if ($request->isPost()) {
                 $form->setData($request->getPost());
                 if ($form->isValid()) {
                    //getting data from ajax. so need to extract them and pass as variables
                    $item_sub_category_id = $this->getRequest()->getPost('item_sub_category_id');
                    $item_name_id = $this->getRequest()->getPost('item_name_id');
                     try {
                         $this->goodsRequisitionService->saveRequisitionDetails($goodsRequisitionModel, $item_sub_category_id, $item_name_id);
                         $this->auditTrailService->saveAuditTrail("EDIT", "goods_requisition_details", "ALL", "SUCCESS");

                         $this->flashMessenger()->addMessage('Item was successfully edited to apply for requisition');
                         return $this->redirect()->toRoute('apply-goods-requisition');
                     }
                     catch(\Exception $e) {
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                        return $this->redirect()->toRoute('apply-goods-requisition');
                             // Some DB Error happened, log it and let the user know
                     }
                 }
             }

               return array(
                 'form' => $form,
                 'employee_details_id' => $this->employee_details_id,
                 'goodsRequisitionDetails' => $goodsRequisitionDetails,
             );
        }else{
            return $this->redirect()->toRoute('apply-goods-requisition');
        }
	}

    /*
    * The action is for update the Dept to Individual Item Issue
    */
    
    public function updateIndGoodsRequisitionAction()
    {
        $this->loginDetails();

        //Value 1 is change of status from "Not Issue" to "Issue to Individual"
        //need to take care of organisation as well
        $message = NULL;
        
        $value = (int) $this->params()->fromRoute('id', 0);
        if($value == 1){
            $status = 'Pending';
            $previousStatus = 'Not Submitted';
        }

         try {
             $this->goodsRequisitionService->updateIndGoodsRequisition($status, $previousStatus, $this->employee_details_id, $id = NULL);
              $this->sendGoodsRequisitionEmail($this->employee_details_id, $this->departments_id, $this->departments_units_id, $this->userrole);
             $this->flashMessenger()->addMessage('You have successfully applied for requisition');
             return $this->redirect()->toRoute('view-requisition');
         }
         catch(\Exception $e) {
            $message = 'Failure';
            $this->flashMessenger()->addMessage($e->getMessage());
            return $this->redirect()->toRoute('view-requisition');
                 // Some DB Error happened, log it and let the user know
         }

        return array(
            'message' => $message,
        );
    }


     //Function to send goods requisition email to the particular applicant supervisor
    public function sendGoodsRequisitionEmail($employee_details_id, $departments_id, $departments_units_id, $userrole)
    {
        $this->loginDetails();

        $supervisor_email = $this->goodsRequisitionService->getSupervisorEmailId($userrole, $departments_units_id);

        $applicant_name = NULL;
        $applicant = $this->goodsRequisitionService->getRequisitionApplicant($employee_details_id);
        foreach($applicant as $temp){
            $applicant_name = $temp['first_name'].' '.$temp['middle_name'].' '.$temp['last_name'];
        }

        foreach($supervisor_email as $email){
            $toEmail = $email;
            $messageTitle = "New Goods Requisition";
            //$messageBody = "<h2>".$applicant_name."</h2><b>Have applied for leave on ".date('Y-m-d')."</b><br>.<b>For Details: Please click below link</b> <br><u>http://rub-ims.rub.edu.bt/public/empleaveapproval/</u>";
            $messageBody = "Dear Sir/Madam,<br><h3>".$applicant_name." have applied for goods requisition on ".date('Y-m-d').".</h3><br><b>Please click the link below for necessary action.</b><br><u>http://rub-ims.rub.edu.bt/requisition-approval-list</u><p>This is an auto-generated email and Please do not reply because this mail id is not monitored</p>";

            $this->emailService->sendMailer($toEmail, $messageTitle, $messageBody);
        }   
    }
	
	
	//To delete Goods Requisition Action

    public function deleteGoodsRequisitionAction()
    {
        $this->loginDetails();
        // get the id of the item sub category
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            try{
                $id = $this->goodsRequisitionService->findGoodsRequisition($id);
            }
            catch(\InvalidArgumentException $e){
                return $this->redirect()->toRoute('apply-goods-requisition');
            }

            $request = $this->getRequest();

            if($request->isPost()){
                $del = $request->getPost('delete_confirmation', 'no');

                if($del == 'yes'){
                    $this->goodsRequisitionService->deleteGoodsRequisition($id);
                }
                $this->auditTrailService->saveAuditTrail("DELETE", "goods_requisition_details", "ALL", "SUCCESS");

                $this->flashMessenger()->addMessage('Item was successfully deleted from requisition list');
                return $this->redirect()->toRoute('apply-goods-requisition');
            }

            return array(
                'id' => $id,
                'employee_details_id' => $this->employee_details_id,
                );
        }else{
            return $this->redirect()->toRoute('apply-goods-requisition');
        }
    }
	
	
	public function viewRequisitionAction()
    {
        $this->loginDetails();

        $message = NULL;

        return new ViewModel(array(
            'approvedRequisition' => $this->goodsRequisitionService->listIndividualRequisition($status='Approved', $this->employee_details_id),
            'rejectedRequisition' => $this->goodsRequisitionService->listIndividualRequisition($status='Rejected', $this->employee_details_id),
            'pendingRequisition' => $this->goodsRequisitionService->listIndividualRequisition($status='Pending', $this->employee_details_id),
            'keyphrase' => $this->keyphrase,
            'message' => $message,
            ));
    }  
    
     public function requisitionApprovalListAction()
    {
        $this->loginDetails();

        $message = NULL;

        return new ViewModel(array(
            'requisitionApprovalList' => $this->goodsRequisitionService->listAllRequisitionApproval($userrole = $this->userrole, $this->organisation_id),
            'keyphrase' => $this->keyphrase,
            'message' => $message,
            ));
    }
    public function allGoodsRequisitionListAction()
    {
        $this->loginDetails();

        return new ViewModel(array(
            'approvedRequisitionList' => $this->goodsRequisitionService->listAllRequisitions($status='Approved', $userrole = $this->userrole, $this->organisation_id),
            'rejectedRequisitionList' => $this->goodsRequisitionService->listAllRequisitions($status='Rejected', $userrole = $this->userrole, $this->organisation_id),
	    'pendingRequisitionList' => $this->goodsRequisitionService->listAllRequisitions($status='Pending', $userrole = $this->userrole, $this->organisation_id),
	    'depreciationval'        => $this->goodsRequisitionService->calculateDepreciation($status='Approved', $this->userrole, $this->organisation_id),
            'keyphrase' => $this->keyphrase,
            ));
    }
    // This function is opnly for Store - reponsible for vieweing only

    public function requisitionGoodsIssueListAction ()
    {
//	    $this->loginDetails();
/*
	    return new ViewModel(array(
            'approvedRequisitionList' => $this->goodsRequisitionService->listAllRequisitionsOnly($status='Approved', $this->userrole, $this->organisation_id),
            'rejectedRequisitionList' => $this->goodsRequisitionService->listAllRequisitionsOnly($status='Rejected',  $this->userrole, $this->organisation_id),
            'pendingRequisitionList' => $this->goodsRequisitionService->listAllRequisitionsOnly($status='Pending', $this->userrole, $this->organisation_id),
            'keyphrase' => $this->keyphrase,
	    ));*/
//	    $test = array (1,1,1,1);

//	    var_dump($test);
    }
    public function listAllRequisitionAction()
    {

	    $this->loginDetails();

            return new ViewModel(array(
            'approvedRequisitionList' => $this->goodsRequisitionService->listAllRequisitionsOnly($status='Approved', $this->userrole, $this->organisation_id),
            'rejectedRequisitionList' => $this->goodsRequisitionService->listAllRequisitionsOnly($status='Rejected',  $this->userrole, $this->organisation_id),
            'pendingRequisitionList' => $this->goodsRequisitionService->listAllRequisitionsOnly($status='Pending', $this->userrole, $this->organisation_id),
            'keyphrase' => $this->keyphrase,
            ));

    }
    public function requisitionApprovedListAction()
    {
        $this->loginDetails();

        return new ViewModel(array(
            'requisitionApprovedList' => $this->goodsRequisitionService->listAllRequisitionApproved()
            ));
    }


      //To view or display list of Item that particular staff applied for requisition Action

    public function empGoodsRequisitionListDetailsAction()
    {
        $this->loginDetails(); 
        // get the id of the item sub category
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $form = new GoodsRequisitionApprovalForm();
            $goodsRequisitionModel = new GoodsRequisition();
            $form->bind($goodsRequisitionModel);
            
            $staffGoodsRequisition = $this->goodsRequisitionService->getStaffGoodsRequisitionDetails($id);
            $staffGoodsRequisitionList = $this->goodsRequisitionService->getStaffGoodsRequisitionListDetails($id);


            $message = NULL;
            
            $request = $this->getRequest();
             if ($request->isPost()) {
                 $form->setData($request->getPost());
                 if ($form->isValid()) {
                     try {
                      //   $this->goodsRequisitionService->saveResponsibility($goodsRequisitionModel);
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
                'staffGoodsRequisition' => $staffGoodsRequisition,
                'staffGoodsRequisitionList' => $staffGoodsRequisitionList,
                'keyphrase' => $this->keyphrase,
                'message' => $message,
                ));
            } else{
                $this->redirect()->toRoute('requisition-approval-list');
            }
    }


    public function approveGoodsRequisitionAction()
    {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            try {
             $this->goodsRequisitionService->approveGoodsRequisition($status='Approved', $previousStatus = NULL, $id, $this->employee_details_id);
             $this->flashMessenger()->addMessage('Requisition was successfully approved');
             $this->auditTrailService->saveAuditTrail("UPDATE", "goods_requisition_details", "ALL", "SUCCESS");

             $this->flashMessenger()->addMessage('You have successfully approved goods requisition');
             return $this->redirect()->toRoute('requisition-approval-list');
         }
         catch(\Exception $e) {
            $message = 'Failure';
            $this->flashMessenger()->addMessage($e->getMessage());
            return $this->redirect()->toRoute('requisition-approval-list');
                 // Some DB Error happened, log it and let the user know
         }
         
        return array();

        }else{
             $this->redirect()->toRoute('requisition-approval-list');
        }
    }

    
    public function requisitionApprovalAction()
    { 
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $form = new GoodsRequisitionApprovalForm();
            $goodsRequisitionModel = new GoodsRequisitionApproval();
            $form->bind($goodsRequisitionModel);

            $approvals = $this->goodsRequisitionService->findRequisitionApproval($id);

            $request = $this->getRequest();
            if($request->isPost()){
                $form->setData($request->getPost());
                if($form->isValid()){
                    try{
                        $this->goodsRequisitionService->saveRequisitionApproval($goodsRequisitionModel);
                        $this->auditTrailService->saveAuditTrail("UPDATE", "goods_requisition_details", "ALL", "SUCCESS");

                        $this->flashMessenger()->addMessage('You have successfully approved/ rejected staff requisition');
                        return $this->redirect()->toRoute('requisition-approval-list');
                    }
                    catch(\Exception $e){
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                        return $this->redirect()->toRoute('requisition-approval-list');
                        //Some DB Error happened, log it and let the user know  
                    }
                
                }
            }

            return array(
                'form' => $form,
                'approvals' => $approvals,
                'employee_details_id' => $this->employee_details_id,
                );
        }else{
            return $this->redirect()->toRoute('requisition-approval-list');
        }     
   }

    public function requisitionForwardApprovalListAction()
    {
        $this->loginDetails();       

        return new ViewModel(array(
            'requisitionForwardApprovalList' => $this->goodsRequisitionService->listAllRequisitionForwardApproval($this->organisation_id)
            ));
    }


    //To view or display list of Item that particular staff applied for requisition and forwarded Action

    public function empRequisitionForwardListDetailsAction()
    {
        $this->loginDetails();

        $id = (int) $this->params()->fromRoute('id', 0);
        
        $form = new GoodsRequisitionForwardApprovalForm();
        $goodsRequisitionModel = new GoodsRequisitionForwardApproval();
        $form->bind($goodsRequisitionModel);
        
        $staffRequisitionForward = $this->goodsRequisitionService->getStaffRequisitionForwardDetails($id);
       // $responsibilityCategory = $this->responsibilityService->listSelectData($tableName = 'responsibility_category', $columnName = 'responsibility_name');
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
                  //   $this->goodsRequisitionService->saveResponsibility($goodsRequisitionModel);
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
            'staffRequisitionForward' => $staffRequisitionForward,
            'staffRequisitionForwardList' => $this->goodsRequisitionService->getStaffRequisitionForwardDetails($id)

            //'empAllGoods' => $this->goodsTransactionService->listEmpAllGoods()
            ));
    }


    public function requisitionForwardApprovalAction()
    {
        $this->loginDetails();

        $id = (int) $this->params()->fromRoute('id', 0);
                
        $form = new GoodsRequisitionForwardApprovalForm();
        $goodsRequisitionModel = new GoodsRequisitionForwardApproval();
        $form->bind($goodsRequisitionModel);

        $forwardApprovals = $this->goodsRequisitionService->findRequisitionForwardApproval($id);

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
                try{
                    $this->goodsRequisitionService->saveRequisitionForwardApproval($goodsRequisitionModel);

                    return $this->redirect()->toRoute('requisition-forward-approval-list');
                }
                catch(\Exception $e){
                    die($e->getMessage());
                    //Some DB Error happened, log it and let the user know  
                }
            
            }
        }

        return array(
            'form' => $form,
            'forwardApprovals' => $forwardApprovals,
            'employee_details_id' => $this->employee_details_id,
            );
   }

   public function approvedRequisitionForwardedListAction()
    {
        $this->loginDetails();

        return new ViewModel(array(
            'approvedRequisitionForwarded' => $this->goodsRequisitionService->listAllApprovedRequisitionForwarded($this->organisation_id)
            ));
    }

    public function updateApprovedReqForwardedAction()
    {
        $this->loginDetails();   
        $id = (int) $this->params()->fromRoute('id', 0);
                
        $form = new GoodsRequisitionForwardUpdateForm();
        $goodsRequisitionModel = new GoodsRequisitionForwardApproval();
        $form->bind($goodsRequisitionModel);

        $updateForwardApproved = $this->goodsRequisitionService->findApprovedRequisitionForwarded($id);

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
                try{
                    $this->goodsRequisitionService->updateApprovedForwardedRequisition($goodsRequisitionModel);

                    return $this->redirect()->toRoute('approved-requisition-forwarded-list');
                }
                catch(\Exception $e){
                    die($e->getMessage());
                    //Some DB Error happened, log it and let the user know  
                }
            
            }
        }

        return array(
            'form' => $form,
            'updateForwardApproved' => $updateForwardApproved,
            'employee_details_id' => $this->employee_details_id,
            );
   }

    //To view or display the details of requisition pending by particular staff

    public function indvReqPendingDetailsAction()
    {
        $this->loginDetails();   
        // get the id of the item sub category
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){        
            $form = new GoodsRequisitionApprovalForm();
            $goodsRequisitionModel = new GoodsRequisition();
            $form->bind($goodsRequisitionModel);
            
            $pendingDetails = $this->goodsRequisitionService->getIndvReqPendingDetails($id);
            
            $request = $this->getRequest();
             if ($request->isPost()) {
                 $form->setData($request->getPost());
                 if ($form->isValid()) {
                     try {
                      //   $this->goodsRequisitionService->saveResponsibility($goodsRequisitionModel);
                       //  $this->redirect()->toRoute('responsibilitycategory');
                     }
                     catch(\Exception $e) {
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                        return $this->redirect()->toRoute('view-requisition');
                             // Some DB Error happened, log it and let the user know
                     }
                 }
             }

            return new ViewModel(array(
                'id' => $id,
                'form' => $form,
                'pendingDetails' => $pendingDetails

                //'empAllGoods' => $this->goodsTransactionService->listEmpAllGoods()
                ));
            }else{
                return $this->redirect()->toRoute('view-requisition');
            }
    }

    //To view or display the details of requisition approved by particular staff

    public function indvReqApprovedDetailsAction()
    {
        $this->loginDetails();
        // get the id of the item sub category
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){        
            $form = new GoodsRequisitionApprovalForm();
            $goodsRequisitionModel = new GoodsRequisition();
            $form->bind($goodsRequisitionModel);
            
            $approvedDetails = $this->goodsRequisitionService->getIndvReqApprovedDetails($id);
           // $responsibilityCategory = $this->responsibilityService->listSelectData($tableName = 'responsibility_category', $columnName = 'responsibility_name');
            
            $request = $this->getRequest();
             if ($request->isPost()) {
                 $form->setData($request->getPost());
                 if ($form->isValid()) {
                     try {
                      //   $this->goodsRequisitionService->saveResponsibility($goodsRequisitionModel);
                       //  $this->redirect()->toRoute('responsibilitycategory');
                     }
                     catch(\Exception $e) {
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                        return $this->redirect()->toRoute('view-requisition');
                             // Some DB Error happened, log it and let the user know
                     }
                 }
             }

            return new ViewModel(array(
                'id' => $id,
                'form' => $form,
                'approvedDetails' => $approvedDetails

                //'empAllGoods' => $this->goodsTransactionService->listEmpAllGoods()
                ));
            }else{
                return $this->redirect()->toRoute('view-requisition');
            }   
    }

    //To view or display the details of requisition rejected by particular staff

    public function indvReqRejectedDetailsAction()
    { 
        $this->loginDetails();
        // get the id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){        
            $form = new GoodsRequisitionApprovalForm();
            $goodsRequisitionModel = new GoodsRequisition();
            $form->bind($goodsRequisitionModel);
            
            $rejectedDetails = $this->goodsRequisitionService->getIndvReqRejectedDetails($id);
           // $responsibilityCategory = $this->responsibilityService->listSelectData($tableName = 'responsibility_category', $columnName = 'responsibility_name');
            
            $request = $this->getRequest();
             if ($request->isPost()) {
                 $form->setData($request->getPost());
                 if ($form->isValid()) {
                     try {
                      //   $this->goodsRequisitionService->saveResponsibility($goodsRequisitionModel);
                       //  $this->redirect()->toRoute('responsibilitycategory');
                     }
                     catch(\Exception $e) {
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                        return $this->redirect()->toRoute('view-requisition');
                             // Some DB Error happened, log it and let the user know
                     }
                 }
             }

            return new ViewModel(array(
                'id' => $id,
                'form' => $form,
                'rejectedDetails' => $rejectedDetails

                //'empAllGoods' => $this->goodsTransactionService->listEmpAllGoods()
                ));
        }else{
            return $this->redirect()->toRoute('view-requisition');
        }
    }

    //To view or display the details of requisition forwarded by particular staff

    public function indvReqForwardedDetailsAction()
    {
        $this->loginDetails();   
        $id = (int) $this->params()->fromRoute('id', 0);
        
        $form = new GoodsRequisitionForwardUpdateForm();
        $goodsRequisitionModel = new GoodsRequisition();
        $form->bind($goodsRequisitionModel);
        
        $forwardedDetails = $this->goodsRequisitionService->getIndvReqForwardedDetails($id);
       // $responsibilityCategory = $this->responsibilityService->listSelectData($tableName = 'responsibility_category', $columnName = 'responsibility_name');
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
                  //   $this->goodsRequisitionService->saveResponsibility($goodsRequisitionModel);
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
            'forwardedDetails' => $forwardedDetails

            //'empAllGoods' => $this->goodsTransactionService->listEmpAllGoods()
            ));
    }


    //To view or display the details of requisition pending by store or procurement

    public function requisitionPendingDetailsAction()
    {
        $this->loginDetails();   
        // get the id of the item sub category
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $form = new GoodsRequisitionForwardUpdateForm();
            $goodsRequisitionModel = new GoodsRequisition();
            $form->bind($goodsRequisitionModel);
            
            $reqPendingDetails = $this->goodsRequisitionService->getRequisitionPendingDetails($id);
           // $responsibilityCategory = $this->responsibilityService->listSelectData($tableName = 'responsibility_category', $columnName = 'responsibility_name');
            
            $request = $this->getRequest();
             if ($request->isPost()) {
                 $form->setData($request->getPost());
                 if ($form->isValid()) {
                     try {
                      //   $this->goodsRequisitionService->saveResponsibility($goodsRequisitionModel);
                       //  $this->redirect()->toRoute('responsibilitycategory');
                     }
                     catch(\Exception $e) {
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                        return $this->redirect()->toRoute('all-goods-requisition-list');
                             // Some DB Error happened, log it and let the user know
                     }
                 }
             }

            return new ViewModel(array(
                'id' => $id,
                'form' => $form,
                'reqPendingDetails' => $reqPendingDetails

                //'empAllGoods' => $this->goodsTransactionService->listEmpAllGoods()
                ));
        }else{
             return $this->redirect()->toRoute('all-goods-requisition-list');
        }
    }


    //To view or display the details of requisition approved by store or procurement

    public function requisitionApprovedDetailsAction()
    {
        $this->loginDetails();   
        // get the id of the item sub category
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $form = new GoodsRequisitionForwardUpdateForm();
            $goodsRequisitionModel = new GoodsRequisition();
            $form->bind($goodsRequisitionModel);
            
            $reqApprovedDetails = $this->goodsRequisitionService->getRequisitionApprovedDetails($id);
           // $responsibilityCategory = $this->responsibilityService->listSelectData($tableName = 'responsibility_category', $columnName = 'responsibility_name');
            
            $request = $this->getRequest();
             if ($request->isPost()) {
                 $form->setData($request->getPost());
                 if ($form->isValid()) {
                     try {
                      //   $this->goodsRequisitionService->saveResponsibility($goodsRequisitionModel);
                       //  $this->redirect()->toRoute('responsibilitycategory');
                     }
                     catch(\Exception $e) {
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                        return $this->redirect()->toRoute('all-goods-requisition-list');
                             // Some DB Error happened, log it and let the user know
                     }
                 }
             }

            return new ViewModel(array(
                'id' => $id,
                'form' => $form,
                'reqApprovedDetails' => $reqApprovedDetails

                //'empAllGoods' => $this->goodsTransactionService->listEmpAllGoods()
                ));
            }else{
                return $this->redirect()->toRoute('all-goods-requisition-list');
            }
    }

    //To view or display the details of requisition rejected by store or procurement

    public function requisitionRejectedDetailsAction()
    {
        $this->loginDetails();   
        // get the id of the item sub category
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $form = new GoodsRequisitionForwardUpdateForm();
            $goodsRequisitionModel = new GoodsRequisition();
            $form->bind($goodsRequisitionModel);
            
            $reqRejectedDetails = $this->goodsRequisitionService->getRequisitionRejectedDetails($id);
           // $responsibilityCategory = $this->responsibilityService->listSelectData($tableName = 'responsibility_category', $columnName = 'responsibility_name');
            
            $request = $this->getRequest();
             if ($request->isPost()) {
                 $form->setData($request->getPost());
                 if ($form->isValid()) {
                     try {
                      //   $this->goodsRequisitionService->saveResponsibility($goodsRequisitionModel);
                       //  $this->redirect()->toRoute('responsibilitycategory');
                     }
                     catch(\Exception $e) {
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                        return $this->redirect()->toRoute('all-goods-requisition-list');
                             // Some DB Error happened, log it and let the user know
                     }
                 }
             }

            return new ViewModel(array(
                'id' => $id,
                'form' => $form,
                'reqRejectedDetails' => $reqRejectedDetails

                //'empAllGoods' => $this->goodsTransactionService->listEmpAllGoods()
                ));
            }else{
                return $this->redirect()->toRoute('all-goods-requisition-list');
            }
    }

    //To view or display the details of requisition forwarded by store or procurement

    public function requisitionForwardedDetailsAction()
    {
        $this->loginDetails();   
        $id = (int) $this->params()->fromRoute('id', 0);
        
        $form = new GoodsRequisitionForwardUpdateForm();
        $goodsRequisitionModel = new GoodsRequisition();
        $form->bind($goodsRequisitionModel);
        
        $reqForwardedDetails = $this->goodsRequisitionService->getRequisitionForwardedDetails($id);
       // $responsibilityCategory = $this->responsibilityService->listSelectData($tableName = 'responsibility_category', $columnName = 'responsibility_name');
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
                  //   $this->goodsRequisitionService->saveResponsibility($goodsRequisitionModel);
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
            'reqForwardedDetails' => $reqForwardedDetails

            //'empAllGoods' => $this->goodsTransactionService->listEmpAllGoods()
            ));
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


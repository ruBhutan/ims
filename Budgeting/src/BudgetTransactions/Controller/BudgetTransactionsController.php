<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace BudgetTransactions\Controller;

use BudgetTransactions\Service\BudgetTransactionsServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use BudgetTransactions\Model\BudgetTransactions;
use BudgetTransactions\Model\BudgetTransactionsSelect;
use BudgetTransactions\Form\CurrentBudgetTransactionsSelectForm;
use BudgetTransactions\Form\CapitalBudgetTransactionsSelectForm;
use BudgetTransactions\Form\BudgetTransactionsForm;
use BudgetTransactions\Form\CapitalBudgetTransactionsForm;
use BudgetTransactions\Form\EditCapitalBudgetTransactionsForm;
use BudgetTransactions\Form\CurrentBudgetTransactionsForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;
use Zend\Form\Element\Select;

/**
 * Description of IndexController
 *
 * @author eDruk Private Ltd
 */

class BudgetTransactionsController extends AbstractActionController
{
    protected $transactionService;
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
	
    public function __construct(BudgetTransactionsServiceInterface $transactionService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
    {
        $this->transactionService = $transactionService;
        $this->notificationService = $notificationService;
        $this->auditTrailService = $auditTrailService;
        $this->serviceLocator = $serviceLocator;

        /*
        * To retrieve the user name from the session
        */
        $authPlugin = $serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
        $this->username = $authPlugin['username'];
        $this->userrole = $authPlugin['role'];
        $this->userregion = $authPlugin['region'];
        $this->usertype = $authPlugin['user_type_id'];
        $this->user_status_id = $authPlugin['user_status_id'];

        /*
        * Getting the employee_details_id related to username
        */
        $empData = $this->transactionService->getUserDetailsId($this->username, $this->usertype);
        foreach($empData as $emp){
            $this->employee_details_id = $emp['id'];
            $this->userDetails = $emp['first_name'].' '.$emp['middle_name'].' '.$emp['last_name'];
            $this->organisation_id = $emp['organisation_id'];
            $this->departments_id = $emp['departments_id'];
            $this->userImage = $emp['profile_picture'];
        }
    }

    public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }
	
    public function applyCurrentSupplementaryBudgetAction()
    {
        $this->loginDetails();
		
        $form = new CurrentBudgetTransactionsSelectForm($this->serviceLocator);
        
        $tmp_data = array();
        $budgetSupplementaryList = $this->transactionService->getBudgetSupplementaryDetails('budget_proposal', 'Not Submitted', $this->organisation_id);

        $budget_supplementary_array = $this->transactionService->getBudgetSupplementaryDetails('budget_proposal', 'Not Submitted', $this->organisation_id);

        foreach($budget_supplementary_array as $tmp){
            $tmp_data[] = $tmp['id'];
        } 

        $budgetForm = new CurrentBudgetTransactionsForm($tmp_data);
		
		$request = $this->getRequest(); 
            if ($request->isPost()) {
                $form->setData($request->getPost());
                if ($form->isValid()) {
                    $fromData = array();
                    $fromData['budget_type'] = $this->getRequest()->getPost('budget_type');
                    $fromData['status'] = $this->getRequest()->getPost('status');
                    $fromData['organisation_id'] = $this->getRequest()->getPost('organisation_id');
                    $fromData['chart_of_accounts_id'] = $this->getRequest()->getPost('from_chart_of_accounts_id'); 
                    try{
                        $this->transactionService->saveSupplementaryBudget($fromData, $type = 'current');
                        $this->auditTrailService->saveAuditTrail("INSERT", "Budget Supplementary", "ALL", "SUCCESS");
                        $this->flashMessenger()->addMessage('Capital Budget Supplementary was successfully added');
                        return $this->redirect()->toRoute('currentsupplementarybudget');
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
                    'ledgerHeads' => $ledgerHeads,
                    'budgetForm' => $budgetForm,
                    'budgetSupplementaryList' => $budgetSupplementaryList,
                    'chartAccounts' => $chartAccounts,
                    'organisation_id' => $this->organisation_id,
                    'accountGroupHeads' => $accountGroupHeads,
                    'message' => $message,
                    'keyphrase' => $this->keyphrase,
                );
    }
	
    public function addCurrentSupplementaryBudgetAction()
    {
            $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
            $form = new CurrentBudgetTransactionsSelectForm($dbAdapter);

            $request = $this->getRequest();
            if ($request->isPost()) {
                $form->setData($request->getPost());
                if ($form->isValid()) {
                                    $fromData = array();
                                    $fromData['budget_ledger_head_id'] = $this->getRequest()->getPost('from_budget_ledger_head_id');
                                    $fromData['accounts_group_head_id'] = $this->getRequest()->getPost('from_accounts_group_head_id');
                                    $fromData['chart_of_accounts_id'] = $this->getRequest()->getPost('from_chart_of_accounts_id');
                                    $fromDataDetails = $this->transactionService->getBudgetDetails($tableName = 'budget_proposal', $fromData);
                }
            }

             $budgetForm = new CurrentBudgetTransactionsForm();
             $chartAccounts = $this->transactionService->listSelectData($tableName = 'chart_of_accounts', $columnName ='account_code', $condition = NULL);

            return array(
                    'budgetForm' => $budgetForm,
                    'fromDataDetails' => $fromDataDetails,
                    'chartAccounts' => $chartAccounts,
                    'organisation_id' => $this->organisation_id);
    }
	
    public function insertCurrentSupplementaryBudgetAction()
    {
        $this->loginDetails();

        $budget_supplementary_array = $this->transactionService->getBudgetSupplementaryDetails('budget_proposal', 'Not Submitted', $this->organisation_id);

        foreach($budget_supplementary_array as $tmp){
            $tmp_data[] = $tmp['id'];
        } 

        $form = new CurrentBudgetTransactionsForm($tmp_data);
        $transactionModel = new BudgetTransactions();
        $form->bind($transactionModel);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) { 
                $data_to_insert = $this->extractFormData($tmp_data); 
                try{
                    $this->transactionService->saveBudgetTransactions($transactionModel, $data_to_insert, $tableName = 'budget_supplementary');
                    $this->auditTrailService->saveAuditTrail("UPDATE", "Budget Supplementary", "ALL", "SUCCESS");
                    $this->flashMessenger()->addMessage('Current Budget Supplementary was successfully submitted');
                    return $this->redirect()->toRoute('listorgcurrentsupplementarybudget');
                }
                catch(\Exception $e) {
                    die($e->getMessage());
                    // Some DB Error happened, log it and let the user know
                   }
            }
        }

        return array();
    }

    public function listOrgCurrentSupplementaryBudgetAction()
    {
        $this->loginDetails(); 
		
        $supplementaryBudget = $this->transactionService->listSupplementaryBudgetTransactions($budgetType='current', $this->organisation_id);
        
        $message = NULL;
		
        return array(
            'supplementaryBudget' => $supplementaryBudget,
            'message' => $message,
            'keyphrase' => $this->keyphrase,
        );
    }

    public function editCurrentSupplementaryBudgetAction()
    {
        $this->loginDetails(); 

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $form = new EditCapitalBudgetTransactionsForm();
            $transactionModel = new BudgetTransactions();
            $form->bind($transactionModel);

            $supplementaryBudget = $this->transactionService->findSupplementaryBudgetTransactions($budgetType='current', $id);
        
            $message = NULL;

            $request = $this->getRequest();
            if ($request->isPost()) {
                $form->setData($request->getPost());
                if ($form->isValid()) {  
                    try{
                        $this->transactionService->updateBudgetTransactions($transactionModel, $tableName = 'budget_supplementary');
                        $this->auditTrailService->saveAuditTrail("UPDATE", "Budget Supplementary", "ALL", "SUCCESS");
                        $this->flashMessenger()->addMessage('Current Budget Supplementary was successfully edited');
                        return $this->redirect()->toRoute('listorgcurrentsupplementarybudget');
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
                'supplementaryBudget' => $supplementaryBudget,
                'message' => $message,
                'keyphrase' => $this->keyphrase,
            );
        }else{
            return $this->redirect()->toRoute('listorgcurrentsupplementarybudget');
        }
    }
	
    public function listCurrentSupplementaryBudgetAction()
    {
        $this->loginDetails(); 

        $form = new EditCapitalBudgetTransactionsForm();
		
        $supplementaryBudget = $this->transactionService->listSupplementaryBudgetTransactions($budgetType='current', NULL);
        
        $message = NULL;
		
        return array(
			'form' => $form,
            'supplementaryBudget' => $supplementaryBudget,
            'keyphrase' => $this->keyphrase,
            'message' => $message,
        );
    }
	
    public function viewCurrentSupplementaryBudgetAction()
    {
        //get the id
		$this->loginDetails(); 

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $form = new EditCapitalBudgetTransactionsForm();
		
            $supplementaryBudget = $this->transactionService->findSupplementaryBudgetTransactions($budgetType='current', $id);
            
            return array(
                'form' => $form,
                'supplementaryBudget' => $supplementaryBudget,
            );
        }else{
            return $this->redirect()->toRoute('listorgcurrentsupplementarybudget');
        }
    }
	
    public function applyCapitalSupplementaryBudgetAction()
    {
        $this->loginDetails();

        $form = new CapitalBudgetTransactionsSelectForm($this->serviceLocator); 

        $tmp_data = array();
        $budgetSupplementaryList = $this->transactionService->getBudgetSupplementaryDetails('budget_proposal_capital', 'Not Submitted', $this->organisation_id);

        $budget_supplementary_array = $this->transactionService->getBudgetSupplementaryDetails('budget_proposal_capital', 'Not Submitted', $this->organisation_id);

        foreach($budget_supplementary_array as $tmp){
            $tmp_data[] = $tmp['id'];
        } 

        $budgetForm = new CapitalBudgetTransactionsForm($tmp_data);
        /*$chartAccounts = $this->transactionService->listSelectData($tableName = 'chart_of_accounts', $columnName ='account_code', $condition = NULL);

        $ledgerHeads = $this->transactionService->listSelectData($tableName = 'budget_ledger_head', $columnName='ledger_head', $condition = NULL);
        $accountGroupHeads = $this->transactionService->listSelectData($tableName = 'accounts_group_head', $columnName='group_head', $condition = NULL);
        */

        $message = NULL;

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $fromData = array();
                $fromData['budget_type'] = $this->getRequest()->getPost('budget_type');
                $fromData['status'] = $this->getRequest()->getPost('status');
                $fromData['organisation_id'] = $this->getRequest()->getPost('organisation_id');
                $fromData['object_code_id'] = $this->getRequest()->getPost('from_object_code_id');
               // $fromDataDetails = $this->transactionService->getBudgetDetails($tableName = 'budget_proposal_capital', $fromData); 
                try{
                    $this->transactionService->saveSupplementaryBudget($fromData, $type = 'capital');
                    $this->auditTrailService->saveAuditTrail("INSERT", "Budget Supplementary", "ALL", "SUCCESS");
                    $this->flashMessenger()->addMessage('Capital Budget Supplementary was successfully added');
                    return $this->redirect()->toRoute('capitalsupplementarybudget');
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
                'ledgerHeads' => $ledgerHeads,
                'budgetForm' => $budgetForm,
                'budgetSupplementaryList' => $budgetSupplementaryList,
                'chartAccounts' => $chartAccounts,
                'organisation_id' => $this->organisation_id,
                'accountGroupHeads' => $accountGroupHeads,
                'message' => $message,
                'keyphrase' => $this->keyphrase,
            );
    }
	
    public function addCapitalSupplementaryBudgetAction()
    {
        $this->loginDetails();

        $form = new CapitalBudgetTransactionsSelectForm($this->serviceLocator);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) { 
                $fromData = array();
                $fromData['activity_name'] = $this->getRequest()->getPost('from_activity_name_id');
                $fromData['broad_head_name_id'] = $this->getRequest()->getPost('from_broad_head_name_id');
                $fromData['object_code_id'] = $this->getRequest()->getPost('from_object_code_id');
                $fromDataDetails = $this->transactionService->getBudgetDetails($tableName = 'budget_proposal_capital', $fromData); 
            }
        }

        $budgetForm = new CapitalBudgetTransactionsForm();
        $chartAccounts = $this->transactionService->listSelectData($tableName = 'chart_of_accounts', $columnName ='account_code', $condition = NULL);

        return array(
            'budgetForm' => $budgetForm,
            'fromDataDetails' => $fromDataDetails,
            'chartAccounts' => $chartAccounts,
            'organisation_id' => $this->organisation_id);
    }
	
    public function insertCapitalSupplementaryBudgetAction()
    {
        $this->loginDetails();
        $budget_supplementary_array = $this->transactionService->getBudgetSupplementaryDetails('budget_proposal_capital', 'Not Submitted', $this->organisation_id);

        foreach($budget_supplementary_array as $tmp){
            $tmp_data[] = $tmp['id'];
        } 

        $form = new CapitalBudgetTransactionsForm($tmp_data);
        $transactionModel = new BudgetTransactions();
        $form->bind($transactionModel);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) { 
                $data_to_insert = $this->extractFormData($tmp_data); 
                try{
                    $this->transactionService->saveBudgetTransactions($transactionModel, $data_to_insert, $tableName = 'budget_supplementary');
                    $this->auditTrailService->saveAuditTrail("UPDATE", "Budget Supplementary", "ALL", "SUCCESS");
                    $this->flashMessenger()->addMessage('Capital Budget Supplementary was successfully submitted');
                    return $this->redirect()->toRoute('listorgcapitalsupplementarybudget');
                }
                catch(\Exception $e) {
                    die($e->getMessage());
                    // Some DB Error happened, log it and let the user know
                   }
            }
        }

        return array();
    }

    public function listOrgCapitalSupplementaryBudgetAction()
    {
        $this->loginDetails(); 
		
        $supplementaryBudget = $this->transactionService->listSupplementaryBudgetTransactions($budgetType='capital', $this->organisation_id);
        
        $message = NULL;
		
        return array(
            'supplementaryBudget' => $supplementaryBudget,
            'message' => $message,
            'keyphrase' => $this->keyphrase,
        );
    }
	
    public function listCapitalSupplementaryBudgetAction()
    {
        $this->loginDetails(); 

        $form = new EditCapitalBudgetTransactionsForm();
		$supplementaryBudget = $this->transactionService->listSupplementaryBudgetTransactions($budgetType='capital', NULL);
		
        return array(
			'form' => $form,
            'supplementaryBudget' => $supplementaryBudget,
            'keyphrase' => $this->keyphrase,
        );
    }


    public function updateSupplementaryBudgetProposalAction()
    {
        $id_from_route = $this->params()->fromRoute('id');
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        $status_from_route = $this->params()->fromRoute('status');
        $status = $this->my_decrypt($status_from_route, $this->keyphrase);

        $type_from_route = $this->params()->fromRoute('type');
        $type = $this->my_decrypt($type_from_route, $this->keyphrase);

        $message = NULL;
        
        if(is_numeric($id)){
            try {
                $this->transactionService->updateBudgetProposalTransaction($status,$id, $tableName = 'budget_supplementary');
                $this->auditTrailService->saveAuditTrail("UPDATE", "Budget Supplementary", "status", "SUCCESS");
                $this->flashMessenger()->addMessage('You have successfully '.$status.' the supplementary budget');

                if($type == 'capital'){
                    return $this->redirect()->toRoute('listcapitalsupplementarybudget');
                }else{
                    return $this->redirect()->toRoute('listcurrentsupplementarybudget');
                }
            }
            catch(\Exception $e) {
                    die($e->getMessage());
                    // Some DB Error happened, log it and let the user know
            }
        }else{
            if($type == 'capital'){
                return $this->redirect()->toRoute('listcapitalsupplementarybudget');
            }else{
                return $this->redirect()->toRoute('listcurrentsupplementarybudget');
            }
        }

        return array(
			'message' => $message,
            'keyphrase' => $this->keyphrase,
        );
    }

    public function editCapitalSupplementaryBudgetAction()
    {
        $this->loginDetails(); 

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $form = new EditCapitalBudgetTransactionsForm();
            $transactionModel = new BudgetTransactions();
            $form->bind($transactionModel);

            $supplementaryBudget = $this->transactionService->findSupplementaryBudgetTransactions($budgetType='capital', $id);
        
            $message = NULL;

            $request = $this->getRequest();
            if ($request->isPost()) {
                $form->setData($request->getPost());
                if ($form->isValid()) {  
                    try{
                        $this->transactionService->updateBudgetTransactions($transactionModel, $tableName = 'budget_supplementary');
                        $this->auditTrailService->saveAuditTrail("UPDATE", "Budget Supplementary", "ALL", "SUCCESS");
                        $this->flashMessenger()->addMessage('Capital Budget Supplementary was successfully edited');
                        return $this->redirect()->toRoute('listorgcapitalsupplementarybudget');
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
                'supplementaryBudget' => $supplementaryBudget,
                'message' => $message,
                'keyphrase' => $this->keyphrase,
            );
        }else{
            return $this->redirect()->toRoute('listorgcapitalsupplementarybudget');
        }
    }
	
    public function viewCapitalSupplementaryBudgetAction()
    {
        $this->loginDetails(); 

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $form = new EditCapitalBudgetTransactionsForm();
            $supplementaryBudget = $this->transactionService->findSupplementaryBudgetTransactions($budgetType='capital', $id);
        
            $message = NULL;
            
            return array(
                'form' => $form,
                'supplementaryBudget' => $supplementaryBudget,
                'message' => $message,
                'keyphrase' => $this->keyphrase,
            );   
        }else{
            return $this->redirect()->toRoute('listorgcapitalsupplementarybudget');
        }
    }
	
    public function applyCurrentBudgetWithdrawalAction()
    {
        $this->loginDetails();

        $form = new CurrentBudgetTransactionsSelectForm($this->serviceLocator);

        $tmp_data = array();
        $budgetWithdrawalList = $this->transactionService->getBudgetWithdrawalDetails('budget_proposal', 'Not Submitted', $this->organisation_id);

        $budget_withdrawal_array = $this->transactionService->getBudgetWithdrawalDetails('budget_proposal', 'Not Submitted', $this->organisation_id);

        foreach($budget_withdrawal_array as $tmp){
            $tmp_data[] = $tmp['id'];
        }

        $budgetForm = new CurrentBudgetTransactionsForm($tmp_data);

        $chartAccounts = $this->transactionService->listSelectData($tableName = 'chart_of_accounts', $columnName ='account_code', $condition = NULL);
    
        $ledgerHeads = $this->transactionService->listSelectData($tableName = 'budget_ledger_head', $columnName='ledger_head', $condition = NULL);
        $accountGroupHeads = $this->transactionService->listSelectData($tableName = 'accounts_group_head', $columnName='group_head', $condition = NULL);
    
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $fromData = array();
                $fromData['budget_type'] = $this->getRequest()->getPost('budget_type');
                $fromData['status'] = $this->getRequest()->getPost('status');
                $fromData['organisation_id'] = $this->getRequest()->getPost('organisation_id');
                $fromData['chart_of_accounts_id'] = $this->getRequest()->getPost('from_chart_of_accounts_id');
                //$fromDataDetails = $this->transactionService->getBudgetDetails($tableName = 'budget_proposal', $fromData);

                try{
                    $this->transactionService->saveWithdrawalBudget($fromData, $type = 'current');
                    $this->auditTrailService->saveAuditTrail("INSERT", "Budget Withdrawal", "ALL", "SUCCESS");
                    $this->flashMessenger()->addMessage('Current Budget WIthdrawal was successfully added');
                    return $this->redirect()->toRoute('currentbudgetwithdrawal');
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
            'ledgerHeads' => $ledgerHeads,
            'budgetForm' => $budgetForm,
            'budgetWithdrawalList' => $budgetWithdrawalList,
            //'fromDataDetails' => $fromDataDetails,
            'chartAccounts' => $chartAccounts,
            'organisation_id' => $this->organisation_id,
            'accountGroupHeads' => $accountGroupHeads,
            'message' => $message,
            'keyphrase' => $this->keyphrase,
        );
    }
	
    public function addCurrentBudgetWithdrawalAction()
    {
            $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
            $form = new CurrentBudgetTransactionsSelectForm($dbAdapter);

            $request = $this->getRequest();
            if ($request->isPost()) {
                $form->setData($request->getPost());
                if ($form->isValid()) {
                                    $fromData = array();
                                    $fromData['budget_ledger_head_id'] = $this->getRequest()->getPost('from_budget_ledger_head_id');
                                    $fromData['accounts_group_head_id'] = $this->getRequest()->getPost('from_accounts_group_head_id');
                                    $fromData['chart_of_accounts_id'] = $this->getRequest()->getPost('from_chart_of_accounts_id');
                                    $fromDataDetails = $this->transactionService->getBudgetDetails($tableName = 'budget_proposal', $fromData);
                }
            }

            $budgetForm = new CurrentBudgetTransactionsForm();
            $chartAccounts = $this->transactionService->listSelectData($tableName = 'chart_of_accounts', $columnName ='account_code', $condition = NULL);

            return array(
                    'budgetForm' => $budgetForm,
                    'fromDataDetails' => $fromDataDetails,
                    'chartAccounts' => $chartAccounts,
                    'organisation_id' => $this->organisation_id);
    }
	
    public function insertCurrentBudgetWithdrawalAction()
    {
        $this->loginDetails();

        $budget_withdrawal_array = $this->transactionService->getBudgetWithdrawalDetails('budget_proposal', 'Not Submitted', $this->organisation_id);

        foreach($budget_withdrawal_array as $tmp){
            $tmp_data[] = $tmp['id'];
        }

        $form = new CurrentBudgetTransactionsForm($tmp_data);
        $transactionModel = new BudgetTransactions();
        $form->bind($transactionModel);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) { 
                $data_to_insert = $this->extractFormData($tmp_data); 
                try{
                    $this->transactionService->saveBudgetTransactions($transactionModel, $data_to_insert, $tableName = 'budget_withdrawal');
                    $this->auditTrailService->saveAuditTrail("UPDATE", "Budget Withdrawal", "ALL", "SUCCESS");
                    $this->flashMessenger()->addMessage('Current Budget Withdrawal was successfully submitted');
                    return $this->redirect()->toRoute('listorgcurrentbudgetwithdrawal');
                }
                catch(\Exception $e) {
                    die($e->getMessage());
                    // Some DB Error happened, log it and let the user know
                   }
            }
        }

        return array();
    }

    public function listOrgCurrentBudgetWithdrawalAction()
    {
        $this->loginDetails(); 
		
        $budgetWithdrawal = $this->transactionService->listBudgetWithdrawalTransactions($budgetType='current', $this->organisation_id);
        
        $message = NULL;
		
        return array(
            'budgetWithdrawal' => $budgetWithdrawal,
            'message' => $message,
            'keyphrase' => $this->keyphrase,
        );
    }

    public function editCurrentBudgetWithdrawalAction()
    {
        $this->loginDetails(); 

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $form = new EditCapitalBudgetTransactionsForm();
            $transactionModel = new BudgetTransactions();
            $form->bind($transactionModel);

            $withdrawalBudget = $this->transactionService->findWithdrawalBudgetTransactions($budgetType='current', $id);
        
            $message = NULL;

            $request = $this->getRequest();
            if ($request->isPost()) {
                $form->setData($request->getPost());
                if ($form->isValid()) {  
                    try{
                        $this->transactionService->updateBudgetTransactions($transactionModel, $tableName = 'budget_withdrawal');
                        $this->auditTrailService->saveAuditTrail("UPDATE", "Budget Withdrawal", "ALL", "SUCCESS");
                        $this->flashMessenger()->addMessage('Current Budget Withdrawal was successfully edited');
                        return $this->redirect()->toRoute('listorgcurrentbudgetwithdrawal');
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
                'withdrawalBudget' => $withdrawalBudget,
                'message' => $message,
                'keyphrase' => $this->keyphrase,
            );
        }else{
            return $this->redirect()->toRoute('listorgcurrentbudgetwithdrawal');
        }
    }
	
    public function listCurrentBudgetWithdrawalAction()
    {
        $this->loginDetails();

        $form = new EditCapitalBudgetTransactionsForm();
		
        $budgetWithdrawal = $this->transactionService->listBudgetWithdrawalTransactions($budgetType='current', NULL);
        
        $message = NULL;
		
        return array(
			'form' => $form,
            'budgetWithdrawal' => $budgetWithdrawal,
            'message' => $message,
            'keyphrase' => $this->keyphrase,
        );
    }
	
    public function viewCurrentBudgetWithdrawalAction()
    {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $form = new EditCapitalBudgetTransactionsForm();
		
            $budgetWithdrawal = $this->transactionService->findWithdrawalBudgetTransactions($budgetType='current', $id);
            
            return array(
                'form' => $form,
                'budgetWithdrawal' => $budgetWithdrawal,
            );   
        }
        return $this->redirect()->toRoute('listorgcapitalbudgetwithdrawal');
    }
	
    public function applyCapitalBudgetWithdrawalAction()
    {
        $this->loginDetails();

        $form = new CapitalBudgetTransactionsSelectForm($this->serviceLocator);
        
        $tmp_data = array();
        $budgetWithdrawalList = $this->transactionService->getBudgetWithdrawalDetails('budget_proposal_capital', 'Not Submitted', $this->organisation_id);

        $budget_withdrawal_array = $this->transactionService->getBudgetWithdrawalDetails('budget_proposal_capital', 'Not Submitted', $this->organisation_id);

        foreach($budget_withdrawal_array as $tmp){
            $tmp_data[] = $tmp['id'];
        }
        
        $budgetForm = new CapitalBudgetTransactionsForm($tmp_data);

        $chartAccounts = $this->transactionService->listSelectData($tableName = 'chart_of_accounts', $columnName ='account_code', $condition = NULL);
		
		$ledgerHeads = $this->transactionService->listSelectData($tableName = 'budget_ledger_head', $columnName='ledger_head', $condition = NULL);
		$accountGroupHeads = $this->transactionService->listSelectData($tableName = 'accounts_group_head', $columnName='group_head', $condition = NULL);
		
		$request = $this->getRequest();
                if ($request->isPost()) {
                    $form->setData($request->getPost());
                    if ($form->isValid()) {
                        $fromData = array();
                        $fromData['budget_type'] = $this->getRequest()->getPost('budget_type');
                        $fromData['status'] = $this->getRequest()->getPost('status');
                        $fromData['organisation_id'] = $this->getRequest()->getPost('organisation_id');
                        $fromData['object_code_id'] = $this->getRequest()->getPost('from_object_code_id');
                       // $fromDataDetails = $this->transactionService->getBudgetDetails($tableName = 'budget_proposal_capital', $fromData);
                       try{
                        $this->transactionService->saveWithdrawalBudget($fromData, $type = 'capital');
                        $this->auditTrailService->saveAuditTrail("INSERT", "Budget Withdrawal", "ALL", "SUCCESS");
                        $this->flashMessenger()->addMessage('Capital Budget WIthdrawal was successfully added');
                        return $this->redirect()->toRoute('capitalbudgetwithdrawal');
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
			'ledgerHeads' => $ledgerHeads,
            'budgetForm' => $budgetForm,
			'budgetWithdrawalList' => $budgetWithdrawalList,
			'chartAccounts' => $chartAccounts,
			'organisation_id' => $this->organisation_id,
            'accountGroupHeads' => $accountGroupHeads,
            'message' => $message,
            'keyphrase' => $this->keyphrase,
        );
    }
	
    public function addCapitalBudgetWithdrawalAction()
    {
            $dbAdapter1 = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
            $form = new CapitalBudgetTransactionsSelectForm($dbAdapter1);

            $request = $this->getRequest();
     if ($request->isPost()) {
         $form->setData($request->getPost());
         if ($form->isValid()) {
                             $fromData = array();
                             $fromData['activity_name'] = $this->getRequest()->getPost('from_activity_name_id');
                             $fromData['broad_head_name_id'] = $this->getRequest()->getPost('from_broad_head_name_id');
                             $fromData['object_code_id'] = $this->getRequest()->getPost('from_object_code_id');
                             $fromDataDetails = $this->transactionService->getBudgetDetails($tableName = 'budget_proposal_capital', $fromData);
         }
     }

             $budgetForm = new CapitalBudgetTransactionsForm();
             $chartAccounts = $this->transactionService->listSelectData($tableName = 'chart_of_accounts', $columnName ='account_code', $condition = NULL);

    return array(
                    'budgetForm' => $budgetForm,
                    'fromDataDetails' => $fromDataDetails,
                    'chartAccounts' => $chartAccounts,
                    'organisation_id' => $this->organisation_id);
    }
	
	public function insertCapitalBudgetWithdrawalAction()
	{
        $this->loginDetails();

        $budget_withdrawal_array = $this->transactionService->getBudgetWithdrawalDetails('budget_proposal_capital', 'Not Submitted', $this->organisation_id);

        foreach($budget_withdrawal_array as $tmp){
            $tmp_data[] = $tmp['id'];
        }
		$form = new CapitalBudgetTransactionsForm($tmp_data);
		$transactionModel = new BudgetTransactions();
		$form->bind($transactionModel);
		
		$request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) { 
                $data_to_insert = $this->extractFormData($tmp_data); 
                try{
                    $this->transactionService->saveBudgetTransactions($transactionModel, $data_to_insert, $tableName = 'budget_withdrawal');
                    $this->auditTrailService->saveAuditTrail("UPDATE", "Budget Withdrawal", "ALL", "SUCCESS");
                    $this->flashMessenger()->addMessage('Capital Budget Withdrawal was successfully submitted');
                    return $this->redirect()->toRoute('listorgcapitalbudgetwithdrawal');
                }
                catch(\Exception $e) {
                    die($e->getMessage());
                    // Some DB Error happened, log it and let the user know
                   }
            }
        }
		 
        return array();
    }
    

    public function listOrgCapitalBudgetWithdrawalAction()
    {
        $this->loginDetails(); 
		
        $budgetWithdrawal = $this->transactionService->listBudgetWithdrawalTransactions($budgetType='capital', $this->organisation_id);
        
        $message = NULL;
		
        return array(
            'budgetWithdrawal' => $budgetWithdrawal,
            'message' => $message,
            'keyphrase' => $this->keyphrase,
        );
    }


    public function editCapitalBudgetWithdrawalAction()
    {
        $this->loginDetails(); 

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $form = new EditCapitalBudgetTransactionsForm();
            $transactionModel = new BudgetTransactions();
            $form->bind($transactionModel);

            $withdrawalBudget = $this->transactionService->findWithdrawalBudgetTransactions($budgetType='capital', $id);
        
            $message = NULL;

            $request = $this->getRequest();
            if ($request->isPost()) {
                $form->setData($request->getPost());
                if ($form->isValid()) {  
                    try{
                        $this->transactionService->updateBudgetTransactions($transactionModel, $tableName = 'budget_withdrawal');
                        $this->auditTrailService->saveAuditTrail("UPDATE", "Budget Withdrawal", "ALL", "SUCCESS");
                        $this->flashMessenger()->addMessage('Capital Budget Withdrawal was successfully edited');
                        return $this->redirect()->toRoute('listorgcapitalbudgetwithdrawal');
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
                'withdrawalBudget' => $withdrawalBudget,
                'message' => $message,
                'keyphrase' => $this->keyphrase,
            );
        }else{
            return $this->redirect()->toRoute('listorgcapitalsupplementarybudget');
        }
    }

	
	public function listCapitalBudgetWithdrawalAction()
    {
        $this->loginDetails();

        $form = new EditCapitalBudgetTransactionsForm();
		
        $budgetWithdrawal = $this->transactionService->listBudgetWithdrawalTransactions($budgetType='capital', NULL);
        
        $message = NULL;
		
        return array(
			'form' => $form,
            'budgetWithdrawal' => $budgetWithdrawal,
            'message' => $message,
            'keyphrase' => $this->keyphrase,
        );
    }
	
	public function viewCapitalBudgetWithdrawalAction()
    {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $form = new EditCapitalBudgetTransactionsForm();
		
            $budgetWithdrawal = $this->transactionService->findWithdrawalBudgetTransactions($budgetType='capital', $id);
            
            return array(
                'form' => $form,
                'budgetWithdrawal' => $budgetWithdrawal,
            );   
        }
        return $this->redirect()->toRoute('listorgcapitalbudgetwithdrawal');
    }


    public function updateWithdrawalBudgetProposalAction()
    {
        $id_from_route = $this->params()->fromRoute('id');
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        $status_from_route = $this->params()->fromRoute('status');
        $status = $this->my_decrypt($status_from_route, $this->keyphrase);

        $type_from_route = $this->params()->fromRoute('type');
        $type = $this->my_decrypt($type_from_route, $this->keyphrase);

        $message = NULL;
        
        if(is_numeric($id)){
            try {
                $this->transactionService->updateBudgetProposalTransaction($status,$id, $tableName = 'budget_withdrawal');
                $this->auditTrailService->saveAuditTrail("UPDATE", "Budget Withdrawal", "status", "SUCCESS");
                $this->flashMessenger()->addMessage('You have successfully '.$status.' the withdrawal budget');

                if($type == 'capital'){
                    return $this->redirect()->toRoute('listcapitalbudgetwithdrawal');
                }else{
                    return $this->redirect()->toRoute('listcurrentbudgetwithdrawal');
                }
            }
            catch(\Exception $e) {
                    die($e->getMessage());
                    // Some DB Error happened, log it and let the user know
            }
        }else{
            if($type == 'capital'){
                return $this->redirect()->toRoute('listcapitalbudgetwithdrawal');
            }else{
                return $this->redirect()->toRoute('listcurrentbudgetwithdrawal');
            }
        }

        return array(
			'message' => $message,
            'keyphrase' => $this->keyphrase,
        );
    }


    public function extractFormData($data)
    {
        $supplementaryData = array();
        
        foreach($data as $key=>$value)
        {
            $supplementaryData[$value]= $this->getRequest()->getPost('amount'.$value);
        }
        return $supplementaryData;
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

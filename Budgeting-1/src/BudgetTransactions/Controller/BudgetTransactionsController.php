<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace BudgetTransactions\Controller;

use BudgetTransactions\Service\BudgetTransactionsServiceInterface;
use BudgetTransactions\Model\BudgetTransactions;
use BudgetTransactions\Model\BudgetTransactionsSelect;
use BudgetTransactions\Form\CurrentBudgetTransactionsSelectForm;
use BudgetTransactions\Form\CapitalBudgetTransactionsSelectForm;
use BudgetTransactions\Form\BudgetTransactionsForm;
use BudgetTransactions\Form\CapitalBudgetTransactionsForm;
use BudgetTransactions\Form\CurrentBudgetTransactionsForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;

class BudgetTransactionsController extends AbstractActionController
{
    protected $transactionService;
    protected $username;
    protected $employee_details_id;
    protected $organisation_id;
	
    public function __construct(BudgetTransactionsServiceInterface $transactionService)
    {
            $this->transactionService = $transactionService;

            /*
             * To retrieve the user name from the session
            */
            $user_session = new Container('user');
            $this->username = $user_session->username;

            /*
            * Getting the employee_details_id related to username
            */

            $empData = $this->transactionService->getUserDetailsId($this->username);
            foreach($empData as $emp){
                    $this->employee_details_id = $emp['id'];
                    }

            //get the organisation id
            $organisationID = $this->transactionService->getOrganisationId($this->username);
            foreach($organisationID as $organisation){
                    $this->organisation_id = $organisation['organisation_id'];
            }
    }
	
    public function applyCurrentSupplementaryBudgetAction()
    {
		$dbAdapter1 = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
		$form = new CurrentBudgetTransactionsSelectForm($dbAdapter1);
		
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
		
		$ledgerHeads = $this->transactionService->listSelectData($tableName = 'budget_ledger_head', $columnName='ledger_head', $condition = NULL);
		$accountGroupHeads = $this->transactionService->listSelectData($tableName = 'accounts_group_head', $columnName='group_head', $condition = NULL);
		 
                return array(
			'form' => $form,
			'ledgerHeads' => $ledgerHeads,
                        'budgetForm' => $budgetForm,
			'fromDataDetails' => $fromDataDetails,
			'chartAccounts' => $chartAccounts,
			'organisation_id' => $this->organisation_id,
			'accountGroupHeads' => $accountGroupHeads);
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
            $form = new CurrentBudgetTransactionsForm();
            $transactionModel = new BudgetTransactions();
            $form->bind($transactionModel);

            $request = $this->getRequest();
            if ($request->isPost()) {
                $form->setData($request->getPost());
                if ($form->isValid()) {
                                    $this->transactionService->saveBudgetTransactions($transactionModel, $tableName = 'budget_supplementary');
                                    $this->redirect()->toRoute('viewcurrentsupplementarybudget');
                }
            }

           return array();
    }
	
    public function listCurrentSupplementaryBudgetAction()
    {
        $form = new CurrentBudgetTransactionsForm();
		
		$supplementaryBudget = $this->transactionService->listSupplementaryBudgetTransactions($budgetType='Current Budget');
		
        return array(
			'form' => $form,
			'supplementaryBudget' => $supplementaryBudget);
    }
	
    public function viewCurrentSupplementaryBudgetAction()
    {
        //get the id
		$id = (int) $this->params()->fromRoute('id',0);
		$form = new CurrentBudgetTransactionsForm();
		
		$supplementaryBudget = $this->transactionService->findSupplementaryBudgetTransactions($budgetType='Current Budget', $id);
		
        return array(
			'form' => $form,
			'supplementaryBudget' => $supplementaryBudget);
    }
	
    public function applyCapitalSupplementaryBudgetAction()
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

        $ledgerHeads = $this->transactionService->listSelectData($tableName = 'budget_ledger_head', $columnName='ledger_head', $condition = NULL);
        $accountGroupHeads = $this->transactionService->listSelectData($tableName = 'accounts_group_head', $columnName='group_head', $condition = NULL);

        return array(
                'form' => $form,
                'ledgerHeads' => $ledgerHeads,
                'budgetForm' => $budgetForm,
                'fromDataDetails' => $fromDataDetails,
                'chartAccounts' => $chartAccounts,
                'organisation_id' => $this->organisation_id,
                'accountGroupHeads' => $accountGroupHeads);
    }
	
    public function addCapitalSupplementaryBudgetAction()
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
	
    public function insertCapitalSupplementaryBudgetAction()
    {
            $form = new CapitalBudgetTransactionsForm();
            $transactionModel = new BudgetTransactions();
            $form->bind($transactionModel);

            $request = $this->getRequest();
            if ($request->isPost()) {
                $form->setData($request->getPost());
                if ($form->isValid()) {
                                    $this->transactionService->saveBudgetTransactions($transactionModel, $tableName = 'budget_supplementary');
                                    $this->redirect()->toRoute('viewcapitalsupplementarybudget');
                }
            }

           return array();
    }
	
    public function listCapitalSupplementaryBudgetAction()
    {
        $form = new CapitalBudgetTransactionsForm();
		
		$supplementaryBudget = $this->transactionService->listSupplementaryBudgetTransactions($budgetType='Capital Budget');
		
        return array(
			'form' => $form,
			'supplementaryBudget' => $supplementaryBudget);
    }
	
    public function viewCapitalSupplementaryBudgetAction()
    {
        //get the id
		$id = (int) $this->params()->fromRoute('id',0);
		$form = new CapitalBudgetTransactionsForm();
		
		$supplementaryBudget = $this->transactionService->findSupplementaryBudgetTransactions($budgetType='Capital Budget', $id);
		
        return array(
			'form' => $form,
			'supplementaryBudget' => $supplementaryBudget);
    }
	
    public function applyCurrentBudgetWithdrawalAction()
    {
            $dbAdapter1 = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
            $form = new CurrentBudgetTransactionsSelectForm($dbAdapter1);
		
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
		
            $ledgerHeads = $this->transactionService->listSelectData($tableName = 'budget_ledger_head', $columnName='ledger_head', $condition = NULL);
            $accountGroupHeads = $this->transactionService->listSelectData($tableName = 'accounts_group_head', $columnName='group_head', $condition = NULL);
		 
            return array(
			'form' => $form,
			'ledgerHeads' => $ledgerHeads,
                        'budgetForm' => $budgetForm,
                        'fromDataDetails' => $fromDataDetails,
                        'chartAccounts' => $chartAccounts,
                        'organisation_id' => $this->organisation_id,
			'accountGroupHeads' => $accountGroupHeads);
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
            $form = new CurrentBudgetTransactionsForm();
            $transactionModel = new BudgetTransactions();
            $form->bind($transactionModel);

            $request = $this->getRequest();
            if ($request->isPost()) {
                $form->setData($request->getPost());
                if ($form->isValid()) {
                                    $this->transactionService->saveBudgetTransactions($transactionModel, $tableName = 'budget_withdrawal');
                                    $this->redirect()->toRoute('viewcurrentsupplementarybudget');
                }
            }

           return array();
    }
	
    public function listCurrentBudgetWithdrawalAction()
    {
        $form = new CurrentBudgetTransactionsForm();
		
		$budgetWithdrawal = $this->transactionService->listBudgetWithdrawalTransactions($budgetType='Current Budget');
		
        return array(
			'form' => $form,
			'budgetWithdrawal' => $budgetWithdrawal);
    }
	
    public function viewCurrentBudgetWithdrawalAction()
    {
        $form = new CurrentBudgetTransactionsForm();
		
		$budgetWithdrawal = $this->transactionService->listBudgetWithdrawalTransactions($budgetType='Current Budget');
		
        return array(
			'form' => $form,
			'budgetWithdrawal' => $budgetWithdrawal);
    }
	
    public function applyCapitalBudgetWithdrawalAction()
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
		
		$ledgerHeads = $this->transactionService->listSelectData($tableName = 'budget_ledger_head', $columnName='ledger_head', $condition = NULL);
		$accountGroupHeads = $this->transactionService->listSelectData($tableName = 'accounts_group_head', $columnName='group_head', $condition = NULL);
		 
                return array(
			'form' => $form,
			'ledgerHeads' => $ledgerHeads,
                        'budgetForm' => $budgetForm,
			'fromDataDetails' => $fromDataDetails,
			'chartAccounts' => $chartAccounts,
			'organisation_id' => $this->organisation_id,
			'accountGroupHeads' => $accountGroupHeads);
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
		$form = new CapitalBudgetTransactionsForm();
		$transactionModel = new BudgetTransactions();
		$form->bind($transactionModel);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 $this->transactionService->saveBudgetTransactions($transactionModel, $tableName = 'budget_withdrawal');
				 $this->redirect()->toRoute('viewcurrentsupplementarybudget');
             }
         }
		 
        return array();
	}
	
	public function listCapitalBudgetWithdrawalAction()
    {
        $form = new CurrentBudgetTransactionsForm();
		
		$budgetWithdrawal = $this->transactionService->listBudgetWithdrawalTransactions($budgetType='Capital Budget');
		
        return array(
			'form' => $form,
			'budgetWithdrawal' => $budgetWithdrawal);
    }
	
	public function viewCapitalBudgetWithdrawalAction()
    {
        $form = new CurrentBudgetTransactionsForm();
		
		$budgetWithdrawal = $this->transactionService->listBudgetWithdrawalTransactions($budgetType='Capital Budget');
		
        return array(
			'form' => $form,
			'budgetWithdrawal' => $budgetWithdrawal);
    }
				
}

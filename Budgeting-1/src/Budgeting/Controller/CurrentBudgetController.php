<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Budgeting\Controller;

use Zend\ServiceManager\ServiceLocatorInterface;
use Budgeting\Service\BudgetingServiceInterface;
use Budgeting\Model\BudgetProposal;
use Budgeting\Model\BudgetLedger;
use Budgeting\Model\BudgetReappropriationSelect;
use Budgeting\Model\BudgetReappropriation;
use Budgeting\Form\BudgetProposalForm;
use Budgeting\Form\BudgetLedgerForm;
use Budgeting\Form\SubmitProposalForm;
use Budgeting\Form\BudgetReappropriationSelectForm;
use Budgeting\Form\BudgetReappropriationForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;

/**
 * Description of IndexController
 *
 * @author Mendrel
 */
class CurrentBudgetController extends AbstractActionController
{
    
	protected $budgetingService;
	protected $username;
	protected $employee_details_id;
	protected $organisation_id;
	
	public function __construct(BudgetingServiceInterface $budgetingService)
	{
		$this->budgetingService = $budgetingService;
		
		/*
		 * To retrieve the user name from the session
		*/
		$user_session = new Container('user');
                $this->username = $user_session->username;
		
		/*
		* Getting the employee_details_id related to username
		*/
		
		$empData = $this->budgetingService->getUserDetailsId($this->username);
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			}
			
		//get the organisation id
		$organisationID = $this->budgetingService->getOrganisationId($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}
	}
	
	/*
	* The action is for budget proposal
	*/
	
	public function addBudgetProposalAction()
    {
		$dbAdapter1 = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
		
		$form = new BudgetProposalForm($dbAdapter1);
		$budgetingModel = new BudgetProposal();
		$form->bind($budgetingModel);
		
		$submitForm = new SubmitProposalForm();
		
		$ledgerHeads = $this->budgetingService->listSelectData($tableName = 'budget_ledger_head', $columnName='ledger_head', $condition = NULL);
		$accountGroupHeads = $this->budgetingService->listSelectData($tableName = 'accounts_group_head', $columnName='group_head', $condition = NULL);
		$chartAccounts = $this->budgetingService->listSelectData($tableName = 'chart_of_accounts', $columnName='account_code', $condition = NULL);
		$departments = $this->budgetingService->listSelectData($tableName = 'departments', $columnName='department_name', $this->organisation_id);
		
		$budgetProposals = $this->budgetingService->listBudgetProposal($tableName = 'budget_proposal', $status = 'Not Submitted');
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 //getting data from ajax. so need to extract them and pass as variables
				 $chart_of_accounts_id = $this->getRequest()->getPost('chart_of_accounts_id');
				 $accounts_group_head_id = $this->getRequest()->getPost('accounts_group_head_id');
                 try {
					 $this->budgetingService->saveBudgetProposal($budgetingModel, $chart_of_accounts_id, $accounts_group_head_id);
					 $this->redirect()->toRoute('budgetproposal');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'submitForm' => $submitForm,
			'ledgerHeads' => $ledgerHeads,
			'accountGroupHeads' => $accountGroupHeads,
			'chartAccounts' => $chartAccounts,
			'budgetProposals' => $budgetProposals,
			'organisation_id' => $this->organisation_id,
			'departments' => $departments);
    }
	
	/*
	* The action is to view budget proposal
	*/
	
	public function viewBudgetProposalAction()
    {
        //get the budget proposal id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$dbAdapter1 = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
		$form = new BudgetProposalForm($dbAdapter1);
		$budgetingModel = new BudgetProposal();
		$form->bind($budgetingModel);
		
		$budgetDetail = $this->budgetingService->findProposalDetail($tableName = 'budget_proposal', $id);
		$budgetProposal = $this->budgetingService->listBudgetProposal($tableName = 'budget_proposal', $status = 'Approved');
		
        return array(
				'form' => $form,
				'budgetDetail' => $budgetDetail,
				'budgetProposal' => $budgetProposal);
    }
	
	public function approvedBudgetProposalAction()
    {
        $dbAdapter1 = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
		$form = new BudgetProposalForm($dbAdapter1);
		$budgetingModel = new BudgetProposal();
		$form->bind($budgetingModel);
		
		$budgetProposal = $this->budgetingService->listBudgetProposal($tableName = 'budget_proposal', $status = 'Approved');
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->budgetingService->save($budgetingModel);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
				'form' => $form,
				'budgetProposal' => $budgetProposal);
    }
	
	/*
	* The action is to view organisation budget proposal
	*/
	
	public function viewOrganisationBudgetProposalAction()
    {
        $dbAdapter1 = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
		$form = new BudgetProposalForm($dbAdapter1);
		$budgetingModel = new BudgetProposal();
		$form->bind($budgetingModel);
		
		$submitForm = new SubmitProposalForm();
		
		$budgetProposal = $this->budgetingService->listBudgetProposal($tableName = 'budget_proposal', $status = 'Submitted to OVC');
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 var_dump($form);
				 die();
                 try {
					 $this->budgetingService->save($budgetingModel);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
				'form' => $form,
				'submitForm' => $submitForm,
				'budgetProposal' => $budgetProposal);
    }
	
	/*
	* The action is for update the Budget Ledger/Programmes
	*/
	
	public function updateBudgetProposalAction()
	{
		//Value 1 is change of status from "Not Submitted" to "Submitted to HR"
		//Value 2 is change of status from "Submitted to HR" to "Submitted to OVC"
		//need to take care of organisation as well
		
		$value = (int) $this->params()->fromRoute('id', 0);
		if($value == 1){
			$status = 'Submitted to OVC';
			$previousStatus = 'Not Submitted';
		}
		else {
			$status = 'Approved';
			$previousStatus = 'Submitted to OVC';
		}
		$organisation_id = 1;
		
	
		 try {
			 $this->budgetingService->updateBudgetProposal($status, $previousStatus);
			 $this->redirect()->toRoute('orgbudgetproposal');
		 }
		 catch(\Exception $e) {
				 die($e->getMessage());
				 // Some DB Error happened, log it and let the user know
		 }
             
		 
        return array();
	}
    
	/*
	* The action is for setting the Budget Ledger/Programmes
	*/
	
	public function addBudgetLedgerAction()
	{
		$form = new BudgetLedgerForm();
		$budgetingModel = new BudgetLedger();
		$form->bind($budgetingModel);
		
		$ledgerHeads = $this->budgetingService->listBudgetLedger($tableName = 'budget_ledger_head');
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->budgetingService->saveBudgetLedger($budgetingModel);
					 $this->redirect()->toRoute('budgetledger');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'organisation_id' => $this->organisation_id,
			'ledgerHeads' => $ledgerHeads);
	}
	
	public function editBudgetLedgerAction()
	{
		//get the budget ledger id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$form = new BudgetLedgerForm();
		$budgetingModel = new BudgetLedger();
		$form->bind($budgetingModel);
		
		$ledgerDetails = $this->budgetingService->findBudgetLedger($id);
		$ledgerHeads = $this->budgetingService->listBudgetLedger($tableName = 'budget_ledger_head');
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->budgetingService->saveBudgetLedger($budgetingModel);
					 $this->redirect()->toRoute('budgetledger');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'ledgerDetails' => $ledgerDetails,
			'organisation_id' => $this->organisation_id,
			'ledgerHeads' => $ledgerHeads);
	}
    
	/*
	* The action is for viewing the Budget Ledger/Programmes
	*/
	
	public function viewBudgetLedgerAction()
	{
		//get the budget ledger id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$form = new BudgetLedgerForm();
		$budgetingModel = new BudgetLedger();
		$form->bind($budgetingModel);
		
		$ledgerDetails = $this->budgetingService->findBudgetLedger($id);
		$ledgerHeads = $this->budgetingService->listBudgetLedger($tableName = 'budget_ledger_head');
		
        return array(
			'form' => $form,
			'ledgerDetails' => $ledgerDetails,
			'organisation_id' => $this->organisation_id,
			'ledgerHeads' => $ledgerHeads);
	}
	
	/*
	* Apply Budget Reappropriation
	*/
	
	public function applyBudgetReappropriationAction()
	{
		$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
		$form = new BudgetReappropriationSelectForm($dbAdapter);
                
		$budgetingModel = new BudgetReappropriationSelect();
		$form->bind($budgetingModel);
		
		$request = $this->getRequest();
                if ($request->isPost()) {
                    $form->setData($request->getPost());
                    if ($form->isValid()) {
                                        $fromData = array();
                                        $toData = array();
                                        $fromData['budget_ledger_head_id'] = $this->getRequest()->getPost('from_budget_ledger_head_id');
                                        $fromData['accounts_group_head_id'] = $this->getRequest()->getPost('from_accounts_group_head_id');
                                        $fromData['chart_of_accounts_id'] = $this->getRequest()->getPost('from_chart_of_accounts_id');
                                        $toData['budget_ledger_head_id'] = $this->getRequest()->getPost('to_budget_ledger_head_id');
                                        $toData['accounts_group_head_id'] = $this->getRequest()->getPost('to_accounts_group_head_id');
                                        $toData['chart_of_accounts_id'] = $this->getRequest()->getPost('to_chart_of_accounts_id');
                                        $fromDataDetails = $this->budgetingService->getReappropriationDetails($tableName = 'budget_proposal', $fromData);
                                        $toDataDetails = $this->budgetingService->getReappropriationDetails($tableName = 'budget_proposal', $toData);
                    }
                }

		 $budgetForm = new BudgetReappropriationForm();
		 $chartAccounts = $this->budgetingService->listSelectData($tableName = 'chart_of_accounts', $columnName ='account_code', $condition = NULL);
		
                return array(
			'form' => $form,
                        'budgetForm' => $budgetForm,
			'toDataDetails' => $toDataDetails,
			'fromDataDetails' => $fromDataDetails,
			'chartAccounts' => $chartAccounts,
			'organisation_id' => $this->organisation_id
			);
	}
	
	/*
	* add Budget Reappropriation
	*/
	
	public function addBudgetReappropriationAction()
	{
		
		$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
		$form = new BudgetReappropriationSelectForm($dbAdapter);
		$budgetingModel = new BudgetReappropriationSelect();
		$form->bind($budgetingModel);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 $fromData = array();
				 $toData = array();
				 $fromData['budget_ledger_head_id'] = $this->getRequest()->getPost('from_budget_ledger_head_id');
				 $fromData['accounts_group_head_id'] = $this->getRequest()->getPost('from_accounts_group_head_id');
				 $fromData['chart_of_accounts_id'] = $this->getRequest()->getPost('from_chart_of_accounts_id');
				 $toData['budget_ledger_head_id'] = $this->getRequest()->getPost('to_budget_ledger_head_id');
				 $toData['accounts_group_head_id'] = $this->getRequest()->getPost('to_accounts_group_head_id');
				 $toData['chart_of_accounts_id'] = $this->getRequest()->getPost('to_chart_of_accounts_id');
				 $fromDataDetails = $this->budgetingService->getReappropriationDetails($tableName = 'budget_proposal', $fromData);
				 $toDataDetails = $this->budgetingService->getReappropriationDetails($tableName = 'budget_proposal', $toData);
             }
         }

		 $budgetForm = new BudgetReappropriationForm();
		 $chartAccounts = $this->budgetingService->listSelectData($tableName = 'chart_of_accounts', $columnName ='account_code', $condition = NULL);

        return array(
			'budgetForm' => $budgetForm,
			'toDataDetails' => $toDataDetails,
			'fromDataDetails' => $fromDataDetails,
			'chartAccounts' => $chartAccounts);
	}
	
	public function updateBudgetReappropriationAction()
	{
		$form = new BudgetReappropriationForm($toIds=array(), $fromIds=array());
		$budgetingModel = new BudgetReappropriation();
		$form->bind($budgetingModel);
		//need to fix this
		$toData = $this->getRequest()->getPost('to_');
			 var_dump($toData);
			 die();
		/*
		
		for($i=1; $i<=3; $i++)
		{
			if($this->getRequest()->getPost('to_'.$i) != NULL)
			{
				$toData = $this->getRequest()->getPost('to_'.$i);
				$toId = $i;
			}
			if($this->getRequest()->getPost('from_'.$i) != NULL)
			{
				$fromData = $this->getRequest()->getPost('from_'.$i);
				$fromId = $i;
			}
		}*/
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 try {
					 $this->budgetingService->addBudgetReappropriation($budgetingModel, $toData, $fromData, $toId, $fromId);
					 $this->redirect()->toRoute('viewbudgetreappropriation');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
	}
	
	/*
	* View Budget Reappropriation
	*/
	
	public function viewBudgetReappropriationAction()
	{		
		$ledgerHeads = $this->budgetingService->listSelectData($tableName = 'budget_ledger_head', $columnName='ledger_head', $condition = NULL);
		$accountGroupHeads = $this->budgetingService->listSelectData($tableName = 'accounts_group_head', $columnName='group_head', $condition = NULL);
		$chartAccounts = $this->budgetingService->listSelectData($tableName = 'chart_of_accounts', $columnName='account_code', $condition = NULL);
		
		$budget = $this->budgetingService->listBudgetReappropriation($columnName = 'from_proposal_id');
		 
        return array(
			'ledgerHeads' => $ledgerHeads,
			'accountGroupHeads' => $accountGroupHeads,
			'budget' => $budget);
	} 
    
}

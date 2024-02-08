<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Budgeting\Controller;

use Zend\ServiceManager\ServiceLocatorInterface;
use Budgeting\Service\BudgetingServiceInterface;
use Budgeting\Model\CapitalBudgetProposal;
use Budgeting\Model\CapitalBudgetReappropriationSelect;
use Budgeting\Model\BudgetReappropriation;
use Budgeting\Form\CapitalBudgetProposalForm;
use Budgeting\Form\SubmitProposalForm;
use Budgeting\Form\CapitalBudgetReappropriationSelectForm;
use Budgeting\Form\CapitalBudgetReappropriationForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;

/**
 * Description of IndexController
 *
 * @author Mendrel
 */
class CapitalBudgetController extends AbstractActionController
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
	
	public function addCapitalBudgetProposalAction()
    {
		$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
		
		$form = new CapitalBudgetProposalForm($dbAdapter);
		$budgetingModel = new CapitalBudgetProposal();
		$form->bind($budgetingModel);
		
		$submitForm = new SubmitProposalForm();
		
		// Need to get activities instead of ledger heads
		//$ledgerHeads = $this->budgetingService->listSelectData($tableName = 'budget_ledger_head', $columnName='ledger_head', $condition = NULL);
		$broadHeadName = $this->budgetingService->listSelectData($tableName = 'broad_head_name', $columnName='broad_head_name', $condition = NULL);
		$objectCode = $this->budgetingService->listSelectData($tableName = 'object_code', $columnName='object_name', $condition = NULL);
		$departments = $this->budgetingService->listSelectData($tableName = 'departments', $columnName='department_name', $organisation_id=5);
		$activities = $this->budgetingService->listSelectData($tableName = 'awpa_objectives_activity', $columnName='activity_name', $condition=NULL);
		
		$budgetProposals = $this->budgetingService->listBudgetProposal($tableName = 'budget_proposal_capital', $status = 'Not Submitted');
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 //getting data from ajax. so need to extract them and pass as variables
				 $broad_head_name_id = $this->getRequest()->getPost('broad_head_name_id');
				 $object_code_id = $this->getRequest()->getPost('object_code_id');
                 try {
					 $this->budgetingService->saveCapitalBudgetProposal($budgetingModel, $broad_head_name_id, $object_code_id);
					 $this->redirect()->toRoute('capitalbudgetproposal');
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
			'activities' => $activities,
			'broadHeadName' => $broadHeadName,
			'objectCode' => $objectCode,
			'budgetProposals' => $budgetProposals, 
			'departments' => $departments,
			'organisation_id' => $this->organisation_id);
    }
	
	/*
	* The action is to view budget proposal
	*/
	
	public function viewCapitalBudgetProposalAction()
    {
        //get the proposal id
		$id = (int) $this->params()->fromRoute('id',0);
		
		$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
		
		$form = new CapitalBudgetProposalForm($dbAdapter);
		
		$budgetDetails = $this->budgetingService->findProposalDetail($tableName='budget_proposal_capital', $id);
		$budgetProposals = $this->budgetingService->listBudgetProposal($tableName = 'budget_proposal_capital', $status = 'Not Submitted');
		$organisationList = $this->budgetingService->listSelectData($tableName = 'organisation', 'organisation_name', NULL);
		
		 
        return array(
				'form' => $form,
				'budgetDetails' => $budgetDetails,
				'budgetProposals' => $budgetProposals,
				'organisationList' => $organisationList);
    }
	
	public function editCapitalBudgetProposalAction()
    {
		//get the proposal id
		$id = (int) $this->params()->fromRoute('id',0);
		
		$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
		
		$form = new CapitalBudgetProposalForm($dbAdapter);
		$budgetingModel = new CapitalBudgetProposal();
		$form->bind($budgetingModel);
		
		$submitForm = new SubmitProposalForm();
		
		$budgetDetails = $this->budgetingService->findProposalDetail($tableName='budget_proposal_capital', $id);;
		$broadHeadName = $this->budgetingService->listSelectData($tableName = 'broad_head_name', $columnName='broad_head_name', $condition = NULL);
		$objectCode = $this->budgetingService->listSelectData($tableName = 'object_code', $columnName='object_name', $condition = NULL);
		$departments = $this->budgetingService->listSelectData($tableName = 'departments', $columnName='department_name', $organisation_id=5);
		$activities = $this->budgetingService->listSelectData($tableName = 'awpa_objectives_activity', $columnName='activity_name', $condition=NULL);
		
		$budgetProposals = $this->budgetingService->listBudgetProposal($tableName = 'budget_proposal_capital', $status = 'Not Submitted');
		$organisationList = $this->budgetingService->listSelectData($tableName = 'organisation', 'organisation_name', NULL);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 //getting data from ajax. so need to extract them and pass as variables
				 $broad_head_name_id = $this->getRequest()->getPost('broad_head_name_id');
				 $object_code_id = $this->getRequest()->getPost('object_code_id');
                 try {
					 $this->budgetingService->saveCapitalBudgetProposal($budgetingModel, $broad_head_name_id, $object_code_id);
					 $this->redirect()->toRoute('capitalbudgetproposal');
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
			'budgetDetails' => $budgetDetails,
			'activities' => $activities,
			'broadHeadName' => $broadHeadName,
			'objectCode' => $objectCode,
			'budgetProposals' => $budgetProposals, 
			'departments' => $departments,
			'organisation_id' => $this->organisation_id,
			'organisationList' => $organisationList);
    }
	
	/*
	* The action is to view organisation budget proposal
	*/
	
	public function viewOrganisationCapitalBudgetProposalAction()
    {
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
		
		$form = new CapitalBudgetProposalForm($dbAdapter);
		$budgetingModel = new CapitalBudgetProposal();
		$form->bind($budgetingModel);
		
		$submitForm = new SubmitProposalForm();
		
		$budgetProposal = $this->budgetingService->listBudgetProposal($tableName = 'budget_proposal_capital', $status = 'Submitted to OVC');
		
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
	
	public function approvedCapitalBudgetProposalAction()
    {
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
		
		$form = new CapitalBudgetProposalForm($dbAdapter);
		$budgetingModel = new CapitalBudgetProposal();
		$form->bind($budgetingModel);
		
		$submitForm = new SubmitProposalForm();
		
		$budgetProposal = $this->budgetingService->listBudgetProposal($tableName = 'budget_proposal_capital', $status = 'Approved');
		
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
	
	public function updateCapitalBudgetProposalAction()
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
	* Apply Budget Reappropriation
	*/
	
	public function applyCapitalBudgetReappropriationAction()
	{
		$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
		//Reappropriation Details
                $form = new CapitalBudgetReappropriationSelectForm($dbAdapter);
                //After Submission of Previous form and getting values
                $reappropriationForm = new CapitalBudgetReappropriationSelectForm($dbAdapter);
		$budgetingModel = new CapitalBudgetReappropriationSelect();
		$reappropriationForm->bind($budgetingModel);
		
		$request = $this->getRequest();
                if ($request->isPost()) {
                    $reappropriationForm->setData($request->getPost());
                    if ($reappropriationForm->isValid()) {
                            $fromData = array();
                            $toData = array();
                            $fromData['activity_name'] = $this->getRequest()->getPost('from_activity_name_id');
                            $fromData['broad_head_name_id'] = $this->getRequest()->getPost('from_broad_head_name_id');
                            $fromData['object_code_id'] = $this->getRequest()->getPost('from_object_code_id');
                            $toData['activity_name'] = $this->getRequest()->getPost('to_activity_name_id');
                            $toData['broad_head_name_id'] = $this->getRequest()->getPost('to_broad_head_name_id');
                            $toData['object_code_id'] = $this->getRequest()->getPost('to_object_code_id');
                            $toDataDetails = $this->budgetingService->getReappropriationDetails($tableName = 'budget_proposal_capital', $toData);
                            $fromDataDetails = $this->budgetingService->getReappropriationDetails($tableName = 'budget_proposal_capital', $fromData);
                    }
                }
		 
		 $budgetForm = new CapitalBudgetReappropriationForm();
		 $chartAccounts = $this->budgetingService->listSelectData($tableName = 'chart_of_accounts', $columnName ='account_code', $condition = NULL);
		
                return array(
			'form' => $form,
                        'reappropriationForm' => $reappropriationForm,
                        'budgetForm' => $budgetForm,
			'toDataDetails' => $toDataDetails,
			'fromDataDetails' => $fromDataDetails,
			'chartAccounts' => $chartAccounts,
			'organisation_id' => $this->organisation_id);
	}
	
	/*
	* add Budget Reappropriation
	*/
	
	public function addCapitalBudgetReappropriationAction()
	{
		$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
		$form = new CapitalBudgetReappropriationSelectForm($dbAdapter);
		$budgetingModel = new CapitalBudgetReappropriationSelect();
		$form->bind($budgetingModel);
		
		$request = $this->getRequest();
                if ($request->isPost()) {
                    $form->setData($request->getPost());
                    if ($form->isValid()) {
                            $fromData = array();
                            $toData = array();
                            $fromData['activity_name'] = $this->getRequest()->getPost('from_activity_name_id');
                            $fromData['broad_head_name_id'] = $this->getRequest()->getPost('from_broad_head_name_id');
                            $fromData['object_code_id'] = $this->getRequest()->getPost('from_object_code_id');
                            $toData['activity_name'] = $this->getRequest()->getPost('to_activity_name_id');
                            $toData['broad_head_name_id'] = $this->getRequest()->getPost('to_broad_head_name_id');
                            $toData['object_code_id'] = $this->getRequest()->getPost('to_object_code_id');
                            $toDataDetails = $this->budgetingService->getReappropriationDetails($tableName = 'budget_proposal_capital', $toData);
                            $fromDataDetails = $this->budgetingService->getReappropriationDetails($tableName = 'budget_proposal_capital', $fromData);
                    }
                }
		 
		 $budgetForm = new CapitalBudgetReappropriationForm();
		 $chartAccounts = $this->budgetingService->listSelectData($tableName = 'chart_of_accounts', $columnName ='account_code', $condition = NULL);

                return array(
			'budgetForm' => $budgetForm,
			'toDataDetails' => $toDataDetails,
			'fromDataDetails' => $fromDataDetails,
			'chartAccounts' => $chartAccounts);
	}
	
	public function updateCapitalBudgetReappropriationAction()
	{
                $form = new CapitalBudgetReappropriationForm();
		$budgetingModel = new BudgetReappropriation();
		$form->bind($budgetingModel);
		$request = $this->getRequest();
                if ($request->isPost()) {
                    $form->setData($request->getPost());
                    if ($form->isValid()) {
                        var_dump($form);
                        die();
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
	
	public function viewCapitalBudgetReappropriationAction()
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

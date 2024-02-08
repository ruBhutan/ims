<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Budgeting\Controller;

//use Zend\ServiceManager\ServiceLocatorInterface;
use Budgeting\Service\BudgetingServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Budgeting\Model\BudgetProposal;
use Budgeting\Model\BudgetLedger;
use Budgeting\Model\BudgetReappropriationSelect;
use Budgeting\Model\BudgetReappropriation;
use Budgeting\Form\BudgetProposalForm;
use Budgeting\Form\BudgetLedgerForm;
use Budgeting\Form\SubmitProposalForm;
use Budgeting\Form\BudgetReappropriationSelectForm;
use Budgeting\Form\BudgetReappropriationForm;
use Budgeting\Form\EditBudgetReappropriationForm;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;
use Zend\Form\Element\Select;

/**
 * Description of IndexController
 *
 * @author Mendrel
 */
class CurrentBudgetController extends AbstractActionController
{
    
	protected $budgetingService;
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
	
	public function __construct(BudgetingServiceInterface $budgetingService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->budgetingService = $budgetingService;
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
		
		$empData = $this->budgetingService->getUserDetailsId($this->username, $this->usertype);
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
	
	/*
	* The action is for budget proposal
	*/
	
	public function addBudgetProposalAction()
    {
    	$this->loginDetails();
    	
		$form = new BudgetProposalForm($this->serviceLocator);
		$budgetingModel = new BudgetProposal();
		$form->bind($budgetingModel);
		
		$submitForm = new SubmitProposalForm();
		
		$ledgerHeads = $this->budgetingService->listSelectData($tableName = 'budget_ledger_head', $columnName='ledger_head', $condition = NULL);
		$accountGroupHeads = $this->budgetingService->listSelectData($tableName = 'accounts_group_head', $columnName='group_head', $condition = NULL);
		$chartAccounts = $this->budgetingService->listSelectData($tableName = 'chart_of_accounts', $columnName='account_code', $condition = NULL);
		$departments = $this->budgetingService->listSelectData($tableName = 'departments', $columnName='department_name', $this->organisation_id);
		
		$budgetProposals = $this->budgetingService->listBudgetProposal($tableName = 'budget_proposal', $status = 'Not Submitted', $this->organisation_id);

		$organisation_list = $this->budgetingService->listSelectData($tableName = 'organisation', $columnName = 'organisation_name', NULL);
		$five_year_plan_list = $this->budgetingService->listAll($tableName = 'five_year_plan');
		foreach($five_year_plan_list as $key => $value){
			$five_year_plan = $value['five_year_plan'];
		}

		$date = date('m');
		if($date >=1 && $date <= 6){
            $start_year = date('Y')-1;
            $end_year = date('Y');
            $financial_year = $start_year.'-'.$end_year;
        }else{
            $start_year = date('Y');
            $end_year = date('Y')+1;
            $financial_year = $start_year.'-'.$end_year;
        }

		$message = NULL;
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) { 
				 //getting data from ajax. so need to extract them and pass as variables
				 $chart_of_accounts_id = $this->getRequest()->getPost('chart_of_accounts_id');
				 $accounts_group_head_id = $this->getRequest()->getPost('accounts_group_head_id');
                 try {
					 $this->budgetingService->saveBudgetProposal($budgetingModel, $chart_of_accounts_id, $accounts_group_head_id, NULL);
					 $this->auditTrailService->saveAuditTrail("INSERT", "Budget Proposal", "ALL", "SUCCESS");
					 $this->flashMessenger()->addMessage('Budget proposal was successfully added');
					 return $this->redirect()->toRoute('budgetproposal');
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
			'organisation_list' => $organisation_list,
			'five_year_plan' => $five_year_plan,
			'financial_year' => $financial_year,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
			'departments' => $departments,
		);
    }
	
	/*
	* The action is to view budget proposal
	*/
	
	public function viewBudgetProposalAction()
    {
        //get the budget proposal id
		$this->loginDetails();

		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);

		if(is_numeric($id)){
			$form = new BudgetProposalForm($this->serviceLocator);
			$budgetingModel = new BudgetProposal();
			$form->bind($budgetingModel);
			
			$budgetDetail = $this->budgetingService->findProposalDetail($tableName = 'budget_proposal', $id);
			$budgetProposal = $this->budgetingService->listBudgetProposal($tableName = 'budget_proposal', $status = 'Approved', $this->organisation_id);
			$organisation_list = $this->budgetingService->listSelectData($tableName = 'organisation', $columnName = 'organisation_name', NULL);
			$department_list = $this->budgetingService->listSelectData($tableName = 'departments', $columnName = 'department_name', NULL);

			
	        return array(
					'form' => $form,
					'budgetDetail' => $budgetDetail,
					'budgetProposal' => $budgetProposal,
					'organisation_list' => $organisation_list,
					'department_list' => $department_list,
				);
		}else{
			return $this->redirect()->toRoute('budgetproposal');
		}
    }


    public function editCurrentBudgetProposalAction()
    {
    	$this->loginDetails();
		//get the budget ledger id
	   $id_from_route = $this->params()->fromRoute('id',0);
	   $id = $this->my_decrypt($id_from_route, $this->keyphrase);
	   
	   $role_type_from_route = $this->params()->fromRoute('role_type');
		$role_type = $this->my_decrypt($role_type_from_route, $this->keyphrase); 
      
      if (is_numeric($id)){ 
			$form = new BudgetProposalForm($this->serviceLocator);
			$budgetingModel = new BudgetProposal();
			$form->bind($budgetingModel);

			$ledgerHeads = $this->budgetingService->listSelectData($tableName = 'budget_ledger_head', $columnName='ledger_head', $condition = NULL);
			$accountGroupHeads = $this->budgetingService->listSelectData($tableName = 'accounts_group_head', $columnName='group_head', $condition = NULL);
			$chartAccounts = $this->budgetingService->listSelectData($tableName = 'chart_of_accounts', $columnName='account_code', $condition = NULL);
			$budgetProposalDetails = $this->budgetingService->findProposalDetail($tableName = 'budget_proposal', $id);
			foreach($budgetProposalDetails as $details){
				$organisation_id = $details['organisation_id'];
			}
			$departments = $this->budgetingService->listSelectData($tableName = 'departments', $columnName='department_name', $organisation_id);
			
			$budgetProposals = $this->budgetingService->listBudgetProposal($tableName = 'budget_proposal', $status = 'Not Submitted', $this->organisation_id);

			$organisation_list = $this->budgetingService->listSelectData($tableName = 'organisation', $columnName = 'organisation_name', NULL);
			
			$budgetDetail = $this->budgetingService->findProposalDetail($tableName = 'budget_proposal', $id);
			$budgetProposal = $this->budgetingService->listBudgetProposal($tableName = 'budget_proposal', $status = 'Approved', $this->organisation_id);
			$organisation_list = $this->budgetingService->listSelectData($tableName = 'organisation', $columnName = 'organisation_name', NULL);
			$department_list = $this->budgetingService->listSelectData($tableName = 'departments', $columnName = 'department_name', NULL);

			$message = NULL;
		
			$request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) {
					 //getting data from ajax. so need to extract them and pass as variables
					 $chart_of_accounts_id = $this->getRequest()->getPost('chart_of_accounts_id');
					 $accounts_group_head_id = $this->getRequest()->getPost('accounts_group_head_id');
	                 try {
						 $this->budgetingService->saveBudgetProposal($budgetingModel, $chart_of_accounts_id, $accounts_group_head_id, $role_type);
						 $this->auditTrailService->saveAuditTrail("UPDATE", "Budget Proposal", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage('Budget proposal was successfully edited');

						 if($role_type == 'INDIVIDUAL'){
							return $this->redirect()->toRoute('budgetproposal');
						 }else{
							return $this->redirect()->toRoute('orgbudgetproposal');
						 }
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
				'budgetDetail' => $budgetDetail,
				'budgetProposal' => $budgetProposal,
				'organisation_list' => $organisation_list,
				'department_list' => $department_list,
				'ledgerHeads' => $ledgerHeads,
				'accountGroupHeads' => $accountGroupHeads,
				'chartAccounts' => $chartAccounts,
				'budgetProposals' => $budgetProposals,
				'organisation_id' => $this->organisation_id,
				'keyphrase' => $this->keyphrase,
				'message' => $message,
				'departments' => $departments,
				);
		}else{
			if($role_type == 'INDIVIDUAL'){
				return $this->redirect()->toRoute('budgetproposal');
			 }else{
				return $this->redirect()->toRoute('orgbudgetproposal');
			 }
		}
    }


    public function deleteCurrentBudgetProposalAction()
    {
    	$this->loginDetails();
         
         //get the id of the travel authorization proposal
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){ 
             try {
                 $result = $this->budgetingService->deleteCurrentBudgetProposal($id);
                 $this->auditTrailService->saveAuditTrail("DELETE", "Budget Proposal", "ALL", "SUCCESS");
                 $this->flashMessenger()->addMessage("You have successfully deleted the budget proposal");
                 return $this->redirect()->toRoute('budgetproposal');
             }
             catch(\Exception $e) {
                     die($e->getMessage());
                     // Some DB Error happened, log it and let the user know
             }
        }else {
        	return $this->redirect()->toRoute('budgetproposal');
           
    	}
	}

	
	public function approvedBudgetProposalAction()
    {
		$this->loginDetails();
		
		$form = new BudgetProposalForm($this->serviceLocator);
		$budgetingModel = new BudgetProposal();
		$form->bind($budgetingModel);
		
		$budgetProposal = $this->budgetingService->listBudgetProposal($tableName = 'budget_proposal', $status = 'Approved', $this->organisation_id);
		
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
				'budgetProposal' => $budgetProposal,
				'keyphrase' => $this->keyphrase,
			);
    }
	
	/*
	* The action is to view organisation budget proposal
	*/
	
	public function viewOrganisationBudgetProposalAction()
    {
		$this->loginDetails();
        //$dbAdapter1 = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
		$form = new BudgetProposalForm($this->serviceLocator);
		$budgetingModel = new BudgetProposal();
		$form->bind($budgetingModel);
		
		$submitForm = new SubmitProposalForm();
		
		$budgetProposal = $this->budgetingService->listBudgetProposal($tableName = 'budget_proposal', $status = 'Submitted to OVC', $this->organisation_id);

		$message = NULL;
		
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
				'submitForm' => $submitForm,
				'budgetProposal' => $budgetProposal,
				'keyphrase' => $this->keyphrase,
				'message' => $message,
			);
    }
	
	/*
	* The action is for update the Budget Ledger/Programmes
	*/
	
	public function updateBudgetProposalAction()
	{
		//Value 1 is change of status from "Not Submitted" to "Submitted to HR"
		//Value 2 is change of status from "Submitted to HR" to "Submitted to OVC"
		//need to take care of organisation as well
		$message = NULL;
		
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
			 $this->budgetingService->updateBudgetProposal('budget_proposal', $status, $previousStatus, $this->organisation_id);
			 if($status = 'Approved'){
				$this->flashMessenger()->addMessage('Budget proposal was successfully approved');
			 }else{
				$this->flashMessenger()->addMessage('Budget proposal was successfully submitted');
			 }
			 return $this->redirect()->toRoute('orgbudgetproposal');
		 }
		 catch(\Exception $e) {
				 die($e->getMessage());
				 // Some DB Error happened, log it and let the user know
		 } 
        return array(
			'message' => $message,

		);
	}
    
	/*
	* The action is for setting the Budget Ledger/Programmes
	*/
	
	public function addBudgetLedgerAction()
	{
		$this->loginDetails();

		$form = new BudgetLedgerForm();
		$budgetingModel = new BudgetLedger();
		$form->bind($budgetingModel);
		
		$ledgerHeads = $this->budgetingService->listBudgetLedger($tableName = 'budget_ledger_head');

		$organisation_list = $this->budgetingService->listSelectData($tableName = 'organisation', $columnName = 'organisation_name', NULL);
		$department_list = $this->budgetingService->listSelectData($tableName = 'departments', $columnName = 'department_name', $this->organisation_id);

		$message = NULL;
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) { 
                 try {
					 $this->budgetingService->saveBudgetLedger($budgetingModel);
					 $this->auditTrailService->saveAuditTrail("INSERT", "Budget Ledger Head", "ALL", "SUCCESS");

					 $this->flashMessenger()->addMessage('Budget Ledger was successfully added');
					 return $this->redirect()->toRoute('budgetledger');
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
			'departments_id' => $this->departments_id,
			'organisation_list' => $organisation_list,
			'department_list' => $department_list,
			'ledgerHeads' => $ledgerHeads,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
		);
	}
	
	public function editBudgetLedgerAction()
	{
		$this->loginDetails();
		//get the budget ledger id
	   $id_from_route = $this->params()->fromRoute('id',0);
       $id = $this->my_decrypt($id_from_route, $this->keyphrase);
      
      if (is_numeric($id)){
      	$form = new BudgetLedgerForm();
		$budgetingModel = new BudgetLedger();
		$form->bind($budgetingModel);
		
		$ledgerDetails = $this->budgetingService->findBudgetLedger($id);
		$ledgerHeads = $this->budgetingService->listBudgetLedger($tableName = 'budget_ledger_head');

		$organisation_list = $this->budgetingService->listSelectData($tableName = 'organisation', $columnName = 'organisation_name', NULL);
		$department_list = $this->budgetingService->listSelectData($tableName = 'departments', $columnName = 'department_name', $this->organisation_id);

		$message = NULL;
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->budgetingService->saveBudgetLedger($budgetingModel);
					 $this->auditTrailService->saveAuditTrail("UPDATE", "Budget Ledger Head", "ALL", "SUCCESS");

					 $this->flashMessenger()->addMessage('Budget Ledger was successfully edited');
					 return $this->redirect()->toRoute('budgetledger');
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
			'ledgerDetails' => $ledgerDetails,
			'organisation_id' => $this->organisation_id,
			'departments_id' => $this->departments_id,
			'organisation_list' => $organisation_list,
			'department_list' => $department_list,
			'ledgerHeads' => $ledgerHeads,
			'message' => $message,
		);
      }else{
      	return $this->redirect()->toRoute('budgetledger');
      }
	}
    
	/*
	* The action is for viewing the Budget Ledger/Programmes
	*/
	
	public function viewBudgetLedgerAction()
	{
		$this->loginDetails();
		//get the budget ledger id
	   $id_from_route = $this->params()->fromRoute('id',0);
       $id = $this->my_decrypt($id_from_route, $this->keyphrase);
      
      if (is_numeric($id)){
      	$form = new BudgetLedgerForm();
		$budgetingModel = new BudgetLedger();
		$form->bind($budgetingModel);
		
		$ledgerDetails = $this->budgetingService->findBudgetLedger($id);
		$ledgerHeads = $this->budgetingService->listBudgetLedger($tableName = 'budget_ledger_head');

		$organisation_list = $this->budgetingService->listSelectData($tableName = 'organisation', $columnName = 'organisation_name', NULL);
		$department_list = $this->budgetingService->listSelectData($tableName = 'departments', $columnName = 'department_name', $this->organisation_id);
		
        return array(
			'form' => $form,
			'ledgerDetails' => $ledgerDetails,
			'organisation_id' => $this->organisation_id,
			'organisation_list' => $organisation_list,
			'department_list' => $department_list,
			'ledgerHeads' => $ledgerHeads,
		);
      }else{
      	return $this->redirect()->toRoute('budgetledger');
      }
	}
	
	/*
	* Apply Budget Reappropriation
	*/
	
	public function applyBudgetReappropriationAction()
	{
		$this->loginDetails();
		
		$form = new BudgetReappropriationSelectForm($this->serviceLocator);         
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
					try{
						$budgetReappropriationData = $this->budgetingService->addBudgetReappropriation($budgetingModel, $toData['chart_of_accounts_id'], $fromData['chart_of_accounts_id']);
						$lastGeneratedId = $budgetReappropriationData->getId();
						$this->auditTrailService->saveAuditTrail("INSERT", "Current Budget Reappropriation", "ALL", "SUCCESS");
						$this->flashMessenger()->addMessage('Budget Reappropriation has been added');
						return $this->redirect()->toRoute('addbudgetreappropriation', array('id' => $this->my_encrypt($lastGeneratedId, $this->keyphrase)));
						}
						catch(\Exception $e) {
							die($e->getMessage());
							// Some DB Error happened, log it and let the user know
				}
			}
		}
		
		return array(
			'form' => $form,
			'budgetForm' => $budgetForm,
			'organisation_id' => $this->organisation_id,
			'message' => $message,
			'keyphrase' => $this->keyphrase,
			);
	}
	
	/*
	* add Budget Reappropriation
	*/
	
	public function addBudgetReappropriationAction()
	{
		$this->loginDetails();

		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){ 
			$form = new BudgetReappropriationSelectForm($this->serviceLocator);
			$budgetingModel = new BudgetReappropriationSelect();
			$form->bind($budgetingModel);

			$budgetReappropriationDetails = $this->budgetingService->getBudgetReappropriationDetails($id);
			$fromId = array();
			$toId = array();
			foreach($budgetReappropriationDetails as $details){
				$fromId[] = $details['from_proposal_id'];
				$toId[] = $details['to_proposal_id'];
			}

			$toDataDetails = $this->budgetingService->getReappropriationDetails($tableName = 'budget_proposal', $toId);
			$fromDataDetails = $this->budgetingService->getReappropriationDetails($tableName = 'budget_proposal', $fromId);

			$budgetForm = new BudgetReappropriationForm($id);

			$message = NULL;
			
			$request = $this->getRequest();
			if ($request->isPost()) {
				$form->setData($request->getPost());
				if ($form->isValid()) {
					/*$fromData = array();
					$toData = array();
					$fromData['budget_ledger_head_id'] = $this->getRequest()->getPost('from_budget_ledger_head_id');
					$fromData['accounts_group_head_id'] = $this->getRequest()->getPost('from_accounts_group_head_id');
					$fromData['chart_of_accounts_id'] = $this->getRequest()->getPost('from_chart_of_accounts_id');
					$toData['budget_ledger_head_id'] = $this->getRequest()->getPost('to_budget_ledger_head_id');
					$toData['accounts_group_head_id'] = $this->getRequest()->getPost('to_accounts_group_head_id');
					$toData['chart_of_accounts_id'] = $this->getRequest()->getPost('to_chart_of_accounts_id');
					$fromDataDetails = $this->budgetingService->getReappropriationDetails($tableName = 'budget_proposal', $fromData);
					$toDataDetails = $this->budgetingService->getReappropriationDetails($tableName = 'budget_proposal', $toData);*/
				}
			}

			$chartAccounts = $this->budgetingService->listSelectData($tableName = 'chart_of_accounts', $columnName ='account_code', $condition = NULL);

			return array(
				'id' => $id,
				'fromData' => $fromData,
				'toData' => $toData,
				'budgetForm' => $budgetForm,
				'toDataDetails' => $toDataDetails,
				'fromDataDetails' => $fromDataDetails,
				'chartAccounts' => $chartAccounts,
				'message' => $message,
				'keyphrase' => $this->keyphrase,
			);
		}else{
			return $this->redirect()->toRoute('addbudgetreappropriation', array('id' => $this->my_encrypt($lastGeneratedId, $this->keyphrase)));
		}		
	}
	
	public function updateBudgetReappropriationAction()
	{
		$this->loginDetails();

		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new BudgetReappropriationForm($id);
			$budgetingModel = new BudgetReappropriation();
			$form->bind($budgetingModel);
			
			$request = $this->getRequest();
				if ($request->isPost()) {
					$form->setData($request->getPost());
					if ($form->isValid()) { 
						$from_amount = $this->extractFormData($id);
						$to_amount = $this->extractFormData1($id); 
						try {
							$this->budgetingService->updateBudgetReappropriation($budgetingModel, $from_amount, $to_amount);
							$this->auditTrailService->saveAuditTrail("UPDATE", "Current Budget Reappropriation", "ALL", "SUCCESS");
							$this->flashMessenger()->addMessage('Budget proposal was successfully submitted');
							return $this->redirect()->toRoute('budgetreappropriation');
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
					'keyphrase' => $this->keyphrase,
				);
			}
		else{
			return $this->redirect()->toRoute('viewbudgetreappropriation');
		}
	}


	/*
	* View Budget Reappropriation
	*/
	
	public function viewOrgCurrentBudgetReappropriationAction()
	{	
		$this->loginDetails();

		$ledgerHeads = $this->budgetingService->listSelectData($tableName = 'budget_ledger_head', $columnName='ledger_head', $condition = NULL);
		$accountGroupHeads = $this->budgetingService->listSelectData($tableName = 'accounts_group_head', $columnName='group_head', $condition = NULL);
		$chartAccounts = $this->budgetingService->listSelectData($tableName = 'chart_of_accounts', $columnName='account_code', $condition = NULL);
		
		$budget = $this->budgetingService->listBudgetReappropriation($columnName = 'from_proposal_id', 'current', $this->organisation_id);

		$message = NULL;
		 
        return array(
			'ledgerHeads' => $ledgerHeads,
			'accountGroupHeads' => $accountGroupHeads,
			'budget' => $budget,
			'message' => $message,
			'keyphrase' => $this->keyphrase,
		);
	} 


	public function editBudgetReappropriationAction()
	{
		$this->loginDetails();

		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
			$form = new EditBudgetReappropriationForm();
			$budgetingModel = new BudgetReappropriation();
			$form->bind($budgetingModel); 
			
			$budgetReappropriation = $this->budgetingService->findReappropriationBudgetTransactions($budgetType='current', $id);

			$toDataDetails = $this->budgetingService->getBudgetReappropriationDetailsList($tableName = 'budget_proposal', $type = 'to', $id);
			$fromDataDetails = $this->budgetingService->getBudgetReappropriationDetailsList($tableName = 'budget_proposal', $type = 'from', $id);
								
			$request = $this->getRequest();
			if ($request->isPost()) {
				$form->setData($request->getPost());
				if ($form->isValid()) {  
					try {
						$this->budgetingService->updateEditedBudgetReappropriation($budgetingModel);
						$this->auditTrailService->saveAuditTrail("UPDATE", "Current Budget Reappropriation", "ALL", "SUCCESS");
					 	$this->flashMessenger()->addMessage('Budget reappropriation proposal was successfully edited');
						return $this->redirect()->toRoute('vieworgcurrentbudgetreappropriation');
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
				'budgetReappropriation' => $budgetReappropriation,
				'toDataDetails' => $toDataDetails,
				'fromDataDetails' => $fromDataDetails,
				'keyphrase' => $this->keyphrase,
			);
		}else{
			return $this->redirect()->toRoute('vieworgcurrentbudgetreappropriation');
		}
	}
	
	/*
	* View Budget Reappropriation
	*/
	
	public function viewBudgetReappropriationAction()
	{
		$this->loginDetails();
				
		$ledgerHeads = $this->budgetingService->listSelectData($tableName = 'budget_ledger_head', $columnName='ledger_head', $condition = NULL);
		$accountGroupHeads = $this->budgetingService->listSelectData($tableName = 'accounts_group_head', $columnName='group_head', $condition = NULL);
		$chartAccounts = $this->budgetingService->listSelectData($tableName = 'chart_of_accounts', $columnName='account_code', $condition = NULL);
		
		$budget = $this->budgetingService->listBudgetReappropriation($columnName = 'from_proposal_id', 'current', NULL);

		$message = NULL;
		 
        return array(
			'ledgerHeads' => $ledgerHeads,
			'accountGroupHeads' => $accountGroupHeads,
			'budget' => $budget,
			'message' => $message,
			'keyphrase' => $this->keyphrase,
		);
	}
	
	
	public function viewBudgetReappropriationDetailAction()
	{
		$this->loginDetails();

		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
			$form = new EditBudgetReappropriationForm();
			$budgetingModel = new BudgetReappropriation();
			$form->bind($budgetingModel); 
			
			$budgetReappropriation = $this->budgetingService->findReappropriationBudgetTransactions($budgetType='current', $id);

			$toDataDetails = $this->budgetingService->getBudgetReappropriationDetailsList($tableName = 'budget_proposal', $type = 'to', $id);
			$fromDataDetails = $this->budgetingService->getBudgetReappropriationDetailsList($tableName = 'budget_proposal', $type = 'from', $id);
								
			return array(
				'id' => $id,
				'form' => $form,
				'budgetReappropriation' => $budgetReappropriation,
				'toDataDetails' => $toDataDetails,
				'fromDataDetails' => $fromDataDetails,
				'keyphrase' => $this->keyphrase,
			);
		}else{
			return $this->redirect()->toRoute('vieworgcurrentbudgetreappropriation');
		}
	}


	public function extractFormData($id)
	{ 
		//$fromAmountData = NULL;
        
        $fromAmountData= $this->getRequest()->getPost('from_amount'.$id);
        
        return $fromAmountData;
	}


	public function extractFormData1($id)
	{ 
		//$toAmountData = NULL;

		$toAmountData= $this->getRequest()->getPost('to_amount'.$id);

        return $toAmountData;
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

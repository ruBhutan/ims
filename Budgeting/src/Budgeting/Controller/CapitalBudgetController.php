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
use Budgeting\Model\CapitalBudgetProposal;
use Budgeting\Model\CapitalBudgetReappropriationSelect;
use Budgeting\Model\BudgetReappropriation;
use Budgeting\Form\CapitalBudgetProposalForm;
use Budgeting\Form\SubmitProposalForm;
use Budgeting\Form\CapitalBudgetReappropriationSelectForm;
use Budgeting\Form\CapitalBudgetReappropriationForm;
use Budgeting\Form\EditBudgetReappropriationForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;
use Zend\Form\Element\Select;

/**
 * Description of IndexController
 *
 * @author Mendrel
 */
class CapitalBudgetController extends AbstractActionController
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
	
	public function addCapitalBudgetProposalAction()
    { 
		//$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
		$this->loginDetails();

		$form = new CapitalBudgetProposalForm($this->serviceLocator);
		$budgetingModel = new CapitalBudgetProposal();
		$form->bind($budgetingModel);
		
		$submitForm = new SubmitProposalForm();
		
		// Need to get activities instead of ledger heads
		//$ledgerHeads = $this->budgetingService->listSelectData($tableName = 'budget_ledger_head', $columnName='ledger_head', $condition = NULL);
		$broadHeadName = $this->budgetingService->listSelectData($tableName = 'broad_head_name', $columnName='broad_head_name', $condition = NULL);
		$objectCode = $this->budgetingService->listSelectData($tableName = 'object_code', $columnName='object_name', $condition = NULL);
		$departments = $this->budgetingService->listSelectData($tableName = 'departments', $columnName='department_name', $this->organisation_id);
		$activities = $this->budgetingService->listSelectData($tableName = 'awpa_objectives_activity', $columnName='activity_name', $condition=NULL);
		
		$budgetProposals = $this->budgetingService->listBudgetProposal($tableName = 'budget_proposal_capital', $status = 'Not Submitted', $this->organisation_id);

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
				 $broad_head_name_id = $this->getRequest()->getPost('broad_head_name_id');
				 $object_code_id = $this->getRequest()->getPost('object_code_id');
                 try {
					 $this->budgetingService->saveCapitalBudgetProposal($budgetingModel, $broad_head_name_id, $object_code_id, NULL);
					 $this->auditTrailService->saveAuditTrail("INSERT", "Budget Proposal Capital", "ALL", "SUCCESS");
					 $this->flashMessenger()->addMessage('Budget proposal was successfully added');
					 return $this->redirect()->toRoute('capitalbudgetproposal');
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
	
	public function viewCapitalBudgetProposalAction()
    {
        //get the proposal id
		$this->loginDetails();

		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);

		if(is_numeric($id)){ 
			$form = new CapitalBudgetProposalForm($this->serviceLocator);
			
			$budgetDetails = $this->budgetingService->findProposalDetail($tableName='budget_proposal_capital', $id);
			$budgetProposals = $this->budgetingService->listBudgetProposal($tableName = 'budget_proposal_capital', $status = 'Not Submitted', $this->organisation_id);
			$organisationList = $this->budgetingService->listSelectData($tableName = 'organisation', 'organisation_name', NULL);
			$departments = $this->budgetingService->listSelectData($tableName = 'departments', $columnName='department_name', $this->organisation_id);
			
			return array(
				'id' => $id,
				'form' => $form,
				'budgetDetails' => $budgetDetails,
				'budgetProposals' => $budgetProposals,
				'organisationList' => $organisationList,
				'departments' => $departments,
			);
		}else{
			return $this->redirect()->toRoute('capitalbudgetproposal');
		}
    }
	
	public function editCapitalBudgetProposalAction()
    {
		//get the proposal id
		$this->loginDetails();

		$id_from_route = $this->params()->fromRoute('id');
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);

		$role_type_from_route = $this->params()->fromRoute('role_type');
		$role_type = $this->my_decrypt($role_type_from_route, $this->keyphrase); 

		if(is_numeric($id)){
			$form = new CapitalBudgetProposalForm($this->serviceLocator);
			$budgetingModel = new CapitalBudgetProposal();
			$form->bind($budgetingModel);
			
			$submitForm = new SubmitProposalForm();
			
			$budgetDetails = $this->budgetingService->findProposalDetail($tableName='budget_proposal_capital', $id);
			$broadHeadName = $this->budgetingService->listSelectData($tableName = 'broad_head_name', $columnName='broad_head_name', $condition = NULL);
			$objectCode = $this->budgetingService->listSelectData($tableName = 'object_code', $columnName='object_name', $condition = NULL);
			//Call function to get organisation id
			$organisation_id = NULL;
			$budgetProposalDetails = $this->budgetingService->findProposalDetail($tableName='budget_proposal_capital', $id);
			foreach($budgetProposalDetails as $details){
				$organisation_id = $details['organisation_id'];
			}
			$departments = $this->budgetingService->listSelectData($tableName = 'departments', $columnName='department_name', $organisation_id);
			$activities = $this->budgetingService->listSelectData($tableName = 'awpa_objectives_activity', $columnName='activity_name', $condition=NULL);
			
			$budgetProposals = $this->budgetingService->listBudgetProposal($tableName = 'budget_proposal_capital', $status = 'Not Submitted', $this->organisation_id);
			$organisationList = $this->budgetingService->listSelectData($tableName = 'organisation', 'organisation_name', NULL);
			
			$request = $this->getRequest();
			if ($request->isPost()) {
				$form->setData($request->getPost());
				if ($form->isValid()) {
					//getting data from ajax. so need to extract them and pass as variables
					$broad_head_name_id = $this->getRequest()->getPost('broad_head_name_id');
					$object_code_id = $this->getRequest()->getPost('object_code_id');
					try {
						$this->budgetingService->saveCapitalBudgetProposal($budgetingModel, $broad_head_name_id, $object_code_id, $role_type);
						$this->auditTrailService->saveAuditTrail("UPDATE", "Budget Proposal Capital", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage('Budget proposal was successfully edited');
						 
						 if($role_type == 'INDIVIDUAL'){
							return $this->redirect()->toRoute('capitalbudgetproposal');
						 }else{
							return $this->redirect()->toRoute('orgcapitalbudgetproposal');
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
				'submitForm' => $submitForm,
				'budgetDetails' => $budgetDetails,
				'activities' => $activities,
				'broadHeadName' => $broadHeadName,
				'objectCode' => $objectCode,
				'budgetProposals' => $budgetProposals, 
				'departments' => $departments,
				'organisation_id' => $this->organisation_id,
				'organisationList' => $organisationList);
		}else{
			if($role_type == 'INDIVIDUAL'){
				return $this->redirect()->toRoute('capitalbudgetproposal');
			 }else{
				return $this->redirect()->toRoute('orgcapitalbudgetproposal');
			 }
		}
	}
	

	public function deleteCapitalBudgetProposalAction()
    {
    	$this->loginDetails();
         
         //get the id of the travel authorization proposal
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){ 
             try {
                 $result = $this->budgetingService->deleteCapitalBudgetProposal($id);
                 $this->auditTrailService->saveAuditTrail("DELETE", "Budget Proposal Capital", "ALL", "SUCCESS");
                 $this->flashMessenger()->addMessage("You have successfully deleted the budget proposal");
                 return $this->redirect()->toRoute('capitalbudgetproposal');
             }
             catch(\Exception $e) {
                     die($e->getMessage());
                     // Some DB Error happened, log it and let the user know
             }
        }else {
        	return $this->redirect()->toRoute('capitalbudgetproposal');
           
    	}
	}
	
	/*
	* The action is to view organisation budget proposal
	*/
	
	public function viewOrganisationCapitalBudgetProposalAction()
    {
		$this->loginDetails();

		$form = new CapitalBudgetProposalForm($this->serviceLocator);
		$budgetingModel = new CapitalBudgetProposal();
		$form->bind($budgetingModel);
		
		$submitForm = new SubmitProposalForm();
		
		$budgetProposal = $this->budgetingService->listBudgetProposal($tableName = 'budget_proposal_capital', $status = 'Submitted to OVC', $this->organisation_id);

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
				'message' => $message,
				'keyphrase' => $this->keyphrase,
			);
    }
	
	public function approvedCapitalBudgetProposalAction()
    {
		$this->loginDetails();

		$form = new CapitalBudgetProposalForm($this->serviceLocator);
		$budgetingModel = new CapitalBudgetProposal();
		$form->bind($budgetingModel);
		
		$submitForm = new SubmitProposalForm();
		
		$budgetProposal = $this->budgetingService->listBudgetProposal($tableName = 'budget_proposal_capital', $status = 'Approved', NULL);
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
				'message' => $message,
				'keyphrase' => $this->keyphrase,
			);
    }
	
	/*
	* The action is for update the Budget Ledger/Programmes
	*/
	
	public function updateCapitalBudgetProposalAction()
	{
		$this->loginDetails();
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
		//$organisation_id = 1;
		
	
		 try {
			 $this->budgetingService->updateBudgetProposal('budget_proposal_capital', $status, $previousStatus, $this->organisation_id);
			 if($status = 'Approved'){
				$this->flashMessenger()->addMessage('Budget proposal was successfully Approved');
			 }else{
				$this->flashMessenger()->addMessage('Budget proposal was successfully Submitted');
			 }
			 return $this->redirect()->toRoute('orgcapitalbudgetproposal');
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
	* Apply Budget Reappropriation
	*/
	
	public function applyCapitalBudgetReappropriationAction()
	{
		$this->loginDetails();

		$form = new CapitalBudgetReappropriationSelectForm($this->serviceLocator);
		$budgetingModel = new CapitalBudgetReappropriationSelect();
		$form->bind($budgetingModel);

		$message = NULL;

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
					
				try{ 
					$budgetReappropriationData = $this->budgetingService->addCapitalBudgetReappropriation($budgetingModel, $toData['object_code_id'], $fromData['object_code_id']);
					$lastGeneratedId = $budgetReappropriationData->getId();
					$this->auditTrailService->saveAuditTrail("INSERT", "Capital Budget Reappropriation", "ALL", "SUCCESS");
					$this->flashMessenger()->addMessage('Budget Reappropriation has been added');
					return $this->redirect()->toRoute('addcapitalbudgetreappropriation', array('id' => $this->my_encrypt($lastGeneratedId, $this->keyphrase)));
					}
					catch(\Exception $e) {
						die($e->getMessage());
						// Some DB Error happened, log it and let the user know
					}
				}
			}
		
        return array(
			'form' => $form,
			'fromData' => $fromData,
			'toData' => $toData,
			'budgetForm' => $budgetForm,
			/*'toDataDetails' => $toDataDetails,
			'fromDataDetails' => $fromDataDetails,*/
			'organisation_id' => $this->organisation_id,
			'message' => $message,
			'keyphrase' => $this->keyphrase,
		);
	}
	
	/*
	* add Budget Reappropriation
	*/
	
	public function addCapitalBudgetReappropriationAction()
	{
		$this->loginDetails();

		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){ 
			$form = new CapitalBudgetReappropriationSelectForm($this->serviceLocator);
			$budgetingModel = new CapitalBudgetReappropriationSelect();
			$form->bind($budgetingModel);

			$budgetReappropriationDetails = $this->budgetingService->getBudgetReappropriationDetails($id);
			$fromId = array();
			$toId = array();
			foreach($budgetReappropriationDetails as $details){
				$fromId[] = $details['from_proposal_id'];
				$toId[] = $details['to_proposal_id'];
			}

			$toDataDetails = $this->budgetingService->getReappropriationDetails($tableName = 'budget_proposal_capital', $toId);
			$fromDataDetails = $this->budgetingService->getReappropriationDetails($tableName = 'budget_proposal_capital', $fromId);

			$budgetForm = new CapitalBudgetReappropriationForm($id);

			$message = NULL;
			
			$request = $this->getRequest();
			if ($request->isPost()) {
				$form->setData($request->getPost());
				if ($form->isValid()) {
						/*$fromData = array();
						$toData = array();
						$fromData['activity_name'] = $this->getRequest()->getPost('from_activity_name_id');
						$fromData['broad_head_name_id'] = $this->getRequest()->getPost('from_broad_head_name_id');
						$fromData['object_code_id'] = $this->getRequest()->getPost('from_object_code_id');
						$toData['activity_name'] = $this->getRequest()->getPost('to_activity_name_id');
						$toData['broad_head_name_id'] = $this->getRequest()->getPost('to_broad_head_name_id');
						$toData['object_code_id'] = $this->getRequest()->getPost('to_object_code_id'); */
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
			return $this->redirect()->toRoute('addcapitalbudgetreappropriation', array('id' => $this->my_encrypt($lastGeneratedId, $this->keyphrase)));
		}
	}
	
	public function updateCapitalBudgetReappropriationAction()
	{
		$this->loginDetails();

		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new CapitalBudgetReappropriationForm($id);
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
						$this->auditTrailService->saveAuditTrail("UPDATE", "Capital Budget Reappropriation", "ALL", "SUCCESS");
					 	$this->flashMessenger()->addMessage('Budget proposal was successfully submitted');
						return $this->redirect()->toRoute('capitalbudgetreappropriation');
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
		}else{
			return $this->redirect()->toRoute('viewcapitalbudgetreappropriation');
		}
	}

	/*
	* View Budget Reappropriation
	*/
	
	public function viewOrgCapitalBudgetReappropriationAction()
	{	
		$this->loginDetails();

		$ledgerHeads = $this->budgetingService->listSelectData($tableName = 'budget_ledger_head', $columnName='ledger_head', $condition = NULL);
		$accountGroupHeads = $this->budgetingService->listSelectData($tableName = 'accounts_group_head', $columnName='group_head', $condition = NULL);
		$chartAccounts = $this->budgetingService->listSelectData($tableName = 'chart_of_accounts', $columnName='account_code', $condition = NULL);
		
		$budget = $this->budgetingService->listBudgetReappropriation($columnName = 'from_proposal_id', 'capital', $this->organisation_id);

		$message = NULL;
		 
        return array(
			'ledgerHeads' => $ledgerHeads,
			'accountGroupHeads' => $accountGroupHeads,
			'budget' => $budget,
			'message' => $message,
			'keyphrase' => $this->keyphrase,
		);
	} 


	public function editCapitalBudgetReappropriationAction()
	{
		$this->loginDetails();

		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
			$form = new EditBudgetReappropriationForm();
			$budgetingModel = new BudgetReappropriation();
			$form->bind($budgetingModel); 
			
			$budgetReappropriation = $this->budgetingService->findReappropriationBudgetTransactions($budgetType='capital', $id);

			$toDataDetails = $this->budgetingService->getBudgetReappropriationDetailsList($tableName = 'budget_proposal_capital', $type = 'to', $id);
			$fromDataDetails = $this->budgetingService->getBudgetReappropriationDetailsList($tableName = 'budget_proposal_capital', $type = 'from', $id);
								
			$request = $this->getRequest();
			if ($request->isPost()) {
				$form->setData($request->getPost());
				if ($form->isValid()) {  
					try {
						$this->budgetingService->updateEditedBudgetReappropriation($budgetingModel);
						$this->auditTrailService->saveAuditTrail("UPDATE", "Capital Budget Reappropriation", "ALL", "SUCCESS");
					 	$this->flashMessenger()->addMessage('Budget reappropriation proposal was successfully edited');
						return $this->redirect()->toRoute('vieworgcapitalbudgetreappropriation');
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
			return $this->redirect()->toRoute('vieworgcapitalbudgetreappropriation');
		}
	}
	
	/*
	* View Budget Reappropriation
	*/
	
	public function viewCapitalBudgetReappropriationAction()
	{	
		$this->loginDetails();

		$ledgerHeads = $this->budgetingService->listSelectData($tableName = 'budget_ledger_head', $columnName='ledger_head', $condition = NULL);
		$accountGroupHeads = $this->budgetingService->listSelectData($tableName = 'accounts_group_head', $columnName='group_head', $condition = NULL);
		$chartAccounts = $this->budgetingService->listSelectData($tableName = 'chart_of_accounts', $columnName='account_code', $condition = NULL);
		
		$budget = $this->budgetingService->listBudgetReappropriation($columnName = 'from_proposal_id', 'capital', NULL);
		 
        return array(
			'ledgerHeads' => $ledgerHeads,
			'accountGroupHeads' => $accountGroupHeads,
			'budget' => $budget,
			'keyphrase' => $this->keyphrase,
		);
	} 

	public function viewCapitalBudgetReappropriationDetailAction()
	{
		$this->loginDetails();

		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
			$form = new EditBudgetReappropriationForm();
			$budgetingModel = new BudgetReappropriation();
			$form->bind($budgetingModel); 
			
			$budgetReappropriation = $this->budgetingService->findReappropriationBudgetTransactions($budgetType='capital', $id);

			$toDataDetails = $this->budgetingService->getBudgetReappropriationDetailsList($tableName = 'budget_proposal_capital', $type = 'to', $id);
			$fromDataDetails = $this->budgetingService->getBudgetReappropriationDetailsList($tableName = 'budget_proposal_capital', $type = 'from', $id);
								

			return array(
				'id' => $id,
				'form' => $form,
				'budgetReappropriation' => $budgetReappropriation,
				'toDataDetails' => $toDataDetails,
				'fromDataDetails' => $fromDataDetails,
				'keyphrase' => $this->keyphrase,
			);
		}else{
			return $this->redirect()->toRoute('vieworgcapitalbudgetreappropriation');
		}
	}

	public function updateBudgetReappropriationProposalAction()
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
                $this->budgetingService->updateBudgetReappropriationProposal($status,$id, $tableName = 'budget_reappropriation');
                $this->auditTrailService->saveAuditTrail("UPDATE", "Budget Reappropriation", "status", "SUCCESS");
                $this->flashMessenger()->addMessage('You have successfully '.$status.' the reappropriation budget');

                if($type == 'capital'){
                    return $this->redirect()->toRoute('viewcapitalbudgetreappropriation');
                }else{
                    return $this->redirect()->toRoute('viewbudgetreappropriation');
                }
            }
            catch(\Exception $e) {
                    die($e->getMessage());
                    // Some DB Error happened, log it and let the user know
            }
        }else{
            if($type == 'capital'){
                return $this->redirect()->toRoute('viewcapitalbudgetreappropriation');
            }else{
                return $this->redirect()->toRoute('viewbudgetreappropriation');
            }
        }

        return array(
			'message' => $message,
            'keyphrase' => $this->keyphrase,
        );
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

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ChequeManagement\Controller;

use Zend\ServiceManager\ServiceLocatorInterface;
use ChequeManagement\Service\ChequeManagementServiceInterface;
use ChequeManagement\Model\ChequeRegistration;
use ChequeManagement\Form\ChequeRegistrationForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;

/**
 * Description of IndexController
 *
 * @author Mendrel
 */
class ChequeManagementController extends AbstractActionController
{
    
	protected $chequeService;
	protected $username;
	protected $employee_details_id;
	protected $organisation_id;
	
	public function __construct(ChequeManagementServiceInterface $chequeService)
	{
		$this->chequeService = $chequeService;
		
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
		
		$empData = $this->chequeService->getUserDetailsId($this->username);
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			}
			
		//get the organisation id
		$organisationID = $this->chequeService->getOrganisationId($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}
	}
	
	public function registerChequeBookAction()
	{
		$form = new ChequeRegistrationForm();
		$chequeModel = new ChequeRegistration();
		$form->bind($chequeModel);
		
		$chequeList = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {				 
					 $this->chequeService->saveChequeRegistration($chequeModel);
					 $this->redirect()->toRoute('addfinancialinstitutions');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'chequeList' => $chequeList
			));
	}
	
	public function addFinancialInstitutionsAction()
	{
		$form = new FinancialInstitutionForm();
		$chequeModel = new FinancialInstitution();
		$form->bind($chequeModel);
		
		//$institutionList = $this->chequeService->listAll('finanical_institutions');
		$institutionList = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {				 
					 $this->chequeService->saveChequeManagement($chequeModel);
					 $this->redirect()->toRoute('addfinancialinstitutions');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'institutionList' => $institutionList
			));
	}
	
	public function editFinancialInstitutionsAction()
	{
		//get the cheque id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$form = new FinancialInstitutionForm();
		$chequeModel = new FinancialInstitution();
		$form->bind($chequeModel);
		
		//$institutionList = $this->chequeService->listAll('finanical_institutions');
		$institutionList = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {				 
					 $this->chequeService->saveChequeManagement($chequeModel);
					 $this->redirect()->toRoute('addfinancialinstitutions');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'institutionList' => $institutionList
			));
	}
	
	public function addBankDetailsAction()
	{
		$form = new BankDetailsForm();
		$chequeModel = new BankDetails();
		$form->bind($chequeModel);
		
		//$institutionList = $this->chequeService->listAll('finanical_institutions');
		$institutionList = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {				 
					 $this->chequeService->saveChequeManagement($chequeModel);
					 $this->redirect()->toRoute('addbankdetails');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'institutionList' => $institutionList
			));
	}
	
	public function editBankDetailsAction()
	{
		//get the cheque id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$form = new BankDetailsForm();
		$chequeModel = new BankDetails();
		$form->bind($chequeModel);
		
		//$institutionList = $this->chequeService->listAll('finanical_institutions');
		$institutionList = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {				 
					 $this->chequeService->saveChequeManagement($chequeModel);
					 $this->redirect()->toRoute('addbankdetails');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'institutionList' => $institutionList
			));
	}
    
	public function addVoucherMasterAction()
	{
		$form = new VoucherMasterForm();
		$chequeModel = new VoucherMaster();
		$form->bind($chequeModel);
		
		//$institutionList = $this->chequeService->listAll('finanical_institutions');
		$institutionList = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {				 
					 $this->chequeService->saveChequeManagement($chequeModel);
					 $this->redirect()->toRoute('addvouchermaster');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'institutionList' => $institutionList
			));
	}
	
	public function editVoucherMasterAction()
	{
		//get the cheque id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$form = new VoucherMasterForm();
		$chequeModel = new VoucherMaster();
		$form->bind($chequeModel);
		
		//$institutionList = $this->chequeService->listAll('finanical_institutions');
		$institutionList = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {				 
					 $this->chequeService->saveChequeManagement($chequeModel);
					 $this->redirect()->toRoute('addvouchermaster');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'institutionList' => $institutionList
			));
	}
	
	public function addFixedDeductionsAction()
	{
		$form = new FixedDeductionsForm();
		$chequeModel = new FixedDeductions();
		$form->bind($chequeModel);
		
		//$institutionList = $this->chequeService->listAll('finanical_institutions');
		$institutionList = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {				 
					 $this->chequeService->saveChequeManagement($chequeModel);
					 $this->redirect()->toRoute('addvouchermaster');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'institutionList' => $institutionList
			));
	}
	
	public function editFixedDeductionsAction()
	{
		//get the cheque id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$form = new FixedDeductionsForm();
		$chequeModel = new FixedDeductions();
		$form->bind($chequeModel);
		
		//$institutionList = $this->chequeService->listAll('finanical_institutions');
		$institutionList = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {				 
					 $this->chequeService->saveChequeManagement($chequeModel);
					 $this->redirect()->toRoute('addvouchermaster');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'institutionList' => $institutionList
			));
	}
	
	public function addFloatingDeductionsTypeAction()
	{
		$form = new FloatingDeductionsTypeForm();
		$chequeModel = new FloatingDeductionsType();
		$form->bind($chequeModel);
		
		//$institutionList = $this->chequeService->listAll('finanical_institutions');
		$institutionList = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {				 
					 $this->chequeService->saveChequeManagement($chequeModel);
					 $this->redirect()->toRoute('addfloatingdeductionstype');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'institutionList' => $institutionList
			));
	}
	
	public function editFloatingDeductionsTypeAction()
	{
		//get the cheque id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$form = new FloatingDeductionsTypeForm();
		$chequeModel = new FloatingDeductionsType();
		$form->bind($chequeModel);
		
		//$institutionList = $this->chequeService->listAll('finanical_institutions');
		$institutionList = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {				 
					 $this->chequeService->saveChequeManagement($chequeModel);
					 $this->redirect()->toRoute('addfloatingdeductionstype');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'institutionList' => $institutionList
			));
	}
	
	public function addFloatingDeductionsAction()
	{
		$form = new FloatingDeductionsForm();
		$chequeModel = new FloatingDeductions();
		$form->bind($chequeModel);
		
		//$institutionList = $this->chequeService->listAll('finanical_institutions');
		$institutionList = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {				 
					 $this->chequeService->saveChequeManagement($chequeModel);
					 $this->redirect()->toRoute('addfloatingdeductionstype');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'institutionList' => $institutionList
			));
	}
	
	public function editFloatingDeductionsAction()
	{
		//get the cheque id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$form = new FloatingDeductionsForm();
		$chequeModel = new FloatingDeductions();
		$form->bind($chequeModel);
		
		//$institutionList = $this->chequeService->listAll('finanical_institutions');
		$institutionList = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {				 
					 $this->chequeService->saveChequeManagement($chequeModel);
					 $this->redirect()->toRoute('addfloatingdeductionstype');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'institutionList' => $institutionList
			));
	}
}

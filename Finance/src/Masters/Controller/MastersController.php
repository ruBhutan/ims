<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Masters\Controller;

use Zend\ServiceManager\ServiceLocatorInterface;
use Masters\Service\MastersServiceInterface;
use Masters\Model\FinancialInstitution;
use Masters\Model\VoucherMaster;
use Masters\Model\FixedDeductions;
use Masters\Model\FloatingDeductionsType;
use Masters\Model\FloatingDeductions;
use Masters\Model\BankDetails;
use Masters\Form\FinancialInstitutionForm;
use Masters\Form\VoucherMasterForm;
use Masters\Form\FixedDeductionsForm;
use Masters\Form\FloatingDeductionsForm;
use Masters\Form\FloatingDeductionsTypeForm;
use Masters\Form\BankDetailsForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;

/**
 * Description of IndexController
 *
 * @author Mendrel
 */
class MastersController extends AbstractActionController
{
    
	protected $mastersService;
	protected $username;
	protected $employee_details_id;
	protected $organisation_id;
	
	public function __construct(MastersServiceInterface $mastersService)
	{
		$this->mastersService = $mastersService;
		
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
		
		$empData = $this->mastersService->getUserDetailsId($this->username);
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			}
			
		//get the organisation id
		$organisationID = $this->mastersService->getOrganisationId($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}
	}
	
	//only to view staff details
	//add, edit etc. in HR Management Module
	public function viewStaffDetailsAction()
	{
		
	}
	
	//view the various position level and their pay scale etc
	//add, edit etc. in HR Management Module
	public function viewStaffPositionLevelAction()
	{
		
	}
	
	public function addFinancialInstitutionsAction()
	{
		$form = new FinancialInstitutionForm();
		$mastersModel = new FinancialInstitution();
		$form->bind($mastersModel);
		
		//$institutionList = $this->mastersService->listAll('finanical_institutions');
		$institutionList = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {				 
					 $this->mastersService->saveMasters($mastersModel);
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
		//get the masters id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$form = new FinancialInstitutionForm();
		$mastersModel = new FinancialInstitution();
		$form->bind($mastersModel);
		
		//$institutionList = $this->mastersService->listAll('finanical_institutions');
		$institutionList = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {				 
					 $this->mastersService->saveMasters($mastersModel);
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
		$mastersModel = new BankDetails();
		$form->bind($mastersModel);
		
		//$institutionList = $this->mastersService->listAll('finanical_institutions');
		$institutionList = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {				 
					 $this->mastersService->saveMasters($mastersModel);
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
		//get the masters id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$form = new BankDetailsForm();
		$mastersModel = new BankDetails();
		$form->bind($mastersModel);
		
		//$institutionList = $this->mastersService->listAll('finanical_institutions');
		$institutionList = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {				 
					 $this->mastersService->saveMasters($mastersModel);
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
		$mastersModel = new VoucherMaster();
		$form->bind($mastersModel);
		
		//$institutionList = $this->mastersService->listAll('finanical_institutions');
		$institutionList = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {				 
					 $this->mastersService->saveMasters($mastersModel);
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
		//get the masters id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$form = new VoucherMasterForm();
		$mastersModel = new VoucherMaster();
		$form->bind($mastersModel);
		
		//$institutionList = $this->mastersService->listAll('finanical_institutions');
		$institutionList = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {				 
					 $this->mastersService->saveMasters($mastersModel);
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
		$mastersModel = new FixedDeductions();
		$form->bind($mastersModel);
		
		//$institutionList = $this->mastersService->listAll('finanical_institutions');
		$institutionList = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {				 
					 $this->mastersService->saveMasters($mastersModel);
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
		//get the masters id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$form = new FixedDeductionsForm();
		$mastersModel = new FixedDeductions();
		$form->bind($mastersModel);
		
		//$institutionList = $this->mastersService->listAll('finanical_institutions');
		$institutionList = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {				 
					 $this->mastersService->saveMasters($mastersModel);
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
		$mastersModel = new FloatingDeductionsType();
		$form->bind($mastersModel);
		
		//$institutionList = $this->mastersService->listAll('finanical_institutions');
		$institutionList = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {				 
					 $this->mastersService->saveMasters($mastersModel);
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
		//get the masters id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$form = new FloatingDeductionsTypeForm();
		$mastersModel = new FloatingDeductionsType();
		$form->bind($mastersModel);
		
		//$institutionList = $this->mastersService->listAll('finanical_institutions');
		$institutionList = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {				 
					 $this->mastersService->saveMasters($mastersModel);
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
		$mastersModel = new FloatingDeductions();
		$form->bind($mastersModel);
		
		//$institutionList = $this->mastersService->listAll('finanical_institutions');
		$institutionList = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {				 
					 $this->mastersService->saveMasters($mastersModel);
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
		//get the masters id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$form = new FloatingDeductionsForm();
		$mastersModel = new FloatingDeductions();
		$form->bind($mastersModel);
		
		//$institutionList = $this->mastersService->listAll('finanical_institutions');
		$institutionList = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {				 
					 $this->mastersService->saveMasters($mastersModel);
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

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Voucher\Controller;

use Zend\ServiceManager\ServiceLocatorInterface;
use Voucher\Service\VoucherServiceInterface;
use Voucher\Model\Voucher;
use Voucher\Form\VoucherForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;

/**
 * Description of IndexController
 *
 * @author Mendrel
 */
class VoucherController extends AbstractActionController
{
    
	protected $voucherService;
	protected $username;
	protected $employee_details_id;
	protected $organisation_id;
	
	public function __construct(VoucherServiceInterface $voucherService)
	{
		$this->voucherService = $voucherService;
		
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
		
		$empData = $this->voucherService->getUserDetailsId($this->username);
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			}
			
		//get the organisation id
		$organisationID = $this->voucherService->getOrganisationId($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}
	}
	
	public function addVoucherAction()
	{
		$form = new VoucherForm();
		$voucherModel = new Voucher();
		$form->bind($voucherModel);
		
		$voucherList = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {				 
					 $this->voucherService->saveVoucher($voucherModel);
					 $this->redirect()->toRoute('addvoucher');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'voucherList' => $voucherList
			));
	}
	
	public function editVoucherAction()
	{
		//get the voucher id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$form = new VoucherForm();
		$voucherModel = new Voucher();
		$form->bind($voucherModel);
		
		$voucherList = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {				 
					 $this->voucherService->saveVoucher($voucherModel);
					 $this->redirect()->toRoute('addvoucher');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'voucherList' => $voucherList
			));
	}
	
	public function voucherVerificationAction()
	{
		//get the voucher id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$form = new FinancialInstitutionForm();
		$voucherModel = new FinancialInstitution();
		$form->bind($voucherModel);
		
		//$institutionList = $this->voucherService->listAll('finanical_institutions');
		$institutionList = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {				 
					 $this->voucherService->saveVoucher($voucherModel);
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
	
	public function addFinancialInstitutionsAction()
	{
		$form = new FinancialInstitutionForm();
		$voucherModel = new FinancialInstitution();
		$form->bind($voucherModel);
		
		//$institutionList = $this->voucherService->listAll('finanical_institutions');
		$institutionList = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {				 
					 $this->voucherService->saveVoucher($voucherModel);
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
		//get the voucher id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$form = new FinancialInstitutionForm();
		$voucherModel = new FinancialInstitution();
		$form->bind($voucherModel);
		
		//$institutionList = $this->voucherService->listAll('finanical_institutions');
		$institutionList = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {				 
					 $this->voucherService->saveVoucher($voucherModel);
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
}

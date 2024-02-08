<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PayrollManagement\Controller;

use Zend\ServiceManager\ServiceLocatorInterface;
use PayrollManagement\Service\PayrollManagementServiceInterface;
use PayrollManagement\Model\FinancialInstitution;
use PayrollManagement\Model\VoucherMaster;
use PayrollManagement\Model\FixedDeductions;
use PayrollManagement\Model\FloatingDeductionsType;
use PayrollManagement\Model\FloatingDeductions;
use PayrollManagement\Model\BankDetails;
use PayrollManagement\Form\FinancialInstitutionForm;
use PayrollManagement\Form\VoucherMasterForm;
use PayrollManagement\Form\FixedDeductionsForm;
use PayrollManagement\Form\FloatingDeductionsForm;
use PayrollManagement\Form\FloatingDeductionsTypeForm;
use PayrollManagement\Form\BankDetailsForm;
use PayrollManagement\Form\SearchForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;

/**
 * Description of IndexController
 *
 * @author Mendrel
 */
class PayrollManagementController extends AbstractActionController
{
    
	protected $payrollService;
	protected $username;
	protected $employee_details_id;
	protected $organisation_id;
	
	public function __construct(PayrollManagementServiceInterface $payrollService)
	{
		$this->payrollService = $payrollService;
		
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
		
		$empData = $this->payrollService->getUserDetailsId($this->username);
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			}
			
		//get the organisation id
		$organisationID = $this->payrollService->getOrganisationId($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}
	}
		
	//only to view staff details
	//add, edit etc. in HR Management Module
	public function viewStaffPayDetailsAction()
	{
		$form = new SearchForm();
		$departmentList = $this->payrollService->listSelectData($tableName = 'departments', $columnName='department_name', $organisation_id = $this->organisation_id);
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$department_name = $this->getRequest()->getPost('department_name');
				//$department_unit = $this->getRequest()->getPost('department_unit');
				$employeeList = $this->payrollService->getEmployeeList($department_name, $department_unit=NULL);
             }
         }
		 else {
			 $employeeList = array();
			 $department_name = NULL;
			 $department_unit = NULL;
		 }
		
		return new ViewModel(array(
            'form' => $form,
			'departmentList' => $departmentList,
			'employeeList' => $employeeList
            ));
	}
	
	public function addStaffFloatingDeductionsAction()
	{
		$form = new FinancialInstitutionForm();
		$payrollModel = new FinancialInstitution();
		$form->bind($payrollModel);
		
		//$institutionList = $this->payrollService->listAll('finanical_institutions');
		$institutionList = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {				 
					 $this->payrollService->savePayrollManagement($payrollModel);
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
	
	public function editStaffFloatingDeductionsAction()
	{
		//get the payroll id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$form = new FinancialInstitutionForm();
		$payrollModel = new FinancialInstitution();
		$form->bind($payrollModel);
		
		//$institutionList = $this->payrollService->listAll('finanical_institutions');
		$institutionList = array();
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {				 
					 $this->payrollService->savePayrollManagement($payrollModel);
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

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace FinanceCodes\Controller;

use FinanceCodes\Service\FinanceCodesServiceInterface;
use FinanceCodes\Model\ChartAccounts;
use FinanceCodes\Model\AccountsGroupHead;
use FinanceCodes\Model\BroadHeadName;
use FinanceCodes\Model\ObjectCode;
use FinanceCodes\Form\ChartAccountsForm;
use FinanceCodes\Form\AccountsGroupHeadForm;
use FinanceCodes\Form\BroadHeadNameForm;
use FinanceCodes\Form\ObjectCodeForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Description of IndexController
 *
 * @author Mendrel
 */
class FinanceCodesController extends AbstractActionController
{
    
	protected $codesService;
	
	public function __construct(FinanceCodesServiceInterface $codesService)
	{
		$this->codesService = $codesService;
	}
	
	/*
	* The action is for finance codes
	*/
	
	public function addBroadHeadNameAction()
    {
		$form = new BroadHeadNameForm();
		$codesModel = new BroadHeadName();
		$form->bind($codesModel);
		
		$broadHeadName = $this->codesService->listAll($tableName = 'broad_head_name');
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->codesService->saveBroadHeadName($codesModel);
					 $this->redirect()->toRoute('broadheadname');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'broadHeadName' => $broadHeadName);
    }
	
	public function editBroadHeadNameAction()
    {
		//get the id of the broad head name
		$id = (int) $this->params()->fromRoute('id', 0);

		$form = new BroadHeadNameForm();
		$codesModel = new BroadHeadName();
		$form->bind($codesModel);
		
		$headDetails = $this->codesService->findFinanceCode($tableName = 'broad_head_name' , $id);
		$broadHeadName = $this->codesService->listAll($tableName = 'broad_head_name');
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->codesService->saveBroadHeadName($codesModel);
					 $this->redirect()->toRoute('broadheadname');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'headDetails' => $headDetails,
			'broadHeadName' => $broadHeadName);
    }
	
	/*
	* The action is for finance codes
	*/
	
	public function addObjectCodeAction()
    {
		$form = new ObjectCodeForm();
		$codesModel = new ObjectCode();
		$form->bind($codesModel);
		
		$broadHeadList = $this->codesService->listSelectData($tableName = 'broad_head_name', $columnName = 'broad_head_name');
		$objectCode = $this->codesService->listAll($tableName = 'object_code');
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->codesService->saveObjectCode($codesModel);
					 $this->redirect()->toRoute('objectcode');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'broadHeadList' => $broadHeadList,
			'objectCode' => $objectCode);
    }
	
	public function editObjectCodeAction()
    {
		//Get the object code id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$form = new ObjectCodeForm();
		$codesModel = new ObjectCode();
		$form->bind($codesModel);
		
		$broadHeadList = $this->codesService->listSelectData($tableName = 'broad_head_name', $columnName = 'broad_head_name');
		$codeDetails = $this->codesService->findFinanceCode($tableName = 'object_code', $id);
		$objectCode = $this->codesService->listAll($tableName = 'object_code');
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->codesService->saveObjectCode($codesModel);
					 $this->redirect()->toRoute('objectcode');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'broadHeadList' => $broadHeadList,
			'codeDetails' => $codeDetails,
			'objectCode' => $objectCode);
    }
	
	/*
	* The action is for finance codes
	*/
	
	public function addChartAccountsAction()
    {
		$form = new ChartAccountsForm();
		$codesModel = new ChartAccounts();
		$form->bind($codesModel);
		
		$chartAccounts = $this->codesService->listAll($tableName = 'chart_of_accounts');
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->codesService->saveChartAccounts($codesModel);
					 $this->redirect()->toRoute('chartaccounts');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'chartAccounts' => $chartAccounts);
    }
	
	public function editChartAccountsAction()
    {
		//get the chart of accounts id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$form = new ChartAccountsForm();
		$codesModel = new ChartAccounts();
		$form->bind($codesModel);
		
		$chartDetails = $this->codesService->findFinanceCode($tableName = 'chart_of_accounts' , $id);
		$chartAccounts = $this->codesService->listAll($tableName = 'chart_of_accounts');
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->codesService->saveChartAccounts($codesModel);
					 $this->redirect()->toRoute('chartaccounts');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'chartDetails' => $chartDetails,
			'chartAccounts' => $chartAccounts);
    }
	
	/*
	* The action is to view finance codes
	*/
	
	public function viewChartAccountsAction()
    {
        $form = new ChartAccountsForm();
		$codesModel = new ChartAccounts();
		$form->bind($codesModel);
		
		$chartAccounts = $this->codesService->listAll($tableName = 'chart_of_accounts');
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->codesService->save($codesModel);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'chartAccounts' => $chartAccounts);
    }
	
	public function addAccountsGroupHeadAction()
    {
		$form = new AccountsGroupHeadForm();
		$codesModel = new AccountsGroupHead();
		$form->bind($codesModel);
		
		$groupHeads = $this->codesService->listAll($tableName = 'accounts_group_head');
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 var_dump($form);
				 die();
                 try {
					 $this->codesService->saveAccountsGroupHead($codesModel);
					 $this->redirect()->toRoute('accountsgrouphead');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'groupHeads' => $groupHeads);
    }
	
	public function viewAccountsGroupHeadAction()
    {
		$form = new AccountsGroupHeadForm();
		$codesModel = new AccountsGroupHead();
		$form->bind($codesModel);
		
		$groupHeads = $this->codesService->listAll($tableName = 'accounts_group_head');
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 var_dump($form);
				 die();
                 try {
					 $this->codesService->saveAccountsGroupHead($codesModel);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'groupHeads' => $groupHeads);
    }
	
	public function editAccountsGroupHeadAction()
    {
		//get the group head id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$form = new AccountsGroupHeadForm();
		$codesModel = new AccountsGroupHead();
		$form->bind($codesModel);
		
		$headDetails = $this->codesService->findFinanceCode($tableName = 'accounts_group_head', $id);
		$groupHeads = $this->codesService->listAll($tableName = 'accounts_group_head');
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 var_dump($form);
				 die();
                 try {
					 $this->codesService->saveAccountsGroupHead($codesModel);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'headDetails' => $headDetails,
			'groupHeads' => $groupHeads);
    }
	
}

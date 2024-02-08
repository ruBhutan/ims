<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace FinanceCodes\Controller;

use FinanceCodes\Service\FinanceCodesServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
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
use Zend\Session\Container;
use Zend\Http\Response\Stream;
use Zend\Http\Headers;
use Zend\Paginator\Adapter\DbSelect;
use Zend\View\Model\JsonModel;
use Zend\Form\Element\Select;

/**
 * Description of IndexController
 *
 * @author Mendrel
 */
class FinanceCodesController extends AbstractActionController
{
    
	protected $codesService;
	protected $notificationService;
    protected $auditTrailService;
    protected $serviceLocator;
    protected $username;
    protected $userrole;
    protected $userregion;
    protected $userDetails;
    protected $userImage;
    protected $employee_details_id;
    protected $student_id;
    protected $organisation_id;
    protected $departments_id;
    protected $usertype;

    protected $keyphrase = "RUB_IMS";
	
	public function __construct(FinanceCodesServiceInterface $codesService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->codesService = $codesService;
		$this->notificationService = $notificationService;
        $this->auditTrailService = $auditTrailService;
        $this->serviceLocator = $serviceLocator;

        /*
         * To retrieve the user name from the session
        */
        $authPlugin = $serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
        $this->username = $authPlugin['username'];
        $this->userrole = $authPlugin['role'];
        $this->usertype = $authPlugin['user_type_id'];
        $this->userregion = $authPlugin['region'];

        $empData = $this->codesService->getUserDetailsId($this->username, $this->usertype);
            foreach($empData as $emp){
                $this->employee_details_id = $emp['id'];
                $this->organisation_id = $emp['organisation_id'];
                $this->departments_id = $emp['departments_id'];
                $this->userDetails = $emp['first_name'].' '.$emp['middle_name'].' '.$emp['last_name'];
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
	* The action is for finance codes
	*/
	
	public function addBroadHeadNameAction()
    {
    	$this->loginDetails();

		$form = new BroadHeadNameForm();
		$codesModel = new BroadHeadName();
		$form->bind($codesModel);
		
		$broadHeadName = $this->codesService->listAll($tableName = 'broad_head_name');

		$message = NULL;
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->codesService->saveBroadHeadName($codesModel);
					  $this->auditTrailService->saveAuditTrail("INSERT", "Broad Head Name", "ALL", "SUCCESS");
                        $this->flashMessenger()->addMessage('Broad Head was successfully added');
					 return $this->redirect()->toRoute('broadheadname');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'broadHeadName' => $broadHeadName,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
		);
    }
	
	public function editBroadHeadNameAction()
    {
		//get the id of the broad head name
		$this->loginDetails();

		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);

		if(is_numeric($id)){
			$form = new BroadHeadNameForm();
			$codesModel = new BroadHeadName();
			$form->bind($codesModel);
			
			$headDetails = $this->codesService->findFinanceCode($tableName = 'broad_head_name' , $id);
			$broadHeadName = $this->codesService->listAll($tableName = 'broad_head_name');

			$message = NULL;
			
			$request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) {
	                 try {
						 $this->codesService->saveBroadHeadName($codesModel);
						 $this->auditTrailService->saveAuditTrail("UPDATE", "Broad Head Name", "ALL", "SUCCESS");
                        $this->flashMessenger()->addMessage('Broad Head was successfully added');
					 return $this->redirect()->toRoute('broadheadname');
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
				'broadHeadName' => $broadHeadName,
				'message' => $message,
			);
		}else{
			return $this->redirect()->toRoute('broadheadname');
		}
    }
	
	/*
	* The action is for finance codes
	*/
	
	public function addObjectCodeAction()
    {
    	$this->loginDetails();

		$form = new ObjectCodeForm();
		$codesModel = new ObjectCode();
		$form->bind($codesModel);
		
		$broadHeadList = $this->codesService->listSelectData($tableName = 'broad_head_name', $columnName = 'broad_head_name');
		$objectCode = $this->codesService->listAll($tableName = 'object_code');

		$message = NULL;
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->codesService->saveObjectCode($codesModel);
					 $this->auditTrailService->saveAuditTrail("INSERT", "Broad Head Name", "ALL", "SUCCESS");
                     $this->flashMessenger()->addMessage('Broad Head was successfully added');
					 return $this->redirect()->toRoute('objectcode');
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
			'objectCode' => $objectCode,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
		);
    }
	
	public function editObjectCodeAction()
    {
		//Get the object code id
		$this->loginDetails();

		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);

		if(is_numeric($id)){
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
						 $this->auditTrailService->saveAuditTrail("UPDATE", "Broad Head Name", "ALL", "SUCCESS");
                     	$this->flashMessenger()->addMessage('Broad Head was successfully added');
						 return $this->redirect()->toRoute('objectcode');
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
		}else{
			return $this->redirect()->toRoute('objectcode');
		}
    }
	
	/*
	* The action is for finance codes
	*/
	
	public function addChartAccountsAction()
    {
    	$this->loginDetails();

		$form = new ChartAccountsForm();
		$codesModel = new ChartAccounts();
		$form->bind($codesModel);
		
		$chartAccounts = $this->codesService->listAll($tableName = 'chart_of_accounts');
		$accountHeadList = $this->codesService->listSelectData($tableName = 'accounts_group_head', $columnName = 'group_head');

		$message = NULL;
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) { 
                 try {
					 $this->codesService->saveChartAccounts($codesModel);
					 $this->auditTrailService->saveAuditTrail("INSERT", "Chart of Account", "ALL", "SUCCESS");
                        $this->flashMessenger()->addMessage('Chart of Accounts was successfully added');
					 return $this->redirect()->toRoute('chartaccounts');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'chartAccounts' => $chartAccounts,
			'accountHeadList' => $accountHeadList,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
		);
    }
	
	public function editChartAccountsAction()
    {
		//get the chart of accounts id
		$this->loginDetails();

		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);

		if(is_numeric($id)){
			$form = new ChartAccountsForm();
			$codesModel = new ChartAccounts();
			$form->bind($codesModel);
			
			$chartDetails = $this->codesService->findFinanceCode($tableName = 'chart_of_accounts' , $id);
			$chartAccounts = $this->codesService->listAll($tableName = 'chart_of_accounts');
			$accountHeadList = $this->codesService->listSelectData($tableName = 'accounts_group_head', $columnName = 'group_head');

			$message = NULL;
			
			$request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) {
	                 try {
						 $this->codesService->saveChartAccounts($codesModel);
						 $this->auditTrailService->saveAuditTrail("UPDATE", "Chart of Account", "ALL", "SUCCESS");
                        $this->flashMessenger()->addMessage('Chart of Accounts was successfully edited');
					 	return $this->redirect()->toRoute('chartaccounts');
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
				'chartAccounts' => $chartAccounts,
				'accountHeadList' => $accountHeadList,
				'message' => $message,
			);
		}else{
			return $this->redirect()->toRoute('chartaccounts');
		}
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
    	$this->loginDetails();

		$form = new AccountsGroupHeadForm();
		$codesModel = new AccountsGroupHead();
		$form->bind($codesModel);
		
		$groupHeads = $this->codesService->listAll($tableName = 'accounts_group_head');

		$message = NULL;
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->codesService->saveAccountsGroupHead($codesModel);
					 $this->auditTrailService->saveAuditTrail("INSERT", "Accounts Group Head", "ALL", "SUCCESS");
                        $this->flashMessenger()->addMessage('Accounts Group Head was successfully added');
					 return $this->redirect()->toRoute('accountsgrouphead');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'groupHeads' => $groupHeads,
			'message' => $message,
			'keyphrase' => $this->keyphrase,
		);
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
		$this->loginDetails();
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);

		if(is_numeric($id)){
			$form = new AccountsGroupHeadForm();
			$codesModel = new AccountsGroupHead();
			$form->bind($codesModel);
			
			$headDetails = $this->codesService->findFinanceCode($tableName = 'accounts_group_head', $id);
			$groupHeads = $this->codesService->listAll($tableName = 'accounts_group_head');

			$message = NULL;
			
			$request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) {
	                 try {
						 $this->codesService->saveAccountsGroupHead($codesModel);
						 $this->auditTrailService->saveAuditTrail("UPDATE", "Accounts Group Head", "ALL", "SUCCESS");
                        $this->flashMessenger()->addMessage('Accounts Group Head was successfully edited');
					 	return $this->redirect()->toRoute('accountsgrouphead');
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
				'groupHeads' => $groupHeads,
				'message' => $message,
			);
		}else{
			return $this->redirect()->toRoute('accountsgrouphead');
		}
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

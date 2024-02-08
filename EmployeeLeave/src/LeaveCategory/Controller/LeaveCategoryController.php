<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace LeaveCategory\Controller;

use LeaveCategory\Service\LeaveCategoryServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Helper\AbstractHelper;
use LeaveCategory\Form\LeaveCategoryForm;
use LeaveCategory\Model\LeaveCategory;

/**
 * Description of IndexController
 *
 */
 
class LeaveCategoryController extends AbstractActionController
{
	protected $leaveService;
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

	
	public function __construct(LeaveCategoryServiceInterface $leaveService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->leaveService = $leaveService;
		$this->notificationService = $notificationService;
        $this->auditTrailService = $auditTrailService;
		
		/*
		 * To retrieve the user name from the session
		*/
		$authPlugin = $serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
        $this->username = $authPlugin['username'];
        $this->userrole = $authPlugin['role'];
        $this->userregion = $authPlugin['region'];
        $this->usertype = $authPlugin['user_type_id'];
		
		//get the user details such as name
		$this->userDetails = $this->leaveService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->leaveService->getUserImage($this->username, $this->usertype);
		
	}
	
	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }
    
	public function addEmployeeLeaveCategoryAction()
    {
        $this->loginDetails();
		
		$form = new LeaveCategoryForm();
		$leaveModel = new LeaveCategory();
		$form->bind($leaveModel);
		
		$leaveCategories = $this->leaveService->listAll($tableName='emp_leave_category');

		$message = NULL;
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->leaveService->save($leaveModel);
					 $this->flashMessenger()->addMessage('Leave Category was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "New Leave Category", "ALL", "SUCCESS");
					  return $this->redirect()->toRoute('addempleavecategory');
				 }
				 catch(\Exception $e) {
				 	$message = 'Failure';
				 	$this->flashMessenger()->addMessage($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'keyphrase' => $this->keyphrase,
			'leaveCategories' => $leaveCategories,
			'message' => $message,
			'keyphrase' => $this->keyphrase,
		);
    }
	
	public function editEmployeeLeaveCategoryAction()
    {
        $this->loginDetails();
		
		//get the student id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new LeaveCategoryForm();
			$leaveModel = new LeaveCategory();
			$form->bind($leaveModel);
			
			$leaveCategories = $this->leaveService->listAll($tableName='emp_leave_category');
			$categoryDetail = $this->leaveService->findLeaveCategory($id);

			$message = NULL;
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
						 $this->leaveService->save($leaveModel);
						 $this->flashMessenger()->addMessage('Leave Category was successfully edited');
						 $this->auditTrailService->saveAuditTrail("EDIT", "Employee Leave Category Edited", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('addempleavecategory');
					 }
					 catch(\Exception $e) {
							$message = 'Failure';
				 			$this->flashMessenger()->addMessage($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			 
			return array(
				'form' => $form,
				'keyphrase' => $this->keyphrase,
				'categoryDetail' => $categoryDetail,
				'leaveCategories' => $leaveCategories,
				'message' => $message,
			);
		} 
		else {
			return $this->redirect()->toRoute('addempleavecategory');
		}

		
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

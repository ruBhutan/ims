<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Hostel\Controller;

use Hostel\Service\HostelServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Hostel\Form\HostelForm;
use Hostel\Form\HostelApplicationForm;
use Hostel\Form\HostelAllocationForm;
use Hostel\Form\HostelInventoryForm;
use Hostel\Form\HostelRoomForm;
use Hostel\Form\SearchForm;
use Hostel\Model\Hostel;
use Hostel\Model\HostelApplication;
use Hostel\Model\HostelAllocation;
use Hostel\Model\HostelInventory;
use Hostel\Model\HostelRoom;
use Zend\Session\Container;

/**
 * Description of IndexController
 *
 */
 
class HostelController extends AbstractActionController
{
	protected $hostelService;
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
	protected $student_id;
	protected $organisation_id;

	protected $keyphrase = "RUB_IMS";
	
	public function __construct(HostelServiceInterface $hostelService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->hostelService = $hostelService;
		$this->notificationService = $notificationService;
        $this->auditTrailService = $auditTrailService;
		$this->serviceLocator = $serviceLocator;
		
		/*
         * To retrieve the user name from the session
        */
        $authPlugin = $this->serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
        // Use service locator to get the authPlugin
        $this->username = $authPlugin['username'];
		$this->userrole = $authPlugin['role'];
		$this->userregion = $authPlugin['region'];
		$this->usertype = $authPlugin['user_type_id'];		
		
		/*
		* Getting the student_id/employee_details_id related to username
		*/
		
		$empData = $this->hostelService->getUserDetailsId($this->username, $tableName = 'employee_details');
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			}
			
		if($this->employee_details_id == NULL)
		{
			$studentData = $this->hostelService->getUserDetailsId($this->username, $tableName = 'student');
			foreach($studentData as $std){
			$this->student_id = $std['id'];
			}
		}

		//get the organisation id
		if($this->employee_details_id == NULL)
			$organisationID = $this->hostelService->getOrganisationId($this->username, $tableName = 'student');
		else 
			$organisationID = $this->hostelService->getOrganisationId($this->username, $tableName = 'employee_details');
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}
	//get the user details such as name
        $this->userDetails = $this->hostelService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->hostelService->getUserImage($this->username, $this->usertype);
		
	}

	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }
    
	public function addHostelDetailAction()
    {
    	$this->loginDetails();
        $form = new HostelForm();
		$hostelModel = new Hostel();
		$form->bind($hostelModel);
		
		$hostels = $this->hostelService->listAll($tableName='hostels_list', $this->organisation_id);
		$hostelProvost = $this->hostelService->listSelectData($tableName = 'employee_details', NULL, $this->organisation_id);

		$message = NULL;
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->hostelService->save($hostelModel);
					 $this->auditTrailService->saveAuditTrail("INSERT", "Hostel Lists", "ALL", "SUCCESS");
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
             $this->flashMessenger()->addMessage('Hostel Details was successfully added');
			 return $this->redirect()->toRoute('addhosteldetail');
         }
		 
        return array(
			'form' => $form,
			'hostels' => $hostels,
			'hostelProvost' => $hostelProvost,
			'organisation_id' => $this->organisation_id,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
		);                   
    }
	
	public function editHostelDetailAction()
    {
    	$this->loginDetails();
        //to get the hostel id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new HostelForm();
			$hostelModel = new Hostel();
			$form->bind($hostelModel);
			
			$hostels = $this->hostelService->listAll($tableName='hostels_list', $this->organisation_id);
			$hostelDetail = $this->hostelService->findHostel($id);
			$hostelProvost = $this->hostelService->listSelectData($tableName = 'employee_details', NULL, $this->organisation_id);
	        
	        $request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) {
	                 try {
						 $this->hostelService->save($hostelModel);
						 $this->auditTrailService->saveAuditTrail("EDIT", "Hostel Lists", "ALL", "SUCCESS");
						 $this->redirect()->toRoute('addhosteldetail');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
	             }
	             $this->flashMessenger()->addMessage('Hostel Details was successfully edited');
				 return $this->redirect()->toRoute('addhosteldetail');
	         }
			 
	        return array(
				'form' => $form,
				'hostels' => $hostels,
				'hostelProvost' => $hostelProvost,
				'hostelDetail' => $hostelDetail); 
        }else{
        	$this->redirect()->toRoute('addhosteldetail');
        }               
    }
	
	public function allocateHostelAction()
    {
    	$this->loginDetails();
        $form = new HostelAllocationForm();
		$hostelModel = new HostelAllocation();
		$form->bind($hostelModel);
        
		$hostelList = $this->hostelService->listSelectData($tableName = 'hostels_list', $columnName = 'hostel_name', $this->organisation_id);
		$programmeYear = $this->hostelService->getStudentNoByYear($this->organisation_id);

		$message = NULL;
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->hostelService->allocateHostel($hostelModel, $this->organisation_id);
					 $this->notificationService->saveNotification('Hostel Allocation', 'ALL', 'ALL', 'Student Hostel');
					 $this->auditTrailService->saveAuditTrail("INSERT", "Student Hostels", "ALL", "SUCCESS");

					 $this->flashMessenger()->addMessage('Hostel was successfully allocated');
					 return $this->redirect()->toRoute('allocatedhostel');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
            'form' => $form,
			'hostelList' => $hostelList,
			'programmeYear' => $programmeYear,
			'organisation_id' => $this->organisation_id,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
            );            
       
    }
	//view hostel allocation list
	public function allocatedHostelListAction()
    {
    	$this->loginDetails();
        $form = new HostelAllocationForm();
		$hostelModel = new HostelAllocation();
		$form->bind($hostelModel);
        
		$hostels = $this->hostelService->listAll($tableName='hostels_list', $this->organisation_id); 

		$message = NULL;
		
        return array(
            'form' => $form,
			'hostels' => $hostels,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
            );            
    }
    
	public function allocatedHostelDetailsAction()
    {
    	$this->loginDetails();
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new HostelAllocationForm();
			$hostelModel = new HostelAllocation();
			$form->bind($hostelModel);
	        
			$hostelDetails = $this->hostelService->findHostel($id);
			$hostelAllocationDetails = $this->hostelService->getHostelAllocationDetails($id); 
			
	        return array(
	            'form' => $form,
				'hostelDetails' => $hostelDetails,
				'hostelAllocationDetails' => $hostelAllocationDetails,
	            );
        }else{
        	$this->redirect()->toRoute('allocatedhostel');
        }          
    }
	
	
	//function not used
	public function addStudentHostelAction()
    {
    	$this->loginDetails();
        $form = new HostelCategoryForm();
		$hostelModel = new HostelCategory();
		$form->bind($hostelModel);
		
		$students = $this->hostelService->listAll($tableName='student');
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->hostelService->save($hostelModel);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'students' => $students);
    }
	
	public function viewHostelRoomAction()
	{
		$this->loginDetails();

		$form = new SearchForm();

		$message = NULL;
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$hostelName = $this->getRequest()->getPost('hostels_list_id');
				$roomNo = $this->getRequest()->getPost('room_no');
				$hostelRoomsList = $this->hostelService->getHostelRoomList($hostelName, $roomNo, $this->organisation_id);
             }
         }
		 else {
			 $hostelRoomsList = array();
		 }
		
		$hostelList = $this->hostelService->listSelectData($tableName = 'hostels_list', $columnName='hostel_name', $this->organisation_id);
					 
        return array(
			'form' => $form,
			'hostelRoomsList' => $hostelRoomsList,
			'hostelList' => $hostelList,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
		);
	}
	
	public function editHostelRoomAction()
	{
		$this->loginDetails();
		//get the hostel and room id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new HostelRoomForm();
			$hostelModel = new HostelRoom();
			$form->bind($hostelModel);
			
	        $request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) {
	                 try {
						 $this->hostelService->saveHostelRoom($hostelModel);
						 $this->auditTrailService->saveAuditTrail("EDIT", "Hostel Rooms", "room_capacity", "SUCCESS");

						 $this->flashMessenger()->addMessage('Hostel Room was successfully edited');
						 return $this->redirect()->toRoute('viewhostelroom');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
	             }
	         }
			 
			$hostelList = $this->hostelService->listSelectData($tableName = 'hostels_list', $columnName='hostel_name', $this->organisation_id);
			$hostelDetail = $this->hostelService->findHostelRoom($id);
			 
	        return array(
				'form' => $form,
				'hostelList' => $hostelList,
				'hostelDetail' => $hostelDetail);
        }else{
        	$this->redirect()->toRoute('viewhostelroom');
        }
	}
    
	//applications for hostel change
	public function hostelChangeApplicationAction()
	{
		$this->loginDetails();
		$form = new HostelApplicationForm();
		$hostelModel = new HostelApplication();
		$form->bind($hostelModel);

		$message = NULL;
		
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->hostelService->saveHostelApplication($hostelModel);
					 $this->notificationService->saveNotification('Hostel Change Application', 'ALL', 'NULL', 'Student Hostel');
                     $this->auditTrailService->saveAuditTrail("INSERT", "Student Hostel Application", "ALL", "SUCCESS");

                     $this->flashMessenger()->addMessage('You have was successfully applied for hostel change');
					 return $this->redirect()->toRoute('hostelchangeapplication');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
		$hostelList = $this->hostelService->listSelectData($tableName = 'hostels_list', $columnName='hostel_name', $this->organisation_id);
		$transferRequest = $this->hostelService->getHostelApplication($this->student_id, $this->organisation_id);
		 
        return array(
			'form' => $form,
			'hostelList' => $hostelList,
			'transferRequest' => $transferRequest,
			'student_id' => $this->student_id,
			'organisation_id' => $this->organisation_id,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
		);
	}
	
	public function editHostelChangeApplicationAction()
	{
		$this->loginDetails();
		$form = new HostelApplicationForm();
		$hostelModel = new HostelApplication();
		$form->bind($hostelModel);
		
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->hostelService->saveHostelApplication($hostelModel);
					 $this->auditTrailService->saveAuditTrail("EDIT", "Student Hostel Application", "ALL", "SUCCESS");

					 $this->flashMessenger()->addMessage('You have was successfully edited the hostel change application');
					 return $this->redirect()->toRoute('hostelchangeapplication');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
		$hostelList = $this->hostelService->listSelectData($tableName = 'hostels_list', $columnName='hostel_name', $this->organisation_id);
		$transferRequest = $this->hostelService->getHostelApplication($this->student_id, $this->organisation_id);
		 
        return array(
			'form' => $form,
			'hostelList' => $hostelList,
			'transferRequest' => $transferRequest,
			'student_id' => $this->student_id,
			'organisation_id' => $this->organisation_id);
	}
	
	public function hostelChangeApplicationListAction()
	{
		$this->loginDetails();
		$form = new HostelApplicationForm();
		$hostelModel = new HostelApplication();
		$form->bind($hostelModel);
		
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->hostelService->saveHostelApplication($hostelModel);
					 return $this->redirect()->toRoute('hostelchangeapplication');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
		$hostelList = $this->hostelService->listSelectData($tableName = 'hostels_list', $columnName='hostel_name', $this->organisation_id);
		$transferRequest = $this->hostelService->getHostelApplication(NULL, $this->organisation_id);
		 
        return array(
			'form' => $form,
			'hostelList' => $hostelList,
			'transferRequest' => $transferRequest,
			'student_id' => $this->student_id,
			'organisation_id' => $this->organisation_id,
			'keyphrase' => $this->keyphrase,
		);
	}
	
	//Room Inventory for hostel
	public function identifyRoomAction()
	{
		$this->loginDetails();
		$form = new HostelInventoryForm();
		$hostelModel = new HostelInventory();
		$form->bind($hostelModel);
		
		$message = NULL;
		
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->hostelService->saveHostelInventory($hostelModel);
					 $this->auditTrailService->saveAuditTrail("INSERT", "Student Hostel Inventory", "ALL", "SUCCESS");
					 $this->flashMessenger()->addMessage("Hostel Inventory was successfully added");
					 return $this->redirect()->toRoute('identifyroom');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
		$hostelList = $this->hostelService->listSelectData($tableName = 'hostels_list', $columnName='hostel_name', $this->organisation_id);
		$hostelInventory = $this->hostelService->getHostelInventory($this->organisation_id);
		 
        return array(
			'form' => $form,
			'hostelList' => $hostelList,
			'hostelInventory' => $hostelInventory,
			'student_id' => $this->student_id,
			'organisation_id' => $this->organisation_id,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
		);
	}
	
	
	public function editHostelInventoryAction()
	{
		$this->loginDetails();
		
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){ 
			$form = new HostelInventoryForm();
			$hostelModel = new HostelInventory();
			$form->bind($hostelModel);
			
			$hostelInventoryDetails = $this->hostelService->getHostelInventoryDetails($id);
			
			$message = NULL;
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
						 $this->hostelService->saveHostelInventory($hostelModel);
						 $this->auditTrailService->saveAuditTrail("EDIT", "Student Hostel Inventory", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage("Hostel Inventory was successfully edited");
						 return $this->redirect()->toRoute('identifyroom');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			 
			$hostelList = $this->hostelService->listSelectData($tableName = 'hostels_list', $columnName='hostel_name', $this->organisation_id);
			$hostelInventory = $this->hostelService->getHostelInventory($this->organisation_id);
			 
			return array(
				'id' => $id,
				'form' => $form,
				'hostelList' => $hostelList,
				'hostelInventory' => $hostelInventory,
				'student_id' => $this->student_id,
				'organisation_id' => $this->organisation_id,
				'hostelInventoryDetails' => $hostelInventoryDetails,
				'keyphrase' => $this->keyphrase,
				'message' => $message,
			);
		}else{
			return $this->redirect()->toRoute('identifyroom');
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

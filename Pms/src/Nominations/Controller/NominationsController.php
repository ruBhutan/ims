<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Nominations\Controller;

use Nominations\Service\NominationsServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Nominations\Form\NominationsForm;
use Nominations\Form\SearchForm;
use Nominations\Model\Nominations;
use Zend\Session\Container;

/**
 * Description of IndexController
 *
 */
 
class NominationsController extends AbstractActionController
{
	protected $nominationService;
	protected $notificationService;
    protected $auditTrailService;
	protected $serviceLocator;
	protected $username;
	protected $userrole;
	protected $usertype;
	protected $userregion;
	protected $userDetails;
	protected $userImage;
	protected $employee_details_id;
	protected $keyphrase = "RUB_IMS";
	
	public function __construct(NominationsServiceInterface $nominationService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->nominationService = $nominationService;
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
		
		/*
		* Getting the employee_details_id related to username
		*/
		
		$empData = $this->nominationService->getEmployeeDetailsId($this->username);
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
		}
		
		//get the user details such as name
		$this->userDetails = $this->nominationService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->nominationService->getUserImage($this->username, $this->usertype);

	}
	
	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }
    
	public function viewPeerAction()
    {
		$this->loginDetails();
		
        $form = new NominationsForm();
		$nominationModel = new Nominations();
		$form->bind($nominationModel);
		
		$students = $this->nominationService->listAll($tableName='student');
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->nominationService->save($nominationModel);
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
    
	public function listNominationsAction()
    {
		$this->loginDetails();
		
       $form = new SearchForm();
       
       $organisationList = $this->nominationService->listSelectData('organisation', 'organisation_name');
	   $iwp_deadline = $this->nominationService->getIwpDeadline();

	   // $employeeList = NULL;
	   
	   $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$empName = $this->getRequest()->getPost('employee_name');
				$position_title = $this->getRequest()->getPost('position_title');
				$organisation_id = $this->getRequest()->getPost('organisation');
				$employeeList = $this->nominationService->getEmployeeList($id = NULL, $empName, $position_title, $organisation_id);
                                unset($employeeList[$this->employee_details_id]);
             }
         }
		 else {
			 $employeeList = array();
		 }
		 
		 //the nominated employees is the list of employees that have been nominated
		$nominatedEmployee = $this->nominationService->getNominatedEmployee($this->employee_details_id);
		$peerList = $this->nominationService->getNominationList($tableName = 'peer_nomination', $this->employee_details_id);
		$subordinateList = $this->nominationService->getNominationList($tableName = 'subordinate_nomination', $this->employee_details_id);
		$beneficiaryList = $this->nominationService->getNominationList($tableName = 'beneficiary_nomination', $this->employee_details_id);
		return array(
			'form' => $form,
            'organisationList' => $organisationList,
			'nominatedEmployee' => $nominatedEmployee,
			'peerList' => $peerList,
			'subordinateList' => $subordinateList,
			'beneficiaryList' => $beneficiaryList,
			'employeeList' => $employeeList,
			'iwp_deadline' => $iwp_deadline,
			'keyphrase' => $this->keyphrase);
		 
		/*
		$form = new NominationsForm();
		$nominationModel = new Nominations();
		$form->bind($nominationModel);
		
		$approvedList = array();
		$rejectedList = array();
		$pendingList = array();
		return array(
			'form' => $form,
			'approvedList' => $approvedList,
			'rejectedList' => $rejectedList,
			'pendingList' => $pendingList);*/
			
    }
    
	public function editPeerAction()
    {
		$this->loginDetails();
		
       $form = new NominationsForm();
		$nominationModel = new Nominations();
		$form->bind($nominationModel);
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 var_dump($form);
				 die();
                 try {
					 $this->nominationService->save($nominationModel);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array('form' => $form);
    }
	
	//to delete peer nomination
	public function deletePeerAction()
    {
		$this->loginDetails();
		
        //get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			try {
				 $this->nominationService->deleteNomination($table_name = 'peer_nomination', $id);
				 $this->flashMessenger()->addMessage('Nomination was successfully deleted');
				 return $this->redirect()->toRoute('listnominations');
			 }
			 catch(\Exception $e) {
				 $message = 'Failure';
				 $this->flashMessenger()->addMessage($e->getMessage());
				 die($e->getMessage());
				 // Some DB Error happened, log it and let the user know
			 }
			return array();
		}
		else {
			return $this->redirect()->toRoute('listnominations');
		}
    }
	
	public function addNominationsAction()
    {
		$this->loginDetails();
		
        //get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$employee_nominee = $id;
		
			$employee = $this->nominationService->getEmployeeList($id, $empName=NULL, $empId=NULL, $department=NULL);
			
			$form = new NominationsForm();
			$nominationModel = new Nominations();
			$form->bind($nominationModel);
					
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
						 $this->nominationService->saveNominations($nominationModel);
						 $this->notificationService->saveNotification('Feedback Nomination', 'ALL', 'ALL', 'Feedback Nominations');
						 $this->auditTrailService->saveAuditTrail("INSERT", "Selection of Fewback Nomination Added", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('listnominations');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			 
			return array(
				'form' => $form,
				'employee' => $employee,
				'employee_nominee' => $employee_nominee,
				'employee_details_id' => $this->employee_details_id);
		}
		else {
			return $this->redirect()->toRoute('listnominations');
		}
    } 
    
	public function viewSubordinateAction()
    {
		$this->loginDetails();
		
        $form = new NominationsForm();
		$nominationModel = new Nominations();
		$form->bind($nominationModel);
		
		$students = $this->nominationService->listAll($tableName='student');
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->nominationService->save($nominationModel);
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
    
	public function editSubordinateAction()
    {
		$this->loginDetails();
		
       $form = new NominationsForm();
		$nominationModel = new Nominations();
		$form->bind($nominationModel);
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 var_dump($form);
				 die();
                 try {
					 $this->nominationService->save($nominationModel);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array('form' => $form);
    }
	
	//to delete subordinate nomination
	public function deleteSubordinateAction()
    {
		$this->loginDetails();
		
        //get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			try {
				 $this->nominationService->deleteNomination($table_name = 'subordinate_nomination', $id);
				 $this->flashMessenger()->addMessage('Nomination was successfully deleted');
				 return $this->redirect()->toRoute('listnominations');
			 }
			 catch(\Exception $e) {
				 $message = 'Failure';
				 $this->flashMessenger()->addMessage($e->getMessage());
				 die($e->getMessage());
				 // Some DB Error happened, log it and let the user know
			 }
			return array();
		}
		else {
			return $this->redirect()->toRoute('listnominations');
		}
    }
    
	public function viewBeneficiaryAction()
    {
		$this->loginDetails();
		
        $form = new NominationsForm();
		$nominationModel = new Nominations();
		$form->bind($nominationModel);
		
		$students = $this->nominationService->listAll($tableName='student');
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->nominationService->save($nominationModel);
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
    
	public function editBeneficiaryAction()
    {
		$this->loginDetails();
		
       $form = new NominationsForm();
		$nominationModel = new Nominations();
		$form->bind($nominationModel);
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 var_dump($form);
				 die();
                 try {
					 $this->nominationService->save($nominationModel);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array('form' => $form);
    }
	
	//to delete beneficiary nomination
	public function deleteBeneficiaryAction()
    {
		$this->loginDetails();
		
        //get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			try {
				 $this->nominationService->deleteNomination($table_name = 'beneficiary_nomination', $id);
				 $this->flashMessenger()->addMessage('Nomination was successfully deleted');
				 return $this->redirect()->toRoute('listnominations');
			 }
			 catch(\Exception $e) {
				 $message = 'Failure';
				 $this->flashMessenger()->addMessage($e->getMessage());
				 die($e->getMessage());
				 // Some DB Error happened, log it and let the user know
			 }
			return array();
		}
		else {
			return $this->redirect()->toRoute('listnominations');
		}
    }
	
	//Decrypt function
	private function my_decrypt($data, $key) 
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

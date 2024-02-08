<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace StudentParticipation\Controller;

use StudentParticipation\Service\StudentParticipationServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use StudentParticipation\Form\StudentParticipationForm;
use StudentParticipation\Form\StudentParticipationCategoryForm;
use StudentParticipation\Form\SearchForm;
use StudentParticipation\Model\StudentParticipation;
use StudentParticipation\Model\StudentParticipationCategory;
use Zend\Session\Container;

/**
 * Description of IndexController
 *
 */
 
class StudentParticipationController extends AbstractActionController
{
	protected $participationService;
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

	protected $keyphrase = "RUB_IMS";
	
	public function __construct(StudentParticipationServiceInterface $participationService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->participationService = $participationService;
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
		$empData = $this->participationService->getUserDetailsId($this->username, $tableName = 'employee_details');
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			}

		if($this->employee_details_id == NULL)
		{
			$studentData = $this->participationService->getUserDetailsId($this->username, $tableName = 'student');
			foreach($studentData as $std){
			$this->student_id = $std['id'];
			$this->organisation_id = $std['organisation_id'];
			}
		}
		
		//get the organisation id
		if($this->usertype == 1){
			$organisationID = $this->participationService->getOrganisationId($this->username, $tableName = 'employee_details');
		}
		else{
			$organisationID = $this->participationService->getOrganisationId($this->username, $tableName = 'student');
		}
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}

		//get the user details such as name
        $this->userDetails = $this->participationService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->participationService->getUserImage($this->username, $this->usertype);
	}

	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }
	
	public function studentParticipationCategoryAction()
    {
    	$this->loginDetails();
		$form = new StudentParticipationCategoryForm();
		$participationModel = new StudentParticipationCategory();
		$form->bind($participationModel);
		
		$message = NULL;
		$participationCategory = $this->participationService->listAll('student_participation_category',$this->organisation_id);
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->participationService->saveParticipationCategory($participationModel);
					 $this->auditTrailService->saveAuditTrail("INSERT", "Student Participation Category", "ALL", "SUCCESS");
					 $message = 'Success';
					 //$this->redirect()->toRoute('viewstdparticipation');
				 }
				 catch(\Exception $e) {
						 $message = $e->getMessage();
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'message' => $message,
			'organisation_id' => $this->organisation_id,
			'participationCategory' => $participationCategory,
			'keyphrase' => $this->keyphrase,
		);
    } 
	
	public function editStudentParticipationCategoryAction()
    {
    	$this->loginDetails();
        //get the category id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new StudentParticipationCategoryForm();
			$participationModel = new StudentParticipationCategory();
			$form->bind($participationModel);
			
			$studentDetail = $this->participationService->getStudentDetails($id);
	        
	        $request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) {
	                 try {
						 $this->participationService->saveParticipationCategory($participationModel);
						 $this->auditTrailService->saveAuditTrail("EDIT", "Student Participation Category", "ALL", "SUCCESS");
						 $this->redirect()->toRoute('viewstdparticipation');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
	             }
	         }
			 
	        return array(
				'form' => $form,
				'studentDetail' => $studentDetail);
        }else{
        	$this->redirect()->toRoute('viewstdparticipation');
        }
    } 
	
	public function studentParticipationAction()
	{
		$this->loginDetails();
		$form = new SearchForm();
	   
	    $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$studentName = $this->getRequest()->getPost('student_name');
				$studentId = $this->getRequest()->getPost('student_id');
				$programme = $this->getRequest()->getPost('programme');
				$studentList = $this->participationService->getStudentList($studentName, $studentId, $programme, $this->organisation_id);
             }
         }
		 else {
			 $studentList = array();
		 }
		
		return new ViewModel(array(
            'form' => $form,
			'studentList' => $studentList,
			'keyphrase' => $this->keyphrase,
            ));
	}
    
	public function addStdParticipationAction()
    {
    	$this->loginDetails();
        //get the student id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new StudentParticipationForm();
			$participationModel = new StudentParticipation();
			$form->bind($participationModel);
			
			$studentDetail = $this->participationService->getStudentDetails($id);
	        
	        $request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             $data = array_merge_recursive(
				 	$request->getPost()->toArray(),
					$request->getFiles()->toArray()
				 ); 
				 $form->setData($data); 
	             if ($form->isValid()) {
	                 try {
						 $this->participationService->save($participationModel);
						 $this->notificationService->saveNotification('Student Participation', $id, 'NULL', 'Student Participation');
	                    $this->auditTrailService->saveAuditTrail("INSERT", "Student Participation", "ALL", "SUCCESS");
						 $this->redirect()->toRoute('viewstdparticipation');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
	             }
	         }
			 
	        return array(
				'form' => $form,
				'studentDetail' => $studentDetail);
        }else{
        	$this->redirect()->toRoute('stdparticipation');
        }
    } 
    
	public function viewStdParticipationAction()
    {
    	$this->loginDetails();
        $form = new StudentParticipationForm();
		$searchForm = new SearchForm();
	   
	    $request = $this->getRequest();
         if ($request->isPost()) {
             $searchForm->setData($request->getPost());
             if ($searchForm->isValid()) {
				$studentName = $this->getRequest()->getPost('student_name');
				$studentId = $this->getRequest()->getPost('student_id');
				$programme = $this->getRequest()->getPost('programme');
				$participationList = $this->participationService->getStudentParticipationList($studentName, $studentId, $programme, $this->organisation_id);
             }
         }
		 else {
			 $participationList = $this->participationService->getParticipationList($this->organisation_id);
		 }
		
        return array(
				'form' => $form,
				'searchForm' => $searchForm,
				'participationList' => $participationList,
				'keyphrase' => $this->keyphrase,
			);
    }
    
	public function editStdParticipationAction()
    {
    	$this->loginDetails();
        $form = new StudentParticipationForm();
		$participationModel = new StudentParticipation();
		$form->bind($participationModel);
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->participationService->save($participationModel);
					 $this->auditTrailService->saveAuditTrail("EDIT", "Student Participation", "ALL", "SUCCESS");
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array('form' => $form);
    }
	
	public function viewStudentParticipationDetailAction()
	{
		$this->loginDetails();
		//get the student id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new StudentParticipationForm();
		
			$student_detail_tmp = $this->participationService->getStudentDetails($id);
			$studentDetail = array();
			foreach($student_detail_tmp as $tmp){
				$studentDetail = $tmp;
			}
			$studentParticipation = $this->participationService->getStudentParticipations($studentDetail['id']);
	        		 
	        return array(
				'form' => $form,
				'studentParticipation' => $studentParticipation);
        }else{
        	$this->redirect()->toRoute('viewstdparticipation');
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

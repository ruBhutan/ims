<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Responsibilities\Controller;

use Responsibilities\Service\ResponsibilitiesServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Responsibilities\Form\ResponsibilityCategoryForm;
use Responsibilities\Form\StudentResponsibilityForm;
use Responsibilities\Form\SearchForm;
use Responsibilities\Model\Responsibilities;
use Responsibilities\Model\ResponsibilityCategory;
use Responsibilities\Model\StudentResponsibility;
use Zend\Session\Container;

/**
 * Description of IndexController
 *
 */
 
class ResponsibilitiesController extends AbstractActionController
{
	protected $responsibilityService;
	protected $notificationService;
    protected $auditTrailService;
    protected $serviceLocator;
	protected $username;
	protected $usertype;
	protected $userrole;
	protected $userregion;
	protected $userDetails;
	protected $userImage;
	protected $employee_details_id;
	protected $organisation_id;

	protected $keyphrase = "RUB_IMS";
	
	public function __construct(ResponsibilitiesServiceInterface $responsibilityService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->responsibilityService = $responsibilityService;
		$this->notificationService = $notificationService;
        $this->auditTrailService = $auditTrailService;
        $this->serviceLocator = $serviceLocator;
		
		/*
		 * To retrieve the user name from the session
		*/
		$authPlugin = $serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
        $this->username = $authPlugin['username'];
        $this->usertype = $authPlugin['user_type_id'];
        $this->userrole = $authPlugin['role'];
        $this->userregion = $authPlugin['region'];
		
		/*
		* Getting the student_id/employee_details_id related to username
		*/
		
		$empData = $this->responsibilityService->getUserDetailsId($this->username);
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			}
		
		//get the organisation id
		$organisationID = $this->responsibilityService->getOrganisationId($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}

		//get the user details such as name
        $this->userDetails = $this->responsibilityService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->responsibilityService->getUserImage($this->username, $this->usertype);
	}

	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }
    
	public function addResponsibilityCategoryAction()
    {
    	$this->loginDetails();
        $form = new ResponsibilityCategoryForm();
		$responsibilityModel = new ResponsibilityCategory();
		$form->bind($responsibilityModel);
		
		$categories = $this->responsibilityService->listAll($tableName='responsibility_category', $this->organisation_id);

		$message = NULL;
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->responsibilityService->save($responsibilityModel);
					 $this->auditTrailService->saveAuditTrail("INSERT", "Responsility Category", "ALL", "SUCCESS");
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }

             $this->flashMessenger()->addMessage('Responsibility Category was successfully added.');
			 return $this->redirect()->toRoute('responsibilitycategory');
         }
		 
        return array(
			'form' => $form,
			'organisation_id' => $this->organisation_id,
			'keyphrase' => $this->keyphrase,
			'categories' => $categories,
			'message' => $message,
		);
    } 
    
	public function addStudentResponsibilityAction()
    {
    	$this->loginDetails();
        //get the student id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new StudentResponsibilityForm();
			$responsibilityModel = new StudentResponsibility();
			$form->bind($responsibilityModel);
			
			$student = $this->responsibilityService->getStudentDetails($id);
			$responsibilityCategory = $this->responsibilityService->listSelectData($tableName = 'responsibility_category', $columnName = 'responsibility_name', $this->organisation_id);
	        
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
						 $this->responsibilityService->saveResponsibility($responsibilityModel);
						 $this->notificationService->saveNotification('Student Responsibility', $id, 'ALL', 'Student Responsibility');
	                     $this->auditTrailService->saveAuditTrail("INSERT", "Student Responsibilities", "ALL", "SUCCESS");

	                     $this->flashMessenger()->addMessage('Responsibility was successfully added to student.');
						 return $this->redirect()->toRoute('studentresponsibility');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
	             }
	         }
			 
	        return array(
				'id' => $id,
				'form' => $form,
				'student' => $student,
				'responsibilityCategory' => $responsibilityCategory
			);
        }else{
        	$this->redirect()->toRoute('responsibilitycategory');
        }
    }
    
	public function listResponsibilityCategoryAction()
    {
    	$this->loginDetails();
        $form = new ResponsibilityCategoryForm();
		$responsibilityModel = new ResponsibilityCategory();
		$form->bind($responsibilityModel);
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 var_dump($form);
				 die();
                 try {
					 $this->responsibilityService->save($responsibilityModel);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array('form' => $form);
    }
    
	public function editResponsibilityCategoryAction()
    {
    	$this->loginDetails();
       //get the responsibility category id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new ResponsibilityCategoryForm();
			$responsibilityModel = new ResponsibilityCategory();
			$form->bind($responsibilityModel);
			
			$responsibility = $this->responsibilityService->getResponsibilityCategoryDetails($id);
	        
	        $request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) {
	                 try {
						 $this->responsibilityService->save($responsibilityModel);
						 $this->auditTrailService->saveAuditTrail("EDIT", "Responsility Category", "ALL", "SUCCESS");

						 $this->flashMessenger()->addMessage('Responsibility Category was successfully edited.');
						 return $this->redirect()->toRoute('responsibilitycategory');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
	             }
	         }
			 
	        return array(
					'id' => $id,
					'form' => $form,
					'responsibility' => $responsibility);
        }else{
        	$this->redirect()->toRoute('responsibilitycategory');
        }
    }
	
	public function viewResponsibilityCategoryAction()
    {
    	$this->loginDetails();
       //get the responsibility category id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$responsibility = $this->responsibilityService->getResponsibilityCategoryDetails($id);
		
		    $form = new ResponsibilityCategoryForm();
			$responsibilityModel = new ResponsibilityCategory();
			$form->bind($responsibilityModel);
			 
	        return array(
					'form' => $form,
					'responsibility' => $responsibility,
				);
        }else{
        	$this->redirect()->toRoute('responsibilitycategory');
        }
    }
	
	public function studentResponsibilityAction()
	{
		$this->loginDetails();

		$form = new SearchForm();

		$message = NULL;
	   
	    $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$studentName = $this->getRequest()->getPost('student_name');
				$studentId = $this->getRequest()->getPost('student_id');
				$programme = $this->getRequest()->getPost('programme');
				$studentList = $this->responsibilityService->getStudentList($studentName, $studentId, $programme, $this->organisation_id);
             }
         }
		 else {
			 $studentList = array();
		 }
		
		return new ViewModel(array(
            'form' => $form,
			'studentList' => $studentList,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
            ));
	}
	
	public function viewStudentResponsibilityAction()
    {
    	$this->loginDetails();

        $form = new ResponsibilityCategoryForm();
		$responsibilityModel = new ResponsibilityCategory();
		$form->bind($responsibilityModel);
		
		$searchForm = new SearchForm();	

		$message = NULL;	
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $searchForm->setData($request->getPost());
             if ($searchForm->isValid()) {
                 try {
					$studentName = $this->getRequest()->getPost('student_name');
					$studentId = $this->getRequest()->getPost('student_id');
					$programme = $this->getRequest()->getPost('programme');
					$studentResponsibilities = $this->responsibilityService->getStudentResponsibilitiesList($studentName, $studentId, $programme, $this->organisation_id);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 else {
			 $studentResponsibilities = $this->responsibilityService->listStudentResponsibilities($this->organisation_id);
		 }
		 
        return array(
			'form' => $form,
			'searchForm' => $searchForm,
			'keyphrase' => $this->keyphrase,
			'studentResponsibilities' => $studentResponsibilities,
			'message' => $message,
		);
    }
		
	public function editStudentResponsibilityAction()
    {
    	$this->loginDetails();
        $form = new ResponsibilityCategoryForm();
		$responsibilityModel = new ResponsibilityCategory();
		$form->bind($responsibilityModel);
		
		$students = $this->responsibilityService->listAll($tableName='student', $this->organisation_id);
		$studentResponsibilities = $this->responsibilityService->listAll($tableName='student_responsibilities', $this->organisation_id);
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->responsibilityService->save($responsibilityModel);
					 $this->auditTrailService->saveAuditTrail("EDIT", "Student Responsibilities", "ALL", "SUCCESS");

					 $this->flashMessenger()->addMessage('Responsibility was successfully edited');
					 return $this->redirect()->toRoute('viewstdresponsibility');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'students' => $students,
			'studentResponsibilities' => $studentResponsibilities);
    }
	
	public function viewStudentResponsibilityDetailAction()
    {
    	$this->loginDetails();
        //get the student id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new StudentResponsibilityForm();
		
			$student_detail_tmp = $this->responsibilityService->getStudentDetails($id);
			$studentDetail = array();
			foreach($student_detail_tmp as $tmp){
				$studentDetail = $tmp;
			}
			$studentResponsibilities = $this->responsibilityService->getStudentResponsibilities($studentDetail['id']);
	        		 
	        return array(
				'form' => $form,
				'studentResponsibilities' => $studentResponsibilities);
        }else{
        	$this->redirect()->toRoute('viewstdresponsibility');
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

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace StudentContribution\Controller;

use StudentContribution\Service\StudentContributionServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use StudentContribution\Form\StudentContributionForm;
use StudentContribution\Form\StudentContributionCategoryForm;
use StudentContribution\Form\SearchForm;
use StudentContribution\Model\StudentContribution;
use StudentContribution\Model\StudentContributionCategory;
use Zend\Session\Container;

/**
 * Description of IndexController
 *
 */
 
class StudentContributionController extends AbstractActionController
{
	protected $contributionService;
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
	
	public function __construct(StudentContributionServiceInterface $contributionService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->contributionService = $contributionService;
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
		$empData = $this->contributionService->getUserDetailsId($this->username, $tableName = 'employee_details');
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			}

		if($this->employee_details_id == NULL)
		{
			$studentData = $this->contributionService->getUserDetailsId($this->username, $tableName = 'student');
			foreach($studentData as $std){
			$this->student_id = $std['id'];
			$this->organisation_id = $std['organisation_id'];
			}
		}
		
		//get the organisation id
		if($this->usertype == 1){
			$organisationID = $this->contributionService->getOrganisationId($this->username, $tableName = 'employee_details');
		}
		else{
			$organisationID = $this->contributionService->getOrganisationId($this->username, $tableName = 'student');
		}
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}

		//get the user details such as name
        $this->userDetails = $this->contributionService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->contributionService->getUserImage($this->username, $this->usertype);
	}


	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }

	
	public function studentContributionCategoryAction()
    {
    	$this->loginDetails();		
		$form = new StudentContributionCategoryForm();
		$contributionModel = new StudentContributionCategory();
		$form->bind($contributionModel);
		
		$contributionCategory = $this->contributionService->listAll('student_contributions_category',$this->organisation_id);
		$message = NULL;
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->contributionService->saveContributionCategory($contributionModel);
					 $this->auditTrailService->saveAuditTrail("INSERT", "Student Contributions Category", "ALL", "SUCCESS");
					 $this->flashMessenger()->addMessage('Contribution Category was successfully added');
					 return $this->redirect()->toRoute('stdcontributioncategory');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'message' => $message,
			'organisation_id' => $this->organisation_id,
			'contributionCategory' => $contributionCategory,
			'keyphrase' => $this->keyphrase,
		);
    } 
	
	public function editStudentContributionCategoryAction()
    {
    	$this->loginDetails();
        //get the category id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){ 
        	$form = new StudentContributionCategoryForm();
			$contributionModel = new StudentContributionCategory();
			$form->bind($contributionModel);
			
			$categoryDetail = $this->contributionService->getStudentContributionCategoryDetails($id);
	        $message = NULL;
	        $request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) {
	                 try {
						 $this->contributionService->saveContributionCategory($contributionModel);
						 $this->auditTrailService->saveAuditTrail("EDIT", "Student Contributions Category", "ALL", "SUCCESS");
						  $this->flashMessenger()->addMessage('Contribution Category was successfully edited');
					 	return $this->redirect()->toRoute('stdcontributioncategory');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
	             }
	         }
			 
	        return array(
				'form' => $form,
				'organisation_id' => $this->organisation_id,
				'categoryDetail' => $categoryDetail,
				'message' => $message,
			);
        }else{
        	$this->redirect()->toRoute('viewstdcontribution');
        }
    } 
	
	public function studentContributionAction()
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
				$studentList = $this->contributionService->getStudentList($studentName, $studentId, $programme, $this->organisation_id);
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
    
	public function addStdContributionAction()
    {
    	$this->loginDetails();
        //get the student id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new StudentContributionForm();
			$contributionModel = new StudentContribution();
			$form->bind($contributionModel);
			
			$studentDetail = $this->contributionService->getStudentDetails($id);

			$contribution_category = $this->contributionService->listSelectData($tableName = 'student_contributions_category', $columnName = 'contribution_type', $this->organisation_id);

			$message = NULL;
	        
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
						 $this->contributionService->save($contributionModel);
						 $this->notificationService->saveNotification('Student Contributions', $id, 'NULL', 'Student Contributions');
	                     $this->auditTrailService->saveAuditTrail("INSERT", "Student Contributions", "ALL", "SUCCESS");
	                     $this->flashMessenger()->addMessage('Student Contribution was successfully added');
						 return $this->redirect()->toRoute('viewstdcontribution');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
	             }
	         }
			 
	        return array(
				'form' => $form,
				'organisation_id' => $this->organisation_id,
				'studentDetail' => $studentDetail,
				'message' => $message,
				'contribution_category' => $contribution_category,
			);
        }else{
        	$this->redirect()->toRoute('stdcontribution');
        }
    } 
    
	public function viewStdContributionAction()
    {
    	$this->loginDetails();
        $form = new StudentContributionForm();
		$searchForm = new SearchForm();

		$message = NULL;
	   
	    $request = $this->getRequest();
         if ($request->isPost()) {
             $searchForm->setData($request->getPost());
             if ($searchForm->isValid()) {
				$studentName = $this->getRequest()->getPost('student_name');
				$studentId = $this->getRequest()->getPost('student_id');
				$programme = $this->getRequest()->getPost('programme');
				$contributionList = $this->contributionService->getStudentContributionList($studentName, $studentId, $programme, $this->organisation_id);
             }
         }
		 else {
			 $contributionList = $this->contributionService->getContributionList($this->organisation_id);
		 }
		
        return array(
			'form' => $form,
			'searchForm' => $searchForm,
			'contributionList' => $contributionList,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
		);
    }
    
	public function editStdContributionAction()
    {
    	$this->loginDetails();

        $form = new StudentContributionForm();
		$contributionModel = new StudentContribution();
		$form->bind($contributionModel);
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->contributionService->save($contributionModel);
					 $this->auditTrailService->saveAuditTrail("EDIT", "Student Contributions", "ALL", "SUCCESS");
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array('form' => $form);
    }
	
	public function viewStudentContributionDetailAction()
	{
		$this->loginDetails();
		//get the student id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new StudentContributionForm();
		
			$student_detail_tmp = $this->contributionService->getStudentDetails($id);
			$studentDetail = array();
			foreach($student_detail_tmp as $tmp){
				$studentDetail = $tmp;
			}
			$studentContributions = $this->contributionService->getStudentContributions($studentDetail['id']);
	        		 
	        return array(
				'form' => $form,
				'studentContributions' => $studentContributions);
        }else{
        	$this->redirect()->toRoute('viewstdcontribution');
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

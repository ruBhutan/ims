<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace StudentSuggestions\Controller;

use StudentSuggestions\Service\StudentSuggestionsServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use StudentSuggestions\Form\StudentSuggestionsForm;
use StudentSuggestions\Form\SuggestionCategoryForm;
use StudentSuggestions\Form\SuggestionCommitteeForm;
use StudentSuggestions\Form\SearchForm;
use StudentSuggestions\Model\StudentSuggestions;
use StudentSuggestions\Model\SuggestionCommittee;
use StudentSuggestions\Model\SuggestionCategory;
use Zend\Session\Container;

/**
 * Description of IndexController
 *
 */
 
class StudentSuggestionsController extends AbstractActionController
{
	protected $studentService;
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
	
	public function __construct(StudentSuggestionsServiceInterface $studentService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->studentService = $studentService;
		$this->notificationService = $notificationService;
        $this->auditTrailService = $auditTrailService;
		$this->serviceLocator = $serviceLocator;
		
		/*
         * To retrieve the user name from the session
        */
        $authPlugin = $this->serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
        // Use service locator to get the authPlugin
        $this->username = $authPlugin['username'];
        $this->usertype = $authPlugin['user_type_id'];
		$this->userrole = $authPlugin['role'];
		$this->userregion = $authPlugin['region'];
		
		/*
		* Getting the student_id/employee_details_id related to username
		*/
		
		$empData = $this->studentService->getUserDetailsId($this->username, $tableName = 'employee_details');
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			}
			
		if($this->employee_details_id == NULL)
		{
			$studentData = $this->studentService->getUserDetailsId($this->username, $tableName = 'student');
			foreach($studentData as $std){
			$this->student_id = $std['id'];
			}
		}

		//get the organisation id
		if($this->employee_details_id == NULL)
			$organisationID = $this->studentService->getOrganisationId($this->username, $tableName = 'student');
		else 
			$organisationID = $this->studentService->getOrganisationId($this->username, $tableName = 'employee_details');
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}

		//get the user details such as name
        $this->userDetails = $this->studentService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->studentService->getUserImage($this->username, $this->usertype);
	}

	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }
	
	public function addStudentSuggestionCategoryAction()
    {	
    	$this->loginDetails();	
		$form = new SuggestionCategoryForm();
		$studentModel = new SuggestionCategory();
		$form->bind($studentModel);
		
		//get list of all suggestion categories
		$suggestionCategories = $this->studentService->listAll($tableName = 'student_suggestion_category', $this->organisation_id);

		$message = NULL;
		        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             $data = $this->params()->fromPost();
             $suggestionCategory = $data['suggestioncategory']['suggestion_category'];

             $check_suggesttion_category = $this->studentService->crossCheckSuggestionCategory($suggestionCategory, $this->organisation_id);

             if($check_suggesttion_category){
             	$message = 'Failure';
             	$this->flashMessenger()->addMessage("You can't add this particular suggestion category since category similar to this was already added. Please try for different category");
             }
             else{
             	if ($form->isValid()) {
	                 try {
						 $this->studentService->saveCategory($studentModel);
						 $this->auditTrailService->saveAuditTrail("INSERT", "Student Suggesting Category", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage('You have successfully added student suggestion category.');
						 return $this->redirect()->toRoute('stdsuggestioncategory');
					 }
					 catch(\Exception $e) {
					 	$message = 'Failure';
					 	$this->flashMessenger()->addMessage($e->getMessage());
					 }
	             }
             }
         }
		 
        return array(
			'form' => $form,
			'suggestionCategories' => $suggestionCategories,
			'organisation_id' => $this->organisation_id,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
		);
    }


    public function editStudentSuggestionCategoryAction()
    {
    	$this->loginDetails();

    	$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new SuggestionCategoryForm();
			$studentModel = new StudentSuggestions();
			$form->bind($studentModel);
			
			//get list of all suggestion categories
			$suggestionCategories = $this->studentService->listAll($tableName = 'student_suggestion_category', $this->organisation_id);

			$suggestionCategoryDetails = $this->studentService->getSuggestionCategoryDetails($id);

			$message = NULL;
			        
	        $request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             $data = $this->params()->fromPost();
	             $suggestionCategory = $data['suggestioncategory']['suggestion_category'];
	             
	             $check_suggesttion_category = $this->studentService->crossCheckSuggestionCategoryDetails($suggestionCategory, $id, $this->organisation_id);

	             if($check_suggesttion_category){
	             	$message = 'Failure';
	             	$this->flashMessenger()->addMessage("You can't edit this particular suggestion category since category similar to this was already there. Please try for different category");
	             }
	             else{
	             	if ($form->isValid()) {
		                 try {
							 $this->studentService->saveCategory($studentModel);
							 $this->auditTrailService->saveAuditTrail("UPDATE", "Student Suggesting Category", "ALL", "SUCCESS");
							 $this->flashMessenger()->addMessage('You have successfully edit student suggestion category.');
							 return $this->redirect()->toRoute('stdsuggestioncategory');
						 }
						 catch(\Exception $e) {
						 	$message = 'Failure';
						 	$this->flashMessenger()->addMessage($e->getMessage());
						 }
		             }
	             }
	         }
			 
	        return array(
	        	'id' => $id,
				'form' => $form,
				'suggestionCategoryDetails' => $suggestionCategoryDetails,
				'suggestionCategories' => $suggestionCategories,
				'organisation_id' => $this->organisation_id,
				'keyphrase' => $this->keyphrase,
				'message' => $message,
			);
        }else{
        	return $this->redirect()->toRoute('stdsuggestioncategory');
        }
    }


     public function addSuggestionCommitteeAction()
	{
		$this->loginDetails();

		$form = new SuggestionCommitteeForm($this->serviceLocator);
		$studentModel = new SuggestionCommittee();
		$form->bind($studentModel);
		
		$suggestionList = $this->studentService->listSelectData($tableName = 'student_suggestion_category', $columnName='suggestion_category', $this->organisation_id);

		$suggestionCommitteeList = $this->studentService->listAllSuggestionCommitteeList($tableName = 'student_suggestion_committee', $this->organisation_id);

		$message = NULL;
		        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             $data = $this->params()->fromPost();
             $suggestionCategory = $data['suggestioncommittee']['student_suggestion_category_id'];
             $fromDate = $data['suggestioncommittee']['from_date'];
             $toDate = $data['suggestioncommittee']['to_date'];
             if ($form->isValid()) { 
             	$employee_details_id = $this->getRequest()->getPost('employee_details_id');
             	
        		$check_suggestion_committee = $this->studentService->crossCheckSuggestionCommitteeMember($suggestionCategory, $employee_details_id);

        		if($check_suggestion_committee){
        			$message = 'Failure';
        			$this->flashMessenger()->addMessage("You can't add this particular staff as a committee member of this particular category since already you have added and still this staff is active");
        		}
        		else if($fromDate >= $toDate){
        			$message = 'Failure';
        			$this->flashMessenger()->addMessage("You can't add this particular staff as a committee member since your To Date is less than or equal to From Date. To Date should be greater than From Date.");
        		}
        		else{
        			try {
						 $this->studentService->saveSuggestionCommittee($studentModel, $employee_details_id);
						 $this->notificationService->saveNotification('Student Suggesstion Committee Member', $employee_details_id, 'ALL', 'Student Suggesstion Committee');
	                     $this->auditTrailService->saveAuditTrail("INSERT", "Student Suggesstion Committee", "ALL", "SUCCESS");
	                     $this->flashMessenger()->addMessage('You have successfully added suggestion Committee member');
						 return $this->redirect()->toRoute('suggestioncommittee');
				 	}
					 catch(\Exception $e) {
				 		$message = 'Failure';
				 		$this->flashMessenger()->addMessage($e->getMessage());
				 	}
        		}
             }
         }
		 
        return array(
			'form' => $form,
			//'suggestionCategories' => $suggestionCategories,
			'suggestionList' => $suggestionList,
			'suggestionCommitteeList' => $suggestionCommitteeList,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
		);
	}
	
	public function editSuggestionCommitteeAction()
	{
		$this->loginDetails();

		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new SuggestionCommitteeForm($this->serviceLocator);
			$studentModel = new SuggestionCommittee();
			$form->bind($studentModel);

			$committeeDetails = $this->studentService->getCommitteDetails($id);
			
			$suggestionList = $this->studentService->listSelectData($tableName = 'student_suggestion_category',$columnName='suggestion_category', $this->organisation_id);

			$suggestionCommitteeList = $this->studentService->listAllSuggestionCommitteeList($tableName = 'student_suggestion_committee', $this->organisation_id);

			$suggestionCommitteeDetails = $this->studentService->getSuggestionCommitteeDetails($id);

			$message = NULL;
			        
	        $request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             $data = $this->params()->fromPost();
	             $suggestionCategory = $data['suggestioncommittee']['student_suggestion_category_id'];
	             $fromDate = $data['suggestioncommittee']['from_date'];
	             $toDate = $data['suggestioncommittee']['to_date'];
	             if ($form->isValid()) { 
	             	$employee_details_id = $this->getRequest()->getPost('employee_details_id');

	        		$check_suggestion_committee = $this->studentService->crossCheckSuggestionCommittee($id, $suggestionCategory, $employee_details_id);

	        		if($check_suggestion_committee){
	        			$message = 'Failure';
	        			$this->flashMessenger()->addMessage("You can't add this particular staff as a committee member of this particular category since already you have added and still this staff is active");
	        		}
	        		else if($fromDate >= $toDate){
	        			$message = 'Failure';
	        			$this->flashMessenger()->addMessage("You can't add this particular staff as a committee member since your To Date is less than or equal to From Date. To Date should be greater than From Date.");
	        		}
	        		else{
	        			try {
							 $this->studentService->saveSuggestionCommittee($studentModel, $employee_details_id);
		                     $this->auditTrailService->saveAuditTrail("UPDATE", "Student Suggesstion Committee", "ALL", "SUCCESS");
		                     $this->flashMessenger()->addMessage('You have successfully edited suggestion Committee member');
							 return $this->redirect()->toRoute('suggestioncommittee');
					 	}
						 catch(\Exception $e) {
					 		$message = 'Failure';
					 		$this->flashMessenger()->addMessage($e->getMessage());
					 	}
	        		}
	             }
	         }
			 
	        return array(
	        	'id'=> $id,
				'form' => $form,
				'suggestionCommitteeDetails' => $suggestionCommitteeDetails,
				'suggestionList' => $suggestionList,
				'suggestionCommitteeList' => $suggestionCommitteeList,
				'committeeDetails' => $committeeDetails,
				'keyphrase' => $this->keyphrase,
				'message' => $message,
			);
        }else{
        	return $this->redirect()->toRoute('suggestioncommittee');
        }	
	}


	public function updateSuggestionCommitteeMemberStatusAction()
	{
		$this->loginDetails();

    	$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        		try{
        			$this->studentService->updateSuggestionCommitteeStatus($status = 'Inactive', $previousStatus=NULL, $id);
        			$this->auditTrailService->saveAuditTrail("UPDATE", "Student Suggesstion Committee", "status", "SUCCESS");
        			$this->flashMessenger()->addMessage("You have successfully Deactivated Suggestion Committee Member Status");
        			return $this->redirect()->toRoute('suggestioncommittee');
        		}
	        	catch(\Exception $e){
	        		$message = 'Failure';
				 	$this->flashMessenger()->addMessage($e->getMessage());
	        	}
        }else{
        	return $this->redirect()->toRoute('suggestioncommittee');
        }
	}
	
	public function viewStudentSuggestionAction()
    {
    	$this->loginDetails();
        $form = new StudentSuggestionsForm();
		$studentModel = new StudentSuggestions();
		$form->bind($studentModel);

		$employee_details_id = $this->employee_details_id;
		
		$studentSuggestions = $this->studentService->listSelectedSuggestion($employee_details_id, $tableName = 'student_suggestion', $this->organisation_id);
		$suggestionCategory = $this->studentService->listAll($tableName = 'student_suggestion_category', $this->organisation_id);
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->studentService->save($studentModel);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'studentSuggestions' => $studentSuggestions,
			'suggestionCategory' => $suggestionCategory
		);
    } 
    
	public function postStudentSuggestionAction()
    {
    	$this->loginDetails();

        $form = new StudentSuggestionsForm();
		$studentModel = new StudentSuggestions();
		$form->bind($studentModel);
		
		$suggestionCategory = $this->studentService->listSelectData($tableName = 'student_suggestion_category' , $columnName = 'suggestion_category', $this->organisation_id);

		$message = NULL;
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->studentService->saveStudentSuggestions($studentModel);
                    //$this->notificationService->saveNotification('Research Grant', 'ALL', 'ALL', 'Research Grant Announcement');
                    $this->auditTrailService->saveAuditTrail("INSERT", "Student Suggesstion", "ALL", "SUCCESS");
                    $this->flashMessenger()->addMessage("You have successfully posted suggestion");
                    return $this->redirect()->toRoute('poststdsuggestions');
				 }
				 catch(\Exception $e) {
				 	$message = 'Failure';
				 	$this->flashMessenger()->addMessage($e->getMessage());
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'usertype' => $this->usertype,
			'student_id' => $this->student_id,
			'suggestionCategory' => $suggestionCategory,
			'suggestionList' => $this->studentService->listStudentSuggestionList($this->student_id),
			'keyphrase' => $this->keyphrase,
			'message' => $message,
		);
    }


    public function studentSuggestionDetailsAction()
    {
    	$this->loginDetails();

    	$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new StudentSuggestionsForm();
			$studentModel = new StudentSuggestions();
			$form->bind($studentModel);
			
			$suggestionDetails = $this->studentService->getSuggestionDetails($id);

			 $request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) {
			                 try {
			                 }
			                 catch(\Exception $e) {
							 	$message = 'Failure';
							 	$this->flashMessenger()->addMessage($e->getMessage());
									 // Some DB Error happened, log it and let the user know
							 }
			             }
	         }
			 
	        return array(
	        	'id' => $id,
				'form' => $form,
				'suggestionDetails' => $suggestionDetails,
			);
        }else{
            return $this->redirect()->toRoute('poststdsuggestions');
        } 
    }


    public function viewSuggestionToCommitteeAction()
    {
    	$this->loginDetails();

    	return array(
    		'employee_details_id' => $this->employee_details_id,
    		'committedSuggestionList' => $this->studentService->listStudentSuggestionToCommittee($this->employee_details_id),
    		'keyphrase' => $this->keyphrase,
    	);
    }


    public function viewPostedSuggestionDetailsAction()
    {
    	$this->loginDetails();

    	$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new StudentSuggestionsForm();
			$studentModel = new StudentSuggestions();
			$form->bind($studentModel);

			return array(
				'id' => $id,
				'form' => $form,
				'suggestionDetails' => $this->studentService->getPostedCommitteeSuggestionDetails($id),
			);
        }else{
        	return $this->redirect()->toRoute('viewsuggestiontocommittee');
        }
    }
    
	public function searchStudentSuggestionAction()
    {
    	$this->loginDetails();
        $form = new StudentSuggestionsForm();
		$studentModel = new StudentSuggestions();
		$form->bind($studentModel);
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 var_dump($form);
				 die();
                 try {
					 $this->studentService->save($studentModel);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array('form' => $form);
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

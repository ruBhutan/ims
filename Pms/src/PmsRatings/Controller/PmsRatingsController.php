<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PmsRatings\Controller;

use PmsRatings\Service\PmsRatingsServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use PmsRatings\Form\FeedbackQuestionsForm;
use PmsRatings\Model\FeedbackQuestions;
use Zend\Session\Container;

/**
 * Description of IndexController
 *
 */
 
class PmsRatingsController extends AbstractActionController
{
	protected $pmsService;
	protected $notificationService;
    protected $auditTrailService;
	protected $serviceLocator;
	protected $username;
	protected $userrole;
	protected $usertype;
	protected $userregion;
	protected $userDetails;
	protected $keyphrase = "RUB_IMS";
	
	public function __construct(PmsRatingsServiceInterface $pmsService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->pmsService = $pmsService;
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
		
		//get the user details such as name
		$this->userDetails = $this->pmsService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->pmsService->getUserImage($this->username, $this->usertype);

	}
	
	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }
    
	public function viewFeedbackQuestionsAction()
    {
		$this->loginDetails();
		
        $peerForm = new FeedbackQuestionsForm();
		$peerModel = new FeedbackQuestions();
		$peerForm->bind($peerModel);
		
		$studentForm = new FeedbackQuestionsForm();
		$studentModel = new FeedbackQuestions();
		$studentForm->bind($studentModel);
		
		$beneficiaryForm = new FeedbackQuestionsForm();
		$beneficiaryModel = new FeedbackQuestions();
		$beneficiaryForm->bind($beneficiaryModel);
		
		$subordinateForm = new FeedbackQuestionsForm();
		$subordinateModel = new FeedbackQuestions();
		$subordinateForm->bind($subordinateModel);
		
		//need to get the list of questions from the database
		$peerQuestions = $this->pmsService->listAll($tableName='peer_feedback_questions');
		$subordinateQuestions = $this->pmsService->listAll($tableName='subordinate_feedback_questions');
		$beneficiaryQuestions = $this->pmsService->listAll($tableName='beneficiary_feedback_questions');
		$studentQuestions = $this->pmsService->listAll($tableName='student_feedback_questions');
       
        return array(
			'peerForm' => $peerForm,
			'studentForm' => $studentForm,
			'beneficiaryForm' => $beneficiaryForm,
			'subordinateForm' => $subordinateForm,
			'peerQuestions' => $peerQuestions,
			'subordinateQuestions' => $subordinateQuestions,
			'beneficiaryQuestions' => $beneficiaryQuestions,
			'studentQuestions' => $studentQuestions);
    }
    
	public function listPmsRatingsAction()
    {
		$this->loginDetails();
		
        $form = newFeedbackQuestionsForm();
		$pmsModel = new FeedbackQuestions();
		$form->bind($pmsModel);
		
		$approvedList = array();
		$rejectedList = array();
		$pendingList = array();
		return array(
			'form' => $form,
			'approvedList' => $approvedList,
			'rejectedList' => $rejectedList,
			'pendingList' => $pendingList);
    }
    
	public function addPeerQuestionsAction()
    {
		$this->loginDetails();
		
	   $form = new FeedbackQuestionsForm();
		$pmsModel = new FeedbackQuestions();
		$form->bind($pmsModel);
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->pmsService->save($pmsModel, $tableName='peer_feedback_questions');
					 $this->auditTrailService->saveAuditTrail("INSERT", "Peer Review Question Added", "ALL", "SUCCESS");
					 return $this->redirect()->toRoute('viewfeedbackquestions');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array('form' => $form);
    }
	
	public function editPeerQuestionsAction()
    {
		$this->loginDetails();
		
	   	//get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			
		}
		else {
			
		}
		
		$form = new FeedbackQuestionsForm();
		$pmsModel = new FeedbackQuestions();
		$form->bind($pmsModel);
        
		//need to get the list of questions from the database
		$question = $this->pmsService->findQuestion($id, $tableName = 'peer_feedback_questions');
		$peerQuestions = $this->pmsService->listAll($tableName='peer_feedback_questions');
				
        return array(
			'form' => $form,
			'question' => $question,
			'peerQuestions' => $peerQuestions);
    }
	
	public function addSubordinateQuestionsAction()
    {
		$this->loginDetails();
		
	   $form = new FeedbackQuestionsForm();
		$pmsModel = new FeedbackQuestions();
		$form->bind($pmsModel);
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->pmsService->save($pmsModel, $tableName='subordinate_feedback_questions');
					 $this->auditTrailService->saveAuditTrail("INSERT", "Subordinate Review Question Added", "ALL", "SUCCESS");
					 return $this->redirect()->toRoute('viewfeedbackquestions');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array('form' => $form);
    }
	
	public function editSubordinateQuestionsAction()
    {
		$this->loginDetails();
		
	   	//get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			
		}
		else {
			
		}
		
		$form = new FeedbackQuestionsForm();
		$pmsModel = new FeedbackQuestions();
		$form->bind($pmsModel);
		
		$question = $this->pmsService->findQuestion($id, $tableName = 'subordinate_feedback_questions');
		$subordinateQuestions = $this->pmsService->listAll($tableName='subordinate_feedback_questions');
        
        return array(
			'form' => $form,
			'question' => $question,
			'subordinateQuestions' => $subordinateQuestions);
    }
	
	public function addBeneficiaryQuestionsAction()
    {
		$this->loginDetails();
		
	   $form = new FeedbackQuestionsForm();
		$pmsModel = new FeedbackQuestions();
		$form->bind($pmsModel);
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->pmsService->save($pmsModel, $tableName='beneficiary_feedback_questions');
					 $this->auditTrailService->saveAuditTrail("INSERT", "Beneficiary Review Question Added", "ALL", "SUCCESS");
					 return $this->redirect()->toRoute('viewfeedbackquestions');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array('form' => $form);
    }
	
	public function editBeneficiaryQuestionsAction()
    {
		$this->loginDetails();
		
	   	//get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			
		}
		else {
			
		}
		
		$form = new FeedbackQuestionsForm();
		$pmsModel = new FeedbackQuestions();
		$form->bind($pmsModel);
		
		$question = $this->pmsService->findQuestion($id, $tableName = 'beneficiary_feedback_questions');
		$beneficiaryQuestions = $this->pmsService->listAll($tableName='beneficiary_feedback_questions');
        
        return array(
			'form' => $form,
			'question' => $question,
			'beneficiaryQuestions' => $beneficiaryQuestions);
    }
	
    public function addStudentQuestionsAction()
    {
		$this->loginDetails();
		
	   $form = new FeedbackQuestionsForm();
		$pmsModel = new FeedbackQuestions();
		$form->bind($pmsModel);
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->pmsService->save($pmsModel, $tableName='student_feedback_questions');
					 $this->auditTrailService->saveAuditTrail("INSERT", "Student Review Question Added", "ALL", "SUCCESS");
					 return $this->redirect()->toRoute('viewfeedbackquestions');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array('form' => $form);
    }
	
	public function editStudentQuestionsAction()
    {
		$this->loginDetails();
		
	   	//get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			
		}
		else {
			
		}
		
		$form = new FeedbackQuestionsForm();
		$pmsModel = new FeedbackQuestions();
		$form->bind($pmsModel);
		
		$question = $this->pmsService->findQuestion($id, $tableName = 'student_feedback_questions');
		$studentQuestions = $this->pmsService->listAll($tableName='student_feedback_questions');
        
        return array(
			'form' => $form,
			'question' => $question,
			'studentQuestions' => $studentQuestions);
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

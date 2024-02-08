<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace HrmPlan\Controller;

use HrmPlan\Service\HrmPlanServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use HrmPlan\Model\HrmPlan;
use HrmPlan\Model\HrmPlanApproval;
use HrmPlan\Form\HrmPlanForm;
use HrmPlan\Form\HrmPlanApprovalForm;
use HrmPlan\Form\SubmitProposalForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Helper\AbstractHelper;
use Zend\Session\Container;

class HrmPlanController extends AbstractActionController
{
   
   	protected $hrmPlanService;
	protected $notificationService;
    protected $auditTrailService;
	protected $serviceLocator;
	protected $username;
	protected $userrole;
	protected $usertype;
	protected $userregion;
	protected $userDetails;
	protected $employee_details_id;
	protected $organisation_id;
	protected $keyphrase = "RUB_IMS";
	
	public function __construct(HrmPlanServiceInterface $hrmPlanService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->hrmPlanService = $hrmPlanService;
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
		* Getting the student_id/employee_details_id related to username
		*/
		
		$empData = $this->hrmPlanService->getUserDetailsId($this->username, $tableName = 'employee_details');
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			}
		
		//get the organisation id
		$organisationID = $this->hrmPlanService->getOrganisationId($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}
		
		//get the user details such as name
		if($this->usertype == 1){
			$this->userDetails = $this->hrmPlanService->getUserDetails($this->username, $tableName = 'employee_details');
		}
		else if($this->usertype == 2){
			$this->userDetails = $this->hrmPlanService->getUserDetails($this->username, $tableName = 'student');

		}
		else {
			$this->userDetails = $this->hrmPlanService->getUserDetails($this->username, $tableName = 'job_applicant');
		}
	}
	
	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
    }
	
	public function hrmproposalAction()
    {
		$this->loginDetails();
		
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
		
		$form = new HrmPlanForm($dbAdapter);
		$hrmPlanModel = new HrmPlan();
		$form->bind($hrmPlanModel);
		
		$hrmProposals = $this->hrmPlanService->listAllProposals($status='Not Submitted', $this->organisation_id);
		$positionCategory = $this->hrmPlanService->listSelectData($tableName = 'position_category' , $columnName = 'category' , $organisation_id = NULL);
		$positionTitle = $this->hrmPlanService->listSelectData($tableName = 'position_title' , $columnName = 'position_title' , $organisation_id = NULL);
		$positionLevel = $this->hrmPlanService->listSelectData($tableName = 'position_level' , $columnName = 'position_level' , $organisation_id = NULL);
		$organisationList = $this->hrmPlanService->listSelectData($tableName = 'organisation' , $columnName = 'organisation_name' , $organisation_id = NULL);
		$departmentList = $this->hrmPlanService->listSelectData($tableName = 'departments' , $columnName = 'department_name' , $this->organisation_id);
		$five_year_plan = $this->hrmPlanService->getFiveYearPlan();
		$proposalDates = $this->hrmPlanService->getProposalDates('HRM Proposal');
		
		$message = NULL;
		
		$submitForm = new SubmitProposalForm();

         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 $data = $this->params()->fromPost();
                 try {
					 $this->hrmPlanService->save($hrmPlanModel, $data);
					 $this->flashMessenger()->addMessage('HRM Proposal was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "HRM Plan", "ALL", "SUCCESS");
					 return $this->redirect()->toRoute('hrmproposal');
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
			 'submitForm' => $submitForm,
			 'hrmProposals' => $hrmProposals,
			 'organisationList' => $organisationList,
			 'departmentList' => $departmentList,
			 'positionCategory' => $positionCategory,
			 'positionLevel' => $positionLevel,
			 'positionTitle' => $positionTitle,
			 'five_year_plan' => $five_year_plan,
			 'proposalDates' => $proposalDates,
			 'message' => $message,
			 'organisation_id' => $this->organisation_id
         );
    }
	
	public function viewHrmProposalAction()
    {
		$this->loginDetails();
		
        //get the id of the hrm proposal
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
			$proposalDetail = $this->hrmPlanService->findProposal($id);
					
			$form = new HrmPlanForm($dbAdapter);
			
			$hrmProposals = $this->hrmPlanService->listAllProposals($status='Not Submitted', $this->organisation_id);
			$organisationList = $this->hrmPlanService->listSelectData($tableName = 'organisation' , $columnName = 'organisation_name' , $organisation_id = NULL);
			
			 return array(
				 'form' => $form,
				 'keyphrase' => $this->keyphrase,
				 'proposalDetail' => $proposalDetail,
				 'hrmProposals' => $hrmProposals,
				 'organisationList' => $organisationList
			 );
		}
		else {
			return $this->redirect()->toRoute('updatehrmapprovedlist');
		}
    }
	
	public function editHrmProposalAction()
    {
		$this->loginDetails();
		
        //get the id of the hrm proposal
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$proposalDetail = $this->hrmPlanService->findProposal($id);
			$dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
			$form = new HrmPlanForm($dbAdapter);
			$hrmPlanModel = new HrmPlan();
			$form->bind($hrmPlanModel);
			
			$hrmProposals = $this->hrmPlanService->listAllProposals($status='Not Submitted', $this->organisation_id);
			$positionCategory = $this->hrmPlanService->listSelectData($tableName = 'position_category' , $columnName = 'category' , $organisation_id = NULL);
			$positionTitle = $this->hrmPlanService->listSelectData($tableName = 'position_title' , $columnName = 'position_title' , $organisation_id = NULL);
			$positionLevel = $this->hrmPlanService->listSelectData($tableName = 'position_level' , $columnName = 'position_level' , $organisation_id = NULL);
			$organisationList = $this->hrmPlanService->listSelectData($tableName = 'organisation' , $columnName = 'organisation_name' , $organisation_id = NULL);
			$departmentList = $this->hrmPlanService->listSelectData($tableName = 'departments' , $columnName = 'department_name' , $this->organisation_id);
			
			$submitForm = new SubmitProposalForm();
	
			 $request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 $data = $this->params()->fromPost();
					 try {
						 $this->hrmPlanService->save($hrmPlanModel, $data);
						 $this->flashMessenger()->addMessage('HRM Proposal was successfully edited');
						 return $this->redirect()->toRoute('hrmproposal');
					 }
					 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
	
			 return array(
				 'form' => $form,
				 'keyphrase' => $this->keyphrase,
				 'submitForm' => $submitForm,
				 'proposalDetail' => $proposalDetail,
				 'hrmProposals' => $hrmProposals,
				 'organisationList' => $organisationList,
				 'departmentList' => $departmentList,
				 'positionCategory' => $positionCategory,
				 'positionLevel' => $positionLevel,
				 'positionTitle' => $positionTitle,
				 'organisation_id' => $this->organisation_id
			 );
		}
		else {
			return $this->redirect()->toRoute('hrmproposal');
		}
    }
	
	public function deleteHrmProposalAction()
    {
		$this->loginDetails();
		
        //get the id of the hrm proposal
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
			$proposalDetail = $this->hrmPlanService->findProposal($id);
					
			$form = new HrmPlanForm($dbAdapter);
			
			$hrmProposals = $this->hrmPlanService->listAllProposals($status='Not Submitted', $this->organisation_id);
			$organisationList = $this->hrmPlanService->listSelectData($tableName = 'organisation' , $columnName = 'organisation_name' , $organisation_id = NULL);
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 try {
						 $this->hrmPlanService->deleteHrmProposal($id);
						 $this->flashMessenger()->addMessage('HRM Proposal was successfully deleted');
						 return $this->redirect()->toRoute('hrmproposal');
				 }
				 catch(\Exception $e) {
					 die($e->getMessage());
					 // Some DB Error happened, log it and let the user know
				 }
			 }
			
			 return array(
				 'form' => $form,
				 'proposalDetail' => $proposalDetail,
				 'hrmProposals' => $hrmProposals,
				 'organisationList' => $organisationList
			 );
		}
		else {
			return $this->redirect()->toRoute('hrmproposal');
		}
    }
	
	public function hrmapprovallistAction()
    {
		$this->loginDetails();
		
        $message = NULL;
		
		return new ViewModel(array(
			'approvals' => $this->hrmPlanService->listAllProposals($status='Pending', $this->organisation_id),
			'keyphrase' => $this->keyphrase,
			'message' => $message
			));
    } 
	
    public function hrmapprovedlistAction()
    {
		$this->loginDetails();
		
        return new ViewModel(array(
                'approvedList' => $this->hrmPlanService->listAllProposals($status='Approved', $this->organisation_id),
                'rejectedList' => $this->hrmPlanService->listAllProposals($status='Rejected', $this->organisation_id),
                'pendingList' => $this->hrmPlanService->listAllProposals($status='Pending', $this->organisation_id),
                ));
    }
	
	public function updatehrmapprovedlistAction()
    {
		$this->loginDetails();
		
       return new ViewModel(array(
			'approvals' => $this->hrmPlanService->listAllProposals($status='Pending', $this->organisation_id),
			'keyphrase' => $this->keyphrase
			));
    } 
	
	public function empworkforceproposalAction()
	{
		$this->loginDetails();
		
		//get the id of the hrm proposal
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$hrmProposal = $this->hrmPlanService->findProposal($id);
		
			$form = new HrmPlanApprovalForm();
			$hrmPlanModel = new HrmPlanApproval();
			$form->bind($hrmPlanModel);
	
			 $request = $this->getRequest();
			 if ($request->isPost()) {
				 
				 //the following set of code is to get the value from the submit buttons
				 $postData = $this->getRequest()->getPost();
				 foreach ($postData as $key => $value)
				 {
					 if($key == 'hrmplanapproval')
					 {
						 $hrmData = $value;
						 if(array_key_exists('approve', $hrmData))
							 $submitValue = 'Approved';
						 else 
							$submitValue = 'Rejected';
					 }
				 }
				 
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
						 $this->hrmPlanService->updateProposal($hrmPlanModel, $submitValue);
						 $this->flashMessenger()->addMessage('HRM Proposal was successfully '.$submitValue);
						 return $this->redirect()->toRoute('hrmapprovedlist');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			 
			 return array(
				 'form' => $form,
				 'hrmProposal' => $hrmProposal
			 );
		}
		else {
			return $this->redirect()->toRoute('hrmapprovedlist');
		}
	}
	
	public function updateHrmProposalAction()
	{
		$this->loginDetails();
		
		//Value 1 is change of status from "Not Submitted" to "Submitted to HR"
		//Value 2 is change of status from "Submitted to HR" to "Submitted to OVC"
		//need to take care of organisation as well
		
		$value = $this->params()->fromRoute('id', 0);
		
		if($value == 1){
			$status = 'Pending';
			$previousStatus = 'Not Submitted';
		}
		else {
			$status = 'Approved';
			$previousStatus = 'Submitted to OVC';
		}
	
		 try {
			 $this->hrmPlanService->updateHrmProposal($status, $previousStatus, $id = NULL, $this->organisation_id);
			 $this->flashMessenger()->addMessage('HRM Proposal was successfully submitted.');
			 return $this->redirect()->toRoute('hrmapprovedlist');
		 }
		 catch(\Exception $e) {
		 	$message = 'Failure';
		 	$this->flashMessenger()->addMessage($e->getMessage());
		 }  
		return array();		
	}
	
	public function approveHrmProposalAction()
	{
		$this->loginDetails();
				
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			try {
				 $this->hrmPlanService->updateHrmProposal($status='Approved', $previousStatus=NULL, $id, $this->organisation_id);
				 $this->flashMessenger()->addMessage('HRM Proposal was successfully approved');
				 return $this->redirect()->toRoute('hrmapprovallist');
			 }
			 catch(\Exception $e) {
					 die($e->getMessage());
					 // Some DB Error happened, log it and let the user know
			 }
			return array();
		}
		else {
			return $this->redirect()->toRoute('hrmapprovallist');
		}
	}
	
	public function rejectHrmProposalAction()
	{
		$this->loginDetails();
				
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			try {
				 $this->hrmPlanService->updateHrmProposal($status='Rejected', $previousStatus=NULL, $id, $this->organisation_id);
				 $this->flashMessenger()->addMessage('HRM Proposal was successfully rejected');
				 return $this->redirect()->toRoute('hrmapprovallist');
			 }
			 catch(\Exception $e) {
					 die($e->getMessage());
					 // Some DB Error happened, log it and let the user know
			 }
			return array();
		}
		else {
			return $this->redirect()->toRoute('hrmapprovallist');
		}
	}
	
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


<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace HrdPlan\Controller;

use HrdPlan\Service\HrdPlanServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use HrdPlan\Model\HrdPlan;
use HrdPlan\Model\HrdPlanApproval;
use HrdPlan\Form\HrdPlanForm;
use HrdPlan\Form\SearchForm;
use HrdPlan\Form\SubmitProposalForm;
use HrdPlan\Form\HrdPlanApprovalForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Helper\AbstractHelper;
use Zend\Session\Container;


class HrdPlanController extends AbstractActionController
{
   
   	protected $hrdPlanService;
	protected $notificationService;
    protected $auditTrailService;
	protected $username;
	protected $userrole;
	protected $usertype;
	protected $userregion;
	protected $userDetails;
	protected $employee_details_id;
	protected $organisation_id;
	protected $keyphrase = "RUB_IMS";
	
	public function __construct(HrdPlanServiceInterface $hrdPlanService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->hrdPlanService = $hrdPlanService;
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
		
		/*
		* Getting the student_id/employee_details_id related to username
		*/
		
		$empData = $this->hrdPlanService->getUserDetailsId($this->username, $tableName = 'employee_details');
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			}
		
		//get the organisation id
		$organisationID = $this->hrdPlanService->getOrganisationId($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}
		
		//get the user details such as name
		if($this->usertype == 1){
			$this->userDetails = $this->hrdPlanService->getUserDetails($this->username, $tableName = 'employee_details');
		}
		else if($this->usertype == 2){
			$this->userDetails = $this->hrdPlanService->getUserDetails($this->username, $tableName = 'student');

		}
		else {
			$this->userDetails = $this->hrdPlanService->getUserDetails($this->username, $tableName = 'job_applicant');
		}

	}
	
	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
    }
	
	public function hrdproposalAction()
    {
		$this->loginDetails();
		
        $form = new HrdPlanForm();
		$hrdPlanModel = new HrdPlan();
		$form->bind($hrdPlanModel);
				
		$submitForm = new SubmitProposalForm();
		$message = NULL;
		
		$approvals = $this->hrdPlanService->listAllProposals($status='Not Submitted', $this->organisation_id);
		$organisationList = $this->hrdPlanService->listSelectData($tableName = 'organisation' , $columnName = 'organisation_name' , $organisation_id = NULL);
		$fundingType = $this->hrdPlanService->listSelectData($tableName = 'funding_category' , $columnName = 'funding_type' , $organisation_id = NULL);
		$trainingType = $this->hrdPlanService->listSelectData($tableName = 'training_types' , $columnName = 'training_type' , $organisation_id = NULL);
		$five_year_plan = $this->hrdPlanService->getFiveYearPlan();
		$proposalDates = $this->hrdPlanService->getProposalDates('HRD Proposal');

         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->hrdPlanService->save($hrdPlanModel);
					 $this->flashMessenger()->addMessage('HRD Proposal was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "HRD Plan", "ALL", "SUCCESS");
					 return $this->redirect()->toRoute('hrdproposal');
				 }
				 catch(\Exception $e) {
					 $message = 'Failure';
					 $this->flashMessenger()->addMessage($e->getMessage());
					 die($e->getMessage());
					 // Some DB Error happened, log it and let the user know
				 }
             }
         }

         return array(
             'form' => $form,
			 'keyphrase' => $this->keyphrase,
			 'organisation_id' => $this->organisation_id,
			 'submitForm' => $submitForm,
			 'approvals' => $approvals,
			 'five_year_plan' => $five_year_plan,
			 'organisationList' => $organisationList,
			 'fundingType' => $fundingType,
			 'trainingType' => $trainingType,
			 'proposalDates' => $proposalDates,
			 'message' => $message
         );
    }
	
	public function viewHrdProposalAction()
    {
		$this->loginDetails();
		
        //get the id of the hrd proposal
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$hrdProposal = $this->hrdPlanService->findProposal($id);
		
			$form = new HrdPlanApprovalForm();
	
			 return array(
				 'form' => $form,
				 'hrdProposal' => $hrdProposal
			 );
		}
		else{
			return $this->redirect()->toRoute('hrdproposal');
		}
    }
	
	public function editHrdProposalAction()
    {
		$this->loginDetails();
		
		//get the id of the hrd proposal
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new HrdPlanForm();
			$hrdPlanModel = new HrdPlan();
			$form->bind($hrdPlanModel);
					
			$submitForm = new SubmitProposalForm();
			
			$hrdProposal = $this->hrdPlanService->findProposal($id);
			$organisationList = $this->hrdPlanService->listSelectData($tableName = 'organisation' , $columnName = 'organisation_name' , $organisation_id = NULL);
			$fundingType = $this->hrdPlanService->listSelectData($tableName = 'funding_category' , $columnName = 'funding_type' , $organisation_id = NULL);
			$trainingType = $this->hrdPlanService->listSelectData($tableName = 'training_types' , $columnName = 'training_type' , $organisation_id = NULL);
			$five_year_plan = $this->hrdPlanService->getFiveYearPlan();
	
			 $request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
						 $this->hrdPlanService->save($hrdPlanModel);
						 $this->flashMessenger()->addMessage('HRD Proposal was successfully edited');
						 $this->auditTrailService->saveAuditTrail("EDIT", "HRD Plan", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('hrdproposal');
					 }
					 catch(\Exception $e) {
						 $message = 'Failure';
						 $this->flashMessenger()->addMessage($e->getMessage());
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
	
			 return array(
				 'form' => $form,
				 'organisation_id' => $this->organisation_id,
				 'submitForm' => $submitForm,
				 'hrdProposal' => $hrdProposal,
				 'five_year_plan' => $five_year_plan,
				 'organisationList' => $organisationList,
				 'fundingType' => $fundingType,
				 'trainingType' => $trainingType
			 );
		}
		else {
			return $this->redirect()->toRoute('hrdproposal');
		}
    }
	
	public function deleteHrdProposalAction()
    {
		$this->loginDetails();
		
        //get the id of the hrd proposal
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$hrdProposal = $this->hrdPlanService->findProposal($id);
		
			$form = new HrdPlanApprovalForm();
			$hrdPlanModel = new HrdPlanApproval();
			$form->bind($hrdPlanModel);
	
			 $request = $this->getRequest();
			 if ($request->isPost()) {
				 //the following set of code is to get the value from the submit buttons
				 $postData = $this->getRequest()->getPost();
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
						 $this->hrdPlanService->save($hrdPlanModel);
						 $this->flashMessenger()->addMessage('HRD Proposal was successfully deleted');
						 $this->auditTrailService->saveAuditTrail("DELETE", "HRD Plan", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('hrdproposal');
					 }
					 catch(\Exception $e) {
						 $message = 'Failure';
						 $this->flashMessenger()->addMessage($e->getMessage());
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
	
			 return array(
				 'form' => $form,
				 'hrdProposal' => $hrdProposal);
		}
		else {
			return $this->redirect()->toRoute('hrdproposal');
		}
    }
	
	public function approveHrdProposalAction()
    {
		$this->loginDetails();
		
		//get the id of the hrd proposal
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			try {
				 $this->hrdPlanService->updateHrdProposal($status='Approved', $previousStatus=NULL, $id, $this->organisation_id);
				 $this->flashMessenger()->addMessage('HRD Proposal was successfully approved');
				 return $this->redirect()->toRoute('hrdapprovallist');
			 }
			 catch(\Exception $e) {
					 die($e->getMessage());
					 // Some DB Error happened, log it and let the user know
			 }
			return array();
		}
		else {
			return $this->redirect()->toRoute('hrdapprovallist');
		}
    }
	
	public function rejectHrdProposalAction()
    {
		$this->loginDetails();
		
		//get the id of the hrd proposal
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			try {
				 $this->hrdPlanService->updateHrdProposal($status='Rejected', $previousStatus=NULL, $id, $this->organisation_id);
				 $this->flashMessenger()->addMessage('HRD Proposal was successfully rejected');
				 return $this->redirect()->toRoute('hrdapprovallist');
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
			return $this->redirect()->toRoute('hrdapprovallist');
		}
    }
	
    public function hrdapprovallistAction()
    {
		$this->loginDetails();
		
        $message = NULL;
		$organisationList = $this->hrdPlanService->listSelectData('organisation' , 'organisation_name' , NULL);
                
		$form = new SearchForm();

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid()) {
					$organisation_id = $this->getRequest()->getPost('organisation_id');
					$approvals = $this->hrdPlanService->listAllProposals($status='Pending', $organisation_id);
			}
		}
		else{
			$approvals = $this->hrdPlanService->listAllProposals($status='Pending', NULL);
		}
		
		return new ViewModel(array(
            'form' => $form,
			'keyphrase' => $this->keyphrase,
			'approvals' => $approvals,
			'organisationList' => $organisationList,
			'message' => $message
			));
    }
	
	//the following functions (view, edit and delete) are for hrd proposals that are 
	// pending for approval. The functions allows the HRO at OVC/HQ to either edit, approve or delete
	// the proposals
	
	public function viewHrdApprovalAction()
    {
		$this->loginDetails();
		
        //get the id of the hrd proposal
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$hrdProposal = $this->hrdPlanService->findProposal($id);
		
			$form = new HrdPlanApprovalForm();
	
			 return array(
				 'form' => $form,
				 'hrdProposal' => $hrdProposal
			 );
		}
		else {
			return $this->redirect()->toRoute('hrdapprovallist');
		}
    }
	
	public function editHrdApprovalAction()
    {
		$this->loginDetails();
		
		//get the id of the hrd proposal
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new HrdPlanForm();
			$hrdPlanModel = new HrdPlan();
			$form->bind($hrdPlanModel);
					
			$submitForm = new SubmitProposalForm();
			
			$hrdProposal = $this->hrdPlanService->findProposal($id);
	
			 $request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
						 $this->hrdPlanService->save($hrdPlanModel);
						 $this->flashMessenger()->addMessage('HRD Proposal was successfully edited');
						 return $this->redirect()->toRoute('hrdproposal');
					 }
					 catch(\Exception $e) {
						 $message = 'Failure';
						 $this->flashMessenger()->addMessage($e->getMessage());
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
	
			 return array(
				 'form' => $form,
				 'keyphrase' => $this->keyphrase,
				 'organisation_id' => $this->organisation_id,
				 'submitForm' => $submitForm,
				 'hrdProposal' => $hrdProposal
			 );
		}
		else {
			return $this->redirect()->toRoute('hrdproposal');
		}	
    }
	
	public function deleteHrdApprovalAction()
    {
		$this->loginDetails();
		
        //get the id of the hrd proposal
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$hrdProposal = $this->hrdPlanService->findProposal($id);
		
			$form = new HrdPlanApprovalForm();
			$hrdPlanModel = new HrdPlanApproval();
			$form->bind($hrdPlanModel);
	
			 $request = $this->getRequest();
			 if ($request->isPost()) {
				 //the following set of code is to get the value from the submit buttons
				 $postData = $this->getRequest()->getPost();
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
						 $this->hrdPlanService->deleteProposal($id);
						 $this->flashMessenger()->addMessage('HRD Proposal was successfully deleted');
						 return $this->redirect()->toRoute('hrdproposal');
					 }
					 catch(\Exception $e) {
						 $message = 'Failure';
						 $this->flashMessenger()->addMessage($e->getMessage());
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
	
			 return array(
				 'form' => $form,
				 'hrdProposal' => $hrdProposal
			 );
		}
		else {
			return $this->redirect()->toRoute('hrdproposal');
		}	 
    }
    
	public function hrdapprovedlistAction()
    {
		$this->loginDetails();
		
        return new ViewModel(array(
			'approvedList' => $this->hrdPlanService->listAllProposals($status='Approved', $this->organisation_id),
			'rejectedList' => $this->hrdPlanService->listAllProposals($status='Rejected', $this->organisation_id),
			'pendingList' => $this->hrdPlanService->listAllProposals($status='Pending', $this->organisation_id),
			'keyphrase' => $this->keyphrase
			));
    }
	
	public function emphrdproposalfilledformAction()
	{
		$this->loginDetails();
		
		//get the id of the hrd proposal
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$hrdProposal = $this->hrdPlanService->findProposal($id);
		
			$form = new HrdPlanApprovalForm();
			$hrdPlanModel = new HrdPlanApproval();
			$form->bind($hrdPlanModel);
	
			 $request = $this->getRequest();
			 if ($request->isPost()) {
				 //the following set of code is to get the value from the submit buttons
				 $postData = $this->getRequest()->getPost();
				 foreach ($postData as $key => $value)
				 {
					 if($key == 'hrdplanapproval')
					 {
						 $hrdData = $value;
						 if(array_key_exists('approve', $hrdData))
							 $submitValue = 'Approved';
						 else 
							$submitValue = 'Rejected';
					 }
				 }
				 
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
						 $this->hrdPlanService->updateProposal($hrdPlanModel, $submitValue, $id);
						 $this->flashMessenger()->addMessage('HRD Proposal was successfully '.$submitValue);
						 return $this->redirect()->toRoute('hrdapprovedlist');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
	
			 return array(
				 'form' => $form,
				 'hrdProposal' => $hrdProposal
			 );
		}
		else {
			return $this->redirect()->toRoute('hrdapprovedlist');
		}		
	}
	
	/*
	* The action is to update the HRD Proposals
	* To submit the proposals to OVC from colleges
	*/
	
	public function updateHrdProposalAction()
	{
		$this->loginDetails();
		
		//Value 1 is change of status from "Not Submitted" to "Pending"
		//Value 2 is change of status from "Pending" to "Submitted to OVC"
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
			 $this->hrdPlanService->updateHrdProposal($status, $previousStatus, $id = NULL, $this->organisation_id);
			 $this->flashMessenger()->addMessage('HRD Proposal was successfully submitted');
			 return $this->redirect()->toRoute('hrdapprovedlist');
		 }
		 catch(\Exception $e) {
		 	$message = 'Failure';
		 	$this->flashMessenger()->addMessage($e->getMessage());
		 }
		return array();
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


<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace HrActivation\Controller;

use HrActivation\Service\HrActivationServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use HrActivation\Model\HrActivation;
use HrActivation\Form\HrActivationForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;


class HrActivationController extends AbstractActionController
{
   
   	protected $hrActivationService;
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
	
	public function __construct(HrActivationServiceInterface $hrActivationService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->hrActivationService = $hrActivationService;
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
		
		$empData = $this->hrActivationService->getUserDetailsId($this->username, $tableName = 'employee_details');
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			}
		
		//get the organisation id
		$organisationID = $this->hrActivationService->getOrganisationId($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}
		
		//get the user details such as name
		if($this->usertype == 1){
			$this->userDetails = $this->hrActivationService->getUserDetails($this->username, $tableName = 'employee_details');
		}
		else if($this->usertype == 2){
			$this->userDetails = $this->hrActivationService->getUserDetails($this->username, $tableName = 'student');

		}
		else {
			$this->userDetails = $this->hrActivationService->getUserDetails($this->username, $tableName = 'job_applicant');
		}

	}
	
	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
    }
	
	public function activateHrProposalAction()
    {
		$this->loginDetails();
		
        $form = new HrActivationForm();
		$hrActivationModel = new HrActivation();
		$form->bind($hrActivationModel);
				
		$activationDates = $this->hrActivationService->listAllActivationDates();
		$five_year_plan = $this->hrActivationService->getFiveYearPlan();
		$message = NULL;

         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->hrActivationService->save($hrActivationModel);
					 $this->flashMessenger()->addMessage('HR Proposal Activiation Date was added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "HR Activation", "ALL", "SUCCESS");
					 return $this->redirect()->toRoute('activatehrproposal');
				 }
				 catch(\Exception $e) {
					 $message = 'Failure';
					 $this->flashMessenger()->addMessage($e->getMessage());
					 die();
					 // Some DB Error happened, log it and let the user know
				 }
             }
         }

         return array(
             'form' => $form,
			 'keyphrase' => $this->keyphrase,
			 'activationDates' => $activationDates,
			 'five_year_plan' => $five_year_plan,
			 'message' => $message
         );
    }
		
	public function editActivationDateAction()
    {
		$this->loginDetails();
		
		//get the id of the hrd proposal
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new HrActivationForm();
			$hrActivationModel = new HrActivation();
			$form->bind($hrActivationModel);
					
			$activationDates = $this->hrActivationService->listAllActivationDates();
			$dateDetails = $this->hrActivationService->findActivationDate($id);
	
			 $request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					  try {
						 $this->hrActivationService->save($hrActivationModel);
						 $this->flashMessenger()->addMessage('HR Proposal Activiation Date was edited');
						 $this->auditTrailService->saveAuditTrail("EDIT", "HR Activation", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('activatehrproposal');
					 }
					 catch(\Exception $e) {
						 $message = 'Failure';
						 $this->flashMessenger()->addMessage($e->getMessage());
						 die();
						 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
	
			 return array(
				 'form' => $form,
				 'keyphrase' => $this->keyphrase,
				 'activationDates' => $activationDates,
				 'dateDetails' => $dateDetails
			 );
		}
		else {
			return $this->redirect()->toRoute('activatehrproposal');
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


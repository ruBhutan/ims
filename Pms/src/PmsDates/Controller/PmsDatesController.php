<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PmsDates\Controller;

use PmsDates\Service\PmsDatesServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use PmsDates\Form\PmsDatesForm;
use PmsDates\Model\PmsDates;
use Zend\Session\Container;

/**
 * Description of IndexController
 *
 */
 
class PmsDatesController extends AbstractActionController
{
	protected $pmsDatesService;
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
	protected $organisation_id;
	protected $role;
	protected $occupational_group;
	protected $keyphrase = "RUB_IMS";
	
	public function __construct(PmsDatesServiceInterface $pmsDatesService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->pmsDatesService = $pmsDatesService;
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
		
		$empData = $this->pmsDatesService->getUserDetailsId($this->username, $tableName = 'employee_details');
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			}

		//get the organisation id
		$organisationID = $this->pmsDatesService->getOrganisationId($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}
		
		//get the user details such as name
		$this->userDetails = $this->pmsDatesService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->pmsDatesService->getUserImage($this->username, $this->usertype);
	}
	
	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }
	
	public function addPmsDatesAction()
	{		
		$this->loginDetails();
		
        $form = new PmsDatesForm();
		$dateModel = new PmsDates();
		$form->bind($dateModel);
		
		$pms_dates = $this->pmsDatesService->listAll();
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->pmsDatesService->save($dateModel);
					 $this->flashMessenger()->addMessage('Dates was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "Dates for IWP were added", "ALL", "SUCCESS");
					 return $this->redirect()->toRoute('addpmsdates');
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
			'pms_dates' => $pms_dates);
	}
	
	public function editPmsDatesAction()
	{
		$this->loginDetails();
		
		//get the date id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new PmsDatesForm();
			$dateModel = new PmsDates();
			$form->bind($dateModel);
			
			$pms_dates = $this->pmsDatesService->listAll();
			$pms_details = $this->pmsDatesService->find($id);
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
						 $this->pmsDatesService->save($dateModel);
						 $this->flashMessenger()->addMessage('Dates was successfully Edited');
					 	$this->auditTrailService->saveAuditTrail("INSERT", "Dates for IWP were changed", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('addpmsdates');
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
				'pms_details' => $pms_details,
				'pms_dates' => $pms_dates);
		}
		else {
			return $this->redirect()->toRoute('addpmsdates');
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

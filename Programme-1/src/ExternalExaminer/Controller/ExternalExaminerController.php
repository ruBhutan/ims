<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ExternalExaminer\Controller;

use ExternalExaminer\Service\ExternalExaminerServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use ExternalExaminer\Model\ExternalExaminer;
use ExternalExaminer\Form\ExternalExaminerForm;
use ExternalExaminer\Form\SearchForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Zend\View\Model\JsonModel;
use Zend\Http\Response\Stream;
use Zend\Http\Headers;


class ExternalExaminerController extends AbstractActionController
{
   
   	protected $externalExaminerService;
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
	
	public function __construct(ExternalExaminerServiceInterface $externalExaminerService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->externalExaminerService = $externalExaminerService;
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
		
		$empData = $this->externalExaminerService->getUserDetailsId($this->username, $tableName = 'employee_details');
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			}
		
		//get the organisation id
		$organisationID = $this->externalExaminerService->getOrganisationId($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}

		//get the user details such as name
        
        $this->userDetails = $this->externalExaminerService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->externalExaminerService->getUserImage($this->username, $this->usertype);

	}

	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }

	
	public function addExternalExaminerAction()
    {
    	$this->loginDetails();		
		$form = new ExternalExaminerForm($this->serviceLocator);
		$externalExaminerModel = new ExternalExaminer();
		$form->bind($externalExaminerModel);
				
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
                 	$form_data = $this->getRequest()->getPost('programmes_id');
					 //$form_data['organisation_id'] = $this->getRequest()->getPost('organisation_id');
					 //$form_data['programmes_id'] = $this->getRequest()->getPost('programmes_id');
					 $this->externalExaminerService->save($externalExaminerModel, $form_data);
					 $this->auditTrailService->saveAuditTrail("INSERT", "External Examiners", "ALL", "SUCCESS");
					 $this->flashMessenger()->addMessage('External Examiner was added');
					 return $this->redirect()->toRoute('addexternalexaminer');
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
			 'message' => $message
         );
    }
		
	public function editExternalExaminerAction()
    {
    	$this->loginDetails();		
		//get the id of the hrd proposal
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){		
			$form = new ExternalExaminerForm($this->serviceLocator);
			$externalExaminerModel = new ExternalExaminer();
			$form->bind($externalExaminerModel);
					
			$message = NULL;
			$examinerDetails = $this->externalExaminerService->findExternalExaminer($id);
			$programmeList = $this->externalExaminerService->listSelectData('programmes','programme_name');

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
						// $form_data['organisation_id'] = $this->getRequest()->getPost('organisation_id');
						 $form_data = $this->getRequest()->getPost('programmes_id');
						 $this->externalExaminerService->updateExternalExaminer($externalExaminerModel, $form_data);
						 $this->auditTrailService->saveAuditTrail("EDIT", "External Examiners", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage('External Examiner was Successfully Edited');
						 return $this->redirect()->toRoute('listexternalexaminer');
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
				 'message' => $message,
				 'examinerDetails' => $examinerDetails,
				 'programmeList' => $programmeList
	         );
        }else{
        	$this->redirect()->toRoute('addexternalexaminer');
        }		
    }
	
	public function viewExternalExaminerAction()
	{
		$this->loginDetails();

		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$examinerDetails = $this->externalExaminerService->findExternalExaminer($id);

	         return array(
				 'examinerDetails' => $examinerDetails,
				 'keyphrase' => $this->keyphrase,
	         );
        }else{
        	$this->redirect()->toRoute('addexternalexaminer');
        }
	}
	
	public function listExternalExaminerAction()
	{
		$this->loginDetails();
		$form = new SearchForm($this->serviceLocator);
		
		$externalExaminers = $this->externalExaminerService->listExternalExaminers();
		$message = NULL;
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$data['organisation_id'] = $this->getRequest()->getPost('organisation_id');
				$data['programmes_id'] = $this->getRequest()->getPost('programmes_id');
				$externalExaminers = $this->externalExaminerService->getExternalExaminersList($data);
             }
         }

         return array(
			 'form' => $form,
			 'externalExaminers' => $externalExaminers,
			 'keyphrase' => $this->keyphrase,
			 'message' => $message
         );
	}


	public function downloadExternalExaminerFileAction()
	{
		$this->loginDetails();
        //get the student id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){ 
            $file = $this->externalExaminerService->getFileName($id);
        
            $response = new Stream();
            $response->setStream(fopen($file, 'r'));
            $response->setStatusCode(200);
            $response->setStreamName(basename($file));
            $headers = new Headers();
            $headers->addHeaders(array(
                'Content-Disposition' => 'attachment; filename="' . basename($file) .'"',
                'Content-Type' => 'application/octet-stream',
                'Content-Length' => filesize($file),
                'Expires' => '@0', // @0, because zf2 parses date as string to \DateTime() object
                'Cache-Control' => 'must-revalidate',
                'Pragma' => 'public'
            ));
            $response->setHeaders($headers);
            return $response;
        }
        else
        {
            $this->redirect()->toRoute('addempemployeetaskrecord');
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


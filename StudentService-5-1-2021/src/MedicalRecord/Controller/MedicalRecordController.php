<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MedicalRecord\Controller;

use MedicalRecord\Service\MedicalRecordServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use MedicalRecord\Form\MedicalRecordForm;
use MedicalRecord\Form\SearchForm;
use MedicalRecord\Model\MedicalRecord;
use Zend\Session\Container;

use Zend\View\Model\JsonModel;

use Zend\Http\Response\Stream;
use Zend\Http\Headers;

use DOMPDFModule\View\Model\PdfModel;

//AJAX
use Zend\Paginator\Adapter\DbSelect;
use Zend\Form\Element\Select;

/**
 * Description of IndexController
 *
 */
 
class MedicalRecordController extends AbstractActionController
{
	protected $recordService;
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
	
	public function __construct(MedicalRecordServiceInterface $recordService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->recordService = $recordService;
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
		
		$empData = $this->recordService->getUserDetailsId($this->username);
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			}
		
		//get the organisation id
		$organisationID = $this->recordService->getOrganisationId($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}

		//get the user details such as name
        $this->userDetails = $this->recordService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->recordService->getUserImage($this->username, $this->usertype);
	}

	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }
    
	public function studentMedicalRecordAction()
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
				$studentList = $this->recordService->getStudentList($studentName, $studentId, $programme, $this->organisation_id);
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
	
	public function addMedicalRecordAction()
    {
    	$this->loginDetails();
        //get the student id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new MedicalRecordForm();
			$recordModel = new MedicalRecord();
			$form->bind($recordModel);
			
			$studentDetail = $this->recordService->getStudentDetails($id);

			$programme_list = $this->recordService->listSelectData('programmes', 'programme_name');
	        
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
						 $this->recordService->save($recordModel);
						 $this->notificationService->saveNotification('Medical Record', $id, 'ALL', 'Student Medical Record');
						 $this->auditTrailService->saveAuditTrail("INSERT", "Student Medical Records", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage('You have successfully added student medical record.');
						 return $this->redirect()->toRoute('viewstdmedicalrecord');
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
				'programme_list' => $programme_list,
			);
        }else{
        	$this->redirect()->toRoute('stdmedicalrecord');
        }
    } 
    
	public function viewMedicalRecordAction()
    {
    	$this->loginDetails();
        $form = new MedicalRecordForm();
		$searchForm = new SearchForm();

		$message = NULL;

		$request = $this->getRequest();
         if ($request->isPost()) {
             $searchForm->setData($request->getPost());
             if ($searchForm->isValid()) {
				$studentName = $this->getRequest()->getPost('student_name');
				$studentId = $this->getRequest()->getPost('student_id');
				$programme = $this->getRequest()->getPost('programme');
				$medicalRecords = $this->recordService->getStudentMedicalRecords($studentName, $studentId, $programme, $this->organisation_id);
             }
         }
		 else {
			 $medicalRecords = $this->recordService->listMedicalRecords($this->organisation_id);
		 }
		
        return array(
				'form' => $form,
				'searchForm' => $searchForm,
				'medicalRecords' => $medicalRecords,
				'keyphrase' => $this->keyphrase,
				'message' => $message,
			);
    }
    
	public function editMedicalRecordAction()
    {
    	$this->loginDetails();
       //get the student id
		$id_from_route = $this->params()->fromRoute('id');
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new MedicalRecordForm();
			$recordModel = new MedicalRecord();
			$form->bind($recordModel);

			$studentMedicalDetails = $this->recordService->getMedicalRecordedDetails($id);
	        
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
						 $this->recordService->save($recordModel);
						 $this->notificationService->saveNotification('Medical Record', $id, 'ALL', 'Student Medical Record');
						 $this->auditTrailService->saveAuditTrail("UPDATE", "Student Medical Records", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage('You have successfully edited student medical record.');
						 return $this->redirect()->toRoute('viewstdmedicalrecord');
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
				'studentMedicalDetails' => $studentMedicalDetails,
			);
        }else{
        	$this->redirect()->toRoute('viewstdmedicalrecord');
        }
    }
	
	public function viewIndividualMedicalRecordAction()
	{
		$this->loginDetails();
		//get the student id
		$id_from_route = $this->params()->fromRoute('id');
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
        if(is_numeric($id)){
        	$form = new MedicalRecordForm();
		
			$student_detail_tmp = $this->recordService->getStudentDetails($id);
			$studentDetail = array();
			foreach($student_detail_tmp as $tmp){
				$studentDetail = $tmp;
			}
			$studentRecords = $this->recordService->getIndividualMedicalRecords($studentDetail['id']);
	        		 
	        return array(
				'form' => $form,
				'studentRecords' => $studentRecords, 
				'keyphrase' => $this->keyphrase,
			);
        }else{
        	$this->redirect()->toRoute('viewstdmedicalrecord');
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

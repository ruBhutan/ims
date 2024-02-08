<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace StudentImage\Controller;

use StudentImage\Service\StudentImageServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use StudentImage\Form\StudentProfilePictureForm;
use StudentImage\Form\SearchForm;
use StudentImage\Model\StudentProfilePicture;
use Zend\Session\Container;

/**
 * Description of IndexController
 *
 */
 
class StudentImageController extends AbstractActionController
{
	protected $studentImageService;
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

	protected $keyphrase = "RUB_IMS";
	
	public function __construct(StudentImageServiceInterface $studentImageService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->studentImageService = $studentImageService;
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
		
		$empData = $this->studentImageService->getUserDetailsId($this->username);
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			}

		//get the organisation id
		$organisationID = $this->studentImageService->getOrganisationId($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}

		//get the user details such as name
        $this->userDetails = $this->studentImageService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->studentImageService->getUserImage($this->username, $this->usertype);

	}

	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }
	
	public function studentProfilePictureAction()
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
				$studentList = $this->studentImageService->getStudentList($studentName, $studentId, $programme, $this->organisation_id);
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
    
	public function addStudentProfilePictureAction()
	{
		$this->loginDetails();
		
		//get the id from the route
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new StudentProfilePictureForm();
			$studentModel = new StudentProfilePicture();
			$form->bind($studentModel); 

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
						 $this->studentImageService->saveStudentProfilePicture($studentModel);
						 $this->flashMessenger()->addMessage('Student Profile was successfully uploaded');
						 $this->auditTrailService->saveAuditTrail("INSERT/UPDATE", "Student", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('addstudentprofilepicture', array('id' => $this->my_encrypt($id, $this->keyphrase)));
					 }
					 catch(\Exception $e) {
					 	$message = 'Failure';
					 	$this->flashMessenger()->addMessage($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return new ViewModel(array(
				'id' => $id,
				'form' => $form,
				'studentDetails' => $this->studentImageService->findStudent($id, NULL),
				'profilePicture' => $this->studentImageService->getStudentProfilePicture($id),
				'message' => $message,
				'keyphrase' => $this->keyphrase,
				));
		}
		else {
			return $this->redirect()->toRoute('studentprofilepicture');
		}
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

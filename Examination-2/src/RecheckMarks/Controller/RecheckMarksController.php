<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RecheckMarks\Controller;

use Zend\ServiceManager\ServiceLocatorInterface;
use RecheckMarks\Service\RecheckMarksServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use RecheckMarks\Model\RecheckMarks;
use RecheckMarks\Form\RecheckMarksForm;
use RecheckMarks\Form\UpdateRecheckMarksForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;

/**
 * Description of IndexController
 *
 * @author Mendrel
 */
class RecheckMarksController extends AbstractActionController
{
    
	protected $recheckService;
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
	protected $student_id;

	protected $keyphrase = "RUB_IMS";
	
	public function __construct(RecheckMarksServiceInterface $recheckService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->recheckService = $recheckService;
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
		* Getting the employee_details_id related to username
		*/
		if($this->usertype = '1'){
			$employee = $this->recheckService->getUserDetailsId($this->username);
			foreach($employee as $emp){
				$this->employee_details_id = $emp['id'];
				$this->organisation_id = $emp['organisation_id'];
			}	
		}
		
		/*
		* Getting the student id related to username
		*/
		if($this->usertype = '2'){
			$student = $this->recheckService->getStudentId($this->username);
			foreach($student as $std){
				$this->student_id = $std['id'];
				$this->organisation_id = $std['organisation_id'];
			}		
		}		
		
	 //get the user details such as name
        $this->userDetails = $this->recheckService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->recheckService->getUserImage($this->username, $this->usertype);
	}

    public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }
	
	public function applyRecheckAction()
	{
		$this->loginDetails();

		$form = new RecheckMarksForm();
		$marksModel = new RecheckMarks();
		$form->bind($marksModel);
		
		$message = NULL;
		$studentDetails = $this->recheckService->getStudentDetails($this->student_id, 'self');
		$modulesList = $this->recheckService->getAcademicModules($this->student_id);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) { 
				$data = $this->params()->fromPost();
				$academic_modules_allocation_id = $data['recheckmarks']['academic_modules_allocation_id'];
				$student_id = $data['recheckmarks']['student_id']; 
				$type = $data['recheckmarks']['type'];
				$check_recheck_application = $this->recheckService->crossCheckModuleRecheckApplication($academic_modules_allocation_id, $student_id, $type);
				$recheck_application_details = array();
				foreach($check_recheck_application as $details){
					$recheck_application_details = $details;
				}
				if($recheck_application_details['id'] && $recheck_application_details['type'] && $recheck_application_details['payment_status'] == 'Payment Awaiting' && $recheck_application_details['recheck_status'] == 'Pending'){
					$message = 'Failure';
					$this->flashMessenger()->addMessage("You can't apply for recheck since you have already applied and your payment status is still ".$recheck_application_details['payment_status']." and recheck status is ".$recheck_application_details['recheck_status']);
				}
				else if($recheck_application_details['id'] && $recheck_application_details['type'] && $recheck_application_details['payment_status'] == 'Payment Updated' && $recheck_application_details['recheck_status'] == 'Pending'){
					$message = 'Failure';
					$this->flashMessenger()->addMessage("You can't apply for recheck since you have already applied and your payment status is still ".$recheck_application_details['payment_status']." and recheck status is ".$recheck_application_details['recheck_status']);
				}
				else{
					try {
						$this->recheckService->saveRecheckApplication($marksModel);
						$this->notificationService->saveNotification('Apply for Recheck of Marks', $this->student_id, 'NULL', 'Recheck Application');
						$this->auditTrailService->saveAuditTrail("INSERT", "Student Recheck Marks", "ALL", "SUCCESS");
						$this->flashMessenger()->addMessage('You have successfully applied for recheck or re-evaluation');
						return $this->redirect()->toRoute('applyforrecheck');
					}
					catch(\Exception $e) {
					   die($e->getMessage());
							// Some DB Error happened, log it and let the user know
					}
				}
             }
         }
		 				
		return new ViewModel(array(
			'form' => $form,
			'student_id' => $this->student_id,
			'studentDetails' => $studentDetails,
			'moduleList' => $modulesList,
			'recheckList' => $this->recheckService->getRecheckList($this->student_id),
			'message' => $message,
			'keyphrase' => $this->keyphrase
			));
	}
	
	public function listRecheckApplicantsAction()
	{
		$this->loginDetails();

		$message = NULL;

		$tmp_data = array();

		$recheckList = $this->recheckService->listRecheckApplicants($this->organisation_id);

		$recheck_list_array = $this->recheckService->listRecheckApplicants($this->organisation_id);
		foreach($recheck_list_array as $tmp){
            $tmp_data[] = $tmp['id'];
		}
		//var_dump($tmp_data); die();

		$recheckForm = new UpdateRecheckMarksForm($tmp_data);

		return new ViewModel(array(
			'recheckForm' => $recheckForm,
			'student_id' => $this->student_id,
			'recheckList' => $recheckList,
			'keyphrase' => $this->keyphrase,
			'message' => $message
			));
	}


	// Record the student section into student_semester_registration at first time
    public function updateRecheckMarksStatusAction()
    {
		$this->loginDetails();
		$recheck_list_array = $this->recheckService->listRecheckApplicants($this->organisation_id);
		foreach($recheck_list_array as $tmp){
			$tmp_data[] = $tmp['id'];
		}
		$form = new UpdateRecheckMarksForm($tmp_data);
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid()) { 

				$data_to_insert = $this->extractFormData($tmp_data, 'payment_remarks'); 
				try {
					$this->recheckService->updateRecheckMarksStatus($data_to_insert, $this->organisation_id, $this->employee_details_id);
					$this->auditTrailService->saveAuditTrail("UPDATE", "Student Recheck Marks", "ALL", "SUCCESS");

					$this->flashMessenger()->addMessage('Student Recheck Payment was successfully updated');
					return $this->redirect()->toRoute('listrecheckapplicants');
		} 
		catch(\Exception $e) {
				$message = 'Failure';
				$this->flashMessenger()->addMessage($e->getMessage());
				return $this->redirect()->toRoute('listrecheckapplicants');
				// Some DB Error happened, log it and let the user know
				}
			}
		}  
	}

	public function approvedRecheckApplicantsAction()
	{
		$this->loginDetails();

		$message = NULL;

		$tmp_data = array();

		$recheckList = $this->recheckService->listRecheckApplicants($this->organisation_id);

		$recheck_list_array = $this->recheckService->listRecheckApplicants($this->organisation_id);
		foreach($recheck_list_array as $tmp){
            $tmp_data[] = $tmp['id'];
		}
		//var_dump($tmp_data); die();

		$recheckForm = new UpdateRecheckMarksForm($tmp_data);

		return new ViewModel(array(
			'recheckForm' => $recheckForm,
			'student_id' => $this->student_id,
			'recheckList' => $recheckList,
			'keyphrase' => $this->keyphrase,
			'message' => $message
			));
	}

	// Record the student section into student_semester_registration at first time
    public function updateApprovedRecheckMarksStatusAction()
    {
		$this->loginDetails();
		$recheck_list_array = $this->recheckService->listRecheckApplicants($this->organisation_id);
		foreach($recheck_list_array as $tmp){
			$tmp_data[] = $tmp['id'];
		}
		$form = new UpdateRecheckMarksForm($tmp_data);
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid()) { 

				$data_to_insert = $this->extractFormData($tmp_data, 'recheck_remarks'); 
				try {
					$this->recheckService->updateApprovedRecheckMarksStatus($data_to_insert, $this->organisation_id, $this->employee_details_id);
					$this->auditTrailService->saveAuditTrail("UPDATE", "Student Recheck Marks", "ALL", "SUCCESS");

					$this->flashMessenger()->addMessage('Student Recheck Status was successfully updated');
					return $this->redirect()->toRoute('approvedrecheckapplicants');
		} 
		catch(\Exception $e) {
				$message = 'Failure';
				$this->flashMessenger()->addMessage($e->getMessage());
				return $this->redirect()->toRoute('approvedrecheckapplicants');
				// Some DB Error happened, log it and let the user know
				}
			}
		}  
	}

	public function recheckApplicationDetailsAction()
	{
		$this->loginDetails();
		
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){ 
			$studentDetails = $this->recheckService->getStudentDetails($id, 'recheck');
			$recheckApplicationDetails = $this->recheckService->getRecheckApplicationDetails($id);

			return array(
				'studentDetails' => $studentDetails,
				'recheckApplicationDetails' => $recheckApplicationDetails,
                );

		}else{
			return;
		}
	}
	
	public function extractFormData($data, $type)
    { 
        $recheckData = array();
        
        foreach($data as $key => $value) 
        {
			if($type == 'payment_remarks'){
				$recheckData[$value]= $this->getRequest()->getPost('payment_remarks_'.$value);
			}else{
				$recheckData[$value]= $this->getRequest()->getPost('recheck_remarks_'.$value);
			}
        }
        return $recheckData;
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

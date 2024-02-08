<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Reassessment\Controller;

use Zend\ServiceManager\ServiceLocatorInterface;
use Reassessment\Service\ReassessmentServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Reassessment\Model\Reassessment;
use Reassessment\Form\ReassessmentForm;
use Reassessment\Form\UpdateReassessmentModuleForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;

/**
 * Description of IndexController
 *
 * @author Mendrel
 */
class ReassessmentController extends AbstractActionController
{
    
	protected $reassessmentService;
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
	
	public function __construct(ReassessmentServiceInterface $reassessmentService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->reassessmentService = $reassessmentService;
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
		if($this->usertype == '1'){
			$employee = $this->reassessmentService->getUserDetailsId($this->username);
			foreach($employee as $emp){
				$this->employee_details_id = $emp['id'];
				$this->organisation_id = $emp['organisation_id'];
				}
		}
		
		/*
		* Getting the student id related to username
		*/
		if($this->usertype == '2'){
			$student = $this->reassessmentService->getStudentDetailsId($this->username);
			foreach($student as $std){
				$this->student_id = $std['id'];
				$this->organisation_id = $std['organisation_id'];
				}
		}

			//get the user details such as name
        $this->userDetails = $this->reassessmentService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->reassessmentService->getUserImage($this->username, $this->usertype);
				
	}

	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
    }
	
	public function applyReassessmentAction()
	{
		$this->loginDetails();
		$form = new ReassessmentForm();
		$marksModel = new Reassessment();
		$form->bind($marksModel);
		
		$message = NULL;
		$studentDetails = $this->reassessmentService->getStudentDetails($this->student_id, 'self');
		$modulesList = $this->reassessmentService->getAcademicModules($this->student_id);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) { 
				$data = $this->params()->fromPost();
				$academic_modules_allocation_id = $data['reassessment']['academic_modules_allocation_id'];
				$student_id = $data['reassessment']['student_id']; 

				$check_reassessment_application = $this->reassessmentService->crossCheckModuleReassessmentApplication($academic_modules_allocation_id, $student_id);
				$reassessment_application_details = array();
				foreach($check_reassessment_application as $details){
					$reassessment_application_details = $details;
				}

				if($reassessment_application_details['id']) {
					if($reassessment_application_details['id'] && $reassessment_application_details['payment_status'] == 'Payment Awaiting' && $reassessment_application_details['reassessment_status'] == 'Pending'){
					$message = 'Failure';
					$this->flashMessenger()->addMessage("You can't apply for reassessment since you have already applied and your payment status is ".$reassessment_application_details['payment_status']." or reassessment status is ".$reassessment_application_details['reassessment_status']);
					}
					else if($reassessment_application_details['id'] && $reassessment_application_details['payment_status'] == 'Payment Updated' && $reassessment_application_details['reassessment_status'] == 'Pending'){
					$message = 'Failure';
					$this->flashMessenger()->addMessage("You can't apply for reassessment since you have already applied and your payment status is ".$reassessment_application_details['payment_status']." or reassessment status is ".$reassessment_application_details['reassessment_status']);
					} else {
						$message = 'Failure';
						$this->flashMessenger()->addMessage("You can't apply for reassessment since you have already applied");
					}
				} else {
					try {
						$this->reassessmentService->saveReassessmentApplication($marksModel);
						$this->notificationService->saveNotification('Apply for Reassessment of Marks', $this->student_id, 'NULL', 'Reassessment Application');
						$this->auditTrailService->saveAuditTrail("INSERT", "Student Reassessment Module", "ALL", "SUCCESS");
						$this->flashMessenger()->addMessage('You have successfully applied reassessment of module');
						return $this->redirect()->toRoute('applyreassessment');
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
			'studentDetails' => $studentDetails,
			'moduleList' => $modulesList,
			'student_id' => $this->student_id,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
			'reassessmentList' => $this->reassessmentService->getReassessmentList($this->student_id),
			));
	}
	
	public function listReassessmentApplicantsAction()
	{
		$this->loginDetails();
		
		$message = NULL;
		$tmp_data = array();

		$assessmentList = $this->reassessmentService->listReassessmentApplicants($this->organisation_id);
		$reassessment_list_array = $this->reassessmentService->listReassessmentApplicants($this->organisation_id);
		foreach($reassessment_list_array as $tmp){
            $tmp_data[] = $tmp['id'];
		}
		
		$reassessmentForm = new UpdateReassessmentModuleForm($tmp_data);
								
		return new ViewModel(array(
			'reassessmentForm' => $reassessmentForm,
			'student_id' => $this->student_id,
			'assessmentList' => $assessmentList,
			'keyphrase' => $this->keyphrase,
			'message' => $message
			));
	}


	// Record the student section into student_semester_registration at first time
    public function updateReassessmentModuleStatusAction()
    {
		$this->loginDetails();
		$reassessment_list_array = $this->reassessmentService->listReassessmentApplicants($this->organisation_id);
		foreach($reassessment_list_array as $tmp){
			$tmp_data[] = $tmp['id'];
		}
		$form = new UpdateReassessmentModuleForm($tmp_data);
		
		$request = $this->getRequest();
		if ($request->isPost()) { 
			$form->setData($request->getPost());
			if ($form->isValid()) { 
				$data_to_insert = $this->extractFormData($tmp_data, 'payment_remarks'); 
				try {
					$this->reassessmentService->updateReassessmentModuleStatus($data_to_insert, $this->organisation_id, $this->employee_details_id);
					$this->auditTrailService->saveAuditTrail("UPDATE", "Student Reassessment Module", "ALL", "SUCCESS");
					$this->flashMessenger()->addMessage('Student Reassessment Payment was successfully updated');
					return $this->redirect()->toRoute('listreassessmentapplicants');
		} 
		catch(\Exception $e) {
				$message = 'Failure';
				$this->flashMessenger()->addMessage($e->getMessage());
				return $this->redirect()->toRoute('listreassessmentapplicants');
				// Some DB Error happened, log it and let the user know
				}
			}
		} 
		return new ViewModel(array(
			'form' => $form,
			));
	}


	public function approvedReassessmentApplicantsAction()
	{
		$this->loginDetails();

		$message = NULL;

		$tmp_data = array();

		$assessmentList = $this->reassessmentService->listReassessmentApplicants($this->organisation_id);

		$reassessment_list_array = $this->reassessmentService->listReassessmentApplicants($this->organisation_id);
		foreach($reassessment_list_array as $tmp){
            $tmp_data[] = $tmp['id'];
		}
		//var_dump($tmp_data); die();

		$reassessmentForm = new UpdateReassessmentModuleForm($tmp_data);

		return new ViewModel(array(
			'reassessmentForm' => $reassessmentForm,
			'student_id' => $this->student_id,
			'assessmentList' => $assessmentList,
			'keyphrase' => $this->keyphrase,
			'message' => $message
			));
	}


	public function updateApprovedReassessmentModuleStatusAction()
    {
		$this->loginDetails();
		$reassessment_list_array = $this->reassessmentService->listReassessmentApplicants($this->organisation_id);
		foreach($reassessment_list_array as $tmp){
			$tmp_data[] = $tmp['id'];
		}
		$form = new UpdateReassessmentModuleForm($tmp_data);
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid()) { 

				$data_to_insert = $this->extractFormData($tmp_data, 'reassessment_remarks'); 
				try {
					$this->reassessmentService->updateApprovedReassessmentModuleStatus($data_to_insert, $this->organisation_id, $this->employee_details_id);
					$this->auditTrailService->saveAuditTrail("UPDATE", "Student Reassessment Module", "ALL", "SUCCESS");

					$this->flashMessenger()->addMessage('Student Reassessment Status was successfully updated');
					return $this->redirect()->toRoute('approvedreassessmentapplicants');
		} 
		catch(\Exception $e) {
				$message = 'Failure';
				$this->flashMessenger()->addMessage($e->getMessage());
				return $this->redirect()->toRoute('approvedreassessmentapplicants');
				// Some DB Error happened, log it and let the user know
				}
			}
		}  
	}


	public function reassessmentApplicationDetailsAction()
	{
		$this->loginDetails();
		
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){ 
			$studentDetails = $this->reassessmentService->getStudentDetails($id, 'reassessment');
			$reassessmentApplicationDetails = $this->reassessmentService->getReassessmentApplicationDetails($id);

			return array(
				'studentDetails' => $studentDetails,
				'reassessmentApplicationDetails' => $reassessmentApplicationDetails,
                );

		}else{
			return;
		}
	}


	public function extractFormData($data, $type)
    { 
        $reassessmentData = array();
        
        foreach($data as $key => $value) 
        {
			if($type == 'payment_remarks'){
				$reassessmentData[$value]= $this->getRequest()->getPost('payment_remarks_'.$value);
			}else{
				$reassessmentData[$value]= $this->getRequest()->getPost('reassessment_remarks_'.$value);
			}
        }
        return $reassessmentData;
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

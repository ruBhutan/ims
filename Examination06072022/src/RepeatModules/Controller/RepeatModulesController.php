<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RepeatModules\Controller;

use Zend\ServiceManager\ServiceLocatorInterface;
use RepeatModules\Service\RepeatModulesServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use RepeatModules\Model\RepeatModules;
use RepeatModules\Form\RepeatModulesForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;

/**
 * Description of IndexController
 *
 * @author Mendrel
 */
class RepeatModulesController extends AbstractActionController
{
    
	protected $repeatModulesService;
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
	
	public function __construct(RepeatModulesServiceInterface $repeatModulesService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->repeatModulesService = $repeatModulesService;
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
			$employee = $this->repeatModulesService->getUserDetailsId($this->username);
			foreach($employee as $emp){
				$this->employee_details_id = $emp['id'];
				$this->organisation_id = $emp['organisation_id'];
			}
		}
		
		/*
		* Getting the student id related to username
		*/
		else if($this->usertype == '2'){
			$student = $this->repeatModulesService->getStudentId($this->username);
			foreach($student as $std){
				$this->student_id = $std['id'];
				$this->organisation_id = $std['organisation_id'];
			}
		}
		

			//get the user details such as name
        $this->userDetails = $this->repeatModulesService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->repeatModulesService->getUserImage($this->username, $this->usertype);
	}


	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }
	

	
	public function applyRepeatModuleAction()
	{ 
		$this->loginDetails();

		$form = new RepeatModulesForm();
		$marksModel = new RepeatModules();
		$form->bind($marksModel);
		
		$message = NULL;
		$studentDetails = $this->repeatModulesService->getStudentDetails($this->student_id);

		$moduleList = $this->repeatModulesService->listEligibleRepeatModuleList($this->student_id);

		$duration = $this->repeatModulesService->getModuleRepeatRegistrationDuration($this->organisation_id);

		$registered_repeat_module_list = $this->repeatModulesService->listRegisteredRepeatModules($this->student_id, $this->organisation_id, 'student');
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->repeatModulesService->save($marksModel);
					 $this->notificationService->saveNotification('Apply for Repeat Module', $this->student_id, 'NULL', 'Repeat Module Application');
                     $this->auditTrailService->saveAuditTrail("INSERT", "Student Repeat Modules", "ALL", "SUCCESS");
					 $this->flashMessenger()->addMessage('You have successfully applied for repeat module');
					 return $this->redirect()->toRoute('applyrepeatmodule');
				 }
				 catch(\Exception $e) {
					die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
						
		return new ViewModel(array(
			'form' => $form,
			'moduleList' => $moduleList,
			'registered_repeat_module_list' => $registered_repeat_module_list,
			'student_id' => $this->student_id,
			'studentDetails' => $studentDetails,
			'duration' => $duration,
			'message' => $message
			));
	}
	
	public function listRepeatModuleApplicantsAction()
	{
		$this->loginDetails();
		$message = NULL;

		$registered_repeat_module_list = $this->repeatModulesService->listRegisteredRepeatModules($this->student_id, $this->organisation_id, 'staff');		
								
		return new ViewModel(array(
			'registered_repeat_module_list' => $registered_repeat_module_list,
			'message' => $message
			));
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

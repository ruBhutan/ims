<?php


namespace AcademicAllocation\Controller;

use AcademicAllocation\Service\AcademicAllocationServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use AcademicAllocation\Form\AllocatedAssessmentDetailsForm;

//Session
use Zend\Session\Container;
//AJAX
use Zend\Http\Response\Stream;
use Zend\Http\Headers;
use Zend\Paginator\Adapter\DbSelect;
use Zend\View\Model\JsonModel;
use Zend\Form\Element\Select;

 
  
class AcademicAllocationController extends AbstractActionController
{
    protected $academicAllocationService;
    protected $notificationService;
    protected $auditTrailService;
    protected $serviceLocator;
    protected $username;
    protected $userrole;
    protected $userregion;
    protected $userDetails;
    protected $userImage;
    protected $employee_details_id;
    protected $organisation_id;
    protected $usertype;

    protected $keyphrase = "RUB_IMS";

	
	public function __construct(AcademicAllocationServiceInterface $academicAllocationService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->academicAllocationService = $academicAllocationService;
        $this->notificationService = $notificationService;
        $this->auditTrailService = $auditTrailService;
        $this->serviceLocator = $serviceLocator;

        /*
         * To retrieve the user name from the session
        */
        $authPlugin = $serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
        $this->username = $authPlugin['username'];
        $this->userrole = $authPlugin['role'];
        $this->usertype = $authPlugin['user_type_id'];
        $this->userregion = $authPlugin['region'];

         /*
        * Getting the employee_details_id related to username
        */
        if($this->usertype == 1){
            $empData = $this->academicAllocationService->getUserDetailsId($tableName = 'employee_details', $this->username);
            foreach($empData as $emp){
                $this->employee_details_id = $emp['id'];
            }
        }else if($this->usertype == 2){
            $stdData = $this->academicAllocationService->getUserDetailsId($tableName = 'student', $this->username);
            foreach($stdData as $std){
                $this->student_id = $std['id'];
            }
        }        

        //get the organisation id
        if($this->usertype == 1){
            $organisationID = $this->academicAllocationService->getOrganisationId($tableName = 'employee_details', $this->username);
            foreach($organisationID as $organisation){
                $this->organisation_id = $organisation['organisation_id'];
            }
        }else if($this->usertype == 2){
            //get the organisation id
            $stdOrganisationID = $this->academicAllocationService->getOrganisationId($tableName = 'student', $this->username);
            foreach($stdOrganisationID as $organisation){
                $this->organisation_id = $organisation['organisation_id'];
            }
        }
        

        //get the user details such as name
        $this->userDetails = $this->academicAllocationService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->academicAllocationService->getUserImage($this->username, $this->usertype);
	}

    public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }


    /*This is action and route to view on index.html */
    public function allocatedModuleAssessmentComponentAction()
    {
        $this->loginDetails();

        $allocatedAssessmentComponent = $this->academicAllocationService->getAllocatedModuleAssessmentComponent($this->organisation_id);
        $message = NULL;

        return array(
            'keyphrase' => $this->keyphrase,
            'message' => $message,
            'organisation_id' => $this->organisation_id,
            'allocatedAssessmentComponent' => $allocatedAssessmentComponent,
        );
    }


    public function editModuleAssessmentComponentAction()
    {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);

		if(is_numeric($id)){ 
            $form = new AllocatedAssessmentDetailsForm(); 

            $assessmentComponentDetails = $this->academicAllocationService->getAllocatedAssessmmentComponentDetail($id);
            
            $message = NULL;
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
				 	$weightage = $this->getRequest()->getPost('weightage'); 
					 try {
						 $this->academicAllocationService->updateAllocatedAssessmentWeightage($id, $weightage);
						 $this->flashMessenger()->addMessage('Allocated Assessment Component Weightage was successfully edited');
						 $this->auditTrailService->saveAuditTrail("EDIT", "Assessment Component", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('allocatedmoduleassessmentcomponent');
					 }
					 catch(\Exception $e) {
							$message = 'Failure';
						 	$this->flashMessenger()->addMessage($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
				 
			 }
			 
			return array(
				'id' => $id,
				'form' => $form,
				'keyphrase' => $this->keyphrase,
                'message' => $message,
                'organisation_id' => $this->organisation_id,
                'assessmentComponentDetails' => $assessmentComponentDetails,
				'allocatedAssessmentComponent' => $this->academicAllocationService->getAllocatedModuleAssessmentComponent($this->organisation_id),
				);

        }else{
            return $this->redirect()->toRoute('allocatedmoduleassessmentcomponent');
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
             
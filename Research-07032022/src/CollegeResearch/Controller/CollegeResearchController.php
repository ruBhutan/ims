<?php

namespace CollegeResearch\Controller;

use CollegeResearch\Service\CollegeResearchServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use CollegeResearch\Model\CollegeResearch;
use CollegeResearch\Model\ResearchRecommendation;
use CollegeResearch\Model\CargGrant;
use CollegeResearch\Model\CargResearch;
use CollegeResearch\Model\CargActionPlan;
use CollegeResearch\Model\CargAction;
use CollegeResearch\Model\UpdateCargGrant;
use CollegeResearch\Form\CargGrantForm;
use CollegeResearch\Form\CargResearchForm;
use CollegeResearch\Form\CargActionPlanForm;
use CollegeResearch\Form\UpdateCargGrantForm;
use CollegeResearch\Form\ResearchRecommendationForm;
use CollegeResearch\Form\SearchForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Zend\Http\Response\Stream;
use Zend\Http\Headers;
  
class CollegeResearchController extends AbstractActionController
{
    protected $collegeResearchService;
    protected $notificationService;
	protected $auditTrailService;
	protected $serviceLocator;
	protected $emailService;
	protected $username;
	protected $userrole;
	protected $usertype;
	protected $user_status_id;
	protected $userregion;
	protected $userDetails;
	protected $userImage;
	protected $lastGeneratedId;
	protected $employee_details_id;
	protected $organisation_id;
	protected $keyphrase = "RUB_IMS";

	
	public function __construct(CollegeResearchServiceInterface $collegeResearchService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->collegeResearchService = $collegeResearchService;
		$this->notificationService = $notificationService;
		$this->auditTrailService = $auditTrailService;
		$this->serviceLocator = $serviceLocator;
		$this->emailService = $serviceLocator->get('Application\Service\EmailService');
		
		/*
		 * To retrieve the user name from the session
		*/
		$authPlugin = $serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
        $this->username = $authPlugin['username'];
         $this->userrole = $authPlugin['role'];
          $this->userregion = $authPlugin['region'];
           $this->usertype = $authPlugin['user_type_id'];
           $this->user_status_id = $authPlugin['user_status_id'];

		
		$empData = $this->collegeResearchService->getUserDetailsId($this->username, $tableName = 'employee_details');
			foreach($empData as $emp){
				$this->employee_details_id = $emp['id'];
			}


		//get the organisation id
		$organisationID = $this->collegeResearchService->getOrganisationId($this->username, $this->usertype);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}
		
		$this->userDetails = $this->collegeResearchService->getUserDetails($this->username, $this->usertype);
		$this->userImage = $this->collegeResearchService->getUserImage($this->username, $this->usertype);
	}

	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }
	
	public function applyCollegeGrantAction()
	{
		$this->loginDetails();

		$id = NULL;

		$form = new CargGrantForm();
		$collegeResearchModel = new CargGrant();
		$form->bind($collegeResearchModel);
		
		$grantList = $this->collegeResearchService->getResearchGrantList($this->organisation_id);
		$grantAnnouncement = $this->collegeResearchService->getResearchGrantAnnouncement($tmp_id = 'College Grant', $this->organisation_id);
		$employee_details = $this->collegeResearchService->getEmployeeDetails($this->employee_details_id);

		$message = NULL;

         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) { 
                 try {
					 $cargData = $this->collegeResearchService->saveCargGrant($collegeResearchModel);
					 $lastGeneratedId = $cargData->getId();
                     $encrypted_id = $this->my_encrypt($lastGeneratedId, $this->keyphrase);
                     $this->auditTrailService->saveAuditTrail("INSERT", "Carg Grant", "ALL", "SUCCESS");
                     $this->flashMessenger()->addMessage('Successfully added Annual College Grant Title');
                    return $this->redirect()->toRoute('cargproject', array('id'=> $encrypted_id));
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
			'grantList' => $grantList,
			'grantAnnouncement' => $grantAnnouncement,
			'employee_details_id' => $this->employee_details_id,
			'employee_details' => $employee_details,
			'message' => $message,
			'keyphrase' => $this->keyphrase,
         );
	}
	
	public function applyCargProjectAction()
     {
     	$this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
        	$data = $this->collegeResearchService->findResearch($id);
		
			$form = new CargResearchForm();
			$collegeResearchModel = new CargResearch();
			$form->bind($collegeResearchModel);

			$message = NULL;

	         $request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             	if ($form->isValid()) {
	                 try {
						 $this->collegeResearchService->saveCargProject($collegeResearchModel);
						 $this->auditTrailService->saveAuditTrail('INSERT', 'Carg Action Plan', 'ALL', 'SUCCESS');
                         $this->flashMessenger()->addMessage('Successfully added Annual Colleg Grant Project Description');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
					 //The following statement is to redirect to next step/controller and send the last insert id 
				 	return $this->redirect()->toRoute('cargactionplan', array('id' => $this->my_encrypt($id, $this->keyphrase)));
	             }
	         }

	         return array(
	             'form' => $form,
				 'dbData' => $data,
				 'message' => $message,
				 'keyphrase' => $this->keyphrase,
	         );
        }else{
        	return $this->redirect()->toRoute('cargproject', array('id' => $this->my_encrypt($id, $this->keyphrase)));
        }
     }
	 
	 public function applyCargActionPlanAction()
     {
     	$this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
        	$data = $this->collegeResearchService->findResearch($id);
		
			$form = new CargActionPlanForm();
			$collegeResearchModel = new CargAction();
			$form->bind($collegeResearchModel);

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
	             $form->setData($request->getPost());
	                 try {
						 $this->collegeResearchService->saveCargActionPlan($collegeResearchModel);
						 $this->auditTrailService->saveAuditTrail('INSERT', 'Carg Budget Plan', 'ALL', 'SUCCESS');
                         $this->flashMessenger()->addMessage('Successfully added Annual College Grant');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
					 //The following statement is to redirect to next step/controller and send the last insert id 
				 	return $this->redirect()->toRoute('grantapplicationstatus');
	             }
	         }

	         return array(
	             'form' => $form,
				 'dbData' => $data,
				 'keyphrase' => $this->keyphrase,
				 'message' => $message,
	         );
        }else{
        	return $this->redirect()->toRoute('cargactionplan', array('id' => $this->my_encrypt($id, $this->keyphrase)));
        }
     }
	
	/*
	* This function should list, edit  College Grants
	* i.e. Should be able to approve Grants (by CRC etc)
	* and/or be editable by the researcher
	*/
	
	public function listCargGrantsAction()
	{
		$this->loginDetails();

		$form = new SearchForm();
		$researches = $this->collegeResearchService->getCargList(NULL, NULL, NULL, 'Pending', $this->organisation_id);
		$aurgResearches = $this->collegeResearchService->getAurgList(NULL, NULL, NULL, NULL, $this->organisation_id);
		
		$message = NULL;
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$researcher_name = $this->getRequest()->getPost('researcher_name');
				$research_title = $this->getRequest()->getPost('research_title');
				$grant_type = $this->getRequest()->getPost('grant_type');
				$researches = $this->collegeResearchService->getCargList($researcher_name, $research_title, $grant_type, $status='Pending', $this->organisation_id);
             }
         }
				
		return new ViewModel(array(
			'form' => $form,
			'researches' => $researches,
			'aurgResearches' => $aurgResearches,
			'message' => $message,
			'keyphrase' => $this->keyphrase,
			));
	}
	
	 public function viewCargApplicationAction()
     {
     	$this->loginDetails();
		//get the university id
		$id = (int) $this->params()->fromRoute('id',0);
		
		$form = new ResearchRecommendationForm();
		$collegeResearchModel = new ResearchRecommendation();
		$form->bind($collegeResearchModel);
		
		$cargDetails = $this->collegeResearchService->findResearchDetails($id, $tableName = 'carg_coresearchers');
		$actionPlanDetails = $this->collegeResearchService->findResearchDetails($id, $tableName = 'carg_action_plan');
		$budgetPlanDetails = $this->collegeResearchService->findResearchDetails($id, $tableName = 'carg_budget_plan');
		
        return array(
             'form' => $form,
			 'id' => $id,
			 'cargDetails' => $cargDetails,
			 'actionPlanDetails' => $actionPlanDetails,
			 'budgetPlanDetails' => $budgetPlanDetails
        );
    }
	
	 public function drilCargApprovalAction()
     {
     	$this->loginDetails();
		//get the university id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
        	$form = new ResearchRecommendationForm();
			$collegeResearchModel = new ResearchRecommendation();
			$form->bind($collegeResearchModel);
			
			$cargDetails = $this->collegeResearchService->findResearchDetails($id, $tableName = 'carg_coresearchers');
			$actionPlanDetails = $this->collegeResearchService->findResearchDetails($id, $tableName = 'carg_action_plan');
			$budgetPlanDetails = $this->collegeResearchService->findResearchDetails($id, $tableName = 'carg_budget_plan');
			$researcherDetails = $this->collegeResearchService->findResearchDetails($id, $tableName = 'employee_details');

			$message = NULL;
			
			$request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) { 
					// $research_status = 'Approved By CRC';
	                 try {
						 $this->collegeResearchService->saveResearchApproval($collegeResearchModel);
						 $this->auditTrailService->saveAuditTrail('UPDATE', 'Carg Grant', 'Application Status', 'SUCCESS');
						 $this->flashMessenger()->addMessage("Successfully update the annual college grant status");
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
					 //The following statement is to redirect to next step/controller and send the last insert id 
				 	return $this->redirect()->toRoute('listcarggrants');
	             }
	         }
			
	        return array(
	             'form' => $form,
				 'id' => $id,
				 'cargDetails' => $cargDetails,
				 'actionPlanDetails' => $actionPlanDetails,
				 'budgetPlanDetails' => $budgetPlanDetails,
				 'researcherDetails' => $researcherDetails,
				 'message' => $message,
	        );
        }else{
        	return $this->redirect()->toRoute('listcarggrants');
        }
    }
	
	//list CARG for update (i.e. the status of the research)
	public function updateCollegeGrantAction()
	{
		$this->loginDetails(); 

		$form = new SearchForm();
		$researches = $this->collegeResearchService->getCargList(NULL, NULL, NULL, 'Approved by CRC', $this->organisation_id);
		
		$message = NULL;

		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$researcher_name = $this->getRequest()->getPost('researcher_name');
				$research_title = $this->getRequest()->getPost('research_title');
				$grant_type = $this->getRequest()->getPost('grant_type'); 
				$researches = $this->collegeResearchService->getCargList($researcher_name, $research_title, $grant_type, $status = 'Approved', $this->organisation_id);
             }
         }
				
		return new ViewModel(array(
			'form' => $form,
			'researches' => $researches,
			'message' => $message,
			'keyphrase' => $this->keyphrase,
			));
	}
	 
	public function updateCargAction()
	{
		$this->loginDetails();
		//get the university id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){ 
        	$form = new UpdateCargGrantForm();
			$collegeResearchModel = new UpdateCargGrant();
			$form->bind($collegeResearchModel);
			
			$cargDetails = $this->collegeResearchService->findResearchDetails($id, $tableName = 'employee_details');
			$researchDetails = $this->collegeResearchService->findResearchDetails($id, $tableName = 'carg_grant');
			$actionPlanDetails = $this->collegeResearchService->findResearchDetails($id, $tableName = 'carg_action_plan');
			$budgetPlanDetails = $this->collegeResearchService->findResearchDetails($id, $tableName = 'carg_budget_plan');
			
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
						 $this->collegeResearchService->updateCargGrant($collegeResearchModel);
						 $this->auditTrailService->saveAuditTrail('INSERT', 'Carg Grant Application Status', 'ALL', 'SUCCESS');
                        $this->flashMessenger()->addMessage('Carg Application Status was successfully updated');
                        return $this->redirect()->toRoute('updatecollegegrant');
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
				'cargDetails' => $cargDetails,
				'researchDetails' => $researchDetails,
				'actionPlanDetails' => $actionPlanDetails,
				'budgetPlanDetails' => $budgetPlanDetails,
	        );
        }else{
        	return $this->redirect()->toRoute('updatecollegegrant');
        }
	}
	
	public function downloadResearchDocumentAction() 
	{
		$this->loginDetails();
		//get the param from the view file
		$file_location = $this->params()->fromRoute('filename',0);
		
		//extract the training id and column name
		$column_name = preg_replace('!\d+!', '', $file_location);
		$column_name = rtrim($column_name,"_");
		preg_match_all('!\d+!',$file_location, $id);
		$application_id = implode(' ', $id[0]);
		
		//get the location of the file from the database		
		$fileArray = $this->collegeResearchService->getFileName($application_id, $column_name, $research_type='carg');
		$file;
		foreach($fileArray as $set){
			$file = $set[$column_name];
		}
		
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
             

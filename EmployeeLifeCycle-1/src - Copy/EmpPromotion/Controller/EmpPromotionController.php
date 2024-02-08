<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace EmpPromotion\Controller;

use EmpPromotion\Service\EmpPromotionServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use EmpPromotion\Form\EmpPromotionForm;
use EmpPromotion\Form\MeritoriousPromotionAchievementsForm;
use EmpPromotion\Form\MeritoriousPromotionSupplementForm;
use EmpPromotion\Form\PromotionApprovalForm;
use EmpPromotion\Form\EmpPromotionApprovalForm;
use EmpPromotion\Form\RejectPromotionForm;
use EmpPromotion\Form\UpdateEmployeePostForm;
use EmpPromotion\Form\OpenCompetitionForm;
use EmpPromotion\Form\SearchForm;
use EmpPromotion\Model\EmpPromotion;
use EmpPromotion\Model\PromotionApproval;
use EmpPromotion\Model\RejectPromotion;
use Zend\Session\Container;
use Zend\Http\Response\Stream;
use Zend\Http\Headers;

/**
 * Description of IndexController
 *
 */
 
class EmpPromotionController extends AbstractActionController
{
	protected $promotionService;
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
	
	public function __construct(EmpPromotionServiceInterface $promotionService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->promotionService = $promotionService;
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
		* Getting the employee_details_id related to username
		*/
		
		$empData = $this->promotionService->getEmployeeDetailsId($this->username);
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
		}
		
		//get the organisation id
		$organisationID = $this->promotionService->getOrganisationId($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}
		
		//get the user details such as name
		$this->userDetails = $this->promotionService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->promotionService->getUserImage($this->username, $this->usertype);
		
	}
	
	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }
		
	public function applyPromotionAction()
	{    
		$this->loginDetails();
		        
		$form = new EmpPromotionForm();
		$promotionModel = new EmpPromotion();
		$form->bind($promotionModel);
		
		$personalDetails = $this->promotionService->getPersonalDetails($this->employee_details_id);
		$educationDetails = $this->promotionService->getEducationDetails($this->employee_details_id);
		$employmentDetails = $this->promotionService->getEmploymentDetails($this->employee_details_id);
		$lastPromotionDate = $this->promotionService->getEmployeeLastPromotion('promotion_date', $this->employee_details_id);
		$trainingDetails = $this->promotionService->getTrainingDetails($this->employee_details_id);
		$researchDetails = $this->promotionService->getResearchDetails($this->employee_details_id);	
		$studyLeaveDetails = $this->promotionService->getStudyLeaveDetails($this->employee_details_id);
		$eolLeaveDetails = $this->promotionService->getEolLeaveDetails($this->employee_details_id);
		$pmsDetails = $this->promotionService->getPmsDetails($this->employee_details_id, $this->userrole);
		$payDetails = $this->promotionService->getPayDetails($employmentDetails['position_level']);
		$positionDetails = $this->promotionService->getPositionDetails($employmentDetails['position_title']);
		
		//Get the notification details, i.e. submission to and submission to department
		$submission_to = $this->promotionService->getNotificationDetails($this->organisation_id);

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
						$data = $form->getData();
						
						$check_promotion = $this->promotionService->crossCheckAppliedPromotion('Normal', $this->employee_details_id);
						if($check_promotion){
							$message = 'Failure';
							$this->flashMessenger()->addMessage("You can't apply for this Normal promotion since you have already applied for promotion and it is still pending");
						}else{
							try {
									$this->promotionService->save($promotionModel);
									$this->flashMessenger()->addMessage('Promotion Application was successfully added');
									$this->notificationService->saveNotification('Promotion Application', $submission_to, NULL, 'Application for Promotion');
									$this->auditTrailService->saveAuditTrail("INSERT", "Promotion Application", "ALL", "SUCCESS");
									return $this->redirect()->toRoute('promotionapplicantstatus');
							}
							catch(\Exception $e) {
								$message = 'Failure';
								$this->flashMessenger()->addMessage($e->getMessage());
							}
						}
			 }
		 }
 
		return array(
					'form' => $form,
					'employee_details_id' => $this->employee_details_id,
					'personalDetails' => $personalDetails,
					'employmentDetails' => $employmentDetails,
					'lastPromotionDate' => $lastPromotion,
					'educationDetails' => $educationDetails,
					'trainingDetails' => $trainingDetails,
					'researchDetails' => $researchDetails,
					'studyLeaveDetails' => $studyLeaveDetails,
					'eolLeaveDetails' => $eolLeaveDetails,
					'pmsDetails' => $pmsDetails,
					'payDetails' => $payDetails,
					'positionDetails' => $positionDetails,
					'message' => $message,
					);
	}
	
	public function applyMeritoriousPromotionAction()
	{
		$this->loginDetails();
		
		$form = new EmpPromotionForm();
		$promotionModel = new EmpPromotion();
		$form->bind($promotionModel);
		
		$personalDetails = $this->promotionService->getPersonalDetails($this->employee_details_id);
		$educationDetails = $this->promotionService->getEducationDetails($this->employee_details_id);
		$employmentDetails = $this->promotionService->getEmploymentDetails($this->employee_details_id);
		$lastPromotionDate = $this->promotionService->getEmployeeLastPromotion('promotion_date', $this->employee_details_id);
		$trainingDetails = $this->promotionService->getTrainingDetails($this->employee_details_id);
		$researchDetails = $this->promotionService->getResearchDetails($this->employee_details_id);	
		$studyLeaveDetails = $this->promotionService->getStudyLeaveDetails($this->employee_details_id);
		$eolLeaveDetails = $this->promotionService->getEolLeaveDetails($this->employee_details_id);
		$pmsDetails = $this->promotionService->getPmsDetails($this->employee_details_id, $this->userrole);
		$payDetails = $this->promotionService->getPayDetails($employmentDetails['position_level']);
		$positionDetails = $this->promotionService->getPositionDetails($employmentDetails['position_title']);
		
		//Get the notification details, i.e. submission to and submission to department
		$submission_to = $this->promotionService->getNotificationDetails($this->organisation_id);

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
				 $data = $form->getData();
				 $check_promotion = $this->promotionService->crossCheckAppliedPromotion('Meritorious', $this->employee_details_id);
						if($check_promotion){
							$message = 'Failure';
							$this->flashMessenger()->addMessage("You can't apply for this Meritorious promotion since you have already applied for promotion and it is still pending");
						}else{
							try {
									$this->promotionService->save($promotionModel);
									$this->flashMessenger()->addMessage('Promotion Application was successfully added');
									$this->notificationService->saveNotification('Promotion Application', $submission_to, NULL, 'Application for Meritorious Promotion');
									$this->auditTrailService->saveAuditTrail("INSERT", "Meritorious Promotion Application", "ALL", "SUCCESS");
									return $this->redirect()->toRoute('promotionapplicantstatus');
							}
							catch(\Exception $e) {
									$message = 'Failure';
									$this->flashMessenger()->addMessage($e->getMessage());
											// Some DB Error happened, log it and let the user know
							}
						}
			 }
		 }

		return array(
					'form' => $form,
					'employee_details_id' => $this->employee_details_id,
					'personalDetails' => $personalDetails,
					'employmentDetails' => $employmentDetails,
					'lastPromotionDate' => $lastPromotion,
					'educationDetails' => $educationDetails,
					'trainingDetails' => $trainingDetails,
					'researchDetails' => $researchDetails,
					'studyLeaveDetails' => $studyLeaveDetails,
					'eolLeaveDetails' => $eolLeaveDetails,
					'pmsDetails' => $pmsDetails,
					'payDetails' => $payDetails,
					'positionDetails' => $positionDetails,
					'message' => $message,
					);
    }
        
	public function listMeritoriousPromotionAction()
	{
		$this->loginDetails();
            
		return array(
				'meritoriousPromotionList' => $this->promotionService->listMeritoriousPromotion($this->organisation_id)
		);
	}
        
	public function meritoriousPromotionSupplementaryFormAction()
	{
		$this->loginDetails();
		
		//get the emp promotion id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$supplementForm = new MeritoriousPromotionSupplementForm();
		$promotionModel = new MeritoriousPromotionSupplement();
		$supplementForm->bind($promotionModel);
		
		$request = $this->getRequest();
			 if ($request->isPost()) {
					$supplementForm->setData($request->getPost());
					$data = array_merge_recursive(
						   $request->getPost()->toArray(),
						   $request->getFiles()->toArray()
					); 
					$supplementForm->setData($data); 
				 if ($supplementForm->isValid()) {
					$data = $supplementForm->getData();
					 try {
						$this->promotionService->savePromotionApprovalDetails($data);
						$this->flashMessenger()->addMessage('Promotion Supplementary Form Application was successfully added');
						$this->notificationService->saveNotification('Promotion Application', 'ALL', 'ALL', 'Forms for Application for Promotion');
							$this->auditTrailService->saveAuditTrail("INSERT", "Supplementary Form for Promotion Application", "ALL", "SUCCESS");
						return $this->redirect()->toRoute('promotionapplicantstatus');
					}
					catch(\Exception $e) {
						die($e->getMessage());
						// Some DB Error happened, log it and let the user know
					}
				 }

			 }
		
		$achievementsForm = new MeritoriousPromotionAchievementsForm;
		
		return array(
				'supplementForm' => $supplementForm,
				'achievementsForm' => $achievementsForm
		);
	}
        
	
	public function promotionApplicantStatusAction()
	{
		$this->loginDetails();
		
		$message = NULL;

		return array(
				'pendingApplicantList' => $this->promotionService->getPromotionApplicantList($this->organisation_id, $status='Pending'),
				'approvedApplicantList' => $this->promotionService->getPromotionApplicantList($this->organisation_id, $status='Approved'),
				'rejectedApplicantList' => $this->promotionService->getPromotionApplicantList($this->organisation_id, $status='Rejected'),
				'keyphrase' => $this->keyphrase,
				'message' => $message
				);
	}
    
	public function empPromotionApprovalAction()
	{
		$this->loginDetails();
		
		//get the employee promotion id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);

		if(is_numeric($id)){
			//get the employee details of the applicant
			$promotion_applicant_detail = $this->promotionService->getPromotionApplicantDetail($id);
			$employee_id = $promotion_applicant_detail['employee_details_id'];
			$form = new PromotionApprovalForm($this->serviceLocator);
			$personalDetails = $this->promotionService->getPersonalDetails($employee_id);
			$employmentDetails = $this->promotionService->getEmploymentDetails($employee_id);

			$lastPromotionDate = $this->promotionService->getEmployeeLastPromotion('promotion_date', $employee_id);
			$lastPromotion = $this->promotionService->getEmployeeLastPromotion(NULL, $id);
				
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 $data = array_merge_recursive(
						$request->getPost()->toArray(),
						$request->getFiles()->toArray()
				 ); 
				 $form->setData($data); 
				 if ($form->isValid()) {
					$data = $form->getData();
					 try {
							 $this->promotionService->savePromotionApprovalDetails($data);
							 $this->flashMessenger()->addMessage('Promotion Application was successfully approved');
							 $this->notificationService->saveNotification('Promotion Application', $employee_id, NULL, 'Promotion Approved');
							 $this->auditTrailService->saveAuditTrail("UPDATE", "Promotion Application Approval", "ALL", "SUCCESS");
							 return $this->redirect()->toRoute('promotionapplicantstatus');
					 }
					 catch(\Exception $e) {
									 die($e->getMessage());
									 // Some DB Error happened, log it and let the user know
					 }
				 }

			 }

			return array(
				'form' => $form,
				'personalDetails' => $personalDetails,
				'employmentDetails' => $employmentDetails,
				'lastPromotionDate' => $lastPromotionDate,
				'lastPromotion' => $lastPromotion,
				'id' => $id
			);	
		}else{
			return $this->redirect()->toRoute('promotionapplicantstatus');
		}
	}
	
	public function viewDetailsForPromotionAction()
	{
		$this->loginDetails();
		
		//get the promotion id. It will be used to get the employee_details_id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$promotion_applicant_detail = $this->promotionService->getPromotionApplicantDetail($id);
			$employee_id = $promotion_applicant_detail['employee_details_id'];
			$employee_user_role = $promotion_applicant_detail['role'];
			
			$form = new EmpPromotionForm();
			$promotionModel = new EmpPromotion();
			$form->bind($promotionModel);
			$personalDetails = $this->promotionService->getPersonalDetails($employee_id);
			$lastPromotionDate = $this->promotionService->getEmployeeLastPromotion('promotion_date', $employee_id);
			$lastPromotion = $this->promotionService->getEmployeeLastPromotion(NULL, $id);
			$educationDetails = $this->promotionService->getEducationDetails($employee_id);
			$employmentDetails = $this->promotionService->getEmploymentDetails($employee_id);
			$trainingDetails = $this->promotionService->getTrainingDetails($employee_id);
			$researchDetails = $this->promotionService->getResearchDetails($employee_id);	
			$studyLeaveDetails = $this->promotionService->getStudyLeaveDetails($employee_id);
			$eolLeaveDetails = $this->promotionService->getEolLeaveDetails($employee_id);
			$pmsDetails = $this->promotionService->getPmsDetails($employee_id, $employee_user_role);
					 
			return array(
				'form' => $form,
				'employee_details_id' => $this->employee_details_id,
				'emp_promotion_id' => $id,
				'personalDetails' => $personalDetails,
				'lastPromotionDate' => $lastPromotionDate,
				'lastPromotion' => $lastPromotion,
				'employmentDetails' => $employmentDetails,
				'educationDetails' => $educationDetails,
				'trainingDetails' => $trainingDetails,
				'researchDetails' => $researchDetails,
				'studyLeaveDetails' => $studyLeaveDetails,
				'eolLeaveDetails' => $eolLeaveDetails,
				'pmsDetails' => $pmsDetails,
				'keyphrase' => $this->keyphrase,
				);
		}
		else {
			return $this->redirect()->toRoute('promotionapplicantstatus');
		}
	}
	
	
	public function empPromotionRejectAction()
    {
		$this->loginDetails();
		
        //get the employee promotion id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			//get the employee details of the applicant
			$promotion_applicant_detail = $this->promotionService->getPromotionApplicantDetail($id);
			$employee_id = $promotion_applicant_detail['employee_details_id'];
			
			$form = new RejectPromotionForm();
			$promotionModel = new RejectPromotion();
			$form->bind($promotionModel);
			
			$personalDetails = $this->promotionService->getPersonalDetails($employee_id);
			$employmentDetails = $this->promotionService->getEmploymentDetails($employee_id);
					
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 $data = $form->getData();
					 try {
							 $this->promotionService->rejectPromotion($data);
							 $this->flashMessenger()->addMessage('Promotion Application was successfully rejected');
							 $this->notificationService->saveNotification('Promotion Application', $employee_id, NULL, 'Promotion Rejected');
							 $this->auditTrailService->saveAuditTrail("UPDATE", "Promotion Application Rejection", "ALL", "SUCCESS");
							 return $this->redirect()->toRoute('promotionapplicantstatus');
					 }
					 catch(\Exception $e) {
									 die($e->getMessage());
									 // Some DB Error happened, log it and let the user know
					 }
				 }

			 }

			return array(
				'form' => $form,
				'personalDetails' => $personalDetails,
				'employmentDetails' => $employmentDetails,
				'id' => $id
				);
		}
		else {
			return $this->redirect()->toRoute('promotionapplicantstatus');
		}
    }
	
	public function viewUpdatedEmployeePostAction()
	{
		$this->loginDetails();
		
		//get the promotion id. It will be used to get the employee_details_id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$promotion_applicant_detail = $this->promotionService->getPromotionApplicantDetail($id);
			$employee_id = $promotion_applicant_detail['employee_details_id'];
			$employee_user_role = $promotion_applicant_detail['role'];
			
			$form = new EmpPromotionForm();
			
			$personalDetails = $this->promotionService->getPersonalDetails($employee_id);
			$employmentDetails = $this->promotionService->getEmploymentDetails($employee_id);
			$promotionDetails = $this->promotionService->findPromotionDetails($id);
					 
			return array(
				'form' => $form,
				'employee_details_id' => $this->employee_details_id,
				'emp_promotion_id' => $id,
				'personalDetails' => $personalDetails,
				'employmentDetails' => $employmentDetails,
				'promotionDetails' => $promotionDetails
				);
		} 
		else {
			return $this->redirect()->toRoute('promotionapplicantstatus');
		}
	}
	
	public function viewRejectedReasonsAction()
	{
		$this->loginDetails();
		
		//get the promotion id. It will be used to get the employee_details_id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$promotion_applicant_detail = $this->promotionService->getPromotionApplicantDetail($id);
			$employee_id = $promotion_applicant_detail['employee_details_id'];
			$employee_user_role = $promotion_applicant_detail['role'];
			
			$form = new EmpPromotionForm();
			$promotionModel = new EmpPromotion();
			$form->bind($promotionModel);
			$personalDetails = $this->promotionService->getPersonalDetails($employee_id);
			$employmentDetails = $this->promotionService->getEmploymentDetails($employee_id);
			$promotionDetails = $this->promotionService->findPromotionDetails($id);
					 
			return array(
				'form' => $form,
				'employee_details_id' => $this->employee_details_id,
				'emp_promotion_id' => $id,
				'personalDetails' => $personalDetails,
				'employmentDetails' => $employmentDetails,
				'promotionDetails' => $promotionDetails
				);
		}
		else {
			return $this->redirect()->toRoute('promotionapplicantstatus');
		}
	}
	
	public function empPromotionSearchAction()
	{
		$this->loginDetails();
		
		$form = new SearchForm();
		
		$request = $this->getRequest();
        if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$empName = $this->getRequest()->getPost('employee_name');
				$empId = $this->getRequest()->getPost('emp_id');
				$department = $this->getRequest()->getPost('department');
				$employeeList = $this->promotionService->getEmployeeList($empName, $empId, $department, $this->organisation_id);
             }
        }
		else {
			$employeeList = array();
		}
		
		return new ViewModel(array(
            'form' => $form,
			'keyphrase' => $this->keyphrase,
			'employeeList' => $employeeList
          ));
	}
	
	public function openCompetitionListAction()
	{
		
	}
	
	public function promotionViaCompetitionAction()
	{
		$this->loginDetails();
		
		//get the promotion id. It will be used to get the employee_details_id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		$employee_id = $id;
		
		if(is_numeric($id)){
			//$dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
			$form = new OpenCompetitionForm($this->serviceLocator);
			
			$personalDetails = $this->promotionService->getPersonalDetails($employee_id);
			$employmentDetails = $this->promotionService->getEmploymentDetails($employee_id);
			$organisationList = $this->promotionService->listSelectData('organisation','organisation_name');
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 $data = array_merge_recursive(
						$request->getPost()->toArray(),
						$request->getFiles()->toArray()
				 ); 
				 $form->setData($data); 
				 if ($form->isValid()) {
					$data = $form->getData();
					 try {
							 $this->promotionService->saveOpenCompetitionPromotion($data);
							 $this->flashMessenger()->addMessage('Promotion Application was successfully approved');
							 $this->notificationService->saveNotification('Promotion Application', $employee_id, NULL, 'Promotion Approved');
							 $this->auditTrailService->saveAuditTrail("UPDATE", "Open Competition Promotion Application Approval", "ALL", "SUCCESS");
							 return $this->redirect()->toRoute('promotionapplicantstatus');
					 }
					 catch(\Exception $e) {
									 die($e->getMessage());
									 // Some DB Error happened, log it and let the user know
					 }
				 }
	
			 }
					 
			return array(
				'form' => $form,
				'employee_details_id' => $employee_id,
				'personalDetails' => $personalDetails,
				'employmentDetails' => $employmentDetails,
				'organisationList' => $organisationList
				);
		}
		else {
			return $this->redirect()->toRoute('promotionapplicantstatus');
		}
	}
	
	public function downloadPromotionDocumentAction() 
	{
		//get the param from the view file
		$file_location = $this->params()->fromRoute('filename',0);
		
		//extract the training id and column name
		$column_name = preg_replace('!\d+!', '', $file_location);
		$document_type = rtrim($column_name,"_");
		preg_match_all('!\d+!',$file_location, $id);
		$promotion_id = implode(' ', $id[0]);
		
		//get the location of the file from the database		
		$file = $this->promotionService->getFileName($promotion_id, $document_type);
		
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

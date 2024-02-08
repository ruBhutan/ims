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
use Zend\View\Model\JsonModel;
use Zend\Session\Container;

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
 
class EmpPromotionController extends AbstractActionController
{
	protected $promotionService;
	protected $notificationService;
	protected $auditTrailService;
	protected $emailService;
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
				
		/*
		* Getting the employee_details_id related to username
		*/
		
		$empData = $this->promotionService->getEmployeeDetailsId($this->username);
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			$this->departments_units_id = $emp['departments_units_id'];
			$this->departments_id = $emp['departments_id'];
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
		$pmsDetails = $this->promotionService->getPmsDetails($this->employee_details_id, $this->userrole);
		        
		$form = new EmpPromotionForm($pmsDetails);
		$promotionModel = new EmpPromotion();
		$form->bind($promotionModel);
		
		$personalDetails = $this->promotionService->getPersonalDetails($this->employee_details_id);
		$educationDetails = $this->promotionService->getEducationDetails($this->employee_details_id);
		$employmentDetails = $this->promotionService->getEmploymentDetails($this->employee_details_id);
		$employeeWorkDetails = $this->promotionService->findEmployeeExtraDetails($tableName='emp_employment_record', $this->employee_details_id);
		$lastPromotionDate = $this->promotionService->getEmployeeLastPromotion('promotion_date', $this->employee_details_id);
		$trainingDetails = $this->promotionService->getTrainingDetails($this->employee_details_id);
		$researchDetails = $this->promotionService->getResearchDetails($this->employee_details_id);	
		$studyLeaveDetails = $this->promotionService->getStudyLeaveDetails($this->employee_details_id);
		$eolLeaveDetails = $this->promotionService->getEolLeaveDetails($this->employee_details_id);
		
		//$pmsDetails = $this->promotionService->getPmsDetails($this->employee_details_id, $this->userrole);
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
						//var_dump($form['supporting_file']); die();
						$performance_year_data = $this->extractFormData('performance_year'); 
						//var_dump($performance_year_data); echo '<br>';
						$performance_rating_data = $this->extractFormData('performance_rating'); 
						// var_dump($performance_rating_data);
						$performance_category_data = $this->extractFormData('performance_category'); 
						//var_dump($performance_category_data);
						//$performance_file_data = $this->extractFormData('supporting_file'); 
						//var_dump($performance_file_data); die();
						$check_promotion = $this->promotionService->crossCheckAppliedPromotion('Normal', $this->employee_details_id);
						if($check_promotion){
							$message = 'Failure';
							$this->flashMessenger()->addMessage("You can't apply for this Normal promotion since you have already applied for promotion and it is still pending");
						}else{
							try {
									$this->promotionService->save($promotionModel, $performance_year_data, $performance_rating_data, $performance_category_data, $this->userrole);
									$this->notificationService->saveNotification('Promotion Application', $submission_to, NULL, 'Application for Promotion');
									$this->auditTrailService->saveAuditTrail("INSERT", "Promotion Application", "ALL", "SUCCESS");
									$this->sendPromotionApplicationEmail($this->employee_details_id, $this->departments_id, $this->departments_units_id, $this->userrole, 'Normal Promotion');
									$this->flashMessenger()->addMessage('Promotion Application was successfully added');
									return $this->redirect()->toRoute('staffpromotionstatus');
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
					'employeeWorkDetails' => $employeeWorkDetails,
					'lastPromotionDate' => $lastPromotionDate,
					'educationDetails' => $educationDetails,
					'trainingDetails' => $trainingDetails,
					'researchDetails' => $researchDetails,
					'studyLeaveDetails' => $studyLeaveDetails,
					'eolLeaveDetails' => $eolLeaveDetails,
					'pmsDetails' => $pmsDetails,
					'payDetails' => $payDetails,
					'positionDetails' => $positionDetails,
					'keyphrase' => $this->keyphrase,
					'message' => $message,
					);
	}
	
	public function applyMeritoriousPromotionAction()
	{
		$this->loginDetails();
		$pmsDetails = $this->promotionService->getPmsDetails($this->employee_details_id, $this->userrole);
		
		$form = new EmpPromotionForm($pmsDetails);
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
		//$pmsDetails = $this->promotionService->getPmsDetails($this->employee_details_id, $this->userrole);
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
				 //var_dump($form['supporting_file']); die();
				$performance_year_data = $this->extractFormData('performance_year'); 
				//var_dump($performance_year_data); echo '<br>';
				$performance_rating_data = $this->extractFormData('performance_rating'); 
				// var_dump($performance_rating_data);
				$performance_category_data = $this->extractFormData('performance_category'); 
				//var_dump($performance_category_data);
				//$performance_file_data = $this->extractFormData('supporting_file'); 
				//var_dump($performance_file_data); die();
				 $check_promotion = $this->promotionService->crossCheckAppliedPromotion('Meritorious', $this->employee_details_id);
						if($check_promotion){
							$message = 'Failure';
							$this->flashMessenger()->addMessage("You can't apply for this Meritorious promotion since you have already applied for promotion and it is still pending");
						}else{
							try {
									$this->promotionService->save($promotionModel, $performance_year_data, $performance_rating_data, $performance_category_data, $this->userrole);
									$this->notificationService->saveNotification('Promotion Application', $submission_to, NULL, 'Application for Meritorious Promotion');
									$this->auditTrailService->saveAuditTrail("INSERT", "Meritorious Promotion Application", "ALL", "SUCCESS");
									$this->sendPromotionApplicationEmail($this->employee_details_id, $this->departments_id, $this->departments_units_id, $this->userrole, 'Meritorious Promotion');
									$this->flashMessenger()->addMessage('Promotion Application was successfully added');
									return $this->redirect()->toRoute('staffpromotionstatus');
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
					'lastPromotionDate' => $lastPromotionDate,
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
	

	public function sendPromotionApplicationEmail($employee_details_id, $departments_id, $departments_units_id, $userrole, $type)
	{
		$this->loginDetails();

    	$supervisor_email = $this->promotionService->getSupervisorEmailId($userrole, $departments_units_id);

	 	$applicant_name = NULL;
	 	$applicant = $this->promotionService->getPromotionApplicant($employee_details_id);
	 	foreach($applicant as $temp){
	 		$applicant_name = $temp['first_name'].' '.$temp['middle_name'].' '.$temp['last_name'];
	 	}

	 	foreach($supervisor_email as $email){
	 		$toEmail = $email;
	        $messageTitle = "New Promotion Application";
	        //$messageBody = "<h2>".$applicant_name."</h2><b>has applied for leave on ".date('Y-m-d')."</b><br>.<b>For Details: Please click below link</b> <br><u>http://ims.rub.edu.bt/public/empleaveapproval/</u>";
			$messageBody = "Dear Sir/Madam,<br><h3>".$applicant_name." has applied for ".$type." on ".date('Y-m-d').".</h3><br><b>Please click the link below for necessary action.</b><br><u>http://ims.rub.edu.bt/public/empapplypromotion</u><p>This is an auto-generated email and Please do not reply because this mail id is not monitored</p>";

	        $this->emailService->sendMailer($toEmail, $messageTitle, $messageBody);
	 	} 
	}

	public function sendPromotionStatusEmail($employee_id, $status)
	{
		$this->loginDetails();

		$applicant_email = NULL;
		$applicant_name = NULL;
	 	$applicant = $this->promotionService->getPromotionApplicant($employee_id);
	 	foreach($applicant as $temp){
			$applicant_name = $temp['first_name'].' '.$temp['middle_name'].' '.$temp['last_name'];
	 		$applicant_email = $temp['email'];
	 	}

		$toEmail = $applicant_email;
		$messageTitle = "Promotion Application Status";
		//$messageBody = "<h2>".$applicant_name."</h2><b>has applied for leave on ".date('Y-m-d')."</b><br>.<b>For Details: Please click below link</b> <br><u>http://ims.rub.edu.bt/public/empleaveapproval/</u>";
		$messageBody = "Dear,<br><h3>".$applicant_name."</h3> Your Promotion applicantion has been ".$status." on ".date('Y-m-d').".<br><b>Please click the link below for necessary action.</b><br><u>http://ims.rub.edu.bt/public/staffpromotionstatus</u><p>This is an auto-generated email and Please do not reply because this mail id is not monitored</p>";

		$this->emailService->sendMailer($toEmail, $messageTitle, $messageBody);
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
				'pendingApplicantList' => $this->promotionService->getPromotionApplicantList($this->organisation_id, $userrole = $this->userrole, $this->employee_details_id, $this->departments_id, $status='Pending'),
				'approvedApplicantList' => $this->promotionService->getPromotionApplicantList($this->organisation_id, $userrole = $this->userrole, $this->employee_details_id, $this->departments_id, $status='Approved'),
				'rejectedApplicantList' => $this->promotionService->getPromotionApplicantList($this->organisation_id, $userrole = $this->userrole, $this->employee_details_id, $this->departments_id, $status='Rejected'),
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
							 $this->notificationService->saveNotification('Promotion Application', $employee_id, NULL, 'Promotion Approved');
							 $this->auditTrailService->saveAuditTrail("UPDATE", "Promotion Application Approval", "ALL", "SUCCESS");
							 $this->sendPromotionStatusEmail($employee_id, 'Approved');
							 $this->flashMessenger()->addMessage('Promotion Application was successfully approved');
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

			$pmsDetails = $this->promotionService->getPmsDetails($employee_id, $employee_user_role);

			$form = new EmpPromotionForm($pmsDetails); 
			$promotionModel = new EmpPromotion();
			$form->bind($promotionModel);
			$personalDetails = $this->promotionService->getPersonalDetails($employee_id);
			$lastPromotionDate = $this->promotionService->getEmployeeLastPromotion('promotion_date', $employee_id);
			$promotionDetails = $this->promotionService->getPromotionDetails($id);
			$lastPromotion = $this->promotionService->getEmployeeLastPromotion(NULL, $id);
			$educationDetails = $this->promotionService->getEducationDetails($employee_id);
			$employmentDetails = $this->promotionService->getEmploymentDetails($employee_id);
			$trainingDetails = $this->promotionService->getTrainingDetails($employee_id);
			$researchDetails = $this->promotionService->getResearchDetails($employee_id);	
			$studyLeaveDetails = $this->promotionService->getStudyLeaveDetails($employee_id);
			$eolLeaveDetails = $this->promotionService->getEolLeaveDetails($employee_id);
			
					 
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
				'promotionDetails' => $promotionDetails,
				'employee_id' => $employee_id,
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

			$lastPromotionDate = $this->promotionService->getEmployeeLastPromotion('promotion_date', $employee_id);
			$lastPromotion = $this->promotionService->getEmployeeLastPromotion(NULL, $id);
					
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 $data = $form->getData();
					 try {
							 $this->promotionService->rejectPromotion($data);
							 $this->notificationService->saveNotification('Promotion Application', $employee_id, NULL, 'Promotion Rejected');
							 $this->auditTrailService->saveAuditTrail("UPDATE", "Promotion Application Rejection", "ALL", "SUCCESS");
							 $this->sendPromotionStatusEmail($employee_id, 'Rejected');
							 $this->flashMessenger()->addMessage('Promotion Application was successfully rejected');
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
			
			$form = new EmpPromotionForm($this->serviceLocator);
			
			$personalDetails = $this->promotionService->getPersonalDetails($employee_id);
			$employmentDetails = $this->promotionService->getEmploymentDetails($employee_id);
			$promotionDetails = $this->promotionService->findPromotionDetails($id);
			$village = $this->promotionService->listSelectData('village', 'village_name');
			$gewog = $this->promotionService->listSelectData('gewog', 'gewog_name');
			$dzongkhag = $this->promotionService->listSelectData('dzongkhag', 'dzongkhag_name');
			$position_category = $this->promotionService->listSelectData('position_category', 'category');
			$position_title = $this->promotionService->listSelectData('position_title', 'position_title');
			$position_level = $this->promotionService->listSelectData('position_level', 'position_level');
					 
			return array(
				'form' => $form,
				'employee_details_id' => $this->employee_details_id,
				'emp_promotion_id' => $id,
				'personalDetails' => $personalDetails,
				'employmentDetails' => $employmentDetails,
				'promotionDetails' => $promotionDetails,
				'village' => $village,
				'gewog' => $gewog,
				'dzongkhag' => $dzongkhag,
				'position_category' => $position_category,
				'position_title' => $position_title,
				'position_level' => $position_level,
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
			
			$form = new EmpPromotionForm($this->serviceLocator);

			$personalDetails = $this->promotionService->getPersonalDetails($employee_id);
			$employmentDetails = $this->promotionService->getEmploymentDetails($employee_id);
			$promotionDetails = $this->promotionService->findPromotionDetails($id);
			$village = $this->promotionService->listSelectData('village', 'village_name');
			$gewog = $this->promotionService->listSelectData('gewog', 'gewog_name');
			$dzongkhag = $this->promotionService->listSelectData('dzongkhag', 'dzongkhag_name');
			$position_category = $this->promotionService->listSelectData('position_category', 'category');
			$position_title = $this->promotionService->listSelectData('position_title', 'position_title');
			$position_level = $this->promotionService->listSelectData('position_level', 'position_level');
					 
			return array(
				'form' => $form,
				'employee_details_id' => $this->employee_details_id,
				'emp_promotion_id' => $id,
				'personalDetails' => $personalDetails,
				'employmentDetails' => $employmentDetails,
				'promotionDetails' => $promotionDetails,
				'village' => $village,
				'gewog' => $gewog,
				'dzongkhag' => $dzongkhag,
				'position_category' => $position_category,
				'position_title' => $position_title,
				'position_level' => $position_level,
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
							 return $this->redirect()->toRoute('emppromotionsearch');
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
			return $this->redirect()->toRoute('emppromotionsearch');
		}
	}


	public function downloadPromotionDetailFileAction()
	{
		//get the param from the view file
		$file_location = $this->params()->fromRoute('filename',0);
		
		//extract the training id and column name
		$column_name = preg_replace('!\d+!', '', $file_location);
		$column_name = rtrim($column_name,"_");
		preg_match_all('!\d+!',$file_location, $id);
		$emp_promotion_id = implode(' ', $id[0]);
		
		//get the location of the file from the database		
		$fileArray = $this->promotionService->getPromotionDetailFileName($emp_promotion_id, $column_name);
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


	//the following function is to extract the data from the form 
	// and return clean data to be inserted into database
	public function extractFormData($type)
	{
		$pmsDetails = $this->promotionService->getPmsDetails($this->employee_details_id, $this->userrole);

		/*$form = new EmpPromotionForm($pmsDetails);
		$request = $this->getRequest();
    	if ($request->isPost()) {
        // Make certain to merge the files info!
        $post = array_merge_recursive(
            $request->getPost()->toArray(),
            $request->getFiles()->toArray()
        );

        $form->setData($post);
		$performanceData = array();*/
		
		if($type == 'performance_year'){
			foreach($pmsDetails as $key=>$value){
				$performanceData[$key]= $this->getRequest()->getPost('performance_year'.$key);
			}
		}
		else if($type == 'performance_rating'){
			foreach($pmsDetails as $key=>$value){
				$performanceData[$key]= $this->getRequest()->getPost('performance_rating'.$key);
			}
		}
		else if($type == 'performance_category'){
			foreach($pmsDetails as $key=>$value){
				$performanceData[$key]= $this->getRequest()->getPost('performance_category'.$key);
			}
		}
		
		return $performanceData;
	}


	public function printEmpApplyPromotionDetailsAction()
     {
        $this->loginDetails();
         //get the id
        $id_from_route = $this->params()->fromRoute('id');
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		$type_from_route = $this->params()->fromRoute('type');
		$type = $this->my_decrypt($type_from_route, $this->keyphrase);
		
		$promotion_type_from_route = $this->params()->fromRoute('promotion_type');
        $promotion_type = $this->my_decrypt($promotion_type_from_route, $this->keyphrase);

        if(is_numeric($id)){ 
			$empDetails = $this->promotionService->getPersonalDetails($id);

			$emp_details_array = array();
			foreach($empDetails as $details){
				$emp_details_array = $details;
			}

			$personalDetails = $this->promotionService->getPersonalDetails($id);
			$educationDetails = $this->promotionService->getEducationDetails($id);
			$employmentDetails = $this->promotionService->getEmploymentDetails($id);
			$employeeWorkDetails = $this->promotionService->findEmployeeExtraDetails($tableName='emp_employment_record', $this->employee_details_id);
			$lastPromotionDate = $this->promotionService->getEmployeeLastPromotion('promotion_date', $id);
			$trainingDetails = $this->promotionService->getTrainingDetails($id);
			$researchDetails = $this->promotionService->getResearchDetails($id);	
			$studyLeaveDetails = $this->promotionService->getStudyLeaveDetails($id);
			$eolLeaveDetails = $this->promotionService->getEolLeaveDetails($id);
			$payDetails = $this->promotionService->getPayDetails($employmentDetails['position_level']);

			$employeePromotionId = $this->promotionService->getEmployeePromotionId($id, $promotion_type);
			$emp_promotion_details = array();
			foreach($employeePromotionId as $det){
				$emp_promotion_details = $det;
			}
			
			$promotionDetails = $this->promotionService->getPromotionDetails($emp_promotion_details['id']);
			$lastPromotion = $this->promotionService->getEmployeeLastPromotion(NULL, $emp_promotion_details['id']);

			$pmsDetails = $this->promotionService->getPmsDetails($id, $this->userrole);
			$pmsDet = $this->promotionService->getPmsDetails($id, $this->userrole);

			$date = date("Y-m-d");
            $pdf = new PdfModel();
            $pdf->setOption('fileName', $emp_details_array['emp_id'].'PromotionDetails'.$date); // Triggers PDF download, automatically appends ".pdf"
            $pdf->setOption('display', PdfModel::DISPLAY_ATTACHMENT);
            $pdf->setOption('paperSize', 'a4'); // Defaults to "8x11"
            $pdf->setOption('paperOrientation', 'landscape'); // Defaults to "portrait"


           
            //To set view variables
            $pdf->setVariables(array(
				'id' => $id,
				'type' => $type,
				'personalDetails' => $personalDetails,
				'educationDetails' => $educationDetails,
				'employmentDetails' => $employmentDetails,
				'lastPromotionDate' => $lastPromotionDate,
				'lastPromotion' => $lastPromotion,
				'promotionDetails' => $promotionDetails,
				'trainingDetails' => $trainingDetails,
				'researchDetails' => $researchDetails,	
				'studyLeaveDetails' => $studyLeaveDetails,
				'eolLeaveDetails' => $eolLeaveDetails,
				'payDetails' => $payDetails,
				'pmsDetails' => $pmsDetails,
				'pmsDet' => $pmsDet,
				'emp_promotion_id' => $emp_promotion_details['id'],
                /*'travelAuthDetails' => $this->promotionService->getStaffTourAuthDetails($id),
                'approvingAuthority' => $this->promotionService->getTourApprovingAuthority($id),
                'travelDetails' => $this->promotionService->getStaffTourDetails($id),
                'fromDate' => $this->promotionService->findFromTravelDate($id),
                'toDate' => $this->promotionService->findToTravelDate($id),*/
           ));

            return $pdf;
        }
        else{
            $this->redirect()->toRoute('empapplypromotion');
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

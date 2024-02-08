<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Review\Controller;

use Review\Service\ReviewServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Review\Form\AcademicSelfEvaluationForm;
use Review\Form\AdministrativeSelfEvaluationForm;
use Review\Form\NatureActivityForm;
use Review\Form\AcademicWeightForm;
use Review\Form\AcademicReviewForm;
use Review\Form\AdministrativeReviewForm;
use Review\Form\FeedbackForm;
use Review\Form\StudentFeedbackForm;
use Review\Form\SearchForm;
use Review\Model\Review;
use Review\Model\NatureActivity;
use Review\Model\AcademicWeight;
use Review\Model\IwpObjectives;
use Review\Model\AcademicReview;
use Zend\Session\Container;
use Zend\View\Model\JsonModel;
use Zend\Http\Response\Stream;
use Zend\Http\Headers;

/**
 * Description of IndexController
 *
 */
 
class ReviewController extends AbstractActionController
{
	protected $reviewService;
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
	protected $role;
	protected $keyphrase = "RUB_IMS";
	
	public function __construct(ReviewServiceInterface $reviewService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->reviewService = $reviewService;
		$this->notificationService = $notificationService;
        $this->auditTrailService = $auditTrailService;
		$this->serviceLocator = $serviceLocator;
		
		/*
		 * To retrieve the user name from the session
		*/
		$authPlugin = $serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
        $this->username = $authPlugin['username'];
        $this->userrole = $authPlugin['role'];
        $this->userregion = $authPlugin['region'];
        $this->usertype = $authPlugin['user_type_id'];
		
		/*
		* Getting the student_id/employee_details_id related to username
		*/
		
		$empData = $this->reviewService->getUserDetailsId($this->username, $tableName = 'employee_details');
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			}

		//get the organisation id
		$organisationID = $this->reviewService->getOrganisationId($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}
		
		//get the user details such as name
		$this->userDetails = $this->reviewService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->reviewService->getUserImage($this->username, $this->usertype);
		
	}
	
	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }
	
	public function performanceAssessmentAction()
    {
		$this->loginDetails();
		
		//the selection of the kpi needs to be done on whether the staff is academic or administrative
		$administrativeKpi = array();
		$academicKpi = array();
		$teachingTheme = array();
		$researchTheme = array();
		$servicesTheme = array();
		$academicWeight = array();

		$iwp_deadline = $this->reviewService->getIwpDeadline('IWP Review');
		
		//get the appraisal period
		$appraisal_period = $this->getAppraisalPeriod();
		
		if(preg_match('/MODULE_TUTOR/', $this->userrole) || preg_match('/HOD/', $this->userrole) || preg_match('/ACADEMIC_STAFF/', $this->userrole)){
			$academicKpi = $this->reviewService->listEmployeeAppraisal($table = 'pms_academic_api', $this->employee_details_id, $status='Approved', $appraisal_period);
			//rewind not supported so same function used to get the array data
			$tmp_data = $this->reviewService->listEmployeeAppraisal($table = 'pms_academic_api', $this->employee_details_id, $status='Approved', $appraisal_period);
			$array_data = array();
			foreach($tmp_data as $appraisal){
				$array_data[$appraisal['id']] = $appraisal['id'];
			}
			$evaluation_for = 'academic';
			$form = new AcademicSelfEvaluationForm($array_data);
			
			//Data to fill up the table
			//activity id is hard coded. Will need to retrieve these values
			//$kpi = $this->reviewService->listEmployeeAppraisal($table = 'pms_academic_api', $this->employee_details_id);
			$teachingTheme = $this->reviewService->listActivityDetail($table = 'pms_nature_activity', $columnName = 'pms_academic_weight_id', $activity_id=1);
			$researchTheme = $this->reviewService->listActivityDetail($table = 'pms_nature_activity', $columnName = 'pms_academic_weight_id', $activity_id=2);
			$servicesTheme = $this->reviewService->listActivityDetail($table = 'pms_nature_activity', $columnName = 'pms_academic_weight_id', $activity_id=3);
			$academicWeight = $this->reviewService->listSelectData($table='pms_academic_weight', $columnName='category', $organisation_id = NULL);
		} else {
			$administrativeKpi = $this->reviewService->listAdministrativeAppraisal($table = 'iwp_subactivities',$this->employee_details_id,$status='Approved',$appraisal_period);
			//rewind not supported so same function used to get the array data
			$tmp_data = $this->reviewService->listAdministrativeAppraisal($table = 'iwp_subactivities', $this->employee_details_id, $status='Approved', $appraisal_period);
			$array_data = array();
			foreach($tmp_data as $appraisal){
				$array_data[$appraisal['id']] = $appraisal['id'];
			}
			$evaluation_for = 'administrative';
			$form = new AdministrativeSelfEvaluationForm($array_data);
		}
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 $rating_data = $this->extractFormData($type='evaluation', $array_data);
				 $review_data = $this->extractFormData($type='review', $array_data);
                 try {
					 $this->reviewService->saveSelfEvaluation($rating_data, $review_data, $evaluation_for, $this->employee_details_id);
					 $this->notificationService->saveNotification('PMS/IWP Self Assessment', 'ALL', 'ALL', 'PMS/IWP Self Assessment');
					 $this->auditTrailService->saveAuditTrail("INSERT", "PMS Self Assessment Added", "ALL", "SUCCESS");
					 return $this->redirect()->toRoute('empperformanceassessment');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return array(
			'form' => $form,
			'evaluation' => $evaluation_for,
			'administrativeKpi' => $administrativeKpi,
			'academicKpi' => $academicKpi,
			'teachingTheme' => $teachingTheme,
			'researchTheme' => $researchTheme,
			'servicesTheme' => $servicesTheme,
			'academicWeight' => $academicWeight,
			'iwp_deadline' => $iwp_deadline,
			'employee_details_id' => $this->employee_details_id);
    } 
    
	public function  studentfeedbackstatusAction()
    {
        return new ViewModel();
    }
	
    public function administrativeReviewFormAction()
    {
		$this->loginDetails();
		
        //get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$appraisal_periond = $this->getAppraisalPeriod();
			$administrativeAppraisal = $this->reviewService->listAdministrativeAppraisal($table = 'iwp_subactivities', $id, $status='Approved', $appraisal_periond);
			//rewind not supported so same function used to get the array data
			$tmp_data = $this->reviewService->listAdministrativeAppraisal($table = 'iwp_subactivities', $id, $status='Approved', $appraisal_periond);
	
			$array_data = array();
			foreach($tmp_data as $appraisal){
				$array_data[$appraisal['id']] = $appraisal['id'];
			}
			$appraisalCount = count($administrativeAppraisal);
					
			$form = new AdministrativeReviewForm($array_data);
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 $rating_data = $this->extractFormData($type='rating', $array_data);
					 try {
						 $this->reviewService->saveSupervisorEvaluation($rating_data, $evaluation_for = 'administrative', $this->employee_details_id);
						 $this->notificationService->saveNotification('PMS/IWP Administrative Supervisor Evaluation', 'ALL', 'ALL', 'PMS/IWP Administrative Supervisor Evaluation');
						 $this->auditTrailService->saveAuditTrail("INSERT", "PMS Administrative Supervisor Evaluation Added", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('empviewassessment');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			$employee_details = $this->reviewService->getEmployeeDetails($id);		
			return array(
				'form' => $form,
				'employee_details' => $employee_details,
				'administrativeAppraisal' => $administrativeAppraisal);
		}
		else {
			return $this->redirect()->toRoute('performancereviewlist');
		}
    }
	
	public function academicReviewFormAction()
    {
		$this->loginDetails();
		
        //get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			//get the appraisal period
			$appraisal_period = $this->getAppraisalPeriod();
			
			$academicAppraisal = $this->reviewService->listEmployeeAppraisal($table = 'pms_academic_api', $id, $status='Approved', $appraisal_period);
			//rewind not supported so same function used to get the array data
			$tmp_data = $this->reviewService->listEmployeeAppraisal($table = 'pms_academic_api', $id, $status='Approved', $appraisal_period);
			$array_data = array();
			foreach($tmp_data as $appraisal){
				$array_data[$appraisal['id']] = $appraisal['id'];
			}
			
			$form = new AcademicReviewForm($array_data);
	
			//Data to fill up the table
			//activity id is hard coded. Will need to retrieve these values
			//$kpi = $this->reviewService->listEmployeeAppraisal($table = 'pms_academic_api', $id);
			$teachingTheme = $this->reviewService->listActivityDetail($table = 'pms_nature_activity', $columnName = 'pms_academic_weight_id', $activity_id=1);
			$researchTheme = $this->reviewService->listActivityDetail($table = 'pms_nature_activity', $columnName = 'pms_academic_weight_id', $activity_id=2);
			$servicesTheme = $this->reviewService->listActivityDetail($table = 'pms_nature_activity', $columnName = 'pms_academic_weight_id', $activity_id=3);
			$academicWeight = $this->reviewService->listSelectData($table='pms_academic_weight', $columnName='category', $organisation_id = NULL);
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 $rating_data = $this->extractFormData($type='rating', $array_data);
					 try {
						 $this->reviewService->saveSupervisorEvaluation($rating_data, $evaluation_for = 'academic', $this->employee_details_id);
						 $this->notificationService->saveNotification('PMS/IWP Academic Supervisor Evaluation', 'ALL', 'ALL', 'PMS/IWP Academic Supervisor Evaluation');
						 $this->auditTrailService->saveAuditTrail("INSERT", "PMS Academic Supervisor Evaluation Added", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('empviewassessment');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
					
			$employee_details = $this->reviewService->getEmployeeDetails($id);	
			
			return array(
				'form' => $form,
				'employee_details' => $employee_details,
				'academicAppraisal' => $academicAppraisal,
				'teachingTheme' => $teachingTheme,
				'researchTheme' => $researchTheme,
				'servicesTheme' => $servicesTheme,
				'academicWeight' => $academicWeight);
		}
		else {
			return $this->redirect()->toRoute('performancereviewlist');
		}
		
    }
   
    public function performanceReviewListAction()
    {
		$this->loginDetails();
		
        $form = new ViewModel();
		$academicAppraisal = $this->reviewService->getAppraisalList($type='academic', $this->employee_details_id, $this->userrole, $this->organisation_id);
		$administrativeAppraisal = $this->reviewService->getAppraisalList($type='administrative', $this->employee_details_id, $this->userrole, $this->organisation_id);
		
		return array(
			'form' => $form,
			'academicAppraisal' => $academicAppraisal,
			'administrativeAppraisal' => $administrativeAppraisal,
			'keyphrase' => $this->keyphrase );
    } 
	
    public function empviewassessmentAction()
    {
		$this->loginDetails();
		
        //the selection of the kpi needs to be done on whether the staff is academic or administrative
		$administrativeKpi = array();
		$academicKpi = array();
		$teachingTheme = array();
		$researchTheme = array();
		$servicesTheme = array();
		$academicWeight = array();
		$performanceScore = array();
		$feedbackScore = array();
		
		//get the appraisal period
		$appraisal_period = $this->getAppraisalPeriod();
		
		if(preg_match('/HOD/', $this->userrole) || preg_match('/ACADEMIC_STAFF/', $this->userrole)){
			$academicKpi = $this->reviewService->listEmployeeAppraisal($table = 'pms_academic_api', $this->employee_details_id, $status='Evaluation Complete',$appraisal_period);
			//rewind not supported so same function used to get the array data
			$tmp_data = $this->reviewService->listEmployeeAppraisal($table = 'pms_academic_api', $this->employee_details_id, $status='Evaluation Complete', $appraisal_period);
			$array_data = array();
			foreach($tmp_data as $appraisal){
				$array_data[$appraisal['id']] = $appraisal['id'];
			}
			$evaluation_for = 'academic';
			$performanceScore = $this->reviewService->getPerformanceScore($evaluation_for, $this->employee_details_id);
			$feedbackScore = $this->reviewService->getFeedbackScore($evaluation_for, $this->employee_details_id);
			$form = new AcademicSelfEvaluationForm($array_data);
			
			//Data to fill up the table
			//activity id is hard coded. Will need to retrieve these values
			//$kpi = $this->reviewService->listEmployeeAppraisal($table = 'pms_academic_api', $this->employee_details_id);
			$teachingTheme = $this->reviewService->listActivityDetail($table = 'pms_nature_activity', $columnName = 'pms_academic_weight_id', $activity_id=1);
			$researchTheme = $this->reviewService->listActivityDetail($table = 'pms_nature_activity', $columnName = 'pms_academic_weight_id', $activity_id=2);
			$servicesTheme = $this->reviewService->listActivityDetail($table = 'pms_nature_activity', $columnName = 'pms_academic_weight_id', $activity_id=3);
			$academicWeight = $this->reviewService->listSelectData($table='pms_academic_weight', $columnName='category', $organisation_id = NULL);
		} else {
			$administrativeKpi = $this->reviewService->listAdministrativeAppraisal($table = 'iwp_subactivities', $this->employee_details_id, $status='Evaluation Complete', $appraisal_period);
			//rewind not supported so same function used to get the array data
			$tmp_data = $this->reviewService->listAdministrativeAppraisal($table = 'iwp_subactivities', $this->employee_details_id, $status='Evaluation Complete', $appraisal_period);
			$array_data = array();
			foreach($tmp_data as $appraisal){
				$array_data[$appraisal['id']] = $appraisal['id'];
			}
			$evaluation_for = 'administrative';
			$performanceScore = $this->reviewService->getPerformanceScore($evaluation_for, $this->employee_details_id);
			$feedbackScore = $this->reviewService->getFeedbackScore($evaluation_for, $this->employee_details_id);
			$form = new AdministrativeSelfEvaluationForm($array_data);
		}
						
		return array(
			'form' => $form,
			'evaluation' => $evaluation_for,
			'administrativeKpi' => $administrativeKpi,
			'academicKpi' => $academicKpi,
			'teachingTheme' => $teachingTheme,
			'researchTheme' => $researchTheme,
			'servicesTheme' => $servicesTheme,
			'academicWeight' => $academicWeight,
			'feedbackScore' => $feedbackScore,
			'performanceScore' => $performanceScore,
			'employee_details_id' => $this->employee_details_id);
    }
	
	//the following action is used by promotion controller as well
	//use to display pms details for each year
	public function viewEmployeePmsDetailsAction()
	{
		$this->loginDetails();
		
		//get the params
		//The first four digits are the year and the rest are the employee id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$appraisal_start_year = substr($id,0,4);
			$appraisal_year = (string)$appraisal_start_year."-".(string)($appraisal_start_year+1);
			
			$employee_id = substr($id,4,2);
			
			//the selection of the kpi needs to be done on whether the staff is academic or administrative
			$administrativeKpi = array();
			$academicKpi = array();
			$teachingTheme = array();
			$researchTheme = array();
			$servicesTheme = array();
			$academicWeight = array();
			$performanceScore = array();
			$feedbackScore = array();
			
			if(preg_match('/HOD/', $this->userrole) || preg_match('/ACADEMIC_STAFF/', $this->userrole)){
				$academicKpi = $this->reviewService->listEmployeeAppraisal($table = 'pms_academic_api', $employee_id, $status='Evaluation Complete', $appraisal_year);
				//rewind not supported so same function used to get the array data
				$tmp_data = $this->reviewService->listEmployeeAppraisal($table = 'pms_academic_api', $employee_id, $status='Evaluation Complete', $appraisal_year);
				$array_data = array();
				foreach($tmp_data as $appraisal){
					$array_data[$appraisal['id']] = $appraisal['id'];
				}
				$evaluation_for = 'academic';
				$performanceScore = $this->reviewService->getPerformanceScore($evaluation_for, $employee_id);
				$feedbackScore = $this->reviewService->getFeedbackScore($evaluation_for, $employee_id);
				$form = new AcademicSelfEvaluationForm($array_data);
				
				//Data to fill up the table
				//activity id is hard coded. Will need to retrieve these values
				//$kpi = $this->reviewService->listEmployeeAppraisal($table = 'pms_academic_api', $this->employee_details_id);
				$teachingTheme = $this->reviewService->listActivityDetail($table = 'pms_nature_activity', $columnName = 'pms_academic_weight_id', $activity_id=1);
				$researchTheme = $this->reviewService->listActivityDetail($table = 'pms_nature_activity', $columnName = 'pms_academic_weight_id', $activity_id=2);
				$servicesTheme = $this->reviewService->listActivityDetail($table = 'pms_nature_activity', $columnName = 'pms_academic_weight_id', $activity_id=3);
				$academicWeight = $this->reviewService->listSelectData($table='pms_academic_weight', $columnName='category', $organisation_id = NULL);
			} else {
				$administrativeKpi = $this->reviewService->listAdministrativeAppraisal($table = 'iwp_subactivities', $employee_id, $status='Evaluation Complete', $appraisal_year);
				//rewind not supported so same function used to get the array data
				$tmp_data = $this->reviewService->listAdministrativeAppraisal($table = 'iwp_subactivities', $employee_id, $status='Evaluation Complete', $appraisal_year);
				$array_data = array();
				foreach($tmp_data as $appraisal){
					$array_data[$appraisal['id']] = $appraisal['id'];
				}
				$evaluation_for = 'administrative';
				$performanceScore = $this->reviewService->getPerformanceScore($evaluation_for, $employee_id);
				$feedbackScore = $this->reviewService->getFeedbackScore($evaluation_for, $employee_id);
				$form = new AdministrativeSelfEvaluationForm($array_data);
			}
							
			return array(
				'form' => $form,
				'evaluation' => $evaluation_for,
				'administrativeKpi' => $administrativeKpi,
				'academicKpi' => $academicKpi,
				'teachingTheme' => $teachingTheme,
				'researchTheme' => $researchTheme,
				'servicesTheme' => $servicesTheme,
				'academicWeight' => $academicWeight,
				'feedbackScore' => $feedbackScore,
				'performanceScore' => $performanceScore,
				'appraisal_year' => $appraisal_year,
				'employee_details_id' => $employee_id);
		}
		else {
			return $this->redirect()->toRoute('pmsemployeelist');
		}
	}
    
	public function feedbacksAction()
    {
		$this->loginDetails();
		
        //the nominated employees is the list of employees that have been nominated
		$nominatedEmployee = $this->reviewService->getNominatedEmployee($this->employee_details_id);
		$peerList = $this->reviewService->getNominationList($tableName = 'peer_nomination', $this->employee_details_id);
		$subordinateList = $this->reviewService->getNominationList($tableName = 'subordinate_nomination', $this->employee_details_id);
		$beneficiaryList = $this->reviewService->getNominationList($tableName = 'beneficiary_nomination', $this->employee_details_id);
		
		return array(
			'nominatedEmployee' => $nominatedEmployee,
			'peerList' => $peerList,
			'subordinateList' => $subordinateList,
			'beneficiaryList' => $beneficiaryList,
			'keyphrase' => $this->keyphrase);
    }
	
    public function peerFeedbackformAction()
    {
		$this->loginDetails();
		
        //get the id for who the feedback is for and then get details of employee
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$revieweeDetails = $this->reviewService->getRevieweeDetails($id, 'peer_nomination');
		
			//listSelectData provides the necessary feedback questions and used here
			$peerFeedback = $this->reviewService->listSelectData($tableName='peer_feedback_questions', $columnName='questions', $empIds= NULL);
			
			$questionsCount = count($peerFeedback);
			$form = new FeedbackForm($questionsCount);
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 $rating_data = $this->extractFormData($type='feedback', $peerFeedback);
					 $appraisal_period = $this->getRequest()->getPost('appraisal_period');
					 //this is who the feedback is for. $this->employee_details_id is the feedback provider
					 $employee_id = $this->getRequest()->getPost('employee_details_id');
					 try {
						 //here the $id is for the nomination id so that we can update the nomination table status
						 $this->reviewService->saveFeedbackEvaluation('peer', $id, $rating_data, $employee_id, $appraisal_period, $this->employee_details_id);
						 $this->auditTrailService->saveAuditTrail("INSERT", "Peer Feedback Evaluation Added", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('feedbacks');
						 
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return array(
				'form' => $form,
				'peerFeedback' => $peerFeedback,
				'revieweeDetails' => $revieweeDetails);
		}
		else {
			return $this->redirect()->toRoute('feedbacks');
		}
    }
	
    public function subordinateFeedbackformAction()
    {
		$this->loginDetails();
		
        //get the id for who the feedback is for and then get details of employee
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$revieweeDetails = $this->reviewService->getRevieweeDetails($id, 'subordinate_nomination');
		
			//listSelectData provides the necessary feedback questions and used here
			$subordinateFeedback = $this->reviewService->listSelectData($tableName='subordinate_feedback_questions', $columnName='questions', $empIds= NULL);
			
			$questionsCount = count($subordinateFeedback);
			$form = new FeedbackForm($questionsCount);
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 $rating_data = $this->extractFormData($type='feedback', $subordinateFeedback);
					 $appraisal_period = $this->getRequest()->getPost('appraisal_period');
					 $employee_id = $this->getRequest()->getPost('employee_details_id');
					 try {
						 //here the $id is for the nomination id so that we can update the nomination table status
						 $this->reviewService->saveFeedbackEvaluation('subordinate', $id, $rating_data, $employee_id, $appraisal_period, $this->employee_details_id);
						 $this->auditTrailService->saveAuditTrail("INSERT", "Subordinated Feedback Evaluation Added", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('feedbacks');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return array(
				'form' => $form,
				'subordinateFeedback' => $subordinateFeedback,
				'revieweeDetails' => $revieweeDetails);
		}
		else {
			return $this->redirect()->toRoute('feedbacks');
		}
		
    }
	
	public function beneficiaryFeedbackformAction()
    {
		$this->loginDetails();
		
        //get the id for who the feedback is for and then get details of employee
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$revieweeDetails = $this->reviewService->getRevieweeDetails($id, 'beneficiary_nomination');
		
			//listSelectData provides the necessary feedback questions and used here
			$beneficiaryFeedback = $this->reviewService->listSelectData($tableName='beneficiary_feedback_questions', $columnName='questions', $empIds= NULL);
			
			$questionsCount = count($beneficiaryFeedback);
			$form = new FeedbackForm($questionsCount);
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 $rating_data = $this->extractFormData($type='feedback', $beneficiaryFeedback);
					 $appraisal_period = $this->getRequest()->getPost('appraisal_period');
					 $employee_id = $this->getRequest()->getPost('employee_details_id');
					 try {
						 //here the $id is for the nomination id so that we can update the nomination table status
						 $this->reviewService->saveFeedbackEvaluation('beneficiary', $id, $rating_data, $employee_id, $appraisal_period, $this->employee_details_id);
						 $this->auditTrailService->saveAuditTrail("INSERT", "Beneficiary Feedback Evaluation Added", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('feedbacks');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
			 }
			
			return array(
				'form' => $form,
				'beneficiaryFeedback' => $beneficiaryFeedback,
				'revieweeDetails' => $revieweeDetails);
		}
		else {
			return $this->redirect()->toRoute('feedbacks');
		}
    }
	
    public function studentFeedbackAction()
    {
		$this->loginDetails();
		
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');

        //listSelectData provides the necessary feedback questions and used here
        $studentFeedback = $this->reviewService->listSelectData($tableName='student_feedback_questions', $columnName='questions', $empIds= NULL);

        $questionsCount = count($studentFeedback);
        $form = new StudentFeedbackForm($dbAdapter);
		
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 $rating_data = $this->extractFormData($type='feedback', $studentFeedback);
                 $appraisal_period = $this->getRequest()->getPost('appraisal_period');
                 //the academic module is actually the id from the table "academic module tutor"
                 //can be used to extract the academic modules allocation id and hence module code
                 $academic_module = $this->getRequest()->getPost('academic_module');
                 $module_tutor = $this->getRequest()->getPost('module_tutor');
                 try {
                        //here the $id is for the nomination id so that we can update the nomination table status
                        $this->reviewService->saveStudentFeedback($rating_data, $academic_module, $module_tutor, $appraisal_period, 0);
					 $this->auditTrailService->saveAuditTrail("INSERT", "Student Feedback Evaluation Added", "ALL", "SUCCESS");
                        return $this->redirect()->toRoute('feedbacks');
                }
                catch(\Exception $e) {
                                die($e->getMessage());
                                // Some DB Error happened, log it and let the user know
                }
                return $this->redirect()->toRoute('studentfeedbacks');
             }
         }
		
		return array(
			'form' => $form,
			'studentFeedback' => $studentFeedback);

    } 
	
	public function administrativeReviewAction()
    {
		$this->loginDetails();
		
        //get 'primary key' id for Emp ID
		$employee_id = $this->reviewService->findEmployeeId($this->username);
		
		$form = new AdministrativeReviewForm();
		$administrativeModel = new IwpObjectives();
		$form->bind($administrativeModel);
		
		//Need to send value of the table name and columns
		//Emp Ids is an array of Ids of Directors
		$objectivesSelect = $this->reviewService->listSelectData($tableName='awpa_objectives_activity', $columnName='activity_name', $empIds= array(1,3,4));
		
		//Data to fill up the table
		//only Temporary as it will be redirected to View Vision Mission Page
		// Just to check whether data is being inserted or not
		$kpi = $this->reviewService->listAll($table = 'awpa_activities');
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->reviewService->saveKpi($planningModel);
					 $this->notificationService->saveNotification('PMS/IWP Administrative Review', 'ALL', 'ALL', 'PMS/IWP Administrative Review');
					 $this->auditTrailService->saveAuditTrail("INSERT", "PMS Administrative Review Added", "ALL", "SUCCESS");
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
				 return $this->redirect()->toRoute('academicreview');
             }
         }
        return array(
				'form' => $form,
				'selectData' => $objectivesSelect,
				'kpi' => $kpi);
    }
	
    public function academicReviewAction()
    {
		$this->loginDetails();
		
        //get 'primary key' id for Emp ID
		$employee_id = $this->reviewService->findEmployeeId($this->username);
		
		$form = new AcademicReviewForm();
		$academicModel = new AcademicReview();
		$form->bind($academicModel);
		
		//Need to send value of the table name and columns
		$natureActivitySelect = $this->reviewService->listSelectData($tableName='pms_nature_activity', $columnName='nature_of_activity', $empIds = NULL);
		
		//Data to fill up the table
		//activity id is hard coded. Will need to retrieve these values
		$kpi = $this->reviewService->listEmployeeReview($table = 'pms_academic_api', $employee_id);
		$teachingTheme = $this->reviewService->listActivityDetail($table = 'pms_nature_activity', $columnName = 'pms_academic_weight_id', $activity_id=1);
		$researchTheme = $this->reviewService->listActivityDetail($table = 'pms_nature_activity', $columnName = 'pms_academic_weight_id', $activity_id=2);
		$servicesTheme = $this->reviewService->listActivityDetail($table = 'pms_nature_activity', $columnName = 'pms_academic_weight_id', $activity_id=3);
		
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->reviewService->saveAcademicReview($academicModel);
					 $this->notificationService->saveNotification('PMS/IWP Academic Review', 'ALL', 'ALL', 'PMS/IWP Academic Review');
					 $this->auditTrailService->saveAuditTrail("INSERT", "PMS Academic Review Added", "ALL", "SUCCESS");
					 return $this->redirect()->toRoute('academicreview');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
        return array(
				'employee_id' => $employee_id,
				'form' => $form,
				'natureActivity' => $natureActivitySelect,
				'kpi' => $kpi,
				'teachingTheme' => $teachingTheme,
				'researchTheme' => $researchTheme,
				'servicesTheme' => $servicesTheme);
    }
    
	public function viewPeerAction()
    {
		$this->loginDetails();
		
        $form = new ReviewForm();
		$reviewModel = new Review();
		$form->bind($reviewModel);
		
		$students = $this->reviewService->listAll($tableName='student');
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->reviewService->save($reviewModel);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'students' => $students);
    }
	
	//list the details of the employee for HR to view PMS Rating
	public function pmsEmployeeListAction()
	{
		$this->loginDetails();
		
       $form = new SearchForm();
	   $appraisal_period = NULL;
	   
	   $employeeList = array();
	   $appraisal_period_list = array();
	   for($i=0; $i<=5; $i++){
		   $appraisal_period_list[(date('Y')-($i+1))."-".(date('Y')-($i))] = (date('Y')-($i+1))."-".(date('Y')-($i));
	   }
	   
	   $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$empName = $this->getRequest()->getPost('employee_name');
				$empId = $this->getRequest()->getPost('emp_id');
				$appraisal_period = $this->getRequest()->getPost('appraisal_period');
				$employeeList = $this->reviewService->getEmployeeList($empName, $empId, $department=NULL, $this->organisation_id);
             }
         }
		
		return new ViewModel(array(
            'form' => $form,
			'keyphrase' => $this->keyphrase,
			'appraisal_period_list' => $appraisal_period_list,
			'appraisal_period' => $appraisal_period,
			'employeeList' => $employeeList
            ));
	}
	
	/*
	* Check and see whether the following functions are used or not. 
	* Seems like a reptition of function from Appraisal Controller to added Academic Weight etc
	*/
	
	public function addNatureActivityAction()
    {
		$this->loginDetails();
		
        $activityForm = new NatureActivityForm();
		$activityModel = new NatureActivity();
		$activityForm->bind($activityModel);
		
		$academicForm = new AcademicWeightForm();
		
		//$students = $this->reviewService->listAll($tableName='student');
		$academicWeight = $this->reviewService->listAll($tableName='pms_academic_weight');
		$natureActivity = $this->reviewService->listAll($tableName='pms_nature_activity');
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $academicForm->setData($request->getPost());
			 var_dump($activityForm);
			 die();
             if ($form->isValid()) {
                 try {
					 $this->reviewService->save($reviewModel);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'activityForm' => $activityForm,
			'academicForm' => $academicForm,
			'academicWeight' => $academicWeight,
			'natureActivity' => $natureActivity);
    }
	
	public function addAcademicWeightAction()
    {
		$this->loginDetails();
			
		$academicForm = new AcademicWeightForm();
		$academicModel = new AcademicWeight();
		$academicForm->bind($academicModel);
		
		//$students = $this->reviewService->listAll($tableName='student');
		$academicWeight = array();
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $academicForm->setData($request->getPost());
			 var_dump($academicForm);
			 die();
             if ($form->isValid()) {
                 try {
					 $this->reviewService->save($reviewModel);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'academicForm' => $academicForm,
			'academicWeight' => $academicWeight);
    }
	
	public function addAcademicApiAction()
    {
		$this->loginDetails();
		
        $form = new ReviewForm();
		$reviewModel = new Review();
		$form->bind($reviewModel);
		
		$students = $this->reviewService->listAll($tableName='student');
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->reviewService->save($reviewModel);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'students' => $students);
    }
	
	public function listNatureActivityAction()
    {
		$this->loginDetails();
		
        $form = new ReviewForm();
		$reviewModel = new Review();
		$form->bind($reviewModel);
		
		$approvedList = array();
		$rejectedList = array();
		$pendingList = array();
		return array(
			'form' => $form,
			'approvedList' => $approvedList,
			'rejectedList' => $rejectedList,
			'pendingList' => $pendingList);
    }
	
	public function listAcademicApiAction()
    {
		$this->loginDetails();
		
        $form = new ReviewForm();
		$reviewModel = new Review();
		$form->bind($reviewModel);
		
		$approvedList = array();
		$rejectedList = array();
		$pendingList = array();
		return array(
			'form' => $form,
			'approvedList' => $approvedList,
			'rejectedList' => $rejectedList,
			'pendingList' => $pendingList);
    }
    
	public function listReviewAction()
    {
		$this->loginDetails();
		
        $form = new ReviewForm();
		$reviewModel = new Review();
		$form->bind($reviewModel);
		
		$approvedList = array();
		$rejectedList = array();
		$pendingList = array();
		return array(
			'form' => $form,
			'approvedList' => $approvedList,
			'rejectedList' => $rejectedList,
			'pendingList' => $pendingList);
    }
	
	//to Get the Appraisal Period
	private function getAppraisalPeriod()
	{
		$appraisal_period = NULL;
		if(date('m') < 6){
			$appraisal_period = (date('Y')-1)."-".(date('Y'));
		 } else {
			 $appraisal_period = (date('Y'))."-".(date('Y')+1);
		 }
		 
		 return $appraisal_period;
	}
	
	//Decrypt function
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
	
    //the following function is to extract the data from the form 
    // and return clean data to be inserted into database
    public function extractFormData($type, $tmp_data)
    {
            $evaluationData = array();
            //evaluation data => 'evaluation_'.$i.$j,
            if($type == 'feedback'){
                    for($i=1; $i<= count($tmp_data); $i++){
                            $evaluationData[$i] = $this->getRequest()->getPost('evaluation'.$i);
                    }
            } else{
                    foreach($tmp_data as $key=>$value){
                            $evaluationData[$key] = $this->getRequest()->getPost($type.$value);
                    }
            }

            return $evaluationData;
    }
    
}

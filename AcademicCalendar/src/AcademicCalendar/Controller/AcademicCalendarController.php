<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AcademicCalendar\Controller;

use Zend\ServiceManager\ServiceLocatorInterface;
use AcademicCalendar\Service\AcademicCalendarServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use AcademicCalendar\Model\AcademicCalendar;
use AcademicCalendar\Model\AcademicEvent;
use AcademicCalendar\Form\AcademicCalendarForm;
use AcademicCalendar\Form\AcademicEventForm;
use AcademicCalendar\Form\StaffCalendarEventForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;

/**
 * Description of IndexController
 *
 * @author Mendrel
 */
class AcademicCalendarController extends AbstractActionController
{
    
	protected $calendarService;
	protected $notificationService;
    protected $auditTrailService;
	protected $serviceLocator;
	protected $username;
	protected $userrole;
    protected $usertype;
    protected $userDetails;
	protected $employee_details_id;
	protected $organisation_id;

	protected $keyphrase = "RUB_IMS";
	
	public function __construct(AcademicCalendarServiceInterface $calendarService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->calendarService = $calendarService;
		$this->notificationService = $notificationService;
        $this->auditTrailService = $auditTrailService;
		$this->serviceLocator = $serviceLocator;
		
		/*
         * To retrieve the user name from the session
        */
        $authPlugin = $this->serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
        // Use service locator to get the authPlugin
        $this->username = $authPlugin['username'];
        $this->usertype = $authPlugin['user_type_id'];
		$this->userrole = $authPlugin['role'];
		$this->userregion = $authPlugin['region'];
		
		/*
		* Getting the employee_details_id related to username
		*/
		
		$empData = $this->calendarService->getUserDetailsId($this->username);
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			}
			
		//get the organisation id
		$organisationID = $this->calendarService->getOrganisationId($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}

		//get the user details such as name
        if($this->usertype == '1'){
            $this->userDetails = $this->calendarService->getUserDetails($this->username, $tableName = 'employee_details');
        }
        else{
            $this->userDetails = $this->calendarService->getUserDetails($this->username, $tableName = 'student');

        }
	}


	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
    }

	
	public function addAcademicCalendarAction()
	{
		$this->loginDetails();
		$form = new AcademicCalendarForm();
		$calendarModel = new AcademicCalendar();
		$form->bind($calendarModel);
		
		$calendarDates = $this->calendarService->listAll('academic_calendar', $this->organisation_id);
		$holidayCalendarDates = $this->calendarService->listAll('holiday', $this->organisation_id);
		$present_year = date('Y');
		$academic_year_list = array();
		//$academic_year_list[($present_year-1)."-".$present_year] = ($present_year-1)."-".$present_year;
		$event_list = $this->calendarService->listSelectData('academic_calendar_events','academic_event',$this->organisation_id);

		$academic_event_details = $this->calendarService->getSemester($this->organisation_id);
        $academic_session = $academic_event_details['academic_event'];
        $academic_year = $academic_event_details['academic_year'];

		
		for($i=2; $i>=0; $i--){
			$academic_year_list[($present_year-$i)."-".($present_year-$i+1)] = ($present_year-$i)."-".($present_year-$i+1);
		}

		$message = NULL;
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {				 
					 $this->calendarService->saveAcademicCalendar($calendarModel);
					 $this->auditTrailService->saveAuditTrail("INSERT", "Academic Calendar", "ALL", "SUCCESS");
					 $this->flashMessenger()->addMessage('Academic Calendar was added successfully');
					 return $this->redirect()->toRoute('addcalendar');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'calendarDates' => $calendarDates,
			'holidayCalendarDates' => $holidayCalendarDates,
			'academic_year_list' => $academic_year_list,
			'academic_year' => $academic_year,
			'academic_session' => $academic_session,
			'event_list' => $event_list,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
			));
	}
	
	public function editAcademicCalendarAction()
	{
		$this->loginDetails();
		//get the calendar id
		//get the student id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){ 
        	$form = new AcademicCalendarForm();
			$calendarModel = new AcademicCalendar();
			$form->bind($calendarModel);
			
			$calendarDetail = $this->calendarService->findCalendarDetail($id);
			$calendarDates = $this->calendarService->listAll('academic_calendar', $this->organisation_id);

			$present_year = date('Y');
			$academic_year_list = array();
			//$academic_year_list[($present_year-1)."-".$present_year] = ($present_year-1)."-".$present_year;
			$event_list = $this->calendarService->listSelectData('academic_calendar_events','academic_event',$this->organisation_id);
			
			for($i=2; $i>=0; $i--){
				$academic_year_list[($present_year-$i)."-".($present_year-$i+1)] = ($present_year-$i)."-".($present_year-$i+1);
			}

			$message = NULL;
					
			$request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) { 
	                 try {
						 $this->calendarService->saveAcademicCalendar($calendarModel);
						 $this->auditTrailService->saveAuditTrail("EDIT", "Academic Calendar", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage('Academic Calendar was edited successfully');
						 return $this->redirect()->toRoute('addcalendar');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
	             }
	         }
			
			return new ViewModel(array(
				'form' => $form,
				'calendarDates' => $calendarDates,
				'calendarDetail' => $calendarDetail,
				'event_list' => $event_list,
				'academic_year_list' => $academic_year_list,
				'message' => $message,
				));
        }else{
        	$this->redirect()->toRoute('addcalendar');
        }
	}
	
	//Adding and Editing Academic Events
	public function addAcademicEventAction()
	{
		$this->loginDetails();
		
		$form = new AcademicEventForm();
		$eventModel = new AcademicEvent();
		$form->bind($eventModel);
		
		$eventList = $this->calendarService->listAll('academic_calendar_events', $this->organisation_id);

		$message = NULL;
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {				 
					 $this->calendarService->saveAcademicEvent($eventModel);
					 $this->auditTrailService->saveAuditTrail("INSERT", "Academic Calendar Event", "ALL", "SUCCESS");
					 $this->flashMessenger()->addMessage("Academic Event was added successfully");
					 return $this->redirect()->toRoute('addacademicevent');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'eventList' => $eventList,
			'organisation_id' => $this->organisation_id,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
			));
	}
	
	public function editAcademicEventAction()
	{
		$this->loginDetails();
		//get the calendar id
		//get the student id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
        	$form = new AcademicEventForm();
			$eventModel = new AcademicEvent();
			$form->bind($eventModel);
			
			$eventDetail = $this->calendarService->findEventDetail($id);

			$eventDates = $this->calendarService->listAll('academic_calendar_events', $this->organisation_id);

			//var_dump($eventDates); die();

			$message = NULL;
					
			$request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) {
	                 try {
						 $this->calendarService->saveAcademicEvent($eventModel);
						 $this->auditTrailService->saveAuditTrail("EDIT", "Academic Calendar", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage("Academic Event was edited successfully");
						 return $this->redirect()->toRoute('addacademicevent');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
	             }
	         }
			
			return new ViewModel(array(
				'form' => $form,
				'eventDates' => $eventDates,
				'eventDetail' => $eventDetail,
				'message' => $message,
				));
        }else{
        	$this->redirect()->toRoute('addacademicevent');
        }
	}
	
	//Action to add event by staff for personal reminders
	public function addStaffCalendarEventAction()
	{
		$this->loginDetails();
		$form = new StaffCalendarEventForm();
		$calendarModel = new AcademicCalendar();
		$form->bind($calendarModel);
		
		$calendarDates = $this->calendarService->getMyEvents($this->employee_details_id);
		$present_year = date('Y');
		$academic_year_list = array();
		//$academic_year_list[($present_year-1)."-".$present_year] = ($present_year-1)."-".$present_year;
		$event_list = $this->calendarService->listSelectData('academic_calendar_events','academic_event',$this->organisation_id);
		
		for($i=2; $i>=0; $i--){
			$academic_year_list[($present_year-$i)."-".($present_year-$i+1)] = ($present_year-$i)."-".($present_year-$i+1);
		}
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {				 
					 $this->calendarService->saveAcademicCalendar($calendarModel);
					 $this->auditTrailService->saveAuditTrail("INSERT", "Academic Calendar", "ALL", "SUCCESS");
					 return $this->redirect()->toRoute('addcalendar');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'calendarDates' => $calendarDates,
			'academic_year_list' => $academic_year_list,
			'event_list' => $event_list,
			'employee_details_id' => $this->employee_details_id,
			'keyphrase' => $this->keyphrase,
			));
	}
	
	public function editStaffCalendarEventAction()
	{
		$this->loginDetails();
		//get the calendar id
		//get the student id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
        	$form = new StaffCalendarEventForm();
			$calendarModel = new AcademicCalendar();
			$form->bind($calendarModel);
			
			$calendarDetail = $this->calendarService->findCalendarDetail($id);
			$calendarDates = $this->calendarService->getMyEvents($this->employee_details_id);
					
			$request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) {
	                 try {
						 $this->calendarService->saveAcademicCalendar($calendarModel);
						 $this->auditTrailService->saveAuditTrail("EDIT", "Academic Calendar", "ALL", "SUCCESS");
						 $this->redirect()->toRoute('addcalendar');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
	             }
	         }
			
			return new ViewModel(array(
				'form' => $form,
				'calendarDates' => $calendarDates,
				'calendarDetail' => $calendarDetail
				));
        }else{
        	$this->redirect()->toRoute('addcalendar');
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

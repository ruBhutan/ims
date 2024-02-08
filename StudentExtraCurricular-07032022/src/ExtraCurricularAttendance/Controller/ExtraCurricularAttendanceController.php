<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ExtraCurricularAttendance\Controller;

use ExtraCurricularAttendance\Service\ExtraCurricularAttendanceServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ExtraCurricularAttendance\Form\SearchForm;
use ExtraCurricularAttendance\Form\ExtraCurricularAttendanceForm;
use ExtraCurricularAttendance\Form\ClubAttendanceForm;
use ExtraCurricularAttendance\Form\SocialEventForm;
use ExtraCurricularAttendance\Form\ExtraCurricularSearchForm;
use ExtraCurricularAttendance\Model\ExtraCurricularAttendance;
use ExtraCurricularAttendance\Model\ClubAttendance;
use ExtraCurricularAttendance\Model\SocialEvent;
use Zend\Session\Container;

/**
 * Description of IndexController
 *
 */
 
class ExtraCurricularAttendanceController extends AbstractActionController
{
	protected $attendanceService;
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
	
	public function __construct(ExtraCurricularAttendanceServiceInterface $attendanceService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->attendanceService = $attendanceService;
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
		
		$empData = $this->attendanceService->getUserDetailsId($this->username, $tableName = 'employee_details');
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			}

		if($this->employee_details_id == NULL)
		{
			$studentData = $this->attendanceService->getUserDetailsId($this->username, $tableName = 'student');
			foreach($studentData as $std){
			$this->student_id = $std['id'];
			$this->organisation_id = $std['organisation_id'];
			}
		}
		
		//get the organisation id
		if($this->organisation_id == NULL){
			$organisationID = $this->attendanceService->getOrganisationId($this->username);
				foreach($organisationID as $organisation){
					$this->organisation_id = $organisation['organisation_id'];
				} 
		}
		//get the user details such as name
        $this->userDetails = $this->attendanceService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->attendanceService->getUserImage($this->username, $this->usertype);
		
	}

	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }
	
	public function addSocialEventAction()
	{
		$this->loginDetails();
		$form = new SocialEventForm();
		$eventModel = new SocialEvent();
		$form->bind($eventModel);
		
		$message = NULL;
		$events = $this->attendanceService->listAll('social_events', $this->organisation_id);

		$academic_year = NULL;
		$month = date('m'); 
		if($month >= 1 && $month <= 6){
			$start_year = date('Y')-1;
			$end_year = date('Y');
			$academic_year = $start_year.'-'.$end_year;
		} else{
			$start_year = date('Y');
			$end_year = date('Y')+1;
			$academic_year = $start_year.'-'.$end_year;
		}
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->attendanceService->saveSocialEvent($eventModel);
					 $this->auditTrailService->saveAuditTrail("INSERT", "Social Events", "ALL", "SUCCESS");
					 $this->flashMessenger()->addMessage('Social Event was successfully added');
					 return $this->redirect()->toRoute('addsocialevent');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
				'form' => $form,
				'message' => $message,
				'events' => $events,
				'academic_year' => $academic_year,
				'keyphrase' => $this->keyphrase,
				'organisation_id' => $this->organisation_id,
			);
	}
	
	public function editSocialEventAction()
	{
		$this->loginDetails();

		//get the id of the organisation
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){ 
        	$form = new SocialEventForm();
			$eventModel = new SocialEvent();
			$form->bind($eventModel);
			
			$events = $this->attendanceService->listAll('social_events', $this->organisation_id);
			$eventDetail = $this->attendanceService->getSocialEvent($id);

			$academic_year = NULL;
			$month = date('m'); 
			if($month >= 1 && $month <= 6){
				$start_year = date('Y')-1;
				$end_year = date('Y');
				$academic_year = $start_year.'-'.$end_year;
			} else{
				$start_year = date('Y');
				$end_year = date('Y')+1;
				$academic_year = $start_year.'-'.$end_year;
			}
	        
	        $request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) { 
	                 try {
						 $this->attendanceService->saveSocialEvent($eventModel);
						 $this->auditTrailService->saveAuditTrail("EDIT", "Social Events", "ALL", "SUCCESS");
						  $this->flashMessenger()->addMessage('Social Event was successfully edited');
					 		return $this->redirect()->toRoute('addsocialevent');
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
				'events' => $events,
				'eventDetail' => $eventDetail,
				'academic_year' => $academic_year,
				'organisation_id' => $this->organisation_id);
        }
        else{
        	return $this->redirect()->toRoute('addsocialevent');
        }
	}
    
	public function studentExtraCurricularAction()
	{
		$this->loginDetails();
		$form = new ExtraCurricularSearchForm();
	   //preset values
	   	$studentList = array();
		$studentCount = NULL;
		$event_name = NULL;
		$date_event = NULL;
		$studentName = NULL;
		$studentId = NULL;
		$programme = NULL;
		$year = NULL;
		
		$programmeList = $this->attendanceService->listSelectData($tableName = 'programmes' , $columnName = 'programme_name', $this->organisation_id);
		$eventList = $this->attendanceService->listSelectData($tableName = 'social_events' , $columnName = 'event', $this->organisation_id);
		$studentYear = $this->attendanceService->listSelectData($tableName = 'programme_year', $columnName = 'year', NULL);

		$message = NULL;
	    
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$studentName = $this->getRequest()->getPost('student_name');
				$studentId = $this->getRequest()->getPost('student_id');
				$programme = $this->getRequest()->getPost('programme');
				$year = $this->getRequest()->getPost('year');
				$event_name = $this->getRequest()->getPost('social_events_id');
				$date_event = $this->getRequest()->getPost('date');

				$check_attendance = $this->attendanceService->crossCheckExtraCurricularAttendance($programme, $year, $event_name);

				if(!empty($check_attendance)){
					$message = 'Failure';
					$this->flashMessenger()->addMessage("You have already added attendance of this particular programme for this particular extra curricular event");
				}else{
					$studentList = $this->attendanceService->getStudentList($studentName, $studentId, $programme, $year, $this->organisation_id);
					$studentCount = $this->attendanceService->getStudentCount($studentName, $studentId, $programme, $year, $this->organisation_id);
					$attendanceForm = new ExtraCurricularAttendanceForm($studentCount);
					$attendanceModel = new ExtraCurricularAttendance();
					$attendanceForm->bind($attendanceModel);
				}				
             }
         }
		
		$attendanceForm = new ExtraCurricularAttendanceForm($studentCount);
		$attendanceModel = new ExtraCurricularAttendance();
		$attendanceForm->bind($attendanceModel);
		
		
		return array(
            'form' => $form,
			'programmeList' => $programmeList,
			'eventList' => $eventList,
			'studentList' => $studentList,
			'attendanceForm' => $attendanceForm,
			'studentCount' => $studentCount,
			'date_event' => $date_event,
			'event_name' => $event_name,
			'studentName' => $studentName,
			'studentId' => $studentId,
			'programme' => $programme,
			'year' => $year,
			'studentYear' => $studentYear,
			'message' => $message
            );
	}
	
	public function addExtraCurricularAttendanceAction()
	{
		$form = new ExtraCurricularAttendanceForm($studentCount= 'null');
		$attendanceModel = new ExtraCurricularAttendance();
		$form->bind($attendanceModel);
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 $data = $this->extractFormData();
				 $programme = $this->getRequest()->getPost('programme');
				 $studentName = $this->getRequest()->getPost('student_name');
				 $studentId = $this->getRequest()->getPost('student_id');
				 $year = $this->getRequest()->getPost('year');
				 $event_name = $this->getRequest()->getPost('social_events_id');
				 $date_event = $this->getRequest()->getPost('date');
                 try {
					 $this->attendanceService->saveExtraCurricularAttendance($data, $programme, $year, $studentName, $studentId, $event_name, $date_event, $this->organisation_id);
					 $this->auditTrailService->saveAuditTrail("INSERT", "Student Extracurricular Attendance", "ALL", "SUCCESS");
					 $this->flashMessenger()->addMessage('Extracurricular Attendance has been successfully entered');
					 return $this->redirect()->toRoute('studentextracurricular');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array('form' => $form);
	}


	public function editExtraCurricularAttendanceAction()
	{
		$this->loginDetails();
		$form = new ExtraCurricularSearchForm();
	   //preset values
	   	$studentList = array();
		$studentCount = NULL;
		$event_name = NULL;
		$date_event = NULL;
		$studentName = NULL;
		$studentId = NULL;
		$programme = NULL;
		$year = NULL;
		$studentAttendance = array();
		
		$programmeList = $this->attendanceService->listSelectData($tableName = 'programmes' , $columnName = 'programme_name', $this->organisation_id);
		$eventList = $this->attendanceService->listSelectData($tableName = 'social_events' , $columnName = 'event', $this->organisation_id);
		$studentYear = $this->attendanceService->listSelectData($tableName = 'programme_year', $columnName = 'year', NULL);

		$message = NULL;
	    
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$studentName = $this->getRequest()->getPost('student_name');
				$studentId = $this->getRequest()->getPost('student_id');
				$programme = $this->getRequest()->getPost('programme');
				$year = $this->getRequest()->getPost('year');
				$event_name = $this->getRequest()->getPost('social_events_id');
				$date_event = $this->getRequest()->getPost('date');

				$check_attendance = $this->attendanceService->crossCheckExtraCurricularAttendance($programme, $year, $event_name);

				if(empty($check_attendance)){
					$message = 'Failure';
					$this->flashMessenger()->addMessage("You haven't entered the attendance. Please enter attendance first");
				}else{
					$studentList = $this->attendanceService->getStudentExtraCurricularAttendance($studentName, $studentId, $programme, $year, $event_name, $this->organisation_id);
					$studentCount = $this->attendanceService->getStudentCount($studentName, $studentId, $programme, $year, $this->organisation_id);
					//$studentAttendance = $this->attendanceService->getExtraCurricularAttendanceList($programme, $year, $event_name, $this->organisation_id);

					$attendanceForm = new ExtraCurricularAttendanceForm($studentCount);
					$attendanceModel = new ExtraCurricularAttendance();
					$attendanceForm->bind($attendanceModel);
				}				
             }
         }
		
		$attendanceForm = new ExtraCurricularAttendanceForm($studentCount);
		$attendanceModel = new ExtraCurricularAttendance();
		$attendanceForm->bind($attendanceModel);
		
		
		return array(
            'form' => $form,
			'programmeList' => $programmeList,
			'eventList' => $eventList,
			'studentList' => $studentList,
			'attendanceForm' => $attendanceForm,
			'studentCount' => $studentCount,
			'date_event' => $date_event,
			'event_name' => $event_name,
			'studentName' => $studentName,
			'studentId' => $studentId,
			'programme' => $programme,
			'year' => $year,
			'studentYear' => $studentYear,
			//'studentAttendance' => $studentAttendance,
			'message' => $message
            );
	}


	public function updateExtraCurricularAttendanceAction()
	{
		$form = new ExtraCurricularAttendanceForm($studentCount= 'null');
		$attendanceModel = new ExtraCurricularAttendance();
		$form->bind($attendanceModel);
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 $data = $this->extractFormData();
				 $programme = $this->getRequest()->getPost('programme');
				 $studentName = $this->getRequest()->getPost('student_name');
				 $studentId = $this->getRequest()->getPost('student_id');
				 $year = $this->getRequest()->getPost('year');
				 $event_name = $this->getRequest()->getPost('social_events_id'); 
				 $date_event = $this->getRequest()->getPost('date');
                 try {
					 $this->attendanceService->updateExtraCurricularAttendance($data, $programme, $year, $studentName, $studentId, $event_name, $date_event, $this->organisation_id);
					 $this->auditTrailService->saveAuditTrail("EDIT", "Student Extracurricular Attendance", "ALL", "SUCCESS");
					 $this->flashMessenger()->addMessage('Extracurricular Attendance has been successfully edited');
					 return $this->redirect()->toRoute('editextracurricularattendance');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array('form' => $form);
	}


	public function viewExtraCurricularAttendanceAction()
	{
		$this->loginDetails();
		$form = new ExtraCurricularSearchForm();
	   //preset values
	   	$studentList = array();
	   	$extraCurricularAttendance = array();
		$studentCount = NULL;
		$event_name = NULL;
		$date_event = NULL;
		$studentName = NULL;
		$studentId = NULL;
		$programme = NULL;
		$year = NULL;
		
		$programmeList = $this->attendanceService->listSelectData($tableName = 'programmes' , $columnName = 'programme_name', $this->organisation_id);
		$eventList = $this->attendanceService->listSelectData($tableName = 'social_events' , $columnName = 'event', $this->organisation_id);
		$studentYear = $this->attendanceService->listSelectData($tableName = 'programme_year', $columnName = 'year', NULL);

		$message = NULL;
	    
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$studentName = $this->getRequest()->getPost('student_name');
				$studentId = $this->getRequest()->getPost('student_id');
				$programme = $this->getRequest()->getPost('programme');
				$year = $this->getRequest()->getPost('year');
				$event_name = $this->getRequest()->getPost('social_events_id');
				$date_event = $this->getRequest()->getPost('date');

				$check_attendance = $this->attendanceService->crossCheckExtraCurricularAttendance($programme, $year, $event_name);

				if(empty($check_attendance)){
					$message = 'Failure';
					$this->flashMessenger()->addMessage("You haven't entered the attendance. Please enter attendance first to view");
				}else{
					$studentList = $this->attendanceService->getStudentExtraCurricularAttendance($studentName, $studentId, $programme, $year, $event_name, $this->organisation_id);
					$studentCount = $this->attendanceService->getStudentCount($studentName, $studentId, $programme, $year, $this->organisation_id);
					// /$extraCurricularAttendance = $this->attendanceService->getStudentExtraCurricularAttendance($programme, $year, $event_name, $this->organisation_id);
				}
				$attendanceForm = new ExtraCurricularAttendanceForm($studentCount);
				$attendanceModel = new ExtraCurricularAttendance();
				$attendanceForm->bind($attendanceModel);
				
             }
         }
		
		$attendanceForm = new ExtraCurricularAttendanceForm($studentCount);
		$attendanceModel = new ExtraCurricularAttendance();
		$attendanceForm->bind($attendanceModel);
		
		
		return array(
            'form' => $form,
			'programmeList' => $programmeList,
			'eventList' => $eventList,
			'studentList' => $studentList,
			'attendanceForm' => $attendanceForm,
			'studentCount' => $studentCount,
			'date_event' => $date_event,
			'event_name' => $event_name,
			'studentName' => $studentName,
			'studentId' => $studentId,
			'programme' => $programme,
			'year' => $year,
			'studentYear' => $studentYear,
			//'extraCurricularAttendance' => $extraCurricularAttendance,
			'message' => $message
            );
	}
	
	public function clubsAttendanceAction()
	{
		$this->loginDetails();
		//initialise the value of studentCount
		$studentCount = 0;
		$form = new SearchForm();
		
		//preset values
		$studentList = array();
		$clubId = NULL;
		$attendance_date=NULL;
		
		$clubList = $this->attendanceService->listSelectData($tableName = 'clubs' , $columnName = 'club_name', $this->organisation_id);

		$message = NULL;
	   
	    $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$clubId = $this->getRequest()->getPost('clubs');
				$attendance_date = $this->getRequest()->getPost('date');

				$check_club_members = $this->attendanceService->crossCheckClubMembers($clubId, $this->organisation_id); 
				$student_clubs_members = array();
				foreach($check_club_members as $members){
					$student_clubs_members = $members;
				}

				$check_club_attendance = $this->attendanceService->crossCheckClubAttendance($check_club_members, $attendance_date);

				if(empty($check_club_members)){
					$message = 'Failure';
					$this->flashMessenger()->addMessage("Sorry! There are no club members");
				}
				else if(!empty($check_club_attendance)){
					$message = 'Failure';
					$this->flashMessenger()->addMessage("You have already entered attendance. Please choose other date or other club");
				}
				else{
					$studentList = $this->attendanceService->getStudentClubList($clubId, $this->organisation_id);
					$studentCount = $this->attendanceService->getStudentClubCount($clubId, $this->organisation_id);
				}
             }
         }
		 
		$attendanceForm = new ClubAttendanceForm($studentCount);
		$attendanceModel = new ClubAttendance();
		$attendanceForm->bind($attendanceModel);
		
		return array(
            'form' => $form,
			'attendanceForm' => $attendanceForm,
			'studentList' => $studentList,
			'clubList' => $clubList,
			'clubId' => $clubId,
			'attendance_date' => $attendance_date,
			'studentCount' => $studentCount,
			'message' => $message,
            );
	}
		
	public function addClubsAttendanceAction()
	{
		$form = new ClubAttendanceForm($studentCount= 'null');
		$attendanceModel = new ClubAttendance();
		$form->bind($attendanceModel);

		$message = NULL;
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 $data = $this->extractFormData();
				 $clubsId = $this->getRequest()->getPost('clubs_id');
				 $date = $this->getRequest()->getPost('date');
                 try {
					 $this->attendanceService->saveClubAttendance($data, $clubsId, $date, $this->organisation_id);
					 $this->auditTrailService->saveAuditTrail("INSERT", "Student Club Attendance", "ALL", "SUCCESS");
					 $this->flashMessenger()->addMessage("Club Attendance was successfully entered");
					 return $this->redirect()->toRoute('clubsattendance');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
        	'form' => $form,
        	'message' => $message,
    	);
	}


	public function editClubsAttendanceAction()
	{
		$this->loginDetails();
		//initialise the value of studentCount
		$studentCount = 0;
		$form = new SearchForm();
		
		//preset values
		$studentList = array();
		$clubId = NULL;
		$attendance_date=NULL;
		
		$clubList = $this->attendanceService->listSelectData($tableName = 'clubs' , $columnName = 'club_name', $this->organisation_id);

		$message = NULL;
	   
	    $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$clubId = $this->getRequest()->getPost('clubs');
				$attendance_date = $this->getRequest()->getPost('date');

				$check_club_members = $this->attendanceService->crossCheckClubMembers($clubId, $this->organisation_id); 
				$student_clubs_members = array();
				foreach($check_club_members as $members){
					$student_clubs_members = $members;
				}

				$check_club_attendance = $this->attendanceService->crossCheckClubAttendance($check_club_members, $attendance_date);

				if(empty($check_club_members)){
					$message = 'Failure';
					$this->flashMessenger()->addMessage("Sorry! There are no club members");
				}
				else if(empty($check_club_attendance)){
					$message = 'Failure';
					$this->flashMessenger()->addMessage("Sorry! Club Attendance was not entered on this date. Please enter first.");
				}
				else{
					$studentList = $this->attendanceService->getStudentClubAttendance($clubId, $this->organisation_id, $attendance_date);
					$studentCount = $this->attendanceService->getStudentClubCount($clubId, $this->organisation_id);
				}
             }
         }
		 
		$attendanceForm = new ClubAttendanceForm($studentCount);
		$attendanceModel = new ClubAttendance();
		$attendanceForm->bind($attendanceModel);
		
		return array(
            'form' => $form,
			'attendanceForm' => $attendanceForm,
			'studentList' => $studentList,
			'clubList' => $clubList,
			'clubId' => $clubId,
			'attendance_date' => $attendance_date,
			'studentCount' => $studentCount,
			'message' => $message,
            );
	}


	public function updateClubsAttendanceAction()
	{
		$form = new ClubAttendanceForm($studentCount= 'null');
		$attendanceModel = new ClubAttendance();
		$form->bind($attendanceModel);

		$message = NULL;
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 $data = $this->extractFormData();
				 $clubsId = $this->getRequest()->getPost('clubs_id');
				 $date = $this->getRequest()->getPost('date'); 
                 try {
					 $this->attendanceService->updateClubAttendance($data, $clubsId, $date, $this->organisation_id);
					 $this->auditTrailService->saveAuditTrail("EDIT", "Student Club Attendance", "ALL", "SUCCESS");
					 $this->flashMessenger()->addMessage("Club Attendance was successfully edited");
					 return $this->redirect()->toRoute('editclubsattendance');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
        	'form' => $form,
        	'message' => $message,
    	);
	}
	    
	public function viewClubsAttendanceAction()
    {
    	$this->loginDetails();
		//initialise the value of studentCount
		$studentCount = 0;
		$form = new SearchForm();
		
		//preset values
		$studentList = array();
		$clubId = NULL;
		$attendance_date=NULL;
		
		$clubList = $this->attendanceService->listSelectData($tableName = 'clubs' , $columnName = 'club_name', $this->organisation_id);

		$message = NULL;
	   
	    $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$clubId = $this->getRequest()->getPost('clubs');
				$attendance_date = $this->getRequest()->getPost('date');

				$check_club_members = $this->attendanceService->crossCheckClubMembers($clubId, $this->organisation_id); 
				$student_clubs_members = array();
				foreach($check_club_members as $members){
					$student_clubs_members = $members;
				}

				$check_club_attendance = $this->attendanceService->crossCheckClubAttendance($check_club_members, $attendance_date);

				if(empty($check_club_members)){
					$message = 'Failure';
					$this->flashMessenger()->addMessage("Sorry! There are no club members");
				}
				else if(empty($check_club_attendance)){
					$message = 'Failure';
					$this->flashMessenger()->addMessage("Sorry! Club Attendance was not entered on this date.");
				}
				else{
					$studentList = $this->attendanceService->getStudentClubAttendance($clubId, $this->organisation_id, $attendance_date);
					$studentCount = $this->attendanceService->getStudentClubCount($clubId, $this->organisation_id);
				}
             }
         }
		 
		$attendanceForm = new ClubAttendanceForm($studentCount);
		$attendanceModel = new ClubAttendance();
		$attendanceForm->bind($attendanceModel);
		
		return array(
            'form' => $form,
			'attendanceForm' => $attendanceForm,
			'studentList' => $studentList,
			'clubList' => $clubList,
			'clubId' => $clubId,
			'attendance_date' => $attendance_date,
			'studentCount' => $studentCount,
			'message' => $message,
            );
    }
    		
	//the following function is to extract the data from the form 
	// and return clean data to be inserted into database
	public function extractFormData()
	{
		$studentCount = $this->getRequest()->getPost('student_count');
		$attendanceData = array();
		
		//evaluation data => 'evaluation_'.$i.$j,
		for($i=1; $i<=$studentCount; $i++)
		{
			$attendanceData[$i] = $this->getRequest()->getPost('attendance_'.$i);
		}
		return $attendanceData;
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

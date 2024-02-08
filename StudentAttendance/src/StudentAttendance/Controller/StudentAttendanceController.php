<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace StudentAttendance\Controller;


use StudentAttendance\Service\StudentAttendanceServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Session\Container;
use Zend\Mvc\Controller\AbstractActionController;
use StudentAttendance\Model\CancelledLectures;
use StudentAttendance\Form\StudentAttendanceForm;
use StudentAttendance\Form\StudentAttendanceDeleteForm;
use StudentAttendance\Form\RecordAttendanceDeleteForm;
use StudentAttendance\Form\ExtraClassAttendanceForm;
use StudentAttendance\Form\RecordAttendanceForm;
use StudentAttendance\Form\CancelledLecturesForm;
use StudentAttendance\Form\CancelledLecturesSearchForm;
use StudentAttendance\Form\SearchForm;
use StudentAttendance\Form\AttendanceReportForm;
use StudentAttendance\Form\DeleteAttendanceSearchForm;
use StudentAttendance\Form\TutorDeleteAttendanceSearchForm;
use StudentAttendance\Form\ExtraClassSearchForm;
use StudentAttendance\Form\ConsolidatedAttendanceForm;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class StudentAttendanceController extends AbstractActionController
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
	protected $organisation_id;

	protected $keyphrase = "RUB_IMS";
	
	public function __construct(StudentAttendanceServiceInterface $attendanceService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
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
			}
		}
		
		//get the organisation id
		$organisationID = $this->attendanceService->getOrganisationId($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
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
	
	
    public function studentAttendanceAction()
    {
    	$this->loginDetails();
		
		$form = new SearchForm($this->serviceLocator);
		
		$message = NULL;
		//default values
		$semesterList = NULL;
		$studentList = array();
		$module = NULL;
		$programme = NULL;
		$programme_name = NULL;
		$module_code = NULL;
        $section = NULL;
        $status = NULL;
		$year = NULL;
		$from_date = NULL;
		$to_date = NULL;
		$date_array = array();
		$timetable_dates = array();
		$attendanceForm = NULL;
		$yearList = $this->attendanceService->getMaxProgrammeDuration($this->organisation_id);
        $sectionList = $this->attendanceService->listSelectData('student_section', 'section', NULL);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$module = $this->getRequest()->getPost('module');
				/*
				* Now retrieve programme id given academic modules allocation
				* old kept for reminder
				* $programme = $this->getRequest()->getPost('programme');
				*/
				$programme = $this->attendanceService->getProgrammeId($module);
				$semester = $this->getRequest()->getPost('semester');
                $section = $this->getRequest()->getPost('section');
				$year = $this->getRequest()->getPost('year');
				$from_date = $this->getRequest()->getPost('from_date');
				$to_date_tmp = $this->getRequest()->getPost('to_date');
				$last_attendance_date = $this->attendanceService->getLastAttendanceDate($module, $programme, $section);
				
				//compare the dates
				if($last_attendance_date >= $from_date){
					$from_date_tmp = strtotime("+1 day", strtotime($last_attendance_date));
					$from_date = date("Y-m-d", $from_date_tmp);
				} else if($last_attendance_date != '1970-01-01'){
					$from_date_tmp = strtotime("+1 day", strtotime($last_attendance_date));
					$from_date = date("Y-m-d", $from_date_tmp);
				}
				//$to_date = $this->truncateToDate($from_date, $to_date_tmp);
				$to_date = $to_date_tmp;
				$programme_name = $this->attendanceService->getProgrammeName($programme);
				$module_code = $this->attendanceService->getModuleCode($module);
				$check_timetable = $this->attendanceService->getTimeTable($section, $module);
				if($check_timetable){
					$studentList = $this->attendanceService->getStudentList($programme, $module, $section, $year, $status);
					$date_array = $this->attendanceService->getAttendanceDates($from_date, $to_date, $section, $module, $programme);
					$timetable_dates = $this->attendanceService->getTimetableWithDates($from_date, $to_date, $section, $module, $programme);
					$attendanceForm = new StudentAttendanceForm($studentList, $timetable_dates);
				} else {
					$attendanceForm = NULL;
					$studentList = array();
					$message = "Failure";
					$this->flashMessenger()->addMessage('<br>
                            1. Make sure you have added the timetable for the selected date of the class.<br>
                            2. Check with record officer whether students are assigned to this section');
				}
             }
         }
		
		return new ViewModel(array(
            'form' => $form,
			'programme_id' => $programme,
			'programme_name' => $programme_name,
			'module_code' => $module_code,
			'academic_modules_id' => $module,
			'attendanceForm' => $attendanceForm,
			'studentList' => $studentList,
			'yearList' => $yearList,
			'sectionList' => $sectionList,
			'section' => $section,
			'year' => $year,
			'from_date' => $from_date,
			'to_date' => $to_date,
			'date_array' => $date_array,
			'timetable_dates' => $timetable_dates,
			'message' => $message,
			'keyphrase' => $this->keyphrase,
			'employee_details_id' => $this->employee_details_id,
            ));
    }

    public function recordMissingStudentAttendanceAction()
    {
    	$this->loginDetails();
		
		$form = new SearchForm($this->serviceLocator);
		
		$message = NULL;
		//default values
		$semesterList = NULL;
		$studentList = array();
		$module = NULL;
		$programme = NULL;
		$programme_name = NULL;
		$module_code = NULL;
        $section = NULL;
        $status = NULL;
		$year = NULL;
		$from_date = NULL;
		$to_date = NULL;
		$date_array = array();
		$timetable_dates = array();
		$attendanceForm = NULL;
		$yearList = $this->attendanceService->getMaxProgrammeDuration($this->organisation_id);
        $sectionList = $this->attendanceService->listSelectData('student_section', 'section', NULL);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$module = $this->getRequest()->getPost('module');
				/*
				* Now retrieve programme id given academic modules allocation
				* old kept for reminder
				* $programme = $this->getRequest()->getPost('programme');
				*/
				$programme = $this->attendanceService->getProgrammeId($module);
				$semester = $this->getRequest()->getPost('semester');
                $section = $this->getRequest()->getPost('section');
				$year = $this->getRequest()->getPost('year');
				$from_date = $this->getRequest()->getPost('from_date');
				$to_date_tmp = $this->getRequest()->getPost('from_date');
				$last_attendance_date = $this->attendanceService->getLastAttendanceDate($module, $programme, $section);
				$attendanceDate = $this->attendanceService->checkAttendanceDateRange($section, $module, $from_date);

				if(!$attendanceDate) {
					//compare the dates
					$to_date = $to_date_tmp;
					$programme_name = $this->attendanceService->getProgrammeName($programme);
					$module_code = $this->attendanceService->getModuleCode($module);
					$check_timetable = $this->attendanceService->getTimeTable($section, $module);
					if($check_timetable){
						$studentList = $this->attendanceService->getStudentList($programme, $module, $section, $year, $status);
						$date_array = $this->attendanceService->getAttendanceDates($from_date, $to_date, $section, $module, $programme);
						$timetable_dates = $this->attendanceService->getTimetableWithDates($from_date, $to_date, $section, $module, $programme);
						$attendanceForm = new StudentAttendanceForm($studentList, $timetable_dates);
					} 
					else {
						$attendanceForm = NULL;
						$studentList = array();
						$message = "Failure";
						$this->flashMessenger()->addMessage('Please Add Timetable first');
					}
				} else {					
					$attendanceForm = NULL;
					$studentList = array();
					$message = "Failure";
					$this->flashMessenger()->addMessage('Attendance For Selected Date has been entered');
				}
             }
         }
		
		return new ViewModel(array(
            'form' => $form,
			'programme_id' => $programme,
			'programme_name' => $programme_name,
			'module_code' => $module_code,
			'academic_modules_id' => $module,
			'attendanceForm' => $attendanceForm,
			'studentList' => $studentList,
			'yearList' => $yearList,
			'sectionList' => $sectionList,
			'section' => $section,
			'year' => $year,
			'from_date' => $from_date,
			'to_date' => $to_date,
			'date_array' => $date_array,
			'timetable_dates' => $timetable_dates,
			'message' => $message,
			'keyphrase' => $this->keyphrase,
			'employee_details_id' => $this->employee_details_id,
            ));
    }
	
	public function recordStudentAttendanceAction()
	{
		$this->loginDetails();
		$form = new RecordAttendanceForm();
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$programme = $this->getRequest()->getPost('programme_id');
				$module = $this->getRequest()->getPost('academic_modules_id');
				$semester = $this->getRequest()->getPost('semester');
				$year = $this->getRequest()->getPost('year');
				$from_date = $this->getRequest()->getPost('from_date');
				$to_date = $this->getRequest()->getPost('to_date');
				$section = $this->getRequest()->getPost('section');
				$studentList = $this->attendanceService->getStudentList($programme, $module, $section, $year, $status);
				$timetable_dates = $this->attendanceService->getTimetableWithDates($from_date, $to_date, $section, $module, $programme);
				$attendance_data = $this->extractFormData($studentList, $timetable_dates);
				try {
					 $this->attendanceService->saveAttendanceRecord($studentList, $timetable_dates, $attendance_data, $module, $programme, $section, $this->employee_details_id);
					 $this->notificationService->saveNotification('Record Student Attendance', 'ALL', 'NULL', 'Student Attendance');
                     $this->auditTrailService->saveAuditTrail("INSERT", "Student Attendance Dates", "ALL", "SUCCESS");
                     $this->auditTrailService->saveAuditTrail("INSERT", "Student Absentee Record", "ALL", "SUCCESS");
					 $this->flashMessenger()->addMessage('Attendance Successfully Added');
					 //return $this->redirect()->toRoute('viewstudentattendance');
					 return $this->redirect()->toRoute('studentattendance');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
			 else{
				$this->flashMessenger()->addMessage('Session has expired. Submit form again');
				return $this->redirect()->toRoute('studentattendance'); 
			 }
         }
	}
	
	public function viewStudentAttendanceAction()
    {
    	$this->loginDetails();
		
		$form = new SearchForm($this->serviceLocator);
		
		//default values
		$semesterList = NULL;
		$studentList = array();
		$attendanceRecordDate = array();
		$absentData = array();
		$module = NULL;
		$programme = NULL;
		$year = NULL;
		$from_date = NULL;
		$to_date = NULL;
		$attendanceForm = NULL;
		$status = NULL;
		$yearList = $this->attendanceService->getMaxProgrammeDuration($this->organisation_id);
		$sectionList = $this->attendanceService->listSelectData('student_section', 'section', NULL);
		$message = NULL;
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$module = $this->getRequest()->getPost('module');
				/*
				* Now retrieve programme id given academic modules allocation
				* old kept for reminder
				* $programme = $this->getRequest()->getPost('programme');
				*/
				$programme = $this->attendanceService->getProgrammeId($module);
				$semester = $this->getRequest()->getPost('semester');
				$section = $this->getRequest()->getPost('section');
				$year = $this->getRequest()->getPost('year');
				$from_date = $this->getRequest()->getPost('from_date');
				$to_date_tmp = $this->getRequest()->getPost('to_date');
				//$to_date = $this->truncateToDate($from_date, $to_date_tmp);
				$to_date = $to_date_tmp;
				$section = $this->getRequest()->getPost('section');
				$last_attendance_date = $this->attendanceService->getLastAttendanceDate($module, $programme, $section);
				//compare the dates
				if($last_attendance_date <= $from_date){
					$message = "Failure";
					$this->flashMessenger()->addMessage('Attendance for Selected Dates have not been entered');
				} else if($last_attendance_date != '1970-01-01'){
					$studentList = $this->attendanceService->getStudentList($programme, $module, $section, $year, $status);
					if($to_date >= $last_attendance_date){
						$to_date = $last_attendance_date;
					}
					$timetable_dates = array();
					$attendanceRecordDate = $this->attendanceService->getAttendanceRecordDates($from_date, $to_date, $module, $programme, $section);
					$absentData = $this->attendanceService->getAbsenteeList($from_date, $to_date, $module, $programme);
					$attendanceForm = new StudentAttendanceForm($studentList, $timetable_dates);
				}
             }
         }
		
		return new ViewModel(array(
            'form' => $form,
			'programme_id' => $programme,
			'academic_modules_id' => $module,
			'attendanceForm' => $attendanceForm,
			'attendanceRecordDate' => $attendanceRecordDate,
			'absentData' => $absentData,
			'studentList' => $studentList,
			'yearList' => $yearList,
			'sectionList' => $sectionList,
			'year' => $year,
			'from_date' => $from_date,
			'to_date' => $to_date,
			'employee_details_id' => $this->employee_details_id,
			'keyphrase' => $this->keyphrase,
			'message' => $message
            ));
    }
	
	public function editStudentAttendanceAction()
    {
    	$this->loginDetails();
		
		$form = new SearchForm($this->serviceLocator);
		
		$message = NULL;
		//default values
		$semesterList = NULL;
		$studentList = array();
		$module = NULL;
		$programme = NULL;
		$programme_name = NULL;
		$module_code = NULL;
        $section = NULL;
		$year = NULL;
		$from_date = NULL;
		$to_date = NULL;
		$status = NULL;
		$date_array = array();
		$timetable_dates = array();
		$attendanceForm = NULL;
		$attendanceRecordDate = array();
		$absentData = array();
		$yearList = $this->attendanceService->getMaxProgrammeDuration($this->organisation_id);
        $sectionList = $this->attendanceService->listSelectData('student_section', 'section', NULL);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$module = $this->getRequest()->getPost('module');
				/*
				* Now retrieve programme id given academic modules allocation
				* old kept for reminder
				* $programme = $this->getRequest()->getPost('programme');
				*/
				$programme = $this->attendanceService->getProgrammeId($module);
				$semester = $this->getRequest()->getPost('semester');
                $section = $this->getRequest()->getPost('section');
				$year = $this->getRequest()->getPost('year');
				$from_date = $this->getRequest()->getPost('from_date');
				$to_date = $this->getRequest()->getPost('from_date');
				$class_type = $this->getRequest()->getPost('class_type');
				//check whether attendance has been entered
				$last_attendance_date = $this->attendanceService->getLastAttendanceDate($module, $programme, $section);
				$programme_name = $this->attendanceService->getProgrammeName($programme);
				$module_code = $this->attendanceService->getModuleCode($module);
				$check_timetable = $this->attendanceService->getTimeTable($section, $module);
				$attendanceDate = $this->attendanceService->checkAttendanceDate($section, $module, $from_date);
				if(!$attendanceDate) {
					$attendanceForm = NULL;
					$studentList = array();
					$message = "Failure";
					$this->flashMessenger()->addMessage('Attendance For Selected Date has not been entered');
				} else if($check_timetable){
					$attendanceRecordDate = $this->attendanceService->getAttendanceRecordDates($from_date, $to_date, $module, $programme, $section);
					$absentData = $this->attendanceService->getAbsenteeList($from_date, $to_date, $module, $programme);
					$studentList = $this->attendanceService->getStudentList($programme, $module, $section, $year, $status);
					$date_array = $this->attendanceService->getAttendanceDates($from_date, $to_date, $section, $module, $programme);
					if($class_type == 'Scheduled'){
						$timetable_dates = $this->attendanceService->getTimetableWithDates($from_date, $to_date, $section, $module, $programme);
					} else{
						$timetable_dates = $this->attendanceService->getExtraClassDates($from_date, $section, $module, $programme);
					}
					
					$attendanceForm = new StudentAttendanceForm($studentList, $timetable_dates);
				} else {
					$attendanceForm = NULL;
					$studentList = array();
					$message = "Failure";
					$this->flashMessenger()->addMessage('Attendance For Selected Date has not been entered');
				}
             }
         }
		
		return new ViewModel(array(
            'form' => $form,
			'programme_id' => $programme,
			'programme_name' => $programme_name,
			'module_code' => $module_code,
			'academic_modules_id' => $module,
			'attendanceForm' => $attendanceForm,
			'studentList' => $studentList,
			'yearList' => $yearList,
			'sectionList' => $sectionList,
			'absentData' => $absentData,
			'attendanceRecordDate' => $attendanceRecordDate,
			'section' => $section,
			'year' => $year,
			'from_date' => $from_date,
			'to_date' => $to_date,
			'date_array' => $date_array,
			'timetable_dates' => $timetable_dates,
			'message' => $message,
			'keyphrase' => $this->keyphrase,
			'employee_details_id' => $this->employee_details_id,
            ));
    }
	
	public function recordEditedStudentAttendanceAction()
	{
		$this->loginDetails();
		$form = new RecordAttendanceForm();
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$programme = $this->getRequest()->getPost('programme_id');
				$module = $this->getRequest()->getPost('academic_modules_id');
				$semester = $this->getRequest()->getPost('semester');
				$year = $this->getRequest()->getPost('year');
				$from_date = $this->getRequest()->getPost('from_date');
				$to_date = $this->getRequest()->getPost('from_date');
				$section = $this->getRequest()->getPost('section');
				$studentList = $this->attendanceService->getStudentList($programme, $module, $section, $year, $status);
				$timetable_dates = $this->attendanceService->getTimetableWithDates($from_date, $to_date, $section, $module, $programme);
				$attendance_data = $this->extractFormData($studentList, $timetable_dates);
				try {
					 $this->attendanceService->saveEditedAttendanceRecord($studentList, $timetable_dates, $attendance_data, $module, $programme, $section, $this->employee_details_id);
					 $this->notificationService->saveNotification('Record Edited Student Attendance', 'ALL', 'NULL', 'Student Attendance');
                     $this->auditTrailService->saveAuditTrail("EDIT", "Student Attendance Dates", "ALL", "SUCCESS");
                     $this->auditTrailService->saveAuditTrail("EBIT", "Student Absentee Record", "ALL", "SUCCESS");
					 $this->flashMessenger()->addMessage('Attendance Successfully Edited');
					 return $this->redirect()->toRoute('editstudentattendance');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
	}


	public function deleteStudentAttendanceAction()
	{
		$this->loginDetails();
		
		$form = new DeleteAttendanceSearchForm($this->serviceLocator);
		
		$message = NULL;
		//default values
		$semesterList = NULL;
		$module = NULL;
		$academic_modules_allocation_id = NULL;
		$programme = NULL;
		$programme_name = NULL;
		$module_code = NULL;
        $section = NULL;
		$from_date = NULL;
		$to_date = NULL;
		$date_array = array();
		$timetable_dates = array();
		$attendanceForm = NULL;
		$attendanceRecordDate = array();
        $sectionList = $this->attendanceService->listSelectData('student_section', 'section', NULL);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) { 

				$programme = $this->getRequest()->getPost('programme');

				$module = $this->getRequest()->getPost('module');
				//var_dump($module); die();
				$semester = $this->getRequest()->getPost('semester');

                $section = $this->getRequest()->getPost('section');

				$from_date = $this->getRequest()->getPost('from_date');

				$to_date = $this->getRequest()->getPost('from_date'); 
				$academic_modules_allocation_id = $this->attendanceService->getAcademicModulesAllocationId($programme, $module, $this->organisation_id);
				//$academic_modules_allocation_id = $this->getRequest()->getPost('module');
				//var_dump($academic_modules_allocation_id); die();

				//check whether attendance has been entered
				$attendance_date = $from_date; 
				$last_attendance_date = $this->attendanceService->getLastAttendanceDate($academic_modules_allocation_id, $programme, $section);

				$programme_name = $this->attendanceService->getProgrammeName($programme);

				$module_code = $this->attendanceService->getModuleCode($academic_modules_allocation_id);

				$check_timetable = $this->attendanceService->getTimeTable($section, $academic_modules_allocation_id);
				if($check_timetable){
					$attendanceRecordDate = $this->attendanceService->getAttendanceRecordDates($from_date, $to_date, $academic_modules_allocation_id, $programme, $section);
					$date_array = $this->attendanceService->getAttendanceDates($from_date, $to_date, $section, $academic_modules_allocation_id, $programme);
					$timetable_dates = $this->attendanceService->getTimetableWithDates($from_date, $to_date, $section, $academic_modules_allocation_id, $programme);
					$attendanceForm = new StudentAttendanceDeleteForm($timetable_dates);

				} else {
					$attendanceForm = NULL;
					$message = "Failure";
					$this->flashMessenger()->addMessage('Attendance For Selected Date has not been entered');
				}
             }
         }
		
		return new ViewModel(array(
            'form' => $form,
			'programme_id' => $programme,
			'programme_name' => $programme_name,
			'module_code' => $module_code,
			//'academic_modules_id' => $module,
			'academic_modules_id' => $academic_modules_allocation_id,
			'attendanceForm' => $attendanceForm,
			'sectionList' => $sectionList,
			'attendanceRecordDate' => $attendanceRecordDate,
			'section' => $section,
			'from_date' => $from_date,
			'to_date' => $to_date,
			'date_array' => $date_array,
			//'attendance_date' => $attendance_date,
			'timetable_dates' => $timetable_dates,
			'message' => $message,
			'keyphrase' => $this->keyphrase,
			'employee_details_id' => $this->employee_details_id,
            ));
	}


	public function tutorDeleteStudentAttendanceAction()
	{
		$this->loginDetails();
		
		$form = new TutorDeleteAttendanceSearchForm($this->serviceLocator);
		//$form = new SearchForm($this->serviceLocator);
		
		$message = NULL;
		//default values
		$semesterList = NULL;
		$module = NULL;
		$programme = NULL;
		$programme_name = NULL;
		$module_code = NULL;
        $section = NULL;
		$from_date = NULL;
		$to_date = NULL;
		$date_array = array();
		$timetable_dates = array();
		$attendanceForm = NULL;
		$attendanceRecordDate = array();
        $sectionList = $this->attendanceService->listSelectData('student_section', 'section', NULL);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) { 

				//$programme = $this->getRequest()->getPost('programme');
				
				$module = $this->getRequest()->getPost('module');
				$programme = $this->attendanceService->getProgrammeId($module);
				//var_dump($module); die();
				$semester = $this->getRequest()->getPost('semester');
                $section = $this->getRequest()->getPost('section');
				$from_date = $this->getRequest()->getPost('from_date');
				$to_date = $this->getRequest()->getPost('from_date'); 
				$academic_modules_allocation_id = $this->getRequest()->getPost('module');

				//$academic_modules_allocation_id = $this->attendanceService->getAcademicModulesAllocationId($programme, $module, $this->organisation_id);
				//check whether attendance has been entered
				$attendance_date = $from_date; 

				$last_attendance_date = $this->attendanceService->getLastAttendanceDate($academic_modules_allocation_id, $programme, $section);
				$programme_name = $this->attendanceService->getProgrammeName($programme);
				$module_code = $this->attendanceService->getModuleCode($academic_modules_allocation_id);
				$check_timetable = $this->attendanceService->getTimeTable($section, $academic_modules_allocation_id);

				if($check_timetable){
					$attendanceRecordDate = $this->attendanceService->getAttendanceRecordDates($from_date, $to_date, $academic_modules_allocation_id, $programme, $section);
					$date_array = $this->attendanceService->getAttendanceDates($from_date, $to_date, $section, $academic_modules_allocation_id, $programme);
					$timetable_dates = $this->attendanceService->getTimetableWithDates($from_date, $to_date, $section, $academic_modules_allocation_id, $programme);
					$attendanceForm = new StudentAttendanceDeleteForm($timetable_dates);
				} else {
					$attendanceForm = NULL;
					$message = "Failure";
					$this->flashMessenger()->addMessage('Attendance For Selected Date has not been entered');
				}
             } else {
             	echo "Form Not Valid"; die();
             }
         }
		
		return new ViewModel(array(
            'form' => $form,
			'programme_id' => $programme,
			'programme_name' => $programme_name,
			'module_code' => $module_code,
			'academic_modules_id' => $module,
			'attendanceForm' => $attendanceForm,
			'sectionList' => $sectionList,
			'attendanceRecordDate' => $attendanceRecordDate,
			'section' => $section,
			'from_date' => $from_date,
			'to_date' => $to_date,
			'date_array' => $date_array,
			//'attendance_date' => $attendance_date,
			'timetable_dates' => $timetable_dates,
			'message' => $message,
			'keyphrase' => $this->keyphrase,
			'employee_details_id' => $this->employee_details_id,
            ));
	}


	public function updateDeletedStudentAttendanceAction()
	{
		$this->loginDetails();
		$form = new RecordAttendanceDeleteForm();
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {

				$programme = $this->getRequest()->getPost('programme_id');


				//$module = $this->getRequest()->getPost('academic_modules_id');
				$semester = $this->getRequest()->getPost('semester');

				$from_date = $this->getRequest()->getPost('from_date');

				$to_date = $this->getRequest()->getPost('from_date');

				$section = $this->getRequest()->getPost('section');

				$academic_modules_allocation_id = $this->getRequest()->getPost('academic_modules_id');
				//var_dump($academic_modules_allocation_id); die();
				//$academic_modules_allocation_id = $this->attendanceService->getAcademicModulesAllocationId($programme, $module, $this->organisation_id);
				//$studentList = $this->attendanceService->getStudentList($programme, $module, $section, $year, $status);
				$timetable_dates = $this->attendanceService->getTimetableWithDates($from_date, $to_date, $section, $academic_modules_allocation_id, $programme);

				$attendance_data = $this->extractFormData1($timetable_dates);
				try {
					 $this->attendanceService->updateDeletedStudentAttendance($from_date, $to_date, $attendance_data, $academic_modules_allocation_id, $programme, $section);
					 $this->notificationService->saveNotification('Record Deleted Student Attendance', 'ALL', 'NULL', 'Student Attendance');
                     $this->auditTrailService->saveAuditTrail("DELETE", "Student Attendance Dates", "ALL", "SUCCESS");
                     $this->auditTrailService->saveAuditTrail("DELETE", "Student Absentee Record", "ALL", "SUCCESS");
                     $this->auditTrailService->saveAuditTrail("INSERT", "Cancelled Lectures", "ALL", "SUCCESS");
					 //$this->flashMessenger()->addMessage('Attendance Successfully Deleted and added to cancelled lectures');
					 $this->flashMessenger()->addMessage('Attendance Successfully Deleted.');
					 return $this->redirect()->toRoute('tutordeletestudentattendance');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
	}
	
	
	public function extraClassAttendanceAction()
	{
		$this->loginDetails();
		
		$form = new ExtraClassSearchForm($this->serviceLocator);
		
		$semesterList = NULL;
		$studentList = array();
		$attendanceRecordDate = array();
		$absentData = array();
		$module = NULL;
		$programme = NULL;
		$year = NULL;
		$from_date = NULL;
		$to_date = NULL;
		$from_time = NULL;
		$section = NULL;
		$attendanceForm = NULL;
		$status = NULL;
		$yearList = $this->attendanceService->getMaxProgrammeDuration($this->organisation_id);
		$sectionList = $this->attendanceService->listSelectData('student_section', 'section', NULL);

		$message = NULL;
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$module = $this->getRequest()->getPost('module');
				/*
				* Now retrieve programme id given academic modules allocation
				* old kept for reminder
				* $programme = $this->getRequest()->getPost('programme');
				*/
				$programme = $this->attendanceService->getProgrammeId($module);
				$semester = $this->getRequest()->getPost('semester');
				//$year = $this->getRequest()->getPost('year');
				$from_date = $this->getRequest()->getPost('from_date');
				$to_date = $this->getRequest()->getPost('from_date');
				$from_time = $this->getRequest()->getPost('from_time'); 
				$section = $this->getRequest()->getPost('section');
				$date_array = $this->attendanceService->getAttendanceDates($from_date, $to_date, $section, $module, $programme);
				$studentList = $this->attendanceService->getStudentList($programme, $module, $section, NULL, $status);
				$attendanceForm = new ExtraClassAttendanceForm($studentList);
             }
         }
		 else {
			 $studentList = array();
			 $from_date = NULL;
			 $to_date = NULL;
			 $from_time = NULL;
			 $date_array = array();
			 $attendanceForm = NULL;
		 }
		
		return new ViewModel(array(
            'form' => $form,
			'attendanceForm' => $attendanceForm,
			'programme_id' => $programme,
			'academic_modules_id' => $module,
			'studentList' => $studentList,
			'yearList' => $yearList,
			'from_date' => $from_date,
			'to_date' => $to_date,
			'from_time' => $from_time,
			'section' => $section,
			'date_array' => $date_array,
			'sectionList' => $sectionList,
			'message' => $message,
            ));
	}
	
	public function recordExtraClassAttendanceAction()
	{
		$this->loginDetails();
		$form = new RecordAttendanceForm();
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$programme = $this->getRequest()->getPost('programme_id');
				$module = $this->getRequest()->getPost('academic_modules_id');
				$semester = $this->getRequest()->getPost('semester');
				//$year = $this->getRequest()->getPost('year');
				$from_date = $this->getRequest()->getPost('from_date');
				$to_date = $this->getRequest()->getPost('to_date');
				$from_time = $this->getRequest()->getPost('from_time');
				$section = $this->getRequest()->getPost('section');
				$studentList = $this->attendanceService->getStudentList($programme, $module, $section, NULL, $status);
				$attendance_data = $this->extractFormData($studentList, $timetable_dates=NULL);
				try {
					 //sending to_date as from_date and to_date are the same
					 $this->attendanceService->saveExtraClassAttendance($studentList, $from_date, $from_time, $attendance_data, $module, $programme, $section, $this->employee_details_id);
					 $this->auditTrailService->saveAuditTrail("INSERT", "Student Attendance Dates", "ALL", "SUCCESS");
					 $this->auditTrailService->saveAuditTrail("INSERT", "Student Absentee Record", "ALL", "SUCCESS");
					 $this->flashMessenger()->addMessage('You have successfully added extra-class attendance');
					 return $this->redirect()->toRoute('extraclassattendance');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
	}
	
	public function cancelledLecturesAction()
	{
		$this->loginDetails();
		
		$form = new CancelledLecturesSearchForm($this->serviceLocator);
				
		//default values
		$message = NULL;
		$module = NULL;
		$programme = NULL;
		$programme_name = NULL;
		$module_code = NULL;
        $section = NULL;
		$from_date = NULL;
		$to_date = NULL;
		$date_array = array();
		$timetable_dates = array();
		$lectureForm = NULL;
        $sectionList = $this->attendanceService->listSelectData('student_section', 'section', NULL);
		$calendarDates = $this->attendanceService->listAll('cancelled_lectures');
				
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$module = $this->getRequest()->getPost('module');
				/*
				* Now retrieve programme id given academic modules allocation
				* old kept for reminder
				* $programme = $this->getRequest()->getPost('programme');
				*/
				$programme = $this->attendanceService->getProgrammeId($module);
				$semester = $this->getRequest()->getPost('semester');
                $section = $this->getRequest()->getPost('section');
				$from_date = $this->getRequest()->getPost('from_date');
				$to_date = $this->getRequest()->getPost('to_date');
				$last_attendance_date = $this->attendanceService->getLastAttendanceDate($module, $programme, $section);
				
				if($last_attendance_date <= $from_date){
					$date_array = $this->attendanceService->getAttendanceDates($from_date, $to_date, $section, $module, $programme);
					$timetable_dates = $this->attendanceService->getTimetableWithDates($from_date, $to_date, $section, $module, $programme);
					$lectureForm = new CancelledLecturesForm($timetable_dates);
				} else {
					$attendanceForm = NULL;
					$studentList = array();
					$message = "Failure";
					$this->flashMessenger()->addMessage('From Date Exceeds Last Attendance Date OR Attendance have already been entered. Select Dates again');
				}
             }
         }
		
		return new ViewModel(array(
			'form' => $form,
			'programme_id' => $programme,
			'academic_modules_id' => $module,
			'section' => $section,
			'from_date' => $from_date,
			'to_date' => $to_date,
			'date_array' => $date_array,
			'timetable_dates' => $timetable_dates,
			'message' => $message,
			'lectureForm' => $lectureForm,
			'calendarDates' => $calendarDates,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
			));
	}
	
	public function recordCancelledLecturesAction()
	{
		$this->loginDetails();
		$timetable_dates = array();
		$form = new CancelledLecturesForm($timetable_dates);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$programme = $this->getRequest()->getPost('programme_id');
				$module = $this->getRequest()->getPost('academic_modules_id');
				$semester = $this->getRequest()->getPost('semester');
				$from_date = $this->getRequest()->getPost('from_date');
				$to_date = $this->getRequest()->getPost('to_date');
				$section = $this->getRequest()->getPost('section');
				$timetable_dates = $this->attendanceService->getTimetableWithDates($from_date, $to_date, $section, $module, $programme);
				$lectures_data = $this->extractFormData(NULL, $timetable_dates);
				$lectures_reasons = $this->extractFormData('Remarks', $timetable_dates);
				try {
					 $this->attendanceService->saveCancelledLectures($timetable_dates, $lectures_data, $section, $module, $programme, $lectures_reasons);
					 $this->notificationService->saveNotification('Lectures Cancelled', 'ALL', 'NULL', 'Lecture Cancelled');
                     $this->auditTrailService->saveAuditTrail("INSERT", "Cancelled Lectures", "ALL", "SUCCESS");
					 $this->flashMessenger()->addMessage('Cancelled Lectures Dates successfully recorded');
					 return $this->redirect()->toRoute('cancelledlectures');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
	}
	
	public function editCancelledLecturesAction()
	{
		$this->loginDetails();
		//get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
			
			$form = new CancelledLecturesForm($this->serviceLocator);
			
			$lectureDetails = $this->attendanceService->getCancelledLectureDetail($id);
			$calendarDates = $this->attendanceService->listAll('cancelled_lectures');
					
			$request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) {
					 $data['id'] = $programme = $this->getRequest()->getPost('id');
					 $data['programme'] = $programme = $this->getRequest()->getPost('programme');
					 $data['module'] = $programme = $this->getRequest()->getPost('module');
					 $data['lecture_date'] = $programme = $this->getRequest()->getPost('lecture_date');
					 $data['reasons'] = $programme = $this->getRequest()->getPost('reasons');
	                 try {				 
						 $this->attendanceService->saveCancelledLectures($data);
						 $this->auditTrailService->saveAuditTrail("EDIT", "Cancelled Lectures", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('cancelledlectures');
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
				'lectureDetails' => $lectureDetails
				));
        }else{
        	return $this->redirect()->toRoute('cancelledlectures');
        }
	}
	
	public function viewStudentAttendanceRecordAction()
    {
    	$this->loginDetails();
		
		$form = new SearchForm($this->serviceLocator);
		
		//default values
		$studentList = array();
		$totalContactHours = NULL;
		$totalLecturesDelievered = NULL;
		$totalLectureHours = NULL;
		$lectureLength = NULL;
		$from_date = date('Y-m-d');
		$to_date = date('Y-m-d');
		$module = NULL;
		/*
		* Get a month list to view attendance data. However, the field name of the form used is "year"
		* Field name "month" does not exist
		*/
		$monthList = $this->attendanceService->getMonthList($this->organisation_id);

		$sectionList = $this->attendanceService->listSelectData('student_section', 'section', NULL);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$module = $this->getRequest()->getPost('module');
				/*
				* Now retrieve programme id given academic modules allocation
				* old kept for reminder
				* $programme = $this->getRequest()->getPost('programme');
				*/
				$programme = $this->attendanceService->getProgrammeId($module);
				$semester = $this->getRequest()->getPost('semester');
				$section = $this->getRequest()->getPost('section');
				//var_dump($semester); die();
				$totalContactHours = $this->attendanceService->getModuleContactHours($module);

				$totalLecturesDelievered = $this->attendanceService->getTotalLecturesDelivered($module);

				$totalLectureHours = $this->attendanceService->getTotalLectureHours($module, $this->organisation_id);

				$lectureLength = $this->attendanceService->getLectureLength($this->organisation_id);
				$from_date = $this->getRequest()->getPost('from_date');

				$to_date = $this->getRequest()->getPost('to_date');

				$studentList = $this->attendanceService->getStudentAttendanceRecord($programme, $module, $section, $from_date, $to_date);
             }
         }
		
		return new ViewModel(array(
            'form' => $form,
			'academic_modules_allocation_id' => $module,
			'studentList' => $studentList,
			'monthList' => $monthList,
			'sectionList' => $sectionList,
			'from_date' => $from_date,
			'to_date' => $to_date,
			'totalContactHours' => $totalContactHours,
			'totalLecturesDelievered' => $totalLecturesDelievered,
			'totalLectureHours' => $totalLectureHours,
			'lectureLength' => $lectureLength,
			'employee_details_id' => $this->employee_details_id,
			'keyphrase' => $this->keyphrase,
            ));
	}
	
	public function viewConsolidatedStudentAttendanceRecordAction()
    {
    	$this->loginDetails();
		
		$form = new AttendanceReportForm($this->serviceLocator);
		
		//default values
		$studentList = array();
		$totalContactHours = NULL;
		$totalLecturesDelievered = NULL;
		$totalLectureHours = NULL;
		$from_date = NULL;//date('Y-m-d');
		$to_date = NULL;//date('Y-m-d');
		$module = NULL;
		$module_tutor = NULL;
		$lectureLength = $this->attendanceService->getLectureLength($this->organisation_id);
		/*
		* Get a month list to view attendance data. However, the field name of the form used is "year"
		* Field name "month" does not exist
		*/
		$monthList = $this->attendanceService->getMonthList($this->organisation_id);

		
		$sectionList = $this->attendanceService->listSelectData('student_section', 'section', NULL);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$programme = $this->getRequest()->getPost('programme');
				$module = $this->getRequest()->getPost('module');
				//$semester = $this->getRequest()->getPost('semester');
				$section = $this->getRequest()->getPost('section');
				
				//var_dump($semester); die();
				$module_tutor = $this->attendanceService->getModuleTutor($module, $section);

				$totalContactHours = $this->attendanceService->getModuleContactHours($module);

				$totalLecturesDelievered = $this->attendanceService->getTotalLecturesDelivered($module);

				$totalLectureHours = $this->attendanceService->getTotalLectureHours($module, $this->organisation_id);

				$from_date = $this->getRequest()->getPost('from_date');

				$to_date = $this->getRequest()->getPost('to_date');
				$studentList = $this->attendanceService->getStudentAttendanceRecord($programme, $module, $section, $from_date, $to_date);
				//var_dump($studentList); die();
             }
         }
		
		return new ViewModel(array(
            'form' => $form,
			'academic_modules_allocation_id' => $module,
			'studentList' => $studentList,
			'monthList' => $monthList,
			'sectionList' => $sectionList,
			'from_date' => $from_date,
			'to_date' => $to_date,
			'totalContactHours' => $totalContactHours,
			'totalLecturesDelievered' => $totalLecturesDelievered,
			'totalLectureHours' => $totalLectureHours,
			'lectureLength' => $lectureLength,
			'module_tutor' => $module_tutor,
			'employee_details_id' => $this->employee_details_id,
			'keyphrase' => $this->keyphrase,
            ));
	}
	
	public function individualStudentAttendanceRecordAction()
	{
		$this->loginDetails();
		$attendanceRecord = array();
		//get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $decrytped_id_from_route = $this->my_decrypt($id_from_route, $this->keyphrase);
		$route_data = explode("_", $decrytped_id_from_route);
		
		if($route_data[0] == 'ERROR'){
			 $this->flashMessenger()->addMessage('Something went wrong!');
			 return $this->redirect()->toRoute('viewattendancerecord');
		}
		$student_id = $route_data[0];
		$crosscheck_student_id = $this->attendanceService->crosscheckStudentId($student_id);
		
		if($crosscheck_student_id){
			$academic_modules_allocation_id = $route_data[1];
			$attendanceRecord = $this->attendanceService->getIndividualStudentAttendanceRecord($student_id, $academic_modules_allocation_id);
		} else{
			 $this->flashMessenger()->addMessage('Something went wrong!');
			 return $this->redirect()->toRoute('viewattendancerecord');
		}
		
		return new ViewModel(array(
			'attendanceRecord' => $attendanceRecord
            ));
	}
	
	public function generateConsolidatedAttendanceAction()
	{
		$this->loginDetails();
		$form = new ConsolidatedAttendanceForm($this->serviceLocator, $options=array(), $this->organisation_id);
		$studentList = array();
		$programmeList = array();
		$yearList = array();
		//$programmeList = $this->attendanceService->listSelectData('programmes','programme_name',$this->organisation_id);
		//$yearList = $this->attendanceService->createYearList($this->organisation_id);
		
		$request = $this->getRequest();
		if($request->isPost()){
			$form->setData($request->getPost());
			if($form->isValid()){
				$data['programmes_id'] = $this->getRequest()->getPost('programmes_id');
				$data['year'] = $this->getRequest()->getPost('year');
				$studentList = $this->attendanceService->generateConsolidatedAttendance($data);
			} 
		}
		
		return new ViewModel(array(
			'form' => $form,
			'programmeList' => $programmeList,
			'keyphrase' => $this->keyphrase,
			'yearList' => $yearList
		));
	}
	
	//truncate the dates for attendance
	public function truncateToDate($from_date, $to_date)
	{
		 //get the "from" month and "to" month
		 //if months are different, then truncate the "to" month
		 $from_month = substr($from_date,5,2);
		 $to_month = substr($to_date,5,2);
		 if($from_month != $to_month){
			 //get number of dats in the selected month
			$days_in_month = cal_days_in_month(CAL_GREGORIAN,substr($from_date,5,2),date('Y'));
			$to_date = date('Y').'-'.substr($from_date,5,2).'-'.$days_in_month;
		 }
		 
		 return $to_date;
	}
	
	//the following function is to extract the data from the form 
	// and return clean data to be inserted into database
	public function extractFormData($studentList, $timetable_dates)
	{
		$evaluationData = array();
		if($timetable_dates == NULL){
			foreach($studentList as $id=>$name){
					if($this->getRequest()->getPost('attendance_'.$id) == "absent"){
						$evaluationData[$id]= $this->getRequest()->getPost('attendance_'.$id);
				}
			}
		} else if($studentList == NULL) {
			foreach($timetable_dates as $key=>$value){
				if($this->getRequest()->getPost('lectures_'.$key)== "cancel"){
					$evaluationData[$key]= $this->getRequest()->getPost('lectures_'.$key);
				}
			}
		}
		else if($studentList == 'Remarks'){
			foreach($timetable_dates as $key=>$value){
				$evaluationData[$key]= $this->getRequest()->getPost('reasons_'.$key);
			}
		}
		 else {
			foreach($studentList as $id=>$name){
				foreach($timetable_dates as $key=>$value){
					if($this->getRequest()->getPost('attendance_'.$id.'_'.$key)== "absent"){
						$evaluationData[$id][$key]= $this->getRequest()->getPost('attendance_'.$id.'_'.$key);
					}
				}
			}
		}
		
		return $evaluationData;
	}


	//the following function is to extract the data from the form 
	// and return clean data to be inserted into database
	public function extractFormData1($timetable_dates)
	{
		$evaluationData = array();
			foreach($timetable_dates as $key=>$value){
				$evaluationData[$key]= $this->getRequest()->getPost('attendance_'.$key);
			}
		
		return $evaluationData;
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
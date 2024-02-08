<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace EmpAttendance\Controller;

use EmpAttendance\Service\EmpAttendanceServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use EmpAttendance\Form\EmpAttendanceForm;
use EmpAttendance\Form\RecordAttendanceForm;
use EmpAttendance\Form\SearchForm;
use EmpAttendance\Model\EmpAttendance;
use Zend\Session\Container;

 
class EmpAttendanceController extends AbstractActionController
{
	protected $attendanceService;
	protected $username;
	protected $userrole;
	protected $usertype;
	protected $userregion;
	protected $userDetails;
	protected $userImage;
	protected $employee_details_id;
	protected $organisation_id;
	
	public function __construct(EmpAttendanceServiceInterface $attendanceService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->attendanceService = $attendanceService;
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
		* Getting the student_id/employee_details_id related to username
		*/
		
		$empData = $this->attendanceService->getUserDetailsId($this->username, $tableName = 'employee_details');
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
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
	
	//old function
	public function empattendanceAction()
    {
        $form = new EmpAttendanceForm();
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 var_dump($form);
				 die();
             }
			 return $this->redirect()->toRoute('addempattendancecategory');
         }
        return array('form' => $form);
    }
    
	public function addEmployeeAttendanceAction()
    {
        $this->loginDetails();
		
		$form = new SearchForm();
		
		$unitsList = $this->attendanceService->listSelectData($tableName='department_units', $columnName='unit_name', $this->organisation_id);
		
		$message = NULL;
	   
	    $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$unitName = $this->getRequest()->getPost('unit_name');
				$from_date = date("Y-m-d", strtotime(substr($this->getRequest()->getPost('from_date'),0,10)));
				$to_date_tmp = date("Y-m-d", strtotime(substr($this->getRequest()->getPost('from_date'),13,10)));
				$to_date = $this->truncateToDate($from_date, $to_date_tmp);
				$staffList = $this->attendanceService->getStaffList($unitName, $this->organisation_id);
				$staffAttendance = $this->attendanceService->getEmployeeAttendance($from_date, $to_date, $unitName, $this->organisation_id);
				$attendanceRecordDate = $this->attendanceService->getAttendanceRecordDates($from_date, $to_date, $unitName);
				$absentData = $this->attendanceService->getAbsenteeList($from_date, $to_date, $unitName);
				$weekends = $this->attendanceService->getWeekends($from_date, $to_date);
				$attendanceForm = new EmpAttendanceForm($staffList, $from_date, $to_date);
             }
         }
		 else {
			 $staffList = array();
			 $attendanceForm = NULL;
			 $from_date = NULL;
			 $to_date = NULL;
			 $staffAttendance = NULL;
			 $attendanceRecordDate = NULL;
			 $absentData = NULL;
			 $weekends = NULL;
			 $unitName = NULL;
		 }
		
		 
        return array(
			'form' => $form,
			'attendanceForm' => $attendanceForm,
			'staffAttendance' => $staffAttendance,
			'attendanceRecordDate' => $attendanceRecordDate,
			'absentData' => $absentData,
			'weekends' => $weekends,
			'from_date' => $from_date,
			'to_date' => $to_date,
			'unitsList' => $unitsList,
			'unitName' => $unitName,
			'staffList' => $staffList,
			'message' => $message);
    }
	
	public function recordEmployeeAttendanceAction()
	{
		$this->loginDetails();
		
		$form = new RecordAttendanceForm();
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$unitName = $this->getRequest()->getPost('departments_units_id');
				$from_date = $this->getRequest()->getPost('from_date');
				$to_date = $this->getRequest()->getPost('to_date');
				$data = $this->extractFormData();
				try {
					 $this->attendanceService->saveAttendanceRecord($unitName, $from_date, $to_date, $data);
					 $this->flashMessenger()->addMessage('Employee Attendance was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "Employee Attendance", "ALL", "SUCCESS");
					 return $this->redirect()->toRoute('empattendance');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
	}
	
	public function editEmployeeAttendanceAction()
    {
        $this->loginDetails();
		
		$form = new EmpAttendanceForm();
		$attendanceModel = new EmpAttendance();
		$form->bind($attendanceModel);
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 var_dump($form);
				 die();
                 try {
					 $this->attendanceService->save($attendanceModel);
					 $this->flashMessenger()->addMessage('Employee Attendance was successfully edited');
					 $this->auditTrailService->saveAuditTrail("EDIT", "Employee Leave Attendance", "ALL", "SUCCESS");
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array('form' => $form);
    }
    
	public function viewEmployeeAttendanceAction()
    {
        $this->loginDetails();
		
		$form = new SearchForm();
		
		$unitsList = $this->attendanceService->listSelectData($tableName='department_units', $columnName='unit_name', $this->organisation_id);
		$leaveCategory = $this->attendanceService->listSelectData('emp_leave_category', 'leave_category', NULL);
	   
	    $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$unitName = $this->getRequest()->getPost('unit_name');
				$from_date = date("Y-m-d", strtotime(substr($this->getRequest()->getPost('from_date'),0,10)));
				$to_date = date("Y-m-d", strtotime(substr($this->getRequest()->getPost('from_date'),13,10)));
				$staffList = $this->attendanceService->getStaffList($unitName, $this->organisation_id);
				$staffAttendance = $this->attendanceService->getEmployeeAttendance($from_date, $to_date, $unitName, $this->organisation_id);
				$attendanceRecordDate = $this->attendanceService->getAttendanceRecordDates($from_date, $to_date, $unitName);
				$absentData = $this->attendanceService->getAbsenteeList($from_date, $to_date, $unitName);
				$weekends = $this->attendanceService->getWeekends($from_date, $to_date);
				$attendanceForm = new EmpAttendanceForm($staffList, $from_date, $to_date);
             }
         }
		 else {
			 $staffList = array();
			 $attendanceForm = NULL;
			 $from_date = NULL;
			 $to_date = NULL;
			 $staffAttendance = array();
			 $attendanceRecordDate = array();
			 $weekends = NULL;
			 $absentData = NULL;
		 }
		
		 
        return array(
			'form' => $form,
			'attendanceForm' => $attendanceForm,
			'staffAttendance' => $staffAttendance,
			'attendanceRecordDate' => $attendanceRecordDate,
			'absentData' => $absentData,
			'weekends' => $weekends,
			'from_date' => $from_date,
			'to_date' => $to_date,
			'unitsList' => $unitsList,
			'leaveCategory' => $leaveCategory,
			'staffList' => $staffList);
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
	public function extractFormData()
	{
		$unitName = $this->getRequest()->getPost('departments_units_id');
		$from_date = $this->getRequest()->getPost('from_date');
		$to_date = $this->getRequest()->getPost('to_date');
		$staffList = $this->attendanceService->getStaffList($unitName, $this->organisation_id);
		$staff_no = count($staffList);
		
		$i = 1;
		foreach($staffList as $detail){
				$staffs[$i++] = $detail['id'];			
		}
		
		//calculate the no of days between the two selected dates
		$start = strtotime($from_date);
		$end =strtotime($to_date);
		$date_diff = $end-$start;
		$no_days = floor($date_diff/(60*60*24)) + 1;
			
		$attendanceData = array();		
		//evaluation data => 'evaluation_'.$i.$j,
		$index=0;
		for($i=1; $i<=$staff_no; $i++)
		{
			for($j=0; $j<$no_days; $j++)
			{
				if($this->getRequest()->getPost('attendance_'.$i.'_'.$j) == 'absent'){
					$attendanceData[$index]['employee_details_id'] = $staffs[$i];
					$attendanceData[$index]['absent_date'] = date(substr($to_date,0,8).($j+1));
					$index++;
				}
			}
		}
		return $attendanceData;
	}
}

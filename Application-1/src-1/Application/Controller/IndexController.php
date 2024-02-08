<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Service\ApplicationServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;

class IndexController extends AbstractActionController
{
	protected $applicationService;
	protected $notificationService;
	protected $auditTrailService;
	protected $serviceLocator;
	protected $username;
	protected $userrole;
	protected $usertype;
	protected $user_status_id;
	protected $userregion;
	protected $userDetails;
	protected $userImage;
	protected $employee_details_id;
	protected $alumni_details_id;
	protected $user_department;
	protected $user_staff_type_id;
	protected $student_id;
	protected $organisation_id;
	
	public function __construct(ApplicationServiceInterface $applicationService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->applicationService = $applicationService;
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
           $this->user_status_id = $authPlugin['user_status_id'];
        
		
		/*
		* Getting the student_id/employee_details_id related to username
		*/

		if($this->usertype == 1){
			$empData = $this->applicationService->getUserDetailsId($this->username, $tableName = 'employee_details');
			foreach($empData as $emp){
				$this->employee_details_id = $emp['id'];
				$this->user_department = $emp['departments_id'];
				$this->user_staff_type_id = $emp['major_occupational_group_id'];
			}
		}
		else if($this->usertype == 2){
			$stdData = $this->applicationService->getUserDetailsId($this->username, $tableName = 'student');
			foreach ($stdData as $std) {
				$this->student_id = $std['id'];
			}
		}	

		else if($this->usertype == 3){
			$parentData = $this->applicationService->getUserDetailsId($this->username, $tableName = 'parent_portal_access');
			foreach ($parentData as $parent) {
				$this->parent_id = $parent['id'];
			}
		}	

		else if($this->usertype == 4){
			$jobApplicantData = $this->applicationService->getUserDetailsId($this->username, $tableName = 'job_applicant');
			foreach ($jobApplicantData as $applicant) {
				$this->job_applicant_id = $applicant['id'];
			}
		}

		else if($this->usertype == 5){
			$alumniData = $this->applicationService->getUserDetailsId($this->username, $tableName = 'alumni');
			foreach ($alumniData as $alumni) {
				$this->alumni_details_id = $alumni['id'];
			}
		}
		
		//get the organisation id
		$organisationID = $this->applicationService->getOrganisationId($this->username, $this->usertype);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}
		
		$this->userDetails = $this->applicationService->getUserDetails($this->username, $this->usertype);
		$this->userImage = $this->applicationService->getUserImage($this->username, $this->usertype);
		
	}

	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }
	
    public function indexAction()
    { 
    	$this->loginDetails();
    	if($this->user_status_id == 0){
    		$this->redirect()->toRoute('changepassword');
    	}

        $leave_notifications = $this->applicationService->getNotifications('Leave', $this->userrole, $this->employee_details_id, $this->user_department, $this->organisation_id);
		$pms_notifications = $this->applicationService->getNotifications('PMS', $this->userrole, $this->employee_details_id, $this->user_department, $this->organisation_id);
		$tour_notifications = $this->applicationService->getNotifications('Tour', $this->userrole, $this->employee_details_id, $this->user_department, $this->organisation_id);
		$goods_requisition_notifications = $this->applicationService->getGoodsRequisitiontNotifications('Goods Requisition', $this->userrole, $this->employee_details_id, $this->user_department, $this->organisation_id);
		$staff_transfer_notification = $this->applicationService->getStaffTransferNotification('Staff Transfer', $this->userrole, $this->employee_details_id, $this->user_department, $this->organisation_id);
		$staff_promotion_notification = $this->applicationService->getStaffPromotionNotification('Staff Promotion', $this->userrole, $this->employee_details_id, $this->user_department, $this->organisation_id);
		$staff_resignation_notification = $this->applicationService->getStaffResignationNotification('Staff Resignation', $this->userrole, $this->employee_details_id, $this->user_department, $this->organisation_id);
		//$goods_requisition_notifications = $this->applicationService->getNotifications('Staff Resignation', $this->userrole, $this->employee_details_id, $this->user_department, $this->organisation_id);*/
		$goods_notifications = array();
		$other_notifications = array();
		$upcoming_dates = $this->applicationService->getUpcomingDates($this->userrole);
		$staffDetails = $this->applicationService->getStaffDetails($this->employee_details_id);
		$presentPositionTitle = $this->applicationService->getPresentPositionTitle($this->employee_details_id);
		$presentPositionLevel = $this->applicationService->getPresentPositionLevel($this->employee_details_id);

		$no_of_student = $this->applicationService->getNumberOfStudents(); 

		$no_of_staff = $this->applicationService->getNumberOfStaff();

		$staff_on_leave = $this->applicationService->getStaffOnLeave($this->organisation_id);
		
		$staff_on_tour = $this->applicationService->getStaffOnTour($this->organisation_id);

		//var_dump($no_of_student); die();

		$organisationList = $this->getCollegeArrayList('Student');

		$organisationLists = $this->getCollegeArrayList('Staff');

		//Student Dashboard
		$studentDetails = $this->applicationService->getStudentDetails($this->student_id);
		$stdCurrentSemesterDet = $this->applicationService->getStdCurrentSemesterDetails($this->student_id, $this->organisation_id);
		$moduleList = $this->applicationService->getAcademicModuleLists($this->student_id, $this->organisation_id);
		$studentCADetails = $this->applicationService->getStdCurrentCADetails($this->student_id, $this->organisation_id);
		$stdAcademicTimetable = $this->applicationService->getStudentAcademicTimetable($this->student_id, $this->organisation_id);
		$timetableTimings = $this->applicationService->getTimetableTiming($this->organisation_id);
        $moduleTutor = $this->applicationService->getAcademicModuleTutor($this->student_id, $this->organisation_id);

        $academicModuleList = $this->applicationService->getStdAcademicModuleLists($this->student_id, $this->organisation_id);

       // $totalLecturesDelievered = $this->applicationService->getTotalLecturesDelivered($this->student_id, $this->organisation_id);
        $absenteeModuleList = $this->applicationService->getAbsenteeModuleRecord($this->student_id, $this->organisation_id);

        return array(
			'upcoming_dates' => $upcoming_dates,
			'leave_notifications' => $leave_notifications,
			'pms_notifications' => $pms_notifications,
			'tour_notifications' => $tour_notifications,
			'goods_requisition_notifications' => $goods_requisition_notifications,
			'staff_transfer_notification' => $staff_transfer_notification,
			'staff_promotion_notification' => $staff_promotion_notification,
			'staff_resignation_notification' => $staff_resignation_notification,
			'usertype' => $this->usertype,
			'goods_notifications' => $goods_notifications,
			'other_notifications' => $other_notifications,
			'student_id' => $this->student_id,
			'employee_details_id' => $this->employee_details_id,
			'staffDetails' => $staffDetails,
			'presentPositionTitle' => $presentPositionTitle,
			'presentPositionLevel' => $presentPositionLevel,
			'no_of_student' => $no_of_student,
			'no_of_staff' => $no_of_staff,
			'staff_on_tour' => $staff_on_tour,
			'staff_on_leave' => $staff_on_leave,
			'organisationList' => $organisationList,
			'organisationLists' => $organisationLists,
			'userrole' => $this->userrole,
			'studentDetails' => $studentDetails,
			'moduleList' => $moduleList,
			'stdCurrentSemesterDet' => $stdCurrentSemesterDet,
			'studentCADetails' => $studentCADetails,
			'stdAcademicTimetable' => $stdAcademicTimetable,
			'timetableTimings' => $timetableTimings,
			'moduleTutor' => $moduleTutor,
			'absenteeModuleList' => $absenteeModuleList,
			'user_staff_type_id' => $this->user_staff_type_id,
			//'totalLecturesDelievered' => $totalLecturesDelievered,
		); 
    }


    public function getCollegeArrayList($type)
    {
        $organisation_array = array();

        if($type == 'Student'){
        	//$organisation = $this->organisation_id;
            $organisation_array_list = $this->applicationService->listSelectData('organisation','organisation_name');
            unset($organisation_array_list['1']);
                $organisation_array = $organisation_array_list;

        }else if($type == 'Staff'){
            $organisation_array_list = $this->applicationService->listSelectData('organisation','organisation_name');
            $organisation_array = $organisation_array_list;
        }    
        return $organisation_array;
    } 

    public function testPartialAction()
    {
    	$test = "TETTTSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSetstsssssssssssssssssssssssssss"; echo $test; die();
    	return array(
    		'test' => $test,
    	);
    }   
}

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace StaffPortal\Controller;

use Zend\ServiceManager\ServiceLocatorInterface;
use StaffPortal\Service\StaffPortalServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use StaffPortal\Model\StaffPortal;
use StaffPortal\Model\StaffDetail;
use StaffPortal\Form\StaffDetailForm;
use StaffPortal\Form\AttendanceSearchForm;
use StaffPortal\Form\StaffAttendanceForm;
use StaffPortal\Form\StudentSearchForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;

use DOMPDFModule\View\Model\PdfModel;
use Zend\Http\Response\Stream;
use Zend\Http\Headers;

//AJAX
use Zend\Paginator\Adapter\DbSelect;
use Zend\Form\Element\Select;

/**
 * Description of IndexController
 *
 * @author Mendrel
 */
class StaffPortalController extends AbstractActionController
{
    
	protected $staffService;
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
	
	public function __construct(StaffPortalServiceInterface $staffService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->staffService = $staffService;
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
        * Getting the employee_details_id related to username
        */
        
        $empData = $this->staffService->getUserDetailsId($this->username);
        foreach($empData as $emp){
            $this->employee_details_id = $emp['id'];
        }

        $stdData = $this->staffService->getUserDetailsId($this->username);
        foreach($stdData as $std){
            $this->student_id = $std['id'];
        }

        //get the organisation id
        $organisationID = $this->staffService->getOrganisationId($this->username);
        foreach($organisationID as $organisation){
            $this->organisation_id = $organisation['organisation_id'];
        }

        //get the organisation id
        $empUnitId = $this->staffService->getDeptUnitId($this->username);
        foreach($empUnitId as $unit){
            $this->departments_units_id = $unit['departments_units_id'];
        }

        $this->userDetails = $this->staffService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->staffService->getUserImage($this->username, $this->usertype);
	}

    public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }


	public function staffDashboardAction()
     {
        $this->loginDetails();     	

     	  $form = new StaffDetailForm();

          return new ViewModel(array(
          	'form' => $form,
          	'personalDetails' => $this->staffService->getStaffPersonalDetails($this->employee_details_id),
          	'empLastLeave' => $this->staffService->getEmpLastLeaveDetails($this->employee_details_id),
          	'employee_details_id' => $this->employee_details_id,
          ));
     }


     public function staffProfileAction()
     {
        $this->loginDetails();
		
        //get the id
        $id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		//$employee_id = NULL;
		
		if(is_numeric($id)){	
				//$employee_id = $id;
				$form = new StaffDetailForm();

				return new ViewModel(array(
					'form' => $form,
					'details' => $this->staffService->getStaffPersonalDetails($id),
					'staffPermanentAddress' => $this->staffService->getStaffPermanentAddress($id),
					'staffDetails' => $this->staffService->getStaffDetails($id),
					'empRelationDetails' => $this->staffService->getEmpRelationDetails($id),
					'empType' => $this->staffService->getEmpType($id),
					'empCurrentPosition' => $this->staffService->getEmpCurrentPosition($id),
					'empPositionLevel' => $this->staffService->getEmpPositionLevel($id),
					'empDeptUnit' => $this->staffService->getEmpDeptUnit($id),
					'empPublication' => $this->staffService->getEmpPublication($id),
					'rubWorkExperience' => $this->staffService->getEmpWorkExperience($id, 'RUB'),
                    'nonRubWorkExperience' => $this->staffService->getEmpWorkExperience($id, 'NON-RUB'),
					'empEducationDetails' => $this->staffService->getEmpEducationDetails($id),
					'empAwardDetails' => $this->staffService->getEmpAwardDetails($id),
					'empCommunityService' => $this->staffService->getEmpCommunityServiceDetails($id),
					'empContributionDetails' => $this->staffService->getEmpContributionDetails($id),
					'empResponsibilityDetails' => $this->staffService->getEmpResponsibilityDetails($id),
					'empTrainingDetails' => $this->staffService->getEmpTrainingDetails($id),
					'id' => $id,
				));
				
			} else {
				
				$form = new StaffDetailForm();
				
				return new ViewModel(array(
					'form' => $form,
					'details' => $this->staffService->getStaffPersonalDetails($this->employee_details_id),
					'staffPermanentAddress' => $this->staffService->getStaffPermanentAddress($this->employee_details_id),
					'staffDetails' => $this->staffService->getStaffDetails($this->employee_details_id),
					'empRelationDetails' => $this->staffService->getEmpRelationDetails($this->employee_details_id),
					'empType' => $this->staffService->getEmpType($this->employee_details_id),
					'empCurrentPosition' => $this->staffService->getEmpCurrentPosition($this->employee_details_id),
					'empPositionLevel' => $this->staffService->getEmpPositionLevel($this->employee_details_id),
					'empDeptUnit' => $this->staffService->getEmpDeptUnit($this->employee_details_id),
					'empPublication' => $this->staffService->getEmpPublication($this->employee_details_id),
					'rubWorkExperience' => $this->staffService->getEmpWorkExperience($this->employee_details_id, 'RUB'),
                    'nonRubWorkExperience' => $this->staffService->getEmpWorkExperience($this->employee_details_id, 'NON-RUB'),
					'empEducationDetails' => $this->staffService->getEmpEducationDetails($this->employee_details_id),
					'empAwardDetails' => $this->staffService->getEmpAwardDetails($this->employee_details_id),
					'empCommunityService' => $this->staffService->getEmpCommunityServiceDetails($this->employee_details_id),
					'empContributionDetails' => $this->staffService->getEmpContributionDetails($this->employee_details_id),
					'empResponsibilityDetails' => $this->staffService->getEmpResponsibilityDetails($this->employee_details_id),
					'empTrainingDetails' => $this->staffService->getEmpTrainingDetails($this->employee_details_id),
					'employee_details_id' => $this->employee_details_id,
				));
			}
     }


     public function staffLeaveStatusAction()
     {
        $this->loginDetails();

     	return new ViewModel(array(
            'employee_details_id' => $this->employee_details_id,
     		'pendingLeave' => $this->staffService->getPendingLeaveList($this->employee_details_id),
     		'approvedLeave' => $this->staffService->getApprovedLeaveList($this->employee_details_id),
     		'rejectedLeave' => $this->staffService->getRejectedLeaveList($this->employee_details_id),
            'keyphrase' => $this->keyphrase,
     	));
     }


     public function staffRejectedLeaveDetailsAction()
     {
        $this->loginDetails();
        $id = (int) $this->params()->fromRoute('id', 0);
        
        return new ViewModel(array(
            'rejectedLeaveDetails' => $this->staffService->getStaffRejectedLeaveStatus($id),
        ));
     }

     public function staffTourStatusAction()
     {
        $this->loginDetails();

        $message = NULL;

     	return new ViewModel(array(
     		'tourList' => $this->staffService->getStaffTourList($this->employee_details_id),
			'keyphrase' => $this->keyphrase,
            'message' => $message,
     	));
     }

     public function staffTourDetailsAction()
     {
        $this->loginDetails();

     	$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $form = new StaffDetailForm();

            $travelAuthDetails = $this->staffService->getStaffTourAuthDetails($id);
            $approvingAuthority = $this->staffService->getTourApprovingAuthority($id);
			$fromDate = $this->staffService->findFromTravelDate($id);
			$toDate = $this->staffService->findToTravelDate($id);

            return new ViewModel(array(
                'id' => $id,
                'form' => $form,
                'travelAuthDetails' => $travelAuthDetails,
                'approvingAuthority' => $approvingAuthority,
				'fromDate' => $fromDate,
				'toDate' => $toDate,
                'travelDetails' => $this->staffService->getStaffTourDetails($id),
                'keyphrase' => $this->keyphrase,
            ));
        }else{
            return $this->redirect()->toRoute('stafftourstatus');
        }
     }


     //Function to delete the staff pending tour
     public function deleteStaffPendingTourAction()
     {
        $this->loginDetails();
         
         //get the id of the travel authorization proposal
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
             try {
                 $result = $this->staffService->deleteStaffPendingTour($id);
                 $this->auditTrailService->saveAuditTrail("DELETE", "Travel Authorization", "ALL", "SUCCESS");
                 $this->flashMessenger()->addMessage("You have successfully deleted the record");
                 return $this->redirect()->toRoute('stafftourstatus');
                 //return $this->redirect()->toRoute('emptraveldetails');
             }
             catch(\Exception $e) {
                     die($e->getMessage());
                     // Some DB Error happened, log it and let the user know
             }
        }else {
            return $this->redirect()->toRoute('stafftourstatus');
        }
     }


     public function printApprovedTravelDetailsAction()
     {
        $this->loginDetails();
         //get the id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $pdf = new PdfModel();
            $pdf->setOption('fileName', 'approvedTravelDetails'); // Triggers PDF download, automatically appends ".pdf"
            $pdf->setOption('display', PdfModel::DISPLAY_ATTACHMENT);
            $pdf->setOption('paperSize', 'a4'); // Defaults to "8x11"
            $pdf->setOption('paperOrientation', 'landscape'); // Defaults to "portrait"


           
            //To set view variables
            $pdf->setVariables(array(
                'id' => $id,
                'travelAuthDetails' => $this->staffService->getStaffTourAuthDetails($id),
                'approvingAuthority' => $this->staffService->getTourApprovingAuthority($id),
                'travelDetails' => $this->staffService->getStaffTourDetails($id),
                'fromDate' => $this->staffService->findFromTravelDate($id),
                'toDate' => $this->staffService->findToTravelDate($id),
           ));

            return $pdf;
        }
        else{
            $this->redirect()->toRoute('stafftourstatus');
        }
     }


     public function staffJobApplicationStatusAction()
     {
        $this->loginDetails();

        return new ViewModel(array(
            'appliedList' => $this->staffService->getStaffJobApplicataionList($this->employee_details_id),
            'employee_details_id' => $this->employee_details_id,
        ));
     }

     public function staffPromotionStatusAction()
     {
        $this->loginDetails();

        return new ViewModel(array(
            'promotionDetails' => $this->staffService->getStaffPromotionDetails($this->employee_details_id),
        ));
     }


     public function staffResignationStatusAction()
     {
         $this->loginDetails();

        return new ViewModel(array(
            'resignationDetails' => $this->staffService->getStaffResignationDetails($this->employee_details_id),
        ));
     }


     public function staffTransferStatusAction()
     {
         $this->loginDetails();

        return new ViewModel(array(
            'transferDetails' => $this->staffService->getStaffTransferDetails($this->employee_details_id),
        ));
     }



     public function staffAttendanceRecordAction()
     {
         $this->loginDetails();

     	$form = new AttendanceSearchForm();

     	$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$from_date = date("Y-m-d", strtotime(substr($this->getRequest()->getPost('from_date'),0,10)));
				$to_date_tmp = date("Y-m-d", strtotime(substr($this->getRequest()->getPost('from_date'),13,10)));
				$to_date = $this->truncateToDate($from_date, $to_date_tmp);
				$staffAttendance = $this->staffService->getStaffAttendance($from_date, $to_date, $this->employee_details_id);
				$attendanceRecordDate = $this->staffService->getAttendanceRecordDates($from_date, $to_date, $this->departments_units_id);
				$absentData = $this->staffService->getAbsenteeList($from_date, $to_date, $this->employee_details_id);
				$weekends = $this->staffService->getWeekends($from_date, $to_date);
				$attendanceForm = new StaffAttendanceForm($from_date, $to_date);
             }
         }
		 else {
			 $attendanceForm = NULL;
			 $from_date = NULL;
			 $to_date = NULL;
			 $staffAttendance = NULL;
			 $attendanceRecordDate = NULL;
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
        );
     }


     public function staffLeaveEncashmentStatusAction()
     {
         $this->loginDetails();

        return new ViewModel(array(
            'leaveEncashment' => $this->staffService->getStaffLeaveEncashmentDetails($this->employee_details_id),
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


    // Ajax action for administration module
    public function ajaxGetUserAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');

        $sql       = "SELECT * FROM employee_details WHERE organisation_id='$parentValue'";
        
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0] = "Please Select User";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['first_name'].' '.$res['middle_name'].' '.$res['last_name'].' ('.$res['emp_id'].')';
        
    }
        return new JsonModel([
            'data' => $selectTwoData
        ]); 
    }

    //Ajax Function to get the sub menus
    public function ajaxGetParentModuleAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        
        if($parentValue == 1){
            $sql       = "SELECT * FROM user_menu WHERE user_menu_level='0'";

            $statement = $dbAdapter->query($sql);
            $result    = $statement->execute();
            $selectTwoData = array();
            $selectTwoData[0] = "Please Select Level Zero Module";
            foreach ($result as $res) {
                $selectTwoData[$res['id']] = $res['menu_name'];
            }
        } 
        else if($parentValue == 2){
            $sql       = "SELECT * FROM user_menu WHERE user_menu_level='1'";

            $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0] = "Please Select Level One Module";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['menu_name'];
            }
        }
         else if($parentValue == 3){
            $sql       = "SELECT * FROM user_menu WHERE user_menu_level='2'";
        
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0] = "Please Select Level Two Module";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['menu_name'];
            }
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }

    
    //Ajax Function to get the sub menus
    public function ajaxGetSubMenusAction()
    {
        $parentValue1 = $_POST['value'];
        
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');

        $sql       = "SELECT * FROM user_menu WHERE user_menu_id='$parentValue1'";
        
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0] = "Please Select Sub Menu";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['menu_name'];
        
    }

        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }


    //Ajax Function to get the sub menus
    public function ajaxGetModuleAction()
    {
        $parentValue = $_POST['value'];

        
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        
        if($parentValue == 1){
            $sql       = "SELECT * FROM user_menu WHERE user_menu_level='0'";
        
        
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0] = "Please Select Menu Level Zero";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['menu_name'];
        }
    }

    else if($parentValue == 2){
            $sql       = "SELECT * FROM user_menu WHERE user_menu_level='1'";
        
        
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0] = "Please Select Menu Level One";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['menu_name'];
        }
    }

    else if($parentValue == 3){
            $sql       = "SELECT * FROM user_menu WHERE user_menu_level='2'";
        
        
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0] = "Please Select Menu Level Two";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['menu_name'];
        }
    }
    
        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }


    public function ajaxGetRouteDetailsAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');

        $sql       = "SELECT * FROM route_list WHERE user_module_id='$parentValue'";
        
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0] = "Please Select Route Details";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['route_details'];
        
    }

        return new JsonModel([
            'data' => $selectTwoData
        ]); 
    }


    public function ajaxGetUserUnitAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');

        $sql       = "SELECT * FROM department_units WHERE departments_id='$parentValue'";
        
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0] = "Please Select User Role Unit";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['unit_name'];
        
    }

        return new JsonModel([
            'data' => $selectTwoData
        ]); 
    }


    /*
    * AJAX Actions for Academic Calendar
    */
    
    public function ajaxModuleNameAction()
    {
        $semester = $this->getSemester($this->organisation_id);
		$academic_year = $this->getAcademicYear($semester);
                
        $parentValue = $_POST['value'];
		
		$semesterArray = $this->getSemesterArray($semester, $parentValue);
        
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        //$sql       = "SELECT id, module_title FROM academic_modules_allocation where semester IN ".$semesterArray." AND academic_year =".$academic_year." AND programmes_id='$parentValue'";
		if($semester == 'Autumn')
			$sql       = "SELECT id, module_title FROM `academic_modules_allocation` WHERE `academic_year` = '$academic_year'  AND `semester` IN (1,3,5,7,9) AND programmes_id='$parentValue'";
		else
			$sql       = "SELECT id, module_title FROM `academic_modules_allocation` WHERE `academic_year` = '$academic_year'  AND `semester` IN (2,4,6,8,10) AND programmes_id='$parentValue'";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Please Select a Module";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['module_title'];
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }


    public function ajaxRequisitionAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        
        $sql       = "SELECT `t1`.`id` AS `id`, `t1`.`item_name` AS `item_name` FROM `item_name` AS `t1` INNER JOIN `item_sub_category` AS `t2` ON `t2`.`id` = `t1`.`item_sub_category_id` WHERE `t1`.`item_sub_category_id`='$parentValue' AND `t2`.`organisation_id`= '$this->organisation_id'";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
         $selectTwoData = array('0' => 'Select Item');
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['item_name'];
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }


    /*
    * AJAX Actions
    * !st is for Budget Proposal
    */
    
    public function ajaxObjectCodeAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter1 = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        
        $sql       = "SELECT id, object_name FROM object_code where broad_head_name_id='$parentValue'";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData['------>']="---------";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['object_name'];
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }
    
    //ajax for budget reappropriation

    public function ajaxBroadHeadNameAction()
    {
            $parentValue = $_POST['value'];

            $dbAdapter1 = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');

            $sql       = "SELECT `t1`.`id` AS `id`, `t1`.`broad_head_name_id` AS `broad_head_name_id`, `t2`.`broad_head_name` AS `broad_head_name` FROM `budget_proposal_capital` AS `t1` INNER JOIN `broad_head_name` AS `t2` ON `t1`.`broad_head_name_id` = `t2`.`id` where activity_name LIKE '$parentValue'";
            $statement = $dbAdapter1->query($sql);
            $result    = $statement->execute();
            $selectTwoData = array();
            $selectTwoData['------>']="---------";
            foreach ($result as $res) {
                $selectTwoData[$res['id']] = $res['broad_head_name'];
            }

            return new JsonModel([
                'data' => $selectTwoData
            ]);
    }
    
    public function ajaxReappropriateObjectCodeAction()
    {
        $parentValue1 = $_POST['value'];
        
        $dbAdapter1 = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
       
        $sql       = "SELECT `t1`.`id` AS `id`, `t2`.`broad_head_name` AS `broad_head_name`, `t3`.`object_name` AS `object_name` FROM `budget_proposal_capital` AS `t1` INNER JOIN `broad_head_name` AS `t2` ON `t1`.`broad_head_name_id` = `t2`.`id` INNER JOIN `object_code` AS `t3` ON `t1`.`object_code_id` = `t3`.`id` where broad_head_name LIKE '$parentValue1'";

        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData['------>']="---------";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['object_name'];
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }


    //Ajax actions for current budget 
    public function ajaxdataAction()
    {
        $parentValue = $_POST['value'];
      
        $dbAdapter1 = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        
        $sql       = "SELECT id, account_code FROM chart_of_accounts where accounts_group_head_id='$parentValue'";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData['------>']="---------";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['account_code'];
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);
    } 
    
    //ajax for budget reappropriation
    
    public function ajaxBudgetLedgerAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter1 = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        
        $sql       = "SELECT `t1`.`id` AS `id`, `t1`.`accounts_group_head_id` AS `accounts_group_head_id`, `t2`.`group_head` AS `group_head`, `t3`.`ledger_head` AS `ledger_head` FROM `budget_proposal` AS `t1` INNER JOIN `accounts_group_head` AS `t2` ON `t1`.`accounts_group_head_id` = `t2`.`id` INNER JOIN `budget_ledger_head` AS `t3` ON `t1`.`budget_ledger_head_id` = `t3`.`id` where `t1`.`budget_ledger_head_id` ='$parentValue'";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData['------>']="---------";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['group_head'];
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }
    
    public function ajaxReappropriateChartAccountsAction()
    {
        $parentValue1 = $_POST['value'];
        
        $dbAdapter1 = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
       
        $sql       = "SELECT `t1`.`id` AS `id`, `t2`.`group_head` AS `group_head`, `t3`.`account_code` AS `account_code` FROM `budget_proposal` AS `t1` INNER JOIN `accounts_group_head` AS `t2` ON `t1`.`accounts_group_head_id` = `t2`.`id` INNER JOIN `chart_of_accounts` AS `t3` ON `t1`.`chart_of_accounts_id` = `t3`.`id` where group_head LIKE '$parentValue1'";
        
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData['------>']="---------";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['account_code'];
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }


    /*
    * AJAX Actions
    * Not used. Using the a different action from Academic Timetable
    */
    
    /*public function ajaxModuleNameAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        
        $sql       = "SELECT id, module_title FROM academic_modules_allocation where  academic_year =" .date('Y'). " AND programmes_id='$parentValue'";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Please Select a Module";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['module_title'];
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }*/


    //ajax for selecting organisation, department and unit
    
    public function ajaxDepartmentNameAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        
        $sql       = "SELECT * FROM `departments` where `organisation_id`= '$parentValue'";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Select a Department";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['department_name'];
        }
        
        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }
    
    public function ajaxUnitNameAction()
    {
        $parentValue1 = $_POST['value'];
        
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
       
        $sql       = "SELECT `t1`.`id` AS `id`, `t1`.`unit_name` AS `unit_name`,  `t2`.`department_name` AS `department_name` FROM `department_units` AS `t1` INNER JOIN `departments` AS `t2` ON `t1`.`departments_id` = `t2`.`id` where `t2`.`id`='$parentValue1'";
        
        //$sql       = "SELECT * FROM `department_units` where `organisation_id`= '$parentValue1'";
        
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Select a Unit";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['unit_name'];
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);
    } 
    
    //ajax for selecting employee category, position title, position level etc
    
    public function ajaxCategoryAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        
        $sql       = "SELECT * FROM `position_category` where `major_occupational_group_id`= '$parentValue'";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Select a Category";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['category'];
        }
        
        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }
    
    public function ajaxPositionTitleAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        
        $sql       = "SELECT `t1`.*, `t2`.`category` AS `category` FROM `position_title` AS `t1` INNER JOIN `position_category` AS `t2` ON `t1`.`position_category_id` = `t2`.`id` WHERE `t2`.`id`= '$parentValue'";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Select a Title";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['position_title'];
        }
        
        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }
    
    public function ajaxPositionLevelAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        
        $sql       = "SELECT `t1`.*, `t2`.`id` AS `major_occupational_group_id`, `t3`.`id` AS `position_category_id`, `t4`.`position_title` AS `position_title` FROM `position_level` AS `t1` INNER JOIN `major_occupational_group` AS `t2` ON `t1`.`major_occupational_group_id` = `t2`.`id` INNER JOIN `position_category` AS `t3` ON `t3`.`major_occupational_group_id`=`t2`.`id` INNER JOIN `position_title` AS `t4` ON `t3`.`id`=`t4`.`position_category_id` WHERE `t4`.`id`= '$parentValue'";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Select a Position Level";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['position_level'];
        }
        
        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }
    
    public function ajaxPayScaleAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        
        $sql       = "SELECT `t1`.*, `t2`.`position_level` AS `position_level` FROM `pay_scale` AS `t1` INNER JOIN `position_level` AS `t2` ON `t1`.`position_level` = `t2`.`id` WHERE `t2`.`id`= '$parentValue'";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Select a Pay Scale";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['minimum_pay_scale'].'-'.$res['maximum_pay_scale'];
        }
        
        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }
    
    public function ajaxTeachingAllowanceAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        
        $value = explode('-', $parentValue);
        $basic_pay = $value[0];
        
        $sql       = "SELECT `t1`.*, `t2`.`position_level` AS `position_level` FROM `teaching_allowance` AS `t1` INNER JOIN `position_level` AS `t2` ON `t1`.`position_level` = `t2`.`id` INNER JOIN `pay_scale` AS `t3` ON `t3`.`position_level` = `t2`.`id` WHERE `t3`.`minimum_pay_scale` = '$basic_pay'";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Select Teaching Allowance (if applicable)";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['teaching_allowance'].'( '.$res['years_in_service'].' )';
        }
        
        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }
    
    //ajax for selecting dzongkhag, gewog and village
    
    public function ajaxGewogAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        
        $sql       = "SELECT * FROM `gewog` where `dzongkhag_id`= '$parentValue'";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Select a Gewog";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['gewog_name'];
        }
        
        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }
    
    public function ajaxVillageAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        
        $sql       = "SELECT `t1`.*, `t2`.`gewog_name` AS `gewog_name` FROM `village` AS `t1` INNER JOIN `gewog` AS `t2` ON `t1`.`gewog_id` = `t2`.`id` WHERE `t2`.`id`='$parentValue'";

        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Select a Village";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['village_name'];
        }
        
        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }


    // Ajax actions for goods transaction
    public function ajaxItemCategoryAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');

       
        $sql       = "SELECT * FROM item_category where major_class_id ='$parentValue'";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData = array('0' => 'Select Item Category');
        //$selectTwoData['------>']="---------";
       foreach ($result as $res) {
        
            $selectTwoData[$res['id']] = $res['category_type'];
        }
        return new JsonModel([
            'data' => $selectTwoData
        ]);  
    }

    public function ajaxItemDetailsCategoryAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');

        $sql       = "SELECT * FROM item_category where major_class_id ='$parentValue'";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData = array('0' => 'Select Item Category');
        //$selectTwoData['------>']="---------";
       foreach ($result as $res) {
        
            $selectTwoData[$res['id']] = $res['category_type'];
        }
        return new JsonModel([
            'data' => $selectTwoData
        ]);  
    }

    public function ajaxItemDetailsSubCategoryAction()
    {
        $parentValue = $_POST['value'];

        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        //$sql       = "SELECT * FROM item_sub_category where item_category_id='$parentValue' AND organisation_id = '$this->organisation_id'";
        $sql       = "SELECT `t1`.*, `t2`.`category_type` AS `category_type` FROM `item_sub_category` AS `t1` INNER JOIN `item_category` AS `t2` ON `t1`.`item_category_id` = `t2`.`id` WHERE `t2`.`id`='$parentValue' AND `t1`.`organisation_id` = '$this->organisation_id'";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData = array('0' => 'Select Item Sub Category');
        //$selectTwoData['------>']="---------";
       foreach ($result as $res) {
        
            $selectTwoData[$res['id']] = $res['sub_category_type'];
        }
        return new JsonModel([
            'data' => $selectTwoData
        ]);  
    }

    public function ajaxGoodsReceivedPurchasedSubCategoryAction()
    {
        $parentValue = $_POST['value'];

        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
       
        $sql       = "SELECT * FROM `item_sub_category` where `item_category_id`= '$parentValue' AND `organisation_id` = '$this->organisation_id'";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData = array('0' => 'Select Item Sub Category');
       // $selectTwoData['------>']="---------";
       foreach ($result as $res) {
        
            $selectTwoData[$res['id']] = $res['sub_category_type'];
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);  
    }

    public function ajaxGoodsReceivedPurchasedItemNameAction()
    {
        $parentValue1 = $_POST['value'];

        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
       
        $sql       = "SELECT DISTINCT `t1`.`id` AS `id`, `t1`.`item_name` AS `item_name`,  `t2`.`sub_category_type` AS `sub_category_type` FROM `item_name` AS `t1` INNER JOIN `item_sub_category` AS `t2` ON `t1`.`item_sub_category_id` = `t2`.`id` where `t2`.`id`='$parentValue1' AND `t2`.`organisation_id`= '$this->organisation_id'";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData = array('0' => 'Select Item Name');
        //$selectTwoData['------>']="---------";
       foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['item_name'];
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }


    public function ajaxGoodsReceivedDonationSubCategoryAction()
    {
        $parentValue = $_POST['value'];

        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
       
        $sql       = "SELECT * FROM `item_sub_category` where `item_category_id`='$parentValue' AND `organisation_id` = '$this->organisation_id'";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData = array('0' => 'Select Item Sub Category');
        //$selectTwoData['------>']="---------";
       foreach ($result as $res) {
        
            $selectTwoData[$res['id']] = $res['sub_category_type'];
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);  
    }

    public function ajaxGoodsReceivedDonationItemNameAction()
    {
        $parentValue1 = $_POST['value'];

        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
       
        $sql       = "SELECT DISTINCT `t1`.`id` AS `id`, `t1`.`item_name` AS `item_name`,  `t2`.`sub_category_type` AS `sub_category_type` FROM `item_name` AS `t1` INNER JOIN `item_sub_category` AS `t2` ON `t1`.`item_sub_category_id` = `t2`.`id` where `t2`.`id`='$parentValue1' AND `t2`.`organisation_id`= '$this->organisation_id'";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData = array('0' => 'Select Item Name');
        //$selectTwoData['------>']="---------";
       foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['item_name'];
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }


    public function ajaxDeptGoodsTransferAction()
    {
        $parentValue = $_POST['value'];

        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
       
        $sql       = "SELECT * FROM `employee_details` where `departments_units_id`='$parentValue'";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData = array('0' => 'Select Responsible Staff');
        //$selectTwoData['------>']="---------";
       foreach ($result as $res) {
        
            $selectTwoData[$res['id']] = $res['first_name']. ' ' .$res['middle_name']. ' '.$res['last_name'].' ('.$res['emp_id'].')';
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);  
    }


    public function ajaxSubStoreIssueItemNameAction()
    {
        $parentValue = $_POST['value'];

        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
       
        $sql       = "SELECT * FROM `item_name` where `item_sub_category_id`= '$parentValue'";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData = array('0' => 'Select Item Name');
       // $selectTwoData['------>']="---------";
       foreach ($result as $res) {
        
            $selectTwoData[$res['id']] = $res['item_name'];
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);  
    }

   /* public function ajaxSubStoreStockQuantityAction()
    {
        $parentValue1 = $_POST['value'];


        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
       
        $sql       = "SELECT SUM(`t1`.`item_in_stock`) AS `item_in_stock`,  `t2`.`item_name` AS `item_name` FROM `goods_received` AS `t1` INNER JOIN `item_name` AS `t2` ON `t1`.`item_name_id` = `t2`.`id` where `t2`.`item_name` LIKE '$parentValue1'";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
       // $selectTwoData = array('0' => 'Select');
       // $selectTwoData['------>']="---------";
       foreach ($result as $res) {
            $selectTwoData[$res['item_in_stock']] = $res['item_in_stock'];
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }*/


    public function ajaxSubStoreItemDetailsAction()
    {
        $parentValue1 = $_POST['value'];

        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
       
        $sql       = "SELECT `t1`.`id` AS `id`, `t1`.`item_in_stock` AS `item_in_stock`, `t1`.`item_received_date` AS `item_received_date`, `t1`.`item_received_date` AS `item_received_date`,`t1`.`item_specification` AS `item_specification`,`t2`.`item_name` AS `item_name` FROM `goods_received` AS `t1` INNER JOIN `item_name` AS `t2` ON `t1`.`item_name_id` = `t2`.`id` INNER JOIN `item_sub_category` AS `t3` ON `t3`.`id` = `t2`.`item_sub_category_id` WHERE `t2`.`id`='$parentValue1' AND `t1`.`item_in_stock` > '0' AND `t3`.`organisation_id`= '$this->organisation_id' ORDER BY `t1`.`id` ASC";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
       $selectTwoData = array('0' => 'THERE IS NO ITEM IN STOCK');
       //$selectTwoData['------>']="---------";
       foreach ($result as $res) {
            unset($selectTwoData[0]);
            $selectTwoData[$res['id']] = $res['item_name']. ' ('.$res['item_in_stock'].' Qty)'. ' - '.$res['item_received_date']. ' (' .$res['item_specification']. ')';
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }


     public function ajaxDepartmentStaffListAction()
    {
        $parentValue = $_POST['value'];

        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
       
        $sql       = "SELECT * FROM `employee_details` where `departments_units_id`='$parentValue'";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData = array('0' => 'Please Select Responsible Staff');
        //$selectTwoData['------>']="---------";
       foreach ($result as $res) {
        
            $selectTwoData[$res['id']] = $res['first_name']. ' ' .$res['middle_name']. ' '.$res['last_name'].' ('.$res['emp_id'].')';
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);  
    }

   // For listing staff list to issue adhoc goods based on department
    public function ajaxAdhocGoodsIssueStaffListAction()
    {
        $parentValue = $_POST['value'];

        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
       
        $sql       = "SELECT * FROM `employee_details` where `departments_units_id`='$parentValue'";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData = array('0' => 'Please Select Responsible Staff');
        //$selectTwoData['------>']="---------";
       foreach ($result as $res) {
        
            $selectTwoData[$res['id']] = $res['first_name']. ' ' .$res['middle_name']. ' '.$res['last_name'].' ('.$res['emp_id'].')';
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);  
    }


    // Ajax function for selecting item name for adhoc goods issue
    public function ajaxAdhocGoodsIssueItemNameAction()
    {
        $parentValue = $_POST['value'];

        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
       
        $sql       = "SELECT * FROM `item_name` where `item_sub_category_id`= '$parentValue'";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData = array('0' => 'Select Item Name');
       // $selectTwoData['------>']="---------";
       foreach ($result as $res) {
        
            $selectTwoData[$res['id']] = $res['item_name'];
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);  
    }
 
    // Ajax function for selecting item in stock with item name and item received date for adhoc goods issue
    public function ajaxAdhocGoodsIssueItemDetailsAction()
    {
        $parentValue1 = $_POST['value'];

        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
       
        $sql       = "SELECT `t1`.`id` AS `id`, `t1`.`item_in_stock` AS `item_in_stock`, `t1`.`item_received_date` AS `item_received_date`, `t1`.`item_received_date` AS `item_received_date`,`t1`.`item_specification` AS `item_specification`,`t2`.`item_name` AS `item_name` FROM `goods_received` AS `t1` INNER JOIN `item_name` AS `t2` ON `t1`.`item_name_id` = `t2`.`id` INNER JOIN `item_sub_category` AS `t3` ON `t3`.`id` = `t2`.`item_sub_category_id` WHERE `t2`.`id`='$parentValue1' AND `t1`.`item_in_stock` > '0' AND `t3`.`organisation_id`= '$this->organisation_id' ORDER BY `t1`.`id` ASC";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData = array('0' => 'THERE IS NO ITEM IN STOCK');
        //$selectTwoData['------>']="---------";
       foreach ($result as $res) {
            unset($selectTwoData[0]);
            $selectTwoData[$res['id']] = $res['item_name']. ' ('.$res['item_in_stock'].' Qty)'. ' - '.$res['item_received_date']. ' (' .$res['item_specification']. ')';
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }


    // To select the staff for nomiation for sub store
     public function ajaxNominateSubStoreStaffListAction()
    {
        $parentValue = $_POST['value'];

        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
       
        $sql       = "SELECT * FROM `employee_details` where `departments_id`='$parentValue'";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData = array('0' => 'Please Select Responsible Staff');
        //$selectTwoData['------>']="---------";
       foreach ($result as $res) {
        
            $selectTwoData[$res['id']] = $res['first_name']. ' '.$res['middle_name']. ' '.$res['last_name'].' ('.$res['emp_id'].')';
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);  
    }

    // To select staff for requisition goods issue based on the department selected
    public function ajaxRequisitionDepartmentStaffAction()
    {
        $parentValue = $_POST['value'];

        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
       
        $sql       = "SELECT * FROM `employee_details` where `departments_units_id`='$parentValue'";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData = array('0' => 'Please Select Responsible Staff');
        //$selectTwoData['------>']="---------";
       foreach ($result as $res) {
        
            $selectTwoData[$res['id']] = $res['first_name']. ' ' .$res['middle_name']. ' '.$res['last_name'].' ('.$res['emp_id'].')';
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);  
    }

    // Ajax function for selecting item name for requisition issue goods
    public function ajaxRequisitionItemNameListAction()
    {
        $parentValue1 = $_POST['value'];

       /* preg_match("/(\w+\d+)/", $parentValue1, $name_emp_id);
        foreach ($name_emp_id as $key => $value) {
        $emp_id_match = $value;
       }*/

        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');

        $sql       = "SELECT `t1`.`id` AS `id`, `t1`.`item_name_id` AS `item_name_id`, `t1`.`requisition_date` AS `requisition_date`, `t1`.`approved_balance_quantity` AS `approved_balance_quantity`, `t1`.`requisition_status` AS `requisition_status`, `t1`.`item_specification` AS `item_specification`, `t1`.`employee_details_id` AS `employee_details_id`, `t3`.`item_name` AS `item_name` FROM `goods_requisition_details` AS `t1` INNER JOIN `employee_details` AS `t2` ON `t1`.`employee_details_id` = `t2`.`id` INNER JOIN `item_name` AS `t3` ON `t3`.`id` = `t1`.`item_name_id` INNER JOIN `item_sub_category` AS `t4` ON `t4`.`id` = `t3`.`item_sub_category_id` WHERE `t2`.`id`='$parentValue1' AND `t1`.`approved_balance_quantity` > '0' AND `t1`.`requisition_status` = 'Approved' AND `t2`.`organisation_id`= '$this->organisation_id' ORDER BY `t1`.`id` ASC";

       
      //  $sql       = "SELECT * FROM `item_name` where `item_sub_category_id`= '$parentValue1'";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData = array('0' => 'THERE IS NO REQUISITION');
        $selectTwoData['------>']="---------";
       foreach ($result as $res) {
            unset($selectTwoData[0]);
        
            $selectTwoData[$res['id']] = $res['item_name'].' - '.$res['approved_balance_quantity'].' Qty'. ' , '.$res['requisition_date']. ' , ' .$res['item_specification'];
      //$selectTwoData[$res['id']] = $res['id']. '-  '.$res['item_name']. ' ('.$res['approved_balance_quantity'].' Qty)'. ' - '.$res['requisition_date']. ' (' .$res['item_specification']. ')';

        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);  
    }


    // Ajax function for selecting item name for requisition issue goods
    public function ajaxRequisitionItemNameStockListAction()
    {
        $parentValue2 = $_POST['value'];

       // $string = preg_replace('/ \([^)]*\)+/', '', $parentValue2);

        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');

        $sql       = "SELECT `t1`.`id` AS `id`, `t1`.`item_name_id` AS `item_name_id`, `t1`.`item_in_stock` AS `item_in_stock`, `t1`.`item_specification` AS `item_specification`, `t1`.`item_received_date` AS `item_received_date`, `t2`.`item_name` AS `item_name` FROM `goods_received` AS `t1` INNER JOIN `item_name` AS `t2` ON `t1`.`item_name_id` = `t2`.`id` INNER JOIN `goods_requisition_details` AS `t3` ON `t3`.`item_name_id` = `t1`.`item_name_id` AND `t2`.`id` = `t3`.`item_name_id` AND `t1`.`item_name_id` = `t2`.`id` INNER JOIN `item_sub_category` AS `t4` ON `t4`.`id` = `t2`.`item_sub_category_id` WHERE `t3`.`id` = '$parentValue2' AND `t1`.`item_in_stock` > '0' AND `t4`.`organisation_id`= '$this->organisation_id' ORDER BY `t1`.`id` ASC";
       
      //  $sql       = "SELECT * FROM `item_name` where `item_sub_category_id`= '$parentValue1'";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData = array('0' => 'THERE IS NO ITEM IN STOCK');
       // $selectTwoData['------>']="---------";
        foreach ($result as $res) {
            unset($selectTwoData[0]);
            $selectTwoData[$res['id']] = $res['item_name']. ' ('.$res['item_in_stock'].' Qty)'. ' - '.$res['item_received_date']. ' (' .$res['item_specification']. ')';
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]); 

        //echo "No item in stock"; 
    }




    // To display the item in stock based on the item name selected
    public function ajaxRequisitionStockQuantityAction()
    {
        $parentValue1 = $_POST['value'];

        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');

        $sql       = "SELECT `t1`.`item_name_id` AS `item_name_id`, `t1`.`item_in_stock` AS `item_in_stock`, `t2`.`item_name` AS `item_name`, `t3`.`organisation_id` as `organisation_id` FROM `goods_received` AS `t1` INNER JOIN `item_name` AS `t2` ON `t1`.`item_name_id` = `t2`.`id` INNER JOIN `item_sub_category` AS `t3` ON `t3`.`id` = `t2`.`item_sub_category_id` where `t2`.`id`='$parentValue1' AND `t3`.`organisation_id`= '$this->organisation_id'";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData[0] = 0;
       // $selectTwoData = array('0' => 'Select');
       // $selectTwoData['------>']="---------";
       foreach ($result as $res) {
            $selectTwoData[0] += $res['item_in_stock'];
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }



    // Ajax action for PMS Review module
    public function ajaxFeedbackModuleTutorAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        
        $sql       = "SELECT id, module_tutor FROM academic_module_tutors where id='$parentValue'";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $employee_id = NULL;
        foreach ($result as $res) {
            $employee_id = $res['module_tutor'];
        }
        $employee_array = str_replace("/", ",", $employee_id);
        
        $sql2       = "SELECT id, first_name, middle_name, last_name FROM employee_details where emp_id IN (".$employee_array.")";
        $statement2 = $dbAdapter1->query($sql2);
        $result2    = $statement2->execute();
        $selectTwoData = array();
        foreach ($result2 as $res2) {
            $selectTwoData[$res2['id']] = $res2['first_name'].' '.$res2['middle_name'].' '.$res2['last_name'];
        }
        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }


    // Ajax actions for ExternalExaminar of programme module
    public function ajaxProgrammeNameAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        
        $sql       = "SELECT id, programme_name FROM programmes where organisation_id='$parentValue'";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['programme_name'];
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }


   //ajax actions for programme module
    public function ajaxAssessmentTypeAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        
        //need to make sure that assessment is not semester exam
        //SELECT *  FROM `assessment_component_types` WHERE `assessment_component_type` NOT IN ('5')        
        $sql       = "SELECT `t1`.*, `t2`.`academic_modules_allocation_id` AS `academic_modules_allocation_id`, `t3`.`assessment_component_type` FROM `academic_assessment` AS `t1` INNER JOIN `assessment_component` AS `t2` ON `t1`.`assessment_component_id` = `t2`.`id` INNER JOIN `assessment_component_types` as `t3` ON `t2`.`assessment_component_types_id` = `t3`.`id` WHERE `t2`.`academic_modules_allocation_id` ='$parentValue' AND `t3`.`assessment_component_type` NOT LIKE 'Semester Exams%'";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Please Select Assessment Type";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['assessment'];
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }
	
	 public function ajaxAssessmentSectionAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
               
        $sql       = "SELECT `t1`.*, `t2`.`academic_modules_allocation_id` AS `academic_modules_allocation_id` FROM `student_section` AS `t1` INNER JOIN `academic_module_tutors` AS `t2` ON `t1`.`id` = `t2`.`section` WHERE `t2`.`academic_modules_allocation_id` ='$parentValue'";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Please Select Section";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['section'];
        }
		
        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }
	
	//SEMESTER EXAMS
    public function ajaxSemesterAssessmentTypeAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        
        //need to make sure that assessment is not semester exam
        //SELECT *  FROM `assessment_component_types` WHERE `assessment_component_type` NOT IN ('5')        
        $sql       = "SELECT `t1`.*, `t2`.`academic_modules_allocation_id` AS `academic_modules_allocation_id`, `t3`.`assessment_component_type` FROM `academic_assessment` AS `t1` INNER JOIN `assessment_component` AS `t2` ON `t1`.`assessment_component_id` = `t2`.`id` INNER JOIN `assessment_component_types` as `t3` ON `t2`.`assessment_component_types_id` = `t3`.`id` WHERE `t2`.`academic_modules_allocation_id` ='$parentValue' AND `t3`.`assessment_component_type` LIKE 'Semester Exams%'";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Please Select Assessment Type";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['assessment'];
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }
	
	 public function ajaxSemesterAssessmentSectionAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
               
        $sql       = "SELECT `t1`.*, `t2`.`academic_modules_allocation_id` AS `academic_modules_allocation_id` FROM `student_section` AS `t1` INNER JOIN `academic_module_tutors` AS `t2` ON `t1`.`id` = `t2`.`section` WHERE `t2`.`academic_modules_allocation_id` ='$parentValue'";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Please Select Section";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['section'];
        }
		
        return new JsonModel([
            'data' => $selectTwoData
        ]);
    } 
    
    public function ajaxAssessmentTypeForEditingAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
                
        $sql       = "SELECT `t1`.*, `t2`.`academic_modules_allocation_id` AS `academic_modules_allocation_id` FROM `academic_assessment` AS `t1` INNER JOIN `assessment_component` AS `t2` ON `t1`.`assessment_component_id` = `t2`.`id` WHERE `t2`.`academic_modules_allocation_id` ='$parentValue'";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Please Select Assessment Type";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['assessment'];
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);
    } 
	
	 public function ajaxAssessmentSectionForEditingAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
               
        $sql       = "SELECT `t1`.*, `t2`.`academic_modules_allocation_id` AS `academic_modules_allocation_id` FROM `student_section` AS `t1` INNER JOIN `academic_module_tutors` AS `t2` ON `t1`.`id` = `t2`.`section` WHERE `t2`.`academic_modules_allocation_id` ='$parentValue'";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Please Select Section";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['section'];
        }
		
        return new JsonModel([
            'data' => $selectTwoData
        ]);
    } 
    /*
    public function ajaxAssignAssessmentAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        
        $sql       = "SELECT id, assessment FROM assessment_component where academic_modules_allocation_id='$parentValue'";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Please Select Assessment Type";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['assessment'];
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }
    */
    public function ajaxSemesterYearAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = "SELECT id, semester FROM student_semester where programme_year_id='$parentValue'";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Select a Semester";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['semester'];
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }
    
    public function ajaxModuleNameForAssessmentAction()
    {
        $parentValue = $_POST['value'];
		$present_date = date('Y-m-d');
		
		$dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = "SELECT `t1`.`academic_year` AS `academic_year`, `t2`.`academic_event` AS `academic_event` FROM `academic_calendar` AS `t1` INNER JOIN `academic_calendar_events` AS `t2` ON `t1`.`academic_event` = `t2`.`id` WHERE  `t1`.`from_date` <= '$present_date'  AND `t1`.`to_date` >= '$present_date' AND `t2`.`organisation_id` = '$this->organisation_id'";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
		
		$academic_session = NULL;
		$academic_session_start = NULL;
		
		foreach($result as $set){
			if($set['academic_event'] == 'Start of Autumn Semester'){
				$academic_session = 'Autumn';
			}
			else if($set['academic_event'] == 'Start of Spring Semester'){
				$academic_session = 'Spring';
			}
		}
		
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql1       = "SELECT id, module_title, module_code FROM academic_modules where programmes_id='$parentValue'";
        $statement1 = $dbAdapter1->query($sql1);
        $result1    = $statement1->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Please Select a Module";
        foreach ($result1 as $res) {
            $selectTwoData[$res['id']] = $res['module_title'].' ('.$res['module_code'].')';
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }
	
	public function ajaxElectiveModuleNameAction()
    {
        $parentValue = $_POST['value'];
		$present_date = date('Y-m-d');
		
		$dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = "SELECT `t1`.`academic_year` AS `academic_year`, `t2`.`academic_event` AS `academic_event` FROM `academic_calendar` AS `t1` INNER JOIN `academic_calendar_events` AS `t2` ON `t1`.`academic_event` = `t2`.`id` WHERE  `t1`.`from_date` <= '$present_date'  AND `t1`.`to_date` >= '$present_date' AND `t2`.`organisation_id` = '$this->organisation_id'";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
		
		$academic_session = NULL;
		$academic_session_start = NULL;
		
		foreach($result as $set){
			if($set['academic_event'] == 'Start of Autumn Semester'){
				$academic_session = 'Autumn';
			}
			else if($set['academic_event'] == 'Start of Spring Semester'){
				$academic_session = 'Spring';
			}
		}
		
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql1		= "SELECT `t1`.`id`, `t1`.`module_title`, `t1`.`module_code`, `t2`.`module_type` AS `module_type` FROM `academic_modules_allocation` AS `t1` INNER JOIN `academic_modules` AS `t2` ON `t2`.`id` = `t1`.`academic_modules_id` WHERE `t2`.`module_type` = 'Elective' AND `t2`.`programmes_id`='$parentValue'";
		$statement1 = $dbAdapter1->query($sql1);
        $result1    = $statement1->execute();
        $selectTwoData = array();
        foreach ($result1 as $res) {
            $selectTwoData[$res['id']] = $res['module_title'].' ('.$res['module_code'].')';
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }
	
	public function ajaxElectiveSectionAction()
    {
        $parentValue = $_POST['value'];
		$semester = $this->getSemester($this->organisation_id);
		$academic_year = $this->getAcademicYear($semester);
        
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        
        $sql       = "SELECT DISTINCT `student_section_id`, `t1`.`module_code`, `t2`.`student_section_id` AS `student_section_id`, `t3`.* FROM `academic_modules_allocation` AS `t1` INNER JOIN `student_semester_registration` AS `t2` ON `t2`.`year_id` = `t1`.`year` INNER JOIN `student_section` AS `t3` ON `t2`.`student_section_id` = `t3`.`id` WHERE `t1`.`academic_session` = '$semester' AND `t1`.`academic_year` = '$academic_year' AND `t1`.`programmes_id`='$parentValue'";
		
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['section'];
        }
        
        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }
	
	public function ajaxModuleAttendanceRecordAction()
    {
        $parentValue = $_POST['value'];
		$present_date = date('Y-m-d');
		
		$dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = "SELECT `t1`.`academic_year` AS `academic_year`, `t2`.`academic_event` AS `academic_event` FROM `academic_calendar` AS `t1` INNER JOIN `academic_calendar_events` AS `t2` ON `t1`.`academic_event` = `t2`.`id` WHERE  `t1`.`from_date` <= '$present_date'  AND `t1`.`to_date` >= '$present_date' AND `t2`.`organisation_id` = '$this->organisation_id'";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
		
		$academic_session = NULL;
		$academic_session_start = NULL;
		
		foreach($result as $set){
			if($set['academic_event'] == 'Start of Autumn Semester'){
				$academic_session = 'Autumn';
			}
			else if($set['academic_event'] == 'Start of Spring Semester'){
				$academic_session = 'Spring';
			}
		}
		
		$academic_year = $this->getAcademicYear($academic_session);
		
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
		$sql1       = "SELECT `t1`.`id` AS `id`, `t2`.`programmes_id` AS `programmes_id`, `t2`.`module_code` AS `module_code`, `t2`.`module_title` AS `module_title` FROM `academic_modules_allocation` AS `t1` INNER JOIN `academic_modules` AS `t2` ON `t2`.`id` = `t1`.`academic_modules_id` WHERE t1.academic_year = '$academic_year' AND t2.programmes_id='$parentValue'";
        $statement1 = $dbAdapter1->query($sql1);
        $result1    = $statement1->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Please Select a Module";
        foreach ($result1 as $res) {
            $selectTwoData[$res['id']] = $res['module_title'].' ('.$res['module_code'].')';
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }


     public function ajaxModuleNameForYearAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        
        $sql       = "SELECT id, module_title, module_code FROM academic_modules where programmes_id='$parentValue'";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['module_title'].' ('.$res['module_code'].')';
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }


    //Ajax actions of student admission module
    public function ajaxStdRegisterProgrammeAction()
    {
        $parentValue = $_POST['value'];

        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
       
        $sql       = "SELECT id, programme_name FROM programmes where organisation_id='$parentValue'";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData = array('0' => 'Select Programme');
        //$selectTwoData['------>']="---------";
       foreach ($result as $res) {
        
            $selectTwoData[$res['id']] = $res['programme_name'];
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);  
    }


    //ajax for selecting dzongkhag, gewog and village
    
    public function ajaxStdGewogAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        
        $sql       = "SELECT * FROM `gewog` where `dzongkhag_id`= '$parentValue'";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Select a Gewog";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['gewog_name'];
        }
        
        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }
    
    public function ajaxStdVillageAction()
    {
        $parentValue1 = $_POST['value'];
        
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        
        $sql       = "SELECT `t1`.*, `t2`.`gewog_name` AS `gewog_name` FROM `village` AS `t1` INNER JOIN `gewog` AS `t2` ON `t1`.`gewog_id` = `t2`.`id` WHERE `t2`.`id`='$parentValue1'";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Select a Village";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['village_name'];
        }
        
        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }


     //ajax for selecting dzongkhag, gewog and village
    
    public function ajaxFatherGewogAction()
    {
        $parentValue2 = $_POST['value'];
        
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        
        $sql       = "SELECT * FROM `gewog` where `dzongkhag_id`= '$parentValue2'";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Select a Gewog";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['gewog_name'];
        }
        
        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }
    
    public function ajaxFatherVillageAction()
    {
         $parentValue3 = $_POST['value'];
        
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        
        $sql       = "SELECT `t1`.*, `t2`.`gewog_name` AS `gewog_name` FROM `village` AS `t1` INNER JOIN `gewog` AS `t2` ON `t1`.`gewog_id` = `t2`.`id` WHERE `t2`.`gewog_name` LIKE '$parentValue3'";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Select a Village";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['village_name'];
        }
        
        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }


    //ajax for selecting dzongkhag, gewog and village
    
    public function ajaxMotherGewogAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        
        $sql       = "SELECT * FROM `gewog` where `dzongkhag_id`= '$parentValue'";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Select a Gewog";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['gewog_name'];
        }
        
        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }
    
    public function ajaxMotherVillageAction()
    {
         $parentValue1 = $_POST['value'];
        
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        
        $sql       = "SELECT `t1`.*, `t2`.`gewog_name` AS `gewog_name` FROM `village` AS `t1` INNER JOIN `gewog` AS `t2` ON `t1`.`gewog_id` = `t2`.`id` WHERE `t2`.`gewog_name` LIKE '$parentValue1'";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Select a Village";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['village_name'];
        }
        
        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }


     
    /*
    * AJAX Actions
    */

    public function ajaxdatathreeAction()
    {
        $parentValue1 = $_POST['value'];
        
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
       
        $sql       = "SELECT details FROM user_acl where route='$parentValue1'";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectThreeData = array();

       foreach ($result as $res) {
            $selectThreeData[$res['details']] = $res['details'];
        }

        return new JsonModel([
            'data1' => $selectThreeData
        ]);
    }

    public function ajaxdataqueryAction()
    {
        $parentValue1 = $_POST['value'];
        
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
       
        $sql       = "SELECT details FROM user_acl";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectThreeData = array();

       foreach ($result as $res) {
            $selectThreeData[$res['details']] = $res['details'];
        }

        return new JsonModel([
            'data1' => $selectThreeData
        ]);
    }


    //ajax actions of student attendance module
    public function ajaxModuleByTutorAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        
        $sql       = "SELECT `t1`.`id` AS `id`, `t1`.`module_title` AS `module_title`, `t2`.`module_tutor` AS `module_tutor` FROM `academic_modules_allocation` AS `t1` INNER JOIN `academic_module_tutors` AS `t2` ON `t1`.`id` = `t2`.`academic_modules_allocation_id` WHERE `module_tutor` = '$this->username' AND t1.programmes_id='$parentValue'";
                
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData['------>']="---------";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['module_title'];
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }


     //Ajax actions of student admission module
    public function ajaxStdSuggCommitteStaffAction()
    {
        $parentValue = $_POST['value'];

        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
       
        $sql       = "SELECT id, first_name, middle_name, last_name, emp_id FROM employee_details where organisation_id='$parentValue'";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData = array('0' => 'Please Select Staff');
        //$selectTwoData['------>']="---------";
       foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['first_name'].' '.$res['middle_name'].' '.$res['last_name'].' ('.$res['emp_id'].')';
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);  
    }


    //ajax for selecting training type based on training category
    
    public function ajaxTrainingTypeAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        
        $sql       = "SELECT * FROM `training_type_details` where `training_types_id`= '$parentValue'";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Please Select Training Type";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['training_type_detail'];
        }
        
        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }
	
	
	//ajax for selecting training type based on training category
    
    public function ajaxProgrammeSemesterAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        
        $sql       = "SELECT * FROM `student_semester` where `programme_year_id`= '$parentValue'";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Please Select Semester";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['semester'];
        }
        
        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }
	
	//AJAX for Getting the list of modules by Module Tutor when adding timetable
	
	public function ajaxModuleTimetableAction()
    {
        $parentValue = $_POST['value'];
		
		$semester = $this->getSemester($this->organisation_id);
		$academic_year = $this->getAcademicYear($semester);
        
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        
        $sql       = "SELECT `t1`.`id` AS `id`, `t1`.`module_title` AS `module_title`, `t2`.`module_tutor` AS `module_tutor` FROM `academic_modules_allocation` AS `t1` INNER JOIN `academic_module_tutors` AS `t2` ON `t1`.`id` = `t2`.`academic_modules_allocation_id` WHERE `t1`.`academic_session` = '$semester' AND `t1`.`academic_year` = '$academic_year' AND `module_tutor` = '$this->username' AND t1.programmes_id='$parentValue'";
                
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Please Select Module";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['module_title'];
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }
	
	public function ajaxSectionTimetableAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        
        $sql       = "SELECT `t1`.`academic_modules_id` AS `academic_modules_id`, `t2`.`module_tutor` AS `module_tutor`, `t3`.`*`  FROM `academic_modules_allocation` AS `t1` INNER JOIN `academic_module_tutors` AS `t2` ON `t1`.`id` = `t2`.`academic_modules_allocation_id` INNER JOIN `student_section` AS `t3` ON `t2`.`section` = `t3`.`id` WHERE `module_tutor` = '$this->username' AND t1.id='$parentValue'";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['section'];
        }
        
        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }
	
	public function ajaxModuleAssessmentComponentAction()
    {
        $parentValue = $_POST['value'];
		$semester = $this->getSemester($this->organisation_id);
		$academic_year = $this->getAcademicYear($semester);
        
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');		
        
        $sql       = "SELECT `t1`.`id` AS `id`, `t1`.`module_title` AS `module_title`, `t1`.`module_code` AS `module_code`, `t2`.`module_coordinator` AS `module_coordinator` FROM `academic_modules_allocation` AS `t1` INNER JOIN `academic_module_coordinators` AS `t2` ON `t1`.`academic_modules_id` = `t2`.`academic_modules_id` WHERE `module_coordinator` = '$this->username' AND t1.academic_year='$academic_year'  AND t1.academic_session='$semester' AND t1.programmes_id='$parentValue'";
		/*
		$sql       = "SELECT `t1`.`id` AS `id`, `t1`.`module_title` AS `module_title`, `t1`.`module_code` AS `module_code`, `t2`.`module_tutor` AS `module_tutor` FROM `academic_modules_allocation` AS `t1` INNER JOIN `academic_module_tutors` AS `t2` ON `t1`.`id` = `t2`.`academic_modules_allocation_id` WHERE `module_tutor` = '$this->username' AND `academic_session` = '$semester' AND t1.academic_year='$academic_year' AND t1.programmes_id='$parentValue'"; */
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Please Select Academic Module";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['module_title'].' ('.$res['module_code'].')';
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }
	
	public function ajaxAssignAssessmentAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        
        $sql       = "SELECT id, assessment FROM assessment_component where academic_modules_allocation_id='$parentValue'";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Please Select Assessment Type";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['assessment'];
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }
	
	/*
	 * Get the year based on the Programme for Moderation
	 * Do not list the years that have already been moderated
	 */
	
	public function ajaxExamModerationAction()
	{
		$parentValue = $_POST['value'];
        
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
		
		$semester = $this->getSemester($this->organisation_id);
		$academic_year = $this->getAcademicYear($semester);
        
        $sql       = "SELECT MAX(programme_duration) As MAX FROM `programmes` WHERE id='$parentValue'";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $max_duration = NULL;
		$years = array();
        foreach ($result as $res) {
            $max_duration = $res['MAX'];
        }
		
		for($i=1; $i<=$max_duration; $i++){
				$years[$i] = $i ." Year";
		}
		
		//Get the years that have already been moderated and remove from "years array"
		$sql2       = "SELECT * FROM `exam_moderation` WHERE `academic_year` = '$academic_year'  AND `semester` = '$semester' AND programmes_id='$parentValue'";
        $statement2 = $dbAdapter1->query($sql2);
        $result2    = $statement2->execute();
		
		foreach ($result2 as $res) {
			unset($years[$res['year']]);
        }

        return new JsonModel([
            'data' => $years
        ]);
	}
	
	/*
	 * Get the year based on the Programme for Back Paper Generation
	 * Do not list the years that have already been generated
	 */
	
	public function ajaxBackPaperGenerationAction()
	{
		$parentValue = $_POST['value'];
        
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
		
		$semester = $this->getSemester($this->organisation_id);
		$academic_year = $this->getAcademicYear($semester);
        
        $sql       = "SELECT MAX(programme_duration) As MAX FROM `programmes` WHERE id='$parentValue'";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $max_duration = NULL;
		$years = array();
        foreach ($result as $res) {
            $max_duration = $res['MAX'];
        }
		
		for($i=1; $i<=$max_duration; $i++){
				$years[$i] = $i ." Year";
		}
		
		//Get the years that have already been moderated and remove from "years array"
		$sql2       = "SELECT * FROM `backpaper_list_generation` WHERE `academic_year` = '$academic_year'  AND `semester` = '$semester' AND programmes_id='$parentValue'";
        $statement2 = $dbAdapter1->query($sql2);
        $result2    = $statement2->execute();
		
		foreach ($result2 as $res) {
			unset($years[$res['year']]);
        }

        return new JsonModel([
            'data' => $years
        ]);
	}
	
	/*
	 * Get the year based on the Programme for Generation of Attendance record
	 * Do not list the years that have already been generated
	 */
	
	public function ajaxStudentAttendanceGenerationAction()
	{
		$parentValue = $_POST['value'];
        
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
		
		$semester = $this->getSemester($this->organisation_id);
		$academic_year = $this->getAcademicYear($semester);
        
        $sql       = "SELECT MAX(programme_duration) As MAX FROM `programmes` WHERE id='$parentValue'";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $max_duration = NULL;
		$years = array();
        foreach ($result as $res) {
            $max_duration = $res['MAX'];
        }
		
		for($i=1; $i<=$max_duration; $i++){
				$years[$i] = $i ." Year";
		}
		
		//Get the years that have already been moderated and remove from "years array"
		$sql2       = "SELECT * FROM `student_consolidated_attendance_generation` WHERE `academic_year` = '$academic_year'  AND `semester` = '$semester' AND programmes_id='$parentValue'";
        $statement2 = $dbAdapter1->query($sql2);
        $result2    = $statement2->execute();
		
		foreach ($result2 as $res) {
			unset($years[$res['year']]);
        }

        return new JsonModel([
            'data' => $years
        ]);
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
	
	/*
	 * Get the semester from the database
	 */
	
	public function getSemester($organisation_id)
	{
		$present_date = date('Y-m-d');
		$dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = "SELECT `t1`.`academic_year` AS `academic_year`, `t2`.`academic_event` AS `academic_event` FROM `academic_calendar` AS `t1` INNER JOIN `academic_calendar_events` AS `t2` ON `t1`.`academic_event` = `t2`.`id` WHERE  `t1`.`from_date` <= '$present_date'  AND `t1`.`to_date` >= '$present_date' AND `t2`.`organisation_id` = '$this->organisation_id'";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
		
		$semester = NULL;
		
		foreach($result as $set){
			if($set['academic_event'] == 'Start of Autumn Semester'){
				$semester = 'Autumn';
			}
			else if($set['academic_event'] == 'Start of Spring Semester'){
				$semester = 'Spring';
			}
		}
		return $semester;
	}


	//ajax for selecting country, dzongkhag, gewog and village

    public function ajaxStudentDzongkhagAction()
    {
        $parentValue = $_POST['value'];

        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');

        $sql       = "SELECT * FROM `dzongkhag` where `country_id`= '$parentValue'";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Select a Dzongkhag";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['dzongkhag_name'];
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }

    public function ajaxStudentGewogAction()
    {
        $parentValue = $_POST['value'];

        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');

        $sql       = "SELECT * FROM `gewog` where `dzongkhag_id`= '$parentValue'";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Select a Gewog";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['gewog_name'];
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }

    public function ajaxStudentVillageAction()
    {
        $parentValue = $_POST['value'];

        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');

        $sql       = "SELECT `t1`.*, `t2`.`gewog_name` AS `gewog_name` FROM `village` AS `t1` INNER JOIN `gewog` AS `t2` ON `t1`.`gewog_id` = `t2`.`id` WHERE `t2`.`id`='$parentValue'";

        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Select a Village";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['village_name'];
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }
    
	
	/*
	* Get Array of Semester Numbers given a programme and academic session
	* 1- Jan Session 2- July Session
	*/
	
	private function getSemesterArray($academic_session, $programmes_id)
	{
		$semesters = array();
		$academic_session_start = NULL;
		
		$present_date = date('Y-m-d');
		$dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = "SELECT `t1`.`*`, `t2`.`academic_session_id` AS `academic_session_id` FROM `academic_session` AS `t1` INNER JOIN `programmes` AS `t2` ON `t1`.`id` = `t2`.`academic_session_id` WHERE  `t2`.`organisation_id` = '$this->organisation_id'";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
		
		foreach($result as $set){
			$academic_session_start = $set['id'];
		}
		
		if($academic_session == 'Spring' && $academic_session_start== 1){
			$semesters = array(1,3,5,7,9);
		} else if($academic_session == 'Spring' && $academic_session_start== 2){
			$semesters = array(2,4,6,8,10);
		} else if($academic_session == 'Autumn' && $academic_session_start== 1){
			$semesters = array(2,4,6,8,10);
		} else {
			$semesters = array(1,3,5,7,9);
		}
		return $semesters;
	}
	
	/*
	 * Get the academic year based on the semester from the database
	 */
	
	public function getAcademicYear($semester_type)
	{
		$academic_year = NULL;
		
		if($semester_type == 'Autumn'){
			$academic_year = date('Y').'-'.(date('Y')+1);
		} else {
			$academic_year = (date('Y')-1).'-'.date('Y');
		}
		
		return $academic_year;
	}


	public function ajaxSemesterAssessmentModuleAction()
    {
        $parentValue = $_POST['value'];

        $semester = $this->getSemester($this->organisation_id);
		$academic_year = $this->getAcademicYear($semester);

        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');

        $sql       = "SELECT * FROM `academic_modules_allocation` where `programmes_id`= '$parentValue' AND `academic_session`='$semester' AND `academic_year`='$academic_year'";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Select a Module";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['module_title'].' ('.$res['module_code'].')';
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }

    public function ajaxSemesterAssessmentComponentAction()
    {
        $parentValue = $_POST['value'];

        $semester = $this->getSemester($this->organisation_id);
		$academic_year = $this->getAcademicYear($semester);

        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');

       /* $sql       = "SELECT `t1`.`*`, `t2`.`academic_session` AS `academic_session`, `t2`.`academic_year` AS `academic_year` FROM `assessment_component` AS `t1` INNER JOIN `academic_modules_allocation` AS `t2` ON `t2`.`id` = `t1`.`academic_modules_allocation_id` WHERE  `t1`.`academic_modules_allocation_id` = '$parentValue' AND `t2`.`academic_session`='$semester' AND `t2`.`academic_year`='$academic_year'";
	*/
        $sql = "SELECT * FROM `assessment_component` where `academic_modules_allocation_id`= '$parentValue'";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData[0]="Select a Module";
        foreach ($result as $res) {
            $selectTwoData[$res['id']] = $res['assessment'];
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);
    }
    
    
}

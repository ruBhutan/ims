<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Reports\Controller;

use Zend\ServiceManager\ServiceLocatorInterface;
use Reports\Service\ReportsServiceInterface;
use Reports\Model\Reports;
use Reports\Form\HrReportForm;
use Reports\Form\HrAdministrationReportForm;
use Reports\Form\HrLifeCycleReportForm;
use Reports\Form\HrPlanningReportForm;
use Reports\Form\HrTrainingReportForm;
use Reports\Form\StudentReportForm;
use Reports\Form\StudentFeedbackReportForm;
use Reports\Form\AcademicReportForm;
use Reports\Form\AcademicResultReportForm;
use Reports\Form\ResearchReportForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
//RBACL
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManage;
use Zend\Db\Sql\Expression;

use DOMPDFModule\View\Model\PdfModel;

/**
 * Description of IndexController
 *
 * @author Mendrel
 */
class ReportsController extends AbstractActionController
{
	protected $reportService;
	protected $serviceLocator;
	protected $username;
	protected $userrole;
	protected $usertype;
	protected $userregion;
	protected $userDetails;
    protected $employee_details_id;
	protected $organisation_id;
	
	public function __construct(ReportsServiceInterface $reportService, $serviceLocator)
	{
		$this->reportService = $reportService;
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
		
		$empData = $this->reportService->getUserDetailsId($this->username, $tableName = 'employee_details');
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			}
			
		//get the organisation id
		$organisationID = $this->reportService->getOrganisationId($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}

		//get the user details such as name
		//get the user details such as name
		$this->userDetails = $this->reportService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->reportService->getUserImage($this->username, $this->usertype);          
	}


	public function loginDetails()
	{
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
	}
	
	//HR Category Reports
	public function hrPlanningReportsAction()
	{
		$this->loginDetails();
		$form = new HrReportForm();
		$hr_report = NULL;
		$organisationList = $this->getOrganisationArrayList();
		$positionCategory = $this->reportService->listSelectData('position_category','category', $this->organisation_id);
		$positionLevel = $this->reportService->listSelectData('position_level','position_level', $this->organisation_id);
		$occupationalGroup = $this->reportService->listSelectData('major_occupational_group', 'major_occupational_group', $this->organisation_id);
		$fiveYearPlanList = $this->reportService->listSelectData('five_year_plan', 'five_year_plan', $this->organisation_id);
		$five_year_plan = array();
		$report_details = array();
			
		$report_list = array(
				'hrd_planning' => 'HRD Planning',
				'hrm_planning' => 'HRM Planning'
			);
	
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid()) {
				$report_details = $this->params()->fromPost();
				$five_year = $report_details['five_year_plan'];
				$five_year_plan = $this->reportService->getFiveYearPlan($five_year);
				try {
					$hr_report = $this->reportService->getHrReport($report_details, $this->organisation_id);
					}
				catch(\Exception $e) {
					 die($e->getMessage());
					 // Some DB Error happened, log it and let the user know
				 }
			 }
		 }
	 
		return new ViewModel(array(
			'form' => $form,
			'hr_report' => $hr_report,
			'report_list' => $report_list,
			'organisationList' => $organisationList,
			'five_year_plan' => $five_year_plan,
			'fiveYearPlanList' => $fiveYearPlanList,
			'positionCategory' => $positionCategory,
			'positionLevel' => $positionLevel,
			'occupationalGroup' => $occupationalGroup,
			'report_details' => $report_details
			));
	}
	
	public function hrRecruitmentReportsAction()
	{
		$this->loginDetails();
		$form = new HrReportForm();
		$hr_report = NULL;
		$organisationList = $this->getOrganisationArrayList();
		$positionCategory = $this->reportService->listSelectData('position_category','category', $this->organisation_id);
		$positionLevel = $this->reportService->listSelectData('position_level','position_level', $this->organisation_id);
		$occupationalGroup = $this->reportService->listSelectData('major_occupational_group', 'major_occupational_group', $this->organisation_id);
		$five_year_plan = array();
		$fiveYearPlanList = $this->reportService->listSelectData('five_year_plan', 'five_year_plan', $this->organisation_id);
		$report_details = array();
			
		$report_list = array(
				'recruitment_position_level' => 'Recruitment By Position Level'
			);
	
		$request = $this->getRequest();
		 if ($request->isPost()) {
			 $form->setData($request->getPost());
			 if ($form->isValid()) {
				 $report_details = $this->params()->fromPost();
				 $five_year = $report_details['five_year_plan'];
				 $five_year_plan = $this->reportService->getFiveYearPlan($five_year);
				 try {
					 $hr_report = $this->reportService->getHrReport($report_details, $this->organisation_id);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
			 }
		 }
		 
		return new ViewModel(array(
			'form' => $form,
			'hr_report' => $hr_report,
						'report_list' => $report_list,
			'organisationList' => $organisationList,
			'five_year_plan' => $five_year_plan,
						'fiveYearPlanList' => $fiveYearPlanList,
			'positionCategory' => $positionCategory,
			'positionLevel' => $positionLevel,
			'occupationalGroup' => $occupationalGroup,
			'report_details' => $report_details
			));
	}
	
	public function hrAdministrationReportsAction()
	{ 
		$this->loginDetails();

		$form = new HrAdministrationReportForm();
		$hr_report = NULL;
		$organisationList = $this->getOrganisationArrayList();
		$positionCategory = $this->reportService->listSelectData('position_category','category', $this->organisation_id);
		$positionLevel = $this->reportService->listSelectData('position_level','position_level', $this->organisation_id);
		$occupationalGroup = $this->reportService->listSelectData('major_occupational_group', 'major_occupational_group', $this->organisation_id);
		$five_year_plan = array();
		$fiveYearPlanList = $this->reportService->listSelectData('five_year_plan', 'five_year_plan', $this->organisation_id);
		$report_details = array();
			
		$report_list = array(
				'staff_leave' => 'Staff On Leave',
				'staff_pending_leave' => 'Staff Pending Leave',
				'staff_tour' => 'Staff On Tour',
				'staff_training' => 'Staff On Training/Studies',
				'staff_leave_encashment' => 'Staff Taken Leave Encashment',
				//if needed, then uncomment
				//'staff_overall' => 'Staff On Leave, Training etc.'
			);
	
		$request = $this->getRequest();
		 if ($request->isPost()) {
			 $form->setData($request->getPost());
			 if ($form->isValid()) {
				 $report_details = $this->params()->fromPost();
				 $report_details['organisation'] = $this->organisation_id;
				 $five_year = $report_details['five_year_plan'];
				 $five_year_plan = $this->reportService->getFiveYearPlan($five_year);
				 try {
					 $hr_report = $this->reportService->getHrReport($report_details, $this->organisation_id);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
			 }
		 }
		 
		return new ViewModel(array(
			'form' => $form,
			'hr_report' => $hr_report,
			'report_list' => $report_list,
			'organisationList' => $organisationList,
			'five_year_plan' => $five_year_plan,
			'fiveYearPlanList' => $fiveYearPlanList,
			'positionCategory' => $positionCategory,
			'positionLevel' => $positionLevel,
			'occupationalGroup' => $occupationalGroup,
			'report_details' => $report_details
			));
	}
	
	public function hrDevelopmentReportsAction()
	{
		$this->loginDetails();
		$form = new HrReportForm();
		$hr_report = NULL;
		$organisationList = $this->getOrganisationArrayList();
		$positionCategory = $this->reportService->listSelectData('position_category','category', $this->organisation_id);
		$positionLevel = $this->reportService->listSelectData('position_level','position_level', $this->organisation_id);
		$occupationalGroup = $this->reportService->listSelectData('major_occupational_group', 'major_occupational_group', $this->organisation_id);
		$five_year_plan = array();
		$fiveYearPlanList = $this->reportService->listSelectData('five_year_plan', 'five_year_plan', $this->organisation_id);
		$report_details = array();
			
		$report_list = array(
				'five_year_implementation' => 'Progress of FYP Implementation',
				'training_implementation_category' => 'Training Implementation by Category',
				'training_implementation_country' => 'Training Implementation by Country',
				'training_implementation_funding' => 'Training Implementation by Source of Funding'
			);
	
		$request = $this->getRequest();
		 if ($request->isPost()) {
			 $form->setData($request->getPost());
			 if ($form->isValid()) {
				 $report_details = $this->params()->fromPost(); 
				 $five_year = $report_details['five_year_plan'];
				 $five_year_plan = $this->reportService->getFiveYearPlan($five_year);
				 try {
					 $hr_report = $this->reportService->getHrReport($report_details, $this->organisation_id);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
			 }
		 }
		 
		return new ViewModel(array(
			'form' => $form,
			'hr_report' => $hr_report,
			'report_list' => $report_list,
			'organisationList' => $organisationList,
			'five_year_plan' => $five_year_plan,
			'fiveYearPlanList' => $fiveYearPlanList,
			'positionCategory' => $positionCategory,
			'positionLevel' => $positionLevel,
			'occupationalGroup' => $occupationalGroup,
			'report_details' => $report_details
			));
	}
        
	public function hrCategoryReportsAction()
	{
		$this->loginDetails();
		$form = new HrReportForm();
		$hr_report = NULL;
		$organisationList = $this->getOrganisationArrayList();
		$positionCategory = $this->reportService->listSelectData('position_category','category', $this->organisation_id);
		$positionLevel = $this->reportService->listSelectData('position_level','position_level', $this->organisation_id);
		$occupationalGroup = $this->reportService->listSelectData('major_occupational_group', 'major_occupational_group', $this->organisation_id);
		$five_year_plan = array();
        $fiveYearPlanList = $this->reportService->listSelectData('five_year_plan', 'five_year_plan', $this->organisation_id);
		$report_type = NULL;
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 $report_details = $this->params()->fromPost(); 
                 $five_year = $report_details['five_year_plan'];
                 $five_year_plan = $this->reportService->getFiveYearPlan($five_year);
				 try {
					 $hr_report = $this->reportService->getHrReport($report_details, $this->organisation_id);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
		return new ViewModel(array(
			'form' => $form,
			'hr_report' => $hr_report,
			'organisationList' => $organisationList,
			'five_year_plan' => $five_year_plan,
            'fiveYearPlanList' => $fiveYearPlanList,
			'positionCategory' => $positionCategory,
			'positionLevel' => $positionLevel,
			'occupationalGroup' => $occupationalGroup,
			'report_type' => $report_type
			));
		
	}
	
	//generate pdf of the reports
	public function generateHrReportsAction()
	{
		$this->loginDetails();
		//get the param from the type of report
		$reporttype = $this->params()->fromRoute('reporttype',0);
        $pdf = new PdfModel();
        $pdf->setOption('fileName', 'RUB HR Reports'); // Triggers PDF download, automatically appends ".pdf"
        $pdf->setOption('display', PdfModel::DISPLAY_ATTACHMENT);
        $pdf->setOption('paperSize', 'a4'); // Defaults to "8x11"
        $pdf->setOption('paperOrientation', 'landscape'); // Defaults to "portrait"

        //To set view variables
        $pdf->setVariables(array(
           'hr_report' => $this->reportService->getHrReport($reporttype, $this->organisation_id),
		   'studyLevel' => $this->reportService->listSelectData('study_level','study_level', $this->organisation_id),
		   'organisationList' => $this->reportService->listSelectData('organisation','organisation_name', $this->organisation_id),
       ));

        return $pdf;
	}
	
	//HR Life Cycle Report
	public function hrLifeCycleReportsAction()
	{
		$this->loginDetails();
		$form = new HrLifeCycleReportForm();
		$hr_report = NULL;
		$organisationList = $this->getOrganisationArrayList();
                
		$positionCategory = $this->reportService->listSelectData('position_category','category', $this->organisation_id);
		$positionLevel = $this->reportService->listSelectData('position_level','position_level', $this->organisation_id);
		$occupationalGroup = $this->reportService->listSelectData('major_occupational_group', 'major_occupational_group', $this->organisation_id);
		$five_year_plan = array();
		$fiveYearPlanList = $this->reportService->listSelectData('five_year_plan', 'five_year_plan', $this->organisation_id);
		$report_details = array(); 
                
		$report_list = array(
				'separation_agencies_position' => 'Separation By Agencies and Position Level',
				'recruitment_separation' => 'Recruitment and Separation Record',
				//'staff_transfer_details' => 'Staff Transfer Details',
				//'staff_promotion_details' => 'Staff Promotion Details',
				//'staff_separation_details' => 'Staff Separation Details',
				'staff_apa_details' => 'Staff Performance Appraisal Details',
				'staff_by_dzongkhag' => 'Staff By Dzongkhag',
				'staff_by_nationality' => 'Staff By Nationality',
				'staff_by_religion' => 'Staff By Religion',
				'staff_by_gender' => 'Staff By Gender',
				'staff_by_organisation' => 'Staff By Organisation',
				'staff_by_department' => 'Staff By Department',
				'staff_by_employeetype' => 'Staff By Employee Type',
				'staff_by_section' => 'Staff By Division/Section',
				'staff_by_maritialstatus' => 'Staff By Maritial Status',
				'staff_by_positiontitle' => 'Staff By Position Title',
				'staff_by_positionlevel' => 'Staff By Position Level',
				'staff_by_bloodgroup' => 'Staff By Blood Group',
			);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 $report_details = $this->params()->fromPost(); 
                 $five_year = $report_details['five_year_plan'];
                 $five_year_plan = $this->reportService->getFiveYearPlan($five_year);
				 try {
					 $hr_report = $this->reportService->getHrReport($report_details, $this->organisation_id);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
		return new ViewModel(array(
			'form' => $form,
			'hr_report' => $hr_report,
            'report_list' => $report_list,
			'organisationList' => $organisationList,
			'five_year_plan' => $five_year_plan,
            'fiveYearPlanList' => $fiveYearPlanList,
			'positionCategory' => $positionCategory,
			'positionLevel' => $positionLevel,
			'occupationalGroup' => $occupationalGroup,
			'report_details' => $report_details
			));
		
	}
	
	//HR Training Report
	public function hrTrainingReportsAction()
	{
		$this->loginDetails();
		$form = new HrTrainingReportForm();
		$hr_report = NULL;
		$organisationList = $this->reportService->listSelectData('organisation','organisation_name', $this->organisation_id);
		$positionCategory = $this->reportService->listSelectData('position_category','category', $this->organisation_id);
		$positionLevel = $this->reportService->listSelectData('position_level','position_level', $this->organisation_id);
		$five_year_plan = array();
		$report_type = NULL;
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 $report_details = $this->getRequest()->getPost('report_type');
                 $five_year = $report_details['five_year_plan'];
                 $five_year_plan = $this->reportService->getFiveYearPlan($five_year);
				 try {
					 $hr_report = $this->reportService->getHrReport($report_type, $this->organisation_id);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
		return new ViewModel(array(
			'form' => $form,
			'hr_report' => $hr_report,
			'organisationList' => $organisationList,
			'five_year_plan' => $five_year_plan,
			'positionCategory' => $positionCategory,
			'positionLevel' => $positionLevel,
			'report_type' => $report_type
			));
		
	}
        //HR Training Report
	public function generateHrTrainingReportsAction()
	{
		$this->loginDetails();
		$form = new HrTrainingReportForm();
		$hr_report = NULL;
		$organisationList = $this->reportService->listSelectData('organisation','organisation_name', $this->organisation_id);
		$positionCategory = $this->reportService->listSelectData('position_category','category', $this->organisation_id);
		$positionLevel = $this->reportService->listSelectData('position_level','position_level', $this->organisation_id);
		$five_year_plan = array();
		$report_type = NULL;
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 $report_details = $this->getRequest()->getPost('report_type');
                 $five_year = $report_details['five_year_plan'];
                 $five_year_plan = $this->reportService->getFiveYearPlan($five_year);
				 try {
					 $hr_report = $this->reportService->getHrReport($report_type, $this->organisation_id);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
		return new ViewModel(array(
			'form' => $form,
			'hr_report' => $hr_report,
			'organisationList' => $organisationList,
			'five_year_plan' => $five_year_plan,
			'positionCategory' => $positionCategory,
			'positionLevel' => $positionLevel,
			'report_type' => $report_type
			));
		
	}
    
	//Student Feedback Reports
	public function studentFeedbackReportsAction()
	{
		$this->loginDetails();
        $form = new StudentFeedbackReportForm();
        $student_report = NULL;
        $organisationList = $this->reportService->listSelectData('organisation','organisation_name', $this->organisation_id);
        $report_type = NULL;

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $report_type = $this->getRequest()->getPost('report_type');
                try {
                        $student_report = $this->reportService->getStudentFeedbackReport($report_type, $this->organisation_id);
                }
                catch(\Exception $e) {
                                die($e->getMessage());
                                // Some DB Error happened, log it and let the user know
                }
            }
        }
		 
		return new ViewModel(array(
			'form' => $form,
			'student_report' => $student_report,
			'organisationList' => $organisationList,
                        'organisation_id' => $this->organisation_id,
			'report_type' => $report_type
			));
		
	}
        
	//Student Reports
	public function studentReportsAction()
	{
		$this->loginDetails();
		$form = new StudentReportForm();
		$student_report = NULL;
		$organisationList = $this->getCollegeArrayList();
		$year = array_combine(range(date('Y'),2012), range(date('Y'),2012));
		$report_details = array();
		$report_list = array(
				'student_intake_report_scholarship' => 'Student In-Take Report (By Scholarship Type)',
				'student_intake_report_college' => 'Student In-Take Report (By College)',
				'student_by_programme_incampus' => 'Student By Programme (Current in Campus)',
				'student_by_programme_enrolled' => 'Student By Programme (Enrolled)',
				'student_by_programme_offcampus' => 'Student By Programme (Off Campus)',
				'grudated_students' => 'Total Graduated Students',
				'suspended_students' => 'Total Suspended Students',
				'terminated_students' => 'Total Terminated Students',
				'withdrawn_students' => 'Total Withdrawn Students',
				'student_by_dzongkhag' => 'Student By Dzongkhag',
				'student_by_nationality' => 'Student By Nationality',
				'student_by_religion' => 'Student By Religion',
				'student_by_gender' => 'Student By Gender',
				'student_by_bloodgroup' => 'Student By Blood Group',
			);

		$enrollment_year = NULL;
	
		$request = $this->getRequest();
			if ($request->isPost()) {
				$form->setData($request->getPost());
				if ($form->isValid()) {
					$report_details = $this->params()->fromPost();
					$enrollment_year = $report_details['year'];
						try {
								$student_report = $this->reportService->getStudentReport($report_details);
						}
						catch(\Exception $e) {
										die($e->getMessage());
										// Some DB Error happened, log it and let the user know
						}
				}
			}
	 
		return new ViewModel(array(
			'form' => $form,
			'student_report' => $student_report,
			'organisationList' => $organisationList,
			'year' => $year,
			'report_list' => $report_list,
			'report_details' => $report_details,
			'enrollment_year' => $enrollment_year,
			));
	}
	
	//generate pdf of the student reports
	public function generateStudentReportsAction()
	{
		$this->loginDetails();
		//get the param from the type of report
		$reporttype = $this->params()->fromRoute('reporttype',0);
        $pdf = new PdfModel();
        $pdf->setOption('fileName', 'RUB Student Reports'); // Triggers PDF download, automatically appends ".pdf"
        $pdf->setOption('display', PdfModel::DISPLAY_ATTACHMENT);
        $pdf->setOption('paperSize', 'a4'); // Defaults to "8x11"
        $pdf->setOption('paperOrientation', 'landscape'); // Defaults to "portrait"

        //To set view variables
        $pdf->setVariables(array(
           'student_report' => $student_report = $this->reportService->getStudentReport($reporttype),
		   'studyLevel' => $this->reportService->listSelectData('study_level','study_level', $this->organisation_id),
		   'organisationList' => $this->reportService->listSelectData('organisation','organisation_name', $this->organisation_id),
       ));

        return $pdf;
	}
	
	//Academic Reports
	public function academicReportsAction()
	{
		$this->loginDetails();
		$form = new AcademicReportForm();
		$student_report = NULL;
		$organisationList = $this->reportService->listSelectData('organisation','organisation_name', $this->organisation_id);
		$report_type = NULL;
		
		$report_details = array();
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 $report_type['report_type'] = $this->getRequest()->getPost('report_type');
				 $report_type['organisation_id'] = $this->getRequest()->getPost('organisation_id');
				 try {
					 $report_details = $this->reportService->getAcademicReport($report_type);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
		return new ViewModel(array(
			'form' => $form,
			'student_report' => $student_report,
			'organisationList' => $organisationList,
			'report_type' => $report_type,
			'report_details' => $report_details
			));
		
	}
	
	//generate pdf of the academic reports
	public function generateAcademicReportsAction()
	{
		$this->loginDetails();
		//get the param from the type of report
		$reporttype = $this->params()->fromRoute('reporttype',0);
        $pdf = new PdfModel();
        $pdf->setOption('fileName', 'RUB Academic Reports'); // Triggers PDF download, automatically appends ".pdf"
        $pdf->setOption('display', PdfModel::DISPLAY_ATTACHMENT);
        $pdf->setOption('paperSize', 'a4'); // Defaults to "8x11"
        $pdf->setOption('paperOrientation', 'landscape'); // Defaults to "portrait"

        //To set view variables
        $pdf->setVariables(array(
           'student_report' => $student_report = $this->reportService->getStudentReport($reporttype),
		   'studyLevel' => $this->reportService->listSelectData('study_level','study_level', $this->organisation_id),
		   'organisationList' => $this->reportService->listSelectData('organisation','organisation_name', $this->organisation_id),
       ));

        return $pdf;
	}


	public function academicResultsReportsAction()
	{
		$this->loginDetails();
		$form = new AcademicResultReportForm();
		$student_report = NULL;
		$organisationList = $this->reportService->listSelectData('organisation','organisation_name', $this->organisation_id);
		$section = $this->reportService->listSelectData('student_section', 'section', NULL);
		$programmeList = $this->reportService->listSelectData('programmes', 'programme_name', NULL);
		$report_type = NULL;
		
		$report_details = array();
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 $report_type['report_type'] = $this->getRequest()->getPost('report_type');
				 $report_type['organisation_id'] = $this->getRequest()->getPost('organisation_id');
				 $report_type['programmes_id'] = $this->getRequest()->getPost('programmes_id');
				 $report_type['section'] = $this->getRequest()->getPost('section');
				 try { 
					 $report_details = $this->reportService->getAcademicResultReport($report_type);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
		return new ViewModel(array(
			'form' => $form,
			'student_report' => $student_report,
			'organisationList' => $organisationList,
			'report_type' => $report_type,
			'report_details' => $report_details,
			'programmeList' => $programmeList,
			'section' => $section,
			));
	}
	
	//Research Reports
	public function researchReportsAction()
	{
		$this->loginDetails();

		$form = new ResearchReportForm();
		$research_report = NULL;
		$organisationList = $this->getOrganisationArrayList();
                
		$report_details = array(); 
                
		$report_list = array(
				'university_research_grant' => 'University Research Grant',
				'college_research_grant' => 'College Research Grant',
				'university_publication' => 'University Publication',
				'college_publication' => 'College Publication',
			);
		
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 $report_details = $this->params()->fromPost(); 
				 try {
					 $research_report = $this->reportService->getResearchReport($report_details, $this->organisation_id);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
		return new ViewModel(array(
			'form' => $form,
			'research_report' => $research_report,
            'report_list' => $report_list,
			'organisationList' => $organisationList,
			'report_details' => $report_details
			));
		
	}
	
	//generate pdf of the academic reports
	public function generateResearchReportsAction()
	{
		$this->loginDetails();
		//get the param from the type of report
		$reporttype = $this->params()->fromRoute('reporttype',0);
        $pdf = new PdfModel();
        $pdf->setOption('fileName', 'RUB Academic Reports'); // Triggers PDF download, automatically appends ".pdf"
        $pdf->setOption('display', PdfModel::DISPLAY_ATTACHMENT);
        $pdf->setOption('paperSize', 'a4'); // Defaults to "8x11"
        $pdf->setOption('paperOrientation', 'landscape'); // Defaults to "portrait"

        //To set view variables
        $pdf->setVariables(array(
           'student_report' => $student_report = $this->reportService->getStudentReport($reporttype),
		   'studyLevel' => $this->reportService->listSelectData('study_level','study_level', $this->organisation_id),
		   'organisationList' => $this->reportService->listSelectData('organisation','organisation_name', $this->organisation_id),
       ));

        return $pdf;
	}
        
	//get the organisation list for drop down
	//if OVC, then display all 
	//otherwise, only display college id
	public function getOrganisationArrayList()
	{
		$this->loginDetails();
		$organisation_array = array();
		
		$organisation = $this->organisation_id;
		$organisation_array_list = $this->reportService->listSelectData('organisation','organisation_name', $this->organisation_id);
		if($organisation != 1){
			foreach($organisation_array_list as $key=>$value){
				if($key != $organisation){
					unset($organisation_array_list[$key]);
				}
			}
			$organisation_array = $organisation_array_list;
		} else {
			//need to insert the all option for OVC
			$organisation_array = $organisation_array_list;
			array_unshift($organisation_array, "All Colleges/Agency");
		}
		
		return $organisation_array;
	}
	
	//get the organisation list for drop down
	//if OVC, then display all 
	//otherwise, only display college id
	public function getCollegeArrayList()
	{
		$this->loginDetails();
		$organisation_array = array();
		
		$organisation = $this->organisation_id;
		$organisation_array_list = $this->reportService->listSelectData('organisation','organisation_name', $this->organisation_id);
		if($organisation != 1){
			foreach($organisation_array_list as $key=>$value){
				if($key != $organisation){
					unset($organisation_array_list[$key]);
				}
			}
			$organisation_array = $organisation_array_list;
		} else {
			//remove OVC option as OVC does not have students
			unset($organisation_array_list['1']);
			//need to insert the all option for OVC
			$organisation_array = $organisation_array_list;
			$organisation_array['0'] = "All Colleges";
		}
		ksort($organisation_array);
		return $organisation_array;
	}
}

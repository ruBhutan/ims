<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace StudentReports\Controller;

use StudentReports\Service\StudentReportsServiceInterface;
use StudentReports\Model\StudentReports;
use StudentReports\Form\StudentReportForm;
use StudentReports\Form\StudentFeedbackReportForm;

use Zend\ServiceManager\ServiceLocatorInterface;
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
class StudentReportsController extends AbstractActionController
{
    
	protected $studentreportService;
	protected $serviceLocator;
	protected $username;
	protected $userrole;
	protected $usertype;
	protected $userregion;
	protected $userDetails;
    protected $employee_details_id;
	protected $organisation_id;
	
	public function __construct(StudentReportsServiceInterface $studentreportsService, $serviceLocator)
	{
		$this->studentreportsService = $studentreportsService;
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
		
		$empData = $this->studentreportsService->getUserDetailsId($this->username, $tableName = 'employee_details');
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			}
			
		//get the organisation id
		$organisationID = $this->studentreportsService->getOrganisationId($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}

		//get the user details such as name
		$this->userDetails = $this->studentreportsService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->studentreportsService->getUserImage($this->username, $this->usertype);          
	}

	public function loginDetails()
	{
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
	}
	
	//Student Feedback Reports
	public function studentFeedbackReportsAction()
	{
		$this->loginDetails();
        $form = new StudentFeedbackReportForm();
        $student_report = NULL;
        $organisationList = $this->studentreportsService->listSelectData('organisation','organisation_name', $this->organisation_id);
        $report_type = NULL;

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $report_type = $this->getRequest()->getPost('report_type');
                try {
                        $student_report = $this->studentreportsService->getStudentFeedbackReport($report_type, $this->organisation_id);
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
	public function overallStudentReportsAction()
	{
		$this->loginDetails();
		$form = new StudentReportForm();
		
		$student_report = NULL;
		$organisationList = $this->getCollegeArrayList();
		$year = NULL;
		//$year = array_combine(range(date('Y'),2012), range(date('Y'),2012));
		$report_details = array();
		$report_list = array(
				'overall_student_by_programme_inrub' => 'Overall Student in RUB by Programme',
				'currently_student_by_programme_incampus' => 'Currently Student in campus by Programme',
				'currently_student_by_programme_offcampus' => 'Currently Student off campus by Programme',
				'currently_suspended_students' => 'Currently Total Student Suspended',
				'overall_terminated_students' => 'Overall Total Terminated Students',
				'overall_withdrawn_students' => 'Overall Total Withdrawn Students',
				'student_by_dzongkhag' => 'Overall Student By Dzongkhag',
				'student_by_nationality' => 'Overall Student By Nationality',
				'student_by_religion' => 'Overall Student By Religion',
				'student_by_gender' => 'Overall Student By Gender',
				'student_by_bloodgroup' => 'Overall Student By Blood Group',
			);

		$enrollment_year = NULL;
		$request = $this->getRequest();
		if($request->isPost()) {
			$form->setData($request->getPost());
			//if ($form->isValid()) {
				$report_details = $this->params()->fromPost();
		
				try {
					$student_report = $this->studentreportsService->getStudentReport($report_details);
				}
				catch(\Exception $e) {
					die($e->getMessage());
					// Some DB Error happened, log it and let the user know
				}
			//}
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

	public function yearwiseStudentReportsAction()
	{
		
		$this->loginDetails();
		$form = new StudentReportForm();
		$student_report = NULL;
		$organisationList = $this->getCollegeArrayList();
		$year = array_combine(range(date('Y'),2012), range(date('Y'),2012));
		$report_details = array();
		$report_list = array(
				'student_intake_report_scholarship' => 'Year wise Student In-Take Report (By Scholarship Type)',
				'student_intake_report_college' => 'Year wise Student In-Take Report (By College)',
				'student_by_programme_enrolled' => 'Student By Programme (Enrolled)',
				'grudated_students' => 'Year wise Total Graduated Students',
			);

		$enrollment_year = NULL;
		$request = $this->getRequest();
			if ($request->isPost()) {
				$form->setData($request->getPost());
				if ($form->isValid()) {
					$report_details = $this->params()->fromPost();
					$enrollment_year = $report_details['year'];
						try {
								$student_report = $this->studentreportsService->getStudentReport($report_details);
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
           'student_report' => $student_report = $this->studentreportsService->getStudentReport($reporttype),
		   'studyLevel' => $this->studentreportsService->listSelectData('study_level','study_level', $this->organisation_id),
		   'organisationList' => $this->studentreportsService->listSelectData('organisation','organisation_name', $this->organisation_id),
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
		$organisation_array_list = $this->studentreportsService->listSelectData('organisation','organisation_name', $this->organisation_id);
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
		$organisation_array_list = $this->studentreportsService->listSelectData('organisation','organisation_name', $this->organisation_id);
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

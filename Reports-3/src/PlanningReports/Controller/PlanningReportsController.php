<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PlanningReports\Controller;


use PlanningReports\Service\PlanningReportsServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use PlanningReports\Form\PlanningReportsForm;
//use PlanningReports\Form\PlanningReportsCategoryForm;
//use PlanningReports\Form\SearchForm;
//use PlanningReports\Model\PlanningReports;
//use PlanningReports\Model\PlanningReportsCategory;
use Zend\Session\Container;
use Zend\Http\Response\Stream;
use Zend\Http\Headers;
use Zend\View\Model\JsonModel;
use DOMPDFModule\View\Model\PdfModel;

//AJAX
use Zend\Paginator\Adapter\DbSelect;
use Zend\Form\Element\Select;

/**
 * Description of IndexController
 *
 */
 
class PlanningReportsController extends AbstractActionController
{
	protected $planningreportsService;
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

	protected $keyphrase = "RUB_IMS";
	
	public function __construct(PlanningReportsServiceInterface $planningreportsService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->planningreportsService = $planningreportsService;
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
		
		$empData = $this->planningreportsService->getUserDetailsId($this->username);
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			}

		//get the organisation id
		$organisationID = $this->planningreportsService->getOrganisationId($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}

		//get the user details such as name
        $this->userDetails = $this->planningreportsService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->planningreportsService->getUserImage($this->username, $this->usertype);

	}

	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }

    public function planningReportsAction()
	{
		$this->loginDetails();

		$form = new PlanningReportsForm();

		$five_year = NULL;
		$objectiveWeight = NULL;
		$staffDetail = NULL;
		$keyAspiration = NULL;
		$successIndicator = NULL;
		$trendsuccessIndicator = NULL;
		$definitionsuccessIndicator = NULL;
		$requirementssuccessindicator = NULL;

		$organisationList = $this->getOrganisationArrayList();

		$five_year_plan = $this->planningreportsService->getFiveYearPlan();
		
		foreach($five_year_plan as $date){
			$this->from_date = $date['from_date'];
		}
		$from_date = $this->from_date;
		$from_date = date("Y", strtotime(substr($from_date,0,10)));
		$present_year = $from_date;

		$financial_year_list = array();
		for($i=0; $i<=4; $i++){
			$financial_year_list[($present_year+$i)."-".($present_year+$i+1)] = ($present_year+$i)."-".($present_year+$i+1);
		}
		
		$fiveYearPlanList = $this->planningreportsService->listSelectData('five_year_plan', 'five_year_plan', $this->organisation_id);

		$report_details = array();
			
		$report_list = array(
			'compiled_apa' => 'Compiled APA'
		);


		$request = $this->getRequest();

		if ($request->isPost()) {
			$form->setData($request->getPost());
			
			if ($form->isValid()) {
				$report_details = $this->params()->fromPost(); 

				$five_year = array();
				foreach($five_year_plan as $key){
						$five_year_id = $key['id'];
						$five_year = $this->planningreportsService->findFiveYearPlan($five_year_id);
				}
				
				try {
					$staffDetail = $this->planningreportsService->getstaffDetail($report_details, $this->organisation_id);
					$objectiveWeight = $this->planningreportsService->getobjectiveWeight($report_details, $this->organisation_id);
					$keyAspiration = $this->planningreportsService->getkeyAspiration($report_details, $this->organisation_id);
					$successIndicator = $this->planningreportsService->getsuccessIndicator($report_details, $this->organisation_id);
					$trendsuccessIndicator = $this->planningreportsService->gettrendsuccessIndicator($report_details, $this->organisation_id);
					$definitionsuccessIndicator = $this->planningreportsService->getdefinitionsuccessIndicator($report_details, $this->organisation_id);
					$requirementssuccessindicator = $this->planningreportsService->getrequirementssuccessindicator($report_details, $this->organisation_id);
				}
				catch(\Exception $e) {
					 die($e->getMessage());
					 // Some DB Error happened, log it and let the user know
				 }
			 }
		 }
	 
		return new ViewModel(array(
			'form' => $form,
			'report_list' => $report_list,
			'organisationList' => $organisationList,
			'five_year' => $five_year,
			'year' => $financial_year_list,
			'staffDetail' => $staffDetail,
			'objectiveWeight' => $objectiveWeight,
			'keyAspiration' => $keyAspiration,
			'successIndicator' => $successIndicator,
			'trendsuccessIndicator' => $trendsuccessIndicator,
			'definitionsuccessIndicator' => $definitionsuccessIndicator,
			'requirementssuccessindicator' => $requirementssuccessindicator,
			'report_details' => $report_details,
			'keyphrase' => $this->keyphrase,
			
			));
	}


	public function printCompiledPlanningReportsAction()
	{
		$this->loginDetails();
         //get the id
        $report_name_from_route = $this->params()->fromRoute('report_name');
		$report_name = $this->my_decrypt($report_name_from_route, $this->keyphrase);

		$organisation_from_route = $this->params()->fromRoute('organisation');
		$organisation = $this->my_decrypt($organisation_from_route, $this->keyphrase);

		$position_from_route = $this->params()->fromRoute('position');
		$position = $this->my_decrypt($position_from_route, $this->keyphrase);

		$financial_year_from_route = $this->params()->fromRoute('financial_year');
		$financial_year = $this->my_decrypt($financial_year_from_route, $this->keyphrase);
		
        if(is_numeric($position)){ 
			$report_details = array();
			$report_details['report_name'] = $report_name;
			$report_details['organisation'] = $organisation;
			$report_details['position'] = $position;
			$report_details['financial_year'] = $financial_year;

			$five_year_plan = $this->planningreportsService->getFiveYearPlan();

			$five_year = array();
			foreach($five_year_plan as $key){
					$five_year_id = $key['id'];
					$five_year = $this->planningreportsService->findFiveYearPlan($five_year_id);
			}


			$position_title = NULL;
			if($position == '1'){
				$position_title = 'Vice Chancellor';
			}else if($position == '2'){
				$position_title = 'Registrar';
			}else if($position == '3'){
				$position_title = 'President';
			}else if($position == '4'){
				$position_title = 'Director for Academic Affairs';
			}else if($position == '5'){
				$position_title = 'Director for Research and External Relations';
			}else if($position == '6'){
				$position_title = 'Director for Planning & Resources';
			}

			$organisationList = $this->planningreportsService->listSelectData('organisation', 'organisation_name', NULL);

			$organisation_name = $organisationList[$organisation];

			$staffDetail = $this->planningreportsService->getstaffDetail($report_details, $this->organisation_id);
			$objectiveWeight = $this->planningreportsService->getobjectiveWeight($report_details, $this->organisation_id);
			$keyAspiration = $this->planningreportsService->getkeyAspiration($report_details, $this->organisation_id);
			$successIndicator = $this->planningreportsService->getsuccessIndicator($report_details, $this->organisation_id);
			$trendsuccessIndicator = $this->planningreportsService->gettrendsuccessIndicator($report_details, $this->organisation_id);
			$definitionsuccessIndicator = $this->planningreportsService->getdefinitionsuccessIndicator($report_details, $this->organisation_id);
			$requirementssuccessindicator = $this->planningreportsService->getrequirementssuccessindicator($report_details, $this->organisation_id);

			$date = date("Y-m-d");
			$pdf = new PdfModel();
			$pdf->setOption($html,'UTF-8');
            $pdf->setOption('fileName', $position_title.'-'.$organisation_name.'-'.$financial_year.'-PlanningReport'.$date); // Triggers PDF download, automatically appends ".pdf"
            $pdf->setOption('display', PdfModel::DISPLAY_ATTACHMENT); 
            $pdf->setOption('paperSize', 'a4'); // Defaults to "8x11"
            $pdf->setOption('paperOrientation', 'landscape'); // Defaults to "portrait"

            //To set view variables
            $pdf->setVariables(array(
				'five_year' => $five_year,
				'staffDetail' => $staffDetail,
				'objectiveWeight' => $objectiveWeight,
				'keyAspiration' => $keyAspiration,
				'successIndicator' => $successIndicator,
				'trendsuccessIndicator' => $trendsuccessIndicator,
				'definitionsuccessIndicator' => $definitionsuccessIndicator,
				'requirementssuccessindicator' => $requirementssuccessindicator,
           ));

            return $pdf;
        }
        else{
            $this->redirect()->toRoute('planningreports');
        }
	}



	//get the organisation list for drop down
	//if OVC, then display all 
	//otherwise, only display college id
	public function getOrganisationArrayList()
	{
		$this->loginDetails();
		$organisation_array = array();
		
		$organisation = $this->organisation_id;
		$organisation_array_list = $this->planningreportsService->listSelectData('organisation','organisation_name', $this->organisation_id);
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
			
		}
		
		return $organisation_array;
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

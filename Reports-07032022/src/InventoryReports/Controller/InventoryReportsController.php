<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace InventoryReports\Controller;


use InventoryReports\Service\InventoryReportsServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use InventoryReports\Form\InventoryReportsForm;
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
 
class InventoryReportsController extends AbstractActionController
{
	
	protected $inventoryreportsService;
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
	
	public function __construct(InventoryReportsServiceInterface $inventoryreportsService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		
		$this->inventoryreportsService = $inventoryreportsService;
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
		
		$empData = $this->inventoryreportsService->getUserDetailsId($this->username);

		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			}

		//get the organisation id
		$organisationID = $this->inventoryreportsService->getOrganisationId($this->username);

		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}

		//get the user details such as name
        $this->userDetails = $this->inventoryreportsService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->inventoryreportsService->getUserImage($this->username, $this->usertype);


	}

	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }

    public function inventoryReportsAction()
	{
		$this->loginDetails();
		$form = new InventoryReportsForm();
		
		$inventory_reports = NULL;
		$organisationList = $this->getOrganisationArrayList();

		$year = NULL;
		//$year = array_combine(range(date('Y'),2012), range(date('Y'),2012));
		$report_details = array();
		$report_list = array(
				'stock_summary' => 'Stock Summary',
			);

		$request = $this->getRequest();
		if($request->isPost()) {
			//$form->setData($request->getPost());
			//if ($form->isValid()) {
				$report_details = $this->params()->fromPost();
		
				try {
					$inventory_reports = $this->inventoryreportsService->getInventoryReports($report_details);
				}
				catch(\Exception $e) {
					die($e->getMessage());
					// Some DB Error happened, log it and let the user know
				}
			//}
		}
		return new ViewModel(array(
			'form' => $form,
			'inventory_reports' => $inventory_reports,
			'organisationList' => $organisationList,
			'year' => $year,
			'report_list' => $report_list,
			'report_details' => $report_details,
			));
	}

	//get the organisation list for drop down
	//if OVC, then display all 
	//otherwise, only display college id
	public function getCollegeArrayList()
	{
		$this->loginDetails();
		$organisation_array = array();
		
		$organisation = $this->organisation_id;
		$organisation_array_list = $this->inventoryreportsService->listSelectData('organisation','organisation_name', $this->organisation_id);
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


	



	//get the organisation list for drop down
	//if OVC, then display all 
	//otherwise, only display college id
	public function getOrganisationArrayList()
	{
		$this->loginDetails();
		$organisation_array = array();
		
		$organisation = $this->organisation_id;
		$organisation_array_list = $this->inventoryreportsService->listSelectData('organisation','organisation_name', $this->organisation_id);
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

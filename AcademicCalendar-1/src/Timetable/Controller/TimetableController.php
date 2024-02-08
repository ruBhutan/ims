<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Timetable\Controller;

use Zend\ServiceManager\ServiceLocatorInterface;
use Timetable\Service\TimetableServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Timetable\Model\Timetable;
use Timetable\Model\UploadTimetable;
use Timetable\Model\TimetableTiming;
use Timetable\Form\TimetableForm;
use Timetable\Form\TimetableTimingForm;
use Timetable\Form\UploadTimetableForm;
use Timetable\Form\SearchForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;
use Zend\Http\Response\Stream;
use Zend\Http\Headers;

/**
 * Description of IndexController
 *
 * @author Mendrel
 */
class TimetableController extends AbstractActionController
{
    
	protected $timetableService;
	protected $notificationService;
    protected $auditTrailService;
    protected $serviceLocator;
	protected $username;
	protected $userrole;
    protected $userregion;
    protected $usertype;
    protected $userDetails;
	protected $employee_details_id;
	protected $organisation_id;

	protected $keyphrase = "RUB_IMS";
	
	public function __construct(TimetableServiceInterface $timetableService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->timetableService = $timetableService;
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
		* Getting the employee_details_id related to username
		*/
		
		$empData = $this->timetableService->getUserDetailsId($this->username);
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			}
			
		//get the organisation id
		$organisationID = $this->timetableService->getOrganisationId($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}

		//get the user details such as name
        if($this->usertype == '1'){
            $this->userDetails = $this->timetableService->getUserDetails($this->username, $tableName = 'employee_details');
        }
        else if($this->usertype == '2'){
            $this->userDetails = $this->timetableService->getUserDetails($this->username, $tableName = 'student');

        }
        else {
            $this->userDetails = $this->timetableService->getUserDetails($this->username, $tableName = 'job_applicant');
        }
	}

    public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
    }
        
	public function uploadTimetableAction()
	{
		$this->loginDetails();
		$form = new UploadTimetableForm();
		$uploadModel = new UploadTimetable();
		$form->bind($uploadModel);

		$message = NULL;

		 $request = $this->getRequest();
		 if ($request->isPost()) {
			 $form->setData($request->getPost());
			 $data = array_merge_recursive(
				$request->getPost()->toArray(),
				$request->getFiles()->toArray()
			 ); 
			$form->setData($data);
			 if ($form->isValid()) {
				 try {
					 $this->timetableService->saveTimetableFile($uploadModel, $this->organisation_id);
					 $this->auditTrailService->saveAuditTrail("INSERT", "Academic Timetable", "ALL", "SUCCESS");
					 $this->flashMessenger()->addMessage('Timetable were successfully uploaded');                     
					 return $this->redirect()->toRoute('addtimetable');
				 }
				 catch(\Exception $e) {
						die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
			 }
		 }
		   return array(
			 'form' => $form,
			 'organisation_id' => $this->organisation_id,
			 'message' => $message,
		 );
	}
	
	public function addTimetableTimingsAction()
	{
		$this->loginDetails();
		$form = new TimetableTimingForm();
		$timetableModel = new TimetableTiming();
		$form->bind($timetableModel);
		
		$timingsList = $this->timetableService->getTimingsList($this->organisation_id);
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->timetableService->saveTimings($timetableModel);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
			 $this->flashMessenger()->addMessage('Timings has been successfully added'); 
			 return $this->redirect()->toRoute('addtimetabletimings');
         }
		 
        return array(
			'form' => $form,
			'organisation_id' => $this->organisation_id,
			'keyphrase' => $this->keyphrase,
			'timingsList' => $timingsList
			); 
	}
	
	public function editTimetableTimingsAction()
	{
		$this->loginDetails();
		//get id from route
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new TimetableTimingForm();
			$timetableModel = new TimetableTiming();
			$form->bind($timetableModel);
			
			$timingsList = $this->timetableService->getTimingsList($this->organisation_id);
			$timingDetails = $this->timetableService->getTimingDetails($id);
	        
	        $request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) {
	                 try {
						 $this->timetableService->saveTimings($timetableModel);
						 $this->auditTrailService->saveAuditTrail("EDIT", "Academic Timetable", "ALL", "SUCCESS");
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
	             }
				 $this->flashMessenger()->addMessage('Timings has been successfully edited'); 
				 return $this->redirect()->toRoute('addtimetabletimings');
	         }
			 
	        return array(
				'form' => $form,
				'organisation_id' => $this->organisation_id,
				'timingsList' => $timingsList,
				'timingDetails' => $timingDetails
				); 
        }else{
        	$this->flashMessenger()->addMessage('Something is wrong. Try again'); 
			return $this->redirect()->toRoute('addtimetabletimings');
        }
	}
	
	public function addTimetableAction()
	{
		$this->loginDetails();
		$message = NULL;
		
		$form = new TimetableForm($this->serviceLocator, $options=array(), $this->organisation_id);
		$timetableModel = new Timetable();
		$form->bind($timetableModel);
		
		$check_allocated_tutor = $this->timetableService->checkAllocatedModuleTutor($this->username);

		if(!empty($check_allocated_tutor)){
		//get timetable by  module tutor
		$timetable = $this->timetableService->getTutorTimetable($this->username, $status = 'Active');
		//var_dump($timetable); die();
		$inActiveTimetable = $this->timetableService->getTutorTimetable($this->username, $status = 'In-Active');

        //$timetable = $this->timetableService->getTimetable(NULL, NULL, NULL, $this->organisation_id);
		$fromTimingList = $this->timetableService->listSelectData($tableName = 'academic_timetable_timing', $columnName='from_time', $this->organisation_id);
		$toTimingList = $this->timetableService->listSelectData($tableName = 'academic_timetable_timing', $columnName='to_time', $this->organisation_id);
	//	$sectionList = $this->timetableService->listSelectData('student_section', 'section', NULL);
						
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {

				$crosscheck_timing = $this->timetableService->crosscheckTiming($timetableModel);
				$crosscheck_timetable = $this->timetableService->crosscheckTimetable($timetableModel);
				 
				 if($crosscheck_timetable){
					 $this->flashMessenger()->addMessage('Timetable for Day/Time has already been added'); 
					 return $this->redirect()->toRoute('addtimetable');
				 }else{
				 	if ($crosscheck_timing){
				 		try {
							 $this->timetableService->saveTimetable($timetableModel);
							 $this->auditTrailService->saveAuditTrail("INSERT", "Academic Timetable", "ALL", "SUCCESS"); 
							 $message = "Success";
							 $this->flashMessenger()->addMessage('Timetable has been successfully added'); 
							 return $this->redirect()->toRoute('addtimetable');
						 }
						 catch(\Exception $e) {
								 die($e->getMessage());
								 // Some DB Error happened, log it and let the user know
						 }
				 	} else {
				 		echo "Academic Timing should not be in a block period";
				 		//$this->flashMessenger()->addMessage('Academic Timing should not be in a block period'); 
					 	//return $this->redirect()->toRoute('addtimetable');
				 	} 
				 }
             }
         }		
		return new ViewModel(array(
			'form' => $form,
			'timetable' => $timetable,
			'inActiveTimetable' => $inActiveTimetable,
			'fromTimingList' => $fromTimingList,
			'toTimingList' => $toTimingList,
		//	'sectionList' => $sectionList,
			'check_allocated_tutor' => $check_allocated_tutor,
			'message' => $message,
			'keyphrase' => $this->keyphrase,
			));
		}
	}
	
	public function viewTimetableAction()
	{
		$this->loginDetails();
		$form = new SearchForm();
		//$timetableModel = new Timetable();
		//$form->bind($timetableModel);
		
		$timetable = $this->timetableService->listAll($tableName='academic_timetable');
		$timetableTimings = $this->timetableService->getTimetableTiming($this->organisation_id);
		$programmeList = $this->timetableService->listSelectData($tableName='programmes', $columnName='programme_name', $this->organisation_id);
		$sectionList = $this->timetableService->listSelectData('student_section','section', NULL);
		$selectYear = $this->timetableService->getMaxProgrammeDuration($this->organisation_id);
				
		$request = $this->getRequest();
                if ($request->isPost()) {
                    $form->setData($request->getPost());
                    if ($form->isValid()) {
					   $year = $this->getRequest()->getPost('year');
					   $programme = $this->getRequest()->getPost('programme');
					   $section = $this->getRequest()->getPost('section');
					   $timetable = $this->timetableService->getTimetable($programme, $section, $year, $this->organisation_id);
                    }
                }
		 else {
			 $timetable = array();
		 }
		
		return new ViewModel(array(
			'form' => $form,
			'timetable' => $timetable,
			'selectYear' => $selectYear,
            'sectionList' => $sectionList,
			'programmeList' => $programmeList,
			'keyphrase' => $this->keyphrase,
			'timetableTimings' => $timetableTimings
			));
	}
	
	public function editTimetableAction()
	{
		$this->loginDetails();
		//get id from route
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
			$form = new TimetableForm($this->serviceLocator, $options=array(), $this->organisation_id);
			$timetableModel = new Timetable();
			$form->bind($timetableModel);
			
			$timetable = $this->timetableService->getTutorTimetable($this->username, $status = 'Active');
			$inActiveTimetable = $this->timetableService->getTutorTimetable($this->username, $status = 'In-Active');
			$fromTimingList = $this->timetableService->listSelectData($tableName = 'academic_timetable_timing', $columnName='from_time', $this->organisation_id);
			$toTimingList = $this->timetableService->listSelectData($tableName = 'academic_timetable_timing', $columnName='to_time', $this->organisation_id);
			$timetableDetails = $this->timetableService->getTimetableDetails($id);
			$sectionList = $this->timetableService->listSelectData('student_section', 'section', NULL);
							
			$request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) {
	             	$crosscheck_timing = $this->timetableService->crosscheckTiming($timetableModel);
	             	if($crosscheck_timing){
	             		try {
							 $this->timetableService->saveTimetable($timetableModel);
							 $this->auditTrailService->saveAuditTrail("EDIT", "Academic Timetable", "ALL", "SUCCESS");
							 $this->flashMessenger()->addMessage('Timetable were successfully edited');   
					 		 return $this->redirect()->toRoute('addtimetable');
						 }
						 catch(\Exception $e) {
								 die($e->getMessage());
								 // Some DB Error happened, log it and let the user know
						 }
	             	} else {
	             		echo "Academic Timing should not be in a block period";
				 		//$this->flashMessenger()->addMessage('Academic Timing should not be in a block period'); 
					 	//return $this->redirect()->toRoute('addtimetable');

	             	}
	             }
	         }
			
			return new ViewModel(array(
				'form' => $form,
				'timetable' => $timetable,
				'inActiveTimetable' => $inActiveTimetable,
				'timetableDetails' => $timetableDetails,
				'fromTimingList' => $fromTimingList,
				'toTimingList' => $toTimingList,
				'sectionList' => $sectionList,
				'keyphrase' => $this->keyphrase,
				));
        }else{
        	$this->flashMessenger()->addMessage('Timetable has been successfully edited'); 
			return $this->redirect()->toRoute('addtimetable');
        }
	}
	
	public function deleteTimetableAction()
	{
		$this->loginDetails();
		//get id from route
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
			$check = $this->timetableService->checkTimetableAttendance($id);
			if($check){
				$this->flashMessenger()->addMessage('Timetable cannot be deleted. Attendance has been entered');
				return $this->redirect()->toRoute('addtimetable');
			} else{
				$this->timetableService->deleteTimetable($id);
				$this->flashMessenger()->addMessage('Timetable were successfully deleted');    
				return $this->redirect()->toRoute('addtimetable');
			}
        }else{
        	$this->flashMessenger()->addMessage('Something went wrong. Try again');
			return $this->redirect()->toRoute('addtimetable');
        }
	}
	
	public function viewTutorTimetableAction()
	{
		$this->loginDetails();
		$form = new SearchForm();

		$check_allocated_tutor = $this->timetableService->checkAllocatedModuleTutor($this->username);

		if(!empty($check_allocated_tutor)){
			$timetable = $this->timetableService->getTutorTimetable($this->username, $status = 'Active');
			$timetableTimings = $this->timetableService->getTimetableTiming($this->organisation_id);
							
			return new ViewModel(array(
				'form' => $form,
				'timetable' => $timetable,
				'keyphrase' => $this->keyphrase,
				'timetableTimings' => $timetableTimings,
				'check_allocated_tutor' => $check_allocated_tutor,
				));
		}
	}
    
	/*
	* AJAX Actions
	*/
	
	public function ajaxModuleNameAction()
    {
		$semester = NULL;
		$academic_year = NULL;
		$dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
		$sql_semester       = "SELECT semester_type FROM academic_year where semester_start <=" .date('Y-m-d'). " AND semester_end >=".date('Y-m-d');
		$statement2 = $dbAdapter->query($sql_semester);
		$result2    = $statement2->execute();
		foreach($result2 as $year){
			$semester = $year['semester_type'];
		}
		
		if($semester_type == 'odd'){
			$academic_year = date('Y').'-'.(date('Y')+1);
		} else {
			$academic_year = (date('Y')-1).'-'.date('Y');
		}
                
		$parentValue = $_POST['value'];
		
        $dbAdapter1 = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        if($semester == 'odd')
			$sql       = "SELECT id, module_title FROM academic_modules_allocation where semester IN (1,3,5,7,9) AND academic_year =" .$academic_year. " AND programmes_id='$parentValue'";
		else
			$sql       = "SELECT id, module_title FROM academic_modules_allocation where semester IN (2,4,6,8,10) AND academic_year =" .$academic_year. " AND programmes_id='$parentValue'";
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

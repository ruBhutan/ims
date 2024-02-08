<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace EmployeeTask\Controller;


use EmployeeTask\Service\EmployeeTaskServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use EmployeeTask\Form\EmployeeTaskForm;
use EmployeeTask\Form\EmployeeTaskCategoryForm;
use EmployeeTask\Form\SearchForm;
use EmployeeTask\Model\EmployeeTask;
use EmployeeTask\Model\EmployeeTaskCategory;
use Zend\Session\Container;
use Zend\Http\Response\Stream;
use Zend\Http\Headers;

/**
 * Description of IndexController
 *
 */
 
class EmployeeTaskController extends AbstractActionController
{
	protected $employeetaskService;
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
	
	public function __construct(EmployeeTaskServiceInterface $employeetaskService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->employeetaskService = $employeetaskService;
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
		
		$empData = $this->employeetaskService->getUserDetailsId($this->username);
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			}

		//get the organisation id
		$organisationID = $this->employeetaskService->getOrganisationId($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}

		//get the user details such as name
        $this->userDetails = $this->employeetaskService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->employeetaskService->getUserImage($this->username, $this->usertype);

	}

	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }
	
    public function addEmployeeTaskCategoryAction()
    { 
    	$this->loginDetails();

        $form = new EmployeeTaskCategoryForm();

		$employeetaskModel = new EmployeeTaskCategory();

		$form->bind($employeetaskModel);
		
		$categories = $this->employeetaskService->listAll($tableName='employee_task_category', $this->organisation_id, $this->employee_details_id);

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
					 $this->employeetaskService->saveCategory($employeetaskModel);
					 $this->auditTrailService->saveAuditTrail("INSERT", "Employee Task Category", "ALL", "SUCCESS");

					 $this->flashMessenger()->addMessage('Employee Task Category was successfully added');
					 return $this->redirect()->toRoute('employeetaskcategory');
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
			'employee_details_id' => $this->employee_details_id,
			'keyphrase' => $this->keyphrase,
			'categories' => $categories,
			'message' => $message,
		);
    }
	
	public function viewEmployeeTaskCategoryAction()
    {
    	$this->loginDetails();
        //get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new EmployeeTaskCategoryForm();
			$employeetaskModel = new EmployeeTaskCategory();
			$form->bind($employeetaskModel);
			
			$employeetask = $this->employeetaskService->getEmployeeTaskCategoryDetails($id);
			 
	        return array(
				'form' => $form,
				'employeetask' => $employeetask);
        }else{
        	return $this->redirect()->toRoute('employeetaskcategory');
        }
    }
	
	public function editEmployeeTaskCategoryAction()
    {
    	$this->loginDetails();
        //get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new EmployeeTaskCategoryForm();
			$employeetaskModel = new EmployeeTaskCategory();
			$form->bind($employeetaskModel);

			
			$employeetask = $this->employeetaskService->getemployeetaskCategoryDetails($id);
	        $request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) {
	                 try {
						 $this->employeetaskService->saveCategory($employeetaskModel);
						 $this->auditTrailService->saveAuditTrail("EDIT", "Employee Task Category", "ALL", "SUCCESS");

						 $this->flashMessenger()->addMessage('Employee Task Category was successfully edited');
						 return $this->redirect()->toRoute('employeetaskcategory');
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
				'employeetask' => $employeetask);
        }else{
        	return $this->redirect()->toRoute('employeetaskcategory');
        }
    }

    public function employeetaskRecordAction()
	{
		$this->loginDetails();

		$form = new SearchForm();

		$message = NULL;

		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$staffName = $this->getRequest()->getPost('staff_name');
				$staffId = $this->getRequest()->getPost('staff_id');
				$staffList = $this->employeetaskService->getStaffList($staffName, $staffId, $this->organisation_id);
             }
         }
		 else {
			 $staffList = array();
		 }
		
		return new ViewModel(array(
            'form' => $form,
			'staffList' => $staffList,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
            ));
	}

    public function addEmployeeTaskRecordAction()
    {
    	$this->loginDetails();
        //get the staff id
        $self_id = $this->employee_details_id;
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        $form = new EmployeeTaskForm();

		$employeetaskModel = new EmployeeTask();

		$form->bind($employeetaskModel);

		$message = NULL;

        if(is_numeric($id)){
        	$form = new EmployeeTaskForm();
        	
			$employeetaskModel = new EmployeeTask();
			$form->bind($employeetaskModel);
			
			$staff = $this->employeetaskService->getStaffDetails($id);
			$activityRecords = $this->employeetaskService->listAll1($id);

			$employeetaskCategory = $this->employeetaskService->listSelectData($tableName = 'employee_task_category', $columnName = 'employee_task_category', $this->organisation_id, $self_id);
	        
	        $request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             $data = array_merge_recursive(
				 	$request->getPost()->toArray(),
					$request->getFiles()->toArray()
				 ); 
				 $form->setData($data); 
	             if ($form->isValid()) {
	             	$data = $this->params()->fromPost();
        			$employeetask_type = $data['employeetask']['employeetask_type'];

        			$status = $this->params()->fromPost();
        			$employeetask_status = $status['employeetask']['status'];
	                 try {
	                 	if($data['employeetask']['to_date'] >= $data['employeetask']['from_date']){
	                 		$this->employeetaskService->saveEmployeeTaskRecord($employeetaskModel);
	                 		$this->notificationService->saveNotification('Staff Task/Project Activity Record', $id, 'NULL', 'Staff Task/Project Activity Record');
	                 		$this->auditTrailService->saveAuditTrail("INSERT", "Staff Task/Project Activity Record", "ALL", "SUCCESS");
	                 		$this->flashMessenger()->addMessage(' Staff Task/Project Activity Record was successfully added');
	                 		return $this->redirect()->toRoute('employeetaskrecord');
						} 
						else {
							$message = 'Failure';
							$this->flashMessenger()->addMessage(' Your "To Date" is before "From Date". Please re-enter the data.');
						}

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
				'staff' => $staff,
				'keyphrase' => $this->keyphrase,
				'emp_id' => $this->username,
				'employee_details_id' => $this->employee_details_id,
				'activityRecords' => $activityRecords,
				'employeetaskCategory' => $employeetaskCategory,
				'message' => $message);
        }else{
        	$this->redirect()->toRoute('employeetaskrecord');
        }
    }
    public function viewEmployeeTaskRecordAction()
    {
    	$this->loginDetails();
        $form = new EmployeeTaskForm();
		$searchForm = new SearchForm();
		$employeetaskModel = new EmployeeTask();
		$form->bind($employeetaskModel);

		$message = NULL;
		$staffName = NULL;
		$staffId = NULL;
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $searchForm->setData($request->getPost());
             if ($searchForm->isValid()) {
				$staffName = $this->getRequest()->getPost('staff_name');
				$staffId = $this->getRequest()->getPost('staff_id');
				
				//$employeetaskRecord = $this->employeetaskService->getStaffEmployeeTaskList($staffName, $staffId, $this->organisation_id);
				$employeetaskRecord = $this->employeetaskService->getStaffList($staffName, $staffId, $this->organisation_id);
             }
         }
		 else {

			 //$employeetaskRecord = $this->employeetaskService->getEmployeeTaskRecord($this->organisation_id);
			 $employeetaskRecord = $this->employeetaskService->getStaffList($staffName, $staffId, $this->organisation_id);
		 }
		 
        return array(
			'form' => $form,
			'searchForm' => $searchForm,
			'keyphrase' => $this->keyphrase,
			'employeetaskRecord' => $employeetaskRecord,
			'message' => $message,
		);
    }
    public function viewIndividualEmployeeTaskRecordAction()
	{
		$this->loginDetails();
		//get the student id
		$staffName = NULL;
		$staffId = NULL;
		$employeetaskRecord = NULL;
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new EmployeeTaskForm();
			$searchForm = new SearchForm();
			$employeetaskModel = new EmployeeTask();
			$form->bind($employeetaskModel);
        	
    		//$form = new EmployeeTaskForm();

    		$staff_detail_tmp = $this->employeetaskService->getStaffDetails($id);
    		$staffDetail = array();
    		foreach($staff_detail_tmp as $tmp){
    			$staffDetail = $tmp;
    		} 

    		$staffRecords = $this->employeetaskService->getStaffEmployeeTaskRecords($staffDetail['id']);

			$request = $this->getRequest();
        	if ($request->isPost()) {
        		$searchForm->setData($request->getPost());
        		if ($searchForm->isValid()) {
        			//$staffRecords = $this->employeetaskService->getStaffEmployeeTaskRecords($staffDetail['id']);

        			//echo($staffRecords); die();
        			$staff_id = $staffDetail['id'];
        			$from_date = $this->getRequest()->getPost('from_date');
        			$to_date = $this->getRequest()->getPost('to_date');
        			$staffRecords = $this->employeetaskService->getstafftaskRecord($staff_id, $from_date, $to_date);

        			//var_dump($employeetaskRecord); die();
        		}
        	}
        }else{
        	//echo "string"; die();
        	$this->redirect()->toRoute('viewemployeetaskrecord');
        }	
        return array(
			'form' => $form,
			'searchForm' => $searchForm,	
			'staffRecords' => $staffRecords,
			//'employeetaskRecord' => $employeetaskRecord,
			//'keyphrase' => $this->keyphrase,
			//'message' => $message,
		);	
    }
    public function addIndividualEmployeeTaskAction()
    {

    	$this->loginDetails();
        //get the id
		
		$id = $this->employee_details_id;//(int) $this->params()->fromRoute('id', 0);
		
		$form = new EmployeeTaskForm();
		$employeetaskModel = new EmployeeTask();
		$form->bind($employeetaskModel);

		$message = NULL;
		
		//Need to send value of the table name and columns
		$tableName = 'employee_task_category';
		$columnName = 'employee_task_category';
		$employeetaskCategorySelect = $this->employeetaskService->listSelectData($tableName, $columnName, $this->organisation_id, $id);
		
		//$staffDetail = $this->employeetaskService->findStaff($id);
		$staffDetail = $this->employeetaskService->getStaffDetails($id);

		$activityRecords = $this->employeetaskService->listAll1($id);
        
        $request = $this->getRequest();
        if ($request->isPost()) {
        	$form->setData($request->getPost());
        	$data = array_merge_recursive(
        		$request->getPost()->toArray(),
        		$request->getFiles()->toArray()
        	); 
        	$form->setData($data); 
        	if ($form->isValid()) {
        		$data = $this->params()->fromPost();
        		$employeetask_type = $data['employeetask']['employeetask_type'];

        		$status = $this->params()->fromPost();
        		$employeetask_status = $status['employeetask']['status'];
        		try {
        			//echo "string"; die();
        			//var_dump($employeetaskModel); die();
        			if($data['employeetask']['to_date'] >= $data['employeetask']['from_date']){
	        			$this->employeetaskService->saveEmployeeTaskRecord($employeetaskModel);
						$this->notificationService->saveNotification('Your Task/Project Activity Record', $id, 'NULL', 'Staff Task/Project Activity Record');
						$this->auditTrailService->saveAuditTrail("INSERT", "Your Task/Project Activity Record", "ALL", "SUCCESS");

						$this->flashMessenger()->addMessage(' Your Task/Project Activity Record was successfully added');
						return $this->redirect()->toRoute('addempemployeetaskrecord');
        			} else {
        				$message = 'Failure';
        				$this->flashMessenger()->addMessage(' Your "To Date" is before "From Date". Please re-enter the data.');
        			}
        			
        		}
        		catch(\Exception $e) {
        			die($e->getMessage());
        			// Some DB Error happened, log it and let the user know
        		}
        	}
        }
		 
        return array(
			'form' => $form,
			'keyphrase' => $this->keyphrase,
			'selectData' => $employeetaskCategorySelect,
			'staffDetail' => $staffDetail,
			'activityRecords' => $activityRecords,
			'message' => $message);
    }
    public function editIndividualEmployeeTaskRecordAction()
    {
    	$this->loginDetails(); 
        //get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);


        if(is_numeric($id)){
        	$form = new EmployeeTaskForm();
			$employeetaskModel = new EmployeeTask();
			$form->bind($employeetaskModel);

			//Need to send value of the table name and columns
			$tableName = 'employee_task_category';
			//$columnName = 'employee_task_category';
			$emploteetaskCategorySelect = $this->employeetaskService->listSelectData1($tableName, $id);
			$employeetask = $this->employeetaskService->getEmployeeTaskRecordDetails($id);

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
	             	//$data = $this->params()->fromPost();
        			//$employeetask_type = $data['employeetask']['employeetask_type'];
	             	
	                 try {
	                 	if($data['employeetask']['to_date'] >= $data['employeetask']['from_date']){
	                 		$this->employeetaskService->saveEmployeeTaskRecord($employeetaskModel);
	                 		$this->auditTrailService->saveAuditTrail("EDIT", "Employee Task Category", "ALL", "SUCCESS");
	                 		$this->flashMessenger()->addMessage('Employee Task Category was successfully edited');
	                 		return $this->redirect()->toRoute('addempemployeetaskrecord');
	                 	} else {
	                 		$message = 'Failure';
							$this->flashMessenger()->addMessage(' Your "To Date" is before "From Date". Please re-enter the data.');
	                 	}
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
	             } else{
	             	//var_dump($employeetaskModel); die();
	             }
	         }
	        return array(
				'id' => $id,
				'form' => $form,
				'selectData' => $emploteetaskCategorySelect,
				'employeetask' => $employeetask,
				'message' => $message);
        }else{
        	return $this->redirect()->toRoute('addempemployeetaskrecord');
        }
    }

    public function downloadEmployeeTaskFileAction()
	{
		$this->loginDetails();
        //get the student id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $file = $this->employeetaskService->getFileName($id);
        
        	$mimetype = mime_content_type($file);
            $response = new Stream();
            $response->setStream(fopen($file, 'r'));
            $response->setStatusCode(200);
            $response->setStreamName(basename($file));
            $headers = new Headers();
            $headers->addHeaderLine('Content-Disposition:inline','attachment; filename="' . basename($file) .'"')
	            	->addHeaderLine('Content-Type', $mimetype)
	            	->addHeaderLine('Content-Length',filesize($file))
	            	->addHeaderLine('Expires','@0') // @0, because zf2 parses date as string to \DateTime() object
	            	->addHeaderLine('Cache-Control','must-revalidate')
	            	->addHeaderLine('Pragma','public')
	            	->addHeaderLine('Content-Transfer-Encoding:binary')
	            	->addHeaderLine('Accept-Ranges:bytes');
           
            $response->setHeaders($headers);
            return $response;
        }
        else
        {
            $this->redirect()->toRoute('addempemployeetaskrecord');
        }
	}
	/*
	
    
	
	
	
    
	public function editDisciplinaryRecordAction()
    {
    	$this->loginDetails();

        $form = new DisciplineForm();
		$disciplineModel = new Discipline();
		$form->bind($disciplineModel);
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->disciplineService->save($disciplineModel);
					 $this->redirect()->toRoute('viewdisciplinaryrecord');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array('form' => $form);
    }
    
	public function searchDisciplinaryRecordAction()
    {
    	$this->loginDetails();

        $form = new DisciplineForm();
		$disciplineModel = new Discipline();
		$form->bind($disciplineModel);
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->disciplineService->save($disciplineModel);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array('form' => $form);
    }
	
	*/

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

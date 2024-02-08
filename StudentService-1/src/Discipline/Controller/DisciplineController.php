<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Discipline\Controller;

use Discipline\Service\DisciplineServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Discipline\Form\DisciplineForm;
use Discipline\Form\DisciplineCategoryForm;
use Discipline\Form\SearchForm;
use Discipline\Model\Discipline;
use Discipline\Model\DisciplineCategory;
use Zend\Session\Container;

/**
 * Description of IndexController
 *
 */
 
class DisciplineController extends AbstractActionController
{
	protected $disciplineService;
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
	
	public function __construct(DisciplineServiceInterface $disciplineService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->disciplineService = $disciplineService;
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
		
		$empData = $this->disciplineService->getUserDetailsId($this->username);
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			}

		//get the organisation id
		$organisationID = $this->disciplineService->getOrganisationId($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}

		//get the user details such as name
        $this->userDetails = $this->disciplineService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->disciplineService->getUserImage($this->username, $this->usertype);

	}

	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }
	
	public function disciplinaryRecordAction()
	{
		$this->loginDetails();

		$form = new SearchForm();

		$message = NULL;
	   
	   $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$studentName = $this->getRequest()->getPost('student_name');
				$studentId = $this->getRequest()->getPost('student_id');
				$programme = $this->getRequest()->getPost('programme');
				$studentList = $this->disciplineService->getStudentList($studentName, $studentId, $programme, $this->organisation_id);
             }
         }
		 else {
			 $studentList = array();
		 }
		
		return new ViewModel(array(
            'form' => $form,
			'studentList' => $studentList,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
            ));
	}
    
	public function addDisciplinaryRecordAction()
    {
    	$this->loginDetails();
        //get the student id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new DisciplineForm();
			$disciplineModel = new Discipline();
			$form->bind($disciplineModel);
			
			$student = $this->disciplineService->getStudentDetails($id);
			$disciplineCategory = $this->disciplineService->listSelectData($tableName = 'discipline_category', $columnName = 'discipline_category', $this->organisation_id);
	        
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
						 $this->disciplineService->saveDisciplinaryRecord($disciplineModel);
						 $this->notificationService->saveNotification('Student Disciplinary Record', $id, 'NULL', 'Student Disciplinary Record');
	                    $this->auditTrailService->saveAuditTrail("INSERT", "Student Disciplinary Record", "ALL", "SUCCESS");

	                    $this->flashMessenger()->addMessage('Disciplinary Record was successfully added');
						 return $this->redirect()->toRoute('viewdisciplinaryrecord');
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
				'student' => $student,
				'emp_id' => $this->username,
				'employee_details_id' => $this->employee_details_id,
				'disciplineCategory' => $disciplineCategory);
        }else{
        	$this->redirect()->toRoute('viewdisciplinaryrecord');
        }
    }
	
	public function viewDisciplinaryRecordAction()
    {
    	$this->loginDetails();
        $form = new DisciplineForm();
		$searchForm = new SearchForm();
		$disciplineModel = new Discipline();
		$form->bind($disciplineModel);

		$message = NULL;
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $searchForm->setData($request->getPost());
             if ($searchForm->isValid()) {
				$studentName = $this->getRequest()->getPost('student_name');
				$studentId = $this->getRequest()->getPost('student_id');
				$programme = $this->getRequest()->getPost('programme');
				$disciplinaryRecord = $this->disciplineService->getStudentDisciplinaryList($studentName, $studentId, $programme, $this->organisation_id);
             }
         }
		 else {
			 $disciplinaryRecord = $this->disciplineService->getDisciplinaryRecord($this->organisation_id);
		 }
		 
        return array(
			'form' => $form,
			'searchForm' => $searchForm,
			'keyphrase' => $this->keyphrase,
			'disciplinaryRecord' => $disciplinaryRecord,
			'message' => $message,
		);
    }
    
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
	
	public function addIndividualDisciplinaryAction()
    {
    	$this->loginDetails();
        //get the id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$form = new DisciplineForm();
		$disciplineModel = new Discipline();
		$form->bind($disciplineModel);
		
		//Need to send value of the table name and columns
		$tableName = 'discipline_category';
		$columnName = 'discipline_category';
		$responsibilitiesSelect = $this->disciplineService->listSelectData($tableName, $columnName, $this->organisation_id);
		
		$studentDetail = $this->disciplineService->findStudent($id);
        
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
		 
        return array(
			'form' => $form,
			'selectData' => $responsibilitiesSelect,
			'studentDetail' => $studentDetail);
    }
	
	public function addDisciplinaryCategoryAction()
    {
    	$this->loginDetails();

        $form = new DisciplineCategoryForm();
		$disciplineModel = new DisciplineCategory();
		$form->bind($disciplineModel);
		
		$categories = $this->disciplineService->listAll($tableName='discipline_category', $this->organisation_id);

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
					 $this->disciplineService->saveCategory($disciplineModel);
					 $this->auditTrailService->saveAuditTrail("INSERT", "Discipline Category", "ALL", "SUCCESS");

					 $this->flashMessenger()->addMessage('Disciplinary Category was successfully added');
					 return $this->redirect()->toRoute('disciplinarycategory');
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
			'keyphrase' => $this->keyphrase,
			'categories' => $categories,
			'message' => $message,
		);
    }
	
	public function viewDisciplinaryCategoryAction()
    {
    	$this->loginDetails();
        //get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new DisciplineCategoryForm();
			$disciplineModel = new DisciplineCategory();
			$form->bind($disciplineModel);
			
			$discipline = $this->disciplineService->getDisciplineCategoryDetails($id);
			 
	        return array(
				'form' => $form,
				'discipline' => $discipline);
        }else{
        	return $this->redirect()->toRoute('responsibilitycategory');
        }
    }
	
	public function editDisciplinaryCategoryAction()
    {
    	$this->loginDetails();
        //get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new DisciplineCategoryForm();
			$disciplineModel = new DisciplineCategory();
			$form->bind($disciplineModel);
			
			$discipline = $this->disciplineService->getDisciplineCategoryDetails($id);
	        
	        $request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) {
	                 try {
						 $this->disciplineService->saveCategory($disciplineModel);
						 $this->auditTrailService->saveAuditTrail("EDIT", "Discipline Category", "ALL", "SUCCESS");

						 $this->flashMessenger()->addMessage('Disciplinary Category was successfully edited');
						 return $this->redirect()->toRoute('disciplinarycategory');
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
				'discipline' => $discipline);
        }else{
        	return $this->redirect()->toRoute('responsibilitycategory');
        }
    }
	
	public function viewIndividualDisciplinaryRecordAction()
	{
		$this->loginDetails();
		//get the student id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new DisciplineForm();
		
			$student_detail_tmp = $this->disciplineService->getStudentDetails($id);
			$studentDetail = array();
			foreach($student_detail_tmp as $tmp){
				$studentDetail = $tmp;
			} 
			$studentRecords = $this->disciplineService->getStudentDisciplinaryRecords($studentDetail['id']);
	        		 
	        return array(
				'form' => $form,
				'studentRecords' => $studentRecords,
			);
        }else{
        	$this->redirect()->toRoute('viewdisciplinaryrecord');
        }		 
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

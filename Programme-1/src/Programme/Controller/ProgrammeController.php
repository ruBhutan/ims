<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Programme\Controller;

use Programme\Service\ProgrammeServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Programme\Form\ProgrammeForm;
use Programme\Form\ModuleForm;
use Programme\Form\AssessmentComponentForm;
use Programme\Form\EditAssessmentComponentForm;
use Programme\Form\AssessmentComponentTypeForm;
use Programme\Form\ContinuousAssessmentForm;
use Programme\Form\DpdAssessmentForm;
use Programme\Form\AssignModuleCoordinatorForm;
use Programme\Form\AssignModuleForm;
use Programme\Form\AssignModuleTutorsForm;
use Programme\Form\AcademicYearModuleForm;
use Programme\Form\EditAcademicYearModuleForm;
use Programme\Form\EditAssessmentForm;
use Programme\Form\EditAssessmentMarkForm;
use Programme\Form\SearchForm;
use Programme\Form\StudentSearchForm;
use Programme\Form\StudentSearchForm1;
use Programme\Form\ExamSearchForm;
use Programme\Form\AcademicCalendarSearchForm;
use Programme\Form\MarkEntryForm;
use Programme\Form\ProgrammeSearchForm;
use Programme\Form\TutorSearchForm;
use Programme\Form\UploadModuleTutorsForm;
use Programme\Form\GraduatedStudentForm;
use Programme\Form\MissingModuleForm;
use Programme\Form\ElectiveModuleSearchForm;
use Programme\Form\AssignElectiveModuleForm;
use Programme\Form\DpdMarkAllocationForm;
use Programme\Form\EditCompiledMarkForm;
use Programme\Model\Programme;
use Programme\Model\Module;
use Programme\Model\AssessmentComponent;
use Programme\Model\EditAssessmentComponent;
use Programme\Model\AssessmentComponentType;
use Programme\Model\ContinuousAssessment;
use Programme\Model\AssignModule;
use Programme\Model\AcademicYearModule;
use Programme\Model\EditAssessmentMark;
use Programme\Model\UploadModuleTutors;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;
use Zend\Http\Response\Stream;
use Zend\Http\Headers;

/**
 * Description of IndexController
 *
 */
 
class ProgrammeController extends AbstractActionController
{
	protected $programmeService;
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
	protected $organisation_id;

	protected $keyphrase = "RUB_IMS";
	
	public function __construct(ProgrammeServiceInterface $programmeService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->programmeService = $programmeService;
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
		
		/*
		* Getting the employee_details_id related to username
		*/
		if($this->usertype == 1){
			$empData = $this->programmeService->getUserDetailsId($this->username, $tableName = 'employee_details');
			foreach($empData as $emp){
				$this->employee_details_id = $emp['id'];
				}
		}
		if($this->usertype == 2){
            $stdData = $this->programmeService->getUserDetailsId($this->username, $tableName = 'student');
            foreach($stdData as $std){
                $this->student_details_id = $std['id'];
            }
        }
			
		//get the organisation id
		$organisationID = $this->programmeService->getOrganisationId($this->username, $this->usertype);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}

		//get the user details such as name
        $this->userDetails = $this->programmeService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->programmeService->getUserImage($this->username, $this->usertype);
	}

	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }

    
	public function addProgrammeAction()
    {
    	$this->loginDetails();
        $form = new ProgrammeForm();
		$programmeModel = new Programme();
		$form->bind($programmeModel);
				
		$programmes = $this->programmeService->listProgrammes($this->organisation_id);
		$tutorList = $this->programmeService->listSelectData($tableName = 'employee_details', $columnName = NULL, $this->organisation_id, $this->username);
		$academicSession = $this->programmeService->listSelectData('academic_session','academic_session', $this->organisation_id, $this->username);
		
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
				 $data = $form->getData();
                 try {
					 $this->programmeService->saveProgramme($programmeModel);
					 $this->auditTrailService->saveAuditTrail("INSERT", "Programmes", "ALL", "SUCCESS");
					 $this->flashMessenger()->addMessage('Programme was successfully added');
					 return $this->redirect()->toRoute('addnewprogramme');
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
			'tutorList' => $tutorList,
			'programmes' => $programmes,
			'academicSession' => $academicSession,
			'keyphrase' => $this->keyphrase,
			'message' => $message);
    }
	
	public function viewProgrammeAction()
	{
		$this->loginDetails();
		//get the programme id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new ProgrammeForm();
		
			$programmeDetails = $this->programmeService->findProgramme($id);
			$tutorList = $this->programmeService->listSelectData($tableName = 'employee_details', $columnName = NULL, $this->organisation_id, $this->username);
			
			return array(
					'form' => $form,
					'programmeDetails' => $programmeDetails,
					'tutorList' => $tutorList,
					'keyphrase' => $this->keyphrase,
				);
        }else{
        	return $this->redirect()->toRoute('addnewprogramme');
        }
	}
	
	public function editProgrammeAction()
	{
		$this->loginDetails();
		//get the programme id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new ProgrammeForm();
			$programmeModel = new Programme();
			$form->bind($programmeModel);
			
			$programmeDetails = $this->programmeService->findProgramme($id);
			$programmes = $this->programmeService->listProgrammes($this->organisation_id);
			$tutorList = $this->programmeService->listSelectData($tableName = 'employee_details', $columnName = NULL, $this->organisation_id, $this->username);
			$academicSession = $this->programmeService->listSelectData('academic_session','academic_session', $this->organisation_id, $this->username);
	        
	        $request = $this->getRequest();
	         if ($request->isPost()) {
				$form->setData($request->getPost());
				 $data = array_merge_recursive(
				 	$request->getPost()->toArray(),
					$request->getFiles()->toArray()
				 ); 
				 $form->setData($data);
	             if ($form->isValid()) {
					 $data = $form->getData(); 
	                 try {
						 $this->programmeService->saveProgramme($programmeModel);
						 $this->auditTrailService->saveAuditTrail("EDIT", "Programmes", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage('Programme was successfully edited');
						 return $this->redirect()->toRoute('addnewprogramme');
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
				'programmeDetails' => $programmeDetails,
				'programmes' => $programmes,
				'academicSession' => $academicSession,
				'keyphrase' => $this->keyphrase,
				'tutorList' => $tutorList);
        }else{
        	return $this->redirect()->toRoute('addnewprogramme');
        }
	}
	
	/*
	* Function to list programmes. Will use data table to search within the table
	*/
	public function listProgrammesAction()
    {
    	$this->loginDetails();		
		$form = new ProgrammeForm();
		$programmes = $this->programmeService->listProgrammes($this->organisation_id);
		
		$message = NULL;
		 
        return array(
			'form' => $form,
			'programmes' => $programmes,
			'keyphrase' => $this->keyphrase,
			'message' => $message);
    }
	    
	public function updateProgrammeAction()
    {
    	$this->loginDetails();
        //get the programme id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new ProgrammeForm();
			$programmeModel = new Programme();
			$form->bind($programmeModel);
			
			$programmeDetails = $this->programmeService->findProgramme($id);
			$programmes = $this->programmeService->listProgrammes($this->organisation_id);
			$tutorList = $this->programmeService->listSelectData($tableName = 'employee_details', $columnName = NULL, $this->organisation_id, $this->username);
			$academicSession = $this->programmeService->listSelectData('academic_session','academic_session', $this->organisation_id, $this->username);
	        
	        $request = $this->getRequest();
	         if ($request->isPost()) {
				$form->setData($request->getPost());
				 $data = array_merge_recursive(
				 	$request->getPost()->toArray(),
					$request->getFiles()->toArray()
				 ); 
				 $form->setData($data);
	             if ($form->isValid()) {
					 $data = $form->getData(); 
	                 try {
						 $this->programmeService->updateProgramme($programmeModel);
						 $this->auditTrailService->saveAuditTrail("INSERT", "Programmes History", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage('Progamme was successfully updated');
						 return $this->redirect()->toRoute('listprogrammes');
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
				'programmeDetails' => $programmeDetails,
				'programmes' => $programmes,
				'academicSession' => $academicSession,
				'tutorList' => $tutorList);
        }else{
        	return $this->redirect()->toRoute('listprogrammes');
        }
    }

	
	public function programmesHistoryAction()
    {
    	$this->loginDetails();		
		$form = new ProgrammeForm();
		$programmes = $this->programmeService->listProgrammes($this->organisation_id);
		 
        return array(
			'form' => $form,
			'programmes' => $programmes,
			'keyphrase' => $this->keyphrase,
		);
    }
	
	public function viewProgrammeHistoryAction()
	{
		$this->loginDetails();
		//get the programme id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){ 
        	$form = new ProgrammeForm();
		
			$programmeDetails = $this->programmeService->getProgrammeHistory($id);
			$tutorList = $this->programmeService->listSelectData($tableName = 'employee_details', $columnName = NULL, $this->organisation_id, $this->username);
			
			return array(
					'form' => $form,
					'programmeDetails' => $programmeDetails,
					'tutorList' => $tutorList,
					'keyphrase' => $this->keyphrase,
					);
        }else{
        	return $this->redirect()->toRoute('listprogrammes');
        }
	}
	
	public function downloadDpdAction() 
	{
		$this->loginDetails();
		//get the param from the view file
		$id_from_route = $this->params()->fromRoute('id');
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        $category_from_route = $this->params()->fromRoute('category');
        $category = $this->my_decrypt($category_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
        	//get the location of the file from the database		
			$file = $this->programmeService->getFileName($id, $category);
			
			$response = new Stream();
			$response->setStream(fopen($file, 'r'));
			$response->setStatusCode(200);
			$response->setStreamName(basename($file));
			$headers = new Headers();
			$headers->addHeaders(array(
				'Content-Disposition' => 'attachment; filename="' . basename($file) .'"',
				'Content-Type' => 'application/octet-stream',
				'Content-Length' => filesize($file),
				'Expires' => '@0', // @0, because zf2 parses date as string to \DateTime() object
				'Cache-Control' => 'must-revalidate',
				'Pragma' => 'public'
			));
			$response->setHeaders($headers);
			return $response;
        }else{
        	return $this->redirect()->toRoute('listprogrammes');
        }
	}
	
	public function addModuleAction()
    {
        $this->loginDetails();
		
		$form = new ModuleForm($this->serviceLocator);
		$moduleModel = new Module();
		$form->bind($moduleModel);
		
		$modules = $this->programmeService->listModules($this->organisation_id);
		$programmeList = $this->programmeService->listSelectData($tableName = 'programmes', $columnName ='programme_name', $this->organisation_id, $this->username);
        
		$module_message = NULL;
		$request = $this->getRequest();
		 if ($request->isPost()) {
				$form->setData($request->getPost());
				$data = $this->params()->fromPost();
				//to check and ensure that there are no duplicate modules entered for a programme
			   $module_code = $data['module']['module_code'];
			   $programmes_id = $data['module']['programmes_id'];
			   $programme_module_check = $this->programmeService->crosscheckProgrammeModule($module_code, $programmes_id);

			   if ($programme_module_check){
					$module_message = "Module with similar Module Code already exists for the selected Programme";
			   } else {
				   if ($form->isValid()) {
						try {
								$this->programmeService->saveModule($moduleModel, $data);
								$this->auditTrailService->saveAuditTrail("INSERT", "Academic Modules", "ALL", "SUCCESS");
								$this->flashMessenger()->addMessage('Module was successfully added');
								return $this->redirect()->toRoute('addnewmodule');
						}
						catch(\Exception $e) {
										die($e->getMessage());
										// Some DB Error happened, log it and let the user know
						}
					}
			   }
		 }

		return array(
			'form' => $form,
			'modules' => $modules,
			'programmeList' => $programmeList,
			'keyphrase' => $this->keyphrase,
			'module_message' => $module_message);
    }
	
	public function viewModuleAction()
    {
    	$this->loginDetails();
        //get the module id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){			
			$form = new ModuleForm($this->serviceLocator);
			$moduleModel = new Module();
			$form->bind($moduleModel);
					
			$moduleDetails = $this->programmeService->findModule($id);
			$programmeList = $this->programmeService->listSelectData($tableName = 'programmes', $columnName ='programme_name', $this->organisation_id, $this->username);
	        		 
	        return array(
				'form' => $form,
				'moduleDetails' => $moduleDetails,
				'programmeList' => $programmeList
			);
        }else{
        	return $this->redirect()->toRoute('addnewmodule');
        }
    }
	
	public function editModuleAction()
    {
    	$this->loginDetails();
        //get the module id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
			$form = new ModuleForm($this->serviceLocator);
			$moduleModel = new Module();
			$form->bind($moduleModel);
			
			$moduleDetails = $this->programmeService->findModule($id);
			$modules = $this->programmeService->listModules($this->organisation_id);
			$programmeList = $this->programmeService->listSelectData($tableName = 'programmes', $columnName ='programme_name', $this->organisation_id, $this->username);
	        
	        $request = $this->getRequest();
	         if ($request->isPost()) {
				 $form->setData($request->getPost());
	             if ($form->isValid()) {
	                       $form->setData($request->getPost());
	                       $data = $this->params()->fromPost();
	                        //to check and ensure that there are no duplicate modules entered for a programme
	                       $module_code = $data['module']['module_code'];
	                       $programmes_id = $data['module']['programmes_id'];
	                       $programme_module_check = $this->programmeService->crosscheckProgrammeModule($module_code, $programmes_id);
	                 try {
						 $this->programmeService->saveModule($moduleModel, $data);
						 $this->auditTrailService->saveAuditTrail("EDIT", "Academic Modules", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage('Module was successfully added');
						 return $this->redirect()->toRoute('addnewmodule');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
	             }
	         }
			 
	        return array(
				'form' => $form,
				'moduleDetails' => $moduleDetails,
				'modules' => $modules,
				'programmeList' => $programmeList,
			);
        }else{
        	 return $this->redirect()->toRoute('addnewmodule');
        }
    }
	
	public function updateModuleAction()
    {
        $this->loginDetails();
		
		$form = new ModuleForm($this->serviceLocator);
		$moduleModel = new Module();
		$form->bind($moduleModel);
		
		$empDetails = $this->programmeService->findEmpDetails($this->username);
		$empDetails = $empDetails->toArray();
        
        $request = $this->getRequest();
         if ($request->isPost()) {
			 $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->programmeService->save($moduleModel);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form);
    }
	
	public function assignModuleCoordinatorAction()
	{
		$this->loginDetails();	

		$form = new AssignModuleCoordinatorForm($this->serviceLocator);
					
		$moduleAllocated = $this->programmeService->getAllocatedModuleWithCoordinators($this->organisation_id);
		$programmeList = $this->programmeService->listSelectData($tableName = 'programmes', $columnName ='programme_name', $this->organisation_id, $this->username);
		$moduleList = $this->programmeService->listSelectData($tableName = 'academic_modules_allocation', $columnName ='module_title', $this->organisation_id, $this->username);
		$tutorList = $this->programmeService->listSelectData($tableName = 'employee_details', $columnName = NULL, $this->organisation_id, $this->username);
		$message = NULL;

        //set '0' so as to validate the form
		$tutorList = array(0=>'Please Select a Module Co-Ordinator')+$tutorList;
        $request = $this->getRequest();
         if ($request->isPost()) {
			 $form->setData($request->getPost());
             if ($form->isValid()) {
				 $data = $this->params()->fromPost();
				 $module_check = $this->programmeService->checkModuleCoordinatorAssignment($data);
				 if($module_check == 'Assigned'){
					 $this->flashMessenger()->addMessage('Module already allocated to Co-Ordinator');
					 $message = 'Failure';
				 } else {
					try {
						 $this->programmeService->saveModuleCoordinator($data);
						 $this->auditTrailService->saveAuditTrail("INSERT", "Academic Module Coordinator", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage('Module Coordinator was successfully added');
						 return $this->redirect()->toRoute('assignmodulecoordinator');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'moduleAllocated' => $moduleAllocated,
			'programmeList' => $programmeList,
			'moduleList' => $moduleList,
			'tutorList' => $tutorList,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
		);
	}
	
	public function deleteModuleCoordinatorAction()
	{
		$this->loginDetails();
        //get the module id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
			$this->programmeService->deleteModuleCoordinator($id);
	        return $this->redirect()->toRoute('assignmodulecoordinator');
        }else{
        	return $this->redirect()->toRoute('assignmodulecoordinator');
        }
	}
	
	public function assignModuleAction()
    {
    	$this->loginDetails();		
		$form = new TutorSearchForm();
		//set initial default to "0"
		$programmes_id = 0;
		$assignModuleTutorForm = NULL;
		$programmeList = $this->programmeService->listSelectData($tableName = 'programmes', $columnName ='programme_name', $this->organisation_id, $this->username);
		$moduleAllocated = array();
		$sectionList = $this->programmeService->listSelectData('student_section', 'section', $this->organisation_id, $this->username);
		$message = NULL;
		
        $request = $this->getRequest();
         if ($request->isPost()) {
			 $form->setData($request->getPost());
             if ($form->isValid()) {
				 $data = $this->params()->fromPost();
                 $programmes_id = $data['programmes_id'];
				 $academic_modules_id = $data['academic_modules_id'];
				 $assignModuleTutorForm = new AssignModuleForm($this->serviceLocator, $programmes_id, $academic_modules_id);
				 $moduleAllocated = $this->programmeService->getAssignedAcademicModulesBySemester($programmes_id, $academic_modules_id);
             }
         }
		 
        return array(
			'form' => $form,
			'assignModuleTutorForm' => $assignModuleTutorForm,
			'programmes_id' => $programmes_id,
			'programmeList' => $programmeList,
			'moduleAllocated' => $moduleAllocated,
			'sectionList' => $sectionList,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
		);
    }
	
	public function moduleTutorAssignmentAction()
    {
    	$this->loginDetails();		
		$message = NULL;
		$programmes_id = 0;
		$academic_modules_id = 0;
		$form = new AssignModuleForm($this->serviceLocator, $programmes_id, $academic_modules_id);
        $request = $this->getRequest();
         if ($request->isPost()) {
			 $form->setData($request->getPost());
             if ($form->isValid()) {
				 $form_data = $this->params()->fromPost();
				 //function to remove all "0" values for array
				 $data = array_filter($form_data, function ($value){
						return !in_array($value, ['', '0']); 
				 });
				 $this->programmeService->saveAcademicModuleToTutorAssignment($data);
				 $this->auditTrailService->saveAuditTrail("ADD", "Academic Modules Tutors Assignment", "ALL", "SUCCESS");
				 $this->flashMessenger()->addMessage('Module Tutors were successfully assigned');
				 return $this->redirect()->toRoute('assignmodule');
             }
         }
		 
        return array(
			'keyphrase' => $this->keyphrase,
			'message' => $message,
		);
    }
	
	//Pre-assign Modules to Tutors
	
	public function assignModuleToTutorsAction()
    {
    	$this->loginDetails();		
		$form = new AssignModuleTutorsForm($this->serviceLocator);
		
		//need to get the module tutors id and assign it to an array
		$moduleTutors = array();
					
		$moduleAllocated = $this->programmeService->getAssignedAcademicModules($this->organisation_id);
		$programmeList = $this->programmeService->listSelectData($tableName = 'programmes', $columnName ='programme_name', $this->organisation_id, $this->username);
		$moduleList = $this->programmeService->listSelectData($tableName = 'academic_modules_allocation', $columnName ='module_title', $this->organisation_id, $this->username);
		$tutorList = $this->programmeService->listSelectData($tableName = 'employee_details', $columnName = NULL, $this->organisation_id, $this->username);
		$message = NULL;

        //set '0' so as to validate the form
		$tutorList = array(0=>'Please Select a Module Tutor')+$tutorList;
        $request = $this->getRequest();
         if ($request->isPost()) {
			 $form->setData($request->getPost());
             if ($form->isValid()) {
				 $data = $this->params()->fromPost();
                 try {
					 $this->programmeService->saveModuleTutorsAssignment($data);
					 $this->auditTrailService->saveAuditTrail("INSERT", "Academic Module Tutors", "ALL", "SUCCESS");
					 $this->flashMessenger()->addMessage('Module Tutor was successfully added');
					 return $this->redirect()->toRoute('assignmodule');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'moduleAllocated' => $moduleAllocated,
			'programmeList' => $programmeList,
			'moduleList' => $moduleList,
			'tutorList' => $tutorList,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
		);
    }
	
	public function deleteModuleTutorAction()
	{
		$this->loginDetails();
        //get the module id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
			$this->programmeService->deleteModuleTutor($id);
			$this->auditTrailService->saveAuditTrail("DELETE", "Module Tutors Assignment", "ALL", "SUCCESS");
			$this->flashMessenger()->addMessage('Module Tutor was successfully deleted');
	        return $this->redirect()->toRoute('assignmoduletotutors');
        }else{
        	return $this->redirect()->toRoute('assignmoduletotutors');
        }
	}
	
	public function viewAcademicYearModuleTutorAction()
	{
		$this->loginDetails();
        
		//$moduleTutorDetails = $this->programmeService->listSelectData($tableName = 'academic_modules_allocation', $columnName ='module_title', $this->organisation_id, $this->username);
		$moduleTutorDetails = $this->programmeService->getAllocatedModuleWithTutors($this->organisation_id);

		$message = NULL;
		
		return array(
			'moduleTutorDetails' => $moduleTutorDetails,
			'message' => $message,
			'keyphrase' => $this->keyphrase
		);
	}
	
	public function deleteAcademicYearModuleTutorAction()
	{
		$this->loginDetails();
        //get the module id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){ 
			$this->programmeService->deleteAcademicYearModuleTutor($id);
			$this->auditTrailService->saveAuditTrail("DELETE", "Academic Module Tutors", "ALL", "SUCCESS");
			$this->flashMessenger()->addMessage('Academic Module Tutor was successfully deleted');
			return $this->redirect()->toRoute('viewacademicyeartutor');
        }else{
        	return $this->redirect()->toRoute('viewacademicyeartutor');
        }
	}
    
    /*public function uploadModuleTutorsAction()
    {
    	$this->loginDetails();
        $form = new UploadModuleTutorsForm();
        $uploadModel = new UploadModuleTutors();
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
                     $this->programmeService->saveModuleTutorFile($uploadModel, $this->organisation_id);
                     $this->auditTrailService->saveAuditTrail("INSERT", "Academic Module Tutors", "ALL", "SUCCESS");
                     $this->flashMessenger()->addMessage('Module Tutors were successfully uploaded');                     
                     return $this->redirect()->toRoute('assignmodule');
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
             'message' => $message,
         );
    }*/
    
    public function crossCheckModuleAssignmentAction()
    {
    	$this->loginDetails();

        return array(
                'moduleUnallocated' => $this->programmeService->getUnallocatedModule($this->organisation_id)
        );
    }
	
	public function academicYearModuleAction()
	{
		$this->loginDetails();
		$form = new AcademicYearModuleForm($this->serviceLocator);
		
		$moduleAllocated = $this->programmeService->getAllocatedModules($this->organisation_id);
		$moduleList = $this->programmeService->listSelectData($tableName = 'academic_modules', $columnName ='module_title', $this->organisation_id, $this->username);
		$programmeList = $this->programmeService->listSelectData($tableName = 'programmes', $columnName ='programme_name', $this->organisation_id, $this->username);
		        
        $request = $this->getRequest();
         if ($request->isPost()) {
			 $form->setData($request->getPost());
             if ($form->isValid()) {
                 $data = $this->params()->fromPost();
				 try {
					 $this->programmeService->saveModuleAllocation($data);
					 $this->flashMessenger()->addMessage('Modules were successfully allocated');
					 return $this->redirect()->toRoute('allocatemoduleacademicyear');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'moduleAllocated' => $moduleAllocated,
			'moduleList' => $moduleList,
			'programmeList' => $programmeList);
	}
	
	//to allocate in bulk
	public function allocateModuleAcademicYearAction()
	{
		$this->loginDetails();
		
		$form = new AcademicYearModuleForm($this->serviceLocator);
		$missingModulesForm = new MissingModuleForm();
		
		$message = NULL;
		
		$moduleAllocated = $this->programmeService->getAllocatedModules($this->organisation_id);
		$moduleList = $this->programmeService->listSelectData($tableName = 'academic_modules', $columnName ='module_title', $this->organisation_id, $this->username);
		$programmeList = $this->programmeService->listSelectData($tableName = 'programmes', $columnName ='programme_name', $this->organisation_id, $this->username);
		        
        $request = $this->getRequest();
         if ($request->isPost()) {
			 $module_check = $this->programmeService->getModuleAllocationPresent($this->organisation_id);
			 if($module_check != NULL){
				 $this->flashMessenger()->addMessage('Modules already allocated for Academic Year');
				 return $this->redirect()->toRoute('allocatemoduleacademicyear');
			 } else {
				 try {
					 $this->programmeService->saveAllModuleAllocation($this->organisation_id);
					 $this->auditTrailService->saveAuditTrail("INSERT", "Academic Modules Allocation", "ALL", "SUCCESS");
					 $this->flashMessenger()->addMessage('Modules were successfully allocated');
					 return $this->redirect()->toRoute('allocatemoduleacademicyear');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
			 }
			 
         }
		 
        return array(
			'form' => $form,
			'missingModulesForm' => $missingModulesForm,
			'moduleAllocated' => $moduleAllocated,
			'moduleList' => $moduleList,
			'programmeList' => $programmeList,
			'keyphrase' => $this->keyphrase,
			'message' => $message);
	}
	
	public function editAcademicYearModuleAction()
	{
		$this->loginDetails();
		//get the module id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$moduleDetails = $this->programmeService->findAllocatedModule($id);

			$form = new EditAcademicYearModuleForm($this->serviceLocator);
			
			$moduleAllocated = $this->programmeService->getAllocatedModules($this->organisation_id);
			$moduleList = $this->programmeService->listSelectData($tableName = 'academic_modules', $columnName ='module_title', $this->organisation_id, $this->username);
			$programmeList = $this->programmeService->listSelectData($tableName = 'programmes', $columnName ='programme_name', $this->organisation_id, $this->username);
			        
	        $request = $this->getRequest();
	         if ($request->isPost()) {
				 $form->setData($request->getPost());
	             if ($form->isValid()) {
	                $data = $this->params()->fromPost(); 
					 try {
						 $this->programmeService->saveModuleAllocation($data);
						 $this->auditTrailService->saveAuditTrail("EDIT", "Academic Modules Allocation", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage('Academic Modules were successfully edited');
						 return $this->redirect()->toRoute('allocatemoduleacademicyear');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
	             }
	         }
			 
	        return array(
				'form' => $form,
				'moduleDetails' => $moduleDetails,
				'moduleAllocated' => $moduleAllocated,
				'moduleList' => $moduleList,
				'programmeList' => $programmeList,
			);
        }else{
        	return $this->redirect()->toRoute('allocatemoduleacademicyear');
        }
	}
	
	public function viewAcademicYearModuleAction()
	{
		$this->loginDetails();
	   $form = new AcademicCalendarSearchForm();
	   
	   $programmeList = $this->programmeService->listSelectData($tableName = 'programmes', $columnName ='programme_name', $this->organisation_id, $this->username);
	   $semesterList = $this->programmeService->getSemesterList($this->organisation_id);
	   $moduleDetail = array();
	   $academic_year =  NULL;
	   //set the default to 2
	   $assessment_components = array();
	   
	   $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$programmes_id = $this->getRequest()->getPost('programmes_id');
				 //academic year is actually the semester
				$semester = $this->getRequest()->getPost('semester');
				$assessment_components = $this->programmeService->getAssessmentComponentNumber($programmes_id, $semester);
				$moduleDetail = $this->programmeService->getAcademicYearModule($programmes_id, $semester);
             }
         }
		 else {
			 $moduleDetail = array();
			 $academic_year = NULL;
		 }
		 
		
		return array(
            'form' => $form,
			'academic_year' => $academic_year,
			'moduleDetail' => $moduleDetail,
			'assessment_components' => $assessment_components,
			'organisation_id' => $this->organisation_id,
			'programmeList' => $programmeList,
			'semesterList' => $semesterList
            );
	}
	
	//to allocate in missing modules
	public function allocateMissingModulesAction()
	{
		$this->loginDetails();
		$form = new MissingModuleForm();
		
		$message = NULL;
		        
        $request = $this->getRequest();
         if ($request->isPost()) {
			 try {
				 $this->programmeService->saveMissingModuleAllocation($this->organisation_id);
				 $this->auditTrailService->saveAuditTrail("INSERT", "Missing Academic Modules Allocation", "ALL", "SUCCESS");
				 $this->flashMessenger()->addMessage('Missing Modules were successfully allocated');
				 return $this->redirect()->toRoute('allocatemoduleacademicyear');
			 }
			 catch(\Exception $e) {
					 die($e->getMessage());
					 // Some DB Error happened, log it and let the user know
			 }
         }
		 
        return array(
			'form' => $form,
			'keyphrase' => $this->keyphrase,
			'message' => $message);
	}
	
	//Allocate Elective Modules
	public function electiveModuleAllocationAction()
	{
		$this->loginDetails();
		
		$studentList = array();
		$module_data['programmes_id'] = NULL;
		$module_data['academic_modules_allocation_id'] = NULL;
		$module_data['section_id'] = NULL;
		
		$assignElectiveModuleForm = new AssignElectiveModuleForm($this->serviceLocator, $studentList);   
		
		$form = new ElectiveModuleSearchForm($this->serviceLocator);
		$request = $this->getRequest();
           if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$data = $this->params()->fromPost();
				
				$module_data['programmes_id'] = $data['programmes_id'];
				$module_data['academic_modules_allocation_id'] = $data['academic_modules_allocation_id'];
				$module_data['section_id'] = $data['section_id'];					
                //$batch_year is not used by the getStudentList function. Set as NULL
				$studentList = $this->programmeService->getStudentList($studentName = NULL, $module_data['section_id'], $module_data['academic_modules_allocation_id'], $module_data['programmes_id'], $batch_year=4, $marks = 'continuous_assessment',$status='elective_allocation');
				$assignElectiveModuleForm = new AssignElectiveModuleForm($this->serviceLocator, $studentList);       
             }
         }
		 
		 return array(
            'form' => $form,
			'assignElectiveModuleForm' => $assignElectiveModuleForm,
			'studentList' => $studentList,
			'module_data' => $module_data,
            'keyphrase' => $this->keyphrase,
            );
		
	}
	
	public function assignElectiveModuleAction()
	{
		$this->loginDetails();
		$form = new AssignElectiveModuleForm($this->serviceLocator, $studentList= array());  
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 $data = $this->getRequest()->getPost();
				 $academic_modules_allocation_id = $this->getRequest()->getPost('academic_modules_allocation_id');
                 try {
					 $this->programmeService->saveStudentElectiveModules($data, $academic_modules_allocation_id, $this->organisation_id);
					 $this->auditTrailService->saveAuditTrail("INSERT", "Student Elective Modules", "ALL", "SUCCESS");
					 $this->flashMessenger()->addMessage('Elective Modules was successfully added');
					 return $this->redirect()->toRoute('electivemoduleallocation');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
		else{
             	$this->flashMessenger()->addMessage('Session has expired. Submit form again');
				return $this->redirect()->toRoute('electivemoduleallocation');
             }
         }
		 
        return array('form' => $form);
		
	}
	
    /*
     * TO allocate Assessment Component Types
     */
    public function assessmentComponentTypeAction()
    {
    	$this->loginDetails();
		
		$componentForm = new AssessmentComponentForm($this->serviceLocator);
		//$moduleModel = new AssessmentComponent();
		//$componentForm->bind($moduleModel);
		
		$typeForm = new AssessmentComponentTypeForm();
		$message = NULL;
				
		$programmeList = $this->programmeService->listSelectData($tableName = 'programmes', $columnName ='programme_name', $this->organisation_id, $this->username);
		
		$componentList = $this->programmeService->getAssessmentComponent($this->organisation_id);
		$componentTypeList = $this->programmeService->getAssessmentComponentType($this->organisation_id);
		$assessmentList = $this->programmeService->listAll($tableName = 'academic_assessment', $this->organisation_id);
		$assessmentType = $this->programmeService->listSelectData($tableName = 'assessment_component', $columnName = 'assessment', $this->organisation_id, $this->username);
		$moduleList = $this->programmeService->getModuleTutorList($this->username);
		$moduleCode = $this->programmeService->listSelectData($tableName = 'academic_modules', $columnName = 'module_code', $this->organisation_id, $this->username);
		$assessmentComponentTypeList = $this->programmeService->listSelectData($tableName = 'assessment_component_types', $columnName = 'assessment_component_type', $this->organisation_id, $this->username);
		
		$request = $this->getRequest();
        if ($request->isPost()) {
			$componentForm->setData($request->getPost());
            if ($componentForm->isValid()) {
				//check if assessment component has already been assigned to avoid duplication
				$assessment_components = $this->getRequest()->getPost('assessmentcomponent');
				$programmes_id = $assessment_components['programmes_id'];
				$assessment = $assessment_components['assessment'];
				$component_exists = $this->programmeService->checkAssessmentComponent($assessment_components);
				if($component_exists != 0){
					$message = 'Failure';
					$this->flashMessenger()->addMessage('Assessment Type for Programme has already been done');
				}else{
					try {
					 $this->programmeService->saveAssessmentComponent($moduleModel);
					 $this->flashMessenger()->addMessage('Assessment Component was successfully added');
					 return $this->redirect()->toRoute('assessmentcomponent');
					}
					catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					}
				}
                
			}
         }
			 
        return array(
			'organisation_id' => $this->organisation_id,
			'componentForm' => $componentForm,
			'componentTypeList' => $componentTypeList,
			'typeForm' => $typeForm,
			'message' => $message,
			'programmeList' => $programmeList,
			'moduleList' => $moduleList,
			'moduleCode' => $moduleCode,
			'assessmentType' => $assessmentType,
			'componentList' => $componentList,
			'assessmentList' => $assessmentList,
			'assessmentComponentTypeList' => $assessmentComponentTypeList);
    }
    
    /*
     * To add assessment component for each module
     */
    
    public function moduleAssessmentComponentAction()
    {
        $this->loginDetails();
		
		$componentForm = new AssessmentComponentForm($this->serviceLocator);
		
		$message = NULL;
				
		$programmeList = $this->programmeService->listSelectData($tableName = 'programmes', $columnName ='programme_name', $this->organisation_id, $this->username);
		
		$componentList = $this->programmeService->getAcademicModuleAssessment($this->organisation_id);
		$componentTypeList = $this->programmeService->getAssessmentComponentType($this->organisation_id);
		$assessmentList = $this->programmeService->listAll($tableName = 'academic_assessment', $this->organisation_id);
		$assessmentType = $this->programmeService->listSelectData($tableName = 'assessment_component', $columnName = 'assessment', $this->organisation_id, $this->username);
		$moduleList = $this->programmeService->getModuleTutorList($this->username);
		$moduleCode = $this->programmeService->listSelectData($tableName = 'academic_modules', $columnName = 'module_code', $this->organisation_id, $this->username);
		$assessmentComponentTypeList = $this->programmeService->listSelectData($tableName = 'assessment_component_types', $columnName = 'assessment_component_type', $this->organisation_id, $this->username);
		
		$request = $this->getRequest();
        if ($request->isPost()) {
			$componentForm->setData($request->getPost());
            if ($componentForm->isValid()) {
				//check if assessment component has already been assigned to avoid duplication
				$assessment_components['programmes_id'] = $this->getRequest()->getPost('programmes_id');
				$assessment_components['academic_modules_id'] = $this->getRequest()->getPost('academic_modules_id');
				$assessment_components['assessment_component_types_id'] = $this->getRequest()->getPost('assessment');
				$component_exists = $this->programmeService->checkAssessmentComponent($assessment_components);
				if($component_exists != 0){
					$message = 'Failure';
					$this->flashMessenger()->addMessage('Assessment Type for Programme has already been done');
				}else{
					try {
						$data = $this->params()->fromPost();
	                    $this->programmeService->saveAssessmentComponent($data);
	                    $this->auditTrailService->saveAuditTrail("INSERT", "Academic Modules Assessment", "ALL", "SUCCESS");
						$this->flashMessenger()->addMessage('Assessment Component was successfully added');
						return $this->redirect()->toRoute('moduleassessmentcomponent');
					}
					catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					}
				}
                
			}
         }
			 
        return array(
			'organisation_id' => $this->organisation_id,
			'componentForm' => $componentForm,
			'componentTypeList' => $componentTypeList,
			'message' => $message,
			'programmeList' => $programmeList,
			'moduleList' => $moduleList,
			'moduleCode' => $moduleCode,
			'assessmentType' => $assessmentType,
			'componentList' => $componentList,
			'assessmentList' => $assessmentList,
			'assessmentComponentTypeList' => $assessmentComponentTypeList,
			'keyphrase' => $this->keyphrase,
		);
    }
    
    //Old function. Kept in case there is a change in flow
    public function assessmentComponentAction()
    {
        $this->loginDetails();
		
		$componentForm = new AssessmentComponentForm($this->serviceLocator);
		$moduleModel = new AssessmentComponent();
		$componentForm->bind($moduleModel);
		
		$assessmentForm = new ContinuousAssessmentForm($this->serviceLocator);
		$typeForm = new AssessmentComponentTypeForm();
		$message = NULL;
				
		$programmeList = $this->programmeService->listSelectData($tableName = 'programmes', $columnName ='programme_name', $this->organisation_id, $this->username);
		
		$componentList = $this->programmeService->getAssessmentComponent($this->organisation_id);
		$componentTypeList = $this->programmeService->getAssessmentComponentType($this->organisation_id);
		$assessmentList = $this->programmeService->listAll($tableName = 'academic_assessment', $this->organisation_id);
		$assessmentType = $this->programmeService->listSelectData($tableName = 'assessment_component', $columnName = 'assessment', $this->organisation_id, $this->username);
		$moduleList = $this->programmeService->getModuleTutorList($this->username);
		$moduleCode = $this->programmeService->listSelectData($tableName = 'academic_modules', $columnName = 'module_code', $this->organisation_id, $this->username);
		$assessmentComponentTypeList = $this->programmeService->listSelectData($tableName = 'assessment_component_types', $columnName = 'assessment_component_type', $this->organisation_id, $this->username);
		
		$request = $this->getRequest();
        if ($request->isPost()) {
			$componentForm->setData($request->getPost());
            if ($componentForm->isValid()) {
				//check if assessment component has already been assigned to avoid duplication
				$assessment_components = $this->getRequest()->getPost('assessmentcomponent');
				$programmes_id = $assessment_components['programmes_id'];
				$assessment = $assessment_components['assessment'];
				$component_exists = $this->programmeService->checkAssessmentComponent($assessment_components);
				if($component_exists != 0){
					$message = 'Failure';
					$this->flashMessenger()->addMessage('Assessment Type for Programme has already been done');
				}else{
					try {
					 $this->programmeService->saveAssessmentComponent($moduleModel);
					 $this->auditTrailService->saveAuditTrail("INSERT", "Academic Modules Assessment", "ALL", "SUCCESS");
					 $this->flashMessenger()->addMessage('Assessment Component was successfully added');
					 return $this->redirect()->toRoute('assessmentcomponent');
					}
					catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					}
				}
                
			}
         }
			 
        return array(
			'organisation_id' => $this->organisation_id,
			'componentForm' => $componentForm,
			'componentTypeList' => $componentTypeList,
			'assessmentForm' => $assessmentForm,
			'typeForm' => $typeForm,
			'message' => $message,
			'programmeList' => $programmeList,
			'moduleList' => $moduleList,
			'moduleCode' => $moduleCode,
			'assessmentType' => $assessmentType,
			'componentList' => $componentList,
			'assessmentList' => $assessmentList,
			'keyphrase' => $this->keyphrase,
			'assessmentComponentTypeList' => $assessmentComponentTypeList);
    }
		
	public function editAssessmentComponentAction()
	{
		$this->loginDetails();
		//get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new EditAssessmentComponentForm();
			$moduleModel = new EditAssessmentComponent();
			$form->bind($moduleModel);
			
			$programmeList = $this->programmeService->listSelectData($tableName = 'programmes', $columnName ='programme_name', $this->organisation_id, $this->username);
			$assessmentComponentTypeList = $this->programmeService->listSelectData($tableName = 'assessment_component_types', $columnName = 'assessment_component_type', $this->organisation_id, $this->username);
			$assessmentDetails = $this->programmeService->getAcademicAssessmentComponentDetails($id);
	        
	        $request = $this->getRequest();
	         if ($request->isPost()) {
				 $form->setData($request->getPost());
	             if ($form->isValid()) {
	                 try {
						 $this->programmeService->saveEditedAssessmentComponent($moduleModel);
						 $this->auditTrailService->saveAuditTrail("EDIT", "Academic Modules Assessment", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('moduleassessmentcomponent');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
	             }
	         }
			 return array(
				'form' => $form,
				'programmeList' => $programmeList,
				'assessmentComponentTypeList' => $assessmentComponentTypeList,
				'assessmentDetails' => $assessmentDetails,
			);
        }else{
        	return $this->redirect()->toRoute('moduleassessmentcomponent');
        }
	}
	
	public function addAssessmentComponentTypeAction()
	{
		$this->loginDetails();
		$form = new AssessmentComponentTypeForm();
		$moduleModel = new AssessmentComponentType();
		$form->bind($moduleModel);
                
        $assessmentComponentTypes = $this->programmeService->listSelectData('assessment_component_types', 'assessment_component_type', $this->organisation_id, $this->username);
		        
        $request = $this->getRequest();
         if ($request->isPost()) {
			 $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->programmeService->saveComponentType($moduleModel);
					 $this->flashMessenger()->addMessage('Assessment Component Type was successfully added');
					 return $this->redirect()->toRoute('addassessmentcomponenttypes');
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
                        'assessmentComponentTypes' => $assessmentComponentTypes
                        );
	}
	
	public function addAssessmentMarkAllocationAction()
	{
		$this->loginDetails();
		
		$assessmentForm = new ContinuousAssessmentForm($this->serviceLocator);
		$assessmentList = $this->programmeService->getTutorAssessmentList($this->username);
		$assessmentType = $this->programmeService->listSelectData($tableName = 'assessment_component', $columnName = 'assessment', $this->organisation_id, $this->username);
		$moduleList = $this->programmeService->getModuleCoordinatorList($this->username);

		$message = NULL;
		        
        $request = $this->getRequest();
         if ($request->isPost()) {
			 $assessmentForm->setData($request->getPost());
             if ($assessmentForm->isValid()) { 
				 $data = $this->params()->fromPost(); 
                 try {
					 $this->programmeService->saveMarkAllocation($data);
					 $this->auditTrailService->saveAuditTrail("INSERT", "Academic Assessment", "ALL", "SUCCESS");
					 $this->flashMessenger()->addMessage('Assessment Mark Allocation was successfully added');
					 return $this->redirect()->toRoute('addallocationmark');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
		 return array(
			'organisation_id' => $this->organisation_id,
			'assessmentForm' => $assessmentForm,
			'moduleList' => $moduleList,
			'assessmentType' => $assessmentType,
			'keyphrase' => $this->keyphrase,
			'assessmentList' => $assessmentList,
			'message' => $message,
		);
	}
	
	public function editAssessmentMarkAllocationAction()
	{
		$this->loginDetails();
		//get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){			
			$assessmentForm = new ContinuousAssessmentForm($this->serviceLocator);
			$assessmentDetails = $this->programmeService->getAssessmentMarkDetails($id);
			$assessmentList = $this->programmeService->getTutorAssessmentList($this->username);
			$assessmentType = $this->programmeService->listSelectData($tableName = 'assessment_component', $columnName = 'assessment', $this->organisation_id, $this->username);
			$moduleList = $this->programmeService->getModuleCoordinatorList($this->username);

			$message = NULL;
			        
	        $request = $this->getRequest();
	         if ($request->isPost()) {
				 $assessmentForm->setData($request->getPost());
	             if ($assessmentForm->isValid()) {
					 $data = $this->params()->fromPost();
	                 try {
						 $this->programmeService->saveMarkAllocation($data);
						 $this->auditTrailService->saveAuditTrail("EDIT", "Academic Assessment", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage('Assessment Mark Allocation was successfully edited');
						 return $this->redirect()->toRoute('assessmentcomponent');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
	             }
	         }
			 return array(
				'organisation_id' => $this->organisation_id,
				'assessmentForm' => $assessmentForm,
				'assessmentDetails' => $assessmentDetails,
				'moduleList' => $moduleList,
				'assessmentType' => $assessmentType,
				'assessmentList' => $assessmentList,
				'message' => $message,
			);
        }else{
        	return $this->redirect()->toRoute('addallocationmark');
        }
	}


	public function deleteAssessmentMarkAllocationAction()
	{
		$this->loginDetails();
		 
		 //get the id of the travel authorization proposal
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$message = NULL;
			
			//Check whether marks has been enterd for this particular academic assessment or not
			$check_academic_assessment = $this->programmeService->crossCheckAcademicAssessmentMarks($id);
			if($check_academic_assessment){
				$message = 'Failure';
				$this->flashMessenger()->addMessage("Sorry! You can't delete this assessment since you have already entered marks for this assessment");
				return $this->redirect()->toRoute('addallocationmark');
			}else{
				try {
					 $result = $this->programmeService->deleteAssessmentMarkAllocation($id);
					  $this->auditTrailService->saveAuditTrail("DELETE", "Academic Assessment", "ALL", "SUCCESS");
					 $this->flashMessenger()->addMessage('Assessment Mark Allocation was successfully deleted');
					 return $this->redirect()->toRoute('addallocationmark');
					 //return $this->redirect()->toRoute('emptraveldetails');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
			}
			return array(
				'id' => $id,
				'message' => $message,
			);
	
		} else {
			return $this->redirect()->toRoute('addallocationmark');
		}
	}
	
	public function addDpdMarkAllocationAction()
	{
		$this->loginDetails();
		
		$assessmentForm = new DpdAssessmentForm($this->serviceLocator);
		$assessmentType = $this->programmeService->listSelectData($tableName = 'assessment_component_types', $columnName = 'assessment_component_type', $this->organisation_id, $this->username);
		$moduleList = $this->programmeService->getModuleCoordinatorList($this->username);
		$assessmentList = $this->programmeService->getDpdAssessmentList($this->organisation_id);

		$message = NULL;
		        
        $request = $this->getRequest();
         if ($request->isPost()) {
			 $assessmentForm->setData($request->getPost());
             if ($assessmentForm->isValid()) { 
				 $data = $this->params()->fromPost(); 
                 try {
					 $this->programmeService->saveDpdMarkAllocation($data);
					 $this->auditTrailService->saveAuditTrail("INSERT", "Academic Assessment", "ALL", "SUCCESS");
					 $this->flashMessenger()->addMessage('Assessment Mark Allocation was successfully added');
					 return $this->redirect()->toRoute('dpdmarkallocation');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
		 return array(
			'organisation_id' => $this->organisation_id,
			'assessmentForm' => $assessmentForm,
			'moduleList' => $moduleList,
			'assessmentType' => $assessmentType,
			'keyphrase' => $this->keyphrase,
			'assessmentList' => $assessmentList,
			'message' => $message,
		);
	}
	
	public function editDpdMarkAllocationAction()
	{
		$this->loginDetails();
		//get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){			
			$assessmentForm = new ContinuousAssessmentForm($this->serviceLocator);
			$assessmentDetails = $this->programmeService->getAssessmentMarkDetails($id);
			$assessmentList = $this->programmeService->getTutorAssessmentList($this->username);
			$assessmentType = $this->programmeService->listSelectData($tableName = 'assessment_component', $columnName = 'assessment', $this->organisation_id, $this->username);
			$moduleList = $this->programmeService->getModuleCoordinatorList($this->username);

			$message = NULL;
			        
	        $request = $this->getRequest();
	         if ($request->isPost()) {
				 $assessmentForm->setData($request->getPost());
	             if ($assessmentForm->isValid()) {
					 $data = $this->params()->fromPost();
	                 try {
						 $this->programmeService->saveMarkAllocation($data);
						 $this->auditTrailService->saveAuditTrail("EDIT", "Academic Assessment", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage('Assessment Mark Allocation was successfully edited');
						 return $this->redirect()->toRoute('assessmentcomponent');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
	             }
	         }
			 return array(
				'organisation_id' => $this->organisation_id,
				'assessmentForm' => $assessmentForm,
				'assessmentDetails' => $assessmentDetails,
				'moduleList' => $moduleList,
				'assessmentType' => $assessmentType,
				'assessmentList' => $assessmentList,
				'message' => $message,
			);
        }else{
        	return $this->redirect()->toRoute('addallocationmark');
        }
	}
	
	public function deleteDpdMarkAllocationAction()
	{
		$this->loginDetails();
		 
		 //get the id of the travel authorization proposal
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$message = NULL;
			
			//Check whether marks has been enterd for this particular academic assessment or not
			$check_academic_assessment = $this->programmeService->crossCheckAcademicAssessmentMarks($id);
			if($check_academic_assessment){
				$message = 'Failure';
				$this->flashMessenger()->addMessage("Sorry! You can't delete this assessment since you have already entered marks for this assessment");
				return $this->redirect()->toRoute('addallocationmark');
			}else{
				try {
					 $result = $this->programmeService->deleteAssessmentMarkAllocation($id);
					  $this->auditTrailService->saveAuditTrail("DELETE", "Academic Assessment", "ALL", "SUCCESS");
					 $this->flashMessenger()->addMessage('Assessment Mark Allocation was successfully deleted');
					 return $this->redirect()->toRoute('addallocationmark');
					 //return $this->redirect()->toRoute('emptraveldetails');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
			}
			return array(
				'id' => $id,
				'message' => $message,
			);
	
		} else {
			return $this->redirect()->toRoute('addallocationmark');
		}
	}
	
	public function allocateDpdMarksAction()
	{
		$this->loginDetails();
		$form = new DpdMarkAllocationForm();
		
		$message = NULL;
		        
        $request = $this->getRequest();
         if ($request->isPost()) {
			 try {
				 $this->programmeService->allocateDpdMarks($this->organisation_id);
				 $this->auditTrailService->saveAuditTrail("INSERT", "Mass Allocation of marks as per DPD", "ALL", "SUCCESS");
				 $this->flashMessenger()->addMessage('Marks as per DPD successfully allocated');
				 return $this->redirect()->toRoute('allocatedpdmarks');
			 }
			 catch(\Exception $e) {
					 die($e->getMessage());
					 // Some DB Error happened, log it and let the user know
			 }
         }
		 
        return array(
			'form' => $form,
			'keyphrase' => $this->keyphrase,
			'message' => $message);
	}

	
	public function academicAssessmentAction()
	{
		$this->loginDetails();
						
		//need to get the student count for entering the marks
		$studentCount = 0;
		//preset values
		$studentList = array();
		$weightage = NULL;
		$assessment_marks = 0;
		$batch = NULL;
		$programmesId = NULL;
		$continuous_assessment_id = NULL;
		$assessment = NULL;
		$section = 'A';
		$academic_year = NULL;
		$crossCheckAssignment = NULL;
		$sectionList = $this->programmeService->listSelectData('student_section', 'section', $this->organisation_id, $this->username);

		$message = NULL;
		
	   $form = new SearchForm($this->serviceLocator);
	   
	   $request = $this->getRequest();
           if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$academic_modules_allocation_id = $this->getRequest()->getPost('assessment_component_id');	
				$assessment = $this->getRequest()->getPost('assessment_type');
				$section = $this->getRequest()->getPost('section');
				$continuous_assessment_id = $this->getRequest()->getPost('assessment_type');
				//check if tutor is assigned to section and/or whether assignment has already been marked
				$crossCheckAssignment = $this->programmeService->crossCheckAsssignment($academic_modules_allocation_id, $assessment, $section, $this->username);
				if($crossCheckAssignment == NULL || $crossCheckAssignment == 'Assigned and Marked'){
					$this->flashMessenger()->addMessage('Assessment Marks have already been added. If you see following student in the list, it means that this students mark is not yet added.');
					$message = 'Failure';

					$year = $this->programmeService->getBatch($academic_modules_allocation_id, $assessment, $assessment_for = 'continuous_assessment');

					foreach($year as $data){
							$weightage = $data['assessment_weightage'];
							$assessment_marks = $data['assessment_marks'];
							//$continuous_assessment_id = $data['academic_assessment_id'];
							$programmesId = $data['programmes_id'];
							$batch = $data['year'];
					}
					//to ensure we only get an number and not "1st Year" etc
					//then subtract 1 as we need the year
					preg_match_all('!\d+!', $batch, $batch_year);
					foreach($batch_year as $key => $value){
							foreach($value as $key1=> $value1){
									$batch = $value1;
							}
					}
					$present_month = date('m');
					$current_semester = $this->programmeService->getSemester($this->organisation_id);
					if(($current_semester['academic_event'] == 'Spring')){
						$academic_year = $current_semester['academic_year'];
					}else{
						$academic_year = $current_semester['academic_year'];
					}
					$studentList = $this->programmeService->getMissingStudentList($continuous_assessment_id, $studentName = NULL, $section, $academic_modules_allocation_id, $programmesId, $academic_year, $marks = 'continuous_assessment',$status = NULL);
					if($studentList){
						$studentCount = count($studentList);	
					} else {
						$studentList = array();
						$studentCount = count($studentList);
					}
					

				} else{
					//need to get which batch the module is for
					$year = $this->programmeService->getBatch($academic_modules_allocation_id, $assessment, $assessment_for = 'continuous_assessment');
					foreach($year as $data){
							$weightage = $data['assessment_weightage'];
							$assessment_marks = $data['assessment_marks'];
							//$continuous_assessment_id = $data['academic_assessment_id'];
							$programmesId = $data['programmes_id'];
							$batch = $data['year'];
					}
					//to ensure we only get an number and not "1st Year" etc
					//then subtract 1 as we need the year
					preg_match_all('!\d+!', $batch, $batch_year);
					foreach($batch_year as $key => $value){
							foreach($value as $key1=> $value1){
									$batch = $value1;
							}
					}
					$present_month = date('m');
					$current_semester = $this->programmeService->getSemester($this->organisation_id);
					if(($current_semester['academic_event'] == 'Spring')){
						$academic_year = $current_semester['academic_year'];
					}else{
						$academic_year = $current_semester['academic_year'];
					}
					$studentList = $this->programmeService->getStudentList($studentName = NULL, $section, $academic_modules_allocation_id, $programmesId, $academic_year, $marks = 'continuous_assessment',$status = NULL);
					
					if($studentList){
						$studentCount = count($studentList);	
					} else {
						$studentList = array();
						$studentCount = count($studentList);
					}
				}
                        
             }
         }
		 
		$marksForm = new MarkEntryForm($studentCount, $assessment_marks);
		
		$moduleList = $this->programmeService->listSelectData($tableName = 'academic_modules_allocation', $columnName = 'module_title', $this->organisation_id, $this->username);
		
		return array(
            'form' => $form,
            'message' => $message,
			'studentList' => $studentList,
            'sectionList' => $sectionList,
			'marksForm' => $marksForm,
			'studentCount' => $studentCount,
			'moduleList' => $moduleList,
			'weightage' => $weightage,
			'assessment_marks' => $assessment_marks,
			'batch' => $academic_year,
			'continuous_assessment_id' => $continuous_assessment_id,
			'programmesId' => $programmesId,
			'assessment' => $assessment,
            'section' => $section,
            'crossCheckAssignment' => $crossCheckAssignment,
            'keyphrase' => $this->keyphrase,
            );
	}
	
	public function addAssessmentMarksAction()
	{
		$this->loginDetails();
    	$form = new MarkEntryForm($studentCount= 'null', 0);

		//var_dump($crossCheckAssignment); die();

        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                    $data = $this->extractFormData();
                    $programmesId = $this->getRequest()->getPost('programmes_id');
                    $continuous_assessment_id = $this->getRequest()->getPost('continuous_assessment_id');
                    $assessment_type = $this->getRequest()->getPost('assessment_type');
                    $batch = $this->getRequest()->getPost('batch');
                    $section = $this->getRequest()->getPost('section');
                 try {
                    $this->programmeService->saveAssessmentMarks($data, $programmesId, $batch, $section, $continuous_assessment_id, $assessment_type);
                    $this->flashMessenger()->addMessage('Assessment Marks was successfully added');
                    return $this->redirect()->toRoute('viewassessmentmarks');
                }
                catch(\Exception $e) {
                                die($e->getMessage());
                                // Some DB Error happened, log it and let the user know
                }
             }
		else{
             	$this->flashMessenger()->addMessage('Session has expired. Submit form again');
				return $this->redirect()->toRoute('academicassessment');
             }
         }
		 
        return array('form' => $form);
	}
	
	public function viewAssessmentMarksAction()
	{
		$this->loginDetails();				
		//need to get the student count for entering the marks
	    $studentCount = 0;
		$studentAssessmentList = array();
		$studentNameList = array();		
		$moduleList = $this->programmeService->listSelectData($tableName = 'academic_modules_allocation', $columnName = 'module_title', $this->organisation_id, $this->username);
		$sectionList = $this->programmeService->listSelectData('student_section', 'section', $this->organisation_id, $this->username);
		
	   $form = new SearchForm($this->serviceLocator);
	   $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$academic_modules_allocation_id = $this->getRequest()->getPost('assessment_component_id');
                $section = $this->getRequest()->getPost('section');
				$studentAssessmentList = $this->programmeService->getStudentAssessmentMarks($academic_modules_allocation_id, $section, 'continuous_assessment');
				$studentNameList = $this->programmeService->getStudentNameList($academic_modules_allocation_id, $section);
             }
         }
		 
		 
		return array(
            'form' => $form,
			'studentAssessmentList' => $studentAssessmentList,
			'studentNameList' => $studentNameList,
			'moduleList' => $moduleList,
            'sectionList' => $sectionList,
            'keyphrase' => $this->keyphrase,
            );
	}
	
	public function editAssessmentMarksAction()
	{
		$this->loginDetails();
						
		//need to get the student count for entering the marks
		$studentCount = 0;
		//preset values
		$studentList = array();
		$weightage = NULL;
		$assessment_marks = 0;
		$batch = NULL;
		$programmesId = NULL;
		$continuous_assessment_id = NULL;
		$assessment = NULL;
		$section = 'A';
		$academic_year = NULL;
		$sectionList = $this->programmeService->listSelectData('student_section', 'section', $this->organisation_id, $this->username);
		$message = NULL;
		$studentMarks = array();
		
	   $form = new SearchForm($this->serviceLocator);
	   
	   $request = $this->getRequest();
           if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$academic_modules_allocation_id = $this->getRequest()->getPost('assessment_component_id');	
				$assessment = $this->getRequest()->getPost('assessment_type');
				$section = $this->getRequest()->getPost('section');
				$continuous_assessment_id = $this->getRequest()->getPost('assessment_type');
				//check if tutor is assigned to section and/or whether assignment has already been marked
				$crossCheckAssignment = $this->programmeService->crossCheckAsssignment($academic_modules_allocation_id, $assessment, $section, $this->username);
				//var_dump($crossCheckAssignment); die();
				/*if($crossCheckAssignment == NULL || $crossCheckAssignment == 'Assigned'){
					$this->flashMessenger()->addMessage('Assessment Marks is not yet added OR Section selected is wrong');
					$message = 'Failure';
				} else if ($crossCheckAssignment == 'Assigned and Marked'){*/
					//need to get which batch the module is for
					$year = $this->programmeService->getBatch($academic_modules_allocation_id, $assessment, $assessment_for = 'continuous_assessment');
					foreach($year as $data){
							$weightage = $data['assessment_weightage'];
							$assessment_marks = $data['assessment_marks'];
							//$continuous_assessment_id = $data['academic_assessment_id'];
							$programmesId = $data['programmes_id'];
							$batch = $data['year'];
					}
					//to ensure we only get an number and not "1st Year" etc
					//then subtract 1 as we need the year
					preg_match_all('!\d+!', $batch, $batch_year);
					foreach($batch_year as $key => $value){
							foreach($value as $key1=> $value1){
									$batch = $value1;
							}
					}
					$present_month = date('m');
					$current_semester = $this->programmeService->getSemester($this->organisation_id);
					if(($current_semester['academic_event'] == 'Spring')){
						$academic_year = $current_semester['academic_year'];
					}else{
						$academic_year = $current_semester['academic_year'];
					}

					$studentList = $this->programmeService->getStudentList($studentName = NULL, $section, $academic_modules_allocation_id, $programmesId, $academic_year, $marks = 'continuous_assessment',$status = NULL);
					$studentCount = count($studentList);
					$studentMarks = $this->programmeService->getStudentMarks($assessment, $section);
				}
                        
            //}
         }
		 
		$marksForm = new EditAssessmentForm($studentList, $assessment_marks);
		
		$moduleList = $this->programmeService->listSelectData($tableName = 'academic_modules_allocation', $columnName = 'module_title', $this->organisation_id, $this->username);
		
		return array(
            'form' => $form,
            'message' => $message,
			'studentList' => $studentList,
            'sectionList' => $sectionList,
			'marksForm' => $marksForm,
			'studentCount' => $studentCount,
			'studentMarks' => $studentMarks,
			'moduleList' => $moduleList,
			'weightage' => $weightage,
			'assessment_marks' => $assessment_marks,
			'batch' => $academic_year,
			'continuous_assessment_id' => $continuous_assessment_id,
			'programmesId' => $programmesId,
			'assessment' => $assessment,
            'section' => $section,
            'keyphrase' => $this->keyphrase,
            );
	}
	
	public function editStudentAssessmentMarkAction()
	{
		$this->loginDetails();
		//get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
		//decrypt not working
        //$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		$id = $id_from_route;

        if(is_numeric($id)){
        	$markDetails = $this->programmeService->editStudentAssessmentMark($id);
			$form = new EditAssessmentMarkForm($markDetails['assessment_marks']);
			
			$markDetails = $this->programmeService->editStudentAssessmentMark($id);
			
			$request = $this->getRequest();
	         if ($request->isPost()) {
				 $form->setData($request->getPost());
	             if ($form->isValid()) {
	                 try {
						 $data['id'] = $this->getRequest()->getPost('id');
						 $data['marks'] = $this->getRequest()->getPost('marks');
						 $this->programmeService->saveEditedAssessmentMark($data);
						 $this->auditTrailService->saveAuditTrail("EDIT", "Assessment Marks", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage('Assessment Marks was successfully edited');
						 return $this->redirect()->toRoute('editassessmentmarks');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
	             }
	         }
			 
	        return array(
				'form' => $form,
				'markDetails' => $markDetails);
        }else{
        	return $this->redirect()->toRoute('editassessmentmarks');
        }
	}
	//update all marks after editing
	public function updateAssessmentMarksAction()
	{
		$this->loginDetails();
    	$form = new MarkEntryForm($studentCount= 'null', 0);
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
             	$programmesId = $this->getRequest()->getPost('programmes_id');
             	$continuous_assessment_id = $this->getRequest()->getPost('continuous_assessment_id');
             	$assessment_type = $this->getRequest()->getPost('assessment_type');
             	$batch = $this->getRequest()->getPost('batch');
             	$section = $this->getRequest()->getPost('section');
             	$studentMarks = $this->programmeService->getStudentMarks($assessment_type, $section);
             	$data = $this->extractStudentMarksData($studentMarks);

             	try {
             		$this->programmeService->updateAssessmentMarks($data, $programmesId, $batch, $section, $continuous_assessment_id, $assessment_type);
                    $this->flashMessenger()->addMessage('Assessment Marks was successfully edited');
                    return $this->redirect()->toRoute('viewassessmentmarks');
                }
                catch(\Exception $e) {
                                die($e->getMessage());
                                // Some DB Error happened, log it and let the user know
                }
             }
		else{
             	$this->flashMessenger()->addMessage('Session has expired. Submit form again');
				return $this->redirect()->toRoute('academicassessment');
             }
         }
		 
        return array('form' => $form);
	}
	//Delete all marks after editing
	public function deleteAssessmentMarksAction()
	{
		$this->loginDetails();
    	$form = new MarkEntryForm($studentCount= 'null', 0);
        
        $request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid()) {
				$programmesId = $this->getRequest()->getPost('programmes_id');
		 	$continuous_assessment_id = $this->getRequest()->getPost('continuous_assessment_id');
		 	$assessment_type = $this->getRequest()->getPost('assessment_type');
		 	$batch = $this->getRequest()->getPost('batch');
		 	$section = $this->getRequest()->getPost('section');
		 	$studentMarks = $this->programmeService->getStudentMarks($assessment_type, $section);

		 	$crossCheckCompiled = $this->programmeService->crossCheckCompiled($batch, $section, $continuous_assessment_id);

		 	if ($crossCheckCompiled){
		 		$this->flashMessenger()->addMessage('The marks for this module has already been compiled. Please delete compiled marks and than delete the Marks');
				$message = 'Failure';
				return $this->redirect()->toRoute('editassessmentmarks');
		 	} else {
		 		try {
		     		$this->programmeService->deleteAssessmentMarks($programmesId, $batch, $section, $continuous_assessment_id, $assessment_type);
		     		$this->flashMessenger()->addMessage('Assessment Marks was successfully Deleted');
		     		return $this->redirect()->toRoute('editassessmentmarks');
		        }
		        catch(\Exception $e) {
		        	die($e->getMessage());
		        	// Some DB Error happened, log it and let the user know
		        }
		 	}
		 } else {
		 	$this->flashMessenger()->addMessage('Session has expired. Submit form again');
		 	return $this->redirect()->toRoute('editassessmentmarks');
		 }
		}
        return array('form' => $form);
	}
	
	public function compileAssessmentMarksAction()
	{
		$this->loginDetails();				
		//need to get the student count for entering the marks
	   $studentCount = 0;
	   $message = NULL;
		
	   $form = new SearchForm($this->serviceLocator);

	   $compileDate = $this->programmeService->listSelectData($tableName = 'academic_calendar','CA', $this->organisation_id, $this->username);

	   $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$academic_modules_allocation_id = $this->getRequest()->getPost('assessment_component_id');
				$section = $this->getRequest()->getPost('section'); //echo $academic_modules_allocation_id; die();
				//check if tutor is assigned to section and/or whether assignment has already been marked
				$crossCheckCompilation = $this->programmeService->checkCompiledMarks($academic_modules_allocation_id, $section, 'CA');
				if($crossCheckCompilation == 'Compiled'){
					$this->flashMessenger()->addMessage('Assessment Marks have already been compiled OR Section selected is wrong');
					$message = 'Failure';
				} else{
					$this->programmeService->compileMarks($academic_modules_allocation_id, $section, 'Continuous Assessment', $this->organisation_id);
					$this->flashMessenger()->addMessage('Assessment Marks has been successfully compiled');
				}
             }
         }
		 		
		$moduleList = $this->programmeService->listSelectData($tableName = 'academic_modules_allocation', $columnName = 'module_title', $this->organisation_id, $this->username);
		$sectionList = $this->programmeService->listSelectData('student_section', 'section', $this->organisation_id, $this->username);
                
		return array(
            'form' => $form,
			'compileDate' => $compileDate,
			'moduleList' => $moduleList,
            'sectionList' => $sectionList,
			'message' => $message,
            'keyphrase' => $this->keyphrase,

            );
	}
	
	public function viewCompiledAssessmentMarksAction()
	{
		$this->loginDetails();				
		//need to get the student count for entering the marks
		$studentCount = 0;
		$message = NULL;
		
		$form = new SearchForm($this->serviceLocator);
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$academic_modules_allocation_id = $this->getRequest()->getPost('assessment_component_id');
                $section = $this->getRequest()->getPost('section');
				$studentAssessmentList = $this->programmeService->getCompiledMarks($academic_modules_allocation_id, $section, 'continuous_assessment');
				
				$studentNameList = $this->programmeService->getStudentNameList($academic_modules_allocation_id, $section);
             }
         }
		 else {
			 $studentAssessmentList = array();
			 $studentNameList = array();
		 }

		 if($studentAssessmentList == NULL){
			$this->flashMessenger()->addMessage(' Result aleady Declared');
			$message = 'Failure';
		 }
		 		
		$moduleList = $this->programmeService->listSelectData($tableName = 'academic_modules_allocation', $columnName = 'module_title', $this->organisation_id, $this->username);
		$sectionList = $this->programmeService->listSelectData('student_section', 'section', $this->organisation_id, $this->username);
                
		return array(
            'form' => $form,
            'message' => $message,
			'studentAssessmentList' => $studentAssessmentList,
			'studentNameList' => $studentNameList,
			'moduleList' => $moduleList,
            'sectionList' => $sectionList,
            'keyphrase' => $this->keyphrase,
            );
	}
	
	//semester exams
	public function semesterAssessmentAction()
	{	   
	   $this->loginDetails();						
		//need to get the student count for entering the marks
	    $studentCount = 0;
	   
	    //preset values
	    $studentList = array();
            $weightage = NULL;
            $assessment_marks = NULL;
            $academic_year = NULL;
            $batch = NULL;
            $programmesId = NULL;
            $continuous_assessment_id = NULL;
            $assessment = NULL;
            $section = NULL;
            $message = NULL;
            
            $sectionList = $this->programmeService->listSelectData('student_section', 'section', $this->organisation_id, $this->username);
		
	   $form = new ExamSearchForm($this->serviceLocator);
	   
	   $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 $assessment = $this->getRequest()->getPost('assessment_type');

				 $continuous_assessment_id = $this->getRequest()->getPost('assessment_type');

				 $academic_modules_allocation_id = $this->getRequest()->getPost('assessment_component_id');
                 $section = $this->getRequest()->getPost('section');
				 //check whether the entry for the module have been done
				 //$mark_entry_check = $this->programmeService->checkSemesterMarkEntry($academic_modules_allocation_id, $section, $this->organisation_id);

				 $mark_entry_check = $this->programmeService->checkSemesterMarkEntry($academic_modules_allocation_id, $assessment, $section, $this->organisation_id, $this->username);

				 //var_dump($mark_entry_check); die();
				if($mark_entry_check != NULL){
					$this->flashMessenger()->addMessage('Assessment Marks have already been added. If you see following student in the list, it means that this students mark is not yet added.');
					$message = 'Failure';

					$academic_modules_allocation_id = $this->getRequest()->getPost('assessment_component_id');

					//need to get which batch the module is for
					$year = $this->programmeService->getBatch($academic_modules_allocation_id, $assessment, $assessment_for = 'semester_exams');
					
					foreach($year as $data){
						$weightage = $data['assessment_weightage'];
						$assessment_marks = $data['assessment_marks'];
						$continuous_assessment_id = $data['academic_assessment_id'];
						$programmesId = $data['programmes_id'];
						$batch = $data['year'];
					}
					
					//to ensure we only get an number and not "1st Year" etc
					//then subtract 1 as we need the year
					preg_match_all('!\d+!', $batch, $batch_year);
					foreach($batch_year as $key => $value){
							foreach($value as $key1=> $value1){
									$batch = $value1;
							}
					}
					$present_month = date('m');

					$current_semester = $this->programmeService->getSemester($this->organisation_id);

					if(($current_semester['academic_event'] == 'Spring')){
						$academic_year = $current_semester['academic_year'];
					}else{
						$academic_year = $current_semester['academic_year'];
					}

					$studentList = $this->programmeService->getMissingStudentList($continuous_assessment_id, $studentName = NULL, $section, $academic_modules_allocation_id, $programmesId, $academic_year, $marks = 'continuous_assessment',$status = NULL);
					if($studentList){
						$studentCount = count($studentList);	
					} else {
						$studentList = array();
						$studentCount = count($studentList);
					}
				 } else{

					$academic_modules_allocation_id = $this->getRequest()->getPost('assessment_component_id');

					//need to get which batch the module is for
					$year = $this->programmeService->getBatch($academic_modules_allocation_id, $assessment, $assessment_for = 'semester_exams');
					
					foreach($year as $data){
						$weightage = $data['assessment_weightage'];
						$assessment_marks = $data['assessment_marks'];
						$continuous_assessment_id = $data['academic_assessment_id'];
						$programmesId = $data['programmes_id'];
						$batch = $data['year'];
					}
					
					//to ensure we only get an number and not "1st Year" etc
					//then subtract 1 as we need the year
					preg_match_all('!\d+!', $batch, $batch_year);
					foreach($batch_year as $key => $value){
							foreach($value as $key1=> $value1){
									$batch = $value1;
							}
					}
					$present_month = date('m');

					$current_semester = $this->programmeService->getSemester($this->organisation_id);

					if(($current_semester['academic_event'] == 'Spring')){
						$academic_year = $current_semester['academic_year'];
					}else{
						$academic_year = $current_semester['academic_year'];
					}

					$studentList = $this->programmeService->getStudentExaminationList($academic_modules_allocation_id, $section, $programmesId, $academic_year);

					$studentCount = count($studentList); 
				 }
             }
         }
		 
		$marksForm = new MarkEntryForm($studentCount, $assessment_marks);
		
		$moduleList = $this->programmeService->listSelectData($tableName = 'academic_modules_allocation', $columnName = 'module_title', $this->organisation_id, $this->username);
		
		
		return array(
            'form' => $form,
			'studentList' => $studentList,
			'marksForm' => $marksForm,
			'studentCount' => $studentCount,
			'moduleList' => $moduleList,
            'sectionList' => $sectionList,
			'weightage' => $weightage,
			'assessment_marks' => $assessment_marks,
			'batch' => $academic_year,
			'continuous_assessment_id' => $continuous_assessment_id,
			'programmesId' => $programmesId,
			'assessment' => $assessment,
            'section' => $section,
            'keyphrase' => $this->keyphrase,
			'message' => $message
            );
	}
	
	public function addSemesterMarksAction()
	{
		$this->loginDetails();
		$form = new MarkEntryForm($studentCount= 'null', 0);
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 $data = $this->extractFormData();
				 $programmesId = $this->getRequest()->getPost('programmes_id');
                 $section = $this->getRequest()->getPost('section');
				 $continuous_assessment_id = $this->getRequest()->getPost('assessment_type');
				 $assessment_type = $this->getRequest()->getPost('assessment_type');

				 $batch = $this->getRequest()->getPost('batch');
                 try {

					 $this->programmeService->saveAssessmentMarks($data, $programmesId, $batch, $section, $continuous_assessment_id, $assessment_type);
					 $this->auditTrailService->saveAuditTrail("INSERT", "Assessment Marks", "ALL", "SUCCESS");
					 $this->flashMessenger()->addMessage('Semester Marks was successfully added');
					 return $this->redirect()->toRoute('semesterassessment');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
		else{
             		$this->flashMessenger()->addMessage('Session has expired. Submit form again');
			return $this->redirect()->toRoute('semesterassessment');
             }
         }
		 
        return array('form' => $form);
	}
	
	public function viewSemesterMarksAction()
	{
		$this->loginDetails();				
		//need to get the student count for entering the marks
	   $studentCount = 0;
		
	   $form = new SearchForm($this->serviceLocator);
	   $request = $this->getRequest();
	   
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$academic_modules_allocation_id = $this->getRequest()->getPost('assessment_component_id');
                $section = $this->getRequest()->getPost('section');
				$studentAssessmentList = $this->programmeService->getStudentAssessmentMarks($academic_modules_allocation_id, $section, 'semester_assessment');
				$studentNameList = $this->programmeService->getStudentNameList($academic_modules_allocation_id, $section);
             }
         }
		 else {
			 $studentAssessmentList = array();
			 $studentNameList = array();
		 }
		 		
		$moduleList = $this->programmeService->listSelectData($tableName = 'academic_modules_allocation', $columnName = 'module_title', $this->organisation_id, $this->username);
		$sectionList = $this->programmeService->listSelectData('student_section', 'section', $this->organisation_id, $this->username);
                
		return array(
            'form' => $form,
			'studentAssessmentList' => $studentAssessmentList,
			'studentNameList' => $studentNameList,
			'moduleList' => $moduleList,
            'sectionList' => $sectionList,
            'keyphrase' => $this->keyphrase,
            );
	}
	
	public function editSemesterMarksAction()
	{
		$this->loginDetails();
						
		//need to get the student count for entering the marks
		$studentCount = 0;
		//preset values
		$studentList = array();
		$weightage = NULL;
		$assessment_marks = 0;
		$batch = NULL;
		$programmesId = NULL;
		$continuous_assessment_id = NULL;
		$assessment = NULL;
		$section = 'A';
		$academic_year = NULL;
		$sectionList = $this->programmeService->listSelectData('student_section', 'section', $this->organisation_id, $this->username);
		$message = NULL;
		$studentMarks = array();
		
	   $form = new ExamSearchForm($this->serviceLocator);
	   
	   $request = $this->getRequest();
           if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$academic_modules_allocation_id = $this->getRequest()->getPost('assessment_component_id');	
				$assessment = $this->getRequest()->getPost('assessment_type');
				$section = $this->getRequest()->getPost('section');
				$continuous_assessment_id = $this->getRequest()->getPost('assessment_type');
				//check if tutor is assigned to section and/or whether assignment has already been marked
				$crossCheckAssignment = $this->programmeService->crossCheckAsssignment($academic_modules_allocation_id, $assessment, $section, $this->username);
				/*if($crossCheckAssignment == NULL || $crossCheckAssignment == 'Assigned and Marked'){
					$this->flashMessenger()->addMessage('Assessment Marks have already been added OR Section selected is wrong');
					$message = 'Failure';
				} else{*/
					//need to get which batch the module is for
					$year = $this->programmeService->getBatch($academic_modules_allocation_id, $assessment, $assessment_for = 'continuous_assessment');
					foreach($year as $data){
							$weightage = $data['assessment_weightage'];
							$assessment_marks = $data['assessment_marks'];
							//$continuous_assessment_id = $data['academic_assessment_id'];
							$programmesId = $data['programmes_id'];
							$batch = $data['year'];
					}
					//to ensure we only get an number and not "1st Year" etc
					//then subtract 1 as we need the year
					preg_match_all('!\d+!', $batch, $batch_year);
					foreach($batch_year as $key => $value){
							foreach($value as $key1=> $value1){
									$batch = $value1;
							}
					}
					$present_month = date('m');
					$current_semester = $this->programmeService->getSemester($this->organisation_id);
					if(($current_semester['academic_event'] == 'Spring')){
						$academic_year = $current_semester['academic_year'];
					}else{
						$academic_year = $current_semester['academic_year'];
					}
					$studentList = $this->programmeService->getStudentList($studentName = NULL, $section, $academic_modules_allocation_id, $programmesId, $academic_year, $marks = 'continuous_assessment',$status = NULL);
					$studentCount = count($studentList);
					$studentMarks = $this->programmeService->getStudentMarks($assessment, $section);
				}
                        
            // }
         }
		 
		$marksForm = new EditAssessmentForm($studentList, $assessment_marks);
		
		$moduleList = $this->programmeService->listSelectData($tableName = 'academic_modules_allocation', $columnName = 'module_title', $this->organisation_id, $this->username);
		
		return array(
            'form' => $form,
            'message' => $message,
			'studentList' => $studentList,
            'sectionList' => $sectionList,
			'marksForm' => $marksForm,
			'studentCount' => $studentCount,
			'studentMarks' => $studentMarks,
			'moduleList' => $moduleList,
			'weightage' => $weightage,
			'assessment_marks' => $assessment_marks,
			'batch' => $academic_year,
			'continuous_assessment_id' => $continuous_assessment_id,
			'programmesId' => $programmesId,
			'assessment' => $assessment,
            'section' => $section,
            'keyphrase' => $this->keyphrase,
            );
	}
	
	public function compileSemesterMarksAction()
	{
		$this->loginDetails();				
		//need to get the student count for entering the marks
	   $studentCount = 0;
	   $message = NULL;
		
	   $form = new SearchForm($this->serviceLocator);

	   $compileDate = $this->programmeService->listSelectData($tableName = 'academic_calendar','SE', $this->organisation_id, $this->username);

	   $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$academic_modules_allocation_id = $this->getRequest()->getPost('assessment_component_id');
				$section = $this->getRequest()->getPost('section');
				//check if tutor is assigned to section and/or whether assignment has already been marked
				$crossCheckCompilation = $this->programmeService->checkCompiledMarks($academic_modules_allocation_id, $section, 'SE');
				if($crossCheckCompilation == 'Compiled'){
					$this->flashMessenger()->addMessage(' Assessment Marks have already been compiled OR Section selected is wrong');
					$message = 'Failure';
				} else{
					$this->programmeService->compileMarks($academic_modules_allocation_id, $section, 'Semester Exams', $this->organisation_id);
					$this->flashMessenger()->addMessage('Semester Marks has been successfully compiled');
				}
             }
         }
		 		
		$moduleList = $this->programmeService->listSelectData($tableName = 'academic_modules_allocation', $columnName = 'module_title', $this->organisation_id, $this->username);
		$sectionList = $this->programmeService->listSelectData('student_section', 'section', $this->organisation_id, $this->username);
                
		return array(
            'form' => $form,
            'compileDate' => $compileDate,
			'moduleList' => $moduleList,
            'sectionList' => $sectionList,
			'message' => $message,
            'keyphrase' => $this->keyphrase,
            );
	}
	
	public function viewCompiledSemesterMarksAction()
	{
		$this->loginDetails();				
		//need to get the student count for entering the marks
		$studentCount = 0;
		$message = NULL;
		
		$form = new SearchForm($this->serviceLocator);
		$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$academic_modules_allocation_id = $this->getRequest()->getPost('assessment_component_id');
                $section = $this->getRequest()->getPost('section');
				$studentAssessmentList = $this->programmeService->getCompiledMarks($academic_modules_allocation_id, $section, 'semester_assessment');
				$studentNameList = $this->programmeService->getStudentNameList($academic_modules_allocation_id, $section);
             }
         }
		 else {
			 $studentAssessmentList = array();
			 $studentNameList = array();
		 }

		 if($studentAssessmentList == NULL){
			$this->flashMessenger()->addMessage(' Result aleady Declared');
			$message = 'Failure';
		 }
		 		
		$moduleList = $this->programmeService->listSelectData($tableName = 'academic_modules_allocation', $columnName = 'module_title', $this->organisation_id, $this->username);
		$sectionList = $this->programmeService->listSelectData('student_section', 'section', $this->organisation_id, $this->username);
                
		return array(
            'form' => $form,
            'message' =>$message,
			'studentAssessmentList' => $studentAssessmentList,
			'studentNameList' => $studentNameList,
			'moduleList' => $moduleList,
            'sectionList' => $sectionList,
            'keyphrase' => $this->keyphrase,
            );
	}
	
	public function editCompiledMarksAction()
	{
		$this->loginDetails();
		//get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
		//decrypt not working
        //$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		$id = $id_from_route;

        if(is_numeric($id)){	
			$markDetails = $this->programmeService->editStudentCompiledMark($id);
			$form = new EditCompiledMarkForm($this->serviceLocator, $markDetails['weightage']);
			
			$request = $this->getRequest();
	         if ($request->isPost()) {
				 $form->setData($request->getPost());
	             if ($form->isValid()) {
	                 try {
						 $marks_data['id'] = $this->getRequest()->getPost('id');
						 $marks_data['marks'] = $this->getRequest()->getPost('marks');
						 $this->programmeService->saveEditedCompiledMark($marks_data);
						 $this->auditTrailService->saveAuditTrail("EDIT", "Compiled Assessment Marks", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage('Marks was successfully edited');
						 return $this->redirect()->toRoute('viewcompiledassessmentmarks');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
	             }
	         }
			 
	        return array(
				'form' => $form,
				'markDetails' => $markDetails);
        }else{
        	return $this->redirect()->toRoute('editassessmentmarks');
        }
	}
	
    //to view the consolidated marks by the module tutor for students of particular module
	public function viewConsolidatedMarksAction()
	{
		$this->loginDetails();				
		//need to get the student count for entering the marks
		$studentCount = 0;

		$form = new ProgrammeSearchForm();

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid()) {
			   $academic_modules_allocation_id = $this->getRequest()->getPost('assessment_component_id');
			   $section = $this->getRequest()->getPost('section');
			   $studentAssessmentList = $this->programmeService->getStudentAssessmentMarks($academic_modules_allocation_id, $section, 'consolidated');
			   $studentNameList = $this->programmeService->getStudentNameList($academic_modules_allocation_id, $section);
			}
		}
		 else {
			 $studentAssessmentList = array();
			 $studentNameList = array();
		 }
		 		
		$moduleList = $this->programmeService->listSelectData($tableName = 'academic_modules_allocation', $columnName = 'module_title', $this->organisation_id, $this->username);
		$sectionList = $this->programmeService->listSelectData('student_section', 'section', $this->organisation_id, $this->username);
		
		return array(
            'form' => $form,
			'studentAssessmentList' => $studentAssessmentList,
			'studentNameList' => $studentNameList,
			'moduleList' => $moduleList,
			'sectionList' => $sectionList,
			'keyphrase' => $this->keyphrase,
                );
	}
        
	//to view the consolidated marks by Programme
	public function viewProgrammeConsolidatedMarksAction()
	{
		$this->loginDetails();
		$form = new StudentSearchForm1();
                
		$programmeList = $this->programmeService->listSelectData('programmes', 'programme_name', $this->organisation_id, $this->username);
		$sectionList = $this->programmeService->listSelectData1('student_section', 'section');
		$semesterList = $this->programmeService->getSemesterList($this->organisation_id);
		
		$present_year = date('Y');
		$academicYearList = array();
		$studentMarkList = array();
        $studentList = array();
		
		for($i=(count($semesterList)/2); $i>=0; $i--){
			$academicYearList[($present_year-$i)."-".($present_year-$i+1)] = ($present_year-$i)."-".($present_year-$i+1);
		}

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid()) {
					$programme = $this->getRequest()->getPost('programme');
					$academic_year = $this->getRequest()->getPost('academic_year');
					$section = $this->getRequest()->getPost('section');
					//Same form is being used for multiple purposes
					//form label is year but we get the semester
					$semester = $this->getRequest()->getPost('year');
					//get year given $semester
					$year = ((int)$semester/2 + (int)$semester%2);
					$current_semester = $this->programmeService->getSemester($this->organisation_id);
					$temp_academic_years = explode("-", $academic_year);
					$batch = $temp_academic_years[0]-((int) $year-1);
					$studentMarkList = $this->programmeService->getStudentConsolidatedMarks($programme, $academic_year, $semester);
					$moduleCreditList = $this->programmeService->getModuleCreditList($programme, $academic_year, $semester);
					$studentList = $this->programmeService->getBasicStudentNameList($programme, $academic_year, $semester, $section);
			}
		}
		 
		return array(
            'form' => $form,
			'programmeList' => $programmeList,
			'semesterList' => $semesterList,
			'academicYearList' => $academicYearList,
			'studentMarkList' => $studentMarkList,
			'studentList' => $studentList,
			'keyphrase' => $this->keyphrase,
			'sectionList' => $sectionList,
			'moduleCreditList' => $moduleCreditList,
		);
	}
        
	//get the list of students to view the consolidated marks
	public function listStudentConsolidatedMarksAction()
	{
		$this->loginDetails();
		$form = new StudentSearchForm();

		$studentNameList =array();
                
                $programmeList = $this->programmeService->listSelectData('programmes', 'programme_name', $this->organisation_id, $this->username);

                $request = $this->getRequest();
                if ($request->isPost()) {
                    $form->setData($request->getPost());
                    if ($form->isValid()) {
                            $student_name = $this->getRequest()->getPost('student_name');
                            $student_id = $this->getRequest()->getPost('student_id');
                            $programme = $this->getRequest()->getPost('programme');
                            $studentNameList = $this->programmeService->getStudentListByYear($student_name, $student_id, $programme);
                    }
                }
		 else {
			 $studentNameList = array();
		 }
		 		
		$moduleList = $this->programmeService->listSelectData($tableName = 'academic_modules_allocation', $columnName = 'module_title', $this->organisation_id, $this->username);
		
		return array(
            'form' => $form,
			'programmeList' => $programmeList,
			'studentNameList' => $studentNameList,
			'keyphrase' => $this->keyphrase
           );
	}
        
	//to view the consolidated marks for student
	public function viewStudentConsolidatedMarksAction()
	{
		$this->loginDetails();
		$message = NULL;
		//get the student id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);

		$getSelfLogo = $this->programmeService->getOrganisationDocument('organisation_document', 'Logo', $this->organisation_id);
		$getOVCLogo = $this->programmeService->getOrganisationDocument('organisation_document', 'Logo', '1');
		
		if(is_numeric($id)){
			$studentConsolidatedMarks = $this->programmeService->getConsolidatedMarkByStudentId($id);
			$studentDetails = $this->programmeService->getStudentDetails($id);
			$student_detail = array();

			foreach($studentDetails as $detail){
				$student_detail = $detail;
			}
					
			$moduleList = $this->programmeService->getModuleListByProgramme($student_detail['programmes_id']);
					
			return array(
				'id' => $id,
				'student_detail' => $student_detail,
				'studentConsolidatedMarks' => $studentConsolidatedMarks,
				'moduleList' => $moduleList,
				'getSelfLogo' => $getSelfLogo,
				'getOVCLogo' => $getOVCLogo
			);
		}else{
			$getStudentBlockDetail = $this->programmeService->getStudentBlockByStudentId($this->student_details_id);
			//var_dump($getStudentBlockDetail); die();
			if($getStudentBlockDetail){
				$studentConsolidatedMarks = NULL;
				$studentDetails = NULL;
				$student_detail = NULL;
				$moduleList = NULL;
				$message = 'Failure';
				$this->flashMessenger()->addMessage('You Result has been blocked, please contact the college exam cell.');
                //return $this->redirect()->toRoute('viewstudentconsolidatedmarks');

			} else {
				$studentConsolidatedMarks = $this->programmeService->getConsolidatedMarkByStudentId($this->student_details_id);
				$studentDetails = $this->programmeService->getStudentDetails($this->student_details_id);
				$student_detail = array();

				foreach($studentDetails as $detail){
					$student_detail = $detail;
				}
						
				$moduleList = $this->programmeService->getModuleListByProgramme($student_detail['programmes_id']);
			}
			return array(
				'student_details_id' => $this->student_details_id,
				'student_detail' => $student_detail,
				'studentConsolidatedMarks' => $studentConsolidatedMarks,
				'moduleList' => $moduleList,
				'getSelfLogo' => $getSelfLogo,
				'getOVCLogo' => $getOVCLogo,
				'message' => $message
			);
		}
	}


	// To search for student those who is going to graduate
	public function graduatedStudentListAction()
	{
		$this->loginDetails();

        // Default values
        $graduateStudentList = array();
        $programmesId = NULL;
        $yearId = NULL;
        $academicYear = NULL;
        $studentName = NULL;
        $studentId = NULL;
        $graduatedStudentForm = NULL;
        $studentCount = 0;

        $form = new StudentSearchForm();

        $programmeList = $this->programmeService->listSelectData('programmes', 'programme_name', $this->organisation_id, $this->username);

        $studentYear = $this->programmeService->listSelectData1('programme_year', 'year');

        $present_year = date('Y');
        $stdAcademicYear = array();        
        for($i=3; $i>=0; $i--){
            $stdAcademicYear[($present_year-$i)."-".($present_year-$i+1)] = ($present_year-$i)."-".($present_year-$i+1);
        }

        $message = NULL;

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
               $programmesId = $this->getRequest()->getPost('programme');
               $yearId = $this->getRequest()->getPost('year');
               $academicYear = $this->getRequest()->getPost('academic_year'); 
               $studentName = $this->getRequest()->getPost('student_name');
               $studentId = $this->getRequest()->getPost('student_id');
               $graduateStudentList = $this->programmeService->getGraduatingStudentList($programmesId, $yearId, $academicYear, $studentName, $studentId);
               $studentCount = count($graduateStudentList);
            } 
        }

        $graduatedStudentForm = new GraduatedStudentForm($studentCount);

        return new ViewModel(array(
            'form' => $form,
            'programmesId' => $programmesId,
            'yearId' => $yearId,
            'academicYear' => $academicYear,
            'stdAcademicYear' => $stdAcademicYear,
            'studentName' => $studentName,
            'studentId' => $studentId,
            'graduateStudentList' => $graduateStudentList,
            'programmeList' => $programmeList,
            'studentYear' => $studentYear,
            'graduatedStudentForm' => $graduatedStudentForm,
            'studentCount' => $studentCount,
            'employee_details_id' => $this->employee_details_id,
            'keyphrase' => $this->keyphrase,
            'message' => $message,
            //'organisation_id' => $organisation_id
            ));
	}


	public function updateGraduatedStudentAction()
	{
		$this->loginDetails();

		$form = new GraduatedStudentForm($studentCount = 'null');

		$message = NULL;

		//$organisation_id = 1;
        $request = $this->getRequest();
        if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
               $programmesId = $this->getRequest()->getPost('programme');
               $yearId = $this->getRequest()->getPost('year');
               $academicYear = $this->getRequest()->getPost('academic_year');
               $studentName = $this->getRequest()->getPost('student_name');
               $studentId = $this->getRequest()->getPost('student_id');

                $student_data = $this->extractGraduatedStudentData();
                try {
                     $this->programmeService->updateGraduatedStudent($student_data, $programmesId, $yearId, $academicYear, $studentName, $studentId, $this->organisation_id);
                     $this->auditTrailService->saveAuditTrail("UPDATE", "Student Graduation", "ALL", "SUCCESS");

                     $this->flashMessenger()->addMessage('Graduated Student was successfully updated');
                     return $this->redirect()->toRoute('graduatedstudentlist');
                    } 
                    catch(\Exception $e) {
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                        return $this->redirect()->toRoute('graduatedstudentlist');
                        // Some DB Error happened, log it and let the user know
                }
            }
        }   
       return array(
            'form' => $form,
            'message' => $message,
        );

	}


	public function extractGraduatedStudentData()
    {
        $studentCount = $this->getRequest()->getPost('studentCount');
        $evaluationData = array();

        for($i=1; $i<=$studentCount; $i++)
        {
                $evaluationData[$i] = $this->getRequest()->getPost('student_'.$i);
        }

        return $evaluationData;
    }
	
	//the following function is to extract the data from the form 
	// and return clean data to be inserted into database
	public function extractFormData()
	{
		$studentCount = $this->getRequest()->getPost('studentCount');

		$evaluationData = array();
		
		//evaluation data => 'evaluation_'.$i.$j,
		for($i=1; $i<=$studentCount; $i++)
		{
			$evaluationData[$i]= $this->getRequest()->getPost('marks_'.$i);
		}

		return $evaluationData;
	}
	
	public function extractStudentMarksData($studentMarks)
	{
		$evaluationData = array();
		
		//evaluation data => 'evaluation_'.$i.$j,
		foreach($studentMarks as $key => $value)
		{
			$evaluationData[$key]= $this->getRequest()->getPost('marks_'.$key);
		}
		return $evaluationData;
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

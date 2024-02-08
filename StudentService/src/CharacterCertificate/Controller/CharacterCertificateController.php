<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CharacterCertificate\Controller;

use CharacterCertificate\Service\CharacterCertificateServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use CharacterCertificate\Form\CharacterCertificateForm;
use CharacterCertificate\Form\CharacterEvaluationForm;
use CharacterCertificate\Form\CharacterEvaluatedRatingForm;
use CharacterCertificate\Form\CharacterEvaluatorForm;
use CharacterCertificate\Form\CharacterEvaluationCriteriaForm;
use CharacterCertificate\Form\SearchForm;
use CharacterCertificate\Model\CharacterCertificate;
use CharacterCertificate\Model\CharacterEvaluationCriteria;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

use Zend\View\Model\JsonModel;

use Zend\Http\Response\Stream;
use Zend\Http\Headers;

use DOMPDFModule\View\Model\PdfModel;

//AJAX
use Zend\Paginator\Adapter\DbSelect;
use Zend\Form\Element\Select;

/**
 * Description of IndexController
 *
 */
 
class CharacterCertificateController extends AbstractActionController
{
	protected $certificateService;
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
	
	public function __construct(CharacterCertificateServiceInterface $certificateService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->certificateService = $certificateService;
		$this->notificationService = $notificationService;
        $this->auditTrailService = $auditTrailService;
		$this->serviceLocator = $serviceLocator;
		
		/*
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
		
		$empData = $this->certificateService->getUserDetailsId($this->username, $tableName = 'employee_details');
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			}
		
		//get the organisation id
		$organisationID = $this->certificateService->getOrganisationId($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}

		//get the user details such as name
        $this->userDetails = $this->certificateService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->certificateService->getUserImage($this->username, $this->usertype);

	}

	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }

    
	public function characterCertificateEvaluatorAction()
    {
    	$this->loginDetails();

        $form = new CharacterEvaluatorForm();
		$certificateModel = new CharacterCertificate();
		$form->bind($certificateModel);
		
		//get the organisation id
		$organisationID = $this->certificateService->getOrganisationId($this->username);
		foreach($organisationID as $organisation){
			$organisation_id = $organisation['organisation_id'];
		}
				
		$staffList = $this->certificateService->getStaffList($organisation_id);
		$evaluatorList = $this->certificateService->getEvaluatorList($organisation_id);
		$programmeList = $this->certificateService->getProgrammeList($organisation_id);
		$evaluatorDetails = $this->certificateService->getEvaluatorDetails($organisation_id);
		        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->certificateService->saveEvaluator($certificateModel);
					 $this->redirect()->toRoute('identifyccevaluator');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'staffList' => $staffList,
			'evaluatorList' => $evaluatorList,
			'evaluatorDetails' => $evaluatorDetails,
			'programmeList' => $programmeList);
    }
	
	//function to search and then display before adding the evaluation
	public function studentEvaluationAction()
    {
    	$this->loginDetails();
       //need to get the student and criteria count for the evaluation form
	   $studentCount = 0;
	   $criteria = $this->certificateService->getCriteriaList($this->organisation_id);
	   $criteriaCount = count($criteria);
	   $form = new SearchForm();

	   $message = NULL;

	   $academic_module_tutors_id = NULL;
	   
	   $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$academic_module_tutors_id = $this->getRequest()->getPost('academic_module_tutors_id');
				
				$academic_modules_allocation_details = $this->certificateService->getAcademicModuleAllocationDetails($academic_module_tutors_id);
				$module_tutor_allocation = array();
				foreach($academic_modules_allocation_details as $details){
					$module_tutor_allocation = $details;
				} 

				$characterEvaluation = array();
				$check_character_evaluation = $this->certificateService->crossCheckCharacterEvaluation($academic_module_tutors_id, $this->employee_details_id);
				foreach($check_character_evaluation as $evaluation){
					$characterEvaluation = $evaluation;
				}
				
				if(!empty($characterEvaluation)){
					$message = 'Failure';
					$this->flashMessenger()->addMessage("You have already evaluated on ".$characterEvaluation['evaluation_date']. ". If there is any mistake then please edit it.");
				}else{
					//get the $programmeId and $batch
					$batch = $this->certificateService->getBatchDetails($module_tutor_allocation['academic_modules_allocation_id'],'batch');
					$programmesId = $this->certificateService->getBatchDetails($module_tutor_allocation['academic_modules_allocation_id'],'programmes_id');

					$studentList = $this->certificateService->getStudentList($studentName=NULL, $programmesId, $this->username, $academic_module_tutors_id);
					$studentCount = count($studentList);
				}
             }
         }
		 else {
			 $studentList = array();
			 $programmesId = NULL;
			 $studentName = NULL;
			 $batch = NULL;
		 }
		 
		$evaluationForm = new CharacterEvaluationForm($studentCount, $criteriaCount);
		$certificateModel = new CharacterCertificate();
		$evaluationForm->bind($certificateModel);
		
		//get the list of programmes for the evaluator				
		$programmeList = $this->certificateService->getEvaluatorProgrammeList($this->username);
		
		return array(
            'form' => $form,
			'studentList' => $studentList,
			'programmesId' => $programmesId,
			'studentName' => $studentName,
			'batch' => $batch,
			'academic_module_tutors_id' => $academic_module_tutors_id,
			'evaluationForm' => $evaluationForm,
			'studentCount' => $studentCount,
			'criteria' => $criteria,
			'criteriaCount' => $criteriaCount,
			'programmeList' => $programmeList,
			'message' => $message,
            );
    }
	
	public function characterCertificateEvaluationAction()
    {
    	$this->loginDetails();
		$form = new CharacterEvaluationForm($studentCount= 'null', $criteriaCount='null');
		$certificateModel = new CharacterCertificate();
		$form->bind($certificateModel);
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 $data = $this->extractFormData(); 
				 $programmesId = $this->getRequest()->getPost('programme_id');
				 $batch = $this->getRequest()->getPost('batch');
				 $academic_module_tutors_id = $this->getRequest()->getPost('academic_module_tutors_id');
				 $studentName =$this->getRequest()->getPost('studentName'); 
			 	try {
					 $this->certificateService->saveCharacterEvaluation($data, $programmesId, $batch, $studentName, $this->username, $academic_module_tutors_id);
					 $this->auditTrailService->saveAuditTrail("INSERT", "Character Evaluation", "ALL", "SUCCESS");

					 $this->flashMessenger()->addMessage('Student Evaluation was successfully added');
					 return $this->redirect()->toRoute('studentccevaluation');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array('form' => $form);
    }

    
	public function characterCertificateGenerationAction()
    {
		$this->loginDetails();
        //need to get the student and criteria count for the evaluation form
        $studentList = array();
		$programmesId = NULL;
		$studentName = NULL;
		$batch = NULL;
	    $studentCount = 0;
	    $criteria = $this->certificateService->getCriteriaList($this->organisation_id); 
	    $criteriaCount = count($criteria); 
	    $form = new SearchForm(); 

	    //get the list of programmes for the evaluator				
		$programmeList = $this->certificateService->getProgrammeList($this->organisation_id);
		$batchList = $this->certificateService->getBatchList($this->organisation_id);
	   
	   	$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
             	$programmesId = $this->getRequest()->getPost('programmes_id');
				$batch = $this->getRequest()->getPost('batch');
				$studentName = $this->getRequest()->getPost('student_name');
				$evaluationList = $this->certificateService->getStudentCharacterEvaluation($studentName, $programmesId, $batch, $this->employee_details_id, $this->organisation_id);
				$studentList = $this->certificateService->getEvaluatedStudentList($studentName, $programmesId, $batch, $this->employee_details_id, $this->organisation_id);
				$studentCount = count($evaluationList); 
             }
         }
		
		return array(
            'form' => $form,
			'studentList' => $studentList,
			'evaluationList' => $evaluationList,
			'programmesId' => $programmesId,
			'studentName' => $studentName,
			'batch' => $batch,
			'studentCount' => $studentCount,
			'criteria' => $criteria,
			'criteriaCount' => $criteriaCount,
			'programmeList' => $programmeList,
			'batchList' => $batchList, 
			'keyphrase' => $this->keyphrase
            );
	}
	
	public function downloadCharacterCertificatePdfAction()
	{
		$this->loginDetails();
         //get the id
        $id_from_route = $this->params()->fromRoute('id');
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){ 
			$stdDetails = $this->certificateService->getStdPersonalDetails($id);
			$std_details_array = array();
			foreach($stdDetails as $details){
				$std_details_array = $details;
			} 

			$personalDetails = $this->certificateService->getStdPersonalDetails($id); 

			$father_detail = $this->certificateService->getStudentRelationDetails('1', $id);
			$mother_detail = $this->certificateService->getStudentRelationDetails('2', $id);
			$organisation_logo = $this->certificateService->getOrganisationLogo('Logo', $this->organisation_id);
			$rub_logo = $this->certificateService->getOrganisationLogo('Logo',NULL);
			$organisation_banner = $this->certificateService->getOrganisationLogo('Banner', $this->organisation_id);

			$criteria = $this->certificateService->getCriteriaList($this->organisation_id); 
			$criteriaCount = count($criteria); 

			$evaluationList = $this->certificateService->getCharacterEvaluation($id);

			$date = date("Y-m-d");
			$pdf = new PdfModel();
			$pdf->setOption($html,'UTF-8');
            $pdf->setOption('fileName', $std_details_array['student_id'].$std_details_array['first_name'].$std_details_array['middle_name'].$std_details_array['last_name'].'CC'.$date); // Triggers PDF download, automatically appends ".pdf"
           // $pdf->setOption('display', PdfModel::DISPLAY_ATTACHMENT); 
            $pdf->setOption('paperSize', 'a4'); // Defaults to "8x11"
            $pdf->setOption('paperOrientation', 'portrait'); // Defaults to "portrait"

            //To set view variables
            $pdf->setVariables(array(
				'id' => $id,
				'personalDetails' => $personalDetails,
				'father_detail' => $father_detail,
				'mother_detail' => $mother_detail,
				'organisation_logo' => $organisation_logo,
				'rub_logo' => $rub_logo,
				'organisation_banner' => $organisation_banner,
				'criteria' => $criteria,
				'criteriaCount' => $criteriaCount,
				'evaluationList' => $evaluationList,
           ));

            return $pdf;
        }
        else{
            $this->redirect()->toRoute('ccgeneration');
        }
	}
    
	public function editCharacterCertificateAction()
    {
    	$this->loginDetails();
       //need to get the student and criteria count for the evaluation form
	   $studentCount = 0;
	   $criteria = $this->certificateService->getCriteriaList($this->organisation_id);
	   $criteriaCount = count($criteria);
	   $form = new SearchForm();

	   $message = NULL;
	   
	   $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$academic_module_tutors_id = $this->getRequest()->getPost('academic_module_tutors_id');

				$academic_modules_allocation_details = $this->certificateService->getAcademicModuleAllocationDetails($academic_module_tutors_id);
				$module_tutor_allocation = array();
				foreach($academic_modules_allocation_details as $details){
					$module_tutor_allocation = $details;
				} 

				$characterEvaluation = array();
				$check_character_evaluation = $this->certificateService->crossCheckCharacterEvaluation($academic_module_tutors_id, $this->employee_details_id);
				foreach($check_character_evaluation as $evaluation){
					$characterEvaluation = $evaluation;
				}
				
				if(empty($characterEvaluation)){
					$message = 'Failure';
					$this->flashMessenger()->addMessage("You have not evaluated. Please evaluate first and edit it.");
				}else{
					//get the $programmeId and $batch
					$batch = $this->certificateService->getBatchDetails($module_tutor_allocation['academic_modules_allocation_id'],'batch');
					$programmesId = $this->certificateService->getBatchDetails($module_tutor_allocation['academic_modules_allocation_id'],'programmes_id');

					$studentList = $this->certificateService->getEvaluatedCharacterStudentList($studentName=NULL, $programmesId, $this->username, $academic_module_tutors_id);
					$studentCount = count($studentList);
				}
             }
         }
		 else {
			 $studentList = array();
			 $programmesId = NULL;
			 $studentName = NULL;
			 $batch = NULL;
		 }
		 
		$evaluationForm = new CharacterEvaluationForm($studentCount, $criteriaCount);
		$certificateModel = new CharacterCertificate();
		$evaluationForm->bind($certificateModel);
		
		//get the list of programmes for the evaluator				
		$programmeList = $this->certificateService->getEvaluatorProgrammeList($this->username);
		
		return array(
            'form' => $form,
			'studentList' => $studentList,
			'programmesId' => $programmesId,
			'studentName' => $studentName,
			'batch' => $batch,
			'academic_module_tutors_id' => $academic_module_tutors_id,
			'evaluationForm' => $evaluationForm,
			'studentCount' => $studentCount,
			'criteria' => $criteria,
			'criteriaCount' => $criteriaCount,
			'programmeList' => $programmeList,
			'message' => $message,
			'keyphrase' => $this->keyphrase,
            );
    }


    public function updateCharacterCertificateEvaluationAction()
    {
    	$this->loginDetails(); 

    	$id_from_route = $this->params()->fromRoute('id');
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        $did_from_route = $this->params()->fromRoute('did');
        $academic_module_tutors_id = $this->my_decrypt($did_from_route, $this->keyphrase);

        if(is_numeric($id)){ 

        	$criteria = $this->certificateService->getCriteriaList($this->organisation_id);
	   		$criteriaCount = count($criteria);

        	$form = new CharacterEvaluatedRatingForm($criteriaCount);

			$evaluatedRating = $this->certificateService->getStudentEvaluatedRating($id, $academic_module_tutors_id, $this->employee_details_id);
			$studentDetails = $this->certificateService->getStudentDetails($id);
	        
	        $request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) {
					 $data = $this->extractFormData1();
					 $academic_module_tutors_id = $this->getRequest()->getPost('academic_module_tutors_id');
				 	try {
						 $this->certificateService->updateCharacterEvaluation($data, $id, $academic_module_tutors_id, $this->employee_details_id, $this->organisation_id);
						 $this->auditTrailService->saveAuditTrail("EDIT", "Character Evaluation", "ALL", "SUCCESS");

						 $this->flashMessenger()->addMessage('Student Evaluation was successfully edited');
						 return $this->redirect()->toRoute('editcharactercert');
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
	        	'academic_module_tutors_id' => $academic_module_tutors_id,
	        	'evaluatedRating' => $evaluatedRating,
	        	'criteria' => $criteria,
				'criteriaCount' => $criteriaCount,
				'studentDetails' => $studentDetails,
	        );
        }else{
        	return $this->redirect()->toRoute('editcharactercert');
        }
    }

	
	public function addCharacterEvaluationCriteriaAction()
    {
    	$this->loginDetails();

        $form = new CharacterEvaluationCriteriaForm();
		$certificateModel = new CharacterEvaluationCriteria();
		$form->bind($certificateModel);
		
		$criteria = $this->certificateService->listAll($tableName='character_evaluation_criteria', $this->organisation_id);

		$message  = NULL;
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->certificateService->saveCriteria($certificateModel);
					 $this->auditTrailService->saveAuditTrail("INSERT", "Character Evaluation Criteria", "ALL", "SUCCESS");
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
             $this->flashMessenger()->addMessage('Character Criteria was successfully added');
			 return $this->redirect()->toRoute('charactercriteria');
         }
		 
        return array(
			'form' => $form,
			'criteria' => $criteria,
			'message' => $message,
			'keyphrase' => $this->keyphrase,
			'organisation_id' => $this->organisation_id,
		);
    }
	
	public function editCharacterEvaluationCriteriaAction()
    {
    	$this->loginDetails();
        //get the criteria id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new CharacterEvaluationCriteriaForm();
			$certificateModel = new CharacterEvaluationCriteria();
			$form->bind($certificateModel);
			
			$criteria = $this->certificateService->findCharacterCriteria($id);
	        
	        $request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) {
	                 try {
						 $this->certificateService->saveCriteria($certificateModel);
						 $this->auditTrailService->saveAuditTrail("EDIT", "Character Evaluation Criteria", "ALL", "SUCCESS");
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
	             }
	             $this->flashMessenger()->addMessage('Character Criteria was successfully edited');
				 return $this->redirect()->toRoute('charactercriteria');
	         }
			 
	        return array(
	        	'id' => $id,
				'form' => $form,
				'criteria' => $criteria);
        }else{
        	$this->redirect()->toRoute('charactercriteria');
        }
    }


    public function viewCharacterCertificateAction()
    {
    	$this->loginDetails();
        //need to get the student and criteria count for the evaluation form
        $studentList = array();
		$programmesId = NULL;
		$studentName = NULL;
		$batch = NULL;
	    $studentCount = 0;
	    $criteria = $this->certificateService->getCriteriaList($this->organisation_id); 
	    $criteriaCount = count($criteria); 
	    $form = new SearchForm(); 

	    //get the list of programmes for the evaluator				
		$programmeList = $this->certificateService->getProgrammeList($this->organisation_id);
		$batchList = $this->certificateService->getBatchList($this->organisation_id);
	   
	   	$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
             	$programmesId = $this->getRequest()->getPost('programmes_id');
				$batch = $this->getRequest()->getPost('batch');
				$studentName = $this->getRequest()->getPost('student_name');
				$evaluationList = $this->certificateService->getStudentCharacterEvaluation($studentName, $programmesId, $batch, $this->employee_details_id, $this->organisation_id);
				$studentList = $this->certificateService->getEvaluatedStudentList($studentName, $programmesId, $batch, $this->employee_details_id, $this->organisation_id);
				$studentCount = count($evaluationList); 
             }
         }
		
		return array(
            'form' => $form,
			'studentList' => $studentList,
			'evaluationList' => $evaluationList,
			'programmesId' => $programmesId,
			'studentName' => $studentName,
			'batch' => $batch,
			'studentCount' => $studentCount,
			'criteria' => $criteria,
			'criteriaCount' => $criteriaCount,
			'programmeList' => $programmeList,
			'batchList' => $batchList
            );
    }

	
	//the following function is to extract the data from the form 
	// and return clean data to be inserted into database
	public function extractFormData()
	{
		$studentCount = $this->getRequest()->getPost('studentCount');
		$criteriaCount = $this->getRequest()->getPost('criteriaCount');
		$evaluationData = array();
		
		//evaluation data => 'evaluation_'.$i.$j,
		for($i=1; $i<=$studentCount; $i++)
		{
			for($j=1; $j<=$criteriaCount; $j++)
			{
				$evaluationData[$i][$j] = $this->getRequest()->getPost('evaluation_'.$i.$j);
			}
		}
		return $evaluationData;
	}


	//the following function is to extract the data from the form 
	// and return clean data to be inserted into database
	public function extractFormData1()
	{
		$criteriaCount = $this->getRequest()->getPost('criteriaCount');
		$evaluationData = array();
		
		//evaluation data => 'evaluation_'.$i,
		for($i=1; $i<=$criteriaCount; $i++)
		{
			$evaluationData[$i] = $this->getRequest()->getPost('evaluation_'.$i);
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

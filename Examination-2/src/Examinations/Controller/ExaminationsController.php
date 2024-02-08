<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Examinations\Controller;

use Zend\ServiceManager\ServiceLocatorInterface;
use Examinations\Service\ExaminationsServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Examinations\Model\Examinations;
use Examinations\Model\ExamHall;
use Examinations\Model\ExaminationCode;
use Examinations\Model\ExamInvigilator;
use Examinations\Form\ExaminationsForm;
use Examinations\Form\ExamHallForm;
use Examinations\Form\ExaminationCodeForm;
use Examinations\Form\ExamInvigilatorForm;
use Examinations\Form\ExaminationTimetableForm;
use Examinations\Form\SearchForm;
use Examinations\Form\StudentSearchForm;
use Examinations\Form\StudentBackPaperSearchForm;
use Examinations\Form\StudentBackPaperForm;
use Examinations\Form\RepeatSemesterSearchForm;
use Examinations\Form\RepeatSemesterForm;
use Examinations\Form\ExaminationEligibilityForm;
use Examinations\Form\ExaminationSearchForm;
use Examinations\Form\EligibilitySearchForm;
use Examinations\Form\SemesterExamResultsForm;
use Examinations\Form\ExamModerationForm;
use Examinations\Form\ExamDeclarationForm;
use Examinations\Form\BackPaperGenerationForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;

/**
 * Description of IndexController
 *
 * @author Mendrel
 */
class ExaminationsController extends AbstractActionController
{
    
	protected $examinationService;
	protected $notificationService;
    protected $auditTrailService;
	protected $serviceLocator;
	protected $username;
	protected $userrole;
    protected $usertype;
    protected $userDetails;
    protected $userImage;
	protected $employee_details_id;
	protected $student_id;
	protected $organisation_id;

	protected $keyphrase = "RUB_IMS";
	
	public function __construct(ExaminationsServiceInterface $examinationService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->examinationService = $examinationService;
		$this->notificationService = $notificationService;
        $this->auditTrailService = $auditTrailService;
		$this->serviceLocator = $serviceLocator;
		
		/*
         * To retrieve the user name from the session
        */
        $authPlugin = $this->serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
        // Use service locator to get the authPlugin
        $this->username = $authPlugin['username'];
        $this->usertype = $authPlugin['user_type_id'];
		$this->userrole = $authPlugin['role'];
		$this->userregion = $authPlugin['region'];	
		
		/*
		* Getting the employee_details_id related to username
		*/
		
		$employee = $this->examinationService->getUserDetailsId($this->username);
		foreach($employee as $emp){
			$this->employee_details_id = $emp['id'];
			}		
		
		//get the organisation id
		$organisationID = $this->examinationService->getOrganisationId($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}


		//get the user details such as name
        $this->userDetails = $this->examinationService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->examinationService->getUserImage($this->username, $this->usertype);
	}


	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }

	
	public function addExaminationHallAction()
	{
		$this->loginDetails();
		$form = new ExamHallForm();
		$examinationModel = new ExamHall();
		$form->bind($examinationModel);
		
		$examinationHalls = $this->examinationService->listAll('examination_hall', $this->organisation_id);
		$message = NULL;
		
		$request = $this->getRequest();
		if($request->isPost()){
			$form->setData($request->getPost());
			if($form->isValid()){
				try {
					$this->examinationService->saveExaminationHall($examinationModel);
					$this->auditTrailService->saveAuditTrail("INSERT", "Examination Hall", "ALL", "SUCCESS");
					$this->flashMessenger()->addMessage('Examination Hall was successfully added');
					return $this->redirect()->toRoute('addexamhall');
				} catch(\Exception $e) {
					die($e->getMessage());
					// Some DB Error happened, log it and let the user know
				}
			}
		}
						
		return new ViewModel(array(
			'form' => $form,
			'student_id' => $this->student_id,
			'organisation_id' => $this->organisation_id,
			'message' => $message,
			'examinationHalls' => $examinationHalls,
			'keyphrase' => $this->keyphrase,
			));
	}
	
	public function editExaminationHallAction()
	{
		$this->loginDetails();
		//get the hall id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new ExamHallForm();
			$examinationModel = new ExamHall();
			$form->bind($examinationModel);
			
			$examinationHalls = $this->examinationService->listAll('examination_hall', $this->organisation_id);
			$hallDetails = $this->examinationService->getTableDetails('examination_hall',$id);
			$message = NULL;
			
			$request = $this->getRequest();
			if($request->isPost()){
				$form->setData($request->getPost());
				if($form->isValid()){
					try {
						$this->examinationService->saveExaminationHall($examinationModel);
						$this->auditTrailService->saveAuditTrail("EDIT", "Examination Hall", "ALL", "SUCCESS");
						$this->flashMessenger()->addMessage('Examination Hall was successfully edited');
						return $this->redirect()->toRoute('addexamhall');
					} catch(\Exception $e) {
						die($e->getMessage());
						// Some DB Error happened, log it and let the user know
					}
				}
			}
							
			return new ViewModel(array(
				'id' => $id,
				'form' => $form,
				'student_id' => $this->student_id,
				'organisation_id' => $this->organisation_id,
				'message' => $message,
				'examinationHalls' => $examinationHalls,
				'hallDetails' => $hallDetails
				));
        }else{
        	$this->redirect()->toRoute('addexamhall');
        }
	}
	
	public function addExaminationTimetableAction()
	{
		$this->loginDetails();
		$form = new ExaminationTimetableForm($this->serviceLocator, $options=array(), $this->organisation_id);
		
		$examinationTimetable = $this->examinationService->listAll('examination_timetable', $this->organisation_id);
		$examinationHalls = $this->examinationService->listSelectData('examination_hall', 'hall_no', $this->organisation_id);
		$examType = $this->examinationService->listSelectData('exam_type', 'exam_type', $this->organisation_id);
		$message = NULL;
		
		$request = $this->getRequest();
		if($request->isPost()){
			$form->setData($request->getPost());
			if($form->isValid()){
				$data = $this->params()->fromPost(); 
				$check_exam_timetable = $this->examinationService->crossCheckExamTimetable('examination_timetable', $data['academic_modules_allocation_id'], $data['exam_date']); 
				if($check_exam_timetable){
					$message = 'Failure';
					$this->flashMessenger()->addMessage('You have already added this exam schedule. If there is any mistake, please edit it.');
				}else{
					try {
						$this->examinationService->saveExaminationTimetable($data);
						$this->auditTrailService->saveAuditTrail("INSERT", "Examination Timetable", "ALL", "SUCCESS");
						$this->flashMessenger()->addMessage('Examination Timetable was successfully added');
						return $this->redirect()->toRoute('addexamtimetable');
					} catch(\Exception $e) {
						die($e->getMessage());
						// Some DB Error happened, log it and let the user know
					}
				}
			}
		}
						
		return new ViewModel(array(
			'form' => $form,
			'student_id' => $this->student_id,
			'organisation_id' => $this->organisation_id,
			'message' => $message,
			'examinationTimetable' => $examinationTimetable,
			'examinationHalls' => $examinationHalls,
			'examType' => $examType,
			'keyphrase' => $this->keyphrase,
			));
	}
	
	public function editExaminationTimetableAction()
	{
		$this->loginDetails();
		//get the timetable id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){ 
			$form = new ExaminationTimetableForm($this->serviceLocator, $options=array(), $this->organisation_id);
			
			$examinationTimetable = $this->examinationService->listAll('examination_timetable', $this->organisation_id);
			$examinationHalls = $this->examinationService->listSelectData('examination_hall', 'hall_no', $this->organisation_id);
			$examType = $this->examinationService->listSelectData('exam_type', 'exam_type', $this->organisation_id);
			$programmeList = $this->examinationService->listSelectData('programmes', 'progre_name', $this->organisation_id);
			$allocatedModuleList = $this->examinationService->listSelectData('academic_modules_allocation', NULL, $this->organisation_id);
			$timetableDetails = $this->examinationService->getTableDetails('examination_timetable', $id);
			$message = NULL;
			
			$request = $this->getRequest();
			if($request->isPost()){
				$form->setData($request->getPost());
				if($form->isValid()){
					$data = $this->params()->fromPost(); 
					try {
						$this->examinationService->saveExaminationTimetable($data);
						$this->auditTrailService->saveAuditTrail("EDIT", "Examination Timetable", "ALL", "SUCCESS");
						$this->flashMessenger()->addMessage('Examination Timetable was successfully edited');
                    	return $this->redirect()->toRoute('addexamtimetable');
					} catch(\Exception $e) {
						die($e->getMessage());
						// Some DB Error happened, log it and let the user know
					}
				}
			}
							
			return new ViewModel(array(
				'form' => $form,
				'student_id' => $this->student_id,
				'organisation_id' => $this->organisation_id,
				'message' => $message,
				'examinationTimetable' => $examinationTimetable,
				'examinationHalls' => $examinationHalls,
				'examType' => $examType,
				'timetableDetails' => $timetableDetails,
				'programmeList' => $programmeList,
				'allocatedModuleList' => $allocatedModuleList,

				));
        }else{
        	$this->redirect()->toRoute('editexamtimetable');
        }
	}
	
	public function viewExaminationTimetableAction()
	{
		$this->loginDetails();
		$form = new ExaminationSearchForm();
		
		$programmeList = $this->examinationService->listSelectData($tableName='programmes', $columnName='programme_name', $this->organisation_id);
		//$examDates = $this->examinationService->getExaminationDates($this->organisation_id);
		$selectYear = $this->examinationService->createYearList($this->organisation_id);
		$examType = $this->examinationService->listSelectData($tableName='exam_type', $columnName='exam_type', $this->organisation_id);
		$message = NULL;
		$examinationTimetable = NULL;
		$examinationDetails = NULL;
		$examinationDates = NULL;
		$examinationTiming = NULL;	
		
		$request = $this->getRequest();
		if($request->isPost()){
			$form->setData($request->getPost());
			if($form->isValid()){
				$data['programme'] = $this->getRequest()->getPost('programme');
				$data['year'] = $this->getRequest()->getPost('year');
				$data['exam_type'] = $this->getRequest()->getPost('exam_type');
				$examinationTimetable = $this->examinationService->getExaminationTimetable($data, NULL, $this->organisation_id);
				$examinationDetails = $this->examinationService->getExaminationTimetable($data, NULL, $this->organisation_id);
				$examinationDates = $this->examinationService->getExaminationDates($this->organisation_id, $data);
				$examinationTiming = $this->examinationService->getExaminationTiming($this->organisation_id, $data);	
			} 
		}
								
		return new ViewModel(array(
			'form' => $form,
			'student_id' => $this->student_id,
			'programmeList' => $programmeList,
			//'examDates' => $examDates,
			'selectYear' => $selectYear,
			'message' => $message,
			'examinationTimetable' => $examinationTimetable,
			'examType' => $examType,
			'examinationDates' => $examinationDates,
			'examinationTiming' => $examinationTiming,
			'examinationDetails' => $examinationDetails,
			'keyphrase' => $this->keyphrase,
			));
	}
	
	public function examHallArrangementAction()
	{
		$this->loginDetails();
		$request = $this->getRequest();
		if($request->isPost()){
			$form->setData($request->getPost());
			if($form->isValid()){
				try {
					$this->saveHallArrangement();
				} catch(\Exception $e) {
					die($e->getMessage());
					// Some DB Error happened, log it and let the user know
				}
			}
		}
	}
	
	public function assignExamInvigilatorAction()
	{
		$this->loginDetails();
		$form = new ExamInvigilatorForm();
		$invigilatorModel = new ExamInvigilator();
		$form->bind($invigilatorModel);

		$invigilatorList = $this->examinationService->listAll('examination_invigilation_duties',$this->organisation_id);
		$examinationList = $this->examinationService->listSelectData('examination_timetable', '', $this->organisation_id);
		$employeeList = $this->examinationService->listSelectData('employee_details', '', $this->organisation_id);
		
		$message = NULL;
		
		$request = $this->getRequest();
		if($request->isPost()){
			$form->setData($request->getPost());
			if($form->isValid()){
				try {
					$this->examinationService->saveExamInvigilator($invigilatorModel);
					$this->auditTrailService->saveAuditTrail("INSERT", "Examination Invigilation Duties", "ALL", "SUCCESS");
					$this->flashMessenger()->addMessage('Examination Invigilation Duties was successfully added');
					return $this->redirect()->toRoute('assignexaminvigilator');
				} catch(\Exception $e) {
					$message = $e->getMessage();
					// Some DB Error happened, log it and let the user know
				}
			}
			//redeclare form so that there is no data and will not reload on refresh
			/*$form = new ExamInvigilatorForm();
			$invigilatorModel = new ExamInvigilator();
			$form->bind($invigilatorModel);*/
		}
		
		return new ViewModel(array(
			'form' => $form,
			'organisation_id' => $this->organisation_id,
			'invigilatorList' => $invigilatorList,
			'examinationList' => $examinationList,
			'employeeList' => $employeeList,
			'keyphrase' => $this->keyphrase,
			'message' => $message
		));
	}
	
	public function editAssignExamInvigilatorAction()
	{
		$this->loginDetails();
		//get the invigilator assignment id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new ExamInvigilatorForm();
			$invigilatorModel = new ExamInvigilator();
			$form->bind($invigilatorModel);
			
			$invigilatorList = $this->examinationService->listAll('examination_invigilation_duties', $this->organisation_id);
			$examinationList = $this->examinationService->listSelectData('examination_timetable', '', $this->organisation_id);
			$employeeList = $this->examinationService->listSelectData('employee_details', '', $this->organisation_id);
			$inviglationDetails = $this->examinationService->getTableDetails('examination_invigilation_duties', $id);
			
			$request = $this->getRequest();
			if($request->isPost()){
				$form->setData($request->getPost());
				if($form->isValid()){
					try {
						$this->examinationService->saveExamInvigilator($invigilatorModel);
						$this->auditTrailService->saveAuditTrail("EDIT", "Examination Invigilation Duties", "ALL", "SUCCESS");
						$this->flashMessenger()->addMessage('Examination Invigilation Duties was successfully edited');
						return $this->redirect()->toRoute('assignexaminvigilator');
					} catch(\Exception $e) {
						die($e->getMessage());
						// Some DB Error happened, log it and let the user know
					}
				}
			}
			
			return new ViewModel(array(
				'form' => $form,
				'organisation_id' => $this->organisation_id,
				'invigilatorList' => $invigilatorList,
				'examinationList' => $examinationList,
				'employeeList' => $employeeList,
				'inviglationDetails' => $inviglationDetails
			));
        }else{
			$this->redirect()->toRoute('assignexaminvigilator');
        }
	}
	
	public function deleteExamInvigilatorAction()
	{
		$this->loginDetails();
		//get the invigilator assignment id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	try {
				$this->examinationService->deleteExamInvigilator($id);
				$this->auditTrailService->saveAuditTrail("DELETE", "Examination Invigilation Duties", "ALL", "SUCCESS");
				$this->flashMessenger()->addMessage('Examination Invigilation Duties was successfully deleted');
					return $this->redirect()->toRoute('assignexaminvigilator');
			} catch(\Exception $e) {
				die($e->getMessage());
				// Some DB Error happened, log it and let the user know
			}
        }else{
        	$this->redirect()->toRoute('assignexaminvigilator');
        }
		
	}
	
	public function eligibleStudentListAction()
	{
		$this->loginDetails();
		$form = new EligibilitySearchForm($this->serviceLocator, $options=array(), $this->organisation_id);
		$studentList = array();
		$programmeList = $this->examinationService->listSelectData('programmes','programme_name',$this->organisation_id);
		$yearList = $this->examinationService->createYearList($this->organisation_id);
		
		$request = $this->getRequest();
		if($request->isPost()){
			$form->setData($request->getPost());
			if($form->isValid()){
				$data['programmes_id'] = $this->getRequest()->getPost('programmes_id');
				$data['academic_modules_id'] = $this->getRequest()->getPost('academic_modules_id');
				$data['year'] = $this->getRequest()->getPost('year');
				$studentList = $this->examinationService->getEligibleStudentList($data, $this->organisation_id, $type='Eligible');
			} 
		}
		
		return new ViewModel(array(
			'form' => $form,
			'studentList' => $studentList,
			'programmeList' => $programmeList,
			'keyphrase' => $this->keyphrase,
			'yearList' => $yearList
		));
	}
	
	public function noneligibleStudentListAction()
	{
		$this->loginDetails();
		$form = new EligibilitySearchForm($this->serviceLocator, $options=array(), $this->organisation_id);
		
		$studentList = array();
		$programmeList = $this->examinationService->listSelectData('programmes','programme_name',$this->organisation_id);
		$yearList = $this->examinationService->createYearList($this->organisation_id);
		
		$request = $this->getRequest();
		if($request->isPost()){
			$form->setData($request->getPost());
			if($form->isValid()){
				$data['programmes_id'] = $this->getRequest()->getPost('programmes_id');
				$data['academic_modules_id'] = $this->getRequest()->getPost('academic_modules_id');
				$data['year'] = $this->getRequest()->getPost('year');
				$studentList = $this->examinationService->getEligibleStudentList($data, $this->organisation_id, $type='Non-Eligible');
			}
		}
		
		return new ViewModel(array(
			'form' => $form,
			'studentList' => $studentList,
			'programmeList' => $programmeList,
			'keyphrase' => $this->keyphrase,
			'yearList' => $yearList
		));
	}
	
	public function viewNonEligibilityReasonsAction()
	{
		$this->loginDetails();
		//get the student id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	//dummy form- used for displaying
			$form = new ExamInvigilatorForm();
			$nonEligibilityReasons = $this->examinationService->getNonEligibilityReasons($id);
			//as rewind does not work	
			$studentDetails = $this->examinationService->getNonEligibilityReasons($id);
			return new ViewModel(array(
				'form' => $form,
				'id' => $id,
				'nonEligibilityReasons' => $nonEligibilityReasons,
				'studentDetails' => $studentDetails
			));
        }else{
        	$this->redirect()->toRoute('noneligiblestudentlist');
        }
	}
	
	public function changeStudentEligibilityAction()
	{
		$this->loginDetails();
		//get the non eligibility id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new ExaminationEligibilityForm();
			$studentDetails = $this->examinationService->getExaminationNonEligibilityDetails($id);
			
			$request = $this->getRequest();
			if($request->isPost()){
				$form->setData($request->getPost());
				if($form->isValid()){
					$data = $this->params()->fromPost();
					try {
						$this->examinationService->changeStudentEligibility($data);
						$this->auditTrailService->saveAuditTrail("INSERT", "Student Examination Noneligibility", "ALL", "SUCCESS");
						$this->redirect()->toRoute('noneligiblestudentlist');
					} catch(\Exception $e) {
						die($e->getMessage());
						// Some DB Error happened, log it and let the user know
					}
				}
			}
			
			return new ViewModel(array(
				'form' => $form,
				'id' => $id,
				'studentDetails' => $studentDetails
			));
        }else{
        	$this->redirect()->toRoute('noneligiblestudentlist');
        }
	}
	
	public function generateExamCodeAction()
	{
		$this->loginDetails();
		$form = new ExaminationCodeForm($this->serviceLocator);
		$examinationModel = new ExaminationCode();
		$form->bind($examinationModel);
		
		$studentList = array();
		$programmeList = $this->examinationService->listSelectData('programmes','programme_name',$this->organisation_id);
		$yearList = $this->examinationService->createYearList($this->organisation_id);
		
		$request = $this->getRequest();
		if($request->isPost()){
			$form->setData($request->getPost());
			if($form->isValid()){
				$data['programmes_id'] = $this->getRequest()->getPost('programmes_id');
				$data['academic_modules_id'] = $this->getRequest()->getPost('academic_modules_id');
				try {
					$this->examinationService->generateExamCodes($examinationModel, $data);
					$this->auditTrailService->saveAuditTrail("INSERT", "Student Examination Code", "ALL", "SUCCESS");
				} catch(\Exception $e) {
					die($e->getMessage());
					// Some DB Error happened, log it and let the user know
				}
			}
		}
		
		return new ViewModel(array(
			'form' => $form,
			'organisation_id' => $this->organisation_id,
			'studentList' => $studentList,
			'programmeList' => $programmeList,
			'yearList' => $yearList
		));
	}
	
	public function viewExamCodeAction()
	{
		$this->loginDetails();
		$form = new SearchForm($this->serviceLocator);
		
		$studentList = array();
		$programmeList = $this->examinationService->listSelectData('programmes','programme_name',$this->organisation_id);
		$yearList = $this->examinationService->createYearList($this->organisation_id);
		
		$request = $this->getRequest();
		if($request->isPost()){
			$form->setData($request->getPost());
			if($form->isValid()){
				$data['programmes_id'] = $this->getRequest()->getPost('programmes_id');
				$data['academic_modules_id'] = $this->getRequest()->getPost('academic_modules_id');
				$data['organisation_id'] = $this->organisation_id;
				$studentList = $this->examinationService->getExaminationCode($data);
			}
		}
		
		return new ViewModel(array(
			'form' => $form,
			'studentList' => $studentList,
			'programmeList' => $programmeList,
			'yearList' => $yearList
		));
	}
	
	public function semesterExamModerationAction()
	{
		$this->loginDetails();
		$form = new ExamModerationForm($this->serviceLocator, $options=array(), $this->organisation_id);
		
		$studentList = array();
		$programmeList = $this->examinationService->listSelectData('programmes','programme_name',$this->organisation_id);
		$yearList = $this->examinationService->createYearList($this->organisation_id);
		
		$request = $this->getRequest();
		if($request->isPost()){
			$form->setData($request->getPost());
			if($form->isValid()){
				$data['programmes_id'] = $this->getRequest()->getPost('programmes_id');
				$data['year'] = $this->getRequest()->getPost('year');
				$data['organisation_id'] = $this->organisation_id;
				$this->examinationService->consolidateMarks($data);
			}
		}
		
		return new ViewModel(array(
			'form' => $form,
			'studentList' => $studentList,
			'programmeList' => $programmeList,
			'yearList' => $yearList
		));
		/*		
		$request = $this->getRequest();
		if($request->isPost()){
			$this->examinationService->consolidateMarks($this->organisation_id);
		}
		
		return new ViewModel(array(
			'form' => $form
		));
		*/
	}
	
	public function backpaperListGenerationAction()
	{
		$this->loginDetails();
		$form = new BackPaperGenerationForm($this->serviceLocator, $options=array(), $this->organisation_id);
		
		$studentList = array();
		$programmeList = $this->examinationService->listSelectData('programmes','programme_name',$this->organisation_id);
		$yearList = $this->examinationService->createYearList($this->organisation_id);

		$repeatStudentList = $this->examinationService->listSelectData('student_repeat_modules','programme_id',$this->organisation_id);
		
		$request = $this->getRequest();
		if($request->isPost()){
			$form->setData($request->getPost());
			if($form->isValid()){
				$data['programmes_id'] = $this->getRequest()->getPost('programmes_id');
				$data['year'] = $this->getRequest()->getPost('year');
				$data['organisation_id'] = $this->organisation_id;
				$this->examinationService->generateBackpaperStudentList($data);
			}
		}
		
		return new ViewModel(array(
			'form' => $form,
			'studentList' => $studentList,
			'programmeList' => $programmeList,
			'yearList' => $yearList,
			'repeatStudentList' => $repeatStudentList
		));
	}
	
	//function to search and then display before adding
	public function studentBackPaperAction()
    {
    	$this->loginDetails();

       // Default values
       $student_ids = array();
       $programme = NULL;
	   $academic_modules_id = NULL;
       $batch = NULL;
       $backlog_semester = NULL;
       $backlog_academic_year = NULL;

       $form = new StudentBackPaperSearchForm($this->serviceLocator);
	   $batchList = $this->examinationService->createYearList($this->organisation_id);
	   $academicYearList = $this->examinationService->getAcademicYearList($this->organisation_id);
	   $semesterList = $this->examinationService->getSemesterList($this->organisation_id);
	   
	   
	   $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             $data = array_merge_recursive(
			 	$request->getPost()->toArray(),
				$request->getFiles()->toArray()
			 ); 
			 $form->setData($data); 
             if ($form->isValid()) {
				$batch = $this->getRequest()->getPost('student_batch');
				$programme = $this->getRequest()->getPost('programmes_id');
				$academic_modules_id = $this->getRequest()->getPost('academic_modules_id');
				$backlog_semester = $this->getRequest()->getPost('backlog_semester');
				$backlog_academic_year = $this->getRequest()->getPost('backlog_academic_year');
				$studentList = $this->examinationService->getStudentBackPaperList($programme, $batch);
				$student_ids = $this->extractStudentIds($studentList);
				
			 }
		 }
		 else {
			 $studentList = array();
		 }
		 
		 $studentBackPaperForm = new StudentBackPaperForm($student_ids);
		
		return new ViewModel(array(
            'form' => $form,
			'studentList' => $studentList,
			'keyphrase' => $this->keyphrase,
			'academicYearList' => $academicYearList,
			'batchList' => $batchList,
			'semesterList' => $semesterList,
			'student_ids' => $student_ids,
       		'programme' => $programme,
			'academic_modules_id' => $academic_modules_id,
       		'student_batch' => $batch,
       		'backlog_semester' => $backlog_semester,
       		'backlog_academic_year' => $backlog_academic_year,
       		'studentBackPaperForm' => $studentBackPaperForm,
            ));
    }
	
	public function addStudentBackPaperAction()
	{
		$this->loginDetails();
		$student_ids = array();
		$form = new StudentBackPaperForm($student_ids);
		
        $request = $this->getRequest();
        if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$batch = $this->getRequest()->getPost('student_batch');
				$programme = $this->getRequest()->getPost('programmes_id');
				$academic_modules_id = $this->getRequest()->getPost('academic_modules_id');
				$backlog_semester = $this->getRequest()->getPost('backlog_semester');
				$backlog_academic_year = $this->getRequest()->getPost('backlog_academic_year');
				$studentList = $this->examinationService->getStudentBackPaperList($programme, $batch);
				$student_ids = $this->extractStudentIds($studentList);
                $backpaper_data = $this->extractFormData($student_ids);
                try {
                     $this->examinationService->addStudentBackPaper($backpaper_data, $programme, $academic_modules_id, $backlog_academic_year, $backlog_semester);
                     $this->auditTrailService->saveAuditTrail("UPDATE", "Student Back Papers", "ALL", "SUCCESS");
                     $this->flashMessenger()->addMessage('Students with Back Papers Added');
                     return $this->redirect()->toRoute('studentbackpaper');
                    } 
				catch(\Exception $e) {
					$message = 'Failure';
					$this->flashMessenger()->addMessage($e->getMessage());
					return $this->redirect()->toRoute('studentbackpaper');
					// Some DB Error happened, log it and let the user know
                }
            }
        }   
       return array(
            'form' => $form,
        );
	}
	
	//function to search and then display before adding
	public function studentBackYearAction()
    {
    	$this->loginDetails();	

	   $form = new StudentSearchForm();
	   
	   $studentList = array();
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
				$studentName = $this->getRequest()->getPost('student_name');
				$studentId = $this->getRequest()->getPost('student_id');
				$programme = $this->getRequest()->getPost('programme');
				$studentList = $this->examinationService->getStudentToAddList($studentName, $studentId, $programme, $this->organisation_id);
             }
         }
		
		return new ViewModel(array(
            'form' => $form,
			'studentList' => $studentList,
			'message' => $message,
			'keyphrase' => $this->keyphrase,
            ));
    }
	
	/* Just to check code. Delete once done
	    
	public function addAchievementsAction()
    {
    	$this->loginDetails();
        //get the student id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new AchievementsForm();
			$achievementModel = new Achievements();
			$form->bind($achievementModel);
			
			$studentDetail = $this->achievementService->getStudentDetails($tableName = 'student', $id);

			$achievements_category = $this->achievementService->listSelectData($tableName = 'student_achievements_category', $columnName = 'achievement_name', $this->organisation_id);

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
						 $this->achievementService->saveAchievements($achievementModel);
						 $this->notificationService->saveNotification('Student Achievements', $this->employee_details_id, $this->departments_id, 'Student Achievements');
	                     $this->auditTrailService->saveAuditTrail("INSERT", "Student Achievements", "ALL", "SUCCESS");
	                     $this->flashMessenger()->addMessage('Achievements Record was successfully added');
						 return $this->redirect()->toRoute('viewachievement');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
	             }
	         }
			 
	        return array(
				'form' => $form,
				'message' => $message,
				'organisation_id' => $this->organisation_id,
				'achievements_category' => $achievements_category,
				'studentDetail' => $studentDetail
			);
        }else{
        	$this->redirect()->toRoute('studentachievement');
        }
    }
	*/
	
	public function addStudentBackYearAction()
	{
		$this->loginDetails();

		//get the non eligibility id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$module_list = array();
			$assigned_module_list = array();
			$moduleCount = NULL;
			$semesterId = NULL;
			$programmesId = NULL;
			
			$form = new RepeatSemesterSearchForm();

			$semesterList = $this->examinationService->getSemesterList($this->organisation_id);
			$student_details = $this->examinationService->getStudentDetail($id);
			$studentDetails = $this->examinationService->getStudentDetail($id);
			$programmeList = $this->examinationService->listSelectData('programmes', 'programme_name', $this->organisation_id);

			$message = NULL;

			$request = $this->getRequest();
			if($request->isPost()){
				$form->setData($request->getPost());
				if($form->isValid()){
					$data['semester'] = $this->getRequest()->getPost('semester'); 
					$semesterId = $this->getRequest()->getPost('semester');
					$data['organisation_id'] = $this->organisation_id;
					$data['student_id'] = $id;
					$data['programmes_id'] = $student_details['programmes_id'];
					$programmesId = $student_details['programmes_id'];

					$check_repeat_modules = $this->examinationService->checkRepeatModuleList($student_details['student_id'], $data['programmes_id'], $data['semester']);

					//var_dump($check_repeat_modules); die();
					
					if(empty($check_repeat_modules)){
						$message = 'Failure'; 
						$this->flashMessenger()->addMessage("There are no single repeat module in this particular semester for this student. Please add repeate module and add to back year list");
						return $this->redirect()->toRoute('addstudentbackyear', array('id'=>$this->my_encrypt($id, $this->keyphrase)));
					}else{
						$module_list = $this->examinationService->getStudentAcademicModuleList($data);
						$assigned_module_list = $this->examinationService->getAssignedRepeatSemesterModules($data);
						$moduleCount = count($module_list);
					}
				}
			}

			$updateRepeatSemesterForm = new RepeatSemesterForm($moduleCount);

			return new ViewModel(array(
				'id' => $id,
				'form' => $form,
				'updateRepeatSemesterForm' => $updateRepeatSemesterForm,
				'studentDetails' => $studentDetails,
				'module_list' => $module_list,
				'semesterList' => $semesterList,
				'programmeList' => $programmeList,
				'assigned_module_list' => $assigned_module_list,
				'moduleCount' => $moduleCount,
				'semesterId' => $semesterId,
				'programmesId' => $programmesId,
				'message' => $message,
				'keyphrase' => $this->keyphrase,
			));
		}else{
			return $this->redirect()->toRoute('studentbackyear');
		}
	}

	//Function to update the repeat semester module in bulk
	public function updateRepeatSemesterModuleAction()
	{
		$this->loginDetails();

		$form = new RepeatSemesterForm($moduleCount='null');

		$message = NULL;
 
		$request = $this->getRequest();
        if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) { 
                $programmesId = $this->getRequest()->getPost('programmes_id');
				$studentId = $this->getRequest()->getPost('student_id');
				$semesterId = $this->getRequest()->getPost('semester');
                $module_data = $this->extractSemesterModuleData();
                try {
                     $this->examinationService->updateRepeatSemesterModule($studentId, $programmesId, $semesterId, $module_data, $this->organisation_id);
                     $this->auditTrailService->saveAuditTrail("INSERT", "Student Repeat Semester Modules", "ALL", "SUCCESS");					 
					 $this->flashMessenger()->addMessage("Repeat Semester Module was successfully updated");
					 return $this->redirect()->toRoute('studentbackyear');
                    } 
					catch(\Exception $e) {
						die($e->getMessage());
					}
            }
        }   
       return array(
			'form' => $form,
			'message' => $message
        );
	}
	
	public function declareResultsAction()
	{
		$this->loginDetails();
		$form = new ExamDeclarationForm();
		$message = NULL;
		
		$request = $this->getRequest();
		if($request->isPost()){
			$form->setData($request->getPost());
			if($form->isValid()){
				$organisation_id = $this->organisation_id;
				
				$semester_details = $this->examinationService->getSemester($organisation_id);
				$semester_session = $semester_details['academic_event'];
				$academic_year = $semester_details['academic_year'];

				$this->examinationService->declareSemesterResults($organisation_id);
				$this->flashMessenger()->addMessage('The Result has been declared successfully for '.$semester_session.' Semester of Academic Year '.$academic_year);
				return $this->redirect()->toRoute('declareresults');
			}
		}
		
		return new ViewModel(array(
			'form' => $form,
			'organisation_id' => $this->organisation_id,
			'message' => $message
		));
	}

	public function blockResultsAction()
	{
		$this->loginDetails();

		$form = new StudentSearchForm();

		$blockList = $this->examinationService->listAll('block_result', $this->organisation_id);
		$studentList = array();

		//var_dump($blockList);die();
	   
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData(
				$request->getPost());
			$data = array_merge_recursive(
				$request->getPost()->toArray(),
				$request->getFiles()->toArray()
			); 
			$form->setData($data); 
			if ($form->isValid()) {
				$studentName = $this->getRequest()->getPost('student_name');
				$studentId = $this->getRequest()->getPost('student_id');
				$programme = $this->getRequest()->getPost('programme');
				$studentList = $this->examinationService->getStudentToAddList($studentName, $studentId, $programme, $this->organisation_id);
			}
		}
		 
		return new ViewModel(array(
            'form' => $form,
			'studentList' => $studentList,
			'blockList' => $blockList,
			'keyphrase' => $this->keyphrase,
            ));
	}

    public function addBlockStudentAction()
	{
		$this->loginDetails();
		//get the invigilator assignment id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        if(is_numeric($id)){
        	try {
				$this->examinationService->saveBlockStudent($id);
				$this->auditTrailService->saveAuditTrail("Added", "Student Added in block Result list", "ALL", "SUCCESS");
				$this->flashMessenger()->addMessage('Student Added in Block Result list');
					return $this->redirect()->toRoute('blockresults');
			} catch(\Exception $e) {
				die($e->getMessage());
				// Some DB Error happened, log it and let the user know
			}
        }else{
        	$this->redirect()->toRoute('assignexaminvigilator');
        }
		
	}

	public function removeBlockStudentAction()
	{
		$this->loginDetails();
		//get the invigilator assignment id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        if(is_numeric($id)){
        	try {
				$this->examinationService->deleteBlockStudent($id);
				$this->auditTrailService->saveAuditTrail("Deleted", "Student Removed from block Result list", "ALL", "SUCCESS");
				$this->flashMessenger()->addMessage('Student Removed from Block Result list.');
					return $this->redirect()->toRoute('blockresults');
			} catch(\Exception $e) {
				die($e->getMessage());
				// Some DB Error happened, log it and let the user know
			}
        }else{
        	$this->redirect()->toRoute('assignexaminvigilator');
        }
		
	}
	
	public function semesterExamModerationByProgrammeAction()
	{
		$this->loginDetails();
		$form = new SemesterExamResultsForm($this->serviceLocator);
				
		$request = $this->getRequest();
		if($request->isPost()){
			$form->setData($request->getPost());
			if($form->isValid()){
				var_dump($form);
				die();
			}
		}
		
		return new ViewModel(array(
			'form' => $form
		));
	}
	
	private function extractFormData($student_ids)
	{
        $backpaper_data = array();
		
		foreach($student_ids as $key=>$value){
			if($this->getRequest()->getPost("CA_".$key)== 1){
				$backpaper_data["CA_".$key]= "CA";
			}
			if($this->getRequest()->getPost("SE_".$key)== 1){
				$backpaper_data["SE_".$key]= "SE";
			}
		}
		
        return $backpaper_data;
	}
	
	private function extractStudentIds($studentList)
	{
		$student_ids = array();
		foreach($studentList as $set)
		{
			$student_ids[$set['id']] = $set['id'];
		}
		return $student_ids;
	}

	public function extractSemesterModuleData()
    {
        $moduleCount = $this->getRequest()->getPost('moduleCount');
        $moduleData = array();

        for($i=1; $i<=$moduleCount; $i++){
			$moduleData[$i] = $this->getRequest()->getPost('module_'.$i);
        }

        return $moduleData;
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

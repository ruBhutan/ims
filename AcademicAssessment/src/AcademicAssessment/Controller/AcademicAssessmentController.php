<?php


namespace AcademicAssessment\Controller;

use AcademicAssessment\Service\AcademicAssessmentServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use AcademicAssessment\Form\DeleteAcademicAssessmentForm;
use AcademicAssessment\Form\DeleteCompiledAssessmentForm;
use AcademicAssessment\Form\StudentSearchForm;
use AcademicAssessment\Form\StudentRepeatSearchForm;
use AcademicAssessment\Form\StudentRepeatModuleMarkForm;
use AcademicAssessment\Form\StudentReassessmentModuleMarkForm;

//Session
use Zend\Session\Container;
//AJAX
use Zend\Http\Response\Stream;
use Zend\Http\Headers;
use Zend\Paginator\Adapter\DbSelect;
use Zend\View\Model\JsonModel;
use Zend\Form\Element\Select;

 
  
class AcademicAssessmentController extends AbstractActionController
{
    protected $academicAssessmentService;
    protected $notificationService;
    protected $auditTrailService;
    protected $serviceLocator;
    protected $username;
    protected $userrole;
    protected $userregion;
    protected $userDetails;
    protected $userImage;
    protected $employee_details_id;
    protected $organisation_id;
    protected $usertype;
    protected $parentValue;
    protected $parentValue1;

    protected $keyphrase = "RUB_IMS";

	
	public function __construct(AcademicAssessmentServiceInterface $academicAssessmentService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->academicAssessmentService = $academicAssessmentService;
        $this->notificationService = $notificationService;
        $this->auditTrailService = $auditTrailService;
        $this->serviceLocator = $serviceLocator;

        /*
         * To retrieve the user name from the session
        */
        $authPlugin = $serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
        $this->username = $authPlugin['username'];
        $this->userrole = $authPlugin['role'];
        $this->usertype = $authPlugin['user_type_id'];
        $this->userregion = $authPlugin['region'];

         /*
        * Getting the employee_details_id related to username
        */
        if($this->usertype == 1){
            $empData = $this->academicAssessmentService->getUserDetailsId($tableName = 'employee_details', $this->username);
            foreach($empData as $emp){
                $this->employee_details_id = $emp['id'];
            }
        }else if($this->usertype == 2){
            $stdData = $this->academicAssessmentService->getUserDetailsId($tableName = 'student', $this->username);
            foreach($stdData as $std){
                $this->student_id = $std['id'];
            }
        }        

        //get the organisation id
        if($this->usertype == 1){
            $organisationID = $this->academicAssessmentService->getOrganisationId($tableName = 'employee_details', $this->username);
            foreach($organisationID as $organisation){
                $this->organisation_id = $organisation['organisation_id'];
            }
        }else if($this->usertype == 2){
            //get the organisation id
            $stdOrganisationID = $this->academicAssessmentService->getOrganisationId($tableName = 'student', $this->username);
            foreach($stdOrganisationID as $organisation){
                $this->organisation_id = $organisation['organisation_id'];
            }
        }
        

        //get the user details such as name
        $this->userDetails = $this->academicAssessmentService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->academicAssessmentService->getUserImage($this->username, $this->usertype);
	}

    public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }


//To add Student Type Action

    /*This is action and route to view on index.html */
    public function deleteCompiledAssessmentMarksAction()
    {
        $this->loginDetails();
        $assessmentForm = new DeleteAcademicAssessmentForm($this->serviceLocator);

        $sectionList = $this->academicAssessmentService->listSelectData($tableName = 'student_section', $columnName = 'section');

        $message = NULL;

        $deleteCompiledAssessmentForm = NULL;

        $studentCompiledMarksList = array();
        $programmes_id = NULL;
        $academic_modules_allocation_id = NULL;
        $assessment_component_id = NULL;
        $section = NULL;

        $request = $this->getRequest();
         if ($request->isPost()) {
             $assessmentForm->setData($request->getPost());
             if ($assessmentForm->isValid()) { 
                 $data = $this->params()->fromPost(); 
                 $programmes_id = $this->getRequest()->getPost('programmes_id');
                 $academic_modules_allocation_id = $this->getRequest()->getPost('academic_modules_allocation_id');
                 $assessment_component_id = $this->getRequest()->getPost('assessment_component_id');
                 $section = $this->getRequest()->getPost('section');

                 $studentCompiledMarksList = $this->academicAssessmentService->getStudentCompiledMarksList($programmes_id, $academic_modules_allocation_id, $assessment_component_id, $section, $this->organisation_id);                
             }
         }

         $deleteCompiledAssessmentForm = new DeleteCompiledAssessmentForm();
         
         return array(
             //'form' => $form,
            'organisation_id' => $this->organisation_id,
            'assessmentForm' => $assessmentForm,
            'deleteCompiledAssessmentForm' => $deleteCompiledAssessmentForm,
            'sectionList' => $sectionList,
            //'assessmentType' => $assessmentType,
            'keyphrase' => $this->keyphrase,
            //'data' => $data,
            'studentCompiledMarksList' => $studentCompiledMarksList,
            'programmes_id' => $programmes_id,
            'academic_modules_allocation_id' => $academic_modules_allocation_id,
            'assessment_component_id' => $assessment_component_id,
            'section' => $section,
            'message' => $message,
        );
    } 
    
    
    public function deleteCompiledAssessmentAction()
    {
        $assessmentForm = new DeleteCompiledAssessmentForm();

        //$organisation_id = 1;
        $request = $this->getRequest();
        if ($request->isPost()) {
             $assessmentForm->setData($request->getPost());
             if ($assessmentForm->isValid()) {
                $programmes_id = $this->getRequest()->getPost('programmes_id');
                 $academic_modules_allocation_id = $this->getRequest()->getPost('academic_modules_allocation_id');
                 $assessment_component_id = $this->getRequest()->getPost('assessment_component_id');
                 $section = $this->getRequest()->getPost('section'); 

                 try {
                    $this->academicAssessmentService->deleteCompileAssessment($programmes_id, $academic_modules_allocation_id, $assessment_component_id, $section, $this->organisation_id);
                    $this->auditTrailService->saveAuditTrail("UPDATE", "Student Consolidated Marks", "ALL", "SUCCESS");
                    $this->auditTrailService->saveAuditTrail("INSERT", "Student Consolidated Marks", "ALL", "SUCCESS");
                    $this->flashMessenger()->addMessage('Compiled Marks was successfully deleted');
                    return $this->redirect()->toRoute('deletecompiledassessmentmarks');
                } 
                catch(\Exception $e) {
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                        return $this->redirect()->toRoute('deletecompiledassessmentmarks');
                 // Some DB Error happened, log it and let the user know
                    }
                }
        }   
       return array(
            'assessmentForm' => $assessmentForm,
        ); 
    }

    //to view the consolidated marks by Programme
    public function viewIndividualConsolidatedMarksAction()
    {
        
        $this->loginDetails();

        $moduleCreditList = NULL;
        $form = new StudentSearchForm();

        $programmeList = $this->academicAssessmentService->listSelectData1('programmes', 'programme_name', $this->organisation_id, $this->username);

        $semesterList = $this->academicAssessmentService->getSemesterList($this->organisation_id);
        
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
                    //Same form is being used for multiple purposes
                    //form label is year but we get the semester
                    $semester = $this->getRequest()->getPost('year');  

                    //get year given $semester
                    $year = ((int)$semester/2 + (int)$semester%2);
                    $current_semester = $this->academicAssessmentService->getSemester($this->organisation_id);
                    $temp_academic_years = explode("-", $academic_year);
                    $batch = $temp_academic_years[0]-((int) $year-1);
                    $studentMarkList = $this->academicAssessmentService->getStudentConsolidatedMarks($programme, $academic_year, $semester, $this->username);
                    $moduleCreditList = $this->academicAssessmentService->getModuleCreditList($programme, $academic_year, $semester, $this->username);
                    $studentList = $this->academicAssessmentService->getBasicStudentNameList($programme, $academic_year, $semester);

                    
            }
        }
         
        return array(
            'form' => $form,
            'programmeList' => $programmeList,
            'semesterList' => $semesterList,
            'academicYearList' => $academicYearList,
            'studentMarkList' => $studentMarkList,
            'studentList' => $studentList,
            'moduleCreditList' => $moduleCreditList,
            'keyphrase' => $this->keyphrase,
        );
        
    } 

    public function addRepeatConsolidatedMarksAction()  {

        $this->loginDetails();

        $stdId = NULL;
        $stdSemester = NULL;
        $repeatModuleDetail = array();
        $studentDatail = array();
        $studentCount = NULL;

    
        $form = new StudentRepeatSearchForm();

        $message = NULL;

        $tableName = 'student_semester';
        $columnName = 'semester';
        $semesterList = $this->academicAssessmentService->listSelectData($tableName, $columnName);

        $repeatModuleDetail = $this->academicAssessmentService->listAll('repeatModuleDetail',$stdId, $stdSemester, $this->organisation_id, $this->username);

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
                $stdId = $this->getRequest()->getPost('student_id');
                $stdSemester = $this->getRequest()->getPost('semester');

                $studentDatail = $this->academicAssessmentService->getStudentLists('studentDetail',$stdId, $stdSemester, $this->organisation_id, $this->username, $this->userrole);

                $repeatModuleDetail = $this->academicAssessmentService->getStudentLists('repeatModuleDetail',$stdId, $stdSemester, $this->organisation_id, $this->username, $this->userrole);
                
                if(!empty($repeatModuleDetail)){
                    $studentCount = count($repeatModuleDetail);
                }
                //var_dump($listMarksDatail['module_title']); die();
            }
        }
        

        $addForm = new StudentRepeatModuleMarkForm();

        return array(
            'form' => $form,
            'addForm' => $addForm,
            'semesterList' => $semesterList,
            'stdId' => $stdId,
            'stdSemester' => $stdSemester,
            'repeatModuleDetail' => $repeatModuleDetail,
            'studentDatail' => $studentDatail,
            'studentCount' => $studentCount,
            'organisation_id' => $this->organisation_id,
            'usertype' => $this->usertype,
            'keyphrase' => $this->keyphrase,
            'message' => $message,
        );
    }

    public function editReConsolidatedMarksAction()  {

        $this->loginDetails();

        $stdId = NULL;
        $stdSemester = NULL;
        $repeatModuleDetail = array();
        $studentDatail = array();
        $studentCount = NULL;

    
        $form = new StudentRepeatSearchForm();

        $message = NULL;

        $tableName = 'student_semester';
        $columnName = 'semester';
        $semesterList = $this->academicAssessmentService->listSelectData($tableName, $columnName);

        //$repeatModuleDetail = $this->academicAssessmentService->listAll('reModuleDetail',$stdId, $stdSemester, $this->organisation_id, $this->username);

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
                $stdId = $this->getRequest()->getPost('student_id');
                $stdSemester = $this->getRequest()->getPost('semester');

                $studentDatail = $this->academicAssessmentService->getStudentLists('studentDetail',$stdId, $stdSemester, $this->organisation_id, $this->username, $this->userrole);

                $repeatModuleDetail = $this->academicAssessmentService->getStudentLists('reModuleDetail',$stdId, $stdSemester, $this->organisation_id, $this->username, $this->userrole);
                
                if(!empty($repeatModuleDetail)){
                    $studentCount = count($repeatModuleDetail);
                }
                //var_dump($listMarksDatail['module_title']); die();
            }
        }
        

        $addForm = new StudentRepeatModuleMarkForm();

        return array(
            'form' => $form,
            'addForm' => $addForm,
            'semesterList' => $semesterList,
            'stdId' => $stdId,
            'stdSemester' => $stdSemester,
            'repeatModuleDetail' => $repeatModuleDetail,
            'studentDatail' => $studentDatail,
            'studentCount' => $studentCount,
            'organisation_id' => $this->organisation_id,
            'usertype' => $this->usertype,
            'keyphrase' => $this->keyphrase,
            'message' => $message,
        );
    }
    
    public function insertRepeatConsolidatedMarkAction()
    {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){       
            $form = new StudentRepeatModuleMarkForm();

            $request = $this->getRequest();
            if($request->isPost()){
                $form->setData($request->getPost());
                if($form->isValid()){
                    $data['assessment'] = $this->getRequest()->getPost('assessment'); 
                    $data['marks'] = $this->getRequest()->getPost('marks'); 
                    $data['programme_name'] = $this->getRequest()->getPost('programme_name'); 
                    $data['academic_modules_allocation_id'] = $this->getRequest()->getPost('academic_modules_allocation_id'); 
                    $data['module_title'] = $this->getRequest()->getPost('module_title'); 
                    $data['backlog_semester'] = $this->getRequest()->getPost('backlog_semester'); 
                    $data['module_code'] = $this->getRequest()->getPost('module_code'); 
                    $data['module_credit'] = $this->getRequest()->getPost('module_credit'); 
                    $data['weightage'] = $this->getRequest()->getPost('weightage'); 
                    $data['programmes_id'] = $this->getRequest()->getPost('programmes_id'); 
                    $data['backlog_academic_year'] = $this->getRequest()->getPost('backlog_academic_year'); 
                    $data['examination_type'] = 'Repeat Module'; 
                    $data['student_id'] = $this->getRequest()->getPost('student_id');  

                    if($data['marks']>$data['weightage']){
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage('<strong>Failure!</strong>Marks Entered is higher than weightage');
                        return $this->redirect()->toRoute('addrepeatconsolidatedmarks');
                    } else {

                        $this->academicAssessmentService->inserRepeatConsolidatedMark($data, $this->organisation_id, $id);
                        $this->auditTrailService->saveAuditTrail("UPDATE", "Student Consolidated_Marks", "ALL", "SUCCESS");
                        $this->flashMessenger()->addMessage('Repeated Consolidated Marks was successfully updated');
                        return $this->redirect()->toRoute('addrepeatconsolidatedmarks');    
                    } 
                }
            }

            return array(
                'form' => $form,
                'usertype' => $this->usertype,
                'keyphrase' => $this->keyphrase,
            );
        }else{
            return $this->redirect()->toRoute('addrepeatconsolidatedmarks');
        }        
    }

    public function updateReConsolidatedMarkAction()
    {
        //var_dump(asdfs); die;
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){       
            $form = new StudentRepeatModuleMarkForm();

            $request = $this->getRequest();
            if($request->isPost()){
                $form->setData($request->getPost());
                if($form->isValid()){
                    $data['assessment'] = $this->getRequest()->getPost('assessment'); 
                    $data['marks'] = $this->getRequest()->getPost('marks'); 
                    $data['programme_name'] = $this->getRequest()->getPost('programme_name'); 
                    $data['academic_modules_allocation_id'] = $this->getRequest()->getPost('academic_modules_allocation_id'); 
                    $data['module_title'] = $this->getRequest()->getPost('module_title'); 
                    $data['backlog_semester'] = $this->getRequest()->getPost('backlog_semester'); 
                    $data['module_code'] = $this->getRequest()->getPost('module_code'); 
                    $data['module_credit'] = $this->getRequest()->getPost('module_credit'); 
                    $data['weightage'] = $this->getRequest()->getPost('weightage'); 
                    $data['programmes_id'] = $this->getRequest()->getPost('programmes_id'); 
                    $data['backlog_academic_year'] = $this->getRequest()->getPost('backlog_academic_year'); 
                    $data['examination_type'] = 'Repeat Module'; 
                    $data['student_id'] = $this->getRequest()->getPost('student_id');  

                    if($data['marks']>$data['weightage']){
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage('<strong>Failure!</strong>Marks Entered is higher than weightage');
                        return $this->redirect()->toRoute('editreconsolidatedmarks');
                    } else {

                        $this->academicAssessmentService->updateReConsolidatedMark($data, $this->organisation_id, $id);
                        $this->auditTrailService->saveAuditTrail("UPDATE", "Student Consolidated_Marks", "ALL", "SUCCESS");
                        $this->flashMessenger()->addMessage('Consolidated Marks was successfully updated');
                        return $this->redirect()->toRoute('editreconsolidatedmarks');    
                    } 
                }
            }

            return array(
                'form' => $form,
                'usertype' => $this->usertype,
                'keyphrase' => $this->keyphrase,
            );
        }else{
            return $this->redirect()->toRoute('addrepeatconsolidatedmarks');
        }        
    }

    public function addReAssessmentMarksAction()  {

        $this->loginDetails();

        $stdId = NULL;
        $stdSemester = NULL;
        $repeatModuleDetail = array();
        $repeatModuleAssessment = array();
        $studentDetail = array();
        $moduleAssessmentCount = 0;
    
        $form = new StudentRepeatSearchForm();

        $message = NULL;

        $tableName = 'student_semester';
        $columnName = 'semester';
        $semesterList = $this->academicAssessmentService->listSelectData($tableName, $columnName);
        
        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
                $stdId = $this->getRequest()->getPost('student_id');
                $stdSemester = $this->getRequest()->getPost('semester');


                $studentDetail = $this->academicAssessmentService->getStudentLists('studentDetail',$stdId, $stdSemester, $this->organisation_id, $this->username, $this->userrole);

                $repeatModuleDetail = $this->academicAssessmentService->getStudentLists('reAssessmentDetail',$stdId, $stdSemester, $this->organisation_id, $this->username, $this->userrole);
                //$repeatModuleAssessment = $this->academicAssessmentService->getStudentLists('repeatModuleAssessment', $stdId, $stdSemester, $this->organisation_id, $this->username, $this->userrole);
                
                if(!empty($repeatModuleDetail)){
                    $moduleAssessmentCount = count($repeatModuleDetail);
                }
                //var_dump($listMarksDatail['module_title']); die();
            }
        }
        

        $addForm = new StudentReassessmentModuleMarkForm($moduleAssessmentCount);

        return array(
            'form' => $form,
            'addForm' => $addForm,
            'semesterList' => $semesterList,
            'stdId' => $stdId,
            'stdSemester' => $stdSemester,
            'repeatModuleDetail' => $repeatModuleDetail,
            'repeatModuleAssessment' => $repeatModuleAssessment,
            'studentDetail' => $studentDetail,
            'moduleAssessmentCount' => $moduleAssessmentCount,
            'organisation_id' => $this->organisation_id,
            'usertype' => $this->usertype,
            'keyphrase' => $this->keyphrase,
            'message' => $message,
        );
    }

    public function insertReAssessmentMarkAction()
    {
        $this->loginDetails(); 
        /*$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase); */
        
        /*if(is_numeric($id)){  */     
            $form = new StudentReassessmentModuleMarkForm($moduleAssessmentCount = NULL); 


            $request = $this->getRequest(); 
            if($request->isPost()){ 
                $form->setData($request->getPost()); 
                if($form->isValid()){ 
                    $data = $this->extractFormData(); 

                    $moduleData['backlog_semester'] = $this->getRequest()->getPost('backlog_semester');
                    //$moduleData['examination_type'] = 'Re-assessment'; 
                    $moduleData['student_id'] = $this->getRequest()->getPost('student_id');  
                
                    $this->academicAssessmentService->insertReAssessmentMark($data, $moduleData, $this->organisation_id, $this->username, $this->userrole);
                    $this->auditTrailService->saveAuditTrail("UPDATE", "Student Consolidated_Marks", "ALL", "SUCCESS");
                    $this->flashMessenger()->addMessage('Repeated Consolidated Marks was successfully updated');
                    return $this->redirect()->toRoute('addreassessmentmarks');
                    
                }
            }

            return array(
                'form' => $form,
                'usertype' => $this->usertype,
                'keyphrase' => $this->keyphrase,
            );
       /* }else{
            return $this->redirect()->toRoute('addreassessmentmarks');
        }  */      
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

    //the following function is to extract the data from the form 
    // and return clean data to be inserted into database
    public function extractFormData()
    {
        $moduleAssessmentCount = $this->getRequest()->getPost('moduleAssessmentCount');

        $assessmentData = array();
        
        //evaluation data => 'evaluation_'.$i.$j,
        for($i=1; $i<=$moduleAssessmentCount; $i++)
        {
            $assessmentData[$i]= $this->getRequest()->getPost('marks_'.$i);
        }

        return $assessmentData;
    }
    



}
             

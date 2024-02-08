<?php


namespace StudentAdmission\Controller;

use StudentAdmission\Service\StudentAdmissionServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use StudentAdmission\Model\StudentAdmission;
use StudentAdmission\Model\RegisterStudent;
use StudentAdmission\Model\UpdateStudent;
use StudentAdmission\Model\UpdateReportedStudentDetails;
use StudentAdmission\Model\AddNewStudent;
use StudentAdmission\Model\StudentType;
use StudentAdmission\Model\StudentHouse;
use StudentAdmission\Model\StudentCategory;
use StudentAdmission\Model\UploadStudentLists;
use StudentAdmission\Model\StudentSemesterRegistration;
use StudentAdmission\Model\UpdateStudentPersonalDetails;
use StudentAdmission\Model\UpdateStudentPermanentAddr;
use StudentAdmission\Model\StudentRelationDetails;
use StudentAdmission\Model\UpdateStudentParentDetails;
use StudentAdmission\Model\UpdateStudentGuardianDetails;
use StudentAdmission\Model\StudentPreviousSchool;
use StudentAdmission\Model\UpdateStudentPreviousSchool;
use StudentAdmission\Model\StudentChangeProgramme;
//use StudentAdmission\Model\UpdateChangeProgramme;
//use StudentAdmission\Model\ImportForm;
use Zend\Session\Container;

use StudentAdmission\Form\RegisterStudentForm;
use StudentAdmission\Form\UpdateStudentForm;
use StudentAdmission\Form\UpdateReportedStudentDetailsForm;
use StudentAdmission\Form\UpdateStudentPersonalDetailsForm;
use StudentAdmission\Form\AddNewStudentForm;
use StudentAdmission\Form\StudentTypeForm;
use StudentAdmission\Form\StudentHouseForm;
use StudentAdmission\Form\StudentCategoryForm;
use StudentAdmission\Form\UploadStudentListsForm;
use StudentAdmission\Form\NewStudentSearchForm;
use StudentAdmission\Form\NewReportedStudentSearchForm;
use StudentAdmission\Form\CollegeReportedStudentSearchForm;
use StudentAdmission\Form\NewStudentListSearchForm;
use StudentAdmission\Form\ReportNewStudentForm;
use StudentAdmission\Form\UpdateStudentSectionForm;
use StudentAdmission\Form\AddStudentSectionForm;
use StudentAdmission\Form\AddStudentHouseForm;
use StudentAdmission\Form\EditStudentListSearchForm;
use StudentAdmission\Form\EditStudentSectionForm;
use StudentAdmission\Form\EditStudentHouseForm;
use StudentAdmission\Form\StudentSemesterRegistrationForm;
use StudentAdmission\Form\StudentSemesterSearchForm;
use StudentAdmission\Form\StudentDetailSearchForm;
use StudentAdmission\Form\StudentDetailsForm;
use StudentAdmission\Form\SubmitStudentReportedForm;
use StudentAdmission\Form\GenerateBulkStudentIdForm;
use StudentAdmission\Form\StudentSearchForm;
use StudentAdmission\Form\StudentRelationDetailsForm;
use StudentAdmission\Form\UpdateStudentPermanentAddrForm;
use StudentAdmission\Form\UpdateStudentParentDetailsForm;
use StudentAdmission\Form\UpdateStudentGuardianDetailsForm;
use StudentAdmission\Form\StudentPreviousSchoolForm;
use StudentAdmission\Form\UpdateStudentPreviousSchoolForm;
use StudentAdmission\Form\StdChangeProgrammeSearchForm;
use StudentAdmission\Form\ChangeProgrammeForm;
use StudentAdmission\Form\ChangeProgrammeSearchForm;
use StudentAdmission\Form\UpdateStudentSemesterForm;
use StudentAdmission\Form\ParentPortalAccessForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

//AJAX
use Zend\Http\Response\Stream;
use Zend\Http\Headers;
use Zend\Paginator\Adapter\DbSelect;
use Zend\View\Model\JsonModel;
use Zend\Form\Element\Select;

 
  
class StudentAdmissionController extends AbstractActionController
{
    protected $studentAdmissionService;
    protected $notificationService;
    protected $auditTrailService;
    protected $serviceLocator;
    protected $username;
    protected $userrole;
    protected $userregion;
    protected $userDetails;
    protected $userImage;
    protected $employee_details_id;
    protected $student_id;
    protected $organisation_id;
    protected $usertype;
    protected $parentValue;
    protected $parentValue1;

    protected $keyphrase = "RUB_IMS";

	
	public function __construct(StudentAdmissionServiceInterface $studentAdmissionService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->studentAdmissionService = $studentAdmissionService;
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
            $empData = $this->studentAdmissionService->getUserDetailsId($tableName = 'employee_details', $this->username);
            foreach($empData as $emp){
                $this->employee_details_id = $emp['id'];
            }
        }else if($this->usertype == 2){
            $stdData = $this->studentAdmissionService->getUserDetailsId($tableName = 'student', $this->username);
            foreach($stdData as $std){
                $this->student_id = $std['id'];
            }
        }        

        //get the organisation id
        if($this->usertype == 1){
            $organisationID = $this->studentAdmissionService->getOrganisationId($tableName = 'employee_details', $this->username);
            foreach($organisationID as $organisation){
                $this->organisation_id = $organisation['organisation_id'];
            }
        }else if($this->usertype == 2){
            //get the organisation id
            $stdOrganisationID = $this->studentAdmissionService->getOrganisationId($tableName = 'student', $this->username);
            foreach($stdOrganisationID as $organisation){
                $this->organisation_id = $organisation['organisation_id'];
            }
        }
        

        //get the user details such as name
        $this->userDetails = $this->studentAdmissionService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->studentAdmissionService->getUserImage($this->username, $this->usertype);
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

    public function addStudentTypeAction()
     {
        $this->loginDetails();
        $form = new StudentTypeForm();
        $studentAdmissionModel = new StudentType();
        $form->bind($studentAdmissionModel);

        $studentType = $this->studentAdmissionService->listAllStudentType($tableName = 'student_type');
        $message = NULL;

         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());

             $data = $this->params()->fromPost();
             $stdType = $data['studenttype']['student_type'];
             $studenttype = $this->studentAdmissionService->crossCheckStudentType($stdType);

             if($studenttype){
                $message = 'Failure';
                $this->flashMessenger()->addMessage('You have already added this student type. Please try for different name.');
             }else{
                if ($form->isValid()) {
                     try {
                         $this->studentAdmissionService->saveStudentType($studentAdmissionModel);
                         $this->auditTrailService->saveAuditTrail("INSERT", "Student Type", "ALL", "SUCCESS");
                         $this->flashMessenger()->addMessage('Student Type was successfully added');
                        return $this->redirect()->toRoute('add-student-type');
                     }
                     catch(\Exception $e) {
                         $message = 'Failure';
                            $this->flashMessenger()->addMessage($e->getMessage());
                            return $this->redirect()->toRoute('add-student-type');
                            // die($e->getMessage());
                             // Some DB Error happened, log it and let the user know
                     }
                 }
             }
         }

         return array(
             'form' => $form,
             'studentType' => $studentType,
             'keyphrase' => $this->keyphrase,
             'message' => $message,
         );
     }



    //To edit Student Type Action

    public function editStudentTypeAction()
    {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $studentTypeDetails = $this->studentAdmissionService->findStudentType($id);
        
            $form = new StudentTypeForm();
            $studentAdmissionModel = new StudentType();
            $form->bind($studentAdmissionModel);

            $message = NULL;

            $request = $this->getRequest();
            if($request->isPost()){
                $form->setData($request->getPost());
                if($form->isValid()){
                    try{
                        
                        $this->studentAdmissionService->saveStudentType($studentAdmissionModel);
                        $this->auditTrailService->saveAuditTrail("EDIT", "Student Type", "ALL", "SUCCESS");
                        $this->flashMessenger()->addMessage('Student Type was successfully edited');
                        return $this->redirect()->toRoute('add-student-type');
                    }
                    catch(\Exception $e){
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                        return $this->redirect()->toRoute('add-student-type');
                    }
                
                }
            }

            return array(
                'form' => $form,
                'studentTypeDetails' =>$studentTypeDetails,
                'message' => $message,
                );
        }else{
            return $this->redirect()->toRoute('add-student-type');
        }
    }
    


    //To add Student Category Action

    public function addStudentCategoryAction()
    {
        $this->loginDetails();
        $form = new StudentCategoryForm();
        $studentAdmissionModel = new StudentCategory();
        $form->bind($studentAdmissionModel);

         $Categories = $this->studentAdmissionService->listAllStudentCategory($tableName = 'student_category');
         $message = NULL;
       
         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());

             $data = $this->params()->fromPost();
             $stdCategory = $data['studentcategory']['student_category'];
             $studentcategory = $this->studentAdmissionService->crossCheckStudentCategory($stdCategory);

             if($studentcategory){
                $message = 'Failure';
                $this->flashMessenger()->addMessage('You have already added this type of student category. Please try for different name.');
             }else{
                if ($form->isValid()) {
                     try {
                         $this->studentAdmissionService->saveStudentCategory($studentAdmissionModel);
                         $this->auditTrailService->saveAuditTrail("INSERT", "Student Category", "ALL", "SUCCESS");
                        $this->flashMessenger()->addMessage('Student Category was successfully added');
                        return $this->redirect()->toRoute('add-student-category');
                     }
                     catch(\Exception $e) {
                        $message = 'Failure';
                            $this->flashMessenger()->addMessage($e->getMessage());
                            return $this->redirect()->toRoute('add-student-category');
                            // die($e->getMessage());
                             // Some DB Error happened, log it and let the user know
                     }
                 }
             }
         }
           return array(
             'form' => $form,
             'Categories' => $Categories,
             'keyphrase' => $this->keyphrase,
             'message' => $message
         );
    }

    //To edit Student Category Action

    public function editStudentCategoryAction()
    {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $studentCategoryDetails = $this->studentAdmissionService->findStudentCategory($id);
        
            $form = new StudentCategoryForm();
            $studentAdmissionModel = new StudentCategory();
            $form->bind($studentAdmissionModel);

            $message = NULL;

            $request = $this->getRequest();
            if($request->isPost()){
                $form->setData($request->getPost());
                if($form->isValid()){
                    try{
                        $this->studentAdmissionService->saveStudentCategory($studentAdmissionModel);
                        $this->auditTrailService->saveAuditTrail("EDIT", "Student Category", "ALL", "SUCCESS");
                        $this->flashMessenger()->addMessage('Student Category was successfully edited');
                        return $this->redirect()->toRoute('add-student-category');
                    }
                    catch(\Exception $e){
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                        return $this->redirect()->toRoute('add-student-category');
                        //Some DB Error happened, log it and let the user know  
                    }
                
                }
            }

            return array(
                'form' => $form,
                'studentCategoryDetails' =>$studentCategoryDetails,
                'message' => $message,
                );
        }else{
            return $this->redirect()->toRoute('add-student-category');
        }
    }


/*student registration done from OVC  */
    public function registerNewStudentAction()
    {
        $this->loginDetails();        

        $form = new RegisterStudentForm($this->serviceLocator);
        $studentAdmissionModel = new RegisterStudent();
        $form->bind($studentAdmissionModel);

        $studentType = $this->studentAdmissionService->listSelectData($tableName = 'student_type', $columnName = 'student_type', NULL);
        $studentGender = $this->studentAdmissionService->listSelectData($tableName = 'gender',  $columnName = 'gender', NULL);
        $relationType = $this->studentAdmissionService->listSelectData($tableName = 'relation_type', $columnName = 'relation', NULL); 

        $message = NULL;       
       
         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());

             $data = $this->params()->fromPost();
             $cid = $data['registerstudent']['cid'];
             $check_student = $this->studentAdmissionService->crossCheckRegisterStudent($cid, $tableName = 'student_registration');

             if($check_student){
                $message = 'Failure';
                $this->flashMessenger()->addMessage('Student with same cid no or passport number has been registered. Please check cid number or passport number and register again.');
             }else{
                if ($form->isValid()) {    
                     try {
                        //getting data from ajax. so need to extract them and pass as variables
                        $organisation_id = $this->getRequest()->getPost('organisation_id');
                        $programme_id = $this->getRequest()->getPost('programme_id');

                        $this->studentAdmissionService->saveRegisteredStudent($studentAdmissionModel, $organisation_id, $programme_id);
                        $this->notificationService->saveNotification('New Student Registration', $organisation_id, $organisation_id, 'Student Admission');
                        $this->auditTrailService->saveAuditTrail("INSERT", "Student Registration", "ALL", "SUCCESS");
                        $this->flashMessenger()->addMessage('Student was successfully registered');
                        return $this->redirect()->toRoute('registered-student-list');
                     }
                     catch(\Exception $e) {
                            $message = 'Failure';
                            $this->flashMessenger()->addMessage($e->getMessage());
                            return $this->redirect()->toRoute('registered-student-list');
                             // Some DB Error happened, log it and let the user know
                     }
                 }
             }
         }
           return array(
             'form' => $form,
             'studentType' => $studentType,
             'studentGender' => $studentGender,
             'relationType' => $relationType,
             'message' => $message,
         );
    }

/*Display basic  registration  details done from OVC  */
     public function registeredStudentListAction()
    {
        $this->loginDetails();

        $form = new NewStudentSearchForm($this->serviceLocator);

        $message = NULL;

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
                $stdOrganisation = $this->getRequest()->getPost('organisation');
                $stdProgramme = $this->getRequest()->getPost('programme');
                $stdYear = $this->getRequest()->getPost('admission_year');
                $stdGender = $this->getRequest()->getPost('gender');
                $stdReportStatus = $this->getRequest()->getPost('student_reporting_status');
                $registerStudentList = $this->studentAdmissionService->getRegisteredStudentList($stdOrganisation, $stdProgramme, $stdYear, $stdGender, $stdReportStatus);
            }
        }

        else {
            $registerStudentList = array();
        }

        return new ViewModel(array(
            'form' => $form,
            'registerStudentList' => $registerStudentList,
            'organisation_id' => $this->organisation_id,
            'employee_details_id' => $this->employee_details_id,
            'keyphrase' => $this->keyphrase,
            'message' => $message,
            ));
    } 

/*to update student report to the colloege */
	public function newRegisteredStudentDetailsAction()
    {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase); 

        if(is_numeric($id)){
            $student_details = $this->studentAdmissionService->getNewRegisteredStudentDetails($id); 
            $stdPermanentAddr = $this->studentAdmissionService->getStudentPermanentAddrDetails($id, 'NEW');
            return array(
                'student_details' => $student_details,
                'stdPermanentAddr' => $stdPermanentAddr,
            );
        }else{
            return $this->redirect()->toRoute('new-registered-student-list');
        }
    }

    /*to view the registered student details by OVC */
    public function registeredStudentDetailsAction()
    {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){ 
            $details = $this->studentAdmissionService->findRegisteredStudentDetails($id); 

            $form = new UpdateStudentForm();
            $studentAdmissionModel = new UpdateStudent();
            $form->bind($studentAdmissionModel);
           
             $request = $this->getRequest();
             if ($request->isPost()) {
                 $form->setData($request->getPost());
                 if ($form->isValid()) {
                     try {
                     }
                     catch(\Exception $e) {
                             die($e->getMessage());
                             // Some DB Error happened, log it and let the user know
                     }
                 }
             }
               return array(
                'form' => $form,
                'details' => $details,
             );
        }else{
            $this->redirect()->toRoute('registered-student-list');
        }
    }

/*to update student report to the colloege */
    public function reportNewRegisteredStudentAction()
    {
        $details = $this->studentAdmissionService->findNewRegisteredStudentDetails($this->params('id'));

        $form = new UpdateStudentForm();
        $studentAdmissionModel = new UpdateStudent();
        $form->bind($studentAdmissionModel);

        $tableName = 'student_semester';
        $columnName = 'semester';
        $studentSemester = $this->studentAdmissionService->listSelectData($tableName, $columnName, NULL);
       
         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
                        $studentAdmissionData = $this->studentAdmissionService->saveNewReportedStudent($studentAdmissionModel);
                    
                        $lastGeneratedId = $studentAdmissionData->getId();
                        $this->redirect()->toRoute('view-new-student-details', array('id' => $lastGeneratedId));
                   // $this->studentAdmissionService->saveNewReportedStudent($studentAdmissionModel);
                     
                    //return $this->redirect()->toRoute('new-registered-student-list');
                 }
                 catch(\Exception $e) {
                         die($e->getMessage());
                         // Some DB Error happened, log it and let the user know
                 }
             }
         }
           return array(
            'form' => $form,
            'details' => $details,
            'studentSemester' => $studentSemester,
         );
    }

   

    /*Display new registered student list  */
     public function newRegisteredStudentListAction()
    {
        $this->loginDetails();

        $newStudentList = array();
        $stdProgramme = NULL;
        $studentCount = 0;
        $reportNewStudentForm = NULL;

        $form = new NewStudentListSearchForm($this->serviceLocator);

        $message = NULL;

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
                $stdProgramme = $this->getRequest()->getPost('programmes_id'); 
                $newStudentList = $this->studentAdmissionService->findNewStudentList($stdProgramme);
                $studentCount = count($newStudentList);

            }
        }

        $reportNewStudentForm = new ReportNewStudentForm($studentCount);

        return new ViewModel(array(
            'form' => $form,
            'newStudentList' => $newStudentList,
            'organisation_id' => $this->organisation_id,
            'stdProgramme' => $stdProgramme,
            'employee_details_id' => $this->employee_details_id,
            'studentCount' => $studentCount,
            'keyphrase' => $this->keyphrase,
            'reportNewStudentForm' => $reportNewStudentForm,
            'message' => $message,
            ));
    }

	
    public function reportNewStudentAction()
    {
        $form = new ReportNewStudentForm($studentCount = 'null');

        //$organisation_id = 1;
        $request = $this->getRequest();
        if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                $stdProgramme = $this->getRequest()->getPost('programmes_id');
                $new_student_data = $this->extractNewStudentData(); 

                 try {
                    $this->studentAdmissionService->updateNewStudentStatus($new_student_data, $status = 'Reported', $this->organisation_id, $stdProgramme);
                    $this->auditTrailService->saveAuditTrail("UPDATE", "Student Registration", "ALL", "SUCCESS");
                    $this->auditTrailService->saveAuditTrail("INSERT", "Student", "ALL", "SUCCESS");
                    $this->flashMessenger()->addMessage('Student was successfully reported');
                    return $this->redirect()->toRoute('new-registered-student-list');
                } 
                catch(\Exception $e) {
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                        return $this->redirect()->toRoute('new-registered-student-list');
                 // Some DB Error happened, log it and let the user know
                    }
                }
        }   
       return array(
            'form' => $form,
        ); 
        //get the id of the new registered student list
       /* $id = (int) $this->params()->fromRoute('id', 0);
        
        try {
             $this->studentAdmissionService->updateNewStudentStatus($status='Reported', $previousStatus=NULL, $id, $this->organisation_id);
             $this->flashMessenger()->addMessage('Student was successfully reported');
             $this->redirect()->toRoute('new-registered-student-list');
         }
         catch(\Exception $e) {
                 die($e->getMessage());
                 // Some DB Error happened, log it and let the user know
         }
         
        return array();*/
    }



    // Function to get the list of student to generate the student id
    public function newReportedStudentListAction()
    {
        $this->loginDetails();

        $form = new GenerateBulkStudentIdForm($this->serviceLocator);

        $message = NULL;

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
                $stdProgramme = $this->getRequest()->getPost('programmes_id');
                $checkNotAssignedId = $this->studentAdmissionService->crossCheckStudentNotAssignedId($stdProgramme);
                if($checkNotAssignedId){
                    $this->studentAdmissionService->assignStudentId($stdProgramme, $this->organisation_id);
                    $this->auditTrailService->saveAuditTrail("UPDATE", "Student", "ALL", "SUCCESS");
                    $this->flashMessenger()->addMessage('Student ID was successfully generated');
                    return $this->redirect()->toRoute('new-reported-student-list');
                }else{
                    $message = 'Failure';
                    $this->flashMessenger()->addMessage('You have already assigned STUDENT ID or there is no new reported student for the selected programme. Please check for other programme.');
                   // return $this->redirect()->toRoute('new-reported-student-list');
                }
            }
        }

       // $submitForm = new SubmitStudentReportedForm();

        return new ViewModel(array(
            'form' => $form,
            //'generatedStudentIdList' => $generatedStudentIdList,
            'generatedStudentIdList' => $this->studentAdmissionService->getGeneratedStudentIdList($this->organisation_id),
            'organisation_id' => $this->organisation_id,
            'employee_details_id' => $this->employee_details_id,
            'message' => $message,
            ));
    }


      //Function to list the reported student list of the year and by mistake if not reported student also checked as report then use this function to delete the student and change the status as pending
     public function reportedStudentListAction()
    {
        $this->loginDetails();

        $form = new NewReportedStudentSearchForm($this->serviceLocator);

        $message = NULL;

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
                $stdProgramme = $this->getRequest()->getPost('programme'); 
                $stdName = $this->getRequest()->getPost('student_name');
                $stdCid = $this->getRequest()->getPost('cid');
                $admissionYear = $this->getRequest()->getPost('admission_year');
                $reportedStudentList = $this->studentAdmissionService->getReportedStudentList($stdName, $stdCid, $stdProgramme, $admissionYear);
            }
        }

        else {
            $reportedStudentList = array();
        }

        return new ViewModel(array(
            'form' => $form,
            'reportedStudentList' => $reportedStudentList,
            'organisation_id' => $this->organisation_id,
            'employee_details_id' => $this->employee_details_id,
            'keyphrase' => $this->keyphrase,
            'message' => $message,
            ));
    }


     /*To update student reported details from College */
    public function deleteNotReportedStudentAction()
    {

        $this->loginDetails();
         
         //get the id of the travel authorization proposal
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){ 
             try {
                 $result = $this->studentAdmissionService->deleteNotReportedStudent($id);
                 $this->auditTrailService->saveAuditTrail("DELETE", "Student", "ALL", "SUCCESS");
                 $this->auditTrailService->saveAuditTrail("UPDATE", "Student Registration", "Student Reporting Status", "SUCCESS");
                 $this->flashMessenger()->addMessage("You have successfully deleted the student");
                 return $this->redirect()->toRoute('reported-student-list');
                 //return $this->redirect()->toRoute('emptraveldetails');
             }
             catch(\Exception $e) {
                     die($e->getMessage());
                     // Some DB Error happened, log it and let the user know
             }
        }else {
            return $this->redirect()->toRoute('reported-student-list');
        }
    } 

/* Add Masters/Exchange Programme/New Student  like King or Queen Scholarship*/
    public function addNewStudentAction()
    {        
        $this->loginDetails();

        $form = new AddNewStudentForm($this->serviceLocator);
        $studentAdmissionModel = new AddNewStudent();
        $form->bind($studentAdmissionModel);

        $tableName = 'student_type';
        $columnName = 'student_type';
        $studentType = $this->studentAdmissionService->listSelectData($tableName, $columnName, NULL);

        $tableName = 'gender';
        $columnName = 'gender';
        $studentGender = $this->studentAdmissionService->listSelectData($tableName, $columnName, NULL); 

        $tableName = 'relation_type';
        $columnName = 'relation';
        $relationship = $this->studentAdmissionService->listSelectData($tableName, $columnName, NULL);

        $tableName = 'programme_year';
        $columnName = 'year';
        $studentYear = $this->studentAdmissionService->listSelectData($tableName, $columnName, NULL);

        $message = NULL;
       
         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());

             $data = $this->params()->fromPost();
             $cid = $data['addnewstudent']['cid'];
             $year_id = $data['addnewstudent']['year_id'];

             $check_student = $this->studentAdmissionService->crossCheckRegisterStudent($cid, $tableName = 'student');

             if($check_student){
                $message = 'Failure';
                $this->flashMessenger()->addMessage('Student with same cid no or passport number has been registered. Please check cid number or passport number and register again.');
             }else{
                if ($form->isValid()) { 
                     try { 
                        //getting data from ajax. so need to extract them and pass as variables
                        $programmes_id = $this->getRequest()->getPost('programme_id');
                        $country_id = $this->getRequest()->getPost('country_id'); 
                        $dzongkhag = $this->getRequest()->getPost('dzongkhag');
                        $gewog = $this->getRequest()->getPost('gewog');
                        $village = $this->getRequest()->getPost('village');
                        $studentAdmissionData = $this->studentAdmissionService->saveNewStudent($studentAdmissionModel, $programmes_id, $country_id, $dzongkhag, $gewog, $village, $year_id, $this->organisation_id);
                        //$lastGeneratedId = $studentAdmissionData->getId();
                        //$this->redirect()->toRoute('view-new-student-details', array('id' => $lastGeneratedId));
                        $this->notificationService->saveNotification('New Student Registration', $this->organisation_id, $this->organisation_id, 'Student Admission');
                        $this->auditTrailService->saveAuditTrail("INSERT", "Student", "ALL", "SUCCESS");
                        $this->auditTrailService->saveAuditTrail("INSERT", "Student Guardian Details", "ALL", "SUCCESS");

                        $this->flashMessenger()->addMessage('New Student Was successfully added');
                        return $this->redirect()->toRoute('new-reported-student-list');
                     }
                     catch(\Exception $e) {
                            $message = 'Failure';
                            $this->flashMessenger()->addMessage($e->getMessage());
                            return $this->redirect()->toRoute('new-reported-student-list');
                             // Some DB Error happened, log it and let the user know
                     }
                 }
             }
         }
           return array(
             'form' => $form,
             'organisation_id' => $this->organisation_id,
             'studentType' => $studentType,
             'studentGender' => $studentGender,
             'studentYear' => $studentYear,
             'relationship' => $relationship,
             'message' => $message,
         );
    }

    public function viewNewStudentDetailsAction()
    {
        $this->loginDetails();
		//get the id
		$id = $this->params()->fromRoute('id');

		$form = new StudentDetailsForm();
				
        $studentDetails = $this->studentAdmissionService->findNewStudentDetails($id);
        $studentGuardianDetails = $this->studentAdmissionService->findStudentGuardianDetails($id);
        $studentSemesterDetails = $this->studentAdmissionService->findStudentSemesterDetails($id);

        $message = NULL;

        return array(
			'id' => $id,
            'form' => $form,
            'studentDetails' => $studentDetails,
            'studentGuardianDetails' => $studentGuardianDetails,
            'studentSemesterDetails' => $studentSemesterDetails,
            'message' => $message,
        );
    }


    /*Display the list of new student for adding section  */
     public function addNewStudentSectionAction()
    {
        $this->loginDetails();
        //ajax actions
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');

        //need to get the student count for entering the section
       $studentCount = 0;

        // Default values
        $studentList = array();
        $programmesId = NULL;
      //  $studentSectionForm = NULL;

        $form = new NewStudentListSearchForm($this->serviceLocator);

        $tableName = 'student_section';
        $columnName = 'section';
        $studentSection = $this->studentAdmissionService->listSelectData($tableName, $columnName, NULL);

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
               $programmesId = $this->getRequest()->getPost('programmes_id');
               $studentList = $this->studentAdmissionService->getNewReportedStudentList($programmesId);
               $studentCount = count($studentList);
            }
        }

        $studentSectionForm = new AddStudentSectionForm($studentCount, $studentSection);
        

        return new ViewModel(array(
            'form' => $form,
            'studentSectionForm' => $studentSectionForm,
            'studentCount' => $studentCount,
            'programmesId' => $programmesId,
            'studentList' => $studentList,
            'studentSection' => $studentSection,
            'employee_details_id' => $this->employee_details_id,
            //'organisation_id' => $organisation_id
            ));
    }


    // Record the student section into student_semester_registration at first time
    public function updateNewStudentSectionAction()
    {

        $form = new AddStudentSectionForm($studentCount= 'null', $studentSection='null');

        //$organisation_id = 1;
        $request = $this->getRequest();
        if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                $data = $this->extractFormData();
                $programmesId = $this->getRequest()->getPost('programmes_id');

                try {
                     $this->studentAdmissionService->updateNewStudentSection($data, $programmesId);
                     $this->redirect()->toRoute('add-new-student-section');
         } 
         catch(\Exception $e) {
                 die($e->getMessage());
                 // Some DB Error happened, log it and let the user know
                }
            }
        }   
       return array(
            'form' => $form,
        ); 
    }

    //To edit the section of student
    public function editStudentSectionAction()
    {
        $this->loginDetails();

        //need to get the student count for entering the section
       $studentCount = 0;

        // Default values
        $editStudentList = array();
        $programmesId = NULL;
        $yearId = NULL;
      //  $studentSectionForm = NULL;

        $form = new EditStudentListSearchForm($this->serviceLocator);

        $tableName = 'programme_year';
        $columnName = 'year';
        $studentYear = $this->studentAdmissionService->listSelectData($tableName, $columnName, NULL);

        $tableName = 'student_section';
        $columnName = 'section';
        $studentSection = $this->studentAdmissionService->listSelectData($tableName, $columnName, NULL);

        $message = NULL;

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
               $programmesId = $this->getRequest()->getPost('programmes_id');
               $yearId = $this->getRequest()->getPost('year');
               $editStudentList = $this->studentAdmissionService->getEditSectionStudentList($programmesId, $yearId, $this->organisation_id);
               $studentCount = count($editStudentList);
            }
        }

        $editStudentSectionForm = new EditStudentSectionForm($studentCount, $studentSection);
        

        return new ViewModel(array(
            'form' => $form,
            'editStudentSectionForm' => $editStudentSectionForm,
            'studentCount' => $studentCount,
            'programmesId' => $programmesId,
            'yearId' => $yearId,
            'editStudentList' => $editStudentList,
            'studentSection' => $studentSection,
            'studentYear' => $studentYear,
            'employee_details_id' => $this->employee_details_id,
            'message' => $message,
            //'organisation_id' => $organisation_id
            ));
    }

    // Record the student section into student_semester_registration at first time
    public function updateEditedStudentSectionAction()
    {

        $form = new EditStudentSectionForm($studentCount= 'null', $studentSection='null');

        //$organisation_id = 1;
        $request = $this->getRequest();
        if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                $data = $this->extractFormData();
                $programmesId = $this->getRequest()->getPost('programmes_id');
                $yearId = $this->getRequest()->getPost('year');

                try {
                     $this->studentAdmissionService->updateEditedStudentSection($data, $programmesId, $yearId, $this->organisation_id);
                     $this->auditTrailService->saveAuditTrail("EDIT", "Student Semester Registration", "ALL", "SUCCESS");
                     $this->flashMessenger()->addMessage('Student Section was successfully updated');
                     return $this->redirect()->toRoute('edit-student-section');
         } 
         catch(\Exception $e) {
                $message = 'Failure';
                $this->flashMessenger()->addMessage($e->getMessage());
                 die();
                 // Some DB Error happened, log it and let the user know
                }
            }
        }   
       return array(
            'form' => $form,
        ); 
    }


    // To add new house
    public function addNewHouseAction()
    {
        $this->loginDetails();

        $form = new StudentHouseForm();
        $studentAdmissionModel = new StudentHouse();
        $form->bind($studentAdmissionModel);

        $house = $this->studentAdmissionService->listAllStudentHouse($tableName = 'student_house', $this->organisation_id);
        $message = NULL;

         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());

             $data = $this->params()->fromPost();
             $house_name = $data['studenthouse']['house_name'];

             $check_house = $this->studentAdmissionService->crossCheckHouse($house_name);

             if($check_house){
                $message = 'Failure';
                 $this->flashMessenger()->addMessage('House with similar name already exist. Please try for different from the existing one.');
             }else{
                if ($form->isValid()) {
                     try {
                        $this->studentAdmissionService->saveNewHouse($studentAdmissionModel);
                        $this->auditTrailService->saveAuditTrail("INSERT", "Student House", "ALL", "SUCCESS");
                        $this->flashMessenger()->addMessage('New House was successfully added');
                        return $this->redirect()->toRoute('add-new-house');
                     }
                     catch(\Exception $e) {
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                        return $this->redirect()->toRoute('add-new-house');
                           //  die($e->getMessage());
                             // Some DB Error happened, log it and let the user know
                     }
                 }
             }
         }

         return array(
             'form' => $form,
             'house' => $house,
             'keyphrase' => $this->keyphrase,
			 'organisation_id' => $this->organisation_id,
             'message' => $message,
             
         );
    }


    //To edit House

    public function editHouseAction()
    {
        $this->loginDetails();
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $editHouse = $this->studentAdmissionService->findHouse($id);
        
            $form = new StudentHouseForm();
            $studentAdmissionModel = new StudentHouse();
            $form->bind($studentAdmissionModel);

            $message = NULL;

            $request = $this->getRequest();
            if($request->isPost()){
                $form->setData($request->getPost());
                if($form->isValid()){
                    try{
                        $this->studentAdmissionService->saveNewHouse($studentAdmissionModel);
                        $this->auditTrailService->saveAuditTrail("EDIT", "Student House", "ALL", "SUCCESS");

                        $this->flashMessenger()->addMessage('House was successfully edited');
                        return $this->redirect()->toRoute('add-new-house');
                    }
                    catch(\Exception $e){
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                        die();
                        //Some DB Error happened, log it and let the user know  
                    }
                
                }
            }
            return array(
                'form' => $form,
                'editHouse' => $editHouse,
                'house' => $this->studentAdmissionService->listAllStudentHouse($tableName = 'student_house', $this->organisation_id),
                'message' => $message,
				'organisation_id' => $this->organisation_id,
                );
        }
    }

    /*Display the list of new student for adding house  */
     public function addNewStudentHouseAction()
    {
        $this->loginDetails();

        //need to get the student count for entering the section
       $studentCount = 0;

        // Default values
        $studentList = array();
        $programmesId = NULL;
        $yearId = NULL;
      //  $studentSectionForm = NULL;

        $form = new EditStudentListSearchForm($this->serviceLocator);

        $tableName = 'programme_year';
        $columnName = 'year';
        $studentYear = $this->studentAdmissionService->listSelectData($tableName, $columnName, NULL);

        $tableName = 'student_house';
        $columnName = 'house_name';
        $studentHouse = $this->studentAdmissionService->listSelectData($tableName, $columnName, $this->organisation_id);

        $message = NULL;

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
               $programmesId = $this->getRequest()->getPost('programmes_id');
               $yearId = $this->getRequest()->getPost('year');
               $studentList = $this->studentAdmissionService->getStudentHouseList($programmesId, $yearId, $this->organisation_id);
               $studentCount = count($studentList);
            }
        }

        $studentHouseForm = new EditStudentHouseForm($studentCount, $studentHouse);
        
        return new ViewModel(array(
            'form' => $form,
            'studentHouseForm' => $studentHouseForm,
            'programmesId' => $programmesId,
            'yearId' => $yearId,
            'studentCount' => $studentCount,
            'studentList' => $studentList,
            'studentHouse' => $studentHouse,
            'studentYear' => $studentYear,
            'employee_details_id' => $this->employee_details_id,
            'message' => $message,
            //'organisation_id' => $organisation_id
            ));
    }


    // Record the student section into student_semester_registration at first time
    public function updateNewStudentHouseAction()
    {

        $form = new EditStudentHouseForm($studentCount= 'null', $studentSection='null');

        //$organisation_id = 1;
        $request = $this->getRequest();
        if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                $data1 = $this->extractFormData1();
                $programmesId = $this->getRequest()->getPost('programmes_id');
                $yearId = $this->getRequest()->getPost('year');
                try {
                     $this->studentAdmissionService->saveNewStudentHouse($data1, $programmesId, $yearId, $this->organisation_id);
                     $this->auditTrailService->saveAuditTrail("UPDATE", "Student House Details", "ALL", "SUCCESS");

                     $this->flashMessenger()->addMessage('House was successfully added to student');
                     return $this->redirect()->toRoute('add-new-student-house');
         } 
         catch(\Exception $e) {
                $message = 'Failure';
                $this->flashMessenger()->addMessage($e->getMessage());
                 return $this->redirect()->toRoute('add-new-student-house');
                 // Some DB Error happened, log it and let the user know
                }
            }
        }   
       return array(
            'form' => $form,
        );  
    }


    /*Display the list of new student for editing house  */
     public function editStudentHouseAction()
    {
        $this->loginDetails();

        //need to get the student count for entering the section
       $studentCount = 0;

        // Default values
        $editStudentList = array();
        $programmesId = NULL;
        $yearId = NULL;
      //  $studentSectionForm = NULL;

        $form = new EditStudentListSearchForm($this->serviceLocator);

        $tableName = 'programme_year';
        $columnName = 'year';
        $studentYear = $this->studentAdmissionService->listSelectData($tableName, $columnName, NULL);

        $tableName = 'student_house';
        $columnName = 'house_name';
        $studentHouse = $this->studentAdmissionService->listSelectData($tableName, $columnName, $this->organisation_id);

        $message = NULL;

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
               $programmesId = $this->getRequest()->getPost('programmes_id');
               $yearId = $this->getRequest()->getPost('year');
               $editStudentList = $this->studentAdmissionService->getEditHouseStudentList($programmesId, $yearId, $this->organisation_id);
               $studentCount = count($editStudentList);
            }
        }

        $editStudentHouseForm = new EditStudentHouseForm($studentCount, $studentHouse);
        

        return new ViewModel(array(
            'form' => $form,
            'editStudentHouseForm' => $editStudentHouseForm,
            'studentCount' => $studentCount,
            'programmesId' => $programmesId,
            'yearId' => $yearId,
            'editStudentList' => $editStudentList,
            'studentHouse' => $studentHouse,
            'studentYear' => $studentYear,
            'employee_details_id' => $this->employee_details_id,
            'message' => $message,
            //'organisation_id' => $organisation_id
            ));
    }

    // Record the student section into student_semester_registration at first time
    public function updateEditedStudentHouseAction()
    {
        $this->loginDetails();

        $form = new EditStudentHouseForm($studentCount= 'null', $studentSection='null');

        //$organisation_id = 1;
        $request = $this->getRequest();
        if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                $data1 = $this->extractFormData1();
                $programmesId = $this->getRequest()->getPost('programmes_id');
                $yearId = $this->getRequest()->getPost('year');

                try {
                     $this->studentAdmissionService->updateEditedStudentHouse($data1, $programmesId, $yearId, $this->organisation_id);
                     $this->auditTrailService->saveAuditTrail("EDIT", "Student House Details", "ALL", "SUCCESS");

                     $this->flashMessenger()->addMessage('Student house was successfully updated');
                     return $this->redirect()->toRoute('edit-student-house');
         } 
         catch(\Exception $e) {
                $message = 'Failure';
                $this->flashMessenger()->addMessage($e->getMessage());
                return $this->redirect()->toRoute('edit-student-house');
                 // Some DB Error happened, log it and let the user know
                }
            }
        }   
       return array(
            'form' => $form,
        ); 
    }


     /*Display the list of new student for editing house  */
     public function registerStudentSemesterAction()
    {
        $this->loginDetails();

        // Default values
        $semesterStudentList = array();
        $programmesId = NULL;
        $yearId = NULL;
        $semesterId = NULL;
        $studentName = NULL;
        $studentId = NULL;
        $academicYear = NULL;
        $studentCount = 0;
        $updateStudentSemesterForm = NULL;
      //  $studentSectionForm = NULL;

        $form = new StudentSemesterSearchForm($this->serviceLocator);

        $tableName = 'programme_year';
        $columnName = 'year';
        $studentYear = $this->studentAdmissionService->listSelectData($tableName, $columnName, NULL);
        $semesterRegistrationAnnouncement = $this->studentAdmissionService->getSemesterRegistrationAnnouncement('Semester Registration', $this->organisation_id);
        //$stdAcademicYear = $this->studentAdmissionService->getAcademicYear($tableName = 'student_semester_registration');

        $message = NULL;

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            $programmesId = $this->getRequest()->getPost('programmes_id');
            $yearId = $this->getRequest()->getPost('year');
            $studentName = $this->getRequest()->getPost('student_name');
            $studentId = $this->getRequest()->getPost('student_id');

           /* $check_academicYear = $this->studentAdmissionService->crossCheckSemesterAcademicYear('Semester Registration', $academicYear);
                if($check_academicYear){
                    $message = 'Failure';
                    $this->flashMessenger()->addMessage('You cannot update the student semester of updated academic year. Please select the previous academic year.');
                }else{*/
                    if($form->isValid()){
                       $semesterStudentList = $this->studentAdmissionService->getSemesterRegistrationStudentList($programmesId, $yearId, $studentName, $studentId, $this->organisation_id);
                       $studentCount = count($semesterStudentList);
                    }
               //  }
            }

        $updateStudentSemesterForm = new UpdateStudentSemesterForm($studentCount);

        return new ViewModel(array(
            'form' => $form,
            'yearId' => $yearId,
            'programmesId' => $programmesId,
            'semesterId' => $semesterId,
            'academicYear' => $academicYear,
            'studentName' => $studentName,
            'studentId' => $studentId,
            //'stdAcademicYear' => $stdAcademicYear,
            'semesterStudentList' => $semesterStudentList,
            'studentYear' => $studentYear,
            'employee_details_id' => $this->employee_details_id,
            'updateStudentSemesterForm' => $updateStudentSemesterForm,
            'studentCount' => $studentCount,
            'semesterRegistrationAnnouncement' => $semesterRegistrationAnnouncement,
            'message' => $message,
            //'organisation_id' => $organisation_id
            ));
    }


    public function updateStudentSemesterAction()
    {
        $this->loginDetails();
        $form = new UpdateStudentSemesterForm($studentCount = 'null');

        //$organisation_id = 1;
        $request = $this->getRequest();
        if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                $programmesId = $this->getRequest()->getPost('programmes_id');
                $yearId = $this->getRequest()->getPost('year');
                $studentName = $this->getRequest()->getPost('student_name');
                $studentId = $this->getRequest()->getPost('student_id');

                $semester_data = $this->extractSemesterUpdateData();
                try {
                     $this->studentAdmissionService->updateStudentSemester('Semester Registration', $semester_data, $programmesId, $yearId, $studentName, $studentId, $this->organisation_id);
                     $this->auditTrailService->saveAuditTrail("UPDATE", "Student Semester Registration", "ALL", "SUCCESS");

                     $this->flashMessenger()->addMessage('Semester is successfully updated');
                     return $this->redirect()->toRoute('register-student-semester');
                    } 
                    catch(\Exception $e) {
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                        return $this->redirect()->toRoute('register-student-semester');
                        // Some DB Error happened, log it and let the user know
                }
            }
        }   
       return array(
            'form' => $form,
        );
    }


    public function semesterReportedStudentListAction()
    {
        $this->loginDetails();

        // Default values
        $semesterStudentList = array();
        $programmesId = NULL;
        $yearId = NULL;
        $studentName = NULL;
        $studentId = NULL;
      //  $studentSectionForm = NULL;

        $form = new StudentDetailSearchForm($this->serviceLocator);

        $tableName = 'programme_year';
        $columnName = 'year';
        $studentYear = $this->studentAdmissionService->listSelectData($tableName, $columnName, NULL);

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
               $programmesId = $this->getRequest()->getPost('programmes_id');
               $yearId = $this->getRequest()->getPost('year');
               $academicYear = $this->getRequest()->getPost('academic_year');
               $studentName = $this->getRequest()->getPost('student_name');
               $studentId = $this->getRequest()->getPost('student_id');
               $semesterStudentList = $this->studentAdmissionService->getSemesterReportedStudentList($programmesId, $yearId, $academicYear, $studentName, $studentId);
            }
        }

        return new ViewModel(array(
            'form' => $form,
            'programmesId' => $programmesId,
            'yearId' => $yearId,
            'stdAcademicYear' => $stdAcademicYear,
            'studentName' => $studentName,
            'studentId' => $studentId,
            'semesterStudentList' => $semesterStudentList,
            'studentYear' => $studentYear,
            'employee_details_id' => $this->employee_details_id,
            'keyphrase' => $this->keyphrase,
            'message' => $message,
            //'organisation_id' => $organisation_id
            ));
    }


    // Record the student section into student_semester_registration at first time
    public function updateNotReportedStudentAction()
    {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $form = new StudentSemesterRegistrationForm();
            $studentAdmissionModel = new StudentSemesterRegistration();
            $form->bind($studentAdmissionModel);

            $studentSemesterDetails = $this->studentAdmissionService->getStudentSemesterDetails($id);
            $tableName = 'student_status_type';
            $columnName = 'reason';
            $studentSemester = $this->studentAdmissionService->listSelectData($tableName, $columnName, NULL);

            $academic_event_details = $this->studentAdmissionService->getSemester($this->organisation_id);
            $academic_year = $this->studentAdmissionService->getCurrentAcademicYear($academic_event_details);

            //$organisation_id = 1;
            $request = $this->getRequest();
            if ($request->isPost()) {
                 $form->setData($request->getPost());
                 if ($form->isValid()) {
                    try {
                        $this->studentAdmissionService->updateNotReportedStudent($studentAdmissionModel);
                        $this->auditTrailService->saveAuditTrail("INSERT", "Student Not Reported Details", "ALL", "SUCCESS");
                        $this->flashMessenger()->addMessage('Student was successfully updated');
                        return $this->redirect()->toRoute('semesterreportedstudentlist');
                } 
                catch(\Exception $e) {
                    $message = 'Failure';
                    $this->flashMessenger()->addMessage($e->getMessage());
                             // Some DB Error happened, log it and let the user know
                    }
                }
            }   
           return array(
                'form' => $form,
                'studentSemester' => $studentSemester,
                'academic_year' => $academic_year,
                'studentSemesterDetails' => $studentSemesterDetails,
            );
        }else{
            $this->redirect()->toRoute('semesterreportedstudentlist');
        } 
    }


   public function viewStudentListAction()
   {
        $this->loginDetails();

        // Default values
        $studentList = array();
        $programmesId = NULL;
        $semesterId = NULL;
        $sectionId = NULL;
        $studentName = NULL;
        $studentId = NULL;
      //  $studentSectionForm = NULL;

        $form = new StudentDetailSearchForm($this->serviceLocator);

        $tableName = 'student_semester';
        $columnName = 'semester';
        $studentSemester = $this->studentAdmissionService->listSelectData($tableName, $columnName, NULL);

        $tableName = 'student_section';
        $columnName = 'section';
        $studentSection = $this->studentAdmissionService->listSelectData($tableName, $columnName, NULL);

        $message = NULL;

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
               $programmesId = $this->getRequest()->getPost('programmes_id');
               $semesterId = $this->getRequest()->getPost('semester_id');
               $sectionId = $this->getRequest()->getPost('student_section_id');
               $studentName = $this->getRequest()->getPost('student_name');
               $studentId = $this->getRequest()->getPost('student_id');
               $studentList = $this->studentAdmissionService->getStudentList($programmesId, $semesterId, $sectionId, $studentName, $studentId);
            }
        }

        return new ViewModel(array(
            'form' => $form,
            'programmesId' => $programmesId,
            'semesterId' => $semesterId,
            'sectionId' => $sectionId,
            'studentName' => $studentName,
            'studentId' => $studentId,
            'studentList' => $studentList,
            'studentSemester' => $studentSemester,
            'studentSection' => $studentSection,
            'employee_details_id' => $this->employee_details_id,
            'keyphrase' => $this->keyphrase,
            'message' => $message,
            //'organisation_id' => $organisation_id
            ));
   }


   // Function to update/ edit the student personal details
   public function studentPersonalDetailsAction()
   {
        $this->loginDetails();
      // $studentCount = 0;

        // Default values
        $studentList = array();
        $programmesId = NULL;
        $yearId = NULL;
        $studentName = NULL;
        $studentId = NULL;
      //  $studentSectionForm = NULL;

        $form = new StudentDetailSearchForm($this->serviceLocator);

        $tableName = 'programme_year';
        $columnName = 'year';
        $studentYear = $this->studentAdmissionService->listSelectData($tableName, $columnName, NULL);

        $present_year = date('Y')+1;
        $stdAcademicYear = array();        
        for($i=6; $i>0; $i--){
            $stdAcademicYear[($present_year-$i)."-".($present_year-$i+1)] = ($present_year-$i)."-".($present_year-$i+1);
        }

        //$stdAcademicYear = $this->studentAdmissionService->listSelectAcademicYear($tableName = 'student_semester_registration');

        $message = NULL;

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
               $programmesId = $this->getRequest()->getPost('programmes_id');
               $yearId = $this->getRequest()->getPost('year');
               $academicYear = $this->getRequest()->getPost('academic_year');
               $studentName = $this->getRequest()->getPost('student_name');
               $studentList = $this->studentAdmissionService->getStudentList($programmesId, $yearId, $academicYear, $studentName, $studentId);
            }
        }

        return new ViewModel(array(
            'form' => $form,
            'programmesId' => $programmesId,
            'yearId' => $yearId,
            'studentName' => $studentName,
            'studentId' => $studentId,
            'studentList' => $studentList,
            'studentYear' => $studentYear,
            'stdAcademicYear' => $stdAcademicYear,
            'employee_details_id' => $this->employee_details_id,
            'keyphrase' => $this->keyphrase,
            'message' => $message,
            //'organisation_id' => $organisation_id
            ));
   }


   public function viewStudentPersonalDetailsAction()
   {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){

            return new ViewModel(array(
                'stdPersonalDetails' => $this->studentAdmissionService->getStudentPersonalDetails($id),
                'stdCategoryDetails' => $this->studentAdmissionService->getStudentCategoryDetails($id),
                'keyphrase' => $this->keyphrase,
            ));
        }else{
            return $this->redirect()->toRoute('studentpersonaldetails');
        }
   }


   public function addStudentPersonalDetailsAction()
   {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $form = new UpdateStudentPersonalDetailsForm();
            $studentAdmissionModel = new UpdateStudentPersonalDetails();
            $form->bind($studentAdmissionModel);

            $personalDetails = $this->studentAdmissionService->getStdPersonalDetails($id);

            $tableName = 'student_category';
            $columnName = 'student_category';
            $studentCategory = $this->studentAdmissionService->listSelectData($tableName, $columnName, NULL);

            $tableName = 'programmes';
            $columnName = 'programme_name';
            $studentProgramme = $this->studentAdmissionService->selectStudentProgramme($tableName, $columnName, $this->organisation_id);

            $tableName = 'gender';
            $columnName = 'gender';
            $studentGender = $this->studentAdmissionService->listSelectData($tableName, $columnName, NULL);

            $tableName = 'student_type';
            $columnName = 'student_type';
            $studentType = $this->studentAdmissionService->listSelectData($tableName, $columnName, NULL);

             $request = $this->getRequest();
             if ($request->isPost()) {
                 $form->setData($request->getPost());
                 if ($form->isValid()) {
                     try {
                         $this->studentAdmissionService->saveStudentPersonalDetails($studentAdmissionModel);
                         $this->auditTrailService->saveAuditTrail("INSERT", "Student Category Details", "ALL", "SUCCESS");
                         $this->auditTrailService->saveAuditTrail("UPDATE", "Student", "ALL", "SUCCESS");
                         $this->flashMessenger()->addMessage('Student Details was successfully updated');                     
                         return $this->redirect()->toRoute('viewstudentpersonaldetails', array('id' => $this->my_encrypt($id, $this->keyphrase)));
                     }
                     catch(\Exception $e) {
                            $message = 'Failure';
                            $this->flashMessenger()->addMessage($e->getMessage());
                             die();
                             // Some DB Error happened, log it and let the user know
                     }
                 }
             }
               return array(
                'form' => $form,
                'personalDetails' => $personalDetails,
                'studentCategory' => $studentCategory,
                'studentProgramme' => $studentProgramme,
                'studentGender' => $studentGender,
                'studentType' => $studentType,
                'stdCategoryDetails' => $this->studentAdmissionService->getStudentCategoryDetails($id),
             );
        }else{
            return $this->redirect()->toRoute('studentpersonaldetails');
        }
   }



   public function studentPermanentAddressDetailsAction()
   {
        $this->loginDetails();

        // Default values
        $studentList = array();
        $programmesId = NULL;
        $yearId = NULL;
        $studentName = NULL;
        $studentId = NULL;
      //  $studentSectionForm = NULL;

        $form = new StudentDetailSearchForm($this->serviceLocator);

        $tableName = 'programme_year';
        $columnName = 'year';
        $studentYear = $this->studentAdmissionService->listSelectData($tableName, $columnName, NULL);

        $present_year = date('Y')+1;
        $stdAcademicYear = array();        
        for($i=6; $i>0; $i--){
            $stdAcademicYear[($present_year-$i)."-".($present_year-$i+1)] = ($present_year-$i)."-".($present_year-$i+1);
        }

        //$stdAcademicYear = $this->studentAdmissionService->listSelectAcademicYear($tableName = 'student_semester_registration');

        $message = NULL;

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
               $programmesId = $this->getRequest()->getPost('programmes_id');
               $yearId = $this->getRequest()->getPost('year');
               $academicYear = $this->getRequest()->getPost('academic_year');
               $studentName = $this->getRequest()->getPost('student_name');
               $studentId = $this->getRequest()->getPost('student_id');
               $studentList = $this->studentAdmissionService->getStudentList($programmesId, $yearId, $academicYear, $studentName, $studentId);
            }
        }

        return new ViewModel(array(
            'form' => $form,
            'programmesId' => $programmesId,
            'yearId' => $yearId,
            'studentName' => $studentName,
            'studentId' => $studentId,
            'studentList' => $studentList,
            'studentYear' => $studentYear,
            'stdAcademicYear' => $stdAcademicYear,
            'employee_details_id' => $this->employee_details_id,
            'keyphrase' => $this->keyphrase,
            'message' => $message,
            //'organisation_id' => $organisation_id
            ));
   }


   public function viewStudentPermanentAddressDetailsAction()
   {

        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            return new ViewModel(array(
            'stdDetails' => $this->studentAdmissionService->getStudentPersonalDetails($id),
            'stdContactDetails' => $this->studentAdmissionService->getStudentContactDetails($id),
            'stdNationality' => $this->studentAdmissionService->getStudentNationalityDetails($id),
            'stdPermanentAddr' => $this->studentAdmissionService->getStudentPermanentAddrDetails($id, 'ALL'),
            'keyphrase' => $this->keyphrase,
            ));
        }else{
            return $this->redirect()->toRoute('studentpermanentaddressdetails');
        }
   }


   public function addStudentPermanentAddrDetailsAction()
   {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $form = new UpdateStudentPermanentAddrForm($this->serviceLocator);
            $studentAdmissionModel = new UpdateStudentPermanentAddr();
            $form->bind($studentAdmissionModel);

            $tableName = 'country';
            $columnName = 'country';
            $country = $this->studentAdmissionService->listSelectData($tableName, $columnName, NULL);

            $tableName = 'nationality';
            $columnName = 'nationality';
            $nationality = $this->studentAdmissionService->listSelectData($tableName, $columnName, NULL);

            $message = NULL;

            $request = $this->getRequest();
             if ($request->isPost()) {
                 $form->setData($request->getPost());

                 $check_address = $this->studentAdmissionService->crossCheckStudentPermanentAddress($id);
                 if($check_address){
                    $message = 'Failure';
                    $this->flashMessenger()->addMessage('Permanent Address has been already added for this student. To edit please click on edit button from the list and edit it.');
                 }else{
                    if ($form->isValid()) {
                         try {
                            $stdDzongkhag = $this->getRequest()->getPost('dzongkhag');
                            $stdGewog = $this->getRequest()->getPost('gewog');
                            $stdVillage = $this->getRequest()->getPost('village');

                            $this->studentAdmissionService->saveStudentPermanentAddr($studentAdmissionModel, $stdDzongkhag, $stdGewog, $stdVillage);
                            $this->auditTrailService->saveAuditTrail("UPDATE", "Student", "ALL", "SUCCESS");
                            $this->auditTrailService->saveAuditTrail("INSERT", "Student Nationality Details", "ALL", "SUCCESS");
                            $this->flashMessenger()->addMessage('Student Permanent Address was successfully added');
                            return $this->redirect()->toRoute('viewstudentpermanentaddressdetails', array('id' => $this->my_encrypt($id, $this->keyphrase)));
                         }
                         catch(\Exception $e) {
                            $message = 'Failure';
                            $this->flashMessenger()->addMessage($e->getMessage());
                            return $this->redirect()->toRoute('studentpermanentaddressdetails');
                                // die($e->getMessage());
                                 // Some DB Error happened, log it and let the user know
                         }
                     }
                 }
             }

             return array(
                'form' => $form,
                'id' => $id,
                'country' => $country,
                'nationality' => $nationality,
                'stdDetails' => $this->studentAdmissionService->getStdPersonalDetails($id),
                'message' => $message,
             );
        }else{
            return $this->redirect()->toRoute('studentpermanentaddressdetails');
        }
   }


   public function editStudentPermanentAddrDetailsAction()
   {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){

            $form = new UpdateStudentPermanentAddrForm($this->serviceLocator);
            $studentAdmissionModel = new UpdateStudentPermanentAddr();
            $form->bind($studentAdmissionModel);

            $tableName = 'country';
            $columnName = 'country';
            $country = $this->studentAdmissionService->listSelectData($tableName, $columnName, NULL);

            $tableName = 'nationality';
            $columnName = 'nationality';
            $nationality = $this->studentAdmissionService->listSelectData($tableName, $columnName, NULL);
            $message = NULL;

            $check_address = $this->studentAdmissionService->crossCheckStudentPermanentAddress($id);
            if(!empty($check_address)){
                $permanentAddr = $this->studentAdmissionService->getStdPermanentAddrDetails($id);
                
                $request = $this->getRequest();
                if($request->isPost()){
                    $form->setData($request->getPost());
                    if($form->isValid()){
                       // var_dump($form);
                        //die();
                        try{
                            $stdDzongkhag = $this->getRequest()->getPost('dzongkhag');
                            $stdGewog = $this->getRequest()->getPost('gewog');
                            $stdVillage = $this->getRequest()->getPost('village'); 

                            $this->studentAdmissionService->updateStudentPermanentAddr($studentAdmissionModel, $stdDzongkhag, $stdGewog, $stdVillage);
                            $this->auditTrailService->saveAuditTrail("UPDATE", "Student", "ALL", "SUCCESS");
                            $this->auditTrailService->saveAuditTrail("UPDATE", "Student Nationality Details", "ALL", "SUCCESS");
                            $this->flashMessenger()->addMessage('Student Permanent Address Details was successfully edited');
                            return $this->redirect()->toRoute('viewstudentpermanentaddressdetails', array('id' => $this->my_encrypt($id, $this->keyphrase)));
                        }
                        catch(\Exception $e){
                            $message = 'Failure';
                            $this->flashMessenger()->addMessage($e->getMessage());
                        }
                    }
                }

                }else{
                    $message = 'Failure';
                    $this->flashMessenger()->addMessage("You can't edit it since you have not yet added the details before. Please add the permanenet address.");
                    return $this->redirect()->toRoute('studentpermanentaddressdetails');
                }            

                return array(
                    'id' => $id,
                    'form' => $form,
                    'country' => $country,
                    'nationality' => $nationality,
                    'permanentAddr' =>$permanentAddr,
                    'check_address' => $check_address,
                    'message' => $message,
                    );
        }else{
            return $this->redirect()->toRoute('studentpermanentaddressdetails');
        }
   }


   public function studentRelationDetailsAction()
   {
        $this->loginDetails();

        // Default values
        $studentList = array();
        $programmesId = NULL;
        $yearId = NULL;
        $studentName = NULL;
        $studentId = NULL;
      //  $studentSectionForm = NULL;

        $form = new StudentDetailSearchForm($this->serviceLocator);

        $tableName = 'programme_year';
        $columnName = 'year';
        $studentYear = $this->studentAdmissionService->listSelectData($tableName, $columnName, NULL);

        $present_year = date('Y')+1;
        $stdAcademicYear = array();        
        for($i=6; $i>0; $i--){
            $stdAcademicYear[($present_year-$i)."-".($present_year-$i+1)] = ($present_year-$i)."-".($present_year-$i+1);
        }

       // $stdAcademicYear = $this->studentAdmissionService->listSelectAcademicYear($tableName = 'student_semester_registration');

        $message = NULL;

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
               $programmesId = $this->getRequest()->getPost('programmes_id');
               $yearId = $this->getRequest()->getPost('year');
               $academicYear = $this->getRequest()->getPost('academic_year');
               $studentName = $this->getRequest()->getPost('student_name');
               $studentId = $this->getRequest()->getPost('student_id');
               $studentList = $this->studentAdmissionService->getStudentList($programmesId, $yearId, $academicYear, $studentName, $studentId);
            }
        }

        return new ViewModel(array(
            'form' => $form,
            'programmesId' => $programmesId,
            'yearId' => $yearId,
            'studentName' => $studentName,
            'stdAcademicYear' => $stdAcademicYear,
            'studentId' => $studentId,
            'studentList' => $studentList,
            'studentYear' => $studentYear,
            'employee_details_id' => $this->employee_details_id,
            'keyphrase' => $this->keyphrase,
            'message' => $message,
            //'organisation_id' => $organisation_id
            ));
   }


   public function viewStudentRelationDetailsAction()
   {
        $this->loginDetails();

        //Get id from the route
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $form = new StudentRelationDetailsForm();
            $studentAdmissionModel = new StudentRelationDetails();
            $form->bind($studentAdmissionModel);

            $nationality = $this->studentAdmissionService->listSelectData($tableName = 'nationality', $columnName = 'nationality', NULL);
            $dzongkhag = $this->studentAdmissionService->listSelectData($tableName = 'dzongkhag', $columnName = 'dzongkhag_name', NULL);
            $relationType = $this->studentAdmissionService->listSelectData($tableName = 'relation_type', $columnName = 'relation', NULL); 

            $check_address = $this->studentAdmissionService->crossCheckStudentPermanentAddress($id);

            $message = NULL;

            $request = $this->getRequest();
            if($request->isPost()){
                $form->setData($request->getPost());
                if(!$id)
                    $id = $this->getRequest()->getPost('student_id');

                $data = $this->params()->fromPost();
                $parentCID = $data['studentrelationdetails']['parent_cid'];
                $check_student_relation = $this->studentAdmissionService->crossCheckStudentRelation($parentCID, $id);

                if($check_student_relation){
                    $message = 'Failure';
                    $this->flashMessenger()->addMessage('Relation Details with same CID/ Passport has been already added to this particular student.');
                }else{
                    if($form->isValid()){
                        try{
                            $this->studentAdmissionService->saveStudentRelationDetails($studentAdmissionModel);
                            $this->auditTrailService->saveAuditTrail("INSERT", "Student Relation Details", "ALL", "SUCCESS");
                            $this->flashMessenger()->addMessage('Relation was successfully added');
                            return $this->redirect()->toRoute('viewstudentrelationdetails', array('id'=>$this->my_encrypt($id, $this->keyphrase)));
                        }
                        catch(\Exception $e){
                            $message = 'Failure';
                            $this->flashMessenger()->addMessage($e->getMessage());
                             return $this->redirect()->toRoute('viewstudentrelationdetails', array('id'=>$this->my_encrypt($id, $this->keyphrase)));
                        }
                    }
                }
            }
            return new ViewModel(array(
                'id' => $id,
                'form' => $form,
                'nationality' => $nationality,
                'dzongkhag' => $dzongkhag,
                'relationType' => $relationType,
                'stdDetails' => $this->studentAdmissionService->getStudentPersonalDetails($id),
                'stdRelationDetails' => $this->studentAdmissionService->findStudentRelationDetails($tableName = 'student_relation_details', $id),
                'keyphrase' => $this->keyphrase,
                'message' => $message,
                'check_address' => $check_address,
            ));
        }else{
            return $this->redirect()->toRoute('studentrelationdetails');
        }
   }


   public function editStudentRelationDetailsAction()
   {
        $this->loginDetails();

        //Get id from the route
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){ 
            $form = new StudentRelationDetailsForm();
            $studentAdmissionModel = new StudentRelationDetails();
            $form->bind($studentAdmissionModel);

            $nationality = $this->studentAdmissionService->listSelectData($tableName = 'nationality', $columnName = 'nationality', NULL);
            $dzongkhag = $this->studentAdmissionService->listSelectData($tableName = 'dzongkhag', $columnName = 'dzongkhag_name', NULL);
            $relationType = $this->studentAdmissionService->listSelectData($tableName = 'relation_type', $columnName = 'relation', NULL); 

            $message = NULL;

            $request = $this->getRequest();
            if($request->isPost()){
                $form->setData($request->getPost());
                    $data = $this->params()->fromPost();
                    $student_id = $data['studentrelationdetails']['student_id']; 
                    if($form->isValid()){
                        try{ 
                            $this->studentAdmissionService->saveStudentRelationDetails($studentAdmissionModel);
                            $this->auditTrailService->saveAuditTrail("EDIT", "Student Relation Details", "ALL", "SUCCESS");
                            $this->flashMessenger()->addMessage('Relation was successfully edited');
                            return $this->redirect()->toRoute('viewstudentrelationdetails', array('id' => $this->my_encrypt($student_id, $this->keyphrase)));
                        }
                        catch(\Exception $e){
                            $message = 'Failure';
                            $this->flashMessenger()->addMessage($e->getMessage());
                             return $this->redirect()->toRoute('studentrelationdetails');
                        }
                    }
                }
                return new ViewModel(array(
                    'id' => $id,
                    'form' => $form,
                    'nationality' => $nationality,
                    'dzongkhag' => $dzongkhag,
                    'relationType' => $relationType,
                    'stdDetails' => $this->studentAdmissionService->getStudentDetails($tableName = 'student_relation_details', $id),
                    'stdRelationDetails' => $this->studentAdmissionService->getStudentRelationDetails($tableName = 'student_relation_details', $id),
                    'message' => $message,
                ));
            }else{
                return $this->redirect()->toRoute('studentrelationdetails');
        }
   }


   /*To update student reported details from College */
    public function deleteStudentRelationAction()
    {

        $this->loginDetails();
         
         //get the id of the travel authorization proposal
        $id_from_route = $this->params()->fromRoute('id');
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        $stdId_from_route = $this->params()->fromRoute('stdId');
        $stdId = $this->my_decrypt($stdId_from_route, $this->keyphrase);

        if(is_numeric($id)){ 
             try {
                 $result = $this->studentAdmissionService->deleteStudentRelation($id);
                 $this->auditTrailService->saveAuditTrail("DELETE", "Student Relation Details", "ALL", "SUCCESS");
                 $this->flashMessenger()->addMessage("You have successfully deleted the student relation");
                 return $this->redirect()->toRoute('viewstudentrelationdetails', array('id' => $this->my_encrypt($stdId, $this->keyphrase)));
                 //return $this->redirect()->toRoute('emptraveldetails');
             }
             catch(\Exception $e) {
                     die($e->getMessage());
                     // Some DB Error happened, log it and let the user know
             }
        }else {
            return $this->redirect()->toRoute('reported-student-list');
        }
    }



   public function editStdInitialRelationDetailsAction()
   {
        $this->loginDetails();
      //Get id from the route
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){ 
            $form = new StudentRelationDetailsForm();
            $studentAdmissionModel = new StudentRelationDetails();
            $form->bind($studentAdmissionModel);

                $checkInitialRelationDetails = $this->studentAdmissionService->checkStdInitialRelationDetails($id);

                $nationality = $this->studentAdmissionService->listSelectData($tableName = 'nationality', $columnName = 'nationality', NULL);
                $dzongkhag = $this->studentAdmissionService->listSelectData($tableName = 'dzongkhag', $columnName = 'dzongkhag_name', NULL);
                $relationType = $this->studentAdmissionService->listSelectData($tableName = 'relation_type', $columnName = 'relation', NULL); 

                $message = NULL;

                if(!$checkInitialRelationDetails){
                    $message = 'Failure';
                    $this->flashMessenger()->addMessage("This particular student don't have initial relation details entered. Please Click on View/ Add button to add more or edit the existing one.");
                    return $this->redirect()->toRoute('studentrelationdetails');
                }else{
                    $request = $this->getRequest();
                    if($request->isPost()){
                        $form->setData($request->getPost());
                            if($form->isValid()){
                                try{
                                    $this->studentAdmissionService->saveStudentRelationDetails($studentAdmissionModel);
                                    $this->auditTrailService->saveAuditTrail("EDIT", "Student Relation Details", "ALL", "SUCCESS");
                                    $this->flashMessenger()->addMessage('Initial Relation was successfully edited');
                                    return $this->redirect()->toRoute('studentrelationdetails');
                                }
                                catch(\Exception $e){
                                    $message = 'Failure';
                                    $this->flashMessenger()->addMessage($e->getMessage());
                                     return $this->redirect()->toRoute('studentrelationdetails');
                                }
                            }
                        }
                    }
                    
                    
                        return new ViewModel(array(
                            'id' => $id,
                            'form' => $form,
                            'nationality' => $nationality,
                            'dzongkhag' => $dzongkhag,
                            'relationType' => $relationType,
                            'stdDetails' => $this->studentAdmissionService->getStudentPersonalDetails($id),
                            'relationDetails' => $this->studentAdmissionService->getStdInitialRelationDetails($id),
                            'message' => $message,
                        ));

            }else{
                return $this->redirect()->toRoute('studentrelationdetails');
        }
   }



   public function studentParentDetailsAction()
   {
        $this->loginDetails();

        // Default values
        $studentList = array();
        $programmesId = NULL;
        $semesterId = NULL;
        $sectionId = NULL;
        $studentName = NULL;
        $studentId = NULL;
      //  $studentSectionForm = NULL;

        $form = new StudentDetailSearchForm($this->serviceLocator);

        $tableName = 'student_semester';
        $columnName = 'semester';
        $studentSemester = $this->studentAdmissionService->listSelectData($tableName, $columnName, NULL);

        $tableName = 'student_section';
        $columnName = 'section';
        $studentSection = $this->studentAdmissionService->listSelectData($tableName, $columnName, NULL);

        $message = NULL;

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
               $programmesId = $this->getRequest()->getPost('programmes_id');
               $semesterId = $this->getRequest()->getPost('semester_id');
               $sectionId = $this->getRequest()->getPost('student_section_id');
               $studentName = $this->getRequest()->getPost('student_name');
               $studentId = $this->getRequest()->getPost('student_id');
               $studentList = $this->studentAdmissionService->getStudentList($programmesId, $semesterId, $sectionId, $studentName, $studentId);
            }
        }

        return new ViewModel(array(
            'form' => $form,
            'programmesId' => $programmesId,
            'semesterId' => $semesterId,
            'sectionId' => $sectionId,
            'studentName' => $studentName,
            'studentId' => $studentId,
            'studentList' => $studentList,
            'studentSemester' => $studentSemester,
            'studentSection' => $studentSection,
            'employee_details_id' => $this->employee_details_id,
            'keyphrase' => $this->keyphrase,
            'message' => $message,
            //'organisation_id' => $organisation_id
            ));
   }


   public function viewStudentParentDetailsAction()
   {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
                return new ViewModel(array(
                'stdDetails' => $this->studentAdmissionService->getStudentPersonalDetails($id),
                'stdParentDetails' => $this->studentAdmissionService->getStudentParentDetails($id),
            ));
        }else{
            return $this->redirect()->toRoute('studentparentdetails');
        }
   }


   public function addStudentParentDetailsAction()
   {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $form = new UpdateStudentParentDetailsForm($this->serviceLocator);
            $studentAdmissionModel = new UpdateStudentParentDetails();
            $form->bind($studentAdmissionModel);

            $tableName = 'nationality';
            $columnName = 'nationality';
            $nationality = $this->studentAdmissionService->listSelectData($tableName, $columnName, NULL);

            $message = NULL;

             $request = $this->getRequest();
             if ($request->isPost()) {
                 $form->setData($request->getPost());

                 $parent_details = $this->studentAdmissionService->crossCheckStdParentDetails($id);
                 if($parent_details){
                    $message = 'Failure';
                    $this->flashMessenger()->addMessage('Parent Details has been already added for this student. To edit please click on edit button to edit it.');
                 }else{
                    if ($form->isValid()) {
                         try {
                            $stdFatherDzongkhag = $this->getRequest()->getPost('father_dzongkhag');
                            $stdFatherGewog = $this->getRequest()->getPost('father_gewog');
                            $stdFatherVillage = $this->getRequest()->getPost('father_village');
                            $stdMotherDzongkhag = $this->getRequest()->getPost('mother_dzongkhag');
                            $stdMotherGewog = $this->getRequest()->getPost('mother_gewog');
                            $stdMotherVillage = $this->getRequest()->getPost('mother_village');

                             $this->studentAdmissionService->saveStudentParentDetails($studentAdmissionModel, $stdFatherDzongkhag, $stdFatherGewog, $stdFatherVillage, $stdMotherDzongkhag, $stdMotherGewog, $stdMotherVillage);
                             $this->auditTrailService->saveAuditTrail("INSERT", "Student Parent Details", "ALL", "SUCCESS");
                             $this->flashMessenger()->addMessage('Student Parent Details was successfully updated');                     
                             return $this->redirect()->toRoute('studentparentdetails');
                         }
                         catch(\Exception $e) {
                                $message = 'Failure';
                                $this->flashMessenger()->addMessage($e->getMessage());
                                 return $this->redirect()->toRoute('studentparentdetails');
                                 // Some DB Error happened, log it and let the user know
                         }
                     }
                 }
             }
               return array(
                'form' => $form,
                'id' => $id,
                'nationality' => $nationality,
                'stdDetails' => $this->studentAdmissionService->getStudentPersonalDetails($id),
                'message' => $message,
             );
        }else{
            return $this->redirect()->toRoute('studentparentdetails');
        }
   }


   public function editStudentParentDetailsAction()
   {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $form = new UpdateStudentParentDetailsForm($this->serviceLocator);
            $studentAdmissionModel = new UpdateStudentParentDetails();
            $form->bind($studentAdmissionModel);

            $tableName = 'nationality';
            $columnName = 'nationality';
            $nationality = $this->studentAdmissionService->listSelectData($tableName, $columnName, NULL);

            $parentDetails = $this->studentAdmissionService->getStdParentDetails($id);

            $message = NULL;

            $request = $this->getRequest();
            if($request->isPost()){
                $form->setData($request->getPost());
                if($form->isValid()){
                    try{
                        $stdFatherDzongkhag = $this->getRequest()->getPost('father_dzongkhag');
                        $stdFatherGewog = $this->getRequest()->getPost('father_gewog');
                        $stdFatherVillage = $this->getRequest()->getPost('father_village');
                        $stdMotherDzongkhag = $this->getRequest()->getPost('mother_dzongkhag');
                        $stdMotherGewog = $this->getRequest()->getPost('mother_gewog');
                        $stdMotherVillage = $this->getRequest()->getPost('mother_village');

                        $this->studentAdmissionService->updateStudentParentDetails($studentAdmissionModel, $stdFatherDzongkhag, $stdFatherGewog, $stdFatherVillage, $stdMotherDzongkhag, $stdMotherGewog, $stdMotherVillage);
                        $this->auditTrailService->saveAuditTrail("UPDATE", "Research Grant", "ALL", "SUCCESS");
                        $this->flashMessenger()->addMessage('Student Parent Details was successfully edited');
                        return $this->redirect()->toRoute('studentparentdetails');
                    }
                    catch(\Exception $e){
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                        die();
                    }
                
                }
            }

            return array(
                'id' => $id,
                'form' => $form,
                'nationality' => $nationality,
                'parentDetails' =>$parentDetails,
                'message' => $message,
                );
        }else{
            return $this->redirect()->toRoute('studentparentdetails');
        }
   }


   public function studentGuardianDetailsAction()
   {
        $this->loginDetails();

        // Default values
        $studentList = array();
        $programmesId = NULL;
        $semesterId = NULL;
        $sectionId = NULL;
        $studentName = NULL;
        $studentId = NULL;
      //  $studentSectionForm = NULL;

        $form = new StudentDetailSearchForm($this->serviceLocator);

        $tableName = 'student_semester';
        $columnName = 'semester';
        $studentSemester = $this->studentAdmissionService->listSelectData($tableName, $columnName, NULL);

        $tableName = 'student_section';
        $columnName = 'section';
        $studentSection = $this->studentAdmissionService->listSelectData($tableName, $columnName, NULL);

        $message = NULL;

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
               $programmesId = $this->getRequest()->getPost('programmes_id');
               $semesterId = $this->getRequest()->getPost('semester_id');
               $sectionId = $this->getRequest()->getPost('student_section_id');
               $studentName = $this->getRequest()->getPost('student_name');
               $studentId = $this->getRequest()->getPost('student_id');
               $studentList = $this->studentAdmissionService->getStudentList($programmesId, $semesterId, $sectionId, $studentName, $studentId);
            }
        }

        return new ViewModel(array(
            'form' => $form,
            'programmesId' => $programmesId,
            'semesterId' => $semesterId,
            'sectionId' => $sectionId,
            'studentName' => $studentName,
            'studentId' => $studentId,
            'studentList' => $studentList,
            'studentSemester' => $studentSemester,
            'studentSection' => $studentSection,
            'employee_details_id' => $this->employee_details_id,
            'keyphrase' => $this->keyphrase,
            'message' => $message,
            //'organisation_id' => $organisation_id
            ));
   }


   public function viewStudentGuardianDetailsAction()
   {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
                return new ViewModel(array(
                'stdGuardianDetails' => $this->studentAdmissionService->getStudentGuardianDetails($id),
            ));
        }else{
            return $this->redirect()->toRoute('studentguardiandetails');
        }
   }


   public function updateStudentGuardianDetailsAction()
   {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $form = new UpdateStudentGuardianDetailsForm();
            $studentAdmissionModel = new UpdateStudentGuardianDetails();
            $form->bind($studentAdmissionModel);

            $guardianDetails = $this->studentAdmissionService->getStdGuardianDetails($id);
            $guardianRelation = $this->studentAdmissionService->listSelectData($tableName = 'relation_type', $columnName = 'relation', NULL);

            $message = NULL;

            $request = $this->getRequest();
            if($request->isPost()){
                $form->setData($request->getPost());
                if($form->isValid()){
                    try{                   
                        $this->studentAdmissionService->saveStudentGuardianDetails($studentAdmissionModel);
                        $this->auditTrailService->saveAuditTrail("UPDATE", "Student Guardian Details", "ALL", "SUCCESS");
                        $this->flashMessenger()->addMessage('Student Guardian Details was successfully edited');
                        return $this->redirect()->toRoute('studentguardiandetails');
                    }
                    catch(\Exception $e){
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                        return $this->redirect()->toRoute('studentguardiandetails');
                    }
                
                }
            }

            return array(
                'id' => $id,
                'form' => $form,
                'guardianDetails' =>$guardianDetails,
                'message' => $message,
                'guardianRelation' => $guardianRelation,
                );
        }else{
            return $this->redirect()->toRoute('studentguardiandetails');
        }
   }



   public function studentPreviousSchoolDetailsAction()
   {
        $this->loginDetails();

        // Default values
        $studentList = array();
        $programmesId = NULL;
        $yearId = NULL;
        $studentName = NULL;
        $studentId = NULL;
      //  $studentSectionForm = NULL;

        $form = new StudentDetailSearchForm($this->serviceLocator);

        $tableName = 'programme_year';
        $columnName = 'year';
        $studentYear = $this->studentAdmissionService->listSelectData($tableName, $columnName, NULL);

        $present_year = date('Y')+1;
        $stdAcademicYear = array();        
        for($i=6; $i>0; $i--){
            $stdAcademicYear[($present_year-$i)."-".($present_year-$i+1)] = ($present_year-$i)."-".($present_year-$i+1);
        }

        //$stdAcademicYear = $this->studentAdmissionService->listSelectAcademicYear($tableName = 'student_semester_registration');

        $message = NULL;

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
               $programmesId = $this->getRequest()->getPost('programmes_id');
               $yearId = $this->getRequest()->getPost('year');
               $academicYear = $this->getRequest()->getPost('academic_year');
               $studentName = $this->getRequest()->getPost('student_name');
               $studentId = $this->getRequest()->getPost('student_id');
               $studentList = $this->studentAdmissionService->getStudentList($programmesId, $yearId, $academicYear, $studentName, $studentId);
            }
        }

        return new ViewModel(array(
            'form' => $form,
            'programmesId' => $programmesId,
            'yearId' => $yearId,
            'studentName' => $studentName,
            'studentId' => $studentId,
            'studentList' => $studentList,
            'studentYear' => $studentYear,
            'stdAcademicYear' => $stdAcademicYear,
            'employee_details_id' => $this->employee_details_id,
            'keyphrase' => $this->keyphrase,
            'message' => $message,
            //'organisation_id' => $organisation_id
            ));
   }


   public function viewStudentPreviousSchoolDetailsAction()
   {
        $this->loginDetails();

        //Get id from the route
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $form = new StudentPreviousSchoolForm();
            $studentAdmissionModel = new StudentPreviousSchool();
            $form->bind($studentAdmissionModel); 

            $message = NULL;

            $school_list = $this->studentAdmissionService->listSelectData($tableName = 'school', $columnName='school_name', NULL);

            $request = $this->getRequest();
            if($request->isPost()){
                $form->setData($request->getPost());
                 if(!$id)
                    $id = $this->getRequest()->getPost('student_id');
                    if($form->isValid()){ 
                        try{
                            $this->studentAdmissionService->saveStudentPreviousSchool($studentAdmissionModel);
                            $this->auditTrailService->saveAuditTrail("INSERT", "Student Previous School Details ", "ALL", "SUCCESS");
                            $this->flashMessenger()->addMessage('Student Previous School Details was successfully added');
                            return $this->redirect()->toRoute('viewstudentpreviousschooldetails', array('id'=>$this->my_encrypt($id, $this->keyphrase)));
                        }
                        catch(\Exception $e){
                            $message = 'Failure';
                            $this->flashMessenger()->addMessage($e->getMessage());
                             return $this->redirect()->toRoute('viewstudentpreviousschooldetails', array('id'=>$this->my_encrypt($id, $this->keyphrase)));
                        }
                    }
            }
            return new ViewModel(array(
                'id' => $id,
                'form' => $form,
                'stdDetails' => $this->studentAdmissionService->getStudentPersonalDetails($id),
                'stdPreviousSchool' => $this->studentAdmissionService->getStudentPreviousSchool($tableName = 'student_previous_school_details', $id),
                'school_list' => $school_list,
                'keyphrase' => $this->keyphrase,
                'message' => $message,
            ));
        }else{
            return $this->redirect()->toRoute('studentpreviousschooldetails');
        }
   }


   public function addStudentPreviousSchoolDetailsAction()
   {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $form = new StudentPreviousSchoolForm();
            $studentAdmissionModel = new StudentPreviousSchool();
            $form->bind($studentAdmissionModel);

            $request = $this->getRequest();
            if($request->isPost()){
                $form->setData($request->getPost());
                if($form->isValid()){
                    try{                   
                        $this->studentAdmissionService->saveStudentPreviousSchool($studentAdmissionModel);
                        $this->auditTrailService->saveAuditTrail("INSERT", "Student Previous School Details ", "ALL", "SUCCESS");
                        $this->flashMessenger()->addMessage('Student Previous School Details was successfully added');
                        return $this->redirect()->toRoute('studentpreviousschooldetails');
                    }
                    catch(\Exception $e){
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                        die();
                    }
                
                }
            }

            return array(
                'id' => $id,
                'form' => $form,
                'stdDetails' => $this->studentAdmissionService->getStudentPersonalDetails($id),
                );
        }else{
            return $this->redirect()->toRoute('studentpreviousschooldetails');
        }
   }


   public function editStudentPreviousSchoolDetailsAction()
   {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id',0);
         $id = $this->my_decrypt($id_from_route, $this->keyphrase);

         if(is_numeric($id)){
            $form = new StudentPreviousSchoolForm();
            $studentAdmissionModel = new StudentPreviousSchool();
            $form->bind($studentAdmissionModel);

            $message = NULL;

            $school_list = $this->studentAdmissionService->listSelectData($tableName = 'school', $columnName='school_name', NULL);

            $request = $this->getRequest();
            if($request->isPost()){
                $form->setData($request->getPost());
                if($form->isValid()){
                    try{
                        $this->studentAdmissionService->saveStudentPreviousSchool($studentAdmissionModel);
                        $this->auditTrailService->saveAuditTrail("EDIT", "Student Previous School Details ", "ALL", "SUCCESS");
                        $this->flashMessenger()->addMessage('Student Previous School Details was successfully edited');
                        return $this->redirect()->toRoute('studentpreviousschooldetails');
                    }
                    catch(\Exception $e){
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                        return $this->redirect()->toRoute('studentpreviousschooldetails');
                    }
                
                }
            }

            return array(
                'id' => $id,
                'form' => $form,
                'stdDetails' => $this->studentAdmissionService->getStudentDetails($tableName = 'student_previous_school_details', $id),
                'stdPreviousSchool' => $this->studentAdmissionService->getStdPreviousSchoolDetails($tableName = 'student_previous_school_details', $id),
                'school_list' => $school_list,
                'message' => $message,
                );
         }else{
            return $this->redirect()->toRoute('studentpreviousschooldetails');
         }
   }

 
    //the following function is to extract the data from the form 
    // and return clean data to be inserted into database
    public function extractFormData()
    {
        $studentCount = $this->getRequest()->getPost('studentCount');
        $sectionData = array();
        
        //evaluation data => 'evaluation_'.$i.$j,
        for($i=1; $i<=$studentCount; $i++)
        {
            $sectionData[$i]= $this->getRequest()->getPost('student_section_id'.$i);
        }
        return $sectionData;
    }

    //the following function is to extract the data from the form 
    // and return clean data to be inserted into database
    public function extractFormData1()
    {
        $studentCount = $this->getRequest()->getPost('studentCount');
        $houseData = array();
        
        //evaluation data => 'evaluation_'.$i.$j,
        for($i=1; $i<=$studentCount; $i++)
        {
            $houseData[$i]= $this->getRequest()->getPost('student_house_id'.$i);
        }
        return $houseData;
    }


    //the following function is to extract the data from the form 
    // and return clean data to be inserted into database
    public function extractFormData2()
    {
        $studentCount = $this->getRequest()->getPost('studentCount');
        $semesterData = array();
        
        //evaluation data => 'evaluation_'.$i.$j,
        for($i=1; $i<=$studentCount; $i++)
        {
            $semesterData[$i]= $this->getRequest()->getPost('semester_id'.$i);
        }
        return $semesterData;
    }

    public function extractNewStudentData()
    {
        $studentCount = $this->getRequest()->getPost('studentCount');
        $evaluationData = array();

        for($i=1; $i<=$studentCount; $i++){
          //  if($this->getRequest()->getPost('student_'.$i) == "yes"){
                $evaluationData[$i] = $this->getRequest()->getPost('student_'.$i);
         //   }
           // else{
             //   $evaluationData[$i] = NULL;
            //}
        }

        return $evaluationData;
    }

    public function extractProgrammeChangeData()
    {
        $studentCount = $this->getRequest()->getPost('studentCount');
        $evaluationData = array();

        for($i=1; $i<=$studentCount; $i++){
          //  if($this->getRequest()->getPost('student_'.$i) == "yes"){
                $evaluationData[$i] = $this->getRequest()->getPost('student_'.$i);
         //   }
           // else{
             //   $evaluationData[$i] = NULL;
            //}
        }

        return $evaluationData;
    }


    public function extractSemesterUpdateData()
    {
        $studentCount = $this->getRequest()->getPost('studentCount');
        $evaluationData = array();

        for($i=1; $i<=$studentCount; $i++){
          //  if($this->getRequest()->getPost('student_'.$i) == "yes"){
                $evaluationData[$i] = $this->getRequest()->getPost('student_'.$i);
         //   }
           // else{
             //   $evaluationData[$i] = NULL;
            //}
        }

        return $evaluationData;
    }


    // Sample for excel upload for students
    /*student details update  from College */
    public function uploadStudentListsAction()
    {
        $this->loginDetails();

        $form = new UploadStudentListsForm();
        $studentAdmissionModel = new UploadStudentLists();
        $form->bind($studentAdmissionModel);

         $newStudentListFile = $this->studentAdmissionService->listAllNewStudentFile($tableName = 'new_student_list_file', $this->organisation_id);

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
                     $this->studentAdmissionService->saveStudentListFile($studentAdmissionModel, $this->organisation_id);
                     $this->notificationService->saveNotification('New Student Registration', 'ALL', 'ALL', 'Student Admission');
                     $this->auditTrailService->saveAuditTrail("INSERT", "Student Registration", "ALL", "SUCCESS");
                     $this->flashMessenger()->addMessage('Student Lists successfully uploaded');                     
                     return $this->redirect()->toRoute('upload-student-lists');
                 }
                 catch(\Exception $e) {
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                         // Some DB Error happened, log it and let the user know
                 }
             }
         }
           return array(
             'form' => $form,
             'newStudentListFile' => $newStudentListFile,
             'organisation_id' => $this->organisation_id,
             'message' => $message,
         );
    } 


    public function downloadStudentExcelListAction()
    {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){        
        $file = $this->studentAdmissionService->getFileName($id);
        
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
            $this->redirect()->toRoute('uploadbulkstudentlist');
        }
    }

    // Function to import student in bulk from college
    public function uploadBulkStudentListAction()
    {
        $this->loginDetails();

        $form = new UploadStudentListsForm();
        $studentAdmissionModel = new UploadStudentLists();
        $form->bind($studentAdmissionModel);

         $newStudentListFile = $this->studentAdmissionService->listAllNewStudentFile($tableName = 'new_student_list_file', $this->organisation_id);

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
                     $this->studentAdmissionService->saveBulkStudentFile($studentAdmissionModel, $this->organisation_id);
                      $this->notificationService->saveNotification('New Student Registration', $this->organisation_id, $this->organisation_id, 'Student Admission');
                     $this->auditTrailService->saveAuditTrail("INSERT", "Student Registration", "ALL", "SUCCESS");
                     $this->flashMessenger()->addMessage('Student Lists successfully uploaded');                     
                     return $this->redirect()->toRoute('uploadbulkstudentlist');
                 }
                 catch(\Exception $e) {
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                        return $this->redirect()->toRoute('uploadbulkstudentlist');
                         // Some DB Error happened, log it and let the user know
                 }
             }
         }
           return array(
             'form' => $form,
             'newStudentListFile' => $newStudentListFile,
             'organisation_id' => $this->organisation_id,
             'keyphrase' => $this->keyphrase,
             'message' => $message,
         );
    } 


    /*Display student details  */
     public function studentListsAction()
    {
        $this->loginDetails();

        $studentList = array();
        $stdName = NULL;
        $stdId = NULL;
        $stdCid = NULL;
        $stdProgramme = NULL;

        $form = new StudentSearchForm($this->serviceLocator);

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
                $stdName = $this->getRequest()->getPost('student_name');
                $stdId = $this->getRequest()->getPost('student_id');
                $stdCid = $this->getRequest()->getPost('cid');
                $stdProgramme = $this->getRequest()->getPost('student_status_type');
                $studentList = $this->studentAdmissionService->getStudentLists($stdName, $stdId, $stdCid, $stdProgramme, $this->organisation_id);
            }
        }

        return new ViewModel(array(
            'form' => $form,
            'stdName' => $stdName,
            'stdId' => $stdId,
            'stdCid' => $stdCid,
            'stdProgramme' => $stdProgramme,
            'studentList' => $studentList,
            'organisation_id' => $this->organisation_id,
            'usertype' => $this->usertype,
            'keyphrase' => $this->keyphrase,
            ));
    }

    //Function to change programme of student
    public function programmeChangeAction()
    {
        $this->loginDetails();
        $studentList = array();
        $stdProgramme = NULL;
        $stdYear = NULL;
        $stdName = NULL;
        $stdId = NULL;
        $studentCount = 0;
        $changeProgrammeForm = NULL;

        $form = new StdChangeProgrammeSearchForm();

        $studentProgramme = $this->studentAdmissionService->listSelectData1($tableName = 'programmes', $columnName = 'programme_name', $this->organisation_id);
        $studentYear = $this->studentAdmissionService->listSelectData($tableName = 'programme_year', $columnName = 'year', NULL);
      //  $studentSection = $this->studentAdmissionService->listSelectData($tableName = 'student_section', $columnName = 'section');

        $changeProgramme = $this->studentAdmissionService->listSelectData1($tableName = 'programmes', $columnName = 'programme_name', $this->organisation_id);
        $changeSession = $this->studentAdmissionService->listSelectData($tableName = 'academic_session', $columnName = 'academic_session', NULL);

        $message = NULL;

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());

            if($form->isValid()){
                $stdProgramme = $this->getRequest()->getPost('programme');
                $stdYear = $this->getRequest()->getPost('year');
                $stdName = $this->getRequest()->getPost('studentName');
                $stdId = $this->getRequest()->getPost('studentId');
                $studentList = $this->studentAdmissionService->getProgrammeChangeStudentLists($stdProgramme, $stdYear, $stdName, $stdId, $this->organisation_id);
                $studentCount = count($studentList);
            }
        }

        $changeProgrammeForm = new ChangeProgrammeForm($studentCount, $this->serviceLocator);

        return new ViewModel(array(
            'form' => $form,
            'studentProgramme' => $studentProgramme,
            'studentYear' => $studentYear,
            'stdProgramme' => $stdProgramme,
            'stdYear' => $stdYear,
            'stdName' => $stdName,
            'stdId' => $stdId,
            'studentList' => $studentList,
            'organisation_id' => $this->organisation_id,
            'employee_details_id' => $this->employee_details_id,
            'changeProgramme' => $changeProgramme,
            'changeSession' => $changeSession,
            'changeProgrammeForm' => $changeProgrammeForm,
            'studentCount' => $studentCount,
            'message' => $message,
        ));
    }


    // Record the student section into student_semester_registration at first time
    public function updateStudentChangeProgrammeAction()
    {
        $form = new ChangeProgrammeForm($studentCount = 'null', $this->serviceLocator);

        //$organisation_id = 1;
        $request = $this->getRequest();
        if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                $stdProgramme = $this->getRequest()->getPost('programme');
                $stdYear = $this->getRequest()->getPost('year');
                $stdName = $this->getRequest()->getPost('studentName');
                $stdId = $this->getRequest()->getPost('studentId');
                $changeProgramme = $this->getRequest()->getPost('changed_programme');
                $changeSession = $this->getRequest()->getPost('changed_session');
                $year = $this->getRequest()->getPost('year_id');
                $semester = $this->getRequest()->getPost('semester_id');
                $academicYear = $this->getRequest()->getPost('academic_year');
                $updateDate = $this->getRequest()->getPost('updated_date');
                $updateBy = $this->getRequest()->getPost('updated_by');

               // $studentList = $this->studentAdmissionService->getProgrammeChangeStudentLists($stdProgramme, $stdSemester, $stdSection, $stdName, $stdId, $this->organisation_id);
               // $studentCount = count($studentList);
                $programme_data = $this->extractProgrammeChangeData();

                try {
                     $this->studentAdmissionService->updateStudentChangeProgramme($programme_data, $stdProgramme, $stdYear, $stdName, $stdId, $this->organisation_id, $changeProgramme, $changeSession, $year, $semester, $academicYear, $updateDate, $updateBy);
                     $this->auditTrailService->saveAuditTrail("INSERT", "Student Programme Change Details", "ALL", "SUCCESS");
                     $this->auditTrailService->saveAuditTrail("UPDATE", "Student", "programmes_id", "SUCCESS");
                     $this->flashMessenger()->addMessage('Student Programme is successfully updated');
                     return $this->redirect()->toRoute('programmechange');
         } 
         catch(\Exception $e) {
                $message = 'Failure';
                $this->flashMessenger()->addMessage($e->getMessage());
                return $this->redirect()->toRoute('programmechange');
                 // Some DB Error happened, log it and let the user know
                }
            }
        }   
       return array(
            'form' => $form,
        );  
    }


    public function studentProgrammeChangedListAction()
    {
        $this->loginDetails();
        $studentList = array();
        $stdProgramme = NULL;
        $stdSemester = NULL;
        $stdYear = NULL;


        $form = new ChangeProgrammeSearchForm($this->serviceLocator);

        $studentProgramme = $this->studentAdmissionService->listSelectData1($tableName = 'programmes', $columnName = 'programme_name', $this->organisation_id);
        $studentSemester = $this->studentAdmissionService->listSelectData($tableName = 'student_semester', $columnName = 'semester', NULL);

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
                $stdProgramme = $this->getRequest()->getPost('programme');
                $stdSemester = $this->getRequest()->getPost('semester');
                $stdYear = $this->getRequest()->getPost('year'); 
                $studentList = $this->studentAdmissionService->getChangedProgrammeStudentList($stdProgramme, $stdSemester, $stdYear, $this->organisation_id);
            }
        }

        return new ViewModel(array(
            'form' => $form,
            'studentProgramme' => $studentProgramme,
            'studentSemester' => $studentSemester,
            'stdProgramme' => $stdProgramme,
            'stdSemester' => $stdSemester,
            'stdYear' => $stdYear,
            'studentList' => $studentList,
            'organisation_id' => $this->organisation_id,
        ));
    }

    public function parentPortalAccessAction()
    {
        $this->loginDetails();

        $studentList = array();
        $stdProgramme = NULL;
        $stdYear = NULL;
        $stdName = NULL;
        $stdId = NULL;

        $form = new StdChangeProgrammeSearchForm();

        $studentProgramme = $this->studentAdmissionService->listSelectData1($tableName = 'programmes', $columnName = 'programme_name', $this->organisation_id);
        $studentYear = $this->studentAdmissionService->listSelectData($tableName = 'programme_year', $columnName = 'year', NULL);

        //$changeProgramme = $this->studentAdmissionService->listSelectData1($tableName = 'programmes', $columnName = 'programme_name', $this->organisation_id);

        $message = NULL;

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
                $stdProgramme = $this->getRequest()->getPost('programme');
                $stdYear = $this->getRequest()->getPost('year');
                $stdName = $this->getRequest()->getPost('studentName');
                $stdId = $this->getRequest()->getPost('studentId');
                $studentList = $this->studentAdmissionService->getProgrammeChangeStudentLists($stdProgramme, $stdYear, $stdName, $stdId, $this->organisation_id);
            }
        }


        return new ViewModel(array(
            'form' => $form,
            'studentProgramme' => $studentProgramme,
            'studentYear' => $studentYear,
            'stdProgramme' => $stdProgramme,
            'stdYear' => $stdYear,
            'stdName' => $stdName,
            'stdId' => $stdId,
            'studentList' => $studentList,
            'organisation_id' => $this->organisation_id,
            'employee_details_id' => $this->employee_details_id,
            'keyphrase' => $this->keyphrase,
            'message' => $message,
        ));
    }


    public function assignFatherAccessAction()
    {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase); 

        $message = NULL;

        if(is_numeric($id)){ 
            $check_father = $this->studentAdmissionService->crossCheckStudentParent('Father', $id); //echo $check_father; die();
             $check_father_cid = $this->studentAdmissionService->crossCheckStudentParentCid('Father', $id);
            if($check_father == NULL){
                $message = 'Failure';
                $this->flashMessenger()->addMessage("This particular student don't have Father record. Please assign try for others!");
                 return $this->redirect()->toRoute('parentportalaccess');
            }else if($check_father != NULL && $check_father_cid == NULL){
                $message = 'Failure';
                $this->flashMessenger()->addMessage("Father Citizenhip ID Number was not entered. Please update before you assign.");
                 return $this->redirect()->toRoute('parentportalaccess');
            }
            else{
                try {
                    $this->studentAdmissionService->assignParentPortalAccess('1', $id, $check_father_cid);
                    $this->auditTrailService->saveAuditTrail("INSERT", "Parent Portal Access", "ALL", "SUCCESS");
                    $this->flashMessenger()->addMessage('Father was assigned successfully for parent portal access');
                    return $this->redirect()->toRoute('parentportalaccess');
                }
                catch(\Exception $e) {
                    $message = 'Failure';
                    $this->flashMessenger()->addMessage($e->getMessage());
                    return $this->redirect()->toRoute('parentportalaccess');
                     // Some DB Error happened, log it and let the user know
             }
            }
             
            return array(
                'message' => $message,
            );
        }else{
            $this->redirect()->toRoute('parentportalaccess');
        }
    }


    public function assignMotherAccessAction()
    {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        $message = NULL;

        if(is_numeric($id)){
            $check_mother = $this->studentAdmissionService->crossCheckStudentParent('Mother', $id); //echo $check_father; die();
             $check_mother_cid = $this->studentAdmissionService->crossCheckStudentParentCid('Mother', $id);
            if($check_mother == NULL){
                $message = 'Failure';
                $this->flashMessenger()->addMessage("This particular student don't have Mother record. Please assign try for others!");
                return $this->redirect()->toRoute('parentportalaccess');
            }else if($check_mother != NULL && $check_mother_cid == NULL){
                $message = 'Failure';
                $this->flashMessenger()->addMessage("Mother Citizenhip ID Number was not entered. Please update before you assign.");
                 return $this->redirect()->toRoute('parentportalaccess');
            }else{
                try {
                 $this->studentAdmissionService->assignParentPortalAccess('2', $id, $check_mother_cid);
                 $this->auditTrailService->saveAuditTrail("INSERT", "Parent Portal Access", "ALL", "SUCCESS");
                 $this->flashMessenger()->addMessage('Mother was assigned successfully for parent portal access');
                 return $this->redirect()->toRoute('parentportalaccess');
             }
             catch(\Exception $e) {
                    $message = 'Failure';
                    $this->flashMessenger()->addMessage($e->getMessage());
                    return $this->redirect()->toRoute('parentportalaccess');
                     // Some DB Error happened, log it and let the user know
             }
            }             
            return array(
                'message' => $message);
        }else{
            $this->redirect()->toRoute('parentportalaccess');
        }
    }


    public function assignGuardianAccessAction()
    {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        $message = NULL;

        if(is_numeric($id)){
            $check_guardian = $this->studentAdmissionService->crossCheckStudentParent('Guardian', $id); //echo $check_father; die();
             $check_guardian_cid = $this->studentAdmissionService->crossCheckStudentParentCid('Guardian', $id);
            if($check_guardian == NULL){
                $message = 'Failure';
                $this->flashMessenger()->addMessage("This particular student don't have Guradian record. Please assign try for others!");
                return $this->redirect()->toRoute('parentportalaccess');
            }else if($check_guardian != NULL && $check_guardian_cid == NULL){
                $message = 'Failure';
                $this->flashMessenger()->addMessage("This Guardian Citizenhip ID Number was not entered. Please update before you assign.");
                 return $this->redirect()->toRoute('parentportalaccess');
            }else{
                try {
                 $this->studentAdmissionService->assignParentPortalAccess($parent_type='Guardian', $id, $check_guardian_cid);
                 $this->auditTrailService->saveAuditTrail("INSERT", "Parent Portal Access", "ALL", "SUCCESS");
                 $this->flashMessenger()->addMessage('Guardian was assigned successfully for parent portal access');
                 return $this->redirect()->toRoute('parentportalaccess');
             }
             catch(\Exception $e) {
                    $message = 'Failure';
                    $this->flashMessenger()->addMessage($e->getMessage());
                    return $this->redirect()->toRoute('parentportalaccess');
                     // Some DB Error happened, log it and let the user know
             }

            }
             
            return array(
                'message' => $message,
            );
        }else{
            $this->redirect()->toRoute('parentportalaccess');
        }
    }


    public function viewParentPortalAccessDetailsAction()
    {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            return new ViewModel(array(
                'parentAccessDetails' => $this->studentAdmissionService->getAssignedParentPortalAccess('Details', $id),
                'parentAccessNationality' => $this->studentAdmissionService->getAssignedParentPortalAccess('Nationality', $id),
                'parentAccessDzongkhag' => $this->studentAdmissionService->getAssignedParentPortalAccess('Dzongkhag', $id),
            ));
        }else{
            $this->redirect()->toRoute('parentportalaccess');
        }
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
             

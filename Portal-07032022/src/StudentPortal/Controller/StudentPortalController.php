<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace StudentPortal\Controller;

use Zend\ServiceManager\ServiceLocatorInterface;
use StudentPortal\Service\StudentPortalServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use StudentPortal\Model\StudentPortal;
use StudentPortal\Model\StudentDetail;
use StudentPortal\Form\StudentClubAttendanceSearchForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;

use Zend\Http\Response\Stream;
use Zend\Http\Headers;

use DOMPDFModule\View\Model\PdfModel;

/**
 * Description of IndexController
 *
 * @author Mendrel
 */
class StudentPortalController extends AbstractActionController
{
    
	protected $studentService;
    protected $notificationService;
    protected $auditTrailService;
    protected $serviceLocator;
    protected $username;
    protected $userrole;
    protected $userregion;
    protected $usertype;
    protected $userDetails;
    protected $employee_details_id;
    protected $student_details_id;
    protected $student_id;
	protected $organisation_id;

    protected $keyphrase = "RUB_IMS";
	
	public function __construct(StudentPortalServiceInterface $studentService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->studentService = $studentService;
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
        * Getting the student_id/employee_details_id related to username
        */
        if($this->usertype == 1){
            $empData = $this->studentService->getUserDetailsId($this->username, $tableName = 'employee_details');
            foreach($empData as $emp){
                $this->employee_details_id = $emp['id'];
                $this->organisation_id = $emp['organisation_id'];
                $this->userDetails = $emp['first_name']." ".$emp['middle_name']." ".$emp['last_name'];
                $this->userImage = $emp['profile_picture'];
            }
        }

        if($this->usertype == 2){
            $stdData = $this->studentService->getUserDetailsId($this->username, $tableName = 'student');
            foreach($stdData as $std){
                $this->student_details_id = $std['id'];
                $this->student_id = $std['student_id'];
                $this->programmes_id = $std['programmes_id'];
                $this->organisation_id = $std['organisation_id'];
                $this->userDetails = $std['first_name']." ".$std['middle_name']." ".$std['last_name'];
                $this->userImage = $std['profile_picture'];
            }
        }

        //get the user details such as name
           // $this->userDetails = $this->studentService->getUserDetails($this->username, $this->usertype);
            //$this->userImage = $this->studentService->getUserImage($this->username, $this->usertype);
	}

    public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
       $this->layout()->setVariable('userImage', $this->userImage);
    }

	public function studentDashboardAction()
     {
        $this->loginDetails();

          return new ViewModel(array(
          	/*'form' => $form,
          	'personalDetails' => $this->staffService->getStaffPersonalDetails($this->employee_details_id),
          	'empLastLeave' => $this->staffService->getEmpLastLeaveDetails($this->employee_details_id),
          	'employee_details_id' => $this->employee_details_id,*/
          ));
     }


     public function studentProfileAction()
     {
        $this->loginDetails(); 
        //get the id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            return new ViewModel(array(
                'studentDetails' => $this->studentService->getStudentDetails($id),
                'studentPersonalDetails' => $this->studentService->getStudentPersonalDetails($id),
                'studentCategory' => $this->studentService->getStudentCategoryDetails($id),
                'studentNationality' => $this->studentService->getStudentNationality($id),
                'stdPermanentAddress' => $this->studentService->getStudentPermanentAddress($id),
                'studentCountry' => $this->studentService->getStudentCountry($id),
                'stdContactDetails' => $this->studentService->getStudentContactDetails($id),
                'stdRelationDetails' => $this->studentService->getStudentRelationDetails($id),
                'studentResponsibility' => $this->studentService->getStudentResponsibility($id, $this->organisation_id),
                'studentAchievement' => $this->studentService->getStudentAchievement($id),
                'studentParticipation' => $this->studentService->getStudentParticipation($id),
                'studentContribution' => $this->studentService->getStudentContribution($id),
                'studentPreviousSchool' => $this->studentService->getStudentPreviousSchoolDetails($id),
                'studentDisciplineRecode' => $this->studentService->getStudentDisciplineRecords($id),
                'id' => $id,
                'organisation_id' => $this->organisation_id,
                'keyphrase' => $this->keyphrase,
            ));
        }
        else
        {
            return new ViewModel(array(
                'studentDetails' => $this->studentService->getStudentDetails($this->student_details_id),
                'studentPersonalDetails' => $this->studentService->getStudentPersonalDetails($this->student_details_id),
                'studentCategory' => $this->studentService->getStudentCategoryDetails($this->student_details_id),
                'studentNationality' => $this->studentService->getStudentNationality($this->student_details_id),
                'stdPermanentAddress' => $this->studentService->getStudentPermanentAddress($this->student_details_id),
                'studentCountry' => $this->studentService->getStudentCountry($this->student_details_id),
                'stdContactDetails' => $this->studentService->getStudentContactDetails($this->student_details_id),
                'stdRelationDetails' => $this->studentService->getStudentRelationDetails($this->student_details_id),
                'studentResponsibility' => $this->studentService->getStudentResponsibility($this->student_details_id, $this->organisation_id),
                'studentAchievement' => $this->studentService->getStudentAchievement($this->student_details_id),
                'studentParticipation' => $this->studentService->getStudentParticipation($this->student_details_id),
                'studentContribution' => $this->studentService->getStudentContribution($this->student_details_id),
                'studentPreviousSchool' => $this->studentService->getStudentPreviousSchoolDetails($this->student_details_id),
                'studentDisciplineRecode' => $this->studentService->getStudentDisciplineRecords($this->student_details_id),
                'student_details_id' => $this->student_details_id,
                'keyphrase' => $this->keyphrase,
            ));
        }
     }


     // Function to view the modules of that particular year and semester.
     public function studentAcademicModuleAction()
     {
        $this->loginDetails();
        return new ViewModel(array(
            'studentSemesterYear' => $this->studentService->getStudentSemesterAcademicYear($this->student_details_id),
            'studentAcademicModules' => $this->studentService->getStudentAcademicModules($this->student_details_id, $this->programmes_id, $this->organisation_id),
            'student_details_id' => $this->student_details_id,
            'organisation_id' => $this->organisation_id,
            'programmes_id' => $this->programmes_id,
        ));
     }

     public function viewAcademicTimetableAction()
     {
        $this->loginDetails(); 
        $stdAcademicTimetable = $this->studentService->getStudentAcademicTimetable($this->student_details_id, $this->organisation_id);
        $timetableTimings = $this->studentService->getTimetableTiming($this->organisation_id);
       // $moduleTutor = $this->studentService->getAcademicModuleTutor($this->student_details_id);

        return new ViewModel(array(
          'stdAcademicTimetable' => $stdAcademicTimetable,
          'timetableTimings' => $timetableTimings,
          'studentSemesterYear' => $this->studentService->getStudentSemesterAcademicYear($this->student_details_id),
         // 'moduleTutor' => $moduleTutor,
        ));
     }

     public function studentCurrentSemesterDetailsAction()
     {
        $this->loginDetails();
        $stdCurrentSemesterDet = $this->studentService->getStdCurrentSemesterDetails($this->student_details_id, $this->organisation_id);
        $moduleList = $this->studentService->getAcademicModuleLists($this->student_details_id, $this->organisation_id);

        return array(
          'stdCurrentSemesterDet' => $stdCurrentSemesterDet,
          'moduleList' => $moduleList,
        );
     }


     public function stdCurrentSemesterCaDetailsAction()
     {
        $this->loginDetails();

        $studentCADetails = $this->studentService->getStdCurrentCADetails($this->student_details_id, $this->organisation_id);
        $moduleList = $this->studentService->getAcademicModuleLists($this->student_details_id, $this->organisation_id);

        return array(
          'studentCADetails' => $studentCADetails,
          'moduleList' => $moduleList,

        );
     }


     public function individualStdModuleCaDetailsAction()
     {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $academic_modules_id = $id; 
            $check_compiled_ca = $this->studentService->crossCheckCompiledCaDetails($academic_modules_id, $this->student_details_id, $this->organisation_id);
            $ca_details = $this->studentService->getStudentModuleCaDetails($academic_modules_id, $this->student_details_id, $this->organisation_id);

            $module_details = $this->studentService->getStudentModuleCaDetails($academic_modules_id, $this->student_details_id, $this->organisation_id);

            return new ViewModel(array(
                'ca_details' => $ca_details,
                'check_compiled_ca' => $check_compiled_ca,
                'module_details' => $module_details,
				));

        }else{
            return $this->redirect()->toRoute('index');
        }
     }


     public function individualStdModuleAbsentDetailsAction()
     { 
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){ 
            $academic_modules_id = $id; 
            $attendance_details = $this->studentService->getStudentModuleAttendanceDetails($academic_modules_id, $this->student_details_id, $this->organisation_id);

            $module_details = $this->studentService->getStudentModuleAttendanceDetails($academic_modules_id, $this->student_details_id, $this->organisation_id);

            return new ViewModel(array(
                'attendance_details' => $attendance_details,
                'module_details' => $module_details,
				));
        } else {
            return $this->redirect()->toRoute('index');
        }
     }


     //Function to view the result of individual student by student 
     public function viewIndividualDeclaredResultAction()
     {
        $this->loginDetails();
        
        $declared_results = $this->studentService->getDeclaredResult($this->student_details_id, $this->student_id, $this->programmes_id, $this->organisation_id);

        return new ViewModel(array(
            'declared_results' => $declared_results,
        ));
        
     }

     public function viewStudentAssessmentMarksAction()
     {
        $this->loginDetails();
        $assessmentMarks = $this->studentService->getStudentAssessmentMarks($this->student_details_id);

        return new ViewModel(array(
            'assessmentMarks' => $assessmentMarks,
        ));
     }

     public function studentRecheckMarkStatusAction()
     {
        $this->loginDetails();
        $recheckMarks = $this->studentService->getStudentRecheckMarkStatus($this->student_details_id);

        return new ViewModel(array(
            'recheckMarks' => $recheckMarks,
        ));
     }

     public function studentReassessmentStatusAction()
     {
        $this->loginDetails();
        $reassessmentModule = $this->studentService->getStudentReassessmentStatus($this->student_details_id);

        return new ViewModel(array(
            'reassessmentModule' => $reassessmentModule,
        ));
     }

     public function studentRepeatModulesAction()
     {
        $this->loginDetails();
        $repeatModules = $this->studentService->getStudentRepeatModuleStatus($this->student_details_id);

        return new ViewModel(array(
            'repeatModules' => $repeatModules,
        ));
     }

     public function viewStudentHostelDetailsAction()
     {
        $this->loginDetails();
        $studentHostelRoom = $this->studentService->getStudentHostelRoomDetails($this->student_details_id);
        $studentHostelInventory = $this->studentService->getStudentHostelRoomInventory($this->student_details_id);

        return new ViewModel(array(
            'studentHostelRoom' => $studentHostelRoom,
            'studentHostelInventory' => $studentHostelInventory,
        ));
     }

     public function hostelChangeApplicationStatusAction()
     {
        $this->loginDetails();
        $hostelChangeApplication = $this->studentService->getHostelChangeApplicationStatus($this->student_details_id);

        return new ViewModel(array(
            'hostelChangeApplication' => $hostelChangeApplication,
        ));
     }

     public function studentClubApplicationStatusAction()
     {
        $this->loginDetails();
        $applicationStatus = $this->studentService->getStudentClubApplicationStatus($this->student_details_id);

        return new ViewModel(array(
            'applicationStatus' => $applicationStatus,
        ));
     }

     public function viewStdClubApplicationDetailsAction()
     {
        $this->loginDetails();
        $id = (int) $this->params()->fromRoute('id',0);

        return new ViewModel(array(
            'applicationDetails' => $this->studentService->getStudentClubApplicationDetails($id),
        ));
     }

     public function studentClubListAction()
     {
        $this->loginDetails();
        $clubList = $this->studentService->getStudentClubList($status = 'Approved', $this->student_details_id);

        return new ViewModel(array(
            'clubList' => $clubList,
        ));
     }

     public function viewStudentClubMemberListAction()
     {
        $this->loginDetails();
        $id = (int) $this->params()->fromRoute('id',0);

        $clubDetails = $this->studentService->getMemberClubDetails($id);
        $noOfMembers = $this->studentService->getClubMemberNos($id);
        $studentClubMembers = $this->studentService->getStudentClubMemberList($id);



        return new ViewModel(array(
            'clubDetails' => $clubDetails,
            'noOfMembers' => $noOfMembers,
            'studentClubMembers' => $studentClubMembers,
        ));
     }


     public function viewStudentClubAttendanceAction()
     {
        $this->loginDetails();
        $id = (int) $this->params()->fromRoute('id',0);

        $form = new StudentClubAttendanceSearchForm();

        $clubDetails = $this->studentService->getMemberClubDetails($id);

        $clubAttendanceList = array();

        $message = NULL;

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
                $attendanceYear = $this->getRequest()->getPost('year');
                $clubAttendanceList = $this->studentService->getStudentClubAttendanceList($attendanceYear, $id, $this->student_details_id);
                $this->flashMessenger()->addMessage('Your club attendance list is successful');
                //$this->redirect()->toRoute('viewstudentclubattendance', array('id' => $id));
            }
        }

        return new ViewModel(array(
            'form' => $form,
            'clubAttendanceList' => $clubAttendanceList,
            'student_details_id' => $this->student_details_id,
            'message' => $message,
            'clubDetails' => $clubDetails,
        ));
     }

     public function viewStdExtraCurricularAttendanceAction()
     {
        $this->loginDetails();
        $form = new StudentClubAttendanceSearchForm();

        $extraCurricularAttendance = array();

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
                $attendanceYear = $this->getRequest()->getPost('year');
                $extraCurricularAttendance = $this->studentService->getStdExtraCurricularAttendanceRecord($attendanceYear, $this->student_details_id);
                //$this->redirect()->toRoute('viewstudentclubattendance', array('id' => $id));
            }
        }

        return new ViewModel(array(
            'form' => $form,
            'extraCurricularAttendance' => $extraCurricularAttendance,
            'student_details_id' => $this->student_details_id,
        ));
     }

     public function viewCounselingAppointmentStatusAction()
     {
        $this->loginDetails();
        $appointmentStatus = $this->studentService->getCounselingAppointmentStatus($this->student_details_id);
        $scheduledAppointment = $this->studentService->getScheduledAppointment($this->student_details_id);
        $recommendedCounseling = $this->studentService->getRecommendedCounseling($this->student_details_id);

        return new ViewModel(array(
            'appointmentStatus' => $appointmentStatus,
            'scheduledAppointment' => $scheduledAppointment,
            'recommendedCounseling' => $recommendedCounseling,
        ));
     }

     public function counselingAppointmentDetailAction()
     {
        $this->loginDetails();
        $id = (int) $this->params()->fromRoute('id',0);

        $appointmentDetail = $this->studentService->getCounselingAppointmentDetails($id);

        return new ViewModel(array(
            'id' => $id,
            'appointmentDetail' => $appointmentDetail,
        ));
     }


     public function counselingScheduledDetailAction()
     {
        $this->loginDetails();
        $id = (int) $this->params()->fromRoute('id',0);

        $scheduledDetail = $this->studentService->getCounselingScheduledDetails($id);

        return new ViewModel(array(
            'id' => $id,
            'scheduledDetail' => $scheduledDetail,
        ));
     }

     public function studentDisciplinaryRecordAction()
     {
        $this->loginDetails();
        $disciplinaryRecords = $this->studentService->getDisciplinaryRecords($this->student_details_id);

        return new ViewModel(array(
            'disciplinaryRecords' => $disciplinaryRecords,
        ));
     }

     public function studentDisciplinaryRecordDetailsAction()
     {
        $this->loginDetails();
        $id = (int) $this->params()->fromRoute('id',0);

        return new ViewModel(array(
            'disciplinaryRecordDetails' => $this->studentService->getStdDisciplinaryRecordDetails($id),
        ));
     }

     public function studentMedicalRecordsAction()
     {
        $this->loginDetails();
        $medicalRecords = $this->studentService->getStdMedicalRecordList($this->student_details_id);

        return new ViewModel(array(
            'medicalRecords' => $medicalRecords,
        ));
     }


     public function stdMedicalRecordDetailsAction()
     {
        $this->loginDetails();
        $id = (int) $this->params()->fromRoute('id',0);

        return new ViewModel(array(
            'medicalDetails' => $this->studentService->getStdMedicalRecordDetails($id),
        ));
     }

     public function studentLeaveStatusAction()
     {
        $this->loginDetails();
        $leaveStatus = $this->studentService->getStudentLeaveStatus($this->student_details_id);

        return new ViewModel(array(
            'leaveStatus' => $leaveStatus,
        ));
     }


     public function viewStudentLeaveDetailsAction()
     {
        $this->loginDetails();
        $id = (int) $this->params()->fromRoute('id',0);

        return new ViewModel(array(
            'stdLeaveDetails' => $this->studentService->getStudentLeaveDetails($id),
        ));
     }

     public function viewStdExamTimetableAction()
     {
        $this->loginDetails();
        $examTimetable = $this->studentService->getExamTimetable($this->programmes_id);
        $examDates = $this->studentService->getExamDates($this->organisation_id);
        $nonEligibleModule = $this->studentService->getNoEligibleModules($this->student_details_id);

        return new ViewModel(array(
            'examTimetable' => $examTimetable,
            'examDates' => $examDates,
            'nonEligibleModule' => $nonEligibleModule,
        ));
     }

     public function printStudentDetailsCardAction()
     {
        $this->loginDetails();
         //get the id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
        if(is_numeric($id)){ 
            $studentDetails = $this->studentService->getStudentDetails($id);
            $stdetails = $this->studentService->getStudentDetails($id);
            $studentImage = $this->studentService->getStuddentProfilePicture($id);

			$std_details_array = array();
			foreach($stdetails as $details){
				$std_details_array = $details;
			}

			$date = date("Y-m-d");
            $pdf = new PdfModel();
            $pdf->setOption('fileName', $std_details_array['student_id'].'StudentDetailsCard'.$date); // Triggers PDF download, automatically appends ".pdf"
            $pdf->setOption('display', PdfModel::DISPLAY_ATTACHMENT);
            $pdf->setOption('paperSize', 'a4'); // Defaults to "8x11"
            $pdf->setOption('paperOrientation', 'landscape'); // Defaults to "portrait"


           
            //To set view variables
            $pdf->setVariables(array(
				'id' => $id,
                'studentDetails' => $studentDetails,
                'studentImage' => $studentImage,
           ));

            return $pdf;
        }
        else{
            return $this->redirect()->toRoute('studentprofile');
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

<?php


namespace UniversityAdministration\Controller;

use UniversityAdministration\Service\UniversityAdministrationServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use UniversityAdministration\Form\NewsPaperForm;
use UniversityAdministration\Model\NewsPaper;
use UniversityAdministration\Form\MeetingTypeForm;
use UniversityAdministration\Model\MeetingType;
use UniversityAdministration\Form\MeetingMinutesForm;
use UniversityAdministration\Model\MeetingMinutes;

//Session
use Zend\Session\Container;
use Zend\Http\Response\Stream;
use Zend\Http\Headers;
//AJAX
//use Zend\Paginator\Adapter\DbSelect;
//use Zend\View\Model\JsonModel;
//use Zend\Form\Element\Select;


  
class UniversityAdministrationController extends AbstractActionController
{
    protected $universityAdministrationService;
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

	
	public function __construct(UniversityAdministrationServiceInterface $universityAdministrationService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->universityAdministrationService = $universityAdministrationService;
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
            $empData = $this->universityAdministrationService->getUserDetailsId($tableName = 'employee_details', $this->username);
            foreach($empData as $emp){
                $this->employee_details_id = $emp['id'];
            }
        }else if($this->usertype == 2){
            $stdData = $this->universityAdministrationService->getUserDetailsId($tableName = 'student', $this->username);
            foreach($stdData as $std){
                $this->student_id = $std['id'];
            }
        }else if($this->usertype == 4){
            $jobData = $this->universityAdministrationService->getUserDetailsId($tableName = 'job_applicant', $this->username);
            foreach($jobData as $job){
                $this->student_id = $job['id'];
            }
        }

        //get the organisation id
        if($this->usertype == 1){
            $organisationID = $this->universityAdministrationService->getOrganisationId($tableName = 'employee_details', $this->username);
            foreach($organisationID as $organisation){
                $this->organisation_id = $organisation['organisation_id'];
            }
        }else if($this->usertype == 2){
            //get the organisation id
            $stdOrganisationID = $this->universityAdministrationService->getOrganisationId($tableName = 'student', $this->username);
            foreach($stdOrganisationID as $organisation){
                $this->organisation_id = $organisation['organisation_id'];
            }
        }else if($this->usertype == 4){
            //get the organisation id
            $jobOrganisationID = $this->universityAdministrationService->getOrganisationId($tableName = 'job_applicant', $this->username);
            foreach($jobOrganisationID as $organisation){
                $this->organisation_id = $organisation['organisation_id'];
            }
            
        }

        //get the user details such as name
        $this->userDetails = $this->universityAdministrationService->getUserDetails($this->username, $this->usertype);

        $this->userImage = $this->universityAdministrationService->getUserImage($this->username, $this->usertype);

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
    public function rubimsInformationAction()
    {
        $this->loginDetails();

        return array(
        );
    }   

    public function jobApplicantHelpAction()
    {
        $this->loginDetails();

        return array(
        );
    }   

    public function pqcMeetingInformationAction()
    {
        $this->loginDetails();

        return array(
        );
    }   


    public function ricMeetingInformationAction()
    {
        $this->loginDetails();

        return array(
        );
    }   


    public function uacMeetingInformationAction()
    {
        $this->loginDetails();

        return array(
        );
    }   

    public function addMeetingTypeAction()
    { 
        $this->loginDetails();

        //$orgABBR = $this->organisation_id;
        //var_dump($orgABBR); die();

        $userrole = $this->userrole;

        $form = new MeetingTypeForm();

        $meetingtypeModel = new MeetingType();

        $form->bind($meetingtypeModel);

        $categories = $this->universityAdministrationService->listAll($tableName='meeting_type', $this->organisation_id, $this->employee_details_id);

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
                    //var_dump($meetingtypeModel); die();
                     $this->universityAdministrationService->saveCategory($meetingtypeModel);
                     $this->auditTrailService->saveAuditTrail("INSERT", "Employee Task Category", "ALL", "SUCCESS");

                     $this->flashMessenger()->addMessage('Employee Task Category was successfully added');
                     return $this->redirect()->toRoute('meetingtype');
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
            'userrole' => $userrole,
            'message' => $message,
        );
    }

    public function editMeetingTypeAction()
    {
        $this->loginDetails();
        //get the id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase); 

        if(is_numeric($id)){
            $form = new MeetingTypeForm();
            $meetingtypeModel = new MeetingType();
            $form->bind($meetingtypeModel);

            //var_dump($meetingtypeModel); die();

            
            $meetingtype = $this->universityAdministrationService->getMeetingTypeDetails($id);

            $request = $this->getRequest();
             if ($request->isPost()) {
                 $form->setData($request->getPost());
                 if ($form->isValid()) {
                     try {
                         $this->universityAdministrationService->saveCategory($meetingtypeModel);
                         $this->auditTrailService->saveAuditTrail("EDIT", "Employee Task Category", "ALL", "SUCCESS");

                         $this->flashMessenger()->addMessage('Employee Task Category was successfully edited');
                         return $this->redirect()->toRoute('meetingtype');
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
                'meetingtype' => $meetingtype);
        }else{
            return $this->redirect()->toRoute('employeetaskcategory');
        }
    }

    public function meetingMinuteInformationAction()
    {
        $this->loginDetails();
        //get the id
        
        $id = $this->employee_details_id;//(int) $this->params()->fromRoute('id', 0);

        $userrole = $this->userrole;

        $username = $this->username;

        $form = new MeetingMinutesForm();
        $meetingminuteModel = new MeetingMinutes();
        $form->bind($meetingminuteModel);

        $message = NULL;
        
        $meetingType = $this->getMeetingTypeArrayList();

        $selectData = $this->universityAdministrationService->listAll($tableName='meeting_minutes', $this->organisation_id, $id);
        
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

                //var_dump($meetingminuteModel); die();

                try {
                    //var_dump($newspaperModel); die();
                    
                    $this->universityAdministrationService->saveMeetingMinutes($meetingminuteModel);
                    $this->notificationService->saveNotification('Your Task/Project Activity Record', $id, 'NULL', 'Staff Task/Project Activity Record');
                    $this->auditTrailService->saveAuditTrail("INSERT", "Your Task/Project Activity Record", "ALL", "SUCCESS");

                    $this->flashMessenger()->addMessage(' Your Task/Project Activity Record was successfully added');
                    return $this->redirect()->toRoute('meetingminuteinformation');
                }
                catch(\Exception $e) {
                    die($e->getMessage());
                    // Some DB Error happened, log it and let the user know
                }
            }
        }
         
        return array(
            'form' => $form,
            'userrole' => $userrole,
            'username' => $username,
            'employee_details_id' => $id,
            'keyphrase' => $this->keyphrase,
            'meetingType' => $meetingType,
            'selectData' => $selectData,
            'message' => $message);
    }

    public function editMeetingMinuteAction()
    {
        $this->loginDetails(); 
        //get the id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $form = new MeetingMinutesForm();
            $meetingminutesModel = new MeetingMinutes();
            $form->bind($meetingminutesModel);

            //Need to send value of the table name and columns
            $tableName = 'meeting_minutes';
            //$columnName = 'employee_task_category';
            $meetingTypeSelect = $this->universityAdministrationService->listSelectData1($tableName, $id);

            $meetingminutes = $this->universityAdministrationService->getMeetingMinutesDetails($id);

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
                    //var_dump($meetingminutesModel); die();
                    
                     try {
                        $this->universityAdministrationService->saveMeetingMinutes($meetingminutesModel);
                        //var_dump($meetingminutesModel); die();
                        $this->auditTrailService->saveAuditTrail("EDIT", "Employee Task Category", "ALL", "SUCCESS");
                        $this->flashMessenger()->addMessage('Employee Task Category was successfully edited');
                        return $this->redirect()->toRoute('meetingminuteinformation');
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
                'selectData' => $meetingTypeSelect,
                'meetingminutes' => $meetingminutes,
                'message' => $message);
        }else{
            return $this->redirect()->toRoute('addempemployeetaskrecord');
        }
    }

    public function rubmeetingMinuteInformationAction()
    {
        $this->loginDetails();
        //get the id
    
        
        $id = $this->employee_details_id;//(int) $this->params()->fromRoute('id', 0);

        $userrole = $this->userrole;

        $form = new MeetingMinutesForm();
        $meetingminuteModel = new MeetingMinutes();
        $form->bind($meetingminuteModel);

        $message = NULL;
        
        $meetingType = $this->getMeetingTypeArrayList();

        $selectData = $this->universityAdministrationService->listAll($tableName='meeting_minutes', 0, $id);

        
        
        
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

                //var_dump($meetingminuteModel); die();

                try {
                    //var_dump($newspaperModel); die();
                    
                    $this->universityAdministrationService->saveMeetingMinutes($meetingminuteModel);
                    $this->notificationService->saveNotification('Your Task/Project Activity Record', $id, 'NULL', 'Staff Task/Project Activity Record');
                    $this->auditTrailService->saveAuditTrail("INSERT", "Your Task/Project Activity Record", "ALL", "SUCCESS");

                    $this->flashMessenger()->addMessage(' Your Task/Project Activity Record was successfully added');
                    return $this->redirect()->toRoute('meetingminuteinformation');
                }
                catch(\Exception $e) {
                    die($e->getMessage());
                    // Some DB Error happened, log it and let the user know
                }
            }
        }
         
        return array(
            'form' => $form,
            'userrole' => $userrole,
            'employee_details_id' => $id,
            'keyphrase' => $this->keyphrase,
            'meetingType' => $meetingType,
            'selectData' => $selectData,
            'message' => $message);
    }


    public function newspaperInformationAction()
    {
        $this->loginDetails();
        //get the id

        
        $id = $this->employee_details_id;//(int) $this->params()->fromRoute('id', 0);

        $userrole = $this->userrole;

        $form = new NewsPaperForm();
        $newspaperModel = new NewsPaper();
        $form->bind($newspaperModel);

        $message = NULL;
        
        //$staffDetail = $this->universityAdministrationService->findStaff($id);
        //Need to send value of the table name and columns
        $tableName = 'newspaper';
        $columnName = 'newspaper_type';
        $selectData = $this->universityAdministrationService->listAll($tableName, $columnName, $id);
        
        //$staffDetail = $this->employeetaskService->getStaffDetails($id);

        //$activityRecords = $this->employeetaskService->listAll1($id);
        
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

                try {
                    
                    $this->universityAdministrationService->saveNewsPaper($newspaperModel);
                    $this->notificationService->saveNotification('Your Task/Project Activity Record', $id, 'NULL', 'Staff Task/Project Activity Record');
                    $this->auditTrailService->saveAuditTrail("INSERT", "Your Task/Project Activity Record", "ALL", "SUCCESS");

                    $this->flashMessenger()->addMessage(' Your Task/Project Activity Record was successfully added');
                    return $this->redirect()->toRoute('newspaperinformation');
                }
                catch(\Exception $e) {
                    die($e->getMessage());
                    // Some DB Error happened, log it and let the user know
                }
            }
        }
         
        return array(
            'form' => $form,
            'userrole' => $userrole,
            'employee_details_id' => $id,
            'keyphrase' => $this->keyphrase,
            //'staffDetail' => $staffDetail,
            'selectData' => $selectData,
            'message' => $message);
    }

    public function downloadEnglishNewsPaperAction()
    {
        $this->loginDetails();

        //echo "string"; die();
        //get the student id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $file = $this->universityAdministrationService->getFileName($table = 'newspaper', $id);
        
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
        }
        else
        {
            $this->redirect()->toRoute('newspaperInformation');
        }
    }

    public function downloadDzongkhaNewsPaperAction()
    {
        $this->loginDetails();

        //echo "string"; die();
        //get the student id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $file = $this->universityAdministrationService->getFileName1($id);
        
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
        }
        else
        {
            $this->redirect()->toRoute('newspaperInformation');
        }
    }

    public function getMeetingTypeArrayList()
    {
        $this->loginDetails();
        $meetingtype_array = array();
        
        $organisation = $this->organisation_id;

        $meeting_type_array_list = $this->universityAdministrationService->listSelectData('meeting_type', $organisation);

        /*if($organisation != 1){
            foreach($meeting_type_array_list as $key=>$value){
                if($key != $organisation){
                    unset($meeting_type_array_list[$key]);
                }
            }
            $meetingtype_array = $meeting_type_array_list;
        } else {
        */
            //need to insert the all option for OVC
            $meetingtype_array = $meeting_type_array_list;
            
        //}
        
        return $meetingtype_array;
    }

    public function downloadMeetingMinutesAction()
    {
        $this->loginDetails();

        //echo "string"; die();
        //get the student id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $file = $this->universityAdministrationService->getFileName($table = 'meeting_minutes', $id);
        
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
        }
        else
        {
            $this->redirect()->toRoute('meetingminuteinformation');
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
             
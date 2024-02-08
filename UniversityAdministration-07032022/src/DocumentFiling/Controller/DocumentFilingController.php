<?php
namespace DocumentFiling\Controller;

use DocumentFiling\Service\DocumentFilingServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DocumentFiling\Form\FilingTypeForm;
use DocumentFiling\Model\FilingType;
use DocumentFiling\Form\FilingDocumentForm;
use DocumentFiling\Model\FilingDocument;

//Session
use Zend\Session\Container;
use Zend\Http\Response\Stream;
use Zend\Http\Headers;
//AJAX
//use Zend\Paginator\Adapter\DbSelect;
//use Zend\View\Model\JsonModel;
//use Zend\Form\Element\Select;

  
class DocumentFilingController extends AbstractActionController
{
    protected $documentFilingService;
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

	
	public function __construct(DocumentFilingServiceInterface $documentFilingService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
        
		$this->documentFilingService = $documentFilingService;
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
            $empData = $this->documentFilingService->getUserDetailsId($tableName = 'employee_details', $this->username);
            foreach($empData as $emp){
                $this->employee_details_id = $emp['id'];
            }

            $empDept = $this->documentFilingService->getUserDetailsId($tableName = 'employee_details', $this->username);
            foreach($empDept as $empDept){
                $this->department_id = $empDept['departments_id'];
            }
        }else if($this->usertype == 2){
            $stdData = $this->documentFilingService->getUserDetailsId($tableName = 'student', $this->username);
            foreach($stdData as $std){
                $this->student_id = $std['id'];
            }
        }else if($this->usertype == 4){
            $jobData = $this->documentFilingService->getUserDetailsId($tableName = 'job_applicant', $this->username);
            foreach($jobData as $job){
                $this->student_id = $job['id'];
            }
        }

        //get the organisation id
        if($this->usertype == 1){
            $organisationID = $this->documentFilingService->getOrganisationId($tableName = 'employee_details', $this->username);
            foreach($organisationID as $organisation){
                $this->organisation_id = $organisation['organisation_id'];
            }
        }else if($this->usertype == 2){
            //get the organisation id
            $stdOrganisationID = $this->documentFilingService->getOrganisationId($tableName = 'student', $this->username);
            foreach($stdOrganisationID as $organisation){
                $this->organisation_id = $organisation['organisation_id'];
            }
        }else if($this->usertype == 4){
            //get the organisation id
            $jobOrganisationID = $this->documentFilingService->getOrganisationId($tableName = 'job_applicant', $this->username);
            foreach($jobOrganisationID as $organisation){
                $this->organisation_id = $organisation['organisation_id'];
            }
            
        }

        //get the user details such as name
        $this->userDetails = $this->documentFilingService->getUserDetails($this->username, $this->usertype);

        $this->userImage = $this->documentFilingService->getUserImage($this->username, $this->usertype);

	}

    public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }

    /*This is action and route to view on index.html */
    public function addDepartmentFilingTypeAction()
    { 
        $this->loginDetails();

        //$orgABBR = $this->organisation_id;
        //var_dump($orgABBR); die();        

        $userrole = $this->userrole;

        $form = new FilingTypeForm();
        
        $filingtypeModel = new FilingType();

        $form->bind($filingtypeModel);

        $categories = $this->documentFilingService->listAll($tableName='meeting_type', $this->organisation_id, $this->employee_details_id, $this->department_id);
        
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
                    $this->documentFilingService->saveCategory($filingtypeModel);
                    $this->auditTrailService->saveAuditTrail("INSERT", "Department Filing Category", "ALL", "SUCCESS");

                    $this->flashMessenger()->addMessage('Document Filing Category was successfully added');
                    return $this->redirect()->toRoute('departmentfilingtype');
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
            'department_id' => $this->department_id,
            'employee_details_id' => $this->employee_details_id,
            'keyphrase' => $this->keyphrase,
            'categories' => $categories,
            'userrole' => $userrole,
            'message' => $message,
        );
    }   

    public function editDepartmentFilingTypeAction()
    {
        $this->loginDetails();
        //get the id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase); 

        if(is_numeric($id)){
            $form = new FilingTypeForm();
            $filingtypeModel = new FilingType();
            $form->bind($filingtypeModel);

            $filingtype = $this->documentFilingService->getFilingTypeDetails($id);

            $request = $this->getRequest();
             if ($request->isPost()) {
                 $form->setData($request->getPost());
                 if ($form->isValid()) {
                     try {
                         $this->documentFilingService->saveCategory($filingtypeModel);
                         $this->auditTrailService->saveAuditTrail("EDIT", "Employee Task Category", "ALL", "SUCCESS");

                         $this->flashMessenger()->addMessage('Document Filing Category was successfully edited');
                         return $this->redirect()->toRoute('departmentfilingtype');
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
                'filingtype' => $filingtype);
        }else{
            return $this->redirect()->toRoute('employeetaskcategory');
        }
    }

    public function uploadDepartmentDocumentsAction()
    {
        
        $this->loginDetails();
        //get the id        
        
        
        $id = $this->employee_details_id;//(int) $this->params()->fromRoute('id', 0);

        $userrole = $this->userrole;

        $username = $this->username;

        $form = new FilingDocumentForm();
        $filingdocumentModel = new FilingDocument();
        $form->bind($filingdocumentModel);


        $message = NULL;
        $department_id = $this->department_id;
        $filingType = $this->getFilingTypeArrayList($department_id);

        $selectData = $this->documentFilingService->listAll($tableName='department_filing_documents', $this->organisation_id, $id, $department_id);  

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
                    $this->documentFilingService->saveFilingDocument($filingdocumentModel);
                    $this->notificationService->saveNotification('Your document has been uploaded', $id, 'NULL', 'Documents uploaded');
                    $this->auditTrailService->saveAuditTrail("INSERT", "Filing Documents uploaded", "ALL", "SUCCESS");

                    $this->flashMessenger()->addMessage(' Your documents has been uploaded successfully');
                    return $this->redirect()->toRoute('uploaddepartmentdocuments');
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
            'filingType' => $filingType,
            'selectData' => $selectData,
            'message' => $message);
    }

    public function viewFilingDocumentAction()
    {
        
        $this->loginDetails();
        //get the id        
        
        
        $id = $this->employee_details_id;//(int) $this->params()->fromRoute('id', 0);

        $userrole = $this->userrole;

        $username = $this->username;

        $form = new FilingDocumentForm();
        $filingdocumentModel = new FilingDocument();
        $form->bind($filingdocumentModel);


        $message = NULL;
        $department_id = $this->department_id;
        $filingType = $this->getFilingTypeArrayList($department_id);

        $selectData = $this->documentFilingService->listAll($tableName='department_filing_documents', $this->organisation_id, $id, $department_id);  

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
                    $this->documentFilingService->saveFilingDocument($filingdocumentModel);
                    $this->notificationService->saveNotification('Your document has been uploaded', $id, 'NULL', 'Documents uploaded');
                    $this->auditTrailService->saveAuditTrail("INSERT", "Filing Documents uploaded", "ALL", "SUCCESS");

                    $this->flashMessenger()->addMessage(' Your documents has been uploaded successfully');
                    return $this->redirect()->toRoute('uploaddepartmentdocuments');
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
            'filingType' => $filingType,
            'selectData' => $selectData,
            'message' => $message);
    }



    public function editFilingDocumentAction()
    {
        $this->loginDetails(); 
        //get the id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $form = new FilingDocumentForm();
            $filingdocumentModel = new FilingDocument();
            $form->bind($filingdocumentModel);

            //Need to send value of the table name and columns
            $tableName = 'department_filing_documents';
            //$columnName = 'employee_task_category';
            $filingTypeSelect = $this->documentFilingService->listSelectData1($tableName, $id);

            $filingdocument = $this->documentFilingService->getFilingDocumentDetails($id);
//var_dump($filingdocument); die();
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
                    //var_dump($filingdocumentModel); die();
                    
                     try {
                        $this->documentFilingService->saveFilingDocument($filingdocumentModel);
                        //var_dump($meetingminutesModel); die();
                        $this->auditTrailService->saveAuditTrail("EDIT", "Filing Document Edited", "ALL", "SUCCESS");
                        $this->flashMessenger()->addMessage('Your documents was successfully edited');
                        return $this->redirect()->toRoute('uploaddepartmentdocuments');
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
                'filingdocument' => $filingdocument,
                'message' => $message);
        }else{
            return $this->redirect()->toRoute('addempemployeetaskrecord');
        }
    }

    public function getFilingTypeArrayList($department_id)
    {
        $this->loginDetails();
        $filingtype_array = array();
        
        $organisation = $this->organisation_id;

        $filing_type_array_list = $this->documentFilingService->listSelectData('meeting_type', $organisation, $department_id);

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
            $filingtype_array = $filing_type_array_list;
            
        //}
        
        return $filingtype_array;
    }

    public function downloadFilingDocumentsAction()
    {
        $this->loginDetails();

        //echo "string"; die();
        //get the student id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        
        if(is_numeric($id)){
            $file = $this->documentFilingService->getFileName($table = 'department_filing_documents', $id);
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
             
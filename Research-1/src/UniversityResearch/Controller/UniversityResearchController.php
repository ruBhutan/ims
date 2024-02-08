<?php

namespace UniversityResearch\Controller;

use UniversityResearch\Service\UniversityResearchServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use UniversityResearch\Model\AurgTitle;
use UniversityResearch\Model\AurgProjectDescription;
use UniversityResearch\Model\AurgActionPlan;
use UniversityResearch\Model\ResearchGrantAnnouncement;
use UniversityResearch\Model\ResearchRecommendation;
use UniversityResearch\Model\UpdateAurgGrant;
use UniversityResearch\Form\AurgTitleForm;
use UniversityResearch\Form\AurgActionPlanForm;
use UniversityResearch\Form\AurgProjectDescriptionForm;
use UniversityResearch\Form\ResearchGrantAnnouncementForm;
use UniversityResearch\Form\ResearchRecommendationForm;
use UniversityResearch\Form\UpdateAurgGrantForm;
use UniversityResearch\Form\SearchForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Zend\Http\Response\Stream;
use Zend\Http\Headers;

class UniversityResearchController extends AbstractActionController {

    protected $universityResearchService;
    protected $notificationService;
    protected $auditTrailService;
    protected $serviceLocator;
    protected $emailService;
    protected $username;
    protected $userrole;
    protected $usertype;
    protected $user_status_id;
    protected $userregion;
    protected $userDetails;
    protected $userImage;
    protected $lastGeneratedId;
    protected $employee_details_id;
    protected $organisation_id;
    protected $keyphrase = "RUB_IMS";

    public function __construct(UniversityResearchServiceInterface $universityResearchService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator) 
    {
        $this->universityResearchService = $universityResearchService;
        $this->notificationService = $notificationService;
        $this->auditTrailService = $auditTrailService;
        $this->serviceLocator = $serviceLocator;
        $this->emailService = $serviceLocator->get('Application\Service\EmailService');

        /*
         * To retrieve the user name from the session
        */
        $authPlugin = $serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
        $this->username = $authPlugin['username'];
         $this->userrole = $authPlugin['role'];
          $this->userregion = $authPlugin['region'];
           $this->usertype = $authPlugin['user_type_id'];
           $this->user_status_id = $authPlugin['user_status_id'];

        $empData = $this->universityResearchService->getUserDetailsId($this->username, $tableName = 'employee_details');
            foreach($empData as $emp){
                $this->employee_details_id = $emp['id'];
            }


        //get the organisation id
        $organisationID = $this->universityResearchService->getOrganisationId($this->username, $this->usertype);
        foreach($organisationID as $organisation){
            $this->organisation_id = $organisation['organisation_id'];
        }
        
        $this->userDetails = $this->universityResearchService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->universityResearchService->getUserImage($this->username, $this->usertype);
    }

    public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }

    public function researchAnnouncementForGrantAction() 
    { 
        $this->loginDetails();

        $form = new ResearchGrantAnnouncementForm();
        $universityResearchModel = new ResearchGrantAnnouncement();
        $form->bind($universityResearchModel);

        $grantAnnouncement = $this->universityResearchService->getResearchGrantAnnouncement($id = NULL, $this->organisation_id);
        $researchTypes = $this->universityResearchService->getAllResearchTypes($this->organisation_id);

        $message = NULL;

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $data = $this->getRequest()->getPost('researchannouncement');
                $research_grant_type = $data['research_grant_type'];
                $start_date = $data['start_date'];
                $end_date = $data['end_date'];
                try {
                    $this->universityResearchService->saveResearchGrantAnnouncement($universityResearchModel);
                    //$this->sendResearchGrantAnnoucementEmail($research_grant_type, $start_date, $end_date);
                    $this->notificationService->saveNotification('Research Grant', 'ALL', 'ALL', 'Research Grant Announcement');
                    $this->auditTrailService->saveAuditTrail("INSERT", "Research Grant Announcement", "ALL", "SUCCESS");
                    $this->flashMessenger()->addMessage('Announcement Dates was successfully added');
                    return $this->redirect()->toRoute('researchannouncementforgrant');
                } catch (\Exception $e) {
                    die($e->getMessage());
                    // Some DB Error happened, log it and let the user know
                }
            }
        }

        return array(
            'form' => $form,
            'grantAnnouncement' => $grantAnnouncement,
            'researchTypes' => $researchTypes,
            'keyphrase' => $this->keyphrase,
            'message' => $message);
    }

    public function editResearchGrantAnnouncementAction() 
    {
        $this->loginDetails();
        //get the id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $form = new ResearchGrantAnnouncementForm();
            $universityResearchModel = new ResearchGrantAnnouncement();
            $form->bind($universityResearchModel);

            $grantAnnouncement = $this->universityResearchService->getResearchGrantAnnouncement(NULL, $this->organisation_id);
            $grantDetail = $this->universityResearchService->getResearchGrantAnnouncement($id, $this->organisation_id);
            $researchTypes = $this->universityResearchService->getAllResearchTypes($this->organisation_id);

            $request = $this->getRequest();
            if ($request->isPost()) {
                $form->setData($request->getPost());
                if ($form->isValid()) {
                    try {
                        $this->universityResearchService->saveResearchGrantAnnouncement($universityResearchModel);
                        $this->flashMessenger()->addMessage('Research Dates were successfully edited');
                        $this->auditTrailService->saveAuditTrail("UPDATE", "Research Grant", "ALL", "SUCCESS");
                        return $this->redirect()->toRoute('researchannouncementforgrant');
                    } catch (\Exception $e) {
                        die($e->getMessage());
                        // Some DB Error happened, log it and let the user know
                    }
                }
            }

            return array(
                'form' => $form,
                'grantAnnouncement' => $grantAnnouncement,
                'grantDetail' => $grantDetail,
                'researchTypes' => $researchTypes);
        }else{
            return $this->redirect()->toRoute('researchannouncementforgrant');
        }
    }


    public function sendResearchGrantAnnoucementEmail($research_grant_type, $start_date, $end_date)
    {
        $this->loginDetails();

        $f_date = explode("/", $start_date);
        $from_date = $f_date[2]."-".$f_date[0]."-".$f_date[1];
        
        $t_date = explode("/", $end_date);
        $to_date = $t_date[2]."-".$t_date[0]."-".$t_date[1];
 

        $grant_type = $this->universityResearchService->getResearchGrantDetail($type = 'Grant Type', $research_grant_type);

        $organisation_name = $this->universityResearchService->getResearchGrantDetail($type = 'organisation', $this->organisation_id);
        
        $toEmail = "rubstaff.rub@rub.edu.bt";
        $messageTitle = "New Research Grant Annoucement";
        $messageBody = "Dear Sir/Madam,<br><b>".$organisation_name."</b> have annouced for grant type <b>".$grant_type."</b> from ".$from_date." to ".$to_date." on ".date('Y-m-d').".<br><b>Please click the link below for necessary action.</b><br><u>http://ims.rub.edu.bt</u><p>This is an auto-generated email and Please do not reply because this mail id is not monitored</p>";

        $this->emailService->sendMailer($toEmail, $messageTitle, $messageBody); 
    }

    public function applyAurgTitleAction() 
    {
        $this->loginDetails();

        $form = new AurgTitleForm();
        $universityResearchModel = new AurgTitle();
        $form->bind($universityResearchModel);

        $researcherDetails = $this->universityResearchService->getResearcherDetails($this->employee_details_id, $type = 'emp id');
        $grantAnnouncement = $this->universityResearchService->getResearchGrantAnnouncement($tmp_id = 'University Grant', $this->organisation_id);
        $grantList = $this->universityResearchService->getResearchGrantList();
        $employee_details = $this->universityResearchService->getEmployeeDetails($this->employee_details_id);

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
                    $aurgData = $this->universityResearchService->saveAurgTitle($universityResearchModel);
                    $lastGeneratedId = $aurgData->getId();
                    $encrypted_id = $this->my_encrypt($lastGeneratedId, $this->keyphrase);
                    $this->flashMessenger()->addMessage('Successfully added Annual University Grant Title');
                    $this->auditTrailService->saveAuditTrail("INSERT", "Aurg Grant", "ALL", "SUCCESS");
                    return $this->redirect()->toRoute('aurgactionplan', array('id' => $encrypted_id));
                } 
                catch (\Exception $e) {
                    die($e->getMessage());
                    // Some DB Error happened, log it and let the user know
                }
            }
            //The following statement is to redirect to next step/controller and send the last insert id 
        }

        return array(
            'id' => $id,
            'form' => $form,
            'employee_details_id' => $this->employee_details_id,
            'researcherDetails' => $researcherDetails,
            'grantAnnouncement' => $grantAnnouncement,
            'grantList' => $grantList,
            'message' => $message,
            'keyphrase' => $this->keyphrase,
            'employee_details' => $employee_details
        );
    }

    public function applyAurgProjectDescriptionAction() 
    {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){ 
            $data = $this->universityResearchService->findResearch($id);

            $form = new AurgProjectDescriptionForm();
            $universityResearchModel = new AurgProjectDescription();
            $form->bind($universityResearchModel);

            $message = NULL;

            $request = $this->getRequest();
            if ($request->isPost()) {
                $form->setData($request->getPost());
                if ($form->isValid()) {
                    try {
                        $this->universityResearchService->saveAurgProjectDescription($universityResearchModel);
                        $this->auditTrailService->saveAuditTrail('INSERT', 'Aurg Grant', 'ALL', 'SUCCESS');
                         $this->flashMessenger()->addMessage('Successfully added Annual University Grant Project Description');
                    } catch (\Exception $e) {
                        die($e->getMessage());
                        // Some DB Error happened, log it and let the user know
                    }
                    //The following statement is to redirect to next step/controller and send the last insert id 
                    return $this->redirect()->toRoute('aurgactionplan', array('id' => $this->my_encrypt($id, $this->keyphrase)));
                }
            }

            return array(
                'form' => $form,
                'dbData' => $data,
                'keyphrase' => $this->keyphrase,
                'message' => $message,
            );
        }else{
            return $this->redirect()->toRoute('aurgactionplan', array('id' => $this->my_encrypt($id, $this->keyphrase)));
        }
    }

    public function applyAurgActionPlanAction() 
    {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $data = $this->universityResearchService->findResearch($id);

            $form = new AurgActionPlanForm();
            $universityResearchModel = new AurgActionPlan();
            $form->bind($universityResearchModel);

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
                        $this->universityResearchService->saveAurgActionPlan($universityResearchModel);
                        $this->auditTrailService->saveAuditTrail('INSERT', 'Aurg Action Plan Budget', 'ALL', 'SUCCESS');
                        $this->flashMessenger()->addMessage('Successfully added Annual University Grant');
                    } catch (\Exception $e) {
                        die($e->getMessage());
                        // Some DB Error happened, log it and let the user know
                    }
                    //The following statement is to redirect to next step/controller and send the last insert id 
                    return $this->redirect()->toRoute('grantapplicationstatus');
                }
            }

            return array(
                'form' => $form,
                'dbData' => $data,
                'keyphrase' => $this->keyphrase,
                'message' => $message,
            );
        }else{
            return $this->redirect()->toRoute('aurgactionplan', array('id' => $this->my_encrypt($id, $this->keyphrase)));
        }
    }

    public function listAurgGrantsAction() 
    {
        $this->loginDetails();

        $form = new SearchForm();
        $researches = $this->universityResearchService->getAurgList(NULL, NULL, NULL, NULL);

        $message = NULL;

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $researcher_name = $this->getRequest()->getPost('researcher_name');
                $research_title = $this->getRequest()->getPost('research_title');
                $grant_type = $this->getRequest()->getPost('grant_type');
                $researches = $this->universityResearchService->getAurgList($researcher_name, $research_title, $grant_type, $status = 'NULL');
            }
        }

        return new ViewModel(array(
            'form' => $form,
            'researches' => $researches,
            'message' => $message,
            'keyphrase' => $this->keyphrase,
        ));
    }

    //list all researches- both CARG and AURG
    public function grantApplicationStatusAction() 
    {
        $this->loginDetails();

        $form = new SearchForm();

        $message = NULL;

        $researches = $this->universityResearchService->getResearchList($this->employee_details_id);

        return new ViewModel(array(
            'form' => $form,
            'researches' => $researches,
            'message' => $message,
            'keyphrase' => $this->keyphrase
        ));
    }

    //Function to delete if the research grant applicantion was not successfully submitted
    public function deleteResearchGrantApplicationAction()
    {
        $this->loginDetails();

        //get the student id
        $id_from_route = $this->params()->fromRoute('id');
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        $type_from_route = $this->params()->fromRoute('type');
        $type = $this->my_decrypt($type_from_route, $this->keyphrase);
       
        if(is_numeric($id)){
            try{
                $this->universityResearchService->deleteResearchGrantApplication($id, $type);
                $this->auditTrailService->saveAuditTrail("DELETE", "Research Grant", "ALL", "SUCCESS");

                $this->flashMessenger()->addMessage('You have delected Research Grant Application successfully');
                return $this->redirect()->toRoute('grantapplicationstatus');
            }
            catch(\Exception $e) {
                $message = 'Failure';
                $this->flashMessenger()->addMessage($e->getMessage());
            }
        	
        return array(
        	'id' => $id,
        	'message' => $message,
        );

        }else{
            return $this->redirect()->toRoute('grantapplicationstatus');
        }
    }

    public function viewAurgApplicationStatusAction() 
    {
        $this->loginDetails();
        //get the university id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $form = new ResearchRecommendationForm();
            $universityResearchModel = new ResearchRecommendation();
            $form->bind($universityResearchModel);

            $aurgDetails = $this->universityResearchService->findResearchDetails($id, $tableName = 'aurg_researchers');
            $actionPlanDetails = $this->universityResearchService->findResearchDetails($id, $tableName = 'aurg_action_plan_budget');
            $researcherDetails = $this->universityResearchService->findResearchDetails($id, $tableName = 'employee_details');
            $organisationList = $this->universityResearchService->listSelectData($tableName = 'organisation', $columnName = 'organisation_name');
            $previousResearch = $this->universityResearchService->getPreviousResearch($id);
            $employeeDetails = $this->universityResearchService->getResearcherDetails($id, $type = 'aurg id');

            return array(
                'form' => $form,
                'id' => $id,
                'researcherDetails' => $researcherDetails,
                'aurgDetails' => $aurgDetails,
                'actionPlanDetails' => $actionPlanDetails,
                'organisationList' => $organisationList,
                'previousResearch' => $previousResearch,
                'employeeDetails' => $employeeDetails,
                'keyphrase' => $this->keyphrase,
            );
        }else{
            return $this->redirect()->toRoute('grantapplicationstatus');
        }
    }


    public function downloadAurgResearchDocumentAction()
    {
        $this->loginDetails();
        //get the param from the view file
        $file_location = $this->params()->fromRoute('filename',0);
        
        //extract the training id and column name
        $column_name = preg_replace('!\d+!', '', $file_location);
        $column_name = rtrim($column_name,"_");
        preg_match_all('!\d+!',$file_location, $id);
        $application_id = implode(' ', $id[0]); 
        
        //get the location of the file from the database        
        $fileArray = $this->universityResearchService->getFileName($application_id, $column_name, $research_type='aurg');
        $file;
        foreach($fileArray as $set){
            $file = $set[$column_name];
        }
        
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

    public function viewCargApplicationStatusAction() 
    {
        $this->loginDetails();
        //get the university id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){ 
            $form = new ResearchRecommendationForm();
            $collegeResearchModel = new ResearchRecommendation();
            $form->bind($collegeResearchModel);

            $cargDetails = $this->universityResearchService->findCargResearchDetails($id, $tableName = 'carg_coresearchers');
             $researcherDetails = $this->universityResearchService->findResearchDetails($id, $tableName = 'carg_grant');
            $actionPlanDetails = $this->universityResearchService->findCargResearchDetails($id, $tableName = 'carg_action_plan');
            $budgetPlanDetails = $this->universityResearchService->findCargResearchDetails($id, $tableName = 'carg_budget_plan');

            return array(
                'form' => $form,
                'cargDetails' => $cargDetails,
                'researcherDetails' => $researcherDetails,
                'actionPlanDetails' => $actionPlanDetails,
                'budgetPlanDetails' => $budgetPlanDetails
            );
        }else{
            return $this->redirect()->toRoute('grantapplicationstatus');
        }
    }

    public function viewAurgApplicationAction() 
    {
        $this->loginDetails();
        //get the university id
        $id = (int) $this->params()->fromRoute('id', 0);

        $form = new ResearchRecommendationForm();
        $universityResearchModel = new ResearchRecommendation();
        $form->bind($universityResearchModel);

        $aurgDetails = $this->universityResearchService->findResearchDetails($id, $tableName = 'aurg_researchers');
        $actionPlanDetails = $this->universityResearchService->findResearchDetails($id, $tableName = 'aurg_action_plan_budget');
        $researcherDetails = $this->universityResearchService->findResearchDetails($id, $tableName = 'employee_details');
        $organisationList = $this->universityResearchService->listSelectData($tableName = 'organisation', $columnName = 'organisation_name');
        $previousResearch = $this->universityResearchService->getPreviousResearch($id);
        $employeeDetails = $this->universityResearchService->getResearcherDetails($id, $type = 'aurg id');

        return array(
            'form' => $form,
            'id' => $id,
            'researcherDetails' => $researcherDetails,
            'aurgDetails' => $aurgDetails,
            'actionPlanDetails' => $actionPlanDetails,
            'organisationList' => $organisationList,
            'previousResearch' => $previousResearch,
            'employeeDetails' => $employeeDetails
        );
    }

    public function drilAurgApprovalAction() 
    {
        $this->loginDetails();
        //get the university id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $form = new ResearchRecommendationForm();
            $universityResearchModel = new ResearchRecommendation();
            $form->bind($universityResearchModel);

            $aurgDetails = $this->universityResearchService->findResearchDetails($id, $tableName = 'aurg_researchers');
            $actionPlanDetails = $this->universityResearchService->findResearchDetails($id, $tableName = 'aurg_action_plan_budget');
            $researcherDetails = $this->universityResearchService->findResearchDetails($id, $tableName = 'employee_details');
            $organisationList = $this->universityResearchService->listSelectData($tableName = 'organisation', $columnName = 'organisation_name');
            $previousResearch = $this->universityResearchService->getPreviousResearch($id);
            $employeeDetails = $this->universityResearchService->getResearcherDetails($id, $type = 'aurg id');

            $request = $this->getRequest();
            if ($request->isPost()) {
                $form->setData($request->getPost());
                if ($form->isValid()) { 
                    try {
                        $this->universityResearchService->saveResearchRecommendation($universityResearchModel, 'dril');
                        $this->auditTrailService->saveAuditTrail('UPDATE', 'Aurg Grant', 'Application Status', 'SUCCESS');
                        $this->flashMessenger()->addMessage('Successfully updated the application status');
                    } catch (\Exception $e) {
                        die($e->getMessage());
                        // Some DB Error happened, log it and let the user know
                    }
                    //The following statement is to redirect to next step/controller and send the last insert id 
                    return $this->redirect()->toRoute('listcarggrants');
                }
            }

            return array(
                'form' => $form,
                'id' => $id,
                'researcherDetails' => $researcherDetails,
                'aurgDetails' => $aurgDetails,
                'actionPlanDetails' => $actionPlanDetails,
                'organisationList' => $organisationList,
                'previousResearch' => $previousResearch,
                'employeeDetails' => $employeeDetails
            );
        }else{
            return $this->redirect()->toRoute('listcarggrants');
        }
    }

    public function drerAurgApprovalAction() 
    {
        $this->loginDetails();
        //get the university id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $form = new ResearchRecommendationForm();
            $universityResearchModel = new ResearchRecommendation();
            $form->bind($universityResearchModel);

            $aurgDetails = $this->universityResearchService->findResearchDetails($id, $tableName = 'aurg_researchers');
            $actionPlanDetails = $this->universityResearchService->findResearchDetails($id, $tableName = 'aurg_action_plan_budget');
            $researcherDetails = $this->universityResearchService->findResearchDetails($id, $tableName = 'employee_details');
            $organisationList = $this->universityResearchService->listSelectData($tableName = 'organisation', $columnName = 'organisation_name');
            $previousResearch = $this->universityResearchService->getPreviousResearch($id);
            $employeeDetails = $this->universityResearchService->getResearcherDetails($id, $type = 'aurg id');

            $request = $this->getRequest();
            if ($request->isPost()) {
                $form->setData($request->getPost());
                if ($form->isValid()) {
                    try {
                        $this->universityResearchService->saveResearchRecommendation($universityResearchModel, 'drer');
                        $this->auditTrailService->saveAuditTrail('UPDATE', 'Aurg Grant', 'Application Status', 'SUCCESS');
                        $this->flashMessenger()->addMessage('Successfully updated the applications status');
                    } catch (\Exception $e) {
                        die($e->getMessage());
                        // Some DB Error happened, log it and let the user know
                    }
                    //The following statement is to redirect to next step/controller and send the last insert id 
                    return $this->redirect()->toRoute('listaurggrants');
                }
            }

            return array(
                'form' => $form,
                'id' => $id,
                'researcherDetails' => $researcherDetails,
                'aurgDetails' => $aurgDetails,
                'actionPlanDetails' => $actionPlanDetails,
                'organisationList' => $organisationList,
                'previousResearch' => $previousResearch,
                'employeeDetails' => $employeeDetails
            );
        }else{
            return $this->redirect()->toRoute('listaurggrants');
        }
    }

    public function updateUniversityGrantAction() 
    {
        $this->loginDetails();

        $form = new SearchForm();
        $researches = $this->universityResearchService->getAurgList(NULL, NULL, NULL, NULL);

        $message = NULL;

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $researcher_name = $this->getRequest()->getPost('researcher_name');
                $research_title = $this->getRequest()->getPost('research_title');
                $grant_type = $this->getRequest()->getPost('grant_type');
                $researches = $this->universityResearchService->getAurgList($researcher_name, $research_title, $grant_type, $status = 'Approved');
            }
        }

        return new ViewModel(array(
            'form' => $form,
            'researches' => $researches,
            'message' => $message,
            'keyphrase' => $this->keyphrase,
        ));
    }

    public function updateAurgAction() 
    {
        $this->loginDetails();
        //get the university id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $form = new UpdateAurgGrantForm();
            $universityResearchModel = new UpdateAurgGrant();
            $form->bind($universityResearchModel);

            $aurgDetails = $this->universityResearchService->findResearchDetails($id, $tableName = 'aurg_researchers');
            $actionPlanDetails = $this->universityResearchService->findResearchDetails($id, $tableName = 'aurg_action_plan_budget');
            $organisationList = $this->universityResearchService->listSelectData($tableName = 'organisation', $columnName = 'organisation_name');
            $researcherDetails = $this->universityResearchService->findResearchDetails($id, $tableName = 'employee_details');
            $previousResearch = $this->universityResearchService->getPreviousResearch($id);
            $employeeDetails = $this->universityResearchService->getResearcherDetails($id, $type = 'aurg id');

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
                        $this->universityResearchService->updateAurgStatus($universityResearchModel);
                        $this->auditTrailService->saveAuditTrail('INSERT', 'Aurg Grant Application Status', 'ALL', 'SUCCESS');
                        $this->flashMessenger()->addMessage('Aurg Application Status was successfully updated');
                        return $this->redirect()->toRoute('updateuniversitygrant');
                    } catch (\Exception $e) {
                        die($e->getMessage());
                        // Some DB Error happened, log it and let the user know
                    }
                    //The following statement is to redirect to next step/controller and send the last insert id 
                    
                }
            }

            return array(
                'form' => $form,
                'id' => $id,
                'researcherDetails' => $researcherDetails,
                'aurgDetails' => $aurgDetails,
                'actionPlanDetails' => $actionPlanDetails,
                'organisationList' => $organisationList,
                'previousResearch' => $previousResearch,
                'employeeDetails' => $employeeDetails
            );
        }else{
            return $this->redirect()->toRoute('updateuniversitygrant');
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

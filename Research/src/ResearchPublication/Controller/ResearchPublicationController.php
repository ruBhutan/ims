<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ResearchPublication\Controller;

use ResearchPublication\Service\ResearchPublicationServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use ResearchPublication\Form\ResearchPublicationForm;
use ResearchPublication\Form\PublicationTypeForm;
use ResearchPublication\Form\ResearchAnnouncementForm;
use ResearchPublication\Form\ResearchRecommendationForm;
use ResearchPublication\Form\ResearchTypeForm;
use ResearchPublication\Form\SeminarAnnouncementForm;
use ResearchPublication\Model\ResearchPublication;
use ResearchPublication\Model\PublicationType;
use ResearchPublication\Model\ResearchAnnouncement;
use ResearchPublication\Model\ResearchRecommendation;
use ResearchPublication\Model\ResearchType;
use ResearchPublication\Model\SeminarAnnouncement;
use Zend\Http\Response\Stream;
use Zend\Http\Headers;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\View\Helper\AbstractHelper;
use Zend\Session\Container;

/**
 * Description of IndexController
 *
 */
class ResearchPublicationController extends AbstractActionController {

    protected $publicationService;
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

    public function __construct(ResearchPublicationServiceInterface $publicationService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator) 
    {
        $this->publicationService = $publicationService;
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

        $empData = $this->publicationService->getUserDetailsId($this->username, $tableName = 'employee_details');
            foreach($empData as $emp){
                $this->employee_details_id = $emp['id'];
            }


        //get the organisation id
        $organisationID = $this->publicationService->getOrganisationId($this->username, $this->usertype);
        foreach($organisationID as $organisation){
            $this->organisation_id = $organisation['organisation_id'];
        }
        
        $this->userDetails = $this->publicationService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->publicationService->getUserImage($this->username, $this->usertype);
    }

    public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }

    public function researchTypeAction() 
    {
        $this->loginDetails();

        $form = new ResearchTypeForm();
        $publicationModel = new ResearchType();
        $form->bind($publicationModel);

        $researchTypes = $this->publicationService->getAllResearchTypes($this->organisation_id);
        if ($this->organisation_id == 1) {
            $grant_category = 'University Grant';
        } else {
            $grant_category = 'College Grant';
        }
        $message = NULL;

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    $this->publicationService->saveResearchType($publicationModel);
                    $this->flashMessenger()->addMessage('Research Type was successfully added');
                    $this->auditTrailService->saveAuditTrail("INSERT", "Research Type", "ALL", "SUCCESS");
                    return $this->redirect()->toRoute('researchtype');
                } catch (\Exception $e) {
                    die($e->getMessage());
                    // Some DB Error happened, log it and let the user know
                }
            }
        }

        return array(
            'form' => $form,
            'employee_details_id' => $this->employee_details_id,
            'organisation_id' => $this->organisation_id,
            'grant_category' => $grant_category,
            'message' => $message,
            'researchTypes' => $researchTypes,
            'keyphrase' => $this->keyphrase,
        );
    }

    public function editResearchTypeAction()
     {
        $this->loginDetails();
        //get the research type id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $form = new ResearchTypeForm();
            $publicationModel = new ResearchType();
            $form->bind($publicationModel);

            $researchTypes = $this->publicationService->getAllResearchTypes($this->organisation_id);
            $researchTypeDetail = $this->publicationService->getDetails($id, $table_name = 'research_type');
            if ($this->organisation_id == 1) {
                $grant_category = 'University Grant';
            } else {
                $grant_category = 'College Grant';
            }
            $message = NULL;

            $request = $this->getRequest();
            if ($request->isPost()) {
                $form->setData($request->getPost());
                if ($form->isValid()) {
                    try {
                        $this->publicationService->saveResearchType($publicationModel);
                        $this->flashMessenger()->addMessage('Research Type was successfully edited');
                        $this->auditTrailService->saveAuditTrail("UPDATE", "Research Type", "ALL", "SUCCESS");
                        return $this->redirect()->toRoute('researchtype');
                    } catch (\Exception $e) {
                        die($e->getMessage());
                        // Some DB Error happened, log it and let the user know
                    }
                }
            }

            return array(
                'form' => $form,
                'employee_details_id' => $this->employee_details_id,
                'organisation_id' => $this->organisation_id,
                'grant_category' => $grant_category,
                'researchTypes' => $researchTypes,
                'message' => $message,
                'researchTypeDetail' => $researchTypeDetail);
        }else{
            return $this->redirect()->toRoute('researchtype');
        }
    }

    public function applyResearchPublicationAction() 
    {
        $this->loginDetails();

        $form = new ResearchPublicationForm();
        $publicationModel = new ResearchPublication();
        $form->bind($publicationModel);

        $publicationList = $this->publicationService->listSelectData($tableName = 'research_publication_types', $columnName = 'publication_name', date('Y-m-d'), $this->organisation_id);

        $empDetails = $this->publicationService->findEmpDetails($this->username);
        $empDetails = $empDetails->toArray();

        $message = NULL;

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            $data = array_merge_recursive(
                    $request->getPost()->toArray(), $request->getFiles()->toArray()
            );
            $form->setData($data);
            if ($form->isValid()) {
                $data = $form->getData();
                try {
                    $this->publicationService->save($publicationModel);
                    $this->auditTrailService->saveAuditTrail('INSERT', 'Research Publication', 'ALL', 'SUCCESS');
                    $this->flashMessenger()->addMessage('Research Publication was successfully added');
                    return $this->redirect()->toRoute('applypublication');
                } catch (\Exception $e) {
                    die($e->getMessage());
                    // Some DB Error happened, log it and let the user know
                }
            }
        }

        return array(
            'form' => $form,
            'employee_details_id' => $this->employee_details_id,
            'empDetails' => $empDetails,
            'publicationList' => $publicationList,
            'message' => $message);
    }


    public function researchPublicationStatusAction()
    {
        $this->loginDetails();

        //$form = new SearchForm();

        $message = NULL;

        $researchPublication = $this->publicationService->getResearchPublicationList($this->employee_details_id);

        return new ViewModel(array(
            'researchPublication' => $researchPublication,
            'message' => $message,
            'keyphrase' => $this->keyphrase
        ));
    }


    public function viewCollegePublicationAction() 
    {
        $this->loginDetails();

        $publications = $this->publicationService->getPublicationList($type = 'College Publication');

        $message = NULL;

        return array(
            'form' => $form,
            'publications' => $publications,
            'message' => $message,
            'keyphrase' => $this->keyphrase,
        );
    }

    public function viewCollegePublicationDetailAction() 
    {
        $this->loginDetails();

        //get the publication id
         //get the university id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $form = new ResearchRecommendationForm();
            $publicationModel = new ResearchRecommendation();
            $form->bind($publicationModel);

            $publicationDetail = $this->publicationService->findPublication($id);

            $request = $this->getRequest();
            if ($request->isPost()) {
                $form->setData($request->getPost());
                if ($form->isValid()) {
                    try {
                        $this->publicationService->updateResearchPublication($publicationModel);
                         $this->auditTrailService->saveAuditTrail('UPDATE', 'Research Publication', 'Publication Status', 'SUCCESS');
                        $this->flashMessenger()->addMessage('Successfully updated the college publication status');
                        return $this->redirect()->toRoute('viewcollegepublication');
                    } catch (\Exception $e) {
                        die($e->getMessage());
                        // Some DB Error happened, log it and let the user know
                    }
                }
            }

            return array(
                'id' => $id,
                'form' => $form,
                'publicationDetail' => $publicationDetail);
        }else{
            return $this->redirect()->toRoute('viewcollegepublication');
        }
    }

    public function viewUniversityPublicationAction() 
    {
        $this->loginDetails();

        $publications = $this->publicationService->getPublicationList($type = 'University Publication');

        $message = NULL;

        return array(
            'form' => $form,
            'publications' => $publications,
            'keyphrase' => $this->keyphrase,
            'message' => $message,
        );
    }

    public function viewUniversityPublicationDetailAction() 
    {
        $this->loginDetails();
         //get the university id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $form = new ResearchRecommendationForm();
            $publicationModel = new ResearchRecommendation();
            $form->bind($publicationModel);

            $publicationDetail = $this->publicationService->findPublication($id);

            $request = $this->getRequest();
            if ($request->isPost()) {
                $form->setData($request->getPost());
                if ($form->isValid()) {
                    try {
                        $this->publicationService->updateResearchPublication($publicationModel);
                        $this->auditTrailService->saveAuditTrail('UPDATE', 'Research Publication', 'Publication Status', 'SUCCESS');
                        $this->flashMessenger()->addMessage('Successfully updated the university publication status');
                        return $this->redirect()->toRoute('viewuniversitypublication');
                    } catch (\Exception $e) {
                        die($e->getMessage());
                        // Some DB Error happened, log it and let the user know
                    }
                }
            }

            return array(
                'id' => $id,
                'form' => $form,
                'publicationDetail' => $publicationDetail,
            );
        }else{
            return $this->redirect()->toRoute('viewuniversitypublication');
        }
    }

    public function approveResearchPublicationAction() 
    {
        $this->loginDetails();

        $form = new ResearchPublicationForm();
        $publicationModel = new ResearchPublication();
        $form->bind($publicationModel);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                var_dump($form);
                die();
                try {
                    $this->publicationService->save($publicationModel);
                    $this->flashMessenger()->addMessage(' was successfully added');
                } catch (\Exception $e) {
                    die($e->getMessage());
                    // Some DB Error happened, log it and let the user know
                }
            }
        }

        return array('form' => $form);
    }

    public function editResearchPublicationAction() 
    {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){ 
            $form = new ResearchPublicationForm();
            $publicationModel = new ResearchPublication();
            $form->bind($publicationModel);

            $publicationDetail = $this->publicationService->findPublication($id);

            $publicationList = $this->publicationService->listSelectData($tableName = 'research_publication_types', $columnName = 'publication_name', date('Y-m-d'), $this->organisation_id);

            $empDetails = $this->publicationService->findEmpDetails($this->username);
            $empDetails = $empDetails->toArray();

            $request = $this->getRequest(); 
            if ($request->isPost()) {
                $form->setData($request->getPost());
                $data = array_merge_recursive(
                    $request->getPost()->toArray(), $request->getFiles()->toArray()
                );
                $form->setData($data);
                if ($form->isValid()) {
                    try {
                        $this->publicationService->save($publicationModel);
                        $this->auditTrailService->saveAuditTrail('UPDATE', 'Research Publication', 'ALL', 'SUCCESS');
                        $this->flashMessenger()->addMessage('Successfully edited the research publication');
                        return $this->redirect()->toRoute('researchpublicationstatus');
                    } catch (\Exception $e) {
                        die($e->getMessage());
                        // Some DB Error happened, log it and let the user know
                    }
                }
            }

            return array(
                'id' => $id,
                'form' => $form,
                'publicationList' => $publicationList,
                'empDetails' => $empDetails,
                'publicationDetail' => $publicationDetail,
            );
        }else{
            return $this->redirect()->toRoute('researchpublicationstatus');
        }
    }

    public function searchResearchPublicationAction() 
    {
        $this->loginDetails();

        $form = new ResearchPublicationForm();
        $publicationModel = new ResearchPublication();
        $form->bind($publicationModel);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                var_dump($form);
                die();
                try {
                    $this->publicationService->save($publicationModel);
                } catch (\Exception $e) {
                    die($e->getMessage());
                    // Some DB Error happened, log it and let the user know
                }
            }
        }

        return array('form' => $form);
    }

    public function requestPublicationAction() 
    {
        $this->loginDetails();

        $form = new ResearchAnnouncementForm();
        $publicationModel = new ResearchAnnouncement();
        $form->bind($publicationModel);

        $publicationList = $this->publicationService->listSelectData($tableName = 'research_publication_types', $columnName = 'publication_name', $date = NULL, $this->organisation_id);
        $publicationAnnouncement = $this->publicationService->getResearchPublicationAnnouncement($id = NULL, $this->organisation_id);

        $message = NULL;

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $data = $this->getRequest()->getPost('researchannouncement');
                $research_publication_type = $data['research_publication_type'];
                $start_date = $data['start_date'];
                $end_date = $data['end_date'];
                try { 
                    $this->publicationService->saveResearchAnnouncement($publicationModel);
                    //$this->sendPublicationAnnoucementEmail($research_publication_type, $start_date, $end_date);
                    $this->flashMessenger()->addMessage('Publication Dates was successfully added');
                    $this->auditTrailService->saveAuditTrail('INSERT', 'Research Publication Announcement', 'ALL', 'SUCCESS');
                    return $this->redirect()->toRoute('requestpublication');
                } catch (\Exception $e) {
                    die($e->getMessage());
                    // Some DB Error happened, log it and let the user know
                }
            }
        }

        return array(
            'publicationList' => $publicationList,
            'form' => $form,
            'organisation_id' => $this->organisation_id,
            'publicationAnnouncement' => $publicationAnnouncement,
            'message' => $message,
            'keyphrase' => $this->keyphrase,
        );
    }


    public function editRequestPublicationAction()
    {
        $this->loginDetails();

        //get the university id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){ 
            $form = new ResearchAnnouncementForm();
            $publicationModel = new ResearchAnnouncement();
            $form->bind($publicationModel);

            $publicationList = $this->publicationService->listSelectData($tableName = 'research_publication_types', $columnName = 'publication_name', $date = NULL, $this->organisation_id);
            $announcementDetail = $this->publicationService->getResearchPublicationAnnouncement($id, $this->organisation_id);
            $publicationAnnouncement = $this->publicationService->getResearchPublicationAnnouncement($id = NULL, $this->organisation_id);

            $message = NULL;

            $request = $this->getRequest();
            if ($request->isPost()) {
                $form->setData($request->getPost());
                if ($form->isValid()) {
                    try {
                        $this->publicationService->saveResearchAnnouncement($publicationModel);
                        $this->flashMessenger()->addMessage('Publication Dates was successfully updated');
                        $this->auditTrailService->saveAuditTrail('UPDATE', 'Research Publication Announcement', 'ALL', 'SUCCESS');
                        return $this->redirect()->toRoute('requestpublication');
                    } catch (\Exception $e) {
                        die($e->getMessage());
                        // Some DB Error happened, log it and let the user know
                    }
                }
            }

            return array(
                'id' => $id,
                'publicationList' => $publicationList,
                'form' => $form,
                'organisation_id' => $this->organisation_id,
                'announcementDetail' => $announcementDetail,
                'publicationAnnouncement' => $publicationAnnouncement,
                'message' => $message,
                'keyphrase' => $this->keyphrase,
            );
        }else{
            return $this->redirect()->toRoute('requestpublication');
        }
    }


    public function sendPublicationAnnoucementEmail($research_publication_type, $start_date, $end_date)
    {
        $this->loginDetails();

        $f_date = explode("/", $start_date);
        $from_date = $f_date[2]."-".$f_date[0]."-".$f_date[1];
        
        $t_date = explode("/", $end_date);
        $to_date = $t_date[2]."-".$t_date[0]."-".$t_date[1];
 

        $publication_type = $this->publicationService->getResearchPublicationDetail($type = 'Publication Type', $research_publication_type);

        $organisation_name = $this->publicationService->getResearchPublicationDetail($type = 'organisation', $this->organisation_id);
        
        $toEmail = "mendrelg@gmail.com";
        $messageTitle = "New Research Publication Annoucement";
        $messageBody = "Dear Sir/Madam,<br><b>".$organisation_name."</b> have annouced for grant type <b>".$publication_type."</b> from ".$from_date." to ".$to_date." on ".date('Y-m-d').".<br><b>Please click the link below for necessary action.</b><br><u>http://rub-ims.rub.edu.bt</u><p>This is an auto-generated email and Please do not reply because this mail id is not monitored</p>";

        $this->emailService->sendMailer($toEmail, $messageTitle, $messageBody); 
    }



    public function announceSeminarAction()
    {
        $this->loginDetails();

        $form = new SeminarAnnouncementForm();
        $publicationModel = new SeminarAnnouncement();
        $form->bind($publicationModel);

        $countryList = $this->publicationService->listSelectData($tableName = "country", $columnName = "country", NULL, NULL);
        $fundingTypes = $this->publicationService->listSelectData($tableName = "funding_category", $columnName = "funding_type", NULL, NULL);
        $message = NULL;

        $seminarAnnouncementList = $this->publicationService->getSemiarAnnouncementList($this->organisation_id);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) { 
                $data = $this->getRequest()->getPost('seminarannouncement');
                $seminar_title = $data['seminar_title'];
                $seminar_location = $data['seminar_location'];
                $seminar_start_date = $data['seminar_start_date'];
                $seminar_end_date = $data['seminar_end_date'];
                try {
                    $this->publicationService->saveSeminarAnnouncement($publicationModel);
                    $this->sendSeminarAnnouncementEmail($this->organisation_id, $seminar_title, $seminar_location, $seminar_start_date, $seminar_end_date);
                    $this->flashMessenger()->addMessage('Seminar Annoucement was successfully added');
                    $this->auditTrailService->saveAuditTrail("INSERT", "Research Seminar Details", "ALL", "SUCCESS");
                    return $this->redirect()->toRoute('announceseminar');
                } catch (\Exception $e) {
                    die($e->getMessage());
                    // Some DB Error happened, log it and let the user know
                }
            }
        }
        return array(
            'form' => $form,
            'countryList' => $countryList,
            'fundingTypes' => $fundingTypes,
            'employee_details_id' => $this->employee_details_id,
            'organisation_id' => $this->organisation_id,
            'seminarAnnouncementList' => $seminarAnnouncementList,
            'keyphrase' => $this->keyphrase,
            'message' => $message,
        );
    }


    public function sendSeminarAnnouncementEmail($organisation_id, $seminar_title, $seminar_location, $seminar_start_date, $seminar_end_date)
    {
        $this->loginDetails();

        $f_date = explode("/", $seminar_start_date);
        $seminar_start_date = $f_date[2]."-".$f_date[0]."-".$f_date[1];
        
        $t_date = explode("/", $seminar_end_date);
        $seminar_end_date = $t_date[2]."-".$t_date[0]."-".$t_date[1];

        $organisation_name = $this->publicationService->getResearchPublicationDetail($type = 'organisation', $organisation_id);
        
        $toEmail = "mendrelg@gmail.com";
        $messageTitle = "New Research Seminar Annoucement";
        $messageBody = "Dear Sir/Madam,<br><b>".$organisation_name."</b> have annouced for seminar meet of title: <b>".$seminar_title."</b> from ".$seminar_start_date." to ".$seminar_end_date." on ".date('Y-m-d'). "at <b>".$seminar_location."<b>.<br><b>Please click the link below for necessary action.</b><br><u>http://rub-ims.rub.edu.bt</u><p>This is an auto-generated email and Please do not reply because this mail id is not monitored</p>";

        $this->emailService->sendMailer($toEmail, $messageTitle, $messageBody); 
    }


    public function editSeminarAnnouncementAction()
    {
        $this->loginDetails();
        //get the publication type id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){ 
            $form = new SeminarAnnouncementForm();
            $publicationModel = new SeminarAnnouncement();
            $form->bind($publicationModel);

            $countryList = $this->publicationService->listSelectData($tableName = "country", $columnName = "country", NULL, NULL);
            $fundingTypes = $this->publicationService->listSelectData($tableName = "funding_category", $columnName = "funding_type", NULL, NULL);
            $message = NULL;
            $seminarAnnouncementDetails = $this->publicationService->getSeminarAnnouncementDetails($id);
            $seminarAnnouncementList = $this->publicationService->getSemiarAnnouncementList($this->organisation_id);

            $request = $this->getRequest();
            if ($request->isPost()) {
                $form->setData($request->getPost());
                if ($form->isValid()) { 
                    try {
                        $this->publicationService->saveSeminarAnnouncement($publicationModel);
                        $this->flashMessenger()->addMessage('Seminar Annoucement was successfully updated');
                        $this->auditTrailService->saveAuditTrail("UPDATE", "Research Seminar Details", "ALL", "SUCCESS");
                        return $this->redirect()->toRoute('announceseminar');
                    } catch (\Exception $e) {
                        die($e->getMessage());
                        // Some DB Error happened, log it and let the user know
                    }
                }
            }
            return array(
                'id' => $id,
                'form' => $form,
                'countryList' => $countryList,
                'fundingTypes' => $fundingTypes,
                'employee_details_id' => $this->employee_details_id,
                'organisation_id' => $this->organisation_id,
                'seminarAnnouncementDetails' => $seminarAnnouncementDetails,
                'seminarAnnouncementList' => $seminarAnnouncementList,
                'keyphrase' => $this->keyphrase,
                'message' => $message,
            );
        }else{
            return $this->redirect()->toRoute('announceseminar');
        }
    }



    public function downloadPublicationAction() 
    {
        $this->loginDetails();

        //get the university id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $file = $this->publicationService->getFileName($id);

            $mimetype = mime_content_type($file);
            $response = new Stream();
            $response->setStream(fopen($file, 'r'));
            $response->setStatusCode(200);
            $response->setStreamName(basename($file));
            $headers = new Headers();
            $headers->addHeaderLine('Content-Disposition:inline','attachment; filename="' . basename($file) .'"')
                    ->addHeaderLine('Content-Type', $mimetype)
                    ->addHeaderLine('Content-Length',filesize($file))
                    ->addHeaderLine('Expires','@0') // @0, because zf2 parses date as string to \DateTime() object
                    ->addHeaderLine('Cache-Control','must-revalidate')
                    ->addHeaderLine('Pragma','public')
                    ->addHeaderLine('Content-Transfer-Encoding:binary')
                    ->addHeaderLine('Accept-Ranges:bytes');
            
            $response->setHeaders($headers);
            return $response;
        }else{
            return $this->redirect()->toRoute('researchpublicationstatus');
        }
    }

    public function addPublicationTypeAction()
    {
        $this->loginDetails();

        $form = new PublicationTypeForm();
        $publicationModel = new PublicationType();
        $form->bind($publicationModel);

        $publicationList = $this->publicationService->listAll($tableName = 'research_publication_types', $this->organisation_id);
        if ($this->organisation_id == 1) {
            $publication_type = 'University Publication';
        } else {
            $publication_type = 'College Publication';
        }

        $message = NULL;

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    $this->publicationService->savePublicationType($publicationModel);
                    $this->flashMessenger()->addMessage('Publication Type was successfully added');
                    $this->auditTrailService->saveAuditTrail("INSERT", "Research Publication Types", "ALL", "SUCCESS");
                    return $this->redirect()->toRoute('addpublicationtype');
                } catch (\Exception $e) {
                    die($e->getMessage());
                    // Some DB Error happened, log it and let the user know
                }
            }
        }

        return array(
            'form' => $form,
            'publication_type' => $publication_type,
            'publicationList' => $publicationList,
            'organisation_id' => $this->organisation_id,
            'keyphrase' => $this->keyphrase,
            'message' => $message);
    }

    public function editPublicationTypeAction() 
    {
        $this->loginDetails();
        //get the publication type id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $form = new PublicationTypeForm();
            $publicationModel = new PublicationType();
            $form->bind($publicationModel);

            $publicationList = $this->publicationService->listAll($tableName = 'research_publication_types', $this->organisation_id);
            $publicationDetails = $this->publicationService->getDetails($id, $table_name = 'research_publication_types');
            if ($this->organisation_id == 1) {
                $publication_type = 'University Publication';
            } else {
                $publication_type = 'College Publication';
            }

            $request = $this->getRequest();
            if ($request->isPost()) {
                $form->setData($request->getPost());
                if ($form->isValid()) {
                    try {
                        $this->publicationService->savePublicationType($publicationModel);
                        $this->flashMessenger()->addMessage('Publication Type was successfully edited');
                        $this->auditTrailService->saveAuditTrail("UPDATE", "Research Publication Types", "ALL", "SUCCESS");
                        return $this->redirect()->toRoute('addpublicationtype');
                    } catch (\Exception $e) {
                        die($e->getMessage());
                        // Some DB Error happened, log it and let the user know
                    }
                }
            }

            return array(
                'form' => $form,
                'publication_type' => $publication_type,
                'publicationList' => $publicationList,
                'organisation_id' => $this->organisation_id,
                'publicationDetails' => $publicationDetails);
        }else{
            return $this->redirect()->toRoute('addpublicationtype');
        }
    }

    public function listPublicationTypeAction()
     {
        $this->loginDetails();
        
        $form = new PublicationTypeForm();
        $publicationModel = new PublicationType();
        $form->bind($publicationModel);

        $publicationList = $this->publicationService->listAll($tableName = 'research_publication_types', $this->organisation_id);
        $message = NULL;

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    $this->publicationService->savePublicationType($publicationModel);
                    return $this->redirect()->toRoute('listpublicationtype');
                } catch (\Exception $e) {
                    die($e->getMessage());
                    // Some DB Error happened, log it and let the user know
                }
            }
        }

        return array(
            'form' => $form,
            'publicationList' => $publicationList,
            'organisation_id' => $this->organisation_id,
            'keyphrase' => $this->keyphrase,
            'message' => $message);
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

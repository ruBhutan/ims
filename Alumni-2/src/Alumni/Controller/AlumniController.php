<?php

namespace Alumni\Controller;

use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Alumni\Service\alumniServiceInterface;
use Alumni\Model\Alumni;
use Alumni\Model\AlumniStudent;
use Alumni\Model\AlumniRegistration;
use Alumni\Model\AlumniEvent;
use Alumni\Model\AlumniProfile;
use Alumni\Model\UpdateAlumni;
use Alumni\Model\AlumniResource;
use Alumni\Model\AlumniEnquiry;
use Alumni\Model\AlumniFaqs;
use Alumni\Model\AlumniContribution;
use Alumni\Model\AlumniSubscriptionDetails;
use Alumni\Model\AlumniSubscriberDetails;
use Alumni\Model\UpdateAlumniSubscriberDetails;
use Alumni\Model\AlumniSubscription;

use Alumni\Form\AlumniNewRegistrationForm;
use Alumni\Form\RegisteredMemberSearchForm;
use Alumni\Form\AlumniApprovalForm;
use Alumni\Form\CreateAlumniEventForm;
use Alumni\Form\AlumniProfileForm;
use Alumni\Form\UpdateAlumniForm;
use Alumni\Form\CreateAlumniResourceForm;
use Alumni\Form\CreateAlumniEnquiryForm;
use Alumni\Form\CreateAlumniFaqsForm;
use Alumni\Form\AlumniContributionForm;
use Alumni\Form\AlumniSubscriptionDetailsForm;
use Alumni\Form\AlumniSubscriberDetailsForm;
use Alumni\Form\UpdateAlumniSubscriberDetailsForm;
use Alumni\Form\AlumniSubscriptionForm;
use Alumni\Form\AlumniSearchForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Http\Response\Stream;
use Zend\Http\Headers;
use Zend\Paginator\Adapter\DbSelect;
use Zend\View\Model\JsonModel;
use Zend\Form\Element\Select;
 
  
class AlumniController extends AbstractActionController
{
    protected $alumniService;
    protected $notificationService;
    protected $auditTrailService;
    protected $serviceLocator;
    protected $emailService;
    protected $username;
    protected $userrole;
    protected $userregion;
    protected $usertype;
    protected $userDetails;
    protected $userImage;
    protected $employee_details_id;
    protected $alumni_id;
    protected $organisation_id;

    protected $keyphrase = "RUB_IMS";
	
	public function __construct(AlumniServiceInterface $alumniService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->alumniService = $alumniService;
        $this->notificationService = $notificationService;
        $this->auditTrailService = $auditTrailService;
        $this->serviceLocator = $serviceLocator;
        $this->emailService = $serviceLocator->get('Application\Service\EmailService');

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
        * Getting the student_id/employee_details_id related to username
        */
        
       
		  
		$empData = $this->alumniService->getEmployeeDetailsId($this->username);
        foreach($empData as $emp){
            $this->employee_details_id = $emp['id'];
        }

        $alumniData = $this->alumniService->getAlumniDetailsId($this->username);
        foreach($alumniData as $alumni){
            $this->alumni_id = $alumni['id'];
        }

        //get the organisation id
        $organisationID = $this->alumniService->getOrganisationId($this->username, $this->usertype);
        foreach($organisationID as $organisation){
        $this->organisation_id = $organisation['organisation_id'];
		
       }

        $this->userDetails = $this->alumniService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->alumniService->getUserImage($this->username, $this->usertype);
	}

    public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }

	//To add Item Category Action

	public function alumniNewRegistrationAction()
    {
        $this->loginDetails();

        $form = new AlumniNewRegistrationForm();
        $alumniModel = new AlumniRegistration();
        $form->bind($alumniModel);

        $programmeName = $this->alumniService->listSelectData($tableName = 'alumni_programmes', $columnName = 'programme_name', $this->organisation_id);

        $qualificationLevel = $this->alumniService->listSelectData($tableName = 'study_level', $columnName = 'study_level', NULL);

        $gender = $this->alumniService->listSelectData($tableName = 'gender', $columnName = 'gender', NULL);

        $presentYear = date('Y');
        $graduationYear = array();
        $enrollmentYear = array();

        //Graduation Year
        for($i=0; $i<50; $i++){
            $graduationYear[$presentYear-$i] = $presentYear-$i;
        }

        //enrollment year
        for($j=0; $j<50; $j++){
             $enrollmentYear[$presentYear-$j] = $presentYear-$j;
        }

        $message = NULL;
       
         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                $data = $this->getRequest()->getPost('alumninewregistration');
                $enrollment_year = $data['enrollment_year']; 
                $graduation_year = $data['graduation_year'];

                if($enrollment_year >= $graduation_year){
                    $message = 'Failure';
                    $this->flashMessenger()->addMessage("Invalid graduation and enrollment year. Your enrollment year should not be equal or greater than graduation year!");
                }			                
                else{
                    try {
                         $this->alumniService->saveAlumniNewRegistered($alumniModel);
                         $this->auditTrailService->saveAuditTrail("INSERT", "Past Student", "ALL", "SUCCESS");
                         $this->auditTrailService->saveAuditTrail("INSERT", "Alumni", "ALL", "SUCCESS");
                         $this->flashMessenger()->addMessage('Past student was successfully added');
                         return $this->redirect()->toRoute('alumnimemberlist');
                     }
                     catch(\Exception $e) {
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                     }

                }
             }
         }
           return array(
             'form' => $form,
             'programmeName' => $programmeName,
             'qualificationLevel' => $qualificationLevel,
             'enrollmentYear' => $enrollmentYear,
             'graduationYear' => $graduationYear,
             'gender' => $gender,
             'organisation_id' => $this->organisation_id,
             'message' => $message,

         );
    }

    /*public function updateAlumniAction()
    {
        $form = new UpdateAlumniForm();
        $alumniModel = new UpdateAlumni();
        $form->bind($alumniModel);

        $tableName = 'alumni_programmes';
        $columnName = 'programme_name';
        $programmeName = $this->alumniService->listSelectData($tableName, $columnName);
       
         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
                     $this->alumniService->saveUpdatedAlumni($alumniModel);
                     
                   return $this->redirect()->toRoute('update-alumni');
                 }
                 catch(\Exception $e) {
                         die($e->getMessage());
                         // Some DB Error happened, log it and let the user know
                 }
             }
         }
           return array(
             'form' => $form,
             'programmeName' => $programmeName,
         );
    }

     public function alumniListAction()
    {
        return new ViewModel(array(
            'approvals' => $this->alumniService->listAllAlumni(),
            ));
    } 
    
      public function alumniAction()
    {
         $id = (int) $this->params()->fromRoute('id', 0);

        $approvals = $this->alumniService->findAlumni($id);
        
        $form = new AlumniForm();
        $alumniModel = new Alumni();
        $form->bind($alumniModel);

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
                try{
                    $this->alumniService->saveAlumni($alumniModel);

                    return $this->redirect()->toRoute('alumni-list');
                }
                catch(\Exception $e){
                    die($e->getMessage());
                    //Some DB Error happened, log it and let the user know  
                }
            
            }
        }

        return array(
    'form' => $form,
   'approvals' => $approvals,
    );
   }*/

    public function alumniMemberListAction()
    {
        $this->loginDetails();

        $alumniMemberList = array();
        $alumniProgramme = NULL;
        $alumniBatch = NULL;
        $alumniName = NULL;
        
        $form = new AlumniSearchForm(); 

        $alumniMemberList = $this->alumniService->listAllAlumniNewRegistered($this->organisation_id);

        $alumniProgramme = $this->alumniService->listSelectData1($tableName = 'alumni_programmes', $columnName = 'programme_name', $this->organisation_id);
        $alumniBatch = $this->alumniService->listSelectData1($tableName = 'alumni', $columnName = 'graduation_year', $this->organisation_id);

        $message = NULL;

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){ 
                $alumniProgramme = $this->getRequest()->getPost('alumni_programme');
                $alumniBatch = $this->getRequest()->getPost('alumni_batch');
                $alumniName = $this->getRequest()->getPost('alumni_name');
                try{
                    $alumniMemberList = $this->alumniService->getAlumniMemberList($alumniProgramme, $alumniBatch, $alumniName, $this->organisation_id);
                }catch(\Exception $e) {
                         die($e->getMessage());
                         // Some DB Error happened, log it and let the user know
                 }                 
            }
        }

        return new ViewModel(array(
            'form' => $form,
            'alumniMemberList' => $alumniMemberList,
            'alumniProgramme' => $alumniProgramme,
            'alumniBatch' => $alumniBatch,
            'alumniName' => $alumniName,
            'keyphrase' => $this->keyphrase,
            'message' => $message,
            ));
    } 

     public function alumniProfileAction()
     {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){ echo $id; die();
                $details = $this->alumniService->findAlumniProfile($id); 
        
                $form = new AlumniProfileForm();
                $alumniModel = new AlumniProfile();
                $form->bind($alumniModel);

                $request = $this->getRequest();
                if($request->isPost()){
                    
                    $form->setData($request->getPost());
                    if($form->isValid()){
                        try{
                            $this->alumniService->saveAlumniProfile($alumniModel);

                            return $this->redirect()->toRoute('alumnimemberlist');
                        }
                        catch(\Exception $e){
                            die($e->getMessage());
                            //Some DB Error happened, log it and let the user know  
                        }
                    
                    }
                }

                return array(
                    'form' => $form,
                    'details' => $details,
                    'employee_details_id' => $this->employee_details_id,
                    'alumni_id' => $this->alumni_id,
                    'organisation_id' => $this->organisation_id,
            );
        }else{
            return $this->redirect()->toRoute('alumnimemberlist');
        }


        

       // return new ViewModel(array(
         //   'members' => $this->alumniService->AlumniProfileDetails($id);
         //   ));
   }
   
   
   /*Display basic  registration  details done from OVC  */
    /* public function registeredMemberListAction()
    {
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');

        $form = new RegisteredMemberSearchForm($dbAdapter);

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){

                //$stdOrganisation = $this->getRequest()->getPost('organisation');
                $memProgramme = $this->getRequest()->getPost('programme');
                $memYear = $this->getRequest()->getPost('graduation_year');
				$memName = $this->getRequest()->getPost('name');
                $registeredMemberList = $this->alumniService->getRegisteredMemberList($memProgramme, $memYear, $memName);
            }
        }

        else {
            $registeredMemberList = array();
        }

        return new ViewModel(array(
            'form' => $form,
            'registeredMemberList' => $registeredMemberList,
            
            ));
    } */


    public function createAlumniEventAction()
    {
        $this->loginDetails();

        $form = new CreateAlumniEventForm();
        $alumniModel = new AlumniEvent();
        $form->bind($alumniModel);

        $tableName = 'alumni_programmes';
        $columnName = 'programme_name';
        $programmeName = $this->alumniService->listSelectData1($tableName, $columnName,$this->organisation_id);

        $presentYear = date('Y');
        $graduationYear = array();

        //Graduation Year
        for($i=0; $i<50; $i++){
            $graduationYear['All'] = 'All';
            $graduationYear[$presentYear-$i] = $presentYear-$i;
        }

        $message = NULL;

        $email = array();
       
         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                $data = $this->getRequest()->getPost('alumnievent');
                $event_name = $data['event_name'];
                $content = $data['content'];
                $batch = $data['batch'];
                $programme = $data['alumni_programmes_id'];
                $organisation = $data['organisation_id'];
                $from_date = $data['from_date'];
                $to_date = $data['to_date'];

                if($from_date > $to_date){
                    $message = 'Failure';
                    $this->flashMessenger()->addMessage("Invalid from date since from date should not be greater than to date. Please enter again!");
                }else{
                    try { 
                        
                         $this->alumniService->saveAlumniEvent($alumniModel);
                         $this->sendCreatedEvent($event_name, $content, $batch, $programme, $organisation, $from_date, $to_date);
                         $this->auditTrailService->saveAuditTrail("INSERT", "Past Student", "ALL", "SUCCESS");
                         $this->flashMessenger()->addMessage("You have successfully created and posted an event");
                         return $this->redirect()->toRoute('createalumnievent');
                     }
                     catch(\Exception $e) {
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                             // Some DB Error happened, log it and let the user know
                     }
                }
             }
         }
           return array(
             'form' => $form,
             'programmeName' => $programmeName,
             'graduationYear' => $graduationYear,
			 'events' => $this->alumniService->listAllAlumniEvent($this->organisation_id),
			 'organisation_id' => $this->organisation_id,
             'message' => $message,
             'keyphrase' => $this->keyphrase,
			 
         );
    }

     public function viewAlumniEventListAction()
    {
        $this->loginDetails();

        return new ViewModel(array(
            'events' => $this->alumniService->listAllAlumniEvent($this->organisation_id)
            ));
    }

// To send email about event based on the batch and programme
    public function sendCreatedEvent($event_name, $content, $batch, $programme, $organisation, $from_date, $to_date)
    {
        $this->loginDetails();

        $emails = $this->alumniService->getEventEmailList($batch, $programme, $organisation);

        foreach($emails as $email){
            $toEmail = $email;
            $messageTitle = $event_name;
            $messageBody = "<h2>Dear all Alumni,<br>There will be ".$event_name." from ".$from_date." To ".$to_date.".</h2><br>"."<b>More Details: </b> <br>".$content;
            $this->emailService->sendMailer($toEmail, $messageTitle, $messageBody);
        }
    }


    public function addAlumniContributionDetailAction()
    {
        $this->loginDetails(); 

        $form = new AlumniContributionForm();
        $alumniModel = new AlumniContribution();
        $form->bind($alumniModel);

        $message = NULL;
       
         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                $data = $this->getRequest()->getPost('alumnicontribution');
                $contribution_details = $data['contribution_details'];
                $contributed_by = $data['contributed_by'];
                $contributed_date = $data['contributed_date'];
                $remarks = $data['remarks'];
                $organisation = $data['organisation_id'];
                    try { 
                        
                         $this->alumniService->saveAlumniContribution($alumniModel);
                         $this->sendContributionDetails($contribution_details, $contributed_by, $contributed_date, $remarks, $organisation);
                         $this->auditTrailService->saveAuditTrail("INSERT", "Alumni Contribution Details", "ALL", "SUCCESS");
                         $this->flashMessenger()->addMessage("You have successfully added alumni contribution details");
                         return $this->redirect()->toRoute('addalumnicontributiondetail');
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
             'contributions' => $this->alumniService->listAllContributionDetails($this->organisation_id),
             'organisation_id' => $this->organisation_id,
             'message' => $message,
             'keyphrase' => $this->keyphrase,
             
         );
    }

// To send email to the particular organisation alumni about the contribution
    public function sendContributionDetails($contribution_details, $contributed_by, $contributed_date, $remarks, $organisation)
    {
        $this->loginDetails();

        $emails = $this->alumniService->getAlumniContributionEmailList($organisation);

        foreach($emails as $email){
            $toEmail = $email;
            $messageTitle = $contribution_details;
            $messageBody = "<h2>Dear all Alumni,<br> ".$contributed_by." Contributed ".'"'.$contribution_details.'"'." on ".$contributed_date.".</h2><br>"."<b>Our Remarks: </b> <br>".$remarks;
            $this->emailService->sendMailer($toEmail, $messageTitle, $messageBody);
        }
    }
	
	
	public function createAlumniResourceAction()
    {
        $this->loginDetails();

        $form = new CreateAlumniResourceForm();
        $alumniModel = new AlumniResource();
        $form->bind($alumniModel);

        $message = NULL;
              
         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
                     $this->alumniService->saveAlumniResource($alumniModel);
                     $this->auditTrailService->saveAuditTrail("INSERT", "Alumni Resource", "ALL", "SUCCESS");
                     $this->flashMessenger()->addMessage("You have successfully added alumni resource");    
                   return $this->redirect()->toRoute('createalumniresource');
                 }
                 catch(\Exception $e) {
                    $message = 'Failure';
                    $this->flashMessenger()->addMessage($e->getMessage());
                 }
             }
         }
           return array(
             'form' => $form,
			 'resource' => $this->alumniService->listAllAlumniResource($this->organisation_id),
             //'programmeName' => $programmeName,
             //'graduationYear' => $graduationYear,
             'message' => $message,
             'keyphrase' => $this->keyphrase,
			 'organisation_id' => $this->organisation_id,
         );
    }

     public function viewAlumniResourceListAction()
    {
        $this->loginDetails(); 

        return new ViewModel(array(
            'resource' => $this->alumniService->listAllAlumniResource($this->organisation_id),
             'check_subscribtion' => $this->alumniService->checkAlumniSubscription($this->alumni_id),
            'alumni_id' => $this->alumni_id,
            'employee_details_id' => $this->employee_details_id,
            ));
    } 
	
	
	public function createAlumniEnquiryAction()
    {
        $this->loginDetails(); 

        $form = new CreateAlumniEnquiryForm();
        $alumniModel = new AlumniEnquiry();
        $form->bind($alumniModel);

        $message = NULL;

         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
                     $this->alumniService->saveAlumniEnquiry($alumniModel);
                     $this->notificationService->saveNotification('New Alumni Enquiry', 'ALL', 'ALL', 'Alumni Enquiry');
                     $this->auditTrailService->saveAuditTrail("INSERT", "Alumni Academic Help", "ALL", "SUCCESS");
                   return $this->redirect()->toRoute('createalumnienquiry');
                 }
                 catch(\Exception $e) {
                    $message = NULL;
                    $this->flashMessenger()->addMessage($e->getMessage());
                         // Some DB Error happened, log it and let the user know
                 }
             }
         }
           return array(
             'form' => $form, 
             'message' => $message,
             'enquiry' => $this->alumniService->listAlumniEnquiry($this->alumni_id),
             'check_subscribtion' => $this->alumniService->checkAlumniSubscription($this->alumni_id),
             'alumni_id' => $this->alumni_id,
             'employee_details_id' => $this->employee_details_id,
			 'organisation_id' => $this->organisation_id,
         );
    }
	
    
	public function viewAlumniEnquiryListAction()
    {
        $this->loginDetails();

        $message = NULL;

        return new ViewModel(array(
            'enquirylist' => $this->alumniService->listAllAlumniEnquiry($this->organisation_id),
            'keyphrase' => $this->keyphrase,
            'message' => $message,
            ));
    }
	
	public function createAlumniFaqsAction()
    {
        $this->loginDetails();

        $form = new CreateAlumniFaqsForm();
        $alumniModel = new AlumniFaqs();
        $form->bind($alumniModel);

        $message = NULL;
              
         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {

                 try {
                     $this->alumniService->saveAlumniFaqs($alumniModel);
                     $this->auditTrailService->saveAuditTrail("INSERT", "Alumni Academic FAQs", "ALL", "SUCCESS");
                   $this->flashMessenger()->addMessage("You have successfully added alumni faqs");   
                   return $this->redirect()->toRoute('createalumnifaqs');
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
			 'faqslist' => $this->alumniService->listAllAlumniFaqs($this->organisation_id),
			 'message' => $message,
			 'organisation_id' => $this->organisation_id,
			 
         );
    }
	
	public function viewAlumniFaqsListAction()
    {
        $this->loginDetails();

        return new ViewModel(array(
            'faqslist' => $this->alumniService->listAllAlumniFaqs($this->organisation_id),
            'check_subscribtion' => $this->alumniService->checkAlumniSubscription($this->alumni_id),
            'alumni_id' => $this->alumni_id,
            'employee_details_id' => $this->employee_details_id,
            ));
    }


    

    public function approveAlumniEnquiryAction()
    {
        //get the id of the hrd proposal
         $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        $message = NULL;

        if(is_numeric($id)){
            try {
                $this->alumniService->updateAlumniEnquiry($status='Approved', $previousStatus=NULL, $id, $this->organisation_id);
                $this->auditTrailService->saveAuditTrail("INSERT", "Alumni Academic Help", "ALL", "SUCCESS");
                $this->flashMessenger()->addMessage("You have successfully approve alumni enquiry");   
                return $this->redirect()->toRoute('viewalumnienquirylist');
         }
         catch(\Exception $e) {
                 die($e->getMessage());
                 // Some DB Error happened, log it and let the user know
         }
         
        return array(
            'message' => $message,
        );
        }else{
            return $this->redirect()->toRoute('viewalumnienquirylist');
        }
    }
	
	/*public function listAllAlumniStudentAction()
    {
        return new ViewModel(array(
            'alumnistudentlist' => $this->alumniService->getAllAlumniStudent($this->organisation_id)
            ));
    }*/

    public function addAlumniSubscriptionDetailAction()
    {
        $this->loginDetails(); 

        $form = new AlumniSubscriptionDetailsForm();
        $alumniModel = new AlumniSubscriptionDetails();
        $form->bind($alumniModel);

        $message = NULL;
              
         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                $data = $this->getRequest()->getPost('alumnisubscriptiondetails');
                $subscription_details = $data['subscription_details'];

                $check_subscription_details = $this->alumniService->crossCheckSubscriptionDetails($subscription_details);
                if($check_subscription_details){
                    $message = 'Failure';
                    $this->flashMessenger()->addMessage("You have already added similar subscription details. Please try for another!.");
                }else{
                    try {
                        $this->alumniService->saveSubscriptionList($alumniModel);
                        $this->auditTrailService->saveAuditTrail("INSERT", "Alumni Subscription Lists", "ALL", "SUCCESS");
                        $this->flashMessenger()->addMessage("You have successfully added alumni subscription details");   
                        return $this->redirect()->toRoute('addalumnisubscriptiondetail');
                     }
                        catch(\Exception $e) {
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                         // Some DB Error happened, log it and let the user know
                    }
                }
             }
         }
           return array(
             'form' => $form, 
             'subscriptionList' => $this->alumniService->listAllAlumniSubscriptionList($this->organisation_id),
             'message' => $message,
             'organisation_id' => $this->organisation_id,
             'keyphrase' => $this->keyphrase,
             
         );
    }


    public function editAlumniSubscriptionDetailAction()
    {
        $this->loginDetails();
      //Get id from the route
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $form = new AlumniSubscriptionDetailsForm();
            $alumniModel = new AlumniSubscriptionDetails();
            $form->bind($alumniModel);

            $message = NULL;
                  
             $request = $this->getRequest();
             if ($request->isPost()) {
                 $form->setData($request->getPost());
                 if ($form->isValid()) {

                     try {
                         $this->alumniService->saveSubscriptionList($alumniModel);
                         $this->auditTrailService->saveAuditTrail("UPDATE", "Alumni Subscription Details", "ALL", "SUCCESS");
                       $this->flashMessenger()->addMessage("You have successfully edited alumni subscription details");   
                       return $this->redirect()->toRoute('addalumnisubscriptiondetail');
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
                 'subscriptionDetail' => $this->alumniService->getAlumniSubscriptionDetails($id),
                 'message' => $message,
                 'organisation_id' => $this->organisation_id,
                 'keyphrase' => $this->keyphrase,
                 
             );
           }
           else
           {
                return $this->redirect()->toRoute('addalumnisubscriptiondetail');
           }
    }


    public function addAlumniSubscriptionAction()
    {
        $this->loginDetails(); 

        $form = new AlumniSubscriptionForm();
        $alumniModel = new AlumniSubscription();
        $form->bind($alumniModel);

        $message = NULL;
              
         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                $data = $this->getRequest()->getPost('alumnisubscription');
                $subscription_type = $data['subscription_type'];

                $check_subscription_type = $this->alumniService->crossCheckSubscriptionType('Add', NULL, $subscription_type, $this->organisation_id);
                if($check_subscription_type){
                    $message = 'Failure';
                    $this->flashMessenger()->addMessage("You have already added similar subscription type. Please try for another and if you want to add more on similar subscription then please edit from the list!.");
                }else{
                    try {
                        $this->alumniService->saveSubscriptionDetails($alumniModel);
                        $this->auditTrailService->saveAuditTrail("INSERT", "Alumni Subscription Details", "ALL", "SUCCESS");
                        $this->flashMessenger()->addMessage("You have successfully added alumni subscription details");   
                        return $this->redirect()->toRoute('addalumnisubscription');
                     }
                        catch(\Exception $e) {
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                         // Some DB Error happened, log it and let the user know
                    }
                }
             }
         }
           return array(
             'form' => $form, 
             'subscriptionDetails' => $this->alumniService->listAlumniSubscriptionDetailList($this->organisation_id),
             'message' => $message,
             'organisation_id' => $this->organisation_id,
             'keyphrase' => $this->keyphrase,
             
         );
    }


    public function editAlumniSubscriptionAction()
    {
        $this->loginDetails();
      //Get id from the route
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){ 
            $form = new AlumniSubscriptionForm();
            $alumniModel = new AlumniSubscription();
            $form->bind($alumniModel);

            $message = NULL;
                  
             $request = $this->getRequest();
             if ($request->isPost()) {
                 $form->setData($request->getPost());
                 if ($form->isValid()) {
                    $data = $this->getRequest()->getPost('alumnisubscription');
                    $subscription_type = $data['subscription_type'];

                    $check_subscription_type = $this->alumniService->crossCheckSubscriptionType('Edit', $id, $subscription_type, $this->organisation_id);
                    if($check_subscription_type){
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage("You have already added similar subscription type. Please edit current type details only!.");
                     }else{

                     try {
                         $this->alumniService->saveSubscriptionDetails($alumniModel);
                         $this->auditTrailService->saveAuditTrail("UPDATE", "Alumni Subscription Details", "ALL", "SUCCESS");
                       $this->flashMessenger()->addMessage("You have successfully edited alumni subscription details");   
                       return $this->redirect()->toRoute('addalumnisubscription');
                     }
                     catch(\Exception $e) {
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                             // Some DB Error happened, log it and let the user know
                     }
                 }
             }
         }
               return array(
                 'form' => $form, 
                 'subscriptionDetail' => $this->alumniService->getAlumniSubscription($id),
                 'message' => $message,
                 'organisation_id' => $this->organisation_id,
                 'keyphrase' => $this->keyphrase,
                 
             );
           }
           else
           {
                return $this->redirect()->toRoute('addalumnisubscription');
           }
    }


    public function alumniSubscriptionListAction()
    {
        $this->loginDetails();

        $message = NULL;

           return array(
             //'form' => $form, 
             'subscriptionList' => $this->alumniService->getAlumniSubscriptionList($this->organisation_id),
             //'subscriberCheck' => $this->alumniService->checkRegisteredSubscriber($this->employee_details_id),
             //'message' => $message,
             'organisation_id' => $this->organisation_id,
             'alumni_id' => $this->alumni_id,
             'message' => $message,
             'keyphrase' => $this->keyphrase,
             
         );
    }


    public function applyAlumniSubscriptionAction()
    {
        $this->loginDetails(); 

        $form = new AlumniSubscriberDetailsForm($this->serviceLocator);
        $alumniModel = new AlumniSubscriberDetails();
        $form->bind($alumniModel);

        $message = NULL;

        $check_subscriber = $this->alumniService->checkRegisteredSubscriber($this->alumni_id);

        $subscriber_details = $this->alumniService->getAlumniSubscriberDetails($this->alumni_id);

        $subscriberDetails = array();

        foreach($subscriber_details as $details){
            $subscriberDetails['subscription_status'] = $details['subscription_status'];
            $subscriberDetails['subscriber_id'] = $details['subscriber_id'];
            $subscriberDetails['subscription_type'] = $details['subscription_type'];
            $subscriberDetails['updated_date'] = $details['updated_date'];
        }

        if($check_subscriber && $subscriberDetails['subscription_status'] == 'Pending'){
            $message = 'Failure';
            $this->flashMessenger()->addMessage("You have already registered for alumni subscription and it is still pending for approval!");
        }else if($check_subscriber && $subscriberDetails['subscription_status'] == 'Approved'){
            $message = 'Failure';
            $this->flashMessenger()->addMessage("You have already registered for alumni subscription and it has been approved on ".$subscriberDetails['updated_date'].". Your subscription ID is ".$subscriberDetails['subscriber_id']." and your subscription type is ".$subscriberDetails['subscription_type']);
        }else{
            $request = $this->getRequest();
                if ($request->isPost()){
                    $form->setData($request->getPost());
                    $subscription_type = $this->getRequest()->getPost('subscription_type');
                    $subscription_charge = $this->getRequest()->getPost('subscription_charge');
                    if ($form->isValid()){ 
                        try{
                            $this->alumniService->saveAlumniSubscription($alumniModel, $subscription_type, $subscription_charge);
                            $this->notificationService->saveNotification('New Alumni Subscription', 'ALL', 'ALL', 'Alumni Subscription');
                            $this->auditTrailService->saveAuditTrail("INSERT", "Alumni Subscriber Details", "ALL", "SUCCESS");
                            $this->flashMessenger()->addMessage("You have successfully registered for alumni subscription");
                            return $this->redirect()->toRoute('alumnisubscriptionlist');
                        }
                        catch(\Exception $e){
                            $message = 'Failure';
                            $this->flashMessenger()->addMessage($e->getMessage());
                        }
                    }
                }
            }

         return array(
            'form' => $form,
            'message' => $message,
            'alumni_id' => $this->alumni_id,
            'organisation_id' => $this->organisation_id,
            'subscriptionDetails' => $this->alumniService->getAlumniSubscribingDetails($this->organisation_id),
         );
    }


    public function alumniSubscriberListAction()
    {
        $this->loginDetails(); 

        $message = NULL;

        return array(
            'pendingSubscriber' => $this->alumniService->getAlumniSubscriberList($this->organisation_id, 'Pending'),
            'approvedSubscriber' => $this->alumniService->getAlumniSubscriberList($this->organisation_id, 'Approved'),
            'rejectedSubscriber' => $this->alumniService->getAlumniSubscriberList($this->organisation_id, 'Rejected'),
            'expiredSubscriber' => $this->alumniService->getAlumniSubscriberList($this->organisation_id, NULL),
            'message' => $message,
            'keyphrase' => $this->keyphrase,
        );
    }


    public function viewAlumniSubscriberDetailsAction()
    {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            return array(
                'subscriberDetails' => $this->alumniService->listAlumniSubscriptionDetails($id),
            );
        }else{
            return $this->redirect()->toRoute('alumnisubscriberlist');
        }

    }


    public function updateAlumniSubscriptionAction()
    {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
            $form = new UpdateAlumniSubscriberDetailsForm(); 
            $alumniModel = new UpdateAlumniSubscriberDetails();
            $form->bind($alumniModel);

            $message = NULL;

           $request = $this->getRequest();
                if ($request->isPost()){
                    $form->setData($request->getPost());
                    if ($form->isValid()){
                        try{
                            $this->alumniService->updateAlumniSubscription($alumniModel);
                            $this->auditTrailService->saveAuditTrail("UPDATE", "Alumni Subscriber Details", "ALL", "SUCCESS");
                            $this->flashMessenger()->addMessage("You have successfully updated the subscription");
                            return $this->redirect()->toRoute('alumnisubscriberlist');
                        }
                        catch(\Exception $e){
                            $message = 'Failure';
                            $this->flashMessenger()->addMessage($e->getMessage());
                        }
                    }
                }

             return array(
                'id' => $id,
                'form' => $form,
                'subscriberDetails' => $this->alumniService->listAlumniSubscriptionDetails($id),
                'subscriptionDetails' => $this->alumniService->getAlumniSubscriptionApplicationDetails($id),
                'message' => $message,
             );
        }else{
            return $this->redirect()->toRoute('alumnisubscriberlist');
        }
    }


    public function renewAlumniExpiredDateAction()
    {
        $this->loginDetails();
        $message = NULL;
        //get the id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
                try {
                 $this->alumniService->renewAlumniSubscription($id);
                 $this->auditTrailService->saveAuditTrail("UPDATE", "Alumni Subscriber Details", "ALL", "SUCCESS");

                 $this->flashMessenger()->addMessage('You have successfully renew an alumni subscription.');
                 return $this->redirect()->toRoute('alumnisubscriberlist');
             }
             catch(\Exception $e) {
                $message = 'Failure';
                $this->flashMessenger()->addMessage($e->getMessage());
                     // Some DB Error happened, log it and let the user know
             }
             return array(
                'message' => $message,
            );
        }
        else
        {
            return $this->redirect()->toRoute('alumnisubscriberlist');
        }
    }


    //ajax for selecting training type based on training category
    
    public function ajaxAlumniSubscriptionChargeAction()
    {
        $parentValue = $_POST['value'];
        
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        
        $sql       = "SELECT * FROM `alumni_subscription_details` where `subscription_type`= '$parentValue' AND `organisation_id`='$this->organisation_id'";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
       // / $selectTwoData[0]="Please Select Semester";
        foreach ($result as $res) {
            $selectTwoData[$res['subscription_type']] = $res['subscription_charge'];
        }
        
        return new JsonModel([
            'data' => $selectTwoData
        ]);
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
             
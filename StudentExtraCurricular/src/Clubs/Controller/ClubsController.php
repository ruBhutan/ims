<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Clubs\Controller;

use Clubs\Service\ClubsServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Clubs\Form\ClubsForm;
use Clubs\Form\ClubsApplicationForm;
use Clubs\Form\ClubsSearchForm;
use Clubs\Model\Clubs;
use Clubs\Model\ClubsApplication;
use Zend\Session\Container;
use Zend\Http\Response\Stream;
use Zend\Http\Headers;
use Zend\Paginator\Adapter\DbSelect;
use Zend\View\Model\JsonModel;
use Zend\Form\Element\Select;

/**
 * Description of IndexController
 *
 */
 
class ClubsController extends AbstractActionController
{
	protected $clubsService;
	protected $notificationService;
    protected $auditTrailService;
	protected $serviceLocator;
	protected $username;
	protected $userrole;
	protected $userregion;
	protected $usertype;
	protected $userDetails;
    protected $userImage;
	protected $employee_details_id;
	protected $student_id;
	protected $organisation_id;

	protected $keyphrase = "RUB_IMS";
	
	public function __construct(ClubsServiceInterface $clubsService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->clubsService = $clubsService;
		$this->notificationService = $notificationService;
        $this->auditTrailService = $auditTrailService;
		$this->serviceLocator = $serviceLocator;
		
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
		
		$empData = $this->clubsService->getUserDetailsId($this->username, $tableName = 'employee_details');
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			}

		if($this->employee_details_id == NULL)
		{
			$studentData = $this->clubsService->getUserDetailsId($this->username, $tableName = 'student');
			foreach($studentData as $std){
			$this->student_id = $std['id'];
			}
		}
		
		//get the organisation id
		$organisationID = $this->clubsService->getOrganisationId($this->username, $this->usertype);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}

		//get the user details such as name
        $this->userDetails = $this->clubsService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->clubsService->getUserImage($this->username, $this->usertype);
	}


	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }

    
	public function addClubAction()
    {
    	$this->loginDetails();
        $form = new ClubsForm();
		$clubsModel = new Clubs();
		$form->bind($clubsModel);
		
		$clubs = $this->clubsService->listAll($tableName='clubs', $this->organisation_id);
		$message = NULL;
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->clubsService->save($clubsModel);
					 $this->auditTrailService->saveAuditTrail("INSERT", "Clubs", "ALL", "SUCCESS");
					$this->flashMessenger()->addMessage('Club was successfully added');
                    return $this->redirect()->toRoute('addclub');
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
			'clubs' => $clubs,
			'keyphrase' => $this->keyphrase,
		); 
    } 
    
	public function editClubAction()
    {
    	$this->loginDetails();
        //get the club id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new ClubsForm();
			$clubsModel = new Clubs();
			$form->bind($clubsModel);
			
			$clubDetails = $this->clubsService->findClubs($id);
			$clubs = $this->clubsService->listAll($tableName='clubs', $this->organisation_id);
			$message = NULL;
	        
	        $request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) {
	                 try {
						 $this->clubsService->save($clubsModel);
						 $this->auditTrailService->saveAuditTrail("EDIT", "Clubs", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage('Club was successfully edited');
                		return $this->redirect()->toRoute('addclub');
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
					'clubs' => $clubs,
					'clubDetails' => $clubDetails,
				);
        }else{
        	$this->redirect()->toRoute('addclub');
        }
    }
	
	public function viewClubAction()
    {
    	$this->loginDetails();
       //get the club id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new ClubsForm();
		
			$clubDetails = $this->clubsService->findClubs($id);
		 
        	return array(
				'form' => $form,
				'clubDetails' => $clubDetails,
				'id' => $id_from_route
			);
        }else{
        	$this->redirect()->toRoute('addclub');
        }
    }
    
	public function searchClubAction()
    {
    	$this->loginDetails();
        $form = new ClubsForm();
		$clubsModel = new Clubs();
		$form->bind($clubsModel);
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 var_dump($form);
				 die();
                 try {
					 $this->clubsService->save($clubsModel);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array('form' => $form);
    }
	
	
	public function applyClubMembershipAction()
    {
    	$this->loginDetails();

		$clubs = $this->clubsService->listAll($tableName='clubs', $this->organisation_id);
        		 
        return array(
			'clubs' => $clubs,
			'keyphrase' => $this->keyphrase,
		);
    }
	
	public function addClubMembersAction()
	{
		$this->loginDetails();
		//get the club id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new ClubsApplicationForm();
			$clubsModel = new ClubsApplication();
			$form->bind($clubsModel);
			
			$clubs = $this->clubsService->listAll($tableName='clubs', $this->organisation_id);
			if($this->student_id){
				$student = $this->clubsService->getStudentDetails($this->student_id);
			}
			else{
				return;
			};

			$clubDetails = $this->clubsService->findClubs($id);

			$message = NULL;
			
				$request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) { 
	             	$check_club_application = $this->clubsService->crossCheckClubApplication($this->student_id, $id);
					if($check_club_application){
						$message = 'Failure';
						$this->flashMessenger()->addMessage('You have already applied for this particular club and it has been already approved or it is still pending');
					}else{
		                 try {
							 $this->clubsService->saveClubApplications($clubsModel);
							 $this->notificationService->saveNotification('Club Membership Application', 'ALL', 'NULL', 'Student Club');
							 $this->auditTrailService->saveAuditTrail("INSERT", "Student Club Applications", "ALL", "SUCCESS");
							 $this->flashMessenger()->addMessage('You have successfully applied for club');
							 return $this->redirect()->toRoute('applymembership');
						 }
						 catch(\Exception $e) {
								 die($e->getMessage());
								 // Some DB Error happened, log it and let the user know
						 }
	             	}
	         	}
			}
			 
	        return array(
				'form' => $form,
				'student' => $student,
				'student_id' => $this->student_id,
				'clubs_id' => $id,
				'clubs' => $clubs,
				'clubDetails' => $clubDetails,
				'message' => $message,
			);
        }else{
        	$this->redirect()->toRoute('clubmembershipstatus');
        }
	}
	
	public function viewClubMembershipstatusAction()
    {
    	$this->loginDetails();

        $form = new ClubsSearchForm();
		
		$clubApplications = $this->clubsService->listClubApplications($this->organisation_id);
		$clubList = $this->clubsService->listSelectData($tableName='clubs', $columnName='club_name', $this->organisation_id);

		$message = NULL;
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
             		$clubs_id = $this->getRequest()->getPost('club_name'); 
                 	$clubApplications = $this->clubsService->getStudentClubMembership($clubs_id, $tableName = 'student_club_applications');
				 }
             }
		 
        return array(
				'form' => $form,
				'clubList' => $clubList,
				'clubApplications' => $clubApplications,
				'keyphrase' => $this->keyphrase,
				'message' => $message,
			);
    }
	
	public function viewClubMembersAction()
    {
    	$this->loginDetails();

        $form = new ClubsSearchForm();
		
		$clubApplications = $this->clubsService->listClubMembers($this->organisation_id);
		$clubList = $this->clubsService->listSelectData($tableName='clubs', $columnName='club_name', $this->organisation_id);
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {

                $clubs_id = $this->getRequest()->getPost('club_name'); 
             	$clubApplications = $this->clubsService->getStudentClubMembership($clubs_id, $tableName = 'student_clubs');
             }
         }
		 
        return array(
			'form' => $form,
			'clubList' => $clubList,
			'clubApplications' => $clubApplications);
    }
	
	public function approveClubMembersAction()
    {
    	$this->loginDetails();
    	//get the club application id
    	$id_from_route = $this->params()->fromRoute('id', 0);
        $application_id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($application_id)){
        	try {
				 $this->clubsService->submitClubApplication($application_id, $status = 'Approved');
				 $this->flashMessenger()->addMessage("You have successfully approved club membership");
				 return $this->redirect()->toRoute('clubmembershipstatus');
			 }
			 catch(\Exception $e) {
					 die($e->getMessage());
					 // Some DB Error happened, log it and let the user know
			 }
        }else{
        	$this->redirect()->toRoute('clubmembershipstatus');
        }		 
    }
	
	public function rejectClubMembersAction()
    {
    	$this->loginDetails();
        //get the club application id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $application_id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($application_id)){
        	try {
				 $this->clubsService->submitClubApplication($application_id, $status = 'Rejected');
				 $this->flashMessenger()->addMessage("You have successfully rejected club membership");
				 return $this->redirect()->toRoute('clubmembershipstatus');
			 }
			 catch(\Exception $e) {
					 die($e->getMessage());
					 // Some DB Error happened, log it and let the user know
			 }
        }else{
        	$this->redirect()->toRoute('clubmembershipstatus');
        }
    }


     public function deleteClubAction()
    {
     
          		$this->loginDetails();



                if ($this->getRequest()->isGet())
                {
                        try {
                                 $id_from_route = $this->params()->fromRoute('id', 0);

                                 $id = $this->my_decrypt($id_from_route, $this->keyphrase);
                                 

                                 // This will fetch ID of the club
                                 if($this->clubsService->deleteClub($id))


                                 //if($id != null)
                                 {

                                          $this->flashMessenger()->addMessage('Club was successfully DELETED');

                                          $this->auditTrailService->saveAuditTrail("DELETE", "Club", "One Row", "SUCCESS", "DELETED CLUB WITH ID:".$id);

                                          return $this->redirect()->toRoute('addclub');
                                 }
                        }catch(\Exception $e) {
                                 $message = 'Failure';
                                 $this->flashMessenger()->addMessage($e->getMessage());
                        }

                       return $this->redirect()->toRoute('addclub'); 
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

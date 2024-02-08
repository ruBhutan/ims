<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Achievements\Controller;

use Achievements\Service\AchievementsServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Achievements\Form\AchievementsForm;
use Achievements\Form\AchievementsCategoryForm;
use Achievements\Form\SearchForm;
use Achievements\Model\Achievements;
use Achievements\Model\AchievementsCategory;
use Zend\Session\Container;
use Zend\Http\Response\Stream;
use Zend\Http\Headers;

/**
 * Description of IndexController
 *
 */
 
class AchievementsController extends AbstractActionController
{
	protected $achievementService;
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
	protected $organisation_id;

	protected $keyphrase = "RUB_IMS";
	
	public function __construct(AchievementsServiceInterface $achievementService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->achievementService = $achievementService;
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
		
		$empData = $this->achievementService->getUserDetailsId($this->username);
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			}
		
		//get the organisation id
		$organisationID = $this->achievementService->getOrganisationId($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
			$this->departments_id = $organisation['departments_id'];
		}

		//get the user details such as name
        $this->userDetails = $this->achievementService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->achievementService->getUserImage($this->username, $this->usertype);
	}

	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }
	
	public function addAchievementCategoryAction()
    {	
    	$this->loginDetails();

		$form = new AchievementsCategoryForm();
		$achievementModel = new AchievementsCategory();
		$form->bind($achievementModel);
		
		$message = NULL;
		$achievementsCategory = $this->achievementService->listAll('student_achievements_category', $this->organisation_id);
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->achievementService->saveAchievementsCategory($achievementModel);
					 $this->auditTrailService->saveAuditTrail("INSERT", "Student Achievement Category", "ALL", "SUCCESS");
					 $this->flashMessenger()->addMessage('Achievement Category was successfully added');
					 return $this->redirect()->toRoute('addachievementcategory');
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
			'achievementsCategory' => $achievementsCategory,
			'keyphrase' => $this->keyphrase,
		);
    }
	
	public function editAchievementCategoryAction()
    {
    	$this->loginDetails();
        //get the category id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){ 
        	$form = new AchievementsCategoryForm();
			$achievementModel = new AchievementsCategory();
			$form->bind($achievementModel);
			
			$achievements_details = $this->achievementService->getAchievementsCategoryDetails($id);
	        
	        $request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) {
	                 try {
						 $this->achievementService->saveAchievementsCategory($achievementModel);
						 $this->auditTrailService->saveAuditTrail("EDIT", "Student Achievement Category", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage('Achievement Category was successfully edit');
						 return $this->redirect()->toRoute('addachievementcategory');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
	             }
	         }
			 
	        return array(
				'form' => $form,
				'achievements_details' => $achievements_details,
			);
        }else{
        	$this->redirect()->toRoute('addachievementcategory');
        }
    }
	
	//function to search and then display before adding
	public function studentAchievementAction()
    {
    	$this->loginDetails();

       $form = new SearchForm();
	   
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
				$studentList = $this->achievementService->getStudentList($studentName, $studentId, $programme, $this->organisation_id);
             }
         }
		 else {
			 $studentList = array();
		 }
		
		return new ViewModel(array(
            'form' => $form,
			'studentList' => $studentList,
			'keyphrase' => $this->keyphrase,
            ));
    }
	    
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
	
	public function viewStudentAchievementAction()
    {
    	$this->loginDetails();
        //get the student id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){ 
        	$form = new AchievementsForm();
		
			$student_detail_tmp = $this->achievementService->getStudentDetails($tableName = 'student_achievements', $id);
			$studentDetail = array();
			foreach($student_detail_tmp as $tmp){
				$studentDetail = $tmp;
			} 

			$studentAchievement = $this->achievementService->getStudentAchievements($studentDetail['id']);
	        		 
	        return array(
				'form' => $form,
				'studentAchievement' => $studentAchievement,
			);
        }else{
        	$this->redirect()->toRoute('viewachievement');
        }
    }
	
	public function viewAchievementsAction()
    {
    	$this->loginDetails();

        $form = new AchievementsForm();
		$searchForm = new SearchForm();

		$message = NULL;
	   
	    $request = $this->getRequest();
         if ($request->isPost()) {
             $searchForm->setData($request->getPost());
             if ($searchForm->isValid()) {
				$studentName = $this->getRequest()->getPost('student_name');
				$studentId = $this->getRequest()->getPost('student_id');
				$programme = $this->getRequest()->getPost('programme');
				$achievements = $this->achievementService->getStudentAchievementList($studentName, $studentId, $programme, $this->organisation_id);
             }
         }
		 else {
			 //default list of achievements. This is a limited list
			 $achievements = $this->achievementService->getAchievements($this->organisation_id);
		 }
		
        return array(
				'form' => $form,
				'searchForm' => $searchForm,
				'achievements' => $achievements,
				'keyphrase' => $this->keyphrase,
				'message' => $message,
			);
    } 
    
	public function editAchievementsAction()
    {
    	$this->loginDetails();
        //get the student id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	 $form = new AchievementsForm();
			$achievementModel = new Achievements();
			$form->bind($achievementModel);

			$studentDetail = $this->achievementService->getStudentDetail($id);
			$achievementDetail = $this->achievementService->getStudentAchievementDetails($id);

			$achievements_category = $this->achievementService->listSelectData($tableName = 'student_achievements_category', $columnName = 'achievement_name', $this->organisation_id);
	        
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
						 $this->achievementService->updateAchievements($achievementModel);
		                     $this->auditTrailService->saveAuditTrail("EDIT", "Student Achievements", "ALL", "SUCCESS");
		                     $this->flashMessenger()->addMessage('Achievements Record was successfully editeds');
							 return $this->redirect()->toRoute('viewachievement');
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
	        	'studentDetail' => $studentDetail,
	        	'achievements_category' => $achievements_category,
	        	'achievementDetail' => $achievementDetail,

	    		);
        }else{
        	return $this->redirect()->toRoute('viewachievement');
        }  
    }


    public function downloadStudentAchievementFileAction()
    {
        $this->loginDetails();
        //get the student id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
            $file = $this->achievementService->getFileName($id);
        
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
            $this->redirect()->toRoute('viewachievement');
        }
    }

    
	public function searchAchievementsAction()
    {
    	$this->loginDetails();
        $form = new AchievementsForm();
		$achievementModel = new Achievements();
		$form->bind($achievementModel);
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->achievementService->save($achievementModel);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array('form' => $form);
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

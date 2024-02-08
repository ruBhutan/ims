<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace EmpTraining\Controller;

use EmpTraining\Service\EmpTrainingServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use EmpTraining\Form\EmpTrainingForm;
use EmpTraining\Form\EmpWorkshopForm;
use EmpTraining\Form\LongTermApplicationForm;
use EmpTraining\Form\UpdateLongTermApplicationForm;
use EmpTraining\Form\ShortTermApplicationForm;
use EmpTraining\Form\HrLongTermApplicationForm;
use EmpTraining\Form\HrShortTermApplicationForm;
use EmpTraining\Form\TrainingNominationForm;
use EmpTraining\Form\TrainingReportForm;
use EmpTraining\Form\StudyReportForm;
use EmpTraining\Form\StudyExtensionForm;
use EmpTraining\Form\SearchForm;
use EmpTraining\Model\TrainingDetails;
use EmpTraining\Model\WorkshopDetails;
use EmpTraining\Model\LongTermApplication;
use EmpTraining\Model\HrLongTermApplication;
use EmpTraining\Model\ShortTermApplication;
use EmpTraining\Model\TrainingNomination;
use EmpTraining\Model\TrainingReport;
use EmpTraining\Model\StudyReport;
use EmpTraining\Model\StudyExtension;
use Zend\Session\Container;
use Zend\Http\Response\Stream;
use Zend\Http\Headers;

class EmpTrainingController extends AbstractActionController
{
	protected $trainingService;
	protected $notificationService;
	protected $auditTrailService;
	protected $serviceLocator;
	protected $emailService;
	protected $username;
	protected $userrole;
	protected $usertype;
	protected $userregion;
	protected $userDetails;
	protected $userImage;
	protected $employee_details_id;
	protected $organisation_id;

	protected $keyphrase = "RUB_IMS";

	
	public function __construct(EmpTrainingServiceInterface $trainingService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->trainingService = $trainingService;
		$this->notificationService = $notificationService;
        $this->auditTrailService = $auditTrailService;
        $this->emailService = $serviceLocator->get('Application\Service\EmailService');
		
		/*
		 * To retrieve the user name from the session
		*/
		$authPlugin = $serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
        $this->username = $authPlugin['username'];
        $this->userrole = $authPlugin['role'];
        $this->userregion = $authPlugin['region'];
        $this->usertype = $authPlugin['user_type_id'];
		
		
		/*
		* Getting the employee_details_id related to username
		*/
		
		$empData = $this->trainingService->getUserDetailsId($this->username);
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			}
		
		//get the organisation id
		$organisationID = $this->trainingService->getOrganisationId($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}
		
		//get the user details such as name
		$this->userDetails = $this->trainingService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->trainingService->getUserImage($this->username, $this->usertype);
	}
	
	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }
    
	public function longTermPlannedTrainingListAction()
    {
		$this->loginDetails();
		
        return new ViewModel(array(
        	'keyphrase' => $this->keyphrase,
			'approvedList' => $this->trainingService->listHrdPlan($type='Long-term Professional Development', $this->organisation_id),
			));
    }
	
	public function longTermPlannedTrainingAction()
    {
		$this->loginDetails();
		
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){			
			$form = new EmpTrainingForm($this->serviceLocator);
			$trainingModel = new TrainingDetails();
			$form->bind($trainingModel);

			$trainingDetail = $this->trainingService->findPlanDetail($id);
			$trainingCategories = $this->trainingService->listSelectData('training_types', 'training_type');
			$trainingTypes = $this->trainingService->listSelectData('training_type_details', 'training_type_detail');
			$fundingSource = $this->trainingService->listSelectData('funding_category', 'funding_type');
			$courseLevel = $this->trainingService->listSelectData('study_level', 'study_level');

			 $institute_country = $this->trainingService->listSelectData($tableName = 'country', $columnName = 'country');

			$message = NULL;
			
	        $request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             $category = $this->getRequest()->getPost('training_category');
	             $type = $this->getRequest()->getPost('training_type');
	             if ($form->isValid()) {
	                 try {
						 $this->trainingService->save($trainingModel, $category, $type);
						 $this->auditTrailService->saveAuditTrail("INSERT", "Long Term Planned Training Announcement", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage('Training was successfully activated');
						 return $this->redirect()->toRoute('applytrainings');
					 }
					 catch(\Exception $e) {
					 	$message = 'Failure';
					 	$this->flashMessenger()->addMessage($e->getMessage());
					 }
	             }
	         }
			 
	       return array(
		       	'id' => $id,
				'form' => $form,
				//'trainingList' => $trainingList,
				'proposing_agency' => $this->organisation_id,
				'trainingDetail' => $trainingDetail,
				'trainingTypes' => $trainingTypes,
				'trainingCategories' => $trainingCategories,
				'fundingSource' => $fundingSource,
				'courseLevel' => $courseLevel,
				'institute_country' => $institute_country,
				'message' => $message,
			);
        }else{
        	return $this->redirect()->toRoute('applytrainings');
        }
    }
	
	public function longTermAdhocTrainingAction()
    {
		$this->loginDetails();
		
        $form = new EmpTrainingForm($this->serviceLocator);
		$trainingModel = new TrainingDetails();
		$form->bind($trainingModel);
		
        $trainingList = $this->trainingService->listAdhocTrainingList('Long Term', $this->organisation_id);
		$fundingSource = $this->trainingService->listSelectData('funding_category', 'funding_type');
		$courseLevel = $this->trainingService->listSelectData('study_level', 'study_level');
		$institute_country = $this->trainingService->listSelectData($tableName = 'country', $columnName = 'country');

		$message = NULL;
		
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
             	$category = $this->getRequest()->getPost('training_category');
	             $type = $this->getRequest()->getPost('training_type');
                 try {
					 $this->trainingService->save($trainingModel, $category, $type);
					 $this->flashMessenger()->addMessage('Long Term Adhoc Training was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "Long Term Adhoc Training Announcement", "ALL", "SUCCESS");
					 return $this->redirect()->toRoute('longtermadhoc');
				 }
				 catch(\Exception $e) {
				 	$message = 'Failure';
				 	$this->flashMessenger()->addMessage($e->getMessage());
				 }
             }
			 
         }
		 
        return new ViewModel(array(
			'form' => $form,
			'trainingList' => $trainingList,
			'proposing_agency' => $this->organisation_id,
			'fundingSource' => $fundingSource,
			'courseLevel' => $courseLevel,
			'institute_country' => $institute_country,
			'message' => $message,
			'keyphrase' => $this->keyphrase,
		));
    }
	
	
	public function editLongTermAdhocTrainingAction()
	{
		$this->loginDetails();
		
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){ 
			$form = new EmpTrainingForm($this->serviceLocator);
			$trainingModel = new TrainingDetails();
			$form->bind($trainingModel);
			
			$training_details = $this->trainingService->getAdhocTrainingDetails('Long Term', $id);
			$fundingSource = $this->trainingService->listSelectData('funding_category', 'funding_type');
			$courseLevel = $this->trainingService->listSelectData('study_level', 'study_level');
			$institute_country = $this->trainingService->listSelectData($tableName = 'country', $columnName = 'country');

			$message = NULL;
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					$category = $this->getRequest()->getPost('training_category');
					 $type = $this->getRequest()->getPost('training_type');
					 try {
						 $this->trainingService->save($trainingModel, $category, $type);
						 $this->flashMessenger()->addMessage('Long Term Adhoc Training was successfully edited');
						 $this->auditTrailService->saveAuditTrail("UPDATE", "Long Term Adhoc Training Announcement", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('longtermadhoc');
					 }
					 catch(\Exception $e) {
						$message = 'Failure';
						$this->flashMessenger()->addMessage($e->getMessage());
					 }
				 }
				 
			 }
			 
			return new ViewModel(array(
				'form' => $form,
				'proposing_agency' => $this->organisation_id,
				'training_details' => $training_details,
				'fundingSource' => $fundingSource,
				'courseLevel' => $courseLevel,
				'institute_country' => $institute_country,
				'message' => $message,
				'keyphrase' => $this->keyphrase,
			));
		}else{
			return $this->redirect()->toRoute('longtermadhoc');
		}
	}
	
	
	public function deleteLongTermAdhocTrainingAction()
	{
		$this->loginDetails();

        //get the student id
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){
			$training_nomination = $this->trainingService->getAdhocTrainingNomination($id, 'Long Term');
			
			if(!empty($training_nomination)){
				$message = 'Failure';
				$this->flashMessenger()->addMessage("Sorry you can't delete this particular adhoc training since you have already nominated staff for this particular training!");
				return $this->redirect()->toRoute('longtermadhoc');
			}
			else{
				try{
					$this->trainingService->deleteAdhocTraining($id, 'Long Term');
					$this->auditTrailService->saveAuditTrail("DELETE", "Long Term Adhoc Training Announcement", "ALL", "SUCCESS");

					$this->flashMessenger()->addMessage('You have delected Long Term Adhoc Training Announcement successfully');
					return $this->redirect()->toRoute('longtermadhoc');
				}
				catch(\Exception $e) {
					$message = 'Failure';
					$this->flashMessenger()->addMessage($e->getMessage());
				}
			}
        	
        return array(
        	'id' => $id,
        	'message' => $message,
        );

        }else{
            return $this->redirect()->toRoute('longtermadhoc');
        }
	}


    public function longTermAdhocTrainingDetailsAction()
    {
    	$this->loginDetails();

    	$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){ 
			$form = new EmpTrainingForm($this->serviceLocator);
			$trainingModel = new TrainingDetails();
			$form->bind($trainingModel);
			$trainingDetails = $this->trainingService->getAdhocTrainingDetails('Long Term', $id);

			$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {

                 try {
					 
				 }
				 catch(\Exception $e) {
				 	$message = 'Failure';
				 	$this->flashMessenger()->addMessage($e->getMessage());
				 }
             }
			 
         }

	        return array(
	        	'form' => $form,
				'trainingDetails' => $trainingDetails,
			);
		}else{
			return $this->redirect()->toRoute('longtermadhoc');
		}
    }
	
	/*
	* Short Term Trainings
	*/
	
	public function shortTermPlannedTrainingListAction()
    {
		$this->loginDetails();

		$message = NULL;
		
        return new ViewModel(array(
			'approvedList' => $this->trainingService->listHrdPlan($type='Short-term Professional Development', $this->organisation_id),
			'keyphrase' => $this->keyphrase,
			'message' => $message,
			));
    }
	
	public function shortTermPlannedTrainingAction()
    {
		$this->loginDetails();
		
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new EmpWorkshopForm();
			$trainingModel = new WorkshopDetails();
			$form->bind($trainingModel);
			
	        $trainingList = array();
	        $funding_source = $this->trainingService->listSelectData('funding_category', 'funding_type');

	        $training_type = $this->trainingService->listSelectData($tableName = 'training_type_details', $columnName = 'training_type_detail');
		    $institute_country = $this->trainingService->listSelectData($tableName = 'country', $columnName = 'country');

	        $message = NULL;
			
	        $request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) {
	                 try {
						 $this->trainingService->saveShortTermTraining($trainingModel);
						 $this->flashMessenger()->addMessage('Short Term Planned Training was successfully added');
						 $this->auditTrailService->saveAuditTrail("INSERT", "Short Term Planned Training Announcement", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('applytrainings');
					 }
					 catch(\Exception $e) {
					 	$message = 'Failure';
					 	$this->flashMessenger()->addMessage($e->getMessage());
					 }
	             }
	         }
			 
	        return array(
				'form' => $form,
				'funding_source' => $funding_source,
				'training_type' => $training_type,
				'institute_country' => $institute_country,
				'proposing_agency' => $this->organisation_id,
				'trainingList' => $trainingList,
				'message' => $message,
			);
        }else{
        	return $this->redirect()->toRoute('shorttermplannedlist');
        }
    }
	
	public function shortTermAdhocTrainingAction()
    {
		$this->loginDetails();
		
        $form = new EmpWorkshopForm();
		$trainingModel = new WorkshopDetails();
		$form->bind($trainingModel);
		
        $trainingList = $this->trainingService->listAdhocTrainingList('Short Term', $this->organisation_id);
        $funding_source = $this->trainingService->listSelectData('funding_category', 'funding_type');
		$training_type = $this->trainingService->listSelectData($tableName = 'training_type_details', $columnName = 'training_type_detail');
		$institute_country = $this->trainingService->listSelectData($tableName = 'country', $columnName = 'country');

        $message = NULL;
		
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->trainingService->saveShortTermTraining($trainingModel);
					 $this->flashMessenger()->addMessage('Short Term Adhoc Training was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "Short Term Adhoc Training Announcement", "ALL", "SUCCESS");
					 return $this->redirect()->toRoute('shorttermadhoc');
					 
				 }
				 catch(\Exception $e) {
						$message = 'Failure';
					 	$this->flashMessenger()->addMessage($e->getMessage());
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'funding_source' => $funding_source,
			'proposing_agency' => $this->organisation_id,
			'trainingList' => $trainingList,
			'training_type' => $training_type,
			'institute_country' => $institute_country,
			'message' => $message,
			'keyphrase' => $this->keyphrase,
		);
    }
	
	
	public function editShortTermAdhocTrainingAction()
	{
		$this->loginDetails();
		
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new EmpWorkshopForm();
			$trainingModel = new WorkshopDetails();
			$form->bind($trainingModel);
			
			$trainingDetails = $this->trainingService->getAdhocTrainingDetails('Short Term', $id);
			$funding_source = $this->trainingService->listSelectData('funding_category', 'funding_type');
			$training_type = $this->trainingService->listSelectData($tableName = 'training_type_details', $columnName = 'training_type_detail');
			$institute_country = $this->trainingService->listSelectData($tableName = 'country', $columnName = 'country');

			$message = NULL;
			
			$request = $this->getRequest();
			 if ($request->isPost()) {
				 $form->setData($request->getPost());
				 if ($form->isValid()) {
					 try {
						 $this->trainingService->saveShortTermTraining($trainingModel);
						 $this->flashMessenger()->addMessage('Short Term Adhoc Training was successfully edited');
						 $this->auditTrailService->saveAuditTrail("UPDATE", "Short Term Adhoc Training Announcement", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('shorttermadhoc');
						 
					 }
					 catch(\Exception $e) {
							$message = 'Failure';
							$this->flashMessenger()->addMessage($e->getMessage());
					 }
				 }
			 }
			 
			return array(
				'form' => $form,
				'funding_source' => $funding_source,
				'proposing_agency' => $this->organisation_id,
				'trainingDetails' => $trainingDetails,
				'training_type' => $training_type,
				'institute_country' => $institute_country,
				'message' => $message,
				'keyphrase' => $this->keyphrase,
			);
		}else{
			return $this->redirect()->toRoute('shorttermadhoc');
		}
	}
	
	
	public function deleteShortTermAdhocTrainingAction()
	{
		$this->loginDetails();
		
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        if(is_numeric($id)){ 
			$training_nomination = $this->trainingService->getAdhocTrainingNomination($id, 'Short Term');
			
			if(!empty($training_nomination)){
				$message = 'Failure';
				$this->flashMessenger()->addMessage("Sorry you can't delete this particular adhoc workshop since you have already nominated staff for this particular workshop!");
				return $this->redirect()->toRoute('shorttermadhoc');
			}
			else{
				try{
					$this->trainingService->deleteAdhocTraining($id, 'Short Term');
					$this->auditTrailService->saveAuditTrail("DELETE", "Short Term Adhoc Training Announcement", "ALL", "SUCCESS");

					$this->flashMessenger()->addMessage('You have delected Short Term Adhoc Training Announcement successfully');
					return $this->redirect()->toRoute('shorttermadhoc');
				}
				catch(\Exception $e) {
					$message = 'Failure';
					$this->flashMessenger()->addMessage($e->getMessage());
				}
			}
        	
        return array(
        	'id' => $id,
        	'message' => $message,
        );

        }else{
            return $this->redirect()->toRoute('shorttermadhoc');
        }
	}


    public function shortTermAdhocTrainingDetailsAction()
    {
    	$this->loginDetails();

    	$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){ 
			$form = new EmpWorkshopForm();
			$trainingModel = new WorkshopDetails();
			$form->bind($trainingModel);
			$trainingDetails = $this->trainingService->getAdhocTrainingDetails('Short Term', $id);

			$request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {

                 try {
					 
				 }
				 catch(\Exception $e) {
				 	$message = 'Failure';
				 	$this->flashMessenger()->addMessage($e->getMessage());
				 }
             }
			 
         }

	        return array(
	        	'form' => $form,
				'trainingDetails' => $trainingDetails,
			);
		}else{
			return $this->redirect()->toRoute('longtermadhoc');
		}
    }

	
	public function applyTrainingsAction()
	{
		$this->loginDetails();
		
		$message = NULL;
		
		return new ViewModel(array(
			'longTermTrainingList' => $this->trainingService->getNominatedTrainingList($tableName='training_details', $this->employee_details_id),
			'shortTermTrainingList' => $this->trainingService->getNominatedTrainingList($tableName='workshop_details', $this->employee_details_id),
			'appliedLongTerm' => $this->trainingService->getAppliedTrainingList('longterm', $this->employee_details_id),
			'appliedShortTerm' => $this->trainingService->getAppliedTrainingList('shortterm', $this->employee_details_id),
			'message' => $message,
			'keyphrase' => $this->keyphrase,
			));	
	}
	
	public function viewTrainingApplicationsAction()
	{
		$this->loginDetails();

		$message = NULL;
		
		return new ViewModel(array(
			'longTermTrainingList' => $this->trainingService->listTrainingDetails($tableName='training_details', $this->organisation_id),
			'shortTermTrainingList' => $this->trainingService->listTrainingDetails($tableName='workshop_details', $this->organisation_id),
			'keyphrase' => $this->keyphrase,
			'message' => $message,
			));	
	}
	
	/*
	* Used By Employee when Filling in the training application form
	*/
	
	public function shortTermTrainingFormAction()
	{
		$this->loginDetails();
		
		
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			//$id is the training id
			 $cross_check = $this->trainingService->crossCheckTrainingApplication($this->employee_details_id, $id, 'shortterm');
			 if($cross_check == "Applied"){
				 $this->flashMessenger()->addMessage('You have already applied for this training. You cannot apply twice');
				  return $this->redirect()->toRoute('applytrainings');
			 }
			
			$form = new ShortTermApplicationForm();
			$trainingModel = new ShortTermApplication();
			$form->bind($trainingModel);

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
					 $data = $form->getData();
					 $workshop_details_id = $trainingModel->getWorkshop_Details_Id();
	                 try {
						 $this->trainingService->saveShortTermApplication($trainingModel);
						 $this->sendAppliedTrainingEmail($this->organisation_id, $workshop_details_id, 'shortterm', $id);
						 $this->flashMessenger()->addMessage('Training Application was successfully saved');
						 $this->notificationService->saveNotification('Training Application', 'ALL', 'ALL', 'Training Application Update');
						 $this->auditTrailService->saveAuditTrail("INSERT", "Training Application", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('applytrainings');
					 }
					 catch(\Exception $e) {
					 	$message = 'Failure';
					 	$this->flashMessenger()->addMessage($e->getMessage());
					 }
	             }
	         }
			 
			return new ViewModel(array(
				'form' => $form,
				'employee_details_id' => $this->employee_details_id,
				'workshop_details_id' => $id,
				'message' => $message,
				'trainingList' => $this->trainingService->listAll($tableName='training_details'),
				));	
		}else{
			 return $this->redirect()->toRoute('applytrainings');
		}
	}


	/*
	* Used By Self to upload the missing file if necessary
	*/
	
	public function editAppliedShortTermApplicationAction()
	{
		$this->loginDetails();
		
		//get the training id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){

			$form = new ShortTermApplicationForm();
			$trainingModel = new ShortTermApplication();
			$form->bind($trainingModel);

			$request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
				 $data = array_merge_recursive(
				 	$request->getPost()->toArray(),
					$request->getFiles()->toArray()
				 ); 
				 $form->setData($data); 
	             if ($form->isValid()) { 
					 $data = $form->getData();
	                 try {
						 $this->trainingService->updateEditedShortTermApplication($trainingModel);
						 $this->flashMessenger()->addMessage('Training was successfully edited');
						 $this->notificationService->saveNotification('Training Application', 'ALL', 'ALL', 'Training Application Update');
						 $this->auditTrailService->saveAuditTrail("UPDATE", "Training Application", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('editshorttermapplication', array('id'=>$this->my_encrypt($id, $this->keyphrase)));
					 }
					 catch(\Exception $e) {
					 	$message = 'Failure';
					 	$this->flashMessenger()->addMessage($e->getMessage());
					 }
	             }
	         }

			$message = NULL;
			
			return new ViewModel(array(
				'id' => $id,
				'form' => $form,
				'appliedTrainingDetails' => $this->trainingService->getAppliedTrainingDetails($id, $training_type='short_term', $this->employee_details_id),
				'employee_details_id' => $this->employee_details_id,
				'keyphrase' => $this->keyphrase,
				'message' => $message,
				));
		}else{
			return $this->redirect()->toRoute('viewtrainingapplications');
		}
	}

	
	/*
	* Used By HRO/Administrative Officer when filling in the details of the training
	*/
	
	public function shortTermApplicationsAction()
	{
		$this->loginDetails();
		
		//get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase); 
		
		if(is_numeric($id)){
			$trainingDetails = array();
			$traineeCount = 0;
			$hrShortTermApplicationForm = NULL;
			$tmp_data = array();

			$trainingDetails = $this->trainingService->getTrainingDetails($id, $training_type='short_term', NULL);
			$trainingNominations = $this->trainingService->getTrainingNominations($id, $training_type='short_term', NULL);

			$training_array = $this->trainingService->getTrainingDetails($id, $training_type='short_term', NULL);
			foreach ($training_array as $tmp) {
				$tmp_data[$tmp['id']] = $tmp['id'];
			}

			//$traineeCount = count($trainingNominations); 

			$hrShortTermApplicationForm = new HrShortTermApplicationForm($tmp_data); 

			return new ViewModel(array(
				//'form' => $form,
	            'id' => $id,
	            'trainingDetails' => $trainingDetails,
	            'trainingNominations' => $trainingNominations,
	            'hrShortTermApplicationForm' => $hrShortTermApplicationForm,
	            'traineeCount' => $traineeCount,
	            'keyphrase' => $this->keyphrase,
				//'message' => $message,
				));
		}else{
			 return $this->redirect()->toRoute('viewtrainingapplications');
		}
	}


	public function updateShortTermApplicationAction()
	{
		$this->loginDetails();

		//get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase); 
		
		if(is_numeric($id)){

			$training_array = $this->trainingService->getTrainingDetails($id, $training_type='short_term', NULL);
			foreach ($training_array as $tmp) {
				$tmp_data[$tmp['id']] = $tmp['id'];
			}

			$form = new HrShortTermApplicationForm($tmp_data);
			$trainingModel = new ShortTermApplication();
			$form->bind($trainingModel);

			$request = $this->getRequest();
			if($request->isPost()){
				$form->setData($request->getPost());
				$data = array_merge_recursive(
				 	$request->getPost()->toArray(),
					$request->getFiles()->toArray()
				 ); 
				 $form->setData($data); 
				if ($form->isValid()) { 
					$data_to_check = $this->extractShortTermTrainingData($tmp_data);
					try{
						$this->trainingService->updateShortTermApplication($trainingModel, $data_to_check);
						$this->flashMessenger()->addMessage('Course Content Schedule was successfully uploaded.');
             			return $this->redirect()->toRoute('viewtrainingapplications');
					}
					catch(\Exception $e){
						die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					}
				}
			}
			return array(
				'id' => $id,
				'form' => $form,
			);
		}else{
			return $this->redirect()->toRoute('viewtrainingapplications');
		}
	}

	/*
	* Used By Employee when Filling in the training application form
	*/
	
	public function longTermTrainingFormAction()
	{
		$this->loginDetails();
		
		//get the training id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
				//$id is the training id
			 $cross_check = $this->trainingService->crossCheckTrainingApplication($this->employee_details_id, $id, 'longterm');
			 if($cross_check == "Applied"){
				 $this->flashMessenger()->addMessage('You have already applied for this training. You cannot apply twice');
				  return $this->redirect()->toRoute('applytrainings');
			 }
			
			$form = new LongTermApplicationForm();
			$trainingModel = new LongTermApplication();
			$form->bind($trainingModel);

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
					 $data = $form->getData();
					 $training_details_id = $trainingModel->getTraining_Details_Id();
	                 try {
						 $this->trainingService->saveLongTermApplication($trainingModel);
						 $this->sendAppliedTrainingEmail($this->organisation_id, $training_details_id, 'longterm', $id);
						 $this->flashMessenger()->addMessage('Training was successfully saved');
						 $this->notificationService->saveNotification('Training Application', 'ALL', 'ALL', 'Training Application Update');
						 $this->auditTrailService->saveAuditTrail("INSERT", "Training Application", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('applytrainings');
					 }
					 catch(\Exception $e) {
					 	$message = 'Failure';
					 	$this->flashMessenger()->addMessage($e->getMessage());
					 }
	             }
	         }
			 
			return new ViewModel(array(
				'form' => $form,
				'trainingList' => $this->trainingService->listAll($tableName='training_details'),
				'employee_details_id' => $this->employee_details_id,
				'message' => $message,
				'training_details_id' => $id
				));	
		}else{
			return $this->redirect()->toRoute('applytrainings');
		}
	}


	/*
	* Used By Self to upload the missing file if necessary
	*/
	
	public function editAppliedLongTermApplicationAction()
	{
		$this->loginDetails();
		
		//get the training id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){

			$form = new UpdateLongTermApplicationForm();
			$trainingModel = new LongTermApplication();
			$form->bind($trainingModel);

			$request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
				 $data = array_merge_recursive(
				 	$request->getPost()->toArray(),
					$request->getFiles()->toArray()
				 ); 
				 $form->setData($data); 
	             if ($form->isValid()) { 
					 $data = $form->getData();
	                 try {
						 $this->trainingService->updateEditedLongTermApplication($trainingModel);
						 $this->flashMessenger()->addMessage('Training was successfully edited');
						 $this->notificationService->saveNotification('Training Application', 'ALL', 'ALL', 'Training Application Update');
						 $this->auditTrailService->saveAuditTrail("UPDATE", "Training Application", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('editlongtermapplication', array('id'=>$this->my_encrypt($id, $this->keyphrase)));
					 }
					 catch(\Exception $e) {
					 	$message = 'Failure';
					 	$this->flashMessenger()->addMessage($e->getMessage());
					 }
	             }
	         }

			$message = NULL;
			
			return new ViewModel(array(
				'id' => $id,
				'form' => $form,
				'appliedTrainingDetails' => $this->trainingService->getAppliedTrainingDetails($id, $training_type='long_term', $this->employee_details_id),
				'employee_details_id' => $this->employee_details_id,
				'keyphrase' => $this->keyphrase,
				'message' => $message,
				));
		}else{
			return $this->redirect()->toRoute('viewtrainingapplications');
		}
	}
	
	/*
	* Used By HRO/Administrative Officer when filling in the details of the training
	*/
	
	public function longTermApplicationsAction()
	{
		$this->loginDetails();
		
		//get the training id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){ 

			$message = NULL;
			
			return new ViewModel(array(
				'id' => $id,
				'trainingDetails' => $this->trainingService->getTrainingDetails($id, $training_type='long_term', NULL),
				'trainingNominations' => $this->trainingService->getTrainingNominations($id, $training_type='long_term', NULL),
				'employee_details_id' => $this->employee_details_id,
				'keyphrase' => $this->keyphrase,
				'message' => $message,
				));
		}else{
			return $this->redirect()->toRoute('viewtrainingapplications');
		}
	}


	public function appliedLongTermApplicationAction()
	{
		$this->loginDetails();

		//get the training id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new HrLongTermApplicationForm();
			$trainingModel = new HrLongTermApplication();
			$form->bind($trainingModel); 

			$message = NULL;

			$applicant_details = $this->trainingService->getLongTermApplicantDetails($id);
			
			$request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
				 $data = array_merge_recursive(
				 	$request->getPost()->toArray(),
					$request->getFiles()->toArray()
				 ); 
				 $form->setData($data); 
	             if ($form->isValid()) { 
					 $data = $form->getData();
	                 try {
						 $this->trainingService->updateLongTermApplication($trainingModel);
						 $this->flashMessenger()->addMessage('Training Application was successfully updated');
						 $this->notificationService->saveNotification('Training Application', 'ALL', 'ALL', 'Training Application Update');
						 $this->auditTrailService->saveAuditTrail("INSERT", "Employee Training Details", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('viewtrainingapplications');
					 }
					 catch(\Exception $e) {
							$message = 'Failure';
					 		$this->flashMessenger()->addMessage($e->getMessage());
					 }
	             }
	         }
			 
			return new ViewModel(array(
				'id' => $id,
				'form' => $form,
				'employee_details_id' => $this->employee_details_id,
				'applicant_details' => $applicant_details,
				'keyphrase' => $this->keyphrase,
				'message' => $message,
				));
		}else{
			return $this->redirect()->toRoute('viewtrainingapplications');
		}
	}

	
	public function nominationListAction()
	{
		$this->loginDetails();
		
	   $form = new SearchForm();

	   $message = NULL;
	   
	   $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				$empName = $this->getRequest()->getPost('employee_name');
				$empId = $this->getRequest()->getPost('emp_id');
				$department = $this->getRequest()->getPost('department');
				$employeeList = $this->trainingService->getEmployeeList($empName, $empId, $department, $this->organisation_id);
             }
         }
		 else {
			 $employeeList = array();
		 }
		
		return new ViewModel(array(
            'form' => $form,
			'employeeList' => $employeeList,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
            ));
	}
	
	public function nominateStaffTrainingAction()
	{
		$this->loginDetails();
		
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new TrainingNominationForm();
			$trainingModel = new TrainingNomination();
			$form->bind($trainingModel);
			
			//table 'training list' does not exist
			//using it as a dummy table to be able to combine short term and long term trainings
			$trainingList = $this->trainingService->listSelectData($tableName='training_list', $columnName='title');
			//var_dump($trainingList); die();
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
					 $employee_id = $trainingModel->getEmployee_Details_Id();
					 $training_detail = $trainingModel->getTraining_Detail(); 
	                 try {
						 $this->trainingService->saveTrainingNomination($trainingModel);
						 $this->sendTrainingNominationEmail($employee_id, $training_detail);
						 $this->flashMessenger()->addMessage('Nomination was successfully added');
						 $this->notificationService->saveNotification('Training Nomination', $employee_id, NULL, 'Nomination for Training');
						 $this->auditTrailService->saveAuditTrail("INSERT", "Nomination for Trainings", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('applytrainings');
					 }
					 catch(\Exception $e) {
							$message = 'Failure';
					 		$this->flashMessenger()->addMessage($e->getMessage());
					 }
	             }
	         }
			
			return new ViewModel(array(
				'employeeDetail' => $this->trainingService->findEmpDetails($id),
				'trainingList' => $trainingList,
				'employee_details_id' => $id,
				'form' => $form,
				'message' => $message,
				));
        }else{
        	 return $this->redirect()->toRoute('nominatestaff');
        }
	}
	
	public function updateStudyStatusAction()
	{
		$this->loginDetails();

		$message = NULL;
				 
		return new ViewModel(array(
			'longTermTrainingList' => $this->trainingService->getTrainingList($tableName='training_details', $this->organisation_id),
			'shortTermTrainingList' => $this->trainingService->getTrainingList($tableName='workshop_details', $this->organisation_id),
			'keyphrase' => $this->keyphrase,
			'message' => $message,
			));	
	}

	public function longTermTraineeListAction()
	{
		$this->loginDetails();

		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){

        	$updatedReportList = $this->trainingService->getUpdatedStudyReportList($id, $tableName = 'longterm_training_report');

        	$message = NULL;

		return new ViewModel(array(
			'longTermTraineeList' => $this->trainingService->getTraineeList($id, $tableName = 'emp_training_details', $this->organisation_id),
			'keyphrase' => $this->keyphrase,
			'updatedReportList' => $updatedReportList,
			'message' => $message,
			));
        }else{
        	return $this->redirect()->toRoute('updatestudystatus');
        }		
	}


	public function shortTermTraineeListAction()
	{
		$this->loginDetails();

		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){

        	$updatedReportList = $this->trainingService->getUpdatedStudyReportList($id, $tableName = 'shortterm_training_report');

        	$message = NULL;

			return new ViewModel(array(
				'shortTermTraineeList' => $this->trainingService->getTraineeList($id, $tableName = 'emp_workshop_details', $this->organisation_id),
				'keyphrase' => $this->keyphrase,
				'updatedReportList' => $updatedReportList,
				'message' => $message,
				));
        	}else{
        		return $this->redirect()->toRoute('updatestudystatus');
        }		
	}	


	public function trainingReportAction()
	{
		$this->loginDetails();
		
		//get the id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		return new ViewModel(array(
			'longTermTrainingList' => $this->trainingService->getTrainingList($tableName='training_details', $this->organisation_id),
			'shortTermTrainingList' => $this->trainingService->getTrainingList($tableName='workshop_details', $this->organisation_id)
			));	
	}
	
	public function updateTrainingReportAction()
	{
		$this->loginDetails();
		
		//get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new TrainingReportForm();
			$trainingModel = new TrainingReport();
			$form->bind($trainingModel);

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
					 $data = $form->getData();
					try {
						$this->trainingService->saveTrainingReport($trainingModel);
						$this->flashMessenger()->addMessage('Training Report was successfully added');
						$this->auditTrailService->saveAuditTrail("INSERT", "Training Report", "ALL", "SUCCESS");
						return $this->redirect()->toRoute('updatestudystatus');
					}
					catch(\Exception $e) {
							$message = 'Failure';
					 		$this->flashMessenger()->addMessage($e->getMessage());
									// Some DB Error happened, log it and let the user know
					}
				}
			}
			 
			return new ViewModel(array(
				'form' => $form,
	            //'workshop_details_id' => $id,
				'trainingDetails' => $this->trainingService->getTrainingDetails($id, $training_type='short_term', 'report'),
	            //'trainingNominations' => $this->trainingService->getTrainingNominations($id, $training_type='short_term', 'report'),
	            'checkTrainingReport' => $this->trainingService->crossCheckTrainingReport($id, $training_type='short_term'),
				'employee_details_id' => $this->employee_details_id,
				'message' => $message,
			));
        }else{
        	return $this->redirect()->toRoute('updatestudystatus');
        }
	}
        
    public function updateStudyReportAction()
	{
		$this->loginDetails();
		
		//get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new StudyReportForm();
			$trainingModel = new StudyReport();
			$form->bind($trainingModel);

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
					 $data = $form->getData();
					try {
						$this->trainingService->saveStudyReport($trainingModel);
						$this->flashMessenger()->addMessage('Study Report was successfully added');
						$this->auditTrailService->saveAuditTrail("INSERT", "Long Term Study Report", "ALL", "SUCCESS");
						return $this->redirect()->toRoute('updatestudystatus');
					}
					catch(\Exception $e) {
						$message = 'Failure';
					 		$this->flashMessenger()->addMessage($e->getMessage());
					}
				}
			}
			 
			return new ViewModel(array(
				'id' => $id,
				'form' => $form,
	            'training_details_id' => $id,
				'trainingDetails' => $this->trainingService->getTrainingDetails($id, $training_type='long_term', 'report'),
	           // 'trainingNominations' => $this->trainingService->getTrainingNominations($id, $training_type='long_term', 'report'),
				'employee_details_id' => $this->employee_details_id,
				'message' => $message,
				));
        }else{
        	return $this->redirect()->toRoute('updatestudystatus');
        }
	}
        
    public function requestStudyExtensionAction()
	{
		$this->loginDetails();
		
		//get the id
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$form = new StudyExtensionForm();
			$trainingModel = new StudyExtension();
			$form->bind($trainingModel);

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
					 $data = $form->getData();
					try {
						$this->trainingService->saveStudyExtensionRequest($trainingModel);
						$this->flashMessenger()->addMessage('Extension For Study was successfully added');
						$this->notificationService->saveNotification('Training Application', 'ALL', 'ALL', 'Request for Training Extension');
						$this->auditTrailService->saveAuditTrail("INSERT", "Training Extension Request", "ALL", "SUCCESS");
						return $this->redirect()->toRoute('updatestudystatus');
					}
					catch(\Exception $e) {
							$message = 'Failure';
					 		$this->flashMessenger()->addMessage($e->getMessage());
									// Some DB Error happened, log it and let the user know
					}
				}
			}
			 
			return new ViewModel(array(
				'form' => $form,
	            'training_details_id' => $id,
				'trainingDetails' => $this->trainingService->getTrainingDetails($id, $training_type='long_term', 'time_extension'),
	            //'trainingNominations' => $this->trainingService->getTrainingNominations($id, $training_type='long_term', 'report'),
				'employee_details_id' => $this->employee_details_id,
				'message' => $message,
				));
        }else{
        	return $this->redirect()->toRoute('updatestudystatus');
        }
	}
	
	public function viewLongTermTrainingReportAction()
	{
		$this->loginDetails();
		
		//get the id
		$id = (int) $this->params()->fromRoute('id', 0);
		$form = new TrainingReportForm();
		//need to get the training report details for HRO to view
		$trainingDetails = $this->trainingService->getTrainingReportDetails($id, $training_type='long_term');
		return new ViewModel(array(
			'form' => $form,
			'trainingDetails' => $this->trainingService->getTrainingReportDetails($id, $training_type='long_term'),
			));
	}
	
	public function viewShortTermTrainingReportAction()
	{
		$this->loginDetails();
		
		//get the id
		$id = (int) $this->params()->fromRoute('id', 0);
		$form = new TrainingReportForm();
		//need to get the training report details for HRO to view
		$trainingDetails = $this->trainingService->getTrainingReportDetails($id, $training_type='short_term');
		return new ViewModel(array(
			'form' => $form,
			'trainingDetails' => $this->trainingService->getTrainingReportDetails($id, $training_type='short_term'),
			));
	}


	//Function to send leave application email to the particular applicant supervisor
    public function sendTrainingNominationEmail($employee_details_id, $training_details)
    {
    	$this->loginDetails();

    	$nominee_details = $this->trainingService->getNomineeDetail($employee_details_id);

    	$nominee_name = NULL;
    	$nominee_email = NULL;
    	foreach($nominee_details as $details){
    		$nominee_name = $details['first_name'].' '.$details['middle_name'].' '.$details['last_name'];
    		$nominee_email = $details['email'];
    	}

 		$toEmail = $nominee_email;
        $messageTitle = "Training Nomination";
		$messageBody = "<h3>Dear ".$nominee_name."</h3>You have been nominated for training title <b>".$training_details."</b> on ".date('Y-m-d').".<br><b>Please click the link below to apply for nominated training.</b><br><u>http://rub-ims.rub.edu.bt</u><p>This is an auto-generated email and Please do not reply because this mail id is not monitored</p>";

        $this->emailService->sendMailer($toEmail, $messageTitle, $messageBody);
	 	
    }


    //Function to send email to the HR/HR Assistant, ADM
    public function sendAppliedTrainingEmail($organisation_id, $training_details_id, $type, $id)
    {
    	$this->loginDetails();
    	$authority_email = $this->trainingService->getAuthorityEmail($organisation_id);

    	$training_title = NULL;
    	$applicant_name = NULL;
    	if($type == 'longterm'){
    		$training_nomination_details = $this->trainingService->getTrainingNominationDetails($training_details_id, 'longterm', $id);

    		foreach($training_nomination_details as $details){
    			$training_title = $details['course_title'];
    			$applicant_name = $details['first_name'].' '.$details['middle_name'].' '.$details['last_name'];
    		}
    	}else if($type == 'shortterm'){
    		$training_nomination_details = $this->trainingService->getTrainingNominationDetails($training_details_id, 'shortterm', $id);
    		foreach($training_nomination_details as $details){
    			$training_title = $details['title'];
    			$applicant_name = $details['first_name'].' '.$details['middle_name'].' '.$details['last_name'];
    		}
    	} 

    	foreach($authority_email as $email){
    		$toEmail = $email;
	        $messageTitle = "Training Application";
			$messageBody = "Dear Sir/Madam<br><b>".$applicant_name."</b> have applied for training title <b>".$training_title."</b> on ".date('Y-m-d').".<br><b>Please click the link below to apply for necessary action.</b><br><u>http://rub-ims.rub.edu.bt</u><p>This is an auto-generated email and Please do not reply because this mail id is not monitored</p>";

	        $this->emailService->sendMailer($toEmail, $messageTitle, $messageBody);
    	}

    }

	
	public function downloadLongTermApplicationAction() 
	{
		//get the param from the view file
		$file_location = $this->params()->fromRoute('filename',0);
		
		//extract the training id and column name
		$column_name = preg_replace('!\d+!', '', $file_location);
		$column_name = rtrim($column_name,"_");
		preg_match_all('!\d+!',$file_location, $id);
		$training_id = implode(' ', $id[0]);
		
		//get the location of the file from the database		
		$fileArray = $this->trainingService->getFileName($training_id, $column_name, $training_type='long_term');
		$file;
		foreach($fileArray as $set){
			$file = $set[$column_name];
		}
		
		$mimetype = mime_content_type($file);
		$response = new Stream();
		$response->setStream(fopen($file, 'r'));
		$response->setStatusCode(200);
		$response->setStreamName(basename($file));
		$headers = new Headers();
		$headers->addHeaderLine('Content-Type', $mimetype)
				->addHeaderLine('Content-Disposition: inline', 'attachment; filename="' . basename($file) .'"')
				->addHeaderLine('Content-Length', filesize($file))
				->addHeaderLine('Expires', '@0')
				->addHeaderLine('Cache-Control', 'must-revalidate')
				->addHeaderLine('Pragma', 'public')
				->addHeaderLine('Content-Transfer-Encoding: binary')
  				->addHeaderLine('Accept-Ranges: bytes');

		$response->setHeaders($headers);
		return $response;
	}
	
	public function downloadShortTermApplicationAction() 
	{
		//get the param from the view file
		$file_location = $this->params()->fromRoute('filename',0);
		
		//extract the training id and column name
		$column_name = preg_replace('!\d+!', '', $file_location);
		$column_name = rtrim($column_name,"_");
		preg_match_all('!\d+!',$file_location, $id);
		$training_id = implode(' ', $id[0]);
		
		//get the location of the file from the database		
		$fileArray = $this->trainingService->getFileName($training_id, $column_name, $training_type='short_term');
		$file;
		foreach($fileArray as $set){
			$file = $set[$column_name];
		}
		
		$mimetype = mime_content_type($file);
		$response = new Stream();
		$response->setStream(fopen($file, 'r'));
		$response->setStatusCode(200);
		$response->setStreamName(basename($file));
		$headers = new Headers();
		$headers->addHeaderLine('Content-Type', $mimetype)
				->addHeaderLine('Content-Disposition: inline', 'attachment; filename="' . basename($file) .'"')
				->addHeaderLine('Content-Length', filesize($file))
				->addHeaderLine('Expires', '@0')
				->addHeaderLine('Cache-Control', 'must-revalidate')
				->addHeaderLine('Pragma', 'public')
				->addHeaderLine('Content-Transfer-Encoding: binary')
  				->addHeaderLine('Accept-Ranges: bytes');

		/*$headers->addHeaders(array(
			'Content-Disposition' => 'attachment; filename="' . basename($file) .'"',
			'Content-Type' => 'application/octet-stream',
			'Content-Length' => filesize($file),
			'Expires' => '@0', // @0, because zf2 parses date as string to \DateTime() object
			'Cache-Control' => 'must-revalidate',
			'Pragma' => 'public'
		));*/

		$response->setHeaders($headers);
		return $response;
	}
	
	
	public function downloadTrainingDocumentsAction() 
	{ //echo 'test'; die();
		//get the param from the view file
		$file_location = $this->params()->fromRoute('filename',0);
		$category = $this->params()->fromRoute('category');
		
		if(is_numeric($file_location)){
			$id = $file_location;
			$column_name = 'nomination_evidence_file';
		}else{
			//extract the training id and column name
			$column_name = preg_replace('!\d+!', '', $file_location);
			$column_name = rtrim($column_name,"_");
			preg_match_all('!\d+!',$file_location, $id);
			$training_id = implode(' ', $id[0]);
			
			$id = $this->trainingService->getTrainingNominationId($training_id, $category, $this->employee_details_id);
		}
		
		//get the location of the file from the database		
		$fileArray = $this->trainingService->getFileName($id, $column_name, $training_type='nomination');
		$file;
		foreach($fileArray as $set){
			$file = $set[$column_name];
		} //echo $file; die();
		
		$mimetype = mime_content_type($file);
		$response = new Stream();
		$response->setStream(fopen($file, 'r'));
		$response->setStatusCode(200);
		$response->setStreamName(basename($file));
		$headers = new Headers();
		$headers->addHeaderLine('Content-Type', $mimetype)
				->addHeaderLine('Content-Disposition: inline', 'attachment; filename="' . basename($file) .'"')
				->addHeaderLine('Content-Length', filesize($file))
				->addHeaderLine('Expires', '@0')
				->addHeaderLine('Cache-Control', 'must-revalidate')
				->addHeaderLine('Pragma', 'public')
				->addHeaderLine('Content-Transfer-Encoding: binary')
  				->addHeaderLine('Accept-Ranges: bytes');

		$response->setHeaders($headers);
		return $response;
	}


	public function extractShortTermTrainingData($data)
    {
        $evaluationData = array();
        
        foreach($data as $key=>$value)
        {
            $evaluationData[$value]= $this->getRequest()->getPost('trainee_'.$value);
        }
        return $evaluationData;
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

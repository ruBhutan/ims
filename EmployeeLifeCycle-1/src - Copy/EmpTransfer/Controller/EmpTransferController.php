<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace EmpTransfer\Controller;

use EmpTransfer\Service\EmpTransferServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use EmpTransfer\Form\EmpTransferForm;
use EmpTransfer\Form\UpdateTransferForm;
use EmpTransfer\Form\OvcTransferApprovalForm;
use EmpTransfer\Form\JoiningReportForm;
use EmpTransfer\Model\EmpTransfer;
use EmpTransfer\Model\OvcTransferApproval;
use EmpTransfer\Model\JoiningReport;
use Zend\Session\Container;
use Zend\Http\Response\Stream;
use Zend\Http\Headers;

/**
 * Description of IndexController
 *
 */
 
class EmpTransferController extends AbstractActionController
{
	protected $transferService;
	protected $notificationService;
	protected $auditTrailService;
	protected $serviceLocator;
	protected $username;
	protected $userrole;
	protected $usertype;
	protected $userregion;
	protected $userDetails;
	 protected $userImage;
	protected $employee_details_id;
	protected $organisation_id;
	protected $keyphrase = "RUB_IMS";
	
	public function __construct(EmpTransferServiceInterface $transferService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->transferService = $transferService;
		$this->notificationService = $notificationService;
        $this->auditTrailService = $auditTrailService;
		$this->serviceLocator = $serviceLocator;
		
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
		
		$empData = $this->transferService->getEmployeeDetailsId($this->username);
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
		}
		
		//get the organisation id
		$organisationID = $this->transferService->getOrganisationId($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}
		
		$this->userDetails = $this->transferService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->transferService->getUserImage($this->username, $this->usertype);
	}
	
	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }
    
    public function applyTransferAction()
    {
		$this->loginDetails();
		
        $form = new EmpTransferForm();
        $transferModel = new EmpTransfer();
        $form->bind($transferModel);

        //$transferCategories = $this->transferService->listAll($tableName='emp_transfer_category');
        $organisations = $this->transferService->listSelectData($tableName = 'organisation', $columnName='organisation_name');

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
				 //get the notifiation details
				 $to_organisation = $transferModel->getTransfer_Request_To();
				 $transfer_from_approval_authority = $this->transferService->getNotificationDetails($this->organisation_id);
				 $transfer_to_approval_authority = $this->transferService->getNotificationDetails($to_organisation);

				 $check_transfer = $this->transferService->crossCheckEmpTransfer($this->employee_details_id, 'pending', 'pending');
				 $check_from_transfer = $this->transferService->crossCheckEmpTransfer($this->employee_details_id, 'pending', 'Approved');
				 $check_to_transfer = $this->transferService->crossCheckEmpTransfer($this->employee_details_id, 'Approved', 'pending');
				 if($check_transfer){
				 	$message = 'Failure';
				 	$this->flashMessenger()->addMessage("You can't apply for the transfer since you have already applied for transfer and it is still pending from both");
				 }else if($check_from_transfer || $check_to_transfer){
				 	$message = 'Failure';
				 	$this->flashMessenger()->addMessage("You can't apply for the transfer since you have already applied for transfer and it is still pending from one side.");
				 }else{
					 	try {
						 $this->transferService->save($transferModel);
						 $this->flashMessenger()->addMessage('Transfer Application was successfully submitted');
						 $this->notificationService->saveNotification('Transfer Application', $transfer_from_approval_authority, NULL , 'Application for Transfer');
						 $this->auditTrailService->saveAuditTrail("INSERT", "Employee Transfer Application", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('stafftransferstatus');
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
			'spouseDetails' => $this->transferService->getEmployeeSpouseDetails('3', $this->employee_details_id),
			'employee_details_id' => $this->employee_details_id,
			'organisations' => $organisations,
			'message' => $message,
			);
    }
	
    public function viewTransferFromDetailAction()
    {
		$this->loginDetails();
		
        //get the transfer id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new EmpTransferForm();
		
			$transferDetail = $this->transferService->findTransferDetails($id);
			$organisations = $this->transferService->listSelectData($tableName = 'organisation', $columnName='organisation_name');
			 
			return array(
				'form' => $form,
				'id' => $id,
				'transfer_applicantion_id' => $id,
				'transferDetail' => $transferDetail,
				'spouseDetails' => $this->transferService->getTransferedEmpSpouseDetails('3', $id),
				'organisations' => $organisations,
				'keyphrase' => $this->keyphrase,
				);
		}
		else {
			return $this->redirect()->toRoute('transferapplicationstatus');
		}
		
    }
	
    //to get the list of transfer applications
    public function transferToApprovalAction()
    {
		$this->loginDetails();
		
        $form = new EmpTransferForm();
		$transferModel = new EmpTransfer();
		$form->bind($transferModel);
		
		$transferList = $this->transferService->getTransferApprovalList($type='transfer_to', $this->organisation_id, $userrole = $this->userrole);
		
		//the details of employees that have applied for transfer
		$transferEmployee = $this->transferService->getTransferEmployee();
		$message = NULL;
        		 
        return array(
			'form' => $form,
			'keyphrase' => $this->keyphrase,
			'transferList' => $transferList,
			'transferEmployee' => $transferEmployee,
			'message' => $message);
    }


    public function viewTransferToDetailAction()
    {
    	$this->loginDetails();
		
        //get the transfer id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			$form = new EmpTransferForm();
		
			$transferDetail = $this->transferService->findTransferDetails($id);
			$organisations = $this->transferService->listSelectData($tableName = 'organisation', $columnName='organisation_name');
			 
			return array(
				'form' => $form,
				'id' => $id,
				'transferDetail' => $transferDetail,
				'spouseDetails' => $this->transferService->getTransferedEmpSpouseDetails('3', $id),
				'organisations' => $organisations,
				'keyphrase' => $this->keyphrase,
				);
		}
		else {
			return $this->redirect()->toRoute('transferapplicationstatus');
		}
    }


    public function downloadEmpTransferDocumentAction()
    {
    	//get the param from the view file
		$file_location = $this->params()->fromRoute('filename',0);
		
		//extract the training id and column name
		$column_name = preg_replace('!\d+!', '', $file_location);
		$column_name = rtrim($column_name,"_");
		preg_match_all('!\d+!',$file_location, $id);
		$application_id = implode(' ', $id[0]);
		
		//get the location of the file from the database		
		$fileArray = $this->transferService->getEmpTransferFileName($application_id, $column_name);
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
	
	//to approve transfer
	public function toCollegeApprovalAction()
	{
		$this->loginDetails();
		
		//get the transfer id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			//To get the details for the notification
			$employee_id = NULL;
			$transferApplicantDetails = $this->transferService->getTransferApplicantDetail($id);
			$employee_id = $transferApplicantDetails['employee_details_id'];
			
			try {
				 $this->transferService->updateTransferStatus($id, $status = 'Approved', $type = 'to_org_transfer_status');
				 $this->flashMessenger()->addMessage('Transfer Application was approved');
				 $this->notificationService->saveNotification('Transfer Approval', $employee_id, NULL, 'Transfer Approval');
				 $this->auditTrailService->saveAuditTrail("Update", "Approval of Employee Transfer", "ALL", "SUCCESS");
				 return $this->redirect()->toRoute('transfertoapproval');
			 }
			 catch(\Exception $e) {
					 die($e->getMessage());
					 // Some DB Error happened, log it and let the user know
			 }
		}
		else {
			return $this->redirect()->toRoute('transfertoapproval');
		}
	}
	
	//to reject transfer
	public function toCollegeRejectAction()
	{
		$this->loginDetails();
		
		//get the transfer id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			//To get the details for the notification
			$employee_id = NULL;
			$transferApplicantDetails = $this->transferService->getTransferApplicantDetail($id);
			$employee_id = $transferApplicantDetails['employee_details_id'];
			
			try {
				 $this->transferService->updateTransferStatus($id, $status = 'Rejected', $type = 'to_org_transfer_status');
				 $this->flashMessenger()->addMessage('Transfer Application was rejected');
				 $this->notificationService->saveNotification('Transfer Rejected', $employee_id, NULL, 'Transfer Rejected');
				 $this->auditTrailService->saveAuditTrail("Update", "Rejection of Employee Transfer", "ALL", "SUCCESS");
				 return $this->redirect()->toRoute('transfertoapproval');
			 }
			 catch(\Exception $e) {
					 die($e->getMessage());
					 // Some DB Error happened, log it and let the user know
			 }
		}
		else {
			return $this->redirect()->toRoute('transfertoapproval');
		}
	}
	
    public function transferFromApprovalAction()
    {
		$this->loginDetails();
		
        $form = new EmpTransferForm();
		$transferModel = new EmpTransfer();
		$form->bind($transferModel);
		
		$transferList = $this->transferService->getTransferApprovalList($type='transfer_from', $this->organisation_id, $userrole = $this->userrole);
		
		//the details of employees that have applied for transfer
		$transferEmployee = $this->transferService->getTransferEmployee();
		$message = NULL;
        		 
            return array(
			'form' => $form,
			'keyphrase' => $this->keyphrase,
			'transferList' => $transferList,
			'transferEmployee' => $transferEmployee,
			'message' => $message);
    }
	
	public function fromCollegeApprovalAction()
	{
		$this->loginDetails();
		
		//get the transfer id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			//To get the details for the notification
			$employee_id = NULL;
			$transferApplicantDetails = $this->transferService->getTransferApplicantDetail($id);
			$employee_id = $transferApplicantDetails['employee_details_id'];
			
			try {
				 $this->transferService->updateTransferStatus($id, $status = 'Approved', $type = 'from_org_transfer_status');
				 $this->flashMessenger()->addMessage('Transfer Application was approved');
				 $this->notificationService->saveNotification('Transfer Approval', $employee_id, NULL, 'Transfer Approval');
				 $this->auditTrailService->saveAuditTrail("Update", "Approval of Employee Transfer", "ALL", "SUCCESS");
				 return $this->redirect()->toRoute('transferfromapproval');
			 }
			 catch(\Exception $e) {
					 die($e->getMessage());
					 // Some DB Error happened, log it and let the user know
			 }
		}
		else {
			return $this->redirect()->toRoute('transferfromapproval');
		}
	}
	
	public function fromCollegeRejectAction()
	{
		$this->loginDetails();
		
		//get the transfer id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			//To get the details for the notification
			$employee_id = NULL;
			$transferApplicantDetails = $this->transferService->getTransferApplicantDetail($id);
			$employee_id = $transferApplicantDetails['employee_details_id'];
			
			try {
				 $this->transferService->updateTransferStatus($id, $status = 'Rejected', $type = 'from_org_transfer_status');
				 $this->flashMessenger()->addMessage('Transfer Application was rejected');
				 $this->notificationService->saveNotification('Transfer Rejected', $employee_id, NULL, 'Transfer Rejected');
				 $this->auditTrailService->saveAuditTrail("Update", "Rejection of Employee Transfer", "ALL", "SUCCESS");
				 return $this->redirect()->toRoute('transferfromapproval');
			 }
			 catch(\Exception $e) {
					 die($e->getMessage());
					 // Some DB Error happened, log it and let the user know
			 }
		}
		else {
			return $this->redirect()->toRoute('transferfromapproval');
		}
	}
	
	public function transferApplicationStatusAction()
	{
		$this->loginDetails();
		
		$approvedTransferList = $this->transferService->getTransferList(NULL, $this->organisation_id, NULL);
		$pendingTransferList = $this->transferService->getTransferList($type='pending', $this->organisation_id, NULL);
		$organisationList = $this->transferService->listSelectData($tableName='organisation', $columnName='organisation_name');
		
		//the details of employees that have applied for transfer
		$transferEmployee = $this->transferService->getTransferEmployee();
		
		$message = NULL;
        		 
        return array(
			'approvedTransferList' => $approvedTransferList,
			'pendingTransferList' => $pendingTransferList,
			'organisationList' => $organisationList,
			'message' => $message);
	}
	
	public function approvedTransfersAction()
	{
		$this->loginDetails();
		
		//$dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
		$form = new UpdateTransferForm($this->serviceLocator);

		$message = NULL;
		
		$approvedTransferList = $this->transferService->getTransferList($type='Approved', $this->organisation_id, NULL);
		$organisationList = $this->transferService->listSelectData($tableName='organisation', $columnName='organisation_name');
		
        return array(
			'form' => $form,
			'approvedTransferList' => $approvedTransferList,
			'organisationList' => $organisationList,
			'keyphrase' => $this->keyphrase,
			'organisation_id' => $this->organisation_id,
			'message' => $message,
			);
	}
	
	public function updateTransferApplicantAction()
	{
		$this->loginDetails();
		
		//get the transfer id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);
		
		if(is_numeric($id)){
			//$dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
			$form = new UpdateTransferForm($this->serviceLocator);
			
			$applicant_details = $this->transferService->getTransferApplicantDetail($id);
			$employee_id = $applicant_details['employee_details_id'];
			
			$personalDetails = $this->transferService->getPersonalDetails($employee_id);
			$employmentDetails = $this->transferService->getEmploymentDetails($employee_id);
			$approvedTransferList = $this->transferService->getTransferList($type='Approved', $this->organisation_id, NULL);
			$organisationList = $this->transferService->listSelectData($tableName='organisation', $columnName='organisation_name');

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
					$requesting_agency = $this->transferService->getTransferRequestAgency($id);
					$data = $form->getData();
					$new_working_agency = $data['new_working_agency'];
					if($requesting_agency != $new_working_agency){
						$message = 'Failure';
						$this->flashMessenger()->addMessage("You can't update the approved transfered staff since you have selected the organisation that the staff requested and approved");
					}
					try {
						$this->transferService->saveTransferApplicantDetails($data);
						$this->flashMessenger()->addMessage('Transfer Application was successfully updated');
						$this->auditTrailService->saveAuditTrail("INSERT", "Updating Employee Transfer", "ALL", "SUCCESS");
						return $this->redirect()->toRoute('approvedtransfers');
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
				'id' => $id,
				'employee_id' => $employee_id,
				'approvedTransferList' => $approvedTransferList,
				'organisationList' => $organisationList,
				'personalDetails' => $personalDetails,
				'employmentDetails' => $employmentDetails,
				'organisation_id' => $this->organisation_id,
				'message' => $message,
				);
		}else{
			return $this->redirect()->toRoute('approvedtransfers');
		}
		
	}
	
	public function ovcTransferApprovalListAction()
	{
		$this->loginDetails();
				
		$approvedTransferList = $this->transferService->getTransferList($type='ovc_approval', $this->organisation_id, NULL);
		$organisationList = $this->transferService->listSelectData($tableName='organisation', $columnName='organisation_name');
		
        return array(
			'approvedTransferList' => $approvedTransferList,
			'organisationList' => $organisationList
			);
	}
	
	public function ovcTransferApprovalAction()
	{
		$this->loginDetails();
		
		//get the transfer id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$form = new OvcTransferApprovalForm();
		$transferModel = new OvcTransferApproval();
		$form->bind($transferModel);
		
		$applicant_details = $this->transferService->getTransferApplicantDetail($id);
		$employee_id = $applicant_details['employee_details_id'];
		
		$transferDetails = $this->transferService->findTransferDetails($id);
		$personalDetails = $this->transferService->getPersonalDetails($employee_id);
		$employmentDetails = $this->transferService->getEmploymentDetails($employee_id);
		
		//for displaying transfer details
		$organisationList = $this->transferService->listSelectData('organisation', 'organisation_name');
		$positionCategoryList = $this->transferService->listSelectData('position_category', 'category');
		$positionLevelList = $this->transferService->listSelectData('position_level', 'position_level');
		$positionTitleList = $this->transferService->listSelectData('position_title', 'position_title');
		
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
					$this->transferService->saveOvcTransferApproval($transferModel);
					$this->flashMessenger()->addMessage('Joining Report was successfully updated');
					$this->auditTrailService->saveAuditTrail("INSERT", "Updating Joining Report", "ALL", "SUCCESS");
					return $this->redirect()->toRoute('ovctransferapprovallist');
				}
				catch(\Exception $e) {
					die($e->getMessage());
					// Some DB Error happened, log it and let the user know
				}
			}
		}

	   return array(
			'form' => $form,
			'transferDetails' => $transferDetails,
			'personalDetails' => $personalDetails,
			'employmentDetails' => $employmentDetails,
			'positionCategoryList' => $positionCategoryList,
			'positionLevelList' => $positionLevelList,
			'positionTitleList' => $positionTitleList,
			'organisationList' => $organisationList
			);
	}
	
	public function ovcTransferApprovedAction()
	{
		$this->loginDetails();
				
		$approvedTransferList = $this->transferService->getTransferList($type='ovc_approved', $this->organisation_id, NULL);
		$organisationList = $this->transferService->listSelectData($tableName='organisation', $columnName='organisation_name');
		
        return array(
			'approvedTransferList' => $approvedTransferList,
			'organisationList' => $organisationList
			);
	}
	
	public function transferJoiningReportAction()
	{
		$this->loginDetails();
		
		//get the transfer id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$form = new JoiningReportForm();
		$transferModel = new JoiningReport();
		$form->bind($transferModel);
		
		$applicant_details = $this->transferService->getTransferApplicantDetail($id);
		$employee_id = $applicant_details['employee_details_id'];
		
		$transferDetails = $this->transferService->findTransferDetails($id);
		$personalDetails = $this->transferService->getPersonalDetails($employee_id);
		$employmentDetails = $this->transferService->getEmploymentDetails($employee_id);
		
		//for displaying transfer details
		$organisationList = $this->transferService->listSelectData('organisation', 'organisation_name');
		$positionCategoryList = $this->transferService->listSelectData('position_category', 'category');
		$positionLevelList = $this->transferService->listSelectData('position_level', 'position_level');
		$positionTitleList = $this->transferService->listSelectData('position_title', 'position_title');
		
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
					$this->transferService->saveJoiningReport($transferModel);
					$this->flashMessenger()->addMessage('Transfer Application was successfully updated');
					$this->auditTrailService->saveAuditTrail("INSERT", "Updating Employee Transfer", "ALL", "SUCCESS");
					return $this->redirect()->toRoute('approvedtransfers');
				}
				catch(\Exception $e) {
					die($e->getMessage());
					// Some DB Error happened, log it and let the user know
				}
			}
		}

	   return array(
			'form' => $form,
			'transferDetails' => $transferDetails,
			'personalDetails' => $personalDetails,
			'employmentDetails' => $employmentDetails,
			'positionCategoryList' => $positionCategoryList,
			'positionLevelList' => $positionLevelList,
			'positionTitleList' => $positionTitleList,
			'organisationList' => $organisationList
			);
	}
	
	private function my_decrypt($data, $key) 
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
    
}

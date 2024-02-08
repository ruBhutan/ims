<?php


namespace OrgSettings\Controller;

use Zend\ServiceManager\ServiceLocatorInterface;
use OrgSettings\Service\OrgSettingsServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use OrgSettings\Model\Organisation;
use OrgSettings\Model\OrganisationDocuments;
use OrgSettings\Form\OrganisationForm;
use OrgSettings\Form\DepartmentForm;
use OrgSettings\Form\UnitForm;
use OrgSettings\Form\OrganisationDocumentsForm;
use OrgSettings\Form\OrganisationDocumentsSearchForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;

use Zend\Http\Response\Stream;
use Zend\Http\Headers;

use DOMPDFModule\View\Model\PdfModel;
 
  
class OrgSettingsController extends AbstractActionController
{
    protected $settingsService;
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
	
	public function __construct(OrgSettingsServiceInterface $settingsService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->settingsService = $settingsService;
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
		
		//get the user details such as name
		$user_details = $this->settingsService->getUserDetails($this->username, $this->usertype);
		foreach($user_details as $details){
			$this->userDetails = $details['first_name'].' '.$details['middle_name'].' '.$details['last_name'];
			$this->organisation_id = $details['organisation_id'];
		}
        $this->userImage = $this->settingsService->getUserImage($this->username, $this->usertype);
		
	}
	
	public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }
	
	public function organisationSettingsAction()
    {
		$this->loginDetails();
		
   		// /$dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
		
		$orgForm = new OrganisationForm();
		$deptForm = new DepartmentForm();
		$unitForm = new UnitForm($this->serviceLocator);
		
		$organisations = $this->settingsService->listAll($tableName = 'organisation');
		$departments = $this->settingsService->listAll($tableName = 'departments');
		$units = $this->settingsService->listAll($tableName = 'department_units');
		
		$organisationList = $this->settingsService->listSelectData($tableName = 'organisation', $columnName='organisation_name');
		$departmentList = $this->settingsService->listSelectData($tableName = 'departments', $columnName='department_name');
		
		$message = NULL;

         return array(
             'orgForm' => $orgForm,
			 'deptForm' => $deptForm,
			 'unitForm' => $unitForm,
			 'organisations' => $organisations,
			 'departments' => $departments,
			 'units' => $units,
			 'organisationList' => $organisationList,
			 'departmentList' => $departmentList,
			 'keyphrase' => $this->keyphrase,
			 'message' => $message
         );
     }
	 
	 public function addOrganisationAction()
     {
		 $this->loginDetails();
		 
        $form = new OrganisationForm();
		$settingsModel = new Organisation();
		$form->bind($settingsModel);

		$message = NULL;

         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->settingsService->save($settingsModel);
					 $this->auditTrailService->saveAuditTrail("INSERT", "New Organisation Added", "ALL", "SUCCESS");
					 $this->flashMessenger()->addMessage('Organisations was successfully added');
					 return $this->redirect()->toRoute('orgsettings');
				 }
				 catch(\Exception $e) {
						die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
     }
	 
	 public function viewOrganisationAction()
     {
		 $this->loginDetails();
		 
        //get the id of the hrd proposal
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$organisations = $this->settingsService->listAll($tableName = 'organisation');
		
		$form = new OrganisationForm();
		$settingsModel = new Organisation();
		$form->bind($settingsModel);

         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->settingsService->save($settingsModel);
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
			 'organisations' => $organisations
         );
     }
	 
	 public function editOrganisationAction()
     {
		 $this->loginDetails();
		 
        //get the id of the organisation
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$organisationDetail = $this->settingsService->findDetails($id, $tableName = 'organisation');
			$organisations = $this->settingsService->listAll($tableName = 'organisation');
			
			$form = new OrganisationForm();
			$settingsModel = new Organisation();
			$form->bind($settingsModel);

	         $request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) {
	                 try {
						 $this->settingsService->save($settingsModel);
						 $this->auditTrailService->saveAuditTrail("UPDATE", "Organisation was Edited", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage('Organisations was successfully edited');
					 	return $this->redirect()->toRoute('orgsettings');
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
				 'organisationDetail' => $organisationDetail,
				 'organisations' => $organisations
	         );
        }else{
        	return $this->redirect()->toRoute('orgsettings');
        }
     }
	 
	 public function addDepartmentAction()
     {
		 $this->loginDetails();
		 
		$form = new DepartmentForm();
		$settingsModel = new Organisation();
		$form->bind($settingsModel);

         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->settingsService->save($settingsModel);
					 $this->auditTrailService->saveAuditTrail("INSERT", "New Department Added", "ALL", "SUCCESS");
					 $this->flashMessenger()->addMessage('Department was successfully added');
					 return $this->redirect()->toRoute('orgsettings');
				 }
				 catch(\Exception $e) {
						die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
     }
	 
	 public function viewDepartmentAction()
     {
		 $this->loginDetails();
		 
        //get the id of the hrd proposal
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$departmentDetail = $this->settingsService->findDetails($id, $tableName = 'departments');
		$departments = $this->settingsService->listAll($tableName = 'departments');
		
		$form = new DepartmentForm();
		$settingsModel = new Organisation();
		$form->bind($settingsModel);

         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->settingsService->save($settingsModel);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }

         return array(
             'form' => $form,
			 'departmentDetail' => $departmentDetail,
			 'departments' => $departments
         );
     }
	 
	 public function editDepartmentAction()
     {
		 $this->loginDetails();
		 
        //get the id of the department
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){ 
        	$organisationList = $this->settingsService->listSelectData($tableName = 'organisation', $columnName='organisation_name');
			$departmentDetail = $this->settingsService->findDetails($id, $tableName = 'departments');
			$departments = $this->settingsService->listAll($tableName = 'departments');
			
			$form = new DepartmentForm();
			$settingsModel = new Organisation();
			$form->bind($settingsModel);

	         $request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) {
	                 try {
						 $this->settingsService->save($settingsModel);
						 $this->auditTrailService->saveAuditTrail("EDIT", "Department Edited", "ALL", "SUCCESS");
						 $this->flashMessenger()->addMessage('Department was successfully edited');
						 return $this->redirect()->toRoute('orgsettings');
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
				 'departmentDetail' => $departmentDetail,
				 'departments' => $departments,
				 'organisationList' => $organisationList
	         );
        }else{
        	return $this->redirect()->toRoute('orgsettings');
        }
     }
	 
	 public function addUnitAction()
     {
		 $this->loginDetails();
		 
		//$dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
		
		$form = new UnitForm($this->serviceLocator);
		$settingsModel = new Organisation();
		$form->bind($settingsModel);

         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->settingsService->save($settingsModel);
					 $this->flashMessenger()->addMessage('Unit was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "New Unit Added", "ALL", "SUCCESS");
					 return $this->redirect()->toRoute('orgsettings');
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
             'keyphrase' => $this->keyphrase
         );
     }
	 
	 public function viewUnitAction()
     {
		 $this->loginDetails();
		 
        //get the id of the unit
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){
        	$unitDetail = $this->settingsService->findDetails($id, $tableName = 'department_unit');
			$units = $this->settingsService->listAll($tableName = 'department_unit');
					
			$form = new UnitForm();
			$settingsModel = new Organisation();
			$form->bind($settingsModel);

	         $request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) {
	                 try {
						 $this->settingsService->save($settingsModel);
						 $this->flashMessenger()->addMessage('Unit was successfully edited');
					 $this->auditTrailService->saveAuditTrail("UPDATE", "New Unit Added", "ALL", "SUCCESS");
					 return $this->redirect()->toRoute('orgsettings');
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
				 'unitDetail' => $unitDetail,
				 'units' => $units
	         );
        }else{
        	return $this->redirect()->toRoute('orgsettings');
        }
     }
	 
	 public function editUnitAction()
     {
		 $this->loginDetails();
		 
        //get the id of the unit
		$id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if(is_numeric($id)){ 
			$organisationList = $this->settingsService->listSelectData($tableName = 'organisation', $columnName='organisation_name');
			$departmentList = $this->settingsService->listSelectData($tableName = 'departments', $columnName='department_name');
			$unitDetail = $this->settingsService->findDetails($id, $tableName = 'department_units');
			$units = $this->settingsService->listAll($tableName = 'department_units');

			$form = new UnitForm($this->serviceLocator);
			$settingsModel = new Organisation();
			$form->bind($settingsModel);

	         $request = $this->getRequest();
	         if ($request->isPost()) {
	             $form->setData($request->getPost());
	             if ($form->isValid()) {
	                 try {
						 $this->settingsService->save($settingsModel);
						 $this->flashMessenger()->addMessage('Unit was successfully edited');
						 $this->auditTrailService->saveAuditTrail("UPDATE", "New Unit Added", "ALL", "SUCCESS");
						 return $this->redirect()->toRoute('orgsettings');
					 }
					 catch(\Exception $e) {
							 die($e->getMessage());
							 // Some DB Error happened, log it and let the user know
					 }
	             }
	         }

	         return array(
	             'form' => $form,
				 'unitDetail' => $unitDetail,
				 'units' => $units,
				 'departmentList' => $departmentList,
				 'organisationList' => $organisationList
	         );
	     }else{
	     	 return $this->redirect()->toRoute('orgsettings');
	     }
	 }
	 
	 public function addOrganisationImageAction()
	 {
		$this->loginDetails();

		$document_type = NULL;
		$organisation_details = NULL;
		$uploadedImage = NULL;
		
		$form = new OrganisationDocumentsSearchForm();

		$addForm = new OrganisationDocumentsForm();

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
					$document_type = $this->getRequest()->getPost('document_type');
					$organisation_details = $this->settingsService->getUploadeOrganisationDocument('organisation', NULL, $this->organisation_id);
					$uploadedImage = $this->settingsService->getUploadeOrganisationDocument('organisation_document', $document_type, $this->organisation_id);

				}
			}
		
		return new ViewModel(array(
			//'id' => $id,
			'form' => $form,
			'addForm' => $addForm,
			'document_type' => $document_type,
			'organisation_details' => $organisation_details,
			'uploadedImage' => $uploadedImage,
			'message' => $message,
			'keyphrase' => $this->keyphrase,
			'organisation_id' => $this->organisation_id
			));
	 }

	 public function insertOrganisationDocumentAction()
	 {
		$this->loginDetails();

		$addForm = new OrganisationDocumentsForm();
		$settingsModel = new OrganisationDocuments();
		$addForm->bind($settingsModel);

		$request = $this->getRequest();
			if($request->isPost()){
				$addForm->setData($request->getPost());
             	$data = array_merge_recursive(
					$request->getPost()->toArray(),
					$request->getFiles()->toArray()
				); 
				$addForm->setData($data); 
				if($addForm->isValid()){ 
					$data = $addForm->getData(); 
					try{
						$this->settingsService->insertOrganisationDocument($settingsModel, $this->organisation_id);
						$this->auditTrailService->saveAuditTrail("UPDATE", "Organisation Document", "ALL", "SUCCESS");
						$this->flashMessenger()->addMessage('Organisation Image was successfully updated');
						return $this->redirect()->toRoute('addorganisationimage');
					}
					catch(\Exception $e) {
						$message = 'Failure';
						$this->flashMessenger()->addMessage($e->getMessage());
						// Some DB Error happened, log it and let the user know
                	} 
			}
		}

		return array(
			'addForm' => $addForm,
			'document_type' => $document_type,
			'keyphrase' => $this->keyphrase,
			'organisation_id' => $this->organisation_id,
		);
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
             
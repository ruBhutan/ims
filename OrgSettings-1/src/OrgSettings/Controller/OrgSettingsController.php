<?php


namespace OrgSettings\Controller;

use OrgSettings\Service\OrgSettingsServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use OrgSettings\Model\Organisation;
use OrgSettings\Form\OrganisationForm;
use OrgSettings\Form\DepartmentForm;
use OrgSettings\Form\UnitForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
 
  
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
		$this->userDetails = $this->settingsService->getUserDetails($this->username, $this->usertype);
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
					 $this->flashMessenger()->addMessage('Organisations was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "New Organisation Added", "ALL", "SUCCESS");
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
             'keyphrase' => $keyphrase,
             'message' => $message,
         );
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
					 $this->flashMessenger()->addMessage('Department was successfully added');
					 $this->auditTrailService->saveAuditTrail("INSERT", "New Department Added", "ALL", "SUCCESS");
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
             'keyphrase' => $this->keyphrase,
         );
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
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$organisationList = $this->settingsService->listSelectData($tableName = 'organisation', $columnName='organisation_name');
		$departmentList = $this->settingsService->listSelectData($tableName = 'departments', $columnName='department_name');
		$unitDetail = $this->settingsService->findDetails($id, $tableName = 'department_units');
		$units = $this->settingsService->listAll($tableName = 'department_units');

		$form = new UnitForm();
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
			 'unitDetail' => $unitDetail,
			 'units' => $units,
			 'departmentList' => $departmentList,
			 'organisationList' => $organisationList
         );
     }
        
}
             
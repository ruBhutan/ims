<?php


namespace EmpTravelAuthorization\Controller;

use EmpTravelAuthorization\Service\EmpTravelAuthorizationServiceInterface;
use EmpTravelAuthorization\Model\EmpTravelAuthorization;
use EmpTravelAuthorization\Form\EmpTravelAuthorizationForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
 
  
class EmpTravelAuthorizationController extends AbstractActionController
{
    protected $empTravelAuthorizationService;
	protected $employee_details_id;
	protected $organisation_id;
	
	public function __construct(EmpTravelAuthorizationServiceInterface $empTravelAuthorizationService)
	{
		$this->empTravelAuthorizationService = $empTravelAuthorizationService;
		
		/*
		 * To retrieve the user name from the session
		*/
		$user_session = new Container('user');
        $this->username = $user_session->username;
		
		/*
		* Getting the student_id/employee_details_id related to username
		*/
		
		$empData = $this->empTravelAuthorizationService->getUserDetailsId($this->username, $tableName = 'employee_details');
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			}
		
		//get the organisation id
		$organisationID = $this->empTravelAuthorizationService->getOrganisationId($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}
	}
	
	public function empTravelAuthorizationAction()
     {
   		$form = new EmpTravelAuthorizationForm();
		$empTravelAuthorizationModel = new EmpTravelAuthorization();
		$form->bind($empTravelAuthorizationModel);

         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->empTravelAuthorizationService->save($empTravelAuthorizationModel);
					 $this->redirect()->toRoute('emptraveldetails');
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }

         return array(
             'form' => $form,
			 'employee_details_id' => $this->employee_details_id,
			 'organisation_id' => $this->organisation_id
         );
     }
	 
	 public function empTravelDetailsAction()
     {
        
		//need to get an array of employees that have applied for list
		//associate the array with employee details
		//need to get the date as we do not need old travel details
		$date = date('Y-m-d');
		$empArray = $this->empTravelAuthorizationService->listTravelEmployee($date);
		
		$empIdArray = array();
		foreach($empArray as $emp){
			$value = $emp['employee_details_id'];
			array_push($empIdArray, $value );
		}
		if($empIdArray != NULL){
			$employees = $this->empTravelAuthorizationService->findEmployeeDetails($empIdArray);
			$empTravels = $this->empTravelAuthorizationService->listAllTravels();
		}
		else{
			$employees = array();
			$empTravels = array();
		}
		
		
		
		$form = new EmpTravelAuthorizationForm();

         return array(
             'form' => $form,
			 'travel' => $empTravels,
			 'employees' => $employees
         );
     }
	 
	 public function empTravelStatusAction()
     {
        //get the id of the travel authorization proposal
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$travelDetails = $this->empTravelAuthorizationService->findTravelDetails($id);
		
		$form = new EmpTravelAuthorizationForm();


         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->empTravelAuthorizationService->save($empTravelAuthorizationModel);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }

         return array(
             'form' => $form,
			 'travelDetails' => $travelDetails
         );
     }
        
}
             
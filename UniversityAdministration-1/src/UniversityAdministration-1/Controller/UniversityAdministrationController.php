<?php


namespace UniversityAdministration\Controller;

use UniversityAdministration\Service\UniversityAdministrationServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

//Session
use Zend\Session\Container;
//AJAX
use Zend\Http\Response\Stream;
use Zend\Http\Headers;
use Zend\Paginator\Adapter\DbSelect;
use Zend\View\Model\JsonModel;
use Zend\Form\Element\Select;

 
  
class UniversityAdministrationController extends AbstractActionController
{
    protected $universityAdministrationService;
    protected $notificationService;
    protected $auditTrailService;
    protected $serviceLocator;
    protected $username;
    protected $userrole;
    protected $userregion;
    protected $userDetails;
    protected $userImage;
    protected $employee_details_id;
    protected $organisation_id;
    protected $usertype;
    protected $parentValue;
    protected $parentValue1;

    protected $keyphrase = "RUB_IMS";

	
	public function __construct(UniversityAdministrationServiceInterface $universityAdministrationService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->universityAdministrationService = $universityAdministrationService;
        $this->notificationService = $notificationService;
        $this->auditTrailService = $auditTrailService;
        $this->serviceLocator = $serviceLocator;

        /*
         * To retrieve the user name from the session
        */
        $authPlugin = $serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
        $this->username = $authPlugin['username'];
        $this->userrole = $authPlugin['role'];
        $this->usertype = $authPlugin['user_type_id'];
        $this->userregion = $authPlugin['region'];

         /*
        * Getting the employee_details_id related to username
        */
        if($this->usertype == 1){
            $empData = $this->universityAdministrationService->getUserDetailsId($tableName = 'employee_details', $this->username);
            foreach($empData as $emp){
                $this->employee_details_id = $emp['id'];
            }
        }else if($this->usertype == 2){
            $stdData = $this->universityAdministrationService->getUserDetailsId($tableName = 'student', $this->username);
            foreach($stdData as $std){
                $this->student_id = $std['id'];
            }
        }        

        //get the organisation id
        if($this->usertype == 1){
            $organisationID = $this->universityAdministrationService->getOrganisationId($tableName = 'employee_details', $this->username);
            foreach($organisationID as $organisation){
                $this->organisation_id = $organisation['organisation_id'];
            }
        }else if($this->usertype == 2){
            //get the organisation id
            $stdOrganisationID = $this->universityAdministrationService->getOrganisationId($tableName = 'student', $this->username);
            foreach($stdOrganisationID as $organisation){
                $this->organisation_id = $organisation['organisation_id'];
            }
        }
        

        //get the user details such as name
        $this->userDetails = $this->universityAdministrationService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->universityAdministrationService->getUserImage($this->username, $this->usertype);
	}

    public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }


//To add Student Type Action

    /*This is action and route to view on index.html */
    public function aprcMeetingInformationAction()
    {
        $this->loginDetails();

        return array(
        );
    }   


    public function pqcMeetingInformationAction()
    {
        $this->loginDetails();

        return array(
        );
    }   


    public function ricMeetingInformationAction()
    {
        $this->loginDetails();

        return array(
        );
    }   


    public function uacMeetingInformationAction()
    {
        $this->loginDetails();

        return array(
        );
    }   


    public function kuenselInformationAction()
    {
        $this->loginDetails();

        return array(
        );
    }

}
             
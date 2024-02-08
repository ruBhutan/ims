<?php

namespace Accounts\Controller;

use Accounts\Service\FeeStructureServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Accounts\Form\StudentFeeReportSearchForm;
use Zend\View\Model\ViewModel;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\JsonModel;
use Zend\Http\Response\Stream;
use Zend\Http\Headers;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Form\Element\Select;

class StudentFeeReportController extends AbstractActionController {

    protected $feeStructurService;
    protected $financial_year;
    protected $serviceLocator;
    protected $notificationService;
    protected $auditTrailService;
    protected $userDetails;
    protected $userImage;
    protected $employee_details_id;
    protected $organisation_id;
    protected $keyphrase = "RUB_IMS";
    protected $feePaymentTable = "student_fee_payment_details";
    protected $feeCategoryTable = "student_fee_category";
    protected $feeDetailsTable = "student_fee_details";
    protected $organisationTable = "organisation";
    protected $messageStatus = "Success";

    protected $_user;
    protected $_user_id;
    protected $_user_name;
    protected $_user_role;
    protected $_user_organisation_id;
    protected $_user_type;
    protected $checking_role_name = "ADMIN";

    public function __construct(FeeStructureServiceInterface $feeStructurService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator) {
        $this->feeStructurService = $feeStructurService;
        $this->serviceLocator = $serviceLocator;
        $this->financial_year = $this->setFinancialYear();
        $this->notificationService = $notificationService;
        $this->auditTrailService = $auditTrailService;
        $this->emailService = $serviceLocator->get('Application\Service\EmailService');
    }

    public function init() {
        if ( !isset($this->_user) ) {
            $this->_user = $this->identity();
        }  

        if ( !isset($this->_user_id) ) {
            $this->_user_id = $this->_user->id;
        }
        
        if ( !isset($this->_user_name) ) {
            $this->_user_name = $this->_user->username;
        } 

        if ( !isset($this->_user_role) ) {
            $this->_user_role = $this->_user->role;
        }

        if ( !isset($this->_user_organisation_id) ) {
            $this->_user_organisation_id = $this->_user->region;
        }

        if ( !isset($this->_user_type) ) {
            $this->_user_type = $this->_user->user_type_id;
        }

        $emp = $this->feeStructurService->getLoginEmpDetailfrmUsername($this->_user_name);
        $this->employee_details_id = $emp['id'];
        $this->userDetails = $emp['first_name'] . ' ' . $emp['middle_name'].' '.$emp['last_name'];
        $this->userImage = $emp['profile_picture'];

        $this->layout()->setVariable('userRole', $this->_user_role);
        $this->layout()->setVariable('userRegion', $this->_user_organisation_id);
        $this->layout()->setVariable('userType', $this->_user_type);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }

    public function indexAction() {
        // Load initial function to set value to defined access variables
        $this->init();

        // Get all organisation lists, if current login user is Admin otherwise return only that's lists
        if ( $this->_user_role === $this->checking_role_name ) {
            $organisationLists = $this->feeStructurService->listAll($this->organisationTable)->toArray();
        } else {
            $organisationLists = $this->feeStructurService->findDetails($this->organisationTable, $this->_user_organisation_id)->toArray();
        }

        // Get a new key and value pair array list for dropdown options
        $organisationLists = $this->getKeyValuePairList($organisationLists, 'id', 'organisation_name');

        $studentReport = $receivableAndPaidFeesCount = [];

        // Get all category list and filter out with organization
        $feeCategoryData = $this->feeStructurService->findDetails($this->feeCategoryTable . 'report-option', $this->_user_organisation_id)->toArray();

        // If user is admin set array blank, because category is dependent to organization
        $feeCategoryLists = $this->getCategoryFilterByOrganization($feeCategoryData);

        // Get a new key and value pair array list for dropdown options
        $feeCategoryLists = $this->getKeyValuePairList($feeCategoryLists, 'id', 'fee_category');

        $request = $this->getRequest();

        $form = new StudentFeeReportSearchForm();

        if ( $request->isPost() ) {
            $form->setData($request->getPost());
            $studentReport = $this->feeStructurService->getStudentFeeReportList($this->feePaymentTable, $request->getPost());
        }

        $receivableAndPaidFeesCount = $this->feeStructurService->getTotalReceivableAndPaidFeesCount($this->feeDetailsTable, $request->getPost());

        if( $this->checking_role_name !== $this->_user_role ) {
            $form->get('organisation_id')->setAttribute('id', 'selectFeeReportOrganisationReadOnly');
            $form->get('organisation_id')->setAttribute('value', $this->_user_organisation_id);
        }

        return array(
            'studentReport' => $studentReport,
            'receivableAndPaidFeesCount' => $receivableAndPaidFeesCount,
            'form' => $form,
            'feeCategoryLists' => $feeCategoryLists,
            'financialYear' => $this->financial_year,
            'organisation_lists' => $organisationLists,
            'role_name' => $this->_user_role
        );
    }

    public function setFinancialYear() {
        $financialYear = [];

        $currentYear = date('Y');

        $startingYear = $currentYear - 3;
        $endingYear = $currentYear + 1;

        for ( $startingYear; $startingYear < $endingYear; $startingYear++ ) {
            $financialYear[($startingYear) . "-" . ($startingYear + 1)] = ($startingYear) . "-" . ($startingYear + 1);
        }

        return $financialYear;
    }

    /**
     * Get a new key and value pair array list for dropdown options
     *
     * @param $array
     * @param $key
     * @param $value
     * @return array|false
     */
    public function getKeyValuePairList($array, $key, $value) {
        return array_combine(
            array_column($array, $key), array_column($array, $value)
        );
    }

    /**
     * If user is admin set array blank, because category is dependent to organization
     *
     * @param $array
     * @return array
     */
    public function getCategoryFilterByOrganization($array) {
        $newArray = [];

//        if ( $this->_user_role !== $this->checking_role_name ) {
            $newArray = array_filter($array, function($data) {
                return ($data['organisation_id'] === $this->_user_organisation_id);
            });
  //      }

        return $newArray;
    }

    /**
     * Get all category list given by organization id
     *
     * @return JsonModel
     */
    public function fetchCategoryByOrganizationAction() {
        $organisationId = $this->getRequest()->getPost('organisation_id');

        if ( !is_numeric($organisationId) ) {
            return new JsonModel(['status' => 'error', 'message' => 'Invalid organisation id']);
        }

        // Get all category list and filter out with organization
        $feeCategoryData = $this->feeStructurService->findDetails($this->feeCategoryTable, $organisationId)->toArray();

        // Get a new key and value pair array list for dropdown options
        $feeCategoryLists = $this->getKeyValuePairList($feeCategoryData, 'id', 'fee_category');

        return new JsonModel(['status' => 'success', 'data' => [
            'categories' => $feeCategoryLists,
        ]]);

    }

}

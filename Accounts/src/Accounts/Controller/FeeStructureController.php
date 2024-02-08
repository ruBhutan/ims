<?php

namespace Accounts\Controller;

use Accounts\Service\FeeStructureServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Accounts\Form\FeeStructure;
use Accounts\Model\StudentFeeStructure;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\JsonModel;
use Zend\Http\Response\Stream;
use Zend\Http\Headers;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Form\Element\Select;

class FeeStructureController extends AbstractActionController {

    protected $service;
    protected $serviceLocator;
    protected $notificationService;
    protected $auditTrailService;
    protected $user_name;
    protected $user_role;
    protected $user_type;
    protected $user_region;
    protected $userDetails;
    protected $userImage;
    protected $employee_details_id;
    protected $organisation_id;
    protected $keyphrase = "RUB_IMS";
    protected $tableName = "student_fee_structure";
    protected $messageStatus = "Success";
    protected $financial_year;
    protected $organisationTable = "organisation";

    public function __construct(FeeStructureServiceInterface $service, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator) {
        $this->service = $service;
        $this->serviceLocator = $serviceLocator;
        $this->financial_year = $this->setFinancialYear();
        $this->notificationService = $notificationService;
        $this->auditTrailService = $auditTrailService;
        $this->emailService = $serviceLocator->get('Application\Service\EmailService');
    }

     /**
     * initial set up
     * general variables are defined here
     */
    public function init() {
        $authPlugin = $this->serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
        $this->user_role = $authPlugin['role'];
        $this->user_name = $authPlugin['username'];
        $this->user_type = $authPlugin['user_type_id'];
        $this->user_region = $authPlugin['region'];

        $emp = $this->service->getLoginEmpDetailfrmUsername($this->user_name);
        $this->employee_details_id = $emp['id'];
        $this->user_organisation_id = $emp['organisation_id'];
        $this->userDetails = $emp['first_name'] . ' ' . $emp['middle_name'].' '.$emp['last_name'];
        $this->userImage = $emp['profile_picture'];

        /*$id_from_route = $this->params()->fromRoute('id');
        $this->e_id = $id_from_route;
        if ($id_from_route)
            $this->_id = $this->my_decrypt($id_from_route, $this->keyphrase);

        $this->_created = date('Y-m-d H:i:s');
        $this->_modified = date('Y-m-d H:i:s');*/

        $this->layout()->setVariable('userRole', $this->user_role);
        $this->layout()->setVariable('userRegion', $this->user_organisation_id);
        $this->layout()->setVariable('userType', $this->user_type);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }

        public function indexAction() { 
            $this->init();
            //echo $this->user_name; die();
            //$this->_user = $this->identity(); 
            $form = new FeeStructure();
            $moduleModel = new StudentFeeStructure();
            $form->bind($moduleModel);
            $feeStructureLists = $this->service->listAll($this->tableName);

            if ( $this->user_role == 'ADMIN' ) {
                $organisationListsArray = $this->service->listAll($this->organisationTable)->toArray();
            } else {
                $organisationListsArray = $this->service->findDetails($this->organisationTable, $this->user_region)->toArray();
            }
    //        $organisationLists = $this->service->listAll('organisation');
    //        $organisationListsArray = $organisationLists->toArray();
            $organisationLists = array_combine(
                array_column($organisationListsArray, 'id'),
                array_column($organisationListsArray, 'organisation_name')
            );

            $response = null;
            $programmesLists = [];
            $studentFeeCategoryLists = [];

            $request = $this->getRequest();
            if ( $request->isPost() ) {
                $form->setData($request->getPost());

                $data = $this->params()->fromPost();

                $programmesLists = $this->getProgrammeAndCategoryOptionLists('programmes', $data['FeeStructure']['organisation_id'], 'id', 'programme_name');

                $studentFeeCategoryLists = $this->getProgrammeAndCategoryOptionLists('student_fee_category', $data['FeeStructure']['organisation_id'], 'id', 'fee_category');

                if ( $form->isValid() ) {
                    $response = $this->saveData($data, $form, $moduleModel);

                    if ( in_array($response, ['Success']) ) {
                        $this->messageStatus = 'Success';
                        return $this->redirect()->toRoute('fee-structure');
                    } else {
                        $this->messageStatus = 'Failure';
                    }
                }
            }

            return array(
                'form' => $form,
                'fee_structure_lists' => $feeStructureLists,
                'organisation_lists' => $organisationLists,
                'programme_lists' => $programmesLists,
                'student_fee_category_lists' => $studentFeeCategoryLists,
                'keyphrase' => $this->keyphrase,
                'message_status' => $this->messageStatus,
                'financial_year' => $this->financial_year
            );
    }

    public function saveData($data, $form, $moduleModel, $msg = 'added') {
        $feeStructure = $this->service->checkUniqueFeeStructure($this->tableName, $data['FeeStructure'], $msg);

        if ( $feeStructure ) {
            $this->flashMessenger()->addMessage("You have already $msg this fee structure.");

            return 'Exits';
        }

        if ( !$form->isValid() ) {
            $this->flashMessenger()->addMessage("Invalid form data.");

            return 'Invalid';
        }

        try {
            $this->service->save($moduleModel, $data);
            $this->flashMessenger()->addMessage("Fee structure was successfully $msg");

            return 'Success';

        } catch ( \Exception $e ) {
            $this->flashMessenger()->addMessage($e->getMessage());

            return 'Failure';
        }
    }

    public function editAction() {
        $this->init();
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if ( !is_numeric($id) ) {
            return $this->redirect()->toRoute('fee-structure');
        }

        $feeStructureDetails = $this->service->findDetails($this->tableName, $id);
        $feeStructureArray = $feeStructureDetails->toArray();

        if ( empty($feeStructureArray) ) {
            $this->messageStatus = 'Failure';
            $this->flashMessenger()->addMessage("Cannot find a record by given id!");
            return $this->redirect()->toRoute('fee-structure');
        }

        $feeStructureLists = $this->service->listAll($this->tableName);

        $organisationLists = $this->service->listAll('organisation');
        $organisationListsArray = $organisationLists->toArray();
        $organisationLists = array_combine(
            array_column($organisationListsArray, 'id'),
            array_column($organisationListsArray, 'organisation_name')
        );

        $organisationId = !empty($feeStructureArray) ? $feeStructureArray[0]['organisation_id'] : 0;

        $programmesLists = $this->getProgrammeAndCategoryOptionLists('programmes', $organisationId, 'id', 'programme_name');

        $studentFeeCategoryLists = $this->getProgrammeAndCategoryOptionLists('student_fee_category', $organisationId, 'id', 'fee_category');

        $form = new FeeStructure();
        $moduleModel = new StudentFeeStructure();
        $form->bind($moduleModel);

        $request = $this->getRequest();
        if ( $request->isPost() ) {
            $form->setData($request->getPost());

            if ( $form->isValid() ) {
                $response = $this->saveData($this->params()->fromPost(), $form, $moduleModel, 'updated');

                if ( in_array($response, ['Success']) ) {
                    $this->messageStatus = 'Success';
                    return $this->redirect()->toRoute('fee-structure');
                } else {
                    $this->messageStatus = 'Failure';
                }
            }
        }

        return array(
            'form' => $form,
            'fee_structure_lists' => $feeStructureLists,
            'organisation_lists' => $organisationLists,
            'programme_lists' => $programmesLists,
            'student_fee_category_lists' => $studentFeeCategoryLists,
            'fee_structure_details' => $feeStructureArray[0],
            'keyphrase' => $this->keyphrase,
            'message_status' => $this->messageStatus,
            'financial_year' => $this->financial_year
        );
    }

    function my_encrypt($data, $key) {
        // Remove the base64 encoding from our key
        $encryption_key = base64_decode($key);
        // Generate an initialization vector
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('BF-CFB'));
        // Encrypt the data using AES 256 encryption in CBC mode using our encryption key and initialization vector.
        $encrypted = openssl_encrypt($data, 'BF-CFB', $encryption_key, 0, $iv);
        // The $iv is just as important as the key for decrypting, so save it with our encrypted data using a unique separator (::)
        return bin2hex(base64_encode($encrypted . '::' . $iv));
    }

    public function my_decrypt($data, $key) {
        // Remove the base64 encoding from our key
        $encryption_key = base64_decode($key);

        $len = strlen($data);
        if ( $len % 2 ) {
            return "ERROR";
        } else {
            // To decrypt, split the encrypted data from our IV - our unique separator used was "::"
            list($encrypted_data, $iv) = explode('::', base64_decode(hex2bin($data)), 2);
            return openssl_decrypt($encrypted_data, 'BF-CFB', $encryption_key, 0, $iv);
        }
    }

    public function ajaxFetchProgrammeAndCategoryByOrganisationAction() {
        $organisationId = $this->getRequest()->getPost('organisation_id');
        if ( !is_numeric($organisationId) ) {
            return new JsonModel(['status' => 'error', 'message' => 'Invalid organisation id']);
        }

        $programmesLists = $this->getProgrammeAndCategoryOptionLists('programmes', $organisationId, 'id', 'programme_name');

        $studentFeeCategoryLists = $this->getProgrammeAndCategoryOptionLists('student_fee_category', $organisationId, 'id', 'fee_category');

        return new JsonModel(['status' => 'success', 'data' => [
            'programmes' => $programmesLists,
            'categories' => $studentFeeCategoryLists,
        ]]);

    }

    public function getProgrammeAndCategoryOptionLists($tableName, $organisationId, $columnID, $columnName) {
        $lists = $this->service->findDetails($tableName, $organisationId);
        $array = $lists->toArray();
        
        return array_combine(
            array_column($array, $columnID),
            array_column($array, $columnName)
        );
    }

    public function setFinancialYear() {
        $financialYear = [];

        $currentYear = date('Y') + 1;

        for ( $i = 0; $i < 3; $i++ ) {
            $financialYear[($currentYear + $i - 1) . "-" . ($currentYear + $i)] = ($currentYear + $i - 1) . "-" . ($currentYear + $i);
        }

        return $financialYear;
    }
}

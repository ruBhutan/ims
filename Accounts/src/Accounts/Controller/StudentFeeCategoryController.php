<?php

namespace Accounts\Controller;

use Accounts\Form\FeeCategoryInputFilter;
use Accounts\Service\FeeStructureServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Accounts\Form\FeeCategory;
use Accounts\Model\StudentFeeCategory;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\JsonModel;
use Zend\Http\Response\Stream;
use Zend\Http\Headers;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Form\Element\Select;

class StudentFeeCategoryController extends AbstractActionController {

    protected $service;
    protected $serviceLocator;
    protected $notificationService;
    protected $auditTrailService;
    protected $userDetails;
    protected $userImage;
    protected $employee_details_id;
    protected $keyphrase = "RUB_IMS";
    protected $tableName = "student_fee_category";
    protected $messageStatus = "Success";
    protected $organisationTable = "organisation";

    protected $_user;
    protected $_user_id;
    protected $_user_name;
    protected $_user_role;
    protected $_user_organisation_id;
    protected $_user_type;

    public function __construct(FeeStructureServiceInterface $service, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator) {
        $this->service = $service;
        $this->serviceLocator = $serviceLocator;
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

        $emp = $this->service->getLoginEmpDetailfrmUsername($this->_user_name);
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
        $response = null;
        $this->init();
        //$this->_user = $this->identity();

        $feeCategoryLists = $this->service->listAll($this->tableName);

        if ($this->_user->role == 'ADMIN' ) {
            $organisationListsArray = $this->service->listAll($this->organisationTable)->toArray();
        } else {
            $organisationListsArray = $this->service->findDetails($this->organisationTable, $this->_user->region)->toArray();
        }
        
//        $organisationLists = $this->service->listAll('organisation');
//        $organisationListsArray = $organisationLists->toArray();
        $organisationLists = array_combine(
            array_column($organisationListsArray, 'id'),
            array_column($organisationListsArray, 'organisation_name')
        );

        $form = new FeeCategory();

        $moduleModel = new StudentFeeCategory();

        $form->bind($moduleModel);

        $request = $this->getRequest();

        if ( $request->isPost() ) {
            $form->setData($request->getPost());

            if ( $form->isValid() ) {
                $response = $this->saveData($this->params()->fromPost(), $form, $moduleModel);

                if ( in_array($response, ['Success']) ) {
                    $this->messageStatus = 'Success';

                    return $this->redirect()->toRoute('student-fee-category');
                } else {
                    $this->messageStatus = 'Failure';
                }
            }
        }

        return array(
            'form' => $form,
            'student_fee_category_lists' => $feeCategoryLists,
            'organisation_lists' => $organisationLists,
            'keyphrase' => $this->keyphrase,
            'message_status' => $this->messageStatus
        );
    }

    public function editAction() 
    {
        $this->init();
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if ( !is_numeric($id) ) {
            return $this->redirect()->toRoute('student-fee-category');
        }

        $feeCategoryDetails = $this->service->findDetails($this->tableName . 'all', $id);
        $feeCategoryArray = $feeCategoryDetails->toArray();

        $feeCategoryLists = $this->service->listAll($this->tableName);

        $organisationLists = $this->service->listAll('organisation');
        $organisationListsArray = $organisationLists->toArray();
        $organisationLists = array_combine(
            array_column($organisationListsArray, 'id'),
            array_column($organisationListsArray, 'organisation_name')
        );

        $response = null;

        $form = new FeeCategory();
        $moduleModel = new StudentFeeCategory();
        $form->bind($moduleModel);

        $request = $this->getRequest();
        if ( $request->isPost() ) {
            $form->setData($request->getPost());

            if ( $form->isValid() ) {
                $response = $this->saveData($this->params()->fromPost(), $form, $moduleModel, 'updated');

                if ( in_array($response, ['Success']) ) {
                    $this->messageStatus = 'Success';
                    return $this->redirect()->toRoute('student-fee-category');
                } else {
                    $this->messageStatus = 'Failure';
                }
            }
        }

        return array(
            'form' => $form,
            'student_fee_category_lists' => $feeCategoryLists,
            'organisation_lists' => $organisationLists,
            'fee_category_details' => $feeCategoryArray[0],
            'keyphrase' => $this->keyphrase,
            'message_status' => $this->messageStatus
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

    public function saveData($data, $form, $moduleModel, $msg = 'added') {
        $feeCategory = $this->service->checkUniqueFeeStructure($this->tableName, $data['StudentFeeCategory'], $msg);

        if ( $feeCategory ) {
            $this->flashMessenger()->addMessage("You have already $msg this fee category.");

            return 'Exits';
        }

        if ( !$form->isValid() ) {
            $this->flashMessenger()->addMessage("Invalid form data.");

            return 'Invalid';
        }

        try {
            $this->service->saveCategory($moduleModel, $data);
            $this->flashMessenger()->addMessage("Fee category was successfully $msg");

            return 'Success';

        } catch ( \Exception $e ) {
            $this->flashMessenger()->addMessage($e->getMessage());

            return 'Failure';
        }
    }
}

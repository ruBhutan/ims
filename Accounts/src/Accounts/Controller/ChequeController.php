<?php

namespace Accounts\Controller;

use Accounts\Service\ChequeServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\TableGateway\TableGateway;
use Zend\Session\Container;
use Zend\Session\SessionManager;

class ChequeController extends AbstractActionController {
    protected $notificationService;
    protected $auditTrailService;
    protected $serviceLocator;
    protected $_table;        // database table
    protected $_id;
    protected $e_id;
    protected $user_name;
    protected $user_role;
    protected $user_type;
    protected $user_region;
    protected $userDetails;
    protected $userImage;
    protected $employee_details_id;
    protected $organisation_id;
    protected $user_organisation_id;
    protected $service;
    protected $_created;
    protected $_modified;
    protected $keyphrase = "RUB_IMS";

    /**
     * ChequeController constructor.
     * @param ChequeServiceInterface $service
     * @param $serviceLocator
     */
    public function __construct(ChequeServiceInterface $service, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator) {
        $this->service = $service;
        $this->notificationService = $notificationService;
        $this->auditTrailService = $auditTrailService;
        $this->serviceLocator = $serviceLocator;
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
        $this->organisation_id = $emp['organisation_id'];
        $this->userDetails = $emp['first_name'] . ' ' . $emp['middle_name'].' '.$emp['last_name'];
        $this->userImage = $emp['profile_picture'];

        $id_from_route = $this->params()->fromRoute('id');
        $this->e_id = $id_from_route;
        if ( $id_from_route ) {
            $this->_id = $this->my_decrypt($id_from_route, $this->keyphrase);
        }

        $this->_created = date('Y-m-d H:i:s');
        $this->_modified = date('Y-m-d H:i:s');

        $this->layout()->setVariable('userRole', $this->user_role);
        $this->layout()->setVariable('userRegion', $this->user_organisation_id);
        $this->layout()->setVariable('userType', $this->user_type);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }

    /**
     * Cheque index action
     **/
    public function indexAction() {
        $this->init();

        $month = '';
        $year = '';
        $userorg = $this->organisation_id;

        if ( $this->getRequest()->isPost() ) {
            $form = $this->getRequest()->getPost();

            $year = $form['year'];

            $month = $form['month'];

            if ( strlen($month) == 1 ) {
                $month = '0' . $month;
            }

            $userorg = $form['organisation'];
        }

        $month = ($month == '') ? date('m') : $month;
        $year = ($year == '') ? date('Y') : $year;

        $minYear = $this->service->getDataByFilter("acbGetMin", "accounts_cheque_book", null, 'receive_date');
        $minYear = ($minYear == "") ? date('Y-m-d') : $minYear;
        $minYear = date('Y', strtotime($minYear));

        $data = array(
            'year' => $year,
            'month' => $month,
            'minYear' => $minYear,
            'userorg' => $userorg,
        );

        //$results = $this->getDefinedTable('Accounts\ChequeTable')->getLocationDateWise('receive_date', $data['userorg'], $data['year'], $data['month']);

        $results = $this->service->getDataByFilter('getLocationDateWise', 'accounts_cheque_book', array('userorg' => $userorg, 'year' => $year, 'month' => $month), 'receive_date');

        return new ViewModel(array(
            'title' => "Cheque Book",
            'data' => $data,
            'cheques' => $results,
            //'BAObj' => $this->getDefinedTable('Accounts\BankaccountTable'),
            //'orgObj' => $this->getDefinedTable('Hr\OrganisationTable'),
            'serviceObj' => $this->service,
            'keyphrase' => $this->keyphrase
        ));
    }

    /**
     * Add Cheque
     **/
    public function addchequeAction() {
        $this->init();

        if ( $this->getRequest()->isPost() ):

            $request = $this->getRequest();

            $form = $request->getPost();

            $date = date('ym', strtotime($form['receive_date']));

            $tmp_Cheque_No = "CQ" . $date;

            $results = $this->service->getDataByFilter('getMonthlyCQ', 'accounts_cheque_book', $tmp_Cheque_No, null);

            $cq_no_list = array();

            foreach ( $results as $result ):
                array_push($cq_no_list, substr($result['cheque_code'], 7));
            endforeach;

            $next_serial = max($cq_no_list) + 1;

            switch ( strlen($next_serial) ) {
                case 1:
                    $next_cq_serial = "000" . $next_serial;
                    break;
                case 2:
                    $next_cq_serial = "00" . $next_serial;
                    break;
                case 3:
                    $next_cq_serial = "0" . $next_serial;
                    break;
                default:
                    $next_cq_serial = $next_serial;
                    break;
            }

            $cheque_code = $tmp_Cheque_No . $next_cq_serial;

            $rowsets = $this->service->getDatabyParam('accounts_bank_account', array('ba.id' => $form['bank_account']), null);

            foreach ( $rowsets as $rowset ) ;

            $data = array(
                'receive_date' => $form['receive_date'],
                'cheque_code' => $cheque_code,
                'bank_account' => $form['bank_account'],
                'start_cheque_no' => $form['cheque_start_no'],
                'end_cheque_no' => $form['cheque_end_no'],
                'no_of_cheque' => $form['no_of_cheque'],
                'organisation_id' => $rowset['organisation_id'],
                'author' => $this->employee_details_id,
                'created' => $this->_created,
                'modified' => $this->_modified
            );

            $result = $this->service->saveupdateData('accounts_cheque_book', $data);

            if ( $result > 0 ):

                $start_cheque_no = $form['cheque_start_no'];

                $end_cheque_no = $form['cheque_end_no'];

                $remarks = $form['remarks'];

                for ( $start_cheque_no = $start_cheque_no; $start_cheque_no <= $end_cheque_no; $start_cheque_no++ ):

                    $cheque_no = str_pad($start_cheque_no, 6, '0', STR_PAD_LEFT);

                    $cheque_detail_data = array(
                        'cheque_id' => $result,
                        'instrument_no' => $cheque_no,
                        'status' => 2,
                        'author' => $this->employee_details_id,
                        'created' => $this->_created,
                        'modified' => $this->_modified,

                    );

                    $this->service->saveupdateData('accounts_cheque_book_dtls', $cheque_detail_data);

                endfor;

                $this->flashMessenger()->addMessage("success^ Successfully added new Cheque Code :" . $cheque_code);

                return $this->redirect()->toRoute('cheque');
            else:
                $this->flashMessenger()->addMessage("Failed^ Failed to add new cheque no. ");

                return $this->redirect()->toRoute('cheque');
            endif;
        endif;

        return new ViewModel(array(
            'title' => "Add Cheque Book",
            //'baObj' => $this->getDefinedTable('Accounts\BankaccountTable'),
            'baObj' => $this->service->getDatabyParam('accounts_bank_account', array('ba.organisation_id' => $this->organisation_id), null),
            'user_org' => $this->organisation_id,
        ));
    }

    /**
     * edit Cheque
     **/
    public function editchequeAction() {
        $this->init();

        if ( $this->getRequest()->isPost() ):
            $request = $this->getRequest();
            $form = $request->getPost();
            $data = array(
                'receive_date' => $form['receive_date'],
                'cheque_code' => $cheque_no,
                'bank_account' => $form['bank_account'],
                'start_cheque_no' => $form['cheque_start_no'],
                'end_cheque_no' => $form['cheque_end_no'],
                'no_of_cheque' => $form['no_of_cheque'],
                'author' => $this->_author,
                'created' => $this->_created,
                'modified' => $this->_modified
            );
            $data = $this->_safedataObj->rteSafe($data);
            //echo "<pre>";print_r($data); exit;
            $result = $this->getDefinedTable('Accounts\ChequeTable')->save($data);

            if ( $result > 0 ):
                $start_cheque_no = $form['cheque_start_no'];
                $end_cheque_no = $form['cheque_end_no'];
                $remarks = $form['remarks'];
                for ( $start_cheque_no = $start_cheque_no; $start_cheque_no <= $end_cheque_no; $start_cheque_no++ ):
                    $cheque_detail_data = array(
                        'cheque_id' => $result,
                        'instrument_no' => $start_cheque_no,
                        'author' => $this->_author,
                        'created' => $this->_created,
                        'modified' => $this->_modified,
                    );
                    $cheque_detail_data = $this->_safedataObj->rteSafe($cheque_detail_data);
                    $this->getDefinedTable('Accounts\ChequeDetailsTable')->save($cheque_detail_data);
                endfor;
                $this->flashMessenger()->addMessage("success^ Successfully added new Cheque :" . $start_cheque_no);
                return $this->redirect()->toRoute('cheque');
            else:
                $this->flashMessenger()->addMessage("Failed^ Failed to add new dispatch");
                return $this->redirect()->toRoute('cheque');
            endif;
        endif;

        $ViewModel = new ViewModel(array(
            'title' => "Edit Cheque",
            'cheque' => $this->getDefinedTable('Accounts\ChequeTable')->get($this->_id),
            'user_org' => $this->_userorg,
        ));
        $ViewModel->setTerminal(True);
        return $ViewModel;
    }

    /**
     * View Advance Salary
     **/
    public function viewchequeAction() {
        $this->init();

        return new ViewModel(array(
            'title' => 'View Cheque',
            'cheque' => $this->service->getDatabyParam('accounts_cheque_book', $this->_id, null),
            //'CBObj' => $this->getDefinedTable('Accounts\ChequeDetailsTable'),
            'serviceObj' => $this->service,
            'userID' => $this->organisation_id,
            'keyphrase' => $this->keyphrase
        ));
    }

    /**
     * Chequecancel action
     **/
    public function chequecancelledlistAction() 
    {
        $this->init();

        return new ViewModel(array(
            'title' => "Cheque Cancellation",
            'cheque' => $this->getDefinedTable('Accounts\ChequeDetailsTable')->get(array('cd.status' => 4)),
            'empldObj' => $this->getDefinedTable('Hr\EmployeeDetailsTable'),
            'keyphrase' => $this->keyphrase
        ));
    }

    /**
     * Add Cheque
     **/
    public function addchequecancelAction() {
        $this->init();

        if ( $this->getRequest()->isPost() ):

            $request = $this->getRequest();

            $form = $request->getPost();

            $cheque_coderesults = $this->service->getDatabyParam('accounts_cheque_book_dtls', array('instrument_no' => $form['instrument_no']), null);

            foreach ( $cheque_coderesults as $rlt ) ;

            $cheque_detail_data = array(
                'id' => $rlt['id'],
                'cancellation_date' => $form['cancellation_date'],
                'status' => 4,
                'cancelled_by' => $this->employee_details_id,
                'reason' => $form['note'],
                'author' => $this->employee_details_id,
                'modified' => $this->_modified,
            );

            $result = $this->service->saveupdateData('accounts_cheque_book_dtls', $cheque_detail_data);

            if ( $result > 0 ):
                $this->flashMessenger()->addMessage("success^ Successfully Cancel the cheque :" . $form['instrument_no']);

                return $this->redirect()->toRoute('cheque', array('action' => 'viewcheque', 'id' => $this->my_encrypt($rlt['cheque_id'], $this->keyphrase)));
            else:
                $this->flashMessenger()->addMessage("Failed^ Failed to add new cheque no. ");

                return $this->redirect()->toRoute('cheque', array('action' => 'viewcheque', 'id' => $this->my_encrypt($rlt['cheque_id'], $this->keyphrase)));
            endif;
        endif;

        return new ViewModel(array(
            'title' => "Add Cheque Book Cancel",
            'chequedtls' => $this->service->getDatabyParam('accounts_cheque_book_dtls', $this->_id, null),
            'keyphrase' => $this->keyphrase
        ));
    }

    /**
     * Action for getting Accounts
     */
    public function getchequenoAction() {

        $this->init();

        $form = $this->getRequest()->getPost();

        $cheque_start_no = $form['cheque_start_no'];
        $cheque_end_no = $form['cheque_end_no'];
        $chequeno = $cheque_end_no - $cheque_start_no + 1;
        echo json_encode(array(
            'chequeno' => $chequeno,
        ));
        exit;
    }

    /**
     * @param $data
     * @param $key
     * @return string
     */
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

    /**
     * @param $data
     * @param $key
     * @return string
     */
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
}

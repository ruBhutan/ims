<?php

namespace Accounts\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\Db\TableGateway\TableGateway;
use Zend\Authentication\AuthenticationService;
use DOMPDFModule\View\Model\PdfModel;
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;
use Accounts\Service\MasterServiceInterface;

class TransactionController extends AbstractActionController {

    protected $_id;
    protected $e_id;
    protected $user_name;
    protected $user_role;
    protected $user_type;
    protected $user_region;
    protected $userDetails;
    protected $employee_details_id;
    protected $organisation_id;
    protected $service;
    protected $serviceLocator;
    protected $_created;  // current date to be used as created dated
    protected $_modified;  // current date to be used as modified date
    protected $keyphrase = "RUB_IMS";
    protected $organisation_table = "organisation";
    protected $transaction_details_table = 'accounts_transaction_details';
    protected $transaction_table = 'accounts_transaction';
    protected $journal_table = 'accounts_journal';
    protected $head_table = 'accounts_head';
    protected $subhead_table = 'accounts_sub_head';
    protected $cheque_book_dtls_table = 'accounts_cheque_book_dtls';
    protected $master_details_table = 'accounts_master_details';
    protected $cheque_book_table = 'accounts_cheque_book';
    protected $tds_table = 'accounts_tds';
    protected $bank_ref_type_table = 'accounts_bank_ref_type';
    protected $employee_details_table = 'employee_details';
    protected $position_title_table = 'position_title';
    protected $job_profile_table = 'job_profile';

    public function __construct(MasterServiceInterface $service, $serviceLocator) {
        $this->service = $service;
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
        $this->organisation_id = $emp['organisation_id'];
        $this->userDetails = $emp['first_name'] . ' ' . $emp['last_name'];
        $this->userImage = $emp['profile_picture'];

        $id_from_route = $this->params()->fromRoute('id');
        $this->e_id = $id_from_route;
        if ( $id_from_route ) {
            if ( strpos($id_from_route, '-') === false ) {
                $this->_id = $this->my_decrypt($id_from_route, $this->keyphrase);
            } else {
                $this->_id = $id_from_route;
            }
        }

        $this->_created = date('Y-m-d H:i:s');
        $this->_modified = date('Y-m-d H:i:s');

        $this->layout()->setVariable('userRole', $this->user_role);
        $this->layout()->setVariable('userRegion', $this->organisation_id);
        $this->layout()->setVariable('userType', $this->user_type);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }

    /**
     *  index action
     */
    public function indexAction() {
        $this->init();

        if ( $this->getRequest()->isPost() ) {
            $form = $this->getRequest()->getPost();
            $year = $form['year'];
            $month = $form['month'];
            if ( strlen($month) == 1 ) {
                $month = '0' . $month;
            }
            $userorg = $form['organisation'];
        } else {
            $month = '';
            $year = '';
            $month = ($month == '') ? date('m') : $month;
            $year = ($year == '') ? date('Y') : $year;
            $userorg = $this->organisation_id;
        }
        $month = ($month == '') ? date('m') : $month;
        $year = ($year == '') ? date('Y') : $year;

        $minYear = $this->service->getMin($this->transaction_table, 'voucher_date', '');
        $minYear = ($minYear == "") ? date('Y-m-d') : $minYear;
        $minYear = date('Y', strtotime($minYear));
        $data = array(
            'year' => $year,
            'month' => $month,
            'minYear' => $minYear,
            'userorg' => $userorg,
        );
        $results = $this->service->getLocationDateWise($this->transaction_table, 'voucher_date', $userorg, $year, $month, '');
        return new ViewModel(array(
            'title' => 'Transaction',
            'keyphrase' => $this->keyphrase,
            'role' => $this->user_role,
            'service' => $this->service,
            'results' => $results,
            'data' => $data,
            'orgObj' => $this->organisation_table,
            'transactiondetailsObj' => $this->transaction_details_table,
        ));
    }

    /**
     *  add transaction action
     */
    public function addtransactionAction() {
        $this->init();
        if ( $this->getRequest()->isPost() ) {
            $form = $this->getRequest()->getpost();
            //generate voucher no
            $org = $this->service->getDatabyParam($this->organisation_table, array('id' => $this->organisation_id), 'organisation_code');
            $prefix = $this->service->getDatabyParam($this->journal_table, $form['voucher_type'], 'prefix');
            $date = date('ym', strtotime($form['voucher_date']));
            $tmp_VCNo = $org[0]['organisation_code'] . $prefix[0]['prefix'] . $date;
            $results = $this->service->getSerial($this->transaction_table, $tmp_VCNo);
            $trns_no_list = array();
            foreach ( $results as $result ):
                array_push($trns_no_list, substr($result['voucher_no'], 8));
            endforeach;
            $next_serial = max($trns_no_list) + 1;

            switch ( strlen($next_serial) ) {
                case 1:
                    $next_vn_serial = "000" . $next_serial;
                    break;
                case 2:
                    $next_vn_serial = "00" . $next_serial;
                    break;
                case 3:
                    $next_vn_serial = "0" . $next_serial;
                    break;
                default:
                    $next_vn_serial = $next_serial;
                    break;
            }
            $voucher_no = $tmp_VCNo . $next_vn_serial;

            $data1 = array(
                'voucher_date' => $form['voucher_date'],
                'voucher_type' => $form['voucher_type'],
                'voucher_no' => $voucher_no,
                'cheque_id' => $form['cheque_no'],
                'voucher_amount' => str_replace(",", "", $form['voucher_amount']),
                'organisation_id' => $this->organisation_id,
                'remark' => $form['remark'],
                'reject_remark' => '',
                'status' => 1, // status initiated
                'author' => $this->employee_details_id,
                'created' => $this->_created,
                'modified' => $this->_modified,
                'cheque_no' => $form['cheque_number']
            );
            $result = $this->service->saveupdateData($this->transaction_table, $data1);
            if ( $result > 0 ) {
                $result_explodes = explode('|', implode('|', $form['sub_head']));
                $masterDtls = array();
                foreach ( $result_explodes as $key => $row ):
                    if ( $key % 3 == 0 ) {
                        array_push($masterDtls, $row);
                    }
                endforeach;
                $debit = $form['debit'];
                $credit = $form['credit'];

                $isTaxDeductionAtSource = $this->service->getDatabyParam(
                    $this->subhead_table,
                    array('code' => 'Tax deduction at Source'),
                    'id'
                );

                for ( $i = 0; $i < sizeof($masterDtls); $i++ ):
                    if ( isset($masterDtls[$i]) && is_numeric($masterDtls[$i]) ):

                        $sub_pass = $this->service->getDatabyParam($this->master_details_table, $masterDtls[$i], 'sub_head');
                        $head = $this->service->getDatabyParam($this->subhead_table, $sub_pass[0]['sub_head'], 'head');

                        if ( !empty($isTaxDeductionAtSource) && ($isTaxDeductionAtSource[0]['id'] == $sub_pass[0]['sub_head']) ) {

                            $supplier_id = $this->service->getDatabyParam($this->master_details_table, $masterDtls[$i], 'ref_id');

                            $accountsSpplierTdsRecords = array(
                                'transaction_id' => $result,
                                'supplier_id' => $supplier_id[0]['ref_id'],
                                'transaction_amount' => is_array($credit) ? array_sum($credit) : '0.00',
                                'tds_amount' => (isset($credit[$i])) ? $credit[$i] : '0.00',
                                'year' => date('Y', strtotime($form['voucher_date'])),
                                'month' => date('m', strtotime($form['voucher_date'])),
                                'created_at' => $this->_created,
                                'updated_at' => $this->_modified,
                            );

                            $responseSave = $this->service->saveupdateData('accounts_supplier_tds_records', $accountsSpplierTdsRecords);
                        }

                        $tdetailsdata = array(
                            'transaction' => $result,
                            'organisation_id' => $this->organisation_id,
                            'head' => $head[0]['head'],
                            'sub_head' => $sub_pass[0]['sub_head'],
                            'master_details' => $masterDtls[$i],
                            //'bank_ref_type'   => '',
                            'debit' => (isset($debit[$i])) ? $debit[$i] : '0.000',
                            'credit' => (isset($credit[$i])) ? $credit[$i] : '0.000',
                            'ref_no' => '',
                            'type' => '1', //user inputted  data
                            'author' => $this->employee_details_id,
                            'created' => $this->_created,
                            'modified' => $this->_modified,
                        );
                        $result1 = $this->service->saveupdateData($this->transaction_details_table, $tdetailsdata);
                        if ( $result1 <= 0 ):
                            break;
                        endif;
                    endif;
                endfor;
                if ( $result1 > 0 ):
                    $this->flashMessenger()->addSuccessMessage(" New Transaction successfully added | " . $voucher_no);
                    return $this->redirect()->toRoute('transaction', array('action' => 'viewtransaction', 'id' => $this->my_encrypt($result, $this->keyphrase)));
                else:
                    $this->flashMessenger()->addErrorMessage(" Failed to add new Transaction");
                    return $this->redirect()->toRoute('transaction');
                endif;
            } else {
                $this->flashMessenger()->addErrorMessage(" Failed to add new Transaction");
                return $this->redirect()->toRoute('transaction');
            }
        }
        $date = date('y-m-d');
        $bank_balance = '';
        $bank_subledgers = $this->service->getBSubledger(array('type' => array('2')), $this->organisation_id, '');
        if ( $bank_subledgers ) {
            foreach ( $bank_subledgers as $bank_subledger ) ;
            $bank_balance = $this->service->getBankandCashBalance('BA', $date, $bank_subledger['subhead_id'], $this->organisation_id);
        }

        $cash_subledgers = $this->service->getCSubledger(array('type' => array('3')), $this->organisation_id, '');
        foreach ( $cash_subledgers as $cash_subledger ) ;
        $cash_balance = $this->service->getBankandCashBalance('CA', $date, $cash_subledger['subhead_id'], $this->organisation_id);

        return new ViewModel(array(
            'title' => 'Add transaction',
            'keyphrase' => $this->keyphrase,
            'role' => $this->user_role,
            'service' => $this->service,
            'orgObj' => $this->organisation_table,
            'journals' => $this->service->getTableData($this->journal_table),
            'subheadObj' => $this->subhead_table,
            'masterDtls' => $this->service->getDistinctESP($this->organisation_id),
            'masterDtlObj' => $this->master_details_table,
            'heads' => $this->service->getTableData($this->head_table),
            'subheads' => $this->service->getTableData($this->subhead_table),
            'user_org' => $this->organisation_id,
            'chObj' => $this->cheque_book_table,
            'chdtlsObj' => $this->cheque_book_dtls_table,
            'tdsObj' => $this->tds_table,
            'bank_balance' => $bank_balance,
            'cash_balance' => $cash_balance,
        ));
    }

    /**
     *  edit transaction action
     */
    public function edittransactionAction() {
        $this->init();

        $trans_id = $this->_id;

        if ( $this->getRequest()->isPost() ) {
            $form = $this->getRequest()->getpost();
            $trans_id = $this->my_decrypt($form['trans_id'], $this->keyphrase);
        }

        $status_data = $this->service->getDatabyParam($this->transaction_table, $trans_id, 'status');
        $voucher_no = $this->service->getDatabyParam($this->transaction_table, $trans_id, 'voucher_no');

        if ( $status_data[0]['status'] >= 3 ):
            $this->redirect()->toRoute('transaction');
        endif;
        if ( $this->getRequest()->isPost() ) {
            $data1 = array(
                'id' => $this->my_decrypt($form['trans_id'], $this->keyphrase),
                'voucher_date' => $form['voucher_date'],
                'voucher_type' => $form['voucher_type'],
                'voucher_amount' => str_replace(",", "", $form['voucher_amount']),
                'cheque_id' => $form['cheque_no'],
                'organisation_id' => $this->organisation_id,
                'remark' => $form['remark'],
                'reject_remark' => '',
                'status' => 1, // status pending
                'modified' => $this->_modified,
                'cheque_no' => $form['cheque_number']
            );

            $result = $this->service->saveupdateData($this->transaction_table, $data1);

            if ( $result > 0 ) {
                $tdetails_id = $form['id'];
                $result_explodes = explode('|', implode('|', $form['sub_head']));
                $masterDtls = array();
                foreach ( $result_explodes as $key => $row ):
                    if ( $key % 3 == 0 ) {
                        array_push($masterDtls, $row);
                    }
                endforeach;
                $debit = $form['debit'];
                $credit = $form['credit'];
                $delete_rows = $this->service->getNotInDtl($tdetails_id, array('transaction' => $result), '');

                $isTaxDeductionAtSource = $this->service->getDatabyParam(
                    $this->subhead_table,
                    array('code' => 'Tax deduction at Source'),
                    'id'
                );

                $this->service->deleteTable('accounts_supplier_tds_records', array('transaction_id' => $result));

                for ( $i = 0; $i < sizeof($masterDtls); $i++ ):
                    if ( isset($masterDtls[$i]) && is_numeric($masterDtls[$i]) ):
                        $sub_pass = $this->service->getDatabyParam($this->master_details_table, $masterDtls[$i], 'sub_head');
                        $head = $this->service->getDatabyParam($this->subhead_table, $sub_pass[0]['sub_head'], 'head');

                        if ( !empty($isTaxDeductionAtSource) && ($isTaxDeductionAtSource[0]['id'] == $sub_pass[0]['sub_head']) ) {

                            $supplier_id = $this->service->getDatabyParam($this->master_details_table, $masterDtls[$i], 'ref_id');

                            $accountsSpplierTdsRecords = array(
                                'transaction_id' => $result,
                                'supplier_id' => $supplier_id[0]['ref_id'],
                                'transaction_amount' => is_array($credit) ? array_sum($credit) : '0.00',
                                'tds_amount' => (isset($credit[$i])) ? $credit[$i] : '0.00',
                                'year' => date('Y', strtotime($form['voucher_date'])),
                                'month' => date('m', strtotime($form['voucher_date'])),
                                'created_at' => $this->_created,
                                'updated_at' => $this->_modified,
                            );

                            $responseSave = $this->service->saveupdateData('accounts_supplier_tds_records', $accountsSpplierTdsRecords);
                        }

                        if ( $tdetails_id[$i] > 0 ):
                            $tdetailsdata = array(
                                'id' => $tdetails_id[$i],
                                'transaction' => $result,
                                'organisation_id' => $this->organisation_id,
                                'head' => $head[0]['head'],
                                'sub_head' => $sub_pass[0]['sub_head'],
                                'master_details' => $masterDtls[$i],
                                'bank_ref_type' => '',
                                'debit' => (isset($debit[$i])) ? $debit[$i] : '0.00',
                                'credit' => (isset($credit[$i])) ? $credit[$i] : '0.00',
                                'ref_no' => '',
                                'type' => '1', //user inputted  data
                                'modified' => $this->_modified,
                            );
                        else:
                            $tdetailsdata = array(
                                'transaction' => $result,
                                'organisation_id' => $this->organisation_id,
                                'head' => $head[0]['head'],
                                'sub_head' => $sub_pass[0]['sub_head'],
                                'master_details' => $masterDtls[$i],
                                'bank_ref_type' => '',
                                'debit' => (isset($debit[$i])) ? $debit[$i] : '0.00',
                                'credit' => (isset($credit[$i])) ? $credit[$i] : '0.00',
                                'ref_no' => '',
                                'type' => '1', //user inputted  data
                                'author' => $this->employee_details_id,
                                'created' => $this->_created, //user inputted  data
                                'modified' => $this->_modified,
                            );
                        endif;
                        $result1 = $this->service->saveupdateData($this->transaction_details_table, $tdetailsdata);
                    endif;
                endfor;
                //deleting deleted table rows form database table
                foreach ( $delete_rows as $delete_row ):
                    $this->service->remove($this->transaction_details_table, $delete_row['id']);
                endforeach;
                $this->flashMessenger()->addSuccessMessage("Transaction successfully updated | " . $voucher_no[0]['voucher_no']);
                return $this->redirect()->toRoute('transaction', array('action' => 'viewtransaction', 'id' => $form['trans_id']));
            } else {
                $this->_connection->rollback(); // rollback transaction over failure
                $this->flashMessenger()->addErrorMessage("Failed to modify Transaction");
                return $this->redirect()->toRoute('transaction', array('action' => 'viewtransaction', 'id' => $form['trans_id']));
            }
        }
        $bank_balance = '';
        $date = date('y-m-d');
        $bank_subledgers = $this->service->getBSubledger(array('type' => array('2')), $this->organisation_id, '');
        if ( $bank_subledgers ) {
            foreach ( $bank_subledgers as $bank_subledger ) ;
            $bank_balance = $this->service->getBankandCashBalance('BA', $date, $bank_subledger['subhead_id'], $this->organisation_id);
        }

        $cash_subledgers = $this->service->getCSubledger(array('type' => array('3')), $this->organisation_id, '');
        foreach ( $cash_subledgers as $cash_subledger ) ;
        $cash_balance = $this->service->getBankandCashBalance('CA', $date, $cash_subledger['subhead_id'], $this->organisation_id);

        return new ViewModel(array(
            'title' => 'Update transaction',
            'id' => $this->e_id,
            'keyphrase' => $this->keyphrase,
            'role' => $this->user_role,
            'service' => $this->service,
            'transactions' => $this->service->getDatabyParam($this->transaction_table, $this->_id, ''),
            'tdetails' => $this->service->getDatabyParam($this->transaction_details_table, array('transaction' => $this->_id), ''),
            'journals' => $this->service->getTableData($this->journal_table),
            'subheadObj' => $this->subhead_table,
            'masterDetailObj' => $this->master_details_table,
            'heads' => $this->service->getTableData($this->head_table),
            'tdetailsObj' => $this->transaction_details_table,
            'orgObj' => $this->organisation_table,
            'userorg' => $this->organisation_id,
            'chObj' => $this->cheque_book_table,
            'chdtlsObj' => $this->cheque_book_dtls_table,
            'tdsObj' => $this->tds_table,
            'bank_balance' => $bank_balance,
            'cash_balance' => $cash_balance,
            'masterDtls' => $this->service->getDistinctESP($this->organisation_id),
            'parties' => $this->service->getParty(array(4, 5, 6, 7, 8), array('transaction' => $this->_id)),
            'accounts_details' => $this->service->getBCADetails($this->organisation_id, array('type' => array('2', '3')), '')
        ));
    }

    /**
     * commit action
     * */
    public function commitAction() {
        $this->init();
        $transactions = $this->service->getDatabyParam($this->transaction_table, $this->_id, '');
        foreach ( $transactions as $row ) ;
        $data = array(
            'id' => $this->_id,
            'status' => 3, // status committed
            'modified' => $this->_modified,
        );
        $result = $this->service->saveupdateData($this->transaction_table, $data);
        $chequedtls = $this->service->getDatabyParam($this->cheque_book_dtls_table, array('id' => $row['cheque_id']), '');
        foreach ( $chequedtls as $row ) ;
        $update_data1 = array(
            'id' => $row['id'],
            'status' => 12,
            'author' => $this->employee_details_id,
            'modified' => $this->_modified,
        );
        $result1 = $this->service->saveupdateData($this->cheque_book_dtls_table, $update_data1);
        $voucher_no = $this->service->getDatabyParam($this->transaction_table, $this->_id, 'voucher_no');
        if ( $result > 0 || $result1 > 0 ):
            $this->flashMessenger()->addSuccessMessage("Transaction Verified Successfully | " . $voucher_no[0]['voucher_no']);
        endif;
        return $this->redirect()->toRoute("transaction", array("action" => "viewtransaction", "id" => $this->e_id));
    }

    /**
     * commit action
     * */
    public function pendingtransactionAction() {
        $this->init();
        $transactions = $this->getDefinedTable('Accounts\TransactionTable')->get($this->_id);
        foreach ( $transactions as $row )
            ;
        $data = array(
            'id' => $this->_id,
            'status' => 2, // status committed
            'modified' => $this->_modified,
        );
        $result1 = $this->getDefinedTable("Accounts\TransactionTable")->save($data);
        if ( $result1 ):
            $notification_data = array(
                'route' => 'transaction',
                'action' => 'viewtransaction',
                'key' => $this->_id,
                'description' => 'Voucher to be Verified',
                'author' => $this->_author,
                'created' => $this->_created,
                'modified' => $this->_modified,
            );
            $notificationResult = $this->getDefinedTable('Acl\NotificationTable')->save($notification_data);
            if ( $notificationResult > 0 ) {
                $voucher_organisation = $this->getDefinedTable('Accounts\TransactionTable')->getColumn($this->_id, 'organisation_id');
                //$finance_offer = $this->getDefinedTable('Acl\UserroleTable')->get(array('subrole'=>'1'));
                //foreach($finance_offer as $row):
                $user_organisatin_id = $this->getDefinedTable('Acl\UsersTable')->getColumn($row['user'], 'organisation_id');
                if ( $user_organisatin_id == $voucher_organisation ):
                    $notify_data = array(
                        'notification' => $notificationResult,
                        'user' => $row['user'],
                        'flag' => '0',
                        'desc' => 'Voucher to be Verified',
                        'author' => $this->_author,
                        'created' => $this->_created,
                        'modified' => $this->_modified,
                    );
                    $notifyResult = $this->getDefinedTable('Acl\NotifyTable')->save($notify_data);
                endif;
                //endforeach;
            }
            $voucher_no = $this->getDefinedTable("Accounts\TransactionTable")->getColumn($this->_id, 'voucher_no');
            $this->flashMessenger()->addMessage("success^ Transaction Commited Successfully | " . $voucher_no);
        else:
            $voucher_no = $this->getDefinedTable("Accounts\TransactionTable")->getColumn($this->_id, 'voucher_no');
            $this->_connection->rollback(); // rollback transaction over failure
            $this->flashMessenger()->addMessage("success^ Transaction Commited Successfully | " . $voucher_no);
        endif;
        return $this->redirect()->toRoute("transaction", array("action" => "viewtransaction", "id" => $this->_id));
    }

    /**
     * get journal view
     *
     * */
    public function viewtransactionAction() {
        $this->init();
        return new ViewModel(array(
            'keyphrase' => $this->keyphrase,
            'role' => $this->user_role,
            'service' => $this->service,
            'transactionrow' => $this->service->getDatabyParam($this->transaction_table, $this->_id, ''),
            'transactiondetails' => $this->service->getDatabyParam($this->transaction_details_table, array('transaction' => $this->_id), ''),
            'parties' => $this->service->getParty(array(4, 5, 6, 7), array('transaction' => $this->_id)),
            'bank_ref_typeObj' => $this->bank_ref_type_table,
            'chequeDtlsObj' => $this->cheque_book_dtls_table,
            'emplObj' => $this->employee_details_table,
            'positiontObj' => $this->position_title_table,
            'employeepfs' => $this->service->getDatabyParam($this->job_profile_table, array('employee_details' => $this->employee_details_id), ''),
            'masterObj' => $this->master_details_table,
            'subheadObj' => $this->subhead_table,
        ));
    }

    /**
     * money receipt print
     * */
    public function receiptprintAction() {
        $this->init();
        return new ViewModel(array(
            'transactionrow' => $this->getDefinedTable("Accounts\TransactionTable")->get($this->_id),
            'transactiondetails' => $this->getDefinedTable("Accounts\TransactiondetailTable")->get(array('transaction' => $this->_id)),
            'transactiondetailsObj' => $this->getDefinedTable("Accounts\TransactiondetailTable"),
            'chequeDtlsObj' => $this->getDefinedTable('Accounts\ChequeDetailsTable'),
        ));
    }

    /**
     * Action for getting accounts master details
     */
    public function getmasterdtlAction() {
        $this->init();
        $arr = explode('-', $this->_id);

        $ref_ID = $arr[0];
        $type_ID = $arr[1];
        if ( $ref_ID == 1 && $type_ID == "" ):
            $accounts_details = $this->service->getBCADetails($this->organisation_id, array('type' => array('2', '3')), '');
            $subhead_details = $this->service->getDatabyParam($this->master_details_table, array('type' => 9), '');
            $mastDID = array();
            foreach ( $accounts_details as $accounts_detail ):
                array_push($mastDID, $accounts_detail['id']);
            endforeach;
            foreach ( $subhead_details as $subhead_detail ):
                array_push($mastDID, $subhead_detail['id']);
            endforeach;
            $masterDtls = $this->service->getDatabyParam($this->master_details_table, array('id' => $mastDID), '');
        elseif ( $ref_ID == '16' && $type_ID == '8' ):
            $accounts_details = $this->service->getBCADetails($this->organisation_id, array('type' => array('2', '3')), '');
            $subhead_details = $this->service->getASSubLedger(array('sub_head' => '28', 'empl.organisation_id' => $this->organisation_id));
            $mastDID = array();
            foreach ( $accounts_details as $accounts_detail ):
                array_push($mastDID, $accounts_detail['id']);
            endforeach;
            foreach ( $subhead_details as $subhead_detail ):
                array_push($mastDID, $subhead_detail['id']);
            endforeach;
            $masterDtls = $this->service->getDatabyParam($this->master_details_table, array('id' => $mastDID), '');
        else:
            $accounts_details = $this->service->getBCADetails($this->organisation_id, array('type' => array('2', '3')), '');
            $subhead_details = $this->service->getDatabyParam($this->master_details_table, array('type' => $type_ID, 'ref_id' => $ref_ID), '');
            $mastDID = array();
            foreach ( $accounts_details as $accounts_detail ):
                array_push($mastDID, $accounts_detail['id']);
            endforeach;
            foreach ( $subhead_details as $subhead_detail ):
                array_push($mastDID, $subhead_detail['id']);
            endforeach;
            $masterDtls = $this->service->getDatabyParam($this->master_details_table, array('id' => $mastDID), '');
        endif;
        $viewModel = new ViewModel(array(
            'masterDtls' => $masterDtls,
            'type_ID' => $type_ID,
            'service' => $this->service,
        ));
        $viewModel->setTerminal(true);
        return $viewModel;
    }

    /**
     *  Edit Currency action
     */
    public function rejectAction() {
        $this->init();
        if ( $this->getRequest()->isPost() ) {
            $form = $this->getRequest()->getPost();
            $data = array(
                'id' => $form['id'],
                'reject_remark' => $form['remark'],
                'status' => 9,
                'author' => $this->_author,
                'modified' => $this->_modified,
            );
            $data = $this->_safedataObj->rteSafe($data);
            $result = $this->getDefinedTable('Accounts\TransactionTable')->save($data);
            if ( $result > 0 ):
                $this->flashMessenger()->addMessage("success^successfully rejected the Voucher");
            else:
                $this->flashMessenger()->addMessage("Failed^ Failed to reject the Voucher");
            endif;
            return $this->redirect()->toRoute('transaction', array('action' => 'viewtransaction', 'id' => $this->_id));
        }

        $ViewModel = new ViewModel(array(
            'title' => 'Reject',
            'transactionrow' => $this->getDefinedTable("Accounts\TransactionTable")->get($this->_id),
        ));

        $ViewModel->setTerminal(True);
        return $ViewModel;
    }

    public function my_encrypt($data, $key) {
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

    public function getBankAccountBalanceAction() {
        $this->init();

        $data = array();
        $status = false;

        if ( !$this->getRequest()->isPost() ) {
            goto end;
        }

        $form = $this->getRequest()->getPost();

        $masterDetails = explode("|", $form['master_details']);

        if ( !is_array($masterDetails) ) {
            goto end;
        }

        $master_detail_id = $masterDetails[0];
        $master_detail_sub_head = $masterDetails[1];
        $master_detail_type = $masterDetails[2];

        $headIdFromSubHead = $this->service->getDatabyParam('accounts_sub_head', $master_detail_sub_head, 'head');

        if ( empty($headIdFromSubHead) ) {
            goto end;
        }

        $checkIsBank = $this->service->getDatabyParam('accounts_head', $headIdFromSubHead[0]['head'], 'code');

        if ( empty($checkIsBank) ) {
            goto end;
        }

        if ( $checkIsBank[0]['code'] !== "Bank" ) {
            goto end;
        }

        $params = [
            'organisation_id' => $this->organisation_id,
            'master_details' => $master_detail_id
        ];

        $bankBalance = $this->service->getBankAccountBalanceFromTransaction($params);

        if ( empty($bankBalance) ) {
            goto end;
        }

        $data = $bankBalance[0]['total_credit'] - $bankBalance[0]['total_debit'];
        $status = true;

        end:

        return new JsonModel(
            array(
                'status' => $status,
                'message' => 'Get bank account balance.',
                'data' => $data,
                'error' => []
            )
        );

    }
}

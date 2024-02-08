<?php

namespace Accounts\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Accounts\Service\MasterServiceInterface;

class ReportController extends AbstractActionController {

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
    protected $serviceLocator;
    protected $_created;  // current date to be used as created dated
    protected $_modified;  // current date to be used as modified date
    protected $keyphrase = "RUB_IMS";
    protected $head_table = 'accounts_head';
    protected $group_table = 'accounts_group';
    protected $subhead_table = 'accounts_sub_head';
    protected $transaction_table = 'accounts_transaction';
    protected $journal_table = 'accounts_journal';
    protected $transaction_details_table = 'accounts_transaction_details';
    protected $closing_balance_table = 'accounts_closing_balance';
    protected $organisation_table = 'organisation';
    protected $class_table = 'accounts_class';
    protected $cheque_book_dtls_table = 'accounts_cheque_book_dtls';

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
        $this->user_organisation_id = $emp['organisation_id'];
        $this->organisation_id = $emp['organisation_id'];
        $this->userDetails = $emp['first_name'] . ' ' . $emp['middle_name'].' '.$emp['last_name'];
        $this->userImage = $emp['profile_picture'];

        $id_from_route = $this->params()->fromRoute('id');
        $this->e_id = $id_from_route;
        if ( $id_from_route )
            $this->_id = $this->my_decrypt($id_from_route, $this->keyphrase);

        $this->_created = date('Y-m-d H:i:s');
        $this->_modified = date('Y-m-d H:i:s');

        $this->layout()->setVariable('userRole', $this->user_role);
        $this->layout()->setVariable('userRegion', $this->user_organisation_id);
        $this->layout()->setVariable('userType', $this->user_type);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }

    /**
     * Ledger and Sub-Ledger Report
     */
    public function ledgerAction() {
        $this->init();

        $class_id = '';
        if ( $this->getRequest()->isPost() ):
            $form = $this->getRequest()->getPost();
            $organisation = $form['organisation'];
            $head = $form['head'];
            $sub_head = $form['sub_head'];
            $start_date = $form['start_date'];
            $end_date = $form['end_date'];
            $group_id = $this->service->getDatabyParam($this->head_table, $head, 'group');
            $class_ids = $this->service->getDatabyParam($this->group_table, $group_id[0]['group'], 'class');
            $class_id = $class_ids[0]['class'];
        else:
            $organisation = $this->user_organisation_id;
            $head = '';
            $sub_head = '-1';
            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d');
        endif;
        $data = array(
            'organisation' => $organisation,
            'head' => $head,
            'sub_head' => $sub_head,
            'start_date' => $start_date,
            'end_date' => $end_date,
        );
        return new ViewModel(array(
            'title' => "Ledger & Sub-Ledger",
            'keyphrase' => $this->keyphrase,
            'role' => $this->user_role,
            'data' => $data,
            'class' => $class_id,
            'service' => $this->service,
            'headObj' => $this->head_table,
            'subheadObj' => $this->subhead_table,
            'journalObj' => $this->journal_table,
            'transactionObj' => $this->transaction_table,
            'transactiondetailObj' => $this->transaction_details_table,
            'closingbalanceObj' => $this->closing_balance_table,
            'orgObj' => $this->organisation_table,
        ));
    }

    /**
     * get Subhead according to Head
     * */
    public function getsubheadAction() {
        $this->init();

        $form = $this->getRequest()->getPost();

        $head_id = $form['head'];

        $subHeadDtls = $this->service->getDatabyParam($this->subhead_table, array('head' => $head_id), '');
        $sub_heads = '';
        $sub_heads .= "<option value='-1'>All</option>";
        foreach ( $subHeadDtls as $subhead ):
            $sub_heads .= "<option value='" . $subhead['id'] . "'>" . $subhead['name'] . "</option>";
        endforeach;

        echo $sub_heads;
        exit;
    }

    /**
     * trial balance Sheet action
     *
     * */
    public function trialbalanceAction() {
        $this->init();
        if ( $this->getRequest()->isPost() ):
            $form = $this->getRequest()->getPost();
            $tier = $form['tier'];
            $organisation = $form['organisation'];
            $start_date = $form['start_date'];
            $end_date = $form['end_date'];
        else:
            $tier = 1;
            $organisation = $this->organisation_id;
            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d');
        endif;
        $data = array(
            'tier' => $tier,
            'organisation' => $organisation,
            'start_date' => $start_date,
            'end_date' => $end_date,
        );
        return new ViewModel(array(
            'title' => "Trial Balance",
            'keyphrase' => $this->keyphrase,
            'role' => $this->user_role,
            'service' => $this->service,
            'groupObj' => $this->group_table,
            'headObj' => $this->head_table,
            'data' => $data,
            'orgObj' => $this->organisation_table,
            'userID' => $this->employee_details_id,
        ));
    }

    /**
     * balance Sheet action
     *
     * */
    public function balancesheetAction() {
        $this->init();

        $tier = 1;
        $organisation = $this->organisation_id;
        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d');

        if ( $this->getRequest()->isPost() ):
            $form = $this->getRequest()->getPost();
            $tier = $form['tier'];
            $organisation = $form['organisation'];
            $start_date = $form['start_date'];
            $end_date = $form['end_date'];
        endif;

        $data = array(
            'tier' => $tier,
            'organisation' => $organisation,
            'start_date' => $start_date,
            'end_date' => $end_date,
        );

        $getAccountClass = $this->service->getBalanceSheetClass($data['organisation'], $data['start_date'], $data['end_date']);
        $getOrganisation = $this->service->getDatabyParam('organisation', array('id ' => $organisation), null);

        return new ViewModel(array(
            //'classObj' => $this->getDefinedTable("Accounts\ClassTable"),
            //'groupObj' => $this->getDefinedTable("Accounts\GroupTable"),
            //'headObj' => $this->getDefinedTable("Accounts\HeadTable"),
            //'subheadObj' => $this->getDefinedTable("Accounts\SubheadTable"),
            //'transactiondetailObj' => $this->getDefinedTable("Accounts\TransactiondetailTable"),
            'data' => $data,
            //'minDate' => $this->getDefinedTable("Accounts\TransactionTable")->getMin('voucher_date'),
            //'orgObj' => $this->getDefinedTable('Hr\OrganisationTable'),
            'serviceObj' => $this->service,
            //'get_organisation' => $this->service->getDatabyParam('organisation', array('id ' => $organisation), null),
            'get_organisation' => $getOrganisation,
            //'get_account_class' => $this->service->getBalanceSheetClass($data['organisation'], $data['start_date'], $data['end_date']),
            'render_html_for_balance' => $this->renderHtmlForBalance($data, $getAccountClass),
            'get_previous_date_format_for_balance' => $this->getPreviousDateFormatForBalance($data)
        ));
    }

    /**
     * profit loss statement action
     *
     * */
    public function profitlossAction() {
        $this->init();

        if ( $this->getRequest()->isPost() ):
            $form = $this->getRequest()->getPost();
            $tier = $form['tier'];
            $organisation = $form['organisation'];
            $start_date = $form['start_date'];
            $end_date = $form['end_date'];
        else:
            $tier = 1;
            $organisation = $this->organisation_id;
            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d');
        endif;

        $data = array(
            'tier' => $tier,
            'organisation' => $organisation,
            'start_date' => $start_date,
            'end_date' => $end_date,
        );

        return new ViewModel(array(
            //'classObj' => $this->getDefinedTable("Accounts\ClassTable"),
            'classObj' => $this->service->getDataByFilter('getProfitlossClass', 'accounts_class', $data, null),
            //'groupObj' => $this->getDefinedTable("Accounts\GroupTable"),
            //'headObj' => $this->getDefinedTable("Accounts\HeadTable"),
            //'subheadObj' => $this->getDefinedTable("Accounts\SubheadTable"),
            //'transactiondetailObj' => $this->getDefinedTable("Accounts\TransactiondetailTable"),
            'transactiondetailObj' => $this->service,
            'data' => $data,
            //'minDate' => $this->getDefinedTable("Accounts\TransactionTable")->getMin('voucher_date'),
            'minDate' => $this->service->getDataByFilter("at_getMin", "accounts_transaction", '', 'voucher_date'),
            //'orgObj' => $this->getDefinedTable('Hr\OrganisationTable'),
            'serviceObj' => $this->service
        ));
    }

    /**
     * Cash Flow action
     *
     * */
    public function cashflowAction() {
        $this->init();
        
        if ( $this->getRequest()->isPost() ):
            $form = $this->getRequest()->getPost();
            $organisation = $form['organisation'];
            $start_date = $form['start_date'];
            $end_date = $form['end_date'];
        else:
            $organisation = $this->user_organisation_id;
            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d');
        endif;
        $data = array(
            'organisation' => $organisation,
            'start_date' => $start_date,
            'end_date' => $end_date,
        );
        return new ViewModel(array(
            'keyphrase' => $this->keyphrase,
            'role' => $this->user_role,
            'service' => $this->service,
            'classObj' => $this->class_table,
            'groupObj' => $this->group_table,
            'headObj' => $this->head_table,
            'subheadObj' => $this->subhead_table,
            'transactionObj' => $this->transaction_table,
            'transactiondetailObj' => $this->transaction_details_table,
            'data' => $data,
            //'minDate' => $this->getDefinedTable("Accounts\TransactionTable")->getMin('voucher_date'),
            'orgObj' => $this->organisation_table,
            'chequeDtlObj' => $this->cheque_book_dtls_table,
        ));
    }

    /**
     * Bank Recounciliation action
     *
     * */
    public function bankreconciliationAction() {
        $this->init();
        $account_dtls = $this->service->getBCADetails($this->organisation_id, array('type' => array('2')), null);

        if ( $this->getRequest()->isPost() ):
            $form = $this->getRequest()->getPost();

            $organisation = $form['organisation'];
            $account = $form['account'];
            $start_date = $form['start_date'];
            $end_date = $form['end_date'];
        else:
            $organisation = $this->organisation_id;
            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d');
            //$account_dtls = $this->getDefinedTable("Accounts\MasterDetailsTable")->getBCADetails($this->organisation_id, array('type' => array('2')));

            foreach ( $account_dtls as $account_dtls_row ) {
                $account = $account_dtls_row['subhead_id'];
            }

        endif;

        $data = array(
            'organisation' => $organisation,
            'account' => $account,
            'start_date' => $start_date,
            'end_date' => $end_date,
        );

        return new ViewModel(array(
            //'classObj' => $this->getDefinedTable("Accounts\ClassTable"),  //not use
            //'classObj' => $this->service->getDataByFilter('getProfitlossClass', 'accounts_class', $data, null),  //not use
            //'groupObj' => $this->getDefinedTable("Accounts\GroupTable"), // not use
            //'headObj' => $this->getDefinedTable("Accounts\HeadTable"),  // not use
            //'transactionObj' => $this->getDefinedTable("Accounts\TransactionTable"), // not use
            //'orgObj' => $this->getDefinedTable('Hr\OrganisationTable'), //not use
            //'chequeDtlObj' => $this->getDefinedTable('Accounts\ChequeDetailsTable'), //not use
            //'subheadObj' => $this->getDefinedTable("Accounts\SubheadTable"),
            //'transactiondetailObj' => $this->getDefinedTable("Accounts\TransactiondetailTable"),
            //'transactiondetailObj' => $this->service,
            //'accounts_details' => $this->getDefinedTable("Accounts\MasterDetailsTable")->getBCADetails($this->organisation_id, array('type' => array('2'))),
            //'minDate' => $this->getDefinedTable("Accounts\TransactionTable")->getMin('voucher_date'),
            'data' => $data,
            'minDate' => $this->service->getDataByFilter("at_getMin", "accounts_transaction", '', 'voucher_date'),
            'accounts_details' => $account_dtls,
            'serviceObj' => $this->service,
            'renderBankConciliation' => $this->renderBankConciliation($data)
        ));
    }

    /**
     * Bank Statement action
     *
     * */
    public function bankstatementAction() {
        $this->init();
        $account_dtls = $this->service->getBCADetails($this->organisation_id, array('type' => array('2')), null);

        if ( $this->getRequest()->isPost() ):

            $form = $this->getRequest()->getPost();
            $organisation = $form['organisation'];
            $account = $form['account'];
            $start_date = $form['start_date'];
            $end_date = $form['end_date'];

            if ( $form['btn'] == 'save_encashment_date' ):

                $encashment_date = $form['encashment_date'];
                $cheque_detail_id = $form['cheque_detail'];

                for ( $i = 0; $i < sizeof($cheque_detail_id); $i++ ):

                    if ( isset($cheque_detail_id[$i]) && is_numeric($cheque_detail_id[$i]) ):

                        if ( $encashment_date[$i] > 0 ):

                            $cheque_detail_data = array(
                                'id' => $cheque_detail_id[$i],
                                'encashment_date' => $encashment_date[$i],
                            );

                            $result1 = $this->service->saveupdateData('accounts_cheque_book_dtls', $cheque_detail_data);
                        endif;

                    endif;

                endfor;

                if ( $result1 > 0 ):
                    $this->flashMessenger()->addMessage("success^ Successfully Encashed.");
                    return $this->redirect()->toRoute('report', array('action' => 'bankstatement'));
                else:
                    $this->flashMessenger()->addMessage("Failed^ Failed to encash.");
                    return $this->redirect()->toRoute('report', array('action' => 'bankstatement'));
                endif;

            endif;

        else:
            $account = 0;
            $isFirst = true;
            foreach ( $account_dtls as $account_dtls_row ) {
                if ( $isFirst === true ) {
                    $account = $account_dtls_row['subhead_id'];
                    $isFirst = false;
                }
            }
            $organisation = $this->organisation_id;
            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d');
        endif;

        $data = array(
            'organisation' => $organisation,
            'account' => $account,
            'start_date' => $start_date,
            'end_date' => $end_date,
        );

        return new ViewModel(array(
            /*'classObj' => $this->getDefinedTable("Accounts\ClassTable"),
            'groupObj' => $this->getDefinedTable("Accounts\GroupTable"),
            'headObj' => $this->getDefinedTable("Accounts\HeadTable"),
            'subheadObj' => $this->getDefinedTable("Accounts\SubheadTable"),
            'transactiondetailObj' => $this->getDefinedTable("Accounts\TransactiondetailTable"),
            'transactionObj' => $this->getDefinedTable("Accounts\TransactionTable"),
            'data' => $data,
            'minDate' => $this->getDefinedTable("Accounts\TransactionTable")->getMin('voucher_date'),
            'orgObj' => $this->getDefinedTable('Hr\OrganisationTable'),
            'chequeDtlObj' => $this->getDefinedTable('Accounts\ChequeDetailsTable'),*/

            'data' => $data,
            'organisationList' => $this->service->getDatabyParam('organisation', array('id' => $data['organisation']), null),
            'organisationName' => $this->service->getDatabyParam('organisation', $data["organisation"], 'organisation_name'),
            'renderBankStatementBlock' => $this->renderBankStatementBlock($data),
            'accounts_details' => $account_dtls,
        ));

    }

    /**
     * Delete Bank Statement action
     *
     * */
    public function deleteAction() {
        $this->init();
        $chequeDetails_id = $this->_id;
        if ( $chequeDetails_id > 0 ):
            $this->getDefinedTable('Accounts\chequeDetailsTable')->save(array('id' => $chequeDetails_id, 'encashment_date' => "(NULL)"));
            $this->flashMessenger()->addMessage("success^ Successfully delete Encashed Date.");
            return $this->redirect()->toRoute('report', array('action' => 'bankstatement'));
        else:
            $this->flashMessenger()->addMessage("Failed^ Failed to delete encashed date.");
            return $this->redirect()->toRoute('report', array('action' => 'bankstatement'));
        endif;
    }

    /**
     * Bank Statement action
     *
     * */
    public function editAction() {
        $this->init();
        if ( $this->getRequest()->isPost() ):
            $form = $this->getRequest()->getPost();
            $cheque_details = $this->getDefinedTable('Accounts\ChequeDetailsTable')->get($this->_id);
            foreach ( $cheque_details as $cd_row )
                ;
            $cheque_detail_data = array(
                'id' => $cd_row['id'],
                'encashment_date' => $form['encashment_date'],
            );
            $result1 = $this->getDefinedTable('Accounts\ChequeDetailsTable')->save($cheque_detail_data);
            if ( $result1 > 0 ):
                $this->flashMessenger()->addMessage("success^ Successfully Updated.");
                return $this->redirect()->toRoute('report', array('action' => 'bankstatement'));
            else:
                $this->flashMessenger()->addMessage("Failed^ Failed to Update.");
                return $this->redirect()->toRoute('report', array('action' => 'bankstatement'));
            endif;
        endif;
        return new ViewModel(array(
            'chequeDtls' => $this->getDefinedTable('Accounts\ChequeDetailsTable')->get($this->_id),
        ));
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

    public function getPreviousDateFormatForBalance($data) {
        $prevoius_start_year = date('y', strtotime($data['start_date'])) - 1;
        $prevoius_start_month = date('m', strtotime($data['start_date']));
        $prevoius_start_day = date('d', strtotime($data['start_date']));
        $pre_end_year = date('y', strtotime($data['end_date'])) - 1;
        $pre_end_month = date('m', strtotime($data['end_date']));
        $pre_end_day = date('d', strtotime($data['end_date']));

        $pre_starting_date = date('Y-m-d', strtotime($prevoius_start_year . '-' . $prevoius_start_month . '-' . $prevoius_start_day));
        $pre_ending_date = date('Y-m-d', strtotime($pre_end_year . '-' . $pre_end_month . '-' . $pre_end_day));

        return array($pre_starting_date, $pre_ending_date);
    }

    public function renderHtmlForBalance($data, $get_account_class) {

        $pre_starting_date = '';
        $pre_ending_date = '';

        if ( $data['tier'] > 0 ) {
            list($pre_starting_date, $pre_ending_date) = $this->getPreviousDateFormatForBalance($data);
        }
        $allHtmlData = [];
        if ( $data['tier'] <= 4 ):

            $get_account_class = array_reverse($get_account_class);
            foreach ( $get_account_class as $classrow ):
                $html = '';
                $grand_pres_total = array();
                $grand_prev_total = array();

                if ( $data['tier'] <= 4 ):
                    //$html .= '<tr class="classrow">';
                    //$html .= '<td>' . $classrow['name'] . '</td>';
                endif;

                $pres_closing_balance_get = $this->service->getClosingBalanceforPresBS($data['organisation'], $data['start_date'], $data['end_date'], $classrow['id'], 4);
                $pres_closing_balance = ($pres_closing_balance_get < 0) ? -$pres_closing_balance_get : $pres_closing_balance_get;

                $prev_closing_balance_get = $this->service->getClosingBalanceforPrevBS($data['organisation'], $pre_starting_date, $pre_ending_date, $classrow['id'], 4);
                $prev_closing_balance = ($prev_closing_balance_get < 0) ? -$prev_closing_balance_get : $prev_closing_balance_get;

                $grand_pres_total[] = $pres_closing_balance;
                $grand_prev_total[] = $prev_closing_balance;

                //$html .= '<td></td>';
                //$html .= '<td></td>';

                if ( $data['tier'] <= 4 ):
                    $html .= '</tr>';
                endif;

                if ( $data['tier'] <= 3 ):

                    $getTransactionGroupforBS = $this->service->getTransactionGroupforBS($data['organisation'], $data['start_date'], $data['end_date'], array('class' => $classrow['id']));

                    if ( !empty($getTransactionGroupforBS) ) :

                        foreach ( $getTransactionGroupforBS as $grouprow ):

                            $html .= '<tr class="grouprow">';
                            $html .= '<td>' . $grouprow['name'] . '</td>';

                            $pres_closing_balance = $this->service->getClosingBalanceforPresBS($data['organisation'], $data['start_date'], $data['end_date'], $grouprow['id'], 3);
                            $pres_closing_balance = ($pres_closing_balance < 0) ? -$pres_closing_balance : $pres_closing_balance;

                            $prev_closing_balance = $this->service->getClosingBalanceforPrevBS($data['organisation'], $pre_starting_date, $pre_ending_date, $grouprow['id'], 3);
                            $prev_closing_balance = ($prev_closing_balance < 0) ? -$prev_closing_balance : $prev_closing_balance;

                            $html .= '<td style="text-align:right">' . number_format($pres_closing_balance, 2, '.', ',') . '</td>';
                            //$html .= '<td style="text-align:right">' . number_format($prev_closing_balance, 2, '.', ',') . '</td>';
                            $html .= '</tr>';

                            if ( $data['tier'] <= 2 ):

                                foreach ( $this->service->getTransactionHeadforBS($data['organisation'], $data['start_date'], $data['end_date'], array('group' => $grouprow['id'])) as $headrow ):

                                    $html .= '<tr class="headrow">';
                                    $html .= '<td>' . $headrow['name'] . '</td>';

                                    $pres_closing_balance = $this->service->getClosingBalanceforPresBS($data['organisation'], $data['start_date'], $data['end_date'], $headrow['id'], 2);
                                    $pres_closing_balance = ($pres_closing_balance < 0) ? -$pres_closing_balance : $pres_closing_balance;

                                    $prev_closing_balance = $this->service->getClosingBalanceforPrevBS($data['organisation'], $pre_starting_date, $pre_ending_date, $headrow['id'], 2);
                                    $prev_closing_balance = ($prev_closing_balance < 0) ? -$prev_closing_balance : $prev_closing_balance;

                                    $html .= '<td style="text-align:right">' . number_format($pres_closing_balance, 2, '.', ',') . '</td>';
                                    //$html .= '<td style="text-align:right">' . number_format($prev_closing_balance, 2, '.', ',') . '</td>';
                                    $html .= '</tr>';

                                    if ( $data['tier'] <= 1 ):

                                        foreach ( $this->service->getTransactionSubheadforBS($data['organisation'], $data['start_date'], $data['end_date'], array('head' => $headrow['id'])) as $subheadrow ): // subhead

                                            $html .= '<tr class="subheadrow">';
                                            $html .= '<td>' . $subheadrow['name'] . '</td>';

                                            $pres_closing_balance = $this->service->getClosingBalanceforPresBS($data['organisation'], $data['start_date'], $data['end_date'], $subheadrow['id'], 1);
                                            $pres_closing_balance = ($pres_closing_balance < 0) ? -$pres_closing_balance : $pres_closing_balance;

                                            $prev_closing_balance = $this->service->getClosingBalanceforPrevBS($data['organisation'], $pre_starting_date, $pre_ending_date, $subheadrow['id'], 1);
                                            $prev_closing_balance = ($prev_closing_balance < 0) ? -$prev_closing_balance : $prev_closing_balance;

                                            $html .= '<td style="text-align:right">' . number_format($pres_closing_balance, 2, '.', ',') . '</td>';
                                            //$html .= '<td style="text-align:right">' . number_format($prev_closing_balance, 2, '.', ',') . '</td>';
                                            $html .= '</tr>';

                                        endforeach;

                                    endif;

                                endforeach;

                            endif;

                        endforeach;

                    endif;

                endif;

                $html .= '<tr class="classrow">';
                $html .= '<td> Total ' . $classrow['name'] . ':</td >';

                foreach ( $grand_pres_total as $total ):
                    foreach ( $grand_prev_total as $total1 ):
                        $html .= '<td style="text-align:right">' . number_format($total, 2, '.', ',') . '</td>';
                        //$html .= '<td style="text-align:right">' . number_format($total1, 2, ' . ', ',') . '</td>';
                    endforeach;
                endforeach;

                $html .= '<tr>';

                $allHtmlData[$classrow['name']] = $html;
            endforeach;

        endif;

        return $allHtmlData;
    }

    public function renderBankConciliation($data) {
        $html = '';

        $getTransactionSubheadforBRS = $this->service->getTransactionSubheadforBRS($data['organisation'], $data['start_date'], $data['end_date'], array('head' => array('74')));

        if ( empty($getTransactionSubheadforBRS) ) {
            return $html;
        }

        foreach ( $getTransactionSubheadforBRS as $subheadrow ):

            $total_budget = $this->service->getBudgetforBRS($data['organisation'], $data['end_date'], $subheadrow['id']);

            $bank_opening_balance_cb = $this->service->getOpeningBalanceCBforBRS($data['organisation'], $data['end_date'], $subheadrow['id']);

            $bank_opening_balance_bs = $this->service->getOpeningBalanceBSforBRS($data['organisation'], $data['end_date'], $subheadrow['id']);

            $fund_withdrawn_cash_book = $total_budget - $bank_opening_balance_cb;

            $fund_withdrawn_bank_statement = $total_budget - $bank_opening_balance_bs;

            $cheque_issued_amount = $this->service->getOpeningBalanceBSforBRS($data['organisation'], $data['end_date'], $subheadrow['id']);

            $amount_debited_cb = $this->service->getAmountDebitedCB($data['organisation'], $data['start_date'], $data['end_date'], 'debit', array('sub_head' => $subheadrow['id']));

            $amount_debited_bs = $this->service->getAmountDebitedBS($data['organisation'], $data['start_date'], $data['end_date'], 'debit', array('sub_head' => $subheadrow['id']));

            $html .= '<tr><td colspan="4"><strong>A. Particulars</strong></td></tr>';

            $html .= '<tr>';
            $html .= '<td colspan="2">1.Total Budget Released</td>';
            $html .= '<td style="text-align:right">' . $this->_number_format($total_budget) . '</td>';
            $html .= '<td style="text-align:right"></td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td colspan="2">2.Less : Closing Balance as per Cash Book</td>';
            $html .= '<td style="text-align:right">' . $this->_number_format($bank_opening_balance_cb) . '</td>';
            $html .= '<td style="text-align:right"></td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td colspan="2">3.Difference : Funds withdrawn as per Cash Book(1 - 2)</td>';
            $html .= '<td style="text-align:right">' . $this->_number_format($fund_withdrawn_cash_book) . '</td>';
            $html .= '<td style="text-align:right"></td>';
            $html .= '</tr>';

            $html .= '<tr><td colspan="4"><strong>B. Reconciliation</strong></td></tr>';

            $html .= '<tr>';
            $html .= '<td colspan="2">1. Funds withdrawn as per Bank statement of the Bank</td>';
            $html .= '<td style="text-align:right">' . $this->_number_format($fund_withdrawn_bank_statement) . '</td>';
            $html .= '<td style="text-align:right"></td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td colspan="2">2. Add: Cheques issued but not cashed (Annex - 1)</td>';
            $html .= '<td style="text-align:right">' . $this->_number_format($amount_debited_cb) . '</td>';
            $html .= '<td style="text-align:right"></td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td colspan="2">3. Add: Amount debited in Cash book but not in Bank statement (Annex - 2)</td>';
            $html .= '<td style="text-align:right">' . $this->_number_format($cheque_issued_amount) . '</td>';
            $html .= '<td style="text-align:right"></td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td colspan="2" style="text-align:center"><strong>Total (1 + 2 + 3)</strong></td>';
            $html .= '<td style="text-align:right">' . $this->_number_format(($fund_withdrawn_bank_statement + $cheque_issued_amount + $amount_debited_bs)) . '</td>';
            $html .= '<td style="text-align:right"></td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td colspan="2">4.Less: Amount debited in bank statement but not in cash book (Annex - 3)</td>';
            $html .= '<td style="text-align:right">' . $this->_number_format($amount_debited_bs) . '</td>';
            $html .= '<td style="text-align:right"></td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td colspan="2" style="text-align:center"><strong>Total (1 + 2 + 3)</strong></td>';
            $html .= '<td style = "text-align:right">' . $this->_number_format($amount_debited_bs) . '</td >';
            $html .= '<td style = "text-align:right"></td >';
            $html .= '</tr >';

        endforeach;

        return $html;
    }

    public function _number_format($value) {
        return number_format($value, 2, ' . ', ',');
    }

    public function renderBankStatementBlock($data) {
        $i = 1;

        $html = '';

        //TODO:: Old getTransactionSubHeadforBankStatement do not remove
        /*$getTransactionSubHeadforBankStatement = $this->service->getTransactionSubheadforBankStatement(
            $data['organisation'],
            $data['start_date'],
            $data['end_date'],
            array('head' => array('74'))
        );

        if ( empty($getTransactionSubHeadforBankStatement) ) {
            return $html;
        }

        foreach ( $getTransactionSubHeadforBankStatement as $subheadrow ):
*/
        $transactionDetails = $this->service->getBankStatement(
            $data['organisation'],
            $data['start_date'],
            $data['end_date'],
            array(
                'sub_head' => $data['account'],
            )
        );

        $bankStatementCount = 0;
        foreach ( $transactionDetails as $transactionDetails_row ):

            if ( !empty($transactionDetails_row['cheque_id']) ) {

                $bankStatementCount++;

                $html .= '<tr>';
                $html .= '<td>' . $i++ . '</td>';
                $html .= '<td><input type="hidden" value="' . $transactionDetails_row['cheque_id'] . '" 
            name="cheque_detail[]" class="form-control"/>' .
                    $this->service->getDataByFilter('get_column_accounts_cheque_book_dtls', 'accounts_cheque_book_dtls', $transactionDetails_row['cheque_id'], 'instrument_no')
                    . '</td>';

                $cheque_amount = ($transactionDetails_row['debit'] > 0) ? $transactionDetails_row['debit'] : $transactionDetails_row['credit'];

                $html .= '<td style="text-align:right" class="green">' . $cheque_amount . '</td>';

                $html .= '<td style="text-align:right">' . $this->service->getDatabyParam('accounts_cheque_book_dtls', $transactionDetails_row['cheque_id'], 'created') . '</td>'; //TODO: issue_date is replace by creatd

                $html .= '<td style="text-align:right">' . $transactionDetails_row['created'] . '</td>';

                /*$html .= '<td>';
                $html .= ' <div class="date input-group" id="encashment_date" data-date="' . date('Y - m - d') . '" data-date-format="yyyy-mm-dd">';

                //$chequeDtlEncashmentDate = $this->service->getDatabyParam('accounts_cheque_book_dtls', $transactionDetails_row['cheque_id'], 'created'); //TODO: encashment_date is replace by creatd


                //$encashment_date = ($chequeDtlEncashmentDate > 0) ? $chequeDtlEncashmentDate : '';
                $encashment_date = $transactionDetails_row['created'];

                $html .= '<input class="form-control span2" name="encashment_date[]" id="encashment_date" value="' . $encashment_date . '" type="text" placeholder="Encashment date" readonly>';
                $html .= '<span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>';
                $html .= '</div>';
                $html .= '</td>';*/

                /*$html .= '<td >';
                $html .= '<div class="btn-group" >';

                $deleteURL = $this->url()->fromRoute('report', array('action' => 'delete', 'id' => $transactionDetails_row['cheque_id']));

                $html .= '<a class="btn btn-xs btn-danger " href = "' . $deleteURL . '" >';
                $html .= '<span class="white" ><i class="fa fa-remove bigger-120" > delete</i > <span class="hidden-lg hidden-xs" ></span ></span >';
                $html .= '</a >';
                $html .= '</div >';

                $html .= '<div class="btn-group" >';

                $editURL = $this->url()->fromRoute('report', array('action' => 'edit', 'id' => $transactionDetails_row['cheque_id']));

                $html .= '<a class="btn btn-xs btn-info " href = "' . $editURL . '" >';
                $html .= '<span class="white" ><i class="fa fa-pencil bigger-120" > Edit</i > <span class="hidden-lg hidden-xs" ></span ></span >';
                $html .= '</a>';
                $html .= '</div>';
                $html .= '</td >';*/

                $html .= '</tr >';
            }
        endforeach;

        if ( empty($transactionDetails) || $bankStatementCount > 0 ) {
            return '<tr align="center"><td colspan="5">No records found</td></tr>';
        }

        return $html;
    }

    public function renderBankStatementBlockOLDDDD($data) {
        $i = 1;

        $html = '';

        $getTransactionSubHeadforBankStatement = $this->service->getTransactionSubheadforBankStatement(
            $data['organisation'],
            $data['start_date'],
            $data['end_date'],
            array('head' => array('74'))
        );

        if ( empty($getTransactionSubHeadforBankStatement) ) {
            return $html;
        }

        foreach ( $getTransactionSubHeadforBankStatement as $subheadrow ):

            $transactionDetails = $this->service->getBankStatement(
                $data['organisation'],
                $data['start_date'],
                $data['end_date'],
                array('sub_head' => $subheadrow['id'])
            );

            foreach ( $transactionDetails as $transactionDetails_row ):

                $html .= '<tr>';
                $html .= '<td>' . $i++ . '</td>';
                $html .= '<td><input type="hidden" value="' . $transactionDetails_row['cheque_detail_id'] . '" name="cheque_detail[]" class="form-control"/>' . $this->chequeDtlObj->getColumn($transactionDetails_row['cheque_detail_id'], 'instrument_no') . '</td>';

                $cheque_amount = ($transactionDetails_row['debit'] > 0) ? $transactionDetails_row['debit'] : $transactionDetails_row['credit'];

                $html .= '<td style="text-align:right" class="green">' . $cheque_amount . '</td>';

                $html .= '<td>' . $this->service->getDatabyParam('accounts_cheque_book_dtls', $transactionDetails_row['cheque_detail_id'], 'issue_date') . '</td>';

                $html .= '<td>';
                $html .= ' <div class="date input-group" id="encashment_date" data-date="' . date('Y - m - d') . '" data-date-format="yyyy-mm-dd">';

                $chequeDtlEncashmentDate = $this->service->getDatabyParam('accounts_cheque_book_dtls', $transactionDetails_row['cheque_detail_id'], 'encashment_date');

                $encashment_date = ($chequeDtlEncashmentDate > 0) ? $chequeDtlEncashmentDate : '';

                $html .= '<input class="form-control span2" name="encashment_date[]" id="encashment_date" value="' . $encashment_date . '" type="text" placeholder="Encashment date" readonly>';
                $html .= '<span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>';
                $html .= '</div>';
                $html .= '</td>';

                $html .= '<td >';
                $html .= '<div class="btn-group" >';

                $deleteURL = $this->url('report', array('action' => 'delete', 'id' => $transactionDetails_row['cheque_detail_id']));

                $html .= '<a class="btn btn-xs btn-danger " href = "' . $deleteURL . '" >';
                $html .= '<span class="white" ><i class="fa fa-remove bigger-120" > delete</i > <span class="hidden-lg hidden-xs" ></span ></span >';
                $html .= '</a >';
                $html .= '</div >';

                $html .= '<div class="btn-group" >';

                $editURL = $this->url('report', array('action' => 'edit', 'id' => $transactionDetails_row['cheque_detail_id']));

                $html .= '<a class="btn btn-xs btn-info " href = "' . $editURL . '" >';
                $html .= '<span class="white" ><i class="fa fa-pencil bigger-120" > Edit</i > <span class="hidden-lg hidden-xs" ></span ></span >';
                $html .= '</a>';
                $html .= '</div>';
                $html .= '</td >';

                $html .= '</tr >';
            endforeach;
        endforeach;
    }
}

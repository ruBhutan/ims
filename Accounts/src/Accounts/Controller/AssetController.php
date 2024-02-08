<?php

namespace Accounts\Controller;

use Accounts\Service\AssetServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AssetController extends AbstractActionController {
    protected $_table;
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
    protected $_created;
    protected $_modified;
    protected $keyphrase = "RUB_IMS";

    public function __construct(AssetServiceInterface $service, $serviceLocator) {
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
     *  index action
     */
    public function indexAction() {
        $this->init();
        return new ViewModel(array());
    }

    /**
     *  party action
     */
    public function partyAction() {
        $this->init();

        $rowset = $this->service->getDatabyParam('supplier_details', array('organisation_id' => $this->organisation_id), null);

        return new ViewModel(array(
            'title' => 'Party',
            'rowset' => $rowset,
            'keyphrase' => $this->keyphrase
        ));
    }

    /**
     * addparty action
     **/
    public function addpartyAction() {
        $this->init();

        if ( $this->getRequest()->isPost() ) {
            $data = array_merge_recursive(
                $this->getRequest()->getPost()->toArray()
            );

            $data1 = array(
                //'code' => $data['code'],
                //'name' => $data['name'],
                'supplier_name' => $data['name'],
                'supplier_code' => $data['code'],
                'supplier_license_no' => $data['supplier_license_no'],
                'supplier_tpn_no' => $data['supplier_tpn_no'],
                'supplier_bank_acc_no' => $data['supplier_bank_acc_no'],
                'supplier_contact_no' => $data['supplier_contact_no'],
                'supplier_address' => $data['address'],
                'supplier_status' => $data['active'],
                'organisation_id' => $data['organisation'],
                'party_role' => $data['party_role'],
                'author' => $this->employee_details_id,
                'created' => $this->_created,
                'modified' => $this->_modified,
            );
            
            $result = $this->service->saveupdateData('supplier_details', $data1); 
            
            if ( $result > 0 ):
                $subhead_details = $this->service->getDataByFilter('getNotInMD', 'accounts_sub_head', $result, null);
               
                foreach ( $subhead_details as $sh_row ):
                    $master_detailsdata = array(
                        'sub_head' => $sh_row['id'],
                        'ref_id' => $result,
                        'type' => 6,
                        'code' => $data['code'],
                        'name' => $data['name'],
                        'author' => $this->employee_details_id,
                        'created' => $this->_created,
                        'modified' => $this->_modified,
                    );
                    
                    $R_Mdetails = $this->service->saveupdateData('accounts_master_details', $master_detailsdata);

                endforeach;

                $this->flashMessenger()->addMessage("success^ New party successfully added");

                return $this->redirect()->toRoute('asset', array('action' => 'party'));
            else:
                $this->flashMessenger()->addMessage("Failed^ Failed to add new party");

                return $this->redirect()->toRoute('asset', array('action' => 'party'));
            endif;
        }

        $organisation = $this->organisation_id;

        $ViewModel = new ViewModel(array(
            'organisations' => $this->service->getDatabyParam('organisation', array('id' => $organisation), null),
            'organisation' => $organisation,
            'proles' => $this->service->getTableData('accounts_party_role'),
            'keyphrase' => $this->keyphrase
        ));

        return $ViewModel;
    }

    /*
     * party editAction
     * */
    public function editpartyAction() {
        $this->init(); 

        if ( $this->getRequest()->isPost() ) {
            $form = $this->getRequest()->getPost();
            $subhead_id = $form['subhead_id'];

            $data = array_merge_recursive(
                $this->getRequest()->getPost()->toArray()
            );

            $data1 = array(
                'id' => $this->_id,

                'supplier_name' => $data['name'],
                'supplier_code' => $data['code'],
                'supplier_license_no' => $data['supplier_license_no'],
                'supplier_tpn_no' => $data['supplier_tpn_no'],
                'supplier_bank_acc_no' => $data['supplier_bank_acc_no'],
                'supplier_contact_no' => $data['supplier_contact_no'],
                'supplier_address' => $data['address'],
                'supplier_status' => $data['active'],
                'organisation_id' => $data['organisation'],
                'party_role' => $data['party_role'],
                'author' => $this->employee_details_id,
                'modified' => $this->_modified,
            );

            $result = $this->service->saveupdateData('supplier_details', $data1);

            if ( $result > 0 && $subhead_id > 0 ) {
                for ( $i = 0; $i < sizeof($subhead_id); $i++ ):
                    $master_detailsdata = array(
                        'sub_head' => $subhead_id[$i],
                        'type' => 6,
                        'ref_id' => $result,
                        'code' => $form['code'],
                        'name' => $form['name'],
                        'author' => $this->employee_details_id,
                        'created' => $this->_created,
                        'modified' => $this->_modified,
                    );

                    $this->service->saveupdateData("accounts_master_details", $master_detailsdata);

                endfor;

                $this->flashmessenger()->addMessage('success^ Successfully updated party ' . $form['name']);
            } else {
                $this->flashmessenger()->addMessage('error^ Failed to update party');
            }

            return $this->redirect()->toRoute('asset', array('action' => 'party'));
        }

        $ViewModel = new ViewModel(array(
            'title' => 'Edit Party',
            'id' => $this->_id,
            'party' => $this->service->getDatabyParam('supplier_details', $this->_id, null),
            //'proleObj' => $this->getDefinedTable('Accounts\PartyRoleTable'),
            'master_details' => $this->service->getDatabyParam('accounts_master_details', array('ref_id' => $this->_id, 'md.type' => '6'), null),
            'subhead_details' => $this->service->getDataByFilter('getNotInMD', 'accounts_sub_head', $this->_id, null),
            //'orgObj' => $this->getDefinedTable('Hr\OrganisationTable'),
            'userorg' => $this->organisation_id,
            'serviceObj' => $this->service,
            'keyphrase' => $this->keyphrase
        ));

        return $ViewModel;
    }

    /**
     *  bankaccount action
     */
    public function bankaccountAction() {
        $this->init();

        return new ViewModel(array(
            'title' => 'Bank Account',
            'rowset' => $this->service->getDatabyParam('accounts_bank_account', array('organisation_id' => $this->organisation_id), null),
            'keyphrase' => $this->keyphrase
        ));
    }

    /**
     * addbankaccount Action
     **/
    public function addbankaccountAction() {
        $this->init(); 

        if ( $this->getRequest()->isPost() ) {

            $form = $this->getRequest()->getPost();

            $data = array(
                'bank_account' => $form['account'],
                'branch' => $form['branch'],
                'organisation_id' => $form['organisation'],
                'author' => $this->employee_details_id,
                'created' => $this->_created,
                'modified' => $this->_modified,
            );

            $result = $this->service->saveupdateData('accounts_bank_account', $data);

            if ( $result > 0 ) {

                $subheaddata = array(
                    'head' => $form['head'],
                    'code' => $form['account'],
                    'name' => $form['account'],
                    'author' => $this->employee_details_id,
                    'created' => $this->_created,
                    'modified' => $this->_modified,
                );

                $R_Sub_head = $this->service->saveupdateData("accounts_sub_head", $subheaddata);

                if ( $R_Sub_head > 0 ) {

                    $md_data = array(
                        'sub_head' => $R_Sub_head,
                        'type' => 2,
                        'ref_id' => $result,
                        'code' => $form['account'],
                        'name' => $form['account'],
                        'author' => $this->employee_details_id,
                        'created' => $this->_created,
                        'modified' => $this->_modified,
                    );

                    $R_M_details = $this->service->saveupdateData('accounts_master_details', $md_data);

                    if ( $R_M_details > 0 ) {
                        $this->flashMessenger()->addSuccessMessage("success^ New bank account successfully added and linked with Sub Head and Master Details");
                    } else {
                        $this->flashMessenger()->addErrorMessage("Failed^ Failed to add in Master Details");
                    }

                } else {
                    $this->flashMessenger()->addErrorMessage("Failed^ Failed to add in Sub Head Details");
                }
            } else {
                $this->flashMessenger()->addErrorMessage("Failed^ Failed to add new bank account");
            }

            return $this->redirect()->toRoute('asset', array('action' => 'bankaccount'));
        }

        return $ViewModel = new ViewModel(array(
            'title' => 'Add Bank Account',
            'rows' => $this->service->getDatabyParam('organisation', array('id' => $this->organisation_id), null),
            'accounts_head' => $this->service->listAll('accounts_head'),
            'organisation' => $this->organisation_id,
            'keyphrase' => $this->keyphrase
        ));
    }

    /**
     * bankaccount editAction
     **/
    public function editbankaccountAction() {
        $this->init();

        if ( !is_numeric($this->_id) ) {
            return $this->redirect()->toRoute('asset');
        }

        if ( $this->getRequest()->isPost() ) {

            $form = $this->getRequest()->getPost();

            $data = array(
                'id' => $this->_id,
                'bank_account' => $form['account'],
                'branch' => $form['branch'],
                'organisation_id' => $form['organisation'],
                'author' => $this->employee_details_id,
                'modified' => $this->_modified,
            );

            $result = $this->service->saveupdateData('accounts_bank_account', $data);

            if ( $result > 0 ) {

                $subhead_id = $form['subhead_id'];

                $masterdetail_id = $form['masterdetail_id'];

                $head = $form['head'];

                $delete_rows = $this->service->getDataByFilter('getNotIn-MD', 'accounts_master_details', $masterdetail_id, array('ref_id' => $this->_id, 'type' => '2'));

                for ( $i = 0; $i < sizeof($head); $i++ ):

                    if ( isset($head[$i]) && $head[$i] > 0 ):

                        if ( $subhead_id[$i] > 0 ) {

                            $subheaddata = array(
                                'id' => $subhead_id[$i],
                                'head' => $head[$i],
                                'code' => $form['account'],
                                'name' => $form['account'],
                                'author' => $this->employee_details_id,
                                'modified' => $this->_modified,
                            );

                            $this->service->saveupdateData('accounts_sub_head', $subheaddata);

                            if ( $masterdetail_id[$i] > 0 && $subhead_id[$i] > 0 ) {
                                $master_detailsdata = array(
                                    'id' => $masterdetail_id[$i],
                                    'sub_head' => $subhead_id[$i],
                                    'type' => 2,
                                    'ref_id' => $result,
                                    'code' => $form['account'],
                                    'name' => $form['account'],
                                    'author' => $this->employee_details_id,
                                    'modified' => $this->_modified,
                                );
                            } else {
                                $master_detailsdata = array(
                                    'sub_head' => $subhead_id[$i],
                                    'type' => 2,
                                    'ref_id' => $result,
                                    'code' => $form['account'],
                                    'name' => $form['account'],
                                    'author' => $this->employee_details_id,
                                    'modified' => $this->_modified,
                                );
                            }

                            $this->service->saveupdateData('accounts_master_details', $master_detailsdata);

                        } else {
                            $subheaddata = array(
                                'head' => $head[$i],
                                'code' => $form['account'],
                                'name' => $form['account'],
                                'author' => $this->employee_details_id,
                                'modified' => $this->_modified,
                            );

                            $this->service->saveupdateData('accounts_sub_head', $subheaddata);

                            if ( $masterdetail_id[$i] > 0 && $subhead_id[$i] > 0 ) {
                                $master_detailsdata = array(
                                    'id' => $masterdetail_id[$i],
                                    'sub_head' => $subhead_id[$i],
                                    'type' => 2,
                                    'ref_id' => $result,
                                    'code' => $form['account'],
                                    'name' => $form['account'],
                                    'author' => $this->employee_details_id,
                                    'modified' => $this->_modified,
                                );
                            } else {
                                $master_detailsdata = array(
                                    'sub_head' => $subhead_id[$i],
                                    'type' => 2,
                                    'ref_id' => $result,
                                    'code' => $form['account'],
                                    'name' => $form['account'],
                                    'author' => $this->employee_details_id,
                                    'modified' => $this->_modified,
                                );
                            }

                            $this->service->saveupdateData('accounts_master_details', $master_detailsdata);
                        }
                    endif;
                endfor;

                foreach ( $delete_rows as $delete_row ):
                    $this->service->deleteTable('accounts_master_details', $delete_row['id']);
                endforeach;

                $this->flashmessenger()->addMessage('success^ Bank Account Successfully Updated');

                return $this->redirect()->toRoute('asset', array('action' => 'bankaccount'));
            } else {

                $this->flashmessenger()->addMessage('error^ Failed to update bank account.');

                return $this->redirect()->toRoute('asset', array('action' => 'bankaccount'));
            }
        }

        $ViewModel = new ViewModel(array(
            'title' => 'Edit Bank Account',
            'id' => $this->_id,
            'bankaccount' => $this->service->getDatabyParam('accounts_bank_account', $this->_id, null),
            'master_details' => $this->service->getDatabyParam('accounts_master_details', array('ref_id' => $this->_id, 'md.type' => '2'), null),
            //'headObj' => $this->getDefinedTable('Accounts\HeadTable'),
            //'subheadObj' => $this->getDefinedTable('Accounts\SubHeadTable'),
            //'orgObj' => $this->getDefinedTable('Hr\OrganisationTable'),
            'serviceObj' => $this->service,
            'userorg' => $this->organisation_id,
            'keyphrase' => $this->keyphrase
        ));

        return $ViewModel;
    }

    /**
     *  cash account action
     */
    public function cashaccountAction() {
        $this->init();
        return new ViewModel(array(
            'title' => 'Cash Account',
            'rowset' => $this->service->getDatabyParam('accounts_cash_account', array('organisation_id' => $this->organisation_id), null),
            //'orgObj' => $this->getDefinedTable('Hr\OrganisationTable'),
            'keyphrase' => $this->keyphrase
        ));
    }

    /**
     * addcashaccount Action
     **/
    public function addcashaccountAction() {
        $this->init();

        if ( $this->getRequest()->isPost() ) {

            $form = $this->getRequest()->getPost();

            $data = array(
                'cash_account' => $form['cash_account'],
                'organisation_id' => $form['organisation'],
                'author' => $this->employee_details_id,
                'created' => $this->_created,
                'modified' => $this->_modified,
            );

            $result = $this->service->saveupdateData('accounts_cash_account', $data);

            if ( $result > 0 ):
                $head = $form['head'];

                for ( $i = 0; $i < sizeof($head); $i++ ):

                    if ( isset($head[$i]) && is_numeric($head[$i]) ):

                        $subheaddata = array(
                            'head' => $head[$i],
                            'code' => $form['cash_account'],
                            'name' => $form['cash_account'],
                            'author' => $this->employee_details_id,
                            'created' => $this->_created,
                            'modified' => $this->_modified,
                        );

                        $resultSH = $this->service->saveupdateData('accounts_sub_head', $subheaddata);

                        if ( $resultSH > 0 ):

                            $master_detailsdata = array(
                                'sub_head' => $resultSH,
                                'type' => 3,
                                'ref_id' => $result,
                                'code' => $form['cash_account'],
                                'name' => $form['cash_account'],
                                'author' => $this->employee_details_id,
                                'created' => $this->_created,
                                'modified' => $this->_modified,
                            );

                            $this->service->saveupdateData('accounts_master_details', $master_detailsdata);

                        endif;

                    endif;
                endfor;

                $this->flashMessenger()->addMessage("success^ New cash account successfully added");
            else:

                $this->flashMessenger()->addMessage("error^ Failed to add new cash account");

            endif;

            return $this->redirect()->toRoute('asset', array('action' => 'cashaccount'));
        }

        return $ViewModel = new ViewModel(array(
            'title' => 'Add Cash Account',
            'rows' => $this->service->getDatabyParam('organisation', array('id' => $this->organisation_id), null),
            'headObj' => $this->service,
            'organisation' => $this->organisation_id,
            'keyphrase' => $this->keyphrase
        ));
    }

    /**
     * cashaccount editAction
     **/
    public function editcashaccountAction() {
        $this->init();

        if ( !is_numeric($this->_id) ) {
            return $this->redirect()->toRoute('cashaccount');
        }

        if ( $this->getRequest()->isPost() ) {

            $form = $this->getRequest()->getPost();

            $data = array(
                'id' => $this->_id,
                'organisation_id' => $form['organisation'],
                'cash_account' => $form['cash_account'],
                'author' => $this->employee_details_id,
                'modified' => $this->_modified,
            );

            $result = $this->service->saveupdateData('accounts_cash_account', $data);

            if ( $result > 0 ) {

                $subhead_id = $form['subhead_id'];

                $masterdetail_id = $form['masterdetail_id'];

                $head = $form['head'];

                $delete_rows = $this->service->getDataByFilter('getNotIn', 'accounts_master_details', $masterdetail_id, array('ref_id' => $this->_id, 'type' => '3'));

                for ( $i = 0; $i < sizeof($head); $i++ ):

                    if ( isset($head[$i]) && $head[$i] > 0 ):

                        if ( $subhead_id[$i] > 0 ) {

                            $subheaddata = array(
                                'id' => $subhead_id[$i],
                                'head' => $head[$i],
                                'code' => $form['cash_account'],
                                'name' => $form['cash_account'],
                                'author' => $this->employee_details_id,
                                'modified' => $this->_modified,
                            );

                            $this->service->saveupdateData('accounts_sub_head', $subheaddata);

                            if ( $masterdetail_id[$i] > 0 && $subhead_id[$i] > 0 ) {

                                $master_detailsdata = array(
                                    'id' => $masterdetail_id[$i],
                                    'sub_head' => $subhead_id[$i],
                                    'type' => 3,
                                    'ref_id' => $result,
                                    'code' => $form['cash_account'],
                                    'name' => $form['cash_account'],
                                    'author' => $this->employee_details_id,
                                    'modified' => $this->_modified,
                                );
                            } else {
                                $master_detailsdata = array(
                                    'sub_head' => $subhead_id[$i],
                                    'type' => 2,
                                    'ref_id' => $result,
                                    'code' => $form['cash_account'],
                                    'name' => $form['cash_account'],
                                    'author' => $this->employee_details_id,
                                    'modified' => $this->_modified,
                                );
                            }

                            $this->service->saveupdateData('accounts_master_details', $master_detailsdata);

                        } else {
                            $subheaddata = array(
                                'head' => $head[$i],
                                'code' => $form['cash_account'],
                                'name' => $form['cash_account'],
                                'author' => $this->employee_details_id,
                                'modified' => $this->_modified,
                            );

                            $this->service->saveupdateData('accounts_sub_head', $subheaddata);

                            if ( $masterdetail_id[$i] > 0 && $subhead_id[$i] > 0 ) {
                                $master_detailsdata = array(
                                    'id' => $masterdetail_id[$i],
                                    'sub_head' => $subhead_id[$i],
                                    'type' => 2,
                                    'ref_id' => $result,
                                    'code' => $form['account'],
                                    'name' => $form['account'],
                                    'author' => $this->employee_details_id,
                                    'modified' => $this->_modified,
                                );
                            } else {
                                $master_detailsdata = array(
                                    'sub_head' => $subhead_id[$i],
                                    'type' => 2,
                                    'ref_id' => $result,
                                    'code' => $form['account'],
                                    'name' => $form['account'],
                                    'author' => $this->employee_details_id,
                                    'modified' => $this->_modified,
                                );
                            }

                            $this->service->saveupdateData('accounts_master_details', $master_detailsdata);
                        }
                    endif;
                endfor;

               /* foreach ( $delete_rows as $delete_row ):
                    $this->service->deleteTable('accounts_master_details', $delete_row['id']);
                endforeach;*/

                $this->flashmessenger()->addMessage('success^ Cash Account Successfully Updated');

                return $this->redirect()->toRoute('asset', array('action' => 'cashaccount'));
            } else {
                $this->flashmessenger()->addMessage('error^ Failed to update cash account.');

                return $this->redirect()->toRoute('asset', array('action' => 'cashaccount'));
            }
        }

        $ViewModel = new ViewModel(array(
            'title' => 'Edit Cash Account',
            'id' => $this->_id,
            'cashaccounts' => $this->service->getDatabyParam('accounts_cash_account', $this->_id, null),
            'master_details' => $this->service->getDatabyParam('accounts_master_details', array('ref_id' => $this->_id, 'md.type' => '3'), null),
            //'orgObj' => $this->service('Hr\OrganisationTable'),
            //'headObj' => $this->getDefinedTable('Accounts\HeadTable'),
            //'subheadObj' => $this->getDefinedTable('Accounts\SubHeadTable'),
            'serviceObj' => $this->service,
            'userorg' => $this->organisation_id,
            'keyphrase' => $this->keyphrase
        ));
        return $ViewModel;
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
}

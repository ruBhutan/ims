<?php

namespace Accounts\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;
use Accounts\Service\MasterServiceInterface;

class MasterController extends AbstractActionController {

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
    protected $service;
    protected $serviceLocator;
    protected $_created;  // current date to be used as created dated
    protected $_modified;  // current date to be used as modified date
    protected $keyphrase = "RUB_IMS";
    protected $bank_ref_type_table = 'accounts_bank_ref_type';
    protected $currency_table = 'accounts_currency';
    protected $pay_head_table = 'payr_pay_heads';
    protected $head_table = 'accounts_head';
    protected $pay_slab_table = 'payr_pay_slab';
    protected $pay_group_table = 'payr_pay_group';

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
        $this->userDetails = $emp['first_name'].' '.$emp['middle_name'].' '.$emp['last_name'];
        $this->userImage = $emp['profile_picture'];

        $id_from_route = $this->params()->fromRoute('id');
        $this->e_id = $id_from_route;
        if($id_from_route)
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
     *  Currency action
     */
    public function currencyAction() {
        $this->init();

        return new ViewModel(array(
            'title' => 'currency',
            'id' => $this->_id,
            'currencyObj' => $this->service->getTableData($this->currency_table),
            'keyphrase' => $this->keyphrase,
        ));
    }

    /**
     * addcurrency action
     */
    public function addcurrencyAction() {
        $this->init();
        if ($this->getRequest()->isPost()) {
            $form = $this->getRequest()->getPost();
            $data = array(
                'currency' => $form['currency'],
                'country' => $form['country'],
                'code' => $form['code'],
                'fraction' => $form['fraction'],
                'author' => $this->employee_details_id,
                'created' => $this->_created,
                'modified' => $this->_modified,
            );

            $result = $this->service->saveupdateData($this->currency_table,$data);
            if ($result > 0):
                $this->flashMessenger()->addSuccessMessage("New currency successfully added");
            else:
                $this->flashMessenger()->addErrorMessage("Failed to add new currency");
            endif;
            return $this->redirect()->toRoute('master', array('action' => 'currency'));
        }
        return new ViewModel(array(
        ));
    }

    /**
     *  Edit Currency action
     */
    public function editcurrencyAction() {
        $this->init();
        if ($this->getRequest()->isPost()) {
            $form = $this->getRequest()->getPost();
            $data = array(
                'id' => $this->my_decrypt($form['currency_id'], $this->keyphrase),
                'currency' => $form['currency'],
                'country' => $form['country'],
                'code' => $form['code'],
                'fraction' => $form['fraction'],
                'status' => $form['status'],
                'author' => $this->employee_details_id,
                'modified' => $this->_modified,
            );
            $result = $this->service->saveupdateData($this->currency_table,$data);
            if ($result > 0):
                $this->flashMessenger()->addSuccessMessage("Currency successfully updated");
            else:
                $this->flashMessenger()->addErrorMessage("Failed to update Currency");
            endif;
            return $this->redirect()->toRoute('master', array('action' => 'currency'));
        }

        return new ViewModel(array(
            'title' => 'editcurrency',
            'id' => $this->e_id,
            'currency' => $this->service->getDatabyParam($this->currency_table,$this->_id,''),
        ));
    }

    /**
     * Bank Ref type Action
     */
    public function bankreftypeAction() {
        $this->init();

        return new ViewModel(array(
            'title' => 'Bank Ref Type',
            'bankref' => $this->service->getTableData($this->bank_ref_type_table),
            'keyphrase' => $this->keyphrase,
        ));
    }

    /**
     * Add bankreftype Action
     */
    public function addbankreftypeAction() {
        $this->init();
        
        if ($this->getRequest()->isPost()) {
            $form = $this->getRequest()->getPost();
            $data = array(
                'bank_ref_type' => $form['bankref'],
                'author' => $this->employee_details_id,
                'created' => $this->_created,
                'modified' => $this->_modified,
            );
            $result = $this->service->saveupdateData($this->bank_ref_type_table,$data);
            if ($result > 0):
                $this->flashMessenger()->addSuccessMessage("New Bank reference type successfully added");
            else:
                $this->flashMessenger()->addErrorMessage("Failed to add new bank reference type");
            endif;
            return $this->redirect()->toRoute('master', array('action' => 'bankreftype'));
        }

        return new ViewModel(array(
            'bankref' => $this->service->getTableData($this->bank_ref_type_table),
        ));
    }

    /**
     * Edit bank ref type Action
     */
    public function editbankreftypeAction() {
        $this->init();

        if ($this->getRequest()->isPost()) {
            $form = $this->getRequest()->getPost();
            $data = array(
                'id' => $this->my_decrypt($form['bank_id'], $this->keyphrase),
                'bank_ref_type' => $form['bank_ref_type'],
                'author' => $this->employee_details_id,
                'modified' => $this->_modified,
            );
            $result = $this->service->saveupdateData($this->bank_ref_type_table,$data);
            if ($result > 0):
                $this->flashmessenger()->addSuccessMessage("Bank reference type successfully updated ");
            else:
                $this->flashmessenger()->addErrorMessage("Failed to update bank reference type");
            endif;
            return $this->redirect()->toRoute('master', array('action' => 'bankreftype'));
        }

        return new ViewModel(array(
            'id' => $this->e_id,
            'bank_ref_type' => $this->service->getDatabyParam($this->bank_ref_type_table,$this->_id,''),
        ));
    }

    /**
     *  Payhead action
     */
    public function payheadAction() {
        $this->init();

        return new ViewModel(array(
            'title' => 'Payhead',
            'rowset' => $this->service->getTableData($this->pay_head_table),
            'payheadObj' => $this->pay_head_table,
            'service' => $this->service,
            'keyphrase' => $this->keyphrase,
        ));
    }

    /**
     * addpayhead action
     */
    public function addpayheadAction() {
        $this->init();
        if ($this->getRequest()->isPost()) {
            $form = $this->getRequest()->getPost();
            $data = array(
                'pay_head' => $form['pay_head'],
                'deduction' => $form['deduction'],
                'code' => $form['code'],
                'type' => $form['type'],
                'dlwp' => ($form['dlwp'] == Null) ? 0 : $form['dlwp'],
                'roundup' => ($form['roundup'] == Null) ? 0 : $form['roundup'],
                'against' => ($form['against'] == Null) ? 0 : $form['against'],
                'percentage' => ($form['percentage'] == Null) ? 0 : $form['percentage'],
                'author' => $this->employee_details_id,
                'created' => $this->_created,
                'modified' => $this->_modified,
            );
            //$result = $this->getDefinedTable('Accounts\PayheadTable')->save($data);
            $result = $this->service->saveupdateData('payr_pay_heads', $data);
            if ($result > 0):
                $sub_headdata = array(
                    'head' => $form['head'],
                    'code' => $form['code'],
                    'name' => $form['pay_head'],
                    'author' => $this->employee_details_id,
                    'created' => $this->_created,
                    'modified' => $this->_modified,
                );
                $R_sub_head = $this->service->saveupdateData("accounts_sub_head", $sub_headdata);
                if ($R_sub_head > 0):
                    $master_ddata = array(
                        'Sub_head' => $R_sub_head,
                        'type' => 8,
                        'ref_id' => $result,
                        'code' => $form['code'],
                        'name' => $form['pay_head'],
                        'author' => $this->employee_details_id,
                        'created' => $this->_created,
                        'modified' => $this->_modified,
                    );
                    $result1 = $this->service->saveupdateData("accounts_master_details", $master_ddata);
                    if ($result1 > 0):
                        $this->flashMessenger()->addMessage("success^ New Pay head successfully added");
                    else:
                        $this->flashMessenger()->addMessage("error^ Failed to add new Pay head");
                    endif;
                else:
                    $this->flashMessenger()->addMessage("error^ Failed to add new Pay head");
                endif;
            else:
                $this->flashMessenger()->addMessage("error^ Failed to add new Pay head");
            endif;
            return $this->redirect()->toRoute('master', array('action' => 'payhead'));
        }

        $payhead_detail = $this->service->getTableData($this->pay_head_table);
        $head_detail = $this->service->getTableData($this->head_table);

        return new ViewModel(array(
            'title' => 'Add Payhead',
            'payheads' => $payhead_detail,
            'heads' => $head_detail,
        ));
    }

    /**
     * editpayheadAction
     * */
    public function editpayheadAction() {
        $this->init();
        if ($this->getRequest()->isPost()) {
            $form = $this->getRequest()->getPost();
            $data = array(
                'id' => $this->_id,
                'pay_head' => $form['pay_head'],
                'deduction' => $form['deduction'],
                'code' => $form['code'],
                'type' => $form['type'],
                'dlwp' => ($form['dlwp'] == NULL) ? 0 : $form['dlwp'],
                'roundup' => ($form['roundup'] == NULL) ? 0 : $form['roundup'],
                'against' => ($form['against'] == NULL) ? 0 : $form['against'],
                'percentage' => ($form['percentage'] == Null) ? 0 : $form['percentage'],
                'author' => $this->employee_details_id,
                'modified' => $this->_modified,
            );

            $payhead_result = $this->service->saveupdateData('payr_pay_heads', $data);

            if ($payhead_result > 0):
                if ($form['change_paystructure'] == '1'):

                    foreach ($this->service->getDatabyParam('payr_pay_structure', array('sd.pay_head' => $this->_id), null) as $row):

                        $employee_details = $row['employee_details'];
                        if ($form['against'] == '-1'):

                            $base_amount = $this->service->getDatabyParam('payr_temp_payroll', array('employee_details' => $employee_details), 'gross');

                        elseif ($form['against'] == '-2'):
                            $Gross_amount = $this->$this->service->getDatabyParam('payr_temp_payroll', array('employee_details' => $employee_details), 'gross');

                            $PFDed = $this->service->getDatabyParam('payr_pay_structure', array('employee_details' => $employee_details, 'pay_head' => 7), 'amount');

                            $GISDed = $this->service->getDatabyParam('payr_pay_structure', array('employee_details' => $employee_details, 'pay_head' => 6), 'amount');

                            $base_amount = $Gross_amount - $PFDed - $GISDed;
                        else:

                            $base_amount = $this->service->getDatabyParam('payr_pay_structure', array('employee_details' => $employee_details, 'pay_head' => $form['against']), 'amount');

                        endif;

                        if ($form['type'] == 2):
                            $amount = ($base_amount * $form['percentage']) / 100;
                            if ($form['roundup'] == 1):
                                $amount = round($amount);
                            endif;

                            $ps_data = array(
                                'id' => $row['id'],
                                'percent' => ($form['percentage'] == Null) ? 0 : $form['percentage'],
                                'dlwp' => $form['dlwp'],
                                'amount' => $amount,
                                'author' => $this->employee_details_id,
                                'modified' => $this->_modified,
                            );

                            $result = $this->service->saveupdateData('payr_pay_structure', $ps_data);

                        elseif ($form['type'] == 3):
                            $rate = 0;
                            $base = 0;
                            $value = 0;
                            $min = 0;

                            foreach ($this->service->getDatabyParam('payr_pay_slab', array('pay_head' => $this->_id), null) as $payslab):

                                if ($base_amount >= $payslab['from_range'] && $base_amount <= $payslab['to_range']):
                                    break;

                                endif;

                            endforeach;

                            if ($payslab['formula'] == 1):
                                $rate = $payslab['rate'];
                                $base = $payslab['base'];
                                $min = $payslab['from_range'];

                                if ($base_amount > 158701):
                                    $amount = ((($base_amount - 83338) / 100) * $rate) + $base;
                                else:
                                    $amount = (intval(($base_amount - $min) / 100) * $rate) + $base;
                                endif;
                            else:
                                $amount = $payslab['value'];
                            endif;

                            if ($form['roundup'] == 1):
                                $amount = round($amount);
                            endif;

                            $ps_data = array(
                                'id' => $row['id'],
                                'percent' => ($form['percentage'] == Null) ? 0 : $form['percentage'],
                                'dlwp' => $form['dlwp'],
                                'amount' => $amount,
                                'author' => $this->employee_details_id,
                                'modified' => $this->_modified,
                            );

                            $result = $this->service->saveupdateData('payr_pay_structure', $ps_data);
                        endif;
                    endforeach;
                    foreach ($this->service->getDatabyParam('payr_pay_structure', array('sd.pay_head' => $this->_id), null) as $row):

                        $this->calculatePayheadAmount($row);

                    endforeach;
                endif;

                $sub_headdata = array(
                    'id' => $form['sub_head_id'],
                    'head' => $form['head'],
                    'code' => $form['code'],
                    'name' => $form['pay_head'],
                    'author' => $this->employee_details_id,
                    'modified' => $this->_modified,
                );

                $R_sub_head = $this->service->saveupdateData("accounts_sub_head", $sub_headdata);

                if ($R_sub_head > 0):
                    $masterDtls_data = array(
                        'id' => $form['masterDtls_id'],
                        'sub_head' => $form['sub_head_id'],
                        'type' => 8,
                        'ref_id' => $payhead_result,
                        'code' => $form['code'],
                        'name' => $form['pay_head'],
                        'author' => $this->employee_details_id,
                        'modified' => $this->_modified,
                    );

                    $result1 = $this->service->saveupdateData("accounts_master_details", $masterDtls_data);

                    if ($result1 > 0):
                        $this->flashMessenger()->addMessage("success^ Pay head successfully updated");
                    else:
                        $this->flashMessenger()->addMessage("error^ Failed to update Master Details");
                    endif;
                else:
                    $this->flashMessenger()->addMessage("error^ Failed to update Pay head");
                endif;
            else:
                $this->flashMessenger()->addMessage("error^ Failed to update Pay head");
            endif;
            return $this->redirect()->toRoute('master', array('action' => 'payhead'));
        }
        return new ViewModel(array(
            'title' => 'Edit Payhead',
            'headobj' => $this->service,
            'payhead' => $this->service->getDatabyParam('payr_pay_heads', $this->_id, null),
            'payheads' => $this->service->getTableData('payr_pay_heads'),
            'masterDtls' => $this->service->getDatabyParam('accounts_master_details', array('ref_id' => $this->_id, 'type' => '8'), null),
        ));
    }

    /**
     *  Payhead action
     */
    public function paygroupAction() {
        $this->init();

        return new ViewModel(array(
            'title' => 'Pay Group',
            'paygroups' => $this->service->getTableData($this->pay_group_table),
            'keyphrase' => $this->keyphrase,
        ));
    }

    /**
     * addpayhead action
     */
    public function addpaygroupAction() {
        $this->init();
        if ($this->getRequest()->isPost()) {
            $form = $this->getRequest()->getPost();
            $data = array(
                'group' => $form['group'],
                'pay_head' => $form['pay_head'],
                'value' => $form['value'],
                'author' => $this->employee_details_id,
                'created' => $this->_created,
                'modified' => $this->_modified,
            );
            $result = $this->service->saveupdateData($this->pay_group_table,$data);
            if ($result > 0):
                $this->flashMessenger()->addSuccessMessage("New Pay group successfully added");
            else:
                $this->flashMessenger()->addErrorMessage("Failed to add new Pay group");
            endif;
            return $this->redirect()->toRoute('master', array('action' => 'paygroup'));
        }
        return new ViewModel(array(
            'title' => 'Add Pay Group',
            'payheads' => $this->service->getTableData($this->pay_head_table),
        ));
    }

    /**
     * editpaygroupAction
     * */
    public function editpaygroupAction() {
        $this->init();
        if ($this->getRequest()->isPost()) {
            $form = $this->getRequest()->getPost();
            $data = array(
                'id' => $this->my_decrypt($form['pgrup_id'], $this->keyphrase),
                'group' => $form['group'],
                'pay_head' => $form['pay_head'],
                'value' => $form['value'],
                'author' => $this->employee_details_id,
                'modified' => $this->_modified,
            );
            $result = $this->service->saveupdateData($this->pay_group_table,$data);
            if ($result > 0):
                $this->flashMessenger()->addSuccessMessage("Pay group successfully updated");
            else:
                $this->flashMessenger()->addErrorMessage("Failed to update Pay group");
            endif;
            return $this->redirect()->toRoute('master', array('action' => 'paygroup'));
        }
        return new ViewModel(array(
            'title' => 'Edit Payhead',
            'id' => $this->e_id,
            'paygroup' => $this->service->getDatabyParam($this->pay_group_table,$this->_id,''),
            'payheads' => $this->service->getTableData($this->pay_head_table),
        ));
    }

    /**
     *  Payslab action
     */
    public function payslabAction() {
        $this->init();

        return new ViewModel(array(
            'title' => 'Pay Slab',
            'rowset' => $this->service->getTableData($this->pay_slab_table),
            'payhead' => $this->service->getTableData($this->pay_head_table),
            'payheadObj' => $this->pay_head_table,
            'service' => $this->service,
            'keyphrase' => $this->keyphrase,
        ));
    }

    public function addpayslabAction() {
        $this->init();
        if ($this->getRequest()->isPost()) {
            $form = $this->getRequest();
            $data = array(
                'pay_head' => $form->getPost('pay_head'),
                'formula' => $form->getPost('formula'),
                'from_range' => $form->getPost('from'),
                'to_range' => $form->getPost('to'),
                'rate' => $form->getPost('rate'),
                'base' => $form->getPost('base'),
                'value' => $form->getPost('value'),
                'author' => $this->employee_details_id,
                'created' => $this->_created,
                'modified' => $this->_modified,
            );
            $result = $this->service->saveupdateData($this->pay_slab_table,$data);
            if ($result > 0):
                $this->flashMessenger()->addSuccessMessage("New Pay slab successfully added");
            else:
                $this->flashMessenger()->addErrorMessage("Failed to add new Pay slab");
            endif;
            return $this->redirect()->toRoute('master', array('action' => 'payslab'));
        }
        return new ViewModel(array(
            'payslab' => $this->service->getTableData($this->pay_slab_table),
            'payhead' => $this->service->getTableData($this->pay_head_table),
        ));
    }

    public function editpayslabAction() {
        $this->init();
        if ($this->getRequest()->isPost()) {
            $form = $this->getRequest();
            $data = array(
                'id' => $this->my_decrypt($form->getPost('slabgrup_id'), $this->keyphrase),
                'pay_head' => $form->getPost('pay_head'),
                'formula' => $form->getPost('formula'),
                'from_range' => $form->getPost('from'),
                'to_range' => $form->getPost('to'),
                'rate' => $form->getPost('rate'),
                'base' => $form->getPost('base'),
                'value' => $form->getPost('value'),
                'author' => $this->employee_details_id,
                'created' => $this->_created,
                'modified' => $this->_modified,
            );
            //var_dump($data); exit;
            $result = $this->service->saveupdateData($this->pay_slab_table,$data);
            if ($result > 0):
                $this->flashMessenger()->addSuccessMessage("Pay slab successfully updated");
            else:
                $this->flashMessenger()->addErrorMessage("Failed to update Pay slab");
            endif;
            return $this->redirect()->toRoute('master', array('action' => 'payslab'));
        }
        return new ViewModel(array(
            'id' => $this->e_id,
            'payslab' => $this->service->getDatabyParam($this->pay_slab_table,$this->_id,''),
            'payhead' => $this->service->getTableData($this->pay_head_table),
            'payheadObj' => $this->pay_head_table,
            'service' => $this->service,
        ));
    }

    /*
     * function to calculate payhead amount on change of payheads
     */

    public function calculatePayheadAmount($paystructure) {

        $payhead_id = $paystructure['pay_head_id'];

        $employee = $paystructure['employee'];

        $payhead_type = $this->service->getDatabyParam('payr_pay_heads', $payhead_id, 'payhead_type');

        $deduction = $this->getDefinedTable('Accounts\PayheadtypeTable')->getColumn($payhead_type, 'deduction');

        if ($deduction == 1):
            $affected_ps = $this->service->getDatabyParam('payr_pay_structure', array('sd.employee' => $employee, 'ph.against' => $payhead_id), null);
        else:
            $affected_ps = $this->service->getDatabyParam('payr_pay_structure', array('sd.employee' => $employee, 'ph.against' => array($payhead_id, '-1', '-2')), null);
        endif;

        foreach ($affected_ps as $aff_ps):

            if ($aff_ps['against'] == '-1'):
                $base_amount = $this->service->getDatabyParam('payr_temp_payroll', array('employee' => $employee), 'gross');
            elseif ($form['against'] == '-2'):

                $Gross_amount = $this->service->getDatabyParam('payr_temp_payroll', array('employee' => $employee), 'gross');

                $PFDed = $this->service->getDatabyParam('payr_pay_structure', array('employee' => $employee, 'pay_head' => 7), 'amount');

                $GISDed = $this->service->getDatabyParam('payr_pay_structure', array('employee' => $employee, 'pay_head' => 6), 'amount');
                $base_amount = $Gross_amount - $PFDed - $GISDed;
            else:
                $base_amount = $this->service->getDatabyParam('payr_pay_structure', array('employee' => $employee, 'pay_head' => $aff_ps['against']), 'amount');
            endif;

            if ($aff_ps['type'] == 2):
                $amount = ($base_amount * $aff_ps['percent']) / 100;

                if ($aff_ps['roundup'] == 1):
                    $amount = round($amount);
                endif;
                $data = array(
                    'id' => $aff_ps['id'],
                    'amount' => $amount,
                    'author' => $this->_author,
                    'modified' => $this->_modified,
                );

                $data = $this->_safedataObj->rteSafe($data);

                $result = $this->service->saveupdateData('payr_pay_structure', $data);

            elseif ($aff_ps['type'] == 3):
                $rate = 0;
                $base = 0;
                $value = 0;
                $min = 0;

                foreach ($this->service->getDatabyParam('payr_pay_slab', array('pay_head' => $aff_ps['pay_head_id']), null) as $payslab):

                    if ($base_amount >= $payslab['from_range'] && $base_amount <= $payslab['to_range']):
                        break;
                    endif;

                endforeach;

                if ($payslab['formula'] == 1):
                    $rate = $payslab['rate'];
                    $base = $payslab['base'];
                    $min = $payslab['from_range'];

                    if ($base_amount > 158701):
                        $amount = ((($base_amount - 83338) / 100) * $rate) + $base;
                    else:
                        $amount = (intval(($base_amount - $min) / 100) * $rate) + $base;
                    endif;
                else:
                    $amount = $payslab['value'];
                endif;

                if ($aff_ps['roundup'] == 1):
                    $amount = round($amount);
                endif;

                $data = array(
                    'id' => $aff_ps['id'],
                    'amount' => $amount,
                    'author' => $this->_author,
                    'modified' => $this->_modified,
                );
                $data = $this->_safedataObj->rteSafe($data);

                $result = $this->service->saveupdateData('payr_pay_structure', $data);
            endif;
        endforeach;

        //making changes to temp payroll
        foreach ($this->service->getDatabyParam('payr_temp_payroll', array('pr.employee_details' => $employee),null) as $temp_payroll):
            $total_earning = 0;
            $total_deduction = 0;

            // TODO add deduction from pay head type 0 or 1
            // TODO separate code for both earning and deduction
            // ORGINAL CODE: $this->getDefinedTable('Hr\PaystructureTable')->get(array('sd.employee' => $employee, 'pht.deduction' => '1')
            foreach ($this->service->getDatabyParam('payr_pay_structure', array('sd.employee_details' => $employee, 'ph.deduction' => '1'), null) as $paydetails):
                if ($paydetails['dlwp'] == 1):
                    $amount = $paydetails['amount'] - ($paydetails['amount'] / $temp_payroll['working_days']) * $temp_payroll['leave_without_pay'];
                else:
                    $amount = $paydetails['amount'];
                endif;
                if ($paydetails['roundup'] == 1):
                    $amount = round($amount);
                endif;
                $total_deduction = $total_deduction + $amount;
            endforeach;

            // TODO add deduction from pay head type 0 or 1
        // ORIGINAL CODE : $this->getDefinedTable('Hr\PaystructureTable')->get(array('sd.employee' => $employee, 'pht.deduction' => '0')
            foreach ($this->service->getDatabyParam('payr_pay_structure', array('sd.employee_details' => $employee, 'ph.deduction' => '0'), null) as $paydetails):
                if ($paydetails['dlwp'] == 1):
                    $amount = $paydetails['amount'] - ($paydetails['amount'] / $temp_payroll['working_days']) * $temp_payroll['leave_without_pay'];
                else:
                    $amount = $paydetails['amount'];
                endif;
                if ($paydetails['roundup'] == 1):
                    $amount = round($amount);
                endif;
                $total_earning = $total_earning + $amount;
            endforeach;

            $leave_encashment = $temp_payroll['leave_encashment'];
            $bonus = $temp_payroll['bonus'];
            $net_pay = $total_earning + $leave_encashment + $bonus - $total_deduction;
            $data1 = array(
                'id' => $temp_payroll['id'],
                'gross' => $total_earning,
                'total_deduction' => $total_deduction,
                'net_pay' => $net_pay,
                'author' => $this->_author,
                'modified' => $this->_modified,
            );
            $data1 = $this->_safedataObj->rteSafe($data1);

            $result1 = $this->service->saveupdateData('payr_temp_payroll', $data1);
        endforeach;
        return $result1;
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
        if ($len % 2) {
            return "ERROR";
        } else {
            // To decrypt, split the encrypted data from our IV - our unique separator used was "::"
            list($encrypted_data, $iv) = explode('::', base64_decode(hex2bin($data)), 2);
            return openssl_decrypt($encrypted_data, 'BF-CFB', $encryption_key, 0, $iv);
        }
    }
}

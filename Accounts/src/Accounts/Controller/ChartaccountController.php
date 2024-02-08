<?php

namespace Accounts\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\View\Model\ViewModel;
use Zend\Db\TableGateway\TableGateway;
use Zend\Authentication\AuthenticationService;
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;
use Accounts\Service\MasterServiceInterface;

class ChartaccountController extends AbstractActionController {

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
    protected $notificationService;
    protected $auditTrailService;
    protected $serviceLocator;
    protected $_created;  // current date to be used as created dated
    protected $_modified;  // current date to be used as modified date
    protected $keyphrase = "RUB_IMS";
    protected $class_table = 'accounts_class';
    protected $group_table = 'accounts_group';
    protected $head_table = 'accounts_head';
    protected $subhead_table = 'accounts_sub_head';
    protected $master_table = 'accounts_master_details';
    protected $journal_table = 'accounts_journal';

    public function __construct(MasterServiceInterface $service, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator) {
        $this->service = $service;
        $this->serviceLocator = $serviceLocator;
        $this->notificationService = $notificationService;
        $this->auditTrailService = $auditTrailService;
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

        $id_from_route = $this->params()->fromRoute('id');
        $this->e_id = $id_from_route;
        if ($id_from_route)
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
     *  index action
     */
    public function indexAction() {
        $this->init();
        return new ViewModel(array(
            'title' => 'Chart of Account',
            'classes' => $this->service->getTableData($this->class_table),
            'service' => $this->service,
            'groupObj' => $this->group_table,
            'headObj' => $this->head_table,
            'subheadObj' => $this->subhead_table,
        ));
    }

    /**
     *  class action
     */
    public function classAction() {
        $this->init();
        return new ViewModel(array(
            'title' => 'Class',
            'keyphrase' => $this->keyphrase,
            'class' => $this->service->getTableData($this->class_table),
        ));
    }

    /**
     *  function/action to add class
     */
    public function addclassAction() {
        $this->init();

        if ($this->getRequest()->isPost()) {
            $form = $this->getRequest()->getPost();
            $data = array(
                'code' => $form['code'],
                'name' => $form['name'],
                'author' => $this->employee_details_id,
                'created' => $this->_created,
                'modified' => $this->_modified,
            );
            $result = $this->service->saveupdateData($this->class_table,$data);

            if ($result > 0):
                $this->flashMessenger()->addSuccessMessage("New Class successfully added");
            else:
                $this->flashMessenger()->addErrorMessage("Failed to add new Class");
            endif;
            return $this->redirect()->toRoute('chartaccount');
        }
        return new ViewModel(array(
        ));
    }

    /**
     *  function/action to edit class
     */
    public function editclassAction() {
        $this->init();
        if ($this->getRequest()->isPost()) {
            $form = $this->getRequest()->getPost();
            $data = array(
                'id' => $this->my_decrypt($form['class_id'], $this->keyphrase),
                'code' => $form['code'],
                'name' => $form['name'],
                'author' => $this->employee_details_id,
                'modified' => $this->_modified,
            );
            $result = $this->service->saveupdateData($this->class_table,$data);
            if ($result > 0):
                $this->flashMessenger()->addSuccessMessage("Class successfully updated");
            else:
                $this->flashMessenger()->addErrorMessage("Failed to update Class");
            endif;
            return $this->redirect()->toRoute('chartaccount');
        }
        return new ViewModel(array(
            'id' => $this->e_id,
            'class' => $this->service->getDatabyParam($this->class_table,$this->_id,''),
        ));
    }

    /**
     *  group action
     */
    public function groupAction() {
        $this->init();
        return new ViewModel(array(
            'title' => 'Group',
            'keyphrase' => $this->keyphrase,
            'class' => $this->service->getTableData($this->class_table),
            'groups' => $this->service->getTableData($this->group_table),
        ));
    }

    /**
     *  function/action to add group
     */
    public function addgroupAction() {
        $this->init();
        if ($this->getRequest()->isPost()) {
            $form = $this->getRequest()->getPost();
            $data = array(
                'code' => $form['code'],
                'name' => $form['name'],
                'class' => $form['class'],
                'author' => $this->employee_details_id,
                'created' => $this->_created,
                'modified' => $this->_modified,
            );
            $result = $this->service->saveupdateData($this->group_table,$data);
            if ($result > 0):
                $this->flashMessenger()->addSuccessMessage("New Group successfully added");
            else:
                $this->flashMessenger()->addErrorMessage("Failed to add new Group");
            endif;
            return $this->redirect()->toRoute('chartaccount', array('action' => 'group'));
        }
        return new ViewModel(array(
            'class' => $this->service->getTableData($this->class_table),
        ));
    }

    /**
     *  function/action to edit group
     */
    public function editgroupAction() {
        $this->init();
        if ($this->getRequest()->isPost()) {
            $form = $this->getRequest()->getPost();
            $data = array(
                'id' => $this->my_decrypt($form['group_id'], $this->keyphrase),
                'code' => $form['code'],
                'name' => $form['name'],
                'class' => $form['class'],
                'author' => $this->employee_details_id,
                'modified' => $this->_modified,
            );
            $result = $this->service->saveupdateData($this->group_table,$data);
            if ($result > 0):
                $this->flashMessenger()->addSuccessMessage("Group successfully updated");
            else:
                $this->flashMessenger()->addErrorMessage("Failed to update Group");
            endif;
            return $this->redirect()->toRoute('chartaccount', array('action' => 'group'));
        }

        return new ViewModel(array(
            'id' => $this->e_id,
            'group' => $this->service->getDatabyParam($this->group_table,$this->_id,''),
            'class' => $this->service->getTableData($this->class_table),
        ));
    }

    /**
     *  head action
     */
    public function headAction() {
        $this->init();
        return new ViewModel(array(
            'title' => 'Head',
            'keyphrase' => $this->keyphrase,
            'head' => $this->service->getTableData($this->head_table),
        ));
    }

    /**
     *  function/action to add head
     * */
    public function addheadAction() {
        $this->init();
        if ($this->getRequest()->isPost()) {
            $form = $this->getRequest()->getPost();
            $data = array(
                'code' => $form['code'],
                'name' => $form['name'],
                'group' => $form['group'],
                'author' => $this->employee_details_id,
                'created' => $this->_created,
                'modified' => $this->_modified,
            );
            $result = $this->service->saveupdateData($this->head_table,$data);
            if ($result > 0):
                $this->flashMessenger()->addSuccessMessage("New Head successfully added");
            else:
                $this->flashMessenger()->addErrorMessage("Failed to add new Head");
            endif;
            return $this->redirect()->toRoute('chartaccount', array('action' => 'head'));
        }
        return new ViewModel(array(
            'group' => $this->service->getTableData($this->group_table),
        ));
    }

    /**
     *   function/action to edit head
     * */
    public function editheadAction() {
        $this->init();
        if ($this->getRequest()->isPost()) {
            $form = $this->getRequest()->getPost();
            $data = array(
                'id' => $this->my_decrypt($form['head_id'], $this->keyphrase),
                'code' => $form['code'],
                'name' => $form['name'],
                'group' => $form['group'],
                'author' => $this->employee_details_id,
                'modified' => $this->_modified,
            );
            $result = $this->service->saveupdateData($this->head_table,$data);
            if ($result > 0):
                $this->flashMessenger()->addSuccessMessage("Head successfully updated");
            else:
                $this->flashMessenger()->addErrorMessage("Failed to update Head");
            endif;
            return $this->redirect()->toRoute('chartaccount', array('action' => 'head'));
        }
        return new ViewModel(array(
            'id' => $this->e_id,
            'head' => $this->service->getDatabyParam($this->head_table,$this->_id,''),
            'group' => $this->service->getTableData($this->group_table),
        ));
    }

    /**
     *  subhead action
     */
    public function subheadAction() {
        $this->init();
        if ($this->getRequest()->isPost()):
            $form = $this->getRequest()->getPost();
            $class = $form['class'];
            $group = $form['group'];
            $head = $form['head'];
        else:
            $class = '-1';
            $group = '-1';
            $head = '-1';
        endif;
        $data = array(
            'class' => $class,
            'group' => $group,
            'head' => $head,
        );
        $subheads = $this->service->getSubheadData($this->subhead_table,$data);

        return new ViewModel(array(
            'title' => 'SubHead',
            'keyphrase' => $this->keyphrase,
            'data' => $data,
            'service' => $this->service,
            'classObj' => $this->class_table,
            'groupObj' => $this->group_table,
            'headObj' => $this->head_table,
            'subheads' => $subheads,
        ));
    }

    /**
     * get group by class
     * */
    public function getgroupAction() {
        $this->init();
        $form = $this->getRequest()->getPost();
        $grp = '';
        $class_id = $form['class'];
        $groups = $this->service->getDatabyParam($this->group_table,array('class' => $class_id),'');

        $grp .= "<option value='-1'>All</option>";
        foreach ($groups as $group):
            $grp .= "<option value='" . $group['id'] . "'>" . $group['code'] . "</option>";
        endforeach;

        echo $grp;
        exit;
    }

    /**
     * get group by class
     * */
    public function getheadAction() {
        $this->init();
        $form = $this->getRequest()->getPost();

        $group_id = $form['group'];
        $heads = $this->service->getDatabyParam($this->head_table,array('group' => $group_id),'');

        $hd .= "<option value='-1'>All</option>";
        foreach ($heads as $head):
            $hd .= "<option value='" . $head['id'] . "'>" . $head['code'] . "</option>";
        endforeach;

        echo $hd;
        exit;
    }

    /**
     *  function/action to add subhead
     * */
    public function addsubheadAction() {
        $this->init();
        if ($this->getRequest()->isPost()) {
            $form = $this->getRequest()->getPost();
            $data = array(
                'head' => $form['head'],
                'code' => $form['shcode'],
                'name' => $form['shname'],
                'author' => $this->employee_details_id,
                'created' => $this->_created,
                'modified' => $this->_modified,
            );
            $result = $this->service->saveupdateData($this->subhead_table,$data);
            if ($result > 0):
                $master_details_data = array(
                    'Sub_head' => $result,
                    'type' => 9,
                    'ref_id' => $result,
                    'code' => $form['shcode'],
                    'name' => $form['shname'],
                    'author' => $this->employee_details_id,
                    'created' => $this->_created,
                    'modified' => $this->_modified,
                );
                $MD_result = $this->service->saveupdateData($this->master_table,$master_details_data);
                if ($MD_result > 0):
                    $MD_result = $this->service->saveupdateData($this->master_table,array('id' => $MD_result, 'ref_id' => $MD_result));
                    $this->flashMessenger()->addSuccessMessage("New Subhead successfully added");
                else:
                    $this->flashMessenger()->addErrorMessage("Failed to update details");
                endif;
            else:
                $this->flashMessenger()->addErrorMessage("Failed to add new Subhead");
            endif;
            return $this->redirect()->toRoute('chartaccount', array('action' => 'subhead'));
        }
        return new ViewModel(array(
            'service' => $this->service,
            'headObj' => $this->head_table,
        ));
    }

    /**
     *  function/action to edit subhead
     * */
    public function editsubheadAction() {
        $this->init();
        if ($this->getRequest()->isPost()) {
            $form = $this->getRequest()->getPost();
            $data = array(
                'id' => $this->my_decrypt($form['shead_id'], $this->keyphrase),
                'head' => $form['head'],
                'code' => $form['shcode'],
                'name' => $form['shname'],
                'author' => $this->employee_details_id,
                'modified' => $this->_modified,
            );

            $result = $this->service->saveupdateData($this->subhead_table,$data);
            if ($result > 0):
                $get_id = $this->service->getDatabyParam($this->master_table,array('sub_head' => $form['subhead_id']),'id');
                $master_details_data = array(
                    'id' => $get_id[0]['id'],
                    'Sub_head' => $form['subhead_id'],
                    'type' => 9,
                    'ref_id' => $result,
                    'code' => $form['shcode'],
                    'name' => $form['shname'],
                    'author' => $this->employee_details_id,
                    'modified' => $this->_modified,
                );
                $MD_result = $this->service->saveupdateData($this->master_table,$master_details_data);
                if ($MD_result > 0):
                    $MD_result = $this->service->saveupdateData($this->master_table,array('id' => $MD_result, 'ref_id' => $MD_result));
                    $this->flashMessenger()->addSuccessMessage("New Subhead successfully updated");
                else:
                    $this->flashMessenger()->addErrorMessage("Failed to update details");
                endif;
            else:
                $this->flashMessenger()->addErrorMessage("Failed to add new Subhead");
            endif;
            return $this->redirect()->toRoute('chartaccount', array('action' => 'subhead'));
        }

        return new ViewModel(array(
            'title' => 'Edit Sub Head',
            'id' => $this->e_id,
            'service' => $this->service,
            'subheads' => $this->service->getDatabyParam($this->subhead_table,$this->_id,''),
            'headObj' => $this->head_table
        ));
    }

    /**
     *  journal action
     */
    public function journalAction() {
        $this->init();
        return new ViewModel(array(
            'title' => 'Journal',
            'keyphrase' => $this->keyphrase,
            'journal' => $this->service->getTableData($this->journal_table),
        ));
    }

    /**
     *  function/action to add Party Role
     */
    public function addjournalAction() {
        $this->init();
        if ($this->getRequest()->isPost()) {
            $form = $this->getRequest()->getPost();
            $data = array(
                'code' => $form['code'],
                'journal' => $form['journal'],
                'prefix' => $form['prefix'],
                'author' => $this->employee_details_id,
                'created' => $this->_created,
                'modified' => $this->_modified,
            );
            $result = $this->service->saveupdateData($this->journal_table,$data);
            if ($result > 0):
                $this->flashMessenger()->addSuccessMessage("New Journal successfully added");
            else:
                $this->flashMessenger()->addErrorMessage("Failed to add new Journal");
            endif;
            return $this->redirect()->toRoute('chartaccount', array('action' => 'journal'));
        }
        return new ViewModel(array(
        ));
    }

    /**
     * edit journal action
     * */
    public function editjournalAction() {
        $this->init();
        if ($this->getRequest()->isPost()) {
            $form = $this->getRequest()->getPost();
            $data = array(
                'id' => $this->my_decrypt($form['journal_id'], $this->keyphrase),
                'code' => $form['code'],
                'journal' => $form['journal'],
                'prefix' => $form['prefix'],
                'author' => $this->employee_details_id,
                'modified' => $this->_modified,
            );
            $result = $this->service->saveupdateData($this->journal_table,$data);
            if ($result > 0):
                $this->flashMessenger()->addSuccessMessage("Journal successfully updated");
            else:
                $this->flashMessenger()->addErrorMessage("Failed to update Journal");
            endif;
            return $this->redirect()->toRoute('chartaccount', array('action' => 'journal'));
        }
        return new ViewModel(array(
            'id' => $this->e_id,
            'journal' => $this->service->getDatabyParam($this->journal_table,$this->_id,''),
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
        if ($len % 2) {
            return "ERROR";
        } else {
            // To decrypt, split the encrypted data from our IV - our unique separator used was "::"
            list($encrypted_data, $iv) = explode('::', base64_decode(hex2bin($data)), 2);
            return openssl_decrypt($encrypted_data, 'BF-CFB', $encryption_key, 0, $iv);
        }
    }    
}

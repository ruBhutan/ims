<?php
namespace Accounts\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\TableGateway\TableGateway;
use Zend\Authentication\AuthenticationService;
use Zend\Stdlib\ArrayObject;
use Zend\Validator\File\Size;
use Zend\Validator\File\Extension;

use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;

class AssetController extends AbstractActionController
{   
	protected $_table; 		// database table 
	
	/**
     * initial set up
     * general variables are defined here
     */
	public function init()
	{				
		$user_session = new Container('user');
        $this->username = $user_session->username;
		
		$empData = $this->getDefinedTable('Accounts\EmployeeDetailsTable')->getUserDetails($this->username);
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
		}
		
		$organisationID = $this->getDefinedTable('Accounts\EmployeeDetailsTable')->getUserDetails($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}

		$this->_id = $this->params()->fromRoute('id');

		$this->_created = date('Y-m-d H:i:s');
		$this->_modified = date('Y-m-d H:i:s');
	}
	/**
	 * Zend Default TableGateway
	 * Table name as the parameter
	 * returns obj
	 */
	public function getDefaultTable($table)
	{
		$this->_table = new TableGateway($table, $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'));
		return $this->_table;
	}

   /**
    * User defined Model
    * Table name as the parameter
    * returns obj
    */
    public function getDefinedTable($table)
    {
    	$sm = $this->getServiceLocator();
    	$this->_table = $sm->get($table);
    	return $this->_table;
    }
	/**
	 *  index action
	 */
	public function indexAction()
	{
		$this->init();
		return new ViewModel(array());
	}
	/**
	 *  party action
	 */
	public function partyAction()
	{
		$this->init();
	    $rowset = $this->getDefinedTable('Accounts\PartyTable')->get(array('organisation_id'=>$this->organisation_id));
		return new ViewModel(array(
			'title'  => 'Party',
			'rowset' => $rowset,
		));
	} 	
	/**
	 * addparty action
	 **/
	public function addpartyAction()
	{
	    $this->init();
		
		if($this->getRequest()->isPost()){
			$data = array_merge_recursive(
				$this->getRequest()->getPost()->toArray()
			);
			$data1 = array(
				'code' => $data['code'],
				'name' => $data['name'],
				'organisation_id' => $data['organisation'],
				'party_role' => $data['party_role'],
				'supplier_address' => $data['address'],
				'supplier_license_no' => $data['supplier_license_no'],
				'supplier_tpn_no' => $data['supplier_tpn_no'],
				'supplier_bank_acc_no' => $data['supplier_bank_acc_no'],
				'supplier_contact_no' => $data['supplier_contact_no'],
				'supplier_status' => $data['active'],
				'author' =>$this->employee_details_id ,
				'created' =>$this->_created,
				'modified' =>$this->_modified,
			);	
			$result = $this->getDefinedTable('Accounts\PartyTable')->save($data1);	
			if($result > 0):
				$subhead_details = $this->getDefinedTable('Accounts\SubHeadTable')->getNotInMD($result);
				foreach($subhead_details as $sh_row):
					$master_detailsdata = array(
					'sub_head' => $sh_row['id'],
					'ref_id'   =>$result,
					'type'     =>6,
					'code'     => $data['code'],
					'name'     => $data['name'],
					'author' =>$this->employee_details_id ,
					'created' =>$this->_created,
					'modified' =>$this->_modified,
				);
				$R_Mdetails = $this->getDefinedTable("Accounts\MasterDetailsTable")->save($master_detailsdata);
				endforeach;
				$this->flashMessenger()->addMessage("success^ New party successfully added");
				return $this->redirect()->toRoute('asset', array('action'=>'party'));
			else:
				$this->flashMessenger()->addMessage("Failed^ Failed to add new party");
				return $this->redirect()->toRoute('asset', array('action'=>'party'));
			endif;
		}
		$organisation = $this->organisation_id; 
		$ViewModel = new ViewModel(array(
			'organisations' => $this->getDefinedTable('Hr\OrganisationTable')->get(array('id'=>$organisation)),
			'organisation' => $organisation,
			'proles' => $this->getDefinedTable('Accounts\PartyRoleTable')->getAll(),
		));
		return $ViewModel;			
	}
	
	/*
	 * party editAction
	 * */
	public function editpartyAction()
	{
		$this->init();
		if($this->getRequest()->isPost()){
			$form = $this->getRequest()->getPost();
            $subhead_id = $form['subhead_id'];			
			$data = array(
					'id' => $this->_id,
					'code' => $form['code'],
					'name' => $form['name'],
					'organisation_id' => $form['organisation'],
					'supplier_address' => $data['address'],
					'supplier_license_no' => $data['supplier_license_no'],
					'supplier_tpn_no' => $data['supplier_tpn_no'],
					'supplier_bank_acc_no' => $data['supplier_bank_acc_no'],
					'supplier_contact_no' => $data['supplier_contact_no'],
					'supplier_status' => $data['active'],
					'author' =>$this->employee_details_id ,
					'modified' =>$this->_modified,
			);
			$result = $this->getDefinedTable('Accounts\PartyTable')->save($data);
			if($result > 0 && $subhead_id > 0){
				for($i=0; $i < sizeof($subhead_id); $i++):
					$master_detailsdata = array(
						'sub_head' => $subhead_id[$i],
						'type' => 6,
						'ref_id' => $result,
						'code' => $form['code'],
						'name' => $form['name'],
						'author' =>$this->employee_details_id ,
						'modified' =>$this->_modified,
					);
				    $this->getDefinedTable("Accounts\MasterDetailsTable")->save($master_detailsdata);
				endfor;	
				$this->flashmessenger()->addMessage('success^ Successfully updated party '.$form['name']);
			}
			else {
				$this->flashmessenger()->addMessage('error^ Failed to update party');
			}
			return $this->redirect()->toRoute('asset', array('action'=>'party'));
		}
			
		$ViewModel = new ViewModel(array(
				'title' => 'Edit Party',
		        'id' => $this->_id,
				'party' => $this->getDefinedTable('Accounts\PartyTable')->get($this->_id),
				'proleObj' => $this->getDefinedTable('Accounts\PartyRoleTable'),
				'master_details' => $this->getDefinedTable('Accounts\MasterDetailsTable')->get(array('ref_id' => $this->_id, 'md.type' => '6')),
				'subhead_details' => $this->getDefinedTable('Accounts\SubHeadTable')->getNotInMD($this->_id),
				'orgObj' => $this->getDefinedTable('Hr\OrganisationTable'),
			    'userorg' => $this->organisation_id,			
		));
		return $ViewModel;
	}
	/**
	 *  bankaccount action
	 */
	public function bankaccountAction()
	{
		$this->init();
		return new ViewModel(array(
			'title'  => 'Bank Account',
			'rowset' => $this->getDefinedTable('Accounts\BankaccountTable')->get(array('organisation_id'=>$this->organisation_id )),
		));
	} 
	
	/**
	 * addbankaccount Action
	 **/
	public function addbankaccountAction()
	{
		$this->init();
		if($this->getRequest()->isPost()){
			$form = $this->getRequest()->getPost();
			$data = array(
				'bank_account' => $form['account'],
				'branch' => $form['branch'],
				'organisation_id' => $form['organisation'],
				'author' => $this->employee_details_id,
				'created' => $this->_created,
				'modified' => $this->_modified,
			);
			$result = $this->getDefinedTable('Accounts\BankaccountTable')->save($data);
			if($result > 0){
				$subheaddata = array(
					'head' => $form['head'],
					'code' => $form['account'],
					'name' => $form['account'],
					'author' => $this->employee_details_id,
					'created' => $this->_created,
					'modified' => $this->_modified,
				);
				$R_Sub_head = $this->getDefinedTable("Accounts\SubheadTable")->save($subheaddata);
				if($R_Sub_head > 0){
					$md_data = array(
						'sub_head' =>$R_Sub_head,
						'type' => 2,
						'ref_id' => $result,
						'code' => $form['account'],
						'name' => $form['account'],
						'author' => $this->employee_details_id,
						'created' => $this->_created,
						'modified' => $this->_modified,
					);
					$R_M_details = $this->getDefinedTable("Accounts\MasterDetailsTable")->save($md_data);
					if($R_M_details > 0){
					    $this->flashMessenger()->addMessage("success^ New bank account successfully added and linked with Sub Head and Master Details");
					}else{
					$this->flashMessenger()->addMessage("Failed^ Failed to add in Master Details");
					}
				}else{
					$this->flashMessenger()->addMessage("Failed^ Failed to add in Sub Head Details");
				}
			}else{
				$this->flashMessenger()->addMessage("Failed^ Failed to add new bank account");
			}
			return $this->redirect()->toRoute('asset', array('action'=>'bankaccount'));
		}
		return $ViewModel = new ViewModel(array(
			'title' => 'Add Bank Account',
	        'rows' => $this->getDefinedTable('Hr\OrganisationTable')->get(array('id'=>$this->organisation_id)),
			'head' => $this->getDefinedTable('Accounts\HeadTable'),
			'organisation'=>$this->organisation_id,
		));	
	}
	
	/**
	 * bankaccount editAction
	 **/
	public function editbankaccountAction()
	{
		$this->init();
		if($this->getRequest()->isPost()){
			$form = $this->getRequest()->getPost();
			$data = array(
				'id' => $this->_id,
				'bank_account' => $form['account'],
				'branch' => $form['branch'],
				'organisation_id' => $form['organisation'],
				'author' => $this->employee_details_id,
				'modified' => $this->_modified,
			);
			$result = $this->getDefinedTable('Accounts\BankaccountTable')->save($data);
			if($result > 0){
				$subhead_id = $form['subhead_id'];
				$masterdetail_id = $form['masterdetail_id'];
				$head= $form['head'];
				$delete_rows = $this->getDefinedTable('Accounts\MasterDetailsTable')->getNotIn($masterdetail_id, array('ref_id' => $this->_id, 'type' => '2'));
				for($i=0; $i < sizeof($head); $i++):
					if(isset($head[$i]) && $head[$i] > 0):
						if($subhead_id[$i] > 0){
							$subheaddata = array(
								'id'   => $subhead_id[$i],
								'head' => $head[$i],
								'code' => $form['account'],
								'name' => $form['account'],
								'author' => $this->employee_details_id,
				                'modified' => $this->_modified,
							);
						    $this->getDefinedTable("Accounts\SubHeadTable")->save($subheaddata);
							if($masterdetail_id[$i] > 0 && $subhead_id[$i] > 0){
								$master_detailsdata = array(
									'id'   => $masterdetail_id[$i],
									'sub_head' => $subhead_id[$i],
									'type' =>2,
									'ref_id' =>$result,
									'code' =>$form['account'],
									'name' =>$form['account'],
									'author' => $this->employee_details_id,
				                    'modified' => $this->_modified,
								);
							}else{
								$master_detailsdata = array(
									'sub_head' => $subhead_id[$i],
									'type' =>2,
									'ref_id' =>$result,
									'code' => $form['account'],
									'name' => $form['account'],
									'author' => $this->employee_details_id,
				                    'modified' => $this->_modified,
								);
							}
							$this->getDefinedTable("Accounts\MasterDetailsTable")->save($master_detailsdata);
						}else{
							$subheaddata = array(
								'head' => $head[$i],
								'code' => $form['account'],
								'name' => $form['account'],
							    'author' => $this->employee_details_id,
				                'modified' => $this->_modified,
							);
						    $this->getDefinedTable("Accounts\SubHeadTable")->save($subheaddata);
							if($masterdetail_id[$i] > 0 && $subhead_id[$i] > 0){
								$master_detailsdata = array(
									'id'   => $masterdetail_id[$i],
									'sub_head' => $subhead_id[$i],
									'type' =>2,
									'ref_id' =>$result,
									'code' => $form['account'],
									'name' => $form['account'],
									'author' => $this->employee_details_id,
				                    'modified' => $this->_modified,
								);
							}else{
								$master_detailsdata = array(
									'sub_head' => $subhead_id[$i],
									'type' =>2,
									'ref_id' =>$result,
									'code' => $form['account'],
									'name' => $form['account'],
									'author' => $this->employee_details_id,
				                    'modified' => $this->_modified,
								);
							}
							$this->getDefinedTable("Accounts\MasterDetailsTable")->save($master_detailsdata);
						}
					endif;
				endfor;	
				foreach($delete_rows as $delete_row):
				 	$this->getDefinedTable('Accounts\MasterDetailsTable')->remove($delete_row['id']);
				endforeach;
				$this->flashmessenger()->addMessage('success^ Bank Account Successfully Updated');
				return $this->redirect()->toRoute('asset', array('action'=>'bankaccount'));			}
			else {
				$this->flashmessenger()->addMessage('error^ Failed to update bank account.');
				return $this->redirect()->toRoute('asset', array('action' => 'bankaccount'));
			}
		}			
		$ViewModel = new ViewModel(array(
			'title' => 'Edit Bank Account',
			'id' => $this->_id,
			'bankaccount' => $this->getDefinedTable('Accounts\BankaccountTable')->get($this->_id),
			'master_details' => $this->getDefinedTable('Accounts\MasterDetailsTable')->get(array('ref_id' =>$this->_id, 'md.type' => '2')),
			'headObj' => $this->getDefinedTable('Accounts\HeadTable'),
			'subheadObj' => $this->getDefinedTable('Accounts\SubHeadTable'),
			'orgObj' => $this->getDefinedTable('Hr\OrganisationTable'),
			'userorg' => $this->organisation_id,			
		));
		return $ViewModel;
	}	  
	/**
	 *  cash account action
	 */
	public function cashaccountAction()
	{
		$this->init();
		return new ViewModel(array(
			'title'  => 'Cash Account',
			'rowset' => $this->getDefinedTable('Accounts\CashaccountTable')->get(array('organisation_id'=>$this->organisation_id)),
			'orgObj'=> $this->getDefinedTable('Hr\OrganisationTable'),
		));
	} 
	/**
	 * addcashaccount Action
	 **/
	public function addcashaccountAction()
	{
		$this->init();
		if($this->getRequest()->isPost()){
			$form = $this->getRequest()->getPost();
			$data = array(
				'cash_account' => $form['cash_account'],
				'organisation_id' => $form['organisation'],
				'author' => $this->employee_details_id,
				'created' => $this->_created,
				'modified' => $this->_modified,
			);
			$result = $this->getDefinedTable('Accounts\CashaccountTable')->save($data);
			if($result > 0):
				$head= $form['head'];
				for($i=0; $i < sizeof($head); $i++):
					if(isset($head[$i]) && is_numeric($head[$i])):
						$subheaddata = array(
							'head' => $head[$i],
							'code' => $form['cash_account'],
							'name' => $form['cash_account'],
							'author' => $this->employee_details_id,
						    'created' => $this->_created,
						    'modified' => $this->_modified,
						);
						$resultSH = $this->getDefinedTable("Accounts\SubheadTable")->save($subheaddata);
						if($resultSH > 0):
							$master_detailsdata = array(
								'sub_head' =>$resultSH,
								'type'  =>3,
								'ref_id'  =>$result,
								'code' => $form['cash_account'],
								'name' => $form['cash_account'],
								'author' => $this->employee_details_id,
								'created' => $this->_created,
								'modified' => $this->_modified,
							);
							$this->getDefinedTable("Accounts\MasterDetailsTable")->save($master_detailsdata);
					    endif;
					endif;
				endfor;
				$this->flashMessenger()->addMessage("success^ New cash account successfully added");
			else:
				$this->flashMessenger()->addMessage("error^ Failed to add new cash account");
			endif;
			return $this->redirect()->toRoute('asset', array('action'=>'cashaccount'));
		}
		$organisation = $this->organisation_id;  
		return $ViewModel = new ViewModel(array(
				'title' => 'Add Cash Account',
				'rows' => $this->getDefinedTable('Hr\OrganisationTable')->get(array('id'=>$organisation)),
				'headObj' => $this->getDefinedTable('Accounts\HeadTable'),
				'organisation' => $organisation,
		));	
	}
	/**
	 * cashaccount editAction
	 **/
	public function editcashaccountAction()
	{
		$this->init();
		if($this->getRequest()->isPost()){
			$form = $this->getRequest()->getPost();
			$data = array(
				'id' => $this->_id,
				'organisation_id' => $form['organisation'],
				'cash_account' => $form['cash_account'],
				'author' =>$this->employee_details_id ,
				'modified' =>$this->_modified,
			);
			$result = $this->getDefinedTable('Accounts\CashaccountTable')->save($data);
			if($result > 0){
				$subhead_id= $form['subhead_id'];
				$masterdetail_id= $form['masterdetail_id'];
				$head= $form['head'];
				$delete_rows = $this->getDefinedTable('Accounts\MasterDetailsTable')->getNotIn($masterdetail_id, array('ref_id' => $this->_id, 'type' => '3'));
				for($i=0; $i < sizeof($head); $i++):
					if(isset($head[$i]) && $head[$i] > 0):
						if($subhead_id[$i] > 0){
							$subheaddata = array(
								'id'   => $subhead_id[$i],
								'head' => $head[$i],
								'code' => $form['cash_account'],
								'name' => $form['cash_account'],
								'author' =>$this->employee_details_id ,
								'modified' =>$this->_modified,
							);
						    $this->getDefinedTable("Accounts\SubheadTable")->save($subheaddata);
							if($masterdetail_id[$i] > 0 && $subhead_id[$i] > 0){
								$master_detailsdata = array(
									'id'   => $masterdetail_id[$i],
									'sub_head' => $subhead_id[$i],
									'type' =>3,
									'ref_id' =>$result,
								    'code' => $form['cash_account'],
								    'name' => $form['cash_account'],
									'author' =>$this->employee_details_id ,
									'modified' =>$this->_modified,
								);
							}else{
								$master_detailsdata = array(
									'sub_head' => $subhead_id[$i],
									'type' =>2,
									'ref_id' =>$result,
									'code' => $form['cash_account'],
									'name' => $form['cash_account'],
									'author' =>$this->employee_details_id ,
									'modified' =>$this->_modified,
								);
							}
							$this->getDefinedTable("Accounts\MasterDetailsTable")->save($master_detailsdata);
						}else{
							$subheaddata = array(
								'head' => $head[$i],
								'code' => $form['cash_account'],
								'name' => $form['cash_account'],
								'author' =>$this->employee_details_id ,
								'modified' =>$this->_modified,
							);
						    $this->getDefinedTable("Accounts\SubHeadTable")->save($subheaddata);
							if($masterdetail_id[$i] > 0 && $subhead_id[$i] > 0){
								$master_detailsdata = array(
									'id'   => $masterdetail_id[$i],
									'sub_head' => $subhead_id[$i],
									'type' =>2,
									'ref_id' =>$result,
									'code' => $form['account'],
									'name' => $form['account'],
								    'author' =>$this->employee_details_id ,
									'modified' =>$this->_modified,
								);
							}else{
								$master_detailsdata = array(
									'sub_head' => $subhead_id[$i],
									'type' =>2,
									'ref_id' =>$result,
									'code' => $form['account'],
									'name' => $form['account'],
									'author' =>$this->employee_details_id ,
									'modified' =>$this->_modified,
								);
							}
							$this->getDefinedTable("Accounts\MasterDetailsTable")->save($master_detailsdata);
						}
					endif;
				endfor;	
				foreach($delete_rows as $delete_row):
				 	$this->getDefinedTable('Accounts\MasterDetailsTable')->remove($delete_row['id']);
				endforeach;
				$this->flashmessenger()->addMessage('success^ Cash Account Successfully Updated');
				return $this->redirect()->toRoute('asset', array('action'=>'cashaccount'));			}
			else {
				$this->flashmessenger()->addMessage('error^ Failed to update cash account.');
				return $this->redirect()->toRoute('asset', array('action' => 'cashaccount'));
			}
		}			
		$ViewModel = new ViewModel(array(
			'title' => 'Edit Cash Account',
			'id' => $this->_id,
			'cashaccounts' => $this->getDefinedTable('Accounts\CashaccountTable')->get($this->_id),
			'master_details' => $this->getDefinedTable('Accounts\MasterDetailsTable')->get(array('ref_id' => $this->_id, 'md.type' => '3')),
			'orgObj' => $this->getDefinedTable('Hr\OrganisationTable'),
			'headObj' => $this->getDefinedTable('Accounts\HeadTable'),
			'subheadObj' => $this->getDefinedTable('Accounts\SubHeadTable'),
			'userorg' => $this->organisation_id,			
		));
		return $ViewModel;
	}	
}

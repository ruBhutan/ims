<?php
namespace Accounts\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\TableGateway\TableGateway;
use Zend\Authentication\AuthenticationService;

use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;

class MasterController extends AbstractActionController
{   
	protected $_table; 		// database table 
    protected $_author; 	// logined user id
    protected $_created; 	// current date to be used as created dated
    protected $_modified; 	// current date to be used as modified date
	protected $username;
	protected $employee_details_id;
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
	 *  Currency action
	 */
	public function currencyAction()
	{ 
	    $this->init();
		if($this->_id > 0):
			foreach ($this->getDefinedTable('Accounts\CurrencyTable')->getAll() as $currency):
				if($currency['id']== $this->_id):
					$status = '1';
					$currency_code = $currency['currency'];
				else:
					$status ='0';
				endif;
				if($currency['id'] == $this->_id || $currency['status'] == '1'):
					$data = array(
							'status'	   => $status,
							'author'   => $this->_author,
							'modified' => $this->_modified
					);
					$this->getDefaultTable('fa_currency')->update($data, array('id'=>$currency['id']));
				endif;
			endforeach;			
			$this->flashMessenger()->addMessage("success^ Currency ".$currency_code." is Selected ");
		endif;
		return new ViewModel(array(
				'title' => 'currency',
				'id' => $this->_id,
				'currencyObj' => $this->getDefinedTable('Accounts\CurrencyTable'),
		));
	}
	/**
	 * addcurrency action
	 */
	public function addcurrencyAction()
	{	
	    $this->init();
		if($this->getRequest()->isPost()){
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
			$result = $this->getDefinedTable('Accounts\CurrencyTable')->save($data);
			if($result > 0):
				$this->flashMessenger()->addMessage("success^ New currency successfully added");
			else:
				$this->flashMessenger()->addMessage("Failed^ Failed to add new currency");
			endif;
			return $this->redirect()->toRoute('master', array('action' => 'currency'));
		}
		return new ViewModel(array(
		));
	}
	/**
	 *  Edit Currency action
	 */
	public function editcurrencyAction()
	{
		$this->init();
		if($this->getRequest()->isPost())
		{
			$form=$this->getRequest()->getPost();
			$data=array(
					'id' => $this->_id,
					'currency' => $form['currency'],
					'country' => $form['country'],
					'code' => $form['code'],
					'fraction' => $form['fraction'],
					'status' => $form['status'],
					'author' => $this->employee_details_id,
					'modified' => $this->_modified,
			);
			$result = $this->getDefinedTable('Accounts\CurrencyTable')->save($data);
			if($result > 0):
				$this->flashMessenger()->addMessage("success^ Currency successfully updated");
			else:
				$this->flashMessenger()->addMessage("Failed^ Failed to update Currency");
			endif;
			return $this->redirect()->toRoute('master', array('action' => 'currency'));
		}
		
		return new ViewModel(array(
				'title' => 'editcurrency',
				'currency' => $this->getDefinedTable('Accounts\CurrencyTable')->get($this->_id),
		));
	}	
	/**
	 * Bank Ref type Action
	 */
	public function bankreftypeAction()
	{	
		$this->init();
		return new ViewModel(array(
				'title' => 'Bank Ref Type',
				'bankref' => $this->getDefinedTable('Accounts\BankreftypeTable')->getAll(),
		));
	}
	/**
	 * Add bankreftype Action
	 */
	public function addbankreftypeAction()
	{	
	    $this->init();
		if($this->getRequest()->isPost()){
			$form = $this->getRequest()->getPost();
			$data = array(
				'bank_ref_type' => $form['bankref'],
				'author' => $this->employee_details_id,
				'created' => $this->_created,
				'modified' => $this->_modified,
			);
			$result = $this->getDefinedTable('Accounts\BankreftypeTable')->save($data);
			if($result > 0):
			$this->flashMessenger()->addMessage("success^ New Bank reference type successfully added");
			else:
			$this->flashMessenger()->addMessage("Failed^ Failed to add new bank reference type");
			endif;
			return $this->redirect()->toRoute('master', array('action'=>'bankreftype'));
		}
		return new ViewModel(array(
				'bankref' => $this->getDefinedTable('Accounts\BankreftypeTable')->getAll(),
		));
	}
	/**
	 * Edit bank ref type Action
	 */
	public function editbankreftypeAction()
	{
	    $this->init();
		if($this->getRequest()->isPost()){
			$form = $this->getRequest()->getPost();
			$data = array(
					'id' => $this->_id,
					'bank_ref_type' => $form['bank_ref_type'],
					'author' => $this->employee_details_id,
					'modified' => $this->_modified,
			);
			$result=$this->getDefinedTable('Accounts\BankreftypeTable')->save($data);
			if($result > 0):
			$this->flashmessenger()->addMessage("success^ Bank reference type successfully updated ");
			else:
			$this->flashmessenger()->addMessage("error^ Failed to update bank reference type");
			endif;
			return $this->redirect()->toRoute('master', array('action'=>'bankreftype'));
		}
		return new ViewModel(array(
				'bank_ref_type' => $this->getDefinedTable('Accounts\BankreftypeTable')->get($this->_id),
		));
	}
	
	/**
	 *  Payhead action
	 */
	public function payheadAction()
	{
		$this->init();
		return new ViewModel(array(
				'title' => 'Payhead',
				'rowset' => $this->getDefinedTable('Accounts\PayheadTable')->getAll(),
				'payheadObj' => $this->getDefinedTable('Accounts\PayheadTable'),
		));
	
	}	
	
	/**
	 * addpayhead action
	 */
	public function addpayheadAction()
	{
	    $this->init();
		if($this->getRequest()->isPost()){
			$form = $this->getRequest()->getPost();
			$data = array(
				'pay_head' => $form['pay_head'],
				'deduction' => $form['deduction'],
				'code' => $form['code'],
				'type' => $form['type'],
				'dlwp' => ($form['dlwp']== Null)?0:$form['dlwp'],
				'roundup' => ($form['roundup']== Null)?0:$form['roundup'],
				'against' => ($form['against']== Null)?0:$form['against'],
				'percentage' => ($form['percentage']== Null)?0:$form['percentage'],
				'author' => $this->employee_details_id,
				'created' => $this->_created,
				'modified' => $this->_modified,
			);
			$result = $this->getDefinedTable('Accounts\PayheadTable')->save($data);
			if($result > 0):
				$sub_headdata = array(
					'head' => $form['head'],
					'code' => $form['code'],
					'name' => $form['pay_head'],
					'author' => $this->employee_details_id,
					'created' => $this->_created,
					'modified' => $this->_modified,
				);
				$R_sub_head = $this->getDefinedTable("Accounts\SubheadTable")->save($sub_headdata);
				if($R_sub_head > 0):
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
					$result1 = $this->getDefinedTable("Accounts\MasterDetailsTable")->save($master_ddata);
					if($result1 > 0):
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
			return $this->redirect()->toRoute('master',array('action'=>'payhead'));
		}
		return new ViewModel(array(
			'title'	=> 'Add Payhead',
			'payheads' => $this->getDefinedTable("Accounts\PayheadTable")->getAll(),
			'heads' => $this->getDefinedTable('Accounts\HeadTable')->getAll(),
		));
	}
	
	/**
	 * editpayheadAction
	 **/
	public function editpayheadAction()
	{
		$this->init();
		if($this->getRequest()->isPost())
		{
			$form=$this->getRequest()->getPost();
			$data=array(
				'id' => $this->_id,
				'pay_head' => $form['pay_head'],
				'deduction' => $form['deduction'],
				'code' => $form['code'],
				'type' => $form['type'],
				'dlwp' => ($form['dlwp']==NULL)?0:$form['dlwp'],
				'roundup' => ($form['roundup']==NULL)?0:$form['roundup'],
				'against' => ($form['against']==NULL)?0:$form['against'],
				'percentage' => ($form['percentage']== Null)?0:$form['percentage'],
				'author' => $this->employee_details_id,
				'modified' => $this->_modified,
			);
			$payhead_result = $this->getDefinedTable('Accounts\PayheadTable')->save($data);
			if($payhead_result > 0):
				if($form['change_paystructure']=='1'):
					foreach($this->getDefinedTable('Accounts\PaystructureTable')->get(array('sd.pay_head'=>$this->_id)) as $row):
						$employee_details = $row['employee_details'];
						if($form['against'] == '-1'):
							$base_amount = $this->getDefinedTable('Accounts\TempPayrollTable')->getColumn(array('employee_details'=>$employee_details),'gross');
						elseif($form['against'] == '-2'):
							$Gross_amount = $this->getDefinedTable('Accounts\TempPayrollTable')->getColumn(array('employee_details'=>$employee_details),'gross');
							$PFDed = $this->getDefinedTable('Accounts\PaystructureTable')->getColumn(array('employee_details'=>$employee_details, 'pay_head'=>7),'amount');
							$GISDed = $this->getDefinedTable('Accounts\PaystructureTable')->getColumn(array('employee_details'=>$employee_details, 'pay_head'=>6),'amount');
							$base_amount = $Gross_amount - $PFDed - $GISDed;
						else:
							$base_amount = $this->getDefinedTable('Accounts\PaystructureTable')->getColumn(array('employee_details'=>$employee_details, 'pay_head'=>$form['against']),'amount');
						endif;
						if($form['type'] == 2 ):				
							$amount = ($base_amount*$form['percentage'])/100;
							if($form['roundup']==1):
								$amount =round($amount);
							endif;
							$ps_data = array(
								'id' => $row['id'],
								'percent' => ($form['percentage']== Null)?0:$form['percentage'],
								'dlwp' => $form['dlwp'],
								'amount' => $amount,
								'author' => $this->employee_details_id,
								'modified' => $this->_modified,
							);
							$result = $this->getDefinedTable('Accounts\PaystructureTable')->save($ps_data);
						elseif($form['type'] == 3):
							$rate=0;  $base=0;  $value=0;  $min=0;
							foreach($this->getDefinedTable('Accounts\PayslabTable')->get(array('pay_head' => $this->_id)) as $payslab):
								if($base_amount>=$payslab['from_range'] && $base_amount<=$payslab['to_range']):
									break;
								endif;
							endforeach;
							if($payslab['formula'] == 1):
								$rate = $payslab['rate'];
								$base = $payslab['base'];
								$min = $payslab['from_range'];
								if($base_amount > 158701):
									$amount = ((($base_amount - 83338)/100)*$rate)+$base;
								else:
									$amount = (intval(($base_amount - $min)/100)*$rate)+$base;
								endif;
							else:
								$amount=$payslab['value'];
							endif;
							if($form['roundup']==1):
								$amount =round($amount);
							endif;
							$ps_data = array(
								'id' => $row['id'],
								'percent' => ($form['percentage']== Null)?0:$form['percentage'],
								'dlwp' => $form['dlwp'],
								'amount' => $amount,
								'author' => $this->employee_details_id,
								'modified' => $this->_modified,
							);
							$result = $this->getDefinedTable('Accounts\PaystructureTable')->save($ps_data);
						endif;
					endforeach;
					foreach($this->getDefinedTable('Accounts\PaystructureTable')->get(array('sd.pay_head'=>$this->_id)) as $row):
						$this->calculatePayheadAmount($row);
					endforeach;
				endif;
				$sub_headdata = array(
					'id'   => $form['sub_head_id'],
					'head' => $form['head'],
					'code' => $form['code'],
					'name' => $form['pay_head'],
					'author' => $this->employee_details_id,
				    'modified' => $this->_modified,
				);
				$R_sub_head = $this->getDefinedTable("Accounts\SubheadTable")->save($sub_headdata);
				if($R_sub_head > 0):
					$masterDtls_data = array(
						'id'   => $form['masterDtls_id'],
						'sub_head' => $form['sub_head_id'],
						'type' => 8,
						'ref_id' =>$payhead_result,
						'code' => $form['code'],
						'name' => $form['pay_head'],
						'author' => $this->employee_details_id,
						'modified' => $this->_modified,
					);
					$result1 = $this->getDefinedTable("Accounts\MasterDetailsTable")->save($masterDtls_data);
					if($result1 > 0):
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
			return $this->redirect()->toRoute('master',array('action'=>'payhead'));
		}
		return new ViewModel(array(
			'title'	=> 'Edit Payhead',
			'headobj' => $this->getDefinedTable('Accounts\HeadTable'),
			'payhead' => $this->getDefinedTable('Accounts\PayheadTable')->get($this->_id),
			'payheads' => $this->getDefinedTable('Accounts\PayheadTable')->getAll(),
			'masterDtls' => $this->getDefinedTable('Accounts\MasterDetailsTable')->get(array('ref_id'=>$this->_id,'md.type'=>'8')),
		));
	}
	
	/**
	 *  Payhead action
	 */
	public function paygroupAction()
	{	
	    $this->init();
		return new ViewModel(array(
				'title' => 'Pay Group',				
				'paygroups' => $this->getDefinedTable('Accounts\PaygroupTable')->getAll(),
		));
	
	}	
	
	/**
	 * addpayhead action
	 */
	public function addpaygroupAction()
	{
	    $this->init();
		if($this->getRequest()->isPost()){
			$form = $this->getRequest()->getPost();
			$data = array(
				'group' => $form['group'],
				'pay_head' => $form['pay_head'],
				'value' => $form['value'],
				'author' => $this->employee_details_id,
				'created' => $this->_created,
				'modified' => $this->_modified,
			);
			$result = $this->getDefinedTable('Accounts\PaygroupTable')->save($data);
			if($result > 0):				
				$this->flashMessenger()->addMessage("success^ New Pay group successfully added");
			else:
				$this->flashMessenger()->addMessage("error^ Failed to add new Pay group");
			endif;
			return $this->redirect()->toRoute('master',array('action'=>'paygroup'));
		}
		return new ViewModel(array(
				'title'	=> 'Add Pay Group',				
				'payheads' => $this->getDefinedTable('Accounts\PayheadTable')->getAll(),
		));
	}
	
	/**
	 * editpaygroupAction
	 **/
	public function editpaygroupAction()
	{
		$this->init();
		if($this->getRequest()->isPost())
		{
			$form=$this->getRequest()->getPost();
			$data=array(
					'id' => $this->_id,
					'group' => $form['group'],
					'pay_head' => $form['pay_head'],
					'value' => $form['value'],
					'author' => $this->employee_details_id,
				    'modified' => $this->_modified,
			);
			$result = $this->getDefinedTable('Accounts\PaygroupTable')->save($data);
			if($result > 0):
				$this->flashMessenger()->addMessage("success^ Pay group successfully updated");
			else:
				$this->flashMessenger()->addMessage("error^ Failed to update Pay group");
			endif;
			return $this->redirect()->toRoute('master',array('action'=>'paygroup'));
		}
		return new ViewModel(array(
				'title'	=> 'Edit Payhead',
				'paygroup' => $this->getDefinedTable('Accounts\PaygroupTable')->get($this->_id),
				'payheads' => $this->getDefinedTable('Accounts\PayheadTable')->getAll(),
		));
	}
	
	/**
	 *  Payslab action
	 */
	public function payslabAction()
	{	
	    $this->init();
		return new ViewModel(array(
			'title' => 'Pay Slab',
			'rowset' => $this->getDefinedTable('Accounts\PaySlabTable')->getAll(),
			'payhead' => $this->getDefinedTable('Accounts\PayheadTable')->getAll(),
			'payheadObj' => $this->getDefinedTable('Accounts\PayheadTable'),
		));
	}
	public function addpayslabAction()
	{	
	    $this->init();
		if($this->getRequest()->isPost()){
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
			$result = $this->getDefinedTable('Accounts\PaySlabTable')->save($data);
			if($result > 0):
			$this->flashMessenger()->addMessage("success^ New Pay slab successfully added");
			else:
			$this->flashMessenger()->addMessage("error^ Failed to add new Pay slab");
			endif;
			return $this->redirect()->toRoute('master',array('action'=>'payslab'));
		}
		return new ViewModel(array(
				'payslab' => $this->getDefinedTable("Accounts\PaySlabTable")->getAll(),
				'payhead' => $this->getDefinedTable('Accounts\PayheadTable')->getAll(),
		));
	}
	public function editpayslabAction()
	{	
	    $this->init();
		if($this->getRequest()->isPost()){
			$form = $this->getRequest();
			$data = array(
				'id'=> $this->_id,
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
			$result = $this->getDefinedTable('Accounts\PaySlabTable')->save($data);
			if($result > 0):
			$this->flashMessenger()->addMessage("success^ Pay slab successfully updated");
			else:
			$this->flashMessenger()->addMessage("error^ Failed to update Pay slab");
			endif;
			return $this->redirect()->toRoute('master',array('action'=>'payslab'));
		}
		return new ViewModel(array(
				'id' => $this->_id,
				'payslab' => $this->getDefinedTable("Accounts\PaySlabTable")->get($this->_id),
				'payhead' => $this->getDefinedTable('Accounts\PayheadTable')->getAll(),
				'payheadObj' => $this->getDefinedTable('Accounts\PayheadTable'),
		));
	}
	
	/*
	 * function to calculate payhead amount on change of payheads
	 */
	public function calculatePayheadAmount($paystructure){
		$payhead_id =$paystructure['pay_head_id'];	
		$employee = $paystructure['employee'];
		$payhead_type = $this->getDefinedTable('Accounts\PayheadTable')->getColumn($payhead_id, 'payhead_type');
		$deduction = $this->getDefinedTable('Accounts\PayheadtypeTable')->getColumn($payhead_type, 'deduction');
		if($deduction == 1):
			$affected_ps = $this->getDefinedTable('Accounts\PaystructureTable')->get(array('sd.employee'=>$employee, 'ph.against'=> $payhead_id));
		else:
			$affected_ps = $this->getDefinedTable('Accounts\PaystructureTable')->get(array('sd.employee'=>$employee, 'ph.against'=> array($payhead_id,'-1','-2')));
		endif;
		foreach($affected_ps as $aff_ps):
			if($aff_ps['against'] == '-1'):
				$base_amount = $this->getDefinedTable('Accounts\TempPayrollTable')->getColumn(array('employee'=>$employee),'gross');
			elseif($form['against'] == '-2'):
				$Gross_amount = $this->getDefinedTable('Accounts\TempPayrollTable')->getColumn(array('employee'=>$employee),'gross');
				$PFDed = $this->getDefinedTable('Accounts\PaystructureTable')->getColumn(array('employee'=>$employee, 'pay_head'=>7),'amount');
				$GISDed = $this->getDefinedTable('Accounts\PaystructureTable')->getColumn(array('employee'=>$employee, 'pay_head'=>6),'amount');
				$base_amount = $Gross_amount - $PFDed - $GISDed;
			else:
				$base_amount = $this->getDefinedTable('Hr\PaystructureTable')->getColumn(array('employee'=>$employee, 'pay_head'=>$aff_ps['against']),'amount');
			endif;
			if($aff_ps['type'] == 2 ):				
				$amount = ($base_amount*$aff_ps['percent'])/100;
				if($aff_ps['roundup'] == 1):
					$amount = round($amount);
				endif;
				$data = array(
					'id' => $aff_ps['id'],
					'amount' => $amount,
					'author' =>$this->_author,
					'modified' =>$this->_modified,
				);
				$data = $this->_safedataObj->rteSafe($data);
				$result = $this->getDefinedTable('Hr\PaystructureTable')->save($data);
			elseif($aff_ps['type'] == 3):
				$rate=0;  $base=0;  $value=0;  $min=0;
				foreach($this->getDefinedTable('Accounts\PayslabTable')->get(array('pay_head' => $aff_ps['pay_head_id'])) as $payslab):
					if($base_amount>=$payslab['from_range'] && $base_amount<=$payslab['to_range']):
						break;
					endif;
				endforeach;
				if($payslab['formula'] == 1):
					$rate = $payslab['rate'];
					$base = $payslab['base'];
					$min = $payslab['from_range'];
					if($base_amount > 158701):
						$amount = ((($base_amount - 83338)/100)*$rate)+$base;
					else:
						$amount = (intval(($base_amount - $min)/100)*$rate)+$base;
					endif;
				else:
					$amount=$payslab['value'];
				endif;
				if($aff_ps['roundup'] == 1):
					$amount = round($amount);
				endif;
				$data = array(
					'id' => $aff_ps['id'],
					'amount' => $amount,
					'author' =>$this->_author,
					'modified' =>$this->_modified,
				);
				$data = $this->_safedataObj->rteSafe($data);
				$result = $this->getDefinedTable('Hr\PaystructureTable')->save($data);
			endif;
		endforeach;
		
		//making changes to temp payroll
		foreach($this->getDefinedTable('Hr\TempPayrollTable')->get(array('pr.employee' => $employee)) as $temp_payroll):				
			$total_earning = 0;		
			$total_deduction = 0;
			foreach($this->getDefinedTable('Hr\PaystructureTable')->get(array('sd.employee' => $employee, 'pht.deduction'=>'1')) as $paydetails):
				if($paydetails['dlwp']==1):
					$amount = $paydetails['amount'] - ($paydetails['amount']/$temp_payroll['working_days']) * $temp_payroll['leave_without_pay'];
				else:
					$amount = $paydetails['amount'];
				endif;
				if($paydetails['roundup']==1):
					$amount =round($amount);
				endif;
				$total_deduction = $total_deduction + $amount;
			endforeach;	
			foreach($this->getDefinedTable('Hr\PaystructureTable')->get(array('sd.employee' => $employee, 'pht.deduction'=>'0')) as $paydetails):
				if($paydetails['dlwp']==1):
					$amount = $paydetails['amount'] - ($paydetails['amount']/$temp_payroll['working_days']) * $temp_payroll['leave_without_pay'];
				else:
					$amount = $paydetails['amount'];
				endif;
				if($paydetails['roundup']==1):
					$amount =round($amount);
				endif;
				$total_earning = $total_earning + $amount;
			endforeach;				
			$leave_encashment = $temp_payroll['leave_encashment'];
			$bonus = $temp_payroll['bonus'];
			$net_pay = $total_earning + $leave_encashment + $bonus - $total_deduction;
			$data1 = array(
					'id'	=> $temp_payroll['id'],
					'gross' => $total_earning,
					'total_deduction' => $total_deduction,
					'net_pay' => $net_pay,
					'author' =>$this->_author,
					'modified' =>$this->_modified,
			);			
			$data1 = $this->_safedataObj->rteSafe($data1);
			$result1 = $this->getDefinedTable('Hr\TempPayrollTable')->save($data1);
		endforeach;
		return $result1;
	}
}

<?php
namespace Accounts\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\TableGateway\TableGateway;
use Zend\Authentication\AuthenticationService;
use DOMPDFModule\View\Model\PdfModel;

use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;

class TransactionController extends AbstractActionController
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
		$this->role = $user_session->role;
		
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
		
		if($this->getRequest()->isPost())
		{
			$form = $this->getRequest()->getPost();
			$year = $form['year'];
			$month = $form['month'];
			if(strlen($month)==1){
				$month = '0'.$month;
			}
			$userorg = $form['organisation'];
		}else{
			$month = '';
			$year = '';
			$month = ($month == '')?date('m'):$month;
			$year = ($year == '')? date('Y'):$year;
			$userorg = $this->organisation_id;
		}
		$month = ($month == '')?date('m'):$month;
		$year = ($year == '')? date('Y'):$year;
		
		$minYear = $this->getDefinedTable("Accounts\TransactionTable")->getMin('voucher_date');
		$minYear = ($minYear == "")?date('Y-m-d'):$minYear;
		$minYear = date('Y', strtotime($minYear));
		$data = array(
				'year' => $year,
				'month' => $month,
				'minYear' => $minYear,
				'userorg' => $userorg,
		);
		$results = $this->getDefinedTable('Accounts\TransactionTable')->getLocationDateWise('voucher_date',$userorg,$year,$month,array('status'=>array(3)));
		return new ViewModel(array(
			'title'  => 'Transaction',
		    'results' => $results,
			'data'    => $data,
			'orgObj'    => $this->getDefinedTable('Hr\OrganisationTable'),
			'transactiondetailsObj'    => $this->getDefinedTable('Accounts\TransactiondetailTable'),
   		));
	} 
	/**
	 *  add transaction action
	 */
	public function addtransactionAction()
	{
		$this->init();
		if($this->getRequest()->isPost()){
			$form = $this->getRequest()->getpost();
			//generate voucher no
			$org = $this->getDefinedTable("Hr\OrganisationTable")->getcolumn($this->organisation_id, 'organisation_code');
			$prefix = $this->getDefinedTable("Accounts\JournalTable")->getcolumn($form['voucher_type'],'prefix');
			$date = date('ym',strtotime($form['voucher_date']));
			$tmp_VCNo = $org.$prefix.$date;
			$results = $this->getDefinedTable("Accounts\TransactionTable")->getSerial($tmp_VCNo);
			
			$trns_no_list = array();
			foreach($results as $result):
				array_push($trns_no_list, substr($result['voucher_no'], 8));
			endforeach;
			$next_serial = max($trns_no_list) + 1;
				
			switch(strlen($next_serial)){
				case 1: $next_vn_serial = "000".$next_serial; break;
				case 2: $next_vn_serial = "00".$next_serial;  break;
				case 3: $next_vn_serial = "0".$next_serial;   break;
				default: $next_vn_serial = $next_serial;       break;
			}	
			$voucher_no = $tmp_VCNo.$next_vn_serial;
			
			$data1 = array(
				'voucher_date' => $form['voucher_date'],
				'voucher_type' => $form['voucher_type'],
				'voucher_no' => $voucher_no,
				'cheque_id' => $form['cheque_no'],
				'voucher_amount' => str_replace( ",", "",$form['voucher_amount']),
				'organisation_id' =>$this->organisation_id,
				'remark' => $form['remark'],
				'reject_remark' => '',
				'status' => 1, // status initiated 
				'author' =>$this->employee_details_id,
				'created' =>$this->_created,
				'modified' =>$this->_modified,
			);
			$result = $this->getDefinedTable('Accounts\TransactionTable')->save($data1);
			if($result > 0){
				$result_explodes = explode('|',implode('|',$form['sub_head']));
				$masterDtls = array();
				foreach($result_explodes as $key => $row):
				if($key % 3 == 0){array_push($masterDtls,$row);}
			    endforeach;
				$debit= $form['debit']; 
				$credit= $form['credit'];
				for($i=0; $i < sizeof($masterDtls); $i++):
					if(isset($masterDtls[$i]) && is_numeric($masterDtls[$i])):
						$tdetailsdata = array(
							'transaction'     => $result,
							'organisation_id' => $this->organisation_id,
							'head'            => $this->getDefinedTable('Accounts\SubheadTable')->getColumn($this->getDefinedTable('Accounts\MasterDetailsTable')->getColumn($masterDtls[$i], $column='sub_head'), $column='head'),
							'sub_head'        => $this->getDefinedTable('Accounts\MasterDetailsTable')->getColumn($masterDtls[$i], $column='sub_head'),
							'master_details'  => $masterDtls[$i],
							//'bank_ref_type'   => '',
							'debit'           => (isset($debit[$i]))? $debit[$i]:'0.000',
							'credit'          => (isset($credit[$i]))? $credit[$i]:'0.000',
							'ref_no'          => '', 
							'type'            => '1',//user inputted  data
							'author' =>$this->employee_details_id,
							'created' =>$this->_created,
							'modified' =>$this->_modified,
						);
						//print_r($tdetailsdata); exit;
						$result1 = $this->getDefinedTable('Accounts\TransactiondetailTable')->save($tdetailsdata);
						if($result1 <= 0):
							break;
						endif;
					endif;
				endfor;
				if($result1 > 0):
					$this->flashMessenger()->addMessage("success^ New Transaction successfully added | ".$voucher_no);
					return $this->redirect()->toRoute('transaction', array('action' =>'viewtransaction', 'id' => $result));
				else:
					$this->flashMessenger()->addMessage("Failed^ Failed to add new Transaction");		
					return $this->redirect()->toRoute('transaction');
				endif;				
			}
			else
			{
				$this->flashMessenger()->addMessage("Failed^ Failed to add new Transaction");		
				return $this->redirect()->toRoute('transaction');
			}
		}
		$date = date('y-m-d');
		$bank_subledgers = $this->getDefinedTable("Accounts\MasterDetailsTable")->getBSubledger(array('type'=>array('2')),$this->organisation_id);
		foreach($bank_subledgers as $bank_subledger);
		$bank_balance = $this->getDefinedTable("Accounts\TransactiondetailTable")->getBankBalance($date,$bank_subledger['subhead_id'],$this->organisation_id);
		$cash_subledgers = $this->getDefinedTable("Accounts\MasterDetailsTable")->getCSubledger(array('type'=>array('3')),$this->organisation_id);
		foreach($cash_subledgers as $cash_subledger);
		$cash_balance = $this->getDefinedTable("Accounts\TransactiondetailTable")->getCashBalance($date,$cash_subledger['subhead_id'],$this->organisation_id);
		return new ViewModel(array(
			'title'  => 'Add transaction',
			'orgObj' => $this->getDefinedTable('Hr\OrganisationTable'),
			'journals' => $this->getDefinedTable('Accounts\JournalTable')->getAll(),
			'subheadObj' => $this->getDefinedTable('Accounts\SubheadTable'),
			'masterDtls' => $this->getDefinedTable('Accounts\MasterDetailsTable')->getDistinctESP($this->organisation_id),
			'masterDtlObj' => $this->getDefinedTable('Accounts\MasterDetailsTable'),
			'heads' => $this->getDefinedTable('Accounts\HeadTable')->getAll(),
			'subheads' => $this->getDefinedTable('Accounts\SubHeadTable')->getAll(),
			'user_org' =>$this->organisation_id,
			'chObj' => $this->getDefinedTable("Accounts\ChequeTable"),
			'chdtlsObj' => $this->getDefinedTable("Accounts\ChequeDetailsTable"),
			'tdsObj' => $this->getDefinedTable("Accounts\TDSTable"),
			'bank_balance' => $bank_balance,
			'cash_balance' => $cash_balance,
		));
	}
	
	/**
	 *  edit transaction action
	 */
	public function edittransactionAction()
	{
		$this->init();
		$status = $this->getDefinedTable("Accounts\TransactionTable")->getColumn($this->_id,'status');
		if($status >= 3):
			$this->redirect()->toRoute('transaction');
		endif;
		if($this->getRequest()->isPost()){
			$form = $this->getRequest()->getpost();
			$data1 = array(
					'id' => $this->_id,
					'voucher_date' => $form['voucher_date'],
					'voucher_type' => $form['voucher_type'],
					'voucher_amount' => str_replace( ",", "",$form['voucher_amount']),
					'cheque_id' => $form['cheque_no'],
					'organisation_id' =>$this->_userorg,
        			'remark' => $form['remark'],
					'reject_remark' => '',
        			'status' => 1, // status pending 
					'modified' =>$this->_modified,
			);			
			$data1 = $this->_safedataObj->rteSafe($data1);
			$this->_connection->beginTransaction(); //***Transaction begins here***//
			$result = $this->getDefinedTable('Accounts\TransactionTable')->save($data1);
			if($result > 0){
				$tdetails_id = $form['id'];
				$result_explodes = explode('|',implode('|',$form['sub_head']));
				$masterDtls = array();
				foreach($result_explodes as $key => $row):
				if($key % 3 == 0){array_push($masterDtls,$row);}
			    endforeach;
				$debit= $form['debit'];
				$credit= $form['credit'];
				$delete_rows = $this->getDefinedTable('Accounts\TransactiondetailTable')->getNotInDtl($tdetails_id, array('transaction' => $result));
				for($i=0; $i < sizeof($masterDtls); $i++):
					if(isset($masterDtls[$i]) && is_numeric($masterDtls[$i])):
						if($tdetails_id[$i]>0):
							$tdetailsdata = array(
								'id' => $tdetails_id[$i],
								'transaction' => $result,
								'organisation_id' =>$this->_userorg,
								'head' => $this->getDefinedTable('Accounts\SubheadTable')->getColumn($this->getDefinedTable('Accounts\MasterDetailsTable')->getColumn($masterDtls[$i], $column='sub_head'), $column='head'),
							    'sub_head'     => $this->getDefinedTable('Accounts\MasterDetailsTable')->getColumn($masterDtls[$i], $column='sub_head'),
								'master_details'  => $masterDtls[$i],
								'bank_ref_type' => '',
								'debit' => (isset($debit[$i]))? $debit[$i]:'0.00',
								'credit' => (isset($credit[$i]))? $credit[$i]:'0.00',
								'ref_no'=> '', 
								'type' => '1',//user inputted  data
								'modified' =>$this->_modified,
							);
						else:
							$tdetailsdata = array(
								'transaction' => $result,
								'organisation_id' => $organisation[$i],
								'head' => $this->getDefinedTable('Accounts\SubheadTable')->getColumn($this->getDefinedTable('Accounts\MasterDetailsTable')->getColumn($masterDtls[$i], $column='sub_head'), $column='head'),
							    'sub_head'     => $this->getDefinedTable('Accounts\MasterDetailsTable')->getColumn($masterDtls[$i], $column='sub_head'),
								'master_details'  => $masterDtls[$i],
								'bank_ref_type' => '',
								'cheque_id' => '',
								'debit' => (isset($debit[$i]))? $debit[$i]:'0.00',
								'credit' => (isset($credit[$i]))? $credit[$i]:'0.00',
								'ref_no'=> '', 
								'type' => '1',//user inputted  data
								'author' => $this->_author,
								'created' => $this->_created,//user inputted  data
								'modified' =>$this->_modified,
							);
						endif;
						$tdetailsdata = $this->_safedataObj->rteSafe($tdetailsdata);
						$result1 = $this->getDefinedTable('Accounts\TransactiondetailTable')->save($tdetailsdata);				
					endif;
				endfor;
				//deleting deleted table rows form database table
				foreach($delete_rows as $delete_row):
					$this->getDefinedTable('Accounts\TransactiondetailTable')->remove($delete_row['id']);
				endforeach;
				$this->_connection->commit(); // commit transaction on success
				$this->flashMessenger()->addMessage("success^ Transaction successfully updated | ".$voucher_no);
				return $this->redirect()->toRoute('transaction', array('action' =>'viewtransaction', 'id' => $this->_id));
			}
			else
			{
				$this->_connection->rollback(); // rollback transaction over failure
				$this->flashMessenger()->addMessage("error^ Failed to modify  Transaction");	
				return $this->redirect()->toRoute('transaction', array('action' =>'viewtransaction', 'id' => $this->_id));
			}
		}
		$date = date('y-m-d');
		$bank_subledgers = $this->getDefinedTable("Accounts\MasterDetailsTable")->getBSubledger(array('type'=>array('2')),$this->_user->organisation_id);
		foreach($bank_subledgers as $bank_subledger);
		$bank_balance = $this->getDefinedTable("Accounts\TransactiondetailTable")->getBankBalance($date,$bank_subledger['subhead_id'],$this->_user->organisation_id);
		$cash_subledgers = $this->getDefinedTable("Accounts\MasterDetailsTable")->getCSubledger(array('type'=>array('3')),$this->_user->organisation_id);
		foreach($cash_subledgers as $cash_subledger);
		$cash_balance = $this->getDefinedTable("Accounts\TransactiondetailTable")->getCashBalance($date,$cash_subledger['subhead_id'],$this->_user->organisation_id);
		return new ViewModel(array(
			'title'  => 'Update transaction',
			'transactions' => $this->getDefinedTable('Accounts\TransactionTable')->get($this->_id),
			'tdetails' => $this->getDefinedTable('Accounts\TransactiondetailTable')->get(array('transaction'=>$this->_id)),
			'journals' => $this->getDefinedTable('Accounts\JournalTable')->getAll(),
			'subheadObj' => $this->getDefinedTable('Accounts\SubheadTable'),
			'masterDetailObj' => $this->getDefinedTable('Accounts\MasterDetailsTable'),
			'heads' => $this->getDefinedTable('Accounts\HeadTable')->getAll(),
			'tdetailsObj' => $this->getDefinedTable('Accounts\TransactiondetailTable'),
			'orgObj' => $this->getDefinedTable('Acl\OrganisationTable'),
			'userorg' => $this->_user->organisation_id,
			'chObj' => $this->getDefinedTable("Accounts\ChequeTable"),
			'chdtlsObj' => $this->getDefinedTable("Accounts\ChequeDetailsTable"),
			'tdsObj' => $this->getDefinedTable("Accounts\TDSTable"),
			'bank_balance' => $bank_balance,
			'cash_balance' => $cash_balance,
			'masterDtls' => $this->getDefinedTable('Accounts\MasterDetailsTable')->getDistinctESP($this->_user->organisation_id),
			'parties' => $this->getDefinedTable("Accounts\TransactiondetailTable")->getParty(array(4,5,6,7),array('transaction' => $this->_id)),
			'accounts_details' => $this->getDefinedTable("Accounts\MasterDetailsTable")->getBCADetails($this->_user->organisation_id,array('type'=>array('2','3')))
		));
	}
	
	/**
	 * commit action
	 **/
	public function commitAction(){
		$this->init();
		$transactions = $this->getDefinedTable('Accounts\TransactionTable')->get($this->_id);
		foreach($transactions as $row);
		$data = array(
			'id' => $this->_id,
			'status' => 3, // status committed 
			'modified' => $this->_modified,
		);	
		$result = $this->getDefinedTable("Accounts\TransactionTable")->save($data); 
        $chequedtls = $this->getDefinedTable("Accounts\ChequeDetailsTable")->get(array('cd.id'=>$row['cheque_id']));
		foreach($chequedtls as $row);
		$update_data1 = array(
			'id'            =>$row['id'],
			'status' 		=> 12,
			'author'	    => $this->_author,
			'modified'      => $this->_modified,
		);	
        $result1 = $this->getDefinedTable("Accounts\ChequeDetailsTable")->save($update_data1);		
		$voucher_no = $this->getDefinedTable("Accounts\TransactionTable")->getColumn($this->_id, 'voucher_no');
		if($result > 0 || $result1 > 0):
			$this->flashMessenger()->addMessage("success^ Transaction Commited Successfully | ".$voucher_no);
		endif;
		return $this->redirect()->toRoute("transaction", array("action"=>"viewtransaction", "id" => $this->_id));
	}
	/**
	 * commit action
	 **/
	public function pendingtransactionAction()
	{
		$this->init();
		$transactions = $this->getDefinedTable('Accounts\TransactionTable')->get($this->_id);
		foreach($transactions as $row);
		$data = array(
			'id' => $this->_id,
			'status' => 2, // status committed 
			'modified' => $this->_modified,
		);	
	    $result1 = $this->getDefinedTable("Accounts\TransactionTable")->save($data);
		if($result1):
			$notification_data = array(
				'route'         => 'transaction',
				'action'        => 'viewtransaction',
				'key' 		    => $this->_id,
				'description'   => 'Voucher to be Verified',
				'author'	    => $this->_author,
				'created'       => $this->_created,
				'modified'      => $this->_modified,   
			);
			$notificationResult = $this->getDefinedTable('Acl\NotificationTable')->save($notification_data);
			if($notificationResult > 0 ){
				$voucher_organisation = $this->getDefinedTable('Accounts\TransactionTable')->getColumn($this->_id, 'organisation_id');
				//$finance_offer = $this->getDefinedTable('Acl\UserroleTable')->get(array('subrole'=>'1'));
				//foreach($finance_offer as $row):
					$user_organisatin_id = $this->getDefinedTable('Acl\UsersTable')->getColumn($row['user'], 'organisation_id');
					if($user_organisatin_id == $voucher_organisation):
						$notify_data = array(
							'notification' => $notificationResult,
							'user'    	   => $row['user'],
							'flag'    	 => '0',
							'desc'    	 => 'Voucher to be Verified',
							'author'	 => $this->_author,
							'created'    => $this->_created,
							'modified'   => $this->_modified,  
						);
						$notifyResult = $this->getDefinedTable('Acl\NotifyTable')->save($notify_data);
					endif;
				//endforeach;
			}
		$voucher_no = $this->getDefinedTable("Accounts\TransactionTable")->getColumn($this->_id, 'voucher_no');
		$this->flashMessenger()->addMessage("success^ Transaction Commited Successfully | ".$voucher_no);
		else:
	    $this->_connection->rollback(); // rollback transaction over failure
		$this->flashMessenger()->addMessage("success^ Transaction Commited Successfully | ".$voucher_no);
		endif;
		return $this->redirect()->toRoute("transaction", array("action"=>"viewtransaction", "id" => $this->_id));
	}
	/**
	 * get journal view
	 * 
	 **/
	public function viewtransactionAction(){
		$this->init();
		return new ViewModel(array(
			'transactionrow' => $this->getDefinedTable("Accounts\TransactionTable")->get($this->_id),
			'transactiondetails' => $this->getDefinedTable("Accounts\TransactiondetailTable")->get(array('transaction' => $this->_id)),
			'parties' => $this->getDefinedTable("Accounts\TransactiondetailTable")->getParty(array(4,5,6,7),array('transaction' => $this->_id)),
            'bank_ref_typeObj' => $this->getDefinedTable('Accounts\BankreftypeTable'),
            'chequeDtlsObj' => $this->getDefinedTable('Accounts\ChequeDetailsTable'),
            'emplObj' => $this->getDefinedTable('Hr\EmployeeDetailsTable'),
			'positiontObj' => $this->getDefinedTable('Hr\PositiontitleTable'),
		    'employeepfs' => $this->getDefinedTable('Hr\JobProfileTable')->get(array('employee_details'=>$this->employee_details_id)),			
		));
	}
    /**
	 * money receipt print
	 **/
	public function receiptprintAction(){
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
	public function getmasterdtlAction()
	{
		$this->init();
		$arr = explode('-',$this->_id);
		
		$ref_ID  = $arr[0];
		$type_ID = $arr[1];
		if($ref_ID == 1 && $type_ID == ""):
			$accounts_details = $this->getDefinedTable("Accounts\MasterDetailsTable")->getBCADetails($this->organisation_id,array('type'=>array('2','3'))); 
			$subhead_details = $this->getDefinedTable("Accounts\MasterDetailsTable")->get(array('type'=>9));
			$mastDID = array();
			foreach($accounts_details as $accounts_detail):
				array_push($mastDID,$accounts_detail['id']);
			endforeach;
			foreach($subhead_details as $subhead_detail):
				array_push($mastDID,$subhead_detail['id']);
			endforeach;
			$masterDtls = $this->getDefinedTable("Accounts\MasterDetailsTable")->get(array('md.id'=>$mastDID));
		elseif($ref_ID == '16' && $type_ID =='8'):
		    $accounts_details = $this->getDefinedTable("Accounts\MasterDetailsTable")->getBCADetails($this->organisation_id,array('type'=>array('2','3'))); 
			$subhead_details = $this->getDefinedTable("Accounts\MasterDetailsTable")->getASSubLedger(array('sub_head'=>'28','empl.organisation_id'=>$this->organisation_id));
			$mastDID = array();
			foreach($accounts_details as $accounts_detail):
				array_push($mastDID,$accounts_detail['id']);
			endforeach;
			foreach($subhead_details as $subhead_detail):
				array_push($mastDID,$subhead_detail['id']);
			endforeach;
			$masterDtls = $this->getDefinedTable("Accounts\MasterDetailsTable")->get(array('md.id'=>$mastDID));
		else:
			$accounts_details = $this->getDefinedTable("Accounts\MasterDetailsTable")->getBCADetails($this->organisation_id,array('type'=>array('2','3'))); 
			$subhead_details = $this->getDefinedTable("Accounts\MasterDetailsTable")->get(array('type'=>$type_ID,'ref_id'=>$ref_ID));
			$mastDID = array();
			foreach($accounts_details as $accounts_detail):
				array_push($mastDID,$accounts_detail['id']);
			endforeach;
			foreach($subhead_details as $subhead_detail):
				array_push($mastDID,$subhead_detail['id']);
			endforeach;
			$masterDtls = $this->getDefinedTable("Accounts\MasterDetailsTable")->get(array('md.id'=>$mastDID));
		endif;
		$viewModel = new ViewModel(array(
		    'masterDtls' => $masterDtls,
		    'type_ID' => $type_ID,
		));
		$viewModel->setTerminal(true);	
		return  $viewModel;
	}
		/**
	 *  Edit Currency action
	 */
	public function rejectAction()
	{
		
		$this->init();
		if($this->getRequest()->isPost())
		{
			$form=$this->getRequest()->getPost();
			$data=array(
					'id' => $form['id'],
					'reject_remark' => $form['remark'],
					'status' => 9,
					'author' =>$this->_author,
					'modified' =>$this->_modified,
			);
			$data = $this->_safedataObj->rteSafe($data);
			$result = $this->getDefinedTable('Accounts\TransactionTable')->save($data);
			if($result > 0):
				$this->flashMessenger()->addMessage("success^successfully rejected the Voucher");
			else:
				$this->flashMessenger()->addMessage("Failed^ Failed to reject the Voucher");
			endif;
			return $this->redirect()->toRoute('transaction', array('action' => 'viewtransaction','id'=>$id));
		}
		
		$ViewModel = new ViewModel(array(
			'title' => 'Reject',
			'transactionrow' => $this->getDefinedTable("Accounts\TransactionTable")->get($this->_id),
		));
		
		$ViewModel->setTerminal(True);
		return $ViewModel;
	}
}

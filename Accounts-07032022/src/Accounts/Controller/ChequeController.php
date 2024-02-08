<?php
namespace Accounts\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\TableGateway\TableGateway;
use Zend\Authentication\AuthenticationService;

use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;

class ChequeController extends AbstractActionController
{   
	protected $_table; 		// database table 
   
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
	 * Cheque index action
	**/
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
			$month =''; $year ='';
			$month = ($month == '')?date('m'):$month;
			$year = ($year == '')? date('Y'):$year;
			$userorg = $this->organisation_id;
		}
		$month = ($month == '')?date('m'):$month;
		$year = ($year == '')? date('Y'):$year;
		$minYear = $this->getDefinedTable("Accounts\ChequeTable")->getMin('receive_date');
		$minYear = ($minYear == "")?date('Y-m-d'):$minYear;
		$minYear = date('Y', strtotime($minYear));
		
		$data = array(
			'year' => $year,
			'month' => $month,
			'minYear' => $minYear,
			'userorg' => $userorg,
		);
		$results = $this->getDefinedTable('Accounts\ChequeTable')->getLocationDateWise('receive_date',$data['userorg'],$data['year'],$data['month']);
		return new ViewModel(array(
			'title' => "Cheque Book",
			'data'  =>$data,
			'cheques' => $results,
			'BAObj' => $this->getDefinedTable('Accounts\BankaccountTable'),
			'orgObj' => $this->getDefinedTable('Hr\OrganisationTable'),
		));
	}
	
	/**
	 * Add Cheque
	**/
	public function addchequeAction()
	{
		$this->init();
		if($this->getRequest()->isPost()):
			$request= $this->getRequest();
			$form = $request->getPost();
			$date = date('ym',strtotime($form['receive_date']));
			$tmp_Cheque_No = "CQ".$date;
			$results = $this->getDefinedTable("Accounts\ChequeTable")->getMonthlyCQ($tmp_Cheque_No);
			
			$cq_no_list = array();
			foreach($results as $result):
				array_push($cq_no_list, substr($result['cheque_code'],7));
			endforeach;
			$next_serial = max($cq_no_list) + 1;
				
			switch(strlen($next_serial)){
				case 1: $next_cq_serial = "000".$next_serial; break;
				case 2: $next_cq_serial = "00".$next_serial;  break;
				case 3: $next_cq_serial = "0".$next_serial;   break;
				default: $next_cq_serial = $next_serial;       break;
			}	
			$cheque_code = $tmp_Cheque_No.$next_cq_serial;
			$rowsets =$this->getDefinedTable('Accounts\BankaccountTable')->get(array('ba.id'=>$form['bank_account']));
			foreach($rowsets as $rowset);
			$data =array(
					'receive_date' => $form['receive_date'],
					'cheque_code' => $cheque_code,
					'bank_account' => $form['bank_account'],
					'start_cheque_no' => $form['cheque_start_no'],
					'end_cheque_no' => $form['cheque_end_no'],
					'no_of_cheque' => $form['no_of_cheque'],
					'organisation_id' =>$rowset['organisation_id'],
					'author' =>$this->employee_details_id,
					'created' =>$this->_created,
					'modified' =>$this->_modified
			);
			$result = $this->getDefinedTable('Accounts\ChequeTable')->save($data);
			if($result>0):
				$start_cheque_no= $form['cheque_start_no'];
				$end_cheque_no= $form['cheque_end_no'];
				$remarks = $form['remarks'];
				for($start_cheque_no=$start_cheque_no; $start_cheque_no <= $end_cheque_no; $start_cheque_no++):
					$cheque_no = str_pad($start_cheque_no,6,'0',STR_PAD_LEFT);
					$cheque_detail_data = array(
						'cheque_id' => $result,
						'instrument_no' => $cheque_no,
						'status' =>2,
						'author' =>$this->employee_details_id,
						'created' =>$this->_created,
						'modified' =>$this->_modified,
					);
					$this->getDefinedTable('Accounts\ChequeDetailsTable')->save($cheque_detail_data);
				endfor;
				$this->flashMessenger()->addMessage("success^ Successfully added new Cheque Code :".$cheque_code);
				return $this->redirect()->toRoute('cheque');
			else:
				$this->flashMessenger()->addMessage("Failed^ Failed to add new cheque no. ");
				return $this->redirect()->toRoute('cheque');
			endif;
		endif;
    	return new ViewModel(array(
		'title'   =>"Add Cheque Book",
		'baObj'   =>$this->getDefinedTable('Accounts\BankaccountTable'),
		'user_org'=> $this->organisation_id,
		));
	}
    /**
	 * edit Cheque
	**/
	public function editchequeAction()
	{
		$this->init();
		
		if($this->getRequest()->isPost()):
			$request= $this->getRequest();
			$form = $request->getPost();
			$data =array(
					'receive_date' => $form['receive_date'],
					'cheque_code' => $cheque_no,
					'bank_account' => $form['bank_account'],
					'start_cheque_no' => $form['cheque_start_no'],
					'end_cheque_no' => $form['cheque_end_no'],
					'no_of_cheque' => $form['no_of_cheque'],
					'author' =>$this->_author,
					'created' =>$this->_created,
					'modified' =>$this->_modified
			);
			$data = $this->_safedataObj->rteSafe($data);
			//echo "<pre>";print_r($data); exit;
			$result = $this->getDefinedTable('Accounts\ChequeTable')->save($data);
			
			if($result>0):
				$start_cheque_no= $form['cheque_start_no'];
				$end_cheque_no= $form['cheque_end_no'];
				$remarks = $form['remarks'];
				for($start_cheque_no=$start_cheque_no; $start_cheque_no <= $end_cheque_no; $start_cheque_no++):
					$cheque_detail_data = array(
						'cheque_id' => $result,
						'instrument_no' => $start_cheque_no,
						'author' =>$this->_author,
						'created' =>$this->_created,
						'modified' =>$this->_modified,
					);
					$cheque_detail_data = $this->_safedataObj->rteSafe($cheque_detail_data);
					$this->getDefinedTable('Accounts\ChequeDetailsTable')->save($cheque_detail_data);
				endfor;
				$this->flashMessenger()->addMessage("success^ Successfully added new Cheque :".$start_cheque_no);
				return $this->redirect()->toRoute('cheque');
			else:
				$this->flashMessenger()->addMessage("Failed^ Failed to add new dispatch");
				return $this->redirect()->toRoute('cheque');
			endif;
		endif;
		
		$ViewModel = new ViewModel(array(
		    'title' => "Edit Cheque",
			'cheque' => $this->getDefinedTable('Accounts\ChequeTable')->get($this->_id),
			'user_org'=> $this->_userorg,
		));
		$ViewModel->setTerminal(True);
		return $ViewModel;
	}
	/**
	 * View Advance Salary
	**/
	public function viewchequeAction()
	{
		$this->init();
		
		return new ViewModel(array(
			'title'         => 'View Cheque',
			'cheque'     => $this->getDefinedTable('Accounts\ChequeTable')->get($this->_id),
			'CBObj'	 =>	$this->getDefinedTable('Accounts\ChequeDetailsTable'),
			'userID' => $this->organisation_id,
		));
	}
	/**
	 * Chequecancel action
	**/
	public function chequecancelledlistAction()
	{
		$this->init();
		
		return new ViewModel(array(
			'title'          => "Cheque Cancellation",
			'cheque' => $this->getDefinedTable('Accounts\ChequeDetailsTable')->get(array('cd.status'=>4)),
			'empldObj' => $this->getDefinedTable('Hr\EmployeeDetailsTable'),
		));
	}
	/**
	 * Add Cheque
	**/
	public function addchequecancelAction()
	{
		$this->init();
		if($this->getRequest()->isPost()):
			$request= $this->getRequest();
			$form = $request->getPost();
				$cheque_coderesults = $this->getDefinedTable('Accounts\ChequeDetailsTable')->get(array('instrument_no'=>$form['instrument_no']));
				foreach($cheque_coderesults as $rlt);
				$cheque_detail_data = array(
					'id' => $rlt['id'],
					'cancellation_date' => $form['cancellation_date'],
					'status' =>4,
					'cancelled_by' => $this->employee_details_id,
					'reason' =>$form['note'],
					'author' =>$this->employee_details_id,
					'modified' =>$this->_modified,
				);
				$result = $this->getDefinedTable('Accounts\ChequeDetailsTable')->save($cheque_detail_data);
			if($result > 0):
				$this->flashMessenger()->addMessage("success^ Successfully Cancel the cheque :".$form['instrument_no']);
				return $this->redirect()->toRoute('cheque', array('action' =>'viewcheque', 'id' => $rlt['cheque_id']));
			else:
				$this->flashMessenger()->addMessage("Failed^ Failed to add new cheque no. ");
				return $this->redirect()->toRoute('cheque', array('action' =>'viewcheque', 'id' =>$rlt['cheque_id']));
			endif;
		endif;
    	return  new ViewModel(array(
			'title'   =>"Add Cheque Book Cancel",
			'chequedtls'=>	$this->getDefinedTable('Accounts\ChequeDetailsTable')->get($this->_id),
		     ));
	}
	/**
	 * Action for getting Accounts
	 */
	public function getchequenoAction()
	{
		
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
}

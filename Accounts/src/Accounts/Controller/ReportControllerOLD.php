<?php
namespace Accounts\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\TableGateway\TableGateway;
use Zend\Authentication\AuthenticationService;
use Zend\Db\Adapter\Adapter;    

use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;

class ReportController extends AbstractActionController
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
	 * trial balance Sheet action
	 * 
	 **/
	public function trialbalanceAction()
	{
		$this->init();
		if($this->getRequest()->isPost()):
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
		    'tier'         => $tier,
			'organisation' => $organisation,
			'start_date'   => $start_date,
			'end_date'     => $end_date,
		); 
		return new ViewModel(array(
		    'title'      =>"Trial Balance",
			'classObj'   => $this->getDefinedTable("Accounts\ClassTable"),
			'groupObj'   => $this->getDefinedTable("Accounts\GroupTable"),
			'headObj'    => $this->getDefinedTable("Accounts\HeadTable"),
			'subheadObj' => $this->getDefinedTable("Accounts\SubheadTable"),
			'transactiondetailObj' => $this->getDefinedTable("Accounts\TransactiondetailTable"),
			'data'       => $data,
			'orgObj'     => $this->getDefinedTable('Hr\OrganisationTable'),
			'userID' => $this->employee_details_id,
		)); 
	}
	
	/**
	 * balance Sheet action
	 * 
	 **/
	public function balancesheetAction(){
		$this->init();
		if($this->getRequest()->isPost()):
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
		    'tier'     => $tier,
			'organisation' => $organisation,
			'start_date' => $start_date,
			'end_date'  => $end_date,
		);
		return new ViewModel(array(
			'classObj' => $this->getDefinedTable("Accounts\ClassTable"),
			'groupObj' => $this->getDefinedTable("Accounts\GroupTable"),
			'headObj' => $this->getDefinedTable("Accounts\HeadTable"),
			'subheadObj' => $this->getDefinedTable("Accounts\SubheadTable"),
			'transactiondetailObj' => $this->getDefinedTable("Accounts\TransactiondetailTable"),
			'data' => $data,
			'minDate' => $this->getDefinedTable("Accounts\TransactionTable")->getMin('voucher_date'),
			'orgObj' => $this->getDefinedTable('Hr\OrganisationTable'),
		));
	}
    /**
	 * profit loss statement action
	 * 
	 **/
	public function profitlossAction(){
		$this->init();
		if($this->getRequest()->isPost()):
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
		    'tier'     => $tier,
			'organisation' => $organisation,
			'start_date' => $start_date,
			'end_date'  => $end_date,
		);
		return new ViewModel(array(
			'classObj' => $this->getDefinedTable("Accounts\ClassTable"),
			'groupObj' => $this->getDefinedTable("Accounts\GroupTable"),
			'headObj' => $this->getDefinedTable("Accounts\HeadTable"),
			'subheadObj' => $this->getDefinedTable("Accounts\SubheadTable"),
			'transactiondetailObj' => $this->getDefinedTable("Accounts\TransactiondetailTable"),
			'data' => $data,
			'minDate' => $this->getDefinedTable("Accounts\TransactionTable")->getMin('voucher_date'),
            'orgObj'  => $this->getDefinedTable('Hr\OrganisationTable'),
		));
	}
	/**
	 * Ledger and Sub-Ledger Report
	 */
	public function ledgerAction()
	{
		$this->init();
		if($this->getRequest()->isPost()):
			$form = $this->getRequest()->getPost();
			$organisation = $form['organisation'];
			$head = $form['head'];
			$sub_head = $form['sub_head'];
			$start_date = $form['start_date'];
			$end_date = $form['end_date'];
		else:
		    $organisation = $this->organisation_id;
		endif;
		$data = array(
			'organisation' => $organisation,
			'head' => $head,
			'sub_head' => $sub_head,
			'start_date' => $start_date,
			'end_date' => $end_date,
		);
		$group_id = $this->getDefinedTable('Accounts\HeadTable')->getColumn($head,'group');
		$class_id = $this->getDefinedTable('Accounts\GroupTable')->getColumn($group_id,'class');
		return new ViewModel(array(
			'title' => "Ledger & Sub-Ledger",
			'data' => $data,
			'class' => $class_id,
			'headObj' => $this->getDefinedTable('Accounts\HeadTable'),
			'subheadObj' => $this->getDefinedTable('Accounts\SubheadTable'),
			'transactionObj' => $this->getDefinedTable('Accounts\TransactionTable'),
			'journalObj' => $this->getDefinedTable('Accounts\JournalTable'),
			'transactiondetailObj'=> $this->getDefinedTable('Accounts\TransactiondetailTable'),
			'closingbalanceObj'=> $this->getDefinedTable('Accounts\ClosingbalanceTable'),
			'orgObj'=> $this->getDefinedTable('Hr\OrganisationTable'),							
		));
	}
    /**
	 * get Subhead according to Head
	**/
	public function getsubheadAction()
	{
		$this->init();
		
		$form = $this->getRequest()->getPost();
		
		$head_id = $form['head'];
		$organisation_id = $form['organisation_id'];
		
		$subHeadDtls = $this->getDefinedTable('Accounts\SubheadTable')->get(array('sh.head'=>$head_id));
		
		$sub_heads .="<option value='-1'>All</option>";
		foreach($subHeadDtls as $subhead):
			$sub_heads .="<option value='".$subhead['id']."'>".$subhead['name']."</option>";
		endforeach;
		
		echo json_encode(array(
				'subheads' => $sub_heads,
		));
		exit;
	}
	  /**
	 * profit loss statement action
	 * 
	 **/
	public function cashflowAction(){
		$this->init();
		if($this->getRequest()->isPost()):
			$form = $this->getRequest()->getPost();
            $organisation = $form['organisation'];
			$start_date = $form['start_date'];
			$end_date = $form['end_date'];
		else:
		    $organisation = $this->organisation_id;
		    $start_date = date('Y-m-d');
		    $end_date = date('Y-m-d');
		endif;
		$data = array(
			'organisation' => $organisation,
			'start_date' => $start_date,
			'end_date'  => $end_date,
		);
		return new ViewModel(array(
			'classObj' => $this->getDefinedTable("Accounts\ClassTable"),
			'groupObj' => $this->getDefinedTable("Accounts\GroupTable"),
			'headObj' => $this->getDefinedTable("Accounts\HeadTable"),
			'subheadObj' => $this->getDefinedTable("Accounts\SubheadTable"),
			'transactiondetailObj' => $this->getDefinedTable("Accounts\TransactiondetailTable"),
			'transactionObj' => $this->getDefinedTable("Accounts\TransactionTable"),
			'data' => $data,
			'minDate' => $this->getDefinedTable("Accounts\TransactionTable")->getMin('voucher_date'),
            'orgObj'  => $this->getDefinedTable('Hr\OrganisationTable'),
            'chequeDtlObj'  => $this->getDefinedTable('Accounts\ChequeDetailsTable'),
		));
	}
}

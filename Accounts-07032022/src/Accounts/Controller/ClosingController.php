<?php
namespace Accounts\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\TableGateway\TableGateway;
use Zend\Authentication\AuthenticationService;

class ClosingController extends AbstractActionController
{   
	protected $_table; 		// database table 
    protected $_user; 		// user detail
    protected $_login_id; 	// logined user id
    protected $_login_role; // logined user role
    protected $_author; 	// logined user id
    protected $_created; 	// current date to be used as created dated
    protected $_modified; 	// current date to be used as modified date
    protected $_config; 	// configuration details
    protected $_dir; 		// default file directory
    protected $_id; 		// route parameter id, usally used by crude
    protected $_auth; 		// checking authentication
    
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
		$this->_auth = new AuthenticationService;
		if(!$this->_auth->hasIdentity()):
			$this->flashMessenger()->addMessage('error^ You dont have right to access this page!');
			$this->redirect()->toRoute('auth', array('action' => 'login'));
		endif;
		
		if(!isset($this->_config)) {
			$this->_config = $this->getServiceLocator()->get('Config');
		}
		if(!isset($this->_user)) {
			$this->_user = $this->identity();
		}
		if(!isset($this->_login_id)){
			$this->_login_id = $this->_user->id;  
		}
		if(!isset($this->_login_role)){
			$this->_login_role = $this->_user->role;  
		}

		if(!isset($this->_author)){
			$this->_author = $this->_user->id;  
		}
        if(!isset($this->_userorg)){
			$this->_userorg = $this->_user->organisation_id;  
		}
		$this->_id = $this->params()->fromRoute('id');
		$this->_created = date('Y-m-d H:i:s');
		$this->_modified = date('Y-m-d H:i:s');
	}
	
	/**
	 *  index action
	 */
	public function closingAction()
	{
		$this->init();		
		$min_year = date('Y', strtotime($this->getDefinedTable("Accounts\TransactionTable")->getMin('voucher_date')));
		if($this->getRequest()->isPost()):
			$form = $this->getRequest()->getPost();
			$year = $form['year'];
			if($form['task']=='1'):
				$filter = array(
						'start_date'=> date('Y-m-d',strtotime('10-01-'.$year)),
						'end_date'  => date('Y-m-t',strtotime('01-12-'.$year)),
						'activity'  => -1,
						'region'    => -1,
						'location'  => -1,
				);
				if(!$this->getDefinedTable("Accounts\ClosingbalanceTable")->isPresent(array('year'=>$year))):
					foreach($this->getDefinedTable("Accounts\SubheadTable")->getAll() as $rows):				
						$closing_balance = $this->getDefinedTable("Accounts\TransactiondetailTable")->getClosingBalance($filter, $rows['id'], 1);
						$closing_cr = ($closing_balance < 0)? -$closing_balance:'0';
						$closing_dr = ($closing_balance > 0)? $closing_balance:'0';
						$data = array(
								'sub_head'   => $rows['id'],
								'year'       => $year,
								'closing_dr' => $closing_dr,
								'closing_cr' => $closing_cr,
								'author'     =>$this->_author,
								'created'    =>$this->_created,
								'modified'   =>$this->_modified,
							);
						$this->getDefinedTable("Accounts\ClosingbalanceTable")->save($data);
					endforeach;
				endif;
			endif;
		else:
			$year = date('Y');
		endif;
		return new ViewModel(array(
			'title'  => 'Closing Balance',
			'selected_year' => $year,
			'min_year'	=> $min_year,
			'closingbalanceObj' => $this->getDefinedTable("Accounts\ClosingbalanceTable"),
			'subheadObj' => $this->getDefinedTable("Accounts\SubheadTable"),
			'transactiondetailObj' => $this->getDefinedTable("Accounts\TransactiondetailTable"),
   		));
	} 
	
	public function addclosingAction(){
		$this->init();
		if($this->getRequest()->isPost()):
			$form = $this->getRequest()->getPost();
		   //echo"<pre>"; print_r($form); exit;
			$closing_id = $form['closing_id'];
			$closing_head = $form['head'];
			$closing_organisation = $form['organisation'];
			$closing_sub_head = $form['sub_head'];
			$closing_dr =$form['closing_dr'];
			$closing_cr =$form['closing_cr'];	
            for($i=0;$i<sizeof($closing_sub_head);$i++){
			  //if($closing_dr[$i] != 0 || $closing_cr[$i] != 0):
				if($closing_id[$i] > 0 ){
					/* Update with year and subhead*/
					$data = array(
					   'id'  => $closing_id[$i],
					   'organisation_id'  =>$closing_organisation[$i],
					   'head'  =>$closing_head[$i],
					   'sub_head'  =>$closing_sub_head[$i],
					   'closing_dr'  => $closing_dr[$i],
					   'closing_cr'  => $closing_cr[$i],
					   'author' =>$this->_author,					
					   'modified' =>$this->_modified,
					);
				}
				else{
					/* Insert with year and subhead*/
					$data = array(	
                        'organisation_id'  =>$closing_organisation[$i],					
					   'head'  =>$closing_head[$i],
					   'sub_head'  =>$closing_sub_head[$i],
					   'year'       => $form['year'],
					   'closing_dr'  => $closing_dr[$i],
					   'closing_cr'  => $closing_cr[$i],
					   'author' =>$this->_author,
					   'created' =>$this->_created,
					   'modified' =>$this->_modified,
					);					
				}
				$result = $this->getDefinedTable("Accounts\ClosingbalanceTable")->save($data);
              //endif; 				
			}
			if($result > 0 ){
				$this->flashMessenger()->addMessage('success^ Successfully Added the closing balance !');
				$this->redirect()->toRoute('closing', array('action' => 'addclosing'));	
			}				
		endif; 
		return new ViewModel(array(
			'title'  => 'Closing Balance',
			'selected_year' => $year,
			'min_year'	=> $min_year,
			'closingbalanceObj' => $this->getDefinedTable("Accounts\ClosingbalanceTable"),
			'subheadObj' => $this->getDefinedTable("Accounts\SubheadTable"),
			'headObj' => $this->getDefinedTable("Accounts\HeadTable"),	

   		));
	}
	
	public function sheadlistAction()
	{
		$this->init();		
		$param = explode('-',$this->_id);
		$head = $param['1'];
		$year = $param['0']; 
		$ViewModel = new ViewModel(array(
		     'head'       => $head,
			 'year'       => $year,
			 'subheadObj' => $this->getDefinedTable('Accounts\SubheadTable'),
			 'headObj' => $this->getDefinedTable("Accounts\HeadTable"),	
             'closingbalanceObj' => $this->getDefinedTable("Accounts\ClosingbalanceTable"),	
             'cashaccountObj' => $this->getDefinedTable('Accounts\CashaccountTable'),			
             'stdObj' => $this->getDefinedTable('Accounts\StudentTable'),			 
             'headObj' => $this->getDefinedTable("Accounts\HeadTable"),	
             'bankaccountObj' => $this->getDefinedTable('Accounts\BankaccountTable'),
			 'orgObj' => $this->getDefinedTable("Acl\OrganisationTable"),
			 'emplObj' => $this->getDefinedTable('Accounts\EmployeeDetailsTable'),
			 'ptObj' => $this->getDefinedTable('Accounts\PartyTable'),
			 'budgetObj' => $this->getDefinedTable('Accounts\BudgetTable'),
			 'userorg'    =>$this->_userorg,
			 'payhObj' => $this->getDefinedTable('Accounts\PayheadTable'),
             'assetObj' => $this->getDefinedTable('Accounts\AssetsTable'),		 
		));
		$ViewModel->setTerminal(True);
		return $ViewModel;
	}
}

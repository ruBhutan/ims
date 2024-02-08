<?php
namespace Accounts\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\TableGateway\TableGateway;
use Zend\Authentication\AuthenticationService;

use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;

class PayrollReportController extends AbstractActionController
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
	 *  index action
	 */
	public function indexAction()
	{
		$this->init();

		return new ViewModel();
	}
	
	/**
	 * report action
	 */
	public function payregisterAction()
	{
		$this->init();
		if($this->getRequest()->isPost())
		{
			$form = $this->getRequest()->getPost();
			$userorg = $form['organisation'];
			$month = $form['month'];
			$year = $form['year'];
			$department = $form['department'];
		}else{
		    $userorg = $this->organisation_id;
			$month = date('m');
		    $year = date('Y');
            $department = '-1';		
		}		
		$data = array(
			'year' => $year,
			'month' => $month,
			'userorg' => $userorg,
			'department' => $department,
		);
		//print_r($data); exit;
		return new ViewModel(array(
			'title' => 'Payroll Report',
			'earningHead' => $this->getDefinedTable('Accounts\PayheadTable')->get(array('deduction'=>0)),
			'deductionHead' => $this->getDefinedTable('Accounts\PayheadTable')->get(array('deduction'=>1)),
			'payrollObj' => $this->getDefinedTable('Accounts\PayrollTable'),
			'paydetailObj' => $this->getDefinedTable('Accounts\PaydetailTable'),
			'department' => $this->getDefinedTable('Hr\DepartmentTable')->getAll(),
			'organisations' => $this->getDefinedTable('Hr\OrganisationTable')->get(array('id'=>$this->organisation_id)),
			'data' => $data,
			'userorg' =>$this->organisation_id,
			'minYear' => $this->getDefinedTable('Accounts\PayrollTable')->getMin('year'),
			'departmentObj' => $this->getDefinedTable('Hr\DepartmentTable'),
			'orgObj' => $this->getDefinedTable('Hr\OrganisationTable'),
			'temppayrollObj' => $this->getDefinedTable('Accounts\TempPayrollTable'),
			'paystructureObj' => $this->getDefinedTable('Accounts\PaystructureTable'),
			'postitleObj' => $this->getDefinedTable('Hr\PositiontitleTable'),
			'plObj' => $this->getDefinedTable('Hr\PositionLevelTable'),
			'dObj' => $this->getDefinedTable('Hr\DepartmentTable')
		));
        //$this->layout('layout/reportlayout');
		//return $ViewModel;
	}
	/**
	* pay head report
	*/
	public function phreportAction()
	{
		$this->init();
		list($month,$year,$payheads,$organisation)=explode('&', $this->_id);
		if(isset($payheads)):
			$payheads = explode('_', $payheads);
		endif;

		if(sizeof($payheads)==0):
			$payheads = array('1'); //default selection
		endif;
		if($year == 0):
			$max_year = $this->getDefinedTable('Accounts\PayrollTable')->getMax('year');
			$max_month = $this->getDefinedTable('Accounts\PayrollTable')->getMax('month', array('year' => $max_year));
			$year = ($max_month == 12)? $max_year+1 : $max_year;
		endif;
		if($month == 0):
			$max_year = $this->getDefinedTable('Accounts\PayrollTable')->getMax('year');
			$max_month = $this->getDefinedTable('Accounts\PayrollTable')->getMax('month', array('year' => $max_year));
			$month = ($max_month == 12)? 1 : $max_month+1;
		endif;
		$data = array(
			'year'=>$year,
			'month'=> $month,
			'data_organisation'=> $organisation,
		);
		$ViewModel = new ViewModel(array(
				'title' 	 => 'Pay Head Report',
				'payheads'	 => $payheads,
				'payheadObj' => $this->getDefinedTable('Accounts\PayheadTable'),
				'paydetailObj' => $this->getDefinedTable('Accounts\PayDetailTable'),
				'payrollObj' => $this->getDefinedTable('Accounts\PayrollTable'),
				'department' => $this->getDefinedTable('Hr\DepartmentTable')->getAll(),
				'organisations' => $this->getDefinedTable('Hr\OrganisationTable')->get(array('id'=>$this->_userorg)),
				'data' => $data,
				'userorg' =>$this->_userorg,
				'temppayrollObj' => $this->getDefinedTable('Accounts\TempPayrollTable'),
				'paystructureObj' => $this->getDefinedTable('Accounts\PaystructureTable'),
				'postitleObj' => $this->getDefinedTable('Hr\PositiontitleTable'),
				'plObj' => $this->getDefinedTable('Hr\PositionLevelTable'),
		));
		$this->layout('layout/reportlayout');
		return $ViewModel;
	}

	/**
	* loan reports 
	*/
	/**
	* loan reports 
	*/
	public function loanreportAction()
	{
		$this->init();
	    list($month, $year,$payheads,$organisation)=explode('&', $this->_id);
		if(isset($payheads)):
			$payheads = explode('_', $payheads);
		endif;

		if(sizeof($payheads)==0):
			$payheads = array('9'); //default selection
		endif;

		if($year == 0):
			$max_year = $this->getDefinedTable('Accounts\PayrollTable')->getMax('year');
			$max_month = $this->getDefinedTable('Accounts\PayrollTable')->getMax('month', array('year' => $max_year));
			$year = ($max_month == 12)? $max_year+1 : $max_year;
		endif;
		if($month == 0):
			$max_year = $this->getDefinedTable('Accounts\PayrollTable')->getMax('year');
			$max_month = $this->getDefinedTable('Accounts\PayrollTable')->getMax('month', array('year' => $max_year));
			$month = ($max_month == 12)? 1 : $max_month+1;
		endif;
		$data = array(
			'year'=>$year,
			'month'=> $month,
			'data_organisation'=> $organisation,
		);
		$ViewModel = new ViewModel(array(
				'title' 	 	=> 'Loan Report',
				'payheads'	 	=> $payheads,
				'payheadObj' 	=> $this->getDefinedTable('Accounts\PayheadTable'),
				'paydetailObj'  => $this->getDefinedTable('Accounts\PayDetailTable'),
				'payrollObj' 	=> $this->getDefinedTable('Accounts\PayrollTable'),
				'department' => $this->getDefinedTable('Acl\DepartmentTable')->getAll(),
				'data' => $data,
				'userorg' =>$this->_userorg,
				'temppayrollObj' => $this->getDefinedTable('Accounts\TempPayrollTable'),
				'orgObj' => $this->getDefinedTable('Acl\OrganisationTable'),
				'paystructureObj' => $this->getDefinedTable('Accounts\PaystructureTable'),
				'postitleObj' => $this->getDefinedTable('Hr\PositiontitleTable'),
				'plObj' => $this->getDefinedTable('Hr\PositionLevelTable'),
		));
		$this->layout('layout/reportlayout');
		return $ViewModel;
	}
	/**
	* group insurence scheme action
	*/
	public function gisAction()
	{
		$this->init();
		list($month, $year,$organisation)=explode('&', $this->_id);
		if($year == 0):
			$max_year = $this->getDefinedTable('Accounts\PayrollTable')->getMax('year');
			$max_month = $this->getDefinedTable('Accounts\PayrollTable')->getMax('month', array('year' => $max_year));
			$year = ($max_month == 12)? $max_year+1 : $max_year;
		endif;
		if($month == 0):
			$max_year = $this->getDefinedTable('Accounts\PayrollTable')->getMax('year');
			$max_month = $this->getDefinedTable('Accounts\PayrollTable')->getMax('month', array('year' => $max_year));
			$month = ($max_month == 12)? 1 : $max_month+1;
		endif;
		$data = array(
			'year'=>$year,
			'month'=> $month,
			'data_organisation'=> $organisation,
		);
		$ViewModel = new ViewModel(array(
			'title' 	 	=> 'Group Insurence Scheme',
			'payheadObj' 	=> $this->getDefinedTable('Accounts\PayheadTable'),
			'employeeObj'   => $this->getDefinedTable('Hr\EmployeeDetailsTable'),
			'paydetailObj'  => $this->getDefinedTable('Accounts\PayDetailTable'),
			'payrollObj' 	=> $this->getDefinedTable('Accounts\PayrollTable'),
			'paygroupObj'=> $this->getDefinedTable('Accounts\PaygroupTable'),
			'department' => $this->getDefinedTable('Hr\DepartmentTable')->getAll(),
			'organisations' => $this->getDefinedTable('Hr\OrganisationTable')->get(array('id'=>$this->_userorg)),
			'data' => $data,
			'temppayrollObj' => $this->getDefinedTable('Accounts\TempPayrollTable'),
			'paystructureObj' => $this->getDefinedTable('Accounts\PaystructureTable'),
			'postitleObj' => $this->getDefinedTable('Hr\PositiontitleTable'),
			'plObj' => $this->getDefinedTable('Hr\PositionLevelTable'),
		));
		$this->layout('layout/reportlayout');
		return $ViewModel;
	}
	/**
	 * Provident Fund Report
	**/
	public function pfreportAction()
	{
		$this->init();
		list($organisation,$year, $month) = explode('&', $this->_id);
		$organisation = ($organisation == 0)? "":$organisation;				
		$activity = ($activity == 0)? "":$activity;
		$sub_activity = ($sub_activity == 0)? "":$sub_activity;
		if($year == 0):
			$max_year = $this->getDefinedTable('Accounts\PayrollTable')->getMax('year');
			$max_month = $this->getDefinedTable('Accounts\PayrollTable')->getMax('month', array('year' => $max_year));
			$year = ($max_month == 12)? $max_year+1 : $max_year;
		endif;
		if($month == 0):
			$max_year = $this->getDefinedTable('Accounts\PayrollTable')->getMax('year');
			$max_month = $this->getDefinedTable('Accounts\PayrollTable')->getMax('month', array('year' => $max_year));
			$month = ($max_month == 12)? 1 : $max_month+1;
		endif;
		$data = array(
				'year' => $year,
				'month' => $month,
				'organisation' => $organisation,
		);
		$ViewModel = new ViewModel(array(
				'title' 	 	=> 'Provident Fund Report',
				'payheadObj' 	=> $this->getDefinedTable('Accounts\PayheadTable'),
				'employeeObj'   => $this->getDefinedTable('Hr\EmployeeDetailsTable'),
				'paydetailObj'  => $this->getDefinedTable('Accounts\PayDetailTable'),
				'payrollObj' 	=> $this->getDefinedTable('Accounts\PayrollTable'),
				'paygroupObj'=> $this->getDefinedTable('Accounts\PaygroupTable'),
				'organisations' => $this->getDefinedTable('Acl\OrganisationTable')->get(array('id'=>$this->_userorg)),
				'data' => $data,
				'temppayrollObj' => $this->getDefinedTable('Accounts\TempPayrollTable'),
				'paystructureObj' => $this->getDefinedTable('Accounts\PaystructureTable'),
		));
		$this->layout('layout/accreportlayout');
		return $ViewModel;
	}
	/**
	 * Health Tax and Personal Income Tax
	**/
	public function htpitreportAction()
	{
		$this->init();
		list($organisation, $year, $month) = explode('&', $this->_id);
		$organisation = ($organisation == 0)? "":$organisation;	
		if($year == 0):
			$max_year = $this->getDefinedTable('Accounts\PayrollTable')->getMax('year');
			$max_month = $this->getDefinedTable('Accounts\PayrollTable')->getMax('month', array('year' => $max_year));
			$year = ($max_month == 12)? $max_year+1 : $max_year;
		endif;
		if($month == 0):
			$max_year = $this->getDefinedTable('Accounts\PayrollTable')->getMax('year');
			$max_month = $this->getDefinedTable('Accounts\PayrollTable')->getMax('month', array('year' => $max_year));
			$month = ($max_month == 12)? 1 : $max_month+1;
		endif;
		$data = array(
				'year' => $year,
				'month' => $month,
				'organisation' => $organisation,
		);
	    $ViewModel = new ViewModel(array(
				'title' 	 	=> 'HT & PIT Report',
				'payheadObj' 	=> $this->getDefinedTable('Accounts\PayheadTable'),
				'employeeObj'   => $this->getDefinedTable('Hr\EmployeeDetailsTable'),
				'paydetailObj'  => $this->getDefinedTable('Accounts\PayDetailTable'),
				'payrollObj' 	=> $this->getDefinedTable('Accounts\PayrollTable'),
				'paygroupObj'=> $this->getDefinedTable('Accounts\PaygroupTable'),
				'organisations'=> $this->getDefinedTable('Acl\OrganisationTable')->get(array('id'=>$this->_userorg)),
				'data' => $data,
				'temppayrollObj' => $this->getDefinedTable('Accounts\TempPayrollTable'),
				'paystructureObj' => $this->getDefinedTable('Accounts\PaystructureTable'),
				'payheadObj' => $this->getDefinedTable('Accounts\PayheadTable'),
		));
		$this->layout('layout/accreportlayout');
		return $ViewModel;
	}
	/**
	 * get sub activity title
	 */
	 public function getsubactivityAction()
	 {
		$this->init();
		$form = $this->getRequest()->getpost();			
		$actID = $form['actID'];
		$sactivities = $this->getDefinedTable('Acl\SubActivityTable')->get(array('activity_id'=>$actID));
		$sacts .="<option value='-1'>All</option>";
		foreach($sactivities as $sact):
			$sacts .="<option value='".$sact['id']."'>".$sact['sub_activity']."</option>";
		endforeach;
		echo json_encode(array(
				'sact' => $sacts,
				'selected_sact' =>'-1',
		));
		exit;
	 }
}

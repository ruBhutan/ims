<?php
namespace Accounts\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\TableGateway\TableGateway;
use Zend\Authentication\AuthenticationService;

use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;

class PayrollController extends AbstractActionController   
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
	 *  Monthly pay index action
	 */
	public function indexAction()
	{
		$this->init();
        if($this->getRequest()->isPost())
		{
			$form = $this->getRequest()->getPost();
			$year = $form['year'];
			$organisation = $form['organisation'];
			$year = ($year == 0)? date('Y'):$year;
		}
		else{
			$year = "";
			$year = ($year == '')? date('Y'):$year;
			$organisation = $this->organisation_id;
		}
		$data = array(
				'year' => $year,
				'organisation' => $organisation,
		    );
		$month = "";
		$month = ($month == 0)? date('m'):$month;
		$payrolls = $this->getDefinedTable('Accounts\PayrollTable')->getLocationYearWise($year,$organisation);
		$temppayrolls =  $this->getDefinedTable('Accounts\TempPayrollTable')->getLocationYearWise($year,$organisation);
		return new ViewModel(array(
			'title'  => 'Payroll',
			'payroll' => $payrolls,
			'temppayroll' => $temppayrolls,
			'minYear' => $this->getDefinedTable('Accounts\PayrollTable')->getMin('year'),
			'data' =>$data,
			'checkbooking' => (sizeof($this->getDefinedTable('Accounts\SalarybookingTable')->get(array('month'=> $month,'year'=> $year,'organisation_id'=>$this->organisation_id,'salary_advance'=>array('1','2')))> 0))? True:False,
			'payrollObj' => $this->getDefinedTable('Accounts\PayrollTable'),
			'temppayrollObj' => $this->getDefinedTable('Accounts\TempPayrollTable'),
			'orgObj' => $this->getDefinedTable('Hr\OrganisationTable'),
		));
	}
	/**
	 *  payroll action displays pay detail action of particular month
	 * 
	 */
	public function payrollAction()
	{
		$this->init();
		list($year, $month) = explode('-', $this->_id);
		$month = 0; $year = 0;
		$month = ($month == 0)? date('m'):$month;
		$year = ($year == 0)? date('Y'):$year;
		$user_org = $this->organisation_id;
		//Check Account or Accounts Officer
		if(!$this->getDefinedTable('Accounts\PayrollTable')->isPresent(array('month'=>$month, 'year'=>$year,'organisation_id'=>$user_org))):
			$this->redirect()->toRoute('payroll',array('action'=>'definepayroll','id'=>$year.'-'.$month.'-'.$user_org));
		endif;	
        $payroll = $this->getDefinedTable('Accounts\PayrollTable')->getPayroll($month,$year,$user_org);		
		return new ViewModel(array(
			'title'  => 'Payroll',
			'employeeObj' => $this->getDefinedTable('Hr\EmployeeDetailsTable'),
			'month' => $month,
			'year' => $year,
			'user_org' =>$user_org,
			'payroll'=>$payroll,
			'bookingbutton' => (sizeof($this->getDefinedTable('Accounts\SalarybookingTable')->get(array('month'=> $month,'year'=> $year,'organisation_id'=>$this->organisation_id,'salary_advance'=>'1')))> 0)? True:False,
			'advancebutton' => (sizeof($this->getDefinedTable('Accounts\SalarybookingTable')->get(array('month'=> $month,'year'=> $year,'organisation_id'=>$this->organisation_id,'salary_advance'=>'2')))> 0)? True:False,
			'orgObj'=>$this->getDefinedTable('Hr\OrganisationTable'),
			'dObj'=>$this->getDefinedTable('Hr\DepartmentTable'),
			'duObj'=>$this->getDefinedTable('Hr\DepartmentUnitTable'),
		));
	}
	/*
	 * generate /update (define) payroll for new month
	 * updation will be all done in tempayroll table
	 */
	public function definepayrollAction()
	{
		$this->init();
		
		$this->_id = isset($this->_id)?$this->_id:date('Y-m-d');
		list($year, $month) = explode('-', $this->_id);	  
	
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
		$userorg = $this->organisation_id;
		//Check Account or Accounts Officer
		if($this->getDefinedTable('Accounts\PayrollTable')->isPresent(array('month'=>$month, 'year'=>$year,'organisation_id'=>$userorg))):
			$this->redirect()->toRoute('payroll',array('id'=>$year.'-'.$month.'-'.$userorg));
		endif;
		$data=array(
			'month'=> $month,
			'year' => $year,
			'userorg' =>$userorg,
			'author'=> $this->employee_details_id,
			'created'=>$this->_created,
			'modified' => $this->_modified
		);
		//prepare temporary payroll
		$this->getDefinedTable('Accounts\TempPayrollTable')->prepareTempPayroll($data);
		foreach($this->getDefinedTable('Accounts\TempPayrollTable')->get(array('pr.status'=>'0','pr.organisation_id'=>$userorg)) as $temp_payroll):
			$employee_details = $temp_payroll['employee_details'];
			$total_earning = 0;		
			$total_deduction = 0;
			$total_actual_earning = 0;
			$total_actual_deduction = 0;
			foreach($this->getDefinedTable('Accounts\PaystructureTable')->get(array('sd.employee_details' => $employee_details,'sd.organisation_id'=>$userorg, 'ph.deduction'=>'1')) as $paydetails):
				if($paydetails['dlwp']==1):
					$amount = $paydetails['amount'] - ($paydetails['amount']/$temp_payroll['working_days']) * $temp_payroll['leave_without_pay'];
				else:
					$amount = $paydetails['amount'];
				endif;
				if($paydetails['roundup']==1):
					$amount =round($amount);
				endif;
				$total_deduction = $total_deduction + $amount;
				$total_actual_deduction = $total_actual_deduction + $paydetails['amount'];
			endforeach;	
			foreach($this->getDefinedTable('Accounts\PaystructureTable')->get(array('sd.employee_details' => $employee_details,'sd.organisation_id'=>$userorg, 'ph.deduction'=>'0')) as $paydetails):
				if($paydetails['dlwp']==1):
					$amount = $paydetails['amount'] - ($paydetails['amount']/$temp_payroll['working_days']) * $temp_payroll['leave_without_pay'];
				else:
					$amount = $paydetails['amount'];
				endif;
				if($paydetails['roundup']==1):
					$amount =round($amount);
				endif;
				$total_earning = $total_earning + $amount;
				$total_actual_earning = $total_actual_earning + $paydetails['amount'];
			endforeach;				
			$leave_encashment = $temp_payroll['leave_encashment'];
			$bonus = $temp_payroll['bonus'];
			$net_pay = $total_earning + $leave_encashment + $bonus - $total_deduction;
			$earning_dlwp = $total_actual_earning - $total_earning;
			$deduction_dlwp = $total_actual_deduction - $total_deduction;
			$data1 = array(
				'id'	=> $temp_payroll['id'],
				'gross' => $total_earning,
				'total_deduction' => $total_deduction,
				'organisation_id' =>$userorg,
				'net_pay' => $net_pay,
				'earning_dlwp' => $earning_dlwp,
				'deduction_dlwp' => $deduction_dlwp,
				'status' => '1', // initiated
			    'author'=> $this->employee_details_id,
				'modified' =>$this->_modified,
			);			
			$data1 = $this->_safedataObj->rteSafe($data1);			
			
			$result1 = $this->getDefinedTable('Accounts\TempPayrollTable')->save($data1);
		endforeach;
		$temppayroll = $this->getDefinedTable('Accounts\TempPayrollTable')->getTempPayroll($year,$userorg);
		return new ViewModel(array(
			'title' => 'Add Pay roll',
			'month' => $month,
			'year' => $year,
			'userorg' => $userorg,
			'temppayroll' =>$temppayroll,
			'temppayrollObj' => $this->getDefinedTable('Accounts\TempPayrollTable'),
			'payStructureObj' => $this->getDefinedTable('Accounts\PaystructureTable'),
			'ptObj' => $this->getDefinedTable('Hr\PositiontitleTable'),
			'orgObj' => $this->getDefinedTable('Hr\OrganisationTable'),

		));
	}
	
	/**
	 * define pay payroll for a particular month
	 */
	public function definepayAction(){
		$this->init();
		if(isset($this->_id) & $this->_id!=0):
			$payheads = explode('-', $this->_id);
		endif;
		if(sizeof($payheads)==0):
			$payheads = array('1'); //default selection
		endif;
		return new ViewModel(array(
			'title' => 'Define Pay Detail',
			'employee_details'=> $this->getDefinedTable('Accounts\EmployeeDetailsTable')->get(array('e.status'=>'1')),
			'payheads'=> $payheads,
			'payheadObj' => $this->getDefinedTable('Accounts\PayheadTable'),
			'paystructureObj' => $this->getDefinedTable('Accounts\PaystructureTable'),
		));
	}
	
	/*
	 * Edit/update payroll -- basically temporary payroll
	 */
	public function editpayrollAction()
	{
		$this->init();	
		if($this->getRequest()->isPost()){
			$form = $this->getRequest()->getPost();	
			$employee_details = $this->getDefinedTable('Accounts\TempPayrollTable')->getColumn($this->_id,'employee_details');
			$total_earning = 0;
			$total_deduction = 0;
			$total_actual_earning = 0;
			$total_actual_deduction = 0;
			$e_dlwp = 0;
			$d_dlwp = 0;
			foreach($this->getDefinedTable('Accounts\PaystructureTable')->get(array('employee_details' => $employee_details)) as $paydetails):		
				if($paydetails['deduction'] == "1"){
					if($paydetails['dlwp']==1):
						$amount = $paydetails['amount'] - ($paydetails['amount']/$form['working_days']) * $form['leave_without_pay'];
					else:
						$amount = $paydetails['amount'];
					endif;
					$final_amt = $amount = $paydetails['amount'] - $amount;
					$final_amt = round($final_amt,2);
					$d_dlwp += $final_amt;
					if($paydetails['roundup']==1):
						$amount =round($amount);
					endif;
					$total_deduction = $total_deduction + $amount;
					$total_actual_deduction = $total_actual_deduction + $paydetails['amount'];
				}
				else
				{
					if($paydetails['dlwp']==1):
						$amount = $paydetails['amount'] - ($paydetails['amount']/$form['working_days']) * $form['leave_without_pay'];
					else:
						$amount = $paydetails['amount'];
					endif;
					$final_amt = $amount = $paydetails['amount'] - $amount;
					$final_amt = round($final_amt,2);
					$e_dlwp += $final_amt;
					if($paydetails['roundup']==1):
						$amount =round($amount);
					endif;
					$total_earning = $total_earning + $amount;
					$total_actual_earning = $total_actual_earning + $paydetails['amount'];
				}
			endforeach;				
			$leave_encashment = $form['leave_encashment'];
			$bonus = $form['bonus'];
			$net_pay = $total_actual_earning + $leave_encashment + $bonus - $total_actual_deduction - $e_dlwp - $d_dlwp;
			//$earning_dlwp = $total_actual_earning - $total_earning;
			//$deduction_dlwp = $total_actual_deduction - $total_deduction;
			$earning_dlwp = $e_dlwp;
			$deduction_dlwp = $d_dlwp;
			$data = array(
				'id'	=> $this->_id,
				'year' => $form['year'],
				'month' => $form['month'],
				'working_days' => $form['working_days'],
				'leave_without_pay' => $form['leave_without_pay'],
				'gross' => $total_actual_earning,
				'total_deduction' => $total_actual_deduction,
				'bonus' => $form['bonus'],
				'leave_encashment' => $leave_encashment,
				'net_pay' => $net_pay,
				'earning_dlwp' => $earning_dlwp,
				'deduction_dlwp' => $deduction_dlwp,
				'status' => '1', // initiated
				'author' =>$this->employee_details_id,
				'modified' =>$this->_modified,
			);
			$result = $this->getDefinedTable('Accounts\TempPayrollTable')->save($data);
			if($result > 0):
				$this->flashMessenger()->addMessage("success^ Payroll succesfully updated");
				//return $this->redirect()->toRoute('payroll', array('action'=>'payroll','id'=>$form['year'].'-'.$form['month']));
				return $this->redirect()->toRoute('payroll', array('action'=>'editpayroll', 'id'=>$this->_id));
			else:
				$this->flashMessenger()->addMessage("error^ Failed to update Payroll");
				return $this->redirect()->toRoute('payroll', array('action'=>'editpayroll', 'id'=>$this->_id));
			endif;
		}	
		return new ViewModel(array(
			'title' => 'Edit Pay roll',
			'payroll' => $this->getDefinedTable('Accounts\TempPayrollTable')->get(array('pr.id'=>$this->_id,'pr.organisation_id'=>$this->organisation_id)),
			'tempPrObj' => $this->getDefinedTable('Accounts\TempPayrollTable'),
			'paystructureObj' => $this->getDefinedTable('Accounts\PaystructureTable'),
			'employeeObj' => $this->getDefinedTable('Hr\EmployeeDetailsTable'),
			'empljpObj' => $this->getDefinedTable('Hr\JobProfileTable'),
			'deptutObj' => $this->getDefinedTable('Hr\DepartmentUnitTable'),
			'ptObj' => $this->getDefinedTable('Hr\PositiontitleTable'),
			'plObj' => $this->getDefinedTable('Hr\PositionlevelTable'),
			'organisation_id' =>$this->organisation_id,
			'positiontObj' => $this->getDefinedTable('Hr\PositiontitleTable'),
		));
	}
	
	/**
	 * define pay :edit paydetail
	 */
	public function editpaydetailAction(){
		$this->init();
		list($employee_details, $payhead, $payheads) = explode('&', $this->_id);
		if($this->getRequest()->isPost()):
			$form=$this->getRequest()->getPost();	
			$roundup = $this->getDefinedTable('Accounts\PayheadTable')->getColumn($payhead, 'roundup');
			if($roundup == 1):
				$form['amount'] = round($form['amount']);
			endif;		
			if($form['id'] > 0):
				$data = array(
					'id' => $form['id'],
					'pay_head' => $payhead,
					'percent' => $form['percent'],
					'amount' => $form['amount'],
					'dlwp' => $form['dlwp'],
					'ref_no' => $form['ref_no'],
					'remarks' => $form['remarks'],
					'modified' =>$this->_modified,	
				);
			else:
				$data = array(
						'employee_details' => $employee_details,
						'pay_head' => $payhead,
						'percent' => $form['percent'],
						'amount' => $form['amount'],
						'dlwp' => $form['dlwp'],
						'ref_no' => $form['ref_no'],
						'remarks' => $form['remarks'],
						'author' =>$this->_author,
						'created' =>$this->_created,
						'modified' =>$this->_modified,
				);
			endif;
			$data = $this->_safedataObj->rteSafe($data);
			$this->_connection->beginTransaction();//****Transaction begins here ***//
			$result = $this->getDefinedTable('Accounts\PaystructureTable')->save($data);
			if($result > 0):
				//changes in paystructure should affect other payheads and temporary payroll
				foreach($this->getDefinedTable('Accounts\PaystructureTable')->get($result) as $row);				
				$result1 = $this->calculatePayheadAmount($row);
				if($result1 > 0):
					$this->_connection->commit(); // commit transaction on success
					$this->flashMessenger()->addMessage("success^ Pay Detail successfully Updated");	
				else:
					$this->_connection->rollback(); // rollback transaction over failure
					$this->flashMessenger()->addMessage("error^ Failed to Update");
				endif;
			else:
				$this->_connection->rollback(); // rollback transaction over failure
				$this->flashMessenger()->addMessage("error^ Failed to Update");
			endif;
			return $this->redirect()->toRoute('payroll', array('action'=>'definepay','id'=>$payheads));		
		else:
			$ViewModel = new ViewModel(array(
					'title' => 'Define Payhead',
					'head_type' => $this->getDefinedTable('Accounts\PayheadTable')->getColumn($payhead, 'type'),
					'paystructure' => $this->getDefinedTable('Accounts\PaystructureTable')->get(array('employee_details'=>$employee_details, 'sd.pay_head'=> $payhead,'organisation_id'=>$userorg)),
					'pay_head_id' => $payhead,
					'get_id' => $this->_id,
					'paystructureObj' => $this->getDefinedTable('Accounts\PaystructureTable'),
					'payheadObj' => $this->getDefinedTable('Accounts\PayheadTable'),
					'paygroupObj' => $this->getDefinedTable('Accounts\PaygroupTable'),
					'temppayrollObj' => $this->getDefinedTable('Accounts\TempPayrollTable')
			));
			$ViewModel->setTerminal(True);
			return $ViewModel;
		endif;
	}
	
	/**
	 * define pay :delete paydetail
	 */
	public function deletepaydetailAction(){
		$this->init();
		list($employee, $payhead, $payheads) = explode('-', $this->_id);
		foreach($this->getDefinedTable('Accounts\PaystructureTable')->get(array('sd.employee'=>$employee, 'sd.pay_head'=>$payhead)) as $row);
		$this->_connection->beginTransaction(); //***Transaction begins here***//
		$result = $this->getDefinedTable('Accounts\PaystructureTable')->remove($row['id']);
		if($result > 0):
			//changes in paystructure should affect other payheads
			$result1 = $this->calculatePayheadAmount($row);
			if($result1 > 0):
				$this->_connection->commit(); // commit transaction on success
				$this->flashMessenger()->addMessage("success^ Paydetail deleted successfully");
			else:
				$this->_connection->rollback(); // rollback transaction over failure
				$this->flashMessenger()->addMessage("error^ Failed to delete Paydetail");
			endif;
			//end			
		else:
			$this->_connection->rollback(); // rollback transaction over failure
			$this->flashMessenger()->addMessage("error^ Failed to delete Paydetail");
		endif;
		$redirectUrl = $this->getRequest()->getHeader('Referer')->getUri();	
		return $this->redirect()->toUrl($redirectUrl);
	}
	
	/**
	 * define pay :delteAll/resetAll paydetail
	 */
	public function deleteallpaydetailAction(){
		$this->init();
		list($payhead, $payheads) = explode('-', $this->_id);
		$this->_connection->beginTransaction(); //***Transaction begins here***//
		foreach($this->getDefinedTable('Accounts\PaystructureTable')->get(array('sd.pay_head'=>$payhead)) as $row):
			$result = $this->getDefinedTable('Accounts\PaystructureTable')->remove($row['id']);
			if($result > 0):
				//changes in paystructure should affect other payheads
				$result1 = $this->calculatePayheadAmount($row);
				if($result1 <= 0):
					break;
				endif;
				//end			
			else:
				$result1 =0;
				break;
			endif;
		endforeach;
		if($result1 > 0):
			$this->_connection->commit(); // commit transaction on success
			$this->flashMessenger()->addMessage("success^ Paydetail deleted successfully");
		else:
			$this->_connection->rollback(); // rollback transaction over failure
			$this->flashMessenger()->addMessage("error^ Failed to delete Paydetail");
		endif;
		$redirectUrl = $this->getRequest()->getHeader('Referer')->getUri();	
		return $this->redirect()->toUrl($redirectUrl);
	}
	/*
	 * Add earning and deduction to paystructure
	 */
	public function addAction()
	{
		$this->init();
		if($this->getRequest()->isPost()){
			$form = $this->getRequest()->getPost();	
			$roundup = $this->getDefinedTable('Accounts\PayheadTable')->getColumn($form['pay_head'], 'roundup');
			if($roundup == 1):
				$form['amount'] = round($form['amount']);
			endif;	
            $dlwp = ($form['dlwp']> 0)?$form['dlwp']:'0';			
			$data = array(
				'employee_details' => $this->_id,
				'pay_head' => $form['pay_head'],
				'percent' => $form['percent'],
				'amount' => $form['amount'],
				'dlwp' => $dlwp,
				'ref_no' => $form['ref_no'],
				'organisation_id' => $this->organisation_id,
				'remarks' => $form['remarks'],
				'remarks' => $form['remarks'],
				'author' =>$this->employee_details_ids,
				'created' =>$this->_created,
				'modified' =>$this->_modified,
			);
			$result = $this->getDefinedTable('Accounts\PaystructureTable')->save($data);			
			if($result > 0):
				//changes in paystructure should affect other payheads
				foreach($this->getDefinedTable('Accounts\PaystructureTable')->get($result) as $row);				
				$result1 = $this->calculatePayheadAmount($row);
				if($result1 > 0):
					$this->flashMessenger()->addMessage("success^ New Pay head successfully added to Pay Structure");
				else:
					$this->flashMessenger()->addMessage("error^ Failed to add new pay head");
				endif;
				//end
			else:
				$this->flashMessenger()->addMessage("error^ Failed to add new pay head");
			endif;
			$redirectUrl = $this->getRequest()->getHeader('Referer')->getUri();	
			return $this->redirect()->toUrl($redirectUrl);
			//return $this->redirect()->toRoute('payroll', array('action'=>'paystructure', 'id' => $this->_id));
		}
		return  new ViewModel(array(
			'organisation_id' => $this->organisation_id,
			'employee_details' => $this->_id,
			'payheadObj' => $this->getDefinedTable('Accounts\PayheadTable'),
			'paystructureObj' => $this->getDefinedTable('Accounts\PaystructureTable'),
			'employeepfs' => $this->getDefinedTable('Hr\JobProfileTable')->get(array('employee_details'=>$this->employee_details_id)),
			'positiontObj' => $this->getDefinedTable('Hr\PositiontitleTable'),
		));
	}

	/*
	 * Edit earning & dediction to paystructure
	* */
	public function editAction()
	{
		$this->init();
		if($this->getRequest()->isPost()){
			$form = $this->getRequest()->getPost();
			$roundup = $this->getDefinedTable('Accounts\PayheadTable')->getColumn($form['pay_head'], 'roundup');
			if($roundup == 1):
				$form['amount'] = round($form['amount']);
			endif;
			$data = array(
					'id' => $this->_id,
					'pay_head' => $form['pay_head'],
					'percent' => $form['percent'],
					'amount' => $form['amount'],
					'dlwp' => $form['dlwp'],
					'ref_no' => $form['ref_no'],
					'organisation_id' => $this->organisation_id,
					'remarks' => $form['remarks'],
					'modified' =>$this->_modified,
			);
			$result = $this->getDefinedTable('Accounts\PaystructureTable')->save($data);
			if($result > 0):			
				//changes in paystructure should affect other payheads
				foreach($this->getDefinedTable('Accounts\PaystructureTable')->get($this->_id) as $row);				
				$result1 = $this->calculatePayheadAmount($row);
				if($result1 > 0):
					$this->flashMessenger()->addMessage("success^ Pay Structure successfully Updated");
				else:
					$this->flashMessenger()->addMessage("error^ Failed to Update pay detail in Paystructure");
				endif;
				//end
			else:
				$this->flashMessenger()->addMessage("error^ Failed to Update pay detail in Paystructure");
			endif;
			$redirectUrl = $this->getRequest()->getHeader('Referer')->getUri();	
			return $this->redirect()->toUrl($redirectUrl);
			//return $this->redirect()->toRoute('payroll', array('action'=>'paystructure', 'id' => $employee_id));			
		}
		return new ViewModel(array(
			'title' => 'Edit Earning/Deduction',
			'organisation_id' => $this->organisation_id,
			'paystructure' => $this->getDefinedTable('Accounts\PaystructureTable')->get($this->_id),
			'paystructureObj' => $this->getDefinedTable('Accounts\PaystructureTable'),
			'payheadObj' => $this->getDefinedTable('Accounts\PayheadTable'),
			'paygroupObj' => $this->getDefinedTable('Accounts\PaygroupTable'),
			'temppayrollObj' => $this->getDefinedTable('Accounts\TempPayrollTable'),
			'empljpObj' => $this->getDefinedTable('Hr\JobProfileTable'),
			'PSObj' => $this->getDefinedTable('Hr\PayScaleTable'),
			'positionlevelObj' => $this->getDefinedTable('Hr\PositionlevelTable'),
			'employeepfs' => $this->getDefinedTable('Hr\JobProfileTable')->get(array('employee_details'=>$this->employee_details_id)),
			'positiontObj' => $this->getDefinedTable('Hr\PositiontitleTable'),
		));
	}

	/**
	 * action to delete pay head from paystructure
	 */
	public function deleteAction()
	{
		$this->init();
		$employee_details = $this->getDefinedTable('Accounts\PaystructureTable')->getColumn(array('id'=>$this->_id,'organisation_id'=>$this->_user-organisation_id),'employee_details');
		foreach($this->getDefinedTable('Accounts\PaystructureTable')->get(array('sd.id'=>$this->_id,'sd.organisation_id'=>$this->_user->organisation_id)) as $row);
		$this->_connection->beginTransaction(); //***Transaction begins here***//
		$result = $this->getDefinedTable('Accounts\PaystructureTable')->remove($this->_id);
		if($result > 0):
			//changes in paystructure should affect other payheads
			$result1 = $this->calculatePayheadAmount($row);
			if($result1 > 0):
				$this->_connection->commit(); // commit transaction on success
				$this->flashMessenger()->addMessage("success^ Payhead deleted successfully");
			else:
				$this->_connection->rollback(); // rollback transaction over failure
				$this->flashMessenger()->addMessage("error^ Failed to delete Payhead");
			endif;
			//end			
		else:
			$this->_connection->rollback(); // rollback transaction over failure
			$this->flashMessenger()->addMessage("error^ Failed to delete Payhead");
		endif;
		$redirectUrl = $this->getRequest()->getHeader('Referer')->getUri();	
		return $this->redirect()->toUrl($redirectUrl);
	}
	/*
	 * Action for add earnings and deductions to temp payroll
	 */
	public function addprAction()
	{
		$this->init();
		if($this->getRequest()->isPost()){
			$form = $this->getRequest()->getPost();	
			$roundup = $this->getDefinedTable('Accounts\PayheadTable')->getColumn($form['pay_head'], 'roundup');
			if($roundup == 1):
				$form['amount'] = round($form['amount']);
			endif;			
			$data = array(
					'employee_details' => $this->_id,
					'pay_head' => $form['pay_head'],
					'percent' => $form['percent'],
					'amount' => $form['amount'],
					'dlwp' => ($form['dlwp']> 0)?$form['dlwp']:'0',
					'organisation_id' => $this->organisation_id,
					'ref_no' => $form['ref_no'],
					'remarks' => $form['remarks'],
					'author' =>$this->employee_details_id,
					'created' =>$this->_created,
					'modified' =>$this->_modified,
			);
			$result = $this->getDefinedTable('Accounts\PaystructureTable')->save($data);				
			if($result > 0):
				//changes in paystructure should affect other payheads
				foreach($this->getDefinedTable('Accounts\PaystructureTable')->get(array('sd.id'=>$this->_id,'sd.organisation_id'=>$this->_user->organisation_id)) as $row);				
				$result1 = $this->calculatePayheadAmount($row);
				if($result1 > 0):
					$this->flashMessenger()->addMessage("success^ New Pay head successfully added");	
				else:
					$this->flashMessenger()->addMessage("error^ Failed to add new pay head");
				endif;
				//end
			else:
				$this->flashMessenger()->addMessage("error^ Failed to add new pay head");
			endif;
			$redirectUrl = $this->getRequest()->getHeader('Referer')->getUri();	
			return $this->redirect()->toUrl($redirectUrl);
		}
		return new ViewModel(array(
			'organisation_id' => $this->organisation_id,
			'employee_details' => $this->_id,
			'payheadObj' => $this->getDefinedTable('Accounts\PayheadTable'),
			'paystructureObj' => $this->getDefinedTable('Accounts\PaystructureTable'),
			'employeepfs' => $this->getDefinedTable('Hr\JobProfileTable')->get(array('employee_details'=>$this->employee_details_id)),
			'positiontObj' => $this->getDefinedTable('Hr\PositiontitleTable'),
		));
	}

	/*
	 * Edit earning & dediction to temp payroll
	* */
	public function editprAction()
	{
		$this->init();
		if($this->getRequest()->isPost()){
			$form = $this->getRequest()->getPost();
			$roundup = $this->getDefinedTable('Accounts\PayheadTable')->getColumn($form['pay_head'], 'roundup');
			if($roundup == 1):
				$form['amount'] = round($form['amount']);
			endif;
			$data = array(
					'id' => $this->_id,
					'pay_head' => $form['pay_head'],
					'percent' => $form['percent'],
					'amount' => $form['amount'],
					'dlwp' => ($form['dlwp'] > 0)?$form['dlwp']:'0',
					'ref_no' => $form['ref_no'],
					'organisation_id' => $this->organisation_id,
					'remarks' => $form['remarks'],
					'modified' =>$this->_modified,
			);
			$result = $this->getDefinedTable('Accounts\PaystructureTable')->save($data);
			if($result > 0):
				//changes in paystructure should affect other payheads and temporary payroll
				foreach($this->getDefinedTable('Accounts\PaystructureTable')->get(array('sd.id'=>$this->_id,'sd.organisation_id'=>$this->_user->organisation_id)) as $row);				
				$result1 = $this->calculatePayheadAmount($row);	
				if($result1 > 0):
					$this->flashMessenger()->addMessage("success^ Pay detail successfully Updated");
				else:
					$this->flashMessenger()->addMessage("error^ Failed to Update pay detail");
				endif;
				//end
			else:
				$this->flashMessenger()->addMessage("error^ Failed to Update pay detail");
			endif;	
			$redirectUrl = $this->getRequest()->getHeader('Referer')->getUri();	
			return $this->redirect()->toUrl($redirectUrl);
		}
		return new ViewModel(array(
			'title' => 'Edit Earning/Deduction',
			'organisation_id' => $this->_userorg,
			'paystructure' => $this->getDefinedTable('Accounts\PaystructureTable')->get($this->_id),
			'paystructureObj' => $this->getDefinedTable('Accounts\PaystructureTable'),
			'payheadObj' => $this->getDefinedTable('Accounts\PayheadTable'),
			'paygroupObj' => $this->getDefinedTable('Accounts\PaygroupTable'),
			'temppayrollObj' => $this->getDefinedTable('Accounts\TempPayrollTable'),
			'empljpObj' => $this->getDefinedTable('Hr\JobProfileTable'),
			'PSObj' => $this->getDefinedTable('Hr\PayScaleTable'),
			'positionlevelObj' => $this->getDefinedTable('Hr\PositionlevelTable'),
			'employeepfs' => $this->getDefinedTable('Hr\JobProfileTable')->get(array('employee_details'=>$this->employee_details_id)),
			'positiontObj' => $this->getDefinedTable('Hr\PositiontitleTable'),
		));
	}

	/**
	 * action to delete
	 */
	public function deleteprAction()
	{
		$this->init();
		$employee_details = $this->getDefinedTable('Accounts\PaystructureTable')->getColumn(array('id'=>$this->_id,'organisation_id'=>$this->_user-organisation_id),'employee_details');
		foreach($this->getDefinedTable('Accounts\PaystructureTable')->get(array('sd.id'=>$this->_id,'sd.organisation_id'=>$this->_user->organisation_id)) as $row);
		$result = $this->getDefinedTable('Accounts\PaystructureTable')->remove($this->_id);
		if($result > 0):
			//changes in paystructure should affect other payheads
			$result1 = $this->calculatePayheadAmount($row);
			if($result1 > 0):
				$this->flashMessenger()->addMessage("success^ Payhead deleted successfully");
			else:
				$this->flashMessenger()->addMessage("error^ Failed to delete Payhead");
			endif;
			//end			
		else:
			$this->flashMessenger()->addMessage("error^ Failed to delete Payhead");
		endif;
		$redirectUrl = $this->getRequest()->getHeader('Referer')->getUri();	
		return $this->redirect()->toUrl($redirectUrl);
	}
	/*
	 * Ajax response action to get payslab type
	 * actual amount(value), percent, slab
	* */

	public function getslabtypeAction()
	{
		$this->init();
		if($this->getRequest()->isPost()):
			$request = $this->getRequest()->getPost();	
			$ViewModel = new ViewModel(array(
				'employee_details' => $request['employee_details'],
				'organisation_id' => $request['organisation_id'],
				'pay_head' => $request['pay_head'],
				'payheadObj' => $this->getDefinedTable('Accounts\PayheadTable'),
				'paystructureObj' => $this->getDefinedTable('Accounts\PaystructureTable'),
				'tempPayrollObj' => $this->getDefinedTable('Accounts\TempPayrollTable'),
				'payslabTable' => $this->getDefinedTable('Accounts\PaySlabTable'),
				'paygroupObj' => $this->getDefinedTable('Accounts\PaygroupTable'),
				'empljpObj' => $this->getDefinedTable('Hr\JobProfileTable'),
				'PSObj' => $this->getDefinedTable('Hr\PayScaleTable'),
				'positionlevelObj' => $this->getDefinedTable('Hr\PositionlevelTable'),
			));
			$ViewModel->setTerminal(True);
			return $ViewModel;
		endif;
		exit;
	}
	
	/*
	 * Submit payroll to the accounts section
	* */
	public function submitpayrollAction()
	{
		$this->init();
		$month = date('m');
		$year = date('Y');
		$userorg = $this->organisation_id;
		foreach($this->getDefinedTable('Accounts\TempPayrollTable')->get(array('pr.organisation_id' => $userorg,'year' => $year,'month' => $month)) as $temp_payroll):
			$payroll_data = array(
				'employee_details' => $temp_payroll['employee_details'],
				'empl_payroll' => $temp_payroll['empl_payroll'],
				'organisation_id' => $temp_payroll['organisation_id'],
				'year' => $temp_payroll['year'],
				'month' => $temp_payroll['month'],
				'working_days' => $temp_payroll['working_days'],
				'leave_without_pay' => $temp_payroll['leave_without_pay'],
				'gross' => $temp_payroll['gross'],
				'total_deduction' => $temp_payroll['total_deduction'],
				'bonus' => $temp_payroll['bonus'],
				'leave_encashment' => $temp_payroll['leave_encashment'],
				'net_pay' => $temp_payroll['net_pay'],
				'earning_dlwp' => $temp_payroll['earning_dlwp'],
				'deduction_dlwp' => $temp_payroll['deduction_dlwp'],
				'status' => '1', // initiated
				'author' =>$this->employee_details_id,
				'created' =>$this->_created,
				'modified' =>$this->_modified,
			);
			$result = $this->getDefinedTable('Accounts\PayrollTable')->save($payroll_data);
			if($result > 0):
				foreach($this->getDefinedTable('Accounts\PaystructureTable')->get(array('employee_details'=> $temp_payroll['employee_details'],'organisation_id'=>$userorg)) as $pay_detail):
					$default_amt = $pay_detail['amount'];
					if($pay_detail['dlwp'] == 1):
						$working_days = $temp_payroll['working_days'];
						$leave_without_pay = $temp_payroll['leave_without_pay'];
						$amt = ($default_amt / $working_days)*$leave_without_pay;
						$final_amt = $default_amt - $amt;
					else:
						$final_amt = $default_amt;
					endif;
					if($pay_detail['roundup'] == 1):
						$final_amt = round($final_amt);
					endif;
					$paydetail_data = array(
						'pay_roll' => $result,
						'pay_head' => $pay_detail['pay_head_id'],
						'amount' => $final_amt,
						'actual_amount' => $default_amt,
						'ref_no' => $pay_detail['ref_no'],
						'organisation_id' => $temp_payroll['organisation_id'],
						'remarks' => $pay_detail['remarks'],
						'author' => $this->employee_details_id,
						'created' => $this->_created,
						'modified' =>$this->_modified,
					);
					$result1 = $this->getDefinedTable('Accounts\PaydetailTable')->save($paydetail_data);
					if($result1 <= 0):
						break;
					endif;
				endforeach;
				if($result1 <= 0):
					break;
				endif;
			else:
				break;
			endif;
		endforeach; 
		if($result1 > 0 && $result > 0):
			$this->flashMessenger()->addMessage("success^ Payroll successfully submitted");	
		else:
			$this->flashMessenger()->addMessage("error^ Failed while submitting payroll, Try again after some time");	
			return $this->redirect()->toRoute('payroll', array('action'=>'definepayroll'));
		endif;
		return $this->redirect()->toRoute('payroll', array('action'=>'index'));
	}
	
	/**
	 * payslip Action
	 */
	public function payslipAction()
	{
		$this->init();
		if($this->getRequest()->isPost()):
			$request = $this->getRequest()->getPost();
			$year = $request['year'];
			$month = $request['month'];
			$organisation = $request['organisation'];
			$employee_details = $request['employee_details'];
		else:
			$employee_details = '';//set default employee to -1 meaning all employee
			$organisation = $this->organisation_id;//set default organisation to -1 meaning all employee
			$month =  date('m');
			$year = date('Y');
		endif;
		return new ViewModel(array(
			'title' => 'Salary Slip',
			'year' => $year,
			'month' => $month,
			'userorg' => $this->organisation_id,
			'organisationObj' => $this->getDefinedTable('Hr\OrganisationTable'),
			'organisation_id' => $organisation,
			'employee_details' => $employee_details,
			'employeeObj' => $this->getDefinedTable('Hr\EmployeeDetailsTable'),
			'peObj' => $this->getDefinedTable('Hr\JobProfileTable'),
			'payheadObj' => $this->getDefinedTable('Accounts\PayheadTable'),
			'payrollObj' => $this->getDefinedTable('Accounts\PayrollTable'),
			'paydetailObj' => $this->getDefinedTable('Accounts\PaydetailTable'),
			'ptObj' => $this->getDefinedTable('Hr\PositiontitleTable'),
			'plObj' => $this->getDefinedTable('Hr\PositionlevelTable'),
			'deptObj' => $this->getDefinedTable('Hr\DepartmentTable'),
			'pemplObj' => $this->getDefinedTable('Hr\JobProfileTable'),
		));
	}
	
	/**
	 * Monthly salary booking in transaction
	 */
	public function booksalaryAction()
	{
		$this->init();
		if($this->getRequest()->isPost()):
			$form = $this->getRequest()->getPost();
			// booking to transaction
			if(isset($form['voucher_date']) && isset($form['voucher_amount'])):
				//generate voucher no
				$ocode = $this->getDefinedTable("Hr\OrganisationTable")->getcolumn($this->organisation_id,'organisation_code');
				$prefix = $this->getDefinedTable("Accounts\JournalTable")->getcolumn($form['voucher_type'],'prefix');
				$date = date('ym',strtotime($form['voucher_date']));
				$tmp_VCNo = $ocode.$prefix.$date;
				
				$results = $this->getDefinedTable("Accounts\TransactionTable")->getSerial($tmp_VCNo);
				
				$pltp_no_list = array();
				foreach($results as $result):
					array_push($pltp_no_list, substr($result['voucher_no'], 8));
				endforeach;
				$next_serial = max($pltp_no_list) + 1;
				//echo $next_serial; exit;
				switch(strlen($next_serial)){
					case 1: $next_dc_serial = "000".$next_serial; break;
					case 2: $next_dc_serial = "00".$next_serial;  break;
					case 3: $next_dc_serial = "0".$next_serial;   break;
					default: $next_dc_serial = $next_serial;       break;
				}	
				$voucher_no = $tmp_VCNo.$next_dc_serial;
				$data1 = array(
						'voucher_date' => $form['voucher_date'],
						'voucher_type' => $form['voucher_type'],
						'voucher_no' => $voucher_no,
						'voucher_amount' => str_replace( ",", "",$form['voucher_amount']),
						'remark' => $form['remark'],
						'organisation_id' => $this->organisation_id,
						'status' => '3',
						'author' =>$this->employee_details_id,
						'created' =>$this->_created,
						'modified' =>$this->_modified,
				);
				$result = $this->getDefinedTable('Accounts\TransactionTable')->save($data1);
				if($result > 0):
					//insert into salarybooking table
					$sb_data = array(
							'transaction' => $result,
							'year' => $form['year'],
							'month' => $form['month'],
							'salary_advance' => '1',
							'organisation_id' =>$this->organisation_id,
							'author' =>$this->employee_details_id,
							'created' =>$this->_created,
							'modified' =>$this->_modified,
					);
					$result1 = $this->getDefinedTable('Accounts\SalarybookingTable')->save($sb_data);
					if($result1 > 0):
						//insert into transactiondetail table from payroll table
						$data = array(
							'year' => $form['year'],
							'month' => $form['month'],
							'pr.organisation_id' =>$this->organisation_id,
						);			
						$organisations = $this->getDefinedTable('Accounts\PayrollTable')->salaryBookingorganisation($data);
						foreach($organisations as $org_row):
							$sh_data = array(
								'year' => $data['year'],
								'month' => $data['month'],
								'organisation' => $org_row['organisation_id'],
								'deduction' => '0',
							);
							$master_dtls = $this->getDefinedTable('Accounts\PayrollTable')->salaryBookingMasterDtls($sh_data);
							foreach($master_dtls as $master_dtls_row):
								$filter = array(
									'year' => $sh_data['year'],
									'month' => $sh_data['month'],
									'organisation' => $sh_data['organisation'],
									'masterdtlsID' => $master_dtls_row['ref_id'],
									'department' => '-1',													
								);
								$amt = $this->getDefinedTable('Accounts\PaydetailTable')->getAmtforSummary($filter);
								
								if((int)$amt > 0):
									if($master_dtls_row['deduction'] == 1):
										$credit_amt = $amt;
										$debit_amt = '0.00';
									else:
										$credit_amt = '0.00';
										$debit_amt = $amt;
									endif;
									$tdtlsdata = array(
										'transaction' => $result,
										'organisation_id' => $org_row['organisation_id'],
										'head' => $master_dtls_row['head_id'],
										'sub_head' => $master_dtls_row['sub_head_id'],
										'master_details' => $master_dtls_row['id'],
										'bank_ref_type' => '',
										'debit' => $debit_amt,
										'credit' => $credit_amt,
										'ref_no'=> '',
										'type' => '2',//system generated data
										'author' =>$this->employee_details_id,
										'created' =>$this->_created,
										'modified' =>$this->_modified,
									);
									$result2 = $this->getDefinedTable('Accounts\TransactiondetailTable')->save($tdtlsdata);
									if($result2 <= 0):
										break;
									endif;
								endif;
							endforeach;
							if($result2 <= 0):
								break;
							endif;
						endforeach;
						
						$sh_data2 = array(
							'year' => $data['year'],
							'month' => $data['month'],
							'organisation' => $this->organisation_id,
							'deduction' => '1',
						);
						$mastdtls2 = $this->getDefinedTable('Accounts\PayrollTable')->salaryBookingMasterDtls($sh_data2);
						foreach($mastdtls2 as $mastdtls_row2):
							$filter2 = array(
								'year' => $sh_data2['year'],
								'month' => $sh_data2['month'],
								'organisation' => $this->organisation_id,
								'masterdtlsID' => $mastdtls_row2['ref_id'],
								'department' => '-1',													
							);
							$amt2 = $this->getDefinedTable('Accounts\PaydetailTable')->getAmtforSummary($filter2);
							
							if((int)$amt2 > 0):
								if($mastdtls_row2['deduction'] == 1):
									$credit_amt2 = $amt2;
									$debit_amt2 = '0.00';
								else:
									$credit_amt2 = '0.00';
									$debit_amt2 = $amt2;
								endif;
								$tdtlsdata2 = array(
										'transaction' => $result,
										'organisation_id' => $this->organisation_id,
										'head' => $mastdtls_row2['head_id'],
										'sub_head' => $mastdtls_row2['sub_head_id'],
										'master_details' => $mastdtls_row2['id'],
										'bank_ref_type' => '',
										'debit' => $debit_amt2,
										'credit' => $credit_amt2,
										'ref_no'=> '',
										'type' => '2',//system generated data
										'author' =>$this->employee_details_id,
										'created' =>$this->_created,
										'modified' =>$this->_modified,
								);
								$result4 = $this->getDefinedTable('Accounts\TransactiondetailTable')->save($tdtlsdata2);
								if($result4 <= 0):
									break;
								endif;
							endif;
						endforeach;
						if($result2 > 0 && $result4 >0):
							//insert into transactiondetail table from form
							$sub_head= $form['sub_head'];
							$debit= $form['debit'];
							$credit= $form['credit'];
							for($i=0; $i < sizeof($sub_head); $i++):
								if(isset($sub_head[$i]) && is_numeric($sub_head[$i])):
									$tdetailsdata = array(
										'transaction' => $result,
										'organisation_id' => $this->organisation_id,
										'head'            => $this->getDefinedTable('Accounts\SubheadTable')->getColumn($this->getDefinedTable('Accounts\MasterDetailsTable')->getColumn($sub_head[$i], $column='sub_head'), $column='head'),
										'sub_head'        => $this->getDefinedTable('Accounts\MasterDetailsTable')->getColumn($sub_head[$i], $column='sub_head'),
										'master_details'  => $sub_head[$i],
										'bank_ref_type' => '',
										'debit' => (isset($debit[$i]))? $debit[$i]:'0.00',
										'credit' => (isset($credit[$i]))? $credit[$i]:'0.00',
										'ref_no'=> '',
										'type' => '1',//user inputted  data
										'author' =>$this->employee_details_id,
										'created' =>$this->_created,
										'modified' =>$this->_modified,
									);
									$result3 = $this->getDefinedTable('Accounts\TransactiondetailTable')->save($tdetailsdata);
									if($result3 <= 0):
										break;
									endif;
								endif;
							endfor;
							if($result3 > 0):
								$this->flashMessenger()->addMessage("success^ New Transaction successfully added | ".$voucher_no);
								return $this->redirect()->toRoute('transaction', array('action' =>'viewtransaction', 'id' => $result));
							else:
								$this->flashMessenger()->addMessage("error^ Failed to book salary to transaction");
							endif;
						else:
							$this->flashMessenger()->addMessage("error^ Failed to book salary to transaction");
						endif;
					else:
						$this->flashMessenger()->addMessage("error^ Failed to book salary to transaction");
					endif;
				else:
					$this->flashMessenger()->addMessage("success^ New Transaction successfully added | ".$voucher_no);
					return $this->redirect()->toRoute('transaction', array('action' =>'viewtransaction', 'id' => $result));
				endif;
				return $this->redirect()->toRoute('payroll', array('action'=>'payroll', 'id'=> $form['year'].'-'.$form['month']));
			else:
				if(isset($form['year']) && isset($form['month'])):
					//check if all the payheads have subheads for booking
					$payheads = $this->getDefinedTable('Accounts\PayheadTable')->getNotIn();
					$data = array(
							'year' => $form['year'],
							'month' => $form['month'],
							'pr.organisation_id' => $this->organisation_id,
					);			
					$organisations = $this->getDefinedTable('Accounts\PayrollTable')->salaryBookingOrganisation($data);
					$user_org  = $this->organisation_id;
                    $date = date('y-m-d');					
					$bank_subledgers = $this->getDefinedTable("Accounts\MasterDetailsTable")->getBSubledger(array('type'=>array('2')),$this->organisation_id);
					foreach($bank_subledgers as $bank_subledger);
					$bank_balance = $this->getDefinedTable("Accounts\TransactiondetailTable")->getBankBalance($date,$bank_subledger['subhead_id'],$this->organisation_id);					
					return new ViewModel(array(
						'title'  => 'Salary Booking',
						'data' => $data,
						'orgObj' => $this->getDefinedTable('Hr\OrganisationTable'),
						'journals' => $this->getDefinedTable('Accounts\JournalTable')->getAll(),
						'materdtlsObj' => $this->getDefinedTable('Accounts\MasterDetailsTable'),
						'heads' => $this->getDefinedTable('Accounts\HeadTable')->getAll(),
						'organisations' => $organisations,
						'payrollObj' => $this->getDefinedTable('Accounts\PayrollTable'),
						'paydetailObj' => $this->getDefinedTable('Accounts\PaydetailTable'),
						'bankdetls' => $this->getDefinedTable('Accounts\BankAccountTable')->get(array('organisation_id'=>$user_org)),
						'payheads' => $payheads,
						'user_org' => $user_org,
						'bank_balance' =>$bank_balance,
					));				
				endif;
			endif;
		endif;
		$this->flashMessenger()->addMessage("error^ Failed to book salary to transaction");	
		return $this->redirect()->toRoute('payroll', array('action'=>'payroll', 'id'=> $form['year'].'-'.$form['month']));
	}
	/**
	 * Monthly salary booking in transaction
	 */
	public function bookadvancesalaryAction()
	{
		$this->init();
		
		list($year, $month) = explode('-', $this->_id);
		
		if($this->getRequest()->isPost()):
			$form = $this->getRequest()->getPost();
			// booking to transaction
			if(isset($form['voucher_date']) && isset($form['voucher_amount'])):
				//generate voucher no
				$organisation = $this->getDefinedTable("Hr\OrganisationTable")->getcolumn($this->organisation_id, 'organisation_code');
				$prefix = $this->getDefinedTable("Accounts\JournalTable")->getcolumn($form['voucher_type'],'prefix');
				$date = date('ym',strtotime($form['voucher_date']));
				$tmp_VCNo = $organisation.$prefix.$date;
				
				$results = $this->getDefinedTable("Accounts\TransactionTable")->getSerial($tmp_VCNo);
				
				$pltp_no_list = array();
				foreach($results as $result):
					array_push($pltp_no_list, substr($result['voucher_no'], 8));
				endforeach;
				$next_serial = max($pltp_no_list) + 1;
				switch(strlen($next_serial)){
					case 1: $next_dc_serial = "000".$next_serial; break;
					case 2: $next_dc_serial = "00".$next_serial;  break;
					case 3: $next_dc_serial = "0".$next_serial;   break;
					default: $next_dc_serial = $next_serial;       break;
				}	
				$voucher_no = $tmp_VCNo.$next_dc_serial;
				
				$data1 = array(
						'voucher_date' => $form['voucher_date'],
						'voucher_type' => $form['voucher_type'],
						'voucher_no' => $voucher_no,
						'organisation_id' => $this->organisation_id,
						'voucher_amount' => str_replace( ",", "",$form['voucher_amount']),
						'remark' => $form['remark'],
						'status' => '3',
						'author' =>$this->employee_details_id,
						'created' =>$this->_created,
						'modified' =>$this->_modified,
				);
				$result = $this->getDefinedTable('Accounts\TransactionTable')->save($data1);
				if($result > 0):
					//insert into salarybooking table
					$sb_data = array(
						'transaction' => $result,
						'organisation_id' => $this->organisation_id,
						'year' => $form['year'],
						'month' => $form['month'],
						'salary_advance' => '2',
						'author' =>$this->employee_details_id,
						'created' =>$this->_created,
					    'modified' =>$this->_modified,
					);
					//print_r($sb_data); exit;
					$result1 = $this->getDefinedTable('Accounts\SalarybookingTable')->save($sb_data);
					if($result1 > 0):
						//insert into transactiondetail table from payroll table
						$data = array(
						    'pr.organisation_id' => $this->organisation_id,
							'year' => $form['year'],
							'month' => $form['month'],
						);			
						$organisations = $this->getDefinedTable('Accounts\PayrollTable')->salaryBookingOrganisation($data);
						foreach($organisations as $org_row):
								$masterDtls_data = array(
									'year' => $data['year'],
									'month' => $data['month'],
									'organisation' => $org_row['organisation_id'],
								);
								//print_r($masterDtls_data); exit;
								$master_details = $this->getDefinedTable('Accounts\PayrollTable')->salaryAdvanceMasterDtls($masterDtls_data);
								foreach($master_details as $master_details_row):
									$payroll_id = $this->getDefinedTable('Accounts\PayrollTable')->getColumn(array('employee_details' =>$master_details_row['ref_id'],'year'=>$data['year'],'month'=>$data['month'],'organisation_id'=> $org_row['organisation_id']),'id'); 	
									$amt = $this->getDefinedTable('Accounts\PaydetailTable')->getColumn(array('pay_roll'=>$payroll_id,'pay_head'=>'16'),'amount');
									if((int)$amt > 0):
										$credit_amt = $amt;
										$debit_amt = '0.00';
										
										$tdtlsdata = array(
												'transaction' => $result,
												'organisation_id' => $this->organisation_id,
												'head' => $master_details_row['head_id'],
												'sub_head' => $master_details_row['sub_head_id'],
												'master_details' => $master_details_row['id'],
												'bank_ref_type' => '',
												'debit' => $debit_amt,
												'credit' => $credit_amt,
												'ref_no'=> '',
												'type' => '2',//system generated data
												'author' =>$this->employee_details_id,
												'created' =>$this->_created,
												'modified' =>$this->_modified,
										);
										//print_r($tdtlsdata); exit;
										$result2 = $this->getDefinedTable('Accounts\TransactiondetailTable')->save($tdtlsdata);
									endif;
							endforeach;
						endforeach;
						if($result2 > 0):
							//insert into transactiondetail table from form
							$sub_head= $form['sub_head'];
							$debit = $form['debit'];
							$credit= $form['credit'];
							for($i=0; $i < sizeof($sub_head); $i++):
								if(isset($sub_head[$i]) && is_numeric($sub_head[$i])):
									$tdetailsdata = array(
											'transaction' => $result,
											'organisation_id' => $this->organisation_id,
											'head'            => $this->getDefinedTable('Accounts\SubheadTable')->getColumn($this->getDefinedTable('Accounts\MasterDetailsTable')->getColumn($sub_head[$i], $column='sub_head'), $column='head'),
											'sub_head'        => $this->getDefinedTable('Accounts\MasterDetailsTable')->getColumn($sub_head[$i], $column='sub_head'),
											'master_details'  => $sub_head[$i],
											'bank_ref_type' => '',
											'debit' => (isset($debit[$i]))? $debit[$i]:'0.00',
											'credit' => (isset($credit[$i]))? $credit[$i]:'0.00',
											'ref_no'=> '',
											'type' => '1',//user inputted  data
						                    'author' =>$this->employee_details_id,
											'created' =>$this->_created,
											'modified' =>$this->_modified,
									);
									$result3 = $this->getDefinedTable('Accounts\TransactiondetailTable')->save($tdetailsdata);
								endif;
							endfor;
							if($result3 > 0):
								$this->flashMessenger()->addMessage("success^ New Transaction successfully added | ".$voucher_no);
								return $this->redirect()->toRoute('transaction', array('action' =>'viewtransaction', 'id' => $result));
							else:
								$this->flashMessenger()->addMessage("error^ Failed to book salary to transaction. Please transaction details");
							endif;
						else:
							$this->flashMessenger()->addMessage("error^ Failed to book advance salary to transaction. Please check transaction details.");
						endif;
					else:
						$this->flashMessenger()->addMessage("error^ Failed to book advance salary to transaction. Please Check the transaction year and month.");
					endif;
				else:
					$this->flashMessenger()->addMessage("error^ Failed to book advance salary to transaction. Please check transaction fields");
				endif;
			else:
				$this->flashMessenger()->addMessage("error^ Failed to book advance salary to transaction. Please check voucher date and amount.");
			endif;
			return $this->redirect()->toRoute('payroll', array('action'=>'payroll', 'id'=> $form['year'].'-'.$form['month']));
		else:
			if(isset($year) && isset($month)):
				$data = array(
					'pr.organisation_id' => $this->organisation_id,
					'year' => $year,
					'month' => $month,
				);			
				$organisations = $this->getDefinedTable('Accounts\PayrollTable')->salaryBookingOrganisation($data);
				$user_org  = $this->organisation_id; 					
				return new ViewModel(array(
					'title'  => 'Advance Salary Booking',
					'data' => $data,
					'orgObj' => $this->getDefinedTable('Hr\OrganisationTable'),
					'journals' => $this->getDefinedTable('Accounts\JournalTable')->getAll(),
					'masterDtlObj' => $this->getDefinedTable('Accounts\MasterDetailsTable'),
					'subheads' => $this->getDefinedTable('Accounts\SubHeadTable')->getAll(),
					'organisations' => $organisations,
					'payrollObj' => $this->getDefinedTable('Accounts\PayrollTable'),
					'paydetailObj' => $this->getDefinedTable('Accounts\PaydetailTable'),
					'employeeObj'  => $this->getDefinedTable('Hr\EmployeeDetailsTable'),
					'postlevelObj'  => $this->getDefinedTable('Hr\PositionlevelTable'),
				    'postitleObj' => $this->getDefinedTable('Hr\PositiontitleTable'),
			        'pemplObj' => $this->getDefinedTable('Hr\JobProfileTable'),
			        'etypeObj' => $this->getDefinedTable('Hr\EmployeeTypeTable'),
			        'userorg' => $this->organisation_id,
					'materdtlsObj' => $this->getDefinedTable('Accounts\MasterDetailsTable'),
					'bankdetls' => $this->getDefinedTable('Accounts\BankAccountTable')->get(array('organisation_id'=>$user_org)),
				));				
			endif;
		endif;
	}
	
	/*
	 * function to calculate payhead amount on change of payheads
	 */
	public function calculatePayheadAmount($paystructure){
		$payhead_id =$paystructure['pay_head_id'];
		$employee_details = $paystructure['employee_details'];
		$deduction = $this->getDefinedTable('Accounts\PayheadTable')->getColumn($payhead_id, 'deduction');
		if($deduction == 1):
			$affected_ps = $this->getDefinedTable('Accounts\PaystructureTable')->get(array('sd.employee_details'=>$employee_details,'sd.organisation_id'=>$this->_user->organisation_id, 'ph.against'=> $payhead_id));
		else:
			$affected_ps = $this->getDefinedTable('Accounts\PaystructureTable')->get(array('sd.employee_details'=>$employee_details,'sd.organisation_id'=>$this->_user->organisation_id,'ph.against'=> array($payhead_id,'-1','-2')));
		endif;
		
        $againstGrossPH = array(); 
        $againstPitNet = array(); 
		foreach($affected_ps as $aff_ps):
			if($aff_ps['against'] == '-1'):
				array_push($againstGrossPH, $aff_ps);
			elseif($aff_ps['against'] == '-2'):
				array_push($againstPitNet, $aff_ps);
			else:
				$base_amount = $this->getDefinedTable('Accounts\PaystructureTable')->getColumn(array('employee_details'=>$employee_details, 'sd.organisation_id'=>$this->_user->organisation_id,'pay_head'=>$aff_ps['against']),'amount');
			endif;
			if($aff_ps['type'] == 2 && $aff_ps['against'] != '-1' && $aff_ps['against'] != '-2'):// type = 2 percentage				
				$amount = ($base_amount*$aff_ps['percent'])/100;
				if($aff_ps['roundup'] == 1):
					$amount = round($amount);
				endif;
				$data = array(
					'id' => $aff_ps['id'],
					'amount' => $amount,
					'author' =>$this->employee_details_id,
					'modified' =>$this->_modified,
				);
				$result = $this->getDefinedTable('Accounts\PaystructureTable')->save($data);
		    elseif($aff_ps['type'] == 3 && $aff_ps['against'] != '-1' && $aff_ps['against'] != '-2'):// type = 3 slab	
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
					'author' =>$this->employee_details_id,
					'modified' =>$this->_modified,
				);
				$result = $this->getDefinedTable('Accounts\PaystructureTable')->save($data);
			endif;
		endforeach;
		
		//making changes to temp payroll
		foreach($this->getDefinedTable('Accounts\TempPayrollTable')->get(array('pr.employee_details' => $employee_details,'pr.organisation_id'=>$this->_user->organisation_id,)) as $temp_payroll);				
		$total_earning = 0;		
		$total_deduction = 0;
		$total_actual_earning = 0;
		$total_actual_deduction = 0;
		foreach($this->getDefinedTable('Accounts\PaystructureTable')->get(array('sd.employee_details' => $employee_details,'sd.organisation_id'=>$this->_user->organisation_id, 'ph.deduction'=>'1')) as $paydetails):
			if($paydetails['dlwp']==1):
				$amount = $paydetails['amount'] - ($paydetails['amount']/$temp_payroll['working_days']) * $temp_payroll['leave_without_pay'];
			else:
				$amount = $paydetails['amount'];
			endif;
			$total_deduction = $total_deduction + $amount;
			$total_actual_deduction = $total_actual_deduction + $paydetails['amount'];
		endforeach;	
		foreach($this->getDefinedTable('Accounts\PaystructureTable')->get(array('sd.employee_details' => $employee_details,'sd.organisation_id'=>$this->_user->organisation_id, 'ph.deduction'=>'0')) as $paydetails):
			if($paydetails['dlwp']==1):
				$amount = $paydetails['amount'] - ($paydetails['amount']/$temp_payroll['working_days']) * $temp_payroll['leave_without_pay'];
			else:
				$amount = $paydetails['amount'];
			endif;
			$total_earning = $total_earning + $amount;
			$total_actual_earning = $total_actual_earning + $paydetails['amount'];
		endforeach;				
		$leave_encashment = $temp_payroll['leave_encashment'];
		$bonus = $temp_payroll['bonus'];
		$net_pay = $total_earning + $leave_encashment + $bonus - $total_deduction;
		$earning_dlwp = $total_actual_earning - $total_earning;
		$deduction_dlwp = $total_actual_deduction - $total_deduction;
		$data1 = array(
				'id'	=> $temp_payroll['id'],
				'gross' => $total_actual_earning,
				'total_deduction' => $total_actual_deduction,
				'net_pay' => $net_pay,
				'earning_dlwp' => $earning_dlwp,
				'deduction_dlwp' => $deduction_dlwp,
				'status' => '1', // initiated
				'author' =>$this->employee_details_id,
				'modified' =>$this->_modified,
		);	
			//echo "<pre>";print_r($data1);exit;
		$result1 = $this->getDefinedTable('Accounts\TempPayrollTable')->save($data1);
		if($result1):
			if(sizeof($againstGrossPH)>0){
			   foreach($againstGrossPH as $aff_ps):
				   $base_amount = $this->getDefinedTable('Accounts\TempPayrollTable')->getColumn(array('employee_details'=>$employee_details,'organisation_id'=>$this->_user->organisation_id,),'gross');
				   if($aff_ps['type'] == 2){
					  $amount = ($base_amount*$aff_ps['percent'])/100;
						if($aff_ps['roundup'] == 1):
							$amount = round($amount);
						endif;
						$data = array(
							'id' => $aff_ps['id'],
							'amount' => $amount,
					        'author' =>$this->employee_details_id,
							'modified' =>$this->_modified,
						);
						$result = $this->getDefinedTable('Accounts\PaystructureTable')->save($data);
				   }
				   elseif($aff_ps['type'] == 3){
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
					        'author' =>$this->employee_details_id,
							'modified' =>$this->_modified,
						);
						$result = $this->getDefinedTable('Accountsv\PaystructureTable')->save($data);
				   }
			   endforeach;
			}
			if(sizeof($againstPitNet)>0){
			   foreach($againstPitNet as $aff_ps):
				   $Gross_amount = $this->getDefinedTable('Accounts\TempPayrollTable')->getColumn(array('employee_details'=>$employee_details,'organisation_id'=>$this->_user-organisation_id,),'gross');
				   $PFDed = $this->getDefinedTable('Accounts\PaystructureTable')->getColumn(array('employee_details'=>$employee_details, 'organisation_id'=>$this->_user-organisation_id,'pay_head'=>13),'amount');
				   $GISDed = $this->getDefinedTable('Accounts\PaystructureTable')->getColumn(array('employee_details'=>$employee_details,'organisation_id'=>$this->_user-organisation_id, 'pay_head'=>17),'amount');
				   $base_amount = $Gross_amount - $PFDed - $GISDed;
				   if($aff_ps['type'] == 2){
					  $amount = ($base_amount*$aff_ps['percent'])/100;
						if($aff_ps['roundup'] == 1):
							$amount = round($amount);
						endif;
						$data = array(
							'id' => $aff_ps['id'],
							'amount' => $amount,
					        'author' =>$this->employee_details_id,
							'modified' =>$this->_modified,
						);
						$result = $this->getDefinedTable('Accounts\PaystructureTable')->save($data);
				   }
				   elseif($aff_ps['type'] == 3){
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
					        'author' =>$this->employee_details_id,
							'modified' =>$this->_modified,
						);
						$result = $this->getDefinedTable('Accounts\PaystructureTable')->save($data);
				   }
			    endforeach;
			}
          return $result1;
		endif; 
	}
	
	/*
	 * Action to add pay structure
	**/
	public function paystructureAction()
	{
		$this->init();
		return new ViewModel(array(
			'title' => 'Pay Structure',
			'id' => $this->_id,
			'employee' => $this->getDefinedTable('Hr\EmployeeDetailsTable')->get(array('e.id'=>$this->_id,'e.organisation_id'=>$this->organisation_id)),
			'emphistoryObj' => $this->getDefinedTable('Hr\JobProfileTable'),
			'empljps' => $this->getDefinedTable('Hr\JobProfileTable')->getmaxpedetails('id',array('eh.employee_details' => $this->_id,'eh.organisation_id'=>$this->organisation_id)),
			'pay_heads' => $this->getDefinedTable('Accounts\PayheadTable')->getAll(),
			'payheadObj' => $this->getDefinedTable('Accounts\PayheadTable'),
			'paystructure' => $this->getDefinedTable('Accounts\PaystructureTable')->get(array('employee_details' => $this->_id)),
			'paystructureObj' => $this->getDefinedTable('Accounts\PaystructureTable'),
			'orgaObj' => $this->getDefinedTable('Hr\OrganisationTable'),
			'deptObj' => $this->getDefinedTable('Hr\DepartmentTable'),
			'deptutObj' => $this->getDefinedTable('Hr\DepartmentUnitTable'),
			'ptObj' => $this->getDefinedTable('Hr\PositiontitleTable'),
			'plObj' => $this->getDefinedTable('Hr\PositionlevelTable'),
			'mogObj' => $this->getDefinedTable('Hr\MajorOccupationalGroupTable'),
			'pcObj' => $this->getDefinedTable('Hr\PositionCategoryTable'),
			'employeepfs' => $this->getDefinedTable('Hr\JobProfileTable')->get(array('employee_details'=>$this->employee_details_id)),
		));
	}
	/**
	 * Ajax to get the employee according to location
	**/
	public function getemployeeAction()
	{
		$this->init();
		
		$form = $this->getRequest()->getPost();
		
		$organisation_id = $form['organisation'];
		$employees = $this->getDefinedTable('Hr\EmployeeDetailsTable')->get(array('e.organisation_id'=>$organisation_id,'e.status'=>array(1,4,5)));
		
		$emp .="<option value='-1'>All</option>";
		foreach($employees as $employee):
			$emp .="<option value='".$employee['id']."'>".$employee['first_name']." ".$employee['middle_name']." ".$employee['last_name']."</option>";
		endforeach;
		echo json_encode(array(
				'emp' => $emp,
		));
		exit;
	}
	/**
	 *View Employee action
	 **/
	public function viewpayrollemployeeAction()
	{
		$this->init();	
		$payrollID = $this->getDefinedTable('Accounts\PayrollTable')->getMax('id', array('employee_details'=>$this->_id));
		$basicPays = $this->getDefinedTable('Accounts\PaydetailTable')->get(array('pay_roll'=>$payrollID, 'pd.pay_head'=>'1'));
		foreach($basicPays as $basicPay):
			$basicPAY = $basicPay['amount'];
		endforeach;
		return new ViewModel(array(
			'title' => 'view',
			'employee' => $this->getDefinedTable('Hr\EmployeeDetailsTable')->get($this->_id),
			'emptypes' => $this->getDefinedTable('Hr\EmployeeTypeTable')->getAll(),
			'empljp' => $this->getDefinedTable('Hr\JobProfileTable')->getmaxpedetails('id',array('eh.employee_details' => $this->_id,'eh.organisation_id'=>$this->organisation_id)),
			'premployees' => $this->getDefinedTable('Hr\JobProfileTable')->get(array('eh.employee_details' => $this->_id,'eh.organisation_id'=>$this->organisation_id)),
			'dzongkhagObj' => $this->getDefinedTable('Hr\DzongkhagTable'),
			'gewogObj' => $this->getDefinedTable('Hr\GewogTable'),
			'villageObj' => $this->getDefinedTable('Hr\VillageTable'),
			'mObj' => $this->getDefinedTable('Hr\MaritialTable'),
			'basicPAY'	  => $basicPAY,
			'userorg'	  => $this->organisation_id,
		    'orgaObj' => $this->getDefinedTable('Hr\OrganisationTable'),
		    'genderObj' => $this->getDefinedTable('Hr\GenderTable'),
		    'countryObj' => $this->getDefinedTable('Hr\CountryTable'),
		    'religionObj' => $this->getDefinedTable('Hr\ReligionTable'),
		    'bgroupObj' => $this->getDefinedTable('Hr\BloodGroupTable'),
		    'nObj' => $this->getDefinedTable('Hr\NationalityTable'),
			'deptObj' => $this->getDefinedTable('Hr\DepartmentTable'),
			'deptutObj' => $this->getDefinedTable('Hr\DepartmentUnitTable'),
			'ptObj' => $this->getDefinedTable('Hr\PositiontitleTable'),
			'plObj' => $this->getDefinedTable('Hr\PositionlevelTable'),
			'mogObj' => $this->getDefinedTable('Hr\MajorOccupationalGroupTable'),
			'pcObj' => $this->getDefinedTable('Hr\PositionCategoryTable'),
			'itypeObj' => $this->getDefinedTable('Hr\IncrementTypeTable'),
		));
	}
	/** funtion to get employee list 
	 * base on authority
	 */
	public function getEmployee($userorg)
	{		
		$employeelist = $this->getDefinedTable('Hr\EmployeeDetailsTable')->getAllEmployee($userorg);//$emp_id, 
		return $employeelist;
		echo $employeelist; exit;
	} 
	/**
	 *  index action
	 */
	public function payrollemployeeAction()
	{
		$this->init();
		
		if($this->getRequest()->isPost()){
			$form = $this->getRequest()->getPost();
			$userorg = $form['organisation'];
			$employeelist = $this->getEmployee($userorg);
		}else{
			$userorg = $this->organisation_id;
			$employeelist = $this->getEmployee($userorg);
		}
		
		if(sizeof($employeelist)==-1):
			foreach($employeelist as $emp);
			$this->redirect()->toRoute('employee', array('action'=>'view', 'id'=>$emp['id']));
		endif;
		
		return new ViewModel(array(
			'title' => 'Employee',
			'employee' => $employeelist,
			'userorg' => $userorg,	
			'organisations' => $this->getDefinedTable('Hr\OrganisationTable')->getAll(),
			'OOBJ' => $this->getDefinedTable('Hr\OrganisationTable'),
			'postitleObj' => $this->getDefinedTable('Hr\PositiontitleTable'),
			'employeecatObj' => $this->getDefinedTable('Hr\EmployeeCategoryTable'),
			'employeetypeObj' => $this->getDefinedTable('Hr\EmployeeTypeTable'),
		));	
	}
}

<?php
namespace Accounts\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Expression;

class PayrollTable extends AbstractTableGateway implements AdapterAwareInterface
{
	protected $table = 'payr_payroll'; //tablename

	public function setDbAdapter(Adapter $adapter)
	{
		$this->adapter = $adapter;
		$this->resultSetPrototype = new HydratingResultSet();
		$this->initialize();
	}	

		/**
	 * Return All records of table
	 * @return Array
	 */
	public function getAll()
	{  
	    $adapter = $this->adapter;
	    $sql = new Sql($adapter);
	    $select = $sql->select();
	    $select->from(array('pr'=>$this->table))
				->join(array('emp'=>'employee_details'), 'pr.employee_details = emp.id', array('employee_details'=>'id','first_name', 'middle_name','last_name','emp_id'))
				->join(array('empl_jp'=>'job_profile'), 'empl_jp.id=pr.empl_payroll', array())
				->join(array('t'=>'employee_type'), 'empl_jp.emp_type_id = t.id', array('emp_type_id'=>'id','employee_type'))
				->join(array('l'=>'organisation'), 'empl_jp.organisation_id = l.id', array('organisation_id'=>'id','organisation_name'));	    
	    $selectString = $sql->getSqlStringForSqlObject($select);
	    $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
	    return $results;
	}
	
	/**
	 * Return records of given condition array | given id
	 * @param Int $id
	 * @return Array
	 */
	public function get($param)
	{
		$where = ( is_array($param) )? $param: array('pr.id' => $param);
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('pr'=>$this->table))
				->join(array('emp'=>'employee_details'), 'pr.employee_details = emp.id', array('employee_details'=>'id','first_name', 'middle_name','last_name','emp_id'))
				->join(array('empl_jp'=>'job_profile'), 'empl_jp.id=pr.empl_payroll', array('position_title_id'))
				->join(array('t'=>'employee_type'), 'empl_jp.emp_type_id = t.id', array('emp_type_id'=>'id','employee_type'))
				->join(array('l'=>'organisation'), 'empl_jp.organisation_id = l.id', array('organisation_id'=>'id','organisation_name'))
		       ->where($where);		
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit; 
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		return $results;
	}
	
	/**
	 * Return records of given condition array | given id
	 * @param Int $id
	 * @return Array
	 */
	public function getforReport($param)
	{
		$where = ( is_array($param) )? $param: array('pr.id' => $param);
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('pr'=>$this->table))
				->join(array('emp'=>'employee_details'), 'pr.employee_details = emp.id', array('employee_details'=>'id','first_name', 'middle_name','last_name','emp_id','cid'))
		        ->join(array('empl_jp'=>'job_profile'), 'empl_jp.id=pr.empl_payroll', array('position_title_id','position_level_id'))
		        ->join(array('d'=>'department'), 'd.id=empl_jp.departments_id', array('departments_id'=>'id','department_name'))
		        ->join(array('o'=>'organisation'), 'o.id=empl_jp.organisation_id', array('organisation_id'=>'id'))
		        ->where($where);		        
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString ; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		return $results;
	}
	
	/**
	 * Return column value of given id
	 * @param Int $id
	 * @param String $column
	 * @return String | Int
	 */
	public function getColumn($param, $column)
	{
		$where = ( is_array($param) )? $param: array('id' => $param);
		$fetch = array($column);
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table);
		$select->columns($fetch);
		$select->where($where);
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
	
		foreach ($results as $result):
	    	$columns =  $result[$column];
		endforeach;
		 
		return $columns;
	}
	
	/**
	 * Save record
	 * @param String $array
	 * @return Int
	 */
	public function save($data)
	{
	    if ( !is_array($data) ) $data = $data->toArray();
	    $id = isset($data['id']) ? (int)$data['id'] : 0;
	    if ( $id > 0 )
	    {
	    	$result = ($this->update($data, array('id'=>$id)))?$id:0;
	    } else {
	        $this->insert($data);
	    	$result = $this->getLastInsertValue(); 
	    }	    	    
	    return $result;	     
	}

	/**
     *  Delete a record
     *  @param int $id
     *  @return true | false
     */
	public function remove($id)
	{
		return $this->delete(array('id' => $id));
	}
	
	/**
	* check particular row is present in the table 
	* with given column and its value
	* @param Array $where
	* @return Boolean
	* 
	*/
	public function isPresent($where)
	{
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			->where($where);
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit; 
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		
		return (sizeof($results)>0)? TRUE:FALSE;
	} 
	
	/**
	 * Return all payrollrecords in month if its payroll is prepared
	 * @return Array
	 */
	public function getLocationYearWise($year,$organisation)
	{
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table, array('month', 'year','organisation_id'))
			   ->group(array('month','year'));
		$select->order(array('month DESC', 'year DESC'));
		if($year != '-1'):
			$select->where(array('year' => $year));
		endif;
		if($organisation != '-1'):
			$select->where(array('organisation_id' => $organisation));
		endif;
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit; 
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		return $results;
	}
	
	/**
	 * Return Min value of the column
	 * @param Array $where
	 * @param String $column
	 * @return String | Int
	 */
	public function getMin($column, $where = NULL)
	{
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table);
		$select->columns(array(
				'min' => new Expression('MIN('.$column.')')
		));
		if($where!=NULL){
			$select->where($where);
		}
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit; 
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
	
		foreach ($results as $result):
		$column =  $result['min'];
		endforeach;
	
		return $column;
	}
	/**
	 * MONTHLY SALARY BOOKING
	 * Get Activity 
	**/
	public function salaryBookingActivity($organisation)
	{
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('pr'=>$this->table))
				->columns(array())
				->join(array('his'=>'emp_emp_history'), 'his.id=pr.emp_his', array('activity_id' => new Expression('Distinct(his.activity_id)')))
				->join(array('act'=>'sys_activity'), 'act.id=his.activity_id', array('activity'))
				->join(array('sact'=>'sys_sub_activity'), 'sact.id=his.sub_activity_id', array('sub_activity'))
				->where(array('his.organisation_id'=> $organisation));
				
		$selectString = $sql->getSqlStringForSqlObject($select);
		echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		return $results;
	}
	/**
	 * MONTHLY SALARY BOOKING
	 * Get sub activity
	**/
	public function salaryBookingPayHead($organisation,$month,$year)
	{
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('pr'=>$this->table))
				->columns(array())
				->join(array('empl_pd'=>'payr_pay_details'), 'empl_pd.pay_roll=pr.id', array('pay_head' => new Expression('Distinct(empl_pd.pay_head)')))
				->where(array('pr.organisation_id'=> $organisation,'pr.month'=>$month,'year'=>$year));
				
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		return $results;
	}
	/**
	 * Return max value of the column
	 * @param Array $where
	 * @param String $column
	 * @return String | Int
	 */
	public function getMax($column, $where=NULL)
	{
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table);
		$select->columns(array(
				'max' => new Expression('MAX('.$column.')')
		));
		if($where!=NULL){
			$select->where($where);
		}
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
	
		foreach ($results as $result):
		$column =  $result['max'];
		endforeach;
	
		return $column;
	}
	
	/**
	 * Return records of given condition array
	 * @param Int $column
	 * @param Int $param
	 * @return Array
	 */
	public function getMaxRow($column,$param)
	{
		$where = ( is_array($param) )? $param: array('id' => $param);
		$adapter = $this->adapter;
		
		$sub0 = new Select($this->table);
		$sub0->columns(array(
				$column => new Expression('MAX('.$column.')')
		));
		//$sub0 = $sub0->toArray();
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
				->where($where)
				->where($column);
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		return $results;
	}
	
	/**
	 * Return records of given condition array
	 * @param Int $column
	 * @param Int $param
	 * @return Array
	 */
	public function getMinRow($column,$param)
	{
		$where = ( is_array($param) )? $param: array('id' => $param);
		$adapter = $this->adapter;
	
		$sub0 = new Select($this->table);
		$sub0->columns(array(
				$column => new Expression('MIN('.$column.')')
		));
		//$sub0 = $sub0->toArray();
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
				->where($where)
				->where($column);
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		return $results;
	}
	
	/**
	 * MONTHLY SALARY BOOKING
	 * Get Locations
	**/
	public function salaryBookingorganisation($param)
	{
		$param = (is_array($param))? $param: array($param);
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('pr'=>$this->table))
				->columns(array())
				->join(array('empl_jp'=>'job_profile'), 'empl_jp.id=pr.empl_payroll', array('organisation_id' => new Expression('Distinct(empl_jp.organisation_id)')))
				->join(array('o'=>'organisation'), 'o.id=empl_jp.organisation_id', array('organisation_name'))
				->where($param);
				
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		return $results;
	}
	
	/**
	 * MONTHLY SALARY BOOKING
	 * Get Subheads
	**/
	public function salaryBookingMasterDtls($data)
	{
		extract($data);
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$sub0 = new Select(array('pd'=>'payr_pay_details'));
		$sub0->columns(array());
		$sub0->join(array('pr'=>$this->table), 'pr.id=pd.pay_roll', array())
			 ->join(array('ph'=>'payr_pay_heads'), 'ph.id=pd.pay_head', array('id' => new Expression('Distinct(ph.id)')))
			 ->join(array('empl_jp'=>'job_profile'), 'empl_jp.id=pr.empl_payroll', array())
			 ->where(array('pr.year'=>$year, 'pr.month'=>$month));
			 if($organisation != '-1'){
				$sub0->where(array('empl_jp.organisation_id'=> $organisation));
			 }
		$select = $sql->select();
		$select->from(array('md'=>'accounts_master_details'))
				->join(array('sh'=>'accounts_sub_head'),'md.sub_head=sh.id',array('sub_head_id'=>'id', 'sub_head'=>'name'))
				->join(array('h'=>'accounts_head'),'sh.head=h.id',array('head_id'=>'id', 'head'=>'name'))
				->join(array('ph'=>'payr_pay_heads'), 'ph.id=md.ref_id', array('deduction'))
				->where(array('md.type'=>8,'ph.deduction'=>$deduction))
				->where->in('md.ref_id',$sub0)
				->where->notin('md.ref_id',array(''));
				
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit; 
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		return $results;
	}
	/**
	 * MONTHLY SALARY BOOKING (AdvanceSalary)
	 * Get Subheads
	**/
	public function salaryAdvanceMasterDtls($data)
	{
		extract($data);
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$sub0 = new Select(array('pd'=>'payr_pay_details'));
		$sub0->columns(array());
		$sub0->join(array('pr'=>$this->table), 'pr.id=pd.pay_roll', array('employee_details'))
			->join(array('empl_jp'=>'job_profile'), 'empl_jp.id=pr.empl_payroll', array())
			 ->where(array('pr.year'=>$year, 'pr.month'=>$month,'pd.pay_head'=>16))
			 ->where->greaterThan('pd.amount',0);
			 if($organisation != '-1'){
				$sub0->where(array('empl_jp.organisation_id'=> $organisation));
			 }
		$select = $sql->select();
		$select->from(array('md'=>'accounts_master_details'))
		        ->join(array('sh'=>'accounts_sub_head'),'md.sub_head=sh.id',array('sub_head_id'=>'id', 'sub_head'=>'name'))
		        ->join(array('h'=>'accounts_head'),'sh.head=h.id',array('head_id'=>'id', 'head'=>'name'))
		        ->where(array('sub_head' =>28,'type'=>5))
				->where->in('ref_id',$sub0);
				
				
		$selectString = $sql->getSqlStringForSqlObject($select);
	    //echo $selectString;exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		return $results;
	}
	/**
	 * GET EMPLOYEES WITH ADVANCE SALARY BOOKING
	 * Get Subheads
	**/
	public function getSADEmp($data)
	{
		extract($data);
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('pd'=>'payr_pay_details'),array())
			->join(array('pr'=>$this->table), 'pr.id=pd.pay_roll', array('employee_details'))
			->join(array('emp_jp'=>'job_profile'), 'emp_jp.id=pr.empl_payroll', array())
			 ->where(array('pr.year'=>$year, 'pr.month'=>$month,'pd.pay_head'=>16))
			 ->order(array('emp_jp.organisation_id'))
			 ->where->greaterThan('pd.amount',0);
				
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString;exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		return $results;
	}
	
	/**
	 * MONTHLY SALARY BOOKING
	 * Return payhead amount for payroll summary
	 * @param Array $param
	 * @return Array
	**/
	public function getAmtforSummary($param, $column)
	{
		extract($param);	
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('pr' => $this->table));
		$select->columns(array('amount' => new Expression('SUM(pr.'.$column.')')))
			->join(array('empl_p'=>'payr_employee'), 'empl_p.id=pr.empl_payroll', array())
			->join(array('o'=>'organisation'), 'empl_p.organisation_id = o.id', array())
			->join(array('e' => 'employee_details'), 'e.id = pr.employee_details', array());
		$select->where(array('pr.year' => $year, 'pr.month' => $month));
	
		if($department != -1):
			if($organisation != -1):
				$select->where(array('empl_p.department'=>$department, 'empl_p.organisation_id'=>$organisation));
			else:
				$select->where(array('empl_p.department' => $department));
			endif;
		else:
			if($organisation != -1):
				$select->where(array('empl_p.organisation_id'=>$organisation));
			endif;
		endif;
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
	
		foreach($results as $result);
		return $result['amount'];
	}
	
	/**
	 * ADVANCE SALARY DEDUCTION
	 * Return records of given condition array | given id
	 * @param Int $id
	 * @return Array
	 */
	public function getSumYearMonth($param,$initial_date)
	{
		$where = ( is_array($param) )? $param: array('pr.id' => $param);
		$initial_y = date_format($initial_date, 'Y');
		$initial_m = date_format($initial_date, 'm');
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('pr'=>$this->table))
			   ->join(array('pd'=>'hr_pay_details'), 'pr.id = pd.pay_roll', array('pay_head'))
		       ->columns(array('sum' => new Expression("SUM(`amount`)")))
		       ->where($where);
		$select->where->greaterThanorEqualTo('pr.year',$initial_y);
		$select->where->greaterThanorEqualTo('pr.month',$initial_m);
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		foreach ($results as $result):
			$column =  $result['sum'];
		endforeach;
		//echo $column; exit;
		return $column;
	}
	
	/**
	 * CONTROL SUMMARY
	 * Get Locations / include region also
	**/
	public function controlsummaryLocation($param)
	{
		$param = (is_array($param))? $param: array($param);
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('pr'=>$this->table))
				->columns(array())
				->join(array('his'=>'hr_emp_history'), 'his.id=pr.emp_his', array('location_id' => new Expression('Distinct(his.location)')))
				->join(array('l'=>'sys_location'), 'l.id=his.location', array('location'))
				->join(array('r'=>'sys_region'), 'r.id=l.region', array('region'))
				->where($param);
				
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		return $results;
	}
	/**
	 * CONTROL SUMMARY
	 * Get No of Entries
	**/
	public function getTotalEntries($param,$status)
	{
		$param = (is_array($param))? $param: array($param);
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('pr'=>$this->table));
		$select->columns(array(
				'count' => new Expression('COUNT(pr.id)')
		));
		$select->join(array('his'=>'hr_emp_history'), 'his.id=pr.emp_his', array())
				->where($param);
		if($status == 'R'):
			$select->where->NotequalTo('his.position_level','19');
		else:
			$select->where->equalTo('his.position_level','19');
		endif;
		$selectString = $sql->getSqlStringForSqlObject($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		foreach ($results as $result):
			$column =  $result['count'];
		endforeach;
		return $column;
	}
	/**
	 * PAYROLL
	 * Get No of Entries
	**/
	public function getCount($param)
	{
		$param = (is_array($param))? $param: array($param);
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table);
		$select->columns(array(
				'count' => new Expression('COUNT(id)')
		));
		$select->where($param);
		$selectString = $sql->getSqlStringForSqlObject($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		foreach ($results as $result):
			$column =  $result['count'];
		endforeach;
		return $column;
	}
	/**
	 * PAYROLL
	 * Get Sum of a column
	**/
	public function getSum($param,$column)
	{
		$param = (is_array($param))? $param: array($param);
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table);
		$select->columns(array(
				'sum' => new Expression('SUM('.$column.')')
		));
		$select->where($param);
		$selectString = $sql->getSqlStringForSqlObject($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		foreach ($results as $result):
			$column =  $result['sum'];
		endforeach;
		return $column;
	}
/**
	 * CONTROL SUMMARY
	 * Get Sum of Earning_lwpd & Deduction_lwpd
	**/
	public function getLWPD($param,$status)
	{
		$param = (is_array($param))? $param: array($param);
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('pr'=>$this->table));
		$select->columns(array(
				'earning_lwpd' => new Expression('SUM(pr.earning_dlwp)'),
				'deduction_lwpd' => new Expression('SUM(pr.deduction_dlwp)'),
		));
		$select->join(array('empl_p'=>'payr_employee'), 'empl_p.id=pr.employee_details', array())
				->where($param);
		if($status == 'R'):
			$select->where->NotequalTo('his.position_level','19');
		else:
			$select->where->equalTo('his.position_level','19');
		endif;
		$selectString = $sql->getSqlStringForSqlObject($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		foreach ($results as $result):
			$earning_lwpd =  $result['earning_lwpd'];
			$deduction_lwpd =  $result['deduction_lwpd'];
		endforeach;
		return $earning_lwpd+deduction_lwpd;
	}
	/**
	 * Return all payrollrecords in month if its payroll is prepared
	 * @return Array
	 */
	public function getPayroll($month,$year,$organisation)
	{
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('pr'=>$this->table), array('month', 'year','organisation_id'))
		        ->join(array('emp'=>'employee_details'), 'pr.employee_details = emp.id', array('employee_details'=>'id','first_name','middle_name','last_name','emp_id'))
				->join(array('empl_jp'=>'job_profile'), 'empl_jp.id=pr.empl_payroll', array('departments_id','departments_units_id','position_title_id'))
				->join(array('t'=>'employee_type'), 'empl_jp.emp_type_id = t.id', array('emp_type_id'=>'id','employee_type'))
				->join(array('o'=>'organisation'), 'o.id= empl_jp.organisation_id', array('organisation'=>'organisation_name','organisation_id'=>'id'));    
		if($month != '-1'):
			$select->where(array('month' => $month));
		endif;
		if($year != '-1'):
			$select->where(array('year' => $year));
		endif;
		if($organisation != '-1'):
			$select->where(array('pr.organisation_id' => $organisation));
		endif;
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		return $results;
	}
}

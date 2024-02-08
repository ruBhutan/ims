<?php
namespace Accounts\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Expression;

class TempPayrollTable extends AbstractTableGateway implements AdapterAwareInterface
{
	protected $table = 'payr_temp_payroll'; //tablename

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
				->join(array('emp'=>'employee_details'), 'pr.employee_details = emp.id', array('employee_details'=>'id','first_name','middle_name','last_name','emp_id','position_title_id'))
				->join(array('emplp'=>'payr_employee'), 'emplp.id=pr.empl_payroll', array())
				->join(array('t'=>'employee_type'), 'emplp.emp_type_id = t.id', array('emp_type_id'=>'id','emp_type'))
				->join(array('o'=>'organisation'), 'o.id= emplp.organisation_id', array('organisation'=>'organisation_name','organisation_id'=>'id'));    
	    $selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit; 
	    $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
	    return $results;
	}
	
	/**
	 * Return All records of table
	 * @return Array
	 */
	public function getLocationWiseEmployeeAll($userorg)
	{  
	    $adapter = $this->adapter;
	    $sql = new Sql($adapter);
	    $select = $sql->select();
	    $select->from(array('pr'=>$this->table))
				->join(array('emp'=>'employee_details'), 'pr.employee_details = emp.id', array('employee_details'=>'id','first_name','middle_name','last_name','emp_id'))
				->join(array('empl_jp'=>'job_profile'), 'empl_jp.id=pr.empl_payroll', array())
				->join(array('t'=>'employee_type'), 'empl_jp.emp_type_id = t.id', array('emp_type_id'=>'id','employee_type'))
				->join(array('o'=>'organisation'), 'o.id= empl_jp.organisation_id', array('organisation'=>'organisation_name','organisation_id'=>'id'));    
	    $select->where(array('pr.organisation_id'=>$userorg));
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
	public function get($param)
	{
		$where = ( is_array($param) )? $param: array('pr.id' => $param);
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('pr'=>$this->table))
				->join(array('emp'=>'employee_details'), 'pr.employee_details = emp.id', array('employee_details'=>'id','first_name','middle_name','last_name', 'emp_id'))
				->join(array('empljp'=>'job_profile'), 'empljp.id=pr.empl_payroll', array())
				->join(array('t'=>'employee_type'), 'empljp.emp_type_id = t.id', array('emp_type_id'=>'id','employee_type'))
				->join(array('o'=>'organisation'), 'o.id= empljp.organisation_id', array('organisation'=>'organisation_name','organisation_id'=>'id'))    
		       ->where($where);
		
		$selectString = $sql->getSqlStringForSqlObject($select);
	    //echo $selectString; exit; 
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
	 * function to insert and delete form hr_temp_payroll table
	 * if the employee status changes or new employee is added
	 */
	public function prepareTempPayroll($data)
	{
		extract($data);
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		
		//delete from temp payroll if employee is resigned, retired or others....
		$emp = new Select(array('empl_jp'=>'job_profile'));
		$emp->columns(array(new Expression('MAX(empl_jp.id)'),'employee_details'));
		$emp->group(array('employee_details'));
		$emp->join(array('empl'=>'employee_details'),'empl.id = empl_jp.employee_details', array());
		$emp->columns(array('employee_details'=>'employee_details'))
		   	->where(array('empl_jp.status'=>array(1,4,5)));
		$emp->where(array('empl.organisation_id'=>$userorg));
		
		
		$delete = $sql->delete();
		$delete->from($this->table);
		$delete->where->notin('employee_details',$emp);
		$delete->where(array('organisation_id'=>$userorg));
		
		$deleteString = $sql->getSqlStringForSqlObject($delete);
		//echo $deleteString; exit; 
		$del_result = $adapter->query($deleteString, $adapter::QUERY_MODE_EXECUTE);
		//end of delete
		
		//insertion of new employee if any
		$empl_jobpf = new Select($this->table);
		$empl_jobpf->columns(array('employee_details'));
		
		$new_employee = new Select(array('empl_jp'=>'job_profile'));
		$new_employee->columns(array('employee_details',new Expression('MAX(empl_jp.id)')));
		$new_employee->group(array('employee_details'));
		$new_employee->join(array('empl'=>'employee_details'),'empl.id = empl_jp.employee_details', array());
		$new_employee->columns(array('employee_details'=>'employee_details'))
					->where(array('empl_jp.status'=>array(1,4,5)))
					->where->notin('empl.id',$empl_jobpf);
		$new_employee->where(array('empl.organisation_id'=>$userorg));
							
		$new_empString = $sql->getSqlStringForSqlObject($new_employee);
		//echo $new_empString; exit;			
		$new_employees= $adapter->query($new_empString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		foreach($new_employees as $row):
			$emp_payroll_detail = new Select(array('empl_jp'=>'job_profile'));
			$emp_payroll_detail->columns(array(
					'id' => new Expression('MAX(id)'),
					'empl_payroll' => 'id',
					'emp_organisation' => 'organisation_id'
			));
			$emp_payroll_detail->where(array('employee_details'=>$row['employee_details'],'organisation_id'=>$userorg));
			
			$emp_payroll_detailsString = $sql->getSqlStringForSqlObject($emp_payroll_detail);	
            //echo $emp_payroll_detailsString; exit;			
			$emp_payroll_details= $adapter->query($emp_payroll_detailsString, $adapter::QUERY_MODE_EXECUTE)->toArray();
			//print_r($emp_payroll_details); exit;
			foreach($emp_payroll_details as $payr_empl_row);
			$new_data = array(
				'employee_details'=> $row['employee_details'],
				'empl_payroll'=> $payr_empl_row['empl_payroll'],
				'month'=> $month,
				'year'=> $year,
				'organisation_id'=> $payr_empl_row['emp_organisation'],
				'status' => '0',
				'working_days'=>date('t',strtotime('1-'.$month.'-'.$year)),
				'author'=>$author,
				'created'=>$created,
				'modified'=>$modified
			);
			//print_r($new_data); exit;
			$this->insert($new_data);
		endforeach;
		// end of insertion of new employee
		
		//update temp_payroll with latest payroll employee id
		foreach($this->getLocationWiseEmployeeAll($userorg) as $tem_payroll):
			$emp_payroll_detail = new Select(array('empl_jp'=>'job_profile'));
			$emp_payroll_detail->columns(array(
					'id' => new Expression('MAX(id)'),
					'empl_payroll' => 'id',
					'emp_organisation' => 'organisation_id'
			));
			$emp_payroll_detail->where(array('employee_details'=>$tem_payroll['employee_details'],'organisation_id'=>$userorg));
			
			$emp_payroll_detailString = $sql->getSqlStringForSqlObject($emp_payroll_detail);
            //echo $emp_payroll_detailString; exit; 			
			$emp_payroll_details = $adapter->query($emp_payroll_detailString, $adapter::QUERY_MODE_EXECUTE)->toArray();
			//print_r($emp_historys); exit;
			foreach($emp_payroll_details as $empl_p_row);
			
			$data = array(
				'empl_payroll'=>$empl_p_row['empl_payroll'],
				'organisation_id'=>$empl_p_row['emp_organisation'],
				'month'=>$month, 
				'year'=>$year, 
				'working_days'=> date('t',strtotime('1-'.$month.'-'.$year))
			);
			$this->update($data, array('employee_details'=>$tem_payroll['employee_details'],'organisation_id'=>$userorg));
		endforeach;
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
	 * Return all payrollrecords in month if its payroll is prepared
	 * @return Array
	 */
	public function getTempPayroll($year,$organisation)
	{
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('tpr'=>$this->table), array('month', 'year','organisation_id'))
		        ->join(array('emp'=>'employee_details'), 'tpr.employee_details = emp.id', array('employee_details'=>'id','first_name','middle_name','last_name','emp_id'))
				->join(array('empl_jp'=>'job_profile'), 'empl_jp.id=tpr.empl_payroll', array('position_title_id'))
				->join(array('t'=>'employee_type'), 'empl_jp.emp_type_id = t.id', array('emp_type_id'=>'id','employee_type'))
				->join(array('o'=>'organisation'), 'o.id= empl_jp.organisation_id', array('organisation'=>'organisation_name','organisation_id'=>'id'));			   
		if($year != '-1'):
			$select->where(array('year' => $year));
		endif;
		if($organisation != '-1'):
			$select->where(array('tpr.organisation_id' => $organisation));
		endif;
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		return $results;
	}
	/**
	 * PAY REGISTER
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
				->join(array('emp'=>'employee_details'), 'pr.employee_details = emp.id', array('employee_details'=>'id','first_name','middle_name','last_name', 'emp_id', 'cid'))
		        ->join(array('empl_jp'=>'job_profile'), 'empl_jp.id=pr.empl_payroll', array('position_title_id','position_level_id','departments_id'))
		        ->join(array('d'=>'departments'), 'd.id=empl_jp.departments_id', array('departments_id'=>'id','department_name'))
		        ->join(array('o'=>'organisation'), 'o.id=empl_jp.organisation_id', array('organisation_id'=>'id'))
		        ->where($where);		        
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
}

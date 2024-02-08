<?php
namespace Accounts\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Expression;

class PaystructureTable extends AbstractTableGateway implements AdapterAwareInterface
{
	protected $table = 'payr_pay_structure'; //tablename

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
	public function getAll($zeroAmt = True)
	{  
	    $adapter = $this->adapter;
	    $sql = new Sql($adapter);
	    $select = $sql->select();
	    $select->from(array('sd' => $this->table))
	            ->join(array('ph' => 'payr_pay_heads'), 'ph.id = sd.pay_head', array('pay_head', 'code', 'pay_head_id' => 'id','against', 'roundup', 'type'));
	    if(!$zeroAmt):
			$select->where->greaterThan('sd.amount', '0');
	    endif;
	    
	    $selectString = $sql->getSqlStringForSqlObject($select);
	    $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
	    return $results;
	}
	
	/**
	 * Return records of given condition array | given id
	 * @param Int $id
	 * @return Array
	 */
	public function get($param, $zeroAmt = True)
	{
		$where = ( is_array($param) )? $param: array('sd.id' => $param);
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('sd'=>$this->table))
		->join(array('ph'=>'payr_pay_heads'),'ph.id = sd.pay_head', array('pay_head', 'code', 'pay_head_id' => 'id', 'against', 'roundup', 'type','deduction'))
		->where($where);
		if(!$zeroAmt):
			$select->where->greaterThan('sd.amount', '0');
	    endif;
		
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
	* 
	*/
	public function isPresent($column, $value)
	{
		$column = $column; $value = $value;
		$resultSet = $this->select(function(Select $select) use ($column, $value){
			$select->where(array($column => $value));
		});
		
		$resultSet = $resultSet->toArray();
		return (sizeof($resultSet)>0)? TRUE:FALSE;
	} 

	/**
	 * Return Min value of the column
	 * @param Array $where
	 * @param String $column
	 * @return String | Int
	 */
	public function getMin($where = NULL, $column)
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
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
	
		foreach ($results as $result):
		$column =  $result['min'];
		endforeach;
	
		return $column;
	}
	
	/**
	 * Return max value of the column
	 * @param Array $where
	 * @param String $column
	 * @return String | Int
	 */
	public function getMax($where=NULL, $column)
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
		$where = ( is_array($param) )? $param: array('sd.id' => $param);
		$adapter = $this->adapter;
		
		$sub0 = new Select($this->table);
		$sub0->columns(array(
				$column => new Expression('MAX('.$column.')')
		));
		//$sub0 = $sub0->toArray();
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('sd'=>$this->table))
	            ->join(array('ph' => 'hr_pay_heads'), 'ph.id = sd.pay_head', array('pay_head', 'pay_head_id' => 'id','roundup','against'))
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
		$where = ( is_array($param) )? $param: array('sd.id' => $param);
		$adapter = $this->adapter;
	
		$sub0 = new Select($this->table);
		$sub0->columns(array(
				$column => new Expression('MIN('.$column.')')
		));
		//$sub0 = $sub0->toArray();
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('sd'=>$this->table))
	            ->join(array('ph' => 'hr_pay_heads'), 'ph.id = sd.pay_head', array('pay_head', 'pay_head_id' => 'id','roundup','against'))
				->where($where)
				->where($column);
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		return $results;
	}
	/**
	 * CONTROL SUMMARY
	 * Return payhead amount for payroll summary
	 * @param Array $param
	 * @return Array
	 */
	public function getControlSummaryAmt($param,$status)
	{
		$param = (is_array($param))? $param: array($param);
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('pd' => $this->table));
		$select->columns(array('amount' => new Expression('SUM(pd.amount)')))
			->join(array('pr' => 'hr_temp_payroll'), 'pr.employee = pd.employee', array())
			->join(array('his'=>'hr_emp_history'), 'his.id=pr.emp_his', array())
		    ->where($param);
		if($status == 'R'):
			$select->where->NotequalTo('his.position_level','19');
		else:
			$select->where->equalTo('his.position_level','19');
		endif;
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
	
		foreach($results as $result);
		return $result['amount'];
	}
}

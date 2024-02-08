<?php
namespace Accounts\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Predicate;
use Zend\Db\Sql\Expression;

class SubheadTable extends AbstractTableGateway implements AdapterAwareInterface
{
	protected $table = 'accounts_sub_head'; //tablename

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
	    $select->from(array('sh'=>$this->table))
				->join(array('h'=>'accounts_head'), 'h.id=sh.head', array('head'=>'name', 'head_id'=>'id'))
				->join(array('g'=>'accounts_group'), 'g.id=h.group', array('group'=>'name', 'group_id'=>'id'))
				->join(array('c'=>'accounts_class'), 'c.id=g.class', array('class'=>'name', 'class_id'=>'id'))
	            ->order(array('code ASC'));
	    $selectString = $sql->getSqlStringForSqlObject($select);
	    $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
	    return $results;
	}
	
	/**
	 * Return records of given condition Array
	 * @param Array
	 * @param Array
	 * @return Array
	 */
	public function get($param, $order=NULL)
	{
		$where = ( is_array($param) )? $param: array('sh.id' => $param);
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
	    $select->from(array('sh'=>$this->table))
				->join(array('h'=>'accounts_head'), 'h.id=sh.head', array('head'=>'name', 'head_id'=>'id'))
				->join(array('g'=>'accounts_group'), 'g.id=h.group', array('group'=>'name', 'group_id'=>'id'))
				->join(array('c'=>'accounts_class'), 'c.id=g.class', array('class'=>'name', 'class_id'=>'id'))
		        ->where($where);
	    if($order != NULL):
	    	$select->order($order);
	    endif;
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit; 
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		return $results;
	}
	
	/**
     * Return column value of given where condition | id
     * @param Int|array $parma
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
	 * Return Min value of the column
	 * @param String $column
	 * @param Array $where
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
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
	
		foreach ($results as $result):
		$column =  $result['min'];
		endforeach;
	
		return $column;
	}
	
	/**
	 * Return max value of the column
	 * @param String $column
	 * @param Array $where
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
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
	
		foreach ($results as $result):
		$column =  $result['max'];
		endforeach;
	
		return $column;
	}
	/**
	 * 
	 * get Subhead present in transactions details
	 * @param Date $start_date
	 * @param Date $end_date
	 * 
	 **/
	public function getTransactionSubheadforAnnexture($start_date,$end_date,$where)
	{		
		extract($start_date,$end_date);	
		
		$year = date('Y', strtotime($start_date));		
		$sub = new Select("accounts_closing_balance");	
		$sub->columns(array("sub_head"))
		    ->where->lessThanOrEqualTo("year",$year);
			 
		$sub0 = new Select("accounts_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3"))
			 ->where->between('voucher_date', $start_date, $end_date)
			 ->OR->lessThan('voucher_date', $start_date);
			 
		$sub1 = new Select("accounts_transaction_details");
		$sub1->columns(array("sub_head"));
		$sub1->where->in("transaction", $sub0);
		 
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->where($where)			  
			   ->where
			        ->nest
				       ->in('id', $sub1)
				       ->OR->in('id', $sub)				
			        ->unnest;

		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray(); 
	    return $results;	  
	}
	/**
	 * 
	 * get Subhead present in transactions details
	 * @param Date $start_date
	 * @param Date $end_date
	 * 
	 **/
	public function getTransactionSubhead($organisation,$start_date,$end_date, $where)
	{		
		//extract($options);	
		
		$year = date('Y', strtotime($start_date));		
		$sub = new Select("accounts_closing_balance");	
		$sub->columns(array("sub_head"))
		    ->where->lessThanOrEqualTo("year",$year);
			 
		$sub0 = new Select("accounts_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3"))
			 ->where->between('voucher_date', $start_date, $end_date);
			 
		$sub1 = new Select("accounts_transaction_details");
		$sub1->columns(array("sub_head"));
		
		if($organisation != -1):
		    $sub1->where(array("organisation_id" => $organisation));
		endif;
		$sub1->where->in("transaction", $sub0);
		 
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->where($where)			  
			   ->where
			        ->nest
				       ->in('id', $sub1)
				       ->OR->in('id', $sub)				
			        ->unnest;
        $select->order('id');
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray(); 
	    return $results;	  
	}
	/**
	 * 
	 * get Subhead present in transactions details
	 * @param Date $start_date
	 * @param Date $end_date
	 * 
	 **/
	public function getTransactionSubheadBA($bank_account,$start_date,$end_date)
	{		
		//extract($options);	
		
		$year = date('Y', strtotime($start_date));		
		$sub = new Select("fa_closing_balance");	
		$sub->columns(array("sub_head"))
		    ->where->lessThanOrEqualTo("year",$year);
			 
		$sub0 = new Select("fa_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3"))
			 ->where->between('voucher_date', $start_date, $end_date);
			 
		$sub1 = new Select("fa_transaction_details");
		$sub1->columns(array("sub_head"));
		
		if($bank_account != -1):
		    $sub1->where(array("organisation_id" => $organisation));
		endif;
		
		$sub1->where->in("transaction", $sub0);
		 
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->where($where)			  
			   ->where
			        ->nest
				       ->in('id', $sub1)
				       ->OR->in('id', $sub)				
			        ->unnest;
        $select->order('id');
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray(); 
	    return $results;	  
	}
  	/**
	 * 
	 * get Subhead present in transactions details
	 * @param Date $start_date
	 * @param Date $end_date
	 * 
	 **/
	public function getTransactionSubheadforLedger($organisation,$start_date,$end_date,$where)
	{		
		//extract($options);	
		
		$year = date('Y', strtotime($start_date));		
		$sub = new Select("accounts_closing_balance");	
		$sub->columns(array("sub_head"))
		    ->where->lessThanOrEqualTo("year",$year);
			 
		$sub0 = new Select("accounts_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3"))
			 ->where->between('voucher_date', $start_date, $end_date)
			 ->OR->lessThan('voucher_date', $start_date);
			 
		$sub1 = new Select("accounts_transaction_details");
		$sub1->columns(array("sub_head"));
		
		if($organisation != -1):
			$sub1->where(array("organisation_id" => $organisation));
		endif;
		$sub1->where->in("transaction", $sub0);
		 
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->where($where)			  
			   ->where
			        ->nest
				       ->in('id', $sub1)
				       ->OR->in('id', $sub)				
			        ->unnest;

		$selectString = $sql->getSqlStringForSqlObject($select);
	    //echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray(); 
	    return $results;	  
	}
	 /**
	 * 
	 * get Subhead present in transactions details
	 * @param Date $start_date
	 * @param Date $end_date
	 * 
	 **/
	public function getTransactionSubheadforPLS($organisation,$start_date,$end_date, $where)
	{		
		
		$prevoius_start_year = date('y', strtotime($start_date)) - 1;
		$prevoius_start_month = date('m', strtotime($start_date));
		$prevoius_start_day = date('d', strtotime($start_date));
		$pre_end_year = date('y', strtotime($end_date)) - 1;
		$pre_end_month = date('m', strtotime($end_date));
		$pre_end_day = date('d', strtotime($end_date));
		$pre_starting_date = date('Y-m-d', strtotime($prevoius_start_year.'-'.$prevoius_start_month.'-'.$prevoius_start_day));
		$pre_ending_date = date('Y-m-d', strtotime($pre_end_year.'-'.$pre_end_month.'-'.$pre_end_day));
		
		$year = date('Y', strtotime($start_date));		
		$sub = new Select("accounts_closing_balance");	
		$sub->columns(array("sub_head"))
		    ->where->lessThanOrEqualTo("year",$year);
			 
		$sub0 = new Select("accounts_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3"))
			 ->where->between('voucher_date', $start_date, $end_date)
			 ->OR->where->between('voucher_date', $pre_starting_date, $pre_ending_date);	 
		$sub1 = new Select("accounts_transaction_details");
		$sub1->columns(array("sub_head"));
		
		if($organisation != -1):
			$sub1->where(array("organisation_id" => $organisation));
		endif;
		$sub1->where->in("transaction", $sub0);
		 
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->where($where)			  
			   ->where
			        ->nest
				       ->in('id', $sub1)
				       ->OR->in('id', $sub)				
			        ->unnest;

		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray(); 
	    return $results;	  
	}
	 /**
	 * 
	 * get Subhead present in transactions details
	 * @param Date $start_date
	 * @param Date $end_date
	 * 
	 **/
	public function getTransactionSubheadforBS($organisation,$start_date,$end_date, $where)
	{		
		
		$prevoius_start_year = date('y', strtotime($start_date)) - 1;
		$prevoius_start_month = date('m', strtotime($start_date));
		$prevoius_start_day = date('d', strtotime($start_date));
		$pre_end_year = date('y', strtotime($end_date)) - 1;
		$pre_end_month = date('m', strtotime($end_date));
		$pre_end_day = date('d', strtotime($end_date));
		$pre_starting_date = date('Y-m-d', strtotime($prevoius_start_year.'-'.$prevoius_start_month.'-'.$prevoius_start_day));
		$pre_ending_date = date('Y-m-d', strtotime($pre_end_year.'-'.$pre_end_month.'-'.$pre_end_day));
		
		$year = date('Y', strtotime($start_date));		
		$sub = new Select("accounts_closing_balance");	
		$sub->columns(array("sub_head"))
		    ->where->lessThanOrEqualTo("year",$year);
			 
		$sub0 = new Select("accounts_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3"))
			 ->where->between('voucher_date', $start_date, $end_date)
			 ->OR->where->between('voucher_date', $pre_starting_date, $pre_ending_date);

			 
		$sub1 = new Select("accounts_transaction_details");
		$sub1->columns(array("sub_head"));
		
		if($organisation != -1):
			$sub1->where(array("organisation_id" => $organisation));
		endif;
		$sub1->where->in("transaction", $sub0);
		 
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->where($where)			  
			   ->where
			        ->nest
				       ->in('id', $sub1)
				       ->OR->in('id', $sub)				
			        ->unnest;

		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray(); 
	    return $results;	  
	}
	/**
	 * Return records of given condition array
	 * @param Int $column
	 * @param Int $param
	 * @return Array
	 */
	public function getMaxRow($column,$param)
	{
		$where = ( is_array($param) )? $param: array('sh.id' => $param);
		$adapter = $this->adapter;
		
		$sub0 = new Select($this->table);
		$sub0->columns(array(
				$column => new Expression('MAX('.$column.')')
		));
		//$sub0 = $sub0->toArray();
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('sh'=>$this->table))
				->join(array('h'=>'fa_head'), 'h.id=sh.head', array('head'=>'name', 'head_id'=>'id'))
				->join(array('g'=>'fa_group'), 'g.id=h.group', array('group'=>'name', 'group_id'=>'id'))
				->join(array('c'=>'fa_class'), 'c.id=g.class', array('class'=>'name', 'class_id'=>'id'))
				->join(array('ht'=>'fa_head_type'), 'ht.id=h.head_type', array('head_type', 'headtype_id'=>'id'))
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
		$where = ( is_array($param) )? $param: array('sh.id' => $param);
		$adapter = $this->adapter;
	
		$sub0 = new Select($this->table);
		$sub0->columns(array(
				$column => new Expression('MIN('.$column.')')
		));
		//$sub0 = $sub0->toArray();
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('sh'=>$this->table))
				->join(array('h'=>'fa_head'), 'h.id=sh.head', array('head'=>'name', 'head_id'=>'id'))
				->join(array('g'=>'fa_group'), 'g.id=h.group', array('group'=>'name', 'group_id'=>'id'))
				->join(array('c'=>'fa_class'), 'c.id=g.class', array('class'=>'name', 'class_id'=>'id'))
				->join(array('ht'=>'fa_head_type'), 'ht.id=h.head_type', array('head_type', 'headtype_id'=>'id'))
		->where($where)
		->where($column);
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		return $results;
	}
	
	/**
	 * Return count base on some condition
	 * @param Array $where
	 * @param Int
	 */
	public function getCount($where = NULL)
	{
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			->columns(array('count' => new Expression('COUNT(*)')));
		
		if($where != NULL):
			$select->where($where);
		endif;
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		
		foreach($results as $row);		
		return $row['count'];
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
		if($where != NULL && ($this->getCount($where) > 0)):
		return TRUE;
		endif;
	
		return FALSE;
	}
	
	/**
	 * Return records of given condition Array
	 * @param Array
	 * @param Array
	 * @return Array
	 */
	public function getSubheadforBS($param, $order=NULL)
	{
		$where = ( is_array($param) )? $param: array('sh.id' => $param);
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
	    $select->from(array('sh'=>$this->table))
				->join(array('h'=>'accounts_head'), 'h.id=sh.head', array('head'=>'name', 'head_id'=>'id'))
				->where($where);
	    if($order != NULL):
	    	$select->order($order);
	    endif;
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		return $results;
	}
	/**
	 * View of Subhead Table	
	 * Return records of given condition Array
	 * @param Array
	 * @param Array
	 * @return Array
	 */
	public function getSubhead($data)
	{
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
	    $select->from(array('sh'=>$this->table))
				->join(array('h'=>'accounts_head'), 'h.id=sh.head', array('head'=>'code', 'head_id'=>'id'))
				->join(array('g'=>'accounts_group'), 'g.id=h.group', array('group'=>'code', 'group_id'=>'id'))
				->join(array('c'=>'accounts_class'), 'c.id=g.class', array('class'=>'code', 'class_id'=>'id'))
				->order(array('sh.code ASC'));
		if($data['class'] != '-1'){
			$select->where(array('c.id' => $data['class']));
		}
		if($data['group'] != '-1'){
			$select->where(array('g.id' => $data['group']));
		}
		if($data['head'] != '-1'){
			$select->where(array('h.id' => $data['head']));
		}
		if($data['group'] == '-1'){
			$select->limit('100');
		}
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		return $results;
	}
	/**
	 * Return id's|columns'value  which is not present in given array
	 * @param Array $param
	 * @param String column
	 * @return Array
	 */
	public function getNotIn($param, $column='id', $where=NULL)
	{
		$param = ( is_array($param) )? $param: array($param);
		$where = (is_array($column)) ? $column: $where;
		$column = (is_array($column)) ? 'id' : $column;
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = new Select();
		$select->from($this->table)
		->columns(array('id'))
		->where->notIn($column, $param);
		if ($where != Null)
		{
			$select->where($where);
		}
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		
		return $results;
	}
	
	/**
	 * Return Sub Ledger  which is not present in given Master Details
	 * @param Array $param
	 * @param String column
	 * @return Array
	 */
	public function getNotInMD($ref_id)
	{			 
		$sub0 = new Select("accounts_master_details");
		$sub0->columns(array("sub_head"))
			 ->where->equalTo('ref_id',$ref_id); 		
		
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('sh'=>$this->table))
				->join(array('md'=>'accounts_master_details'), 'md.sub_head=sh.id', array('sub_head'=>'code', 'subhead_id'=>'id'))
			   ->where->Notin('sh.id', $sub0);
		$select->where(array("md.type" => array(9)));
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray(); 
	    return $results;
	}
	/**
	 * Return Sub Ledger  which is not present in given Master Details
	 * @param Array $param
	 * @param String column
	 * @return Array
	 */
	public function getsubHeadforLSL($param,$organisation_id)
	{		
        $where = ( is_array($param) )? $param: array('sh.id' => $param);	
		
		$sub0 = new Select("accounts_bank_account");
		$sub0->columns(array("id"))
			 ->where->equalTo('organisation_id',$organisation_id); 		
		
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('sh'=>$this->table))
				->join(array('md'=>'accounts_master_details'), 'md.sub_head=sh.id', array('sub_head'=>'code', 'subhead_id'=>'id'))
			   ->where->in('md.ref_id', $sub0);
		$select->where(array("md.type" => array(2)));
		$selectString = $sql->getSqlStringForSqlObject($select);
		echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray(); 
	    return $results;
	}
}


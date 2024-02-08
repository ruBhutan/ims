<?php
namespace Accounts\Model;
use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Expression;

class TransactiondetailTable extends AbstractTableGateway implements AdapterAwareInterface
{
	protected $table = 'accounts_transaction_details'; //tablename

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
	    $select->from(array('td'=>$this->table))
				->join(array('t'=>'accounts_transaction'),'t.id = td.transaction', array('transaction_id' => 'id'))
				->join(array('o'=>'organisation'), 'o.id = td.organisation_id', array('organisation_id' => 'id'))
				->join(array('h'=>'accounts_head'), 'h.id = td.head', array('head' => 'code', 'head_id' => 'id'))
				->join(array('sh'=>'accounts_sub_head'), 'sh.id = td.sub_head', array('sub_head' => 'code', 'sub_head_id' => 'id'));
	    
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
		$where = ( is_array($param) )? $param: array('td.id' => $param);
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
	    $select->from(array('td'=>$this->table))
			->join(array('t'=>'accounts_transaction'),'t.id = td.transaction', array('transaction_id' => 'id', 'voucher_type'))
			->join(array('o'=>'organisation'), 'o.id = td.organisation_id', array('organisation' => 'organisation_name','organisation_id' => 'id'))
			->join(array('h'=>'accounts_head'), 'h.id = td.head', array('head' => 'name', 'head_id' => 'id'))
			->join(array('sh'=>'accounts_sub_head'), 'sh.id = td.sub_head', array('sub_head' => 'name', 'sub_head_id' => 'id'))
			->join(array('md'=>'accounts_master_details'), 'md.id = td.master_details', array('master_details' => 'name', 'ref_id','type','master_details_id' => 'id'))
			->where($where);
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString;exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		return $results;
	}
	/**
	 * Return records of given condition array | given id
	 * @param Int $id
	 * @return Array
	 */
	public function getParty($type_id,$param)
	{
		$where = ( is_array($param) )? $param: array('td.id' => $param);
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
	    $select->from(array('td'=>$this->table))
			->join(array('t'=>'accounts_transaction'),'t.id = td.transaction', array('transaction_id' => 'id', 'voucher_type'))
			->join(array('o'=>'organisation'), 'o.id = td.organisation_id', array('organisation' => 'organisation_name','organisation_id' => 'id'))
			->join(array('h'=>'accounts_head'), 'h.id = td.head', array('head' => 'name', 'head_id' => 'id'))
			->join(array('sh'=>'accounts_sub_head'), 'sh.id = td.sub_head', array('sub_head' => 'name', 'sub_head_id' => 'id'))
			->join(array('md'=>'accounts_master_details'), 'md.id = td.master_details', array('master_details' => 'name', 'ref_id','type','master_details_id' => 'id'))
			->where($where)
			->where(array('md.type'=>$type_id));
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString;exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		return $results;
	}
    /*
	*Type wise record;
	*/
	public function getTypeWise($param, $type)
	{
		$where = ( is_array($param) )? $param: array('td.id' => $param);
		$type = array($type);
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
	    $select->from(array('td'=>$this->table))
				->join(array('t'=>'accounts_transaction'),'t.id = td.transaction', array('transaction_id' => 'id', 'voucher_type'))
				->join(array('o'=>'organisation'), 'o.id = td.organisation_id', array('organisation' => 'organisation_name','organisation_id' => 'id'))
				->join(array('h'=>'accounts_head'), 'h.id = td.head', array('head' => 'code', 'head_id' => 'id'))
				->join(array('sh'=>'accounts_sub_head'), 'sh.id = td.sub_head', array('sub_head' => 'code', 'sub_head_id' => 'id'))
		        ->where($where);
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString;
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
     *  @param Int $id
     *  @return true | false
     */
	public function remove($id)
	{
		return $this->delete(array('id' => $id));
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
	 * get sum by class
	 * @param String $column
	 * @param Array $options
	 * @param String $column
	 * @param int $class
	 * @return int
	 */
	public function getSumbyClass($organisation,$start_date,$end_date, $column, $class)
	{		
		
		$sub0 = new Select("accounts_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3")) //committed status
			 ->where->between('voucher_date', $start_date, $end_date);
		
		$sub1 = new Select("accounts_group");
		$sub1->columns(array("id"))
			 ->where(array("class" =>$class));
		
		$sub2 = new Select("accounts_head");
		$sub2->columns(array("id"))
			 ->where->in("group", $sub1);
		
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->columns(array( new Expression('SUM('.$column.') as total')));
		if($organisation != -1):
		    $select->where(array("organisation_id" => $organisation));
		endif;
		$select->where->in('head', $sub2)
			   ->where->in('transaction', $sub0);
        $select->order(array('transaction ASC'));
		$select->order(array('created ASC'));
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();           
		
	   foreach ($results as $result):
		  $sum =  $result['total'];
	   endforeach;  
	   return $sum;	    
	}
	
	/**
	 * get sum by group
	 * @param String $column
	 * @param Array $options
	 * @param String $column
	 * @param Int $group
	 * @return int
	 */
	public function getSumbyGroup($organisation,$start_date,$end_date, $column, $group)
	{		
		//extract($options);
		
		$sub0 = new Select("accounts_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3")) //committed status
			 ->where->between('voucher_date', $start_date, $end_date);
			 
		$sub1 = new Select("accounts_head");
		$sub1->columns(array("id"))
			 ->where(array("group"=>$group));
		
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->columns(array( new Expression('SUM('.$column.') as total')));
        if($organisation != -1):
		    $select->where(array("organisation_id" => $organisation));
		endif;		
		$select->where->in('head', $sub1)
			   ->where->in('transaction', $sub0);
        $select->order(array('transaction ASC'));
		$select->order(array('created ASC'));
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();           
		
	   foreach ($results as $result):
		  $sum =  $result['total'];
	   endforeach;  
	   
	   return $sum;	    
	}	
	/**
	 * get sum by head
	 * @param String $column
	 * @param Array $options
	 * @param String $column
	 * @param Int $head
	 * @return int
	 */
	public function getSumbyHead($organisation,$start_date,$end_date, $column, $head)
	{
		//extract($options);
		
		$sub0 = new Select("accounts_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3")) //committed status
			 ->where->between('voucher_date', $start_date, $end_date);
			 
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->columns(array( new Expression('SUM('.$column.') as total')))
			   ->where(array("head" => $head));
		if($organisation != -1):
		    $select->where(array("organisation_id" => $organisation));
		endif;
		$select->where->in('transaction', $sub0);
        $select->order(array('transaction ASC'));
		$select->order(array('created ASC'));
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString ; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();           
		
	   foreach ($results as $result):
		  $sum =  $result['total'];
	   endforeach;  
	   
	   return $sum;
	}	
	/**
	 * get sum by subhead
	 * @param String $column
	 * @param Array $options
	 * @param String $column
	 * @param Int $sub_head
	 * @return int
	 */		
	public function getSumbySubhead($organisation,$start_date,$end_date,$column, $sub_head)
	{
		
		$sub0 = new Select("accounts_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3")) //committed status
			 ->where->between('voucher_date', $start_date, $end_date);
				
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->columns(array( new Expression('SUM('.$column.') as total')))
			   ->where(array("sub_head" => $sub_head));
		if($organisation != -1):
			$select->where(array("organisation_id" => $organisation));
		endif;
		$select->where->in('transaction', $sub0);
        $select->order(array('transaction ASC'));
		$select->order(array('created ASC'));
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();           
		
	   foreach ($results as $result):
		  $sum =  $result['total'];
	   endforeach;  
	   
	   return $sum;	    
	}
	/**
	 * get sum by subhead for annexture
	  * @param String $column
	 * @param Array $options
	 * @param String $column
	 * @param Int $sub_head
	 * @return int
	 */		
	public function getSumbySubheadforAnnexture($organisation,$start_date,$end_date, $column,$sub_head)
	{
		$sub0 = new Select("accounts_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3")) //committed status
			 ->where->between('voucher_date', $start_date, $end_date);
				
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->columns(array( new Expression('SUM('.$column.') as total')))
			   ->where(array("sub_head" => $sub_head));
			   if($organisation != -1):
				    $select->where(array("organisation_id" => $organisation));
			   endif;
		$select->where->in('transaction', $sub0);
		$select->order(array('transaction ASC'));
		$select->order(array('created ASC'));

		$selectString = $sql->getSqlStringForSqlObject($select);
	    //echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();           
		
	   foreach ($results as $result):
		  $sum =  $result['total'];
	   endforeach;  
	   
	   return $sum;	    
	}
	/**
	 * get sum by subhead for Ledger
	  * @param String $column
	 * @param Array $options
	 * @param String $column
	 * @param Int $sub_head
	 * @return int
	 */		
	public function getSumbyTransactionIDforLedger($organisation,$start_date,$end_date,$column,$where)
	{
		$sub0 = new Select("accounts_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3")) //committed status
			 ->where->between('voucher_date', $start_date, $end_date);
				
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->columns(array( new Expression('SUM('.$column.') as total')))
			   ->where($where);
			    if($organisation != -1):
				    $select->where(array("organisation_id" => $organisation));
			    endif;
		$select->where->in('transaction', $sub0);
        $select->order(array('transaction ASC'));
		$select->order(array('created ASC'));
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString;  exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();           
		
	   foreach ($results as $result):
		  $sum =  $result['total'];
	   endforeach;  
	   
	   return $sum;	    
	}
	
	/**
	 * get sum by subhead for annexture opening
	  * @param String $column
	 * @param String $column
	 * @param Int $sub_head
	 * @return int
	 */		
	public function getSumbySubheadforAnnextureOpening($start_date,$end_date,$column,$sub_head)
	{
		//extract($start_date,$end_date);
		
		$sub0 = new Select("accounts_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3")) //committed status
			 ->where->between('voucher_date',$start_date,$end_date);
				
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->columns(array( new Expression('SUM('.$column.') as total')))
			   ->where(array("sub_head" => $sub_head));
		$select->where->in('transaction', $sub0);
        $select->order(array('transaction ASC'));
		$select->order(array('created ASC'));
		$selectString = $sql->getSqlStringForSqlObject($select);
	   //echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();           
		
	   foreach ($results as $result):
		  $sum =  $result['total'];
	   endforeach;  
	   
	   return $sum;	    
	}
	/**
	 * get sum by subhead for sub ledger opening
	  * @param String $column
	 * @param String $column
	 * @param Int $sub_head
	 * @return int
	 */		
	public function getSumbySubheadforLedgerOpening($start_date,$column,$sub_head)
	{
		//extract($start_date,$end_date);			
		$sub0 = new Select("accounts_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3")) //committed status
			 ->where->lessThan('voucher_date',$start_date);
				
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->columns(array( new Expression('SUM('.$column.') as total')))
			   ->where(array("sub_head" => $sub_head));
		$select->where->in('transaction', $sub0);
        $select->order(array('transaction ASC'));
		$select->order(array('created ASC'));
		$selectString = $sql->getSqlStringForSqlObject($select);
	    //echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();           
		
	   foreach ($results as $result):
		  $sum =  $result['total'];
	   endforeach;  
	   
	   return $sum;	    
	}
	/**
	 * get sum by subhead for ledger opening
	 * @param String $column
	 * @param String $column
	 * @param Int $sub_head
	 * @return int
	 */		
	public function getSumbyheadforLedgerOpening($start_date,$column,$head)
	{		
		$sub0 = new Select("accounts_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3")) //committed status
			 ->where->lessThan('voucher_date',$start_date);
				
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->columns(array( new Expression('SUM('.$column.') as total')))
			   ->where(array("head" => $head));
		$select->where->in('transaction', $sub0);
        $select->order(array('transaction ASC'));
		$select->order(array('created ASC'));
		$selectString = $sql->getSqlStringForSqlObject($select);
	    //echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();           
		
	   foreach ($results as $result):
		  $sum =  $result['total'];
	   endforeach;  
	   
	   return $sum;	    
	}
	/**
	 * Calculate opening balance for sub ledger
	 * @param Array $options
	 * @param Int $id
	 * @return Int
	 */
	public function getOpeningBalanceforSHLedger($start_date,$organisation,$id)
	{	
	
		$adapter = $this->adapter;  
		$sql = new Sql($adapter);
		$year = date('Y', strtotime($start_date));
		
		$sub0 = new Select("accounts_closing_balance");

			$sub0->columns(array( new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
            $sub0->where->lessThanOrEqualTo("year", $year);	
			$sub0->where(array('sub_head' => $id));				
			$sub0->where(array('organisation_id' => $organisation));				
			$total_debit = $this->getSumbySubheadforLedgerOpening($start_date, 'debit', $id);
			$total_credit = $this->getSumbySubheadforLedgerOpening($start_date, 'credit', $id); 

		$selectString = $sql->getSqlStringForSqlObject($sub0);
        //echo $selectString; exit;
		$balances = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();	
		foreach($balances as $balance); 
		return $balance['total_closing_dr'] - $balance['total_closing_cr'] + $total_debit - $total_credit;				
	}
	/**
	 * get sum by bank subhead
	 * @param String $column
	 * @param String $column
	 * @param Int $sub_head
	 * @return int
	 */		
	public function getSumbyBankSubLedger($start_date,$column,$bank_sub_ledger)
	{		
		$sub0 = new Select("accounts_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3")) //committed status
			 ->where->lessThanorEqualTo('voucher_date',$start_date);
				
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->columns(array( new Expression('SUM('.$column.') as total')))
			   ->where(array("sub_head" => $bank_sub_ledger));
		$select->where->in('transaction', $sub0);
        $select->order(array('transaction ASC'));
		$select->order(array('created ASC'));
		$selectString = $sql->getSqlStringForSqlObject($select);
	   //echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();           
		
	   foreach ($results as $result):
		  $sum =  $result['total'];
	   endforeach;  
	   
	   return $sum;	    
	}
	/**
	 * Calculate opening balance for sub ledger
	 * @param Array $options
	 * @param Int $id
	 * @return Int
	 */
	public function getBankBalance($date,$bank_sub_ledger,$organisation)
	{	
	
		$adapter = $this->adapter;  
		$sql = new Sql($adapter);
		$year = date('Y', strtotime($date));
		
		$sub0 = new Select("accounts_closing_balance");

			$sub0->columns(array( new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
            $sub0->where->lessThanOrEqualTo("year", $year);	
			$sub0->where(array('sub_head' => $bank_sub_ledger));				
			$sub0->where(array('organisation_id' => $organisation));				
			$total_debit = $this->getSumbyBankSubLedger($date, 'debit', $bank_sub_ledger);
			$total_credit = $this->getSumbyBankSubLedger($date, 'credit', $bank_sub_ledger); 

		$selectString = $sql->getSqlStringForSqlObject($sub0);
        //echo $selectString; exit;
		$balances = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();	
		foreach($balances as $balance); 
		return $balance['total_closing_dr'] - $balance['total_closing_cr'] + $total_debit - $total_credit;				
	}
	/**
	 * get sum by bank subhead
	 * @param String $column
	 * @param String $column
	 * @param Int $sub_head
	 * @return int
	 */		
	public function getSumbyCashSubLedger($date,$column,$cash_subledger)
	{		
		$sub0 = new Select("accounts_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3")) //committed status
			 ->where->lessThanorEqualTo('voucher_date',$date);
				
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->columns(array( new Expression('SUM('.$column.') as total')))
			   ->where(array("sub_head" => $cash_subledger));
		$select->where->in('transaction', $sub0);
        $select->order(array('transaction ASC'));
		$select->order(array('created ASC'));
		$selectString = $sql->getSqlStringForSqlObject($select);
	    //echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();           
		
	   foreach ($results as $result):
		  $sum =  $result['total'];
	   endforeach;  
	   
	   return $sum;	    
	}
	/**
	 * Calculate opening balance for sub ledger
	 * @param Array $options
	 * @param Int $id
	 * @return Int
	 */
	public function getCashBalance($date,$cash_subledger,$organisation)
	{	
	
		$adapter = $this->adapter;  
		$sql = new Sql($adapter);
		$year = date('Y', strtotime($date));
		
		$sub0 = new Select("accounts_closing_balance");

			$sub0->columns(array( new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
            $sub0->where->lessThanOrEqualTo("year", $year);	
			$sub0->where(array('sub_head' => $cash_subledger));				
			$sub0->where(array('organisation_id' => $organisation));				
			$total_debit = $this->getSumbyCashSubLedger($date, 'debit', $cash_subledger);
			$total_credit = $this->getSumbyCashSubLedger($date, 'credit', $cash_subledger); 

		$selectString = $sql->getSqlStringForSqlObject($sub0);
        //echo $selectString; exit;
		$balances = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();	
		foreach($balances as $balance); 
		return $balance['total_closing_dr'] - $balance['total_closing_cr'] + $total_debit - $total_credit;				
	}
	/**
	 * Calculate opening balance for ledger
	 * @param Array $options
	 * @param Int $id
	 * @return Int
	 */
	public function getOpeningBalanceforHLedger($start_date,$organisation,$id)
	{	
	
		$adapter = $this->adapter;  
		$sql = new Sql($adapter);
		$year = date('Y', strtotime($start_date));

		$sub1 = new Select("accounts_sub_head");
		$sub1->columns(array("id"))
		     ->where(array("head"=>$id));
			
		$sub0 = new Select("accounts_closing_balance");

		$sub0->columns(array( new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
		$sub0->where->lessThanOrEqualTo("year",$year)
			 ->where->in('sub_head',$sub1);
		$sub0->where(array('organisation_id'=>$organisation));
		
		$total_debit = $this->getSumbyheadforLedgerOpening($start_date,'debit', $id);
		$total_credit = $this->getSumbyheadforLedgerOpening($start_date, 'credit', $id); 
 			
		$selectString = $sql->getSqlStringForSqlObject($sub0);
        //echo $selectString; exit;
		$balances = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();	
		foreach($balances as $balance); 
		return $balance['total_closing_dr'] - $balance['total_closing_cr'] + $total_debit - $total_credit;				
	}
	/**
	 * get sum by subhead TBO
	 * @param String $column
	 * @param Array $options
	 * @param String $column
	 * @param Int $sub_head
	 * @return int
	 */		
	public function getSumbySubheadTBOpening($organisation,$start_date,$column, $sub_head)
	{		
		$sub0 = new Select("accounts_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3")) //committed status
			 ->where->lessThan('voucher_date',$start_date);
				
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->columns(array( new Expression('SUM('.$column.') as total')))
			   ->where(array("sub_head" => $sub_head));
		if($organisation != -1):
			$select->where(array("organisation_id" => $organisation));
		endif;
		$select->where->in('transaction', $sub0);
        $select->order(array('transaction ASC'));
		$select->order(array('created ASC'));
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();           
		
	   foreach ($results as $result):
		  $sum =  $result['total'];
	   endforeach;  
	   
	   return $sum;	    
	}
	/**
	 * get sum by head TBO
	 * @param String $column
	 * @param Array $options
	 * @param String $column
	 * @param Int $head
	 * @return int
	 */
	public function getSumbyHeadTBOpening($organisation,$start_date, $column, $head)
	{
		//extract($options);
		
		$sub0 = new Select("accounts_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3")) //committed status
			 ->where->lessThan('voucher_date', $start_date);
			 
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->columns(array( new Expression('SUM('.$column.') as total')))
			   ->where(array("head" => $head));
		if($organisation != -1):
			$select->where(array("organisation_id" => $organisation));
		endif;
		$select->where->in('transaction', $sub0);
        $select->order(array('transaction ASC'));
		$select->order(array('created ASC'));
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString ; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();           
		
	   foreach ($results as $result):
		  $sum =  $result['total'];
	   endforeach;  
	   
	   return $sum;
	}
	/**
	 * get sum by class for TBO
	 * @param String $column
	 * @param Array $options
	 * @param String $column
	 * @param int $class
	 * @return int
	 */
	public function getSumbyClassTBOpening($organisation,$start_date, $column, $class)
	{		
		
		$sub0 = new Select("accounts_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3")) //committed status
			 ->where->lessThan('voucher_date', $start_date);
		
		$sub1 = new Select("accounts_group");
		$sub1->columns(array("id"))
			 ->where(array("class" => $class));
		
		$sub2 = new Select("accounts_head");
		$sub2->columns(array("id"))
			 ->where->in("group", $sub1);
		
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->columns(array( new Expression('SUM('.$column.') as total')));
			   
		if($organisation != -1):
			$select->where(array("organisation_id" => $organisation));
		endif;
		$select->where->in('head', $sub2)
			   ->where->in('transaction', $sub0);
        $select->order(array('transaction ASC'));
		$select->order(array('created ASC'));
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();           
		
	   foreach ($results as $result):
		  $sum =  $result['total'];
	   endforeach;  
	   return $sum;	    
	}
	/**
	 * get sum by group TBO
	 * @param String $column
	 * @param Array $options
	 * @param String $column
	 * @param Int $group
	 * @return int
	 */
	public function getSumbyGroupTBOpening($organisation,$start_date, $column, $group)
	{		
		
		$sub0 = new Select("accounts_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3")) //committed status
			 ->where->lessThan('voucher_date', $start_date);
			 
		$sub1 = new Select("accounts_head");
		$sub1->columns(array("id"))
			 ->where(array("group"=>$group));
		
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->columns(array( new Expression('SUM('.$column.') as total')));
			   
		if($organisation != -1):
			$select->where(array("organisation_id" => $organisation));
		endif;
		$select->where->in('head', $sub1)
			   ->where->in('transaction', $sub0);
        $select->order(array('transaction ASC'));
		$select->order(array('created ASC'));
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();           
		
	   foreach ($results as $result):
		  $sum =  $result['total'];
	   endforeach;  
	   return $sum;	    
	}
	/**
	 * Calculate opening balance
	 * @param Array $options
	 * @param Int $id
	 * @param Ind $tier
	 * @return Int
	 */
	public function getOpeningBalance($organisation,$start_date,$end_date, $id, $tier)
	{	 		
		$adapter = $this->adapter;  
		$sql = new Sql($adapter);
		$year = date('Y', strtotime($start_date));
		$sub0 = new Select("accounts_closing_balance");
		if($tier == 1):
			$sub0->columns(array( new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
			$sub0->where->lessThan("year",$year);
			$sub0->where(array('sub_head' => $id,'organisation_id'=>$organisation));
			
			$total_debit = $this->getSumbySubheadTBOpening($organisation,$start_date, 'debit', $id);
			$total_credit = $this->getSumbySubheadTBOpening($organisation,$start_date, 'credit', $id);  			
		elseif($tier == 2):
			$sub1 = new Select("accounts_sub_head");
			$sub1->columns(array("id"))
				->where(array("head"=>$id));
			
			$sub0->columns(array( new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
			$sub0->where->lessThan("year",$year)
			     ->where->equalTo('organisation_id',$organisation)
				 ->where->in('sub_head',$sub1);
			
			$total_debit = $this->getSumbyHeadTBOpening($organisation,$start_date, 'debit', $id);
			$total_credit = $this->getSumbyHeadTBOpening($organisation,$start_date, 'credit', $id);	
		elseif($tier == 3):
			$sub2 = new Select("accounts_head");
			$sub2->columns(array("id"))
				->where(array("group" => $id));
		
			$sub1 = new Select("accounts_sub_head");
			$sub1->columns(array("id"))
				->where->in('head',$sub2);
				
			$sub0->columns(array( new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
			$sub0->where->lessThan("year",$year)
				 ->where->equalTo('organisation_id',$organisation)
				 ->where->in('sub_head',$sub1);
			
			$total_debit = $this->getSumbyGroupTBOpening($organisation,$start_date, 'debit', $id);
			$total_credit = $this->getSumbyGroupTBOpening($organisation,$start_date, 'credit', $id);
		elseif($tier == 4):
			$sub3 = new Select("accounts_group");
			$sub3->columns(array("id"))
				->where(array("class" => $id));
		
			$sub2 = new Select("accounts_head");
			$sub2->columns(array("id"))
				->where->in('group', $sub3);
			
			$sub1 = new Select("accounts_sub_head");
			$sub1->columns(array("id"))
				->where->in('head',$sub2);
			
			$sub0->columns(array( new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
			$sub0->where->lessThan("year",$year)
			     ->where->equalTo('organisation_id',$organisation)
				 ->where->in('sub_head',$sub1);
		
			$total_debit = $this->getSumbyClassTBOpening($organisation,$start_date, 'debit', $id);
			$total_credit = $this->getSumbyClassTBOpening($organisation,$start_date, 'credit', $id);
		endif;
		
		$selectString = $sql->getSqlStringForSqlObject($sub0);	
		//echo $selectString; exit;
		$balances = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();	
		foreach($balances as $balance); 
		return $balance['total_closing_dr'] - $balance['total_closing_cr'] + $total_debit - $total_credit;		
	}
	/**
	 * Calculate opening balance for annexture
	 * @param Array $options
	 * @param Int $id
	 * @return Int
	 */
	public function getOpeningBalanceforAnnexture($start_date,$id)
	{			
		$adapter = $this->adapter;  
		$sql = new Sql($adapter);
		$year = date('Y', strtotime($start_date));
		$starting_date = '2017-07-01';
		$date = $start_date;
		$date = strtotime($date);
		$date = strtotime("-1 day", $date);
		$closing_date = date('Y-m-d', $date);
		$ending_date = $closing_date;
				
		$sub0 = new Select("accounts_closing_balance");

			$sub0->columns(array( new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
			$sub0->where->lessThanOrEqualTo("year", $year);	
            $sub0->where(array('sub_head' => $id));	

			$total_debit = $this->getSumbySubheadforAnnextureOpening($starting_date,$ending_date, 'debit', $id);
			$total_credit = $this->getSumbySubheadforAnnextureOpening($starting_date ,$ending_date, 'credit', $id); 
 			
		$selectString = $sql->getSqlStringForSqlObject($sub0);
      
		$balances = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();	
		foreach($balances as $balance); 
		return $balance['total_closing_dr'] - $balance['total_closing_cr'] + $total_debit - $total_credit;				
	}
	
	/**
	 * Calculate closing balance
	 * @param Array $options
	 * @param Int $id
	 * @param Ind $tier
	 * @return Int
	 */
	public function getClosingBalance($organisation,$start_date,$end_date, $id, $tier)
	{		
		
		$adapter = $this->adapter;  
		$sql = new Sql($adapter);
		
		$year = date('Y', strtotime($start_date));
        $starting_date = date('Y-m-d',strtotime('01-01-'.$year));
		$ending_date = $end_date;
		$sub0 = new Select("accounts_closing_balance");
		//echo $tier; exit;
		if($tier == 1):
			$sub0->columns(array( new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
			$sub0->where->lessThanOrEqualTo("year", $year);
			$sub0->where(array('sub_head' => $id));


			$total_debit = $this->getSumbySubhead($activity,$region,$location,$starting_date,$ending_date, 'debit', $id);
			$total_credit = $this->getSumbySubhead($activity,$region,$location,$starting_date,$ending_date, 'credit', $id); 			
		elseif($tier == 2):
			$sub1 = new Select("accounts_sub_head");
			$sub1->columns(array("id"))
				->where(array("head" => $id));
			
			$sub0->columns(array( new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
			$sub0->where->lessThanOrEqualTo("year", $year)
				->where->in('sub_head',$sub1);
			
			$total_debit = $this->getSumbyHead($activity,$region,$location,$starting_date,$ending_date, 'debit', $id);
			$total_credit = $this->getSumbyHead($activity,$region,$location,$starting_date,$ending_date, 'credit', $id);		
		elseif($tier == 3):
			$sub2 = new Select("accounts_head");
			$sub2->columns(array("id"))
				->where(array("group" => $id));
		
			$sub1 = new Select("accounts_sub_head");
			$sub1->columns(array("id"))
				->where->in('head',$sub2);
				
			$sub0->columns(array( new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
			$sub0->where->lessThanOrEqualTo("year", $year)
				->where->in('sub_head',$sub1);
			
			$total_debit = $this->getSumbyGroup($activity,$region,$location,$starting_date,$ending_date, 'debit', $id);
			$total_credit = $this->getSumbyGroup($activity,$region,$location,$starting_date,$ending_date, 'credit', $id);
		elseif($tier == 4):
			$sub3 = new Select("accounts_group");
			$sub3->columns(array("id"))
				->where(array("class" => $id));
		
			$sub2 = new Select("accounts_head");
			$sub2->columns(array("id"))
				->where->in('group', $sub3);
			
			$sub1 = new Select("accounts_sub_head");
			$sub1->columns(array("id"))
				->where->in('head',$sub2);
			
			$sub0->columns(array( new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
			$sub0->where->lessThanOrEqualTo("year", $year)
				->where->in('sub_head',$sub1);
		
			$total_debit = $this->getSumbyClass($activity,$region,$location,$starting_date,$ending_date, 'debit', $id);
			$total_credit = $this->getSumbyClass($activity,$region,$location,$starting_date,$ending_date, 'credit', $id);
		endif;
		
		$selectString = $sql->getSqlStringForSqlObject($sub0);
		$balances = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		foreach($balances as $balance);
		return $balance['total_closing_dr'] - $balance['total_closing_cr'] + $total_debit - $total_credit;	
	}
	/**
	 * Calculate closing balance
	 * @param Array $options
	 * @param Int $id
	 * @param Ind $tier
	 * @return Int
	 */
	public function getClosingBalanceAL($organisation,$start_date,$end_date,$id, $tier)
	{				
		$adapter = $this->adapter;  
		$sql = new Sql($adapter);
		
		$year = date('Y', strtotime($start_date));
		$year1 = date('Y', strtotime($start_date)) - 10;
        $starting_date = date('Y-m-d',strtotime('01-01-'.$year1));
		$ending_date = $end_date;
		$sub0 = new Select("accounts_closing_balance");
		//echo $tier; exit;
		if($tier == 1):
			$sub0->columns(array( new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
			$sub0->where->lessThan("year", $year);
			$sub0->where(array('sub_head' => $id));
			$sub0->where(array('organisation_id' => $organisation));


			$total_debit = $this->getSumbySubhead($organisation,$starting_date,$ending_date, 'debit', $id);
			$total_credit = $this->getSumbySubhead($organisation,$starting_date,$ending_date, 'credit', $id); 			
		elseif($tier == 2):
			$sub1 = new Select("accounts_sub_head");
			$sub1->columns(array("id"))
				->where(array("head" => $id));
			
			$sub0->columns(array( new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
			$sub0->where->lessThan("year", $year)
				->where->in('sub_head',$sub1);
			$sub0->where(array('organisation_id' => $organisation));
			
			$total_debit = $this->getSumbyHead($organisation,$starting_date,$ending_date, 'debit', $id);
			$total_credit = $this->getSumbyHead($organisation,$starting_date,$ending_date, 'credit', $id);		
		elseif($tier == 3):
			$sub2 = new Select("accounts_head");
			$sub2->columns(array("id"))
				->where(array("group" => $id));
		
			$sub1 = new Select("accounts_sub_head");
			$sub1->columns(array("id"))
				->where->in('head',$sub2);
				
			$sub0->columns(array( new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
			$sub0->where->lessThan("year", $year)
				->where->in('sub_head',$sub1);
			$sub0->where(array('organisation_id' => $organisation));
			
			$total_debit = $this->getSumbyGroup($organisation,$starting_date,$ending_date, 'debit', $id);
			$total_credit = $this->getSumbyGroup($organisation,$starting_date,$ending_date, 'credit', $id);
		elseif($tier == 4):
			$sub3 = new Select("accounts_group");
			$sub3->columns(array("id"))
				->where(array("class" => $id));
		
			$sub2 = new Select("accounts_head");
			$sub2->columns(array("id"))
				->where->in('group', $sub3);
			
			$sub1 = new Select("accounts_sub_head");
			$sub1->columns(array("id"))
				->where->in('head',$sub2);
			
			$sub0->columns(array( new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
			$sub0->where->lessThan("year", $year)
				->where->in('sub_head',$sub1);
			$sub0->where(array('organisation_id' => $organisation));
		
			$total_debit = $this->getSumbyClass($organisation,$starting_date,$ending_date,'debit', $id);
			$total_credit = $this->getSumbyClass($organisation,$starting_date,$ending_date,'credit', $id);
		endif;
		
		$selectString = $sql->getSqlStringForSqlObject($sub0);
		//echo $selectString; exit;
		$balances = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		
		foreach($balances as $balance);
		return $balance['total_closing_dr'] - $balance['total_closing_cr'] + $total_debit - $total_credit;
         		
	}
		/**
	 * Calculate closing balance
	 * @param Array $options
	 * @param Int $id
	 * @param Ind $tier
	 * @return Int
	 */
	public function getClosingBalanceIE($organisation,$start_date,$end_date,$id, $tier)
	{		
		
		$adapter = $this->adapter;  
		$sql = new Sql($adapter);
		
		$year = date('Y', strtotime($start_date));
        $starting_date = date('Y-m-d',strtotime($start_date));
		$ending_date = $end_date;
		$sub0 = new Select("accounts_closing_balance");
		if($tier == 1):
			$sub0->columns(array( new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
			$sub0->where->lessThan("year", $year);
			$sub0->where(array('sub_head' => $id));
			$sub0->where(array('organisation_id' => $organisation));


			$total_debit = $this->getSumbySubhead($organisation,$starting_date,$ending_date, 'debit', $id);
			$total_credit = $this->getSumbySubhead($organisation,$starting_date,$ending_date, 'credit', $id); 			
		elseif($tier == 2):
			$sub1 = new Select("accounts_sub_head");
			$sub1->columns(array("id"))
				->where(array("head" => $id));
			
			$sub0->columns(array( new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
			$sub0->where->lessThan("year", $year)
				->where->in('sub_head',$sub1);
			$sub0->where(array('organisation_id' => $organisation));
			
			$total_debit = $this->getSumbyHead($organisation,$starting_date,$ending_date, 'debit', $id);
			$total_credit = $this->getSumbyHead($organisation,$starting_date,$ending_date, 'credit', $id);		
		elseif($tier == 3):
			$sub2 = new Select("accounts_head");
			$sub2->columns(array("id"))
				->where(array("group" => $id));
		
			$sub1 = new Select("accounts_sub_head");
			$sub1->columns(array("id"))
				->where->in('head',$sub2);
				
			$sub0->columns(array( new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
			$sub0->where->lessThan("year", $year)
				->where->in('sub_head',$sub1);
			$sub0->where(array('organisation_id' => $organisation));
			
			$total_debit = $this->getSumbyGroup($organisation,$starting_date,$ending_date,'debit', $id);
			$total_credit = $this->getSumbyGroup($organisation,$starting_date,$ending_date,'credit', $id);
		elseif($tier == 4):
			$sub3 = new Select("accounts_group");
			$sub3->columns(array("id"))
				->where(array("class" => $id));
		
			$sub2 = new Select("accounts_head");
			$sub2->columns(array("id"))
				->where->in('group', $sub3);
			
			$sub1 = new Select("accounts_sub_head");
			$sub1->columns(array("id"))
				->where->in('head',$sub2);
			
			$sub0->columns(array( new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
			$sub0->where->lessThan("year", $year)
				->where->in('sub_head',$sub1);
			$sub0->where(array('organisation_id' => $organisation));
		
			$total_debit = $this->getSumbyClass($organisation,$starting_date,$ending_date, 'debit', $id);
			$total_credit = $this->getSumbyClass($organisation,$starting_date,$ending_date, 'credit', $id);
		endif;
		
		$selectString = $sql->getSqlStringForSqlObject($sub0);
		//echo $selectString; exit;
		$balances = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		foreach($balances as $balance);
		return $balance['total_closing_dr'] - $balance['total_closing_cr'] + $total_debit - $total_credit;	
	}
	/**
	 * Return records of given condition array
	 * @param Int $column
	 * @param Int $param
	 * @return Array
	 */
	public function getMaxRow($column,$param)
	{
		$where = ( is_array($param) )? $param: array('td.id' => $param);
		$adapter = $this->adapter;
		
		$sub0 = new Select($this->table);
		$sub0->columns(array(
				$column => new Expression('MAX('.$column.')')
		));
		//$sub0 = $sub0->toArray();
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('td'=>$this->table))
				->join(array('t'=>'accounts_transaction'),'t.id = td.transaction', array('transaction_id' => 'id'))
				->join(array('l'=>'organisation'), 'l.id = td.organisation_id', array('organisation_name', 'organisation_id' => 'id'))
				->join(array('a'=>'activity'), 'a.id = td.activity', array('activity', 'activity_id' => 'id'))
				->join(array('h'=>'accounts_head'), 'h.id = td.head', array('head' => 'code', 'head_id' => 'id'))
				->join(array('sh'=>'accounts_sub_head'), 'sh.id = td.sub_head', array('sub_head' => 'code', 'sub_head_id' => 'id'))
				->join(array('brt'=>'accounts_bank_ref_type'), 'brt.id = td.bank_ref_type', array('bank_ref_type', 'bank_ref_type_id' => 'id'))
				->where($where)
				->where($column);
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		return $results;
	}
	
	/**
	 * Return records of given condition array
	 * @param Stirng $column
	 * @param Int $param
	 * @return Array
	 */
	public function getMinRow($column,$param)
	{
		$where = ( is_array($param) )? $param: array('td.id' => $param);
		$adapter = $this->adapter;
	
		$sub0 = new Select($this->table);
		$sub0->columns(array(
				$column => new Expression('MIN('.$column.')')
		));
		//$sub0 = $sub0->toArray();
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('td'=>$this->table))
				->join(array('t'=>'accounts_transaction'),'t.id = td.transaction', array('transaction_id' => 'id'))
				->join(array('l'=>'Organisation'), 'l.id = td.location', array('organisation_name', 'organisation_id' => 'id'))
				->join(array('a'=>'activity'), 'a.id = td.activity', array('activity', 'activity_id' => 'id'))
				->join(array('h'=>'accounts_head'), 'h.id = td.head', array('head' => 'code', 'head_id' => 'id'))
				->join(array('sh'=>'accounts_sub_head'), 'sh.id = td.sub_head', array('sub_head' => 'code', 'sub_head_id' => 'id'))
				->join(array('brt'=>'accounts_bank_ref_type'), 'brt.id = td.bank_ref_type', array('bank_ref_type', 'bank_ref_type_id' => 'id'))
		->where($where)
		->where($column);
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		return $results;
	}
	
	/**
	 * Return id's|columns'value  which is not present in given array
	 * @param Array $param
	 * @param String column
	 * @return Array
	 */
	public function getNotIn($param, $column='id')
	{
		$param = ( is_array($param) )? $param: array($param);
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = new Select();
		$select->from($this->table)
				->columns(array('id'))
				->where(array('type'=>1))// type = 1 meaning usere inputted data
				->where->notIn($column, $param);
		$selectString = $sql->getSqlStringForSqlObject($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		return $results;
	}
    /**
	 * Return id's|columns'value  which is not present in given array
	 * @param Array $param
	 * @param String column
	 * @return Array
	 */
	public function getNotInDtl($param, $column='id', $where=NULL)
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
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		
		return $results;
	}
	/* get sum by class
	 * @param String $column
	 * @param Array $options
	 * @param String $column
	 * @param int $class
	 * @return int
	 */
	public function getSumbyClassforPresBS($organisation,$starting_date,$ending_date, $column, $class)
	{				
		$sub0 = new Select("accounts_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3")) //committed status
			 ->where->between('voucher_date', $starting_date,$ending_date);
		
		$sub1 = new Select("accounts_group");
		$sub1->columns(array("id"))
			 ->where(array("class" => $class));
		
		$sub2 = new Select("accounts_head");
		$sub2->columns(array("id"))
			 ->where->in("group", $sub1);
		
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->columns(array( new Expression('SUM('.$column.') as total')));   
		if($organisation != -1):
			$select->where(array("organisation_id" => $organisation));
		endif;
		$select->where->in('head', $sub2)
			   ->where->in('transaction', $sub0);

		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit; 
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();           
		
	   foreach ($results as $result):
		  $sum =  $result['total'];
	   endforeach;  
	   return $sum;	    
	}
	/**
	 * get sum by group
	 * @param String $column
	 * @param Array $options
	 * @param String $column
	 * @param Int $group
	 * @return int
	 */
	public function getSumbyGroupforPresBS($organisation,$starting_date,$ending_date, $column, $group)
	{		
		
		$sub0 = new Select("accounts_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3")) //committed status
			 ->where->between('voucher_date', $starting_date, $ending_date);
			 
		$sub1 = new Select("accounts_head");
		$sub1->columns(array("id"))
			 ->where(array("group"=>$group));
		
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->columns(array( new Expression('SUM('.$column.') as total')));
			   
		if($organisation != -1):
			$select->where(array("organisation_id" => $organisation));
		endif;
		$select->where->in('head', $sub1)
			   ->where->in('transaction', $sub0);

		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString;exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();           
		
	   foreach ($results as $result):
		  $sum =  $result['total'];
	   endforeach;  
	   
	   return $sum;	    
	}
	/**
	 * get sum by head
	 * @param String $column
	 * @param Array $options
	 * @param String $column
	 * @param Int $head
	 * @return int
	 */
	public function getSumbyHeadforPresBS($organisation,$starting_date,$ending_date,$column, $head)
	{
		
		$sub0 = new Select("accounts_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3")) //committed status
			 ->where->between('voucher_date', $starting_date, $ending_date);
			 
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->columns(array( new Expression('SUM('.$column.') as total')))
			   ->where(array("head" => $head));
		if($organisation != -1):
			$select->where(array("organisation_id" => $organisation));
		endif;
		$select->where->in('transaction', $sub0);

		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();           
		
	   foreach ($results as $result):
		  $sum =  $result['total'];
	   endforeach;  
	   
	   return $sum;	    
	}
	/**
	 * get sum by subhead
	  * @param String $column
	 * @param Array $options
	 * @param String $column
	 * @param Int $sub_head
	 * @return int
	 */		
	public function getSumbySubheadforPresBS($organisation,$starting_date,$ending_date, $column, $sub_head)
	{	
		$sub0 = new Select("accounts_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3")) //committed status
			 ->where->between('voucher_date', $starting_date, $ending_date);
				
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->columns(array( new Expression('SUM('.$column.') as total')))
			   ->where(array("sub_head" => $sub_head));
		if($organisation != -1):
			$select->where(array("organisation_id" => $organisation));
		endif;
		$select->where->in('transaction', $sub0);
  
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit; 
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();           
		
	   foreach ($results as $result):
		  $sum =  $result['total'];
	   endforeach;  
	   
	   return $sum;	    
	}
	/**
	 * Calculate closing balance
	 * @param Array $options
	 * @param Int $id
	 * @param Ind $tier
	 * @return Int
	 */
	public function getClosingBalanceforPresBS($organisation,$starting_date,$ending_date,$id, $tier)
	{	
		$starting_date = $starting_date;
		$ending_date = $ending_date;
		if($tier == 1):
			$total_debit = $this->getSumbySubheadforPresBS($organisation,$starting_date,$ending_date, 'debit', $id);
			$total_credit = $this->getSumbySubheadforPresBS($organisation,$starting_date,$ending_date, 'credit', $id);  			
		elseif($tier == 2):
		
			$total_debit = $this->getSumbyHeadforPresBS($organisation,$starting_date,$ending_date, 'debit', $id);
			$total_credit = $this->getSumbyHeadforPresBS($organisation,$starting_date,$ending_date, 'credit', $id);			
		elseif($tier == 3):
		
     		$total_debit = $this->getSumbyGroupforPresBS($organisation,$starting_date,$ending_date, 'debit', $id);
			$total_credit = $this->getSumbyGroupforPresBS($organisation,$starting_date,$ending_date, 'credit', $id);
		elseif($tier == 4):
		
			$total_debit = $this->getSumbyClassforPresBS($organisation,$starting_date,$ending_date, 'debit', $id);
			$total_credit = $this->getSumbyClassforPresBS($organisation,$starting_date,$ending_date, 'credit', $id);
		endif;
		return $total_debit - $total_credit;	
	}
	/**
	 * get sum by class
	 * @param String $column
	 * @param Array $options
	 * @param String $column
	 * @param int $class
	 * @return int
	 */
	public function getSumbyClassforPrevBS($organisation,$starting_date,$ending_date, $column, $class)
	{		
		
		$sub0 = new Select("accounts_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3")) //committed status
			 ->where->between('voucher_date', $starting_date,$ending_date);
		
		$sub1 = new Select("accounts_group");
		$sub1->columns(array("id"))
			 ->where(array("class" => $class));
		
		$sub2 = new Select("accounts_head");
		$sub2->columns(array("id"))
			 ->where->in("group", $sub1);
		
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->columns(array( new Expression('SUM('.$column.') as total')));
			   
		if($organisation != -1):
			$select->where(array("organisation_id" => $organisation));
		endif;
		$select->where->in('head', $sub2)
			   ->where->in('transaction', $sub0);

		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit; 
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();           
		
	   foreach ($results as $result):
		  $sum =  $result['total'];
	   endforeach;  
	   return $sum;	    
	}
	/**
	 * get sum by group
	 * @param String $column
	 * @param Array $options
	 * @param String $column
	 * @param Int $group
	 * @return int
	 */
	public function getSumbyGroupforPrevBS($organisation,$starting_date,$ending_date, $column, $group)
	{		
		
		$sub0 = new Select("accounts_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3")) //committed status
			 ->where->between('voucher_date', $starting_date, $ending_date);
			 
		$sub1 = new Select("accounts_head");
		$sub1->columns(array("id"))
			 ->where(array("group"=>$group));
		
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->columns(array( new Expression('SUM('.$column.') as total')));
			   
		if($organisation != -1):
			$select->where(array("organisation_id" => $organisation));
		endif;
		$select->where->in('head', $sub1)
			   ->where->in('transaction', $sub0);

		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString;exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();           
		
	   foreach ($results as $result):
		  $sum =  $result['total'];
	   endforeach;  
	   
	   return $sum;	    
	}
	/**
	 * get sum by head
	 * @param String $column
	 * @param Array $options
	 * @param String $column
	 * @param Int $head
	 * @return int
	 */
	public function getSumbyHeadforPrevBS($organisation,$starting_date,$ending_date,$column, $head)
	{
		
		$sub0 = new Select("accounts_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3")) //committed status
			 ->where->between('voucher_date', $starting_date, $ending_date);
			 
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->columns(array( new Expression('SUM('.$column.') as total')))
			   ->where(array("head" => $head));
		if($organisation != -1):
			$select->where(array("organisation_id" => $organisation));
		endif;
		$select->where->in('transaction', $sub0);

		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();           
		
	   foreach ($results as $result):
		  $sum =  $result['total'];
	   endforeach;  
	   
	   return $sum;	    
	}
	/**
	 * get sum by subhead
	  * @param String $column
	 * @param Array $options
	 * @param String $column
	 * @param Int $sub_head
	 * @return int
	 */		
	public function getSumbySubheadforPrevBS($organisation,$starting_date,$ending_date, $column, $sub_head)
	{
		$sub0 = new Select("accounts_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3")) //committed status
			 ->where->between('voucher_date', $starting_date, $ending_date);
				
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->columns(array( new Expression('SUM('.$column.') as total')))
			   ->where(array("sub_head" => $sub_head));
		if($organisation != -1):
			$select->where(array("organisation_id" => $organisation));
		endif;
		$select->where->in('transaction', $sub0);

		$selectString = $sql->getSqlStringForSqlObject($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();           
		
	   foreach ($results as $result):
		  $sum =  $result['total'];
	   endforeach;  
	   
	   return $sum;	    
	}
	/**
	 * Calculate closing balance
	 * @param Array $options
	 * @param Int $id
	 * @param Ind $tier
	 * @return Int
	 */
	public function getClosingBalanceforPrevBS($organisation,$starting_date,$ending_date, $id, $tier)
	{	
		$starting_date = $starting_date;
		$ending_date = $ending_date;
		if($tier == 1):
		
			$total_debit = $this->getSumbySubheadforPrevBS($organisation,$starting_date,$ending_date, 'debit', $id);
			$total_credit = $this->getSumbySubheadforPrevBS($organisation,$starting_date,$ending_date, 'credit', $id);  			
		elseif($tier == 2):
		
			$total_debit = $this->getSumbyHeadforPrevBS($organisation,$starting_date,$ending_date,'debit', $id);
			$total_credit = $this->getSumbyHeadforPrevBS($organisation,$starting_date,$ending_date,'credit', $id);			
		elseif($tier == 3):
     		$total_debit = $this->getSumbyGroupforPrevBS($organisation,$starting_date,$ending_date,'debit', $id);
			$total_credit = $this->getSumbyGroupforPrevBS($organisation,$starting_date,$ending_date,'credit', $id);
		elseif($tier == 4):
			$total_debit = $this->getSumbyClassforPrevBS($organisation,$starting_date,$ending_date, 'debit', $id);
			$total_credit = $this->getSumbyClassforPrevBS($organisation,$starting_date,$ending_date,'credit', $id);
		endif;
		return $total_debit - $total_credit;	
	}
    /**
	 * Get cash Report
	 * @param Int $id
	 * @param date
	 * @return Array
	 */
	public function getCash($voucher_type,$start_date,$end_date,$where)
	{
		
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('td'=>$this->table))
				->join(array('t'=>'accounts_transaction'),'t.id = td.transaction', array('voucher_type','voucher_no','voucher_date','status','remark'))
		        ->where($where)
		        ->where(array('t.status'=>3))
				->order(array('voucher_date ASC'))	
				->order(array('t.created ASC'))	
			    ->where(array('voucher_type'=>$voucher_type))
				->where->between('voucher_date', $start_date, $end_date);	
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		return $results;
	}
   /**
	 * Get bank Report
	 * @param Int $id
	 * @param date
	 * @return Array
	 */
	public function getBank($where,$bank_account)
	{		
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('td'=>$this->table))
			->join(array('t'=>'accounts_transaction'),'t.id = td.transaction', array('cheque_id','voucher_no','voucher_date','status','remark'));
		$select->where(array('td.sub_head'=>$bank_account));
		$select->where($where);
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		return $results;
	}
	/**
	 * get sum by class
	 * @param String $column
	 * @param Array $options
	 * @param String $column
	 * @param int $class
	 * @return int
	 */
	public function getSumbyClassforPresPLS($organisation,$starting_date,$ending_date, $column, $class)
	{		
		
		$sub0 = new Select("accounts_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3")) //committed status
			 ->where->between('voucher_date', $starting_date,$ending_date);
		
		$sub1 = new Select("accounts_group");
		$sub1->columns(array("id"))
			 ->where(array("class" => $class));
		
		$sub2 = new Select("accounts_head");
		$sub2->columns(array("id"))
			 ->where->in("group", $sub1);
		
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->columns(array( new Expression('SUM('.$column.') as total')));
			   
		if($organisation != -1):
			$select->where(array("organisation_id" => $organisation));
		endif;
		$select->where->in('head', $sub2)
			   ->where->in('transaction', $sub0);

		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit; 
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();           
		
	   foreach ($results as $result):
		  $sum =  $result['total'];
	   endforeach;  
	   return $sum;	    
	}
	/**
	 * get sum by group
	 * @param String $column
	 * @param Array $options
	 * @param String $column
	 * @param Int $group
	 * @return int
	 */
	public function getSumbyGroupforPresPLS($organisation,$starting_date,$ending_date, $column, $group)
	{		
		
		$sub0 = new Select("accounts_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3")) //committed status
			 ->where->between('voucher_date', $starting_date, $ending_date);
			 
		$sub1 = new Select("accounts_head");
		$sub1->columns(array("id"))
			 ->where(array("group"=>$group));
		
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->columns(array( new Expression('SUM('.$column.') as total')));
			   
		if($organisation != -1):
			$select->where(array("organisation_id" => $organisation));
		endif;
		$select->where->in('head', $sub1)
			   ->where->in('transaction', $sub0);

		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString;exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();           
		
	   foreach ($results as $result):
		  $sum =  $result['total'];
	   endforeach;  
	   
	   return $sum;	    
	}
	
	/**
	 * get sum by group
	 * @param String $column
	 * @param Array $options
	 * @param String $column
	 * @param Int $group
	 * @return int
	 */
	public function getSumbyGroupforPrevPLS($activity,$region,$location,$starting_date,$ending_date, $column, $group)
	{		
		
		$sub0 = new Select("fa_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3")) //committed status
			 ->where->between('voucher_date', $starting_date, $ending_date);
			 
		$sub1 = new Select("fa_head");
		$sub1->columns(array("id"))
			 ->where(array("group"=>$group));
		
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->columns(array( new Expression('SUM('.$column.') as total')));
			   
		if($activity != -1):
			$select->where(array("activity" => $activity));
		endif;
		if($region != -1):
			if($location != -1):
				$select->where(array("location" => $location));
			else:
				$sub_loc = new Select("sys_location");
				$sub_loc ->columns(array("id"))
						 ->where(array("region" => $region));
				$select->where->in("location", $sub_loc);
			endif;
		endif;
		$select->where->in('head', $sub1)
			   ->where->in('transaction', $sub0);

		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString;exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();           
		
	   foreach ($results as $result):
		  $sum =  $result['total'];
	   endforeach;  
	   
	   return $sum;	    
	}
	
	/**
	 * get sum by head
	 * @param String $column
	 * @param Array $options
	 * @param String $column
	 * @param Int $head
	 * @return int
	 */
	public function getSumbyHeadforPresPLS($organisation,$starting_date,$ending_date,$column, $head)
	{
		
		$sub0 = new Select("accounts_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3")) //committed status
			 ->where->between('voucher_date', $starting_date, $ending_date);
			 
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->columns(array( new Expression('SUM('.$column.') as total')))
			   ->where(array("head" => $head));
		if($organisation != -1):
			$select->where(array("organisation_id" => $organisation));
		endif;
		$select->where->in('transaction', $sub0);

		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();           
		
	   foreach ($results as $result):
		  $sum =  $result['total'];
	   endforeach;  
	   
	   return $sum;	    
	}
	/**
	 * get sum by head
	 * @param String $column
	 * @param Array $options
	 * @param String $column
	 * @param Int $head
	 * @return int
	 */
	public function getSumbyHeadforPrevPLS($activity,$region,$location,$starting_date,$ending_date,$column, $head)
	{
		
		$sub0 = new Select("fa_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3")) //committed status
			 ->where->between('voucher_date', $starting_date, $ending_date);
			 
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->columns(array( new Expression('SUM('.$column.') as total')))
			   ->where(array("head" => $head));
		if($activity != -1):
			$select->where(array("activity" => $activity));
		endif;
		if($region != -1):
			if($location != -1):
				$select->where(array("location" => $location));
			else:
				$sub_loc = new Select("sys_location");
				$sub_loc ->columns(array("id"))
						 ->where(array("region" => $region));
				$select->where->in("location", $sub_loc);
			endif;
		endif;
		$select->where->in('transaction', $sub0);

		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();           
		
	   foreach ($results as $result):
		  $sum =  $result['total'];
	   endforeach;  
	   
	   return $sum;	    
	}
	/**
	 * get sum by subhead
	  * @param String $column
	 * @param Array $options
	 * @param String $column
	 * @param Int $sub_head
	 * @return int
	 */		
	public function getSumbySubheadforPresPLS($organisation,$starting_date,$ending_date, $column, $sub_head)
	{
		$sub0 = new Select("accounts_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3")) //committed status
			 ->where->between('voucher_date', $starting_date, $ending_date);
				
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->columns(array( new Expression('SUM('.$column.') as total')))
			   ->where(array("sub_head" => $sub_head));
		if($organisation != -1):
			$select->where(array("organisation_id" => $organisation));
		endif;
		$select->where->in('transaction', $sub0);

		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();           
		
	   foreach ($results as $result):
		  $sum =  $result['total'];
	   endforeach;  
	   
	   return $sum;	    
	}
	
	/**
	 * Calculate opening balance
	 * @param Array $options
	 * @param Int $id
	 * @param Ind $tier
	 * @return Int
	 */
	public function getOpeningBalanceforBSPLS($start_date, $id, $tier)
	{	 
		$adapter = $this->adapter;  
		$sql = new Sql($adapter);
		$year = date('Y', strtotime($start_date));
		
		$starting_date = date('Y-m-d', strtotime('01-01-'.$year));
		$ending_date = $start_date;
		
		$filter = array(
			'start_date' => $starting_date,
			'end_date'   => $ending_date,
			'activity'   => $activity,
			'region'     => $region,
			'location'   => $location,
		);
		
		$sub0 = new Select("fa_closing_balance");
		
		if($tier == 1):
			$sub0->columns(array( new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
			$sub0->where(array("year" => $year-2, 'id' => $id));			
			$total_debit = $this->getSumbySubheadforBSPLS($filter, 'debit', $id);
			$total_credit = $this->getSumbySubheadforBSPLS($filter, 'credit', $id);  			
		elseif($tier == 2):
			$sub1 = new Select("fa_sub_head");
			$sub1->columns(array("id"))
				->where(array("head"=>$id));
			
			$sub0->columns(array( new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
			$sub0->where(array("year" => $year-2))
				->where->in('sub_head',$sub1);
			
			$total_debit = $this->getSumbyHeadforBSPLS($filter, 'debit', $id);
			$total_credit = $this->getSumbyHeadforBSPLS($filter, 'credit', $id);			
		elseif($tier == 3):
			$sub2 = new Select("fa_head");
			$sub2->columns(array("id"))
				->where(array("group" => $id));
		
			$sub1 = new Select("fa_sub_head");
			$sub1->columns(array("id"))
				->where->in('head',$sub2);
				
			$sub0->columns(array( new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
			$sub0->where(array("year" => $year-2))
				->where->in('sub_head',$sub1);
			
			$total_debit = $this->getSumbyGroupforBSPLS($filter, 'debit', $id);
			$total_credit = $this->getSumbyGroupforBSPLS($filter, 'credit', $id);
		elseif($tier == 4):
			$sub3 = new Select("fa_group");
			$sub3->columns(array("id"))
				->where(array("class" => $id));
		
			$sub2 = new Select("fa_head");
			$sub2->columns(array("id"))
				->where->in('group', $sub3);
			
			$sub1 = new Select("fa_sub_head");
			$sub1->columns(array("id"))
				->where->in('head',$sub2);
			
			$sub0->columns(array( new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
			$sub0->where(array("year" => $year-2))
				->where->in('sub_head',$sub1);
		
			$total_debit = $this->getSumbyClassforBSPLS($filter, 'debit', $id);
			
			$total_credit = $this->getSumbyClassforBSPLS($filter, 'credit', $id);
		endif;
		
		$selectString = $sql->getSqlStringForSqlObject($sub0);	
        //echo $selectString; exit;		
		$balances = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();	
		foreach($balances as $balance); 
		return $balance['total_closing_dr'] - $balance['total_closing_cr'] + $total_debit - $total_credit;				
	}
	/**
	 * Calculate closing balance
	 * @param Array $options
	 * @param Int $id
	 * @param Ind $tier
	 * @return Int
	 */
	public function getClosingBalanceforPresPLS($organisation,$starting_date,$ending_date, $id, $tier)
	{	
		$starting_date = $starting_date;
		$ending_date = $ending_date;
		if($tier == 1):
			$total_debit = $this->getSumbySubheadforPresPLS($organisation,$starting_date,$ending_date, 'debit', $id);
			$total_credit = $this->getSumbySubheadforPresPLS($organisation,$starting_date,$ending_date, 'credit', $id);  			
		elseif($tier == 2):
		
			$total_debit = $this->getSumbyHeadforPresPLS($organisation,$starting_date,$ending_date, 'debit', $id);
			$total_credit = $this->getSumbyHeadforPresPLS($organisation,$starting_date,$ending_date, 'credit', $id);			
		elseif($tier == 3):
     		$total_debit = $this->getSumbyGroupforPresPLS($organisation,$starting_date,$ending_date, 'debit', $id);
			$total_credit = $this->getSumbyGroupforPresPLS($organisation,$starting_date,$ending_date, 'credit', $id);
		elseif($tier == 4):
			$total_debit = $this->getSumbyClassforPresPLS($organisation,$starting_date,$ending_date, 'debit', $id);
			$total_credit = $this->getSumbyClassforPresPLS($organisation,$starting_date,$ending_date, 'credit', $id);
		endif;
		return $total_debit - $total_credit;	
	}
	/**
	 * Calculate closing balance
	 * @param Array $options
	 * @param Int $id
	 * @param Ind $tier
	 * @return Int
	 */
	public function getClosingBalanceforPrevPLS($organisation,$starting_date,$ending_date, $id, $tier)
	{	
		$starting_date = $starting_date;
		$ending_date = $ending_date;
		if($tier == 1):
		
			$total_debit = $this->getSumbySubheadforPresPLS($organisation,$starting_date,$ending_date, 'debit', $id);
			$total_credit = $this->getSumbySubheadforPresPLS($organisation,$starting_date,$ending_date, 'credit', $id);  			
		elseif($tier == 2):
		
			$total_debit = $this->getSumbyHeadforPresPLS($organisation,$starting_date,$ending_date, 'debit', $id);
			$total_credit = $this->getSumbyHeadforPresPLS($organisation,$starting_date,$ending_date, 'credit', $id);			
		elseif($tier == 3):
     		$total_debit = $this->getSumbyGroupforPresPLS($organisation,$starting_date,$ending_date, 'debit', $id);
			$total_credit = $this->getSumbyGroupforPresPLS($organisation,$starting_date,$ending_date, 'credit', $id);
		elseif($tier == 4):
			$total_debit = $this->getSumbyClassforPresPLS($organisation,$starting_date,$ending_date, 'debit', $id);
			$total_credit = $this->getSumbyClassforPresPLS($organisation,$starting_date,$ending_date, 'credit', $id);
		endif;
		return $total_debit - $total_credit;	
	}
	/**
	 * Cash Book Head For Pling Head Office
	 * Return records of given condition array
	 * @param Stirng $column
	 * @param Int $param
	 * @return Array
	 */
	public function getCashBookHead($param)
	{
        $where = ( is_array($param) )? $param: array('td.id' => $param);
	    $adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('td'=>$this->table))
				->join(array('h'=>'accounts_head'), 'h.id = td.head', array('head' => 'code'));
		$select->columns(array(
				'max' => new Expression('MAX(td.id)')
		));
		$select->where($where);
		$select->where->notIn('h.id', array(4));
		
		$selectString = $sql->getSqlStringForSqlObject($select);
	//	echo $selectString;exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		foreach ($results as $result):
			$columns =  $result['head'];
		endforeach;
		 
		return $columns;
	}
	/**
	 * Calculate opening balance
	 * @param Array $options
	 * @param Int $id
	 * @param Ind $tier
	 * @return Int
	 */
	public function getOpeningBalanceforBA($organisation,$start_date, $subhead_id)
	{		
		$adapter = $this->adapter;  
		$sql = new Sql($adapter);
		
		$year = date('Y', strtotime($start_date));
		$sub0 = new Select("accounts_closing_balance");
			$sub0->columns(array( new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
			$sub0->where->lessThanOrEqualTo("year", $year);
			$sub0->where(array('sub_head' => $subhead_id));
			$sub0->where(array('organisation_id' => $organisation));


			$total_debit = $this->getSumbySubheadforBA($organisation,$start_date, 'debit', $subhead_id);
			$total_credit = $this->getSumbySubheadforBA($organisation,$start_date, 'credit', $subhead_id); 			 
			
		$selectString = $sql->getSqlStringForSqlObject($sub0);
		//echo $selectString; exit;
		$balances = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		foreach($balances as $balance);
		return $balance['total_closing_dr'] - $balance['total_closing_cr'] + $total_debit - $total_credit;	
	}
	/**
	 * Calculate opening balance
	 * @param Array $options
	 * @param Int $id
	 * @param Ind $tier
	 * @return Int
	 */
	public function getOpeningBalanceCBforBRS($organisation,$end_date,$subhead_id)
	{		
		$adapter = $this->adapter;  
		$sql = new Sql($adapter);
		
		$year = date('Y', strtotime($end_date));
		$sub0 = new Select("accounts_closing_balance");
			$sub0->columns(array( new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
			$sub0->where->LessThanOrEqualTo("year", $year);
			$sub0->where(array('sub_head' => $subhead_id));
			$sub0->where(array('organisation_id' => $organisation));


			$total_debit = $this->getSumbySubheadCBforBRS($organisation,$end_date, 'debit', $subhead_id);
			$total_credit = $this->getSumbySubheadCBforBRS($organisation,$end_date, 'credit', $subhead_id); 			 
			
		$selectString = $sql->getSqlStringForSqlObject($sub0);
		//echo $selectString; exit;
		$balances = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		foreach($balances as $balance);
		return $balance['total_closing_dr'] - $balance['total_closing_cr'] + $total_debit - $total_credit;	
	}
	/**
	 * Calculate opening balance
	 * @param Array $options
	 * @param Int $id
	 * @param Ind $tier
	 * @return Int
	 */
	public function getOpeningBalanceBSforBRS($organisation,$end_date,$subhead_id)
	{		
		$adapter = $this->adapter;  
		$sql = new Sql($adapter);
		
		$year = date('Y', strtotime($end_date));
		$sub0 = new Select("accounts_closing_balance");
			$sub0->columns(array( new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
			$sub0->where->LessThanOrEqualTo("year", $year);
			$sub0->where(array('sub_head' => $subhead_id));
			$sub0->where(array('organisation_id' => $organisation));


			$total_debit = $this->getSumbySubheadCBforBRS($organisation,$end_date, 'debit', $subhead_id);
			$total_credit = $this->getSumbySubheadCBforBRS($organisation,$end_date, 'credit', $subhead_id); 			 
			
		$selectString = $sql->getSqlStringForSqlObject($sub0);
		//echo $selectString; exit;
		$balances = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		foreach($balances as $balance);
		return $balance['total_closing_dr'] - $balance['total_closing_cr'] + $total_debit - $total_credit;	
	}
	/**
	 * Calculate opening balance
	 * @param Array $options
	 * @param Int $id
	 * @param Ind $tier
	 * @return Int
	 */
	public function getBudgetforBRS($organisation,$end_date,$subhead_id)
	{		
		$adapter = $this->adapter;  
		$sql = new Sql($adapter);
		
		$year = date('Y', strtotime($end_date));
		$start_date = date('Y-01-t');
		$sub0 = new Select("accounts_closing_balance");
			$sub0->columns(array( new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
			$sub0->where->LessThanOrEqualTo("year", $year);
			$sub0->where(array('sub_head' => $subhead_id));
			$sub0->where(array('organisation_id' => $organisation));


			$total_debit = $this->getSumbySubheadBudgetforBRS($organisation,$end_date, 'debit', $subhead_id);
			$total_credit = $this->getSumbySubheadBudgetforBRS($organisation,$end_date,'credit', $subhead_id); 			 
			
		$selectString = $sql->getSqlStringForSqlObject($sub0);
		//echo $selectString; exit;
		$balances = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		foreach($balances as $balance);
		return $balance['total_closing_dr'] - $balance['total_closing_cr'] + $total_debit - $total_credit;	
	}
	/**
	 * Calculate opening balance
	 * @param Array $options
	 * @param Int $id
	 * @param Ind $tier
	 * @return Int
	 */
	public function getFundWithDrawnforBRS($organisation,$start_date,$end_date,$subhead_id)
	{		
		$adapter = $this->adapter;  
		$sql = new Sql($adapter);
		
		$year = date('Y', strtotime($end_date));
		$start_date = date('Y-01-t');
		$sub0 = new Select("accounts_closing_balance");
			$sub0->columns(array( new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
			$sub0->where->LessThanOrEqualTo("year", $year);
			$sub0->where(array('sub_head' => $subhead_id));
			$sub0->where(array('organisation_id' => $organisation));


			$total_debit = $this->getSumbySubheadFundWithDrawnforBRS($organisation,$start_date,$end_date, 'debit', $subhead_id);
			$total_credit = $this->getSumbySubheadFundWithDrawnforBRS($organisation,$start_date,$end_date,'credit', $subhead_id); 			 
			
		$selectString = $sql->getSqlStringForSqlObject($sub0);
		//echo $selectString; exit;
		$balances = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		foreach($balances as $balance);
		return $balance['total_closing_dr'] - $balance['total_closing_cr'] + $total_debit - $total_credit;	
	}
	/**
	 * Calculate opening balance
	 * @param Array $options
	 * @param Int $id
	 * @param Ind $tier
	 * @return Int
	 */
	public function getOpeningBalanceforCA($organisation,$end_date, $subhead_id)
	{		
		
		$adapter = $this->adapter;  
		$sql = new Sql($adapter);
		
		$year = date('Y', strtotime($end_date));
		$sub0 = new Select("accounts_closing_balance");
		$sub0->columns(array( new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
		$sub0->where->lessThan("year", $year);
		$sub0->where(array('sub_head' => $subhead_id));
		$sub0->where(array('organisation_id' => $organisation));

		$total_debit = $this->getSumbySubheadforCA($organisation,$end_date, 'debit', $subhead_id);
		$total_credit = $this->getSumbySubheadforCA($organisation,$end_date, 'credit', $subhead_id); 			 
			
		$selectString = $sql->getSqlStringForSqlObject($sub0);
		//echo $selectString; exit;
		$balances = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		foreach($balances as $balance);
		return $balance['total_closing_dr'] - $balance['total_closing_cr'] + $total_debit - $total_credit;	
	}
	/**
	 * get sum by subhead
	 * @param String $column
	 * @param Array $options
	 * @param String $column
	 * @param Int $sub_head
	 * @return int
	 */		
	public function getSumbySubheadforBA($organisation,$start_date, $column, $sub_head)
	{		
		$sub0 = new Select("accounts_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3")) //committed status
			 ->where->lessThan('voucher_date',$start_date);
				
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->columns(array( new Expression('SUM('.$column.') as total')))
			   ->where(array("sub_head" => $sub_head))
			   ->where(array("organisation_id" =>$organisation));
		$select->where->in('transaction', $sub0);
        $select->order(array('transaction ASC'));
		$select->order(array('created ASC'));
		$selectString = $sql->getSqlStringForSqlObject($select);
	    //echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();           
		
	   foreach ($results as $result):
		  $sum =  $result['total'];
	   endforeach;  
	   
	   return $sum;	    
	}
	/**
	 * get sum by subhead
	 * @param String $column
	 * @param Array $options
	 * @param String $column
	 * @param Int $sub_head
	 * @return int
	 */		
	public function getSumbySubheadCBforBRS($organisation,$end_date, $column, $sub_head)
	{	
        	
		$sub0 = new Select(array('t'=>"accounts_transaction")); 		//$sub0 ->join(array('cd'=>'accounts_cheque_book_dtls'),'t.cheque_detail_id = cd.id', array());
		$sub0->columns(array("id"))
			 ->where(array("t.status" => "3")) //committed status
			 ->where->lessThan('voucher_date',$end_date);
				
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->columns(array( new Expression('SUM('.$column.') as total')))
			   ->where(array("sub_head" => $sub_head))
			   ->where(array("organisation_id" =>$organisation));
		$select->where->in('transaction', $sub0);
        $select->order(array('transaction ASC'));
		$select->order(array('created ASC'));
		$selectString = $sql->getSqlStringForSqlObject($select);
	    //echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();           
		
	   foreach ($results as $result):
		  $sum =  $result['total'];
	   endforeach;  
	   
	   return $sum;	    
	}
	/**
	 * get sum by subhead
	 * @param String $column
	 * @param Array $options
	 * @param String $column
	 * @param Int $sub_head
	 * @return int
	 */		
	public function getSumbySubheadBSforBRS($organisation,$end_date, $column, $sub_head)
	{	
        	
		$sub0 = new Select(array('t'=>"accounts_transaction")); 		
		$sub0 ->join(array('cd'=>'accounts_cheque_book_dtls'),'t.cheque_detail_id = cd.id', array());
		$sub0->columns(array("id"))
			 ->where(array("t.status" => "3")) //committed status
			 ->where->lessThan('encashment_date',$end_date);
				
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->columns(array( new Expression('SUM('.$column.') as total')))
			   ->where(array("sub_head" => $sub_head))
			   ->where(array("organisation_id" =>$organisation));
		$select->where->in('transaction', $sub0);
        $select->order(array('transaction ASC'));
		$select->order(array('created ASC'));
		$selectString = $sql->getSqlStringForSqlObject($select);
	    //echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();           
		
	   foreach ($results as $result):
		  $sum =  $result['total'];
	   endforeach;  
	   
	   return $sum;	    
	}
	/**
	 * get sum by subhead
	 * @param String $column
	 * @param Array $options
	 * @param String $column
	 * @param Int $sub_head
	 * @return int
	 */		
	public function getSumbySubheadFundWithDrawnforBRS($organisation,$start_date,$end_date, $column, $sub_head)
	{	
        	
		$sub0 = new Select(array('t'=>"accounts_transaction")); 		//$sub0 ->join(array('cd'=>'accounts_cheque_book_dtls'),'t.cheque_detail_id = cd.id', array());
		$sub0->columns(array("id"))
			 ->where(array("t.status" => "3")) //committed status
			 ->where->between('voucher_date',$start_date,$end_date);
				
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->columns(array( new Expression('SUM('.$column.') as total')))
			   ->where(array("sub_head" => $sub_head))
			   ->where(array("organisation_id" =>$organisation));
		$select->where->in('transaction', $sub0);
        $select->order(array('transaction ASC'));
		$select->order(array('created ASC'));
		$selectString = $sql->getSqlStringForSqlObject($select);
	    //echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();           
		
	   foreach ($results as $result):
		  $sum =  $result['total'];
	   endforeach;  
	   
	   return $sum;	    
	}
	/**
	 * get sum by subhead
	 * @param String $column
	 * @param Array $options
	 * @param String $column
	 * @param Int $sub_head
	 * @return int
	 */		
	public function getSumbySubheadBudgetforBRS($organisation,$end_date,$column, $sub_head)
	{	
        	
		$sub0 = new Select(array('t'=>"accounts_transaction"));
		$sub0->columns(array("id"))
			 ->where(array("t.status" => "3")) //committed status
			 ->where->lessThan('voucher_date',$end_date);
				
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->columns(array( new Expression('SUM('.$column.') as total')))
			   ->where(array("sub_head" => $sub_head))
			   ->where(array("organisation_id" =>$organisation));
		$select->where->in('transaction', $sub0);
        $select->order(array('transaction ASC'));
		$select->order(array('created ASC'));
		$selectString = $sql->getSqlStringForSqlObject($select);
	    //echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();           
		
	   foreach ($results as $result):
		  $sum =  $result['total'];
	   endforeach;  
	   
	   return $sum;	    
	}
	/**
	 * get sum by subhead
	 * @param String $column
	 * @param Array $options
	 * @param String $column
	 * @param Int $sub_head
	 * @return int
	 */		
	public function getSumbySubheadforCA($organisation,$start_date, $column, $sub_head)
	{		
		$sub0 = new Select("accounts_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3")) //committed status
			 ->where->lessThan('voucher_date',$start_date);
				
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->columns(array( new Expression('SUM('.$column.') as total')))
			   ->where(array("sub_head" => $sub_head))
			   ->where(array("organisation_id" =>$organisation));
		$select->where->in('transaction', $sub0);
        $select->order(array('transaction ASC'));
		$select->order(array('created ASC'));
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();           
		
	   foreach ($results as $result):
		  $sum =  $result['total'];
	   endforeach;  
	   
	   return $sum;	    
	}
	/**
	 * get sum by subhead for BRS
	  * @param String $column
	 * @param Array $options
	 * @param String $column
	 * @param Int $sub_head
	 * @return int
	 */		
	public function getAmountDebitedCB($organisation,$start_date,$end_date,$column,$where)
	{
		$sub0 = new Select("accounts_transaction");
		$sub0->columns(array("id"))
			 ->where(array("status" => "3")) //committed status
			 ->where->between('voucher_date', $start_date, $end_date);
				
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->columns(array( new Expression('SUM('.$column.') as total')))
			   ->where($where);
			    if($organisation != -1):
				    $select->where(array("organisation_id" => $organisation));
			    endif;
		$select->where->in('transaction', $sub0);
        $select->order(array('transaction ASC'));
		$select->order(array('created ASC'));
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString;  exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();           
		
	   foreach ($results as $result):
		  $sum =  $result['total'];
	   endforeach;  
	   
	   return $sum;	    
	}
	/**
	 * get sum by subhead for BRS
	  * @param String $column
	 * @param Array $options
	 * @param String $column
	 * @param Int $sub_head
	 * @return int
	 */		
	public function getAmountDebitedBS($organisation,$start_date,$end_date,$column,$where)
	{
		$sub0 = new Select(array('t'=>"accounts_transaction")); 		
		$sub0 ->join(array('cd'=>'accounts_cheque_book_dtls'),'t.cheque_detail_id = cd.id', array());
		$sub0->columns(array("id"))
			 ->where(array("t.status" => "3")) //committed status
			 ->where->between('encashment_date', $start_date, $end_date);
				
		$adapter = $this->adapter;  	 
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
			   ->columns(array( new Expression('SUM('.$column.') as total')))
			   ->where($where);
			    if($organisation != -1):
				    $select->where(array("organisation_id" => $organisation));
			    endif;
		$select->where->in('transaction', $sub0);
        $select->order(array('transaction ASC'));
		$select->order(array('created ASC'));
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString;  exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();           
		
	   foreach ($results as $result):
		  $sum =  $result['total'];
	   endforeach;  
	   
	   return $sum;	    
	}
	/* Get Cash Flow Report
	 * @param Int $id
	 * @param date
	 * @return Array
	 */
	public function getCashFlow($where)
	{		
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('td'=>$this->table))
			->join(array('t'=>'accounts_transaction'),'t.id = td.transaction', array('cheque_detail_id','voucher_no','voucher_date','status','remark'));
		$select->where($where);
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		return $results;
	}
	/* Get Bank Statement
	 * @param Int $id
	 * @param date
	 * @return Array
	 */
	public function getBankStatement($organisation,$start_date,$end_date,$where)
	{		
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('td'=>$this->table))
			->join(array('t'=>'accounts_transaction'),'t.id = td.transaction', array('cheque_detail_id','voucher_no','voucher_date','status','remark'));
        if($organisation != -1):
		    $select->where(array("td.organisation_id" => $organisation));
		endif;		
		$select->where->between('voucher_date', $start_date, $end_date);
		$select->where($where);
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		return $results;
	}
}

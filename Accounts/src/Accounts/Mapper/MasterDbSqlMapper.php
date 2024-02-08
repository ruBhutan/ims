<?php

namespace Accounts\Mapper;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\where;
use Zend\Db\Sql\Having;
use Zend\Db\Sql\Group;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Db\Adapter\Adapter;
//use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\ResultSet\HydratingResultSet;


class MasterDbSqlMapper implements MasterMapperInterface {

    /**
     * @var \Zend\Db\Adapter\AdapterInterface
     *
     */
    protected $dbAdapter;

    /*
     * @var \Zend\Stdlib\Hydrator\HydratorInterface
     */
    protected $hydrator;

    /**
     * @param AdapterInterface $dbAdapter
     */
    public function __construct(AdapterInterface $dbAdapter, HydratorInterface $hydrator) {
        $this->dbAdapter = $dbAdapter;
        $this->hydrator = $hydrator;
        $this->resultSetPrototype = new HydratingResultSet();
    }

    public function getLoginEmpDetailfrmUsername($username) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('ed' => 'employee_details'))
            ->where(array('ed.emp_id' => $username));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return current($resultSet->initialize($result)->toArray());
    }

    public function getTableData($tablename) { 
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        if($tablename == 'accounts_group'){
            $select->from(array('ag' => $tablename))
                   ->join(array('ac' => 'accounts_class'), 'ac.id=ag.class', array('ac_name' => 'name'));
        }else if($tablename == 'accounts_head'){
            $select->from(array('ah' => $tablename))
                   ->join(array('ag' => 'accounts_group'), 'ag.id=ah.group', array('ag_name' => 'name'));
        }
        else{
            $select->from($tablename);
        }

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result)->toArray();
    }

    public function saveupdateData($table_name, $data) {
        $sql = new Sql($this->dbAdapter);

        if ( isset($data['id']) && $data['id'] > 0 ) {
            $update = new Update($table_name);
            $update->set($data);
            $update->where(array('id = ?' => $data['id']));
            $stmt = $sql->prepareStatementForSqlObject($update);
            $result = $stmt->execute();

            $last_inserted_id = $data['id'];
        } else {
            $insert = new Insert($table_name);
            $insert->values($data);
            $stmt = $sql->prepareStatementForSqlObject($insert);
            $result = $stmt->execute();

            $last_inserted_id = $result->getGeneratedValue();
        }

        return $last_inserted_id;
    }

    public function getDatabyParam($tablename, $params, $column = null) 
    {     
        $sql = new Sql($this->dbAdapter);

        if($tablename == 'accounts_cheque_book'){
            $where = (is_array($params)) ? $params : array('organisation_id' => $params);
        }
        $where = (is_array($params)) ? $params : array('id' => $params);
        
        $select = $sql->select();

        $select->from($tablename);

        if ( $column ) {
            $select->columns(array($column));
        }

        if ( $params ) {
            $select->where($where);
        }

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $results = $resultSet->initialize($result)->toArray();

        if ( $tablename === 'accounts_cheque_book_dtls' ) {
            foreach ( $results as $result ):
                $columns = $result[$column];
            endforeach;

            return $columns;
        }
//var_dump($results); die();
        return $results;
    }

    public function getSubheadData($tablename, $data) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('sh' => $tablename))
            ->join(array('h' => 'accounts_head'), 'h.id=sh.head', array('head' => 'code', 'head_id' => 'id'))
            ->join(array('g' => 'accounts_group'), 'g.id=h.group', array('group' => 'code', 'group_id' => 'id'))
            ->join(array('c' => 'accounts_class'), 'c.id=g.class', array('class' => 'code', 'class_id' => 'id'))
            ->order(array('sh.code ASC'));

        if ( $data['class'] != '-1' ) {
            $select->where(array('c.id' => $data['class']));
        }
        if ( $data['group'] != '-1' ) {
            $select->where(array('g.id' => $data['group']));
        }
        if ( $data['head'] != '-1' ) {
            $select->where(array('h.id' => $data['head']));
        }
        if ( $data['group'] == '-1' ) {
            $select->limit(100);
        }

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result)->toArray();
    }

    /**
     *
     * get Subhead present in transactions details
     * @param Date $start_date
     * @param Date $end_date
     *
     * */
    public function getTransactionSubheadforLedger($table_name, $organisation, $start_date, $end_date, $where) {
        $sql = new Sql($this->dbAdapter);
        $year = date('Y', strtotime($start_date));

        $sub = $sql->select();
        $sub->from('accounts_closing_balance')
            ->columns(array("sub_head"))
            ->where->lessThanOrEqualTo("year", $year);

        $sub0 = $sql->select();
        $sub0->from('accounts_transaction')
            ->columns(array("id"))
            ->where(array("status" => "3"))
            ->where->between('voucher_date', $start_date, $end_date)
            ->OR->lessThan('voucher_date', $start_date);

        $sub1 = $sql->select();
        $sub1->from('accounts_transaction_details')
            ->columns(array("sub_head"));
        if ( $organisation != -1 ):
            $sub1->where(array("organisation_id" => $organisation));
        endif;
        $sub1->where->in("transaction", $sub0);

        $select = $sql->select();
        $select->from($table_name)
            ->where($where)
            ->where
            ->nest
            ->in('id', $sub1)
            ->OR->in('id', $sub)
            ->unnest;

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result)->toArray();
    }

    /**
     * Calculate opening balance for sub ledger
     * @param Array $options
     * @param Int $id
     * @return Int
     */
    public function getOpeningBalanceforSHLedger($table_name, $start_date, $organisation, $id) {
        $sql = new Sql($this->dbAdapter);
        $year = date('Y', strtotime($start_date));

        $sub0 = $sql->select();
        $sub0->from('accounts_closing_balance')
            ->columns(array(new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')))
            ->where(array('sub_head' => $id))
            ->where(array('organisation_id' => $organisation))
            ->where->lessThanOrEqualTo("year", $year);

        $total_debit = $this->getSumbySubheadforLedgerOpening($table_name, $start_date, 'debit', $id);
        $total_credit = $this->getSumbySubheadforLedgerOpening($table_name, $start_date, 'credit', $id);

        $stmt = $sql->prepareStatementForSqlObject($sub0);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $balances = $resultSet->initialize($result)->toArray();

        foreach ( $balances as $balance ) ;
        return $balance['total_closing_dr'] - $balance['total_closing_cr'] + $total_debit - $total_credit;
    }

    /**
     * get sum by subhead for sub ledger opening
     * @param String $column
     * @param String $column
     * @param Int $sub_head
     * @return int
     */
    public function getSumbySubheadforLedgerOpening($table_name, $start_date, $column, $sub_head) {
        $sql = new Sql($this->dbAdapter);

        $sub0 = $sql->select();
        $sub0->from('accounts_transaction')
            ->columns(array("id"))
            ->where(array("status" => "3"))
            ->where->lessThan('voucher_date', $start_date);

        $select = $sql->select();
        $select->from($table_name)
            ->columns(array(new Expression('SUM(' . $column . ') as total')))
            ->where(array("sub_head" => $sub_head))
            ->where->in('transaction', $sub0);
        $select->order(array('transaction ASC'));
        $select->order(array('created ASC'));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $results = $resultSet->initialize($result)->toArray();
        foreach ( $results as $result ):
            $sum = $result['total'];
        endforeach;

        return $sum;
    }

    /**
     *
     * get transaction id present in transactions details
     * @param Date $start_date
     * @param Date $end_date
     *
     * */
    public function getTransactionIDforLedger($table_name, $organisation, $start_date, $end_date, $where) {
        $sql = new Sql($this->dbAdapter);

        $sub1 = $sql->select();
        $sub1->from('accounts_transaction_details')
            ->columns(array("transaction"))
            ->where($where);

        if ( $organisation != -1 ):
            $sub1->where(array("organisation_id" => $organisation));
        endif;

        $select = $sql->select();
        $select->from($table_name)
            ->where(array("status" => "3"))
            ->where->between('voucher_date', $start_date, $end_date)
            ->where->in('id', $sub1);
        $select->order(array('voucher_date ASC'));
        $select->order(array('id ASC'));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result)->toArray();
    }

    /**
     * get sum by subhead for Ledger
     * @param String $column
     * @param Array $options
     * @param String $column
     * @param Int $sub_head
     * @return int
     */
    public function getSumbyTransactionIDforLedger($table_name, $organisation, $start_date, $end_date, $column, $where) {
        $sql = new Sql($this->dbAdapter);

        $sub0 = $sql->select();
        $sub0->from('accounts_transaction')
            ->columns(array("id"))
            ->where(array("status" => "3"))
            ->where->between('voucher_date', $start_date, $end_date);

        $select = $sql->select();
        $select->from($table_name)
            ->columns(array(new Expression('SUM(' . $column . ') as total')))
            ->where($where);
        if ( $organisation != -1 ):
            $select->where(array("organisation_id" => $organisation));
        endif;
        $select->where->in('transaction', $sub0);
        $select->order(array('transaction ASC'));
        $select->order(array('created ASC'));
        $selectString = $sql->getSqlStringForSqlObject($select);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $results = $resultSet->initialize($result)->toArray();

        foreach ( $results as $resultdata ):
            $sum = $resultdata['total'];
        endforeach;

        return $sum;
    }

    /**
     *
     * get Head present in transactions details
     * @param Date $start_date
     * @param Date $end_date
     *
     * */
    public function getTransactionHeadforLedger($table_name, $organisation, $start_date, $end_date, $where) {
        $sql = new Sql($this->dbAdapter);
        $year = date('Y', strtotime($start_date));

        $sub = $sql->select();
        $sub->from('accounts_closing_balance')
            ->columns(array("head"))
            ->where->lessThanOrEqualTo("year", $year);

        $sub0 = $sql->select();
        $sub0->from('accounts_transaction')
            ->columns(array("id"))
            ->where(array("status" => "3"))
            ->where->between('voucher_date', $start_date, $end_date)
            ->OR->lessThan('voucher_date', $start_date);

        $sub1 = $sql->select();
        $sub1->from('accounts_transaction_details')
            ->columns(array("head"));
        if ( $organisation != -1 ):
            $sub1->where(array("organisation_id" => $organisation));
        endif;
        $sub1->where->in("transaction", $sub0);

        $select = $sql->select();
        $select->from($table_name)
            ->where($where)
            ->where
            ->nest
            ->in('id', $sub1)
            ->OR->in('id', $sub)
            ->unnest;

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result)->toArray();
    }

    /**
     * Calculate opening balance for ledger
     * @param Array $options
     * @param Int $id
     * @return Int
     */
    public function getOpeningBalanceforHLedger($table_name, $start_date, $organisation, $id) {
        $sql = new Sql($this->dbAdapter);
        $year = date('Y', strtotime($start_date));

        $sub1 = $sql->select();
        $sub1->from('accounts_sub_head')
            ->columns(array("id"))
            ->where(array("head" => $id));

        $sub0 = $sql->select()
            ->from('accounts_closing_balance')
            ->columns(array(new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')))
            ->where(array('organisation_id' => $organisation));
        $sub0->where->in('sub_head', $sub1);
        $sub0->where->lessThanOrEqualTo("year", $year);

        $total_debit = $this->getSumbyheadforLedgerOpening($table_name, $start_date, 'debit', $id);
        $total_credit = $this->getSumbyheadforLedgerOpening($table_name, $start_date, 'credit', $id);

        $stmt = $sql->prepareStatementForSqlObject($sub0);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $balances = $resultSet->initialize($result)->toArray();

        foreach ( $balances as $balance ) ;
        return $balance['total_closing_dr'] - $balance['total_closing_cr'] + $total_debit - $total_credit;
    }

    /**
     * get sum by subhead for ledger opening
     * @param String $column
     * @param String $column
     * @param Int $sub_head
     * @return int
     */
    public function getSumbyheadforLedgerOpening($table_name, $start_date, $column, $head) {
        $sql = new Sql($this->dbAdapter);

        $sub0 = $sql->select();
        $sub0->from('accounts_transaction')
            ->columns(array("id"))
            ->where(array("status" => "3"))//committed status
            ->where->lessThan('voucher_date', $start_date);

        $select = $sql->select();
        $select->from($table_name)
            ->columns(array(new Expression('SUM(' . $column . ') as total')))
            ->where(array("head" => $head))
            ->where->in('transaction', $sub0);
        $select->order(array('transaction ASC'));
        $select->order(array('created ASC'));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $results = $resultSet->initialize($result)->toArray();

        foreach ( $results as $resultdata ):
            $sum = $resultdata['total'];
        endforeach;

        return $sum;
    }

    /**
     *
     * get Subhead present in transactions details
     * @param Date $start_date
     * @param Date $end_date
     *
     **/
    public function getTransactionSubheadforCashFlow($table_name, $organisation, $start_date, $end_date, $where) {
        $sql = new Sql($this->dbAdapter);
        $year = date('Y', strtotime($start_date));
        $sub = $sql->select();
        $sub->from('accounts_closing_balance')
            ->columns(array("sub_head"))
            ->where->lessThanOrEqualTo("year", $year);

        $sub0 = $sql->select();
        $sub0->from('accounts_transaction')
            ->columns(array("id"))
            ->where(array("status" => "3"))
            ->where->between('voucher_date', $start_date, $end_date)
            ->OR->lessThan('voucher_date', $start_date);

        $sub1 = $sql->select();
        $sub1->from('accounts_transaction_details')
            ->columns(array("sub_head"));
        if ( $organisation != -1 ):
            $sub1->where(array("organisation_id" => $organisation));
        endif;
        $sub1->where->in("transaction", $sub0);

        $select = $sql->select();
        $select->from($table_name)
            ->where($where)
            ->where
            ->nest
            ->in('id', $sub1)
            ->OR->in('id', $sub)
            ->unnest;
        $select->Limit(3);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result)->toArray();
    }

    /**
     * Calculate opening balance
     * @param Array $options
     * @param Int $id
     * @param Ind $tier
     * @return Int
     */
    public function getOpeningBalanceforCABA($type, $table_name, $organisation, $date, $subhead_id) {
        $sql = new Sql($this->dbAdapter);
        $year = date('Y', strtotime($date));

        $sub = $sql->select();
        $sub->from('accounts_closing_balance');
        $sub->columns(array(new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
        $sub->where(array('sub_head' => $subhead_id));
        $sub->where(array('organisation_id' => $organisation));

        if ( $type == 'BA' ) {
            $sub->where->lessThanOrEqualTo("year", $year);
        } else if ( $type == 'CA' ) {
            $sub->where->lessThan("year", $year);
        }

        $total_debit = $this->getSumbySubheadforCABA($type, $table_name, $organisation, $date, 'debit', $subhead_id);
        $total_credit = $this->getSumbySubheadforCABA($type, $table_name, $organisation, $date, 'credit', $subhead_id);

        $stmt = $sql->prepareStatementForSqlObject($sub);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $balances = $resultSet->initialize($result)->toArray();

        foreach ( $balances as $balance ) ;
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
    public function getSumbySubheadforCABA($type, $table_name, $organisation, $start_date, $column, $sub_head) {
        $sql = new Sql($this->dbAdapter);
        $sub0 = $sql->select();
        $sub0->from('accounts_transaction');
        $sub0->columns(array("id"))
            ->where(array("status" => "3"))//committed status
            ->where->lessThan('voucher_date', $start_date);

        $select = $sql->select();
        $select->from($table_name)
            ->columns(array(new Expression('SUM(' . $column . ') as total')))
            ->where(array("sub_head" => $sub_head))
            ->where(array("organisation_id" => $organisation));
        $select->where->in('transaction', $sub0);
        $select->order(array('transaction ASC'));
        $select->order(array('created ASC'));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $results = $resultSet->initialize($result)->toArray();

        foreach ( $results as $resultdata ):
            $sum = $resultdata['total'];
        endforeach;

        return $sum;
    }

    /** GET DISTINCT TRANSACTION ID
     * Return records of given condition array | given id
     * @param Start_date
     * @param Int $id
     * @return Array
     */
    public function getDisinctTransactionBCAID($table_name, $accounts, $start_date, $end_date) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t' => $table_name))
            ->join(array('td' => 'accounts_transaction_details'), 't.id = td.transaction', array('sub_head', 'head'))
            ->columns(array(new Expression('DISTINCT(t.id) as transaction_id')))
            ->where(array('sub_head' => $accounts))
            ->where->between('voucher_date', $start_date, $end_date);
        $select->where(array('t.status' => '3'));
        $select->order(array('voucher_date ASC'));
        $select->order(array('t.id ASC'));
        $select->order(array('t.created ASC'));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result)->toArray();
    }

    /* Get Cash Flow Report
    * @param Int $id
    * @param date
    * @return Array
    */
    public function getCashFlow($table_name, $where) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('td' => $table_name))
            ->join(array('t' => 'accounts_transaction'), 't.id = td.transaction', array('cheque_id', 'voucher_no', 'voucher_date', 'status', 'remark'));
        $select->where($where);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result)->toArray();
    }

    public function getBalanceSheetClass($organisation, $start_date, $end_date) {
        $prevoius_start_year = date('y', strtotime($start_date)) - 1;
        $prevoius_start_month = date('m', strtotime($start_date));
        $prevoius_start_day = date('d', strtotime($start_date));
        $pre_end_year = date('y', strtotime($end_date)) - 1;
        $pre_end_month = date('m', strtotime($end_date));
        $pre_end_day = date('d', strtotime($end_date));
        $pre_starting_date = date('Y-m-d', strtotime($prevoius_start_year . '-' . $prevoius_start_month . '-' . $prevoius_start_day));
        $pre_ending_date = date('Y-m-d', strtotime($pre_end_year . '-' . $pre_end_month . '-' . $pre_end_day));

        $sub0 = new Select("accounts_transaction");
        $sub0->columns(array("id"))
            ->where(array("status" => "3"))//committed status
            ->where->between('voucher_date', $start_date, $end_date)
            ->OR->where->between('voucher_date', $pre_starting_date, $pre_ending_date);

        $sub1 = new Select("accounts_transaction_details");
        $sub1->columns(array("head"));
        if ( $organisation != -1 ):
            $sub1->where(array("organisation_id" => $organisation));
        endif;
        $sub1->where->in("transaction", $sub0);

        $sub2 = new Select("accounts_head");
        $sub2->columns(array("group"))
            ->where->in("id", $sub1);

        $sub3 = new Select("accounts_group");
        $sub3->columns(array("class"))
            ->where->in("id", $sub2);

        $sql = new Sql($this->dbAdapter);

        $select = $sql->select();

        $select->from('accounts_class')
            ->where->in('id', array(1, 2))
            ->where->in('id', $sub3);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result)->toArray();
    }

    public function getTransactionGroupforBS($organisation, $start_date, $end_date, $where) {

        $prevoius_start_year = date('y', strtotime($start_date)) - 1;
        $prevoius_start_month = date('m', strtotime($start_date));
        $prevoius_start_day = date('d', strtotime($start_date));
        $pre_end_year = date('y', strtotime($end_date)) - 1;
        $pre_end_month = date('m', strtotime($end_date));
        $pre_end_day = date('d', strtotime($end_date));
        $pre_starting_date = date('Y-m-d', strtotime($prevoius_start_year . '-' . $prevoius_start_month . '-' . $prevoius_start_day));
        $pre_ending_date = date('Y-m-d', strtotime($pre_end_year . '-' . $pre_end_month . '-' . $pre_end_day));

        $year = date('Y', strtotime($start_date));
        $sub = new Select("accounts_closing_balance");
        $sub->columns(array("sub_head"))
            ->where->lessThanOrEqualTo("year", $year);

        $sub9 = new select("accounts_sub_head");
        $sub9->columns(array("head"))
            ->where->in("id", $sub);

        $sub0 = new Select("accounts_transaction");
        $sub0->columns(array("id"))
            ->where(array("status" => "3"))//committed status
            ->where->between('voucher_date', $start_date, $end_date)
            ->OR->where->between('voucher_date', $pre_starting_date, $pre_ending_date);

        $sub1 = new Select("accounts_transaction_details");
        $sub1->columns(array("head"));
        if ( $organisation != -1 ):
            $sub1->where(array("organisation_id" => $organisation));
        endif;
        $sub1->where->in("transaction", $sub0);

        $sub2 = new Select("accounts_head");
        $sub2->columns(array("group"));
        $sub2->where
            ->nest
            ->in('id', $sub1)
            ->OR->in('id', $sub9)
            ->unnest;

        $sql = new Sql($this->dbAdapter);

        $select = $sql->select();

        $select->from('accounts_group')
            ->where($where)
            ->where->in('id', $sub2);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result)->toArray();
    }

    public function getClosingBalanceforPresBS($organisation, $starting_date, $ending_date, $id, $tier) {
        if ( $tier == 1 ):
            $total_debit = $this->getSumbySubheadforPresBS($organisation, $starting_date, $ending_date, 'debit', $id);
            $total_credit = $this->getSumbySubheadforPresBS($organisation, $starting_date, $ending_date, 'credit', $id);
        elseif ( $tier == 2 ):

            $total_debit = $this->getSumbyHeadforPresBS($organisation, $starting_date, $ending_date, 'debit', $id);
            $total_credit = $this->getSumbyHeadforPresBS($organisation, $starting_date, $ending_date, 'credit', $id);
        elseif ( $tier == 3 ):

            $total_debit = $this->getSumbyGroupforPresBS($organisation, $starting_date, $ending_date, 'debit', $id);
            $total_credit = $this->getSumbyGroupforPresBS($organisation, $starting_date, $ending_date, 'credit', $id);
        elseif ( $tier == 4 ):

            $total_debit = $this->getSumbyClassforPresBS($organisation, $starting_date, $ending_date, 'debit', $id);
            $total_credit = $this->getSumbyClassforPresBS($organisation, $starting_date, $ending_date, 'credit', $id);
        endif;
        return $total_debit - $total_credit;
    }

    public function getSumbySubheadforPresBS($organisation, $starting_date, $ending_date, $column, $sub_head) {
        $sub0 = new Select("accounts_transaction");
        $sub0->columns(array("id"))
            ->where(array("status" => "3"))//committed status
            ->where->between('voucher_date', $starting_date, $ending_date);

        $sql = new Sql($this->dbAdapter);

        $select = $sql->select();

        $select->from('accounts_transaction_details')
            ->columns(array(new Expression('SUM(' . $column . ') as total')))
            ->where(array("sub_head" => $sub_head));
        if ( $organisation != -1 ):
            $select->where(array("organisation_id" => $organisation));
        endif;
        $select->where->in('transaction', $sub0);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $results = $resultSet->initialize($result)->toArray();

        foreach ( $results as $result ):
            $sum = $result['total'];
        endforeach;

        return $sum;
    }

    public function getSumbyHeadforPresBS($organisation, $starting_date, $ending_date, $column, $head) {

        $sub0 = new Select("accounts_transaction");
        $sub0->columns(array("id"))
            ->where(array("status" => "3"))//committed status
            ->where->between('voucher_date', $starting_date, $ending_date);

        $sql = new Sql($this->dbAdapter);

        $select = $sql->select();

        $select->from('accounts_transaction_details')
            ->columns(array(new Expression('SUM(' . $column . ') as total')))
            ->where(array("head" => $head));
        if ( $organisation != -1 ):
            $select->where(array("organisation_id" => $organisation));
        endif;
        $select->where->in('transaction', $sub0);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $results = $resultSet->initialize($result)->toArray();

        foreach ( $results as $result ):
            $sum = $result['total'];
        endforeach;

        return $sum;
    }

    public function getSumbyGroupforPresBS($organisation, $starting_date, $ending_date, $column, $group) {

        $sub0 = new Select("accounts_transaction");
        $sub0->columns(array("id"))
            ->where(array("status" => "3"))//committed status
            ->where->between('voucher_date', $starting_date, $ending_date);

        $sub1 = new Select("accounts_head");
        $sub1->columns(array("id"))
            ->where(array("group" => $group));

        $sql = new Sql($this->dbAdapter);

        $select = $sql->select();

        $select->from('accounts_transaction_details')
            ->columns(array(new Expression('SUM(' . $column . ') as total')));

        if ( $organisation != -1 ):
            $select->where(array("organisation_id" => $organisation));
        endif;
        $select->where->in('head', $sub1)
            ->where->in('transaction', $sub0);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $results = $resultSet->initialize($result)->toArray();

        foreach ( $results as $result ):
            $sum = $result['total'];
        endforeach;

        return $sum;
    }

    public function getSumbyClassforPresBS($organisation, $starting_date, $ending_date, $column, $class) {
        $sub0 = new Select("accounts_transaction");
        $sub0->columns(array("id"))
            ->where(array("status" => "3"))//committed status
            ->where->between('voucher_date', $starting_date, $ending_date);

        $sub1 = new Select("accounts_group");
        $sub1->columns(array("id"))
            ->where(array("class" => $class));

        $sub2 = new Select("accounts_head");
        $sub2->columns(array("id"))
            ->where->in("group", $sub1);

        $sql = new Sql($this->dbAdapter);

        $select = $sql->select();

        $select->from('accounts_transaction_details')
            ->columns(array(new Expression('SUM(' . $column . ') as total')));
        if ( $organisation != -1 ):
            $select->where(array("organisation_id" => $organisation));
        endif;
        $select->where->in('head', $sub2)
            ->where->in('transaction', $sub0);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $results = $resultSet->initialize($result)->toArray();

        foreach ( $results as $result ):
            $sum = $result['total'];
        endforeach;
        return $sum;
    }

    /**
     * @param $organisation
     * @param $starting_date
     * @param $ending_date
     * @param $id
     * @param $tier
     * @return mixed
     */
    public function getClosingBalanceforPrevBS($organisation, $starting_date, $ending_date, $id, $tier) {
        if ( $tier == 1 ):

            $total_debit = $this->getSumbySubheadforPrevBS($organisation, $starting_date, $ending_date, 'debit', $id);
            $total_credit = $this->getSumbySubheadforPrevBS($organisation, $starting_date, $ending_date, 'credit', $id);
        elseif ( $tier == 2 ):

            $total_debit = $this->getSumbyHeadforPrevBS($organisation, $starting_date, $ending_date, 'debit', $id);
            $total_credit = $this->getSumbyHeadforPrevBS($organisation, $starting_date, $ending_date, 'credit', $id);
        elseif ( $tier == 3 ):
            $total_debit = $this->getSumbyGroupforPrevBS($organisation, $starting_date, $ending_date, 'debit', $id);
            $total_credit = $this->getSumbyGroupforPrevBS($organisation, $starting_date, $ending_date, 'credit', $id);
        elseif ( $tier == 4 ):
            $total_debit = $this->getSumbyClassforPrevBS($organisation, $starting_date, $ending_date, 'debit', $id);
            $total_credit = $this->getSumbyClassforPrevBS($organisation, $starting_date, $ending_date, 'credit', $id);
        endif;
        return $total_debit - $total_credit;
    }

    public function getSumbySubheadforPrevBS($organisation, $starting_date, $ending_date, $column, $sub_head) {
        $sub0 = new Select("accounts_transaction");
        $sub0->columns(array("id"))
            ->where(array("status" => "3"))//committed status
            ->where->between('voucher_date', $starting_date, $ending_date);

        $sql = new Sql($this->dbAdapter);

        $select = $sql->select();

        $select->from('accounts_transaction_details')
            ->columns(array(new Expression('SUM(' . $column . ') as total')))
            ->where(array("sub_head" => $sub_head));
        if ( $organisation != -1 ):
            $select->where(array("organisation_id" => $organisation));
        endif;
        $select->where->in('transaction', $sub0);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $results = $resultSet->initialize($result)->toArray();

        foreach ( $results as $result ):
            $sum = $result['total'];
        endforeach;

        return $sum;
    }

    public function getSumbyHeadforPrevBS($organisation, $starting_date, $ending_date, $column, $head) {

        $sub0 = new Select("accounts_transaction");
        $sub0->columns(array("id"))
            ->where(array("status" => "3"))//committed status
            ->where->between('voucher_date', $starting_date, $ending_date);

        $sql = new Sql($this->dbAdapter);

        $select = $sql->select();

        $select->from('accounts_transaction_details')
            ->columns(array(new Expression('SUM(' . $column . ') as total')))
            ->where(array("head" => $head));
        if ( $organisation != -1 ):
            $select->where(array("organisation_id" => $organisation));
        endif;
        $select->where->in('transaction', $sub0);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $results = $resultSet->initialize($result)->toArray();

        foreach ( $results as $result ):
            $sum = $result['total'];
        endforeach;

        return $sum;
    }

    public function getSumbyGroupforPrevBS($organisation, $starting_date, $ending_date, $column, $group) {

        $sub0 = new Select("accounts_transaction");
        $sub0->columns(array("id"))
            ->where(array("status" => "3"))//committed status
            ->where->between('voucher_date', $starting_date, $ending_date);

        $sub1 = new Select("accounts_head");
        $sub1->columns(array("id"))
            ->where(array("group" => $group));

        $sql = new Sql($this->dbAdapter);

        $select = $sql->select();

        $select->from('accounts_transaction_details')
            ->columns(array(new Expression('SUM(' . $column . ') as total')));

        if ( $organisation != -1 ):
            $select->where(array("organisation_id" => $organisation));
        endif;
        $select->where->in('head', $sub1)
            ->where->in('transaction', $sub0);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $results = $resultSet->initialize($result)->toArray();

        foreach ( $results as $result ):
            $sum = $result['total'];
        endforeach;

        return $sum;
    }

    public function getSumbyClassforPrevBS($organisation, $starting_date, $ending_date, $column, $class) {

        $sub0 = new Select("accounts_transaction");
        $sub0->columns(array("id"))
            ->where(array("status" => "3"))//committed status
            ->where->between('voucher_date', $starting_date, $ending_date);

        $sub1 = new Select("accounts_group");
        $sub1->columns(array("id"))
            ->where(array("class" => $class));

        $sub2 = new Select("accounts_head");
        $sub2->columns(array("id"))
            ->where->in("group", $sub1);

        $sql = new Sql($this->dbAdapter);

        $select = $sql->select();

        $select->from('accounts_transaction_details')
            ->columns(array(new Expression('SUM(' . $column . ') as total')));

        if ( $organisation != -1 ):
            $select->where(array("organisation_id" => $organisation));
        endif;
        $select->where->in('head', $sub2)
            ->where->in('transaction', $sub0);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $results = $resultSet->initialize($result)->toArray();

        foreach ( $results as $result ):
            $sum = $result['total'];
        endforeach;
        return $sum;
    }

    /**
     * Return Min value of the column
     * @param Array $where
     * @param String $column
     * @return String | Int
     */
    public function getMin($table_name, $column, $where = NULL) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from($table_name);
        $select->columns(array(
            'min' => new Expression('MIN(' . $column . ')'),
        ));

        if ( $where != NULL ) {
            $select->where($where);
        }

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $results = $resultSet->initialize($result)->toArray();

        foreach ( $results as $resultdata ):
            $column = $resultdata['min'];
        endforeach;

        return $column;
    }

    /**
     * Return records of given year and month
     * @param Int $id
     * @return Array
     */
    public function getlocationDateWise($table_name, $column, $userorg, $year, $month, $param) {
        $where = (is_array($param)) ? $param : array('id' => $param);

        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t' => $table_name))
            ->join(array('j' => 'accounts_journal'), 'j.id = t.voucher_type', array('voucher_type' => 'journal', 'voucher_id' => 'id'))
            ->columns(array(
                'id', 'voucher_date', 'voucher_type', 'voucher_no', 'voucher_amount', 'organisation_id', 'remark',
                'status', 'author', 'created', 'modified',
                'year' => new Expression('YEAR(' . $column . ')'),
                'month' => new Expression('MONTH(' . $column . ')'),
                'date' => new Expression('DAY(' . $column . ')'),
            ))
            ->order(array('id DESC'));

        if ( $param ) {
            $select->where($where);
        }
        if ( $userorg != '-1' ) {
            $select->where(array('organisation_id' => $userorg));
        }
        if ( $month != '-1' ) {
            $select->having(array('month' => $month));
        }
        if ( $year != '-1' ) {
            $select->having(array('year' => $year));
        }

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result)->toArray();
    }

    /**
     * Return records of given condition Array
     * @param Array
     * @param Array
     * @return Array
     */
    public function getBSubledger($param, $user_org, $order = NULL) {
        $where = (is_array($param)) ? $param : array('md.id' => $param);

        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('md' => 'accounts_master_details'))
            ->join(array('sh' => 'accounts_sub_head'), 'sh.id=md.sub_head', array('sub_head' => 'name', 'subhead_id' => 'id'))
            ->join(array('t' => 'accounts_type'), 't.id=md.type', array('type_id' => 'id'))
            ->join(array('b' => 'accounts_bank_account'), 'b.id=md.ref_id', array('ref_id' => 'id'))
            ->where($where);
        $select->where(array('organisation_id' => $user_org));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result)->toArray();
    }

    /**
     * Return records of given condition Array
     * @param Array
     * @param Array
     * @return Array
     */
    public function getCSubledger($param, $user_org, $order = NULL) {
        $where = (is_array($param)) ? $param : array('md.id' => $param);

        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('md' => 'accounts_master_details'))
            ->join(array('sh' => 'accounts_sub_head'), 'sh.id=md.sub_head', array('sub_head' => 'name', 'subhead_id' => 'id'))
            ->join(array('t' => 'accounts_type'), 't.id=md.type', array('type_id' => 'id'))
            ->join(array('c' => 'accounts_cash_account'), 'c.id=md.ref_id', array('ref_id' => 'id'))
            ->where($where);
        $select->where(array('organisation_id' => $user_org));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result)->toArray();
    }

    /**
     * Calculate opening balance for sub ledger
     * @param Array $options
     * @param Int $id
     * @return Int
     */
    public function getBankandCashBalance($type, $date, $sub_ledger, $organisation) {
        $sql = new Sql($this->dbAdapter);
        $year = date('Y', strtotime($date));

        $sub0 = $sql->select();
        $sub0->from('accounts_closing_balance')
            ->columns(array(new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')))
            ->where(array('sub_head' => $sub_ledger))
            ->where(array('organisation_id' => $organisation));
        $sub0->where->lessThanOrEqualTo("year", $year);

        $total_debit = $this->getSumbyBankandCashSubLedger($date, 'debit', $sub_ledger);
        $total_credit = $this->getSumbyBankandCashSubLedger($date, 'credit', $sub_ledger);

        $stmt = $sql->prepareStatementForSqlObject($sub0);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $balances = $resultSet->initialize($result)->toArray();

        foreach ( $balances as $balance ) ;
        return $balance['total_closing_dr'] - $balance['total_closing_cr'] + $total_debit - $total_credit;
    }

    /**
     * get sum by bank subhead
     * @param String $column
     * @param String $column
     * @param Int $sub_head
     * @return int
     */
    public function getSumbyBankandCashSubLedger($start_date, $column, $sub_ledger) {
        $sql = new Sql($this->dbAdapter);

        $sub0 = $sql->select();
        $sub0->from('accounts_transaction');
        $sub0->columns(array("id"))
            ->where(array("status" => "3"))
            ->where->lessThanorEqualTo('voucher_date', $start_date);

        $select = $sql->select();
        $select->from('accounts_transaction_details')
            ->columns(array(new Expression('SUM(' . $column . ') as total')))
            ->where(array("sub_head" => $sub_ledger));
        $select->where->in('transaction', $sub0);
        $select->order(array('transaction ASC'));
        $select->order(array('created ASC'));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $results = $resultSet->initialize($result)->toArray();

        foreach ( $results as $resultdata ):
            $sum = $resultdata['total'];
        endforeach;

        return $sum;
    }

    /**
     * MONTHLY SALARY BOOKING
     * Get Subheads
     **/
    public function getDistinctESP($user_org) {
        $sql = new Sql($this->dbAdapter); 

        $sub0 = $sql->select();
        $sub0->from(array('ap' => 'supplier_details'))
            ->columns(array('id'))
            ->where(array('organisation_id' => $user_org));

        $sub1 = $sql->select();
        $sub1->from(array('empl' => 'employee_details'))
            ->columns(array('id'))
            ->where(array('organisation_id' => $user_org));

        $sub2 = $sql->select();
        $sub2->from(array('std' => 'student'))
            ->columns(array('id'))
            ->where(array('organisation_id' => $user_org));

        $sub3 = $sql->select();
        $sub3->from(array('bg' => 'accounts_budget'))
            ->columns(array('id'))
            ->where(array('organisation_id' => $user_org));

        $sub4 = $sql->select();
        $sub4->from(array('ph' => 'payr_pay_heads'))
            ->columns(array('id'))
            ->where(array('id' => 16));

        $select = $sql->select();
        $select->from(array('md' => 'accounts_master_details'))
            ->join(array('t' => 'accounts_type'), 't.id=md.type', array('type' => 'name', 'type_id' => 'id'));
        $select->columns(array(
            'ref_id' => new Expression('Distinct(ref_id)'),
            'name' => 'name',
            'code' => 'code',
        ));
        $select->where
            ->nest
            ->in('ref_id', $sub0)
            ->OR->in('ref_id', $sub1)
            ->OR->in('ref_id', $sub2)
            ->OR->in('ref_id', $sub3)
            ->OR->in('ref_id', $sub4)
            ->unnest;
        $select->where(array('md.type' => array('4', '5', '6', '7', '8')));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result)->toArray();
    }

    /**
     * Return records of given condition Array
     * @param $user_org
     * @param $param
     * @param $order
     * @return array
     */
    public function getBCADetails($user_org, $param, $order = NULL) {

        $sql = new Sql($this->dbAdapter);

        $sub0 = $sql->select();
        $sub0->from(array('ba' => 'accounts_bank_account'))
            ->columns(array('id'))
            ->where(array('organisation_id' => $user_org));

        $sub1 = $sql->select();
        $sub1->from(array('ca' => 'accounts_cash_account'))
            ->columns(array('id'))
            ->where(array('organisation_id' => $user_org));

        $where = (is_array($param)) ? $param : array('md.id' => $param);

        $select = $sql->select();
        $select->from(array('md' => 'accounts_master_details'))
            ->join(array('sh' => 'accounts_sub_head'), 'sh.id=md.sub_head', array('sub_head' => 'name', 'subhead_id' => 'id'))
            ->join(array('t' => 'accounts_type'), 't.id=md.type', array('type_id' => 'id'));
        $select->where->nest
            ->in('ref_id', $sub0)
            ->OR->in('ref_id', $sub1)
            ->unnest;
        $select->where($where);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result)->toArray();
    }

    /**
     * Return records of given condition Array
     * @param $param
     * @param $order
     * @return array
     */
    public function getASSubLedger($param, $order = NULL) {
        $sql = new Sql($this->dbAdapter);

        $where = (is_array($param)) ? $param : array('md.id' => $param);
        $select = $sql->select();
        $select->from(array('md' => 'accounts_master_details'))
            ->join(array('sh' => 'accounts_sub_head'), 'sh.id=md.sub_head', array('sub_head' => 'name', 'subhead_id' => 'id'))
            ->join(array('t' => 'accounts_type'), 't.id=md.type', array('type_id' => 'id'))
            ->join(array('empl' => 'employee_details'), 'empl.id=md.ref_id', array('ref_id' => 'id'))
            ->where($where);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result)->toArray();
    }

    /**
     * Return latest serail of given date array
     *
     * @param $table_name
     * @param $prefix_PO_code
     * @return array
     */
    public function getSerial($table_name, $prefix_PO_code) { 
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from($table_name);
        $select->where->like('voucher_no', $prefix_PO_code . "%");

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result)->toArray();
    }

    /**
     * Return records of given condition array | given id
     * @param Int $id
     * @return array
     */
    public function getParty($type_id, $param) {
        $sql = new Sql($this->dbAdapter);

        $where = (is_array($param)) ? $param : array('td.id' => $param);
        $select = $sql->select();
        $select->from(array('td' => 'accounts_transaction_details'))
            ->join(array('t' => 'accounts_transaction'), 't.id = td.transaction', array('transaction_id' => 'id', 'voucher_type'))
            ->join(array('o' => 'organisation'), 'o.id = td.organisation_id', array('organisation' => 'organisation_name', 'organisation_id' => 'id'))
            ->join(array('h' => 'accounts_head'), 'h.id = td.head', array('head' => 'name', 'head_id' => 'id'))
            ->join(array('sh' => 'accounts_sub_head'), 'sh.id = td.sub_head', array('sub_head' => 'name', 'sub_head_id' => 'id'))
            ->join(array('md' => 'accounts_master_details'), 'md.id = td.master_details', array('master_details' => 'name', 'ref_id', 'type', 'master_details_id' => 'id'))
            ->where($where)
            ->where(array('md.type' => $type_id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result)->toArray();
    }

    /**
     * Return id's|columns'value  which is not present in given array
     * @param Array $param
     * @param String column
     * @return Array
     */
    public function getNotInDtl($param, $column = 'id', $where = NULL) {
        $sql = new Sql($this->dbAdapter);

        $param = (is_array($param)) ? $param : array($param);
        $where = (is_array($column)) ? $column : $where;
        $column = (is_array($column)) ? 'id' : $column;

        $select = $sql->select();
        $select->from('accounts_transaction_details')
            ->columns(array('id'))
            ->where->notIn($column, $param);

        if ( $where != Null ) {
            $select->where($where);
        }

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result)->toArray();
    }

    /**
     *  Delete a record
     * @param Int $id
     * @return true | false
     */
    public function remove($tableName, $id) {
        $where = (is_array($id)) ? $id : array('id = ?' => $id);
        $deleteAction = new Delete($tableName);
        $deleteAction->where($where);
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($deleteAction);
        $result = $stmt->execute();
        return (bool) $result->getAffectedRows();
    }

    /**
     * @param $type
     * @param $tablename
     * @param $params
     * @param null $column
     * @return array
     */
    public function getDataByFilter($type, $tablename, $params, $column = null) {

        $sql = new Sql($this->dbAdapter);

        $select = $sql->select();

        if ( $type == "getProfitlossClass" ) {

            $prevoius_start_year = date('y', strtotime($params['start_date'])) - 1;
            $prevoius_start_month = date('m', strtotime($params['start_date']));
            $prevoius_start_day = date('d', strtotime($params['start_date']));

            $pre_end_year = date('y', strtotime($params['end_date'])) - 1;
            $pre_end_month = date('m', strtotime($params['end_date']));
            $pre_end_day = date('d', strtotime($params['end_date']));

            $pre_starting_date = date('Y-m-d', strtotime($prevoius_start_year . '-' . $prevoius_start_month . '-' . $prevoius_start_day));
            $pre_ending_date = date('Y-m-d', strtotime($pre_end_year . '-' . $pre_end_month . '-' . $pre_end_day));

            $sub0 = new Select("accounts_transaction");
            $sub0->columns(array("id"))
                ->where(array("status" => "3"))//committed status
                ->where->between('voucher_date', $params['start_date'], $params['end_date'])
                ->OR->where->between('voucher_date', $pre_starting_date, $pre_ending_date);

            $sub1 = new Select("accounts_transaction_details");
            $sub1->columns(array("head"));
            if ( $params['organisation'] != -1 ):
                $sub1->where(array("organisation_id" => $params['organisation']));
            endif;
            $sub1->where->in("transaction", $sub0);

            $sub2 = new Select("accounts_head");
            $sub2->columns(array("group"))->where->in("id", $sub1);

            $sub3 = new Select("accounts_group");
            $sub3->columns(array("class"))->where->in("id", $sub2);

            $select->from($tablename)->where->in('id', array(3, 4))->where->in('id', $sub3);

        } elseif ( $type == 'getTransactionGroupforPLS' ) {

            $prevoius_start_year = date('y', strtotime($params['start_date'])) - 1;
            $prevoius_start_month = date('m', strtotime($params['start_date']));
            $prevoius_start_day = date('d', strtotime($params['start_date']));

            $pre_end_year = date('y', strtotime($params['end_date'])) - 1;
            $pre_end_month = date('m', strtotime($params['end_date']));
            $pre_end_day = date('d', strtotime($params['end_date']));

            $pre_starting_date = date('Y-m-d', strtotime($prevoius_start_year . '-' . $prevoius_start_month . '-' . $prevoius_start_day));
            $pre_ending_date = date('Y-m-d', strtotime($pre_end_year . '-' . $pre_end_month . '-' . $pre_end_day));

            $year = date('Y', strtotime($params['start_date']));
            $sub = new Select("accounts_closing_balance");
            $sub->columns(array("sub_head"))->where->lessThanOrEqualTo("year", $year);

            $sub9 = new select("accounts_sub_head");
            $sub9->columns(array("head"))->where->in("id", $sub);

            $sub0 = new Select("accounts_transaction");
            $sub0->columns(array("id"))
                ->where(array("status" => "3"))//committed status
                ->where->between('voucher_date', $params['start_date'], $params['end_date'])
                ->OR->where->between('voucher_date', $pre_starting_date, $pre_ending_date);

            $sub1 = new Select("accounts_transaction_details");
            $sub1->columns(array("head"));
            if ( $params['organisation'] != -1 ):
                $sub1->where(array("organisation_id" => $params['organisation']));
            endif;
            $sub1->where->in("transaction", $sub0);

            $sub2 = new Select("accounts_head");
            $sub2->columns(array("group"));
            $sub2->where->nest->in('id', $sub1)->OR->in('id', $sub9)->unnest;

            $select->from($tablename)->where($params['where'])->where->in('id', $sub2);

        } elseif ( $type == 'getTransactionHeadforPLS' ) {

            $prevoius_start_year = date('y', strtotime($params['start_date'])) - 1;
            $prevoius_start_month = date('m', strtotime($params['start_date']));
            $prevoius_start_day = date('d', strtotime($params['start_date']));

            $pre_end_year = date('y', strtotime($params['end_date'])) - 1;
            $pre_end_month = date('m', strtotime($params['end_date']));
            $pre_end_day = date('d', strtotime($params['end_date']));

            $pre_starting_date = date('Y-m-d', strtotime($prevoius_start_year . '-' . $prevoius_start_month . '-' . $prevoius_start_day));
            $pre_ending_date = date('Y-m-d', strtotime($pre_end_year . '-' . $pre_end_month . '-' . $pre_end_day));

            $year = date('Y', strtotime($params['start_date']));
            $sub = new Select("accounts_closing_balance");
            $sub->columns(array("sub_head"))
                ->where->lessThanOrEqualTo("year", $year);

            $sub9 = new select("accounts_sub_head");
            $sub9->columns(array("head"))
                ->where->in("id", $sub);

            $sub0 = new Select("accounts_transaction");
            $sub0->columns(array("id"))
                ->where(array("status" => "3"))//committed status
                ->where->between('voucher_date', $params['start_date'], $params['end_date'])
                ->Or->where->between('voucher_date', $pre_starting_date, $pre_ending_date);

            $sub1 = new Select("accounts_transaction_details");
            $sub1->columns(array("head"));
            if ( $params['organisation'] != -1 ):
                $sub1->where(array("organisation_id" => $params['organisation']));
            endif;
            $sub1->where->in("transaction", $sub0);

            $select->from($tablename)->where($params['where'])->where->nest->in('id', $sub1)->OR->in('id', $sub9)->unnest;

        } elseif ( $type == 'getTransactionSubheadforPLS' ) {

            $prevoius_start_year = date('y', strtotime($params['start_date'])) - 1;
            $prevoius_start_month = date('m', strtotime($params['start_date']));
            $prevoius_start_day = date('d', strtotime($params['start_date']));

            $pre_end_year = date('y', strtotime($params['end_date'])) - 1;
            $pre_end_month = date('m', strtotime($params['end_date']));
            $pre_end_day = date('d', strtotime($params['end_date']));

            $pre_starting_date = date('Y-m-d', strtotime($prevoius_start_year . '-' . $prevoius_start_month . '-' . $prevoius_start_day));
            $pre_ending_date = date('Y-m-d', strtotime($pre_end_year . '-' . $pre_end_month . '-' . $pre_end_day));

            $year = date('Y', strtotime($params['start_date']));
            $sub = new Select("accounts_closing_balance");
            $sub->columns(array("sub_head"))->where->lessThanOrEqualTo("year", $year);

            $sub0 = new Select("accounts_transaction");
            $sub0->columns(array("id"))
                ->where(array("status" => "3"))
                ->where->between('voucher_date', $params['start_date'], $params['end_date'])
                ->OR->where->between('voucher_date', $pre_starting_date, $pre_ending_date);
            $sub1 = new Select("accounts_transaction_details");
            $sub1->columns(array("sub_head"));

            if ( $params['organisation'] != -1 ):
                $sub1->where(array("organisation_id" => $params['organisation']));
            endif;
            $sub1->where->in("transaction", $sub0);

            $select->from($tablename)->where($params['where'])->where->nest->in('id', $sub1)->OR->in('id', $sub)->unnest;

        } elseif ( $type == 'at_getMin' ) {

            $select->from($tablename);
            $select->columns(array(
                'min' => new Expression('MIN(' . $column . ')'),
            ));

        } elseif ( $type === 'get_column_accounts_cheque_book_dtls' ) {

            $where = (is_array($params)) ? $params : array('id' => $params);

            $select->from($tablename);
            $select->columns(array($column));
            $select->where($where);

        } else {
            $select->from($tablename);
        }

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();

        if ( $type === 'get_column_accounts_cheque_book_dtls' ) {
            $results = $resultSet->initialize($result)->toArray();

            foreach ( $results as $result ):
                $columns = $result[$column];
            endforeach;

            return $columns;
        }

        if ( $type == 'at_getMin' ) {
            $results = $resultSet->initialize($result)->toArray();

            foreach ( $results as $result ):
                $column = $result['min'];
            endforeach;

            return $column;
        }

        return $resultSet->initialize($result)->toArray();
    }

    public function getClosingBalanceforPresPLS($organisation, $starting_date, $ending_date, $id, $tier) {

        if ( $tier == 1 ):
            $total_debit = $this->getSumbySubheadforPresPLS($organisation, $starting_date, $ending_date, 'debit', $id);
            $total_credit = $this->getSumbySubheadforPresPLS($organisation, $starting_date, $ending_date, 'credit', $id);
        elseif ( $tier == 2 ):

            $total_debit = $this->getSumbyHeadforPresPLS($organisation, $starting_date, $ending_date, 'debit', $id);
            $total_credit = $this->getSumbyHeadforPresPLS($organisation, $starting_date, $ending_date, 'credit', $id);
        elseif ( $tier == 3 ):
            $total_debit = $this->getSumbyGroupforPresPLS($organisation, $starting_date, $ending_date, 'debit', $id);
            $total_credit = $this->getSumbyGroupforPresPLS($organisation, $starting_date, $ending_date, 'credit', $id);
        elseif ( $tier == 4 ):
            $total_debit = $this->getSumbyClassforPresPLS($organisation, $starting_date, $ending_date, 'debit', $id);
            $total_credit = $this->getSumbyClassforPresPLS($organisation, $starting_date, $ending_date, 'credit', $id);
        endif;

        return $total_debit - $total_credit;
    }

    public function getClosingBalanceforPrevPLS($organisation, $starting_date, $ending_date, $id, $tier) {
        if ( $tier == 1 ):

            $total_debit = $this->getSumbySubheadforPresPLS($organisation, $starting_date, $ending_date, 'debit', $id);
            $total_credit = $this->getSumbySubheadforPresPLS($organisation, $starting_date, $ending_date, 'credit', $id);
        elseif ( $tier == 2 ):

            $total_debit = $this->getSumbyHeadforPresPLS($organisation, $starting_date, $ending_date, 'debit', $id);
            $total_credit = $this->getSumbyHeadforPresPLS($organisation, $starting_date, $ending_date, 'credit', $id);
        elseif ( $tier == 3 ):
            $total_debit = $this->getSumbyGroupforPresPLS($organisation, $starting_date, $ending_date, 'debit', $id);
            $total_credit = $this->getSumbyGroupforPresPLS($organisation, $starting_date, $ending_date, 'credit', $id);
        elseif ( $tier == 4 ):
            $total_debit = $this->getSumbyClassforPresPLS($organisation, $starting_date, $ending_date, 'debit', $id);
            $total_credit = $this->getSumbyClassforPresPLS($organisation, $starting_date, $ending_date, 'credit', $id);
        endif;
        return $total_debit - $total_credit;
    }

    public function getSumbySubheadforPresPLS($organisation, $starting_date, $ending_date, $column, $sub_head) {
        $sub0 = new Select("accounts_transaction");
        $sub0->columns(array("id"))
            ->where(array("status" => "3"))//committed status
            ->where->between('voucher_date', $starting_date, $ending_date);

        $sql = new Sql($this->dbAdapter);

        $select = $sql->select();

        $select->from('accounts_transaction_details')
            ->columns(array(new Expression('SUM(' . $column . ') as total')))
            ->where(array("sub_head" => $sub_head));

        if ( $organisation != -1 ):
            $select->where(array("organisation_id" => $organisation));
        endif;

        $select->where->in('transaction', $sub0);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $results = $resultSet->initialize($result)->toArray();

        foreach ( $results as $result ):
            $sum = $result['total'];
        endforeach;

        return $sum;
    }

    public function getSumbyHeadforPresPLS($organisation, $starting_date, $ending_date, $column, $head) {

        $sub0 = new Select("accounts_transaction");
        $sub0->columns(array("id"))
            ->where(array("status" => "3"))//committed status
            ->where->between('voucher_date', $starting_date, $ending_date);

        $sql = new Sql($this->dbAdapter);

        $select = $sql->select();

        $select->from('accounts_transaction_details')
            ->columns(array(new Expression('SUM(' . $column . ') as total')))
            ->where(array("head" => $head));

        if ( $organisation != -1 ):
            $select->where(array("organisation_id" => $organisation));
        endif;

        $select->where->in('transaction', $sub0);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $results = $resultSet->initialize($result)->toArray();

        foreach ( $results as $result ):
            $sum = $result['total'];
        endforeach;

        return $sum;
    }

    public function getSumbyGroupforPresPLS($organisation, $starting_date, $ending_date, $column, $group) {

        $sub0 = new Select("accounts_transaction");
        $sub0->columns(array("id"))
            ->where(array("status" => "3"))//committed status
            ->where->between('voucher_date', $starting_date, $ending_date);

        $sub1 = new Select("accounts_head");
        $sub1->columns(array("id"))->where(array("group" => $group));

        $sql = new Sql($this->dbAdapter);

        $select = $sql->select();

        $select->from('accounts_transaction_details')->columns(array(new Expression('SUM(' . $column . ') as total')));

        if ( $organisation != -1 ):
            $select->where(array("organisation_id" => $organisation));
        endif;
        $select->where->in('head', $sub1)->where->in('transaction', $sub0);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $results = $resultSet->initialize($result)->toArray();

        foreach ( $results as $result ):
            $sum = $result['total'];
        endforeach;

        return $sum;
    }

    public function getSumbyClassforPresPLS($organisation, $starting_date, $ending_date, $column, $class) {

        $sub0 = new Select("accounts_transaction");
        $sub0->columns(array("id"))
            ->where(array("status" => "3"))//committed status
            ->where->between('voucher_date', $starting_date, $ending_date);

        $sub1 = new Select("accounts_group");
        $sub1->columns(array("id"))->where(array("class" => $class));

        $sub2 = new Select("accounts_head");
        $sub2->columns(array("id"))->where->in("group", $sub1);

        $sql = new Sql($this->dbAdapter);

        $select = $sql->select();

        $select->from('accounts_transaction_details')->columns(array(new Expression('SUM(' . $column . ') as total')));

        if ( $organisation != -1 ):
            $select->where(array("organisation_id" => $organisation));
        endif;

        $select->where->in('head', $sub2)->where->in('transaction', $sub0);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $results = $resultSet->initialize($result)->toArray();

        foreach ( $results as $result ):
            $sum = $result['total'];
        endforeach;

        return $sum;

    }

    public function getTransactionSubheadforBRS($organisation, $start_date, $end_date, $where) {
        $year = date('Y', strtotime($start_date));
        $sub = new Select("accounts_closing_balance");
        $sub->columns(array("sub_head"))
            ->where->lessThanOrEqualTo("year", $year);

        $sub0 = new Select("accounts_transaction");
        $sub0->columns(array("id"))
            ->where(array("status" => "3"))
            ->where->between('voucher_date', $start_date, $end_date)
            ->OR->lessThan('voucher_date', $start_date);

        $sub1 = new Select("accounts_transaction_details");
        $sub1->columns(array("sub_head"));

        if ( $organisation != -1 ):
            $sub1->where(array("organisation_id" => $organisation));
        endif;
        $sub1->where->in("transaction", $sub0);

        $sql = new Sql($this->dbAdapter);

        $select = $sql->select();

        $select->from('accounts_sub_head')->where($where)->where->nest->in('id', $sub1)->OR->in('id', $sub)->unnest;

        $select->Limit(3);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $results = $resultSet->initialize($result)->toArray();

        return $results;
    }

    public function getBudgetforBRS($organisation, $end_date, $subhead_id) {
        $sql = new Sql($this->dbAdapter);

        $year = date('Y', strtotime($end_date));
        $start_date = date('Y-01-t');
        $sub0 = new Select("accounts_closing_balance");
        $sub0->columns(array(new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
        $sub0->where->LessThanOrEqualTo("year", $year);
        $sub0->where(array('sub_head' => $subhead_id));
        $sub0->where(array('organisation_id' => $organisation));

        $total_debit = $this->getSumbySubheadBudgetforBRS($organisation, $end_date, 'debit', $subhead_id);
        $total_credit = $this->getSumbySubheadBudgetforBRS($organisation, $end_date, 'credit', $subhead_id);

        $stmt = $sql->prepareStatementForSqlObject($sub0);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $balances = $resultSet->initialize($result)->toArray();

        foreach ( $balances as $balance ) ;

        return $balance['total_closing_dr'] - $balance['total_closing_cr'] + $total_debit - $total_credit;
    }

    public function getSumbySubheadBudgetforBRS($organisation, $end_date, $column, $sub_head) {

        $sub0 = new Select(array('t' => "accounts_transaction"));
        $sub0->columns(array("id"))
            ->where(array("t.status" => "3"))//committed status
            ->where->lessThan('voucher_date', $end_date);

        $sql = new Sql($this->dbAdapter);

        $select = $sql->select();

        $select->from('accounts_transaction_details')
            ->columns(array(new Expression('SUM(' . $column . ') as total')))
            ->where(array("sub_head" => $sub_head))
            ->where(array("organisation_id" => $organisation));

        $select->where->in('transaction', $sub0);
        $select->order(array('transaction ASC'));
        $select->order(array('created ASC'));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $results = $resultSet->initialize($result)->toArray();

        foreach ( $results as $result ):
            $sum = $result['total'];
        endforeach;

        return $sum;
    }

    public function getOpeningBalanceCBforBRS($organisation, $end_date, $subhead_id) {
        $sql = new Sql($this->dbAdapter);

        $year = date('Y', strtotime($end_date));
        $sub0 = new Select("accounts_closing_balance");
        $sub0->columns(array(new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
        $sub0->where->LessThanOrEqualTo("year", $year);
        $sub0->where(array('sub_head' => $subhead_id));
        $sub0->where(array('organisation_id' => $organisation));

        $total_debit = $this->getSumbySubheadCBforBRS($organisation, $end_date, 'debit', $subhead_id);
        $total_credit = $this->getSumbySubheadCBforBRS($organisation, $end_date, 'credit', $subhead_id);

        $stmt = $sql->prepareStatementForSqlObject($sub0);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $balances = $resultSet->initialize($result)->toArray();

        foreach ( $balances as $balance ) ;

        return $balance['total_closing_dr'] - $balance['total_closing_cr'] + $total_debit - $total_credit;
    }

    public function getOpeningBalanceBSforBRS($organisation, $end_date, $subhead_id) {
        $sql = new Sql($this->dbAdapter);

        $year = date('Y', strtotime($end_date));
        $sub0 = new Select("accounts_closing_balance");
        $sub0->columns(array(new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
        $sub0->where->LessThanOrEqualTo("year", $year);
        $sub0->where(array('sub_head' => $subhead_id));
        $sub0->where(array('organisation_id' => $organisation));

        $total_debit = $this->getSumbySubheadCBforBRS($organisation, $end_date, 'debit', $subhead_id);
        $total_credit = $this->getSumbySubheadCBforBRS($organisation, $end_date, 'credit', $subhead_id);

        $stmt = $sql->prepareStatementForSqlObject($sub0);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $balances = $resultSet->initialize($result)->toArray();

        foreach ( $balances as $balance ) ;

        return $balance['total_closing_dr'] - $balance['total_closing_cr'] + $total_debit - $total_credit;
    }

    public function getSumbySubheadCBforBRS($organisation, $end_date, $column, $sub_head) {

        $sub0 = new Select(array('t' => "accounts_transaction"));
        //$sub0 ->join(array('cd'=>'accounts_cheque_book_dtls'),'t.cheque_detail_id = cd.id', array());
        $sub0->columns(array("id"))
            ->where(array("t.status" => "3"))//committed status
            ->where->lessThan('voucher_date', $end_date);

        $sql = new Sql($this->dbAdapter);

        $select = $sql->select();

        $select->from('accounts_transaction_details')
            ->columns(array(new Expression('SUM(' . $column . ') as total')))
            ->where(array("sub_head" => $sub_head))
            ->where(array("organisation_id" => $organisation));

        $select->where->in('transaction', $sub0);

        $select->order(array('transaction ASC'));

        $select->order(array('created ASC'));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $results = $resultSet->initialize($result)->toArray();

        foreach ( $results as $result ):
            $sum = $result['total'];
        endforeach;

        return $sum;
    }

    public function getAmountDebitedCB($organisation, $start_date, $end_date, $column, $where) {
        $sub0 = new Select("accounts_transaction");
        $sub0->columns(array("id"))
            ->where(array("status" => "3"))//committed status
            ->where->between('voucher_date', $start_date, $end_date);

        $sql = new Sql($this->dbAdapter);

        $select = $sql->select();

        $select->from('accounts_transaction_details')
            ->columns(array(new Expression('SUM(' . $column . ') as total')))
            ->where($where);

        if ( $organisation != -1 ):
            $select->where(array("organisation_id" => $organisation));
        endif;

        $select->where->in('transaction', $sub0);

        $select->order(array('transaction ASC'));

        $select->order(array('created ASC'));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $results = $resultSet->initialize($result)->toArray();

        foreach ( $results as $result ):
            $sum = $result['total'];
        endforeach;

        return $sum;
    }

    public function getAmountDebitedBS($organisation, $start_date, $end_date, $column, $where) {
        $sub0 = new Select(array('t' => "accounts_transaction"));
        $sub0->join(array('cd' => 'accounts_cheque_book_dtls'), 't.cheque_id = cd.id', array());
        $sub0->columns(array("id"))
            ->where(array("t.status" => "3"))//committed status
            ->where->between('t.created', $start_date, $end_date);

        $sql = new Sql($this->dbAdapter);

        $select = $sql->select();

        $select->from('accounts_transaction_details')
            ->columns(array(new Expression('SUM(' . $column . ') as total')))
            ->where($where);

        if ( $organisation != -1 ):
            $select->where(array("organisation_id" => $organisation));
        endif;

        $select->where->in('transaction', $sub0);

        $select->order(array('transaction ASC'));

        $select->order(array('created ASC'));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $results = $resultSet->initialize($result)->toArray();

        foreach ( $results as $result ):
            $sum = $result['total'];
        endforeach;

        return $sum;
    }

    public function getTransactionClass($organisation, $start_date, $end_date) {
        $sql = new Sql($this->dbAdapter);
        $year = date('Y', strtotime($start_date));

        $sub = $sql->select();
        $sub->from('accounts_closing_balance')
            ->columns(array("sub_head"))
            ->where->lessThanOrEqualTo("year", $year);

        $sub9 = $sql->select();
        $sub9->from("accounts_sub_head")
            ->columns(array("head"))
            ->where->in("id", $sub);

        $sub0 = $sql->select();
        $sub0->from("accounts_transaction")
            ->columns(array("id"))
            ->where(array("status" => "3"))//committed status
            ->where->between('voucher_date', $start_date, $end_date)
            ->OR->lessThan('voucher_date', $start_date);

        $sub1 = $sql->select();
        $sub1->from("accounts_transaction_details")
            ->columns(array("head"));
        if ( $organisation != -1 ):
            $sub1->where(array("organisation_id" => $organisation));
        endif;
        $sub1->where->in("transaction", $sub0);

        $sub2 = $sql->select();
        $sub2->from("accounts_head")
            ->columns(array("group"));
        $sub2->where
            ->nest
            ->in('id', $sub1)
            ->OR->in('id', $sub9)
            ->unnest;

        $sub3 = $sql->select();
        $sub3->from("accounts_group")
            ->columns(array("class"))
            ->where->in("id", $sub2);

        $select = $sql->select();
        $select->from('accounts_class')
            ->where->in('id', $sub3);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result)->toArray();
    }

    /**
     *
     * get Group present in transactions details
     * @param Array $options
     * @param Array $where
     *
     **/
    public function getTransactionGroup($organisation, $start_date, $end_date, $where) {
        $sql = new Sql($this->dbAdapter);
        $year = date('Y', strtotime($start_date));

        $sub = $sql->select();
        $sub->from("accounts_closing_balance")
            ->columns(array("sub_head"))
            ->where->lessThanOrEqualTo("year", $year);

        $sub9 = $sql->select();
        $sub9->from("accounts_sub_head")
            ->columns(array("head"))
            ->where->in("id", $sub);

        $sub0 = $sql->select();
        $sub0->from("accounts_transaction")
            ->columns(array("id"))
            ->where(array("status" => "3"))//committed status
            ->where->between('voucher_date', $start_date, $end_date);

        $sub1 = $sql->select();
        $sub1->from("accounts_transaction_details")
            ->columns(array("head"));
        if ( $organisation != -1 ):
            $sub1->where(array("organisation_id" => $organisation));
        endif;
        $sub1->where->in("transaction", $sub0);

        $sub2 = $sql->select();
        $sub2->from("accounts_head")
            ->columns(array("group"));
        $sub2->where
            ->nest
            ->in('id', $sub1)
            ->OR->in('id', $sub9)
            ->unnest;

        $select = $sql->select();
        $select->from('accounts_group')
            ->where($where)
            ->where->in('id', $sub2);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result)->toArray();
    }

    /**
     *
     * get Head present in transactions details
     * @param Date $start_date
     * @param Date $end_date
     *
     **/
    public function getTransactionHead($organisation, $start_date, $end_date, $where) {
        $sql = new Sql($this->dbAdapter);
        $year = date('Y', strtotime($start_date));

        $sub = $sql->select();
        $sub->from("accounts_closing_balance")
            ->columns(array("sub_head"))
            ->where->lessThanOrEqualTo("year", $year);

        $sub9 = $sql->select();
        $sub9->from("accounts_sub_head")
            ->columns(array("head"))
            ->where->in("id", $sub);

        $sub0 = $sql->select();
        $sub0->from("accounts_transaction")
            ->columns(array("id"))
            ->where(array("status" => "3"))//committed status
            ->where->between('voucher_date', $start_date, $end_date);

        $sub1 = $sql->select();
        $sub1->from("accounts_transaction_details")
            ->columns(array("head"));
        if ( $organisation != -1 ):
            $sub1->where(array("organisation_id" => $organisation));
        endif;
        $sub1->where->in("transaction", $sub0);

        $select = $sql->select();
        $select->from('accounts_head')
            ->where($where)
            ->where
            ->nest
            ->in('id', $sub1)
            ->OR->in('id', $sub9)
            ->unnest;

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result)->toArray();
    }

    /**
     *
     * get Subhead present in transactions details
     * @param Date $start_date
     * @param Date $end_date
     *
     **/
    public function getTransactionSubhead($organisation, $start_date, $end_date, $where) {
        $sql = new Sql($this->dbAdapter);
        $year = date('Y', strtotime($start_date));

        $sub = $sql->select();
        $sub->from("accounts_closing_balance")
            ->columns(array("sub_head"))
            ->where->lessThanOrEqualTo("year", $year);

        $sub0 = $sql->select();
        $sub0->from("accounts_transaction")
            ->columns(array("id"))
            ->where(array("status" => "3"))
            ->where->between('voucher_date', $start_date, $end_date);

        $sub1 = $sql->select();
        $sub1->from("accounts_transaction_details")
            ->columns(array("sub_head"));
        if ( $organisation != -1 ):
            $sub1->where(array("organisation_id" => $organisation));
        endif;
        $sub1->where->in("transaction", $sub0);

        $select = $sql->select();
        $select->from('accounts_sub_head')
            ->where($where)
            ->where
            ->nest
            ->in('id', $sub1)
            ->OR->in('id', $sub)
            ->unnest;
        $select->order('id');

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result)->toArray();
    }

    /**
     * get sum by class
     * @param String $column
     * @param Array $options
     * @param String $column
     * @param int $class
     * @return int
     */
    public function getSumbyClass($organisation, $start_date, $end_date, $column, $class) {
        $sql = new Sql($this->dbAdapter);
        $year = date('Y', strtotime($start_date));

        $sub0 = $sql->select();
        $sub0->from("accounts_transaction")
            ->columns(array("id"))
            ->where(array("status" => "3"))//committed status
            ->where->between('voucher_date', $start_date, $end_date);

        $sub1 = $sql->select();
        $sub1->from("accounts_group")
            ->columns(array("id"))
            ->where(array("class" => $class));

        $sub2 = $sql->select();
        $sub2->from("accounts_head")
            ->columns(array("id"))
            ->where->in("group", $sub1);

        $select = $sql->select();
        $select->from('accounts_transaction_details')
            ->columns(array(new Expression('SUM(' . $column . ') as total')));
        if ( $organisation != -1 ):
            $select->where(array("organisation_id" => $organisation));
        endif;
        $select->where->in('head', $sub2)
            ->where->in('transaction', $sub0);
        $select->order(array('transaction ASC'))
            ->order(array('created ASC'));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $results = $resultSet->initialize($result)->toArray();

        foreach ( $results as $resultdata ):
            $sum = $resultdata['total'];
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
    public function getSumbyGroup($organisation, $start_date, $end_date, $column, $group) {
        $sql = new Sql($this->dbAdapter);

        $sub0 = $sql->select();
        $sub0->from("accounts_transaction")
            ->columns(array("id"))
            ->where(array("status" => "3"))//committed status
            ->where->between('voucher_date', $start_date, $end_date);

        $sub1 = $sql->select();
        $sub1->from("accounts_head")
            ->columns(array("id"))
            ->where(array("group" => $group));

        $select = $sql->select();
        $select->from('accounts_transaction_details')
            ->columns(array(new Expression('SUM(' . $column . ') as total')));
        if ( $organisation != -1 ):
            $select->where(array("organisation_id" => $organisation));
        endif;
        $select->where->in('head', $sub1)
            ->where->in('transaction', $sub0);
        $select->order(array('transaction ASC'))
            ->order(array('created ASC'));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $results = $resultSet->initialize($result)->toArray();

        foreach ( $results as $resultdata ):
            $sum = $resultdata['total'];
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
    public function getSumbyHeadandSubhead($type, $organisation, $start_date, $end_date, $column, $head) {
        $sql = new Sql($this->dbAdapter);

        $sub0 = $sql->select();
        $sub0->from("accounts_transaction");
        $sub0->columns(array("id"))
            ->where(array("status" => "3"))//committed status
            ->where->between('voucher_date', $start_date, $end_date);

        $select = $sql->select();
        $select->from('accounts_transaction_details')
            ->columns(array(new Expression('SUM(' . $column . ') as total')));
        if ( $type == 'HEAD' ) {
            $select->where(array("head" => $head));
        } elseif ( $type == 'SUB' ) {
            $select->where(array("sub_head" => $head));
        }
        if ( $organisation != -1 ):
            $select->where(array("organisation_id" => $organisation));
        endif;
        $select->where->in('transaction', $sub0);
        $select->order(array('transaction ASC'))
            ->order(array('created ASC'));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $results = $resultSet->initialize($result)->toArray();

        foreach ( $results as $resultdata ):
            $sum = $resultdata['total'];
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
    public function getOpeningBalance($organisation, $start_date, $end_date, $id, $tier) {
        $sql = new Sql($this->dbAdapter);
        $year = date('Y', strtotime($start_date));

        $sub0 = $sql->select();
        $sub0->from("accounts_closing_balance");
        $sub1 = $sql->select();
        $sub1->from("accounts_sub_head");
        $sub2 = $sql->select();
        $sub2->from("accounts_head");
        if ( $tier == 1 ):
            $sub0->columns(array(new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
            $sub0->where->lessThan("year", $year);
            $sub0->where(array('sub_head' => $id, 'organisation_id' => $organisation));

            $total_debit = $this->getSumbyHeadandSubheadTBOpening('SUB', $organisation, $start_date, 'debit', $id);
            $total_credit = $this->getSumbyHeadandSubheadTBOpening('SUB', $organisation, $start_date, 'credit', $id);
        elseif ( $tier == 2 ):
            $sub1->columns(array("id"))
                ->where(array("head" => $id));

            $sub0->columns(array(new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
            $sub0->where->lessThan("year", $year)
                ->where->equalTo('organisation_id', $organisation)
                ->where->in('sub_head', $sub1);

            $total_debit = $this->getSumbyHeadandSubheadTBOpening('HEAD', $organisation, $start_date, 'debit', $id);
            $total_credit = $this->getSumbyHeadandSubheadTBOpening('HEAD', $organisation, $start_date, 'credit', $id);
        elseif ( $tier == 3 ):
            $sub2->from("accounts_head")
                ->columns(array("id"))
                ->where(array("group" => $id));

            $sub1->columns(array("id"))
                ->where->in('head', $sub2);

            $sub0->columns(array(new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
            $sub0->where->lessThan("year", $year)
                ->where->equalTo('organisation_id', $organisation)
                ->where->in('sub_head', $sub1);

            $total_debit = $this->getSumbyGroupTBOpening($organisation, $start_date, 'debit', $id);
            $total_credit = $this->getSumbyGroupTBOpening($organisation, $start_date, 'credit', $id);
        elseif ( $tier == 4 ):
            $sub3 = $sql->select();
            $sub3->from("accounts_group")
                ->columns(array("id"))
                ->where(array("class" => $id));

            $sub2->columns(array("id"))
                ->where->in('group', $sub3);

            $sub1->columns(array("id"))
                ->where->in('head', $sub2);

            $sub0->columns(array(new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
            $sub0->where->lessThan("year", $year)
                ->where->equalTo('organisation_id', $organisation)
                ->where->in('sub_head', $sub1);

            $total_debit = $this->getSumbyClassTBOpening($organisation, $start_date, 'debit', $id);
            $total_credit = $this->getSumbyClassTBOpening($organisation, $start_date, 'credit', $id);
        endif;

        $stmt = $sql->prepareStatementForSqlObject($sub0);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $balances = $resultSet->initialize($result)->toArray();

        foreach ( $balances as $balance ) ;
        return $balance['total_closing_dr'] - $balance['total_closing_cr'] + $total_debit - $total_credit;
    }

    /**
     * get sum by head TBO
     * @param String $column
     * @param Array $options
     * @param String $column
     * @param Int $head
     * @return int
     */
    public function getSumbyHeadandSubheadTBOpening($type, $organisation, $start_date, $column, $head) {
        $sql = new Sql($this->dbAdapter);

        $sub0 = $sql->select();
        $sub0->from("accounts_transaction")
            ->columns(array("id"))
            ->where(array("status" => "3"))//committed status
            ->where->lessThan('voucher_date', $start_date);

        $select = $sql->select();
        $select->from('accounts_transaction_details')
            ->columns(array(new Expression('SUM(' . $column . ') as total')));

        if ( $type == 'HEAD' ) {
            $select->where(array("head" => $head));
        } elseif ( $type == 'SUB' ) {
            $select->where(array("sub_head" => $head));
        }
        if ( $organisation != -1 ):
            $select->where(array("organisation_id" => $organisation));
        endif;

        $select->where->in('transaction', $sub0);
        $select->order(array('transaction ASC'))
            ->order(array('created ASC'));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $results = $resultSet->initialize($result)->toArray();

        foreach ( $results as $resultdata ):
            $sum = $resultdata['total'];
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
    public function getSumbyGroupTBOpening($organisation, $start_date, $column, $group) {
        $sql = new Sql($this->dbAdapter);

        $sub0 = $sql->select();
        $sub0->from("accounts_transaction");
        $sub0->columns(array("id"))
            ->where(array("status" => "3"))//committed status
            ->where->lessThan('voucher_date', $start_date);

        $sub1 = $sql->select();
        $sub1->from("accounts_head");
        $sub1->columns(array("id"))
            ->where(array("group" => $group));

        $select = $sql->select();
        $select->from('accounts_transaction_details')
            ->columns(array(new Expression('SUM(' . $column . ') as total')));
        if ( $organisation != -1 ):
            $select->where(array("organisation_id" => $organisation));
        endif;

        $select->where->in('head', $sub1)
            ->where->in('transaction', $sub0);
        $select->order(array('transaction ASC'))
            ->order(array('created ASC'));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $results = $resultSet->initialize($result)->toArray();

        foreach ( $results as $resultdata ):
            $sum = $resultdata['total'];
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
    public function getSumbyClassTBOpening($organisation, $start_date, $column, $class) {
        $sql = new Sql($this->dbAdapter);

        $sub0 = $sql->select();
        $sub0->from("accounts_transaction")
            ->columns(array("id"))
            ->where(array("status" => "3"))//committed status
            ->where->lessThan('voucher_date', $start_date);

        $sub1 = $sql->select();
        $sub1->from("accounts_group")
            ->columns(array("id"))
            ->where(array("class" => $class));

        $sub2 = $sql->select();
        $sub2->from("accounts_head")
            ->columns(array("id"))
            ->where->in("group", $sub1);

        $select = $sql->select();
        $select->from('accounts_transaction_details')
            ->columns(array(new Expression('SUM(' . $column . ') as total')));
        if ( $organisation != -1 ):
            $select->where(array("organisation_id" => $organisation));
        endif;

        $select->where->in('head', $sub2)
            ->where->in('transaction', $sub0);
        $select->order(array('transaction ASC'))
            ->order(array('created ASC'));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $results = $resultSet->initialize($result)->toArray();

        foreach ( $results as $resultdata ):
            $sum = $resultdata['total'];
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
    public function getClosingBalanceAL($organisation, $start_date, $end_date, $id, $tier) {
        $sql = new Sql($this->dbAdapter);

        $year = date('Y', strtotime($start_date));
        $year1 = date('Y', strtotime($start_date)) - 10;
        $starting_date = date('Y-m-d', strtotime('01-01-' . $year1));
        $ending_date = $end_date;

        $sub0 = $sql->select();
        $sub0->from("accounts_closing_balance");
        $sub1 = $sql->select();
        $sub1->from("accounts_sub_head");
        $sub2 = $sql->select();
        $sub2->from("accounts_head");

        if ( $tier == 1 ):
            $sub0->columns(array(new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')))
                ->where(array('sub_head' => $id))
                ->where(array('organisation_id' => $organisation))
                ->where->lessThan("year", $year);

            $total_debit = $this->getSumbyHeadandSubhead('SUB', $organisation, $starting_date, $ending_date, 'debit', $id);
            $total_credit = $this->getSumbyHeadandSubhead('SUB', $organisation, $starting_date, $ending_date, 'credit', $id);
        elseif ( $tier == 2 ):
            $sub1->columns(array("id"))
                ->where(array("head" => $id));

            $sub0->columns(array(new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
            $sub0->where->lessThan("year", $year)
                ->where->in('sub_head', $sub1);
            $sub0->where(array('organisation_id' => $organisation));

            $total_debit = $this->getSumbyHeadandSubhead('HEAD', $organisation, $starting_date, $ending_date, 'debit', $id);
            $total_credit = $this->getSumbyHeadandSubhead('HEAD', $organisation, $starting_date, $ending_date, 'credit', $id);
        elseif ( $tier == 3 ):
            $sub2->columns(array("id"))
                ->where(array("group" => $id));

            $sub1->columns(array("id"))
                ->where->in('head', $sub2);

            $sub0->columns(array(new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
            $sub0->where->lessThan("year", $year)
                ->where->in('sub_head', $sub1);
            $sub0->where(array('organisation_id' => $organisation));

            $total_debit = $this->getSumbyGroup($organisation, $starting_date, $ending_date, 'debit', $id);
            $total_credit = $this->getSumbyGroup($organisation, $starting_date, $ending_date, 'credit', $id);
        elseif ( $tier == 4 ):
            $sub3 = $sql->select();
            $sub3->from("accounts_group");
            $sub3->columns(array("id"))
                ->where(array("class" => $id));

            $sub2->columns(array("id"))
                ->where->in('group', $sub3);

            $sub1->columns(array("id"))
                ->where->in('head', $sub2);

            $sub0->columns(array(new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
            $sub0->where->lessThan("year", $year)
                ->where->in('sub_head', $sub1);
            $sub0->where(array('organisation_id' => $organisation));

            $total_debit = $this->getSumbyClass($organisation, $starting_date, $ending_date, 'debit', $id);
            $total_credit = $this->getSumbyClass($organisation, $starting_date, $ending_date, 'credit', $id);
        endif;

        $stmt = $sql->prepareStatementForSqlObject($sub0);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $balances = $resultSet->initialize($result)->toArray();

        foreach ( $balances as $balance ) ;
        return $balance['total_closing_dr'] - $balance['total_closing_cr'] + $total_debit - $total_credit;
    }

    /**
     * Calculate closing balance
     * @param Array $options
     * @param Int $id
     * @param Ind $tier
     * @return Int
     */
    public function getClosingBalanceIE($organisation, $start_date, $end_date, $id, $tier) {
        $sql = new Sql($this->dbAdapter);

        $year = date('Y', strtotime($start_date));
        $starting_date = date('Y-m-d', strtotime($start_date));
        $ending_date = $end_date;

        $sub0 = $sql->select();
        $sub0->from("accounts_closing_balance");
        $sub1 = $sql->select();
        $sub1->from("accounts_sub_head");
        $sub2 = $sql->select();
        $sub2->from("accounts_head");

        if ( $tier == 1 ):
            $sub0->columns(array(new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')))
                ->where(array('sub_head' => $id))
                ->where(array('organisation_id' => $organisation))
                ->where->lessThan("year", $year);

            $total_debit = $this->getSumbyHeadandSubhead('SUB', $organisation, $starting_date, $ending_date, 'debit', $id);
            $total_credit = $this->getSumbyHeadandSubhead('SUB', $organisation, $starting_date, $ending_date, 'credit', $id);
        elseif ( $tier == 2 ):
            $sub1->columns(array("id"))
                ->where(array("head" => $id));

            $sub0->columns(array(new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
            $sub0->where->lessThan("year", $year)
                ->where->in('sub_head', $sub1);
            $sub0->where(array('organisation_id' => $organisation));

            $total_debit = $this->getSumbyHeadandSubhead('HEAD', $organisation, $starting_date, $ending_date, 'debit', $id);
            $total_credit = $this->getSumbyHeadandSubhead('HEAD', $organisation, $starting_date, $ending_date, 'credit', $id);
        elseif ( $tier == 3 ):
            $sub2->columns(array("id"))
                ->where(array("group" => $id));

            $sub1->columns(array("id"))
                ->where->in('head', $sub2);

            $sub0->columns(array(new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
            $sub0->where->lessThan("year", $year)
                ->where->in('sub_head', $sub1);
            $sub0->where(array('organisation_id' => $organisation));

            $total_debit = $this->getSumbyGroup($organisation, $starting_date, $ending_date, 'debit', $id);
            $total_credit = $this->getSumbyGroup($organisation, $starting_date, $ending_date, 'credit', $id);
        elseif ( $tier == 4 ):
            $sub3 = $sql->select();
            $sub3->from("accounts_group");
            $sub3->columns(array("id"))
                ->where(array("class" => $id));

            $sub2->columns(array("id"))
                ->where->in('group', $sub3);

            $sub1->columns(array("id"))
                ->where->in('head', $sub2);

            $sub0->columns(array(new Expression('SUM(closing_cr) as total_closing_cr'), new Expression('SUM(closing_dr) as total_closing_dr')));
            $sub0->where->lessThan("year", $year)
                ->where->in('sub_head', $sub1);
            $sub0->where(array('organisation_id' => $organisation));

            $total_debit = $this->getSumbyClass($organisation, $starting_date, $ending_date, 'debit', $id);
            $total_credit = $this->getSumbyClass($organisation, $starting_date, $ending_date, 'credit', $id);
        endif;

        $stmt = $sql->prepareStatementForSqlObject($sub0);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $balances = $resultSet->initialize($result)->toArray();

        foreach ( $balances as $balance ) ;
        return $balance['total_closing_dr'] - $balance['total_closing_cr'] + $total_debit - $total_credit;
    }

    public function getTransactionHeadforBS($organisation, $start_date, $end_date, $where) {

        $sql = new Sql($this->dbAdapter);

        $prevoius_start_year = date('y', strtotime($start_date)) - 1;
        $prevoius_start_month = date('m', strtotime($start_date));
        $prevoius_start_day = date('d', strtotime($start_date));
        $pre_end_year = date('y', strtotime($end_date)) - 1;
        $pre_end_month = date('m', strtotime($end_date));
        $pre_end_day = date('d', strtotime($end_date));
        $pre_starting_date = date('Y-m-d', strtotime($prevoius_start_year . '-' . $prevoius_start_month . '-' . $prevoius_start_day));
        $pre_ending_date = date('Y-m-d', strtotime($pre_end_year . '-' . $pre_end_month . '-' . $pre_end_day));

        $year = date('Y', strtotime($start_date));
        $sub = new Select("accounts_closing_balance");
        $sub->columns(array("sub_head"))
            ->where->lessThanOrEqualTo("year", $year);

        $sub9 = new select("accounts_sub_head");
        $sub9->columns(array("head"))
            ->where->in("id", $sub);

        $sub0 = new Select("accounts_transaction");
        $sub0->columns(array("id"))
            ->where(array("status" => "3"))//committed status
            ->where->between('voucher_date', $start_date, $end_date)
            ->Or->where->between('voucher_date', $pre_starting_date, $pre_ending_date);

        $sub1 = new Select("accounts_transaction_details");
        $sub1->columns(array("head"));
        if ( $organisation != -1 ):
            $sub1->where(array("organisation_id" => $organisation));
        endif;
        $sub1->where->in("transaction", $sub0);

        $select = $sql->select();
        $select->from('accounts_head')
            ->where($where)
            ->where
            ->nest
            ->in('id', $sub1)
            ->OR->in('id', $sub9)
            ->unnest;

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $results = $resultSet->initialize($result)->toArray();

        return $results;
    }

    public function getTransactionSubheadforBS($organisation, $start_date, $end_date, $where) {
        $sql = new Sql($this->dbAdapter);

        $prevoius_start_year = date('y', strtotime($start_date)) - 1;
        $prevoius_start_month = date('m', strtotime($start_date));
        $prevoius_start_day = date('d', strtotime($start_date));
        $pre_end_year = date('y', strtotime($end_date)) - 1;
        $pre_end_month = date('m', strtotime($end_date));
        $pre_end_day = date('d', strtotime($end_date));
        $pre_starting_date = date('Y-m-d', strtotime($prevoius_start_year . '-' . $prevoius_start_month . '-' . $prevoius_start_day));
        $pre_ending_date = date('Y-m-d', strtotime($pre_end_year . '-' . $pre_end_month . '-' . $pre_end_day));

        $year = date('Y', strtotime($start_date));
        $sub = new Select("accounts_closing_balance");
        $sub->columns(array("sub_head"))
            ->where->lessThanOrEqualTo("year", $year);

        $sub0 = new Select("accounts_transaction");
        $sub0->columns(array("id"))
            ->where(array("status" => "3"))
            ->where->between('voucher_date', $start_date, $end_date)
            ->OR->where->between('voucher_date', $pre_starting_date, $pre_ending_date);

        $sub1 = new Select("accounts_transaction_details");
        $sub1->columns(array("sub_head"));

        if ( $organisation != -1 ):
            $sub1->where(array("organisation_id" => $organisation));
        endif;
        $sub1->where->in("transaction", $sub0);

        $select = $sql->select();
        $select->from('accounts_sub_head')
            ->where($where)
            ->where
            ->nest
            ->in('id', $sub1)
            ->OR->in('id', $sub)
            ->unnest;

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $results = $resultSet->initialize($result)->toArray();

        return $results;
    }

    /* Bank Statement */
    public function getTransactionSubheadforBankStatement($organisation, $start_date, $end_date, $where) {
        //TODO:: following is Old query do not remove
        /*$year = date('Y', strtotime($start_date));

        $sub = new Select("accounts_closing_balance");
        $sub->columns(array("sub_head"))->where->lessThanOrEqualTo("year", $year);

        $sub0 = new Select("accounts_transaction");
        $sub0->columns(array("id"))
            ->where(array("status" => "3"))
            ->where->between('voucher_date', $start_date, $end_date)
            ->OR->lessThan('voucher_date', $start_date);

        $sub1 = new Select("accounts_transaction_details");
        $sub1->columns(array("sub_head"));
        if ( $organisation != -1 ):
            $sub1->where(array("organisation_id" => $organisation));
        endif;
        $sub1->where->in("transaction", $sub0);

        $sql = new Sql($this->dbAdapter);

        $select = $sql->select();

        $select->from('accounts_sub_head')
            ->where($where)
            ->where
            ->nest
            ->in('id', $sub1)
            ->OR->in('id', $sub)
            ->unnest;
        $select->Limit(3);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $results = $resultSet->initialize($result)->toArray();

        return $results;*/

    }

    public function getBankStatement($organisation, $start_date, $end_date, $where) {
        $sql = new Sql($this->dbAdapter);

        $select = $sql->select();

        $select->from(array('td' => 'accounts_transaction_details'))
            ->join(array('t' => 'accounts_transaction'), 't.id = td.transaction', array('cheque_id', 'voucher_no', 'voucher_date', 'status', 'remark'));

        if ( $organisation != -1 ):
            $select->where(array("td.organisation_id" => $organisation));
        endif;

        $select->where->between('voucher_date', $start_date, $end_date);

        $select->where($where);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $results = $resultSet->initialize($result)->toArray();

        return $results;
    }

    public function deleteTable($tableName, $id) {

        $where = (is_array($id)) ? $id : array('id = ?' => $id);

        $deleteAction = new Delete($tableName);

        $deleteAction->where($where);

        $sql = new Sql($this->dbAdapter);

        $stmt = $sql->prepareStatementForSqlObject($deleteAction);
        $result = $stmt->execute();

        return (bool) $result->getAffectedRows();
    }

    public function getBankAccountBalanceFromTransaction($params) {
        $sql = new Sql($this->dbAdapter);

        $select = $sql->select();

        $sub0 = new Select("accounts_transaction");
        $sub0->columns(array("id"));
        $sub0->where(array("status" => "3", 'organisation_id' => $params['organisation_id']));

        $select->columns(
            array(
                'total_credit' => new Expression('SUM(credit)'),
                'total_debit' => new Expression('SUM(debit)')
            )
        );

        $select->from(array('td' => 'accounts_transaction_details'));
        $select->where(array('master_details' => $params['master_details']));
        $select->where->nest->in('transaction', $sub0)->unnest;

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $results = $resultSet->initialize($result)->toArray();

        return $results;
    }
}

<?php

namespace Accounts\Mapper;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class AssetDbSqlMapper implements AssetMapperInterface {

    /**
     * @var AdapterInterface
     */
    protected $dbAdapter;

    /**
     * @var HydratorInterface
     */
    protected $hydrator;

    /**
     * MasterDbSqlMapper constructor.
     * @param AdapterInterface $dbAdapter
     * @param HydratorInterface $hydrator
     */
    public function __construct(AdapterInterface $dbAdapter, HydratorInterface $hydrator) {
        $this->dbAdapter = $dbAdapter;
        $this->hydrator = $hydrator;
    }

    /**
     * @param $tableName
     * @return \Zend\Db\ResultSet\AbstractResultSet
     */
    public function listAll($tableName) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        if ( $tableName === 'organisation' ) {

            $select->from(array('t1' => $tableName));
            $select->columns(array('id', 'organisation_name'));

        } else if ( $tableName === 'student_fee_category' ) {

            $select->from(array('h' => $tableName))
                ->join(array('g' => 'accounts_group'), 'g.id=h.group', array('group' => 'name', 'group_id' => 'id'))
                ->join(array('c' => 'accounts_class'), 'c.id=g.class', array('class' => 'name', 'class_id' => 'id'))
                ->order(array('code ASC'));

        } else {
            $select->from(array('t1' => $tableName));
        }

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }

    /**
     * @param $tableName
     * @param $id
     * @return \Zend\Db\ResultSet\AbstractResultSet
     */
    public function findDetails($tableName, $id) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        if ( $tableName === 'programmes' ) {

            $select->from(array('t1' => $tableName));
            $select->columns(array('id', 'programme_name'));
            $select->where(array('organisation_id = ?' => $id));

        } else if ( $tableName === 'student_fee_category' ) {

            $select->from(array('t1' => $tableName));
            $select->columns(array('id', 'fee_category'));
            $select->where(array('organisation_id = ?' => $id));

        } else if ( $tableName === 'student_fee_category' . 'report-option' ) {

            $select->from(array('t1' => 'student_fee_category'));
            $select->where(array('organisation_id = ?' => $id));

        } else if ( $tableName === 'student_fee_category' . 'all' ) {

            $select->from(array('t1' => 'student_fee_category'));
            $select->where(array('id = ?' => $id));

        } else {
            $select->from(array('t1' => $tableName));
            $select->where(array('id = ?' => $id));
        }

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }

    /**
     * @param $username
     * @return mixed
     */
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

    /**
     * @param $tablename
     * @return array
     */
    public function getTableData($tablename) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from($tablename);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result)->toArray();
    }

    /**
     * @param $table_name
     * @param $data
     * @return mixed|null
     */
    public function saveupdateData($table_name, $data) 
    { 
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

    /**
     * @param $tablename
     * @param $params
     * @param null $column
     * @return array
     */
    public function getDatabyParam($tablename, $params, $column = null) {
        $sql = new Sql($this->dbAdapter);

        $where = (is_array($params)) ? $params : array('id' => $params);

        $select = $sql->select();

        if ( $tablename == "accounts_bank_account" ) {
            $where = (is_array($params)) ? $params : array('ba.id' => $params);

            $select->from(array('ba' => $tablename))
                ->join(array('o' => 'organisation'), 'o.id=ba.organisation_id', array('organisation' => 'organisation_name', 'organisation_id' => 'id'));

        } elseif ( $tablename == "supplier_details" ) {

            $where = (is_array($params)) ? $params : array('p.id' => $params);

            $select->from(array('p' => $tablename))
                ->join(array('o' => 'organisation'), 'o.id = p.organisation_id', array('organisation' => 'organisation_name', 'organisation_id' => 'id'))
                ->join(array('r' => 'accounts_party_role'), 'r.id = p.party_role', array('party_role' => 'party_role', 'party_role_id' => 'id'));

        } elseif ( $tablename == "accounts_master_details" ) {

            $where = (is_array($params)) ? $params : array('md.id' => $params);

            $select->from(array('md' => $tablename))
                ->join(array('sh' => 'accounts_sub_head'), 'sh.id=md.sub_head', array('sub_head' => 'name', 'subhead_id' => 'id'))
                ->join(array('h' => 'accounts_head'), 'h.id=sh.head', array('head' => 'name', 'head_id' => 'id'))
                ->join(array('g' => 'accounts_group'), 'g.id=h.group', array('group' => 'name', 'group_id' => 'id'))
                ->join(array('c' => 'accounts_class'), 'c.id=g.class', array('class' => 'name', 'class_id' => 'id'))
                ->join(array('t' => 'accounts_type'), 't.id=md.type', array('type_id' => 'id'));

        } elseif ( $tablename == 'accounts_cash_account' ) {
            $where = (is_array($params)) ? $params : array('ca.id' => $params);

            $select->from(array('ca' => $tablename))
                ->join(array('o' => 'organisation'), 'o.id= ca.organisation_id', array('organisation' => 'organisation_name', 'organisation_id' => 'id'));
        } elseif ( $tablename == 'accounts_sub_head.single-column' ) {
            $where = (is_array($params)) ? $params : array('id' => $params);

            $select->from('accounts_sub_head');

        } elseif ( $tablename == 'accounts_head' ) {
            $where = ( is_array($params) )? $params: array('h.id' => $params);

            $select->from(array('h' => $tablename))
                ->join(array('g' => 'accounts_group'), 'g.id=h.group', array('group' => 'name', 'group_id' => 'id'))
                ->join(array('c' => 'accounts_class'), 'c.id=g.class', array('class' => 'name', 'class_id' => 'id'));

        } else {
            $select->from($tablename);
        }

        if ( $column ) {
            $select->columns(array($column));
        }

        if ( $params ) {
            $select->where($where);
        }

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();

        if ( $tablename == 'accounts_sub_head.single-column' ) {
            $columns = 0;

            foreach ( $resultSet->initialize($result) as $row ):
                $columns = $row[$column];
            endforeach;

            return $columns;
        }

        return $resultSet->initialize($result)->toArray();
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

        if ( $type == "getNotInMD" ) { 
            $sub0 = new Select("accounts_master_details");
            $sub0->columns(array("sub_head"))->where->equalTo('ref_id', $params);

            $select->from(array('sh' => $tablename))
                ->join(array('md' => 'accounts_master_details'), 'md.sub_head=sh.id', array('sub_head' => 'code', 'subhead_id' => 'id'))
                ->where->Notin('sh.id', $sub0);

            $select->where(array("md.type" => array(9)));

        } elseif ( $type == "getNotIn-MD" ) {

            $param = (is_array($params)) ? $params : array($params);

            $where = (is_array($column)) ? $column : NULL;

            $column = (is_array($column)) ? 'id' : $column;

            $select->from($tablename)->columns(array('id'))->where->notIn($column, $param);

            if ( $where != Null ) {
                $select->where($where);
            }

        } else {
            $select->from($tablename);
        }

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result)->toArray();
    }

    /**
     * @param $tableName
     * @param $id
     * @return bool
     */
    public function deleteTable($tableName, $id) {

        $where = (is_array($id)) ? $id : array('id = ?' => $id);

        $deleteAction = new Delete($tableName);

        $deleteAction->where($where);

        $sql = new Sql($this->dbAdapter);

        $stmt = $sql->prepareStatementForSqlObject($deleteAction);
        $result = $stmt->execute();

        return (bool) $result->getAffectedRows();
    }
}

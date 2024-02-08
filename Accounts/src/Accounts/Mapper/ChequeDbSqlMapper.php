<?php

namespace Accounts\Mapper;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ChequeDbSqlMapper implements ChequeMapperInterface {

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

        if ( $tablename === 'accounts_bank_account' ) :

            $where = ( is_array($params) )? $params: array('ba.id' => $params);

            $select->from(array('ba' => $tablename))
                ->join(array('o' => 'organisation'), 'o.id=ba.organisation_id', array('organisation' => 'organisation_name', 'organisation_id' => 'id'))
                ->where($where);

        elseif( $tablename == 'accounts_cheque_book_dtls' ):

            $where = ( is_array($params) )? $params: array('cd.id' => $params);

            $select->from(array('cd' => $tablename));
        else:

            $select->from($tablename);

        endif;

        if ( $column ) {
            $select->columns(array($column));
        }

        if ( $params ) {
            $select->where($where);
        }

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();

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

        if ( $type == "acbGetMin" ) {

            $select->from($tablename);
            $select->columns(array(
                'min' => new Expression('MIN(' . $column . ')')
            ));

        } else if ( $type == 'getLocationDateWise' ) {

            $select->from(array('c' => $tablename))
                ->columns(array(
                    'id', 'cheque_code', 'receive_date', 'organisation_id', 'issue_date', 'bank_account', 'start_cheque_no', 'end_cheque_no',
                    'no_of_cheque', 'author', 'created', 'modified',
                    'year' => new Expression('YEAR(' . $column . ')'),
                    'month' => new Expression('MONTH(' . $column . ')'),
                    'date' => new Expression('DAY(' . $column . ')'),
                ))
                ->order(array('id DESC'));

            if ( $params['userorg'] != '-1' ) {
                $select->where(array('organisation_id' => $params['userorg']));
            }

            if ( $params['month'] != '-1' ) {
                $select->having(array('month' => $params['month']));
            }

            if ( $params['year'] != '-1' ) {
                $select->having(array('year' => $params['year']));
            }

        } else if ( $type == 'getMonthlyCQ' ) {

            $select->from($tablename);
            $select->where->like('cheque_code', $params . "%");

        } else {

            $select->from($tablename);

        }

        $stmt = $sql->prepareStatementForSqlObject($select);

        $result = $stmt->execute();

        $resultSet = new ResultSet();

        if ( $type == "acbGetMin" ) {
            $results = $resultSet->initialize($result)->toArray();

            foreach ( $results as $result ):
                $column = $result['min'];
            endforeach;

            return $column;
        }

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

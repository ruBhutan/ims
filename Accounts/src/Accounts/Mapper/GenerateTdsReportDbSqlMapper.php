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

class GenerateTdsReportDbSqlMapper implements GenerateTdsReportMapperInterface {

    protected $dbAdapter;

    protected $hydrator;

    public function __construct(AdapterInterface $dbAdapter, HydratorInterface $hydrator) {
        $this->dbAdapter = $dbAdapter;
        $this->hydrator = $hydrator;
    }

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

        $select->from($tablename);

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

    public function getDatabyParam($tablename, $params, $column = null) {
        $sql = new Sql($this->dbAdapter);

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

        return $resultSet->initialize($result)->toArray();
    }

    public function getDataByFilter($type, $tablename, $params, $column = null) {
        $sql = new Sql($this->dbAdapter);

        $select = $sql->select();

        if ( $type == "student-with-organisation" ) {

            $select->from(array('st' => $tablename))
                ->join(
                    array('oz' => 'organisation'),
                    'oz.id=st.organisation_id',
                    array('organisation_name' => 'organisation_name', 'organisation_code' => 'abbr')
                );

        } else {
            $select->from($tablename);
        }

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result)->toArray();
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

    public function getEmployeeDetailsData($param) {
        $where = (is_array($param)) ? $param : array('ed.id' => $param);

        $adapter = $this->dbAdapter;

        $sql = new Sql($adapter);

        $select = $sql->select();

        $select->from(array('ed' => 'employee_details'))->where($where);

        $selectString = $sql->getSqlStringForSqlObject($select);

        $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();

        return $results;
    }

    public function getOrganisationData($param = null) {
        $where = (is_null($param) ? null : ((is_array($param)) ? $param : array('o.id' => $param)));

        $adapter = $this->dbAdapter;

        $sql = new Sql($adapter);

        $select = $sql->select();

        $select->from(array('o' => 'organisation'));

        if ( !is_null($where) ) {
            $select->where($where);
        }

        $selectString = $sql->getSqlStringForSqlObject($select);

        $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();

        return $results;
    }

    public function getPayrollTableData($param) {
        /* TODO :: IF pay_heads change from database, check following IDS is proper or not
            Table Name : payr_pay_heads
            pay_head Column         : IDS
            Basic Salary            : 1
            Provident Fund          : 12
            Group Insurance Scheme  : 13
            Health Tax              : 14
            Personal Income Tax     : 15
        */

        $extraFields = [
            'payr_pay_heads' => [
                'BS' => 1,
                'GPF' => 12,
                'GIS' => 13,
                'HC' => 14,
                'TDS' => 15,
            ]
        ];

        $HCSelect = new Select();
        $HCSelect->from(array('ppd' => 'payr_pay_details'));
        $HCSelect->columns(array(new Expression('amount')));
        $HCSelect->where->addPredicates('ppd.pay_roll = pr.id and ppd.pay_head = ' . $extraFields['payr_pay_heads']['HC']);

        $TDSSelect = new Select();
        $TDSSelect->from(array('ppd' => 'payr_pay_details'));
        $TDSSelect->columns(array(new Expression('amount')));
        $TDSSelect->where->addPredicates('ppd.pay_roll = pr.id and ppd.pay_head = ' . $extraFields['payr_pay_heads']['TDS']);

        $GISSelect = new Select();
        $GISSelect->from(array('ppd' => 'payr_pay_details'));
        $GISSelect->columns(array(new Expression('amount')));
        $GISSelect->where->addPredicates('ppd.pay_roll = pr.id and ppd.pay_head = ' . $extraFields['payr_pay_heads']['GIS']);

        $GPFSelect = new Select();
        $GPFSelect->from(array('ppd' => 'payr_pay_details'));
        $GPFSelect->columns(array(new Expression('amount')));
        $GPFSelect->where->addPredicates('ppd.pay_roll = pr.id and ppd.pay_head = ' . $extraFields['payr_pay_heads']['GPF']);

        $BSSelect = new Select();
        $BSSelect->from(array('ppd' => 'payr_pay_details'));
        $BSSelect->columns(array(new Expression('amount')));
        $BSSelect->where->addPredicates('ppd.pay_roll = pr.id and ppd.pay_head = ' . $extraFields['payr_pay_heads']['BS']);

        $where = (is_array($param)) ? $param['where'] : array('pr.id' => $param);

        $adapter = $this->dbAdapter;

        $sql = new Sql($adapter);

        $select = $sql->select();

        $select->from(array('pr' => 'payr_payroll'))
            ->join(array('emp' => 'employee_details'), 'pr.employee_details = emp.id', array('employee_details' => 'id', 'first_name', 'middle_name', 'last_name', 'emp_id', 'cid'))
            ->join(array('empljp' => 'job_profile'), 'empljp.id=pr.empl_payroll', array())
            ->join(array('t' => 'employee_type'), 'empljp.emp_type_id = t.id', array('emp_type_id' => 'id', 'employee_type'))
            ->join(array('o' => 'organisation'), 'o.id= empljp.organisation_id', array('organisation' => 'organisation_name', 'organisation_id' => 'id'))
            ->where($where);

        if ( isset($param['between_month']) ) {
            $select->where->between('month', $param['between_month']['start'], $param['between_month']['end']);
        }

        $select->order('pr.month ASC');

        $select->columns(array(
            'id',
            'employee_details',
            'empl_payroll',
            'year',
            'month',
            'working_days',
            'leave_without_pay',
            'gross',
            'total_deduction',
            'bonus',
            'leave_encashment',
            'net_pay',
            'earning_dlwp',
            'deduction_dlwp',
            'organisation_id',
            'status',
            'author',
            'created',
            'hc_amount' => new Expression('?', array($HCSelect)),
            'tds_amount' => new Expression('?', array($TDSSelect)),
            'gis_amount' => new Expression('?', array($GISSelect)),
            'pf_amount' => new Expression('?', array($GPFSelect)),
            'bs_amount' => new Expression('?', array($BSSelect)),
        ));

        $selectString = $sql->getSqlStringForSqlObject($select);

        $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();

        return $results;
    }

    public function getSupplierTdsRecordsTableData($param) {

        $where = (is_array($param)) ? $param['where'] : array('a.id' => $param);

        $adapter = $this->dbAdapter;

        $sql = new Sql($adapter);

        $select = $sql->select();

        $select->from(array('a' => 'accounts_supplier_tds_records'))
            ->join(array('e' => 'supplier_details'), 'e.id = a.supplier_id', array(
                'supplier_name', 'supplier_code','supplier_tpn_no', 'supplier_license_no'
            ))
            ->join(array('o' => 'organisation'), 'o.id= e.organisation_id', array('organisation' => 'organisation_name', 'organisation_id' => 'id'))
            ->join(array('at' => 'accounts_transaction'), 'at.id = a.transaction_id', array('status'))
            ->where($where);

        if ( isset($param['between_month']) ) {
            $select->where->between('month', $param['between_month']['start'], $param['between_month']['end']);
        }

        $select->where('at.status != 1');

        $select->columns(array(
            'id',
            'transaction_id',
            'supplier_id',
            'transaction_amount',
            'tds_amount',
            'year',
            'month',
            'created_at',
            'updated_at',
            /*'supplier_name',
            'supplier_code',
            'organisation_id',
            'organisation_name'*/
        ));

        $select->order('a.month ASC');

        $selectString = $sql->getSqlStringForSqlObject($select);

        $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();

        return $results;
    }
}

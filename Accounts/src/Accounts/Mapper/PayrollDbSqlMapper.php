<?php

namespace Accounts\Mapper;

use Accounts\Model\StudentFeeCategory;
use Accounts\Model\StudentFeeStructure;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Db\Sql\Expression;

class PayrollDbSqlMapper implements PayrollMapperInterface {
    /**
     * @var \Zend\Db\Adapter\AdapterInterface
     *
     */

    protected $dbAdapter;

    /*
     * @var \Zend\Stdlib\Hydrator\HydratorInterface
    */
    protected $hydrator;

    /*
     * @var \Accounts\Model\Interface
    */
    protected $prototype;

    /**
     * @param AdapterInterface $dbAdapter
     */

    public function __construct(AdapterInterface $dbAdapter, HydratorInterface $hydrator, StudentFeeStructure $prototype) {
        $this->dbAdapter = $dbAdapter;
        $this->hydrator = $hydrator;
        $this->prototype = $prototype;
    }

    public function listAll($tableName) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        if ( $tableName === 'organisation' ) {
            $select->from(array('t1' => $tableName));
            $select->columns(array('id', 'organisation_name'));
        } else if ( $tableName === 'student_fee_category' ) {
            $select->from(array('t1' => $tableName));
            $select->join(["o" => "organisation"], "o.id = t1.organisation_id", ["organisation_name" => 'organisation_name']);
        } else if ( $tableName === 'student_fee_structure' ) {
            $select->from(array('t1' => $tableName));
            $select->join(["o" => "organisation"], "o.id = t1.organisation_id", ["organisation_name" => 'organisation_name']);
            $select->join(["p" => "programmes"], "p.id = t1.programmes_id", ["programme_name" => 'programme_name']);
            $select->join(["stc" => "student_fee_category"], "stc.id = t1.student_fee_category_id", ["student_fee_category_name" => 'fee_category']);
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

    public function getEmployeeList($tableName, $params = []) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('ed' => $tableName))
            ->join(["et" => "employee_type"], "et.id = ed.emp_type", ['role' => 'employee_type'])
            ->join(['d' => 'departments'], 'd.id = ed.departments_id', ['department_name']);

        if ( !empty($params['employee_id']) ) {
            $select->where(array('ed.emp_id = ?' => $params['employee_id']));
        }
        if ( !empty($params['employee_name']) ) {
            $select->where(array('ed.first_name = ?' => $params['employee_name']));
        }
        if ( !empty($params['organisation_id']) ) {
            $select->where(array('ed.organisation_id = ?' => $params['organisation_id']));
        }

        $select->columns(
            [
                'id' => 'id',
                'emp_id' => 'emp_id',
                'employee_full_name' => new Expression('CONCAT(ed.first_name," ",ed.last_name)'),
            ]
        );

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result)->toArray();
    }

    public function getEmpPersonalDetail($id) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('ed' => 'employee_details'))
            ->join(["et" => "employee_type"], "et.id = ed.emp_type", ['role' => 'employee_type'])
            ->join(['d' => 'departments'], 'd.id = ed.departments_id', ['department_name'])
            ->join(["o" => "organisation"], "o.id = ed.organisation_id", ['organisation_name', 'organisation_code'])
            ->where(array('ed.id' => $id));
        $select->columns(
            [
                'id' => 'id',
                'emp_id' => 'emp_id',
                'employee_full_name' => new Expression('CONCAT(ed.first_name," ",ed.last_name)'),
                'phone_no' => new Expression('phone_no'),
                'email' => new Expression('email'),
            ]
        );

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return current($resultSet->initialize($result)->toArray());
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

    public function getEmpPayrollDetail($id, $params = null) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('pp' => 'payr_payroll'))
            ->where(array('pp.employee_details =' . $id));

        if ( isset($params['status']) && $params['status'] != "" ) {
            $select->where(array('pp.status = ?' => $params['status']));
        }
        if ( isset($params['financial_year']) && $params['financial_year'] != "" ) {
            $year = explode('-', $params['financial_year']);
            $select->where("DATE(CONCAT(`year`, '-', `month`,'-01')) BETWEEN '" . $year['0'] . "-04-01' AND '" . $year['1'] . "-03-31'");
        }

        $select->columns(
            [
                'id' => new Expression('id'),
                'year' => new Expression('year'),
                'month' => new Expression('month'),
                'working_days' => new Expression('working_days'),
                'gross' => new Expression('gross'),
                'total_deduction' => new Expression('total_deduction'),
                'net_pay' => new Expression('net_pay'),
                'status' => new Expression('status'),
                'created' => new Expression('created'),
            ]
        );

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result)->toArray();
    }

    public function getEmpNetPayable($params) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('pp' => 'payr_payroll'))
            ->join(["ed" => "employee_details"], "ed.id = pp.employee_details", ['full_name' => new Expression('CONCAT(first_name," ",last_name)')]);

        if ( isset($params['organisation_id']) && $params['organisation_id'] != "" ) {
            $select->where(array('pp.organisation_id = ?' => $params['organisation_id']));
        }

        if ( isset($params['year']) && $params['year'] != "" ) {
            $select->where(array('pp.year = ?' => $params['year']));
        }

        if ( isset($params['month']) && $params['month'] != "" ) {
            $select->where(array('pp.month = ?' => $params['month']));
        }

        $select->columns(
            [
                'net_pay' => new Expression('net_pay'),
                'status' => new Expression('status'),
            ]
        );

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result)->toArray();
    }

    public function getPayrollTableData($param) 
    { 
        $where = (is_array($param)) ? $param : array('pr.id' => $param);
        $adapter = $this->dbAdapter;
        $sql = new Sql($adapter);
        $select = $sql->select();
        $select->from(array('pr' => 'payr_payroll'))
            ->join(array('emp' => 'employee_details'), 'pr.employee_details = emp.id', array('employee_details' => 'id', 'first_name', 'middle_name', 'last_name', 'emp_id'))
            ->join(array('empljp' => 'job_profile'), 'empljp.id=pr.empl_payroll', array())
            ->join(array('t' => 'employee_type'), 'empljp.emp_type_id = t.id', array('emp_type_id' => 'id', 'employee_type'))
            ->join(array('o' => 'organisation'), 'o.id= empljp.organisation_id', array('organisation' => 'organisation_name', 'organisation_id' => 'id'))
            ->where($where);

        $selectString = $sql->getSqlStringForSqlObject($select);
        //echo $selectString; exit;
        $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
        return $results;
    }

    public function getPayrollTableDataColumn($param, $column) {
        $where = (is_array($param)) ? $param : array('id' => $param);
        $fetch = array($column);
        $adapter = $this->dbAdapter;
        $sql = new Sql($adapter);
        $select = $sql->select();
        $select->from('payr_payroll');
        $select->columns($fetch);
        $select->where($where);

        $selectString = $sql->getSqlStringForSqlObject($select);
        //echo $selectString; exit;
        $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();

        foreach ( $results as $result ):
            $columns = $result[$column];
        endforeach;

        return $columns;
    }

    public function getPayrollStrucutreData($param, $zeroAmt = true) 
    { 
        $where = (is_array($param)) ? $param : array('sd.id' => $param);
        $adapter = $this->dbAdapter;
        $sql = new Sql($adapter);
        $select = $sql->select();
        $select->from(array('sd' => 'payr_pay_structure'))
            ->join(array('ph' => 'payr_pay_heads'), 'ph.id = sd.pay_head', array('pay_head', 'code', 'pay_head_id' => 'id', 'against', 'roundup', 'type', 'deduction'))
            ->where($where);
        if ( !$zeroAmt ):
            $select->where->greaterThan('sd.amount', '0');
        endif;

        $selectString = $sql->getSqlStringForSqlObject($select);
        //echo $selectString; exit;
        $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
        return $results;
    }


    public function getEmployeeDetailsData($param) {
        $where = (is_array($param)) ? $param : array('ed.id' => $param);
        $adapter = $this->dbAdapter;
        $sql = new Sql($adapter);
        $select = $sql->select();
        $select->from(array('ed' => 'employee_details'))->where($where);

        $selectString = $sql->getSqlStringForSqlObject($select);
        //echo $selectString; exit;
        $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
        return $results;
    }

    public function getAllEmployeeDetailsData() {
        $adapter = $this->dbAdapter;
        $sql = new Sql($adapter);
        $select = $sql->select();
        $select->from('employee_details');

        $selectString = $sql->getSqlStringForSqlObject($select);
        $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
        return $results;
    }

    public function getJobProfileData($param) { 
        $where = (is_array($param)) ? $param : array('jp.id' => $param);
        $adapter = $this->dbAdapter;
        $sql = new Sql($adapter);
        $select = $sql->select();
        $select->from(array('jp' => 'job_profile'))->where($where);

        $selectString = $sql->getSqlStringForSqlObject($select);
        //echo $selectString; exit;
        $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
        return $results;
    }

    public function getPositionTitleDataColumn($param, $column) {
        $where = (is_array($param)) ? $param : array('id' => $param);
        $fetch = array($column);
        $adapter = $this->dbAdapter;
        $sql = new Sql($adapter);
        $select = $sql->select();
        $select->from('position_title');
        $select->columns($fetch);
        $select->where($where);

        $selectString = $sql->getSqlStringForSqlObject($select);
        //echo $selectString; exit;
        $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();

        foreach ( $results as $result ):
            $columns = $result[$column];
        endforeach;

        return $columns;
    }

    public function getDepartmentDataColumn($param, $column) {
        $where = (is_array($param)) ? $param : array('id' => $param);
        $fetch = array($column);
        $adapter = $this->dbAdapter;
        $sql = new Sql($adapter);
        $select = $sql->select();
        $select->from('departments');
        $select->columns($fetch);
        $select->where($where);

        $selectString = $sql->getSqlStringForSqlObject($select);
        //echo $selectString; exit;
        $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();

        foreach ( $results as $result ):
            $columns = $result[$column];
        endforeach;

        return $columns;
    }

    public function getPositionLevelDataColumn($param, $column) {
        $where = (is_array($param)) ? $param : array('id' => $param);
        $fetch = array($column);
        $adapter = $this->dbAdapter;
        $sql = new Sql($adapter);
        $select = $sql->select();
        $select->from('position_level');
        $select->columns($fetch);
        $select->where($where);

        $selectString = $sql->getSqlStringForSqlObject($select);
        //echo $selectString; exit;
        $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();

        foreach ( $results as $result ):
            $columns = $result[$column];
        endforeach;

        return $columns;
    }

    public function getOrganisationDataColumn($param, $column) {
        $where = (is_array($param)) ? $param : array('id' => $param);
        $fetch = array($column);
        $adapter = $this->dbAdapter;
        $sql = new Sql($adapter);
        $select = $sql->select();
        $select->from('organisation');
        $select->columns($fetch);
        $select->where($where);

        $selectString = $sql->getSqlStringForSqlObject($select);
        //echo $selectString; exit;
        $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();

        foreach ( $results as $result ):
            $columns = $result[$column];
        endforeach;

        return $columns;
    }

    public function savePayrollTableData($data) 
    {
        if ( !is_array($data) ) {
            $data = $data->toArray();
        }

        if( empty($data['empl_payroll']) ) {
            return 0;
        }

        $id = isset($data['id']) ? (int) $data['id'] : 0;

        if ( $id > 0 ) {
            $data['modified'] = date('Y-m-d H:i:s');
            $action = new Update('payr_payroll');
            $action->set($data);
            $action->where(array('id' => $id));
        } else {
            $data['created'] = $data['modified'] = date('Y-m-d H:i:s');
            $action = new Insert('payr_payroll');
            $action->values($data);
        }

        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        if($id > 0){
            return ($result instanceof ResultInterface) ? $result->getAffectedRows() : 0;
        }else{
            return ($result instanceof ResultInterface) ? $result->getGeneratedValue() : 0;
        }
    }

    public function checkPayRollExistingOrNot($data) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('pp' => 'payr_payroll'));
        $select->where(array('employee_details' => $data['employee_details']));
        $select->where(array('year' => $data['year']));
        $select->where(array('month' => $data['month']));
        $select->where(array('organisation_id' => $data['organisation_id']));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        if ( $resultSet->count() === 0 ) {
            return false;
        }

        return true;
    }

    public function deletePayrollData($id) {
        $action = new Delete('payr_payroll');
        $action->where(array('id' => intval($id)));

        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        return (bool) $result->getAffectedRows();
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

    public function getPayrollDetailsData($param) 
    { 
        $where = (is_array($param)) ? $param : array('pd.id' => $param);
        $adapter = $this->dbAdapter;
        $sql = new Sql($adapter);
        $select = $sql->select();
        $select->from(array('pd' => 'payr_pay_details'))
            ->join(array('ph' => 'payr_pay_heads'), 'ph.id=pd.pay_head')
            ->where($where);

        $selectString = $sql->getSqlStringForSqlObject($select);
        //echo $selectString; exit;
        $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
        return $results;
    }

    public function getEmpJobProfile($id) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('jp' => 'job_profile'))
            ->where(array('jp.employee_details =' . $id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return current($resultSet->initialize($result)->toArray());
    }

    public function getEmpPayHeads($table_name, $emp_id, $deduction = null) {
        $sql = new Sql($this->dbAdapter);

        $notin = $sql->select();
        $notin->from($table_name);
        $notin->columns(array('pay_head'))
            ->where(array('employee_details = ?' => $emp_id));

        $select = $sql->select();
        $select->from(array('ph' => 'payr_pay_heads'))
            ->where->notIn('ph.id', $notin);

        if ( isset($deduction) ) {
            $select->where->equalTo('ph.deduction', $deduction);
        }

        $select->columns(
            [
                'id' => 'id',
                'pay_head' => 'pay_head',
                'type' => 'type',
            ]
        );

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result)->toArray();
    }

    public function getPayHeadbyId($id) {
        $sql = new Sql($this->dbAdapter);

        $select = $sql->select();

        $select->from(array('ph' => 'payr_pay_heads'))
            ->where(array('ph.id' => $id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return current($resultSet->initialize($result)->toArray());
    }

    public function getPayScaleDetail($id) {
        $sql = new Sql($this->dbAdapter);

        $select = $sql->select();

        $select->from(array('ps' => 'pay_scale'))
            ->where(array('ps.position_level' => $id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return current($resultSet->initialize($result)->toArray());
    }

    public function getEmpBaseAmount($params, $columnname, $table_name) 
    { 
        $sql = new Sql($this->dbAdapter);

        $select = $sql->select();
        $select->from($table_name)
            ->columns(array($columnname))
            ->where($params);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return current($resultSet->initialize($result)->toArray());
    }


    public function getPayGroup($id) {
        $sql = new Sql($this->dbAdapter);

        $select = $sql->select();
        $select->from(array('pg' => 'payr_pay_group'))
            ->where(array('pg.pay_head' => $id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result)->toArray();
    }

    public function getPaySlabList($id) {
        $sql = new Sql($this->dbAdapter);

        $select = $sql->select();
        $select->from(array('pps' => 'payr_pay_slab'))
            ->where(array('pps.pay_head' => $id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result)->toArray();
    }

    public function savePayStructure($data, $table_name) 
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

    public function getPayStructureDetail($params) {
        $sql = new Sql($this->dbAdapter);

        $where = (is_array($params)) ? $params : array('pps.id' => $params);
        $select = $sql->select();
        $select->from(array('pps' => 'payr_pay_structure'))
            ->join(array('ph' => 'payr_pay_heads'), 'ph.id = pps.pay_head', array('pay_head', 'code', 'pay_head_id' => 'id', 'against', 'roundup', 'type', 'deduction'))
            ->where($where);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return current($resultSet->initialize($result)->toArray());
    }

    public function getTempPayrollData($params) {
        $sql = new Sql($this->dbAdapter);

        $where = (is_array($params)) ? $params : array('pr.id' => $params);
        $select = $sql->select();
        $select->from(array('pr' => 'payr_temp_payroll'))
            ->join(array('emp' => 'employee_details'), 'pr.employee_details = emp.id', array('employee_details' => 'id', 'first_name', 'middle_name', 'last_name', 'emp_id'))
            ->join(array('empljp' => 'job_profile'), 'empljp.id=pr.empl_payroll', array())
            ->join(array('t' => 'employee_type'), 'empljp.emp_type_id = t.id', array('emp_type_id' => 'id', 'employee_type'))
            ->join(array('o' => 'organisation'), 'o.id= empljp.organisation_id', array('organisation' => 'organisation_name', 'organisation_id' => 'id'))
            ->where($where);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result)->toArray();
    }

    public function updatePayrollStatus(array $param) {
        $sql = new Sql($this->dbAdapter);

        $updateQuery = new Update('payr_payroll');
        $updateQuery->set(array('status' => 1));
        $updateQuery->where($param);

        $stmt = $sql->prepareStatementForSqlObject($updateQuery);
        $result = $stmt->execute();

        return $result->count();
    }

    public function deletePayhead($id) {
        $delete = new Delete('payr_pay_structure');
        $delete->where(array('id' => intval($id)));

        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($delete);
        $result = $stmt->execute();

        return (bool) $result->getAffectedRows();
    }

    public function savePayrollDetail($data) {
        $sql = new Sql($this->dbAdapter);

        $deleteAction = new Delete('payr_pay_details');
        $deleteAction->where(array('pay_roll = ?' => $data['payroll_id']));
        $deleteResponse = $sql->prepareStatementForSqlObject($deleteAction)->execute();

        $action = new Insert('payr_pay_details');

        $insertedData = [];

        foreach ( $data['data'] as $filed ) {
            $array['pay_head'] = $filed['pay_head_id'];
            $array['dlwp'] = $filed['dlwp'];
            $array['percent'] = $filed['percent'];
            $array['amount'] = $filed['amount'];
            $array['actual_amount'] = $filed['amount'];
            $array['ref_no'] = $filed['ref_no'];
            $array['remarks'] = $filed['remarks'];

            $array['pay_roll'] = $data['payroll_id'];
            $array['organisation_id'] = $data['organisation_id'];
            $array['author'] = $data['author'];

            $array['created'] = date('Y-m-d H:i:s');
            $array['modified'] = date('Y-m-d H:i:s');

            $action->values($array);
            $stmt = $sql->prepareStatementForSqlObject($action);
            $result = $stmt->execute();

            $insertedData[] = $result->getGeneratedValue();
        }

        return count($insertedData);
    }
}

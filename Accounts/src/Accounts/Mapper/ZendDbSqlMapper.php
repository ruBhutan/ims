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

class ZendDbSqlMapper implements FeeStructureMapperInterface {
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

    public function getLoginEmpDetailfrmUsername($username)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('ed' => 'employee_details'))
            ->where(array('ed.emp_id' => $username));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return current($resultSet->initialize($result)->toArray());
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

    public function save(StudentFeeStructure $moduleObject, $level) {
        $this->insertOrUpdate($moduleObject, 'student_fee_structure');
    }

    public function checkUniqueFeeStructure($tableName, $fields, $type) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        if ( $tableName === 'student_fee_category' ) {
            $select->from(array('t1' => $tableName));
            $select->where(array('organisation_id' => $fields['organisation_id']));
            $select->where(array('fee_category' => $fields['fee_category']));
        } else {
            $select->from(array('t1' => $tableName));
            $select->where(array('student_fee_category_id' => $fields['student_fee_category_id']));
            $select->where(array('programmes_id' => $fields['programmes_id']));
            $select->where(array('organisation_id' => $fields['organisation_id']));
            $select->where(array('financial_year' => $fields['financial_year']));
        }

        if ( $type === 'updated' && !empty($fields['id']) ) {
            $select->where(array('id != ?' => $fields['id']));
        }

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        if ( $resultSet->count() === 0 ) {
            return false;
        }
        return true;
    }

    public function saveCategory(StudentFeeCategory $moduleObject, $level) {
        $this->insertOrUpdate($moduleObject, 'student_fee_category');
    }

    protected function insertOrUpdate($moduleObject, $tableName) {
        $moduleData = $this->hydrator->extract($moduleObject);

        unset($moduleData['id']);

        if ( $moduleObject->getId() ) {
            //ID present, so it is an update
            $action = new Update($tableName);
            $action->set($moduleData);
            $action->where(array('id = ?' => $moduleObject->getId()));
        } else {
            $action = new Insert($tableName);
            $action->values($moduleData);
        }

        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        if ( $result instanceof ResultInterface ) {
            if ( $newId = $result->getGeneratedValue() ) {
                echo $moduleObject->setId($newId);
            }
            return $moduleObject;
        }

        throw new \Exception("Database Error");
    }

    public function getStudentFeeReportList($tableName, $params = []) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('sfpd' => $tableName))
//            ->join(['sfd' => 'student_fee_details'], 'sfd.id = sfpd.student_fees_details_id', ['financial_year', 'status'])
            ->join(['sfd' => 'student_fee_details'], 'sfd.id = sfpd.student_fees_details_id', ['financial_year'])
            ->join(["stud" => "student"], "stud.id = sfd.student_id", ['student_first_name' => 'first_name', 'student_last_name' => 'last_name'])
            ->join(['sfc' => 'student_fee_category'], 'sfd.student_fee_category_id = sfc.id', ['fee_category']);

        if ( !empty($params->fee_category) ) {
            $select->where(array('sfd.student_fee_category_id = ?' => $params->fee_category));
        }

        if ( !empty($params->payment_status) ) {
            $select->where(array('sfpd.status = ?' => $params->payment_status));
        }

        if ( !empty($params->financial_year) ) {
            $select->where(array('sfd.financial_year = ?' => $params->financial_year));
        }

        if ( !empty($params->organisation_id) ) {
            $select->where(array('stud.organisation_id = ?' => $params->organisation_id));
        }

        $select->columns(
            [
                'total_fee' => new Expression('sfd.amount'),
                'fee_received' => new Expression('sfpd.amount'),
                'status' => 'status',
                'paid_date' => 'created_at',
                'student_full_name' => new Expression('CONCAT(stud.first_name," ",stud.last_name)')
            ]
        );

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result)->toArray();
    }

    public function getTotalReceivableAndPaidFeesCount($tableName,$params) {        
        if(!empty((array)$params)){
            $sql = new Sql($this->dbAdapter);
            
            /* receivable amount query */
            $receive = $sql->select();
            $receive->from(array('sfd' => $tableName));
            $receive->columns([
                'total_receivable_fees' => new Expression('sum(sfd.amount)'),
            ]);
            if ( !empty($params->organisation_id) ) {
                $receive->where(array('sfd.organisation_id' => $params->organisation_id));
            }
            if ( !empty($params->fee_category) ) {
                $receive->where(array('sfd.student_fee_category_id ' => $params->fee_category));
            }
            if ( !empty($params->financial_year) ) {
                $receive->where(array('sfd.financial_year' => $params->financial_year));
            }
            
            $stmt = $sql->prepareStatementForSqlObject($receive);
            $receive_result = $stmt->execute();
            
            $resultSet = new ResultSet();
            $receivable_amt = current($resultSet->initialize($receive_result)->toArray());
            /* receivable amount query */
            
            /* received amount query */
            $received = $sql->select();
            $received->from(array('sfpd' => 'student_fee_payment_details'))
            ->join(['sfd' => 'student_fee_details'], 'sfd.id = sfpd.student_fees_details_id');
            $received->columns([
                'total_received_fees' => new Expression('SUM(sfpd.amount)'),
            ]);
            if ( !empty($params->organisation_id) ) {
                $received->where(array('sfd.organisation_id' => $params->organisation_id));
            }
            if ( !empty($params->fee_category) ) {
                $received->where(array('sfd.student_fee_category_id' => $params->fee_category));
            }
            if ( !empty($params->financial_year) ) {
                $received->where(array('sfd.financial_year' => $params->financial_year));
            }
            $received->where(array('sfpd.status' => 'Completed'));

            $stmt1 = $sql->prepareStatementForSqlObject($received);
            $received_result = $stmt1->execute();

            $resultSet1 = new ResultSet();
            $received_amt = current($resultSet1->initialize($received_result)->toArray());
            /* received amount query */
            
            $total_amt = array_merge($received_amt,$receivable_amt);

            return $total_amt;
        }
    }
}
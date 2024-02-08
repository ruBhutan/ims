<?php

namespace StudentFeeDetails\Mapper;

use StudentFeeDetails\Model\StudentFeeDetails;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements StudentFeeDetailsMapperInterface {
    protected $dbAdapter;

    protected $hydrator;

    protected $prototype;

    public function __construct(AdapterInterface $dbAdapter, HydratorInterface $hydrator, StudentFeeDetails $prototype) {
        $this->dbAdapter = $dbAdapter;
        $this->hydrator = $hydrator;
        $this->prototype = $prototype;
    }

    public function getStudentDetailsByID($table_name, $user_name) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'student'))
            ->join(array('t2' => 'programmes'), 't2.id = t1.programmes_id', array('programme_name'))
            ->join(array('t3' => 'organisation'), 't3.id = t1.organisation_id', array('organisation_name'))
            ->join(array('t4' => 'student_type'), 't4.id = t1.scholarship_type', array('student_type'))
            ->join(array('t5' => 'gender'), 't5.id = t1.gender', array('student_gender' => 'gender'))
            ->where(array('t1.student_id =' . $user_name));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ( $result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows() ) {
            return $this->hydrator->hydrate($result->current(), $this->prototype);
        }

        throw new \InvalidArgumentException("Student Type with given ID: ($user_name) not found");
    }

    public function listStudentFeeList($student_id) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'student_fee_details'))
            ->join(array('t2' => 'student_fee_category'), 't1.student_fee_category_id = t2.id', array('fee_category'))
            ->where(array('t1.student_id' => intval($student_id)))->order(['id' => 'DESC']);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }
}

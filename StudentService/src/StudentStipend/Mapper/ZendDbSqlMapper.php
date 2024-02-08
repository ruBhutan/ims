<?php

namespace StudentStipend\Mapper;

use StudentStipend\Model\StudentStipend;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Group;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Update;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements StudentStipendMapperInterface {
    protected $dbAdapter;

    protected $hydrator;

    protected $studentStipendPrototype;

    public function __construct(AdapterInterface $dbAdapter, HydratorInterface $hydrator, StudentStipend $studentStipendPrototype) {
        $this->dbAdapter = $dbAdapter;

        $this->hydrator = $hydrator;

        $this->studentStipendPrototype = $studentStipendPrototype;
    }

    public function getUserDetailsId($tableName, $username) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        if ( $tableName == 'employee_details' ) {
            $select->from(array('t1' => $tableName));
            $select->where(array('emp_id' => $username));
            $select->columns(array('id'));
        } else if ( $tableName == 'student' ) {
            $select->from(array('t1' => $tableName));
            $select->where(array('student_id' => $username));
            $select->columns(array('id'));
        }

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }

    public function getOrganisationId($tableName, $username) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        if ( $tableName == 'employee_details' ) {
            $select->from(array('t1' => $tableName));
            $select->where(array('emp_id' => $username));
            $select->columns(array('organisation_id'));
        } else if ( $tableName == 'student' ) {
            $select->from(array('t1' => $tableName));
            $select->where(array('student_id' => $username));
            $select->columns(array('organisation_id'));
        }

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }

    public function getUserDetails($username, $usertype) {
        $name = NULL;

        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        if ( $usertype == 1 ) {
            $select->from(array('t1' => 'employee_details'));
            $select->where(array('t1.emp_id' => $username));
            $select->columns(array('first_name', 'middle_name', 'last_name'));
        } else if ( $usertype == 2 ) {
            $select->from(array('t1' => 'student'));
            $select->where(array('student_id' => $username));
            $select->columns(array('first_name', 'middle_name', 'last_name'));
        }

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        foreach ( $resultSet as $set ) {
            $name = $set['first_name'] . " " . $set['middle_name'] . " " . $set['last_name'];
        }

        return $name;
    }

    public function getUserImage($username, $usertype) {
        $img_location = NULL;

        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        if ( $usertype == 1 ) {
            $select->from(array('t1' => 'employee_details'));
            $select->where(array('t1.emp_id' => $username));
            $select->columns(array('profile_picture', 'first_name', 'middle_name', 'last_name'));
        }

        if ( $usertype == 2 ) {
            $select->from(array('t1' => 'student'));
            $select->where(array('t1.student_id' => $username));
            $select->columns(array('profile_picture', 'first_name', 'middle_name', 'last_name'));
        }

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        foreach ( $resultSet as $set ) {
            $img_location = $set['profile_picture'];
        }

        return $img_location;
    }

    public function getStudentListsToAdmin($stdName, $stdId, $organisation_id = null) {
        $governmentScholarshipTypeId = '1'; // Government Scholarship Student Type ID
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'student'))
            ->join(array('t2' => 'programmes'),
                't2.id = t1.programmes_id', array('programme_name'))
            ->join(array('t3' => 'student_type'),
                't3.id = t1.scholarship_type', array('student_type'))
            ->join(array('t4' => 'gender'),
                't4.id = t1.gender', array('stdGender' => 'gender'))
            ->join(array('t5' => 'dzongkhag'),
                't5.id = t1.dzongkhag', array('dzongkhag_name'))
            ->join(array('t6' => 'gewog'),
                't6.id = t1.gewog', array('gewog_name'))
            ->join(array('t7' => 'village'),
                't7.id = t1.village', array('village_name'))
            ->where(array('t1.student_status_type_id' => '1', 't1.scholarship_type = ?' => $governmentScholarshipTypeId));

        if ( $stdName ) {
            $select->where->like('t1.first_name', '%' . $stdName . '%');
        }
        if ( $stdId ) {
            $select->where(array('t1.student_id' => $stdId));
        }
        if ( $organisation_id ) {
            $select->where(array('t1.organisation_id' => $organisation_id));
        }
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }

    public function getStudentLists($stdName, $stdId, $organisation_id) {
        $governmentScholarshipTypeId = '1'; // Government Scholarship Student Type ID
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'student'))
            ->join(array('t2' => 'programmes'),
                't2.id = t1.programmes_id', array('programme_name'))
            ->join(array('t3' => 'student_type'),
                't3.id = t1.scholarship_type', array('student_type'))
            ->join(array('t4' => 'gender'),
                't4.id = t1.gender', array('stdGender' => 'gender'))
            ->join(array('t5' => 'dzongkhag'),
                't5.id = t1.dzongkhag', array('dzongkhag_name'))
            ->join(array('t6' => 'gewog'),
                't6.id = t1.gewog', array('gewog_name'))
            ->join(array('t7' => 'village'),
                't7.id = t1.village', array('village_name'))
            ->where(array('t1.organisation_id = ?' => $organisation_id, 't1.student_status_type_id' => '1', 't1.scholarship_type = ?' => $governmentScholarshipTypeId));

        if ( $stdName ) {
            $select->where->like('t1.first_name', '%' . $stdName . '%');
        }
        if ( $stdId ) {
            $select->where(array('t1.student_id' => $stdId));
        }

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }

    public function getStdPersonalDetails($id) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'student'))
            ->join(array('t2' => 'programmes'),
                't2.id = t1.programmes_id', array('programme_name'))
            ->join(array('t3' => 'organisation'), // join table with alias
                't3.id = t1.organisation_id', array('organisation_name'))
            ->join(array('t4' => 'student_type'), // join table with alias
                't4.id = t1.scholarship_type', array('student_type'))
            ->join(array('t5' => 'gender'),
                't5.id = t1.gender', array('student_gender' => 'gender'))
            ->where(array('t1.id =' . $id));  //join expression; //

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }

    public function listStudentStipendList($student_id = null, $id = null) {
        $sql = new Sql($this->dbAdapter);

        $select = $sql->select();

        $whereCondition = [];

        if ( $student_id !== null ) {
            $whereCondition['t1.student_id'] = intval($student_id);
        }

        if ( $id !== null ) {
            $whereCondition['t1.id'] = intval($id);
        }

        $select->from(array('t1' => 'student_stipend'))->order(['id' => 'DESC']);

        if ( !empty($whereCondition) ) {
            $select->where($whereCondition);
        }

        $select->columns(array('*'));

        $stmt = $sql->prepareStatementForSqlObject($select);

        $result = $stmt->execute();

        $resultSet = new ResultSet();

        return $resultSet->initialize($result);
    }

    public function fetchFeeCategories($organisation_id) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'student_fee_category'))->columns(array('id', 'organisation_id', 'fee_category'));
        $select->where(array('t1.organisation_id' => intval($organisation_id)));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        $allFeeCategories = [];

        foreach ( $resultSet as $set ) {
            $allFeeCategories[$set['id']] = $set['fee_category'];
        }

        return $allFeeCategories;
    }

    public function isStudentStipendsPaid($data) {
        $sql = new Sql($this->dbAdapter);

        $select = $sql->select();

        $select->from(array('t1' => 'student_stipend'));

        $select->where([
            't1.student_id' => $data['student_id'],
            't1.year' => $data['year'],
            't1.month' => $data['month']
        ]);

        $select->limit(1);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        $studentStipendPaid = [];

        foreach ( $resultSet as $row ) {
            if ( isset($data['id']) && !empty($data['id']) && $data['id'] == $row['id'] ) {
                return false;
            }

            $studentStipendPaid[] = $row;
        }

        return (count($studentStipendPaid) > 0) ? true : false;
    }

    public function saveStudentStipendDetails(StudentStipend $studentStipendDetails) {
        $sql = new Sql($this->dbAdapter);

        $studentStipendDetailsData = $this->hydrator->extract($studentStipendDetails);

        unset($studentStipendDetailsData['id']);

        if ( $studentStipendDetails->getId() ) {
            $action = new Update('student_stipend');
            $action->set($studentStipendDetailsData);
            $action->where(array('id = ?' => $studentStipendDetails->getId()));
        } else {
            $action = new Insert('student_stipend');
            $action->values($studentStipendDetailsData);
        }

        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        if ( $result instanceof ResultInterface ) {
            if ( $newId = $result->getGeneratedValue() ) {
                $studentStipendDetails->setId($newId);
            }

            return $studentStipendDetails;
        }

        throw new \Exception("Database Error");
    }

    public function deleteStudentStipend($id) {
        $sql = new Sql($this->dbAdapter);

        $deleteAction = new Delete('student_stipend');

        $deleteAction->where(array('id = ?' => $id));

        $result = $sql->prepareStatementForSqlObject($deleteAction)->execute();

        return (bool) $result->getAffectedRows();
    }

    public function generateBulkStudentStipend($data) {
        $governmentScholarshipTypeId = '1'; // Government Scholarship Student Type ID
        $studentIds = [];

        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'student'))->columns(array('id'))->where(
            array(
                't1.organisation_id' => $data['organisation_id'],
                't1.student_status_type_id' => '1',
                't1.scholarship_type = ?' => $governmentScholarshipTypeId
            )
        );
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        $resultSet = new ResultSet();
        $studentData = $resultSet->initialize($result)->toArray();

        foreach ( $studentData as $studentId ) {
            $studentIds[] = $studentId['id'];
        }

        $studenStipendData = [
            'year' => $data['year'],
            'month' => $data['month'],
            'stipend' => $data['stipend'],
            'h_r' => $data['h_r'],
            'ebill' => $data['ebill'],
            'net_amount' => $data['net_amount'],
        ];

        $insertedData = [];

        $action = new Insert('student_stipend');

        foreach ( $studentIds as $studentId ) {
            $studenStipendData = array_merge($studenStipendData, [
                'student_id' => $studentId,
            ]);

            $isExists = $this->isStudentStipendsPaid($studenStipendData);

            if ( !$isExists ) {
                $action->values($studenStipendData);
                $stmt = $sql->prepareStatementForSqlObject($action);
                $result = $stmt->execute();

                $insertedData[] = $result->getGeneratedValue();
            }
        }

        return (count($studentIds) === count($insertedData)) ? true : count($insertedData);
    }

    public function getStudentStipendListByFilter($data) {
        $governmentScholarshipTypeId = '1'; // Government Scholarship Student Type ID

        if ( empty($data) ) {
            return [];
        }

        $studentIds = [];
        $studentNameWithID = [];

        $sql = new Sql($this->dbAdapter);

        $getStudentList = $sql->select();
        $getStudentList->from(array('t1' => 'student'));
        $getStudentList->where(array('t1.organisation_id' => $data['organisation_id']));

        $getStudentList->where(
            array(
                't1.organisation_id' => $data['organisation_id'],
                't1.student_status_type_id' => '1',
                't1.scholarship_type = ?' => $governmentScholarshipTypeId
            )
        );

        $statement = $sql->prepareStatementForSqlObject($getStudentList);
        $studentResult = $statement->execute();
        $studentResultSet = new ResultSet();
        $studentData = $studentResultSet->initialize($studentResult)->toArray();

        foreach ( $studentData as $studentId ) {
            $studentIds[] = $studentId['id'];

            $studentNameWithID[$studentId['id']] = $studentId['first_name'] . " " . $studentId['middle_name'] . " " . $studentId['last_name'] . "";
        }

        $getStudentStipendList = $sql->select();
        $getStudentStipendList->from(array('t1' => 'student_stipend'));
        $getStudentStipendList->where(array(
            't1.year' => $data['year'],
            't1.month' => $data['month'],
        ));
        $getStudentStipendList->where(array("t1.student_id" => $studentIds));
        $statementStipend = $sql->prepareStatementForSqlObject($getStudentStipendList);
        $studentStipendResult = $statementStipend->execute();
        $studentStipendResultSet = new ResultSet();
        $studentStipendData = $studentStipendResultSet->initialize($studentStipendResult)->toArray();

        $arrayList = [];
        if ( !empty($studentStipendData) ) {
            $arrayList = array_map(function($data, $k) use ($studentNameWithID) {
                unset($data["id"]);
                unset($data["created"]);
                unset($data["updated"]);

                $data['student_name'] = $studentNameWithID[$data['student_id']];
                $data['id'] = $k + 1;

                return $data;

            }, $studentStipendData, array_keys($studentStipendData));
        }

        return $arrayList;
    }
}

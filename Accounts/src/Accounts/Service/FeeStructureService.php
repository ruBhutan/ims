<?php

namespace Accounts\Service;

use Accounts\Model\StudentFeeCategory;
use Accounts\Model\StudentFeeStructure;
use Accounts\Mapper\FeeStructureMapperInterface;

class FeeStructureService implements FeeStructureServiceInterface {

    protected $mapper;

    public function __construct(FeeStructureMapperInterface $mapper) {
        $this->mapper = $mapper;
    }
    
    public function getLoginEmpDetailfrmUsername($username)
    {
        return $this->mapper->getLoginEmpDetailfrmUsername($username);
    }

    public function listAll($tableName) {
        return $this->mapper->listAll($tableName);
    }

    public function findDetails($tableName, $id) {
        return $this->mapper->findDetails($tableName, $id);
    }

    public function save(StudentFeeStructure $moduleObject, $level) {
        return $this->mapper->save($moduleObject, $level);
    }

    public function checkUniqueFeeStructure($tableName, $fields, $type) {
        return $this->mapper->checkUniqueFeeStructure($tableName, $fields, $type);
    }

    public function saveCategory(StudentFeeCategory $moduleObject, $level) {
        return $this->mapper->saveCategory($moduleObject, $level);
    }

    public function getStudentFeeReportList($tableName, $params) {
        return $this->mapper->getStudentFeeReportList($tableName, $params);
    }

    public function getTotalReceivableAndPaidFeesCount($tableName, $params) {
        return $this->mapper->getTotalReceivableAndPaidFeesCount($tableName, $params);
    }
}

<?php
namespace Accounts\Service;

use Accounts\Mapper\PayrollMapperInterface;

class PayrollService implements PayrollServiceInterface {

    protected $mapper;

    public function __construct(PayrollMapperInterface $mapper) {
        $this->mapper = $mapper;
    }

    public function listAll($tableName) {
        return $this->mapper->listAll($tableName);
    }

    public function findDetails($tableName, $id) {
        return $this->mapper->findDetails($tableName, $id);
    }

    public function getLoginEmpDetailfrmUsername($username) {
        return $this->mapper->getLoginEmpDetailfrmUsername($username);
    }

    public function getEmployeeList($tableName, $params) {
        return $this->mapper->getEmployeeList($tableName, $params);
    }

    public function getEmpPersonalDetail($id) {
        return $this->mapper->getEmpPersonalDetail($id);
    }

    public function getEmpPayrollDetail($id,$params) {
        return $this->mapper->getEmpPayrollDetail($id,$params);
    }

    public function getEmpNetPayable($params) {
        return $this->mapper->getEmpNetPayable($params);
    }

    public function getEmpJobProfile($id) {
        return $this->mapper->getEmpJobProfile($id);
    }

    public function getEmpPayHeads($table_name,$emp_id,$deduction) {
        return $this->mapper->getEmpPayHeads($table_name,$emp_id,$deduction);
    }

    public function getPayHeadbyId($id) {
        return $this->mapper->getPayHeadbyId($id);
    }

    public function getPayScaleDetail($id) {
        return $this->mapper->getPayScaleDetail($id);
    }

    public function getEmpBaseAmount($params,$columnname,$table_name) {
        return $this->mapper->getEmpBaseAmount($params,$columnname,$table_name);
    }

    public function getPayGroup($id) {
        return $this->mapper->getPayGroup($id);
    }

    public function getPaySlabList($id) {
        return $this->mapper->getPaySlabList($id);
    }

    public function savePayStructure($data,$table_name) {
        return $this->mapper->savePayStructure($data,$table_name);
    }

    public function getPayStructureDetail($params) {
        return $this->mapper->getPayStructureDetail($params);
    }

    public function getTempPayrollData($params) {
        return $this->mapper->getTempPayrollData($params);
    }

    public function getPayrollTableData($param) {
        return $this->mapper->getPayrollTableData($param);
    }

    public function getPayrollTableDataColumn($param, $column) {
        return $this->mapper->getPayrollTableDataColumn($param, $column);
    }

    public function getPayrollStrucutreData($param, $zeroAmt = true) {
        return $this->mapper->getPayrollStrucutreData($param, $zeroAmt);
    }

    public function getEmployeeDetailsData($param) {
        return $this->mapper->getEmployeeDetailsData($param);
    }

    public function getAllEmployeeDetailsData() {
        return $this->mapper->getAllEmployeeDetailsData();
    }

    public function getJobProfileData($param) {
        return $this->mapper->getJobProfileData($param);
    }

    public function getPositionTitleDataColumn($param, $column) {
        return $this->mapper->getPositionTitleDataColumn($param, $column);
    }

    public function getDepartmentDataColumn($param, $column) {
        return $this->mapper->getDepartmentDataColumn($param, $column);
    }

    public function getPositionLevelDataColumn($param, $column) {
        return $this->mapper->getPositionLevelDataColumn($param, $column);
    }

    public function savePayrollTableData($data) {
        return $this->mapper->savePayrollTableData($data);
    }

    public function savePayrollDetail($data) {
        return $this->mapper->savePayrollDetail($data);
    }

    public function deletePayrollData($id) {
        return $this->mapper->deletePayrollData($id);
    }

    public function getOrganisationData($param = null) {
        return $this->mapper->getOrganisationData($param);
    }

    public function getOrganisationDataColumn($param, $column) {
        return $this->mapper->getOrganisationDataColumn($param, $column);
    }

    public function getPayrollDetailsData($param) {
        return $this->mapper->getPayrollDetailsData($param);
    }

    public function checkPayRollExistingOrNot($data) {
        return $this->mapper->checkPayRollExistingOrNot($data);
    }

    public function updatePayrollStatus(array $param) {
        return $this->mapper->updatePayrollStatus($param);
    }

    public function deletePayhead($id) {
        return $this->mapper->deletePayhead($id);
    }
}

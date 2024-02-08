<?php

namespace Accounts\Service;

use Accounts\Mapper\GenerateTdsReportMapperInterface;

class GenerateTdsReportService implements GenerateTdsReportServiceInterface {

    protected $mapper;

    public function __construct(GenerateTdsReportMapperInterface $mapper) {
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

    public function getTableData($tablename) {
        return $this->mapper->getTableData($tablename);
    }

    public function saveupdateData($tablename, $params) {
        return $this->mapper->saveupdateData($tablename, $params);
    }

    public function getDatabyParam($tablename, $params, $column) {
        return $this->mapper->getDatabyParam($tablename, $params, $column);
    }

    public function getDataByFilter($type, $tablename, $params, $column) {
        return $this->mapper->getDataByFilter($type, $tablename, $params, $column);
    }

    public function deleteTable($tableName, $id) {
        return $this->mapper->deleteTable($tableName, $id);
    }

    public function getEmployeeDetailsData($param) {
        return $this->mapper->getEmployeeDetailsData($param);
    }

    public function getOrganisationData($param = null) {
        return $this->mapper->getOrganisationData($param);
    }

    public function getPayrollTableData($param) {
        return $this->mapper->getPayrollTableData($param);
    }

    public function getSupplierTdsRecordsTableData($param) {
        return $this->mapper->getSupplierTdsRecordsTableData($param);
    }
}

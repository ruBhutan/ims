<?php

namespace Accounts\Mapper;

interface GenerateTdsReportMapperInterface {

    public function listAll($tableName);

    public function findDetails($tableName, $id);

    public function getLoginEmpDetailfrmUsername($username);

    public function getTableData($tablename);

    public function saveupdateData($tablename, $params);

    public function getDatabyParam($tablename, $params, $column);

    public function getDataByFilter($type, $tablename, $params, $column);

    public function deleteTable($tableName, $id);

    public function getEmployeeDetailsData($param);

    public function getOrganisationData($param);

    public function getPayrollTableData($param);

    public function getSupplierTdsRecordsTableData($param);
}

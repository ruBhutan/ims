<?php

namespace Accounts\Service;

interface ChequeServiceInterface {

    public function listAll($tableName);

    public function findDetails($tableName, $id);

    public function getLoginEmpDetailfrmUsername($username);

    public function getTableData($tablename);

    public function saveupdateData($tablename, $params);

    public function getDatabyParam($tablename, $params, $column);

    public function getDataByFilter($type, $tablename, $params, $column);

    public function deleteTable($tableName, $id);
}

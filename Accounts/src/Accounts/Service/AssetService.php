<?php

namespace Accounts\Service;

use Accounts\Mapper\AssetMapperInterface;

class AssetService implements AssetServiceInterface {

    protected $mapper;

    public function __construct(AssetMapperInterface $mapper) {
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
}

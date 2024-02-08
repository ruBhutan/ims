<?php
namespace Accounts\Service;

interface PayrollServiceInterface {

    public function listAll($tableName);

    public function findDetails($tableName, $id);

    public function getLoginEmpDetailfrmUsername($username);

    public function getEmployeeList($tableName,$params);

    public function getEmpPersonalDetail($id);

    public function getEmpPayrollDetail($id,$params);

    public function getEmpNetPayable($params);

    public function getPayrollTableData($param);

    public function getPayrollTableDataColumn($param, $column);

    public function getPayrollStrucutreData($param, $zeroAmt);

    public function getEmployeeDetailsData($param);

    public function getAllEmployeeDetailsData();

    public function getJobProfileData($param);

    public function getPositionTitleDataColumn($param, $column);

    public function getDepartmentDataColumn($param, $column);

    public function getPositionLevelDataColumn($param, $column);

    public function savePayrollTableData($data);

    public function savePayrollDetail($data);

    public function deletePayrollData($id);

    public function getOrganisationData($param);

    public function getOrganisationDataColumn($param, $column);

    public function getPayrollDetailsData($param);

    public function getEmpJobProfile($id);

    public function getEmpPayHeads($table_name,$emp_id,$deduction);

    public function getPayHeadbyId($id);

    public function getPayScaleDetail($id);

    public function getEmpBaseAmount($params,$columnname,$table_name);

    public function getPayGroup($id);

    public function getPaySlabList($id);

    public function savePayStructure($data,$table_name);

    public function getPayStructureDetail($params);

    public function getTempPayrollData($params);

    public function checkPayRollExistingOrNot($data);

    public function updatePayrollStatus(array $param);

    public function deletePayhead($id);

}

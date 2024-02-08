<?php

namespace Accounts\Service;

use Accounts\Mapper\MasterMapperInterface;

class MasterService implements MasterServiceInterface {

    protected $mapper;

    public function __construct(MasterMapperInterface $mapper) {
        $this->mapper = $mapper;
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

    public function getSubheadData($tablename, $params) {
        return $this->mapper->getSubheadData($tablename, $params);
    }

    public function getTransactionSubheadforLedger($table_name, $organisation, $start_date, $end_date, $where) {
        return $this->mapper->getTransactionSubheadforLedger($table_name, $organisation, $start_date, $end_date, $where);
    }

    public function getOpeningBalanceforSHLedger($table_name, $start_date, $organisation, $id) {
        return $this->mapper->getOpeningBalanceforSHLedger($table_name, $start_date, $organisation, $id);
    }

    public function getTransactionIDforLedger($table_name, $organisation, $start_date, $end_date, $where) {
        return $this->mapper->getTransactionIDforLedger($table_name, $organisation, $start_date, $end_date, $where);
    }

    public function getSumbyTransactionIDforLedger($table_name, $organisation, $start_date, $end_date, $column, $where) {
        return $this->mapper->getSumbyTransactionIDforLedger($table_name, $organisation, $start_date, $end_date, $column, $where);
    }

    public function getTransactionHeadforLedger($table_name, $organisation, $start_date, $end_date, $where) {
        return $this->mapper->getTransactionHeadforLedger($table_name, $organisation, $start_date, $end_date, $where);
    }

    public function getOpeningBalanceforHLedger($table_name, $start_date, $organisation, $id) {
        return $this->mapper->getOpeningBalanceforHLedger($table_name, $start_date, $organisation, $id);
    }

    public function getTransactionSubheadforCashFlow($table_name, $organisation, $start_date, $end_date, $where) {
        return $this->mapper->getTransactionSubheadforCashFlow($table_name, $organisation, $start_date, $end_date, $where);
    }

    public function getOpeningBalanceforCABA($type, $table_name, $organisation, $date, $subhead_id) {
        return $this->mapper->getOpeningBalanceforCABA($type, $table_name, $organisation, $date, $subhead_id);
    }

    public function getDisinctTransactionBCAID($table_name, $accounts, $start_date, $end_date) {
        return $this->mapper->getDisinctTransactionBCAID($table_name, $accounts, $start_date, $end_date);
    }

    public function getCashFlow($table_name, $where) {
        return $this->mapper->getCashFlow($table_name, $where);
    }

    public function getBalanceSheetClass($organisation, $start_date, $end_date) {
        return $this->mapper->getBalanceSheetClass($organisation, $start_date, $end_date);
    }

    public function getTransactionGroupforBS($organisation, $start_date, $end_date, $where) {
        return $this->mapper->getTransactionGroupforBS($organisation, $start_date, $end_date, $where);
    }

    public function getClosingBalanceforPresBS($organisation, $starting_date, $ending_date, $id, $tier) {
        return $this->mapper->getClosingBalanceforPresBS($organisation, $starting_date, $ending_date, $id, $tier);
    }

    public function getSumbySubheadforPresBS($organisation, $starting_date, $ending_date, $column, $sub_head) {
        return $this->mapper->getSumbySubheadforPresBS($organisation, $starting_date, $ending_date, $column, $sub_head);
    }

    public function getSumbyHeadforPresBS($organisation, $starting_date, $ending_date, $column, $head) {
        return $this->mapper->getSumbyHeadforPresBS($organisation, $starting_date, $ending_date, $column, $head);
    }

    public function getSumbyGroupforPresBS($organisation, $starting_date, $ending_date, $column, $group) {
        return $this->mapper->getSumbyGroupforPresBS($organisation, $starting_date, $ending_date, $column, $group);
    }

    public function getSumbyClassforPresBS($organisation, $starting_date, $ending_date, $column, $class) {
        return $this->mapper->getSumbyClassforPresBS($organisation, $starting_date, $ending_date, $column, $class);
    }

    public function getClosingBalanceforPrevBS($organisation, $starting_date, $ending_date, $id, $tier) {
        return $this->mapper->getClosingBalanceforPrevBS($organisation, $starting_date, $ending_date, $id, $tier);
    }

    public function getSumbySubheadforPrevBS($organisation, $starting_date, $ending_date, $column, $sub_head) {
        return $this->mapper->getSumbySubheadforPrevBS($organisation, $starting_date, $ending_date, $column, $sub_head);
    }

    public function getSumbyHeadforPrevBS($organisation, $starting_date, $ending_date, $column, $head) {
        return $this->mapper->getSumbyHeadforPrevBS($organisation, $starting_date, $ending_date, $column, $head);
    }

    public function getSumbyGroupforPrevBS($organisation, $starting_date, $ending_date, $column, $group) {
        return $this->mapper->getSumbyGroupforPrevBS($organisation, $starting_date, $ending_date, $column, $group);
    }

    public function getSumbyClassforPrevBS($organisation, $starting_date, $ending_date, $column, $class) {
        return $this->mapper->getSumbyClassforPrevBS($organisation, $starting_date, $ending_date, $column, $class);
    }

    public function getMin($table_name, $column, $where) {
        return $this->mapper->getMin($table_name, $column, $where);
    }

    public function getlocationDateWise($table_name, $column, $userorg, $year, $month, $param) {
        return $this->mapper->getlocationDateWise($table_name, $column, $userorg, $year, $month, $param);
    }

    public function getBSubledger($param, $user_org, $order) {
        return $this->mapper->getBSubledger($param, $user_org, $order);
    }

    public function getCSubledger($param, $user_org, $order) {
        return $this->mapper->getCSubledger($param, $user_org, $order);
    }

    public function getBankandCashBalance($type, $date, $sub_ledger, $organisation) {
        return $this->mapper->getBankandCashBalance($type, $date, $sub_ledger, $organisation);
    }

    public function getDistinctESP($user_org) {
        return $this->mapper->getDistinctESP($user_org);
    }

    public function getBCADetails($user_org, $param, $order) {
        return $this->mapper->getBCADetails($user_org, $param, $order);
    }

    public function getASSubLedger($param, $order) {
        return $this->mapper->getASSubLedger($param, $order);
    }

    public function getSerial($table_name, $prefix_PO_code) {
        return $this->mapper->getSerial($table_name, $prefix_PO_code);
    }

    public function getParty($type_id, $param) {
        return $this->mapper->getParty($type_id, $param);
    }

    public function getNotInDtl($param, $column, $where) {
        return $this->mapper->getNotInDtl($param, $column, $where);
    }

    public function remove($tableName, $id) {
        return $this->mapper->remove($tableName, $id);
    }

    public function getDataByFilter($type, $tablename, $params, $column) {
        return $this->mapper->getDataByFilter($type, $tablename, $params, $column);
    }

    public function getClosingBalanceforPresPLS($organisation, $starting_date, $ending_date, $id, $tier) {
        return $this->mapper->getClosingBalanceforPresPLS($organisation, $starting_date, $ending_date, $id, $tier);
    }

    public function getClosingBalanceforPrevPLS($organisation, $starting_date, $ending_date, $id, $tier) {
        return $this->mapper->getClosingBalanceforPrevPLS($organisation, $starting_date, $ending_date, $id, $tier);
    }

    public function getTransactionSubheadforBRS($organisation, $start_date, $end_date, $where) {
        return $this->mapper->getTransactionSubheadforBRS($organisation, $start_date, $end_date, $where);
    }

    public function getBudgetforBRS($organisation, $end_date, $subhead_id) {
        return $this->mapper->getBudgetforBRS($organisation, $end_date, $subhead_id);
    }

    public function getSumbySubheadBudgetforBRS($organisation, $end_date, $column, $sub_head) {
        return $this->mapper->getSumbySubheadBudgetforBRS($organisation, $end_date, $column, $sub_head);
    }

    public function getOpeningBalanceCBforBRS($organisation, $end_date, $subhead_id) {
        return $this->mapper->getOpeningBalanceCBforBRS($organisation, $end_date, $subhead_id);
    }

    public function getOpeningBalanceBSforBRS($organisation, $end_date, $subhead_id) {
        return $this->mapper->getOpeningBalanceBSforBRS($organisation, $end_date, $subhead_id);
    }

    public function getSumbySubheadCBforBRS($organisation, $end_date, $column, $sub_head) {
        return $this->mapper->getSumbySubheadCBforBRS($organisation, $end_date, $column, $sub_head);
    }

    public function getAmountDebitedCB($organisation, $start_date, $end_date, $column, $where) {
        return $this->mapper->getAmountDebitedCB($organisation, $start_date, $end_date, $column, $where);
    }

    public function getAmountDebitedBS($organisation, $start_date, $end_date, $column, $where) {
        return $this->mapper->getAmountDebitedBS($organisation, $start_date, $end_date, $column, $where);
    }

    public function getTransactionClass($organisation, $start_date, $end_date) {
        return $this->mapper->getTransactionClass($organisation, $start_date, $end_date);
    }

    public function getSumbyClass($organisation, $start_date, $end_date, $column, $class) {
        return $this->mapper->getSumbyClass($organisation, $start_date, $end_date, $column, $class);
    }

    public function getOpeningBalance($organisation, $start_date, $end_date, $id, $tier) {
        return $this->mapper->getOpeningBalance($organisation, $start_date, $end_date, $id, $tier);
    }

    public function getClosingBalanceAL($organisation, $start_date, $end_date, $id, $tier) {
        return $this->mapper->getClosingBalanceAL($organisation, $start_date, $end_date, $id, $tier);
    }

    public function getClosingBalanceIE($organisation, $start_date, $end_date, $id, $tier) {
        return $this->mapper->getClosingBalanceIE($organisation, $start_date, $end_date, $id, $tier);
    }

    public function getTransactionGroup($organisation, $start_date, $end_date, $where) {
        return $this->mapper->getTransactionGroup($organisation, $start_date, $end_date, $where);
    }

    public function getSumbyGroup($organisation, $start_date, $end_date, $column, $group) {
        return $this->mapper->getSumbyGroup($organisation, $start_date, $end_date, $column, $group);
    }

    public function getTransactionHead($organisation, $start_date, $end_date, $where) {
        return $this->mapper->getTransactionHead($organisation, $start_date, $end_date, $where);
    }

    public function getSumbyHeadandSubhead($type, $organisation, $start_date, $end_date, $column, $head) {
        return $this->mapper->getSumbyHeadandSubhead($type, $organisation, $start_date, $end_date, $column, $head);
    }

    public function getTransactionSubhead($organisation, $start_date, $end_date, $where) {
        return $this->mapper->getTransactionSubhead($organisation, $start_date, $end_date, $where);
    }

    public function getTransactionHeadforBS($organisation, $start_date, $end_date, $where) {
        return $this->mapper->getTransactionHeadforBS($organisation, $start_date, $end_date, $where);
    }

    public function getTransactionSubheadforBS($organisation, $start_date, $end_date, $where) {
        return $this->mapper->getTransactionSubheadforBS($organisation, $start_date, $end_date, $where);
    }

    public function getSumbySubheadforLedgerOpening($table_name, $start_date, $column, $sub_head) {
        return $this->mapper->getSumbySubheadforLedgerOpening($table_name, $start_date, $column, $sub_head);
    }

    public function getSumbyheadforLedgerOpening($table_name, $start_date, $column, $head) {
        return $this->mapper->getSumbyheadforLedgerOpening($table_name, $start_date, $column, $head);
    }

    public function getSumbySubheadforCABA($type, $table_name, $organisation, $start_date, $column, $sub_head) {
        return $this->mapper->getSumbySubheadforCABA($type, $table_name, $organisation, $start_date, $column, $sub_head);
    }

    public function getSumbyBankandCashSubLedger($start_date, $column, $sub_ledger) {
        return $this->mapper->getSumbyBankandCashSubLedger($start_date, $column, $sub_ledger);
    }

    public function getSumbySubheadforPresPLS($organisation, $starting_date, $ending_date, $column, $sub_head) {
        return $this->mapper->getSumbySubheadforPresPLS($organisation, $starting_date, $ending_date, $column, $sub_head);
    }

    public function getSumbyHeadforPresPLS($organisation, $starting_date, $ending_date, $column, $head) {
        return $this->mapper->getSumbyHeadforPresPLS($organisation, $starting_date, $ending_date, $column, $head);
    }

    public function getSumbyGroupforPresPLS($organisation, $starting_date, $ending_date, $column, $group) {
        return $this->mapper->getSumbyGroupforPresPLS($organisation, $starting_date, $ending_date, $column, $group);
    }

    public function getSumbyClassforPresPLS($organisation, $starting_date, $ending_date, $column, $class) {
        return $this->mapper->getSumbyClassforPresPLS($organisation, $starting_date, $ending_date, $column, $class);
    }

    public function getSumbyHeadandSubheadTBOpening($type, $organisation, $start_date, $column, $head) {
        return $this->mapper->getSumbyHeadandSubheadTBOpening($type, $organisation, $start_date, $column, $head);
    }

    public function getSumbyGroupTBOpening($organisation, $start_date, $column, $group) {
        return $this->mapper->getSumbyGroupTBOpening($organisation, $start_date, $column, $group);
    }

    public function getSumbyClassTBOpening($organisation, $start_date, $column, $class) {
        return $this->mapper->getSumbyClassTBOpening($organisation, $start_date, $column, $class);
    }

    /* Bank Statement */
    public function getTransactionSubheadforBankStatement($organisation,$start_date,$end_date,$where) {
        return $this->mapper->getTransactionSubheadforBankStatement($organisation,$start_date,$end_date,$where);
    }

    public function getBankStatement($organisation,$start_date,$end_date,$where) {
        return $this->mapper->getBankStatement($organisation,$start_date,$end_date,$where);
    }

    public function deleteTable($tableName, $id) {
        return $this->mapper->deleteTable($tableName, $id);
    }

    public function getBankAccountBalanceFromTransaction($params) {
        return $this->mapper->getBankAccountBalanceFromTransaction($params);
    }
}

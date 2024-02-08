<?php

namespace Accounts\Service;

interface MasterServiceInterface {

    public function getLoginEmpDetailfrmUsername($username);

    public function getTableData($tablename);

    public function saveupdateData($tablename, $params);

    public function getDatabyParam($tablename, $params, $column);

    public function getSubheadData($tablename, $params);

    public function getTransactionSubheadforLedger($table_name, $organisation, $start_date, $end_date, $where);

    public function getOpeningBalanceforSHLedger($table_name, $start_date, $organisation, $id);

    public function getTransactionIDforLedger($table_name, $organisation, $start_date, $end_date, $where);

    public function getSumbyTransactionIDforLedger($table_name, $organisation, $start_date, $end_date, $column, $where);

    public function getTransactionHeadforLedger($table_name, $organisation, $start_date, $end_date, $where);

    public function getOpeningBalanceforHLedger($table_name, $start_date, $organisation, $id);

    public function getTransactionSubheadforCashFlow($table_name, $organisation, $start_date, $end_date, $where);

    public function getOpeningBalanceforCABA($type, $table_name, $organisation, $date, $subhead_id);

    public function getDisinctTransactionBCAID($table_name, $accounts, $start_date, $end_date);

    public function getCashFlow($table_name, $where);

    public function getBalanceSheetClass($organisation, $start_date, $end_date);

    public function getTransactionGroupforBS($organisation, $start_date, $end_date, $where);

    public function getClosingBalanceforPresBS($organisation, $starting_date, $ending_date, $id, $tier);

    public function getSumbySubheadforPresBS($organisation, $starting_date, $ending_date, $column, $sub_head);

    public function getSumbyHeadforPresBS($organisation, $starting_date, $ending_date, $column, $head);

    public function getSumbyGroupforPresBS($organisation, $starting_date, $ending_date, $column, $group);

    public function getSumbyClassforPresBS($organisation, $starting_date, $ending_date, $column, $class);

    public function getClosingBalanceforPrevBS($organisation, $starting_date, $ending_date, $id, $tier);

    public function getSumbySubheadforPrevBS($organisation, $starting_date, $ending_date, $column, $sub_head);

    public function getSumbyHeadforPrevBS($organisation, $starting_date, $ending_date, $column, $head);

    public function getSumbyGroupforPrevBS($organisation, $starting_date, $ending_date, $column, $group);

    public function getSumbyClassforPrevBS($organisation, $starting_date, $ending_date, $column, $class);

    public function getMin($table_name, $column, $where);

    public function getlocationDateWise($table_name, $column, $userorg, $year, $month, $param);

    public function getBSubledger($param, $user_org, $order);

    public function getCSubledger($param, $user_org, $order);

    public function getBankandCashBalance($type, $date, $sub_ledger, $organisation);

    public function getDistinctESP($user_org);

    public function getBCADetails($user_org, $param, $order);

    public function getASSubLedger($param, $order);

    public function getSerial($table_name, $prefix_PO_code);

    public function getParty($type_id, $param);

    public function getNotInDtl($param, $column, $where);

    public function remove($tableName, $id);

    public function getDataByFilter($type, $tablename, $params, $column);

    public function getClosingBalanceforPresPLS($organisation, $starting_date, $ending_date, $id, $tier);

    public function getClosingBalanceforPrevPLS($organisation, $starting_date, $ending_date, $id, $tier);

    public function getTransactionSubheadforBRS($organisation, $start_date, $end_date, $where);

    public function getBudgetforBRS($organisation, $end_date, $subhead_id);

    public function getSumbySubheadBudgetforBRS($organisation, $end_date, $column, $sub_head);

    public function getOpeningBalanceCBforBRS($organisation, $end_date, $subhead_id);

    public function getOpeningBalanceBSforBRS($organisation, $end_date, $subhead_id);

    public function getSumbySubheadCBforBRS($organisation, $end_date, $column, $sub_head);

    public function getAmountDebitedCB($organisation, $start_date, $end_date, $column, $where);

    public function getAmountDebitedBS($organisation, $start_date, $end_date, $column, $where);

    public function getTransactionClass($organisation, $start_date, $end_date);

    public function getSumbyClass($organisation, $start_date, $end_date, $column, $class);

    public function getOpeningBalance($organisation, $start_date, $end_date, $id, $tier);

    public function getClosingBalanceAL($organisation, $start_date, $end_date, $id, $tier);

    public function getClosingBalanceIE($organisation, $start_date, $end_date, $id, $tier);

    public function getTransactionGroup($organisation, $start_date, $end_date, $where);

    public function getSumbyGroup($organisation, $start_date, $end_date, $column, $group);

    public function getTransactionHead($organisation, $start_date, $end_date, $where);

    public function getSumbyHeadandSubhead($type, $organisation, $start_date, $end_date, $column, $head);

    public function getTransactionSubhead($organisation, $start_date, $end_date, $where);

    public function getTransactionHeadforBS($organisation, $start_date, $end_date, $where);

    public function getTransactionSubheadforBS($organisation, $start_date, $end_date, $where);

    public function getSumbySubheadforLedgerOpening($table_name, $start_date, $column, $sub_head);

    public function getSumbyheadforLedgerOpening($table_name, $start_date, $column, $head);

    public function getSumbySubheadforCABA($type, $table_name, $organisation, $start_date, $column, $sub_head);

    public function getSumbyBankandCashSubLedger($start_date, $column, $sub_ledger);

    public function getSumbySubheadforPresPLS($organisation, $starting_date, $ending_date, $column, $sub_head);

    public function getSumbyHeadforPresPLS($organisation, $starting_date, $ending_date, $column, $head);

    public function getSumbyGroupforPresPLS($organisation, $starting_date, $ending_date, $column, $group);

    public function getSumbyClassforPresPLS($organisation, $starting_date, $ending_date, $column, $class);

    public function getSumbyHeadandSubheadTBOpening($type, $organisation, $start_date, $column, $head);

    public function getSumbyGroupTBOpening($organisation, $start_date, $column, $group);

    public function getSumbyClassTBOpening($organisation, $start_date, $column, $class);

    /* Bank Statement */
    public function getTransactionSubheadforBankStatement($organisation,$start_date,$end_date,$where);

    public function getBankStatement($organisation,$start_date,$end_date,$where);

    public function deleteTable($tableName, $id);

    public function getBankAccountBalanceFromTransaction($params);
}

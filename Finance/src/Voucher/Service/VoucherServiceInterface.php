<?php

namespace Voucher\Service;

use Voucher\Model\Voucher;

interface VoucherServiceInterface
{
	/**
	 * Should return a set of all proposals that we can iterate over. 
	 *
	 * @return array|VoucherInterface[]
	*/
	
	public function listAll($tableName);
	
	/*
	* Find the Proposal Details
	*/
	
	public function findCalendarDetail($id);
	
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username);
	
	/*
	* Get organisation id based on the username
	*/
	
	public function getOrganisationId($username);
        
	 
	 /**
	 * @param VoucherInterface $budgetingObject
	 *
	 * @param VoucherInterface $budgetingObject
	 * @return VoucherInterface
	 * @throws \Exception
	 */
	 
	 public function saveVoucher(Voucher $chequeObject);
	 
	 
	 /**
	 * Should return a set of all objectives that we can iterate over. 
	 * 
	 * The purpose of the function is the objectives for the dropdown select list
	 *
	 * @return array|VoucherInterface[]
	*/
	
	public function listSelectData($tableName, $columnName, $condition);
		
		
}
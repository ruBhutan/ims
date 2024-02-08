<?php

namespace Voucher\Mapper;

use Voucher\Model\Voucher;

interface VoucherMapperInterface
{

	/**
	 * 
	 * @return array/ Voucher[]
	 */
	 
	public function findAll($tableName);
	
	/*
	* Find the Academic Calendar Details
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
	 * 
	 * @param type $VoucherInterface
	 * 
	 * to save academics
	 */
	
	public function saveVoucher(Voucher $VoucherInterface);
	
	/**
	 * 
	 * @return array/ Voucher[]
	 */
	 
	public function listSelectData($tableName, $columnName, $condition);
	
}
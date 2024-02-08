<?php

namespace Voucher\Service;

use Voucher\Mapper\VoucherMapperInterface;
use Voucher\Model\Voucher;

class VoucherService implements VoucherServiceInterface
{
	/**
	 * @var \Blog\Mapper\VoucherMapperInterface
	*/
	
	protected $voucherMapper;
	
	public function __construct(VoucherMapperInterface $voucherMapper) {
		$this->voucherMapper = $voucherMapper;
	}
	
	public function listAll($tableName)
	{
		return $this->voucherMapper->findAll($tableName);
	}
	
	public function findCalendarDetail($id)
	{
		return $this->voucherMapper->findCalendarDetail($id);
	}
	
	public function getUserDetailsId($username)
	{
		return $this->voucherMapper->getUserDetailsId($username);
	}
	
	public function getOrganisationId($username)
	{
		return $this->voucherMapper->getOrganisationId($username);
	}
		
	public function saveVoucher(Voucher $voucherObject) 
	{
		return $this->voucherMapper->saveVoucher($voucherObject);
	}
	
	public function listSelectData($tableName, $columnName, $condition)
	{
		return $this->voucherMapper->listSelectData($tableName, $columnName, $condition);
	}
	
}
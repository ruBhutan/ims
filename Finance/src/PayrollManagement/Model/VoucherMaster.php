<?php

namespace Masters\Model;

class VoucherMaster
{
	protected $id;
	protected $voucher_type;
	protected $remarks;
	 
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getVoucher_Type()
	{
		return $this->voucher_type;
	}
	
	public function setVoucher_Type($voucher_type)
	{
		$this->voucher_type = $voucher_type;
	}
	
	public function getRemarks()
	{
		return $this->remarks;
	}
	
	public function setRemarks($remarks)
	{
		$this->remarks = $remarks;
	}
}
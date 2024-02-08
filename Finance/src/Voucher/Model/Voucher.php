<?php

namespace Voucher\Model;

class Voucher
{
	protected $id;
	protected $voucher_date;
	protected $voucher_added_date;
	protected $account_no;
	protected $voucher_type;
	protected $payee_details;
	protected $particulars;
	protected $income_type;
	protected $amount;
	protected $tds_percentage;
	protected $remarks;
	protected $status;
	 
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getVoucher_Date()
	{
		return $this->voucher_date;
	}
	
	public function setVoucher_Date($voucher_date)
	{
		$this->voucher_date = $voucher_date;
	}
	
	public function getVoucher_Added_Date()
	{
		return $this->voucher_added_date;
	}
	
	public function setVoucher_Added_Date($voucher_added_date)
	{
		$this->voucher_added_date = $voucher_added_date;
	}
	
	public function getAccount_No()
	{
		return $this->account_no;
	}
	
	public function setAccount_No($account_no)
	{
		$this->account_no = $account_no;
	}
	
	public function getVoucher_Type()
	{
		return $this->voucher_type;
	}
	
	public function setVoucher_Type($voucher_type)
	{
		$this->voucher_type = $voucher_type;
	}
	
	public function getPayee_Details()
	{
		return $this->payee_details;
	}
	
	public function setPayee_Details($payee_details)
	{
		$this->payee_details = $payee_details;
	}
	
	public function getParticulars()
	{
		return $this->particulars;
	}
	
	public function setParticulars($particulars)
	{
		$this->particulars = $particulars;
	}
	
	public function getIncome_Type()
	{
		return $this->income_type;
	}
	
	public function setIncome_Type($income_type)
	{
		$this->income_type = $income_type;
	}
	
	public function getAmount()
	{
		return $this->amount;
	}
	
	public function setAmount($amount)
	{
		$this->amount = $amount;
	}
	
	public function getTds_Percentage()
	{
		return $this->tds_percentage;
	}
	
	public function setTds_Percentage($tds_percentage)
	{
		$this->tds_percentage = $tds_percentage;
	}
	
	public function getRemarks()
	{
		return $this->remarks;
	}
	
	public function setRemarks($remarks)
	{
		$this->remarks = $remarks;
	}
	
	public function getStatus()
	{
		return $this->status;
	}
	
	public function setStatus($status)
	{
		$this->status = $status;
	}
	 
}
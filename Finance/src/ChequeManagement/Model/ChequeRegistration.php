<?php

namespace ChequeManagement\Model;

class ChequeRegistration
{
	protected $id;
	protected $bank_name;
	protected $bank_account_no;
	 
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getBank_Name()
	{
		return $this->bank_name;
	}
	
	public function setBank_Name($bank_name)
	{
		$this->bank_name = $bank_name;
	}
	
	public function getBank_Account_No()
	{
		return $this->bank_account_no;
	}
	
	public function setBank_Account_No($bank_account_no)
	{
		$this->bank_account_no = $bank_account_no;
	}
}
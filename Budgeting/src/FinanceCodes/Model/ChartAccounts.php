<?php

namespace FinanceCodes\Model;

class ChartAccounts
{
	protected $id;
	protected $head_of_accounts;
	protected $account_code;
	protected $accounts_group_head_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
		
	public function getHead_Of_Accounts()
	{
		return $this->head_of_accounts;
	}
	
	public function setHead_Of_Accounts($head_of_accounts)
	{
		$this->head_of_accounts = $head_of_accounts;
	}
	
	public function getAccount_Code()
	{
		return $this->account_code;
	}
	
	public function setAccount_Code($account_code)
	{
		$this->account_code = $account_code;
	}
	
	public function getAccounts_Group_Head_Id()
	{
		return $this->accounts_group_head_id;
	}
	
	public function setAccounts_Group_Head_Id($accounts_group_head_id)
	{
		$this->accounts_group_head_id =$accounts_group_head_id;
	}
}
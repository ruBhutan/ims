<?php

namespace Masters\Model;

class FloatingDeductions
{
	protected $id;
	protected $account_group_head;
	protected $chart_of_accounts;
	protected $deduction_percentage;
	protected $remarks;
	protected $floating_deductions_id;
	 
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getAccount_Group_Head()
	{
		return $this->account_group_head;
	}
	
	public function setAccount_Group_Head($account_group_head)
	{
		$this->account_group_head = $account_group_head;
	}
	
	public function getChart_Of_Accounts()
	{
		return $this->chart_of_accounts;
	}
	
	public function setChart_Of_Accounts($chart_of_accounts)
	{
		$this->chart_of_accounts = $chart_of_accounts;
	}
	
	public function getDeduction_Percentage()
	{
		return $this->deduction_percentage;
	}
	
	public function setDeduction_Percentage($deduction_percentage)
	{
		$this->deduction_percentage = $deduction_percentage;
	}
	
	public function getRemarks()
	{
		return $this->remarks;
	}
	
	public function setRemarks($remarks)
	{
		$this->remarks = $remarks;
	}
	
	public function getFloating_Deductions_Id()
	{
		return $this->floating_deductions_id;
	}
	
	public function setFloating_Deductions_Id($floating_deductions_id)
	{
		$this->floating_deductions_id = $floating_deductions_id;
	}
}
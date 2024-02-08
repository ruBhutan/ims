<?php

namespace Budgeting\Model;

class BudgetReappropriationSelect
{
	protected $id;
	protected $budget_type;
	protected $from_budget_ledger_head_id;
	protected $from_accounts_group_head_id;
	protected $from_chart_of_accounts_id;
	protected $to_budget_ledger_head_id;
	protected $to_accounts_group_head_id;
	protected $to_chart_of_accounts_id;
	protected $organisation_id;
	protected $status;

	public function getId()
	{
		return $this->id;
	}
	
	public function setId($id)
	{
		$this->id = $id;
	}

	public function getBudget_Type()
	{
		return $this->budget_type;
	}
	
	public function setBudget_Type($budget_type)
	{
		$this->budget_type = $budget_type;
	}
	
	public function getFrom_Budget_Ledger_Head_Id()
	{
		return $this->from_budget_ledger_head_id;
	}
	
	public function setFrom_Budget_Ledger_Head_Id($from_budget_ledger_head_id)
	{
		$this->from_budget_ledger_head_id = $from_budget_ledger_head_id;
	}
	
	public function getFrom_Accounts_Group_Head_Id()
	{
		return $this->from_accounts_group_head_id;
	}
	
	public function setFrom_Accounts_Group_Head_Id($from_accounts_group_head_id)
	{
		$this->from_accounts_group_head_id = $from_accounts_group_head_id;
	}
	
	public function getFrom_Chart_of_Accounts_Id()
	{
		return $this->from_chart_of_accounts_id;
	}
	
	public function setFrom_Chart_of_Accounts_Id($from_chart_of_accounts_id)
	{
		$this->from_chart_of_accounts_id = $from_chart_of_accounts_id;
	}
	
	public function getTo_Budget_Ledger_Head_Id()
	{
		return $this->to_budget_ledger_head_id;
	}
	
	public function setTo_Budget_Ledger_Head_Id($to_budget_ledger_head_id)
	{
		$this->to_budget_ledger_head_id = $to_budget_ledger_head_id;
	}
	
	public function getTo_Accounts_Group_Head_Id()
	{
		return $this->to_accounts_group_head_id;
	}
	
	public function setTo_Accounts_Group_Head_Id($to_accounts_group_head_id)
	{
		$this->to_accounts_group_head_id = $to_accounts_group_head_id;
	}
	
	public function getTo_Chart_of_Accounts_Id()
	{
		return $this->to_chart_of_accounts_id;
	}
	
	public function setTo_Chart_of_Accounts_Id($to_chart_of_accounts_id)
	{
		$this->to_chart_of_accounts_id = $to_chart_of_accounts_id;
	}
	
	public function getOrganisation_Id()
	{
		return $this->organisation_id;
	}
	
	public function setOrganisation_Id($organisation_id)
	{
		$this->organisation_id = $organisation_id;
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
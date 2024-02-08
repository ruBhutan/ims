<?php

namespace Budgeting\Model;

class CapitalBudgetProposal
{
	protected $id;
	protected $five_year_plan;
	protected $financial_year;
	protected $budget_type;
	protected $proposed_budget_amount;
	protected $write_up;
	protected $budget_amount_approved;
	protected $balance;
	protected $budget_proposal_status;
	protected $object_code_id;
	protected $activity_name;
	protected $activity_id;
	protected $broad_head_name_id;
	protected $accounts_group_head_id;
	protected $departments_id;
	protected $organisation_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getFive_Year_Plan()
	{
		return $this->five_year_plan; 
	}
	 	 
	public function setFive_Year_Plan($five_year_plan)
	{
		$this->five_year_plan = $five_year_plan;
	}
	 
	public function getFinancial_Year()
	{
		return $this->financial_year;
	}
	 
	public function setFinancial_Year($financial_year)
	{
		$this->financial_year = $financial_year;
	}
	 
	public function getBudget_Type()
	{
		return $this->budget_type;
	}
	
	public function setBudget_Type($budget_type)
	{
		$this->budget_type = $budget_type;
	}
	
	public function getProposed_Budget_Amount()
	{
		return $this->proposed_budget_amount;
	}
	
	public function setProposed_Budget_Amount($proposed_budget_amount)
	{
		$this->proposed_budget_amount = $proposed_budget_amount;
	}
	
	public function getBalance()
	{
		return $this->balance;
	}
	
	public function setBalance($balance)
	{
		$this->balance = $balance;
	}
	
	public function getWrite_Up()
	{
		return $this->write_up;
	}
	
	public function setWrite_Up($write_up)
	{
		$this->write_up = $write_up;
	}
	
	public function getBudget_Amount_Approved()
	{
		return $this->budget_amount_approved;
	}
	
	public function setBudget_Amount_Approved($budget_amount_approved)
	{
		$this->budget_amount_approved = $budget_amount_approved;
	}
	
	public function getBudget_Proposal_Status()
	{
		return $this->budget_proposal_status;
	}
	
	public function setBudget_Proposal_Status($budget_proposal_status)
	{
		$this->budget_proposal_status = $budget_proposal_status;
	}
	
	public function getActivity_Name()
	{
		return $this->activity_name;
	}
	
	public function setActivity_Name($activity_name)
	{
		$this->activity_name = $activity_name;
	}
	
	public function getActivity_Id()
	{
		return $this->activity_id;
	}
	
	public function setActivity_Id($activity_id)
	{
		$this->activity_id = $activity_id;
	}
	
	public function getBroad_Head_Name_Id()
	{
		return $this->broad_head_name_id;
	}
	
	public function setBroad_Head_Name_Id($broad_head_name_id)
	{
		$this->broad_head_name_id = $broad_head_name_id;
	}
	
	public function getObject_Code_Id()
	{
		return $this->object_code_id;
	}
	
	public function setObject_Code_Id($object_code_id)
	{
		$this->object_code_id = $object_code_id;
	}
	
	public function getDepartments_Id()
	{
		return $this->departments_id;
	}
	
	public function setDepartments_Id($departments_id)
	{
		$this->departments_id = $departments_id;
	}
	
	public function getOrganisation_Id()
	{
		return $this->organisation_id;
	}
	
	public function setOrganisation_Id($organisation_id)
	{
		$this->organisation_id = $organisation_id;
	}
	
}
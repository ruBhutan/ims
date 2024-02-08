<?php

namespace BudgetTransactions\Model;

class BudgetTransactions
{
	protected $id;
	protected $reference_no;
	protected $budget_type;
	protected $reference_date;
	protected $reasons;
	protected $from_proposal_id;
	protected $amount;
	protected $remarks;
	protected $status;
	protected $organisation_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getReference_No()
	{
		return $this->reference_no;
	}
	
	public function setReference_No($reference_no)
	{
		$this->reference_no = $reference_no;
	}
	
	public function getBudget_Type()
	{
		return $this->budget_type;
	}
	
	public function setBudget_Type($budget_type)
	{
		$this->budget_type = $budget_type;
	}
	
	public function getReference_Date()
	{
		return $this->reference_date;
	}
	
	public function setReference_Date($reference_date)
	{
		$this->reference_date = $reference_date;
	}
	
	public function getReasons()
	{
		return $this->reasons;
	}
	
	public function setReasons($reasons)
	{
		$this->reasons = $reasons;
	}
	
	public function getFrom_Proposal_Id()
	{
		return $this->from_proposal_id;
	}
	
	public function setFrom_Proposal_Id($from_proposal_id)
	{
		$this->from_proposal_id = $from_proposal_id;
	}
		
	public function getAmount()
	{
		return $this->amount;
	}
	
	public function setAmount($amount)
	{
		$this->amount = $amount;
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
	
	public function getOrganisation_Id()
	{
		return $this->organisation_id;
	}
	
	public function setOrganisation_Id($organisation_id)
	{
		$this->organisation_id = $organisation_id;
	}
	
}
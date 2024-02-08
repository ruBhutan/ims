<?php
 /*
 * this model of reappropriation works for both current and capital
 * as we only need the id of the proposals
 */

namespace Budgeting\Model;

class BudgetReappropriation
{
	protected $id;
	protected $budget_type;
	protected $reference_no;
	protected $reference_date;
	protected $purpose;
	protected $from_proposal_id;
	protected $to_proposal_id;
	protected $from_amount;
	protected $to_amount;
	protected $status;
	
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
	
	public function getReference_Date()
	{
		return $this->reference_date;
	}
	
	public function setReference_Date($reference_date)
	{
		$this->reference_date = $reference_date;
	}
	
	public function getPurpose()
	{
		return $this->purpose;
	}
	
	public function setPurpose($purpose)
	{
		$this->purpose = $purpose;
	}
	
	public function getFrom_Proposal_Id()
	{
		return $this->from_proposal_id;
	}
	
	public function setFrom_Proposal_Id($from_proposal_id)
	{
		$this->from_proposal_id = $from_proposal_id;
	}
	
	public function getTo_Proposal_Id()
	{
		return $this->to_proposal_id;
	}
	
	public function setTo_Proposal_Id($to_proposal_id)
	{
		$this->to_proposal_id = $to_proposal_id;
	}
	
	public function getFrom_Amount()
	{
		return $this->from_amount;
	}
	
	public function setFrom_Amount($from_amount)
	{
		$this->from_amount = $from_amount;
	}
	
	public function getTo_Amount()
	{
		return $this->to_amount;
	}
	
	public function setTo_Amount($to_amount)
	{
		$this->to_amount = $to_amount;
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
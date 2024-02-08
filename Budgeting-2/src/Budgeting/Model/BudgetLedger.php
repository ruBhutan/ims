<?php

namespace Budgeting\Model;

class BudgetLedger
{
	protected $id;
	protected $ledger_head;
	protected $remarks;
	protected $departments_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
		
	public function getLedger_Head()
	{
		return $this->ledger_head;
	}
	
	public function setLedger_Head($ledger_head)
	{
		$this->ledger_head = $ledger_head;
	}
	
	public function getRemarks()
	{
		return $this->remarks;
	}
	
	public function setRemarks($remarks)
	{
		$this->remarks = $remarks;
	}
		
	public function getDepartments_Id()
	{
		return $this->departments_id;
	}
	
	public function setDepartments_Id($departments_id)
	{
		$this->departments_id = $departments_id;
	}	
}
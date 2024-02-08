<?php

namespace FinanceCodes\Model;

class AccountsGroupHead
{
	protected $id;
	protected $group_head;
	protected $group_code;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getGroup_Head()
	{
		return $this->group_head;
	}
	
	public function setGroup_Head($group_head)
	{
		$this->group_head = $group_head;
	}
	
	public function getGroup_Code()
	{
		return $this->group_code;
	}
	
	public function setGroup_Code($group_code)
	{
		$this->group_code = $group_code;
	}
}
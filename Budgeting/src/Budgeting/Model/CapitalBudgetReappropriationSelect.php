<?php

namespace Budgeting\Model;

class CapitalBudgetReappropriationSelect
{
	protected $id;
	protected $budget_type;
	protected $from_activity_name_id;
	protected $from_broad_head_name_id;
	protected $from_object_code_id;
	protected $to_activity_name_id;
	protected $to_broad_head_name_id;
	protected $to_object_code_id;
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
	
	public function getFrom_Activity_Name_Id()
	{
		return $this->from_activity_name_id;
	}
	
	public function setFrom_Activity_Name_Id($from_activity_name_id)
	{
		$this->from_activity_name_id = $from_activity_name_id;
	}
	
	public function getFrom_Broad_Head_Name_Id()
	{
		return $this->from_broad_head_name_id;
	}
	
	public function setFrom_Broad_Head_Name_Id($from_broad_head_name_id)
	{
		$this->from_broad_head_name_id = $from_broad_head_name_id;
	}
	
	public function getFrom_Object_Code_Id()
	{
		return $this->from_object_code_id;
	}
	
	public function setFrom_Object_Code_Id($from_object_code_id)
	{
		$this->from_object_code_id = $from_object_code_id;
	}
	
	public function getTo_Activity_Name_Id()
	{
		return $this->to_activity_name_id;
	}
	
	public function setTo_Activity_Name_Id($to_activity_name_id)
	{
		$this->to_activity_name_id = $to_activity_name_id;
	}
	
	public function getTo_Broad_Head_Name_Id()
	{
		return $this->to_broad_head_name_id;
	}
	
	public function setTo_Broad_Head_Name_Id($to_broad_head_name_id)
	{
		$this->to_broad_head_name_id = $to_broad_head_name_id;
	}
	
	public function getTo_Object_Code_Id()
	{
		return $this->to_object_code_id;
	}
	
	public function setTo_Object_Code_Id($to_object_code_id)
	{
		$this->to_object_code_id = $to_object_code_id;
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
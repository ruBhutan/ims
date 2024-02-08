<?php

namespace Masters\Model;

class FloatingDeductionsType
{
	protected $id;
	protected $type_of_deductions;
	protected $deduction_percentage;
	protected $description;
	 
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getType_Of_Deductions()
	{
		return $this->type_of_deductions;
	}
	
	public function setType_Of_Deductions($type_of_deductions)
	{
		$this->type_of_deductions = $type_of_deductions;
	}
	
	public function getDeduction_Percentage()
	{
		return $this->deduction_percentage;
	}
	
	public function setDeduction_Percentage($deduction_percentage)
	{
		$this->deduction_percentage = $deduction_percentage;
	}
	
	public function getDescription()
	{
		return $this->description;
	}
	
	public function setDescription($description)
	{
		$this->description = $description;
	}
}
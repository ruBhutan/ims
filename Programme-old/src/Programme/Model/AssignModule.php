<?php

namespace Programme\Model;

class AssignModule
{
	protected $id;
	protected $module_tutor;
	protected $module_tutor_2;
	protected $module_tutor_3;
	protected $module_coordinator;
	protected $year;
	protected $academic_modules_allocation_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getModule_Tutor()
	{
		return $this->module_tutor;
	}
	
	public function setModule_Tutor($module_tutor)
	{
		$this->module_tutor = $module_tutor;
	}
	
	public function getModule_Tutor_2()
	{
		return $this->module_tutor_2;
	}
	
	public function setModule_Tutor_2($module_tutor_2)
	{
		$this->module_tutor_2 = $module_tutor_2;
	}
	
	public function getModule_Tutor_3()
	{
		return $this->module_tutor_3;
	}
	
	public function setModule_Tutor_3($module_tutor_3)
	{
		$this->module_tutor_3 = $module_tutor_3;
	}
	
	public function getModule_Coordinator()
	{
		return $this->module_coordinator;
	}
	
	public function setModule_Coordinator($module_coordinator)
	{
		$this->module_coordinator = $module_coordinator;
	}
	
	public function getYear()
	{
		return $this->year;
	}
	
	public function setYear($year)
	{
		$this->year = $year;
	}
	
	public function getAcademic_Modules_Allocation_Id()
	{
		return $this->academic_modules_allocation_id;
	}
	
	public function setAcademic_Modules_Allocation_Id($academic_modules_allocation_id)
	{
		$this->academic_modules_allocation_id = $academic_modules_allocation_id;
	}
	
}
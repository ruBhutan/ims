<?php

namespace Reassessment\Model;

class Reassessment
{
	protected $id;
	protected $application_date;
	protected $academic_modules_allocation_id;
	protected $student_id;
	
	 
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getApplication_Date()
	{
		return $this->application_date;
	}
	
	public function setApplication_Date($application_date)
	{
		$this->application_date = $application_date;
	}
	
	public function getAcademic_Modules_Allocation_Id()
	{
		return $this->academic_modules_allocation_id;
	}
	
	public function setAcademic_Modules_Allocation_Id($academic_modules_allocation_id)
	{
		$this->academic_modules_allocation_id = $academic_modules_allocation_id;
	}
	
	public function getStudent_Id()
	{
		return $this->student_id;
	}
	
	public function setStudent_Id($student_id)
	{
		$this->student_id = $student_id;
	}
	 
}
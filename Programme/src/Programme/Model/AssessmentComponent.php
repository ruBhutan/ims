<?php

namespace Programme\Model;

class AssessmentComponent
{
	protected $id;
	protected $assessment;
	protected $weightage;
	protected $remarks;
	protected $programmes_id;
	//protected $academic_modules_allocation_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getAssessment()
	{
		return $this->assessment;
	}
	
	public function setAssessment($assessment)
	{
		$this->assessment = $assessment;
	}
	
	public function getWeightage()
	{
		return $this->weightage;
	}
	
	public function setWeightage($weightage)
	{
		$this->weightage = $weightage;
	}
	
	public function getRemarks()
	{
		return $this->remarks;
	}
	
	public function setRemarks($remarks)
	{
		$this->remarks = $remarks;
	}
	
	public function getProgrammes_Id()
	{
		return $this->programmes_id;
	}
	
	public function setProgrammes_Id($programmes_id)
	{
		$this->programmes_id = $programmes_id;
	}
	
	/*
	public function getAcademic_Modules_Allocation_Id()
	{
		return $this->academic_modules_allocation_id;
	}
	
	public function setAcademic_Modules_Allocation_Id($academic_modules_allocation_id)
	{
		$this->academic_modules_allocation_id = $academic_modules_allocation_id;
	}
	*/
}
<?php

namespace Programme\Model;

class ContinuousAssessment
{
	protected $id;
	//protected $date;
	//protected $assessment_type;
	protected $assessment;
	protected $date_of_submission;
	protected $assessment_marks;
	protected $assessment_weightage;
	protected $remarks;
	protected $assessment_component_id;
	protected $academic_modules_allocation_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	/* 
	public function getDate()
	{
		return $this->date;
	}
	
	public function setDate($date)
	{
		$this->date = $date;
	}
	*/
	public function getAssessment()
	{
		return $this->assessment;
	}
	
	public function setAssessment($assessment)
	{
		$this->assessment = $assessment;
	}
	/*
	public function getAssessment_Type()
	{
		return $this->assessment_type;
	}
	
	public function setAssessment_Type($assessment_type)
	{
		$this->assessment_type = $assessment_type;
	}
	*/	
	public function getDate_Submission()
	{
		return $this->date_of_submission;
	}
	
	public function setDate_Submission($date_of_submission)
	{
		$this->date_of_submission = $date_of_submission;
	}
	
	public function getAssessment_Marks()
	{
		return $this->assessment_marks;
	}
	
	public function setAssessment_Marks($assessment_marks)
	{
		$this->assessment_marks = $assessment_marks;
	}
	
	public function getAssessment_Weightage()
	{
		return $this->assessment_weightage;
	}
	
	public function setAssessment_Weightage($assessment_weightage)
	{
		$this->assessment_weightage = $assessment_weightage;
	}
	
	public function getRemarks()
	{
		return $this->remarks;
	}
	
	public function setRemarks($remarks)
	{
		$this->remarks = $remarks;
	}
		
	public function getAssessment_Component_Id()
	{
		return $this->assessment_component_id;
	}
	
	public function setAssessment_Component_Id($assessment_component_id)
	{
		$this->assessment_component_id = $assessment_component_id;
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
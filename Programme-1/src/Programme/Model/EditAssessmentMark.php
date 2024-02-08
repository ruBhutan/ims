<?php

namespace Programme\Model;

class EditAssessmentMark
{
	protected $id;
	protected $student_id;
	protected $assessment_id;
	protected $assessment_marks;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getStudent_Id()
	{
		return $this->student_id;
	}
	
	public function setStudent_Id($student_id)
	{
		$this->student_id = $student_id;
	}
	
	public function getAssessment_Id()
	{
		return $this->assessment_id;
	}
	
	public function setAssessment_Id($assessment_id)
	{
		$this->assessment_id = $assessment_id;
	}
	
	public function getAssessment_Marks()
	{
		return $this->assessment_marks;
	}
	
	public function setAssessment_Marks($assessment_marks)
	{
		$this->assessment_marks = $assessment_marks;
	}
}
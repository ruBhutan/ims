<?php

namespace Examinations\Model;

class ExamInvigilator
{
	protected $id;
	protected $employee_details_id;
	protected $exam_reliever_id;
	protected $examination_timetable_id;
	protected $organisation_id;
	 
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getEmployee_Details_Id()
	{
		return $this->employee_details_id;
	}
	
	public function setEmployee_Details_Id($employee_details_id)
	{
		$this->employee_details_id = $employee_details_id;
	}
	
	public function getExam_Reliever_Id()
	{
		return $this->exam_reliever_id;
	}
	
	public function setExam_Reliever_Id($exam_reliever_id)
	{
		$this->exam_reliever_id = $exam_reliever_id;
	}
	
	public function getExamination_Timetable_Id()
	{
		return $this->examination_timetable_id;
	}
	
	public function setExamination_Timetable_Id($examination_timetable_id)
	{
		$this->examination_timetable_id = $examination_timetable_id;
	}
	
	public function getOrganisation_Id()
	{
		return $this->organisation_id;
	}
	
	public function setOrganisation_Id($organisation_id)
	{
		$this->organisation_id = $organisation_id;
	}
	 
}
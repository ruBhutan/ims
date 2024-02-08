<?php

namespace Responsibilities\Model;

class StudentResponsibility
{
	protected $id;
	protected $start_date;
	protected $end_date;
	protected $remarks;
	protected $student_id;
	protected $responsibility_category_id;
	protected $evidence_file;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getStart_Date()
	{
		return $this->start_date;
	}
	
	public function setStart_Date($start_date)
	{
		$this->start_date = $start_date;
	}
	
	public function getEnd_Date()
	{
		return $this->end_date;
	}
	
	public function setEnd_Date($end_date)
	{
		$this->end_date = $end_date;
	}
	
	public function getRemarks()
	{
		return $this->remarks;
	}
	
	public function setRemarks($remarks)
	{
		$this->remarks = $remarks;
	}
	
	public function getStudent_Id()
	{
		return $this->student_id;
	}
	
	public function setStudent_Id($student_id)
	{
		$this->student_id = $student_id;
	}
	
	public function getResponsibility_Category_Id()
	{
		return $this->responsibility_category_id;
	}
	
	public function setResponsibility_Category_Id($responsibility_category_id)
	{
		$this->responsibility_category_id = $responsibility_category_id;
	}

	public function getEvidence_File()
	{
		return $this->evidence_file;
	}
	
	public function setEvidence_File($evidence_file)
	{
		$this->evidence_file = $evidence_file;
	}
}
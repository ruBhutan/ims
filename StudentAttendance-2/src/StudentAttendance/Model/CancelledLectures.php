<?php

namespace StudentAttendance\Model;

class CancelledLectures
{
	protected $id;
	protected $lecture_date;
	protected $reasons;
	protected $academic_modules_allocation_id;
	 
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getLecture_Date()
	{
		return $this->lecture_date;
	}
	
	public function setLecture_Date($lecture_date)
	{
		$this->lecture_date = $lecture_date;
	}
	
	public function getReasons()
	{
		return $this->reasons;
	}
	
	public function setReasons($reasons)
	{
		$this->reasons = $reasons;
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
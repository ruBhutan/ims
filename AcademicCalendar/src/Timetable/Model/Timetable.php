<?php

namespace Timetable\Model;

class Timetable
{
	protected $id;
	protected $day;
	protected $classroom;
	protected $from_time;
	protected $to_time;
	protected $group;
	protected $academic_year;
	protected $semester;
	protected $status;
	protected $programmes_id;
	protected $academic_modules_allocation_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getClassroom()
	{
		return $this->classroom;
	}
	
	public function setClassroom($classroom)
	{
		$this->classroom = $classroom;
	}
	
	public function getDay()
	{
		return $this->day;
	}
	
	public function setDay($day)
	{
		$this->day = $day;
	}
	
	public function getFrom_Time()
	{
		return $this->from_time;
	}
	
	public function setFrom_Time($from_time)
	{
		$this->from_time = $from_time;
	}
	
	public function getTo_Time()
	{
		return $this->to_time;
	}
	
	public function setTo_Time($to_time)
	{
		$this->to_time = $to_time;
	}
	
	public function getSemester()
	{
		return $this->semester;
	}
	
	public function setSemester($semester)
	{
		$this->semester = $semester;
	}
	
	public function getAcademic_Year()
	{
		return $this->academic_year;
	}
	
	public function setAcademic_Year($academic_year)
	{
		$this->academic_year = $academic_year;
	}
	
	public function getGroup()
	{
		return $this->group;
	}
	
	public function setGroup($group)
	{
		$this->group = $group;
	}
	
	public function getStatus()
	{
		return $this->status;
	}
	
	public function setStatus($status)
	{
		$this->status = $status;
	}
	
	public function getProgrammes_Id()
	{
		return $this->programmes_id;
	}
	
	public function setProgrammes_Id($programmes_id)
	{
		$this->programmes_id = $programmes_id;
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
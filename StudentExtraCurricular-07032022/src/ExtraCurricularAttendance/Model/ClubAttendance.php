<?php

namespace ExtraCurricularAttendance\Model;

class ClubAttendance
{
	protected $id;
	protected $date;
	protected $student_clubs_id;
	protected $attendance;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getDate()
	{
		return $this->date;
	}
	
	public function setDate($date)
	{
		$this->date = $date;
	}
	
	public function getStudent_Clubs_Id()
	{
		return $this->student_clubs_id;
	}
	
	public function setStudent_Clubs_Id($student_clubs_id)
	{
		$this->student_clubs_id = $student_clubs_id;
	}
	 
	public function getAttendance()
	{
		return $this->attendance;
	}
	
	public function setAttendance($attendance)
	{
		$this->attendance = $attendance;
	}
}
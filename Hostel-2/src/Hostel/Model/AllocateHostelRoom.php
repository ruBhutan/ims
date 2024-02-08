<?php

namespace Hostel\Model;

class AllocateHostelRoom
{
	protected $id;
	protected $hostel_rooms_id;
	protected $student_id;
	protected $year;
		
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getHostel_Rooms_Id()
	{
		return $this->hostel_rooms_id;
	}
	
	public function setHostel_Rooms_Id($hostel_rooms_id)
	{
		$this->hostel_rooms_id = $hostel_rooms_id;
	}
	
	public function getStudent_Id()
	{
		return $this->student_id;
	}
	
	public function setStudent_Id($student_id)
	{
		$this->student_id = $student_id;
	}
	
	public function getYear()
	{
		return $this->year;
	}
	
	public function setYear($year)
	{
		$this->year = $year;
	}
}
<?php

namespace Clubs\Model;

class ClubsApplication
{
	protected $id;
	protected $reasons;
	protected $status;
	protected $student_id;
	protected $clubs_id;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getReasons()
	{
		return $this->reasons;
	}
	
	public function setReasons($reasons)
	{
		$this->reasons = $reasons;
	}
	
	public function getStatus()
	{
		return $this->status;
	}
	
	public function setStatus($status)
	{
		$this->status = $status;
	}
	
	public function getStudent_Id()
	{
		return $this->student_id;
	}
	
	public function setStudent_Id($student_id)
	{
		$this->student_id = $student_id;
	}
	
	public function getClubs_Id()
	{
		return $this->clubs_id;
	}
	
	public function setClubs_Id($clubs_id)
	{
		$this->clubs_id = $clubs_id;
	}
}
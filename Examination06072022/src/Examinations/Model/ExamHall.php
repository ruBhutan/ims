<?php

namespace Examinations\Model;

class ExamHall
{
	protected $id;
	protected $hall_no;
	protected $hall_name;
	protected $no_seats;
	protected $organisation_id;
	 
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getHall_No()
	{
		return $this->hall_no;
	}
	
	public function setHall_No($hall_no)
	{
		$this->hall_no = $hall_no;
	}
	
	public function getHall_Name()
	{
		return $this->hall_name;
	}
	
	public function setHall_Name($hall_name)
	{
		$this->hall_name = $hall_name;
	}
	
	public function getNo_Seats()
	{
		return $this->no_seats;
	}
	
	public function setNo_Seats($no_seats)
	{
		$this->no_seats = $no_seats;
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
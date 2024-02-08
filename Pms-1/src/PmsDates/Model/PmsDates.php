<?php

namespace PmsDates\Model;

class PmsDates
{
	protected $id;
	protected $pms_year;
	protected $date_for;
	protected $start_date;
	protected $end_date;
	protected $date_range;
	
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getPms_Year()
	{
		return $this->pms_year;
	}
	
	public function setPms_Year($pms_year)
	{
		$this->pms_year = $pms_year;
	}
	
	public function getDate_For()
	{
		return $this->date_for;
	}
	
	public function setDate_For($date_for)
	{
		$this->date_for = $date_for;
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
	
	public function getDate_Range()
	{
		return $this->date_range;
	}
	
	public function setDate_Range($date_range)
	{
		$this->date_range = $date_range;
	}
}
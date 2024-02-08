<?php

namespace Planning\Model;

class ApaActivation
{
	protected $id;
	protected $apa_year;
	protected $apa_type;
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
	 	
	public function getApa_Year()
	{
		return $this->apa_year;
	}
	
	public function setApa_Year($apa_year)
	{
		$this->apa_year = $apa_year;
	}
	
	public function getApa_Type()
	{
		return $this->apa_type;
	}
	
	public function setApa_Type($apa_type)
	{
		$this->apa_type = $apa_type;
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
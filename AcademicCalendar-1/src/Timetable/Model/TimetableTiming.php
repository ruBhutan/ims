<?php

namespace Timetable\Model;

class TimetableTiming
{
	protected $id;
	protected $from_time;
	protected $to_time;
	protected $organisation_id;
	 
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
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
	
	/*
	public function getTimes()
	{
		return $this->times;
	}
	
	public function setTimes($times)
	{
		$this->times = $times;
	}
	*/
	public function getOrganisation_Id()
	{
		return $this->organisation_id;
	}
	
	public function setOrganisation_Id($organisation_id)
	{
		$this->organisation_id = $organisation_id;
	}
	 
}
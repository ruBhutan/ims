<?php

namespace Planning\Model;

class FiveYearPlan
{
	protected $id;
	protected $five_year_plan;
	protected $from_date;
	protected $to_date;
	protected $date_range;
 	 
	public function getId()
	{
		return $this->id;
	}
	 
	public function setId($id)
	{
		$this->id = $id;
	}
	 
	public function getFive_Year_Plan()
	{
		return $this->five_year_plan;
	}
	
	public function setFive_Year_Plan($five_year_plan)
	{
		$this->five_year_plan = $five_year_plan;
	}
	
	public function getFrom_Date()
	{
		return $this->from_date;
	}
	
	public function setFrom_Date($from_date)
	{
		$this->from_date = $from_date;
	}
	
	public function getTo_Date()
	{
		return $this->to_date;
	}
	
	public function setTo_Date($to_date)
	{
		$this->to_date = $to_date;
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

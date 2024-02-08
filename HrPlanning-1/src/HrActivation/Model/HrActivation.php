<?php

namespace HrActivation\Model;

class HrActivation
{
	protected $id;
	protected $hr_proposal_type;
	protected $five_year_plan;
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
	 
	public function getHr_Proposal_Type()
	{
		return $this->hr_proposal_type;
	}
	
	public function setHr_Proposal_Type($hr_proposal_type)
	{
		$this->hr_proposal_type = $hr_proposal_type;
	}
	
	public function getFive_Year_Plan()
	{
		return $this->five_year_plan;
	}
	
	public function setFive_Year_Plan($five_year_plan)
	{
		$this->five_year_plan = $five_year_plan;
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